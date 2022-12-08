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
use App\Models\Faq;
use App\Models\Website;
use Illuminate\Support\Facades\Redirect;

class FaqController extends Controller
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
                $data = Faq::all();
            }else{
                $data = Faq::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('website', function($data){
                    if(Auth::user()->hasAnyRole('superadmin','admin')){
                        $website = $data->website->name;
                    }else{
                        $website = '';
                    }
                    return $website;
                })
                ->addColumn('category', function ($data){
                    $category = json_decode($data->faq_category->title)->th;
                    return $category;
                })
                ->addColumn('question', function ($data){
                    $question = json_decode($data->question)->th;
                    return $question;
                })
                ->addColumn('answer', function ($data){
                    $answer = json_decode($data->answer)->th;
                    return $answer;
                })
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('faq.edit',['faq' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/faq') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/faq/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/faq/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/faq/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->rawColumns(['btn','publish','sorting', 'website','category','question','answer'])
                ->make(true);
        }

        $websites = Website::all();
        return view('admin.faq.faq.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        $categories = FaqCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();

        return view('admin.faq.faq.create',compact('websites','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = [
            'th' => $request->question_th,
            'en' => $request->question_en
        ];

        $answer = [
            'th' => $request->answer_th,
            'en' => $request->answer_en
        ];

        $faq = new Faq();
        $faq->faq_category_id = $request->faqcategory;
        $faq->question = json_encode($question);
        $faq->answer = json_encode($answer);

        if($request->website){
            $faq->website_id = $request->website;
        }else{
            $faq->website_id = Auth::user()->website_id;
        }

        if($faq->save()){
            alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('faq.index');
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
    public function edit(Faq $faq)
    {
        $websites = Website::all();

        if(Auth::user()->hasAnyRole('superadmin','admin')){
            $categories = FaqCategory::where('website_id',$faq->website_id)->where('publish',1)->get();
        }else{
            $categories = FaqCategory::where('website_id',Auth::user()->website_id)->where('publish',1)->get();
        }

        return view('admin.faq.faq.edit',compact('faq','websites','categories'));
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
        $question = [
            'th' => $request->question_th,
            'en' => $request->question_en
        ];

        $answer = [
            'th' => $request->answer_th,
            'en' => $request->answer_en
        ];

        $faq = Faq::whereId($id)->first();
        $faq->faq_category_id = $request->faqcategory;
        $faq->question = json_encode($question);
        $faq->answer = json_encode($answer);

        if($request->website){
            $faq->website_id = $request->website;
        }else{
            $faq->website_id = Auth::user()->website_id;
        }

        if($faq->save()){
            alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route('faq.index');
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

        $faq = Faq::whereId($id)->first();
        if($faq->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $faq = Faq::whereId($id)->first();
        if($faq->publish == 1) {
            $faq->publish = 0;
        }else{
            $faq->publish = 1;
        }

        if($faq->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $faq = Faq::whereId($id)->first();
        $faq->sort = $request->data;
        $faq->updated_at = Carbon::now();
        if($faq->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function getfaqcategory($id)
    {
        $faqcategories = FaqCategory::where('website_id',$id)->get();

        return response()->json(['faqcategories' => $faqcategories]);
    }
}
