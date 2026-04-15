<?php

namespace Database\Seeders;

use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Foreign-key placeholder records only: User rows (doctors + extra patients) and DoctorProfile rows.
 *
 * Passwords are unique random hashes — not distributed as demo credentials. These satisfy
 * patient_id / doctor_id / doctor_profiles.user_id constraints for appointment and pivot seeding.
 * Does not alter guards, middleware, or the baseline admin/patient accounts from BaselineSeeder.
 */
class DemoMedicalStaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            [
                'user' => [
                    'email' => 'demo-dr-1@seed-placeholder.invalid',
                    'name' => 'Dr. Amira Al-Harbi',
                    'gender' => 'female',
                    'phone' => '+966 50 110 2201',
                ],
                'profile' => [
                    'status' => 'approved',
                    'education_stage' => 'Board-certified cardiologist',
                    'bio' => 'Focus on preventive cardiology, heart failure management, and post-operative follow-up for cardiac patients.',
                    'hourly_rate' => 350.00,
                    'work_start_time' => '09:00',
                    'work_end_time' => '17:00',
                    'off_day_1' => 'friday',
                    'off_day_2' => 'saturday',
                    'approved_at' => now()->subMonths(8)->toDateString(),
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-2@seed-placeholder.invalid',
                    'name' => 'Dr. Omar Khalil',
                    'gender' => 'male',
                    'phone' => '+966 55 220 3302',
                ],
                'profile' => [
                    'status' => 'approved',
                    'education_stage' => 'Consultant orthopaedic surgeon',
                    'bio' => 'Sports injuries, joint replacement planning, and conservative management of chronic musculoskeletal pain.',
                    'hourly_rate' => 420.00,
                    'work_start_time' => '08:00',
                    'work_end_time' => '16:00',
                    'off_day_1' => 'sunday',
                    'off_day_2' => 'monday',
                    'approved_at' => now()->subMonths(5)->toDateString(),
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-3@seed-placeholder.invalid',
                    'name' => 'Dr. Layla Mansour',
                    'gender' => 'female',
                    'phone' => '+966 54 330 4403',
                ],
                'profile' => [
                    'status' => 'approved',
                    'education_stage' => 'Consultant dermatologist',
                    'bio' => 'Medical and cosmetic dermatology including acne, eczema, pigmentation, and routine skin cancer screening.',
                    'hourly_rate' => 280.00,
                    'work_start_time' => '10:00',
                    'work_end_time' => '18:00',
                    'off_day_1' => 'thursday',
                    'off_day_2' => 'friday',
                    'approved_at' => now()->subMonths(4)->toDateString(),
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-4@seed-placeholder.invalid',
                    'name' => 'Dr. Yusuf Rahman',
                    'gender' => 'male',
                    'phone' => '+966 56 440 5504',
                ],
                'profile' => [
                    'status' => 'approved',
                    'education_stage' => 'Consultant paediatrician',
                    'bio' => 'Growth and development assessments, childhood vaccinations coordination, and acute paediatric illness visits.',
                    'hourly_rate' => 300.00,
                    'work_start_time' => '09:00',
                    'work_end_time' => '15:00',
                    'off_day_1' => 'saturday',
                    'off_day_2' => 'sunday',
                    'approved_at' => now()->subMonths(3)->toDateString(),
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-5@seed-placeholder.invalid',
                    'name' => 'Dr. Hala Saeed',
                    'gender' => 'female',
                    'phone' => '+966 59 550 6605',
                ],
                'profile' => [
                    'status' => 'approved',
                    'education_stage' => 'Consultant neurologist',
                    'bio' => 'Headache disorders, epilepsy follow-up, and collaborative care pathways with imaging and rehabilitation teams.',
                    'hourly_rate' => 380.00,
                    'work_start_time' => '09:00',
                    'work_end_time' => '17:00',
                    'off_day_1' => 'monday',
                    'off_day_2' => 'tuesday',
                    'approved_at' => now()->subMonths(2)->toDateString(),
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-pending@seed-placeholder.invalid',
                    'name' => 'Dr. Kareem Nasser',
                    'gender' => 'male',
                    'phone' => '+966 58 660 7706',
                ],
                'profile' => [
                    'status' => 'pending',
                    'education_stage' => 'Specialist registrar — internal medicine',
                    'bio' => 'Awaiting credential verification; interests include hypertension and diabetes co-management.',
                    'hourly_rate' => 0,
                    'work_start_time' => null,
                    'work_end_time' => null,
                    'off_day_1' => null,
                    'off_day_2' => null,
                    'approved_at' => null,
                    'rejection_reason' => null,
                ],
            ],
            [
                'user' => [
                    'email' => 'demo-dr-rejected@seed-placeholder.invalid',
                    'name' => 'Dr. Fadi Murad',
                    'gender' => 'male',
                    'phone' => '+966 57 770 8807',
                ],
                'profile' => [
                    'status' => 'rejected',
                    'education_stage' => 'Applicant — general practice',
                    'bio' => 'Application did not meet current staffing requirements.',
                    'hourly_rate' => 0,
                    'work_start_time' => null,
                    'work_end_time' => null,
                    'off_day_1' => null,
                    'off_day_2' => null,
                    'approved_at' => null,
                    'rejection_reason' => 'Incomplete licensing documentation and unavailable interview window during review period.',
                ],
            ],
        ];

        foreach ($staff as $row) {
            $password = Hash::make(Str::uuid()->toString().Str::uuid()->toString());

            $user = User::firstOrCreate(
                ['email' => $row['user']['email']],
                array_merge($row['user'], [
                    'role' => 'doctor',
                    'is_active' => true,
                    'password' => $password,
                ])
            );

            DoctorProfile::updateOrCreate(
                ['user_id' => $user->id],
                array_merge($row['profile'], ['user_id' => $user->id])
            );
        }

        $extraPatients = [
            [
                'email' => 'demo-pt-1@seed-placeholder.invalid',
                'name' => 'Noura Al-Qahtani',
                'gender' => 'female',
                'phone' => '+966 50 901 1122',
            ],
            [
                'email' => 'demo-pt-2@seed-placeholder.invalid',
                'name' => 'Khalid Al-Otaibi',
                'gender' => 'male',
                'phone' => '+966 55 902 3344',
            ],
        ];

        foreach ($extraPatients as $patient) {
            User::firstOrCreate(
                ['email' => $patient['email']],
                [
                    'name' => $patient['name'],
                    'role' => 'patient',
                    'is_active' => true,
                    'gender' => $patient['gender'],
                    'phone' => $patient['phone'],
                    'password' => Hash::make(Str::uuid()->toString().Str::uuid()->toString()),
                ]
            );
        }
    }
}
