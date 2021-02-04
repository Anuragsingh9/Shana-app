// Smooth Scrolling
// jQuery.smoothScroll();

// Inner Page Banner Fixed on scroll
/*if(window.innerWidth>=768){
    jQuery(window).scroll(function(event) {
        // Inner Page Banner Fixed on window scrolling
        var scrolled_banner = jQuery(window).scrollTop();
        var banner_height = jQuery('header').height()+jQuery('.banner_section').height() -100;
        if(scrolled_banner>banner_height){
          jQuery('.banner_section').addClass('banner_fixed');
        //   jQuery('.page_main_sec').css('padding-top','200px');
        }else{
          jQuery('.banner_section').removeClass('banner_fixed');
        //   jQuery('.page_main_sec').css('padding-top','50px');
        }
    });
}*/

// Home Page Course Menu Fixed on scroll
if(window.innerWidth>=768){
  jQuery(window).scroll(function(event) {
    var scrolled = jQuery(window).scrollTop();
    var slider_height = jQuery('header').height()+jQuery('#homeSlider').height();
    if(scrolled>slider_height){
      jQuery('.menulist_section').addClass('course_names_fixed');
    }else{
      jQuery('.menulist_section').removeClass('course_names_fixed');
    }
    
  });
  
  // Add smooth scrolling to all links
  jQuery(document).on('click','.mainCoursesName ul li a',function(){
      // Make sure this.hash has a value before overriding default behavior
      if (this.hash !== "") {
        // // Prevent default anchor click behavior
        // event.preventDefault();

        // // Store hash
        var hash = this.hash;

        // Using jQuery's animate() method to add smooth page scroll
        // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
        jQuery('html, body').animate({scrollTop: jQuery(hash).offset().top -65}, 800, function(){
     
          // Add hash (#) to URL when done scrolling (default click behavior)
          window.location.hash = hash;
        });
      } // End if
  });
}




// // home page Course Toggle Menu on mobile view
// if(window.innerWidth<=767){
//   $('.list_course_name').click(function(){
//     $('.list_course_name').parent().find('.CourseSyllabus').hide();
//     $(this).parent().find('.CourseSyllabus').toggle();
//   });
// }


jQuery('.subjectSlider').owlCarousel({
  items:4,
  dots: false,
  nav: true,
  margin: 10,
  singleItem:true,
    responsive:{
        0:{
          items:2
        },
        480:{
          items:3
        },
        768:{
          items:2
        },
        1000:{
          items:3
        },
        1400:{
          items:4
        }
    }
});