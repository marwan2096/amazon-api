<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name'        => 'Marwan',
            'last_name'         => 'Ali',
            'email'             => 'your@gmail.com',
            'phone'             => '010123456766',
            'gender'            => 'male',
            'birth_date'        => null,
            'avatar'            => null,
            'password'          => Hash::make('password123'),
            'status'            => 'active',
            'type'              => 'admin',
            'email_verified_at' => null,
        ]);


    }
}
