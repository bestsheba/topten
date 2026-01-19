<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin and manager role
        $adminRole = Role::updateOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $managerRole = Role::updateOrCreate(['name' => 'Manager', 'guard_name' => 'admin']);

    }
}
