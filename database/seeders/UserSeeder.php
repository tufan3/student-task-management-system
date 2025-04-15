<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Head Master',
            'email' => 'headmaster@gmail.com',
            'phone' => '01313123654',
            'password' => Hash::make('123456789'),
            'role' => 'headmaster',
        ]);
    }
}
