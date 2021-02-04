//var base_url='http://localhost/work/shana_app/public/api/';
//var base_url = 'http://192.168.1.14/work/shana_app/public/api/';
//var base_url='http://localhost/shana_app/public/api/';
// var base_url='http://192.168.1.121/shana_app/public/api/';
var base_url='//127.0.0.1/work/shana-laravel/public/api/';
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
function gethomeSlider(url, type,user_id, path_url) {
    $.ajax({
        type: type,
        url: base_url + url,
        data: {user_id:user_id},
        success: function (data) {
            //console.log(data.data);
            $('#dataList').removeClass('hidden');

            if (data.status) {
                var html = '';
                var course = '';
                var subject = '';
                var subjectInner = '';
                var allData = data.data;
                $.each(allData, function (i, e) {

                    if (i == 'slider') {

                        $.each(e, function (key, value) {
                            html += '<div class="item">';
                            html += '   <div class="">';
                            html += '   </div><img src="' + value.image + '" class="img-responsive">  </div>';
                        });
                    }
                    if (i == 'course') {
                        $.each(e, function (key, value) {
                            window.location.href + 'course';
                            
                            if(key<=4)
                            {
                            course += '<li><a href="#sec' + (value.id) + '">' + value.course + '</a></li>';
                            }
                            subject += '<div class="course_details_row" id="sec' + (value.id) + '">';
                            subject += '  <div class="row">  <div class="col-xs-12 col-sm-3 list_course_name ">';
                            if (value.what_next == "document") {
                                localStorage.setItem('document_' + value.id, 'course');
                                subject += '<a href="' + path_url + '/multi-documents/' + value.id + '" class="course_document"  data-id="' + value.id + '">'
                                
                                subject += '<div class="course-icon"><div class="course-icon-img" style="background:url('+ value.image +') no-repeat"></div></div><h4>' 
                                if(value.type=='Paid')
                                {
                                    if(value.isUserBuy)
                                {
                                subject += value.course;
                                }
                               else
                                {
                                   subject += '<i class="fa fa-lock"></i>'+ value.course;
                                }
                            }
                            else{
                                subject += value.course;
                            } 
                                subject +='</h4></a> </div>';
                            }
                            else {
                                subject += '<a href="' + path_url + '/courses/' + value.id + '" style="color:#'+value.color+'">';
                                subject += '<div class="course-icon"><div class="course-icon-img" style="background:url('+ value.image +') no-repeat"></div></div><h4>' 
                                if(value.type=='Paid')
                                {
                                if(value.isUserBuy )
                                {
                                    subject += value.course;
                                }
                               else
                                {
                                   subject += '<i class="fa fa-lock"></i>'+ value.course;
                                } 
                            }
                            else{
                                subject += value.course;
                            }
                                subject +='</h4></a></div>';
                            }
                            subject += '   <div class="col-xs-12 col-sm-9 CourseSyllabus"><ul>';
                            //console.log(value);
                            if (value.innerItems) {
                                $.each(value.innerItems, function (key, value) {
                                    if (value.type == 'document') {
                                        subjectInner += '<li><a href="' + path_url + '/documents/' + value.id + '">' + value.title + '</a></li>';
                                    }
                                    else {
                                        if (value.what_next == 'Nothing') {
                                            subjectInner += '<li style="color:#'+value.color+'">' + value.title + '</li>';
                                        }
                                        else if (value.what_next == 'document') {
                                            localStorage.setItem('document_' + value.id, 'subject');
                                            subjectInner += '<li><a href="' + path_url + '/multi-documents/' + value.id + '">' + value.title + '</a></li>';
                                        }
                                        else {
                                            subjectInner += '<li><a href="' + path_url + '/course-subjects/' + value.id + '">' + value.title + '</a></li>';
                                        }

                                    }
                                });
                            }
                            subject += subjectInner + ' </ul> </div> </div> </div>';
                            subjectInner = '';
                            $('.courseNameList').find('.container').append(subject);
                            subject = '';
                        });


                    }


                });
                $('#homeSlider').html(html);

                $('#homeSlider').owlCarousel({
                    loop: true,
                    items: 1,
                    dots: true,
                    nav: true,
                    margin: 0,
                });

                $('#course').html(course);
                $('#loader').addClass('hidden');


            }

        }

    });


}

//++++++++++++++++++++++++++++++++++++++++++++++++++ sign up +++++++++++++++++++++++++++++++++++++++++
function doSignUp(url, type, mobile_no) {
    var returnObject = {};
    return $.ajax({
        type: type,
        url: base_url + url,
        data: {_token: CSRF_TOKEN, mobile: mobile_no},
        success: function (data) {


        }
    });
    //return returnObject;

}

function doOtpVerify(url, type, mobile_no, otp) {
    return $.ajax({
        type: type,
        url: base_url + url,
        data: {_token: CSRF_TOKEN, mobile: mobile_no, otp: otp},
        success: function (data) {
        }
    })
}

function apiAjaxCall(url, type, data) {
    data._token = CSRF_TOKEN;
    return $.ajax({
        type: type,
        url: base_url + url,
        data: data,
        success: function (data) {
        },
        error: function (data) {
            swal(data.responseJSON.message)
        }

    })
}
function mediaAjaxCall(url, type, data) {

    return $.ajax({
        type: type,
        url: base_url + url,
        data: data,
        async:false,
        success: function (data) {
        }
    })
}

