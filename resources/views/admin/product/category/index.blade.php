@extends('adminlte::page')
@php $pagename = 'จัดการหมวดหมู่สินค้า'; @endphp
@section('title', $pagename)
@section('content')
<div class="contrainer p-2">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background-color: transparent;">
                <li class="breadcrumb-item"><a href="{{ url('admin') }}" class="text-info"><i class="fa fa-home fa-fw" aria-hidden="true"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item active">{{ $pagename }}</li>
            </ol>
        </nav>
    </div>

    <div class="card card-info card-outline">
        <div class="card-header" style="font-size: 20px;">
            {{ $pagename }} @if(request()->get('website')) {{ ' : ' . $websites->where('id',request()->get('website'))->first()->name }}  @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <a href="@if(request()->get('website')) {{ route('productcategory.create',['website' => request()->get('website')]) }} @else {{ route('productcategory.create') }} @endif" class="btn btn-info mb-2">เพิ่มข้อมูล</a>
                    <x-categories :categories="$categories"/>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="accordion" id="accordionExample">
                                <div class="card mb-0">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                        <button class="btn btn-link float-right" type="button" data-toggle="collapse" data-target="#headingThree" aria-expanded="false"> ➕ </button>
                                        <a>เทส</a>
                                        </h5>
                                    </div>
                                </div>
                                <div class="card collapse mb-0" id="headingThree">
                                    <div class="card-header" >
                                        <h5 class="mb-0">
                                        <button class="btn btn-link float-right collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false">
                                            ➕
                                        </button>
                                        <a class="ml-3">เทส</a>
                                        </h5>
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

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@11"])

@push('js')
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    function productcategory_confirmdelete(url){
    Swal.fire({
        title: 'ต้องการลบใช่หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: url,
                data: {
                    _token: CSRF_TOKEN
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire({
                            title: response.msg,
                            icon: 'success',
                            timeout: 2000,
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            title: response.msg,
                            icon: 'error',
                            timeout: 2000,
                        });
                    }
                }
            });

        }
    });
    }
</script>
@endpush
@endsection
