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

use App\Models\Service;
use App\Models\Website;

class ServiceController extends Controller
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
                $data = Service::all();
            }else{
                $data = Service::where('website_id',Auth::user()->website_id)->get();
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
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('service.edit',['service' => $data['slug']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/service') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/service/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/service/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/service/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){

                    if($data->getFirstMediaUrl('service')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('service')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting','title','website'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.service.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.service.create',compact('websites'));
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

        $service = new Service();
        $service->title = json_encode($title);

        if($request->website){
            $service->website_id = $request->website;
        }else{
            $service->website_id = Auth::user()->website_id;
        }

        $service->short_detail = json_encode($short_detail);
        $service->detail = json_encode($detail);
        $service->seo_keyword = $request->post('seo_keyword');
        $service->seo_description = $request->post('seo_description');
        $service->created_at = Carbon::now();
        $service->updated_at = Carbon::now();

        if($service->save()){
            $i = 0;
            $medias_original_name = $request->input('image', []);
            foreach ($request->input('image', []) as $file) {
                $service->addMedia(storage_path('tmp/uploads/' . $file))
                    ->withCustomProperties(['order' => $i])
                    ->setName($medias_original_name[$i])
                    ->toMediaCollection('service');
                $i++;
            }

            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('service.index');
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
    public function edit(Service $service)
    {
        $websites = Website::all();

        $medias = $service->getMedia('service');
        $images = $medias->sortBy(function ($med, $key) {
            return $med->getCustomProperty('order');
        });

        return view('admin.service.edit',compact('service','images','websites'));
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

        $service = Service::whereId($id)->first();
        $service->title = $title;

        if($request->website){
            $service->website_id = $request->website;
        }else{
            $service->website_id = Auth::user()->website_id;
        }

        $service->short_detail = $short_detail;
        $service->detail = $detail;
        $service->seo_keyword = $request->post('seo_keyword');
        $service->seo_description = $request->post('seo_description');
        $service->updated_at = Carbon::now();

        if($service->save()){
            $medias = $service->getMedia('service');
            if (count($medias) > 0) {
                foreach ($medias as $media) {
                    if (!in_array($media->file_name, $request->input('image', []))) {
                        $media->delete();
                    }
                }
            }

            $i = 1;
            $medias = $service->getMedia('service')->pluck('file_name')->toArray();
            $medias_original_name = $request->input('image', []);

            foreach ($request->input('image', []) as $file) {
                if (count($medias) === 0 || !in_array($file, $medias)) {
                    $service->addMedia(storage_path('tmp/uploads/' . $file))
                        ->withCustomProperties(['order' => $i])
                        ->setName($medias_original_name[$i - 1])
                        ->toMediaCollection('service');
                } else {
                    $image = $service->getMedia('service')->where('file_name', $file)->first();
                    $image->setCustomProperty('order', $i);
                    $image->save();
                }
                $i++;
            }

            Alert::success('บันทึกข้อมูล');
            return redirect()->route('service.index');
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

        $service = Service::whereId($id)->first();
        if($service->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $service = Service::whereId($id)->first();
        if($service->publish == 1) {
            $service->publish = 0;
        }else{
            $service->publish = 1;
        }

        if($service->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $service = Service::whereId($id)->first();
        $service->sort = $request->data;
        $service->updated_at = Carbon::now();
        if($service->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
