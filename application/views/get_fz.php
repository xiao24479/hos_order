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

<link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen" />

</head>
  
<body  >

<input type="hidden"  id="fz_order_id" value="<?php echo $order_id;?>">
<input type="hidden"  id="fz_base_url" value="<?php echo $this->config->item('base_url');?>">


 
	 <div class="modal-body" style="max-height:440px;"> 
            <div class="control-group">
				<label class="control-label">备注</label>
				<div class="controls">
					<textarea class="input-xxlarge " rows="5"   id="fz_remark" style="width:520px;"></textarea>
				</div>
			</div>
			<div class="control-group">
				 <span style="margin-left: 10px;"> <input type="button" class="input_search"  id="add_fz" style="vertical-align:middle;height:30px; cursor:pointer;" value="添加复诊"  ></span>
			</div>
	 </div> 

	<div class="container-fluid">
					<div class="row-fluid" style="margin-top: 10px;">
				  
    				 <div class="row-fluid">
    				 <div class="span12">
    				 <table class="list_table" width="100%" cellspacing="0" cellpadding="2" border="0">
    				 <thead>
    				 <tr class="">
					               <th style="text-align:center;" width="5%">序号</th>
                                  <th style="text-align:center;" width="15%">操作人</th>
                                  <th style="text-align:center;" width="20%">时间</th>
                                  <th style="text-align:center;" width="60%">备注</th>
                                  
    				 </tr>
    				 </thead>
    				 <tbody >
    				   <tr class="fz_tbody"></tr>
    				 <?php foreach ($order as $key=>$order_temp){?>
			 			<tr class="fz_tbody">
						    <td style="text-align:center;"><?php echo $key+1;?></td>
				 			<td style="text-align:center;"><?php echo $order_temp['admin_name'];?></td>
				 			<td style="text-align:center;"><?php echo date("Y-m-d H:i:s", $order_temp['fztime']);?></td>
				 			<td style="text-align:center;"><?php echo $order_temp['fzremark'];?></td> 
				 		</tr>
			 		<?php }?> 
                          </tbody>
    				 </table>
    				 </div>
    				 </div>
 
					</div>
					  
				<!-- END PAGE CONTENT-->
			 </div>
</body>
</html>

<script src="static/js/jquery-1.8.3.min.js"></script>
<script language="javascript">
$(document).ready(function(e) {
	 $("#add_fz").click(function(){ 
		  var fz_remark = $("#fz_remark").val(); 
          $.ajax({
				type:'post',
				url:$("#fz_base_url").val()+'?c=order&m=add_fz_ajax',
				data:'fz_remark=' +fz_remark+"&order_id="+$("#fz_order_id").val(),
				dataType: "json",
				success:function(data)
				{   
					if(data != null){
						if(data['id'] == 0){
							alert("添加失败");
						}else{
							$(".fz_tbody:first").before('<tr class="fz_tbody"><td style="text-align:center;"></td>'+
								'<td style="text-align:center;">'+data['admin_name']+'</td>'+
								'<td style="text-align:center;">'+data['fztime']+'</td>'+
								'<td style="text-align:center;">'+fz_remark+'</td>'+
							'</tr>');
						}
					} else{
						alert("添加失败");
					}
				},
				complete: function (XHR, TS)
				{
				   XHR = null;
				}
		});  
	 });  
	 
});
</script>