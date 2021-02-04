@extends('layouts.master')
@section('content')
<div class="content animate-panel">
	<div id="student" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table table-responsive" id="users">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Coupon</th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(isset($planCode))
                                        @forelse($planCode as $key => $val)

                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$val}}</td>
                                                
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
<script>
	$(document).ready(function(){
    $('#users').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,1]
                },
                text:"Export Users"
            },
        ]
    });
});
</script>
@endsection