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
                        <span>Plan</span>
                    </li>
                    <li class="active">
                        <span>Tables design</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Plan
            </h2>
             <small>Examples of various designs of tables.</small>
        </div>
    </div>
</div>

 -->

<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Plan
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
                        <!-- <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    Add Plan 
                </div>
                <div class="panel-body">
                    @if(!empty($plan))
                        {{Form::model($plan,['url' => url('update-plan',$plan->id),'class'=>'form-horizontal','id'=>'plan-form','files'=>true])}}
                    @else
                        {{Form::open(['url' => url('insert-plan'),'class'=>'form-horizontal','id'=>'plan-form','files'=>true])}}
                    @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                {{Form::text('name',null,array('class'=>'form-control m-b','placeholder'=>'Plan Name'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Duration</label>
                            <div class="col-sm-5">
                                {{Form::text('duration',null,array('class'=>'form-control m-b','placeholder'=>'Duration'))}}
                            </div>
                            <div class="col-sm-5">
                                {{Form::select('field',['Year'=>'Year','Month'=>'Month'],null,array('class'=>'form-control m-b'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Amount</label>
                            <div class="col-sm-4">
                                {{Form::text('amount',null,array('class'=>'form-control m-b','placeholder'=>'Plan Amount'))}}
                            </div>
                            <label class="col-sm-2 control-label">Plan For Number Of Users</label>
                            <div class="col-sm-4">
                                {{Form::number('num_users',1,array('class'=>'form-control m-b','placeholder'=>'Number of Users'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-4">
                                {{Form::textarea('description',null,array('class'=>'form-control m-b','id'=>'description','placeholder'=>'Description','rows'=>'2'))}}
                            </div>
                            <label class="col-sm-2 control-label">Preview Image</label>
                            <div class="col-sm-4">
                                @if(isset($plan->image))
                                    <img src="{{cloudUrl($plan->image)}}" class="img-responsive" width="110" height="200" />
                                @endif
                                {{Form::file('preview',null)}}
                                <span>Preview Image Size must be 250X250 </span>
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
    
</script>
@endsection