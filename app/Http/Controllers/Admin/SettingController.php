<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.index');
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
        setting(['phone' => $request->phone])->save();
        setting(['email' => $request->email])->save();
        setting(['address' => $request->address])->save();
        setting(['aboutus' => $request->aboutus])->save();
        setting(['aboutus_short' => $request->aboutus_short])->save();

        setting(['seo_keyword' => $request->seo_keyword])->save();
        setting(['seo_description' => $request->seo_description])->save();

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

        if ($request->file('img_login')){
            if(!empty(setting('img_login'))){
                File::delete(public_path(setting('img_login')));
            }
            //resize image
            $path = storage_path('tmp/uploads');
            $imgwidth = 500;
            $imgheight = 500;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img_login');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $full_path = public_path('uploads/setting/'.$name);
            $img = \Image::make($file->getRealPath());
            $img->resize($imgwidth, $imgheight, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path);
            setting(['img_login' => 'uploads/setting/'.$name])->save();
        }

        if ($request->file('img_sidebar')){
            if(!empty(setting('img_sidebar'))){
                File::delete(public_path(setting('img_sidebar')));
            }
            //resize image
            $path = storage_path('tmp/uploads');
            $imgwidth = 150;
            $imgheight = 150;

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->file('img_sidebar');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $full_path = public_path('uploads/setting/'.$name);
            $img = \Image::make($file->getRealPath());
            $img->resize($imgwidth, $imgheight, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($full_path);
            setting(['img_sidebar' => 'uploads/setting/'.$name])->save();
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
        //
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
        //
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
