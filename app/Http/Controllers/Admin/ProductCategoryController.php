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
        if(Auth::user()->hasAnyRole('superadmin','admin')){

            if($request->get('website')){
                $categories = ProductCategory::tree()->where('website_id',$request->website);
                $websites = Website::where('publish',1)->get();

                return view('admin.product.category.index',compact('categories','websites'));
            }else{
                if($request->ajax()){
                    $data = Website::where('publish',1)->get();
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
                        ->addColumn('btn',function ($data){
                            $btn = '<a class="btn btn-sm btn-warning" href="'.route('productcategory.index',['website' => $data['id']]).'"><i
                                class="fa fa-pen"
                                data-toggle="tooltip"
                                title="แก้ไข"></i></a>';
                            return $btn;
                        })
                        ->rawColumns(['btn','publish'])
                        ->make(true);
                }
            }

            $websites = Website::where('publish',1)->get();
            return view('admin.product.category.admin',compact('websites'));
        }else{
            $categories = ProductCategory::tree()->where('website_id',Auth::user()->website_id);
            $websites = Website::where('publish',1)->get();

            return view('admin.product.category.index',compact('categories','websites'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->get('website')){
            $categories = ProductCategory::where('website_id',$request->website)->get();
        }else{
            $categories = ProductCategory::where('website_id',Auth::user()->website_id)->get();
        }

        $websites = Website::all();
        // $categories = ProductCategory::all();
        return view('admin.product.category.create',compact('websites','categories'));
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

        $detail = [
            'th' => $request->detail_th,
            'en' => $request->detail_en
        ];

        $category = new ProductCategory();
        $category->title = json_encode($title,JSON_UNESCAPED_UNICODE);
        $category->detail = json_encode($detail,JSON_UNESCAPED_UNICODE);
        $category->parent_id = $request->parent;

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

            if($request->website) {
                return redirect()->route('productcategory.index',['website' => $request->website]);
            }else{
                return redirect()->route('productcategory.index');
            }
        }

        return redirect()->route('productcategory.create');
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
    public function edit($productecategory)
    {
        $category = ProductCategory::where('slug',$productecategory)->first();
        $websites = Website::all();

        if(Auth::user()->hasAnyRole('superadmin','admin')){
            $categories = ProductCategory::where('website_id',$category->website_id)->where('publish',1)->get();
        }else{
            $categories = ProductCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();
        }

        $cate = [];

        foreach($categories as $item){
            if(!$item->parent_id){
                array_push($cate,$item);
            }
        }

        dd($cate);

        return view('admin.product.category.edit', compact('category','websites','categories'));
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

        $detail = [
            'th' => $request->detail_th,
            'en' => $request->detail_en
        ];

        $category = ProductCategory::whereId($id)->first();
        $category->title = json_encode($title,JSON_UNESCAPED_UNICODE);
        $category->detail = json_encode($detail,JSON_UNESCAPED_UNICODE);
        $category->parent_id = $request->parent;

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
            return redirect()->route('productcategory.index');
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
