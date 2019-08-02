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

<link href="static/css/font-awesome.css" rel="stylesheet" />

<link href="static/css/style.css" rel="stylesheet" />

<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />

<link rel="stylesheet" href="static/vendor/layui/css/layui.css"  media="all">

<style>
  body{background-color: #fff;}
  a{text-decoration: none;}
  select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input{height:38px;padding-left: 10px;margin-bottom:0;background-color: #fff;}
  .layui-form-item .layui-input-inline{width:250px !important;}
</style>

</head>

<body class="fixed-top" style="width:100%;margin:0 auto; ">

  <!--遮罩层使用 div -->
  <div id="mask" class="mask"></div>


  <?php echo $top; ?>

  <div id="container" class="row-fluid" >

    <?php echo $sider_menu; ?>

    <div id="main-content" style="background-color:#fff;<?php if (!empty($hos_id)) {echo "overflow-x: hidden;";}?>">

      <!-- BEGIN PAGE CONTAINER-->
      <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <?php echo $themes_color_select; ?>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">
          <form class="layui-form layui-form-pane" action="" method="get" style="margin-top: 20px;" >
            <input type="hidden" value="order" name="c">
            <input type="hidden" value="logRecord" name="m">
            <div class="layui-form-item">

              <div class="layui-inline">
                <label class="layui-form-label">选择日期</label>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date" value="<?php echo $date;?>" placeholder="请选择日期" autocomplete="off" class="layui-input" readonly>
                </div>
              </div>

              <div class="layui-inline">
                <label class="layui-form-label">项目</label>
                <div class="layui-input-block">
                  <select name="hos_id">
                    <option value="">选择项目</option>
                    <?php if (!empty($hospital)) { foreach ($hospital as $key => $value) { ?>
                      <option value="<?php echo $value['hos_id']; ?>" <?php if ($hos_id == $value['hos_id']) { echo "selected";} ?>><?php echo $value['hos_name'] ?></option>
                    <?php }} ?>
                  </select>
                </div>
              </div>
              <div class="layui-inline" style="margin-bottom: 10px;">
                <div class="layui-input-inline">
                  <button class="layui-btn" lay-submit="" lay-filter="demo1">搜索</button>
                  <?php if (!empty($hos_id)) {?>
                  <button data-type="export" type="button" class="layui-btn" id="export" >导出</button>
                  <?php } ?>
                </div>
              </div>
            </div>
          </form>
        </div>

        <?php if (!empty($hos_id)) {?>

        <div class="layui-form">
            <table class="layui-table">
                <colgroup>
                  <col width="150">
                  <col width="100">
                  <col width="150">
                  <col width="120">
                </colgroup>
                <thead>
                    <tr>
                        <th>项目</th>
                        <th>预约号</th>
                        <th>操作时间</th>
                        <th>操作人</th>
                        <th>详细</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($info)) {?>
                <?php foreach ($info as $key => $infos):?>
                    <?php $num = count($infos); ?>
                    <?php foreach ($infos as $k => $item):?>
                    <tr>
                    <?php if ($item['order_id'] == $key && $k == 0 && $num > 1) { ?>
                        <td rowspan= <?php echo $num; ?> ><?php echo $item['hos_name']; ?></td>
                        <td rowspan= <?php echo $num; ?> ><?php echo $item['order_no']; ?></td>
                    <?php } elseif ($item['order_id'] == $key && $k != 0 && $num > 1) { ?>

                    <?php } else { ?>
                        <td ><?php echo $item['hos_name']; ?></td>
                        <td ><?php echo $item['order_no']; ?></td>
                    <?php }?>
                        <td><?php echo date('Y-m-d H:i',$item['action_time']); ?></td>
                        <td><?php echo $item['action_name']; ?></td>
                        <td><?php echo $item['action_type']; ?></td>
                    </tr>
                    <?php endforeach;?>
                <?php endforeach;?>
                <?php }else{ ?>
                    <tr>
                    <td colspan="5" style="color:#f00;" align="center">根据条件没找到数据哦！</td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>

        <?php echo $page; ?>

        <?php } ?>



      </div>

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

  <script type="text/javascript" src="static/js/date.js"></script>

  <script type="text/javascript" src="static/js/daterangepicker.js"></script>

  <script src="static/js/datepicker/js/datepicker.js"></script>

  <script src="static/vendor/layui/layui.js"></script>

  <script>

    layui.use(['form', 'laydate','table','element'], function(){
      var form = layui.form
      ,layer = layui.layer
      ,laydate = layui.laydate
      ,element = layui.element;

      laydate.render({
        elem: '#date'
        ,type: 'date'
        ,range: '-'
        ,format: 'yyyy年M月d日'
        ,trigger: 'click'
      });


      var active = {
        export: function(){
          window.location.href = '?c=order&m=logRecordExport<?php echo $parse; ?>';
        }
      };

      $('#export').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
      });

      <?php if (empty($hos_id)) {?>
      layer.msg('请选择你要查询的项目！', {icon: 7,time: 2000});
      <?php } ?>

    })
  </script>




  </body>
</html>
