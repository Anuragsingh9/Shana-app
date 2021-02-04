
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
                        <span>Media List</span>
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
                                        <th>Media Type</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                        <tbody>
                                                    @foreach($media as $key => $data)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $data['document']['title'] }}</td>
                                                        <td class="action-table">{{ $data['doc_type'] }}</td>
                                                        <td colspan="2"><a href="{{url('edit-media/'.$data['id'])}}" class="btn btn-xs btn-info">Edit</a>
                                                        @if($data['doc_type']=='Audio')
                                                        <a href="{{url('delete-media/'.$data['id'])}}" class="btn btn-xs btn-danger">Delete</a></td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                        </tbody>

                                </table>
                                {{ $media->links()  }}
                            </div>
                    </div>           
            </div>
        </div>
    </div>
</div>
@endsection


