@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                User List
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#student">Users</a></li>
                    <li class=""><a data-toggle="tab" href="#paid">Paid-Users</a></li>
           {{--         <li class=""><a data-toggle="tab" href="#parents">Parents</a></li>
                    <li class=""><a data-toggle="tab" href="#competitor">Competitor</a></li>--}}
                </ul>
                <div class="row">
                    <div class="col-md-4">
                        <select id='instituteSearch' class="form-control">
                            <option value="">Please Select Institute</option>
                            @foreach ($institute as $element)
                              <option value="{{$element}}">{{$element}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id='citySearch' class="form-control">
                            <option value="">Please Select city</option>
                            @foreach ($city as $element)
                              <option value="{{$element}}">{{$element}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button id="clear">Clear Filter</button>
                    </div>
                </div>
                <div class="tab-content"> 
                    <div id="student" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table table-responsive" id="users">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Age</th>
                                        <th>City</th>
                                        <th>Institute</th>
                                        <th>Referal Code</th>
                                        <th>Self Referal Code</th>
                                        <th>Total Referal Amount</th>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($staff['student']))
                                        @forelse($staff['student'] as $key => $val)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val['name']}}</td>
                                                <td>{{$val['mobile']}}</td>
                                                <td>{{$val['age']}}</td>
                                                <td>{{$val['city']}}</td>
                                                <td>{{$val['institute']}}</td>
                                                <td>{{$val['ref_code']}}</td>
                                                <td>{{$val['self_ref_code']}}</td>
                                                <td>{{$val['total_ref_amt']}}</td>
                                                <td><img alt="logo" class="img-circle m-b " height="100px" width="100px" src="{{cloudUrl($val['photo'])}}"></td>
                                                

                                                <td>
                                                    <a href="{{url('view-user',$val['id'])}}"><button class="btn btn-xs btn-success">View</button></a>
                                                    <a class="delete-user" data-id="{{$val['id']}}"><button class="btn btn-xs btn-danger">Delete</button></a>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
                                    </tbody>
                                </table>
                               
                            </div>
                        </div>
                    </div>
                    <div id="paid" class="tab-pane ">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table table-responsive" id="paid_users">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Plan Purchase At</th>
                                        <th>Plan Expired On</th>
                                        <th>Age</th>
                                        <th>City</th>
                                        <th>Institute</th>
                                        <th>Referal Code</th>
                                        <th>Self Referal Code</th>
                                        <th>Total Referal Amount</th>
                                        <th>Photo</th>
                                       <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($paidUser))
                                        @forelse($paidUser->toArray() as $key => $val)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val['name']}}</td>
                                                <td>{{$val['mobile']}}</td>
                                                <td>{{\Carbon\Carbon::parse($val['created_at'])->format('d M Y')}}</td>
                                                <td>{{\Carbon\Carbon::parse($val['end_date'])->format('d M Y')}}</td>
                                                <td>{{$val['age']}}</td>
                                                <td>{{$val['city']}}</td>
                                                <td>{{$val['institute']}}</td>
                                                <td>{{$val['ref_code']}}</td>
                                                <td>{{$val['self_ref_code']}}</td>
                                                <td>{{$val['total_ref_amt']}}</td>
                                                <td><img alt="logo" class="img-circle m-b " height="100px" width="100px" src="{{cloudUrl($val['photo'])}}"></td>
                                                <td>
                                                    <a href="{{url('view-user',$val['id'])}}"><button class="btn btn-xs btn-success">View</button></a>
                                                    <a class="delete-user" data-id="{{$val['id']}}"><button class="btn btn-xs btn-danger">Delete</button></a>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    @endif
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
<script>
    $('#instituteSearch').on('change',function(e){
        filterDatatabe('#users',5,event.target.value)
        filterDatatabe('#paid_users',7,event.target.value)
       
    })
    $('#citySearch').on('change',function(e){
        filterDatatabe('#users',4,event.target.value)
        filterDatatabe('#paid_users',6,event.target.value)
    })
    $('#clear').on('click',function(e){
        $('#instituteSearch').val("");
        $('#citySearch').val("");
        filterDatatabe('#users',4,'')
        filterDatatabe('#users',5,'')
        filterDatatabe('#paid_users',6,'')
        filterDatatabe('#paid_users',7,'')
    })
    function filterDatatabe(table_name,cloumn_number,searchvalue){
        var table=$(table_name).DataTable();
            table.column(cloumn_number).search( searchvalue ).draw();
        }
    $(document).ready(function(){
    $('#users').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                },
                text:"Export Users"
            },
        ]
    });
      $('#paid_users').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11]
                },
                text:"Export Users"
            },
        ]
    });
});
</script>
@endsection