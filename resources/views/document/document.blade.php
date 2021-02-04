@extends('layouts.master')
@section('content')


<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Document
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div id="loader" class="hide">
        <img src="{{ publicAsset('images/LoaderIcon.gif') }}" class="img-responsive center-block">
    </div>
    <div class="row">

        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <!-- <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    Add Document Form
                </div>
                <div class="panel-body">
                    @if(!empty($document))
                        {{Form::model($document,['url' => url('update-document',$document->id),'class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                        {{Form::hidden('id',$document->id,array('id'=>'id'))}}
                    @else
                        {{Form::open(['url' => url('insert-document'), 'method' => 'post','class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                        {{Form::hidden('id','',array('id'=>'id'))}}
                    @endif
                         <div class="form-group">
                            <label class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-10">
                                {{Form::select('course_id',$course,null,array('class'=>'form-control m-b course','placeholder'=>'Select Course'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Course</label>
                            <div class="col-sm-10">
                                {{Form::select('subject_id',[],null,array('class'=>'form-control m-b subject','placeholder'=>'Select Subject'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Chapter</label>
                            <div class="col-sm-10">
                                {{Form::select('chapter_id',[],null,array('class'=>'form-control m-b chapter chapter','placeholder'=>'Select Chapter'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Topic</label>
                            <div class="col-sm-10">
                                {{Form::select('topic_id',[],null,array('class'=>'form-control m-b topic','placeholder'=>'Select Topic'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                {{Form::text('title',null,array('class'=>'form-control m-b','placeholder'=>'Title'))}}
                            </div>
                        </div>
                        <div class="form-group {{ (isset($document))?'hidden':'' }}">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                @if(isset($document))
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Video',false,array("class"=> "type-check","disabled"))}}Video</label>
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Audio',false,array("class"=> "type-check","disabled"))}}Audio</label>
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Text',false,array("class"=> "type-check","disabled"))}}Text</label>
                                    {{Form::hidden("doc_type_text",null,array('class'=>'doc_type_text'))}}
                                @else
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','Video',false,array("class"=> "type-check"))}}Video</label>
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','Audio',false,array("class"=> "type-check"))}}Audio</label>
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','Text',false,array("class"=> "type-check"))}}Text</label>
                                    {{-- <label class="checkbox-inline">{{Form::radio('doc_type[]','Audio_Text',false,array("class"=> "type-check"))}}Audio-Text</label>
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','Video_Text',false,array("class"=> "type-check"))}}Video-Text</label> --}}
                                @endif
                            </div>
                        </div>
                        <div class="Video" style="display:none;">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File/Url</label>
                                <div class="col-sm-10">
                                    <label class="checkbox-inline">{{Form::radio('video_type','file',false,array("class"=>"video-check"))}}File</label>
                                    <label class="checkbox-inline">{{Form::radio('video_type','url',false,array("class"=>"video-check"))}}Url</label>
                                </div>                                
                            </div>
                            <div class="form-group file" style="display:none;">
                                <label class="col-sm-2 control-label">Video File</label>
                                <div class="col-sm-10">
                                    {{Form::file('video_file',null)}}
                                </div>                                
                            </div>
                            <div class="form-group url" style="display:none;">
                                <label class="col-sm-2 control-label">Video Url</label>
                                <div class="col-sm-10">
                                    {{Form::text('video_url',null,array('class'=>'form-control m-b ','placeholder'=>'URL'))}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group Audio" style="display:none;">
                            <label class="col-sm-2 control-label">Audio File</label>
                            <div class="col-sm-10">
                                @if(isset($document->doc_file))
                                    Audio File:{{$document->doc_file}}
                                @endif
                                {{Form::file('audio_file',null)}}
                            </div>                                
                        </div>
                        <div class="form-group Text" style="display:none;">
                            <label class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-10">
                                {{Form::textarea('content',null,array('class'=>'form-control m-b','id'=>'pdf_content','placeholder'=>'Content','rows'=>'2'))}}
                                {{-- {{Form::file('pdf_file',null)}} --}}
                            </div>                                
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Author Name</label>
                            <div class="col-sm-10">
                                {{Form::text('author_name',null,array('class'=>'form-control m-b','placeholder'=>'Author Name'))}}
                            </div>
                        </div>
                        <div class="form-group hide">
                            <label class="col-sm-2 control-label">Display To</label>
                            <div class="col-sm-10">
                                {{Form::select('user_type[]',user_type(),null,array('multiple'=>true,'class'=>'form-control m-b user_type'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Preview Image</label>
                            <div class="col-sm-10">
                                @if(isset($document->preview_image))
                                    <img src="{{cloudUrl($document->preview_image)}}" class="img-responsive" width="110" height="200" />
                                @endif
                                {{Form::file('preview',null)}}
                                <span>Preview Image Size must be 250X250 </span>
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-10">
                                {{Form::text('order',null,array('class'=>'form-control m-b','id'=>'order','placeholder'=>'Order'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                {{Form::textarea('description',null,array('class'=>'form-control m-b','id'=>'description','placeholder'=>'Description','rows'=>'2'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
                                <div class="preview"></div>
                                <div class="progress" style="display:none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        0%
                                    </div>
                                </div>
                            </div>
                        </div>
                       {{-- <div id="loader-icon" style="display:none;"><img src="{{asset('images/LoaderIcon.gif')}}" /></div>--}}

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                {{Form::reset('Cancel',array('class'=>'btn btn-default'))}}
                                {{Form::submit('Save',array('class'=>'btn btn-primary btnSubmit','id'=>"btnSubmit"))}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>


</div>

<script src="{{publicAsset('js/jqueryForm.js')}}"></script>
<script>



    $(document).ready(function() {
        $('select[name=topic_id]').on('change',function(e){
            localStorage.setItem('topic', e.target.value);
        })
        $('select[name=course_id]').on('change',function(e){
            localStorage.clear();
        })

        $(".user_type").multiselect({
            includeSelectAllOption: true,
        });
        //CKEDITOR.replace( 'description' );
        CKEDITOR.replace( 'pdf_content' );
        @if(isset($document))
            var course_id="{{$document->course_id or ''}}";
            var subject_id="{{$document->subject_id or ''}}";
            var chapter_id="{{$document->chapter_id or ''}}";
            var topic_id="{{$document->topic_id or ''}}";

            changeCourse(course_id,subject_id);
            changeSubject(subject_id,chapter_id);
            changeChapter(chapter_id,topic_id);
            @else
             var course_id=localStorage.getItem('course');;
            var subject_id=localStorage.getItem('subject');;
            var chapter_id=localStorage.getItem('chapter');;
            var topic_id=localStorage.getItem('topic');;
             $('select[name=course_id]').val(localStorage.getItem('course'))
            changeCourse(course_id,subject_id);
            changeSubject(subject_id,chapter_id);
            changeChapter(chapter_id,topic_id);
        @endif
    });
    @if(isset($document))
        var user_type="{!! $document->user_type !!}";
        var doc_type="{{ $document->doc_type }}";
        var result = user_type.split(',');
        $(".user_type").val(result);
        $('input[type=checkbox]').each(function() {
            if($(this).val()==doc_type)
                $(this).prop('checked', true);
            func_type_check();
        });
        $(".doc_type_text").val(doc_type);
        @else
         $(document).ready(function() {
            $(function(){
                $("#document-form").validate({

                    rules: {
                        //topic_id: { required: true },
                        title: { required: true },
                        author_name: { required: true },
                        description: { required: true },
                        'user_type[]': { required: false },
                        'doc_type[]' : { required :true },
                        video_type : { required : true },
                        video_url : {required: true, url: true},
                        video_file : {required : function(){
                            return $("#id").val() < 0;
                        },
                            accept: "video/*" },
                        audio_file : {required : function(){
                            return $("#id").val() < 0;
                        },
                            accept: "audio/*"},
                        preview : {
                            accept: "jpg,png,jpeg,gif"
                        }
                    },
                    messages:{
                        video_file:{ accept: "Please Select only Video File",},
                        audio_file:{ accept: "Please Select only Audio File",},
                        preview:{ accept: "Please Select only Image File",}
                    },
                    submitHandler: function(form) { // <- pass 'form' argument in
                    $("#pdf_content").val(CKEDITOR.instances['pdf_content'].getData()) 
                        $(".submit").attr("disabled", true);

                       // $('#loader').removeClass('hide');
                        $('.progress').css('display', 'block');
                        $('#document-form').ajaxSubmit({
                            target:   '#targetLayer',
                            beforeSubmit: function() {
                                $(".progress-bar").width('0%');
                            },
                            uploadProgress: function (event, position, total, percentComplete){
                                console.log(percentComplete);
                                $(".progress-bar").css('width',percentComplete + '%');
                                $(".progress-bar").html( percentComplete +'%')
                            },
                            dataType:'json',
                            timeout:   60000*10,
                            success:showResponse,
                            error:function (data) {
                                //alert('asdfasdfDDD')
                            }

                            //resetForm: true
                        });
                        return false;
                    }
                });
            });

        });
    function showResponse(data, statusText, xhr, $form)  {


        console.log(data)

        if(data.status==200){
            $(".progress-bar").addClass('progress-bar-success');
            $('#loader').addClass('hide');
            window.location.href="{{url('document-list')}}";

        }else{
            $(".submit").attr("disabled", false);
            $('#loader').addClass('hide');

        }


        // for normal html responses, the first argument to the success callback
        // is the XMLHttpRequest object's responseText property

        // if the ajaxSubmit method was passed an Options Object with the dataType
        // property set to 'xml' then the first argument to the success callback
        // is the XMLHttpRequest object's responseXML property

        // if the ajaxSubmit method was passed an Options Object with the dataType
        // property set to 'json' then the first argument to the success callback
        // is the json data object returned by the server

        /*   alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +
         '\n\nThe output div should have already been updated with the responseText.');*/
    }

    @endif
    $(document).on('click','.type-check',function(){
        func_type_check(this.value);
    });
    $(document).on('click','.video-check',function(){
        func_video_check();   
    });
    function func_type_check(data){

        if(data){

            if (data == 'Audio_Text') {
                $(".Audio").show();
                $(".Text").show();
                $(".Video").hide();
            }
            else if (data == 'Video_Text') {
                $(".Video").show();
                $(".Text").show();
                $(".Audio").hide();
            }
            else if(data=='Audio')
                {
                    $(".Video").hide();
                    $(".Text").hide();
                    $("." + data).show();
                }
            else if(data=='Video')
                {
                    $(".Audio").hide();
                    $(".Text").hide();
                    $("." + data).show();
                }
            else if(data=='Text')
                {
                    $(".Video").hide();
                    $(".Audio").hide();
                    $("." + data).show();
                }



        }else{
            $('input[name="doc_type[]"]').each(function () {

                    if (!this.checked) {
                        if ($(this).val() == 'Audio_Text') {
                            $(".Audio").hide();
                            $(".Text").hide();
                        } else if ($(this).val() == 'Video_Text') {
                            $(".Video").hide();
                            $(".Text").hide();
                        } else {
                            $("." + $(this).val()).hide();
                        }
                    }


               /* if (this.checked) {
                    if ($(this).val() == 'Audio_Text') {
                        $(".Audio").show();
                        $(".Text").show();
                    } else if ($(this).val() == 'Video_Text') {
                        $(".Video").show();
                        $(".Text").show();
                    }else{
                        $("." + $(this).val()).show();
                    }
                }else {

                    $('input[name="doc_type[]"]:checked').each(function () {
                        if (!this.checked) {
                            if ($(this).val() == 'Audio_Text') {
                                $(".Audio").hide();
                                $(".Text").hide();
                            } else if ($(this).val() == 'Video_Text') {
                                $(".Video").hide();
                                $(".Text").hide();
                            } else {
                                $("." + $(this).val()).hide();
                            }
                        }
                    });

                    //$("." + $(this).val()).show();
                }*/
            });
        }


    }
    function func_video_check(){
        $('input[type=radio]').each(function () {
            if (this.checked)
                $("."+$(this).val()).show();
            else
                $("."+$(this).val()).hide();
            });
    }

/*
    $('#btnSubmit').click(function () {
        var result = { };
        $.each($('form').serializeArray(), function() {
            result[this.name] = this.value;
        });

result['audio']=$('#userImage')[0].files[0];

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            dataType: 'json',
            url: "insert-document",
            processData: true,
            contentType: false,
            data: {x:$('#document-form').serializeArray() },
            success: function (data) {
            }
        });
        });*/


</script>
    <style>
        #loader {
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 9;
            left: 0;
            top: 0;
            background: rgba(255, 255, 255, 0.5);
        }
        #loader img {
            width: 134px;
            height: 100px;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
        }
    </style>
@endsection