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
  <div class="widget">
<!--			<div class="widget-title">
				<h4><i class="icon-reorder"></i> 参数名与参数值说明&nbsp;&nbsp;【<a href="?c=site&m=cpc_all_keywrods&site_id=<?php echo $site_info['site_id']?>" target="_blank" style="color:#F00">更新关键词URL&nbsp;&nbsp;上次更新时间：<?php echo date("Y-m-d H:i:s", $site_info['bd_url_down']);?></a>】</h4>
			<span class="tools">
				<a href="javascript:;" class="icon-chevron-up"></a>
				<a href="javascript:;" class="icon-remove"></a>
			</span>
			</div>-->
<div class="widget-body" style="display:none;">
<h4>参数名说明</h4>
<dl class="dl-horizontal">
 <dt>ra_a</dt>
 <dd>代表竞价计划</dd>
 <dt>ra_b</dt>
 <dd>代表竞价单元</dd>
 <dt>ra_c</dt>
 <dd>代表竞价关键词</dd>
 <dt>ra_d</dt>
 <dd>代表“关键词ID_单元ID_计划ID”</dd>
 <dt>ra_e</dt>
 <dd>代表搜索，如baidu_cpc</dd>
</dl>
<h4>参数值说明</h4>
<dl class="dl-horizontal">
<dt>{$plan_id}</dt>
<dd>该变量表示计划ID</dd>
<dt>{$group_id}</dt>
<dd>该变量表示单元ID</dd>
<dt>{$key_id}</dt>
<dd>该变量表示关键词ID</dd>
<dt>{$plan_name}</dt>
<dd>该变量表示计划名称</dd>
<dt>{$group_name}</dt>
<dd>该变量表示单元名称</dd>
<dt>{$key_name}</dt>
<dd>该变量表示关键词名称</dd>
</dl>
 </div>
</div>
</div>
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget purple">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $site_info['site_name'] . " (<a style='color:#fff;' target='_blank' href='http://" . $site_info['site_domain'] . "/'>" . $site_info['site_domain'] . "</a>)&nbsp;&nbsp;" . $this->lang->line('cpc_plan'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
<div class="widget-body">
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="20">#</th>
	 <th><?php echo $this->lang->line('plan_name'); ?></th>
	 <th width="635"><?php echo $this->lang->line('pc_url'); ?></th>
	 <th width="115"><?php echo $this->lang->line('update'); ?></th>
 </tr>
 </thead>
 <tbody>
  <tr>
	 <td width="20">&nbsp;</td>
	 <td><select name="url_type" id="url_type" class="input-large" style="margin:0;"><option value="0">全部类型</option><option value="1">PC端</option><option value="2">移动端</option></select></td>
	 <td><label class="td_checkbox"><input name="url_add_all" type="checkbox" value="1" checked /> <?php echo $this->lang->line('url_add'); ?></label>&nbsp;&nbsp;<input type="text" value="utm_medium=ppc&utm_source=baidu&utm_term={$key_name}&utm_content={$group_name}&utm_campaign={$plan_name}&m={wmatch}" name="url_all" placeholder="<?php echo $this->lang->line('url_tag_plan'); ?>" class="input-xxlarge" style="margin:0;"/></td>
	 <td><button class="btn btn-default popovers" data-trigger="hover" data-placement="left" data-content="上次更新时间：<?php echo date("Y-m-d H:i:s", $site_info['bd_url_up']);?>" data-original-title="更新全部关键词URL" onClick="all_url(<?php echo $site_info['site_id']?>)"><i class="icon-arrow-up"></i></button>&nbsp;&nbsp;<button class="btn btn-inverse popovers" data-trigger="hover" data-placement="left" data-content="点此按钮，将会更新全部的计划下来。" data-original-title="更新全部计划" onClick="go_url('?c=site&m=cpc_plan&site_id=<?php echo $site_info['site_id']?>&update=1')"><i class="icon-refresh"></i> 计划</button></td>
 </tr>
 <?php
 $i = 0;
 foreach($plan_list as $list):
 $i ++;
 ?>
 <tr>
 	 <td><?php echo $i;?></td>
	 <td><a href="javascript:;" onClick="get_group(this, <?php echo $list['plan_id'];?>, <?php echo $site_info['site_id'];?>, 0)"><i class="icon-plus"></i> <?php echo $list['plan_name'];?></a>&nbsp;&nbsp;<span class="badge<?php if($list['group_count'] == -1){ echo " badge-important"; }?>"><?php echo $list['group_count'];?></span></td>
	 <td><label class="td_checkbox"><input type="checkbox" value="1" name="url_add" id="url_add_plan_<?php echo $list['plan_id'];?>" checked /> <?php echo $this->lang->line('url_add'); ?></label>&nbsp;&nbsp;<input type="text" value="utm_medium=ppc&utm_source=baidu&utm_term={$key_name}&utm_content={$group_name}&utm_campaign={$plan_name}&m={wmatch}" name="url_tag_plan" id="url_id_plan_<?php echo $list['plan_id'];?>" placeholder="<?php echo $this->lang->line('url_tag_plan'); ?>" class="input-xlarge" style="margin:0;"/>&nbsp;&nbsp;<button class="btn btn-default popovers" data-trigger="hover" data-placement="left" data-content="更新之前请做好账户备份，点此按钮将会更新当前计划下所有关键词的URL，但是需要先确定相关关键词是否更新到本地。" data-original-title="更新当前计划下的URL" onClick="plan_change_url(this, <?php echo $list['plan_id'];?>, <?php echo $site_info['site_id'];?>)"><i class="icon-arrow-up"></i></button>&nbsp;<button class="btn btn-danger popovers" data-trigger="hover" data-placement="left" data-content="下载当前计划下的所有单元以及单元下所有关键词信息到本地系统" data-original-title="更新当前计划下的所有单元与关" onClick="go_url('?c=site&m=cpc_plan_keywords&site_id=<?php echo $site_info['site_id']?>&plan_id=<?php echo $list['plan_id']?>&step=1')"><i class="icon-refresh"></i></button>&nbsp;<button class="btn btn-warning popovers" data-trigger="hover" data-placement="left" data-content="把创意中的URL更新到本地系统的关键词原始URL中" data-original-title="把创意中的URL更新到本地系统的关键词原始URL中" onClick="go_url('?c=site&m=chuang_keyword_url&site_id=<?php echo $site_info['site_id']?>&plan_id=<?php echo $list['plan_id']?>')"><i class="icon-arrow-down"></i></button></td>
	 <td><button class="btn btn-danger popovers" data-trigger="hover" data-placement="left" data-content="点此按钮，将会把当前计划下的单元信息从账户全部更新到本地系统。" data-original-title="更新当前计划下的所有单元" onClick="plan_update(this, <?php echo $list['plan_id'];?>, <?php echo $site_info['site_id'];?>, 1)"><i class="icon-refresh"></i> 下载计划</button></td>
 </tr>
 <?php endforeach; ?>
 </tbody>
</table>


</div>
			  </div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
</div>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   
<script language="javascript">
$(document).ready(function(){
	/* 全部追加URL */
	$("input[name='url_add_all']").change(function(){
		if($(this).attr("checked") == 'checked')
		{
			$("input[name='url_add']").checkCbx(true);
		}
		else
		{
			$("input[name='url_add']").checkCbx(false);
		}
	});
	
	/* 当前计划下所有单元追加URL */
	$("input[id^='url_add_plan']").change(function(){
		var url_plan_id = $(this).attr("id").split("_");
		url_plan_id = url_plan_id[3];
		if($(this).attr("checked") == 'checked')
		{
			$("input[id^='url_add_group_" + url_plan_id + "']").checkCbx(true);
		}
		else
		{
			$("input[id^='url_add_group_" + url_plan_id + "']").checkCbx(false);
		}
	});
	
	/* 全部URL都加上某参数 */
	$("input[name='url_all']").change(function(){
		$("input[name^='url_tag_']").val($(this).val());
	});
	
	/* 当前计划下所有单元URL加上某参数 */
	$("input[id^='url_id_plan']").change(function(){
		var url_id = $(this).attr("id").split("_");
		url_id = url_id[3];
		$("input[id^='url_id_group_" + url_id + "']").val($(this).val());
	});
	
	/* 根据类型显示URL */
	$("#url_type").change(function(){
		$("div[id^='pc_div_']").css("display", "block");
		$("div[id^='mobile_div_']").css("display", "block");
			
		if($("#url_type").val() == 1)
		{
		   $("div[id^='mobile_div_']").css("display", "none");
		}
		else if($("#url_type").val() == 2)
		{
		   $("div[id^='pc_div_']").css("display", "none");
		}
	});
});

function all_url(site_id)
{
	var url_tag = $("input[name='url_all']").val();
	var url_add = 0;
	if(url_tag == "")
	{
		alert('计划URL参数不能为空！');
		$(obj).children("i").removeClass("icon-spin");
		return false;
	}
	if($("input[name='url_add_all']").attr('checked') == 'checked')
	{
		url_add = 1;
	}
	window.location.href = "?c=site&m=cpc_all_url_ajax&site_id=" + site_id + '&url_add=' + url_add + '&url_tag=' + encodeURIComponent(url_tag) + '&type=' + $("#url_type").val();
}

function plan_change_url(obj, plan_id, site_id)
{
	$(obj).children("i").addClass("icon-spin");
	var url_tag = $("input[id='url_id_plan_" + plan_id + "']").val();
	var url_add = 0;
	if(url_tag == "")
	{
		alert('计划URL参数不能为空！');
		$(obj).children("i").removeClass("icon-spin");
		return false;
	}
	if($("input[id='url_add_plan_" + plan_id + "']").attr('checked') == 'checked')
	{
		url_add = 1;
	}
	$.ajax({
		type:'post',
		url:'?c=site&m=cpc_plan_url_ajax',
		data:'plan_id=' + plan_id + '&site_id=' + site_id + '&url_add=' + url_add + '&url_tag=' + encodeURIComponent(url_tag) + '&type=' + $("#url_type").val(),
		success:function(data)
		{
			if(data == 0)
			{
				alert('参数不能为空！');
			}
			else if(data == 1)
			{
				alert('该计划没有更新数据到本地！');
			}
			else if(data == 2)
			{
				alert('该计划下有单元没有更新数据到本地！');
			}
			else if(data == 3)
			{
				$(obj).parent().parent().children("td").children(".badge").after('&nbsp;<button class="btn btn-primary"><i class="icon-check"></i></button>');
				alert('更新成功！');
			}
			else if(data == 4)
			{
				$(obj).parent().parent().children("td").children(".badge").after('&nbsp;<button class="btn btn-primary"><i class="icon-check"></i></button>');
				alert('更新成功！');
			}
			else if(data == 5)
			{
				alert('更新失败！');
			}
			else if(data == 10)
			{
				alert('请确保关键词原始URL不能为空！');
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $(obj).children("i").removeClass("icon-spin");
		}
	});
}

function group_change_url(obj, group_id, site_id)
{
	$(obj).children("i").addClass("icon-spin");
	var url_tag = $("input[data='url_id_group_" + group_id + "']").val();
	var key_url = $(".pc_url_" + group_id).val();
	var mb_key_url = $(".mobile_url_" + group_id).val();
	var url_add = 0;
	if($("input[data='url_add_group_" + group_id + "']").attr('checked') == 'checked')
	{
		url_add = 1;
	}
	if(url_tag == "")
	{
		alert('单元URL参数不能为空！');
		$(obj).children("i").removeClass("icon-spin");
		return false;
	}
	
	
	$.ajax({
		type:'post',
		url:'?c=site&m=cpc_group_url_ajax',
		data:'group_id=' + group_id + '&site_id=' + site_id + '&url_add=' + url_add + '&url_tag=' + encodeURIComponent(url_tag) + '&key_url=' + encodeURIComponent(key_url) + '&mb_key_url=' + encodeURIComponent(mb_key_url) + '&type=' + $("#url_type").val(),
		success:function(data)
		{
			if(data == 0)
			{
				alert('参数不能为空！');
			}
			else if(data == 1)
			{
				alert('该单元还没有从百度竞价更新关键词到本地系统，请先更新该单元！');
			}
			else if(data == 2)
			{
				alert('该单元下目前没有关键词！');
			}
			else if(data == 3)
			{
				//alert('更新成功！');
				$(obj).parent().parent().parent().children("td").children(".badge").after('&nbsp;<button class="btn btn-primary"><i class="icon-check"></i></button>');
			}
			else if(data == 4)
			{
				alert('更新失败！');
			}
			else if(data == 10)
			{
				alert('请确保关键词原始URL不能为空！');
			}
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $(obj).children("i").removeClass("icon-spin");
		}
	});
}

/* 异步获取计划下的单元信息 */
function get_group(obj, plan_id, site_id, update)
{
	if($(obj).children("i").attr("class") == 'icon-plus')
	{
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		/* 如果已经加载了则显示刚刚隐藏的 */
		if(($(".group_" + plan_id).attr("class") == "group_" + plan_id) && (update == 0))
		{
			$(".group_" + plan_id).slideDown(100, function(){ $(obj).children("i").attr("class", "icon-minus"); });
		}
		else
		{
			$.ajax({
				type:'post',
				url:'?c=site&m=cpc_group_ajax',
				data:'plan_id=' + plan_id + '&site_id=' + site_id + '&update=' + update,
				success:function(data)
				{
				   data = $.parseJSON(data);
				   $.each(data, function(key, value){
					   show_group(obj, value.group_id, value.group_name, value.keyword_count, value.plan_id, site_id);
				   });
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $(obj).children("i").attr("class", "icon-minus");
				}
			});
		}
	}
	else
	{
		/* 点击关闭的时候，把内容隐藏即可 */
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		$(".gk_" + plan_id).slideUp(100);
		$(".group_" + plan_id).each(function(i){
			$(".group_" + plan_id).eq(i).children().eq(1).children().eq(1).children("i").attr("class", "icon-plus")
		});
		$(".group_" + plan_id).slideUp(100, function(){ $(obj).children("i").attr("class", "icon-plus"); });
	}
}

/* 异步获取单元下的关键词 */
function get_keyword(obj, group_id, plan_id, site_id, update)
{
	$('#bt_' + group_id).css({position:"absolute", right:"-1000px"});
	if($(obj).children("i").attr("class") == 'icon-plus')
	{
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		/* 如果已经加载了则显示刚刚隐藏的 */
		if(($(".keyword_" + group_id).attr("class") == "gk_" + plan_id + " keyword_" + group_id) && (update == 0))
		{
			$("div[id^='pc_div_']").css("display", "block");
			$("div[id^='mobile_div_']").css("display", "block");
			
			/* 根据需要操作的URL类型显示URL */
		    if($("#url_type").val() == 1)
		    {
			   $("div[id^='mobile_div_']").css("display", "none");
		    }
		    else if($("#url_type").val() == 2)
		    {
			   $("div[id^='pc_div_']").css("display", "none");
		    }
			$('#bt_' + group_id).css({position:"absolute", right:"215px"});
			$(".keyword_" + group_id).slideDown(100, function(){ $(obj).children("i").attr("class", "icon-minus"); });
		}
		else
		{
			$.ajax({
				type:'post',
				url:'?c=site&m=cpc_keyword_ajax',
				data:'group_id=' + group_id + '&plan_id=' + plan_id +  '&site_id=' + site_id + '&update=' + update,
				success:function(data)
				{
				   if(data != 'empty')
				   {
					   data = $.parseJSON(data);
					   $.each(data, function(key, value){
						   show_keyword(obj, value.key_id, value.key_keyword, value.pc_url, value.key_price, value.key_match, value.key_url, group_id, plan_id, site_id, value.mobile_url, value.mb_key_url);
						   /* 根据需要操作的URL类型显示URL */
						   if($("#url_type").val() == 1)
						   {
							   $("div[id^='mobile_div_']").css("display", "none");
						   }
						   else if($("#url_type").val() == 2)
						   {
							   $("div[id^='pc_div_']").css("display", "none");
						   }
					   });
				   }
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				   $(obj).children("i").attr("class", "icon-minus");
				   $('#bt_' + group_id).css({position:"absolute", right:"215px"});
				}
			});
		}
	}
	else
	{
		/* 点击关闭的时候，把内容隐藏即可 */
		$(obj).children("i").attr("class", "icon-refresh icon-spin");
		if($(obj).parent().parent().next().attr("class") == "gk_" + plan_id + " keyword_" + group_id)
		{
			$(".keyword_" + group_id).slideUp(100, function(){ $(obj).children("i").attr("class", "icon-plus"); });
		}
		else
		{
			$(obj).children("i").attr("class", "icon-plus");
		}
	}
}

function show_group(obj, group_id, group_name, keyword_count, plan_id, site_id)
{
	var html = '<tr class="group_' + plan_id + '"><td>&nbsp;</td>';
	html += '<td><div class="td_child"></div><a href="javascript:;" onClick="get_keyword(this, ' + group_id + ', ' + plan_id + ', ' + site_id + ', 0)"><i class="icon-plus"></i> ' + group_name +  '</a>';
	if(keyword_count == -1)
	{
		html += '&nbsp;&nbsp;<span class="badge badge-important">' + keyword_count + '</span>';
	}
	else
	{
		html += '&nbsp;&nbsp;<span class="badge">' + keyword_count + '</span>';
	}
	html += '</td>';
	html += '<td><label class="td_checkbox"><input type="checkbox" value="1" name="url_add" data="url_add_group_' + group_id + '" id="url_add_group_' + plan_id + '" checked /> <?php echo $this->lang->line('url_add'); ?></label>&nbsp;&nbsp;<input type="text" value="utm_medium=ppc&utm_source=baidu&utm_term={$key_name}&utm_content={$group_name}&utm_campaign={$plan_name}&m={wmatch}"  data="url_id_group_' + group_id + '" id="url_id_group_' + plan_id + '" placeholder="<?php echo $this->lang->line('url_tag_group'); ?>" class="input-xlarge" style="margin:0;"/>&nbsp;&nbsp;<span style="position:absolute;right:-1000px;" id="bt_' + group_id + '"><button class="btn btn-default" onClick="group_change_url(this, ' + group_id + ', ' + site_id + ')"><i class="icon-arrow-up"></i> 更新账户URL</button>&nbsp;<button class="btn btn-warning" onClick="creative_url(this, ' + group_id + ', ' + site_id + ')"><i class="icon-arrow-down"></i>下载创意URL</button></span></td>';
	html += '<td><button class="btn btn-primary" onClick="group_update(this, ' + group_id + ', ' + plan_id + ', ' + site_id + ', 1)"><i class="icon-refresh"></i> 下载单元</button></td></tr>';
	$(obj).parent().parent().after(html);
}

function show_keyword(obj, key_id, key_keyword, pc_url, key_price, key_match, key_url, group_id, plan_id, site_id, mobile_url, mb_key_url)
{
	var html = '<tr class="gk_' + plan_id + ' keyword_' + group_id + '"><td>&nbsp;</td>';
	html += '<td><div class="td_child"></div><div class="td_child"></div><a href="javascript:;" onClick=""><i class="icon-minus"></i> ' + key_keyword +  '</a></td>';
	html += '<td><div id="pc_div_' + key_id + '">P：<input type="text" value="' + pc_url + '" name="pc_url_' + key_id + '" class="input-xlarge" style="margin:0;" />&nbsp;&nbsp;<div class="input-append" style="margin:0;"><input type="text" value="' + key_url + '" name="key_url_' + key_id + '" class="pc_url_' + group_id + ' input-xlarge" style="margin:0;" /><div class="btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button><ul class="pc_url_ul_' + group_id + ' dropdown-menu pull-right"></ul></div></div></div><div id="mobile_div_' + key_id + '">B：<input type="text" value="' + mobile_url + '" name="mobile_url_' + key_id + '" class="input-xlarge" style="margin:0;" />&nbsp;&nbsp;<div class="input-append" style="margin:0;"><input type="text" value="' + mb_key_url + '" name="mb_key_url_' + key_id + '" class="mobile_url_' + group_id + ' input-xlarge" style="margin:0;" /><div class="btn-group"><button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button><ul class="mobile_url_ul_' + group_id + ' dropdown-menu pull-right"></ul></div></div></div></td>';
	html += '<td><button class="btn btn-default" onClick="keyword_update(this, ' +　group_id +　',' + key_id + ', ' + site_id +  ')"><i class="icon-arrow-up"></i> URL更新</button></td></tr>';
	$(obj).parent().parent().after(html);
}

function keyword_update(obj, group_id, key_id, site_id)
{
	$(obj).children("i").addClass("icon-spin");
	var key_url = $("input[name='key_url_" + key_id + "']").val();
	var pc_url = $("input[name='pc_url_" + key_id + "']").val();
	var mb_key_url = $("input[name='mb_key_url_" + key_id + "']").val();
	var mobile_url = $("input[name='mobile_url_" + key_id + "']").val();
	var url_tag = $("input[data='url_id_group_" + group_id + "']").val();
	var url_add = 0;
	if($("input[data='url_add_group_" + group_id + "']").attr('checked') == 'checked')
	{
		url_add = 1;
	}

	$.ajax({
		type:'post',
		url:'?c=site&m=cpc_keyword_update_ajax',
		data:'key_id=' + key_id + '&url_add=' + url_add + '&url_tag=' + encodeURIComponent(url_tag) + '&site_id=' + site_id + '&key_url=' + encodeURIComponent(key_url) + '&pc_url=' + encodeURIComponent(pc_url) + '&mb_key_url=' + encodeURIComponent(mb_key_url) + '&mobile_url=' + encodeURIComponent(mobile_url) + '&type=' + $("#url_type").val(),
		success:function(data)
		{
		   if(data == 'empty')
		   {
		   	 alert('不能为空！');
		   }
		   else if(data == 0)
		   {
		     alert('<?php echo $this->lang->line('fail'); ?>');
		   }
		   else
		   {
		   	 alert('<?php echo $this->lang->line('success'); ?>');
		   }
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $(obj).children("i").removeClass("icon-spin");
		}
	});
}

function group_update(obj, group_id, plan_id, site_id, update)
{
	if($(obj).parent().parent().next().attr("class") == "gk_" + plan_id + " keyword_" + group_id)
	{
		$(".keyword_" + group_id).slideUp(100);
	}
	$(obj).parent().parent().children("td").eq(1).children().children("i").attr("class", "icon-plus");
	get_keyword($(obj).parent().parent().children("td").eq(1).children("a"), group_id, plan_id, site_id, update);
}

function plan_update(obj, plan_id, site_id, update)
{
	if($(obj).parent().parent().next().attr("class") == "group_" + plan_id)
	{
		$(".gk_" + plan_id).slideUp(100);
		$(".group_" + plan_id).slideUp(100);
	}
	$(obj).parent().parent().children("td").eq(1).children().children("i").attr("class", "icon-plus");
	get_group($(obj).parent().parent().children("td").eq(1).children("a"), plan_id, site_id, update);
}

//根据单元ID获取创意信息
function creative_url(obj, group_id, site_id)
{
	$(obj).html('<i class="icon-minus"></i>');
	$.ajax({
		type:'post',
		url:'?c=site&m=creative_url',
		data:'group_id=' + group_id + '&site_id=' + site_id,
		success:function(data)
		{
		   if(data != 'empty')
		   {
			   data = $.parseJSON(data);
			   $.each(data, function(key, value){
				   $(".pc_url_" + group_id).val(value.pc);
				   $(".mobile_url_" + group_id).val(value.mobile);
				   
				   $(".pc_url_ul_" + group_id).html($(".pc_url_ul_" + group_id).html() + "<li><a href='javascript:;' onClick='get_creative_url(this);'>" + value.pc + "</a></li>");
				   $(".mobile_url_ul_" + group_id).html($(".mobile_url_ul_" + group_id).html() + "<li><a href='javascript:;' onClick='get_creative_url(this);'>" + value.mobile + "</a></li>");
			   });
		   }
		},
		complete: function (XHR, TS)
		{
		   XHR = null;
		   $(obj).html('<i class="icon-arrow-down"></i>下载创意URL');
		}
	});
}

function get_creative_url(obj)
{
	var url = $(obj).html();
	$(obj).parent().parent().parent().parent().children("input").val(url);
}

$.fn.checkCbx = function(type){ 
	return this.each(function(){
		this.checked = type; 
	}); 
   }
</script>
</body>
</html>