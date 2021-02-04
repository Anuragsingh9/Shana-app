@extends('layouts.master')
@section('content')

    <div class="normalheader transition animated fadeIn small-header">
        <div class="hpanel">
            <div class="panel-body">
               <!--  <a class="small-header-action" href="#">
                    <div class="clip-header">
                        <i class="fa fa-arrow-up"></i>
                    </div>
                </a> -->

                <h2 class="font-light m-b-xs">
                    Edit @if($siteInfo->type!='term'){{strtoupper($siteInfo->type).' Us'}} @else
                        {{'Term & Conditions'}}
                             @endif
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

                        </div>
                        <br/>
                    </div>
                    <div class="panel-body">
                        @if(!empty($siteInfo))
                            {{Form::model($siteInfo,['url' => url('update-site-info',$siteInfo->id),'class'=>'form-horizontal','files'=>true,'id'=>'course-form'])}}
                            {{Form::hidden('id',$siteInfo->id,array('id'=>'id'))}}
                        @else
                            {{Form::open(['url' => url('insert-course'),'class'=>'form-horizontal','id'=>'course-form','files'=>true])}}
                            {{Form::hidden('id','',array('id'=>'id'))}}
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-4">
                                {{Form::text('title',null,array('class'=>'form-control m-b','placeholder'=>'Please Enter Title'))}}
                            </div>
                        </div>
                            <div class="form-group " >
                                <label class="col-sm-2 control-label">Content</label>
                                <div class="col-sm-10">
                                    {{Form::textarea('content_data',null,array('class'=>'form-control m-b','id'=>'pdf_content','placeholder'=>'Content','rows'=>'20'))}}
                                    {{-- {{Form::file('pdf_file',null)}} --}}
                                </div>
                            </div>

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">

                                {{Form::submit('Update',array('class'=>'btn btn-info '))}}
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{publicAsset('ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'pdf_content' );


    </script>
@endsection