@extends('layouts.master')
@section('content')
<!-- <div class="normalheader transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="#">
                <div class="clip-header">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </a>

            <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="index-2.html">Dashboard</a></li>
                    <li>
                        <span>Document</span>
                    </li>
                    <li class="active">
                        <span>Tables design</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Home Images
            </h2>
        </div>
    </div>
</div> -->

<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
               Plan Slider Images
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
                        <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a> -->

                    </div>

                </div>
                <div class="panel-body">
                    @if(!empty($document))
                        {{Form::model($document,['url' => url('update-plan-images',$document->id),'class'=>'form-horizontal','files'=>true,'id'=>'home-image-form'])}}
                        {{Form::hidden('id',$document->id,array('id'=>'id'))}}
                    @else
                        {{Form::open(['url' => url('insert-plan-images'),'class'=>'form-horizontal','id'=>'home-image-form','files'=>true])}}
                        {{Form::hidden('id','',array('id'=>'id'))}}

                    @endif

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-5">

                                {{Form::file('image',null)}}
                                 <span>Image Size must be 500X275 </span>
                            </div>
                            @if(isset($document->image))
                                <div class="col-sm-5">
                                <img src="{{cloudUrl($document->image)}}" class="img-responsive" width="110" height="200" />
                                </div>
                                    @endif
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

</script>
@endsection