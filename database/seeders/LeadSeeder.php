<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $counselor = $users->where('role_id', 2)->first() ?? $users->first();

        $leadsData = [
            [
                'first_name' => 'Amit',
                'last_name' => 'Sharma',
                'email' => 'amit.sharma@example.com',
                'phone' => '9876543210',
                'alternate_phone' => '9876543211',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'source' => 'website',
                'course_interested' => 'Lift & Escalator Safety Engineering',
                'assigned_to' => $counselor?->id,
                'status' => 1, // New
                'notes' => 'Inquired about weekend batches and certification fee structure.',
                'next_follow_up_at' => now()->addDays(1),
                'created_by' => $counselor?->id,
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Verma',
                'email' => 'priya.verma@example.com',
                'phone' => '9812345678',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'source' => 'referral',
                'course_interested' => 'Diploma in Vertical Transportation',
                'assigned_to' => $counselor?->id,
                'status' => 2, // Contacted
                'notes' => 'Spoke on phone. Shared syllabus on WhatsApp.',
                'next_follow_up_at' => now()->addDays(2),
                'created_by' => $counselor?->id,
            ],
            [
                'first_name' => 'Rahul',
                'last_name' => 'Patel',
                'email' => 'rahul.patel@example.com',
                'phone' => '9823456789',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'source' => 'walk-in',
                'course_interested' => 'Advanced Elevator Technology',
                'assigned_to' => $counselor?->id,
                'status' => 3, // Follow-up scheduled
                'notes' => 'Visited center. Requested meeting with faculty head.',
                'next_follow_up_at' => now()->addHours(6),
                'created_by' => $counselor?->id,
            ],
            [
                'first_name' => 'Sneha',
                'last_name' => 'Rao',
                'email' => 'sneha.rao@example.com',
                'phone' => '9834567890',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'source' => 'social_media',
                'course_interested' => 'Lift & Escalator Safety Engineering',
                'assigned_to' => $counselor?->id,
                'status' => 4, // Converted
                'notes' => 'Completed enrollment form. Paid registration token.',
                'converted_at' => now(),
                'created_by' => $counselor?->id,
            ],
            [
                'first_name' => 'Vikram',
                'last_name' => 'Singh',
                'email' => 'vikram.singh@example.com',
                'phone' => '9845678901',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'source' => 'advertisement',
                'course_interested' => 'Advanced Elevator Technology',
                'assigned_to' => $counselor?->id,
                'status' => 5, // Closed / Lost
                'notes' => 'Looking for mechanical drafting, not lift tech.',
                'created_by' => $counselor?->id,
            ],
        ];

        foreach ($leadsData as $data) {
            $lead = Lead::create($data);

            LeadFollowUp::create([
                'lead_id' => $lead->id,
                'user_id' => $counselor?->id,
                'type' => 'call',
                'remarks' => 'Initial inquiry verification and course counseling.',
                'scheduled_at' => now()->subDay(),
                'completed_at' => now(),
                'status' => 2, // Completed
            ]);
        }
    }
}
