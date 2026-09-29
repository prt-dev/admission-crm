<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Admin User
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@admissioncrm.local',
                'password' => Hash::make('Admin@12345'),
                'role_id' => 1,
                'status' => 1,
                'last_login_at' => now(),
            ]
        );

        // Default Counselor User
        User::firstOrCreate(
            ['username' => 'counselor1'],
            [
                'name' => 'Admission Counselor',
                'email' => 'counselor@admissioncrm.local',
                'password' => Hash::make('Counselor@12345'),
                'role_id' => 2,
                'status' => 1,
                'last_login_at' => null,
            ]
        );

        // Seed additional dummy users
        User::factory(10)->create();
    }
}
