<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyUserSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $companies = Company::all();

        foreach ($users as $user) {
            foreach ($companies->random(2) as $company) {
                DB::table('submissions')->insert([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'is_sent' => 0,
                ]);
            }
        }
    }
}
