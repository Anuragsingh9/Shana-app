@extends('layouts.master')
@section('content')
<!-- <div class="normalheader transition animated fadeIn">
    <div class="hpanel">
        <div class="panel-body">
            <a class="small-header-action" href="#">
                <div class="clip-header">
                    <i class="fa fa-arrow-up"></i>
                </div>
            </a>

            <div id="hbreadcrumb" class="pull-right m-t-lg">
                <ol class="hbreadcrumb breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li>
                        <span>Document</span>
                    </li>
                    <li class="active">
                        <span>Document List</span>
                    </li>
                </ol>
            </div>
            <h2 class="font-light m-b-xs">
                Document List
            </h2>
        </div>
    </div>
</div> -->

<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           
 
            
            <h2 class="font-light m-b-xs">
                Document List
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">

        <div class="col-lg-12">
            <div class="hpanel">
                    <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($document as $key => $item)
                                        <tr>
                                           <th>{{ ++$key }}</th>
                                           <th><a href="{{ url('media-document-list') }}/{{ $item->id }}" >{{ $item->title }}</a></th>
                                           <th><a  class="btn btn-xs btn-info" href="{{ url('edit-document') }}/{{ $item->id }}">edit</a>
                                           <a class="delete-document btn btn-xs btn-danger" data-id="{{$item->id}}">Delete</a></th>
                                        </tr>
                                       @endforeach
                                    </tbody>

                                </table>
                                {{ $document->links()  }}
                            </div>
                    </div>           
            </div>
        </div>
    </div>
</div>
@endsection