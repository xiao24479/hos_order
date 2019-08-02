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

.a{ position:absolute; top:55px; left:0; height:185px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}

.b{ position:absolute; top:273px; left:0; height:40px; width:100%;filter:alpha(Opacity=100);-moz-opacity:0.1;opacity: 0.1;z-index:100; background-color:#fff;}

 
.remark{height:auto; width:auto; overflow:hidden; position:relative; top:0px; left:0px;}

.widget-body{ margin:0; padding:0;}

.form-horizontal{ padding:20px 0; margin:0;}

.row-fluid{}

.span6{margin:0;}

.span5{margin:0; float:right;}

.from_bottom{z-index:999; position:fixed; bottom:0; left:0; width:100%; height:30px; background-color:#f0f0f0; padding:10px 0 10px 90px; border-top:1px solid #ccc;}

.from_bottom button{ margin-right:20px;}

</style>
 
</head>



<body > 
         <?php if(!empty($info)): ?>
             <form onSubmit="return chkForm();" action="?c=order&m=order_update_liulian_setok" method="post" class="form-horizontal" style="position:relative;">
				  <table><tr><td>预约编号:<input type="text" value="<?php echo $info['order_no_yy'];?>"  class="input-large" name="order_no_yy" id="order_no_yy"/></td>
				  <td>  <input type="hidden" name="order_id" id="order_id" value="<?php echo $info['order_id'];?>" /> 
				    <button type="submit" id="submit" class="btn btn-danger"><i class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?> </button>
				    <button type="reset" class="btn btn-inverse"><i class="icon-remove"></i> <?php echo $this->lang->line('reset'); ?> </button></td></tr></table>
			</form> 
		<?php endif; ?>  
<script src="static/js/jquery.js"></script>
<script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<script charset="utf-8" src="static/js/clockface.js"></script>
<script src="static/js/common-scripts.js"></script>

<script>
// 验证
function chkForm()
{
	if($("#order_no_yy").val() == 0 || $("#order_no_yy").val() == '' || $("#order_no_yy").val() == null)

	{

		alert("请输入预约编号！");

		return false;

	}
    return true; 
} 
</script> 
</body> 
</html>