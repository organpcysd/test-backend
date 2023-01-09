<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class WebsiteBranch extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'website_branches';
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'line',
        'line_token',
        'facebook',
        'messenger',
        'google_map',
        'website_id'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
