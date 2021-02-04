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
                Image List
            </h2>
        </div>
    </div>
</div>



<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">

                <div class="tab-content">
                    <div id="topic" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($data as $key => $value)
                                        <tr>
                                            <td>
                                                <img src={{(!empty($value->image)?cloudUrl($value->image):getImgSrc('uploads/preview_image/','dummy.jpg'))}} width=100 height=100></a>

                                            </td>
                                            <td>
                                                <a href="{{url('edit-home-images',$value->id)}}" class="btn btn-xs btn-info">Edit</a>
                                                {{-- <a class="delete-document btn btn-xs btn-danger" data-id="{{$data['id']}}">Delete</a>--}}
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- <div id="video" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Topic</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($video as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                        <th>Display To</th>
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                        <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td>
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Video')
                                                                @if($data['doc_url']!='')
                                                                    <a href={{$data['doc_url']}} target=_blank>URL</a>
                                                                @else
                                                                    <a href='uploads/{{strtolower($data['doc_type'])."/".$data['doc_file']}}' target="_blank">
                                                                    <img src={{getImgSrc('uploads/preview_image/',$data['preview_image'])}} width=100 height=100></a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{url('edit-document',$data['id'])}}" class="btn btn-xs btn-info">Edit</a>
                                                            <a class="delete-document btn btn-xs btn-danger" data-id="{{$data['id']}}">Delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </td>                        
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="audio" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Topic</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($audio as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                        <th>Display To</th>
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                        <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td>
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Audio')
                                                                <a href='uploads/{{strtolower($data['doc_type'])."/".$data['doc_file']}}' target="_blank">
                                                                <img src={{getImgSrc('uploads/preview_image/',$data['preview_image'])}} width=100 height=100></a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{url('edit-document',$data['id'])}}" class="btn btn-xs btn-info">Edit</a>
                                                            <a class="delete-document btn btn-xs btn-danger" data-id="{{$data['id']}}">Delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </td>                        
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="text" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Topic</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($text as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                        <th>Display To</th>
                                                        <th width="200px">Description</th>
                                                        <th>Content</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                        <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td>
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            {!! $data['content'] !!}
                                                        </td>
                                                        <td>
                                                            <a href="{{url('edit-document',$data['id'])}}" class="btn btn-xs btn-info">Edit</a>
                                                            <a class="delete-document btn btn-xs btn-danger" data-id="{{$data['id']}}">Delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </td>                        
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection