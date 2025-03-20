<?php

namespace App\Http\Controllers;

use App\Exports\CompanyExport;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends BaseController
{
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index()
    {
        $companies = Company::with(['positions'])->get();
        return $this->sendSuccess(CompanyResource::collection($companies), 'Companies retrieved successfully');
    }

    public function store(CompanyRequest $request)
    {
        $company = $this->companyService->create($request->validated());
        $company->load('positions');
        return $this->sendSuccess(new CompanyResource($company), 'Company created successfully');
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $updatedCompany = $this->companyService->update($company, $request->validated());
        return $this->sendSuccess(new CompanyResource($updatedCompany), 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $this->companyService->delete($company);
        return $this->sendSuccess([], 'Company deleted successfully');
    }

    public function exportCompanies(Request $request)
    {
        $filters = [
            'country_id' => $request->input('country_id'),
            'position' => $request->input('position'),
        ];

        return Excel::download(new CompanyExport($filters), 'filtered_companies.xlsx');
    }


}
