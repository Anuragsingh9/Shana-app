@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
            <!-- <a class="small-header-action" href="#">
                <div class="clip-header">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </a> -->

            <!-- <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="active">
                        <span>Topic</span>
                    </li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Topic
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
                        <a class="btn btn-primary" href={{url('/document')}}>Add Document</a>
                        <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                   <br/>
                </div>
                <div class="panel-body">
                    @if(!empty($topic))
                        {{Form::model($topic,['url' => url('update-topic',$topic->id),'class'=>'form-horizontal','id'=>'topic-form','files'=>true])}}
                        {{Form::hidden('id',$topic->id,array('id'=>'id'))}}
                    @else
                        {{Form::open(['url' => url('insert-topic'), 'method' => 'post','class'=>'form-horizontal','id'=>'topic-form','files'=>true])}}
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
                                {{Form::select('chapter_id',[],null,array('class'=>'form-control m-b chapter','placeholder'=>'Select Chapter'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Topic</label>
                            <div class="col-sm-10">
                                {{Form::text('topic',null,array('class'=>'form-control m-b','placeholder'=>'Topic'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10">
                                 <img src="{{isset($topic->image)?CreateTemporaryURL($topic->image):''}}" id="blah" class="img-responsive {{ isset($topic->image)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('file',null)}}
                                <span>Image Size must be 500X275 </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Banner Image</label>
                            <div class="col-sm-10">

                                <img src="{{isset($course->banner_img)?cloudUrl($course->banner_img):''}}" id="blah" class="img-responsive {{ isset($course->banner_img)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('banner_img_file',null)}}
                                <span>Banner Image Size must be 500X275 </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Icon Image</label>
                            <div class="col-sm-10">

                                <img src="{{isset($course->icon_img)?cloudUrl($course->icon_img):''}}" id="blah" class="img-responsive {{ isset($course->icon_img)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('icon_img_file',null)}}
                                <span>Icon Image Size must be 250X250 </span>
                            </div>
                        </div>


                        <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <label>{{Form::radio("status","1",false,array("class"=>''))}}Enable</label>
                                    <label>{{Form::radio("status","0",false,array("class"=>''))}}Disable</label>
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
$(document).ready(function(){
    $('input[name=file]').on('change',function(e){
            console.log(this)
            var input=this;
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            $('#blah').removeClass('hidden')
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(75)
                    .height(75);
            };

            reader.readAsDataURL(input.files[0]);
        }
        })
    @if(!empty($topic))
        var subject_id="{{$topic->subject_id or ''}}";
        var course_id="{{$topic->course_id or ''}}";
        var chapter_id="{{$topic->chapter_id or ''}}";
        changeCourse(course_id,subject_id);
        changeSubject(subject_id,chapter_id);
    @endif
});

</script>
@endsection