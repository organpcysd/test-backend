<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;

class Banner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Sluggable;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'slug',
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner');
        $this->addMediaCollection('banner_mobile');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
