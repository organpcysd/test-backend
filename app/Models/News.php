<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;

class News extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Sluggable;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'short_detail',
        'detail',
        'seo_keyword',
        'seo_description',
        'publish',
        'sort',
        'website_id',
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
        $this->addMediaCollection('news');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
