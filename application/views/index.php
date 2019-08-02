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
    <link href="static/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="static/css/bootstrap-responsive.min.css" rel="stylesheet"/>
    <link href="static/css/font-awesome.css" rel="stylesheet"/>
    <link href="static/css/style.css" rel="stylesheet"/>
    <link href="static/css/style-responsive.css" rel="stylesheet"/>
    <link href="static/css/style-default.css" rel="stylesheet" id="style_color"/>
    <link href="static/css/bootstrap-fullcalendar.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="static/css/metro-gallery.css" media="screen"/>
    <link href="static/js/datepicker/css/datepicker.css" rel="stylesheet"/>
    <style>
        .date_div {
            position: absolute;
            float: left;
            position: fixed;
            z-index: 99999;
            margin-left: 5%;
            top: 205px;
        }

        .boxLoading {
            display: none;
            width: 50px;
            height: 50px;
            margin: auto;
            position: absolute;
            left: 0;
            right: 0;
            top: 100px;
            z-index: 99999;
        }

        .boxLoading:before {
            content: '';
            width: 50px;
            height: 5px;
            background: #000;
            opacity: 0.1;
            position: absolute;
            top: 59px;
            left: 0;
            border-radius: 50%;
            animation: box-loading-shadow 0.5s linear infinite;
        }

        .boxLoading:after {
            content: '';
            width: 50px;
            height: 50px;
            background: #06B8C0;
            animation: box-loading-animate 0.5s linear infinite;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 3px;
        }

        @keyframes box-loading-animate {
            17% {
                border-bottom-right-radius: 3px;
            }
            25% {
                transform: translateY(9px) rotate(22.5deg);
            }
            50% {
                transform: translateY(18px) scale(1, .9) rotate(45deg);
                border-bottom-right-radius: 40px;
            }
            75% {
                transform: translateY(9px) rotate(67.5deg);
            }
            100% {
                transform: translateY(0) rotate(90deg);
            }
        }

        @keyframes box-loading-shadow {
            0%, 100% {
                transform: scale(1, 1);
            }
            50% {
                transform: scale(1.2, 1);
            }
        }
    </style>

</head>
<body class="fixed-top" style="background-color: #eeeeee;">
<?php echo $top; ?>
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div id="container" class="row-fluid">
    <!-- BEGIN SIDEBAR -->
    <?php echo $sider_menu; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN PAGE -->
    <div id="main-content" style="background-color: #dcdcdc;">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <?php echo $themes_color_select; ?>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

            <div id="page-wraper">
                <?php if ($one_day_data == '1') { ?>
                    <div class="row-fluid">
                        <div class="metro-nav">
                            <div style="margin-bottom: -10px;margin-top:30px;">
                                <?php if ($hosnum > 0): ?>
                                    <form action="?c=index&m=index" method="post">

                                        <span style="line-height:36px; display:block;float:left;font-size:16px;">所选医院：</span>

                                        <div style="margin-left:50px; padding-top:3px;">

                                            <SELECT style="width:150" name="hos_id" id="hos_id">
                                                <?php
                                                /**  2016 10 22 单独显示仁爱医院 产科 和妇科 在第一个下拉框里面 **/
                                                if ($admin['rank_id'] == 443 || $admin['rank_id'] == 332 || $admin['rank_id'] == 186 || $admin['rank_id'] == 3 || $admin['rank_id'] == 71 || $admin['rank_id'] == 1 || $admin['rank_id'] == 5 || $admin['rank_id'] == 21 || $admin['rank_id'] == 7 || $admin['rank_id'] == 9 || $admin['rank_id'] == 82) { ?>
                                                    <?php if ($input == '0') { ?>
                                                        <OPTION value="1_1">深圳仁爱医院妇科</OPTION>
                                                        <OPTION value="1_7">深圳仁爱医院产科</OPTION>
                                                        <OPTION value="1_32">深圳仁爱医院不孕不育科</OPTION>
                                                        <OPTION value="45_114_116">厦门鹭港医院妇科</OPTION>
                                                        <OPTION value="45_115">厦门鹭港医院产科</OPTION>
                                                        <OPTION value="54_153">宁波新东方医院不孕不育科</OPTION>

                                                        <OPTION value="" selected='selected'>请选择···</OPTION>
                                                    <?php } else { ?>
                                                        <OPTION value="1_1"
                                                                <?php if ($input == '1_1'): ?>selected<?php endif; ?>>
                                                            深圳仁爱医院妇科
                                                        </OPTION>
                                                        <OPTION value="1_7"
                                                                <?php if ($input == '1_7'): ?>selected<?php endif; ?> >
                                                            深圳仁爱医院产科
                                                        </OPTION>
                                                        <OPTION value="1_32"
                                                                <?php if ($input == '1_32'): ?>selected<?php endif; ?> >
                                                            深圳仁爱医院不孕不育科
                                                        </OPTION>
                                                        <OPTION value="45_114_116"
                                                                <?php if ($input == '45_114_116'): ?>selected<?php endif; ?>>
                                                            厦门鹭港医院妇科
                                                        </OPTION>
                                                        <OPTION value="45_115"
                                                                <?php if ($input == '45_115'): ?>selected<?php endif; ?> >
                                                            厦门鹭港医院产科
                                                        </OPTION>
                                                        <OPTION value="54_153"
                                                                <?php if ($input == '54_153'): ?>selected<?php endif; ?> >
                                                            宁波新东方医院不孕不育科
                                                        </OPTION>
                                                        <OPTION value=""
                                                                <?php if ($input == '0'): ?>selected<?php endif; ?> >
                                                            请选择···
                                                        </OPTION>
                                                    <?php } ?>

                                                <?php } elseif ($admin['rank_id'] == 481) {
                                                    ?>
                                                    <?php if ($input == '0') { ?>
                                                        <OPTION value="1_1">深圳仁爱医院妇科</OPTION>
                                                        <OPTION value="1_7">深圳仁爱医院产科</OPTION>

                                                        <OPTION value="" selected='selected'>请选择···</OPTION>
                                                    <?php } else { ?>
                                                        <OPTION value="1_1"
                                                                <?php if ($input == '1_1'): ?>selected<?php endif; ?>>
                                                            深圳仁爱医院妇科
                                                        </OPTION>
                                                        <OPTION value="1_7"
                                                                <?php if ($input == '1_7'): ?>selected<?php endif; ?> >
                                                            深圳仁爱医院产科
                                                        </OPTION>
                                                        <OPTION value=""
                                                                <?php if ($input == '0'): ?>selected<?php endif; ?> >
                                                            请选择···
                                                        </OPTION>
                                                    <?php } ?>
                                                <?php } else {
                                                    ?>
                                                    <OPTION
                                                    value=""  <?php if ($input == ''): ?>selected<?php endif; ?>>
                                                        请选择···</OPTION><?php
                                                } ?>

                                                <?php
                                                $hospital_sort = array();
                                                foreach ($hospital as $v) {
                                                    $hospital_sort[] = $v['hos_name'] . '|' . $v['hos_id'];
                                                }

                                                foreach ($hospital_sort as $k => $v) {
                                                    $hospital_sort[$k] = iconv('UTF-8', 'GBK//IGNORE', $v);
                                                }
                                                foreach ($hospital_sort as $k => $v) {
                                                    $hospital_sort[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
                                                }
                                                rsort($hospital_sort);

                                                foreach ($hospital_sort as $hospital_sort_temp) {
                                                    $hos_id_arr = explode('|', $hospital_sort_temp);
                                                    if ($hos_id_arr[1] == 33) {//排除预约回收站
                                                        continue;
                                                    }
                                                    if ($_COOKIE['l_admin_id'] == 73) {//黄总权限
                                                        if (in_array($hos_id_arr[1], array(1, 33, 44, 36, 46, 50, 21))) {//排除这些项目
                                                            continue;
                                                        }
                                                    }
                                                    foreach ($hospital as $item) {
                                                        if ($hos_id_arr[1] == $item['hos_id']) { ?>
                                                            <OPTION value="<?php echo $item['hos_id']; ?>"
                                                                    <?php if ($input == $item['hos_id']){ ?>selected<?php } ?> ><?php echo $item['hos_name']; ?></OPTION>
                                                        <?php }
                                                    }
                                                } ?>


                                            </SELECT>
                                            <font style="line-height:36px;font-size:16px;margin-left: 20px;">所选科室：</font>
                                            <SELECT style="width:150" name="keshi_id" id="keshi_id">
                                                <OPTION value="">请选择科室···</OPTION>
                                            </SELECT>
<!--                                            <span id="fk">-->
<!--                                                <SELECT style="width:150" name="fuke" id="fuke"></SELECT>-->
<!--                                            </span>-->

                                            <input type="submit" value="提交" class="btn search_from"
                                                   style="margin-bottom:5px;background-color:#00a186;color:#fff;margin-left: 5px;"/>

                                        </div>

                                        <!--<div id="renaidiv" style="display: none;">
                                            <OPTION value="1">特殊选择···</OPTION>
                                            <OPTION value="1,32" <?php /*if ($fuke == '1,32') {
                                                echo "selected='selected'";
                                            } */?>>妇科+女性不孕
                                            </OPTION>
                                            <OPTION value="js,37" <?php /*if ($fuke == 'js,37') {
                                                echo "selected='selected'";
                                            } */?>>计生科
                                            </OPTION>
                                        </div>

                                        <div id="xiamendiv" style="display: none;">
                                            <OPTION value="114">特殊选择···</OPTION>
                                            <OPTION value="114,116" <?php /*if ($fuke == '114,116') {
                                                echo "selected='selected'";
                                            } */?>>妇科+女性不孕
                                            </OPTION>
                                        </div>-->


                                        <span style="line-height:36px; display:block;float:left;font-size:16px;">搜索时间：</span>

                                        <?php if (!empty($date)) { ?>
                                            <input id="inputDate" class="input-block-level" value="<?php echo $date; ?>"
                                                   style="width:240px;vertical-align:middle;height:16px;font-size:12px; "
                                                   name="date" type="text">
                                        <?php } else { ?>
                                            <input id="inputDate" class="input-block-level" value=""
                                                   style="width:240px;vertical-align:middle;height:16px;font-size:12px; "
                                                   name="date" type="text">
                                        <?php } ?>

                                    </form>
                                <?php endif; ?>

                                <div class="date_div">
                                    <div class="divdate"></div>
                                    <div class="anniu"><a href="javascript:;" class="btn btn-inverse guanbi">
                                            关闭 </a><br/><a href="javascript:;" class="btn btn-inverse today">
                                            今天 </a><br/><a href="javascript:;" class="btn btn-inverse week">
                                            一周 </a><br/><a href="javascript:;" class="btn btn-inverse month">
                                            一月 </a><br/><a href="javascript:;" class="btn btn-inverse year"> 一年 </a>
                                    </div>
                                </div>

                            </div>


                            <?php if (count($xiamen_data) > 0) { ?>
                                <?php
                                $date = urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time()));
                                $xm_yy_114_add_count = 0;
                                $xm_yy_115_add_count = 0;
                                foreach ($xiamen_data['xm_keshi_yy_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 114) {
                                        $xm_yy_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 115) {
                                        $xm_yy_115_add_count++;
                                    }
                                }
                                $xm_yd_114_add_count = 0;
                                $xm_yd_115_add_count = 0;
                                foreach ($xiamen_data['xm_keshi_yd_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 114) {
                                        $xm_yd_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 115) {
                                        $xm_yd_115_add_count++;
                                    }
                                }
                                $xm_hz_114_add_count = 0;
                                $xm_hz_115_add_count = 0;
                                foreach ($xiamen_data['xm_keshi_dz_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 114) {
                                        $xm_hz_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 115) {
                                        $xm_hz_115_add_count++;
                                    }
                                }
                                ?>

                                <!-- 	  <div class="fkyy">
				    <div class="fkyy">
					<div class="fkdz">
					 <div>
					<a href="?c=order&m=order_list&t=1&hos_id=45&keshi_id=114&date=<?php echo $date; ?>#厦门鹭港妇产医院妇科今日预约">
					 鹭港妇产医院妇科今日预约<span><?php echo $xm_yy_114_add_count; ?></span> </a>
                     </div>
                     <div>

                    <a href="?c=order&m=order_list&t=2&hos_id=45&keshi_id=114&date=<?php echo $date; ?>#厦门鹭港妇产医院妇科今日预到">
					鹭港妇产医院妇科今日预到<span><?php echo $xm_yd_114_add_count; ?></span> </a>
                       </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=45&keshi_id=114&date=<?php echo $date; ?>#厦门鹭港妇产医院妇科今日到诊" >
					鹭港妇产医院妇科今日到诊<span><?php echo $xm_hz_114_add_count; ?></span> </a>   </div>
					</div>
					</div>
				  </div>

					 <div class="fkyy" style="padding-right:4px;padding-buttom:4px;">
				      <div class="fkyy">
					<div class="fkdz">
					 <div>
					<a href="?c=order&m=order_list&t=1&hos_id=45&keshi_id=115&date=<?php echo $date; ?>#厦门鹭港妇产医院产科今日预约">
					鹭港妇产医院产科今日预约<span><?php echo $xm_yy_115_add_count; ?></span> </a>
                     </div>
                     <div>

                    <a href="?c=order&m=order_list&t=2&hos_id=45&keshi_id=115&date=<?php echo $date; ?>#厦门鹭港妇产医院产科今日预到">
					鹭港妇产医院产科今日预到<span><?php echo $xm_yd_115_add_count; ?></span> </a>
                     </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=45&keshi_id=115&date=<?php echo $date; ?>#厦门鹭港妇产医院产科今日到诊" >
					鹭港妇产医院产科今日到诊<span><?php echo $xm_hz_115_add_count; ?></span> </a>   </div>
					</div>
					</div>
				  </div>   -->
                            <?php } ?>

                            <?php if (count($xiamen_hz_data) > 0) { ?>
                                <?php
                                $xm_yy_114_add_count = 0;
                                $xm_yy_115_add_count = 0;
                                foreach ($xiamen_hz_data['xm_keshi_hz_yy_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 117) {
                                        $xm_yy_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 118) {
                                        $xm_yy_115_add_count++;
                                    }
                                }
                                $xm_yd_114_add_count = 0;
                                $xm_yd_115_add_count = 0;
                                foreach ($xiamen_hz_data['xm_keshi_hz_yd_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 117) {
                                        $xm_yd_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 118) {
                                        $xm_yd_115_add_count++;
                                    }
                                }
                                $xm_hz_114_add_count = 0;
                                $xm_hz_115_add_count = 0;
                                foreach ($xiamen_hz_data['xm_keshi_hz_dz_data'] as $xiamen_data_t) {
                                    if ($xiamen_data_t['keshi_id'] == 117) {
                                        $xm_hz_114_add_count++;
                                    }
                                    if ($xiamen_data_t['keshi_id'] == 118) {
                                        $xm_hz_115_add_count++;
                                    }
                                }
                                ?>

                                <!--	  <div class="fkyy">
				    <div class="fkyy">
					<div class="fkdz">
					 <div>
					<a href="?c=order&m=order_list&t=1&hos_id=46&keshi_id=117&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)妇科今日预约">
					鹭港妇产医院(合作)妇科今日预约<span><?php echo $xm_yy_114_add_count; ?></span> </a>
                     </div>
                     <div>

                    <a href="?c=order&m=order_list&t=2&hos_id=46&keshi_id=117&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)妇科今日预到">
					鹭港妇产医院(合作)妇科今日预到<span><?php echo $xm_yd_114_add_count; ?></span> </a>
                       </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=46&keshi_id=117&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)妇科今日到诊" >
					鹭港妇产医院(合作)妇科今日到诊<span><?php echo $xm_hz_114_add_count; ?></span> </a>   </div>
					</div>
					</div>
				  </div>

					 <div class="fkyy"  style="padding-right:4px;padding-buttom:4px;">
				      <div class="fkyy">
					<div class="fkdz">
					 <div>
					<a href="?c=order&m=order_list&t=1&hos_id=46&keshi_id=118&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)产科今日预约">
					鹭港妇产医院(合作)产科今日预约<span><?php echo $xm_yy_115_add_count; ?></span> </a>
                     </div>
                     <div>

                    <a href="?c=order&m=order_list&t=2&hos_id=46&keshi_id=118&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)产科今日预到">
					鹭港妇产医院(合作)产科今日预到<span><?php echo $xm_yd_115_add_count; ?></span> </a>
                     </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=46&keshi_id=118&date=<?php echo $date; ?>#厦门鹭港妇产医院(合作)产科今日到诊" >
					鹭港妇产医院(合作)产科今日到诊<span><?php echo $xm_hz_115_add_count; ?></span> </a>   </div>
					</div>
					</div>
				  </div>   -->
                            <?php } ?>


                            <?php if (isset($search_time_get_html)) {
                                echo $search_time_get_html;
                            } else { ?>
                                <?php if (isset($order[0]['renai_fk_order']) && isset($order[0]['renai_fk_order_to']) && ($admin['rank_id'] == 332 || $admin['rank_id'] == 186 || $admin['rank_id'] == 3 || $admin['rank_id'] == 71 || $admin['rank_id'] == 1 || $admin['rank_id'] == 5 || $admin['rank_id'] == 21 || $admin['rank_id'] == 7 || $admin['rank_id'] == 28 || $admin['rank_id'] == 9 || $admin['rank_id'] == 82)): ?>
                                    <!--	 <div style="width:12%;">
					 <div class="fkyy">
					<div class="tb1">
					</div>
					<div class="fkdz">
					 <div>
					<a href="?c=order&m=order_list&t=1&hos_id=<?php echo isset($order[0]['renai_fk_hos_id']) ? $order[0]['renai_fk_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_fk_keshi_id']) ? $order[0]['renai_fk_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱医院妇科今日预约">
					仁爱医院妇科今日预约<span><?php echo isset($order[0]['renai_ck_order_add_to']) ? $order[0]['renai_ck_order_add_to'] : 0; ?></span> </a>
                     </div>
                     <div>

                    <a href="?c=order&m=order_list&t=2&hos_id=<?php echo isset($order[0]['renai_fk_hos_id']) ? $order[0]['renai_fk_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_fk_keshi_id']) ? $order[0]['renai_fk_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱医院妇科今日预到">
					仁爱医院妇科今日预到<span><?php echo isset($order[0]['renai_fk_order']) ? $order[0]['renai_fk_order'] : 0; ?></span> </a>
                       </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=<?php echo isset($order[0]['renai_fk_hos_id']) ? $order[0]['renai_fk_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_fk_keshi_id']) ? $order[0]['renai_fk_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱医院妇科今日到诊" >
					仁爱医院妇科今日到诊<span><?php echo isset($order[0]['renai_fk_order_to']) ? $order[0]['renai_fk_order_to'] : 0; ?></span> </a>   </div>
					</div>
					</div>
					</div>   -->
                                <?php endif; ?>

                                <div class="all-panel">
                                    <div class="metro-nav-block nav-block-yellow"
                                         style="background-color: #00a187;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list_liulian&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#今天留联"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '今天留联','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($today_ll_count) ? $today_ll_count : 0; ?></div>
                                            <div class="status">今天留联</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-yellow"
                                         style="background-color: #00a187;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#今天预约"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '今天预约','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($order[0]['add']) ? $order[0]['add'] : 0; ?></div>
                                            <div class="status">今天预约</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-yellow"
                                         style="background-color: #00a187;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=2&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#今天预到"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '今天预到','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($order[0]['order']) ? $order[0]['order'] : 0; ?></div>
                                            <div class="status">今天预到</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-yellow"
                                         style="background-color: #00a187;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=3&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#今天来院"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '今天来院','','']);">
                                            <i class="icon-user-md"></i>
                                            <div class="info"><?php
                                                $gonghai_come = isset($order[0]['gonghai_come']) ? $order[0]['gonghai_come'] : 0;
                                                $gonghai_order = isset($order[0]['gonghai_order']) ? $order[0]['gonghai_order'] : 0;
                                                $order_come = isset($order[0]['come']) ? $order[0]['come'] : 0;
                                                // 仁爱的计数不算公海的数量  ，其他医院的计算 预约数量+公海数量
                                                if ($input == '1_1' || $input == '1') {
                                                    $total = $order_come;
                                                } else {
                                                    $total = $gonghai_come + $order_come;
                                                }
                                                echo $total; ?>
                                                <hr/>
                                                <?php if ($order[0]['order'] > 0) {
                                                    echo number_format((($total / ($order[0]['order'])) * 100), 0, '.', '');
                                                } else {
                                                    echo 0;
                                                } ?>%
                                            </div>

                                            <div class="status">今天来院</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-yellow"
                                         style="background-color: #00a187;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title="" href="javaScript:void(0);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($today_fz_count) ? $today_fz_count : 0; ?></div>
                                            <div class="status">今天复诊</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-green"
                                         style="background-color: #fe9b00;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time() - 86400) . " - " . date("Y年m月d日", time() - 86400)); ?>#昨日预约"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '昨日预约','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($order[1]['add']) ? $order[1]['add'] : 0; ?></div>
                                            <div class="status">昨日预约</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-green"
                                         style="background-color: skyblue;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=2&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time() + 86400) . " - " . date("Y年m月d日", time() + 86400)); ?>#明日预到"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '明日预到','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($tomo_order_count) ? $tomo_order_count : 0; ?></div>
                                            <div class="status">明日预到</div>
                                        </a>
                                    </div>

                                    <?php if (isset($order[0]['renai_ck_add']) && isset($order[0]['renai_ck_add_to']) && ($admin['rank_id'] == 332 || $admin['rank_id'] == 186 || $admin['rank_id'] == 3 || $admin['rank_id'] == 71 || $admin['rank_id'] == 1 || $admin['rank_id'] == 5 || $admin['rank_id'] == 21 || $admin['rank_id'] == 7 || $admin['rank_id'] == 28 || $admin['rank_id'] == 9 || $admin['rank_id'] == 82)): ?>
                                        <!--	<div style="width:12%;">
					 <div class="fkyy">
					<div class="tb2">
					</div>
					<div class="fkdz">
					<div>
                    <a href="?c=order&m=order_list&t=1&hos_id=<?php echo isset($order[0]['renai_ck_hos_id']) ? $order[0]['renai_ck_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_ck_keshi_id']) ? $order[0]['renai_ck_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱医院产科今日预约">
					仁爱医院产科今日预约<span><?php echo isset($order[0]['renai_ck_order_add']) ? $order[0]['renai_ck_order_add'] : 0; ?></span> </a>
                    </div>
                     <div>
                    <a  href="?c=order&m=order_list&t=2&hos_id=<?php echo isset($order[0]['renai_ck_hos_id']) ? $order[0]['renai_ck_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_ck_keshi_id']) ? $order[0]['renai_ck_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱院医产科今预到" >
					仁爱医院产科今日预到<span><?php echo isset($order[0]['renai_ck_add']) ? $order[0]['renai_ck_add'] : 0; ?></span></a>
                    </div>
                     <div>
                    <a href="?c=order&m=order_list&t=3&hos_id=<?php echo isset($order[0]['renai_ck_hos_id']) ? $order[0]['renai_ck_hos_id'] : 0; ?>&keshi_id=<?php echo isset($order[0]['renai_ck_keshi_id']) ? $order[0]['renai_ck_keshi_id'] : 0; ?>&date=<?php echo urlencode(date("Y年m月d日", time()) . " - " . date("Y年m月d日", time())); ?>#仁爱医院产科今日到诊">
					仁爱医院产科今日到诊<span><?php echo isset($order[0]['renai_ck_add_to']) ? $order[0]['renai_ck_add_to'] : 0; ?></span></a>   </div>
					</div>
					</div>
					</div>   -->
                                    <?php endif; ?>

                                    <div class="metro-nav-block nav-block-red"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list_liulian&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time() - 24 * 60 * 60) . " - " . date("Y年m月d日", time() - 24 * 60 * 60)); ?>#昨日留联"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '昨日留联','','']);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($yesterday_ll_count) ? $yesterday_ll_count : 0; ?></div>
                                            <div class="status">昨日留联</div>
                                        </a>
                                    </div>

                                    <div class="metro-nav-block nav-block-red"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list_liulian&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php

                                           $time1 = date('Y-m-01', time());
                                           $now_time = strtotime("$time1 +1 month -1 day");
                                           echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time)); ?>#本月预约">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($month_ll_count) ? $month_ll_count : 0; ?></div>
                                            <div class="status">本月留联</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-red"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php

                                           $time1 = date('Y-m-01', time());
                                           $now_time = strtotime("$time1 +1 month -1 day");
                                           echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time)); ?>#本月预约">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($yue['add']) ? $yue['add'] : 0; ?></div>
                                            <div class="status">本月预约</div>
                                        </a>
                                    </div>
                                    <!--
					 <div class="metro-nav-block nav-block-red" style="background-color: #da542e;width:13%;">
                        <a data-original-title="" href="?c=order&m=order_list&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php

                                    $time1 = date('Y-m-01', time());
                                    $now_time = strtotime("$time1 +1 month -1 day");
                                    echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time)); ?>#本月预约">
                            <i class="icon-hospital"></i>
                            <div class="info"><?php echo isset($yue['add']) ? $yue['add'] : 0; ?></div>
                            <div class="status">本月预到</div>
                        </a>
                    </div>-->

                                    <div class="metro-nav-block nav-block-red"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=gonghai&m=gonghai&t=1&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php
                                           $time2 = date('Y-m-01', time());
                                           $now_time2 = strtotime("$time2 +1 month -1 day");
                                           echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time2)); ?>#本月来院">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($month_gh_count) ? $month_gh_count : 0; ?></div>
                                            <div class="status">本月公海</div>
                                        </a>
                                    </div>

                                    <div class="metro-nav-block nav-block-orange"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=3&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php
                                           $time2 = date('Y-m-01', time());
                                           $now_time2 = strtotime("$time2 +1 month -1 day");
                                           echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time2)); ?>#本月来院">
                                            <i class="icon-user-md"></i>
                                            <div class="info"><?php
                                                $aa = isset($yue['come']) ? $yue['come'] : 0;
                                                $bb = isset($yue['gonghai_come']) ? $yue['gonghai_come'] : 0;
                                                // 仁爱的计数不算公海的数量  ，其他医院的计算 预约数量+公海数量
                                                if ($input == '1_1' || $input == '1') {
                                                    $cc = $aa;
                                                } else {
                                                    $cc = $aa + $bb;
                                                }
                                                echo $cc; ?></div>
                                            <div class="status">本月来院</div>
                                        </a>
                                    </div>

                                    <div class="metro-nav-block nav-block-red"
                                         style="background-color: #da542e;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title="" href="javaScript:void(0);">
                                            <i class="icon-hospital"></i>
                                            <div class="info"><?php echo isset($month_fz_count) ? $month_fz_count : 0; ?></div>
                                            <div class="status">本月复诊</div>
                                        </a>
                                    </div>
                                    <div class="metro-nav-block nav-block-green"
                                         style="background-color: #fe9b00;<?php if ($input == '1_1' || $input == '1_7') { ?>width:13%;<?php } else { ?>width:13%;<?php } ?>">
                                        <a data-original-title=""
                                           href="?c=order&m=order_list&t=3&hos_id=<?php echo $hos_id; ?>&keshi_id=<?php echo $keshi_id; ?>&date=<?php echo urlencode(date("Y年m月d日", time() - 86400) . " - " . date("Y年m月d日", time() - 86400)); ?>#昨日来院"
                                           onClick="_czc.push(['_trackEvent', '系统首页', '<?php echo $admin['name']; ?>', '昨日来院','','']);">
                                            <i class="icon-user-md"></i>
                                            <div class="info"><?php
                                                $a = isset($order[1]['come']) ? $order[1]['come'] : 0;
                                                $b = isset($order[1]['gonghai_come']) ? $order[1]['gonghai_come'] : 0;
                                                // 仁爱的计数不算公海的数量  ，其他医院的计算 预约数量+公海数量
                                                if ($input == '1_1' || $input == '1') {
                                                    $c = $a;
                                                } else {
                                                    $c = $a + $b;
                                                }
                                                echo $c; ?>
                                                <hr/>
                                                <?php if ($order[1]['order'] > 0) {
                                                    echo number_format((($c / $order[1]['order']) * 100), 0, '.', '');
                                                } else {
                                                    echo 0;
                                                } ?>%
                                            </div>
                                            <div class="status">昨日来院</div>
                                        </a>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>

                    <div class="row-fluid" style="margin-left: 0px;" id="month_data">

                    </div>
                    <div class="row-fluid">
                        <div class="">
                            <!-- BEGIN NOTIFICATIONS PORTLET-->
                            <div class="widget purple" style="width:100%;border: 1px solid #e7e7e7;float:left">
                                <div class="widget-title" style="background-color:#00a186;">
                                    <h4><i class="icon-hospital"></i>每日数据 </h4>
                                    <span class="tools">
                               <a href="javascript:;" class="icon-chevron-down"></a>
                               <a href="javascript:;" class="icon-remove"></a>
                           </span>
                                </div>
                                <div class="widget-body">
                                    <div style="height:auto; width:100%; display:block;">

                                        <table class="table table-striped">

                                            <tr>
                                                <th>日期</th>
                                                <th>预约</th>
                                                <th>预到</th>
                                                <th>到诊</th>
                                                <th>预到到诊率</th>

                                            </tr>
                                            <?php
                                            foreach ($order

                                            as $key => $val){


                                            ?>
                                            <tr align=center>
                                                <td><?php echo date("y-m-d", $val['time']);
                                                    if (date("w", $val['time']) == 6 || date("w", $val['time']) == 0) {
                                                        echo "*";
                                                    }
                                                    ?></td>
                                                <td><?php echo $val['add']; ?></td>
                                                <td><?php echo $val['order']; ?></td>
                                                <td><?php echo $val['come']; ?></td>
                                                <td><?php
                                                    if ($val['order'] == 0) {

                                                        echo '0%';

                                                    } else {

                                                        echo number_format((($val['come'] / $val['order']) * 100), 2, '.', '') . '%';

                                                    }

                                                    ?></td>

                                                <?php
                                                }
                                                ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- END NOTIFICATIONS PORTLET-->

                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- END PAGE CONTENT-->
        </div>
        <!-- END PAGE CONTAINER-->
    </div>
    <!-- END PAGE -->
</div>


<script src="static/js/jquery.js"></script>
<script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
<script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="static/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="static/js/fullcalendar.min.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<!-- ie8 fixes -->
<!--[if lt IE 9]>
<script src="static/js/excanvas.js"></script>
<script src="static/js/respond.js"></script>
<![endif]-->

<script type="text/javascript" src="static/js/date.js"></script>
<script type="text/javascript" src="static/js/daterangepicker.js"></script>
<script src="static/js/datepicker/js/datepicker.js"></script>

<script src="static/js/common-scripts.js"></script>
<script src="static/js/c/esl.js"></script>
<script language="javascript" type="text/javascript">

    $(document).ready(function () {
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

        $("#nextdate").focus(function () {

            $('.date_lx').css("display", "block");

            $('.date_lx .datepicker').css({"width": "210px", 'height': '160px', 'background': 'black'});

        });

        $('.date_div').css("display", "none");

        $(".anniu .guanbi").click(function () {

            $('.date_div').css("display", "none");

        });

        $("#inputDate").focus(function () {
            $("#gaoji").hide();
            $('.date_div').css("display", "block");
            $('.date_div .datepicker').css({"width": "420px", 'height': '160px', 'background': 'black'});
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

        /*if ($("#hos_id").val() == 1) {
            $("#fuke").html($("#renaidiv").html());
            $("#fk").show();
        } else if ($("#hos_id").val() == 45) {
            $("#fuke").html($("#xiamendiv").html());
            $("#fk").show();
        } else {
            $("#fuke").html("");
            $("#fk").hide();
            $("#fuke").val() == "1";
        }*/

        var hos_id = $("#hos_id").val();
        var keshi_id =<?php if (!empty($input_keshi)) {
            echo $input_keshi;
        } else {
            echo '0';
        }?>;
        ajax_get_keshi(hos_id, keshi_id);
        ajax_get_month(hos_id, keshi_id);
        //ajax_get_advisory_rankings(hos_id, keshi_id);

    });

    $("#hos_id").change(function () {
        if ($("#hos_id").val() == 1) {
            $("#fuke").html($("#renaidiv").html());
            $("#fk").show();
        } else if ($("#hos_id").val() == 45) {
            $("#fuke").html($("#xiamendiv").html());
            $("#fk").show();
        } else {
            $("#fk").hide();
        }
        var hos_id = $(this).val();

        ajax_get_keshi(hos_id, 0);

    });

    function ajax_get_month(hos_id, keshi_id) {
        var fuke = $("#fuke").val();
        var hos_id = hos_id;
        var keshi_id = keshi_id;
        $.ajax({

            type: 'post',

            url: '?c=index&m=get_month_ajax',

            data: 'hos_id=' + hos_id + '&keshi_id=' + keshi_id + '&fuke=' + fuke + '&date=' + $("#inputDate").val(),

            success: function (data) {

                $("#month_data").html(data);
                $("#get_month_data_table tr").find("td").each(function () {
                    if ($(this).html() == 0 && $(this).html() != '') {
                        $(this).html("<span style='color:red'>0</span>");
                    }
                });

            },

            complete: function (XHR, TS) {

                XHR = null;

            }

        });


    }


    function ajax_get_advisory_rankings(hos_id, keshi_id) {

        var fuke = $("#fuke").val();

        var hos_id = hos_id;
        var keshi_id = keshi_id;
        if (hos_id == '1_1' || hos_id == '1_7' || hos_id == '45_114_116' || hos_id == '45_115') {
            fuke = '1';
        }
        $.ajax({

            type: 'post',

            url: '?c=index&m=get_advisory_rankings_ajax',

            data: 'hos_id=' + hos_id + '&keshi_id=' + keshi_id + '&fuke=' + fuke,

            success: function (data) {
                $("#month_advisory_rankings").html(data);

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


</script>

</body>
</html>