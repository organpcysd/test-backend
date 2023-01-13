@extends('adminlte::page')
@php $pagename = 'เพิ่มข่าวสาร'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการข่าวสาร</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('news.store') }}" enctype="multipart/form-data" id="form">
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
                                                        <label class="form-label" selected>เว็บไซต์</label> <br/>
                                                        <select name="website" id="website" class="sel2 ac-contentform-control form-control-sm" style="width: 100%;">
                                                            <option value="" disabled selected>--- เลือกเว็บไซต์ ---</option>
                                                            @foreach($websites as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">หัวข้อ</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_th" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียดย่อย</label> <br/>
                                                    <textarea name="short_detail_th" class="form-control form-control-sm"></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียด</label> <br/>
                                                    <textarea class="summernote" name="detail_th"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">หัวข้อ</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_en">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียดย่อย</label> <br/>
                                                    <textarea name="short_detail_en" class="form-control form-control-sm"></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">รายละเอียด</label> <br/>
                                                    <textarea class="summernote" name="detail_en"></textarea>
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
                            <textarea type="text" class="form-control form-control-sm" name="seo_keyword"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SEO Description</label>
                            <textarea type="text" class="form-control form-control-sm" name="seo_description"></textarea>
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
@push('js')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script> --}}
<script>
    $('#form').submit(function() {
        role = {!! Auth::user()->hasAnyRole('superadmin','admin') ? 'true' : 'false' !!};

        if($('#website').val() == null && role === true){
            toastr.error('กรุณาเลือกเว็บไซต์');
            return false;
        }
    });
</script>

@endpush
@endsection
