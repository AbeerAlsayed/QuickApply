<?php

namespace App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountryService
{
    public function create(array $data): Country
    {
        return DB::transaction(function () use ($data) {
            return Country::create($data);
        });
    }

    public function update(Country $country, array $data): Country
    {
        return DB::transaction(function () use ($country, $data) {
            $country->update($data);
            return $country;
        });
    }


    public function delete(Country $country)
    {
        return DB::transaction(function () use ($country) {
            return $country->delete();
        });
    }
}
