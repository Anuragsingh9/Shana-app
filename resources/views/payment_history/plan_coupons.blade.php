@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">

            <h2 class="font-light m-b-xs">
                Plan Coupons List
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                 <ul class="nav nav-tabs">
                   {{-- <li class="active"><a data-toggle="tab" href="#all">All</a></li>
                   --}}
                </ul>
                <div class="tab-content">
                    <div id="all" class="tab-pane active">
                <div class="panel-body">
                    <div class="table-responsive">
                    {{Form::open(['url' => url('multiple-chapter-delete'),'id'=>'multiple-chapter-form'])}}
                    <table cellpadding="1" cellspacing="1" class="table">
                        <thead>
                        <tr>
                            <th>RefCode</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Activated On</th>
                            <th>EndDate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($data as $value)
                        <tr>

                            <td>{{$value->ref_code}}</td>
                            <td>{{isset($value->userPurchase->user_id)?$value->userPurchase->user_id:'-'}}</td>
                            <td>@if($value->status==1){{'Active'}} @else {{'Not Active'}} @endif </td>
                            <td>{{(!empty($value->activated_on)?date("d-M-Y",strtotime($value->activated_on)):'-')}}</td>
                            <td>{{isset($value->userPurchase->end_date)?date("d-M-Y",strtotime($value->userPurchase->end_date)):'-'}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{Form::close()}}
                    </div>
                </div>
            </div>

                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection