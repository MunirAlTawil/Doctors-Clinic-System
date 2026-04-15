<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\CardPayment;
use App\Models\ClinicSetting;
use App\Models\DoctorAvailability;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Populates appointments, appointment_slots, and optionally card_payments.
 * Respects doctor specialty links, availability windows, break intervals, and unique slot constraint.
 *
 * Not seeded: notifications (Laravel can regenerate via app events), sessions, jobs, cache.
 */
class DemoAppointmentAndPaymentSeeder extends Seeder
{
    /** @var array<string, true> */
    private array $occupiedSlotKeys = [];

    public function run(): void
    {
        $this->occupiedSlotKeys = [];

        // Clear demo-generated booking data only (safe when migrating fresh; avoids orphan slots if re-run alone).
        AppointmentSlot::query()->delete();
        CardPayment::query()->delete();
        Appointment::query()->delete();

        $profitPercent = (float) (ClinicSetting::first()?->admin_profit_percent ?? 15);

        $plans = [
            ['doctor' => 'demo-dr-1@seed-placeholder.invalid', 'patient' => 'patient@example.com', 'specialty' => 'Cardiology', 'offset' => -18, 'start' => '10:00', 'hours' => 2, 'payment_method' => 'cash', 'status' => 'completed'],
            ['doctor' => 'demo-dr-1@seed-placeholder.invalid', 'patient' => 'demo-pt-1@seed-placeholder.invalid', 'specialty' => 'Cardiology', 'offset' => -12, 'start' => '09:00', 'hours' => 1, 'payment_method' => 'card', 'status' => 'completed', 'card' => ['type' => 'visa', 'holder' => 'Noura Al-Qahtani', 'last4' => '4242']],
            ['doctor' => 'demo-dr-2@seed-placeholder.invalid', 'patient' => 'patient@example.com', 'specialty' => 'Orthopedics', 'offset' => -8, 'start' => '11:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'completed'],
            ['doctor' => 'demo-dr-2@seed-placeholder.invalid', 'patient' => 'demo-pt-2@seed-placeholder.invalid', 'specialty' => 'Orthopedics', 'offset' => -5, 'start' => '10:00', 'hours' => 3, 'payment_method' => 'card', 'status' => 'completed', 'card' => ['type' => 'mastercard', 'holder' => 'Khalid Al-Otaibi', 'last4' => '5588']],
            ['doctor' => 'demo-dr-3@seed-placeholder.invalid', 'patient' => 'demo-pt-1@seed-placeholder.invalid', 'specialty' => 'Dermatology', 'offset' => -3, 'start' => '14:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'completed'],
            ['doctor' => 'demo-dr-4@seed-placeholder.invalid', 'patient' => 'patient@example.com', 'specialty' => 'Pediatrics', 'offset' => -22, 'start' => '09:00', 'hours' => 2, 'payment_method' => 'cash', 'status' => 'completed'],
            ['doctor' => 'demo-dr-4@seed-placeholder.invalid', 'patient' => 'demo-pt-2@seed-placeholder.invalid', 'specialty' => 'Pediatrics', 'offset' => -15, 'start' => '10:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'canceled'],
            ['doctor' => 'demo-dr-5@seed-placeholder.invalid', 'patient' => 'demo-pt-1@seed-placeholder.invalid', 'specialty' => 'Neurology', 'offset' => -7, 'start' => '11:00', 'hours' => 2, 'payment_method' => 'card', 'status' => 'completed', 'card' => ['type' => 'visa', 'holder' => 'Noura Al-Qahtani', 'last4' => '1099']],
            ['doctor' => 'demo-dr-3@seed-placeholder.invalid', 'patient' => 'patient@example.com', 'specialty' => 'Dermatology', 'offset' => 4, 'start' => '11:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'booked'],
            ['doctor' => 'demo-dr-5@seed-placeholder.invalid', 'patient' => 'patient@example.com', 'specialty' => 'Neurology', 'offset' => 6, 'start' => '14:00', 'hours' => 2, 'payment_method' => 'card', 'status' => 'booked', 'card' => ['type' => 'mastercard', 'holder' => 'Test Patient', 'last4' => '7712']],
            ['doctor' => 'demo-dr-2@seed-placeholder.invalid', 'patient' => 'demo-pt-1@seed-placeholder.invalid', 'specialty' => 'Rheumatology', 'offset' => 9, 'start' => '12:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'booked'],
            ['doctor' => 'demo-dr-1@seed-placeholder.invalid', 'patient' => 'demo-pt-2@seed-placeholder.invalid', 'specialty' => 'General Internal Medicine', 'offset' => 11, 'start' => '15:00', 'hours' => 2, 'payment_method' => 'cash', 'status' => 'booked'],
            ['doctor' => 'demo-dr-4@seed-placeholder.invalid', 'patient' => 'demo-pt-2@seed-placeholder.invalid', 'specialty' => 'General Internal Medicine', 'offset' => 14, 'start' => '11:00', 'hours' => 1, 'payment_method' => 'card', 'status' => 'booked', 'card' => ['type' => 'visa', 'holder' => 'Khalid Al-Otaibi', 'last4' => '3001']],
            ['doctor' => 'demo-dr-5@seed-placeholder.invalid', 'patient' => 'demo-pt-2@seed-placeholder.invalid', 'specialty' => 'General Internal Medicine', 'offset' => 20, 'start' => '09:00', 'hours' => 1, 'payment_method' => 'cash', 'status' => 'booked'],
        ];

        foreach ($plans as $plan) {
            $this->seedOneAppointmentPlan($plan, $profitPercent);
        }
    }

    /**
     * @param  array<string, mixed>  $plan
     */
    private function seedOneAppointmentPlan(array $plan, float $profitPercent): void
    {
        $doctor = User::where('email', $plan['doctor'])->with('doctorProfile')->first();
        $patient = User::where('email', $plan['patient'])->first();
        $specialty = Specialty::where('name', $plan['specialty'])->first();

        if (! $doctor || ! $patient || ! $specialty || ! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
            return;
        }

        if (! $doctor->doctorSpecialties()->where('specialties.id', $specialty->id)->exists()) {
            return;
        }

        $preferred = now()->startOfDay()->addDays((int) $plan['offset']);
        $date = $this->resolveDateNear($doctor, $preferred, $plan['start'], (int) $plan['hours']);

        if ($date === null) {
            return;
        }

        $starts = $this->buildHourlySequence($plan['start'], (int) $plan['hours']);
        if ($starts === []) {
            return;
        }

        foreach ($starts as $startH) {
            $endH = Carbon::createFromFormat('H:i', $startH)->addHour()->format('H:i');
            if (! $this->slotIsBookable($doctor, $date, $startH, $endH)) {
                return;
            }
        }

        foreach ($starts as $startH) {
            $key = $doctor->id.'|'.$date.'|'.$startH;
            if (isset($this->occupiedSlotKeys[$key])) {
                return;
            }
        }

        $hourlyRate = (float) $doctor->doctorProfile->hourly_rate;
        $hours = count($starts);
        $total = round($hourlyRate * $hours, 2);
        $profitAmount = round(($total * $profitPercent) / 100, 2);
        $doctorNet = round($total - $profitAmount, 2);
        $endTime = Carbon::createFromFormat('H:i', $starts[array_key_last($starts)])->addHour()->format('H:i');

        DB::transaction(function () use ($plan, $doctor, $patient, $specialty, $date, $starts, $hours, $hourlyRate, $total, $profitPercent, $profitAmount, $doctorNet, $endTime) {
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'specialty_id' => $specialty->id,
                'appointment_date' => $date,
                'start_time' => $starts[0],
                'hours_count' => $hours,
                'end_time' => $endTime,
                'hourly_rate' => $hourlyRate,
                'total_amount' => $total,
                'admin_profit_percent' => $profitPercent,
                'admin_profit_amount' => $profitAmount,
                'doctor_net_amount' => $doctorNet,
                'payment_method' => $plan['payment_method'],
                'payment_status' => 'paid',
                'status' => $plan['status'],
            ]);

            foreach ($starts as $startH) {
                $slotEnd = Carbon::createFromFormat('H:i', $startH)->addHour()->format('H:i');
                AppointmentSlot::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $doctor->id,
                    'slot_date' => $date,
                    'slot_start' => $startH,
                    'slot_end' => $slotEnd,
                ]);
                $this->occupiedSlotKeys[$doctor->id.'|'.$date.'|'.$startH] = true;
            }

            if ($plan['payment_method'] === 'card' && isset($plan['card'])) {
                $appointment->cardPayment()->create([
                    'card_type' => $plan['card']['type'],
                    'cardholder_name' => $plan['card']['holder'],
                    'card_number_last4' => $plan['card']['last4'],
                    'expiry_month' => '06',
                    'expiry_year' => '28',
                    'status' => 'paid',
                ]);
            }
        });
    }

    /**
     * Finds a valid working day near the preferred calendar date (tries 0, ±1, ±2, … days)
     * so past offsets stay approximately in the past and future offsets in the future.
     */
    private function resolveDateNear(User $doctor, Carbon $preferred, string $firstStart, int $hours): ?string
    {
        $order = [0];
        for ($i = 1; $i <= 10; $i++) {
            $order[] = $i;
            $order[] = -$i;
        }

        foreach ($order as $delta) {
            $candidate = $preferred->copy()->addDays($delta)->startOfDay();
            $dayName = strtolower($candidate->englishDayOfWeek);
            $off = [
                strtolower((string) $doctor->doctorProfile->off_day_1),
                strtolower((string) $doctor->doctorProfile->off_day_2),
            ];
            if (in_array($dayName, $off, true)) {
                continue;
            }
            if ($this->daySupportsPlan($doctor, $candidate->toDateString(), $firstStart, $hours)) {
                return $candidate->toDateString();
            }
        }

        return null;
    }

    private function daySupportsPlan(User $doctor, string $date, string $firstStart, int $hours): bool
    {
        $starts = $this->buildHourlySequence($firstStart, $hours);
        foreach ($starts as $startH) {
            $endH = Carbon::createFromFormat('H:i', $startH)->addHour()->format('H:i');
            if (! $this->slotIsBookable($doctor, $date, $startH, $endH)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<string>
     */
    private function buildHourlySequence(string $firstStart, int $hours): array
    {
        if ($hours < 1) {
            return [];
        }
        $out = [];
        $t = Carbon::createFromFormat('H:i', $firstStart);
        for ($i = 0; $i < $hours; $i++) {
            $out[] = $t->copy()->addHours($i)->format('H:i');
        }

        return $out;
    }

    private function slotIsBookable(User $doctor, string $date, string $slotStart, string $slotEnd): bool
    {
        $available = DoctorAvailability::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('work_date', $date)
            ->where('is_available', true)
            ->where('start_time', '<=', $slotStart)
            ->where('end_time', '>=', $slotEnd)
            ->exists();

        if (! $available) {
            return false;
        }

        $inBreak = DoctorAvailability::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('work_date', $date)
            ->where('is_available', false)
            ->where('start_time', '<=', $slotStart)
            ->where('end_time', '>=', $slotEnd)
            ->exists();

        if ($inBreak) {
            return false;
        }

        return true;
    }
}
