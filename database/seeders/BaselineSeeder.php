<?php

namespace Database\Seeders;

use App\Models\ClinicSetting;
use App\Models\DoctorProfile;
use App\Models\PageContent;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Application bootstrap: admin/patient users, core specialties (with descriptions), clinic setting, CMS stubs.
 */
class BaselineSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@clinic.local',
        ], [
            'name' => 'System Admin',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::updateOrCreate([
            'email' => 'patient@example.com',
        ], [
            'name' => 'Test Patient',
            'role' => 'patient',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::updateOrCreate([
            'email' => 'munir@clinic.local',
        ], [
            'name' => 'Munir',
            'role' => 'patient',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        $quickDoctor = User::updateOrCreate(
            ['email' => 'doctor@clinic.local'],
            [
                'name' => 'Dr. Munir Clinic (Quick Demo)',
                'role' => 'doctor',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        DoctorProfile::updateOrCreate(
            ['user_id' => $quickDoctor->id],
            [
                'status' => 'approved',
                'education_stage' => 'Consultant — general and preventive care',
                'bio' => 'Demo doctor account for quick login and load testing with seeded appointments.',
                'hourly_rate' => 275.00,
                'work_start_time' => '09:00',
                'work_end_time' => '17:00',
                'off_day_1' => 'friday',
                'off_day_2' => 'saturday',
                'approved_at' => now()->subMonth()->toDateString(),
                'rejection_reason' => null,
            ]
        );

        $specialties = [
            [
                'name' => 'Cardiology',
                'description' => 'Prevention, diagnosis, and treatment of heart and vascular disease, including hypertension, coronary artery disease, arrhythmias, and heart failure follow-up.',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail disorders—acne, eczema, psoriasis, infections, and screening for suspicious lesions—with both medical and procedural care options.',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bones, joints, ligaments, and muscles: fractures, sports injuries, arthritis management, and pre- and post-operative musculoskeletal rehabilitation planning.',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Healthcare for infants, children, and adolescents: growth monitoring, vaccinations, acute illness visits, and coordination with schools and caregivers.',
            ],
        ];

        foreach ($specialties as $row) {
            Specialty::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'],
                    'is_active' => true,
                ]
            );
        }

        ClinicSetting::firstOrCreate([], ['admin_profit_percent' => 15]);

        PageContent::firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Doktors Clinic',
                'subtitle' => 'Smart clinic graduation project.',
                'body' => "Doktors Clinic is a smart healthcare platform focused on improving clinic operations,\npatient booking experience, and doctor schedule management.",
                'is_published' => true,
            ]
        );

        PageContent::firstOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'subtitle' => 'Reach our clinic team anytime.',
                'body' => "Phone: +000 000 0000\nEmail: support@doktors.local\nAddress: Main Clinic Street",
                'is_published' => true,
            ]
        );
    }
}
