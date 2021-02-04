<?php

namespace App\Http\Controllers;

use App\CourseUser;
use App\Role;
use App\Temp;
use App\User;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use App\Staff, App\Topic, App\Document, App\ForgetPassword, App\Course, App\Chapter, App\Subject, App\Library, App\Bookmark, App\Plan;
use Hash, DB;

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
        $user = User::where('mobile', $request->mobile)/*->where('staffs.status', 1)
            ->leftJoin('courses', 'staffs.course_id', '=', 'courses.id')*/
        ->first();
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                unset($user->password);
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
            'mobile' => 'required|exists:temp,mobile|numeric',
            'otp' => 'required|exists:temp,otp',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' =>implode(',',$validator->errors()->all())], '201');
        } else {
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

    public function update_user(Request $request)
    {
        $msg = $this->msg_array['UPDATE_0'];
        $data_array['name'] = $request->name;
        $data_array['course_id'] = $request->course_id;
        $data_array['email'] = $request->email;
        $data_array['school'] = $request->school;
        $data_array['type'] = $request->type;
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
            $filename = fileUpload($request->profile_pic, 'uploads/profile', 512, 512);
            $data_array['profile_pic'] = $filename;
        }
        //return response()->json(['status'=>201,'data'=>$data_array],'201'); 
        $flag = Staff::where('id', $request->id)->update($data_array);
        if ($flag) {
            $msg = $this->msg_array['UPDATE_1'];
            $data = Staff::where('staffs.id', $request->id)
                ->leftJoin('courses', 'staffs.course_id', '=', 'courses.id')
                ->first(['staffs.*', 'courses.course']);
            return response()->json(['status' => 200, 'msg' => $msg, 'data' => $data], '200');
        }
        return response()->json(['status' => 201, 'msg' => $msg], '201');
    }

    /*public function update_user(Request $request){
        $msg=$this->msg_array['UPDATE_0'];
        $data_array['name']=$request->name;
        $data_array['course_id']=$request->course_id;
        $data_array['email']=$request->email;
        $data_array['school']=$request->school;
        $data_array['type']=$request->type;
        if(isset($request->profile_pic)){
            $filename=fileUpload($request->profile_pic,'uploads/profile',512,512);
            $data_array['profile_pic']=$filename;
        }
        $flag=Staff::where('id',$request->id)->update($data_array);
        if($flag){
            $msg=$this->msg_array['UPDATE_1'];
            $data=Staff::where('staffs.id',$request->id)
                        ->leftJoin('courses', 'staffs.course_id', '=', 'courses.id')
                        ->first(['staffs.*','courses.course']);
            return response()->json(['status'=>200,'msg'=>$msg,'data'=>$data],'200');
        }
        return response()->json(['status'=>201,'msg'=>$msg],'201'); 
    }*/
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
        $staff = Staff::where('mobile', $request->mobile)->where('status', 1)->first();
        if ($staff) {
            $user = $this->core->forgetPassword($request->mobile);
            if ($user) {
                return response()->json(['status' => 200, 'data' => $user, 'msg' => $this->msg_array['OTP_SENT']], '200');
            }
        }
        return response()->json(['status' => 201, 'msg' => $this->msg_array['INVALID_MOBILE']], '201');
    }

    public function forgetPasswordUpdate(Request $request)
    {
        if (ForgetPassword::where('mobile', $request->mobile)->where('otp', $request->otp)->first()) {
            ForgetPassword::where('mobile', $request->mobile)->delete();
            if (Staff::where('mobile', $request->mobile)->update(['password' => Hash::make($request->password)]))
                return response()->json(['status' => 200, 'msg' => $this->msg_array['PASSWORD_1']], '200');
        }
        return response()->json(['status' => 201, 'msg' => $this->msg_array['OTP_UNMATCH']], '201');
    }

    public function course()
    {

        $data = Course::where("status", 1)->with('subject')->get(['id', 'course', 'image']);
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function chapter(Request $request)
    {
        //subject_id
        $data = Chapter::where('status', 1)->with('topic')->where('subject_id', $request->subject_id)->get(['id', 'chapter', 'image']);
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {
            $whereId=strtolower($request->type).'_id';
            //topic_id, user_id
            $user_id = $request->user_id;
            $data = DB::table('documents')
                ->select(['documents.id as document_id',  'documents.title', 'documents.doc_type', 'documents.preview_image', DB::raw("count(library_history.staff_id) as seen"), DB::raw("count(bookmarks.staff_id) as bookmark")
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
                ->where('documents.'.$whereId, $request->id)
                ->groupBy('documents.id')
                ->get();
            return response()->json(['status' => 200, 'data' => $data], '200');
        }
    }

    public function home()
    {
        $document = Document::where('status', 1)->with('topic')->get();
        $data = ['Student' => array(), 'Teacher' => array(), 'Parents' => array(), 'Competitor' => array()];
        if (!empty($document)) {
            foreach ($document as $value) {
                $user_type = explode(",", $value->user_type);
                foreach ($user_type as $val) {
                    if (count($data[$val]) <= 25) {
                        $data[$val][] = array(
                            "document_id" => $value->id,
                            "title" => $value->title,
                            'description' => $value->description,
                            "preview_image" => $value->preview_image,
                            "duration" => $value->duration,
                        );
                    } else {
                        break;
                    }
                }
            }
        }
        return response()->json(['status' => 200, 'data' => $data], '200');
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
                    ->get(['documents.id', 'title', 'author_name', 'description', 'preview_image', 'duration','doc_type']);
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

    public function addlibrary(Request $request)
    {
        $data = $this->add_library_function($request->user_id, $request->document_id, $request->type);
        if ($data['library'] == 1)
            return response()->json(['status' => 200, 'data' => $data['flag']], '200');
        else
            return response()->json(['status' => 201, 'data' => $data['flag']], '201');
    }

    public function search(Request $request)
    {
        /* $data["course"] = Course::where('course', 'like', '%'.$request->search . '%')->where("status", 1)->get(['id', 'course']);
         $data["subject"] = Subject::where('subject', 'like', $request->search . '%')->where("status", 1)->get(['id', 'subject', 'image']);
         $data["chapter"] = Chapter::where('chapter', 'like', $request->search . '%')->where("status", 1)->get(['id', 'chapter', 'image']);
         $data["topic"] = Topic::where('topic', 'like', $request->search . '%')->where("status", 1)->get(['id', 'topic', 'image']);
        */
        if(!empty($request->search)){
            $data= Course::where('course', 'like', '%'.$request->search . '%')->where("status", 1)->get(['id', 'course']);
        }else{
            $data=[];
        }

        return response()->json(['status' => 200, 'data' => $data], '200');
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

    public function bookmark(Request $request)
    {
        //$user_id,$field_id,$type('topic','document')
        $date = date('Y-m-d H:i:s');
        $flag = 1;
        $type = $request->type;
        $type_id = $request->type . '_id';
        $bookmark = Bookmark::where('staff_id', $request->user_id)->first();
        if (!empty($bookmark)) {
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
        if ($data['bookmark'] == 1)
            return response()->json(['status' => 200, 'data' => $flag], '200');
        else
            return response()->json(['status' => 201, 'data' => $flag], '201');
    }

    public function getBookmark(Request $request)
    {
        //user_id, type('document','topic')
        $bookmark = Bookmark::where('staff_id', $request->user_id)->first();
        $data['document'] = [];
        $data['topic'] = [];
        if (!empty($bookmark)) {
            $document = json_decode($bookmark->document, true);
            $topic = json_decode($bookmark->topic, true);
            if (!empty($document)) {
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
            }
            if (!empty($topic)) {
                foreach ($topic as $item) {
                    $topic_array[] = $item['id'];
                    $topic_arraydate[$item['id']] = $item['date'];
                }
                $topic_placeholders = implode(',', array_fill(0, count($topic_array), '?'));
                $topic_type = Topic::whereIn('id', $topic_array)
                    ->orderByRaw("field(id,{$topic_placeholders})", $topic_array)
                    ->get(['id', 'topic as title', 'image as preview_image']);
                foreach ($topic_type as $value) {
                    if (array_key_exists($value->id, $topic_arraydate))
                        $value->datetime = $topic_arraydate[$value->id];
                    else
                        $value->datetime = null;
                }
                $data['topic'] = $topic_type;
            }
        }
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function document_detail(Request $request)
    {
        //document_id,user_id
        $document_id = $request->document_id;
        $user_id = ($request->user_id) ? $request->user_id : null;
        $data = null;
        //added two fields document_url and duration in sec's:By Sourabh.
        $document = DB::table('documents')
            ->select([DB::raw('TIME_TO_SEC(documents.duration) as duration'), 'documents.content as document_text_content', 'documents.doc_file as document_url', 'documents.id as document_id', 'documents.topic_id', 'documents.title', 'documents.author_name', 'documents.description', 'documents.doc_type', 'documents.preview_image', DB::raw("count(library_mylist.staff_id) as mylist"), DB::raw("count(library_likes.staff_id) as likes")
            ])
            ->leftJoin('library as library_mylist', function ($join) use ($document_id, $user_id) {
                $join->where('library_mylist.staff_id', $user_id);
                $join->whereRaw("FIND_IN_SET($document_id,library_mylist.mylist_id) > 0");
            })
            ->leftJoin('library as library_likes', function ($join) use ($document_id, $user_id) {
                $join->where('library_likes.staff_id', $user_id);
                $join->whereRaw("FIND_IN_SET($document_id,library_likes.likes_id) > 0");
            })
            ->where('documents.id', $request->document_id)
            ->where('documents.status', 1)
            ->first();
        if (!empty($document->document_id)) {

            $document->next = Document::where('topic_id', $document->topic_id)->where('status', 1)->where('id', "!=", $document->document_id)->get(['id', 'preview_image', 'title', 'doc_type', DB::raw('TRIM(BOTH "00:" FROM duration)as duration'), 'description']);

            $data = $document;
        }
        if (isset($request->user_id))
            $item = $this->add_library_function($request->user_id, $request->document_id, 'history');
        return response()->json(['status' => 200, 'data' => $data], '200');
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
            return response()->json(['status' => 201, 'data' => implode(',',$validator->errors()->all())], '200');
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
                    }) ->where('status', 1)->get(['id', 'title', 'description', 'preview_image', 'duration', 'doc_type'
                        ,'course_id','chapter_id','subject_id','topic_id']);
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
                                    "preview_image" => $item->preview_image,
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

    public function getFrontImages()
    {
        $data = DB::table('intro_images')->select('image')->get();
        return response()->json(['status' => 200, 'data' => $data], '200');
    }


    /*
     * this api for user registration via mobile in temp table
     * */
    public function userRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|unique:users,mobile|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {

            $otp = $this->core->otpGenerate();
            $sendSms = $this->core->sendSMS($request->mobile, $otp);

            if (isset($sendSms->errors)) {
                return response()->json(['status' => 201, 'msg' => 'Something went Wrong! Please Try again.'], '201');
            } else {
                $time = Carbon::now()->addMinute(2)->toDateTimeString();
                $tempUser = Temp::create(['otp' => $otp, 'mobile' => $request->mobile, 'valid_til' => $time]);
                return response()->json(['status' => 200, 'data' => ['otp' => $tempUser->otp, 'mobile' => $tempUser->mobile]], '200');
            }


        }
    }

    /*
    * this api add user with role
    * */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|unique:users,mobile|numeric',
            //'email' => 'required|email',
            'password' => 'required',
            'name' => 'required|alpha',
            'city' => 'required|alpha',
            'age' => 'required|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {

            $user = User::create([
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'name' => $request->name,
                'city' => $request->city,
                'institute' => $request->institute,
                'age' => $request->age,
                'ref_code' => $request->ref_code,
                'self_ref_code' => $this->core->referalGenerate(),
                'total_ref_amt' => 0,
            ]);
            $user
                ->roles()
                ->attach(Role::where('name', 'User')->first());
            if ($user)
                return response()->json(['status' => 200, 'data' => $user], '200');
            else
                return response()->json(['status' => 201, 'msg' => 'Something Went Wrong!Please Try again.'], '201');
        }
    }


    public function addCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
            //'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {

            foreach (json_decode(stripslashes($request->course_id), true) as $item) {
                CourseUser::create(['course_id' => $item['id'], 'user_id' => $request->user_id]);
            }
            return response()->json(['status' => 200, 'msg' => 'Course Added Successfully.'], '200');
        }
    }

    public function getCourse()
    {
        $data = Course::where("status", 1)->get(['id', 'course', 'image']);
        //$data = Course::where("status", 1)->with('subject')->get(['id', 'course', 'image']);
        return response()->json(['status' => 200, 'data' => $data], '200');
    }

    public function getUserCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {
            $userCourses = User::with('courses')->where('id', $request->user_id)->first();

            foreach ($userCourses->courses as $item) {
                if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                    $next = 'Subject';
                } elseif (Document::where('course_id', $item->id)->count() > 0) {
                    $next = 'Docs';
                } else {
                    $next = 'Nothing';
                }
                $finalData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image, 'type' => $item->type, 'amount' => $item->amount, 'what_next' => $next];
            }
            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

    public function getCourseSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|numeric',
            'course_id' => 'required|exists:courses,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {
            $userCourseSubject = User::with(['courses' => function ($query) use ($request) {
                $query->where('course_id', $request->course_id);
            }, 'courses.subject' => function ($query) use ($request) {
                $query->where('course_id', $request->course_id);
                $query->select(["id", "course_id", "subject", "type", "amount", "duration", "field", "image",]);
            }])->where('id', $request->user_id)->first(['id']);
            //adding whatNext for app requirement
            if (!empty($userCourseSubject) && count($userCourseSubject->courses) > 0) {
                foreach ($userCourseSubject->courses as $itemValue) {
                    foreach ($itemValue->subject as $item) {
                        if (Chapter::where('subject_id', $item->id)->count() > 0) {
                            $next = 'Chapter';
                        } elseif (Document::where('subject_id', $item->id)->count() > 0) {
                            $next = 'Docs';
                        } else {
                            $next = 'Nothing';
                        }

                        $finalData[] = array(
                            "id" => $item->id,
                            "course_id" => $item->course_id,
                            "subject" => $item->subject,
                            'type' => $item->doc_type,
                            "amount" => $item->amount,
                            "duration" => $item->duration,
                            "field" => $item->field,
                            "image" => $item->image,
                            "whatNext" => $next,
                        );
                    }
                }
            } else {
                $finalData = [];
            }



            //$data = Subject::where("course_id", $request->course_id)->get();
            return response()->json(['status' => 200, 'data' =>
                $finalData], '200');
        }
    }

    public function getSubjectChapter(Request $request){
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {
            $subjectChapter = Chapter::where('subject_id', $request->subject_id)->where('status',1)->get();
            if(count($subjectChapter)>0) {
                foreach ($subjectChapter as $item) {
                    if (count(Topic::where('chapter_id', $item->id)->first()) > 0) {
                        $next = 'Topic';
                    } elseif (Document::where('chapter_id', $item->id)->count() > 0) {
                        $next = 'Docs';
                    } else {
                        $next = 'Nothing';
                    }
                    $finalData[] = ['id' => $item->id, 'chapter' => $item->chapter, 'image' => $item->image, 'what_next' => $next];
                }
            }else{
                $finalData=[];
            }

            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

    public function getChapterTopic(Request $request){
        $validator = Validator::make($request->all(), [
            'chapter_id' => 'required|exists:chapters,id|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',',$validator->errors()->all())], '201');
        } else {
            $chapterTopic = Topic::where('chapter_id', $request->chapter_id)->where('status',1)->get();
            if(count($chapterTopic)>0) {
                foreach ($chapterTopic as $item) {
                    if (Document::where('topic_id', $item->id)->count() > 0) {
                        $next = 'Docs';
                    } else {
                        $next = 'Nothing';
                    }
                    $finalData[] = ['id' => $item->id, 'topic' => $item->topic, 'image' => $item->image, 'what_next' => $next];
                }
            }else{
                $finalData=[];
            }

            return response()->json(['status' => 200, 'data' => $finalData], '200');
        }
    }

    public function getCoursesAsAlpha(){
        //getting all courses
        $courses=Course::where('status',1)->orderBy('id','asc')->get(['course','id','image']);
        //getting whatNext as per mobile team requirement
        foreach ($courses as $item) {
            if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                $next = 'Subject';
            } elseif (Document::where('course_id', $item->id)->count() > 0) {
                $next = 'Docs';
            } else {
                $next = 'Nothing';
            }
            $finalData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image,'what_next' => $next];
        }

        foreach ($finalData as $course) {
            //foreach alphabate
            foreach (range('a', 'z') as $letter) {
                if(startsWiths(strtolower($course['course']),$letter)){
                    if(!isset($alpha[strtoupper($letter)])) {
                        $alpha[strtoupper($letter)][] = $course;
                    }else{
                        array_push($alpha[strtoupper($letter)],$course);
                    }
                }else{
                    if(!isset($alpha[strtoupper($letter)])) {
                        $alpha[strtoupper($letter)]=[];
                    }

                }
            }
            //foreach 0-9
            foreach (range(1, 9) as $number) {
                if(startsWithsNum(strtolower($course['course']),$number)){
                    if(!isset($alpha[($number)])) {

                        $alpha[($number)][] = $course;
                    }else{
                        array_push($alpha[($number)],$course);
                    }
                }else{
                    if(!isset($alpha[($number)])) {
                        $alpha[($number)] =[] ;
                    }
                }
            }
        }
        return response()->json(['status' => 200, 'data' => $alpha], '200');

    }

    public function getAllAudio(){
        $audio=Document::where('doc_type','Audio')->where('status',1)->get(['title','preview_image','doc_file','doc_type',]);
        return response()->json(['status' => 200, 'data' => $audio], '200');
    }

    public function getRecentFive(){
        $recent=Course::where("status", 1)->orderBy('id', 'desc')->take(5)->get(['id', 'course', 'image']);
        foreach ($recent as $item) {
            if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                $next = 'Subject';
            } elseif (Document::where('course_id', $item->id)->count() > 0) {
                $next = 'Docs';
            } else {
                $next = 'Nothing';
            }
            $recentData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image,'what_next' => $next];
        }
        $data = Course::where("status", 1)->get(['id', 'course', 'image']);
        foreach ($data as $item) {
            if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                $next = 'Subject';
            } elseif (Document::where('course_id', $item->id)->count() > 0) {
                $next = 'Docs';
            } else {
                $next = 'Nothing';
            }
            $finalData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image,'what_next' => $next];
        }
        return response()->json(['status' => 200, 'data' =>['all'=>$finalData,'recent'=> $recentData]], '200');
    }

    public function getCoursesAsAlphaNew(){
        //getting all courses
        $courses=Course::where('status',1)->orderBy('id','asc')->get(['course','id','image']);
        //getting whatNext as per mobile team requirement
        foreach ($courses as $item) {
            if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                $next = 'Subject';
            } elseif (Document::where('course_id', $item->id)->count() > 0) {
                $next = 'Docs';
            } else {
                $next = 'Nothing';
            }
            $finalData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image,'what_next' => $next];
        }

        foreach ($finalData as $course) {
            //foreach alphabate
            foreach (range('a', 'z') as $letter) {
                if(startsWiths(strtolower($course['course']),$letter)){
                    if(!isset($alpha[strtoupper($letter)])) {
                        $alpha[strtoupper($letter)][] = $course;
                    }else{
                        array_push($alpha[strtoupper($letter)],$course);
                    }
                }else{
                    if(!isset($alpha[strtoupper($letter)])) {
                        $alpha[strtoupper($letter)]=[];
                    }

                }
            }
            //foreach 0-9
            foreach (range(1, 9) as $number) {
                if(startsWithsNum(strtolower($course['course']),$number)){
                    if(!isset($alpha[($number)])) {

                        $alpha[($number)][] = $course;
                    }else{
                        array_push($alpha[($number)],$course);
                    }
                }else{
                    if(!isset($alpha[($number)])) {
                        $alpha[($number)] =[] ;
                    }
                }
            }
        }
        //rendering array as per new requirement by mobile team
        foreach ($alpha as $key=>$item) {
            $newArray[]=['title'=>$key,'data'=>$item];
        }

        return response()->json(['status' => 200, 'data' => $newArray], '200');

    }

    public function getCoursesAsAlphaNew2(){
        //getting all courses
        $courses=Course::where('status',1)->orderBy('id','asc')->get(['course','id','image']);
        //getting whatNext as per mobile team requirement
        foreach ($courses as $item) {
            if (count(Subject::where('course_id', $item->id)->first()) > 0) {
                $next = 'Subject';
            } elseif (Document::where('course_id', $item->id)->count() > 0) {
                $next = 'Docs';
            } else {
                $next = 'Nothing';
            }
            $finalData[] = ['id' => $item->id, 'course' => $item->course, 'image' => $item->image,'what_next' => $next];
        }

        foreach ($finalData as $course) {
            //foreach 0-9
            foreach (range(1, 9) as $number) {
                if(startsWithsNum(strtolower($course['course']),$number)){
                    if(!isset($alpha[($number)])) {
                        $course['first']='T';
                        $course['start']=($number);
                        $alpha[($number)][] = $course;
                    }else{
                        $course['first']='F';
                        $course['start']=($number);
                        array_push($alpha[($number)],$course);
                    }
                }/*else{
                    if(!isset($alpha[($number)])) {
                        $alpha[($number)] =[] ;
                    }
                }*/
            }
            //foreach alphabate
            foreach (range('a', 'z') as $letter) {
                if(startsWiths(strtolower($course['course']),$letter)){
                    if(!isset($alpha[strtoupper($letter)])) {
                        $course['first']='T';
                        $course['start']=strtoupper($letter);
                        $alpha[strtoupper($letter)][] = $course;
                    }else{
                        $course['first']='F';
                        $course['start']=strtoupper($letter);
                        array_push($alpha[strtoupper($letter)],$course);
                    }
                }
//else{
//                    if(!isset($alpha[strtoupper($letter)])) {
//                        $alpha[strtoupper($letter)]=[];
//                    }
//
//                }
            }

        }
        krsort($alpha);

        //rendering array as per new requirement by mobile team
        foreach ($alpha as $key=>$item) {

            $newArray[]=$item;
        }


        return response()->json(['status' => 200, 'data' => $newArray], '200');

    }

}
