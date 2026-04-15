<?php

namespace Database\Seeders;

use App\Models\DoctorAvailability;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Expands weekly templates on approved doctor profiles into concrete doctor_availabilities rows
 * (same idea as AvailabilityController: one available interval per working day).
 * Covers all approved doctors; applies a default Mon–Thu (Fri/Sat off) schedule when profile fields are empty.
 * Future window: two calendar months ahead from today (plus past days for demo appointment seeding).
 * Optional lunch break as is_available=false for selected demo doctors.
 */
class DemoDoctorAvailabilitySeeder extends Seeder
{
    /** Past days kept so DemoAppointmentAndPaymentSeeder negative offsets still resolve to bookable days. */
    private int $pastDays = 45;

    public function run(): void
    {
        $doctors = User::query()
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', fn ($q) => $q->where('status', 'approved'))
            ->with('doctorProfile')
            ->get();

        $rangeStart = now()->copy()->subDays($this->pastDays)->startOfDay();
        $rangeEnd = now()->copy()->addMonths(2)->startOfDay();

        foreach ($doctors as $doctor) {
            $profile = $doctor->doctorProfile;

            if (! $profile->work_start_time || ! $profile->work_end_time || ! $profile->off_day_1 || ! $profile->off_day_2) {
                $profile->update([
                    'work_start_time' => '09:00',
                    'work_end_time' => '17:00',
                    'off_day_1' => 'friday',
                    'off_day_2' => 'saturday',
                ]);
                $profile->refresh();
            }

            DoctorAvailability::query()
                ->where('doctor_id', $doctor->id)
                ->whereDate('work_date', '>=', $rangeStart->toDateString())
                ->whereDate('work_date', '<=', $rangeEnd->toDateString())
                ->delete();

            $startTime = Carbon::parse($profile->work_start_time)->format('H:i');
            $endTime = Carbon::parse($profile->work_end_time)->format('H:i');
            $off = [strtolower((string) $profile->off_day_1), strtolower((string) $profile->off_day_2)];

            $cursor = $rangeStart->copy();

            while ($cursor->lessThanOrEqualTo($rangeEnd)) {
                $dayName = strtolower($cursor->englishDayOfWeek);

                if (in_array($dayName, $off, true)) {
                    $cursor->addDay();

                    continue;
                }

                DoctorAvailability::create([
                    'doctor_id' => $doctor->id,
                    'work_date' => $cursor->toDateString(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_available' => true,
                ]);

                if (in_array($doctor->email, [
                    'demo-dr-1@seed-placeholder.invalid',
                    'demo-dr-3@seed-placeholder.invalid',
                ], true)) {
                    DoctorAvailability::create([
                        'doctor_id' => $doctor->id,
                        'work_date' => $cursor->toDateString(),
                        'start_time' => '12:00',
                        'end_time' => '13:00',
                        'is_available' => false,
                    ]);
                }

                $cursor->addDay();
            }
        }
    }
}
