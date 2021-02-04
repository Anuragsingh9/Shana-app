@extends('layouts.front')

@section('content')
<div id="loader">
    <img src="{{ publicAsset('css/images/loader.gif') }}" alt="Loader">
</div>
<div id="dataList" class="hidden">
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Subject Course Page Title</h1>
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
        {{-- <div class="sub_data_row">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-5 sub_course_chapter_title">
                                                    <a href="#">
                                                        <div class="sub_course_img">
                                                            <div class="sub_course_icon" style="background: url('{{ asset('css/images/math.png') }}') no-repeat center"></div>
                                                        </div>
                                                        <div class="about_course_data">
                                                            <h4>Maths</h4>
                                                            <!-- <div class="complete_status">
                                                                0 of 50 complete
                                                            </div> -->
                                                            <div class="description_about">
                                                                Learn early elementary math—counting, shapes, basic addition and subtraction, and more.
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-xs-12 col-sm-7">
                                                    <!--  -->
                                                    <div class="subjectSlider">
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Counting</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Addition and subtraction intro</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Place value (tens and hundreds)</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Addition and subtraction within 20</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Addition and subtraction within 100</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Addition and subtraction within 1000</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Measurement and data</a>
                                                        </div>
                                                        <div class="item">
                                                            <a href=""><img src="{{ asset('css/images/sub_icon2.jpg') }}" class="img-responsive">Geometry</a>
                                                        </div>
                                                    </div>
                                                    <a href="" class="all_sub_view">View All</a>
                                                </div>
                                            </div>
                                        </div> --}}
    </div>
</div>
</div>
@push('script')
<script>
    @if(Auth::check())
    jQuery('.subjectSlider').owlCarousel({
  loop: true,
  items:4,
  dots: false,
  nav: true,
  margin: 10,
  singleItem:true,
    responsive:{
        0:{
          items:2
        },
        480:{
          items:3
        },
        768:{
          items:2
        },
        1000:{
          items:3
        },
        1400:{
          items:4
        }
    }
});

     path=window.location.href;
    var id=path.substr(path.lastIndexOf('/') + 1);
    data={subject_id:id}
           var res=apiAjaxCall('chapter','POST',data)
            var list_title='';
           var list_item='';
            res.then(data => {
                 $('.banner_section').css('background-color', '#'+data.data.subject_detail.color);
                 $('.page_title').html(data.data.subject_detail.name);
                 data.data.data.map(function(e,index){
                    var url='';
                            if(e.what_next=='Topic')
                            {
                                url="{{ url('topic') }}"+'/'+e.id;
                            }
                            else
                            {
                                localStorage.setItem('document_' + e.id, 'Chapter');
                               url= "{{ url('multi-documents') }}"+'/'+e.id;
                            }
                        list_title+='<div class="sub_data_row">\
            <div class="row">\
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 sub_course_chapter_title">\
                    <a href="'+url+'">\
                        <div class="sub_course_img">\
                            <div class="sub_course_icon" style="background: url('+e.image+') no-repeat center"></div>\
                        </div>\
                        <div class="about_course_data">\
                            <h4>'+e.chapter+'</h4>\
                            <!-- <div class="complete_status">\
                                0 of 50 complete\
                            </div> -->\
                        </div>\
                    </a>\
                </div>'
                //<div class="col-xs-12 col-sm-7">\
                    // <div class="subjectSlider" id="'+e.id+'">\
                    // </div>\
                list_title+='</div>\
    </div>\
</div>';
                    $('.page_main_sec').find('.container').append(list_title);
                    list_title='';
                    // if(e.what_next=='Topic')
                    // {       var item='';
                    //         data={chapter_id:e.id,user_id:'{{ Auth::user()->id }}'}
                    //     var res=apiAjaxCall('topic','POST',data)
                    //      res.then(data => {
                    //         console.log(data)
                    //         data.data.data.map(function(e){
                    //         localStorage.setItem('document_'+e.id,'chapter' );
                    //          item+='<div class="item">\
                    //         <a href="'+'{{ url("multi-documents") }}/'+e.id+'"><img src="'+e.image+'" class="img-responsive">'+e.topic+'</a>\
                    //     </div>'
                    //         })
                    //        // $('#'+data.data.chapter_detail.id).html(item);

                    //      })
                    // }

                 })

            })
            $(document).ajaxStop(function () {
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
@endsection