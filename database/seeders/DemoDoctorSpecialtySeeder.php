<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Attaches specialties to approved demo doctors (pivot doctor_specialty).
 */
class DemoDoctorSpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'doctor@clinic.local' => ['Cardiology', 'General Internal Medicine', 'Dermatology'],
            'demo-dr-1@seed-placeholder.invalid' => ['Cardiology', 'General Internal Medicine'],
            'demo-dr-2@seed-placeholder.invalid' => ['Orthopedics', 'Rheumatology'],
            'demo-dr-3@seed-placeholder.invalid' => ['Dermatology'],
            'demo-dr-4@seed-placeholder.invalid' => ['Pediatrics', 'General Internal Medicine'],
            'demo-dr-5@seed-placeholder.invalid' => ['Neurology', 'General Internal Medicine'],
        ];

        foreach ($map as $email => $specialtyNames) {
            $doctor = User::where('email', $email)->first();
            if (! $doctor || ! $doctor->doctorProfile || $doctor->doctorProfile->status !== 'approved') {
                continue;
            }

            $ids = Specialty::whereIn('name', $specialtyNames)->pluck('id')->all();
            $doctor->doctorSpecialties()->syncWithoutDetaching($ids);
        }

        $fallback = Specialty::where('name', 'General Internal Medicine')->first();
        if ($fallback) {
            User::query()
                ->where('role', 'doctor')
                ->whereHas('doctorProfile', fn ($q) => $q->where('status', 'approved'))
                ->whereDoesntHave('doctorSpecialties')
                ->each(function (User $doctor) use ($fallback): void {
                    $doctor->doctorSpecialties()->syncWithoutDetaching([$fallback->id]);
                });
        }
    }
}
