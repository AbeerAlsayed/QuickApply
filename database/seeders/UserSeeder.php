<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password'=>Hash::make('password'),
                'facebook' => 'facebook.com/janesmith',
                'instagram' => 'instagram.com/janesmith',
                'linkedin' => 'linkedin.com/in/janesmith',
                'phone_number' => '+9876543210',
                'address' => '456 Elm Road, LA',
                'cv_path' => '/cvs/jane_smith_cv.pdf',
                'desired_position' => 'Data Scientist',
                'description' => 'Passionate about AI and data analytics.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed.ali@example.com',
                'facebook' => null,
                'password'=>Hash::make('password'),
                'instagram' => 'instagram.com/ahmedali',
                'linkedin' => 'linkedin.com/in/ahmedali',
                'phone_number' => '+1122334455',
                'address' => '789 Oak Avenue, Cairo',
                'cv_path' => '/cvs/ahmed_ali_cv.pdf',
                'desired_position' => 'Marketing Manager',
                'description' => 'Specialized in digital marketing and branding.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert users into the database
        User::insert($users);
    }
}
