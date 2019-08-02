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
<link href="static/css/bootstrap-fullcalendar.css" rel="stylesheet" />
<style>
.modal {width:80%; left:50%;margin-left:-40%;}
input.btn_text {background:none;border:none;padding:0;margin:0;height:30px;width:100px;}
</style>
</head>
<body class="fixed-top">
   <?php echo $top; ?>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <?php echo $sider_menu; ?>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">
<input type="hidden" value="system" name="c" />
<input type="hidden" value="book_list" name="m" />
<div class="span3">
	 <div class="control-group order_from">
		<label class="control-label" style="float: left;height: 30px;line-height: 30px;">选择月份</label>
		<div class="controls">
			<div class="input-icon left">
				<select name="book_time_yue" class="input-large" id="book_time_yue" style="width:105px;text-align: center;padding-left: 33px !important;">
					<option value="">请选择月份...</option>
					<option value="1" <?php if($book_time_yue == 1){echo " selected";}?>>1月</option>
					<option value="2" <?php if($book_time_yue == 2){echo " selected";}?>>2月</option>
					<option value="3" <?php if($book_time_yue == 3){echo " selected";}?>>3月</option>
					<option value="4" <?php if($book_time_yue == 4){echo " selected";}?>>4月</option>
					<option value="5" <?php if($book_time_yue == 5){echo " selected";}?>>5月</option>
					<option value="6" <?php if($book_time_yue == 6){echo " selected";}?>>6月</option>
					<option value="7" <?php if($book_time_yue == 7){echo " selected";}?>>7月</option>
					<option value="8" <?php if($book_time_yue == 8){echo " selected";}?>>8月</option>
					<option value="9" <?php if($book_time_yue == 9){echo " selected";}?>>9月</option>
					<option value="10" <?php if($book_time_yue == 10){echo " selected";}?>>10月</option>
					<option value="11" <?php if($book_time_yue == 11){echo " selected";}?>>11月</option>
					<option value="12" <?php if($book_time_yue == 12){echo " selected";}?>>12月</option>
				</select>
			</div>
		</div>
	</div>	
</div>
<div class="span3">
    <div class="row-form">
		<label class="select_label">选择医院</label>
		<select name="hos_id" id="hos_id" style="width:170px;">
			<option value="">请选择医院...</option>
			<?php foreach($hospital as $val):?><option value="<?php echo $val['hos_id']; ?>" <?php if($val['hos_id'] == $hos_id){echo " selected";}?>><?php echo $val['hos_name']; ?></option><?php endforeach;?>
		</select>
		<i class=""></i>
	</div>
</div>
<div class="span3">
	<div class="row-form">
		<label class="select_label">选择科室</label>
			<select name="keshi_id" id="keshi_id" style="width:140px;">
				<option value="">请选择科室..</option>
		</select>
		<i class=""></i>
	</div>
</div>
<div class="span3">
    <div class="row-form">
		<label class="select_label">选择医生</label>
		<select name="doc_id" id="doc_id" style="width:140px;">
			<option value="">请选择医生...</option>
		</select>
	</div>
</div>		
<div class="order_btn">
    <button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button> 
	<?php if($check):?>
	<?php if(!empty($doc_id)):?>
	<button class="btn"> <a href="?c=system&m=book_create&type=1&doc_id=<?php echo $doc_id ?>&book_time_yue=<?php echo $book_time_yue ?>"> 生成 </a></button>
	<?php endif;?>
	<?php else:?>
	<a role="button" class="btn btn-danger" data-toggle="modal" onclick="ajax_book_create(this)" style="background-color:#00a186;">按月生成</a>
	<?php endif;?>
</div>
</form>
            <div id="page-wraper">
				<?php if($check):?>
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-striped table-hover">
						<tr>
						<th width="40">排序</th>
						<th>医生</th>
						<th>周日</th>
						<th>周一</th>
						<th>周二</th>
						<th>周三</th>
						<th>周四</th>
						<th>周五</th>
						<th>周六</th>
						<th>状态</th>
						</tr>
						<?php foreach($book_list as $key=>$val):?>
							<tr>
							<td><?php echo $val['doc_order'];?></td>
							<td><span><?php echo $val['doc_name'];?></span><br /><?php echo $val['keshi_name'];?></td>
							<?php for($i=0;$i<7;$i++):?>
							<td>
							<?php if(isset($val[$i])):?>
								<span class="book_text"><?php echo implode(',',$val[$i]);?></span>
							<?php endif;?>
							<br />
							<a href="#myModal" onclick="book_list(this,<?php echo $key;?>)" role="button" class="btn" data-toggle="modal">修改/添加</a>
							</td>
							<?php endfor;?>
							<td><a class="btn btn-<?php if($val['is_show']==0):?>success<?php else:?>danger<?php endif;?>" style="margin-top:20px;margin-left:20px;" onclick="ajax_book_hidden(this,<?php echo $key;?>)"><?php if($val['is_show']==0):?>显示<?php else:?>隐藏<?php endif;?></a></td>
							</tr>
						<?php endforeach;?>
					</table>
					<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">【医生】修改排班</h3>
						</div>
						<div class="modal-body">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover table-bordered" id="editable-sample">
								<thead>
									<tr class="">
									<td width="30">ID</td>
									<td>日期</td>
									<td>时段</td>
									<td>总号数</td>
									<td>已预约</td>
									<td>操作</td>
									</tr>
								</thead>
								<tbody id="book_content">
								</tbody>
							</table>
					    </div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
						</div>
					</div>
				<?php else:;?>
					<div class="msg" style="border-color:#00a186;">
						  <div class="info">
						  <h1>本月医生出诊信息未生成</h1>
						  <p>每月只能生成一次医生出诊信息，在您确定填写好出诊报表的前提下生成，出诊信息生成后您可实时查看和修改</p>
						  <ul>
							<li><a style="color:#00a186;" href="?c=system&m=book_create&hos_id=<?php echo $hos_id ?>&book_time_yue=<?php echo $book_time_yue ?>">生成本月出诊信息</a></li>
						  </ul>
						  </div>
					</div>
				<?php endif;?>
            </div>
            <!-- END PAGE CONTENT-->
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->
   </div>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script type="text/javascript" src="static/js/jquery-ui-1.9.2.custom.min.js"></script>
   <script src="static/js/fullcalendar.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   
   <!--动态表格JS-->
   <script type="text/javascript" src="static/js/jquery.dataTables.js"></script>
   <script type="text/javascript" src="static/js/DT_bootstrap.js"></script>
   <script src="static/js/dynamic-table.js"></script>
   <!--动态表格JS-->
   <script src="static/js/common-scripts.js"></script>
   <script language="javascript">
   function ajax_book_hidden(obj,doc_id){
	   var text = $(obj).text();
	   if(text == '显示'){
		   var tag = 1;
	   }else if(text == '隐藏'){
		  var tag = 2; 
	   }
		$.ajax({
			url: '?c=system&m=book_hidden_ajax',
			type: 'post',
			dataType: 'json',
			data: 'doc_id='+ doc_id + '&tag=' + tag,
			async: false,//同步传输才能给外面的变量传值
			success: function(data){
				if(data>0){
					if(tag == 1){
						$(obj).text('隐藏');
					}else if(tag == 2){
						$(obj).text('显示');
					}
				}else{
					alert('操作失败');
				}
			}
		});  
   }
   function book_list(obj,doc_id){
		var data_book = $(obj).parent().children('.book_text').html();
		var yue = <?php echo $book_time_yue;?>;
		var doc_name = $(obj).parent().parent().children('td:eq(1)').children('span').text();
		$('#myModalLabel').html('【'+doc_name+'】修改排班');
		$('#book_content').html('');
		$.ajax({
				type:'post',
				url:'?c=system&m=book_list_ajax',
				data:'doc_id=' + doc_id +　'&yue=' + yue + '&day=' + data_book,
				success:function(data)
				{
					var html = '';
					html += '<tr>';
					html += '<td>————</td>';
					html += '<td><input name="book_time" class="btn_text" value="" type="text"></input></td>';
					html += '<td><input name="data_time" class="btn_text" value="" type="text"></input></td>';
					html += '<td><input name="num" class="btn_text" value="" type="text"></input></td>';
					html += '<td class="center">———</td>';
					html += '<td><a class="btn btn-success" onclick="ajax_book_add(this,'+doc_id+')">添加</a></td>';
					html += '</tr>';
					if(data != -1){
						var book = jQuery.parseJSON(data);
						for(var i=0; i<book.length; i++)
						{
							html += '<tr>';
							html += '<td>'+book[i].book_id+'</td>';
							html += '<td>'+book[i].book_time+'</td>';
							html += '<td><input name="data_time" class="btn_text" value="'+book[i].shang+'-'+book[i].xia+'" type="text"></input></td>';
							html += '<td><input name="num" class="btn_text" value="'+book[i].num+'" type="text"></input></td>';
							html += '<td class="center">'+book[i].numed+'</td>';
							html += '<td><a class="btn btn-danger" onclick="ajax_book_del(this,'+book[i].book_id+')">删除</a></td>';
							html += '</tr>';
							
						}
					}
					$('#book_content').html(html);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $(".btn_text").hover(
				   function(){
						val_old = $(this).val();
						$(this).css({'background':'#FFF','border':'1px solid #AAA'});
					},
					function(){
						$(this).css({'background':'none','border':'1px solid #FFF'});
						var val_new = $(this).val();
						if(val_new!=val_old){
							var book_id = $(this).parent().parent().children('td').eq(0).text();
							var name = ($(this).attr('name'));
							$.ajax({
								url: '?c=system&m=book_update_ajax',
								type: 'post',
								dataType: 'json',
								data: 'book_id='+book_id+'&'+name+'='+val_new,
								async: false,//同步传输才能给外面的变量传值
								success: function(data){
								if(data>0)
								  alert('排班修改成功');
								else
								  alert('排班修改失败');
								}
							  });  
						}
					});
				}
			});
   }
   
	$("#keshi_id").change(function(){
		var keshi_id = $(this).val();
		var hos_id = $('#hos_id').val();
		$(this).next("i").attr("class", "icon-refresh icon-spin");
		$.ajax({
				type:'post',
				url:'?c=system&m=doc_list_ajax',
				data:'hos_id=' + hos_id +　'&keshi_id=' + keshi_id + '&check=1',
				success:function(data)
				{
					$("#doc_id").empty();
					$("#doc_id").append(data);
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $("#keshi_id").next("i").attr("class", "");
				}
			});
	});
<?php if($keshi_id > 0):?>
ajax_get_doc(<?php echo $hos_id?>, <?php echo $keshi_id?>,<?php echo $doc_id?>);
<?php endif;?>
<?php if($hos_id > 0):?>
ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);
<?php endif;?>
function ajax_get_doc(hos_id,keshi_id, doc_id)	
{
	$.ajax({
		type:'post',
		url:'?c=system&m=doc_list_ajax',
		data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id + '&doc_id=' + doc_id + '&check=1',
		success:function(data)
		{
			$("#doc_id").empty();
			$("#doc_id").append(data);
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});	
}
function ajax_book_add(obj,doc_id){
	var book_time = $(obj).parent().parent().children('td:eq(1)').children(':input').val();
	var data_time = $(obj).parent().parent().children('td:eq(2)').children(':input').val();
	var num = $(obj).parent().parent().children('td:eq(3)').children(':input').val();
	$.ajax({
		type:'post',
		url:'?c=system&m=book_add_ajax',
		data:'book_time=' + book_time + '&num=' + num + '&doc_id=' + doc_id + '&data_time=' + data_time,
		success:function(data)
		{
			if(data>0){
				alert('添加成功');
			}else{
				alert('添加失败');
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});	
}
function ajax_book_del(obj,book_id){
	if (confirm("确定要删除这一行数据吗？") == false) {
        return;
    }
	var book_id = book_id;	
	$.ajax({
		type:'post',
		url:'?c=system&m=book_del_ajax',
		data:'book_id=' + book_id ,
		success:function(data)
		{
			$(obj).parent().parent().remove();
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});	
}
$("#hos_id").change(function(){

		var hos_id = $(this).val();

		ajax_get_keshi(hos_id, 0);

	});
function ajax_get_keshi(hos_id, check_id)
{
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
		}
	});
}
function ajax_book_create(obj){
	var hos_id = $('#hos_id').val();
	var book_time_yue = $('#book_time_yue').val();
	if(hos_id == ''){
		alert('请选择医院');
		return;
	}
	if(book_time_yue == ''){
		alert('请选择月份');
		return;
	}
	$.ajax({
		type:'post',
		url:'?c=system&m=book_create',
		data:'hos_id=' + hos_id + '&book_time_yue=' + book_time_yue,
		success:function(data)
		{
			$(obj).css('background','black');
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		}
	});	
}
</script>
</body>
</html>