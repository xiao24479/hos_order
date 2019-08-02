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
<style>
td{color:#666;}
.td1 td{background-color:#efefef;}
.date_div{ position:absolute; top:140px; z-index:100000;}
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
	<form method="post">
	<input type="hidden" value="order" name="c" />

	<input type="hidden" value="huifang_list" name="m" />
	<div class="span4">
		<div class="row-form">
			<label class="select_label">联系时间</label>
			<input type="text" style="width:270px;" class="input-block-level" name="date" value="<?php echo $start_date; ?> - <?php echo $end_date; ?>" id="inputDate" />

		</div>
		<div class="date_div" style="display:none;">

			<div class="divdate"></div>

			<div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br /><a href="javascript:;" class="btn btn-inverse today"> 今天 </a><br /><a href="javascript:;" class="btn btn-inverse week"> 一周 </a><br /><a href="javascript:;" class="btn btn-inverse month"> 一月 </a><br /><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>

		</div>
		<div class="row-form">

			<label class="select_label">预约医院</label>

			<select name="hos_id" id="hos_id" style="width:167px;">

				<option value=""><?php echo $this->lang->line('hospital_select'); ?></option>

				<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>

			</select>
			<select name="type_id" style="width:100px;">
				<option value="0">咨询类型</option>
				<option value="1">咨询回访</option>
				<option value="2">咨询备注</option>
			</select>
		</div>
	</div>
	<div class="span3">
		<select name="lx_id" style="width:120px;">
			<option value="0">回访类型</option>
			<?php foreach($huifang['lx'] as $key=>$val):?>
			<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
			<?php endforeach;?>
		</select>
		<select name="jg_id" style="width:120px;">
			<option value="0">回访状态</option>
			<?php foreach($huifang['jg'] as $key=>$val):?>
			<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
			<?php endforeach;?>
		</select>
		<select name="jg_id" style="width:120px;">
			<option value="0">回访状态</option>
			<?php foreach($huifang['jg'] as $key=>$val):?>
			<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
			<?php endforeach;?>
		</select>
		<select name="ls_id" style="width:120px;">
			<option value="0">客户流向</option>
			<?php foreach($huifang['ls'] as $key=>$val):?>
			<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
			<?php endforeach;?>
		</select>

	</div>
	<div class="span3">
		<div class="row-form">

			<label class="select_label">咨询员</label>

			<input type="text" value="" class="input-medium" name="a_i"  />

		</div>
		<div class="row-form">

			<label class="select_label">快捷查询</label>

			<input name="name" style="width:150px;" data-trigger="hover" data-placement="left" data-content="可输入预约号，患者电话，手机号码，通过单击鼠标左键进行针对性搜索。" data-original-title="搜索说明：" type="text" value="<?php if(isset($name)){echo $name;}?>" class="popovers">

		</div>
	</div>
	<div class="span1">
	<button type="submit" class="btn btn-success" style="margin-top:42px;"> 搜索 </button>
	</div>
	</form>
				  <div class="widget orange">
                                      <div class="widget-title" style="background-color:#00a186;">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
<!-- 								<a href="?c=order&m=huifang_list"><span class="d_url">所有回访</span></a>
								<a href="?c=order&m=huifang_list&huifang_d=1"><span class="d_url">今日回访</span></a>
								<a href="?c=order&m=huifang_list&huifang_d=2"><span class="d_url">昨日回访</span></a>
								<a href="?c=order&m=huifang_list&huifang_d=3"><span class="d_url">明日回访</span></a> -->
								<style>
								.d_url{height:32px; line-height:34px;color:#fff;mrgin-right:25px;}
								</style>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>

                            <div class="widget-body">
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-advance">
  <thead>
  <tr>
	<th>预约号</th>
	<th>患者信息</th>
    <th>回访主题</th>
	<th>回访类型</th>
	<th>回访状态</th>
	<th>客户流向</th>
	<th>下次联系时间</th>
	<th width="250">回访</th>
	<th>操作</th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i=0;
  foreach($huifang_list as $key=>$val):
  $count = count($val);
  $i++;
  foreach($val as $key=>$item):
  ?>
  <tr style="height:70px;  <?php if(($i+1)%2==0){echo "background-color:#fff";}?>">
	<?php if($key == 0):?>
    <td rowspan="<?php echo $count;?>"><?php echo $item['order_no']; ?><br/><?php if($item['is_come'] > 0){ echo '<i id="status_' . $item['order_id'] . '" class="icon-ok" style="color:#F00; font-size: large;"></i>';}else{ echo '<i id="status_' . $item['order_id'] . '" class="icon-remove" style="color:#00F; font-size: large;"></i>'; }?></a></td>
    <td rowspan="<?php echo $count;?>"><?php echo $pat_cache[$item['pat_id']]['pat_name'];?><br /><?php echo $pat_cache[$item['pat_id']]['pat_phone'];?></br><small style="color:red;">[<?php echo $count;?>]条咨询回访</small></td>
    <?php endif;?>
    <td><?php if(empty($item['zt_id'])){echo '未填写';}else{echo $huifang['zt'][$item['zt_id']];}?></td>
    <td><?php if(empty($item['lx_id'])){echo '未填写';}else{echo $huifang['lx'][$item['lx_id']];}?></td>
	<td><?php if(empty($item['jg_id'])){echo '未填写';}else{echo $huifang['jg'][$item['jg_id']];}?></td>
	<td><?php if(empty($item['ls_id'])){echo '未填写';}else{echo $huifang['ls'][$item['ls_id']];}?></td>
	<td><?php if(empty($item['date_lx'])){echo '未安排时间';}else{echo date('Y-m-d H:i',$item['date_lx']);}?></td>
	<td style="position:relative;">
	<div class="remark" style="height: 70px; z-index: 1; padding-bottom: 10px; background: none;">
	<blockquote><p><?php echo $item['mark_content'];?></p><small><a href="###"><?php echo $item['admin_name'];?></a> <cite><?php echo date('m-d H:i',$item['mark_time']);?></cite></small></blockquote>
	</div>
	</td>
	<?php if($key == 0):?>
	<td rowspan="<?php echo $count;?>">
		<?php
		if((in_array(66, $admin_action)) || ($_COOKIE['l_admin_action'] == 'all') || ($rank_type == 2)):
		?>
		<a href="javascript:order_window('?c=order&m=order_info&type=mi&order_id=<?php echo $item['order_id'];?>');" class="btn btn-info"><?php echo $this->lang->line('edit'); ?></a>
		<?php endif;?>
		<a href="#visit" role="button" class="btn btn-success" data-toggle="modal" onclick="ajax_remark(<?php echo $item['order_id'];?>)">回访</a>
	</td>
	<?php endif;?>
  </tr>
  <?php
	endforeach;
  endforeach;
  ?>
  </tbody>
  </table>
  <?php echo $page; ?>
			  </div>
			  </div>
	<div id="visit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">回访记录</h3>

		</div>

		<div class="modal-body">

			<div class="control-group">

				<div class="controls" id="patient_info"></div>

			</div>
			<div class="control-group">
				<div class="span6">
				<label class="control-label">回访主题</label>

				<div class="controls">
					<select name="zt_id" id="zt_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['zt'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>

				</div>
				</div>

				<div class="span6">
				<label class="control-label">回访类型</label>

				<div class="controls">
					<select name="lx_id" id="lx_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['lx'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>

				</div>
				</div>
			</div>
			<div class="control-group">
				<div class="span6">
				<label class="control-label">回访状态</label>

				<div class="controls">
					<select name="jg_id" id="jg_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['jg'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>

				</div>
				</div>

				<div class="span6">
				<label class="control-label">客户流向</label>

				<div class="controls">
					<select name="ls_id" id="ls_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($huifang['ls'] as $key=>$val):?>

                    <option value="<?php echo $key; ?>"><?php echo $val; ?></option>

                    <?php endforeach;?>

                    </select>

				</div>
				</div>
			</div>

			<div class="control-group">

				<div class="span6">
				<label class="control-label"><?php echo $this->lang->line('false_name');?></label>

				<div class="controls">
					<select name="false_id" id="false_id">

                    <option value="0"><?php echo $this->lang->line('please_select'); ?></option>

                    <?php foreach($dao_false as $val):?>

                    <option value="<?php echo $val['false_id']; ?>"><?php echo $val['false_name']; ?></option>

                    <?php endforeach;?>

                    </select>

				</div>
				</div>
				<div class="span6">
				<label class="control-label">下次联系</label>

				<div class="controls">
					<input type="text" value=""  name="nextdate" id="nextdate" style="width:80px;"/>
					<div class="input-append" style="margin-right:20px; float:right;">
						 <input type="text" name="datehour" id="datehour" style="width:87px; float:left;;" value="">
						 <div class="btn-group">
							<button data-toggle="dropdown" class="btn dropdown-toggle">
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right">
								<li onClick="time_sel(this)"><a href="#">08:00</a></li>
								<li onClick="time_sel(this)"><a href="#">09:00</a></li>
								<li onClick="time_sel(this)"><a href="#">10:00</a></li>
								<li onClick="time_sel(this)"><a href="#">11:00</a></li>
								<li onClick="time_sel(this)"><a href="#">12:00</a></li>
								<li onClick="time_sel(this)"><a href="#">13:00</a></li>
								<li onClick="time_sel(this)"><a href="#">14:00</a></li>
								<li onClick="time_sel(this)"><a href="#">15:00</a></li>
								<li onClick="time_sel(this)"><a href="#">16:00</a></li>
								<li onClick="time_sel(this)"><a href="#">17:00</a></li>
								<li class="divider"></li>
								<li onClick="time_clean(this)"><a href="#">自定义</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="date_lx" style="display:none;position:absolute;"><div class="lxdate"></div></div>
				</div>
			</div>

            <div class="control-group">

				<label class="control-label"><?php echo $this->lang->line('notice'); ?></label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="visit_remark" id="visit_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="order_id" id="visit_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="visit_add();"> 提交 </button>

		</div>

	</div>

			  </div>
            </div>
            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   <script src="static/js/datepicker/js/datepicker.js"></script>
   <script>
	$('.lxdate').DatePicker({

		flat: true,

		date: [''],

		current: '',

		format: 'Y-m-d',

		calendars: 1,

		starts: 1,

		onChange: function(formated) {
			$('#nextdate').val(formated);
			$('.date_lx').hide();

		}

	});
	$("#nextdate").focus(function(){

		$('.date_lx').css("display", "block");

		$('.date_lx .datepicker').css({"width":"210px",'height':'160px','background':'black'});

	});
	function ajax_remark(order_id)

{

	$("#visit").children("btn").css("display", "none");

	$("#visit_order_id").val(order_id);

	$("#false_id").val(0);

	$("#visit_remark").val("");

	var zt_id = $("#zt_id").val(0);

	var lx_id = $("#lx_id").val(0);

	var jg_id = $("#jg_id").val(0);

	var ls_id = $("#ls_id").val(0);

	var date_lx = $("#nextdate").val('');
	var datehour = $("#datehour").val('');

}
	function visit_add()

{

	var order_id = $("#visit_order_id").val();

	var false_id = $("#false_id").val();

	var remark = $("#visit_remark").val();

	var zt_id = $("#zt_id").val();

	var lx_id = $("#lx_id").val();

	var jg_id = $("#jg_id").val();

	var ls_id = $("#ls_id").val();

	var date_lx = $("#nextdate").val();
	var datehour = $("#datehour").val();
	$.ajax({

		type:'post',

		url:'?c=order&m=order_update_ajax',

		data:'order_id=' + order_id + '&false_id=' + false_id + '&zt_id=' + zt_id + '&lx_id=' + lx_id + '&jg_id=' + jg_id + '&ls_id=' + ls_id + '&date_lx=' + date_lx + '&remark=' + remark + '&type=visit' + '&datehour=' + datehour,

		success:function(data)

		{

			XHR = null;

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});

}
function order_window(url)

{

	window.open (url, 'newwindow', 'height=650, width=1100, top=50, left=50, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');

}
$(".remark").hover(

	  function(){

	      $(this).css({height:"auto", background:"#CCFFCC", "z-index":"999", "padding-bottom":"50px"});

	  },

	  function(){

	     $(this).css({height:"70px", background:"none", "z-index":"1", "padding-bottom":"10px"});

	  }

	);
function time_sel(obj){
		var shijian = $(obj).children("a").html();
		$(obj).parent().parent().prev().val(shijian);
		$(obj).parent().parent().prev().focus();
}
function time_clean(obj){
		$(obj).parent().parent().prev().val("");
		$(obj).parent().parent().prev().focus();
	};
	$("#inputDate").focus(function(){

		$('.date_div').css("display", "block");
		$('.date_div .datepicker').css({"width":"444px",'height':'156px','background':'black'});
	});
	$('.divdate').DatePicker({

		flat: true,

		date: ['<?php echo $start_date; ?>','<?php echo $end_date; ?>'],

		current: '<?php echo $end_date; ?>',

		format: 'Y年m月d日',

		calendars: 2,

		mode: 'range',

		starts: 1,

		onChange: function(formated) {

			$('#inputDate').val(formated.join(' - '));

		}

	});
	$(".anniu .guanbi").click(function(){

		$('.date_div').css("display", "none");

	});
	$(".anniu .today").click(function(){

		$('#inputDate').val(get_day(0) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .week").click(function(){

		$('#inputDate').val(get_day(-6) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .month").click(function(){

		$('#inputDate').val(get_day(-29) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	$(".anniu .year").click(function(){

		$('#inputDate').val(get_day(-364) + " - " + get_day(0));

		$('.date_div').css("display", "none");

	});

	function get_day(day){

       var today = new Date();

       var targetday_milliseconds=today.getTime() + 1000*60*60*24*day;

       today.setTime(targetday_milliseconds); /* 注意，这行是关键代码 */

       var tYear = today.getFullYear();

       var tMonth = today.getMonth();

       var tDate = today.getDate();

       tMonth = doHandleMonth(tMonth + 1);

       tDate = doHandleMonth(tDate);

       return tYear + "年" + tMonth + "月" + tDate + "日";

}
function doHandleMonth(month){

       var m = month;

       if(month.toString().length == 1){

          m = "0" + month;

       }

       return m;

}

   </script>
</body>
</html>