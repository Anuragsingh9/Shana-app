@extends('layouts.front')

@section('content')
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
                    {{--   <a id="media" data-fancybox data-type="iframe" controlsList="nodownload" data-src=""
                          href="javascript:;">
                       </a>
                    --}}
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
                    <div class="modal-body" id="modal-media2">

                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .modal-content{
            background-color: transparent;
            border: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            margin-top: -120px;
        }
        .modal-header{
            border-bottom: none;
            padding: 0;
        }
        .modal-header .close {
            color: #fff;
        }
        video {
            width:100%;
        }
        .modal-lg {
            width: 90%;
        }
    </style>

@endsection

@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelement-and-player.min.js"></script>
<script src="{{publicAsset('js/chromecast.min.js')}}"></script>

<script>
 path=window.location.href;
var id=path.substr(path.lastIndexOf('/') + 1);
data={document_id:id,user_id:'{{Auth::user()->id}}'};
var res=apiAjaxCall('document-detail-web','POST',data)
var type="";
 res.then(data => {
    if(data.data.audio_link!="")
    {
        type="audio";
         $("#modal-media2").html('<audio width="100%" autobuffer autoloop loop controls id="media-Audio">\
                        </audio>')
        var player;
        player = new MediaElementPlayer('media-Audio', {
            features: ['playpause', 'current', 'progress', 'duration', 'volume', 'chromecast', 'fullscreen']
        });
        player.setSrc(data.data.audio_link)
    }
    else if(data.data.video_link!="")
    {
        type="video";
        $("#modal-media2").html('<video width="100%" height="560" autoplay preload="none" id="media">\
                            Your browser does not support the video tag.\
                        </video>')
        var player;
        player = new MediaElementPlayer('media', {
            features: ['playpause', 'current', 'progress', 'duration', 'volume', 'chromecast', 'fullscreen']
        });
        if(data.data.orignal_link==''){
            player.setSrc(data.data.video_link)
        }
        else{
            player.setSrc(data.data.orignal_link)
            player.play()
            $('#media_youtube_iframe').css('width', '100%');         
  
        }


        //$("#media").html('<source src="'+data.data.video_link+'" type="video/mp4">');
        //console.log($("#media"))    ;
    }
    else
    {
         type="text";
         $('#modal-media2').html('<div style="color:#fff">'+data.data.text_link+'</div>') 
    }

    $(document).ajaxStop(function () {
             $('#dataList').removeClass('hidden');
             $('#loader').addClass('hidden');
              $(document).ready(function() {

                 //$("#media").fancybox().trigger('click');
                $('#myModal').modal('show');
                if(type!="text")
                {
                  player.load();
                  player.play();
              }
                 });
});

 });

 $('#myModal').on('hidden.bs.modal', function () {
     history.go(-1)
 })

 
</script>

@endpush
