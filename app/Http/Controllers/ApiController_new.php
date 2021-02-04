<?php

namespace App\Http\Controllers;

use App\CourseUser;
use App\PlanCoupon;
use App\PlanPurchase;
use App\Role;
use App\SiteInfo;
use App\SocialLogin;
use App\Temp;
use App\User;
use App\Book;
use App\UserPurchase;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Validator;
use Illuminate\Http\Request;
use App\Staff, App\Topic, App\Document, App\ForgetPassword, App\Course, App\Chapter, App\Subject, App\Library, App\Bookmark, App\Plan;
use Hash, DB;
use YouTubeDownloader;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->core = app(\App\Http\Controllers\CoreController::class);
        $this->msg_array = array('INVALID_USER' => 'Invalid Username or Password',
            'OTP_Expired' => 'Otp Expired',
            'OTP_UNMATCH' => 'Otp Not Match',
            'OTP_VERIFY' => 'Otp Verified',
            'USER_EXIST' => 'Mobile number already exist',
            'UPDATE_1' => 'Record Update Successfully.',
            'UPDATE_0' => 'Record Not Update.',
            'INVALID_PASSWORD' => 'Invalid Old Password',
            'PASSWORD_0' => 'Password not change',
            'PASSWORD_1' => 'Password change successfully',
            'INVALID_MOBILE' => 'Mobile No. does not exist',
            'OTP_SENT' => 'OTP sent.',
            'BOOKMARK_1' => 'Bookmark Inserted Successfully',
            'BOOKMARK_0' => 'Bookmark not Inserted Successfully',
            'LIBRARY_1' => 'Library Inserted Successfully',
            'LIBRARY_0' => 'Library not Inserted',
            //'OTP_SENT'=>'OTP sent to your mobile number.please wait for message.',
        );
    }

    public function login(Request $request)
    {
        $msg = $this->msg_array['INVALID_USER'];
        $user=null;
        if(strtolower($request->type) == 'mobile'){
            $user = User::where('mobile', $request->mobile)/*->where('staffs.status', 1)
                ->leftjoin('topics>leftJoin('courses', 'staffs.course_id', '=', 'courses.id')*/
            ->first();
        }
        elseif($request->email!=''){
            $user = User::where('email', $request->email)/*->where('staffs.status', 1)
                ->leftjoin('topics>leftJoin('courses', 'staffs.course_id', '=', 'courses.id')*/
            ->first();   
        }
        
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                unset($user->password);

                //add device_token in db
                $user->update(['device_token' => $request->device_token]);
                $user->photo = cloudUrl($user->photo);

                return response()->json(['status' => 200, 'data' => $user], '200');
            }
        }
        return response()->json(['status' => 201, 'msg' => $msg], '201');
    }

    public function registration(Request $request)
    {
        $msg = $this->msg_array['USER_EXIST'];
        $user = $this->core->checkStaffExists($request->mobile);
        if ($user) {
            $array = $this->core->staffRegistration($request->all());
            if ($array)
                return response()->json(['status' => 200, 'data' => $array], '200');
        } else
            return response()->json(['error' => 201, 'msg' => $msg], 201);
    }

    public function otp_verify(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type'=>'required',
            'mobile' => (strtolower($request->type)=='mobile')?'required|exists:temp,mobile|numeric':'',
            'email' => (strtolower($request->type)=='email')?'required|exists:temp,mobile|email':'',
            'otp' => 'required|exists:temp,otp',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            if(strtolower($request->type)=='mobile'){
                $request->mobile=$request->email;
            }
            $msg = $this->msg_array['OTP_UNMATCH'];
            $user = $this->core->verifyOtp($request->mobile, $request->otp);
            if ($user) {
                $msg = $this->msg_array['OTP_VERIFY'];
                return response()->json(['status' => 200, 'data' => ['mobile' => $request->mobile]], '200');
            } elseif ($user == 'expired') {
                return response()->json(['status' => 201, 'msg' => $this->msg_array['OTP_Expired']], '201');
            } else {
                return response()->json(['status' => 201, 'msg' => $msg], '201');
            }
        }


    }

    public function changePassword(Request $request)
    {
        $msg = $this->msg_array['INVALID_PASSWORD'];
        if ($this->core->passwordMatch($request->user_id, $request->old_password)) {
            if ($this->core->updatePassword($request->user_id, $request->new_password))
                return response()->json(['status' => 200, 'msg' => $this->msg_array['PASSWORD_1']], '200');
            else
                return response()->json(['status' => 201, 'msg' => $this->msg_array['PASSWORD_0']], '201');
        }
        return response()->json(['status' => 201, 'msg' => $this->msg_array['INVALID_PASSWORD']], '201');
    }

    public function forgetPassword(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {
            $sendOtp = $this->core->forgetPassword($request->mobile);
            if ($sendOtp) {
                return response()->json(['status' => 200, 'data' => ['mobile' => $request->mobile], 'msg' => $this->msg_array['OTP_SENT']], '200');
            }
        }
        return response()->json(['status' => 201, 'msg' => $this->msg_array['INVALID_MOBILE']], '201');
    }

    public function forgetPasswordUpdate(Request $request)
    {
        if (ForgetPassword::where('mobile', $request->mobile)->where('otp', $request->otp)->first()) {
            ForgetPassword::where('mobile', $request->mobile)->delete();
            if (User::where('mobile', $request->mobile)->update(['password' => Hash::make($request->password)]))
                return response()->json(['status' => 200, 'msg' => $this->msg_array['PASSWORD_1']], '200');
        }
        return response()->json(['status' => 201, 'msg' => $this->msg_array['OTP_UNMATCH']], '201');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function course(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {

            $data = Course::where("status", 1)->get(['id', 'course', 'image', 'amount', 'type', 'color']);

            foreach ($data as $datum) {
                if (count(Subject::where('course_id', $datum->id)->first()) > 0) {
                    $next = 'Subject';
                } elseif (Document::where('course_id', $datum->id)->count() > 0) {
                    $next = 'document';
                } else {
                    $next = 'Nothing';
                }
                if (strtolower($datum->type) != 'free') {
                    $user_pur = UserPurchase::where(['user_id' => $request->id, 'item_id' => $datum->id, 'item_type' => 0])->count();
                    if ($user_pur > 0) {
                        $isUserBuy = true;
                    } else {
                        $isUserBuy = false;
                    }
                    $finalArray[] = [
                        'id' => $datum->id, 'name' => $datum->course, 'image' => cloudUrl($datum->image),
                        'amount' => $datum->amount, 'color' => $datum->color, 'isUserBuy' => $isUserBuy
                        , 'what_next' => $next
                    ];
                } else {
                    $finalArray[] = [
                        'id' => $datum->id, 'name' => $datum->course, 'image' => cloudUrl($datum->image),
                        'color' => $datum->color, 'what_next' => $next
                    ];

                }
            }
            return response()->json(['status' => 200, 'data' => $finalArray], '200');
        }
    }

    public function subject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {

            $data = Course::where(["status" => 1, 'id' => $request->course_id])->
            with('subject')->first(['id', 'color', 'course', 'image']);
            $finalArray = [];
            $parentImagePath='';
            if (count($data->subject) > 0) {
                foreach ($data->subject as $datum) {

                    if (count(Chapter::where('subject_id', $datum->id)->first()) > 0) {
                        $next = 'Chapter';
                    } elseif (Document::where('subject_id', $datum->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    if (strtolower($datum->type) != 'free') {
                        $user_pur = UserPurchase::where(['user_id' => $request->id, 'item_id' => $datum->id, 'item_type' => 1])->count();
                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalArray['data'][] = [
                            'id' => $datum->id, 'name' => $datum->subject, 'image' => cloudUrl(($datum->image!='uploads/preview_image/dummy.png')?$datum->image:$data->image),
                            'amount' => $datum->amount, 'isUserBuy' => $isUserBuy
                            , 'what_next' => $next,
                        ];
                    } else {
                        $finalArray['data'][] = [
                            'id' => $datum->id, 'name' => $datum->subject, 'image' => cloudUrl(($datum->image!='uploads/preview_image/dummy.png')?$datum->image:$data->image), 'what_next' => $next

                        ];

                    }
                }

                $finalArray['course_detail'] = [
                    'id' => $data->id, 'name' => $data->course, 'image' => cloudUrl($data->image),
                    'color' => $data->color
                ];
            }
            return response()->json(['status' => 200, 'data' => $finalArray], '200');
        }
    }

    public function chapter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $subjectChapter = Chapter::with('subject.course')->where('subject_id', $request->subject_id)->where('status', 1)->get();
            $parentImagePath='';
            if (count($subjectChapter) > 0) {
                foreach ($subjectChapter as $item) {
                        if($item->subject->image!='uploads/preview_image/dummy.png')
                        {
                                $parentImagePath=$item->subject->image ;
                        }
                        else if($item->subject->course->image!='uploads/preview_image/dummy.png')
                        {
                                $parentImagePath=$item->subject->course->image ;
                        }
                    if (count(Topic::where('chapter_id', $item->id)->first()) > 0) {
                        $next = 'Topic';
                    } elseif (Document::where('chapter_id', $item->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    $finalData['data'][] = ['id' => $item->id, 'chapter' => $item->chapter,
                        'image' => cloudUrl(($item->image!='uploads/preview_image/dummy.png')?$item->image:$subjectChapter[0]->subject->image),
                        'what_next' => $next];
                }

                $finalData['subject_detail'] = [
                    'id' => $subjectChapter[0]->subject->id, 'name' => $subjectChapter[0]->subject->subject,
                    'image' => cloudUrl($subjectChapter[0]->subject->image),
                    'color' => $subjectChapter[0]->subject->course->color, 'course_name' => $subjectChapter[0]->subject->course->course
                ];

            } else {
                $finalData = [];
            }

            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

    public function topic(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chapter_id' => 'required|exists:chapters,id|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $chapterTopic = Topic::with('chapter.subject.course', 'document')
                ->where('chapter_id', $request->chapter_id)->where('status', 1)->get();
                   $parentImagePath='';
            if (count($chapterTopic) > 0) {
                foreach ($chapterTopic as $item) {
                        if($item->chapter->subject->image!='uploads/preview_image/dummy.png')
                        {
                                $parentImagePath=$item->chapter->subject->image ;
                        }
                        else if($item->chapter->subject->course->image!='uploads/preview_image/dummy.png')
                        {
                                $parentImagePath=$item->chapter->subject->course->image ;
                        }
                    $bookmark = Topic::where('topics.id', $item->id)
                        ->leftJoin('bookmarks', function ($join) use ($request) {
                            $join->where('bookmarks.staff_id', $request->user_id);
                            $join->whereRaw('FIND_IN_SET(topics.id,bookmarks.topic_id) > 0 ');
                        })->first();

                    if (!empty($bookmark->topic_id) || $bookmark->topic_id != '') {
                        $explode = explode(',', $bookmark->topic_id);
                        if (in_array($item->id, $explode)) {
                            $isBookmark = true;
                        } else {
                            $isBookmark = false;
                        }

                    } else {
                        $isBookmark = false;
                    }

                    if (Document::where('topic_id', $item->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }


                    if ($item->chapter->subject->course->type=='Free') {

                        $isParentFree = 'Free';
                    } else {
                        $isParentFree  = 'Paid';
                    }



                    foreach ($item->document as $docItem) {
                        $nextDocumentMedia = Document::where('documents.id', $docItem->id)->with('media')
                            ->first();
                        $seen = Document::where('documents.id', $docItem->id)
                            ->select([DB::raw("count(library_history.staff_id) as seen")
                            ])
                            ->leftJoin('library as library_history', function ($join) use ($request) {
                                $join->where('library_history.staff_id', $request->user_id);
                                $join->whereRaw('FIND_IN_SET(documents.id,library_history.history_id) > 0 ');
                            })->first();
                        foreach ($nextDocumentMedia->media as $items) {
                            if ($items->doc_type == 'Audio')
                                $audioType[] = 'Audio';
                            elseif ($items->doc_type == 'Video')
                                $audioType[] = 'Video';
                            else
                                $audioType[] = 'Text';
                        }

                        if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                            $type = 'AVT';
                        elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                            $type = 'AV';
                        } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                            $type = 'AT';
                        } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                            $type = 'VT';
                        } else {
                            $type = $audioType[0];
                        }




                        $user_pur = UserPurchase::where('user_id',$request->user_id)
                            ->where(function ($q) use($docItem){
                                $q->whereIn('item_id', [$docItem->course_id, $docItem->subject_id]);
                                $q->orWhere('item_type',2);
                            })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                            ->count();

                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }

                        $doc[] = ['type' => 'document', 'id' => $docItem->id,
                            'text' => $docItem->title, 'author_name' => $docItem->author_name,
                            'image' => cloudUrl(($docItem->preview_image!='uploads/preview_image/dummy.png')?$docItem->preview_image:$parentImagePath), 'doc_type' => $type, 'seen' => $seen->seen,'isUserBuy'=>$isUserBuy,'isParentFree'=>$isParentFree
                        ];
                        $type = [];
                        $audioType = [];
                    }

                    $finalData['data'][] = [
                        'id' => $item->id, 'topic' => $item->topic,
                        'image' => cloudUrl(($item->image!='uploads/preview_image/dummy.png')?$item->image:$parentImagePath), 'bookmark' => $isBookmark,
                        'color' => (isset($item->chapter->subject->course->color) ? $item->chapter->subject->course->color : 000000),
                        'chapter_name' => $item->chapter->chapter, 'document' => isset($doc) ? $doc : ($doc = [])
                    ];
                    $doc = [];
                }

                $finalData['chapter_detail'] = [
                    'id' => $chapterTopic[0]->chapter->id, 'name' => $chapterTopic[0]->chapter->chapter,
                    'image' => cloudUrl(($chapterTopic[0]->chapter->image!='uploads/preview_image/dummy.png')?$chapterTopic[0]->chapter->image:$parentImagePath),
                    'color' => $chapterTopic[0]->chapter->subject->course->color,
                    'course_name' => $chapterTopic[0]->chapter->subject->course->course,
                    'subject_name' => $chapterTopic[0]->chapter->subject->subject
                ];
            } else {
                $finalData = [];
            }

            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

    public function document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
            'id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $whereId = strtolower($request->type) . '_id';
            //topic_id, user_id
            $user_id = $request->user_id;
            $data = DB::table('documents')
                ->select(['documents.id as document_id','documents.course_id','documents.subject_id', 'documents.title', 'documents.doc_type', 'documents.author_name', 'documents.preview_image', DB::raw("count(library_history.staff_id) as seen"), DB::raw("count(bookmarks.staff_id) as bookmark")
                ])
                //->leftjoin('topics', 'topics.id', '=', 'documents.topic_id')
                ->leftJoin('library as library_history', function ($join) use ($user_id) {
                    $join->where('library_history.staff_id', $user_id);
                    $join->whereRaw('FIND_IN_SET(documents.id,library_history.history_id) > 0 ');
                })
                ->leftJoin('bookmarks', function ($join) use ($user_id) {
                    $join->where('bookmarks.staff_id', $user_id);
                    $join->whereRaw('FIND_IN_SET(documents.id,bookmarks.document_id) > 0 ');
                })
                ->where('documents.status', 1)
                ->where('documents.' . $whereId, $request->id)
                ->groupBy('documents.id')
                ->get();

            $type = strtolower($request->type);

            $$type = DB::table(strtolower($request->type) . 's')->where('id', $request->id)->first();
            if ($type == 'subject') {
                $course = Course::find($$type->course_id);

                if ($course->type=='Free') {

                    $isParentFree = 'Free';
                } else {
                    $isParentFree  = 'Paid';
                }
            } elseif ($type == 'chapter') {
                $subject = Subject::where('id',$$type->subject_id)->with('course')->first();
                if ($subject->course->type=='Free') {

                    $isParentFree = 'Free';
                } else {
                    $isParentFree  = 'Paid';
                }
            } elseif ($type == 'topic') {
                $chapter = Chapter::where('id',$$type->chapter_id)->with('subject.course')->first();
                if ($chapter->subject->course->type=='Free') {

                    $isParentFree = 'Free';
                } else {
                    $isParentFree  = 'Paid';
                }
            } else {
                if ($$type->type=='Free') {

                    $isParentFree = 'Free';
                } else {
                    $isParentFree  = 'Paid';
                }
            }



            //giving course_id as document id bcz here getting data as per group by title
            if (count($data) > 0) {
                foreach ($data as $datum) {
                    $nextDocumentMedia = Document::where('id', $datum->document_id)->with('media')->first();
                   if(count($nextDocumentMedia->media)>0){
                    foreach ($nextDocumentMedia->media as $items) {
                        if ($items->doc_type == 'Audio')
                            $audioType[] = 'Audio';
                        elseif ($items->doc_type == 'Video')
                            $audioType[] = 'Video';
                        else
                            $audioType[] = 'Text';
                    }

                    if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                        $type = 'AVT';
                    elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                        $type = 'AV';
                    } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                        $type = 'AT';
                    } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                        $type = 'VT';
                    } else {
                        $type = $audioType[0];
                    }

                    $user_pur = UserPurchase::where('user_id',$request->user_id)
                        ->where(function ($q) use($datum){
                            $q->whereIn('item_id', [$datum->course_id, $datum->subject_id]);
                            $q->orWhere('item_type',2);
                        })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                        ->count();
                    if ($user_pur > 0) {
                        $isUserBuy = true;
                    } else {
                        $isUserBuy = false;
                    }

                    $doc[] = ['type' => 'document', 'id' => $datum->document_id,
                        'text' => $datum->title, 'author_name' => $datum->author_name,
                        'image' => cloudUrl($datum->preview_image),'isUserBuy'=>$isUserBuy, 'doc_type' => $type, 'seen' => $datum->seen,'isParentFree'=>$isParentFree
                    ];

                    $type = [];
                    $audioType = [];
                   }
                }


                //get type detail
                $type = strtolower($request->type);
                $type_table = strtolower($request->type) . 's';

                $$type = DB::table(strtolower($request->type) . 's')
                    ->select([(strtolower($request->type) . 's') . '.*', DB::raw("count(bookmarks.staff_id) as bookmark")
                    ])
                    ->where((strtolower($request->type) . 's') . '.id', $request->id)->leftJoin('bookmarks', function ($join) use ($user_id, $type_table, $type) {
                        $join->where('bookmarks.staff_id', $user_id);
                        $join->whereRaw('FIND_IN_SET(' . $type_table . '.id,bookmarks.' . $type . '_id) > 0 ');
                    })->first();

                if ($$type->bookmark == 0) {
                    $isBookmark = false;
                } else {
                    $isBookmark = true;
                }


                $finalArray['data'][] = [
                    'id' => $$type->id, 'text' => $$type->$type,
                    'image' => cloudUrl($$type->image), 'bookmark' => $isBookmark,
                    'document' => isset($doc) ? $doc : ($doc = [])
                ];
                /* $type = strtolower($request->type);

                 $$type = DB::table(strtolower($request->type) . 's')->where('id', $request->id)->first();

                 if ($type == 'subject') {
                     $course = Course::find($$type->course_id);
                     $finalArray[$type . '_detail'] = [
                         'id' => $$type->id, 'name' => $$type->$type, 'image' => ($$type->image),
                         'color' => $course->color
                     ];
                 } elseif ($type == 'chapter') {
                     $subject = Subject::find($$type->subject_id);
                     $course = Course::find($subject->course_id);
                     $finalArray[$type . '_detail'] = [
                         'id' => $$type->id, 'name' => $$type->$type, 'image' => ($$type->image),
                         'color' => $course->color
                     ];
                 } elseif ($type == 'topic') {
                     $chapter = Chapter::find($$type->chapter_id);
                     $subject = Subject::find($chapter->subject_id);
                     $course = Course::find($subject->course_id);
                     $finalArray[$type . '_detail'] = [
                         'id' => $$type->id, 'name' => $$type->$type, 'image' => ($$type->image),
                         'color' => $course->color
                     ];
                 } else {
                     $finalArray[$type . '_detail'] = [
                         'id' => $$type->id, 'name' => $$type->$type, 'image' => ($$type->image),
                         'color' => $$type->color
                     ];
                 }*/

            } else {
                $finalArray = [];
            }
            return response()->json(['status' => 200, 'data' => $finalArray], '200');
        }
    }

    public function getHome(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $data['slider'] = DB::table('home_images')->select('image')->limit(3)->orderBy('id', 'desc')->where('delated_at',null)->get();
            //courses
            $courses = Course::with(['subject', 'documents' => function ($q) {
            }])->where('courses.show_home', 1)->where('courses.data_type', 0)->where('courses.status', 1)
                ->orderBy('courses.id', 'asc')
                ->get()->toArray();

            //adding slider
            foreach ($data['slider'] as $img) {
                $slider_img[] = ['image' => cloudUrl($img->image)];
            }


            $finalData['slider'] = $slider_img;
            $allCourses = $courses;
            if (count($allCourses) > 0) {
                foreach ($allCourses as $k => $item) {

                    if (empty($item['subject']) && empty($item['documents'])) {
                        unset($allCourses[$k]);
                    }
                    if (empty($item['subject'])) {

                        unset($allCourses[$k]['subject']);
                    }
                    if (empty($item['documents'])) {
                        unset($allCourses[$k]['documents']);
                    }
                    if (!empty($item['subject'])) {
                        unset($allCourses[$k]['documents']);
                    }
                }
                foreach ($allCourses as $course) {
                    if (!empty($course['subject'])) {
                        foreach ($course['subject'] as $subject) {
                            if (count(Chapter::where('subject_id', $subject['id'])->first()) > 0) {
                                $next = 'Chapter';
                            } elseif (Document::where('subject_id', $subject['id'])->count() > 0) {
                                $next = 'document';
                            } else {
                                $next = 'Nothing';
                            }
                            $sub[] = ['type' => 'subject', 'id' => $subject['id'], 'title' => $subject['subject'], 'image' => cloudUrl(($subject['image']!='uploads/preview_image/dummy.png')?$subject['image']:$course['image']), 'color' => $course['color'], 'what_next' => $next];
                        }
                        $dataa = $sub;
                    } else {
                        foreach ($course['documents'] as $document) {
                            //giving course_id as document id bcz here getting data as per group by title

                            $nextDocumentMedia = Document::where('id', $document['id'])->with('media')->first();
                            foreach ($nextDocumentMedia->media as $items) {
                                if ($items->doc_type == 'Audio')
                                    $audioType[] = 'Audio';
                                elseif ($items->doc_type == 'Video')
                                    $audioType[] = 'Video';
                                else
                                    $audioType[] = 'Text';
                            }

                            if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                                $type = 'AVT';
                            elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                                $type = 'AV';
                            } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                                $type = 'AT';
                            } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                                $type = 'VT';
                            } else {
                                $type = $audioType[0];
                            }
                            $doc[] = ['type' => 'document', 'id' => $document['id'],
                                'title' => $document['title'], 'color' => $course['color'],
                                'author_name' => $document['author_name'],
                                'image' => cloudUrl(($document['preview_image']!='uploads/preview_image/dummy.png')?$document['preview_image']:$course['image']), 'doc_type' => $type
                            ];
                            $type = [];
                            $audioType = [];
                        }
                        $dataa = $doc;
                    }

                    if (strtolower($course['type']) != 'free') {
                        // $user_pur = UserPurchase::where(['user_id' => $request->user_id, 'item_id' => $course['id'], 'item_type' => 0])->count();
                        $user_pur = UserPurchase::where('user_id',$request->user_id)
                            ->where(function ($q) use($course){
                                $q->whereIn('item_id', [ $course['id']]);
                                $q->orWhere('item_type',2);
                            })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                            ->count();

                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalData['course'][] = [
                            'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                            'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'], 'isUserBuy' => $isUserBuy,
                            'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                            (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                        ];
                    } else {
                        $finalData['course'][] = [
                            'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                            'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'],
                            'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                            (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                        ];

                    }
                    $dataa = [];
                    $sub = [];
                    $doc = [];

                }
            } else {
                $finalData['course'] = [];
            }
            //recent history
            $finalData['history'] = $this->history('history', isset($request->user_id) ? $request->user_id : 0);
            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

 public function getSpiritual(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $data['slider'] = DB::table('home_images')->select('image')->limit(3)->orderBy('id', 'desc')->where('delated_at',null)->get();
            //courses
            $courses = Course::with(['subject', 'documents' => function ($q) {
            }])->where('courses.show_home', 1)->where('courses.data_type', 1)->where('courses.status', 1)
                ->orderBy('courses.id', 'asc')
                ->get()->toArray();

            //adding slider
            foreach ($data['slider'] as $img) {
                $slider_img[] = ['image' => cloudUrl($img->image)];
            }


            $finalData['slider'] = $slider_img;
            $allCourses = $courses;
            if (count($allCourses) > 0) {
                foreach ($allCourses as $k => $item) {

                    if (empty($item['subject']) && empty($item['documents'])) {
                        unset($allCourses[$k]);
                    }
                    if (empty($item['subject'])) {

                        unset($allCourses[$k]['subject']);
                    }
                    if (empty($item['documents'])) {
                        unset($allCourses[$k]['documents']);
                    }
                    if (!empty($item['subject'])) {
                        unset($allCourses[$k]['documents']);
                    }
                }
                foreach ($allCourses as $course) {
                    if (!empty($course['subject'])) {
                        foreach ($course['subject'] as $subject) {
                            if (count(Chapter::where('subject_id', $subject['id'])->first()) > 0) {
                                $next = 'Chapter';
                            } elseif (Document::where('subject_id', $subject['id'])->count() > 0) {
                                $next = 'document';
                            } else {
                                $next = 'Nothing';
                            }
                            $sub[] = ['type' => 'subject', 'id' => $subject['id'], 'title' => $subject['subject'], 'image' => cloudUrl(($subject['image']!='uploads/preview_image/dummy.png')?$subject['image']:$course['image']), 'color' => $course['color'], 'what_next' => $next];
                        }
                        $dataa = $sub;
                    } else {
                        foreach ($course['documents'] as $document) {
                            //giving course_id as document id bcz here getting data as per group by title

                            $nextDocumentMedia = Document::where('id', $document['id'])->with('media')->first();
                            foreach ($nextDocumentMedia->media as $items) {
                                if ($items->doc_type == 'Audio')
                                    $audioType[] = 'Audio';
                                elseif ($items->doc_type == 'Video')
                                    $audioType[] = 'Video';
                                else
                                    $audioType[] = 'Text';
                            }

                            if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                                $type = 'AVT';
                            elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                                $type = 'AV';
                            } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                                $type = 'AT';
                            } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                                $type = 'VT';
                            } else {
                                $type = $audioType[0];
                            }
                            $doc[] = ['type' => 'document', 'id' => $document['id'],
                                'title' => $document['title'], 'color' => $course['color'],
                                'author_name' => $document['author_name'],
                                'image' => cloudUrl(($document['preview_image']!='uploads/preview_image/dummy.png')?$document['preview_image']:$course['image']), 'doc_type' => $type
                            ];
                            $type = [];
                            $audioType = [];
                        }
                        $dataa = $doc;
                    }

                    if (strtolower($course['type']) != 'free') {
                        // $user_pur = UserPurchase::where(['user_id' => $request->user_id, 'item_id' => $course['id'], 'item_type' => 0])->count();
                        $user_pur = UserPurchase::where('user_id',$request->user_id)
                            ->where(function ($q) use($course){
                                $q->whereIn('item_id', [ $course['id']]);
                                $q->orWhere('item_type',2);
                            })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                            ->count();

                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalData['course'][] = [
                            'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                            'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'], 'isUserBuy' => $isUserBuy,
                            'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                            (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                        ];
                    } else {
                        $finalData['course'][] = [
                            'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                            'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'],
                            'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                            (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                        ];

                    }
                    $dataa = [];
                    $sub = [];
                    $doc = [];

                }
            } else {
                $finalData['course'] = [];
            }
            //recent history
            $finalData['history'] = $this->history('history', isset($request->user_id) ? $request->user_id : 0);
            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }
    public function history($type, $user_id)
    {
        //user_id,type("likes","history","mylist","downloads");
        $type = $type;
        $typeId = $type . '_id';
        $data = [];
        $mylist = Library::where('staff_id', $user_id)->first();
        if (!empty($mylist)) {
            $field = json_decode($mylist->$type, true);
            if (!empty($field)) {
                foreach ($field as $item) {
                    $array[] = $item['doc_id'];
                    $arraydate[$item['doc_id']] = $item['date'];
                }
                $array_placeholders = implode(',', array_fill(0, count($array), '?'));
                $array_type = Document::whereIn('documents.id', $array)
                    ->leftjoin('topics', 'topics.id', '=', 'topic_id')
                    ->orderByRaw("field(documents.id,{$array_placeholders})", $array)
                    ->get(['documents.id', 'title', 'author_name', 'description', 'preview_image', 'duration', 'doc_type']);
                foreach ($array_type as $value) {
                    if (array_key_exists($value->id, $arraydate))
                        $value->datetime = $arraydate[$value->id];
                    else
                        $value->datetime = null;
                }
                $data = $array_type;
            }
            return $data;
        }
        return $data;
    }

    public function search(Request $request)
    {
        if (!empty($request->search)) {

            $data["course"] = Course::where('course', 'like', '%' . $request->search . '%')->where("status", 1)->get(['id', 'course', 'image', 'amount', 'type', 'color']);
            $data["subject"] = Subject::where('subject', 'like', '%' . $request->search . '%')->where("status", 1)->get(['id', 'subject', 'image', 'amount', 'type']);
            $data["chapter"] = Chapter::where('chapter', 'like', '%' . $request->search . '%')->where("status", 1)->get(['id', 'chapter', 'image']);
            $data["topic"] = Topic::where('topic', 'like', '%' . $request->search . '%')->where("status", 1)->get(['id', 'topic', 'image']);
            $data["document"] = Document::where(function ($query) use ($request) {
                // $query->orWhere('content', 'like', '%' . $request->search . '%');
                $query->orWhere('title', 'like', '%' . $request->search . '%');
                // $query->orWhere('description', 'like', '%' . $request->search . '%');
            })->where("status", 1)->get(['id', 'doc_file', 'preview_image', 'title', 'doc_type', 'author_name']);

            //for course
            if (count($data["course"]) > 0) {
                foreach ($data["course"] as $datum) {
                    if (count(Subject::where('course_id', $datum->id)->first()) > 0) {
                        $next = 'Subject';
                    } elseif (Document::where('course_id', $datum->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    if (strtolower($datum->type) != 'free') {
                        $user_pur = UserPurchase::where(['user_id' => $request->id, 'item_id' => $datum->id, 'item_type' => 0])->count();
                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalArray['course'][] = [
                            'id' => $datum->id, 'title' => $datum->course, 'image' => cloudUrl($datum->image),
                            'amount' => $datum->amount, 'color' => $datum->color, 'isUserBuy' => $isUserBuy
                            , 'what_next' => $next
                        ];
                    } else {
                        $finalArray['course'][] = [
                            'id' => $datum->id, 'title' => $datum->course, 'image' => cloudUrl($datum->image),
                            'color' => $datum->color, 'what_next' => $next
                        ];

                    }
                }
            } else {
                $finalArray['course'] = [];
            }
            //for subject
            if (count($data["subject"]) > 0) {
                foreach ($data["subject"] as $datum) {
                    if (count(Chapter::where('subject_id', $datum->id)->first()) > 0) {
                        $next = 'Chapter';
                    } elseif (Document::where('subject_id', $datum->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    if (strtolower($datum->type) != 'free') {
                        $user_pur = UserPurchase::where(['user_id' => $request->id, 'item_id' => $datum->id, 'item_type' => 1])->count();
                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalArray['subject'][] = [
                            'id' => $datum->id, 'title' => $datum->subject, 'image' => cloudUrl($datum->image),
                            'amount' => $datum->amount, 'isUserBuy' => $isUserBuy
                            , 'what_next' => $next
                        ];
                    } else {
                        $finalArray['subject'][] = [
                            'id' => $datum->id, 'title' => $datum->subject, 'image' => cloudUrl($datum->image), 'what_next' => $next
                        ];

                    }
                }

            } else {
                $finalArray['subject'] = [];
            }
            //for chapter
            if (count($data["chapter"]) > 0) {
                foreach ($data["chapter"] as $item) {

                    if (count(Topic::where('chapter_id', $item->id)->first()) > 0) {
                        $next = 'Topic';
                    } elseif (Document::where('chapter_id', $item->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    $finalArray['chapter'][] = ['id' => $item->id, 'title' => $item->chapter,
                        'image' => cloudUrl($item->image),
                        'what_next' => $next];
                }


            } else {
                $finalArray['chapter'] = [];
            }
            //for topic
            if (count($data["topic"]) > 0) {
                foreach ($data["topic"] as $item) {

                    if (Document::where('topic_id', $item->id)->count() > 0) {
                        $next = 'document';
                    } else {
                        $next = 'Nothing';
                    }
                    $finalArray['topic'][] = ['id' => $item->id, 'title' => $item->topic,
                        'image' => cloudUrl($item->image),
                        'what_next' => $next];
                }


            } else {
                $finalArray['topic'] = [];
            }

            //for document
            if (count($data["document"]) > 0) {
                foreach ($data["document"] as $datum) {
                    foreach ($datum->media as $items) {
                        if ($items->doc_type == 'Audio')
                            $audioType[] = 'Audio';
                        elseif ($items->doc_type == 'Video')
                            $audioType[] = 'Video';
                        else
                            $audioType[] = 'Text';
                    }

                    if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                        $type = 'AVT';
                    elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                        $type = 'AV';
                    } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                        $type = 'AT';
                    } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                        $type = 'VT';
                    } else {
                        $type = $audioType[0];
                    }
                    $finalArray['document'][] = ['id' => $datum->id,
                        'title' => $datum->title, 'author_name' => $datum->author_name,
                        'image' => cloudUrl($datum->preview_image), 'doc_type' => $type
                    ];
                    $type = [];
                    $audioType = [];
                }
            } else {
                $finalArray['document'] = [];
            }


        } else {
            $finalArray = [];
        }


        return response()->json(['status' => 200, 'data' => $finalArray], '200');
    }

    public function library(Request $request)
    {
        //user_id,type("likes","history","mylist","downloads");
        $type = $request->type;
        $data = null;
        $mylist = Library::where('staff_id', $request->user_id)->first();
        if (!empty($mylist)) {
            $field = json_decode($mylist->$type, true);
            if (!empty($field)) {
                foreach ($field as $item) {
                    $array[] = $item['doc_id'];
                    $arraydate[$item['doc_id']] = $item['date'];
                }
                $array_placeholders = implode(',', array_fill(0, count($array), '?'));
                $array_type = Document::whereIn('documents.id', $array)
                    ->leftjoin('topics', 'topics.id', '=', 'topic_id')
                    ->orderByRaw("field(documents.id,{$array_placeholders})", $array)
                    ->get(['documents.id', 'title', 'author_name', 'description', 'preview_image', 'duration', 'doc_type']);
                foreach ($array_type as $value) {
                    if (array_key_exists($value->id, $arraydate))
                        $value->datetime = $arraydate[$value->id];
                    else
                        $value->datetime = null;
                }
                $data = $array_type;
            }
            return response()->json(['status' => 200, 'data' => $data], '200');
        }
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function addlibrary(Request $request)
    {
        $data = $this->add_library_function($request->user_id, $request->document_id, $request->type);
        if ($data['library'] == 1)
            return response()->json(['status' => 200, 'data' => $data['flag']], '200');
        else
            return response()->json(['status' => 201, 'data' => $data['flag']], '201');
    }

    function add_library_function($user_id, $document_id, $type)
    {
        $type = $type;
        $data = null;
        $flag = 1;
        $type_id = $type . "_id";
        $date = date('Y-m-d H:i:s');
        $library = Library::where('staff_id', $user_id)->first();
        if (!empty($library)) {
            $temp_doc_data = json_decode($library->$type);
            $doc_ids = explode(",", $library->$type_id);
            if (in_array($document_id, $doc_ids)) {
                unset($doc_ids[array_search($document_id, $doc_ids)]);
                if ($type != 'likes' && $type != 'mylist') {
                    $doc_ids[] = $document_id;
                }
                foreach ($temp_doc_data as $key1 => $val) {
                    if ($val->doc_id == $document_id) {
                        unset($temp_doc_data[$key1]);
                        if ($type != 'likes' && $type != 'mylist') {
                            $temp_doc_data[] = array("doc_id" => $document_id, "date" => $date);
                        } else
                            $flag = 0;
                    }
                }
            } else {
                $temp_doc_data[] = array('doc_id' => $document_id, 'date' => $date);
                $doc_ids[] = $document_id;
            }
            $doc_data = array_values($temp_doc_data);
            $data['library'] = Library::where('staff_id', $user_id)->update([$type => json_encode($doc_data), $type_id => implode(",", $doc_ids)]);
        } else {
            $doc_data[] = array("doc_id" => $document_id, 'date' => $date);
            $data['library'] = Library::insert(['staff_id' => $user_id, $type => json_encode($doc_data), $type_id => $document_id]);
        }
        $data['flag'] = $flag;
        return $data;
    }

    public function userProfile(Request $request)
    {
        $user = Staff::where('id', $request->user_id)->with('library')->first(['id', 'name', 'profile_pic', 'mobile']);
        $data = $user;
        $data['likes_count'] = 0;
        $data['mylists_count'] = 0;
        if (!empty($user->library)) {
            if ($user->library->likes != '')
                $data['likes_count'] = count(json_decode($user->library->likes));
            if ($user->library->mylist != '')
                $data['mylists_count'] = count(json_decode($user->library->mylist));
        }
        unset($data['library']);
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function addBookmark(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'field_id' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //$user_id,$field_id,$type('topic','document')
            $date = date('Y-m-d H:i:s');
            $flag = 1;
            $type = $request->type;
            $type_id = $request->type . '_id';
            //getting user bookmark
            $bookmark = Bookmark::where('staff_id', $request->user_id)->first();
            if (!empty($bookmark)) {
                //decoding type(topic,document)
                $temp_data = json_decode($bookmark->$type, true);
                $ids = ($bookmark->$type_id) ? explode(",", $bookmark->$type_id) : [];
                if (in_array($request->field_id, $ids)) {
                    unset($ids[array_search($request->field_id, $ids)]);
                    //$ids[]=$request->field_id;
                    foreach ($temp_data as $key1 => $val) {
                        if ($val['id'] == $request->field_id) {
                            unset($temp_data[$key1]);
                            $flag = 0;
                            //$temp_data[]=array("id"=>$request->field_id,"date"=>$date);
                        }
                    }
                } else {
                    $temp_data[] = array('id' => $request->field_id, 'date' => $date);
                    $ids[] = $request->field_id;
                }
                $doc_data = array_values($temp_data);
                $data['bookmark'] = Bookmark::where('staff_id', $request->user_id)->update([$type => json_encode($doc_data), $type_id => implode(",", $ids)]);
            } else {
                $doc_data[] = array("id" => $request->field_id, 'date' => $date);
                $data['bookmark'] = Bookmark::insert(['staff_id' => $request->user_id, $type_id => $request->field_id, $type => json_encode($doc_data)]);
            }
            if ($flag == 0)
                $status = false;
            else
                $status = true;
            if ($data['bookmark'] == 1)
                return response()->json(['status' => 200, 'data' => $status], '200');
            else
                return response()->json(['status' => 201, 'data' => $flag], '201');
        }
    }

    public function getBookmark_old(Request $request)
    {
        //user_id, type('document','topic')
        $bookmark = Bookmark::where('staff_id', $request->user_id)->first();
        //$data['document'] = [];
        $data = [];
        if (!empty($bookmark)) {
            //$document = json_decode($bookmark->document, true);
            $topic = json_decode($bookmark->topic, true);
            /* if (!empty($document)) {
                 foreach ($document as $item) {
                     $doc_array[] = $item['id'];
                     $doc_arraydate[$item['id']] = $item['date'];
                 }
                 $doc_placeholders = implode(',', array_fill(0, count($doc_array), '?'));
                 $doc_type = Document::whereIn('documents.id', $doc_array)
                     ->orderByRaw("field(documents.id,{$doc_placeholders})", $doc_array)
                     ->get(['documents.id', 'title', 'description', 'preview_image', 'doc_type']);
                 foreach ($doc_type as $value) {
                     if (array_key_exists($value->id, $doc_arraydate))
                         $value->datetime = $doc_arraydate[$value->id];
                     else
                         $value->datetime = null;
                 }
                 $data['document'] = $doc_type;
             }*/
            if (!empty($topic)) {
                foreach ($topic as $item) {
                    $topic_array[] = $item['id'];
                    $topic_arraydate[$item['id']] = $item['date'];
                }
                $topic_placeholders = implode(',', array_fill(0, count($topic_array), '?'));

                $topic_type = Topic::whereIn('id', explode(',', $bookmark->topic_id))
                    ->orderByRaw("field(id,{$topic_placeholders})", $topic_array)
                    ->get(['id', 'topic as title', 'image as preview_image']);
                foreach ($topic_type as $value) {
                    $value->preview_image = cloudUrl($value->preview_image);
                    if (array_key_exists($value->id, $topic_arraydate))
                        $value->datetime = $topic_arraydate[$value->id];
                    else
                        $value->datetime = null;
                }
                $data = $topic_type;
            }
        }
        return response()->json(['status' => 200, 'data' => $data], '200');
    }


    public function getBookmark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'type' => 'required',
            //'id' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //$type_id=strtolower($request->type).'_id';
            //$type=strtolower($request->type);
            $bookmark = Bookmark::where(['staff_id' => $request->user_id])->first();

            //$data['document'] = [];
            $data = [];
            if (!empty($bookmark)) {
                $course = json_decode($bookmark->course, true);
                $subject = json_decode($bookmark->subject, true);
                $chapter = json_decode($bookmark->chapter, true);
                $topic = json_decode($bookmark->topic, true);

                if (!empty($course)) {
                    foreach ($course as $item) {
                        $course_array[] = $item['id'];
                        $course_arraydate[$item['id']] = $item['date'];
                    }
                    $course_placeholders = implode(',', array_fill(0, count($course_array), '?'));

                    $course_type = Course::whereIn('id', explode(',', $bookmark->course_id))
                        ->orderByRaw("field(id,{$course_placeholders})", $course_array)
                        ->get(['id', 'course as title', 'image as preview_image', 'color']);
                    foreach ($course_type as $value) {
                        if (array_key_exists($value->id, $course_arraydate))
                            $datetime = $course_arraydate[$value->id];
                        else
                            $datetime = null;
                        $course_final[] = [
                            'id' => $value->id,
                            'title' => $value->title,
                            'parent_name' => $value->title,
                            'preview_image' => cloudUrl($value->preview_image),
                            'datatime' => $datetime,
                            'color' => $value->color,
                            'type' => 'course',
                        ];
                        /*   $value->preview_image = cloudUrl($value->preview_image);
                           if (array_key_exists($value->id, $course_arraydate))
                               $value->datetime = $course_arraydate[$value->id];
                           else
                               $value->datetime = null;*/
                    }
                    $data['course'] = $course_final;
                } else {
                    $data['course'] = [];
                }
                if (!empty($subject)) {
                    foreach ($subject as $item) {
                        $subject_array[] = $item['id'];
                        $subject_arraydate[$item['id']] = $item['date'];
                    }
                    $subject_placeholders = implode(',', array_fill(0, count($subject_array), '?'));

                    $subject_type = Subject::whereIn('id', explode(',', $bookmark->subject_id))
                        ->with('course')
                        ->orderByRaw("field(id,{$subject_placeholders})", $subject_array)
                        ->get();
                    foreach ($subject_type as $value) {
                        if (array_key_exists($value->id, $subject_arraydate))
                            $datetime = $subject_arraydate[$value->id];
                        else
                            $datetime = null;

                        $subject_final[] = [
                            'id' => $value->id,
                            'title' => $value->subject,
                            'parent_name' => $value->course->course,
                            'preview_image' => cloudUrl($value->image),
                            'color' => $value->course->color,
                            'datatime' => $datetime,
                            'type' => 'subject',
                        ];

                    }
                    $data['subject'] = $subject_final;
                } else {
                    $data['subject'] = [];
                }
                if (!empty($chapter)) {
                    foreach ($chapter as $item) {
                        $chapter_array[] = $item['id'];
                        $chapter_arraydate[$item['id']] = $item['date'];
                    }
                    $chapter_placeholders = implode(',', array_fill(0, count($chapter_array), '?'));

                    $chapter_type = Chapter::whereIn('id', explode(',', $bookmark->chapter_id))
                        ->with('subject.course')
                        ->orderByRaw("field(id,{$chapter_placeholders})", $chapter_array)
                        ->get();
                    foreach ($chapter_type as $value) {
                        if (array_key_exists($value->id, $chapter_arraydate))
                            $datetime = $chapter_arraydate[$value->id];
                        else
                            $datetime = null;

                        $chapter_final[] = [
                            'id' => $value->id,
                            'title' => $value->chapter,
                            'preview_image' => cloudUrl($value->image),
                            'color' => $value->subject->course->color,
                            'parent_name' => $value->subject->course->course,
                            'datatime' => $datetime,
                            'type' => 'chapter',
                        ];

                    }
                    $data['chapter'] = $chapter_final;
                } else {
                    $data['chapter'] = [];
                }
                if (!empty($topic)) {
                    foreach ($topic as $item) {
                        $topic_array[] = $item['id'];
                        $topic_arraydate[$item['id']] = $item['date'];
                    }
                    $topic_placeholders = implode(',', array_fill(0, count($topic_array), '?'));

                    $topic_type = Topic::whereIn('id', explode(',', $bookmark->topic_id))
                        ->with('chapter.subject.course')
                        ->orderByRaw("field(id,{$topic_placeholders})", $topic_array)
                        ->get();
                    foreach ($topic_type as $value) {
                        if (array_key_exists($value->id, $topic_arraydate))
                            $datetime = $topic_arraydate[$value->id];
                        else
                            $datetime = null;

                        $topic_final[] = [
                            'id' => $value->id,
                            'title' => $value->topic,
                            'preview_image' => cloudUrl($value->image),
                            'color' => $value->chapter->subject->course->color,
                            'parent_name' => $value->chapter->subject->course->course,
                            'datatime' => $datetime,
                            'type' => 'topic',
                        ];
                    }
                    $data['topic'] = $topic_final;
                } else {
                    $data['topic'] = [];
                }
            } else {
                $data['course'] = [];
                $data['subject'] = [];
                $data['chapter'] = [];
                $data['topic'] = [];
            }
            return response()->json(['status' => 200, 'data' =>
                array_merge($data['course'], $data['subject'], $data['chapter'], $data['topic'])
            ], '200');
        }
    }

    public function document_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:documents,id',
        ], [
            'user_id.required' => "User Id required.",
            'user_id.exists' => "User Id does not exists.",
            'document_id.required' => "Document Id required.",
            'document_id.exists' => "Document Id does not exists.",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => implode(',', $validator->errors()->all())], '200');
            //return response()->json(['status' => 201, 'data' => implode(',', implode(',',$validator->errors()->all()))], '200');
        } else {
            //document_id,user_id
            $document_id = $request->document_id;
            $user_id = ($request->user_id) ? $request->user_id : null;
            $data = null;
            //added two fields document_url and duration in sec's:By Sourabh.
            $document = Document::
            leftJoin('library as library_history', function ($join) use ($user_id) {
                $join->where('library_history.staff_id', $user_id);
                $join->whereRaw('FIND_IN_SET(documents.id,library_history.likes_id) > 0 ');
            })->
            where('documents.id', $request->document_id)
                ->where('documents.status', 1)
                ->first(['documents.id', 'documents.title', 'library_history.likes_id'
                    , 'documents.course_id', 'documents.subject_id'
                    , 'documents.chapter_id', 'documents.topic_id', 'documents.author_name', 'documents.preview_image'
                ]);


            //getting media of document id
            if (count($document) > 0) {
                $documentMedia = Document::where('id', $document->id)->with('media')->first();

                if (isset($documentMedia->media) && count($documentMedia->media) > 0) {

                    foreach ($documentMedia->media as $item) {
                        if ($item->doc_type == 'Audio') {
                            $audio = CreateTemporaryURL($item->doc_file);
                            $audioDuration = TimeToSec($item->duration);
                            $doc_type = 'Audio';
                        }
                        if ($item->doc_type == 'Video') {
                            $video_url = $this->youtubeDownload($item->doc_file);
                            $video = isset($video_url) ? $video_url : $item->doc_file;
                            $doc_type = 'Video';
                        }

                        if ($item->doc_type == 'Text') {
                            $doc_type = 'Text';
                            $text = ($item->doc_file);
                        }

                        if ($item->doc_type == 'Audio')
                            $audioTypeFirst[] = 'Audio';
                        elseif ($item->doc_type == 'Video')
                            $audioTypeFirst[] = 'Video';
                        else
                            $audioTypeFirst[] = 'Text';

                    }

                    if (!empty($document->id)) {

                        if (!empty($document->topic_id) && $document->topic_id!=0) {
                            $rel_value = 'topic';
                        } elseif (!empty($document->chapter_id) && $document->chapter_id!=0) {
                            $rel_value = 'chapter';
                        } elseif (!empty($document->subject_id) && $document->subject_id!=0) {
                            $rel_value = 'subject';
                        } else {
                            $rel_value = 'course';
                        }

                        $type_id = $rel_value . '_id';

                        $next_doc = Document::
                        leftJoin($rel_value . 's', function ($join) use ($type_id, $document, $user_id, $rel_value) {
                            $join->where($rel_value . 's.id', $document->$type_id);
                            $join->where($rel_value . 's.status', 1);

                        })
                            ->where('documents.status', 1)->where('documents.id', "!=", $document->id)
                            ->where('documents.' . $type_id, $document->$type_id)
                            ->get(['documents.id', 'documents.title'
                                , 'documents.course_id', 'documents.subject_id'
                                , 'documents.chapter_id', 'documents.topic_id', $rel_value . 's.' . $rel_value, 'documents.author_name', 'documents.preview_image'
                            ]);

                        //$audioType=[];

                        foreach ($next_doc as $item) {
                            $nextDocumentMedia = Document::where('id', $item->id)->with('media')->first();
  if(count($nextDocumentMedia->media)>0){
                            foreach ($nextDocumentMedia->media as $items) {
                                if ($items->doc_type == 'Audio')
                                    $audioType[] = 'Audio';
                                elseif ($items->doc_type == 'Video')
                                    $audioType[] = 'Video';
                                else
                                    $audioType[] = 'Text';
                            }

                            if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                                $type = 'AVT';
                            elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                                $type = 'AV';
                            } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                                $type = 'AT';
                            } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                                $type = 'VT';
                            } else {
                                $type = $audioType[0];
                            }

                            $finalNextDoc[] = ['id' => $item->id, 'title' => $item->title,
                                $rel_value => $item->$rel_value, 'type' => $type
                                , 'image' => cloudUrl($item->preview_image), 'author_name' => $item->author_name
                            ];

                            $audioType = [];
  }
                        }

                    }
                    if (isset($request->user_id))
                        $history = $this->add_library_function($request->user_id, $request->document_id, 'history');


                    $user_pur = UserPurchase::where(['user_id' => $request->user_id])
                        ->whereIn('item_id', [$document->course_id, $document->subject_id])
                        ->orWhere('item_type',2)
                        ->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                        ->count();
                    if ($user_pur > 0) {
                        $isUserBuy = true;
                    } else {
                        $isUserBuy = false;
                    }


                    if ((in_array('Audio', $audioTypeFirst) && in_array('Video', $audioTypeFirst) && in_array('Text', $audioTypeFirst)))
                        $doc_type = 'AVT';
                    elseif ((in_array('Audio', $audioTypeFirst) && in_array('Video', $audioTypeFirst))) {
                        $doc_type = 'AV';
                    } elseif ((in_array('Audio', $audioTypeFirst) && in_array('Text', $audioTypeFirst))) {
                        $doc_type = 'AT';
                    } elseif ((in_array('Video', $audioTypeFirst) && in_array('Text', $audioTypeFirst))) {
                        $doc_type = 'VT';
                    } else {
                        $doc_type = $audioTypeFirst[0];
                    }


                    //checking that document liked or not
                    $finalData = [
                        'id' => $document->id,
                        'title' => $document->title,
                        'audio_link' => isset($audio) ? $audio : ($audio = ''),
                        'audio_duration' => isset($audioDuration) ? $audioDuration : '',
                        'video_link' => isset($video) ? $video : ($video = ''),
                        'text_link' => isset($text) ? $text : ($text = ''),
                        'doc_type' => isset($doc_type) ? $doc_type : $doc_type,
                        'image' => cloudUrl($document->preview_image),
                        'author_name' => $document->author_name,
                        'is_liked' => (!empty($document->likes_id) ? true : false),
                        'is_UserBuy' => $isUserBuy,
                        'related_doc' => isset($finalNextDoc) ? $finalNextDoc : ($finalNextDoc = [])
                    ];
                    $audioTypeFirst = [];
                    $doc_type = '';
                    return response()->json(['status' => 200, 'data' => $finalData], '200');

                } else {
                    return response()->json(['status' => 201, 'data' => $finalData = []], '200');
                }
            } else {
                return response()->json(['status' => 201, 'data' => $finalData = []], '200');
            }

        }
    }

    public function youtubeDownload($url)
    {
        $yt = new YouTubeDownloader();
        $links = $yt->getDownloadLinks($url);
        return (isset($links['18']['url']) ? $links['18']['url'] : null);
        //return $links['18']['url'];
    }

    public function getRecentList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ], [
            'user_id.required' => "User Id required.",
            'user_id.exists' => "User Id does not exists.",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => implode(',', $validator->errors()->all())], '200');
            //return response()->json(['status' => 201, 'data' => implode(',', implode(',',$validator->errors()->all()))], '200');
        } else {
            $userCourses = User::where('users.id', $request->user_id)
                ->select([
                    'courses.id as course_id', 'chapters.id as chapter_id',
                    'subjects.id as subject_id', 'topics.id as topic_id'
                ])
                ->leftjoin('course_user', 'users.id', '=', 'course_user.user_id')
                ->leftjoin('courses', 'course_user.course_id', '=', 'courses.id')
                ->leftjoin('subjects', 'courses.id', '=', 'subjects.course_id')
                ->leftjoin('chapters', 'subjects.id', '=', 'chapters.subject_id')
                ->leftjoin('topics', 'chapters.id', '=', 'topics.chapter_id')
                ->where('courses.type', 'Free')
                /*     ->where('subjects.type', 'Free')
                     ->where('courses.status', 1)
                     ->where('subjects.status', 1)
                     ->where('chapters.status', 1)
                     ->where('topics.status', 1)*/
                ->orWhere('subjects.type', 'Free')
                ->where('courses.status', 1)
                ->orWhere('subjects.status', 1)
                ->orWhere('chapters.status', 1)
                ->orWhere('topics.status', 1)
                ->groupBy('course_id')
                ->get();

            if (!empty($userCourses)) {
                foreach ($userCourses as $value) {
                    $documents[] = Document::where(function ($query) use ($value) {
                        $query->orWhere('course_id', $value->course_id);
                        $query->orWhere('chapter_id', $value->chapter_id);
                        $query->orWhere('subject_id', $value->subject_id);
                        $query->orWhere('topic_id', $value->topic_id);
                    })->where('status', 1)->get(['id', 'title', 'description', 'preview_image', 'duration', 'doc_type'
                        , 'course_id', 'chapter_id', 'subject_id', 'topic_id']);
                }

                if (!empty($documents) && count($documents) > 0) {
                    $i = 0;
                    foreach ($documents as $itemValue) {
                        foreach ($itemValue as $item) {

                            if ($i < 25) {
                                $finalData[] = array(
                                    "document_id" => $item->id,
                                    "title" => $item->title,
                                    'description' => $item->description,
                                    "preview_image" => cloudUrl($item->preview_image),
                                    "duration" => $item->duration,
                                    "type" => $item->doc_type,
                                );
                            } else {
                                break;
                            }
                            $i++;
                        }
                    }
                } else {
                    $finalData = [];
                }


            } else {
                $finalData = [];
            }

            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }

    }


    /*
     * this api for user registration via mobile in temp table
     * */

    public function getFrontImages()
    {
        $data = DB::table('intro_images')->select('image')->get();
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    /*
    * this api add user with role
    * */

    public function userRegistration(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);
        if(strtolower($request->type)=='email'){
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric|unique:users,mobile|min:10',
            ]);   
        }

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $otp = $this->core->otpGenerate();
                if(strtolower($request->type)=='email'){
                    // $sendEmail = $this->core->sendEMAIL($request->email, $otp);
                }
                else{
                    $sendSms = $this->core->sendSMS($request->mobile, $otp);
                }
                if (isset($sendSms->errors)) {
                    return response()->json(['status' => 201, 'msg' => 'Something went Wrong! Please Try again.'], '201');
                } else {
                    $time = Carbon::now()->addMinute(2)->toDateTimeString();
                if(strtolower($request->type)=='email'){
                   $tempUser = Temp::create(['otp' => $otp, 'mobile' => $request->email, 'valid_til' => $time]);
                }
                else{
                   $tempUser = Temp::create(['otp' => $otp, 'mobile' => $request->mobile, 'valid_til' => $time]);
                }
                    // $tempUser = Temp::create(['otp' => $otp, 'mobile' => $request->mobile, 'valid_til' => $time]);
                if(strtolower($request->type)=='email'){
                    return response()->json(['status' => 200, 'data' => ['otp' => $tempUser->otp, 'email' => $tempUser->mobile]], '200');
                }
                else{
                    return response()->json(['status' => 200, 'data' => ['otp' => $tempUser->otp, 'mobile' => $tempUser->mobile]], '200');   
                }
            }

        }
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,mobile',
            'mobile' => (strtolower($request->type)=='mobile')?'required|unique:users,mobile|numeric':'unique:users,mobile|numeric',
            'email' => (strtolower($request->type)=='email')?'required|email|unique:users,email|':'',
            'password' => 'required',
            'name' => 'required',
            //'city' => 'required|alpha',
            //'age' => 'required|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {

            $user = User::create([
                'mobile' => isset($request->mobile)?$request->mobile:null,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'name' => $request->name,
                //'city' => $request->city,
                //'institute' => $request->institute,
                //'age' => $request->age,
                'ref_code' => $request->ref_code,
                'device_token' => $request->device_token,
                'self_ref_code' => $this->core->referalGenerate(),
                'total_ref_amt' => 0,
                'photo' => isset($request->photo) ? $request->photo : ('uploads/profile/profile.jpg'),
            ]);
            $user
                ->roles()
                ->attach(Role::where('name', 'User')->first());
            if ($user) {
                $user->photo = cloudUrl($user->photo);

                return response()->json(['status' => 200, 'data' => $user], '200');

            } else
                return response()->json(['status' => 201, 'msg' => 'Something Went Wrong!Please Try again.'], '201');
        }
    }

    public function getHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $history = $this->history('history', $request->user_id);

            if (count($history) > 0) {
                //arranging as per other doc
                foreach ($history as $docItem) {
                    $nextDocumentMedia = Document::where('id', $docItem->id)->with('media')->first();
                    foreach ($nextDocumentMedia->media as $items) {
                        if ($items->doc_type == 'Audio')
                            $audioType[] = 'Audio';
                        elseif ($items->doc_type == 'Video')
                            $audioType[] = 'Video';
                        else
                            $audioType[] = 'Text';
                    }

                    if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                        $type = 'AVT';
                    elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                        $type = 'AV';
                    } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                        $type = 'AT';
                    } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                        $type = 'VT';
                    } else {
                        $type = $audioType[0];
                    }

                    $doc[] = ['id' => $docItem->id,
                        'text' => $docItem->title, 'author_name' => $docItem->author_name,
                        'image' => cloudUrl($docItem->preview_image), 'doc_type' => $type
                    ];
                    $type = [];
                    $audioType = [];

                }
                return response()->json(['status' => 200, 'data' => $doc], '200');

            } else {
                return response()->json(['status' => 200, 'data' => $doc = []], '200');
            }


        }

    }

    public function getLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $history = $this->history('likes', $request->user_id);

            if (count($history) > 0) {
                //arranging as per other doc
                foreach ($history as $docItem) {
                    $nextDocumentMedia = Document::where('id', $docItem->id)->with('media')->first();
                    foreach ($nextDocumentMedia->media as $items) {
                        if ($items->doc_type == 'Audio')
                            $audioType[] = 'Audio';
                        elseif ($items->doc_type == 'Video')
                            $audioType[] = 'Video';
                        else
                            $audioType[] = 'Text';
                    }

                    if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                        $type = 'AVT';
                    elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                        $type = 'AV';
                    } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                        $type = 'AT';
                    } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                        $type = 'VT';
                    } else {
                        $type = $audioType[0];
                    }

                    $doc[] = ['id' => $docItem->id,
                        'text' => $docItem->title, 'author_name' => $docItem->author_name,
                        'image' => cloudUrl($docItem->preview_image), 'doc_type' => $type
                    ];
                    $type = [];
                    $audioType = [];

                }
                return response()->json(['status' => 200, 'data' => $doc], '200');

            } else {
                return response()->json(['status' => 200, 'data' => $doc = []], '200');
            }

        }

    }

    public function addLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
            'document_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $data = $this->add_library_function($request->user_id, $request->document_id, 'likes');
            if ($data['library'] == 1)
                return response()->json(['status' => 200, 'data' => $data['flag']], '200');
            else
                return response()->json(['status' => 201, 'data' => $data['flag']], '201');
        }
    }

    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            //'profile_pic' => 'max:2560',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $msg = $this->msg_array['UPDATE_0'];
            $data_array['name'] = $request->name;
            $data_array['email'] = $request->email;
            $data_array['institute'] = $request->institute;
            $data_array['city'] = isset($request->city) ? $request->city : '';
            $data_array['age'] = $request->age;
            $data_array['institute'] = $request->institute;

            if (isset($request->plan_id)) {
                $data_array['plan_id'] = $request->plan_id;
                $plan = Plan::where('id', $request->plan_id)->first();
                if (!empty($plan)) {
                    $data_array['amount'] = $plan->amount;
                    $data_array['from_date'] = date("Y-m-d");
                    if ($plan->field == "Month")
                        $data_array['to_date'] = date("Y-m-d", strtotime(" +" . $plan->duration . " months"));
                    else
                        $data_array['to_date'] = date("Y-m-d", strtotime(" +" . $plan->duration . " years"));
                }
            }
            if (isset($request->profile_pic)) {
                ini_set('upload_max_filesize', '1024M');
                ini_set('post_max_size', '1024M');
                ini_set('memory_limit', '512M');
                ini_set('max_input_time', 800);
                ini_set('max_execution_time', 800);
                $filename = 'uploads/profile/' . time() . '.' . $request->profile_pic->getClientOriginalExtension();

                $image = imageResizeAndSave($request->file('profile_pic'), 200, 200, $filename);

                $data_array['photo'] = $image;
            }
            //return response()->json(['status'=>201,'data'=>$data_array],'201');
            $user = User::where('id', $request->user_id)->first();
            $flag = $user->update($data_array);
            if ($flag) {
                $msg = $this->msg_array['UPDATE_1'];
                $user->photo = cloudUrl($user->photo);
                $user->email=($user->email==null)?'':$user->email;
                $user->name=($user->name==null)?'':$user->name;
                $user->age=($user->age==null)?'':$user->age;
                $user->institute=($user->institute==null)?'':$user->institute;
                return response()->json(['status' => 200, 'msg' => $msg, 'data' => $user], '200');
            }
            return response()->json(['status' => 201, 'msg' => $msg], '201');
        }
    }

    public function getCourseData(Request $request)
    {
        //courses
        $courses = Course::with('subject', 'documents')->where('courses.id', $request->course_id)
            ->orderBy('courses.id', 'asc')
            ->get()->toArray();
        $allCourses = $courses;
        if (count($allCourses) > 0) {
            foreach ($allCourses as $k => $item) {

                if (empty($item['subject']) && empty($item['documents'])) {
                    unset($allCourses[$k]);
                }
                if (empty($item['subject'])) {

                    unset($allCourses[$k]['subject']);
                }
                if (empty($item['documents'])) {
                    unset($allCourses[$k]['documents']);
                }
                if (!empty($item['subject'])) {
                    unset($allCourses[$k]['documents']);
                }
            }
            foreach ($allCourses as $course) {
                if (!empty($course['subject'])) {
                    foreach ($course['subject'] as $subject) {
                        $sub[] = ['id' => $subject['id'], 'subject' => $subject['subject'], 'image' => $subject['image'], 'color' => $course['color']];
                    }
                    $dataa = $sub;
                } else {
                    foreach ($course['documents'] as $document) {
                        $doc[] = ['document_id' => $document['id'], 'doc_file' => $document['doc_file'],
                            'title' => $document['title'], 'color' => $course['color'],
                            'author_name' => $document['author_name'], 'doc_type' => $document['doc_type'],
                            'preview_image' => ($document['preview_image']),
                        ];
                    }
                    $dataa = $doc;
                }
                $finalData['course'][] = [
                    'id' => $course['id'], 'course' => $course['course'], 'image' => $course['image'],
                    'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'],
                    (isset($course['subject']) ? 'subjects' : 'document') => $dataa
                ];
            }
        } else {
            $finalData['course'] = [];
        }

        return response()->json(['status' => 200, 'data' => $finalData], '200');
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $user = User::where('id', $request->user_id)->update(['device_token' => '']);
            return response()->json(['status' => 200, 'data' => true], '200');

        }
    }

    public function uniqueMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:users,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            return response()->json(['status' => 200, 'msg' => ''], '200');
        }
    }

    public function getTermAbout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $data = SiteInfo::where('type', $request->type)->first(['title', 'content_data']);

            return response()->json(['status' => 200, 'data' => $data], '200');
        }
    }

    public function getPlanWeb()
    {
        $data = Plan::where('status', 1)->get();
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function getPlan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $data = Plan::where('status', 1)->get();
            foreach ($data as $datum) {
                $datum->image = cloudUrl($datum->image);
                $plan_purchase=PlanPurchase::where('user_id',$request->user_id)->where(['item_type'=>2,'item_id'=>$datum->id])->first();
                $datum->plan_purchase=count($plan_purchase)?1:0;
            }
            //getting User Current Coupon
            $userPurchase=UserPurchase::where('user_id',$request->user_id)->where('item_type',2)->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))->join('plan_coupons','plan_coupons.user_purchase_id','=','user_purchases.id')->first();
            if(count($userPurchase)>0){
                $userData=1;
            }else{
                $userData=0;
            }
    $slider = DB::table('plan_slider_images')->select('image')->where('deleted_at',null)->get();
          foreach ($slider as $img) {
            $slider_img[] = ['image' => cloudUrl($img->image)];
        }
            return response()->json(['status' => 200, 'data' => $data,'plan_purchase'=>$userData,'slider'=>$slider_img], '200');
        }
    }

    public function frontHome()
    {
$data['slider'] = DB::table('home_images')->select('image')->where('delated_at',null)->get();
        //courses
        $courses = Course::with(['subject', 'documents' => function ($q) {
        }])->where('courses.show_home', 1)->where('courses.status', 1)
            ->orderBy('courses.id', 'asc')
            ->get()->toArray();

        //adding slider
        foreach ($data['slider'] as $img) {
            $slider_img[] = ['image' => cloudUrl($img->image)];
        }


        $finalData['slider'] = $slider_img;
        $allCourses = $courses;
        if (count($allCourses) > 0) {
            foreach ($allCourses as $k => $item) {

                if (empty($item['subject']) && empty($item['documents'])) {
                    unset($allCourses[$k]);
                }
                if (empty($item['subject'])) {

                    unset($allCourses[$k]['subject']);
                }
                if (empty($item['documents'])) {
                    unset($allCourses[$k]['documents']);
                }
                if (!empty($item['subject'])) {
                    unset($allCourses[$k]['documents']);
                }
            }
            foreach ($allCourses as $course) {
                if (!empty($course['subject'])) {
                    foreach ($course['subject'] as $subject) {
                        if (count(Chapter::where('subject_id', $subject['id'])->first()) > 0) {
                            $next = 'Chapter';
                        } elseif (Document::where('subject_id', $subject['id'])->count() > 0) {
                            $next = 'document';
                        } else {
                            $next = 'Nothing';
                        }
                        $sub[] = ['type' => 'subject', 'id' => $subject['id'], 'title' => $subject['subject'], 'image' => cloudUrl($subject['image']), 'color' => $course['color'], 'what_next' => $next];
                    }
                    $dataa = $sub;
                } else {
                    foreach ($course['documents'] as $document) {
                        //giving course_id as document id bcz here getting data as per group by title

                        $nextDocumentMedia = Document::where('id', $document['id'])->with('media')->first();
                        foreach ($nextDocumentMedia->media as $items) {
                            if ($items->doc_type == 'Audio')
                                $audioType[] = 'Audio';
                            elseif ($items->doc_type == 'Video')
                                $audioType[] = 'Video';
                            else
                                $audioType[] = 'Text';
                        }
                        if (isset($audioType) && count($audioType) > 0) {

                            if ((in_array('Audio', $audioType) && in_array('Video', $audioType) && in_array('Text', $audioType)))
                                $type = 'AVT';
                            elseif ((in_array('Audio', $audioType) && in_array('Video', $audioType))) {
                                $type = 'AV';
                            } elseif ((in_array('Audio', $audioType) && in_array('Text', $audioType))) {
                                $type = 'AT';
                            } elseif ((in_array('Video', $audioType) && in_array('Text', $audioType))) {
                                $type = 'VT';
                            } else {
                                $type = $audioType[0];
                            }
                        } else {
                            $type = 'Nothing';
                        }
                        $doc[] = ['type' => 'document', 'id' => $document['id'],
                            'title' => $document['title'], 'color' => $course['color'],
                            'author_name' => $document['author_name'],
                            'image' => cloudUrl($document['preview_image']), 'doc_type' => $type
                        ];
                        $type = [];
                        $audioType = [];
                    }
                    $dataa = $doc;
                }
                if (strtolower($course['type']) != 'free') {
                    if(isset($request->user_id) && $request->user_id!=0)
                    {
                       $user_pur = UserPurchase::where('user_id',$request->user_id)
                            ->where(function ($q) use($course){
                                $q->whereIn('item_id', [ $course['id']]);
                                $q->orWhere('item_type',2);
                            })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                            ->count();
                        }
                        else
                        {
                            $user_pur=0;
                        }
                        if ($user_pur > 0) {
                            $isUserBuy = true;
                        } else {
                            $isUserBuy = false;
                        }
                        $finalData['course'][] = [
                            'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                            'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'], 'isUserBuy' => $isUserBuy,
                            'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                            (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                        ];
                    } else {

                $finalData['course'][] = [
                    'id' => $course['id'], 'course' => $course['course'], 'image' => cloudUrl($course['image']),
                    'type' => $course['type'], 'amount' => $course['amount'], 'color' => $course['color'],
                    'what_next' => (isset($course['subject']) ? 'subject' : 'document'),
                    (isset($course['subject']) ? 'innerItems' : 'innerItems') => $dataa
                ];
            }

                $dataa = [];
                $sub = [];
                $doc = [];

            }
        } else {
            $finalData['course'] = [];
        }

        return response()->json(['status' => 200, 'data' => $finalData], '200');
    }
    /*rzp_test_NKrj0ZFPtohyT4*/
    public function checkDocParentType(Request $request){

    $doc=Document::where('id',$request->document_id)->with('course','subject')->first();
        if(count($doc->course)>0){
        return response()->json(['status' => 200, 'data' => $doc->course->type], '200');
    }else{
            return response()->json(['status' => 200, 'data' => $doc->subject->type], '200');
        }
}

    public function document_detail_web(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:documents,id',
        ], [
            'user_id.required' => "User Id required.",
            'user_id.exists' => "User Id does not exists.",
            'document_id.required' => "Document Id required.",
            'document_id.exists' => "Document Id does not exists.",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'data' => implode(',', $validator->errors()->all())], '200');
            //return response()->json(['status' => 201, 'data' => implode(',', implode(',',$validator->errors()->all()))], '200');
        } else {
            //document_id,user_id
            $document_id = $request->document_id;
            $user_id = ($request->user_id) ? $request->user_id : null;
            $data = null;
            //added two fields document_url and duration in sec's:By Sourabh.
            $document = Document::
            leftJoin('library as library_history', function ($join) use ($user_id) {
                $join->where('library_history.staff_id', $user_id);
                $join->whereRaw('FIND_IN_SET(documents.id,library_history.likes_id) > 0 ');
            })->
            where('documents.id', $request->document_id)
                ->where('documents.status', 1)
                ->first(['documents.id', 'documents.title', 'library_history.likes_id'
                    , 'documents.course_id', 'documents.subject_id'
                    , 'documents.chapter_id', 'documents.topic_id', 'documents.author_name', 'documents.preview_image'
                ]);

            //getting media of document id
            if (count($document) > 0) {
                $documentMedia = Document::where('id', $document->id)->with('media')->first();

                if (isset($documentMedia->media) && count($documentMedia->media) > 0) {

                    foreach ($documentMedia->media as $item) {
                        if ($item->doc_type == 'Audio') {
                            $audio = CreateTemporaryURL($item->doc_file);
                            $audioDuration = TimeToSec($item->duration);
                            $doc_type = 'Audio';
                        }
                        if ($item->doc_type == 'Video') {
                            $video_url = $this->youtubeDownload($item->doc_file);
                            $video = isset($video_url) ? $video_url : $item->doc_file;
                            $doc_type = 'Video';
                        }

                        if ($item->doc_type == 'Text') {
                            $doc_type = 'Text';
                            $text = ($item->doc_file);
                        }

                        if ($item->doc_type == 'Audio')
                            $audioTypeFirst[] = 'Audio';
                        elseif ($item->doc_type == 'Video')
                            $audioTypeFirst[] = 'Video';
                        else
                            $audioTypeFirst[] = 'Text';

                    }

                    if (isset($request->user_id))
                        $history = $this->add_library_function($request->user_id, $request->document_id, 'history');

                    $user_pur = UserPurchase::where('user_id',$request->user_id)
                        ->where(function ($q) use($document){
                            $q->whereIn('item_id', [$document->course_id, $document->subject_id]);
                            $q->orWhere('item_type',2);
                        })->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))
                        ->count();
                    if ($user_pur > 0) {
                        $isUserBuy = true;
                    } else {
                        $isUserBuy = false;
                    }

                    //checking that document liked or not
                    $finalData = [
                        'id' => $document->id,
                        'title' => $document->title,
                        'audio_link' => isset($audio) ? $audio : ($audio = ''),
                        'audio_duration' => isset($audioDuration) ? $audioDuration : '',
                        'video_link' => isset($video) ? $video : ($video = ''),
                        'text_link' => isset($text) ? $text : ($text = ''),
                        'image' => cloudUrl($document->preview_image),
                        'author_name' => $document->author_name,
                        'is_liked' => (!empty($document->likes_id) ? true : false),
                        'is_UserBuy' => $isUserBuy,
                        'related_doc' => isset($finalNextDoc) ? $finalNextDoc : ($finalNextDoc = [])
                    ];
                    $audioTypeFirst = [];
                    $doc_type = '';
                    return response()->json(['status' => 200, 'data' => $finalData], '200');

                } else {
                    return response()->json(['status' => 201, 'data' => $finalData = []], '200');
                }
            } else {
                return response()->json(['status' => 201, 'data' => $finalData = []], '200');
            }

        }
    }

    public function getDocUrl($id,$type)
    {
        $documentMedia = Media::where('document_id', $id)->where('doc_type',$type)->first();
        if($documentMedia->doc_type=='Text'){
            return $documentMedia->content;
        }else{
            return cloudUrl($documentMedia->content);
        }

    }

    public function getDocPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
          $doc = Document::where('id', $request->id)->with('course', 'subject')->first();

          if(count($doc->course)>0)
          {
          $doc->course->image=cloudUrl($doc->course->image);
          $doc->course->banner_img=cloudUrl($doc->course->banner_img);
          $doc->course->icon_img=cloudUrl($doc->course->icon_img);
          $data['course']=$doc->course;
          }
          else
          {
              $data['course']=[];
          }
           if($doc->subject!=null)
          {
          $doc->subject->image=cloudUrl($doc->subject->image);
          $doc->subject->banner_img=cloudUrl($doc->subject->banner_img);
          $doc->subject->icon_img=cloudUrl($doc->subject->icon_img);
          $data['subject']=$doc->subject;
          }
          else
          {
              $data['subject']=[];
          }

            return response()->json(['status' => 200, 'data' => $data], '200');
        }
    }

    public function getRedeemToken(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'redeem_token' => 'required|exists:plan_coupons,ref_code',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //getredeem
            $coupon=PlanCoupon::where(['ref_code'=>$request->redeem_token,'status'=>0])->with('planPurchase')->first();
            if(count($coupon)>0){
            if(password_verify(($coupon->salt.$request->redeem_token.env('APP_KEY')),$coupon->hash)){
                //checking if user activated any plan
                $userPurchase=UserPurchase::where('user_id',$request->user_id)->where('item_type',2)->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))->join('plan_coupons','plan_coupons.user_purchase_id','=','user_purchases.id')->first();
                if(count($userPurchase)==0) {
                    $plan = Plan::find($coupon->planPurchase->getOriginal('item_id'));
                    if ($plan->field == 'Month') {
                        $end_date = (Carbon::now()->addDays(($plan->duration * 30)))->format('Y-m-d h:i:s');
                    } else {
                        $end_date = (Carbon::now()->addDays(($plan->duration * 365)))->format('Y-m-d h:i:s');
                    }

                    $userPurchase = UserPurchase::create([
                        'purchase_id' => $coupon->planPurchase->purchase_id,
                        'paid_amount' => $coupon->planPurchase->paid_amount,
                        'user_id' => $request->user_id,
                        'end_date' => $end_date,
                        'item_id' => $coupon->planPurchase->getOriginal('item_id'),
                        'item_type' => $coupon->planPurchase->item_type,
                        'discount_amount' => $coupon->planPurchase->discount_amount,
                        'item_price' => $coupon->planPurchase->item_price,
                        'payment_data' => $coupon->planPurchase->payment_data,
                    ]);
                    $coupon->update(['user_purchase_id' => $userPurchase->id, 'status' => 1, 'activated_on' => Carbon::now()->format('Y-m-d h:i:s')]);

                    return response()->json(['status' => 200, 'msg' => 'Coupon Successfully Activated'], '200');
                }else {
                    return response()->json(['status' => 201, 'msg' => 'Already Subscribed To a Plan.'], '201');
                }
            }else {
                return response()->json(['status' => 201, 'msg' => 'Please Enter Valid Redeem Code'], '201');
            }
            }else {
                return response()->json(['status' => 201, 'msg' => 'Please Enter Valid Redeem Code'], '201');
            }

        }
    }

    public function getPlanCouponList(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        $finalData=[];

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $coupons=PlanPurchase::where('user_id',$request->user_id)->
            with('coupons')->first();
            if(count($coupons)>0) {
                foreach ($coupons->coupons as $coupon) {
                    $finalData[] = ['ref_code' => $coupon->ref_code, 'active_date' => (!empty($coupon->activated_on)?Carbon::parse($coupon->activated_on)->format('d-M-Y'):''), 'status' => $coupon->status];
                }
            }else{
                $finalData=[];
            }

            //getting User Current Coupon
            $userPurchase=UserPurchase::where('user_id',$request->user_id)->where('item_type',2)->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))->join('plan_coupons','plan_coupons.user_purchase_id','=','user_purchases.id')->first();
           if(count($userPurchase)>0){
               $userData=[
                   'ref_code'=>$userPurchase->ref_code
               ];
           }else{
               $userData=[];
           }

            return response()->json(['status' => 200, 'data' => ($finalData)?$finalData:($finalData=[]),'user_data' => $userData], '200');
        }
    }

    public function getPlanHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            $plans=UserPurchase::where('user_id',$request->user_id)
                ->select(['user_purchases.id',DB::raw("DATE_FORMAT(user_purchases.end_date, '%d %b %Y') as end_date"),'plans.name as name','plans.image as image'])
                ->join('plans', function($join)
                {
                    $join->on('user_purchases.item_id', '=', 'plans.id')
                        ->where('user_purchases.item_type', '=',2);
                })->get();

            $subjects=UserPurchase::where('user_id',$request->user_id)
                ->select(['user_purchases.id',DB::raw("DATE_FORMAT(user_purchases.end_date, '%d %b %Y') as end_date"),'subjects.subject as name','subjects.image as image'])
                ->join('subjects', function($join)
                {
                    $join->on('user_purchases.item_id', '=', 'subjects.id')
                        ->where('user_purchases.item_type', '=',1);
                })->get();

            $courses =UserPurchase::where('user_id',$request->user_id)
                ->select(['user_purchases.id',DB::raw("DATE_FORMAT(user_purchases.end_date, '%d %b %Y') as end_date"),'courses.course as name','courses.image as image' ])
                ->join('courses', function($join)
                {
                    $join->on('user_purchases.item_id', '=', 'courses.id')
                        ->where('user_purchases.item_type', '=',0);
                })->get();


            //dd(array_merge($plans->toArray(),$subjects->toArray(),$courses->toArray()));
            $final=array_merge($plans->toArray(),$subjects->toArray(),$courses->toArray());
            $finalData=$final;
            foreach ($finalData as $k=>$item) {
                $finalData[$k]['image']=cloudUrl($item['image']);


            }
            return response()->json(['status' => 200, 'data' =>$finalData], '200');
        }
    }

    public function sendAppLink(Request $request){
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {

            $appLink='https://play.google.com/store/apps/details?id=com.shana';
            return response()->json(['status' => 201, 'msg' => 'Message Successfully Sent To Mobile :'.$request->mobile], '200');
        }
    }

    public function planPayment(Request $request){
      $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'plan_id' => 'required',
            'razorpay_payment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //get API Configuration
            $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
            //Fetch payment information by razorpay_payment_id
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (!empty($request->razorpay_payment_id)) {
                try {
                    $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));

                } catch (\Exception $e) {
                    return response()->json(['status' => 201, 'msg' => $e->getMessage()], '201');

                }
                //dd($response, $input, json_decode($input['order_detaill'], true));
                //entry for plan payment

                if (isset($request->type)&& strtolower($request->type)=='plan') {
                    $data = Plan::find($request->plan_id);
                    if (isset($data->num_users)) {
                        //add into plan purchase
                        $planPurchase = PlanPurchase::create(
                            [
                                'purchase_id' => $response->id,
                                'paid_amount' => ($response->amount / 100),
                                'user_id' => $request->user_id,
                                'item_id' => $data->id,
                                'item_type' => 2,
                                'discount_amount' => 0,
                                'item_price' => $data->amount,
                                'payment_data' => json_encode($response->toArray()),
                            ]
                        );

                        for ($i = 0; $i < $data->num_users; $i++) {
                            $salt = $this->core->planReferalGenerate();
                            $refCode = 'Shana' . $this->core->planReferalGenerate();
                            $planCoupons = PlanCoupon::create([
                                'plan_purchase' => $planPurchase->id,
                                'ref_code' => $refCode,
                                'salt' => $salt,
                                'hash' => password_hash($salt . $refCode . env('APP_KEY'), PASSWORD_DEFAULT),
                            ]);
                        }

                        //plan auto activate

                         $coupon=PlanCoupon::where('plan_purchase',$planPurchase->id)->with('planPurchase')->first();
                         //checking if user activated any plan
                $userPurchase=UserPurchase::where('user_id',$request->user_id)->where('item_type',2)->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))->join('plan_coupons','plan_coupons.user_purchase_id','=','user_purchases.id')->first();
                if(count($userPurchase)==0) {
                    $plan = Plan::find($coupon->planPurchase->getOriginal('item_id'));
                    if ($plan->field == 'Month') {
                        $end_date = (Carbon::now()->addDays(($plan->duration * 30)))->format('Y-m-d h:i:s');
                    } else {
                        $end_date = (Carbon::now()->addDays(($plan->duration * 365)))->format('Y-m-d h:i:s');
                    }

                    $userPurchase = UserPurchase::create([
                        'purchase_id' => $coupon->planPurchase->purchase_id,
                        'paid_amount' => $coupon->planPurchase->paid_amount,
                        'user_id' => $request->user_id,
                        'end_date' => $end_date,
                        'item_id' => $coupon->planPurchase->getOriginal('item_id'),
                        'item_type' => $coupon->planPurchase->item_type,
                        'discount_amount' => $coupon->planPurchase->discount_amount,
                        'item_price' => $coupon->planPurchase->item_price,
                        'payment_data' => $coupon->planPurchase->payment_data,
                    ]);
                    $coupon->update(['user_purchase_id' => $userPurchase->id, 'status' => 1, 'activated_on' => Carbon::now()->format('Y-m-d h:i:s')]);

                    //return response()->json(['status' => 200, 'msg' => 'Coupon Successfully Activated'], '200');
                    return response()->json(['status' => 201, 'msg' => 'Success! Payment Completed and Coupon Activated Successfully.'], '200');
                }else {
                   // return response()->json(['status' => 201, 'msg' => 'Already Subscribed To a Plan.'], '201');
                    return response()->json(['status' => 201, 'msg' => 'Success! Payment Completed and You Already Subscribed To a Plan.'], '200');
                }


                    }
                    return response()->json(['status' => 201, 'msg' => 'Payment Successfully Completed.'], '200');
                }else{
                    return response()->json(['status' => 201, 'msg' => 'Invalid Request.'], '201');
                }

            }else{
                return response()->json(['status' => 201, 'msg' => 'Invalid Request.'], '201');
            }
        }
    }
    public function getBooks(){
          
            $data=Book::where('status',1)->get(['id','title','doc_type','description','orderNo','preview','file_detail']);
            $book=[];
            foreach($data as $k=>$item){
                $book[$k]=$item;
                $book[$k]['preview']=cloudUrl($item->preview);
                if(isset($item->doc_type) && $item->doc_type!='text')
                {
                    $book[$k]['file_detail']=cloudUrl($item->file_detail);
                }
            }
            if(count($book)>0){
                return response()->json(['status' => 200, 'data' => $book], '200');
            }
            else{
                  return response()->json(['status' => 201, 'msg' => 'Np Books Found'], '201');
            }
        
    }
    public function courseSubjectPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'id' => 'required',
            'razorpay_payment_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
        } else {
            //get API Configuration
            // $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
            // //Fetch payment information by razorpay_payment_id
            // $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (!empty($request->razorpay_payment_id)) {
                // try {
                //         $response = $api->payment->fetch($request->razorpay_payment_id)->capture(array('amount' => $payment['amount']));
                //     } catch (\Exception $e) {
                //         return response()->json(['status' => 201, 'msg' => $e->getMessage()], '201');
                //     }
                //dd($response, $input, json_decode($input['order_detaill'], true));
                //entry for plan payment
                
                    if (isset($request->type)&& strtolower($request->type)=='course') {
                        $data=Course::where('id',$request->id)->first()->toArray();
                    }
                    else{
                        $data=Subject::where('id',$request->id)->first()->toArray();
                    }
                    if (isset($data) && count($data)>0) {
                        if($data['field']=='Month'){
                            $end_date=(Carbon::now()->addDays(($data['duration']*30)))->format('Y-m-d h:i:s');
                        }else{
                            $end_date=(Carbon::now()->addDays(($data['duration']*365)))->format('Y-m-d h:i:s');
                        }
                        // $response=(object)['id'=>1,'amount'=>100000,'sdsasasad','sdsadasas'];
                        
                        //add into plan purchase
                            UserPurchase::create([
                            'purchase_id'=>$response->id,
                            'paid_amount'=>($response->amount/100),
                            'user_id'=>$request->user_id,
                            'end_date'=>$end_date,
                            'item_id'=>$data['id'],
                            'item_type'=>(isset($data['course'])?0:1),
                            'discount_amount'=>isset($data['discount'])?$data['discount']:0,
                            'item_price'=>isset($data['amount2'])?$data['amount2']:$data['amount'],
                            'payment_data'=>json_encode($response->toArray()),
                            ]);
                            return response()->json(['status' => 200, 'msg' => 'Payment Successfully Completed.'], '200');
                    }
                    else{
                        return response()->json(['status' => 201, 'msg' => 'Invalid Request.'], '201');
                    }
                } 
        }
    }
    public function socialLogin(Request $request){
      
        $user=User::where('email',$request->email)->first(['id']);
        if ($user) {
            SocialLogin::insert(['email'=>$request->email,'name'=>$request->email,'profile'=>isset($request->photo) ? $request->photo : ('uploads/profile/profile.jpg'),'social_id'=>$request->social_id,'user_id'=>$user->id]);
                Auth::loginUsingId($user->id);
        } else {
            $social=SocialLogin::where('email',$request->email)->first();
            if($social){
                Auth::loginUsingId($social->user_id);
           
            }
            else{
               $user_id= User::insertGetId([ 'mobile' => isset($request->mobile)?$request->mobile:null,
                'email' => $request->email,
                'password' => bcrypt('password'),
                'name' => $request->name,
                //'city' => $request->city,
                //'institute' => $request->institute,
                //'age' => $request->age,
                'ref_code' => $request->ref_code,
                'device_token' => $request->device_token,
                'self_ref_code' => $this->core->referalGenerate(),
                'total_ref_amt' => 0,
                'photo' => isset($request->photo) ? $request->photo : ('uploads/profile/profile.jpg'),]);
                $social=SocialLogin::insert(['email'=>$request->email,'name'=>$request->email,'profile'=>isset($request->photo) ? $request->photo : ('uploads/profile/profile.jpg'),'social_id'=>$request->social_id,'user_id'=>$user_id]);
                if($social){
                     Auth::loginUsingId($user_id);
                }
            }
            return response()->json(['status' => 200, 'msg' =>'User Login Successfully','data'=>Auth::user()], '200');  
        }  
    }
    public function send(){
        $data=$this->core->sendEMAIL('ravindra@sharabhtechnologies.com','12345');
        return $data;
    }
}
