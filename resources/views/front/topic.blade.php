@extends('layouts.front')

@section('content')
<div id="loader">
    <img src="{{ publicAsset('css/images/loader.gif') }}" alt="Loader">
</div>
<div id="dataList" class="hidden">
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Page Title</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>
{{-- <div class="page_upper_text">
    <div class="container">
        <div class="row text-center">
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                                                <h2>Want a personalized The World of Math experience?</h2>
                                                <p>Missions recommend what to learn next, help you remember what you’ve learned by mixing skills, and save your progress.</p>
                                            </div>
                                        </div> 
    </div>
</div> --}}
<div class="page_main_sec">
    <div class="container">
        {{-- <div class="row sub_data_row">
                                            <div class="col-xs-12 col-sm-12 col-md-5 sub_course_chapter_icon">
                                                <a href="">
                                                    <div class="sub_course_img">
                                                        <div class="sub_course_icon" style="background: url('{{ asset('css/images/math.png')}}') no-repeat center"></div>
                                                    </div>
                                                    <div class="about_course_data">
                                                        <h4>IAS</h4>
                                                        <div class="description_about">
                                                            Learn early elementary math—counting, shapes, basic addition and subtraction, and more.
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-7">
                                                <div class="list_view">
                                                    <ul>
                                                        <li><a href="">Counting</a></li>
                                                        <li><a href="">Addition and subtraction intro</a></li>
                                                        <li><a href="">Place value (tens and hundreds)</a></li>
                                                        <li><a href="">Addition and subtraction within 20</a></li>
                                                        <li><a href="">Addition and subtraction within 100</a></li>
                                                        <li><a href="">Addition and subtraction within 1000</a></li>
                                                        <li><a href="">Measurement and data</a></li>
                                                        <li><a href="">Geometry</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div> --}}
        
    </div>
</div>
</div>
@push('script')
<script>
    @if(Auth::check())
        path=window.location.href;
    var id=path.substr(path.lastIndexOf('/') + 1);
    data={chapter_id:id,user_id:'{{Auth::user()->id}}'}
           var res=apiAjaxCall('topic','POST',data)
           var list_title='';
           var list_item='';
           res.then(data => {
                        
                 $('.banner_section').css('background-color', '#'+data.data.chapter_detail.color); 
                 $('.page_title').html(data.data.chapter_detail.name); 
                 data.data.data.map(function(value,index){
                    
                    var url='';
                    var topic_url='';
                    var item='';
                    var list_title='';
                    topic_url="{{ url('multi-documents') }}"+'/'+value.id;
                    localStorage.setItem(value.id,value.topic );
                    value.document.map(function(e,index){
                        url="{{ url('documents') }}"+'/'+e.id;
                             item+='<li><a href="'+url+'">'+e.text+'</a></li>'
                            }) 
                        list_title+=' <div class="row sub_data_row"> <div class="col-xs-12 col-sm-12 col-md-5 sub_course_chapter_icon">\
                         <a href="'+topic_url+'" class="rav">\
                                <div class="sub_course_img">\
                                    <div class="sub_course_icon" style="background: url('+value.image+') no-repeat center"></div>\
                                    </div>\
                                <div class="about_course_data">\
                                    <h4>'+value.topic+'</h4>\
                                    <div class="description_about">\
                                    </div>\
                                </div>\
                        </a>\
                    </div><div class="col-xs-12 col-sm-12 col-md-7">\
                                    <div class="list_view">\
                                        <ul>'+item+'</ul>\
                                    </div>\
                                    </div>';
                            $('.page_main_sec').find('.container').append(list_title);
                    }) 
                    $('#dataList').removeClass('hidden');
                    $('#loader').addClass('hidden');     
                });
           @else
   
    jQuery(document).ready(function($) {
        $('#loginFormModal').modal({
            backdrop:'static', show: true
        })
        $('.close').on('click',function(e){
            window.location.href="{{ url('') }}";
        });
        $('#loginFormModal').modal('show');  
    });
    @endif 
    </script>
    @endpush
    
@endSection