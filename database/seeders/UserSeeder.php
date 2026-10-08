<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $fullRole = Role::where('name', 'full_access')->first();
        $partialRole = Role::where('name', 'partial_access')->first();
        $restrictedRole = Role::where('name', 'restricted_access')->first();

        // 1. Full Access Users
        User::updateOrCreate(
            ['email' => 'bod@rbktracker.com'],
            [
                'name' => 'Ir. Hendra Wijaya (Dirut BoD)',
                'password' => Hash::make('password'),
                'role_id' => $fullRole->id,
                'department_title' => 'Board of Directors (BoD)',
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@rbktracker.com'],
            [
                'name' => 'Siti Rahmawati, S.E. (Finance)',
                'password' => Hash::make('password'),
                'role_id' => $fullRole->id,
                'department_title' => 'Finance & Treasury',
            ]
        );

        User::updateOrCreate(
            ['email' => 'gm@rbktracker.com'],
            [
                'name' => 'Budi Santoso, S.T. (GM RBK)',
                'password' => Hash::make('password'),
                'role_id' => $fullRole->id,
                'department_title' => 'General Manager RBK',
            ]
        );

        // 2. Partial Access Users
        User::updateOrCreate(
            ['email' => 'pengawas@rbktracker.com'],
            [
                'name' => 'Rian Kurniawan (Pengawas Lapangan)',
                'password' => Hash::make('password'),
                'role_id' => $partialRole->id,
                'department_title' => 'Site Operations',
            ]
        );

        User::updateOrCreate(
            ['email' => 'arsitek@rbktracker.com'],
            [
                'name' => 'Dewi Lestari, S.Ars (Arsitek / Drafter)',
                'password' => Hash::make('password'),
                'role_id' => $partialRole->id,
                'department_title' => 'Design & Engineering',
            ]
        );

        // 3. Restricted Access Users
        User::updateOrCreate(
            ['email' => 'client@rbktracker.com'],
            [
                'name' => 'Bapak Ahmad Subagja (Client Investor)',
                'password' => Hash::make('password'),
                'role_id' => $restrictedRole->id,
                'department_title' => 'Client / External Investor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'visitor@rbktracker.com'],
            [
                'name' => 'Public Visitor',
                'password' => Hash::make('password'),
                'role_id' => $restrictedRole->id,
                'department_title' => 'Guest Visitor',
            ]
        );
    }
}
