<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title></title>

    <style type="text/css">

        body {

            font-size: 12px;

            padding-left: 10px;

            margin: 0px;

            background-color: #eee;

        }

        #content {

            padding-top: 0px;

            margin-left: 10px;

            margin: 0 auto;

            width: 800px;

        }

        #detail_table tr {

            height: 30px;

        }

        #order_log {

            width: 800px;

        }

        .log_tb {

            border: 0px solid;

            border-color: #0044cc;

            color: #808080;

        }

    </style>

</head>

<body>

<div id="content">

    <h2>预约详情</h2>

    <div id="detail">

        <table width="800px" id="detail_table">

            <?php foreach ($order_detail as $val) { ?>

                <tr>

                    <td>留联单号：<?= $val['order_no'] ?><br/>
                        预约单号：<?= $val['order_no_yy'] ?>
                    </td>
                    <td>患者姓名：<?= $val['pat_name'] ?></td>
                    <td>性别：<?= $val['pat_sex'] ?></td>
                    <td>年龄：<?= $val['pat_age'] ?></td>

                </tr>

                <tr>

                    <td>初诊/复诊：<?php if ($val['is_first'] == 1) {
                            echo '初诊';
                        } else {
                            echo '复诊';
                        } ?></td>

                    <td>联系电话：<?php echo $val['pat_phone'] . "        " . $val['pat_phone1']; ?></td>
                    <td>联系QQ：<?php echo $val['pat_qq']; ?></td>
                    <td>联系微信：<?php echo $val['pat_weixin']; ?></td>
                </tr>
                <tr>

                    <td colspan="4">联系地址：

                        <?php if (@$val['pat_province'] > 0) {
                            echo $area[$val['pat_province']]['region_name'];
                        }

                        if (@$val['pat_city'] > 0) {
                            echo "、" . $area[$val['pat_city']]['region_name'];
                        }

                        if (@$val['pat_area'] > 0) {
                            echo "、" . $area[$val['pat_area']]['region_name'];
                        }

                        ?> </td>
                </tr>

                <tr>

                    <td>预约医院：

                        <?php

                        foreach ($hospital as $t) {

                            if ($t['hos_id'] == $val['hos_id']) {

                                echo $t['hos_name'];

                            }


                        }

                        ?>


                    </td>
                    <td>预约科室：<?php

                        if (isset($keshi[$val['keshi_id']])) {
                            echo $keshi[$val['keshi_id']]['keshi_name'];
                        }

                        ?></td>
                    <td>预约病种：<?php

                        if (isset($jibing[$val['jb_parent_id']])) {
                            echo $jibing[$val['jb_parent_id']]['jb_name'];
                        }

                        if (isset($jibing[$val['jb_id']])) {
                            echo "<br />" . "(" . $jibing[$val['jb_id']]['jb_name'] . ")";
                        }

                        ?></td>

                    <td>预约性质：<?php

                        if (isset($type_list[$val['order_type']])) {
                            echo $type_list[$val['order_type']]['type_name'];
                        }


                        ?>

                    </td>


                </tr>

                <tr>

                    <td colspan="2">首次接待咨询员：<?php echo $gonghai_log[0]['action_name']; ?></td>
                    <td colspan="2">预约途径：<?php

                        if (isset($from_list[$val['from_parent_id']])) {
                            echo $from_list[$val['from_parent_id']]['from_name'] . "----";
                        }

                        if (isset($from_arr[$val['from_id']])) {
                            echo $from_arr[$val['from_id']]['from_name'] . "<br />";
                        }


                        ?></td>


                </tr>

            <?php } ?>

        </table>


    </div>

    <?php if (!empty($gonghai_log)): ?>
        <div id="order_log">
            <h3>留联公海日志：</h3>
            <table width="800px" class="log_tb">
                <tr bgcolor="#ddd">
                    <td width="120px">操作时间</td>
                    <td>操作类型</td>
                    <td width="100px">操作人员</td>
                </tr>
                <?php
                $i = 1;
                $cnfRules = $this->config->item("dropSeaRuleLiulian");
                foreach ($gonghai_log as $value) {
                    ?>
                    <tr <?php if ($i % 2 == 0) {
                        echo "style='background-color:#fff;'";
                    } ?>>
                        <td><?= date("Y-m-d H:i", $value['action_time']) ?></td>
                        <td style='color:red'>
                            <?php if ($value['action_type'] == 0): ?>
                                掉入公海
                                <?php if (!empty($cnfRules[$value['action_rules']])): ?>
                                    (<?php echo $cnfRules[$value['action_rules']]; ?>)
                                <?php endif; ?>
                            <?php elseif ($value['action_type'] == 1): ?>
                                从公海捞取
                            <?php endif; ?>
                        </td>
                        <td><?= $value['admin_name'] ?></td>
                    </tr>

                    <?php
                    $i++;
                }

                ?>

            </table>
        </div>
    <?php endif; ?>

    <div id="order_log">

        <h3>备注：</h3>


        <textarea class="input-xxlarge" rows="5" style="width:780px;"> <?= $val['remark'] ?></textarea>

    </div>


    <div id="order_log">

        <h3>对话记录：</h3>

        <textarea class="input-xxlarge" rows="5" name="con_content" id="con_content"
                  style="width:780px;"> <?= $val['con_content'] ?></textarea>

    </div>


</div>

</body>

</html>


<script src="static/js/jquery.js"></script>


<script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>


<script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>


<script src="static/js/bootstrap.min.js"></script>


<script type="text/javascript" src="static/js/bootstrap-datepicker.js"></script>


<script charset="utf-8" src="static/editor/kindeditor.js"></script>


<script charset="utf-8" src="static/js/clockface.js"></script>


<script charset="utf-8" src="static/editor/lang/zh_CN.js"></script>


<!-- ie8 fixes -->


<!--[if lt IE 9]>


<script src="static/js/excanvas.js"></script>


<script src="static/js/respond.js"></script>


<![endif]-->


<script src="static/js/common-scripts.js"></script>


<script>


    KindEditor.ready(function (K) {


        window.editor = K.create('#con_content', {


            resizeType: 1,


            allowPreviewEmoticons: false,


            allowImageUpload: false,


            items: [


                'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',


                'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',


                'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'fullscreen']


        });


    });

</script>