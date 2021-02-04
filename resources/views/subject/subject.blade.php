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
                    <li><a href="index-2.html">Dashboard</a></li>
                    <li>
                        <span>Course</span>
                    </li>
                    <li class="active">
                        <span>Course List</span>
                    </li>
                </ol>
            </div> -->
            <h2 class="font-light m-b-xs">
                Course
            </h2>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-heading">
                    <div class="panel-tools">
                        <a class="btn btn-primary" href={{url('/chapter')}}>Add Chapter</a>
                        <!-- <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        <a class="closebox"><i class="fa fa-times"></i></a> -->
                    </div>
                    <br/>
                </div>
                <div class="panel-body">
                    @if(!empty($subject))
                        {{Form::model($subject,['url' => url('update-subject',$subject->id),'class'=>'form-horizontal','id'=>'subject-form','files'=>true])}}
                        {{Form::hidden('id',$subject->id,array('id'=>'id'))}}
                    @else
                        {{Form::open(['url' => url('insert-subject'), 'method' => 'post','class'=>'form-horizontal','id'=>'subject-form','files'=>true])}}
                        {{--  {{Form::hidden('id','',array('id'=>'id'))}}  --}}
                    @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-10">
                                {{Form::select('course_id',$course,null,array('class'=>'form-control m-b','placeholder'=>'Select Subject'))}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Course</label>
                            <div class="col-sm-10">
                                {{Form::text('subject',null,array('class'=>'form-control m-b','placeholder'=>'Course'))}}
                            </div>
                        </div>
                        
                        
                         <div class="form-group">
                            <label class="col-sm-2 control-label">Data Entry Type</label>
                            <div class="col-sm-10">
                                 @if(isset($subject->data_type))
                              <label><input type="radio" name="data_type1" value="0" class="data_type-class" @if($subject->data_type==0) checked @endif />Educational</label> 
                              <label><input type="radio" name="data_type1" value="1" class="data_type-class" @if($subject->data_type==1) checked @endif />Spiritual</label>  

                            @else
                                <label>{{Form::radio("data_type1",0,true,array("class"=>'data_type-class'))}}Educational</label>
                                <label>{{Form::radio("data_type1",1,false,array("class"=>'spiritual-class'))}}Spiritual</label>
                            @endif
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-10">
                                <label>{{Form::radio("type","Free",true,array("class"=>'type-class'))}}Free</label>
                                <label>{{Form::radio("type","Paid",false,array("class"=>'type-class'))}}Paid</label>
                            </div>
                        </div>
                        
                        <div class="paid-type" style="display:none">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Duration</label>
                                <div class="col-sm-5">
                                    {{Form::text('duration',null,array('class'=>'form-control m-b','placeholder'=>'Duration'))}}
                                </div>
                                <div class="col-sm-5">
                                    {{Form::select('field',["Year"=>"Year","Month"=>"Month"],null,array('class'=>'form-control m-b'))}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Amount</label>
                                <div class="col-sm-10">
                                    {{Form::text('amount',null,array('class'=>'form-control m-b','placeholder'=>'Amount'))}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Referral Type</label>
                                <div class="col-sm-2">
                                    {{Form::radio('ref_type','Percentage',false,array('class'=>''))}}Percentage
                                    {{Form::radio('ref_type','Rs',false,array('class'=>''))}}Rs.
                                </div>

                            <div class="hidden" id='referal'>                                
                                <label class="col-sm-4 control-label">Referral Amount/Percentage</label>
                                <div class="col-sm-4">
                                    {{Form::number('ref_amount',null,array('class'=>'form-control','placeholder'=>'Enter Value'))}}
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Image</label>
                            <div class="col-sm-10">
                                <img src="{{isset($subject->image)?CreateTemporaryURL($subject->image):''}}" id="blah" class="img-responsive {{ isset($subject->image)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('file',null)}}<span>Image Size must be 500X275 </span>
                            </div>
                        </div>



                        <div class="form-group">
                            <label class="col-sm-2 control-label">Banner Image</label>
                            <div class="col-sm-10">

                                <img src="{{isset($course->banner_img)?cloudUrl($course->banner_img):''}}" id="blah" class="img-responsive {{ isset($course->banner_img)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('banner_img_file',null)}} <span>Banner Image Size must be 500X275 </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Icon Image</label>
                            <div class="col-sm-10">

                                <img src="{{isset($course->icon_img)?cloudUrl($course->icon_img):''}}" id="blah" class="img-responsive {{ isset($course->icon_img)?'':'hidden' }}" width="110" height="200" />
                                {{Form::file('icon_img_file',null)}} <span>Icon Image Size must be 250X250 </span>
                            </div>
                        </div>

                        <div class="form-group">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10">
                                    <label>{{Form::radio("status","1",false,array("class"=>''))}}Enable</label>
                                    <label>{{Form::radio("status","0",false,array("class"=>''))}}Disable</label>
                                </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                {{Form::reset('Cancel',array('class'=>'btn btn-default'))}}
                                {{Form::submit('Save',array('class'=>'btn btn-primary'))}}
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     $('input[name=ref_type]').on('change',function(e){
            $('#referal').removeClass('hidden');
        })
    $('input[name=file]').on('change',function(e){

            var input=this;
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            $('#blah').removeClass('hidden')
            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(75)
                    .height(75);
            };

            reader.readAsDataURL(input.files[0]);
        }
        })
    @if(isset($subject) && $subject->type=='Paid')
        $(".paid-type").show();
    @endif
      @if(isset($subject) && $subject->data_type==1)
          $(".paid-type").hide();
             $(".type-class").attr('checked',false);
             $(".first").attr('checked', true);
             $(".type-class").attr('disabled',true);

        @endif
    $(document).on('click','.type-class',function(){
        if($(this).val()=='Paid')
            $(".paid-type").show();
        else
            $(".paid-type").hide();   
    });
     $(document).on('click','.spiritual-class',function(){
             $(".paid-type").hide();
             $(".type-class").attr('checked',false);
             $(".first").attr('checked', true);
             $(".type-class").attr('disabled',true);
     
        });
  $(document).on('click','.data_type-class',function(){
             
             $(".type-class").attr('disabled',false);
     
        });
</script>
@endsection