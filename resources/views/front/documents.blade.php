@extends('layouts.front')

@section('content')
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelementplayer.css">
    <link rel="stylesheet" href="{{publicAsset('css/chromecast.min.css')}}">
    <link rel="stylesheet" href="{{publicAsset('css/demo.css')}}">
<div id="loader">
    <img src="{{ publicAsset('css/images/loader.gif') }}" alt="Loader">
</div>
<div id="dataList" class="hidden">
<div class="banner_section clearfix">
    <div class="container page_title_section">
        <h1 class="page_title">Counting</h1>
        <div class="banner-icon"><span><img src="{{ publicAsset('css/images/idea-icon2.png')}}" class="center-block"></span></div>
    </div>
</div>

{{-- <div class="page_upper_text">
    <div class="container">
        <div class="row text-center">
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-8 col-md-offset-2">
                <h2>Want a personalized The World of Math experience?</h2>
                <p>Missions recommend what to learn next, help you remember what you’ve learned by mixing skills, and save your progress.</p>
            </div>
        </div>
    </div>
</div> --}}

<div class="page-topic-sec">
	<div class="container">
		<div class="row" id="document_list">

		</div>
	</div>
</div>
</div>
<div class="modal fade" id="buyFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Buy Options</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="clearfix">
            <div class="row">
                <div class="col-xs-12 tile">
                    <div class="tile-second">
                    <ul style="overflow:hidden">
                    <li style="float:left; margin-left:20px">                     
                               <div id='course'>  
                               </div>
                       </li>
                       <li style="float:left; margin-left:20px">
                           <div id='subject'>
                               
                           </div>
                       </li>
                   </ul>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="media-modal">

                        
                    </div>
                </div>
            </div>
        </div>
    </div>

 <style>
        #myModal .modal-content{
            background-color: transparent;
            border: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            margin-top: -120px;
        }
        #myModal .modal-header{
            border-bottom: none;
            padding: 0;
        }
        #myModal .modal-header .close {
            color: #fff;
        }
        video {
            width:100%;
        }
        audio {
            width:100%;
        }
        .modal-lg {
            width: 90%;
        }
    </style>
    
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelement-and-player.min.js"></script>
<script src="{{publicAsset('js/chromecast.min.js')}}"></script>
<script>
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    @if(Auth::check())
    path=window.location.href;
    var id=path.substr(path.lastIndexOf('/') + 1);
    data={document_id:id,user_id:'{{Auth::user()->id}}'}
           var res=apiAjaxCall('document-detail-web','POST',data)
           var list_title='';
           var list_item='';
           res.then(data => {
                $('.page_title').html(data.data.title); 
                list_title+='<div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 text-center topic-detail document-data"><div class="x-y-full" style="background: url('+data.data.image+') no-repeat center ;background-size: cover;"><div class="topic-detail-inner">\
                <h2>'+data.data.title+'</h2>\
                <h4>'+data.data.author_name+'</h4>\
                <ul>';
                paiddata={'document_id':id}
                    var paidCheck=apiAjaxCall('check-doc-parent','POST',paiddata)
                    paidCheck.then(checkData => {
                
                if(data.data.video_link!='')
                {
                    if(checkData.data!='Paid')
                    {

                   list_title+= '<li class="video-icon-list">\
                         <a  class="media-play" data-src="'+data.data.video_link+'" data-type="video">\
                            <div class="topic-icon-video">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 314.068 314.068" style="enable-background:new 0 0 314.068 314.068;" xml:space="preserve">\
                                        <g>\
                                            <g id="_x33_56._Play">\
                                                <g>\
                                                    <path d="M293.002,78.53C249.646,3.435,153.618-22.296,78.529,21.068C3.434,64.418-22.298,160.442,21.066,235.534     c43.35,75.095,139.375,100.83,214.465,57.47C310.627,249.639,336.371,153.62,293.002,78.53z M219.834,265.801     c-60.067,34.692-136.894,14.106-171.576-45.973C13.568,159.761,34.161,82.935,94.23,48.26     c60.071-34.69,136.894-14.106,171.578,45.971C300.493,154.307,279.906,231.117,219.834,265.801z M213.555,150.652l-82.214-47.949     c-7.492-4.374-13.535-0.877-13.493,7.789l0.421,95.174c0.038,8.664,6.155,12.191,13.669,7.851l81.585-47.103     C221.029,162.082,221.045,155.026,213.555,150.652z" fill="#ffffff"/>\
                                                </g>\
                                            </g>\
                                        </g>\
                                </svg>\
                             </div>\
                             <div class="text-topic">Play Video</div>\
                             \
                        </a>\
                    </li>';
                }
                else
                {
                    
                    if(data.data.is_UserBuy){
                        list_title+= '<li class="video-icon-list">\
                         <a class="media-play" data-src="'+data.data.video_link+'" data-type="video">\
                            <div class="topic-icon-video">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 314.068 314.068" style="enable-background:new 0 0 314.068 314.068;" xml:space="preserve">\
                                        <g>\
                                            <g id="_x33_56._Play">\
                                                <g>\
                                                    <path d="M293.002,78.53C249.646,3.435,153.618-22.296,78.529,21.068C3.434,64.418-22.298,160.442,21.066,235.534     c43.35,75.095,139.375,100.83,214.465,57.47C310.627,249.639,336.371,153.62,293.002,78.53z M219.834,265.801     c-60.067,34.692-136.894,14.106-171.576-45.973C13.568,159.761,34.161,82.935,94.23,48.26     c60.071-34.69,136.894-14.106,171.578,45.971C300.493,154.307,279.906,231.117,219.834,265.801z M213.555,150.652l-82.214-47.949     c-7.492-4.374-13.535-0.877-13.493,7.789l0.421,95.174c0.038,8.664,6.155,12.191,13.669,7.851l81.585-47.103     C221.029,162.082,221.045,155.026,213.555,150.652z" fill="#ffffff"/>\
                                                </g>\
                                            </g>\
                                        </g>\
                                </svg>\
                             </div>\
                             <div class="text-topic">Play Video</div>\
                             \
                        </a>\
                    </li>';
                    }else{
                        list_title+='<button class="btn btn-warning buy" data-id="'+id+'">Buy Now</button>';
                    }

                }
                }
                
                    if(data.data.audio_link!='')
                    {

                        if(checkData.data!='Paid')
                        {
                    list_title+='<li class="audio-icon-list">\
                         <a class="media-play" data-src="'+data.data.audio_link+'" data-type="audio" >\
                            <div class="topic-icon-audio">\
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 287.386 287.386" style="enable-background:new 0 0 287.386 287.386;" xml:space="preserve">\
                                <g>\
                                      <g>\
                                        <path id="audio" d="M62.743,155.437v98.42c0,5.867,4.741,10.605,10.605,10.605c5.854,0,10.605-4.738,10.605-10.605v-98.42    c0-5.856-4.751-10.605-10.605-10.605C67.484,144.832,62.743,149.576,62.743,155.437z" fill="#ffffff"/>\
                                        <path id="audio" d="M29.456,264.582h23.351v-116.85c0.064-0.56,0.166-1.119,0.166-1.693c0-50.412,40.69-91.42,90.698-91.42    c50.002,0,90.692,41.008,90.692,91.42c0,0.771,0.113,1.518,0.228,2.263v116.28h23.354c16.254,0,29.442-13.64,29.442-30.469    v-60.936c0-13.878-8.989-25.57-21.261-29.249c-1.129-66.971-55.608-121.124-122.45-121.124    c-66.86,0-121.347,54.158-122.465,121.15C8.956,147.638,0,159.32,0,173.187v60.926C0,250.932,13.187,264.582,29.456,264.582z" fill="#ffffff"/>\
                                        <path id="audio" d="M203.454,155.437v98.42c0,5.867,4.748,10.605,10.604,10.605s10.604-4.738,10.604-10.605v-98.42    c0-5.856-4.748-10.605-10.604-10.605C208.191,144.832,203.454,149.576,203.454,155.437z" fill="#ffffff"/>\
                                     </g>\
                                </g>\
                            </svg>\
                            </div>\
                            <div class="text-topic">Play Audio</div>\
                            \
                        </a>\
                    </li>';
                    }
                else
                {
                    
                    if(data.data.is_UserBuy){
                        list_title+='<li class="audio-icon-list">\
                         <a class="media-play" data-src="'+data.data.audio_link+'" data-type="audio">\
                            <div class="topic-icon-audio">\
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 287.386 287.386" style="enable-background:new 0 0 287.386 287.386;" xml:space="preserve">\
                                <g>\
                                      <g>\
                                        <path id="audio" d="M62.743,155.437v98.42c0,5.867,4.741,10.605,10.605,10.605c5.854,0,10.605-4.738,10.605-10.605v-98.42    c0-5.856-4.751-10.605-10.605-10.605C67.484,144.832,62.743,149.576,62.743,155.437z" fill="#ffffff"/>\
                                        <path id="audio" d="M29.456,264.582h23.351v-116.85c0.064-0.56,0.166-1.119,0.166-1.693c0-50.412,40.69-91.42,90.698-91.42    c50.002,0,90.692,41.008,90.692,91.42c0,0.771,0.113,1.518,0.228,2.263v116.28h23.354c16.254,0,29.442-13.64,29.442-30.469    v-60.936c0-13.878-8.989-25.57-21.261-29.249c-1.129-66.971-55.608-121.124-122.45-121.124    c-66.86,0-121.347,54.158-122.465,121.15C8.956,147.638,0,159.32,0,173.187v60.926C0,250.932,13.187,264.582,29.456,264.582z" fill="#ffffff"/>\
                                        <path id="audio" d="M203.454,155.437v98.42c0,5.867,4.748,10.605,10.604,10.605s10.604-4.738,10.604-10.605v-98.42    c0-5.856-4.748-10.605-10.604-10.605C208.191,144.832,203.454,149.576,203.454,155.437z" fill="#ffffff"/>\
                                     </g>\
                                </g>\
                            </svg>\
                            </div>\
                            <div class="text-topic">Play Audio</div>\
                            \
                        </a>\
                    </li>';
                    }
                    else {
                        list_title+='<button class="btn btn-warning buy" data-id="'+id+'">Buy Now</button>';
                    }
                }
                
                }
                if(data.data.text_link!='')
                {
                    if(checkData.data!='Paid')
                        {
                    list_title+='<li class="text-icon-list">\
                        <a class="media-play" data-src="'+data.data.text_link+'" data-type="text">\
                            <div class="topic-icon-text">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 470.586 470.586" style="enable-background:new 0 0 470.586 470.586;" xml:space="preserve">\
                                    <g>\
                                        <path d="M327.081,0H90.234C74.331,0,61.381,12.959,61.381,28.859v412.863c0,15.924,12.95,28.863,28.853,28.863H380.35   c15.917,0,28.855-12.939,28.855-28.863V89.234L327.081,0z M333.891,43.184l35.996,39.121h-35.996V43.184z M384.972,441.723   c0,2.542-2.081,4.629-4.635,4.629H90.234c-2.55,0-4.619-2.087-4.619-4.629V28.859c0-2.548,2.069-4.613,4.619-4.613h219.411v70.181   c0,6.682,5.443,12.099,12.129,12.099h63.198V441.723z M128.364,128.89H334.15c5.013,0,9.079,4.066,9.079,9.079   c0,5.013-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079C119.285,132.957,123.352,128.89,128.364,128.89z    M343.229,198.98c0,5.012-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15   C339.163,189.901,343.229,193.968,343.229,198.98z M343.229,257.993c0,5.013-4.066,9.079-9.079,9.079H128.364   c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15C339.163,248.914,343.229,252.98,343.229,257.993z    M343.229,318.011c0,5.013-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15   C339.163,308.932,343.229,312.998,343.229,318.011z" fill="#ffffff"/>\
                                    </g>\
                            </div> \
                            <div class="text-topic">\
                                Open Text\
                            </div>    \
                                           \
                        </a>\
                    </li>';
                    }
                else
                {
                    if(data.data.is_UserBuy){
                        list_title+='<li class="text-icon-list">\
                        <a class="media-play" data-src="'+data.data.text_link+'" data-type="text">\
                            <div class="topic-icon-text">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="23px" height="23px" viewBox="0 0 470.586 470.586" style="enable-background:new 0 0 470.586 470.586;" xml:space="preserve">\
                                    <g>\
                                        <path d="M327.081,0H90.234C74.331,0,61.381,12.959,61.381,28.859v412.863c0,15.924,12.95,28.863,28.853,28.863H380.35   c15.917,0,28.855-12.939,28.855-28.863V89.234L327.081,0z M333.891,43.184l35.996,39.121h-35.996V43.184z M384.972,441.723   c0,2.542-2.081,4.629-4.635,4.629H90.234c-2.55,0-4.619-2.087-4.619-4.629V28.859c0-2.548,2.069-4.613,4.619-4.613h219.411v70.181   c0,6.682,5.443,12.099,12.129,12.099h63.198V441.723z M128.364,128.89H334.15c5.013,0,9.079,4.066,9.079,9.079   c0,5.013-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079C119.285,132.957,123.352,128.89,128.364,128.89z    M343.229,198.98c0,5.012-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15   C339.163,189.901,343.229,193.968,343.229,198.98z M343.229,257.993c0,5.013-4.066,9.079-9.079,9.079H128.364   c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15C339.163,248.914,343.229,252.98,343.229,257.993z    M343.229,318.011c0,5.013-4.066,9.079-9.079,9.079H128.364c-5.012,0-9.079-4.066-9.079-9.079s4.067-9.079,9.079-9.079H334.15   C339.163,308.932,343.229,312.998,343.229,318.011z" fill="#ffffff"/>\
                                    </g>\
                            </div> \
                            <div class="text-topic">\
                                Open Text\
                            </div>    \
                                           \
                        </a>\
                    </li>';
                    }else{
                        list_title+='<button class="btn btn-warning buy" data-id="'+id+'">Buy Now</button>';
                    }

                }
                }
                list_title+='</ul>\
            </div></div></div>';
            $('#document_list').html(list_title);
                });
           
           })
//LOADER
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
    $(document).on('click','.buy',function(){
        var id= $(this).data('id');
        data={id:id}
        var res=apiAjaxCall('doc-purchase-data','POST',data);
       res.then(data => {
        console.log(data.data.course)
        if(data.data.course!=null)
          {
              
              $('#course').html('<span>'+data.data.course.course+'</span>\
                                                 <ul>\
                                                     <li>price: '+data.data.course.amount+'</li>\
                                                     <li>time:'+data.data.course.duration+' '+data.data.course.field+'</li>\
                                                 </ul>\
                                                 <form action="{!!route('subjectcoursepay')!!}" method="POST" >\
                                                    <input type="hidden" name="id" value="'+data.data.course.id+'">\
                                                    <input type="hidden" name="type" value="course">\
                                                    <input type="hidden" name="_token" value="{!!csrf_token()!!}">\
                                                    <button type="submit" data-id='+data.data.course.id+' data-type="course" id="subject-buy" class="btn sc-buy btn-sm btn-warning">Buy</button>\
                                             </div>')
         }
          if(data.data.subject!=null)
          {
              $('#subject').html('<span>'+data.data.subject.subject+'</span>\
                                                 <ul>\
                                                     <li>price: '+data.data.subject.amount+'</li>\
                                                     <li>time:'+data.data.subject.duration+' '+data.data.subject.field+'</li>\
                                                 </ul>\
                                                 <form action="{!!route('subjectcoursepay')!!}" method="POST" >\
                            <input type="hidden" name="id" value="'+data.data.subject.id+'">\
                            <input type="hidden" name="type" value="subject">\
                            <input type="hidden" name="_token" value="{!!csrf_token()!!}">\
                                                 <button type="submit" id="subject-buy" class="btn sc-buy btn-sm btn-warning">Buy</button>\
                                             </div>')
          }
          $('#buyFormModal').modal('show');                 
        })
        
    }) 
     var player;
    $(document).on('click','.sc-buy',function(){
        var id= $(this).data('id');
        var type= $(this).data('type');
        
       $('#buyFormModal').modal('hide');
    })
    $(document).on('click',".media-play",function(e){
        console.log($(this).data('src'))
       
                        
       if($(this).data('type')=='video')
       {
        $('#media-modal').html(' <video width="100%" height="560" autoplay preload="none" id="media">\
                            Your browser does not support the video tag.\
                        </video>')
            player = new MediaElementPlayer('media', {
                features: ['playpause', 'current', 'progress', 'duration', 'volume', 'chromecast', 'fullscreen']
            });
        }
        else if($(this).data('type')=='audio')
        {
                $('#media-modal').html(' <audio width="100%" autobuffer autoloop loop controls id="media-Audio">\
                        </audio>')
            player = new MediaElementPlayer('media-Audio', {
                features: ['playpause', 'current', 'progress', 'duration', 'volume', 'chromecast', 'fullscreen']
            });
        }
        else
        {
           $('#media-modal').html('<div style="color:#fff">'+$(this).data('src')+'</div>') 
        }
        if($(this).data('type')!='text')
        {
            player.setSrc($(this).data('src'))
            $('#myModal').modal('show'); 
            player.load();
            player.play();
        }
        else
        {
            $('#myModal').modal('show'); 
        }
    })
    $('#myModal').on('hidden.bs.modal', function () {
      player.pause();
 })
</script>
    
@endpush
<style>
    .document-data h2{
    margin-bottom: 20px;
    color: #fff;
    font-weight: 300;
    font-size: 20px;
}
.document-data h4 {
  color: #fff;  
}
</style>
@endsection