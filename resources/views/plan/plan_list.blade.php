@extends('layouts.master')
@section('content')
<!-- <div class="normalheader transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="#">
                <div class="clip-header"><i class="fa fa-arrow-up"></i></div>
            </a>
            <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><span>Plan</span></li>
                    <li class="active"><span>Plan List</span></li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">Plan List</h2>
        </div>
    </div>
</div> -->

<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Plan List
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
                        <!-- <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table cellpadding="1" cellspacing="1" class="table" id="course-table">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1 @endphp
                            @foreach($plan as $value)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->duration}} {{$value->field}}</td>
                                    <td>{{$value->amount}}</td>
                                    <td>{{datetimeFormat($value->created_at)}}</td>
                                    <td>
                                        <a href="{{url('edit-plan/'.$value->id)}}" class="btn btn-xs btn-info">Edit</a>
                                        <a class="delete-plan btn btn-xs btn-danger" data-id="{{$value->id}}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>   
                        </table>
                        {{ $plan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    
/*$(function() {
    $('#course-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'course_ajax',
        columns: [
            {data : 's_id', name : 's_id'},
            { data: 'course', name: 'course' },
            {data: 'created_at', name: 'created_at'}
           {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
    });
});*/
</script>
@endsection