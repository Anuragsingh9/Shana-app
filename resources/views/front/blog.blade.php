<!DOCTYPE html>
@extends('layouts.front')

@section('content')
	<div class="banner_section clearfix" style="background-color: rgb(102, 74, 255);">
		<div class="container page_title_section">
			<h1 class="page_title">Blog</h1>
			<div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
		</div>
	</div>


	<div class="page_main_sec">
		<div class="container">
		    <div class="row flexbox">
		        <div class="col-xs-12 col-sm-8 col-md-9 blogview-left">
		            <div class="row">
            			<div class="sub_data_row" id="blog">
            			    
            				
            			</div>
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
   
 var res=apiAjaxCall('booksWeb','Get',{})
      var list_title2='';
      const monthNames = ["January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"
                        ];
      var blogDate=''
     res.then(data => {
     			console.log(data)
     			 $.each(data.data, function (index, value) {
     			     var date=new Date(value.created_at)
     			        blogDate=date.getDate()+'-'+monthNames[date.getMonth()]+'-'+date.getFullYear()
         				var title=value.file_detail
                        var shortText = jQuery.trim(title).substring(0, 250).split(" ").slice(0, -1).join(" ") + "...";
     			 	if(value.doc_type=="pdf" || value.doc_type=="word" ){
		     			 	$('#blog').append('<div class="col-xs-4 col-sm-4 col-md-4 sub_course_chapter_icon">\
							<a href="'+value.file_detail+'" target="_blank">\
								<div class="sub_course_img">\
									<div class="sub_course_icon" style="background: url('+value.preview+') no-repeat center;">\
									</div>\
								</div>\
								<div class="about_course_data">\
									<h4>'+value.title+'</h4>\
									<span class="post-date-time">'+blogDate+'</span>\
								</div>\
							</a>\
						</div>')
     			 	}
     			 	else{
     			 		$('#blog').append('<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 sub_course_chapter_icon">\
							<a href="{{url("view-book")}}/'+value.title+'">\
								<div class="sub_course_img">\
									<div class="sub_course_icon" style="background: url('+value.preview+') no-repeat center top;">\
									</div>\
								</div>\
								<div class="about_course_data">\
									<h4>'+value.title+'</h4>\
									<span class="post-date-time">'+blogDate+'</span>\
									<div class="clearfix shortPostContent">'+shortText+'</div>\
								</div>\
							</a>\
						</div>')
     			 	}
     			 })
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
								<div class="recent-img" style="background: url('+value.preview+') no-repeat center top;"></div>\
								<h5>'+value.title+'</h5>\
								<p>'+((value.description==null)?'':descripText)+'</p>\
								<a href="'+value.file_detail+'" target="_blank">Read More <i class="fa fa-long-arrow-right"></i></a>\
							</div>\
						</div>\
						')
	      			}
	      			else{
	      				$('#recent-blog').append('<div class="recent-inner">\
							<div class="recent-inner-sec">\
								<div class="recent-img" style="background: url('+value.preview+') no-repeat center top;"></div>\
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