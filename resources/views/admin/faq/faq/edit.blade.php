@extends('adminlte::page')
@php $pagename = 'แก้ไขคำถาม-ตอบ'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#" onclick="history.back()" class="text-info">จัดการคำถาม-ตอบ</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <form method="post" action="{{ route('faq.update',['faq' => $faq->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card card-info card-outline">
            <div class="card-header" style="font-size: 20px;">
                {{ $pagename }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
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
                                                @if(Auth::user()->hasAnyRole('superadmin','admin'))
                                                <div class="mb-3">
                                                    <label class="form-label" selected>เว็บไซต์</label>
                                                    <input type="text" class="form-control form-control-sm" value="{{ $faq->website->name }}" readonly>
                                                    <select name="website" class="form-control form-control-sm" style="display: none;" id="website">
                                                        <option value="" disabled selected>--- เลือกเว็บไซต์ ---</option>
                                                        @foreach($websites as $item)
                                                            <option value="{{$item->id}}" @if($faq->website_id == $item->id) selected @endif>{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label" selected>หมวดหมู่</label>
                                                    <select name="faqcategory" class="form-control form-control-sm" id="faqcategory" required>
                                                        <option value="" disabled>--- เลือกหมวดหมู่ ---</option>
                                                        @foreach($categories as $item)
                                                            <option value="{{$item->id}}" @if($faq->faq_category_id == $item->id) selected @endif>{{ json_decode($item->title)->th }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">คำถาม</label>
                                                    <input type="text" class="form-control form-control-sm" name="question_th" value="{{ json_decode($faq->question)->th }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">คำตอบ</label>
                                                    <textarea class="summernote form-control form-control-sm" name="answer_th" required>{{ json_decode($faq->answer)->th }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                    <label class="form-label">คำถาม</label>
                                                    <input type="text" class="form-control form-control-sm" name="question_en" value="{{ json_decode($faq->question)->en }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">คำตอบ</label>
                                                    <textarea class="summernote form-control form-control-sm" name="answer_en" required>{{ json_decode($faq->answer)->en }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
<script>

</script>
@endpush
@endsection