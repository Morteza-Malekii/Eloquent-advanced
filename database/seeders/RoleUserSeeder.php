<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $roles = Role::all();

        // اگر roles خالی باشد، کار خاصی نکن
        if ($roles->isEmpty() || $users->isEmpty()) {
            return;
        }

        $users->each(function ($user) use ($roles) {
            // به هر کاربر بین 1 تا 3 نقش رندوم بده
            $roleIds = $roles->random(rand(1, min(3, $roles->count())))
                             ->pluck('id')
                             ->toArray();

            // چون روی pivot unique(user_id, role_id) گذاشتیم،
            // syncWithoutDetaching امن‌تر از attach خالی است
            $user->roles()->syncWithoutDetaching($roleIds);
        });
    }
}
