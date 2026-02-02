<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin',  'label' => 'مدیر سیستم']);
        Role::create(['name' => 'editor', 'label' => 'ویرایشگر']);
        Role::create(['name' => 'user',   'label' => 'کاربر معمولی']);
    }
}
