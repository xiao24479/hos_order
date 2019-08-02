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
      <div class="widget orange">
        <div class="widget-title">
          <h4><i class="icon-reorder"></i> 列表框</h4>
          <span class="tools"> <a href="javascript:;" class="icon-chevron-down"></a> <a href="javascript:;" class="icon-remove"></a> </span> </div>
        <div class="widget-body">
          <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-hover">
          <tr><td colspan="5"><form method="post" id="s_search" action="/?c=swt&m=swt_search" style="padding-top:7px;padding-bottom:7px;margin:0;"><input type="text" name="key" class="input-xlarge" style="margin:0" /> <input type="hidden" name="catid" value="1" id="catid" /><input type="submit" id="s_title" value="搜<?php echo $this->lang->line('swt_title');?>" class="btn btn-success" /><input type="button" class="btn" id="s_item" value="搜<?php echo $this->lang->line('item_title');?>" /></form></td></tr>
            <tr>
              <th width="80"><?php echo $this->lang->line('swt_id');?></th>
              <th width="250"><?php echo $this->lang->line('swt_title');?></th>
              <th><?php echo $this->lang->line('item_title');?></th>
              <th width="120"><?php echo $this->lang->line('swt_width');?></th>
              <th width="150"><?php echo $this->lang->line('swt_action');?></th>
            </tr>
            <?php foreach($swt_list as $v){ ?>
            <tr>
              <td><?php echo $v['swt_id'];?></td>
              <td valign="middle"><input type="text" class="btn_text" value="<?php echo $v['swt_title'];?>" /></td>
              <td><a href="javascript:;" class="search_a"><?php foreach($item_list as $r){ if($r['item_id']==$v['item_id']) echo $r['item_title'];} ?></a></td>
              <td><?php echo $v['swt_width'];?></td>
              <td><a href="/?c=swt&m=swt_edit&swt_id=<?php echo $v['swt_id'];?>" target="_blank" class="btn btn-primary"><?php echo $this->lang->line('swt_edit');?></a>&nbsp;<a href="/?c=swt&m=swt_del&swt_id=<?php echo $v['swt_id'];?>" class="btn btn-danger"><?php echo $this->lang->line('swt_del');?></a></td>
            </tr>
            <?php } ?>
          </table>
          <div class="page_div"><?php if(!empty($pages)) echo $pages;?></div>
        </div>
      </div>
      <!-- END PAGE CONTENT-->
    </div>
    <!-- END PAGE CONTAINER-->
  </div>
  <!-- END PAGE -->
</div>
<script src="static/js/jquery.js"></script>
<script type="text/javascript">
  $(function(){
      $("#s_item").click(function(){
        $("#catid").val('2');
        $("#s_search").submit();
        });
      $("a.search_a").click(function(){
        $("input.input-xlarge").val($(this).text());
        $("#catid").val('2');
        $("#s_search").submit();
        });
      var val_old; 
      $(".btn_text").hover(function(){
        val_old = $(this).val();
        $(this).css({'background':'#FFF','border':'1px solid #AAA'});
          },
          function(){
          $(this).css({'background':'none','border':'1px solid #FFF'});
          var val_new = $(this).val();
          if(val_new!=val_old){
            var swt_id = $(this).parent('td').prev('td').text();
            $.ajax({
                url: '/?c=swt&m=swt_field',
                type: 'post',
                dataType: 'json',
                data: 'swt_id='+swt_id+'&swt_title='+val_new,
                async: false,//同步传输才能给外面的变量传值
                success: function(json){
                if(json.status>0)
                  alert('客服名称修改成功');
                else
                  alert('客服名称修改失败');
                },
                error: function(){
                    alert('数据调用出错!');
                  }
              });  
            }

          });
    });
</script>
<style type="text/css">
  input.btn_text {background:none;border:none;padding:0;margin:0;height:30px;width:220px;}
</style>
<script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<!-- ie8 fixes -->
<!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
<script src="static/js/common-scripts.js"></script>
</body>
</html>
