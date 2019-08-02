<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// 系统初始设置类
class Common {
    var $db;
    var $config;
    var $lang;
    var $load;
    public function __construct() {
        date_default_timezone_set('Asia/Chongqing');
        $CI = & get_instance();
        $CI->load->database();
        $CI->lang->load('common');
        $this->config = $CI->config;
        $this->db = $CI->db;
        $this->lang = $CI->lang;
        $this->load = $CI->load;
    }
    /*

    记录系统的操作日志，写入admin_log数据表中

    */
    public function sys_log($admin_id, $admin_name, $action, $data = array()) {
        //调用示例
        $mac = new MacAddInfo(PHP_OS);
        $mac_addr = $mac->mac_addr;
        if (empty($mac_addr)) {
            $mac_addr = '未定位到mac地址';
        }
        $data = !empty($data) ? json_encode($data) : '';
        $arr = array('admin_id' => $admin_id, 'admin_name' => $admin_name, 'log_ip' => getip(), 'mac_address' => $mac_addr, 'log_time' => time(), 'log_action' => $action, 'log_data' => $data);
        $this->db->insert($this->table('admin_log'), $arr);
    }
    /*

    记录系统的预约列表 操作日志，写入search_log数据表中

    */
    public function search_log($search_data = array()) {
        if (count($search_data) > 0) {
            $mac = new MacAddInfo(PHP_OS);
            $search_data['mac_address'] = $mac->mac_addr;
            $this->db->insert($this->table('search_log'), $search_data);
        }
    }
    // 系统初始化函数
    public function config($action = '') {
        //unset($_COOKIE);exit;
        $data = array();
        $data['admin_action'] = array();
        $no_admin_check = array('login', 'login_ck');
        //利用in_array()函数判断$action是否在$no_admin_check中存在匹配的值，也就是说如果页面不是login和login_ck时执行下面内容
        if (!in_array($action, $no_admin_check)) { //判断是否存在cookie['L_admin_id']cookie['l_admin_username']
            if (isset($_COOKIE['l_admin_id']) && ($_COOKIE['l_admin_id'] > 0) && isset($_COOKIE['l_admin_username']) && !empty($_COOKIE['l_admin_username'])) {
                $admin_id = $_COOKIE['l_admin_id']; //赋值给$admin_id
                if ($admin_id !== '105' && $admin_id !== '2') {
                    //如果$admin_id的值不为105并且不为2时
                    $sql = "SELECT admin_id, token,is_pass,rank_id FROM " . $this->table('admin') . " WHERE admin_id = $admin_id";
                    $row = $this->getRow($sql);
                    //当我们登录的L_key的值和系统中的token不相同时，我们进行下一步的操作时会发生重复登录的操作，并且会把相关信息写入admin_log数据表中。
                    //var_dump($_COOKIE);
                    //var_dump($row);
                    //exit;
                    //echo $_COOKIE['l_key'].'/'.$row['token'].'/'.($_COOKIE['l_key'] !== $row['token']);
                    //exit;
                    if ($_COOKIE['l_key'] !== $row['token']) {
                        $this->sys_log($_COOKIE['l_admin_id'], $_COOKIE['l_admin_name'], 'logout', array('账户重复登录'));
                        //如果账户重复登录，设置cookie值过期，并且把所有相关的cookie值清空。
                        $cookie_time = time() - 3600;
                        setcookie('l_admin_id', "", $cookie_time, "/");
                        setcookie('l_admin_username', "", $cookie_time, "/");
                        setcookie('l_admin_name', "", $cookie_time, "/");
                        setcookie('l_rank_id', "", $cookie_time, "/");
                        setcookie('l_admin_action', "", $cookie_time, "/");
                        setcookie('l_hos_id', "", $cookie_time, "/");
                        setcookie('l_keshi_id', "", $cookie_time, "/");
                        setcookie('l_key', "", $cookie_time, "/");
                        $url = array(0 => array('href' => '?c=index&m=login', 'target' => 'self', 'text' => '返回登录页面'));
                        $this->msg('该账户已在其它电脑上登录，请重新登录', 0, $url);
                        exit();
                    } elseif ($row['is_pass'] !== '1') {
                        $cookie_time = time() - 3600;
                        setcookie('l_admin_id', "", $cookie_time, "/");
                        setcookie('l_admin_username', "", $cookie_time, "/");
                        setcookie('l_admin_name', "", $cookie_time, "/");
                        setcookie('l_rank_id', "", $cookie_time, "/");
                        setcookie('l_admin_action', "", $cookie_time, "/");
                        setcookie('l_hos_id', "", $cookie_time, "/");
                        setcookie('l_keshi_id', "", $cookie_time, "/");
                        setcookie('l_key', "", $cookie_time, "/");
                        $url = array(0 => array('href' => '?c=index&m=login', 'target' => 'self', 'text' => '返回登录页面'));
                        $this->msg('用户名不存在！请重新登录！', 0, $url);
                        exit();
                    }

                    //配置项是否已开启登录限制功能
                    if ($this->config->item('switch') == 1) {

                        //限制登录
                        if (!empty($row['rank_id'])) {
                            $sql_rank = "SELECT is_limit FROM " . $this->table('rank') . " WHERE rank_id = {$row['rank_id']}";
                            $row_rank = $this->getRow($sql_rank);
                            if ($row_rank['is_limit'] == 1) {
                                //限制登录
                                $ip_from_user = ips();
                                $ip_from_designated = explode(',', $this->config->item('ip'));//固定IP
                                $ips_from_designated = explode(',', $this->config->item('ips'));//IP段

                                //IP段检测
                                $ipregexp = implode('|', str_replace( array('*','.'), array('\d+','\.') ,$ips_from_designated) );

                                if (!in_array($ip_from_user, $ip_from_designated) && !preg_match("/^(".$ipregexp.")$/", $ip_from_user)) {
                                    $this->sys_log($_COOKIE['l_admin_id'], $_COOKIE['l_admin_name'], 'logout', array(
                                        '受限账户外部登录'
                                    ));
                                    //如果账户重复登录，设置cookie值过期，并且把所有相关的cookie值清空。
                                    $cookie_time = time() - 3600;
                                    setcookie('l_admin_id', "", $cookie_time, "/");
                                    setcookie('l_admin_username', "", $cookie_time, "/");
                                    setcookie('l_admin_name', "", $cookie_time, "/");
                                    setcookie('l_rank_id', "", $cookie_time, "/");
                                    setcookie('l_admin_action', "", $cookie_time, "/");
                                    setcookie('l_hos_id', "", $cookie_time, "/");
                                    setcookie('l_keshi_id', "", $cookie_time, "/");
                                    setcookie('l_key', "", $cookie_time, "/");
                                    $url = array(
                                        0 => array(
                                            'href' => '?c=index&m=login',
                                            'target' => 'self',
                                            'text' => '返回登录页面'
                                        )
                                    );
                                    $this->msg('该账户只能在公司登录！', 0, $url);
                                    exit();
                                }
                            }
                        }
                    }


                    //厦门宁波医生岗位类型不能进入order_list页面
                    if (!empty($row['rank_id'])) {
                        $sql_hos = "SELECT rank_id FROM " . $this->table('rank_power') . " WHERE hos_id in(45,46,54)";
                        $row_hos = $this->getAll($sql_hos);
                        $new_row_hos = array();
                        foreach ($row_hos as $key => $value) {
                            $new_row_hos[] = $value['rank_id'];
                        }
                        $new_row_hos_array = array_unique($new_row_hos);
                        asort($new_row_hos_array);

                        $sql_rank = "SELECT rank_type FROM " . $this->table('rank') . " WHERE rank_id = {$row['rank_id']}";
                        $row_rank = $this->getRow($sql_rank);
                        if ($row_rank['rank_type'] == 1 && in_array($row['rank_id'], $new_row_hos_array) && ($_GET['m'] == 'order_list' || $_GET['m'] == 'docter_index' )) {
                            header("location:?c=order&m=docter_index_xiamen");
                            exit();
                        } elseif ($row_rank['rank_type'] == 1 && $_GET['m'] == 'order_list') {
                            //医生岗位类型不能进入order_list页面
                            header("location:?c=order&m=docter_index");
                            exit();
                        }
                    }

                }
                $data['admin'] = array();
                $data['admin']['id'] = $_COOKIE['l_admin_id'];
                $data['admin']['username'] = $_COOKIE['l_admin_username'];
                $data['admin']['name'] = $_COOKIE['l_admin_name'];
                $data['admin']['rank_id'] = $_COOKIE['l_rank_id'];
                $img = $this->config->item('base_url') . 'static/avatar/' . shubu($_COOKIE['l_admin_id']) . '_small.gif';
                $data['admin']['img'] = $this->lang->line('uc_url') . "/avatar.php?uid=" . $_COOKIE['l_admin_id'] . "&type={S}&size=small";
                // 判断当前权限是否合法
                $admin_action = $_COOKIE['l_admin_action'];
                $action_menu = array();
                if ($admin_action == '') {
                    $this->common->msg($this->lang->line('no_power'), 1);
                    exit();
                } elseif ($admin_action != 'all') {
                    $admin_action = explode(",", $admin_action);
                    $data['admin_action'] = $admin_action;
                    $menu_arr = read_static_cache('menu_arr');
                    if (empty($menu_arr)) {
                        $sql = "SELECT * FROM " . $this->table('action') . " WHERE 1 ORDER BY act_order ASC";
                        $query = $this->db->query($sql);
                        $menu = array();
                        foreach ($query->result_array() as $row) {
                            $menu[$row['act_id']]['act_id'] = $row['act_id'];
                            $menu[$row['act_id']]['act_name'] = $row['act_name'];
                            $menu[$row['act_id']]['act_action'] = $row['act_action'];
                            $menu[$row['act_id']]['parent_id'] = $row['parent_id'];
                            $menu[$row['act_id']]['act_order'] = $row['act_order'];
                            $menu[$row['act_id']]['act_url'] = $row['act_url'];
                            $menu[$row['act_id']]['is_show'] = $row['is_show'];
                        }
                        write_static_cache('menu_arr', $menu); //从action数据表中获取相关的信息，写入缓存中

                    }
                    foreach ($menu_arr as $val) {
                        if ($val['act_action'] == $action) {
                            $this_act_id = $val['act_id'];
                            break;
                        }
                    }
                    //var_dump($action);
                    //var_dump($this_act_id);
                    //var_dump($admin_action);exit;
                    if (!in_array($this_act_id, $admin_action)) {
                        header("Content-type:text/html;charset=utf-8");
                        show_error($this->lang->line('no_power'), 500);
                        exit();
                    }
                }
                $number_info = $this->getRow("select * from " . $this->table('order_num') . " where number_id=1");
                $s = $number_info['number_s'];
                $e = $number_info['number_e'];
                $number_array = $this->array_admin_id($s, $e);
                if (!empty($number_array) && isset($number_array)) {
                    if (in_array($admin_id, $number_array)) {
                        header("Content-type:text/html;charset=utf-8");
                        show_error("", 500);
                        exit();
                    }
                }
            } else {
                $cookie_time = time() - 3600;
                setcookie('l_admin_id', "", $cookie_time, "/");
                header("location:?c=index&m=login");
                exit();
            }
        } else {
            $data['title'] = '深圳仁爱医院数据仓库管理系统';
            return $data;
        }
        //检测留言模块
        if ($admin_action == 'all' || in_array('123', $admin_action)) {
            $where = 'admin_id = 0';
            if (!empty($_COOKIE["l_hos_id"])) {
                $where.= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            $sql = "select count(order_id) from " . $this->table('order_mes') . " where $where";
            $count = $this->getOne($sql);
            $data['mes_count'] = $count;
        }
        //      //以下是检测系统消息模块 代码开始
        //               $query="select * from ".$this->table('system_message')." where is_pass=1 and message_id not in(select message_id from ".$this->table('message_read')." where admin_id=".$_COOKIE['l_admin_id'].") order by message_time desc" ;
        //               $query_2="select * from ".$this->table('system_message')." where is_pass=1 and message_id in(select message_id from ".$this->table('message_read')." where admin_id=".$_COOKIE['l_admin_id'].") order by message_time desc" ;
        $query_3 = "select count(*) from " . $this->table('system_message') . " where is_pass=1 and message_id not in(select message_id from " . $this->table('message_read') . " where admin_id=" . $_COOKIE['l_admin_id'] . ")";
        //               $res=$this->getAll($query);
        //               $res_2=$this->getAll($query_2);
        $res_3 = $this->getOne($query_3);
        //                 $data['no_read']=$res;
        //                 $data['is_read']=$res_2;
        $data['no_read_count'] = $res_3;
        //
        //
        ////以下是检测系统消息模块 代码结束
        //以下是稍微复杂点的根据权限而影响的菜单显示
        $menu = $this->get_menu($action);
        $data['menu'] = $menu['menu'];
        $data['menu_here'] = $menu['menu_here'];
        if (empty($data['menu_here']['parent']['son']['act_action'])) {
            if (!empty($data['menu_here']['parent']['act_action'])) {
                $data['act_name'] = $data['menu_here']['parent']['act_name'];
            } else {
                switch ($action) {
                    case 'login':
                        $data['act_name'] = $this->lang->line('home');
                    break;
                    default:
                        $data['act_name'] = $action;
                }
            }
        } else {
            $data['act_name'] = $data['menu_here']['parent']['son']['act_name'];
        }
        $data['title'] = '恒信健网络预约挂号管理系统';
        return $data;
    }
    // 简单的权限检测
    public function auth($action = '') {
        $no_admin_check = array('login', 'login_ck');
        if (!in_array($action, $no_admin_check)) {
            if (isset($_COOKIE['l_admin_id']) && ($_COOKIE['l_admin_id'] > 0) && isset($_COOKIE['l_admin_username']) && !empty($_COOKIE['l_admin_username'])) {
                $admin_action = $_COOKIE['l_admin_action'];
                $action_menu = array();
                if ($admin_action == '') {
                    return false;
                } elseif ($admin_action = 'all') {
                    return true;
                } else {
                    $admin_action = explode(",", $admin_action);
                    $menu_arr = read_static_cache('menu_arr');
                    if (empty($menu_arr)) {
                        $sql = "SELECT * FROM " . $this->table('action') . " WHERE 1 ORDER BY act_order ASC";
                        $query = $this->db->query($sql);
                        $menu = array();
                        foreach ($query->result_array() as $row) {
                            $menu[$row['act_id']]['act_id'] = $row['act_id'];
                            $menu[$row['act_id']]['act_name'] = $row['act_name'];
                            $menu[$row['act_id']]['act_action'] = $row['act_action'];
                            $menu[$row['act_id']]['parent_id'] = $row['parent_id'];
                            $menu[$row['act_id']]['act_order'] = $row['act_order'];
                            $menu[$row['act_id']]['act_url'] = $row['act_url'];
                            $menu[$row['act_id']]['is_show'] = $row['is_show'];
                        }
                        write_static_cache('menu_arr', $menu);
                    }
                    foreach ($menu_arr as $val) {
                        if ($val['act_action'] == $action) {
                            $this_act_id = $val['act_id'];
                            break;
                        }
                    }
                    if (!in_array($this_act_id, $admin_action)) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
            return false;
        }
        return false;
    }
    public function get_hosname() {
        $where = 1;
        if (!empty($_COOKIE["l_hos_id"])) {
            $where.= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
        }
        $sql = "SELECT * FROM " . $this->table('hospital') . " WHERE " . $where;
        $query = $this->db->query($sql);
        $hospital_all = $query->result_array();
        //过滤无效的医院
        $hospital = array();
        foreach ($hospital_all as $hospital_all_temp) {
            if (empty($hospital_all_temp['ask_auth'])) {
                $hospital[] = $hospital_all_temp;
            }
        }
        return $hospital;
    }
    //根据操作获取相应的分级菜单
    public function get_menu($action = '') {
        $data = read_static_cache('menu'); //读取缓存文件
        //定义两个二维和两个三维数组
        $menu_here['parent']['act_id'] = '';
        $menu_here['parent']['act_action'] = '';
        $menu_here['parent']['son']['act_id'] = '';
        $menu_here['parent']['son']['act_action'] = '';
        //如果缓存文件中的数据为空从action获取相关的数据，分别写入缓存文件menu_arr.php
        //和menu.php文件中。其中写入menu.php文件中的数据经过getDataTree()函数的分类排列
        if (empty($data)) {
            $sql = "SELECT * FROM " . $this->table('action') . " WHERE 1 ORDER BY act_order ASC";
            $query = $this->db->query($sql);
            $menu = array();
            foreach ($query->result_array() as $row) {
                $menu[$row['act_id']]['act_id'] = $row['act_id'];
                $menu[$row['act_id']]['act_name'] = $row['act_name'];
                $menu[$row['act_id']]['act_action'] = $row['act_action'];
                $menu[$row['act_id']]['parent_id'] = $row['parent_id'];
                $menu[$row['act_id']]['act_order'] = $row['act_order'];
                $menu[$row['act_id']]['act_url'] = $row['act_url'];
                $menu[$row['act_id']]['is_show'] = $row['is_show'];
            }
            write_static_cache('menu_arr', $menu);
            $menu = getDataTree($menu, 'act_id', 'parent_id', 'son');
            write_static_cache('menu', $menu);
            $data = $menu;
        }
        $menu = array();
        //如果用户拥有全部权限，则把从数据库中获取经过处理的数组$data赋值给$menu
        if ($_COOKIE['l_admin_action'] == 'all') {
            $menu = $data;
        } else {
            //从cookie中获取用户的操作权限
            $admin_action = explode(",", $_COOKIE['l_admin_action']);
            $menu_arr = read_static_cache('menu_arr');
            foreach ($menu_arr as $val) {
                if (in_array($val['act_id'], $admin_action)) {
                    $menu[$val['act_id']]['act_id'] = $val['act_id'];
                    $menu[$val['act_id']]['act_name'] = $val['act_name'];
                    $menu[$val['act_id']]['act_action'] = $val['act_action'];
                    $menu[$val['act_id']]['parent_id'] = $val['parent_id'];
                    $menu[$val['act_id']]['act_order'] = $val['act_order'];
                    $menu[$val['act_id']]['act_url'] = $val['act_url'];
                    $menu[$val['act_id']]['is_show'] = $val['is_show'];
                }
            }
            //获取相应的权限，赋值给$menu
            $menu = getDataTree($menu, 'act_id', 'parent_id', 'son');
        }
        foreach ($menu as $val) {
            if ($val['act_action'] == $action) {
                $menu_here['parent']['act_id'] = $val['act_id'];
                $menu_here['parent']['act_action'] = $val['act_action'];
                $menu_here['parent']['act_name'] = $val['act_name'];
            } elseif (isset($val['son'])) {
                foreach ($val['son'] as $v) {
                    if ($v['act_action'] == $action) {
                        $menu_here['parent']['act_id'] = $val['act_id'];
                        $menu_here['parent']['act_action'] = $val['act_action'];
                        $menu_here['parent']['act_name'] = $val['act_name'];
                        $menu_here['parent']['son']['act_id'] = $v['act_id'];
                        $menu_here['parent']['son']['act_action'] = $v['act_action'];
                        $menu_here['parent']['son']['act_name'] = $v['act_name'];
                    }
                }
            }
        }
        //返回数组，并分别把$menu和$menu_here赋值给menu 和menu_here
        return array('menu' => $menu, 'menu_here' => $menu_here);
    }
    /**
     * 系统提示信息
     *
     * @access      public
     * @param       string      msg_detail      消息内容
     * @param       int         msg_type        消息类型， 0消息，1错误，2询问
     * @param       array       links           可选的链接
     * @param       boolen      $auto_redirect  是否需要自动跳转
     * @return     int          $seconds        跳转时间，默认为3如果要更改在common_lang.php同时修改
     */
    public function msg($msg_detail, $msg_type = 0, $links = array(), $view = true, $auto_redirect = true, $seconds = 3) {
        if (count($links) == 0) {
            $links[0]['text'] = $this->lang->line('go_back');
            $links[0]['href'] = 'javascript:history.go(-1)';
        }
        $data['msg_detail'] = $msg_detail;
        $data['msg_type'] = $msg_type;
        $data['links'] = $links;
        $data['default_url'] = $links[0]['href'];
        $data['auto_redirect'] = $auto_redirect;
        $data['seconds'] = $seconds;
        if ($view) {
            $data = $this->load->view('msg', $data, true);
            echo $data;
            exit();
        } else {
            return $data;
        }
    }
    // 清除百度竞价URL里的附加参数
    public function baidu_cpc_url($url) {
        $tag = array('ra_a=', 'ra_c=', 'ra_d=', 'ra_b=', 'ra_e=');
        // 处理 &　呼号后的参数
        $url_arr = explode("&", $url);
        foreach ($url_arr as $key => $val) {
            $v = explode("=", $val);
            if (in_array($v[0] . "=", $tag)) {
                unset($url_arr[$key]);
            }
        }
        // 处理 ?　后的参数
        $url_arr_a = explode("?", $url_arr[0]);
        unset($url_arr[0]);
        foreach ($url_arr_a as $key => $val) {
            $v = explode("=", $val);
            if (in_array($v[0] . "=", $tag)) {
                unset($url_arr_a[$key]);
            }
        }
        // 合并URL
        $url = array_merge($url_arr_a, $url_arr);
        if (isset($url[1])) {
            $url[0].= "?" . $url[1];
            unset($url[1]);
        }
        $u = implode("&", $url);
        return $u;
    }
    /* 处理静态缓存文件 */
    public function static_cache($type, $cache_name, $action = '', $tags = array()) {
        if ($type == 'delete') {
            clear_static_cache($cache_name);
        } elseif ($type == 'read') {
            $cache_file_path = APPPATH . 'cache/static/' . $cache_name . '.php';
            if (file_exists($cache_file_path)) {
                $data = read_static_cache($cache_name);
            } else {
                $data = $this->static_data($action, $tags);
                write_static_cache($cache_name, $data);
            }
            return $data;
        } else {
            $data = $this->static_data($action, $tags);
            write_static_cache($cache_name, $data);
        }
    }
    public function static_data($action, $tags = array()) {
        if ($action == 'keshi') {
            $data = $this->getAll("SELECT * FROM " . $this->table('keshi') . " WHERE hos_id = " . $tags['hos_id'] . " ORDER BY keshi_order ASC");
            $data = getDataTree($data, 'keshi_id', 'parent_id', 'child');
        } elseif ($action == 'jibing') {
            $data = $this->getAll("SELECT * FROM " . $this->table('jibing') . " WHERE 1 ORDER BY jb_order ASC");
            $data = getDataTree($data, 'jb_id', 'parent_id', 'child');
        } elseif ($action == 'jibing_list') {
            $data = $this->getAll("SELECT * FROM " . $this->table('jibing') . " WHERE 1 ORDER BY jb_order ASC");
        } elseif ($action == 'rank') {
            $data = $this->getAll("SELECT * FROM " . $this->table('rank') . " ORDER BY rank_order ASC, rank_id DESC");
            $data = getDataTree($data, 'rank_id', 'parent_id', 'child');
        } elseif ($action == 'rank_arr') {
            $arr = $this->getAll("SELECT * FROM " . $this->table('rank') . " ORDER BY rank_order ASC, rank_id DESC");
            $data = array();
            if (empty($arr)) {
                return false;
            }
            foreach ($arr as $val) {
                $data[$val['rank_id']] = $val;
            }
        } elseif ($action == 'order_type') {
            $data = $this->getAll("SELECT * FROM " . $this->table('order_type') . " ORDER BY type_order ASC, type_id DESC");
        } elseif ($action == 'hospital') {
            $data = $this->getRow("SELECT * FROM " . $this->table('hospital') . " WHERE hos_id = " . $tags['hos_id'] . "");
        } elseif ($action == 'site') {
            $data = $this->getAll("SELECT * FROM " . $this->table('st') . " WHERE hos_id = " . $tags['hos_id'] . "");
        }
        return $data;
    }
    /* MYSQL 数据库函数 */
    public function getOne($sql) {
        $query = $this->db->query($sql);
        $arr = $query->result_array();
        if (empty($arr)) {
            return false;
        } else {
            foreach ($arr[0] as $val) {
                $r = $val;
            }
            return $r;
        }
    }
    /* MYSQL 数据库函数 */
    public function getAll($sql) {
        $query = $this->db->query($sql);
        $arr = $query->result_array();
        return $arr;
    }
    /* MYSQL 数据库函数 */
    public function getRow($sql) {
        $query = $this->db->query($sql);
        $arr = $query->result_array();
        if (empty($arr)) {
            return false;
        } else {
            return $arr[0];
        }
    }
    /* 返回带数据表前缀的表名 */
    public function table($table) {
        return $this->db->dbprefix . $table;
    }
    /**
     * 修整缩略名
     *
     * @access public
     * @param string $str 需要生成缩略名的字符串
     * @param string $default 默认的缩略名
     * @param integer $maxLength 缩略名最大长度
     * @param string $charset 字符编码
     * @return string
     */
    public static function repair_slugName($str, $default = NULL, $maxLength = 200, $charset = 'UTF-8') {
        $str = str_replace(array("'", ":", "\\", "/"), "", $str);
        $str = str_replace(array("+", ",", " ", ".", "?", "=", "&", "!", "<", ">", "(", ")", "[", "]", "{", "}"), "_", $str);
        $str = trim($str, '_');
        $str = empty($str) ? $default : $str;
        return function_exists('mb_get_info') ? mb_strimwidth($str, 0, 128, '', $charset) : substr($str, $maxLength);
    }
    /**
     * 抽取多维数组的某个元素,组成一个新数组,使这个数组变成一个扁平数组
     * 使用方法:
     * <code>
     * <?php
     * $fruit = array(array('apple' => 2, 'banana' => 3), array('apple' => 10, 'banana' => 12));
     * $banana = Common::arrayFlatten($fruit, 'banana');
     * print_r($banana);
     * //outputs: array(0 => 3, 1 => 12);
     * ?>
     * </code>
     *
     * @access public
     * @param array $value 被处理的数组
     * @param string $key 需要抽取的键值
     * @return array
     */
    public function array_admin_id($s, $e) {
        $sql = "select admin_id from " . $this->table('admin') . " where admin_id>" . $s . " and admin_id<" . $e;
        $query = $this->db->query($sql);
        $arr = $query->result_array();
        $arr_key = array();
        foreach ($arr as $result) {
            $arr_key[] = $result['admin_id'];
        }
        //        $str_key=implode($arr_key,',');
        return $arr_key;
    }
    public static function array_flatten($value = array(), $key) {
        $result = array();
        if ($value) {
            foreach ($value as $inval) {
                if (is_array($inval) && isset($inval[$key])) {
                    $result[] = $inval[$key];
                } else {
                    break;
                }
            }
        }
        return $result;
    }
    /**
     * 格式化metas输出
     *
     * @access public
     * @param array - $metas metas内容数组
     * @param string - $split 分割符
     * @param boolean - $link 是否输出连接
     * @return string - 格式化输出
     */
    public static function format_metas($metas = array(), $split = ',', $link = true) {
        $format = '';
        if ($metas) {
            $result = array();
            foreach ($metas as $meta) {
                $result[] = $link ? '<a href="' . site_url($meta['type'] . '/' . $meta['slug']) . '">' . $meta['name'] . '</a>' : $meta['name'];
            }
            $format = implode($split, $result);
        }
        return $format;
    }
    // 获取手机归属地
    public function get_area_phone($phone) {
        if (empty($phone)) {
            return 1;
        }
        $xml = file_get_contents("http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=" . $phone);
        $area = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $area;
    }
    public function phone_check($phone) {
        if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$/", $phone)) {
            return true;
        }
        return false;
    }
}
/**
 * 获取机器网卡的物理（MAC）地址
 * 目前支持WIN/LINUX系统
 *
 */
class MacAddInfo {
    var $return_array = array(); // 返回带有MAC地址的字串数组
    var $mac_addr;
    function MacAddInfo($os_type) {
        switch (strtolower($os_type)) {
            case "linux":
                $this->forLinux();
            break;
            case "solaris":
            break;
            case "unix":
            break;
            case "aix":
            break;
            default:
                $this->forWindows();
            break;
        }
        $temp_array = array();
        foreach ($this->return_array as $value) {
            if (preg_match("/[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f][:-]" . "[0-9a-f][0-9a-f]/i", $value, $temp_array)) {
                $this->mac_addr = $temp_array[0];
                break;
            }
        }
        unset($temp_array);
        return $this->mac_addr;
    }
    function forWindows() {
        @exec("ipconfig /all", $this->return_array);
        if ($this->return_array) return $this->return_array;
        else {
            $ipconfig = $_SERVER["WINDIR"] . "/system32/ipconfig.exe";
            if (is_file($ipconfig)) @exec($ipconfig . " /all", $this->return_array);
            else @exec($_SERVER["WINDIR"] . "/system/ipconfig.exe /all", $this->return_array);
            return $this->return_array;
        }
    }
    function forLinux() {
        @exec("ifconfig -a", $this->return_array);
        return $this->return_array;
    }
}
