@extends('adminlte::page')
@php $pagename = 'เพิ่มผู้ใช้งาน'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการแบนเนอร์</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('user.store') }}" enctype="multipart/form-data" onSubmit="return checkpass()">
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 25px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control form-control-sm" name="name" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เว็บไซต์</label>
                                    <select name="website" class="form-control form-control-sm">
                                        <option value="">--- เลือกเว็บไซต์ ---</option>
                                        @foreach($websites as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">สิทธิ์การใช้งาน</label>
                                    <select name="role" class="form-control form-control-sm">
                                        @foreach($roles as $item)
                                            @if(Auth::user()->hasRole('superadmin'))
                                                <option value="{{$item->name}}" @if($item->name == 'user') selected @endif>{{$item->name}}</option>
                                            @else
                                                @if($item->name == 'superadmin')

                                                @else
                                                    <option value="{{$item->name}}" @if($item->name == 'user') selected @endif>{{$item->name}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">อีเมล</label>
                                    @error('email')
                                        <span class="text-danger">** {{$message}}</span>
                                    @enderror
                                    <input type="email" class="form-control form-control-sm" name="email" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อผู้ใช้</label>
                                    @error('username')
                                        <span class="text-danger">** {{$message}}</span>
                                    @enderror
                                    <input type="text" class="form-control form-control-sm" name="username" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่าน</label>
                                    <input type="password" class="form-control form-control-sm" name="password" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">ยืนยันรหัสผ่าน</label>
                                    <input type="password" class="form-control form-control-sm" name="confirmpass">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">สิทธิ์เพิ่มเติม</label>
                            <select name="permission[]" class="form-control form-control-sm" multiple style="width: 100%; height: 200px;">
                                @foreach($permissions as $item)
                                    <option value="{{$item->name}}">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="float-right">
                    <a class="btn btn-secondary" onclick="history.back();"><i class="fas fa-arrow-left mr-2"></i>ย้อนกลับ</a>
                    <button class="btn btn-info" type="submit"><i class="fas fa-save mr-2"></i>บันทึก</button>
                </div>
            </div>
        </div>
    </form>
</div>
@section('plugins.Sweetalert2', true)
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@11"])
@section('plugins.Select2', true)

@push('js')
    <script>
        $(document).ready(function () {
            var demo1 = $('select[name="permission[]"]').bootstrapDualListbox({
                infoText: 'ทั้งหมด {0} รายการ',
                infoTextEmpty: 'ไม่มีรายการ',
                filterPlaceHolder: 'ค้นหา',
                moveAllLabel: 'เลือกทั้งหมด',
                removeAllLabel: 'นำออกทั้งหมด',
                infoTextFiltered: 'ทั้งหมด {0} จาก {1} รายการ',
                filterTextClear: '',
            });
        });

        function checkpass(){
            var pass1 = document.forms['frmuser']['password'].value;
            var pass2 = document.forms['frmuser']['comfirmpass'].value;

            if(pass1 === pass2){
                return true;
            }
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'รหัสผ่านไม่ตรงกัน',
                animation: false,
            });
            document.forms['frmuser']['password'].focus();
            return false;
        }
    </script>
@endpush
@endsection
