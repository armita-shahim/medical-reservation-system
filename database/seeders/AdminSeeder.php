<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\UserRole;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if ($admin) {
            return;
        }

        $admin = new User();

        $admin->name = 'Admin';
        $admin->email = 'admin@example.com';
        $admin->password = 'adminPassword123';
        $admin->role = UserRole::Admin;
        $admin->is_active = true;
        $admin->email_verified_at = now();

        $admin->save();
    }
}
