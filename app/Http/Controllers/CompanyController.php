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

    // Delete a company
    public function destroy(Company $company)
    {
        $this->companyService->delete($company);
        return $this->sendSuccess([], 'Company deleted successfully');
    }

    public function exportCompanies(Request $request)
    {
        $filters = [
            'country_id' => $request->input('country_id'), // فلتر الدولة
            'position' => $request->input('position'),
        ];

        return Excel::download(new CompanyExport($filters), 'filtered_companies.xlsx');
    }

    public function getCompaniesByCountry($countryId, $userId)
    {
        // البحث عن البلد
        $country = Country::find($countryId);

        if (!$country) {
            return response()->json(['message' => 'Country not found'], 404);
        }

        // البحث عن المستخدم
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // جلب الشركات المرتبطة بالبلد مع المناصب
        $companies = $country->companies()->with('positions')->get();

        // تنسيق النتائج لكل شركة بناءً على حالة is_sent الخاصة بالمستخدم
        $results = $companies->map(function ($company) use ($user) {
            // التحقق مما إذا كان المستخدم قد أرسل إلى هذه الشركة أم لا
            $isSent = $company->users()
                ->where('user_id', $user->id)
                ->exists() ? $company->users()->where('user_id', $user->id)->first()->pivot->is_sent : false;

            return [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'company_email' => $company->email,
                'positions' => $company->positions->map(function ($position) {
                    return [
                        'position_id' => $position->id,
                        'position_title' => $position->title,
                    ];
                }),
                'is_sent' => $isSent,
            ];
        });

        return response()->json($results);
    }

}
