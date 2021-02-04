<!DOCTYPE html>
<html>

<!-- Mirrored from webapplayers.com/homer_admin-v1.9.1/tables_design.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Mar 2017 08:50:43 GMT -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ url('') }}">
    <!-- Page title -->
    <title>{{ config('app.name', 'Shana') }}</title>
<link rel="shortcut icon" type="image/png" href="{{ publicAsset('images/favicon-32x32.png')}}"/>
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <!--<link rel="shortcut icon" type="image/ico" href="favicon.ico" />-->

    <!-- Vendor styles -->
    <link rel="stylesheet" href="{{publicAsset('font-awesome/css/font-awesome.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('metisMenu/dist/metisMenu.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/animate.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/bootstrap.css')}}" />

    <!-- App styles -->
    <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('fonts/pe-icon-7-stroke/css/helper.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('styles/style.css')}}">
    <link rel="stylesheet" href="{{publicAsset('css/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{publicAsset('ckeditor/samples/css/samples.css')}}">
    <link rel="stylesheet" href="{{publicAsset('css/sweet-alert.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/toastr.min.css')}}" />
    <link rel="stylesheet" href="{{publicAsset('css/adminStyle.css')}}" />
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"> 
    <script src="{{publicAsset('js/jquery.min.js')}}"></script>
    <script src="{{publicAsset('js/bootstrap.min.js')}}"></script>

</head>

<body class="fixed-navbar fixed-sidebar">
    
        <div class="loader" id="siteLoader">
                <img src="{{ publicAsset('css/images/loader.gif') }}" alt="Loader">
            </div>
        <div id="site"class="hidden">
<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <img src="{{ publicAsset('images/shana-logo.png') }}" height="38px"  alt="Shana Logo" class="img-responsive" />
    </div>
    <nav role="navigation">
        <!--   <div class="header-link hide-menu"><i class="fa fa-bars"></i></div> -->
        <div class="small-logo">
            <span class="text-primary">K.C Pathshala</span>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                        <i class="pe-7s-upload pe-rotate-90"></i>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</div>

<!-- Navigation -->
    <aside id="menu">
        <div id="navigation">
            <div class="profile-picture">
            <!--  <a href={{url('dashboard')}}>
                    <img src={{getImgSrc('uploads/profile/',Auth::user()->photo)}} class="img-circle m-b" alt="logo">
                </a> -->

                <div class="stats-label text-color">
                    <span class="font-extra-bold font-uppercase">{{Auth::user()->name}}</span>
                </div>
            </div>

            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{url('dashboard')}}"><span class="fa fa-tachometer dash-icon"></span> <span class="nav-label">Dashboard</span></a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-pencil-square-o dash-icon"></span><span class="nav-label">Blog</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/book') }}">Add Blog</a></li>
                        <li><a href="{{ url('/book-list') }}">Blog List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-pencil-square-o dash-icon"></span><span class="nav-label">Subject</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/subject') }}">Add Subject</a></li>
                        <li><a href="{{ url('/subject-list') }}">Subject List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-book dash-icon"></span><span class="nav-label">Course</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/course') }}">Add Course</a></li>
                        <li><a href="{{ url('/course-list') }}">Course List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="glyphicon glyphicon-list-alt dash-icon"></span><span class="nav-label">Chapter</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/chapter') }}">Add Chapter</a></li>
                        <li><a href="{{ url('/chapter-list') }}">Chapter List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-comment dash-icon"></span><span class="nav-label">Topic</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/topic') }}">Add Topic</a></li>
                        <li><a href="{{ url('/topic-list') }}">Topic List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-file dash-icon"></span><span class="nav-label">Document</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/document') }}">Add Document</a></li>
                        <li><a href="{{ url('/multi/document') }}">Add Multiple Document</a></li>
                        {{-- <li><a href="{{ url('/document-list') }}">Document List</a></li> --}}
                        <li><a href="{{ url('/document-list') }}">Document List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#"><span class="fa fa-picture-o dash-icon"></span><span class="nav-label">Home Images</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                    {{-- <li><a href="{{ url('/home-images') }}">Add Images</a></li>--}}
                        <li><a href="{{ url('/home-images-list') }}">Slider Images List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#"><span class="fa fa-picture-o dash-icon"></span><span class="nav-label">Plan Images Slider</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        {{--<li><a href="{{ url('/plan-images') }}">Add Images</a></li>--}}
                        <li><a href="{{ url('/plan-images-list') }}">Plan Slider Images List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#"><span class="fa fa-plus dash-icon"></span><span class="nav-label">Site Info</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                    {{--  <li><a href="{{ url('/add-about') }}">Add About Us</a></li>
                        <li><a href="{{ url('/add-contact') }}">Add Contact Us</a></li>
                        <li><a href="{{ url('/add-term') }}">Add Term & conditions</a></li>--}}
                        <li><a href="{{ url('/site-info') }}">Site Info List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#"><span class="fa fa-plus dash-icon"></span><span class="nav-label">Plans</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/plan') }}">Add Plans</a></li>
                        <li><a href="{{ url('/plan-list') }}">Plans List</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span class="fa fa-plus dash-icon"></span><span class="nav-label">Purchase History</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ url('/plan-history') }}">Plans History</a></li>
                        <li><a href="{{ url('/other-history') }}">Subject/Course History </a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{url('user')}}"><span class="glyphicon glyphicon-user dash-icon"></span><span class="nav-label">User</span></a>
                </li>
                <li>
                    <a href="{{url('splash')}}"><span class="fa fa-film dash-icon"></span> <span class="nav-label">Splash Images</span></a>
                </li>
                <li>
                    <a href="#"><span class="fa fa-bell dash-icon"></span><span class="nav-label">Notification</span><span class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{ route('addPush') }}">Add Notification</a></li>
                        <li><a href="{{ route('notification-list') }}">Notification List</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div id="wrapper">
    @yield('content')
    <!-- Footer-->
        <footer class="footer">
            <div class="pull-left">SHANA</div>
            <div class="pull-right"><a href="https://www.pebibits.com/" target="_blank">Design By: PEBIBITS PVT LTD.</a></div>
        </footer>
    </div>
</div>



<script src="{{publicAsset('jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{publicAsset('js/bootstrap-multiselect.js')}}"></script>

<script src="{{publicAsset('ckeditor/ckeditor.js')}}"></script>
<script src="{{publicAsset('ckeditor/samples/js/sample.js')}}"></script>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>



<script src="{{publicAsset('js/jquery.validate.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="{{publicAsset('js/custom_validation.js')}}"></script>
<script src="{{publicAsset('slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{publicAsset('metisMenu/dist/metisMenu.min.js')}}"></script>
<script src="{{publicAsset('js/icheck.min.js')}}"></script>
<script src="{{publicAsset('js/index.js')}}"></script>

<script src="{{publicAsset('js/sweet-alert.min.js')}}"></script>
<script src="{{publicAsset('js/toastr.min.js')}}"></script>
<script src="{{publicAsset('js/custom.js')}}"></script>

<!-- App scripts -->
<script src="{{publicAsset('scripts/homer.js')}}"></script>
<script>
    $(window).on('load',()=>{
        $('#site').removeClass('hidden')
        $('#siteLoader').addClass('hidden')
    })
    
    var url="{!! url('') !!}";
    $(document).ready(function(){
        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-top-center",
            "closeButton": true,
            "toastClass": "animated fadeInDown",
        };
        @if(Session::has('success'))
            toastr.success("{{Session::get('success')}}");
        @endif
        @if(Session::has('error'))
            toastr.error("{{Session::get('error')}}");
        @endif
    });

    $(document).on("change",".course",function(){
        changeCourse($(this).val());
    });

    $(document).on("change",".subject",function(){
        changeSubject($(this).val());
    });
    $(document).on("change",".chapter",function(){
        changeChapter($(this).val());
    });
</script>
</body>

<!-- Mirrored from webapplayers.com/homer_admin-v1.9.1/tables_design.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 06 Mar 2017 08:50:43 GMT -->
</html>

