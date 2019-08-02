<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $admin['name'] . '-' . $title; ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="static/vendor/layui/css/layui.css" rel="stylesheet"/>
    <link href="static/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="static/css/bootstrap-responsive.min.css" rel="stylesheet"/>
    <link href="static/css/font-awesome.css" rel="stylesheet"/>
    <link href="static/css/style.css" rel="stylesheet"/>
    <link href="static/css/style-responsive.css" rel="stylesheet"/>
    <link href="static/css/style-default.css" rel="stylesheet" id="style_color"/>
    <link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen"/>
    <link href="static/js/datepicker/css/datepicker.css" rel="stylesheet"/>
    <style type="text/css">
        /*#main-content{margin-left:0px;}
        #sidebar{margin-left:-180px; z-index:-1;}
        .sidebar-scroll{z-index:-1;}*/
        .date_div {
            position: absolute;
            top: 55px;
            left: 412px;
            z-index: 999;
        }

        .anniu {
            display: none;
        }

        .order_form1 {
            margin-bottom: 10px;
            width: 100%;
            border: 1px solid #e3e3e3;
            padding: 10px 0 0 0;
        }

        .order_btn1 {
            left: 1210px;
        }

        .o_a a {
            padding: 0 10px;
        }

        .autocomplete {
            border: 1px solid #9ACCFB;
            background-color: white;
            text-align: left;
        }

        .autocomplete li {
            list-style-type: none;
        }

        .clickable {
            cursor: default;
        }

        .highlight {
            background-color: #9ACCFB;

        }

        .list_table .td2 td {

            background-color: linen;
        }

        .list_table .td3 td {

            background-color: lightpink;
        }

        .list_table .blacklist td {
            background-color: #999;
        }

        .list_table .exceed_15_hf td {
            background-color: #ec8a8a;
            border-top: 1px solid #999;
        }
    </style>
    <script src="static/js/jquery.js"></script>
    <script language="javascript">
        //if($(window).width() >= 1200)
        //{
        //	window.location.href = '?c=order&m=order_list';
        //}

        function exprot_gonghai() {
            $("#m").val("gonghai_order_list_down");
            $("#c").val("order");
            $(".order_form1").submit();
            $("#m").val("gonghai");
            $("#c").val("gonghai");

        }

    </script>
</head>

<body class="fixed-top">

<?php echo $top; ?>
<div id="container" class="row-fluid">
    <?php echo $sider_menu; ?>

    <div id="main-content">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid" style="position:relative; padding-top:10px;">
            <div class="order_count">

            </div>
            <form action="" method="get" class="date_form order_form1">
                <input type="hidden" value="liuliangonghai" name="c" id="c"/>
                <input type="hidden" value="llindex" name="m" id="m"/>
                <!--<input type="hidden" value="mi" name="type" />-->
                <div class="span5">
                    <div class="row-form">
                        <select name="t" style="width:110px;">
                            <option value="1" <?php if ($t == 1) {
                                echo " selected";
                            } ?>>登记时间
                            </option>
                            <option value="2" <?php if ($t == 2) {
                                echo " selected";
                            } ?>>公海时间
                            </option>
                        </select>
                        <input type="text" value="<?php echo $start; ?> - <?php echo $end; ?>" style="width:270px;"
                               class="input-block-level" name="date" id="inputDate"/>
                    </div>
                    <div class="date_div">
                        <div class="divdate"></div>
                        <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi"> 关闭 </a><br/><a
                                    href="javascript:;" class="btn btn-inverse today"> 今天 </a><br/><a
                                    href="javascript:;" class="btn btn-inverse week"> 一周 </a><br/><a href="javascript:;"
                                                                                                     class="btn btn-inverse month">
                                一月 </a><br/><a href="javascript:;" class="btn btn-inverse year"> 一年 </a></div>
                    </div>


                    <div class="row-form">
                        <label class="select_label"><?php echo $this->lang->line('order_keshi'); ?></label>
                        <select name="hos_id" id="hos_id" style="width:180px;">
                            <option value=""><?php echo $this->lang->line('hospital_select'); ?></option>
                            <?php foreach ($hospital as $val): ?>
                                <option value="<?php echo $val['hos_id']; ?>" <?php if ($val['hos_id'] == $hos_id) {
                                    echo " selected";
                                } ?>><?php echo $val['hos_name']; ?></option><?php endforeach; ?>
                        </select>
                        <select name="keshi_id" id="keshi_id" style="width:130px;">
                            <option value=""><?php echo $this->lang->line('keshi_select'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="span3">
                    <div class="row-form">
                        <label class="select_label"><?php echo $this->lang->line('patient_name'); ?></label>
                        <input type="text" value="<?php echo $p_n; ?>" class="input-medium" name="p_n"/>
                    </div>
                    总记录数：<font style="font-size:18px;" color="red"><?= $all_count ?></font>
                </div>
                <div class="span3">
                    <div class="row-form">
                        <label class="select_label"><?php echo $this->lang->line('order_no'); ?></label>
                        <input type="text" value="<?php echo $o_o; ?>" class="input-medium" name="o_o"/>
                    </div>
                    <div class="row-form">

                        <label class="select_label">咨询员</label>

                        <input type="text" value="<?php echo $a_i; ?>" class="input-medium" name="a_i"/>

                    </div>

                </div>
                <div class="span3">

                    <div class="row-form">

                        <label class="select_label"><?php echo $this->lang->line('patient_phone'); ?></label>

                        <input type="text" value="<?php echo $p_p; ?>" class="input-medium" name="p_p"/>

                    </div>

                    <div class="row-form">
                        <label class="select_label">回访员</label>
                        <input type="text" value="<?php echo $a_h; ?>" class="input-medium" name="a_h"/>
                    </div>
                </div>
                <div class="span3">

                    <div class="row-form">

                        <label class="select_label">QQ</label>

                        <input type="text" value="<?php echo $p_qq; ?>" class="input-medium" name="p_qq"/>

                    </div>

                    <div class="row-form">
                        <label class="select_label">微信</label>
                        <input type="text" value="<?php echo $p_wx; ?>" class="input-medium" name="p_wx"/>
                    </div>
                </div>
                <div class="order_btn1 span3">
                    <button type="submit" class="btn btn-success"
                            onsubmit="_czc.push(['_trackEvent', '预约列表', '<?php echo $admin['name']; ?>', '搜索','','']);">
                        搜索
                    </button>

                    <!--<span style="margin-left: 10px;"> <input type="button" class="input_search"
                                                             style="vertical-align:middle;height:30px; cursor:pointer;"
                                                             value="导出" onClick="exprot_gonghai()"/>-->

                </div>
            </form>
            <div class="row-fluid">
                <div class="span12">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="list_table">
                        <thead>
                        <tr>
                            <th width="50"><?php echo $this->lang->line('order_no'); ?></th>
                            <th><?php echo $this->lang->line('patient_info'); ?></th>
                            <th width="200"><?php echo $this->lang->line('time'); ?></th>
                            <th width="80">途径/性质</th>
                            <th width="70"><?php echo $this->lang->line('order_keshi'); ?></th>
                            <th width="70"><?php echo $this->lang->line('patient_jibing'); ?></th>
                            <th width="65">咨询/医生</th>
                            <th width="200"><?php echo $this->lang->line('visit'); ?></th>
                            <th width="200"><?php echo $this->lang->line('notice'); ?></th>
                            <th width="70"
                                style="border-right:1px solid #9D4A9C;"><?php echo $this->lang->line('action'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (empty($order_list)) {
                            echo "<tr style='color:red;'><td colspan='10'>抱歉！当前暂时没有相关公海患者数据！</td></tr>";
                        } else {
                            $i = 0;

                            if (strcmp($_COOKIE['l_admin_action'], 'all') == 0) {
                                $l_admin_action = array("179");
                            } else {
                                $l_admin_action = explode(',', $_COOKIE['l_admin_action']);
                            }
                            foreach ($order_list as $item):

                                ?>
                                <!-- 系统添加时间 -->
                                <input type="hidden" id="order_add_time_check_<?php echo $item['order_id']; ?>"
                                       value="<?php echo $item['order_addtime']; ?>"/>
                                <!--  当前所属咨询员 -->
                                <input type="hidden" id="admin_id_check_<?php echo $item['order_id']; ?>"
                                       value="<?php echo $item['admin_id']; ?>"/>
                                <input type="hidden" id="order_hidden_time_check_<?php echo $item['order_id']; ?>"
                                       value="<?php echo $item['order_time']; ?>"/>
                                <input type="hidden" id="order_time_duan_check_<?php echo $item['order_id']; ?>"
                                       value="<?php echo $item['order_time_duan']; ?>"/>

                                <tr class="<?php if ($i % 2) {
                                    echo 'td1';
                                } ?> " style="height:90px;">

                                    <td>
                                        <!--       <a href="?c=order&m=order_info&order_id= echo $item['order_id']; ?>&p=1" target="_blank"> echo $item['order_no']; ?></a><br />-->

                                        <a style="cursor:pointer;color:#333333;"
                                           id="order_no_<?php echo $item['order_id']; ?>" <?php if (in_array(143, $admin_action) || $_COOKIE['l_admin_action'] == 'all' || $item['hos_id'] == 1) {
                                            echo " onclick='open7(" . $item['order_id'] . ");'";
                                        } else {
                                            echo "";
                                        } ?>><?php echo $item['order_no']; ?></a>

                                        <br/>
                                        <?php if ($item['is_first']) {
                                            echo "初诊";
                                        } else {
                                            echo "<font color='#FF0000'>复诊</font>";
                                        } ?><br/>

                                        <!-- 权限判断  -->
                                        <input type="hidden" id="l_admin_action"
                                               value="<?php echo $_COOKIE['l_admin_action']; ?>">
                                        <!-- 岗位角色  -->
                                        <input type="hidden" id="l_rank_type" value="<?php echo $rank_type; ?>">

                                    </td>
                                    <td style="text-align:left;">
                                        <div id="pat_<?php echo $item['order_id']; ?>">
                                            姓名：<font
                                                    color='#FF0000'><b><?php echo $item['pat_name'] ?></b></font>（<?php echo $item['pat_sex']; ?>
                                            、<?php echo $item['pat_age'] ?>岁）
                                            <a href="#kefu_talk" title="对话记录"
                                               id="talk_status_<?php echo $item['order_id']; ?>" role="button"
                                               data-toggle="modal"
                                               onClick="kefu_talk('<?php echo $item['order_id'] ?>');_czc.push(['_trackEvent', '留联相关', '<?php echo $admin['name']; ?>', '对话记录','','']);"><img
                                                        src="static/img/talk<?php if (empty($item['con_content'])) {
                                                            echo '_g';
                                                        } ?>.png"
                                                        width="16px;"/></a>

                                            <br/>

                                            电话：<font
                                                    id="pat_phone_<?php echo $item['order_id']; ?>"><?php echo $item['pat_phone'];
                                                if (!empty($item['pat_phone1'])) {
                                                    echo "/" . $item['pat_phone1'];
                                                } ?></font><br/>

                                            地区：<?php
                                            if (@$item['pat_province'] > 0) {
                                                echo $area[$item['pat_province']]['region_name'];
                                            }
                                            if (@$item['pat_city'] > 0) {
                                                echo "、" . $area[$item['pat_city']]['region_name'];
                                            }
                                            if (@$item['pat_area'] > 0) {
                                                echo "、" . $area[$item['pat_area']]['region_name'];
                                            }

                                            ?><br/>

                                            <?php

                                            if (!empty($item['pat_qq'])) {
                                                echo $item['pat_qq'] . "(QQ)";
                                            }
                                            if (!empty($item['pat_weixin'])) {
                                                echo "      " . $item['pat_weixin'] . "(微信) ";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td style="text-align:left;">
                                        <?php echo $this->lang->line('order_addtime'); ?>
                                        ：<?php echo $item['order_addtime']; ?><br/>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($from_list[$item['from_parent_id']])) {
                                            echo $from_list[$item['from_parent_id']]['from_name'] . "<br />";
                                        }
                                        if (isset($from_arr[$item['from_id']])) {
                                            echo $from_arr[$item['from_id']]['from_name'] . "<br />";
                                        }
                                        if (isset($type_list[$item['order_type']])) {
                                            echo $type_list[$item['order_type']]['type_name'];
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        if (isset($keshi[$item['keshi_id']])) {
                                            echo $keshi[$item['keshi_id']]['keshi_name']; ?>
                                            <input type="hidden" id="<?php echo $item['order_id']; ?>_keshi_id"
                                                   value="<?php echo $item['keshi_id']; ?>">
                                        <?php }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if (isset($jibing[$item['jb_parent_id']])) {
                                            echo $jibing[$item['jb_parent_id']]['jb_name'];
                                        }
                                        if (isset($jibing[$item['jb_id']])) {
                                            echo "<br />" . $jibing[$item['jb_id']]['jb_name'];
                                        }
                                        ?></td>
                                    <td>
                                        <?= $item['admin_name'] ?>
                                        <br/><br/>
                                        <input type="hidden" name="hos_id_<?php echo $item['order_id']; ?>"
                                               id="hos_id_<?php echo $item['order_id']; ?>"
                                               value="<?php echo $item['hos_id']; ?>"/>
                                        <input type="hidden" name="keshi_id_<?php echo $item['order_id']; ?>"
                                               id="keshi_id_<?php echo $item['order_id']; ?>"
                                               value="<?php echo $item['keshi_id']; ?>"/>

                                    </td>
                                    <td style="position:relative;">
                                        <div class="remark"
                                             style="height: 70px; background: none; z-index: 1; padding-bottom: 10px;">
                                            <?php foreach ($order_remark_list as $order_remark_list_t) {
                                                if ($order_remark_list_t['order_id'] == $item['order_id'] && $order_remark_list_t['mark_type'] == 3) {
                                                    ?>
                                                    <blockquote>
                                                        <p><?php echo $order_remark_list_t['mark_content']; ?></p>
                                                        <small>
                                                            <a href="###"><?php echo $order_remark_list_t['admin_name']; ?></a>
                                                            <cite><?php echo date("Y-m-d H:i:s", $order_remark_list_t['mark_time']); ?></cite>
                                                        </small>
                                                    </blockquote>
                                                <?php }
                                            } ?>
                                        </div>
                                    </td>
                                    <td style="position:relative;">
                                        <div class="remark"
                                             style="height: 70px; background: none; z-index: 1; padding-bottom: 10px;">
                                            <?php foreach ($order_remark_list as $order_remark_list_t) {
                                                if ($order_remark_list_t['order_id'] == $item['order_id'] && $order_remark_list_t['mark_type'] != 3) {
                                                    ?>
                                                    <blockquote class="g">
                                                        <p style="font-size:12px;"><?php echo $order_remark_list_t['mark_content']; ?></p>
                                                        <small>
                                                            <a href="###"><?php echo $order_remark_list_t['admin_name']; ?></a>
                                                            <cite><?php echo date("Y-m-d H:i:s", $order_remark_list_t['mark_time']); ?></cite>
                                                        </small>
                                                    </blockquote>
                                                    <?php
                                                }
                                            } ?></div>
                                    </td>
                                    <td>
                                        <div id="add_person_<?= $item['order_id'] ?>"><a href="javascript:;" data-id="<?php echo $item['order_id'] ?>" class=" btn btn-info btn-fetch">捞取</a></div>
                                        <a href="#kefu_hf" title="回访记录" id="hf_status_<?php echo $item['order_id']; ?>"
                                           class="btn btn-info" role="button" data-toggle="modal"
                                           onClick="kefu_hf('<?php echo $item['order_id'] ?>');">回访</a>
                                    </td>

                                </tr>
                                <?php
                                $i++;
                            endforeach;
                        }
                        ?>

                        </tbody>
                    </table>
                    <?php echo $page; ?>
                    <div style="margin-bottom:30px;"></div>
                    <div id="kefu_hf" class="modal hide fade" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1">回访记录</h3>
                        </div>
                        <div class="modal-body">
                            <form action="?c=order&m=ajax_hf_add_liulian" method="post" id="hf_form"
                                  style="width:100%;">
                                <div class="control-group order_from">
                                    <label class="control-label">记录详情</label>
                                    <textarea class="input-xxlarge " rows="2" name="msg_hf" id="msg_hf"
                                              style="width:500px;"></textarea>
                                    <input type="hidden" name="hf_order_id" id="hf_order_id"><!-- 隐藏留联单ID -->
                                    <button type="button" id="hf_sumbit" class="btn btn-success"
                                            style="background-color:#00a186;"><i
                                                class="icon-ok"></i> <?php echo $this->lang->line('submit'); ?>
                                    </button>
                                    <span style="color:red" id="hf_span"></span>
                                    <span id="hf_span_ok"></span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-body" id="hf">
                            <div class="left hf_left"
                                 style="width:100%; height:300px; float:center; overflow-y: auto; overflow-x: hidden;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭</button>
                        </div>
                    </div>
                    <div id="kefu_talk" class="modal hide fade" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1">对话记录</h3>
                        </div>
                        <div class="modal-body" id="con_content"></div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭</button>
                        </div>
                    </div>
                    <div id="kefu_talk" class="modal hide fade" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel1" aria-hidden="true" style="width:80%; left:30%;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel1">对话记录</h3>
                        </div>
                        <div class="modal-body" id="con_content"></div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"> 关闭</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
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
<script type="text/javascript" src="static/js/date.js"></script>
<script type="text/javascript" src="static/js/daterangepicker.js"></script>
<script src="static/js/datepicker/js/datepicker.js"></script>
<script src="static/vendor/layui/layui.js"></script>


<script language="javascript">

    layui.use('layer', function(){
        var layer = layui.layer;

        $(".btn-fetch").click(function () {
            var id = parseInt($(this).attr("data-id"));
            layer.confirm('确定捞取吗？', {
                icon:0,
                btn: ['确认','取消'] //按钮
            }, function(){
                layer.msg('捞取成功', {icon: 1, time: 1000},function () {
                    window.location.href = "?c=liuliangonghai&m=fetch&order_id="+id;
                    //window.location.href = "?c=liuliangonghai&m=llindex";
                });
            });
        });
    });



    $(document).ready(function (e) {

        $(".anniu").css("display", "block");
        $('.divdate').DatePicker({
            flat: true,
            date: ['<?php echo $start_date; ?>', '<?php echo $end_date; ?>'],
            current: '<?php echo $end_date; ?>',
            format: 'Y年m月d日',
            calendars: 2,
            mode: 'range',
            starts: 1,
            onChange: function (formated) {
                $('#inputDate').val(formated.join(' - '));
            }
        });
        $('.date_div').css("display", "none");
        $(".anniu .guanbi").click(function () {
            $('.date_div').css("display", "none");
        });
        $("#inputDate").focus(function () {
            $('.date_div').css("display", "block");
        });
        $(".anniu .today").click(function () {
            $('#inputDate').val(get_day(0) + " - " + get_day(0));
            $('.date_div').css("display", "none");
        });
        $(".anniu .week").click(function () {
            $('#inputDate').val(get_day(-6) + " - " + get_day(0));
            $('.date_div').css("display", "none");
        });
        $(".anniu .month").click(function () {
            $('#inputDate').val(get_day(-29) + " - " + get_day(0));
            $('.date_div').css("display", "none");
        });
        $(".anniu .year").click(function () {
            $('#inputDate').val(get_day(-364) + " - " + get_day(0));
            $('.date_div').css("display", "none");
        });
        $("#hos_id").change(function () {
            var hos_id = $(this).val();
            ajax_get_keshi(hos_id, 0);
        });

        $("#keshi_id").change(function () {
            var keshi_id = $(this).val();
        });


        //添加时间的选择
        $('.lxdate').DatePicker({

            flat: true,

            date: [''],

            current: '',

            format: 'Y-m-d',

            calendars: 1,

            starts: 1,

            onChange: function (formated) {
                $('#nextdate').val(formated);
                $('.date_lx').hide();

            }

        });
        $("#nextdate").focus(function () {

            $('.date_lx').css("display", "block");

            $('.date_lx .datepicker').css({"width": "210px", 'height': '160px', 'background': 'black'});

        });

        <?php if($hos_id > 0):?>
        ajax_get_keshi(<?php echo $hos_id?>, <?php echo $keshi_id?>);
        <?php endif;?>
        <?php if($f_p_i > 0):?>
        ajax_from(<?php echo $f_p_i?>, <?php echo $f_i?>);
        <?php endif;?>
    });



    function get_day(day) {
        var today = new Date();
        var targetday_milliseconds = today.getTime() + 1000 * 60 * 60 * 24 * day;
        today.setTime(targetday_milliseconds);
        /* 注意，这行是关键代码 */
        var tYear = today.getFullYear();
        var tMonth = today.getMonth();
        var tDate = today.getDate();
        tMonth = doHandleMonth(tMonth + 1);
        tDate = doHandleMonth(tDate);
        return tYear + "年" + tMonth + "月" + tDate + "日";
    }

    function doHandleMonth(month) {
        var m = month;
        if (month.toString().length == 1) {
            m = "0" + month;
        }
        return m;
    }

    function ajax_from(parent_id, from_id) {
        $.ajax({
            type: 'post',
            url: '?c=order&m=from_order_ajax',
            data: 'parent_id=' + parent_id + '&from_id=' + from_id,
            success: function (data) {
                $("#from_id").html(data);
            },
            complete: function (XHR, TS) {
                XHR = null;
            }
        });
    }

    function ajax_get_keshi(hos_id, check_id) {
        $.ajax({
            type: 'post',
            url: '?c=order&m=keshi_list_ajax',
            data: 'hos_id=' + hos_id + '&check_id=' + check_id,
            success: function (data) {
                $("#keshi_id").html(data);
            },
            complete: function (XHR, TS) {
                XHR = null;
            }
        });
    }

    function open7(order_id) {
        var diag = new Dialog();
        diag.Width = 820;
        diag.Height = 800;
        diag.Title = "留联公海日志";
        diag.URL = "?c=liuliangonghai&m=log&order_id=" + order_id;
        diag.show();
    }

    $(".remark").hover(
        function () {
            $(this).css({height: "auto", background: "#f7f7f7", "z-index": "999", "padding-bottom": "50px"});
        },function () {
            $(this).css({height: "70px", background: "none", "z-index": "1", "padding-bottom": "10px"});
        }
    );

    $("#hf_sumbit").click(function () {
        $("#hf_span").html("");
        $("#hf_span_ok").html("");
        if ($("#msg_hf").val() == null || $("#msg_hf").val() == '') {
            $("#hf_span").html("请填入记录");
        } else if ($("#msg_hf").val().length > 1800) {
            $("#hf_span").html("你填的太多了,只可以填写500个字。");
        } else {
            // 通过 form 的 id 取得 form
            var $form = $('#hf_form');
            $.ajax({
                type: 'post',
                url: $form.attr("action"),
                data: 'hf_order_id=' + $("#hf_order_id").val() + "&msg_hf=" + $("#msg_hf").val(),
                success: function (data) {
                    $("#hf_span_ok").html("添加成功，请看下面的第一条记录。");
                    $(".hf_left").html(data + $(".hf_left").html());
                    window.location.reload();
                },
                complete: function (XHR, TS) {
                    XHR = null;
                }
            });
        }
    });
    function kefu_hf(order_id) {
        $("#msg_hf").val("");
        $("#hf_order_id").val(order_id);
        if (order_id >= 1) {
            $.ajax({
                type: 'post',
                url: '?c=order&m=hf_info_liulian',
                data: 'order_id=' + order_id,
                success: function (data) {
                    $(".hf_left").html(data);
                },
                complete: function (XHR, TS) {
                    XHR = null;
                }
            });
        }
    }
    function kefu_talk(order_id) {
        if (order_id >= 1) {
            $.ajax({
                type: 'post',
                url: '?c=order&m=talk_info_liulian_gh',
                data: 'order_id=' + order_id,
                success: function (data) {
                    $("#con_content").html(data);
                },
                complete: function (XHR, TS) {
                    XHR = null;
                }
            });
        }
    }

</script>
</body>
</html>