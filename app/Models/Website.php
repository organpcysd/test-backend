<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Website extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'websites';
    protected $fillable = [
        'name',
        'domain_name',
        'title',
        'address',
        'phone1',
        'phone2',
        'fax',
        'company_number',
        'email1',
        'email2',
        'line',
        'line_token',
        'facebook',
        'messenger',
        'youtube',
        'youtube_embed',
        'instagram',
        'twitter',
        'linkedin',
        'whatsapp',
        'google_map',
        'about_us',
        'short_about_us',
        'seo_keyword',
        'seo_description'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('website_logo');
        $this->addMediaCollection('website_favicon');
        $this->addMediaCollection('website_ogimage');
        $this->addMediaCollection('website_banner_aboutus');
        $this->addMediaCollection('website_banner_product');
        $this->addMediaCollection('website_banner_service');
        $this->addMediaCollection('website_banner_promotion');
        $this->addMediaCollection('website_banner_news');
        $this->addMediaCollection('website_banner_faq');
        $this->addMediaCollection('website_banner_contact');
    }

    public function user() {
        return $this->hasOne(User::class,'id','website_id');
    }

    public function website_branche() {
        return $this->hasMany(WebsiteBranch::class,'id','website_id');
    }

    public function news() {
        return $this->hasMany(News::class,'id','website_id');
    }

    public function banner() {
        return $this->hasMany(Banner::class,'id','website_id');
    }

    public function promotion() {
        return $this->hasMany(Promotion::class,'id','website_id');
    }

    public function service() {
        return $this->hasMany(Service::class,'id','website_id');
    }

    public function product_category() {
        return $this->hasMany(ProductCategory::class,'id','website_id');
    }

    public function sub_product_category() {
        return $this->hasMany(SubProductCategory::class,'id','website_id');
    }

    public function product() {
        return $this->hasMany(Product::class,'id','website_id');
    }

    public function faq_category() {
        return $this->hasMany(FaqCategory::class,'id','website_id');
    }

    public function faq() {
        return $this->hasMany(Faq::class,'id','website_id');
    }

}
