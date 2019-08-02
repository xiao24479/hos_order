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

<style>

.modal {width:80%; left:50%;margin-left:-40%;}

.modal-body {



max-height: 500px;



min-height: 380px;}

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

					<form action="" method="get" class="date_form order_form" id="order_list_form" style="height:auto;">

						<input type="hidden" value="system" name="c" />

						<input type="hidden" value="docter" name="m" />

						<div class="span4">

							<div class="row-form">

								<label class="select_label">选择医院</label>

								<select name="hos_id" id="hos_id" style="width:180px;">

									<option value="">请选择医院...</option>

									<?php foreach($hos_list as $val):?>

									<option <?php if($hos_id==$val['hos_id']){echo 'selected';}?> value="<?php echo $val['hos_id'];?>" ><?php echo $val['hos_name']; ?></option>

									<?php endforeach;?>

								</select>

							<i class=""></i>

							</div>

						</div>

						<div class="span4">

							<div class="row-form">

								<label class="select_label">选择科室</label>

								<select name="keshi_id" id="keshi_id" style="width:180px;">

									<option value="">请选择科室..</option>

								</select>

							</div>

						</div>		

						<div class="span4">

                                                    <button type="submit" class="btn btn-success" style="background-color:#00a186;"> 搜索 </button> 
							<a href="?c=system&m=docter_info" class="btn"> 添加医生 </a> 

						</div>

					</form>

				</div>

				<div id="page-wraper">

				    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-hover table-bordered">

						<tr><th>排序</th><th>医生</th><th>科室</th><th>排班</th><th>操作</th><th>操作</th></tr>
                                            <?php if(!empty($docter)){  ?>
						<?php foreach($docter as $key=>$val):?>

						<tr>

							<td><span><?php echo $val['doc_order'];?></span></td>

							<td><span><?php echo $val['doc_name'];if(empty($val['is_show'])){echo '(有效)';}else{echo '(失效)';}?></span></td>

							<td><?php echo $val['keshi_name'];?></td>

                                                        <td><?php 
                                                        if(isset($val['book_time'])){
                                                           $aa=$val['book_time']; 
                                                        }else{
                                                           $aa=array(); 
                                                        }
                                                        if(implode('，',$aa)){
                                                        $str=implode('，',$aa);
                                                        
                                                        }else{$str='未排班';};
                                                        echo $str;?></td>

							<td><a href="#myModal" role="button" onclick="edit_static(this,<?php echo $key;?>)" class="btn" data-toggle="modal" >修改排班</a></td>

							<td>
								<button class="btn btn-primary" onclick="go_url('?c=system&m=docter_info&doc_id=<?php echo $key;?>')" style="background-color:#00a186;"><i class="icon-pencil"></i>修改医生资料</button>
								<?php if($_COOKIE['l_rank_id'] == 1):?>
								<button class="btn" onclick="go_del('?c=system&m=docter_del&doc_id=<?php echo $key;?>')">删除医生</button>
								<?php endif;?>
							</td>

						</tr>

						<?php endforeach;
                                            }else{ ?>
                                                
                                                <tr width="100%"><td colspan="6" style="text-align:center;color:red;">很抱歉！还未收录相关数据！</td></tr>
                                            <?php
                                            
                                            }
                                                
                                                
                                                ?>

					</table>

					<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

						<div class="modal-header">

							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

							<h3 id="myModalLabel">【医生一】修改排班</h3>

						</div>

						<div class="modal-body">

							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped table-hover table-bordered" id="editable-sample">

								<thead>

									<tr>

									<td width="30">ID</td>

									<td>周几</td>

									<td>开始</td>

									<td>结束</td>

									<td>接待人数</td>

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

<script>

function time_sel(obj){

		var shijian = $(obj).children("a").html();

		$(obj).parent().parent().prev().val(shijian);

		$(obj).parent().parent().prev().focus();

}

function time_clean(obj){

		$(obj).parent().parent().prev().val("");

		$(obj).parent().parent().prev().focus();

	};

<?php if($hos_id > 0):?>

ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);

<?php endif;?>

function edit_static(obj,doc_id)

{

	var doc_name = $(obj).parent().parent().children('td:eq(1)').children('span').text();

	$('#myModalLabel').html('【'+doc_name+'】排班管理');

	//根据id获取当前医生的排班数据

	$.ajax({

		type:'post',

		url:'?c=system&m=static_book_ajax',

		data:'doc_id=' + doc_id ,

		success:function(data)

		{

			var html = '<tr>';

				html += '<td>#</td>';

				html += '<td>';						

				html += '<input style="width:87px;margin-top:10px;" type="text" id="add_time" value="">';						

				html += '<div class="btn-group">';					

				html += '<button data-toggle="dropdown" class="btn dropdown-toggle">';					

				html += '<span class="caret"></span>';					

				html += '</button>';					

				html += '<ul class="dropdown-menu pull-right">';					

				html += '<li onclick="time_sel(this)"><a href="#">周日</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周一</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周二</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周三</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周四</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周五</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">周六</a></li>';					

				html += '</ul>';					

				html += '</div>';					

				html += '</td>';					

				html += '<td>';					

				html += '<input style="width:87px;margin-top:10px;" id="add_shang" type="text" value="">';					

				html += '<div class="btn-group">';					

				html += '<button data-toggle="dropdown" class="btn dropdown-toggle">';					

				html += '<span class="caret"></span>';					

				html += '</button>';					

				html += '<ul class="dropdown-menu pull-right">';					

				html += '<li onclick="time_sel(this)"><a href="#">08:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">09:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">10:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">11:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">12:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">13:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">14:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">15:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">16:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">17:00</a></li>';					

				html += '<li class="divider"></li>';					

				html += '<li onclick="time_clean(this)"><a href="#">自定义</a></li>';					

				html += '</ul>';					

				html += '</div>';					

				html += '</td>';					

				html += '<td>';					

				html += '<input style="width:87px;margin-top:10px;" id="add_xia" type="text" value="">';					

				html += '<div class="btn-group">';					

				html += '<button data-toggle="dropdown" class="btn dropdown-toggle">';					

				html += '<span class="caret"></span>';					

				html += '</button>';					

				html += '<ul class="dropdown-menu pull-right">';					

				html += '<li onclick="time_sel(this)"><a href="#">08:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">09:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">10:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">11:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">12:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">13:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">14:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">15:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">16:00</a></li>';					

				html += '<li onclick="time_sel(this)"><a href="#">17:00</a></li>';					

				html += '<li class="divider"></li>';					

				html += '<li onclick="time_clean(this)"><a href="#">自定义</a></li>';					

				html += '</ul>';					

				html += '</div>';					

				html += '</td>';							

				html += '<td>';							

				html += '<input style="width:87px;margin-top:10px;" id="add_num" type="text" value="">';							

				html += '</td>';							

				html += '<td>';							

				html += '<a class="btn btn-success" style="margin-top:10px;" onclick="ajax_book_add(this,'+doc_id+')">添加</a>';							

				html += '</td>';		

				html += '</tr>';

				var book = jQuery.parseJSON(data);

				var weak = ["周日","周一", "周二", "周三","周四","周五","周六",]; 

				for(var i=0; i<book.length; i++)

				{

					html += '<tr>';

					html += '<td>'+book[i].book_id+'</td>';

					html += '<td>'+weak[book[i].book_time]+'</td>';					

					html += '<td>'+book[i].shang+'</td>';					

					html += '<td>'+book[i].xia+'</td>';						

					html += '<td>';							

					html += '<input style="width:87px;" class="btn_text" type="text" value="'+book[i].num+'">';							

					html += '</td>';							

					html += '<td>';							

					html += '<a class="btn btn-danger" onclick="ajax_book_del(this,'+book[i].book_id+')">删除</a>';							

					html += '</td>';		

					html += '</tr>';

				}

				$('#book_content').html(html);

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		   $('.btn_text').hover(

				function(){

					val_old = $(this).val();

				},

				function(){

					var val_new = $(this).val();

					if(!isNaN(val_new)&&val_new!=val_old){

						//获取当前book_id

						var book_id = $(this).parent().parent().children('td').eq(0).text();

						$.ajax({

							type:'post',

							url:'?c=system&m=static_update_ajax',

							data:'book_id=' + book_id + '&num=' + val_new,

							success:function(data)

							{

								if(data>0){

									alert('修改成功');

								}else{

									alert('修改失败');

								}

							},

							complete: function (XHR, TS)

							{

							   XHR = null;

							}

						});	

					}

				}

			);

		}

	});

}

function ajax_book_del(obj,book_id)

{

	if (confirm("确定要删除这一行数据吗？") == false) {

        return;

    }

	$.ajax({

		type:'post',

		url:'?c=system&m=static_del_ajax',

		data:'book_id=' + book_id ,

		success:function(data)

		{

			if(data>0){

				alert('删除成功');

				$(obj).parent().parent().remove();

			}else{

				alert('删除失败');

			}

		},

		complete: function (XHR, TS)

		{

		   XHR = null;

		}

	});	

}

function ajax_book_add(obj,doc_id)

{

	var book_time = $('#add_time').val();

	var shang = $('#add_shang').val();

	var xia = $('#add_xia').val();

	var num = $('#add_num').val();

	if( book_time == '' || shang == '' || xia == '' || num == ''){

		alert('有属性为空，添加失败');

	}

	if(isNaN(num)){

		alert("接诊人数必须是数字");

	}

	$.ajax({

		type:'post',

		url:'?c=system&m=static_add_ajax',

		data:'book_time=' + book_time + '&shang=' + shang + '&xia=' + xia + '&num=' + num + '&doc_id=' + doc_id,

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

</script>

</body>

</html>