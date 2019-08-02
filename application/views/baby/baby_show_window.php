<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />
        <title></title>
        <style type="text/css">
            .content1{
                
                border: 1px solid lightblue;
                border-width: lightblue;
                width:100%;
                text-align: center;
            }
            
            
        </style>
        
        
    </head>
    <body class="fixed-top" style="background:#fff;">
      <?php echo $top; ?>
<div id="container" class="row-fluid">
<?php echo $sider_menu; ?>
<div id="main-content">
        <table  class="content1" border="1px" cellspacing="0" bordercolor="#808080">
            
            <tr style="text-align: left; color:blue;margin-left: 30px;"><td colspan="23" ><div style="width:60%;float:left;"><label>备注：<br/>1、预约显示以指定时间段为准。
                            <br/>2、“0”表示未被预约，“1”表示该时段预约1人，“2”表示该时段预约2人，“3”表示该时段预约3人，以此类推。<br/>3、为了使显示结果正确，添加预约信息人员务必填写准确的时间段信息。</label></div>
                    <div style="width:38%;float:right;"><br/>
                    
                    
					<form action="" method="post" style="height:auto;"> 
						<input type="hidden" value="order" name="c" /> 
						<input type="hidden" value="baby_show_window" name="m" /> 
                     <div class="input-append date" style="height:18px;" id="dpYears" data-date="<?php echo date("Y-m-d ",strtotime($baby['order_time']))?>" data-date-format="yyyy-mm-dd" data-date-viewmode="day">
                     <input class="m-ctrl-medium" size="18" type="text" value="<?php echo date("Y-m-d ",strtotime($baby['order_time']))?>"  name="baby_time" readonly>
			         <span class="add-on"><i class="icon-calendar"></i></span>
                     </div>
                     <input type='submit' value='检索'/>
                    </form>
                    </div></td></tr>  

            <tr style="background-color: lightsteelblue;"><td >日期</td><td>预到人数</td>
            <!--
            <td>
              <table>
              <tr><td colspan="2">9:00-9:40</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">9:50-10:30</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">10:40-11:20</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">11:30-12:10</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">14:00-14:40</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">14:50-15:30</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
               <td>
              <table>
              <tr><td colspan="2">15:40-16:20</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
           <td>
              <table>
              <tr><td colspan="2">16:30-17:10</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
             <td>
              <table>
              <tr><td colspan="2">17:20-18:00</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
             <td>
              <table>
              <tr><td colspan="2">18:10-18:40</td></tr>
              <tr><td>大缸</td><td>小缸</td></tr>
             </table>
            </td>
             <td>
              <table>
              <tr><td colspan="2">18:50-19:30</td></tr>
              <tr><td >大缸</td><td>小缸</td></tr>
             </table>
            </td>
            -->
            <?php  foreach($baby['baby_type'] as $baby_type_temp){
				echo '<td><table><tr><td colspan="2">'.$baby_type_temp['time_start'].'~'.$baby_type_temp['time_end'].'</td></tr>
				<tr><td>小缸</td><td>大缸</td>
				</tr></table></td>';
				
				}?></tr>
            <tr>
             <?php  foreach($baby['baby_select_type'] as $baby_select_type_temp){echo '<td>'.$baby_select_type_temp['date'].'</td><td>';
			 
			  if(intval($baby_select_type_temp['all_sum']) == 0){
				  echo '<span style="color:red;">'.$baby_select_type_temp['all_sum'].'</span>';
			  }else{
				  echo $baby_select_type_temp['all_sum'];
			  }
			 echo '</td>';
			
	         foreach($baby['baby_type'] as $baby_type_temp){ 
				 echo '<td><table><tr>';
				 $bightml = '<td  style="width:30px;text-align:center;"><span style="color:red;">0</span></td>';
				 $smallhtml = '<td  style="width:30px;text-align:center;"><span style="color:red;">0</span></td>'; 
				  foreach($baby_select_type_temp['data'] as $baby_type_temp_data_temp){  
					 if($baby_type_temp['time_start'] == $baby_type_temp_data_temp['time_start']){ 
						
						 if($baby_type_temp_data_temp['jb_id'] == 301){
						  $smallhtml = '<td   style="width:30px;text-align:center;">'.$baby_type_temp_data_temp['sum'].'</td>';
						 } 
						  if($baby_type_temp_data_temp['jb_id'] == 300){
						  $bightml = '<td style="width:30px;text-align:center;">'.$baby_type_temp_data_temp['sum'].'</td>';
						 }
					 } 
				 }  
				 echo $bightml.$smallhtml;
				 echo '</tr></table></td>';
			 }
			 echo '</tr> <tr >';
			 }?>
            
        </table>
         
        <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
<script>
	$('#dpYears').datepicker();
	</script>
     </div>
    </div>
    </body>
</html>

