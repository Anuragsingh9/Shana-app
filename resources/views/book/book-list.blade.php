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
                Blog List
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
                    <li class=""><a data-toggle="tab" href="#delete">Deleted</a></li>
                </ul>
                <div class="tab-content">
                    <div id="all" class="tab-pane active">
                <div class="panel-body">
                    <div class="table-responsive">
                    {{Form::open(['url' => url('multiple-book-delete'),'id'=>'multiple-book-form'])}}
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Images</th>
                            <th>Document Type</th>
                            <th>Author Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th colspan="3" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($book as $value)
                        <tr>
                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_book_d'))}}</td>
                            <td>{{$value->title}}</td>
                            <td>{{$value->description}}</td>
                            <td><img src="{{cloudUrl($value->preview)}}" width="100" height="100"></td>
                            <td>{{$value->doc_type}}</td>
                            <td>{{($value->author_name!=null)?$value->author_name:''}}</td>
                            <td><i class="fa @if($value->status==0){{'fa-times'}}@else{{'fa-check'}}@endif" style="color:@if($value->status==0){{'red'}}@else{{'green'}}@endif" aria-hidden="true"></i></td>
                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                            <td>
                                <a href="{{url('edit-book/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a></td>
                                <td><a class="update-book-status btn btn-xs btn-default" data-id="{{$value->id}}">@if($value->status==0){{'Activate'}}@else{{'Deactivate'}}@endif</a></td>
                                <td><a class="delete-book btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{Form::submit("Delete Multiple",array("class"=>'btn btn-primary pull-right mdelete','disabled'=>'disabled'))}}
                    {{Form::close()}}
                    </div>
                </div>
            </div>
            <div id="delete" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {{Form::open(['url' => url('multiple-book-restore'),'id'=>'multiple-course-form'])}}
                                <table cellpadding="1" cellspacing="1" class="table" id="course-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Document Type</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    
                                    @php $i=1 @endphp
                                    @foreach($bookDelete as $value)
                                    <tr>
                                        <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_book_r'))}}</td>
                                        
                                        <td>{{$value->title}}</td>
                                        <td>{{$value->description}}</td>
                                        <td><img src="{{cloudUrl($value->preview)}}" width="100" height="100"></td>
                                        <td>{{$value->doc_type}}</td>
                                        <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                        <td>
                                            <a class="restore-book btn btn-xs btn-danger" data-id="{{$value->id}}">Restore</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>   
                                </table>
                                {{Form::submit("Restore Multiple",array("class"=>'btn btn-primary pull-right mrestore','disabled'=>'disables'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                  {{--   {{ $book->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
$(document).ready(function() {
     $('.chk_book_d').change(function() {
        var allCheckBox = $(".chk_book_d")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.mdelete').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.mdelete').removeAttr('disabled')
        } else{     
            $('.mdelete').removeAttr('disabled')
        }
});
$('.chk_book_r').change(function() {
        var allCheckBox = $(".chk_book_r")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.mrestore').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.mrestore').removeAttr('disabled')
        } else{     
            $('.mrestore').removeAttr('disabled')
        }
});
    
});
</script>
@endsection
