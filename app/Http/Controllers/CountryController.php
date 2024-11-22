<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\CountryService;

class CountryController extends BaseController
{
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    // عرض جميع الدول
    public function index()
    {
        $countries = Country::all();
        return $this->sendSuccess(CountryResource::collection($countries), 'Countries retrieved successfully');
    }

    // عرض دولة معينة
    public function show(Country $country)
    {
        return $this->sendSuccess(new CountryResource($country), 'Country retrieved successfully');
    }

    // إنشاء دولة جديدة
    public function store(CountryRequest $request)
    {
        $country = $this->countryService->create($request->validated());
        return $this->sendSuccess(new CountryResource($country), 'Country created successfully');
    }

    // تحديث بيانات دولة
    public function update(CountryRequest $request, Country $country)
    {
        $updatedCountry = $this->countryService->update($country, $request->validated());
        return $this->sendSuccess(new CountryResource($updatedCountry), 'Country updated successfully');
    }

    // حذف دولة
    public function destroy(Country $country)
    {
        $this->countryService->delete($country);
        return $this->sendSuccess([], 'Country deleted successfully');
    }
}
