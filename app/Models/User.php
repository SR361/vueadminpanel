<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use File;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'designation',
        'education',
        'location',
        'skills',
        'profile_photo',
        'password',
        'role',
        'token',
        'connection_id',
        'user_status',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_created_at',
    ];

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format(config('app.date_format'));
    }

    public function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => RoleType::from($value)->name,
        );
    }

    public function getProfilePhotoAttribute($value)
    {
        // dd(public_path());
        if(!isset($value)){
            return asset('default.png');
        }else{
            if (File::exists(public_path('/uploads/profile_photo/'.$value))) {
                return asset('uploads/profile_photo/'.$value);
            }else{
                return asset('default.png');
            }
        }
    }
}
