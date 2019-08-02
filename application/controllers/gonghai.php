 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * 这个是公海患者的控制器
 *  2015-11-23
 *  千年老道
 */

class Gonghai extends CI_Controller{

    public function __construct() {
        //继承父类 其中调用的方法，各个函数都能够使用
        parent::__construct();
        $this->load->model('Gonghai_model');
        $this->load->model('Order_model');
		$this->lang->load('order');
		$this->lang->load('common');
		$this->model = $this->Gonghai_model;
    }


		   	/**
	日期合法校验
	**/
	public function dateCheck($data){
			$data = str_replace(array("年", "月"), "-", $data);
			$data = str_replace(array("日"), "", $data);
			$is_date=strtotime($data)?strtotime($data):false;
			if($is_date===false){
				return false;
			}else{
				return true;
			}
	}

    //参照预约列表的结构，所写的公海患者列表显示函数
    public function index(){
        	$data = array();
		$data           = $this->common->config('gonghai');
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号
		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话
		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';
		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;
		$bind         = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;
		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;
		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;
		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;
		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;
		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;
                $gonghai_type   = isset($_REQUEST['gonghai_type'])? trim($_REQUEST['gonghai_type']):'is_gonghai';

		/* 未定患者 */
		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$data['hos_id']   = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$data['p_n']      = $patient_name;
		$data['p_p']      = $patient_phone;
		$data['o_o']      = $order_no;
		$data['f_p_i']    = $from_parent_id;
		$data['f_i']      = $from_id;
		$data['a_i']      = $asker_name;
		$data['s']        = $status;
		$data['wx']        = $bind;
		$data['o_t']      = $type_id;
		$data['t']        = $order_type;
		$data['p_jb']     = $p_jb;
		$data['jb']       = $jb;
//判断日期是否为空
		if(!empty($date))//对查询的两个时间进行处理
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
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
		}
		else
		{
			if(isset($_COOKIE['start_time']))
			{
				$start = date("Y-m-d", $_COOKIE['start_time']);
				$end = date("Y-m-d", $_COOKIE['end_time']);
				$data['start_date'] = $start;
				$data['end_date'] = $end;
				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
			}
			else
			{
				$start = date("Y-m-d", time());
				$end = date("Y-m-d", time());
				$data['start_date'] = $start;
				$data['end_date'] = $end;
				$data['start'] = date("Y年m月d日", time());
				$data['end'] = date("Y年m月d日", time());
			}
		}
                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，
                //而$[start_date]表示xx-xx-xx这种格式的日期



                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表
		$hospital = $this->model->hospital_order_list();
		$keshi = $this->model->keshi_order_list();
		$keshi_arr = array();
                //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
		foreach($keshi as $val)
		{
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
		foreach($from_list as $val)
		{
			if($val['parent_id'] == 0)
			{
				$par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
			else
			{
				$from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
		}
		$from_list = $par_from_arr;
                //获取预约类别表Order_type中的所有数据
		$type_list = $this->model->type_order_list();

		$type_arr = array();
		foreach($type_list as $val)
		{
			$type_arr[$val['type_id']]['type_id'] = $val['type_id'];
			$type_arr[$val['type_id']]['type_name'] = $val['type_name'];
			$type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
			$type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
			$type_arr[$val['type_id']]['type_order'] = $val['type_order'];
		}
		$type_list = $type_arr;

		$hos_id_arr = array();
		$keshi_id_arr = array();
		$jibing = read_static_cache('jibing_list');
		$jibing_list = array();
		$jibing_parent = array();
		if(!empty($jibing))
		{
			foreach($jibing as $val)
			{
				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
				if($val['jb_level'] == 2)
				{
					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
				}
			}
		}

		$data['from_arr'] = $from_arr;
		$data['keshi'] = $keshi_arr;
		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;
		$hos_auth = array();
		foreach($hospital as $val)
		{
			$hos_id_arr[] = $val['hos_id'];
			if($val['ask_auth']){
				$hos_auth[] = $val['hos_id'];
			}
		}

		foreach($keshi as $val)
		{
			$keshi_id_arr[] = $val['keshi_id'];
		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$data['now_page'] = $page;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;
		$per_page = empty($per_page)? 30:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');//调用CI自带的page分页类

		/* 处理判断条件 */
		$where = 1;
		$orderby = '';
		if(empty($hos_id))
		{
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
		}
		else
		{
			$where .= ' AND o.hos_id = ' . $hos_id;
		}

		if(empty($keshi_id))
		{
			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}
		else
		{
			$where .= ' AND o.keshi_id = ' . $keshi_id;
		}


		if(!empty($from_parent_id))
		{
			$where .= ' AND o.from_parent_id = ' . $from_parent_id;
		}

		if(!empty($from_id))
		{
			$where .= ' AND o.from_id = ' . $from_id;
		}

		if(!empty($p_jb))
		{
			$where .= ' AND o.jb_parent_id = ' . $p_jb;
		}


		if(!empty($jb))
		{
			$where .= ' AND o.jb_id = ' . $jb;
		}

		if(!empty($asker_name))

		{
			$where .= " AND o.admin_name = '" . $asker_name . "'";
		}

		/* 订单状态 */
		if($status == 1)
		{
			$where .= ' AND o.is_come = 0';
		}
		elseif($status == 2)
		{
			$where .= ' AND o.is_come = 1';
		}
		elseif($status == 3)
		{
			$where .= ' AND o.doctor_time > 0';
		}

		if($bind == 1){

			$where .= ' AND p.pat_weixin is null';
		}elseif($bind == 2){

			$where .= ' AND  p.pat_weixin <> ""';
		}

		if(!empty($pro)&&is_numeric($pro)){
			$where .= ' AND  p.pat_province = '.$pro;
			$data['pro'] = $pro;
		}else{
			$data['pro'] = 0;
		}
		if(!empty($city)&&is_numeric($city)){
			$where .= ' AND  p.pat_city = '.$city;
			$data['city'] = $city;
		}
		if(!empty($are)&&is_numeric($are)){
			$where .= ' AND  p.pat_area = '.$are;
			$data['are'] = $are;
		}

		if(!empty($type_id))
		{
			$where .= " AND o.type_id = $type_id";
		}

		if($order_time_type == 1)
		{
			$where .= ' AND o.order_time = 0';
		}
		if($wu == 1)
		{
			$w_start = strtotime($start . ' 00:00:00') - 43200;
			$w_end = strtotime($end . ' 11:59:59');
		}
		else
		{
			$w_start = strtotime($start . ' 00:00:00');
			$w_end = strtotime($end . ' 23:59:59');
		}
                //根据gonghai_type的不同，来选择不同的选择条件
                if($gonghai_type=='is_gonghai'){
                  $where .=" AND o.gonghai_type='gonghai' AND o.is_come=0 ";

                }elseif($gonghai_type=='is_out'){
                   $where .=" AND o.gonghai_type!='gonghai' AND o.is_come=0 ";

                }


		/* 时间条件 */
		if($order_type == 1)
		{//预约登记时间
			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			$orderby .= ',o.order_addtime DESC ';
		}
		elseif($order_type == 2)
		{//预约时间
			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			$orderby .= ',o.order_time DESC ';
		}
		elseif($order_type == 3)
		{//实到时间
			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			$orderby .= ',o.come_time DESC ';
		}
		elseif($order_type == 4)
		{
                    //医生排班时间
			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;
			$orderby .= ',o.doctor_time DESC ';
        } elseif ($order_type == 5) {
        	//获取最新掉入公海的order_id
        	//与gonghai_order表去比对order_id
        	//存在的order_id都是还在公海里面的，不存在的基本是被捞取了的
        	//由于公海查询开始时间如果超过当天会产生慢查询，所以通过时间判断避开
        	$max_today = strtotime(date('Y-m-d 23:59:59'),time());
            if ($w_start<=$max_today) {
                $sql_dump = " select t.order_id from ".$this->common->table('gonghai_log')." as t where t.action_time=(select max(t1.action_time) from ".$this->common->table('gonghai_log')." as t1 where t.order_id = t1.order_id and t1.action_type = '掉入公海') and t.action_time >= ".$w_start." and t.action_time <= ".$w_end." and t.action_type = '掉入公海' " ;

                $dump_res=$this->common->getAll($sql_dump);
                //echo $sql_dump;die();
                if (!empty($dump_res)) {
                    $dump_order_id = '';
                    foreach ($dump_res as $key => $value) {
                        $dump_order_id .= $value['order_id'].',';
                    }

                    $dump_order_id = substr($dump_order_id, 0, -1);

                    //p($dump_order_id);exit();

                    $where.= " AND o.order_id IN (".$dump_order_id.")";
                } else {
                    $where.= " AND o.order_id IN ('')";
                }
            } else {
                $where.= " AND o.order_id IN ('')";
            }
        }

		//查询回访人员
		$hf_name     = $_REQUEST['a_h'];
		$data['a_h'] = $hf_name;
		if(!empty($hf_name)){
			$order_remark =  $this->common->getAll("select DISTINCT order_id from " . $this->common->table('order_remark') . " where admin_name = '".$hf_name."' and  mark_type = 3   AND mark_time between ".$w_start." AND ".$w_end );
			$order_remark_order_no_str = '';
			foreach ($order_remark as $order_remark_temp){
				if(empty($order_remark_order_no_str)){
					$order_remark_order_no_str = $order_remark_temp['order_id'];
				}else{
					$order_remark_order_no_str .= ','.$order_remark_temp['order_id'];
				}
			}
			if(!empty($order_remark_order_no_str)){
				$where = " o.order_id in(".$order_remark_order_no_str.")";
			}else{
				$w_start = strtotime(date("Y-m-d",time()) . ' 00:00:00');
				$w_end = strtotime(date("Y-m-d",time()) . ' 23:59:59');
				$where = " o.order_id in(0)";
			}
		}else{
			/* 当输入患者的信息时，其他的搜索条件都不需要了 */
			if(!empty($patient_name))
			{

				$where = " p.pat_name = '". $patient_name . "'";
				$data['p_n']      = $patient_name;
				$data['p_p']      = "";
				$data['o_o']      = "";
				if(!empty($_COOKIE["l_hos_id"]))
				{
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}

				if(!empty($_COOKIE["l_keshi_id"]))
				{
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}

			if(!empty($_COOKIE["l_rank_id"])){
				$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];
				$rank_type = $this->common->getRow($sql);
				if($rank_type['rank_type'] == 1){
					$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

				}
			}

			if(!empty($patient_phone))
			{
				$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";
				$data['p_n']      = "";
				$data['p_p']      = $patient_phone;
				$data['o_o']      = "";
				if(!empty($_COOKIE["l_hos_id"]))
				{
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
				if(!empty($_COOKIE["l_keshi_id"]))
				{
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}

			if(!empty($order_no))
			{
				$where = " o.order_no = '" . $order_no . "'";
				$data['p_n']      = "";
				$data['p_p']      = "";
				$data['o_o']      = $order_no;
				if(!empty($_COOKIE["l_hos_id"]))
				{
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}

				if(!empty($_COOKIE["l_keshi_id"]))
				{
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}

		}

		if($orderby == '')
		{
			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';
		}
		else
		{
			$orderby = substr($orderby, 1);//删除orderby中的","
			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
		}


                //获取相关数据
//                $where.=" AND is_come=0";
                 if($gonghai_type=='is_come'){

                    $where .="  AND o.is_come=1 AND o.order_id in(select distinct(order_id) from ".$this->common->table('gonghai_log')." where action_type='掉入公海')";
                $order_count = $this->Order_model->order_count($where);
		$order_count['wei'] = $order_count['count'] - $order_count['come'];//未到人数=预约总人数-实到人数
		$config = page_config();
		if($type == 'mi')
		{//切换到迷你页面
			$config['base_url'] = '?c=gonghai&m=gonghai&type=mi&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu. '&a_h=' . $data['a_h'];
		}
		else
		{//切换到正常页面
			$config['base_url'] = '?c=gonghai&m=gonghai&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu. '&a_h=' . $data['a_h'];
		}
		$config['total_rows'] = $order_count['count'];//总记录数
		$config['per_page'] = $per_page;//每页记录数
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
            if (empty($dump_res) && $order_type == 5) {
                $order_list = array();
            } else {
                $this->pagination->initialize($config);
                $order_list = $this->Order_model->order_list($where, $page, $per_page, $orderby, $order_type);
            }
                }else{


                $order_count = $this->model->order_count($where);
		$order_count['wei'] = $order_count['count'] - $order_count['come'];//未到人数=预约总人数-实到人数
		$config = page_config();
		if($type == 'mi')
		{//切换到迷你页面
			$config['base_url'] = '?c=gonghai&m=gonghai&type=mi&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu. '&a_h=' . $data['a_h'];
		}
		else
		{//切换到正常页面
			$config['base_url'] = '?c=gonghai&m=gonghai&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu. '&a_h=' . $data['a_h'];
		}
		$config['total_rows'] = $order_count['count'];//总记录数
		$config['per_page'] = $per_page;//每页记录数
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;

            if (empty($dump_res) && $order_type == 5) {
                $order_list = array();
            } else {
                $this->pagination->initialize($config);
                $order_list = $this->model->gonghai_list($where, $page, $per_page, $orderby, $order_type);
            }
                }
		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");
		$dao_false_arr = array();
		foreach($dao_false as $val)
		{
			$dao_false_arr[$val['false_id']] = $val['false_name'];
		}

		$rank_type = $this->model->rank_type();
		$area = $this->model->area();
		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
		$flag_1 = array();
		$flag_2 = array();
		foreach($province as $val){
			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){
				$flag_1[] = $val;
			}else{
				$flag_2[] = $val;
			}
		}
		$province_list = array_merge($flag_1,$flag_2);



		//指定回访admin_id
		$defined_hf_admin_ids = array(288,2169,1948,1707,2007,2764,2173,2212,2124);
		//回访超过15天标红
		$ids = '';
		foreach ($order_list as $key => $value) {
			if (end($order_list) === $value) {
				$ids .= $value['order_id'];
			} else {
				$ids .= $value['order_id'].',';
			}
		}
		if (!empty($ids)) {
			$hf_res = $this->common->getAll("select a.order_id,a.mark_time from hui_order_remark as a where a.mark_type = 3 and a.mark_time = (select max(b.mark_time) from hui_order_remark as b where a.order_id = b.order_id)  and a.order_id in ($ids) ");
			$now_hf_time = time();
			$exceed_time_ids = array();
			foreach ($hf_res as $key => $value) {
				if ($value['mark_time'] + 15*24*60*60 < $now_hf_time) {
					$exceed_time_ids[] = $value['order_id'];
				}
			}
			foreach ($order_list as $key => $value) {
				//如果最近回访时间超过15天就标红
				if (in_array($value['order_id'], $exceed_time_ids) && in_array($_COOKIE['l_admin_id'], $defined_hf_admin_ids)) {
					$order_list[$key]['exceed_15_hf'] = 1;
				} else {
					$order_list[$key]['exceed_15_hf'] = 0;
				}

			}
		}





		//$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL
		$data['area'] = $area;
                $data['gonghai_type']=$gonghai_type;
                $data['all_count']=$order_count['count'];
		$data['province'] = $province_list;
		$data['rank_type'] = $rank_type;
		$data['dao_false'] = $dao_false;
		$data['huifang'] = $this->set_huifang();
		$data['dao_false_arr'] = $dao_false_arr;
		$data['page'] = $this->pagination->create_links();
		$data['per_page'] = $per_page;
		$data['order_list'] = $order_list;
		$data['order_count'] = $order_count;
		$data['type_list'] = $type_list;
		$data['from_list'] = $from_list;
		$data['hospital'] = $hospital;
		$data['hos_auth'] = $hos_auth;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data,true);

		/***
		 * 2017 07 01 仁爱妇科 不孕
		*  查询 仁爱预约数量
		*/
		$order_id_str = '';
		foreach ($order_list as $order_list_temp){
			if(empty($order_id_str)){
				$order_id_str = $order_list_temp['order_id'];
			}else{
				$order_id_str .= ",".$order_list_temp['order_id'];
			}
		}
		if(empty($order_id_str)){
			$order_id_str = 0;
		}
		$sql_order_reanai_sum = "SELECT * FROM " . $this->common->table('order_reanai_sum') . "  WHERE  order_id in(".$order_id_str.")";
		$data['info_yd_time'] = $this->common->getAll($sql_order_reanai_sum);


		 //计算推送的数量
		 $data['ireport_order_ount'] =0;
		 if(strcmp($_COOKIE["l_admin_action"],'all') ==0){
			 $where_ts = ' ireport_order_id > 0';
			if(empty($hos_id)){
				if(!empty($_COOKIE["l_hos_id"])){
					$where_ts .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
			}else{
				$where_ts .= ' AND o.hos_id = ' . $hos_id;
			}
			if(empty($keshi_id)){
				if(!empty($_COOKIE["l_keshi_id"])){
					$where_ts .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}else{
				$where_ts .= ' AND o.keshi_id = ' . $keshi_id;
			}
			if($order_type == 1){//预约登记时间
				$where_ts .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			}else if($order_type == 2){//预约时间
				$where_ts .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			}else if($order_type == 3){//实到时间
				$where_ts .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			}else if($order_type == 4){//医生排班时间
				$where_ts .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;
			}

			if(strcmp($where_ts,' ireport_order_id > 0') !=0){
				$sql_ts = "SELECT count(o.ireport_order_id) as count FROM " . $this->common->table('gonghai_order') ."  as o where ".$where_ts;
				$gonghai_ts_order_data = $this->common->getAll($sql_ts);
				//echo $config['total_rows'].'/'.$gonghai_ts_order_data[0]['count'].'/'.$sql_ts;exit;
				if(count($gonghai_ts_order_data) > 0){
					if(empty($gonghai_ts_order_data[0]['count'])){
						$gonghai_ts_order_data[0]['count'] =0 ;
					}
					$data['ireport_order_ount'] =$config['total_rows'] - $gonghai_ts_order_data[0]['count'];
				}
			}
		 }

		//针对男科项目，公海患者回访界面只需要预到时间和备注，其他选项去掉
		$designate_hos_id = array(3,6,37,42);
		$cur_hos_id = $_COOKIE['l_hos_id'];
		$cur_hos_id_array = array_unique(explode(',', $cur_hos_id));

		$data['is_nk_show'] = 0;
		foreach ($cur_hos_id_array as $key => $value) {
			if (in_array($value, $designate_hos_id)) {
				$data['is_nk_show'] = 1;
			}
		}

		$this->load->view('siwei/gonghai.php', $data);
    }

    //这个是个人预约列表，基本上和公海患者的写法大同小异
    public function person_gonghai_list(){
     	$data = array();
		$data           = $this->common->config('person_gonghai_list');
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号
		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话
		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';
		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;
		$bind         = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;
		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;
		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;
		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;
		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;
		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;

		/* 未定患者 */
		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$data['hos_id']   = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$data['p_n']      = $patient_name;
		$data['p_p']      = $patient_phone;
		$data['o_o']      = $order_no;
		$data['f_p_i']    = $from_parent_id;
		$data['f_i']      = $from_id;
		$data['a_i']      = $asker_name;
		$data['s']        = $status;
		$data['wx']        = $bind;
		$data['o_t']      = $type_id;
		$data['t']        = $order_type;
		$data['p_jb']     = $p_jb;
		$data['jb']       = $jb;
		//判断日期是否为空
		$date_str = $date;
		if(!empty($date))//对查询的两个时间进行处理
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
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
		}
		else
		{
			if(isset($_COOKIE['start_time']))
			{
				$start = date("Y-m-d", $_COOKIE['start_time']);
				$end = date("Y-m-d", $_COOKIE['end_time']);
				$data['start_date'] = $start;
				$data['end_date'] = $end;
				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
			}
			else
			{
				$start = date("Y-m-d", time());
				$end = date("Y-m-d", time());
				$data['start_date'] = $start;
				$data['end_date'] = $end;
				$data['start'] = date("Y年m月d日", time());
				$data['end'] = date("Y年m月d日", time());
			}
		}
                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，
                //而$[start_date]表示xx-xx-xx这种格式的日期



                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表
		$hospital = $this->model->hospital_order_list();
		$keshi = $this->model->keshi_order_list();
		$keshi_arr = array();
                //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
		foreach($keshi as $val)
		{
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
		foreach($from_list as $val)
		{
			if($val['parent_id'] == 0)
			{
				$par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
			else
			{
				$from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
		}
		$from_list = $par_from_arr;
                //获取预约类别表Order_type中的所有数据
		$type_list = $this->model->type_order_list();

		$type_arr = array();
		foreach($type_list as $val)
		{
			$type_arr[$val['type_id']]['type_id'] = $val['type_id'];
			$type_arr[$val['type_id']]['type_name'] = $val['type_name'];
			$type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
			$type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
			$type_arr[$val['type_id']]['type_order'] = $val['type_order'];
		}
		$type_list = $type_arr;

		$hos_id_arr = array();
		$keshi_id_arr = array();
		$jibing = read_static_cache('jibing_list');
		$jibing_list = array();
		$jibing_parent = array();
		if(!empty($jibing))
		{
			foreach($jibing as $val)
			{
				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
				if($val['jb_level'] == 2)
				{
					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
				}
			}
		}

		$data['from_arr'] = $from_arr;
		$data['keshi'] = $keshi_arr;
		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;
		$hos_auth = array();
		foreach($hospital as $val)
		{
			$hos_id_arr[] = $val['hos_id'];
			if($val['ask_auth']){
				$hos_auth[] = $val['hos_id'];
			}
		}

		foreach($keshi as $val)
		{
			$keshi_id_arr[] = $val['keshi_id'];
		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$data['now_page'] = $page;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;
		$per_page = empty($per_page)? 30:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');//调用CI自带的page分页类

		/* 处理判断条件 */
		$where = 1;
		$orderby = '';
		if(empty($hos_id))
		{
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
		}
		else
		{
			$where .= ' AND o.hos_id = ' . $hos_id;
		}

		if(empty($keshi_id))
		{
			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}
		else
		{
			$where .= ' AND o.keshi_id = ' . $keshi_id;
		}


		if(!empty($from_parent_id))
		{
			$where .= ' AND o.from_parent_id = ' . $from_parent_id;
		}

		if(!empty($from_id))
		{
			$where .= ' AND o.from_id = ' . $from_id;
		}

		if(!empty($p_jb))
		{
			$where .= ' AND o.jb_parent_id = ' . $p_jb;
		}


		if(!empty($jb))
		{
			$where .= ' AND o.jb_id = ' . $jb;
		}

		if(!empty($asker_name))
		{
			$where .= " AND o.admin_name = '" . $asker_name . "'";
		}

		/* 订单状态 */
		if($status == 1)
		{
			$where .= ' AND o.is_come = 0';
		}
		elseif($status == 2)
		{
			$where .= ' AND o.is_come = 1';
		}
		elseif($status == 3)
		{
			$where .= ' AND o.doctor_time > 0';
		}

		if($bind == 1){

			$where .= ' AND p.pat_weixin is null';
		}elseif($bind == 2){

			$where .= ' AND  p.pat_weixin <> ""';
		}

		if(!empty($pro)&&is_numeric($pro)){
			$where .= ' AND  p.pat_province = '.$pro;
			$data['pro'] = $pro;
		}else{
			$data['pro'] = 0;
		}
		if(!empty($city)&&is_numeric($city)){
			$where .= ' AND  p.pat_city = '.$city;
			$data['city'] = $city;
		}
		if(!empty($are)&&is_numeric($are)){
			$where .= ' AND  p.pat_area = '.$are;
			$data['are'] = $are;
		}

		if(!empty($type_id))
		{
			$where .= " AND o.type_id = $type_id";
		}

		if($order_time_type == 1)
		{
			$where .= ' AND o.order_time = 0';
		}
		if($wu == 1)
		{
			$w_start = strtotime($start . ' 00:00:00') - 43200;
			$w_end = strtotime($end . ' 11:59:59');
		}
		else
		{
			$w_start = strtotime($start . ' 00:00:00');
			$w_end = strtotime($end . ' 23:59:59');
		}
		/* 时间条件 */
		if($order_type == 1)
		{//预约登记时间
			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			$orderby .= ',o.order_addtime DESC ';
		}
		elseif($order_type == 2)
		{//预约时间
			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';
		}
		elseif($order_type == 3)
		{//实到时间
			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			$orderby .= ',o.come_time DESC ';
		}
		elseif($order_type == 4)
		{
                    //医生排班时间
			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;
			$orderby .= ',o.doctor_time DESC ';
		}

		/* 当输入患者的信息时，其他的搜索条件都不需要了 */
		if(!empty($patient_name))
		{

			$where = " p.pat_name = '". $patient_name . "'";
			$data['p_n']      = $patient_name;
			$data['p_p']      = "";
			$data['o_o']      = "";
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}

			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}

		if(!empty($_COOKIE["l_rank_id"])){
			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];
			$rank_type = $this->common->getRow($sql);
			if($rank_type['rank_type'] == 1){
				$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

			}
		}

		if(!empty($patient_phone))
		{
			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";
			$data['p_n']      = "";
			$data['p_p']      = $patient_phone;
			$data['o_o']      = "";
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}

		if(!empty($order_no))
		{
			$where = " o.order_no = '" . $order_no . "'";
			$data['p_n']      = "";
			$data['p_p']      = "";
			$data['o_o']      = $order_no;
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}

			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}

		if($orderby == '')
		{
			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';
		}
		else
		{
			$orderby = substr($orderby, 1);//删除orderby中的","
			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
		}
//                $where1=" o.gonghai_type='".$_COOKIE['l_admin_name']."'";
//                //$where1=" o.gonghai_type='gonghai' ";
//                $orderby1=" order by o.order_time desc ";
                 $orderby1=" order by o.order_time asc ";
//                $where2=" o.admin_name='".$_COOKIE['l_admin_name']."'";
                  $where.=" AND o.admin_name='".$_COOKIE['l_admin_name']."'";
//                 $where1=" o.admin_name='daodao' ";
		$order_count = $this->model->order_count_new($where);
		//var_dump($order_count);
		$order_count['wei'] = $order_count['count'] - $order_count['come'];//未到人数=预约总人数-实到人数
		$config = page_config();
		if($type == 'mi')
		{//切换到迷你页面
			$config['base_url'] = '?c=gonghai&m=person_gonghai_list&type=mi&date='.$date_str.'&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&p=' . $per_page . '&t=' . $order_type . '&o_t=' . $type_id . '&wu=' . $wu;

                    //$config['base_url'] = '?c=gonghai&m=person_gonghai_list&type=mi';
                }
		else
		{//切换到正常页面
			$config['base_url'] = '?c=gonghai&m=person_gonghai_list&date='.$date_str.'&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&p=' . $per_page . '&t=' . $order_type . '&o_t=' . $type_id . '&wu=' . $wu;

                    //$config['base_url'] = '?c=gonghai&m=person_gonghai_list';
                }
		$config['total_rows'] = $order_count['count'];//总记录数
		$config['per_page'] = $per_page;//每页记录数
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;

		$this->pagination->initialize($config);
                //获取相关数据
//		$gonghai_list = $this->model->person_gonghai_list($where1, $page, $per_page, $orderby1);
                $order_list=$this->model->person_gonghai_list($where,$page,$per_page,$orderby1);
		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");
		$dao_false_arr = array();
		foreach($dao_false as $val)
		{
			$dao_false_arr[$val['false_id']] = $val['false_name'];
		}

		$rank_type = $this->model->rank_type();
		$area = $this->model->area();
		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
		$flag_1 = array();
		$flag_2 = array();
		foreach($province as $val){
			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){
				$flag_1[] = $val;
			}else{
				$flag_2[] = $val;
			}
		}
		$province_list = array_merge($flag_1,$flag_2);

		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL
		$data['area'] = $area;
		$data['province'] = $province_list;
		$data['rank_type'] = $rank_type;
		$data['dao_false'] = $dao_false;
		$data['huifang'] = $this->set_huifang();
		$data['dao_false_arr'] = $dao_false_arr;
		$data['page'] = $this->pagination->create_links();
		$data['per_page'] = $per_page;
		$data['order_list'] = $order_list;

		/***
		 * 2017 07 01 仁爱妇科 不孕
		*  查询 仁爱预约数量
		*/
		$order_id_str = '';
		foreach ($order_list as $key=>$order_list_temp){
			$order_list[$key]['gonghai_time'] =0;
			if(empty($order_id_str)){
				$order_id_str = $order_list_temp['order_id'];
			}else{
				$order_id_str .= ",".$order_list_temp['order_id'];
			}
		}
		if(empty($order_id_str)){
			$order_id_str = 0;
		}
		$sql_order_reanai_sum = "SELECT * FROM " . $this->common->table('order_reanai_sum') . "  WHERE  order_id in(".$order_id_str.")";
		$data['info_yd_time'] = $this->common->getAll($sql_order_reanai_sum);


		if(!empty($order_id_str)){
			//查询最后一个公海捞出时间
			$action_time = $this->common->getAll("SELECT max(action_time) as action_time,order_id FROM " . $this->common->table("gonghai_log") . " WHERE order_id in(".$order_id_str.") and action_type = '从公海捞取' group by order_id ");
			if(count($action_time) > 0){
				foreach($action_time as $action_time_temp){
					foreach($order_list as $key=>$val){
						if($val['order_id'] ==  $action_time_temp['order_id'] && !empty($action_time_temp['action_time'])){
							$order_list[$key]['gonghai_time'] = $action_time_temp['action_time'];
							break;
						}
					}
				}
			}
		}

//                $data['gonghai_list']=$gonghai_list;
		$data['order_count'] = $order_count;
		$data['type_list'] = $type_list;
		$data['from_list'] = $from_list;
		$data['hospital'] = $hospital;
		$data['hos_auth'] = $hos_auth;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data,true);
		$this->load->view('siwei/person_gonghai_list.php', $data);



               }

    //设置回访的相关选择数组
    private function set_huifang()
	{
		$data = array();
		$data['zt'] = array(
						1	=>	'确定预约时间',
						2	=>	'了解未到诊原因',
						3	=>	'了解未消费原因',
						4	=>	'去电详细咨询',
						);
		$data['lx'] = array(
						1	=>	'未预约回访',
						2	=>	'未到诊回访',
						3	=>	'未消费回访',
						4	=>	'到诊前回访',
						5	=>	'到诊后回访',
						6	=>	'手术前回访',
						7	=>	'手术后回访',
						8	=>	'手术后复查',
						9	=>	'活动回访',
						10	=>	'投拆回访',
						11	=>	'其他',
						);
		$data['jg'] = array(
						1	=>	'接通',
						2	=>	'关机',
						3	=>	'无人接听',
						4	=>	'拒接',
						5	=>	'停机/无法接通',
						6	=>	'空号/错号',
						);
		$data['ls'] = array(
						1	=>	'无',
						2	=>	'竞争对手',
						3	=>	'公立医院',
						4	=>	'自然流失',
						);
		return $data;
	}

	//从公海患者中把相关的人员捞取到个人预约列表，
	public function update_name(){
		$order_id=isset($_GET['order_id'])?intval($_GET['order_id']):0;
		if(empty($_COOKIE['l_admin_name'])){
			$_COOKIE['l_admin_name'] = '';
		}
		if(empty($_COOKIE['l_admin_name']) || empty($_COOKIE['l_admin_id'])){
			$this->common->msg('COOKIE失效,请重新登录账户',1);
		}else{
			$order_id_data = $this->common->getOne("SELECT order_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
			if(empty($order_id_data)){
				$this->common->msg('数据已经不在公海。返回原页面！',1);
			}else{
				if(strcmp($_COOKIE["l_admin_action"],'all') !=0){
					$l_admin_action = explode(",",$_COOKIE["l_admin_action"]);
					if (!in_array(184, $l_admin_action)){
						$this->common->msg('你没有捞取公海数据的权限。返回原页面！',1);
					}
				}
				$result=$this->model->update_order_name($order_id,$_COOKIE['l_admin_name'],$_COOKIE['l_admin_id']);
				if($result==true){
					//$this->send_order_shujuzhognxin($order_id);
					echo "<script>window.location.href = '?c=gonghai&m=person_gonghai_list';</script>";
				}else{

					$this->common->msg('插入数据失败！返回原页面！',1);
				}
			}
		}
	}



	public function testsea()
	{

		$time_dat = date("Y-m-d",time()-15*24*60*60);
		$start_time=strtotime($time_dat." 00:00:00");

		//所有
		$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(54) AND o.keshi_id in(151) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.is_come=0 ";

		$all_orders=$this->common->getAll($sql);

		if (empty($all_orders)) {
			return true;
		}

		$all_order_ids = array();
		foreach ($all_orders as $order) {
			$all_order_ids[] = $order['order_id'];
		}

		$order_id_str = implode(",", $all_order_ids);

		//回访未超过15天
		$sql = " select t.order_id,t.mark_time from ".$this->common->table('order_remark')." as t where order_id in(".$order_id_str.") and mark_time=(select max(t1.mark_time) from ".$this->common->table('order_remark')." as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time >= ".$start_time." and t.mark_type = 3 order by t.mark_time desc" ;

	    $under_15_hf_orders=$this->common->getAll($sql);

	    $under_15_hf_order_ids = array();
	    foreach ($under_15_hf_orders as $order) {
	    	$under_15_hf_order_ids[] = $order['order_id'];
	    }

    	//回访超过15天
    	$sql = " select t.order_id,t.mark_time from ".$this->common->table('order_remark')." as t where order_id in(".$order_id_str.") and mark_time=(select max(t1.mark_time) from ".$this->common->table('order_remark')." as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time < ".$start_time." and t.mark_type = 3 order by t.mark_time desc" ;

        $exceed_15_hf_orders=$this->common->getAll($sql);

        $exceed_15_hf_order_ids = array();
        foreach ($exceed_15_hf_orders as $order) {
        	$exceed_15_hf_order_ids[] = $order['order_id'];
        }


        //所有回访为空的
	    $null_hf_orders = array_diff($all_order_ids, $under_15_hf_order_ids, $exceed_15_hf_order_ids);

	    $null_hf_order_ids = array();
		foreach ($null_hf_orders as $id) {
			$null_hf_order_ids[] = $id;
		}

		if (empty($null_hf_order_ids)) {
			$order_id_str = 0;
		}

		$order_id_str = implode(",", $null_hf_order_ids);

		//超过登记时间15天回访为空的
	    $sql = "select order_id from ".$this->common->table('order')." where order_id in (".$order_id_str.") and order_addtime < ".$start_time ;

	    $exceed_addtime15_null_hf_orders=$this->common->getAll($sql);

        $exceed_addtime15_null_hf_orders_ids = array();
        foreach ($exceed_addtime15_null_hf_orders as $order) {
        	$exceed_addtime15_null_hf_orders_ids[] = $order['order_id'];
        }

        //超过登记时间15天回访为空的，回访超过15天掉公海
       	$final_order_ids = array_merge($exceed_15_hf_order_ids,$exceed_addtime15_null_hf_orders_ids);

       	p($final_order_ids);die();

	}


	/***
	 *
	*  定时执行掉入公海
	*/
	public function auto_gonghai(){


  //   	$time = date("Y-m-d",time());
  //   	if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

		// 	$bol = $this->model->update_gonghai_addtime_timeout_other_wenzhou();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州男科 外线科 1点到2点 掉登记时间超过40天的所有数据 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州男科 外线科 1点到2点 掉登记时间超过40天的所有数据 同步失败')");
		// 	}
		// 	$bol = $this->model->update_dongfang_order_timeout_wenzhou();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 登记时间40天内 预到而未到的 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 登记时间40天内 预到而未到的 翌日流入公海 同步失败')");
		// 	}
		// 	$bol = $this->model->update_dongfang_order_timeout_wenzhou_hf();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 翌日流入公海 同步失败')");
		// 	}
		// 	$bol = $this->model->update_dongfang_order_timeout_wenzhou_gh();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 捞出时间40天内 预到而未到的 翌日流入公海 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 捞出时间40天内 预到而未到的 翌日流入公海 翌日流入公海 同步失败')");
		// 	}
		// 	$bol = $this->model->update_dongfang_order_timeout_wenzhou_gh_hf();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','温州 男科  外线科 1点到2点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 翌日流入公海 同步失败')");
		// 	}
		// }

		//***********************************************************************************************************************

    	$time = date("Y-m-d",time());
    	if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){

			$bol = $this->model->update_gonghai_addtime_timeout_other();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点  掉登记时间超过40天的所有数据 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点  掉登记时间超过40天的所有数据 同步失败')");
			}
			$bol = $this->model->update_tz_df_order_timeout();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 登记时间40天内 预到而未到的 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 登记时间40天内 预到而未到的 翌日流入公海 同步失败')");
			}


			$bol = $this->model->update_tz_df_order_timeout_hf();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 同步失败')");
			}

			$bol = $this->model->update_tz_df_order_timeout_gh();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 捞出时间40天内 预到而未到的 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 捞出时间40天内 预到而未到的 翌日流入公海 同步失败')");
			}

			$bol = $this->model->update_tz_df_order_timeout_gh_hf();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','台州 2点到3点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步失败')");
			}
		}


		//***********************************************************************************************************************



       $time = date("Y-m-d", time());
       if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

            $bol = $this->model->drop_order_addtime_exceed_30();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点  登记时间超过30天掉公海(排除已捞出预约) 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点  登记时间超过30天掉公海(排除已捞出预约) 同步失败(没有符合掉公海条件的)')");
            }

            $bol = $this->model->drop_order_addtime_under_30_notcome();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内遇到未到掉公海 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内遇到未到掉公海 同步失败(没有符合掉公海条件的)')");
            }

            $bol = $this->model->drop_order_addtime_under_30_exceed7_notcalled();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内超过7天未回访掉公海 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内超过7天未回访掉公海 同步失败(没有符合掉公海条件的)')");
            }

            $bol = $this->model->drop_order_addtime_under_30_notcalled();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内登记时间超过7天且未回访掉公海 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 登记时间30天内登记时间超过7天且未回访掉公海 同步失败(没有符合掉公海条件的)')");
            }

            $bol = $this->model->drop_fish_time_exceed_10_notcome();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 超过捞取时间10天未到诊掉公海 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 超过捞取时间10天未到诊掉公海 同步失败(没有符合掉公海条件的)')");
            }

            $bol = $this->model->drop_order_time_modify3_notcome();
            if ($bol) {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 预到时间修改超过3次未到诊掉公海 同步成功')");
            } else {
                $this->db->query("insert into " . $this->common->table('auto_longhai_log') . "(time,msg) values('" . date("Y-m-d H:i:s", time()) . "','东方 1点 预到时间修改超过3次未到诊掉公海 同步失败(没有符合掉公海条件的)')");
            }

        }

        //***********************************************************************************************************************



  //   	$time = date("Y-m-d",time());
  //   	if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

		// 	$bol = $this->model->update_gonghai_addtime_timeout_other_chengdu();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都男科 外线科  3点到4点 掉登记时间超过40天的所有数据 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都男科 外线科  3点到4点 掉登记时间超过40天的所有数据 同步失败')");
		// 	}
		// 	$bol = $this->model->update_dongfang_order_timeout_chengdu();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 登记时间40天内 预到而未到的 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 登记时间40天内 预到而未到的 翌日流入公海 同步失败')");
		// 	}

		// 	$bol = $this->model->update_dongfang_order_timeout_chengdu_hf();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海 同步失败')");
		// 	}

		// 	$bol = $this->model->update_dongfang_order_timeout_chengdu_gh();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 捞出时间40天内 预到而未到的 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 捞出时间40天内 预到而未到的 翌日流入公海 同步失败')");
		// 	}

		// 	$bol = $this->model->update_dongfang_order_timeout_chengdu_gh_hf();
		// 	if($bol){
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步成功')");
		// 	}else{
		// 		$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','成都 男科 外线科  3点到4点 捞出时间40天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步失败')");
		// 	}
		// }

		//***********************************************************************************************************************

    	/*$time = date("Y-m-d",time());
    	if(time() >= strtotime($time." 04:00:00") && time() < strtotime($time." 05:00:00")){

			$bol = $this->model->update_gonghai_addtime_timeout_other_ningbo();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 掉登记时间超过60天的所有数据 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 掉登记时间超过60天的所有数据 同步失败')");
			}
			$bol = $this->model->update_dongfang_order_timeout_ningbo();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 登记时间60天内 预到而未到的 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 登记时间60天内 预到而未到的 翌日流入公海 同步失败')");
			}

			$bol = $this->model->update_dongfang_order_timeout_ningbo_hf();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 登记时间60天内 没到预到时间但回访时间超过10天 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 登记时间60天内 没到预到时间但回访时间超过10天 翌日流入公海 同步失败')");
			}

			$bol = $this->model->update_dongfang_order_timeout_ningbo_gh();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 捞出时间60天内 预到而未到的 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 捞出时间60天内 预到而未到的 翌日流入公海 同步失败')");
			}

			$bol = $this->model->update_dongfang_order_timeout_ningbo_gh_hf();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 捞出时间60天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 捞出时间60天内 没到预到时间但最新回访时间超过10天 翌日流入公海 同步失败')");
			}
		}*/

		$time = date("Y-m-d",time());
		if(time() >= strtotime($time." 04:00:00") && time() < strtotime($time." 05:00:00")){
			$bol = $this->model->ningbo_drop_sea();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 回访时间超过15天且未回访按登记时间超过15天 翌日流入公海 同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','宁波男科 4点到5点 回访时间超过15天且未回访按登记时间超过15天 翌日流入公海 同步失败')");
			}
		}

		//***********************************************************************************************************************

    	$time = date("Y-m-d",time());
    	if(time() >= strtotime($time." 05:00:00") && time() < strtotime($time." 06:00:00")){

			$bol = $this->model->update_rafk_order_timeout();
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','仁爱妇科+不孕同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','仁爱妇科+不孕同步失败')");
			}
			$bol=$this->model->update_rafk_order_timeout_other();//2017 07 01 当天预到而未到，翌日流入公海    -- 仁爱  妇科+不孕
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','仁爱妇科+不孕同步成功')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','仁爱妇科+不孕同步失败')");
			}
			$bol=$this->model->update_rafk_order_timeout_other();//30号强制掉 仁爱 妇科+不孕
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','30号强制掉 仁爱 妇科+不孕')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','30号强制掉 仁爱 妇科+不孕')");
			}
			$bol=$this->model->update_rafk_addtime_timeout_other();//掉30号之前的所有数据    -- 仁爱  妇科+不孕
			if($bol){
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','掉30号之前的所有数据 仁爱 妇科+不孕')");
			}else{
				$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','掉30号之前的所有数据 仁爱 妇科+不孕')");
			}
		}

		// $bol=$this->model->update_to_gonghai_all();//掉30号之前的所有数据    -- 仁爱   温州  台州 东方 成都
		// if($bol){
		// 	$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','掉30号之前的所有数据    -- 仁爱   温州  台州 东方 成都')");
		// }else{
		// 	$this->db->query("insert into ".$this->common->table('auto_longhai_log')."(time,msg) values('".date("Y-m-d H:i:s",time())."','掉30号之前的所有数据    -- 仁爱   温州  台州 东方 成都')");
		// }

		//获取当时掉入公海的预约单数据
		// $start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");//对时间的格式化操作
		// $apisql = 'select order_id from '.$this->common->table('gonghai_log')." where action_time >= ".$start_time." and action_type like '%掉入公海%'";
		// $gonghai_log_data = $this->common->getAll($apisql);
		// if(count($gonghai_log_data) > 0){
		// 	$send_array = array();
		// 	$send_data = array();
		// 	$order_id_str = '';
		// 	foreach ($gonghai_log_data as $gonghai_log_data_s){
		// 		if(empty($order_id_str)){
		// 			$order_id_str =  $gonghai_log_data_s['order_id'];
		// 		}else{
		// 			$order_id_str =  $order_id_str.','.$gonghai_log_data_s['order_id'];
		// 		}
		// 	}
		// 	if(!empty($order_id_str)){
		// 		$apisql = 'select ireport_order_id,order_id,is_first from '.$this->common->table('gonghai_order')." where order_id in (".$order_id_str.")";
		// 		$ireport_order_data = $this->common->getAll($apisql);
		// 		$send_array = array();
		// 		foreach ($ireport_order_data as $ireport_order_data_ts){
		// 			$send_data = array();
		// 			$send_data['operation_type'] = 'editType';
		// 			$send_data['order_id'] = $ireport_order_data_ts['order_id'];
		// 			$send_data['ireport_order_id'] = $ireport_order_data_ts['ireport_order_id'];
		// 			$send_data['yuyue_type'] = $ireport_order_data_ts['is_first'];
		// 			$send_data['gonghai_status'] =  1;
		// 			$send_array[] =$send_data;
		// 		}
		// 		$this->sycn_order_data_to_ireport($send_array);
		// 	}
		// }

		exit('It works!');
	}


	/***
	 *
	*  定时执行掉入公海
	*/
	// public function auto_gonghai_test(){
	// 	//获取当时掉入公海的预约单数据
	// 	$start_time = strtotime(date("Y-m-d", time()) . " 00:00:00");//对时间的格式化操作
	// 	$apisql = 'select order_id from '.$this->common->table('gonghai_log')." where action_time >= ".$start_time." and action_type like '%掉入公海%'";
	// 	$gonghai_log_data = $this->common->getAll($apisql);
	// 	if(count($gonghai_log_data) > 0){
	// 		$send_array = array();
	// 		$send_data = array();
	// 		$order_id_str = '';
	// 		foreach ($gonghai_log_data as $gonghai_log_data_s){
	// 			if(empty($order_id_str)){
	// 				$order_id_str =  $gonghai_log_data_s['order_id'];
	// 			}else{
	// 				$order_id_str =  $order_id_str.','.$gonghai_log_data_s['order_id'];
	// 			}
	// 		}
	// 		if(!empty($order_id_str)){
	// 			$apisql = 'select ireport_order_id,order_id,is_first,order_no from '.$this->common->table('gonghai_order')." where order_id in (".$order_id_str.")";
	// 			$ireport_order_data = $this->common->getAll($apisql);
	// 			$send_array = array();
	// 			foreach ($ireport_order_data as $ireport_order_data_ts){
	// 				$send_data = array();
	// 				$send_data['operation_type'] = 'editType';
	// 				$send_data['order_id'] = $ireport_order_data_ts['order_id'];
	// 				$send_data['order_no'] = $ireport_order_data_ts['order_no'];
	// 				$send_data['ireport_order_id'] = $ireport_order_data_ts['ireport_order_id'];
	// 				$send_data['yuyue_type'] = $ireport_order_data_ts['is_first'];
	// 				$send_data['gonghai_status'] =  1;
	// 				$send_array[] =$send_data;
	// 			}
	// 			var_dump(json_encode($send_array));exit;
	// 			//$this->sycn_order_data_to_ireport($send_array);
	// 		}
	// 	}

	// 	exit;
	// }




	/***
	 *
	*  定时执行 判断公海已到诊数据  恢复至预约表 1个小时一次
	*/
	public function auto_gonghai_other(){

		//公海到诊的自动捞回到预约表中
		$this->model->come_from_gonghai();
		exit;
	}


	/***
	 *
	* 刷新整个过期的预约数据，使其掉入公海
	*/
	public function update_gonghai_all(){
		$msg = '';
		//echo date("Y-m-d",time()-30*24*60*60);exit;
		//echo '系统定时执行同步程序，同步时间在凌晨执行。不需要手动操作。此方法作废。';exit;
		/**
		 $end_time=strtotime(date("Y-m-d",time())." 00:00:00");
		 $start_time=strtotime(date("Y-m-d",time()-24*60*60)." 00:00:00");
		 echo date("Y-m-d H:i",1504281599);
		 echo date("Y-m-d H:i",$start_time);
		 exit;
		 **/

		//$this->model->update_to_gonghai_all();
		exit;
		//大途径修改为：其它；小途径修改为：电话呼出  and gl.`action_time`  BETWEEN  1504281599 and 1506873599
		$sql = "SELECT gl.order_id FROM `db39mgb5x7t132rc`.`hui_gonghai_log` as gl  where gl.action_id = 288  and  action_type like '%电话呼出%'   order by gl.action_time desc";
		$from_list = $this->common->getAll($sql);
		$order_no_exit = '';
		foreach ($from_list as $from_list_temp){
			$sql = 'select order_no,admin_id from '. $this->common->table('order') .' where order_id in('.$from_list_temp['order_id'].') ';
			$order_list = $this->common->getAll($sql);
			if(count($order_list) == 0){
				$sql = 'select order_no,admin_id from '. $this->common->table('gonghai_order') .' where order_id in('.$from_list_temp['order_id'].') ';
				$order_list = $this->common->getAll($sql);
				if(count($order_list) == 0){
					if(empty($order_no_exit)){
						$order_no_exit  =$from_list_temp['order_id'];
					}else{
						$order_no_exit  =$order_no_exit.'|'.$from_list_temp['order_id'];
					}

				}
			}

			$sql = 'select order_no,admin_id from '. $this->common->table('gonghai_order') .' where order_id in('.$from_list_temp['order_id'].') ';
			$order_list = $this->common->getAll($sql);
			if(count($order_list) == 0){
				$sql = 'select order_no,admin_id from '. $this->common->table('order') .' where order_id in('.$from_list_temp['order_id'].') ';
				$order_list = $this->common->getAll($sql);
				if(count($order_list) == 0){
					if(empty($order_no_exit)){
						$order_no_exit  =$from_list_temp['order_id'];
					}else{
						$order_no_exit  =$order_no_exit.'|'.$from_list_temp['order_id'];
					}

				}
			}

		}
		var_dump($order_no_exit);
		exit;


		/**
		//大途径修改为：其它；小途径修改为：电话呼出  and gl.`action_time`  BETWEEN  1504281599 and 1506873599

		$sql = "SELECT gl.order_id FROM `db39mgb5x7t132rc`.`hui_gonghai_log` as gl  where gl.action_id = 288  and  action_type like '%途径：其他-电话呼出%'   order by gl.action_time desc";
		$from_list = $this->common->getAll($sql);
		$order_no_exit = '';
		foreach ($from_list as $from_list_temp){
			$sql = 'select order_no,admin_id from '. $this->common->table('order') .' where order_id in('.$from_list_temp['order_id'].') ';
			$order_list = $this->common->getAll($sql);
			if(count($order_list) == 0){
				$sql = 'select order_no,admin_id from '. $this->common->table('gonghai_order') .' where order_id in('.$from_list_temp['order_id'].') ';
				$order_list = $this->common->getAll($sql);
				if(count($order_list) > 0){

					if($order_list[0]['admin_id'] != 288){
						if(empty($order_no_exit)){
							$order_no_exit  =$order_list[0]['order_no'];
						}else{
							$order_no_exit  =$order_no_exit.'|'.$order_list[0]['order_no'];
						}
					}
				}
			}else{

				if($order_list[0]['admin_id'] != 288){
					if(empty($order_no_exit)){
						$order_no_exit  =$order_list[0]['order_no'];
					}else{
						$order_no_exit  =$order_no_exit.'|'.$order_list[0]['order_no'];
					}
				}
			}
		}
		var_dump($order_no_exit);
		exit;
		**/


		/**
		$time_str= date("Y-m",time());
		$start_time=strtotime($time_str."-01 23:59:59");
		$end_time=strtotime($time_str."-31 23:59:59");
		echo  $start_time.'/'.$end_time;
		**/
		exit;


		$this->model->update_gonghai_addtime_timeout_other();
		$this->model->update_gonghai_addtime_timeout();
		$this->model->update_dongfang_order_timeout();
		$this->model->update_order_timeout();
		exit;



		if(!$this->model->update_gonghai_addtime_timeout()){$msg   = '台州东方同步失败'; }

		if(!$this->model->update_order_timeout()){
			if(empty($msg)){$msg   = '台州同步失败';}else{$msg  .= ';台州同步失败';}
		}

		//2016 10 21 ,把已经超过预约时间的订单数据 从预约流入到公海。这里是第一次把预约数据插入到公/海数据里面    --东方
		if(!$this->model->update_dongfang_order_timeout()){
			if(empty($msg)){$msg   = '东方同步失败';}else{$msg  .= ';东方同步失败';}
		}

		/***
		 * 2017 07 01 仁爱妇科 不孕流入公海
		*/
		$result10=$this->model->update_rafk_order_timeout();//2017 07 01 当天预到而未到，翌日流入公海    -- 仁爱  妇科+不孕
		$result11=$this->model->update_rafk_order_timeout_other();//2017 07 01 超过最长 7天以后的流入公海     -- 仁爱  妇科+不孕
		if($result10 || $result11){
			if(empty($msg)){$msg   = '仁爱妇科+不孕同步成功';}else{$msg  .= ';仁爱妇科+不孕同步成功';}
		}else{
			if(empty($msg)){$msg   = '仁爱妇科+不孕同步失败';}else{$msg  .= ';仁爱妇科+不孕同步失败';}
		}

		//公海到诊的自动捞回到预约表中
		$this->model->come_from_gonghai();

		if(empty($msg)){ $this->common->msg("同步成功",0);}else{$this->common->msg($msg,0);}
	}

	//显示整个订单的详情，包括订单的日志信息
	public function order_detail(){
		$order_id=isset($_REQUEST['order_id'])?intval($_REQUEST['order_id']):0;
		$where=" o.order_id=".$order_id;
		$orderby=" order by o.order_id";
		$order_detail=$this->model->order_list($where,0,1,$orderby);
		$logData = $this->model->gonghai_log_list($order_id);


		//判断当天当前用户是否已经点击显示号码除了属于自己的
		$admin_id = intval($_COOKIE['l_admin_id']);
		$time = time();
		$s_t = strtotime(date('Y-m-d 00:00:00',$time));
		$e_t = strtotime(date('Y-m-d 23:59:59',$time));
		$where = array('admin_id'=>$admin_id,'order_id'=>$order_id,'add_time >='=>$s_t,'add_time <='=>$e_t);
		$this->db->set_dbprefix("henry_");
		$records = $this->db->select('order_id,pat_id')->get_where('show_phone_record', $where)->result_array();
		foreach ($order_detail as $key => $value) {
			$admin_id = $value['admin_id'];
		}
		$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		if (!$records && ($admin_id != $_COOKIE['l_admin_id']) && ($_COOKIE['l_admin_action'] != 'all') && !in_array(198, $l_admin_action)) {
			foreach ($order_detail as $key => $value) {
				$order_detail[$key]['pat_phone'] = substr($value['pat_phone'],0, -4)."****";
				$order_detail[$key]['pat_phone1'] = substr($value['pat_phone1'],0, -4)."****";
			}

			foreach ($logData as $key => $value) {
				$logData[$key]['action_type'] = is_log_phone($value['action_type']);
			}
		}


		//掉公海规则
		$dropsealog = $this->db->select('order_id,type,gh_id')->order_by('drop_time', 'DESC')->get_where('drop_sea_log', array('order_id' => $order_id))->result_array();
		$dropsealogdata = array();
		foreach ($dropsealog as $item) {
		    $dropsealogdata[$item['gh_id']] = $item['type'];
		}
		$data['dropsealog'] = $dropsealogdata;
		$data['cnfRules'] = $this->config->item('dropSeaRule');


		$this->db->set_dbprefix("hui_");

		//获取相关科室和疾病的信息
		$hospital = $this->model->hospital_order_list();
		$keshi = $this->model->keshi_order_list();
		$keshi_arr = array();
		//生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
		foreach($keshi as $val)
		{
			$keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
			$keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
			$keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
			$keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
			$keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
			$keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
		}

		if(empty($order_detail[$order_id]['from_parent_id'])){
			$sql = 'select from_id,from_name,parent_id from '. $this->common->table('order_from');
		}else{
			if(empty($order_detail[$order_id]['from_id'])){
				$sql = 'select from_id,from_name,parent_id from '. $this->common->table('order_from') .' where from_id in('.$order_detail[$order_id]['from_parent_id'].') ';
			}else{
				$sql = 'select from_id,from_name,parent_id from '. $this->common->table('order_from') .' where from_id in('.$order_detail[$order_id]['from_parent_id'].','.$order_detail[$order_id]['from_id'].') ';
			}
		}
		$from_list = $this->common->getAll($sql);

		$par_from_arr = array();
		$from_arr = array();//生成二维数组，把每条记录作为数组存进$from_arr
		foreach($from_list as $val)
		{
			if($val['parent_id'] == 0)
			{
				$par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
			else
			{
				$from_arr[$val['from_id']]['from_id'] = $val['from_id'];
				$from_arr[$val['from_id']]['from_name'] = $val['from_name'];
				$from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
			}
		}
		$from_list = $par_from_arr;
		//获取预约类别表Order_type中的所有数据
		$type_list = $this->model->type_order_list();

		$type_arr = array();
		foreach($type_list as $val)
		{
			$type_arr[$val['type_id']]['type_id'] = $val['type_id'];
			$type_arr[$val['type_id']]['type_name'] = $val['type_name'];
			$type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
			$type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
			$type_arr[$val['type_id']]['type_order'] = $val['type_order'];
		}
		$type_list = $type_arr;

		$hos_id_arr = array();
		$keshi_id_arr = array();
		$jibing = read_static_cache('jibing_list');
		$jibing_list = array();
		$jibing_parent = array();
		if(!empty($jibing))
		{
			foreach($jibing as $val)
			{
				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
				if($val['jb_level'] == 2)
				{
					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
				}
			}
		}

		$data['from_arr'] = $from_arr;
		$data['from_list'] = $from_list;
		$data['type_list']=$type_list;
		$data['keshi'] = $keshi_arr;
		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;
		$data['hospital'] = $hospital;

		//获取相关地区的信息
		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
		$flag_1 = array();
		$flag_2 = array();
		foreach($province as $val){
			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){
				$flag_1[] = $val;
			}else{
				$flag_2[] = $val;
			}
		}
		$province_list = array_merge($flag_1,$flag_2);
		$data['order_detail']=$order_detail;
		$data['province']=$province_list;
		$data['area']=$area;
		$data['gonghai_log']=$logData;
		$data['rank_type']=$this->model->rank_type();
		$this->load->view('siwei/order_detail.php',$data);


	}

	/*
	 * 检测是否在公海
	* **/
	public function gonghai_check_exits_ajax()
	{
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		$order_id = $this->common->getOne("SELECT order_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
		if(empty($order_id)){
			echo 0;exit;
		}
		echo 1;exit;
	}


	public function gonghai_update_ajax()
	{
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$type = $_REQUEST['type'];
		$remark = isset($_REQUEST['remark'])? trim($_REQUEST['remark']):'';
		if($type == 'visit'){
            //更新公海患者中的预约时间
			$false_id = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;
			if(empty($_COOKIE['l_admin_name'])){$_COOKIE['l_admin_name'] = '';}
			$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 3, 'type_id' => $false_id));

			$mark_id = $this->db->insert_id();
			$zt_id = isset($_REQUEST['zt_id'])? intval($_REQUEST['zt_id']):0;
			$lx_id = isset($_REQUEST['lx_id'])? intval($_REQUEST['lx_id']):0;
			$jg_id = isset($_REQUEST['jg_id'])? intval($_REQUEST['jg_id']):0;
			$ls_id = isset($_REQUEST['ls_id'])? intval($_REQUEST['ls_id']):0;
			$date_lx = isset($_REQUEST['date_lx'])? trim($_REQUEST['date_lx']):'';
			$datehour = isset($_REQUEST['datehour'])? trim($_REQUEST['datehour']):'';
			$nextdate = isset($_REQUEST['date_lx'])?strtotime($_REQUEST['date_lx']):'';
			$gonghai = isset($_REQUEST['gonghai'])?intval($_REQUEST['gonghai']):'0';//判断公海是否有值

			if(empty($gonghai)){
				//查询订单记录
				$order_time_data = $this->common->getRow("SELECT keshi_id,order_time FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
				if(!$order_time_data){
					$order_time_data = $this->common->getRow("SELECT keshi_id,order_time FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
				}
				/**
				 *
				 * 2017 0629 添加功能
				 * 记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
				 * **/
				$date_lx_check =0;
				if(($order_time_data['keshi_id'] == 1 || $order_time_data['keshi_id'] == 32) && !empty($order_time_data['order_time']) ){
					//时间相同 不用改
					if(strcmp(date("Y-m-d", $order_time_data['order_time']),$date_lx) != 0 ){
						$order_reanai_sum_data  = $this->common->getAll("SELECT * FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
						if(count($order_reanai_sum_data) > 0){
							if($order_reanai_sum_data[0]['sum'] == 3){
								$date_lx_check =1;//超过3次的不能继续修改预到时间
							}else{
								$hui_order_reanai_sum_data = array();
								$hui_order_reanai_sum_data['sum'] = intval($order_reanai_sum_data[0]['sum'])+1;
								$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
							}
						}
					}
				}
				if(!empty($date_lx_check)){
					//更新order表和gonghai_order表中的order_time 和order_time_duan
					$query1="update ".$this->common->table('gonghai_order')." set order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query1);
					$query2="update ".$this->common->table('order')." set order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query2);
					$date_lx = $order_time_data['order_time'];
					if(!empty($datehour)){
						$date_lx .= ' '.$datehour;
					}
					$remark_extend = array(
							'mark_id' 	=> $mark_id,
							'zt_id'		=> $zt_id,
							'lx_id'		=> $lx_id,
							'jg_id'		=> $jg_id,
							'ls_id'		=> $ls_id,
							'date_lx'	=> strtotime($date_lx),
					);
					$this->db->insert($this->common->table('remark_extend'), $remark_extend);
				}else{
					//更新order表和gonghai_order表中的order_time 和order_time_duan
					$query1="update ".$this->common->table('gonghai_order')." set order_time='".$nextdate."',order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query1);
					$query2="update ".$this->common->table('order')." set order_time='".$nextdate."',order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query2);

					if(!empty($datehour)){
						$date_lx .= ' '.$datehour;
					}
					$remark_extend = array(
							'mark_id' 	=> $mark_id,
							'zt_id'		=> $zt_id,
							'lx_id'		=> $lx_id,
							'jg_id'		=> $jg_id,
							'ls_id'		=> $ls_id,
							'date_lx'	=> strtotime($date_lx),
					);
					$this->db->insert($this->common->table('remark_extend'), $remark_extend);

					//更新操作日志
					$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'更新预到时间为:'.$date_lx,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>time(),'action_id'=>$_COOKIE['l_admin_id']));


				}
			}else{
					//更新order表和gonghai_order表中的order_time 和order_time_duan
					$query1="update ".$this->common->table('gonghai_order')." set order_time='".$nextdate."',order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query1);
					$query2="update ".$this->common->table('order')." set order_time='".$nextdate."',order_time_duan='".$datehour."'  where order_id=".$order_id;
					$this->db->query($query2);

					if(!empty($datehour)){
						$date_lx .= ' '.$datehour;
					}
					$remark_extend = array(
							'mark_id' 	=> $mark_id,
							'zt_id'		=> $zt_id,
							'lx_id'		=> $lx_id,
							'jg_id'		=> $jg_id,
							'ls_id'		=> $ls_id,
							'date_lx'	=> strtotime($date_lx),
					);
					$this->db->insert($this->common->table('remark_extend'), $remark_extend);

					//更新操作日志
					$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'更新预到时间为:'.$date_lx,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>time(),'action_id'=>$_COOKIE['l_admin_id']));

			}
			$str = '<blockquote><p>' . $remark;
			if(!empty($false_id))
			{
				$false_str = $this->common->getOne("SELECT false_name FROM " . $this->common->table('dao_false') . " WHERE false_id = $false_id");
				$str .= '<font color=#FF0000>（未到诊原因：' . $false_str . '）</font>';
			}
			$str .= '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

			/**
			 * 推送数据到数据中心
			 * */
			//$this->send_order_shujuzhognxin($order_id);
			//$this->send_huifang_to_shujuzhongxin($order_id);

		}elseif($type == 'dao'){
			$doctor_name = $_REQUEST['doctor_name'];
			$this->db->update($this->common->table('order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));
                        $this->db->update($this->common->table('gonghai_order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));
			$str = array('come_time' => date("Y-m-d H:i"));
			$info = $this->model->order_info($order_id);
			if($info['hos_id']==1){
				$username = $info['pat_phone'];
				$this->group_change($username);
			}
			if(!empty($remark)) {
				$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 2));
				$str['remark'] = '<blockquote class="d"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
			}
			$this->db->update($this->common->table('ask_data'), array('is_dao' => 1), array('order_id' => $order_id));
			$site_addtime = $this->common->getOne("SELECT order_addtime FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
			$site_addtime = strtotime(date("Y-m-d", $site_addtime) . ' 00:00:00');
			$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_daozhen = site_daozhen + 1 WHERE site_addtime = " . $site_addtime);
			$str = json_encode($str);

			/**
			 * 推送数据到数据中心
			 * */
			//$this->send_order_shujuzhognxin($order_id);
			//$this->send_daozhen_to_shujuzhongxin($remark,$order_id);

		}elseif($type == 'doctor'){
			$come_time = $this->common->getOne("SELECT come_time FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
			if(empty($come_time)){
				$come_time = time();
			}
			$doctor_name = $_REQUEST['doctor_name'];
			$this->db->update($this->common->table('order'), array('come_time' => $come_time, 'doctor_name' => $doctor_name, 'doctor_time' => time()), array('order_id' => $order_id));
			$str = array('come_time' => date("Y-m-d H:i", $come_time));
			$str = array('doctor_time' => date("Y-m-d H:i"));
			if(!empty($remark)){
				$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 5));
				$str['remark'] = '<blockquote class="doc"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
			}
			$str = json_encode($str);
		}
		echo $str;
	}

	//编辑
	public function order_info()
	{
		$data = array();
		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);
		$type     = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);
		$p = isset($_REQUEST['p'])? $_REQUEST['p']:0;
		if(empty($order_id))
		{
			$data = $this->common->config('order_add');
		}
		else
		{
			if(($p == 1) || ($p == 2))
			{
				$data = $this->common->config('order_add');
			}
			else
			{
				$data = $this->common->config('order_edit');
			}
			$info = $this->model->order_info($order_id);
			if(empty($info))
			{
				exit('错误的授权！');
			}
			if(($p == 2) && ($info['admin_id'] != $_COOKIE['l_admin_id']))
			{
				$data = $this->common->config('order_edit');
			}
			if($info['admin_id'] == 0)
			{
				$data = $this->common->config('order_add');
				$p = 2;
			}

			$laiyuanwebdata =  $this->common->getAll("SELECT form,guanjianzi FROM " . $this->common->table("order_laiyuanweb") . " WHERE order_id = $order_id");
			$info['laiyuanweb'] = '';
			$info['guanjianzi'] = '';
			if(count($laiyuanwebdata) > 0){
				$info['laiyuanweb'] = $laiyuanwebdata[0]['form'];
				$info['guanjianzi'] = $laiyuanwebdata[0]['guanjianzi'];
			}
			$data['info'] = $info;
			$remark = $this->model->order_remark($order_id);
			$data['remark'] = $remark;
			$con_content = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");
			$data['con_content'] = $con_content;
			$order_data = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");
			$data['order_data'] = $order_data;

			/***
			 * 2017 07 28 获取来源网址
			*/
			$data['keshiurl_data'] = array();
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
				if (in_array($data['info']['hos_id'], $l_hos_id)){
					$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =".$data['info']['hos_id']." and ( keshi_id =  0 or  keshi_id in(".$_COOKIE["l_keshi_id"].")) and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
					$data['keshiurl_data'] = $this->common->getAll($sql);
				}
			}else{
				$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =".$data['info']['hos_id']." and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
				$data['keshiurl_data'] = $this->common->getAll($sql);
			}

		}
		$data['p'] = $p;
		$from_list = $this->model->from_order_list();
		$hospital = $this->model->hospital_order_list();
		//$keshi = $this->model->keshi_order_list();
		$type_list = $this->model->type_order_list();
		$asker_list = $this->model->asker_list(1);
		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
		$flag_1 = array();
		$flag_2 = array();
		foreach($province as $val){
			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){
				$flag_1[] = $val;

			}else{
				$flag_2[] = $val;
			}
		}
		$province_list = array_merge($flag_1,$flag_2);

		if(isset($_COOKIE['l_hos_id']))
		{
			$l_hos_id = explode(",", $_COOKIE['l_hos_id']);
			krsort($l_hos_id);
			$data['l_hos_id'] = $l_hos_id[0];
		}
		else
		{
			$data['l_hos_id'] = 1;
		}
		$rank_type = $this->model->rank_type();
		$data['rank_type'] = $rank_type;
		$data['type'] = $type;
		$data['province'] = $province_list;
		$data['asker_list'] = $asker_list;
		$data['from_list'] = $from_list;
		$data['hospital'] = $hospital;
		//$data['keshi'] = $keshi;
		$data['type_list'] = $type_list;

		/**2016 10 27   不具备修改 咨询员权限的人 不能更改咨询员信息**/
		/**2016 10 27   不具备修改 患者个信息的人 不能更**/
		if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(148,$l_admin_action)){
		     	$data['order_edit_consultants'] = '1';
	         }
	         if(in_array(149,$l_admin_action)){
		     	$data['order_edit_person_info'] = '1';
	         }
		}else{
			$data['order_edit_consultants'] = '1';
			$data['order_edit_person_info'] = '1';
		}

		/***
		 * 2017 07 01 仁爱妇科 不孕
		*  查询 仁爱预约数量
		*/
		$sql_order_reanai_sum = "SELECT * FROM " . $this->common->table('order_reanai_sum') . "  WHERE  order_id = ".$order_id;
		$data['info_yd_time'] = $this->common->getRow($sql_order_reanai_sum);

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('siwei/person_info_mi', $data);
	}

	//台州导医快捷查询开始
	public function daoyi_query(){
		$data=array();
		$dy_order_no= isset($_REQUEST['dy_order_no'])?strtoupper(trim($_REQUEST['dy_order_no'])):'';
		$dy_order_name= isset($_REQUEST['dy_order_name'])?trim($_REQUEST['dy_order_name']):'';
		$dy_order_phone= isset($_REQUEST['dy_order_phone'])?trim($_REQUEST['dy_order_phone']):'';
		$where="";
		if(!empty($dy_order_no)){
			$where.=" AND o.order_no='".$dy_order_no."'";
		}
		if(!empty($dy_order_name)){
			$where.=" AND p.pat_name='".$dy_order_name."'";
		}
		if(!empty($dy_order_phone)){
			$where.=" AND p.pat_phone='".$dy_order_phone."'";
		}
		if(empty($dy_order_no)&&empty($dy_order_name)&&empty($dy_order_phone)){
			$data['type']=3;
		}else{
			$res1=$this->common->getAll("select o.order_id,o.is_come,o.order_no,o.order_addtime,o.order_time,o.admin_name,p.pat_name,p.pat_phone,h.hos_name  from ".$this->common->table('order')." o left join ".$this->common->table('patient')." p on o.pat_id=p.pat_id left join ".$this->common->table('hospital')." h on o.hos_id=h.hos_id where (o.hos_id=3 or o.hos_id=15) ".$where);
			$res2=$this->common->getAll("select o.order_id,o.is_come,o.order_no,o.order_addtime,o.order_time,o.admin_name,p.pat_name,p.pat_phone,h.hos_name  from ".$this->common->table('gonghai_order')." o left join ".$this->common->table('patient')." p on o.pat_id=p.pat_id left join ".$this->common->table('hospital')." h on o.hos_id=h.hos_id where (o.hos_id=3 or o.hos_id=15) ".$where);
			if(!empty($res1)){
				foreach($res1 as $val)
				{
					$data['order'][$val['order_id']] = $val;
					$data['order'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
				}
			}
			if(!empty($res2)){
				foreach($res2 as $val)
				{
					$data['gonghai'][$val['order_id']] = $val;
					$data['gonghai'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
				}
			}
			if(empty($res1)&&empty($res2)){
				$data['type']=2;

			}else{
				$data['type']=1;
			}
		}
		$data = json_encode($data);
		echo $data;
	}

    //在个人预约中编辑预约订单
    public function order_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$order_id        = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		$p_id            = isset($_REQUEST['pad_id'])? intval($_REQUEST['pad_id']):0;
		$p               = isset($_REQUEST['p'])? intval($_REQUEST['p']):0;
		$remark          = trim($_REQUEST['remark']);
		$sms_themes      = isset($_REQUEST['sms_themes'])? intval($_REQUEST['sms_themes']):0;
		$sms_content     = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';
		$type            = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$order_no        = trim($_REQUEST['order_no']);
		$from_parent_id  = intval($_REQUEST['from_parent_id']);
		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']) : 0;
		$is_first        = intval($_REQUEST['is_first']);
		$from_value      = trim($_REQUEST['from_value']);
		$hos_id          = intval($_REQUEST['hos_id']);
		$keshi_id        = intval($_REQUEST['keshi_id']);
		$type_id         = intval($_REQUEST['type_id']);
		$jb_parent_id    = intval($_REQUEST['jibing_parent_id']);
		$jb_id           = intval($_REQUEST['jibing_id']);
		$admin_id        = intval($_REQUEST['admin_id']);
		$pat_name        = trim($_REQUEST['patient_name']);
		$pat_sex         = intval($_REQUEST['pat_sex']);
		$pat_phone       = trim($_REQUEST['patient_phone']);
		$pat_weixin       = trim($_REQUEST['patient_weixin']);
		if(empty($pat_weixin)){
			$pat_weixin = '';
		}
		$pat_qq         = trim($_REQUEST['patient_qq']);
		if(empty($pat_qq)){
			$pat_qq = '';
		}
		$pat_age         = trim($_REQUEST['patient_age']);
		$order_time      = trim($_REQUEST['order_time']);

		$order_time_duan_d = !empty($_REQUEST['order_time_duan_d'])? trim($_REQUEST['order_time_duan_d']):'';
		$order_time_duan_j = !empty($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';
		if($jb_id == 300 || $jb_id == 301){
			$order_time_duan_j = !empty($_REQUEST['order_time_duan_b'])? trim($_REQUEST['order_time_duan_b']):'';
		}
		$duan_confirm      = intval($_REQUEST['duan_confirm']);
		$pat_province    = intval($_REQUEST['province']);
		$pat_city        = intval($_REQUEST['city']);
		$pat_area        = intval($_REQUEST['area']);
		$pat_address     = trim($_REQUEST['patient_address']);

		$con_content =  stripslashes(trim($_REQUEST['con_content']));

		$data_time       = trim($_REQUEST['data_time']);
		$data_time       = strtotime($data_time);
		$yunzhou         = intval($_REQUEST['yunzhou']);

		$order_time_duan = ($duan_confirm == 1)? $order_time_duan_d:$order_time_duan_j;
		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';
		$order_time_type = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;
		$keshiurl_id = isset($_REQUEST['keshiurl_id'])? intval($_REQUEST['keshiurl_id']):0;

		$laiyuanweb       = trim($_REQUEST['laiyuanweb']);//来源网址

		$guanjianzi       = trim($_REQUEST['guanjianzi']);//关键字

		if(empty($pat_name))
		{
			$this->common->msg("患者姓名不能为空！", 1);
		}

		if(empty($hos_id))
		{
			$this->common->msg("医院不能为空！", 1);
		}

		if(empty($keshi_id))
		{
			$this->common->msg("科室不能为空！", 1);
		}

		if(empty($admin_id))
		{
			$this->common->msg("咨询员不能为空！", 1);
		}

		if(empty($order_no))//预约单号自增
		{
			$order_no = file_get_contents("./application/cache/static/order_no.txt");
			$zm = array(            '1' => 'A',
						'2' => 'B',
						'3' => 'C',
						'4' => 'D',
						'5' => 'E',
						'6' => 'F',
						'7' => 'G',
						'8' => 'H',
						'9' => 'I',
						'10' => 'J',
						'11' => 'K',
						'12' => 'L',
						'13' => 'M',
						'14' => 'N',
						'15' => 'O',
						'16' => 'P',
						'17' => 'Q',
						'18' => 'R',
						'19' => 'S',
						'20' => 'T',
						'21' => 'U',
						'22' => 'V',
						'23' => 'W',
						'24' => 'X',
						'25' => 'Y',
						'26' => 'Z');

			if(empty($order_no)){
				$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
			}

			if(empty($order_no))
			{
				$order_no = "AA0001";
			}
			else
			{
				$order_no_zimu = substr($order_no, 0, 2);
				$order_no_shuzi = substr($order_no, 2, 4);
				$order_no_shuzi = intval($order_no_shuzi) + 1;

				if($order_no_shuzi >= 9999)
				{
					$order_no_zimu_a = substr($order_no_zimu, 0, 1);
					$order_no_zimu_b = substr($order_no_zimu, 1, 1);
					$mz = array_flip($zm);
					if($order_no_zimu_b == 'Z')
					{
						$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];
						$order_no_zimu_b = 'A';
					}
					else
					{
						$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];
					}
					$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;
					$order_no_shuzi = '0001';
				}
				else
				{
					$zimu_len = 4 - strlen($order_no_shuzi);
					for($i = 1; $i <= $zimu_len; $i ++)
					{
						$order_no_shuzi = "0" . $order_no_shuzi;
					}
				}
				$order_no = $order_no_zimu . $order_no_shuzi;
			}
			file_put_contents("./application/cache/static/order_no.txt", $order_no);
		}
		$pat_phone = explode("/", $pat_phone);
		$patient = array('pat_name' => $pat_name,
					     'pat_sex' => $pat_sex,
					     'pat_age' => $pat_age,
					     'pat_province' => $pat_province,
					     'pat_city' => $pat_city,
						 'pat_area' => $pat_area,
						 'pat_address' => $pat_address,
						 'pat_phone' => $pat_phone[0],
						 'pat_weixin' => $pat_weixin,
						 'pat_qq' => $pat_qq,
						 'pat_phone1' => isset($pat_phone[1])? $pat_phone[1]:'');

		$admin_name = $this->common->getOne("SELECT admin_name FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");
		$order = array('order_no' => $order_no,
					   'is_first' => $is_first,
					   'admin_id' => $admin_id,
						'admin_name' => $admin_name,
					   'from_parent_id' => $from_parent_id,
					   'from_id' => $from_id,
					   'from_value' => $from_value,
					   'hos_id' => $hos_id,
					   'keshi_id' => $keshi_id,
					   'type_id' => $type_id,
					   'jb_parent_id' => $jb_parent_id,
					   'jb_id' => $jb_id,
					   'order_time_duan' => $order_time_duan,
						'keshiurl_id' => $keshiurl_id,
					   'duan_confirm' => $duan_confirm);

		if($order_time_type == 1)
		{
			$order['order_time'] = strtotime($order_time);
			$order['order_null_time'] = '';
		}
		elseif($order_time_type == 2)
		{
			     if($form_action == 'update')
				{
					if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){

					}else{
					  $order['order_time'] = 0;
					  $order['order_null_time'] = empty($order_null_time)? '未定':$order_null_time;
				    }
				}else{
					if($keshi_id != 12 && $keshi_id != 46 && $keshi_id != 15){
						/**
						 2016 1111   carlcao  修改 如果类型为未定，系统默认延后一个月时间
						 台州东方男科 默认7天，其他科室 默认一个月
						**/
						 $order_time =time()+30*24*60*60;
						if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){
							 $order_time =time()+7*24*60*60;
						}
				    }else{
					   $order_time = '0';
					}
					$order['order_time'] =  $order_time;
					$order['order_null_time'] = '';

				}
		}


		/**记录日志*/
		 $update_info=$this->model->order_update_log($order_id,array_merge($order,$patient));
		 $time1=time();
		 if(empty($update_info)){
			 $update_info="暂无重要信息修改记录！";
			 $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>$update_info,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time1,'action_id'=>$_COOKIE['l_admin_id']));
		 }else{
			 $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>$update_info,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time1,'action_id'=>$_COOKIE['l_admin_id']));         }



        //查询没修改前的数据
        $this->db->set_dbprefix("hui_");
        $order_row_res = $this->db->get_where("order",array('order_id' => $order_id))->row_array();



		if($form_action == 'update')
		{
			if(($p == 1) || ($p == 2))
			{
				$data = $this->common->config('order_add');
			}
			else
			{
				$data = $this->common->config('order_edit');
			}

			$come_time   = isset($_REQUEST['come_time'])? trim($_REQUEST['come_time']):'';
			$doctor_name = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';
			if($come_time != "")
			{
				$order['come_time'] = strtotime($come_time);
				$order['doctor_name'] = $doctor_name;
			}


			 $defult_patient_phone       = trim($_REQUEST['defult_patient_phone']);
			 if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
				 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
				 //  更改 咨询员名称
			     if(!in_array(148,$l_admin_action)){
			     	 unset($order['admin_id']);
			     	 unset($order['admin_name']);
		         }

		         if(empty($defult_patient_phone)){
					/** 如果一开始咨询员未录入电话号码  则默认添加电话号码
					已录入电话号码的 就需要权限修改了。
					*/
					 if(!in_array(149,$l_admin_action)){
					 	unset($patient['pat_name']);
					 	unset($patient['pat_phone1']);
			         }
		         }else{
		         	if(!in_array(149,$l_admin_action)){
		         		unset($patient['pat_name']);
		         		unset($patient['pat_phone']);
		         		unset($patient['pat_phone1']);
		         		unset($patient['pat_qq']);
		         		unset($patient['pat_weixin']);
			         }
		         }

			}

			$this->db->update($this->common->table("patient"), $patient, array('pat_id' => $p_id));
			//先查询订单数据 后面 宝宝 分缸有用
			$order_time_duan_data  = $this->common->getAll("SELECT o.order_time_duan,o.order_time,o.jb_id,ot.baby_select_type_id FROM " . $this->common->table('order') ." as o left join " . $this->common->table('baby_stype_order') ." as ot on o.order_no = ot.order_no  WHERE o.order_id = ".$order_id);
			if(count($order_time_duan_data) == 0){
				$order_time_duan_data  = $this->common->getAll("SELECT o.order_time_duan,o.order_time,o.jb_id,ot.baby_select_type_id FROM " . $this->common->table('gonghai_order') ." as o left join " . $this->common->table('baby_stype_order') ." as ot on o.order_no = ot.order_no  WHERE o.order_id = ".$order_id);
			}

			/**
			 *
			 * 2017 0629 添加功能
			 * 记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
			 * **/
			/***
			if($order['keshi_id'] == 1 || $order['keshi_id'] == 32){
				if(empty($order['order_time'])){
					$this->db->delete($this->common->table('order_reanai_sum'), array('order_id' =>$order_id));
				}else{
					$order_time_data  = $this->common->getAll("SELECT order_time,admin_id FROM " . $this->common->table('order') ."  WHERE order_id = ".$order_id);
					//判断咨询员是否变更，如果变更 计数归0.未变更的 则计数累积。
					if($admin_id == $order_time_data[0]['admin_id']){
						//时间相同 不用改
						if(strcmp($order_time_data[0]['order_time'],$order['order_time']) != 0 ){
							$order_reanai_sum_data  = $this->common->getAll("SELECT sum FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
							if(count($order_reanai_sum_data) > 0){
								if($order_time_data[0]['admin_id'] == $_COOKIE['l_admin_id'] ){//咨询员自己改时间
									if($order_reanai_sum_data[0]['sum'] == 3){
										unset($order['order_time']);//超过3次的不能继续修改预到时间
									}else{
										$hui_order_reanai_sum_data = array();
										$hui_order_reanai_sum_data['sum'] = intval($order_reanai_sum_data[0]['sum'])+1;
										$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
									}
								}else{//非咨询员改时间
									if($order_reanai_sum_data[0]['sum'] < 3){
										unset($order['order_time']);//未超过3次的  不能干涉 修改时间
									}else{
										$hui_order_reanai_sum_data = array();
										$hui_order_reanai_sum_data['sum'] = 3;
										// 以当天预到时间的23:59:59为最后流入公海判断时间  等于或者超过改时间，而未就诊的 自动掉入公海
										$hui_order_reanai_sum_data['order_time'] = strtotime(date("Y-m-d",$order['order_time'])." 23:59:59");
										$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
									}
								}
							}else{
								//config 设置的 日期天
								$dy = $this->config->item('renfi_fk_by_day_time');
								if(!is_numeric($dy)){
									$dy = 7;
								}else if(floor($dy) == 0){
									$dy = 7;
								}else if(floor($dy) != $dy){
									$dy = 7;
								}
								//第一次预到时间 在加 $dy 天
								$hui_order_reanai_sum = array();
								$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
								$hui_order_reanai_sum['order_id'] =$order_id;
								$hui_order_reanai_sum['sum'] = 0;
								$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
							}
						}
					}else{
						$order_reanai_sum_data  = $this->common->getAll("SELECT order_id FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
						if(count($order_reanai_sum_data) > 0){
							//咨询员变了
							//config 设置的 日期天
							$dy = $this->config->item('renfi_fk_by_day_time');
							if(!is_numeric($dy)){
								$dy = 7;
							}else if(floor($dy) == 0){
								$dy = 7;
							}else if(floor($dy) != $dy){
								$dy = 7;
							}
							$hui_order_reanai_sum_data = array();
							$hui_order_reanai_sum_data['sum'] = 0;
							// 以当天预到时间的23:59:59为最后流入公海判断时间  等于或者超过改时间，而未就诊的 自动掉入公海
							$hui_order_reanai_sum_data['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
							$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
						}else{
							$hui_order_reanai_sum = array();
							//config 设置的 日期天
							$dy = $this->config->item('renfi_fk_by_day_time');
							if(!is_numeric($dy)){
								$dy = 7;
							}else if(floor($dy) == 0){
								$dy = 7;
							}else if(floor($dy) != $dy){
								$dy = 7;
							}
							//第一次预到时间 在加 $dy 天
							$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
							$hui_order_reanai_sum['order_id'] =$order_id;
							$hui_order_reanai_sum['sum'] = 0;
							$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
						}
					}
				}
			}
			**/

			$this->db->update($this->common->table("order"), $order, array('order_id' => $order_id));
            $this->db->update($this->common->table("gonghai_order"), $order, array('order_id' => $order_id));

            //更新来源网址
            $order_laiyuanweb = array();
            $order_laiyuanweb['form'] = $laiyuanweb;
			$order_laiyuanweb['guanjianzi'] = $guanjianzi;
            $this->db->update($this->common->table("order_laiyuanweb"), $order_laiyuanweb, array('order_id' => $order_id));


			//记录宝宝缸数
			if($order['jb_id'] == 300 || $order['jb_id'] == 301 ){
				if(!empty($order_time_duan_data[0]['baby_select_type_id'])){
					 $sql = 'select id,sum from '.$this->common->table('baby_select_type')." where id =  ".$order_time_duan_data[0]['baby_select_type_id'];
					$baby_select_type_data = $this->common->getAll($sql);
					if(count($baby_select_type_data) > 0){
						$baby_select_type_array = array();
						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])-1;
						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));
					}
				}
				//删除旧的预约单关联记录
				$this->db->delete($this->common->table('baby_stype_order'), array('order_no' =>$order['order_no']));

				$order_time_duan_array = explode('~',$order['order_time_duan'] );
				$sql = 'select id from '.$this->common->table('baby_type')." where jb_id  = ".$order['jb_id']." and time_start = '".$order_time_duan_array[0]."' and time_end = '".$order_time_duan_array[1]."'";
				$baby_type_data = $this->common->getone($sql);
				if(!empty($baby_type_data)){
					$sql = 'select id,sum from '.$this->common->table('baby_select_type')." where baby_type_id =  ".$baby_type_data." and days = '".date('Y-m-d',$order['order_time'])."'";
					$baby_select_type_data = $this->common->getAll($sql);
					if(count($baby_select_type_data) > 0){
						$baby_select_type_array = array();
						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])+1;
						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));
						//增加预约单和宝宝缸表 关联表
						$baby_type_order_array = array();
						$baby_type_order_array['baby_select_type_id'] = $baby_select_type_data[0]['id'];
						$baby_type_order_array['order_no'] =$order['order_no'];
						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);
					}else{
						$baby_select_type_array = array();
						$baby_select_type_array['baby_type_id'] = $baby_type_data;
						$baby_select_type_array['days'] =date('Y-m-d',$order['order_time']);
						$baby_select_type_array['sum'] =1;
						$baby_select_type_array['add_time'] =date('Y-m-d H:i:s',time());
						$this->db->insert($this->common->table("baby_select_type"), $baby_select_type_array);

						//增加预约单和宝宝缸表 关联表
						$baby_type_order_array = array();
						$baby_type_order_array['baby_select_type_id'] = $this->db->insert_id();
						$baby_type_order_array['order_no'] =$order['order_no'];
						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);
					}
				}
			}else{
				if(!empty($order_time_duan_data[0]['baby_select_type_id'])){
					 $sql = 'select id,sum from '.$this->common->table('baby_select_type')." where id =  ".$order_time_duan_data[0]['baby_select_type_id'];
					$baby_select_type_data = $this->common->getAll($sql);
					if(count($baby_select_type_data) > 0){
						$baby_select_type_array = array();
						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])-1;
						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));
					}
				}
				if(in_array($order_time_duan_data[0]['jb_id'],array(300,301))){
					//删除旧的预约单关联记录
					$this->db->delete($this->common->table('baby_stype_order'), array('order_no' =>$order['order_no']));
				}
			}

			$con_id = $this->common->getOne("SELECT con_id FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");
			if($con_id)
			{
				$this->db->update($this->common->table("ask_content"), array('con_content' => $con_content), array('order_id' => $order_id));
			}
			else
			{
				$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));
			}
		}
		else
		{
			$this->common->config('order_add');
			$is_order = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE order_no = '" . $order_no . "'");
			if($is_order)
			{
				$this->common->msg("请勿重复提交！", 1);
			}
			$patient['pat_weixin'] = $pat_weixin;
			$patient['pat_qq'] = $pat_qq;
			$this->db->insert($this->common->table("patient"), $patient);
			$pat_id = $this->db->insert_id();
			$order['pat_id'] = $pat_id;
			$order['order_addtime'] = time();
			$this->db->insert($this->common->table("order"), $order);
			$order_id = $this->db->insert_id();

			//添加来源网址
			$order_laiyuanweb = array();
			$order_laiyuanweb['form'] = $laiyuanweb;
			$order_laiyuanweb['guanjianzi'] = $guanjianzi;
			$order_laiyuanweb['order_id'] = $order_id;
			$this->db->insert($this->common->table("order_laiyuanweb"), $order_laiyuanweb);


			/**
			 *
			 * 2017 0629 添加功能
			 * 记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
			 * **/
			if(($order['keshi_id'] == 1 || $order['keshi_id'] == 32) && !empty($order['order_time']) ){
				$hui_order_reanai_sum = array();
				//config 设置的 日期天
				$dy = $this->config->item('renfi_fk_by_day_time');
				if(!is_numeric($dy)){
					$dy = 7;
				}else if(floor($dy) == 0){
					$dy = 7;
				}else if(floor($dy) != $dy){
					$dy = 7;
				}
				//第一次预到时间 在加 $dy 天
				$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
				$hui_order_reanai_sum['order_id'] =$order_id;
				$hui_order_reanai_sum['sum'] = 0;
				$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
			}


			if($hos_id==1){
				$regis = array();
				$regis['username'] = $patient['pat_phone'];
				$regis['nickname'] = $patient['pat_name'];
				$regis['password'] = $order['order_no'];
				$this->register($regis);
			}
			if(isset($_REQUEST['card'])){
				$card = trim($_REQUEST['card']);
			}
			if(!empty($card)){
				$this->db->insert($this->common->table("card_data"),array('order_id' => $order_id,'img' => $card));
			}
			$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));
		}

		$this->db->query("DELETE FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");
		if(!empty($yunzhou))
		{
			$this->db->insert($this->common->table("order_data"), array('order_id' => $order_id, 'data_time' => $data_time));
		}
		if(!empty($remark))
		{
			$remark = array('order_id' => $order_id,
							'admin_id' => $_COOKIE['l_admin_id'],
							'admin_name' => $_COOKIE['l_admin_name'],
							'mark_content' => $remark,
							'mark_time' => time(),
							'mark_type' => 1);

			$this->db->insert($this->common->table("order_remark"), $remark);
		}


		if (in_array($hos_id, $this->config->item("allowed_drop_hos_id"))) {
			if ($form_action == "add") {
			    //记录预到时间用于判断咨询修改预约次数
			    $ordertime_records = array(
			        'order_id' => $order_id,
			        'admin_id' => $_COOKIE['l_admin_id'],
			        'order_time' => $order['order_time'],
			        'action_time' => time(),
			        'action_type' => 1,
			    );
			    $this->db->set_dbprefix("henry_");
			    $this->db->insert("ordertime_update_record", $ordertime_records);
			    //p($this->db->last_query());die();
			} elseif ($form_action == "update") {
			    if ($order_row_res['order_time'] != $order['order_time'] ) {
			        $this->db->set_dbprefix("henry_");
			        //记录预到时间用于判断咨询修改预约次数
			        $ordertime_records = array(
			            'order_id' => $order_id,
			            'admin_id' => $_COOKIE['l_admin_id'],
			            'order_time' => $order['order_time'],
			            'action_time' => time(),
			            'action_type' => 2,
			        );
			        $this->db->insert("ordertime_update_record", $ordertime_records);
			        //p($this->db->last_query());die();
			    }
			}
		}

		$this->db->set_dbprefix("hui_");



		$msg_detail = $this->lang->line('success');

		/**
		 * 推送数据到数据中心
		 * */
		//$this->send_order_shujuzhognxin($order_id);

		if($sms_themes > 0)
		{
			$arr = array('send_content' => $sms_content,
						'send_time' => time(),
						 'send_phone' => $pat_phone[0],
						 'send_type' => 1,
						 'type_value' => $order_id,
						 'hos_id' => $hos_id,
						 'keshi_id' => $keshi_id,
						 'admin_id' => $_COOKIE['l_admin_id'],
						 'admin_name' => $_COOKIE['l_admin_name']);

			$this->db->insert($this->common->table("sms_send"), $arr);
			$msgid = $this->db->insert_id();
			$sms_content = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

			require_once('application/libraries/sms/nusoap.php');
			$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
			$client->soap_defencoding = 'utf-8';
			$client->decode_utf8      = false;
			$client->xml_encoding     = 'utf-8';
			$send_phone = implode(";", $pat_phone);

			$params = array(
				'account' => $hospital['sms_name'],
				'password' => $hospital['sms_pwd'],
				'destmobile' => $send_phone,
				'msgText' => $sms_content,
			);

			$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
			$send_status = $result['sendBatchMessageReturn'];
			$status = $this->lang->line('sms_send_status');
			if($send_status >= 0)
			{
				$msg_detail = "短信发送成功！";
				$send_status = 0;
			}
			else
			{
				$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";
			}
			$this->db->update($this->common->table('sms_send'), array('send_status' => $send_status), array('send_id' => $msgid));
			$msg_detail = mb_convert_encoding($msg_detail,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
			header('Cache-control: private');
    		header('Content-type: text/html; charset=utf-8');
			echo "<script language=\"javascript\">alert('" . $msg_detail . "');</script>";
		}
		if($type == 'mi')
		{
			echo "<script language=\"javascript\">window.opener.location.reload(); window.close();</script>";
		}
		else
		{
			$links[0] = array('href' => '?c=order&m=order_info&order_id=' . $order_id . '&p=' . $p, 'text' => $this->lang->line('edit_back'));
			$links[1] = array('href' => '?c=order&m=order_info', 'text' => $this->lang->line('add_back'));
			$links[2] = array('href' => '?c=order&m=order_list', 'text' => $this->lang->line('list_back'));
			$this->common->msg($msg_detail, 0, $links, true, false,0);
		}
	}


    /*展示预约详情**/
	public function get_remark_info(){

		$data = array();
		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);
		$data['info'] = $this->common->getRow("SELECT * FROM " . $this->common->table("gonghai_order") . " WHERE order_id = $order_id");
		$data['remark'] = $this->model->order_remark($order_id);
		$data['con_content'] = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");
		$data['order_data'] = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

		$from_list = $this->model->from_order_list();
		$hospital = $this->model->hospital_order_list();
		//$keshi = $this->model->keshi_order_list();
		$type_list = $this->model->type_order_list();
		$asker_list = $this->model->asker_list(0);
		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
		$flag_1 = array();
		$flag_2 = array();
		foreach($province as $val){
			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){
				$flag_1[] = $val;
			}else{
				$flag_2[] = $val;
			}
		}
		$province_list = array_merge($flag_1,$flag_2);
		if(isset($_COOKIE['l_hos_id'])){
			$l_hos_id = explode(",", $_COOKIE['l_hos_id']);
			krsort($l_hos_id);
			$data['l_hos_id'] = $l_hos_id[0];
		}else{
			$data['l_hos_id'] = 1;
		}
		$rank_type = $this->model->rank_type();
		$data['rank_type'] = $rank_type;
		$data['province'] = $province_list;
		$data['asker_list'] = $asker_list;
		$data['from_list'] = $from_list;
		$data['hospital'] = $hospital;
		//$data['keshi'] = $keshi;
		$data['type_list'] = $type_list;
		$this->load->view('siwei/order_info', $data);

	}

	/***
	 * 推送到诊信息到数据中心
	 * */
	private function send_daozhen_to_shujuzhongxin($remark,$order_id){
		$send_array = array();
		$send_data = array();
		$send_data['operation_type'] = 'daozhen';
		$apisql = 'select doctor_name,ireport_order_id,come_time,is_come from '.$this->common->table('order')." where order_id  = '".$order_id."'";
		$ireport_order_data = $this->common->getAll($apisql);
		if(!empty($ireport_order_data[0]['ireport_order_id'])){
			$send_data['order_id'] = $order_id;
			$send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];

			if(!empty($ireport_order_data[0]['is_come'])){
				$send_data['visited'] =  1;
				$send_data['visite_date'] =  $ireport_order_data[0]['come_time'];
				$send_data['doctor'] =  $ireport_order_data[0]['doctor_name'];
			}else{
				$send_data['visited'] =  0;
				$send_data['visite_date'] =  '';
				$send_data['doctor'] =  '';
			}

			$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type != 3';
			$api_order_remark_data  = $this->common->getAll($apisql);
			$api_order_remark_str = '';
			foreach($api_order_remark_data as $api_order_remark_data_t){
				if(empty($api_order_remark_str)){
					$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}else{
					$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}
			}
			$send_data['memo'] = $api_order_remark_str.'#'.$remark.'['.$_COOKIE['l_admin_name'].']';
			$send_array[] =$send_data;
			//$this->sycn_order_data_to_ireport($send_array);

			//剔除仁爱数据推送
			$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
			if(!$order_hos_id){
				$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
			}

			if (!in_array('1',$order_hos_id)) {
				$this->sycn_order_data_to_ireport($send_array);
			}

		}

	}

	/**推送回访信息到数据中心
	 *
	 * **/
	private function send_huifang_to_shujuzhongxin($order_id){
		$send_array = array();
		$send_data = array();
		$send_data['operation_type'] = 'huifang';
		$apisql = 'select ireport_order_id,order_time,is_come from '.$this->common->table('order')." where order_id  = '".$order_id."'";
		$ireport_order_data = $this->common->getAll($apisql);
		if($ireport_order_data[0]['is_come'] == 0 && !empty($ireport_order_data[0]['ireport_order_id'])){
			$send_data['order_id'] = $order_id;
			$send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];

			$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type in(3)';
			$api_order_remark_data  = $this->common->getAll($apisql);
			$api_order_remark_str = '';
			foreach($api_order_remark_data as $api_order_remark_data_t){
				if(empty($api_order_remark_str)){
					$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}else{
					$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}
			}
			$send_data['visite_times'] = count($api_order_remark_data);
			$send_data['visite_bycheck_desc'] = $api_order_remark_str;
			$apisql = 'select max(mark_time) as mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type = 3';
			$send_data['visite_datetime'] = $this->common->getOne($apisql);
			$send_data['visite_bycheck'] =0;
			$send_data['track_next_time'] =$ireport_order_data[0]['order_time'];
			$send_array[] =$send_data;
			//$this->sycn_order_data_to_ireport($send_array);

			//剔除仁爱数据推送
			$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
			if(!$order_hos_id){
				$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
			}

			if (!in_array('1',$order_hos_id)) {
				$this->sycn_order_data_to_ireport($send_array);
			}

		}
	}
	/***
	 * 推送或者更新订单数据到数据中心
	 * */
	private function send_order_shujuzhognxin($order_id){
		$send_array = array();
		$send_data = array();

		$apisql = 'select * from '.$this->common->table('order')." where order_id  = '".$order_id."'";
		$ireport_order_data = $this->common->getAll($apisql);
		$send_data['gonghai_status'] =0;
		if(count($ireport_order_data) ==0 ){
			$apisql = 'select * from '.$this->common->table('gonghai_order')." where order_id  = '".$order_id."'";
			$ireport_order_data = $this->common->getAll($apisql);
			$send_data['gonghai_status'] =1;
		}
		$send_data['order_id'] = $ireport_order_data[0]['order_id'];
		if(empty($ireport_order_data[0]['is_first'])){
			$ireport_order_data[0]['is_first'] =1;
		}else{
			$ireport_order_data[0]['is_first'] =0;
		}
		$send_data['yuyue_type'] = $ireport_order_data[0]['is_first'];
		$apisql = 'select * from '.$this->common->table('patient')." where pat_id  = '".$ireport_order_data[0]['pat_id']."'";
		$ireport_order_patient_data = $this->common->getAll($apisql);

		$send_data['ireport_order_id'] =$ireport_order_data[0]['ireport_order_id'];
		$send_data['operation_type'] = 'edit';
		if(empty($send_data['ireport_order_id'])){
			$send_data['operation_type'] = 'add';
			unset($send_data['ireport_order_id']);
		}
		$send_data['add_time'] = $ireport_order_data[0]['order_addtime'];

		$send_data['order_no'] = $ireport_order_data[0]['order_no'];
		$apisql = 'select ireport_hos_id from '.$this->common->table('keshi')." where keshi_id  = '".$ireport_order_data[0]['keshi_id']."'";
		$send_data['id_hospital'] = $this->common->getone($apisql);

		$apisql = 'select ireport_admin_id,admin_name from '.$this->common->table('admin')." where admin_id  = '".$ireport_order_data[0]['admin_id']."'";
		$api_admin_data = $this->common->getAll($apisql);

		$send_data['id_consult'] = $api_admin_data[0]['ireport_admin_id'];
		$send_data['consult_name'] = $api_admin_data[0]['admin_name'];
		$send_data['date_day'] = $ireport_order_data[0]['order_time'];
		$disease= '';
		if(empty($ireport_order_data[0]['jb_id'])){
			$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_parent_id']."'";
			$api_jibing_data  = $this->common->getone($apisql);
			if(!empty($api_jibing_data)){
				$disease= $api_jibing_data;
			}
		}else if(!empty($ireport_order_data[0]['jb_id'])){
			$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_parent_id']."'";
			$api_jibing_data  = $this->common->getone($apisql);
			if(!empty($api_jibing_data)){
				$disease= $api_jibing_data;
			}

			$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_id']."'";
			$api_jibing_data  = $this->common->getone($apisql);
			if(!empty($api_jibing_data)){
				$disease= $disease.' '.$api_jibing_data;
			}
		}
		$send_data['disease'] = $disease;

		$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type != 3';
		$api_order_remark_data  = $this->common->getAll($apisql);
		$api_order_remark_str = '';
		foreach($api_order_remark_data as $api_order_remark_data_t){
			if(empty($api_order_remark_str)){
				$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
			}else{
				$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
			}
		}
		$send_data['memo'] = $api_order_remark_str;
		//到诊状态
		if(!empty($ireport_order_data[0]['is_come'])){
			$send_data['visited']  =  1;
			$send_data['visite_date']  =  $ireport_order_data[0]['come_time'];
			$send_data['doctor']  =  $ireport_order_data[0]['doctor_name'];
		}
		$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type = 3';
		$api_order_remark_data  = $this->common->getAll($apisql);
		$api_order_remark_str = '';
		foreach($api_order_remark_data as $api_order_remark_data_t){
			if(empty($api_order_remark_str)){
				$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
			}else{
				$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
			}
		}
		$send_data['visite_times'] = count($api_order_remark_data);
		$send_data['visite_bycheck_desc'] = $api_order_remark_str;
		$apisql = 'select max(mark_time) as mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type in(3)';
		$send_data['visite_datetime'] = $this->common->getOne($apisql);
		$send_data['visite_bycheck'] =0;
		$send_data['track_next_time'] =$ireport_order_data[0]['order_time'];

		$send_data['yuyue_time_duan'] = $ireport_order_data[0]['order_time_duan'];
		$send_data['yuyue_time_type'] = $ireport_order_data[0]['duan_confirm'];

		$send_data['dialogue_record'] = '';

		$apisql = 'select con_content from '.$this->common->table('ask_content').' where order_id = '.$ireport_order_data[0]['order_id'];
		$api_ask_content_data  = $this->common->getAll($apisql);
		if(count($api_ask_content_data) > 0){
			$send_data['dialogue_record'] = $api_ask_content_data[0]['con_content'];
		}

		//添加来源网址
		$apisql = 'select form,guanjianzi from '.$this->common->table('order_laiyuanweb').' where order_id = '.$ireport_order_data[0]['order_id'];
		$api_form_data  = $this->common->getAll($apisql);
		$send_data['keywords'] = '';
		if(count($api_form_data) > 0){
			$send_data['keywords'] = mb_substr($api_form_data[0]['guanjianzi'],0,90,'utf-8');
		}

		$send_data['source_url'] = '';
		if(!empty($ireport_order_data[0]['keshiurl_id'])){
			$apisql = 'select url from '.$this->common->table('keshiurl').' where id = '.$ireport_order_data[0]['keshiurl_id'];
			$api_keshiurl_data  = $this->common->getAll($apisql);
			if(count($api_keshiurl_data) > 0){
				$send_data['source_url'] = $api_keshiurl_data[0]['url'];
			}
		}else{
			if(count($api_form_data) > 0){
				$send_data['source_url'] = $api_form_data[0]['form'];
			}
		}
		//末次月经查询
		$send_data['last_menstrual_period']= '';
		$apisql = 'select data_time from '.$this->common->table('order_data').' where order_id = '.$ireport_order_data[0]['order_id'];
		$data_time_data  = $this->common->getAll($apisql);
		if(count($data_time_data) > 0){
			$send_data['last_menstrual_period'] = $data_time_data[0]['data_time'];
		}

		$send_data['patient_name'] = $ireport_order_patient_data[0]['pat_name'];
		$send_data['age'] = $ireport_order_patient_data[0]['pat_age'];


		$send_data['order_type'] = '0';
		$apisql = 'select ireport_order_type_id from '.$this->common->table('order_type').' where type_id = '.$ireport_order_data[0]['type_id'];
		$api_order_type_data  = $this->common->getAll($apisql);
		if(count($api_order_type_data) > 0){
			if(!empty( $api_order_type_data[0]['ireport_order_type_id'])){
				$send_data['order_type'] = $api_order_type_data[0]['ireport_order_type_id'];
			}
		}

		$send_data['order_from'] = '0';
		$send_data['order_from_two'] = '0';
		$send_data['from_value'] = $ireport_order_data[0]['from_value'];
		$apisql = 'select ireport_order_from_id from '.$this->common->table('order_from').' where from_id = '.$ireport_order_data[0]['from_parent_id'];
		$api_order_from_1_data  = $this->common->getAll($apisql);
		if(count($api_order_from_1_data) > 0){
			if(!empty( $api_order_from_1_data[0]['ireport_order_from_id'])){
				$send_data['order_from'] = $api_order_from_1_data[0]['ireport_order_from_id'];
			}
		}
		$apisql = 'select ireport_order_from_id from '.$this->common->table('order_from').' where from_id = '.$ireport_order_data[0]['from_id'];
		$api_order_from_2_data  = $this->common->getAll($apisql);
		if(count($api_order_from_2_data) > 0){
			if(!empty( $api_order_from_2_data[0]['ireport_order_from_id'])){
				$send_data['order_from_two'] = $api_order_from_2_data[0]['ireport_order_from_id'];
			}
		}

		$apisql = 'select region_id,region_name from '.$this->common->table('region');
		$api_region_data  = $this->common->getAll($apisql);
		$area = '';
		foreach ($api_region_data as $api_region_data_s){
			if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_province']){
				$area = $api_region_data_s['region_name'] ;
			}
			if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_city']){
				$area = $area.' '.$api_region_data_s['region_name'] ;
			}
			if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_area']){
				$area = $area.' '.$api_region_data_s['region_name'] ;
			}
		}
		$send_data['area'] = $area.' '.$ireport_order_patient_data[0]['pat_address'];
		$send_data['phone'] = $ireport_order_patient_data[0]['pat_phone'];
		$send_data['email'] = '';
		if($ireport_order_patient_data[0]['pat_sex'] == 2){
			$send_data['sex'] = '女';
		}else{
			$send_data['sex'] = '男';
		}
		$send_array[] = $send_data;
		//$this->sycn_order_data_to_ireport($send_array);

		//剔除仁爱数据推送
		$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
		if(!$order_hos_id){
			$order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
		}

		if (!in_array('1',$order_hos_id)) {
			$this->sycn_order_data_to_ireport($send_array);
		}

	}

	/****
	 * 订单添加或者更新
	*/
	private function sycn_order_data_to_ireport($parm){

		//if($_COOKIE['l_admin_id'] == 849 or  $_COOKIE['l_admin_id'] == 1938  or $_COOKIE['l_admin_id'] == 879){

		header("Content-type: text/html; charset=utf-8");
		$info = array();
		foreach ($parm as $temps){
			$info_temp = array();
			$info_temp['operation_type'] = $temps['operation_type'];
			if(empty($temps['ireport_order_id'])){
				$temps['ireport_order_id'] = '';
			}
			if($temps['operation_type'] == 'add' ){
				$info_temp['order_no'] =  $temps['order_no'];
				$info_temp['id_hospital'] = $temps['id_hospital'];
				$info_temp['id_consult'] = $temps['id_consult'];
				$info_temp['consult_name'] = $temps['consult_name'];
				$info_temp['date_day'] = $temps['date_day'];
				$info_temp['disease'] = $temps['disease'];
				$info_temp['memo'] = $temps['memo'];
				$info_temp['add_time'] = $temps['add_time'];
				$info_temp['booking_period'] = '';
				$info_temp['dialogue_record'] = base64_encode($temps['dialogue_record']);
				$info_temp['source_url'] = $temps['source_url'];
					$info_temp['keywords'] = $temps['keywords'];
				$info_temp['last_menstrual_period'] = $temps['last_menstrual_period'];
				$info_temp['patient_name'] = $temps['patient_name'];
				$info_temp['age'] = $temps['age'];
				$info_temp['area'] = $temps['area'];
				$info_temp['phone'] = $temps['phone'];
				$info_temp['email'] = $temps['email'];
				$info_temp['sex'] = $temps['sex'];
				$info_temp['visite_times']=$temps['visite_times'];
				$info_temp['visite_datetime']=$temps['visite_datetime'];
				$info_temp['visite_bycheck_desc']=$temps['visite_bycheck_desc'];
				$info_temp['visite_bycheck']= $temps['visite_bycheck'];
				$info_temp['track_next_time']=$temps['track_next_time'];
				$info_temp['order_type']=$temps['order_type'];
				$info_temp['order_from']=$temps['order_from'];
				$info_temp['order_from_two']=$temps['order_from_two'];
				$info_temp['from_value']=$temps['from_value'];
				$info_temp['gonghai_status'] = 0;
				$info_temp['yuyue_type'] = $temps['yuyue_type'];
				if(!empty($temps['visite_date'])){
					$info_temp['visite_date'] =  $temps['visite_date'];
				}
				if(!empty($temps['doctor'])){
					$info_temp['doctor'] =  $temps['doctor'];
				}
				if(!empty($temps['visited'])){
					$info_temp['visited'] =  $temps['visited'];
				}
			}else if($temps['operation_type'] == 'edit' ){
				$info_temp['id'] =  $temps['ireport_order_id'];
				$info_temp['order_no'] =  $temps['order_no'];
				$info_temp['id_hospital'] = $temps['id_hospital'];
				$info_temp['id_consult'] = $temps['id_consult'];
				$info_temp['consult_name'] = $temps['consult_name'];
				$info_temp['date_day'] = $temps['date_day'];
				$info_temp['disease'] = $temps['disease'];
				$info_temp['memo'] = $temps['memo'];
				$info_temp['add_time'] = $temps['add_time'];
				$info_temp['booking_period'] = '';
				$info_temp['dialogue_record'] = base64_encode($temps['dialogue_record']);
				$info_temp['source_url'] = $temps['source_url'];
				$info_temp['keywords'] = $temps['keywords'];
				$info_temp['last_menstrual_period'] = $temps['last_menstrual_period'];
				$info_temp['patient_name'] = $temps['patient_name'];
				$info_temp['age'] = $temps['age'];
				$info_temp['area'] = $temps['area'];
				$info_temp['phone'] = $temps['phone'];
				$info_temp['email'] = $temps['email'];
				$info_temp['sex'] = $temps['sex'];
				$info_temp['order_type']=$temps['order_type'];
				$info_temp['order_from']=$temps['order_from'];
				$info_temp['order_from_two']=$temps['order_from_two'];
				$info_temp['from_value']=$temps['from_value'];
				$info_temp['gonghai_status'] = $temps['gonghai_status'];;
				$info_temp['yuyue_type'] = $temps['yuyue_type'];
				if(!empty($temps['visite_date'])){
					$info_temp['visite_date'] =  $temps['visite_date'];
				}
				if(!empty($temps['doctor'])){
					$info_temp['doctor'] =  $temps['doctor'];
				}
				if(!empty($temps['visited'])){
					$info_temp['visited'] =  $temps['visited'];
				}
			}else if($temps['operation_type'] == 'huifang' ){
				$info_temp['id'] =  $temps['ireport_order_id'];
				$info_temp['visite_times']=$temps['visite_times'];
				$info_temp['visite_datetime']=$temps['visite_datetime'];
				$info_temp['visite_bycheck_desc']=$temps['visite_bycheck_desc'];
				$info_temp['visite_bycheck']= $temps['visite_bycheck'];
				$info_temp['track_next_time']=$temps['track_next_time'];
			}else if($temps['operation_type'] == 'daozhen' ){
				$info_temp['id'] =  $temps['ireport_order_id'];
				$info_temp['visited'] =  $temps['visited'];
				$info_temp['visite_date'] =  $temps['visite_date'];
				$info_temp['doctor'] =  $temps['doctor'];
				$info_temp['memo'] =$temps['memo'];
			}else if($temps['operation_type'] == 'editType' ){
				$info_temp['id'] =  $temps['ireport_order_id'];
				$info_temp['gonghai_status'] = $temps['gonghai_status'];
				$info_temp['yuyue_type'] = $temps['yuyue_type'];
			}else if($temps['operation_type'] == 'quxiao_or_huifu' ){
				$info_temp['id'] =  $temps['ireport_order_id'];
				if(empty( $temps['order_out'])){
					$temps['order_out'] = 1;
				}
				$info_temp['order_out'] =  $temps['order_out'];
			}
			$info_temp['renai_id'] = $temps['order_id'];
			$info[] = $info_temp;
		}
		$info=  json_encode($info);

		$url = $this->config->item('ireport_url_add_order');
		if(!empty($url)){
			require_once(BASEPATH."/core/Decryption.php");

			$decryption = new Decryption();
			$mu_str= $this->config->item('renai_mu_word');
			$gong_str= $this->config->item('renai_mu_number');
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			$encryption_validate = $decryption->encryption($str,$key_user);
			$encryption_data = $decryption->encryption($info,$key_user);

			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$output = curl_exec($ch);
			curl_close($ch);
			//var_dump($output);exit;

			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='订单添加或者更新';
			$api_request['apply_name']='订单';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;

			$output_json = json_decode($output,true);
			if(!empty($output_json)){
				$output_data = json_decode($output_json['msg'],true);;
				$add_empty_status =$output_data['add_empty_status'];
				$add_exits_status  =$output_data['add_exits_status'];
				$add_add_ok_status  =$output_data['add_add_ok_status'];
				$add_add_error_status  =$output_data['add_add_error_status'];
				$update_ok_status  =$output_data['update_ok_status'];
				$update_error_status  =$output_data['update_error_status'];

				if(strcmp($output_json['code'],'info_empty') ==0 ){
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'decryption_error') ==0 ){
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'ok') ==0 ){
					$api_request['apply_status']='1';
					if(count($add_add_ok_status) > 0){
						foreach ($add_add_ok_status as $temps){
							$update = array();
							$update['ireport_order_id'] = $temps['active_id'];
							if($temps['operation_type'] == 'add' ){
								$update['ireport_msg'] = '绑定成功';
							}else if($temps['operation_type'] == 'edit' ){
								$update['ireport_msg'] = '更新成功';
							}else if($temps['operation_type'] == 'huifang' ){
								$update['ireport_msg'] = '回访成功';
							}else if($temps['operation_type'] == 'daozhen' ){
								$update['ireport_msg'] = '到诊完成';
							}
							$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
							//同时更新预约和公海
							$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));

						}
					}else if(count($update_ok_status) > 0){
						foreach ($update_ok_status as $temps){
							$update = array();
							$update['ireport_order_id'] = $temps['active_id'];
							if($temps['operation_type'] == 'add' ){
								$update['ireport_msg'] = '更新成功';
							}else if($temps['operation_type'] == 'edit' ){
								$update['ireport_msg'] = '修改成功';
							}else if($temps['operation_type'] == 'huifang' ){
								$update['ireport_msg'] = '回访成功';
							}else if($temps['operation_type'] == 'daozhen' ){
								$update['ireport_msg'] = '到诊完成';
							}
							$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
							//同时更新预约和公海
							$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));

						}
					}else{
						if(!empty($add_empty_status)){
							foreach ($add_empty_status as $temps){
								$update = array();
								$update['ireport_msg'] = '存在空值';
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								//同时更新预约和公海
								$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));

							}
						}else if(!empty($add_exits_status)){
							foreach ($add_exits_status as $temps){
								$update = array();
								$update['ireport_msg'] = '存在相同的数据';
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								//同时更新预约和公海
								$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));

							}
						}else if(!empty($add_add_error_status)){
							foreach ($add_add_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '添加失败';
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								//同时更新预约和公海
								$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));
							}
						}else if(!empty($update_error_status)){
							foreach ($update_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '更新失败';
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								//同时更新预约和公海
								$this->db->update($this->db->dbprefix . "gonghai_order", $update, array('order_id' => $temps['renai_id']));
							}
						}
					}
				}else{
					$output_json['msg'] = json_encode($output);
					$api_request['apply_status']='1';
				}
			}else{
				$output_json['msg'] = json_encode($output);
				$api_request['apply_status']='1';
			}
			$api_request['response_msg']=$output_json['msg'];
			$api_request['response_code']=$output_json['code'];
			$api_request['response_data']=$output;
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}  else{
			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='订单添加或者更新';
			$api_request['apply_name']='订单';
			$api_request['apply_name']='';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;
			$api_request['apply_status']=2;
			$api_request['response_msg']='接口异常';
			$api_request['response_code']='';
			$api_request['response_data']='';
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}
		//}
	}
}