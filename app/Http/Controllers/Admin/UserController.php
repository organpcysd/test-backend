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

use App\Models\User;
use App\Models\Website;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
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
                $data = User::all();
            }else{
                $data = User::whereHas('roles', function($q){$q->whereIn('name',['admin','user']);});
            }
            return DataTables::make($data)
                ->addIndexColumn()
                ->addColumn('website',function ($data){
                    if($data['website_id']){
                        $website = $data->website->name;
                    }else{
                        $website = '';
                    }
                    return $website;
                })
                ->addColumn('btn',function ($data){
                    if(Auth::user()->id == $data['id']) {
                        $btn = '<a class="btn btn-sm btn-warning" href="'.route('user.edit',['user' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" disabled><i class="fa fa-trash" data-toggle="tooltip" title="ลบข้อมูล"></i></button>';
                    }else{
                        $btn = '<a class="btn btn-sm btn-warning" href="'.route('user.edit',['user' => $data['id']]).'"><i
                            class="fa fa-pen"
                            data-toggle="tooltip"
                            title="แก้ไข"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="confirmdelete(`'. url('admin/user') . '/' . $data['id'].'`)"><i
                            class="fa fa-trash"
                            data-toggle="tooltip"
                            title="ลบข้อมูล"></i></button>';
                    }

                    return $btn;
                })
                ->addColumn('status',function ($data){
                    if(Auth::user()->id == $data['id']){
                        $status = '';
                    }else{
                        if($data['status']){
                            $status = '<label class="switch"> <input type="checkbox" checked value="0" id="' . $data['id'] . '" onchange="publish(`'. url('admin/user/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                        }else{
                            $status = '<label class="switch"> <input type="checkbox" value="1" id="'.$data['id'].'" onchange="publish(`'. url('admin/user/publish') . '/' . $data['id'].'`)"> <span class="slider round"></span> </label>';
                        }
                    }

                    return $status;
                })
                ->rawColumns(['btn','status','sorting','website'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $websites = Website::all();

        return view('admin.user.create',compact('roles','permissions','websites'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'username' => 'unique:users'
        ],[
            'email.unique' => 'มีผู้ใช้งานอีเมลนี้แล้ว',
            'username.unique' => 'มีชื่อผู้ใช้งานนี้แล้ว'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 1;
        $user->website_id = $request->website;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();

        if($user->save()){
            $user->assignRole($request->role);

            if($request->permission){
                $user->givePermissionTo([$request->permission]);
            }

            Alert::success('บันทึกข้อมูล');
            return view('admin.user.index');
        }

        Alert::error('ผิดพลาด');
        return view('admin.user.create');
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
        $user = User::whereId($id)->first();
        $roles = Role::all();
        $permissions = Permission::all();
        $websites = Website::all();

        return view('admin.user.edit',compact('user','roles','permissions','websites'));
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
        $request->validate([
            'email' => 'unique:users,email,'.$id,
            'username' => 'unique:users,username,'.$id
        ],[
            'email.unique' => 'มีผู้ใช้งานอีเมลนี้แล้ว',
            'username.unique' => 'มีชื่อผู้ใช้งานนี้แล้ว'
        ]);

        $user = User::whereId($id)->first();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if($request->password != null){
            $user->password = bcrypt($request->password);
        }
        $user->status = 1;
        $user->website_id = $request->website;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();

        if($user->save()){
            $user->syncRoles($request->role);
            $user->syncPermissions([$request->permission]);

            Alert::success('บันทึกข้อมูล');
            return redirect()->route('user.index');
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

        $user = User::whereId($id)->first();
        if($user->delete()){
            $status = true;
            $msg = 'เสร็จสิ้น';
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function publish($id)
    {
        $status = false;
        $msg = 'บันทึกข้อมูลผิดพลาด';

        $user = User::whereId($id)->first();
        if($user->status == 1) {
            $user->status = 0;
        }else{
            $user->status = 1;
        }

        if($user->save()){
            $status = true;
            $msg = 'บันทึกข้อมูลเรียบร้อย';
        }
        return response()->json(['msg' => $msg, 'status' => $status]);
    }

    public function getDataEmployeeById($emp_id)
    {

    }

    public function getDataTimeAttendanceTodayByEmpId($emp_id)
    {

    }
}
