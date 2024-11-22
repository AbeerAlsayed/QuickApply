<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'linkedin',
        'facebook',
        'twitter',
        'instagram',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
