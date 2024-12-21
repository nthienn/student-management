<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_permissions = Permission::all();

        $user_permission = Permission::where('title', 'student_access')->get();

        Role::find(1)->permissions()->attach($admin_permissions);

        Role::find(2)->permissions()->attach($user_permission);
    }
}