@extends('layouts.front')

@section('content')
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Subscription</h1>
    </div>
</div>
<div class="subscription-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
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
                @endif
                {!! Session::forget('success') !!}

                <div class="panel panel-default">
                    <div class="panel-heading">{{isset($subject->subject)?strtoupper($subject->subject):strtoupper($course->course)}}</div>

                    <div class="panel-body">
                        <div class="col-sm-4"><img src="{{cloudUrl(isset($subject->image)?$subject->image:$course->image)}}"></div>
                        <div class="col-sm-8"><h3>{{isset($subject->subject)?strtoupper($subject->subject):strtoupper($course->course)}}</h3>
                               {{-- <p>Archis Enviro Pvt. Ltd formally know as Archis Enviro Pvt. Ltd provides Turnkey EPC Solar Energy Solutions from Concept to Commissioning for solar PV Installation Services of Rooftop & Ground Mounted throughout the Project</p>--}}
                        </div>
                        <div class="col-sm-12 text-center">
                        <form name="redirect" action="{!!route('subjectCoursePayment')!!}" method="POST" >
                            <!-- Note that the amount is in paise = 50 INR -->
                            <!--amount need to be in paisa-->
                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                    data-key="{{ Config::get('razor.razor_key') }}"
                                    data-amount="{{isset($subject->amount)?($subject->amount*100):($course->amount*100)}}"
                                    data-buttontext="Pay {{isset($subject->amount)?($subject->amount):($course->amount)}} INR"
                                    data-name="{{isset($subject->subject)?strtoupper($subject->subject):strtoupper($course->course)}}"
                                    data-description=""
                                    data-image="{{cloudUrl('uploads/preview_image/dummy.png')}}"
                                    data-prefill.name=""
                                    data-prefill.email=""
                                    data-theme.color="#{{ isset($subject->subject)?'85985':strtoupper($course->color) }}">
                            </script>
                            <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                             <input type="hidden" name="order_detaill" value="{{isset($course)?$course:$subject}}">
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
 
<style type="text/css">
.subscription-section{
    padding: 60px 0px;
}
.panel-default>.panel-heading {
    color: #fff;
    background-color: #d03e3e;
    border-color: #ddd;
    text-align: center;
    font-size: 24px;
}
.panel {
    margin-bottom: 20px;
    background-color: transparent;
    border: none;
    border-radius: 4px;
    -webkit-box-shadow: none;
    box-shadow: none;
}
input[type="submit"] {
    background: green;
    font-size: 20px;
    margin-top: 30px;
    padding: 7px 20px;
    color: #fff;
}
input[type="submit"]:hover{
    background: #169c16;
}
.subscription-section img{
    float: left;
    width: 200px;
    height: auto;
}
.subscription-section h3{
    display: inline-block;
    color: #115f70;
    margin-top: 0;
}
.subscription-section p{
    line-height: 28px;
}
.panel-body{
    border: 1px solid #f3f3f3;
    box-shadow: 0 1px 1px rgba(0,0,0,0.05);
}
</style> 

@endsection