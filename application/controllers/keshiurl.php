<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type: text/html; charset=utf-8");

// 首页入口
class Keshiurl extends CI_Controller
{
	var $model;//定义模型文件
	public function __construct()
	{
		parent::__construct();  
		$this->load->model('Order_model');
	}
	 
	public function lists(){
		$data = array();  
		$data = $this->common->config('keshiurl_list');
		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		
		$where='1 = 1';
		$hos_id=$_REQUEST['hos_id'];
		if($hos_id == null){
			$hos_id = $_REQUEST['hos_id'];
		}
		if(!empty($hos_id)){
			$data['hos_id'] = $hos_id;
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
				if (in_array($hos_id, $l_hos_id)){
					$data['hos_id'] = $hos_id;
					$where .=  " and hos_id = '".$hos_id."'";
				}
			}else{
				$data['hos_id'] = $hos_id;
				$where .=  " and hos_id = '".$hos_id."'";
			}
		}else if(strcmp($_COOKIE["l_admin_action"],'all') !=0){
			$where .=  " and hos_id in(".$_COOKIE["l_hos_id"].")";		
		} 
		$keshi_id=$_REQUEST['keshi_id'];
		if($keshi_id == null){
			$keshi_id = $_REQUEST['keshi_id'];
		} 
		if(!empty($keshi_id)){
			$data['keshi_id'] = $keshi_id;
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
				if (in_array($keshi_id, $l_keshi_id)){
					$data['keshi_id'] = $keshi_id; 
					$where .=  " and keshi_id = '".$keshi_id."'";
				}
			}else{
				$data['keshi_id'] = $keshi_id;
				$where .=  " and keshi_id = '".$keshi_id."'";
			}
		}else if(strcmp($_COOKIE["l_admin_action"],'all') !=0){			
			$where .=  " and keshi_id in(".$_COOKIE["l_keshi_id"].")";		
		} 
		 
		$status=$_REQUEST['status'];
		if($status == null){
			$status = $_REQUEST['status'];
		}
		if(!empty($status)){
			$data['status'] = $status; 
			$where .=  " and status = '".$status."'";
		} 
		$text=$_REQUEST['text'];
		if($text == null){
			$text = $_REQUEST['text'];
		}
		if(!empty($text)){
			$data['text'] = $text;
			$where .=  " and  ( url like '%".$text."%' or title like '%".$text."%' ) ";
		} 
		$config = page_config();
		$config['base_url'] = '?c=keshiurl&m=lists&hos_id='.$hos_id.'&hos_id='.$hos_id.'&keshi_id='.$keshi_id.'&status='.$status; 
		$config['total_rows'] = $this->common->getOne("SELECT count(id) as id FROM " . $this->common->table('keshiurl') . " WHERE ".$where);
		$config['per_page'] = '30';
		$config['uri_segment'] = 30;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();  
        $data['list_arr'] = $this->common->getAll("SELECT * FROM " . $this->common->table('keshiurl') . " WHERE ".$where." order by id desc limit ".$page.",".$config['uri_segment']);    
        $data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		
		$data['hos_data'] = $this->Order_model->hospital_order_list();
		if(!empty($hos_id)){
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE hos_id =".$hos_id." and  keshi_id  in(".$_COOKIE["l_keshi_id"].")  ORDER BY CONVERT(keshi_name USING gbk) asc ";
			}else{
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE hos_id =".$hos_id."  ORDER BY CONVERT(keshi_name USING gbk) asc ";
			}
			$data['keshi_data'] = $this->common->getAll($sql);
		}  
		$this->load->view('keshiurl/list', $data);
	}   
	 
	public function del()
	{
		$this->common->config('keshiurllist');
		$id = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		if(empty($id))
		{
			echo 0;exit;
		}
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$sql ="SELECT id FROM " . $this->common->table('keshiurl') . " WHERE id = $id and keshi_id in(".$_COOKIE["l_keshi_id"].")  LIMIT 1";
		}else{
			$sql ="SELECT id FROM " . $this->common->table('keshiurl') . " WHERE id = $id LIMIT 1"; 
		} 
		$hav_child = $this->common->getOne($sql);
		if(empty($hav_child))
		{
			echo 0;exit;
		}
		$this->db->delete($this->common->table('keshiurl'), array('id' => $id)); 
		if($this->db->affected_rows() > 0){
			echo 1;exit;
		} else{
			echo 0;exit;
		} 
	}

	public function info()
	{
		$data = array();
		$data = $this->common->config('keshiurl_add');
		$data['hos_data'] = $this->Order_model->hospital_order_list();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$data['hos_data'] = $this->Order_model->hospital_order_list();
		$this->load->view('keshiurl/info', $data);
	}
	
	
	public function add()
	{
	
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0; 
		$keshi_id          = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0; 
		$links= array();
		$links[0] = array('href' => '?c=keshiurl&m=lists', 'text' => $this->lang->line('list_back'));
  
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
			if (!in_array($hos_id, $l_hos_id)){
				$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
				if (!in_array($keshi_id, $l_keshi_id)){
					$this->common->msg("添加失败,没有当前科室的操作权限", 0, $links);
				}
			}else{
				$this->common->msg("添加失败,没有当前医院的操作权限", 0, $links);
			}
		} 
	 
		$title        = trim($_REQUEST['title']);
		$url        = trim($_REQUEST['url']);
		if(empty($hos_id) || empty($hos_id) || empty($url))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$arr = array(
				'hos_id' => $hos_id,
				'keshi_id' => $keshi_id,
				'title' => $title,
				'url' => $url);
		$this->common->config('keshiurl_add');
		if($this->db->insert($this->common->table("keshiurl"), $arr)){
			$links[0] = array('href' => '?c=keshiurl&m=lists', 'text' => $this->lang->line('list_back'));
			$this->common->msg($this->lang->line('success'), 0, $links);
		}else{
			$links[0] = array('href' => '?c=keshiurl&m=lists', 'text' => $this->lang->line('list_back'));
			$this->common->msg("添加失败", 0, $links);
		}
		
		
	}
	 
	public function update_status()
	{ 
		$id          = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		$status          = isset($_REQUEST['status'])? intval($_REQUEST['status']):0;
		if(empty($id) || empty($status)){
			echo 0;exit;
		} 
		$this->common->config('keshiurl_add'); 
		$arr = array();
		$arr['status'] = $status; 
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$sql = "SELECT  keshi_id FROM " . $this->common->table('keshiurl') . " WHERE id =  ".$id;
			$keshiurl = $this->common->getAll($sql);
			if(count($keshiurl) > 0){
				$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]); 
				if (in_array($keshiurl[0]['keshi_id'], $l_keshi_id)){
					$this->db->update($this->common->table("keshiurl"), $arr, array('id' => $id));
				}else{
					echo 0;exit;
				}
			}else{
				echo 0;exit;
			}
		}else{
			$this->db->update($this->common->table("keshiurl"), $arr, array('id' => $id));
		}
		if($this->db->affected_rows() > 0){
			echo 1;exit;
		} else{
			echo 0;exit;
		}
	}
	
	public function get_html(){
		$hos_id =$_REQUEST['hos_id'];
		if(EMPTY($hos_id)){
			ECHO '';exit;
		}
		$keshi_id =$_REQUEST['keshi_id'];
		if(EMPTY($keshi_id)){
			$keshi_id  =0;
		}
		
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			if(empty($keshi_id)){
				$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
				if (in_array($hos_id, $l_hos_id)){
					$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =".$hos_id." and ( keshi_id =  0 or  keshi_id in(".$_COOKIE["l_keshi_id"].")) and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
				}else{
					ECHO '';exit;
				}
			}else{
				$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
				if (in_array($keshi_id, $l_keshi_id)){
					$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE keshi_id =  ".$keshi_id." and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
				}else{
					ECHO '';exit;
				}
			}
		}else{
			if(empty($keshi_id)){
				$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =  ".$hos_id." and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
			}else{
				$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE keshi_id =  ".$keshi_id." and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
			}
		}
		$keshiurl = $this->common->getAll($sql);
		$html = '<option value="0">请选择</option>';
		foreach ($keshiurl as $keshiurl_temp){
			$html .= '<option value="'.$keshiurl_temp['id'].'">'.$keshiurl_temp['url'].'-'.$keshiurl_temp['title'].'</option>';
		}
		echo $html;exit;
	} 
}