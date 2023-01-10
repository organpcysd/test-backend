<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        {{-- @yield('title_prefix', config('adminlte.title_prefix', '')) --}}
        {{-- @yield('title', config('adminlte.title', 'AdminLTE 3')) --}}
        {{-- @yield('title_postfix', config('adminlte.title_postfix', '')) --}}
        @yield('title', setting('title'))
    </title>

    {{-- <script src="https://kit.fontawesome.com/4134f7c670.js" crossorigin="anonymous"></script> --}}
    <link href='https://fonts.googleapis.com/css?family=Kanit:400,300&subset=thai,latin' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.4.6/css/flag-icon.min.css">

    {{-- Text Editor --}}
    <script src="https://cdn.tiny.cloud/1/b45ns822wglixl75gol486a00kvm8ummsvel4lwy8n6ylpmx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

        @if(config('adminlte.google_fonts.allowed', true))
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        @endif
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        {{-- Custom Favicon --}}
        <link rel="shortcut icon" href="@if(setting('img_favicon')) {{ asset(setting('img_favicon')) }} @else {{ asset('images/no-image.jpg') }} @endif" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif
    <style>
        /* Sidebar */
        .nav-header {
            color: #000000 !important;
            background: #F3F3F3 !important;
            padding: -1rem !important;
            border: 0 !important;
        }

        .navbar-light .navbar-nav .nav-link{
            color: #ffffff
        }

		.sidebar {
            padding-left: 0 !important ;
            padding-right: 0 !important ;
        }

        .sidebar-mini .main-sidebar .nav-link, .sidebar-mini-md .main-sidebar .nav-link, .sidebar-mini-xs .main-sidebar .nav-link {
            width: auto !important;
        }

        .nav-pills .nav-link{
            border-radius : 0rem !important;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 45px;
            height: 25px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 4px;
            bottom: 5px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26x);
            -ms-transform: translateX(26px);
            transform: translateX(22px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* Dropzone */
        .dropzone .dz-preview .dz-image img {
            width: 100% !important;
            display: block;
        }

        body {
            font-family: kanit !important;
        }
    </style>
</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- SummerNote --}}
    <link href="{{ asset('plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/summernote/plugin/tam-emoji/css/emoji.css') }}" rel="stylesheet">

    <script src="{{ asset('plugins/summernote/summernote-bs4.js') }}"></script>

    <script src="{{ asset('plugins/summernote/plugin/tam-emoji/js/config.js') }}" rel="stylesheet"></script>
    <script src="{{ asset('plugins/summernote/plugin/tam-emoji/js/tam-emoji.min.js') }}" rel="stylesheet"></script>

    <script>
        // # Emoji Button
        document.emojiButton = 'fas fa-smile'; // default: fa fa-smile-o
        // #The Emoji selector to input Unicode characters instead of images
        document.emojiType = 'unicode'; // default: image
        // #Relative path to emojis
        document.emojiSource = '{{ asset('plugins/summernote/plugin/tam-emoji/img') }}';

        //SummerNote
        $(document).ready(function() {
            $('.sel2').select2();
            bsCustomFileInput.init();
            // emojione.ascii = true;
            $('.summernote').summernote({
                tabsize: 2,
                height: 200,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear','fontname','fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['picture', 'link','video','emoji']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
            });
        });
    </script>

    {{-- component function --}}
    <script src="{{ asset('js/admin/component.js') }}"></script>

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

</body>

</html>
