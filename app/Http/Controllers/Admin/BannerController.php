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

use App\Models\Banner;
use App\Models\Website;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            if(Auth::user()->hasRole('superadmin')){
                $data = Banner::all();
            }else{
                $data = Banner::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('website',function ($data){
                    if($data['website_id']){
                        $website = $data->website->name;
                    }else{
                        $website = '';
                    }
                    return $website;
                })
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('banner.edit',['banner' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/banner') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/banner/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/banner/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/banner/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){
                    if($data->getFirstMediaUrl('banner')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('banner')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting','website'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.banner.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();

        return view('admin.banner.create',compact('websites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $banner->title = $request->post('title');

        if($request->website){
            $banner->website_id = $request->website;
        }else{
            $banner->website_id = Auth::user()->website_id;
        }

        $banner->created_at = Carbon::now();
        $banner->updated_at = Carbon::now();

        if($banner->save()){
            if($request->file('img')){
                $getImage = $request->img;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->img;
                $img = Image::make($getImage->getRealPath());

                $img->resize(1920, 1080, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $banner->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('banner');
            }
            if($request->file('img2')){
                $getImage = $request->img2;
                $path = storage_path('app\public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $img = Image::make($getImage->getRealPath());
                $img->resize(500, 700, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());
                $banner->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('banner_mobile');
            }
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('banner.index');
        }
        Alert::error('ผิดพลาด');
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
        $banner = Banner::whereId($id)->first();
        $websites = Website::all();

        return view('admin.banner.edit', compact('banner','websites'));
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
        $banner = Banner::whereId($id)->first();
        $banner->title = $request->post('title');

        if($request->website){
            $banner->website_id = $request->website;
        }else{
            $banner->website_id = Auth::user()->website_id;
        }

        $banner->updated_at = Carbon::now();
        if($banner->save()){
            if($request->file('img')){
                $medies = $banner->getMedia('banner');
                if (count($medies) > 0) {
                    foreach ($medies as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->img;
                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $img = Image::make($getImage->getRealPath());
                $img->resize(1920, 1080, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());
                $banner->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('banner');
            }

            if($request->file('img2')){
                $medies = $banner->getMedia('banner_mobile');
                if (count($medies) > 0) {
                    foreach ($medies as $media) {
                        $media->delete();
                    }
                }

                $getImage = $request->img2;
                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $img = Image::make($getImage->getRealPath());
                $img->resize(500, 700, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());
                $banner->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('banner_mobile');
            }
            Alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route('banner.index');
        }
        Alert::error('ผิดพลาด');
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

        $banner = Banner::whereId($id)->first();
        if($banner->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $banner = Banner::whereId($id)->first();
        if($banner->publish == 1) {
            $banner->publish = 0;
        }else{
            $banner->publish = 1;
        }

        if($banner->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $banner = Banner::whereId($id)->first();
        $banner->sort = $request->data;
        $banner->updated_at = Carbon::now();
        if($banner->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
