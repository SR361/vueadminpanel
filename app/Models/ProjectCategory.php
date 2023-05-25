<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $table = 'project_categories';

    use HasFactory;

    protected $fillable = [
        'company_id',
        'category_name',
    ];

    // protected $attributes = [
    //     'company_id' => Auth::guard('api')->user()->company_id,
    // ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::observe(ProjectCategoryObserver::class);
    //     $company = company();
    //     static::addGlobalScope('company', function (Builder $builder) use($company) {
    //         if ($company) {
    //             $builder->where('project_category.company_id', '=', $company->id);
    //         }
    //     });
    // }
}
