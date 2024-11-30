<?php

namespace App\Exports;

use App\Models\Company;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompanyExport implements FromCollection
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Company::query();

        // تطبيق فلتر الدولة
        if (!empty($this->filters['country_id'])) {
            $query->where('country_id', $this->filters['country_id']);
        }
        if (!empty($this->filters['position'])) {
            $query->where('position', $this->filters['position']);
        }

        return $query->get();
    }
}

