<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('backend.layouts.partials.meta_tags')
    <title>@yield('title', config('app.name'))</title>
    @include('backend.layouts.partials.styles')
    @yield('styles')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HX7G827G7B"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-HX7G827G7B');
    </script>
</head>

<body>
    {{-- <marquee style="font-size: 30px;
            color: blue;
            position: absolute;
            top: 68px;
            z-index: 1;">
        Thông báo! Hệ thống yêu cầu linh kiện chạy thử nghiệm 1 ngày 23/08/2024 !!!
    </marquee> --}}
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    @include('backend.layouts.partials.preloader')

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        @include('backend.layouts.partials.header')
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @include('backend.layouts.partials.sidebar')
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->

        <div class="page-wrapper" id="root">

            @yield('admin-content')
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            {{-- @include('backend.layouts.partials.footer') --}}
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    @include('backend.layouts.partials.theme-options')
    <div class="chat-windows"></div>
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
    <div id="fade_overlay"><img id="fade_loading" src="{{ asset('public/images/loadding.gif') }}"/></div>
    <script src="/js/lang"></script>
    <script src="{{ mix('/js/plugin.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
