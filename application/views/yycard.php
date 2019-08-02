<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

<meta charset="utf-8" />

<title><?php echo $admin['name'] . '-' . $title; ?></title>

<meta content="width=device-width, initial-scale=1.0" name="viewport" />

<meta content="" name="description" />

<meta content="" name="author" />



    <style type="text/css">

	.yueye ul,.yueye ulli{margin:0px;padding:0px;list-style-type:none;}

	.yueye ul.yueyeul{width:100%;height:auto;overflow:hidden;margin:auto;}

	.yueye input{border:0px;width:100%;height:100%;margin:0px;padding:0px;}

	.yueye ul li{float:left}

	</style>



</head>



<body class="fixed-top">

<?php if(isset($patient)){

	$action = explode(",",$_COOKIE['l_admin_action']);

	if($_COOKIE['l_admin_action'] != 'all' && !in_array('179',$action)){

		$patient['pat_phone']  =  $patient['pat_phone'][0].$patient['pat_phone'][1].$patient['pat_phone'][2].'*****';

		$patient['pat_phone1']  =  $patient['pat_phone1'][0].$patient['pat_phone1'][1].$patient['pat_phone1'][2].'*****';

	}

	?>



     <?php if(strcmp($order['hos_id'],6) ==0 ){?>

     	<div class="yueye" style="background:url(static/img/dongfangyuyue.png?v=201905071451) no-repeat center;width:663px;height:550px;margin:auto">

	 <?php }else if(strcmp($order['hos_id'],37) ==0 ){?>
			<?php if(strcmp($order['keshi_id'],173) ==0){?><!--温州妇科-->
				<div class="yueye" style="background:url(static/img/wenzhouyuyuefk.png) no-repeat center;width:640px;height:372px;margin:auto">
			<?php }else{?>
				<div class="yueye" style="background:url(static/img/wenzhouyuyue.png) no-repeat center;width:640px;height:372px;margin:auto">
			<?php }?>

	 <?php }else if(strcmp($order['hos_id'],42) ==0 ){?>

     	<div class="yueye" style="background:url(static/img/jundayuyue.png) no-repeat center;width:640px;height:372px;margin:auto">

	 <?php }else if(strcmp($order['hos_id'],3) ==0 ){?>

     	<div class="yueye" style="background:url(static/img/taizhouyuyue.png?v=201905071531) no-repeat center;width:663px;height:550px;margin:auto">

	 <?php }else if(strcmp($order['hos_id'],39) ==0 ){?>

     	<div class="yueye" style="background:url(static/img/yuyue.png) no-repeat center;width:640px;height:372px;margin:auto">

	 <?php }else if(strcmp($order['hos_id'],54) ==0 ){?>
     	<div class="yueye" style="background:url(static/img/ningbo.png) no-repeat center;width:640px;height:372px;margin:auto">
	 <?php }?>



		   <ul class="yueyeul">



		     <li style="width:100%;height:160px;margin:auto;"></li>

				<li style="width:90px;height:30px;margin-left:80px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo $patient['pat_name'];?>" /></li>

				<li style="width:95px;height:30px;margin-left:95px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo $patient['pat_age'];?>" /></li>

				<li style="width:145px;height:30px;margin-left:110px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo date("m月d号",$order['order_time']);?>" /></li>

			</ul>

			<ul style="margin-top:17px">

				<li style="width:90px;height:30px;margin-left:80px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo $order['keshi_name'];?>" /></li>

				<li style="width:95px;height:30px;margin-left:95px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo $order['order_no'];?>" /></li>

				<li style="width:145px;height:30px;margin-left:110px;border-bottom:1px solid #0388c6">

					<input type="text" name="" value="<?php echo $patient['pat_phone'];?>" /></li>

			</ul>

		</div>

<?php }?>



</body>

</html>