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

    public function scopeFilterByEmailAndCountry($query, $email, $countryName)
    {
        return $query->when($email, function($query) use ($email) {
            return $query->where('email', 'like', "%$email%");
        })
            ->when($countryName, function($query) use ($countryName) {
                return $query->whereHas('country', function($query) use ($countryName) {
                    return $query->where('name', 'like', "%$countryName%"); // نبحث هنا عن اسم البلد في جدول `countries`
                });
            });
    }

}
