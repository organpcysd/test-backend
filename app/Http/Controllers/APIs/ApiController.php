<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\Banner;

class ApiController extends Controller
{
    // Banners
    public function getDataBanners($website_code){

        $website = Website::where('website_code',$website_code)->first();
        $banners = Banner::where('publish',1)->where('website_id',$website->id)->get();

        foreach ($banners as $key => $banner) {
            $banners[$key]['img_website'] = ($banner->getFirstMediaUrl('banner') ? $banner->getFirstMediaUrl('banner') : asset('images/no-image.jpg'));
            $banners[$key]['img_mobile'] = ($banner->getFirstMediaUrl('banner_mobile') ? $banner->getFirstMediaUrl('banner_mobile') : asset('images/no-image.jpg'));
        }

        $string = "organ";
        $str_len = strlen($string);

        $str_to_insert = 'x';

        for($i = 0; $i <= 5; $i++){
            if($i%2 == 0){
                $string = substr_replace($string, $str_to_insert, $i+1, 0);
            }
        }

        dd($string);

        return response()->json([$banners], 200);
        // return response()->json([$banners], 200);
    }
}
