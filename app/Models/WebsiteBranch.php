<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteBranch extends Model
{
    use HasFactory;

    protected $table = 'website_branches';
    protected $fillable = [
        'name',
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

    public function website() {
        return $this->belongsTo(Website::class,'website_id','id');
    }
}
