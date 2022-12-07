<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
Use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Permission::all();
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('permission.edit',['permission' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/permission') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->rawColumns(['btn'])
                ->make(true);
        }
        return view('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'description' => $request->description]);
        if($permission){
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect()->route('permission.index');
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
    public function edit(Permission $permission)
    {
        return view('admin.permission.edit',compact('permission'));
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
        $permission = Permission::whereId($id)->first();
        $permission->name = $request->name;
        $permission->description = $request->description;
        $permission->updated_at = Carbon::now();

        if($permission->save()){
            alert::success('บันทึกข้อมูล');
            return redirect()->route('permission.index');
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
        $msg = 'ไม่สามารถลบข้อมูลได้';
        $permission = Permission::whereId($id)->first();
        if ($permission->delete()) {
            $status = true;
            $msg = 'ลบข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'msg' => $msg]);
    }
}
