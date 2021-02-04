<html>
<head>
    <title> Custom Form Kit </title>
</head>
<body>
<center>

    <?php

    function LocalEncrypt($plainText,$key)
    {
        $secretKey = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
        $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $plainPad = pkcs5_pad($plainText, $blockSize);
        if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1)
        {
            $encryptedText = mcrypt_generic($openMode, $plainPad);
            mcrypt_generic_deinit($openMode);

        }
        return bin2hex($encryptedText);
    }


    //*********** Padding Function *********************

    function pkcs5_pad ($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    //********** Hexadecimal to Binary function for php 4.0 version ********

    function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString="";
        $count=0;
        while($count<$length)
        {
            $subString =substr($hexString,$count,2);
            $packedString = pack("H*",$subString);
            if ($count==0)
            {
                $binString=$packedString;
            }

            else
            {
                $binString.=$packedString;
            }

            $count+=2;
        }
        return $binString;
    }
    ?>
    <?php

    error_reporting(0);

    $merchant_data='83638';
    $working_key='1F87364AB011007C6A892587DCE57C8E';//Shared by CCAVENUES
    $access_code='AVHX75FA68BN00XHNB';//Shared by CCAVENUES

    foreach ($parameters as $key => $value){
        $merchant_data.=$key.'='.urlencode($value).'&';
    }

    $encrypted_data=LocalEncrypt($merchant_data,$working_key); // Method for encrypting the data.

    ?>
    <form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
        <?php
        echo "<input type=hidden name=encRequest value=$encrypted_data>";
        echo "<input type=hidden name=access_code value=$access_code>";
        ?>
    </form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

