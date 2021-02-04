<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>var BASE_URL='{{url('')}}'</script>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ publicAsset('images/favicon-32x32.png')}}"/>

    <!-- Styles -->
    {{--<link href="{{ publicAsset('css/app.css') }}" rel="stylesheet">--}}
    <link href="{{ publicAsset('css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ publicAsset('css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ publicAsset('css/owlcarousel-theme.css') }}">
    <link rel="stylesheet" href="{{publicAsset('css/sweet-alert.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ publicAsset('css/font-awesome.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
    <link href="{{ publicAsset('css/style.css') }}" rel="stylesheet">
    <link href="{{ publicAsset('css/media.css') }}" rel="stylesheet">
   {{--  <script src="{{ publicAsset('js/jquery-1.11.2.min.js') }}"></script> --}}
     <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
    <script src="{{ publicAsset('js/fancybox.js') }}"></script>
    <script src="{{ publicAsset('js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
    <script src="{{ publicAsset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ publicAsset('js/smoothscroll.js') }}"></script>
    <script src="{{ publicAsset('js/apiCall.js') }}"></script>
    <script src="{{ publicAsset('js/script.js') }}"></script>
    <script src="{{publicAsset('js/sweet-alert.min.js')}}"></script>

</head>

<body oncontextmenu="return false" >
    <header>
    <div class="container">
        <div class="row">
            <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3 logo">
                <a href="{{ url('/') }}" class="">
                    <img src="{{ publicAsset('css/images/kcpic.jpg') }}" class="img-responsive logo-kc-pic">
                    <!--<h1><span class="logo_txt1">S</span><span class="logo_txt2">H</span><span class="logo_txt3">A</span><span class="logo_txt4">N</span><span class="logo_txt5">A</span></h1>-->
                    <img src="{{publicAsset('images/shana-text-logo.png')}}" class="img-responsive logo-text"/>
                </a>
            </div>
            <div class="visible-xs col-xs-7 col-sm-9 col-md-9 col-lg-9">
                <form id="search_form_mm" class="visible-xs">
                    <button type="button" class="search_btn">
                            <svg role="img" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 10 10">
                                <path fill="#FFFFFF" d="M7.73732912,6.67985439 C7.75204857,6.69246326 7.76639529,
    6.70573509 7.7803301,6.7196699 L9.65165045,8.59099025 C9.94454365,
    8.8838835 9.94454365,9.3587572 9.65165045,9.65165045 C9.3587572,
    9.94454365 8.8838835,9.94454365 8.59099025,9.65165045 L6.7196699,
    7.7803301 C6.70573509,7.76639529 6.69246326,7.75204857 6.67985439,
    7.73732912 C5.99121283,8.21804812 5.15353311,8.5 4.25,8.5 C1.90278981,
    8.5 0,6.59721019 0,4.25 C0,1.90278981 1.90278981,0 4.25,0 C6.59721019,
    0 8.5,1.90278981 8.5,4.25 C8.5,5.15353311 8.21804812,5.99121283
    7.73732912,6.67985439 L7.73732912,6.67985439 Z M4.25,7.5 C6.04492544,
    7.5 7.5,6.04492544 7.5,4.25 C7.5,2.45507456 6.04492544,1 4.25,1
    C2.45507456,1 1,2.45507456 1,4.25 C1,6.04492544 2.45507456,7.5 4.25,
    7.5 L4.25,7.5 Z"></path>
                            </svg>
                    </button>
                    <div class="search_input">
                        <input type="search" name="search" class="search-temp" placeholder="Search" title=" Must be 3 charactor">
                    </div>
                </form>
            </div>
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 main-menu">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-menu">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="header-menu">
                        <ul class="nav navbar-nav navbar-right">
                            <!-- <li><a href="index.html">Home</a></li>
                            <li><a href="#">About</a></li> -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton">Courses</a>
                                <ul class="sub-menu dropdown-menu course-header " aria-labelledby="dropdownMenuButton">
                                    {{--<li>
                                        <div class="menuChildBox">
                                            <a href="">Math by Subject</a>
                                            <ul class="child-submenu">
                                                <li><a href="#">Early math</a></li>
                                                <li><a href="#">Arithmetic</a></li>
                                                <li><a href="#">Pre-algebra</a></li>
                                                <li><a href="#">Algebra 1</a></li>
                                                <li><a href="#">Geometry</a></li>
                                                <li><a href="#">Trigonometry</a></li>
                                                <li><a href="#">Calculus</a></li>
                                                <li><a href="#">Multivariable calculus</a></li>
                                                <li><a href="#">Early math</a></li>
                                                <li><a href="#">Arithmetic</a></li>
                                                <li><a href="#">Pre-algebra</a></li>
                                                <li><a href="#">Algebra 1</a></li>
                                                <li><a href="#">Geometry</a></li>
                                            </ul>
                                        </div>
                                    </li>--}}

                                </ul>
                            </li>
                            <li>
                                <a href="{{ url('plans') }}">Plans</a>
                            </li>
                            <!--<li><a href="{{ url('inspire') }}">Inspire Me</a></li>-->
                             <li>
                                <a href="{{ url('blog') }}">Blog</a>
                            </li>
                            <!--<li><a href="{{ url('event') }}">Events</a></li>-->
                             @if(Auth::check())
                                <li class="dropdown hidden-xs">
                                    @if (filter_var(Auth::user()->photo, FILTER_VALIDATE_URL)) 
				                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="userMenuButton">
                                            <span style="background-image: url('{{(!empty(Auth::user()->photo)?Auth::user()->photo:publicAsset('css/images/no-image.jpg'))  }}');"></span>
                                        </a>
				                    @else
					                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="userMenuButton">
                                            <span style="background-image: url('{{(!empty(Auth::user()->photo)?cloudUrl(Auth::user()->photo):publicAsset('css/images/no-image.jpg'))  }}');"></span>
                                        </a>
				                    @endif
                                    
                                    <ul class="sub-menu dropdown-menu logout" aria-labelledby="userMenuButton">
                                        <li><a href="{{ url('profile') }}">Profile</a></li>
                                        <li class="logouts"><a href="{{ url('customOut') }}">Logout</a> </li>
                                    </ul>
                                </li>
                                <li class="visible-xs"><a href="{{ url('profile') }}">Profile</a></li>
                                <li class="logouts visible-xs"><a href="{{ url('customOut') }}">Logout</a> </li>
                             @else
                                <li class="loginMenu"><a href="#login" data-toggle="modal" data-target="#loginFormModal">Login</a></li>
                                <li class="signupMenu"><a href="{{ url('/signUp') }}">Sign Up</a></li>
                       @endif
                        </ul>
                        <form id="search_form" class="hidden-xs">
                            <button type="button" class="search_btn">
                                <svg role="img" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 10 10">
                                    <path fill="#FFFFFF" d="M7.73732912,6.67985439 C7.75204857,6.69246326 7.76639529,
        6.70573509 7.7803301,6.7196699 L9.65165045,8.59099025 C9.94454365,
        8.8838835 9.94454365,9.3587572 9.65165045,9.65165045 C9.3587572,
        9.94454365 8.8838835,9.94454365 8.59099025,9.65165045 L6.7196699,
        7.7803301 C6.70573509,7.76639529 6.69246326,7.75204857 6.67985439,
        7.73732912 C5.99121283,8.21804812 5.15353311,8.5 4.25,8.5 C1.90278981,
        8.5 0,6.59721019 0,4.25 C0,1.90278981 1.90278981,0 4.25,0 C6.59721019,
        0 8.5,1.90278981 8.5,4.25 C8.5,5.15353311 8.21804812,5.99121283
        7.73732912,6.67985439 L7.73732912,6.67985439 Z M4.25,7.5 C6.04492544,
        7.5 7.5,6.04492544 7.5,4.25 C7.5,2.45507456 6.04492544,1 4.25,1
        C2.45507456,1 1,2.45507456 1,4.25 C1,6.04492544 2.45507456,7.5 4.25,
        7.5 L4.25,7.5 Z"></path>
                                </svg>
                            </button>
                            <div class="search_input">
                                <input type="search" name="search" class="search-temp" placeholder="Search" id="search_text" title="Must be 3 charactor"> 
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="loginFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Login to Shana</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="clearfix">
            <div class="row">
                <div class="col-xs-12 tile">
                    <div class="tile-second">
                        <form name="FormLogin" onsubmit="Login()" id="FormLogin" class="">
                            <div class="form-group clearfix mb-0">
                                <a href="{{ url('socialLogin') }}/facebook/" class="btn btn-facebook">Facebook</a>
                                <a href="{{ url('socialLogin') }}/google/" class="btn btn-google">Google</a>
                            </div>
                            <div class="tb-separator" data-prompt="OR"></div>
                            <div class="form-group">
                                <input class="form-control" name="mobile" type="text" placeholder="Email or Mobile">
                                <label class="error" id="mobile_error"></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="pswd" placeholder="Password">
                            </div>
                             <label class="error" id="login_error"></label>
                            <div class="form-group text-left">
                                <a href="" class="forgetBtn btn btn-xs btn-link">Forgot Password?</a>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick="Login()" class="btn btn-gray-dark btn-block">Login</button>
                            </div>
                            <p class="text-center needAccount">Need an account?
                                <a class="btn btn-sm btn-link" ng-click="tileNo = 3" href="{{ url('/signUp') }}">Sign Up</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="page-main-wrap" style="min-height: 600px">
        @yield('content')
</div>

<footer>
    <div class="container">
        <div class="col-xs-12 col-sm-4 footer_para">
            <img src="{{ publicAsset('images/shanatext-footerlogo.png') }}" class="img-responsive">
            <ul>
                <li class="address">Shana International school, Jaipur - Ganganagar by pass Near Udasar Crossing Bikaner Raj.</li>
                <li class="mail">learnomatrix@gmail.com</li>
                <li class="phone">+91-9166022555 </li>
             <!--    <li class="">+91-9783058504</li>
                <li class="">+91-9829058584</li> -->
            </ul>
        </div>
        <div class="col-xs-12 col-sm-8 footer-menu-sec">
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3 footer-menu">
                <h3>Menu</h3>
                <ul>
                    <li><a href="{{url('/')}}">Home</a></li>
                    <li><a href="{{url('about-us')}}">About Us</a></li>
                    <li><a href="{{url('term-policy')}}">Term & Conditions</a></li>
                    <li><a href="{{url('privacy-policy')}}">Privacy & Policy</a></li>
                    <li><a href="{{url('refund-policy')}}">Refund & Cancellation</a></li>
                   {{--  <li><a href="{{url('contact-us')}}">Contact Us</a></li> --}}

                </ul>
            </div>
            <div class="hidden-xs col-sm-8 col-md-6 col-lg-6 footer-menu" id="footer">
                <h3>Course</h3>
                <ul >
                    {{--<li><a href="{{url('/')}}">Home</a></li>
                    <li><a href="{{url('about-us')}}">About Us</a></li>
                    <li><a href="{{url('term-policy')}}">Term & Conditions</a></li>
                    <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                    <li><a href="{{url('refund-policy')}}">Refund & Cancellation</a></li>
                    <li><a href="{{url('contact-us')}}">Contact Us</a></li>--}}

                </ul>
                </div>
                
            
            <div class="col-sm-4 col-md-3 footer-social">
                <h3>Follow us:</h3>
                <ul>
                    <li class="facebook">
                        <a href="https://www.facebook.com/shanakids/info?tab=overview">
                        <img src="{{ publicAsset('css/images/facebook-logo.png') }}"></a>
                    </li>
                    <li class="twitter">
                        <a href="https://twitter.com/">
                        <img src="{{ publicAsset('css/images/twitter.png') }}"></a>
                    </li>
                    <li class="insta">
                        <a href="">
                        <img src="{{ publicAsset('css/images/instagram-logo.png') }}"></a>
                    </li>
                 <!--    <li class="google">
                        <img src="{{ publicAsset('css/images/google-plus-logo.png') }}">
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
    <div class="container copyright">
        <div class="col-xs-12 col-sm-6 pull-left">
            &copy; 2018 Shana. All Right Reserved.
        </div>
        <div class="col-xs-12 col-sm-6 text-right design-sec">
            Design & Developed by:<a href="https://www.pebibits.com/" target="_blank"> Pebibits Technologies Pvt. Ltd.</a>
        </div>
    </div>
</footer>

   <script>
        $(document).ready(function() {
            document.addEventListener('contextmenu', event => event.preventDefault());
        });
        
     
     @if(Auth::check())
    var user_id={{ Auth::user()->id }};
    @else
    var user_id=0;
    @endif
    data={user_id:user_id}
     var res=apiAjaxCall('front-home','POST',data)
      var list_title2='';
      var footer='<h3>Course</h3>\
                <ul>';
     res.then(data => {
                data.data.course.map(function(value,index){
                   var murl=""
                   if (value.what_next == "document") {
                       murl='//www.shana.co.in/multi-documents/'+value.id
                   }
                   else if(value.what_next == "subject"){
                         murl='//www.shana.co.in/courses/'+value.id
                   }
                     url="{{ url('courses') }}"+'/'+value.id;
                    list_title2+='<li>\
                                        <div class="menuChildBox">\
                                            <a>'+value.course+'</a>\
                                            <ul class="child-submenu">';
                        footer+='<li><a href='+murl+'>'+value.course+'</a></li>';
                        
                        value.innerItems.map(function(e){
                            if(e.type=='document')
                            {
                                sub_url="{{ url('documents') }}"+'/'+e.id;
                            }
                            else
                            {
                                sub_url=""
                                if(e.what_next=='Chapter')
                                {
                                    sub_url="{{ url('course-subjects') }}"+'/'+e.id;
                                }
                                else if(e.what_next=='document')
                                {
                                    sub_url="{{ url('multi-documents') }}"+'/'+e.id;
                                }
                            }
                            list_title2+='<li><a href="'+sub_url+'">'+e.title+'</a></li>';
                        })
                            list_title2+='</ul>\
                                        </div>\
                                    </li>';
                })
                $('.course-header').html(list_title2)
                footer+='</ul>'
               
                $('#footer').html(footer);
     })
     
       function Login()
       {
        $('#FormLogin').validate({
            rules: {
                mobile: {
                    required: true,
                    minlength:10,
                    maxlength:10,
                    number: true,
                },
                pswd: {
                    required: true,
                },
            },
            messages: {
                mobile:
                {
                   required:"Please Enter Mobile Number",
                   minlength:"Mobile Number Must Be valid",
                   maxlength:"Mobile Number Must Be valid",
                   number:"Please Enter valid Mobile Number"
                },
                pswd: {
                    required:"Enter Password.",
            }
        }
        });

        if ((!$('#FormLogin').valid())) {
            return false;
        }
        if (($('#FormLogin').valid())) {

         var user_mobile=$('input[name=mobile]').val();
         var user_password=$('input[name=pswd]').val();
         data={mobile:user_mobile,password:user_password}
           var res=apiAjaxCall('user-login','POST',data)
           res.then(data => {
                   
                   if(data.auth)
                   {
                      $('#mobile_error').hide()
                      window.location.href=BASE_URL+data.intended;
                   }
                   else
                   {
                    if(data.msg)
                    {
                    $('#mobile_error').html(data.msg)
                    $('#mobile_error').show()
                    }
                    if(data.message)
                    {
                       $('#login_error').html(data.message)
                       $('#login_error').show()
                    }
                   }
                });
       }

       }
       $('#FormLogin input').keydown(function(e) {
    if (e.keyCode == 13) {
       Login();
    }
});
        $('#search_form').on('submit',function(event) {
            event.preventDefault();
            var search =$('#search_text').val();
            console.log(search.length)
            if(search.length<3)
            {
                swal("Whoops", "Search String must be 3 Character long!! ", "info");
            }
            else
            {
                localStorage.setItem('search',search );
                 window.location.href=BASE_URL+'/search';
            }
       });

        // jQuery(document).ready(function() {
        //     jQuery('.search_btn').click(function() {
        //         jQuery('.search-temp').toggle();
        //     });
        // });

        jQuery(document).ready(function(){
            jQuery(".search_btn").click(function(){
                jQuery(".search-temp").css({"opacity":"1"},400);
                jQuery(".search_input").css({"opacity":"1"},400);
                jQuery(".search_input").toggle(200);
                // jQuery(".search-temp").show(5000);
                // jQuery(".search-temp").animate({width: '100%'},500);
            });
            return true;
        });
    


   </script>
@stack('script')
</body>
</html>
