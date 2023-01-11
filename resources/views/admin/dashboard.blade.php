@extends('adminlte::page')
@php $pagename = 'หน้าแรก' @endphp
@section('title', setting('title').' | '.$pagename)
@push('css')
<style>
    .info-box .info-box-icon{
        width: 65px !important;
    }
</style>
@endpush
@section('content')
    <div class="p-3">
        <h3 class="mb-3">ภาพรวม</h3>
        <div class="row">
            @if(Auth::user()->hasPermissionTo('news'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-newspaper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ข่าวสาร</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('product'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-fw fa-boxes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">สินค้า</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('service'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-headset"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">บริการของเรา</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('promotion'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-bullhorn"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">โปรโมชั่น</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('review'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="far fa-flag"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">รีวิว</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('website'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-fw fa-globe"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">เว็บไซต์</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif

            @if(Auth::user()->hasPermissionTo('user'))
            <div class="col-sm-3">
                <div class="info-box shadow rounded-pill">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-fw fa-user-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ผู้ใช้งาน</span>
                        <span class="info-box-number count">150</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- <div class="row">
            <div class="col-sm-6">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <label class="text-info" style="font-size: 18px; font-weight:500 !important;">ผู้เข้าชม</label>
                    </div>
                    <div class="card-body">
                        <div class="float-right">
                            <button class="btn btn-secondary btn-sm">วัน</button>
                            <button class="btn btn-secondary btn-sm">สัปดาห์</button>
                            <button class="btn btn-secondary btn-sm">เดือน</button>
                        </div>
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

@push('js')
    <script>
        $('.count').each(function () {
            $(this).prop('Counter',0).animate({
                Counter: $(this).text()
            }, {
                duration: 1000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });



        const labels = ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'];

        const data = {
        labels: labels,
        datasets: [{
            label: 'My First Dataset',
            data: [65, 59, 80, 81, 56, 55, 40],
            fill: false,
            borderColor: '#17a2b8',
            tension: 0.1
        }]
        };

        const config = {
            type: 'line',
            data: data,
        };

        const ctx = document.getElementById('myChart');
        new Chart(ctx, config);


    </script>
@endpush
@endsection
