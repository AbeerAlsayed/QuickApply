<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Submission extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'company_id',
        'cv',
        'description',
        'position',
        'is_sent',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
