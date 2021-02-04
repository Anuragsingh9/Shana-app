@extends('layouts.master')
@section('content')
    <div class="normalheader transition animated fadeIn  small-header">
        <div class="hpanel">
            <div class="panel-body">
                <!-- <a class="small-header-action" href="#">
                    <div class="clip-header"><i class="fa fa-arrow-up"></i></div>
                </a> -->
                <!-- <div id="hbreadcrumb" class="pull-right m-t-lg">
                    <ol class="hbreadcrumb breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><span>Subject</span></li>
                        <li class="active"><span>Subject List</span></li>
                    </ol>
                </div> -->
                <h2 class="font-light m-b-xs">Site Info List</h2>
            </div>
        </div>
    </div>
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <ul class="nav nav-tabs">

                    </ul>
                    <div class="tab-content">
                        <div id="all" class="tab-pane active">
                            <div class="panel-body">
                                <div class="table-responsive">

                                    <table cellpadding="1" cellspacing="1" class="table" id="course-table">
                                        <thead>
                                        <tr>

                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @php $i=1 @endphp
                                        @foreach($data as $value)
                                            <tr>

                                                <td>{{$value->title}}</td>
                                                <td>{!!mb_strimwidth($value->content_data, 0, 250, "...")!!}</td>
                                                <td> @if($value->type=='term')
                                                        {{'Term & Conditions'}}
                                                    @elseif($value->type=='policy'){{'Privacy Policy'}}
                                                        @elseif($value->type=='refund'){{'Refund & Cancellation'}} @else {{ucwords($value->type).' Us'}} @endif</td>
                                                <td>
                                                    <a href="{{url('edit/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection