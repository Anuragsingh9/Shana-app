<?php
 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clear', function () {
    $exitCode = Artisan::call('config:cache');
    dd($exitCode);
})->name('/clear');
Route::get('/', function () {
    return view('front.home');
})->name('/');
Route::get('/courses/{id}', function () {
    return view('front.course');
});
Route::get('/play-media/{id}', function () {
    return view('front.play');
});
Route::get('/about-us', function () {
    return view('front.about'); 
});
Route::get('/term-policy', function () {
    return view('front.about'); 
});
Route::get('/contact-us', function () {
    return view('front.about'); 
});
Route::get('/refund-policy', function () {
    return view('front.about');
});
Route::get('/privacy-policy', function () {
    return view('front.about'); 
});
Route::get('/event', function () {
    return view('front.event'); 
});Route::get('/inspire', function () {
    return view('front.inspire'); 
});
Route::get('/documents/{id}', function () {
    return view('front.documents');
});
Route::get('/multi-documents/{topic_id}', function () {
    return view('front.multi-document');
});
Route::get('/course-subjects/{id}', function () {
    return view('front.course-subjects');
});
Route::get('/topic/{id}', function () {
    return view('front.topic');
});
Route::get('/signUp', function () {
    return view('front.sign_up');
});
Route::get('/plans', function () {
    return view('front.plans');
});
Route::get('/profile', function () {
//    return Auth::user();
    return view('front.profile');
});
Route::get('/customOut', 'Auth\LoginController@customLogout')->name('customOut');
Route::get('/search', function () {
    return view('front.search');
});


Route::get('/blog', function () {
    return view('front.blog'); 
});
Route::get('/view-book/{id}', function () {
    return view('front.blog_view'); 
});
// login check raoute for plan purchase
Route::get('/login-check', function () {
    return view('front.loginCheck'); 
});
Route::get('socialLogin/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/login', 'Auth\LoginController@showLoginForm');

Auth::routes();
Route::group(['middleware' => 'loginCheck'], function () {
    // Get Route For Show Payment Form
    Route::post('/paywithrazorpay', 'HomeController@payWithRazorpay')->name('paywithrazorpay');
    Route::post('/subjectcoursepay', 'HomeController@subjectCoursePay')->name('subjectcoursepay');
    // Post Route For Makw Payment Request
    Route::post('/payment', 'HomeController@payment')->name('payment');
    Route::post('/subjectCoursePayment', 'HomeController@subjectCoursePayment')->name('subjectCoursePayment');
    Route::get('/pay',['uses'=>'HomeController@pay']);
});
Route::group(['middleware' => 'auth'], function () {
// Route::get('/generateCoupon', 'AdminController@generateCoupon')->name('generateCoupon');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');

//Route::get('/course',['uses'=>'AdminController@course','as'=>'course']);
Route::get('/subject',['uses'=>'AdminController@course','as'=>'subject']);
Route::get('/subject-list',['uses'=>'AdminController@course_list','as'=>'subject-list']);
Route::post('/insert-course',['uses'=>'AdminController@insert_course']);
Route::get('/edit-course/{id}',['uses'=>'AdminController@edit_course']);
Route::post('/update-course/{id}',['uses'=>'AdminController@update_course']);
Route::get('/status-change-course/{id}',['uses'=>'AdminController@statusChangeCourse']);
Route::get('/soft-delete-course/{id}',['uses'=>'AdminController@soft_delete']);
Route::get('/soft-restore-course/{id}',['uses'=>'AdminController@soft_restore']);
Route::post('/multiple-course-delete',['uses'=>'AdminController@multiple_course_delete']);
Route::post('/multiple-course-restore',['uses'=>'AdminController@multiple_course_restore']);


//Route::get('/subject',['uses'=>'AdminController@subject','as'=>'subject']);
Route::get('/course',['uses'=>'AdminController@subject','as'=>'course']);
Route::get('/course-list',['uses'=>'AdminController@subject_list','as'=>'course-list']);
Route::post('/insert-subject',['uses'=>'AdminController@insert_subject']);
Route::get('/edit-subject/{id}',['uses'=>'AdminController@edit_subject']);
Route::post('/update-subject/{id}',['uses'=>'AdminController@update_subject']);
Route::get('/delete-subject/{id}',['uses'=>'AdminController@delete_subject']);
Route::get('/status-change-subject/{id}',['uses'=>'AdminController@statusChangeSubject']);
Route::get('/soft-delete-subject/{id}',['uses'=>'AdminController@soft_deleteSubject']);
Route::get('/soft-restore-subject/{id}',['uses'=>'AdminController@soft_restoreSubject']);
Route::post('/multiple-subject-delete',['uses'=>'AdminController@multiple_subject_delete']);
Route::post('/multiple-subject-restore',['uses'=>'AdminController@multiple_subject_restore']);

Route::get('/chapter',['uses'=>'AdminController@chapter','as'=>'chapter']);
Route::get('/chapter-list',['uses'=>'AdminController@chapter_list','as'=>'chapter-list']);
Route::post('/insert-chapter',['uses'=>'AdminController@insert_chapter']);
Route::get('/edit-chapter/{id}',['uses'=>'AdminController@edit_chapter']);
Route::post('/update-chapter/{id}',['uses'=>'AdminController@update_chapter']);
/*Route::get('/delete-chapter/{id}',['uses'=>'AdminController@delete_chapter']);*/
Route::get('/status-change-chapter/{id}',['uses'=>'AdminController@statusChangeChapter']);
Route::get('/soft-delete-chapter/{id}',['uses'=>'AdminController@soft_deleteChapter']);
Route::get('/soft-restore-chapter/{id}',['uses'=>'AdminController@soft_restoreChapter']);
Route::post('/multiple-chapter-delete',['uses'=>'AdminController@multiple_chapter_delete']);
Route::post('/multiple-chapter-restore',['uses'=>'AdminController@multiple_chapter_restore']);

Route::get('/topic',['uses'=>'AdminController@topic','as'=>'topic']);
Route::get('/topic-list',['uses'=>'AdminController@topic_list','as'=>'topic-list']);
Route::post('/insert-topic',['uses'=>'AdminController@insert_topic']);
Route::get('/edit-topic/{id}',['uses'=>'AdminController@edit_topic']);
Route::post('/update-topic/{id}',['uses'=>'AdminController@update_topic']);
Route::get('/delete-topic/{id}',['uses'=>'AdminController@delete_topic']);
Route::get('/status-change-topic/{id}',['uses'=>'AdminController@statusChangeTopic']);
Route::get('/soft-delete-topic/{id}',['uses'=>'AdminController@soft_deleteTopic']);
Route::get('/soft-restore-topic/{id}',['uses'=>'AdminController@soft_restoreTopic']);
Route::post('/multiple-topic-delete',['uses'=>'AdminController@multiple_topic_delete']);
Route::post('/multiple-topic-restore',['uses'=>'AdminController@multiple_topic_restore']);
//add siteInfo details
Route::get('/edit/{siteInfo}',['uses'=>'AdminController@addAbout']);
Route::post('/update-site-info/{siteInfo}',['uses'=>'AdminController@addAbout']);
Route::get('/site-info',['uses'=>'AdminController@siteInfo']);


Route::get('/document',['uses'=>'AdminController@document','as'=>'document']);
Route::get('/document_prog',['uses'=>'AdminController@document_prog','as'=>'document_prog']);
//Route::get('/document-list',['uses'=>'AdminController@document_list','as'=>'document-list']);
Route::post('/insert-document',['uses'=>'AdminController@insert_document']);
Route::get('/edit-document/{id}',['uses'=>'AdminController@edit_document']);
Route::get('/edit-media/{media_id}',['uses'=>'AdminController@edit_media']);
Route::get('/delete-media/{media_id}',['uses'=>'AdminController@delete_media']);
Route::post('/update-document/{id}',['uses'=>'AdminController@update_document']);
Route::post('/update-media',['uses'=>'AdminController@update_media']);
Route::get('/delete-document/{id}',['uses'=>'AdminController@delete_document']);
Route::get('/document-list',['uses'=>'AdminController@document_list2','as'=>'document-list2']);
Route::get('/sub-document-list/{id}',['uses'=>'AdminController@sub_document_list2','as'=>'sub-document-list2']);
Route::get('/chap-document-list/{id}',['uses'=>'AdminController@chap_document_list2','as'=>'chap-document-list2']);
Route::get('/topic-document-list/{id}',['uses'=>'AdminController@topic_document_list2','as'=>'topic-document-list2']);
Route::get('/doc-document-list/{id}',['uses'=>'AdminController@doc_document_list2','as'=>'doc-document-list2']);
Route::get('/media-document-list/{id}',['uses'=>'AdminController@media_document_list2','as'=>'media-document-list2']);
//Multi document add
Route::get('/multi/document',['uses'=>'AdminController@multiDocument','as'=>'multiDocument']);
Route::post('/insert-multi-document',['uses'=>'AdminController@insertMultiDocument']);

Route::get('splash',['uses'=>'AdminController@splashImages']);
Route::get('/edit-splash/{id}',['uses'=>'AdminController@edit_splash']);
Route::post('/update-splash/{id}',['uses'=>'AdminController@update_splash']);
//home images

Route::get('plan-history',['uses'=>'AdminController@planHistory']);
Route::get('other-history',['uses'=>'AdminController@otherHistory']);
Route::get('plan-coupons/{id}',['uses'=>'AdminController@planCoupons']);

Route::get('/home-images-list',['uses'=>'AdminController@homeImagelist','as'=>'homeImages-list']);
Route::get('/edit-home-images/{id}',['uses'=>'AdminController@edit_homeImage']);
Route::post('/update-home-images/{id}',['uses'=>'AdminController@update_homeImages']);
Route::get('/home-images',['uses'=>'AdminController@homeImages','as'=>'homeImage']);
Route::post('/insert-home-images',['uses'=>'AdminController@addHomeImages']);



Route::get('/plan-images-list',['uses'=>'AdminController@planImageSliderlist','as'=>'planImages-list']);
Route::get('/edit-plan-images/{id}',['uses'=>'AdminController@edit_planImageSlider']);
Route::post('/update-plan-images/{id}',['uses'=>'AdminController@update_planImageSlider']);
Route::get('/plan-images',['uses'=>'AdminController@planImageSlider','as'=>'planImageSlider']);
Route::post('/insert-plan-images',['uses'=>'AdminController@addPlanImageSlider']);



Route::get('/user',['uses'=>'AdminController@user']);
Route::get('/delete-user/{id}',['uses'=>'AdminController@delete_user']);
Route::get('/view-user/{id}',['uses'=>'AdminController@view_user']);

Route::get('/plan',['uses'=>'AdminController@plan','as'=>'plan']);
Route::get('/plan-list',['uses'=>'AdminController@plan_list','as'=>'plan-list']);
Route::post('/insert-plan',['uses'=>'AdminController@insert_plan']);
Route::get('/edit-plan/{id}',['uses'=>'AdminController@edit_plan']);
Route::post('/update-plan/{id}',['uses'=>'AdminController@update_plan']);
Route::get('/delete-plan/{id}',['uses'=>'AdminController@delete_plan']);


Route::get('/getSubjectByCourseId/{course_id}',['uses'=>'AdminController@getSubjectByCourseId']);
Route::get('/getChapterBySubjectId/{subject_id}',['uses'=>'AdminController@getChapterBySubjectId']);
Route::get('/getTopicByChapterId/{chapter_id}',['uses'=>'AdminController@getTopicByChapterId']);

// // Get Route For Show Payment Form
// Route::post('/paywithrazorpay', 'HomeController@payWithRazorpay')->name('paywithrazorpay');
// Route::post('/subjectcoursepay', 'HomeController@subjectCoursePay')->name('subjectcoursepay');
// // Post Route For Makw Payment Request
// Route::post('/payment', 'HomeController@payment')->name('payment');
// Route::post('/subjectCoursePayment', 'HomeController@subjectCoursePayment')->name('subjectCoursePayment');
// Route::get('/pay',['uses'=>'HomeController@pay']);
Route::get('/youtube-download',['uses'=>'AdminController@youtubeDownload']);

Route::get('/indipay/response', function (\Illuminate\Http\Request $request) {
    dd($request->all());
    //return 'Hello World';
});
Route::get('/indipay/cancel/response', function (\Illuminate\Http\Request $request) {
    dd($request->all());
    //  return 'Hello World';
});
Route::get('test', function (\Illuminate\Http\Request $request) {
    set_time_limit(0);
    ob_implicit_flush();
    $count = 1122812; // Number of e-mails you have to send. Calculate this however you wish.

    $percent = $count * 0.01; // Get 1 percent.
    echo '<span style="font-family: Verdana;">Saving your Data<br/>[';
    for($i = 0; $i < $count; $i++)
    { // Loop.. generally be from SQL I would imagine

        if (($i % $percent) == 0)
        {
            echo '|';
        }
    }
    echo ']';
    echo '<br/>Done!</font>';
});
Route::get('/push',['uses'=>'PushNotificationController@index']);
Route::get('/add-push-notification',['uses'=>'PushNotificationController@create','as'=>'addPush']);
Route::post('/save-push-notification',['uses'=>'PushNotificationController@store','as'=>'postPush']);
Route::get('/notification-list',['uses'=>'PushNotificationController@list','as'=>'notification-list']);

Route::get('/book',['uses'=>'AdminController@addBook','as'=>'book']);
Route::get('/book-list',['uses'=>'AdminController@bookList','as'=>'book-list']);
Route::post('/insert-book',['uses'=>'AdminController@insertBook','as'=>'insert-book']);
Route::get('/edit-book/{id}',['uses'=>'AdminController@editBook','as'=>'edit-book']);
Route::post('/update-book/{id}',['uses'=>'AdminController@updateBook','as'=>'update-book']);

Route::get('/status-change-book/{id}',['uses'=>'AdminController@statusChangeBook']);
Route::get('/soft-delete-book/{id}',['uses'=>'AdminController@soft_deleteBook']);
Route::get('/soft-restore-book/{id}',['uses'=>'AdminController@soft_restoreBook']);
Route::post('/multiple-book-delete',['uses'=>'AdminController@multiple_book_delete']);
Route::post('/multiple-book-restore',['uses'=>'AdminController@multiple_book_restore']);

Route::get('/check-order/{value}/{id}',['uses'=>'AdminController@checkBookOrder']);
});