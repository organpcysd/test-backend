<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;

class SubProductCategory extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $table = 'sub_product_categories';

    protected $fillable = [
        'title',
        'detail',
        'slug',
        'publish',
        'sort',
        'website_id',
        'product_category_id'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('sub_product_category');
    }

    public function product_category(){
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }

    public function product(){
        return $this->hasMany(Product::class,'id','sub_product_category_id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
