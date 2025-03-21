<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\PositionEnum;
use App\Traits\TimestampsFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TimestampsFormat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'education',
        'experience',
        'skills',
        'position',
        'cv',
        'message',
    ];


    protected $casts = [
        'position' => PositionEnum::class, // تحويل الحقل إلى Enum تلقائيًا
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function scopeFilterByEmail($query, $email)
    {
        if ($email) {
            return $query->where('email', 'like', "%$email%");
        }
        return $query;
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'submissions')->withPivot('is_sent');
    }
    public function tests()
    {
        return $this->hasMany(Test::class);
    }

}
