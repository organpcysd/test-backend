<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

use App\Models\Website;
use Spatie\Permission\Models\Role;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Website::all();
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/website/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/website/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('website_code',function ($data){
                    $website_code = '<label class="text-info font-weight-normal" onclick="copy(this)">' . $data['website_code'] . '<i class="fas fa-paste ml-2"></i></label>';
                    return $website_code;
                })
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('website.edit',['website' => $data['id']]).'"><i
                        class="fa fa-pen"
                        data-toggle="tooltip"
                        title="แก้ไข"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/website') . '/' . $data['id'].'`)"><i
                        class="fa fa-trash"
                        data-toggle="tooltip"
                        title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->rawColumns(['btn','publish','website_code'])
                ->make(true);
        }
        return view('admin.website.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.website.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //pattern

        //WB20230101
        $config = [
            'table' => 'websites',
            'field' => 'website_code',
            'length' => 10,
            'prefix' => 'WB' . date('Y') . date('m')
        ];
        // now use it

        $website_code = IdGenerator::generate($config);

        $website = new Website();
        $website->name = $request->name;
        $website->website_code = $website_code;
        $website->domain_name = $request->domain_name;
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

            alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('website.index');
        }

        alert::error('ผิดพลาด');
        return redirect()->back();
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
        $website = Website::whereId($id)->first();
        return view('admin.website.edit', compact('website'));
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
        $website->name = $request->name;
        $website->domain_name = $request->domain_name;
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
            return redirect()->route('website.index');
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
        $status = false;
        $msg = 'ผิดพลาด';

        $website = Website::whereId($id)->first();
        if($website->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $website = Website::whereId($id)->first();
        if($website->publish == 1) {
            $website->publish = 0;
        }else{
            $website->publish = 1;
        }

        if($website->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }
}
