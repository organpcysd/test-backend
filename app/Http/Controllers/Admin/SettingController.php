<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Facades\Auth;

use App\Models\Website;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasAnyRole('superadmin','admin')){
            return view('admin.settings.backend.index');
        }else{
            return redirect()->route('settings.edit',['setting' => Auth::user()->website->slug]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        setting(['title' => $request->title])->save();
        setting(['detail' => $request->detail])->save();

        if ($request->file('img_favicon')){
            if(!empty(setting('img_favicon'))){
                File::delete(public_path(setting('img_favicon')));
            }
            //resize image
            $path = storage_path('tmp/uploads');
            $imgwidth = 100;
            $imgheight = 100;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img_favicon');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $full_path = public_path('uploads/setting/'.$name);
            $img = \Image::make($file->getRealPath());
            $img->resize($imgwidth, $imgheight, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path);
            setting(['img_favicon' => 'uploads/setting/'.$name])->save();
        }

        if ($request->file('img_logo')){
            if(!empty(setting('img_logo'))){
                File::delete(public_path(setting('img_logo')));
            }
            //resize image
            $path = storage_path('tmp/uploads');
            $imgwidth = 500;
            $imgheight = 500;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img_logo');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $full_path = public_path('uploads/setting/'.$name);
            $img = \Image::make($file->getRealPath());
            $img->resize($imgwidth, $imgheight, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path);
            setting(['img_logo' => 'uploads/setting/'.$name])->save();
        }

        if ($request->file('img_og')){
            if(!empty(setting('img_og'))){
                File::delete(public_path(setting('img_og')));
            }
            //resize image
            $path = storage_path('tmp/uploads');
            $imgwidth = 150;
            $imgheight = 150;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img_og');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $full_path = public_path('uploads/setting/'.$name);
            $img = \Image::make($file->getRealPath());
            $img->resize($imgwidth, $imgheight, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path);
            setting(['img_og' => 'uploads/setting/'.$name])->save();
        }

        Alert::success('บันทึกข้อมูลสำเร็จ');
        return redirect()->route('settings.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $website = Website::whereId(Auth::user()->website_id)->first();

        return view('admin.settings.website.index',compact('website'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $website = Website::whereId($id)->first();
        // $website->name = $request->name;
        // $website->domain_name = $request->domain_name;
        $website->title = $request->title;
        $website->address = $request->address;
        $website->phone1 = $request->phone1;
        $website->phone2 = $request->phone2;
        $website->fax = $request->fax;
        $website->company_number = $request->company_number;
        $website->email1 = $request->email1;
        $website->email2 = $request->email2;
        $website->line = $request->line;
        $website->line_token = $request->line_token;
        $website->facebook = $request->facebook;
        $website->messenger = $request->messenger;
        $website->youtube = $request->youtube;
        $website->youtube_embed = $request->youtube_embed;
        $website->instagram = $request->instagram;
        $website->twitter = $request->twitter;
        $website->linkedin = $request->linkedin;
        $website->whatsapp = $request->whatsapp;
        $website->google_map = $request->google_map;
        $website->about_us = $request->about_us;
        $website->short_about_us = $request->short_about_us;
        $website->seo_keyword = $request->seo_keyword;
        $website->seo_description = $request->seo_description;

        if($website->save()){
            if($request->file('img_logo')){
                $medias = $website->getMedia('website_logo');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->img_logo;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->img_logo;
                $img_logo = Image::make($getImage->getRealPath());
                $img_logo->resize(500, 500, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_logo');
            }

            if($request->file('img_favicon')){
                $medias = $website->getMedia('website_favicon');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->img_favicon;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->img_favicon;
                $img_favicon = Image::make($getImage->getRealPath());
                $img_favicon->resize(100, 100, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_favicon');
            }

            if($request->file('img_og')){
                $medias = $website->getMedia('website_ogimage');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->img_og;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->img_og;
                $img_og = Image::make($getImage->getRealPath());
                $img_og->resize(150, 150, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_ogimage');
            }

            if($request->file('banner_aboutus')){
                $medias = $website->getMedia('website_banner_aboutus');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_aboutus;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_aboutus;
                $banner_aboutus = Image::make($getImage->getRealPath());
                $banner_aboutus->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_aboutus');
            }

            if($request->file('banner_product')){
                $medias = $website->getMedia('website_banner_product');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_product;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_product;
                $banner_product = Image::make($getImage->getRealPath());
                $banner_product->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_product');
            }

            if($request->file('banner_service')){
                $medias = $website->getMedia('website_banner_service');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_service;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_service;
                $banner_service = Image::make($getImage->getRealPath());
                $banner_service->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_service');
            }

            if($request->file('banner_promotion')){
                $medias = $website->getMedia('website_banner_promotion');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_promotion;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_promotion;
                $banner_promotion = Image::make($getImage->getRealPath());
                $banner_promotion->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_promotion');
            }

            if($request->file('banner_news')){
                $medias = $website->getMedia('website_banner_news');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_news;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_news;
                $banner_news = Image::make($getImage->getRealPath());
                $banner_news->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_news');
            }

            if($request->file('banner_faq')){
                $medias = $website->getMedia('website_banner_faq');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_faq;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_faq;
                $banner_faq = Image::make($getImage->getRealPath());
                $banner_faq->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_faq');
            }

            if($request->file('banner_contact')){
                $medias = $website->getMedia('website_banner_contact');
                if (count($medias) > 0) {
                    foreach ($medias as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->banner_contact;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->banner_contact;
                $banner_contact = Image::make($getImage->getRealPath());
                $banner_contact->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $website->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('website_banner_contact');
            }

            alert::success('บันทึกสำเร็จ');
            return redirect()->route('settings.edit',['setting' => Auth::user()->website_id]);
        }

        alert::error('ผิดพลาด');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
