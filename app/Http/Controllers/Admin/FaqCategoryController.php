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

use App\Models\FaqCategory;
use App\Models\Website;

class FaqCategoryController extends Controller
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
                $data = FaqCategory::all();
            }else{
                $data = FaqCategory::where('website_id',Auth::user()->website_id)->get();
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
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('faqcategory.edit',['faqcategory' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/faqcategory') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/faqcategory/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/faqcategory/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/faqcategory/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->rawColumns(['btn','publish','sorting', 'website'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.faq.category.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.faq.category.create',compact('websites'));
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

        $faqcategory = new FaqCategory();
        $faqcategory->title = json_encode($title);

        if($request->website){
            $faqcategory->website_id = $request->website;
        }else{
            $faqcategory->website_id = Auth::user()->website_id;
        }

        $faqcategory->created_at = Carbon::now();
        $faqcategory->updated_at = Carbon::now();

        if($faqcategory->save()){
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('faqcategory.index');
        }

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
    public function edit(FaqCategory $faqcategory)
    {
        $websites = Website::all();
        return view('admin.faq.category.edit', compact('faqcategory','websites'));
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

        $faqcategory = FaqCategory::whereId($id)->first();
        $faqcategory->title = json_encode($title);

        if($request->website){
            $faqcategory->website_id = $request->website;
        }else{
            $faqcategory->website_id = Auth::user()->website_id;
        }

        $faqcategory->created_at = Carbon::now();
        $faqcategory->updated_at = Carbon::now();

        if($faqcategory->save()){
            Alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route('faqcategory.index');
        }

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

        $faqcategory = FaqCategory::whereId($id)->first();
        if($faqcategory->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $faqcategory = FaqCategory::whereId($id)->first();
        if($faqcategory->publish == 1) {
            $faqcategory->publish = 0;
        }else{
            $faqcategory->publish = 1;
        }

        if($faqcategory->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $faqcategory = FaqCategory::whereId($id)->first();
        $faqcategory->sort = $request->data;
        $faqcategory->updated_at = Carbon::now();
        if($faqcategory->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
