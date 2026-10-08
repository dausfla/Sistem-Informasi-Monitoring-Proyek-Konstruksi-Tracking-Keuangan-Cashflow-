<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'full_access'],
            ['description' => 'Full Access (BoD, GM RBK, Finance) - Full CRUD on projects, cashflows & approvals']
        );

        Role::updateOrCreate(
            ['name' => 'partial_access'],
            ['description' => 'Partial Access (Site & HO Team: Admin, Arsitek, Drafter, Pengawas, Purchasing) - Progress update & draft upload']
        );

        Role::updateOrCreate(
            ['name' => 'restricted_access'],
            ['description' => 'Restricted Access (Client & Visitor) - View only dashboard metrics, Kurva S & approved docs']
        );
    }
}
