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
            ['name' => 'Germany', 'code' => 'DE'],
            ['name' => 'Syria', 'code' => 'SY'],
            ['name' => 'Egypt', 'code' => 'EG'],
            ['name' => 'United Arab Emirates', 'code' => 'AE'],
            ['name' => 'Saudi Arabia', 'code' => 'SA'],
            ['name' => 'Turkey', 'code' => 'TR'],
            ['name' => 'Jordan', 'code' => 'JO'],
        ]);

    }
}
