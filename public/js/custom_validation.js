//Chapter form validate
$(function(){
    $("#chapter-form").validate({
        rules: {
            subject_id: { required: true, },
            chapter: { required: true, },
            file  : { accept: "jpg,png,jpeg,gif"},
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
//Course form validate
$(function(){
    $("#course-form").validate({
        rules: {
            course: { required: true, },
            file  : { accept: "jpg,png,jpeg,gif"},
            duration: {required :true},
            amount: {required :true},
            show_home:{required :true},
            status:{required :true}
        },
        submitHandler: function(form) {
            form.submit();
        }
        
    });
});
//Document form validate
/*$(function(){
    $("#document-form").validate({

        rules: {
            //topic_id: { required: true },
            title: { required: true },
            author_name: { required: true },
            description: { required: true },
            'user_type[]': { required: false },
            'doc_type[]' : { required :true },
            video_type : { required : true },
            video_url : {required: true, url: true},
            video_file : {required : function(){
                            return $("#id").val() < 0;
                            }, 
                        accept: "video/!*" },
            audio_file : {required : function(){
                            return $("#id").val() < 0;
                        }, 
                        accept: "audio/!*"},
            preview : {
                accept: "jpg,png,jpeg,gif"                
            }   
        },
        messages:{
            video_file:{ accept: "Please Select only Video File",},
            audio_file:{ accept: "Please Select only Audio File",},
            preview:{ accept: "Please Select only Image File",}
        },
        submitHandler: function(form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            //documentAdd();
            form.submit(); // <- use 'form' argument here.
        }
    });
});*/
//Subject Form Validate
$(function(){
    $("#subject-form").validate({
        rules: {
            course_id: { required: true, },
            subject: { required: true,},
            file  : { accept: "jpg,png,jpeg,gif"},
            duration: {required :true},
            amount: {required :true},
           
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
// Topic Form Validate
$(function(){
    $("#topic-form").validate({
        rules: {
            chapter_id: { required: true },
            topic: { required: true },
            file  : { accept: "jpg,png,jpeg,gif"},
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

//multiple course Delete Form
$(function(){
    $("#multiple-course-form").validate({
       rules: {
            'id[]': { required: true }
        },
        submitHandler: function(form) {
            form.submit();
        } 
    });
});

//multiple Chapter delete Form
$(function(){
    $("#multiple-chapter-form").validate({
       rules: {
            'id[]': { required: true }
        },
        submitHandler: function(form) {
            form.submit();
        } 
    });
});

//multiple Subject delete Form
$(function(){
    $("#multiple-subject-form").validate({
       rules: {
            'id[]': { required: true }
        },
        submitHandler: function(form) {
            form.submit();
        } 
    });
})

//multiple Topic delete Form
$(function(){
    $("#multiple-topic-form").validate({
       rules: {
            'id[]': { required: true }
        },
        submitHandler: function(form) {
            form.submit();
        } 
    });
});


$(function(){
    $("#plan-form").validate({
       rules: {
            'name': { required: true },
            'duration': { required: true, digits: true },
            'amount': { required: true }
        },
        submitHandler: function(form) {
            form.submit();
        } 
    });
});
$(function(){
    $("#home-image-form").validate({
        rules: {
             image  : {required :true, accept: "jpg,png,jpeg,gif"},
            },
        submitHandler: function(form) {
            form.submit();
        }
        
    });
});