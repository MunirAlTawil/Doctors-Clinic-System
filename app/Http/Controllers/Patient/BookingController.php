<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\ClinicSetting;
use App\Models\DoctorAvailability;
use App\Models\Specialty;
use App\Models\User;
use App\Notifications\SystemMessageNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function create(Request $request): View
    {
        $specialties = Specialty::where('is_active', true)->orderBy('name')->get();

        $selectedSpecialtyId = $request->string('specialty_id')->toString();
        $selectedDoctorId = $request->string('doctor_id')->toString();
        $selectedDate = $request->string('appointment_date')->toString();
        $doctorSearch = $request->string('doctor_search')->toString();

        $doctorsQuery = User::query()
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', fn ($q) => $q->where('status', 'approved'))
            ->with(['doctorProfile', 'doctorSpecialties']);

        if ($selectedSpecialtyId !== '') {
            $doctorsQuery->whereHas('doctorSpecialties', fn ($q) => $q->where('specialties.id', (int) $selectedSpecialtyId));
        }

        if ($doctorSearch !== '') {
            $doctorsQuery->where(function ($q) use ($doctorSearch) {
                $q->where('name', 'like', "%{$doctorSearch}%")
                    ->orWhere('email', 'like', "%{$doctorSearch}%");
            });
        }

        if ($selectedDoctorId !== '') {
            $doctorsQuery->where('id', (int) $selectedDoctorId);
        }

        $doctors = $doctorsQuery->get();

        $availableHourSlots = [];

        if ($selectedSpecialtyId !== '' && $selectedDoctorId !== '' && $selectedDate !== '') {
            $doctor = User::with(['doctorProfile', 'doctorSpecialties'])
                ->where('role', 'doctor')
                ->findOrFail((int) $selectedDoctorId);

            if (! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
                $availableHourSlots = [];
            } elseif ($selectedSpecialtyId !== '' && ! $doctor->doctorSpecialties->pluck('id')->contains((int) $selectedSpecialtyId)) {
                $availableHourSlots = [];
            } else {
                $availableIntervals = DoctorAvailability::query()
                    ->where('doctor_id', $doctor->id)
                    ->where('work_date', $selectedDate)
                    ->where('is_available', true)
                    ->orderBy('start_time')
                    ->get();

                $breakIntervals = DoctorAvailability::query()
                    ->where('doctor_id', $doctor->id)
                    ->where('work_date', $selectedDate)
                    ->where('is_available', false)
                    ->orderBy('start_time')
                    ->get();

                $candidateSet = [];
                foreach ($availableIntervals as $availability) {
                    if (! $availability->start_time || ! $availability->end_time) {
                        continue;
                    }

                    $intervalStart = Carbon::parse($availability->start_time)->setDate(2000, 1, 1);
                    $intervalEnd = Carbon::parse($availability->end_time)->setDate(2000, 1, 1);

                    for ($n = 0; $n < 24; $n++) {
                        $candidateStart = (clone $intervalStart)->addHours($n);
                        $candidateEnd = (clone $candidateStart)->addHour();

                        if ($candidateStart->greaterThanOrEqualTo($intervalEnd)) {
                            break;
                        }

                        if ($candidateEnd->greaterThan($intervalEnd)) {
                            break;
                        }

                        $requestedAt = Carbon::parse($selectedDate.' '.$candidateStart->format('H:i'));
                        if ($requestedAt->lessThanOrEqualTo(now())) {
                            continue;
                        }

                        $blockedByBreak = false;
                        foreach ($breakIntervals as $breakInterval) {
                            $breakStart = Carbon::parse($breakInterval->start_time)->setDate(2000, 1, 1);
                            $breakEnd = Carbon::parse($breakInterval->end_time)->setDate(2000, 1, 1);
                            if ($candidateStart->greaterThanOrEqualTo($breakStart) && $candidateEnd->lessThanOrEqualTo($breakEnd)) {
                                $blockedByBreak = true;
                                break;
                            }
                        }
                        if ($blockedByBreak) {
                            continue;
                        }

                        $conflict = AppointmentSlot::where('doctor_id', $doctor->id)
                            ->where('slot_date', $selectedDate)
                            ->where('slot_start', $candidateStart->format('H:i'))
                            ->exists();

                        if (! $conflict) {
                            $candidateSet[$candidateStart->format('H:i')] = true;
                        }
                    }
                }

                $availableHourSlots = array_keys($candidateSet);
                sort($availableHourSlots);
            }
        }

        return view('patient.bookings.create', [
            'specialties' => $specialties,
            'doctors' => $doctors,
            'selectedSpecialtyId' => $selectedSpecialtyId,
            'selectedDoctorId' => $selectedDoctorId,
            'selectedDate' => $selectedDate,
            'doctorSearch' => $doctorSearch,
            'availableHourSlots' => $availableHourSlots,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'specialty_id' => ['required', 'exists:specialties,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'appointment_date' => ['required', 'date'],
            'selected_slots' => ['required', 'array', 'min:1', 'max:8'],
            'selected_slots.*' => ['required', 'regex:/^\\d{2}:\\d{2}$/'],
            'payment_method' => ['required', 'in:cash,card'],
            'card_type' => ['nullable', 'in:visa,mastercard'],
            'cardholder_name' => ['nullable', 'string', 'max:255'],
            'card_number' => ['nullable', 'string', 'min:12', 'max:19'],
            'expiry_month' => ['nullable', 'string', 'size:2'],
            'expiry_year' => ['nullable', 'string', 'size:2'],
        ], [
            'selected_slots.required' => 'Please select at least one hour.',
            'selected_slots.min' => 'Please select at least one hour.',
            'selected_slots.max' => 'Maximum 8 hours per booking.',
        ]);

        $doctor = User::with('doctorProfile')->findOrFail($data['doctor_id']);
        if (! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
            return back()->withErrors(['doctor_id' => 'This doctor is not approved.'])->withInput();
        }

        $selectedSlots = collect($data['selected_slots'])
            ->map(fn ($slot) => Carbon::parse($slot)->format('H:i'))
            ->unique()
            ->sort()
            ->values();

        if ($selectedSlots->isEmpty()) {
            return back()->withErrors(['selected_slots' => 'Please select booking hours.'])->withInput();
        }

        $startTime = $selectedSlots->first();
        $hours = $selectedSlots->count();
        $endTime = Carbon::createFromFormat('H:i', $selectedSlots->last())->addHour()->format('H:i');

        for ($i = 1; $i < $selectedSlots->count(); $i++) {
            $prev = Carbon::createFromFormat('H:i', $selectedSlots[$i - 1]);
            $current = Carbon::createFromFormat('H:i', $selectedSlots[$i]);
            if ($prev->copy()->addHour()->format('H:i') !== $current->format('H:i')) {
                return back()->withErrors(['selected_slots' => 'Please select consecutive hours only.'])->withInput();
            }
        }

        $requestedAt = Carbon::parse($data['appointment_date'].' '.$startTime);
        if ($requestedAt->lessThanOrEqualTo(now())) {
            return back()->withErrors(['appointment_date' => 'Cannot book in the past.'])->withInput();
        }

        if (! $doctor->doctorSpecialties()->where('specialties.id', (int) $data['specialty_id'])->exists()) {
            return back()->withErrors(['specialty_id' => 'This specialty is not linked to the selected doctor.'])->withInput();
        }

        $slots = [];
        foreach ($selectedSlots as $slotStartTime) {
            $slotStart = Carbon::createFromFormat('H:i', $slotStartTime);
            $slotEnd = $slotStart->copy()->addHour();
            $slots[] = ['start' => $slotStart->format('H:i'), 'end' => $slotEnd->format('H:i')];

            $availableForHour = DoctorAvailability::query()
                ->where('doctor_id', $doctor->id)
                ->where('work_date', $data['appointment_date'])
                ->where('is_available', true)
                ->where('start_time', '<=', $slotStart->format('H:i'))
                ->where('end_time', '>=', $slotEnd->format('H:i'))
                ->exists();

            if (! $availableForHour) {
                return back()->withErrors(['selected_slots' => 'Some selected hours are outside the doctor working hours.'])->withInput();
            }

            $insideBreak = DoctorAvailability::query()
                ->where('doctor_id', $doctor->id)
                ->where('work_date', $data['appointment_date'])
                ->where('is_available', false)
                ->where('start_time', '<=', $slotStart->format('H:i'))
                ->where('end_time', '>=', $slotEnd->format('H:i'))
                ->exists();

            if ($insideBreak) {
                return back()->withErrors(['selected_slots' => 'Selected hours include the doctor break period.'])->withInput();
            }
        }

        $conflict = AppointmentSlot::where('doctor_id', $doctor->id)
            ->where('slot_date', $data['appointment_date'])
            ->whereIn('slot_start', collect($slots)->pluck('start'))
            ->exists();

        if ($conflict) {
            return back()->withErrors(['selected_slots' => 'One or more selected hours are already booked.'])->withInput();
        }

        $hourlyRate = (float) $doctor->doctorProfile->hourly_rate;
        $total = $hourlyRate * $hours;
        $profitPercent = (float) ClinicSetting::first()?->admin_profit_percent ?? 15;
        $profitAmount = ($total * $profitPercent) / 100;
        $doctorNet = $total - $profitAmount;

        $appointment = DB::transaction(function () use ($data, $doctor, $hourlyRate, $total, $profitPercent, $profitAmount, $doctorNet, $hours, $endTime, $slots, $startTime) {
            $appointment = Appointment::create([
                'patient_id' => Auth::id(),
                'doctor_id' => $doctor->id,
                'specialty_id' => $data['specialty_id'],
                'appointment_date' => $data['appointment_date'],
                'start_time' => $startTime,
                'hours_count' => $hours,
                'end_time' => $endTime,
                'hourly_rate' => $hourlyRate,
                'total_amount' => $total,
                'admin_profit_percent' => $profitPercent,
                'admin_profit_amount' => $profitAmount,
                'doctor_net_amount' => $doctorNet,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'paid',
                'status' => 'booked',
            ]);

            foreach ($slots as $slot) {
                AppointmentSlot::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $doctor->id,
                    'slot_date' => $data['appointment_date'],
                    'slot_start' => $slot['start'],
                    'slot_end' => $slot['end'],
                ]);
            }

            if ($data['payment_method'] === 'card') {
                $appointment->cardPayment()->create([
                    'card_type' => $data['card_type'] ?? 'visa',
                    'cardholder_name' => $data['cardholder_name'] ?? 'CARD USER',
                    'card_number_last4' => substr(($data['card_number'] ?? '0000'), -4),
                    'expiry_month' => $data['expiry_month'] ?? '01',
                    'expiry_year' => $data['expiry_year'] ?? '30',
                    'status' => 'paid',
                ]);
            }

            return $appointment;
        });

        $patient = Auth::user();
        $specialtyName = Specialty::whereKey((int) $data['specialty_id'])->value('name') ?? '';

        Notification::send(
            User::activeAdmins()->get(),
            new SystemMessageNotification(
                'New booking',
                'Patient '.$patient->name.' ('.$patient->email.') booked appointment #'.$appointment->id.' with Dr. '.$doctor->name.' — specialty: '.$specialtyName.' — date: '.$data['appointment_date'].' — starts '.$startTime.' — hours: '.$hours.'.',
                'info'
            )
        );

        return redirect()->route('bookings.create')->with('success', 'Appointment booked successfully.');
    }
}
