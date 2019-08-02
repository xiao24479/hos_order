<?php

/**
 * 预约报表统计
 * User: Henry
 * Date: 2018/5/15
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller
{
    var $model;
    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->model = $this->Order_model;
    }

    /**
     日期合法校验
     *
     */
    public function dateCheck($data) {
        $data = str_replace(array(
            "年",
            "月"
        ) , "-", $data);
        $data = str_replace(array(
            "日"
        ) , "", $data);
        $is_date = strtotime($data) ? strtotime($data) : false;
        if ($is_date === false) {
            return false;
        } else {
            return true;
        }
    }

    public function rindex(){
        $data = array();
        $data = $this->common->config('rindex');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : ''; //日期
        $hos_id = isset($_REQUEST['hos_id']) ? trim($_REQUEST['hos_id']) : '';
        $admin_name = isset($_REQUEST['admin_name']) ? trim($_REQUEST['admin_name']) : '';
        $group = isset($_REQUEST['group']) ? trim($_REQUEST['group']) : '';
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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            $w_start = strtotime(date('Y-m-d',time()) . ' 00:00:00');
            $w_end = strtotime(date('Y-m-d',time()) . ' 23:59:59');
            $data['start'] = date('Y年m月d日',time());
            $data['end'] = date('Y年m月d日',time());
        }

        //获取本月日期
        if (isset($start) && !empty($start) && strcmp($start, $end) == 0) {
            //是否只选择了一天
            //起始时间为当前选择日期的一号到当前选择时间
            //以开始时间为准

            $timestamp=strtotime($start);

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

        } elseif ( isset($start) && !empty($start) && strcmp($start, $end) != 0 ) {
            //以结束时间为准
            $timestamp=strtotime($end);

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

        } else {
            //当月起始时间
            $cur_month_start=date('Y',time()).'-'.(date('m',time())).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');
        }


        $where = " where 1";
        $where1 = " where 1";
        $where2 = " where 1";
        $where3 = " where 1";


        if (!empty($admin_name)) {
            $where.= " AND a.admin_name = '".$admin_name."'";
            $where1.= " AND a.admin_name = '".$admin_name."'";
            $where3.= " AND a.admin_name = '".$admin_name."'";
        }

        if (!empty($group)) {
            //台州男科下载
            if ($group == '99999') {
                $where.= " AND c.id in (106,107,108,121)";
                $where1.= " AND c.id in (106,107,108,121)";
                $where3.= " AND c.id in (106,107,108,121)";
            } else {
                $where.= " AND c.id = '".$group."'";
                $where1.= " AND c.id = '".$group."'";
                $where3.= " AND c.id = '".$group."'";
            }
        }

        if (!empty($hos_id)) {
            $where.= " AND a.hos_id = '".$hos_id."' AND c.hos_id = '".$hos_id."'";
            $where1.= " AND hos_id = '".$hos_id."'";
            $where2.= " AND a.hos_id = '".$hos_id."'";
            $where3.= " AND c.hos_id = '".$hos_id."'";
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where.= " AND a.hos_id IN (" . $_COOKIE["l_hos_id"] . ") AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where1.= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where2.= " AND a.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where3.= " AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where1.= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }

        }

        //登记
        $sql_reg = "SELECT a.admin_id,a.admin_name,a.hos_id,count(*) AS reg_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND  a.order_addtime between " . $w_start . " AND " . $w_end . "  GROUP BY a.admin_id ORDER BY reg_num DESC";
        // echo $sql_reg;exit();
        $res_reg = $this->common->getAll($sql_reg);

        $list_reg = array();
        foreach ($res_reg as $val) {
            $list_reg[$val['admin_id']][] = $val;
        }
        foreach ($list_reg as $key => $val) {
            $des[$key]['reg_num'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['reg_num']+= $v['reg_num'];
            }
        }

        //预到
        $sql_order = "SELECT a.admin_id,a.admin_name,a.hos_id,count(*) AS order_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND  a.order_time between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY order_num DESC";

        $res_order = $this->common->getAll($sql_order);

        $list_order = array();
        foreach ($res_order as $val) {
            $list_order[$val['admin_id']][] = $val;
        }
        foreach ($list_order as $key => $val) {
            $des[$key]['order_num'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['order_num']+= $v['order_num'];
            }
        }


        //到诊
        $sql_come = "SELECT a.admin_id,a.admin_name,a.hos_id,count(*) AS come_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND a.is_come=1 AND a.come_time between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY come_num DESC";

        $res_come = $this->common->getAll($sql_come);

        $list_come = array();
        foreach ($res_come as $val) {
            $list_come[$val['admin_id']][] = $val;
        }

        foreach ($list_come as $key => $val) {
            $des[$key]['come_num'] = 0;
            foreach ($val as $k => $v) {
                $des[$key]['admin_id'] = $v['admin_id'];
                $des[$key]['admin_name'] = $v['admin_name'];
                $des[$key]['come_num']+= $v['come_num'];
            }
        }

        // $sql_dia = "SELECT a.admin_id,a.admin_name,sum(d.dialog_num) AS dialog_num,sum(d.target_num) AS target_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $w_start . " AND d.end_date <=" . $w_end ." GROUP BY a.admin_id";


        //对话数
        $sql_dia = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS dialog_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $w_start . " AND d.end_date <=" . $w_end ."  GROUP BY a.admin_id";

        $res_dia = $this->common->getAll($sql_dia);

        $ded = array();
        foreach ($res_dia as $val) {
             $ded[$val['admin_id']]['admin_id'] = $val['admin_id'];
             $ded[$val['admin_id']]['admin_name'] = $val['admin_name'];
             $ded[$val['admin_id']]['dialog_num'] = $val['dialog_num'];
        }

        //获取本月目标到诊、任务到诊、任务对话
        $sql_tar = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.target_num) AS target_num,sum(d.task_come_num) AS task_come_num,sum(d.task_dialog_num) AS task_dialog_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $c_m_start . " AND d.end_date <=" . $c_m_end ."  GROUP BY a.admin_id";


        $res_tar = $this->common->getAll($sql_tar);

        foreach ($res_tar as $val) {
            $ded[$val['admin_id']]['admin_id'] = $val['admin_id'];
            $ded[$val['admin_id']]['admin_name'] = $val['admin_name'];
            $ded[$val['admin_id']]['target_num'] = $val['target_num'];
            $ded[$val['admin_id']]['task_come_num'] = $val['task_come_num'];
            $ded[$val['admin_id']]['task_dialog_num'] = $val['task_dialog_num'];
        }

        foreach ($des as $key => $val) {

            $ded[$key]['admin_id'] = $val['admin_id'];
            $ded[$key]['admin_name'] = $val['admin_name'];
            $ded[$key]['order_num'] = $val['order_num']?$val['order_num']:'0';
            $ded[$key]['reg_num'] = $val['reg_num']?$val['reg_num']:'0';
            $ded[$key]['come_num'] = $val['come_num']?$val['come_num']:'0';

        }

        //获取所选小组下面的所有咨询
        $sql_zx= "SELECT a.admin_id,a.admin_name FROM `hui_admin` as a left join `hui_order` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on a.admin_group = c.id ".$where3." GROUP BY a.admin_id";
        $res_zx = $this->common->getAll($sql_zx);
//exit($sql_zx);
        $ass = array();
        foreach ($res_zx as $val) {
             $ass[$val['admin_id']]['admin_id'] = $val['admin_id'];
             $ass[$val['admin_id']]['admin_name'] = $val['admin_name'];
        }

        foreach ($ded as $key => $val) {

            $ass[$key]['admin_id'] = $val['admin_id'];
            $ass[$key]['admin_name'] = $val['admin_name'];
            $ass[$key]['order_num'] = $val['order_num']?$val['order_num']:'0';
            $ass[$key]['reg_num'] = $val['reg_num']?$val['reg_num']:'0';
            $ass[$key]['come_num'] = $val['come_num']?$val['come_num']:'0';
            $ass[$key]['dialog_num'] = $val['dialog_num'];
            $ass[$key]['target_num'] = $val['target_num'];
            $ass[$key]['task_come_num'] = $val['task_come_num'];
            $ass[$key]['task_dialog_num'] = $val['task_dialog_num'];

        }

        $count = array();
        foreach ($ass as $key => $value) {
            $count['reg_count'] += $value['reg_num'];
            $count['order_count'] += $value['order_num'];
            $count['come_count'] += $value['come_num'];
            $count['dialog_count'] += $value['dialog_num'];
            $count['target_count'] += $value['target_num'];
            $count['task_come_num_count'] += $value['task_come_num'];
            $count['task_dialog_num_count'] += $value['task_dialog_num'];
        }

        //p($des);exit();
        $data['data_list'] = $ass;

        $data['count'] = $count;



        $sql_group = "SELECT a.id,a.name,a.hos_id,b.hos_name FROM `hui_user_groups` as a left join `hui_hospital` as b on a.hos_id = b.hos_id ".$where2;
        $res_group = $this->common->getAll($sql_group);
        $groups = array();
        foreach ($res_group as $key => $value) {
            $groups[$value['hos_name']][] = $value;
        }
        $final_groups = array();
        foreach ($groups as $key => $value) {
            foreach ($value as $k => $v) {
                $final_groups[$key][$v['name']] = $v;
                ksort($final_groups[$key]);
            }
        }
        ksort($final_groups);
        //p($final_groups);die();
        $hospital = $this->model->hospital_order_list();
        $data['hospital'] = $this->model->hospital_order_list();
        $data['groups'] = $final_groups;
        $data['hos_id'] = $hos_id;


        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $data['date'] = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $data['admin_name'] = $admin_name;

        $parse = "";

        if (!empty($data['date'])) {
            $parse .= "&date=".$data['date'];
        }
        if (!empty($data['hos_id'])) {
            $parse .= "&hos_id=".$data['hos_id'];
        }
        if (!empty($group)) {
            $parse .= "&group=".$group;
        }
        if (!empty($admin_name)) {
            $parse .= "&admin_name=".$admin_name;
        }

        $data['parse'] = $parse;
        $data['group_id'] = $group;
        $this->load->view('report/index', $data);
    }

    public function ajax_dialog_num(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $admin_id = isset($_REQUEST['aid']) ? trim(intval($_REQUEST['aid'])) : '0';
        $num = isset($_REQUEST['num']) ? trim(intval($_REQUEST['num'])) : '0';

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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            exit('0');
        }

        $arr = array(
            'admin_id' => $admin_id,
            'start_date' => $w_start,
            'end_date' => $w_end,
            'dialog_num' => $num,
            'addtime' => time(),
            'add_admin_id' => $_COOKIE['l_admin_id']
        );

        $sql = "select id from " . $this->common->table('dialog_num') . " where start_date = $w_start and end_date = $w_end and admin_id = $admin_id";

        $row = $this->common->getOne($sql);

        if ($row) {
            $id = $row;
            $this->db->update($this->db->dbprefix . "dialog_num", $arr, array(
                'id' => $id
            ));
        } else {
            $this->db->insert($this->db->dbprefix . "dialog_num", $arr);
            $id = $this->db->insert_id();
        }

        if ($id) {
            exit('1');
        } else {
            exit('2');
        }

    }

    public function ajax_target_num(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $admin_id = isset($_REQUEST['aid']) ? trim(intval($_REQUEST['aid'])) : '0';
        $num = isset($_REQUEST['num']) ? trim(intval($_REQUEST['num'])) : '0';

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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            exit('0');
        }

        $arr = array(
            'admin_id' => $admin_id,
            'start_date' => $w_start,
            'end_date' => $w_end,
            'target_num' => $num,
            'addtime' => time(),
            'add_admin_id' => $_COOKIE['l_admin_id']
        );

        $sql = "select id from " . $this->common->table('dialog_num') . " where start_date = $w_start and end_date = $w_end and admin_id = $admin_id";

        $row = $this->common->getOne($sql);

        if ($row) {
            $id = $row;
            $this->db->update($this->db->dbprefix . "dialog_num", $arr, array(
                'id' => $id
            ));
        } else {
            $this->db->insert($this->db->dbprefix . "dialog_num", $arr);
            $id = $this->db->insert_id();
        }

        if ($id) {
            exit('1');
        } else {
            exit('2');
        }

    }


    //编辑任务到诊
    public function ajax_task_come_num(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $admin_id = isset($_REQUEST['aid']) ? trim(intval($_REQUEST['aid'])) : '0';
        $num = isset($_REQUEST['num']) ? trim(intval($_REQUEST['num'])) : '0';

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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            exit('0');
        }

        $arr = array(
            'admin_id' => $admin_id,
            'start_date' => $w_start,
            'end_date' => $w_end,
            'task_come_num' => $num,
            'addtime' => time(),
            'add_admin_id' => $_COOKIE['l_admin_id']
        );

        $sql = "select id from " . $this->common->table('dialog_num') . " where start_date = $w_start and end_date = $w_end and admin_id = $admin_id";

        $row = $this->common->getOne($sql);

        if ($row) {
            $id = $row;
            $this->db->update($this->db->dbprefix . "dialog_num", $arr, array(
                'id' => $id
            ));
        } else {
            $this->db->insert($this->db->dbprefix . "dialog_num", $arr);
            $id = $this->db->insert_id();
        }

        if ($id) {
            exit('1');
        } else {
            exit('2');
        }

    }


    //编辑任务对话量
    public function ajax_task_dialog_num(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $admin_id = isset($_REQUEST['aid']) ? trim(intval($_REQUEST['aid'])) : '0';
        $num = isset($_REQUEST['num']) ? trim(intval($_REQUEST['num'])) : '0';

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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            exit('0');
        }

        $arr = array(
            'admin_id' => $admin_id,
            'start_date' => $w_start,
            'end_date' => $w_end,
            'task_dialog_num' => $num,
            'addtime' => time(),
            'add_admin_id' => $_COOKIE['l_admin_id']
        );

        $sql = "select id from " . $this->common->table('dialog_num') . " where start_date = $w_start and end_date = $w_end and admin_id = $admin_id";

        $row = $this->common->getOne($sql);

        if ($row) {
            $id = $row;
            $this->db->update($this->db->dbprefix . "dialog_num", $arr, array(
                'id' => $id
            ));
        } else {
            $this->db->insert($this->db->dbprefix . "dialog_num", $arr);
            $id = $this->db->insert_id();
        }

        if ($id) {
            exit('1');
        } else {
            exit('2');
        }

    }


    public function ajax_get_group(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;
        $check_id = isset($_REQUEST['check_id']) ? intval($_REQUEST['check_id']) : 0;
        if (empty($hos_id)) {
            exit();
        }

        $str = "<option value=\"0\">请选择小组...</option>";

        $sql = "select id,name from hui_user_groups where hos_id = {$hos_id}";
        $res_group = $this->common->getAll($sql);

        foreach ($res_group as $key => $value) {
            $groups[$value['name']]['id'] = $value['id'];
            $groups[$value['name']]['name'] = $value['name'];
        }
        ksort($groups);


        foreach ($groups as $val) {
            $str.= '<option value="' . $val['id'] . '"';
            if ($val['id'] == $check_id) {
                $str.= " selected";
            }
            $str.= '>' . $val['name'] . '</option>';
        }

        //台州卜清单独加上台州男科整体下载功能
        if (($hos_id == 3 && $_COOKIE['l_admin_id'] == 2598 && $_COOKIE['l_admin_id'] == 2598) || ($hos_id == 3 && $_COOKIE['l_admin_action'] == 'all')) {
            if ($check_id == '99999') {
                $str .= "<option value=\"99999\" selected>台州男科</option>";
            } else {
                $str .= "<option value=\"99999\">台州男科</option>";
            }
        }

        echo $str;
    }


    public function export(){
        header('content-type:text/html;charset=utf-8');
        //p($_GET);die();
        //p($_COOKIE);die();
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $t_date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : date('Y年m月d日',time())." - ".date('Y年m月d日',time());
        $admin_name = isset($_REQUEST['admin_name']) ? trim($_REQUEST['admin_name']) : '';
        $group = isset($_REQUEST['group']) ? trim($_REQUEST['group']) : '';
        $hos_id = isset($_REQUEST['hos_id']) ? trim($_REQUEST['hos_id']) : '';

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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
        }
        if (isset($start) && !empty($start)) {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }
        if (!isset($start) && empty($start)) {
            $w_start = strtotime(date('Y-m-d',time()) . ' 00:00:00');
            $w_end = strtotime(date('Y-m-d',time()) . ' 23:59:59');
        }


        //获取本月日期
        if (isset($start) && !empty($start) && strcmp($start, $end) == 0) {
            //是否只选择了一天
            //起始时间为当前选择日期的一号到当前选择时间
            //以开始时间为准

            $timestamp=strtotime($start);

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

        } elseif ( isset($start) && !empty($start) && strcmp($start, $end) != 0 ) {
            //以结束时间为准
            $timestamp=strtotime($end);

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

        } else {
            //当月起始时间
            $cur_month_start=date('Y',time()).'-'.(date('m',time())).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');
        }



        $where = " where 1";
        $where1 = " where 1";

        $where3 = " where 1";


        if (!empty($admin_name)) {
            $where.= " AND a.admin_name = '".$admin_name."'";
            $where1.= " AND a.admin_name = '".$admin_name."'";
            $where3.= " AND a.admin_name = '".$admin_name."'";
        }

        if (!empty($group)) {
            //台州男科下载
            if ($group == '99999') {
                $where.= " AND c.id in (106,107,108,121)";
                $where1.= " AND c.id in (106,107,108,121)";
                $where3.= " AND c.id in (106,107,108,121)";
            } else {
                $where.= " AND c.id = '".$group."'";
                $where1.= " AND c.id = '".$group."'";
                $where3.= " AND c.id = '".$group."'";
            }
        }

        if (!empty($hos_id)) {
            $where.= " AND a.hos_id = '".$hos_id."' AND c.hos_id = '".$hos_id."'";
            $where1.= " AND hos_id = '".$hos_id."'";
            $where3.= " AND c.hos_id = '".$hos_id."'";
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where.= " AND a.hos_id IN (" . $_COOKIE["l_hos_id"] . ") AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where1.= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where3.= " AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where1.= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }

        }


        //登记
        $sql_reg = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS reg_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND  a.order_addtime between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY reg_num DESC";

        $res_reg = $this->common->getAll($sql_reg);

        $list_reg = array();
        foreach ($res_reg as $val) {
            $list_reg[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_reg);
        foreach ($list_reg as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['reg_num']+= $v[0]['reg_num'];
            }
        }

        //预到
        $sql_order = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS order_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND  a.order_time between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY order_num DESC";

        $res_order = $this->common->getAll($sql_order);

        $list_order = array();
        foreach ($res_order as $val) {
            $list_order[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_order);
        foreach ($list_order as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['order_num']+= $v[0]['order_num'];
            }
        }


        //到诊
        $sql_come = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS come_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND a.is_come=1 AND a.come_time between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY come_num DESC";

        $res_come = $this->common->getAll($sql_come);

        $list_come = array();
        foreach ($res_come as $val) {
            $list_come[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_come);
        foreach ($list_come as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['come_num']+= $v[0]['come_num'];
            }
        }


        // $sql_dia = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS dialog_num,sum(d.target_num) AS target_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $w_start . " AND d.end_date <=" . $w_end ."  GROUP BY a.admin_id";

        //对话数
        $sql_dia = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS dialog_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $w_start . " AND d.end_date <=" . $w_end ."  GROUP BY a.admin_id";

        $res_dia = $this->common->getAll($sql_dia);

        $ded = array();

        foreach ($res_dia as $val) {
            $list_dia[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_dia);
        foreach ($list_dia as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ded[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ded[$key][$k]['id'] = $v[0]['id'];
                $ded[$key][$k]['name'] = $v[0]['name'];
                $ded[$key][$k]['dialog_num'] = $v[0]['dialog_num'];
            }
        }


        //获取本月目标到诊
        $sql_tar = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.target_num) AS target_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $c_m_start . " AND d.end_date <=" . $c_m_end ."  GROUP BY a.admin_id";


        $res_tar = $this->common->getAll($sql_tar);


        foreach ($res_tar as $val) {
            $list_tar[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_tar);
        foreach ($list_tar as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ded[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ded[$key][$k]['id'] = $v[0]['id'];
                $ded[$key][$k]['name'] = $v[0]['name'];
                $ded[$key][$k]['target_num']+= $v[0]['target_num'];
            }
        }


        foreach ($des as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v['admin_id'];
                $ded[$key][$k]['admin_name'] = $v['admin_name'];
                $ded[$key][$k]['order_num'] = $v['order_num']?$v['order_num']:'0';
                $ded[$key][$k]['reg_num'] = $v['reg_num']?$v['reg_num']:'0';
                $ded[$key][$k]['come_num'] = $v['come_num']?$v['come_num']:'0';
            }
        }
        ksort($ded);


        //获取所选小组小面的所有咨询
        $sql_zx= "SELECT a.admin_id,a.admin_name,c.id,c.name FROM `hui_admin` as a left join `hui_order` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on a.admin_group = c.id ".$where3." GROUP BY a.admin_id";

        $res_zx = $this->common->getAll($sql_zx);

        $ass = array();
        foreach ($res_zx as $val) {
            $list_zx[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_zx);
        foreach ($list_zx as $key => $val) {
            foreach ($val as $k => $v) {
                $ass[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ass[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ass[$key][$k]['id'] = $v[0]['id'];
                $ass[$key][$k]['name'] = $v[0]['name'];
            }
        }

        foreach ($ded as $key => $val) {
            foreach ($val as $k => $v) {
                $ass[$key][$k]['admin_id'] = $v['admin_id'];
                $ass[$key][$k]['admin_name'] = $v['admin_name'];
                $ass[$key][$k]['order_num'] = $v['order_num']?$v['order_num']:'0';
                $ass[$key][$k]['reg_num'] = $v['reg_num']?$v['reg_num']:'0';
                $ass[$key][$k]['come_num'] = $v['come_num']?$v['come_num']:'0';
                $ass[$key][$k]['dialog_num'] = $v['dialog_num'];
                $ass[$key][$k]['target_num'] = $v['target_num'];
            }
        }

        $count = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                $count['reg_count'] += $v['reg_num'];
                $count['order_count'] += $v['order_num'];
                $count['come_count'] += $v['come_num'];
                $count['dialog_count'] += $v['dialog_num'];
                $count['target_count'] += $v  ['target_num'];
            }
        }
//p($ass);
        $count_group = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                if ($key == $v['id']) {
                    $count_group[$key]['reg_count'] += $v['reg_num'];
                    $count_group[$key]['order_count'] += $v['order_num'];
                    $count_group[$key]['come_count'] += $v['come_num'];
                    $count_group[$key]['dialog_count'] += $v['dialog_num'];
                    $count_group[$key]['target_count'] += $v  ['target_num'];
                }
            }
        }
//p($count_group);exit();

        $row = array();
        $n = 0;
        foreach ($ass as $key => $value) {
            foreach ($value as $v) {
                $row[$key]['group'] = $v['name'];
                $row[$key]['info'][] = $v;
            }
            $n++;
        }

        foreach ($row as $key => $value) {
            $row[$key]['count'] = $count_group[$key];
        }

        //p($row);exit();



        //p($count_group);p($count);exit();



        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('男科周数据报表');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:J2');
        // $objPHPExcel->getActiveSheet()->mergeCells('B1:D1');
        // $objPHPExcel->getActiveSheet()->mergeCells('E1:E2');
        // $objPHPExcel->getActiveSheet()->mergeCells('F1:F2');
        // $objPHPExcel->getActiveSheet()->mergeCells('G1:G2');
        // $objPHPExcel->getActiveSheet()->mergeCells('H1:H2');
        // $objPHPExcel->getActiveSheet()->mergeCells('I1:I2');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$t_date.'男科数据报表');
        $objPHPExcel->getActiveSheet()->setCellValue('A3','小组');
        $objPHPExcel->getActiveSheet()->setCellValue('B3','咨询员');
        $objPHPExcel->getActiveSheet()->setCellValue('C3','咨询');
        $objPHPExcel->getActiveSheet()->setCellValue('D3','预约');
        $objPHPExcel->getActiveSheet()->setCellValue('E3','到诊');
        $objPHPExcel->getActiveSheet()->setCellValue('F3','预约率');
        $objPHPExcel->getActiveSheet()->setCellValue('G3','预约到诊率');
        $objPHPExcel->getActiveSheet()->setCellValue('H3','总转化率');
        $objPHPExcel->getActiveSheet()->setCellValue('I3','目标到诊');
        $objPHPExcel->getActiveSheet()->setCellValue('J3','任务完成率');

       //设置列宽和行高
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(9);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(14.25);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Microsoft Yahei');

        //设置单元格内文字自动换行
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);


        //设置单元格文字颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:J2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        //设置背景色
        $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->getStartColor()->setARGB('0000b0f0');

        //冻结前两行
        $objPHPExcel->getActiveSheet()->freezePane('A3');
        $objPHPExcel->getActiveSheet()->freezePane('A4');

        //设置前两行字体
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false);

        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:J3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:J3')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


        $all_num = count($row);

        $num = 0;
        $s = 0;
        $c = 3;
        foreach ($row as $key => $value) {
            for ($i = 4; $i <= count($value['info'])+5; $i++) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), $value['group']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), $value['info'][$i-4]['admin_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), !empty($value['info'][$i-4]['dialog_num'])?$value['info'][$i-4]['dialog_num']:'0');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), $value['info'][$i-4]['reg_num']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), $value['info'][$i-4]['come_num']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), number_format((($value['info'][$i-4]['reg_num']/$value['info'][$i-4]['dialog_num']) * 100) , 2, '.', '') . '%');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), number_format((($value['info'][$i-4]['come_num']/$value['info'][$i-4]['reg_num']) * 100) , 2, '.', '') . '%');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), number_format((($value['info'][$i-4]['come_num']/$value['info'][$i-4]['dialog_num']) * 100) , 2, '.', '') . '%');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), $value['info'][$i-4]['target_num']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), number_format((($value['info'][$i-4]['come_num']/$value['info'][$i-4]['target_num']) * 100) , 2, '.', '') . '%');
                if ($i == count($value['info'])+4) {
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c+1) .':A' . ($i+$num+$s));
                    //echo ('A' . ($c+1) .':A' . ($i+$num+$s))."<br>";
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), '');
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), '汇总');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), $value['count']['dialog_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), $value['count']['reg_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), $value['count']['come_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), number_format((($value['count']['reg_count']/$value['count']['dialog_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), number_format((($value['count']['come_count']/$value['count']['reg_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), number_format((($value['count']['come_count']/$value['count']['dialog_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), $value['count']['target_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), number_format((($value['count']['come_count']/$value['count']['target_count']) * 100) , 2, '.', '') . '%');

                    $objPHPExcel->getActiveSheet()->getStyle('B' . ($i+$num+$s))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                    $c = $i+$num+$s;
                } else if($i == count($value['info'])+5){
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), '');
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), '合计');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), $count['dialog_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), $count['reg_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), $count['come_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), number_format((($count['reg_count']/$count['dialog_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), number_format((($count['come_count']/$count['reg_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), number_format((($count['come_count']/$count['dialog_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), $count['target_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), number_format((($count['come_count']/$count['target_count']) * 100) , 2, '.', '') . '%');

                    // $objPHPExcel->getActiveSheet()->getStyle('B' . ($i+$num+$s))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                    // $c = $i+$num+$s;
                }

                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':J' . ($i+$num+$s))->getFont()->setSize(10);
                //边框
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':J' . ($i+$num+$s))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':J' . ($i+$num+$s))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
            }
            $num += count($value['info']);
            $s ++;
        }
//exit();







        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$t_date.'报表.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }



    /**
     * 导出目标分解
     */
    public function allocate(){
        header('content-type:text/html;charset=utf-8');
        //p($_GET);die();
        //p($_COOKIE);die();
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
        $admin_name = isset($_REQUEST['admin_name']) ? trim($_REQUEST['admin_name']) : '';
        $group = isset($_REQUEST['group']) ? trim($_REQUEST['group']) : '';
        $hos_id = isset($_REQUEST['hos_id']) ? trim($_REQUEST['hos_id']) : '';


        //获取医院信息
        if (!empty($group) && empty($hos_id)) {
            $sql_hos = "select hos_id,hos_name from hui_user_groups where id = $group";
            $res_hos = $this->common->getRow($sql_hos);
        }
        if ((empty($group) && !empty($hos_id)) || (!empty($group) && !empty($hos_id))) {
            $sql_hos = "select hos_id,hos_name from hui_hospital where hos_id = $hos_id";
            $res_hos = $this->common->getRow($sql_hos);
        }


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
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);

            //$t_date = !empty($end)?date('Y月m月01日',strtotime($end)).' - '.date('Y月m月d日',strtotime($end)):'';
            $t_date = $date[0].' - '.$date[1];
        }


        if (isset($start) && !empty($start) && strcmp($start, $end) == 0) {
            //是否只选择了一天
            //起始时间为当前选择日期的一号到当前选择时间
            //以开始时间为准

            $timestamp=strtotime($start);
            $last_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01';
            $last_month_end=date('Y-m-d',strtotime("$last_month_start +1 month -1 day"));

            $l_m_start = strtotime($last_month_start . ' 00:00:00');
            $l_m_end = strtotime($last_month_end . ' 23:59:59');

            $last_month = intval(date('m',$l_m_start));
            $cur_month = intval(date('m',$timestamp));

            //开始时间月的起始时间
            $cur_day_start = strtotime(date('Y-m-01 00:00:00',$timestamp));
            $cur_day_end = strtotime(date('Y-m-01 23:59:59',$timestamp));

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

            //获取当月天数
            $day_num = date('t',$timestamp);
        } elseif ( isset($start) && !empty($start) && strcmp($start, $end) != 0 ) {
            //以结束时间为准
            $timestamp=strtotime($end);
            $last_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01';
            $last_month_end=date('Y-m-d',strtotime("$last_month_start +1 month -1 day"));

            $l_m_start = strtotime($last_month_start . ' 00:00:00');
            $l_m_end = strtotime($last_month_end . ' 23:59:59');

            $last_month = intval(date('m',$l_m_start));
            $cur_month = intval(date('m',$timestamp));

            //结束时间月的起始时间
            $cur_day_start = strtotime(date('Y-m-01 00:00:00',$timestamp));
            $cur_day_end = strtotime(date('Y-m-01 23:59:59',$timestamp));

            //当月起始时间
            $cur_month_start=date('Y',$timestamp).'-'.(date('m',$timestamp)).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

            //获取当月天数
            $day_num = date('t',$timestamp);
        } else {
            //起始时间为当前日期的一号到当前时间

            $last_month_start=date('Y',time()).'-'.(date('m',time())-1).'-01';
            $last_month_end=date('Y-m-d',strtotime("$last_month_start +1 month -1 day"));

            $l_m_start = strtotime($last_month_start . ' 00:00:00');
            $l_m_end = strtotime($last_month_end . ' 23:59:59');

            $last_month = intval(date('m',$l_m_start));
            $cur_month = intval(date('m',time()));

            //缺省时间月各天的起始时间
            $cur_day_start = strtotime(date('Y-m-01 00:00:00',time()));
            $cur_day_end = strtotime(date('Y-m-01 23:59:59',time()));

            //当月起始时间
            $cur_month_start=date('Y',time()).'-'.(date('m',time())).'-01';
            $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
            $c_m_start = strtotime($cur_month_start . ' 00:00:00');
            $c_m_end = strtotime($cur_month_end . ' 23:59:59');

            //获取当月天数
            $day_num = date('t',time());
        }

        $w_start = $c_m_start;
        $w_end = $c_m_end;

        $where = " where 1";
        $where1 = " where 1";
        $where3 = " where 1";


        if (!empty($admin_name)) {
            $where.= " AND a.admin_name = '".$admin_name."'";
            $where1.= " AND a.admin_name = '".$admin_name."'";
            $where3.= " AND a.admin_name = '".$admin_name."'";
        }

        if (!empty($group)) {

            if ($group == '99999') {
                $where.= " AND c.id in (106,107,108,121)";
                $where1.= " AND c.id in (106,107,108,121)";
                $where3.= " AND c.id in (106,107,108,121)";
            } else {
                $where.= " AND c.id = '".$group."'";
                $where1.= " AND c.id = '".$group."'";
                $where3.= " AND c.id = '".$group."'";
            }

        }

        if (!empty($hos_id)) {
            $where.= " AND a.hos_id = '".$hos_id."' AND c.hos_id = '".$hos_id."'";
            $where1.= " AND hos_id = '".$hos_id."'";
            $where3.= " AND c.hos_id = '".$hos_id."'";
        } else {
            if (!empty($_COOKIE["l_hos_id"])) {
                $where.= " AND a.hos_id IN (" . $_COOKIE["l_hos_id"] . ") AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where1.= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
                $where3.= " AND c.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
            }
            if (!empty($_COOKIE['l_keshi_id'])) {
                $where1.= " AND keshi_id in(" . $_COOKIE['l_keshi_id'] . ")";
            }

        }


        //本月到诊
        $sql_come = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS come_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND a.is_come=1 AND a.come_time between " . $w_start . " AND " . $w_end . " GROUP BY a.admin_id ORDER BY come_num DESC";

        $res_come = $this->common->getAll($sql_come);

        $list_come = array();
        foreach ($res_come as $val) {
            $list_come[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_come);
        foreach ($list_come as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['come_num'] = $v[0]['come_num'];
            }
        }

        //本月对话
        $sql_dia = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS dialog_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $w_start . " AND d.end_date <=" . $w_end ."  GROUP BY a.admin_id";

        $res_dia = $this->common->getAll($sql_dia);

        $ded = array();

        foreach ($res_dia as $val) {
            $list_dia[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_dia);
        foreach ($list_dia as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ded[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ded[$key][$k]['id'] = $v[0]['id'];
                $ded[$key][$k]['name'] = $v[0]['name'];
                $ded[$key][$k]['dialog_num'] = $v[0]['dialog_num'];
            }
        }

        //获取本月目标到诊、任务到诊、任务对话
        $sql_tar = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.target_num) AS target_num,sum(d.task_come_num) AS task_come_num,sum(d.task_dialog_num) AS task_dialog_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $c_m_start . " AND d.end_date <=" . $c_m_end ."  GROUP BY a.admin_id";

        $res_tar = $this->common->getAll($sql_tar);

        foreach ($res_tar as $val) {
            $list_tar[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_tar);
        foreach ($list_tar as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ded[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ded[$key][$k]['id'] = $v[0]['id'];
                $ded[$key][$k]['name'] = $v[0]['name'];
                $ded[$key][$k]['target_num'] = $v[0]['target_num'];
                $ded[$key][$k]['task_come_num'] = $v[0]['task_come_num'];
                $ded[$key][$k]['task_dialog_num'] = $v[0]['task_dialog_num'];
            }
        }


        //获取当月每天到诊和对话
        for ($i=0; $i < $day_num; $i++) {
            $col_name_come = ($i+1).'_day_come_num';
            $col_name_dia = ($i+1).'_day_dialog_num';
            $array_name_come = 'list_day_come_'.($i+1);
            $array_name_dia = 'list_day_dia_'.($i+1);
            //本月第($i+1)天到诊
            $cur_one_day_start = $cur_day_start + 86400*$i;
            $cur_one_day_end = $cur_day_end + 86400*$i;

            $sql_day = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS $col_name_come FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND a.is_come=1 AND a.come_time between " . $cur_one_day_start . " AND " . $cur_one_day_end . " GROUP BY a.admin_id";

            $res_day = $this->common->getAll($sql_day);

            ${$array_name_come} = array();
            foreach ($res_day as $val) {
                ${$array_name_come}[$val['id']][$val['admin_id']][] = $val;
            }
            ksort(${$array_name_come});
            foreach (${$array_name_come} as $key => $val) {
                foreach ($val as $k => $v) {
                    $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                    $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                    $des[$key][$k]['id'] = $v[0]['id'];
                    $des[$key][$k]['name'] = $v[0]['name'];
                    $des[$key][$k][$col_name_come] = $v[0][$col_name_come];
                }
            }
            //本月第($i+1)天对话
            $sql_day = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS $col_name_dia FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $cur_one_day_start . " AND d.end_date <=" . $cur_one_day_end ."  GROUP BY a.admin_id";

            $res_day = $this->common->getAll($sql_day);

            ${$array_name_dia}= array();
            foreach ($res_day as $val) {
                ${$array_name_dia}[$val['id']][$val['admin_id']][] = $val;
            }
            ksort(${$array_name_dia});

            foreach (${$array_name_dia} as $key => $val) {
                foreach ($val as $k => $v) {
                    $ded[$key][$k]['admin_id'] = $v[0]['admin_id'];
                    $ded[$key][$k]['admin_name'] = $v[0]['admin_name'];
                    $ded[$key][$k]['id'] = $v[0]['id'];
                    $ded[$key][$k]['name'] = $v[0]['name'];
                    $ded[$key][$k][$col_name_dia] = $v[0][$col_name_dia];
                }
            }
        }



        //获取上月到诊
        $sql_l_m_come = "SELECT a.admin_id,a.admin_name,a.hos_id,c.id,c.name,count(*) AS l_m_come_num FROM `hui_order` as a left join `hui_admin` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on b.admin_group = c.id ".$where." AND a.is_come=1 AND a.come_time between " . $l_m_start . " AND " . $l_m_end . " GROUP BY a.admin_id ORDER BY l_m_come_num DESC";

        $res_l_m_come = $this->common->getAll($sql_l_m_come);

        $list_l_m_come = array();
        foreach ($res_l_m_come as $val) {
            $list_l_m_come[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_l_m_come);
        // p($list_l_m_come);exit();
        foreach ($list_l_m_come as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['l_m_come_num']+= $v[0]['l_m_come_num']?$v[0]['l_m_come_num']:'0';
            }
        }
        //p($des);exit();

        //上月对话
        $sql_l_m_dia = "SELECT a.admin_id,a.admin_name,c.id,c.name,sum(d.dialog_num) AS l_m_dialog_num,sum(d.target_num) AS l_m_target_num FROM `hui_admin` as a left join `hui_user_groups` as c on a.admin_group = c.id left join `hui_dialog_num` as d on a.admin_id = d.admin_id ".$where1." AND d.start_date >= " . $l_m_start . " AND d.end_date <=" . $l_m_end ."  GROUP BY a.admin_id";

        $res_l_m_dia = $this->common->getAll($sql_l_m_dia);

        foreach ($res_l_m_dia as $val) {
            $list_l_m_dia[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_l_m_dia);
        foreach ($list_l_m_dia as $key => $val) {
            foreach ($val as $k => $v) {
                $des[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $des[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $des[$key][$k]['id'] = $v[0]['id'];
                $des[$key][$k]['name'] = $v[0]['name'];
                $des[$key][$k]['l_m_dialog_num'] = $v[0]['l_m_dialog_num'];
                $des[$key][$k]['l_m_target_num']+= $v[0]['l_m_target_num'];
            }
        }

        foreach ($des as $key => $val) {
            foreach ($val as $k => $v) {
                $ded[$key][$k]['admin_id'] = $v['admin_id'];
                $ded[$key][$k]['admin_name'] = $v['admin_name'];
                $ded[$key][$k]['come_num'] = $v['come_num']?$v['come_num']:'0';
                $ded[$key][$k]['l_m_come_num'] = $v['l_m_come_num']?$v['l_m_come_num']:'0';
                $ded[$key][$k]['l_m_dialog_num'] = $v['l_m_dialog_num']?$v['l_m_dialog_num']:'0';
                $ded[$key][$k]['l_m_target_num'] = $v['l_m_target_num']?$v['l_m_target_num']:'0';

                for ($i=1; $i < $day_num+1; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';
                    $ded[$key][$k][$col_name_come] = $v[$col_name_come]?$v[$col_name_come]:'0';
                }
            }
        }
        ksort($ded);


        //获取所选小组小面的所有咨询
        $sql_zx= "SELECT a.admin_id,a.admin_name,c.id,c.name FROM `hui_admin` as a left join `hui_order` as b on a.admin_id = b.admin_id left join `hui_user_groups` as c on a.admin_group = c.id ".$where3." GROUP BY a.admin_id";

        $res_zx = $this->common->getAll($sql_zx);

        $ass = array();
        foreach ($res_zx as $val) {
            $list_zx[$val['id']][$val['admin_id']][] = $val;
        }
        ksort($list_zx);
        foreach ($list_zx as $key => $val) {
            foreach ($val as $k => $v) {
                $ass[$key][$k]['admin_id'] = $v[0]['admin_id'];
                $ass[$key][$k]['admin_name'] = $v[0]['admin_name'];
                $ass[$key][$k]['id'] = $v[0]['id'];
                $ass[$key][$k]['name'] = $v[0]['name'];
            }
        }

        foreach ($ded as $key => $val) {
            foreach ($val as $k => $v) {
                $ass[$key][$k]['admin_id'] = $v['admin_id'];
                $ass[$key][$k]['admin_name'] = $v['admin_name'];
                $ass[$key][$k]['come_num'] = $v['come_num']?$v['come_num']:'0';
                $ass[$key][$k]['dialog_num'] = $v['dialog_num'];
                $ass[$key][$k]['target_num'] = $v['target_num'];
                $ass[$key][$k]['task_come_num'] = $v['task_come_num'];
                $ass[$key][$k]['task_dialog_num'] = $v['task_dialog_num'];
                $ass[$key][$k]['l_m_come_num'] = $v['l_m_come_num']?$v['l_m_come_num']:'0';
                $ass[$key][$k]['l_m_dialog_num'] = $v['l_m_dialog_num']?$v['l_m_dialog_num']:'0';
                $ass[$key][$k]['l_m_target_num'] = $v['l_m_target_num']?$v['l_m_target_num']:'0';


                for ($i=1; $i < $day_num+1; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';
                    $ass[$key][$k][$col_name_come] = $v[$col_name_come]?$v[$col_name_come]:'0';
                    $ass[$key][$k][$col_name_dia] = $v[$col_name_dia]?$v[$col_name_dia]:'0';
                }

            }
        }

        //整体合计
        $count = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                $count['come_count'] += $v['come_num'];
                $count['dialog_count'] += $v['dialog_num'];
                $count['target_count'] += $v['target_num'];
                $count['task_come_num_count'] += $v['task_come_num'];
                $count['task_dialog_num_count'] += $v['task_dialog_num'];
                $count['l_m_come_count'] += $v['l_m_come_num'];
                $count['l_m_dialog_count'] += $v['l_m_dialog_num'];
                $count['l_m_target_count'] += $v['l_m_target_num'];

                for ($i=1; $i < $day_num+1; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';
                    $col_name_come_count = $i.'_day_come_num_count';
                    $col_name_dia_count = $i.'_day_dialog_num_count';

                    $count[$col_name_come_count] += $v[$col_name_come];
                    $count[$col_name_dia_count] += $v[$col_name_dia];

                }
            }
        }

        //组合计
        $count_group = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                if ($key == $v['id']) {
                    $count_group[$key]['come_count'] += $v['come_num'];
                    $count_group[$key]['dialog_count'] += $v['dialog_num'];
                    $count_group[$key]['target_count'] += $v['target_num'];
                    $count_group[$key]['task_come_num_count'] += $v['task_come_num'];
                    $count_group[$key]['task_dialog_num_count'] += $v['task_dialog_num'];
                    $count_group[$key]['l_m_come_count'] += $v['l_m_come_num'];
                    $count_group[$key]['l_m_dialog_count'] += $v['l_m_dialog_num'];
                    $count_group[$key]['l_m_target_count'] += $v['l_m_target_num'];


                    for ($i=1; $i < $day_num+1; $i++) {
                        $col_name_come = $i.'_day_come_num';
                        $col_name_dia = $i.'_day_dialog_num';
                        $col_name_come_count = $i.'_day_come_num_count';
                        $col_name_dia_count = $i.'_day_dialog_num_count';

                        $count_group[$key][$col_name_come_count] += $v[$col_name_come];
                        $count_group[$key][$col_name_dia_count] += $v[$col_name_dia];

                    }

                }
            }
        }

        //个人合计
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                for ($i=1; $i < $day_num+1; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';

                    $ass[$key][$k]['come_count'] += $v[$col_name_come];
                    $ass[$key][$k]['dialog_count'] += $v[$col_name_dia];

                    if (!isset($ass[$key][$k][$col_name_come])) {
                        $ass[$key][$k][$col_name_come] = '0';
                    }

                    if (!isset($ass[$key][$k][$col_name_dia])) {
                        $ass[$key][$k][$col_name_dia] = '0';
                    }

                }
            }
        }



        //获取查询条件天数
        $from_start_day = intval(date('d',strtotime($start)));
        $from_end_day = intval(date('d',strtotime($end)));
        $cha_day = $from_end_day - $from_start_day + 1;

        //查询
        //整体合计
        $count_search = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                $count_search['come_count'] += $v['come_num'];
                $count_search['dialog_count'] += $v['dialog_num'];
                $count_search['target_count'] += $v['target_num'];
                $count_search['task_come_num_count'] += $v['task_come_num'];
                $count_search['task_dialog_num_count'] += $v['task_dialog_num'];
                $count_search['l_m_come_count'] += $v['l_m_come_num'];
                $count_search['l_m_dialog_count'] += $v['l_m_dialog_num'];
                $count_search['l_m_target_count'] += $v['l_m_target_num'];


                for ($i=$from_start_day; $i < $cha_day+$from_start_day; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';
                    $col_name_come_count = $i.'_day_come_num_count';
                    $col_name_dia_count = $i.'_day_dialog_num_count';

                    $count_search[$col_name_come_count] += $v[$col_name_come];
                    $count_search[$col_name_dia_count] += $v[$col_name_dia];
                }
            }
        }

        for ($i=$from_start_day; $i < $cha_day+$from_start_day; $i++) {
            $col_name_come_count = $i.'_day_come_num_count';
            $col_name_dia_count = $i.'_day_dialog_num_count';

            $count_search['come_count_search'] += $count_search[$col_name_come_count];
            $count_search['dialog_count_search'] += $count_search[$col_name_dia_count];
        }


        //查询
        //组合计
        $count_group_search = array();
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                if ($key == $v['id']) {
                    $count_group_search[$key]['come_count'] += $v['come_num'];
                    $count_group_search[$key]['dialog_count'] += $v['dialog_num'];
                    $count_group_search[$key]['target_count'] += $v['target_num'];
                    $count_group_search[$key]['task_come_num_count'] += $v['task_come_num'];
                    $count_group_search[$key]['task_dialog_num_count'] += $v['task_dialog_num'];
                    $count_group_search[$key]['l_m_come_count'] += $v['l_m_come_num'];
                    $count_group_search[$key]['l_m_dialog_count'] += $v['l_m_dialog_num'];
                    $count_group_search[$key]['l_m_target_count'] += $v['l_m_target_num'];


                    for ($i=$from_start_day; $i < $cha_day+$from_start_day; $i++) {
                        $col_name_come = $i.'_day_come_num';
                        $col_name_dia = $i.'_day_dialog_num';
                        $col_name_come_count = $i.'_day_come_num_count';
                        $col_name_dia_count = $i.'_day_dialog_num_count';

                        $count_group_search[$key][$col_name_come_count] += $v[$col_name_come];
                        $count_group_search[$key][$col_name_dia_count] += $v[$col_name_dia];

                    }

                }
            }
        }

        foreach ($count_group_search as $key => $value) {
            for ($i=$from_start_day; $i < $cha_day+$from_start_day; $i++) {
                $col_name_come_count = $i.'_day_come_num_count';
                $col_name_dia_count = $i.'_day_dialog_num_count';

                $count_group_search[$key]['come_count_search'] += $value[$col_name_come_count];
                $count_group_search[$key]['dialog_count_search'] += $value[$col_name_dia_count];
            }
        }

        //查询
        //个人合计
        foreach ($ass as $key => $value) {
            foreach ($value as $k => $v) {
                for ($i=$from_start_day; $i < $cha_day+$from_start_day; $i++) {
                    $col_name_come = $i.'_day_come_num';
                    $col_name_dia = $i.'_day_dialog_num';

                    $ass[$key][$k]['come_count_search'] += $v[$col_name_come];
                    $ass[$key][$k]['dialog_count_search'] += $v[$col_name_dia];

                }
            }
        }


        $row = array();
        $n = 0;
        foreach ($ass as $key => $value) {
            foreach ($value as $v) {
                $row[$key]['group'] = $v['name'];
                $row[$key]['info'][] = $v;
            }
            $n++;
        }

        foreach ($row as $key => $value) {
            $row[$key]['count'] = $count_group[$key];
        }

        foreach ($row as $key => $value) {
            $row[$key]['count_search'] = $count_group_search[$key];
        }

       //p($row);exit();


        //p($count_group);p($count);exit();

        //字母日期对照
        $col_letters =array(
            '1' => 'O','2' => 'P','3' => 'Q','4' => 'R','5' => 'S','6' => 'T','7' => 'U','8' => 'V','9' => 'W','10' => 'X','11' => 'Y','12' => 'Z','13' => 'AA','14' => 'AB','15' => 'AC','16' => 'AD','17' => 'AE','18' => 'AF','19' => 'AG','20' => 'AH','21' => 'AI','22' => 'AJ','23' => 'AK','24' => 'AL','25' => 'AM','26' => 'AN','27' => 'AO','28' => 'AP','29' => 'AQ','30' => 'AR','31' => 'AS'
        );

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle($res_hos['hos_name'].'目标分解');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$col_letters[$day_num].'1');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
        $objPHPExcel->getActiveSheet()->mergeCells('E2:H2');
        $objPHPExcel->getActiveSheet()->mergeCells('I2:K2');
        $objPHPExcel->getActiveSheet()->mergeCells('L2:N2');
        $objPHPExcel->getActiveSheet()->mergeCells('O2:'.$col_letters[$day_num].'2');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$t_date.'目标分解');
        $objPHPExcel->getActiveSheet()->setCellValue('A2','商务通');
        $objPHPExcel->getActiveSheet()->setCellValue('B2',$last_month.'月完成量');
        $objPHPExcel->getActiveSheet()->setCellValue('E2',$cur_month.'月目标');
        $objPHPExcel->getActiveSheet()->setCellValue('I2','当前合计');
        $objPHPExcel->getActiveSheet()->setCellValue('L2','月度合计');
        $objPHPExcel->getActiveSheet()->setCellValue('O2','月度完成进度表');

       //设置列宽和行高
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(3.75);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(3.75);

        if ($col_letters[$day_num] == 'AP') {
            $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(3.75);
        } elseif ($col_letters[$day_num] == 'AQ') {
            $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(3.75);
        } elseif ($col_letters[$day_num] == 'AR') {
            $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(3.75);
        } elseif ($col_letters[$day_num] == 'AS') {
            $objPHPExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(3.75);
            $objPHPExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(3.75);
        }


        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension('A1:'.$col_letters[$day_num].'1')->setRowHeight(22.5);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        //设置单元格内文字自动换行
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);


        //设置单元格文字颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$col_letters[$day_num].'1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        //设置背景色
        // $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        // $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->getStartColor()->setARGB('0000b0f0');

        //冻结前两行
        // $objPHPExcel->getActiveSheet()->freezePane('A3');
        // $objPHPExcel->getActiveSheet()->freezePane('A4');


        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$col_letters[$day_num].'1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$col_letters[$day_num].'1')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
        $objPHPExcel->getActiveSheet()->getStyle('A2:'.$col_letters[$day_num].'2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A2:'.$col_letters[$day_num].'2')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


        $all_num = count($row);

        $num = 0;
        $s = 0;
        foreach ($row as $key => $value) {
            $q = -1;
            for ($i = 3; $i <= count($value['info'])+5; $i++) {
                $c = $i+$num+$s+$q;
                if ($i == 3) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), $value['group']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), $last_month.'月完成量');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), $last_month.'月对话量');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), $last_month.'月转化');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), $cur_month.'月任务量');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), '冲刺目标');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), $cur_month.'月对话量');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), $cur_month.'月转化');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), '实际目标');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), '完成情况');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i+$num+$s), '转化率');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i+$num+$s), '实际目标');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i+$num+$s), '完成情况');
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i+$num+$s), '转化率');
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . ($i+$num+$s), '1');
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . ($i+$num+$s), '2');
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($i+$num+$s), '3');
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . ($i+$num+$s), '4');
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . ($i+$num+$s), '5');
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . ($i+$num+$s), '6');
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . ($i+$num+$s), '7');
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . ($i+$num+$s), '8');
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . ($i+$num+$s), '9');
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . ($i+$num+$s), '10');
                    $objPHPExcel->getActiveSheet()->setCellValue('Y' . ($i+$num+$s), '11');
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . ($i+$num+$s), '12');
                    $objPHPExcel->getActiveSheet()->setCellValue('AA' . ($i+$num+$s), '13');
                    $objPHPExcel->getActiveSheet()->setCellValue('AB' . ($i+$num+$s), '14');
                    $objPHPExcel->getActiveSheet()->setCellValue('AC' . ($i+$num+$s), '15');
                    $objPHPExcel->getActiveSheet()->setCellValue('AD' . ($i+$num+$s), '16');
                    $objPHPExcel->getActiveSheet()->setCellValue('AE' . ($i+$num+$s), '17');
                    $objPHPExcel->getActiveSheet()->setCellValue('AF' . ($i+$num+$s), '18');
                    $objPHPExcel->getActiveSheet()->setCellValue('AG' . ($i+$num+$s), '19');
                    $objPHPExcel->getActiveSheet()->setCellValue('AH' . ($i+$num+$s), '20');
                    $objPHPExcel->getActiveSheet()->setCellValue('AI' . ($i+$num+$s), '21');
                    $objPHPExcel->getActiveSheet()->setCellValue('AJ' . ($i+$num+$s), '22');
                    $objPHPExcel->getActiveSheet()->setCellValue('AK' . ($i+$num+$s), '23');
                    $objPHPExcel->getActiveSheet()->setCellValue('AL' . ($i+$num+$s), '24');
                    $objPHPExcel->getActiveSheet()->setCellValue('AM' . ($i+$num+$s), '25');
                    $objPHPExcel->getActiveSheet()->setCellValue('AN' . ($i+$num+$s), '26');
                    $objPHPExcel->getActiveSheet()->setCellValue('AO' . ($i+$num+$s), '27');

                    if ($col_letters[$day_num] == 'AP') {
                        $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($i+$num+$s), '28');
                    } elseif ($col_letters[$day_num] == 'AQ') {
                        $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($i+$num+$s), '28');
                        $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($i+$num+$s), '29');
                    } elseif ($col_letters[$day_num] == 'AR') {
                        $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($i+$num+$s), '28');
                        $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($i+$num+$s), '29');
                        $objPHPExcel->getActiveSheet()->setCellValue('AR' . ($i+$num+$s), '30');
                    } elseif ($col_letters[$day_num] == 'AS') {
                        $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($i+$num+$s), '28');
                        $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($i+$num+$s), '29');
                        $objPHPExcel->getActiveSheet()->setCellValue('AR' . ($i+$num+$s), '30');
                        $objPHPExcel->getActiveSheet()->setCellValue('AS' . ($i+$num+$s), '31');
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':' . $col_letters[$day_num] . ($i+$num+$s))->getFont()->setSize(12);
                    //边框
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':' . $col_letters[$day_num] . ($i+$num+$s))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':' . $col_letters[$day_num] . ($i+$num+$s))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


                } elseif ($i == count($value['info'])+4) {

                    $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c) .':A' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('B' . ($c) .':B' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('C' . ($c) .':C' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('D' . ($c) .':D' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('E' . ($c) .':E' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('F' . ($c) .':F' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('G' . ($c) .':G' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('H' . ($c) .':H' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('K' . ($c) .':K' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('N' . ($c) .':N' . ($c+1));

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($c), '汇总');
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($c), $value['count']['l_m_come_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($c), $value['count']['l_m_dialog_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($c), number_format((($value['count']['l_m_come_count']/$value['count']['l_m_dialog_count']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($c), $value['count']['task_come_num_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($c), $value['count']['target_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($c), $value['count']['task_dialog_num_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($c), number_format((($value['count']['task_come_num_count']/$value['count']['task_dialog_num_count']) * 100) , 2, '.', '') . '%');

                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($c), '总对话');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($c+1), '总到诊');

                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($c), $value['count_search']['dialog_count_search']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($c+1), $value['count_search']['come_count_search']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($c), number_format((($value['count_search']['come_count_search']/$value['count_search']['dialog_count_search']) * 100) , 2, '.', '') . '%');


                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($c), '总对话');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($c+1), '总到诊');

                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($c), $value['count']['dialog_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($c+1), $value['count']['come_count']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($c), number_format((($value['count']['come_count']/$value['count']['dialog_count']) * 100) , 2, '.', '') . '%');

                    for ($K=1; $K < $day_num+1; $K++) {
                        $col_name_come = $K.'_day_come_num';
                        $col_name_dia = $K.'_day_dialog_num';
                        $col_name_come_count = $K.'_day_come_num_count';
                        $col_name_dia_count = $K.'_day_dialog_num_count';
                        $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$K] . ($c), $value['count'][$col_name_dia_count]);
                        $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$K] . ($c+1), $value['count'][$col_name_come_count]);
                    }



                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c+1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c+1))->getFont()->setBold(true);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getFont()->setSize(12);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getFont()->setSize(12);
                    //边框
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


                } elseif ($i == count($value['info'])+5) {

                    $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c) . ':' . $col_letters[$day_num] . ($c));
                    $objPHPExcel->getActiveSheet()->getRowDimension($c)->setRowHeight(11.25);

                } else {

                    $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c) .':A' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('B' . ($c) .':B' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('C' . ($c) .':C' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('D' . ($c) .':D' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('E' . ($c) .':E' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('F' . ($c) .':F' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('G' . ($c) .':G' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('H' . ($c) .':H' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('K' . ($c) .':K' . ($c+1));
                    $objPHPExcel->getActiveSheet()->mergeCells('N' . ($c) .':N' . ($c+1));

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($c), $value['info'][$i-4]['admin_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($c), $value['info'][$i-4]['l_m_come_num']?$value['info'][$i-4]['l_m_come_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($c), $value['info'][$i-4]['l_m_dialog_num']?$value['info'][$i-4]['l_m_dialog_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($c), number_format((($value['info'][$i-4]['l_m_come_num']/$value['info'][$i-4]['l_m_dialog_num']) * 100) , 2, '.', '') . '%');
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($c), $value['info'][$i-4]['task_come_num']?$value['info'][$i-4]['task_come_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($c), $value['info'][$i-4]['target_num']?$value['info'][$i-4]['target_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($c), $value['info'][$i-4]['task_dialog_num']?$value['info'][$i-4]['task_dialog_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($c), number_format((($value['info'][$i-4]['task_come_num']/$value['info'][$i-4]['task_dialog_num']) * 100) , 2, '.', '') . '%');

                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($c), '当前对话');
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($c+1), '当前到诊');


                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($c), $value['info'][$i-4]['dialog_count_search']?$value['info'][$i-4]['dialog_count_search']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . ($c+1), $value['info'][$i-4]['come_count_search']?$value['info'][$i-4]['come_count_search']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($c), number_format((($value['info'][$i-4]['come_count_search']/$value['info'][$i-4]['dialog_count_search']) * 100) , 2, '.', '') . '%');

                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($c), '当月对话');
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($c+1), '当月到诊');

                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($c), $value['info'][$i-4]['dialog_num']?$value['info'][$i-4]['dialog_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($c+1), $value['info'][$i-4]['come_num']?$value['info'][$i-4]['come_num']:'0');
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . ($c), number_format((($value['info'][$i-4]['come_num']/$value['info'][$i-4]['dialog_num']) * 100) , 2, '.', '') . '%');

                    for ($j=1; $j < $day_num+1; $j++) {
                        $col_name_come = $j.'_day_come_num';
                        $col_name_dia = $j.'_day_dialog_num';
                        $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$j] . ($c), $value['info'][$i-4][$col_name_dia]);
                        $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$j] . ($c+1), $value['info'][$i-4][$col_name_come]);
                    }


                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getFont()->setSize(12);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getFont()->setSize(12);
                    //边框
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c) . ':' . $col_letters[$day_num] . ($c))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':' . $col_letters[$day_num] . ($c+1))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

                }
                //var_dump($c.'----'.$i.'----'.$num.'----'.$s.'-----'.$q);
                $q ++;
            }
            $num += count($value['info'])*2+3;
            $s ++;

        }

        $lastcell = $num+$s+4;
        // var_dump($lastcell);
        // exit();
        //合计
        if(!empty($row)){

            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($lastcell-1), $res_hos['hos_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($lastcell-1), $last_month.'月完成量');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($lastcell-1), $last_month.'月对话量');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($lastcell-1), $last_month.'月转化');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($lastcell-1), $cur_month.'月任务量');
            $objPHPExcel->getActiveSheet()->setCellValue('F' . ($lastcell-1), '冲刺目标');
            $objPHPExcel->getActiveSheet()->setCellValue('G' . ($lastcell-1), $cur_month.'月对话量');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . ($lastcell-1), $cur_month.'月转化');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . ($lastcell-1), '实际目标');
            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($lastcell-1), '完成情况');
            $objPHPExcel->getActiveSheet()->setCellValue('K' . ($lastcell-1), '转化率');
            $objPHPExcel->getActiveSheet()->setCellValue('L' . ($lastcell-1), '实际目标');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . ($lastcell-1), '完成情况');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . ($lastcell-1), '转化率');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . ($lastcell-1), '1');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . ($lastcell-1), '2');
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($lastcell-1), '3');
            $objPHPExcel->getActiveSheet()->setCellValue('R' . ($lastcell-1), '4');
            $objPHPExcel->getActiveSheet()->setCellValue('S' . ($lastcell-1), '5');
            $objPHPExcel->getActiveSheet()->setCellValue('T' . ($lastcell-1), '6');
            $objPHPExcel->getActiveSheet()->setCellValue('U' . ($lastcell-1), '7');
            $objPHPExcel->getActiveSheet()->setCellValue('V' . ($lastcell-1), '8');
            $objPHPExcel->getActiveSheet()->setCellValue('W' . ($lastcell-1), '9');
            $objPHPExcel->getActiveSheet()->setCellValue('X' . ($lastcell-1), '10');
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . ($lastcell-1), '11');
            $objPHPExcel->getActiveSheet()->setCellValue('Z' . ($lastcell-1), '12');
            $objPHPExcel->getActiveSheet()->setCellValue('AA' . ($lastcell-1), '13');
            $objPHPExcel->getActiveSheet()->setCellValue('AB' . ($lastcell-1), '14');
            $objPHPExcel->getActiveSheet()->setCellValue('AC' . ($lastcell-1), '15');
            $objPHPExcel->getActiveSheet()->setCellValue('AD' . ($lastcell-1), '16');
            $objPHPExcel->getActiveSheet()->setCellValue('AE' . ($lastcell-1), '17');
            $objPHPExcel->getActiveSheet()->setCellValue('AF' . ($lastcell-1), '18');
            $objPHPExcel->getActiveSheet()->setCellValue('AG' . ($lastcell-1), '19');
            $objPHPExcel->getActiveSheet()->setCellValue('AH' . ($lastcell-1), '20');
            $objPHPExcel->getActiveSheet()->setCellValue('AI' . ($lastcell-1), '21');
            $objPHPExcel->getActiveSheet()->setCellValue('AJ' . ($lastcell-1), '22');
            $objPHPExcel->getActiveSheet()->setCellValue('AK' . ($lastcell-1), '23');
            $objPHPExcel->getActiveSheet()->setCellValue('AL' . ($lastcell-1), '24');
            $objPHPExcel->getActiveSheet()->setCellValue('AM' . ($lastcell-1), '25');
            $objPHPExcel->getActiveSheet()->setCellValue('AN' . ($lastcell-1), '26');
            $objPHPExcel->getActiveSheet()->setCellValue('AO' . ($lastcell-1), '27');


            if ($col_letters[$day_num] == 'AP') {
                $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($lastcell-1), '28');
            } elseif ($col_letters[$day_num] == 'AQ') {
                $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($lastcell-1), '28');
                $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($lastcell-1), '29');
            } elseif ($col_letters[$day_num] == 'AR') {
                $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($lastcell-1), '28');
                $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($lastcell-1), '29');
                $objPHPExcel->getActiveSheet()->setCellValue('AR' . ($lastcell-1), '30');
            } elseif ($col_letters[$day_num] == 'AS') {
                $objPHPExcel->getActiveSheet()->setCellValue('AP' . ($lastcell-1), '28');
                $objPHPExcel->getActiveSheet()->setCellValue('AQ' . ($lastcell-1), '29');
                $objPHPExcel->getActiveSheet()->setCellValue('AR' . ($lastcell-1), '30');
                $objPHPExcel->getActiveSheet()->setCellValue('AS' . ($lastcell-1), '31');
            }


            $objPHPExcel->getActiveSheet()->mergeCells('A' . ($lastcell) .':A' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('B' . ($lastcell) .':B' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('C' . ($lastcell) .':C' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('D' . ($lastcell) .':D' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('E' . ($lastcell) .':E' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('F' . ($lastcell) .':F' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('G' . ($lastcell) .':G' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('H' . ($lastcell) .':H' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('K' . ($lastcell) .':K' . ($lastcell+1));
            $objPHPExcel->getActiveSheet()->mergeCells('N' . ($lastcell) .':N' . ($lastcell+1));


            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($lastcell), '合计');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($lastcell), $count['l_m_come_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($lastcell), $count['l_m_dialog_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($lastcell), number_format((($count['l_m_come_count']/$count['l_m_dialog_count']) * 100) , 2, '.', '') . '%');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($lastcell), $count['task_come_num_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . ($lastcell), $count['target_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . ($lastcell), $count['task_dialog_num_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . ($lastcell), number_format((($count['task_come_num_count']/$count['task_dialog_num_count']) * 100) , 2, '.', '') . '%');


            $objPHPExcel->getActiveSheet()->setCellValue('I' . ($lastcell), '当前对话');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . ($lastcell+1), '当前到诊');


            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($lastcell), $count_search['dialog_count_search']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($lastcell+1), $count_search['come_count_search']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . ($lastcell), number_format((($count_search['come_count_search']/$count_search['dialog_count_search']) * 100) , 2, '.', '') . '%');

            $objPHPExcel->getActiveSheet()->setCellValue('L' . ($lastcell), '当月对话');
            $objPHPExcel->getActiveSheet()->setCellValue('L' . ($lastcell+1), '当月到诊');


            $objPHPExcel->getActiveSheet()->setCellValue('M' . ($lastcell), $count['dialog_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . ($lastcell+1), $count['come_count']);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . ($lastcell), number_format((($count['come_count']/$count['dialog_count']) * 100) , 2, '.', '') . '%');

            for ($i=1; $i < $day_num+1; $i++) {
                $col_name_come = $i.'_day_come_num';
                $col_name_dia = $i.'_day_dialog_num';
                $col_name_come_count = $i.'_day_come_num_count';
                $col_name_dia_count = $i.'_day_dialog_num_count';
                $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$i] . ($lastcell), $count[$col_name_dia_count]);
                $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$i] . ($lastcell+1), $count[$col_name_come_count]);
            }



            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell-1) . ':' . $col_letters[$day_num] . ($lastcell-1))->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell-1) . ':' . $col_letters[$day_num] . ($lastcell-1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell-1) . ':' . $col_letters[$day_num] . ($lastcell-1))->getFont()->setSize(12);
            //边框
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell-1) . ':' . $col_letters[$day_num] . ($lastcell-1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell-1) . ':' . $col_letters[$day_num] . ($lastcell-1))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell) . ':' . $col_letters[$day_num] . ($lastcell))->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell) . ':' . $col_letters[$day_num] . ($lastcell))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell) . ':' . $col_letters[$day_num] . ($lastcell))->getFont()->setSize(12);
            //边框
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell) . ':' . $col_letters[$day_num] . ($lastcell))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell) . ':' . $col_letters[$day_num] . ($lastcell))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell+1) . ':' . $col_letters[$day_num] . ($lastcell+1))->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell+1) . ':' . $col_letters[$day_num] . ($lastcell+1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell+1) . ':' . $col_letters[$day_num] . ($lastcell+1))->getFont()->setSize(12);
            //边框
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell+1) . ':' . $col_letters[$day_num] . ($lastcell+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($lastcell+1) . ':' . $col_letters[$day_num] . ($lastcell+1))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
        }

        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$res_hos['hos_name'].$cur_month.'月目标分解.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }



    /**
     * 病种区域功能
     */

    public function analyze(){
        $data = array();
        $data = $this->common->config('analyze');


        $hos_id = trim(intval($_REQUEST['hos_id']));
        $type = trim(intval($_REQUEST['type']));//1:男科;2:妇科
        $date = trim($_REQUEST['date']);

        $parse = '';
        if (empty($hos_id)) {
            $hos_id = '';
            $parse .= '';//默认空
        } else {
            $parse .= '&hos_id='.$hos_id;
        }
        if (empty($type)) {
            $type = '1';
            $parse .= '&type=1';//默认男科
        } else {
            $parse .= '&type='.$type;
        }
        if (empty($date) || !checkDateTime($date)) {
            $parse .= '&date='.date('Y-m',time());//默认当月
            $data['default_month'] = intval(date('m',time())).'月';
            $data['date'] = date('Y-m',time());
            //
            //echo $data['date'];exit();
        } else {
            $parse .= '&date='.$date;
            $data['default_month'] = intval(date('m',strtotime($date))).'月';
            $data['date'] = $date;
        }


        $data['hospital'] = $this->model->hospital_order_list();


        $sql = "select * from " . $this->common->table('report_bz') ." where type = {$type} and is_show = 1 order by `order` asc";
        $row = $this->common->getAll($sql);

        $array_tree = getDataTree($row,'id','pid');

        $table_bz_header_top = '';
        $table_bz_header_bot = '';

        $n = 0;
        foreach ($array_tree as $key => $value) {
            if (empty($value['child'])) {
                $table_bz_header_top .= "<th lay-data=\"{field:'bz_name_".$value['id']."',edit: 'text', align:'center'}\" rowspan=\"2\">".$value['bz_name']."</th>\n";
                $n++;
            } else {

                foreach ($value['child'] as $k => $v) {
                    $table_bz_header_bot .= "<th lay-data=\"{field:'bz_name_".$v['id']."', edit: 'text', align:'center'}\">".$v['bz_name']."</th>\n";
                    $n++;
                }

                $table_bz_header_top .= "<th lay-data=\"{field:'bz_name_".$value['id']."', edit: 'text', align:'center'}\" colspan=\"".count($value['child'])."\">".$value['bz_name']."</th>\n";
            }
        }



        $data['bz_col'] = $n;
        $data['table_bz_header_top'] = $table_bz_header_top;
        $data['table_bz_header_bot'] = $table_bz_header_bot;



//p($table_bz_header_top);die();

        $data['hos_id'] = $hos_id;
        $data['type'] = $type;
        $data['parse'] = $parse;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('report/analyze', $data);

    }



    public function bz_area_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $hos_id = !empty($_REQUEST['hos_id']) ? trim(intval($_REQUEST['hos_id'])) : '0';
        $type = !empty($_REQUEST['type']) ? trim(intval($_REQUEST['type'])) : '1';//1:男科;2:妇科
        $date = !empty($_REQUEST['date']) ? trim($_REQUEST['date']) : '';


        if (empty($date) || !checkDateTime($date)) {
            show_404();
        }

        $time = strtotime($date);


        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
        $start_time = strtotime($s_time . ' 00:00:00');
        $end_time = strtotime($e_time . ' 23:59:59');

        $day_num = date('t',$time);

        //echo $day_num;die;

        $where =' where 1';
        $where_bz =' where 1 and b.is_show = 1';
        if (!empty($hos_id)) {
            $where .= " and hos_id = ".$hos_id;
            $where_bz .= " and a.hos_id = ".$hos_id;
        } else {
            $where.= " and hos_id in (" . $_COOKIE["l_hos_id"] . ")";
            $where_bz.= " and a.hos_id in (" . $_COOKIE["l_hos_id"] . ")";
        }

        if (!empty($type)) {
            $where .= " and type = ".$type;
            $where_bz .= " and b.type = ".$type;
        }

        if (!empty($time)) {
            $where .= " and time between ".$start_time." and ".$end_time;
            $where_bz .= " and a.time between ".$start_time." and ".$end_time;
        }


        if ($type == 1) {//男科
            $sql = "select id,one_word,two_word,more_word,time from " . $this->common->table('report_area_analysis') . $where;
            $row = $this->common->getAll($sql);
            //p($row);

            $sql_bz = "select * from " . $this->common->table('report_bz_analysis'). " as a left join "  . $this->common->table('report_bz')." as b on a.bzid = b.id". $where_bz;

            $row_bz = $this->common->getAll($sql_bz);
            //p($row_bz);exit();

            $row_bz_tree = array();
            foreach ($row_bz as $key => $value) {
                $row_bz_tree[$value['id']][] = $value;
            }

            $new_row = array();
            for ($i=0; $i < $day_num; $i++) {

                if (empty($row)) {
                    $new_row[$i]['num'] = $i+1;
                } else {
                    $new_row[$i]['num'] = $i+1;
                    foreach ($row as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['id'] = $value['id'];
                            $new_row[$i]['one_word'] = $value['one_word'];
                            $new_row[$i]['two_word'] = $value['two_word'];
                            $new_row[$i]['more_word'] = $value['more_word'];
                            $new_row[$i]['all_word'] = $value['one_word']+$value['two_word']+$value['more_word'];

                        }
                    }

                    foreach ($row_bz as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['bz_name_'.$value['id']] = $value['bz_num'];
                        }
                    }

                    if ($i+1 == $day_num) {
                        $new_row[$i+2]['num'] = '合计';
                        foreach ($row as $key => $value) {
                            $new_row[$i+2]['one_word'] += $value['one_word'];
                            $new_row[$i+2]['two_word'] += $value['two_word'];
                            $new_row[$i+2]['more_word'] += $value['more_word'];
                            $new_row[$i+2]['all_word'] += $value['one_word']+$value['two_word']+$value['more_word'];
                        }
                        foreach ($row_bz_tree as $key => $value) {
                            foreach ($value as $k => $val) {
                                $new_row[$i+2]['bz_name_'.$key] += $val['bz_num'];
                            }
                        }
                    }
                }
            }
        } elseif ($type == 2) {//妇科
            $sql = "select id,local_city,local_province,other_way,time from " . $this->common->table('report_area_analysis') . $where;
            $row = $this->common->getAll($sql);
            //p($row);die();
            $sql_bz = "select * from " . $this->common->table('report_bz_analysis'). " as a left join "  . $this->common->table('report_bz')." as b on a.bzid = b.id". $where_bz;

            $row_bz = $this->common->getAll($sql_bz);
            //p($row_bz);


            $row_bz_tree = array();
            foreach ($row_bz as $key => $value) {
                $row_bz_tree[$value['id']][] = $value;
            }


            $new_row = array();
            for ($i=0; $i < $day_num; $i++) {

                if (empty($row)) {
                    $new_row[$i]['num'] = $i+1;
                } else {
                    $new_row[$i]['num'] = $i+1;
                    foreach ($row as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['id'] = $value['id'];
                            $new_row[$i]['local_city'] = $value['local_city'];
                            $new_row[$i]['local_province'] = $value['local_province'];
                            $new_row[$i]['other_way'] = $value['other_way'];
                            $new_row[$i]['all_word'] = $value['local_city']+$value['local_province']+$value['other_way'];
                        }
                    }
                    foreach ($row_bz as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['bz_name_'.$value['id']] = $value['bz_num'];
                        }
                    }

                    if ($i+1 == $day_num) {
                        $new_row[$i+2]['num'] = '合计';
                        foreach ($row as $key => $value) {
                            $new_row[$i+2]['local_city'] += $value['local_city'];
                            $new_row[$i+2]['local_province'] += $value['local_province'];
                            $new_row[$i+2]['other_way'] += $value['other_way'];
                            $new_row[$i+2]['all_word'] += $value['local_city']+$value['local_province']+$value['other_way'];
                        }

                        foreach ($row_bz_tree as $key => $value) {
                            foreach ($value as $k => $val) {
                                $new_row[$i+2]['bz_name_'.$key] += $val['bz_num'];
                            }
                        }
                    }

                }
            }
        }

        //p($new_row);die();

        $result = array('code'=>0,'count'=>count($new_row),'data'=>$new_row,'msg'=>'');
        echo json_encode($result);
    }


    public function add_data_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('add_data_ajax');

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';
        $t = !empty($_REQUEST['t']) ? trim(intval($_REQUEST['t'])) : '';
        $d = !empty($_REQUEST['d']) ? trim($_REQUEST['d']) : '';
        $type = !empty($_REQUEST['type']) ? trim(intval($_REQUEST['type'])) : '';
        $field = !empty($_REQUEST['field']) ? trim($_REQUEST['field']) : '';
        $value = !empty($_REQUEST['value']) ? trim(intval($_REQUEST['value'])) : '0';
        $ks_type = !empty($_REQUEST['ks_type']) ? trim(intval($_REQUEST['ks_type'])) : '1';//1，男科；2，妇科
        $hos_id = !empty($_REQUEST['hos_id']) ? trim(intval($_REQUEST['hos_id'])) : '0';


        if (empty($type) || empty($field) || empty($t) || empty($d) || !checkDateTime($d)) {
            echo json_encode(array('code'=>'0'));
            exit();
        }

        $time = strtotime($d."-".ct($t));

        if (intval(date('t',strtotime($d))) < intval($t)) {
            echo json_encode(array('code'=>'0'));
            exit();
        }

        if ($type == 1) {//编辑

            $arr = array(
                'time'=> $time,
                $field => $value,
                'admin_id' => $_COOKIE['l_admin_id'],
                'add_time' => time(),
                'hos_id'   => $hos_id,
                'type'   => $ks_type
            );


            $res = $this->db->update($this->db->dbprefix . "report_area_analysis", $arr, array(
                'id' => $id
            ));


            if ($res) {
                echo json_encode(array('code'=>'1'));
                exit();
            } else {
                echo json_encode(array('code'=>'0'));
                exit();
            }

        } elseif ($type == 2) {//添加

            $arr = array(
                'time'=> $time,
                $field => $value,
                'admin_id' => $_COOKIE['l_admin_id'],
                'add_time' => time(),
                'hos_id'   => $hos_id,
                'type'   => $ks_type
            );


            $tt =  $this->db->get_where($this->db->dbprefix . "report_area_analysis", array('time' => $time,'type' => $ks_type,'hos_id' => $hos_id))->result_array();

            if ($tt) {
                $ts = $this->db->update($this->db->dbprefix . "report_area_analysis", $arr, array(
                    'time' => $time,'hos_id' => $hos_id,'type'   => $ks_type
                ));
                if ($ts) {
                    echo json_encode(array('code'=>'1'));
                    exit();
                } else {
                    echo json_encode(array('code'=>'0'));
                    exit();
                }
            } else {

                $res = $this->db->insert($this->db->dbprefix . "report_area_analysis", $arr, array(
                    'id' => $id
                ));


                if ($res) {
                    echo json_encode(array('code'=>'1'));
                    exit();
                } else {
                    echo json_encode(array('code'=>'0'));
                    exit();
                }
            }

        } else {
            echo json_encode(array('code'=>'0'));
            exit();
        }

    }



    public function bz_data_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('bz_data_ajax');

        $t = !empty($_REQUEST['t']) ? trim(intval($_REQUEST['t'])) : '';
        $d = !empty($_REQUEST['d']) ? trim($_REQUEST['d']) : '';

        $field = !empty($_REQUEST['field']) ? trim($_REQUEST['field']) : '';
        $value = !empty($_REQUEST['value']) ? trim(intval($_REQUEST['value'])) : '0';
        $ks_type = !empty($_REQUEST['ks_type']) ? trim(intval($_REQUEST['ks_type'])) : '1';//1，男科；2，妇科
        $hos_id = !empty($_REQUEST['hos_id']) ? trim(intval($_REQUEST['hos_id'])) : '0';


        if ( empty($field) || empty($t) || empty($d) || !checkDateTime($d)) {
            echo json_encode(array('code'=>'0'));
            exit();
        }

        $time = strtotime($d."-".ct($t));

        $d_str = 'bz_name_';
        $id = intval(str_replace($d_str, '', $field));

        if ($id == '0') {
            echo json_encode(array('code'=>'0'));
            exit();
        }

        $rs_ex = $this->db->get_where($this->db->dbprefix . "report_bz_analysis", array('bzid' => $id,'time' => $time,'hos_id' => $hos_id))->result_array();

        if (empty($rs_ex)) {
            $type = 2;
        } else {
            $type = 1;
        }


        if ($type == 1) {//编辑

            $arr = array(
                'bz_num' => $value,
                'admin_id' => $_COOKIE['l_admin_id'],
                'add_time' => time()
            );


            $res = $this->db->update($this->db->dbprefix . "report_bz_analysis", $arr, array(
                'bzid' => $id,
                'time' => $time,
                'hos_id' => $hos_id
            ));


            if ($res) {
                echo json_encode(array('code'=>'1','msg'=>'编辑成功！'));
                exit();
            } else {
                echo json_encode(array('code'=>'0','msg'=>'编辑成功！'));
                exit();
            }

        } elseif ($type == 2) {//添加

            $arr = array(
                'time'=> $time,
                'bz_num' => $value,
                'admin_id' => $_COOKIE['l_admin_id'],
                'add_time' => time(),
                'hos_id'   => $hos_id,
                'bzid'   => $id
            );


            $res = $this->db->insert($this->db->dbprefix . "report_bz_analysis", $arr);

            if ($res) {
                echo json_encode(array('code'=>'1','msg'=>'添加成功！'));
                exit();
            } else {
                echo json_encode(array('code'=>'0','msg'=>'添加失败！'));
                exit();
            }

        } else {
            echo json_encode(array('code'=>'0'));
            exit();
        }

    }


    public function bzindex(){
        $data = array();
        $data = $this->common->config('bzindex');
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('report/bzindex', $data);
    }

    public function add_bz(){
        $data = array();
        $data = $this->common->config('add_bz');


        $sql = "select * from " . $this->common->table('report_bz') ." order by `order` asc";

        $row = $this->common->getAll($sql);
        // p(trees($row));die();
        $data['info'] = trees($row);

        $this->load->view('report/add_bz', $data);

    }

    public function edit_bz(){
        $data = array();
        $data = $this->common->config('edit_bz');

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';

        if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
            $id = trim(intval($_REQUEST['id']));
        } else {
            show_404();
        }

        $sqls = "select * from " . $this->common->table('report_bz') ." where id = ".$id;

        $res = $this->common->getRow($sqls);

        if (!$res) {
            show_404();
            exit();
        }

        $data['data'] = $res;

        $sql = "select * from " . $this->common->table('report_bz') ." order by `order` asc";

        $row = $this->common->getAll($sql);
        // p(trees($row));die();
        $data['info'] = trees($row);


        $this->load->view('report/edit_bz', $data);

    }

    public function bz_list_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $sql = "select * from " . $this->common->table('report_bz') ." order by `order` asc";
        $row = $this->common->getAll($sql);

        $array_tree = trees($row);

        foreach ($array_tree as $key => $value) {
            $array_tree[$key]['bz_name_c'] = $value['html'].$value['bz_name'];
            $array_tree[$key]['show'] = $value['is_show'] == 1 ? '<button class="layui-btn layui-btn-normal layui-btn-xs">启用</button>':'<button class="layui-btn layui-btn-primary layui-btn-xs">停用</button>';
        }

        //var_dump($array_tree);die();

        $result = array('code'=>0,'count'=>count($row),'data'=>$array_tree,'msg'=>'');
        echo json_encode($result);
    }

    public function add_bz_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('add_bz');

        $bz_name = !empty($_REQUEST['bzname']) ? trim($_REQUEST['bzname']) : '';
        $pid = !empty($_REQUEST['pid']) ? trim(intval($_REQUEST['pid'])) : '0';
        $type = !empty($_REQUEST['type']) ? trim(intval($_REQUEST['type'])) : '1';

        if (!empty($_REQUEST['switch']) && $_REQUEST['switch'] == 'on') {
            $switch = '1';
        } else {
            $switch = '0';
        }

        $order = !empty($_REQUEST['order']) ? trim(intval($_REQUEST['order'])) : '0';

        $arr = array(
            'bz_name' => $bz_name,
            'pid' => $pid,
            'order' => $order,
            'type' => $type,
            'is_show' => $switch
        );

        $sql = "select id from " . $this->common->table('report_bz') . " where bz_name = '".$bz_name."' and pid = '".$pid."' and type = '".$type."'";

        $row = $this->common->getOne($sql);

        if ($row) {
            echo json_encode(array('code'=>'0'));
        } else {
            $this->db->insert($this->db->dbprefix . "report_bz", $arr);
            $id = $this->db->insert_id();
            if ($id) {
                echo json_encode(array('code'=>'1'));
            } else {
                echo json_encode(array('code'=>'0'));
            }
        }

    }

    public function edit_bz_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('add_bz');

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';
        $bz_name = !empty($_REQUEST['bzname']) ? trim($_REQUEST['bzname']) : '';
        $pid = !empty($_REQUEST['pid']) ? trim(intval($_REQUEST['pid'])) : '0';
        $type = !empty($_REQUEST['type']) ? trim(intval($_REQUEST['type'])) : '1';


        if (!empty($_REQUEST['switch']) && $_REQUEST['switch'] == 'on') {
            $switch = '1';
        } else {
            $switch = '0';
        }

        $order = !empty($_REQUEST['order']) ? trim(intval($_REQUEST['order'])) : '0';

        $arr = array(
            'bz_name' => $bz_name,
            'pid' => $pid,
            'order' => $order,
            'type' => $type,
            'is_show' => $switch
        );


        $res = $this->db->update($this->db->dbprefix . "report_bz", $arr, array(
            'id' => $id
        ));


        if ($res) {
            echo json_encode(array('code'=>'1'));
        } else {
            echo json_encode(array('code'=>'0'));
        }

    }


    public function delete_bz_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('add_bz');

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';



        $res = $this->db->delete($this->db->dbprefix . "report_bz", array(
            'id' => $id
        ));


        if ($res) {
            echo json_encode(array('code'=>'1'));
        } else {
            echo json_encode(array('code'=>'0'));
        }

    }

    public function delete_batch_bz_ajax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

       // $data = array();
        //$data = $this->common->config('add_bz');

        $ids = !empty($_REQUEST['ids']) ? trim($_REQUEST['ids']) : '';

        $array_ids = explode(',', $ids);
       // p($array_ids);die();

        $res = $this->db->where_in('id', $array_ids)->delete($this->db->dbprefix . "report_bz");


        if ($res) {
            echo json_encode(array('code'=>'1'));
        } else {
            echo json_encode(array('code'=>'0'));
        }

    }



    public function export_analysis(){
        header('content-type:text/html;charset=utf-8');
        //p($_GET);die();
        $hos_id = !empty($_REQUEST['hos_id']) ? trim(intval($_REQUEST['hos_id'])) : '0';
        $type = !empty($_REQUEST['type']) ? trim(intval($_REQUEST['type'])) : '1';//1:男科;2:妇科
        $date = !empty($_REQUEST['date']) ? trim($_REQUEST['date']) : '';


        if (empty($date) || !checkDateTime($date)) {
            show_404();
        }

        $time = strtotime($date);

        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
        $start_time = strtotime($s_time . ' 00:00:00');
        $end_time = strtotime($e_time . ' 23:59:59');

        $day_num = date('t',$time);
        $month = intval(date('m',$time));


        //获取医院信息
        $sql_hos = "select hos_id,hos_name from hui_hospital where hos_id = {$hos_id}";
        $res_hos = $this->common->getRow($sql_hos);
        if (empty($res_hos)) {
            show_404();
        }

        //获取病种
        $sql_bz_list = "select * from " . $this->common->table('report_bz') ." where type = {$type} and is_show = 1 order by `order` asc";
        $row_bz_list = $this->common->getAll($sql_bz_list);
        $array_tree = getDataTree($row_bz_list,'id','pid');


        //字母日期对照
        //病种数量不要超过31个
        $col_letters =array(
            '1' => 'F','2' => 'G','3' => 'H','4' => 'I','5' => 'J','6' => 'K','7' => 'L','8' => 'M','9' => 'N','10' => 'O','11' => 'P','12' => 'Q','13' => 'R','14' => 'S','15' => 'T','16' => 'U','17' => 'V','18' => 'W','19' => 'X','20' => 'Y','21' => 'Z','22' => 'AA','23' => 'AB','24' => 'AC','25' => 'AD','26' => 'AE','27' => 'AF','28' => 'AG','29' => 'AH','30' => 'AI','31' => 'AJ'
        );


        $where =' where 1';
        $where_bz =' where 1 and b.is_show = 1';
        if (!empty($hos_id)) {
            $where .= " and hos_id = ".$hos_id;
            $where_bz .= " and a.hos_id = ".$hos_id;
        } else {
            $where.= " and hos_id in (" . $_COOKIE["l_hos_id"] . ")";
            $where_bz.= " and a.hos_id in (" . $_COOKIE["l_hos_id"] . ")";
        }

        if (!empty($type)) {
            $where .= " and type = ".$type;
            $where_bz .= " and b.type = ".$type;
        }

        if (!empty($time)) {
            $where .= " and time between ".$start_time." and ".$end_time;
            $where_bz .= " and a.time between ".$start_time." and ".$end_time;
        }


        if ($type == 1) {//男科
            $sql = "select id,one_word,two_word,more_word,time from " . $this->common->table('report_area_analysis') . $where;
            $row = $this->common->getAll($sql);
            //p($row);
            $sql_bz = "select a.id,a.bz_num,a.time,a.bzid from " . $this->common->table('report_bz_analysis'). " as a left join "  . $this->common->table('report_bz')." as b on a.bzid = b.id". $where_bz;
            $row_bz = $this->common->getAll($sql_bz);
            //p($row_bz);

            $new_row_bz = array();
            foreach ($row_bz as $key => $value) {
                $new_row_bz[$value['id']] = $value;
            }
            //p($new_row_bz);exit();

            //查询病种
           // $bz_new = array();
            foreach ($array_tree as $key => $value) {
                if (empty($value['child'])) {
                    $bz_new[$value['id']]=$value;
                } else {
                    foreach ($value['child'] as $k => $v) {
                        $bz_new[$v['id']]=$v;
                    }
                }
            }

            $zimu_bz = array();
            $z = 1;
            foreach ($bz_new as $key => $value) {
                $zimu_bz[$z] = $value;
                $z++;
            }

            foreach ($zimu_bz as $key => $value) {
                $zimu_bz[$key]['zm'] = $col_letters[$key];
            }


            $zimu_bz_new = array();

            foreach ($zimu_bz as $key => $value) {
                $zimu_bz_new[$value['id']] = $value;
            }

            foreach ($zimu_bz_new as $key => $value) {
                foreach ($new_row_bz as $k => $val) {
                    if ($key == $val['bzid']) {
                        $zimu_bz_new[$key]['sub'][$k] = $val;
                    }
                }
            }

            // p($zimu_bz_new);exit();

            $new_row = array();
            for ($i=0; $i < $day_num; $i++) {

                if (empty($row)) {
                    $new_row[$i]['num'] = $i+1;
                } else {
                    $new_row[$i]['num'] = $i+1;
                    foreach ($row as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['id'] = $value['id'];
                            $new_row[$i]['one_word'] = $value['one_word'];
                            $new_row[$i]['two_word'] = $value['two_word'];
                            $new_row[$i]['more_word'] = $value['more_word'];
                            $new_row[$i]['all_word1'] = $value['one_word']+$value['two_word']+$value['more_word'];
                        }
                    }
                }
            }
        } elseif ($type == 2) {//妇科
            $sql = "select id,local_city,local_province,other_way,time from " . $this->common->table('report_area_analysis') . $where;
            $row = $this->common->getAll($sql);
            //p($row);die();
            $sql_bz = "select a.id,a.bz_num,a.time,a.bzid from " . $this->common->table('report_bz_analysis'). " as a left join "  . $this->common->table('report_bz')." as b on a.bzid = b.id". $where_bz;
            $row_bz = $this->common->getAll($sql_bz);

            $new_row_bz = array();
            foreach ($row_bz as $key => $value) {
                $new_row_bz[$value['id']] = $value;
            }
            //p($new_row_bz);

            //查询病种
           // $bz_new = array();
            foreach ($array_tree as $key => $value) {
                if (empty($value['child'])) {
                    $bz_new[$value['id']]=$value;
                } else {
                    foreach ($value['child'] as $k => $v) {
                        $bz_new[$v['id']]=$v;
                    }
                }
            }

            $zimu_bz = array();
            $z = 1;
            foreach ($bz_new as $key => $value) {
                $zimu_bz[$z] = $value;
                $z++;
            }

            foreach ($zimu_bz as $key => $value) {
                $zimu_bz[$key]['zm'] = $col_letters[$key];
            }


            $zimu_bz_new = array();

            foreach ($zimu_bz as $key => $value) {
                $zimu_bz_new[$value['id']] = $value;
            }

            foreach ($zimu_bz_new as $key => $value) {
                foreach ($new_row_bz as $k => $val) {
                    if ($key == $val['bzid']) {
                        $zimu_bz_new[$key]['sub'][$k] = $val;
                    }
                }
            }


            $new_row = array();
            for ($i=0; $i < $day_num; $i++) {

                if (empty($row)) {
                    $new_row[$i]['num'] = $i+1;
                } else {
                    $new_row[$i]['num'] = $i+1;
                    foreach ($row as $key => $value) {
                        $day = intval(date('d',$value['time']));
                        if ( $i+1 == $day ) {
                            $new_row[$i]['id'] = $value['id'];
                            $new_row[$i]['local_city'] = $value['local_city'];
                            $new_row[$i]['local_province'] = $value['local_province'];
                            $new_row[$i]['other_way'] = $value['other_way'];
                            $new_row[$i]['all_word2'] = $value['local_city']+$value['local_province']+$value['other_way'];
                        }
                    }
                }
            }
        }

        $count_all = array();
        foreach ($new_row as $key => $value) {
            $count_all['all_one'] += $value['one_word'];
            $count_all['all_two'] += $value['two_word'];
            $count_all['all_more'] += $value['more_word'];
            $count_all['all_word1'] += $value['all_word1'];
            $count_all['all_local_c'] += $value['local_city'];
            $count_all['all_local_p'] += $value['local_province'];
            $count_all['all_local_o'] += $value['other_way'];
            $count_all['all_word2'] += $value['all_word2'];
        }

        //p($count_all);exit();


        if ($type == 1) {
            $type_str = '男科';
        } else {
            $type_str = '妇科';
        }



        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle($type_str);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

        //设置列宽和行高
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);
        $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(22.5);
        //$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(4.25);
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(14.25);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


        $cn = 1;
        foreach ($array_tree as $key => $value) {
            if (empty($value['child'])) {
                $objPHPExcel->getActiveSheet()->mergeCells($col_letters[$cn].'3:'.$col_letters[$cn].'4');
                $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$cn].'3',$value['bz_name']);
                $cn++;
            } else {
                $ac = count($value['child']);
                $objPHPExcel->getActiveSheet()->mergeCells($col_letters[$cn].'3:'.$col_letters[$cn+$ac-1].'3');
                $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$cn].'3',$value['bz_name']);
                foreach ($value['child'] as $k => $v) {
                    $objPHPExcel->getActiveSheet()->setCellValue($col_letters[$cn].'4',$v['bz_name']);
                    $cn++;
                }
            }
        }

        $objPHPExcel->getActiveSheet()->mergeCells('A1:'.$col_letters[$cn-1].'1');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:E2');
        $objPHPExcel->getActiveSheet()->mergeCells($col_letters['1'].'2:'.$col_letters[$cn-1].'2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4');
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:C4');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:D4');
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$res_hos['hos_name'].$type_str.'商务通对话区域及病种分析');
        $objPHPExcel->getActiveSheet()->setCellValue('A2','日期');
        $objPHPExcel->getActiveSheet()->setCellValue('B2','区域分析');
        $objPHPExcel->getActiveSheet()->setCellValue($col_letters['1'].'2','病种分析');
        $objPHPExcel->getActiveSheet()->setCellValue('A3',$month.'月');


        if ($type == 1) {//男科
            $objPHPExcel->getActiveSheet()->setCellValue('B3','一句话');
            $objPHPExcel->getActiveSheet()->setCellValue('C3','三句话');
            $objPHPExcel->getActiveSheet()->setCellValue('D3','三句以上');
            $objPHPExcel->getActiveSheet()->setCellValue('E3','总对话');
        } else {//妇科
            $objPHPExcel->getActiveSheet()->setCellValue('B3','本市对话');
            $objPHPExcel->getActiveSheet()->setCellValue('C3','本省对话');
            $objPHPExcel->getActiveSheet()->setCellValue('D3','其他');
            $objPHPExcel->getActiveSheet()->setCellValue('E3','合计');
        }


        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle($col_letters['1'].'2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle($col_letters['1'].'2')->getFont()->setSize(12);




        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$col_letters[$cn-1].($day_num+5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:'.$col_letters[$cn-1].($day_num+5))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


        $all_num = count($new_row);

        //计算病种对话总数
        foreach ($zimu_bz_new as $key => $value) {
            if (!empty($value['sub'])) {
                foreach ($value['sub'] as $k => $val) {
                    $zimu_bz_new[$key]['sum_bz_num'] += $val['bz_num'];
                }
            }
        }

        //p($zimu_bz_new);exit();
        //p($new_row);exit();


        //设置区域分析
        $i = 5;
        foreach ($new_row as $key => $value) {
            if ($type == 1) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value['num']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value['one_word']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value['two_word']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value['more_word']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $value['all_word1']);
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value['num']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value['local_city']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value['local_province']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value['other_way']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $value['all_word2']);
            }

            $i ++;
        }

        //设置病种分析
        foreach ($zimu_bz_new as $key => $value) {
            if (!empty($value['sub']) && !empty($value['zm'])) {
                foreach ($value['sub'] as $k => $val) {
                    $day = intval(date('d',$val['time']));
                    $objPHPExcel->getActiveSheet()->setCellValue($value['zm'].($day+4), $val['bz_num']);
                    $objPHPExcel->getActiveSheet()->setCellValue($value['zm'].$i, $value['sum_bz_num']);
                }
            }
        }


        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '合计');
        if ($type == 1) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $count_all['all_one']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $count_all['all_two']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $count_all['all_more']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $count_all['all_word1']);
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $count_all['all_local_c']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $count_all['all_local_p']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $count_all['all_local_o']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $count_all['all_word2']);
        }




        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$res_hos['hos_name'].'网络咨询区域和病种分析表.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }


    /**
     * 留联到诊报表
     */

    public function lindex(){
        $data = array();
        $data = $this->common->config('lindex');

        $hos_id = !empty($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):0;
        $date = !empty($_REQUEST['date'])?$_REQUEST['date']:'';
        $ks_type = !empty($_REQUEST['ks_type'])?trim($_REQUEST['ks_type']):0;

        $keshi_ids = str_replace("_", ",", $ks_type);

        if ($ks_type != 0) {
            $where_is_ks = " AND a.keshi_id IN (" . $keshi_ids . ")";
        } else {
            $where_is_ks = "";
        }

        if (empty($hos_id) || empty($date)) {

            $data['hos_id'] = '';
            $start_time = strtotime(date('Y-m-d 00:00:00',time()));
            $end_time = strtotime(date('Y-m-d 23:59:59',time()));
            $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);

        } else {

            if (!empty($date)) {
                $arr_dt = explode(' - ', $date);
                if (count($arr_dt)<2) {
                    $data['dateMsg'] = array('code'=>1,'msg'=>'日期格式错误');
                }
                foreach ($arr_dt as $key => $value) {
                    if (!preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $value)) {
                        $data['dateMsg'] = array('code'=>2,'msg'=>'日期格式错误');
                    }
                }

                $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
                $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);

                $start_time = strtotime($s_t." 00:00:00");
                $end_time = strtotime($e_t." 23:59:59");
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            } else {
                $start_time = strtotime(date('Y-m-d 00:00:00',time()));
                $end_time = strtotime(date('Y-m-d 23:59:59',time()));
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            }

            $per_page_num = 30;
            $per_page = !empty($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;

            $where = " where 1 ";
            $limit = " limit {$per_page},{$per_page_num}";
            $groupby = " group by a.admin_id";

            if ($_COOKIE["l_admin_action"] == "all" && empty($_COOKIE["l_hos_id"])) {
                $where .= "";
            }

            if (!empty($hos_id)) {
                $where .= ' AND a.hos_id = '.$hos_id.$where_is_ks;
            } else {
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND a.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND a.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($date)) {
                $where .= ' AND a.order_addtime between '.$start_time.' and '.$end_time;
            }



            $res = array();
            //获取留联量
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_num from hui_order_liulian as a ".$where.$groupby;
            $nn = $this->common->getAll($sql);

            foreach ($nn as $key => $value) {
                $res[$value['admin_id']] = $value;
                $res[$value['admin_id']]['ll_yy_num'] = 0;
                $res[$value['admin_id']]['ll_dz_num'] = 0;
            }

            //获取留联预约量
            $where_yy = " AND a.order_no_yy is not null";
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_yy_num from hui_order_liulian as a ".$where.$where_yy.$groupby;
            $ss = $this->common->getAll($sql);

            foreach ($ss as $key => $value) {
                $res[$value['admin_id']]['ll_yy_num'] = $value['ll_yy_num'];
            }


            //获取留联到诊量
            $where_dz = " AND b.is_come = 1";
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_dz_num from hui_order_liulian as a left join hui_order as b on a.order_no_yy = b.order_no ".$where.$where_dz.$groupby;

            $dz = $this->common->getAll($sql);

            foreach ($dz as $key => $value) {
                $res[$value['admin_id']]['ll_dz_num'] = $value['ll_dz_num'];
            }

            foreach ($res as $key => $value) {
                $res[$key]['ll_yy'] = number_format(($value['ll_yy_num']/$value['ll_num'])*100,2).'%';
                $res[$key]['ll_dz'] = number_format(($value['ll_dz_num']/$value['ll_num'])*100,2).'%';
            }


            $data['info'] = $res;

            //p($data['info']);die();

            $heji = array();

            foreach ($res as $key => $value) {
                $heji['all_ll_num'] += $value['ll_num'];
                $heji['all_ll_yy_num'] += $value['ll_yy_num'];
                $heji['all_ll_dz_num'] += $value['ll_dz_num'];
            }

            $heji['all_ll_yy'] = number_format(($heji['all_ll_yy_num']/$heji['all_ll_num'])*100,2).'%';
            $heji['all_ll_dz'] = number_format(($heji['all_ll_dz_num']/$heji['all_ll_num'])*100,2).'%';

            $data['heji'] = $heji;
            $data['hos_id'] = $hos_id;
            $data['parse'] = '&date='.$data['date'].'&hos_id='.$hos_id.'&ks_type='.$ks_type;

        }

        //获取配置留联到诊报表男科妇科区分数组
        $liulian_dz_ks = $this->config->item('liulian_dz_ks');

        $data['ks_type_list'] = $liulian_dz_ks[$hos_id];

        $data['ks_type'] = $ks_type;





        $data['hospital'] = $this->model->hospital_order_list();

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('report/liulian_order_daozhen', $data);
    }




    public function dump_liulian(){
        $data = array();
        $data = $this->common->config('dump_liulian');

        $hos_id = !empty($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):'0';
        $date = !empty($_REQUEST['date'])?$_REQUEST['date']:'';

        $ks_type = !empty($_REQUEST['ks_type'])?trim($_REQUEST['ks_type']):0;

        $keshi_ids = str_replace("_", ",", $ks_type);

        if ($ks_type != 0) {
            $where_is_ks = " AND a.keshi_id IN (" . $keshi_ids . ")";
        } else {
            $where_is_ks = "";
        }

        if (empty($hos_id) || empty($date)) {

            $data['hos_id'] = '';
            $start_time = strtotime(date('Y-m-d 00:00:00',time()));
            $end_time = strtotime(date('Y-m-d 23:59:59',time()));
            $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);

        } else {

            if (!empty($date)) {
                $arr_dt = explode(' - ', $date);
                if (count($arr_dt)<2) {
                    $data['dateMsg'] = array('code'=>1,'msg'=>'日期格式错误');
                }
                foreach ($arr_dt as $key => $value) {
                    if (!preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $value)) {
                        $data['dateMsg'] = array('code'=>2,'msg'=>'日期格式错误');
                    }
                }

                $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
                $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);

                $start_time = strtotime($s_t." 00:00:00");
                $end_time = strtotime($e_t." 23:59:59");
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            } else {
                $start_time = strtotime(date('Y-m-d 00:00:00',time()));
                $end_time = strtotime(date('Y-m-d 23:59:59',time()));
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            }

            $per_page_num = 30;
            $per_page = !empty($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;

            $where = " where 1 ";
            $limit = " limit {$per_page},{$per_page_num}";
            $groupby = " group by a.admin_id";

            if ($_COOKIE["l_admin_action"] == "all" && empty($_COOKIE["l_hos_id"])) {
                $where .= "";
            }

            if (!empty($hos_id)) {
                $where .= ' AND a.hos_id = '.$hos_id.$where_is_ks;
            } else {
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND a.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND a.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($date)) {
                $where .= ' AND a.order_addtime between '.$start_time.' and '.$end_time;
            }



            $res = array();
            //获取留联量
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_num from hui_order_liulian as a ".$where.$groupby;
            $nn = $this->common->getAll($sql);

            foreach ($nn as $key => $value) {
                $res[$value['admin_id']] = $value;
                $res[$value['admin_id']]['ll_yy_num'] = 0;
                $res[$value['admin_id']]['ll_dz_num'] = 0;
            }

            //获取留联预约量
            $where_yy = " AND a.order_no_yy is not null";
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_yy_num from hui_order_liulian as a ".$where.$where_yy.$groupby;
            $ss = $this->common->getAll($sql);

            foreach ($ss as $key => $value) {
                $res[$value['admin_id']]['ll_yy_num'] = $value['ll_yy_num'];
            }


            //获取留联到诊量
            $where_dz = " AND b.is_come = 1";
            $sql = "select a.admin_id,a.admin_name,count(a.order_id) as ll_dz_num from hui_order_liulian as a left join hui_order as b on a.order_no_yy = b.order_no ".$where.$where_dz.$groupby;

            $dz = $this->common->getAll($sql);

            foreach ($dz as $key => $value) {
                $res[$value['admin_id']]['ll_dz_num'] = $value['ll_dz_num'];
            }

            foreach ($res as $key => $value) {
                $res[$key]['ll_yy'] = number_format(($value['ll_yy_num']/$value['ll_num'])*100,2).'%';
                $res[$key]['ll_dz'] = number_format(($value['ll_dz_num']/$value['ll_num'])*100,2).'%';
            }


            $row = $res;

            //p($data['info']);die();

            $heji = array();

            foreach ($res as $key => $value) {
                $heji['all_ll_num'] += $value['ll_num'];
                $heji['all_ll_yy_num'] += $value['ll_yy_num'];
                $heji['all_ll_dz_num'] += $value['ll_dz_num'];
            }

            $heji['all_ll_yy'] = number_format(($heji['all_ll_yy_num']/$heji['all_ll_num'])*100,2).'%';
            $heji['all_ll_dz'] = number_format(($heji['all_ll_dz_num']/$heji['all_ll_num'])*100,2).'%';

        }
        //p($row);exit();

        $sql = "select hos_name from hui_hospital where hos_id = ".$hos_id;
        $rss = $this->common->getRow($sql);

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('留联数据报表');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$date.$rss['hos_name'].'留联数据报表');
        $objPHPExcel->getActiveSheet()->setCellValue('A2','咨询员');
        $objPHPExcel->getActiveSheet()->setCellValue('B2','留联量');
        $objPHPExcel->getActiveSheet()->setCellValue('C2','留联预约量');
        $objPHPExcel->getActiveSheet()->setCellValue('D2','留联到诊量');
        $objPHPExcel->getActiveSheet()->setCellValue('E2','留联预约率');
        $objPHPExcel->getActiveSheet()->setCellValue('F2','留联到诊率');


       //设置列宽和行高
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Microsoft Yahei');

        //设置单元格内文字自动换行
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);


        //设置单元格文字颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);


        //冻结
        $objPHPExcel->getActiveSheet()->freezePane('A2');

        //设置前两行字体
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setSize(13);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);

        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:F2')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

        $n = 3;
        foreach ($row as $key => $value) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.($n), $value['admin_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.($n), $value['ll_num']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.($n), $value['ll_yy_num']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.($n), $value['ll_dz_num']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.($n), $value['ll_yy']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.($n), $value['ll_dz']);
            $objPHPExcel->getActiveSheet()->getStyle('A'.($n).':F'.($n))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getStyle('A'.($n).':F'.($n))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
            $n++;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A'.($n), '合计');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.($n), $heji['all_ll_num']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.($n), $heji['all_ll_yy_num']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.($n), $heji['all_ll_dz_num']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.($n), $heji['all_ll_yy']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.($n), $heji['all_ll_dz']);

        $objPHPExcel->getActiveSheet()->getStyle('A'.($n).':F'.($n))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A'.($n).':F'.($n))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$date.$rss['hos_name'].'留联数据报表.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }



    public function ajax_get_cks(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $hos_id = isset($_REQUEST['hosId'])?intval($_REQUEST['hosId']):0;

        //获取配置留联到诊报表男科妇科区分数组
        $liulian_dz_ks = $this->config->item('liulian_dz_ks');

        $opt = "";
        $opt .= "<option value=\"0\">全部</option>";
        foreach ($liulian_dz_ks[$hos_id] as $key => $value) {
            $opt .= "<option value='".$value['ks_ids']."'>".$value['ks_name']."</option>";

        }

        if (!empty($liulian_dz_ks[$hos_id])) {

            $ajaxReturn = array('code'=>1,'msg'=>$opt);
            echo json_encode($ajaxReturn);
            exit();
        } else {
            $ajaxReturn = array('code'=>2,'msg'=>'');
            echo json_encode($ajaxReturn);
            exit();
        }

    }



}







































?>