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
            'education' => $this->education,
            'experience' => $this->experience,
            'skills' => explode(',', $this->skills),
            'position' => $this->position,
            'cv_path' => $this->cv ? url('storage/' . $this->cv) : null,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
