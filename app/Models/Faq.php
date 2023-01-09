<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Faq extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'slug',
        'publish',
        'sort',
        'website_id',
        'faq_category_id'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'question'
            ]
        ];
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function faq_category(){
        return $this->belongsTo(FaqCategory::class,'faq_category_id','id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}

