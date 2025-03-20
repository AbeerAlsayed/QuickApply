<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelNotFoundException;
use App\Http\Requests\CountryRequest;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {
        $countries = Country::all();
        return $this->sendSuccess(CountryResource::collection($countries), 'Countries retrieved successfully');
    }

    public function store(CountryRequest $request)
    {
        $country = $this->countryService->create($request->validated());
        return $this->sendSuccess(new CountryResource($country), 'Country created successfully');
    }

    public function update(CountryRequest $request, Country $country)
    {
        $updatedCountry = $this->countryService->update($country, $request->validated());
        return $this->sendSuccess(new CountryResource($updatedCountry), 'Country updated successfully');
    }

    public function destroy($id)
    {
        try {
            $country = Country::find($id);

            if (!$country) {
                throw new \App\Exceptions\ModelNotFoundException('Country', 'ID not found');
            }

            $this->countryService->delete($country);

            return $this->sendSuccess([], 'Country deleted successfully');
        } catch (\App\Exceptions\ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
