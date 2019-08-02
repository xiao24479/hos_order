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
      

        <table class="content1" border="1px" cellspacing="0" bordercolor="#808080">
            
            <tr style="text-align: left; color:blue;margin-left: 30px;"><td colspan="23" ><div style="width:60%;float:left;"><label>备注：<br/>1、预约显示以整点和半点为准。（超过整点以整点为准，超过半点以半点为准）
                            <br/>2、“0”表示未被预约，“1”表示该时段预约1人，“2”表示该时段预约2人，“3”表示该时段预约3人，以此类推。<br/>3、为了使显示结果正确，添加预约信息人员务必填写准确的时间段信息。</label></div>
                    <div style="width:38%;float:right;"><br/>
                    <form action='?c=order&m=siwei_show_window' method="post">
                
                        <div class="input-append date"style="height:18px;" id="dpYears" data-date="<?php echo date("Y-m-d ",strtotime($siwei['order_time']))?>" data-date-format="yyyy-mm-dd" data-date-viewmode="day">
			<input class="m-ctrl-medium" size="18" type="text" value="<?php echo date("Y-m-d ",strtotime($siwei['order_time']))?>"  name="siwei_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
                      
		</div>
  <input type='submit' value='检索'/>
                    </form></div></td></tr>  

            <tr style="background-color: lightsteelblue;"><td>日期</td><td>预到人数</td><td>8:00</td><td>8:30</td><td>9:00</td><td>9:30</td><td>10:00
            </td><td>10:30</td><td>11:00</td><td>11:30</td><td>12:00</td><td>12:30</td><td>13:00</td>
        <td>13:30</td><td>14:00</td><td>14:30</td><td>15:00</td><td>15:30</td><td>16:00</td><td>16:30</td>
        <td>17:00</td><td>17:30</td><td>18:00</td>
        </tr>
        <?php for($j=0;$j<30;$j++){
            $a=strtotime($siwei['order_time']);
//           var_dump($siwei);
            $b=$a+$j*24*60*60;
             $weekday = date('w', $b);
             if($weekday=='0'||$weekday=='6'){
                ?> <tr style="text-align: center;background-color:lightgrey;">
                 <?php
             }else{?>
           <tr style="text-align: center;">
           <?php
             }
            ?>
        
<!--        <tr style="text-align: center;">-->
            <td><?php 
            
           
            $weeklist = array('日', '一', '二', '三', '四', '五', '六');
       
            echo date("y-m-d ",$b).'( 星期' . $weeklist[$weekday].')';
            ?></td><td style="color:red;"><?php 
            $count=array();
            $count4=0;
            for($t=0;$t<21;$t++){
                $times=count(@$siwei['data'][$j][0][$t]);
                $count4+=$times;
            }
            $count[$j]=$count4;
            echo $count[$j];?></td>
            <?php
            for($i=0;$i<21;$i++){
                if(isset($siwei['data'][$j][0][$i])){
                echo "<td style='color:red;'>".count(@$siwei['data'][$j][0][$i])."</td>";
                }else{
                    echo "<td>0</td>";
                }
            }
            
            ?>
            
            
        </tr>
        <?php 
        
                }
        ?>
        
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
    </body>
</html>

