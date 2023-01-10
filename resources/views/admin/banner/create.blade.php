@extends('adminlte::page')
@php $pagename = 'เพิ่มแบนเนอร์'; @endphp
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

    <form method="post" action="{{ route('banner.store') }}" enctype="multipart/form-data" id="form">
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
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
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">ชื่อแบนเนอร์ (ภาษาไทย)</label>
                            <input type="text" class="form-control form-control-sm" name="title_th">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียด (ภาษาไทย)</label>
                            <input type="text" class="form-control form-control-sm" name="detail_th">
                        </div>
                    </div>
                    @if(Auth::user()->hasPermissionTo('english-language'))
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">ชื่อแบนเนอร์ (ภาษาอังกฤษ)</label>
                            <input type="text" class="form-control form-control-sm" name="title_en">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียด (ภาษาอังกฤษ)</label>
                            <input type="text" class="form-control form-control-sm" name="detail_en">
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <label class="form-label">รูปแบนเนอร์ (ปกติ)</label> <br/>
                                    <img src="{{ asset('images/no-image.jpg') }}" height="200" id="showimg1" style="cursor: pointer; max-width: 100%"> <br/>
                                    <span class="text-danger">**รูปภาพขนาด 1920x700 pixel**</span>
                                </div>
                                <div class="custom-file">
                                    <input name="img" type="file" class="custom-file-input" id="imgInp1" accept="image/*" onchange="return fileValidation(this)" required>
                                    <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <label class="form-label">รูปแบนเนอร์ (โทรศัพท์)</label> <br/>
                                    <img src="{{ asset('images/no-image.jpg') }}" height="200" id="showimg2" style="cursor: pointer; max-width: 100%"> <br/>
                                    <span class="text-danger">**รูปภาพขนาด 500x700 pixel**</span>
                                </div>
                                <div class="custom-file">
                                    <input name="img2" type="file" class="custom-file-input" id="imgInp2" accept="image/*" onchange="return fileValidation(this)" required>
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
@push('js')
<script>
    $('#form').submit(function() {
        role = {!! Auth::user()->hasAnyRole('superadmin','admin') ? 'true' : 'false' !!};

        if($('#website').val() == null && role === true){
            toastr.options = {
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "progressBar": true,
                "newestOnTop": true,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.info('กรุณาเลือกเว็บไซต์');
            return false;
        }
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
