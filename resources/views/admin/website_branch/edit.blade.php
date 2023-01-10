@extends('adminlte::page')
@php $pagename = 'แก้ไขโปรโมชั่น'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการสาขา</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('branch.update',['branch' => $branch->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link text-cyan active" id="th-tab" data-toggle="tab" href="#th" role="tab" aria-controls="th" aria-selected="true">ภาษาไทย</a>
                                    </li>
                                    @if(Auth::user()->hasPermissionTo('english-language'))
                                    <li class="nav-item">
                                        <a class="nav-link text-cyan" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">ภาษาอังกฤษ</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="th" role="tabpanel" aria-labelledby="th-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    @if(Auth::user()->hasAnyRole('superadmin','admin'))
                                                    <div class="mb-3">
                                                        <label class="form-label" selected>เว็บไซต์</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ $branch->website->name }}" readonly>
                                                        <select name="website" class="form-control form-control-sm" style="display: none;">
                                                            <option value="" disabled selected>--- เลือกเว็บไซต์ ---</option>
                                                            @foreach($websites as $item)
                                                                <option value="{{$item->id}}" @if($branch->website_id == $item->id) selected @endif>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ชื่อสาขา</label>
                                                    <input type="text" class="form-control form-control-sm" name="name_th" value="{{ json_decode($branch->name)->th }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">ที่อยู่</label> <br/>
                                                    <textarea name="address_th" class="form-control form-control-sm">{{ json_decode($branch->address)->th }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">ชื่อสาขา</label>
                                                    <input type="text" class="form-control form-control-sm" name="name_en" value="{{ json_decode($branch->name)->en }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ที่อยู่</label> <br/>
                                                    <textarea name="address_en" class="form-control form-control-sm">{{ json_decode($branch->address)->en }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <h3 class="mb-3">ข้อมูลติดต่อ</h3>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="form-control form-control-sm" name="phone" value="{{ $branch->phone }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">อีเมล</label>
                                    <input type="text" class="form-control form-control-sm" name="email" value="{{ $branch->email }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Facebook</label>
                                    <input type="text" class="form-control form-control-sm" name="facebook" value="{{ $branch->facebook }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Messenger</label>
                                    <input type="text" class="form-control form-control-sm" name="messenger" value="{{ $branch->messenger }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Line</label>
                                    <input type="text" class="form-control form-control-sm" name="line" value="{{ $branch->line }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Line Token</label>
                                    <input type="text" class="form-control form-control-sm" name="line_token" value="{{ $branch->line_token }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Google Map</label> <small class="text-danger">**embed link</small> <br/>
                                    <input type="text" class="form-control form-control-sm" name="google_map" value="{{ $branch->google_map }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Social 1</label>
                                    <input type="text" class="form-control form-control-sm" name="social_1" value="{{ $branch->social_1 }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Social 2</label>
                                    <input type="text" class="form-control form-control-sm" name="social_2" value="{{ $branch->social_2 }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Social 3</label>
                                    <input type="text" class="form-control form-control-sm" name="social_3" value="{{ $branch->social_3 }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Social 4</label>
                                    <input type="text" class="form-control form-control-sm" name="social_4" value="{{ $branch->social_4 }}">
                                </div>
                            </div>
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

@endsection
