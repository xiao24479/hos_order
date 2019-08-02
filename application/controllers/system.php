<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 系统设置
class System extends CI_Controller
{
	var $model;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('System_model');
		$this->lang->load('system');
		$this->lang->load('common');

		$this->model = $this->System_model;
	}

	public function hospital()
	{
		$data = array();
		$data = $this->common->config('hospital');

		$hos_list = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY CONVERT(hos_name USING gbk) asc");

		$data['hos_list'] = $hos_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('hospital', $data);
	}

	public function hospital_info()
	{
		$data = array();
		$hos_id = empty($_REQUEST['hos_id'])? 0:intval($_REQUEST['hos_id']);

		if(empty($hos_id))
		{
			$data = $this->common->config('hospital_add');
		}
		else
		{
			$data = $this->common->config('hospital_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('hospital') ." WHERE hos_id = $hos_id");
			$data['info'] = $info;
		}

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('hospital_info', $data);
	}

	public function hospital_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$hos_id          = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$ask_auth          = !empty($_REQUEST['ask_auth'])? intval($_REQUEST['ask_auth']):0;
		$hos_name        = trim($_REQUEST['hos_name']);
		$hos_time        = trim($_REQUEST['hos_time']);
		$hos_address     = trim($_REQUEST['hos_address']);
		$hos_tel         = trim($_REQUEST['hos_tel']);
		$hos_website     = trim($_REQUEST['hos_website']);
		$sms_int         = trim($_REQUEST['sms_int']);
		$sms_name        = trim($_REQUEST['sms_name']);
		$sms_pwd         = trim($_REQUEST['sms_pwd']);
		$hos_pos         = trim($_REQUEST['hos_pos']);
		$hos_swt         = trim($_REQUEST['hos_swt']);
		$hos_slug        = trim($_REQUEST['hos_slug']);
		$hos_time        = strtotime($hos_time);
		$yuyuka	= trim($_REQUEST['yuyuka']);
		if(empty($hos_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$arr = array('hos_name' => $hos_name,
		             'hos_time' => $hos_time,
					 'hos_address' => $hos_address,
					 'hos_tel' => $hos_tel,
					 'hos_pos' => $hos_pos,
					 'hos_swt' => $hos_swt,
					 'hos_slug' => $hos_slug,
					 'ask_auth'=> $ask_auth,
					 'hos_website' => $hos_website,
					 'sms_name' => $sms_name,
					 'sms_int' => $sms_int,
					 'yuyuka' => $yuyuka,
					 'sms_pwd' => $sms_pwd);
		if($form_action == 'update')
		{
			if(empty($hos_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('hospital_edit');
			$this->db->update($this->common->table("hospital"), $arr, array('hos_id' => $hos_id));

			/**

			* 推送数据到数据中心

			* **/

			// $send_array =array();

			// $send_data = array();

			// $send_data['hos_id'] = $hos_id;

			// $send_data['ireport_hos_id'] = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('hospital') . " WHERE hos_id = ".$hos_id);;

			// $send_data['hos_name'] = $hos_name;

			// $send_array[] =$send_data;

			// //$this->sycn_hospital_data_to_ireport($send_array);
			// //剔除仁爱数据推送
			// if ($hos_id != '1') {
			// 	$this->sycn_hospital_data_to_ireport($send_array);
			// }

		}
		else
		{
			$this->common->config('hospital_add');

			$this->db->insert($this->common->table("hospital"), $arr);
			$hos_id = $this->db->insert_id();

			/**

			* 推送数据到数据中心

			* **/

			// $send_array =array();

			// $send_data = array();

			// $send_data['hos_id'] = $hos_id;

			// $send_data['ireport_hos_id'] =0;

			// $send_data['hos_name'] = $hos_name;

			// $send_array[] =$send_data;

			// //$this->sycn_hospital_data_to_ireport($send_array);
			// //剔除仁爱数据推送
			// if ($hos_id != '1') {
			// 	$this->sycn_hospital_data_to_ireport($send_array);
			// }

		}
		$this->common->static_cache('delete', "hospital/" . $hos_id);
		$links[0] = array('href' => '?c=system&m=hospital_info&hos_id=' . $hos_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=hospital_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=hospital', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function keshi_info()
	{
		$data = array();
		$keshi_id = empty($_REQUEST['keshi_id'])? 0:intval($_REQUEST['keshi_id']);

		if(empty($keshi_id))
		{
			$data = $this->common->config('keshi_add');
		}
		else
		{
			$data = $this->common->config('keshi_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('keshi') ." WHERE keshi_id = $keshi_id");
			$keshi_jibing = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " WHERE keshi_id = $keshi_id");
			$jibing_list = array();
			foreach($keshi_jibing as $val)
			{
				$jibing_list[] = $val['jb_id'];
			}
			$data['jibing_list'] = $jibing_list;
			$data['info'] = $info;
		}
		$jibing = $this->common->getAll("SELECT * FROM " . $this->common->table('jibing') . " WHERE parent_id = 0 ORDER BY jb_order ASC, jb_id DESC");
		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY CONVERT(hos_name USING gbk) asc");

		$data['hospital'] = $hospital;
		$data['jibing'] = $jibing;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('keshi_info', $data);
	}

	public function keshi_sex_get_ajax()
	{
		$keshi_id        = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$sex = $this->common->getOne("SELECT sex FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$keshi_id);
		echo $sex;exit;

	}

	public function keshi_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$keshi_id        = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$keshi_name      = trim($_REQUEST['keshi_name']);
		$hos_id          = trim($_REQUEST['hos_id']);
		$parent_id       = trim($_REQUEST['parent_id']);
		$keshi_order     = trim($_REQUEST['keshi_order']);
		$weiding     = trim($_REQUEST['weiding']);
		$jb              = $_REQUEST['jb'];
		$sex     = trim($_REQUEST['pat_sex']);
		if(empty($keshi_name) || empty($hos_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$keshi_level = $this->common->getOne("SELECT keshi_level FROM " . $this->common->table('keshi') . " WHERE keshi_id = $parent_id");
		$keshi_level = $keshi_level + 1;
		$arr = array('keshi_name' => $keshi_name,
		             'hos_id' => $hos_id,
					 'parent_id' => $parent_id,
					 'keshi_order' => $keshi_order,
					 'sex' => $sex,
					 'weiding' => $weiding,
					 'keshi_level' => $keshi_level);

		if($form_action == 'update')
		{
			if(empty($keshi_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('keshi_edit');

			$this->db->update($this->common->table("keshi"), $arr, array('keshi_id' => $keshi_id));

			/**
			 * 推送数据到数据中心
			 * **/
			// $send_array =array();
			// $send_data = array();
			// $send_data['keshi_id'] = $keshi_id;
			// $send_data['ireport_hos_id'] = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$keshi_id);;
			// $send_data['keshi_name'] = $keshi_name;
			// $hos_name = $this->common->getOne("SELECT hos_name FROM " . $this->common->table('hospital') . " WHERE hos_id = ".$hos_id);
			// $send_data['hos_name'] = $hos_name;
			// $send_array[] =$send_data;
			// //$this->sycn_keshi_data_to_ireport($send_array);

			// //剔除仁爱数据推送
			// if ($hos_id != '1') {
			// 	$this->sycn_keshi_data_to_ireport($send_array);
			// }

			//如果科室推送成功 则继续推送科室选择的疾病
			// $keshi_and_jb_data = array();
			// $keshi_and_jb_data['hospital_id'] = $send_data['ireport_hos_id'];
			// $ireport_jb_id_str = '0';
			// foreach($jb as $val)
			// {
			// 	$ireport_jb_id = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$val);;
			// 	if(!empty($ireport_jb_id)){
			// 		if(empty($ireport_jb_id_str)){
			// 			$ireport_jb_id_str = $ireport_jb_id;
			// 		}else{
			// 			$ireport_jb_id_str = $ireport_jb_id_str.','.$ireport_jb_id;
			// 		}
			// 	}
			// }
			// if(!empty($ireport_jb_id_str)){
			// 	$keshi_and_jb_data['disease_id_str'] = $ireport_jb_id_str;
			// 	//$this->sycn_keshi_and_jb_data_to_ireport($keshi_and_jb_data);
			// 	//剔除仁爱数据推送
			// 	if ($hos_id != '1') {
			// 		$this->sycn_keshi_and_jb_data_to_ireport($keshi_and_jb_data);
			// 	}
			// }
		}
		else
		{
			$this->common->config('keshi_add');

			$this->db->insert($this->common->table("keshi"), $arr);
			$keshi_id = $this->db->insert_id();

			/**
			 * 推送数据到数据中心
			 * **/
			// $send_array =array();
			// $send_data = array();
			// $send_data['keshi_id'] = $keshi_id;
			// $send_data['ireport_hos_id'] =0;
			// $send_data['keshi_name'] = $keshi_name;
			// $hos_name = $this->common->getOne("SELECT hos_name FROM " . $this->common->table('hospital') . " WHERE hos_id = ".$hos_id);
			// $send_data['hos_name'] = $hos_name;
			// $send_array[] =$send_data;
			// //$this->sycn_keshi_data_to_ireport($send_array);

			// //剔除仁爱数据推送
			// if ($hos_id != '1') {
			// 	$this->sycn_keshi_data_to_ireport($send_array);
			// }

			//如果科室推送成功 则继续推送科室选择的疾病
			// $keshi_and_jb_data = array();
			// $keshi_and_jb_data['hospital_id'] =   $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$keshi_id);
			// $ireport_jb_id_str = '0';
			// foreach($jb as $val)
			// {
			// 	$ireport_jb_id = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$val);;
			// 	if(!empty($ireport_jb_id)){
			// 		if(empty($ireport_jb_id_str)){
			// 			$ireport_jb_id_str = $ireport_jb_id;
			// 		}else{
			// 			$ireport_jb_id_str = $ireport_jb_id_str.','.$ireport_jb_id;
			// 		}
			// 	}
			// }
			// if(!empty($ireport_jb_id_str)){
			// 	$keshi_and_jb_data['disease_id_str'] = $ireport_jb_id_str;
			// 	//$this->sycn_keshi_and_jb_data_to_ireport($keshi_and_jb_data);
			// 	//剔除仁爱数据推送
			// 	if ($hos_id != '1') {
			// 		$this->sycn_keshi_and_jb_data_to_ireport($keshi_and_jb_data);
			// 	}
			// }
		}

		$this->db->delete($this->common->table('keshi_jibing'), array('keshi_id' => $keshi_id));
		foreach($jb as $val)
		{
			$this->db->insert($this->common->table('keshi_jibing'), array('keshi_id' => $keshi_id, 'jb_id' => $val));
		}

		$this->common->static_cache('delete', "keshi/" . $hos_id);
		$links[0] = array('href' => '?c=system&m=keshi_info&keshi_id=' . $keshi_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=keshi_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=hospital', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	/*public function keshi_del()
	{
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		if(empty($keshi_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$hav_child = $this->common->getOne("SELECT keshi_id FROM " . $this->common->table('keshi') . " WHERE parent_id = $keshi_id LIMIT 1");

		if($hav_child)
		{
			$this->common->msg($this->lang->line('keshi_hav_child'), 1);
		}
		$hos_id = $this->common->getOne("SELECT hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = $keshi_id");
		$this->db->delete($this->common->table('keshi'), array('keshi_id' => $keshi_id));

		$this->common->static_cache('delete', "keshi/" . $hos_id);

		$links[0] = array('href' => '?c=system&m=hospital', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}*/

	/**检查科室是否可以 未定预到时间*/
	public function ajax_keshi_weiding()
	{
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		if(empty($keshi_id)){
			echo -1;exit;
		}
		$keshi_list = $this->common->getAll("SELECT weiding FROM " . $this->common->table('keshi') . " where keshi_id = ".$keshi_id);
		if(count($keshi_list) > 0 ){
			echo $keshi_list[0]['weiding'];exit;
		}
		echo -1;exit;
	}

	public function jibing()
	{
		$data = array();
		$data = $this->common->config('jibing');

		$jibing = $this->common->static_cache('read', "jibing_list", 'jibing_list');
		$jibing = $this->common->static_cache('read', "jibing", 'jibing');
		$format_str = '<tr><td>%s</td><td>%s%s</td><td>%s</td><td>%s</td><td><button class="btn btn-primary" onClick="go_url(\'?c=system&m=jibing_info&jb_id=%s\')"><i class="icon-pencil"></i></button>&nbsp;<button class="btn btn-danger" onClick="go_del(\'?c=system&m=jibing_del&jb_id=%s\')"><i class="icon-trash"></i></button></td></tr>';
		$jibing_list = options($jibing, 'jb_id', 'jb_name', 'child', '<span class="td_child"></span>', '', 'jibing_list', $format_str, 0, 'jb_level');

		$data['jibing'] = $jibing_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('jibing', $data);
	}

	public function jibing_info()
	{
		$data = array();
		$jb_id = empty($_REQUEST['jb_id'])? 0:intval($_REQUEST['jb_id']);

		if(empty($jb_id))
		{
			$data = $this->common->config('jibing_add');
			$jibing = $this->common->static_cache('read', "jibing", 'jibing');
			$jibing_list = options($jibing, 'jb_id', 'jb_name', 'child', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '', 'option', '', 0, 'jb_level');
		}
		else
		{
			$data = $this->common->config('jibing_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('jibing') . " WHERE jb_id = $jb_id");
			$jibing = $this->common->static_cache('read', "jibing", 'jibing');
			$jibing_list = options($jibing, 'jb_id', 'jb_name', 'child', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '', 'option', '', $info['parent_id'], 'jb_level');
			$data['info'] = $info;
		}

		$data['jibing'] = $jibing_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('jibing_info', $data);
	}

	public function jibing_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$jb_id           = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;
		$jb_name         = trim($_REQUEST['jb_name']);
		$jb_code         = trim($_REQUEST['jb_code']);
		$parent_id       = trim($_REQUEST['parent_id']);
		$jb_order        = trim($_REQUEST['jb_order']);

		if(empty($jb_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$jb_level = $this->common->getOne("SELECT jb_level FROM " . $this->common->table('jibing') . " WHERE jb_id = $parent_id");
		$jb_level = $jb_level + 1;
		$arr = array('jb_name' => $jb_name,
					 'jb_code' => $jb_code,
					 'parent_id' => $parent_id,
					 'jb_order' => $jb_order,
					 'jb_level' => $jb_level);

		if($form_action == 'update')
		{
			if(empty($jb_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('jibing_edit');

			$this->db->update($this->common->table("jibing"), $arr, array('jb_id' => $jb_id));

			/**
			 * 推送数据到数据中心
			 * **/
			// $send_array =array();
			// $send_data = array();
			// $send_data['jb_id'] = $jb_id;
			// $ireport_jb = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$jb_id);
			// if(empty($ireport_jb)){
			// 	$ireport_jb =0 ;
			// }
			// $send_data['ireport_jb_id'] =$ireport_jb;
			// $send_data['jb_name'] = $jb_name;
			// $send_data['jb_code'] = $jb_code;
			// if(empty($parent_id)){
			// 	$parent_id =0 ;
			// }
			// $ireport_jb = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$parent_id);
			// $send_data['parent_id'] = 0;
			// if(!empty($ireport_jb)){
			// 	$send_data['parent_id'] = $ireport_jb;
			// }
			// $send_array[] =$send_data;
			// $this->sycn_jb_data_to_ireport($send_array);
		}
		else
		{
			$this->common->config('jibing_add');

			$this->db->insert($this->common->table("jibing"), $arr);
			$jb_id = $this->db->insert_id();

			/**
			 * 推送数据到数据中心
			 * **/
			// $send_array =array();
			// $send_data = array();
			// $send_data['jb_id'] = $jb_id;
			// $send_data['ireport_jb_id'] =0;
			// $send_data['jb_name'] = $jb_name;
			// $send_data['jb_code'] = $jb_code;
			// if(empty($parent_id)){
			// 	$parent_id =0 ;
			// }
			// $ireport_jb = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$parent_id);
			// $send_data['parent_id'] = 0;
			// if($ireport_jb > 0){
			// 	$send_data['parent_id'] = $ireport_jb;
			// }
			// $send_array[] = $send_data;
			// $this->sycn_jb_data_to_ireport($send_array);
		}
		$this->common->static_cache('delete', "jibing");
		$this->common->static_cache('delete', "jibing_list");
		$this->common->static_cache('read', "jibing_list", 'jibing_list');
		$this->common->static_cache('read', "jibing", 'jibing');
		$links[0] = array('href' => '?c=system&m=jibing_info&jb_id=' . $jb_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=jibing_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=jibing', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function jibing_del()
	{
		$this->common->config('jibing');
		$jb_id = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;
		if(empty($jb_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$hav_child = $this->common->getOne("SELECT jb_id FROM " . $this->common->table('jibing') . " WHERE parent_id = $jb_id LIMIT 1");

		if($hav_child)
		{
			$this->common->msg($this->lang->line('jibing_hav_child'), 1);
		}

		// $ireport_id = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$jb_id);
		// if(!empty($ireport_id)){
		// 	/**
		// 	 * 推送数据到数据中心
		// 	 * **/
		// 	$send_data = array();
		// 	$send_data['ireport_jb_id'] = $ireport_id;
		// 	$this->sycn_del_jb_data_to_ireport($send_data);
		// }

		$this->db->delete($this->common->table('jibing'), array('jb_id' => $jb_id));
		$this->common->static_cache('delete', "jibing");
		$this->common->static_cache('delete', "jibing_list");

		$this->common->static_cache('read', "jibing_list", 'jibing_list');
		$this->common->static_cache('read', "jibing", 'jibing');
		$links[0] = array('href' => '?c=system&m=jibing', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function google_analytics()
	{
		$data = array();
		$data = $this->common->config('google_analytics');
		$analytics = $this->common->getAll("SELECT * FROM " . $this->common->table('google_analytics') . " ORDER BY ana_id DESC");
		$data['analytics'] = $analytics;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('google_analytics', $data);
	}

	public function analytics_update()
	{
		$this->common->config('google_analytics');
		$google_id    = trim($_REQUEST['google_id']);
		$project_id   = intval($_REQUEST['project_id']);
		$ana_id       = intval($_REQUEST['ana_id']);
		$is_pass       = intval($_REQUEST['is_pass']);
		$form_action  = $_REQUEST['form_action'];

		if(empty($google_id) || empty($project_id))
		{
			exit();
		}

		if($form_action == 'update')
		{
			if(empty($ana_id))
			{
				exit();
			}
			$this->db->update($this->common->table("google_analytics"), array('google_id' => $google_id, 'project_id' => $project_id, 'is_pass' => $is_pass), array('ana_id' => $ana_id));
		}
		else
		{
			$this->db->insert($this->common->table("google_analytics"), array('google_id' => $google_id, 'project_id' => $project_id, 'is_pass' => $is_pass));
		}
		header("location:?c=system&m=google_analytics\r\n");
	}

	public function analytics_del()
	{
		$ana_id = intval($_REQUEST['ana_id']);
		if(empty($ana_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->db->delete($this->common->table('google_analytics'), array('ana_id' => $ana_id));
		$links[0] = array('href' => '?c=system&m=google_analytics', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}


	public function sms_themes()
	{
		$data = array();
		$data = $this->common->config('sms_themes');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		$this->load->helper('page');
		$where = '1';
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
			if(!empty($hos_id))
			{
				$where .= " AND t.hos_id IN (" . $hos_id . ")";
			}
			if(!empty($keshi_id))
			{
				$where .= " AND t.keshi_id IN (" . $keshi_id . ")";
			}
		}else{
			if(!empty($hos_id))
			{
				$hos_data = explode(',',$_COOKIE["l_hos_id"]);
				$hos_check =0 ;
				foreach($hos_data as $hos_data_temp){
					if($hos_data_temp == $hos_id){
						$hos_check = 1;break;
					}
				}
				if($hos_check == 0){
					$where .= ' AND t.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}else{
					$where .= " AND t.hos_id IN (" . $hos_id . ")";
				}
			}else{
				$where .= ' AND t.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
			}
			if(!empty($keshi_id))
			{
				$keshi_data = explode(',',$_COOKIE["l_keshi_id"]);
				$keshi_check =0 ;
				foreach($keshi_data as $keshi_data_temp){
					if($keshi_data_temp == $keshi_id){
						$keshi_check = 1;break;
					}
				}
				if($keshi_check == 0){
					$where .= " AND t.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}else{
					$where .= " AND t.keshi_id IN (" . $keshi_id . ")";
				}
			}else{
				$where .= " AND t.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
		}
		$count = $this->model->themes_count($where);
		$config = page_config();
		$config['base_url'] = '?c=system&m=sms_themes&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id;
		$config['total_rows'] = $count;
		$config['per_page'] = 20;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
        if(!empty($_COOKIE["l_hos_id"]))
		{
			$data['hospital'] = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where  ask_auth = 0  AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")  ORDER BY CONVERT(hos_name USING gbk) asc");
		}else if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
			$data['hospital'] = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where  ask_auth = 0  ORDER BY CONVERT(hos_name USING gbk) asc");
		}else{
			$data['hospital'] = array();
		}
		if(!empty($hos_id))
		{
			if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
				$data['keshi'] = $this->common->getAll("SELECT * FROM " . $this->common->table('keshi') . " where hos_id = ".$hos_id);
			}else{
				$data['keshi'] = $this->common->getAll("SELECT * FROM " . $this->common->table('keshi') . " where hos_id = ".$hos_id." and keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")" );
			}
		}

		$themes = $this->model->themes($where, $page, $config['per_page']);
		$data['page'] = $this->pagination->create_links();
		$data['themes'] = $themes;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('sms_themes', $data);
	}

	public function sms_themes_info()
	{
		$data = array();
		$themes_id = empty($_REQUEST['themes_id'])? 0:intval($_REQUEST['themes_id']);
		if(empty($themes_id))
		{
			$data = $this->common->config('sms_themes_add');
		}
		else
		{
			$data = $this->common->config('sms_themes_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('sms_themes') . " WHERE themes_id = $themes_id");
			$data['info'] = $info;

		}

		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where hos_id in(".$_COOKIE["l_hos_id"].") ORDER BY CONVERT(hos_name USING gbk) asc");
		}else{
			$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY CONVERT(hos_name USING gbk) asc");
		}

		$data['hospital'] = $hospital;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('sms_themes_info', $data);
	}

	public function sms_themes_update()
	{
		$form_action  = $_REQUEST['form_action'];
		$themes_id     = isset($_REQUEST['themes_id'])? intval($_REQUEST['themes_id']):0;
		$themes_name   = isset($_REQUEST['themes_name'])? trim($_REQUEST['themes_name']):'';
		$hos_id  = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id  = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$themes_content   = isset($_REQUEST['themes_content'])? trim($_REQUEST['themes_content']):'';

		if(empty($themes_name) || empty($themes_content))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$arr = array('themes_name' => $themes_name,
					 'hos_id' => $hos_id,
					 'keshi_id' => $keshi_id,
					 'themes_content' => $themes_content);

		if($form_action == 'update')
		{
			if(empty($themes_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->db->update($this->common->table('sms_themes'), $arr, array('themes_id' => $themes_id));
		}
		else
		{
			$this->db->insert($this->common->table('sms_themes'), $arr);
			$themes_id = $this->db->insert_id();
		}
		$links[0] = array('href' => '?c=system&m=sms_themes_info&themes_id=' . $themes_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=sms_themes_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=sms_themes', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function sms_themes_del()
	{
		$themes_id = isset($_REQUEST['themes_id'])? intval($_REQUEST['themes_id']):0;
		if(empty($themes_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$this->db->delete($this->common->table('sms_themes'), array('themes_id' => $themes_id));
		$links[0] = array('href' => '?c=system&m=sms_themes', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function sms_send_log()
	{
		$data = array();
		$data = $this->common->config('sms_send_log');

		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		$where = 1;
		if(!empty($_COOKIE["l_hos_id"]))
		{
			$where .= ' AND s.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
		}

		if(!empty($_COOKIE["l_keshi_id"]))
		{
			$where .= " AND s.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
		}

		$log_count = $this->model->sms_log_count($where);
		$config = page_config();
		$config['base_url'] = '?c=system&m=sms_send_log';
		$config['total_rows'] = $log_count;
		$config['per_page'] = '20';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$data['sms_log_list'] = $this->model->sms_log_list($where, $page, $config['per_page']);

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('sms_send_log', $data);
	}

	public function sms_action()
	{
		$data = array();
		$data = $this->common->config('sms_action');
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where hos_id in(".$_COOKIE["l_hos_id"].") ORDER BY CONVERT(hos_name USING gbk) asc");
		}else{
			$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " ORDER BY CONVERT(hos_name USING gbk) asc");
		}
		$data['hospital'] = $hospital;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('sms_action', $data);
	}

	public function sms_send()
	{
		$send_id = isset($_REQUEST['send_id'])? intval($_REQUEST['send_id']):0;
		$this->load->helper(sms);
		if($send_id > 0)
		{
			$send_info = $this->common->getRow("SELECT * FROM " . $this->common->table('sms_send') . " WHERE send_id = $send_id");
			$hospital = $this->common->static_cache('read', "hospital/" . $send_info['hos_id'], 'hospital', array('hos_id' => $send_info['hos_id']));
			$func_name = 'sms_'.$hospital['sms_int'];
			if ( function_exists($func_name)){
				$msg = mb_convert_encoding($send_info['send_content'],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$send_status = $func_name($send_phone,$msg,$hospital['sms_name'],$hospital['sms_pwd']);

				$status = $this->lang->line('sms_send_status');
				if($send_status == 0)
				{
					$msg_detail = "短信发送成功！" . $send_status;
				}
				else
				{
					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";
				}
			}else{
				$msg_detail = "短信发送失败，失败原因为：没有设置发送短信的接口！";
				$send_status = 108;
			}
			$this->db->update($this->common->table('sms_send'), array('send_status' => $send_status), array('send_id' => $send_info['send_id']));
		}
		else
		{
			$send_content = isset($_REQUEST['send_content'])? trim($_REQUEST['send_content']):'';
			$send_phone   = isset($_REQUEST['send_phone'])? trim($_REQUEST['send_phone']):'';
			$hos_id       = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
			$keshi_id     = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

			if(empty($send_content) || empty($send_phone))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}

			$send_phone   = explode("\r\n", $send_phone);

			$send_id_arr  = array();
			foreach($send_phone as $key=>$val)
			{
				if(empty($val))
				{
					unset($send_phone[$key]);
				}
				else
				{
					$this->db->insert($this->common->table('sms_send'), array('send_content' => $send_content, 'send_time' => time(), 'send_type' => 2, 'send_phone' => $val, 'hos_id' => $hos_id, 'keshi_id' => $keshi_id, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name']));
					$send_id_arr[] = $this->db->insert_id();
				}
			}

			$send_phone   = implode(";", $send_phone);
			$hos_id       = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
			$keshi_id     = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
			$send_id_str = implode(",", $send_id_arr);
			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));
			$func_name = 'sms_'.$hospital['sms_int'];
			if ( function_exists($func_name)){
				$msg = mb_convert_encoding($send_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

				$send_status = sms_jianzhou($send_phone,$msg,$hospital['sms_name'],$hospital['sms_pwd']);
				$status = $this->lang->line('sms_send_status');
				if($send_status >= 0)
				{
					$msg_detail = "短信发送成功！" . $send_status;
					$send_status = 0;
				}
				else
				{
					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";
				}
			}else{
				$msg_detail = "短信发送失败，失败原因为：未设置短信接口！";
				$send_status = 108;
			}
			$sql = "UPDATE " . $this->common->table('sms_send') . " SET send_status = '" . $send_status . "' WHERE send_id IN ($send_id_str)";
			$this->db->query($sql);
		}

		$links[0] = array('href' => '?c=system&m=sms_send_log', 'text' => $this->lang->line('list_back'));
		$this->common->msg($msg_detail, 0, $links, true, false);
	}

	public function just_sms(){
		$send_content = isset($_REQUEST['send_content'])? trim($_REQUEST['send_content']):'';
		$send_phone   = isset($_REQUEST['send_phone'])? strval($_REQUEST['send_phone']):'';
		$hos_id       = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id     = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$this->load->helper(sms);
		$this->db->insert($this->common->table('sms_send'), array('send_content' => $send_content, 'send_time' => time(), 'send_type' => 4, 'send_phone' => $send_phone, 'hos_id' => $hos_id, 'keshi_id' => $keshi_id));
		$send_id_str = $this->db->insert_id();
		$send_status = sms_jianzhou($send_phone,$send_content);

			if($send_status >= 0)
			{

				$send_status = 0;
				$sql = "UPDATE " . $this->common->table('sms_send') . " SET send_status = '" . $send_status . "' WHERE send_id IN ($send_id_str)";
				$this->db->query($sql);

			}
			else
			{

				$sql = "UPDATE " . $this->common->table('sms_send') . " SET send_status = '" . $send_status . "' WHERE send_id IN ($send_id_str)";
				$this->db->query($sql);
			}

			echo $send_status;
	}

        public function analytics_ajax()
	{
		$ana_id = intval($_REQUEST['ana_id']);
		if(empty($ana_id))
		{
			exit();
		}
		$info = $this->common->getRow("SELECT * FROM " . $this->common->table('google_analytics') . " WHERE ana_id = $ana_id");
		echo json_encode($info);
	}
	public function into_auth()
	{
		echo '恶意访问，系统已拒绝';exit;
		$cookie_time = time() + 3600;
		setcookie('l_admin_id', "2", $cookie_time, "/");
		setcookie('l_admin_username', "test", $cookie_time, "/");
		setcookie('l_admin_name', "测试", $cookie_time, "/");
		setcookie('l_rank_id', "1", $cookie_time, "/");
		setcookie('l_admin_action', "all", $cookie_time, "/");
		header('Location: http://www.renaidata.com');
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
		$keshi_list = $this->common->getAll('select * from '.$this->common->table('keshi').' where hos_id = '.$hos_id.' order by is_rec desc,keshi_order desc');

		if(empty($keshi_list))
		{
			exit();
		}

		if($type == 1)
		{
			echo options($keshi_list, 'keshi_id', 'keshi_name', 'child', '&nbsp;&nbsp;&nbsp;&nbsp;', '', 'option', '', $check_id, 'keshi_level');
		}
		elseif($type == 2)
		{
			$format_str = '<tr class="keshi_%s"><td>&nbsp;&nbsp;&nbsp;<a href="javascript:;" onClick="get_doc(this, %s,%s)"><i class="icon-plus"></i>&nbsp;%s%s</a></td><td>&nbsp;</td><td><button class="btn btn-primary" onClick="go_url(\'?c=system&m=keshi_info&keshi_id=%s\')"><i class="icon-pencil"></i></button> <button class="btn btn-danger" onClick="go_del(\'?c=system&m=keshi_del&keshi_id=%s\')"><i class="icon-trash"></i></button> &nbsp;<button class="btn btn-danger" onClick="go_url(\'?c=system&m=keshi_rec&keshi_id=%s&check=%s\')">%s</button></td></tr>';
			$str = '';
			foreach($keshi_list as $val){
				if(!empty($val['ireport_hos_id'])){
					$val['keshi_name'] = $val['keshi_name']."____(已绑定数据中心)";
				}else if(empty($val['ireport_msg'])){
					$val['keshi_name'] = $val['keshi_name']."____(未绑定数据中心)";
				}else{
					$val['keshi_name'] = $val['keshi_name']."____(未绑定数据中心,".$val['ireport_msg'].")";
				}

				if($val['is_rec']){
					$str .= sprintf($format_str, $val['hos_id'],$val['hos_id'],$val['keshi_id'],'',$val['keshi_name'], $val['keshi_id'], $val['keshi_id'],$val['keshi_id'],1,'<i class="icon-thumbs-down"></i>');
				}else{
					$str .= sprintf($format_str, $val['hos_id'],$val['hos_id'],$val['keshi_id'],'',$val['keshi_name'], $val['keshi_id'], $val['keshi_id'],$val['keshi_id'],2,'<i class="icon-thumbs-up"></i>');
				}
			}
				echo $str;
		}
	}

	public function doc_list_ajax()
	{
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$check = isset($_REQUEST['check'])? intval($_REQUEST['check']):0;


		if($check){
			$docter = $this->common->getAll('select doc_name, doc_id ,is_rec from '.$this->common->table('docter'). ' where hos_id = '.$hos_id.' ORDER BY `hui_docter`.`is_rec` DESC, `hui_docter`.`doc_order` DESC');
			$format_str = '<option value="%s">%s</option>';
			$str = '<option value="">请选择医生...</option>';
			foreach($docter as $val){

				$str .= sprintf($format_str,$val['doc_id'],$val['doc_name']);

				if($doc_id==$val['doc_id']){

					$str .= sprintf('<option value="%s" selected >%s</option>',$val['doc_id'],$val['doc_name']);
				}


			}
			echo $str;exit();
		}
		$docter = $this->common->getAll('select doc_name, doc_id ,is_rec from '.$this->common->table('docter'). ' where hos_id = '.$hos_id.' and keshi_id = '.$keshi_id.' ORDER BY `hui_docter`.`is_rec` DESC , `hui_docter`.`doc_order` DESC');
		$format_str = '<tr class="doc_%s"><td><i class="icon-user-md"></i>&nbsp;%s%s</td><td>&nbsp;</td><td><button class="btn btn-primary" onClick="go_url(\'?c=system&m=docter_info&doc_id=%s\')"><i class="icon-pencil"></i></button> <button class="btn btn-danger" onClick="go_del(\'?c=system&m=docter_del&doc_id=%s\')"><i class="icon-trash"></i></button>  &nbsp;<button class="btn btn-danger" onClick="go_url(\'?c=system&m=docter_rec&doc_id=%s&check=%s\')">%s</button></td></tr>';
		$str = '';
		foreach($docter as $val){
			if($val['is_rec']){
				$str .= sprintf($format_str, $hos_id, '&nbsp;&nbsp;&nbsp;&nbsp;', $val['doc_name'], $val['doc_id'], $val['doc_id'],$val['doc_id'],1,'<i class="icon-thumbs-down"></i>');
			}else{
				$str .= sprintf($format_str, $hos_id, '&nbsp;&nbsp;&nbsp;&nbsp;', $val['doc_name'], $val['doc_id'], $val['doc_id'],$val['doc_id'],2,'<i class="icon-thumbs-up"></i>');
			}
		}
		echo $str;
	}

	public function docter_rec()
	{
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$check = isset($_REQUEST['check'])? intval($_REQUEST['check']):0;
		if(empty($doc_id) || empty($check))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		if($check == 1){

			$this->db->update($this->common->table("docter"), array('is_rec'=>0), array('doc_id' => $doc_id));
		}elseif($check == 2){

			$this->db->update($this->common->table("docter"), array('is_rec'=>1), array('doc_id' => $doc_id));
		}
		$links[0] = array('href' => '?c=system&m=docter', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function keshi_rec()
	{
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$check = isset($_REQUEST['check'])? intval($_REQUEST['check']):0;
		if(empty($keshi_id) || empty($check))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		if($check == 1){

			$this->db->update($this->common->table("keshi"), array('is_rec'=>0), array('keshi_id' => $keshi_id));
		}elseif($check == 2){

			$this->db->update($this->common->table("keshi"), array('is_rec'=>1), array('keshi_id' => $keshi_id));
		}
		$links[0] = array('href' => '?c=system&m=docter', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function area_ajax()
	{
		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;
		$parent_id = isset($_REQUEST['parent_id'])? intval($_REQUEST['parent_id']):1;
		echo "<option value=\"0\">" . $this->lang->line('please_select') . "</option>";
		if(empty($parent_id))
		{
			exit();
		}
		$sql = "SELECT region_id, region_name FROM " . $this->common->table('region') . " WHERE parent_id = '" . $parent_id . "'";
		$list = $this->common->getAll($sql);
		foreach($list as $val)
		{
			echo "<option value=\"" . $val['region_id'] . "\"";
			if($val['region_id'] == $check_id)
			{
				echo " selected ";
			}
			echo ">" . $val['region_name'] . "</option>";
		}
	}

	public function ajax_docter_list()
	{
		// 获取医院科室和科室下面的医生信息
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		if(empty($hos_id)) exit;
		$num = isset($_REQUEST['num'])? intval($_REQUEST['num']):0;
		// 查询该医院下面的医生关联科室信息
		if($keshi_id){
			$where = 'and keshi_id = '.$keshi_id;
		}
		$sql = 'select d.*,k.keshi_name from '.$this->common->table('docter').' d
				left join '.$this->common->table('keshi').' k on d.keshi_id = k.keshi_id
				where d.hos_id = '.$hos_id.' '.$where.' order by d.is_rec desc';
		$list = $this->common->getAll($sql);
		$data = array();
		foreach($list as $val){
			$val['doc_img'] = 'http://www.renaidata.com/static/upload/'.$val['doc_img'];
			if(!empty($val['setting'])){
				$setting = new_stripslashes($val['setting']);
				eval("\$setting = $setting;");
				foreach($setting as $k=>$v){
					$info[$key][$k] = $v;
				}
			}
			if($num==1){
				$data[$val['keshi_name']] = $val;
			}else{
				if(isset($data[$val['keshi_name']])&&$num>0&&count($data[$val['keshi_name']])>=$num) continue;
				$data[$val['keshi_name']][] = $val;
			}
		}
		$callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
		$tmp = json_encode($data);
		echo $callback.'('.$tmp.')';exit;
	}
	//通过id或者name搜索医生信息.一次拉取出多个医生信息
	public function docter_name_info()
	{
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$doc_name = isset($_REQUEST['doc_name'])? trim($_REQUEST['doc_name']):'';
		$list_name = isset($_REQUEST['list_name']) ? trim($_REQUEST['list_name']) : '';
		if($doc_id>0){
			$sql = 'select d.*,k.keshi_name from '.$this->common->table('docter').' d
			left join '.$this->common->table('keshi').' k on d.keshi_id = k.keshi_id
			where doc_id = '.$doc_id.' order by is_rec desc';
		}
		if(!empty($doc_name)){
			$sql = 'select * from '.$this->common->table('docter').' where doc_name = "'.$doc_name.'" order by is_rec desc';
		}
		if(!empty($sql)){
			$info = $this->common->getRow($sql);
			$info['doc_img'] = 'http://www.renaidata.com/static/upload/'.$info['doc_img'];
			if(!empty($val['setting'])){
				$setting = new_stripslashes($val['setting']);
				eval("\$setting = $setting;");
				foreach($setting as $k=>$v){
					$info[$key][$k] = $v;
				}
			}
		}
		if(!empty($list_name)){
			$arr = explode('_',$list_name);
			$str = '';
			foreach($arr as $val){
				$str .= '"'.$val.'",';
			}
			$str = substr($str,0,-1);
			$sql = 'select * from '.$this->common->table('docter').' where doc_name IN ('.$str.') order by is_rec desc';
			$info = $this->common->getAll($sql);
			foreach($info as $key=>$val){
				$info[$key]['doc_img'] = 'http://www.renaidata.com/static/upload/'.$val['doc_img'];
				//如果存在自定义键，加载它
				if(!empty($val['setting'])){
					$setting = new_stripslashes($val['setting']);
					eval("\$setting = $setting;");
					foreach($setting as $k=>$v){
						$info[$key][$k] = $v;
					}
				}
			}
		}
		$callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
		$tmp = json_encode($info);
		echo $callback.'('.$tmp.')';exit;
	}

	// 获取医院列表和科室列表
	public function ajax_hos_list()
	{
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$callback = isset($_GET['callback']) ? trim($_GET['callback']) : ''; //jsonp回调参数，必需
		//获取医院列表
		if(empty($hos_id)){
			$sql = 'select hos_id,hos_name,hos_address,hos_tel,hos_website from '.$this->common->table('hospital')." ORDER BY CONVERT(hos_name USING gbk) asc ";
		}else{
		// 获取科室列表
			$sql = 'select keshi_id ,keshi_name from '.$this->common->table('keshi').' where hos_id = '.$hos_id.' order by keshi_order desc';
		}
		$data = $this->common->getAll($sql);
		$tmp = json_encode($data);
		echo $callback.'('.$tmp.')';exit;
	}


	public function docter_info()
	{
		$data = array();

		$doc_id        = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;

		if(!empty($doc_id)){
			$data = $this->common->config('docter_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('docter') ." WHERE doc_id = $doc_id");
			$data['info'] = $info;
			$data['doc_id'] = $doc_id;
			$setting = new_stripslashes($info['setting']);
			eval("\$setting = $setting;");
			$data['setting'] = $setting;

		}else{

			$data = $this->common->config('docter_add');
			$hos_id = $this->input->get('hos_id',true);
			if(!empty($hos_id)){
				$data['hos_id'] = $hos_id;

			}
		}
		// 医院信息
		$hospital = $this->common->get_hosname();
		$data['hospital'] = $hospital;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('docter/docter_info', $data);

	}

	public function static_book_ajax()
	{
		$doc_id        = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		if(empty($doc_id)){
			exit();
		}
		$arr = $this->common->getAll('select * from '.$this->common->table('static_book').' where doc_id = '.$doc_id.' order by book_time,shang');
		echo json_encode($arr);
	}

	public function static_add_ajax()
	{
		$doc_id        = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$num        = isset($_REQUEST['num'])? intval($_REQUEST['num']):0;
		$book_time        = isset($_REQUEST['book_time'])? trim($_REQUEST['book_time']):'';
		$shang        = isset($_REQUEST['shang'])? trim($_REQUEST['shang']):'';
		$xia        = isset($_REQUEST['xia'])? trim($_REQUEST['xia']):'';
		if(empty($doc_id)||empty($num)||empty($book_time)||empty($shang)||empty($xia)){
			echo -1;
			exit();
		}
		$arr = array('周日','周一','周二','周三','周四','周五','周六');
		if(!in_array($book_time,$arr)){
			echo -2;
			exit();
		}else{
			$book_time = array_search($book_time,$arr);
		}
		$staus = $this->common->getOne('select book_id from '.$this->common->table('static_book').' where book_time = '.$book_time.' and shang = "'.$shang.'" and xia = "'.$xia.'" and num = '.$num.' and doc_id = '.$doc_id);
		if(empty($staus)){
			$insert = array(
				'doc_id' => $doc_id,
				'num' => $num,
				'shang' => $shang,
				'xia' => $xia,
				'book_time' => $book_time
			);
			$this->db->insert($this->common->table("static_book"), $insert);
			$id = $this->db->insert_id();
			echo $id;
		}else{
			echo -3;
		}
	}
	//医生管理
	public function docter()
	{
		$data = array();
		$data = $this->common->config('docter');
		$hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;
		$keshi_id = isset($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$where = 1;
		if($hos_id){
			$where .= ' and d.hos_id = '.$hos_id;
		}
		if($keshi_id){
			$where .= ' and d.keshi_id = '.$keshi_id;
		}
		$sql = "select d.is_show,d.doc_name,d.doc_order,k.keshi_name,d.doc_id from ".$this->common->table('docter')." d
				left join ".$this->common->table('keshi')." k on k.keshi_id = d.keshi_id
				where $where order by d.doc_order desc";
		$res = $this->common->getAll($sql);
		$docter = array();
		foreach($res as $val){
			$docter[$val['doc_id']]['doc_name'] = $val['doc_name'];
			$docter[$val['doc_id']]['keshi_name'] = $val['keshi_name'];
			$docter[$val['doc_id']]['doc_order'] = $val['doc_order'];
			$docter[$val['doc_id']]['is_show'] = $val['is_show'];
			$doc_arr[] = $val['doc_id'];
		}
                //原先的语句 $doc = implode(',',$doc_arr);
                //以下是修改后的语句
               if(!empty($doc_arr)){
		$doc = implode(',',$doc_arr);
               }else{

                 $doc="1,2";
               }
		// 获取医生的排班信息
                //出错语句
		//$sql = "select distinct book_time,doc_id from ".$this->common->table('static_book').' where doc_id in ('.$doc.')';
                $sql = "select distinct book_time,doc_id from ".$this->common->table('static_book')." where doc_id in (".$doc.")";
		$res = $this->common->getAll($sql);
		if(!empty($res)){
			$weekday = array('周日','周一','周二','周三','周四','周五','周六');
			foreach($res as $val){
				$docter[$val['doc_id']]['book_time'][]=$weekday[$val['book_time']];
			}
		}
		$hos_list = $this->common->get_hosname();
		$data['hos_list'] = $hos_list;
		$data['docter'] = $docter;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('docter_new', $data);
	}
	public function book_list()
	{
		$data = array();
		$data = $this->common->config('book_list');
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$book_time_type = isset($_REQUEST['book_time_type'])? intval($_REQUEST['book_time_type']):1;
		$book_time_yue = isset($_REQUEST['book_time_yue'])? intval($_REQUEST['book_time_yue']):0;
		$book_time = isset($_REQUEST['book_time'])? trim($_REQUEST['book_time']):'';
		$hospital = $this->common->get_hosname();
		$data['hospital'] = $hospital;
		$data['hos_id'] = $hos_id;
		$data['keshi_id'] = $keshi_id;
		$data['doc_id'] = $doc_id;
		$data['book_time'] = $book_time;
		$data['book_time_yue'] = $book_time_yue;
		$data['book_time_type'] = $book_time_type;

		if($book_time_type == 1){
			if($book_time_yue){
				$start = mktime(0, 0, 0, $book_time_yue  , 01, date("Y"));
			}else{
				$start = mktime(0, 0, 0, date("m")  , 01, date("Y"));
				$data['book_time_yue'] = date("m");
			}
			$all = date('t',$start)-1;
			$end	= $start+$all*86400;
		}else if($book_time_type == 2){
			$b_time = strtotime($book_time);
			$start = mktime(0, 0, 0, date("m",$b_time)  , 01, date("Y",$b_time));
			$all = date('t',$b_time)-1;
			$end	= $start+$all*86400;
		}
		$check = $this->common->getOne('select book_time from '.$this->common->table('book_list').' where book_time >= '.$start.' and book_time <= '.$end);

		if($check){
			$where = 1;
			if($doc_id){
				$where .= ' and d.doc_id = '.$doc_id;
			}else{
				//获取医生信息
				if($hos_id){
					$where .= ' and d.hos_id = '.$hos_id;
					if($keshi_id){
						$where .= ' and d.keshi_id = '.$keshi_id;
					}
				}else{
					$wh = 1;
					if(!empty($_COOKIE["l_hos_id"])){
						$where .= " AND d.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
					}
				}
			}
			if($book_time_type == 1){
				$where .= ' and b.book_time >= '.$start.' and b.book_time <= '.$end;
			}else if($book_time_type == 2 && $b_time){
				$where .= ' and b.book_time = '.$b_time;
			}else{
				$d_time = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
				$where .= ' and b.book_time = '.$d_time;
			}
			$sql = 'select b.book_time,d.doc_order,d.is_show,d.doc_id,d.doc_name,k.keshi_name from '.$this->common->table('docter').' d
					right join '.$this->common->table('book_list').' b on d.doc_id = b.doc_id
					left join '.$this->common->table('keshi').' k on k.keshi_id = d.keshi_id
					where '.$where.' order by d.is_show,d.doc_order desc,b.book_time';
			$res = $this->common->getAll($sql);
			$book_list = array();
			$time_tag = array();
			foreach($res as $val){
				if(isset($time_tag[$val['doc_id']])){
					if(in_array($val['book_time'],$time_tag[$val['doc_id']])){
						continue;
					}else{
						$time_tag[$val['doc_id']][] = $val['book_time'];
					}
				}else{
					$time_tag[$val['doc_id']] = array($val['book_time']);
				}
				$book_list[$val['doc_id']]['doc_name'] = $val['doc_name'];
				$book_list[$val['doc_id']]['keshi_name'] = $val['keshi_name'];
				$book_list[$val['doc_id']]['doc_order'] = $val['doc_order'];
				$book_list[$val['doc_id']]['is_show'] = $val['is_show'];
				$w = date('w',$val['book_time']);
				$book_list[$val['doc_id']][$w][]= date('j',$val['book_time']);
			}
			$data['book_list'] = $book_list;
			$data['check'] = true;
		}else{
			$data['check'] = false;
		}
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('book_list_new', $data);
	}

	public function book_update_ajax()
	{
			$book_id		 = intval($_REQUEST['book_id']);
			if(!$book_id){
				exit();
			}
			if(isset($_REQUEST['num'])){
				$num		 	 = intval($_REQUEST['num']);
				$book = array('num'=>$num);
			}else if(isset($_REQUEST['data_time'])){
				$data_time			 = trim($_REQUEST['data_time']);
				$date = explode('-',$data_time);
				if(preg_match('/^\d{2}:\d{2}$/s',$date[0]) && preg_match('/^\d{2}:\d{2}$/s',$date[1])){
					$book = array('shang'=>$date[0],'xia'=>$date[1]);
				}else{
					echo -1;
					exit();
				}
			}else{
				echo -1;
				exit();
			}
			$this->db->where('book_id', $book_id);
			$res = $this->db->update('book_list', $book);
			echo $res;
	}

	public function book_list_ajax()
	{
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$yue = isset($_REQUEST['yue'])? intval($_REQUEST['yue']):0;
		$day = isset($_REQUEST['day'])? trim($_REQUEST['day']):'';
		if(empty($doc_id)||empty($yue)||empty($day)){
			exit();
		}
		if($day == 'undefined'){
			echo -1;
			exit();
		}
		$year = date('Y');
		$day = explode(',',$day);
		$data_time = array();
		foreach($day as $val){
			$str = $year.'-'.$yue.'-'.$val;
			$data_time[] = strtotime($str);
		}
		$book_time = implode(',',$data_time);
		//查询数据
		$sql = 'select * from '.$this->common->table('book_list').' where doc_id = '.$doc_id.' and book_time in ('.$book_time.') order by book_time,shang';
		$res = $this->common->getAll($sql);
		foreach($res as $key=>$val){
			$res[$key]['book_time'] = date('m月d日',$val['book_time']);
		}
		echo json_encode($res);
	}

	public function book_add_ajax()
	{
			$book_time		 = trim($_REQUEST['book_time']);
			$data_time		 = trim($_REQUEST['data_time']);
			$num			 = intval($_REQUEST['num']);
			$doc_id			 = intval($_REQUEST['doc_id']);
			$book = array();
			if(empty($doc_id) || empty($num)){
				echo -1;
				exit();
			}
			$date = explode('-',$data_time);
			if(preg_match('/^\d{2}:\d{2}$/s',$date[0]) && preg_match('/^\d{2}:\d{2}$/s',$date[1])){
				$book = array('shang'=>$date[0],'xia'=>$date[1]);
			}else{
				echo -1;
				exit();
			}
			$replace = array('月','日');
			$str = str_replace($replace,'-',$book_time);
			$str = substr($str,0,-1);
			$year = date('Y');
			$book['doc_id'] = $doc_id;
			$book['num'] = $num;
			$time = $year.'-'.$str;
			$book['book_time'] = strtotime($time);
			$this->db->insert('book_list', $book);
			$id = $this->db->insert_id();
			echo $id;
	}

	public function book_del_ajax()
	{
			$book_id		 = intval($_REQUEST['book_id']);


			$this->db->delete('book_list', array('book_id' => $book_id));
	}

	public function book_create()
	{
		//获取固定的医生信息
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$book_time_yue = isset($_REQUEST['book_time_yue'])? intval($_REQUEST['book_time_yue']):0;
		$type = isset($_REQUEST['type'])? intval($_REQUEST['type']):0;

		if($type){
			if(empty($doc_id)||empty($book_time_yue)){

				$this->common->msg('系统异常', 1);
			}else{
				$start  = mktime(0, 0, 0, $book_time_yue  , 01, date("Y"));
				$all = date('t');
				$end	= $start+$all*86400;
				$check = $this->common->getOne('select book_time from '.$this->common->table('book_list').' where doc_id = '.$doc_id.' and book_time >= '.$start.' and book_time <= '.$end);
				if($check){
					$this->common->msg('当前月份出诊信息已经生成', 1);
				}
				$static = $this->common->getAll('select * from '.$this->common->table('static_book').' where doc_id = '.$doc_id);

			}

		}else{
			if(empty($hos_id)||empty($book_time_yue)){

				$this->common->msg('系统异常', 1);
			}else{
				$start  = mktime(0, 0, 0, $book_time_yue  , 01, date("Y"));
				$all = date('t');
				$end	= $start+$all*86400;
				$docter_list = $this->common->getAll('select doc_name, doc_id from '.$this->common->table('docter'). ' where hos_id = '.$hos_id);				$doc_list = '';
				foreach($docter_list as $val){
					$check = $this->common->getOne('select book_time from '.$this->common->table('book_list').' where doc_id = '.$val['doc_id'].' and book_time >= '.$start.' and book_time <= '.$end);
					if(empty($check)){
						$doc_list[] = $val['doc_id'];
					}
				}
				if(empty($doc_list)){
					$this->common->msg('该医院下没有医生的排班需要生成', 1);
				}
				$doc_list = implode(',',$doc_list);
				$static = $this->common->getAll('select * from '.$this->common->table('static_book').' where doc_id IN ('.$doc_list.')');
			}
		}

		//本月一号的时间挫
		$static_list = array();
		foreach($static as $val){
			$static_list[$val['book_time']][] = $val;
		}
		$arr = array();
		for($i=0,$j=date('t',$start);$i<$j;$i++){
			$time = $start+$i*86400;
			if(array_key_exists(date('w',$time),$static_list)){
				foreach($static_list[date('w',$time)] as $val){
					$arr[$time][] = $val;
				}
			}
		}
		if(empty($arr)){
			$this->common->msg('没有可生成的排班信息，请先添加',1);
		}
		$data = array();
		foreach($arr as $key=>$val){
			foreach($val as $v){

				$data[] = array(
						'book_time'	=>	$key,
						'doc_id'	=>	$v['doc_id'],
						'shang'		=>	$v['shang'],
						'xia'		=>	$v['xia'],
						'num'		=>	$v['num']

					);
			}
		}
		if(!empty($data)){
			$this->db->insert_batch($this->common->table("book_list"),$data);
		}
		$this->common->msg($this->lang->line('success'));
	}

	public function book_hidden_ajax()
	{
		if(!isset($_REQUEST['tag']) || !isset($_REQUEST['doc_id'])){
			echo -1;
			exit();
		}
		$tag = $_REQUEST['tag'];
		$data = array();
		if($tag == 1){
			$data['is_show'] = 1;
		}elseif($tag == 2){
			$data['is_show'] = 0;
		}else{
			echo -2;
			exit();
		}
		$doc_id = intval($_REQUEST['doc_id']);
		$this->db->where('doc_id', $doc_id);
		$res = $this->db->update('docter', $data);
		echo $res;
	}

	public function docter_update()
	{
		$doc_id        = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		$form_action     = $_REQUEST['form_action'];
		$doc_name		 = trim($_REQUEST['doc_name']);
		$doc_zc		 = trim($_REQUEST['doc_zc']);
		$doc_des		 = trim($_REQUEST['doc_des']);
		$doc_long		 = trim($_REQUEST['doc_long']);
		$keshi_id        = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
		$hos_id        = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$order 		= isset($_REQUEST['doc_order']) ? intval($_REQUEST['doc_order']):0;
		$is_rec 		= isset($_REQUEST['is_rec']) ? intval($_REQUEST['is_rec']):0;
		$doc_img	= empty($_REQUEST['img_suo']) ? '' : trim($_REQUEST['img_suo']);
		$work_img	= count($_REQUEST['img_work']) ? $_REQUEST['img_work'] : '';
		if($work_img) $work_img = implode(',',$work_img);
		$tag	= count($_REQUEST['doc_tag']) ? $_REQUEST['doc_tag'] : '';
		$value	= count($_REQUEST['doc_val']) ? $_REQUEST['doc_val'] : '';
		$is_show	= count($_REQUEST['is_show']) ? $_REQUEST['is_show'] : '0';
		if(empty($is_show)){
			$is_show = 0;
		}
		$arr = array(
				'doc_name' => $doc_name,
				'doc_img' => $doc_img,
				'work_img' => $work_img,
				'doc_zc' => $doc_zc,
				'doc_des' => $doc_des,
				'doc_long' => $doc_long,
				'doc_order' => $order,
				'is_rec' => $is_rec,
				'is_show' => $is_show,
				'keshi_id' => $keshi_id,
				'hos_id' => $hos_id);
		if($tag){
			$setting = array();
			foreach($tag as $key => $val){
				if(empty($val)) continue;
				$setting[$val] = $value[$key];
			}
			$setting = array2string($setting);
			$arr['setting'] = $setting;
		}

		if($form_action == 'add'){

			$data = $this->common->config('docter_add');
			$this->db->insert($this->common->table("docter"), $arr);
			$doc_id = $this->db->insert_id();

		}else{

			$data = $this->common->config('docter_edit');
			$this->db->update($this->common->table("docter"), $arr, array('doc_id' => $doc_id));
		}
		$links[0] = array('href' => '?c=system&m=docter_info&doc_id=' . $doc_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=docter_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=docter', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function static_del_ajax()
	{
		$book_id			 = intval($_REQUEST['book_id']);
		if(empty($book_id)){
			echo -1;
			exit();
		}
		$this->db->delete($this->common->table('static_book'), array('book_id' => $book_id,));
		echo 1;
	}

	public function static_update_ajax()
	{
		$book_id			 = intval($_REQUEST['book_id']);
		$num			 = intval($_REQUEST['num']);
		if(empty($book_id) || empty($num)){
			echo -1;
			exit();
		}
		$book = array('num'=>$num);
		$this->db->where('book_id', $book_id);
		$res = $this->db->update('static_book', $book);
		echo $res;
	}
	public function docter_del()
	{
		$doc_id = isset($_REQUEST['doc_id'])? intval($_REQUEST['doc_id']):0;
		if(empty($doc_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->db->delete($this->common->table('docter'), array('doc_id' => $doc_id));

		// 清除医生排版数据

		$this->db->delete($this->common->table('book_list'), array('doc_id' => $doc_id));

		$links[0] = array('href' => '?c=system&m=docter', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	public function weixin_info()
	{
		$data = array();
		$wx_id = empty($_REQUEST['wx_id'])? 0:intval($_REQUEST['wx_id']);

		if(empty($wx_id))
		{
			$data = $this->common->config('weixin_add');
		}
		else
		{
			$data = $this->common->config('weixin_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('weixin') ." WHERE wx_id = $wx_id");
			$data['info'] = $info;
		}
		$data['hospital'] = $this->common->get_hosname();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/weixin_info', $data);

	}
	public function site_info()
	{
		$data = array();
		$st_id = empty($_REQUEST['st_id'])? 0:intval($_REQUEST['st_id']);

		if(empty($st_id))
		{
			$data = $this->common->config('st_add');
		}
		else
		{
			$data = $this->common->config('st_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('st') ." WHERE st_id = $st_id");
			$data['info'] = $info;
		}
		$data['hospital'] = $this->common->get_hosname();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('content/site_info', $data);

	}
	public function site_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$hos_id          = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$st_id          = isset($_REQUEST['st_id'])? intval($_REQUEST['st_id']):0;
		$sitename       	 = trim($_REQUEST['sitename']);
		$domain	         = trim($_REQUEST['domain']);
		$sysid	         = intval($_REQUEST['sysid']);


		if(empty($hos_id)||empty($sitename)||empty($sysid)||empty($domain))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$arr = array('hos_id' => $hos_id,
		             'sitename' => $sitename,
					 'sysid' => $sysid,
					 'domain' => $domain);
		if($form_action == 'update')
		{
			if(empty($st_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('st_edit');

			$this->db->update($this->common->table("st"), $arr, array('st_id' => $st_id));
		}
		else
		{
			$this->common->config('st_add');

			$this->db->insert($this->common->table("st"), $arr);
			$st_id = $this->db->insert_id();
		}
		$this->common->static_cache('delete', "site/" . $hos_id);
		$links[0] = array('href' => '?c=system&m=site_info&st_id=' . $st_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=site_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=site_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	public function site_list()
	{
		$data = array();
		$data = $this->common->config('st_list');
		$hospital = $this->common->get_hosname();
		$hos = array();
		$str = array();
		foreach($hospital as $val){
			$hos[$val['hos_id']] = $val['hos_name'];
			$str[] = $val['hos_id'];
		}
		$str = implode(',',$str);
		$data['hos'] = $hos;
		$data['sys'] = array(1=>'phpcms',2=>'dedecms',3=>'其他');
		$sql = 'select * from '.$this->common->table('st').' where hos_id in ('.$str.')';
		$data['st_list'] = $this->common->getAll($sql);

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('content/st_list', $data);
	}
	public function site_delete()
	{
		$this->common->config('st_delete');
		$st_id          = isset($_REQUEST['st_id'])? intval($_REQUEST['st_id']):0;
		if(empty($st_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$this->db->delete($this->common->table('st'), array('st_id' => $st_id));
		$links[0] = array('href' => '?c=system&m=site_list', 'text' => $this->lang->line('list_back'));
		$data = $this->common->msg($this->lang->line('success'), 0, $links, false);
		$this->load->view('msg', $data);
	}
	public function sms_update()
	{
		//名称，使用接口，帐号，密码
		$form_action     = $_REQUEST['form_action'];
		$sms_id          = isset($_REQUEST['sms_id'])? intval($_REQUEST['sms_id']):0;
		$hos_id          = !empty($_REQUEST['hos_id'])? $_REQUEST['hos_id']:0;
		$sms_name        = isset($_REQUEST['sms_name'])? trim($_REQUEST['sms_name']):'';
		$sms_int         = isset($_REQUEST['sms_int'])? trim($_REQUEST['sms_int']):'';
		$account      	 = trim($_REQUEST['account']);
		$password	     = trim($_REQUEST['password']);

		if(empty($sms_name)||empty($sms_int)||empty($account)||empty($password))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$arr = array('sms_name' => $sms_name,
		             'sms_int' => $sms_int,
					 'account' => $account,
					 'password' => $password
					 );
		if($form_action == 'update')
		{
			if(empty($sms_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('sms_edit');

			$this->db->update($this->common->table("sms_config"), $arr, array('sms_id' => $sms_id));
			// 处理关联医院
			$this->db->delete($this->common->table("sms_relation"), array('sms_id' => $sms_id));
		}
		else
		{
			$this->common->config('sms_add');

			$this->db->insert($this->common->table("sms_config"), $arr);
			$sms_id = $this->db->insert_id();
		}
		// 添加关联医院
		$data = array();
		foreach($hos_id as $val){
			$data[] = array('hos_id'=>$val,'sms_id'=>$sms_id);
		}
		$this->db->insert_batch($this->common->table("sms_relation"), $data);
		$links[0] = array('href' => '?c=system&m=sms_info&sms_id=' . $sms_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=sms_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=system&m=sms_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	public function sms_info()
	{
		$data = array();
		$sms_id = empty($_REQUEST['sms_id'])? 0:intval($_REQUEST['sms_id']);

		if(empty($sms_id))
		{
			$data = $this->common->config('sms_add');
		}
		else
		{
			$data = $this->common->config('sms_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('sms_config') ." WHERE sms_id = $sms_id");
			$data['info'] = $info;
			$hos_id = $this->common->getAll("select hos_id from ".$this->common->table('sms_relation')." where sms_id = $sms_id");
			$hos_arr = array();
			foreach($hos_id as $val){
				$hos_arr[] = $val['hos_id'];
			}
			$data['hos_arr'] = $hos_arr;
		}
		$data['hospital'] = $this->common->get_hosname();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('content/sms_info', $data);
	}
	public function sms_list()
	{
		$data = array();
		$data = $this->common->config('sms_list');
		$sql = 'select * from '.$this->common->table('sms_config');
		$data['sms_list'] = $this->common->getAll($sql);

		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('content/sms_list', $data);
	}
	public function sms_delete()
	{
		$this->common->config('sms_delete');
		$sms_id          = isset($_REQUEST['sms_id'])? intval($_REQUEST['sms_id']):0;
		if(empty($sms_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}

		$this->db->delete($this->common->table('sms_config'), array('sms_id' => $sms_id));
		$this->db->delete($this->common->table('sms_relation'), array('sms_id' => $sms_id));
		$links[0] = array('href' => '?c=system&m=sms_list', 'text' => $this->lang->line('list_back'));
		$data = $this->common->msg($this->lang->line('success'), 0, $links, false);
		$this->load->view('msg', $data);
	}
	public function sms_id_ajax()
	{
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$str = '<option value=\"0\">请选择...</option>';

		$sql = "select c.sms_id,c.sms_name from " . $this->common->table('sms_relation') . " r
				left join " . $this->common->table('sms_config') . " c on r.sms_id = c.sms_id
				where r.hos_id = $hos_id";

		$row = $this->common->getAll($sql);

		foreach($row as $val)

		{

			$str .= '<option value="' . $val['sms_id'] . '"';

			$str .= '>' . $val['sms_name'] . '</option>';

		}
		echo $str;
	}

	//宝宝缸管理
	public function baby()
	{
		$data = array();
		$data = $this->common->config('baby');
		$jb_id = isset($_REQUEST['jb_id']) ? intval($_REQUEST['jb_id']) : 0;
		$data['jb_id'] = $jb_id;
		$where = 1;
		if($jb_id){
			$where .= ' and d.jb_id = '.$jb_id;
		}
		$sql = "select d.*,k.jb_name from ".$this->common->table('baby_type')." d
				left join ".$this->common->table('jibing')." k on k.jb_id = d.jb_id
					where $where order by d.add_time desc";
		$data['baby'] = $this->common->getAll($sql);

		//最高一级的疾病数据
		$sql = "SELECT * FROM " . $this->common->table('jibing') . " WHERE parent_id = 281 ";
		$data['jb_list'] =$this->common->getAll($sql);
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('baby_new', $data);
	}
		//宝宝缸修改
		public function baby_update()
		{
		$id        = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		$form_action     = $_REQUEST['form_action'];
		$time		 = trim($_REQUEST['time']);
		$sum		 = trim($_REQUEST['sum']);
		$jb_id        = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;
		$arr = array(
				'sum' =>  $sum);
		if($form_action == 'add'){
			$time_str = explode('-',$time);
			$arr['time_start'] =$time_str[0];
			$arr['jb_id'] =$jb_id;
			$arr['time_end'] = $time_str[1];
			$arr['add_time'] = date("Y-m-d H:i:s",time());
			$this->db->insert($this->common->table("baby_type"), $arr);
			$doc_id = $this->db->insert_id();
		}else{
			$arr['edit_time'] =  date("Y-m-d H:i:s",time()); ;
			$this->db->update($this->common->table("baby_type"), $arr, array('id' => $id));
	}
	$links[0] = array('href' => '?c=system&m=baby_info&id=' . $id, 'text' =>  "修改宝宝缸");
	$links[1] = array('href' => '?c=system&m=baby_info', 'text' => "添加宝宝缸");
	$links[2] = array('href' => '?c=system&m=baby', 'text' => "宝宝缸列表");
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	//宝宝缸查询
	public function baby_info()
	{
		$data = array();
		$id        = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		if(!empty($id)){
			$data = $this->common->config('baby_edit');
			$data['info'] = $this->common->getRow("SELECT b.*,jb.jb_name FROM " . $this->common->table('baby_type') ." as b," . $this->common->table('jibing') ." as jb  WHERE b.id = ".$id." and b.jb_id = jb.jb_id ");
			$data['id'] = $id;
			$setting = new_stripslashes($info['setting']);
			eval("\$setting = $setting;");
			$data['setting'] = $setting;
		}else{
			$data = $this->common->config('baby_add');
			$jb_id = $this->input->get('jb_id',true);
			if(!empty($jb_id)){
				$data['jb_id'] = $jb_id;
			}
		}
		//最高一级的疾病数据
		$sql = "SELECT * FROM " . $this->common->table('jibing') . " WHERE parent_id = 281 ";
		$query = $this->db->query($sql);
		$data['jb_list'] =$query->result_array();
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('baby/baby_info', $data);
	}

	public function baby_del()
	{
		$doc_id = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		if(empty($doc_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->db->delete($this->common->table('baby_type'), array('id' => $doc_id));

		$links[0] = array('href' => '?c=system&m=baby', 'text' => '宝宝缸管理');
		$this->common->msg($this->lang->line('success'), 0, $links);
	}

	public function ajax_baby_time_list()
	{

		// 获取 科室下面的时间信息
		$jb_id = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;
		if(empty($jb_id)) exit;
		$sql = 'select time_start,time_end from '.$this->common->table('baby_type').' where jb_id = '.$jb_id;
		$list = $this->common->getAll($sql);
		$str = "'9:00-9:40''9:50-10:30''10:40-11:20''11:30-12:10''14:00-14:40''14:50-15:30''15:40-16:20''16:30-17:10''17:20-18:00''18:10-18:40''18:50-19:30'";
		foreach ($list as $list_temp){
			$str = str_replace("'".$list_temp['time_start'].'-'.$list_temp['time_end']."'","",$str);
		}
		$arr = explode("''",$str);
		$html  ='';
		foreach ($arr as $arr_temp){
			$arr_temp = str_replace("'","",$arr_temp);
			if($html == ''){
				$html = '<option value="'.$arr_temp.'">'.$arr_temp.'</option>';
			}else{
				$html .= '<option value="'.$arr_temp.'">'.$arr_temp.'</option>';
			}
		}
		echo '<option value="">请选择时间范围</option>'.$html;exit;
	}
	//预约挂号页面
	public function ajax_baby_time_to_order_list()
	{
		// 获取 科室下面的时间信息
		$jb_id = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;
		$defualtval = isset($_REQUEST['defualtval'])?$_REQUEST['defualtval']:0;
		$days = isset($_REQUEST['days'])?$_REQUEST['days']:0;

		if(empty($jb_id)) exit;
		//查询预约挂号在当前 疾病下面的数据
		$time_out=date("Y-m-d",time());
		//$time1=$time_out." 00:00:00";
		//$end_time=strtotime($time1);
		//$start_time=time()-30*24*60*60;
		$html  ='';
		$sql = "select b.id,b.time_start,b.time_end,b.sum,bs.sum as user_sum from ".$this->common->table('baby_type')." as b left join ".$this->common->table('baby_select_type')." as bs on bs.baby_type_id = b.id and bs.days = '".$days."' where b.jb_id = ".$jb_id;
		$list = $this->common->getAll($sql);
		foreach ($list as $list_temp){
			$select = '';
			if($defualtval == ($list_temp['time_start'].'~'.$list_temp['time_end'])){
				$select = 'selected';
				}

			if(intval($list_temp['user_sum']) < intval($list_temp['sum'])){
				if($html == ''){
					$html = '<option '.$select .' value="'.$list_temp['time_start'].'~'.$list_temp['time_end'].'">'.$list_temp['time_start'].'~'.$list_temp['time_end'].'</option>';
				}else{
					$html .= ' <option '.$select .' value="'.$list_temp['time_start'].'~'.$list_temp['time_end'].'">'.$list_temp['time_start'].'~'.$list_temp['time_end'].'</option>';
				}
			}else if(empty($list_temp['user_sum']) && intval($list_temp['sum']) > 0){
				if($html == ''){
					$html = ' <option '.$select .' value="'.$list_temp['time_start'].'~'.$list_temp['time_end'].'">'.$list_temp['time_start'].'~'.$list_temp['time_end'].'</option>';
				}else{
					$html .= '<option '.$select .' value="'.$list_temp['time_start'].'~'.$list_temp['time_end'].'">'.$list_temp['time_start'].'~'.$list_temp['time_end'].'</option>';
				}
			}
		}
		echo $html;exit;
	}


	/***
	 * 同步医院数据到 数据中心
	*/
	private function sycn_hospital_data_to_ireport($parm){


		$info = array();

		foreach ($parm as $temps){

			$info_temp = array();

			if(empty($temps['ireport_hos_id'])){

				$temps['ireport_hos_id'] = '';

			}

			$info_temp['id'] = $temps['ireport_hos_id'];

			$info_temp['name'] = $temps['hos_name'];

			$info_temp['hos_id'] = $temps['hos_id'];

			$info[] = $info_temp;

		}

		$info=  json_encode($info);



		//var_dump($info_temp);exit;

		//推送数据到数据中心系统

		$url = $this->config->item('ireport_url_send_hospital');

		if(!empty($url)){



			require_once(BASEPATH."/core/Decryption.php");



			//加密请求

			$decryption = new Decryption();

			//字母字符  用户保存，手动更新

			$mu_str= $this->config->item('renai_mu_word');

			//符号字符  用户保存，手动更新

			$gong_str= $this->config->item('renai_mu_number');

			//生成一次性加密key

			$key_user = $decryption->createRandKey($mu_str,$gong_str);

			//var_dump($key_user);

			$str = $this->config->item('ireport_name').date("Y-m-d H",time());

			//var_dump($str) ;exit;

			//加密

			$encryption_validate = $decryption->encryption($str,$key_user);

			$encryption_data = $decryption->encryption($info,$key_user);

			//var_dump($encryption_data) ;exit;



			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;

			/**

			//初始化

			$ch = curl_init();

			//设置选项，包括URL

			curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption_validate['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$encryption_data['data']);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_HEADER, 0);

			//执行并获取HTML文档内容

			$output = curl_exec($ch);

			//释放curl句柄

			curl_close($ch);

			//var_dump($output);exit;

			**/



			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);



			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_POST, 1);// post数据

			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量

			$output = curl_exec($ch);

			curl_close($ch);

			/*** **/

			//var_dump($output);exit;



			$api_request = array();

			$api_request['apply_id']= '';

			$api_request['apply_type']='添加或者更新医院';

			$api_request['apply_name']='医院';

			$api_request['apply_time']=date("Y-m-d H:i:s",time());

			$api_request['apply_address']=$url;

			$api_request['apply_data']=$info;



			//待更新数据

			$output_json = json_decode($output,true);

			if(!empty($output_json)){

				$output_data = json_decode($output_json['msg'],true);;

				$add_empty_status =$output_data['add_empty_status'];

				$add_exits_status  =$output_data['add_exits_status'];

				$add_add_ok_status  =$output_data['add_add_ok_status'];

				$add_add_error_status  =$output_data['add_add_error_status'];

				$add_update_ok_status  =$output_data['add_update_ok_status'];

				$add_update_error_status  =$output_data['add_update_error_status'];



				if(strcmp($output_json['code'],'info_empty') ==0 ){//记录日志

					$api_request['apply_status']='2';

				}else if(strcmp($output_json['code'],'decryption_error') ==0 ){//记录日志

					$api_request['apply_status']='2';

				}else if(strcmp($output_json['code'],'ok') ==0 ){//记录日志

					$api_request['apply_status']='1';

					if(count($add_add_ok_status) > 0){

						foreach ($add_add_ok_status as $temps){

							$update = array();

							$update['ireport_hos_id'] = $temps['active_id'];

							$update['ireport_msg'] = '绑定成功';

							$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

						}

					}else if(count($add_update_ok_status) > 0){

						foreach ($add_update_ok_status as $temps){

							$update = array();

							$update['ireport_hos_id'] = $temps['active_id'];

							$update['ireport_msg'] = '更新成功';

							$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

						}

					}else{

						if(!empty($add_empty_status)){

							foreach ($add_empty_status as $temps){

								$update = array();

								$update['ireport_msg'] = '操作失败。存在空值';

								$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

							}

						}else if(!empty($add_exits_status)){

							foreach ($add_exits_status as $temps){

								$update = array();

								$update['ireport_msg'] = '操作失败。存在同样的账户数据';

								$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

							}

						}else if(!empty($add_add_error_status)){

							foreach ($add_add_error_status as $temps){

								$update = array();

								$update['ireport_msg'] = '操作失败。数据中心添加失败';

								$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

							}

						}else if(!empty($add_update_error_status)){

							foreach ($add_update_error_status as $temps){

								$update = array();

								$update['ireport_msg'] = '操作失败。数据中心更新失败';

								$this->db->update($this->db->dbprefix . "hospital", $update, array('hos_id' => $temps['hos_id']));

							}

						}

					}

				}else{//记录日志

					$output_json['msg'] = '绑定失败。接口反馈异常';

					$api_request['apply_status']='1';

				}

			}else{

				$output_json['msg'] = '绑定失败。接口反馈异常';

				$api_request['apply_status']='1';

			}

			$api_request['response_msg']=$output_json['msg'];

			$api_request['response_code']=$output_json['code'];

			$api_request['response_data']=$output;

			$this->db->insert($this->common->table("api_request_log"), $api_request);

		}else{

			$api_request = array();

			$api_request['apply_id']= '';

			$api_request['apply_type']='添加或者更新医院';

			$api_request['apply_name']='医院';

			$api_request['apply_time']=date("Y-m-d H:i:s",time());

			$api_request['apply_address']=$url;

			$api_request['apply_data']=$info;

			$api_request['apply_status']=2;

			$api_request['response_msg']='请求URL地址为空';

			$api_request['response_code']='';

			$api_request['response_data']='';

			$this->db->insert($this->common->table("api_request_log"), $api_request);

		}
	}


	/****
	 * 同步科室数据到 数据中心
	*/
	private function sycn_keshi_data_to_ireport($parm){
		$info = array();
		foreach ($parm as $temps){
			$info_temp = array();
			if(empty($temps['ireport_hos_id'])){
				$temps['ireport_hos_id'] = '';
			}
			if(empty($temps['hos_name'])){
				$info_temp['name'] =  $temps['keshi_name'];
			}else{
				$info_temp['name'] = $temps['hos_name'].' '.$temps['keshi_name'];
			}
			$info_temp['id'] = $temps['ireport_hos_id'];
			$info_temp['renai_id'] = $temps['keshi_id'];
			$info[] = $info_temp;
		}
		$info=  json_encode($info);

		//var_dump($info_temp);exit;
		//推送数据到数据中心系统
		$url = $this->config->item('ireport_url_send_keshi');
		if(!empty($url)){

			require_once(BASEPATH."/core/Decryption.php");

			//加密请求
			$decryption = new Decryption();
			//字母字符  用户保存，手动更新
			$mu_str= $this->config->item('renai_mu_word');
			//符号字符  用户保存，手动更新
			$gong_str= $this->config->item('renai_mu_number');
			//生成一次性加密key
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			//var_dump($key_user);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			//var_dump($str) ;exit;
			//加密
			$encryption_validate = $decryption->encryption($str,$key_user);
			$encryption_data = $decryption->encryption($info,$key_user);
			//var_dump($encryption_data) ;exit;

			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
			/**
			 //初始化
			 $ch = curl_init();
			 //设置选项，包括URL
			 curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption_validate['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$encryption_data['data']);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 //执行并获取HTML文档内容
			 $output = curl_exec($ch);
			 //释放curl句柄
			 curl_close($ch);
			 //var_dump($output);exit;
			**/

			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);// post数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
			$output = curl_exec($ch);
			curl_close($ch);
			/*** **/
			//var_dump($output);exit;

			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='添加或者科室';
			$api_request['apply_name']='科室';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;

			//待更新数据
			$output_json = json_decode($output,true);
			if(!empty($output_json)){
				$output_data = json_decode($output_json['msg'],true);;
				$add_empty_status =$output_data['add_empty_status'];
				$add_exits_status  =$output_data['add_exits_status'];
				$add_add_ok_status  =$output_data['add_add_ok_status'];
				$add_add_error_status  =$output_data['add_add_error_status'];
				$add_update_ok_status  =$output_data['add_update_ok_status'];
				$add_update_error_status  =$output_data['add_update_error_status'];

				if(strcmp($output_json['code'],'info_empty') ==0 ){//记录日志
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'decryption_error') ==0 ){//记录日志
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'ok') ==0 ){//记录日志
					$api_request['apply_status']='1';
					if(count($add_add_ok_status) > 0){
						foreach ($add_add_ok_status as $temps){
							$update = array();
							$update['ireport_hos_id'] = $temps['active_id'];
							$update['ireport_msg'] = '绑定成功';
							$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

						}
					}else if(count($add_update_ok_status) > 0){
						foreach ($add_update_ok_status as $temps){
							$update = array();
							$update['ireport_hos_id'] = $temps['active_id'];
							$update['ireport_msg'] = '更新成功';
							$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

						}
					}else{
						if(!empty($add_empty_status)){
							foreach ($add_empty_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。存在空值';
								$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

							}
						}else if(!empty($add_exits_status)){
							foreach ($add_exits_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。存在同样的账户数据';
								$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

							}
						}else if(!empty($add_add_error_status)){
							foreach ($add_add_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。数据中心添加失败';
								$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

							}
						}else if(!empty($add_update_error_status)){
							foreach ($add_update_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。数据中心更新失败';
								$this->db->update($this->db->dbprefix . "keshi", $update, array('keshi_id' => $temps['renai_id']));

							}
						}
					}
				}else{//记录日志
					$output_json['msg'] = '绑定失败。接口反馈异常';
					$api_request['apply_status']='1';
				}
			}else{
				$output_json['msg'] = '绑定失败。接口反馈异常';
				$api_request['apply_status']='1';
			}
			$api_request['response_msg']=$output_json['msg'];
			$api_request['response_code']=$output_json['code'];
			$api_request['response_data']=$output;
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}else{
			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='添加或者科室';
			$api_request['apply_name']='科室';
			$api_request['apply_name']='';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;
			$api_request['apply_status']=2;
			$api_request['response_msg']='请求URL地址为空';
			$api_request['response_code']='';
			$api_request['response_data']='';
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}

	}

	/****
	 * 删除  科室 数据   同步到 数据中心
	*/
	private function sycn_del_keshi_data_to_ireport($parm){
		header("Content-type: text/html; charset=utf-8");

		$info = array();
		$info_temp = array();
		$info_temp['id'] =  $parm['ireport_hos_id'];

		$info[] = $info_temp;

		//var_dump($info_temp);exit;
		//推送数据到数据中心系统
		$url = $this->config->item('ireport_url_del_keshi');
		if(!empty($url)){
			require_once(BASEPATH."/core/Decryption.php");

			//加密请求
			$decryption = new Decryption();
			//字母字符  用户保存，手动更新
			$mu_str= $this->config->item('renai_mu_word');
			//符号字符  用户保存，手动更新
			$gong_str= $this->config->item('renai_mu_number');
			//生成一次性加密key
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			//var_dump($key_user);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			//var_dump($str) ;exit;
			//加密
			$encryption_validate = $decryption->encryption($str,$key_user);

			$encryption_data = $decryption->encryption(json_encode($info),$key_user);
			//var_dump($encryption) ;exit;

			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
			/**
			 //初始化
			 $ch = curl_init();
			 //设置选项，包括URL
			 curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 //执行并获取HTML文档内容
			 $output = curl_exec($ch);
			 //释放curl句柄
			 curl_close($ch);
			//var_dump($output);exit;**/

			/*****/
			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);// post数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
			$output = curl_exec($ch);
			curl_close($ch);
		}


	}

	/****
	 * 同步疾病数据到 数据中心
	*/
	private function sycn_jb_data_to_ireport($parm){
		header("Content-type: text/html; charset=utf-8");

		$info = array();
		foreach ($parm as $temps){
			$info_temp = array();
			if(empty($temps['ireport_jb_id'])){
				$temps['ireport_jb_id'] = '';
			}
			$info_temp['id'] =  $temps['ireport_jb_id'];
			$info_temp['real_name'] =  $temps['jb_name'];
			$info_temp['show_name'] =  $temps['jb_name'];
			$info_temp['code'] =  $temps['jb_code'];
			$info_temp['parent_id'] = $temps['parent_id'];
			$info_temp['renai_id'] = $temps['jb_id'];
			$info[] = $info_temp;
		}

		$info=  json_encode($info);

		//var_dump($info_temp);exit;
		//推送数据到数据中心系统
		$url = $this->config->item('ireport_url_send_jb');
		if(!empty($url)){
			require_once(BASEPATH."/core/Decryption.php");

			//加密请求
			$decryption = new Decryption();
			//字母字符  用户保存，手动更新
			$mu_str= $this->config->item('renai_mu_word');
			//符号字符  用户保存，手动更新
			$gong_str= $this->config->item('renai_mu_number');
			//生成一次性加密key
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			//var_dump($key_user);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			//var_dump($str) ;exit;
			//加密
			$encryption_validate = $decryption->encryption($str,$key_user);
			$encryption_data = $decryption->encryption($info,$key_user);
			//var_dump($encryption_data) ;exit;

			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
			/**
			 //初始化
			 $ch = curl_init();
			 //设置选项，包括URL
			 curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption_validate['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$encryption_data['data']);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 //执行并获取HTML文档内容
			 $output = curl_exec($ch);
			 //释放curl句柄
			 curl_close($ch);
			 //var_dump($output);exit;
			**/

			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);// post数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
			$output = curl_exec($ch);
			curl_close($ch);
			/*** **/
			//var_dump($output);exit;

			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='添加或者更新疾病';
			$api_request['apply_name']='疾病';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;

			//待更新数据
			$output_json = json_decode($output,true);
			if(!empty($output_json)){
				$output_data = json_decode($output_json['msg'],true);;
				$add_empty_status =$output_data['add_empty_status'];
				$add_exits_status  =$output_data['add_exits_status'];
				$add_add_ok_status  =$output_data['add_add_ok_status'];
				$add_add_error_status  =$output_data['add_add_error_status'];
				$add_update_ok_status  =$output_data['add_update_ok_status'];
				$add_update_error_status  =$output_data['add_update_error_status'];

				if(strcmp($output_json['code'],'info_empty') ==0 ){//记录日志
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'decryption_error') ==0 ){//记录日志
					$api_request['apply_status']='2';
				}else if(strcmp($output_json['code'],'ok') ==0 ){//记录日志
					$api_request['apply_status']='1';
					if(count($add_add_ok_status) > 0){
						foreach ($add_add_ok_status as $temps){
							$update = array();
							$update['ireport_jb_id'] = $temps['active_id'];
							$update['ireport_msg'] = '绑定成功';
							$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

						}
					}else if(count($add_update_ok_status) > 0){
						foreach ($add_update_ok_status as $temps){
							$update = array();
							$update['ireport_jb_id'] = $temps['active_id'];
							$update['ireport_msg'] = '更新成功';
							$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

						}
					}else{
						if(!empty($add_empty_status)){
							foreach ($add_empty_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。存在空值';
								$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

							}
						}else if(!empty($add_exits_status)){
							foreach ($add_exits_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。存在同样的账户数据';
								$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

							}
						}else if(!empty($add_add_error_status)){
							foreach ($add_add_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。数据中心添加失败';
								$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

							}
						}else if(!empty($add_update_error_status)){
							foreach ($add_update_error_status as $temps){
								$update = array();
								$update['ireport_msg'] = '操作失败。数据中心更新失败';
								$this->db->update($this->db->dbprefix . "jibing", $update, array('jb_id' => $temps['renai_id']));

							}
						}
					}
				}else{//记录日志
					$output_json['msg'] = '绑定失败。接口反馈异常';
					$api_request['apply_status']='1';
				}
			}else{
				$output_json['msg'] = '绑定失败。接口反馈异常';
				$api_request['apply_status']='1';
			}
			$api_request['response_msg']=$output_json['msg'];
			$api_request['response_code']=$output_json['code'];
			$api_request['response_data']=$output;
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}else{
			$api_request = array();
			$api_request['apply_id']= '';
			$api_request['apply_type']='添加或者更新疾病';
			$api_request['apply_name']='疾病';
			$api_request['apply_name']='';
			$api_request['apply_time']=date("Y-m-d H:i:s",time());
			$api_request['apply_address']=$url;
			$api_request['apply_data']=$info;
			$api_request['apply_status']=2;
			$api_request['response_msg']='请求URL地址为空';
			$api_request['response_code']='';
			$api_request['response_data']='';
			$this->db->insert($this->common->table("api_request_log"), $api_request);
		}


	}


	/****
	 * 同步科室和疾病数据到 数据中心
	*/
	private function sycn_keshi_and_jb_data_to_ireport($parm){
		header("Content-type: text/html; charset=utf-8");

		$info = array();
		$info_temp = array();
		$info_temp['hospital_id'] =  $parm['hospital_id'];
		$info_temp['disease_id_str'] =  $parm['disease_id_str'];
		$info[] = $info_temp;

		//var_dump($info_temp);exit;
		//推送数据到数据中心系统
		$url = $this->config->item('ireport_url_send_keshi_and_jb');
		if(!empty($url)){
			require_once(BASEPATH."/core/Decryption.php");

			//加密请求
			$decryption = new Decryption();
			//字母字符  用户保存，手动更新
			$mu_str= $this->config->item('renai_mu_word');
			//符号字符  用户保存，手动更新
			$gong_str= $this->config->item('renai_mu_number');
			//生成一次性加密key
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			//var_dump($key_user);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			//var_dump($str) ;exit;
			//加密
			$encryption_validate = $decryption->encryption($str,$key_user);

			$encryption_data = $decryption->encryption(json_encode($info),$key_user);
			//var_dump($encryption) ;exit;

			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
			/**
			 //初始化
			 $ch = curl_init();
			 //设置选项，包括URL
			 curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 //执行并获取HTML文档内容
			 $output = curl_exec($ch);
			 //释放curl句柄
			 curl_close($ch);
			//var_dump($output);exit;**/

			/***	**/
			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);// post数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
			$output = curl_exec($ch);
			curl_close($ch);

			//var_dump($update);exit;
		}

	}


	/****
	 * 删除   疾病 数据   同步到 数据中心
	*/
	private function sycn_del_jb_data_to_ireport($parm){
		header("Content-type: text/html; charset=utf-8");

		$info = array();
		$info_temp = array();
		$info_temp['id'] =  $parm['ireport_jb_id'];

		$info[] = $info_temp;

		//var_dump($info_temp);exit;
		//推送数据到数据中心系统
		$url = $this->config->item('ireport_url_del_jb');
		if(!empty($url)){
			require_once(BASEPATH."/core/Decryption.php");

			//加密请求
			$decryption = new Decryption();
			//字母字符  用户保存，手动更新
			$mu_str= $this->config->item('renai_mu_word');
			//符号字符  用户保存，手动更新
			$gong_str= $this->config->item('renai_mu_number');
			//生成一次性加密key
			$key_user = $decryption->createRandKey($mu_str,$gong_str);
			//var_dump($key_user);
			$str = $this->config->item('ireport_name').date("Y-m-d H",time());
			//var_dump($str) ;exit;
			//加密
			$encryption_validate = $decryption->encryption($str,$key_user);

			$encryption_data = $decryption->encryption(json_encode($info),$key_user);
			//var_dump($encryption) ;exit;

			//echo $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info;exit;
			/**
			 //初始化
			 $ch = curl_init();
			 //设置选项，包括URL
			 curl_setopt($ch, CURLOPT_URL, $url."&appid=".$this->config->item('ireport_name')."&appkey=".$this->config->item('ireport_pwd')."&verification=".$encryption['data']."&key_one=".$key_user['mu_str_number']."&key_two=".$key_user['gong_str_number']."&info=".$info);
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			 curl_setopt($ch, CURLOPT_HEADER, 0);
			 //执行并获取HTML文档内容
			 $output = curl_exec($ch);
			 //释放curl句柄
			 curl_close($ch);
			//var_dump($output);exit;**/

			/*****/
			$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);// post数据
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);// post的变量
			$output = curl_exec($ch);
			curl_close($ch);
		}


	}

}