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

          <!--   <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="{{url('dashboard')}}">Dashboard</a></li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Dashborad
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                   <!--  <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Course
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$course[0]->total_course}}</h3>
                            <a href="{{url('course-list')}}"><small>Total Course</small></a>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-color3">{{$course[0]->free_count}}</h3>
                            <a href="{{url('course-list')}}"><small>Free Course</small></a>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-color3">{{$course[0]->paid_count}}</h3>
                            <a href="{{url('course-list')}}"><small>Paid Course</small></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                   <!--  <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Document
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$document[0]->total_video}}</h3>
                            <a href="{{url('document-list')}}"><small>Total Video</small></a>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-color3">{{$document[0]->total_audio}}</h3>
                            <a href="{{url('document-list')}}"><small>Total Audio</small></a>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-color3">{{$document[0]->total_text}}</h3>
                            <a href="{{url('document-list')}}"><small>Total Text</small></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Amount
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success"></h3>
                            <small>Total Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Amount
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$total_amount}}</h3>
                            <small>Total Amount</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                User
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$user['free']}}</h3>
                            <small>Total User</small>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{($user['free']-$user['paid'])}}</h3>
                            <small>Total Free User</small>
                        </div>
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$user['paid']}}</h3>
                            <small>Total Paid User</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Notification
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$notification}}</h3>
                            <small>Total Notification</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3" style="">
            <div class="panel-heading">
                <div class="panel-tools">
                    <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                    <a class="closebox"><i class="fa fa-times"></i></a> -->
                </div>
                Running Plan
            </div>
            <div class="hpanel">
                <div class="panel-body list">
                    <div class="list-item-container">
                        <div class="list-item">
                            <h3 class="no-margins font-extra-bold text-success">{{$running_plan}}</h3>
                            <small>Total Running</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
