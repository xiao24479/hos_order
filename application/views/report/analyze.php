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
            <input type="hidden" value="report" name="c">
            <input type="hidden" value="analyze" name="m">
            <div class="layui-form-item">

              <div class="layui-inline">
                <label class="layui-form-label">年月</label>
                <div class="layui-input-inline">
                  <input type="text" name="date" id="date" value="<?php echo $date;?>" lay-verify="date" placeholder="请选择月份" autocomplete="off" class="layui-input">
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
              <div class="layui-inline">
                <label class="layui-form-label">科室</label>
                <div class="layui-input-block">
                  <input type="radio" name="type" value="1" title="男科" <?php if ($type == '1' || empty($type)) { echo "checked";} ?>>
                  <input type="radio" name="type" value="2" title="妇科" <?php if ($type == '2') { echo "checked";} ?>>
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

        <?php if (!empty($hos_id) && $type == 1) {?>

        <div class="row-fluid">
          <table class="layui-table" lay-data="{height: 'full-220', url:'?c=report&m=bz_area_ajax<?php echo $parse; ?>', cellMinWidth: 80, id:'andata'}" lay-filter="andata">
            <thead>
              <tr>
                <th lay-data="{field:'num',align:'center'}" rowspan="3" ><?php echo $default_month; ?></th>
                <th lay-data="{align:'center'}" colspan="4">区域分析</th>
                <th lay-data="{align:'center'}" colspan="<?php echo $bz_col; ?>">病种分析</th>
              </tr>
              <tr>
                <th lay-data="{field:'one_word', edit: 'text',align:'center'}" rowspan="2">一句话</th>
                <th lay-data="{field:'two_word', edit: 'text',align:'center'}" rowspan="2">三句话</th>
                <th lay-data="{field:'more_word', edit: 'text',align:'center'}" rowspan="2">三句话以上</th>
                <th lay-data="{field:'all_word',align:'center'}" rowspan="2">总对话</th>
                <?php echo $table_bz_header_top; ?>
              </tr>
              <tr>
                <?php echo $table_bz_header_bot; ?>
              </tr>
            </thead>
          </table>
        </div>

        <?php } ?>



        <?php if (!empty($hos_id) && $type == 2) {?>

        <div class="row-fluid">
          <table class="layui-table" lay-data="{height: 'full-220', url:'?c=report&m=bz_area_ajax<?php echo $parse; ?>', cellMinWidth: 80, id:'andata'}" lay-filter="andata">
            <thead>
              <tr>
                <th lay-data="{field:'num',align:'center'}" rowspan="3" ><?php echo $default_month; ?></th>
                <th lay-data="{align:'center'}" colspan="4">区域分析</th>
                <th lay-data="{align:'center'}" colspan="<?php echo $bz_col; ?>">病种分析</th>
              </tr>
              <tr>
                <th lay-data="{field:'local_city', edit: 'text',align:'center'}" rowspan="2">本市对话</th>
                <th lay-data="{field:'local_province', edit: 'text',align:'center'}" rowspan="2">本省对话</th>
                <th lay-data="{field:'other_way', edit: 'text',align:'center'}" rowspan="2">其他</th>
                <th lay-data="{field:'all_word',align:'center'}" rowspan="2">合计</th>
                <?php echo $table_bz_header_top; ?>
              </tr>
              <tr>
                <?php echo $table_bz_header_bot; ?>
              </tr>
            </thead>
          </table>
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

      //监听单元格编辑
      table.on('edit(andata)', function(obj){
        var value = obj.value //得到修改后的值
        ,data = obj.data //得到所在行所有键值
        ,field = obj.field; //得到字段
        console.log(field);
        var ivalue=parseInt(value);
        var patrn=/^[0-9]+$/;
        if (!patrn.exec(ivalue)) {
          layer.msg('只能是整数', {icon: 2,time: 1000});
          setTimeout(function(){
            window.location.reload();
          },1000);
          return false;
        }
        var type,msg_true,msg_false;
        if (data.id) {//1.编辑 2.添加
          type='1';
          msg_true='编辑成功！';
          msg_false='编辑失败！';
        } else {
          type='2';
          msg_true='添加成功！';
          msg_false='添加失败！';
        }

        var fieldtxt = 'bz_name_';
        if (field.indexOf(fieldtxt)>-1) {//病种分析
          $.ajax({//异步请求返回给后台
            url:'?c=report&m=bz_data_ajax',
            type:'post',
            data:{t:data.num,d:'<?php if (!empty($date)){echo $date;} ?>',field:field,value:value,hos_id:'<?php if (!empty($hos_id)){echo $hos_id;} ?>',ks_type:'<?php if (!empty($type)){echo $type;} ?>'},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg(res.msg, {icon: 1,time: 1000});
                // setTimeout(function(){
                //   window.location.reload();
                // },1000);
              } else {
                layer.msg(res.msg, {icon: 2,time: 1000});
                setTimeout(function(){
                  window.location.reload();
                },1500);
              }
            }
          });
        } else {//区域分析
          $.ajax({//异步请求返回给后台
            url:'?c=report&m=add_data_ajax',
            type:'post',
            data:{id:data.id,t:data.num,d:'<?php if (!empty($date)){echo $date;} ?>',type:type,field:field,value:value,hos_id:'<?php if (!empty($hos_id)){echo $hos_id;} ?>',ks_type:'<?php if (!empty($type)){echo $type;} ?>'},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg(msg_true, {icon: 1,time: 1000});
                // setTimeout(function(){
                //   window.location.reload();
                // },1000);
              } else {
                layer.msg(msg_false, {icon: 2,time: 1000});
                setTimeout(function(){
                  window.location.reload();
                },1500);
              }
            }
          });
        }

      });

      var active = {
        export: function(){
          window.location.href = '?c=report&m=export_analysis<?php echo $parse; ?>';
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
