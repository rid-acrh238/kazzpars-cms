<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'kazzpars0@gmail.com';

        // Hanya buat admin kalau belum ada — password TIDAK
        // di-reset setiap kali seeder dijalankan ulang.
        if (User::where('email', $email)->exists()) {
            return;
        }

        User::create([
            'name' => 'Kazzpars',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);
    }
}
