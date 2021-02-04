
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var BASEURL=$('meta[name="base-url"]').attr('content')+'/';
//delete Subject records
$('.delete-subject').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-delete-subject/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

$('.restore-subject').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, restore it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-restore-subject/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});
$('.update-subject-status').click(function(){

    var id=$(this).data("id");
    $.ajax({
        url: BASEURL+ "status-change-subject/"+id,
        success:function(response){
            location.reload();
        }
    });
});


//delete Course records
$('.delete-course').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-delete-course/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});
$('.restore-course').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, retore it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-restore-course/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

$('.update-course-status').click(function(){
    var id=$(this).data("id");
    $.ajax({
        url: BASEURL+ "status-change-course/"+id,
        success:function(response){
            location.reload();
        }
    });
});

//delete Chapter records
$('.delete-chapter').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-delete-chapter/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

$('.restore-chapter').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, restore it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-restore-chapter/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});
$('.update-chapter-status').click(function(){

    var id=$(this).data("id");
    $.ajax({
        url: BASEURL+ "status-change-chapter/"+id,
        success:function(response){
            location.reload();
        }
    });
});

//delete Topic records
$('.delete-topic').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-delete-topic/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});
$('.restore-topic').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, restore it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-restore-topic/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

$('.update-topic-status').click(function(){

    var id=$(this).data("id");
    $.ajax({
        url: BASEURL+ "status-change-topic/"+id,
        success:function(response){
            location.reload();
        }
    });
});

//delete Document records
$('.delete-document').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "delete-document/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

//delete Staff records
$('.delete-user').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "delete-user/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

//delete Plan records
$('.delete-plan').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "delete-plan/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

//change Course function
function changeCourse(course_id,subject_id=''){
    $(".subject").html('<option selected disabled>Select Subject</option>');
    $(".chapter").html('<option selected disabled>Select Chapter</option>');
    $(".topic").html('<option selected disabled>Select Topic</option>');
    localStorage.setItem('course', course_id);
    $.ajax({
        url: url+"/getSubjectByCourseId/"+course_id,
        success:function(response){
            var data=$.parseJSON(response);
            /*$(".subject").append('<option selected disabled>Select Subject</option>')*/
            $.each(data, function( index, value ) {
                if(index==subject_id)
                    $(".subject").append('<option value='+index+' selected="selected">'+value+'</option>')
                else
                    $(".subject").append('<option value='+index+'>'+value+'</option>')
            });
        }
    });
}
//change Subject Function
function changeSubject(subject_id,chapter_id=''){
    $(".chapter").html('<option selected disabled>Select Chapter</option>');
    $(".topic").html('<option selected disabled>Select Topic</option>');
    localStorage.setItem('subject', subject_id);
    $.ajax({
        url: url+"/getChapterBySubjectId/"+subject_id,
        success:function(response){
            var data=$.parseJSON(response);
            /*$(".chapter").append('<option selected disabled>Select Chapter</option>')*/
            $.each(data, function( index, value ) {
                if(index==chapter_id)
                    $(".chapter").append('<option value='+index+' selected="selected">'+value+'</option>');
                else
                    $(".chapter").append('<option value='+index+'>'+value+'</option>');
            });
        }
    });
}

//change Chapter Funcation
function changeChapter(chapter_id,topic_id=''){
    $(".topic").html('<option selected disabled>Select Topic</option>');
    localStorage.setItem('chapter', chapter_id);
    
    $.ajax({
        url: url+"/getTopicByChapterId/"+chapter_id,
        success:function(response){
            var data=$.parseJSON(response);
            $.each(data, function( index, value ) {
                if(index==topic_id)
                    $(".topic").append('<option value='+index+' selected="selected">'+value+'</option>');
                else
                    $(".topic").append('<option value='+index+'>'+value+'</option>');
            });
        }
    });
}
$('.delete-book').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-delete-book/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});

$('.restore-book').click(function(){
    var id=$(this).data("id");
    swal({
        title: "Are you sure?",
        text: "Your will not be able to recover this record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, restore it!",
        cancelButtonText: "Cancel",
    },
    function (isConfirm){
        if(isConfirm){ 
            $.ajax({
                url: BASEURL+ "soft-restore-book/"+id,
                success:function(response){
                    location.reload();
                }
            });
        }else{
            swal("Cancelled", "Your record is safe :)", "error");
        }
    });
});
$('.update-book-status').click(function(){

    var id=$(this).data("id");
    $.ajax({
        url: BASEURL+ "status-change-book/"+id,
        success:function(response){
            location.reload();
        }
    });
});
$('#orderBook').on('blur',function(e){
    var value=e.target.value;
  
    if(parseInt(value,10)>0){
         var id=$(this).data("id");
         $.ajax({
        url: BASEURL+ "check-order/"+value+'/'+id,
        success:function(response){
           $("#orderError").html(response.msg)  
           $("#btnSubmit").attr({
               disabled: (response.status==0)?true:false,
               });  
        }
    })
    }
    else{
        
        $("#orderError").html('please insert value above 0')  
        $("#btnSubmit").attr({
               disabled: (response.status==0)?true:false,
               });
    }
})