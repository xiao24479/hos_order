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
          <div class="widget-body">
            <form method="post" enctype="multipart/form-data" class="form-horizontal" action="/c=swt&m=upload_edit&swt_id=<?php echo $swt_id;?>">
           <div class="control-group">
             <label class="control-label"><?php echo $this->lang->line('swt_title');?></label>
             <div class="controls">
               <input type="text" class="input-xlarge if_no" value="<?php echo $swt_arr['swt_title'];?>" name="swt_title" />
             </div>
           </div>
           <div class="control-group">
             <label class="control-label"><?php echo $this->lang->line('swt_width');?></label>
             <div class="controls">
               <input type="text" class="input-xlarge if_no" value="<?php echo $swt_arr['swt_width'];?>" name="swt_width" />
             </div>
           </div>
           <?php foreach($img_list as $k=>$v){ ?>
           <div class="control-group">
             <label class="control-label"><?php echo $this->lang->line('swt_src');?></label>
             <div class="controls">
               <input type="text" class="input-xlarge" name="swt_img[]" value="<?php echo $v;?>" /><span class="my_btn my_btn2">x</span>
             </div>
           </div>
           <div class="control-group">
             <label class="control-label"><?php echo $this->lang->line('swt_img');?></label>
             <div class="controls">
               <input name="img_id[]" value="<?php echo $k;?>" type="hidden" />
               <input type="file" class="input-xlarge" style="width:285px" name="userfile<?php echo $k;?>" /><span class="my_btn my_btn1">+</span>
             </div>
           </div>
           <?php } ?>
           <div class="control-group">
             <label class="control-label"><?php echo $this->lang->line('item_title');?></label>
             <div class="controls">
               <select name="item_id" style="width:auto">
                 <?php foreach($item_list as  $r){ ?>
                 <option value="<?php echo $r['item_id'];?>"<?php if($r['item_id']==$swt_arr['item_id']) echo ' selected';?>><?php echo $r['item_title'];?></option>
                 <?php } ?>
               </select>
             </div>
           </div>
           <div class="control-group">
             <div class="controls">
               <input type="button" class="btn btn-success" value="修改" /><input type="reset" class="btn" value="重置" />
             </div>
           </div>
           </form>
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
            $("input:file").each(function(){
              if($(this).val()=='')
                $(this).closest('div.control-group').remove();
              });
            $("form.form-horizontal").submit();
           });
         var num = <?php echo count($img_list);?>;
         $("span.my_btn1").click(function(){
            $(this).closest("div.control-group").after('<div class="control-group"><label class="control-label"><?php echo $this->lang->line('swt_img');?></label><div class="controls"><input type="file" style="width:285px" class="input-xlarge" name="userfile'+num+'" /><input type="hidden" value="'+num+'" name="img_id[]" /></div></div>');
            num +=1;
           });
         $("span.my_btn2").click(function(){
           $(this).closest('div.control-group').next().remove();
           $(this).closest('div.control-group').remove();
           });

       });
   </script>
</body>
</html>
