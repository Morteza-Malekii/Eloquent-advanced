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
        Role::insert([
            ['name' => 'admin',  'label' => 'مدیر سیستم'],
            ['name' => 'editor', 'label' => 'ویرایشگر'],
            ['name' => 'user',   'label' => 'کاربر معمولی'],
        ]);
    }
}
