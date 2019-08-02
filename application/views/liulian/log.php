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
                        <?php if($value['action_type'] == 0):?>
                            掉入公海
                            <?php if (!empty($cnfRules[$value['action_rules']])): ?>
                                (<?php echo $cnfRules[$value['action_rules']]; ?>)
                            <?php endif; ?>
                        <?php elseif($value['action_type'] == 1): ?>
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
</div>
</body>
</html>
