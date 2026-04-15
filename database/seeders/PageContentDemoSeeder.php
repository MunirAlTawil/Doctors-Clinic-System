<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

/**
 * Enriches CMS page copy for demo presentation (same slugs as baseline).
 */
class PageContentDemoSeeder extends Seeder
{
    public function run(): void
    {
        PageContent::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About Doktors Clinic',
                'subtitle' => 'Modern outpatient scheduling and coordinated care.',
                'body' => <<<'MD'
Doktors Clinic connects patients with verified specialists through a structured booking workflow, transparent visit windows, and clear financial summaries for each appointment.

Our platform supports clinic administrators with oversight of doctor onboarding, specialty catalogues, and operational reporting, while doctors manage recurring availability and appointment outcomes. Patients can discover providers by specialty, review available hourly slots, and complete reservations with a documented payment method.

This environment is designed for reliable day-to-day clinic operations: every booking reserves discrete hour-level slots to prevent double scheduling, and commission settings are snapshotted per visit for consistent historical reporting.
MD,
                'is_published' => true,
                'image_path' => null,
            ]
        );

        PageContent::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact Us',
                'subtitle' => 'Reception, billing, and medical records.',
                'body' => <<<'MD'
**Reception desk:** +966 11 555 0140 (08:00–20:00, Saturday–Thursday)

**Billing inquiries:** billing@doktors-clinic.demo

**Medical records / referrals:** records@doktors-clinic.demo

**Address:** King Fahd Road, Al Olaya, Riyadh — Building 12, Floor 3

For urgent emergencies, please use your national emergency number or visit the nearest hospital emergency department.
MD,
                'is_published' => true,
                'image_path' => null,
            ]
        );
    }
}
