<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FunctionalAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder populates the functional_areas table with values
     * from FUNCTIONAL_AREAS in resources/js/data/observationData.js
     */
    public function run(): void
    {
        // Empty the table first
        DB::table('functional_areas')->truncate();

        // Functional areas from observationData.js
        $functionalAreas = [
            ['fa_code' => 'ACS & ACR', 'title' => 'Away Club Support & Away Club Relations'],
            ['fa_code' => 'BRO', 'title' => 'Broadcasting'],
            ['fa_code' => 'CAT', 'title' => 'Catering'],
            ['fa_code' => 'CLO', 'title' => 'Club Liaison'],
            ['fa_code' => 'COM', 'title' => 'Communications'],
            ['fa_code' => 'CSR', 'title' => 'Corporate Social Responsibility'],
            ['fa_code' => 'CUS', 'title' => 'Customer Service'],
            ['fa_code' => 'DAT', 'title' => 'Data & Analytics'],
            ['fa_code' => 'DIS', 'title' => 'Disability Access'],
            ['fa_code' => 'ENV', 'title' => 'Environmental Sustainability'],
            ['fa_code' => 'EVE', 'title' => 'Event Management & Operations'],
            ['fa_code' => 'FAN', 'title' => 'Fan Engagement & Experience'],
            ['fa_code' => 'FIN', 'title' => 'Finance & Commercial'],
            ['fa_code' => 'GOV', 'title' => 'Governance & Compliance'],
            ['fa_code' => 'HOS', 'title' => 'Hospitality'],
            ['fa_code' => 'HR', 'title' => 'Human Resources'],
            ['fa_code' => 'IT', 'title' => 'Information Technology & Digital'],
            ['fa_code' => 'MAR', 'title' => 'Marketing & Brand'],
            ['fa_code' => 'MED', 'title' => 'Medical & Sports Science'],
            ['fa_code' => 'RET', 'title' => 'Retail & Merchandising'],
            ['fa_code' => 'SAF', 'title' => 'Safety & Security'],
            ['fa_code' => 'STA', 'title' => 'Stadium & Facilities'],
            ['fa_code' => 'TIC', 'title' => 'Ticketing'],
            ['fa_code' => 'Other', 'title' => 'Other'],
        ];

        // Insert all functional areas with active_flag = 1 and timestamps
        foreach ($functionalAreas as $area) {
            DB::table('functional_areas')->insert([
                'fa_code' => $area['fa_code'],
                'title' => $area['title'],
                'active_flag' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Functional areas table seeded successfully with ' . count($functionalAreas) . ' records.');
    }
}
