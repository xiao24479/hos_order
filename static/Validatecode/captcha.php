<?php
session_start(); 
require dirname(dirname(dirname(__FILE__))).'/static/Validatecode/Validatecode.php';  //�Ȱ������������ʵ��·������ʵ����������޸ġ�
$_vc = new Validatecode();  //ʵ����һ������ 
$_SESSION['authnum_session'] = $_vc->getCode();//��֤�뱣�浽SESSION�� 
$_vc->doimg(); //����ͼƬ 

?>