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

    <div id="main-content" style="background-color:#fff;<?php if (!empty($hos_id)) {echo "overflow-x: hidden;";}?>">

      <!-- BEGIN PAGE CONTAINER-->
      <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <?php echo $themes_color_select; ?>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">
          <form class="layui-form layui-form-pane" action="" method="get" style="margin-top: 20px;" >
            <input type="hidden" value="his" name="c">
            <input type="hidden" value="hindex" name="m">
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
                  <select name="his_id">
                    <option value="">选择项目</option>
                    <?php if (!empty($item)) { foreach ($item as $key => $value) { ?>
                      <option value="<?php echo $value['id']; ?>" <?php if ($his_id == $value['id']) { echo "selected";} ?>><?php echo $value['his_item'] ?></option>
                    <?php }} ?>
                  </select>
                </div>
              </div>
              <div class="layui-inline" style="margin-bottom: 10px;">
                <div class="layui-input-inline" id="histopsearch">
                  <button class="layui-btn" lay-submit="" lay-filter="demo1">搜索</button>
                  <?php if (!empty($his_id)) {?>
                  <button data-type="export" type="button" class="layui-btn" id="export" >导出</button>
                  <button data-type="checkUnmactchPhone" type="button" class="layui-btn" id="checkUnmactchPhone" >查看未匹配的患者电话</button>
                  <?php } ?>
                </div>
              </div>
            </div>
          </form>
        </div>

        <?php if (!empty($his_id)) {?>

        <div class="layui-form" style="/*overflow: scroll;height: 700px;*/">
            <table class="layui-table">
                <!-- <colgroup>
                  <col width="150">
                  <col width="100">
                  <col width="150">
                  <col width="120">
                </colgroup> -->
                <thead>
                    <tr>
                        <th rowspan="4">组名</th>
                        <th rowspan="4">姓名</th>
                        <th colspan="8">男科到诊</th>
                        <th colspan="5" rowspan="2">其他科室到诊</th>
                        <th rowspan="4">总对话</th>
                        <th colspan="6">消费</th>
                        <th rowspan="4">人均消费</th>
                        <th rowspan="4">转化率</th>
                    </tr>
                    <tr>
                      <th rowspan="3">总到诊</th>
                      <th rowspan="2" colspan="3">0-消费</th>
                      <th rowspan="3">有效</th>
                      <th colspan="3">主要渠道</th>
                      <th rowspan="3">男科初诊消费</th>
                      <th rowspan="3">男科复诊</th>
                      <th colspan="2">其它科室消费</th>
                      <th rowspan="3">男科初复诊消费</th>
                      <th rowspan="3">总消费</th>
                    </tr>
                    <tr>
                      <th rowspan="2">竞价</th>
                      <th rowspan="2">优化</th>
                      <th rowspan="2">其他</th>
                      <th rowspan="2">总</th>
                      <th rowspan="2">有效</th>
                      <th rowspan="2">妇科</th>
                      <th rowspan="2">肛肠</th>
                      <th rowspan="2">其它</th>
                      <th rowspan="2">妇科</th>
                      <th rowspan="2">肛肠科</th>
                    </tr>
                    <tr>
                      <th>竞</th>
                      <th>优</th>
                      <th>它</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($new_infos as $item): ?>
                      <?php $num = count($item['sub'])+1; ?>
                      <?php for ($n=0;$n<$num;$n++): ?>
                      <?php if ($n == 0): ?>
                        <tr>
                          <td rowspan="<?php echo $num;?>"><?php echo $item['gname'];?></td>
                          <td><?php echo $item['sub'][$n]['admin_name'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk'];?></td>
                          <td><?php echo $item['sub'][$n]['jj_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['yh_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['other_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk']-$item['sub'][$n]['jj_zero_ptient']-$item['sub'][$n]['yh_zero_ptient']-$item['sub'][$n]['other_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_jj'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_yh'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_qt'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_other_all'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_other_all'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_fk'];?></td>
                          <td></td>
                          <td><?php echo $item['sub'][$n]['come_num_other'];?></td>
                          <td><?php echo $item['sub'][$n]['dialog_num'];?></td>
                          <td><?php echo $item['sub'][$n]['cz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['fz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['fk_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['gc_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['cz_amount']+$item['sub'][$n]['fz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['amount'];?></td>
                          <td><?php echo number_format((($item['sub'][$n]['amount']/$item['sub'][$n]['come_num'])) , 2, '.', '');?></td>
                          <td><?php echo number_format((($item['sub'][$n]['come_num']/$item['sub'][$n]['dialog_num']) * 100) , 2, '.', '')."%";?></td>
                        </tr>
                      <?php elseif ($n == $num-1): ?>
                        <tr>
                          <td>小计</td>
                          <td><?php echo $item['g_come_num_nk'];?></td>
                          <td><?php echo $item['g_jj_zero_ptient'];?></td>
                          <td><?php echo $item['g_yh_zero_ptient'];?></td>
                          <td><?php echo $item['g_other_zero_ptient'];?></td>
                          <td><?php echo $item['g_come_num_nk']-$item['g_jj_zero_ptient']-$item['g_yh_zero_ptient']-$item['g_other_zero_ptient'];?></td>
                          <td><?php echo $item['g_come_num_nk_jj'];?></td>
                          <td><?php echo $item['g_come_num_nk_yh'];?></td>
                          <td><?php echo $item['g_come_num_nk_qt'];?></td>
                          <td><?php echo $item['g_come_num_other_all'];?></td>
                          <td><?php echo $item['g_come_num_other_all'];?></td>
                          <td><?php echo $item['g_come_num_fk'];?></td>
                          <td></td>
                          <td><?php echo $item['g_come_num_other'];?></td>
                          <td><?php echo $item['g_dialog_num'];?></td>
                          <td><?php echo $item['g_cz_amount'];?></td>
                          <td><?php echo $item['g_fz_amount'];?></td>
                          <td><?php echo $item['g_fk_amount'];?></td>
                          <td><?php echo $item['g_gc_amount'];?></td>
                          <td><?php echo $item['g_cz_amount']+$item['g_fz_amount'];?></td>
                          <td><?php echo $item['g_amount'];?></td>
                          <td><?php echo number_format((($item['sub'][$n]['g_amount']/$item['sub'][$n]['g_come_num'])) , 2, '.', '');?></td>
                          <td><?php echo number_format((($item['sub'][$n]['g_come_num']/$item['sub'][$n]['g_dialog_num']) * 100) , 2, '.', '')."%";?></td>
                        </tr>
                      <?php else: ?>
                        <tr>
                          <td><?php echo $item['sub'][$n]['admin_name'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk'];?></td>
                          <td><?php echo $item['sub'][$n]['jj_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['yh_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['other_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk']-$item['sub'][$n]['jj_zero_ptient']-$item['sub'][$n]['yh_zero_ptient']-$item['sub'][$n]['other_zero_ptient'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_jj'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_yh'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_nk_qt'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_other_all'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_other_all'];?></td>
                          <td><?php echo $item['sub'][$n]['come_num_fk'];?></td>
                          <td></td>
                          <td><?php echo $item['sub'][$n]['come_num_other'];?></td>
                          <td><?php echo $item['sub'][$n]['dialog_num'];?></td>
                          <td><?php echo $item['sub'][$n]['cz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['fz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['fk_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['gc_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['cz_amount']+$item['sub'][$n]['fz_amount'];?></td>
                          <td><?php echo $item['sub'][$n]['amount'];?></td>
                          <td><?php echo number_format((($item['sub'][$n]['amount']/$item['sub'][$n]['come_num'])) , 2, '.', '');?></td>
                          <td><?php echo number_format((($item['sub'][$n]['come_num']/$item['sub'][$n]['dialog_num']) * 100) , 2, '.', '')."%";?></td>
                        </tr>
                      <?php endif; ?>
                      <?php endfor; ?>
                    <?php endforeach; ?>
                    <tr>
                      <td colspan="2">合计</td>
                      <td><?php echo $all_info['all_come_num_nk'];?></td>
                      <td><?php echo $all_info['all_jj_zero_ptient'];?></td>
                      <td><?php echo $all_info['all_yh_zero_ptient'];?></td>
                      <td><?php echo $all_info['all_other_zero_ptient'];?></td>
                      <td><?php echo $all_info['all_come_num_nk']-$all_info['all_jj_zero_ptient']-$all_info['all_yh_zero_ptient']-$all_info['all_other_zero_ptient'];?></td>
                      <td><?php echo $all_info['all_come_num_nk_jj'];?></td>
                      <td><?php echo $all_info['all_come_num_nk_yh'];?></td>
                      <td><?php echo $all_info['all_come_num_nk_qt'];?></td>
                      <td><?php echo $all_info['all_come_num_other_all'];?></td>
                      <td><?php echo $all_info['all_come_num_other_all'];?></td>
                      <td><?php echo $all_info['all_come_num_fk'];?></td>
                      <td></td>
                      <td><?php echo $all_info['all_come_num_other'];?></td>
                      <td><?php echo $all_info['all_dialog_num'];?></td>
                      <td><?php echo $all_info['all_cz_amount'];?></td>
                      <td><?php echo $all_info['all_fz_amount'];?></td>
                      <td><?php echo $all_info['all_fk_amount'];?></td>
                      <td><?php echo $all_info['all_gc_amount'];?></td>
                      <td><?php echo $all_info['all_cz_amount']+$all_info['all_fz_amount'];?></td>
                      <td><?php echo $all_info['all_amount'];?></td>
                      <td><?php echo number_format((($all_info['all_amount']/$all_info['all_come_num'])) , 2, '.', '');?></td>
                      <td><?php echo number_format((($all_info['all_come_num']/$all_info['all_dialog_num']) * 100) , 2, '.', '')."%";?></td>
                    </tr>
                </tbody>
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

      var __HTML ="<div class=\"layui-form\" style=\"padding:0 20px;\"><table class=\"layui-table\"><thead><tr><th>小组</th><th>咨询员</th><th>电话</th></tr></thead><tbody><?php foreach ($new_infos as $item): ?><?php foreach ($item['sub'] as $val): ?><?php if(!empty($val['un_match_phone'])): ?><tr><td><?php echo $val['gname'];?></td><td><?php echo $val['admin_name'];?></td><td><?php echo $val['un_match_phone'];?></td></tr><?php endif; ?><?php endforeach; ?><?php endforeach; ?></tbody></table></div>";

      var active = {
        export: function(){
          window.location.href = '?c=his&m=export_his<?php echo $parse; ?>';
        },
        checkUnmactchPhone: function(){
          layer.open({
            type: 1,
            title:'未匹配的患者电话<font color=red>&nbsp;&nbsp;(HIS系统查不到的患者电话号码)</font>',
            skin: 'layui-layer-rim', //加上边框
            area: ['auto', '600px'], //宽高
            content: __HTML
          });
        }
      };

      $('#export').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
      });

      $('#checkUnmactchPhone').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
      });

      <?php if (empty($his_id)) {?>
      layer.msg('请选择你要查询的项目！', {icon: 7,time: 1000});
      <?php } ?>

    })

  </script>




  </body>
</html>
