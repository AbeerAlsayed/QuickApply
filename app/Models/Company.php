<?php

namespace App\Models;

use App\Traits\TimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use HasFactory, Notifiable, TimestampsFormat;

    protected $fillable = [
        'name',
        'email',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'submissions')->withPivot('is_sent');
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

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
