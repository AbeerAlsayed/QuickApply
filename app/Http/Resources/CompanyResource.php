<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'linkedin' => $this->linkedin,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'country_id' => $this->country ? $this->country->id : null,
            'country_name' => $this->country ? $this->country->name : null,

            // Returning positions with id and title, using map
            'positions' => $this->whenLoaded('positions', function () {
                return $this->positions->map(function ($position) {
                    return [
                        'id' => $position->id,
                        'title' => $position->title,
                    ];
                });
            }),
        ];
    }
}
