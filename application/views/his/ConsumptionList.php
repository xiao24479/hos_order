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
  table th,td{
    text-align: center !important;
  }
  #histopsearch{
    width: auto;
  }
  .layui-table td, .layui-table th{
    padding: 3px 5px;
  }
</style>

</head>

<body class="fixed-top" style="width:100%;margin:0 auto; ">

  <!--遮罩层使用 div -->
  <div id="mask" class="mask"></div>


  <?php echo $top; ?>

  <div id="container" class="row-fluid" >

    <?php echo $sider_menu; ?>

    <div id="main-content" style="background-color:#fff;">

      <!-- BEGIN PAGE CONTAINER-->
      <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <?php echo $themes_color_select; ?>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">
          <form class="layui-form layui-form-pane" action="" method="get" style="margin-top: 20px;" >
            <input type="hidden" value="his" name="c">
            <input type="hidden" value="ConsumptionList" name="m">
            <div class="layui-form-item">

              <div class="layui-inline">
                <label class="layui-form-label">消费年月</label>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date" value="<?php echo $date;?>" lay-verify="date" placeholder="请选择月份" autocomplete="off" class="layui-input">
                </div>
              </div>

              <div class="layui-inline">
                <label class="layui-form-label">项目</label>
                <div class="layui-input-block">
                  <select name="hos_id">
                    <option value="">选择项目</option>
                    <?php if (!empty($item)) { foreach ($item as $key => $value) { ?>
                      <option value="<?php echo $value['hos_id']; ?>" <?php if ($hos_id == $value['hos_id']) { echo "selected";} ?>><?php echo $value['hos_name'] ?></option>
                    <?php }} ?>
                  </select>
                </div>
              </div>
              <div class="layui-inline">
                <label class="layui-form-label">预约号</label>
                <div class="layui-input-inline">
                  <input type="text" name="order_no" id="order_no" value="<?php echo $order_no;?>" placeholder="请填写预约号" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-inline">
                <label class="layui-form-label">患者</label>
                <div class="layui-input-inline">
                  <input type="text" name="pat_name" id="pat_name" value="<?php echo $pat_name;?>" placeholder="请填写患者" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-inline">
                <label class="layui-form-label">咨询</label>
                <div class="layui-input-inline">
                  <input type="text" name="admin_name" id="admin_name" value="<?php echo $admin_name;?>" placeholder="请填写咨询" autocomplete="off" class="layui-input">
                </div>
              </div>
              <div class="layui-inline" style="margin-bottom: 10px;">
                <div class="layui-input-inline" id="histopsearch">
                  <button class="layui-btn">搜索</button>
                  <?php if (!empty($hos_id)) {?>
                  <!-- <button data-type="export" type="button" class="layui-btn" id="export" >导出</button> -->
                  <?php } ?>
                </div>
              </div>
            </div>
          </form>
        </div>

        <?php if (!empty($hos_id)) {?>

        <div class="layui-form" style="/*overflow: scroll;height: 700px;*/">
            <table class="layui-table">
                <thead>
                  <tr>
                      <th rowspan="2">到诊时间</th>
                      <th rowspan="2">医院</th>
                      <th rowspan="2">科室</th>
                      <th rowspan="2">预约号</th>
                      <th rowspan="2">患者</th>
                      <th rowspan="2">咨询员</th>
                      <th rowspan="2">就诊号</th>
                      <th colspan="3">金额</th>
                      <th rowspan="2">详细</th>
                  </tr>
                  <tr>
                      <th>门诊</th>
                      <th>住院</th>
                      <th>合计</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if (!empty($new_infos)): ?>
                    <?php foreach ($new_infos as $key => $value): ?>
                    <tr>
                      <td><?php echo date('Y-m-d H:i:s',$value['come_time']); ?></td>
                      <td><?php echo $value['hos_name']; ?></td>
                      <td><?php echo $value['keshi_name']; ?></td>
                      <td><?php echo $value['order_no']; ?></td>
                      <td><?php echo $value['pat_name']; ?></td>
                      <td><?php echo $value['admin_name']; ?></td>
                      <td><?php echo $value['his_jzkh']; ?></td>
                      <td><?php if(!empty($value['mz']) || !empty($value['zy'])): ?><?php echo number_format($value['mz'],2,'.',','); ?><?php else: ?>0<?php endif; ?></td>
                      <td><?php if(!empty($value['zy']) || !empty($value['zy'])): ?><?php echo number_format($value['zy'],2,'.',','); ?><?php else: ?>0<?php endif; ?></td>
                      <td><?php if(!empty($value['mz']) || !empty($value['zy'])): ?><?php echo number_format($value['ssje'],2,'.',','); ?><?php else: ?>0<?php endif; ?></td>
                      <td><a class="btn btn-success"   onClick="ajax_get_consumption('<?php echo $value['order_no'];?>','<?php echo $date;?>');"   href="javaScript:void(0);" >查看消费记录</a></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                      <th class="alert-warning">总额</th>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="alert-warning">患者数：<b><?php echo $data_num; ?></b></td>
                      <td class="alert-info">门诊：<b><?php echo $all_sum['mz']?number_format($all_sum['mz'],2,'.',','):0; ?></b></td>
                      <td class="alert-danger">住院：<b><?php echo $all_sum['zy']?number_format($all_sum['zy'],2,'.',','):0; ?></b></td>
                      <th class="alert-success"><b><?php echo number_format($all_sum['mz']+$all_sum['zy'],2,'.',','); ?></b></th>
                      <td></td>
                    </tr>
                    <?php else: ?>
                      <tr>
                        <td style="color: red;text-align: center;" colspan="11">找不到记录！</td>
                      </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php echo $page; ?>
        </div>

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
      ,table = layui.table
      ,element = layui.element;
      //常规用法
      laydate.render({
        elem: '#date'
        ,type: 'month'
        ,theme: 'grid'
      });

      var active = {
        export: function(){
          window.location.href = '?c=his&m=export_his<?php echo $parse; ?>';
        }
      };

      $('#export').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
      });

      <?php if (empty($hos_id)) {?>
      layer.msg('请选择你要查询的项目！', {icon: 7,time: 1000});
      <?php } ?>

    })

    function ajax_get_consumption(order_no,date,hos_id,keshi_id){
      var diag = new Dialog();
      diag.Width = 900;
      diag.Height = 400;
      diag.Title = "获取患者消费记录";
      diag.URL = "?c=his&m=getHisSingleComsumption&order_no="+order_no+"&date="+date;
      diag.show();
    }

  </script>




  </body>
</html>
