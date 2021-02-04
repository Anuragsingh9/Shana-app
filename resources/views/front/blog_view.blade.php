<!DOCTYPE html>
@extends('layouts.front')

@section('content')
	<div class="banner_section clearfix" style="background-color: rgb(102, 74, 255);">
		<div class="container page_title_section">
			<h1 class="page_title" id="page-title">Heading 1</h1>
			<div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
		</div>
	</div>

	<div class="post-page page_main_sec">
		<div class="container">
		    <div class="row flexbox">
		        <div class="col-xs-12 col-sm-8 col-md-9 blogview-left">
        			<div class="clearfix" id="blogView">
        				<!--<div class="col-sm-12 blog-detail " style="padding: 50px 0px;">-->
        				<!--	<img src="maths.jpg" class="img-responsive center-block">-->
        					<!--<h2>Heading name</h2>-->
        				<!--	<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>-->
        					
        				<!--</div>-->
        				<!--<div class="col-sm-12 pdf-making" style="padding: 50px 0px;">-->
        				<!--	<a href="make.pdf">Page Maker</a>-->
        				<!--</div>-->
        				
        			</div>
		        </div>
		        <div class="col-xs-12 col-sm-4 col-md-3 blog-page-recent-post recent-post" id="recent-blog">
					
				</div>
			</div>
		</div>

	</div>
	
	@push('script')
<script>
	
	 path=window.location.href;
   var id=path.substr(path.lastIndexOf('/') + 1)
 var res=apiAjaxCall('get-book-id','post',{'id':id})
      var list_title2='';
     res.then(data => {
     		var value=data.data;
     		        $('#page-title').html(value.title)
      			 	$('#blogView').html('<div class="blog-detail clearfix">\
					<div class="blog-content clearfix">\
						<img src="'+value.preview+'" class="img-responsive center-block">\
						<p>'+value.file_detail+'</p>\
					</div>\
					</div>\
				')
     			 
    })

     var recent=apiAjaxCall('recent-books','get',{})
      recent.then(data => {
     		$('#recent-blog').append('<h4>Recent Post</h4>')
     			 $.each(data.data, function (index, value) {
     			    var descrip_text=value.description
                    var descripText = jQuery.trim(descrip_text).substring(0, 75).split(" ").slice(0, -1).join(" ") + "...";
     			 	if(value.doc_type=="pdf" || value.doc_type=="word" ){
		      			 	$('#recent-blog').append('<div class="recent-inner">\
							<div class="recent-inner-sec">\
								<div class="recent-img" style="background: url('+value.preview+') no-repeat center;"></div>\
								<h3>'+value.title+'</h3>\
								<p>'+((value.description==null)?'':descripText)+'</p>\
								<a href="'+value.file_detail+'" target="_blank">Read More <i class="fa fa-long-arrow-right"></i></a>\
							</div>\
						</div>\
						')
	      			}
	      			else{
	      				$('#recent-blog').append('<div class="recent-inner">\
							<div class="recent-inner-sec">\
								<div class="recent-img" style="background: url('+value.preview+') no-repeat center;"></div>\
								<h5>'+value.title+'</h5>\
								<p>'+((value.description==null)?'':descripText)+'</p>\
								<a href="{{url("view-book")}}/'+value.title+'">Read More <i class="fa fa-long-arrow-right"></i></a>\
							</div>\
						</div>\
						')
	      			}
      			})	 
    })
</script>
@endpush
@endSection