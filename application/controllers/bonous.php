<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");

// 首页入口
class Bonous extends CI_Controller
{
	var $model;//定义模型文件
	public function __construct()
	{
		parent::__construct();  
	}
	 
	public function lists(){
			$data = array();  
		$data = $this->common->config('bonous_list');
		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		
		$where='1 = 1';
		$state=$_REQUEST['state'];
		if($state == null){
			$state = $_REQUEST['state'];
		}
		if(!empty($state)){
			$data['state'] = $state; 
			$where .=  " and state = '".$state."'";
		}
		$type=$_REQUEST['type'];
		if($type == null){
			$type = $_REQUEST['type'];
		}
		if(!empty($type)){
			$data['type'] = $type;
			 
			$where .=  " and mold = '".$type."'";
		}
		$mobile=$_REQUEST['mobile'];
		if($mobile == null){
			$mobile = '';
		}
		if(!empty($mobile)){
			$data['mobile'] = $mobile;
			$mobile = trim($mobile);
			$where .=  " and mobile = ".$mobile;
		}
		 
		//日期
		$date = $_REQUEST['date'];
		//判断日期是否为空
		if(!empty($date))//对查询的两个时间进行处理
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];
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
		if(!empty($data['start_date']) && !empty($data['end_date'])){
			$active_start=$data['start_date'].' 00:00:00';
			$active_end= $data['end_date'].' 23:59:59';
			$where .=  " and receive_time between '".$active_start."' and '".$active_end."'";
		}
		$admin_count = $this->common->getOne("SELECT count(id) as id FROM " . $this->common->table('bonous') . " WHERE ".$where);
		
		$config = page_config();
		$config['base_url'] = '?c=bonous&m=lists&date='.$_REQUEST['date'].'&type'.$type.'&mobile='.$mobile."&state=".$state;
		$config['total_rows'] = $admin_count;
		$config['per_page'] = '30';
		$config['uri_segment'] = 30;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
        $data['list_arr'] = $this->common->getAll("SELECT * FROM " . $this->common->table('bonous') . " WHERE ".$where." order by receive_time desc limit ".$page.",".$config['uri_segment']);
		foreach($data['list_arr'] as $key=>$temp){
			$data['list_arr'][$key]['remark_list'] = $this->common->getAll("SELECT * FROM " . $this->common->table('bonous_record') . " WHERE bonousid in(".$temp['id'].") order by add_time desc limit 0,3");
		}
		$data['amount'] = $this->common->getOne("SELECT sum(amount) FROM " . $this->common->table('bonous_bag_amount') . " WHERE date between '".$data['start_date']."' and '".$data['end_date']."'"); 
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		
		$this->load->view('bonous/list', $data); 
	}   
	
	public function list_log(){
		$data = array();
		$data = $this->common->config('bonous_list');
		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		
		$where='1 = 1'; 
		$bonousid=$_REQUEST['bonousid']; 
		if(!empty($bonousid)){
			$data['bonousid'] = $bonousid; 
			$where .=  " and bonousid = ".$bonousid;
		} 
		$admin_count = $this->common->getOne("SELECT count(id) as id FROM " . $this->common->table('bonous_record') . " WHERE ".$where);
			
		$config = page_config();
		$config['base_url'] = '?c=bonous&m=list_log&bonousid='.$bonousid;
		$config['total_rows'] = $admin_count;
		$config['per_page'] = '30';
		$config['uri_segment'] = 30;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		
		$data['list_arr'] = $this->common->getAll("SELECT * FROM " . $this->common->table('bonous_record') . " WHERE ".$where." order by add_time desc limit ".$page.",".$config['uri_segment']);
	  
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		
		$this->load->view('bonous/list_log', $data);
		
	}
	 
	public function add_remark(){
		$data = array();
		$data = $this->common->config('bonous_list');
		$id=$_REQUEST['id'];
		$data['id'] = $id;
		if($_POST){
			//添加备注
			$remark=$_REQUEST['remark'];
			$add_time=date('Y-m-d H:i:s',strtotime('now')); 
			$this->db->insert($this->common->table('bonous_record'),array('bonousid'=>$id,"remark"=>$remark,"add_time"=>$add_time));
			 
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			
			if($this->db->affected_rows() > 0){
				$data['error'] = '添加成功';
				$this->load->view('bonous/remark', $data);
			}else{
				$data['remark'] = $remark;
				$data['error'] = '添加失败';
				$this->load->view('bonous/remark', $data);
			}
		}else{
		    
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('bonous/remark', $data);
		} 
	}
	
	//添加接口
	public function add(){
		$mold=$_REQUEST['mold'];
		$mold=trim($mold);
		$mobile=$_REQUEST['mobile'];
		$mobile=trim($mobile);
		$receive_time=date('Y-m-d H:i:s',strtotime('now'));
		if(empty($mold) || empty($mobile)){
			echo json_encode(array(status=>'no'));exit;
		}
		//手机号码归属地查询
		$url = "http://sj.apidata.cn/?mobile=".$mobile;                //api接口地址 
		$res = $this->request_get($url);
		$res_arr = json_decode($res,true);
		if($res_arr['status']=='1'){                                //如果成功获取数据
			$province = $res_arr['data']['province'];
			$city = $res_arr['data']['city'];
		}else{
			$province='';
			$city='';
		}
		$this->db->insert($this->common->table('bonous'),array('mold'=>$mold,'mobile'=>$mobile,'province'=>$province,'city'=>$city,'ip_address'=>$this->getRealIpAddr(),'receive_time'=>$receive_time));
		if($this->db->affected_rows() > 0){
			echo json_encode(array(status=>'ok'));exit;
		}else{
			echo json_encode(array(status=>'no'));exit;
		}
	}
	//红包点击数
	public function count(){
		$date=date('Y-m-d',strtotime('now'));
		
		$amount = $this->common->getOne("SELECT amount FROM " . $this->common->table('bonous_bag_amount') . " WHERE date ='".$date."'"); 
		if($amount && $amount > 0){
			$update =array();
			$update['amount'] = $amount+1;
			$this->db->update($this->common->table('bonous_bag_amount'), $update, array('date' => $date)); 
		}else{
			$this->db->insert($this->common->table('bonous_bag_amount'),array('amount'=>1,"date"=>$date)); 
		}
		if($this->db->affected_rows() > 0){
			echo json_encode(array(status=>'ok'));exit;
		}else{
			echo json_encode(array(status=>'no'));exit;
		}
	}
	
	
	
	/**
	 * 发送get请求
	 * @param string $url
	 * @return bool|mixed
	 */
	function request_get($url = '')
	{
		if (empty($url)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	//获得用户IP地址
	function getRealIpAddr(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){ //check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	
	public function update_state(){
		//首先更新状态
		$id=$_REQUEST['id'];if(empty($id)){exit(json_encode(300));}
		$state=$_REQUEST['state']; if(empty($state)){$state = 0;}  
		$update =array();
		$update['state'] = $state; 
		$this->db->update($this->common->table('bonous'), $update, array('id' => $id));
		if($this->db->affected_rows() > 0){
			exit(json_encode(200));
		}else{
			exit(json_encode(300));
		}
	}
}