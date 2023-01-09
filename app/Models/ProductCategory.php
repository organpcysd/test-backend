<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;

class ProductCategory extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use Sluggable;

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'detail',
        'website_id',
        'slug',
        'publish',
        'sort',
        'parent_id',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_category');
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public static function tree()
    {
        $allCategories = ProductCategory::get();

        $rootCategories = $allCategories->whereNull('parent_id');

        self::formatTree($rootCategories, $allCategories);

        return $rootCategories;
    }

    private static function formatTree($categories, $allCategories)
    {
        foreach ($categories as $category) {
            $category->children = $allCategories->where('parent_id', $category->id)->values();

            if($category->children->isNotEmpty()) {
                self::formatTree($category->children, $allCategories);
            }
        }
    }

    public function isChild(): bool
    {
        return $this->parent_id != null;
    }

    public function product(){
        return $this->hasMany(Product::class,'id','product_category_id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
