<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>报表病种添加</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="static/vendor/layui/css/layui.css"  media="all">
  <style>
  	.layui-input-block{
  		margin-right: 60px;
  	}
  </style>
</head>
<body>
	<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	</fieldset>

	<form class="layui-form" action="">
	  	<div class="layui-form-item">
	    	<label class="layui-form-label">病种名</label>
	    	<div class="layui-input-block">
	      		<input type="text" name="bzname" required  lay-verify="required" placeholder="请输入病种名" autocomplete="off" class="layui-input" value="<?php echo $data['bz_name']; ?>">
	    	</div>
	  	</div>

		<div class="layui-form-item">
			<label class="layui-form-label">父病种</label>
			<div class="layui-input-block">
				<select name="pid">
					<option value="0">选择</option>
					<?php if (!empty($info)) { foreach ($info as $key => $value) { ?>
						<option value="<?php echo $value['id'] ?>" <?php if ($data['pid'] == $value['id']) { echo "selected";} ?> ><?php echo $value['html'].$value['bz_name'] ?></option>
					<?php }} ?>
				</select>
			</div>
		</div>

		<div class="layui-form-item">
			<label class="layui-form-label">启用</label>
			<div class="layui-input-block">
			  	<input type="checkbox" name="switch" lay-skin="switch" <?php if($data['is_show'] == 1) { echo "checked";}?>>
			</div>
		</div>

		<div class="layui-form-item" pane="">
			<label class="layui-form-label">科室</label>
			<div class="layui-input-block">
			  	<input type="radio" name="type" value="1" title="男科" <?php if($data['type'] == 1) { echo "checked";}?>>
			  	<input type="radio" name="type" value="2" title="妇科" <?php if($data['type'] == 2) { echo "checked";}?>>
			</div>
		</div>

	  	<div class="layui-form-item">
	    	<label class="layui-form-label">排序</label>
	    	<div class="layui-input-inline">
	      		<input type="text" name="order" placeholder="请输入排序" autocomplete="off" class="layui-input" value="<?php echo $data['order']; ?>">
	    	</div>
	  	</div>

		<div class="layui-form-item">
			<div class="layui-input-block">
			  	<button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
			  	<button type="reset" class="layui-btn layui-btn-primary">重置</button>
			</div>
		</div>
	</form>
  	<script src="static/vendor/layui/layui.js"></script>
	<script>

	layui.use('form', function(){
	  var form = layui.form;
	  var $$ = layui.jquery;
	  //监听提交
	  form.on('submit(formDemo)', function(data){
	    var main = data.field;
		$$.ajax({//异步请求返回给后台
			url:'?c=report&m=edit_bz_ajax&id=<?php echo $data['id']; ?>',
			type:'post',
			data:main,
			dataType:'json',
			success:function(res){
				//console.log(res);
			  if (res.code == 1) {
			  	layer.msg('添加成功！', {icon: 1});

			  	setTimeout(function(){
			  		window.parent.location.reload();
			  	},1000);
				return false;
			  } else {
			  	layer.msg('添加失败！', {icon: 5});
			  	//关闭当前iframe
			  	setTimeout(function(){
				    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
					parent.layer.close(index); //再执行关闭
			  	},1000);
			  	return false;
			  }
			}
		});
		return false;
	  });
	});
	</script>

</body>
</html>