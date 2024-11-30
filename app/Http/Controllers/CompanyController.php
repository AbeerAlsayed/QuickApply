<?php

namespace App\Http\Controllers;

use App\Exports\CompanyExport;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
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

    // List all companies
    public function index()
    {
        $companies = Company::with('positions', 'country')->get();
        return $this->sendSuccess(CompanyResource::collection($companies), 'Companies retrieved successfully');
    }

    // Show a single company
    public function show(Company $company)
    {
        return $this->sendSuccess(new CompanyResource($company), 'Company retrieved successfully');
    }

    // Create a new company
    public function store(CompanyRequest $request)
    {
        $company = $this->companyService->create($request->validated());
        return $this->sendSuccess(new CompanyResource($company), 'Company created successfully');
    }

    // Update a company
    public function update(CompanyRequest $request, Company $company)
    {
        $updatedCompany = $this->companyService->update($company, $request->validated());
        return $this->sendSuccess(new CompanyResource($updatedCompany), 'Company updated successfully');
    }

    // Delete a company
    public function destroy(Company $company)
    {
        $this->companyService->delete($company);
        return $this->sendSuccess([], 'Company deleted successfully');
    }

    // دالة الفلتر
//    public function filter(Request $request)
//    {
//        // اجلب البريد الإلكتروني واسم البلد من الطلب
//        $email = $request->query('email');
//        $countryName = $request->query('country_name');
//
//        // فلترة الشركات باستخدام الـ Scope
//        $companies = Company::filterByEmailAndCountry($email, $countryName)->get();
//
//        // إعادة الرد بالنتائج
//        return response()->json($companies);
//    }
    public function exportCompanies(Request $request)
    {
        $filters = [
            'country_id' => $request->input('country_id'), // فلتر الدولة
            'position' => $request->input('position'),
        ];

        return Excel::download(new CompanyExport($filters), 'filtered_companies.xlsx');
    }
}
