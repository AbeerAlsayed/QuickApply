<?php

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
        DB::table('companies')->insert([
            [
                'name' => 'شركة ألمانية',
                'email' => 'info@germanco.com',
                'phone_number' => '+49 123 456789',
                'address' => 'Berlin, Germany',
                'linkedin' => 'https://linkedin.com/company/germanco',
                'facebook' => 'https://facebook.com/germanco',
                'twitter' => 'https://twitter.com/germanco',
                'instagram' => 'https://instagram.com/germanco',
                'country_id' => 1,  // تأكد من أن هذه الـ country_id موجودة في جدول countries
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شركة مصرية',
                'email' => 'contact@egcompany.com',
                'phone_number' => '+20 987 654321',
                'address' => 'Cairo, Egypt',
                'linkedin' => 'https://linkedin.com/company/egcompany',
                'facebook' => 'https://facebook.com/egcompany',
                'twitter' => 'https://twitter.com/egcompany',
                'instagram' => 'https://instagram.com/egcompany',
                'country_id' => 3,  // تأكد من أن هذه الـ country_id موجودة في جدول countries
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'شركة إماراتية',
                'email' => 'contact@aecompany.com',
                'phone_number' => '+971 555 123456',
                'address' => 'Dubai, UAE',
                'linkedin' => 'https://linkedin.com/company/aecompany',
                'facebook' => 'https://facebook.com/aecompany',
                'twitter' => 'https://twitter.com/aecompany',
                'instagram' => 'https://instagram.com/aecompany',
                'country_id' => 4,  // تأكد من أن هذه الـ country_id موجودة في جدول countries
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // يمكنك إضافة المزيد من الشركات هنا...
        ]);
    }
}
