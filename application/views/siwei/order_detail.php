<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
            body{
                font-size: 12px;
                padding-left: 10px;
               margin:0px;
                background-color: #eee;
            }
            #content{
                padding-top:0px;
              margin-left: 10px;
                margin: 0 auto;
                width:800px;

            }
            #detail_table tr{

                height:30px;

            }

            #order_log{

                width: 800px;
            }
            .log_tb{
                border: 0px solid;
                border-color: #0044cc;
                color:#808080;

            }
        </style>
    </head>
    <body>
        <div id="content">
            <h2>预约详情</h2>
            <div id="detail">
                <table width="800px" id="detail_table">
                    <?php

                    $keshi_check_ts = $this->config->item('keshi_check_ts');

                    $keshi_check_ts = explode(",",$keshi_check_ts);

                    $zixun_check_ts = $this->config->item('zixun_check_ts');

                    $zixun_check_ts = explode(",",$zixun_check_ts);
                    $pat_phone = '';
                    $pat_phone1 = '';
                    $pat_check  = '';
                    foreach ($order_detail as $val){
                         $pat_phone =$val['pat_phone'];
                         $pat_phone1 =$val['pat_phone1'];

                    	  //咨询只能看自己的电话 其他电话不可见
						if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $val['hos_id'] == 3 && in_array($val['keshi_id'], $keshi_check_ts)){
							if($_COOKIE['l_admin_id'] != $val['admin_id']){
								$val['pat_phone']  =  $val['pat_phone'][0].$val['pat_phone'][1].$val['pat_phone'][2].'*****';
								$val['pat_phone1']  =  $val['pat_phone1'][0].$val['pat_phone1'][1].$val['pat_phone1'][2].'*****';
								$pat_check  = '1';
							}
						}?>
                    <tr>
                        <td>预约单号：<?=$val['order_no']?></td><td>患者姓名：<?=$val['pat_name']?></td><td>性别：<?=$val['pat_sex']?></td><td>年龄：<?=$val['pat_age']?></td>


                    </tr>
                    <tr>
                        <td>初诊/复诊：<?php if($val['is_first']==1){echo '初诊';}else{echo '复诊';}?></td>
                        <td>联系电话：<?php echo $val['pat_phone']."        ".$val['pat_phone1'];?>
                        </td><td colspan="2">联系地址：
                      <?php  if(@$val['pat_province'] > 0){ echo $area[$val['pat_province']]['region_name'];}
		  if(@$val['pat_city'] > 0){ echo "、" . $area[$val['pat_city']]['region_name'];}
		  if(@$val['pat_area'] > 0){ echo "、" . $area[$val['pat_area']]['region_name'];}
                        ?>




                        </td>

                    </tr>
                    <tr>
                        <td>预约医院：
                        <?php
    foreach($hospital as $t){
        if($t['hos_id']==$val['hos_id']){
            echo $t['hos_name'];
        }

    }
	?>

                        </td><td>预约科室：<?php
    if(isset($keshi[$val['keshi_id']])){echo $keshi[$val['keshi_id']]['keshi_name'];}
	?></td><td>预约病种：<?php
      if(isset($jibing[$val['jb_parent_id']])){echo $jibing[$val['jb_parent_id']]['jb_name'];}
	  if(isset($jibing[$val['jb_id']])){echo "<br />" . "(".$jibing[$val['jb_id']]['jb_name'].")";}
	?></td>
                        <td>预约性质：<?php
                        if(isset($type_list[$val['order_type']])){ echo $type_list[$val['order_type']]['type_name'];}

                        ?>
                        </td>


                    </tr>
                    <tr>
                        <td colspan="2">首次接待咨询员：<?php echo $gonghai_log[0]['action_name'];?></td><td colspan="2">预约途径：<?php
    if(isset($from_list[$val['from_parent_id']])){  echo $from_list[$val['from_parent_id']]['from_name'] . "----"; }
    if(isset($from_arr[$val['from_id']])){  echo $from_arr[$val['from_id']]['from_name'] . "<br />"; }

	?></td>


                    </tr>
                    <?php }?>
                </table>



            </div>

            <div id="order_log">
                <h3>预约操作日志：</h3>
                <table width="800px" class="log_tb">
                    <tr bgcolor="#ddd"><td width="120px">操作时间</td><td>操作类型</td><td width="100px">操作人员</td></tr>
                    <?php
                    $i=1;
                    foreach($gonghai_log as $value){
						if(!empty($pat_check)){
							$value['action_type'] =str_replace($pat_phone,"******",$value['action_type']);

							$value['action_type'] =str_replace($pat_phone1,"******",$value['action_type']);
						}

                        $ruleType = $dropsealog[$value['log_id']];
                        if (!empty($ruleType)){
                            $value['action_type'] = $value['action_type']."(".$cnfRules[$ruleType].")";
                        }
                      ?>
                    <tr <?php if($i%2==0){echo "style='background-color:#fff;'";}?>><td><?=date("Y-m-d H:i",$value['action_time'])?></td><td <?php if(mb_substr($value['action_type'],0,4,'utf-8')=="新增数据"||mb_substr($value['action_type'],0,4,'utf-8')=="暂无重要"){echo "style='color:green'";}else{echo "style='color:red'";}?>><?=$value['action_type']?></td><td><?=$value['action_name']?></td></tr>

                   <?php
                   $i++;
                    }

                    ?>



                </table>
            </div>


        </div>
    </body>
</html>
