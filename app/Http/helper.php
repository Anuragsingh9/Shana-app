<?php
use App\Subject;
use App\UserPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

function datetimeFormat($datetime)
{
    return date('d M Y h:i:s', strtotime($datetime));
}
function user_type(){
    $array=['Student'=>'Student',
            'Teacher'=>'Teacher',
            'Parents'=>'Parents',
            'Competitor'=>'Competitor'
    ];
    return $array;
}
function fileUpload($file,$path,$height=null,$width=null)
{
    $filename='';
    File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
    if($file!=''){
        $filename=md5($file->getClientOriginalName()).time().'.'.$file->getClientOriginalExtension();
        if($height>0 && $width>0){
            $img = Image::make($file->getRealPath());
            $img->resize($height, $width, function ($constraint) {
                  $constraint->aspectRatio();
            })->save($path."/".$filename);
        }
        else{
            $file->move($path, $filename);  
        }
    }
    return $filename;
}
function getImgSrc($path,$img_name=null)
{
     if(file_exists(public_path($path.$img_name)) && $img_name !='')
          return url($path.$img_name);
     else 
          return url($path.'/dummy.jpg');              
}
function getprofile($path,$img_name=null)
{
     if(file_exists(public_path($path.$img_name)) && $img_name !='')
          return url($path.$img_name);
     else 
          return url('images/dummy.jpg');              
}

/*
    * check that string start with which alphabates
    * */
function startsWiths($haystack, $needle)
{

    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/*
    * check that string start with which alphabates
    * */
function startsWithsNum($haystack, $needle)
{

    $length = strlen($needle);
    return (substr($haystack, 0, $length) == $needle);
}

function imageResizeAndSave($file,$height,$width,$filename){
    if($height>0 && $width>0){
       // dump($file,$height,$width,$filename);
        $img = Image::make($file->getRealPath());
        $img->resize($height, $width, function ($constraint) {
            $constraint->aspectRatio();
        });

        //detach method is the key! Hours to find it... :/
        $resource = $img->stream()->detach();

        $path = Storage::disk('spaces')->put(
             strtolower($filename),
            $resource, 'public'
        );
        return $preview_image = strtolower($filename);

    }
}

function countHomeShowCourse(){
    $limit=Config('constant.homeCourse.limit');
    $course=\App\Course::where('show_home',1)->count();
    if($limit>$course){
        return true;
    }else{
        return false;
    }
}

function getS3Parameter($file_path, $type = null, $file_name = null) {
    /*
      $file_path = full file url with folder name
      `   $type =>  1: get file download url, 2: get file view url
      https://s3.ap-south-1.amazonaws.com/ops.sharabh.org/
     */
    $url = '';
    $config['Bucket'] = env('AWS_BUCKET');
    $config['Key'] = $file_path;

    $s3 = Storage::disk('spaces');
    if ($s3->exists($file_path)) {
        if ($type == 1) {
            if ($file_name != null) {
                $config['ResponseContentDisposition'] = 'attachment;filename=' . $file_name;
            } else {
                $config['ResponseContentDisposition'] = 'attachment';
            }
        }
        $command = $s3->getDriver()->getAdapter()->getClient()->getCommand('GetObject', $config);
        $requestData = $s3->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+5 minutes');
        $url = $requestData->getUri();
        return (string) $url;
    }
    return null;
}
function aws_s3_link($access_key='FHY6TP2QRWEU745NVL5A', $secret_key='OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ', $bucket='shana-app',
                     $canonical_uri='/uploads/course/1515159848.png', $expires = 300, $region = 'ams3', $extra_headers = array()) {
    $encoded_uri = str_replace('%2F', '/', rawurlencode($canonical_uri));

    $signed_headers = array();
    foreach ($extra_headers as $key => $value) {
        $signed_headers[strtolower($key)] = $value;
    }
    if (!array_key_exists('host', $signed_headers)) {
        $signed_headers['host'] = ($region == 'ams3') ? "$bucket.ams3.digitaloceanspaces.com" : "$bucket.ams3.digitaloceanspaces.com";
    }
    ksort($signed_headers);

    $header_string = '';
    foreach ($signed_headers as $key => $value) {
        $header_string .= $key . ':' . trim($value) . "\n";
    }
    $signed_headers_string = implode(';', array_keys($signed_headers));

    $timestamp = time();
    $date_text = gmdate('Ymd', $timestamp);
    $time_text = $date_text . 'T000000Z';

    $algorithm = 'AWS4-HMAC-SHA256';
    $scope = "$date_text/$region/s3/aws4_request";

    $x_amz_params = array(
        'X-Amz-Algorithm' => $algorithm,
        'X-Amz-Credential' => $access_key . '/' . $scope,
        'X-Amz-Date' => $time_text,
        'X-Amz-SignedHeaders' => $signed_headers_string
    );
    if ($expires > 0) $x_amz_params['X-Amz-Expires'] = $expires;
    ksort($x_amz_params);

    $query_string_items = array();
    foreach ($x_amz_params as $key => $value) {
        $query_string_items[] = rawurlencode($key) . '=' . rawurlencode($value);
    }
    $query_string = implode('&', $query_string_items);

    $canonical_request = "GET\n$encoded_uri\n$query_string\n$header_string\n$signed_headers_string\nUNSIGNED-PAYLOAD";
    $string_to_sign = "$algorithm\n$time_text\n$scope\n" . hash('sha256', $canonical_request, false);
    $signing_key = hash_hmac('sha256', 'aws4_request', hash_hmac('sha256', 's3', hash_hmac('sha256', $region, hash_hmac('sha256', $date_text, 'AWS4' . $secret_key, true), true), true), true);
    $signature = hash_hmac('sha256', $string_to_sign, $signing_key);

    $url = 'https://' . $signed_headers['host'] . $encoded_uri . '?' . $query_string . '&X-Amz-Signature=' . $signature;
    return $url;
}



function aws_s3($file_path){
    $stringToSign = "AWS4-HMAC-SHA256". "\n".
        date('c') ."\n" .
        date('Ymd') . "/" .'ams3'. "/" + "s3/aws4_request" . "\n" .hash('sha256', $file_path, false);
    $dateKey = hash_hmac('sha256',"AWS4" .'OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ', date('Ymd'));
$dateRegionKey = hash_hmac('sha256',$dateKey,'ams3' );
$dateRegionServiceKey = hash_hmac('sha256',$dateRegionKey, "s3");
$signingKey = hash_hmac('sha256',$dateRegionServiceKey, "aws4_request");
   return $signature = (hash_hmac('sha256',$signingKey, $stringToSign));
}

/*function generateSignature($a) {

    $awsKeyId = 'FHY6TP2QRWEU745NVL5A';
    $awsSecret = 'OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ';
    $expires = time() + (5*60);
    $httpVerb = "GET";
    $contentMD5 = "";
    $contentType = "image/png";
    $amzHeaders = "";
    $amzResource = "/" . $a;

    $stringToSign = $httpVerb . "\n\n\n" . $expires . "\n" . $amzResource ;
    $signature = urlencode(base64_encode( hash_hmac('sha1', 'OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ', utf8_encode($stringToSign) ) ));

    $url = "https://shana-app.ams3.digitaloceanspaces.com%s?AWSAccessKeyId=%s&Expires=%s&Signature=%s";
    $presignedUrl = sprintf( $url , $amzResource , $awsKeyId , $expires , $signature );

    return $presignedUrl;

}*/


function signRequest($bucket="shana-app.ams3.digitaloceanspaces.com", $request="/uploads/course/1515159945.png", $expiration=0, $s3secret='OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ', $headers = '', $type = 'GET', $content_type = 'default')
{
    if ($expiration == 0 || $expiration == null)
    {
        $expiration = time() + 315576000; // 10 years (never)
    }
    $awsKeyId = 'FHY6TP2QRWEU745NVL5A';
    if (strcmp($content_type, 'default') == 0)
    {
        $content_type = "";
    }

    $headers = trim($headers, '&');


    // but it will only work as this
    $string = "$type\n\n\n$expiration\n/$bucket$request?$headers";

    // this could be a single line of code but left otherwise for readability
    // must be in UTF8 format
    $string = utf8_encode(trim($string));
    // encode to binary hash using sha1. require S3 bucket secret key
    $hash = hash_hmac("sha1",$string, $s3secret,true);
    // sha1 hash must be base64 encoded
    $hash = base64_encode($hash);
    // base64 encoded sha1 hash must be urlencoded
    $signature = urlencode($hash);
    $url = "https://shana-app.ams3.digitaloceanspaces.com%s?AWSAccessKeyId=%s&Expires=%s&Signature=%s";
    $presignedUrl = sprintf( $url , $request , $awsKeyId , $expiration , $signature );
    return $presignedUrl;
}
function TimeToSec($time) {
    $sec = 0;
   if(!empty($time))
    foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
    return $sec;
}

function CreateTemporaryURL($file_name = "", $valid_for = "1 hour") {
    $host='digitaloceanspaces.com';
    $secret_key ='2q+YRbmUTrtxUj/bZ9h0pMBGPLHAqTL5fUbELZ1iA2s';
    $expiry = strtotime("+ ".$valid_for);
    $file_name = rawurlencode($file_name);
    $file = str_replace(array('%2F', '%2B'), array('/', '+'), ltrim($file_name, '/') );
    $objectPathForSignature = '/'. 'shana-app' .'/'. $file_name;
    $stringToSign = implode("\n", $pieces = array('GET', null, null, $expiry, $objectPathForSignature));
    //  $url = 'https://' . env('AWS_BUCKET') . '.'.env('AWS_REGION').'.'.$host.'/' . $file_name;
     $url = 'https://shana-app.ams3.'.$host.'/' . $file_name;
    $blocksize = 64;
    if (strlen($secret_key) > $blocksize) $secret_key = pack('H*', sha1($secret_key));
    $secret_key = str_pad($secret_key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack( 'H*', sha1(($secret_key ^ $opad) . pack( 'H*', sha1(($secret_key ^ $ipad) . $stringToSign))));
    $signature = base64_encode($hmac);
    $queries = http_build_query($pieces = array(
        'AWSAccessKeyId' =>'CMWXRCBET3RI3VQEEKDC',
        'Expires' => $expiry,
        'Signature' => $signature,
    ));
     $url .= "?".$queries;
    return @json_decode(@json_encode($url), true);
}
function ObjReturn($return) {
    $return = @json_decode(@json_encode($return), true);
    $return = $this->AWSTime($return);
    return $return;
}

function cloudUrl($file){
    $host='digitaloceanspaces.com';
    $bucket='shana-app';
    $region='ams3';

   return $url = 'https://' . $bucket . '.'.$region.'.'.$host.'/' . $file;

}
function publicAsset($path){
    return url('public/'.$path);
}
function getUserPlan($id){
    $userPurchase=UserPurchase::where('user_id',$id)->where('item_type',2)->whereDate('end_date','>=',Carbon::now()->format('Y-m-d'))->join('plan_coupons','plan_coupons.user_purchase_id','=','user_purchases.id')->first();
    if($userPurchase != NULL){
        return   $userPurchase->ref_code;
    }else{
        return   'N/A';
    }
}
?>