<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Demo dataset entry point. Order respects foreign keys:
     * baseline users/settings/pages/specialties → extended catalog → CMS copy → staff/patients (FK placeholders)
     * → pivot specialties → generated availabilities → appointments/slots/payments.
     *
     * Intentionally not seeded: notifications (runtime), sessions, password_reset_tokens, jobs, cache, failed_jobs.
     */
    public function run(): void
    {
        $this->call([
            BaselineSeeder::class,
            SpecialtyCatalogSeeder::class,
            PageContentDemoSeeder::class,
            DemoMedicalStaffSeeder::class,
            DemoDoctorSpecialtySeeder::class,
            DemoDoctorAvailabilitySeeder::class,
            DemoAppointmentAndPaymentSeeder::class,
            BulkQuickDoctorAppointmentsSeeder::class,
        ]);
    }
}
