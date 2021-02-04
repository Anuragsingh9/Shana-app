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
                    {{Form::open(['url' => url('insert-multi-document'), 'method' => 'post','class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                    {{Form::hidden('id','',array('id'=>'id'))}}
                    <div class="multi_fields">
                        <div class="panel-body">
                            {{--  @if(!empty($document))
                            {{Form::model($document,['url' => url('update-document',$document->id),'class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                            {{Form::hidden('id',$document->id,array('id'=>'id'))}}
                        @else--}}
                            <div class="top_btn"><button class="add_field_button btn btn-info btn-md">+</button></div>
                            {{--@endif--}}
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Subject</label>
                                <div class="col-sm-2">
                                    {{Form::select('course_id[]',$course,null,array('class'=>'form-control m-b course','placeholder'=>'Select Course','required'=>'required'))}}
                                </div>

                                <label class="col-sm-1 control-label">Course</label>
                                <div class="col-sm-2">
                                    {{Form::select('subject_id[]',[],null,array('class'=>'form-control m-b subject','placeholder'=>'Select Subject'))}}
                                </div>
                                <label class="col-sm-1 control-label">Chapter</label>
                                <div class="col-sm-2">
                                    {{Form::select('chapter_id[]',[],null,array('class'=>'form-control m-b chapter ','placeholder'=>'Select Chapter'))}}
                                </div>

                                <label class="col-sm-1 control-label">Topic</label>
                                <div class="col-sm-2">
                                    {{Form::select('topic_id[]',[],null,array('class'=>'form-control m-b topic','placeholder'=>'Select Topic'))}}
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-1 control-label">Title</label>
                                <div class="col-sm-5">
                                    {{Form::text('title[]',null,array('class'=>'form-control m-b title','placeholder'=>'Title','required'=>'required'))}}
                                </div>

                                <label class="col-sm-1 control-label">Type</label>
                                <div class="col-sm-5">
                                    @if(isset($document))
                                        <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Video',false,array("class"=> "type-check","disabled"))}}
                                            Video</label>
                                        <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Audio',false,array("class"=> "type-check","disabled"))}}
                                            Audio</label>
                                        <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Text',false,array("class"=> "type-check","disabled"))}}
                                            Text</label>
                                        {{Form::hidden("doc_type_text",null,array('class'=>'doc_type_text'))}}
                                    @else
                                        <label class="checkbox-inline">{{Form::radio('doc_type[]','Video0',false,array("class"=> "type-check",'required'=>'required'))}}
                                            Video</label>
                                        <label class="checkbox-inline">{{Form::radio('doc_type[]','Audio0',false,array("class"=> "type-check",'required'=>'required'))}}
                                            Audio</label>
                                        <label class="checkbox-inline">{{Form::radio('doc_type[]','Text0',false,array("class"=> "type-check",'required'=>'required'))}}
                                            Text</label>
                                        {{-- <label class="checkbox-inline">{{Form::radio('doc_type[1]','Audio_Text1',false,array("class"=> "type-check"))}}
                                            Audio-Text</label>
                                        <label class="checkbox-inline">{{Form::radio('doc_type[1]','Video_Text1',false,array("class"=> "type-check"))}}
                                            Video-Text</label> --}}
                                    @endif
                                </div>
                            </div>


                            <div class="Video0" style="display:none;">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">File/Url</label>
                                    <div class="col-sm-10">
                                        <label class="checkbox-inline">{{Form::radio('video_type[0]','file0',false,array("class"=>"video-check","data-value"=>"0"))}}
                                            File</label>
                                        <label class="checkbox-inline">{{Form::radio('video_type[0]','url0',false,array("class"=>"video-check","data-value"=>"0"))}}
                                            Url</label>
                                    </div>
                                </div>
                                <div class="form-group file0" style="display:none;">
                                    <label class="col-sm-2 control-label">Video File</label>
                                    <div class="col-sm-10">
                                        {{Form::file('video_file[0]',null)}}
                                    </div>
                                </div>
                                <div class="form-group url0" style="display:none;">
                                    <label class="col-sm-2 control-label">Video Url</label>
                                    <div class="col-sm-10">
                                        {{Form::text('video_url[0]',null,array('class'=>'form-control m-b ','placeholder'=>'URL'))}}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group Audio0" style="display:none;">
                                <label class="col-sm-2 control-label">Audio File</label>
                                <div class="col-sm-10">
                                    @if(isset($document->doc_file))
                                        Audio File:{{$document->doc_file}}
                                    @endif
                                    {{Form::file('audio_file[0]',null)}}
                                </div>
                            </div>
                            <div class="form-group Text0" style="display:none;">
                                <label class="col-sm-2 control-label">Content</label>
                                <div class="col-sm-10">
                                    {{Form::textarea('content[]',null,array('class'=>'form-control m-b','id'=>'pdf_content0','placeholder'=>'Content','rows'=>'2'))}}
                                    {{-- {{Form::file('pdf_file',null)}} --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Author Name</label>
                                        <div class="col-sm-10">
                                            {{Form::text('author_name[]',null,array('class'=>'form-control m-b','placeholder'=>'Author Name'))}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Preview Image</label>
                                        <div class="col-sm-10">
                                            @if(isset($document->preview_image))
                                                <img src="{{cloudUrl($document->preview_image)}}" class="img-responsive"
                                                     width="110" height="200"/>
                                            @endif
                                            {{Form::file('preview[]',null)}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group hide">
                                    <label class="col-sm-2 control-label">Display To</label>
                                    <div class="col-sm-10">
                                        {{Form::select('user_type[]',user_type(),null,array('multiple'=>true,'class'=>'form-control m-b user_type'))}}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-10">
                                            {{Form::textarea('description[]',null,array('class'=>'form-control description m-b','id'=>'description','placeholder'=>'Description','rows'=>'2','required'=>'required'))}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="preview"></div>
                               

                            </div>
                            {{-- <div id="loader-icon" style="display:none;"><img src="{{asset('images/LoaderIcon.gif')}}" /></div>--}}


                        </div>
                        <div class="input_fields_wrap">

                        </div>
                    </div>
                     <div class="form-group">
                    <div class="col-xs-12 col-sm-12">
                        <div class="progress" style=display:none;>
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        0%
                                    </div>
                                </div>
                    </div> 
                    </div>
                    <div class="form-group pull-right">
                        <div class="col-sm-8 col-sm-offset-2">
                            {{--{{Form::reset('Cancel',array('class'=>'btn btn-default'))}}--}}
                            {{Form::submit('Save',array('class'=>'btn btn-primary btnSubmit','id'=>"btnSubmits"))}}
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>


    </div>

    <script src="{{publicAsset('js/jqueryForm.js')}}"></script>
    <script>
        // for auto select previous value

        $(document).on('ready',function(e){
             var course_id=localStorage.getItem('course');
            var subject_id=localStorage.getItem('subject');
            var chapter_id=localStorage.getItem('chapter');
            var topic_id=localStorage.getItem('topic');
            var type=localStorage.getItem('doc_type');
            if(course_id!=null){
                changeCourse(course_id,subject_id);
                if(subject_id!=null){
                    changeSubject(subject_id,chapter_id);
                    if(chapter_id!=null){
                        changeChapter(chapter_id,topic_id);
                        $('.topic').val(topic_id);
                        $('.chapter').val(chapter_id);
                    }
                    $('.subject').val(subject_id);
                }
                $('.course').val(course_id);

           }
           if(type!=null){
                $('input[type="radio"][name="doc_type[]"]').each(function(){
                          if($(this).val()==type+0)
                           $(this).prop('checked', true);
                            func_type_check(this)
                });
            }    
            // end select previous value
           $(function () {

                $("#document-form").validate({

                     rules: {
                        //topic_id: { required: true },
                        'title[]': {required: true},
                        'author_name[]': {required: true},
                        'description[]': {required: true},
                        'user_type[]': {required: false},
                        'doc_type[1]': {required: true},
                        'video_type[1]': {required: true},
                        'video_url[]': {required: true, url: true},
                        'video_file[]': {
                            required: function () {
                                return $("#id").val() < 0;
                            },
                            accept: "video/*"
                        },
                        'audio_file[1]': {
                            required: function () {
                                return $("#id").val() < 0;
                            },
                            accept: "audio/*"
                        },
                        'preview[]': {
                            accept: "jpg,png,jpeg,gif"
                        }
                    },
                    messages: {
                        'video_file[]': {accept: "Please Select only Video File",},
                        'audio_file[1]': {accept: "Please Select only Audio File",},
                        'preview[]': {accept: "Please Select only Image File",}
                    },
                    submitHandler: function (form) { // <- pass 'form' argument in
                        $(".submit").attr("disabled", true);
                        //$('#loader').removeClass('hide');
                        $('.progress').css('display', 'block');
                        $('#document-form').ajaxSubmit({
                           
                            target: '#targetLayer',
                            beforeSubmit: function () {
                                $("#progress-bar").width('0%');
                            },
                            uploadProgress: function (event, position, total, percentComplete) {
                                 console.log(percentComplete);
                                $(".progress-bar").css('width',percentComplete + '%');
                                $(".progress-bar").html( percentComplete +'%')
                            },
                            dataType:'json',
                            timeout:   60000*1000,
                            success:showResponse,
                            error: function (data) {
                                //alert('asdfasdfDDD')
                            }

                            //resetForm: true
                        });
                        // return false;
                    }
                });
            }); 
        })
        var rule=''
        $(document).ready(function () {
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $(".input_fields_wrap"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID
            var value = 1;
            @php  $no = 1;  @endphp
             //initlal text box count
            $(add_button).click(function (e) { //on add input button click
                e.preventDefault();
               
                if (value < max_fields) { //max input box allowed
                    var html = '  <div class="panel-body">';
                    html += '<div class="form-group">';
                    html += '<label class="col-sm-1 control-label">Subject</label>';
                    html += '<div class="col-sm-2">';
                    html+='<select class="form-control m-b course-multi" data-id="'+value+'" required="required" name="course_id['+value+']" id="course'+value+'" aria-required="true" aria-invalid="true">\
                    <option selected="selected" value="">Select Course</option>\
                    @foreach($course as $k=>$v)\
                    <option value="{{$k}}">{{$v}}</option>\
                    @endforeach\
                    </select></div>';

                    html += '<label class="col-sm-1 control-label">Course</label>';
                    html += '<div class="col-sm-2">';
                    html+='<select class="form-control m-b subject-multi" name="subject_id['+value+']"  data-id="'+value+'" id="subject'+value+'">\
                    <option selected="selected" value="">Select Subject</option>\
                    </select></div>'
                    html += ' <label class="col-sm-1 control-label">Chapter</label>';
                    html += ' <div class="col-sm-2">';
                     html+='<select class="form-control m-b chapter-multi" name="chapter_id['+value+']"  data-id="'+value+'" id="chapter'+value+'">\
                    <option selected="selected" value="">Select Chapter</option>\
                    </select></div>'
                    html += ' <label class="col-sm-1 control-label">Topic</label>';
                    html += '  <div class="col-sm-2">';
                     html+='<select class="form-control m-b topic-multi" name="topic_id['+value+']"  data-id="'+value+'" id="topic'+value+'">\
                    <option selected="selected" value="">Select Topic</option>\
                    </select>'
                    html += '  </div>';

                    html += '</div>';

                    html += ' <div class="form-group">';
                    html += ' <label class="col-sm-1 control-label">Title</label>';
                    html += '    <div class="col-sm-5">';
                    html+='<input class="form-control m-b" placeholder="Title" required="" name="title['+value+']" type="text" aria-required="true" aria-invalid="true">'
                    html += '</div>';

                    html += '<label class="col-sm-1 control-label">Type</label>';
                    html += '<div class="col-sm-5">';

                    html += '<label class="checkbox-inline">';
                    html +='<input type="radio" class="type-check" name="doc_type['+value+']" value="Video'+value+'" required/>Video</label>';
                    html += '<label class="checkbox-inline">';
                    html +='<input type="radio" class="type-check" name="doc_type['+value+']" value="Audio'+value+'" required/>Audio</label>';
                    html += '<label class="checkbox-inline">';
                    html +='<input type="radio" class="type-check" name="doc_type['+value+']" value="Text'+value+'" required/>Text</label>';
                   html += '  </div></div>';

                    html +=' <div class="Video'+value+'" style="display:none;">';
                    html +='<div class="form-group">';
                    html +='<label class="col-sm-2 control-label">File/Url</label>';
                    html +='<div class="col-sm-10">';
                    html +='<label class="checkbox-inline"><input class="video-check" type="radio" name="video_type['+value+']" value="file'+value+'" data-value="'+value+'">';
                    html +='File</label>';
                    html +='<label class="checkbox-inline"><input class="video-check" type="radio" name="video_type['+value+']" value="url'+value+'" data-value="'+value+'">';
                    html +='Url</label>';
                    html +='</div>';
                    html +=' </div>';
                    html +=' <div class="form-group file'+value+'" style="display:none;">';
                    html +='<label class="col-sm-2 control-label">Video File</label>';
                    html +='<div class="col-sm-10">';
                    html +='{{Form::file("video_file",null)}}</div></div>';
                    html +='<div class="form-group url'+value+'" style="display:none;">';
                    html +='<label class="col-sm-2 control-label">Video Url</label>';
                    html +='<div class="col-sm-10">';
                    html +='<input class="form-control m-b" type="text" placeholder="URL" name="video_url['+value+']">';
                    html +='</div> </div></div>';
                    html +='  <div class="form-group Audio'+value+'" style="display:none;">';
                    html +=' <label class="col-sm-2 control-label">Audio File</label>';
                    html +=' <div class="col-sm-10">';
                    html +='<input class="form-control " type="file"  name="audio_file['+value+']"> </div></div>';
                    html +=' <div class="form-group Text'+value+'" style="display:none;">';
                    html +='<label class="col-sm-2 control-label">Content</label>';
                    html +=' <div class="col-sm-10">';
                    html +='<textarea name="content[]" class="form-control m-b" id="pdf_content'+value+'"></textarea>    </div> </div>';

                    html +=' <div class="row">';
                    html +='<div class="col-sm-6">';
                    html +='<div class="form-group">';
                    html +='<label class="col-sm-2 control-label">Author Name</label>';
                    html +=' <div class="col-sm-10">';
                    html +='{{Form::text("author_name[]",null,array("class"=>"form-control m-b","placeholder"=>"Author Name"))}}';
                    html +='</div>';
                    html +='</div>';
                    html +='<div class="form-group">';
                    html +=' <label class="col-sm-2 control-label">Preview Image</label>';
                    html +='<div class="col-sm-10">';

                    html +='    {{Form::file("preview[]",null)}}';
                    html +='    </div>';
                    html +=' </div>';
                    html +='    </div>';
                    html +='   <div class="form-group hide">';
                    html +='   <label class="col-sm-2 control-label">Display To</label>';
                    html +=' <div class="col-sm-10">';
                    html +='       {{Form::select("user_type[]",user_type(),null,array("multiple"=>true,"class"=>"form-control m-b user_type"))}}';
                    html +='   </div>';
                    html +='   </div>';
                    html +='  <div class="col-sm-6">';
                    html +='   <div class="form-group">';
                    html +='  <label class="col-sm-2 control-label">Description</label>';
                    html +='  <div class="col-sm-10">';
                    // html +='    {{Form::textarea("description[]",null,array("class"=>"form-control description m-b","id"=>"description","placeholder"=>"Description","rows"=>"2",'required'=>'required'))}}';
                    html+='<textarea class="form-control description m-b " id="description" placeholder="Description" rows="2" required="required" name="description['+value+']" cols="50" aria-required="true" aria-invalid="true"></textarea>';
                    html +='  </div>';
                    html +=' </div>';
                    html +='  </div>';
                    html +=' </div>';

                    $(wrapper).append('<div class="clearfix">' + html + '<a href="#" class="remove_field">x</a></div></div>'); //add input box
                    // auto fill previous value
                    var course_id=localStorage.getItem('course');
                    var subject_id=localStorage.getItem('subject');
                    var chapter_id=localStorage.getItem('chapter');
                    var topic_id=localStorage.getItem('topic');
                    var type=localStorage.getItem('doc_type');
                    updateLocalValue('course',course_id,value,subject_id)
                    updateLocalValue('subject',subject_id,value,chapter_id)
                    updateLocalValue('chapter',chapter_id,value,topic_id)

                    if(type!=null){
                        $('input[type="radio"][name="doc_type['+value+']"]').each(function(){
                          if($(this).val()==type+value)
                           $(this).prop('checked', true);
                            func_type_check(this)
                        });
                    }
                       //end auto fill value
                    CKEDITOR.replace('pdf_content'+value);
                     value++; //text box increment
                    @php  $no=$no+1;  @endphp
                    console.log(value,"{{$no}}")
                     
                }
            });

            $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                value--;
                 @php  $no--;  @endphp
            })
        });
    
         
        function showResponse(data, statusText, xhr, $form)  {

            console.log(data)

            if (data.status == 200) {
                $(".progress-bar").addClass('progress-bar-success');
                window.location.href="{{url('document-list')}}";

            } else {
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

        $(document).ready(function () {
            $(".user_type").multiselect({
                includeSelectAllOption: true,
            });
            //CKEDITOR.replace( 'description' );
            CKEDITOR.replace('pdf_content0');
        });

    $(document).on('click', '.type-check', function () {
            var info=this;
        func_type_check(info);
        });
        $(document).on('click', '.video-check', function () {
            var vid_info=this;
            func_video_check(vid_info);
        });
        function func_type_check(data) {
            var lastChar = data.value[data.value.length -1];
            var typeValue=data.value.substring(0,data.value.length - 1);
            console.log(data,typeValue,lastChar);
            localStorage.setItem('doc_type', typeValue);
            if (typeValue) {

                if (typeValue == 'Audio_Text') {
                    $(".Audio"+lastChar).show();
                    $(".Text"+lastChar).show();
                    $(".Video"+lastChar).hide();
                }
                else if (typeValue == 'Video_Text') {
                    $(".Video"+lastChar).show();
                    $(".Text"+lastChar).show();
                    $(".Audio"+lastChar).hide();
                }
                else if (typeValue == 'Audio') {
                    $(".Video"+lastChar).hide();
                    $(".Text"+lastChar).hide();
                    $("." + typeValue+lastChar).show();
                }
                else if (typeValue == 'Video') {
                    $(".Audio"+lastChar).hide();
                    $(".Text"+lastChar).hide();
                    $("." + typeValue+lastChar).show();
                }
                else if (typeValue == 'Text') {
                    $(".Video"+lastChar).hide();
                    $(".Audio"+lastChar).hide();
                    $("." + typeValue+lastChar).show();
                }


            } else {
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
        function func_video_check(vid_info) {

            console.log(vid_info);
            $('.'+$(vid_info).val()).show();
            var url='url'+$(vid_info).data('value')
            var file='file'+$(vid_info).data('value')
            
            if(vid_info.value==url){
               $('.'+file).hide();
            }
            else if(vid_info.value==file){
             $('.'+url).hide();   
            }
            /*$('input[type=radio]').each(function () {
                if (this.checked)
                    $("." + $(this).val()).show();
                else
                    $("." + $(this).val()).hide();
            });*/
        }
$(document).on("change",".course-multi",function(){
        var id=$(this).val()
        var stateId=$(this).data('id')
       changeDropDown('course',id,stateId)
    });

    $(document).on("change",".subject-multi",function(){
        var id=$(this).val()
        var stateId=$(this).data('id')
       changeDropDown('subject',id,stateId)
    });
    $(document).on("change",".chapter-multi",function(){
         var id=$(this).val()
        var stateId=$(this).data('id')
       changeDropDown('chapter',id,stateId)
    });
    $(document).on("change",".topic-multi",function(){
         var id=$(this).val()
        var stateId=$(this).data('id')
       changeDropDown('topic',id,stateId)
    });

    function changeDropDown(type,id,stateId){
        switch(type){
            case 'course':
            $('#subject'+stateId).html('<option selected disabled>Select Subject</option>');
            $('#chapter'+stateId).html('<option selected disabled>Select Chapter</option>');
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
                localStorage.setItem('course', id);
                $.ajax({
                    url: url+"/getSubjectByCourseId/"+id,
                    success:function(response){
                        var data=$.parseJSON(response);
                        
                        var data2=''
                        $.each(data, function( index, value ) {
                            
                                data2+='<option value='+index+'>'+value+'</option>'
                        });
                        $('#subject'+stateId).append(data2)
                    }
                });
            break;
            case 'subject':
            $('#chapter'+stateId).html('<option selected disabled>Select Chapter</option>');
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
            localStorage.setItem('subject', id);
            $.ajax({
                url: url+"/getChapterBySubjectId/"+id,
                success:function(response){
                    var data=$.parseJSON(response);
                    /*$(".chapter").append('<option selected disabled>Select Chapter</option>')*/
                  
                        var data2=''
                        $.each(data, function( index, value ) {
                            
                                data2+='<option value='+index+'>'+value+'</option>'
                        });
                        $('#chapter'+stateId).append(data2)
                    }
                });
            break;
            case 'chapter':
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
            localStorage.setItem('chapter', id);
    
            $.ajax({
                url: url+"/getTopicByChapterId/"+id,
                success:function(response){
                    var data=$.parseJSON(response);
                    var data2=''
                        $.each(data, function( index, value ) {
                            
                                data2+='<option value='+index+'>'+value+'</option>'
                        });
                        $('#topic'+stateId).append(data2)
                }
            });
            break;
            case 'topic':
             localStorage.setItem('topic', id);
            break;
            
        }
    }
    function updateLocalValue(type,id,stateId,nextId){
         var selected='';
        switch(type){
            case 'course':
            $('#course'+stateId).val(id);
            $('#subject'+stateId).html('<option selected disabled>Select Subject</option>');
            $('#chapter'+stateId).html('<option selected disabled>Select Chapter</option>');
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
                
                $.ajax({
                    url: url+"/getSubjectByCourseId/"+id,
                    success:function(response){
                        var data=$.parseJSON(response);
                        
                        var data2=''
                        $.each(data, function( index, value ) {
                                var selected='';
                                if(nextId==index){
                                    selected='selected';
                                }
                                data2+='<option value="'+index+'" '+selected+'>'+value+'</option>'
                        });
                        $('#subject'+stateId).append(data2)
                    }
                });
            break;
            case 'subject':
            $('#chapter'+stateId).html('<option selected disabled>Select Chapter</option>');
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
            
            $.ajax({
                url: url+"/getChapterBySubjectId/"+id,
                success:function(response){
                    var data=$.parseJSON(response);
                    /*$(".chapter").append('<option selected disabled>Select Chapter</option>')*/
                  
                        var data2=''
                        $.each(data, function( index, value ) {
                             var selected='';
                            if(nextId==index){
                                    selected='selected';
                                }
                                data2+='<option value="'+index+'" '+selected+'>'+value+'</option>'
                        });
                        $('#chapter'+stateId).append(data2)
                    }
                });
            break;
            case 'chapter':
            $('#topic'+stateId).html('<option selected disabled>Select Topic</option>');
            
    
            $.ajax({
                url: url+"/getTopicByChapterId/"+id,
                success:function(response){
                    var data=$.parseJSON(response);
                    var data2=''
                        $.each(data, function( index, value ) {
                             var selected='';
                            if(nextId==index){
                                    selected='selected';
                                }
                                data2+='<option value="'+index+'" '+selected+'>'+value+'</option>'
                        });
                        $('#topic'+stateId).append(data2)
                }
            });
            break;
            
            
        }

    }


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
        .multi_fields{
            max-height: 500px !important;
            overflow: auto;
        }
        .top_btn {
            text-align: right;
            margin-bottom: 20px;
            width: 30px;
            height: 30px;
            position: absolute;
            right: 50px;
            bottom: 100px;
        }
        .multi_fields .panel-body {
            padding-right: 70px;
            position: static;
        }
        a.remove_field{
            display: inline-block;
            padding: 3px 15px;
            text-align: right;
            background: #c0392b;
            color: #fff;
            float: right;
            margin-top: -15px;
        }
        .multi_fields .clearfix{
            margin: 10px 0px;
        }
    </style>
@endsection