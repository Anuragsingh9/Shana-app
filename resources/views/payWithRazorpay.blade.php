@extends('layouts.front')

@section('content')
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Subscription</h1>
        <div class="banner-icon"><span><img src="http://www.shana.co.in/public/css/images/idea-icon2.png" class="center-block"></span></div>
    </div>
</div>
<div class="subscription-section">
    <div class="container">

        @if(Auth::check())

            <div class="subscription_get">
                @if($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Error!</strong> {{ $message }}
                    </div>
                @endif
                {!! Session::forget('error') !!}
                @if($message = Session::get('success'))
                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>Success!</strong> {{ $message }}
                    </div>
                    <script>
                        {!! Session::forget('success') !!}
                        setTimeout( function(){
                            window.location='{{url("/")}}';
                            // $('#backForm').submit();
                        }  , 5000 );
                    </script>
                    @else

                        {!! Session::forget('success') !!}
                        <div class="panel panel-default">
                            <!-- <div class="panel-heading">{{$plan->name}}</div> -->

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4"><img src="{{cloudUrl($plan->image)}}" class="img-responsive"></div>
                                    <div class="col-xs-12 col-sm-8">
                                        <h3>{{($plan->name)}}</h3>
                                        <p><!-- {{($plan->description)}} -->{{$plan->description}}</p>
                                        <form name="redirect" action="{!!route('payment')!!}" method="POST" >
                                            <!-- Note that the amount is in paise = 50 INR -->
                                            <!--amount need to be in paisa-->
                                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                    data-key="{{ Config::get('razor.razor_key') }}"
                                                    data-amount="{{($plan->amount*100)}}"
                                                    data-buttontext="Pay {{($plan->amount)}} "
                                                    data-name="{{$plan->name}}"
                                                    data-description=""
                                                    data-image="{{cloudUrl('uploads/preview_image/dummy.png')}}"
                                                    data-prefill.name=""
                                                    data-prefill.email=""
                                                    data-theme.color="#ff7529">
                                            </script>
                                            <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                                            <input type="hidden" name="order_detaill" value="{{$plan}}">
        
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif

            </div>
              @else
            <script>

  $('#loginFormModal').modal({backdrop: 'static', keyboard: false})
  $('.close').hide();
  $('#loginFormModal').modal('show');

            </script>
  @endif
    </div>
</div> 

@endsection
