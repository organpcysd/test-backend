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

use App\Models\Promotion;
use App\Models\Website;

class PromotionController extends Controller
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
                $data = Promotion::all();
            }else{
                $data = Promotion::where('website_id',Auth::user()->website_id)->get();
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
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('promotion.edit',['promotion' => $data['slug']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/promotion') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/promotion/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/promotion/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/promotion/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){
                    if($data->getFirstMediaUrl('promotion')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('promotion')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting','title','website'])
                ->make(true);
        }

        $websites = Website::where('publish',1)->get();
        return view('admin.promotion.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.promotion.create',compact('websites'));
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

        $promotion = new Promotion();
        $promotion->title = json_encode($title);

        if($request->website){
            $promotion->website_id = $request->website;
        }else{
            $promotion->website_id = Auth::user()->website_id;
        }

        $promotion->short_detail = json_encode($short_detail);
        $promotion->detail = json_encode($detail);
        $promotion->seo_keyword = $request->post('seo_keyword');
        $promotion->seo_description = $request->post('seo_description');
        $promotion->created_at = Carbon::now();
        $promotion->updated_at = Carbon::now();

        if($promotion->save()){
            $i = 0;
            $medias_original_name = $request->input('image', []);
            foreach ($request->input('image', []) as $file) {
                $promotion->addMedia(storage_path('tmp/uploads/' . $file))
                    ->withCustomProperties(['order' => $i])
                    ->setName($medias_original_name[$i])
                    ->toMediaCollection('promotion');
                $i++;
            }

            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('promotion.index');
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
    public function edit(Promotion $promotion)
    {
        $websites = Website::all();

        $medias = $promotion->getMedia('promotion');
        $images = $medias->sortBy(function ($med, $key) {
            return $med->getCustomProperty('order');
        });

        return view('admin.promotion.edit',compact('promotion','images','websites'));
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

        $promotion = Promotion::whereId($id)->first();
        $promotion->title = json_encode($title);

        if($request->website){
            $promotion->website_id = $request->website;
        }else{
            $promotion->website_id = Auth::user()->website_id;
        }

        $promotion->short_detail = json_encode($short_detail);
        $promotion->detail = json_encode($detail);
        $promotion->seo_keyword = $request->post('seo_keyword');
        $promotion->seo_description = $request->post('seo_description');
        $promotion->updated_at = Carbon::now();

        if($promotion->save()){
            $medias = $promotion->getMedia('promotion');
            if (count($medias) > 0) {
                foreach ($medias as $media) {
                    if (!in_array($media->file_name, $request->input('image', []))) {
                        $media->delete();
                    }
                }
            }

            $i = 1;
            $medias = $promotion->getMedia('promotion')->pluck('file_name')->toArray();
            $medias_original_name = $request->input('image', []);

            foreach ($request->input('image', []) as $file) {
                if (count($medias) === 0 || !in_array($file, $medias)) {
                    $promotion->addMedia(storage_path('tmp/uploads/' . $file))
                        ->withCustomProperties(['order' => $i])
                        ->setName($medias_original_name[$i - 1])
                        ->toMediaCollection('promotion');
                } else {
                    $image = $promotion->getMedia('promotion')->where('file_name', $file)->first();
                    $image->setCustomProperty('order', $i);
                    $image->save();
                }
                $i++;
            }

            Alert::success('บันทึกข้อมูล');
            return redirect()->route('promotion.index');
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

        $promotion = Promotion::whereId($id)->first();
        if($promotion->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $promotion = Promotion::whereId($id)->first();
        if($promotion->publish == 1) {
            $promotion->publish = 0;
        }else{
            $promotion->publish = 1;
        }

        if($promotion->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $promotion = Promotion::whereId($id)->first();
        $promotion->sort = $request->data;
        $promotion->updated_at = Carbon::now();
        if($promotion->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
