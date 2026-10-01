<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Admission;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Schema;

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = null;
        if (Schema::hasTable('users')) {
            $admin = User::first();
        }

        $sessions = [
            [
                'name' => 'Academic Year 2024-2025',
                'code' => 'SESS-2024-25',
                'start_date' => '2024-04-01',
                'end_date' => '2025-03-31',
                'is_current' => false,
                'status' => AcademicSession::STATUS_COMPLETED,
                'description' => 'Academic session for 2024-2025 batch cohorts.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Academic Year 2025-2026',
                'code' => 'SESS-2025-26',
                'start_date' => '2025-04-01',
                'end_date' => '2026-03-31',
                'is_current' => false,
                'status' => AcademicSession::STATUS_COMPLETED,
                'description' => 'Academic session for 2025-2026 batch cohorts.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Academic Year 2026-2027',
                'code' => 'SESS-2026-27',
                'start_date' => '2026-04-01',
                'end_date' => '2027-03-31',
                'is_current' => true,
                'status' => AcademicSession::STATUS_ACTIVE,
                'description' => 'Current ongoing academic session running all active batches.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Academic Year 2027-2028',
                'code' => 'SESS-2027-28',
                'start_date' => '2027-04-01',
                'end_date' => '2028-03-31',
                'is_current' => false,
                'status' => AcademicSession::STATUS_UPCOMING,
                'description' => 'Upcoming academic session for advance admissions and new batch planning.',
                'created_by' => $admin?->id,
            ],
        ];

        $currentSessionModel = null;
        foreach ($sessions as $data) {
            $sess = AcademicSession::updateOrCreate(['code' => $data['code']], $data);
            if ($sess->is_current) {
                $currentSessionModel = $sess;
            }
        }

        // Link existing batches and admissions to the current active session if tables exist
        if ($currentSessionModel) {
            if (Schema::hasTable('batches')) {
                Batch::whereNull('academic_session_id')->update(['academic_session_id' => $currentSessionModel->id]);
            }
            if (Schema::hasTable('admissions')) {
                Admission::whereNull('academic_session_id')->update(['academic_session_id' => $currentSessionModel->id]);
            }
        }
    }
}
