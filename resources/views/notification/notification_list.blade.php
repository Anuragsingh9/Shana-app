@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Notification List
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="tab-content">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Receiver Name</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                       
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($notification))
                                        @foreach($notification as $key => $val)

                                            <tr>
                                                @php $sno=(($notification->currentPage())-1)*$notification->perPage() @endphp
                                                <td>{{ $sno=$sno+($key+1) }}</td>
                                                <td>{{$val['user']['name']}}</td>
                                                <td>{{$val['title']}}</td>
                                                <td>{{$val['message']}}</td>
                                                <td>{{$val['event']}}</td>
                                                
                                            </tr>
                                        @endforeach

                                    @endif
                                    </tbody>
                                    
                                </table>
                               <div class="pull-right">{{ $notification->links() }}</div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection