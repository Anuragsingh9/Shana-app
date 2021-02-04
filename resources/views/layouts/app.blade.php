<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shana') }}</title>

    <!-- Styles -->
    <!--<link href="{{ publicAsset('css/app.css') }}" rel="stylesheet">-->
    <link rel="stylesheet" href="{{publicAsset('font-awesome/css/font-awesome.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('metisMenu/dist/metisMenu.css')}}" />
    {{-- <link rel="stylesheet" href="{{publicAsset('css/animate.css')}}" /> --}}
    <link rel="stylesheet" href="{{publicAsset('css/bootstrap.css')}}" />

    <!-- App styles -->
    {{-- <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/helper.css')}}" /> --}}
    <link rel="stylesheet" href="{{publicAsset('styles/style.css')}}">
</head>
<body class="blank">

@yield('content');

    <!-- Scripts -->
    <script src="{{publicAsset('js/jquery.min.js')}}"></script>
<script src="{{publicAsset('jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{publicAsset('slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{publicAsset('js/bootstrap.min.js')}}"></script>
<script src="{{publicAsset('metisMenu/dist/metisMenu.min.js')}}"></script>
<script src="{{publicAsset('js/icheck.min.js')}}"></script>
<script src="{{publicAsset('js/index.js')}}"></script>

<!-- App scripts -->
<script src="{{publicAsset('scripts/homer.js')}}"></script>
</body>
</html>
