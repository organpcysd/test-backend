@extends('adminlte::page')
@php $pagename = 'จัดการเว็บไซต์'; @endphp
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
            {{ $pagename }}
        </div>
        <div class="card-body">
            <div class="float-right">
                <input type="search" id="custom-search-input" class="form-control form-control-sm" placeholder="ค้นหา">
            </div>
            <a href="{{ route('website.create') }}" class="btn btn-info mb-2">เพิ่มข้อมูล</a>
            <table id="table" class="table table-striped table-hover table-sm dataTable no-footer dtr-inline nowrap"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">##</th>
                        <th class="text-center">ชื่อเว็บไซต์</th>
                        <th class="text-center">ชื่อโดเมน</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@11"])

@push('js')
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var table;
        $(document).ready( function () {
            table = $('#table').DataTable({
                pageLength: 50,
                responsive: true,
                processing: true,
                scrollX: true,
                scrollCollapse: true,
                language: {
                    url: "{{ asset('vendor/datatables/th.json') }}",
                },
                serverSide: true,
                ajax: "",
                columnDefs: [
					{className: 'text-center', targets: [0,3]},
					{orderable: false,	targets: [3]}
				],
                columns: [
                    {data: 'DT_RowIndex', name: 'id'} ,
                    {data: 'name'},
                    {data: 'domain_name'},
                    {data: 'btn'},
                ],
                "dom": 'rtip',
            });
        });

        // search
        $('#custom-search-input').keyup(function(){
            table.search($(this).val()).draw();
        })

</script>
@endpush
@endsection