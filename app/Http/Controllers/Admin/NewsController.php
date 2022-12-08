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

use App\Models\News;
use App\Models\Website;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            if(Auth::user()->hasAnyRole('superadmin','admin')){
                $data = News::all();
            }else{
                $data = News::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('title',function ($data){
                    $title = json_decode($data->title)->th;
                    return $title;
                })
                ->addColumn('website',function ($data){
                    if($data['website_id']){
                        $website = $data->website->name;
                    }else{
                        $website = '';
                    }
                    return $website;
                })
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('news.edit',['news' => $data['slug']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/news') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/news/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/news/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/news/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){
                    if($data->getFirstMediaUrl('news')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('news')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting','title','website'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.news.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.news.create',compact('websites'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = [
            'th' => $request->title_th,
            'en' => $request->title_en
        ];

        $short_detail = [
            'th' => $request->short_detail_th,
            'en' => $request->short_detail_en
        ];

        $detail = [
            'th' => $request->detail_th,
            'en' => $request->detail_en
        ];

        $news = new News();
        $news->title = json_encode($title);

        if($request->website){
            $news->website_id = $request->website;
        }else{
            $news->website_id = Auth::user()->website_id;
        }

        $news->short_detail = json_encode($short_detail);
        $news->detail = json_encode($detail);
        $news->seo_keyword = $request->post('seo_keyword');
        $news->seo_description = $request->post('seo_description');
        $news->created_at = Carbon::now();
        $news->updated_at = Carbon::now();

        if($news->save()){
            $i = 0;
            $medias_original_name = $request->input('image', []);
            foreach ($request->input('image', []) as $file) {
                $news->addMedia(storage_path('tmp/uploads/' . $file))
                    ->withCustomProperties(['order' => $i])
                    ->setName($medias_original_name[$i])
                    ->toMediaCollection('news');
                $i++;
            }

            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('news.index');
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
    public function edit(News $news)
    {
        $websites = Website::all();

        $medias = $news->getMedia('news');
        $images = $medias->sortBy(function ($med, $key) {
            return $med->getCustomProperty('order');
        });

        return view('admin.news.edit',compact('news','images','websites'));
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
        $title = [
            'th' => $request->title_th,
            'en' => $request->title_en
        ];

        $short_detail = [
            'th' => $request->short_detail_th,
            'en' => $request->short_detail_en
        ];

        $detail = [
            'th' => $request->detail_th,
            'en' => $request->detail_en
        ];

        $news = News::whereId($id)->first();
        $news->title = $title;

        if($request->website){
            $news->website_id = $request->website;
        }else{
            $news->website_id = Auth::user()->website_id;
        }

        $news->short_detail = $short_detail;
        $news->detail = $detail;
        $news->seo_keyword = $request->post('seo_keyword');
        $news->seo_description = $request->post('seo_description');
        $news->updated_at = Carbon::now();

        if($news->save()){
            $medias = $news->getMedia('news');
            if (count($medias) > 0) {
                foreach ($medias as $media) {
                    if (!in_array($media->file_name, $request->input('image', []))) {
                        $media->delete();
                    }
                }
            }

            $i = 1;
            $medias = $news->getMedia('news')->pluck('file_name')->toArray();
            $medias_original_name = $request->input('image', []);

            foreach ($request->input('image', []) as $file) {
                if (count($medias) === 0 || !in_array($file, $medias)) {
                    $news->addMedia(storage_path('tmp/uploads/' . $file))
                        ->withCustomProperties(['order' => $i])
                        ->setName($medias_original_name[$i - 1])
                        ->toMediaCollection('news');
                } else {
                    $image = $news->getMedia('news')->where('file_name', $file)->first();
                    $image->setCustomProperty('order', $i);
                    $image->save();
                }
                $i++;
            }

            Alert::success('บันทึกข้อมูล');
            return redirect()->route('news.index');
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

        $news = News::whereId($id)->first();
        if($news->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $news = News::whereId($id)->first();
        if($news->publish == 1) {
            $news->publish = 0;
        }else{
            $news->publish = 1;
        }

        if($news->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $news = News::whereId($id)->first();
        $news->sort = $request->data;
        $news->updated_at = Carbon::now();
        if($news->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
