@extends('layouts.master')
@section('content')


<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">
           

            
            <h2 class="font-light m-b-xs">
                Blog
            </h2>
        </div>
    </div>
</div>

<div class="content animate-panel">
    <div id="loader" class="hide">
        <img src="{{ publicAsset('images/LoaderIcon.gif') }}" class="img-responsive center-block">
    </div>
    <div class="row">

        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <!-- <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    Add Blog Form
                </div>
                <div class="panel-body">
                    @if(!empty($book))
                        {{Form::model($book,['url' => url('update-book',$book->id),'class'=>'form-horizontal','files'=>true,'id'=>'book-form'])}}
                        {{Form::hidden('id',$book->id,array('id'=>'id'))}}
                    @else
                        {{Form::open(['url' => url('insert-book'), 'method' => 'post','class'=>'form-horizontal','files'=>true,'id'=>'book-form'])}}
                        {{Form::hidden('id','',array('id'=>'id'))}}
                    @endif
                         
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Book Title</label>
                            <div class="col-sm-10">
                                {{Form::text('title',null,array('class'=>'form-control m-b','placeholder'=>'Title'))}}
                                @if ($errors->has('title'))
                                    <div class="error">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                @if(isset($book))
                                    <!--<label class="checkbox-inline">{{Form::radio('doc_type[]','pdf',($book->doc_type == 'pdf')?true:false,array("class"=> "type-check",'id'=>'pdf'))}}PDF</label>-->
                                    <!--<label class="checkbox-inline">{{Form::radio('doc_type[]','word',($book->doc_type == 'word')?true:false,array("class"=> "type-check",'id'=>'word'))}}WORD</label>-->
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','text',($book->doc_type == 'text')?true:false,array("class"=> "type-check",'id'=>'text'))}}TEXT</label>
                                    {{Form::hidden("doc_type_text",null,array('class'=>'doc_type_text'))}}
                                @else
                                    <!--<label class="checkbox-inline">{{Form::radio('doc_type[]','pdf',false,array("class"=> "type-check",'id'=>'pdf'))}}PDF</label>-->
                                    <!--<label class="checkbox-inline">{{Form::radio('doc_type[]','word',false,array("class"=> "type-check",'id'=>'word'))}}WORD</label>-->
                                    <label class="checkbox-inline">{{Form::radio('doc_type[]','text',true,array("class"=> "type-check",'id'=>'text'))}}TEXT</label>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group pdf" style="display:none;">
                            <label class="col-sm-2 control-label">PDF File</label>
                            <div class="col-sm-10">
                                @if(isset($book->doc_type) && $book->doc_type=='pdf')
                                    Pdf File:{{$book->file_detail}}
                                @endif
                                {{Form::file('pdf_file',null)}}
                            </div>                                
                        </div>--}}
                        {{--<div class="form-group word" style="display:none;">
                            <label class="col-sm-2 control-label">word File</label>
                            <div class="col-sm-10">
                                @if(isset($book->doc_type) && $book->doc_type=='word')
                                    Word File:{{$book->file_detail}}
                                @endif
                                {{Form::file('word_file',null)}}
                            </div>                                
                        </div>--}}
                        <div class="form-group text" >
                            <label class="col-sm-2 control-label">Text Content</label>
                            <div class="col-sm-10">
                                @if(isset($book))
                                {{Form::textarea('text_content',$book->file_detail,array('class'=>'form-control m-b','id'=>'pdf_content','placeholder'=>'Content','rows'=>'2'))}}
                                @else
                                {{Form::textarea('text_content',null,array('class'=>'form-control m-b','id'=>'pdf_content','placeholder'=>'Content','rows'=>'2'))}}
                                @endif
                                {{-- {{Form::file('pdf_file',null)}} --}}
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Preview Image</label>
                            <div class="col-sm-10">
                                @if(isset($book->preview))
                                    <img src="{{cloudUrl($book->preview)}}" class="img-responsive" width="110" height="200" />
                                @endif
                                {{Form::file('preview',null)}}  <span>Preview Image Size must be 800X450 </span>
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-10">
                                @if(isset($book))
                                {{Form::text('order',$book->orderNo,array('class'=>'form-control m-b','id'=>'orderBook','placeholder'=>'Order','data-id'=>$book->id))}}
                                @else
                                {{Form::text('order',$order,array('class'=>'form-control m-b','id'=>'orderBook','placeholder'=>'Order','data-id'=>0))}}
                                @endif
                                <div class='text-danger' id="orderError"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Author Name</label>
                            <div class="col-sm-10">
                                
                                    {{Form::text('author_name',null,array('class'=>'form-control m-b','placeholder'=>'Author Name'))}}
                          
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                
                                    {{Form::textarea('description',null,array('class'=>'form-control m-b','id'=>'description','placeholder'=>'Description','rows'=>'2'))}}
                          
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
                                <div class="preview"></div>
                                <div class="progress" style="display:none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        0%
                                    </div>
                                </div>
                            </div>
                        </div>
                       {{-- <div id="loader-icon" style="display:none;"><img src="{{asset('images/LoaderIcon.gif')}}" /></div>--}}

                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                {{Form::reset('Cancel',array('class'=>'btn btn-default'))}}
                                {{Form::submit('Save',array('class'=>'btn btn-primary btnSubmit','id'=>"btnSubmit"))}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>


</div>

<script src="{{publicAsset('js/jqueryForm.js')}}"></script>

<script>
    
    $(document).ready(function() {
        @if(isset($book))
        showForm('{{$book->doc_type}}')
        
        @endif
        CKEDITOR.replace( 'pdf_content');
      
       })
$('#pdf').on('click',function(e){
    showForm('pdf');
})
$('#word').on('click',function(e){
    showForm('word');
})
$('#text').on('click',function(e){
    showForm('text');
})
$(document).ready(function() {
            $(function(){
                $("#book-form").validate({

                    rules: {
                        //topic_id: { required: true },
                        title: { required: true },
                        
                        'doc_type[]' : { required :true },
                        video_type : { required : true },
                        video_url : {required: true, url: true},
                        pdf_file : {
                            accept: "application/pdf" },
                        
                        preview : {
                            accept: "jpg,png,jpeg,gif"
                        }
                    },
                    messages:{
                        pdf_file:{ accept: "Please Select only Pdf File",},
                        word_file:{ accept: "Please Select only Word File",},
                        preview:{ accept: "Please Select only Image File",}
                    },
                    submitHandler: function(form) { // <- pass 'form' argument in
                    //     $(".submit").attr("disabled", true);

                    //   // $('#loader').removeClass('hide');
                    //     $('.progress').css('display', 'block');
                      $('#document-form').ajaxSubmit({
                            target:   '#targetLayer',
                            beforeSubmit: function() {
                                $(".progress-bar").width('0%');
                            },
                            uploadProgress: function (event, position, total, percentComplete){
                                console.log(percentComplete);
                                $(".progress-bar").css('width',percentComplete + '%');
                                $(".progress-bar").html( percentComplete +'%')
                            },
                            dataType:'json',
                            timeout:   60000*10,
                            success:showResponse,
                            error:function (data) {
                                //alert('asdfasdfDDD')
                            }

                            //resetForm: true
                        });
                    //     return false;
                    }
                });
            });

        });
function showForm(name){
    switch(name){
        case 'pdf':
        $('.pdf').show();
        $('.word').hide();
        $('.text').hide();
        break;
        case 'word':
        $('.pdf').hide();
        $('.word').show();
        $('.text').hide();
        break;
        case 'text':
        $('.pdf').hide();
        $('.word').hide();
        $('.text').show();
        break;
    }
}
</script>
    <style>
        #loader {
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 9;
            left: 0;
            top: 0;
            background: rgba(255, 255, 255, 0.5);
        }
        #loader img {
            width: 134px;
            height: 100px;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
        }
    </style>
@endsection