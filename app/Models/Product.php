<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $table = 'products';

    protected $fillable = [
        'title',
        'price',
        'price_promotion',
        'promotion_status',
        'bestsell_status',
        'recommended_status',
        'short_detail',
        'detail',
        'slug',
        'seo_keyword',
        'seo_description',
        'publish',
        'sort',
        'product_category_id',
        'sub_product_category_id',
        'website_id'
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
        $this->addMediaCollection('product');
    }

    public function product_category(){
        return $this->belongsTo(ProductCategory::class,'product_category_id','id');
    }

    public function sub_product_category(){
        return $this->belongsTo(SubProductCategory::class,'sub_product_category_id','id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
