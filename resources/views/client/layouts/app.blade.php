<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>@if(empty(setting('title'))) {{ config('app.name', 'Meeting') }} @else {{setting('title')}} @endif</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="@if(empty(setting('img_favicon'))) {{asset('images/no-image.jpg')}} @else {{asset(setting('img_favicon'))}} @endif"/>

    {{-- SEO TAG default --}}
    <meta name="keywords" content="@yield('meta_tag_keyword',setting('seo_keyword'))">
    <meta name="description" content="@yield('meta_tag_description',setting('seo_description'))">

    {{-- Font Awesome 6.2.0 --}}
    <link href="{{ asset('fontawesome/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome/css/solid.css') }}" rel="stylesheet">

    <!-- POLO -->
    <link href="{{asset('/polo/css/plugins.css')}}" rel="stylesheet">
    <link href="{{asset('/polo/css/style.css')}}" rel="stylesheet">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Kanit:400,300&subset=thai,latin' rel='stylesheet' type='text/css'>

    @stack('css')
    <!-- Styles -->
    <style>
        body {
            font-family: kanit !important;
        }
    </style>

    <!-- Google tag (gtag.js) -->

</head>
<body>
    @include('client.layouts.header')
    @yield('content')
    @include('client.layouts.footer')

  <!--Plugins-->
  <script src="{{asset('polo/js/jquery.js')}}"></script>
  <script src="{{asset('polo/js/plugins.js')}}"></script>


  <!--Template functions-->
  <script src="{{asset('polo/js/functions.js')}}"></script>

  @stack('js')

</body>
</html>
