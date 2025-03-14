<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'sami',
            'email' => 'sami@gmail.com',
            'password' => Hash::make('abeersami'),
            'education' => 'Bachelor’s Degree in Computer Science',
            'experience' => '5 years in software development',
            'skills' => 'Laravel, Vue.js, MySQL, Docker',
            'position' => 'Full Stack Developer',
            'cv' => null,
            'message' => 'Available for new opportunities!',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password456'),
            'education' => 'Master’s Degree in Artificial Intelligence',
            'experience' => '3 years in AI application development',
            'skills' => 'Python, TensorFlow, Laravel, PostgreSQL',
            'position' => 'AI Engineer',
            'cv' => 'cvs/jane_smith_cv.pdf',
            'message' => 'Passionate about AI and data science!',
            'email_verified_at' => now(),
        ]);
    }
}
