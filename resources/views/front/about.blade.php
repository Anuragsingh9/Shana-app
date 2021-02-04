@extends('layouts.front')

@section('content')
<!-- <div id="loaderssd">
    <img src="{{ asset('css/images/loader.gif') }}" alt="Loader">
</div> -->

<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Page Title</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>


<div class="about-sec">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				{{-- <img src="{{ asset('css/images/taxture.jpg') }}"> --}}
				{{-- <h1 id="title"></h1> --}}
				<p id="contain">	</p>
			</div>
		</div>
	</div>
</div>
@push('script')
<script>
	
	 path=window.location.href;
    var id=path.substr(path.lastIndexOf('/') + 1);
    		if(id=='privacy-policy')
    		{
    			var data={type:'policy'};
    		}
    		else if(id=='contact-us')
    		{
    			var data={type:'contact'};
    		}
    		else if(id=='term-policy') 
    		{
    			var data={type:'term'};
    		}
            else if(id=='refund-policy')
            {
                var data={type:'refund'};
            }
    		else
    		{
    			var data={type:'about'};
    		}
 var res=apiAjaxCall('site-details','POST',data)
      var list_title2='';
     res.then(data => {
     			//$("#title").html(data.data.title)
     			$(".page_title").html(data.data.title)
     			$("#contain").html(data.data.content_data)
     })
</script>
@endpush
@endSection
