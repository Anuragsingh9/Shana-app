<?php

namespace App\Http\Controllers;

use App\Plan;
use App\PlanCoupon;
use App\PlanPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Socialite;
use Razorpay\Api\Api;
use Session;
use Illuminate\Support\Facades\Input;
use Redirect;
use Validator;
use App\Course;
use App\Subject;
use App\UserPurchase;
use App\User;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->core = app(\App\Http\Controllers\CoreController::class);
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }


    public function pay()
    {
        $parameters = [

            'tid' => '1233221223322',
            'merchant_id' => env('INDIPAY_MERCHANT_ID'),
            'order_id' => '1232212',
            'amount' => '1200.00',
            'currency' => env('INDIPAY_CURRENCY'),
            'redirect_url' => env('INDIPAY_REDIRECT_URL'),
            'cancel_url' => env('INDIPAY_CANCEL_URL'),
            'language' => env('INDIPAY_LANGUAGE'),


        ];

        return view('ccavenue', compact('parameters'));
    }

    public function payWithRazorpay(Request $request)
    {
        /*    $api = new Api(Config::get('razor.razor_key'), Config::get('razor.razor_secret'));

    // Orders
            $orders = $api->payment->all(['skip'=>0]); // Returns array of order objects
    dd($orders);*/
        $method=$request->method();
        if($method=='POST'){
            $validator = Validator::make($request->all(), [
                'plan_id' => 'required|exists:plans,id',
            ]);

            if ($validator->fails()) {
                return redirect('plans')->with('error', Config('constant.msg.record_insert_0'));
            } else {
                $plan = Plan::find($request->plan_id);
                return view('payWithRazorpay', compact('plan'));
            }
        }else{
            return view('payWithRazorpay');
        }




    }

    public function payment()
    {

        //Input items of form
        $input = Input::all();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

            } catch (\Exception $e) {
                //return  $e->getMessage();
                \Session::put('error', $e->getMessage());
                return redirect()->back();
            }
            //dd($response, $input, json_decode($input['order_detaill'], true));
            //entry for plan payment

            if (isset($input['order_detaill'])) {
                $data = json_decode($input['order_detaill'], true);
                if (isset($data['num_users'])) {
                    //add into plan purchase
                    $planPurchase=PlanPurchase::create(
                        [
                            'purchase_id'=>$response->id,
                            'paid_amount'=>($response->amount/100),
                            'user_id'=>Auth::user()->id,
                            'item_id'=>$data['id'],
                            'item_type'=>2,
                            'discount_amount'=>0,
                            'item_price'=>$data['amount'],
                            'payment_data'=>json_encode($response->toArray()),
                        ]
                    );

                    for ($i = 0; $i < $data['num_users']; $i++) {
                        $salt=$this->core->planReferalGenerate();
                        $refCode='Shana' . $this->core->planReferalGenerate();
                        $planCoupons=PlanCoupon::create([
                            'plan_purchase'=>$planPurchase->id,
                            'ref_code'=>$refCode,
                            'salt' => $salt,
                            'hash' => password_hash($salt.$refCode.env('APP_KEY'), PASSWORD_DEFAULT),
                        ]);

                    }
                }

            }
            // Do something here for store payment details in database...
        }

        \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        return redirect()->back();
    }



    public function subjectCoursePay(Request $request)
    {
        if($request->type=='course')
        {
                $validator = Validator::make($request->all(), [
                    'id' => 'required|exists:courses,id',
                ]);

        if ($validator->fails()) {
            return redirect('plans')->with('error', Config('constant.msg.record_insert_0'));
        } else {
            if($request->wallet>0)
            {
                $request->wallet=Auth::user()->total_ref_amt;
            }
            $course=Course::find($request->id);
            $course->amount2=$course->amount;
               

            if($request->wallet>=$course->amount2)
            {
                $courseData=$course;
                $data = $courseData->toArray();
            User::where('id',Auth::user()->id)->update(['total_ref_amt'=>(Auth::user()->total_ref_amt-$data['amount'])]);
            if($data['ref_type']=='Percentage')
            {
                $ref_amt=(($data['amount2']*$data['ref_amount'])/100)/2;
            }
            else
            {
             $ref_amt=($data['ref_amount'])/2;   
            }
                $user=User::where('self_ref_code',Auth::user()->ref_code)->first();
                if($user!=null)
                {
                    User::where('id',$user->id)->update(['total_ref_amt'=>($user->total_ref_amt+$ref_amt)]);
                    User::where('id',Auth::user()->id)->update(['total_ref_amt'=>($user->total_ref_amt+$ref_amt)]);
                } 
            }
            else
            {
                $course->amount=($course->amount-$request->wallet);

                $course->discount=$request->wallet;

                return view('subjectCoursePay',compact('course'));
            }
        }
    }
        else
    {
     $validator = Validator::make($request->all(), [
            'id' => 'required|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect('plans')->with('error', Config('constant.msg.record_insert_0'));
        } else {
            $subject=Subject::find($request->id);
            $subject->amount2=$subject->amount;
           

            if($request->wallet>=$subject->amount2)
            {
                $data = $subject->toArray();

            User::where('id',Auth::user()->id)->update(['total_ref_amt'=>(Auth::user()->total_ref_amt-$data['amount2'])]);
            if($data['ref_type']=='Percentage')
            {
                $ref_amt=(($item['amount2']*$item['ref_amount'])/100)/2;
            }
            else
            {
             $ref_amt=($item['ref_amount'])/2;   
            }
                $user=User::where('self_ref_code',Auth::user()->ref_code)->first();
                if($user!=null)
                {
                    User::where('id',$user->id)->update(['total_ref_amt'=>($user->total_ref_amt+$ref_amt)]);
                    User::where('id',Auth::user()->id)->update(['total_ref_amt'=>($user->total_ref_amt+$ref_amt)]);
                } 
              return redirect()->back();
            }
            else
            {
                $subject->amount=($subject->amount-$request->wallet);

                $subject->discount=$request->wallet;
                return view('subjectCoursePay',compact('subject'));
            }
           
        }   
    }

    }

    public function subjectCoursePayment()
    {
        //Input items of form
        $input = Input::all();
       //dd($input);
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        // dd($payment);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));

            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }
             if (isset($input['order_detaill'])) {
                $data = json_decode($input['order_detaill'], true);
                //dd($data);
           //dd($response);
             if($data['field']=='Month'){
                    $end_date=(Carbon::now()->addDays(($data['duration']*30)))->format('Y-m-d h:i:s');
                }else{
                    $end_date=(Carbon::now()->addDays(($data['duration']*365)))->format('Y-m-d h:i:s');
                }
            UserPurchase::create([
                'purchase_id'=>$response->id,
                'paid_amount'=>($response->amount/100),
                'user_id'=>Auth::user()->id,
                'end_date'=>$end_date,
                'item_id'=>$data['id'],
                'item_type'=>(isset($data['course'])?0:1),
                'discount_amount'=>$data['discount'],
                'item_price'=>$data['amount2'],
                'payment_data'=>json_encode($response->toArray()),
                ]);
            User::where('id',Auth::user()->id)->update(['total_ref_amt'=>(Auth::user()->total_ref_amt-$data['discount'])]);
            if($data['ref_type']=='Percentage')
            {
                $ref_amt=(($data['amount2']*$data['ref_amount'])/100)/2;
            }
            else
            {
             $ref_amt=($data['ref_amount'])/2;   
            }
                $user=User::where('self_ref_code',Auth::user()->ref_code)->first();
                if($user!=null)
                {
                    User::where('id',$user->id)->update(['total_ref_amt'=>($user->total_ref_amt+$ref_amt)]);
                    User::where('id',Auth::user()->id)->update(['total_ref_amt'=>(Auth::user()->total_ref_amt+$ref_amt)]);
                }

        }
            // Do something here for store payment details in database...
        }

        \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');
        return redirect('/');
    }
}
