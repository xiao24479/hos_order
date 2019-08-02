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
				  <div class="widget purple">
                            <div class="widget-title" style="background-color: #00a186;">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>
<div class="widget-body">
	 <div class="clearfix">
		 <div class="btn-group">
          <form action="" method="get" class="date_form order_form" id="order_list_form"style="width:100%;">
   
                        <input type="hidden" value="system" name="c" />  
                        <input type="hidden" value="sms_themes" name="m" />

                       医院:<select name="hos_id"  id="hos_id" style="width:110px;vertical-align:middle;height:30px;margin-top:8px;font-size:12px; " >
                        <option value="0">请选择</option>
                       <?php foreach($hospital as $val){?>
                        <option value="<?php echo $val['hos_id']; ?>" <?php if(strcmp($hos_id,$val['hos_id']) ==0){echo 'selected="selected"';} ?>><?php echo $val['hos_name']; ?></option>
                     <?php  }?>
                     </select>
                      科室:<select name="keshi_id" id="keshi_id"  style="width:110px;vertical-align:middle;height:30px;margin-top:8px;font-size:12px; " >
                        <option value="0">请选择</option>
                           <?php foreach($keshi as $val){?>
                        <option value="<?php echo $val['keshi_id']; ?>" <?php if(strcmp($keshi_id,$val['keshi_id']) ==0){echo 'selected="selected"';} ?>><?php echo $val['keshi_name']; ?></option>
                     <?php  }?>
                       </select>
                        <span style="margin-left: 10px;"> <input type="image" class="input_search" src="static/img/dy_search.png" style="vertical-align:middle;height:30px; cursor:pointer;" onClick="this.form.submit();"/></span>
                    
                        <span style="margin-left: 10px;">  <a href="?c=system&m=sms_themes_info" class="btn btn-primary"><?php echo $this->lang->line('add'); ?></a></span>
                     </form>
                      
		 </div>
	 </div>
	 <div class="space15"></div>
<table class="table table-hover" id="editable-sample">
 <thead>
 <tr>
	 <th width="40">序号</th>
	 <th><?php echo $this->lang->line('themes_name'); ?></th>
	 <th><?php echo $this->lang->line('hos_name'); ?></th>
	 <th><?php echo $this->lang->line('keshi_name'); ?></th>
	 <th width="100"><?php echo $this->lang->line('action'); ?></th>
 </tr>
 </thead>
 <tbody>
<?php
if(!empty($themes)):
        $i=0;
	foreach($themes as $list):
               $i++;
?>
 <tr  <?php if(($i+1)%2==0){echo "style='background-color:#fff'";}?>>
 	 <td><?php echo $list['themes_id'];?></td>
	 <td><?php echo $list['themes_name'];?></td>
	 <td><?php if(empty($list['hos_name'])){ echo "-"; }else{echo $list['hos_name'];}?></td>
	 <td><?php if(empty($list['keshi_name'])){ echo "-"; }else{echo $list['keshi_name'];}?></td>
	 <td><button class="btn btn-primary" onClick="go_url('?c=system&m=sms_themes_info&themes_id=<?php echo $list['themes_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=system&m=sms_themes_del&themes_id=<?php echo $list['themes_id']?>')"><i class="icon-trash"></i></button></td>
 </tr>
<?php
if(!empty($list['child'])):

	foreach($list['child'] as $val):
         
?>
<tr>
 <td>&nbsp;</td>
 <td><span class="td_child"></span><?php echo $val['from_name'];?></td>
 <td><?php if(empty($val['hos_name'])){ echo "-"; }else{echo $val['hos_name'];}?></td>
 <td><?php if(empty($val['keshi_name'])){ echo "-"; }else{echo $val['keshi_name'];}?></td>
 <td><?php echo $val['from_order'];?></td>
 <td><button class="btn btn-primary" onClick="go_url('?c=order&m=from_info&from_id=<?php echo $val['from_id']?>')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del('?c=order&m=from_del&from_id=<?php echo $val['from_id']?>')"><i class="icon-trash"></i></button></td>
</tr>
<?php
	endforeach;
endif;
	endforeach;
endif;
?>
<tr><td colspan="6" style="text-align:center;"><?php echo $page?></td></tr>
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
$(document).ready(function(e) {
	$("#hos_id").change(function(){
		$.ajax({
			type:'post',
			url:'?c=order&m=keshi_list_ajax',
			data:'hos_id=' + $("#hos_id").val() + '&check_id=' + 0,
			success:function(data){
				$("#keshi_id").html(data);
			},
			complete: function (XHR, TS){
				XHR = null;
			} 
		}); 
	}); 
});
</script>

</body>
</html>