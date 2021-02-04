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
                 <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#course">Course</a></li>
                     <li class=""><a data-toggle="tab" href="#subject">Subject</a></li>
                     <li class=""><a data-toggle="tab" href="#chapter">Chapter</a></li>
                     <li class=""><a data-toggle="tab" href="#topic">Topic</a></li>
                    <li class=""><a data-toggle="tab" href="#media">Media</a></li>

                    {{-- <li class=""><a data-toggle="tab" href="#video">Video</a></li>
                    <li class=""><a data-toggle="tab" href="#audio">Audio</a></li>
                    <li class=""><a data-toggle="tab" href="#text">Text</a></li> --}}
                </ul>
                <div class="tab-content">
                    <div id="topic" class="tab-pane ">
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
                                        @foreach($topic as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th>Document Type</th> -->
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                        <!-- <th>Display To</th> -->
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        
                                                        <!-- <td>{{$data['doc_type']}}</td> -->
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                       <!--  <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td> -->
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Audio')
                                                                <a href='{{$data['doc_file']}}' target="_blank">
                                                                <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=100 height=100></a>
                                                            @elseif($data['doc_type']=='Video')
                                                                @if($data['doc_url']!='')
                                                                    <a href={{$data['doc_url']}} target=_blank>URL</a>
                                                                @else

                                                                    <a href='{{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}}' target="_blank">
                                                                        <img src={{(!empty(cloudUrl($data['preview_image']))?CreateTemporaryURL(cloudUrl($data['preview_image'])):CreateTemporaryURL('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                                @endif
                                                            @else
                                                                {!! $data['content'] !!}
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
                    <div id="course" class="tab-pane active">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Course</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($course as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th>Document Type</th> -->
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                       <!--  <th>Display To</th> -->
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <!-- <td>{{$data['doc_type']}}</td> -->
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                        <!-- <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td> -->
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Audio')
                                                                <a href='{{$data['doc_file']}}' target="_blank">
                                                                    <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                            @elseif($data['doc_type']=='Video')
                                                                @if($data['doc_url']!='')
                                                                    <a href={{$data['doc_url']}} target=_blank>URL</a>
                                                                @else
                                                                    <a href='{{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}}' target="_blank">
                                                                        <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                                @endif
                                                            @else
                                                                {!! $data['content'] !!}
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
                     <div id="media" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Document</th>
                                       {{-- <th>Media</th>--}}
                                        <th>Type</th>
                                       {{-- <th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($media as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{ $value['document_name'] }}</td>
                                            <td>

                                                <table class="act-table">
                                                    @foreach($value['media'] as $data)

                                                    <tr>
                                                        <td class="action-table">{{ $data['doc_type'] }}</td>
                                                        <td colspan="2"><a href="{{url('edit-media/'.$data['id'])}}" class="btn btn-xs btn-info">Edit</a></td>
                                                        @if($data['doc_type']=='Audio')
                                                        <td colspan="2"><a href="{{url('delete-media/'.$data['id'])}}" class="btn btn-xs btn-danger">Delete</a></td>
                                                        @endif
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
                    <div id="chapter" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Chapter</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($chapter as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th>Document Type</th> -->
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                        <!-- <th>Display To</th> -->
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <!-- <td>{{$data['doc_type']}}</td> -->
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                        <!-- <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td> -->
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Audio')
                                                                <a href='{{$data['doc_file']}}' target="_blank">
                                                                    <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                            @elseif($data['doc_type']=='Video')
                                                                @if($data['doc_url']!='')
                                                                    <a href={{$data['doc_url']}} target=_blank>URL</a>
                                                                @else
                                                                    <a href='{{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}}' target="_blank">
                                                                        <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                                @endif
                                                            @else
                                                                {!! $data['content'] !!}
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
                    <div id="subject" class="tab-pane">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="1" cellspacing="1" class="table">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subject</th>
                                        <th>Document</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($subject as $key => $value)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key}}</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th>Document Type</th> -->
                                                        <th>Title</th>
                                                        <th>Author Name</th>
                                                       <!--  <th>Display To</th> -->
                                                        <th width="200px">Description</th>
                                                        <th>File</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($value as $data)
                                                    <tr>
                                                        <!-- <td>{{$data['doc_type']}}</td> -->
                                                        <td>{{$data['title']}}</td>
                                                        <td>{{$data['author_name']}}</td>
                                                       <!--  <td>
                                                            @php $display_to=explode(",",$data['user_type']); @endphp
                                                            @foreach($display_to as $item)
                                                                <li>{{$item}}</li>
                                                            @endforeach
                                                        </td> -->
                                                        <td>{!! $data['description'] !!}</td>
                                                        <td>
                                                            @if($data['doc_type']=='Audio')
                                                                <a href='{{$data['doc_file']}}' target="_blank">
                                                                    <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                            @elseif($data['doc_type']=='Video')
                                                                @if($data['doc_url']!='')
                                                                    <a href={{$data['doc_url']}} target=_blank>URL</a>
                                                                @else
                                                                    <a href='{{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}}' target="_blank">
                                                                        <img src={{(!empty(cloudUrl($data['preview_image']))?cloudUrl($data['preview_image']):getImgSrc('uploads/preview_image/',cloudUrl($data['preview_image'])))}} width=70 height=70></a>
                                                                @endif
                                                            @else
                                                                {!! $data['content'] !!}
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

                </div>
                {{ $document->links() }}
            </div>
        </div>
    </div>
</div>
@endsection