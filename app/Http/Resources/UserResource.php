<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'cv_path' => $this->cv ? url('storage/' . $this->cv) : null,
            'position' => $this->position,
            'description' => $this->description,
        ];
    }
}
