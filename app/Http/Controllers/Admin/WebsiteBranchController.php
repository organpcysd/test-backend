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

use App\Models\WebsiteBranch;
use App\Models\Website;

class WebsiteBranchController extends Controller
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
                $data = WebsiteBranch::all();
            }else{
                $data = WebsiteBranch::where('website_id',Auth::user()->website_id)->get();
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('name',function ($data){
                    $name = json_decode($data->name)->th;
                    return $name;
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
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('branch.edit',['branch' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/branch') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->addColumn('publish',function ($data){
                    if($data['publish']){
                        $publish = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/branch/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }else{
                        $publish = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/branch/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                    }
                    return $publish;
                })
                ->addColumn('sorting',function ($data){
                    $sorting = '<input name="sort" type="number" class="form-control form-control-sm" value="'.$data['sort'].'" id="'.$data['id'].'" onkeyup="sort(this,`'. url('admin/branch/sort') . '/' . $data['id'].'`)"/>';
                    return $sorting;
                })
                ->rawColumns(['btn','publish','sorting','name','website'])
                ->make(true);
        }

        $websites = Website::where('publish',1)->get();
        return view('admin.website_branch.index',compact('websites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $websites = Website::all();
        return view('admin.website_branch.create',compact('websites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = [
            'th' => $request->name_th,
            'en' => $request->name_en
        ];

        $address = [
            'th' => $request->address_th,
            'en' => $request->address_en
        ];

        $branch = new WebsiteBranch();
        $branch->name = json_encode($name);

        if($request->website){
            $branch->website_id = $request->website;
        }else{
            $branch->website_id = Auth::user()->website_id;
        }

        $branch->address = json_encode($address);
        $branch->phone = $request->phone;
        $branch->google_map = $request->google_map;
        $branch->facebook = $request->facebook;
        $branch->messenger = $request->messenger;
        $branch->line = $request->line;
        $branch->line_token = $request->line_token;
        $branch->email = $request->email;

        $branch->created_at = Carbon::now();
        $branch->updated_at = Carbon::now();

        if($branch->save()){
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('branch.index');
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
    public function edit(WebsiteBranch $branch)
    {
        $websites = Website::all();
        return view('admin.website_branch.edit',compact('branch','websites'));
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
        $name = [
            'th' => $request->name_th,
            'en' => $request->name_en
        ];

        $address = [
            'th' => $request->address_th,
            'en' => $request->address_en
        ];

        $branch = WebsiteBranch::whereId($id)->first();
        $branch->name = json_encode($name);

        if($request->website){
            $branch->website_id = $request->website;
        }else{
            $branch->website_id = Auth::user()->website_id;
        }

        $branch->address = json_encode($address);
        $branch->phone = $request->phone;
        $branch->google_map = $request->google_map;
        $branch->facebook = $request->facebook;
        $branch->messenger = $request->messenger;
        $branch->line = $request->line;
        $branch->line_token = $request->line_token;
        $branch->email = $request->email;

        $branch->updated_at = Carbon::now();

        if($branch->save()){
            Alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route('branch.index');
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

        $branch = WebsiteBranch::whereId($id)->first();
        if($branch->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $branch = WebsiteBranch::whereId($id)->first();
        if($branch->publish == 1) {
            $branch->publish = 0;
        }else{
            $branch->publish = 1;
        }

        if($branch->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function sort($id, Request $request){
        $status = false;
        $message = 'ไม่สามารถบันทึกข้อมูลได้';

        $branch = WebsiteBranch::whereId($id)->first();
        $branch->sort = $request->data;
        $branch->updated_at = Carbon::now();
        if($branch->save()){
            $status = true;
            $message = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'message' => $message]);
    }
}
