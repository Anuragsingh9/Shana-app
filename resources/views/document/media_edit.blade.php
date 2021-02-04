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
    <div class="row">
        
        <div class="col-lg-12">
            <div class="hpanel">
                       
                <div class="panel-body">
                        {{Form::model($document->document,['url' => url('update-media'),'class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                        {{Form::hidden('id',$document->id,array('id'=>'id'))}}
                        {{Form::hidden('doc_id',$document->document->id,array('id'=>'id'))}}
                    
                         <div class="form-group">
                            <label class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-10">
                                <div >{{Form::select('course_id',$course,null,array('class'=>'form-control m-b course','placeholder'=>'Select Course'))}}
                                    {{-- isset($document->document->course)?$document->document->course->course:'' --}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Course</label>
                            <div class="col-sm-10">
                                 <div >
                                    {{Form::select('subject_id',[],null,array('class'=>'form-control m-b subject','placeholder'=>'Select Subject'))}}
                                    {{-- isset($document->document->subject)?$document->document->subject->subject:''  --}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Chapter</label>
                            <div class="col-sm-10">
                                <div>
                                    {{Form::select('chapter_id',[],null,array('class'=>'form-control m-b chapter chapter','placeholder'=>'Select Chapter'))}}
                                    {{-- isset($document->document->chapter)?$document->document->chapter->chapter:'' --}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Topic</label>
                            <div class="col-sm-10">
                                <div >
                                    {{Form::select('topic_id',[],null,array('class'=>'form-control m-b topic','placeholder'=>'Select Topic'))}}
                                    {{-- isset($document->document->topic)?$document->document->topic->topic:'' --}}</div>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Video',($document->doc_type=='Video')?true:false,array("class"=> "type-check"))}}Video</label>
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Audio',($document->doc_type=='Audio')?true:false,array("class"=> "type-check"))}}Audio</label>
                                    <label class="checkbox-inline">{{Form::checkbox('doc_type[]','Text',($document->doc_type=='Text')?true:false,array("class"=> "type-check"))}}Text</label>
                            </div>
                        </div>
                        <div class="Video" style="display:none;">
                            <div class="form-group hidden">
                                <label class="col-sm-2 control-label">File/Url</label>
                                <div class="col-sm-10">
                                    {{-- <label class="checkbox-inline">{{Form::radio('video_type','file',false,array("class"=>"video-check"))}}File</label> --}}
                                    <label class="checkbox-inline">{{Form::radio('video_type','url',($document->doc_type=='Video')?true:false,array("class"=>"video-check"))}}Url</label>
                                </div>                                
                            </div>
                            <div class="form-group file" style="display:none;">
                                <label class="col-sm-2 control-label">Video File</label>
                                <div class="col-sm-10">
                                    {{Form::file('video_file',null)}}
                                </div>                                
                            </div>
                            <div class="form-group url" style="display:($document->doc_type=='Video')?'':none;">
                                <label class="col-sm-2 control-label">Video Url</label>
                                <div class="col-sm-10">
                                    {{Form::text('video_url',$document->doc_url,array('class'=>'form-control m-b ','placeholder'=>'URL'))}}
                                </div>
                            </div>
                        </div>
                        <div class="form-group Audio" style="display:none;">
                            <label class="col-sm-2 control-label">Audio File</label>
                            <div class="col-sm-10">
                                @if($document->doc_type=='Audio')
                                    Audio File:{{$document->doc_file}}
                                @endif
                                {{Form::file('audio_file',null)}}
                            </div>                                
                        </div>
                        <div class="form-group Text" style="display:none;">
                            <label class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-10">
                                {{Form::textarea('content',($document->doc_type=='Text')?$document->content:'',array('class'=>'form-control m-b','id'=>'pdf_content','placeholder'=>'Content','rows'=>'2'))}}
                                {{-- {{Form::file('pdf_file',null)}} --}}
                            </div>                                
                        </div>
                       <!--  <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                {{Form::text('title',null,array('class'=>'form-control m-b','placeholder'=>'Title'))}}
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
                                    <img src="{{$document->preview_image}}" class="img-responsive" width="110" height="200" />
                                @endif
                                {{Form::file('preview',null)}}
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                {{Form::textarea('description',null,array('class'=>'form-control m-b','id'=>'description','placeholder'=>'Description','rows'=>'2'))}}
                            </div>
                        </div> -->
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                {{Form::reset('Cancel',array('class'=>'btn btn-default'))}}
                                {{Form::submit('Update',array('class'=>'btn btn-primary'))}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".user_type").multiselect({
            includeSelectAllOption: true,
        });
        //CKEDITOR.replace( 'description' );
        CKEDITOR.replace( 'pdf_content' );
        @if(isset($document))
            var course_id="{{$document->document->course_id or ''}}";
            var subject_id="{{$document->document->subject_id or ''}}";
            var chapter_id="{{$document->document->chapter_id or ''}}";
            var topic_id="{{$document->document->topic_id or ''}}";

            changeCourse(course_id,subject_id);
            changeSubject(subject_id,chapter_id);
            changeChapter(chapter_id,topic_id);
        @endif
    });
    @if(isset($document->document))
        var user_type="{!! $document->document->user_type !!}";
        var doc_type="{{ $document->document->doc_type }}";
        var result = user_type.split(',');
        $(".user_type").val(result);
        $('input[type=checkbox]').each(function() {
            if($(this).val()==doc_type)
                $(this).prop('checked', true);
            func_type_check();
        });
        $(".doc_type_text").val(doc_type);
    @endif
    $(document).on('click','.type-check',function(){
        func_type_check();
    });
    $(document).on('click','.video-check',function(){
        func_video_check();   
    });
    function func_type_check(){
        $('input[type=checkbox]').each(function () {
            if (this.checked)
                $("."+$(this).val()).show(); 
            else
                $("."+$(this).val()).hide();
        });
    }
    function func_video_check(){
        $('input[type=radio]').each(function () {
            if (this.checked)
                $("."+$(this).val()).show(); 
            else
                $("."+$(this).val()).hide();
            });
    }
</script>
@endsection