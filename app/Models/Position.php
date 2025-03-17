<?php

namespace App\Models;

use App\Traits\TimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, TimestampsFormat;

    protected $fillable = ['title', 'company_id'];

    // Relationship with Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

