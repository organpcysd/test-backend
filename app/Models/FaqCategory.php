<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class FaqCategory extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'faq_categories';

    protected $fillable = [
        'title',
        'publish',
        'slug',
        'sort',
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

    public function faq(){
        return $this->hasMany(Faq::class,'id','faq_category_id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
