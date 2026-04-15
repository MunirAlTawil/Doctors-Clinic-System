<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\ClinicSetting;
use App\Models\DoctorAvailability;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $doctorId = Auth::id();
        $date = $request->string('date')->toString();
        $status = $request->string('status')->toString();

        $appointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->when($date !== '', fn ($q) => $q->where('appointment_date', $date), fn ($q) => $q->whereDate('appointment_date', '>=', now()->toDateString()))
            ->when($status !== '', fn ($q) => $q->where('status', $status), fn ($q) => $q->where('status', 'booked'))
            ->with('patient')
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return view('doctor.appointments.index', [
            'appointments' => $appointments,
            'date' => $date,
            'status' => $status,
        ]);
    }

    public function edit(Appointment $appointment): View|RedirectResponse
    {
        $doctorId = Auth::id();
        abort_unless($appointment->doctor_id === $doctorId, 403);
        abort_unless($appointment->status === 'booked', 403);

        $doctor = User::query()
            ->whereKey($doctorId)
            ->with('doctorSpecialties')
            ->firstOrFail();

        $specialtyIds = $doctor->doctorSpecialties->pluck('id')->all();
        $specialties = Specialty::query()
            ->where('is_active', true)
            ->whereIn('id', $specialtyIds)
            ->orderBy('name')
            ->get();

        if ($specialties->isEmpty()) {
            return redirect()
                ->route('doctor.appointments.index')
                ->withErrors(['specialty_id' => 'Add at least one active specialty in your profile before editing appointments.']);
        }

        $appointment->load('patient');

        return view('doctor.appointments.edit', [
            'appointment' => $appointment,
            'specialties' => $specialties,
        ]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctorId = Auth::id();
        abort_unless($appointment->doctor_id === $doctorId, 403);
        abort_unless($appointment->status === 'booked', 403);

        $doctor = User::with('doctorProfile')->whereKey($doctorId)->firstOrFail();
        if (! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
            return back()->withErrors(['doctor' => 'Your profile is not approved.'])->withInput();
        }

        $data = $request->validate([
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_phone' => ['nullable', 'string', 'max:50'],
            'patient_gender' => ['nullable', 'in:male,female'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'regex:/^\d{2}:\d{2}$/'],
            'hours_count' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        if (! $doctor->doctorSpecialties()->where('specialties.id', (int) $data['specialty_id'])->exists()) {
            return back()->withErrors(['specialty_id' => 'Pick one of your specialties.'])->withInput();
        }

        $startTime = Carbon::parse($data['start_time'])->format('H:i');
        $hours = (int) $data['hours_count'];
        $slots = $this->buildHourlySlotRows($startTime, $hours);
        $lastStart = $slots[array_key_last($slots)]['start'];
        $endTime = Carbon::createFromFormat('H:i', $lastStart)->addHour()->format('H:i');

        foreach ($slots as $row) {
            $slotStart = Carbon::createFromFormat('H:i', $row['start']);
            $slotEnd = Carbon::createFromFormat('H:i', $row['end']);

            $availableForHour = DoctorAvailability::query()
                ->where('doctor_id', $doctor->id)
                ->where('work_date', $data['appointment_date'])
                ->where('is_available', true)
                ->where('start_time', '<=', $row['start'])
                ->where('end_time', '>=', $row['end'])
                ->exists();

            if (! $availableForHour) {
                return back()->withErrors(['start_time' => 'Selected time is outside your working hours for that date.'])->withInput();
            }

            $insideBreak = DoctorAvailability::query()
                ->where('doctor_id', $doctor->id)
                ->where('work_date', $data['appointment_date'])
                ->where('is_available', false)
                ->where('start_time', '<=', $row['start'])
                ->where('end_time', '>=', $row['end'])
                ->exists();

            if ($insideBreak) {
                return back()->withErrors(['start_time' => 'Selected hours overlap your break on that date.'])->withInput();
            }
        }

        $slotStarts = collect($slots)->pluck('start')->all();
        $conflict = AppointmentSlot::query()
            ->where('doctor_id', $doctor->id)
            ->where('slot_date', $data['appointment_date'])
            ->whereIn('slot_start', $slotStarts)
            ->where('appointment_id', '!=', $appointment->id)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['start_time' => 'One or more of those hours are already booked.'])->withInput();
        }

        $hourlyRate = (float) $doctor->doctorProfile->hourly_rate;
        $total = round($hourlyRate * $hours, 2);
        $profitPercent = (float) (ClinicSetting::first()?->admin_profit_percent ?? 15);
        $profitAmount = round(($total * $profitPercent) / 100, 2);
        $doctorNet = round($total - $profitAmount, 2);

        $patient = $appointment->patient;
        if (! $patient || ! $patient->isPatient()) {
            return back()->withErrors(['patient_name' => 'Invalid patient record.'])->withInput();
        }

        DB::transaction(function () use (
            $appointment,
            $patient,
            $data,
            $startTime,
            $hours,
            $endTime,
            $hourlyRate,
            $total,
            $profitPercent,
            $profitAmount,
            $doctorNet,
            $slots,
            $doctor
        ): void {
            $patient->update([
                'name' => $data['patient_name'],
                'phone' => $data['patient_phone'] ?? null,
                'gender' => $data['patient_gender'] ?? null,
            ]);

            AppointmentSlot::query()->where('appointment_id', $appointment->id)->delete();

            $appointment->update([
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
            ]);

            foreach ($slots as $row) {
                AppointmentSlot::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $doctor->id,
                    'slot_date' => $data['appointment_date'],
                    'slot_start' => $row['start'],
                    'slot_end' => $row['end'],
                ]);
            }
        });

        return redirect()
            ->route('doctor.appointments.index')
            ->with('success', 'Appointment updated.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctorId = Auth::id();

        abort_unless($appointment->doctor_id === $doctorId, 403);

        $data = $request->validate([
            'status' => ['required', 'in:completed,canceled'],
        ]);

        $appointment->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Appointment status updated.');
    }

    /**
     * @return list<array{start: string, end: string}>
     */
    private function buildHourlySlotRows(string $startH, int $hours): array
    {
        $rows = [];
        $base = Carbon::createFromFormat('H:i', $startH);
        for ($i = 0; $i < $hours; $i++) {
            $start = $base->copy()->addHours($i)->format('H:i');
            $end = $base->copy()->addHours($i + 1)->format('H:i');
            $rows[] = ['start' => $start, 'end' => $end];
        }

        return $rows;
    }
}
