@extends('layouts.front')

@section('content')
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Subscription Plans</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>

<div class="page_upper_text">
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-md-offset-2">
                <h2>Subscription Plans - Get All Course Access For You And Your Family</h2>
                <p>Subscribe to plan for a year at throw away prices and access all the courses. Select the best plan as per your requirements.</p>
            </div>
        </div>
    </div>
</div>

<div class="page_main_sec">
    <div class="container">
        <div class="row">
            <div class="packs-container plans-data">
                {{--<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="plan-pack planbox">
                        <div class="price-details">
                            <div class="plan-head tb-gradient-1">
                                <!-- save <i class="fa fa-inr" aria-hidden="true"></i><span>101</span> -->
                                <i class="tb-icon tb-rupee font-sm align-baseline"></i>
                            </div>
                            <div class="actual_price_box">
                                <div class="real_price">
                                    <!-- <i class="fa fa-inr" aria-hidden="true"></i> 200 -->
                                </div>
                                <div class="new_price">
                                    <i class="fa fa-inr" aria-hidden="true"></i> 99
                                </div>
                                <div class="subscription_period">
                                    For 1 Month
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <h4>Shana Test Book</h4>
                            <h5>1 Month Subscription</h5>
                            <div class="plan_points">
                                <ul>
                                    <li>Unlimited Tests for All The Exams</li>
                                    <li>Hindi & English - Bilingual Tests</li>
                                    <li>Detailed Solutions and Analysis</li>
                                </ul>
                            </div>
                            <div class="subscribe_btn_block">
                                <button type="button" class="btn btn-primary">Subscribe Now</button>
                            </div>
                        </div>
                    </div>
                </div>--}}
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    {{--@if(Auth::check())--}}
	data={}
           var res=apiAjaxCall('plans-web','GET',data)
           list_plan='';
           res.then(data => {
           			data.data.map(function(e){
           			list_plan='<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 plan-pack-outer">\
                    <div class="plan-pack planbox">\
                        <div class="price-details">\
                            <div class="plan-head tb-gradient-1">\
                                <!-- save <i class="fa fa-inr" aria-hidden="true"></i><span>101</span> -->\
                                <i class="tb-icon tb-rupee font-sm align-baseline"></i>\
                            </div>\
                            <div class="actual_price_box">\
                                <div class="real_price">\
                                    <!-- <i class="fa fa-inr" aria-hidden="true"></i> 200 -->\
                                </div>\
                                <div class="new_price">\
                                    <i class="fa fa-inr" aria-hidden="true"></i> '+e.amount+'\
                                </div>\
                                <div class="subscription_period">\
                                    For '+e.duration+' '+e.field+'\
                                </div>\
                            </div>\
                        </div>\
                        <div class="content">\
                            <h4>'+e.name+'</h4>\
                            <h5>'+e.duration+' '+e.field+' Subscription</h5>\
                            <div class="plan_points">\
                                <ul>\
                                    <li>'+e.description+'</li>\
                                </ul>\
                            </div>\
                            <div class="subscribe_btn_block">\
                            \  <form action="{!!route('paywithrazorpay')!!}" method="POST" >\
                            \<input type="hidden" name="plan_id" value="'+e.id+'">\
                            \  <input type="hidden" name="_token" value="{!!csrf_token()!!}">\
                                <button type="submit" class="btn btn-primary">Subscribe Now</button>\</form>\
                            </div>\
                        </div>\
                    </div>\
                </div>';
                	$('.plans-data').append(list_plan);
                	list_plan='';
                })
        });
         {{--  @else
   $('#loginFormModal').modal({
            backdrop:'static', show: true
        })
        $('.close').on('click',function(e){
            window.location.href="{{ url('') }}";
        });
    $('#loginFormModal').modal('show');
    @endif --}}
</script>
@endpush