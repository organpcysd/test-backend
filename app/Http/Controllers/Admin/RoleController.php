<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Role::all();
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('btn',function ($data){
                    $btn = '<a class="btn btn-sm btn-warning" href="'.route('role.edit',['role' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/role') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    return $btn;
                })
                ->rawColumns(['btn'])
                ->make(true);
        }
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.role.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = new Role();
        $roles->name = $request->name;
        $roles->guard_name = 'web';
        $roles->created_at = Carbon::now();
        $roles->updated_at = Carbon::now();
        if($roles->save()){
            $roles->syncPermissions([$request->permiss]);
            Alert::success('success');
            return view('admin.role.index');
        }
        return view('admin.role.create');
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
    public function edit($id)
    {
        $roles = Role::find($id);
        $permiss = Permission::all();
        return view('admin.role.edit',compact('permiss','roles'));
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
        $roles = Role::whereId($id)->first();
        $roles->name = $request->name;
        $roles->updated_at = Carbon::now();

        if($roles->save()){
            $roles->syncPermissions([$request->permiss]);
            Alert::success('บันทึกข้อมูลสำเร็จ');
            return redirect()->route('role.index');
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
        $msg = 'ไม่สามารถลบข้อมูลได้';

        $role = Role::whereId($id)->first();
        $role->revokePermissionTo($role->getPermissionNames()->toarray());

        if ($role->delete()) {
            $status = true;
            $msg = 'ลบข้อมูลเรียบร้อย';
        }
        return response()->json(['status' => $status, 'msg' => $msg]);
    }
}
