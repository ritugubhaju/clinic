<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $exists = DB::selectOne('SELECT id FROM users WHERE email = ?', ['admin@clinic.com']);

        if ($exists) {
            return;
        }

        DB::insert(
            'INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())',
            ['Super Admin', 'admin@clinic.com', Hash::make('password'), 'super_admin']
        );

        $user = User::where('email', 'admin@clinic.com')->first();
        $user->assignRole('super_admin');
    }
}
