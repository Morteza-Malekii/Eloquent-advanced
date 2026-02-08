<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) نقش‌ها (دیتای واقعی سیستم)
        $this->call(RoleSeeder::class);

        // 2) ساخت یوزرها + پست‌ها + کامنت‌ها (همه از طریق factoryها)
        User::factory(20)->create();

        // 3) اختصاص roleها به یوزرها (اگر هنوز RoleUserSeeder داری)
        $this->call(RoleUserSeeder::class);
    }
}
