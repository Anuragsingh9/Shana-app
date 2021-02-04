<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Document;
use Validator;

class PushNotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello Word')
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['data' => ['yash' => 'no', 'sourabh' => 'pancharia'], 'click_action' => 'v1', 'sub_text' => 'its sub text']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        //$token = "cl5ZWv1TZ4A:APA91bEV-P6MbDhULTvy6OObzOZgM5DkSfq2R5nihn6iAqJVqF2C_uRk8CK9IlRZQXO_gdwKOEFwiVpx1cQP492ONqOH015v8OrGgxHrqNihXyG80YTGSV99ORFCglFgtqU0ISlZYg02";
        $token = ["cJSfad-4z1M:APA91bGC1E_-CMyJSlwGRh1bgCEokTCNRpNjP4VUx8Md7dsv3ZzkCLsyetliQH3VzTliqmN9Flr9HQdJGftZAjoHyNEZCsbqxGginuGyRrI2XJNiQ1AEq9oSvcgp-6okd-0VedjYKBfC"];

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        dd($downstreamResponse->tokensToModify(), $downstreamResponse, $downstreamResponse->numberSuccess());
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
        //return view('home');
    }

    protected function multipleNotification()
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
        $tokens = MYDATABASE::pluck('fcm_token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

// return Array (key:token, value:errror) - in production you should remove from your database the tokens present in this array
        $downstreamResponse->tokensWithError();
    }


    protected function sendNotificationMarketing($title, $body, $tokens, $url = '', $addData = [])
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default')->setColor('purple');

        $uId = rand(15, 15);
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(
            ['id' => $uId, 'type' => 'Marketing', 'channel'=>'shana_notif_channel','custom_notification' => [
                'type' => 'Marketing',
                'id' => $uId,
                'title' => $title,
                'show_in_foreground' => true,
                'lights' => true,
                'wake_screen' => true,
                'sound' => 'default',
                'priority' => 'high',
                'body' => $body,
                'alert' => true,
                'ongoing' => true,
                'icon' => 'ic_launcher',
                'color' => 'purple',
                'channel'=>'shana_notif_channel'
                //'picture' => 'http://shana.co.in/public/css/images/background-mobile.jpg',
            ]]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        if ($downstreamResponse->numberSuccess() >= 1)
            return true;
        else
            return false;

        /*
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();*/
    }

    public function create()
    {
        $user = User::where('id', '!=', 1)->get(['id', 'name']);
        return view('notification.add_notification', compact('user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userChecked' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('add-push-notification')->with('error' , Config('constant.msg.record_insert_0'));
        } else {
        $users = User::where('device_token', '!=', null)->whereIn('id', $request->userChecked)->pluck('device_token')->toArray();
        
        if (count($users) > 0) {
            $send = $this->sendNotificationMarketing($request->title, $request->message, $users);

            if ($send) {
                foreach ($request->userChecked as $user) {
                    $notification[] = [
                        'sender_id' => Auth::user()->id,
                        'receiver_id' => $user,
                        'title' => $request->title,
                        'message' => $request->message,
                        'event' => 'Marketing',
                    ];
                }
                Notification::insert($notification);
                return redirect('add-push-notification')->with('success', Config('constant.msg.record_insert_1'));
            } else {
                return redirect('add-push-notification')->with('error', Config('constant.msg.record_insert_0'));
            }

        } else {
            return redirect('add-push-notification')->with('error', Config('constant.msg.record_insert_0'));
        }
    }

    }

    public function sendNotificationOnDocumentAdd($document_id)
    {
        $document = Document::where(['status' => 1, 'id' => $document_id])->with('course', 'media')->first();
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New Document Added.');
        $notificationBuilder->setBody('New Document added ' . $document->title)
            ->setSound('default')->setColor('purple');

        //getting media type
        foreach ($document->media as $items) {
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


        $dataBuilder = new PayloadDataBuilder();
        $uId = rand(15, 15);
        $dataBuilder->addData(['id' => $uId, 'type' => 'Doc', 'doc_id' => $document_id,
            'course_name' => $document->course->course,
            'channel'=>'shana_notif_channel',
            'doc_type' => $type, 'custom_notification' => [
                'type' => 'Doc',
                'id' => $uId,
                'title' => $document->title,
                'show_in_foreground' => true,
                'lights' => true,
                'wake_screen' => true,
                'sound' => 'default',
                'priority' => 'high',
                'body' => "New Document added .$document->title",
                'alert' => true,
                'ongoing' => true,
                'icon' => 'ic_launcher',
                'color' => 'purple',
                'doc_id' => $document_id,
                'course_name' => $document->course->course,
                'doc_type' => $type,
                'channel'=>'shana_notif_channel'
            ]
        ]);
        /*['data'=>['id'=>$document_id,'title'=>$document->title,'course_name'=>$document->course->course,'doc_type'=>$type],'type'=>'Doc','sub_text'=>'','picture'=>$document->preview_image]*/
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
        $tokens = User::whereNotNull('device_token')->pluck('device_token')->toArray();
        //      $tokens = ["cJSfad-4z1M:APA91bGC1E_-CMyJSlwGRh1bgCEokTCNRpNjP4VUx8Md7dsv3ZzkCLsyetliQH3VzTliqmN9Flr9HQdJGftZAjoHyNEZCsbqxGginuGyRrI2XJNiQ1AEq9oSvcgp-6okd-0VedjYKBfC"];
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        if ($downstreamResponse->numberSuccess() >= 1)
            return true;
        else
            return false;
    }

    public function list()
    {
        $notification = Notification::with('user')->where('event','Marketing')->orderBy('id','desc')->paginate(15);

        return view('notification.notification_list', compact('notification'));
    }
}
