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

<style type="text/css">
#leftUL{
list-style-type:none;
margin-left:0px;
background-color:white; 
width:200px;
height:200px;
padding:0px;
overflow:auto;
}


#center_ul{
list-style-type:none;
margin-left:5px;
margin-top:50px;
width:50px;
height:200px;
padding:0px;
}


#rightUL{
	
overflow:auto;	
margin-left:0px;	
list-style-type:none;
background-color:white;  
width:200px;
height:200px;
padding:0px;
}

.lia{text-decoration:none}


.change_li{
margin-bottom:2px; 
}
 
.change_li:hover{
background-color:#DCDCDC;	
}


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
                    <form method="post" action="?c=group_person&m=add_group_person" id="tab">
                        <div class="span9" style="margin-top:50px;">
							<div class="content">
							 <table>
                                   <tr>
                                      
                                       <td><label>上级组</label>
                                 <select name="parent_id">
                                 <option value="0">请选择</option>
                                   <?php  foreach($parnt_list as $val){ ?>
                                    <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                                    <?php }?>
                                 </select></td> 
                                  <td> <label>名称</label>
								<input name="name" class="span12" placeholder="名称" value=""></td>
                                        
                                   </tr>
                                   <tr>
                                       <td> <label>医院</label>
                                 <select name="hospital_id" id="hos_id"> 
                                 <option value="0">请选择</option>
                                   <?php  foreach($hospital_list as $val){ ?>
                                    <option value="<?php echo $val['hos_id'];?>"><?php echo $val['hos_name'];?></option>
                                    <?php }?>
                                 </select>
                                       </td>
                                       <td> <label>科室</label>
			                                 <select name="keshi_id" id="keshi_id">
			                                     <option value="0">请选择</option>
			                                  
			                                 </select>
                                       </td>
                                      </tr>
                                      
                                      <tr>
                                       <td  colspan="2" >
                                           <table>
                                              <tr>
                                                 <td><label>待选分组人员</label>
	                                       	<ul id="leftUL"></ul></td>
                                                 <td><ul id="center_ul">
	                                       	  <li><input type="button" id="add_all" value="添加"/></li>
	                                       	  <li>&nbsp;</li> 
	                                       	  <li><input type="button"  id="del_all"  value="清空"/></li>
	                                       	</ul></td>
                                                 <td> <label>已选分组人员</label>
	                                       	 <ul id="rightUL"></ul></td>
                                              </tr>
                                           </table> 
	                                       	
							            </td>
                                      </tr>
                             </table>
							    <!-- 用户最新数组 -->
							    <input type="hidden" value="" id="admin_id_str" name="admin_id_str">
							    <!-- 用户历史选择数组 -->
							    <input type="hidden" value="<?php echo  $rightUL_admin;?>" id="rightUL_admin">
						 
							    <div class="pix_25"></div>  <p><input type="button" id="check_submit" value="提交"/></p> </div> 
						</div> 
						</form> 
					</div>
				<!-- END PAGE CONTENT-->
			 </div>
			 <!-- END PAGE CONTAINER-->
		</div>
	</div>
   <script src="static/js/jquery-1.8.3.min.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   
    <!-- 加载jquery json对象 -->
     <script type="text/javaScript" src="static/js/jquery_json/jquery.json-2.3.js"></script>
    
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
   
   <script type="text/javascript">
   $("#add_all").click(function(){
       if($("#leftUL").html() != null && $("#leftUL").html() != ''){
       	$("#rightUL").html($("#rightUL").html()+$("#leftUL").html()); 
       	$("#leftUL").html("");
       } 
	});

   $("#del_all").click(function(){
  	   if($("#rightUL").html() != null && $("#rightUL").html() != ''){
     		$("#leftUL").html($("#leftUL").html()+$("#rightUL").html()); 
       	$("#rightUL").html("");
  	   }
	});
	//添加校验
    $("#check_submit").click(function(){
        var admin_id_str= '';
    	$("#rightUL>li").each(function(){
    		if(admin_id_str == ''){
    			admin_id_str = $(this).attr("id");
        	}else{
        		admin_id_str  = admin_id_str+','+$(this).attr("id");
            }
    	}); 
    	$("#admin_id_str").val(admin_id_str);
    	$("#tab").submit();
    }); 

    $("#hos_id").change(function(){
		ajax_get_keshi($(this).val(), 0);
		ajax_get_admin($("#hos_id").val(),0);
	});

	$("#keshi_id").change(function(){
		ajax_get_admin($("#hos_id").val(),$(this).val());
	});
	
    function ajax_get_keshi(hos_id, check_id)
    {
	    $.ajax({
			type:'post',
			url:'?c=order&m=keshi_list_ajax',
			data:'hos_id=' + hos_id + '&check_id=' + check_id,
			success:function(data)
			{ 
				$("#keshi_id").html(data);
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
    } 

    function ajax_get_admin(hos_id, keshi_id)
    {
         $.ajax({
			type:'post',
			url:'?c=group_person&m=admin_list_ajax',
			data:'hos_id=' + hos_id + '&keshi_id=' + keshi_id,
			success:function(data)
			{ 
				   var $leftUL = $("#leftUL");
				   $leftUL.html("");
			       myJson  =  jQuery.parseJSON(data);
			       for (var i = 0; i < myJson.length; i++) {
				       if(myJson[i].id > 0){
				    	   $myLi = $("<li class='change_li' id='" + myJson[i].id + "'><a class='lia' href='javaScript:void(0);'>" + myJson[i].Name+"</a></li>");
						   $leftUL.append($myLi);
					   } 
					} 
			},
			complete: function (XHR, TS)
			{
			   XHR = null;
			}
		});
    }

 
    $(".change_li").live("click", function(e){
    	var $rightUL = $("#rightUL");
    	var $leftUL = $("#leftUL"); 
    	if ($(this).parent().attr("id") == "leftUL") {
    		$rightUL.append("<li class='change_li' id='" + $(this).attr("id") + "'>"+$(this).html()+"</li>");
		} else {
			$leftUL.append("<li class='change_li' id='" + $(this).attr("id") + "'>"+$(this).html()+"</li>");
		} 
    	$(this).remove();
    });
	
	</script>
   
</body>
</html>