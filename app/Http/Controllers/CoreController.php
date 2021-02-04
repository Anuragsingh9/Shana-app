<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Staff, App\Temp, App\ForgetPassword;
use Hash;

class CoreController extends Controller
{
    public function __construct()
    {
    }

    public function checkStaffExists($key)
    {
        $staff = Staff::where('mobile', $key)->first();
        if (!empty($staff))
            return false;
        else
            return true;
    }

    public function staffRegistration($data)
    {
        $dataArray['name'] = $data['name'];
        $dataArray['mobile'] = $data['mobile'];
        $dataArray['password'] = Hash::make($data['password']);
        $dataArray['otp'] = $this->otpGenerate();
        $dataArray['referal_code'] = $this->referalGenerate();
        if (isset($data['referal'])) {
            $dataArray['use_referal'] = $data['referal'];
        }
        $dataArray['type'] = $data['type'];
        //$this->sendSMS($dataArray['mobile'],$dataArray['otp']);
        $id = Temp::insertGetId($dataArray);
        $array = false;
        if ($id) {
            $array['id'] = $id;
            $array['otp'] = $dataArray['otp'];
        }
        return $array;
    }

    public function verifyOtp($mobile, $otp)
    {
        $userArray = Temp::where('mobile', $mobile)->where('otp', $otp)/*->where('verified',false)*/->orderBy('id','desc')->first();
        if (!empty($userArray)) {
            if((Carbon::now()->lte(Carbon::parse($userArray->valid_til)))){
                //Temp::where('id', $userArray->id)->update(['verified'=>true]);
                Temp::where('id', $userArray->id)->delete();
                return true;

            }else{
                Temp::where('id', $userArray->id)->delete();
                return 'expired';
            }
        }else{
            return false;
        }

    }

    public function passwordMatch($user_id, $string)
    {
        $data['user'] = Staff::find($user_id);
        if ($data['user']) {
            if (password_verify($string, $data['user']->password))
                return true;
        }
        return false;
    }

    public function updatePassword($id, $password)
    {
        if (Staff::where('id', $id)->update(['password' => Hash::make($password)]))
            return true;
        return false;
    }

    public function forgetPassword($mobile)
    {
        $dataArray['otp'] = $this->otpGenerate();
        $dataArray['mobile'] = $mobile;
        $sendSms=$this->sendSMS($dataArray['mobile'],$dataArray['otp']);
        if (isset($sendSms->errors)) {
            return false;
        }
        ForgetPassword::where('mobile', $mobile)->delete();
        if (ForgetPassword::insertGetId($dataArray))
            return $dataArray;
        else
            return false;
    }

    public function otpGenerate()
    {
        return substr(str_shuffle('123456789123456789123456789'), 1, 6);
    }

    public function referalGenerate()
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, 6);
    }

    public function planReferalGenerate()
    {
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%^&*(){}'), 1, 5);
    }

    public function sendSMS($mobile, $otp)
    {
      /*  // Account details
        $apiKey = urlencode('y8Qvysw/B8c-33FL4ZkRvDPVF5n9AAF0xVnVg4shDD');

        // Message details
        $sender = urlencode('MGSUNI');
        $message = rawurlencode('
        your request for '.$otp.' submitted successfully. it will be delivered in next 4 working days.
        ');
        $numbers = $mobile;

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.txtlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);*/

        // Account details
        $username = 'contact@sharabhtechnologies.com';
        $hash = 'Password@123';
        // Message details
        $numbers = $mobile;
        $sender = urlencode('SHANAA');
        $message = rawurlencode('Thank you for registering with SHANA. Your
 verification code is '.$otp.'
        ');
        $apiKey = urlencode('y8Qvysw/B8c-33FL4ZkRvDPVF5n9AAF0xVnVg4shDD');
        //$numbers = implode(',', $numbers);
        // Prepare data for POST request
        //$data = array('username' => $username, 'password' => $hash, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        // Send the POST request with cURL
        $ch = curl_init('http://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);

    }
}
