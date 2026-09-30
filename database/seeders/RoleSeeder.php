<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full administrative access across all CRM modules, settings, and user management.',
                'permissions' => ['*'],
                'status' => 1,
                'is_system' => true,
            ],
            [
                'id' => 2,
                'name' => 'Counselor / Admission Officer',
                'slug' => 'counselor',
                'description' => 'Manages leads, inquiries, follow-ups, and student admissions lifecycle.',
                'permissions' => [
                    'leads.view', 'leads.create', 'leads.update', 'leads.follow_up',
                    'admissions.view', 'admissions.create', 'admissions.update', 'admissions.payment',
                    'courses.view', 'batches.view',
                ],
                'status' => 1,
                'is_system' => true,
            ],
            [
                'id' => 3,
                'name' => 'Faculty / Instructor',
                'slug' => 'instructor',
                'description' => 'Assigned to course batches, access to batch schedules and student attendance.',
                'permissions' => [
                    'batches.view', 'courses.view', 'admissions.view',
                ],
                'status' => 1,
                'is_system' => true,
            ],
            [
                'id' => 4,
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'Student portal access for view-only course schedule, payment receipt, and profile.',
                'permissions' => [
                    'profile.view', 'admissions.view_own', 'payments.view_own',
                ],
                'status' => 1,
                'is_system' => true,
            ],
        ];

        foreach ($roles as $data) {
            Role::updateOrCreate(['id' => $data['id']], $data);
        }
    }
}
