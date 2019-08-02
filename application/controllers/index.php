<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");

// 首页入口
class Index extends CI_Controller
{
    var $model;//定义模型文件

    public function __construct()
    {
        parent::__construct();
        //如果是手机访问 自动跳往手机网站
        if ($this->is_mobile_request()) {
            header('Location: http://mobile.renaidata.com/mobile/');
            exit;
        }

        $this->load->model('Index_model');//加载控制器Index_Model
        $this->load->model('Order_model');
        $this->lang->load('index');
        $this->lang->load('common');//分别导入语言类index和common

        $this->model = $this->Index_model;//赋值
    }

    /***
     * 判断手机访问
     */
    function is_mobile_request()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser = 0;
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if ($mobile_browser > 0)
            return true;
        else
            return false;

    }

    //获取数据库的信息从信息表中

    public function test()
    {
        $data = array();
        $data = $this->common->config('test');
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('test', $data);
    }


    //后台系统首页
    public function index()
    {

        //echo  strtotime(date("Y-m-d", time() + (86400*3)) . " 00:00:01");exit;

        $data = array();
        $data = $this->common->config('index');//这里调用的是libraries下的common.php中的config()方法
        $start_time = strtotime(date("Y-m-d", time()) . " 23:59:59");//对时间的格式化操作\
        $end_time = strtotime(date("Y-m-d", time() - (86400 * 30)) . " 00:00:00");

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';//日期
        //时间搜索权限
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];
            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
            $end = str_replace(array("年", "月", "日"), "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

            $start_time = strtotime($end . ' 23:59:59');
            $end_time = strtotime($start . ' 00:00:01');
            //echo $start . ' 00:00:00';
            //echo $end . ' 23:59:59';
            //exit;
        }

        $hospital = $this->common->get_hosname();
        $fuke = isset($_REQUEST['fuke']) ? trim($_REQUEST['fuke']) : "1";
        $data['fuke'] = $fuke;
        //前台 判断搜索条件跳转到预约列表的时候， 如果是 妇科加不孕  则已妇科为准
        if ($data['fuke'] != '1') {
            $special_keshi_id_array = explode(',', $data['fuke']);
            /***
             * 2017 06 28 添加功能  开始
             */
            //判斷是否是人流
            if ($special_keshi_id_array[0] == 'js') {
                $data['input_keshi'] = 1;
                $data['keshi_id'] = 1;
            } else {
                $data['input_keshi'] = $special_keshi_id_array[0];
                $data['keshi_id'] = $special_keshi_id_array[0];
            }
            /***
             * 2017 06 28 添加功能  结束
             */
        }

        //$link=$this->load->database('bbs',true,false);
        //print_r($link);
        /*在进入首页面的过程中先对$_post的值先进行一个判断

		* 如果有值传递进来分别赋值给 $hos_id,$data以及$order
		* $order获取的值经过index_Model.php文件中order_count（）方法的调用。
		*                 */
        $data['hos_id'] = 0;
        $data['keshi_id'] = 0;
        if (!empty($_POST)) {
            $hos_id = $_POST['hos_id'];
            if ($hos_id == '1_1') {
                $keshi_id = '';
                $data['hos_id'] = '1';
                $data['keshi_id'] = '1';
            } else if ($hos_id == '1_7') {
                $keshi_id = '';
                $data['hos_id'] = '1';
                $data['keshi_id'] = '7';
            } else if ($hos_id == '45_114_116') {
                $keshi_id = '';
                $data['hos_id'] = '45';
                $data['keshi_id'] = '114';
            } else if ($hos_id == '45_115') {
                $keshi_id = '';
                $data['hos_id'] = '45';
                $data['keshi_id'] = '115';
            } else if ($hos_id == '1_32') {
                $keshi_id = '';
                $data['hos_id'] = '1';
                $data['keshi_id'] = '32';
            } else if ($hos_id == '54_153') {
                $keshi_id = '';
                $data['hos_id'] = '54';
                $data['keshi_id'] = '153';
            } else {
                $keshi_id = $_POST['keshi_id'];
                $data['hos_id'] = $hos_id;
                $data['keshi_id'] = $keshi_id;
            }
            $data['input'] = $hos_id;
            if ($data['input'] == '') {
                $data['input'] = 0;
            }
            $data['input_keshi'] = $keshi_id;
            if (!empty($date)) {
                $order = $this->model->order_count_by_set_time($start_time, $end_time, $hos_id, $keshi_id, $data['admin']['rank_id'], $data['fuke']);
            } else {
                $order = $this->model->order_count($start_time, $end_time, $hos_id, $keshi_id, $data['admin']['rank_id'], $data['fuke']);
            }
        } else {
            if (!empty($date)) {
                $order = $this->model->order_count_by_set_time($start_time, $end_time, '', '', $data['admin']['rank_id'], '');
            } else {
                $order = $this->model->order_count($start_time, $end_time, '', '', $data['admin']['rank_id'], '');
            }
            $data['input'] = 0;
        }
        //var_dump($order);exit;
        if (!empty($date)) {
            $html = '';
            $html .= '<div class="metro-nav-block nav-block-red" style="background-color: #da542e;">
		<a data-original-title="" href="?c=order&m=order_list&t=1&hos_id=' . $data['hos_id'] . '&keshi_id=' . $data['keshi_id'] . '&date=';
            $html .= urlencode(date("Y年m月d日", $end_time) . " - " . date("Y年m月d日", $start_time));
            $html .= '#本月预约"><i class="icon-hospital"></i><div class="info">';
            $html .= isset($order['yue']['add']) ? $order['yue']['add'] : 0;
            $html .= '</div><div class="status">搜索时间范围之内的预约</div></a></div>
		                    <div class="metro-nav-block nav-block-orange" style="background-color: #da542e;">
		                        <a data-original-title="" href="?c=order&m=order_list&t=3&hos_id=' . $data['hos_id'] . '&keshi_id=' . $data['keshi_id'] . '&date=';
            $html .= urlencode(date("Y年m月d日", $end_time) . " - " . date("Y年m月d日", $start_time));
            $html .= '#本月来院"><i class="icon-user-md"></i><div class="info">';
            $aa = isset($order['yue']['come']) ? $order['yue']['come'] : 0;
            $bb = isset($order['yue']['gonghai_come']) ? $order['yue']['gonghai_come'] : 0;
            // 仁爱的计数不算公海的数量  ，其他医院的计算 预约数量+公海数量
            if ($data['input'] == '1_1' || $data['input'] == '1') {
                $cc = $aa;
            } else {
                $cc = $aa + $bb;
            }
            $html .= $cc;
            $html .= '</div><div class="status">搜索时间范围之内的来院</div></a></div>';
            $data['search_time_get_html'] = $html;
        } else {
            $data['yue'] = $order['yue'];
        }

        $data['order'] = $order['order'];

        /***         -------------------------------卓总 新增代码-------------------------------------        ***/

        $fuke = isset($_REQUEST['fuke']) ? trim($_REQUEST['fuke']) : "";
        $hospital = $this->common->get_hosname();
        /*admin_id in(452,778)*/
        $where = " where 1";
        if (!empty($_REQUEST['hos_id'])) {
            if ($_REQUEST['hos_id'] == '1_1') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id in(1)";
                }
            } else if ($_REQUEST['hos_id'] == '1_7') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id = 7 ";
                }
            } else if ($hos_id == '45_114_116') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                $where .= " AND hos_id=" . $hos_id_array[0];
                $where .= " AND keshi_id =" . $hos_id_array[1];
            } else if ($hos_id == '45_115') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                $where .= " AND hos_id=" . $hos_id_array[0];
                $where .= " AND keshi_id =" . $hos_id_array[1];
            } else if ($hos_id == '1_32') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                $where .= " AND hos_id=" . $hos_id_array[0];
                $where .= " AND keshi_id =" . $hos_id_array[1];
            } else if ($hos_id == '54_153') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                $where .= " AND hos_id=" . $hos_id_array[0];
                $where .= " AND keshi_id =" . $hos_id_array[1];
            } else {
                $where .= " AND hos_id=" . $_REQUEST['hos_id'];

                if (!empty($_REQUEST['keshi_id'])) {
                    $where .= " AND keshi_id=" . $_REQUEST['keshi_id'];
                } else if (!empty($_COOKIE['l_keshi_id'])) {
                    $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                }
            }
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }
        }
        if ($fuke != "1" && $_REQUEST['hos_id'] == 1) {
            $special_keshi_id_array = explode(',', $fuke);
            if (count($special_keshi_id_array) > 1) {
                /***                 * 2017 06 28 添加功能  开始                */
                /*判斷是否是人流*/
                if ($special_keshi_id_array[0] == 'js') {
                    $where = " where 1 AND hos_id = 1";
                } else {
                    $where = " where 1 AND hos_id = 1 AND keshi_id in(" . $fuke . ")";
                }
                /***                 * 2017 06 28 添加功能  结束                */
            }
        }

        /*明日预到*/
        $data['tomo_order_count'] = 0;
        $start_time = strtotime(date("Y-m-d", time() + 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() + 24 * 60 * 60) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(order_time) as count FROM " . $this->common->table('order') . $where . "  and   order_time between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(order_time) as count FROM " . $this->common->table('order') . $where . "  and  order_time between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(order_time) as count FROM " . $this->common->table('order') . $where . "  and  order_time between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['tomo_order_count'] = $row['count'];
            }
        }

        /*今日留联*/
        $data['today_ll_count'] = 0;
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and   order_addtime between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['today_ll_count'] = $row['count'];
            }
        }


        /*昨日留联*/
        $data['yesterday_ll_count'] = 0;
        $start_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and   order_addtime between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['yesterday_ll_count'] = $row['count'];
            }
        }

        /*今日复诊*/
        // $data['today_fz_count'] =  0;
        // $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        // $end_time = strtotime(date("Y-m-d", time()). " 23:59:59");
        // $sql  = '';
        // if(!empty($data['hos_id'])){
        // 	$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // 	if(!empty($data['keshi_id'])){
        // 		$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // 	}
        // }else{
        // 	$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // }
        // if(!empty($sql )){
        // 	$row = $this->common->getRow($sql);
        // 	if(count($row) > 0){
        // 		$data['today_fz_count'] =$row['count'];
        // 	}
        // }
        $data['today_fz_count'] = 0;
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0  and  come_time between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0  and  come_time between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0  and  come_time between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['today_fz_count'] = $row['count'];
            }
        }

        /*本月留联*/
        $time = date("Y-m-01", time());
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
        $data['month_ll_count'] = 0;
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('order_liulian') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['month_ll_count'] = $row['count'];
            }
        }

        /*本月公海*/
        $data['month_gh_count'] = 0;
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('gonghai_order') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('gonghai_order') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(order_addtime) as count FROM " . $this->common->table('gonghai_order') . $where . "  and  order_addtime between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['month_gh_count'] = $row['count'];
            }
        }

        /*本月复诊*/
        // $data['month_fz_count'] =  0;
        // $start_time = strtotime($time." 00:00:00");
        // $end_time = strtotime(date("Y-m-d",strtotime("$time +1 month -1 day"))." 23:59:59");
        // $sql  = '';		if(!empty($data['hos_id'])){
        // 	$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // 	if(!empty($data['keshi_id'])){
        // 		$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // 	}
        // }else{
        // 	$sql = "SELECT count(fztime) as count FROM " . $this->common->table('order_fz') .$where. "  and  fztime between  ".$start_time." and ".$end_time;
        // }
        // if(!empty($sql )){
        // 	$row = $this->common->getRow($sql);
        // 	if(count($row) > 0){
        // 		$data['month_fz_count'] =$row['count'];
        // 	}
        // }
        $data['month_fz_count'] = 0;
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
        $sql = '';
        if (!empty($data['hos_id'])) {
            $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0  and  come_time between  " . $start_time . " and " . $end_time;
            if (!empty($data['keshi_id'])) {
                $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0  and  come_time between  " . $start_time . " and " . $end_time;
            }
        } else {
            $sql = "SELECT count(*) as count FROM " . $this->common->table('order') . $where . " and is_first = 0 and  come_time between  " . $start_time . " and " . $end_time;
        }
        if (!empty($sql)) {
            $row = $this->common->getRow($sql);
            if (count($row) > 0) {
                $data['month_fz_count'] = $row['count'];
            }
        }

        /***         -------------------------------卓总 新增代码-------------------------------------        ***/


        // 获取医院信息

        /** 2016 11 28   判断是否具有 预约客服的7天查询时间限制    **/
        $one_day_data = '0';

        //判断权限
        if (strcmp($_COOKIE['l_admin_action'], 'all') != 0) {
            $l_admin_action = explode(',', $_COOKIE['l_admin_action']);
            if (in_array(155, $l_admin_action)) {
                $one_day_data = '1';
            }
        } else {
            $one_day_data = '1';
        }
        $data['one_day_data'] = $one_day_data;

        $hospital = $this->common->get_hosname();
        $data['hosnum'] = count($hospital);
        $data['hospital'] = $hospital;

        /**
         * 厦门鹭港医院展示判断
         **/
        $xiamen_check = array();
        foreach ($hospital as $hospital_temp) {
            if ($hospital_temp['hos_id'] == 45 || $hospital_temp['hos_id'] == 46) {
                $xiamen_check[] = $hospital_temp['hos_id'];
            }
        }
        if (count($xiamen_check) > 0) {
            $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
            $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");
            if ($data['input'] == 45 || empty($data['input'])) {//本部
                $xm_data = array();
                //计算厦门鹭港妇产科医院的今日添加数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 45 and keshi_id in(114,115) and order_addtime between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_yy_data'] = $this->common->getALL($xm_day_yy);
                //计算厦门鹭港妇产科医院的今日预到数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 45 and keshi_id in(114,115) and order_time between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_yd_data'] = $this->common->getALL($xm_day_yy);
                //计算厦门鹭港妇产科医院的今日到诊数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 45 and keshi_id in(114,115) and is_come = 1 and  come_time between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_dz_data'] = $this->common->getALL($xm_day_yy);
                $data['xiamen_data'] = $xm_data;
            }
            if ($data['input'] == 46 || empty($data['input'])) {//外部合作
                $xm_data = array();
                //计算厦门鹭港妇产科医院_外部合作的今日添加数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 46 and keshi_id in(117,118)  and order_addtime between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_hz_yy_data'] = $this->common->getALL($xm_day_yy);
                //var_dump( $xm_data['xm_keshi_hz_yy_data']);exit;
                //计算厦门鹭港妇产科医院_外部合作的今日预到数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 46 and keshi_id in(117,118)  and order_time between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_hz_yd_data'] = $this->common->getALL($xm_day_yy);
                //计算厦门鹭港妇产科医院_外部合作的今日到诊数据  妇科+产科
                $xm_day_yy = "select keshi_id from " . $this->common->table('order') . "  where hos_id = 46 and keshi_id in(117,118)  and is_come = 1 and  come_time between " . $start_time . " and " . $end_time;
                $xm_data['xm_keshi_hz_dz_data'] = $this->common->getALL($xm_day_yy);
                $data['xiamen_hz_data'] = $xm_data;
            }
        }


        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $this->load->view('index', $data);

    }


    //后台系统首页
    public function get_index_by_time()
    {
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';//日期
        //时间搜索权限
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];
            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
            $end = str_replace(array("年", "月", "日"), "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

        }
        if (isset($start) && !empty($start)) {
            $start_time = strtotime($start . ' 00:00:00');
            $end_time = strtotime($end . ' 23:59:59');
        } else {
            echo '';
            exit;
        }

        $data = array();
        $data = $this->common->config('index');//这里调用的是libraries下的common.php中的config()方法
        $hospital = $this->common->get_hosname();
        $fuke = isset($_REQUEST['fuke']) ? trim($_REQUEST['fuke']) : "1";
        $hos_id = $_REQUEST['hos_id'];
        if ($hos_id == '1_1') {
            $keshi_id = '';
        } else if ($hos_id == '1_7') {
            $keshi_id = '';
        } else {
            $keshi_id = $_REQUEST['keshi_id'];
        }
        $input = $hos_id;
        if ($input == '') {
            $input = 0;
        }

        $order = $this->model->order_count($start_time, $end_time, $hos_id, $keshi_id, $data['admin']['rank_id'], $fuke);
        $data['order'] = $order['order'];
        $yue = $order['yue'];


        echo '<div class="metro-nav-block nav-block-red" style="background-color: #da542e;">
		<a data-original-title="" href="?c=order&m=order_list&t=1&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&date=';


        $time1 = date('Y-m-01', time());
        $now_time = strtotime("$time1 +1 month -1 day");
        echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time));
        echo '#本月预约"><i class="icon-hospital"></i><div class="info">';
        echo isset($order['yue']['add']) ? $order['yue']['add'] : 0;
        echo '</div><div class="status">本月预约</div></a></div>
		                    <div class="metro-nav-block nav-block-orange" style="background-color: #da542e;">
		                        <a data-original-title="" href="?c=order&m=order_list&t=3&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&date=';
        $time2 = date('Y-m-01', time());
        $now_time2 = strtotime("$time2 +1 month -1 day");
        echo urlencode(date("Y年m月01日", time()) . " - " . date("Y年m月d日", $now_time2));
        echo '#本月来院"><i class="icon-user-md"></i><div class="info">';
        $aa = isset($yue['come']) ? $yue['come'] : 0;
        $bb = isset($yue['gonghai_come']) ? $yue['gonghai_come'] : 0;
        // 仁爱的计数不算公海的数量  ，其他医院的计算 预约数量+公海数量
        if ($input == '1_1' || $input == '1') {
            $cc = $aa;
        } else {
            $cc = $aa + $bb;
        }
        echo $cc;
        echo '</div><div class="status">本月来院</div></a></div>';

        exit;
    }


    /**
     * 日期合法校验
     **/
    public function dateCheck($data)
    {
        $data = str_replace(array("年", "月"), "-", $data);
        $data = str_replace(array("日"), "", $data);
        $is_date = strtotime($data) ? strtotime($data) : false;
        if ($is_date === false) {
            return false;
        } else {
            return true;
        }
    }


    public function get_month_ajax()
    {
        //判断权限
        if (strcmp($_COOKIE['l_admin_action'], 'all') != 0) {
            $l_admin_action = explode(',', $_COOKIE['l_admin_action']);
            if (!in_array(155, $l_admin_action)) {
                echo '';
                exit;
            }
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';//日期
        //时间搜索权限
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];


            if (!$this->dateCheck($start) || !$this->dateCheck($end)) {
                $start = date("Y年m月d日");
                $end = $start;
            }


            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
            $end = str_replace(array("年", "月", "日"), "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }


        /**** ****/
        $data = $this->common->config('index');//这里调用的是libraries下的common.php中的config()方法

        // 获取医院信息
        //获取排行榜数据开始
//            $keshi_id=isset($_REQUEST['keshi_id'])?intval($_REQUEST['keshi_id']):0;
        $today_time_start = strtotime(date("Y-m-d ", time()) . "00:00:00");
        $today_time_end = strtotime(date("Y-m-d ", time()) . "23:59:59");

        /**
         * $month_time_start=mktime(0,0,0,date('m'),1,date('Y'));
         * $month_time_end=mktime(23,59,59,date('m'),date('t'),date('Y'));
         * $last_month_start=mktime(0,0,0,date('m')-1,1,date('Y'));
         * $last_month_end=mktime(23,59,59,date('m')-1,date('t'),date('Y'));
         **/

        $time = date("Y-m-01", time());
        $month_time_start = strtotime($time . " 00:00:00");
        $month_time_end = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
        $time = date('Y-m-01', strtotime(date('Y', time()) . '-' . (date('m', time()) - 1) . '-01'));
        $last_month_start = strtotime($time . " 00:00:00");
        $last_month_end = strtotime(date('Y-m-d', strtotime("$time +1 month -1 day")) . " 23:59:59");
        //echo date('Y-m-d h:i:s',$month_time_start).'<br/>'.date('Y-m-d h:i:s',$month_time_end)."<br/>";
        //echo date('Y-m-d h:i:s',$last_month_start).'<br/>'.date('Y-m-d h:i:s',$last_month_end);exit;

        $fuke = isset($_REQUEST['fuke']) ? trim($_REQUEST['fuke']) : "";
        $hospital = $this->common->get_hosname();
        //admin_id in(452,778)
        $where = " where 1";
        if (!empty($_REQUEST['hos_id'])) {
            if ($_REQUEST['hos_id'] == '1_1') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id in(1)";
                }
            } else if ($_REQUEST['hos_id'] == '1_7') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id = 7 ";
                }
            } else {
                $where .= " AND hos_id=" . $_REQUEST['hos_id'];

                if (!empty($_REQUEST['keshi_id'])) {
                    $where .= " AND keshi_id=" . $_REQUEST['keshi_id'];
                } else if (!empty($_COOKIE['l_keshi_id'])) {
                    $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                }

            }
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }
        }
        if ($fuke != "1" && $_REQUEST['hos_id'] == 1) {
            $special_keshi_id_array = explode(',', $fuke);
            if (count($special_keshi_id_array) > 1) {
                /***
                 * 2017 06 28 添加功能  开始
                 */
                //判斷是否是人流
                if ($special_keshi_id_array[0] == 'js') {
                    $where = " where 1 AND hos_id = 1 AND jb_parent_id IN (" . $special_keshi_id_array[1] . ")";
                } else {
                    $where = " where 1 AND hos_id = 1 AND keshi_id in(" . $fuke . ")";
                }
                /***
                 * 2017 06 28 添加功能  结束
                 */
            }

        }


        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 0;
        $hos_id = isset($_REQUEST['hos_id']) ? $_REQUEST['hos_id'] : 0;
        if (!empty($type)) {
            $keshi = array();
            if ($type == 'fuke') {
                $keshi = $this->config->item('all_fuke');
                $data['type'] = $type;
            } elseif ($type == 'byby') {
                $keshi = $this->config->item('all_byby');
                $data['type'] = $type;
            } elseif ($type == 'gc') {
                $keshi = $this->config->item('all_gc');
                $data['type'] = $type;
            } else {
                $keshi = $this->config->item('all_nanke');
                $data['type'] = 'nanke';
            }

            $ks = array();
            foreach ($keshi as $value) {
                $ks[] = explode('_', $value['keshi_id']);
            }

            $ks_ids_str = '';
            foreach ($ks as $value) {
                foreach ($value as $val) {
                    $ks_ids_str .= $val . ',';
                }
            }

            if (!empty($hos_id)) {
                $where = " where hos_id IN (" . str_replace('_', ',', $hos_id) . ") and keshi_id IN (" . substr($ks_ids_str, 0, -1) . ")";
            } else {
                $where = " where keshi_id IN (" . substr($ks_ids_str, 0, -1) . ")";
            }

        }

        $des = array();

//               //获取预约客服全月到诊数据

//          $sql_3="select admin_name,hos_id,COUNT(*) AS month_come from ".$this->common->table('order').$where." and is_come=1 and DATE_FORMAT( from_unixtime(come_time) , '%Y%m' ) = DATE_FORMAT( now() , '%Y%m' ) group by admin_name order by month_come desc";

        if (isset($start) && !empty($start)) {
            $month_time_start = strtotime($start . ' 00:00:00');
            $month_time_end = strtotime($end . ' 23:59:59');
        }
        $sql_3 = "select admin_name,admin_id,hos_id,COUNT(*) AS month_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $month_time_start . " and " . $month_time_end . "  group by admin_id order by month_come desc";
        $info_3 = $this->common->getALL($sql_3);
        $list_3 = array();
        foreach ($info_3 as $val) {
            $list_3[$val['admin_id']][] = $val;
        }
        foreach ($list_3 as $key => $val) {
            $des[$key]['month_come'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['month_come'] += $v['month_come'];
            }
        }

        /***
         * 2017 06 28 添加功能  开始
         */
        //获取预约客服全月预到数据
        $sql_15 = "select admin_name,admin_id,hos_id,COUNT(*) AS month_action from " . $this->common->table('order') . $where . "   and order_time between " . $month_time_start . " and " . $month_time_end . "  group by admin_id order by month_action desc";
        $info_15 = $this->common->getALL($sql_15);
        $list_15 = array();
        foreach ($info_15 as $val) {
            $list_15[$val['admin_id']][] = $val;
        }
        foreach ($list_15 as $key => $val) {
            $des[$key]['month_action'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['month_action'] += $v['month_action'];
            }
        }
        /***
         * 2017 06 28 添加功能  结束
         *
         */


        //获取预约客服上月到诊数据,进行前三名的排行
        if (isset($start) && !empty($start)) {
            $last_month_start = strtotime($start . ' 00:00:00');
            $last_month_end = strtotime($end . ' 23:59:59');
        }
        $sql_5 = "select admin_name,admin_id,COUNT(*) AS last_month_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $last_month_start . " and  " . $last_month_end . " group by admin_id order by last_month_come desc limit 0,3";
        $info_5 = $this->common->getALL($sql_5);
        $list_5 = array();
        $res_last_month = array();
        foreach ($info_5 as $val) {
            $list_5[$val['admin_id']][] = $val;
        }
        foreach ($list_5 as $key => $val) {
            $res_last_month[$key]['last_month_come'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $res_last_month[$key]['last_month_come'] += $v['last_month_come'];
                $res_last_month[$key]['admin_name'] = $v['admin_name'];
            }
        }

        //获取预约客服上月登记数据,进行前三名的排行
        if (isset($start) && !empty($start)) {
            $last_month_start = strtotime($start . ' 00:00:00');
            $last_month_end = strtotime($end . ' 23:59:59');
        }
        $sql_6 = "select admin_name,admin_id,COUNT(*) AS last_month_add from " . $this->common->table('order') . $where . " and order_addtime between " . $last_month_start . " and  " . $last_month_end . " group by admin_id";
        $info_6 = $this->common->getALL($sql_6);
        $list_6 = array();
        foreach ($info_6 as $val) {
            $list_6[$val['admin_id']][] = $val;
        }
        foreach ($list_6 as $key => $val) {
            $res_last_month[$key]['last_month_add'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $res_last_month[$key]['last_month_add'] += $v['last_month_add'];
                $res_last_month[$key]['admin_name'] = $v['admin_name'];
            }
        }
        $data['last_month'] = $res_last_month;

        //获取当月个人预约数据
        if (isset($start) && !empty($start)) {
            $month_time_start = strtotime($start . ' 00:00:00');
            $month_time_end = strtotime($end . ' 23:59:59');
        }
        $sql = "select admin_name,admin_id,COUNT(*) AS count  from " . $this->common->table('order') . $where . " AND order_addtime between  $month_time_start and  $month_time_end  group by admin_id";
        $info = $this->common->getAll($sql);

        $list = array();
        foreach ($info as $val) {
            $list[$val['admin_id']][] = $val;
        }
        foreach ($list as $key => $val) {
            $des[$key]['count'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['count'] += $v['count'];
            }
        }
        if (empty($start)) {
            //获取当天个人预到数据
            $sql_1 = "select admin_name,admin_id,COUNT(*) AS count_t_order  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_start . " and  " . $today_time_end . "  group by admin_id";
            $info_1 = $this->common->getAll($sql_1);
            $list_1 = array();
            foreach ($info_1 as $val) {
                $list_1[$val['admin_id']][] = $val;
            }
            foreach ($list_1 as $key => $val) {
                $des[$key]['count_t_order'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_order'] += $v['count_t_order'];
                }
            }


            for ($i = 1; $i < 32; $i++) {
                $today_time_add_one_start = strtotime(date("Y-m-d", time() + (86400 * $i)) . " 00:00:00");//对时间的格式化操作\
                $today_time_add_one_end = strtotime(date("Y-m-d", time() + (86400 * $i)) . " 23:59:59");
                $sql_add_one = "select order_id,admin_name,admin_id,COUNT(*) AS count_t_add_order_" . $i . "  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end . "  group by admin_id";
                $info_add_one = $this->common->getAll($sql_add_one);
                /*查询时间数据*/
                $sql_add_one = "select order_id,order_addtime,admin_id from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end;
                $info_add_one_order_add_time = $this->common->getAll($sql_add_one);

                $list_add_one = array();
                foreach ($info_add_one as $val) {
                    $list_add_one[$val['admin_id']][] = $val;
                }
                $today_time_start = strtotime(date("Y-m-d", time()) . " 00:00:00");//对时间的格式化操作
                $today_time_end = strtotime(date("Y-m-d", time()) . " 23:59:59");
                foreach ($list_add_one as $key => $val) {
                    $des[$key]['count_t_add_order_' . $i] = 0;
                    $des[$key]['count_t_add_order_today_' . $i] = 0;
                    foreach ($val as $k => $v) {
                        $des[$key]['admin_id'] = $v['admin_id'];
                        foreach ($info_add_one_order_add_time as $info_add_one_order_add_time_val) {
                            if ($des[$key]['admin_id'] == $info_add_one_order_add_time_val['admin_id'] && $info_add_one_order_add_time_val['order_addtime'] >= $today_time_start && $info_add_one_order_add_time_val['order_addtime'] <= $today_time_end) {
                                $des[$key]['count_t_add_order_today_' . $i] += 1;
                            }
                        }
                        $des[$key]['count_t_add_order_' . $i] += $v['count_t_add_order_' . $i];
                    }
                }
            }

            /*获取下个月 (以31天为周期计算)*/
            $today_time_add_one_start = strtotime(date("Y-m-d", time() + (86400 * 32)) . " 00:00:00");
            $today_time_add_one_end = strtotime(date("Y-m-d", time() + (86400 * 63)) . " 23:59:59");
            $sql_add_one = "select admin_name,admin_id,COUNT(*) AS count_t_add_next_order  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end . "  group by admin_id";
            $info_add_one = $this->common->getAll($sql_add_one);
            /*查询时间数据*/
            $sql_add_one = "select order_id,order_addtime,admin_id from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end;
            $info_add_one_order_add_time = $this->common->getAll($sql_add_one);
            $list_add_one = array();
            foreach ($info_add_one as $val) {
                $list_add_one[$val['admin_id']][] = $val;
            }
            foreach ($list_add_one as $key => $val) {
                $des[$key]['count_t_add_next_order'] = 0;
                $des[$key]['count_t_add_next_order_today'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    foreach ($info_add_one_order_add_time as $info_add_one_order_add_time_val) {
                        if ($des[$key]['admin_id'] == $info_add_one_order_add_time_val['admin_id'] && $info_add_one_order_add_time_val['order_addtime'] >= $today_time_start && $info_add_one_order_add_time_val['order_addtime'] <= $today_time_end) {
                            $des[$key]['count_t_add_next_order_today'] += 1;
                        }
                    }
                    $des[$key]['count_t_add_next_order'] += $v['count_t_add_next_order'];
                }
            }


            /*获取当天个人预约数据*/
            $sql_2 = "select admin_name,admin_id,COUNT(*) AS count_t_add  from " . $this->common->table('order') . $where . " AND order_addtime between " . $today_time_start . " and  " . $today_time_end . "  group by admin_id";
            $info_2 = $this->common->getAll($sql_2);
            $list_2 = array();
            foreach ($info_2 as $val) {
                $list_2[$val['admin_id']][] = $val;
            }
            foreach ($list_2 as $key => $val) {
                $des[$key]['count_t_add'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_add'] += $v['count_t_add'];
                }
            }

            /*获取预约客服今日到诊数据*/
            $sql_4 = "select admin_name,admin_id,COUNT(*) AS today_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $today_time_start . " and " . $today_time_end . " group by admin_id ";
            $info_4 = $this->common->getALL($sql_4);
            $list_4 = array();
            foreach ($info_4 as $val) {
                $list_4[$val['admin_id']][] = $val;
            }
            foreach ($list_4 as $key => $val) {
                $des[$key]['today_come'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['today_come'] += $v['today_come'];
                }
            }

            //昨天时间
            $tommro_time_add_one_start = strtotime(date("Y-m-d", time() - (86400 * 1)) . " 00:00:00");//对时间的格式化操作\
            $tommro_time_add_one_end = strtotime(date("Y-m-d", time() - (86400 * 1)) . " 23:59:59");
            //获取昨天个人预约数据
            $sql_12 = "select admin_name,admin_id,COUNT(*) AS count_t_tommro_add  from " . $this->common->table('order') . $where . " AND order_addtime between " . $tommro_time_add_one_start . " and  " . $tommro_time_add_one_end . "  group by admin_id";
            $info_12 = $this->common->getAll($sql_12);
            $list_12 = array();
            foreach ($info_12 as $val) {
                $list_12[$val['admin_id']][] = $val;
            }
            foreach ($list_12 as $key => $val) {
                $des[$key]['count_t_tommro_add'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_tommro_add'] += $v['count_t_tommro_add'];
                }
            }

            //获取昨天到诊数据
            $sql_14 = "select admin_name,admin_id,COUNT(*) AS count_t_tommro_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $tommro_time_add_one_start . " and " . $tommro_time_add_one_end . " group by admin_id ";
            $info_14 = $this->common->getALL($sql_14);
            $list_14 = array();
            foreach ($info_14 as $val) {
                $list_14[$val['admin_id']][] = $val;
            }
            foreach ($list_14 as $key => $val) {
                $des[$key]['count_t_tommro_come'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_tommro_come'] += $v['count_t_tommro_come'];
                }
            }
        }


        //对相同到诊数情况下的预约客服重新排序，代码开始
        $aa = array();
        $bb = array();

        foreach ($des as $v) {
            @$aa[] = $v['month_come'];

        }
        foreach ($des as $k => $v) {
            @$bb[] = $v['count'];

        }

        array_multisort($aa, SORT_DESC, $bb, SORT_ASC, $des);

        $cc = array();
        $dd = array();

        foreach ($res_last_month as $v) {
            @$cc[] = $v['last_month_come'];

        }
        foreach ($res_last_month as $k => $v) {
            @$dd[] = $v['last_month_add'];

        }


        array_multisort($cc, SORT_DESC, $dd, SORT_ASC, $res_last_month);

        //对相同到诊数情况下的预约客服重新排序，代码结束
        //获取排行榜数据结束


        $group_id_str = '0';
        foreach ($des as $v) {
            if ($group_id_str == '0') {
                $group_id_str = $v['admin_id'];
            } else {
                $group_id_str .= ',' . $v['admin_id'];
            }
        }

        //查询一级组名称
        $sql_group = "select id,name from " . $this->common->table('user_groups') . " where parent_id = 0";
        $groups_data = $this->common->getALL($sql_group);

        //查询全部组合名称
        $sql_admin = "select g.name,g.parent_id,a.admin_group,a.admin_id from " . $this->common->table('admin') . " as a," . $this->common->table('user_groups') . " as g where a.admin_group = g.id and  a.admin_id in(" . $group_id_str . ")";

        $admin_in_data = $this->common->getALL($sql_admin);
        foreach ($admin_in_data as $admin_in_data_key => $admin_in_data_val) {
            $check_group = 0;
            foreach ($groups_data as $groups_data_val) {
                if ($admin_in_data_val['parent_id'] == $groups_data_val['id']) {
                    $check_group = 0;
                    $admin_in_data[$admin_in_data_key]['name'] = $groups_data_val['name'] . '<br/>' . $admin_in_data[$admin_in_data_key]['name'];
                    break;
                }
            }
            if ($check_group != 0) {
                break;
            }
        }


        //定义星期 数据
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");

        $style_flow = 'overflow-x: scroll;';
        $style_wide = 'width:3000px;';
        $style_width = 'width:6000px;';

        if (!empty($start)) {
            $style_flow = '';
            $style_wide = 'width:100%;';
            $style_width = 'width:100%;';
        }

        echo "<div class=''>
                     <div class='widget purple' style='border: 0px solid #e7e7e7;background-color: #dcdcdc;margin-left: 0px;position:relative;'>
             			<div class='widget-body' style='margin-left: 0px; '><div id='' style=' width:100%; display:block;float:left;" . $style_flow . "'>
                             <div style='" . $style_wide . "background-color: #dcdcdc;min-height:200px;'>
                                  <label style='width:100%;color:#00a186;line-height: 30px;font-size:16px;background-color: #d1d1d1;'>&nbsp;&nbsp;&nbsp;&nbsp;<img src='static/img/000.gif'/>&nbsp;&nbsp;&nbsp;&nbsp;";

        if (empty($start)) {
            echo "本月预约客服";
        } else {
            echo "搜索时间范围之内的预约客服";
        }

        echo "<font style='font-size:12px;float:right;color:#808080;'>注：相同到诊数以到诊率为标准&nbsp;&nbsp;</font></label>
                                     <table id='get_month_data_table' class='table-striped' style='" . $style_width . "text-align:center;'>
                                         <tr ><td><span style='color:#00a187;font-size:12px'>到诊</span></td>
										 	  <td><span style='color:#00a187;font-size:12px'>所属组</span></td>
										      <td><span style='color:#00a187;font-size:12px'>预约客服</span></td>
											  <td><span style='color:#00a187;font-size:12px'>全月预约</span></td>
          		  							  <td><span style='color:#00a187;font-size:12px'>全月预到</span></td>
											  <td><span style='color:#00a187;font-size:12px'>全月到诊</span></td>
		 							  <td><span style='color:#00a187;font-size:12px'>到诊率</span></td>";
        if (empty($start)) {
            echo
            "<td><span style='color:#00a187;font-size:12px'>今天预约</span></td>
				<td><span style='color:#00a187;font-size:12px'>今天预到</span></td>
				<td><span style='color:#00a187;font-size:12px'>今天到诊</span></td>
				<td><span style='color:#00a187;font-size:12px'>昨日预约</span></td>
				<td><span style='color:#00a187;font-size:12px'>昨日到诊</span></td>
				";
            for ($i = 1; $i < 32; $i++) {
                echo "<td><table><tr><td colspan='3' style='color:#00a187;'>" . date('d', time() + 3600 * 24 * $i) . "号(" . $weekarray[date("w", time() + 3600 * 24 * $i)] . ")</td></tr><tr><td><span style='color:#00a187;font-size:12px'>今日约</span></td><td><span style='color:#00a187;font-size:12px'>预到</span></td></tr></table></td>";
            }
            echo "
				<td><table><tr><td colspan='3' style='color:#00a187;'>下月总计)</td></tr><tr><td><span style='color:#00a187;font-size:12px'>今日约</span></td><td><span style='color:#00a187;font-size:12px'>预到</span></td></tr></table></td>";
        }

        echo
        "</tr>";
        //if(false){//属于仁爱 医院 妇科
        if (strcmp($_REQUEST['hos_id'], 1) == 0 && strcmp($_REQUEST['keshi_id'], 1) == 0) {//属于仁爱 医院 妇科
            $i = 1;
            foreach ($des as $k => $v) {
                $des[$k]['index'] = $i;
                $des[$k]['group_name'] = '';
                foreach ($admin_in_data as $admin_in_data_val) {
                    if ($v['admin_id'] == $admin_in_data_val['admin_id']) {
                        $des[$k]['group_name'] = $admin_in_data_val['name'];
                        break;
                    }
                }
                $i++;
            }

            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field' => 'group_name',       //排序字段
            );
            $arrSort = array();
            foreach ($des AS $uniqid => $row) {
                foreach ($row AS $key => $value) {
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            if ($sort['direction']) {
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $des);
            }
        } else {
            $i = 1;
            foreach ($des as $k => $v) {
                $des[$k]['index'] = $i;
                $des[$k]['group_name'] = '';
                foreach ($admin_in_data as $admin_in_data_val) {
                    if ($v['admin_id'] == $admin_in_data_val['admin_id']) {
                        $des[$k]['group_name'] = $admin_in_data_val['name'];
                        break;
                    }
                }
                $i++;
            }
        }

        //$des按全月到诊排序
        foreach ($des as $key => $value) {
            $month_come_ar[] = $value['month_come'] ? $value['month_come'] : 0;
        }
        array_multisort($month_come_ar, SORT_DESC, $des);


        //统计合计数据
        $count_data = array();
        foreach ($des as $k => $v) {

            if (isset($count_data['count'])) {
                $count_data['count'] += $v['count'];
            } else {
                $count_data['count'] = $v['count'];
            }


            if (isset($v['month_action'])) {

                if (isset($count_data['month_action'])) {
                    $count_data['month_action'] += $v['month_action'];
                } else {
                    $count_data['month_action'] = $v['month_action'];
                }
            } else {
                if (!isset($count_data['month_action'])) {
                    $count_data['month_action'] = 0;
                }
            }

            if (isset($v['month_come'])) {
                if (isset($count_data['month_come'])) {
                    $count_data['month_come'] += $v['month_come'];
                } else {
                    $count_data['month_come'] = $v['month_come'];
                }
            } else {
                if (!isset($count_data['month_come'])) {
                    $count_data['month_come'] = 0;
                }
            }


            if (empty($start)) {

                if (isset($v['count_t_add'])) {
                    if (isset($count_data['count_t_add'])) {
                        $count_data['count_t_add'] += $v['count_t_add'];
                    } else {
                        $count_data['count_t_add'] = $v['count_t_add'];
                    }
                } else {
                    if (!isset($count_data['count_t_add'])) {
                        $count_data['count_t_add'] = 0;
                    }
                }


                if (isset($v['count_t_order'])) {
                    if (isset($count_data['count_t_order'])) {
                        $count_data['count_t_order'] += $v['count_t_order'];
                    } else {
                        $count_data['count_t_order'] = $v['count_t_order'];
                    }
                } else {
                    if (!isset($count_data['count_t_order'])) {
                        $count_data['count_t_order'] = 0;
                    }
                }

                if (isset($v['today_come'])) {
                    if (isset($count_data['today_come'])) {
                        $count_data['today_come'] += $v['today_come'];
                    } else {
                        $count_data['today_come'] = $v['today_come'];
                    }
                } else {
                    if (!isset($count_data['today_come'])) {
                        $count_data['today_come'] = 0;
                    }
                }

                if (isset($v['count_t_tommro_add'])) {
                    if (isset($count_data['count_t_tommro_add'])) {
                        $count_data['count_t_tommro_add'] += $v['count_t_tommro_add'];
                    } else {
                        $count_data['count_t_tommro_add'] = $v['count_t_tommro_add'];
                    }
                } else {
                    if (!isset($count_data['count_t_tommro_add'])) {
                        $count_data['count_t_tommro_add'] = 0;
                    }
                }

                if (isset($v['count_t_tommro_come'])) {
                    if (isset($count_data['count_t_tommro_come'])) {
                        $count_data['count_t_tommro_come'] += $v['count_t_tommro_come'];
                    } else {
                        $count_data['count_t_tommro_come'] = $v['count_t_tommro_come'];
                    }
                } else {
                    if (!isset($count_data['count_t_tommro_come'])) {
                        $count_data['count_t_tommro_come'] = 0;
                    }
                }

                for ($i = 1; $i < 32; $i++) {

                    if (isset($v['count_t_add_order_today_' . $i])) {
                        if (isset($count_data['count_t_add_order_today_' . $i])) {
                            $count_data['count_t_add_order_today_' . $i] += $v['count_t_add_order_today_' . $i];
                        } else {
                            $count_data['count_t_add_order_today_' . $i] = $v['count_t_add_order_today_' . $i];
                        }
                    } else {
                        if (!isset($count_data['count_t_add_order_today_' . $i])) {
                            $count_data['count_t_add_order_today_' . $i] = 0;
                        }
                    }

                    if (isset($v['count_t_add_order_' . $i])) {
                        if (isset($count_data['count_t_add_order_' . $i])) {
                            $count_data['count_t_add_order_' . $i] += $v['count_t_add_order_' . $i];
                        } else {
                            $count_data['count_t_add_order_' . $i] = $v['count_t_add_order_' . $i];
                        }
                    } else {
                        if (!isset($count_data['count_t_add_order_' . $i])) {
                            $count_data['count_t_add_order_' . $i] = 0;
                        }
                    }
                }

                if (isset($v['count_t_add_next_order_today'])) {
                    if (isset($count_data['count_t_add_next_order_today'])) {
                        $count_data['count_t_add_next_order_today'] += $v['count_t_add_next_order_today'];
                    } else {
                        $count_data['count_t_add_next_order_today'] = $v['count_t_add_next_order_today'];
                    }
                } else {
                    if (!isset($count_data['count_t_add_next_order_today'])) {
                        $count_data['count_t_add_next_order_today'] = 0;
                    }
                }

                if (isset($v['count_t_add_next_order'])) {
                    if (isset($count_data['count_t_add_next_order'])) {
                        $count_data['count_t_add_next_order'] += $v['count_t_add_next_order'];
                    } else {
                        $count_data['count_t_add_next_order'] = $v['count_t_add_next_order'];
                    }
                } else {
                    if (!isset($count_data['count_t_add_next_order'])) {
                        $count_data['count_t_add_next_order'] = 0;
                    }
                }
            }
        }


        $per_page = 20;
        $count_page = ceil(count($des) / $per_page);
        $des = array_slice($des, 0, $per_page);


        $k_index = 1;
        foreach ($des as $k => $v) {
            $k_index++;
            $i_check = $k_index % 2;

            echo "<tr><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            echo $v['index'] . "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            //查询用户 admin_group
            echo $v['group_name'];

            echo "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            echo $v['admin_name'] . "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            $v['count'] = isset($v['count']) ? intval($v['count']) : 0;
            echo $v['count'];

            echo "</td><td ";


            /***
             * 2017 06 28 添加功能  开始
             *
             */

            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";

            if (isset($v['month_action'])) {
                echo $v['month_action'];
            } else {
                echo 0;
            }
            echo "</td><td ";
            /***
             * 2017 06 28 添加功能  结束
             *
             */


            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";

            if (isset($v['month_come'])) {
                echo $v['month_come'];
            } else {
                echo 0;
            }
            echo "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            if (!isset($v['month_come']) || !isset($v['count'])) {
                echo '0%';
            } else {
                echo number_format((($v['month_come'] / $v['count']) * 100), 0, '.', '') . '%';
            }
            echo "</td>";

            if (empty($start)) {

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_add'])) {
                    echo $v['count_t_add'];
                } else {
                    echo 0;
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";

                if (isset($v['count_t_order'])) {
                    echo $v['count_t_order'];
                } else {
                    echo 0;
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";

                if (isset($v['today_come'])) {
                    echo $v['today_come'];
                } else {
                    echo 0;
                }

                echo "<td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_tommro_add'])) {
                    echo $v['count_t_tommro_add'];
                } else {
                    echo 0;
                }


                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_tommro_come'])) {
                    echo $v['count_t_tommro_come'];
                } else {
                    echo 0;
                }


                for ($i = 1; $i < 32; $i++) {

                    echo "</td><td ";
                    if ($i_check == 0) {
                        echo "style='background-color:#c8c8c8;'";
                    }
                    echo " ><table><tr><td style='width:30px;";
                    if ($i_check == 0) {
                        echo "background-color:#c8c8c8;";
                    }
                    echo "'>";

                    if (isset($v['count_t_add_order_today_' . $i])) {
                        echo $v['count_t_add_order_today_' . $i];
                    } else {
                        echo 0;
                    }

                    echo "</td><td style='width:30px;";
                    if ($i_check == 0) {
                        echo "background-color:#c8c8c8;";
                    }
                    echo "'>";

                    if (isset($v['count_t_add_order_' . $i])) {
                        echo $v['count_t_add_order_' . $i];
                    } else {
                        echo 0;
                    }
                    echo "</td></tr></table> ";
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " ><table><tr><td style='width:30px;";
                if ($i_check == 0) {
                    echo "background-color:#c8c8c8;";
                }
                echo "'>";
                if (isset($v['count_t_add_next_order_today'])) {
                    echo $v['count_t_add_next_order_today'];
                } else {
                    echo 0;
                }

                echo "</td><td style='width:30px;";
                if ($i_check == 0) {
                    echo "background-color:#c8c8c8;";
                }
                echo "'>";
                if (isset($v['count_t_add_next_order'])) {
                    echo $v['count_t_add_next_order'];
                } else {
                    echo 0;
                }

                echo "</td></tr></table></td>";
            }
            echo "</tr>";
        }


        $month_come_percent = '';
        if (!isset($count_data['month_come']) || !isset($count_data['count'])) {
            $month_come_percent = '0%';
        } else {
            $month_come_percent = number_format((($count_data['month_come'] / $count_data['count']) * 100), 0, '.', '') . '%';
        }
        echo "<tr ><td style='background-color:#c8c8c8;' >合计</td>
						   <td style='background-color:#c8c8c8;' ></td>
						   <td style='background-color:#c8c8c8;' ></td>
						   <td style='background-color:#c8c8c8;' >" . $count_data['count'] . "</td>
						   <td style='background-color:#c8c8c8;' >" . $count_data['month_action'] . "</td>
						   <td style='background-color:#c8c8c8;' >" . $count_data['month_come'] . "</td>
						   <td style='background-color:#c8c8c8;' >" . $month_come_percent . "</td>";
        if (empty($start)) {
            echo
                "<td style='background-color:#c8c8c8;' >" . $count_data['count_t_add'] . "</td>
						    <td style='background-color:#c8c8c8;' >" . $count_data['count_t_order'] . "</td>
						    <td style='background-color:#c8c8c8;' >" . $count_data['today_come'] . "</td>
						   <td style='background-color:#c8c8c8;' >" . $count_data['count_t_tommro_add'] . "</td>
						   <td style='background-color:#c8c8c8;' >" . $count_data['count_t_tommro_come'] . "</td>";

            for ($i = 1; $i < 32; $i++) {
                echo "<td  ><table><tr><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_order_today_' . $i] . "</td><td  style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_order_' . $i] . "</td></tr></table></td>";
            }
            echo "
						   <td ><table><tr><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_next_order_today'] . "</td><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_next_order'] . "</td></tr></table></td>";
        }

        echo "</tr>";

        echo "</table> </div></div><br/>";
        echo '<div class="boxLoading"></div>';
        echo '<div id="page"></div>';

        echo <<<EOT
			<link rel="stylesheet" href="/static/css/jquery.page.css">
			<script src="/static/js/jquery.page.js"></script>
		  	<script>
		  		$(function(){
		  			$("#page").Page({
						totalPages: $count_page,//分页总数
						liNums: 3,//分页的数字按钮数(建议取奇数)
						activeClass: 'activP', //active 类样式定义
						callBack : function(page){
								var hos_id = $.trim($('#hos_id').val());
								var keshi_id = $.trim($('#keshi_id').val());
								var date = $.trim($('#inputDate').val());
								$.ajax({
				      				url: '?c=index&m=index_table_ajax',
				      				type: 'POST',
				      				dataType: 'html',
				      				data: {page:page,date:date,hos_id:hos_id,keshi_id:keshi_id,type:'$type'},
				      				beforeSend:function(){
								    	$('.boxLoading').show();
								    	$('#get_month_data_table').hide();
								    },
								    complete:function(){
								    	$('.boxLoading').fadeOut('400', function() {
								    		$('#get_month_data_table').fadeIn('400');
								    	});
								    },
				      				success: function(data){
				      					$("#get_month_data_table").html(data);
				      					$("#get_month_data_table tr").find("td").each(function(){
											if($(this).html() == 0 && $(this).html() != ''){
												$(this).html("<span style='color:red'>0</span>");
											}
										});
				      				}
				      			});
						}
					})
	  	    	});
			</script>
EOT;

        if (empty($start)) {
            echo "<div style='width:100%;float:right;background-color: #dcdcdc;'>
                      <label style='width:100%;color:#00a186;line-height: 30px;font-size:16px;background-color: #d1d1d1;'>&nbsp;&nbsp;&nbsp;&nbsp;<img src='static/img/000.gif' />&nbsp;&nbsp;&nbsp;&nbsp;上月预约客服<span style='margin-left: 70px;'></span></label>
                           <div style=\"width:350px;height:300px;margin-left: 20px;margin-top:0px;background-image:url('static/img/last_paiming.jpg'); background-repeat: no-repeat;\">
                                 <div style=' position:relative; width: 320px;margin-left: 30px;height:260px;'>";

            $j = 0;
            foreach ($res_last_month as $k => $v) {
                if (isset($v['last_month_come'])) {
                    $j++;
                    echo "<p style='line-height: 18px;";
                    if ($j == 1) {
                        echo 'padding-top:55px;';
                    } elseif ($j == 2) {
                        echo 'padding-top:58px;';
                    } elseif ($j == 3) {
                        echo 'padding-top:58px;';
                    }
                    echo "font-size:14px;padding-left: 40px; '><font></font><font style='margin-left:20px;color:#fff;'>";
                    echo $v['admin_name'] . "</font><font style='margin-left: 20px;color:#fff;'>到诊";
                    echo $v['last_month_come'] . "人 &nbsp;&nbsp;到诊率：";
                    if (empty($v['last_month_come']) || empty($v['last_month_add'])) {
                        echo '0';
                    } else {
                        echo number_format((($v['last_month_come'] / $v['last_month_add']) * 100), 0, '.', '');
                    }
                    echo "%</font></p>";
                } else {
                    echo '';
                }
            }
            echo "</div></div></div></div></div>";

            /*卓总安排的需求数据
			   组到诊统计
			    $sql_group="select   from ".$this->common->table('user_groups').$where." come_time between ".$month_time_start." and ".$month_time_end;
			    */
            /**
             * 获得所要获取的组
             */
            if (!empty($_REQUEST['hos_id'])) {
                if ($_REQUEST['hos_id'] == '1_7') {
                    $group_array = array(24, 23, 66, 63, 75, 67, 68, 69, 70, 61, 73, 62, 64, 65, 74);
                } else {
                    $group_array = array(67, 68, 69, 70, 61, 73, 62, 63, 64, 65, 66, 75, 24, 74);
                }
            } else {
                $group_array = array(67, 68, 69, 70, 61, 73, 62, 63, 64, 65, 66, 75, 24, 74);
            }

            $group_str = implode(",", $group_array);

            $where = " where 1";
            $term = " where 1";
            if (!empty($_REQUEST['hos_id'])) {
                if ($_REQUEST['hos_id'] == '1_1') {
                    $hos_id_array = explode('_', $_REQUEST['hos_id']);
                    if (count($hos_id_array) > 1) {
                        $where .= " AND hos_id=" . $hos_id_array[0];
                        $where .= " AND keshi_id in(1)";
                        $term .= " AND hos_id=" . $hos_id_array[0];
                        $term .= " AND keshi_id in(1)";
                    }
                } else if ($_REQUEST['hos_id'] == '1_7') {
                    $hos_id_array = explode('_', $_REQUEST['hos_id']);
                    if (count($hos_id_array) > 1) {
                        $where .= " AND hos_id=" . $hos_id_array[0];
                        $where .= " AND keshi_id = 7 ";
                        $term .= " AND hos_id=1";
                        $term .= " AND keshi_id in(1,32)";
                    }
                } else {
                    $where .= " AND hos_id=" . $_REQUEST['hos_id'];
                    if (!empty($_REQUEST['keshi_id'])) {
                        $where .= " AND keshi_id=" . $_REQUEST['keshi_id'];
                        $term .= " AND keshi_id=" . $_REQUEST['keshi_id'];
                    } else if (!empty($_COOKIE['l_keshi_id'])) {
                        $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                        $term .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                    }
                }
            } else {
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                    $term .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                }
                if (!empty($_COOKIE['l_keshi_id'])) {
                    $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                    $term .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                }
            }

            if ($fuke != "1" && $_REQUEST['hos_id'] == 1) {
                $special_keshi_id_array = explode(',', $fuke);
                if (count($special_keshi_id_array) > 1) {
                    /*判斷是否是人流*/
                    if ($special_keshi_id_array[0] == 'js') {
                        $where = " where 1 AND hos_id = 1";
                    } else {
                        $where = " where 1 AND hos_id = 1 AND keshi_id in(" . $fuke . ")";
                    }
                }

            }

            $sql_group = "select id,name,admin_id from " . $this->common->table('user_groups') . $term;
            $info_group_arr = $this->common->getALL($sql_group);
            $info_group = array();
            foreach ($group_array as $key => $val) {
                foreach ($info_group_arr as $v) {
                    if (strcmp($v['id'], $val) == 0) {
                        $info_group[$key] = $v;
                    }
                }
            }

            $info_id_str = '';
            $admin_id_str = 0;
            foreach ($info_group as $info_group_s) {
                if (empty($info_id_str)) {
                    $info_id_str = $info_group_s['id'];
                } else {
                    $info_id_str = $info_id_str . ',' . $info_group_s['id'];
                }
                if (!empty($info_group_s['admin_id'])) {
                    if (empty($admin_id_str)) {
                        $admin_id_str = $info_group_s['admin_id'];
                    } else {
                        $admin_id_str = $admin_id_str . ',' . $info_group_s['admin_id'];
                    }
                }
            }
            $admin_sql = "SELECT admin_id,admin_name,admin_username FROM " . $this->common->table('admin') . " WHERE admin_id in(" . $admin_id_str . ") ";
            $group_admin_list = $this->common->getAll($admin_sql);
            if (!empty($info_id_str)) {
                $sql_user = "select admin_id,admin_group from " . $this->common->table('admin') . ' where admin_group in(' . $info_id_str . ') and is_pass = 1';
                $info_admin = $this->common->getALL($sql_user);
                $order_admin_id_str = '';
                foreach ($info_admin as $info_admin_ts) {
                    if (empty($order_admin_id_str)) {
                        $order_admin_id_str = $info_admin_ts['admin_id'];
                    } else {
                        $order_admin_id_str = $order_admin_id_str . ',' . $info_admin_ts['admin_id'];
                    }
                }

                /**按照添加日期来搜索**/
                $time = date("Y-m-01", time());
                $month_time_start = strtotime($time . " 00:00:00");
                $month_time_end = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
                /*echo date("Y-m-d ",$month_time_start).'/'.date("Y-m-d ",$month_time_end);exit;*/
                $sql_order = "select admin_id,order_id,order_addtime,order_time,come_time,is_come from " . $this->common->table('order') . $where . ' and admin_id in(' . $order_admin_id_str . ') and  ( order_addtime between ' . $month_time_start . ' and ' . $month_time_end . ')';
                $info_order = $this->common->getALL($sql_order);
                $info_order_count = array();//待计算order
                $order_id_str = '';
                foreach ($info_order as $data_temp) {
                    if (empty($order_id_str)) {
                        $order_id_str = $data_temp['order_id'];
                    } else {
                        $order_id_str = $order_id_str . ',' . $data_temp['order_id'];
                    }
                }

                if (!empty($order_id_str)) {
                    /*获取取消订单记录*/
                    $apisql = 'select * from ' . $this->common->table('order_out') . " where order_id in(" . $order_id_str . ")";
                    $api_out_order_data = $this->common->getAll($apisql);
                    foreach ($info_order as $data_temp) {
                        $out_check = 0;
                        foreach ($api_out_order_data as $out_order_data_temp) {
                            if (strcmp($out_order_data_temp['order_id'], $data_temp['order_id']) == 0) {
                                $out_check = 1;
                                break;
                            }
                        }
                        if (empty($out_check)) {
                            $info_order_count[] = $data_temp;
                        }
                    }
                }

                /*预约单有数据*/
                if (count($info_order_count) > 0) {
                    foreach ($info_admin as $info_admin_key => $info_admin_ts) {
                        if (!isset($info_admin[$info_admin_key]['come'])) {
                            $info_admin[$info_admin_key]['come'] = 0;
                        }
                        if (!isset($info_admin[$info_admin_key]['yy'])) {
                            $info_admin[$info_admin_key]['yy'] = 0;
                        }
                        if (!isset($info_admin[$info_admin_key]['yd'])) {
                            $info_admin[$info_admin_key]['yd'] = 0;
                        }
                        foreach ($info_order_count as $info_order_count_ts) {
                            if (strcmp($info_order_count_ts['admin_id'], $info_admin_ts['admin_id']) == 0) {
                                if ($info_order_count_ts['come_time'] >= $month_time_start && $info_order_count_ts['come_time'] <= $month_time_end && $info_order_count_ts['is_come'] == 1) {
                                    $info_admin[$info_admin_key]['come'] = $info_admin[$info_admin_key]['come'] + 1;
                                }
                                if ($info_order_count_ts['order_time'] >= $month_time_start && $info_order_count_ts['order_time'] <= $month_time_end) {
                                    $info_admin[$info_admin_key]['yd'] = $info_admin[$info_admin_key]['yd'] + 1;
                                }
                                if ($info_order_count_ts['order_addtime'] >= $month_time_start && $info_order_count_ts['order_addtime'] <= $month_time_end) {
                                    $info_admin[$info_admin_key]['yy'] = $info_admin[$info_admin_key]['yy'] + 1;
                                }
                            }
                        }
                    }


                    /*按照到诊总数排序倒序输出*/
                    $grou_sort = array();
                    foreach ($info_group as $info_group_key => $info_group_ts) {
                        $info_admin_key_check = 0;
                        $info_yy_count = 0;
                        $info_yd_count = 0;
                        $info_come_count = 0;
                        foreach ($info_admin as $info_admin_ts) {
                            if (strcmp($info_admin_ts['admin_group'], $info_group_ts['id']) == 0) {
                                if (empty($info_admin_key_check)) {
                                    $info_admin_key_check = $info_admin_ts['admin_id'];
                                } else {
                                    $info_admin_key_check = $info_admin_key_check . ',' . $info_admin_ts['admin_id'];
                                }
                                $info_yy_count = $info_yy_count + $info_admin_ts['yy'];
                                $info_yd_count = $info_yd_count + $info_admin_ts['yd'];
                                $info_come_count = $info_come_count + $info_admin_ts['come'];
                            }
                        }
                        $admin_name = '';
                        foreach ($group_admin_list as $group_admin_list_ts) {
                            if (strcmp($group_admin_list_ts['admin_id'], $info_group_ts['admin_id']) == 0) {
                                $admin_name = $group_admin_list_ts['admin_name'];
                                break;
                            }
                        }
                        $info_group[$info_group_key]['admin_name'] = $admin_name;
                        $info_group[$info_group_key]['yy'] = $info_yy_count;
                        $info_group[$info_group_key]['yd'] = $info_yd_count;
                        $info_group[$info_group_key]['come'] = $info_come_count;
                        $grou_sort_ts = sprintf('%.2f', ($info_come_count / $info_yd_count * 100));
                        /*var_dump($info_come_count.'/'.$info_yy_count.'/'.$grou_sort_ts);*/
                        $grou_sort[] = $grou_sort_ts;
                        $info_group[$info_group_key]['come_lv'] = $grou_sort_ts . '%';
                        $info_group[$info_group_key]['come_count'] = $grou_sort_ts;
                    }

                    //数组倒序
                    //	rsort($grou_sort);
                    /*var_dump(json_encode($info_group));*/
                    /*exit;*/
                    echo '<div id="" style=" width:100%;display:block;float:left">
			         		<div style="width:100%;background-color: #dcdcdc; ">
			         		<label style="width:100%;color:#00a186;line-height: 30px;font-size:16px;background-color: #d1d1d1;">&nbsp;&nbsp;&nbsp;&nbsp;<img src="static/img/000.gif">本月预约客服<font style="font-size:12px;float:right;color:#808080;">注：相同到诊数以到诊率为标准&nbsp;&nbsp;</font></label>                                  <table id="get_month_data_table" class="table-striped" style="width:100%;text-align:center;">
			         		 <tbody><tr><td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">组到诊</span></td>										 	  <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">所属组</span></td>										      <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">组长</span></td>											  <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">全月预约</span></td>          		  							  <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">全月预到</span></td>											  <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">全月到诊</span></td>		 							  <td style="width:50px;text-align:center;"><span style="color:#00a187;font-size:12px">预到到诊率</span></td></tr>';
                    $group_id_cehck = array();
                    foreach ($grou_sort as $grou_sort_key => $grou_sort_ts) {
                        foreach ($info_group as $info_group_eky => $info_group_ts) {
                            if (!in_array($info_group_ts['id'], $group_id_cehck) && $grou_sort_ts == $info_group_ts['come_count']) {
                                $group_id_cehck[] = $info_group_ts['id'];
                                echo '<tr><td>';
                                if ($grou_sort_key + 1 != count($grou_sort)) {
                                    echo ++$grou_sort_key;
                                }
                                echo '</td><td>' . $info_group_ts['name'] . '</td><td>' . $info_group_ts['admin_name'] . '</td><td>' . $info_group_ts['yy'] . '</td><td>' . $info_group_ts['yd'] . '</td><td>' . $info_group_ts['come'] . '</td><td>' . $info_group_ts['come_lv'] . '</td></tr>';
                                break;
                            }
                        }
                    }
                    echo '</tbody></table> </div></div>';
                }
            }
        }
    }

    public function admin_log()
    {
        $data = array();
        $data = $this->common->config('admin_log');
        $admin_name = isset($_REQUEST['admin_name']) ? trim($_REQUEST['admin_name']) : '';
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : "";//日期格式为2015年04月03日-2016年04月23日
        $where = 1;
        if (!empty($admin_name)) {

            $where .= " and admin_name='" . $admin_name . "'";
        }

        if (!empty($date)) {
            $time = explode("-", $date);
            $s = trim($time[0]);
            $e = trim($time[1]);
            $s_time = str_replace(array("年", "月", "日"), "-", $s);
            $e_time = str_replace(array("年", "月", "日"), "-", $e);
            $s_time = substr($s_time, 0, -1);
            $e_time = substr($e_time, 0, -1);
            $start_time = $s_time . " 00:00:00";
            $end_time = $e_time . " 23:59:59";
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);
            $data['start'] = $s;
            $data['end'] = $e;
        } else {
            $t_time = date("Y-m-d", time());
            $start_time = $t_time . " 00:00:00";
            $end_time = $t_time . " 23:59:59";
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);

            $data['start'] = date("Y年m月d日", time());
            $data['end'] = date("Y年m月d日", time());
        }
        $where .= " and log_time>=" . $start_time . " and log_time<=$end_time";
        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $per_page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 30;
        $per_page = empty($per_page) ? 30 : $per_page;
        $this->load->library('pagination');
        $this->load->helper('page');
        $count = $this->common->getOne("SELECT COUNT(*) FROM " . $this->common->table("admin_log") . " where " . $where);
        $config = page_config();
        $config['base_url'] = '?c=index&m=admin_log&p=' . $per_page . '&admin_name=' . $admin_name . '&date=' . $data['start'] . '+-+' . $data['end'];
        $config['total_rows'] = $count;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['num_links'] = 3;
        $this->pagination->initialize($config);
        $log = $this->model->log_list($where, $page, $per_page);
        $sql = "select a.admin_name,r.rank_name from " . $this->common->table('admin') . "  a left join " . $this->common->table('rank') . " r on a.rank_id=r.rank_id";
        $rank_list = $this->common->getAll($sql);
        foreach ($rank_list as $val) {
            $rank[$val['admin_name']] = $val['rank_name'];
        }
        $data['rank'] = $rank;
        $data['log'] = $log;

        $data['page'] = $this->pagination->create_links();
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('admin_log', $data);
    }

    public function admin_list()
    {

        $data = array();
        $data = $this->common->config('admin_list');
        $this->load->helper('page');
        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $this->load->library('pagination');
        if ($_COOKIE['l_rank_id'] > 1) {
            $rank_info = $this->common->getRow("SELECT rank_level, parent_id FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
            $rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
            $rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $rank_info['parent_id']);
            $ranks = array();

            // 把同一组的全部获取出来
            foreach ($rank as $key => $val) {
                if ($val['rank_id'] == $_COOKIE['l_rank_id']) {
                    $ranks = $val;
                }
            }
            $ranks = recursive_merge($ranks, 'rank_id');
            $rank = array();
            $rank = array_keys($ranks);
        } else {
            $rank[] = 'all';
        }

        $admin_count = $this->model->admin_count();
        $config = page_config();
        $config['base_url'] = '?c=index&m=admin_list';
        $config['total_rows'] = $admin_count;
        $config['per_page'] = '5000';
        $config['uri_segment'] = 10;
        $config['num_links'] = 5;
        $this->pagination->initialize($config);
        $data['page'] = $this->pagination->create_links();

        $data['rank_id_arr'] = $rank;
        $data['rank_id'] = $_COOKIE['l_rank_id'];
        $data['admin_id'] = $_COOKIE['l_admin_id'];

        $data['admin_list'] = $this->model->get_admin_list($page, $config['per_page'], $rank);

        /****
         * 查看岗位类型
         * */
        $rank_type_data = $this->common->getRow("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
        if ($rank_type_data['rank_type'] == 6) {
            $rank_type_all_data = $this->common->getAll("SELECT rank_id FROM " . $this->common->table('rank') . " WHERE rank_user like \"%'" . $_COOKIE['l_admin_id'] . "'%\"");
            $rank_id_str = '';
            foreach ($rank_type_all_data as $rank_type_all_data_s) {
                if (empty($rank_id_str)) {
                    $rank_id_str = $rank_type_all_data_s['rank_id'];
                } else {
                    $rank_id_str = $rank_id_str . ',' . $rank_type_all_data_s['rank_id'];
                }
            }
            if (!empty($rank_id_str)) {
                $data['admin_list'] = $this->common->getAll("SELECT a.admin_id,a.admin_name,a.admin_username,a.rank_id,a.admin_logintimes,a.is_pass,ai.admin_sex,ai.admin_tel,ai.admin_tel_duan,ai.admin_qq,ai.admin_email,a.admin_lasttime,a.admin_nowtime,r.rank_name FROM " . $this->common->table('admin') . " as a," . $this->common->table('admin_info') . " as ai," . $this->common->table('rank') . " as r WHERE  a.rank_id in(" . $rank_id_str . ") and a.admin_id = ai.admin_id and a.rank_id = r.rank_id  ORDER BY CONVERT(a.admin_name USING gbk) asc ");
            }
        }

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('admin_list', $data);
    }

    public function admin_info()
    {
        $data = array();
        $admin_id = empty($_REQUEST['admin_id']) ? 0 : intval($_REQUEST['admin_id']);
        if (empty($admin_id)) {
            $data = array();
            $data = $this->common->config('admin_add');
            $level_id = $this->common->getOne("SELECT rank_level FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
            //添加$level_id的判断，
//                        if(!empty($level_id)){
//
//                            $level_id=$level_id;
//                        }else{
//                            $level_id=5;
//                        }
            $rank = $this->common->getAll("SELECT * FROM " . $this->common->table('rank') . " WHERE rank_level >= $level_id");
            if ($_COOKIE['l_rank_id'] == 1) {
                $l_rank_id = 0;
            } else {
                $l_rank_id = $_COOKIE['l_rank_id'];
            }
            $rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $l_rank_id);
            $rank_option = options($rank, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '');


            $group_list = array();
            $sql = "select hos_id,hos_name from " . $this->common->table('hospital');
            $group_hos_list = $this->common->getAll($sql);
            $sql = "select keshi_id,keshi_name from " . $this->common->table('keshi');
            $group_keshi_list = $this->common->getAll($sql);

            $sql = "select id,parent_id,name,hos_id,keshi_id from " . $this->common->table('user_groups') . "  ORDER BY CONVERT(name USING gbk) asc ";
            $group_3_list = $this->common->getAll($sql);
            foreach ($group_3_list as $group_3_list_t) {
                $rank_s = 0;
                foreach ($group_list as $group_list_t) {
                    if ($group_3_list_t['id'] == $group_list_t['id']) {
                        $rank_s = 1;
                        break;
                    }
                }
                if (empty($rank_s)) {
                    $hos_name = '';
                    foreach ($group_hos_list as $group_hos_list_t) {
                        if ($group_hos_list_t['hos_id'] == $group_3_list_t['hos_id']) {
                            $hos_name = $group_hos_list_t['hos_name'];
                            break;
                        }
                    }
                    $keshi_name = '';
                    foreach ($group_keshi_list as $group_keshi_list_t) {
                        if ($group_keshi_list_t['keshi_id'] == $group_3_list_t['keshi_id']) {
                            $keshi_name = $group_keshi_list_t['keshi_name'];
                            break;
                        }
                    }
                    if (empty($hos_name) && empty($keshi_name)) {
                        $group_3_list_t['name'] = $group_3_list_t['name'];
                    } else if (!empty($hos_name) && empty($keshi_name)) {
                        $group_3_list_t['name'] = $hos_name . '-' . $group_3_list_t['name'];
                    } else if (!empty($hos_name) && !empty($keshi_name)) {
                        $group_3_list_t['name'] = $hos_name . '-' . $keshi_name . '-' . $group_3_list_t['name'];
                    }
                    $group_list[] = $group_3_list_t;
                }
            }
            $data['group_list'] = $group_list;


            /****
             * 查看岗位类型
             * */
            $rank_type_data = $this->common->getRow("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
            if ($rank_type_data['rank_type'] == 6) {
                $rows = $this->common->getAll("SELECT * FROM " . $this->common->table('rank') . " WHERE rank_user like \"%'" . $_COOKIE['l_admin_id'] . "'%\" ");

                $id = 'rank_id';
                $pid = 'parent_id';
                $child = 'child';
                $root = $this->common->getAll("SELECT min(rank_id) as rank_id FROM " . $this->common->table('rank') . " WHERE rank_user like \"%'" . $_COOKIE['l_admin_id'] . "'%\" ");

                $tree = array(); // 树
                if (is_array($rows)) {
                    $array = array();
                    foreach ($rows as $key => $item) {
                        $array[$item[$id]] =& $rows[$key];
                    }
                    foreach ($rows as $key => $item) {
                        $parentId = $item[$pid];
                        if ($root[0]['rank_id'] == $parentId) {
                            $tree[] =& $rows[$key];
                        } else {
                            if (isset($array[$parentId])) {
                                $parent =& $array[$parentId];
                                $parent[$child][] =& $rows[$key];
                            }
                        }
                    }
                }
                $rank_option = options($tree, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '', '');
            }

        } else {
            //判定代码，如果自己的成员中没有，则显示没有相关权限 代码开始
            if ($_COOKIE['l_rank_id'] > 1) {
                $rank_info = $this->common->getRow("SELECT rank_level, parent_id FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
                $rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
                $rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $rank_info['parent_id']);
                $ranks = array();

                // 把同一组的全部获取出来
                foreach ($rank as $key => $val) {
                    if ($val['rank_id'] == $_COOKIE['l_rank_id']) {
                        $ranks = $val;
                    }
                }
                $ranks = recursive_merge($ranks, 'rank_id');
                $rank = array();
                $rank = array_keys($ranks);
            } else {
                $rank[] = 'all';
            }

            $rank_6_hos_str = '';//当用户岗位类型为6的数据  获取医院ID
            $rank_6_keshi_str = '';//当用户岗位类型为6的数据  获取科室ID

            $admin_rank_id = $this->common->getOne("select rank_id from " . $this->common->table('admin') . " where admin_id= " . $admin_id);

            //获取所有岗位科室
            $rank_keshi_info = $this->common->getAll("SELECT hos_id,keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $admin_rank_id);
            foreach ($rank_keshi_info as $rank_keshi_info_s) {
                if (empty($rank_6_keshi_str)) {
                    $rank_6_keshi_str = $rank_keshi_info_s['keshi_id'];
                } else {
                    $rank_6_keshi_str = $rank_6_keshi_str . ',' . $rank_keshi_info_s['keshi_id'];
                }
                if (empty($rank_6_hos_str)) {
                    $rank_6_hos_str = $rank_keshi_info_s['hos_id'];
                } else {
                    $rank_6_hos_str = $rank_6_hos_str . ',' . $rank_keshi_info_s['hos_id'];
                }
            }

            /****
             * 查看岗位类型
             * */
            $rank_type_data = $this->common->getRow("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
            if ($rank_type_data['rank_type'] == 6) {
                $rank_user_data = $this->common->getRow("SELECT rank_user FROM " . $this->common->table('rank') . " WHERE rank_id = " . $admin_rank_id);
                if (empty($rank_user_data['rank_user'])) {
                    if (in_array($admin_rank_id, $rank) == false && $rank[0] != 'all') {
                        $this->common->msg('你没有相关的权限编辑该成员！');
                    }
                } else {
                    if (!empty($rank_user_data['rank_user'])) {
                        $rank_user = explode(",", $rank_user_data['rank_user']);
                    } else {
                        $rank_user = array();
                    }
                    if (empty($rank_user_data['rank_user']) || !in_array("'" . $_COOKIE['l_admin_id'] . "'", $rank_user)) {
                        $this->common->msg('你没有相关的权限编辑该成员！');
                    }
                }
            } else {
                if (in_array($admin_rank_id, $rank) == false && $rank[0] != 'all') {
                    $this->common->msg('你没有相关的权限编辑该成员！');
                }
            }

            $data = array();
            $data = $this->common->config('admin_edit');

            $info = $this->common->getRow("SELECT a.*, i.* FROM " . $this->common->table('admin') . " a LEFT JOIN " . $this->common->table('admin_info') . " i ON i.admin_id = a.admin_id WHERE a.admin_id = $admin_id");
            $info['birthday'] = date("Y-m-d", $info['admin_birthday']);
            $data['info'] = $info;

            $level_id = $this->common->getOne("SELECT rank_level FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
            if (empty($level_id)) {
                $this->common->msg("没有权限操作！", 1);

            } else {
                $rank = $this->common->getAll("SELECT * FROM " . $this->common->table('rank') . " WHERE rank_level >= " . $level_id);

            }
            if ($_COOKIE['l_rank_id'] == 1) {
                $l_rank_id = 0;
            } else {
                $l_rank_id = $_COOKIE['l_rank_id'];
            }
            $rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $l_rank_id);
            $rank_option = options($rank, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '', $info['rank_id']);

            $asker = $this->common->getRow("SELECT * FROM " . $this->common->table('asker') . " WHERE admin_id = $admin_id");
            $data['asker'] = $asker;

            $group_list = array();

            if (empty($rank_6_hos_str)) {
                $rank_6_hos_str = 0;
            }
            if (empty($rank_6_keshi_str)) {
                $rank_6_keshi_str = 0;
            }

            $sql = "select hos_id,hos_name from " . $this->common->table('hospital') . " where hos_id in(" . $rank_6_hos_str . ")";
            $group_hos_list = $this->common->getAll($sql);

            $sql = "select keshi_id,keshi_name from " . $this->common->table('keshi') . " where  keshi_id in(" . $rank_6_keshi_str . ")";
            $group_keshi_list = $this->common->getAll($sql);

            $sql = "select id,parent_id,name,hos_id,keshi_id from " . $this->common->table('user_groups') . " where  keshi_id in(" . $rank_6_keshi_str . ") ORDER BY CONVERT(name USING gbk) asc ";
            $group_2_list = $this->common->getAll($sql);
            foreach ($group_2_list as $group_2_list_t) {
                $rank_s = 0;
                foreach ($group_list as $group_list_t) {
                    if ($group_2_list_t['id'] == $group_list_t['id']) {
                        $rank_s = 1;
                        break;
                    }
                }
                if (empty($rank_s)) {
                    $hos_name = '';
                    foreach ($group_hos_list as $group_hos_list_t) {
                        if ($group_hos_list_t['hos_id'] == $group_2_list_t['hos_id']) {
                            $hos_name = $group_hos_list_t['hos_name'];
                            break;
                        }
                    }
                    $keshi_name = '';
                    foreach ($group_keshi_list as $group_keshi_list_t) {
                        if ($group_keshi_list_t['keshi_id'] == $group_2_list_t['keshi_id']) {
                            $keshi_name = $group_keshi_list_t['keshi_name'];
                            break;
                        }
                    }
                    if (empty($hos_name) && empty($keshi_name)) {
                        $group_2_list_t['name'] = $group_2_list_t['name'];
                    } else if (!empty($hos_name) && empty($keshi_name)) {
                        $group_2_list_t['name'] = $hos_name . '-' . $group_2_list_t['name'];
                    } else if (!empty($hos_name) && !empty($keshi_name)) {
                        $group_2_list_t['name'] = $hos_name . '-' . $keshi_name . '-' . $group_2_list_t['name'];
                    }
                    $group_list[] = $group_2_list_t;
                }
            }

            $sql = "select id,parent_id,name,hos_id,keshi_id from " . $this->common->table('user_groups') . " where  hos_id in(" . $rank_6_hos_str . ") ORDER BY CONVERT(name USING gbk) asc ";
            $group_1_list = $this->common->getAll($sql);
            foreach ($group_1_list as $group_1_list_t) {
                $rank_s = 0;
                foreach ($group_list as $group_list_t) {
                    if ($group_1_list_t['id'] == $group_list_t['id']) {
                        $rank_s = 1;
                        break;
                    }
                }
                if (empty($rank_s)) {
                    $hos_name = '';
                    foreach ($group_hos_list as $group_hos_list_t) {
                        if ($group_hos_list_t['hos_id'] == $group_1_list_t['hos_id']) {
                            $hos_name = $group_hos_list_t['hos_name'];
                            break;
                        }
                    }
                    $keshi_name = '';
                    foreach ($group_keshi_list as $group_keshi_list_t) {
                        if ($group_keshi_list_t['keshi_id'] == $group_1_list_t['keshi_id']) {
                            $keshi_name = $group_keshi_list_t['keshi_name'];
                            break;
                        }
                    }
                    if (empty($hos_name) && empty($keshi_name)) {
                        $group_1_list_t['name'] = $group_1_list_t['name'];
                    } else if (!empty($hos_name) && empty($keshi_name)) {
                        $group_1_list_t['name'] = $hos_name . '-' . $group_1_list_t['name'];
                    } else if (!empty($hos_name) && !empty($keshi_name)) {
                        $group_1_list_t['name'] = $hos_name . '-' . $keshi_name . '-' . $group_1_list_t['name'];
                    }
                    $group_list[] = $group_1_list_t;
                }
            }

            $sql = "select id,parent_id,name,hos_id,keshi_id from " . $this->common->table('user_groups') . " where hos_id = 0 or keshi_id = 0 ORDER BY CONVERT(name USING gbk) asc ";
            $group_3_list = $this->common->getAll($sql);
            foreach ($group_3_list as $group_3_list_t) {
                $rank_s = 0;
                foreach ($group_list as $group_list_t) {
                    if ($group_3_list_t['id'] == $group_list_t['id']) {
                        $rank_s = 1;
                        break;
                    }
                }
                if (empty($rank_s)) {
                    $hos_name = '';
                    foreach ($group_hos_list as $group_hos_list_t) {
                        if ($group_hos_list_t['hos_id'] == $group_3_list_t['hos_id']) {
                            $hos_name = $group_hos_list_t['hos_name'];
                            break;
                        }
                    }
                    $keshi_name = '';
                    foreach ($group_keshi_list as $group_keshi_list_t) {
                        if ($group_keshi_list_t['keshi_id'] == $group_3_list_t['keshi_id']) {
                            $keshi_name = $group_keshi_list_t['keshi_name'];
                            break;
                        }
                    }
                    if (empty($hos_name) && empty($keshi_name)) {
                        $group_3_list_t['name'] = $group_3_list_t['name'];
                    } else if (!empty($hos_name) && empty($keshi_name)) {
                        $group_3_list_t['name'] = $hos_name . '-' . $group_3_list_t['name'];
                    } else if (!empty($hos_name) && !empty($keshi_name)) {
                        $group_3_list_t['name'] = $hos_name . '-' . $keshi_name . '-' . $group_3_list_t['name'];
                    }
                    $group_list[] = $group_3_list_t;
                }
            }
            $data['group_list'] = $group_list;

            /****
             * 查看岗位类型
             * */
            if ($rank_type_data['rank_type'] == 6) {
                $rows = $this->common->getAll("SELECT * FROM " . $this->common->table('rank') . " WHERE rank_user like \"%'" . $_COOKIE['l_admin_id'] . "'%\" ");

                $id = 'rank_id';
                $pid = 'parent_id';
                $child = 'child';
                $root = $this->common->getAll("SELECT min(rank_id) as rank_id FROM " . $this->common->table('rank') . " WHERE rank_user like \"%'" . $_COOKIE['l_admin_id'] . "'%\" ");

                $tree = array(); // 树
                if (is_array($rows)) {
                    $array = array();
                    foreach ($rows as $key => $item) {
                        $array[$item[$id]] =& $rows[$key];
                    }
                    foreach ($rows as $key => $item) {
                        $parentId = $item[$pid];
                        if ($root[0]['rank_id'] == $parentId) {
                            $tree[] =& $rows[$key];
                        } else {
                            if (isset($array[$parentId])) {
                                $parent =& $array[$parentId];
                                $parent[$child][] =& $rows[$key];
                            }
                        }
                    }
                }
                $rank_option = options($tree, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '', '');
            }
        }

        $data['rank_option'] = $rank_option;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('admin_info', $data);
    }

    public function admin_update()
    {
        $form_action = $_REQUEST['form_action'];
        $admin_id = isset($_REQUEST['admin_id']) ? intval($_REQUEST['admin_id']) : 0;
        $log_errs = isset($_REQUEST['log_errs']) ? intval($_REQUEST['log_errs']) : 0;
        $admin_username = trim($_REQUEST['admin_username']);
        $openid = trim($_REQUEST['openid']);
        $admin_password = trim($_REQUEST['admin_password']);
        $confrim_pass = trim($_REQUEST['confrim_pass']);
        $admin_name = trim($_REQUEST['admin_name']);
        $admin_sex = intval($_REQUEST['admin_sex']);
        $admin_birthday = trim($_REQUEST['admin_birthday']);
        $admin_tel = trim($_REQUEST['admin_tel']);
        $admin_tel_duan = trim($_REQUEST['admin_tel_duan']);
        $admin_qq = trim($_REQUEST['admin_qq']);
        $is_pass = trim($_REQUEST['is_pass']);
        $admin_group = trim($_REQUEST['admin_group']);
        $login_style = trim($_REQUEST['login_style']);
        if (empty($login_style)) {
            $login_style = 0;
        }
        $email = substr(md5(time()), 0, 6) . '@sina.com';
        $admin_email = empty($_REQUEST['admin_email']) ? $email : trim($_REQUEST['admin_email']);

        $admin_address = trim($_REQUEST['admin_address']);
        $rank_id = intval($_REQUEST['rank_id']);
        $admin_action = empty($_REQUEST['admin_action']) ? '' : implode(",", $_REQUEST['admin_action']);

        //人事行政类型 直接把岗位权限赋值给用户
        $rank_type_data = $this->common->getRow("SELECT rank_id,rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
        if ($rank_type_data['rank_type'] == 6) {
            $rank_action_data = $this->common->getRow("SELECT rank_action FROM " . $this->common->table('rank') . " WHERE rank_id = " . $rank_id);
            if (count($rank_action_data) > 0) {
                $admin_action = $rank_action_data['rank_action'];
            }
            //echo $admin_action;exit;
        }

        $asker_swt_name = isset($_REQUEST['asker_swt_name']) ? trim($_REQUEST['asker_swt_name']) : '';
        $asker_qiao_name = isset($_REQUEST['asker_qiao_name']) ? trim($_REQUEST['asker_qiao_name']) : '';

        $admin_birthday = strtotime($admin_birthday);
        if (empty($admin_username)) {
            $this->common->msg("账户不能为空！", 1);
        }
        if (empty($admin_name)) {
            $this->common->msg("用户名不能为空！", 1);
        }

//		if(empty($rank_id))
//		{
//			$this->common->msg("请选择所属岗位！", 1);
//		}

        if ($admin_password !== $confrim_pass) {
            $this->common->msg($this->lang->line('password_different'), 1);
        }

        if ($form_action == 'update') {
            $is_havd = $this->common->getOne("SELECT admin_id FROM " . $this->common->table("admin") . " WHERE admin_username = '" . $admin_username . "' AND admin_id != $admin_id");
        } else {
            $is_havd = $this->common->getOne("SELECT admin_id FROM " . $this->common->table("admin") . " WHERE admin_username = '" . $admin_username . "'");
        }

        if ($is_havd) {
            $this->common->msg("您输入的用户名已经存在，请更换新的再提交！", 1);
        }

        $admin_main = array('admin_username' => $admin_username,
            'admin_password' => md5($admin_password),
            'admin_name' => $admin_name,
            'rank_id' => $rank_id,
            'admin_action' => $admin_action,
            'admin_addtime' => time(),
            'log_errs' => $log_errs,
            'is_pass' => $is_pass,
            'admin_group' => $admin_group,
            'login_style' => $login_style
        );

//		include './api/config.php';
//		include './api/uc_client/client.php';
//		if(!empty($admin_password))
//		{
//			$admin_main['admin_password'] = md5($admin_password);
//			$ucresult = uc_user_edit($_REQUEST['admin_username'], '', $_REQUEST['admin_password'], $admin_email, 1);
//			if($ucresult == -1) {
//				$this->common->msg('旧密码不正确', 1);
//				exit();
//			} elseif($ucresult == -4) {
//				$this->common->msg('Email 格式有误', 1);
//				exit();
//			} elseif($ucresult == -5) {
//				$this->common->msg('Email 不允许注册', 1);
//				exit();
//			} elseif($ucresult == -6) {
//				$this->common->msg('该 Email 已经被注册', 1);
//				exit();
//			}
//		}

        $admin_info = array('admin_sex' => $admin_sex,
            'admin_birthday' => $admin_birthday,
            'admin_tel' => $admin_tel,
            'admin_tel_duan' => $admin_tel_duan,
            'admin_qq' => $admin_qq,
            'admin_email' => $admin_email,
            'admin_address' => $admin_address);

        if ($form_action == 'update') {
            if (empty($admin_password)) {
                unset($admin_main['admin_password']);
            }

//			$ucresult = uc_user_edit($_REQUEST['admin_username'], '', '', $admin_email, 1);
//			if($ucresult == -1) {
//				$this->common->msg('旧密码不正确', 1);
//			} elseif($ucresult == -4) {
//				$this->common->msg('Email 格式有误', 1);
//			} elseif($ucresult == -5) {
//				$this->common->msg('Email 不允许注册', 1);
//			} elseif($ucresult == -6) {
//				$this->common->msg('该 Email 已经被注册', 1);
//			}
            $this->common->config('admin_edit');

            //只有管理员才可以修改 微信openid
            if ($_COOKIE['l_admin_action'] == 'all') {
                $admin_main['openid'] = $openid;
            }

            $this->db->update($this->db->dbprefix . "admin", $admin_main, array('admin_id' => $admin_id));
            $this->db->update($this->db->dbprefix . "admin_info", $admin_info, array('admin_id' => $admin_id));

            /**
             * 推送数据到数据中心
             * **/
            // $send_data = array();
            // $send_data_t = array();
            // $send_data_t['admin_id'] = $admin_id;

            // $ireport_admin_id =0;
            // $ireport_admin_data = $this->common->getAll("SELECT ireport_admin_id,admin_password FROM " . $this->common->table("admin") . " WHERE admin_id = '" . $admin_id . "'");
            // if(count($ireport_admin_data) > 0){
            // 	$ireport_admin_id =$ireport_admin_data[0]['ireport_admin_id'];
            // 	if(empty($admin_password))
            // 	{
            // 		$send_data_t['admin_password'] =$ireport_admin_data[0]['admin_password'];
            // 	}else{
            // 		$send_data_t['admin_password'] =$admin_main['admin_password'];
            // 	}
            // 	$send_data_t['ireport_admin_id'] =$ireport_admin_id;
            // 	$send_data_t['admin_name'] = $admin_main['admin_name'];
            // 	$send_data_t['admin_username'] =$admin_main['admin_username'];
            // 	if($is_pass == 1){
            // 		$send_data_t['is_pass'] = 0;//0 可以登录 1不可以登录
            // 	}else{
            // 		$send_data_t['is_pass'] = 1;//0 可以登录 1不可以登录
            // 	}
            // 	$send_data[] =$send_data_t;
            // 	//var_dump($send_data);exit;
            // 	//$this->sycn_user_data_to_ireport($send_data);

            //              //剔除仁爱数据推送
            //              $rank_hos_ids = $this->common->getAll("SELECT hos_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $rank_id);
            //              $rank_hos_ids_row = array();
            //              foreach ($rank_hos_ids as $key => $value) {
            //                  $rank_hos_ids_row[] =$value['hos_id'];
            //              }
            //              //var_dump($rank_hos_ids_row);

            //              if (!in_array('1', $rank_hos_ids_row)) {
            //                  $this->sycn_user_data_to_ireport($send_data);
            //              }


            // }

            /***
             * if(!empty($admin_name) && !empty($admin_id)){
             * //修改预约客服名字会影响到预约单的预约客服名字变化
             * $admin_name_old = $this->common->getOne("SELECT count(admin_name) as counts FROM " . $this->common->table("order") . " WHERE admin_id = ".$admin_id." and admin_name != '".$admin_name."'");
             * if(!empty($admin_name_old))
             * {
             * $this->db->update($this->common->table("order"), array('admin_name' => $admin_name), array('admin_id' => $admin_id,'admin_name' => trim($_REQUEST['admin_name_hidden'])));
             * }
             * $admin_name_old = $this->common->getOne("SELECT count(admin_name) as counts FROM " . $this->common->table("gonghai_order") . " WHERE admin_id = ".$admin_id." and admin_name != '".$admin_name."'");
             * if(!empty($admin_name_old))
             * {
             * $this->db->update($this->common->table("gonghai_order"), array('admin_name' => $admin_name), array('admin_id' => $admin_id,'admin_name' =>  trim($_REQUEST['admin_name_hidden'])));
             * }
             * }
             ***/


        } else {
            if (empty($admin_password)) {
                $this->common->msg($this->lang->line('no_empty'), 1);
            }
//
//			$uid = uc_user_register($_REQUEST['admin_username'], $_REQUEST['admin_password'], $admin_info['admin_email']);
//			if($uid <= 0) {
//				if($uid == -1) {
//					$msg = '用户名不合法';
//				} elseif($uid == -2) {
//					$msg = '包含要允许注册的词语';
//				} elseif($uid == -3) {
//					$msg = '用户名已经存在';
//				} elseif($uid == -4) {
//					$msg = 'Email 格式有误';
//				} elseif($uid == -5) {
//					$msg = 'Email 不允许注册';
//				} elseif($uid == -6) {
//					$msg = '该 Email 已经被注册';
//				} else {
//					$msg = '未定义';
//				}
//				$this->common->msg($msg, 1);
//				exit();
//			}
            $this->common->config('admin_add');
            $this->db->insert($this->common->table("admin"), $admin_main);
            $admin_id = $this->db->insert_id();

            $admin_info['admin_id'] = $admin_id;
            $this->db->insert($this->common->table("admin_info"), $admin_info);

            /**
             * 推送数据到数据中心
             * **/
            // $send_array = array();
            // $send_data = array();
            // $send_data['admin_id'] = $admin_id;
            // $send_data['ireport_admin_id'] =0;
            // $send_data['admin_name'] = $admin_main['admin_name'];
            // $send_data['admin_username'] =$admin_main['admin_username'];
            // $send_data['admin_password'] =$admin_main['admin_password'];
            // $send_data['is_pass'] = 0;//0 可以登录 1不可以登录
            // $send_array[] =$send_data;
            // //$this->sycn_user_data_to_ireport($send_array);


            //          //剔除仁爱数据推送
            //          $rank_hos_ids = $this->common->getAll("SELECT hos_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $rank_id);
            //          $rank_hos_ids_row = array();
            //          foreach ($rank_hos_ids as $key => $value) {
            //              $rank_hos_ids_row[] =$value['hos_id'];
            //          }
            //          //var_dump($rank_hos_ids_row);

            //          if (!in_array('1', $rank_hos_ids_row)) {
            //              $this->sycn_user_data_to_ireport($send_data);
            //          }


        }

        $rank_type = $this->common->getOne("SELECT rank_type FROM " . $this->common->table("rank") . " WHERE rank_id = $rank_id");
        if ($rank_type == 2) {
            $arr = array('asker_qiao_name' => $asker_qiao_name,
                'asker_swt_name' => $asker_swt_name);
            $asker_id = $this->common->getOne("SELECT admin_id FROM " . $this->common->table("asker") . " WHERE admin_id = $admin_id");
            if ($asker_id) {
                $this->db->update($this->common->table("asker"), $arr, array("admin_id" => $admin_id));
            } else {
                $arr['admin_id'] = $admin_id;
                $this->db->insert($this->common->table("asker"), $arr);
            }
        }

        $links[0] = array('href' => '?c=index&m=admin_info&admin_id=' . $admin_id, 'text' => $this->lang->line('edit_back'));
        $links[1] = array('href' => '?c=index&m=admin_info', 'text' => $this->lang->line('add_back'));
        $links[2] = array('href' => '?c=index&m=admin_list', 'text' => $this->lang->line('list_back'));
        $this->common->msg($this->lang->line('success'), 0, $links);
    }

    public function admin_action()
    {
        $data = array();
        $data = $this->common->config('admin_action');

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('admin_action', $data);
    }

    public function admin_action_info()
    {
        $act_id = empty($_REQUEST['act_id']) ? 0 : intval($_REQUEST['act_id']);
        if (empty($act_id)) {
            $data = array();
            $data = $this->common->config('admin_action_add');
        } else {
            $data = array();
            $data = $this->common->config('admin_action_edit');

            $info = $this->model->action_info($act_id);
            $data['info'] = $info;
        }

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('admin_action_info', $data);
    }

    public function admin_action_update()
    {
        $form_action = $_REQUEST['form_action'];
        $act_id = isset($_REQUEST['act_id']) ? intval($_REQUEST['act_id']) : 0;
        $act_action = trim($_REQUEST['act_action']);
        $parent_id = intval($_REQUEST['parent_id']);
        $act_url = trim($_REQUEST['act_url']);
        $is_show = intval($_REQUEST['is_show']);
        $act_order = trim($_REQUEST['act_order']);
        $act_name = trim($_REQUEST['act_name']);

        $arr = array('act_name' => $act_name,
            'act_action' => $act_action,
            'act_url' => $act_url,
            'parent_id' => $parent_id,
            'is_show' => $is_show,
            'act_order' => $act_order);

        if (empty($act_name) || empty($act_action)) {
            $this->common->msg($this->lang->line('no_empty'), 1);
        }
        if ($form_action == 'update') {
            $this->common->config('admin_action_edit');
            $this->db->update($this->db->dbprefix . "action", $arr, array('act_id' => $act_id));
        } else {
            $this->common->config('admin_action_add');
            $this->db->insert($this->db->dbprefix . "action", $arr);
            $act_id = $this->db->insert_id();
        }
        clear_static_cache('menu_arr');
        clear_static_cache('menu');
        $links[0] = array('href' => '?c=index&m=admin_action_info&act_id=' . $act_id, 'text' => $this->lang->line('edit_back'));
        $links[1] = array('href' => '?c=index&m=admin_action_info', 'text' => $this->lang->line('add_back'));
        $links[2] = array('href' => '?c=index&m=admin_action', 'text' => $this->lang->line('list_back'));
        $this->common->msg($this->lang->line('success'), 0, $links);
    }

    public function admin_action_del()
    {
        $this->common->config('admin_action_del');
        $act_id = isset($_REQUEST['act_id']) ? intval($_REQUEST['act_id']) : 0;

        $sql = "SELECT COUNT(*) AS c FROM " . $this->common->table('action') . " WHERE parent_id = $act_id";
        $query = $this->db->query($sql);
        $arr = $query->result_array();

        if ($arr[0]['c']) {
            $data = $this->common->msg($this->lang->line('have_son'), 1, array(), false);
        } else {
            $this->db->delete($this->db->dbprefix . "action", array('act_id' => $act_id));
            $links[0] = array('href' => '?c=index&m=admin_action', 'text' => $this->lang->line('list_back'));
            $data = $this->common->msg($this->lang->line('success'), 0, $links, array(), false);
            clear_static_cache('menu');
            clear_static_cache('menu_arr');
        }
        $this->load->view('msg', $data);
    }

    public function rank_list()
    {
        $data = array();
        $data = $this->common->config('rank_list');

        $rank = $this->common->static_cache('read', "rank", 'rank');
        $format_str = '<tr id="menu_%s"><td>%s %s &nbsp;<img src="static/img/users.png" id="user_show_%s" onclick="users_list_ajax(%s);" style="width:16px;"></td><td>%s</td><td><button class="btn btn-primary" onClick="go_url(\'?c=index&m=rank_info&rank_id=%s\')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del(\'?c=index&m=rank_del&rank_id=%s\')"><i class="icon-trash"></i></button>&nbsp;</td></tr>';
        $rank_list = options($rank, 'rank_id', 'rank_name', 'child', '<span class="td_child"></span>', '', 'rank_list', $format_str);
        $data['rank_list'] = $rank_list;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('rank_list', $data);
    }

    public function user_list_ajax()
    {
        $rank_id = isset($_REQUEST['rank_id']) ? intval($_REQUEST['rank_id']) : 0;
        $sql = "select admin_name,admin_id,admin_nowtime from " . $this->common->table('admin') . " where is_pass=1 AND rank_id=" . $rank_id;
        $sql1 = "select count(*) from " . $this->common->table('admin') . " where is_pass=1 AND rank_id=" . $rank_id;
        $data = array();
        $res = $this->common->getAll($sql);
        $num = $this->common->getOne($sql1);
        $data['num'] = $num;
        $data['user_list'] = $res;
        $data = json_encode($data);

        echo $data;

    }

    //同步更新同岗位下所有成员的权限，使所有权限同步 代码开始
    public function update_user_rank()
    {
        $rank_id = isset($_REQUEST['rank_id']) ? intval($_REQUEST['rank_id']) : 0;


        $this->db->trans_start();
        $sql = "update " . $this->common->table('admin') . " a," . $this->common->table('rank') . " r set a.admin_action=r.rank_action where a.rank_id=r.rank_id and r.rank_id=" . $rank_id;
        $rs = $this->db->query($sql);
        $this->db->trans_complete();
        if ($rs) {
            $links[0] = array('href' => '?c=index&m=rank_info&rank_id=' . $rank_id, 'text' => $this->lang->line('edit_back'));
            $links[1] = array('href' => '?c=index&m=rank_info', 'text' => $this->lang->line('add_back'));
            $links[2] = array('href' => '?c=index&m=rank_list', 'text' => $this->lang->line('list_back'));
            $this->common->msg($this->lang->line('success'), 0, $links);

        } else {

            $this->common->msg('同步失败', 0);
        }


    }

    //同步更新同岗位下所有成员的权限，使所有权限同步 代码结束
    public function rank_info()
    {
        $rank_id = isset($_REQUEST['rank_id']) ? intval($_REQUEST['rank_id']) : 0;
        $data = array();
        $rank = $this->common->static_cache('read', "rank", 'rank');
        if (empty($rank_id)) {
            $data = $this->common->config('rank_add');
            $rank_option = options($rank, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '');
        } else {
            $data = $this->common->config('rank_edit');
            $info = $this->common->getRow("SELECT * FROM " . $this->common->table('rank') . " WHERE rank_id = $rank_id");
            $info['rank_action'] = explode(",", $info['rank_action']);
            $data['info'] = $info;
            $rank_option = options($rank, 'rank_id', 'rank_name', 'child', "&nbsp;&nbsp;&nbsp;&nbsp;", '', 'option', '', $info['parent_id']);
            $rank_power = $this->model->rank_hos($rank_id);
            $data['rank_power'] = $rank_power;
        }

        $hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where ask_auth != 1  ORDER BY CONVERT(hos_name USING gbk) asc");
        $data['hospital'] = $hospital;

        $data['user'] = $this->common->getAll("SELECT admin_id,admin_name,admin_username FROM " . $this->common->table('admin') . " where is_pass = 1  ORDER BY CONVERT(admin_name USING gbk) asc");

        $data['rank_option'] = $rank_option;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('rank_info', $data);
    }

    public function rank_update()
    {
        $form_action = $_REQUEST['form_action'];
        $rank_id = isset($_REQUEST['rank_id']) ? intval($_REQUEST['rank_id']) : 0;
        $rank_name = trim($_REQUEST['rank_name']);
        $parent_id = intval($_REQUEST['parent_id']);
        $hos_id = isset($_REQUEST['hos_id']) ? $_REQUEST['hos_id'] : array();
        $keshi_id = isset($_REQUEST['keshi_id']) ? $_REQUEST['keshi_id'] : array();
        $rank_type = intval($_REQUEST['rank_type']);
        $rank_action = isset($_REQUEST['rank_action']) ? $_REQUEST['rank_action'] : array();
        $rank_order = intval($_REQUEST['rank_order']);
        $is_limit = intval($_REQUEST['is_limit']);
        $rank_action = implode(",", $rank_action);
        //获取用户与岗位关联关系
        $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : array();
        if (!empty($user)) {
            $user = "'" . implode("','", $user) . "'";
        } else {
            $user = '';
        }
        $rank_level = 1;

        if (!empty($parent_id)) {
            $query = $this->db->query("SELECT rank_level FROM " . $this->db->dbprefix . "rank WHERE rank_id = $parent_id");
            $rank_level = $query->result_array();
            $rank_level = $rank_level[0]['rank_level'] + 1;
        }

        $data = array();
        $arr = array('rank_name' => $rank_name,
            'parent_id' => $parent_id,
            'rank_type' => $rank_type,
            'rank_action' => $rank_action,
            'rank_level' => $rank_level,
            'rank_order' => $rank_order,
            'rank_user' => $user,
            'is_limit' => $is_limit
        );

        if (empty($rank_name) || empty($rank_action)) {
            $this->common->msg($this->lang->line('no_empty'), 1);
        }

        if ($form_action == 'update') {
            $this->common->config('rank_edit');
            $this->db->update($this->db->dbprefix . "rank", $arr, array('rank_id' => $rank_id));
        } else {
            $this->common->config('rank_add');
            $this->db->insert($this->db->dbprefix . "rank", $arr);
            $rank_id = $this->db->insert_id();
        }

        $this->common->static_cache('delete', "rank");
        $this->common->static_cache('delete', "rank_arr");
        $this->db->delete($this->common->table('rank_power'), array('rank_id' => $rank_id));
        $hos_keshi = array();
        foreach ($hos_id as $key => $val) {
            if (!empty($val)) {
                $hos_keshi[$val] = isset($hos_keshi[$val]) ? $hos_keshi[$val] : array();
                if (!in_array($keshi_id[$key], $hos_keshi[$val])) {
                    $hos_keshi[$val][] = $keshi_id[$key];
                }
            }
        }

        foreach ($hos_keshi as $key => $val) {
            foreach ($val as $k => $v) {
                $this->db->insert($this->common->table('rank_power'), array('rank_id' => $rank_id, 'hos_id' => $key, 'keshi_id' => $v));
            }
        }

        $links[0] = array('href' => '?c=index&m=rank_info&rank_id=' . $rank_id, 'text' => $this->lang->line('edit_back'));
        $links[1] = array('href' => '?c=index&m=rank_info', 'text' => $this->lang->line('add_back'));
        $links[2] = array('href' => '?c=index&m=rank_list', 'text' => $this->lang->line('list_back'));
        $this->common->msg($this->lang->line('success'), 0, $links);
    }

    function rank_del()
    {
        $data = $this->common->config('rank_del');
        $rank_id = isset($_REQUEST['rank_id']) ? intval($_REQUEST['rank_id']) : 0;
        if (empty($rank_id)) {
            $this->common->msg($this->lang->line('no_empty'), 1);
        }

        $have_son = $this->common->getOne("SELECT rank_id FROM " . $this->common->table('rank') . " WHERE parent_id = $rank_id");
        if ($have_son) {
            $data = $this->common->msg($this->lang->line('have_son'), 1, array(), false);
        } else {
            $this->db->delete($this->common->table("rank"), array('rank_id' => $rank_id));
            $links[0] = array('href' => '?c=index&m=rank_list', 'text' => $this->lang->line('list_back'));
            $data = $this->common->msg($this->lang->line('success'), 0, $links, false);
            $this->common->static_cache('delete', "rank");
            $this->common->static_cache('delete', "rank_arr");
        }
        $this->load->view('msg', $data);
    }

    public function login()
    {

        //屏蔽杭州地区访问
        $ips = ips();
        // 利用淘宝接口根据ip查询所在区域信息
        //echo $ips;
        $resArea = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip={$ips}");
        $resArea = json_decode($resArea, true);
        //var_dump($resArea['data']['city']);
        if ($resArea['data']['city'] == '杭州') {
            show_404();
        }


        $data = array();
        $data = $this->common->config('login');
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : '';
        $ip = getip();
        $ip_arr = array('202.104.113.105', '127.0.0.1');
        if ($type == 'weixin') //if(!in_array($ip, $ip_arr))
        {
            if (isset($_COOKIE['data_id']) && $_COOKIE['data_id'] > 0) {
                $id = $_COOKIE['data_id'];
            } else {
                $id = $this->common->getOne("SELECT id FROM hui_weixin_login ORDER BY time DESC LIMIT 0, 1");
                $id = $id + 1;
            }
            $id = ($id >= 9998) ? 1 : $id;
            $id_havd = $this->common->getOne("SELECT id FROM hui_weixin_login WHERE id = $id LIMIT 0, 1");
            if (!empty($id_havd)) {
                $this->db->query("UPDATE hui_weixin_login SET ip = '" . $ip . "', time = " . time() . " WHERE id = $id");
            } else {
                $this->db->insert("hui_weixin_login", array('id' => $id, 'ip' => $ip, 'time' => time()));
            }
            setcookie('data_id', $id, (time() + 300));

            $data['id'] = $id;
            /*登录界面*/
            $this->load->view('login_weixin', $data);
        } else {
            if (isset($_COOKIE['l_admin_id']) && $_COOKIE['l_admin_id'] > 0) {
                header("location: ./\r\n");
                exit();
            }

            /*登录界面*/
            $this->load->view('login', $data);
        }
    }

    public function get_weixin_ajax()
    {
        $ip = getip();
        $id = $this->common->getOne("SELECT id FROM hui_weixin_login ORDER BY time DESC LIMIT 0, 1");
        $id = $id + 1;
        $id = ($id >= 9998) ? 1 : $id;
        $id_havd = $this->common->getOne("SELECT id FROM hui_weixin_login WHERE id = $id LIMIT 0, 1");
        if (!empty($id_havd)) {
            $this->db->query("UPDATE hui_weixin_login SET ip = '" . $ip . "', time = " . time() . " WHERE id = $id");
        } else {
            $this->db->insert("hui_weixin_login", array('id' => $id, 'ip' => $ip, 'time' => time()));
        }
        echo $id;
    }

    public function get_weixin_username()
    {
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id == 0) {
            exit();
        }
        $openid = $this->common->getOne("SELECT openid FROM hui_weixin_login WHERE id = $id AND time > " . (time() - 300));
        if (!empty($openid)) {
            $username = $this->common->getOne("SELECT phone FROM " . $this->common->table('wx_user') . " WHERE openid = '" . $openid . "'");
            echo $username;
        }
    }

    public function weixin_login()
    {
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id == 0) {
            exit();
        }

        $openid = $this->common->getOne("SELECT openid FROM hui_weixin_login WHERE id = $id AND time > " . (time() - 300));
        if (!empty($openid)) {
            $row = $this->common->getRow("SELECT * FROM " . $this->common->table('admin') . " WHERE admin_openid = '" . $openid . "'");
            $cookie_time = 0;
            $hos_keshi = $this->common->getAll("SELECT hos_id, keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $row['rank_id']);
            $hos_str = '';
            $keshi_str = '';
            if (!empty($hos_keshi)) {
                foreach ($hos_keshi as $val) {
                    $hos_str .= $val['hos_id'] . ",";
                    $keshi_str .= $val['keshi_id'] . ",";
                }
                $hos_str = substr($hos_str, 0, -1);
                $keshi_str = substr($keshi_str, 0, -1);
            }
            setcookie('l_admin_id', $row['admin_id'], $cookie_time, "/");
            setcookie('l_admin_username', $row['admin_username'], $cookie_time, "/");
            setcookie('l_admin_name', $row['admin_name'], $cookie_time, "/");
            setcookie('l_rank_id', $row['rank_id'], $cookie_time, "/");
            setcookie('l_admin_action', $row['admin_action'], $cookie_time, "/");
            setcookie('l_hos_id', $hos_str, $cookie_time, "/");
            setcookie('l_keshi_id', $keshi_str, $cookie_time, "/");
            $arr = array('admin_lasttime' => $row['admin_nowtime'],
                'admin_nowtime' => time(),
                'admin_logintimes' => $row['admin_logintimes'] + 1,
                'admin_lastip' => $row['admin_nowip'],
                'admin_nowip' => getip(),
                'log_errs' => 0);
            $this->db->update($this->common->table('admin'), $arr, array('admin_id' => $row['admin_id']));
            $this->db->delete($this->common->table('weixin_login'), array('id' => $id));
            echo 1;
        }
    }

    public function weixin_login_ck()
    {
        include './api/config.php';
        include './api/uc_client/client.php';
        $admin_password = $this->common->getOne("SELECT admin_password FROM " . $this->common->table("admin") . " WHERE admin_id = " . $_COOKIE['l_admin_id']);
        list($uid, $username, $password, $email) = uc_user_login($_COOKIE['l_admin_username'], $admin_password);
        if ($uid > 0) {
            //header("location:./\r\n");
            //用户登陆成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
            setcookie('Example_auth', uc_authcode($uid . "\t" . $username, 'ENCODE'));
            //生成同步登录的代码
            $ucsynlogin = uc_user_synlogin($uid);
            $data = '登录成功' . $ucsynlogin;

            $this->common->sys_log($_COOKIE['l_admin_id'], $_COOKIE['l_admin_name'], 'login', array('desc' => '微信登录'));
        }
        $links[0] = array('href' => './', 'text' => $this->lang->line('home'));
        $this->common->msg($data, 0, $links);
    }

    public function logout()
    {
        /* 清空登陆的cookie */
        $cookie_time = time() - 3600;
        setcookie('l_admin_id', "", $cookie_time, "/");
        setcookie('l_admin_username', "", $cookie_time, "/");
        setcookie('l_admin_name', "", $cookie_time, "/");
        setcookie('l_rank_id', "", $cookie_time, "/");
        setcookie('l_admin_action', "", $cookie_time, "/");
        setcookie('l_hos_id', "", $cookie_time, "/");
        setcookie('l_keshi_id', "", $cookie_time, "/");
        setcookie('l_key', "", $cookie_time, "/");
        if (isset($_COOKIE['l_admin_id'])) {
            $this->common->sys_log($_COOKIE['l_admin_id'], $_COOKIE['l_admin_name'], 'logout');
        }

        // include './api/config.php';
        // include './api/uc_client/client.php';
        // $ucsynlogout = uc_user_synlogout();

        $msg = "退出成功" . $ucsynlogout;
        $links[0] = array('href' => '?c=index&m=login', 'text' => '返回登陆页面');
        $this->common->msg($msg, 0, $links);
    }


    public function login_ck()
    {
        /*登录验证*/
        $username = isset($_REQUEST['admin_username']) ? trim($_REQUEST['admin_username']) : '';
        $password = isset($_REQUEST['admin_password']) ? trim($_REQUEST['admin_password']) : '';
        $remember = isset($_REQUEST['remember']) ? intval($_REQUEST['remember']) : 0;

        if (empty($username)) {
            $this->common->msg($this->lang->line('username_empty'), 1);
        } elseif (empty($password)) {
            $this->common->msg($this->lang->line('password_empty'), 1);
        } else {
            $sql = "SELECT admin_id, admin_username, admin_password, admin_name, rank_id, admin_action, admin_nowtime, admin_logintimes, admin_nowip, log_errs FROM " . $this->common->table('admin') . " WHERE admin_username = '$username' AND is_pass = 1";
            $row = $this->common->getRow($sql);
            if (empty($row)) {
                $this->common->msg($this->lang->line('username_error'), 1);
            } else {
                //  检查是否在24小时内错误5次
//				if(($row['log_errs'] >= 5) and (($row['admin_nowtime'] + 86400) >= time()))
//				{
//					$this->common->msg("账号登陆错误次数太多，请明天再来！", 1);
//					exit();
//				}

                $password = md5($password);
                //$password==$row['admin_password'];
                if ($password == $row['admin_password']) {
                    if ($remember == 1) {
                        $cookie_time = time() + (365 * 24 * 60 * 60);
                    } else {
                        $cookie_time = 0;
                    }

                    $hos_keshi = $this->common->getAll("SELECT hos_id, keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $row['rank_id']);
                    $hos_str = '';
                    $keshi_str = '';
                    if (!empty($hos_keshi)) {
                        foreach ($hos_keshi as $val) {
                            $hos_str .= $val['hos_id'] . ",";
                            $keshi_str .= $val['keshi_id'] . ",";
                        }
                        $hos_str = substr($hos_str, 0, -1);
                        $keshi_str = substr($keshi_str, 0, -1);
                    }
                    // 每次登录更新一下key
                    $key = sha1(time() . rand());
                    setcookie('l_admin_id', $row['admin_id'], $cookie_time, "/");

                    $arr = array('admin_lasttime' => $row['admin_nowtime'],
                        'admin_nowtime' => time(),
                        'admin_logintimes' => $row['admin_logintimes'] + 1,
                        'admin_lastip' => $row['admin_nowip'],
                        'admin_nowip' => getip(),
                        'log_errs' => 0,
                        'token' => $key
                    );

                    //var_dump($row);
                    //var_dump($arr);
                    $this->common->sys_log($row['admin_id'], $row['admin_name'], 'login');
                    $this->db->where('admin_id', $row['admin_id']);
                    $this->db->update($this->common->table('admin'), $arr);

                    //echo $this->db->last_query();exit;

                    $sql = "SELECT token FROM " . $this->common->table('admin') . " WHERE admin_id = " . $row['admin_id'];
                    $row_token = $this->common->getAll($sql);
                    //echo $sql;
                    //var_dump($row);
                    //exit;

//						$this->common->sys_log($row['admin_id'], $row['admin_name'], 'login');
                    if ($_REQUEST['admin_password'] == '123456') {
                        $links[0] = array('href' => '?c=index&m=edit_pass', 'text' => '修改个人信息');
                        $this->common->msg(" 密码简单，请修改密码后再登录！", 0, $links);
                        exit();
                    } else {

                        //查询岗位类型
                        $rank_type = $this->common->getAll("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = " . $row['rank_id']);
                        setcookie('l_rank_type', @$rank_type[0]['rank_type'], $cookie_time, "/");
                        setcookie('l_admin_username', $row['admin_username'], $cookie_time, "/");
                        setcookie('l_admin_name', $row['admin_name'], $cookie_time, "/");
                        setcookie('l_rank_id', $row['rank_id'], $cookie_time, "/");
                        setcookie('l_admin_action', $row['admin_action'], $cookie_time, "/");
                        setcookie('l_hos_id', $hos_str, $cookie_time, "/");
                        setcookie('l_keshi_id', $keshi_str, $cookie_time, "/");
                        setcookie('l_key', $row_token[0]['token'], $cookie_time, "/");


                        //判断是否导医和医生登录，代码开始
                        $rankid = $row['rank_id'];
                        $rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
                        $rank_type = $rank[$rankid]['rank_type'];

                        if ($rank_type == 3) {
                            setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                            echo "<script>window.location.href = '?c=order&m=daoyi_index';</script>";
                        } elseif ($rank_type == 1) {
                            setcookie('l_login_weixin_check', 1, $cookie_time, "/");

                            //厦门医生查询以前到诊数据 ADD 2018-6-4
                            $sql_hos = "SELECT rank_id FROM " . $this->common->table('rank_power') . " WHERE hos_id in(45,46)";
                            $row_hos = $this->common->getAll($sql_hos);
                            $new_row_hos = array();
                            foreach ($row_hos as $key => $value) {
                                $new_row_hos[] = $value['rank_id'];
                            }
                            $new_row_hos_array = array_unique($new_row_hos);
                            asort($new_row_hos_array);

                            $sql_rank = "SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = {$row['rank_id']}";
                            $row_rank = $this->common->getRow($sql_rank);
                            if (in_array($row['rank_id'], $new_row_hos_array)) {
                                header("location:?c=order&m=docter_index_xiamen");
                                exit();
                            } else {
                                header("location:?c=order&m=docter_index");
                                exit();
                            }

                        } elseif ($rank_type == 5) {
                            setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                            echo "<script>window.location.href = '?c=order&m=zmt_index';</script>";
                        } else {
                            setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                            echo "<script>window.location.href = '?c=index&m=index';</script>";
                        }
                        //判断是否导医和医生登录，代码结束
                        exit();
                    }

                } else {
                    if ($row['log_errs'] >= 1) {
                        $this->db->query("UPDATE " . $this->common->table('admin') . " SET log_errs = log_errs + 1 WHERE admin_id = " . $row['admin_id']);
                    } else {
                        $this->db->query("UPDATE " . $this->common->table('admin') . " SET log_errs = 1, admin_nowtime = " . time() . " WHERE admin_id = " . $row['admin_id']);
                    }
                    $this->common->msg($this->lang->line('password_error'), 1);
                }
            }
        }


    }

    public function ajax_rank_action()
    {
        $rank_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($rank_id <= 0) {
            $this->common->msg($this->lang->line('no_empty'), 1);
        }

        $action = $this->common->getRow("SELECT rank_action, rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = $rank_id");

        echo json_encode($action);
    }

    public function edit_pass()
    {

        $this->load->view('edit_pass');
    }

    public function change_pass()
    {
        $admin_id = isset($_REQUEST['admin_id']) ? trim($_REQUEST['admin_id']) : '';
        $admin_password = isset($_REQUEST['admin_password']) ? trim($_REQUEST['admin_password']) : '';
        $re_password = isset($_REQUEST['re_password']) ? trim($_REQUEST['re_password']) : '';
        $confirm_password = isset($_REQUEST['confirm_password']) ? trim($_REQUEST['confirm_password']) : '';
        $admin_password = md5($admin_password);

        if (empty($re_password) || empty($confirm_password) || empty($admin_password)) {
            $this->common->msg($this->lang->line('password_check'), 1);
        }


        if ($re_password !== $confirm_password) {
            $this->common->msg($this->lang->line('confirm_password_check'), 1);
        }

        $info = $this->common->getRow("SELECT admin_username, admin_password FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");
        $password = $info['admin_password'];
        if ($admin_password !== $password) {
            $this->common->msg($this->lang->line('before_password'), 1);
        }

        include './api/config.php';
        include './api/uc_client/client.php';
        $ucresult = uc_user_edit($info['admin_username'], $_REQUEST['admin_password'], $_REQUEST['re_password'], '');
        if ($ucresult == -1) {
            $this->common->msg('旧密码不正确', 1);
        } elseif ($ucresult == -4) {
            $this->common->msg('Email 格式有误', 1);
        } elseif ($ucresult == -5) {
            $this->common->msg('Email 不允许注册', 1);
        } elseif ($ucresult == -6) {
            $this->common->msg('该 Email 已经被注册', 1);
        }

        $this->db->update($this->common->table("admin"), array('admin_password' => md5($re_password)), array('admin_id' => $_COOKIE['l_admin_id']));
        $cookie_time = time() - 3600;
        setcookie('l_admin_id', "", $cookie_time, "/");
        setcookie('l_admin_username', "", $cookie_time, "/");
        setcookie('l_admin_name', "", $cookie_time, "/");
        setcookie('l_rank_id', "", $cookie_time, "/");
        setcookie('l_admin_action', "", $cookie_time, "/");
        setcookie('l_hos_id', "", $cookie_time, "/");
        setcookie('l_keshi_id', "", $cookie_time, "/");
        setcookie('l_key', "", $cookie_time, "/");
        $links[0] = array('href' => '?c=index&m=login', 'text' => '返回登录界面');
        $data = $this->common->msg($this->lang->line('success'), 0, $links);


    }

    public function my_profile()
    {
        $data = array();
        $data = $this->common->config('my_profile');

        $admin_id = $_COOKIE['l_admin_id'];
        $info = $this->model->admin_info($admin_id);
        if ($info['admin_sex'] == 1) {
            $info['admin_sex'] = $this->lang->line('sex_man');
        } else {
            $info['admin_sex'] = $this->lang->line('sex_woman');
        }
        $info['admin_birthday'] = date("Y-m-d", $info['admin_birthday']);
        $info['admin_action'] = explode(",", $info['admin_action']);
        $data['info'] = $info;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('my_profile', $data);
    }

    //导医系统专用修改个人信息页面 代码开始
    public function dy_my_profile()
    {
        $data = array();
        $data = $this->common->config('my_profile');

        $admin_id = $_COOKIE['l_admin_id'];
        $info = $this->model->admin_info($admin_id);
        if ($info['admin_sex'] == 1) {
            $info['admin_sex'] = $this->lang->line('sex_man');
        } else {
            $info['admin_sex'] = $this->lang->line('sex_woman');
        }
        $info['admin_birthday'] = date("Y-m-d", $info['admin_birthday']);
        $info['admin_action'] = explode(",", $info['admin_action']);
        $data['info'] = $info;

        $this->load->view('daoyi/my_profile', $data);
    }

    //导医系统专用修改个人信息页面 代码结束
    public function docter_my_profile()
    {
        $data = array();
        $data = $this->common->config('my_profile');

        $admin_id = $_COOKIE['l_admin_id'];
        $info = $this->model->admin_info($admin_id);
        if ($info['admin_sex'] == 1) {
            $info['admin_sex'] = $this->lang->line('sex_man');
        } else {
            $info['admin_sex'] = $this->lang->line('sex_woman');
        }
        $info['admin_birthday'] = date("Y-m-d", $info['admin_birthday']);
        $info['admin_action'] = explode(",", $info['admin_action']);
        $data['info'] = $info;

        $this->load->view('docter/my_profile', $data);
    }

    //产品专用修改个人信息页面 代码结束
    public function pro_my_profile()
    {
        $data = array();
        $data = $this->common->config('my_profile');
        $admin_id = $_COOKIE['l_admin_id'];
        $info = $this->model->admin_info($admin_id);
        if ($info['admin_sex'] == 1) {
            $info['admin_sex'] = $this->lang->line('sex_man');
        } else {
            $info['admin_sex'] = $this->lang->line('sex_woman');
        }
        $info['admin_birthday'] = date("Y-m-d", $info['admin_birthday']);
        $info['admin_action'] = explode(",", $info['admin_action']);
        $data['info'] = $info;
        $this->load->view('pro/my_profile', $data);
    }

    //导医系统专用修改个人信息页面 代码结束
    public function my_setting()
    {
        $data = array();
        $data = $this->common->config('my_setting');
        $admin_id = $_COOKIE['l_admin_id'];
        $rank_id = $_COOKIE['l_rank_id'];
        $rank_type = $this->common->getOne("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = $rank_id");
        if ($rank_type == 2) {
            $asker = $this->common->getRow("SELECT * FROM " . $this->common->table('asker') . " WHERE admin_id = $admin_id");
            $data['asker'] = $asker;
        }
        $openid = $this->common->getOne("SELECT admin_openid FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");
        $data['admin_id'] = $admin_id;
        $data['openid'] = $openid;
        $data['rank_type'] = $rank_type;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('my_setting', $data);
    }

    public function pass_check()
    {
        $data = array();
        $data = $this->common->config('my_profile');

        $admin_id = isset($_REQUEST['admin_id']) ? intval($_REQUEST['admin_id']) : 0;
        $admin_password = isset($_REQUEST['admin_password']) ? trim($_REQUEST['admin_password']) : '';
        $re_password = isset($_REQUEST['re_password']) ? trim($_REQUEST['re_password']) : '';
        $confirm_password = isset($_REQUEST['confirm_password']) ? trim($_REQUEST['confirm_password']) : '';
        $admin_password = md5($admin_password);

        if (empty($re_password) || empty($confirm_password) || empty($admin_password)) {
            $this->common->msg($this->lang->line('password_check'), 1);
        }


        if ($re_password !== $confirm_password) {
            $this->common->msg($this->lang->line('confirm_password_check'), 1);
        }

        $info = $this->common->getRow("SELECT admin_username, admin_password FROM " . $this->common->table('admin') . " WHERE admin_id = " . $_COOKIE['l_admin_id']);
        $password = $info['admin_password'];
        if ($admin_password !== $password) {
            $this->common->msg($this->lang->line('before_password'), 0);
        }


        $this->db->update($this->common->table("admin"), array('admin_password' => md5($re_password)), array('admin_id' => $_COOKIE['l_admin_id']));
//		$links[0] = array('href' => '?c=index&m=my_profile', 'text' => $this->lang->line('person_info_back'));
        $data = $this->common->msg($this->lang->line('success'), 0);
        $this->load->view('msg', $data);
    }

    public function calendar()
    {
        $data = array();
        $data = $this->common->config('calendar');

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('calendar', $data);
    }

    public function person_info_check()
    {
        $data = array();
        $data = $this->common->config('my_profile');

        $admin_id = isset($_REQUEST['admin_id']) ? trim($_REQUEST['admin_id']) : '';
        $admin_birthday = isset($_REQUEST['admin_birthday']) ? trim($_REQUEST['admin_birthday']) : '';
        $admin_tel = isset($_REQUEST['admin_tel']) ? trim($_REQUEST['admin_tel']) : '';
        $admin_tel_duan = isset($_REQUEST['admin_tel_duan']) ? trim($_REQUEST['admin_tel_duan']) : '';
        $admin_qq = isset($_REQUEST['admin_qq']) ? trim($_REQUEST['admin_qq']) : '';
        $admin_email = isset($_REQUEST['admin_email']) ? trim($_REQUEST['admin_email']) : '';
        $admin_address = isset($_REQUEST['admin_address']) ? trim($_REQUEST['admin_address']) : '';
        $admin_birthday = gmstr2time($admin_birthday . 'Z');
        $person_info = array('admin_birthday' => $admin_birthday,
            'admin_tel' => $admin_tel,
            'admin_tel_duan' => $admin_tel_duan,
            'admin_qq' => $admin_qq,
            'admin_email' => $admin_email,
            'admin_address' => $admin_address);
        if (empty($admin_birthday) || empty($admin_tel) || empty($admin_qq) || empty($admin_email) || empty($admin_address)) {
            $this->common->msg($this->lang->line('person_info_error'), 1);
        }
        include './api/config.php';
        include './api/uc_client/client.php';
        $ucresult = uc_user_edit($_COOKIE['l_admin_username'], '', '', $_REQUEST['admin_email'], 1);
        if ($ucresult == -1) {
            $this->common->msg('旧密码不正确', 1);
        } elseif ($ucresult == -4) {
            $this->common->msg('Email 格式有误', 1);
        } elseif ($ucresult == -5) {
            $this->common->msg('Email 不允许注册', 1);
        } elseif ($ucresult == -6) {
            $this->common->msg('该 Email 已经被注册', 1);
        }

        $this->db->update($this->common->table("admin_info"), $person_info, array('admin_id' => $admin_id));
//		$links[0] = array('href' => '?c=index&m=my_profile', 'text' => $this->lang->line('person_info_back'));
        $data = $this->common->msg($this->lang->line('success'), 0);
        $this->load->view('msg', $data);
    }

    public function asker_name_ajax()
    {
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';
        $admin_id = isset($_REQUEST['admin_id']) ? intval($_REQUEST['admin_id']) : 0;
        if (empty($name)) {
            echo 0;
            exit();
        }

        $where = 1;
        if ($admin_id) {
            $where .= " AND admin_id != " . $admin_id;
        }

        if ($type == 1) {
            $where .= " AND asker_qiao_name = '" . $name . "'";
        } else if ($type == 2) {
            $where .= " AND asker_swt_name = '" . $name . "'";
        }
        $admin_id = $this->common->getOne("SELECT admin_id FROM " . $this->common->table('asker') . " WHERE $where");
        if ($admin_id) {
            echo 1;
            exit();
        } else {
            echo -1;
            exit();
        }
    }

    public function ip_get_ajax()
    {
        $ip = isset($_REQUEST['ip']) ? trim($_REQUEST['ip']) : '';

        $data = @file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip);
        $content = json_decode($data, true);
        if (!empty($content['data']['region'])) {
            if ($content['code'] == 0) {
                $data = $content['data']['region'] . $content['data']['city'] . "--" . $content['data']['isp'];

            } else {

                $data = "未查询到ip归属信息！";
            }

        } else {
            $data = "未查询到ip归属信息！";
        }
        echo $data;
    }


    //微信扫码登录
    public function login_weixin_scan()
    {
        $this->load->view('login_weixin_scan', array());
    }

    /*
              * 账户绑定
             *
             * **/
    public function login_bd_from_wexin()
    {
        $openid = trim($_REQUEST['openid']);
        $newtime = trim($_REQUEST['newtime']);
        if (empty($openid) || empty($newtime)) {

        } else {
            $zh_id = trim($_REQUEST['zh_id']);
            if (!empty($openid) && !empty($zh_id)) {
                //绑定
                $arr = array('openid' => base64_decode(urldecode($openid)), 'new_apply_sign_time' => base64_decode(urldecode($newtime)));
                $this->db->where('admin_id', $zh_id);
                if ($this->db->update($this->common->table('admin'), $arr)) {
                    echo 1;
                    exit;
                }
            }
        }
        echo 0;
        exit;
    }


    /**
     * 检查是否已经激活登录信息  检测登录状态  当识别到唯一码已经激活系统的某一个账户的时候，自动获取账户信息，然后提示前天轮循的js 请求进入系统。
     * **/
    public function ajax_scan_status()
    {
        $newtime = isset($_REQUEST['newtime']) ? trim($_REQUEST['newtime']) : '';
        if (empty($newtime)) {
            echo 0;
            exit;
        }
        $sql = "SELECT admin_id, admin_username, admin_password, admin_name, rank_id, admin_action, admin_nowtime, admin_logintimes, admin_nowip, log_errs FROM " . $this->common->table('admin') . " WHERE new_apply_sign_time = '" . $newtime . "'";
        $row = $this->common->getRow($sql);
        if ($row) {
            $cookie_time = 0;
            $hos_keshi = $this->common->getAll("SELECT hos_id, keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $row['rank_id']);
            $hos_str = '';
            $keshi_str = '';
            if (!empty($hos_keshi)) {
                foreach ($hos_keshi as $val) {
                    $hos_str .= $val['hos_id'] . ",";
                    $keshi_str .= $val['keshi_id'] . ",";
                }
                $hos_str = substr($hos_str, 0, -1);
                $keshi_str = substr($keshi_str, 0, -1);
            }
            // 每次登录更新一下key
            $key = sha1(time() . rand());
            setcookie('l_admin_id', $row['admin_id'], $cookie_time, "/");

            $arr = array('admin_lasttime' => $row['admin_nowtime'],
                'admin_nowtime' => time(),
                'admin_logintimes' => $row['admin_logintimes'] + 1,
                'admin_lastip' => $row['admin_nowip'],
                'admin_nowip' => getip(),
                'log_errs' => 0,
                'token' => $key
            );

            $this->common->sys_log($row['admin_id'], $row['admin_name'], 'login');
            $this->db->where('admin_id', $row['admin_id']);
            $this->db->update($this->common->table('admin'), $arr);

            $sql = "SELECT token FROM " . $this->common->table('admin') . " WHERE admin_id = " . $row['admin_id'];
            $row_token = $this->common->getAll($sql);

            setcookie('l_admin_username', $row['admin_username'], $cookie_time, "/");
            setcookie('l_admin_name', $row['admin_name'], $cookie_time, "/");
            setcookie('l_rank_id', $row['rank_id'], $cookie_time, "/");
            setcookie('l_admin_action', $row['admin_action'], $cookie_time, "/");
            setcookie('l_hos_id', $hos_str, $cookie_time, "/");
            setcookie('l_keshi_id', $keshi_str, $cookie_time, "/");
            setcookie('l_key', $row_token[0]['token'], $cookie_time, "/");

            //判断是否导医和医生登录，代码开始
            $rankid = $row['rank_id'];
            $rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
            $rank_type = $rank[$rankid]['rank_type'];

            if ($rank_type == 3) {
                setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                echo "?c=order&m=daoyi_index";
            } elseif ($rank_type == 1) {
                setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                echo "?c=order&m=docter_index";
            } elseif ($rank_type == 5) {
                setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                echo "?c=order&m=zmt_index";
            } else {
                setcookie('l_login_weixin_check', 1, $cookie_time, "/");
                echo "?c=index&m=index";
            }
            exit;
        }
        echo 0;
        exit;
    }


    //查询用户
    public function ajax_query_user()
    {
        $code = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
        $zh = isset($_REQUEST['zh']) ? trim($_REQUEST['zh']) : '';
        $pw = isset($_REQUEST['pw']) ? trim($_REQUEST['pw']) : '';
        if (empty($code) || empty($zh) || empty($pw)) {
            echo 0;
            exit;
        }
        session_start();
        if (empty($_SESSION['authnum_session']) || strcmp($_SESSION['authnum_session'], $code) != 0) {
            echo 1;
            exit;
        }

        $zh = base64_decode($zh);
        $pw = base64_decode($pw);
        $sql = "SELECT admin_password,admin_id FROM " . $this->common->table('admin') . " WHERE admin_username = '" . $zh . "'";
        $admin_data = $this->common->getAll($sql);
        if (count($admin_data) > 0) {
            $admin_data_check = 0;
            foreach ($admin_data as $admin_data_temp) {
                if (strcmp($admin_data_temp['admin_password'], md5($pw)) == 0) {
                    $admin_data_check = $admin_data_temp['admin_id'];
                    break;
                }
            }
            if ($admin_data_check > 0) {
                echo $admin_data_check;
                exit;
            } else {
                echo 3;
                exit;
            }
        } else {
            echo 4;
            exit;
        }
        echo 5;
        exit;
    }

    public function weixinlogin_msg()
    {
        $this->load->view('weixinlogin_msg', array());
    }

    /**检测登录账户的登录类型**/
    public function check_user_name()
    {
        $username = $_REQUEST['username'];
        if (empty($username)) {
            echo 3;
            exit;
        }
        $sql = "SELECT login_style FROM " . $this->common->table('admin') . " WHERE admin_username = '" . $username . "'";
        $login_style = $this->common->getOne($sql);
        if ($login_style == null) {
            $login_style = 4;
        }
        echo $login_style;
        exit;
    }


    /****
     * 同步用户数据到 数据中心
     */
    private function sycn_user_data_to_ireport($parm)
    {
        //if($_COOKIE['l_admin_id'] == 849 or  $_COOKIE['l_admin_id'] == 1938  or $_COOKIE['l_admin_id'] == 879){


        $info = array();
        foreach ($parm as $temps) {
            $info_temp = array();
            if (empty($temps['ireport_admin_id'])) {
                $temps['ireport_admin_id'] = '';
            }
            $info_temp['renai_id'] = $temps['admin_id'];
            $info_temp['id'] = $temps['ireport_admin_id'];
            $info_temp['name'] = $temps['admin_name'];
            $info_temp['login_name'] = $temps['admin_username'];
            $info_temp['login_passwd'] = $temps['admin_password'];
            $info_temp['is_delete'] = $temps['is_pass'];
            $info[] = $info_temp;
        }
        $info = json_encode($info);

        //var_dump($info_temp);exit;
        //推送数据到数据中心系统
        $url = $this->config->item('ireport_url_send_user');

        if (!empty($url)) {
            require_once(BASEPATH . "/core/Decryption.php");

            //加密请求
            $decryption = new Decryption();
            //字母字符  用户保存，手动更新
            $mu_str = $this->config->item('renai_mu_word');
            //符号字符  用户保存，手动更新
            $gong_str = $this->config->item('renai_mu_number');
            //生成一次性加密key
            $key_user = $decryption->createRandKey($mu_str, $gong_str);
            //var_dump($key_user);
            $str = $this->config->item('ireport_name') . date("Y-m-d H", time());
            //var_dump($str) ;exit;
            //加密
            $encryption_validate = $decryption->encryption($str, $key_user);
            $encryption_data = $decryption->encryption($info, $key_user);
            //var_dump($encryption_data) ;exit;

            //echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
            /**
             * //初始化
             * $ch = curl_init();
             * //设置选项，包括URL
             * curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption_validate['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$encryption_data['data']);
             * curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             * curl_setopt($ch, CURLOPT_HEADER, 0);
             * //执行并获取HTML文档内容
             * $output = curl_exec($ch);
             * //释放curl句柄
             * curl_close($ch);
             * //var_dump($output);exit;
             **/

            $post_data = array("appid" => $this->config->item('ireport_name'), "appkey" => $this->config->item('ireport_pwd'), "verification" => $encryption_validate['data'], "key_one" => $key_user['mu_str_number'], "key_two" => $key_user['gong_str_number'], "info" => $encryption_data['data']);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);// post数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
            $output = curl_exec($ch);
            curl_close($ch);
            /*** **/
            //var_dump($url);
            //var_dump($output);exit;
            $api_request = array();
            $api_request['apply_id'] = '';
            $api_request['apply_type'] = '添加或者更新用户账户';
            $api_request['apply_name'] = '用户账户';
            $api_request['apply_time'] = date("Y-m-d H:i:s", time());
            $api_request['apply_address'] = $url;
            $api_request['apply_data'] = $info;

            //待更新数据
            $output_json = json_decode($output, true);
            if (!empty($output_json)) {
                $output_data = json_decode($output_json['msg'], true);;
                $add_empty_status = $output_data['add_empty_status'];
                $add_exits_status = $output_data['add_exits_status'];
                $add_add_ok_status = $output_data['add_add_ok_status'];
                $add_add_error_status = $output_data['add_add_error_status'];
                $add_update_ok_status = $output_data['add_update_ok_status'];
                $add_update_error_status = $output_data['add_update_error_status'];

                if (strcmp($output_json['code'], 'info_empty') == 0) {//记录日志
                    $api_request['apply_status'] = '2';
                } else if (strcmp($output_json['code'], 'decryption_error') == 0) {//记录日志
                    $api_request['apply_status'] = '2';
                } else if (strcmp($output_json['code'], 'ok') == 0) {//记录日志
                    $api_request['apply_status'] = '1';
                    if (count($add_add_ok_status) > 0) {
                        foreach ($add_add_ok_status as $temps) {
                            $update = array();
                            $update['ireport_admin_id'] = $temps['active_id'];
                            $update['ireport_msg'] = '绑定成功';
                            $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                        }
                    } else if (count($add_update_ok_status) > 0) {
                        foreach ($add_update_ok_status as $temps) {
                            $update = array();
                            $update['ireport_admin_id'] = $temps['active_id'];
                            $update['ireport_msg'] = '更新成功';
                            $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                        }
                    } else {
                        if (!empty($add_empty_status)) {
                            foreach ($add_empty_status as $temps) {
                                $update = array();
                                $update['ireport_msg'] = '操作失败。存在空值';
                                $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                            }
                        } else if (!empty($add_exits_status)) {
                            foreach ($add_exits_status as $temps) {
                                $update = array();
                                $update['ireport_msg'] = '操作失败。存在同样的账户数据';
                                $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                            }
                        } else if (!empty($add_add_error_status)) {
                            foreach ($add_add_error_status as $temps) {
                                $update = array();
                                $update['ireport_msg'] = '操作失败。数据中心添加失败';
                                $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                            }
                        } else if (!empty($add_update_error_status)) {
                            foreach ($add_update_error_status as $temps) {
                                $update = array();
                                $update['ireport_msg'] = '操作失败。数据中心更新失败';
                                $this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
                            }
                        }
                    }
                } else {//记录日志
                    $output_json['msg'] = '绑定失败。接口反馈异常';
                    $api_request['apply_status'] = '1';
                }
            } else {
                $output_json['msg'] = '绑定失败。接口反馈异常';
                $api_request['apply_status'] = '1';
            }
            $api_request['response_msg'] = $output_json['msg'];
            $api_request['response_code'] = $output_json['code'];
            $api_request['response_data'] = $output;
            $this->db->insert($this->common->table("api_request_log"), $api_request);
        } else {
            $api_request = array();
            $api_request['apply_id'] = '';
            $api_request['apply_type'] = '添加或者更新用户账户';
            $api_request['apply_name'] = '用户账户';
            $api_request['apply_name'] = '';
            $api_request['apply_time'] = date("Y-m-d H:i:s", time());
            $api_request['apply_address'] = $url;
            $api_request['apply_data'] = $info;
            $api_request['apply_status'] = 2;
            $api_request['response_msg'] = '请求URL地址为空';
            $api_request['response_code'] = '';
            $api_request['response_data'] = '';
            $this->db->insert($this->common->table("api_request_log"), $api_request);
        }
        //}

    }


    /****
     * 删除  账户 数据   同步到 数据中心
     */
    private function sycn_del_user_data_to_ireport($parm)
    {
        header("Content-type: text/html; charset=utf-8");

        $info = array();
        $info_temp = array();
        $info_temp['id'] = $parm['ireport_admin_id'];

        $info[] = $info_temp;

        //var_dump($info_temp);exit;
        //推送数据到数据中心系统
        $url = $this->config->item('ireport_url_del_user');
        if (!empty($url)) {
            require_once(BASEPATH . "/core/Decryption.php");

            //加密请求
            $decryption = new Decryption();
            //字母字符  用户保存，手动更新
            $mu_str = $this->config->item('renai_mu_word');
            //符号字符  用户保存，手动更新
            $gong_str = $this->config->item('renai_mu_number');
            //生成一次性加密key
            $key_user = $decryption->createRandKey($mu_str, $gong_str);
            //var_dump($key_user);
            $str = $this->config->item('ireport_name') . date("Y-m-d H", time());
            //var_dump($str) ;exit;
            //加密
            $encryption_validate = $decryption->encryption($str, $key_user);

            $encryption_data = $decryption->encryption(json_encode($info), $key_user);
            //var_dump($encryption) ;exit;

            //echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
            /**
             * //初始化
             * $ch = curl_init();
             * //设置选项，包括URL
             * curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info);
             * curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             * curl_setopt($ch, CURLOPT_HEADER, 0);
             * //执行并获取HTML文档内容
             * $output = curl_exec($ch);
             * //释放curl句柄
             * curl_close($ch);
             * //var_dump($output);exit;**/

            /*****/
            $post_data = array("appid" => $this->config->item('ireport_name'), "appkey" => $this->config->item('ireport_pwd'), "verification" => $encryption_validate['data'], "key_one" => $key_user['mu_str_number'], "key_two" => $key_user['gong_str_number'], "info" => $encryption_data['data']);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);// post数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
            $output = curl_exec($ch);
            curl_close($ch);
        } else {
            $api_request = array();
            $api_request['apply_id'] = '';
            $api_request['apply_type'] = '删除用户账户';
            $api_request['apply_name'] = '用户账户';
            $api_request['apply_time'] = date("Y-m-d H:i:s", time());
            $api_request['apply_address'] = $url;
            $api_request['apply_data'] = $info;
            $api_request['apply_status'] = 2;
            $api_request['response_msg'] = '请求URL地址为空';
            $api_request['response_code'] = '';
            $api_request['response_data'] = '';
            $this->db->insert($this->common->table("api_request_log"), $api_request);
        }

    }

    /**
     * 首页咨询分页
     * @return [type] [description]
     */
    function index_table_ajax()
    {
        //判断权限
        if (strcmp($_COOKIE['l_admin_action'], 'all') != 0) {
            $l_admin_action = explode(',', $_COOKIE['l_admin_action']);
            if (!in_array(155, $l_admin_action)) {
                echo '';
                exit;
            }
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';//日期
        //时间搜索权限
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];


            if (!$this->dateCheck($start) || !$this->dateCheck($end)) {
                $start = date("Y年m月d日");
                $end = $start;
            }


            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
            $end = str_replace(array("年", "月", "日"), "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }

        /**** ****/
        $data = $this->common->config('index');//这里调用的是libraries下的common.php中的config()方法

        $today_time_start = strtotime(date("Y-m-d ", time()) . "00:00:00");
        $today_time_end = strtotime(date("Y-m-d ", time()) . "23:59:59");


        $time = date("Y-m-01", time());
        $month_time_start = strtotime($time . " 00:00:00");
        $month_time_end = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");
        $time = date('Y-m-01', strtotime(date('Y', time()) . '-' . (date('m', time()) - 1) . '-01'));
        $last_month_start = strtotime($time . " 00:00:00");
        $last_month_end = strtotime(date('Y-m-d', strtotime("$time +1 month -1 day")) . " 23:59:59");

        $fuke = isset($_REQUEST['fuke']) ? trim($_REQUEST['fuke']) : "";
        $hospital = $this->common->get_hosname();
        //admin_id in(452,778)
        $where = " where 1";
        if (!empty($_REQUEST['hos_id'])) {
            if ($_REQUEST['hos_id'] == '1_1') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id in(1)";
                }
            } else if ($_REQUEST['hos_id'] == '1_7') {
                $hos_id_array = explode('_', $_REQUEST['hos_id']);
                if (count($hos_id_array) > 1) {
                    $where .= " AND hos_id=" . $hos_id_array[0];
                    $where .= " AND keshi_id = 7 ";
                }
            } else {
                $where .= " AND hos_id=" . $_REQUEST['hos_id'];

                if (!empty($_REQUEST['keshi_id'])) {
                    $where .= " AND keshi_id=" . $_REQUEST['keshi_id'];
                } else if (!empty($_COOKIE['l_keshi_id'])) {
                    $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
                }

            }
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where .= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }
        }


        if ($fuke != "1" && $_REQUEST['hos_id'] == 1) {
            $special_keshi_id_array = explode(',', $fuke);
            if (count($special_keshi_id_array) > 1) {
                /***
                 * 2017 06 28 添加功能  开始
                 */
                //判斷是否是人流
                if ($special_keshi_id_array[0] == 'js') {
                    $where = " where 1 AND hos_id = 1 AND jb_parent_id IN (" . $special_keshi_id_array[1] . ")";
                } else {
                    $where = " where 1 AND hos_id = 1 AND keshi_id in(" . $fuke . ")";
                }
                /***
                 * 2017 06 28 添加功能  结束
                 */
            }

        }

        $type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : 0;
        $hos_id = !empty($_REQUEST['hos_id']) ? $_REQUEST['hos_id'] : 0;
        if (!empty($type)) {
            $keshi = array();
            if ($type == 'fuke') {
                $keshi = $this->config->item('all_fuke');
                $data['type'] = $type;
            } elseif ($type == 'byby') {
                $keshi = $this->config->item('all_byby');
                $data['type'] = $type;
            } elseif ($type == 'gc') {
                $keshi = $this->config->item('all_gc');
                $data['type'] = $type;
            } else {
                $keshi = $this->config->item('all_nanke');
                $data['type'] = 'nanke';
            }

            $ks = array();
            foreach ($keshi as $value) {
                $ks[] = explode('_', $value['keshi_id']);
            }

            $ks_ids_str = '';
            foreach ($ks as $value) {
                foreach ($value as $val) {
                    $ks_ids_str .= $val . ',';
                }
            }

            if (!empty($hos_id)) {
                $where = " where hos_id IN (" . str_replace('_', ',', $hos_id) . ") and keshi_id IN (" . substr($ks_ids_str, 0, -1) . ")";
            } else {
                $where = " where keshi_id IN (" . substr($ks_ids_str, 0, -1) . ")";
            }

        }

        $des = array();

        if (isset($start) && !empty($start)) {
            $month_time_start = strtotime($start . ' 00:00:00');
            $month_time_end = strtotime($end . ' 23:59:59');
        }
        $sql_3 = "select admin_name,admin_id,hos_id,COUNT(*) AS month_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $month_time_start . " and " . $month_time_end . "  group by admin_id order by month_come desc";
        $info_3 = $this->common->getALL($sql_3);
        $list_3 = array();
        foreach ($info_3 as $val) {
            $list_3[$val['admin_id']][] = $val;
        }
        foreach ($list_3 as $key => $val) {
            $des[$key]['month_come'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['month_come'] += $v['month_come'];
            }
        }

        /***
         * 2017 06 28 添加功能  开始
         */
        //获取预约客服全月预到数据
        $sql_15 = "select admin_name,admin_id,hos_id,COUNT(*) AS month_action from " . $this->common->table('order') . $where . "   and order_time between " . $month_time_start . " and " . $month_time_end . "  group by admin_id order by month_action desc";
        $info_15 = $this->common->getALL($sql_15);
        $list_15 = array();
        foreach ($info_15 as $val) {
            $list_15[$val['admin_id']][] = $val;
        }
        foreach ($list_15 as $key => $val) {
            $des[$key]['month_action'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['month_action'] += $v['month_action'];
            }
        }

        //p($des);die();


        //获取当月个人预约数据
        if (isset($start) && !empty($start)) {
            $month_time_start = strtotime($start . ' 00:00:00');
            $month_time_end = strtotime($end . ' 23:59:59');
        }
        $sql = "select admin_name,admin_id,COUNT(*) AS count  from " . $this->common->table('order') . $where . " AND order_addtime between  $month_time_start and  $month_time_end  group by admin_id";
        $info = $this->common->getAll($sql);

        $list = array();
        foreach ($info as $val) {
            $list[$val['admin_id']][] = $val;
        }
        foreach ($list as $key => $val) {
            $des[$key]['count'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['count'] += $v['count'];
            }
        }


        //获取预约客服上月到诊数据,进行前三名的排行
        if (isset($start) && !empty($start)) {
            $last_month_start = strtotime($start . ' 00:00:00');
            $last_month_end = strtotime($end . ' 23:59:59');
        }
        $sql_5 = "select admin_name,admin_id,COUNT(*) AS last_month_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $last_month_start . " and  " . $last_month_end . " group by admin_id order by last_month_come desc limit 0,3";
        $info_5 = $this->common->getALL($sql_5);
        $list_5 = array();
        foreach ($info_5 as $val) {
            $list_5[$val['admin_id']][] = $val;
        }
        foreach ($list_5 as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
            }
        }

        //获取预约客服上月登记数据,进行前三名的排行
        if (isset($start) && !empty($start)) {
            $last_month_start = strtotime($start . ' 00:00:00');
            $last_month_end = strtotime($end . ' 23:59:59');
        }
        $sql_6 = "select admin_name,admin_id,COUNT(*) AS last_month_add from " . $this->common->table('order') . $where . " and order_addtime between " . $last_month_start . " and  " . $last_month_end . " group by admin_id";
        $info_6 = $this->common->getALL($sql_6);
        $list_6 = array();
        foreach ($info_6 as $val) {
            $list_6[$val['admin_id']][] = $val;
        }
        foreach ($list_6 as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
            }
        }


        if (empty($start)) {
            //获取当天个人预到数据
            $sql_1 = "select admin_name,admin_id,COUNT(*) AS count_t_order  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_start . " and  " . $today_time_end . "  group by admin_id";
            $info_1 = $this->common->getAll($sql_1);
            $list_1 = array();
            foreach ($info_1 as $val) {
                $list_1[$val['admin_id']][] = $val;
            }
            foreach ($list_1 as $key => $val) {
                $des[$key]['count_t_order'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_order'] += $v['count_t_order'];
                }
            }

            for ($i = 1; $i < 32; $i++) {
                $today_time_add_one_start = strtotime(date("Y-m-d", time() + (86400 * $i)) . " 00:00:00");//对时间的格式化操作\
                $today_time_add_one_end = strtotime(date("Y-m-d", time() + (86400 * $i)) . " 23:59:59");
                $sql_add_one = "select order_id,admin_name,admin_id,COUNT(*) AS count_t_add_order_" . $i . "  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end . "  group by admin_id";
                $info_add_one = $this->common->getAll($sql_add_one);
                /*查询时间数据*/
                $sql_add_one = "select order_id,order_addtime,admin_id from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end;
                $info_add_one_order_add_time = $this->common->getAll($sql_add_one);

                $list_add_one = array();
                foreach ($info_add_one as $val) {
                    $list_add_one[$val['admin_id']][] = $val;
                }
                $today_time_start = strtotime(date("Y-m-d", time()) . " 00:00:00");//对时间的格式化操作
                $today_time_end = strtotime(date("Y-m-d", time()) . " 23:59:59");
                foreach ($list_add_one as $key => $val) {
                    $des[$key]['count_t_add_order_' . $i] = 0;
                    $des[$key]['count_t_add_order_today_' . $i] = 0;
                    foreach ($val as $k => $v) {
                        $des[$key]['admin_id'] = $v['admin_id'];
                        foreach ($info_add_one_order_add_time as $info_add_one_order_add_time_val) {
                            if ($des[$key]['admin_id'] == $info_add_one_order_add_time_val['admin_id'] && $info_add_one_order_add_time_val['order_addtime'] >= $today_time_start && $info_add_one_order_add_time_val['order_addtime'] <= $today_time_end) {
                                $des[$key]['count_t_add_order_today_' . $i] += 1;
                            }
                        }
                        $des[$key]['count_t_add_order_' . $i] += $v['count_t_add_order_' . $i];
                    }
                }
            }

            /*获取下个月 (以31天为周期计算)*/
            $today_time_add_one_start = strtotime(date("Y-m-d", time() + (86400 * 32)) . " 00:00:00");
            $today_time_add_one_end = strtotime(date("Y-m-d", time() + (86400 * 63)) . " 23:59:59");
            $sql_add_one = "select admin_name,admin_id,COUNT(*) AS count_t_add_next_order  from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end . "  group by admin_id";
            $info_add_one = $this->common->getAll($sql_add_one);
            /*查询时间数据*/
            $sql_add_one = "select order_id,order_addtime,admin_id from " . $this->common->table('order') . $where . " AND order_time between " . $today_time_add_one_start . " and " . $today_time_add_one_end;
            $info_add_one_order_add_time = $this->common->getAll($sql_add_one);
            $list_add_one = array();
            foreach ($info_add_one as $val) {
                $list_add_one[$val['admin_id']][] = $val;
            }
            foreach ($list_add_one as $key => $val) {
                $des[$key]['count_t_add_next_order'] = 0;
                $des[$key]['count_t_add_next_order_today'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    foreach ($info_add_one_order_add_time as $info_add_one_order_add_time_val) {
                        if ($des[$key]['admin_id'] == $info_add_one_order_add_time_val['admin_id'] && $info_add_one_order_add_time_val['order_addtime'] >= $today_time_start && $info_add_one_order_add_time_val['order_addtime'] <= $today_time_end) {
                            $des[$key]['count_t_add_next_order_today'] += 1;
                        }
                    }
                    $des[$key]['count_t_add_next_order'] += $v['count_t_add_next_order'];
                }
            }


            /*获取当天个人预约数据*/
            $sql_2 = "select admin_name,admin_id,COUNT(*) AS count_t_add  from " . $this->common->table('order') . $where . " AND order_addtime between " . $today_time_start . " and  " . $today_time_end . "  group by admin_id";
            $info_2 = $this->common->getAll($sql_2);
            $list_2 = array();
            foreach ($info_2 as $val) {
                $list_2[$val['admin_id']][] = $val;
            }
            foreach ($list_2 as $key => $val) {
                $des[$key]['count_t_add'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_add'] += $v['count_t_add'];
                }
            }

            /*获取预约客服今日到诊数据*/
            $sql_4 = "select admin_name,admin_id,COUNT(*) AS today_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $today_time_start . " and " . $today_time_end . " group by admin_id ";
            $info_4 = $this->common->getALL($sql_4);
            $list_4 = array();
            foreach ($info_4 as $val) {
                $list_4[$val['admin_id']][] = $val;
            }
            foreach ($list_4 as $key => $val) {
                $des[$key]['today_come'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['today_come'] += $v['today_come'];
                }
            }

            //昨天时间
            $tommro_time_add_one_start = strtotime(date("Y-m-d", time() - (86400 * 1)) . " 00:00:00");//对时间的格式化操作\
            $tommro_time_add_one_end = strtotime(date("Y-m-d", time() - (86400 * 1)) . " 23:59:59");
            //获取昨天个人预约数据
            $sql_12 = "select admin_name,admin_id,COUNT(*) AS count_t_tommro_add  from " . $this->common->table('order') . $where . " AND order_addtime between " . $tommro_time_add_one_start . " and  " . $tommro_time_add_one_end . "  group by admin_id";
            $info_12 = $this->common->getAll($sql_12);
            $list_12 = array();
            foreach ($info_12 as $val) {
                $list_12[$val['admin_id']][] = $val;
            }
            foreach ($list_12 as $key => $val) {
                $des[$key]['count_t_tommro_add'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_tommro_add'] += $v['count_t_tommro_add'];
                }
            }

            //获取昨天到诊数据
            $sql_14 = "select admin_name,admin_id,COUNT(*) AS count_t_tommro_come from " . $this->common->table('order') . $where . " and is_come=1 and come_time between " . $tommro_time_add_one_start . " and " . $tommro_time_add_one_end . " group by admin_id ";
            $info_14 = $this->common->getALL($sql_14);
            $list_14 = array();
            foreach ($info_14 as $val) {
                $list_14[$val['admin_id']][] = $val;
            }
            foreach ($list_14 as $key => $val) {
                $des[$key]['count_t_tommro_come'] = 0;
                foreach ($val as $k => $v) {
                    $des[$key]['admin_id'] = $v['admin_id'];
                    $des[$key]['admin_name'] = $v['admin_name'];
                    $des[$key]['count_t_tommro_come'] += $v['count_t_tommro_come'];
                }
            }
        }


        //对相同到诊数情况下的预约客服重新排序，代码开始
        $aa = array();
        $bb = array();

        foreach ($des as $v) {
            @$aa[] = $v['month_come'];
        }
        foreach ($des as $k => $v) {
            @$bb[] = $v['count'];
        }

        array_multisort($aa, SORT_DESC, $bb, SORT_ASC, $des);


        $group_id_str = '0';
        foreach ($des as $v) {
            if ($group_id_str == '0') {
                $group_id_str = $v['admin_id'];
            } else {
                $group_id_str .= ',' . $v['admin_id'];
            }
        }

        //查询一级组名称
        $sql_group = "select id,name from " . $this->common->table('user_groups') . " where parent_id = 0";
        $groups_data = $this->common->getALL($sql_group);

        //查询全部组合名称
        $sql_admin = "select g.name,g.parent_id,a.admin_group,a.admin_id from " . $this->common->table('admin') . " as a," . $this->common->table('user_groups') . " as g where a.admin_group = g.id and  a.admin_id in(" . $group_id_str . ")";

        $admin_in_data = $this->common->getALL($sql_admin);
        foreach ($admin_in_data as $admin_in_data_key => $admin_in_data_val) {
            $check_group = 0;
            foreach ($groups_data as $groups_data_val) {
                if ($admin_in_data_val['parent_id'] == $groups_data_val['id']) {
                    $check_group = 0;
                    $admin_in_data[$admin_in_data_key]['name'] = $groups_data_val['name'] . '<br/>' . $admin_in_data[$admin_in_data_key]['name'];
                    break;
                }
            }
            if ($check_group != 0) {
                break;
            }
        }


        //定义星期 数据
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");

        $style_flow = 'overflow-x: scroll;';
        $style_wide = 'width:3000px;';
        $style_width = 'width:6000px;';

        if (!empty($start)) {
            $style_flow = '';
            $style_wide = 'width:100%;';
            $style_width = 'width:100%;';
        }


        echo "
		     <tbody><tr ><td><span style='color:#00a187;font-size:12px'>到诊</span></td>
			 	  <td><span style='color:#00a187;font-size:12px'>所属组</span></td>
			      <td><span style='color:#00a187;font-size:12px'>预约客服</span></td>
				  <td><span style='color:#00a187;font-size:12px'>全月预约</span></td>
						  <td><span style='color:#00a187;font-size:12px'>全月预到</span></td>
				  <td><span style='color:#00a187;font-size:12px'>全月到诊</span></td>
			  <td><span style='color:#00a187;font-size:12px'>到诊率</span></td>";
        if (empty($start)) {
            echo
            "<td><span style='color:#00a187;font-size:12px'>今天预约</span></td>
				<td><span style='color:#00a187;font-size:12px'>今天预到</span></td>
				<td><span style='color:#00a187;font-size:12px'>今天到诊</span></td>
				<td><span style='color:#00a187;font-size:12px'>昨日预约</span></td>
				<td><span style='color:#00a187;font-size:12px'>昨日到诊</span></td>
				";
            for ($i = 1; $i < 32; $i++) {
                echo "<td><table><tr><td colspan='3' style='color:#00a187;'>" . date('d', time() + 3600 * 24 * $i) . "号(" . $weekarray[date("w", time() + 3600 * 24 * $i)] . ")</td></tr><tr><td><span style='color:#00a187;font-size:12px'>今日约</span></td><td><span style='color:#00a187;font-size:12px'>预到</span></td></tr></table></td>";
            }
            echo "
				<td><table><tr><td colspan='3' style='color:#00a187;'>下月总计)</td></tr><tr><td><span style='color:#00a187;font-size:12px'>今日约</span></td><td><span style='color:#00a187;font-size:12px'>预到</span></td></tr></table></td>";
        }

        echo "</tr>";


        //if(false){//属于仁爱 医院 妇科
        if (strcmp($_REQUEST['hos_id'], 1) == 0 && strcmp($_REQUEST['keshi_id'], 1) == 0) {//属于仁爱 医院 妇科
            $i = 1;
            foreach ($des as $k => $v) {
                $des[$k]['index'] = $i;
                $des[$k]['group_name'] = '';
                foreach ($admin_in_data as $admin_in_data_val) {
                    if ($v['admin_id'] == $admin_in_data_val['admin_id']) {
                        $des[$k]['group_name'] = $admin_in_data_val['name'];
                        break;
                    }
                }
                $i++;
            }

            $sort = array(
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
                'field' => 'group_name',       //排序字段
            );
            $arrSort = array();
            foreach ($des AS $uniqid => $row) {
                foreach ($row AS $key => $value) {
                    $arrSort[$key][$uniqid] = $value;
                }
            }
            if ($sort['direction']) {
                array_multisort($arrSort[$sort['field']], constant($sort['direction']), $des);
            }
        } else {
            $i = 1;
            foreach ($des as $k => $v) {
                $des[$k]['index'] = $i;
                $des[$k]['group_name'] = '';
                foreach ($admin_in_data as $admin_in_data_val) {
                    if ($v['admin_id'] == $admin_in_data_val['admin_id']) {
                        $des[$k]['group_name'] = $admin_in_data_val['name'];
                        break;
                    }
                }
                $i++;
            }
        }

        //$des按全月到诊排序
        // foreach ($des as $key => $value) {
        //     $month_come_ar[] = $value['month_come']?$value['month_come']:0;
        // }
        // array_multisort($month_come_ar, SORT_DESC, $des);

        //统计合计数据
        $count_data = array();
        foreach ($des as $k => $v) {

            if (isset($count_data['count'])) {
                $count_data['count'] += $v['count'];
            } else {
                $count_data['count'] = $v['count'];
            }


            if (isset($v['month_action'])) {

                if (isset($count_data['month_action'])) {
                    $count_data['month_action'] += $v['month_action'];
                } else {
                    $count_data['month_action'] = $v['month_action'];
                }
            } else {
                if (!isset($count_data['month_action'])) {
                    $count_data['month_action'] = 0;
                }
            }

            if (isset($v['month_come'])) {
                if (isset($count_data['month_come'])) {
                    $count_data['month_come'] += $v['month_come'];
                } else {
                    $count_data['month_come'] = $v['month_come'];
                }
            } else {
                if (!isset($count_data['month_come'])) {
                    $count_data['month_come'] = 0;
                }
            }


            if (empty($start)) {

                if (isset($v['count_t_add'])) {
                    if (isset($count_data['count_t_add'])) {
                        $count_data['count_t_add'] += $v['count_t_add'];
                    } else {
                        $count_data['count_t_add'] = $v['count_t_add'];
                    }
                } else {
                    if (!isset($count_data['count_t_add'])) {
                        $count_data['count_t_add'] = 0;
                    }
                }


                if (isset($v['count_t_order'])) {
                    if (isset($count_data['count_t_order'])) {
                        $count_data['count_t_order'] += $v['count_t_order'];
                    } else {
                        $count_data['count_t_order'] = $v['count_t_order'];
                    }
                } else {
                    if (!isset($count_data['count_t_order'])) {
                        $count_data['count_t_order'] = 0;
                    }
                }

                if (isset($v['today_come'])) {
                    if (isset($count_data['today_come'])) {
                        $count_data['today_come'] += $v['today_come'];
                    } else {
                        $count_data['today_come'] = $v['today_come'];
                    }
                } else {
                    if (!isset($count_data['today_come'])) {
                        $count_data['today_come'] = 0;
                    }
                }

                if (isset($v['count_t_tommro_add'])) {
                    if (isset($count_data['count_t_tommro_add'])) {
                        $count_data['count_t_tommro_add'] += $v['count_t_tommro_add'];
                    } else {
                        $count_data['count_t_tommro_add'] = $v['count_t_tommro_add'];
                    }
                } else {
                    if (!isset($count_data['count_t_tommro_add'])) {
                        $count_data['count_t_tommro_add'] = 0;
                    }
                }

                if (isset($v['count_t_tommro_come'])) {
                    if (isset($count_data['count_t_tommro_come'])) {
                        $count_data['count_t_tommro_come'] += $v['count_t_tommro_come'];
                    } else {
                        $count_data['count_t_tommro_come'] = $v['count_t_tommro_come'];
                    }
                } else {
                    if (!isset($count_data['count_t_tommro_come'])) {
                        $count_data['count_t_tommro_come'] = 0;
                    }
                }

                for ($i = 1; $i < 32; $i++) {

                    if (isset($v['count_t_add_order_today_' . $i])) {
                        if (isset($count_data['count_t_add_order_today_' . $i])) {
                            $count_data['count_t_add_order_today_' . $i] += $v['count_t_add_order_today_' . $i];
                        } else {
                            $count_data['count_t_add_order_today_' . $i] = $v['count_t_add_order_today_' . $i];
                        }
                    } else {
                        if (!isset($count_data['count_t_add_order_today_' . $i])) {
                            $count_data['count_t_add_order_today_' . $i] = 0;
                        }
                    }

                    if (isset($v['count_t_add_order_' . $i])) {
                        if (isset($count_data['count_t_add_order_' . $i])) {
                            $count_data['count_t_add_order_' . $i] += $v['count_t_add_order_' . $i];
                        } else {
                            $count_data['count_t_add_order_' . $i] = $v['count_t_add_order_' . $i];
                        }
                    } else {
                        if (!isset($count_data['count_t_add_order_' . $i])) {
                            $count_data['count_t_add_order_' . $i] = 0;
                        }
                    }
                }

                if (isset($v['count_t_add_next_order_today'])) {
                    if (isset($count_data['count_t_add_next_order_today'])) {
                        $count_data['count_t_add_next_order_today'] += $v['count_t_add_next_order_today'];
                    } else {
                        $count_data['count_t_add_next_order_today'] = $v['count_t_add_next_order_today'];
                    }
                } else {
                    if (!isset($count_data['count_t_add_next_order_today'])) {
                        $count_data['count_t_add_next_order_today'] = 0;
                    }
                }

                if (isset($v['count_t_add_next_order'])) {
                    if (isset($count_data['count_t_add_next_order'])) {
                        $count_data['count_t_add_next_order'] += $v['count_t_add_next_order'];
                    } else {
                        $count_data['count_t_add_next_order'] = $v['count_t_add_next_order'];
                    }
                } else {
                    if (!isset($count_data['count_t_add_next_order'])) {
                        $count_data['count_t_add_next_order'] = 0;
                    }
                }
            }
        }

        $page = intval($_REQUEST['page']);
        $limit = 20;

        $offset = ($page - 1) * $limit;

        $des = array_slice($des, $offset, $limit);

        //p($des);die();


        $k_index = 1;
        foreach ($des as $k => $v) {
            $k_index++;
            $i_check = $k_index % 2;

            echo "<tr><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            echo $v['index'] . "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            //查询用户 admin_group
            echo $v['group_name'];

            echo "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            echo $v['admin_name'] . "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            $v['count'] = isset($v['count']) ? intval($v['count']) : 0;
            echo $v['count'];

            echo "</td><td ";


            /***
             * 2017 06 28 添加功能  开始
             *
             */

            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";

            if (isset($v['month_action'])) {
                echo $v['month_action'];
            } else {
                echo 0;
            }
            echo "</td><td ";
            /***
             * 2017 06 28 添加功能  结束
             *
             */


            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";

            if (isset($v['month_come'])) {
                echo $v['month_come'];
            } else {
                echo 0;
            }
            echo "</td><td ";
            if ($i_check == 0) {
                echo "style='background-color:#c8c8c8;'";
            }
            echo " >";
            if (!isset($v['month_come']) || !isset($v['count'])) {
                echo '0%';
            } else {
                echo number_format((($v['month_come'] / $v['count']) * 100), 0, '.', '') . '%';
            }
            echo "</td>";

            if (empty($start)) {

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_add'])) {
                    echo $v['count_t_add'];
                } else {
                    echo 0;
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";

                if (isset($v['count_t_order'])) {
                    echo $v['count_t_order'];
                } else {
                    echo 0;
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";

                if (isset($v['today_come'])) {
                    echo $v['today_come'];
                } else {
                    echo 0;
                }

                echo "<td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_tommro_add'])) {
                    echo $v['count_t_tommro_add'];
                } else {
                    echo 0;
                }


                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " >";
                if (isset($v['count_t_tommro_come'])) {
                    echo $v['count_t_tommro_come'];
                } else {
                    echo 0;
                }

                for ($i = 1; $i < 32; $i++) {

                    echo "</td><td ";
                    if ($i_check == 0) {
                        echo "style='background-color:#c8c8c8;'";
                    }
                    echo " ><table><tr><td style='width:30px;";
                    if ($i_check == 0) {
                        echo "background-color:#c8c8c8;";
                    }
                    echo "'>";

                    if (isset($v['count_t_add_order_today_' . $i])) {
                        echo $v['count_t_add_order_today_' . $i];
                    } else {
                        echo 0;
                    }

                    echo "</td><td style='width:30px;";
                    if ($i_check == 0) {
                        echo "background-color:#c8c8c8;";
                    }
                    echo "'>";

                    if (isset($v['count_t_add_order_' . $i])) {
                        echo $v['count_t_add_order_' . $i];
                    } else {
                        echo 0;
                    }
                    echo "</td></tr></table> ";
                }

                echo "</td><td ";
                if ($i_check == 0) {
                    echo "style='background-color:#c8c8c8;'";
                }
                echo " ><table><tr><td style='width:30px;";
                if ($i_check == 0) {
                    echo "background-color:#c8c8c8;";
                }
                echo "'>";
                if (isset($v['count_t_add_next_order_today'])) {
                    echo $v['count_t_add_next_order_today'];
                } else {
                    echo 0;
                }

                echo "</td><td style='width:30px;";
                if ($i_check == 0) {
                    echo "background-color:#c8c8c8;";
                }
                echo "'>";
                if (isset($v['count_t_add_next_order'])) {
                    echo $v['count_t_add_next_order'];
                } else {
                    echo 0;
                }

                echo "</td></tr></table></td>";
            }

            echo "</tr>";
        }

        $month_come_percent = '';
        if (!isset($count_data['month_come']) || !isset($count_data['count'])) {
            $month_come_percent = '0%';
        } else {
            $month_come_percent = number_format((($count_data['month_come'] / $count_data['count']) * 100), 0, '.', '') . '%';
        }
        echo "<tr ><td style='background-color:#c8c8c8;' >合计</td>
					   <td style='background-color:#c8c8c8;' ></td>
					   <td style='background-color:#c8c8c8;' ></td>
					   <td style='background-color:#c8c8c8;' >" . $count_data['count'] . "</td>
					   <td style='background-color:#c8c8c8;' >" . $count_data['month_action'] . "</td>
					   <td style='background-color:#c8c8c8;' >" . $count_data['month_come'] . "</td>
					   <td style='background-color:#c8c8c8;' >" . $month_come_percent . "</td>";
        if (empty($start)) {
            echo
                "<td style='background-color:#c8c8c8;' >" . $count_data['count_t_add'] . "</td>
					    <td style='background-color:#c8c8c8;' >" . $count_data['count_t_order'] . "</td>
					    <td style='background-color:#c8c8c8;' >" . $count_data['today_come'] . "</td>
					   <td style='background-color:#c8c8c8;' >" . $count_data['count_t_tommro_add'] . "</td>
					   <td style='background-color:#c8c8c8;' >" . $count_data['count_t_tommro_come'] . "</td>";

            for ($i = 1; $i < 32; $i++) {
                echo "<td  ><table><tr><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_order_today_' . $i] . "</td><td  style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_order_' . $i] . "</td></tr></table></td>";
            }
            echo "
					   <td ><table><tr><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_next_order_today'] . "</td><td style='width:30px;background-color:#c8c8c8;'>" . $count_data['count_t_add_next_order'] . "</td></tr></table></td>";
        }
        echo "</tr></tbody>";

    }


    //男科首页
    public function index_nanke()
    {
        $data = array();
        $data = $this->common->config('index_nanke');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';

        $hos_id = (isset($_POST['hos_id']) && !empty($_POST['hos_id'])) ? trim($_POST['hos_id']) : 0;

        $type = 'nanke';

        $common = $this->index_common($date, $hos_id, $type);

        $data = array_merge($data, $common);

        $hospital = $this->config->item('all_nanke');
        $data['hospital'] = $hospital;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $this->load->view('index_common', $data);

    }

    //妇科首页
    public function index_fuke()
    {
        $data = array();
        $data = $this->common->config('index_fuke');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';

        $hos_id = (isset($_POST['hos_id']) && !empty($_POST['hos_id'])) ? trim($_POST['hos_id']) : 0;

        $type = 'fuke';

        $common = $this->index_common($date, $hos_id, $type);

        $data = array_merge($data, $common);

        $hospital = $this->config->item('all_fuke');
        $data['hospital'] = $hospital;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $this->load->view('index_common', $data);

    }

    //不孕不育首页
    public function index_byby()
    {
        $data = array();
        $data = $this->common->config('index_byby');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';

        $hos_id = (isset($_POST['hos_id']) && !empty($_POST['hos_id'])) ? trim($_POST['hos_id']) : 0;

        $type = 'byby';

        $common = $this->index_common($date, $hos_id, $type);

        $data = array_merge($data, $common);

        $hospital = $this->config->item('all_byby');
        $data['hospital'] = $hospital;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $this->load->view('index_common', $data);

    }


    //肛肠首页
    public function index_gc()
    {
        $data = array();
        $data = $this->common->config('index_gc');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';

        $hos_id = (isset($_POST['hos_id']) && !empty($_POST['hos_id'])) ? trim($_POST['hos_id']) : 0;

        $type = 'gc';

        $common = $this->index_common($date, $hos_id, $type);

        $data = array_merge($data, $common);

        $hospital = $this->config->item('all_gc');
        $data['hospital'] = $hospital;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $this->load->view('index_common', $data);

    }

    private function index_common($date, $hos_id, $type)
    {
        $data = array();


        //用病种区分科室有很多漏洞 病种未填写
        //男科
        // $jb = $this->db->get_where('jibing',array('parent_id' => 85))->result_array();

        // $jb_ids = array();
        // foreach ($jb as $item) {
        // 	$jb_ids[] = $item['jb_id'];
        // }

        $keshi = array();
        if ($type == 'fuke') {
            $keshi = $this->config->item('all_fuke');
            $data['type'] = $type;
        } elseif ($type == 'byby') {
            $keshi = $this->config->item('all_byby');
            $data['type'] = $type;
        } elseif ($type == 'gc') {
            $keshi = $this->config->item('all_gc');
            $data['type'] = $type;
        } else {
            $keshi = $this->config->item('all_nanke');
            $data['type'] = 'nanke';
        }

        $ks = array();
        foreach ($keshi as $value) {
            $ks[] = explode('_', $value['keshi_id']);
        }

        $ks_ids = array();
        foreach ($ks as $value) {
            foreach ($value as $val) {
                $ks_ids[] = $val;
            }
        }

        //如按时间搜索
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];
            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
            $end = str_replace(array("年", "月", "日"), "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

            $start_time = strtotime($start . ' 00:00:00');
            $end_time = strtotime($end . ' 23:59:59');

            //预约数据
            $this->db->where('order_addtime >=', $start_time);
            $this->db->where('order_addtime <=', $end_time);
            if ($hos_id) {
                $this->db->where_in('hos_id', explode('_', $hos_id));
            }
            $this->db->where_in('keshi_id', $ks_ids);
            $order_addtime_num = $this->db->count_all_results('order');

            //到诊数据
            $this->db->where('come_time >=', $start_time);
            $this->db->where('come_time <=', $end_time);
            $this->db->where('is_come', 1);
            if ($hos_id) {
                $this->db->where_in('hos_id', explode('_', $hos_id));
            }
            $this->db->where_in('keshi_id', $ks_ids);
            $come_time_num = $this->db->count_all_results('order');

            $html = '';
            $html .= '<div class="metro-nav-block nav-block-red" style="background-color: #da542e;"><a data-original-title="" href="?c=order&m=order_list&t=1&date=';
            $html .= urlencode(date("Y年m月d日", $start_time) . " - " . date("Y年m月d日", $end_time));
            $html .= '#本月预约"><i class="icon-hospital"></i><div class="info">';
            $html .= $order_addtime_num;
            $html .= '</div><div class="status">搜索时间范围之内的预约</div></a></div>
		                    <div class="metro-nav-block nav-block-orange" style="background-color: #da542e;">
		                        <a data-original-title="" href="?c=order&m=order_list&t=3&date=';
            $html .= urlencode(date("Y年m月d日", $start_time) . " - " . date("Y年m月d日", $end_time));
            $html .= '#本月来院"><i class="icon-user-md"></i><div class="info">';
            $html .= $come_time_num;
            $html .= '</div><div class="status">搜索时间范围之内的来院</div></a></div>';
            $data['search_time_get_html'] = $html;

        } else {
            //默认30天
            $start_time = strtotime(date("Y-m-d", time() - (86400 * 30)) . " 00:00:00");
            $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");
        }

        //预约数据
        $this->db->select('count(*) as count, order_addtime');
        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $this->db->group_by("order_addtime");
        $add_count_arr = $this->db->get('order')->result_array();

        //预到数据
        $this->db->select('count(*) as count, order_time');
        $this->db->where('order_time >=', $start_time);
        $this->db->where('order_time <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $this->db->group_by("order_time");
        $order_count_arr = $this->db->get('order')->result_array();

        //到诊数据
        $this->db->select('count(*) as count, come_time');
        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_come', 1);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $this->db->group_by("come_time");
        $come_count_arr = $this->db->get('order')->result_array();


        //最近30天的预约到诊数据
        $order = array();
        for ($i = 1; $i < 31; $i++) {
            $s_t = strtotime(date("Y-m-d", $end_time - (86400 * $i)) . " 00:00:00");
            $e_t = strtotime(date("Y-m-d", $end_time - (86400 * $i)) . " 23:59:59");

            $order[$i]['time'] = $s_t;
            $order[$i]['add'] = isset($order[$i]['add']) ? $order[$i]['add'] : 0;
            $order[$i]['come'] = isset($order[$i]['come']) ? $order[$i]['come'] : 0;
            $order[$i]['order'] = isset($order[$i]['order']) ? $order[$i]['order'] : 0;

            foreach ($add_count_arr as $value) {
                if ($value['order_addtime'] >= $s_t && $value['order_addtime'] <= $e_t) {
                    $order[$i]['add'] += $value['count'];
                }
            }

            foreach ($order_count_arr as $value) {
                if ($value['order_time'] >= $s_t && $value['order_time'] <= $e_t) {
                    $order[$i]['order'] += $value['count'];
                }
            }

            foreach ($come_count_arr as $value) {
                if ($value['come_time'] >= $s_t && $value['come_time'] <= $e_t) {
                    $order[$i]['come'] += $value['count'];
                }
            }

        }

        $data['order'] = $order;


        /*明日预到*/
        $start_time = strtotime(date("Y-m-d", time() + 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() + 24 * 60 * 60) . " 23:59:59");

        $this->db->where('order_time >=', $start_time);
        $this->db->where('order_time <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['tomo_order_count'] = $this->db->count_all_results('order');


        /*今日留联*/
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['today_ll_count'] = $this->db->count_all_results('order_liulian');


        /*今天预约*/
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['today_order_add_count'] = $this->db->count_all_results('order');

        /*昨日预约*/
        $start_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['yes_order_add_count'] = $this->db->count_all_results('order');

        /*昨日来院*/
        $start_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 23:59:59");

        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_come', 1);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['yes_come_count'] = $this->db->count_all_results('order');

        /*今天预到*/
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");

        $this->db->where('order_time >=', $start_time);
        $this->db->where('order_time <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['today_order_count'] = $this->db->count_all_results('order');

        /*今天来院*/
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");

        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_come', 1);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['today_come_count'] = $this->db->count_all_results('order');

        /*昨日留联*/

        $start_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time() - 24 * 60 * 60) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['yesterday_ll_count'] = $this->db->count_all_results('order_liulian');

        /*本月留联*/
        $time = date("Y-m-01", time());
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['month_ll_count'] = $this->db->count_all_results('order_liulian');


        /*本月预约*/
        $time = date("Y-m-01", time());
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['month_order_add_count'] = $this->db->count_all_results('order');


        /*本月来院*/
        $time = date("Y-m-01", time());
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");

        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_come', 1);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['month_come_count'] = $this->db->count_all_results('order');

        /*本月公海*/
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");

        $this->db->where('order_addtime >=', $start_time);
        $this->db->where('order_addtime <=', $end_time);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['month_gh_count'] = $this->db->count_all_results('gonghai_order');

        /*今日复诊*/
        $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", time()) . " 23:59:59");

        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_first', 0);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['today_fz_count'] = $this->db->count_all_results('order');

        /*本月复诊*/
        $time = date("Y-m-01", time());
        $start_time = strtotime($time . " 00:00:00");
        $end_time = strtotime(date("Y-m-d", strtotime("$time +1 month -1 day")) . " 23:59:59");

        $this->db->where('come_time >=', $start_time);
        $this->db->where('come_time <=', $end_time);
        $this->db->where('is_first', 0);
        if ($hos_id) {
            $this->db->where_in('hos_id', explode('_', $hos_id));
        }
        $this->db->where_in('keshi_id', $ks_ids);
        $data['month_fz_count'] = $this->db->count_all_results('order');

        $data['hos_id'] = $hos_id;
        $data['date'] = $date;

        return $data;

    }


}