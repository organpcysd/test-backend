<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelperController;
// use App\Http\Controllers\Helper\DecodedDataController;
use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\Banner;

class ApiController extends Controller
{
    // Banners
    public function getDataBanners($website_code){

        $website = Website::where('website_code',base64_decode($website_code))->first();
        $banners = Banner::where('publish',1)->where('website_id',$website->id)->get();

        foreach ($banners as $key => $banner) {
            $banners[$key]['img_website'] = ($banner->getFirstMediaUrl('banner') ? $banner->getFirstMediaUrl('banner') : asset('images/no-image.jpg'));
            $banners[$key]['img_mobile'] = ($banner->getFirstMediaUrl('banner_mobile') ? $banner->getFirstMediaUrl('banner_mobile') : asset('images/no-image.jpg'));
        }

        $banners = (new HelperController)->dataparsing($banners,$website_code);

        return response()->json($banners, 200);
    }
}
