<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'phone_code'
    ];

    // العلاقة مع الشركات
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
