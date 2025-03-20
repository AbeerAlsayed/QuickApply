<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = ['Laravel', 'React', 'UI/UX', 'Flutter'];
        $companies = DB::table('companies')->pluck('id');

        foreach ($companies as $companyId) {
            foreach ($positions as $position) {
                DB::table('positions')->insert([
                    'title' => $position,
                    'company_id' => $companyId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
