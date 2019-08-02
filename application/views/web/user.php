<!doctype html>
<html lang="en" class="no-js m">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<meta name="renderer" content="webkit">
<title>会员中心 - <?php echo $hos_name;?></title>
<meta name="description" content="">
<link rel="alternate icon" type="image/png" href="i/favicon.png">
<link rel="icon" type="image/svg+xml" href="i/favicon.svg"/>
<link rel="apple-touch-icon-precomposed" href="i/app-icon72x72@2x.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="stylesheet" href="static/phone/css/amui.all.min.css">
<script src="static/phone/js/jquery.min.js"></script>
<script src="static/phone/js/handlebars.min.js"></script>
 <script src="http://cdn.amazeui.org/amazeui/2.0.0/js/amazeui.min.js"></script>
</head>
<body>
<?php $this->load->view('web/head');?>
<div data-am-widget="tabs" class="am-tabs am-tabs-d2">
  <ul class="am-tabs-nav am-cf">
    <li class="am-active"> <a href="[data-tab-panel-0]">我的预约</a> </li>
    <li class=""> <a href="[data-tab-panel-1]">病历报告</a> </li>
    <li class=""> <a href="[data-tab-panel-2]">个人资料</a> </li>
  </ul>
  <div class="am-tabs-bd">
    <div data-tab-panel-0 class="am-tab-panel am-active">
      <table class="am-table">
        <thead>
          <tr>
            <th>名称</th>
            <th>内容</th>
          </tr>
        </thead>
        <tbody>
		
		<?php foreach($order as $val):?>
		<tr>
			<td>预约号</td><td><?php echo $val['order_no'];?></td>
		</tr>
		<tr>
			<td>姓名</td><td><?php echo $val['pat_name']?></td>
		</tr>
		<tr>
			<td>登记时间</td><td><?php echo date('Y-m-d H:i',$val['order_addtime']);?></td>
		</tr>
		<?php if($val['order_time']):?>
		<tr>
			<td>预约时间</td><td>预计<?php echo date('Y-m-d',$val['order_time']);?><?php if($val['order_time_duan']): echo $val['order_time_duan'];?><?php endif;?>就诊</td>
		</tr>
		<?php endif;?>
		<?php if($val['is_come']):?>
		<tr>
			<td>就诊时间</td><td><?php echo date('Y-m-d',$val['come_time']);?></td>
		</tr>
		<?php endif;?>
		<tr>
			<td>预约科室</td><td><?php echo $val['keshi_name'];?></td>
		</tr>
		<tr>
			<td>状态</td><td><?php if($val['is_come']):?>已来院<?php else:?>未来院<?php if(!empty($val['name'])){echo '<span style="color:red;">(已取消预约)</span>';}?><?php endif;?></td>
		</tr>
		<?php if(!$val['is_come']):?>
		<tr>
			<td>操作</td><td><button class="am-btn am-btn-success doc-prompt-toggle" >修改</button>&nbsp;&nbsp;&nbsp;<input class="order_id" type="hidden" value="<?php echo $val['order_id'];?>"><?php if(empty($val['name'])):?><button class="am-btn am-btn-success cancel" >取消来院</button><?php endif;?></td>
			
		</tr>
		<?php endif;?>
		<?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div data-tab-panel-1 class="am-tab-panel ">
		建设中.....
     <!-- <table class="am-table">
        <thead>
          <tr>
            <th>时间</th>
            <th>检查报告</th>
            <th>结果</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>2014-10-01</td>
            <td><a href="#">唐氏筛查报告</a></td>
            <td><span class="am-badge am-badge-success">正常</span></td>
          </tr>
          <tr>
            <td>2014-10-01</td>
            <td><a href="#">唐氏筛查报告</a></td>
            <td><span class="am-badge am-badge-warning">高风险</span></td>
          </tr>
          <tr>
            <td>2014-10-01</td>
            <td><a href="#">唐氏筛查报告</a></td>
            <td><span class="am-badge am-badge-danger">重检</span></td>
          </tr>
          <tr>
            <td>2014-10-01</td>
            <td><a href="#">唐氏筛查报告</a></td>
            <td><span class="am-badge am-badge-success">正常</span></td>
          </tr>
        </tbody>
      </table>-->
    </div>
    <div data-tab-panel-2 class="am-tab-panel ">
      <table class="am-table">
        <thead>
          <tr>
            <th>项目</th>
            <th>内容</th>
          </tr>
        </thead>
		<?php if(isset($user_wx_info)):?>
        <tbody>
          <tr>
            <td>姓名</td>
            <td><?php echo $user_wx_info['username'];?></td>
          </tr>
          <tr>
            <td>性别</td>
            <td><?php if($user_wx_info['sex']==1):?>男<?php else:?>女<?php endif;?></td>
          </tr>
          <tr>
            <td>年龄</td>
            <td><?php echo $user_wx_info['age'];?></td>
          </tr>
          <tr>
            <td>手机</td>
            <td><?php echo $user_wx_info['phone'];?></td>
          </tr>
          <tr>
            <td>QQ</td>
            <td><?php echo $user_wx_info['qq'];?></td>
          </tr>
          <tr>
            <td>邮箱</td>
            <td><?php echo $user_wx_info['email'];?></td>
          </tr>
        </tbody>
		<?php else:?>
		<tbody>
          <tr>
            <td>姓名</td>
            <td><?php echo $order[0]['pat_name'];?></td>
          </tr>
          <tr>
            <td>性别</td>
            <td><?php if($order[0]['pat_sex']==1):?>男<?php else:?>女<?php endif;?></td>
          </tr>
          <tr>
            <td>年龄</td>
            <td><?php echo $order[0]['pat_age'];?></td>
          </tr>
          <tr>
            <td>手机</td>
            <td><?php echo $order[0]['pat_phone'];?></td>
          </tr>
          <tr>
            <td>QQ</td>
            <td></td>
          </tr>
          <tr>
            <td>邮箱</td>
            <td></td>
          </tr>
        </tbody>
		
		<?php endif;?>
      </table>
      <a href="?c=show&m=user_edit&pat_id=<?php echo $order[0]['pat_id']?>"><button type="submit" class="am-btn am-btn-default am-btn-block">修改</button></a>
    </div>
  </div>
</div>
		<div class="am-modal am-modal-prompt" tabindex="-1" id="my-prompt">
			<div class="am-modal-dialog">
				<div class="am-modal-hd">预约修改</div>
				<div class="am-modal-bd">
					修改您的来院时间
					<input type="text" id="time" class="am-modal-prompt-input" readOnly onClick="WdatePicker()">
				</div>
				<div class="am-modal-footer">
				  <span class="am-modal-btn" data-am-modal-cancel>取消</span>
				  <span class="am-modal-btn" data-am-modal-confirm>提交</span>
				</div>
			</div>
		</div>
<!--底部菜单-->
<?php $this->load->view('web/foot');?>
<script src="static/js/time/WdatePicker.js"></script>
<!--底部菜单-->
<script>
$(function() {
  $('.doc-prompt-toggle').click(function() {
	var order_id = $(this).next(".order_id").val();
    $('#my-prompt').modal({
      relatedElement: this,
      onConfirm: function(data) {
		var time = $('#time').val();
		if(time==''){
			alert('请选择来院时间');
			return false;
		}
        $.ajax({

			type:'post',

			url:'?c=order&m=user_time_ajax',

			data:'order_id=' + order_id + '&order_time=' + time ,

			success:function(data)

			{
				if(data == 1){
					alert('来院时间修改为'+time);
					document.location.reload();
				}else{
					alert('来院时间修改未成功');
				}
			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});       
      },
      onCancel: function() {
        XHR = null;
      }
    });
  });
  
  $('.cancel').click(function() {
	if(window.confirm('你确定要取消该预约吗？')){
                 
             
		var order_id = $(this).prev(".order_id").val();
		$.ajax({

				type:'post',

				url:'?c=order&m=user_out_ajax',

				data:'order_id=' + order_id ,

				success:function(data)

				{
					
					alert('已经取消预约');
					document.location.reload();
				},

				complete: function (XHR, TS)

				{

				   XHR = null;

				}

		}); 
	}
  });
});
</script>
</body>
</html>