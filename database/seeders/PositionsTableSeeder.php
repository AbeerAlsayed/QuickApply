<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Company;

class PositionsTableSeeder extends Seeder
{
    public function run()
    {
        // التأكد من وجود شركات في قاعدة البيانات
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->info('No companies found. Please seed the companies table first.');
            return;
        }

        // تخصيص الوظائف التي سيتم إضافتها لكل شركة
        $positions = [
            'Laravel Developer' => 'Responsible for developing and maintaining backend APIs using Laravel.',
            'React Developer' => 'Build dynamic and responsive frontend applications using React.',
            'UI/UX Designer' => 'Design user-centric interfaces with a focus on usability and aesthetics.',
            'Graphic Designer' => 'Create visually appealing graphics for web and print media.',
        ];

        // إضافة الوظائف إلى كل شركة، مع التأكد من عدم تكرار الوظائف
        foreach ($companies as $company) {
            foreach ($positions as $title => $description) {
                // التأكد من أن الوظيفة لم تُضاف مسبقًا إلى الشركة
                // سنقوم بالتحقق أولاً إذا كانت الوظيفة موجودة لهذا الشركة بالعنوان
                if (!Position::where('company_id', $company->id)->where('title', $title)->exists()) {
                    // إذا كانت الوظيفة غير موجودة، نقوم بإضافتها
                    Position::create([
                        'title' => $title,
                        'description' => $description,
                        'company_id' => $company->id,
                    ]);
                }
            }
        }
    }
}
