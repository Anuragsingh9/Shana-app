@extends('layouts.front')

@section('content')
<div id="loader">
    <img src="{{ publicAsset('css/images/loader.gif') }}" alt="Loader">
</div>
<div id="dataList" class="hidden">
    <div id="homeSlider" class="owl-carousel">
        {{--<div class="item">
            <div class="sliderBg" style="background: url('images/slider.jpg') no-repeat center;">
                <div class="container">
                    <div class="col-xs-6 sliderContent whiteText">
                        <div class="slideText">
                            <a class="e_learn_btn">E-Learning Solutions</a>
                            <h1>Complete Solutions For Your Education Needs</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="sliderBg" style="background: url('images/slider.jpg') no-repeat center;">
                <div class="container">
                    <div class="col-xs-6 sliderContent whiteText">
                        <div class="slideText">
                            <a class="e_learn_btn">E-Learning Solutions</a>
                            <h1>Complete Solutions For Your Education Needs</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div>

   <div class="menulist_section">
    <div class="mainCoursesName">
        <div class="container">
            <ul id="course">
               {{--  <li><a href="#sec1">Maths</a></li>
                                                                            <li><a href="#sec2">English</a></li>
                                                                            <li><a href="#sec3">Perosnality Development</a></li>
                                                                            <li><a href="#sec4">Science</a></li>
                                                                            <li><a href="#sec5">Memory Enhancement</a></li>
                                                                            <li><a href="#sec6">Reasoning</a></li>
                                                                            <li><a href="#sec7">General Knowledge</a></li>
                                                                            <li><a href="#sec8">Motivational</a></li> --}}
            </ul>
        </div>
    </div>
    <!-- <div class="div_on_scroll clearfix"></div> -->
    <div class="clearfix courseNameList">
        <div class="container">
            
        </div>
    </div>
</div>

    <div class="app_section">
        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-md-offset-1 col-lg-offset-1">
                <div class="col-xs-12 col-sm-6">
                    <img src="{{publicAsset('css/images/background-mobile.jpg')}}" class="img-responsive center-block">
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="app-details">
                        <div class="app-content">
                            <h2 class="title">DOWNLOAD SHANA APP</h2>
                            <p class="text">Learnomatrix is a systematic, scientific, effective and an empowering tool which enables every learner to realize his/her complete potential and empowers them to fulfill their dream in cracking any sort of competitive Exams.</p>
                            <div class="get-apps-link">
                                <div class="input-state">
                                    <form id="sendLink">
                                    <input id="mobileForAppLink" class="form-control" type="text" maxlength="10" placeholder="ENTER PHONE NUMBER">
                                   
                                    <button type="submit" class="btn send-sms">GET LINK</button>
                                     <div class="text-danger" id=linkMsg></div>
                                </form>
                                </div>
                            </div>
                            <div class="separator">
                                <span class="or-spacer">or</span>
                            </div>
                            <div class="play-store-url">
                                <a class="app-link" href="https://play.google.com/store/apps/details?id=com.shana&hl=en" target="_blank">
                                    <img class="img-responsive" alt="KC Pathshala App on Google Playstore" src="{{publicAsset('images/google-play-badge.png')}}">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- dd(Auth::user()) --}}
@endsection

@push('script')
<script>
$('#sendLink').on('submit',function(e){
    e.preventDefault();
    var mob = /^[1-9]{1}[0-9]{9}$/;
    if (mob.test($('#mobileForAppLink').val()) == false) {
       $('#linkMsg').html('Please enter valid mobile number.')
       $('mobileForAppLink').addClass('has-error')
    }
    else{
        var mobile=$('#mobileForAppLink').val()
         $('#linkMsg').html('')
         $('mobileForAppLink').removeClass('has-error')
         var data={'mobile':mobile}
          var res=apiAjaxCall('get-link','POST',data)
          res.then(data => {
            if(data.status==true){
               $('#mobileForAppLink').val('') 
               $('#linkMsg').html('Link send to your Mobile successfully.')
            }
            else{
               $('#linkMsg').html('Woops! Something Went Wrong.') 
            }
          })
    }
})
    var url="{{ url('') }}";
    @if(Auth::check())
    var user_id={{ Auth::user()->id }};
    @else
    var user_id=0;
    @endif
    gethomeSlider('front-home','POST',user_id,url);
    jQuery(document).ready(function() {
        // body...
        // home page Course Toggle Menu on mobile view
        if(window.innerWidth<=767){
          $(document).on('click','.list_course_name',function(){
            var current_menu = $(this).next();
            if(current_menu.is(':hidden')){
                $('.list_course_name').parent().find('.CourseSyllabus').slideUp();
                $(this).parent().find('.CourseSyllabus').slideDown();
            }else{
                $(this).parent().find('.CourseSyllabus').slideUp();
            }
          });
        }
        
    });
</script>
@endpush
