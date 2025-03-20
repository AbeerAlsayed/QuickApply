<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $countries = DB::table('countries')->pluck('id', 'name');

        foreach ($countries as $countryName => $countryId) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('companies')->insert([
                    'name' => "$countryName Company $i",
                    'email' => "company$i@example.com",
                    'country_id' => $countryId,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
