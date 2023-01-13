@extends('adminlte::page')
@php $pagename = 'แก้ไขรีวิว'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการรีวิว</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('review.update',['review' => $review->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
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
                                                        <input type="text" class="form-control form-control-sm" value="{{ $review->website->name }}" readonly>
                                                        <select name="website" class="form-control form-control-sm" style="display: none;">
                                                            <option value="" disabled selected>--- เลือกเว็บไซต์ ---</option>
                                                            @foreach($websites as $item)
                                                                <option value="{{$item->id}}" @if($review->website_id == $item->id) selected @endif>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">หัวข้อ</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_th" value="{{ json_decode($review->title)->th }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียดย่อย</label> <br/>
                                                    <textarea name="short_detail_th" class="form-control form-control-sm">{{ json_decode($review->short_detail)->th }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Facebook</label> <small class="text-danger">**Url</small>
                                                    <input type="text" class="form-control form-control-sm" name="facebook" value="{{ $review->facebook }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Instagram</label> <small class="text-danger">**Url</small>
                                                    <input type="text" class="form-control form-control-sm" name="instagram" value="{{ $review->instagram }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Twitter</label> <small class="text-danger">**Url</small>
                                                    <input type="text" class="form-control form-control-sm" name="twitter" value="{{ $review->twitter }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียด</label> <br/>
                                                    <textarea class="summernote" name="detail_th">{{ json_decode($review->detail)->th }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">หัวข้อ</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_en" value="{{ json_decode($review->title)->en }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียดย่อย</label> <br/>
                                                    <textarea name="short_detail_en" class="form-control form-control-sm">{{ json_decode($review->short_detail)->en }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียด</label> <br/>
                                                    <textarea class="summernote" name="detail_en">{{ json_decode($review->detail)->en }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label class="form-label">รูปภาพ</label>
                            <div class="dropzone image-preview" id="imageDropzone">
                                <div class="dz-message">
                                    <i class="fas fa-upload"></i><br>
                                    <span>แนบไฟล์ภาพ</span>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="mb-3">
                            <label class="form-label">SEO Keyword</label>
                            <textarea type="text" class="form-control form-control-sm" name="seo_keyword">{{ $review->seo_keyword }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SEO Description</label>
                            <textarea type="text" class="form-control form-control-sm" name="seo_description">{{ $review->seo_description }}</textarea>
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
