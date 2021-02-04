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
                Plan Purchase List
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
                            <th>Payment Id</th>
                            <th>User</th>
                            <th>Plan</th>
                            <th>Paid Amount</th>
                            <th>Discount Amount</th>
                            <th>Purchase At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1 @endphp
                        @foreach($data as $value)
                        <tr>
                            <td><a href="{{url('plan-coupons',$value->id)}}">{{$value->purchase_id}}</a> </td>
                            <td>{{$value->user_id}}</td>
                            <td>{{$value->item_id}}</td>
                            <td><i class="fa fa-inr" aria-hidden="true"></i>{{$value->paid_amount}}</td>
                            <td><i class="fa fa-inr" aria-hidden="true"></i>{{$value->discount_amount}}</td>
                            <td>{{date("d-M-Y",strtotime($value->created_at))}}</td>
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