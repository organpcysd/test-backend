@extends('adminlte::page')
@php $pagename = 'จัดการบทบาท' @endphp
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
            <a href="{{ route('role.create') }}" class="btn btn-info mb-2">เพิ่มข้อมูล</a>
            <table id="table" class="table table-striped table-hover table-sm dataTable no-footer dtr-inline nowrap"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">##</th>
                        <th class="text-center">หัวเรื่อง</th>
                        <th class="text-center">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
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
					{className: 'text-center', targets: [0,2]},
					{orderable: false,	targets: [2]}
				],
                columns: [
                    {data: 'DT_RowIndex', name: 'id'} ,
                    {data: 'name'},
                    {data: 'btn'},
                ],
            });
        });
    </script>
@endpush
@endsection
