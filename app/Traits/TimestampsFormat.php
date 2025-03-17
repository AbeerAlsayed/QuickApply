<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

trait TimestampsFormat
{
    public function createdAt(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value)->format('Y-m-d H:i:s'));
    }

    public function updatedAt(): Attribute
    {
        return Attribute::get(fn($value) => Carbon::parse($value)->format('Y-m-d H:i:s'));
    }
}
