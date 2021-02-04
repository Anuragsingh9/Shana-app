@extends('layouts.front')

@section('content')

<div id="signup_banner">
    <div id="signup_form">
        <!-- multistep form -->
        <form id="msform" action="index.html" method="POST">
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Number Setup</li>
                <li>OTP Setup</li>
                <li>Profiles</li>
            </ul>
            <!-- fieldsets -->
            <fieldset>
                <h2 class="login-f-title">Enter Your Mobile Number</h2>
                <!-- <h3 class="fs-subtitle">This is step 1</h3> -->
                <div id="form">
                    <input type="text" name="signup_number" id="mobile_no" placeholder="Mobile Number" /> 
                    <span class="text-danger" id="mobile-check-msg"></span>
                </div>
                <input type="button" name="next" class="next action-button" id="send_otp" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="login-f-title">Enter Your OTP</h2>
                <!-- <h3 class="fs-subtitle">Confirm your OTP</h3> -->
                <div id="form">
                    <input type="text" name="user_number" placeholder="Mobile Number" />
                    <input type="text" name="singup_otp" id="otp" placeholder="OTP" />
                </div>
                <input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="button" name="next" class="next action-button" id="verify_otp" value="Next" />
            </fieldset>
            <fieldset>
                <h2 class="login-f-title">Other Details</h2>
                <!-- <h3 class="fs-subtitle">We will never sell it</h3> -->
                <div id="form">
                    <input type="text" name="user_number" placeholder="Enter your phone no." />
                    <input type="text" name="user_name" placeholder="Enter your name" />
                    <input type="password" name="user_pass" placeholder="Enter your password" />
                    <input type="email" name="user_email" placeholder="Enter your email address" />
                    <input type="text" name="user_ref_code" placeholder="Enter your user referal code" />
                    <input type="hidden" name="user_photo"/>
                </div>
                <input type="button" name="previous" class="previous action-button" value="Previous" />
                <input type="button" name="submit" class="submit action-button" id="add_profile" value="Submit" />
                <div class="text-danger" id="errorMsg"></div>
            </fieldset> 
        </form>
    </div>
</div>
@endsection

@push('script')
<script>
   $('input[name=user_name]').val("{{ (Session::has('name'))?Session::get('name'):'' }}")
   $('input[name=user_email]').val("{{ (Session::has('email'))?Session::get('email'):'' }}")
   $('input[name=user_photo]').val("{{ (Session::has('photo'))?Session::get('photo'):'' }}")

jQuery(document).ready(function () {

    var serverOtp=0;
    var mob=0;
    jQuery.validator.addMethod("otp_check", function(value, element, param) {
    return value === serverOtp;
  }, jQuery.validator.format("Otp Incorrect."));
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches
$(".submit").click(function() {
    $('#msform').validate({
            rules: {    
                user_name: {
                    required: true
                },
                user_pass: {
                    required: true,
                    minlength:6,
                },
                user_email: {
                    // required: true,
                    email: true
                }
            },
            messages: {
                user_name: "Enter your name.",
                user_pass: {
                    required:"Enter Password.",
                    minlength:"Password Must be 6 Long."},
                user_email: {
                    required:"Enter your email address",
                    email:"Email Invalid"}
            }
        });
    if ((!$('#msform').valid())) {
            return false;
        }
        if (($('#msform').valid())) {
            var number=$('input[name=user_number]').val();
            var user_name=$('input[name=user_name]').val();
            var user_pass=$('input[name=user_pass]').val();
            var user_email=$('input[name=user_email]').val();
            var user_photo=$('input[name=user_photo]').val();
            var ref_code=$('input[name=user_ref_code]').val();
            data={mobile:number,email:user_email,password:user_pass,name:user_name,photo:user_photo,ref_code:ref_code}
           var res=apiAjaxCall('add-user','POST',data)
           res.then(data => {
                   if(data.status==200)
                   {
                       path= '{{ URL('') }}';  
                    window.location.href =path+'/';
                   }
                   else if(data.status==201){
                       $('#errorMsg').html(data.msg)
                   }
                });
        }
});
    $(".next").click(function () {
        $('#msform').validate({
            rules: {    
                signup_number: {
                    required: true,
                    minlength:10,
                    maxlength:10,
                    number: true,
                    
                    // extension:'mov|mp4|mpeg|wmv'
                },
                singup_otp: {
                    required: true,
                    number: true,
                    otp_check:true,
                },
                user_name: {
                    required: true
                },
                user_pass: {
                    required: true,
                    minlength:6,
                },
                user_email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                signup_number:
                {
                   required:"Please Enter Mobile Number",  
                   minlength:"Mobile Number Must Be valid",
                   maxlength:"Mobile Number Must Be valid",
                   number:"Please Enter valid Mobile Number"
                }, 
                singup_otp:
                {
                 required: "Please Enter OTP" ,
                  otp_check:"Otp Incorrect.",
                  number:"Otp invalid"
                } ,
                user_name: "Enter your name.",
                user_pass: {
                    required:"Enter Password.",
                    minlength:"Password Must be 6 Long."},
                user_email: {
                    required:"Enter your email address",
                    email:"Email Invalid"}
            }
        });

        if ((!$('#msform').valid())) {
            return false;
        }
        if (($('#msform').valid())) {
            if(this.id=='send_otp')
            {
                var mobNo=$('#mobile_no').val();
                if(mobNo.length>0){   
                var data=doSignUp('registration','POST',mobNo)
                data.then(data => {
                    if(data.status==200){
                    // console.log(data)
                    serverOtp=data.data.otp;
                    // console.log(serverOtp)
                        mob=data.status;
                        // $('#msform').rules('remove', 'mobile_check');
                    }
                    else
                    {
                        
                    }
                });
                
                $('input[name=user_number]').val(mobNo)
                $('input[name=user_number]').attr('disabled', true);
                }
            }
            else if(this.id=='verify_otp')
            {
                 var otp=$('#otp').val();
                 var mobNo=$('input[name=user_number]').val();
                 var data=doOtpVerify('otp-verify','POST',mobNo,otp)
                    data.then(data => {
                        console.log(data.status)
                        if(data.status==200)
                        {
                            return true;
                        }
                        
                    });
            
            }
            
        }
       
        if (animating) return false;
        animating = false;

        current_fs = $(this).parent();              //fieldset
        next_fs = $(this).parent().next();          //next fieldset

        //activate next step on progressbar using the index of next_fs
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50) + "%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'transform': 'scale(' + scale + ')'
                });
                next_fs.css({
                    'left': left,
                    'opacity': opacity
                });
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
   
    });

    $(".previous").click(function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({
            opacity: 0
        }, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({
                    'left': left
                });
                previous_fs.css({
                    'transform': 'scale(' + scale + ')',
                    'opacity': opacity
                });
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
            //this comes from the custom easing plugin
            easing: 'easeInOutBack'
        });
    });

    // $(".submit").click(function () {
    //     return false;
    // });
}); 
$('input[name=signup_number]').on('change',function(e){
    // console.log(e.target.value.length)
    if(e.target.value.length==10)
    {
        data={mobile:e.target.value}
           var res=apiAjaxCall('mobile-check','POST',data)
           res.then(data => {
                   if(data.status==200)
                   {
                    $("#mobile-check-msg").html('This mobile Number Already Taken.');

                    $("#send_otp").attr('disabled', true);
                   }
                   else
                   {
                     $("#mobile-check-msg").html('');
                    $("#send_otp").attr('disabled', false);
                   }
                });
    } 
})

</script>
@endpush