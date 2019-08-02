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
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<style>
td{color:#666;}
.td1 td{background-color:#efefef;}
</style>
</head>

<body class="fixed-top">
   <?php echo $top; ?>
   <div id="container" class="row-fluid">
   <?php echo $sider_menu; ?>
   <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
              <div class="row-fluid">
			    <div class="span12">
				  <div class="widget orange">
<!--                            <div class="widget-title">
                                <h4><i class="icon-reorder"></i> <?php echo $this->lang->line('content_table'); ?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                            </div>-->
							
                            <div class="widget-body">
							<div class="row-fluid"><div class="span7"><a href="#weixin_all" title="微信内容" role="button" data-toggle="modal" onClick="weixin_content_all()"><button class="btn btn-primary">点击群发</i></button></a>&nbsp;&nbsp;<a href="?c=weixin&m=user_info_ins"><button class="btn btn-primary">更新用户</i></button></a></div><div class="span2"><div id="sample_1_length" class="dataTables_length"><label><select name="gid" id="group" style="width:168px;"><option value="0">请选择分组</option><?php foreach($group as $key=>$val):?><option value="<?php echo $key;?>" <?php if($gid==$key):?>selected="selected"<?php endif;?>><?php echo $val;?></option><?php endforeach;?></select></label></div></div><div class="span3"><div class="dataTables_filter" id="sample_1_filter"><label>Search: <input type="text" id="wx_name" aria-controls="sample_1" class="input-medium"></label></div></div></div>
			  <table width="100%" border="0" cellspacing="0" cellpadding="2" class="table table-advance">
  <thead>
  <tr>
    <th width="10">#</th>
	<th>用户昵称</th>
    <th>状态</th>
	<th>性别</th>
	<th>地区</th>
	<th>分组</th>
	<th>关注时间</th>
	<th>访问时间</th>
	<th width="100"><?php echo $this->lang->line('action'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach($user_info as $item):
 
  ?>
  <tr<?php if($i % 2){ echo " class='td1'";}?>>
    <td><?php echo $i; ?></td>
    <td><?php echo $item['nickname']?>&nbsp;&nbsp;<a href="#pos_info" title="地址信息" role="button" data-toggle="modal" onClick="pos_info('<?php echo $item['pos']?>')"><i class="icon-map-marker"></i></a></td>
    <td><?php if($item['subscribe'] == 1){echo '关注中';}elseif($item['subscribe'] == 0){echo '取消关注';}?></td>
    <td><?php if($item['sex'] == 1){echo '男';}elseif($item['sex'] == 2){echo '女';}?></td>
    <td><?php echo $item['country'].$item['province'].'省'.$item['city'].'市';?></td>
	<td><?php echo $group[$item['gid']];?></td>
	<td><?php echo date('Y-m-d H:i:s',$item['subscribe_time']);?></td>
	<td><?php if(!empty($item['come_time'])){echo date('Y-m-d H:i:s',$item['come_time']);}else{ echo '未知';}?></td>
	<td>
	<a href="#weixin" title="微信内容" role="button" data-toggle="modal" onClick="weixin_content('<?php echo $item['openid'];?>')"><button class="btn btn-primary"><i class="icon-pencil"></i></button></a>&nbsp;<button class="btn btn-danger" onClick="del_ajax(this,<?php echo $item['wx_uid'];?>)"><i class="icon-trash"></i></button>
	
	</td>
  </tr>

  <?php
  $i ++;
  endforeach;
  ?>
  </tbody>
  </table>
  <?php echo $page; ?>
			  </div>
			  </div>
			  <div id="weixin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3>发送微信消息</h3>

		</div>

		<div class="modal-body">
		
            <div class="control-group">

				<label class="control-label">填写要发送的信息</label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="weixin_remark" id="weixin_remark" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			<input type="hidden" name="weixin_id" id="weixin_user_id" value="" />
			
			<input type="hidden" name="order_id" id="weixin_order_id" value="" />

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="weixin_send();"> 提交 </button>

		</div>

	</div>
	<div id="weixin_all" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3>群发微信消息</h3>

		</div>

		<div class="modal-body">
		
            <div class="control-group">

				<label class="control-label">填写要发送的信息内容</label>

				<div class="controls">

					<textarea class="input-xxlarge " rows="5" name="weixin_remark" id="weixin_remark_all" style="width:520px;"></textarea>

				</div>

			</div>

		</div>

		<div class="modal-footer">

			

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

			<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" onClick="weixin_send_all();"> 提交 </button>

		</div>

	</div>
	<div id="pos_info" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

			<h3 id="myModalLabel1">用户最近一次出现的位置</h3>

		</div>

		<div class="modal-body" id="pos_content" style="height:800px;">

		</div>
		


		<div class="modal-footer">

			<button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭 </button>

		</div>

	</div>
			  </div>
            </div>
            <!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
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
   <script language="javascript">
   window.qq = window.qq || {};
	qq.maps = qq.maps || {};
	window.soso || (window.soso = qq);
	soso.maps || (soso.maps = qq.maps);
	(function () {
	    function getScript(src) {
	        document.write('<' + 'script src="' + src + '"' +' type="text/javascript"><' + '/script>');
	    }
	    qq.maps.__load = function (apiLoad) {
	        delete qq.maps.__load;
	        apiLoad([["2.3.1","",0],["http://open.map.qq.com/","apifiles/2/3/1/mods/","http://open.map.qq.com/apifiles/2/3/1/theme/",true],[1,18,34.519469,104.461761,4],[1416559858452,"http://ping.map.qq.com/call","http://ping.map.qq.com/call"],["http://apic.map.qq.com/","http://apikey.map.qq.com/mkey/index.php/mkey/check","http://sv.map.qq.com/xf","http://sv.map.qq.com/rarp"],[[null,["http://p0.map.gtimg.com/maptilesv3","http://p1.map.gtimg.com/maptilesv3","http://p2.map.gtimg.com/maptilesv3","http://p3.map.gtimg.com/maptilesv3"],"png",[256,256],1,19,"",true,false],[null,["http://p0.map.gtimg.com/hwaptiles","http://p1.map.gtimg.com/hwaptiles","http://p2.map.gtimg.com/hwaptiles","http://p3.map.gtimg.com/hwaptiles"],"png",[128,128],1,19,"",false,false],[null,["http://p0.map.gtimg.com/sateTiles","http://p1.map.gtimg.com/sateTiles","http://p2.map.gtimg.com/sateTiles","http://p3.map.gtimg.com/sateTiles"],"jpg",[256,256],1,19,"",false,false],[null,["http://p0.map.gtimg.com/sateTranTilesv3","http://p1.map.gtimg.com/sateTranTilesv3","http://p2.map.gtimg.com/sateTranTilesv3","http://p3.map.gtimg.com/sateTranTilesv3"],"png",[256,256],1,19,"",false,false],[null,["http://sv0.map.qq.com/road/","http://sv1.map.qq.com/road/","http://sv2.map.qq.com/road/","http://sv3.map.qq.com/road/"],"png",[256,256],1,19,"",false,false],[null,["http://rtt2.map.qq.com/live/"],"png",[256,256],1,19,"",false,false],null,null,null],["http://s.map.qq.com/TPano/v1.1.1/TPano.js","http://map.qq.com/",""]],loadScriptTime);
	    };
	    var loadScriptTime = (new Date).getTime();
	    getScript("http://open.map.qq.com/apifiles/2/3/1/main.js");
	})();
   var Maps={
		Author :'moretouch',
		Website :'http://www.moretouch.cn/',
		Version:'V1.0 20140320',
		iconPic :'statics/icon.png',
		map:''
	};
	Maps.QQ =(function(){
		    return{
			/*
			 单点标注
			*@_Container ->地图容器
			*@_Point ->地图坐标（中心位置）->数据类型->{'lng':26.639252,'lat':106.646286}
			*@_Zoom ->地图缩放级别
			*@_labelText ->label文字
			*/
		    _marker : function(_Container,lng,lat,_Zoom,_labelText){
				var center = new qq.maps.LatLng(lng,lat);
				this.map = new qq.maps.Map(document.getElementById(_Container),{
					center: center,
					zoom: _Zoom
				});
				/*标注*/
				var marker = new qq.maps.Marker({
					position: center,
					draggable: false,/*拖动*/
					map: this.map
				});
				/*标注Label*/
				var label = new qq.maps.Label({
					position: center,
					map: this.map,
					content:_labelText,
					style:{color:"#f00",fontSize:"14px",fontWeight:"bold"},
					offset:new qq.maps.Size(16,-30)
				});
			}
		  }//End 
	})();

	$("#group").change(function(){
		var id = $("#group").val();
		location.href = "?c=weixin&m=user_list&gid="+id;
	});
	$("#wx_name").change(function(){
		var name = $("#wx_name").val();
		if(name !== ''){
			location.href = "?c=weixin&m=user_list&name="+name;
		}
	});
	function del_ajax(obj,id){
		var returnVal = window.confirm("您确定删除该记录？", "");
		if(returnVal) {
			$.ajax({

				type:'post',

				url:'?c=weixin&m=user_delete_ajax',

				data:'wx_uid=' + id,

				success:function(data)

				{
					if(data == 1){
					
						alert('删除失败');
					}
					if(data == 2){
					
						 $(obj).parent().parent().remove();
					}
				
				},

				complete: function (XHR, TS)

				{

				   XHR = null;
				  
				}

			});
			
		}		
	}
	function weixin_content(user_id)
	{
		$("#weixin_remark").val('');
		$("#weixin_user_id").val(user_id);
	}
	function weixin_content_all()
	{
		$("#weixin_remark_all").val('');
	}
	function weixin_send()
	{
		var user_id = $("#weixin_user_id").val();
		var remark = $("#weixin_remark").val();
		
		if(remark == ''){
		
			alert('发送内容不能为空');
			return;
		}
		$.ajax({

			type:'post',

			url:'?c=weixin&m=ajax_send',

			data:'user_id=' +　user_id + '&remark=' + remark,

			success:function(data)

			{

				alert(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}
		});
	}
	function weixin_send_all()
	{
		var remark = $("#weixin_remark_all").val();
		
		if(remark == ''){
		
			alert('发送内容不能为空');
			return;
		}
		$.ajax({

			type:'post',

			url:'?c=weixin&m=ajax_send_all',

			data:'remark=' + remark,

			success:function(data)

			{

				alert(data);

			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}
		});
	}

function pos_info(pos)
{
	if(pos !== '')

	{
		$('#pos_content').css('height','800px');

		$.ajax({

			type:'post',

			url:'?c=weixin&m=pos_info',

			data:'pos=' + pos,

			success:function(data)

			{
				var obj=$.parseJSON(data);
				var lng = (obj.row.result.location.lng);
				var lat = (obj.row.result.location.lat);
				var name = obj.row.result.formatted_address;
				Maps.QQ._marker('pos_content',lat,lng,16,name);
			},

			complete: function (XHR, TS)

			{

			   XHR = null;

			}

		});

	}else{
		
		$('#pos_content').css('height','50px');
		$('#pos_content').text('没有该用户的地理位置数据')
	}
}

   </script>
</body>
</html>