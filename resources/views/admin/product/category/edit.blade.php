@extends('adminlte::page')
@php $pagename = 'แก้ไขหมวดหมู่'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการหมวดหมู่สินค้า</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('category.update',['category' => $category->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
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
                                                        <input type="text" class="form-control form-control-sm" value="{{ $category->website->name }}" readonly>
                                                        <select name="website" class="form-control form-control-sm" style="display: none;">
                                                            <option value="" disabled selected>--- เลือกเว็บไซต์ ---</option>
                                                            @foreach($websites as $item)
                                                                <option value="{{$item->id}}" @if($category->website_id == $item->id) selected @endif>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ชื่อหมวดหมู่</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_th" value="{{ json_decode($category->title)->th }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">ชื่อหมวดหมู่</label>
                                                    <input type="text" class="form-control form-control-sm" name="title_en" value="{{ json_decode($category->title)->en }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <div class="text-center">
                                <label class="form-label">รูปภาพ</label> <br/>
                                <img src="@if($category->getFirstMediaUrl('product_category')) {{$category->getFirstMediaUrl('product_category')}} @else {{asset('images/no-image.jpg')}} @endif" height="200" id="showimg1" style="cursor: pointer; max-width: 100%"> <br/>
                                <span class="text-danger">**รูปภาพขนาด 300x300 pixel**</span>
                            </div>
                            <div class="custom-file">
                                <input name="img" type="file" class="custom-file-input" id="imgInp1" accept="image/*" onchange="return fileValidation(this)">
                                <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
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
@section('plugins.CustomFileInput', true)

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    });

    $('#showimg1').click(function () {
            $('#imgInp1').trigger('click');
    });

    function previewImg(id) {
            const [file] = id.files
            if (file) {
                if(id.id === "imgInp1"){
                    showimg1.src = URL.createObjectURL(file);
                }
            }
    }

    function fileValidation(ele) {
        var fileInput = ele;
        var filePath = fileInput.value;

        // Allowing file type
        var allowedExtensions = /(\.gif|\.png|\.jpeg|\.jpg)$/i;

        if (!allowedExtensions.exec(filePath)) {
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'ไฟล์ที่นำเข้าต้องเป็นไฟล์รูปภาพเท่านั้น',
                timer: 2000,
            })
            fileInput.value = '';
            return false;
        } else {
            previewImg(fileInput);
        }
    }

</script>
@endpush
@endsection
