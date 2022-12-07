@extends('adminlte::page')
@php $pagename = 'แก้ไขแบนเนอร์'; @endphp
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

    <form method="post" action="{{ route('banner.update',['banner' => $banner->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        @if(Auth::user()->hasRole('superadmin'))
                        <div class="mb-3">
                            <label class="form-label">เว็บไซต์</label>
                            <select name="website" class="form-control form-control-sm" required readonly>
                                <option value="" disabled>--- เลือกเว็บไซต์ ---</option>
                                @foreach($websites as $item)
                                    <option value="{{$item->id}}" @if($banner->website_id == $item->id) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">ชื่อแบนเนอร์</label>
                            <input type="text" class="form-control form-control-sm" name="title" value="{{ $banner->title }}" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <label class="form-label">รูปแบนเนอร์ (ปกติ)</label> <br/>
                                    <img src="@if($banner->getFirstMediaUrl('banner')) {{$banner->getFirstMediaUrl('banner')}} @else {{asset('images/no-image.jpg')}} @endif" width="auto" height="200" id="showimg1"> <br/>
                                    <span class="text-danger">**รูปภาพขนาด 1920x700 pixel**</span>
                                </div>
                                <div class="custom-file">
                                    <input name="img" type="file" class="custom-file-input" id="imgInp1" accept="image/*" onchange="return fileValidation(this)">
                                    <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <label class="form-label">รูปแบนเนอร์ (โมบาย)</label> <br/>
                                    <img src="@if($banner->getFirstMediaUrl('banner_mobile')) {{$banner->getFirstMediaUrl('banner_mobile')}} @else {{asset('images/no-image.jpg')}} @endif" width="auto" height="200" id="showimg2"> <br/>
                                    <span class="text-danger">**รูปภาพขนาด 500x700 pixel**</span>
                                </div>
                                <div class="custom-file">
                                    <input name="img2" type="file" class="custom-file-input" id="imgInp2" accept="image/*" onchange="return fileValidation(this)">
                                    <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
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
@section('plugins.CustomFileInput', true)

@push('js')
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    });

    $('#showimg1').click(function () {
            $('#imgInp1').trigger('click');
    });

    $('#showimg2').click(function () {
            $('#imgInp2').trigger('click');
    });

    function previewImg(id) {
            const [file] = id.files
            if (file) {
                if(id.id === "imgInp1"){
                    showimg1.src = URL.createObjectURL(file);
                }else if(id.id === "imgInp2"){
                    showimg2.src = URL.createObjectURL(file);
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
