<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'publish',
        'sort',
        'website_id',
        'faq_category_id'
    ];

    public function faq_category(){
        return $this->belongsTo(FaqCategory::class,'faq_category_id','id');
    }

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}

