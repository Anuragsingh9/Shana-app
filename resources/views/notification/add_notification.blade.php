@extends('layouts.master')
@section('title')
    Add Notification
@endsection
<!--filter-none class use for hide search-->
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Add Notification
            </h2>
        </div>
    </div>
</div>


    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <ul class="nav">

                    </ul>
                    <div class="tab-content">
                        <div id="all" class="tab-pane active">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <div class="col-sm-12"><input type="text" name="search" id="search" placeholder="search..."></div>
                                    <div class="col-sm-3 table-scroll">     
                                    {!! Form::open(['method'=>'POST','action'=>'PushNotificationController@store','class'=>"form-inline",'id'=>"search-form"]) !!}
                                    <table cellpadding="1" cellspacing="1" class="table" id=>
                                        <thead>
                                        <tr>
                                            <th class="table-checkbox">
                                                <label class="m-b-none mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="group-checkable"
                                                           data-set="#student_list .checkboxes"/>
                                                    <span><label class="m-b-none"><b>CheckAll</b></label></span>
                                                </label>
                                            </th>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $i=1 @endphp
                                        @foreach($user as $item)
                                            <tr class="searchTR">
                                                <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" name="userChecked[]" id="{{$item->id}}"
                                                               class="checkboxes" value="{{$item->id}}"/>
                                                        <span></span>
                                                    </label></td>
                                                <td class="searchName">{{$item->name}}</td>


                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                    <div class="col-sm-8 col-sm-offset-1">
                                    <label class="">Title</label>
                                    <input class="form-control col-sm-12" name="title">
                                    <div class="col-sm-12 nopadding">


                                        <div class="form-body" style="min-height:0px;">


                                            <label>Message</label>
                                            <textarea class="form-control" style="resize:vertical; min-height:150px"
                                                      name="message"></textarea>
                                        </div>

                                        <div class="form-footer text-right">


                                            {{Form::submit("Send",array("class"=>'btn btn-success'))}}
                                        </div>
                                    </div>
                                    </div>



                                    {{Form::close()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--<div class="page-content">
        <!-- BEGIN PAGE HEADER-->

        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="index.html">Home</a>
                    <i class="fa fa-circle"></i>
                </li>

                <li>
                    <span> Multiple Notification</span>
                </li>
            </ul>

        </div>
        <h3 class="page-title"> Multiple Notification
            <!--<small>bootstrap form controls and more</small>-->
        </h3>
        <!-- END PAGE BAR -->
        <!-- BEGIN DASHBOARD STATS 1-->
        <div class="row">
            <div class="one-third clearfix">
                {!! Form::open(['method'=>'POST','action'=>'PushNotificationController@store','class'=>"form-inline",'id'=>"search-form"]) !!}
                <div class="one">
                    <div class="portlet light portlet-fit portlet-datatable bordered ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-layers font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Students List</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column "
                                   id="student_list">
                                <thead>
                                <tr>
                                    <th class="table-checkbox">
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="group-checkable"
                                                   data-set="#student_list .checkboxes"/>
                                            <span></span>
                                        </label>
                                    </th>
                                    <th> Role</th>
                                    <th> Name</th>


                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user as $item)
                                    <tr>
                                        <td><label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" name="teacherChecked[]" id="{{$item->id}}"
                                                       class="checkboxes" value="' . $users->id . '"/>
                                                <span></span>
                                            </label></td>
                                        <td>{{$item->name}}</td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="one">
                    <div class="portlet light portlet-fit portlet-datatable bordered  filter-none">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-envelope-o font-green"></i>
                                <span class="caption-subject font-green sbold uppercase">Message</span>
                            </div>
                            <!--<div class="actions">
                                 <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                     <i class="icon-cloud-upload"></i>
                                 </a>
                                 <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                     <i class="icon-wrench"></i>
                                 </a>
                                 <a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
                                     <i class="icon-trash"></i>
                                 </a>
                             </div>-->
                        </div>
                        <div class="portlet-body ">
                            <form>
                                <div class="form-body" style="min-height:0px;">
                                    <textarea class="form-control" style="resize:vertical; min-height:150px"
                                              name="message"></textarea>
                                </div>

                                <div class="form-footer text-right">
                                    <input type="submit" name="send" class="btn btn-success" value="Send">
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- END DASHBOARD STATS 1-->


    </div>--}}
    <script>
        $(document).ready(function () {
            $(".group-checkable").click(function () {
                $(".checkboxes").prop('checked', $(this).prop('checked'));
            });

            $(".checkboxes").change(function(){
                if (!$(this).prop("checked")){
                    $(".group-checkable").prop("checked",false);
                }
            });

        });
       $("#search").on('keyup',function(e){
           if(e.target.value.length>3){
                var li=$(".searchName")
                for (i = 0; i < li.length; i++) {
                    var value=li[i].innerHTML
                        if (value.toUpperCase().indexOf(e.target.value.toUpperCase())>-1) {
                        $(".searchTR")[i].style.display = "";
                        } else {
                            $(".searchTR")[i].style.display = "none";
                        }
                }
            }
            else if(e.target.value.length==0){
                var li=$(".searchName")
                for (i = 0; i < li.length; i++) {
                    $(".searchTR")[i].style.display = "";
                }
            }
        })
       
    </script>
@endsection

<style>
    .action-table tr td:first-child {
        width: 40px;
    }

    .action-table tr td:nth-of-type(2) {
        width: 100px;
    }

    .action-table tr td:last-child {
        width: 100px;
    }
</style>
