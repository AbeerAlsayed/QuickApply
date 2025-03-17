<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company->name,
            'email' => $this->user->email,
            'position' => $this->user->position,
            'is_sent' => $this->is_sent,
            'country' => $this->company->country->name,
        ];
    }
}
