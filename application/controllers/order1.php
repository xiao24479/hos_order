<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller
{
	var $model;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Order_model');
		$this->lang->load('order');
		$this->lang->load('common');
		$this->model = $this->Order_model;
	}
        //四维表格显示
        public function siwei_show(){
            $dat=array();
            $now_time=date("Y-m-d",time());
            
           $a=isset($_POST['siwei_time'])?$_POST['siwei_time']:$now_time;
            
            $dat=$this->common->config('siwei_show');
        $dat['siwei']=$this->model->siwei_list($a);
        $dat['top'] = $this->load->view('top', $dat, true);
	$dat['themes_color_select'] = $this->load->view('themes_color_select', '', true);
	$dat['sider_menu'] = $this->load->view('sider_menu', $dat, true);
        $this->load->view('siwei/order_siwei',$dat);
     
            
        }
        public function siwei_show_window(){
            $dat=array();
            $now_time=date("Y-m-d",time());
            
           $a=isset($_POST['siwei_time'])?$_POST['siwei_time']:$now_time;
            
            //$dat=$this->common->config('siwei_show');
        $dat['siwei']=$this->model->siwei_list($a);
        
        $this->load->view('siwei/siwei_show_window',$dat);
            
            
        }
        public function gonghai(){
            
            
            $this->load->view('siwei/gonghai');
        }
        
	public function search_ajax()
	{		
		$search = $_REQUEST['search_text'];
        $sql = 'select a.admin_name, a.admin_id, p.hos_id  from '. $this->common->table('admin') .' a
		left join ' . $this->common->table('rank') . ' r on a.rank_id = r.rank_id
		left join ' . $this->common->table('rank_power') . ' p on a.rank_id = p.rank_id
		where r.rank_type = 1';		
		$arr = $this->common->getAll($sql);
		$list = array();
		foreach($arr as $val){
			$list[$val['admin_id']] = $val;
		}
		
		if($_COOKIE['l_admin_id'] == 1||$_COOKIE['l_rank_id']==1){
			$sql = 'select hos_id from '. $this->common->table('hospital');
			$hos_arr = $this->common->getAll($sql);
			$hos = array();
			foreach($hos_arr as $val){
			
				$hos[] = $val['hos_id'];
			}
		}else{
			$hos = explode(',',$_COOKIE['l_hos_id']);
		}
		
		$res = array();
		
		foreach($list as $val){
			if(in_array($val['hos_id'],$hos)){
		
				if(strstr($val['admin_name'],$search)){
					$res[] = $val['admin_name'];
				}
			}
		}
		
		print_r(json_encode($res));
	
	}

	public function swt_talk()
	{
		$file_path = './static/swt/';
		$content = isset($_REQUEST['data'])? $_REQUEST['data']:'';
		$cid  = isset($_REQUEST['cid'])? $_REQUEST['cid']:'';
		$gid  = isset($_REQUEST['gid'])? $_REQUEST['gid']:'';
		$first = isset($_REQUEST['first'])? $_REQUEST['first']:1;
		$sid = isset($_REQUEST['sid'])? $_REQUEST['sid']:'DLT92296270';
		
		if(!empty($gid))
		{
			$gid = explode(".", $gid);
			$gid = $gid[count($gid) - 2] . "." . $gid[count($gid) - 1];
			$gid = sub_str($gid, 35);
		}
		
		$content = cut_html(urldecode($content));

		if($first == 1)
		{
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('swt') . " WHERE cid = '" . $cid . "' AND gid = '" . $gid . "'");
			if(empty($info))
			{
				$this->db->insert($this->common->table('swt'), array('cid' => $cid, 'gid' => $gid, 'sid' => $sid));
			}
		}

		$cid_url = substr($cid, 0, 3) . "/" . substr($cid, 3, 3) . "/" . substr($cid, 6, 4) . "/" . substr($cid, 10, 3) . "/" . substr($cid, 13) . "/";
		$file_path = $file_path . $cid_url;

		if(!file_exists($file_path))
		{
			mkdirs($file_path);
		}

		$cid_url_file = empty($gid)? 'index.php':$gid . ".php";
		$cache_file_path =  $file_path . $cid_url_file;
		$swt_list = array();
		$swt_info = array('asker' => 0, 'admin' => 0, 'asktime' => 0, 'last' => '');
		$add = 0;
		
		if(file_exists($cache_file_path))
		{
			include_once($cache_file_path);
       		$swt_list = $data['swt_list'];
        	$swt_info = $data['swt_info'];
		}
		
		preg_match_all("|<p><font\sclass=\"(.*)namefont\">(.*)</font></p>|U", $content, $user);
		$word = preg_split("|<p><font\sclass=\"(.*)namefont\">(.*)</font></p>|U", $content); // 分割对话，提取对话的内容，避免对话中出现div的错误
		//preg_match_all("|<div\sstyle=\"margin-left:5px\"\sclass=\"(.*)font\">(.*)</div>|U", $content, $word);		

		if(isset($user[2]))
		{
			if(!isset($swt_list[$user[2][count($user[2]) - 1]]))
			{
				$add = 1;
			}
			foreach($user[2] as $key => $val)
			{
				$swt_list[$user[2][$key]]['type'] = $user[1][$key];
				$swt_list[$user[2][$key]]['user'] = $user[2][$key];
				$swt_list[$user[2][$key]]['word'] = $word[$key + 1];
			}
			$swt_info['admin'] = 0;
			$swt_info['asker'] = 0;
			foreach($swt_list as $val)
			{
				if($val['type'] == 'operator')
				{
					$swt_info['admin'] ++;
				}
				else
				{
					$swt_info['asker'] ++;
				}
			}
			
			$swt_info['last'] = $user[1][count($user[1]) - 1];
			$start_time = $user[2][0];
			$end_time = $user[2][count($user[2]) - 1];			
			$start_time = substr($start_time, -14);
			$end_time = substr($end_time, -14);
			$start_time = strtotime(date("Y") . "-" . $start_time);
			$end_time = strtotime(date("Y") . "-" . $end_time);
			if($first == 1 || $add == 1)
			{
				$swt_info['asktime'] = $swt_info['asktime'] + ($end_time - $start_time);
			}

			$swt_data['swt_info'] = $swt_info;
			$swt_data['swt_list'] = $swt_list;		

			if(!file_exists($cache_file_path))
			{
				$fp = @fopen($cache_file_path, "a+");
				fwrite($fp, '1');
				fclose($fp);
			}
			$content = "<?php\r\n";
			$content .= "\$data = " . var_export($swt_data, true) . ";\r\n";
			$content .= "?>";
			file_put_contents($cache_file_path, $content, LOCK_EX);
		}
	}

	public function order_type()
	{
		$data = array();
		$data = $this->common->config('order_type');
		$type_list = $this->common->static_cache('read', "order_type", 'order_type');
		$data['type_list'] = $type_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('order_type', $data);
	}
	
	public function type_info()
	{
		$data = array();
		$type_id = empty($_REQUEST['type_id'])? 0:intval($_REQUEST['type_id']);
		if(empty($type_id))
		{
			$data = $this->common->config('type_add');
		}
		else
		{
			$data = $this->common->config('type_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('order_type') ." WHERE type_id = $type_id");
			$data['info'] = $info;
		}
		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY hos_id ASC");
		$data['hospital'] = $hospital;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('type_info', $data);
	}

	public function type_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$type_id         = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;
		$type_name       = trim($_REQUEST['type_name']);
		$hos_id          = trim($_REQUEST['hos_id']);
		$keshi_id        = trim($_REQUEST['keshi_id']);
		$type_desc       = trim($_REQUEST['type_desc']);
		$type_order      = trim($_REQUEST['type_order']);

		if(empty($type_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$arr = array('type_name' => $type_name,
					 'hos_id' => $hos_id,
					 'keshi_id' => $keshi_id,
					 'type_desc' => $type_desc,
					 'type_order' => $type_order);

		if($form_action == 'update')
		{
			if(empty($type_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('type_edit');
			$this->db->update($this->common->table("order_type"), $arr, array('type_id' => $type_id));
		}
		else
		{
			$this->common->config('type_add');
			$this->db->insert($this->common->table("order_type"), $arr);
			$type_id = $this->db->insert_id();
		}
		$this->common->static_cache('delete', "order_type");
		$links[0] = array('href' => '?c=order&m=type_info&type_id=' . $type_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=order&m=type_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=order&m=order_type', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function type_del()
	{
		$this->common->config('type_del');
		$type_id = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;
		if(empty($type_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->db->delete($this->common->table("order_type"), array('type_id' => $type_id));
		$this->common->static_cache('delete', "order_type");
		$links[0] = array('href' => '?c=order&m=order_type', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function order_from()
	{
		$data = array();
		$data = $this->common->config('order_from');
		$from_list = $this->model->from_list();
		$data['from_list'] = $from_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('order_from', $data);
	}

	public function from_info()
	{
		$data = array();
		$from_id = empty($_REQUEST['from_id'])? 0:intval($_REQUEST['from_id']);	
		if(empty($from_id))
		{
			$data = $this->common->config('order_from_add');
		}
		else
		{
			$data = $this->common->config('order_from_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('order_from') ." WHERE from_id = $from_id");
			$data['info'] = $info;
		}
		$from_list = $this->common->getAll("SELECT * FROM " . $this->common->table('order_from') . " WHERE parent_id = 0 ORDER BY from_order ASC, from_id DESC");
		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY hos_id ASC");
		$data['from_list'] = $from_list;
		$data['hospital'] = $hospital;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('from_info', $data);
	}

	public function from_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;
		$from_name       = trim($_REQUEST['from_name']);
		$hos_id          = trim($_REQUEST['hos_id']);
		$keshi_id        = trim($_REQUEST['keshi_id']);
		$parent_id       = trim($_REQUEST['parent_id']);
		$from_order      = trim($_REQUEST['from_order']);
		$is_show		 = $_REQUEST['is_show'];

		if(empty($from_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
	
		$arr = array('from_name' => $from_name,
					 'hos_id' => $hos_id,
					 'keshi_id' => $keshi_id,
					 'parent_id' => $parent_id,
					 'from_order' => $from_order,
					 'is_show'	=> $is_show);

		if($form_action == 'update')
		{
			if(empty($from_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('order_from_edit');			
			$this->db->update($this->common->table("order_from"), $arr, array('from_id' => $from_id));   
		}
		else
		{
			$this->common->config('order_from_add');
			$this->db->insert($this->common->table("order_from"), $arr);
			$from_id = $this->db->insert_id();
		}

		$links[0] = array('href' => '?c=order&m=from_info&from_id=' . $from_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=order&m=from_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=order&m=order_from', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function from_del()
	{
		$this->common->config('from_del');
		$from_id = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;
		if(empty($from_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->db->delete($this->common->table("order_from"), array('from_id' => $from_id));
		$this->common->static_cache('delete', "order_from");
		$links[0] = array('href' => '?c=order&m=order_from', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

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
			$data['info'] = $info;
			$remark = $this->model->order_remark($order_id);
			$data['remark'] = $remark;		
			$con_content = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");
			$data['con_content'] = $con_content;
			$order_data = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");
			$data['order_data'] = $order_data;
		}
		$data['p'] = $p;
		$from_list = $this->model->from_order_list();
		$hospital = $this->model->hospital_order_list();
		//$keshi = $this->model->keshi_order_list();
		$type_list = $this->model->type_order_list();
                //选择0或者1,0代表根据当前医院来获取相关的咨询员信息，1代表医院全部信息
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
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('order_info' . $type, $data);
	}
	//预约卡管理，定位坐标，文本替换
	public function order_card()
	{
		$data = array();
		$card_id = empty($_REQUEST['card_id'])? 0:intval($_REQUEST['card_id']);
		if(empty($card_id))
		{
			$data = $this->common->config('card_add');
		}
		else
		{	
			$data = $this->common->config('card_edit');		
			$info = $this->common->getRow('select * from '.$this->common->table('order_card').' where card_id = '.$card_id);		
			$card_content = $info['card_content'];	
			$tag = explode(',',$card_content);
			$list = array();
			foreach($tag as $val){
				$arr = array();
				if(strstr($val,'username')){
					$arr = explode('-',$val);
					$info['username'] = $arr[1].'-'.$arr[2];
				}else if(strstr($val,'age')){
					$arr = explode('-',$val);
					$info['age'] = $arr[1].'-'.$arr[2];
				}else if(strstr($val,'phone')){
					$arr = explode('-',$val);
					$info['phone'] = $arr[1].'-'.$arr[2];
				}else if(strstr($val,'jibing')){
					$arr = explode('-',$val);
					$info['jibing'] = $arr[1].'-'.$arr[2];
				}else if(strstr($val,'orderno')){
					$arr = explode('-',$val);
					$info['orderno'] = $arr[1].'-'.$arr[2];
				}else if(strstr($val,'ordertime')){
					$arr = explode('-',$val);
					$info['ordertime'] = $arr[1].'-'.$arr[2];
				}
			}		
			$data['info'] = $info;
		}
		// 获取医院信息
		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY hos_id ASC");	
		$data['hospital'] = $hospital;	
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('order_card', $data);
	}
	
	public function card_info()
	{
		$order_id = intval($_REQUEST['order_id']);
		$url = $this->common->getOne('select img from '.$this->common->table('card_data').' where order_id = '.$order_id);
		$type = trim($_REQUEST['type']);
		if($type=='reset'){	
			//删除图片
			$del = explode('/',$url);
			unlink ('static/images/'.$del[5]);
			$url = null;
		}
		if(!empty($url)){
			echo '<img src="'.$url.'">';
		}else{
			// 生成预约卡操作
			$res = $this->get_info_by_id($order_id);
			$hos_id = $res['hos_id'];
			$card_info = $this->get_card($hos_id);
			
			if(empty($card_info)){
				echo 1;
				exit();
			}
			if(empty($res['order_time'])){
				$order_time = '未定';
			}else{
				$order_time = date('Y年m月d日',$res['order_time']);
			}
			if(empty($res['pat_age'])){
				$res['pat_age'] = '未知';
			}
			$arr = array(
				'patient_name'	=>	$res['pat_name'],
				'patient_phone'	=>	$res['pat_phone'],
				'order_time'	=>	$order_time,
				'patient_age'	=>	$res['pat_age'],
				'jibing_parent'	=>	$res['jb_name'],
				'order_no'		=>	$res['order_no'],
			);
			$list = $this->card_info_arr($arr,$card_info['card_content']);		
			$url = $this->create_card($card_info['img'],$list);
			//关联预约卡
			if($url){
				if($type=='reset'){
					$this->db->update($this->common->table("card_data"),array('img' => $url),array('order_id' => $order_id));
				}else{
					$this->db->insert($this->common->table("card_data"),array('order_id' => $order_id,'img' => $url));
				}
				echo '<img src="'.$url.'">';
			}else{
				echo 2;
			}
		}
	}
	private function get_info_by_order_id($id)
	{		
		if(file_exists('static/images/ok.jpg')){
			unlink ('static/images/ok.jpg');
		}		
	}
	
	public function card_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$card_id         = isset($_REQUEST['card_id'])? intval($_REQUEST['card_id']):0;
		$card_name 		 = trim($_REQUEST['card_name']);
		$hos_id          = intval($_REQUEST['hos_id']);
		$keshi_id        = intval($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0;
		$card_content = '';
		$username = trim($_REQUEST['username']);
		if(!empty($username)){
			$card_content .= '{username}-'.$username;
		}
		$age = trim($_REQUEST['age']);
		if(!empty($age)){
			$card_content .= ',{age}-'.$age;
		}
		$phone = trim($_REQUEST['phone']);
		if(!empty($phone)){
			$card_content .= ',{phone}-'.$phone;
		}
		$jibing = trim($_REQUEST['jibing']);
		if(!empty($jibing)){
			$card_content .= ',{jibing}-'.$jibing;
		}
		$ordertime = trim($_REQUEST['ordertime']);
		if(!empty($ordertime)){
			$card_content .= ',{ordertime}-'.$ordertime;
		}
		$orderno = trim($_REQUEST['orderno']);
		if(!empty($orderno)){
			$card_content .= ',{orderno}-'.$orderno;
		}
		$img    		 = trim($_REQUEST['img']);
		if(empty($card_name)){
			$this->common->msg('预约卡名称不能为空');
		}
		if(empty($hos_id)){
			$this->common->msg('请选择医院');
		}
		if(empty($card_content)){
			$this->common->msg('预设信息不能为空');
		}
		if(empty($img)){
			$this->common->msg('背景图不能为空');
		}
		$arr_card = array(
			'card_name'	=> $card_name,
			'hos_id'	=> $hos_id,
			'keshi_id'	=> $keshi_id,
			'card_content'	=> $card_content,
			'img'	=> $img,
		);
		
		if($form_action == 'update'){
		
			if(empty($card_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('card_edit');
			
			$this->db->update($this->common->table("order_card"), $arr_card, array('card_id' => $card_id));
		
		}else{
			//添加预约卡操作
			$this->common->config('card_add');
			// 检测当前科室下是否有预约卡
			$has = $this->common->getOne('select card_id from '.$this->common->table('order_card').' where hos_id = '.$hos_id);
			if(empty($has)){
				$this->db->insert($this->common->table("order_card"), $arr_card);
				$card_id = $this->db->insert_id();
			}else{
				$card_id = $has;
				$this->db->update($this->common->table("order_card"), $arr_card, array('card_id' => $card_id));
			}
		}
		$links[0] = array('href' => '?c=order&m=order_card&card_id=' . $card_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=order&m=order_card', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=order&m=card_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);	
	}
	
	public function card_list()
	{
		$data = array();
		$data = $this->common->config('card_list');
		$sql = 'select o.card_id,o.card_name,k.keshi_name,h.hos_name from '.$this->common->table('order_card').' o 
				left join '.$this->common->table('hospital').' h on h.hos_id = o.hos_id
				left join '.$this->common->table('keshi').' k on k.keshi_id = o.keshi_id';
		$list = $this->common->getAll($sql);
		$data['list'] = $list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('card_list', $data);
	}
	public function card_delete()
	{
		$this->common->config('card_delete');
		$card_id          = isset($_REQUEST['card_id'])? intval($_REQUEST['card_id']):0;
		$this->db->delete($this->common->table('order_card'), array('card_id' => $card_id));
	}
	public function card_set_ajax()
	{
		$hos_id = intval($_REQUEST['hos_id']);
		$res = $this->get_card($hos_id);
		if(empty($res)){
			echo 1;
		}else{
			$order_time = trim($_REQUEST['order_time']);
			if(empty($order_time)){
				$order_time = '未定';
			}else{
				$order_time = date('Y年m月d日',strtotime($order_time));
			}
			$order_time = date('Y年m月d日',strtotime($order_time));
			$arr = array(
				'patient_name'	=>	trim($_REQUEST['patient_name']),
				'patient_phone'	=>	trim($_REQUEST['patient_phone']),
				'order_time'	=>	$order_time,
				'patient_age'	=>	trim($_REQUEST['patient_age']),
				'jibing_parent'	=>	trim($_REQUEST['jibing_parent']),
				'order_no'		=>	trim($_REQUEST['order_no']),
			);
			$list = $this->card_info_arr($arr,$res['card_content']);
			$html = $this->create_card($res['img'],$list);
			if(!$html){
				echo 2;
			}else{
				$img = trim($_REQUEST['img']);
				if(!empty($img)){
					$src = explode('/',$img);
					$tmp = $src[5];
					if(file_exists('static/images/'.$tmp)){
						unlink('static/images/'.$tmp);
					}
				}
				echo $html;
			}
		}
	}
	private function get_card($hos_id,$keshi_id = null)
	{
		if(empty($keshi_id)){		
			$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id);
		}else{
			$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id.' and keshi_id = '.$keshi_id);
			if(empty($res)){
				$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id);
			}
		}
		return $res;
	}
	private function get_info_by_id($order_id)
	{
		$sql = 'select o.hos_id,o.keshi_id,o.order_time,o.order_no,j.jb_name,p.pat_name,p.pat_age,p.pat_phone
				from '.$this->common->table('order').' o 
				left join '.$this->common->table('patient').' p on o.pat_id = p.pat_id
				left join '.$this->common->table('jibing').' j on o.jb_parent_id = j.jb_id
				where order_id = '.$order_id;
		$res = $this->common->getRow($sql);
		return $res;
	}
	
	public function get_info_by_phone()
	{
		$pat_phone = trim($_REQUEST['pat_phone']);
		if(empty($pat_phone)){
			exit;
		}
		$sql = 'select o.hos_id,o.keshi_id,o.order_time,o.order_no,j.jb_name,p.pat_name,p.pat_age,p.pat_phone
				from '.$this->common->table('order').' o 
				left join '.$this->common->table('patient').' p on o.pat_id = p.pat_id
				left join '.$this->common->table('jibing').' j on o.jb_parent_id = j.jb_id
				where p.pat_phone = '.$pat_phone;
		$res = $this->common->getAll($sql);
		print_r(json_encode($res));
	}
	
	private function card_info_arr($arr,$string)
	{
		$replace = array('{username}', '{phone}', '{ordertime}', '{age}','{jibing}', '{orderno}');
		$value = array($arr['patient_name'], $arr['patient_phone'], $arr['order_time'], $arr['patient_age'], $arr['jibing_parent'],$arr['order_no']);
		$card_content = str_replace($replace, $value, $string);
		$tag = explode(',',$card_content);
		$list = array();
		foreach($tag as $key=>$val){
			$list[$key] = explode('-',$val);
		}
		return $list;
	}
	public function card_set()
	{
		$img_suo = trim($_REQUEST['img_suo']);
		if(!empty($img_suo)){
			$src = explode('/',$img_suo);
			$tmp = $src[5];
			if(file_exists('static/images/'.$tmp)){
				unlink('static/images/'.$tmp);
			}
		}
		$card_content = '';
		$username = trim($_REQUEST['username']);
		if(!empty($username)){
			$card_content .= '姓名位-'.$username;
		}
		$age = trim($_REQUEST['age']);
		if(!empty($age)){
			$card_content .= ',年龄位-'.$age;
		}
		$phone = trim($_REQUEST['phone']);
		if(!empty($phone)){
			$card_content .= ',手机位-'.$phone;
		}
		$jibing = trim($_REQUEST['jibing']);
		if(!empty($jibing)){
			$card_content .= ',疾病位-'.$jibing;
		}
		$ordertime = trim($_REQUEST['ordertime']);
		if(!empty($ordertime)){
			$card_content .= ',预约时间-'.$ordertime;
		}
		$orderno = trim($_REQUEST['orderno']);
		if(!empty($orderno)){
			$card_content .= ',预约号-'.$orderno;
		}
		$img = trim($_REQUEST['img']);
		$tag = explode(',',$card_content);
		$list = array();
		foreach($tag as $key=>$val){
			$list[$key] = explode('-',$val);
		}
		$html = $this->create_card($img,$list);
		echo $html;
	}
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
                if($_REQUEST['contact']=='phone'){
                    
                   $patient_phone=$_REQUEST['contact_input']; 
                }elseif($_REQUEST['contact']=='qq'){
                    $patient_qq=$_REQUEST['contact_input'];
                    
                }elseif($_REQUEST['contact']=='weixin'){
                    
                    $patient_weixin=$_REQUEST['contact_input'];
                }
                
                
                
		$pat_phone       = isset($patient_phone)?trim($patient_phone):'';
                $pat_qq          = isset($patient_qq)?trim($patient_qq):'';
                $pat_weixin          = isset($patient_weixin)?trim($patient_weixin):'';
		$pat_age         = trim($_REQUEST['patient_age']);
		$order_time      = trim($_REQUEST['order_time']);
		$order_time_duan_d = !empty($_REQUEST['order_time_duan_d'])? trim($_REQUEST['order_time_duan_d']):'';
		$order_time_duan_j = !empty($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';
		$duan_confirm      = intval($_REQUEST['duan_confirm']);
		$pat_province    = intval($_REQUEST['province']);
		$pat_city        = intval($_REQUEST['city']);
		$pat_area        = intval($_REQUEST['area']);
		$pat_address     = trim($_REQUEST['patient_address']);
		$con_content     = trim($_REQUEST['con_content']);
		$data_time       = trim($_REQUEST['data_time']);
		$data_time       = strtotime($data_time);
		$yunzhou         = intval($_REQUEST['yunzhou']);
		
		$order_time_duan = ($duan_confirm == 1)? $order_time_duan_d:$order_time_duan_j;
		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';
		$order_time_type = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

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
						 'pat_phone1' => isset($pat_phone[1])? $pat_phone[1]:'',
                                                 'pat_qq'=> $pat_qq,
                                                 'pat_weixin'=>$pat_weixin
                    );
                                                  

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
					   'duan_confirm' => $duan_confirm);
		
		if($order_time_type == 1)
		{
			$order['order_time'] = strtotime($order_time);
			$order['order_null_time'] = '';
		}
		elseif($order_time_type == 2)
		{
			$order['order_time'] = 0;
			$order['order_null_time'] = empty($order_null_time)? '未定':$order_null_time;
		}
		
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

			$this->db->update($this->common->table("patient"), $patient, array('pat_id' => $p_id));
			$this->db->update($this->common->table("order"), $order, array('order_id' => $order_id));		
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
			$this->db->insert($this->common->table("patient"), $patient);
			$pat_id = $this->db->insert_id();
			$order['pat_id'] = $pat_id;
			$order['order_addtime'] = time();
			$this->db->insert($this->common->table("order"), $order);
			$order_id = $this->db->insert_id();
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
		$msg_detail = $this->lang->line('success');
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
                    $time=time();
                    $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'更新预约','action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time));
			$links[0] = array('href' => '?c=order&m=order_info&order_id=' . $order_id . '&p=' . $p, 'text' => $this->lang->line('edit_back'));
			$links[1] = array('href' => '?c=order&m=order_info', 'text' => $this->lang->line('add_back'));
			$links[2] = array('href' => '?c=order&m=order_list', 'text' => $this->lang->line('list_back'));
			$this->common->msg($msg_detail, 0, $links, true, false,0);
		}
	}
	public function dean_list()
	{
		$data = array();
		$data           = $this->common->config('dean_list');
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$data['now_page'] = $page;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):20;
		$per_page = empty($per_page)? 20:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');	
		$where = 1;
		if(!empty($patient_name))
		{
			$where = " name like '%". $patient_name . "%'";
			$data['p_n']      = $patient_name;
			$data['p_p']      = "";
			$query[]='p_n='.$patient_name;
		}
		if(!empty($patient_phone))
		{
			$where = " phone = '". $patient_phone ."'" ;
			$data['p_n']      = "";
			$data['p_p']      = $patient_phone;
			$query[]='p_p='.$patient_phone;
		}
		$sql = 'select count(1) as count from '.$this->common->table('ask_dean').' where '.$where;
		$message_count = $this->common->getone($sql);	
		$config = page_config();
		$config['base_url'] = '?c=order&m=dean_list'.implode('&',$query);	
		$config['total_rows'] = $message_count;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);	
		$sql = 'select * from '.$this->common->table('ask_dean').' where '.$where.' limit '.$page.','.$per_page ;
		$message_list = $this->common->getAll($sql);
		$data['page'] = $this->pagination->create_links();
		$data['per_page'] = $per_page;
		$data['message_list'] = $message_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('dean_list', $data);
	}
	
	public function message_list()
	{
		$data = array();
		$data           = $this->common->config('message_list');
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$handle         = isset($_REQUEST['handle'])? intval($_REQUEST['handle']):0;
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';
		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;
		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;
		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;
		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):1;
		$data['t']  = $order_type;
		$data['hos_id']   = $hos_id;
		$data['p_n']      = $patient_name;
		$data['p_p']      = $patient_phone;
			
		if(!empty($date))
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];
			$data['start'] = $start;
			$data['end'] = $end;
			$start = str_replace(array("年", "月", "日"), "-", $start);
			$end = str_replace(array("年", "月", "日"), "-", $end);
			$start = substr($start, 0, -1);
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
		
		$hospital = $this->model->hospital_order_list();	
		$data['hospital'] = $hospital;	
	
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$data['now_page'] = $page;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;
		$per_page = empty($per_page)? 30:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');		
		/* 处理判断条件 */

		$where = 1;
		$orderby = '';	
		$query = array();
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
			$query[]='hos_id='.$hos_id;
		}		
		if($handle)
		{
			if($handle == 1){
				$where .= ' AND o.admin_id = 0';
			}else{
				$where .= ' AND o.admin_id > 0';
			}
			$data['handle'] = $handle;
			$query[]='handle='.$handle;
		}
		
		/* 订单状态 */	
		if(!empty($pro)&&is_numeric($pro)){
			$where .= ' AND  p.pat_province = '.$pro;
			$data['pro'] = $pro;
			$query[]='province='.$pro;
		}else{
			$data['pro'] = 0;
		}
		if(!empty($city)&&is_numeric($city)){
			$where .= ' AND  p.pat_city = '.$city;
			$data['city'] = $city;
			$query[]='city='.$city;
		}
		if(!empty($are)&&is_numeric($are)){
			$where .= ' AND  p.pat_area = '.$are;
			$data['are'] = $are;
			$query[]='area='.$are;
		}

		$w_start = strtotime($start . ' 00:00:00');
		$w_end = strtotime($end . ' 23:59:59');
		if($orderby == '')
		{
			$orderby = ' ORDER BY o.order_id DESC ';
		}
		/* 时间条件 */

		if($order_type == 1)
		{
			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			$orderby .= ',o.order_addtime DESC ';
			$query[]='t=1';
		}
		elseif($order_type == 2)
		{
			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			$orderby .= ',o.order_time DESC ';
			$query[]='t=2';
		}
		/* 当输入患者的信息时，其他的搜索条件都不需要了 */
		if(!empty($patient_name))
		{
			$where = " p.pat_name like '%". $patient_name . "%'";
			$data['p_n']      = $patient_name;
			$data['p_p']      = "";
			$query[]='p_n='.$patient_name;
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
		}
		if(!empty($patient_phone))
		{
			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";
			$data['p_n']      = "";
			$data['p_p']      = $patient_phone;
			$query[]='p_p='.$patient_phone;
			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
		}
		
		$message_count = $this->model->message_count($where);		
		$config = page_config();
		$config['base_url'] = '?c=order&m=message_list&'.implode('&',$query);	
		$config['total_rows'] = $message_count['count'];
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);	
		$message_list = $this->model->message_list($where, $page, $per_page, $orderby);	
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
		$area = $this->model->area();
		$province_list = array_merge($flag_1,$flag_2);
		$data['area'] = $area;
		$rank_type = $this->model->rank_type();
		$data['rank_type'] = $rank_type;
		$jibing = read_static_cache('tag_list');
		$jibing_list = array();
		foreach($jibing as $val){
			$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];

			$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
		}
		$data['jibing'] = $jibing_list;
		$data['province'] = $province_list;
		$data['page'] = $this->pagination->create_links();
		$data['per_page'] = $per_page;
		$data['message_list'] = $message_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('message_list', $data);
	}
//预约列表
        
	public function order_list()
	{
		$data = array();
		$data           = $this->common->config('order_list');
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
		if(!empty($date))
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];
			$data['start'] = $start;
			$data['end'] = $end;
			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—
			$end = str_replace(array("年", "月", "日"), "-", $end);
			$start = substr($start, 0, -1);
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
                //获取订单类别表Order_type中的所有数据
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
			$orderby = substr($orderby, 1);
			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
		}

		$order_count = $this->model->order_count($where);
		$order_count['wei'] = $order_count['count'] - $order_count['come'];
		$config = page_config();
		if($type == 'mi')
		{
			$config['base_url'] = '?c=order&m=order_list&type=mi&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu;
		}
		else
		{
			$config['base_url'] = '?c=order&m=order_list&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu;
		}
		$config['total_rows'] = $order_count['count'];
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
                //获取相关数据
		$order_list = $this->model->order_list($where, $page, $per_page, $orderby);
		
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

		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL
		$data['area'] = $area;	
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
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		if($type == 'mi')
		{
			$this->load->view('order_list_mi', $data);
		}
		else
		{
			$this->load->view('order_list', $data);
		}
	}
	
	public function order_list_down()
	{
		$data = array();
		$data = $this->common->config('order_list_down');
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';
		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';
		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;
		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;
		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;
		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$data['hos_id']   = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$data['p_n']      = $patient_name;
		$data['p_p']      = $patient_phone;
		$data['o_o']      = $order_no;
		$data['f_p_i']    = $from_parent_id;
		$data['f_i']      = $from_id;
		$data['a_i']      = $asker_name;
		$data['s']        = $status;
		$data['o_t']      = $type_id;
		$data['t']        = $order_type;
		$data['p_jb']     = $p_jb;
		$data['jb']       = $jb;

		if(!empty($date))
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];
			$data['start'] = $start;
			$data['end'] = $end;
			$start = str_replace(array("年", "月", "日"), "-", $start);
			$end = str_replace(array("年", "月", "日"), "-", $end);
			$start = substr($start, 0, -1);
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
				$data['start'] = date("Y年m月d日", time() - 86400);
				$data['end'] = date("Y年m月d日", time());
			}
		}
		
		$hospital = $this->model->hospital_order_list();
		$keshi = $this->model->keshi_order_list();
		$keshi_arr = array();
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
		$from_arr = array();
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
		$from_list_son = $from_arr;
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
				$jibing_list[$val['jb_id']]['jb_code'] = $val['jb_code'];
				if($val['jb_level'] == 2)
				{
					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
					$jibing_parent[$val['jb_id']]['jb_code'] = $val['jb_code'];
				}
			}
		}

		$data['from_arr'] = $from_arr;
		$data['keshi'] = $keshi_arr;
		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;

		foreach($hospital as $val)
		{
			$hos_id_arr[] = $val['hos_id'];
		}
		foreach($keshi as $val)
		{
			$keshi_id_arr[] = $val['keshi_id'];
		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;
		$per_page = empty($per_page)? 30:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');

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

		if(!empty($type_id))
		{
			$where .= " AND o.type_id = $type_id";
		}

		if($wu == 1)
		{
			$w_start = strtotime($start . ' 00:00:00') - 25200;
			$w_end = strtotime($end . ' 17:00:00');
		}
		else
		{
			$w_start = strtotime($start . ' 00:00:00');
			$w_end = strtotime($end . ' 23:59:59');
		}

		/* 时间条件 */
		if($order_type == 1)
		{
			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			$orderby .= ',o.order_addtime DESC ';
		}
		elseif($order_type == 2)
		{
			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			$orderby .= ',o.order_time DESC ';
		}
		elseif($order_type == 3)
		{
			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			$orderby .= ',o.come_time DESC ';
		}
		elseif($order_type == 4)
		{
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

		if(!empty($patient_phone))
		{
			$where = " p.pat_phone = '". $patient_phone . "'";
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
			$orderby = ' ORDER BY o.order_id DESC ';
		}
		else
		{
			$orderby = substr($orderby, 1);
			$orderby = ' ORDER BY ' . $orderby . ", o.order_id DESC";
		}

		$order_list = $this->model->order_list($where, 0, 10000, $orderby);		
		$order_id_arr = array();
		foreach($order_list as $key => $val)
		{
			$order_id_arr[] = $key;
		}
		$area = $this->model->area();
		// 查询获取备注信息
		$sql = "SELECT * FROM " . $this->common->table('order_remark') . " WHERE order_id IN (" . implode(",", $order_id_arr) . ") ORDER BY mark_id DESC";
		$row = $this->common->getAll($sql);
		foreach($row as $val)
		{
			$order_list[$val['order_id']]['mark'] = isset($order_list[$val['order_id']]['mark'])? $order_list[$val['order_id']]['mark']:'';
			$order_list[$val['order_id']]['mark'] .= $val['mark_content'] . "[" . $val['admin_name'] . " @" . date("m-d H:i", $val['mark_time']) . "]";
		}
		// 清空输出缓冲区
		//ob_clean();
		// 载入PHPExcel类库
		$this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		// 创建PHPExcel对象
		$objPHPExcel = new PHPExcel();
		// 设置excel文件属性描述
		$objPHPExcel->getProperties()
					->setTitle(time())
					->setDescription(time());
		// 设置当前工作表
		$objPHPExcel->setActiveSheetIndex(0);
		// 设置表头
		$fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式','方式子类', '地区', '预约性质', '预约人', '备注', '回访登记');
		// 列编号从0开始，行编号从1开始
		$col = 0;
		$row = 1;
		foreach($fields as $field)
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
			$col++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		// 从第二行开始输出数据内容
		$row = 2;
		$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];
		$rank_type = $this->common->getRow($sql);
		foreach($order_list as $val)
		{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val["is_come"] == 1)? (!empty($val["come_time"])? date("Y-m-d H:i", $val["come_time"]):''):'');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no"]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["doctor_name"]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_name"]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_age"]);
			if($rank_type['rank_type']==2||$rank_type['rank_type']==3||$_COOKIE['l_admin_action'] == 'all'||$_COOKIE['l_admin_id']=129){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val["pat_phone"]);
			}else{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, substr_replace($val["pat_phone"],'****',7,4));
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($jibing_list[$val["jb_id"]])? $jibing_list[$val["jb_id"]]['jb_name']:''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_code']:'');
			$from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name']:'';
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $from_name);	
			$from_name_son = isset($from_list_son[$val["from_id"]])? $from_list_son[$val["from_id"]]['from_name']:'';
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $from_name_son);
			$a = '';
			if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}
		    if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}
		    if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val["admin_name"]);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, isset($val['mark'])? $val['mark']:'');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, isset($val['back'])? $val['back']:'');
			$row++;
		}
		//输出excel文件
		$objPHPExcel->setActiveSheetIndex(0);
		// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
		// 设置HTTP头
		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
		header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	
	public function order_swt()
	{
		$data = array();
		$data = $this->common->config('order_swt');
		$down           = isset($_REQUEST['down'])? intval($_REQUEST['down']):0;
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';
		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';
		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';
		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):2;
		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;
		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;
		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$swt_url        = isset($_REQUEST['swt_url'])? trim($_REQUEST['swt_url']):'';
		$swt_keyword    = isset($_REQUEST['swt_keyword'])? trim($_REQUEST['swt_keyword']):'';
		$swt_type       = isset($_REQUEST['swt_type'])? trim($_REQUEST['swt_type']):'';

		$data['swt_url']     = $swt_url;
		$data['swt_keyword'] = $swt_keyword;
		$data['swt_type']    = $swt_type;
		$data['hos_id']   = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$data['p_n']      = $patient_name;
		$data['p_p']      = $patient_phone;
		$data['o_o']      = $order_no;
		$data['f_p_i']    = $from_parent_id;
		$data['f_i']      = $from_id;
		$data['a_i']      = $asker_name;
		$data['s']        = $status;
		$data['o_t']      = $type_id;
		$data['t']        = $order_type;
		$data['p_jb']     = $p_jb;
		$data['jb']       = $jb;
		
		if(!empty($date))
		{
			$date = explode(" - ", $date);
			$start = $date[0];
			$end = $date[1];
			$data['start'] = $start;
			$data['end'] = $end;
			$start = str_replace(array("年", "月", "日"), "-", $start);
			$end = str_replace(array("年", "月", "日"), "-", $end);
			$start = substr($start, 0, -1);
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
	
		$hospital = $this->model->hospital_order_list();
		$keshi = $this->model->keshi_order_list();
		$keshi_arr = array();
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
		$from_arr = array();
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
				$jibing_list[$val['jb_id']]['jb_code'] = $val['jb_code'];
				if($val['jb_level'] == 2)
				{
					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
					$jibing_parent[$val['jb_id']]['jb_code'] = $val['jb_code'];
				}
			}
		}
		$data['from_arr'] = $from_arr;
		$data['keshi'] = $keshi_arr;
		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;
		foreach($hospital as $val)
		{
			$hos_id_arr[] = $val['hos_id'];
		}
		foreach($keshi as $val)
		{
			$keshi_id_arr[] = $val['keshi_id'];
		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$data['now_page'] = $page;
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):200;
		$per_page = empty($per_page)? 200:$per_page;
		$this->load->library('pagination');
		$this->load->helper('page');
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
		
		if(!empty($swt_keyword))
		{
			$where .= " AND s.swt_keyword = '" . $swt_keyword . "'";
		}

		if(!empty($swt_url))
		{
			$where .= " AND s.swt_url = '" . $swt_url . "'";
		}

		if(!empty($swt_type))
		{
            if($swt_type == '未标注')
			{
			$where .= " AND s.swt_type is null";
			}
			else
			{
			$where .= " AND s.swt_type = '" . $swt_type . "'";	
			}
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

		if(!empty($type_id))
		{
			$where .= " AND o.type_id = $type_id";
		}

		if($wu == 1)
		{
			$w_start = strtotime($start . ' 00:00:00') - 25200;
			$w_end = strtotime($end . ' 17:00:00');
		}
		else
		{
			$w_start = strtotime($start . ' 00:00:00');
			$w_end = strtotime($end . ' 23:59:59');
		}

		/* 时间条件 */
		if($order_type == 1)
		{
			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			$orderby .= ',o.order_addtime DESC ';
		}
		elseif($order_type == 2)
		{
			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			$orderby .= ',o.order_time DESC ';
		}
		elseif($order_type == 3)
		{
			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			$orderby .= ',o.come_time DESC ';
		}
		elseif($order_type == 4)
		{
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
			$orderby = substr($orderby, 1);
			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
		}

		$order_count = $this->model->order_swt_count($where);
		$order_count['wei'] = $order_count['count'] - $order_count['come'];
		$config = page_config();
		$config['base_url'] = '?c=order&m=order_swt&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu;
		$config['total_rows'] = $order_count['count'];
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);

		$order_list = $this->model->order_swt_list($where, $page, $per_page, $orderby);
		$order_list_down = $this->model->order_swt_list($where, 0, $order_count['count'], $orderby);
		$rank_type = $this->model->rank_type();
		$area = $this->model->area();
		if($down == 1)
		{
			// 清空输出缓冲区
			//ob_clean();
			// 载入PHPExcel类库
			$this->load->library('PHPExcel');
			$this->load->library('PHPExcel/IOFactory');
			// 创建PHPExcel对象
			$objPHPExcel = new PHPExcel();
			// 设置excel文件属性描述
			$objPHPExcel->getProperties()
						->setTitle(time())
						->setDescription(time());
			// 设置当前工作表
			$objPHPExcel->setActiveSheetIndex(0);
			// 设置表头
			$fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式', '地区', '预约性质', '关键词', '轨迹来源', '网址');
			// 列编号从0开始，行编号从1开始
			$col = 0;
			$row = 1;
			foreach($fields as $field)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
				$col++;
			}

			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			// 从第二行开始输出数据内容
			$row = 2;
			foreach($order_list_down as $val)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val["is_come"] == 1)? (!empty($val["come_time"])? date("Y-m-d H:i", $val["come_time"]):''):'');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["doctor_name"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_name"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_age"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val["pat_phone"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (isset($data['jibing'][$val["jb_parent_id"]])? $data['jibing'][$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($data['jibing'][$val["jb_id"]])? $data['jibing'][$val["jb_id"]]['jb_name']:''));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, isset($data['jibing'][$val["jb_parent_id"]])? $data['jibing'][$val["jb_parent_id"]]['jb_code']:'');
				$from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name']:'';
				if(($from_name == '商务通') || ($from_name == 'QQ') || ($from_name == '美洽') || ($from_name == '百度商桥') || ($from_name == 'PC') || ($from_name == '移动'))
				{
					$from_name = '网络';
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $from_name);
				$a = '';
				if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}
				if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}
				if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val["swt_keyword"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val["swt_type"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val["swt_url"]);
				$row++;
			}
			//输出excel文件
			$objPHPExcel->setActiveSheetIndex(0);
			// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			// 设置HTTP头
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}
		else
		{
			$data['down_url'] = '?c=order&m=order_swt&down=1&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL
			$data['rank_type'] = $rank_type;
			$data['page'] = $this->pagination->create_links();
			$data['per_page'] = $per_page;
			$data['order_list'] = $order_list;
			$data['order_count'] = $order_count;
			$data['type_list'] = $type_list;
			$data['from_list'] = $from_list;
			$data['hospital'] = $hospital;
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('order_swt', $data);
		}
	}

	public function order_swt_update()
	{
		$type        = $_REQUEST['type'];
		$order_id    = $_REQUEST['order_id'];
		$value       = $_REQUEST['value'];

		$arr = array('order_id' => $order_id,
			         "$type" => $value);

		$is_havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order_swt') . " WHERE order_id = $order_id");
		if($is_havd)
		{
			$this->db->update($this->common->table('order_swt'), $arr, array('order_id' => $order_id));
		}
		else
		{
			$this->db->insert($this->common->table('order_swt'), $arr);
		}
		echo 1;
	}

	public function ask_data_input()
	{
		$data = array();
		$data = $this->common->config('ask_data_input');
		$site_list = $this->common->getAll("SELECT site_id, site_mobile_domain, site_domain FROM " . $this->common->table('site') . " ORDER BY site_order ASC, site_id DESC");
		$data['site_list'] = $site_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('ask_data_input', $data);
	}

	public function ask_data_update()
	{
		$this->common->config('ask_data_input');
		$data_type = intval($_REQUEST['data_type']);
		$data_site = intval($_REQUEST['data_site']);
		$data_time = trim($_REQUEST['data_time']);

		if(empty($data_type))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		if(($data_type == 2) && empty($data_site))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$config['upload_path'] = 'static/upload/';
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size'] = '2000';
		$zui = explode(".", $_FILES['file']['name']);
		$zui = $zui[sizeof($zui)- 1];
		$config['file_name'] = time() . "." . $zui;
		$this->load->library('upload', $config);
		$this->upload->do_upload('file');
		$data =  $this->upload->data();
		$file = $data['full_path'];

		if($zui == 'csv')
		{
			$fp = fopen($file,'r');
			$i = 1;
			while ($val = fgetcsv($fp))
			{
				if($i > 1)
				{
					if($data_type == 2)
					{
						$val[3] = mb_convert_encoding($val[3],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$val[11] = mb_convert_encoding($val[11],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$val[15] = mb_convert_encoding($val[15],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$val[17] = isset($val[17])? mb_convert_encoding($val[17],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';
						$val[18] = isset($val[18])? mb_convert_encoding($val[18],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';
						$val[21] = mb_convert_encoding($val[21],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$val[23] = isset($val[23])? mb_convert_encoding($val[23],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';
						$val[24] = isset($val[24])? mb_convert_encoding($val[24],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';
						$val[26] = trim($val[26]);
						if($val[15] == '访客发起')
						{
							$qiao_start_type = 1;
						}
						else
						{
							$qiao_start_type = 2;
						}
						$qiao_ask_time = $val[2];
						$site_addtime = $data_time;
						$admin_id = $this->common->getOne("SELECT admin_id FROM " . $this->common->table('asker') . " WHERE asker_qiao_name = '" . $val[11] . "'");
						if(!empty($admin_id) && $val[12] >= 3)
						{
							$this->db->query("UPDATE " . $this->common->table('asker') . " SET asker_ask = asker_ask + 1 WHERE admin_id = $admin_id");
							$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_ask	= site_ask + 1 WHERE site_id = $data_site AND site_addtime = " . strtotime($site_addtime));
						}

						$key_id = 0;
						$plan_id = 0;
						$search_key = 0;
						$qiao_from_type = $val[21];
						if($qiao_from_type == '直接访问')
						{
							$from_site_id = 1;
							$from_type_id = 3;
						}
						elseif($qiao_from_type == '百度搜索推广')
						{
							$from_site_id = 4;
							$from_type_id = 4;
							if(!empty($val[23]))
							{
								$key_info = $this->common->getRow("SELECT key_id, plan_id FROM " . $this->common->table('bd_cpc_day_' . $data_site) . " WHERE key_word = '" . $val[23] . "'");
								$key_id = $key_info['key_id'];
								$plan_id = $key_info['plan_id'];
							}	
							if(!empty($val[24]))
							{
								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[24] . "'");
								if(!$search_key)
								{
									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[24]));
									$search_key = $this->db->insert_id();
								}
							}
						}
						elseif($qiao_from_type == '百度自然搜索')
						{
							$from_site_id = 6;
							$from_type_id = 2;
							if(!empty($val[23]))
							{
								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");
								if(!$search_key)
								{
									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[23]));
									$search_key = $this->db->insert_id();
								}
							}
						}
						elseif($qiao_from_type == '搜狗')
						{
							$from_site_id = 12;
							$from_type_id = 2;
							if(!empty($val[23]))
							{
								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");
								if(!$search_key)
								{
									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[23]));
									$search_key = $this->db->insert_id();
								}
							}
						}
						else
						{
							if(strstr($qiao_from_type, '360.cn'))
							{
								$from_site_id = 12;
								$from_type_id = 2;
							}
							else
							{
								$from_site_id = 0;
								$from_type_id = 0;
							}
							if(!empty($val[23]))
							{
								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");
								if(!$search_key)
								{
									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[24]));
									$search_key = $this->db->insert_id();
								}
							}
						}
						$sys_type = 1;
						if(strstr($val[25], 'iPhone')){
							$sys_type = 2;
						}elseif(strstr($val[25], 'Android'))
						{
							$sys_type = 2;
						}elseif(strstr($val[25], 'Linux'))
						{
							$sys_type = 2;
						}

						/* 根据IP获取地区 */
						$area_info = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $val[27]);
						$area_info = json_decode($area_info);
						$data_province = $area_info->data->region;
						$data_city = $area_info->data->city;
						$arr = array('admin_id' => $admin_id,
									 'site_id' => $data_site,
									 'from_site_id' => $from_site_id,
									 'from_type_id' => $from_type_id,
									 'plan_id' => $plan_id,
									 'key_id' => $key_id,
									 'search_key' => $search_key,
									 'ask_from_id' => 3,
									 'data_time' => strtotime($qiao_ask_time),
									 'data_year' => date("Y", strtotime($qiao_ask_time)),
									 'data_month' => date("m", strtotime($qiao_ask_time)),
									 'data_day' => date("d", strtotime($qiao_ask_time)),
									 'data_week' => date("w", strtotime($qiao_ask_time)),
									 'data_hour' => date("H", strtotime($qiao_ask_time)),
									 'ask_from_value' => $val[26],
									 'data_type' => $sys_type);
									 
						/* 查看是否预约 */
						$order_id = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE from_parent_id = 3 AND from_value = '" . $val[26] . "' AND order_addtime <= '" . strtotime($data_time . " 23:59:59") . "' AND order_addtime >= '" . strtotime($data_time . " 00:00:00") . "'");
						if($order_id)
						{
							$arr['is_order'] = 1;
							$arr['order_id'] = $order_id;
							$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_order = site_order + 1 WHERE site_id = $data_site AND site_addtime = " . strtotime($site_addtime));
						}

						$this->db->insert($this->common->table('ask_data'), $arr);
						$data_id = $this->db->insert_id();					

						$arr = array('data_id' => $data_id,
									 'data_ip' => $val[27],
									 'data_province' => $data_province,
									 'data_city' => $data_city,
									 'data_viewer_words' => $val[12],
									 'data_asker_words' => $val[13],
									 'data_start_type' => $qiao_start_type);
						$this->db->insert($this->common->table('ask_data_info'), $arr);
					
						$arr = array('data_id' => $data_id,
									 'qiao_ask_time' => strtotime($qiao_ask_time),
									 'qiao_view_times' => $val[4],
									 'qiao_view_pages' => $val[5],
									 'qiao_this_pages' => $val[6],
									 'qiao_asker' => $val[11],
									 'admin_id' => $admin_id,
									 'qiao_viewer_words' => $val[12],
									 'qiao_asker_words' => $val[13],
									 'qiao_start_type' => $qiao_start_type,
									 'qiao_viewer_name' => $val[17],
									 'qiao_viewer_others' => $val[18],
									 'qiao_from_page' => isset($val[20])? $val[20]:'',
									 'qiao_from_type' => $val[21],
									 'qiao_keyword' => $val[23],
									 'qiao_search_keyword' => $val[24],
									 'qiao_only_id' => $val[26],
									 'qiao_ip' => $val[27]);

						$this->db->insert($this->common->table('ask_data_qiao'), $arr);
					}
					elseif($data_type == 1)
					{
					}
				}
				$i ++;
			}
			fclose($fp);

		}
		elseif($zui == 'xls')
		{
			$this->load->library('excel');
			$this->excel->setOutputEncoding('CP936');
			$this->excel->_encoderFunction = 'mb_convert_encoding';
			$this->excel->read($file);	
		}
		$file_zui = explode(".", $file);
		if(isset($file_zui[1]))
		{
			unlink($file);
		}
		$links[0] = array('href' => '?c=order&m=ask_data_input', 'text' => $this->lang->line('go_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function dao_false()
	{
		$data = array();
		$data = $this->common->config('dao_false');
		$false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");
		$data['false'] = $false;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('dao_false', $data);
	}

	public function dao_false_update()
	{
		$this->common->config('dao_false');
		$form_action  = $_REQUEST['form_action'];
		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;
		$false_name   = isset($_REQUEST['false_name'])? trim($_REQUEST['false_name']):'';
		$false_order  = isset($_REQUEST['false_order'])? intval($_REQUEST['false_order']):0;
		if(empty($false_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$arr = array('false_name' => $false_name,
					 'false_order' => $false_order);

		if($form_action == 'update')
		{
			if(empty($false_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->db->update($this->common->table('dao_false'), $arr, array('false_id' => $false_id));
		}
		else
		{
			$this->db->insert($this->common->table('dao_false'), $arr);
		}

		$links[0] = array('href' => '?c=order&m=dao_false', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function dao_false_ajax()
	{
		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;
		if(empty($false_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$info = $this->common->getRow("SELECT * FROM " . $this->common->table('dao_false') . " WHERE false_id = $false_id");
		echo json_encode($info);
	}

	public function dao_false_del()
	{
		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;
		if(empty($false_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
	
		$this->db->delete($this->common->table('dao_false'), array('false_id' => $false_id));
		$links[0] = array('href' => '?c=order&m=dao_false', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function order_info_ajax()
	{
		$order_id     = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		if(empty($order_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$info = $this->model->order_info($order_id);
		if($info['pat_sex'] == 1)
		{
			$info['sex'] = $this->lang->line('man');
		}
		else
		{
			$info['sex'] = $this->lang->line('woman');
		}
		$info['addtime'] = date("Y-m-d H:i", $info['order_addtime']);
		if(empty($info['order_time']))
		{
			$info['ordertime'] = $info['order_null_time'];
		}

		else

		{

			$info['ordertime'] = date("Y-m-d", $info['order_time']);

			if(!empty($info['order_time_duan']))

			{

				$dt = $this->lang->line('day_time');

				$info['ordertime'] .= $dt[$info['order_time_duan']];

			}

		}

		echo json_encode($info);

	}

	
	public function order_out_ajax()
	{
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		$sql = "select rank_type from ".$this->common->table('rank')." where rank_id = ".$_COOKIE['l_rank_id'];
		$type = $this->common->getOne($sql);
		if($_COOKIE['l_rank_id'] == 1 || $type == 2){
		
			$sql = 'select * from '.$this->common->table('order_out').' where order_id = '.$order_id;
			
			$res = $this->common->getRow($sql);
			
			if($res){
				
				$this->db->delete($this->common->table('order_out'),array('order_id' => $order_id));
				
				$str = '<blockquote><p><font color=#FF0000>（取消用户不来院的提示信息）</font></p><small><a>' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
				$tag = 1;
			}else{
			
				$this->db->insert($this->common->table('order_out'), array('order_id' => $order_id,  'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() , 'type' => 1));
				
				$str = '<blockquote><p><font color=#FF0000>（该预约用户确认不来院）</font></p><small><a>' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
				$tag = 2;
			}
			 print_r(json_encode(array('str'=>$str,'tag'=>$tag)));
		}

	}
	
	public function user_out_ajax()
	{
		$order_id = $this->input->post('order_id',true);
		if(!empty($order_id)&&is_numeric($order_id)){
			$sql = 'select * from '.$this->common->table('order_out').' where order_id = '.$order_id;
			
			$res = $this->common->getRow($sql);
			
			if(empty($res)){
				$user = $this->common->getRow('select p.pat_id,p.pat_name from '.$this->common->table('patient').' p left join '.$this->common->table('order').' o on p.pat_id = o.pat_id where o.order_id = '.$order_id);
				$this->db->insert($this->common->table('order_out'), array('order_id' => $order_id,  'admin_id' => $user['pat_id'], 'admin_name' => $user['pat_name'], 'mark_time' => time() , 'type' => 1));
			}
		}
	}
	
	public function user_time_ajax()
	{
		$order_id = $this->input->post('order_id',true);
		$order_time = $this->input->post('order_time',true);
		if(!empty($order_id)&&is_numeric($order_id)){
			$order_time = strtotime($order_time);
			$res = $this->db->update($this->common->table('order'),array('order_time'=>$order_time),array('order_id'=>$order_id));
			if($res){
			
				$this->db->delete($this->common->table('order_out'),array('order_id' => $order_id));
				
				echo 1;
			}
		}
	}
	public function huifang_list()
	{
		$data = array();
		$data = $this->common->config('huifang_list');
		// 是否是通过姓名查找
		$name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';
		$a_i = isset($_REQUEST['a_i']) ? trim($_REQUEST['a_i']) : '';
		$hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;
		$type_id = isset($_REQUEST['type_id']) ? intval($_REQUEST['type_id']) : 0;
		if(empty($type_id)||$type_id == 1){
			$type = 3;
		}else{
			$type = 1;
		}
		$hos = $this->common->get_hosname();
		$data['hospital'] = $hos;
		if(empty($hos_id)){
			foreach($hos as $val)
			{
				$hos_arr [] = $val['hos_id'];
			}
			$hos_str = implode(',',$hos_arr);
		}else{
			$hos_str = $hos_id;
		}
		
		$data['huifang'] = $this->set_huifang();
		
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
			
		$data['now_page'] = $page;
			
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):10;

		$per_page = empty($per_page)? 10:$per_page;
			
		$this->load->library('pagination');

		$this->load->helper('page');
			
		$config = page_config();

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;
		
		//处理搜索条件
		$huifang_d = isset($_REQUEST['huifang_d']) ? intval($_REQUEST['huifang_d']) : 0;
		$zt_id = isset($_REQUEST['zt_id']) ? intval($_REQUEST['zt_id']) : 0;
		$lx_id = isset($_REQUEST['lx_id']) ? intval($_REQUEST['lx_id']) : 0;
		$jg_id = isset($_REQUEST['jg_id']) ? intval($_REQUEST['jg_id']) : 0;
		$ls_id = isset($_REQUEST['ls_id']) ? intval($_REQUEST['ls_id']) : 0;
		$date  = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';
		$query = array();
		$where = 1;
		if(!empty($huifang_d)&&empty($name)){
			$time = strtotime(date('Y-m-d'));
			if(1 == $huifang_d){
				$query[] = 'huifang_d=1';
				$where .= ' and o.order_time = '.$time;
			}else if(2 == $huifang_d){
				$query[] = 'huifang_d=2';
				$start = $time-24*3600;
				$where .= ' and o.order_time = '.$start;
			}else if(3 == $huifang_d){
				$query[] = 'huifang_d=3';
				$start = $time+24*3600;
				$where .= ' and o.order_time = '.$start;
			}
			$start = date("Y-m-d", time());

			$end = date("Y-m-d", time());

			$data['start_date'] = $start;

			$data['end_date'] = $end;
		}else{
			if(!empty($date))

			{
				$query[] = 'date='.$date;
				$data['date'] = $date;
				$date = explode(" - ", $date);

				$start = $date[0];

				$end = $date[1];

				$start = str_replace(array("年", "月", "日"), "-", $start);

				$end = str_replace(array("年", "月", "日"), "-", $end);

				$start = substr($start, 0, -1);

				$end = substr($end, 0, -1);

				$data['start_date'] = $start;

				$data['end_date'] = $end;
				
				$w_start = strtotime($start . ' 00:00:00');
				
				$w_end = strtotime($end . ' 23:59:59');
				
				$where .= ' and date_lx > '.$w_start.' and date_lx < '.$w_end;
			}else{
				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;
			
			}
			
		}
		if(!empty($zt_id)){
			$query[] = 'zt_id='.$zt_id;
			$where .= ' and zt_id = '.$zt_id;
		}
		if(!empty($lx_id)){
			$query[] = 'lx_id='.$lx_id;
			$where .= ' and lx_id = '.$lx_id;
		}
		if(!empty($jg_id)){
			$query[] = 'jg_id='.$jg_id;
			$where .= ' and jg_id = '.$jg_id;
		}
		if(!empty($ls_id)){
			$query[] = 'ls_id='.$ls_id;
			$where .= ' and ls_id = '.$ls_id;
		}
		if(empty($name)){
			if(!empty($a_i)){
				$query[] = 'a_i='.$a_i;
				$where .= ' and r.admin_name = "'.$a_i.'"';
			}
			// 统计回访条数
			$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r 
					left join '.$this->common->table('order').' o on o.order_id = r.order_id
					left join '.$this->common->table('remark_extend').' e on r.mark_id = e.mark_id 
					where r.mark_type = '.$type.' and '.$where.' and o.hos_id in ('.$hos_str.')';
			$count = $this->common->getOne($sql);
			if($count>0){
			$config['base_url'] = '?c=order&m=huifang_list&p=' . $per_page .'&'.implode('&',$query);
			
			$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.order_no,o.pat_id,o.is_come,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx 
					from '.$this->common->table('order_remark').' r 
					left join '.$this->common->table('order').' o on o.order_id = r.order_id 
					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id 
					where r.mark_type = '.$type.' and '.$where.' and o.hos_id in ('.$hos_str.') order by o.order_id desc,r.mark_time desc LIMIT '.$page.', '.$per_page;
			$huifang_info = $this->common->getAll($sql_list);
			// 统计病人信息
			foreach($huifang_info as $val)
			{
				$pat_arr [] = $val['pat_id'];
			}
			$pat_str = implode(',',$pat_arr);
			$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_id in ('.$pat_str.')';
			$pat_list = $this->common->getAll($pat_info);
			$pat_cache = array();
			foreach($pat_list as $val){
				$pat_cache[$val['pat_id']] = $val;
			}
			}else{
				$pat_cache = array();
				$huifang_info = array();
			}
		}else{
			// 查找符合姓名的患者信息
			if(preg_match("/^[A-Za-z]*$/",$name)){
				$order_no = strtoupper($name);
				$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r  
					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id 
					left join '.$this->common->table('order').' o on o.order_id = r.order_id 
					where mark_type = '.$type.' and o.order_no = "'.$name.'" and '.$where.' and hos_id in ('.$hos_str.')';
				$count = $this->common->getOne($sql);
				if($count>0){
					$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.pat_id,o.order_no,o.order_addtime,o.pat_id,o.is_come,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx 
					from '.$this->common->table('order_remark').' r 
					left join '.$this->common->table('order').' o on o.order_id = r.order_id 
					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id 
					where r.mark_type = '.$type.' and '.$where.' and o.order_no = "'.$name.'"';
					$huifang_info = $this->common->getAll($sql_list);
					$pat_id = $huifang_info[0]['pat_id'];
					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_id = '.$pat_id;
					$pat_list = $this->common->getAll($pat_info);
					$pat_cache = array();
					foreach($pat_list as $val){
						$pat_cache[$val['pat_id']] = $val;
					}
				}else{
					$pat_cache = array();
					$huifang_info = array();				
				}
			}else{
				if(intval($name)==0){
					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_name like "%'.$name.'%"';
				}else{
					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_phone = "'.$name.'"';
				}
				$pat_list = $this->common->getAll($pat_info);
				$pat_cache = array();
				$pat_id_arr = array();
				foreach($pat_list as $val){
					$pat_id_arr[] = $val['pat_id'];
					$pat_cache[$val['pat_id']] = $val;
				}
				$pat_id_str = implode(',',$pat_id_arr);
				// 查找符合条件的订单
				$sql_order = 'select order_id from '.$this->common->table('order').' where hos_id in ('.$hos_str.') and pat_id in ('.$pat_id_str.')';
				$arr = $this->common->getAll($sql_order);
				foreach($arr as $val){
					$order_arr[] = $val['order_id'];
				}
				$order_str = implode(',',$order_arr);
				// 统计回访信息
				$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r  
						left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id 
						where mark_type = '.$type.' and '.$where.' and order_id in ('.$order_str.')';
				$count = $this->common->getOne($sql);
		
				$config['base_url'] = '?c=order&m=huifang_list&p=' . $per_page . '&name='.$name .'&'.implode($query);
				
				
				
				$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.order_no,o.order_addtime,o.pat_id,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx 
						from '.$this->common->table('order_remark').' r 
						left join '.$this->common->table('order').' o on o.order_id = r.order_id 
						left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id 
						where r.mark_type = '.$type.' and '.$where.' and o.order_id in ('.$order_str.') order by o.order_id desc,r.mark_time desc LIMIT '.$page.', '.$per_page;
				$huifang_info = $this->common->getAll($sql_list);
			}
			$data['name'] = $name;
		}
		// 根据订单id归类
		$res = array();
		foreach($huifang_info as $val){
			$res[$val['order_id']][] = $val;
		}
		
		$config['total_rows'] = $count;
		$this->pagination->initialize($config);
		$data['huifang_list'] = $res;
		$data['pat_cache'] = $pat_cache;
		$data['page'] = $this->pagination->create_links();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('huifang_list', $data);
	}
	public function order_update_ajax()
	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$type = $_REQUEST['type'];

		$remark = isset($_REQUEST['remark'])? trim($_REQUEST['remark']):''; 
               

		if($type == 'visit')

		{
                       //更新公海患者中的预约时间
                       
                        
			$false_id = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;

			$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 3, 'type_id' => $false_id));
			
			$mark_id = $this->db->insert_id();
			
			$zt_id = isset($_REQUEST['zt_id'])? intval($_REQUEST['zt_id']):0;
			$lx_id = isset($_REQUEST['lx_id'])? intval($_REQUEST['lx_id']):0;
			$jg_id = isset($_REQUEST['jg_id'])? intval($_REQUEST['jg_id']):0;
			$ls_id = isset($_REQUEST['ls_id'])? intval($_REQUEST['ls_id']):0;
			$date_lx = isset($_REQUEST['date_lx'])? trim($_REQUEST['date_lx']):'';
			$datehour = isset($_REQUEST['datehour'])? trim($_REQUEST['datehour']):'';
                         $nextdate = isset($_REQUEST['date_lx'])?strtotime($_REQUEST['date_lx']):'';
//                          $query1="update ".$this->common->table('gonghai_order')." set order_time='".$nextdate."',order_time_duan='".$datehour."'  where order_id=".$order_id;
//                        $this->db->query($query1);
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
			
			$str = '<blockquote><p>' . $remark;

			

			if(!empty($false_id))

			{

				$false_str = $this->common->getOne("SELECT false_name FROM " . $this->common->table('dao_false') . " WHERE false_id = $false_id");

				$str .= '<font color=#FF0000>（未到诊原因：' . $false_str . '）</font>';

			}

			

			$str .= '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

		}

		elseif($type == 'dao')

		{

			$doctor_name = $_REQUEST['doctor_name'];

			$this->db->update($this->common->table('order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));
                        $this->db->update($this->common->table('gonghai_order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));
			$str = array('come_time' => date("Y-m-d H:i"));
			
			$info = $this->model->order_info($order_id);
			if($info['hos_id']==1){
				$username = $info['pat_phone'];
				$this->group_change($username);
			}
			

			if(!empty($remark))

			{

				$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 2));

				$str['remark'] = '<blockquote class="d"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

			}

			

			$this->db->update($this->common->table('ask_data'), array('is_dao' => 1), array('order_id' => $order_id));

			$site_addtime = $this->common->getOne("SELECT order_addtime FROM " . $this->common->table('order') . " WHERE order_id = $order_id");

			$site_addtime = strtotime(date("Y-m-d", $site_addtime) . ' 00:00:00');

			$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_daozhen = site_daozhen + 1 WHERE site_addtime = " . $site_addtime);

			$str = json_encode($str);

		}

		elseif($type == 'doctor')

		{

			$come_time = $this->common->getOne("SELECT come_time FROM " . $this->common->table('order') . " WHERE order_id = $order_id");

			if(empty($come_time))

			{

				$come_time = time();

			}

			$doctor_name = $_REQUEST['doctor_name'];

			$this->db->update($this->common->table('order'), array('come_time' => $come_time, 'doctor_name' => $doctor_name, 'doctor_time' => time()), array('order_id' => $order_id));

			$str = array('come_time' => date("Y-m-d H:i", $come_time));

			$str = array('doctor_time' => date("Y-m-d H:i"));

			

			if(!empty($remark))

			{

				$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 5));

				$str['remark'] = '<blockquote class="doc"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

			}

			

			$str = json_encode($str);

		}

		

		echo $str;

	}
	
	public function mes_content_ajax()
	{
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		$remark = isset($_REQUEST['remark'])? trim($_REQUEST['remark']):'';
		if($order_id <= 0 || empty($remark))
		{
			echo 0;
			exit();
		}
		$this->db->insert($this->common->table('mes_content'), array('order_id' => $order_id, 'mark_content' => $remark, 'mark_time' => time() ,'mark_type' => 3));
		$this->db->update($this->common->table('order_mes'), array('admin_id' => $_COOKIE['l_admin_id'],), array('order_id' => $order_id));
		$str = '<blockquote><p>' . $remark;

		$str .= '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

		echo $str;
	}

	

	public function from_order_ajax()

	{

		$parent_id = isset($_REQUEST['parent_id'])? intval($_REQUEST['parent_id']):0;

		$from_id = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;
		
		$tag = isset($_REQUEST['tag'])? intval($_REQUEST['tag']):0;

		echo "<option value=\"0\">" . $this->lang->line('please_select') . "</option>";

		$from_list = $this->model->from_order_list($parent_id,$tag);

		if(empty($from_list))

		{

			exit();

		}

		foreach($from_list as $val)

		{

			echo "<option value=\"" . $val['from_id'] . "\"";

			if($val['from_id'] == $from_id)

			{

				echo " selected";

			}

			echo ">" . $val['from_name'] . "</option>";

		}

	}

	

	public function jibing_ajax()

	{

		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;

		$parent_id = isset($_REQUEST['parent_id'])? intval($_REQUEST['parent_id']):0;

		if($parent_id == 0)

		{

			echo "<option value=\"0\">" . $this->lang->line('jb_parent_select') . "</option>";

			$sql = "SELECT j.jb_id, j.jb_name 

					FROM " . $this->common->table('jibing') . " j 

					LEFT JOIN  " . $this->common->table('keshi_jibing') . " jk ON jk.keshi_id = $keshi_id

					WHERE jk.jb_id = j.parent_id AND j.jb_level = 2";

		}

		else

		{

			echo "<option value=\"0\">" . $this->lang->line('jb_child_select') . "</option>";

			$sql = "SELECT j.jb_id, j.jb_name 

					FROM " . $this->common->table('jibing') . " j 

					WHERE j.parent_id = $parent_id";

		}

		

		$list = $this->common->getAll($sql);

		if(empty($list))

		{

			exit();

		}

		foreach($list as $val)

		{

			echo "<option value=\"" . $val['jb_id'] . "\"";

			if($val['jb_id'] == $check_id)

			{

				echo " selected ";

			}

			echo ">" . $val['jb_name'] . "</option>";

		}

	}

	

	public function phone_ajax()

	{

		$phone = isset($_REQUEST['phone'])? trim($_REQUEST['phone']):'';

		$data = array();

		$data['over'] = '';

		if(empty($phone))

		{

			$data['type'] = 0;

			$data = json_encode($data);

			echo $data;

			exit();

		}

		$phone = explode("/", $phone);

		$phone = $phone[0];

		/* 检查此手机号码之前是否预约过 */

		$order_over = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('order') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '$phone' ORDER BY o.order_id DESC");

		if(!empty($order_over))

		{

			foreach($order_over as $val)

			{

				$data['over'][$val['order_id']] = $val;

				$data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);

			}

		}



		$p_start = substr($phone, 0, 1);

		if($p_start == 0)

		{

			$p_start = substr($phone, 0, 5);

			$area_info = $this->common->getRow("SELECT region_id, parent_id FROM " . $this->common->table('region') . " WHERE region_quhao = '" . $p_start . "'");

			if(empty($area_info))

			{

				$p_start = substr($phone, 0, 4);

				$area_info = $this->common->getRow("SELECT region_id, parent_id FROM " . $this->common->table('region') . " WHERE region_quhao = '" . $p_start . "'");

			}

			

			if(empty($area_info))

			{

				$p_start = substr($phone, 0, 3);

				$area_info = $this->common->getRow("SELECT region_id, parent_id FROM " . $this->common->table('region') . " WHERE region_quhao = '" . $p_start . "'");

			}

			

			$data['type'] = 2;

			$data['info'] = $area_info;

		}

		else

		{

			$xml = file_get_contents("http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=" . $phone);

			preg_match_all( "/\<city\>(.*?)\<\/city\>/s", $xml, $city);

			if(!isset($city[1][0]))

			{

				$data['type'] = 1;

				$data = json_encode($data);

				echo $data;

				exit();

			}

			$city[1][0] = mb_convert_encoding($city[1][0],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			$area_info = $this->common->getRow("SELECT region_id, parent_id FROM " . $this->common->table('region') . " WHERE region_name = '" . trim($city[1][0]) . "' AND region_type = 2");

			$data['type'] = 2;

			$data['info'] = $area_info;

		}

		$data = json_encode($data);

		echo $data;

	}

	

	public function order_no_ajax()

	{

		$order_no = file_get_contents("./application/cache/static/order_no.txt");

		$zm = array('1' => 'A',

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

		

		echo $order_no;

	}

	

	public function use_no_ajax()

	{

		$order_no = trim($_REQUEST['order_no']);

		$havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE order_no = '" . $order_no . "'");

		if($havd)

		{

			echo 1;

			exit();

		}

		else

		{

			$str_len = strlen($order_no);

			if($str_len != 6)

			{

				echo 2;

				exit();

			}

		}

	}

	

	public function keshi_list_ajax()

	{

		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;

		$type = isset($_REQUEST['type'])? intval($_REQUEST['type']):1;

		

		if($type == 1)

		{

			echo "<option value=\"0\">请选择科室...</option>";

		}

		

		if(empty($hos_id))

		{

			exit();

		}

		

		$keshi_list = $this->model->keshi_order_list($hos_id);

		if(empty($keshi_list))

		{

			exit();

		}

		

		$str = '';

		foreach($keshi_list as $val)

		{

			$str .= '<option value="' . $val['keshi_id'] . '"';

			if($val['keshi_id'] == $check_id)

			{

				$str .= " selected";

			}

			$str .= '>' . $val['keshi_name'] . '</option>';

		}

		

		echo $str;

	}

	

	public function sms_themes_ajax()

	{

		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$where = 1;

		if(!empty($hos_id))

		{

			$where .= " AND hos_id = $hos_id";

		}

		if(!empty($keshi_id))

		{

			$where .= " AND keshi_id = $keshi_id";

		}

		$str = '<option value=\"0\">请选择...</option>';

		

		$row = $this->common->getAll("SELECT themes_id, themes_name FROM " . $this->common->table('sms_themes') . " WHERE $where");

		foreach($row as $val)

		{

			$str .= '<option value="' . $val['themes_id'] . '"';

			$str .= '>' . $val['themes_name'] . '</option>';

		}

		

		echo $str;

	}

	

	public function sms_ajax()

	{

		$pat_name = isset($_REQUEST['pat_name'])? trim($_REQUEST['pat_name']):'';

		$pat_phone = isset($_REQUEST['pat_phone'])? trim($_REQUEST['pat_phone']):'';

		$order_time = isset($_REQUEST['order_time'])? trim($_REQUEST['order_time']):'';

		$order_addtime = isset($_REQUEST['order_addtime'])? trim($_REQUEST['order_addtime']):'';

		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';

		$order_time_type = isset($_REQUEST['order_time_type'])? trim($_REQUEST['order_time_type']):'';

		$order_time_duan = isset($_REQUEST['order_time_duan'])? trim($_REQUEST['order_time_duan']):'';

		$order_no = isset($_REQUEST['order_no'])? trim($_REQUEST['order_no']):'';

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$themes_id = isset($_REQUEST['themes_id'])? intval($_REQUEST['themes_id']):0;

		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		

		$themes_content = $this->common->getOne("SELECT themes_content FROM " . $this->common->table('sms_themes') . " WHERE themes_id = $themes_id");

		if(empty($themes_content))

		{

			echo 1;

			exit();

		}

		

		if($type == 'list')

		{

			$addtime = $order_addtime;

			$ordertime = $order_time;

			if(!empty($order_time_duan))

			{

				$ordertime .= " " . $order_time_duan;

			}

		}

		else

		{

			if(!empty($order_id))

			{

				$order_info = $this->common->getRow("SELECT * FROM " . $this->common->table('order') . " WHERE order_id = $order_id");

				$addtime = date("m-d H:i", $order_info['order_addtime']);

			}

			else

			{

				$addtime = date("m-d H:i");

			}

			

			if($order_time_type == 1)

			{

				$ordertime = $order_time;

			}

			else

			{

				$ordertime = $order_null_time;

			}

			

			$duan = $this->lang->line('day_time');

			if($order_time_duan > 0)

			{

				$ordertime .= " " . $duan[$order_time_duan];

			}

		}



		$replace = array('{username}', '{phone}', '{ordertime}', '{addtime}', '{orderno}');

		$value = array($pat_name, $pat_phone, $ordertime, $addtime, $order_no);

		

		$sms = str_replace($replace, $value, $themes_content);

		echo $sms;

	}

	

	public function ajax_remark_list()

	{

		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';

		$order_id = substr($order_id, 0, -1);

		$sql = "SELECT * FROM " . $this->common->table('order_remark') . " WHERE order_id IN ($order_id) ORDER BY mark_id DESC,mark_time DESC";

		$row = $this->common->getAll($sql);

		$arr = array();

		foreach($row as $val)

		{

			$arr[$val['mark_id']] = $val;

			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);

		}

		echo json_encode($arr);

	}
	public function ajax_message_list()

	{

		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';

		$order_id = substr($order_id, 0, -1);

		$sql = "SELECT * FROM " . $this->common->table('mes_content') . " WHERE order_id IN ($order_id) ORDER BY mark_id DESC,mark_time DESC";

		$row = $this->common->getAll($sql);

		$arr = array();

		foreach($row as $val)

		{

			$arr[$val['mark_id']] = $val;

			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);

		}

		echo json_encode($arr);

	}
	

	public function change_order_status()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		if($order_id <= 0)

		{

			echo 0;

			exit();

		}

		

		$is_come = $this->common->getOne("SELECT is_come FROM " . $this->common->table('order') . " WHERE order_id = $order_id");

		if($is_come == 1)

		{

			$this->db->update($this->common->table('order'), array('is_come' => 0), array('order_id' => $order_id));
                        $this->db->update($this->common->table('gonghai_order'), array('is_come' => 0), array('order_id' => $order_id));
		}

		else
		{

			$this->db->update($this->common->table('order'), array('come_time' => time(), 'is_come' => 1), array('order_id' => $order_id));
                        $this->db->update($this->common->table('gonghai_order'), array('come_time' => time(), 'is_come' => 1), array('order_id' => $order_id));
			$info = $this->model->order_info($order_id);
			if($info['hos_id']==1){
				$username = $info['pat_phone'];
				$this->group_change($username);
			}
		}

		echo date("Y-m-d H:i");

	}

	public function siwei()

	{

		$hos_id            = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$order_id          = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$order_time        = isset($_REQUEST['order_time'])? trim($_REQUEST['order_time']):'';

		$order_time_duan_j = isset($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';
	

		if(empty($hos_id) || empty($order_time) || empty($order_time_duan_j))

		{

			echo 1;

			exit();

		}

		$order_time = strtotime($order_time);

		$where = 1;

		if($order_id > 0)

		{

			$where .= " AND order_id != $order_id";

		}

		

		$is_havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE $where AND hos_id = $hos_id AND jb_parent_id = 149 AND order_time = $order_time AND order_time_duan = '" . $order_time_duan_j . "'");

		if($is_havd)

		{

			echo 1;

			exit();

		}

		else

		{

			echo 0;

		}

	}

	

	public function kefu_talk()

	{

		$type          = isset($_REQUEST['type'])? intval($_REQUEST['type']):0;

		$from_value    = isset($_REQUEST['from_value'])? trim($_REQUEST['from_value']):'';

		

		if(empty($type) || empty($from_value))

		{

			echo 0;

			exit();

		}

		

		if($type == 1)

		{

			$gid_arr = $this->common->getAll("SELECT gid FROM " . $this->common->table('swt') . " WHERE cid = '" . $from_value . "' ORDER BY swt_id ASC");

			$from_value = substr($from_value, 0, 3) . "/" . substr($from_value, 3, 3) . "/" . substr($from_value, 6, 4) . "/" . substr($from_value, 10, 3) . "/" . substr($from_value, 13) . "/";

			$file_path = './static/swt/' . $from_value;

			$str = '';

			$gid_str = '';

			foreach($gid_arr as $val)

			{

				if(empty($val['gid']))

				{

					$val['gid'] = 'index';

				}

				else

				{

					$gid_str .= $val['gid'] . ',';

				}

				

				include_once($file_path . $val['gid'] . ".php");

				foreach($data['swt_list'] as $val)

				{

					$val['user'] = str_replace("您", "患者", $val['user']);

					$str .= "<p><b>" . $val['user'] . "</b></p>";

					$str .= $val['word'] . "<p>&nbsp;</p>";

				}

			}

			echo json_encode(array('gid' => $gid_str, 'str' => $str));

		}

	}

	

	public function page_path()

	{

		$from_value    = isset($_REQUEST['from_value'])? trim($_REQUEST['from_value']):'';

		$gid_arr = $this->common->getAll("SELECT gid FROM " . $this->common->table('swt') . " WHERE cid = '" . $from_value . "' ORDER BY swt_id ASC");

		$str = "";

		foreach($gid_arr as $val)

		{

			if(!empty($val['gid']))

			{

				$site_id = $this->common->getOne("SELECT site_id FROM " . $this->common->table('google_visitor') . " WHERE vis_cid = '" . $val['gid'] . "'");

				$from_site_list  = read_static_cache('from_site_list');

				$from_type_list  = read_static_cache('from_type_list');

				if($site_id)

				{

					$sql = "SELECT path_cid, path_pre, path_url, path_title, path_time, is_ask, load_id, COUNT(path_cid) AS path_url_reload

							FROM " . $this->common->table('google_path_' . $site_id) . " 

							WHERE path_cid = '" . $val['gid'] . "'

							GROUP BY path_url

							ORDER BY path_vtime ASC, path_time DESC";

					$row = $this->common->getAll($sql);

					$i = 1;

					foreach($row as $key=>$val)

					{

						if($val['load_id'] >= 1)

						{

							$load_info = $this->common->getRow("SELECT l.from_site_id, l.from_type_id, l.from_url, k.key_keyword 

																FROM " . $this->common->table('google_load_' . $site_id) . " l 

																LEFT JOIN " . $this->common->table('google_search') . " s ON s.load_id = l.load_id AND s.site_id = $site_id

																LEFT JOIN " . $this->common->table('keywords') . " k ON s.key_id = k.key_id

																WHERE l.load_id = " . $val['load_id']);

						}

						$str .= "<p>" . $i . "、" . date("Y-m-d H:i:s", $val['path_time']) . "<br/>";

						if($i == 1)

						{

							if(isset($load_info['from_site_id']) && !empty($load_info['from_site_id']))

							{

							    $str .=  "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">在</font> " . $from_site_list[$load_info['from_site_id']]['site_name'] . " <font color=\"#FF0000\">以</font> ";

								$str .=  $from_type_list[$load_info['from_type_id']]['type_name'] . " 方式";

								if(!empty($load_info['key_keyword']))

								{

									$str .=  "搜索关键词 <font color=\"#FF0000\" title=\"" . $load_info['key_keyword'] . "\">" . sub_str($load_info['key_keyword'], 20) . "</font>";

								}

							}

							elseif(isset($load_info['from_url']) && !empty($load_info['from_url']))

							{

							    $str .=  "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">在</font> <a href=\"" . $load_info['from_url'] . "\" target=\"_blank\" title=\"" . $load_info['from_url'] . "\">" . sub_str($load_info['from_url'], 30) . "</a>";

							}

							else

							{

								$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> 直接输入";

							}

						}

						elseif(empty($val['path_pre']))

						{

							$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> 直接输入";

						}

						else

						{

							$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> <a href=\"" . $val['path_pre'] . "\" target=\"_blank\">" . $val['path_pre'] . "</a>";

						}

						$str .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">到</font> <a title=\"" . $val['path_title'] . "\" href=\"http://" . $val['path_url'] . "\" target=\"_blank\">http://" . $val['path_url'] . "</a></p>";

						$i ++;

					}

				}

			}

		}

		echo $str;

	}

	

	public function talk_info()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$info = '<div class="left" style="width:65%; height:400px; float:left; overflow-y: auto; overflow-x: hidden;">';

		$content = $this->common->getOne("SELECT con_content FROM " . $this->common->table('ask_content') . " WHERE order_id = $order_id");
		$info .=$content;
		$info .= '</div>';
		if(!empty($content)){
		$sql = "SELECT p.*, a.admin_name, a.admin_id
				FROM " . $this->common->table('asker_ping') . " p
				LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = p.uid
				where p.order_id = $order_id ORDER BY p.tid ASC ";
				
		$ping = $this->common->getAll($sql);

		$info .= '<div class="pinglun">';
		$info .= '<h4><i class="icon-bell"></i>&nbsp;点评</h4>';
		$info .= '<dl class="contentz">';
		foreach($ping as $val)
		{
		$time = date('Y-m-d H:s:i',$val['time']);
		$info .= '<dt class="text_title">'.$val['admin_name'].'&nbsp;发表于'.$time.'</dt>';
		$info .= '<dd class="text_con">'.$val['content'].'</dd>';
                $info .='<hr class="content_hr" />';
		}
		
		$info .= '</dl>';
		$info .= '<div class="tijiao">';
		$info .= '<textarea class="input-large" style="width:80%" rows="5" onClick="bigTxt()" id="textareaz">';
		$info .= '我也说一句';
		$info .= '</textarea>';
		$info .= '<button class="btn" data-toggle="modal" onClick="insert_talk(\''.$order_id.'\')"> 发表 </button>';
		$info .= '</div>';
		
		
		$info .= '</div>';
		}
		
		echo $info;
		

	}
	
	public function insert_info()
	{
	// tid content order_id fid uid
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;
		$data = $_REQUEST['content'];
		$admin_name = $_COOKIE['l_admin_name'];
		$uid = $_COOKIE['l_admin_id'];
		
		$time = time();
		$arr = array(
				'content' => $data,
				'order_id' => $order_id,
				'uid' => $uid,
				'fid' => '0',
				'time'=>$time,
		);
		$this->db->insert($this->common->table("asker_ping"), $arr);
		$time = date('Y-m-d H:s:i',$time);
		$info = '';
		
		//$info .= '<div class="con">';
		$info .= '<dt class="text_title"><span style="color:gray;">'.$admin_name.'&nbsp;发表于'.$time.'</span></dt>';
		
		$info .= '<dd class="text_con">'.$data.'<dd>';
		$info .= '<hr class="content_hr" />';
		echo $info;
	}

	

	public function sms_content()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$sql = "SELECT send_id, send_content, send_time, send_phone, type_value, admin_id, admin_name, send_status, send_type 

				FROM " . $this->common->table('sms_send') . "

				WHERE (send_type = 1 OR send_type = 3) AND type_value = $order_id

				ORDER BY send_time DESC";

		$row = $this->common->getAll($sql);

		$arr = array();

		$status = $this->lang->line('sms_send_status');

		foreach($row as $key=>$val)

		{

			$arr[$key] = $val;

			$arr[$key]['send_time'] = date("m-d H:i", $val['send_time']);

			$arr[$key]['status'] = isset($status[$val['send_status']])? $status[$val['send_status']]:$val['send_status'];

		}

		

		echo json_encode($arr);

	}

	

	public function sms_reply()

	{

		$type = isset($_REQUEST['type'])? intval($_REQUEST['type']):1;

		if($type == 1)

		{

			$date = file_get_contents('./application/cache/static/sms_time.txt');

			echo date("Y-m-d H:i:s", $date);

		}

		else

		{

			$date = file_get_contents('./application/cache/static/sms_time.txt');

			$hospital = $this->common->static_cache('read', "hospital/1", 'hospital', array('hos_id' => 1));

			$username = urlencode(trim($hospital['sms_name']));

			$password = urlencode(trim($hospital['sms_pwd']));

			

			require_once('application/libraries/sms/nusoap.php');

			$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);

			$client->soap_defencoding = 'utf-8';

			$client->decode_utf8      = false;

			$client->xml_encoding     = 'utf-8';



			$params = array(

				'account' => $hospital['sms_name'],

				'password' => $hospital['sms_pwd'],

				'param' => 0,

			);

			

			$result = $client->call('getReceivedMsg', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');

			//$TimeStamp = urlencode(date("YmdHi", $date));

			//$xml = file_get_contents("http://119.145.253.67:8080/edeeserver/getDelivery.do?UserName=$username&TimeStamp=$TimeStamp&Password=$password");

			//$xml = mb_convert_encoding($xml,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			//$xml = file_get_contents("./application/cache/static/cy.txt");

			//preg_match_all('|<MobileNumber>(.*)</MobileNumber>|U', $xml, $num);

			//preg_match_all('|<MsgContent>(.*)</MsgContent>|U', $xml, $con);

			//preg_match_all('|<ReplyTime>(.*)</ReplyTime>|U', $xml, $time);

			if(!isset($result['getReceivedMsgReturn']) || empty($result['getReceivedMsgReturn']))

			{

				echo 1;

				exit();

			}

			

			$str = explode("|", $result['getReceivedMsgReturn']);

			if(!isset($str[1]))

			{

				echo 1;

				exit();

			}

			foreach($str as $val)

			{

				if(empty($val))

				{

					continue;

				}

				$str_arr = explode(",", $val);

				$phone = $str_arr[0];

				$time = substr($str_arr[2], 0, 4) . "-" . substr($str_arr[2], 4, 2) . "-" . substr($str_arr[2], 6, 2) . " " . substr($str_arr[2], 8, 2) . ":" . substr($str_arr[2], 10, 2) . ":" . substr($str_arr[2], 12, 2);

				$str_arr[1] = str_replace("\\","%",$str_arr[1]);

				$content = unescape($str_arr[1]);

				$order_id = $this->common->getOne("SELECT type_value FROM " . $this->common->table('sms_send') . " WHERE send_type = 1 AND send_phone = '" . $phone . "' AND send_time >= " . (time() - 86400 * 90) . " ORDER BY send_id LIMIT 1");

				

				$send = array('send_content' => $content,

				              'send_time' => strtotime($time),

							  'send_phone' => $phone,

							  'admin_name' => $phone,

							  'send_status' => 0,

							  'send_type' => 0,

							  'type_value' => 0);

				

				if($order_id)

				{

					$remark = array('order_id' => $order_id,

									'admin_id' => 0,

									'admin_name' => $phone,

									'mark_content' => $content,

									'mark_time' => strtotime($time),

									'mark_type' => 4);

					$this->db->insert($this->common->table("order_remark"), $remark);

					$send['send_type'] = 3;

					$send['type_value'] = $order_id;

				}

				$this->db->insert($this->common->table("sms_send"), $send);

			}

			file_put_contents('./application/cache/static/sms_time.txt', time());

			echo 1;

		}

	}

	public function sms_send_ajax()
	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$pat_phone = isset($_REQUEST['pat_phone'])? trim($_REQUEST['pat_phone']):'';

		$sms_content = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';

		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		
		$sms_id = isset($_REQUEST['sms_id'])? intval($_REQUEST['sms_id']):0;

		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$pat_phone = explode("/", $pat_phone);

		if(empty($sms_content) || ($hos_id <= 0))
		{
			echo "不能为空！";
			exit();
		}
		$this->load->helper(sms);
		if($sms_id){
			//获取配置信息
			$sms_config = $this->common->getRow("select sms_int,account,password from ".$this->common->table('sms_config')." where sms_id = $sms_id");
			$func_name = 'sms_'.$sms_config['sms_int'];
			$send_phone = implode(",", $pat_phone);
			if ( function_exists($func_name)){
				
				$msg = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$send_status = $func_name($send_phone,$msg,$sms_config['account'],$sms_config['password']);
				$status = $this->lang->line('sms_send_status');
				if($send_status == 0)
				{
					$msg_detail = "短信发送成功！";
					$send_status = 0;
				}
				else
				{
					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";
				}
			}else{
				$msg_detail = "短信发送失败，失败原因：没有可用关口！";
				$send_status = 108;
			}
		}else{
			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));
			$func_name = 'sms_'.$hospital['sms_int'];
			$send_phone = implode(";", $pat_phone);
			if ( function_exists($func_name)){
				
				$msg = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$send_status = sms_jianzhou($send_phone,$msg,$hospital['sms_name'],$hospital['sms_pwd']);
				$status = $this->lang->line('sms_send_status');
				if($send_status == 0)
				{
					$msg_detail = "短信发送成功！";
					$send_status = 0;
				}
				else
				{
					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";
				}
			}else{
				$msg_detail = "短信发送失败，失败原因：没有可用关口！";
				$send_status = 108;
			}
		}
		$this->db->insert($this->common->table('sms_send'), array('send_content' => $sms_content, 'send_time' => time(), 'send_type' => 1, 'type_value' => $order_id, 'send_phone' => $send_phone, 'hos_id' => $hos_id, 'keshi_id' => $keshi_id, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'send_status' => $send_status));

		echo $msg_detail;

	}
	
	public function miss_patient()
	{
		$data = array();
		$data = $this->common->config('miss_patient');
		
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
	
		
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		
		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;
		
		if(1 == $tj_lx){
		
			$tag_str = '%u';
		}else if(2 == $tj_lx){
			$tag_str = '%c';
		}else{
			$tag_str = '%d';
		}
		
		// 组装where条件
		$where = 1;
		
		
		
		if(!empty($keshi_id))
		{
			$where .= " AND o.keshi_id = ".$keshi_id;
		}
		if(!empty($hos_id))
		{
			$where .= " AND o.hos_id = ".$hos_id;
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
		
		
		//组装时间参数，以月为单位进行统计，默认显示当前月的数据
		
		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

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
		
		// end 时间组装
		
		// 预约来源
		$from_list = $this->model->from_order_list(-1);

		$par_from_arr = array();

		$from_arr = array();

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
		
		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();
		$jb_arr = array();
		if(!empty($jibing))

		{

			foreach($jibing as $val)

			{
				$jb_arr[$val['jb_id']] = $val['jb_name'];

				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];

				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];

				if($val['jb_level'] == 2)

				{

					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];

					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];

				}

			}

		}


		
		$data['jibing'] = $jibing_list;
		$data['jb_arr'] = $jb_arr;
		$data['jibing_parent'] = $jibing_parent;

		$from_list = $par_from_arr;
		$data['from_list'] = $from_list;
		$data['p_jb'] = $p_jb;  
		$data['jb'] = $jb;  
		$data['f_p_i'] = $from_parent_id;
		$data['f_i'] = $from_id;
		$hospital = $this->model->hospital_order_list();
		$data['hospital'] = $hospital;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;
		
		$w_start = strtotime($start . ' 00:00:00');
		$w_end = strtotime($end . ' 23:59:59');

		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,1,$tag_str); //统计到诊率
		$order_feibu = $this->model->order_count_data($w_start,$w_end,$where,1); //统计分布情况
		
		$order_jb = $this->model->order_count_data($w_start,$w_end,$where,6); //到诊病种分布
		$lin = 0;
		foreach($order_jb as $val){
		
			$lin += $val['count'];
		}
		$little = array();
		$big = array();
		$little_c = 0;
		foreach($order_jb as $val){
		
			if($val['count']<$lin*0.05){
				$little[] = $val;
				$little_c += $val['count'];
			}else{
				$big[] = $val;
			}
		}

		$data['little'] = $little;
		$data['big'] = $big;
		$data['little_c'] = $little_c;
		$data['order'] = $order_count;
		$data['feibu'] = $order_feibu;
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('miss_patient', $data);
		
		
	}
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
		public function miss_order()
		{
		$data = array();
		$data = $this->common->config('miss_order');
		
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
	
		
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		
		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;
		
		if(1 == $tj_lx){
		
			$tag_str = '%u';
		}else if(2 == $tj_lx){
			$tag_str = '%c';
		}else{
			$tag_str = '%d';
		}
		
		// 组装where条件
		$where = 1;
		
		
		
		if(!empty($keshi_id))
		{
			$where .= " AND o.keshi_id = ".$keshi_id;
		}
		if(!empty($hos_id))
		{
			$where .= " AND o.hos_id = ".$hos_id;
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
		
		
		//组装时间参数，以月为单位进行统计，默认显示当前月的数据
		
		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

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
		
		// end 时间组装
		
		// 预约来源
		$from_list = $this->model->from_order_list(-1);

		$par_from_arr = array();

		$from_arr = array();

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



		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;

		$from_list = $par_from_arr;
		$data['from_list'] = $from_list;
		$data['p_jb'] = $p_jb;  
		$data['jb'] = $jb;  
		$data['f_p_i'] = $from_parent_id;
		$data['f_i'] = $from_id;
		$hospital = $this->model->hospital_order_list();
		$data['hospital'] = $hospital;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;
		
		$w_start = strtotime($start . ' 00:00:00');
		$w_end = strtotime($end . ' 23:59:59');

		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,1,$tag_str); //统计到诊率
		//回访数统计
		$order_huifan = $this->model->order_count_data($w_start,$w_end,$where,2,1,$tag_str);
		$order_huifan_only = $this->model->order_count_data($w_start,$w_end,$where,7,1,$tag_str);
		
		
		$order_res = $this->model->order_count_data($w_start,$w_end,$where,3); // 

		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");
		foreach($dao_false as $val){
		
			$dao[$val['false_id']] = $val['false_name'];
		}
		$s = date('d',$w_start);
		$e = date('d',$w_end);
		$end = $e - $s +1;
		$hf = array();
		//数组长度
		$count = count($order_huifan);
		if($count<$end){
			$tag = array();
			$res = array();
			foreach($order_huifan as $val){
			
				$tag[] = intval($val['tag']);
			
				$res[intval($val['tag'])] = $val;
				
			}
			for($i=0;$i<$end;$i++){
		
			if(in_array($i+1,$tag)){
				$hf[$i] = $res[$i+1];
			
			}else{
			
			
			
				$hf[$i] = array('count'=>0,'come'=>0,'tag'=>$i+1);
			}
			}
		}else{
		
			$hf = $order_huifan;
		}
		$hf_o = array();
		$count = count($order_huifan_only);
		if($count<$end){
			$tag = array();
			$res = array();
			foreach($order_huifan_only as $val){
			
				$tag[] = intval($val['tag']);
			
				$res[intval($val['tag'])] = $val;
				
			}
			for($i=0;$i<$end;$i++){
		
			if(in_array($i+1,$tag)){
				$hf_o[$i] = $res[$i+1];
			
			}else{
			
			
			
				$hf_o[$i] = array('count'=>0,'oid'=>0,'come'=>0,'tag'=>$i+1);
			}
			}
		}else{
		
			$hf_o = $order_huifan_only;
		}
		
		$data['res'] = $order_res;
		$data['dao_false'] = $dao;
		$data['order_huifan'] = $hf;
		$data['order_huifan_only'] = $hf_o;
		$data['order'] = $order_count;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('miss_order', $data);
			
		}
		
		public function order_data()
		{
		$data = array();
		$data = $this->common->config('order_data');
	
		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
	
		
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		
		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;
		
		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;
		
		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;
		
		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;
		
		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;
		
		if(1 == $tj_lx){
		
			$tag_str = '%u';
		}else if(2 == $tj_lx){
			$tag_str = '%c';
		}else{
			$tag_str = '%d';
		}
		// 组装where条件
		$where = 1;
		
		
		
		if(!empty($keshi_id))
		{
			$where .= " AND o.keshi_id = ".$keshi_id;
		}
		if(!empty($hos_id))
		{
			$where .= " AND o.hos_id = ".$hos_id;
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
		
		
		//组装时间参数，以月为单位进行统计，默认显示当前月的数据
		
		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

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
		
		// end 时间组装
		
		// 预约来源
		$from_list = $this->model->from_order_list(-1);

		$par_from_arr = array();

		$from_arr = array();

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
		
		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();
		
		$jb_arr = array();

		if(!empty($jibing))

		{

			foreach($jibing as $val)

			{
				$jb_arr[$val['jb_id']] = $val['jb_name'];
			
				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];

				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];

				if($val['jb_level'] == 2)

				{

					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];

					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];

				}

			}

		}



		$data['jibing'] = $jibing_list;
		$data['jibing_parent'] = $jibing_parent;

		$from_list = $par_from_arr;
		$data['from_list'] = $from_list;
		$data['p_jb'] = $p_jb;  
		$data['jb'] = $jb;  
		$data['f_p_i'] = $from_parent_id;
		$data['f_i'] = $from_id;
		$hospital = $this->model->hospital_order_list();
		$data['hospital'] = $hospital;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;
		
		$w_start = strtotime($start . ' 00:00:00');
		$w_end = strtotime($end . ' 23:59:59');

		$order_list = $this->model->order_count_data($w_start,$w_end,$where,4,1,$tag_str); // 预定未定统计
		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,2,$tag_str); //遇到到诊统计
		//预约病种统计
		$order_jb = $this->model->order_count_data($w_start,$w_end,$where,5);	//病种统计
		$lin = 0;
		foreach($order_jb as $val){
		
			$lin += $val['count'];
		}
		$little = array();
		$big = array();
		$little_c = 0;
		foreach($order_jb as $val){
		
			if($val['count']<$lin*0.05){
				$little[] = $val;
				$little_c += $val['count'];
			}else{
				$big[] = $val;
			}
		}
		$data['little'] = $little;
		$data['big'] = $big;
		$data['little_c'] = $little_c;
		
		$data['order'] = $order_count;
		$data['order_list'] = $order_list;
		$data['jb_arr'] = $jb_arr;
		$data['order_jb'] = $order_jb;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('order_data', $data);
			
		}
		
		//对话登记
		public function talk_add()
		{
			$data = array();
			$data = $this->common->config('talk_add');
			$data['add_id'] = $_COOKIE['l_admin_id'];
			$data['add_name'] = $_COOKIE['l_admin_name'];
			
			//预约途径
			$from_list = $this->model->from_order_list(-1);

			$par_from_arr = array();

			$from_arr = array();

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
			
			
	
			$data['from_list'] = $from_list;
			$data['info'] = '';
			
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('talk_add', $data);
		}
		
		public function talk_update()
		{

			$admin_id = isset($_REQUEST['l_admin_id'])? intval($_REQUEST['l_admin_id']):0;
			
			$from_id = isset($_REQUEST['zx_from'])? intval($_REQUEST['zx_from']):0;
			
			$date = isset($_REQUEST['zx_data'])? strval($_REQUEST['zx_data']):'';
			
			$num = isset($_REQUEST['zx_num'])? intval($_REQUEST['zx_num']):0;
			
			$form_action = $_REQUEST['form_action'];
			
			$timetag = strtotime($date);
			$arr = array('admin_id' => $admin_id,
		             'from_id' => $from_id,
					 'date' => $timetag,
					 'num' => $num,
					 );
			if($form_action == 'add'){
			
				$sql = "select talk_id from " .$this->common->table('talk'). " where date = $timetag and admin_id = $admin_id";
				$row = $this->common->getOne($sql);
				if($row){
					$talk_id = $row;
					$this->db->update($this->db->dbprefix . "talk", $arr, array('talk_id' => $talk_id));
				}else{
				
				$this->db->insert($this->db->dbprefix . "talk", $arr);
				
				$talk_id = $this->db->insert_id();
				
				}
				
			}
			if($talk_id){
				$this->common->msg($this->lang->line('success'));
			}else{
			
				$this->common->msg('操作失败');
			}
		}
		
		public function zx_data()
		{
			$data = array();
			$data = $this->common->config('zx_data');
			$hospital = $this->common->get_hosname();
			$asker_list = $this->model->asker_list(1);
			
			$data['asker_list'] = $asker_list;
			
			$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
			
			$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):$hospital[0]['hos_id'];
			
			$sel = isset($_REQUEST['sel'])? strval($_REQUEST['sel']):'count';
			
			$data['hos_id'] = $hos_id;
			
			$data['sel'] = $sel;
			
			if(!empty($date))

			{

				$date = explode(" - ", $date);

				$start = $date[0];

				$end = $date[1];

				$data['start'] = $start;

				$data['end'] = $end;

				$start = str_replace(array("年", "月", "日"), "-", $start);

				$end = str_replace(array("年", "月", "日"), "-", $end);

				$start = substr($start, 0, -1);

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
			$w_start = strtotime($start . ' 00:00:00');
			$w_end = strtotime($end . ' 23:59:59');
			
			$st = intval(date('d',$w_start));
			
			$en = intval(date('d',$w_end));
			
			
			
			$data['hospital'] = $hospital;
			
			$sql = "select admin_name,hos_id,COUNT(*) AS count, SUM(IF(is_come, 1, 0)) AS come, SUM(IF(order_time=0,1,0)) AS Ncome,SUM(IF(order_time!=0,1,0)) AS Ycome, FROM_UNIXTIME(order_addtime, '%d' ) as tag from " .$this->common->table('order') . " where hos_id = " .$hos_id . " AND order_addtime >= " . $w_start . " AND order_addtime <= " . $w_end ." group by admin_name,tag";
			
			$info = $this->common->getAll($sql);
			
			$list = array();
			
			$null = array('count' => 0,'come' => 0,'Ncome' => 0,'Ycome' => 0);
			
			foreach($info as $val){
				$list[$val['admin_name']][intval($val['tag'])] = $val;	
			}
			$res = array();
			foreach($list as $key=>$val){
				for($i=$st; $i<=$en; $i++){
					if(array_key_exists($i,$val)){
					
						$res[$key][$i] = $val[$i];
					}else{
					
						$res[$key][$i] = $null;
					}
				}
			}
			$des = array();
			foreach($list as $key=>$val){
				$des[$key]['count'] = 0;
				$des[$key]['come'] = 0;
				$des[$key]['Ycome'] = 0;
				$des[$key]['Ncome'] = 0;
				foreach($val as $k=>$v){
				
					$des[$key]['count'] += $v['count'];
					$des[$key]['come'] += $v['come'];
					$des[$key]['Ycome'] += $v['Ycome'];
					$des[$key]['Ncome'] += $v['Ncome'];
				
				}
			
			
			}
			$sql_last = "select admin_name,hos_id,COUNT(*) AS count, SUM(IF(is_come, 1, 0)) AS come, SUM(IF(order_time=0,1,0)) AS Ncome,SUM(IF(order_time!=0,1,0)) AS Ycome from " .$this->common->table('order') . " where hos_id = " .$hos_id . " AND order_addtime >= " . $w_start . " AND order_addtime <= " . $w_end ." group by admin_name";
			
			$info_last = $this->common->getAll($sql_last);
			$count_s = 0;
			$come_s = 0;
			$Ycome_s = 0;
			foreach($info_last as $val){
				$count_s += $val['count'];
				$come_s += $val['come'];
				$Ycome_s += $val['Ycome'];
			}
			
			$data['info_last'] = $info_last;
			$data['count_s'] = $count_s;
			$data['come_s'] = $come_s;
			$data['Ycome_s'] = $Ycome_s;
			
			$data['st'] = $st;
			
			$data['en'] = $en;
			$data['res'] = $res;
			$data['des'] = $des;		

		

			
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('zx_data', $data);
		}
		
		public function text(){
			$this->load->helper('url');
			$base_url = base_url();
			// 获取订单no
			
			$curlPost1 = '';
			$ch1 = curl_init();//初始化curl

			curl_setopt($ch1,CURLOPT_URL,$base_url.'?c=order&m=order_no_ajax');//抓取指定网页

			curl_setopt($ch1, CURLOPT_HEADER, 0);//设置header

			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

			curl_setopt($ch1, CURLOPT_POST, 1);//post提交方式

			curl_setopt($ch1, CURLOPT_POSTFIELDS, $curlPost1);

			$data1 = curl_exec($ch1);//运行curl

			curl_close($ch1);

			$order_no = $data1;//输出结果
			// 医院 科室  医生名称 电话
			$keshi_id           = isset($_REQUEST['keshi_id'])? trim($_REQUEST['keshi_id']):'';
			
			$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):'';
			$doctor_name = isset($_REQUEST['doctor_name'])? strval($_REQUEST['doctor_name']):'';
			
			$pat_phone = isset($_REQUEST['pat_phone'])? strval($_REQUEST['pat_phone']):'';
			$pat_name = isset($_REQUEST['pat_name'])? strval($_REQUEST['pat_name']):'';
			
			$send_content = '测试';
		
			$curlPost = array('send_content'=>$send_content,'send_phone'=>$pat_phone,'hos_id'=>$hos_id,'keshi_id'=>$keshi_id);
			$ch = curl_init();//初始化curl

			curl_setopt($ch,CURLOPT_URL,$base_url.'?c=system&m=just_sms');//抓取指定网页

			curl_setopt($ch, CURLOPT_HEADER, 0);//设置header

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上

			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式

			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

			$data = curl_exec($ch);//运行curl

			curl_close($ch);
			
			if($data==0){
				
				$pat = array(	'pat_name' => $pat_name,

									'pat_phone' => $pat_phone,

									);
				$this->db->insert($this->common->table("patient"), $pat);					
				$pat_id = $this->db->insert_id();
				$remark = array('order_no' => $order_no,

									'keshi_id' => $keshi_id,

									'hos_id' => $hos_id,

									'doctor_name' => $doctor_name,
									
									'pat_id' => $pat_id,
                                                                        'order_addtime' => time(),

									);

				$this->db->insert($this->common->table("order"), $remark);
				
				$order_id = $this->db->insert_id();
				
				if($order_id){
					$msg_detail = "短信预约成功！";
				}else{
					$msg_detail = "短信预约失败！";
				}
				$links[0] = array('href' => '?c=order&m=doc_show', 'text' => $this->lang->line('list_back'));
				$this->common->msg($msg_detail, 0, $links, true, false);
			}
			
			
		}
		
		public function doc_show(){
			
			$this->load->view('doc_show');
		
		}
				public function pat_order(){
			
			$enter_id = isset($_REQUEST['pat_id'])? intval($_REQUEST['pat_id']):0;
			$info = $this->common->getRow("select * from " . $this->common->table('pat_enter') . " where enter_id = ". $enter_id);
			$data = array();
			$data = $this->common->config('pat_order');
			$keshi = read_static_cache('keshi_list_true');
			if(empty($keshi)){
			$keshi_list = array(
				array(keshi_id=>1,keshi_name=>'炎症'),
				array(keshi_id=>2,keshi_name=>'计生'),
				array(keshi_id=>3,keshi_name=>'不孕'),
				array(keshi_id=>4,keshi_name=>'产科'),
				array(keshi_id=>5,keshi_name=>'内科'),
				array(keshi_id=>6,keshi_name=>'外科'),
				array(keshi_id=>7,keshi_name=>'男科'),
				array(keshi_id=>8,keshi_name=>'皮肤科'),
				array(keshi_id=>9,keshi_name=>'肛肠科'),
				array(keshi_id=>10,keshi_name=>'五官科'),
				array(keshi_id=>11,keshi_name=>'皮肤美容科'),
				array(keshi_id=>12,keshi_name=>'儿科'),
				array(keshi_id=>13,keshi_name=>'消化科'),
				array(keshi_id=>13,keshi_name=>'口腔科')
			);
			
			write_static_cache('keshi_list_true',$keshi_list);
			$keshi = $keshi_list;
			}
			
			$data['keshi'] = $keshi;
			$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
			$data['info'] = $info;
			$data['province'] = $province;
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('pat_order', $data);
		
		}
		
		public function pat_update(){
		
		
			
			$form_action     = $_REQUEST['form_action'];

			$enter_id        = isset($_REQUEST['enter_id'])? intval($_REQUEST['enter_id']):0;
			

			$remark          = trim($_REQUEST['remark']);


			$order_no        = trim($_REQUEST['order_no']);
			
			$keshi_id        = intval($_REQUEST['keshi_id']);
			
			$jb_parent_id    = trim($_REQUEST['jibing_parent_id']);

			
			
			$admin_id        = trim($_REQUEST['admin_id']);

			$from_parent_id  = trim($_REQUEST['from_parent_id']);

			$from_id         = trim($_REQUEST['from_id']);

			$is_first        = intval($_REQUEST['is_first']);

			

			$pat_name        = trim($_REQUEST['patient_name']);

			$pat_sex         = intval($_REQUEST['pat_sex']);

			$pat_phone       = trim($_REQUEST['patient_phone']);

			$pat_age         = trim($_REQUEST['patient_age']);

			

			

			$pat_province    = intval($_REQUEST['province']);

			$pat_city        = intval($_REQUEST['city']);

			$pat_area        = intval($_REQUEST['area']);

			$pat_address     = trim($_REQUEST['patient_address']);
			$doctor_name     = trim($_REQUEST['doctor_name']);

			
			$data = array(
						'order_no' => $order_no,  //预约号

						'keshi_id' => $keshi_id, //科室id

						'jb_parent_id' => $jb_parent_id,  //大病种
					
						
						//预约途径
						
						'from_parent_id' => $from_parent_id,
						'from_id' => $from_id,
						
						'admin_id' => $admin_id,
						'pat_name' => $pat_name,
						'pat_sex' => $pat_sex,
						'pat_phone' => $pat_phone,
						'pat_age' => $pat_age,
						
						'is_first' => $is_first,
						

						'doctor_name' => $doctor_name,
									
						'pat_province' => $pat_province,
									
						'pat_city'=>$pat_city,
						'pat_area'=>$pat_area,
						'pat_address'=>$pat_address,
						'remark'=>$remark,
						'add_time' =>time()

									);
			if($form_action=='update'){
				$links[0] = array('href' => '?c=order&m=pat_list', 'text' => $this->lang->line('list_back'));
				$pat_id = $this->db->update($this->common->table("pat_enter"), $data, array('enter_id' => $enter_id));
				if($pat_id){
			
				$this->common->msg('病人信息更改成功',0,$links);
				}
			}elseif($form_action=='add'){ 
				$pat_id = $this->db->insert($this->common->table("pat_enter"), $data);
				if($pat_id){
			
				$this->common->msg('病人信息登记成功');
				}
				
			}
		
		}
		
		public function pat_list(){
		
			$data = array();

			$data           = $this->common->config('pat_list');
		

		$keshi_id       = isset($_REQUEST['keshi_id'])? trim($_REQUEST['keshi_id']):'';
	
		
		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
		
		$date_get 		= $date;
		
		$from_parent_id = isset($_REQUEST['f_p_i'])? trim($_REQUEST['f_p_i']):0;
		
		$from_id        = isset($_REQUEST['f_i'])? trim($_REQUEST['f_i']):0;
		
		$p_jb           = isset($_REQUEST['p_jb'])? trim($_REQUEST['p_jb']):'';
		
		$patient_name           = isset($_REQUEST['patient_name'])? trim($_REQUEST['patient_name']):'';
		$admin_id           = isset($_REQUEST['admin_id'])? trim($_REQUEST['admin_id']):'';
		$patient_phone           = isset($_REQUEST['patient_phone'])? trim($_REQUEST['patient_phone']):'';
		$doctor_name           = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';
		$order_no           = isset($_REQUEST['order_no'])? trim($_REQUEST['order_no']):'';
		
		// 组装where条件
		$where = 1;
		
		
		
		if(!empty($keshi_id))
		{
			$where .= " AND keshi_id = '".$keshi_id."'";
			$data['keshi_id'] = $keshi_id;
		}
		if(!empty($patient_name))
		{
			$where .= " AND pat_name = '".$patient_name."'";
			$data['patient_name'] = $patient_name;
		}
		if(!empty($doctor_name))
		{
			$where .= " AND doctor_name = '".$doctor_name."'";
			$data['doctor_name'] = $doctor_name;
		}
		if(!empty($order_no))
		{
			$where .= " AND order_no = '".$order_no."'";
			$data['order_no'] = $order_no;
		}
		if(!empty($patient_phone))
		{
			$where .= " AND pat_phone = ".$patient_phone;
			$data['patient_phone'] = $patient_phone;
		}
		if(!empty($admin_id))
		{
			$where .= " AND admin_id = '".$admin_id."'";
			$data['admin_id'] = $admin_id;
		}
		if(!empty($from_parent_id))

		{

			$where .= " AND from_parent_id = '". $from_parent_id."'";
			$data['from_parent_id'] = $from_parent_id;

		}

		if(!empty($from_id))

		{

			$where .= " AND from_id = '". $from_id."'";
			$data['from_id'] = $from_id;

		}	

	
	
		

		if(!empty($p_jb))

		{

			$where .= " AND jb_parent_id = '". $p_jb."'";
			$data['p_jb'] = $p_jb;
		}

		
		
		
		//组装时间参数，以月为单位进行统计，默认显示当前月的数据
		
		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

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
		
		$w_start = strtotime($start . ' 00:00:00');
		$w_end = strtotime($end . ' 23:59:59');
		
		// end 时间组装
		
			$keshi = read_static_cache('keshi_list_true');
			$keshi_list = array();
			foreach($keshi as $val){
				$keshi_list[$val['keshi_id']] = $val['keshi_name'];
			
			
			}
			$data['keshi'] = $keshi;
			$data['keshi_list'] = $keshi_list;
			$data['f_p_i'] = $from_parent_id;
			$data['f_i'] = $from_id;
			$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

			$data['now_page'] = $page;

			$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

			$per_page = empty($per_page)? 30:$per_page;

			$this->load->library('pagination');

			$this->load->helper('page');
			$pat_list_count = $this->model->pat_list_count($w_start,$w_end,$where);
			
			$config = page_config();
			$config['base_url'] = '?c=order&m=pat_list&keshi_id='.$keshi_id.'&f_p_i='.$from_parent_id.'&f_i='.$from_id.'&p_jb='.$p_jb.'&date='.$date_get.'&patient_name='.$patient_name.'&admin_id='.$admin_id.'&patient_phone='.$patient_phone.'&doctor_name='.$doctor_name.'&order_no='.$order_no;
			$config['total_rows'] = $pat_list_count;

			$config['per_page'] = $per_page;

			$config['uri_segment'] = 10;

			$config['num_links'] = 5;
			
			$this->pagination->initialize($config);
			
			$pat_list = $this->model->pat_list($w_start,$w_end,$where, $page, $per_page);
			$data['page'] = $this->pagination->create_links();
			
			
			$data['pat_list'] = $pat_list;
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('pat_list', $data);
		
		}
		private function create_card($img,$card_content,$name='')
		{
			
			if (!file_exists('static/upload/'.$img)) {
				return false;
			}
			$this->load->library('image_lib');
			$this->load->helper('url');
			$last = count($card_content)-1;
			if(empty($name)){
				$tmp = md5(uniqid(mt_rand()));
			}else{
				$tmp = $name;
			}
			foreach($card_content as $key=>$val){
				$config = array();
				if($key == 0){
					$config['image_library'] = 'gd2';
					$config['source_image'] = 'static/upload/'.$img;
					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';
					$config['wm_text'] = $val[0];
					$config['wm_font_size'] = '10';
					$config['wm_font_color'] = '070707';
					$config['wm_vrt_alignment'] = 'top';
					$config['wm_hor_alignment'] = 'left';
					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）  
					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）
					$config['new_image'] = 'static/images/ok.jpg';
				}else if($key == $last){
					$config['image_library'] = 'gd2';
					$config['source_image'] = 'static/images/ok.jpg';
					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';
					$config['wm_text'] = $val[0];
					$config['wm_font_size'] = '10';
					$config['wm_font_color'] = '070707';
					$config['wm_vrt_alignment'] = 'top';
					$config['wm_hor_alignment'] = 'left';
					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）  
					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）
					// 生成最终的图片名称
					$config['new_image'] = 'static/images/'.$tmp.'.jpg';
				}else{
					$config['image_library'] = 'gd2';
					$config['source_image'] = 'static/images/ok.jpg';
					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';
					$config['wm_text'] = $val[0];
					$config['wm_font_size'] = '10';
					$config['wm_font_color'] = '070707';
					$config['wm_vrt_alignment'] = 'top';
					$config['wm_hor_alignment'] = 'left';
					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）  
					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）
					$config['new_image'] = 'static/images/ok.jpg';
				}
				$this->image_lib->initialize($config); 
				$this->image_lib->watermark();
			}
			if(file_exists('static/images/'.$tmp.'.jpg')){
				$html = base_url().'static/images/'.$tmp.'.jpg';
				return $html;
			}else{
				return false;
			}
		}
		/*
		* 与phpcms交互
		*
		*/
		private function register($register)
		{
			$url = 'http://www.ra120.com/api.php';
			$code = sys_auth('action=number_add&username='.$register['username'].'&nickname='.$register['nickname'].'&password='.$register['password'],'ENCODE','c7FfDNAI8B7NEvd708LakK0AuSMHvhrU');
			$url .= '?op=uc&code='.$code;
			https_get($url);
		}
		private function group_change($username)
		{
			$url = 'http://www.ra120.com/api.php';
			$code = sys_auth('action=group_change&username='.$username,'ENCODE','c7FfDNAI8B7NEvd708LakK0AuSMHvhrU');
			$url .= '?op=uc&code='.$code;
			https_get($url);
		}
}