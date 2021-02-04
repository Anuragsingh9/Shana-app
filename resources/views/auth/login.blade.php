<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!--<link href="{{ publicAsset('css/app.css') }}" rel="stylesheet">-->
    <link rel="stylesheet" href="{{publicAsset('font-awesome/css/font-awesome.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('metisMenu/dist/metisMenu.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/animate.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/bootstrap.css')}}" />

    <!-- App styles -->
    <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/helper.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('styles/style.css')}}">
</head>
<body class="blank">

<!-- Simple splash screen-->
{{--<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1>Homer - Responsive Admin Theme</h1><p>Special Admin Theme for small and medium webapp with very clean and aesthetic style and feel. </p><div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div> </div> </div>--}}
<!--[if lt IE 7]>
<p class="alert alert-danger">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="color-line"></div>
<div class="login-container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center m-b-md">
                <img src="{{ publicAsset('images/shana-logo.png') }}" alt="Shana Logo"/>
            </div>
            <div class="hpanel">
                <div class="panel-body">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="email">E-Mail Address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button class="btn btn-success btn-block" type="submit">Login</button>
                       <!--  <a class="btn btn-default btn-block" href="#">Register</a> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Scripts -->
    <!-- <script src="{{ publicAsset('js/app.js') }}"></script>-->
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
