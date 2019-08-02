<?php
session_start(); 
require dirname(dirname(dirname(__FILE__))).'/static/Validatecode/Validatecode.php';  //先把类包含进来，实际路径根据实际情况进行修改。
$_vc = new Validatecode();  //实例化一个对象 
$_SESSION['authnum_session'] = $_vc->getCode();//验证码保存到SESSION中 
$_vc->doimg(); //生成图片 

?>