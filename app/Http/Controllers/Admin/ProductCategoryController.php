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

use App\Models\ProductCategory;
use App\Models\Website;

class ProductCategoryController extends Controller
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
                $data = ProductCategory::all();
            }else{
                $data = ProductCategory::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('website', function($data){
                    $website = $data->website->name;
                    return $website;
                })
                ->addColumn('title', function ($data){
                    $title = json_decode($data->title)->th;
                    return $title;
                })
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('category.edit',['category' => $data['slug']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/category') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/category/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/category/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/category/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){
                    if($data->getFirstMediaUrl('product_category')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('product_category')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting', 'website'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.product.category.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.product.category.create',compact('websites'));
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

        $category = new ProductCategory();
        $category->title = json_encode($title);

        if($request->website){
            $category->website_id = $request->website;
        }else{
            $category->website_id = Auth::user()->website_id;
        }

        $category->created_at = Carbon::now();
        $category->updated_at = Carbon::now();

        if($category->save()){

            if($request->file('img')){
                $getImage = $request->img;

                $path = storage_path('app/public');
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $getImage = $request->img;
                $img = Image::make($getImage->getRealPath());
                $img->resize(300, 300, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());

                $category->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('product_category');
            }
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('category.index');
        }

        return redirect()->route('category.create');
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
    public function edit(ProductCategory $category)
    {
        $websites = Website::all();
        return view('admin.product.category.edit', compact('category','websites'));
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

        $category = ProductCategory::whereId($id)->first();
        $category->title = $title;

        if($request->website){
            $category->website_id = $request->website;
        }else{
            $category->website_id = Auth::user()->website_id;
        }

        $category->updated_at = Carbon::now();

        if($category->save()){
            if($request->file('img')){
                $medies = $category->getMedia('product_category');
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
                $img->resize(300, 300, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path('app/public').'/'.$getImage->getClientOriginalName());
                $category->addMedia(storage_path('app/public').'/'.$getImage->getClientOriginalName())->toMediaCollection('product_category');


            }
            Alert::success('บันทึกข้อมูล');
            return redirect()->route('category.index');
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

        $category = ProductCategory::whereId($id)->first();
        if($category->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $category = ProductCategory::whereId($id)->first();
        if($category->publish == 1) {
            $category->publish = 0;
        }else{
            $category->publish = 1;
        }

        if($category->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $category = ProductCategory::whereId($id)->first();
        $category->sort = $request->data;
        $category->updated_at = Carbon::now();
        if($category->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
