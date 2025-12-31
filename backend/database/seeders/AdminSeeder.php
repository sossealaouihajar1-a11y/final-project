<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'Super Admin ',
            'email' => 'admin@vintage-shop.com',
            'password' => Hash::make('Admin@123456'),
            'role' => 'admin',
            'phone' => '+212600000000',
            'city' => 'fes',
            'address' => 'Adresse admin',
     
        ]);

    }
}

