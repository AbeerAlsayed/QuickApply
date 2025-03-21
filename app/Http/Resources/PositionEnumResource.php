<?php

// app/Http/Resources/PositionEnumResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionEnumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'value' => $this->value,
        ];
    }
}
