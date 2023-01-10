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
// use App\Models\SubProductCategory;
use App\Models\Product;
use App\Models\Website;

class ProductController extends Controller
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
                $data = Product::all();
            }else{
                $data = Product::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('product.edit',['product' => $data['slug']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/product') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('website', function($data){
                    $website = $data->website->name;
                    return $website;
                })
                ->addColumn('title', function($data){
                    $title = json_decode($data->title)->th;
                    return $title;
                })
                ->addColumn('category', function($data){
                    $category = json_decode($data->product_category->title)->th;
                    return $category;
                })
                // ->addColumn('subcategory', function($data){
                //     $subcategory = '';

                //     if($data['sub_product_category_id']){
                //         $subcategory = json_decode($data->sub_product_category->title)->th;
                //     }

                //     return $subcategory;
                // })
                ->addColumn('promotion_status',function ($data){
                    if($data['promotion_status']){
                        $promotion_status = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="promotion(`'. url('admin/product/promotion') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $promotion_status = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="promotion(`'. url('admin/product/promotion') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $promotion_status;
                })
                ->addColumn('bestsell_status',function ($data){
                    if($data['bestsell_status']){
                        $bestsell_status = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="bestsell(`'. url('admin/product/bestsell') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $bestsell_status = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="bestsell(`'. url('admin/product/bestsell') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $bestsell_status;
                })
                ->addColumn('recommended_status',function ($data){
                    if($data['recommended_status']){
                        $recommended_status = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="recommended(`'. url('admin/product/recommended') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $recommended_status = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="recommended(`'. url('admin/product/recommended') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $recommended_status;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/product/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/product/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/product/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->addColumn('img',function ($data){
                    if($data->getFirstMediaUrl('product')){
                        $img = '<img src="'.asset($data->getFirstMediaUrl('product')).'" style="width: auto; height: 40px;">';
                    }else{
                        $img = '<img src="'.asset('images/no-image.jpg').'" style="width: auto; height: 40px;">';
                    }

                    return $img;
                })
                ->rawColumns(['btn','img','publish','sorting','subcategory','category','promotion_status','bestsell_status','recommended_status'])
                ->make(true);
        }

        $websites = Website::where('publish',1)->get();
        return view('admin.product.product.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->hasAnyRole('superadmin','admin')){
            $categories = ProductCategory::where('publish',1)->get();
            // $subcategories = SubProductCategory::where('publish',1)->get();
        }else{
            $categories = ProductCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();
            // $subcategories = SubProductCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();
        }

        $websites = Website::all();

        return view('admin.product.product.create',compact('categories','websites'));
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

        $product = new Product();
        $product->product_category_id = $request->category;
        // $product->sub_product_category_id = $request->subcategory;
        $product->title = json_encode($title,JSON_UNESCAPED_UNICODE);
        $product->short_detail = json_encode($short_detail,JSON_UNESCAPED_UNICODE);
        $product->detail = json_encode($detail,JSON_UNESCAPED_UNICODE);
        $product->price = $request->price;
        $product->price_promotion = $request->price_promotion;

        $product->seo_keyword = $request->seo_keyword;
        $product->seo_description = $request->seo_description;

        if($request->website){
            $product->website_id = $request->website;
        }else{
            $product->website_id = Auth::user()->website_id;
        }

        if($product->save()){
            $i = 0;
            $medies_original_name = $request->input('image', []);
            foreach ($request->input('image', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/' . $file))
                    ->withCustomProperties(['order' => $i])
                    ->setName($medies_original_name[$i])
                    ->toMediaCollection('product');
                $i++;
            }

            alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route("product.index");
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
    public function edit(Product $product)
    {
        if(Auth::user()->hasAnyRole('superadmin','admin')){
            $categories = ProductCategory::where('website_id',$product->website_id)->where('publish',1)->get();
            // $subcategories = SubProductCategory::where('product_category_id',$product->product_category_id)->where('website_id',$product->website_id)->where('publish',1)->get();
        }else{
            $categories = ProductCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();
            // $subcategories = SubProductCategory::where('product_category_id',$product->product_category_id)->where('website_id',Auth::user()->website_id)->where('publish',1)->get();
        }

        $medias = $product->getMedia('product');
        $images = $medias->sortBy(function ($med, $key) {
            return $med->getCustomProperty('order');
        });

        $websites = Website::all();
        return view('admin.product.product.edit',compact('product','categories','images','websites'));
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

        $product = Product::whereId($id)->first();
        $product->product_category_id = $request->category;
        // $product->sub_product_category_id = $request->subcategory;
        $product->title = json_encode($title,JSON_UNESCAPED_UNICODE);
        $product->detail = json_encode($detail,JSON_UNESCAPED_UNICODE);
        $product->short_detail = json_encode($short_detail,JSON_UNESCAPED_UNICODE);
        $product->price = $request->price;
        $product->price_promotion = $request->price_promotion;
        $product->seo_keyword = $request->seo_keyword;
        $product->seo_description = $request->seo_description;

        if($request->website){
            $product->website_id = $request->website;
        }else{
            $product->website_id = Auth::user()->website_id;
        }

        if($product->save()){
            $medies = $product->getMedia('product');
            if (count($medies) > 0) {
                foreach ($medies as $media) {
                    if (!in_array($media->file_name, $request->input('image', []))) {
                        $media->delete();
                    }
                }
            }

            $i = 1;
            $medies = $product->getMedia('product')->pluck('file_name')->toArray();
            $medies_original_name = $request->input('image', []);

            foreach ($request->input('image', []) as $file) {
                if (count($medies) === 0 || !in_array($file, $medies)) {
                    $product->addMedia(storage_path('tmp/uploads/' . $file))
                        ->withCustomProperties(['order' => $i])
                        ->setName($medies_original_name[$i - 1])
                        ->toMediaCollection('product');
                } else {
                    $image = $product->getMedia('product')->where('file_name', $file)->first();
                    $image->setCustomProperty('order', $i);
                    $image->save();
                }
                $i++;
            }

            alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route("product.index");
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

        $product = Product::whereId($id)->first();
        if($product->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function getCategory($id){
        $categories = ProductCategory::where('website_id',$id)->get();
        return response()->json(['categories' => $categories]);
    }

    // public function getSubcategory($id){
    //     $subcategories = SubProductCategory::where('product_category_id',$id)->get();

    //     return response()->json(['subcategories' => $subcategories]);
    // }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $product = Product::whereId($id)->first();
        if($product->publish == 1) {
            $product->publish = 0;
        }else{
            $product->publish = 1;
        }

        if($product->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $product = Product::whereId($id)->first();
        $product->sort = $request->data;
        $product->updated_at = Carbon::now();
        if($product->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function promotion($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $product = Product::whereId($id)->first();
        if($product->promotion_status == 1) {
            $product->promotion_status = 0;
        }else{
            $product->promotion_status = 1;
        }

        if($product->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function bestsell($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $product = Product::whereId($id)->first();
        if($product->bestsell_status == 1) {
            $product->bestsell_status = 0;
        }else{
            $product->bestsell_status = 1;
        }

        if($product->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function recommended($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $product = Product::whereId($id)->first();

        if($product->recommended_status == 1) {
            $product->recommended_status = 0;
        }else{
            $product->recommended_status = 1;
        }

        if($product->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }
}
