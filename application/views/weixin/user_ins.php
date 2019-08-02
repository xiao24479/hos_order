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
<meta http-equiv="refresh" content="1;url=<?php echo $default_url; ?>"> 
<style>
.ProgressBar {    
position: relative;    
width: 600px;    /* ��� */   
border: 1px solid #B1D632;    
padding: 1px;
margin:0 auto;    
}    
.ProgressBar div {    
display: block;    
position: relative;    
background: #B1D632;    
color: #333333;    
height: 20px; /* �߶� */   
line-height: 20px;  /* ����͸߶�һ�£��ı����ܴ�ֱ���� */   
}    
.ProgressBar div span {     
position: absolute;    
width: 200px; /* ��� */   
text-align: center;    
font-weight: bold;    
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
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
                            <div class="widget-body">
							
							<div class="ProgressBar">    
<div style="width: <?php echo $page_log;?>%;"><span><?php echo $page_log;?>%</span></div>    
</div>    

							

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
   <!--��̬���JS-->
   <script type="text/javascript" src="static/js/jquery.dataTables.js"></script>
   <script type="text/javascript" src="static/js/DT_bootstrap.js"></script>
   <script src="static/js/dynamic-table.js"></script>
   <!--��̬���JS-->
   <script src="static/js/common-scripts.js"></script>
</body>
</html>