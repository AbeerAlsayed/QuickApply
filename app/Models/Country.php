<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code'];

    public function companies(){
        return $this->hasMany(Company::class);
    }
    // تعريف الـ scope
    public function scopeFilterByNameOrCode($query, $searchTerm)
    {
        return $query->where('name', 'like', "%$searchTerm%")
            ->orWhere('code', 'like', "%$searchTerm%");
    }
}
