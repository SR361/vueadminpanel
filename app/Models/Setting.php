<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'site_logo',
        'theme',
        'footer',
        'price',
        'header_setting',
        'sidebar_option',
        'sidebar_variants',
        'navbar_variants',
        'accent_color_variants',
        'dark_sidebar_variants',
        'light_sidebar_variants',
        'brand_logo_variants',
    ];

    public function getSiteLogoAttribute($value)
    {
        if(!isset($value)){
            return asset('default.png');
        }else{
            if (File::exists(public_path('/uploads/setting/'.$value))) {
                return asset('uploads/setting/'.$value);
            }else{
                return asset('default.png');
            }
        }
    }

    public function setSidebarOptionAttribute($value)
    {
        $this->attributes['sidebar_option'] = json_encode($value);
    }

    public function getSidebarOptionAttribute($value)
    {
        return $this->attributes['sidebar_option'] = json_decode($value);
    }

    public function setHeaderSettingAttribute($value)
    {
        $this->attributes['header_setting'] = json_encode($value);
    }

    public function getHeaderSettingAttribute($value)
    {
        return $this->attributes['header_setting'] = json_decode($value);
    }
}
