<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'cid',
        'sid',
        'name',
        'qty',
        'price',
        'description',
        'image',
        'status',
    ];
    protected $append = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        if(!isset($this->image)){
            return asset('default.png');
        }else{
            if (File::exists(public_path('public/uploads/product'.$this->image))) {
                return asset('public/uploads/products/'.$this->image);
            }else{
                return asset('default.png');
            }
        }
    }

    public function getImageAttribute($value)
    {
        // dd(public_path());
        if(!isset($value)){
            return asset('default.png');
        }else{
            if (File::exists(public_path('/uploads/product/'.$value))) {
                return asset('uploads/product/'.$value);
            }else{
                return asset('default.png');
            }
        }
    }

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'cid');
    }

    public function childcategory()
    {
        return $this->belongsTo(Categorie::class, 'sid');
    }

    public function productimages()
    {
        return $this->hasMany(ProductGalleryImages::class, 'pid');
    }
}
