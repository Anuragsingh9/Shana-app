<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
<style type="text/css">
h1{text-align:center}
.form-wrp {width: 350px;margin: 30px auto 0px;background: #02cce6;padding: 20px;}
#results span{padding: 20px;background: #00bfd8;margin: 5px 0;text-align: center;box-shadow: inset 0px 0px 3px 0px rgba(0, 0, 0, 0.06);border: 1px solid #00b4cc;display: block;}
</style>
</head>

<body>

<?php
$access_key         = "FHY6TP2QRWEU745NVL5A"; //User Access Key
$secret_key         = "OJ0usdHoBsAfpQCGfGRHtV8mrydPpIWsqvpcYe4GuPQ"; //secret key
$my_bucket          = "shana-app"; //bucket name
$region				= "ams3"; //bucket region
$allowd_file_size	= "1048579, 10485760"; //This example allows a file size from 1 to 10 MiB

//dates
$short_date         = gmdate('Ymd'); //short date
$iso_date           = gmdate("Ymd\THis\Z"); //iso format date
$expiration_date    = gmdate('Y-m-d\TG:i:s\Z', strtotime('+1 hours')); //policy expiration 1 hour from now
$presigned_url_expiry    = 3600; //Presigned URL validity expiration time (3600 = 1 hour)

$policy = array(
'expiration' => gmdate('Y-m-d\TG:i:s\Z', strtotime('+6 hours')),
'conditions' => array(
	array('bucket' => $my_bucket),  
	array('acl' => 'public-read'),  
	array('starts-with', '$key', ''),  
	array('starts-with', '$Content-Type', ''),  
	array('success_action_status' => '201'),  
	array('x-amz-credential' => implode('/', array($access_key, $short_date, $region, 's3', 'aws4_request'))),  
	array('x-amz-algorithm' => 'AWS4-HMAC-SHA256'),  
	array('x-amz-date' => $iso_date),  
	array('x-amz-expires' => ''.$presigned_url_expiry.''),  
));

$policybase64 = base64_encode(json_encode($policy));	

$kDate = hash_hmac('sha256', $short_date, 'AWS4' . $secret_key, true);
$kRegion = hash_hmac('sha256', $region, $kDate, true);
$kService = hash_hmac('sha256', "s3", $kRegion, true);
$kSigning = hash_hmac('sha256', "aws4_request", $kService, true);
$signature = hash_hmac('sha256', $policybase64 , $kSigning);
?>
<h1>Ajax Upload to Amazon AWS S3</h1>
<div class="form-wrp">
<div id="results"><!-- server response here --></div>
<form action="https://<?=$my_bucket?>.<?=$region?>.digitaloceanspaces.com" method="post" id="aws_upload_form"  enctype="multipart/form-data">
<input type="hidden" name="acl" value="public-read">
<input type="hidden" name="success_action_status" value="201">
<input type="hidden" name="policy" value="<?=$policybase64?>">
<input type="hidden" name="X-amz-credential" value="<?=$access_key?>/<?=$short_date?>/<?=$region?>/s3/aws4_request">
<input type="hidden" name="X-amz-algorithm" value="AWS4-HMAC-SHA256">
<input type="hidden" name="X-amz-date" value="<?=$iso_date?>">
<input type="hidden" name="X-amz-expires" value="<?=$presigned_url_expiry?>">
<input type="hidden" name="X-amz-signature" value="<?=$signature?>">
<input type="hidden" name="key" value="">
<input type="hidden" name="Content-Type" value="">
<input type="file" name="file" />
<input type="submit" value="Upload File" />
</form>
</div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>

$("#aws_upload_form").submit(function(e) {
    e.preventDefault();
	the_file = this.elements['file'].files[0];
	var filename = Date.now() + '.' + the_file.name.split('.').pop();
	$(this).find("input[name=key]").val(filename);
	$(this).find("input[name=Content-Type]").val(the_file.type);
	

    var post_url = $(this).attr("action"); //get form action url
    var form_data = new FormData(this); //Creates new FormData object
    $.ajax({
        url : post_url,
        type: 'post',
		datatype: 'xml',
        data : form_data,
		contentType: false,
        processData:false,
		xhr: function(){
			var xhr = $.ajaxSettings.xhr();
			if (xhr.upload){
				var progressbar = $("<div>", { style: "background:#607D8B;height:10px;margin:10px 0;" }).appendTo("#results"); //create progressbar
				xhr.upload.addEventListener('progress', function(event){
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
							percent = Math.ceil(position / total * 100);
							progressbar.css("width", + percent +"%");
						}
				}, true);
			}
			return xhr;
		}
    }).done(function(response){
		var url = $(response).find("Location").text(); //get file location
		var the_file_name = $(response).find("Key").text(); //get uploaded file name
        $("#results").html("<span>File has been uploaded, Here's your file <a href=" + url + ">" + the_file_name + "</a></span>"); //response
    }).error(function() {
      console.log( arguments);
    });
});

</script>
</body>
</html>
