<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إدخال بيانات الشركات
        $companies = [
            [
                'name' => 'شركة ألمانية',
                'email' => 'somaia96.sh@gmail.com',
                'country_id' => 1,  // تأكد من أن هذه الـ country_id موجودة في جدول countries
            ],
            [
                'name' => 'شركة مصرية',
                'email' => 'abeerosami1996@gmail.com',
                'country_id' => 1,
            ],
            [
                'name' => 'شركة إماراتية',
                'email' => 'abeer.sami.alsayed@gmail.com',
                'country_id' => 1,
            ],
        ];

        // إدخال الشركات في قاعدة البيانات
        foreach ($companies as $company) {
            $companyId = DB::table('companies')->insertGetId($company);

            // إدخال الوظائف المتعلقة بالشركة
            $positions = [
                'Laravel Developer',
                'React Developer',
                'UI/UX Designer',
                'Graphic Designer',
            ];

            $positionsData = [];

            foreach ($positions as $positionTitle) {
                $positionsData[] = [
                    'company_id' => $companyId,
                    'title' => $positionTitle,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // استخدام insertOrIgnore لإدخال البيانات بدون تكرار
            DB::table('positions')->insertOrIgnore($positionsData);
        }
    }
}
