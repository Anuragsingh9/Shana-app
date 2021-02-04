@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="#">
                <div class="clip-header">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </a>

            <!--<div id="hbreadcrumb" class="pull-right m-t-lg">-->
            <!--    <ol class="hbreadcrumb breadcrumb">-->
            <!--        <li><a href="#">Dashboard</a></li>-->
            <!--        <li>-->
            <!--            <span></span>-->
            <!--        </li>-->
            <!--        <li class="active">-->
            <!--            <span></span>-->
            <!--        </li>-->
            <!--    </ol>-->
            <!--</div>-->
            <h2 class="font-light m-b-xs">
                Add Splash Image
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>

                    </div>

                </div>
                <div class="panel-body">
                    @if(!empty($document))
                        {{Form::model($document,['url' => url('update-document',$document->id),'class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                        {{Form::hidden('id',$document->id,array('id'=>'id'))}}
                    @else
                        {{Form::model($splash,['url' => url('update-splash',$splash->id),'class'=>'form-horizontal','files'=>true,'id'=>'document-form'])}}
                        {{Form::hidden('id',$splash->id,array('id'=>'id'))}}
                    @endif

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Front Image</label>
                            <div class="col-sm-10">
                                @if(isset($document->preview_image))
                                    <img src="{{asset('uploads/preview_image/'.$document->preview_image)}}" class="img-responsive" width="110" height="200" />
                                @endif
                                {{Form::file('preview',null)}}
                                
                            </div>                                
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                {{Form::reset('Cancel',array('class'=>'btn btn-default'))}}
                                {{Form::submit('Save',array('class'=>'btn btn-primary'))}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // $(document).ready(function() {
    //     $(".user_type").multiselect({
    //         includeSelectAllOption: true,
    //     });
    //     //CKEDITOR.replace( 'description' );
    //     CKEDITOR.replace( 'pdf_content' );
    //     @if(isset($document))
    //         var course_id="{{$document->course_id or ''}}";
    //         var subject_id="{{$document->subject_id or ''}}";
    //         var chapter_id="{{$document->chapter_id or ''}}";
    //         var topic_id="{{$document->topic_id or ''}}";

    //         changeCourse(course_id,subject_id);
    //         changeSubject(subject_id,chapter_id);
    //         changeChapter(chapter_id,topic_id);
    //     @endif
    // });
    // @if(isset($document))
    //     var user_type="{!! $document->user_type !!}";
    //     var doc_type="{{ $document->doc_type }}";
    //     var result = user_type.split(',');
    //     $(".user_type").val(result);
    //     $('input[type=checkbox]').each(function() {
    //         if($(this).val()==doc_type)
    //             $(this).prop('checked', true);
    //         func_type_check();
    //     });
    //     $(".doc_type_text").val(doc_type);
    // @endif
    // $(document).on('click','.type-check',function(){
    //     func_type_check();
    // });
    // $(document).on('click','.video-check',function(){
    //     func_video_check();   
    // });
    // function func_type_check(){
    //     $('input[type=checkbox]').each(function () {
    //         if (this.checked)
    //             $("."+$(this).val()).show(); 
    //         else
    //             $("."+$(this).val()).hide();
    //     });
    // }
    // function func_video_check(){
    //     $('input[type=radio]').each(function () {
    //         if (this.checked)
    //             $("."+$(this).val()).show(); 
    //         else
    //             $("."+$(this).val()).hide();
    //         });
    // }
</script>
@endsection