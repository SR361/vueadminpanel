<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class ProductGalleryImages extends Model
{
    use HasFactory;
    protected $fillable =[
        'pid',
        'image'
    ];

    public function getImageAttribute($value)
    {
        // dd(public_path());
        if(!isset($value)){
            return asset('default.png');
        }else{
            if (File::exists(public_path('/uploads/productgallery/'.$value))) {
                return asset('uploads/productgallery/'.$value);
            }else{
                return asset('default.png');
            }
        }
    }
}
