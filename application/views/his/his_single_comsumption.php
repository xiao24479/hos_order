<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <h1 class="text-center">消费记录</h1>
          <h4>总共消费：<b><?php echo number_format($info['mz']['ss_sum']+$info['zy']['ss_sum'],2,'.',','); ?></b></h4>
          <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#mz" aria-controls="mz" role="tab" data-toggle="tab">门诊费用</a></li>
              <li role="presentation"><a href="#zy" aria-controls="zy" role="tab" data-toggle="tab">住院费用</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content" style="margin-top: 20px;">
              <div role="tabpanel" class="tab-pane fade in active" id="mz">
                <table class="table table-bordered table-hover table-condensed">
                  <thead>
                    <tr>
                      <th scope="col">缴费时间</th>
                      <th scope="col">药品</th>
                      <th scope="col">单价</th>
                      <th scope="col">数量</th>
                      <th scope="col">应收金额</th>
                      <th scope="col">实收金额</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($info['mz'])): ?>
                      <!-- 门诊 -->
                      <tr>
                        <th colspan="4" class="info">门诊费用总额</th>
                        <td class="danger"><?php echo  number_format($info['mz']['ys_sum'],2,'.',','); ?></td>
                        <th class="danger"><?php echo  number_format($info['mz']['ss_sum'],2,'.',','); ?></th>
                      </tr>
                      <?php foreach ($info['mz']['sub'] as $key => $value): ?>
                      <tr>
                        <td><?php echo date('Y-m-d H:i:s',strtotime($value['d_time'])); ?></td>
                        <td><?php echo $value['c_name']; ?></td>
                        <td><?php echo $value['d_cost']; ?></td>
                        <td><?php echo $value['i_num']; ?></td>
                        <td><?php echo $value['d_ys_sum']; ?></td>
                        <td><?php echo $value['d_ss_sum']; ?></td>
                      </tr>
                      <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td style="color: red;text-align: center;" colspan="6">还没消费记录呢！</td>
                        </tr>
                      <?php endif; ?>

                  </tbody>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane" id="zy">
                <table class="table table-bordered table-hover table-condensed">
                  <thead>
                    <tr>
                      <th scope="col">缴费时间</th>
                      <th scope="col">药品</th>
                      <th scope="col">单价</th>
                      <th scope="col">数量</th>
                      <th scope="col">应收金额</th>
                      <th scope="col">实收金额</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($info['zy'])): ?>
                      <!-- 住院 -->
                      <tr>
                        <th colspan="4" class="info">住院费用总额</th>
                        <td class="danger"><?php echo  number_format($info['zy']['ys_sum'],2,'.',','); ?></td>
                        <th class="danger"><?php echo  number_format($info['zy']['ss_sum'],2,'.',','); ?></th>
                      </tr>
                      <?php foreach ($info['zy']['sub'] as $key => $value): ?>
                      <tr>
                        <td><?php echo date('Y-m-d H:i:s',strtotime($value['d_time'])); ?></td>
                        <td><?php echo $value['c_name']; ?></td>
                        <td><?php echo $value['d_cost']; ?></td>
                        <td><?php echo $value['i_num']; ?></td>
                        <td><?php echo $value['d_ys_sum']; ?></td>
                        <td><?php echo $value['d_ss_sum']; ?></td>
                      </tr>
                      <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td style="color: red;text-align: center;" colspan="6">还没消费记录呢！</td>
                        </tr>
                      <?php endif; ?>

                  </tbody>
                </table>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
  </body>
</html>