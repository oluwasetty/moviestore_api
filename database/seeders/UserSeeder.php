<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()->create()->each(function ($user) {
            $roleId = Role::where('slug', 'admin')->value('id');
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $roleId,
            ]);
        });
    }
}
