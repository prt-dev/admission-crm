<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();

        $courses = [
            [
                'name' => 'Lift & Escalator Safety Engineering',
                'code' => 'LESE-101',
                'description' => 'Comprehensive technical training on lift maintenance, safety standards, and escalator mechanisms.',
                'duration' => '6 Months',
                'fee' => 45000.00,
                'status' => 1,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Advanced Elevator Technology & Automation',
                'code' => 'AETA-201',
                'description' => 'Modern microprocessor controls, PLC integration, regenerative drives, and smart lift monitoring.',
                'duration' => '1 Year',
                'fee' => 75000.00,
                'status' => 1,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Diploma in Vertical Transportation Systems',
                'code' => 'DVTS-301',
                'description' => 'Architectural traffic analysis, hoistway design, safety inspection protocols, and installation engineering.',
                'duration' => '2 Years',
                'fee' => 120000.00,
                'status' => 1,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Escalator & Moving Walkway Specialist',
                'code' => 'EMWS-102',
                'description' => 'Mechanical truss assembly, step chain inspection, comb-plate safety switches, and emergency brake testing.',
                'duration' => '3 Months',
                'fee' => 25000.00,
                'status' => 1,
                'created_by' => $admin?->id,
            ],
        ];

        foreach ($courses as $data) {
            Course::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
