<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'company_id'];

    // Relationship with Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

