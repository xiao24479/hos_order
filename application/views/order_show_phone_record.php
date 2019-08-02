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
            <input type="hidden" value="showPhoneRecord" name="m">
            <div class="layui-form-item">

              <div class="layui-inline">
                <label class="layui-form-label">选择日期</label>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date" value="<?php echo $date;?>" placeholder="请选择日期" autocomplete="off" class="layui-input" readonly>
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

        <div class="layui-form">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>岗位</th>
                        <th>用户名</th>
                        <th>操作人</th>
                        <th>次数</th>
                        <th>详细</th>
                    </tr>
                </thead>
                <tbody>
                  <?php if(!empty($info)):?>
                  <?php foreach ($info as $key => $item):?>
                  <tr>
                    <td><?php echo $item['rank_name']; ?></td>
                    <td><?php echo $item['admin_username']; ?></td>
                    <td><?php echo $item['admin_name']; ?></td>
                    <td><?php echo $item['number']; ?></td>
                    <td><button class="layui-btn layui-btn-sm showDetail" aid="<?php echo $item['admin_id']; ?>" user="<?php echo $item['admin_name']; ?>" date="<?php echo $date; ?>" ><i class="layui-icon layui-icon-tips"></i></button></td>
                  </tr>
                  <?php endforeach;?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" style="color: red" class="text-center">找不到数据呢！</td>
                    </tr>
                  <?php endif;?>
                </tbody>
            </table>
        </div>

        <?php echo $page; ?>

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
        ,range: true
        ,format: 'yyyy年M月d日'
        ,trigger: 'click'
        ,theme: 'molv'
      });


      var active = {
        export: function(){
          window.location.href = '?c=order&m=showPhoneRecordExport<?php echo $parse; ?>';
        }
      };

      $('#export').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
      });

    })

    $(".showDetail").click(function(event) {
      var aid = $(this).attr('aid');
      var date = $(this).attr('date');
      var user = $(this).attr('user');

      $.post('?c=order&m=showPhoneDetailRecord', {aid: aid, date: date}, function(data, textStatus, xhr) {
        if (data.code == 1) {
          var __HTML='';
          __HTML += "<div class=\"layui-form\" style=\"padding:0 20px;\"><table class=\"layui-table\"><thead><tr><th>时间</th><th>预约号</th><th>医院</th><th>科室</th><th>电话</th></tr></thead><tbody>";
          $.each(data.data,function(i,item){
            __HTML +="<tr oid="+item.order_id+"><td>"+item.add_time+"</td><td>"+item.order_no+"</td><td>"+item.hos_name+"</td><td>"+item.keshi_name+"</td><td>"+item.pat_phone+"/"+item.pat_phone1+"</td></tr>";
          });
          __HTML += "</tbody></table></div>";
          layer.open({
            type: 1,
            scrollbar: false,
            title:'<font color=red>'+user+'</font>&nbsp;&nbsp;点击显示过的患者电话',
            skin: 'layui-layer-rim', //加上边框
            area: ['800px;', '600px'], //宽高
            content: __HTML
          });
        }
      });
    });


  </script>




  </body>
</html>
