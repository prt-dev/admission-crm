<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admissions = Admission::all();
        $batches = Batch::all();
        $instructor = User::where('role_id', 3)->first() ?? User::first();

        if ($admissions->isEmpty() || $batches->isEmpty()) {
            return;
        }

        // Topics and training sessions based on courses-and-batches.txt reference
        $curriculumSessions = [
            // Theory Sessions (T)
            [
                'topic' => 'Overview of NLETA Skill India Mission & Industry Standards',
                'type' => Attendance::TYPE_THEORY,
                'duration' => 2.00,
                'days_ago' => 14,
            ],
            [
                'topic' => 'Overview of Elevator Industry & Core Architecture',
                'type' => Attendance::TYPE_THEORY,
                'duration' => 2.00,
                'days_ago' => 13,
            ],
            [
                'topic' => 'General Safety, Signages, Warnings & PPE Guidelines',
                'type' => Attendance::TYPE_THEORY,
                'duration' => 4.00,
                'days_ago' => 12,
            ],
            [
                'topic' => 'Equipments, Tools, Tackles & Fire Protection Protocol',
                'type' => Attendance::TYPE_THEORY,
                'duration' => 4.00,
                'days_ago' => 11,
            ],
            // Practical Sessions (P)
            [
                'topic' => 'Practical: Scaffolding, Fall Protection Barriers & Safety Planks',
                'type' => Attendance::TYPE_PRACTICAL,
                'duration' => 4.00,
                'days_ago' => 10,
            ],
            [
                'topic' => 'Practical: Electrical Equipments Working & Jumpers Safety',
                'type' => Attendance::TYPE_PRACTICAL,
                'duration' => 8.00,
                'days_ago' => 9,
            ],
            [
                'topic' => 'Practical: First Aid Kit, CPR Procedure & Hazard Removal',
                'type' => Attendance::TYPE_PRACTICAL,
                'duration' => 4.00,
                'days_ago' => 8,
            ],
            [
                'topic' => 'Practical: Machine Room Safety & Shaft Pit Entry Inspection',
                'type' => Attendance::TYPE_PRACTICAL,
                'duration' => 8.00,
                'days_ago' => 7,
            ],
            [
                'topic' => 'Practical: Cartop Entry, Balancing & Load Testing Procedure',
                'type' => Attendance::TYPE_PRACTICAL,
                'duration' => 8.00,
                'days_ago' => 6,
            ],
            // OJT (On-the-Job Training) Sessions (O)
            [
                'topic' => 'OJT: Parameters Settings, Programming & Controller Calibration',
                'type' => Attendance::TYPE_OJT,
                'duration' => 8.00,
                'days_ago' => 5,
            ],
            [
                'topic' => 'OJT: Auto Rescue Operation (ARD) & Live Site Safety Drill',
                'type' => Attendance::TYPE_OJT,
                'duration' => 8.00,
                'days_ago' => 4,
            ],
            [
                'topic' => 'OJT: Quality Inspection(s) & Escalator/Travelator Guidelines',
                'type' => Attendance::TYPE_OJT,
                'duration' => 8.00,
                'days_ago' => 3,
            ],
            [
                'topic' => 'OJT: End-to-End Commissioning, Safety Planks & Maintenance Operations',
                'type' => Attendance::TYPE_OJT,
                'duration' => 16.00,
                'days_ago' => 1,
            ],
        ];

        foreach ($batches as $batch) {
            $batchAdmissions = $admissions->where('batch_id', $batch->id);
            $batchCourseId = $batch->courses->first()?->id;

            if ($batchAdmissions->isEmpty() && $batchCourseId) {
                // If admission has no batch_id, match by course
                $batchAdmissions = $admissions->where('course_id', $batchCourseId);
            }

            foreach ($curriculumSessions as $index => $session) {
                $sessionDate = Carbon::now()->subDays($session['days_ago'])->toDateString();

                foreach ($batchAdmissions as $studentIndex => $admission) {
                    // Introduce realistic attendance variety (mostly present, some late/absent)
                    $status = Attendance::STATUS_PRESENT;
                    $remarks = 'Attended session actively.';

                    if (($index + $studentIndex) % 7 === 0) {
                        $status = Attendance::STATUS_LATE;
                        $remarks = 'Joined 15 minutes late due to transit.';
                    } elseif (($index + $studentIndex) % 11 === 0) {
                        $status = Attendance::STATUS_LEAVE;
                        $remarks = 'Pre-approved medical leave.';
                    } elseif (($index + $studentIndex) % 13 === 0) {
                        $status = Attendance::STATUS_ABSENT;
                        $remarks = 'Absent without prior notice.';
                    }

                    Attendance::updateOrCreate(
                        [
                            'admission_id' => $admission->id,
                            'batch_id' => $batch->id,
                            'date' => $sessionDate,
                            'type' => $session['type'],
                        ],
                        [
                            'course_id' => $batchCourseId ?? $admission->course_id,
                            'duration' => $session['duration'],
                            'status' => $status,
                            'topic_covered' => $session['topic'],
                            'remarks' => $remarks,
                            'marked_by' => $instructor?->id,
                        ]
                    );
                }
            }
        }
    }
}
