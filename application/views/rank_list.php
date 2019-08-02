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
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>  
                            </span>
                            </div>-->
<div class="widget-body">
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-striped table-bordered table-advance table-hover" id="sample_4">
  <thead>
  <tr>
	<th><?php echo $this->lang->line('rank_name'); ?></th>
	<th width="60"><?php echo $this->lang->line('order'); ?></th>
	<th width="120"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php echo $rank_list; ?>
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
  
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
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
   <script type="text/javascript">
       //显示岗位下的成员名单
   function users_list_ajax(rank_id){
       var rank_id=rank_id;
       $.ajax({
           
                        type:'post',

			url:'?c=index&m=user_list_ajax',

			data:'rank_id=' + rank_id,
			success:function(data)
                        {
                            data = $.parseJSON(data);
//                            $("#user_list_"+rank_id+" option").remove();
                             $("#user_list_"+rank_id).remove();
                             $("#user_show_"+rank_id).after('<img src="static/img/remove.png" style="width:16px;" onclick="hid('+rank_id+');">');
                             $("#user_show_"+rank_id).css('display','none');
                             var html='<div id="user_list_'+rank_id+'" onclick="hid('+rank_id+');" style="width:100%;"> ';
				$.each(data['user_list'],function(key,value){
                                    var  nowtime=new Date(parseInt(value.admin_nowtime) * 1000).toLocaleString().substr(0,19); 
//                                   html +='<option onclick="go_url(\'?c=index&m=admin_info&admin_id='+value.admin_id+'\')">'+value.admin_name+'</option>'; 
                                    html+='<p><span style="padding-left:150px;width:150px;">'+value.admin_name+'</span><span style="padding-left:30px;color:gray;">最新登录时间:   '+nowtime+'</span><img src="static/img/star.png" onClick="go_url(\'?c=index&m=admin_info&admin_id='+value.admin_id+'\')" style="width:18px; margin-left:20px;">&nbsp;<br/></p>';
                                });
                               html+="</div>";
//                               alert(html);
//                           $("#user_list_"+rank_id).append(html);
                              $("#menu_"+rank_id).after(html);
			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}  
           
           
           
           
       });
   }
   //隐藏清除div中的内容，并且使内容展示图标显示出来，把关闭图标移除
   function hid(rank_id){
  $("#user_list_"+rank_id).remove();
  $("#user_show_"+rank_id).css('display','inline');
 $("#user_show_"+rank_id).next('img').remove();
   }
   
   </script>
</body>
</html>