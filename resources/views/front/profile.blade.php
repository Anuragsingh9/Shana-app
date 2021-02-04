
@extends('layouts.front')

@section('content')

	<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">My Profile</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>

<div class="profile-section">
	<div class="container">
			<div class="clearfix user-profile-box">

			<!--<img src="{{ cloudUrl(Auth::user()->photo) }}" class="center-block img-responsive" >-->
					@if(filter_var(Auth::user()->photo, FILTER_VALIDATE_URL))
					<div class="user_profiel_image" style="background-image: url('{{ Auth::user()->photo }}');"></div>
{{--					{{dd("okss")}}--}}

				@else
					<div class="user_profiel_image" style="background-image: url('{{ Auth::user()->photo }}');"></div>
				@endif

				<div class="username">{{Auth::user()->name}}</div>

				<div class="user-profile-data-row">
					<!--<div class="profile-detail-row">-->
					<!--	<div class="name"><i class="fa fa-graduation-cap"></i>Name</div>-->
					<!--	<div class="profile-name profile-data">{{Auth::user()->name}}</div>-->
					<!--</div>-->
					<div class="profile-detail-row">
						<div class="name"><i class="fa fa-tablet"></i>Mobile</div>
						<div class="profile-email profile-data">{{Auth::user()->mobile}}</div>
					</div>
					<div class="profile-detail-row">
						<div class="name"><i class="fa fa-envelope-o"></i>Email</div>
						<div class="profile-password profile-data">@if(!empty(Auth::user()->email)){{Auth::user()->email}} @else {{'N/A'}}@endif</div>
					</div>
					<div class="profile-detail-row">
						<div class="name"><i class="fa fa-university"></i>Institiue</div>
						<div class="profile-password profile-data">@if(!empty(Auth::user()->institute)){{Auth::user()->institute}} @else {{'N/A'}}@endif</div>
					</div>

					<div class="profile-detail-row">
						<div class="name"><i class="fa fa-code"></i>Referral Code</div>
						<div class="profile-refferal profile-data">{{Auth::user()->self_ref_code}}</div>
					</div>

					<div class="profile-detail-row">
						<div class="name"><i class="fa fa-magic"></i>Active Plan</div>
						<div class="profile-plan profile-data">{{getUserPlan(Auth::user()->id)}}</div>
					</div>
				</div>
			</div>
	</div>
</div>


@endsection