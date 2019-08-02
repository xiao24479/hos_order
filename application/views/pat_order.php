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
<style>
.a{ position:absolute; top:0; left:0; height:220px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}
.b{ position:absolute; top:250px; left:0; height:40px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}
.remark{height:auto; width:auto; overflow:hidden; position:relative; top:0px; left:0px;}
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
		  <div class="widget green">
<!--					<div class="widget-title">
					<h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_form'); ?></h4>
					<span class="tools">
						<a href="javascript:;" class="icon-chevron-down"></a>
						<a href="javascript:;" class="icon-remove"></a>
					</span>
					</div>-->
					<div class="widget-body">
<?php if(!empty($info)): ?>

<form onSubmit="return chkForm();" action="?c=order&m=pat_update" method="post" class="form-horizontal" style="position:relative;">
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
	<div class="controls">
		<input type="text" value="<?php if(!empty($info['order_no'])){echo $info['order_no'];}?>" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" />
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_keshi');?></label>
		<div class="controls">
			<select name="keshi_id" id="keshi_id">
			<option value="0"><?php echo $this->lang->line('please_select');?></option>
			<?php foreach($keshi as $val){ ?>
			<option <?php if($val['keshi_id'] == $info['keshi_id']){echo 'selected';}?> value="<?php echo $val['keshi_id'];?>"><?php echo $val['keshi_name'];?></option>
			<?php } ?>
			</select>
		</div>
	</div>
</div>
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label">接诊医生</label>
		<div class="controls">
			<input type="text" value="<?php if(!empty($info['doctor_name'])){echo $info['doctor_name'];}?>" class="input-large" name="doctor_name" id="doctor_name" />
		</div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_jibing');?></label>
		<div class="controls">
			<input type="text" value="<?php if(!empty($info['jb_parent_id'])){echo $info['jb_parent_id'];}?>" class="input-large" name="jibing_parent_id" id="jibing_parent_id" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label">登记</label>
		<div class="controls">
			<input type="text" value="<?php if(!empty($info['admin_id'])){echo $info['admin_id'];}?>" class="input-large" name="admin_id" id="admin_id" />
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="span6 order_from">

	<label class="control-label">来源</label>
	<div class="controls">
	   <input type="text" value="<?php if(!empty($info['from_parent_id'])){echo $info['from_parent_id'];}?>" class="input-large" name="from_parent_id" id="from_parent_id" />
	  
	   
	</div>
	
</div>
<div class="span6 order_from">
	<label class="control-label">来源渠道</label>
	<div class="controls">
	   <input type="text" value="<?php if(!empty($info['from_id'])){echo $info['from_id'];}?>" class="input-large" name="from_id" id="from_id" />
	  
	   
	</div>
</div>

</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>
		<div class="controls">
			<input type="text" value="<?php if(!empty($info['pat_name'])){echo $info['pat_name'];}?>" class="input-large" name="patient_name" id="patient_name" />
            <select name="is_first" style="width:80px;">
            <option <?php if($info['is_first']==1){echo 'selected';}?> value="1">初诊</option>
            <option <?php if($info['is_first']==0){echo 'selected';}?> value="0">复诊</option>
            </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('sex');?></label>
		<div class="controls">
		 <label class="radio1">
			<input type="radio" name="pat_sex" value="1" <?php if($info['pat_sex']==1){ echo "checked='checked'";}?>/>
			<?php echo $this->lang->line('man');?>
		</label>
		<label class="radio1">
			<input type="radio" name="pat_sex" value="2" <?php if($info['pat_sex']==2){ echo "checked='checked'";}?>/>
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
			<input type="text" value="<?php if(!empty($info['pat_phone'])){echo $info['pat_phone'];}?>" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('age');?></label>
		<div class="controls">
			<input type="text" value="<?php if(!empty($info['pat_age'])){echo $info['pat_age'];}?>" class="input-large" name="patient_age" style="width:205px;" />
	    </div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('patient_address');?></label>
	<div class="controls">
		<select name="province" id="province" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		   <?php foreach($province as $val){ ?>
		   <option value="<?php echo $val['region_id'];?>" <?php if($val['region_id'] == $info['pat_province']){echo "selected";}?>><?php echo $val['region_name']; ?></option>
		   <?php } ?>
		</select>
		<select name="city" id="city" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<select name="area" id="area" class="input-small m-wrap" style="width:150px;">
		   <option value="0"><?php echo $this->lang->line('please_select');?></option>
		</select>
		<input type="text" value="<?php if(!empty($info['pat_address'])){echo $info['pat_address'];}?>" class="input-xlarge" name="patient_address" style="width:355px;"/>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('remark');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px; border:1px solid #00F;"><?php if(!empty($info['remark'])){echo $info['remark'];}?></textarea>
	</div>
</div>
</div>

<div class="control-group order_from">
	<div class="controls">
<input type="hidden" name="form_action" value="update" />
<input type="hidden" name="enter_id" id="enter_id" value="<?php echo $info['enter_id']; ?>" />
<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
  </form>
<?php else:?>
<form onSubmit="return chkForm();" action="?c=order&m=pat_update" method="post" class="form-horizontal" style="position:relative;">
<div class="row-fluid order_from">
<div class="control-group">
	<label class="control-label"><?php echo $this->lang->line('order_no');?></label>
	<div class="controls">
		<input type="text" value="" data-trigger="hover" data-placement="right" data-content="若不输入预约号，系统会自动生成新的预约号。之前生成的预约号，只要没有使用都可以继续使用！" data-original-title="预约号说明：" class="input-large popovers" name="order_no" id="order_no" />
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('order_keshi');?></label>
		<div class="controls">
			<select name="keshi_id" id="keshi_id">
			<option value="0"><?php echo $this->lang->line('please_select');?></option>
			<?php foreach($keshi as $val){ ?>
			<option value="<?php echo $val['keshi_id'];?>"><?php echo $val['keshi_name'];?></option>
			<?php } ?>
			</select>
		</div>
	</div>
</div>
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label">接诊医生</label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="doctor_name" id="doctor_name" />
		</div>
	</div>
</div>
</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_jibing');?></label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="jibing_parent_id" id="jibing_parent_id" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group order_from">
		<label class="control-label">登记</label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="admin_id" id="admin_id" />
		</div>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="span6 order_from">

	<label class="control-label">来源</label>
	<div class="controls">
	   <input type="text" value="" class="input-large" name="from_parent_id" id="from_parent_id" />
	  
	   
	</div>
	
</div>
<div class="span6 order_from">
	<label class="control-label">来源渠道</label>
	<div class="controls">
	   <input type="text" value="" class="input-large" name="from_id" id="from_id" />
	  
	   
	</div>
</div>

</div>
<div class="row-fluid">
<div class="span6 order_from">
	<div class="control-group order_from">
		<label class="control-label"><?php echo $this->lang->line('patient_name');?></label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="patient_name" id="patient_name" />
            <select name="is_first" style="width:80px;">
            <option value="1">初诊</option>
            <option value="0">复诊</option>
            </select>
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('sex');?></label>
		<div class="controls">
		 <label class="radio1">
			<input type="radio" name="pat_sex" value="1" <?php if($_COOKIE['l_rank_id'] == 59 || $_COOKIE['l_rank_id'] == 56 || $_COOKIE['l_rank_id'] == 53 || $_COOKIE['l_rank_id'] == 29 || $_COOKIE['l_rank_id'] == 16 || $_COOKIE['l_admin_action'] == 'all'){ echo "checked='checked'";}?>/>
			<?php echo $this->lang->line('man');?>
		</label>
		<label class="radio1">
			<input type="radio" name="pat_sex" value="2" <?php if($_COOKIE['l_rank_id'] == 31 || $_COOKIE['l_rank_id'] == 28){ echo "checked='checked'";}?>/>
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
			<input type="text" value="" data-trigger="hover" data-placement="right" data-content="【香港区号为：00852】【固定电话格式：075512345678】【手机号码前面不加0】" data-original-title="号码输入规则" class="input-large popovers" name="patient_phone" id="patient_phone" />
		</div>
	</div>
</div>
<div class="span5 order_from">
	<div class="control-group">
		<label class="control-label"><?php echo $this->lang->line('age');?></label>
		<div class="controls">
			<input type="text" value="" class="input-large" name="patient_age" style="width:205px;" />
	    </div>
	</div>
</div>
</div>
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
		<input type="text" value="" class="input-xlarge" name="patient_address" style="width:355px;"/>
	</div>
</div>
</div>
<div class="row-fluid order_from">
<div class="control-group order_from">
	<label class="control-label"><?php echo $this->lang->line('remark');?></label>
	<div class="controls">
		<textarea class="input-xxlarge " rows="2" name="remark" style="width:820px; "></textarea>
	</div>
</div>
</div>

<div class="control-group order_from">
	<div class="controls">
		<input type="hidden" name="form_action" value="add" />
		<input type="hidden" name="order_id" id="order_id" value="0" />   
		<button type="submit" id="submit" class="btn btn-success"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
		<button type="reset" class="btn"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button>
	</div>
</div>
</form>
<?php endif; ?>
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
   <script charset="utf-8" src="static/editor/kindeditor.js"></script>
   <script charset="utf-8" src="static/js/clockface.js"></script>
<script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>
<script>
KindEditor.ready(function(K) {
	window.editor = K.create('#con_content', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
						'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'fullscreen']
				});
});
$(document).ready(function(){
	/*$("input[type='text']").hover(function(){
		$(this).focus();
	});
	$("textarea").hover(function(){
		$(this).focus();
	});*/
	

	
	$(".jzsj").click(function(){
		var shijian = $(this).children("a").html();
		$("input[name=order_time_duan_j]").val(shijian);
	});
	
	$(".zdy").click(function(){
		$("input[name=order_time_duan_j]").val("");
		$("input[name=order_time_duan_j]").focus();
	});
	
	/*$('#order_time_duan_j').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#order_time_duan_j').click(function (e) {
        e.stopPropagation();
        $('#order_time_duan_j').clockface('toggle');
    });*/
	
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
<?php if(!empty($info)):?>
ajax_area('city', <?php echo $info['pat_province']; ?>, <?php echo $info['pat_city']; ?>, 3);
ajax_area('area', <?php echo $info['pat_city']; ?>, <?php echo $info['pat_area']; ?>, 3);
<?php endif;?>
	
	$("#province").change(function(){
		var province_id = $(this).val();
		ajax_area('city', province_id, 0, 2);
	});
	
	$("#city").change(function(){
		var city_id = $(this).val();
		ajax_area('area', city_id, 0, 3);
	});
	
	$("#patient_phone").focusout(function(){
		var phone = CtoH($(this).val());
		$("#patient_phone").val(phone);

		if(phone != "")
		{
			$("#patient_phone").after("<i class='icon-refresh icon-spin'></i>");
			$.ajax({
				type:'post',
				url:'?c=order&m=phone_ajax',
				data:'phone=' + phone,
				success:function(data)
				{
					data = $.parseJSON(data);
					type = data['type'];
					if(type == 0)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline">请输入号码</span>');
					}
					else if(type == 1)
					{
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("span").remove();
						$("#patient_phone").parent().parent().addClass("error");
						$("#patient_phone").after('<span class="help-inline">号码格式错误</span>');
					}
					else if(type == 2)
					{
						var city_id = data['info']['region_id'];
						var province_id = data['info']['parent_id'];

						$("#province option").removeAttr("selected");
						$("#province option[value='" + province_id + "']").prop("selected",true);
						
						ajax_area('city', province_id, city_id, 2);
						$("#patient_phone").next("i").remove();
						$("#patient_phone").next("div").remove();
						$("#patient_phone").parent().parent().removeClass("error");
						
						if(data['over'] != "")
						{
							var html = '&nbsp;<div class="btn-group"><button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">当前号码预约过 <span class="caret"></span></button><ul class="dropdown-menu">';
							$.each(data['over'], function(key, value){
								html += '<li><a href="#">患者姓名：<font color="red">' + value.pat_name + '</font>、预约号：<font color="red">' + value.order_no + '</font>、咨询员：<font color="red">' + value.admin_name + '</font>、登记时间：<font color="red">' + value.addtime + '</font></a></li>';
							});
							html += '</ul></div>';
							$("#patient_phone").after(html);
						}
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
			});
		}
	});
	
});
// 验证
function chkForm()
{
	if($("#keshi_id").val() == 0)
	{
		alert("请选择科室！");
		return false;
	}
	/*else if($("#jibing_parent_id").val() == 0)
	{
		alert("请选择病种！");
		return false;
	}*/
	else if($("#hos_id").val() == 0)
	{
		alert("请选择医院！");
		return false;
	}
	else if($("#admin_id").val() == 0)
	{
		alert("请选择咨询员！");
		return false;
	}
	else if($("#patient_name").val() == "")
	{
		alert("请输入患者姓名！");
		return false;
	}
	else
	{
		return true;
	}
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