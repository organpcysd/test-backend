@extends('adminlte::page')
@php $pagename = 'แก้ไขบทบาท'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการบทบาท</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{route('permission.update',['permission' => $permission->id])}}">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col">
                <div class="card card-info card-outline">
                    <div class="card-header" style="font-size: 20px;">
                        {{ $pagename }}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="name">ชื่อสิทธิ์การเข้าถึง</label>
                                    <input name="name" type="text" class="form-control form-control-sm" value="{{ $permission->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="name">คำอธิบาย</label>
                                    <input name="description" type="text" class="form-control form-control-sm" value="{{ $permission->description }}" required>
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
            </div>
        </div>
    </form>
</div>
@endsection
