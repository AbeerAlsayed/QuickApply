<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'company_id' => $this->id,
            'company_name' => $this->name,
            'company_email' => $this->email,
            'positions' => $this->positions->map(fn($position) => [
                'position_id' => $position->id,
                'position_title' => $position->title,
            ]),
            'is_sent' => $this->users->first()->is_sent ?? false,
        ];
    }
}
