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
                        <span>Chapter</span>
                    </li>
                    <li class="active">
                        <span>Chapter List</span>
                    </li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Chapter List
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
                    {{Form::open(['url' => url('multiple-chapter-delete'),'id'=>'multiple-chapter-form'])}}
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <!-- <th>Image</th> -->
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($chapter as $value)
                        <tr>
                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_chapter_d'))}}</td>
                            <td>{{(isset($value->subject)) ? $value->subject->subject : ''}}</td>
                            <td>{{$value->chapter}}</td>
                           {{-- <td><img src="{{getImgSrc('uploads/chapter/',$value->image)}}" width="100" height="100"></td>--}}
                            <!-- <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                            <td><i class="fa @if($value->status==0){{'fa-times'}}@else{{'fa-check'}}@endif" style="color:@if($value->status==0){{'red'}}@else{{'green'}}@endif" aria-hidden="true"></i></td>
                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                            <td>
                                <a href="{{url('edit-chapter/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a>
                                <a class="update-chapter-status btn btn-xs btn-default" data-id="{{$value->id}}">@if($value->status==0){{'Actived'}}@else{{'Deactived'}}@endif</a>
                                <a class="delete-chapter btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{Form::submit("Delete Multiple",array("class"=>'btn btn-primary chapterDel pull-right','disabled'=>'disabled'))}}
                    {{Form::close()}}
                    </div>
                </div>
            </div>
            <div id="delete" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {{Form::open(['url' => url('multiple-course-restore'),'id'=>'multiple-course-form'])}}
                                <table cellpadding="1" cellspacing="1" class="table" id="course-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>chapter</th>
                                      <!-- <th>Image</th> -->
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @php $i=1 @endphp
                                    @foreach($chapterDelete as $value)
                                    <tr>
                                        <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_chpter_r'))}}</td>
                                        <td>{{(isset($value->subject)) ? $value->subject->subject : ''}}</td>
                                        <td>{{$value->chapter}}</td>
                                        <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                        <td>
                                            <a class="restore-chapter btn btn-xs btn-danger" data-id="{{$value->id}}">Restore</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>   
                                </table>
                                {{Form::submit("Restore Multiple",array("class"=>'btn btn-primary pull-right chapterRes','disabled'=>'disabled'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    {{ $chapter->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
     $('.chk_chapter_d').change(function() {
        var allCheckBox = $(".chk_chapter_d")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.chapterDel').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.chapterDel').removeAttr('disabled')
        } else{     
            $('.chapterDel').removeAttr('disabled')
        }
});
$('.chk_chpter_r').change(function() {
        var allCheckBox = $("chk_chpter_r")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.chapterRes').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.chapterRes').removeAttr('disabled')
        } else{     
            $('.chapterRes').removeAttr('disabled')
        }
});
    
});
</script>
@endsection