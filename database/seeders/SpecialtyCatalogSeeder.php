<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

/**
 * Additional medical specialties for richer public and admin listings.
 * Does not duplicate baseline names (unique constraint on name).
 */
class SpecialtyCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Neurology',
                'description' => 'Diagnosis and treatment of disorders of the brain, spinal cord, and peripheral nerves, including headaches, seizures, and movement disorders.',
                'is_active' => true,
            ],
            [
                'name' => 'Otolaryngology (ENT)',
                'description' => 'Ear, nose, and throat care including hearing assessments, sinus conditions, and voice disorders.',
                'is_active' => true,
            ],
            [
                'name' => 'Endocrinology',
                'description' => 'Management of hormone-related conditions such as diabetes, thyroid disorders, and metabolic syndrome.',
                'is_active' => true,
            ],
            [
                'name' => 'General Internal Medicine',
                'description' => 'Comprehensive adult primary care, preventive screenings, and coordination of chronic disease management.',
                'is_active' => true,
            ],
            [
                'name' => 'Rheumatology',
                'description' => 'Care for autoimmune and musculoskeletal conditions including arthritis, lupus, and inflammatory joint disease.',
                'is_active' => true,
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Assessment and treatment of mental health conditions with emphasis on evidence-based therapy and medication management.',
                'is_active' => true,
            ],
        ];

        foreach ($catalog as $row) {
            Specialty::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'],
                    'is_active' => $row['is_active'],
                ]
            );
        }
    }
}
