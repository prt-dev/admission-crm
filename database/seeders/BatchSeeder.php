<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $course1 = Course::where('code', 'LESE-101')->first();
        $course2 = Course::where('code', 'AETA-201')->first();
        $course3 = Course::where('code', 'DVTS-301')->first();

        $batches = [
            [
                'name' => 'LESE Batch - Spring 2026 Morning',
                'code' => 'BATCH-LESE-2026-M1',
                'course_ids' => array_filter([$course1?->id]),
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->addMonths(6)->endOfMonth()->toDateString(),
                'timing' => '09:00 AM - 01:00 PM',
                'capacity' => 30,
                'status' => 2, // Ongoing
                'instructor_id' => $admin?->id,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'LESE Batch - Spring 2026 Evening',
                'code' => 'BATCH-LESE-2026-E1',
                'course_ids' => array_filter([$course1?->id]),
                'start_date' => now()->addWeeks(2)->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
                'timing' => '05:00 PM - 08:30 PM',
                'capacity' => 25,
                'status' => 1, // Upcoming
                'instructor_id' => $admin?->id,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Advanced Elevator Batch - 2026',
                'code' => 'BATCH-AETA-2026-A1',
                'course_ids' => array_filter([$course2?->id]),
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->addMonths(11)->toDateString(),
                'timing' => '10:00 AM - 03:00 PM',
                'capacity' => 20,
                'status' => 2, // Ongoing
                'instructor_id' => $admin?->id,
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Diploma VT Cohort - 2026',
                'code' => 'BATCH-DVTS-2026-01',
                'course_ids' => array_filter([$course3?->id]),
                'start_date' => now()->addMonth()->toDateString(),
                'end_date' => now()->addYears(2)->toDateString(),
                'timing' => '09:30 AM - 04:30 PM',
                'capacity' => 40,
                'status' => 1, // Upcoming
                'instructor_id' => $admin?->id,
                'created_by' => $admin?->id,
            ],
        ];

        foreach ($batches as $data) {
            $courseIds = $data['course_ids'] ?? [];
            unset($data['course_ids']);

            $batch = Batch::updateOrCreate(['code' => $data['code']], $data);
            if (!empty($courseIds)) {
                $batch->courses()->sync($courseIds);
            }
        }
    }
}
