<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'abeer',
            'email' => 'abeer@gmail.com',
            'password' => Hash::make('password123'),
            'cv' => null, // يمكن تركه فارغاً أو وضع قيمة افتراضية
            'position' => 'Developer',
            'description' => 'This is a test user for seeding data.',
        ]);
    }
}
