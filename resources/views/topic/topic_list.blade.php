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
                    <li><a href="#">Dashboard</a></li>
                    <li>
                        <span>Topic</span>
                    </li>
                    <li class="active">
                        <span>Topic List</span>
                    </li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Topic List
            </h2>
           
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                 <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#all">All</a></li>
                    <li class=""><a data-toggle="tab" href="#delete">Delete</a></li>
                </ul>
                <div class="tab-content">
                    <div id="all" class="tab-pane active">
                <div class="panel-body">
                    <div class="table-responsive">
                    {{Form::open(['url' => url('multiple-topic-delete'),'id'=>'multiple-topic-form'])}}
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Chapter</th>
                            <th>Topic</th>
                           <!--  <th>Image</th> -->
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($topic as $value)
                        <tr>
                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>''))}}</td>
                            <td>{{isset($value->chapter) ? $value->chapter->chapter : ''}}</td>
                            <td>{{$value->topic}}</td>
                           <!--  {{--<td><img src="{{getImgSrc('uploads/topic/',$value->image)}}" width="100" height="100"></td>--}}
                            <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                            <td><i class="check-status fa @if($value->status==0){{'fa-times'}}@else{{'fa-check'}}@endif" style="color:@if($value->status==0){{'red'}}@else{{'green'}}@endif" aria-hidden="true"></i></td>

                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                            <td>
                                <a href="{{url('edit-topic/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a>
                                <a class="update-topic-status btn btn-xs btn-default" data-id="{{$value->id}}">@if($value->status==0){{'Actived'}}@else{{'Deactived'}}@endif</a>
                                <a class="delete-topic btn btn-xs btn-danger" data-id="{{$value->id}}" >Delete</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{Form::submit("Delete",array("class"=>'btn btn-primary pull-right'))}}
                    {{Form::close()}}
                    </div>
                </div>
            </div>
            <div id="delete" class="tab-pane ">
                <div class="panel-body">
                    <div class="table-responsive">
                    {{Form::open(['url' => url('multiple-topic-restore'),'id'=>'multiple-topic-form'])}}
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Chapter</th>
                            <th>Topic</th>
                           <!--  <th>Image</th> -->
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($topicDelete as $value)
                        <tr>
                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>''))}}</td>
                            <td>{{isset($value->chapter) ? $value->chapter->chapter : ''}}</td>
                            <td>{{$value->topic}}</td>
                           <!--  {{--<td><img src="{{getImgSrc('uploads/topic/',$value->image)}}" width="100" height="100"></td>--}}
                            <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                            <!-- <td><i class="fa @if($value->status==0){{'fa-times'}}@else{{'fa-check'}}@endif" style="color:@if($value->status==0){{'red'}}@else{{'green'}}@endif" aria-hidden="true"></i></td> -->

                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                            <td>
                                <a class="restore-topic btn btn-xs btn-danger" data-id="{{$value->id}}" >Restore</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{Form::submit("Restore",array("class"=>'btn btn-primary pull-right'))}}
                    {{Form::close()}}
                    </div>

                </div>
            </div>
                    {{ $topic->links() }}
            </div>
        </div>
    </div>
</div>
@endsection