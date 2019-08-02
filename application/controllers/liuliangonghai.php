<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 留联公海控制器
 * User: Administrator
 * Date: 2019/4/6
 * Time: 14:31
 */
class Liuliangonghai extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gonghai_model');
        $this->load->model('Order_model');
        $this->lang->load('order');
        $this->lang->load('common');
        $this->model = $this->Gonghai_model;
    }

    /**
     * 日期合法校验
     **/
    private function dateCheck($data)
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

    /**
     * 列表页
     */
    public function llindex()
    {
        $data = array();
        $data = $this->common->config('llindex');
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';//日期
        $hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;//预约医院编号
        $keshi_id = isset($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0;//科室编号
        $patient_name = isset($_REQUEST['p_n']) ? trim($_REQUEST['p_n']) : '';//病人名称
        $patient_phone = isset($_REQUEST['p_p']) ? trim($_REQUEST['p_p']) : '';//病人手机电话
        $order_no = isset($_REQUEST['o_o']) ? trim($_REQUEST['o_o']) : '';//预约编号
        $asker_name = isset($_REQUEST['a_i']) ? trim($_REQUEST['a_i']) : '';
        $order_type = isset($_REQUEST['t']) ? intval($_REQUEST['t']) : 2;
        $p_qq = isset($_REQUEST['p_qq']) ? trim($_REQUEST['p_qq']) : '';
        $p_wx = isset($_REQUEST['p_wx']) ? trim($_REQUEST['p_wx']) : '';

        $data['hos_id'] = $hos_id;
        $data['keshi_id'] = $keshi_id;
        $data['p_n'] = $patient_name;
        $data['p_p'] = $patient_phone;
        $data['o_o'] = $order_no;
        $data['a_i'] = $asker_name;
        $data['t'] = $order_type;
        $data['p_qq'] = $p_qq;
        $data['p_wx'] = $p_wx;

        //判断日期是否为空
        if (!empty($date))//对查询的两个时间进行处理
        {
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
            $start = substr($start, 0, -1);//给起始加一个空格
            $end = substr($end, 0, -1);
            $data['start_date'] = $start;
            $data['end_date'] = $end;
            setcookie('start_time', strtotime($start));
            setcookie('end_time', strtotime($end));
        } else {
            if (isset($_COOKIE['start_time'])) {
                $start = date("Y-m-d", $_COOKIE['start_time']);
                $end = date("Y-m-d", $_COOKIE['end_time']);
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
                $data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
            } else {
                $start = date("Y-m-d", time());
                $end = date("Y-m-d", time());
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", time());
                $data['end'] = date("Y年m月d日", time());
            }
        }

        //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表
        $hospital = $this->model->hospital_order_list();
        $keshi = $this->model->keshi_order_list();
        $keshi_arr = array();
        //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
        foreach ($keshi as $val) {
            $keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
            $keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
            $keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
            $keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
            $keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
            $keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
        }

        $from_list = $this->model->from_order_list(-1);
        $par_from_arr = array();
        $from_arr = array();//生成二维数组，把每条记录作为数组存进$from_arr
        foreach ($from_list as $val) {
            if ($val['parent_id'] == 0) {
                $par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            } else {
                $from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            }
        }
        $from_list = $par_from_arr;

        $jibing = read_static_cache('jibing_list');
        $jibing_list = array();
        $jibing_parent = array();
        if (!empty($jibing)) {
            foreach ($jibing as $val) {
                $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                if ($val['jb_level'] == 2) {
                    $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                    $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                }
            }
        }

        $data['from_arr'] = $from_arr;
        $data['keshi'] = $keshi_arr;
        $data['jibing'] = $jibing_list;
        $data['jibing_parent'] = $jibing_parent;

        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $data['now_page'] = $page;
        $per_page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 30;
        $per_page = empty($per_page) ? 30 : $per_page;
        $this->load->library('pagination');
        $this->load->helper('page');//调用CI自带的page分页类

        /* 处理判断条件 */
        $where = 1;
        $orderby = '';
        if (empty($hos_id)) {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
            }
        } else {
            $where .= ' AND o.hos_id = ' . $hos_id;
        }

        if (empty($keshi_id)) {
            if (!empty($_COOKIE["l_keshi_id"])) {
                $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
            }
        } else {
            $where .= ' AND o.keshi_id = ' . $keshi_id;
        }

        if (!empty($asker_name)) {
            $where .= " AND o.admin_name = '" . $asker_name . "'";
        }

        $w_start = strtotime($start . ' 00:00:00');
        $w_end = strtotime($end . ' 23:59:59');

        /* 时间条件 */
        if ($order_type == 1) {//预约登记时间
            $where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
            $orderby .= ',o.order_addtime DESC ';
        } elseif ($order_type == 2) {
            //获取掉入公海的order_id
            $max_today = strtotime(date('Y-m-d 23:59:59'), time());
            if ($w_start <= $max_today) {
                $sql_dump = " select t.order_id from henry_gonghai_liulian_log as t where t.action_time=(select max(t1.action_time) from henry_gonghai_liulian_log as t1 where t.order_id = t1.order_id and t1.action_type = 0) and t.action_time >= " . $w_start . " and t.action_time <= " . $w_end;
                $dump_res = $this->common->getAll($sql_dump);
                //p($dump_res);
                if (!empty($dump_res)) {
                    $dump_order_id = '';
                    foreach ($dump_res as $key => $value) {
                        $dump_order_id .= $value['order_id'] . ',';
                    }
                    $dump_order_id = substr($dump_order_id, 0, -1);
                    $where .= " AND o.order_id IN (" . $dump_order_id . ")";
                } else {
                    $where .= " AND o.order_id IN ('')";
                }
            } else {
                $where .= " AND o.order_id IN ('')";
            }
        }

        //查询回访人员
        $hf_name = isset($_REQUEST['a_h'])?$_REQUEST['a_h']:'';
        $data['a_h'] = $hf_name;
        if (!empty($hf_name)) {
            $order_remark = $this->common->getAll("select DISTINCT order_id from " . $this->common->table('order_remark') . " where admin_name = '" . $hf_name . "' and  mark_type = 3   AND mark_time between " . $w_start . " AND " . $w_end);
            $order_remark_order_no_str = '';
            foreach ($order_remark as $order_remark_temp) {
                if (empty($order_remark_order_no_str)) {
                    $order_remark_order_no_str = $order_remark_temp['order_id'];
                } else {
                    $order_remark_order_no_str .= ',' . $order_remark_temp['order_id'];
                }
            }
            if (!empty($order_remark_order_no_str)) {
                $where = " o.order_id in(" . $order_remark_order_no_str . ")";
            } else {
                $w_start = strtotime(date("Y-m-d", time()) . ' 00:00:00');
                $w_end = strtotime(date("Y-m-d", time()) . ' 23:59:59');
                $where = " o.order_id in(0)";
            }
        } else {
            /* 当输入患者的信息时，其他的搜索条件都不需要了 */
            if (!empty($patient_name)) {

                $where = " p.pat_name = '" . $patient_name . "'";
                $data['p_n'] = $patient_name;
                $data['p_p'] = "";
                $data['o_o'] = "";
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }

                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($patient_phone)) {
                $where = " (p.pat_phone = '" . $patient_phone . "' OR p.pat_phone1 = '" . $patient_phone . "')";
                $data['p_n'] = "";
                $data['p_p'] = $patient_phone;
                $data['o_o'] = "";
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($order_no)) {
                $where = " o.order_no = '" . $order_no . "'";
                $data['p_n'] = "";
                $data['p_p'] = "";
                $data['o_o'] = $order_no;
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }

                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($p_qq)) {
                $where = " p.pat_qq = '" . $p_qq . "'";
                $data['p_n'] = "";
                $data['p_p'] = "";
                $data['p_wx'] = "";
                $data['o_o'] = "";
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($p_wx)) {
                $where = " p.pat_weixin = '" . $p_wx . "'";
                $data['p_n'] = "";
                $data['p_p'] = "";
                $data['p_qq'] = "";
                $data['o_o'] = "";
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

        }

        if ($orderby == '') {
            $orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';
        } else {
            $orderby = substr($orderby, 1);//删除orderby中的","
            $orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
        }

        //获取相关数据
        $sql = "SELECT COUNT(*) AS count from henry_gonghai_liulian as o LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id WHERE $where";
        $order_count = $this->common->getRow($sql);

        $config = page_config();
        $config['base_url'] = '?c=liuliangonghai&m=llindex&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&a_h=' . $data['a_h'];
        $config['total_rows'] = $order_count['count'];//总记录数
        $config['per_page'] = $per_page;//每页记录数
        $config['uri_segment'] = 10;
        $config['num_links'] = 5;

        if (empty($dump_res) && $order_type == 2) {
            $order_list = array();
        } else {
            $this->pagination->initialize($config);
            $order_list = $this->getLiulianData($where, $page, $per_page, $orderby);
            //p($order_count);die();
        }
        //p($this->db->last_query());die();
        //回访和留言
        $order_id_str = 0 ;
        foreach($order_list  as $order_list_data){
            if($order_id_str == 0){
                $order_id_str = $order_list_data['order_id'];
            }else{
                $order_id_str =  $order_id_str.','.$order_list_data['order_id'];
            }
        }

        $data['order_remark_list'] = $this->common->getAll("SELECT * FROM   " . $this->common->table('order_liulian_remark') . "  WHERE  order_id in(".$order_id_str.")  order by mark_time desc");

        $order_hf_lists = array();
        foreach ($data['order_remark_list'] as $key => $value) {
            if ($value['mark_type'] == 3) {
                $order_hf_lists[$value['order_id']][] = $value;
            }
        }

        $rank_type = $this->model->rank_type();
        $area = $this->model->area();
        $province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
        $flag_1 = array();
        $flag_2 = array();
        foreach ($province as $val) {
            if (trim($val['region_name']) == '广东' || trim($val['region_name']) == '浙江') {
                $flag_1[] = $val;
            } else {
                $flag_2[] = $val;
            }
        }
        $province_list = array_merge($flag_1, $flag_2);

        $data['rank_type'] = $this->model->rank_type();
        $data['area'] = $area;
        $data['all_count'] = $order_count['count'];
        $data['province'] = $province_list;
        $data['page'] = $this->pagination->create_links();
        $data['per_page'] = $per_page;
        $data['order_list'] = $order_list;
        $data['from_list'] = $from_list;
        $data['hospital'] = $hospital;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);

        $this->load->view('liulian/gonghai', $data);
    }

    /**
     * 获取公海留联数据
     * @param $where
     * @param int $first
     * @param int $size
     * @param $orderby
     * @return array
     */
    private function getLiulianData($where, $first = 0, $size = 20, $orderby)
    {

        $sql = "SELECT o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, o.con_content
                FROM henry_gonghai_liulian as o
                LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
                WHERE $where  " .$orderby . " LIMIT $first, $size";
        $row = $this->common->getAll($sql);
        $arr = array();
        //重新把获取到的信息存进二维数组$arr中
        foreach($row as $val)
        {
            $arr[$val['order_id']]['order_id'] = $val['order_id'];
            $arr[$val['order_id']]['order_no'] = $val['order_no'];
            $arr[$val['order_id']]['is_first'] = $val['is_first'];
            $arr[$val['order_id']]['pat_name'] = $val['pat_name'];
            $arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
            $arr[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
            if($val['pat_sex'] == 1)
            {
                $arr[$val['order_id']]['pat_sex'] = $this->lang->line('man');
            }
            else
            {
                $arr[$val['order_id']]['pat_sex'] = $this->lang->line('woman');
            }

            $arr[$val['order_id']]['pat_age'] = $val['pat_age'];
            $arr[$val['order_id']]['pat_province'] = $val['pat_province'];
            $arr[$val['order_id']]['pat_city'] = $val['pat_city'];
            $arr[$val['order_id']]['pat_area'] = $val['pat_area'];
            $arr[$val['order_id']]['pat_qq'] = $val['pat_qq'];
            $arr[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
            $arr[$val['order_id']]['pat_blacklist'] = $val['pat_blacklist'];
            $arr[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
            if(!empty($val['order_time']))
            {
                $arr[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
            }
            else
            {
                $arr[$val['order_id']]['order_time'] = $val['order_null_time'];
            }
            $arr[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];
            $arr[$val['order_id']]['come_time'] = $val['come_time'];
            $arr[$val['order_id']]['doctor_time'] = $val['doctor_time'];
            $arr[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];
            $arr[$val['order_id']]['from_id'] = $val['from_id'];
            $arr[$val['order_id']]['hos_id'] = $val['hos_id'];
            $arr[$val['order_id']]['keshi_id'] = $val['keshi_id'];
            $arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
            $arr[$val['order_id']]['jb_id'] = $val['jb_id'];
            $arr[$val['order_id']]['order_type'] = $val['order_type'];
            $arr[$val['order_id']]['admin_name'] = $val['admin_name'];
            $arr[$val['order_id']]['pat_id'] = $val['pat_id'];
            $arr[$val['order_id']]['doctor_name'] = $val['doctor_name'];
            $arr[$val['order_id']]['admin_id'] = $val['admin_id'];
            $arr[$val['order_id']]['from_value'] = $val['from_value'];
            $arr[$val['order_id']]['con_content'] = $val['con_content'];
        }
        return $arr;
    }

    /**
     * 定时掉入公海
     */
    public function drop_gonghai_task()
    {

        //东莞
        $where = " o.hos_id = 6 ";


        // 公海条件
        // 1、首次登记3天无有效回访备注，7天无二次有效回访，流入留联公海；
        // 2、登记30天内未转预约，流入留联公海；

        $time = time();

        //登记超过30天未转预约(不管有无回访但先排除从公海捞出的)，流入留联公海
        $exe_time = strtotime(date('Y-m-d 00:00:00',$time - 24*60*60*30));
        $end_time = strtotime(date('Y-m-d 23:59:59',$time - 24*60*60*1));

        $sql = "select order_id from hui_order_liulian as o where $where and o.order_no_yy is null and o.order_addtime < $exe_time";
        $exceed30_ids_res = $this->common->getAll($sql);

        //公海捞出的患者
        $sql = " select t.order_id from henry_gonghai_liulian_log as t where t.action_time=(select max(t1.action_time) from henry_gonghai_liulian_log as t1 where t.order_id = t1.order_id and t1.action_type = 1) AND t.order_id IN (select order_id from hui_order_liulian as o where $where and o.order_no_yy is null)";
        $laoqu_ids_res = $this->common->getAll($sql);
        $laoqu_ids = array();
        if (!empty($laoqu_ids_res)) {
            foreach ($laoqu_ids_res as $value) {
                $laoqu_ids[] = $value['order_id'];
            }
        }

        $exceed30_ids = array();
        if (!empty($exceed30_ids_res)) {
            foreach ($exceed30_ids_res as $value) {
                if (!in_array($value['order_id'],$laoqu_ids)){
                    $exceed30_ids[] = $value['order_id'];
                }
            }
        }

        //p($this->db->last_query());die();
        //p($exceed30_ids);die();

        //登记30天内未转预约
        $sql = "select order_id from hui_order_liulian as o where $where and o.order_no_yy is null AND o.order_addtime between " . $exe_time . " AND " . $end_time;
        $under30_ids_res = $this->common->getAll($sql);
        $under30_ids = array();
        if (!empty($under30_ids_res)) {
            foreach ($under30_ids_res as $value) {
                $under30_ids[] = $value['order_id'];
            }
        }

        //p($under30_ids);die();

        if (!empty($under30_ids)){
            $under30_ids_str = implode(',',$under30_ids);
        } else {
            $under30_ids_str = 0;
        }

        //登记30天内未转预约 是否有回访
        //有回访
        $sql = "SELECT DISTINCT order_id FROM hui_order_liulian_remark WHERE mark_type =3 AND ORDER_ID IN (".$under30_ids_str.")";
        $called_ids_res = $this->common->getAll($sql);
        if (!empty($called_ids_res)) {
            foreach ($called_ids_res as $value) {
                $called_ids[] = $value['order_id'];
            }
        }

        //未回访
        $no_called_ids = array_values(array_diff($under30_ids,$called_ids));

        if (!empty($no_called_ids)){
            $no_called_ids_str = implode(',',$no_called_ids);
        } else {
            $no_called_ids_str = 0;
        }

        //未回访按登记时间超过3天的
        $exe_time = strtotime(date('Y-m-d 00:00:00',$time - 24*60*60*3));
        $sql = "select order_id from hui_order_liulian as o where $where AND order_id IN (".$no_called_ids_str.") and o.order_addtime < $exe_time";
        $no_called_exceed3_ids_res = $this->common->getAll($sql);
        $no_called_exceed3_ids = array();
        if (!empty($no_called_exceed3_ids_res)) {
            foreach ($no_called_exceed3_ids_res as $value) {
                $no_called_exceed3_ids[] = $value['order_id'];
            }
        }

        //p($no_called_exceed3_ids);die();

        //已回访但距最近一次回访超过7天的
        $exe_time = strtotime(date('Y-m-d 00:00:00',$time - 24*60*60*7));
        if (!empty($called_ids)){
            $called_ids_str = implode(',',$called_ids);
        } else {
            $called_ids_str = 0;
        }
        $called_exceed7_ids_res = $this->common->getAll("SELECT order_id FROM hui_order_liulian_remark as t WHERE t.mark_type =3 and t.mark_time < $exe_time and  t.mark_time = (SELECT max(mark_time) FROM hui_order_liulian_remark WHERE order_id = t.order_id ) AND t.order_id IN (".$called_ids_str.")");
        $called_exceed7_ids = array();
        if (!empty($called_exceed7_ids_res)) {
            foreach ($called_exceed7_ids_res as $value) {
                $called_exceed7_ids[] = $value['order_id'];
            }
        }

        //p($called_exceed3_ids);die();

        //捞取的患者按捞取时间超过十天未转预约
        $exe_time = strtotime(date('Y-m-d 00:00:00',$time - 24*60*60*10));
        $sql = " select t.order_id from henry_gonghai_liulian_log as t where t.action_time=(select max(t1.action_time) from henry_gonghai_liulian_log as t1 where t.order_id = t1.order_id and t1.action_type = 1) and t.action_time < " . $exe_time . " AND t.order_id IN (select order_id from hui_order_liulian as o where $where and o.order_no_yy is null)";
        $laoqu_exceed10_ids_res = $this->common->getAll($sql);
        $laoqu_exceed10_ids = array();
        if (!empty($laoqu_exceed10_ids_res)) {
            foreach ($laoqu_exceed10_ids_res as $value) {
                $laoqu_exceed10_ids[] = $value['order_id'];
            }
        }
        //p($this->db->last_query());

        //掉公海order_id
        $droptosea_ids = array_merge($exceed30_ids,$no_called_exceed3_ids,$called_exceed7_ids);
        //p(count($droptosea_ids));die();

        //不掉公海order_id
        //未回访但没超过3天的
        $no_called_under3_ids = array_diff($no_called_ids,$no_called_exceed3_ids);
        //回访但没超过7天的
        $called_under7_ids = array_diff($called_ids,$called_exceed7_ids);
        //不掉数据
        $normal_ids = array_merge($no_called_under3_ids,$called_under7_ids);

        /*echo count($exceed30_ids);
        echo "<br>";
        echo count($under30_ids);
        echo "<br>";
        echo count(array_merge($no_called_exceed3_ids,$called_exceed7_ids));
        echo "<br>";
        echo count($normal_ids);
        echo "<br>";
        echo count($exceed30_ids)+count($under30_ids)+count(array_merge($no_called_exceed3_ids,$called_exceed7_ids))+count($normal_ids);
        p($normal_ids);die();*/

        $drop_data = array();
        //与配置文件留联规则对应起来
        $drop_data[1] = $exceed30_ids;//登记时间超过30天
        $drop_data[2] = $no_called_exceed3_ids;//无回访超过3天掉
        $drop_data[3] = $called_exceed7_ids;//回访超过7天掉
        $drop_data[4] = $laoqu_exceed10_ids;//捞取时间超过十天未转预约

        //p($drop_data);die();

        $all_dump = array_merge($exceed30_ids,$no_called_exceed3_ids,$called_exceed7_ids,$laoqu_exceed10_ids);
        //p($all_dump);die();

        if (empty($all_dump)){
            exit("There's no data to drop down!");
        }

        if ($this->drop_gonghai_liulian($drop_data)) {
            exit("It had been finished!");
        } else {
            exit("It had been failed!");
        }

    }

    /**
     * 往公海插入数据
     * @param $drop_data
     * @return bool
     */
    private function drop_gonghai_liulian($drop_data)
    {
        $this->db->trans_begin();

        $time = strtotime(date('Y-m-d H:i:s'));

        foreach ($drop_data as $value) {
            foreach ($value as $v) {
                $al[] = $v;
            }
        }

        $liulian_expired_ids_str = implode(',',$al);

        //p($liulian_expired_ids_str);die();

        try {
            if (!empty($liulian_expired_ids_str)) {
                $sql = "insert into henry_gonghai_liulian (order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,laiyuanweb,remark,guanjianzi,con_content) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,laiyuanweb,remark,guanjianzi,con_content from " . $this->common->table('order_liulian') . " where order_id in(" . $liulian_expired_ids_str . ")";
                $delete_sql = "delete from " . $this->common->table('order_liulian') . " where order_id in(" . $liulian_expired_ids_str . ")";
                if ($this->db->query($sql)) {
                    if ($this->db->query($delete_sql)) {

                        $part_sql = '';
                        foreach ($drop_data as $key => $value) {
                            foreach ($value as $order_id) {
                                $part_sql .= "($order_id,0,0,$key,$time),";
                            }
                        }
                        $part_sql = substr($part_sql,0,-1);
                        $sql = "insert into henry_gonghai_liulian_log (order_id,admin_id,action_type,action_rules,action_time) values ".$part_sql;
                        if (!$this->db->query($sql)) {
                            throw new Exception ('插入公海留联日志失败！');
                        }

                    } else {
                        throw new Exception ('删除相应留联失败！');
                    }
                } else {
                    throw new Exception ('数据掉公海失败！');
                }

            } else {
                $this->dropLog('没有需要掉公海的留联！');
            }
        } catch (Exception $exception) {
            if ($this->db->trans_rollback()) {
                $this->dropLog($exception->getMessage().' 回滚成功！');
            } else {
                $this->dropLog($exception->getMessage().' 回滚失败！');
            }
            return false;
        }
        $this->db->trans_commit();

        $exceed30_ids_sum = count($drop_data[1]);
        $no_called_exceed3_ids_sum = count($drop_data[2]);
        $called_exceed7_ids_sum = count($drop_data[3]);
        $laoqu_exceed10_ids = count($drop_data[4]);
        $num = $exceed30_ids_sum + $no_called_exceed3_ids_sum + $called_exceed7_ids_sum + $laoqu_exceed10_ids;
        $this->dropLog('执行成功 '.$num.' 条！(登记时间超过30天:'.$exceed30_ids_sum.';无回访超过3天掉:'.$no_called_exceed3_ids_sum.';回访超过7天掉:'.$called_exceed7_ids_sum.';捞取时间超过十天未转预约:'.$laoqu_exceed10_ids.')');
        return true;

    }

    /**
     * 捞取公海留联
     */
    public function fetch()
    {
        $data = $this->common->config('fetch');

        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        if (empty($_COOKIE['l_admin_name'])) {
            $_COOKIE['l_admin_name'] = '';
        }
        if (empty($_COOKIE['l_admin_name']) || empty($_COOKIE['l_admin_id'])) {
            $this->common->msg('COOKIE失效,请重新登录账户', 1);
        } else {
            $order_id_data = $this->common->getOne("SELECT order_id FROM henry_gonghai_liulian WHERE order_id = $order_id");
            if (empty($order_id_data)) {
                $this->common->msg('数据已经不在公海。返回原页面！', 1);
            } else {
                /*if (strcmp($_COOKIE["l_admin_action"], 'all') != 0) {
                    $l_admin_action = explode(",", $_COOKIE["l_admin_action"]);
                    if (!in_array(184, $l_admin_action)) {
                        $this->common->msg('你没有捞取公海数据的权限。返回原页面！', 1);
                    }
                }*/

                //更新公海中的相关数据
                $admin_name = $_COOKIE['l_admin_name'];
                $admin_id = $_COOKIE['l_admin_id'];

                $this->db->trans_begin();
                try {
                    if($this->db->query("update henry_gonghai_liulian set  admin_id=".$admin_id.",admin_name='".$admin_name."' where order_id=".$order_id)){
                        //插入更新记录
                        if($this->db->query("INSERT INTO `henry_gonghai_liulian_log` (`order_id`, `action_type`, `admin_id`, `action_time`) VALUES ($order_id, 1, $admin_id, ".time().")")){
                            //查询公海订单数据
                            $sql = "SELECT * FROM henry_gonghai_liulian where order_id=".$order_id;
                            $order_query_row = $this->common->getRow($sql);

                            if(empty($order_query_row['order_id'])){$order_query_row['order_id']=0;}
                            if(empty($order_query_row['order_no'])){$order_query_row['order_no']='';}
                            if(empty($order_query_row['is_first'])){$order_query_row['is_first']=0;}
                            if(empty($order_query_row['admin_id'])){$order_query_row['admin_id']=0;}
                            if(empty($order_query_row['admin_name'])){$order_query_row['admin_name']='';}
                            if(empty($order_query_row['pat_id'])){$order_query_row['pat_id']=0;}
                            if(empty($order_query_row['from_parent_id'])){$order_query_row['from_parent_id']=0;}
                            if(empty($order_query_row['from_id'])){$order_query_row['from_id']=0;}
                            if(empty($order_query_row['from_value'])){$order_query_row['from_value']='';}
                            if(empty($order_query_row['hos_id'])){$order_query_row['hos_id']=0;}
                            if(empty($order_query_row['keshi_id'])){$order_query_row['keshi_id']=0;}
                            if(empty($order_query_row['type_id'])){$order_query_row['type_id']=0;}
                            if(empty($order_query_row['jb_parent_id'])){$order_query_row['jb_parent_id']=0;}
                            if(empty($order_query_row['jb_id'])){$order_query_row['jb_id']=0;}
                            if(empty($order_query_row['order_addtime'])){$order_query_row['order_addtime']=0;}
                            if(empty($order_query_row['order_time'])){$order_query_row['order_time']=0;}
                            if(empty($order_query_row['order_null_time'])){$order_query_row['order_null_time']='';}
                            if(empty($order_query_row['doctor_time'])){$order_query_row['doctor_time']=0;}
                            if(empty($order_query_row['doctor_id'])){$order_query_row['doctor_id']=0;}
                            if(empty($order_query_row['doctor_name'])){$order_query_row['doctor_name']='';}
                            if(empty($order_query_row['come_time'])){$order_query_row['come_time']=0;}
                            if(empty($order_query_row['order_time_duan'])){$order_query_row['order_time_duan']='';}
                            if(empty($order_query_row['duan_confirm'])){$order_query_row['duan_confirm']=0;}
                            if(empty($order_query_row['is_come'])){$order_query_row['is_come']=0;}
                            $order_query_row['laiyuanweb'] = !empty($order_query_row['laiyuanweb']) ? addslashes($order_query_row['laiyuanweb']):'';
                            $order_query_row['remark'] = !empty($order_query_row['remark']) ? addslashes($order_query_row['remark']):'';
                            $order_query_row['guanjianzi'] = !empty($order_query_row['guanjianzi']) ? addslashes($order_query_row['guanjianzi']):'';
                            $order_query_row['con_content'] = !empty($order_query_row['con_content']) ? addslashes($order_query_row['con_content']):'';

                            $insert_str = ''.

                                "".$order_query_row['order_id'].",".
                                "'".$order_query_row['order_no']."',".
                                "".$order_query_row['is_first'].",".
                                "".$order_query_row['admin_id'].",".
                                "'".$order_query_row['admin_name']."',".
                                "".$order_query_row['pat_id'].",".
                                "".$order_query_row['from_parent_id'].",".
                                "".$order_query_row['from_id'].",".
                                "'".$order_query_row['from_value']."',".
                                "".$order_query_row['hos_id'].",".
                                "".$order_query_row['keshi_id'].",".
                                "".$order_query_row['type_id'].",".
                                "".$order_query_row['jb_parent_id'].",".
                                "".$order_query_row['jb_id'].",".
                                "".$order_query_row['order_addtime'].",".
                                "".$order_query_row['order_time'].",".
                                "'".$order_query_row['order_null_time']."',".
                                "".$order_query_row['doctor_time'].",".
                                "".$order_query_row['doctor_id'].",".
                                "'".$order_query_row['doctor_name']."',".
                                "".$order_query_row['come_time'].",".
                                "'".$order_query_row['order_time_duan']."',".
                                "".$order_query_row['duan_confirm'].",".
                                "".$order_query_row['is_come'].",".
                                "'".$order_query_row['laiyuanweb']."',".
                                "'".$order_query_row['remark']."',".
                                "'".$order_query_row['guanjianzi']."',".
                                "'".$order_query_row['con_content']."'";
                            $query1="insert into ".$this->common->table('order_liulian')." (order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,laiyuanweb,remark,guanjianzi,con_content) values(".$insert_str.")";
                            if($this->db->query($query1)){
                                if (!$this->db->query("delete from henry_gonghai_liulian where order_id=".$order_id)) {
                                    throw new Exception('删除公海留联失败！');
                                }
                            } else {
                                throw new Exception('插入记录失败！');
                            }
                        }  else {
                            throw new Exception('插入LOG失败！');
                        }
                    } else {
                        throw new Exception('更新记录失败！');
                    }
                } catch (Exception $exception) {
                    $this->db->trans_rollback();
                    P($this->db->last_query());
                    $this->common->msg($exception->getMessage(), 1);
                }
                $this->db->trans_commit();
                echo "<script>window.location.href = '?c=order&m=order_list_person_liulian';</script>";
            }
        }
    }

    //显示公海日志
    public function log()
    {
        $order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
        $sql = "select a.*,b.admin_name from henry_gonghai_liulian_log as a left join hui_admin as b on a.admin_id = b.admin_id where a.order_id=".$order_id." order by a.id desc";
        $logData = $this->common->getAll($sql);
        $data['gonghai_log'] = $logData;
        $this->load->view('liulian/log.php', $data);
    }

    /**
     * 掉公海日志
     * @param $msg
     * @return bool
     */
    private function dropLog($msg)
    {
        $time = date('Y-m-d H:i:s');
        $sql = "insert into henry_gonghai_liulian_exe_log (msg,exe_time) values ('$msg','$time')";
        $this->db->query($sql);
        return true;
    }

}