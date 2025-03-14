<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إدخال بيانات الدول
        DB::table('countries')->insert([
            ['name' => 'Germany'],
            ['name' => 'Syria'],
            ['name' => 'Egypt'],
            ['name' => 'United Arab Emirates'],
            ['name' => 'Saudi Arabia'],
            ['name' => 'Turkey'],
            ['name' => 'Jordan'],
        ]);

    }
}
