@extends('adminlte::page')
@php $pagename = 'จัดการหมวดหมู่ย่อยสินค้า'; @endphp
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
            @if(Auth::user()->hasAnyRole('superadmin','admin'))
                <div class="float-right">
                    <select id="custom-search-input-select" class="form-control form-control-sm">
                        <option value="">ทั้งหมด</option>
                        @foreach ($websites as $item)
                            <option>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="float-right">
                <input type="search" id="custom-search-input" class="form-control form-control-sm" placeholder="ค้นหา">
            </div>
            <a href="{{ route('subcategory.create') }}" class="btn btn-info mb-2">เพิ่มข้อมูล</a>
            <table id="table" class="table table-striped table-hover table-sm dataTable no-footer dtr-inline nowrap"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">##</th>
                        <th class="text-center">เว็บไซต์</th>
                        <th class="text-center">หมวดหมู่หลัก</th>
                        <th class="text-center">หมวดหมู่ย่อย</th>
                        <th class="text-center">รูปภาพ</th>
                        <th class="text-center">การมองเห็น</th>
                        <th class="text-center" style="width: 10%">ลำดับ</th>
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

            role = {{ Auth::user()->hasAnyRole('superadmin','admin') ? 'true' : 'false' }};

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
					{className: 'text-center', targets: [0,4,5,6,7]},
					{orderable: false,	targets: [4,5,6,7]},
                    {visible: role, targets: [1]}
				],
                columns: [
                    {data: 'DT_RowIndex', name: 'id'},
                    {data: 'website'},
                    {data: 'category'},
                    {data: 'title'},
                    {data: 'img', searchable: false},
                    {data: 'publish'},
                    {data: 'sorting'},
                    {data: 'btn'},
                ],
                "dom": 'rtip',
            });
        });

        //custom search datatable
        $('#custom-search-input').keyup(function(){
            table.search($(this).val()).draw();
        })

        $('#custom-search-input-select').change(function(){
            table.search($(this).val()).draw() ;
        })
</script>
@endpush
@endsection
