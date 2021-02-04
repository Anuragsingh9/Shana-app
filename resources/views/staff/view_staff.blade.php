@extends('layouts.master')
@section('content')
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           
            <h2 class="font-light m-b-xs">
                Profile
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
<div class="row">
    <div class="col-lg-3"> 
        <div class="hpanel hgreen">
            <div class="panel-body">
                <img alt="logo" class="img-circle m-b " height="100px" width="100px" src="{{cloudUrl($staff->photo)}}">
                <h3>{{$staff->name or ''}}</h3>
                {{-- <div class="text-muted font-bold m-b-xs">Course : {{($staff->course) ? $staff->course->course : ''}}</div> --}}
                <div class="text-muted font-bold m-b-xs">Institute : {{$staff->institute}}</div>
                <div class="text-muted font-bold m-b-xs">Mobile : {{$staff->mobile}}</div>
                <div class="text-muted font-bold m-b-xs">Referal code : {{$staff->self_ref_code}}</div>
                <div class="text-muted font-bold m-b-xs">Referal Amount : {{$staff->total_ref_amt}}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="hpanel">
            <div class="hpanel">

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#history">History ({{count($history)}})</a></li>
                <li class=""><a data-toggle="tab" href="#likes">Likes ({{count($likes)}}) </a></li>
                <li class=""><a data-toggle="tab" href="#downloads">Download ({{count($downloads)}})</a></li>
               {{--  <li class=""><a data-toggle="tab" href="#mylist">My list ({{count($mylist)}}) </a></li> --}}
            </ul>
            <div class="tab-content">
                <div id="history" class="tab-pane active">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="historytable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Title </th>
                                    <th>Author Name</th>
                                    <th>Description</th>
                                    <th>Date Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp

                                @foreach($history as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->mediaList[0]->doc_type}}</td>
                                    <td><img width ="100" height="100" src="{{cloudUrl($data->preview_image)}}"></td>
                                    <td>{{$data->title}}</td>
                                    <td>{{$data->author_name}}</td>
                                    <td>{!! $data->description !!}</td>
                                    <td>{{date("d-m-Y h:i a",strtotime($history_date[$data->id]))}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="likes" class="tab-pane">
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-striped" id="liketable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Title </th>
                                    <th>Author Name</th>
                                    <th>Description</th>
                                    <th>Date Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($likes as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->doc_type}}</td>
                                    <td><img width ="100" height="100" src="{{cloudUrl($data->preview_image)}}"></td>
                                    <td>{{$data->title}}</td>
                                    <td>{{$data->author_name}}</td>
                                    <td>{!! $data->description !!}</td>
                                    <td>{{date("d-m-Y h:i a",strtotime($likes_date[$data->id]))}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                                </tbody>
                            </table>
                        </div>                   
                    </div>
                </div>
                <div id="downloads" class="tab-pane">
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-striped" id="downloadtable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Image</th>
                                    <th>Title </th>
                                    <th>Author Name</th>
                                    <th>Description</th>
                                    <th>Date Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                @foreach($downloads as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$data->doc_type}}</td>
                                    <td><img width ="100" height="100" src="{{cloudUrl($data->preview_image)}}"></td>
                                    <td>{{$data->title}}</td>
                                    <td>{{$data->author_name}}</td>
                                    <td>{!! $data->description !!}</td>
                                    <td>{{date("d-m-Y h:i a",strtotime($downloads_date[$data->id]))}}</td>   
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               {{-- <div id="mylist" class="tab-pane">
                                                                 <div class="panel-body no-padding">
                                                                     <div class="table-responsive">
                                                                         <table class="table table-striped">
                                                                             <thead>
                                                                             <tr>
                                                                                 <th>#</th>
                                                                                 <th>Type</th>
                                                                                 <th>Image</th>
                                                                                 <th>Title </th>
                                                                                 <th>Author Name</th>
                                                                                 <th>Description</th>
                                                                                 <th>Date Time</th>
                                                                             </tr>
                                                                             </thead>
                                                                             <tbody>
                                                                             @php $i=1; @endphp
                                                                             @foreach($mylist as $data)
                                                                             <tr>
                                                                                 <td>{{$i++}}</td>
                                                                                 <td>{{$data->doc_type}}</td>
                                                                                 <td><img width ="100" height="100" src={{getImgSrc('uploads/preview_image/',$data->preview_image)}}></td>
                                                                                 <td>{{$data->title}}</td>
                                                                                 <td>{{$data->author_name}}</td>
                                                                                 <td>{!! $data->description !!}</td>
                                                                                 <td>{{date("d-m-Y h:i a",strtotime($mylist_date[$data->id]))}}</td>
                                                                             </tr>
                                                                             @endforeach
                                                                             </tbody>
                                                                         </table>
                                                                     </div>
                                                                 </div>
                                                             </div>--}}
            </div>
            </div>
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
                    columns: [0,1,2]
                },
                text:"Export Users"
            },
        ]
    });
    $('#historytable').DataTable({
  "searching": false,
  "bLengthChange": false,
});
    $('#likestable').DataTable({
  "searching": false,
  "bLengthChange": false,
});
    $('#downloadtable').DataTable({
  "searching": false,
  "bLengthChange": false,
});
});
</script>
@endsection