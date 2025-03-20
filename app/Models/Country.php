<?php

namespace App\Models;

use App\Traits\TimestampsFormat;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Country extends Model
{
    use HasFactory, Notifiable, TimestampsFormat;
    protected $fillable = ['name', 'code'];

    public function companies(){
        return $this->hasMany(Company::class);
    }


}
