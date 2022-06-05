<!DOCTYPE html>
<html lang="{{ current_local() }}" dir="{{ current_dir() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} {{ isset($title) ? "| $title" : '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .dataTable , .dataTable thead th{
            text-align: center !important;
        }
    </style>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    @if (current_dir() == 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/dashboard/css/adminlte.rtl.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/dashboard/css/adminlte.min.css') }}">
    @endif
    {{ $css ?? '' }}
    @stack('css')
</head>
<body {{ $attributes->class('hold-transition sidebar-mini') }}>
@if(!isset($auth))
    <!-- Site wrapper -->
    <div class="wrapper">
        <x-layout.header/>
        <x-layout.sidebar/>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            {{ $header ?? '' }}
            <!-- Main content -->
            <section class="content">
                {{ $slot }}
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <x-layout.footer/>
    </div>
    <!-- ./wrapper -->
@else
    {{ $slot }}
@endif


<!-- jQuery -->
<script src="{{ asset('assets/dashboard/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dashboard/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
{{--<script src="{{ asset('assets/dashboard/js/demo.js') }}"></script>--}}
@include('sweetalert::alert')

{{ $js ?? '' }}
@stack('js')

</body>
</html>
