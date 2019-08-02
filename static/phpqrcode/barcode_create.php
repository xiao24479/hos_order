<?php
//echo  urldecode('http%3A%2F%2Fwei.tzyy120.com%2Fwebchatmobile%2Findex.php%3Fc%3Dweixinlogin%26m%3Dindex%26id%3D13');exit;
if(isset($_GET['url'])){
	include "../phpqrcode/phpqrcode.php"; //引入PHP QR库文件
	$errorCorrectionLevel = "L";
	$matrixPointSize = "4";
	//var_dump(base64_decode($_GET['url']));exit;
	QRcode::png(base64_decode($_GET['url']), false, $errorCorrectionLevel, $matrixPointSize,1);
}
