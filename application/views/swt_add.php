<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title><?php echo $title; ?></title>
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
      <div class="widget green">
        <div class="widget-title">
          <h4><i class="icon-reorder"></i> 输入表单框</h4>
          <span class="tools"> <a href="javascript:;" class="icon-chevron-down"></a> <a href="javascript:;" class="icon-remove"></a> </span> </div>
        <div class="widget-body">
          <form method="post" enctype="multipart/form-data" class="form-horizontal" target="_blank" action="/c=swt&m=upload">
            <div class="control-group">
              <label class="control-label"><?php echo $this->lang->line('swt_title');?></label>
              <div class="controls">
                <input type="text" class="input-xlarge if_no" name="swt_title" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"><?php echo $this->lang->line('swt_width');?></label>
              <div class="controls">
                <input type="text" class="input-xlarge if_no" value="1000" name="swt_width" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label"><?php echo $this->lang->line('swt_img');?></label>
              <div class="controls">
                <input type="file" class="input-xlarge if_no" name="userfile" />
                <span class="my_btn">+</span> </div>
            </div>
            <div class="control-group">
              <label class="control-label"><?php echo $this->lang->line('item_title');?></label>
              <div class="controls">
                <select name="item_id" style="width:auto">
                  <?php foreach($item_list as  $r){ ?>
                  <option value="<?php echo $r['item_id'];?>"><?php echo $r['item_title'];?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">
                <input type="submit" class="btn btn-success" value="上传" />
                <input type="reset" class="btn" value="重置" />
              </div>
            </div>
          </form>
        </div>
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
<script src="static/js/bootstrap.min.js"></script>
<!-- ie8 fixes -->
<!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
<script src="static/js/common-scripts.js"></script>
<script type="text/javascript">
     $(function(){
         $("input.btn-success").click(function(){
           var no_action = '';
           $("input.if_no").each(function(){
             if($(this).val()==''){
             no_action = 1;
             return false;
              }
             });
           if(no_action==1){
            alert('请填写完整之后再提交');
            return false;
            }
           });
         var num = 1;
         $("span.my_btn").click(function(){
            $(this).closest("div.control-group").after('<div class="control-group"><label class="control-label"><?php echo $this->lang->line('swt_img');?></label><div class="controls"><input type="file" class="input-xlarge" name="userfile'+num+'" /></div></div>');
            num +=1;
           });
       });
   </script>
</body>
</html>
