<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;

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
        $companies = Company::with('country')->get();
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
}
