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

       
        <table class="content1" border="1px" cellspacing="0" bordercolor="#808080">
            <?php 
            $count1=0;
            $count2=0;
            $count3=0;
            for($t=0;$t<21;$t++){
                $times=count(@$siwei['data'][0][0][$t]);
                $count1+=$times;
                
            }
            for($t=0;$t<21;$t++){
                $times2=count(@$siwei['data'][1][0][$t]);
                $count2+=$times2;
                
            }
            for($t=0;$t<21;$t++){
                $times3=count(@$siwei['data'][2][0][$t]);
                $count3+=$times3;
                
            }

            ?>
            
            
            <tr style="text-align:left;height:20px;letter-spacing:1px;"><td colspan="22"><span style="color:lightseagreen;"><?php echo date("Y年m月d日 ",strtotime($siwei['order_time']))?>预到人数&nbsp;<span style="color:lightcoral;font-size: 20px;"><?php echo $count1;?></span>人&nbsp;&nbsp;
                    近3天预到人数：<span style="color:lightcoral;font-size: 20px;"><?php echo $count1+$count2+$count3;?></span>人
                   
                    </span></td> </tr>
            <tr style="text-align:center;"><td colspan="22"><form action='?c=order&m=siwei_show' method="post">
                
                        <div class="input-append date"style="height:20px;" id="dpYears" data-date="<?php echo date("Y-m-d ",strtotime($siwei['order_time']))?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
			<input class="m-ctrl-medium" size="16" type="text" value="<?php echo date("Y-m-d ",strtotime($siwei['order_time']))?>"  name="siwei_time" readonly>
			<span class="add-on"><i class="icon-calendar"></i></span>
                      
		</div>
  <input type='submit' value='检索'/>
                    </form></td> </tr>
            <tr style="background-color: lightsteelblue;"><td>日期</td><td>8:00</td><td>8:30</td><td>9:00</td><td>9:30</td><td>10:00
            </td><td>10:30</td><td>11:00</td><td>11:30</td><td>12:00</td><td>12:30</td><td>13:00</td>
        <td>13:30</td><td>14:00</td><td>14:30</td><td>15:00</td><td>15:30</td><td>16:00</td><td>16:30</td>
        <td>17:00</td><td>17:30</td><td>18:00</td>
        </tr>
        <?php for($j=0;$j<30;$j++){?>
        
        <tr style="text-align: center;">
            <td><?php 
            $a=strtotime($siwei['order_time']);
//           var_dump($siwei);
            $b=$a+$j*24*60*60;
            echo date("y-m-d ",$b);
            ?></td>
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
            <tr style="text-align: left; color:red;margin-left: 30px;"><td colspan="21" rowspan="5"><label>备注：<br/>1、预约显示以整点和半点为准。（超过整点以整点为准，超过半点以半点为准）
                    <br/>2、“0”表示未被预约，“1”表示该时段预约1人，“2”表示该时段预约2人，“3”表示该时段预约3人，以此类推。<br/>3、为了使显示结果正确，添加预约信息人员务必填写准确的时间段信息。<br/><br/><br/></label></td></tr>  
        </table>
          </div>
</div>
</div>
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
