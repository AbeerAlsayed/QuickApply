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
                'phone_number' => '+49 123 456789',
                'address' => 'Berlin, Germany',
                'linkedin' => 'https://linkedin.com/company/germanco',
                'facebook' => 'https://facebook.com/germanco',
                'twitter' => 'https://twitter.com/germanco',
                'instagram' => 'https://instagram.com/germanco',
                'country_id' => 1,  // تأكد من أن هذه الـ country_id موجودة في جدول countries
            ],
            [
                'name' => 'شركة مصرية',
                'email' => 'abeerosami1996@gmail.com',
                'phone_number' => '+20 987 654321',
                'address' => 'Cairo, Egypt',
                'linkedin' => 'https://linkedin.com/company/egcompany',
                'facebook' => 'https://facebook.com/egcompany',
                'twitter' => 'https://twitter.com/egcompany',
                'instagram' => 'https://instagram.com/egcompany',
                'country_id' => 1,
            ],
            [
                'name' => 'شركة إماراتية',
                'email' => 'abeer.sami.alsayed@gmail.com',
                'phone_number' => '+971 555 123456',
                'address' => 'Dubai, UAE',
                'linkedin' => 'https://linkedin.com/company/aecompany',
                'facebook' => 'https://facebook.com/aecompany',
                'twitter' => 'https://twitter.com/aecompany',
                'instagram' => 'https://instagram.com/aecompany',
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
                    'description' => null, // أو أي قيمة أخرى تحتاجها
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // استخدام insertOrIgnore لإدخال البيانات بدون تكرار
            DB::table('positions')->insertOrIgnore($positionsData);
        }
    }
}
