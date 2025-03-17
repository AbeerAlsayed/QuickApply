<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    public function create(array $data): Company
    {
        return DB::transaction(function () use ($data) {
            $company = Company::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'country_id' => $data['country_id'],
            ]);

            if (isset($data['positions']) && is_array($data['positions'])) {
                foreach ($data['positions'] as $positionData) {
                    $company->positions()->create([
                        'title' => $positionData['title'],
                    ]);
                }
            }

            return $company;
        });
    }
    public function update(Company $company, array $data): Company
    {
        return DB::transaction(function () use ($company, $data) {
            $company->update($data);
            return $company;
        });
    }

    public function delete(Company $company): void
    {
        DB::transaction(function () use ($company) {
            $company->delete();
        });
    }
}
