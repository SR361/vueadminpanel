<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    protected $table = 'companies';
    use HasApiTokens, Notifiable;
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_email',
        'company_phone',
        'website',
        'address',
        'currency_id',
        'timezone',
        'locale',
        'date_format',
        'time_format',
        'week_start',
        'longitude',
        'latitude'
    ];
}
