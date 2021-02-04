@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="#">
                <div class="clip-header">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </a>

            <!-- <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li>
                        <span>Course</span>
                    </li>
                    <li class="active">
                        <span>Course List</span>
                    </li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Course List
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
                    <li class=""><a data-toggle="tab" href="#free">Free</a></li>
                    <li class=""><a data-toggle="tab" href="#paid">Paid</a></li>
                    <li class=""><a data-toggle="tab" href="#delete">Delete</a></li>
                </ul>
                <div class="tab-content">
                    <div id="all" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {{Form::open(['url' => url('multiple-subject-delete'),'id'=>'multiple-subject-form'])}}
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject</th>
                                        <th>Course</th>
                                        <th>Type</th>
                                       <!--  <th>Image</th> -->
                                        <th>Status</th>
                                        <th>Content Type</th>
                                        <th>Created At</th>
                                        <th colspan="3" class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1 @endphp
                                    @foreach($subject as $value)
                                    <tr>
                                        <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_course_d'))}}</td>
                                        <td>{{isset($value->course) ? $value->course->course : ''}}</td>
                                        <td>{{$value->subject}}</td>
                                        <td>
                                            <strong>{{$value->type}}</strong>
                                            @if($value->type=="Paid")
                                                <li> Duration : {{$value->duration}} {{$value->field}} </li>
                                                <li> Amount : {{$value->amount}} </li>
                                            @endif 
                                        </td>
                                        <!-- <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                                        <td><i class="check-status fa @if($value->status==0){{'fa-times'}}@else{{'fa-check'}}@endif" style="color:@if($value->status==0){{'red'}}@else{{'green'}}@endif" aria-hidden="true"></i></td>
                                          <td>@if($value->data_type==0){{'Educational'}}@else{{'Spiritual'}}@endif </td>
                                        <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                        <td>
                                            <a href="{{url('edit-subject/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a></td>
                                            <td><a class="update-subject-status btn btn-xs btn-default" data-id="{{$value->id}}">@if($value->status==0){{'Actived'}}@else{{'Deactived'}}@endif</a></td>
                                                <td><a class="delete-subject btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{Form::submit("Delete Multiple",array("class"=>'btn btn-primary pull-right courseDelete','disabled'=>'disabled'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    <div id="free" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {{Form::open(['url' => url('multiple-subject-delete'),'id'=>'multiple-subject-form'])}}
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject</th>
                                        <th>Course</th>
                                       <!--  <th>Image</th> -->
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th colspan="3">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1 @endphp
                                    @foreach($subject as $value)
                                        @if($value->type=="Free")
                                        <tr>
                                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_course_f'))}}</td>
                                            <td>{{isset($value->course) ? $value->course->course : ''}}</td>
                                            <td>{{$value->subject}}</td>
                                            <!-- <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                                            <td>@if($value->status==0){{'Disabled'}}@else{{'Enabled'}}@endif</td>
                                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                            <td>
                                                <a href="{{url('edit-subject/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a></td>
                                            <td><a class="update-subject-status btn btn-xs btn-default" data-id="{{$value->id}}">Status</a></td>
                                            <td><a class="delete-subject btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                {{Form::submit("Delete Multiple",array("class"=>'btn btn-primary pull-right courseFree','disabled'=>'disabled'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    <div id="paid" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                {{Form::open(['url' => url('multiple-subject-delete'),'id'=>'multiple-subject-form'])}}
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Course</th>
                                        <th>Course</th>
                                        <th>Duration</th>
                                        <th>Amount</th>
                                       <!--  <th>Image</th> -->
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th colspan="3">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=1 @endphp
                                    @foreach($subject as $value)
                                        @if($value->type=="Paid")
                                        <tr>
                                            <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_course_p'))}}</td>
                                            <td>{{isset($value->course) ? $value->course->course : ''}}</td>
                                            <td>{{$value->subject}}</td>
                                            <td>{{$value->duration}} {{$value->field}} </td>
                                            <td>{{$value->amount}} </td>
                                           <!-- {{-- <td><img src="{{getImgSrc('uploads/subject/',$value->image)}}"></td>--}}
                                            <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                                            <td>@if($value->status==0){{'Disabled'}}@else{{'Enabled'}}@endif</td>
                                            <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                            <td>
                                                <a href="{{url('edit-subject/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a></td>
                                            <td>    <a class="update-subject-status btn btn-xs btn-default" data-id="{{$value->id}}">Status</a></td>
                                            <td>    <a class="delete-subject btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                {{Form::submit("Delete Multiple",array("class"=>'btn btn-primary pull-right CoursePaid','disabled'=>'disabled'))}}
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
                                        <th>Type</th>
                                      <!--   <th>Image</th> -->
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php $i=1 @endphp

                                    @foreach($subjectDelete as $value)
                                    <tr>
                                        <td>{{Form::checkbox('id[]',$value->id,false,array('class'=>'chk_course chk_course_r'))}}</td>
                                       <td>{{$value->subject}}</td>
                                        <td>
                                            <strong>{{$value->type}}</strong>
                                            @if($value->type=="Paid")
                                                <li> Duration : {{$value->duration}} {{$value->field}} </li>
                                                <li> Amount : {{$value->amount}} </li>
                                            @endif 
                                        </td>
                                       <!--  <td><img src="{{(!empty($value->image)?$value->image:$value->image)}}" width="100" height="100"></td> -->
                                        <td>{{date("d-m-Y h:ia",strtotime($value->created_at))}}</td>
                                        <td>
                                            
                                            <a class="restore-subject btn btn-xs btn-danger" data-id="{{$value->id}}">Retore</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>   
                                </table>
                                {{Form::submit("Restore Multiple",array("class"=>'btn btn-primary pull-right courseRestore','disabled'=>'disabled'))}}
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    {{ $subject->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
     $('.chk_course_d').change(function() {
        var allCheckBox = $(".chk_course_d")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.courseDelete').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.courseDelete').removeAttr('disabled')
        } else{     
            $('.courseDelete').removeAttr('disabled')
        }
});
$('.chk_course_f').change(function() {
        var allCheckBox = $(".chk_course_f")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.courseFree').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.courseFree').removeAttr('disabled')
        } else{     
            $('.courseFree').removeAttr('disabled')
        }
});
$('.chk_course_p').change(function() {
        var allCheckBox = $(".chk_course_p")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.CoursePaid').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.CoursePaid').removeAttr('disabled')
        } else{     
            $('.CoursePaid').removeAttr('disabled')
        }
});
$('.chk_course_r').change(function() {
        var allCheckBox = $(".chk_course_r")
        var count_checked = allCheckBox.filter(":checked").length; 
        if (count_checked == 0)  {
        $('.courseRestore').attr('disabled','disabled')
        } else if(count_checked != allCheckBox.length) {
            $('.courseRestore').removeAttr('disabled')
        } else{     
            $('.courseRestore').removeAttr('disabled')
        }
});
    
});

</script>
@endsection