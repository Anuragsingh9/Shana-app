<?php
 
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('/registration',['uses'=>'ApiController@registration']);

Route::post('/social_login',['uses'=>'ApiController@socialLogin']);
Route::post('/chapter',['uses'=>'ApiController@chapter']);
Route::post('/document',['uses'=>'ApiController@document'])->middleware('cors');

Route::post('/library',['uses'=>'ApiController@library']);
Route::post('/add-library',['uses'=>'ApiController@addlibrary']);



Route::post('/user-profile',['uses'=>'ApiController@userProfile']);
Route::post('/add-bookmark',['uses'=>'ApiController@addBookmark']);
Route::post('/get-bookmark',['uses'=>'ApiController@getBookmark']);

Route::post('document-detail',['uses'=>'ApiController@document_detail']);
Route::post('document-detail-web',['uses'=>'ApiController@document_detail_web']);

/*
 * Api routes as per changed working by sourabh pancharia.
 * */

Route::post('/login',['uses'=>'ApiController@login']);
Route::post('/registration',['uses'=>'ApiController@userRegistration']);
Route::post('/otp-verify',['uses'=>'ApiController@otp_verify']);
Route::post('/add-user',['uses'=>'ApiController@createUser']);
Route::post('/home',['uses'=>'ApiController@getHome']);
Route::post('/spiritual',['uses'=>'ApiController@getSpiritual']);
Route::post('/course',['uses'=>'ApiController@course']);
Route::post('/subject',['uses'=>'ApiController@subject']);
Route::post('/history',['uses'=>'ApiController@getHistory']);
Route::post('/likes',['uses'=>'ApiController@getLike']);
Route::post('/add-like',['uses'=>'ApiController@addLike']);
Route::post('/update-user',['uses'=>'ApiController@updateUser']);
Route::post('/change-password',['uses'=>'ApiController@changePassword']);
Route::post('/forget-password',['uses'=>'ApiController@forgetPassword']);
Route::post('forget-password-update',['uses'=>'ApiController@forgetPasswordUpdate']);
Route::post('see-all',['uses'=>'ApiController@getCourseData']);
Route::post('/search',['uses'=>'ApiController@search']);
Route::post('/logout',['uses'=>'ApiController@logout']);
Route::post('/mobile-check',['uses'=>'ApiController@uniqueMobile']);
Route::post('/user-login',['uses'=>'Auth\LoginController@customLogin']);
Route::post('/site-details',['uses'=>'ApiController@getTermAbout']);
Route::post('/check-doc-parent',['uses'=>'ApiController@checkDocParentType']);
Route::get('/doc-url/{id}/{type}',['uses'=>'ApiController@getDocUrl']);
Route::post('/doc-purchase-data',['uses'=>'ApiController@getDocPurchase']);
Route::post('/redeem-token',['uses'=>'ApiController@getRedeemToken']);
Route::post('/coupon-list',['uses'=>'ApiController@getPlanCouponList']);
Route::post('/plan-history',['uses'=>'ApiController@getPlanHistory']);
Route::post('/plan-purchase',['uses'=>'ApiController@planPayment']);


Route::get('/front-images',['uses'=>'ApiController@getFrontImages']);
Route::post('/recent-list',['uses'=>'ApiController@getRecentList']);
Route::post('/add-course',['uses'=>'ApiController@addCourse']);
Route::get('/all-course',['uses'=>'ApiController@getCourse']);
Route::post('/user-course',['uses'=>'ApiController@getUserCourse']);
Route::post('/all-course-subject',['uses'=>'ApiController@getCourseSubject']);
Route::post('/subject-chapter',['uses'=>'ApiController@getSubjectChapter']);
Route::post('/topic',['uses'=>'ApiController@topic']);
Route::get('/alpha-courses',['uses'=>'ApiController@getCoursesAsAlpha']);
Route::get('/all-audio',['uses'=>'ApiController@getAllAudio']);
Route::get('/recent-five',['uses'=>'ApiController@getRecentFive']);
Route::get('/alpha-new',['uses'=>'ApiController@getCoursesAsAlphaNew']);
Route::get('/alpha-new2',['uses'=>'ApiController@getCoursesAsAlphaNew2']);
Route::post('/plans',['uses'=>'ApiController@getPlan']);
Route::get('/plans-web',['uses'=>'ApiController@getPlanWeb']);
Route::post('/front-home/',['uses'=>'ApiController@frontHome']);
Route::get('/books',['uses'=>'ApiController@getBooks']);
Route::get('/booksWeb',['uses'=>'ApiController@getBooksWeb']);
Route::get('/recent-books',['uses'=>'ApiController@recentbook']);
Route::post('/get-book-id',['uses'=>'ApiController@getBookById']);

Route::post('/subject-course-purchase/',['uses'=>'ApiController@courseSubjectPurchase']);
Route::post('/get-link/',['uses'=>'ApiController@getLink']);
Route::get('/get-version',['uses'=>'ApiController@getVersion']);
Route::post('/update-ref',['uses'=>'ApiController@updateRef']);