<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Banner extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'banners';

    protected $fillable = [
        'title',
        'publish',
        'sort',
        'website_id',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner');
        $this->addMediaCollection('banner_mobile');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
