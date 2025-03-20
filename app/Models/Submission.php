<?php

namespace App\Models;

use App\Traits\TimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Submission extends Model
{
    use HasFactory, Notifiable, TimestampsFormat;

    protected $fillable = [
        'user_id',
        'company_id',
        'is_sent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
