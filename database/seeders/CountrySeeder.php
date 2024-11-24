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
            ['name' => 'ألمانيا', 'code' => 'DE'],
            ['name' => 'سوريا', 'code' => 'SY'],
            ['name' => 'مصر', 'code' => 'EG'],
            ['name' => 'الإمارات', 'code' => 'AE'],
            ['name' => 'السعودية', 'code' => 'SA'],
            ['name' => 'تركيا', 'code' => 'TR'],
            ['name' => 'الأردن', 'code' => 'JO'],
        ]);
    }
}
