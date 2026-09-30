<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $counselor = User::where('role_id', 2)->first() ?? User::first();
        $studentUser = User::where('email', 'sneha.rao@example.com')->first();
        $lead = Lead::where('status', 4)->first();
        $course1 = Course::where('code', 'LESE-101')->first();
        $course2 = Course::where('code', 'AETA-201')->first();
        $batch1 = Batch::where('code', 'BATCH-LESE-2026-M1')->first();
        $batch2 = Batch::where('code', 'BATCH-AETA-2026-A1')->first();

        $admissionsData = [
            [
                'admission_number' => 'ADM-2026-000101',
                'registration_number' => 'REG-2026-000101',
                'user_id' => $studentUser?->id,
                'lead_id' => $lead?->id,
                'course_id' => $course1?->id ?? 1,
                'batch_id' => $batch1?->id,
                'first_name' => 'Sneha',
                'last_name' => 'Rao',
                'email' => 'sneha.rao@example.com',
                'phone' => '9834567890',
                'alternate_phone' => '9834567899',
                'dob' => '2002-05-14',
                'gender' => 'Female',
                'guardian_name' => 'Kishore Rao',
                'guardian_phone' => '9834567800',
                'address' => '42, MG Road, Indiranagar',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'pincode' => '560038',
                'qualification' => 'Diploma in Mechanical Engineering',
                'admission_date' => now()->subDays(5)->toDateString(),
                'course_fee' => 45000.00,
                'discount_amount' => 5000.00,
                'final_fee' => 40000.00,
                'paid_amount' => 20000.00,
                'due_amount' => 20000.00,
                'payment_status' => 2, // Partial
                'status' => 1, // Confirmed
                'remarks' => 'Converted from lead. Registration token paid.',
                'admitted_by' => $counselor?->id,
            ],
            [
                'admission_number' => 'ADM-2026-000102',
                'registration_number' => 'REG-2026-000102',
                'user_id' => null,
                'lead_id' => null,
                'course_id' => $course2?->id ?? 2,
                'batch_id' => $batch2?->id,
                'first_name' => 'Rohan',
                'last_name' => 'Deshmukh',
                'email' => 'rohan.deshmukh@example.com',
                'phone' => '9811223344',
                'alternate_phone' => null,
                'dob' => '2001-11-20',
                'gender' => 'Male',
                'guardian_name' => 'Suresh Deshmukh',
                'guardian_phone' => '9811223300',
                'address' => '108, Shivaji Nagar',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'pincode' => '411005',
                'qualification' => 'B.Tech Electrical',
                'admission_date' => now()->subDays(2)->toDateString(),
                'course_fee' => 75000.00,
                'discount_amount' => 0.00,
                'final_fee' => 75000.00,
                'paid_amount' => 75000.00,
                'due_amount' => 0.00,
                'payment_status' => 3, // Paid
                'status' => 1, // Confirmed
                'remarks' => 'Direct walk-in admission. Full payment done.',
                'admitted_by' => $counselor?->id,
            ],
        ];

        foreach ($admissionsData as $data) {
            Admission::updateOrCreate(['admission_number' => $data['admission_number']], $data);
        }
    }
}
