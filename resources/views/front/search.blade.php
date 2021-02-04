@extends('layouts.front')

@section('content')
		<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Page Title</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>
{{-- <div class="page_upper_text">
    <div class="container">
    </div>
</div> --}}
<div class="page_main_sec">
    <div class="container">
		 <div class="row" id="search-list">
								
    								</div>
								</div>
							</div>
@endsection
@push('script')
 <script>
	 @if(Auth::check())
	data={search:localStorage.getItem('search')}
           var res=apiAjaxCall('search','POST',data)
           list_item='';
           res.then(data => {
           	$('.page_title').html('Searching is "'+localStorage.getItem('search')+'"')
           	       		if(data.data.chapter.length>0)
                   		{
                   			list_item+='<div class="col-sm-12 page-search"><div class="">\
                   					<span>chapter</span>\
                   					\
                   					</div>\
                   					<ul>';
                   				data.data.chapter.map(function(value,index){
                   					if(value.what_next!="Nothing")
                   							{
                   								list_item+='<a href="{{ url("topic") }}/'+value.id+'"><li>'+value.title+'</li></a>';		
                   							}
                   							else
                   							{
                   								list_item+='<li>'+value.title+'</li>';	
                   							}		
                   				})	
                   			list_item+='</ul>\
                   						  </div>';
                   		}
                   		else if(data.data.course.length>0)
                   		{
                   			list_item+='<div class="col-sm-12 page-search"><div class="">\
                   					<span>course</span>\
                   					\
                   					</div>\
                   					<ul>';
                   				data.data.course.map(function(value,index){
                   					if(value.what_next!="Nothing")
                   							{
                   								list_item+='<a href="{{ url("courses") }}/'+value.id+'"><li>'+value.title+'</li></a>';		
                   							}
                   							else
                   							{
                   								list_item+='<li>'+value.title+'</li>';	
                   							}	
                   				})	
                   			list_item+='</ul>\
                   						  </div>';
                   		}
                   		else if(data.data.document.length>0)
                   		{
                   			list_item+='<div class="col-sm-12 page-search"><div class="">\
                   					<span>document</span>\
                   					\
                   					</div>\
                   					<ul>';
                   				data.data.document.map(function(value,index){
                   					if(value.what_next!="Nothing")
                   							{
                   								list_item+='<a href="{{ url("documents") }}/'+value.id+'"><li>'+value.title+'</li></a>';		
                   							}
                   							else
                   							{
                   								list_item+='<li>'+value.title+'</li>';	
                   							}	
                   				})	
                   			list_item+='</ul>\
                   						  </div>';
                   		}
                   		else if(data.data.subject.length>0)
                   		{
                   				console.log(data.data.subject.length)
                   			list_item+='<div class="col-sm-12 page-search"><div class="">\
                   					<span>subject</span>\
                   					\
                   					</div>\
                   					<ul>';
                   				data.data.subject.map(function(value,index){
                   					console.log(value.title)
                   							if(value.what_next!="Nothing")
                   							{
                   								list_item+='<a href="{{ url("course-subjects") }}/'+value.id+'"><li>'+value.title+'</li></a>';		
                   							}
                   							else
                   							{
                   								list_item+='<li>'+value.title+'</li>';	
                   							}
                   				})	
                   			list_item+='</ul>\
                   						  </div>';
                   		}
                   		else if(data.data.topic.length>0)
                   		{
                   			list_item+='<div class="col-sm-12 page-search"><div class="">\
                   					<span>topic</span>\
                   					\
                   					</div>\
                   					<ul>';
                   				data.data.topic.map(function(value,index){
                   					if(value.what_next!="Nothing")
                   							{
                   								list_item+='<a href="{{ url("multi-documents") }}/'+value.id+'"><li>'+value.title+'</li></a>';		
                   							}
                   							else
                   							{
                   								list_item+='<li>'+value.title+'</li>';	
                   							}	
                   				})	
                   			list_item+='</ul>\
                   						  </div>';
                   		}
                   		else
                   		{
                   			$('#search-list').html('No data Found');
                        list_item+="No data Found";
                   		}
                   		console.log(list_item)
                   		$('#search-list').html(list_item);
                   });
@else
    $('#loginFormModal').modal({
            backdrop:'static', show: true
        })
        $('.close').on('click',function(e){
            window.location.href="{{ url('') }}";
        });
   jQuery(document).ready(function($) {
       $('#loginFormModal').modal('show');  
    });
    @endif 
</script>
<style type="text/css">
.page-search{
	margin-bottom: 40px;
}
	.page-search span {
    font-size: 34px;
    text-transform: capitalize;
    color: #0d5c6d;
    font-weight: bold;
    padding: 20px 0px;
    display: inline-block;
}
.page-search ul li {
    padding-left: 30px;
    color: #aaa;
    font-size: 20px;
    position: relative;
}
.page-search ul a:hover,.page-search ul a:focus{
	text-decoration: none;
}
.page-search ul a:hover li{
	color: #0d5c6d;
}
.page-search li:after {
    content: '';
    position: absolute;
    width: 10px;
    height: 10px;
    border-top: 1px solid #aaa;
    border-right: 1px solid #aaa;
    transform: rotate(45deg);
    left: 7px;
    top: 10px;
}
.page-search li:hover:after{
	border-color: #0d5c6d;
}
</style>
@endpush