<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasFactory;

    protected $table = 'faq_categories';

    protected $fillable = [
        'title',
        'publish',
        'sort',
        'website_id'
    ];

    public function faq(){
        return $this->hasMany(Faq::class,'id','faq_category_id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
