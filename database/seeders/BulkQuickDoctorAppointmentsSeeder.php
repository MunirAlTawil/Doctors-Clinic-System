<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\ClinicSetting;
use App\Models\DoctorAvailability;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fills remaining hourly slots for doctor@clinic.local with many appointments (up to 500).
 * Munir (munir@clinic.local) receives ~70% of bookings; other active patients share the rest.
 * Runs after DemoAppointmentAndPaymentSeeder so existing demo slots stay respected.
 * Date window matches DemoDoctorAvailabilitySeeder (past 45 days through two months ahead).
 */
class BulkQuickDoctorAppointmentsSeeder extends Seeder
{
    private const DOCTOR_EMAIL = 'doctor@clinic.local';

    private const MUNIR_EMAIL = 'munir@clinic.local';

    /** @var array<string, true> */
    private array $occupiedSlotKeys = [];

    public function run(): void
    {
        $doctor = User::query()
            ->where('email', self::DOCTOR_EMAIL)
            ->with('doctorProfile')
            ->first();

        $munir = User::query()->where('email', self::MUNIR_EMAIL)->first();

        if (! $doctor || ! $munir || ! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
            return;
        }

        $specialtyIds = $doctor->doctorSpecialties()->pluck('specialties.id')->all();
        if ($specialtyIds === []) {
            return;
        }

        $this->occupiedSlotKeys = [];
        foreach (AppointmentSlot::query()->where('doctor_id', $doctor->id)->get() as $slot) {
            $dateStr = $slot->slot_date instanceof \Carbon\CarbonInterface
                ? $slot->slot_date->toDateString()
                : (string) $slot->slot_date;
            $startH = Carbon::parse($slot->slot_start)->format('H:i');
            $this->occupiedSlotKeys[$doctor->id.'|'.$dateStr.'|'.$startH] = true;
        }

        $otherPatients = User::query()
            ->where('role', 'patient')
            ->where('id', '!=', $munir->id)
            ->where('is_active', true)
            ->get();

        if ($otherPatients->isEmpty()) {
            $otherPatients = User::query()
                ->where('role', 'patient')
                ->where('is_active', true)
                ->get();
        }

        if ($otherPatients->isEmpty()) {
            return;
        }

        $profitPercent = (float) (ClinicSetting::first()?->admin_profit_percent ?? 15);
        $hourlyRate = (float) $doctor->doctorProfile->hourly_rate;

        $from = now()->copy()->subDays(45)->startOfDay();
        $to = now()->copy()->addMonths(2)->startOfDay();

        $maxAppointments = 500;
        $created = 0;

        for ($day = $from->copy(); $day->lessThanOrEqualTo($to) && $created < $maxAppointments; $day->addDay()) {
            $dateStr = $day->toDateString();
            $dayName = strtolower($day->englishDayOfWeek);
            $off = [
                strtolower((string) $doctor->doctorProfile->off_day_1),
                strtolower((string) $doctor->doctorProfile->off_day_2),
            ];
            if (in_array($dayName, $off, true)) {
                continue;
            }

            $workStart = Carbon::parse($doctor->doctorProfile->work_start_time)->format('H:i');
            $workEnd = Carbon::parse($doctor->doctorProfile->work_end_time)->format('H:i');
            $cursor = Carbon::createFromFormat('H:i', $workStart);
            $endBoundary = Carbon::createFromFormat('H:i', $workEnd);

            while ($cursor->copy()->addHour()->lessThanOrEqualTo($endBoundary) && $created < $maxAppointments) {
                $startH = $cursor->format('H:i');
                $endH = $cursor->copy()->addHour()->format('H:i');

                if (! $this->slotIsBookable($doctor, $dateStr, $startH, $endH)) {
                    $cursor->addHour();

                    continue;
                }

                $occKey = $doctor->id.'|'.$dateStr.'|'.$startH;
                if (isset($this->occupiedSlotKeys[$occKey])) {
                    $cursor->addHour();

                    continue;
                }

                $slotMoment = Carbon::parse($dateStr.' '.$startH);
                $status = $slotMoment->lessThanOrEqualTo(now()) ? 'completed' : 'booked';

                $patient = random_int(1, 100) <= 70 ? $munir : $otherPatients->random();
                $specialty = Specialty::query()->whereIn('id', $specialtyIds)->inRandomOrder()->first();
                if (! $specialty) {
                    $cursor->addHour();

                    continue;
                }

                $this->createOneHourAppointment(
                    $doctor,
                    $patient,
                    $specialty,
                    $dateStr,
                    $startH,
                    $hourlyRate,
                    $profitPercent,
                    $status
                );

                $this->occupiedSlotKeys[$occKey] = true;
                $created++;
                $cursor->addHour();
            }
        }
    }

    private function createOneHourAppointment(
        User $doctor,
        User $patient,
        Specialty $specialty,
        string $date,
        string $startH,
        float $hourlyRate,
        float $profitPercent,
        string $status
    ): void {
        $starts = [$startH];
        $hours = 1;
        $total = round($hourlyRate * $hours, 2);
        $profitAmount = round(($total * $profitPercent) / 100, 2);
        $doctorNet = round($total - $profitAmount, 2);
        $endTime = Carbon::createFromFormat('H:i', $startH)->addHour()->format('H:i');

        DB::transaction(function () use ($doctor, $patient, $specialty, $date, $starts, $hours, $hourlyRate, $total, $profitPercent, $profitAmount, $doctorNet, $endTime, $status, $startH): void {
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'specialty_id' => $specialty->id,
                'appointment_date' => $date,
                'start_time' => $startH,
                'hours_count' => $hours,
                'end_time' => $endTime,
                'hourly_rate' => $hourlyRate,
                'total_amount' => $total,
                'admin_profit_percent' => $profitPercent,
                'admin_profit_amount' => $profitAmount,
                'doctor_net_amount' => $doctorNet,
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'status' => $status,
            ]);

            foreach ($starts as $h) {
                $slotEnd = Carbon::createFromFormat('H:i', $h)->addHour()->format('H:i');
                AppointmentSlot::create([
                    'appointment_id' => $appointment->id,
                    'doctor_id' => $doctor->id,
                    'slot_date' => $date,
                    'slot_start' => $h,
                    'slot_end' => $slotEnd,
                ]);
            }
        });
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

        return ! $inBreak;
    }
}
