<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin; // Use your Admin model

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // uncheck foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Permission::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Define permissions for the 'admin' guard
        $permissions = [
            // Dashboard
            ['name' => 'view dashboard', 'group' => 'dashboard'],

            // Role Management
            ['name' => 'view roles', 'group' => 'roles'],
            ['name' => 'create role', 'group' => 'roles'],
            ['name' => 'edit role', 'group' => 'roles'],
            ['name' => 'delete role', 'group' => 'roles'],

            // Admin Management
            ['name' => 'view admins', 'group' => 'admins'],
            ['name' => 'create admin', 'group' => 'admins'],
            ['name' => 'edit admin', 'group' => 'admins'],
            ['name' => 'delete admin', 'group' => 'admins'],

            // Settings
            ['name' => 'manage settings', 'group' => 'settings'],
            ['name' => 'manage profile', 'group' => 'settings'],
            ['name' => 'manage site colors', 'group' => 'settings'],

            // Customer Management
            ['name' => 'view customers', 'group' => 'customers'],
            ['name' => 'manage customers', 'group' => 'customers'],

            // Content Management
            ['name' => 'view pages', 'group' => 'pages'],
            ['name' => 'create page', 'group' => 'pages'],
            ['name' => 'edit page', 'group' => 'pages'],
            ['name' => 'delete page', 'group' => 'pages'],

            // Landing Page Management
            ['name' => 'manage landing pages', 'group' => 'pages'],

            // Category Management
            ['name' => 'view categories', 'group' => 'categories'],
            ['name' => 'create category', 'group' => 'categories'],
            ['name' => 'edit category', 'group' => 'categories'],
            ['name' => 'delete category', 'group' => 'categories'],
            ['name' => 'manage subcategories', 'group' => 'categories'],

            // Product Management
            ['name' => 'view products', 'group' => 'products'],
            ['name' => 'create product', 'group' => 'products'],
            ['name' => 'edit product', 'group' => 'products'],
            ['name' => 'delete product', 'group' => 'products'],
            ['name' => 'manage product offers', 'group' => 'products'],

            // Brand Management
            ['name' => 'view brands', 'group' => 'brands'],
            ['name' => 'create brand', 'group' => 'brands'],
            ['name' => 'edit brand', 'group' => 'brands'],
            ['name' => 'delete brand', 'group' => 'brands'],

            // Size Management
            ['name' => 'manage sizes', 'group' => 'attributes'],

            // Color Management
            ['name' => 'manage colors', 'group' => 'attributes'],

            // Order Management
            ['name' => 'view orders', 'group' => 'orders'],
            ['name' => 'manage orders', 'group' => 'orders'],
            ['name' => 'download invoice', 'group' => 'orders'],
            ['name' => 'manage incomplete orders', 'group' => 'orders'],

            // Marketing & Promotions
            ['name' => 'manage notices', 'group' => 'marketing'],
            ['name' => 'manage sliders', 'group' => 'marketing'],
            ['name' => 'manage offer sliders', 'group' => 'marketing'],
            ['name' => 'manage coupons', 'group' => 'marketing'],
            ['name' => 'manage testimonials', 'group' => 'marketing'],
            ['name' => 'manage seo', 'group' => 'marketing'],
            ['name' => 'manage meta pixel', 'group' => 'marketing'],

            // Gallery
            ['name' => 'manage gallery', 'group' => 'gallery'],

            // Blog
            ['name' => 'view blogs', 'group' => 'blog'],
            ['name' => 'create blog', 'group' => 'blog'],
            ['name' => 'edit blog', 'group' => 'blog'],
            ['name' => 'delete blog', 'group' => 'blog'],
        ];

        // Create or update permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission['name'],
                    'guard_name' => 'admin'
                ],
                [
                    'group' => $permission['group']
                ]
            );
        }

        // Create Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'admin'
            ]
        );

        // Sync all permissions to Super Admin role
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // Assign Super Admin role to the first admin (if exists)
        if ($admin = Admin::first()) {
            $admin->syncRoles([$superAdminRole]);
        }
    }
}
