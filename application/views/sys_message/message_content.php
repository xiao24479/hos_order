<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>系统公告内容</title>
        <style type="text/css">
            body{
                margin:0px;
                
            }
            #content{
               margin:0 auto;
           
               height:600px;
              
                
            }
            
        </style>
    </head>
    <body>
      <?php foreach($mes_content as $val){?>
        <div id="content">
 
            <div id="title" style="width:100%;background-color:#dcdcdc;hetight:60px;padding-top:60px;padding-bottom: 10px;text-align: center;color:#494949;font-family:'微软雅黑';font-size:22px;font-weight: bold;"><?=$val['message_title']?></div>
            <div id="min_title" style=" width: 100%; text-align: center;font-size: 10px;color:#808080;margin-top:10px;" ><?php echo date("Y年m月d日",$val['message_time']);?>&nbsp;&nbsp;发布：<?=$val['message_user']?></div>
            <div style="width:90%;align:center;margin-left: 50px;margin-top:30px;font-size: 14px; color:#494949;"><?=stripslashes($val['message_content'])?></div>
            
            
            
            
            
        </div>
           
        <?php }?>
    </body>
</html>
