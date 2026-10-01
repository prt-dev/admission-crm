<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'users.view', 'module' => 'users', 'description' => 'View users listing and user details'],
            ['name' => 'Create Users', 'slug' => 'users.create', 'module' => 'users', 'description' => 'Add new user accounts'],
            ['name' => 'Edit Users', 'slug' => 'users.edit', 'module' => 'users', 'description' => 'Update user details and credentials'],
            ['name' => 'Delete Users', 'slug' => 'users.delete', 'module' => 'users', 'description' => 'Remove user accounts'],
            ['name' => 'Change User Status', 'slug' => 'users.status', 'module' => 'users', 'description' => 'Activate or deactivate users'],

            // Role & Permission Management
            ['name' => 'View Roles', 'slug' => 'roles.view', 'module' => 'roles', 'description' => 'View roles and their permissions'],
            ['name' => 'Create Roles', 'slug' => 'roles.create', 'module' => 'roles', 'description' => 'Create custom roles'],
            ['name' => 'Edit Roles', 'slug' => 'roles.edit', 'module' => 'roles', 'description' => 'Update role info and permissions'],
            ['name' => 'Delete Roles', 'slug' => 'roles.delete', 'module' => 'roles', 'description' => 'Delete custom roles'],

            // Leads & Inquiries Management
            ['name' => 'View Leads', 'slug' => 'leads.view', 'module' => 'leads', 'description' => 'View incoming leads and inquiries'],
            ['name' => 'Create Leads', 'slug' => 'leads.create', 'module' => 'leads', 'description' => 'Create new leads manually'],
            ['name' => 'Edit Leads', 'slug' => 'leads.edit', 'module' => 'leads', 'description' => 'Update lead status and details'],
            ['name' => 'Delete Leads', 'slug' => 'leads.delete', 'module' => 'leads', 'description' => 'Delete lead records'],
            ['name' => 'Assign Leads', 'slug' => 'leads.assign', 'module' => 'leads', 'description' => 'Assign leads to admission counselors'],
            ['name' => 'Manage Follow-ups', 'slug' => 'leads.follow_up', 'module' => 'leads', 'description' => 'Schedule and log lead follow-up interactions'],

            // Course Management
            ['name' => 'View Courses', 'slug' => 'courses.view', 'module' => 'courses', 'description' => 'View course catalog and details'],
            ['name' => 'Create Courses', 'slug' => 'courses.create', 'module' => 'courses', 'description' => 'Add new training courses and programs'],
            ['name' => 'Edit Courses', 'slug' => 'courses.edit', 'module' => 'courses', 'description' => 'Update course descriptions, fees, and durations'],
            ['name' => 'Delete Courses', 'slug' => 'courses.delete', 'module' => 'courses', 'description' => 'Delete courses'],

            // Batch Management
            ['name' => 'View Batches', 'slug' => 'batches.view', 'module' => 'batches', 'description' => 'View batch schedules and capacity'],
            ['name' => 'Create Batches', 'slug' => 'batches.create', 'module' => 'batches', 'description' => 'Create new batch cohorts'],
            ['name' => 'Edit Batches', 'slug' => 'batches.edit', 'module' => 'batches', 'description' => 'Update batch dates, timings, and instructors'],
            ['name' => 'Delete Batches', 'slug' => 'batches.delete', 'module' => 'batches', 'description' => 'Delete batches'],

            // Admissions Management
            ['name' => 'View Admissions', 'slug' => 'admissions.view', 'module' => 'admissions', 'description' => 'View student admissions and profiles'],
            ['name' => 'Create Admissions', 'slug' => 'admissions.create', 'module' => 'admissions', 'description' => 'Enroll new students / convert leads to admissions'],
            ['name' => 'Edit Admissions', 'slug' => 'admissions.edit', 'module' => 'admissions', 'description' => 'Update student admission details'],
            ['name' => 'Delete Admissions', 'slug' => 'admissions.delete', 'module' => 'admissions', 'description' => 'Delete admission records'],
            ['name' => 'Record Payments', 'slug' => 'admissions.payment', 'module' => 'admissions', 'description' => 'Record and manage course fee payments'],

            // Attendance Management
            ['name' => 'View Attendances', 'slug' => 'attendances.view', 'module' => 'attendances', 'description' => 'View attendance logs and statistics'],
            ['name' => 'Create Attendance', 'slug' => 'attendances.create', 'module' => 'attendances', 'description' => 'Record attendance for students'],
            ['name' => 'Edit Attendance', 'slug' => 'attendances.edit', 'module' => 'attendances', 'description' => 'Update attendance records'],
            ['name' => 'Delete Attendance', 'slug' => 'attendances.delete', 'module' => 'attendances', 'description' => 'Delete attendance records'],
            ['name' => 'Mark Batch Attendance', 'slug' => 'attendances.mark', 'module' => 'attendances', 'description' => 'Mark attendance in bulk for a batch session'],

            // Academic Session Management
            ['name' => 'View Academic Sessions', 'slug' => 'sessions.view', 'module' => 'sessions', 'description' => 'View academic session years and cohorts'],
            ['name' => 'Create Academic Session', 'slug' => 'sessions.create', 'module' => 'sessions', 'description' => 'Add new academic sessions'],
            ['name' => 'Edit Academic Session', 'slug' => 'sessions.edit', 'module' => 'sessions', 'description' => 'Update academic session details'],
            ['name' => 'Delete Academic Session', 'slug' => 'sessions.delete', 'module' => 'sessions', 'description' => 'Delete academic sessions'],
            ['name' => 'Set Current Session', 'slug' => 'sessions.set_current', 'module' => 'sessions', 'description' => 'Set an academic session as active/current'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $data) {
            $perm = Permission::updateOrCreate(['slug' => $data['slug']], $data);
            $createdPermissions[$data['slug']] = $perm->id;
        }

        // Link default permissions for roles
        $roles = Role::all();
        $superAdmin = $roles->where('slug', 'super-admin')->first();
        $counselor = $roles->where('slug', 'counselor')->first();
        $instructor = $roles->where('slug', 'instructor')->first();
        $student = $roles->where('slug', 'student')->first();

        // Super Admin gets all permissions
        if ($superAdmin) {
            RolePermission::where('role_id', $superAdmin->id)->delete();
            foreach ($createdPermissions as $permId) {
                RolePermission::create([
                    'role_id' => $superAdmin->id,
                    'permission_id' => $permId,
                ]);
            }
            $superAdmin->permissions = ['*'];
            $superAdmin->save();
        }

        // Counselor permissions
        if ($counselor) {
            $counselorSlugs = [
                'leads.view', 'leads.create', 'leads.edit', 'leads.assign', 'leads.follow_up',
                'admissions.view', 'admissions.create', 'admissions.edit', 'admissions.payment',
                'courses.view', 'batches.view', 'attendances.view', 'sessions.view',
            ];
            RolePermission::where('role_id', $counselor->id)->delete();
            foreach ($counselorSlugs as $slug) {
                if (isset($createdPermissions[$slug])) {
                    RolePermission::create([
                        'role_id' => $counselor->id,
                        'permission_id' => $createdPermissions[$slug],
                    ]);
                }
            }
            $counselor->permissions = $counselorSlugs;
            $counselor->save();
        }

        // Instructor permissions
        if ($instructor) {
            $instructorSlugs = [
                'batches.view', 'courses.view', 'admissions.view',
                'attendances.view', 'attendances.create', 'attendances.edit', 'attendances.mark',
                'sessions.view',
            ];
            RolePermission::where('role_id', $instructor->id)->delete();
            foreach ($instructorSlugs as $slug) {
                if (isset($createdPermissions[$slug])) {
                    RolePermission::create([
                        'role_id' => $instructor->id,
                        'permission_id' => $createdPermissions[$slug],
                    ]);
                }
            }
            $instructor->permissions = $instructorSlugs;
            $instructor->save();
        }

        // Student permissions
        if ($student) {
            $studentSlugs = ['admissions.view', 'attendances.view', 'sessions.view'];
            RolePermission::where('role_id', $student->id)->delete();
            foreach ($studentSlugs as $slug) {
                if (isset($createdPermissions[$slug])) {
                    RolePermission::create([
                        'role_id' => $student->id,
                        'permission_id' => $createdPermissions[$slug],
                    ]);
                }
            }
            $student->permissions = $studentSlugs;
            $student->save();
        }
    }
}
