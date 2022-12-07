@extends('adminlte::page')
@php $pagename = 'เพิ่มเว็บไซต์'; @endphp
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

    <form method="POST" action="{{ route('website.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link text-cyan active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">ข้อมูลทั่วไป</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-cyan" id="images-tab" data-toggle="tab" href="#images" role="tab" aria-controls="images" aria-selected="false">รูปภาพ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-cyan" id="aboutus-tab" data-toggle="tab" href="#aboutus" role="tab" aria-controls="aboutus" aria-selected="false">เกี่ยวกับเรา</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-cyan" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo" aria-selected="false">SEO</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                @include('admin.website.partials.create_general')
                            </div>
                            <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                                @include('admin.website.partials.create_images')
                            </div>
                            <div class="tab-pane fade" id="aboutus" role="tabpanel" aria-labelledby="aboutus-tab">
                                @include('admin.website.partials.create_aboutus')
                            </div>
                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                @include('admin.website.partials.create_seo')
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
@section('plugins.Sweetalert2', true)
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@11"])
@section('plugins.CustomFileInput', true)

@push('js')
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init()
        });

        $('#showimg_favicon').click(function () {
            $('#img_favicon').trigger('click');
        });

        $('#showimg_logo').click(function () {
            $('#img_logo').trigger('click');
        });

        $('#showimg_og').click(function () {
            $('#img_og').trigger('click');
        });

        $('#showimg_aboutus').click(function () {
            $('#img_aboutus').trigger('click');
        });

        $('#showimg_product').click(function () {
            $('#img_product').trigger('click');
        });

        $('#showimg_service').click(function () {
            $('#img_service').trigger('click');
        });

        $('#showimg_promotion').click(function () {
            $('#img_promotion').trigger('click');
        });

        $('#showimg_news').click(function () {
            $('#img_news').trigger('click');
        });

        $('#showimg_faq').click(function () {
            $('#img_faq').trigger('click');
        });

        $('#showimg_contact').click(function () {
            $('#img_contact').trigger('click');
        });

        function previewImg(id) {
            const [file] = id.files
            if (file) {
                if(id.id === "img_favicon"){
                    showimg_favicon.src = URL.createObjectURL(file);
                }else if(id.id === "img_logo"){
                    showimg_logo.src = URL.createObjectURL(file);
                }else if(id.id === "img_og"){
                    showimg_og.src = URL.createObjectURL(file);
                }else if(id.id === "img_aboutus"){
                    showimg_aboutus.src = URL.createObjectURL(file);
                }else if(id.id === "img_product"){
                    showimg_product.src = URL.createObjectURL(file);
                }else if(id.id === "img_service"){
                    showimg_service.src = URL.createObjectURL(file);
                }else if(id.id === "img_promotion"){
                    showimg_promotion.src = URL.createObjectURL(file);
                }else if(id.id === "img_news"){
                    showimg_news.src = URL.createObjectURL(file);
                }else if(id.id === "img_faq"){
                    showimg_faq.src = URL.createObjectURL(file);
                }else if(id.id === "img_contact"){
                    showimg_contact.src = URL.createObjectURL(file);
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
