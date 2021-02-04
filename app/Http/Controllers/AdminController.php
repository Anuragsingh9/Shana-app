<?php

namespace App\Http\Controllers;

use App\Media;
use App\Notification;
use App\PlanCoupon;
use App\PlanPurchase;
use App\SiteInfo;
use App\User;
use App\Book;
use App\UserPurchase;
use Config;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Auth, DB, Image;
use Validator;
use App\Course, App\Subject, App\Chapter, App\Topic, App\Staff, App\Document, App\Plan,App\Bookmark,App\Library;
use Storage;
use YouTubeDownloader;
use App\Http\Controllers\PushNotificationController;

class AdminController extends Controller
{
    public function __construct(PushNotificationController $PushNotification)
    {
        $this->middleware(['auth', 'admin']);
        $this->PushNotificationController = $PushNotification;
        $this->core = app(\App\Http\Controllers\CoreController::class);
    }

    public function dashboard()
    {
        $data['course'] = DB::select(DB::raw("SELECT sum(case when status = 1 then 1 else 0 end) total_course, 
                                            sum(case when status = 1 AND type = 'Free' then 1 else 0 end) free_count ,
                                            sum(case when status = 1 AND type = 'Paid' then 1 else 0 end) paid_count 
                                    FROM `courses` WHERE 1")
        );
        $data['document'] = DB::select(DB::raw("SELECT sum(case when status = 1 AND doc_type = 'Video' then 1 else 0 end) total_video,
                                            sum(case when status = 1 AND doc_type = 'Audio' then 1 else 0 end) total_audio, 
                                            sum(case when status = 1 AND doc_type = 'Text' then 1 else 0 end) total_text
                                    FROM `medias` WHERE 1")
        );
        $data['user']['free']=User::count();
        $data['user']['paid']=UserPurchase::count();
        $data['total_amount']=UserPurchase::sum('paid_amount');
        $data['notification']=Notification::where('event','Marketing')->count();
        $data['running_plan']=UserPurchase::whereDate('end_date','>=', Carbon::now())->count();
        return view('dashboard', $data);
    }

    //Course Method
    public function course()
    {
        return view('course/course');
    }

    public function course_list()
    {

        $data['course'] = Course::/*where('status',1)->*/
        orderBy('id','DESC')->paginate(10);
        $data['courseDelete'] = Course::onlyTrashed()->where('status', 0)->get();
        return view('course/course_list', $data);
    }

    public function insert_course(Request $request)
    {


        if (isset($request->file)) {
            $filename = 'uploads/course/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['image' => $preview_image]);
        } else
            $request->merge(['image' => ('uploads/preview_image/dummy.png')]);

        //banner Image
        if (isset($request->banner_img_file)) {

            $filename = 'uploads/course/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        } else
            $request->merge(['banner_img' => ('uploads/preview_image/dummy.png')]);


        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/course/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        } else
            $request->merge(['icon_img' => ('uploads/preview_image/dummy.png')]);


        if ($request->color == 'FFFFFF') {
            $request->except(['color']);
            $data = $request->color;
            $data = '000000';
            $request->merge(['color' => $data]);
        }
    $request->merge(['data_type' => $request->data_type1]);


        if (Course::insert(
            $request->except(['_token', 'file', 'id', 'icon_img_file', 'banner_img_file','data_type1'])))
            return redirect('subject')->with('success', Config('constant.msg.record_insert_1'));
        else
            return redirect('subject')->with('error', Config('constant.msg.record_insert_0'));
    }

    public function edit_course($id)
    {
        $data['course'] = Course::where('id', $id)->first();
        return view('course/course', $data);
    }

    public function update_course($id, Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/course/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 500, 275, $filename);
            $request->merge(['image' => $filename]);
        }

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/course/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        }
        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/course/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        }
        if ($request->color == 'FFFFFF') {
            $request->except(['color']);
            $data = $request->color;
            $data = '000000';
            $request->merge(['color' => $data]);
        }
         $request->merge(['data_type' => $request->data_type1]);
        if (Course::where('id', $id)->update($request->except(['_token', 'file', 'id', 'icon_img_file', 'banner_img_file','data_type1'])))
            return redirect('subject-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('subject-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    public function statusChangeCourse($id)
    {
        $course = Course::where('id', $id)->first();
        if ($course->status == 0)
            Course::where('id', $id)->update(['status' => 1]);
        else
            Course::where('id', $id)->update(['status' => 0]);

        // return redirect('subject-list')->with('success', Config('constant.msg.status_changed'));
        return json_encode($course);
    }

    public function soft_delete($id)
    {
        $course = Course::find($id);
        $course->status = 0;
        $course->save();
        $course->delete();
    }

    public function soft_restore($id)
    {
        $course = Course::where('id', $id)->restore();
        return response()->json($course);
    }

    public function multiple_course_delete(Request $request)
    {
        if (!empty($request->id)) {
            $course = Course::whereIn('id', $request->id)->update(['status' => 0]);
            Course::whereIn('id', $request->id)->delete();
            return redirect('subject-list')->with('success', Config('constant.msg.record_delete_1'));
        } else
            return redirect('subject-list');
    }

    public function multiple_course_restore(Request $request)
    {
        if (!empty($request->id)) {
            Course::whereIn('id', $request->id)->restore();
            $course = Course::whereIn('id', $request->id)->update(['status' => 0]);

            return redirect('subject-list')->with('success', Config('constant.msg.record_restore_1'));
        } else
            return redirect('subject-list');
    }

    //Subject Method
    public function subject()
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        return view('subject/subject', $data);
    }

    public function subject_list()
    {
        $data['subject'] = Subject::/*where('status',1)->*/
        with('course')->orderBy('id','DESC')->paginate(10);
        $data['subjectDelete'] = Subject::onlyTrashed()->
        with('course')->where('status', 0)->get();
        return view('subject/subject_list', $data);
    }

    public function insert_subject(Request $request)
    {

        if (isset($request->file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['image' => $filename]);
        } else
            $request->merge(['image' => ('uploads/preview_image/dummy.png')]);

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        } else
            $request->merge(['banner_img' => ('uploads/preview_image/dummy.png')]);


        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        } else
            $request->merge(['icon_img' => ('uploads/preview_image/dummy.png')]);

             $request->merge(['data_type' => $request->data_type1]);

        if (Subject::insert($request->except(['_token', 'file', 'id', 'icon_img_file', 'banner_img_file','data_type1'])))
            return redirect('course')->with('success', Config('constant.msg.record_insert_1'));
        else
            return redirect('course')->with('error', Config('constant.msg.record_insert_0'));
    }

    public function edit_subject($id)
    {
        $data['subject'] = Subject::where('id', $id)->first();
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        return view('subject/subject', $data);
    }

    public function update_subject($id, Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 250, 250, $filename);
            $request->merge(['image' => $filename]);
        }

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        }
        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/subject/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        }
 $request->merge(['data_type' => $request->data_type1]);

        if (Subject::where('id', $id)->update($request->except(['_token', 'file', 'id', 'icon_img_file', 'banner_img_file','data_type1'])))
            return redirect('course-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('course-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    public function delete_subject($id)
    {
        $subject = Subject::where('id', $id)->update(['status' => 0]);
        return json_encode($subject);
    }

    public function statusChangeSubject($id)
    {
        $subject = Subject::where('id', $id)->first();
        if ($subject->status == 0)
            Subject::where('id', $id)->update(['status' => 1]);
        else
            Subject::where('id', $id)->update(['status' => 0]);
        return json_encode($subject);
    }

    public function soft_deleteSubject($id)
    {
        $subject = Subject::find($id);
        $subject->status = 0;
        $subject->save();
        $subject->delete();
        return json_encode($subject);
    }

    public function soft_restoreSubject($id)
    {
        $subject = Subject::where('id', $id)->restore();
        return json_encode($subject);
    }

    public function multiple_subject_delete(Request $request)
    {
        if (!empty($request->id)) {
            $subject = Subject::whereIn('id', $request->id)->update(['status' => 0]);
            Subject::whereIn('id', $request->id)->delete();
            return redirect('course-list')->with('success', Config('constant.msg.record_delete_1'));
        } else
            return redirect('course-list');
    }

    public function multiple_subject_restore(Request $request)
    {
        if (!empty($request->id)) {
            Subject::whereIn('id', $request->id)->restore();
            $subject = Subject::whereIn('id', $request->id)->update(['status' => 0]);

            return redirect('course-list')->with('success', Config('constant.msg.record_restore_1'));
        } else
            return redirect('course-list');
    }

    //Chapter Method
    public function chapter()
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        //$data['subject']=Subject::where('status',1)->pluck('subject','id');
        return view('chapter/chapter', $data);
    }

    public function chapter_list()
    {
        $data['chapter'] = Chapter::/*where('status',1)->*/
        with('subject')->orderBy('id','DESC')->paginate(10);
        $data['chapterDelete'] = Chapter::onlyTrashed()->
        with('subject')->where('status', 0)->get();
        return view('chapter/chapter_list', $data);
    }

    public function insert_chapter(Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['image' => $filename]);
        } else
            $request->merge(['image' => ('uploads/preview_image/dummy.png')]);

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        } else
            $request->merge(['banner_img' => ('uploads/preview_image/dummy.png')]);


        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        } else
            $request->merge(['icon_img' => ('uploads/preview_image/dummy.png')]);

        if (Chapter::insert($request->except(['_token', 'file', 'course_id', 'id', 'banner_img_file', 'icon_img_file'])))
            return redirect('chapter')->with('success', Config('constant.msg.record_insert_1'));
        else
            return redirect('chapter')->with('error', Config('constant.msg.record_insert_0'));
    }

    public function edit_chapter($id)
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        $data['chapter'] = Chapter::where('id', $id)->first();
        if (!empty($data['chapter']))
            $data['chapter']['course_id'] = Subject::where('id', $data['chapter']->subject_id)->first(['course_id'])->course_id;
        else
            $data['chapter']['course_id'] = '';
        return view('chapter/chapter', $data);
    }

    public function update_chapter($id, Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 250, 250, $filename);
            $request->merge(['image' => $filename]);
        }

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        }
        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/chapter/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        }

        if (Chapter::where('id', $id)->update($request->except(['_token', 'file', 'course_id', 'id', 'banner_img_file', 'icon_img_file'])))
            return redirect('chapter-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('chapter-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    public function delete_chapter($id)
    {
        $chapter = Chapter::where('id', $id)->update(['status' => 0]);
        return json_encode($chapter);
    }

    public function statusChangeChapter($id)
    {

        $chapter = Chapter::where('id', $id)->first();
        if ($chapter->status == 0)
            Chapter::where('id', $id)->update(['status' => 1]);
        else
            Chapter::where('id', $id)->update(['status' => 0]);
        return json_encode($chapter);
    }

    public function soft_deleteChapter($id)
    {
        $chapter = Chapter::find($id);
        $chapter->status = 0;
        $chapter->save();
        $chapter->delete();
        return json_encode($chapter);
    }

    public function soft_restoreChapter($id)
    {
        $chapter = chapter::where('id', $id)->restore();
        return json_encode($chapter);
    }

    public function multiple_chapter_delete(Request $request)
    {
        if (!empty($request->id)) {
            $chapter = Chapter::whereIn('id', $request->id)->update(['status' => 0]);
            chapter::whereIn('id', $request->id)->delete();
            return redirect('chapter-list')->with('success', Config('constant.msg.record_delete_1'));
        } else
            return redirect('chapter-list');
    }

    public function multiple_chapter_restore(Request $request)
    {
        if (!empty($request->id)) {
            chapter::whereIn('id', $request->id)->restore();
            $chapter = Chapter::whereIn('id', $request->id)->update(['status' => 0]);

            return redirect('chapter-list')->with('success', Config('constant.msg.record_restore_1'));
        } else
            return redirect('chapter-list');
    }

    //Topic Method
    public function topic()
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        //$data['chapter']=Chapter::where('status',1)->pluck('chapter','id');
        return view('topic/topic', $data);
    }

    public function topic_list()
    {
        $data['topic'] = Topic::/*where('status',1)->*/
        with('chapter')->orderBy('id','DESC')->paginate(10);
        $data['topicDelete'] = Topic::onlyTrashed()->
        with('chapter')->where('status', 0)->get();
        return view('topic/topic_list', $data);
    }

    public function insert_topic(Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 250, 250, $filename);
            $request->merge(['image' => $filename]);
        } else
            $request->merge(['image' => ('uploads/preview_image/dummy.png')]);

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 275, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['banner_img' => $filename]);
        } else
            $request->merge(['banner_img' => ('uploads/preview_image/dummy.png')]);


        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['icon_img' => $filename]);
        } else
            $request->merge(['icon_img' => ('uploads/preview_image/dummy.png')]);

        if (Topic::insert($request->except(['_token', 'file', 'course_id', 'subject_id', 'id', 'banner_img_file', 'icon_img_file'])))
            return redirect('topic')->with('success', Config('constant.msg.record_insert_1'));
        else
            return redirect('topic')->with('error', Config('constant.msg.record_insert_0'));
    }

    public function edit_topic($id)
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        $data['topic'] = Topic::where('id', $id)->first();
        if (!empty($data['topic'])) {
            $data['topic']['subject_id'] = Chapter::where('id', $data['topic']->chapter_id)->first(['subject_id'])->subject_id;
            $data['topic']['course_id'] = Subject::where('id', $data['topic']->subject_id)->first(['course_id'])->course_id;
        } else {
            $data['topic']['subject_id'] = '';
            $data['topic']['course_id'] = '';
        }
        //dd($data['topic']);
        return view('topic/topic', $data);
    }

    public function update_topic($id, Request $request)
    {
        if (isset($request->file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('file'), 250, 250, $filename);
            $request->merge(['image' => $filename]);
        }

        //banner Image
        if (isset($request->banner_img_file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->banner_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('banner_img_file'), 500, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,25);
            $request->merge(['banner_img' => $filename]);
        }
        //icon Image
        if (isset($request->icon_img_file)) {
            $filename = 'uploads/topic/' . time() . '.' . $request->icon_img_file->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('icon_img_file'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,25);
            $request->merge(['icon_img' => $filename]);
        }

        if (Topic::where('id', $id)->update($request->except(['_token', 'file', 'course_id', 'subject_id', 'id', 'banner_img_file', 'icon_img_file'])))
            return redirect('topic-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('topic-list')->with('error', Config('constant.msg.record_updated_0'));
        //return redirect('topic-list');
    }

    public function delete_topic($id)
    {
        $topic = Topic::where('id', $id)->update(['status' => 0]);
        return json_encode($topic);
    }

    public function statusChangeTopic($id)
    {
        $topic = Topic::where('id', $id)->first();
        if ($topic->status == 0)
            Topic::where('id', $id)->update(['status' => 1]);
        else
            Topic::where('id', $id)->update(['status' => 0]);
        return json_encode($topic);
    }

    public function soft_deleteTopic($id)
    {
        $topic = Topic::find($id);
        $topic->status = 0;
        $topic->save();
        $topic->delete();
        return json_encode($topic);
    }

    public function soft_restoreTopic($id)
    {
        $topic = Topic::where('id', $id)->restore();
        return json_encode($topic);
    }

    public function multiple_topic_delete(Request $request)
    {
        if (!empty($request->id)) {
            $topic = Topic::whereIn('id', $request->id)->update(['status' => 0]);
            Topic::whereIn('id', $request->id)->delete();
            return redirect('topic-list')->with('success', Config('constant.msg.record_delete_1'));
        } else
            return redirect('topic-list');
    }

    public function multiple_topic_restore(Request $request)
    {
        if (!empty($request->id)) {
            Topic::whereIn('id', $request->id)->restore();
            $topic = Topic::whereIn('id', $request->id)->update(['status' => 0]);

            return redirect('topic-list')->with('success', Config('constant.msg.record_restore_1'));
        } else
            return redirect('topic-list');
    }

    //Document Methods
    public function document()
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        //$data['topic']=Topic::where('status',1)->pluck('topic','id');
        return view('document/document', $data);
    }

    public function insert_document(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doc_type' => 'required|array|min:1'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
            // return redirect('document')->with('error', implode(',',$validator->errors()->all()));
        } else {


            if (in_array('Audio_Text', $request->doc_type)) {
                $request->except(['doc_type']);
                $data = $request->doc_type;
                $data[0] = 'Audio';
                $data[1] = 'Text';
                $request->merge(['doc_type' => $data]);
            } elseif (in_array('Video_Text', $request->doc_type)) {
                $request->except(['doc_type']);
                $data = $request->doc_type;
                $data[0] = 'Video';
                $data[1] = 'Text';
                $request->merge(['doc_type' => $data]);

            }

            if (isset($request->preview)) {
                $filename = 'uploads/preview_image/' . time() . '.' . $request->preview->getClientOriginalExtension();
                $preview_image = imageResizeAndSave($request->file('preview'), 250, 250, $filename);
            } else {
                $filename = ('uploads/preview_image/dummy.png');
            }
            //insert in document
            $document_id = Document::create(array(
                'course_id' => $request->course_id,
                'subject_id' => $request->subject_id,
                'chapter_id' => $request->chapter_id,
                'topic_id' => $request->topic_id,
                'title' => $request->title,
                'author_name' => $request->author_name,
                'description' => $request->description,
                'preview_image' => $filename,
                'user_type' => isset($request->user_type) ? implode(",", $request->user_type) : 'Student',
            ));


            if (in_array('Video', $request->doc_type)) {
                if ($request->video_type == 'file') {
                    $duration = null;
                    $filename = fileUpload($request->video_file, 'uploads/video');
                    if ($filename != '') {
                        $path = 'uploads/video/' . $filename;
                        $getID3 = new \getID3;
                        $file = $getID3->analyze($path);
                        $duration = gmdate('H:i:s', $file['playtime_seconds']);
                    }
                    $array[] = array(
                        'document_id' => $document_id->id,
                        'doc_url' => '',
                        'doc_file' => $filename,
                        'content' => $filename,
                        'duration' => $duration,
                        'doc_type' => 'Video',

                    );
                } elseif ($request->video_type == 'url') {
                    //$video_url = $this->youtubeDownload($request->video_url);
                    $array[] = array(
                        'document_id' => $document_id->id,
                        'doc_url' => $request->video_url,
                        'doc_file' => $request->video_url,
                        'content' => $request->video_url,
                        'duration' => '',
                        'doc_type' => 'Video',

                    );
                }
            }
            if (in_array('Audio', $request->doc_type)) {
                //$filename=fileUpload($request->audio_file,'uploads/audio');
                $duration = null;
                $getID3 = new \getID3;
                $file = $getID3->analyze($request->file('audio_file'));
                $duration = gmdate('H:i:s', $file['playtime_seconds']);
                $filename = 'uploads/audio/' . time() . '.' . $request->audio_file->getClientOriginalExtension();
                $audio = $request->file('audio_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($audio));
                $filename = ($filename);

                $array[] = array(
                    'document_id' => $document_id->id,
                    'doc_url' => '',
                    'doc_file' => $filename,
                    'content' => $filename,
                    'duration' => $duration,
                    'doc_type' => 'Audio',

                );
            }
            if (in_array('Text', $request->doc_type)) {
                $array[] = array(
                    'document_id' => $document_id->id,
                    'doc_url' => '',
                    'doc_file' => $request->content,
                    'content' => $request->content,
                    'duration' => '',
                    'doc_type' => 'Text',

                );
            }

            if (isset($array) && !empty($array)) {
                if (Media::insert($array)) {
                     $send = $this->PushNotificationController->sendNotificationOnDocumentAdd($document_id->id);
                    if ($send) {
                        $users= User::whereNotNull('device_token')->pluck('id')->toArray();
                        foreach ($users as $user) {
                            $notification[] = [
                                'sender_id' => Auth::user()->id,
                                'receiver_id' => $user,
                                'title' => 'Notification On Document Add',
                                'message' => 'New Document add ' . $request->title,
                                'doc_id' => $document_id->id,
                                'event' => 'Doc',
                            ];
                        }
                        Notification::insert($notification);
                    }
                    return response()->json(['status' => 200, 'msg' => Config('constant.msg.record_insert_1')], '200');
                    // return redirect('document-list')->with('success', Config('constant.msg.record_insert_1'));
                } else
                    return response()->json(['status' => 201, 'msg' => Config('constant.msg.record_insert_0')], '201');
                // return redirect('document-list')->with('error', Config('constant.msg.record_insert_0'));

            } else
                return response()->json(['status' => 201, 'msg' => Config('constant.msg.record_insert_0')], '201');
            //return redirect('document-list')->with('success', Config('constant.msg.record_insert_1'));
        }
    }

    public function document_list2()
    {
        $data=Course::where('status', 1)->orderBy('id','DESC')->paginate(20);
        //dd($data);
        return view('document/document_list2',compact('data'));
    }
     public function sub_document_list2($id=null)
    {
        $data=Subject::where(['status'=>1,'course_id'=>$id])->paginate(20);
        if(!count($data)>0)
        {
            $document=Document::where(['status'=>1,'course_id'=>$id])->paginate(20);
            //dd($document);
            return view('document/doc-document-list',compact('document'));
        }
        else
        {
            return view('document/sub-document-list',compact('data'));
        }
    }
    public function chap_document_list2($id=null)
    {
        $data=Chapter::where(['status'=>1,'subject_id'=>$id])->paginate(20);
        //dd($data);
        if(!count($data)>0)
        {
            $document=Document::where(['status'=>1,'subject_id'=>$id])->paginate(20);
            //dd($document);
            return view('document/doc-document-list',compact('document'));
        }
        else
        {
            return view('document/chap-document-list',compact('data'));
        }
    }
    public function topic_document_list2($id=null)
    {
        $data=Topic::where(['status'=>1,'chapter_id'=>$id])->paginate(20);
        //dd($data);
        if(!count($data)>0)
        {
            $document=Document::where(['status'=>1,'chapter_id'=>$id])->paginate(20);
            // dd($document);
            return view('document/doc-document-list',compact('document'));
        }
        else
        {
            return view('document/topic-document-list',compact('data'));
        }
    }
    public function doc_document_list2($id=null)
    {
            $document=Document::where('topic_id',$id)->paginate(20);
            //dd($document);
            return view('document/doc-document-list',compact('document'));
        
    }
    public function media_document_list2($id=null)
    {
            $media=media::with('document')->where('document_id',$id)->paginate(20);
            //dd($media);
            return view('document/media-document-list',compact('media'));
        
    }

    public function document_list()
    {
        $document = Document::where('status', 1)->orderBy('id', 'desc')->with('topic', 'course', 'subject', 'chapter', 'media')->paginate(20);
        $topic = $video = $audio = $text = $course = $chapter = $subject = [];
        $media = [];
        foreach ($document as $val) {
            if (isset($val->media)) {
                $media[$val->id] = ['document_name' => $val->title, 'media' => $val->media];
            }
            if (isset($val->topic)) {
                $topic[$val->topic->topic][] = array(
                    'id' => $val->id,
                    'user_type' => $val->user_type,
                    'title' => $val->title,
                    'author_name' => $val->author_name,
                    'description' => $val->description,
                    'preview_image' => $val->preview_image,
                    'doc_type' => $val->doc_type,
                    'doc_file' => $val->doc_file,
                    'doc_url' => $val->doc_url,
                    'content' => $val->content,
                );
                if ($val->doc_type == "Video") {
                    $video[$val->topic->topic][] = array(
                        'id' => $val->id,
                        'user_type' => $val->user_type,
                        'title' => $val->title,
                        'author_name' => $val->author_name,
                        'description' => $val->description,
                        'preview_image' => $val->preview_image,
                        'doc_type' => $val->doc_type,
                        'doc_file' => $val->doc_file,
                        'doc_url' => $val->doc_url,
                        'content' => $val->content,
                    );
                } elseif ($val->doc_type == "Audio") {
                    $audio[$val->topic->topic][] = array(
                        'id' => $val->id,
                        'user_type' => $val->user_type,
                        'title' => $val->title,
                        'author_name' => $val->author_name,
                        'description' => $val->description,
                        'preview_image' => $val->preview_image,
                        'doc_type' => $val->doc_type,
                        'doc_file' => $val->doc_file,
                        'doc_url' => $val->doc_url,
                        'content' => $val->content,
                    );
                } elseif ($val->doc_type == "Text") {
                    $text[$val->topic->topic][] = array(
                        'id' => $val->id,
                        'user_type' => $val->user_type,
                        'title' => $val->title,
                        'author_name' => $val->author_name,
                        'description' => $val->description,
                        'preview_image' => $val->preview_image,
                        'doc_type' => $val->doc_type,
                        'doc_file' => $val->doc_file,
                        'doc_url' => $val->doc_url,
                        'content' => $val->content,
                    );
                }
            } elseif (isset($val->course) && $val->subject_id == '') {
                $course[$val->course->course][] = array(
                    'id' => $val->id,
                    'user_type' => $val->user_type,
                    'title' => $val->title,
                    'author_name' => $val->author_name,
                    'description' => $val->description,
                    'preview_image' => $val->preview_image,
                    'doc_type' => $val->doc_type,
                    'doc_file' => $val->doc_file,
                    'doc_url' => $val->doc_url,
                    'content' => $val->content,
                );
            } elseif (isset($val->subject) && $val->chapter_id == '') {
                $subject[$val->subject->subject][] = array(
                    'id' => $val->id,
                    'user_type' => $val->user_type,
                    'title' => $val->title,
                    'author_name' => $val->author_name,
                    'description' => $val->description,
                    'preview_image' => $val->preview_image,
                    'doc_type' => $val->doc_type,
                    'doc_file' => $val->doc_file,
                    'doc_url' => $val->doc_url,
                    'content' => $val->content,
                );
            } elseif (isset($val->chapter) && $val->topic_id == '') {
                $chapter[$val->chapter->chapter][] = array(
                    'id' => $val->id,
                    'user_type' => $val->user_type,
                    'title' => $val->title,
                    'author_name' => $val->author_name,
                    'description' => $val->description,
                    'preview_image' => $val->preview_image,
                    'doc_type' => $val->doc_type,
                    'doc_file' => $val->doc_file,
                    'doc_url' => $val->doc_url,
                    'content' => $val->content,
                );
            }
        }
        $data['topic'] = $topic;
        $data['course'] = $course;
        $data['subject'] = $subject;
        $data['chapter'] = $chapter;


        $data['video'] = $video;
        $data['audio'] = $audio;
        $data['text'] = $text;
        $data['media'] = $media;
        //dd($data);
        return view('document/document_list', $data, compact('document'));
    }

    public function delete_document($id)
    {
        $document = Document::where('id', $id)->update(['status' => 0]);
        // $document = Media::where('id', $id)->first();

        // //dd($data['document']->document);

        // $t = Storage::disk('spaces')->delete($document->doc_file);
        // Media::where('id', $id)->delete();
        return json_encode($document);
    }

    public function edit_document($id)
    {
        $data['document'] = Document::with('media')->where('id', $id)->first();
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        //$data['topic']=Topic::where('status',1)->pluck('topic','id');
        // dd($data);
        return view('document/document', $data);
    }

    public function edit_media($media)
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        $data['document'] = Media::with('document')->where('id', $media)->first();
        unset($data['document']['document']['doc_type']);
        //dd($data['document']->document);
        return view('document/media_edit', $data);
    }

    public function delete_media($id)
    {
        $document = Media::where('id', $id)->first();

        //dd($data['document']->document);

        $t = Storage::disk('spaces')->delete($document->doc_file);
        Media::where('id', $id)->delete();
        return redirect('document-list');
    }

    public function update_media(Request $request)
    {
        // dd($request->all());

        $array = [];

        $array['doc_type'] = $request->doc_type[0];
        if ($request->doc_type[0] == 'Text') {
            $array['content'] = $request->content;
            $array['doc_file'] = $request->content;
        } elseif ($request->doc_type[0] == 'Audio') {
            $duration = null;
            /*            $filename=fileUpload($request->audio_file,'uploads/audio');
                        if($filename!=''){
                            $path='uploads/audio/'.$filename;
                            $getID3 = new \getID3;
                            $file = $getID3->analyze($path);
                            $duration = gmdate('H:i:s', $file['playtime_seconds']);
                            $array['doc_file']=$filename;
                            $array['duration']=$duration;
                        }*/

            $duration = null;
            $getID3 = new \getID3;
            if ($request->file('audio_file')) {
                $file = $getID3->analyze($request->file('audio_file'));
                $duration = gmdate('H:i:s', $file['playtime_seconds']);

                $filename = 'uploads/audio/' . time() . '.' . $request->audio_file->getClientOriginalExtension();
                $audio = $request->file('audio_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($audio));
                $filename = $filename;
                $array['doc_file'] = $filename;
                $array['duration'] = $duration;
            }
        } elseif ($request->doc_type[0] == 'Video') {
            if (isset($request->video_file)) {
                $duration = null;
                $filename = fileUpload($request->video_file, 'uploads/video');
                if ($filename != '') {
                    $path = 'uploads/video/' . $filename;
                    $getID3 = new \getID3;
                    $file = $getID3->analyze($path);
                    $duration = gmdate('H:i:s', $file['playtime_seconds']);
                    $array['doc_file'] = $filename;
                    $array['duration'] = $duration;
                }
            } else {
                if (strpos($request->video_url, 'googlevideo') > 0) {
                    $video_url = $request->video_url;
                } else {
                    $video_url = $request->video_url;
                }
                $array['doc_url'] = $video_url;
                $array['doc_file'] = $video_url;
                $array['content'] = $video_url;
            }
        }
        // $array['course_id'] = $request->course_id;
        // $array['subject_id'] = $request->subject_id;
        // $array['chapter_id'] = $request->chapter_id;
        // $array['topic_id'] = $request->topic_id;
        // $array['title'] = $request->title;
        // $array['author_name'] = $request->author_name;
        // $array['description'] = $request->description;
        // $array['user_type'] = implode(",", $request->user_type);
        if (isset($request->preview)) {
            $filename = 'uploads/preview_image/' . time() . '.' . $request->preview->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('preview'), 250, 250, $filename);
            $array['preview_image'] = $preview_image;
        }
        //dd($array);
        $documentData = ['course_id' => ($request->course_id) ? $request->course_id : null, 'subject_id' => ($request->subject_id) ? $request->subject_id : null, 'chapter_id' => ($request->chapter_id) ? $request->chapter_id : null, 'topic_id' => ($request->topic_id) ? $request->topic_id : null];

        Document::where('id', $request->doc_id)->update($documentData);
        if (Media::where('id', $request->id)->update($array))
            return redirect('document-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('document-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    public function update_document($id, Request $request)
    {
        // dd($request->all());
        if ($request->doc_type_text == 'Text') {
            $array['content'] = $request->content;
            $array['doc_file'] = $request->content;
        } elseif ($request->doc_type_text == 'Audio') {
            $duration = null;
            /*            $filename=fileUpload($request->audio_file,'uploads/audio');
                        if($filename!=''){
                            $path='uploads/audio/'.$filename;
                            $getID3 = new \getID3;
                            $file = $getID3->analyze($path);
                            $duration = gmdate('H:i:s', $file['playtime_seconds']);
                            $array['doc_file']=$filename;
                            $array['duration']=$duration;
                        }*/

            $duration = null;
            $getID3 = new \getID3;
            $file = $getID3->analyze($request->file('audio_file'));
            $duration = gmdate('H:i:s', $file['playtime_seconds']);

            $filename = 'uploads/audio/' . time() . '.' . $request->audio_file->getClientOriginalExtension();
            $audio = $request->file('audio_file');
            $t = Storage::disk('spaces')->put($filename, file_get_contents($audio));
            $filename = $filename;
            $array['doc_file'] = $filename;
            $array['duration'] = $duration;
        } elseif ($request->doc_type_text == 'Video') {
            if (isset($request->video_file)) {
                $duration = null;
                $filename = fileUpload($request->video_file, 'uploads/video');
                if ($filename != '') {
                    $path = 'uploads/video/' . $filename;
                    $getID3 = new \getID3;
                    $file = $getID3->analyze($path);
                    $duration = gmdate('H:i:s', $file['playtime_seconds']);
                    $array['doc_file'] = $filename;
                    $array['duration'] = $duration;
                }
            } else {
                $video_url = $this->youtubeDownload($request->doc_url);
                $array['doc_url'] = $video_url;
                $array['doc_file'] = $video_url;
                $array['content'] = $video_url;
            }
        }
        $array['course_id'] = $request->course_id;
        $array['subject_id'] = $request->subject_id;
        $array['chapter_id'] = $request->chapter_id;
        $array['topic_id'] = $request->topic_id;
        $array['title'] = $request->title;
        $array['author_name'] = $request->author_name;
        $array['description'] = $request->description;
        $array['user_type'] = implode(",", $request->user_type);
        if (isset($request->preview)) {
            $filename = 'uploads/preview_image/' . time() . '.' . $request->preview->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('preview'), 250, 250, $filename);
            $array['preview_image'] = $filename;
        }

        if (Document::where('id', $id)->update($array))
            return redirect('document-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('document-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    //Staff Method
     public function user()
    {

        $paidUser=User::join('user_purchases', 'users.id', '=', 'user_purchases.user_id')->select('users.*','user_purchases.created_at','user_purchases.end_date')->whereDate('user_purchases.end_date','>=', Carbon::now())->get();

      $staffs = User::with('roles')->whereNotIn('id',$paidUser->pluck('id'))->get();

        $array = [];
        $institute = [];
        $city = [];
        foreach ($staffs as $value) {
            if (count($value->roles) > 0 && $value->roles[0]->name != 'Admin') {
               
                $array['student'][] = array(
                    'id' => $value->id,
                    'name' => $value->name,
                    'email' => $value->email,
                    'mobile' => $value->mobile,
                    'photo' => $value->photo,
                    'age' => $value->age,
                    'city' => $value->city,
                    'institute' => $value->institute,
                    'ref_code' => $value->ref_code,
                    'self_ref_code' => $value->self_ref_code,
                    'total_ref_amt' => $value->total_ref_amt,
                    'ref_code' => $value->ref_code,
                    'course' => (($value->course) ? $value->course->course : '')
                );
                $city[]=$value->city;
                $institute[]=$value->institute;

            }
        }
        
            foreach ($paidUser as $key => $value) {
                $city[]=$value->city;
                $institute[]=$value->institute;
            }
        
        $city=array_unique($city);
        $institute=array_unique($institute);
        $city=array_filter($city, function($value) { return $value != ''; });
        $institute=array_filter($institute, function($value) { return $value != ''; });
        $data['staff'] = $array;
        return view('staff/staff', $data, compact('staffs','paidUser','city','institute'));
    }


    public function delete_user($id)
    {

        $topic = User::find($id);
        $topic->delete();
        Bookmark::where('staff_id',$id)->delete();
        Library::where('staff_id',$id)->delete();
        //dd($topic);
        // $staff = User::where('id', $id)->update(['status' => 0]);
        return redirect('user');
    }

    public function view_user($id)
    {
        $data['staff'] = User::where('id', $id)->with('library')->first();
        $likes = $history = $downloads = $mylist = [];
        $data['likes_date'] = $data['history_date'] = $data['downloads_date'] = $data['mylist_date'] = [];
        if (!empty($data['staff']->library)) {
            $likes = explode(",", $data['staff']->library->likes_id);
            $likes_array = ($data['staff']->library->likes) ? json_decode($data['staff']->library->likes) : [];
            foreach ($likes_array as $val) {
                $data['likes_date'][$val->doc_id] = $val->date;
            }
        }
        if (!empty($data['staff']->library)) {
            $history = explode(",", $data['staff']->library->history_id);
            $history_array = ($data['staff']->library->history) ? json_decode($data['staff']->library->history) : [];
            foreach ($history_array as $val) {
                $data['history_date'][$val->doc_id] = $val->date;
            }
        }
        if (!empty($data['staff']->library)) {
            $downloads = explode(",", $data['staff']->library->downloads_id);
            $downloads_array = ($data['staff']->library->downloads) ? json_decode($data['staff']->library->downloads) : [];
            foreach ($downloads_array as $val) {
                $data['downloads_date'][$val->doc_id] = $val->date;
            }
        }
        // if (!empty($data['staff']->library)) {
        //     $mylist = explode(",", $data['staff']->library->mylist_id);
        //     $mylist_array = ($data['staff']->library->mylist) ? json_decode($data['staff']->library->mylist) : [];
        //     foreach ($mylist_array as $val) {
        //         $data['mylist_date'][$val->doc_id] = $val->date;
        //     }
        // }
        $data['likes'] = Document::with('topic','mediaList')->whereIn('id', $likes)->get();
        $data['history'] = Document::with('topic','mediaList')->whereIn('id', $history)->get();
        $data['downloads'] = Document::with('topic','mediaList')->whereIn('id', $downloads)->get();
        // $data['mylist'] = Document::with('topic')->whereIn('id', $mylist)->get();

        //dd($data);
        return view('staff/view_staff', $data);
    }

    public function getSubjectByCourseId($course_id)
    {
        $subject = Subject::/*where('status',1)->*/
        where('course_id', $course_id)->pluck('subject', 'id');
        return json_encode($subject);
    }

    public function getChapterBySubjectId($subject_id)
    {
        $chapter = Chapter::/*where('status',1)->*/
        where('subject_id', $subject_id)->pluck('chapter', 'id');
        return json_encode($chapter);
    }

    public function getTopicByChapterId($chapter_id)
    {
        $topic = Topic::/*where('status',1)->*/
        where('chapter_id', $chapter_id)->pluck('topic', 'id');
        return json_encode($topic);
    }

    //Plans Method
    public function plan()
    {
        return view('plan/plan');
    }

    public function plan_list()
    {
        $data['plan'] = Plan::where('status', 1)->paginate(10);
        return view('plan/plan_list', $data);
    }

    public function insert_plan(Request $request)
    {
        /*$from_date = date("Y-m-d");
        if($request->field=="Month")
            $to_date = date("Y-m-d", strtotime(" +".$request->duration." months"));
        else
            $to_date = date("Y-m-d", strtotime(" +".$request->duration." years"));
        $request->merge(['date_from'=>$from_date,'date_to'=>$to_date]);*/
        if (isset($request->preview)) {
            $filename = 'uploads/plan/' . time() . '.' . $request->preview->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('preview'), 250, 250, $filename);
            // $preview_image=fileUpload($request->file,'uploads/course',250,250);
            $request->merge(['image' => $preview_image]);
        } else
            $request->merge(['image' => ('uploads/plan/dummy.png')]);

        if (Plan::insert($request->except(['_token', 'preview'])))
            return redirect('plan-list')->with('success', Config('constant.msg.record_insert_1'));
        else
            return redirect('plan-list')->with('error', Config('constant.msg.record_insert_0'));
    }

    public function edit_plan($id)
    {
        $data['plan'] = Plan::where('id', $id)->first();
        return view('plan/plan', $data);
    }

    public function update_plan($id, Request $request)
    {

        if (isset($request->preview)) {
            $filename = 'uploads/plan/' . time() . '.' . $request->preview->getClientOriginalExtension();
            $preview_image = imageResizeAndSave($request->file('preview'), 250, 250, $filename);
            $request->merge(['image' => $filename]);
        }
        if (Plan::where('id', $id)->update($request->except(['_token', 'preview'])))
            return redirect('plan-list')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('plan-list')->with('error', Config('constant.msg.record_updated_0'));
    }

    public function delete_plan($id)
    {
        $plan = Plan::where('id', $id)->update(['status' => 0]);
        return json_encode($plan);
    }

    public function splashImages()
    {
        $data = DB::table('intro_images')->get()->toArray();
        return view('splash/splash_list', compact('data'));
    }

    public function edit_splash($id)
    {
        $data['splash'] = DB::table('intro_images')->where('id', $id)->first();
        return view('splash/splash', $data);
    }

    public function update_splash($id, Request $request)
    {

        $validatedData = $request->validate([
            'preview' => 'required',
        ]);

        //$preview_image=fileUpload($request->preview,'uploads/splash',110,200);
        $filename = 'uploads/splash/' . time() . '.' . $request->preview->getClientOriginalExtension();
        $image = $request->file('preview');
        $t = Storage::disk('spaces')->put($filename, file_get_contents($image), 'public');
        $preview_image = Storage::disk('spaces')->url($filename);

        if (DB::table('intro_images')->where('id', $id)->update(['image' => $preview_image]))
            return redirect('splash')->with('success', Config('constant.msg.record_updated_1'));
        else
            return redirect('splash')->with('error', Config('constant.msg.record_updated_0'));
    }


    public function homeImages()
    {
        return view('home_images.home_image');
    }

    public function addHomeImages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required| mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return redirect('home-images')->with('error', implode(',', $validator->errors()->all()));
        } else {
            $filename = 'uploads/slider/' . time() . '.' . $request->image->getClientOriginalExtension();
            $image = imageResizeAndSave($request->file('image'), 1920, 550, $filename);

            //create image
            if (DB::table('home_images')->insert(['image' => $image]))
                return redirect('home-images-list')->with('success', Config('constant.msg.record_insert_1'));
            else
                return redirect('home-images')->with('error', Config('constant.msg.record_insert_0'));


        }
    }

    public function homeImagelist()
    {
        $data = DB::table('home_images')->where('delated_at',null)->get()->toArray();
        return view('home_images.home_image_list', compact('data'));
    }

    public function edit_homeImage($id)
    {
        $data['document'] = DB::table('home_images')->where('id', $id)->first();
        return view('home_images.home_image', $data);
    }

    public function update_homeImages($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required| mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return redirect('edit-home-images/' . $id)->with('error', implode(',', $validator->errors()->all()));
        } else {
            $filename = 'uploads/slider/' . time() . '.' . $request->image->getClientOriginalExtension();
            $filename2 = 'uploads/slider/' . time() . '.' . $request->image->getClientOriginalExtension();
            $image = imageResizeAndSave($request->file('image'), 1920, 550, $filename);
            $mobile_images = imageResizeAndSave($request->file('image'), 1920, 550, $filename2);
          

            if (DB::table('home_images')->where('id', $id)->update(['image' => $image,'image' => $mobile_images]))
                return redirect('home-images-list')->with('success', Config('constant.msg.record_updated_1'));
            else
                return redirect('home-images')->with('error', Config('constant.msg.record_updated_0'));
        }
    }


    public function youtubeDownload($url)
    {
        // $url=Media::where(['status'=>1,'doc_type'=>'Video','id'=>5])->first(['doc_url'])->doc_url;
        // dd($url);
        $yt = new YouTubeDownloader();
        $links = $yt->getDownloadLinks($url);
        // dd($links['18']);
        return $links['18']['url'];
    }

    public function multiDocument()
    {
        $data['course'] = Course::/*where('status',1)->*/
        pluck('course', 'id');
        //$data['topic']=Topic::where('status',1)->pluck('topic','id');
        return view('document/multi_document', $data);
    }

    public function insertMultiDocument(Request $request)
    {
       
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'doc_type' => 'required|array|min:1'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 201, 'msg' => implode(',', $validator->errors()->all())], '201');
            // return redirect('document')->with('error', implode(',',$validator->errors()->all()));
        } else {

            //return response()->json(['status' => 200, 'msg' => $request->all()], '200');
            $request->except(['doc_type']);
            $data = [];

            foreach ($request->course_id as $k => $item) {
                if (isset($request->doc_type)) {
                    if (in_array('Audio_Text' . ($k ), $request->doc_type)) {

                        $data[$k + 1][] = 'Audio';
                        $data[$k + 1][] = 'Text';

                    }
                    if (in_array('Video_Text' . ($k ), $request->doc_type)) {
                        $data[$k + 1][] = 'Video';
                        $data[$k + 1][] = 'Text';
                    }
                    if (in_array('Audio' . ($k ), $request->doc_type)) {
                        $data[$k + 1][] = 'Audio';
                    }
                    if (in_array('Video' . ($k ), $request->doc_type)) {
                        $data[$k + 1][] = 'Video';
                    }
                    if (in_array('Text' . ($k ), $request->doc_type)) {
                        $data[$k + 1][] = 'Text';
                    }

                }

                if (isset($request->preview[$k ])) {
                    $filename = 'uploads/preview_image/' . time() . '.' . $request->preview[$k ]->getClientOriginalExtension();
                    $preview_image[] = imageResizeAndSave($request->preview[$k ], 250, 250, $filename);
                } else {
                    $preview_image[] = ('uploads/preview_image/dummy.png');
                }
                if (isset($request->video_type)) {
                    if (in_array('file' . ($k ), $request->video_type)) {
                        $video_type[$item] = 'file';
                    }
                    if (in_array('url' . ($k ), $request->video_type)) {
                        $video_type[$item] = 'url';
                    }
                }
                if (isset($request->video_url) && !empty($request->video_url[$k ])) {

                }
            }

            $request->merge(['doc_type' => $data]);
            $request->merge(['preview' => $preview_image]);
            $request->merge(['video_type' => isset($video_type) ? $video_type : ($video_type = [])]);

            foreach ($request->course_id as $key => $items) {

                //insert in document
                $document_id = Document::create(array(
                    'course_id' => $items,
                    'subject_id' => (isset($request->subject_id[$key]) ? $request->subject_id[$key] : ''),
                    'chapter_id' => (isset($request->chapter_id[$key]) ? $request->chapter_id[$key] : ''),
                    'topic_id' => (isset($request->topic_id[$key]) ? $request->topic_id[$key] : ''),
                    'title' => $request->title[$key],
                    'author_name' => $request->author_name[$key],
                    'description' => $request->description[$key],
                    'preview_image' => $preview_image[$key],
                    'user_type' => 'Student',
                ));

                if (isset($request->doc_type[$key + 1]) && in_array('Video', $request->doc_type[$key + 1])) {
                    if (isset($request->video_type[$items]) && $request->video_type[$items] == 'file') {
                        $duration = null;
                        $filename = fileUpload($request->video_file[$key + 1], 'uploads/video');
                        if ($filename != '') {
                            $path = 'uploads/video/' . $filename;
                            $getID3 = new \getID3;
                            $file = $getID3->analyze($path);
                            $duration = gmdate('H:i:s', $file['playtime_seconds']);
                        }
                        $array[] = array(
                            'document_id' => $document_id->id,
                            'doc_url' => '',
                            'doc_file' => $filename,
                            'content' => $filename,
                            'duration' => $duration,
                            'doc_type' => 'Video',

                        );
                    } elseif (isset($request->video_type[$items]) && $request->video_type[$items] == 'url') {
                        //$video_url = $this->youtubeDownload($request->video_url);
                        $array[] = array(
                            'document_id' => $document_id->id,
                            'doc_url' => $request->video_url[$key],
                            'doc_file' => $request->video_url[$key],
                            'content' => $request->video_url[$key],
                            'duration' => '',
                            'doc_type' => 'Video',

                        );
                    }
                }
                if (isset($request->doc_type[$key + 1]) && in_array('Audio', $request->doc_type[$key + 1])) {
                    //$filename=fileUpload($request->audio_file,'uploads/audio');
                    $duration = null;
                    $getID3 = new \getID3;
                    $file = $getID3->analyze($request->audio_file[$key]);
                    $duration = gmdate('H:i:s', $file['playtime_seconds']);
                    $filename = 'uploads/audio/' . time() . '.' . $request->audio_file[$key]->getClientOriginalExtension();
                    $audio = $request->audio_file[$key];
                    $t = Storage::disk('spaces')->put($filename, file_get_contents($audio));
                    $filename = ($filename);

                    $array[] = array(
                        'document_id' => $document_id->id,
                        'doc_url' => '',
                        'doc_file' => $filename,
                        'content' => $filename,
                        'duration' => $duration,
                        'doc_type' => 'Audio',

                    );
                }

                if (isset($request->doc_type[$key + 1]) && in_array('Text', $request->doc_type[$key + 1])) {
                    $array[]=array(
                        'document_id' => $document_id->id,
                        'doc_url' => '',
                        'doc_file' => $request->content[$key],
                        'content' => $request->content[$key],
                        'duration' => '',
                        'doc_type' => 'Text',

                    );
                }

            }
           
            if (isset($array) && !empty($array)) {

                if (Media::insert($array)) {
                    // $send = $this->PushNotificationController->sendNotificationOnDocumentAdd($document_id->id);
                    /*if ($send) {
                        foreach ($users as $user) {
                            $notification[] = [
                                'sender_id' => Auth::user()->id,
                                'receiver_id' => $user,
                                'title' => 'Notification On Document Add',
                                'message' => 'New Document add ' . $request->title,
                                'doc_id' => $document_id->id,
                                'event' => 'Doc',
                            ];
                        }
                        Notification::insert($notification);
                    }*/
                    return response()->json(['status' => 200, 'msg' => Config('constant.msg.record_insert_1')], '200');
                    // return redirect('document-list')->with('success', Config('constant.msg.record_insert_1'));
                } else
                    return response()->json(['status' => 201, 'msg' => Config('constant.msg.record_insert_0')], '201');
                // return redirect('document-list')->with('error', Config('constant.msg.record_insert_0'));

            } else
                return response()->json(['status' => 201, 'msg' => Config('constant.msg.record_insert_0')], '201');
            //return redirect('document-list')->with('success', Config('constant.msg.record_insert_1'));
        }
    }

    function addAbout(Request $request, SiteInfo $siteInfo)
    {
        if ($request->method() == 'POST') {
            $siteInfo->update(['title' => $request->title, 'content_data' => $request->content_data]);
            return redirect('site-info')->with('success', Config('constant.msg.record_updated_1'));
        } else {
            return view('site_info.edit', compact('siteInfo'));
        }
    }

    public function siteInfo()
    {
        $data = SiteInfo::get(['id', 'title', 'content_data', 'type']);
        return view('site_info.site_info_list', compact('data'));
    }

    public function planHistory()
    {
        $data = PlanPurchase::paginate(10);
        
        return view('payment_history.plan_history', compact('data'));
    }

    public function planCoupons($id)
    {
        $data = PlanCoupon::where('plan_purchase', $id)->with('userPurchase')->paginate(10);
        return view('payment_history.plan_coupons', compact('data'));
    }

    protected function otherHistory(){
        $data = UserPurchase::where('item_type','!=',2)->paginate(10);
        return view('payment_history.course_subject_history', compact('data'));
    }
     public function addPlanImageSlider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required| mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return redirect('plan-images')->with('error', implode(',', $validator->errors()->all()));
        } else {
             $filename2 = 'uploads/slider/' . time() . '.' . $request->image->getClientOriginalExtension();
            
             $image =imageResizeAndSave($request->file('image'), 500, 275, $filename2);

            //create image
            if (DB::table('plan_slider_images')->insert(['image' => $image]))
                return redirect('plan-images-list')->with('success', Config('constant.msg.record_insert_1'));
            else
                return redirect('plan-images')->with('error', Config('constant.msg.record_insert_0'));


        }
    }
    public function planImageSlider()
    {
        return view('plan_slider.plan_sider');
    }
    public function planImageSliderlist()
    {
        $data = DB::table('plan_slider_images')->where('deleted_at',null)->get()->toArray();
        return view('plan_slider.plan_sider_list', compact('data'));
    }

    public function edit_planImageSlider($id)
    {
        $data['document'] = DB::table('plan_slider_images')->where('id', $id)->first();
        return view('plan_slider.plan_sider', $data);
    }

    public function update_planImageSlider($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required| mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return redirect('edit-home-images/' . $id)->with('error', implode(',', $validator->errors()->all()));
        } else {
            $filename2 = 'uploads/slider/' . time() . '.' . $request->image->getClientOriginalExtension();
            
            $mobile_images = imageResizeAndSave($request->file('image'), 500, 275, $filename2);


            if (DB::table('plan_slider_images')->where('id', $id)->update(['image' => $mobile_images]))
                return redirect('plan-images-list')->with('success', Config('constant.msg.record_updated_1'));
            else
                return redirect('plan-images')->with('error', Config('constant.msg.record_updated_0'));
        }
    }
    public function addBook(){
        $order=Book::orderBy('orderNo', 'desc')->first(['orderNo'])->orderNo+1;
     return view('book.add-book',compact('order'));   
    }
    public function insertBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        preg_match_all('/<img[^>]+>/i',$request->text_content, $result); 
        $img = array();
        foreach( $result[0] as $img_tag)
        {
            preg_match_all('/(src)=("[^"]*")/i',$img_tag, $data);
           $data[2][0]=str_replace('"',"",$data[2][0]);
           $request->text_content=str_replace('src="'.$data[2][0].'"','src="http://shana.co.in'.$data[2][0].'"',$request->text_content);
       }
        if (isset($request->preview)) {
                $filename = 'uploads/preview_image/' . time() . '.' . $request->preview->getClientOriginalExtension();
                $preview = imageResizeAndSave($request->file('preview'), 800, 450, $filename);
            } else {
                $preview = ('uploads/preview_image/dummy.png');
        }
        $filename='';         
        switch ($request->doc_type[0]) {
            case 'pdf':
                $filename = 'uploads/pdf/' . time() . '.' . $request->pdf_file->getClientOriginalExtension();
                $pdf = $request->file('pdf_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($pdf),'public');
                $filename = $filename;
                break; 
            case 'word':
                $filename = 'uploads/word/' . time() . '.' . $request->word_file->getClientOriginalExtension();
                $word = $request->file('word_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($word),'public');
                $filename = $filename;
                break;
            case 'text':
            $filename=$request->text_content;
            break;
        }
        if($request->order==null){
            $request->order=Book::latest()->first(['orderNo'])->orderNo+1;
        }
        $data=['title'=>$request->title,'description'=>$request->description,'doc_type'=>$request->doc_type[0],'preview'=>$preview,'file_detail'=>$filename,'orderNo'=>$request->order,'author_name'=>$request->author_name];
        
        $book=Book::insert($data);

        return redirect('book-list');
    }
    public function bookList(){
        $data['book']=Book::all();
         $data['bookDelete'] = Book::onlyTrashed()->get();
         
        return view('book.book-list',$data);
    }
    public function editBook($id){
        $data['book']=Book::where('id',$id)->first();
        return view('book.add-book',$data);
    }
    public function updateBook(Request $request)
    {
        preg_match_all('/<img[^>]+>/i',$request->text_content, $result); 
        $img = array();
        foreach( $result[0] as $img_tag)
        {
          
            if(strpos($img_tag, 'src="http://shana.co.in')==false){
                preg_match_all('/(src)=("[^"]*")/i',$img_tag, $data);
               $data[2][0]=str_replace('"',"",$data[2][0]);
               $request->text_content=str_replace('src="'.$data[2][0].'"','src="http://shana.co.in'.$data[2][0].'"',$request->text_content);
            }
        }
        $data=['title'=>$request->title,'description'=>$request->description,'doc_type'=>$request->doc_type[0],'orderNo'=>$request->order,'author_name'=>$request->author_name];
         if (isset($request->preview)) {
                $filename = 'uploads/preview_image/' . time() . '.' . $request->preview->getClientOriginalExtension();
                $preview = imageResizeAndSave($request->file('preview'), 800, 450, $filename);
                $data['preview']=$preview;
            } 

        switch ($request->doc_type[0]) {
            case 'pdf':
            if(isset($request->pdf_file)){
                $filename = 'uploads/pdf/' . time() . '.' . $request->pdf_file->getClientOriginalExtension();
                $pdf = $request->file('pdf_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($pdf), 'public');
                $filename = ($filename);
                $data['file_detail']=$filename;
                }
                break; 
            case 'word':
             if(isset($request->pdf_file)){
                $filename = 'uploads/word/' . time() . '.' . $request->word_file->getClientOriginalExtension();
                $word = $request->file('word_file');
                $t = Storage::disk('spaces')->put($filename, file_get_contents($word), 'public');
                $filename = ($filename);
                $data['file_detail']=$filename;
            }
                break;
            case 'text':
            $data['file_detail']=$request->text_content;
            break;
        }
        
        $book=Book::where('id',$request->id)->update($data);

        return redirect('book-list');
    }
    public function delete_book($id)
    {
        $topic = Book::where('id', $id)->update(['status' => 0]);
        return json_encode($topic);
    }

    public function statusChangeBook($id)
    {
        $book = Book::where('id', $id)->first();
        if ($book->status == 0)
            Book::where('id', $id)->update(['status' => 1]);
        else
            Book::where('id', $id)->update(['status' => 0]);
        return json_encode($book);
    }

    public function soft_deleteBook($id)
    {
        $book = Book::find($id);
        $book->status = 0;
        $book->save();
        $book->delete();
        return json_encode($book);
    }

    public function soft_restoreBook($id)
    {
        $topic = Book::where('id', $id)->restore();
        return json_encode($topic);
    }

    public function multiple_book_delete(Request $request)
    {
        if (!empty($request->id)) {
            $topic = Book::whereIn('id', $request->id)->update(['status' => 0]);
            Book::whereIn('id', $request->id)->delete();
            return redirect('book-list')->with('success', Config('constant.msg.record_delete_1'));
        } else
            return redirect('book-list');
    }

    public function multiple_book_restore(Request $request)
    {
        if (!empty($request->id)) {
            Book::whereIn('id', $request->id)->restore();
            $topic = Book::whereIn('id', $request->id)->update(['status' => 0]);

            return redirect('book-list')->with('success', Config('constant.msg.record_restore_1'));
        } else
            return redirect('book-list');
    }
    public function checkBookOrder($val,$id){
       if($id>0){
        $book=Book::where('orderNo',$val)->where('id','!=',$id)->get(['id']);
        }
        else{
         $book=Book::where('orderNo',$val)->get(['id']);   
        }  
        if(count($book)>0){
            return response()->json(['status'=>0,'msg'=>'Order Number Already Use']);
        }
        else{
            return response()->json(['status'=>1,'msg'=>'']);   
        }
    }
    public function generateCoupon(){
        for ($i = 0; $i < 1; $i++) {
            $salt = $this->core->planReferalGenerate();
            $refCode = 'Shana' . $this->core->planReferalGenerate();
            $planCoupons = PlanCoupon::create([
                'plan_purchase' => 10,
                'ref_code' => $refCode,
                'salt' => $salt,
                'hash' => password_hash($salt . $refCode . env('APP_KEY'), PASSWORD_DEFAULT),
            ]);
            $planCode[]=$planCoupons->ref_code;
        }
       return view('coupon',compact('planCode'));
    }
}