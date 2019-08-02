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

<link href="static/css/bootstrap.min.css" rel="stylesheet" />

<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />

<link href="static/css/font-awesome.css" rel="stylesheet" />

<link href="static/css/style.css" rel="stylesheet" />

<link href="static/css/style-responsive.css" rel="stylesheet" />

<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />

<link href="static/js/datepicker/css/datepicker.css" rel="stylesheet" />

<link href="static/css/clockface.css" rel="stylesheet" />

 <script type="text/javascript"    >

        function open_window(){



            window.open('?c=order&m=siwei_show_window','newwindow','width=1000,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no')



        }



        </script>

<style>

.a{ position:absolute; top:0; left:0; height:220px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}

.b{ position:absolute; top:250px; left:0; height:40px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}

<?php if(isset($order_data['data_time'])):?>

.b{ height:70px;}

<?php endif; ?>

.remark{height:auto; width:auto; overflow:hidden; position:relative; top:0px; left:0px;}

.black_overlay{

display: none;

position: absolute;

top: 0%;

left: 0%;

width: 100%;

height: 100%;

background-color: black;

z-index:1001;

-moz-opacity: 0.8;

opacity:.80;

filter: alpha(opacity=80);

}

.white_content {

display: none;

position: absolute;

top: 10%;

left: 10%;

width: 80%;

height: 90%;

border: 16px solid #00a186;

background-color: white;

z-index:1002;

overflow: auto;

}

.widget-title{



  background-color: #00a186;



}

</style>

</head>



<body class="fixed-top">

<?php echo $top; ?>

<div id="container" class="row-fluid">

<?php echo $sider_menu; ?>



<div id="main-content">



         <!-- BEGIN PAGE CONTAINER-->

         <div class="container-fluid">

            <!-- BEGIN PAGE HEADER-->

            <?php echo $themes_color_select; ?>

            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->



	  <div class="row-fluid">



		<div class="span12">

		  <div class="widget green" style="border-color:#e7e7e7;">

<!--                      <div class="widget-title" style="background-color:#00a186;">

					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>

					<span class="tools">

						<a href="javascript:;" class="icon-chevron-down"></a>

						<a href="javascript:;" class="icon-remove"></a>

					</span>

					</div>-->



					<div class="widget-body">

						<form onSubmit="return chkForm();" action="?c=order&m=order_update_liulian" method="post" class="form-horizontal" style="position:relative;">

						<div class="row-fluid order_from">

						<div class="control-group">

							<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
							<div class="controls" style="position:relative;">
							   <?php if(isset($info)){ echo $info['order_no'];?>
						            <input type="hidden" value="<?php echo $info['order_no'];?>" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" />&nbsp;
								<?php }else{?>
									 <input type="text" value="" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" /> <button type="button" id="get_order_no" class="btn btn-success" style="background-color:#00a186;"> <?php echo $this->lang->line('get_order_no'); ?> </button>&nbsp;
						            <img id="img_suo" style="position: absolute;z-index: 1000;left: 30%;display: none;top:30px;" src="">
						            <input type="hidden" value="" name="card" />
								<?php }?>
							</div>

						</div>

						</div>

						<div class="row-fluid">

						<div class="span6 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('order_keshi');?></label>

								<div class="controls">

								   <select name="hos_id" id="hos_id">

								   <option value="0"><?php echo $this->lang->line('hospital_select');?></option>
								     <?php if(isset($info)){
								     	foreach($hospital as $val){ ?>
								   			<option value="<?php echo $val['hos_id'];?>"<?php if($val['hos_id'] == $info['hos_id']){ ?> selected="selected"<?php }?>><?php echo $val['hos_name'];?></option>
								   		  <?php } ?>
								     <?php }else{
								     	   foreach($hospital as $val){ ?>
								   			<option value="<?php echo $val['hos_id'];?>"<?php if($val['hos_id'] == $l_hos_id){ ?> selected="selected"<?php }?>><?php echo $val['hos_name'];?></option>
								   	<?php }}?>
								   </select>

								  <select name="keshi_id" id="keshi_id">
								     <option value="0"><?php echo $this->lang->line('keshi_select');?></option>
								     <?php if(isset($info)){
								     	foreach($keshi_data as $val){ ?>
								   			<option  value="<?php echo $val['keshi_id'];?>" <?php if($val['keshi_id'] == $info['keshi_id']){ ?> selected="selected"<?php }?>><?php echo $val['keshi_name'];?></option>
								   		  <?php } ?>
								     <?php }?>

								   </select>
								</div>

							</div>

						</div>

						<div class="span5 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('type_name');?></label>

								<div class="controls">

								   <select name="type_id">
								    <?php foreach($type_list as $val){

								   	if($val['type_id'] == 116){?>

								   		<option value="<?php echo $val['type_id'];?>"><?php echo $val['type_name'];?></option>

								    <?php 	break;} ?>

								   <?php } ?>

								   </select>

								</div>

							</div>

						</div>

						</div>

						<div class="row-fluid">

						<div class="span6 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('patient_jibing');?></label>

								<div class="controls">

								   <select name="jibing_parent_id" id="jibing_parent_id">

								     <option value="0"><?php echo $this->lang->line('jb_parent_select');?></option>
								   <?php if(isset($info)){
								   	     foreach($jb1_data as $val){ 	?>
								   		<option value="<?php echo $val['jb_id'];?>"  <?php if($val['jb_id'] == $info['jb_parent_id']){ ?> selected="selected"<?php }?>><?php echo $val['jb_name'];?></option>
								   <?php }}?>
								   </select>


								   <select name="jibing_id" id="jibing_id">



								   <option value="0"><?php echo $this->lang->line('jb_child_select');?></option>
								    <?php if(isset($info)){
								    	foreach($jb2_data as $val){ ?>
								   		<option value="<?php echo $val['jb_id'];?>"  <?php if($val['jb_id'] == $info['jb_id']){ ?> selected="selected"<?php }?>><?php echo $val['jb_name'];?></option>
								   <?php }}?>
								   </select>

								</div>

							</div>

						</div>

						<div class="span5 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('patient_ask');?></label>

								<div class="controls">

								   <select name="admin_id" id="admin_id">

								   <option value="0"><?php echo $this->lang->line('please_select');?></option>

								   <?php 	if(isset($info)){
								   	foreach($asker_list as $val){ ?>
								   	 <option value="<?php echo $val['admin_id'];?>"  <?php if($val['admin_id'] == $info['admin_id']){ echo "selected";}?>><?php echo $val['admin_name'];?></option>
								    <?php }}else{
								   	foreach($asker_list as $val){ 	?>
								   		<option value="<?php echo $val['admin_id'];?>" <?php if($val['admin_id'] == $_COOKIE['l_admin_id']){ echo "selected";}?> ><?php echo $val['admin_name'];?></option>
								   <?php }}?>
								   </select>

								</div>

							</div>

						</div>

						</div>

						<div class="row-fluid order_from">

						<div class="control-group order_from">

							<label class="control-label"><?php echo $this->lang->line('from_name');?></label>

							<div class="controls">

							   <select name="from_parent_id" id="from_parent_id">

							   <option value="0"><?php echo $this->lang->line('please_select');?></option>

							   <?php if(isset($info)){
							   	foreach($from_list as $val){ ?>
							   <option value="<?php echo $val['from_id'];?>"  <?php if($val['from_id'] == $info['from_parent_id']){ echo "selected";}?>><?php echo $val['from_name'];?></option>

							   <?php } }else{
								   	foreach($from_list as $val){ 	?>
								   	<option value="<?php echo $val['from_id'];?>" ><?php echo $val['from_name'];?></option>

								   	 <?php }}?>

							   </select>



							   <select name="from_id" id="from_id">

							   <option value="0"><?php echo $this->lang->line('please_select');?></option>
							    <?php if(isset($info)){
							    	foreach($form2_data as $val){ ?>
							   <option value="<?php echo $val['from_id'];?>"  <?php if($val['from_id'] == $info['from_id']){ echo "selected";}?>><?php echo $val['from_name'];?></option>

							   <?php } }?>


							   </select>

							   <input type="text" name="from_value" id="from_value" value="<?php if(isset($info)){echo $info['from_value']; }?>" class="input-xlarge" readonly  style="width:367px;background-color: #fff;"/>

							</div>

						</div>

						</div>

						<div class="row-fluid">

						<div class="span6 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>

								<div class="controls">
									<input type="text" value="<?php if(isset($info)){echo $info['patt_name']; }?>" class="input-large" name="patient_name" id="patient_name" />

						            <select name="is_first" style="width:80px;">
								          <?php if(isset($info)){?>
								           <option value="1" <?php if($info['is_first'] == 1){echo 'selected';}?>>初诊</option>
								           <option value="0"  <?php if($info['is_first'] == 0){echo 'selected';}?>>复诊</option>
									      <?php }else{?>
									      <option value="1">初诊</option><option value="0">复诊</option>
									      <?php }?>
						            </select>

								</div>

							</div>

						</div>

							<div class="span5 order_from">
								<div class="control-group">
										<label class="control-label"><?php echo $this->lang->line('sex');?></label>
										<div class="controls">
										 <label class="radio1">
											<input type="radio" name="pat_sex" value="1" <?php if(isset($info)){if($info['pat_sex'] == 1):?>checked="checked"<?php endif;}?>/>
											<?php echo $this->lang->line('man');?>
										</label>
										<label class="radio1">
											<input type="radio" name="pat_sex" value="2" <?php if(isset($info)){if($info['pat_sex'] == 2):?>checked="checked"<?php endif;}?>/>
											<?php echo $this->lang->line('woman');?>
										</label>
									    </div>
								</div>
							</div>
						</div>

						<div class="row-fluid">

						<div class="span6 order_from">
							<div class="control-group order_from">
								<label class="control-label"><?php echo $this->lang->line('phone');?></label>
								<div class="controls">
									<input type="text" value="<?php if(isset($info)){echo $info['pat_phone']; }?>" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
						            <!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
						            <input type="hidden" id="phone_check_month" value="0">
								</div>

							</div>
						</div>

						<div class="span5 order_from">

							<div class="control-group">

								<label class="control-label"><?php echo $this->lang->line('age');?></label>

								<div class="controls">

									<input type="text" value="<?php if(isset($info)){echo $info['pat_age']; }?>"  class="input-large" name="patient_age" id="patient_age" style="width:205px;" />

							    </div>

							</div>

						</div>

						</div>


						<div class="row-fluid">

						<div class="span6 order_from">
							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('qq');?></label>
								<div class="controls">
									<input type="text" value="<?php if(isset($info)){echo $info['pat_qq']; }?>" data-trigger="hover" data-placement="right" data-content="请输入QQ号码" data-original-title="号码输入规则" class="input-large popovers" name="patient_qq" id="patient_qq" />
								</div>

							</div>
						</div>

						<div class="span5 order_from">

							<div class="control-group">

								<label class="control-label">微信账号</label>
								<div class="controls">
									<input type="text" value="<?php if(isset($info)){echo $info['pat_weixin']; }?>" data-trigger="hover" data-placement="right" data-content="请输入微信账号" data-original-title="号码输入规则" class="input-large popovers" name="patient_weixin" id="patient_weixin" />
								</div>

							</div>

						</div>

						</div>

						<!--
						<div class="row-fluid order_from">

						<div class="span6 order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('order_time');?></label>

								<div class="controls">

									<div class="input-icon left">

										<i class="icon-time"></i>

										<input type="text" name="order_time" class="input-large" value="<?php if(isset($info)){echo date("Y-m-d H:i:s",$info['order_time']); }else{echo date("Y-m-d");} ?>" id="order_time" style="width:95px;" />

										 <input type="hidden" id="order_hidden_time" value="<?php if(isset($info)){echo date("Y-m-d H:i:s",$info['order_time']); }else{echo date("Y-m-d");} ?>"/>


										<input type="hidden" name="order_null_time" class="input-large" value="<?php if(isset($info)){echo $info['order_null_time']; }else{echo date("Y-m-d");} ?>" id="order_null_time" style="width:95px;" />

										 <color id="order_time_type_msg" style="color:red;"></color>

									</div>

								</div>

							</div>

						</div>
						</div>
						-->

						<div class="row-fluid order_from">

							<div class="control-group order_from">

								<label class="control-label"><?php echo $this->lang->line('patient_address');?></label>

								<div class="controls">

									<select name="province" id="province" class="input-small m-wrap" style="width:150px;">

									   <option value="0"><?php echo $this->lang->line('please_select');?></option>

									   <?php foreach($province as $val){ ?>

									   <option value="<?php echo $val['region_id'];?>"><?php echo $val['region_name']; ?></option>

									   <?php } ?>

									</select>

									<select name="city" id="city" class="input-small m-wrap" style="width:150px;">

									   <option value="0"><?php echo $this->lang->line('please_select');?></option>

									</select>

									<select name="area" id="area" class="input-small m-wrap" style="width:150px;">

									   <option value="0"><?php echo $this->lang->line('please_select');?></option>

									</select>

									<input type="text" value="<?php echo $info['pat_address'];?>" class="input-xlarge" name="patient_address" style="width:305px;"/>

								</div>

							</div>

						</div>


						<div class="row-fluid order_from">
						<div class="control-group order_from">
							<label class="control-label">商务通来源网址</label>
								<div class="controls">
								    <input type="text" name="laiyuanweb" id="laiyuanweb" value="<?php if(isset($info)){echo $info['laiyuanweb']; }?>" class="input-xlarge"  style="width:367px;"/>
								</div>
						</div>
						</div>

						<div class="control-group order_from">
							<label class="control-label">关键字</label>
								<div class="controls">
								    <input type="text" name="guanjianzi" id="guanjianzi"  value="" class="input-xlarge"  style="width:367px;"/>
								</div>
						</div>

						<div class="row-fluid order_from">

						<div class="control-group order_from">

							<label class="control-label"><?php echo $this->lang->line('remark');?></label>

							<div class="remark" style="height: 70px; background: none; z-index: 1; padding-bottom: 10px;">
						<?php foreach($order_remark_list as $order_remark_list_t){
							if($order_remark_list_t['order_id'] == $info['order_id']){?>
							 <blockquote><p><?php echo $order_remark_list_t['mark_content'];?></p>
							 <small><a href="###"><?php echo  $order_remark_list_t['admin_name'];?></a> <cite><?php echo date("Y-m-d H:i:s", $order_remark_list_t['mark_time']);?></cite></small>
							 </blockquote>
						  <?php  }
						}?>
							</div>

							<div class="controls">

								<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px;"><?php if(isset($info)){echo $info['remark']; }?></textarea>

							</div>

						</div>

						<div class="control-group order_from">

							<label class="control-label">对话记录</label>
                            <div class="description controls">
                            <script id="editor" type="text/plain"><?php if(isset($info)){echo $info['con_content']; }?></script>
                            </div>
                            <!-- 将对话记录赋值到此处 -->
                            <textarea name="con_content" style="display: none;"  id="con_content" ></textarea>

						</div>

						</div>



						<div class="control-group order_from">

							<div class="controls">

								<input type="hidden" name="form_action" id="form_action" value="<?php if(isset($info)){echo 'update'; }else{echo 'add';}?>" />

								<input type="hidden" name="order_id" id="order_id" value="<?php if(isset($info)){echo $info['order_id']; }else{echo '0';}?>" />

						        <input type="hidden" name="p" value="<?php echo $p; ?>" />

						        <input type="hidden"  name="pad_id" value="<?php if(isset($info)){echo $info['pat_id']; } ?>"/>


						        <button type="submit" id="submit" class="btn btn-success" style="background-color:#00a186;"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>

								<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>

							</div>

						</div>

						</form>

                     </div>

                 </div>

             </div>

          </div>

      </div>

    </div>

</div>





   <script src="static/js/jquery.js"></script>

   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>

   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>

   <script src="static/js/bootstrap.min.js"></script>

   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>


   <!-- ie8 fixes -->

   <!--[if lt IE 9]>

   <script src="static/js/excanvas.js"></script>

   <script src="static/js/respond.js"></script>

   <![endif]-->

<!-- 百度編輯器  -->
    <script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/ueditor.all.min.js"> </script>
	<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	<script type="text/javascript" charset="utf-8" src="static/js/ueditor/lang/zh-cn/zh-cn.js"></script>
     <script type="text/javascript">
	 //实例化编辑器
	 //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	 var editor = UE.getEditor('editor',{toolbars:[[
	                                           'toggletool','fullscreen', 'source', '|', 'undo', 'redo', '|',
	                                           'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist','|',
	                                           'simpleupload', 'insertimage','snapscreen','|', 'selectall', 'cleardoc'
	                                       ]],initialFrameWidth:830,initialFrameHeight:400});

	 </script>
	 <style type="text/css">
	.description{width:80%;}
	</style>

   <script src="static/js/common-scripts.js"></script>

   <script src="static/js/datepicker/js/datepicker.js"></script>

<script>

$(document).ready(function(){

	$("#yunzhou_btn").click(function(){

		$("#yunzhou_div").css("display", "block");

	});


     $("#submit").click(function(){

  		var patient_name = $('#patient_name').val();

  		if(patient_name == ''){

  			alert('病人姓名不能为空');

  			return false;

  		}

	    var keshi_id = $('#keshi_id').val();
  		if(keshi_id == '' || keshi_id == 0){

  			alert('科室必须选择');

  			return false;

  		}

 		 var admin_id = $('#admin_id').val();

    		if(admin_id == ''  || admin_id == 0){

    			alert('咨询员必须选择');

    			return false;

    		}

        var laiyuanweb = $("#laiyuanweb").val();
        if(laiyuanweb==''){
        	alert('为了规范统计数据，必须录入来源，商务通录入来源网址，电话录入座机号码，以此类推。');

			return false;
        }


	});


	 $("#yuyue_btn").click(function(){



		var patient_name = $('#patient_name').val();

		if(patient_name == ''){

			alert('病人姓名不能为空');

			return false;

		}


		var keshi_id = $('#keshi_id').val();
  		if(keshi_id == '' || keshi_id == 0){

  			alert('科室必须选择');

  			return false;

  		}

 		 var admin_id = $('#admin_id').val();

    		if(admin_id == ''  || admin_id == 0){

    			alert('咨询员必须选择');

    			return false;

    		}


	});



	$("#yunzhou").click(function(){

		yunzhou();

	});

        $("#yunzhoutian").click(function(){

		yunzhoutian();

	});



	$(".jzsj").click(function(){

		var shijian = $(this).children("a").html();

		$("input[name=order_time_duan_j]").val(shijian);

	});



	$(".zdy").click(function(){

		$("input[name=order_time_duan_j]").val("");

		$("input[name=order_time_duan_j]").focus();

	});




	$("#duan_confirm").change(function(){

		var c = $(this).val();

		if(c == 1)

		{

			$('#order_time_duan_d').css("display", "block");

			$('#order_time_duan_j').css("display", "none");

		}

		else

		{

			$('#order_time_duan_d').css("display", "none");

			$('#order_time_duan_j').css("display", "block");

		}

	});



	$("#data_time").change(function(){



	});

	if($("#order_id").val() >0){}else{
	   ajax_get_keshi(<?php echo $l_hos_id; ?>);
	}

	var current_order_time = $('#order_time').val();

	if(current_order_time == null || current_order_time == "" || current_order_time == "未定"){

		 if($('#order_hidden_time').val() == null || $('#order_hidden_time').val() == "" || $('#order_hidden_time').val() == "未定"){

		 	current_order_time = <?php echo date("Y-m-d",time());?>;

	     }else{

			current_order_time = $('#order_hidden_time').val();

		 }

	}

	$('#order_time').DatePicker({

		format:'Y-m-d',

		date: current_order_time,

		current: current_order_time,

		starts: 1,

		position: 'right',

		onBeforeShow: function(){

			$('#order_time').DatePickerSetDate(current_order_time, true);

		},

		onChange: function(formated, dates){

			var today=new Date();

			today.setHours(0);

			today.setMinutes(0);

			today.setSeconds(0);

			today.setMilliseconds(0);

			$("#order_time_type_msg").html("");

			if (Date.parse(today) > Date.parse(new Date(formated))) {

				$("#order_time").val($("#order_hidden_time").val());

				$("#order_time_type_msg").html("必须大于等于当前时间");

			}else{

				 $('#order_time').val(formated);

				 $('#order_time').DatePickerHide();

				 $("#order_time_type_msg").html("");

			}

		}

	});



	$('#data_time').DatePicker({

		format:'Y-m-d',

		date: $('#data_time').val(),

		current: $('#data_time').val(),

		starts: 1,

		position: 'right',

		onBeforeShow: function(){

			$('#data_time').DatePickerSetDate($('#data_time').val(), true);

		},

		onChange: function(formated, dates){

			$('#data_time').val(formated);

			$('#data_time').DatePickerHide();

		}

	});



	$("#from_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_from(parent_id);

	});



	$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

		ajax_get_jibing(0, 0, 0);

		ajax_get_jibing(0, -1, 0);

		ajax_get_form(hos_id,0);
	});



	$("#keshi_id").change(function(){

		var keshi_id = $(this).val();

		ajax_get_jibing(keshi_id, 0, 0);


		//科室变化影响性别的变化
		 $.ajax({
				type:'get',
				url:'?c=system&m=keshi_sex_get_ajax',
				data:'keshi_id='+keshi_id,
				success:function(data)
				{
					 $("input[name='pat_sex']").each(function(){
						 $(this).prop('checked', false);
						 if($(this).val() == data){
							 $(this).prop('checked', true);
					     }
					 });
				}
			});


	});



	$("#jibing_parent_id").change(function(){

		var parent_id = $(this).val();

		ajax_get_jibing(0, parent_id, 0);

	});



	$("#province").change(function(){

		var province_id = $(this).val();

		ajax_area('city', province_id, 0, 2);

	});



	$("#city").change(function(){

		var city_id = $(this).val();

		ajax_area('area', city_id, 0, 3);

	});



	$("#get_order_no").click(function(){

		$("#order_no").next("i").remove();

		$("#order_no").next("span").remove();

		$("#order_no").parent().parent().removeClass("error");

		$.ajax({

			type:'post',

			url:'?c=order&m=order_no_liulian_ajax',

			data:'',

			success:function(data)

			{

				$("#order_no").val(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	});



	$("#order_no").focusout(function(){

		$("#submit").attr("disabled", false);

		var order_no = $(this).val();

		if(order_no != '')

		{

			$("#order_no").after("<i class='icon-refresh icon-spin'></i>");

			$.ajax({

				type:'post',

				url:'?c=order&m=use_no_liulian_ajax',

				data:'order_no=' + order_no,

				success:function(data)

				{

					if(data == 1)

					{

						$("#order_no").next("i").remove();

						$("#order_no").next("span").remove();

						$("#order_no").parent().parent().addClass("error");

						$("#order_no").after('<span class="help-inline">此预约号已使用</span>');

						$("#submit").attr("disabled", true);

					}

					else if(data == 2)

					{

						$("#order_no").next("i").remove();

						$("#order_no").next("span").remove();

						$("#order_no").parent().parent().addClass("error");

						$("#order_no").after('<span class="help-inline">预约号不正确</span>');

					}

					else

					{

						$("#order_no").next("i").remove();

						$("#order_no").next("span").remove();

						$("#order_no").parent().parent().removeClass("error");

					}

				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

			});

		}

	});



	$("#order_time").change(function(){

		 $("#order_time_type_msg").html("");

		if($("#order_hidden_time").val() != $("#order_time").val()){

			if($("#order_time").val() != '' && $("#order_time").val()  != null){

				var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;

				 if(!DATE_FORMAT.test($("#order_time").val())){

					  $("#order_time_type_msg").html("抱歉，您输入的日期格式有误，正确格式应为\"<?php echo date("Y-m-d",time());?>\".");

					  $("#order_time").val($("#order_hidden_time").val());

				 }

			}

		}

	});





	$("#order_time_type").change(function(){

		if($("#order_hidden_time").val() == '' || $("#order_hidden_time").val() == null || $("#order_time").val() == '未定'){

			var order_time_type = $(this).val();

			if(order_time_type == 1)

			{

				$("#order_time").attr("type", "text");

				$("#order_null_time").attr("type", "hidden");

			}

			else if(order_time_type == 2)

			{

				 $("#order_time").attr("type", "hidden");

				 $("#order_null_time").attr("type", "text");

			}

		}

	});



	//  预约途径 ID

	$("#from_value").focusout(function(){

		//$("#from_value").next("a").remove();

		var from_parent_id = $("#from_parent_id").val();

		var from_value = $("#from_value").val();

		if(from_parent_id == 1) // 如果是商务通，则调取商务通对话

		{

			$.ajax({

				type:'post',

				url:'?c=order&m=kefu_talk',

				data:'type=1&from_value=' + from_value,

				success:function(data)

				{

					data = $.parseJSON(data);

					html = data['str'];

					gid = data['gid_str'];

					$("#con_content").html(html);

					editor.html(html);

					//$("#from_value").after(" <a href=\"javascript:order_window('');\" class=\"btn btn-info\"><i class='icon-hand-right'></i></a>");

				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

			});

		}

	});

});

<?php if(isset($order_data['data_time'])):?>

yunzhou();

<?php endif; ?>

function CloseDiv(show_div,bg_div)

	{

		$('#MyDiv').css("display", "none");



		$('#fade').css("display", "none");

	}

// 验证

function chkForm()

{

    $("#con_content").html(editor.getContent());

    if($("#patient_name").val() == "")

	{
		alert("请输入患者姓名！");
		return false;

	}

	if($("#phone_check_month").val() == "1")
	{
		//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
		alert("存在重复的并且在两个月之内的电话记录，不能提交！");
		return false;
	}

	if($("#patient_phone").val() != '' ){//添加判断电话格式
		var phone_msg = ajax_checkphone(CtoH($("#patient_phone").val()));
		if(phone_msg != ''){
			alert(phone_msg);
			return false;
		}
	}


}

$("#patient_phone").keyup(function(){
	//判断当前的医院 科室 电话号码是否变更 如果变更，才需要请求后台核实电话有效性
	    ajax_checkphone(CtoH($(this).val()));

});



function ajax_checkphone(phone)
{


	 var phone_msg = '';
		$("#patient_phone").val(phone);
		//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
		 $("#phone_check_month").val("0");
		 $(".remove_msg").remove();
		if(phone != "")
		{

			$("#patient_phone").after("<i class='icon-refresh icon-spin'></i>");
			$.ajax({
				type:'post',
				url:'?c=order&m=phone_hos_keshi_check_ajax',
				data:'phone=' + phone+'&order_id='+$("#order_id").val()+'&hos_id='+$("#hos_id").val()+'&keshi_id='+$("#keshi_id").val(),
				async: false,
				success:function(data)
				{
					data = $.parseJSON(data);
					type = data['type'];
					if(type == 0)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline  remove_msg">请输入号码</span>');

						 phone_msg ="请输入号码！";
					}
					else if(type == 1)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline   remove_msg">号码格式错误</span>');

						phone_msg ="号码格式错误！";
					}
					else if(type == 2)
					{
//						var city_id = data['info']['region_id'];
//						var province_id = data['info']['parent_id'];
//
//						$("#province option").removeAttr("selected");
//						$("#province option[value='" + province_id + "']").prop("selected",true);
//
//						ajax_area('city', province_id, city_id, 2);
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、最后回访时间：<font color="red">' + value.mark_time + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
						}
					}else if(type == 5)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码在两个月之内已经被预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、最后回访时间：<font color="red">' + value.mark_time + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
							//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
							$("#phone_check_month").val("1");


							 phone_msg ="当前号码在两个月之内已经被预约过！";
						}
					}else if(type== 4){
                        $("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("success");
						$("#patient_phone").after('<span class="help-inline remove_msg">可以使用！</span>');

                   }else if(type== 6){
                        $("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("success");
						$("#patient_phone").after('<span class="help-inline remove_msg ">请选择医院和科室！</span>');

                    }else if(type == 7){
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");

						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group   remove_msg"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码在在留联中已经被登记过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#" style="width:500px;white-space: normal;">患者姓名：<font color="red">' + value.pat_name + '</font>、医院：<font color="red">' + value.hos_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font>、来源：<font color="red">' + value.from + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
							//<!-- 电话检查 为0的时候正常提交，不为0的时候 需要检查当前号码是否已经存在，如果存在重复则需要判断是否在两个月以内，是则不能提交信息 -->
							$("#phone_check_month").val("1");


							 phone_msg ="当前号码在在留联中已经被登记过！";
						}
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
		return phone_msg;
}





// 时间戳转换时间

function getdate(tm){

	var tt=new Date(parseInt(tm) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ")

	return tt;

}



//时间转换时间戳

function transdate(endTime){

	var date=new Date();

	date.setFullYear(endTime.substring(0,4));

	date.setMonth(endTime.substring(5,7)-1);

	date.setDate(endTime.substring(8,10));

	date.setHours(endTime.substring(11,13));

	date.setMinutes(endTime.substring(14,16));

	date.setSeconds(endTime.substring(17,19));

	return Date.parse(date)/1000;

}



function ajax_from(parent_id, from_id)

{

	$("#from_value").after("<i class='icon-refresh icon-spin'></i>");



	if(parent_id == 1)

	{

		$("#from_value").attr("readonly", false);

		$("#from_value").attr("placeholder", "请输入商务通访客唯一ID号");

	}

	else if(parent_id == 2)

	{

		$("#from_value").attr("readonly", false);

		$("#from_value").attr("placeholder", "请输入患者QQ号");

	}

	else if(parent_id == 3)

	{

		$("#from_value").attr("readonly", false);

		$("#from_value").attr("placeholder", "请输入百度商桥访客唯一身份ID");

	}

	else if(parent_id == 4)

	{

		$("#from_value").attr("readonly", true);

		$("#from_value").attr("placeholder", "");

	}

	else if(parent_id == 15)

	{

		$("#from_value").attr("readonly", false);

		$("#from_value").attr("placeholder", "请输入患者的微信号");

	}

	else if(parent_id == 12)

	{

		$("#from_value").attr("readonly", true);

		$("#from_value").attr("placeholder", "");

	}

	else if(parent_id == 11)

	{

		$("#from_value").attr("readonly", true);

		$("#from_value").attr("placeholder", "");

	}

	else if(parent_id == 23)

	{

		$("#from_value").attr("readonly", false);

		$("#from_value").attr("placeholder", "输入备注信息");

	}

	$.ajax({

		type:'post',

		url:'?c=order&m=from_order_ajax',

		data:'parent_id=' + parent_id + '&from_id=' + from_id,

		success:function(data)

		{

		   $("#from_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#from_value").next(".icon-spin").remove();

		}

	});

}



function ajax_area(area_name, parent_id, check_id, type)

{

	$("#patient_address").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=system&m=area_ajax',

		data:'parent_id=' + parent_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#" + area_name).html(data);



			if(type == 2)

			{

				ajax_area('area', check_id, 0, 3);

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#patient_address").next(".icon-spin").remove();

		}

	});

}


function ajax_get_jibing(keshi_id, parent_id, check_id)

{

	$("#jibing_id").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=jibing_ajax',

		data:'keshi_id=' + keshi_id + '&parent_id=' + parent_id + '&check_id=' + check_id,

		success:function(data)

		{

			if(parent_id == 0)

			{

				$("#jibing_parent_id").html(data);

			}

			else

			{

				$("#jibing_id").html(data);

			}



		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#jibing_id").next(".icon-spin").remove();

		}

	});

}

function ajax_get_form(hos_id, keshi_id)
{
	$("#from_parent_id").after("<i class='icon-refresh icon-spin'></i>");
	$.ajax({
		type:'post',
		url:'?c=order&m=form_list_ajax',
		data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id+"&check_id="+$("#from_parent_id").val(),
		success:function(data)
		{
			$("#from_parent_id").html(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $("#from_parent_id").next(".icon-spin").remove();
		}
	});
}


function ajax_get_keshi(hos_id, check_id)

{

	$("#keshi_id").after("<i class='icon-refresh icon-spin'></i>");

	$.ajax({

		type:'post',

		url:'?c=order&m=keshi_list_ajax',

		data:'hos_id=' + hos_id + '&check_id=' + check_id,

		success:function(data)

		{

			$("#keshi_id").html(data);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $("#keshi_id").next(".icon-spin").remove();

		}

	});

}

/* 全角字符转半角 */
function CtoH(str){
	var result="";
	for (var i = 0; i < str.length; i++){
		if (str.charCodeAt(i)==12288){
			result+= String.fromCharCode(str.charCodeAt(i)-12256);
			continue;
		}
		if (str.charCodeAt(i)>65280 && str.charCodeAt(i)<65375) result+= String.fromCharCode(str.charCodeAt(i)-65248);
		else result+= String.fromCharCode(str.charCodeAt(i));
	}
	return result;
}
</script>

</body>

</html>