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

        $users->each(function(User $user) use ($roles){
            $roleIds = $roles->random(rand(1, min(3, $roles->count())))
                                ->pluck('id')
                                ->toArray();
            $user->roles()->syncWithoutDetaching($roleIds);
        });

    }
}
