<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 网站数据
class Site extends CI_Controller
{
	var $model;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Site_model');
		$this->lang->load('site');
		
		$this->model = $this->Site_model;
	}
	
	public function site_list()
	{
		$data = array();
		$data = $this->common->config('site_list');
		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		$rank_id = $_COOKIE['l_rank_id'];
		
		$site_count = $this->model->site_count($rank_id);
		$config = page_config();
		$config['base_url'] = '?c=index&m=site_list';
		$config['total_rows'] = $site_count;
		$config['per_page'] = '20';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$data['site_list'] = $this->model->site_list($page, $config['per_page'],$rank_id);
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_list', $data);
	}
	
	public function site_info()
	{
		$data = array();
		$site_id = empty($_REQUEST['site_id'])? 0:intval($_REQUEST['site_id']);
		$rank = read_static_cache('rank');
		if(empty($site_id))
		{
			$data = $this->common->config('site_add');
			$format_str = '<li>%s<label><input type="checkbox" name="rank_id[]" value="%s" rank_id="%s" parent_id="%s"/> %s</label></li>';
			$rank_list = options($rank, 'rank_id', 'rank_name', 'child', '<span class="td_child"></span>', '', 'rank_tree', $format_str);
		}
		else
		{
			$data = $this->common->config('site_edit');
			$format_str = '<li>%s<label><input type="checkbox" name="rank_id[]" value="%s" rank_id="%s" parent_id="%s"/> %s</label></li>';
			$rank_list_b = options($rank, 'rank_id', 'rank_name', 'child', '<span class="td_child"></span>', '', 'rank_tree', $format_str);
			$rank_list_b = explode("</li>", $rank_list_b);
			$site_info = $this->model->site_info($site_id);
			$data['site_info'] = $site_info['info'];
			$rank_id_arr = array();
			foreach($site_info['rank_id_arr'] as $val)
			{
				$rank_id_arr[] = $val['rank_id'];
			}
			foreach($rank_list_b as $key => $val)
			{
				if(empty($val))
				{
					continue;
				}
				preg_match_all("|value=\"(.*)\"|U", $val, $rank_id_b);
				$rank_id_b = $rank_id_b[1][0];
				if(in_array($rank_id_b, $rank_id_arr))
				{
					$rank_list_b[$key] = str_replace("<input type=", "<input checked=\"checked\" type=", $val . "</li>");
				}
			}
			$rank_list = implode("",$rank_list_b);
			$keywords = array();
			foreach($site_info['keywords'] as $val)
			{
				$keywords[] = $val['key_keyword'];
			}
			$keywords = implode("\r\n",$keywords);
			$data['keywords'] = $keywords;
		}
		
		$site_system = $this->common->getAll("SELECT * FROM " . $this->common->table('site_system'));
		$ba_xz = $this->common->getAll("SELECT * FROM " . $this->common->table('ba_xz'));
		$data['site_system'] = $site_system;
		$data['ba_xz'] = $ba_xz;
		$data['rank_list'] = $rank_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_info', $data);
	}
	
	public function site_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$site_id         = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$site_domain     = trim($_REQUEST['site_domain']);
		$site_mobile_domain     = trim($_REQUEST['site_mobile_domain']);
		$site_name       = trim($_REQUEST['site_name']);
		$site_time       = trim($_REQUEST['site_time']);
		$swt_url         = trim($_REQUEST['swt_url']);
		$site_bd         = intval($_REQUEST['site_bd']);
		$site_bd_username = trim($_REQUEST['site_bd_username']);
		$site_bd_password = trim($_REQUEST['site_bd_password']);
	    $site_bd_token    = trim($_REQUEST['site_bd_token']);
		$rank_id         = $_REQUEST['rank_id'];
		$sys_id = trim($_REQUEST['sys_id']);
		$site_ba_no = trim($_REQUEST['site_ba_no']);
		$site_ba_com = trim($_REQUEST['site_ba_com']);
		$xz_id = trim($_REQUEST['xz_id']);
		$site_host = trim($_REQUEST['site_host']);
		$site_ba_name = trim($_REQUEST['site_ba_name']);
		$site_ba_time = trim($_REQUEST['site_ba_time']);
		$site_ip = trim($_REQUEST['site_ip']);
		$site_host_system = trim($_REQUEST['site_host_system']);
		$site_domain_time = trim($_REQUEST['site_domain_time']);
		$site_keywords = trim($_REQUEST['site_keywords']);
		$old_site_keywords = isset($_REQUEST['old_site_keywords'])? $_REQUEST['old_site_keywords']:array();
		$rank_order = trim($_REQUEST['rank_order']);
		$site_time = strtotime($site_time);
		$site_ba_time = strtotime($site_ba_time);
		$site_domain_time = strtotime($site_domain_time);
		$site_keywords = explode("\r\n" , $site_keywords);

		//if(empty($site_domain) || empty($site_name) || empty($swt_url) || empty($site_ba_no) || empty($site_ba_com) || empty($site_host) || empty($site_ba_name) || empty($site_ip) || empty($site_host_system))
		if(empty($site_domain) || empty($site_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$arr = array('site_domain' => $site_domain,
		             'site_mobile_domain' => $site_mobile_domain,
					 'site_name' => $site_name,
					 'site_time' => $site_time,
					 'site_order' => $rank_order,
					 'site_bd' => $site_bd,
					 'site_bd_username' => $site_bd_username,
					 'site_bd_password' => $site_bd_password,
					 'site_bd_token' => $site_bd_token,
					 'swt_url' => $swt_url);
						 
		if($form_action == 'update')
		{
			if(empty($site_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('site_add');
			
			$old_site_keywords = explode("\r\n" , $old_site_keywords);
			$is_new_keyword = array_diff($site_keywords, $old_site_keywords);
			$is_del_keyword = array_diff($old_site_keywords, $site_keywords);
			
			$this->db->update($this->common->table("site"), $arr, array('site_id' => $site_id));
			$site_info = array('site_ba_no' => $site_ba_no,
							   'site_ba_com' => $site_ba_com,
							   'site_ba_name' => $site_ba_name,
							   'site_ba_time' => $site_ba_time,
							   'site_ip ' => $site_ip,
							   'site_host' => $site_host,
							   'site_host_system' => $site_host_system,
							   'site_domain_time' => $site_domain_time,
							   'sys_id' => $sys_id,
							   'xz_id' => $xz_id);
			$this->db->update($this->common->table("site_info"), $site_info, array('site_id' => $site_id));   
			$this->db->delete($this->common->table("site_rank"), array('site_id' => $site_id));
			foreach($is_del_keyword as $val)
			{
				$arr = array('site_id' => $site_id, 'key_keyword' => $val);
				$this->db->delete($this->common->table("site_keywords"), $arr);
			}
			foreach($is_new_keyword as $val)
			{
				$arr = array('site_id' => $site_id, 'key_keyword' => $val);
				$this->db->insert($this->common->table("site_keywords"), $arr);
			}
			
			foreach($rank_id as $val)
			{
				$arr = array('site_id' => $site_id, 'rank_id' => $val);
				$this->db->insert($this->common->table("site_rank"), $arr);
			}
			
			$this->common->static_cache('delete', "cpc/" . $site_id);
		}
		else
		{
			$this->common->config('site_add');
			$this->db->insert($this->common->table("site"), $arr);
			$site_id = $this->db->insert_id();
			$site_info = array('site_ba_no' => $site_ba_no,
							   'site_id' => $site_id,
							   'site_ba_com' => $site_ba_com,
							   'site_ba_name' => $site_ba_name,
							   'site_ba_time' => $site_ba_time,
							   'site_ip ' => $site_ip,
							   'site_host' => $site_host,
							   'site_host_system' => $site_host_system,
							   'site_domain_time' => $site_domain_time,
							   'sys_id' => $sys_id,
							   'xz_id' => $xz_id);
			$this->db->insert($this->common->table("site_info"), $site_info);
			foreach($site_keywords as $val)
			{
				$arr = array('site_id' => $site_id, 'key_keyword' => $val);
				$this->db->insert($this->common->table("site_keywords"), $arr);
			}
			
			foreach($rank_id as $val)
			{
				$arr = array('site_id' => $site_id, 'rank_id' => $val);
				$this->db->insert($this->common->table("site_rank"), $arr);
			}
			
			// 为每个站点生成各自的流量统计数据表
			
			// 创建访客信息主表
			/*$sql = "CREATE TABLE `hui_google_visitor_" . $site_id . "` (
					  `vis_id` int(11) NOT NULL auto_increment,
					  `vis_cid` varchar(30) NOT NULL,
					  `vis_city` varchar(50) NOT NULL,
					  `sys_id` smallint(3) NOT NULL,
					  `bro_id` smallint(3) NOT NULL,
					  `scr_id` smallint(5) NOT NULL,
					  `vis_visits` smallint(5) NOT NULL,
					  `vis_pageviews` int(10) NOT NULL,
					  PRIMARY KEY  (`vis_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$this->db->query($sql);*/
			
			// 创建访客入口信息表
			$sql = "CREATE TABLE `hui_google_load_" . $site_id . "` (
					  `load_id` bigint(15) unsigned NOT NULL auto_increment,
					  `domain_id` tinyint(2) NOT NULL,
					  `load_cid` varchar(35) NOT NULL,
					  `from_site_id` smallint(5) NOT NULL,
					  `from_type_id` smallint(3) NOT NULL,
					  `from_url` varchar(500) NOT NULL,
					  `load_page` varchar(150) NOT NULL,
					  `load_view_time` int(11) NOT NULL,
					  `load_year` smallint(4) NOT NULL,
					  `load_month` smallint(2) NOT NULL,
					  `load_day` smallint(2) NOT NULL,
					  `load_hour` smallint(2) NOT NULL,
					  `load_time` int(11) NOT NULL,
					  `is_ask` tinyint(1) NOT NULL default '0',
					  PRIMARY KEY  (`load_id`),
					  KEY `domain_id` (`domain_id`),
					  KEY `load_cid` (`load_cid`),
					  KEY `is_ask` (`is_ask`),
					  KEY `load_time` (`load_time`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			 $this->db->query($sql);
			 
			 // 创建访客轨迹信息表
			 $sql = "CREATE TABLE `hui_google_path_" . $site_id . "` (
					  `path_id` bigint(15) NOT NULL auto_increment,
					  `domain_id` tinyint(2) NOT NULL,
					  `path_cid` varchar(35) NOT NULL,
					  `load_id` bigint(15) NOT NULL,
					  `path_vtime` int(11) unsigned NOT NULL,
					  `path_pre` varchar(500) NOT NULL,
					  `path_url` varchar(500) NOT NULL,
					  `path_title` varchar(200) NOT NULL,
					  `path_time` int(11) unsigned NOT NULL,
					  `is_ask` tinyint(1) NOT NULL default '0',
					  PRIMARY KEY  (`path_id`),
					  KEY `domain_id` (`domain_id`),
					  KEY `path_cid` (`path_cid`),
					  KEY `load_id` (`load_id`),
					  KEY `is_ask` (`is_ask`),
					  KEY `path_time` (`path_time`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$this->db->query($sql);

			// 创建访客来源搜索信息表
			/*$sql = "CREATE TABLE `hui_google_search_" . $site_id . "` (
					  `search_id` int(11) unsigned NOT NULL auto_increment,
					  `load_id` bigint(15) unsigned NOT NULL,
					  `key_id` int(11) unsigned NOT NULL,
					  PRIMARY KEY  (`search_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$this->db->query($sql);*/
			
			// 创建网站关键词日报表
			/*$sql = "CREATE TABLE `hui_site_keyword_log_" . $site_id . "` (
					  `log_id` int(11) unsigned NOT NULL auto_increment,
					  `key_id` smallint(5) NOT NULL,
					  `log_year` smallint(4) NOT NULL,
					  `log_month` smallint(2) NOT NULL,
					  `log_day` smallint(2) NOT NULL,
					  `bd_pm` smallint(2) NOT NULL,
					  `google_pm` smallint(2) NOT NULL,
					  `360_pm` smallint(2) NOT NULL,
					  `bd_pm_change` smallint(3) NOT NULL,
					  `google_pm_change` smallint(3) NOT NULL,
					  `360_pm_change` smallint(3) NOT NULL,
					  PRIMARY KEY  (`log_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
			$this->db->query($sql);*/
			
			if($site_bd == 1)
			{
				// 创建访客从百度竞价来路的信息
				/*$sql = "CREATE TABLE `hui_google_bd_cpc_" . $site_id . "` (
						  `cpc_id` int(11) NOT NULL auto_increment,
						  `load_id` bigint(15) unsigned NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  PRIMARY KEY  (`cpc_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);*/
			
				// 创建百度竞价站的日账户报告
				$sql = "CREATE TABLE `hui_bd_cpc_day_" . $site_id . "` (
						  `day_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `day_year` smallint(4) NOT NULL,
						  `day_month` smallint(2) NOT NULL,
						  `day_day` smallint(2) NOT NULL,
						  `day_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `day_shows` int(11) NOT NULL,
						  `day_clicks` smallint(5) NOT NULL,
						  `day_cost` float(10,2) NOT NULL,
						  `day_click_lv` float(5,2) NOT NULL,
						  `day_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`day_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
				
				$sql = "CREATE TABLE `hui_bd_cpc_day_pc_" . $site_id . "` (
						  `day_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `day_year` smallint(4) NOT NULL,
						  `day_month` smallint(2) NOT NULL,
						  `day_day` smallint(2) NOT NULL,
						  `day_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `day_shows` int(11) NOT NULL,
						  `day_clicks` smallint(5) NOT NULL,
						  `day_cost` float(10,2) NOT NULL,
						  `day_click_lv` float(5,2) NOT NULL,
						  `day_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`day_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
				
				$sql = "CREATE TABLE `hui_bd_cpc_day_mobile_" . $site_id . "` (
						  `day_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `day_year` smallint(4) NOT NULL,
						  `day_month` smallint(2) NOT NULL,
						  `day_day` smallint(2) NOT NULL,
						  `day_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `day_shows` int(11) NOT NULL,
						  `day_clicks` smallint(5) NOT NULL,
						  `day_cost` float(10,2) NOT NULL,
						  `day_click_lv` float(5,2) NOT NULL,
						  `day_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`day_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
				
				// 创建竞价网站小时关键词报告
				$sql = "CREATE TABLE `hui_bd_cpc_hour_" . $site_id . "` (
						  `hour_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `hour_year` smallint(4) NOT NULL,
						  `hour_month` smallint(2) NOT NULL,
						  `hour_day` smallint(2) NOT NULL,
						  `hour_hour` smallint(2) NOT NULL,
						  `hour_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `hour_shows` int(11) NOT NULL,
						  `hour_clicks` smallint(5) NOT NULL,
						  `hour_cost` float(10,2) NOT NULL,
						  `hour_click_lv` float(5,2) NOT NULL,
						  `hour_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`hour_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
				
				$sql = "CREATE TABLE `hui_bd_cpc_hour_pc_" . $site_id . "` (
						  `hour_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `hour_year` smallint(4) NOT NULL,
						  `hour_month` smallint(2) NOT NULL,
						  `hour_day` smallint(2) NOT NULL,
						  `hour_hour` smallint(2) NOT NULL,
						  `hour_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `hour_shows` int(11) NOT NULL,
						  `hour_clicks` smallint(5) NOT NULL,
						  `hour_cost` float(10,2) NOT NULL,
						  `hour_click_lv` float(5,2) NOT NULL,
						  `hour_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`hour_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
				
				$sql = "CREATE TABLE `hui_bd_cpc_hour_mobile_" . $site_id . "` (
						  `hour_id` int(11) unsigned NOT NULL auto_increment,
						  `bd_id` int(11) NOT NULL,
						  `hour_year` smallint(4) NOT NULL,
						  `hour_month` smallint(2) NOT NULL,
						  `hour_day` smallint(2) NOT NULL,
						  `hour_hour` smallint(2) NOT NULL,
						  `hour_time` int(11) NOT NULL,
						  `plan_id` int(11) NOT NULL,
						  `group_id` int(11) NOT NULL,
						  `key_id` bigint(15) NOT NULL,
						  `word_id` int(11) NOT NULL,
						  `key_word` varchar(200) NOT NULL,
						  `hour_shows` int(11) NOT NULL,
						  `hour_clicks` smallint(5) NOT NULL,
						  `hour_cost` float(10,2) NOT NULL,
						  `hour_click_lv` float(5,2) NOT NULL,
						  `hour_price` float(5,2) NOT NULL,
						  `qian_show_cost` float(5,2) NOT NULL,
						  PRIMARY KEY  (`hour_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
				$this->db->query($sql);
			}
		}
		
		clear_static_cache("site");
		$row = $this->common->getAll("SELECT site_id, site_domain, site_mobile_domain FROM " . $this->common->table("site") . " ORDER BY site_id DESC");
		$site_list = array();
		foreach($row as $val)
		{
			if(!empty($val['site_domain']))
			{
				$site_list[$val['site_id']][$val['site_domain']]   = 1;
			}
			if(!empty($val['site_mobile_domain']))
			{
				$site_list[$val['site_id']][$val['site_mobile_domain']] = 2;
			}
		}
		write_static_cache("site", $site_list);
		$links[0] = array('href' => '?c=site&m=site_info&site_id=' . $site_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=site&m=site_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=site&m=site_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function site_delete()
	{
		$this->common->config('site_delete');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->db->delete($this->common->table("site"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_info"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_keywords"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_data"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_rank"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_seo"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("bd_cpc_account"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("bd_cpc_tmp"), array('site_id' => $site_id));
		$this->db->delete($this->common->table("site_seo"), array('site_id' => $site_id));
		
		if($this->db->table_exists("site_keyword_log_" . $site_id))
		{
			$this->db->query("DROP TABLE  `site_keyword_log_" . $site_id . "`");
		}
		
		/*if($this->db->table_exists("hui_google_visitor_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_google_visitor_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_google_search_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_google_search_" . $site_id . "`");
		}
		*/
		if($this->db->table_exists("hui_google_path_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_google_path_" . $site_id . "`");
		}
		
		/*if($this->db->table_exists("hui_google_bd_cpc_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_google_bd_cpc_" . $site_id . "`");
		}*/
		
		if($this->db->table_exists("hui_google_load_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_google_load_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_hour_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_hour_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_hour_pc_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_hour_pc_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_hour_mobile_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_hour_mobile_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_day_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_day_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_day_pc_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_day_pc_" . $site_id . "`");
		}
		
		if($this->db->table_exists("hui_bd_cpc_day_mobile_" . $site_id))
		{
			$this->db->query("DROP TABLE  `hui_bd_cpc_day_mobile_" . $site_id . "`");
		}
		$this->common->static_cache('delete', "cpc/" . $site_id);
		
		$row = $this->common->getAll("SELECT site_id, site_domain, site_mobile_domain FROM " . $this->common->table("site") . " ORDER BY site_id DESC");
		$site_list = array();
		foreach($row as $val)
		{
			if(!empty($val['site_domain']))
			{
				$site_list[$val['site_id']][$val['site_domain']]   = 1;
			}
			if(!empty($val['site_mobile_domain']))
			{
				$site_list[$val['site_id']][$val['site_mobile_domain']] = 2;
			}
		}
		write_static_cache("site", $site_list);
		
		$links[0] = array('href' => '?c=site&m=site_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
        public function site_edit(){
            $data=array();
            $sql="select * from ".$this->common->table('order_num')." where number_id=1";
            $str=$this->common->getRow($sql);
            $data['info']=$str;
            $this->load->view('site_edit',$data);
            
        }
        public function site_up(){
            $start=isset($_POST['start'])?intval($_POST['start']):0;
            $end=isset($_POST['end'])?intval($_POST['end']):0;
            $data=array('number_s'=>$start,'number_e'=>$end);
            $this->db->update($this->common->table('order_num'),$data,array('number_id'=>1));
            if($this->db->affected_rows()){
                $this->common->msg("success",1);
            }else{
                $this->common->msg("failed",0);
            }
            
        }
	public function from_type_list()
	{
		$data = array();
		$data = $this->common->config('from_type_list');
		
		$data['type_list'] = $this->common->getAll("SELECT * FROM " . $this->common->table('from_type') . " ORDER BY type_id ASC");
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('from_type_list', $data);
	}
	
	public function from_type_info()
	{
		$data = array();
		$type_id = empty($_REQUEST['type_id'])? 0:intval($_REQUEST['type_id']);
		
		if(empty($type_id))
		{
			$data = $this->common->config('from_type_add');
		}
		else
		{
			$data = $this->common->config('from_type_edit');
			
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('from_type') . " WHERE type_id = $type_id");
			$data['info'] = $info;
		}
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('from_type_info', $data);
	}
	
	public function from_type_update()
	{
		$form_action = isset($_REQUEST['form_action'])? $_REQUEST['form_action']:'';
		$type_name = isset($_REQUEST['type_name'])? trim($_REQUEST['type_name']):'';
		$type_desc = isset($_REQUEST['type_desc'])? trim($_REQUEST['type_desc']):'';
		$type_id = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;
		
		if(empty($form_action) || empty($type_name))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$data = array();
		$arr = array('type_name' => $type_name, 'type_desc' => $type_desc);
		
		if($form_action == 'add')
		{
			$data = $this->common->config('from_type_add');
			$this->db->insert($this->common->table('from_type'), $arr);
			$type_id = $this->db->insert_id();
		}
		else
		{
			$data = $this->common->config('from_type_edit');
			$this->db->update($this->common->table('from_type'), $arr, array('type_id' => $type_id));
		}
		$row = $this->common->getAll("SELECT type_id, type_name FROM " . $this->common->table('from_type') . " ORDER BY type_id ASC");
		$from_type_list = array();
		foreach($row as $val)
		{
			$from_type_list[$val['type_id']]['type_id'] = $val['type_id'];
			$from_type_list[$val['type_id']]['type_name'] = $val['type_name'];
		}
		write_static_cache("from_type_list", $from_type_list);
		$links[0] = array('href' => '?c=site&m=from_type_info&type_id=' . $type_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=site&m=from_type_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=site&m=from_type_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function from_type_del()
	{
		$this->common->config('from_type_del');
		$type_id = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;
		
		if(empty($type_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->db->delete($this->common->table('from_type'), array('type_id' => $type_id));
		$links[0] = array('href' => '?c=site&m=from_type_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function from_site_list()
	{
		$data = array();
		$data = $this->common->config('from_site_list');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		$this->load->helper('page');
		
		$site_count = $this->model->from_site_count();
		$config = page_config();
		$config['base_url'] = '?c=index&m=admin_list';
		$config['total_rows'] = $site_count;
		$config['per_page'] = '20';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		
		$data['site_list'] = $this->model->from_site_list($page, $config['total_rows']);
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('from_site_list', $data);
	}
	
	public function from_site_info()
	{
		$data = array();
		$site_id = empty($_REQUEST['site_id'])? 0:intval($_REQUEST['site_id']);
		
		if(empty($site_id))
		{
			$data = $this->common->config('from_site_add');
		}
		else
		{
			$data = $this->common->config('from_site_edit');
			
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('from_site') . " WHERE site_id = $site_id");
			$data['info'] = $info;
		}
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('from_site_info', $data);
	}
	
	public function from_site_update()
	{
		$form_action = isset($_REQUEST['form_action'])? $_REQUEST['form_action']:'';
		$site_name = isset($_REQUEST['site_name'])? trim($_REQUEST['site_name']):'';
		$site_domain = isset($_REQUEST['site_domain'])? trim($_REQUEST['site_domain']):'';
		$site_zhu_domain = isset($_REQUEST['site_zhu_domain'])? trim($_REQUEST['site_zhu_domain']):'';
		$site_desc = isset($_REQUEST['site_desc'])? trim($_REQUEST['site_desc']):'';
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		
		if(empty($form_action) || empty($site_name) || empty($site_domain) || empty($site_zhu_domain))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$data = array();
		$arr = array('site_name' => $site_name,
					 'site_domain' => $site_domain,
					 'site_zhu_domain' => $site_zhu_domain,
					 'site_desc' => $site_desc);
		
		if($form_action == 'add')
		{
			$this->common->config('from_site_add');
			$this->db->insert($this->common->table('from_site'), $arr);
			$site_id = $this->db->insert_id();
		}
		else
		{
			$this->common->config('from_site_edit');
			$this->db->update($this->common->table('from_site'), $arr, array('site_id' => $site_id));
		}

		$row = $this->common->getAll("SELECT site_id, site_name, site_domain, site_zhu_domain FROM " . $this->common->table('from_site') . " ORDER BY site_id ASC");
		$from_site_list = array();
		foreach($row as $val)
		{
			$from_site_list[$val['site_id']]['site_id'] = $val['site_id'];
			$from_site_list[$val['site_id']]['site_name'] = $val['site_name'];
			$from_site_list[$val['site_id']]['site_domain'] = $val['site_domain'];
			$from_site_list[$val['site_id']]['site_zhu_domain'] = $val['site_zhu_domain'];
		}
		write_static_cache("from_site_list", $from_site_list);

		$links[0] = array('href' => '?c=site&m=from_site_info&site_id=' . $site_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=site&m=from_site_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=site&m=from_site_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function from_site_del()
	{
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->common->config('from_site_delete');
		$this->db->delete($this->common->table('from_site'), array('site_id' => $site_id));
		$links[0] = array('href' => '?c=site&m=from_site_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function data()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$domain_id = isset($_REQUEST['domain_id'])? intval($_REQUEST['domain_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$site_list = read_static_cache('site');
		$site_data = $this->model->data($site_id, $domain_id, $start, $end);

		$data['site_domain'] = array_flip($site_list[$site_id]);
		$data['site_domain'] = ($domain_id == 0)? 'www':$data['site_domain'][$domain_id];
		$data['domain_id'] = $domain_id;
		$data['site_data'] = $site_data;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_data', $data);
	}
	
	public function area_data()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$area_data = $this->model->area_data($site_id, $start, $end);
		$data['area_data'] = $area_data;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_area_data', $data);
	}
	
	public function order_area_data()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$area_data = $this->model->order_area_data($site_id, $start, $end);
		$data['area_data'] = $area_data;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_order_area_data', $data);
	}
	
	public function site_from_data()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$from_data = $this->model->site_from_data($site_id, $start, $end);
		$data['from_data'] = $from_data;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_from_data', $data);
	}
	
	public function site_page_views()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$page_data = $this->model->site_page_views($site_id, $start, $end);
		$data['page_data'] = $page_data;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_page_views', $data);
	}
	
	public function site_page_path()
	{
		$data = array();
		$data = $this->common->config('site_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$domain_id = isset($_REQUEST['domain_id'])? intval($_REQUEST['domain_id']):0;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$from_site = isset($_REQUEST['from_site'])? intval($_REQUEST['from_site']):0;
		$from_type = isset($_REQUEST['from_type'])? intval($_REQUEST['from_type']):0;
		$is_ask = isset($_REQUEST['is_ask'])? intval($_REQUEST['is_ask']):0;
		$cid = isset($_REQUEST['cid'])? trim($_REQUEST['cid']):'';
		
		$where = 1;
		if($from_site)
		{
			$where .= " AND l.from_site_id = $from_site";
		}
		if($from_type)
		{
			$where .= " AND l.from_type_id = $from_type";
		}
		if($cid)
		{
			$where .= " AND l.load_cid = $cid";
		}
		if($is_ask)
		{
			if($is_ask == 2)
			{
				$where .= " AND l.is_ask = 0";
			}
			else
			{
				$where .= " AND l.is_ask = 1";
			}
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$site_list = read_static_cache('site');
		
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->helper('page');
		$this->load->library('pagination');
		$count = $this->model->page_path_count($site_id, $domain_id, $start, $end, $where);
		$config = page_config();
		$config['base_url'] = '?c=site&m=site_page_path&site_id=' . $site_id . '&domain_id=' . $domain_id . '&start=' . $data['start'] . '&end=' . $data['end'] . '&from_site=' . $from_site . '&from_type=' . $from_type . '&is_ask=' . $is_ask . '&cid=' . $cid;
		$config['total_rows'] = $count;
		$config['per_page'] = '30';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$page_path = $this->model->page_path($site_id, $domain_id, $start, $end, $page, $config['per_page'], $where);

		$data['page'] = $this->pagination->create_links();
		$data['page_path'] = $page_path;
		$data['page_no'] = $page;
		$data['from_site_list']  = read_static_cache('from_site_list');
		$data['from_type_list']  = read_static_cache('from_type_list');
		$data['google_device']   = read_static_cache('google_device');
		$data['google_screen']   = read_static_cache('google_screen');
		$data['google_browser']  = read_static_cache('google_browser');
		$data['google_system']   = read_static_cache('google_system');
		$data['site_info'] = $site_info;
		$data['domain_id'] = $domain_id;
		$data['site_domain'] = array_flip($site_list[$site_id]);
		$data['site_domain'] = ($domain_id == 0)? 'www':$data['site_domain'][$domain_id];
		
		$data['from_site'] = $from_site;
		$data['from_type'] = $from_type;
		$data['is_ask'] = $is_ask;
		$data['cid'] = $cid;
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_page_path', $data);
	}
	
	public function cpc()
	{
		$data = array();
		$data = $this->common->config('site_cpc');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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

			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$site_cpc = $this->model->cpc($site_id, $start, $end);
		$data['site_cpc'] = $site_cpc;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_cpc', $data);
	}
	
	public function cpc_keyword()
	{
		$data = array();
		$data = $this->common->config('site_cpc');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$data['type'] = $type;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		$by = isset($_REQUEST['by'])? intval($_REQUEST['by']):2;
		$order = isset($_REQUEST['order'])? intval($_REQUEST['order']):2;
		
		$data['by'] = $by;
		$data['order'] = $order;
		
		if($by == 1)
		{
			$order_by = ($order == 1)? "shows ASC":"shows DESC";
		}
		elseif($by == 2)
		{
			$order_by = ($order == 1)? "clicks ASC":"clicks DESC";
		}
		elseif($by == 5)
		{
			$order_by = ($order == 1)? "cost ASC":"cost DESC";
		}
		elseif($by == 3)
		{
			if($order == 1)
			{
				$order_by = "click_lv ASC";
			}
			else
			{
				$order_by = "click_lv DESC";
			}
		}
		elseif($by == 4)
		{
			if($order == 1)
			{
				$order_by = "price ASC";
			}
			else
			{
				$order_by = "price DESC";
			}
		}
		elseif($by == 6)
		{
			if($order == 1)
			{
				$order_by = "qian_show_cost ASC";
			}
			else
			{
				$order_by = "qian_show_cost DESC";
			}
		}
		elseif($by == 7)
		{
			$order_by = ($order == 1)? "ask_count ASC":"ask_count DESC";
		}
		elseif($by == 8)
		{
			$order_by = ($order == 1)? "ask_cb ASC":"ask_cb DESC";
		}
		elseif($by == 9)
		{
			$order_by = ($order == 1)? "ask_lv ASC":"ask_lv DESC";
		}
		elseif($by == 10)
		{
			$order_by = ($order == 1)? "order_count ASC":"order_count DESC";
		}
		elseif($by == 11)
		{
			$order_by = ($order == 1)? "order_lv ASC":"order_lv DESC";
		}
		elseif($by == 12)
		{
			$order_by = ($order == 1)? "dao_count ASC":"dao_count DESC";
		}
		elseif($by == 13)
		{
			$order_by = ($order == 1)? "dao_lv ASC":"dao_lv DESC";
		}
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->load->helper('page');
		$this->load->library('pagination');
		
		$count = $this->model->cpc_keyword_count($site_id, $type, $start, $end);
		$config = page_config();
		$config['base_url'] = '?c=site&m=cpc_keyword&site_id=' . $site_id . '&start=' . $data['start'] . '&end=' . $data['end'] . '&by=' . $data['by'] . '&order=' . $data['order'];
		$config['total_rows'] = $count;
		$config['per_page'] = '30';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$site_info = $this->model->site_bd_info($site_id);
		
		$keyword_data = $this->model->cpc_keyword($site_id, $type, $start, $end, $order_by, $page, $config['per_page']);
		$data['keyword_data'] = $keyword_data;
		$data['page_no'] = $page;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_cpc_keyword', $data);
	}
	
	public function cpc_plan_data()
	{
		$data = array();
		$data = $this->common->config('site_cpc');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$data['type'] = $type;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 7));
			$end = date("Y-m-d", time());
			$data['start'] = date("Y年m月d日", (time() - 86400 * 7));
			$data['end'] = date("Y年m月d日", time());
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		$by = isset($_REQUEST['by'])? intval($_REQUEST['by']):2;
		$order = isset($_REQUEST['order'])? intval($_REQUEST['order']):2;
		
		$data['by'] = $by;
		$data['order'] = $order;
		
		if($by == 1)
		{
			$order_by = ($order == 1)? "shows ASC":"shows DESC";
		}
		elseif($by == 2)
		{
			$order_by = ($order == 1)? "clicks ASC":"clicks DESC";
		}
		elseif($by == 5)
		{
			$order_by = ($order == 1)? "cost ASC":"cost DESC";
		}
		elseif($by == 3)
		{
			if($order == 1)
			{
				$order_by = "click_lv ASC";
			}
			else
			{
				$order_by = "click_lv DESC";
			}
		}
		elseif($by == 4)
		{
			if($order == 1)
			{
				$order_by = "price ASC";
			}
			else
			{
				$order_by = "price DESC";
			}
		}
		elseif($by == 6)
		{
			if($order == 1)
			{
				$order_by = "qian_show_cost ASC";
			}
			else
			{
				$order_by = "qian_show_cost DESC";
			}
		}
		elseif($by == 7)
		{
			$order_by = ($order == 1)? "ask_count ASC":"ask_count DESC";
		}
		elseif($by == 8)
		{
			$order_by = ($order == 1)? "ask_cb ASC":"ask_cb DESC";
		}
		elseif($by == 9)
		{
			$order_by = ($order == 1)? "ask_lv ASC":"ask_lv DESC";
		}
		elseif($by == 10)
		{
			$order_by = ($order == 1)? "order_count ASC":"order_count DESC";
		}
		elseif($by == 11)
		{
			$order_by = ($order == 1)? "order_lv ASC":"order_lv DESC";
		}
		elseif($by == 12)
		{
			$order_by = ($order == 1)? "dao_count ASC":"dao_count DESC";
		}
		elseif($by == 13)
		{
			$order_by = ($order == 1)? "dao_lv ASC":"dao_lv DESC";
		}
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->load->helper('page');
		$this->load->library('pagination');
		
		$count = $this->model->cpc_plan_count($site_id, $type, $start, $end);
		$config = page_config();
		$config['base_url'] = '?c=site&m=cpc_plan_data&site_id=' . $site_id . '&start=' . $data['start'] . '&end=' . $data['end'] . '&by=' . $data['by'] . '&order=' . $data['order'];
		$config['total_rows'] = $count;
		$config['per_page'] = '30';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$site_info = $this->model->site_bd_info($site_id);
		
		$cpc_plan_data = $this->model->cpc_plan_data($site_id, $type, $start, $end, $order_by, $page, $config['per_page']);
		$data['plan_data'] = $cpc_plan_data;
		$data['page_no'] = $page;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_cpc_plan_data', $data);
	}
	
	public function cpc_hour()
	{
		$data = array();
		$data = $this->common->config('site_cpc');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$data['type'] = $type;
		$date = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
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
			
			$current = intval((strtotime($start) + strtotime($end)) / 2);
			$data['current'] = date("Y年m月d日", ($current - (86400 * 30)));
		}
		else
		{
			$start = date("Y-m-d", (time() - 86400 * 8));
			$end = date("Y-m-d", time() - 86400 * 1);
			$data['start'] = date("Y年m月d日", (time() - 86400 * 8));
			$data['end'] = date("Y年m月d日", time() - 86400 * 1);
			$data['current'] = date("Y年m月d日", time() - (86400 * 30));
		}
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$cpc_hour = $this->model->cpc_hour($site_id, $type, $start, $end);
		$data['cpc_hour'] = $cpc_hour;
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_cpc_hour', $data);
	}
	
	public function cpc_account()
	{
		$data = array();
		$data = $this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		if(!isset($site_info['bd_update']) || ($site_info['bd_update'] + (3600 * 24)) <= time() )
		{
			//$site_info['site_bd_username'] = mb_convert_encoding($site_info['site_bd_username'],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
			$this->load->library('baidu', array('serviceName' => 'AccountService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAccountInfoRequest' => array('type' => 1));
			$output_response = $this->baidu->soapCall('getAccountInfo', $arguments, $output_headers);
			$account_info = $output_response->accountInfoType;
			$bd_area = $this->lang->line("bd_area");

			if(is_array($account_info->regionTarget))
			{
				foreach($account_info->regionTarget as $val)
				{
					$ba_region[] = $bd_area[$val];
				}
			}
			else
			{
				$ba_region[] = $bd_area[$account_info->regionTarget];
			}

			$arr = array('site_id' => $site_id,
						 'bd_id' => $account_info->userid,
						 'bd_balance' => $account_info->balance,
						 'bd_cost' => $account_info->cost,
						 'bd_budget' => $account_info->budget,
						 'bd_opendomains' => $account_info->balance,
						 'bd_regionTarget' => implode(",", $ba_region),
						 'bd_update' => gmstr2time(date("Y-m-d", time())));
			if(!isset($account_info->payment))
			{
				$arr['bd_payment'] = $account_info->cost;
			}
			else
			{
				$arr['bd_payment'] = $account_info->payment;
			}
			
			if(is_array($account_info->openDomains))
			{
				$arr['bd_opendomains'] = implode(",", $account_info->openDomains);
			}
			else
			{
				$arr['bd_opendomains'] = $account_info->openDomains;
			}
			
			if(!isset($site_info['bd_update']))
			{
				$this->db->insert($this->common->table('site_bd'), $arr);
			}
			else
			{
				$this->db->update($this->common->table('site_bd'), $arr, array('site_id' => $site_id));
			}
			
			$site_info = $this->model->site_bd_info($site_id);
		}
		$data['site_info'] = $site_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('cpc_account', $data);
	}
	
	public function cpc_plan()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$update = isset($_REQUEST['update'])? intval($_REQUEST['update']):0;
		if(empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);

		// 判断是否需要更新全部计划信息
		if($update)
		{			
			$this->load->library('baidu', array('serviceName' => 'CampaignService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAllCampaignRequest' => '');
			$output_response = $this->baidu->soapCall('getAllCampaign', $arguments, $output_headers);
			$plan_arr = $output_response->campaignTypes;
			$plan_arr_arr = array();
			foreach($plan_arr as $val)
			{
				$plan_arr_arr[$val->campaignId]['plan_id'] = $val->campaignId;
				$plan_arr_arr[$val->campaignId]['bd_id'] = $site_info['bd_id'];
				$plan_arr_arr[$val->campaignId]['site_id'] = $site_id;
				$plan_arr_arr[$val->campaignId]['plan_name'] = $val->campaignName;
			}
			
			$plan_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id ORDER BY plan_id ASC");
			//如果账户计划为空，则说明是新加账户，需全部添加账户信息
			if(empty($plan_list))
			{
				foreach($plan_arr_arr as $val)
				{
					$arr = array('plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'plan_name' => $val['plan_name']);	 
					$this->db->insert($this->common->table('bd_plan'), $arr);
				}
			}
			else
			{
				foreach($plan_list as $val)
				{
					$plan_list_arr[$val['plan_id']]['plan_id'] = $val['plan_id'];
					$plan_list_arr[$val['plan_id']]['bd_id'] = $val['bd_id'];
					$plan_list_arr[$val['plan_id']]['site_id'] = $val['site_id'];
					$plan_list_arr[$val['plan_id']]['plan_name'] = $val['plan_name'];
				}
				
				foreach($plan_arr_arr as $key=>$val)
				{
					if(isset($plan_list_arr[$key]))
					{
						$arr = array('plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'plan_name' => $val['plan_name']);
						$this->db->update($this->common->table('bd_plan'), $arr, array('plan_id' => $val['plan_id']));
						
						unset($plan_list_arr[$key]);
					}
					else
					{ 
						$arr = array('plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'plan_name' => $val['plan_name']);
						$this->db->insert($this->common->table('bd_plan'), $arr);
					}
				}
				
				foreach($plan_list_arr as $val)
				{
					$this->db->delete($this->common->table('bd_plan'), array('plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_group'), array('plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_keyword'), array('plan_id' => $val['plan_id']));
				}
			}
			
			$links[0] = array('href' => '?c=site&m=cpc_plan&site_id=' . $site_id, 'text' => $this->lang->line('go_back'));
		    $this->common->msg($this->lang->line('success'), 0, $links);
			exit();
		}
		
		$plan_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id ORDER BY plan_id ASC");
		$data['site_info'] = $site_info;
		$data['plan_list'] = $plan_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('cpc_plan', $data);
	}
	
	public function cpc_plan_keywords()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$plan_id = isset($_REQUEST['plan_id'])? intval($_REQUEST['plan_id']):0;
		$group_id = isset($_REQUEST['group_id'])? trim($_REQUEST['group_id']):'';
		$step    = isset($_REQUEST['step'])? intval($_REQUEST['step']):1;
		
		$site_info = $this->model->site_bd_info($site_id);
		// 更新单元
		if($step == 1)
		{
			if($plan_id < 1)
			{
				$this->common->msg('当前执行的计划ID为空！', 1);
			}
			$this->load->library('baidu', array('serviceName' => 'AdgroupService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAdgroupByCampaignIdRequest' => array('campaignIds' => array($plan_id)));
			$output_response = $this->baidu->soapCall('getAdgroupByCampaignId', $arguments, $output_headers);
			$group_arr = $output_response->campaignAdgroups->adgroupTypes;
			$group_arr_arr = array();
			$group_list_arr = array();
			
			if(isset($group_arr->adgroupId))
			{
				$group_arr_arr[$group_arr->adgroupId]['group_id'] = $group_arr->adgroupId;
				$group_arr_arr[$group_arr->adgroupId]['plan_id'] = $group_arr->campaignId;
				$group_arr_arr[$group_arr->adgroupId]['bd_id'] = $site_info['bd_id'];
				$group_arr_arr[$group_arr->adgroupId]['site_id'] = $site_id;
				$group_arr_arr[$group_arr->adgroupId]['group_name'] = $group_arr->adgroupName;
			}
			else
			{
				foreach($group_arr as $val)
				{
					$group_arr_arr[$val->adgroupId]['group_id'] = $val->adgroupId;
					$group_arr_arr[$val->adgroupId]['plan_id'] = $val->campaignId;
					$group_arr_arr[$val->adgroupId]['bd_id'] = $site_info['bd_id'];
					$group_arr_arr[$val->adgroupId]['site_id'] = $site_id;
					$group_arr_arr[$val->adgroupId]['group_name'] = $val->adgroupName;
				}
			}
			
			$group_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY plan_id ASC");
			//如果账户计划为空，则说明是新加账户，需全部添加账户信息
			if(empty($group_list))
			{
				foreach($group_arr_arr as $val)
				{
					$arr = array('group_id' => $val['group_id'],
								 'plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'group_name' => $val['group_name']);	 
					$this->db->insert($this->common->table('bd_group'), $arr);
				}
				$this->db->update($this->common->table('bd_plan'), array('group_count' => count($group_arr_arr)), array('plan_id' => $val['plan_id'], 'site_id' => $site_id));
			}
			else
			{
				foreach($group_list as $val)
				{
					$group_list_arr[$val['group_id']]['group_id'] = $val['group_id'];
					$group_list_arr[$val['group_id']]['plan_id'] = $val['plan_id'];
					$group_list_arr[$val['group_id']]['bd_id'] = $val['bd_id'];
					$group_list_arr[$val['group_id']]['site_id'] = $val['site_id'];
					$group_list_arr[$val['group_id']]['group_name'] = $val['group_name'];
				}
				
				foreach($group_arr_arr as $key=>$val)
				{
					if(isset($group_list_arr[$key]))
					{
						$arr = array('group_id' => $val['group_id'],
						             'plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'group_name' => $val['group_name']);
						$this->db->update($this->common->table('bd_group'), $arr, array('group_id' => $val['group_id']));
						
						unset($group_list_arr[$key]);
					}
					else
					{ 
						$arr = array('group_id' => $val['group_id'],
						             'plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'group_name' => $val['group_name']);
						$this->db->insert($this->common->table('bd_group'), $arr);
					}
				}
				
				foreach($group_list_arr as $val)
				{
					$this->db->delete($this->common->table('bd_group'), array('group_id' => $val['group_id'], 'plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_keyword'), array('group_id' => $val['group_id'], 'plan_id' => $val['plan_id']));
				}
			}
			$group_id = $this->common->getAll("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY group_id DESC LIMIT 5");
			$group_id = array_map("array_pop",$group_id);
			$group_id = implode(",", $group_id);
			$links[0] = array('href' => '?c=site&m=cpc_plan_keywords&step=2&group_id=' . $group_id . '&site_id=' . $site_id . '&plan_id=' . $plan_id, 'text' => '开始更新关键词信息');
			$this->common->msg($this->lang->line('success'), 0, $links);
		}
		// 更新关键词信息
		elseif($step == 2)
		{
			if(empty($group_id))
			{
				$this->common->msg('当前执行的推广单元ID为空！', 1);
			}
			$adgroupIds = explode(",", $group_id);
			$group_id_min = $adgroupIds[count($adgroupIds) - 1];
			$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getKeywordByAdgroupIdRequest' => array('adgroupIds' => $adgroupIds));
			$output_response = $this->baidu->soapCall('getKeywordByAdgroupId', $arguments, $output_headers);
			$keyword_arr = $output_response->groupKeywords;
			$keyword_arr_arr = array();
			
			if(isset($keyword_arr->keywordTypes))
			{
				$keyword_arr_arr[$group_id] = $keyword_arr->keywordTypes;
			}
			else
			{
				foreach($keyword_arr as $val)
				{
					$keyword_arr_arr[$val->adgroupId] = $val->keywordTypes;
				}
			}
			foreach($keyword_arr_arr as $key => $val)
			{
				$keyword_list_arr = array();
				$group_id = $key;
				$keyword_arr_a = $val;
				
				$group_info = $this->common->getRow("SELECT bd_id, plan_id, site_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND group_id = $group_id");
				$key_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE site_id = $site_id AND group_id = $group_id");
				//如果账户单元下关键词为空，则全部添加关键词信息
				if(empty($key_list))
				{
					if(isset($keyword_arr_a->keywordId))
					{
						$arr = array('key_id' => "$keyword_arr_a->keywordId",
									 'key_keyword' => $keyword_arr_a->keyword,
									 'group_id' => $group_id,
									 'plan_id' => $group_info['plan_id'],
									 'bd_id' => $group_info['bd_id'],
									 'site_id' => $group_info['site_id'],
									 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
									 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
									 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
									 'key_match' => $keyword_arr_a->matchType,
									 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
									 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
						$this->db->insert($this->common->table('bd_keyword'), $arr);
					}
					else
					{
						foreach($keyword_arr_a as $v)
						{
							$arr = array('key_id' => "$v->keywordId",
										 'key_keyword' => $v->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($v->pcDestinationUrl)? '':$v->pcDestinationUrl,
										 'mobile_url' => empty($v->mobileDestinationUrl)? '':$v->mobileDestinationUrl,
										 'key_price' => isset($v->price)? $v->price:'',
										 'key_match' => $v->matchType,
										 'key_url' => empty($v->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v->pcDestinationUrl),
										 'mb_key_url' => empty($v->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v->mobileDestinationUrl));
							$this->db->insert($this->common->table('bd_keyword'), $arr);
						}
					}
					$this->db->update($this->common->table('bd_group'), array('keyword_count' => count($keyword_arr_a)), array('group_id' => $group_id, 'site_id' => $site_id));
				}
				else
				{
					foreach($key_list as $v2)
					{
						$keyword_list_arr[$v2['key_id']]['key_id'] = $v2['key_id'];
						$keyword_list_arr[$v2['key_id']]['key_keyword'] = $v2['key_keyword'];
						$keyword_list_arr[$v2['key_id']]['group_id'] = $v2['group_id'];
						$keyword_list_arr[$v2['key_id']]['plan_id'] = $v2['plan_id'];
						$keyword_list_arr[$v2['key_id']]['bd_id'] = $v2['bd_id'];
						$keyword_list_arr[$v2['key_id']]['site_id'] = $v2['site_id'];
						$keyword_list_arr[$v2['key_id']]['pc_url'] = $v2['pc_url'];
						$keyword_list_arr[$v2['key_id']]['mobile_url'] = $v2['mobile_url'];
						$keyword_list_arr[$v2['key_id']]['key_price'] = $v2['key_price'];
						$keyword_list_arr[$v2['key_id']]['key_match'] = $v2['key_match'];
						$keyword_list_arr[$v2['key_id']]['key_url'] = $v2['key_url'];
						$keyword_list_arr[$v2['key_id']]['mb_key_url'] = $v2['mb_key_url'];
					}

					
					if(isset($keyword_arr_a->keywordId))
					{
						$kk = $keyword_arr_a->keywordId;
						if(isset($keyword_list_arr["$kk"]))
						{
							$arr = array('key_id' => "$keyword_arr_a->keywordId",
										 'key_keyword' => $keyword_arr_a->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
										 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
										 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
										 'key_match' => $keyword_arr_a->matchType,
										 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
										 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
							$this->db->update($this->common->table('bd_keyword'), $arr, array('key_id' => "$keyword_arr_a->keywordId", 'site_id' => $site_id));
							unset($keyword_list_arr["$kk"]);
						}
						else
						{ 
							$arr = array('key_id' => "$keyword_arr_a->keywordId",
										 'key_keyword' => $keyword_arr_a->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
										 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
										 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
										 'key_match' => $keyword_arr_a->matchType,
										 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
										 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
							$this->db->insert($this->common->table('bd_keyword'), $arr);
						}
					}
					else
					{
						foreach($keyword_arr_a as $v1)
						{
							$kk = $v1->keywordId;
							if(isset($keyword_list_arr["$kk"]))
							{
								$arr = array('key_id' => "$v1->keywordId",
											 'key_keyword' => $v1->keyword,
											 'group_id' => $group_id,
											 'plan_id' => $group_info['plan_id'],
											 'bd_id' => $group_info['bd_id'],
											 'site_id' => $group_info['site_id'],
											 'pc_url' => empty($v1->pcDestinationUrl)? '':$v1->pcDestinationUrl,
											 'mobile_url' => empty($v1->mobileDestinationUrl)? '':$v1->mobileDestinationUrl,
											 'key_price' => isset($v1->price)? $v1->price:'',
											 'key_match' => $v1->matchType,
											 'key_url' => empty($v1->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v1->pcDestinationUrl),
										 	 'mb_key_url' => empty($v1->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v1->mobileDestinationUrl));
								$this->db->update($this->common->table('bd_keyword'), $arr, array('key_id' => "$v1->keywordId", 'site_id' => $site_id));
								unset($keyword_list_arr["$kk"]);
							}
							else
							{ 
								$arr = array('key_id' => "$v1->keywordId",
											 'key_keyword' => $v1->keyword,
											 'group_id' => $group_id,
											 'plan_id' => $group_info['plan_id'],
											 'bd_id' => $group_info['bd_id'],
											 'site_id' => $group_info['site_id'],
											 'pc_url' => empty($v1->pcDestinationUrl)? '':$v1->pcDestinationUrl,
											 'mobile_url' => empty($v1->mobileDestinationUrl)? '':$v1->mobileDestinationUrl,
											 'key_price' => isset($v1->price)? $v1->price:'',
											 'key_match' => $v1->matchType,
											 'key_url' => empty($v1->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v1->pcDestinationUrl),
											 'mb_key_url' => empty($v1->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v1->mobileDestinationUrl));
								$this->db->insert($this->common->table('bd_keyword'), $arr);
							}
						}
					}
					
					foreach($keyword_list_arr as $val)
					{
						$this->db->delete($this->common->table('bd_keyword'), array('group_id' => $val['group_id'], 'site_id' => $val['site_id']));
					}
				}
			}
			$group_id = $this->common->getAll("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND group_id < $group_id_min  AND plan_id = $plan_id ORDER BY group_id DESC LIMIT 5");
			$group_id = array_map("array_pop",$group_id);
			$group_id = implode(",", $group_id);
			if(empty($group_id))
			{
				$links[0] = array('href' => '?c=site&m=cpc_plan&site_id=' . $site_id, 'text' => '返回账号计划页');
		    	$this->common->msg($this->lang->line('success'), 0, $links);
			}
			else
			{
				$links[0] = array('href' => '?c=site&m=cpc_plan_keywords&step=2&group_id=' . $group_id . '&site_id=' . $site_id . '&plan_id=' . $plan_id, 'text' => '更新下五个单元的关键词，单元ID为：' . $group_id);
		    	$this->common->msg($this->lang->line('success'), 0, $links, true, true, 1);
			}
		}
	}
	
	public function chuang_keyword_url()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$plan_id = isset($_REQUEST['plan_id'])? intval($_REQUEST['plan_id']):0;
		$group_id = isset($_REQUEST['group_id'])? intval($_REQUEST['group_id']):0;
		
		$site_info = $this->model->site_bd_info($site_id);
		
		if($group_id == 0)
		{
			$group_id = $this->common->getOne("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY group_id DESC LIMIT 1");
		}
		
		$this->load->library('baidu', array('serviceName' => 'CreativeService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('getCreativeByAdgroupIdRequest' => array('adgroupIds' => array($group_id)));
		$output_response = $this->baidu->soapCall('getCreativeByAdgroupId', $arguments, $output_headers);
		
		if(isset($output_response->groupCreatives->creativeTypes))
		{
			$url_arr = $output_response->groupCreatives->creativeTypes;
			$pc_url = $url_arr[0]->pcDestinationUrl;
			$mobile_url = $url_arr[0]->mobileDestinationUrl;
			
			$this->db->update($this->common->table('bd_keyword'), array('key_url' => $pc_url), array('site_id' => $site_id, 'plan_id' => $plan_id, 'group_id' => $group_id, 'key_url' => ""));
			$this->db->update($this->common->table('bd_keyword'), array('mb_key_url' => $mobile_url), array('site_id' => $site_id, 'plan_id' => $plan_id, 'group_id' => $group_id, 'mb_key_url' => ""));
		}
		
		$group_id = $this->common->getOne("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id AND group_id < $group_id ORDER BY group_id DESC LIMIT 1");
		if(empty($group_id))
		{
			$links[0] = array('href' => '?c=site&m=cpc_plan&site_id=' . $site_id, 'text' => '返回账号计划页');
			$this->common->msg($this->lang->line('success'), 0, $links);
		}
		else
		{
			$links[0] = array('href' => '?c=site&m=chuang_keyword_url&group_id=' . $group_id . '&site_id=' . $site_id . '&plan_id=' . $plan_id, 'text' => '下载一下单元创意URL，单元ID为：' . $group_id);
			$this->common->msg($this->lang->line('success'), 0, $links, true, true, 1);
		}
	}
	
	public function plan_keyword_update()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$plan_id = isset($_REQUEST['plan_id'])? intval($_REQUEST['plan_id']):0;
		$url_add = isset($_REQUEST['url_add'])? trim($_REQUEST['url_add']):'';
		$add_str    = isset($_REQUEST['add_str'])? trim($_REQUEST['add_str']):'';
		
		$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE plan_id = $plan_id AND site_id = $site_id ORDER BY key_id ASC");
		if(empty($keyword_list))
		{
			echo 3;
			exit();
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$keywordtypes = array();
		foreach($keyword_list as $key => $val)
		{
			$keywordtypes[$key]['keywordId']        = floatval($val['key_id']);
			$key_name = $this->common->getOne("SELECT key_keyword FROM " . $this->common->table('bd_keyword') . " WHERE key_id = " . floatval($val['key_id']));
			// 把变量转成具体的值
			$tag_arr = array('{$plan_id}', '{$group_id}', '{$key_id}', '{$plan_name}', '{$group_name}', '{$key_name}');
			$val_arr = array($val['plan_id'], $val['group_id'], $val['key_id'], urlencode($plan_name), urlencode($group_name), urlencode($key_name));
			$url_tag_re = str_replace($tag_arr, $val_arr, $url_tag);
			
			$pc_url = $val['pc_url'];
			$mobile_url = $val['mobile_url'];
			$key_url = $val['key_url'];
			$mb_key_url = $val['mb_key_url'];
			$is_pc_first_tag = !strpos($pc_url, "?");
			$is_key_first_tag = !strpos($key_url, "?");
			if($url_add == 1)
			{
				if($type == 0) // 所有URL都要修改
				{
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
				}
				elseif($type == 1) // 只修改PC端
				{
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
				}
				else // 只修改移动端
				{
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
				}
			}
			else
			{
				if($type == 0)
				{
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
				}
				elseif($type == 1)
				{
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}

					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
				}
				else
				{
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
				}
			}
		}

		$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('updateKeywordRequest' => array('keywordTypes' => $keywordtypes));
		$output_response = $this->baidu->soapCall('updateKeyword', $arguments, $output_headers);
	}
	
	//更新账户全部信息下来
	public function cpc_all_keywrods()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$plan_id = isset($_REQUEST['plan_id'])? intval($_REQUEST['plan_id']):0;
		$group_id = isset($_REQUEST['group_id'])? trim($_REQUEST['group_id']):'';
		$step    = isset($_REQUEST['step'])? intval($_REQUEST['step']):1;
		
		$site_info = $this->model->site_bd_info($site_id);
		// 更新计划
		if($step == 1)
		{
			$this->load->library('baidu', array('serviceName' => 'CampaignService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAllCampaignRequest' => '');
			$output_response = $this->baidu->soapCall('getAllCampaign', $arguments, $output_headers);
			$plan_arr = $output_response->campaignTypes;
			$plan_arr_arr = array();
			foreach($plan_arr as $val)
			{
				$plan_arr_arr[$val->campaignId]['plan_id'] = $val->campaignId;
				$plan_arr_arr[$val->campaignId]['bd_id'] = $site_info['bd_id'];
				$plan_arr_arr[$val->campaignId]['site_id'] = $site_id;
				$plan_arr_arr[$val->campaignId]['plan_name'] = $val->campaignName;
			}
			
			$plan_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id ORDER BY plan_id ASC");
			//如果账户计划为空，则说明是新加账户，需全部添加账户信息
			if(empty($plan_list))
			{
				foreach($plan_arr_arr as $val)
				{
					$arr = array('plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'plan_name' => $val['plan_name']);	 
					$this->db->insert($this->common->table('bd_plan'), $arr);
				}
			}
			else
			{
				foreach($plan_list as $val)
				{
					$plan_list_arr[$val['plan_id']]['plan_id'] = $val['plan_id'];
					$plan_list_arr[$val['plan_id']]['bd_id'] = $val['bd_id'];
					$plan_list_arr[$val['plan_id']]['site_id'] = $val['site_id'];
					$plan_list_arr[$val['plan_id']]['plan_name'] = $val['plan_name'];
				}
				
				foreach($plan_arr_arr as $key=>$val)
				{
					if(isset($plan_list_arr[$key]))
					{
						$arr = array('plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'plan_name' => $val['plan_name']);
						$this->db->update($this->common->table('bd_plan'), $arr, array('plan_id' => $val['plan_id']));
						
						unset($plan_list_arr[$key]);
					}
					else
					{ 
						$arr = array('plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'plan_name' => $val['plan_name']);
						$this->db->insert($this->common->table('bd_plan'), $arr);
					}
				}
				
				foreach($plan_list_arr as $val)
				{
					$this->db->delete($this->common->table('bd_plan'), array('plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_group'), array('plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_keyword'), array('plan_id' => $val['plan_id']));
				}
			}
			$plan_id = $this->common->getOne("SELECT plan_id FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id ORDER BY plan_id DESC LIMIT 1");
			$links[0] = array('href' => '?c=site&m=cpc_all_keywrods&step=2&plan_id=' . $plan_id . '&site_id=' . $site_id, 'text' => '开始更新推广单元信息');
		    $this->common->msg($this->lang->line('success'), 0, $links);
		}
		// 逐步更新各个计划下的单元
		elseif($step == 2)
		{
			if($plan_id < 1)
			{
				$this->common->msg('当前执行的计划ID为空！', 1);
			}
			$this->load->library('baidu', array('serviceName' => 'AdgroupService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAdgroupByCampaignIdRequest' => array('campaignIds' => array($plan_id)));
			$output_response = $this->baidu->soapCall('getAdgroupByCampaignId', $arguments, $output_headers);
			$group_arr = $output_response->campaignAdgroups->adgroupTypes;
			$group_arr_arr = array();
			$group_list_arr = array();
			
			if(isset($group_arr->adgroupId))
			{
				$group_arr_arr[$group_arr->adgroupId]['group_id'] = $group_arr->adgroupId;
				$group_arr_arr[$group_arr->adgroupId]['plan_id'] = $group_arr->campaignId;
				$group_arr_arr[$group_arr->adgroupId]['bd_id'] = $site_info['bd_id'];
				$group_arr_arr[$group_arr->adgroupId]['site_id'] = $site_id;
				$group_arr_arr[$group_arr->adgroupId]['group_name'] = $group_arr->adgroupName;
			}
			else
			{
				foreach($group_arr as $val)
				{
					$group_arr_arr[$val->adgroupId]['group_id'] = $val->adgroupId;
					$group_arr_arr[$val->adgroupId]['plan_id'] = $val->campaignId;
					$group_arr_arr[$val->adgroupId]['bd_id'] = $site_info['bd_id'];
					$group_arr_arr[$val->adgroupId]['site_id'] = $site_id;
					$group_arr_arr[$val->adgroupId]['group_name'] = $val->adgroupName;
				}
			}
			
			$group_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY plan_id ASC");
			//如果账户计划为空，则说明是新加账户，需全部添加账户信息
			if(empty($group_list))
			{
				foreach($group_arr_arr as $val)
				{
					$arr = array('group_id' => $val['group_id'],
								 'plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'group_name' => $val['group_name']);	 
					$this->db->insert($this->common->table('bd_group'), $arr);
				}
				$this->db->update($this->common->table('bd_plan'), array('group_count' => count($group_arr_arr)), array('plan_id' => $val['plan_id'], 'site_id' => $site_id));
			}
			else
			{
				foreach($group_list as $val)
				{
					$group_list_arr[$val['group_id']]['group_id'] = $val['group_id'];
					$group_list_arr[$val['group_id']]['plan_id'] = $val['plan_id'];
					$group_list_arr[$val['group_id']]['bd_id'] = $val['bd_id'];
					$group_list_arr[$val['group_id']]['site_id'] = $val['site_id'];
					$group_list_arr[$val['group_id']]['group_name'] = $val['group_name'];
				}
				
				foreach($group_arr_arr as $key=>$val)
				{
					if(isset($group_list_arr[$key]))
					{
						$arr = array('group_id' => $val['group_id'],
						             'plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'group_name' => $val['group_name']);
						$this->db->update($this->common->table('bd_group'), $arr, array('group_id' => $val['group_id']));
						
						unset($group_list_arr[$key]);
					}
					else
					{ 
						$arr = array('group_id' => $val['group_id'],
						             'plan_id' => $val['plan_id'],
									 'bd_id' => $val['bd_id'],
									 'site_id' => $val['site_id'],
									 'group_name' => $val['group_name']);
						$this->db->insert($this->common->table('bd_group'), $arr);
					}
				}
				
				foreach($group_list_arr as $val)
				{
					$this->db->delete($this->common->table('bd_group'), array('group_id' => $val['group_id'], 'plan_id' => $val['plan_id']));
					$this->db->delete($this->common->table('bd_keyword'), array('group_id' => $val['group_id'], 'plan_id' => $val['plan_id']));
				}
			}
			$plan_id = $this->common->getOne("SELECT plan_id FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id AND plan_id < $plan_id ORDER BY plan_id DESC LIMIT 1");
			if(empty($plan_id))
			{
				$group_id = $this->common->getAll("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id ORDER BY group_id DESC LIMIT 5");
				$group_id = array_map("array_pop",$group_id);
				$group_id = implode(",", $group_id);
				$links[0] = array('href' => '?c=site&m=cpc_all_keywrods&step=3&group_id=' . $group_id . '&site_id=' . $site_id, 'text' => '开始更新关键词信息');
		    	$this->common->msg($this->lang->line('success'), 0, $links);
			}
			else
			{
				$links[0] = array('href' => '?c=site&m=cpc_all_keywrods&step=2&plan_id=' . $plan_id . '&site_id=' . $site_id, 'text' => '更新下一个计划的推广单元，计划ID为：' . $plan_id);
		    	$this->common->msg($this->lang->line('success'), 0, $links, true, true, 1);
			}
		}
		
		// 更新关键词信息
		elseif($step == 3)
		{
			if(empty($group_id))
			{
				$this->common->msg('当前执行的推广单元ID为空！', 1);
			}
			$adgroupIds = explode(",", $group_id);
			$group_id_min = $adgroupIds[count($adgroupIds) - 1];
			$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getKeywordByAdgroupIdRequest' => array('adgroupIds' => $adgroupIds));
			$output_response = $this->baidu->soapCall('getKeywordByAdgroupId', $arguments, $output_headers);
			$keyword_arr = $output_response->groupKeywords;
			$keyword_arr_arr = array();
			
			if(isset($keyword_arr->keywordTypes))
			{
				$keyword_arr_arr[$group_id] = $keyword_arr->keywordTypes;
			}
			else
			{
				foreach($keyword_arr as $val)
				{
					$keyword_arr_arr[$val->adgroupId] = $val->keywordTypes;
				}
			}
			foreach($keyword_arr_arr as $key => $val)
			{
				$keyword_list_arr = array();
				$group_id = $key;
				$keyword_arr_a = $val;
				
				$group_info = $this->common->getRow("SELECT bd_id, plan_id, site_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND group_id = $group_id");
				$key_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE site_id = $site_id AND group_id = $group_id");
				//如果账户单元下关键词为空，则全部添加关键词信息
				if(empty($key_list))
				{
					if(isset($keyword_arr_a->keywordId))
					{
						$arr = array('key_id' => "$keyword_arr_a->keywordId",
									 'key_keyword' => $keyword_arr_a->keyword,
									 'group_id' => $group_id,
									 'plan_id' => $group_info['plan_id'],
									 'bd_id' => $group_info['bd_id'],
									 'site_id' => $group_info['site_id'],
									 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
									 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
									 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
									 'key_match' => $keyword_arr_a->matchType,
									 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
									 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
						$this->db->insert($this->common->table('bd_keyword'), $arr);
					}
					else
					{
						foreach($keyword_arr_a as $v)
						{
							$arr = array('key_id' => "$v->keywordId",
										 'key_keyword' => $v->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($v->pcDestinationUrl)? '':$v->pcDestinationUrl,
										 'mobile_url' => empty($v->mobileDestinationUrl)? '':$v->mobileDestinationUrl,
										 'key_price' => isset($v->price)? $v->price:'',
										 'key_match' => $v->matchType,
										 'key_url' => empty($v->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v->pcDestinationUrl),
										 'mb_key_url' => empty($v->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v->mobileDestinationUrl));
							$this->db->insert($this->common->table('bd_keyword'), $arr);
						}
					}
					$this->db->update($this->common->table('bd_group'), array('keyword_count' => count($keyword_arr_a)), array('group_id' => $group_id, 'site_id' => $site_id));
				}
				else
				{
					foreach($key_list as $v2)
					{
						$keyword_list_arr[$v2['key_id']]['key_id'] = $v2['key_id'];
						$keyword_list_arr[$v2['key_id']]['key_keyword'] = $v2['key_keyword'];
						$keyword_list_arr[$v2['key_id']]['group_id'] = $v2['group_id'];
						$keyword_list_arr[$v2['key_id']]['plan_id'] = $v2['plan_id'];
						$keyword_list_arr[$v2['key_id']]['bd_id'] = $v2['bd_id'];
						$keyword_list_arr[$v2['key_id']]['site_id'] = $v2['site_id'];
						$keyword_list_arr[$v2['key_id']]['pc_url'] = $v2['pc_url'];
						$keyword_list_arr[$v2['key_id']]['mobile_url'] = $v2['mobile_url'];
						$keyword_list_arr[$v2['key_id']]['key_price'] = $v2['key_price'];
						$keyword_list_arr[$v2['key_id']]['key_match'] = $v2['key_match'];
						$keyword_list_arr[$v2['key_id']]['key_url'] = $v2['key_url'];
						$keyword_list_arr[$v2['key_id']]['mb_key_url'] = $v2['mb_key_url'];
					}

					
					if(isset($keyword_arr_a->keywordId))
					{
						$kk = $keyword_arr_a->keywordId;
						if(isset($keyword_list_arr["$kk"]))
						{
							$arr = array('key_id' => "$keyword_arr_a->keywordId",
										 'key_keyword' => $keyword_arr_a->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
										 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
										 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
										 'key_match' => $keyword_arr_a->matchType,
										 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
										 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
							$this->db->update($this->common->table('bd_keyword'), $arr, array('key_id' => "$keyword_arr_a->keywordId", 'site_id' => $site_id));
							unset($keyword_list_arr["$kk"]);
						}
						else
						{ 
							$arr = array('key_id' => "$keyword_arr_a->keywordId",
										 'key_keyword' => $keyword_arr_a->keyword,
										 'group_id' => $group_id,
										 'plan_id' => $group_info['plan_id'],
										 'bd_id' => $group_info['bd_id'],
										 'site_id' => $group_info['site_id'],
										 'pc_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$keyword_arr_a->pcDestinationUrl,
										 'mobile_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$keyword_arr_a->mobileDestinationUrl,
										 'key_price' => isset($keyword_arr_a->price)? $keyword_arr_a->price:'',
										 'key_match' => $keyword_arr_a->matchType,
										 'key_url' => empty($keyword_arr_a->pcDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->pcDestinationUrl),
										 'mb_key_url' => empty($keyword_arr_a->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($keyword_arr_a->mobileDestinationUrl));
							$this->db->insert($this->common->table('bd_keyword'), $arr);
						}
					}
					else
					{
						foreach($keyword_arr_a as $v1)
						{
							$kk = $v1->keywordId;
							if(isset($keyword_list_arr["$kk"]))
							{
								$arr = array('key_id' => "$v1->keywordId",
											 'key_keyword' => $v1->keyword,
											 'group_id' => $group_id,
											 'plan_id' => $group_info['plan_id'],
											 'bd_id' => $group_info['bd_id'],
											 'site_id' => $group_info['site_id'],
											 'pc_url' => empty($v1->pcDestinationUrl)? '':$v1->pcDestinationUrl,
											 'mobile_url' => empty($v1->mobileDestinationUrl)? '':$v1->mobileDestinationUrl,
											 'key_price' => isset($v1->price)? $v1->price:'',
											 'key_match' => $v1->matchType,
											 'key_url' => empty($v1->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v1->pcDestinationUrl),
										     'mb_key_url' => empty($v1->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v1->mobileDestinationUrl));
								$this->db->update($this->common->table('bd_keyword'), $arr, array('key_id' => "$v1->keywordId", 'site_id' => $site_id));
								unset($keyword_list_arr["$kk"]);
							}
							else
							{ 
								$arr = array('key_id' => "$v1->keywordId",
											 'key_keyword' => $v1->keyword,
											 'group_id' => $group_id,
											 'plan_id' => $group_info['plan_id'],
											 'bd_id' => $group_info['bd_id'],
											 'site_id' => $group_info['site_id'],
											 'pc_url' => empty($v1->pcDestinationUrl)? '':$v1->pcDestinationUrl,
											 'mobile_url' => empty($v1->mobileDestinationUrl)? '':$v1->mobileDestinationUrl,
											 'key_price' => isset($v1->price)? $v1->price:'',
											 'key_match' => $v1->matchType,
											 'key_url' => empty($v1->pcDestinationUrl)? '':$this->common->baidu_cpc_url($v1->pcDestinationUrl),
											 'mb_key_url' => empty($v1->mobileDestinationUrl)? '':$this->common->baidu_cpc_url($v1->mobileDestinationUrl));
								$this->db->insert($this->common->table('bd_keyword'), $arr);
							}
						}
					}
					
					foreach($keyword_list_arr as $val)
					{
						$this->db->delete($this->common->table('bd_keyword'), array('group_id' => $val['group_id'], 'site_id' => $val['site_id']));
					}
				}
			}
			$group_id = $this->common->getAll("SELECT group_id FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND group_id < $group_id_min ORDER BY group_id DESC LIMIT 5");
			$group_id = array_map("array_pop",$group_id);
			$group_id = implode(",", $group_id);
			if(empty($group_id))
			{
				// 记录最后更新时间
				$this->db->query("UPDATE " . $this->common->table('site_bd') . " SET bd_url_down = " . time() . " WHERE site_id = $site_id");
				$links[0] = array('href' => '?c=site&m=cpc_plan&site_id=' . $site_id, 'text' => '返回账号计划页');
		    	$this->common->msg($this->lang->line('success'), 0, $links);
			}
			else
			{
				$links[0] = array('href' => '?c=site&m=cpc_all_keywrods&step=3&group_id=' . $group_id . '&site_id=' . $site_id, 'text' => '更新下五个单元的关键词，单元ID为：' . $group_id);
		    	$this->common->msg($this->lang->line('success'), 0, $links, true, true, 1);
			}
		}
	}
	
	public function cpc_group_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0");
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$plan_id = isset($_REQUEST['plan_id'])? $_REQUEST['plan_id']:0;
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$update = isset($_REQUEST['update'])? intval($_REQUEST['update']):0;
		if(empty($plan_id) || empty($site_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$site_info = $this->model->site_bd_info($site_id);
		$group_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY group_id ASC");
		
		if($update || empty($group_list))
		{
			$this->load->library('baidu', array('serviceName' => 'AdgroupService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getAdgroupByCampaignIdRequest ' => array('campaignIds' => array($plan_id)));
			$output_response = $this->baidu->soapCall('getAdgroupByCampaignId', $arguments, $output_headers);
			$group_arr = $output_response->campaignAdgroups->adgroupTypes;
			
			// 如果该单元下只有一个关键词，则处理，使之成为二维数组类型
			if(isset($group_arr->adgroupId))
			{
				$tmp = new stdClass();
				foreach($group_arr as $key => $val)
				{
					$tmp->hui->$key = $val;
				}
				$group_arr = $tmp;
			}
			
			$group_arr_arr = array();
			foreach($group_arr as $val)
			{
				$group_arr_arr[$val->adgroupId]['group_id'] = $val->adgroupId;
				$group_arr_arr[$val->adgroupId]['bd_id'] = $site_info['bd_id'];
				$group_arr_arr[$val->adgroupId]['site_id'] = $site_id;
				$group_arr_arr[$val->adgroupId]['plan_id'] = $val->campaignId;
				$group_arr_arr[$val->adgroupId]['group_name'] = $val->adgroupName;
			}
			
			if(empty($group_list))
			{
				foreach($group_arr_arr as $val)
				{
					$arr = array('group_id' => $val['group_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'plan_id' => $val['plan_id'],
								 'group_name' => $val['group_name']);
					$this->db->insert($this->common->table('bd_group'), $arr);
				}
			}
			else
			{
				foreach($group_list as $val)
				{
					$group_list_arr[$val['group_id']]['group_id'] = $val['group_id'];
					$group_list_arr[$val['group_id']]['bd_id'] = $val['bd_id'];
					$group_list_arr[$val['group_id']]['site_id'] = $val['site_id'];
					$group_list_arr[$val['group_id']]['plan_id'] = $val['plan_id'];
					$group_list_arr[$val['group_id']]['group_name'] = $val['group_name'];
				}
				
				$group_update = array_intersect($group_arr_arr, $group_list_arr);  //计算2个数组的交集，以确定更新部分的计划
				$group_add = array_diff($group_arr_arr, $group_list_arr); // 计算在新获取数组，但不在已有数据中的数组，以便确定新添加的计划。
				$group_del = array_diff($group_list_arr, $group_arr_arr);
				
				foreach($group_update as $val)
				{
					$arr = array('group_id' => $val['group_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'plan_id' => $val['plan_id'],
								 'group_name' => $val['group_name']);
					$this->db->update($this->common->table('bd_group'), $arr, array('group_id' => $val['group_id']));
				}
				
				foreach($group_add as $val)
				{
					$arr = array('group_id' => $val['group_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'plan_id' => $val['plan_id'],
								 'group_name' => $val['group_name']);
					$this->db->insert($this->common->table('bd_group'), $arr);
				}
				
				foreach($group_del as $val)
				{
					$this->db->delete($this->common->table('bd_group'), array('group_id' => $val['group_id']));
					$this->db->delete($this->common->table('bd_keyword'), array('group_id' => $val['group_id']));
				}
			}
			$this->db->update($this->common->table('bd_plan'), array('group_count' => sizeof($group_arr_arr)), array('plan_id' => $plan_id));
			$group_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_group') . " WHERE site_id = $site_id AND plan_id = $plan_id ORDER BY group_id ASC");
		}
		$group_list = json_encode($group_list);
		echo $group_list;
	}
	
	public function cpc_keyword_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$data = array();
		$data = $this->common->config('cpc_account');
		
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$plan_id = isset($_REQUEST['plan_id'])? $_REQUEST['plan_id']:0;
		$group_id = isset($_REQUEST['group_id'])? $_REQUEST['group_id']:0;
		$update = isset($_REQUEST['update'])? intval($_REQUEST['update']):0;
		if(empty($site_id) || empty($plan_id) || empty($group_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$group_info = $this->common->getRow("SELECT * FROM " . $this->common->table('bd_group') . " WHERE group_id = $group_id AND site_id = $site_id");
		if(($group_info['keyword_count'] == -1) && ($update == 0))
		{
			echo 'empty';
			exit();
		}
		
		$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE group_id = $group_id AND site_id = $site_id ORDER BY key_id ASC");
		if($update || empty($keyword_list))
		{
			$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getKeywordByAdgroupIdRequest' => array('adgroupIds' => array($group_id)));
			$output_response = $this->baidu->soapCall('getKeywordByAdgroupId', $arguments, $output_headers);
			if(!isset($output_response->groupKeywords->keywordTypes))
			{
				$this->db->update($this->common->table("bd_group"), array('keyword_count' => 0), array('group_id' => $group_id, 'site_id' => $site_id));
				echo 'empty';
				exit();
			}
			$keyword_arr = $output_response->groupKeywords->keywordTypes;

			// 如果该单元下只有一个关键词，则处理，使之成为二维数组类型
			if(isset($keyword_arr->keywordId))
			{
				$tmp = new stdClass();
				foreach($keyword_arr as $key => $val)
				{
					$tmp->hui->$key = $val;
				}
				$keyword_arr = $tmp;
			}
			
			$keyword_arr_arr = array();
			foreach($keyword_arr as $val)
			{
				$keyword_arr_arr[$val->keywordId]['key_id'] = $val->keywordId;
				$keyword_arr_arr[$val->keywordId]['key_keyword'] = $val->keyword;
				$keyword_arr_arr[$val->keywordId]['group_id'] = $val->adgroupId;
				$keyword_arr_arr[$val->keywordId]['plan_id'] = $plan_id;
				$keyword_arr_arr[$val->keywordId]['bd_id'] = $site_info['bd_id'];
				$keyword_arr_arr[$val->keywordId]['site_id'] = $site_id;
				$keyword_arr_arr[$val->keywordId]['pc_url'] = isset($val->pcDestinationUrl)? $val->pcDestinationUrl:'';
				$keyword_arr_arr[$val->keywordId]['mobile_url'] = isset($val->mobileDestinationUrl)? $val->mobileDestinationUrl:'';
				$keyword_arr_arr[$val->keywordId]['key_price'] = isset($val->price)? $val->price:'';
				$keyword_arr_arr[$val->keywordId]['key_match'] = $val->matchType;
			}
			
			if(empty($keyword_list))
			{
				foreach($keyword_arr_arr as $val)
				{
					$arr = array('key_id' => $val['key_id'],
								 'key_keyword' => $val['key_keyword'],
								 'group_id' => $val['group_id'],
								 'plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'pc_url' => $val['pc_url'],
								 'mobile_url' => $val['mobile_url'],
								 'key_price' => $val['key_price'],
								 'key_match' => $val['key_match'],
								 'key_url' => $this->common->baidu_cpc_url($val['pc_url']),
								 'mb_key_url' => $this->common->baidu_cpc_url($val['mobile_url']));
					$this->db->insert($this->common->table('bd_keyword'), $arr);
				}
			}
			else
			{
				foreach($keyword_list as $val)
				{
					$keyword_list_arr[$val['key_id']]['key_id'] = $val['key_id'];
					$keyword_list_arr[$val['key_id']]['key_keyword'] = $val['key_keyword'];
					$keyword_list_arr[$val['key_id']]['group_id'] = $val['group_id'];
					$keyword_list_arr[$val['key_id']]['plan_id'] = $val['plan_id'];
					$keyword_list_arr[$val['key_id']]['bd_id'] = $val['bd_id'];
					$keyword_list_arr[$val['key_id']]['site_id'] = $val['site_id'];
					$keyword_list_arr[$val['key_id']]['pc_url'] = $val['pc_url'];
					$keyword_list_arr[$val['key_id']]['mobile_url'] = $val['mobile_url'];
					$keyword_list_arr[$val['key_id']]['key_price'] = $val['key_price'];
					$keyword_list_arr[$val['key_id']]['key_match'] = $val['key_match'];
					$keyword_list_arr[$val['key_id']]['key_url'] = $val['key_url'];
					$keyword_list_arr[$val['key_id']]['mb_key_url'] = $val['mb_key_url'];
				}
				
				$keyword_update = array_intersect($keyword_arr_arr, $keyword_list_arr);  //计算2个数组的交集，以确定更新部分的计划
				$keyword_add = array_diff($keyword_arr_arr, $keyword_list_arr); // 计算在新获取数组，但不在已有数据中的数组，以便确定新添加的计划。
				$keyword_del = array_diff($keyword_list_arr, $keyword_arr_arr);
				
				foreach($keyword_update as $val)
				{
					$arr = array('key_id' => $val['key_id'],
								 'key_keyword' => $val['key_keyword'],
								 'group_id' => $val['group_id'],
								 'plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'pc_url' => $val['pc_url'],
								 'mobile_url' => $val['mobile_url'],
								 'key_price' => $val['key_price'],
								 'key_match' => $val['key_match'],
								 'key_url' => $this->common->baidu_cpc_url($val['pc_url']),
								 'mb_key_url' => $this->common->baidu_cpc_url($val['mobile_url']));
					$this->db->update($this->common->table('bd_keyword'), $arr, array('key_id' => $val['key_id']));
				}
				
				foreach($keyword_add as $val)
				{
					$arr = array('key_id' => $val['key_id'],
								 'key_keyword' => $val['key_keyword'],
								 'group_id' => $val['group_id'],
								 'plan_id' => $val['plan_id'],
								 'bd_id' => $val['bd_id'],
								 'site_id' => $val['site_id'],
								 'pc_url' => $val['pc_url'],
								 'mobile_url' => $val['mobile_url'],
								 'key_price' => $val['key_price'],
								 'key_match' => $val['key_match'],
								 'key_url' => $this->common->baidu_cpc_url($val['pc_url']),
								 'mb_key_url' => $this->common->baidu_cpc_url($val['mobile_url']));
					$this->db->insert($this->common->table('bd_keyword'), $arr);
				}
				
				foreach($keyword_del as $val)
				{
					$this->db->delete($this->common->table('bd_keyword'), array('key_id' => $val['key_id']));
				}
			}
			$this->db->update($this->common->table("bd_group"), array('keyword_count' => sizeof($keyword_arr_arr)), array('group_id' => $group_id, 'site_id' => $site_id));
			$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE group_id = $group_id AND site_id = $site_id ORDER BY key_id ASC");
		}
		$keyword_list = json_encode($keyword_list);
		echo $keyword_list;
	}
	
	public function cpc_keyword_update_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$key_id = isset($_REQUEST['key_id'])? $_REQUEST['key_id']:0;
		$key_url = isset($_REQUEST['key_url'])? trim($_REQUEST['key_url']):'';
		$pc_url = isset($_REQUEST['pc_url'])? trim($_REQUEST['pc_url']):'';
		$mb_key_url = isset($_REQUEST['mb_key_url'])? trim($_REQUEST['mb_key_url']):'';
		$mobile_url = isset($_REQUEST['mobile_url'])? trim($_REQUEST['mobile_url']):'';
		$url_tag = isset($_REQUEST['url_tag'])? trim($_REQUEST['url_tag']):'';
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		$url_add = isset($_REQUEST['url_add'])? $_REQUEST['url_add']:0;
		
		if(empty($site_id) || empty($key_id) || empty($key_url) || empty($mb_key_url) || empty($url_tag))
		{
			echo 'empty';
			exit();
		}

		$key_info = $this->common->getRow("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE key_id = $key_id AND site_id = $site_id");
		$site_info = $this->model->site_bd_info($site_id);
		
		$group_name = $this->common->getOne("SELECT group_name FROM " . $this->common->table('bd_group') . " WHERE group_id = " . floatval($key_info['group_id']));
		$plan_name = $this->common->getOne("SELECT plan_name FROM " . $this->common->table('bd_plan') . " WHERE plan_id = " . floatval($key_info['plan_id']));
		// 把变量转成具体的值
		$tag_arr = array('{$plan_id}', '{$group_id}', '{$key_id}', '{$plan_name}', '{$group_name}', '{$key_name}');
		$val_arr = array($key_info['plan_id'], $key_info['group_id'], $key_info['key_id'], urlencode($plan_name), urlencode($group_name), urlencode($key_info['key_keyword']));
		$url_tag_re = str_replace($tag_arr, $val_arr, $url_tag);
		$is_pc_first_tag = !strpos($pc_url, "?");
		$is_key_first_tag = !strpos($key_url, "?");
		$keywordtypes[0]['keywordId'] = floatval($key_id);
		if($type == 0)
		{
			if($url_add == 1)
			{
				$pc_url = $key_url . "&" . $url_tag_re;
				if($is_key_first_tag)
				{
					$pc_url = $key_url . "?" . $url_tag_re;
				}
				
				$mobile_url = $mb_key_url . "&" . $url_tag_re;
				if($is_key_first_tag)
				{
					$mobile_url = $mb_key_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['pcDestinationUrl'] = $pc_url;
				$keywordtypes[0]['mobileDestinationUrl'] = $mobile_url;
			}
			else
			{
				$pc_url = $pc_url . "&" . $url_tag_re;
				if($is_pc_first_tag)
				{
					$pc_url = $pc_url . "?" . $url_tag_re;
				}
				
				$mobile_url = $mobile_url . "&" . $url_tag_re;
				if($is_pc_first_tag)
				{
					$mobile_url = $mobile_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['pcDestinationUrl'] = $pc_url;
				$keywordtypes[0]['mobileDestinationUrl'] = $mobile_url;
			}
			
			$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($key_id)));
		}
		elseif($type == 1)
		{
			if($url_add == 1)
			{
				$pc_url = $key_url . "&" . $url_tag_re;
				if($is_key_first_tag)
				{
					$pc_url = $key_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['pcDestinationUrl'] = $pc_url;
			}
			else
			{
				$pc_url = $pc_url . "&" . $url_tag_re;
				if($is_pc_first_tag)
				{
					$pc_url = $pc_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['pcDestinationUrl'] = $pc_url;
			}
			$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($key_id)));
		}
		elseif($type == 2)
		{
			if($url_add == 1)
			{
				$mobile_url = $mb_key_url . "&" . $url_tag_re;
				if($is_key_first_tag)
				{
					$mobile_url = $mb_key_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['mobileDestinationUrl'] = $mobile_url;
			}
			else
			{
				$mobile_url = $mobile_url . "&" . $url_tag_re;
				if($is_pc_first_tag)
				{
					$mobile_url = $mobile_url . "?" . $url_tag_re;
				}
				$keywordtypes[0]['mobileDestinationUrl'] = $mobile_url;
			}
			$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($key_id)));
		}
		$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('updateKeywordRequest' => array('keywordTypes' => array(array('keywordId' => floatval($key_id), 'mobileDestinationUrl' => $mobile_url))));
		$output_response = $this->baidu->soapCall('updateKeyword', $arguments, $output_headers);
		if(!empty($output_response))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	
	public function cpc_group_url_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$group_id = isset($_REQUEST['group_id'])? $_REQUEST['group_id']:0;
		$url_add = isset($_REQUEST['url_add'])? intval($_REQUEST['url_add']):'';
		$url_tag = isset($_REQUEST['url_tag'])? trim($_REQUEST['url_tag']):'';
		$key_url = isset($_REQUEST['key_url'])? trim($_REQUEST['key_url']):'';
		$mb_key_url = isset($_REQUEST['mb_key_url'])? trim($_REQUEST['mb_key_url']):'';
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		if(empty($site_id) || empty($group_id) || empty($url_tag))
		{
			echo 0;
			exit();
		}

		$group_info = $this->common->getRow("SELECT * FROM " . $this->common->table('bd_group') . " WHERE group_id = $group_id AND site_id = $site_id");
		$group_name = $group_info['group_name'];
		if($group_info['keyword_count'] == -1)
		{
			echo 1;
		}
		else
		{
			$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE group_id = $group_id AND site_id = $site_id ORDER BY key_id ASC");
			if(empty($keyword_list))
			{
				echo 2;
				exit();
			}
			
			$site_info = $this->model->site_bd_info($site_id);
			$keywordtypes = array();
			foreach($keyword_list as $key => $val)
			{
				$keywordtypes[$key]['keywordId']        = floatval($val['key_id']);
				$key_name = $this->common->getOne("SELECT key_keyword FROM " . $this->common->table('bd_keyword') . " WHERE key_id = " . floatval($val['key_id']));
				$plan_name = $this->common->getOne("SELECT plan_name FROM " . $this->common->table('bd_plan') . " WHERE plan_id = " . floatval($val['plan_id']));
				// 把变量转成具体的值
				$tag_arr = array('{$plan_id}', '{$group_id}', '{$key_id}', '{$plan_name}', '{$group_name}', '{$key_name}');
				$val_arr = array($val['plan_id'], $val['group_id'], $val['key_id'], urlencode($plan_name), urlencode($group_name), urlencode($key_name));
				$url_tag_re = str_replace($tag_arr, $val_arr, $url_tag);
				
				$pc_url = $val['pc_url'];
				$mobile_url = $val['mobile_url'];
				$key_url = $key_url;
				$mb_key_url = $mb_key_url;
				$is_pc_first_tag = !strpos($pc_url, "?");
				$is_key_first_tag = !strpos($key_url, "?");
				if($url_add == 1)
				{
					if($type == 0) // 所有URL都要修改
					{
						if(empty($key_url) || empty($mb_key_url))
						{
							echo 10;
							exit();
						}
						$pc_url = $key_url . "&" . $url_tag_re;
						if($is_key_first_tag)
						{
							$pc_url = $key_url . "?" . $url_tag_re;
						}
						
						$mobile_url = $mb_key_url . "&" . $url_tag_re;
						if($is_key_first_tag)
						{
							$mobile_url = $mb_key_url . "?" . $url_tag_re;
						}
						$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
						$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
						
						$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
					}
					elseif($type == 1) // 只修改PC端
					{
						if(empty($key_url))
						{
							echo 10;
							exit();
						}
						$pc_url = $key_url . "&" . $url_tag_re;
						if($is_key_first_tag)
						{
							$pc_url = $key_url . "?" . $url_tag_re;
						}
						$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
						$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
					}
					else // 只修改移动端
					{
						if(empty($mb_key_url))
						{
							echo 10;
							exit();
						}
						$mobile_url = $mb_key_url . "&" . $url_tag_re;
						if($is_key_first_tag)
						{
							$mobile_url = $mb_key_url . "?" . $url_tag_re;
						}
						$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
						$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
					}
				}
				else
				{
					if($type == 0)
					{
						if(empty($pc_url) || empty($mobile_url))
						{
							echo 10;
							exit();
						}
						$pc_url = $pc_url . "&" . $url_tag_re;
						if($is_pc_first_tag)
						{
							$pc_url = $pc_url . "?" . $url_tag_re;
						}
						
						$mobile_url = $mobile_url . "&" . $url_tag_re;
						if($is_pc_first_tag)
						{
							$mobile_url = $mobile_url . "?" . $url_tag_re;
						}
						$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
						$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
						$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
					}
					elseif($type == 1)
					{
						if(empty($pc_url))
						{
							echo 10;
							exit();
						}
						$pc_url = $pc_url . "&" . $url_tag_re;
						if($is_pc_first_tag)
						{
							$pc_url = $pc_url . "?" . $url_tag_re;
						}

						$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
						$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
					}
					else
					{
						if(empty($mobile_url))
						{
							echo 10;
							exit();
						}
						$mobile_url = $mobile_url . "&" . $url_tag_re;
						if($is_pc_first_tag)
						{
							$mobile_url = $mobile_url . "?" . $url_tag_re;
						}
						$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
						$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
					}
				}
			}

			$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('updateKeywordRequest' => array('keywordTypes' => $keywordtypes));
			$output_response = $this->baidu->soapCall('updateKeyword', $arguments, $output_headers);
			if(!empty($output_response))
			{
				echo 3;
			}
			else
			{
				echo 4;
			}
		}
	}
	public function cpc_all_url_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$plan_id = isset($_REQUEST['plan_id'])? $_REQUEST['plan_id']:0;
		$url_add = isset($_REQUEST['url_add'])? intval($_REQUEST['url_add']):'';
		$url_tag = isset($_REQUEST['url_tag'])? trim($_REQUEST['url_tag']):'';
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		if(empty($site_id) || empty($url_tag))
		{
			echo "不能为空";
			exit();
		}
		$site_info = $this->model->site_bd_info($site_id);
		
		$plan_info = $this->common->getRow("SELECT * FROM " . $this->common->table('bd_plan') . " WHERE plan_id >= $plan_id AND site_id = $site_id ORDER BY plan_id ASC LIMIT 0, 1");
		$plan_name = $plan_info['plan_name'];
		if($plan_info['group_count'] == -1)
		{
			echo "计划下有单位为更新最新数据！";
			exit();
		}
		
		$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE plan_id = " . $plan_info['plan_id'] . " AND site_id = $site_id ORDER BY key_id ASC");
		if(empty($keyword_list))
		{
			echo 3;
			exit();
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$keywordtypes = array();
		foreach($keyword_list as $key => $val)
		{
			$keywordtypes[$key]['keywordId']        = floatval($val['key_id']);
			$key_info = $this->common->getRow("SELECT key_keyword, group_id FROM " . $this->common->table('bd_keyword') . " WHERE key_id = " . floatval($val['key_id']));
			$group_id = $key_info['group_id'];
			$key_name = $key_info['key_keyword'];
			$group_info = $this->common->getRow("SELECT group_name FROM " . $this->common->table('bd_group') . " WHERE group_id = $group_id");
			$group_name = $group_info['group_name'];
			// 把变量转成具体的值
			$tag_arr = array('{$plan_id}', '{$group_id}', '{$key_id}', '{$plan_name}', '{$group_name}', '{$key_name}');
			$val_arr = array($val['plan_id'], $val['group_id'], $val['key_id'], urlencode($plan_name), urlencode($group_name), urlencode($key_name));
			$url_tag_re = str_replace($tag_arr, $val_arr, $url_tag);
			
			$pc_url = $val['pc_url'];
			$mobile_url = $val['mobile_url'];
			$key_url = $val['key_url'];
			$mb_key_url = $val['mb_key_url'];
			$is_pc_first_tag = !strpos($pc_url, "?");
			$is_key_first_tag = !strpos($key_url, "?");
			if($url_add == 1)
			{
				if($type == 0) // 所有URL都要修改
				{
					if(empty($key_url) || empty($mb_key_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' 原始URL为空！";
						exit();
					}
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
				elseif($type == 1) // 只修改PC端
				{
					if(empty($key_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' 原始URL为空！";
						exit();
					}
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
				}
				else // 只修改移动端
				{
					if(empty($mb_key_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' 原始URL为空！";
						exit();
					}
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
			}
			else
			{
				if($type == 0)
				{
					if(empty($pc_url) || empty($mobile_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' URL为空！";
						exit();
					}
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
				elseif($type == 1)
				{
					if(empty($pc_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' URL为空！";
						exit();
					}
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}

					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
				}
				else
				{
					if(empty($mobile_url))
					{
						echo "计划：'" . $plan_name . "'，单元：'" . $group_name . "'，关键词：'" . $key_name . "' URL为空！";
						exit();
					}
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
			}
		}

		$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('updateKeywordRequest' => array('keywordTypes' => $keywordtypes));
		$output_response = $this->baidu->soapCall('updateKeyword', $arguments, $output_headers);
		$output_response = 1;
		if(!empty($output_response))
		{
			$plan_id = $this->common->getOne("SELECT plan_id FROM " . $this->common->table('bd_plan') . " WHERE site_id = $site_id AND plan_id > " . $plan_info['plan_id'] . " ORDER BY plan_id ASC LIMIT 0, 1");
			if(empty($plan_id))
			{
				// 记录最后更新时间
				$this->db->query("UPDATE " . $this->common->table('site_bd') . " SET bd_url_up = " . time() . " WHERE site_id = $site_id");
				$links[0] = array('href' => '?c=site&m=cpc_plan&site_id=' . $site_id, 'text' => '返回账号计划页');
		    	$this->common->msg($this->lang->line('success'), 0, $links);
			}
			else
			{
				$links[0] = array('href' => '?c=site&m=cpc_all_url_ajax&plan_id=' . $plan_id . '&site_id=' . $site_id . "&url_add=" . $url_add . "&url_tag=" . urlencode($url_tag) . "&type=" . $type, 'text' => '更新下计划的关键词，计划ID为：' . $plan_id);
		    	$this->common->msg($this->lang->line('success'), 0, $links, true, true, 1);
			}
		}
		else
		{
			echo "更新失败！";
			exit();
		}
		
	}
	public function cpc_plan_url_ajax()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$plan_id = isset($_REQUEST['plan_id'])? $_REQUEST['plan_id']:0;
		$url_add = isset($_REQUEST['url_add'])? intval($_REQUEST['url_add']):'';
		$url_tag = isset($_REQUEST['url_tag'])? trim($_REQUEST['url_tag']):'';
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		if(empty($site_id) || empty($plan_id) || empty($url_tag))
		{
			echo 0;
			exit();
		}
		
		$plan_info = $this->common->getRow("SELECT * FROM " . $this->common->table('bd_plan') . " WHERE plan_id = $plan_id");
		$plan_name = $plan_info['plan_name'];
		if($plan_info['group_count'] == -1)
		{
			echo 1;
			exit();
		}
		
		$keyword_list = $this->common->getAll("SELECT * FROM " . $this->common->table('bd_keyword') . " WHERE plan_id = $plan_id AND site_id = $site_id ORDER BY key_id ASC");
		if(empty($keyword_list))
		{
			echo 3;
			exit();
		}
		
		$site_info = $this->model->site_bd_info($site_id);
		$keywordtypes = array();
		foreach($keyword_list as $key => $val)
		{
			$keywordtypes[$key]['keywordId']        = floatval($val['key_id']);
			$key_info = $this->common->getRow("SELECT key_keyword, group_id FROM " . $this->common->table('bd_keyword') . " WHERE key_id = " . floatval($val['key_id']));
			$group_id = $key_info['group_id'];
			$key_name = $key_info['key_keyword'];
			$group_info = $this->common->getRow("SELECT group_name FROM " . $this->common->table('bd_group') . " WHERE group_id = $group_id");
			$group_name = $group_info['group_name'];
			// 把变量转成具体的值
			$tag_arr = array('{$plan_id}', '{$group_id}', '{$key_id}', '{$plan_name}', '{$group_name}', '{$key_name}');
			$val_arr = array($val['plan_id'], $val['group_id'], $val['key_id'], urlencode($plan_name), urlencode($group_name), urlencode($key_name));
			$url_tag_re = str_replace($tag_arr, $val_arr, $url_tag);
			
			$pc_url = $val['pc_url'];
			$mobile_url = $val['mobile_url'];
			$key_url = $val['key_url'];
			$mb_key_url = $val['mb_key_url'];
			$is_pc_first_tag = !strpos($pc_url, "?");
			$is_key_first_tag = !strpos($key_url, "?");
			if($url_add == 1)
			{
				if($type == 0) // 所有URL都要修改
				{
					if(empty($key_url) || empty($mb_key_url))
					{
						echo 10;
						exit();
					}
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
				elseif($type == 1) // 只修改PC端
				{
					if(empty($key_url))
					{
						echo 10;
						exit();
					}
					$pc_url = $key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$pc_url = $key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
				}
				else // 只修改移动端
				{
					if(empty($mb_key_url))
					{
						echo 10;
						exit();
					}
					$mobile_url = $mb_key_url . "&" . $url_tag_re;
					if($is_key_first_tag)
					{
						$mobile_url = $mb_key_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
			}
			else
			{
				if($type == 0)
				{
					if(empty($pc_url) || empty($mobile_url))
					{
						echo 10;
						exit();
					}
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}
					
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'mobile_url' => $mobile_url, 'key_url' => $key_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
				elseif($type == 1)
				{
					if(empty($pc_url))
					{
						echo 10;
						exit();
					}
					$pc_url = $pc_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$pc_url = $pc_url . "?" . $url_tag_re;
					}

					$keywordtypes[$key]['pcDestinationUrl'] = $pc_url;
					$this->db->update($this->common->table('bd_keyword'), array('pc_url' => $pc_url, 'key_url' => $key_url), array('key_id' => floatval($val['key_id'])));
				}
				else
				{
					if(empty($mobile_url))
					{
						echo 10;
						exit();
					}
					$mobile_url = $mobile_url . "&" . $url_tag_re;
					if($is_pc_first_tag)
					{
						$mobile_url = $mobile_url . "?" . $url_tag_re;
					}
					$keywordtypes[$key]['mobileDestinationUrl'] = $mobile_url;
					$this->db->update($this->common->table('bd_keyword'), array('mobile_url' => $mobile_url, 'mb_key_url' => $mb_key_url), array('key_id' => floatval($val['key_id'])));
				}
			}
		}

		$this->load->library('baidu', array('serviceName' => 'KeywordService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('updateKeywordRequest' => array('keywordTypes' => $keywordtypes));
		$output_response = $this->baidu->soapCall('updateKeyword', $arguments, $output_headers);
		if(!empty($output_response))
		{
			echo 4;
		}
		else
		{
			echo 5;
		}
	}
	
	public function creative_url()
	{
		ini_set("soap.wsdl_cache_enabled", "0"); 
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$group_id = isset($_REQUEST['group_id'])? $_REQUEST['group_id']:0;
		if(empty($site_id) || empty($group_id))
		{
			echo 0;
			exit();
		}
		$site_info = $this->model->site_bd_info($site_id);
		$this->load->library('baidu', array('serviceName' => 'CreativeService', 'username' => $site_info['site_bd_username'], 'password' => $site_info['site_bd_password'], 'token' => $site_info['site_bd_token']));
		$output_headers = array();
		$arguments = array('getCreativeByAdgroupIdRequest' => array('adgroupIds' => array($group_id)));
		$output_response = $this->baidu->soapCall('getCreativeByAdgroupId', $arguments, $output_headers);
		$url_arr = $output_response->groupCreatives->creativeTypes;
		$url_list = array();
		
		if(empty($url_arr))
		{
			echo 'empty';
			exit();
		}
		foreach($url_arr as $key=>$val)
		{
			$url_list[$key]['pc'] = $val->pcDestinationUrl;
			$url_list[$key]['mobile'] = $val->mobileDestinationUrl;
		}
		
		$url_list = json_encode($url_list);
		echo $url_list;
	}
	
	public function cpc_report_timing()
	{
		$data = array();
		$data = $this->common->config('cpc_report_timing');
		$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('site') . " WHERE site_bd = 1 ORDER BY site_id ASC");
		$site_list = array();
		foreach($arr as $val)
		{
			$site_list[$val['site_id']]['site_id'] = $val['site_id'];
			$site_list[$val['site_id']]['site_name'] = $val['site_name'];
			$site_list[$val['site_id']]['site_domain'] = $val['site_domain'];
			$site_list[$val['site_id']]['site_bd'] = $val['site_bd'];
			$site_list[$val['site_id']]['site_bd_username'] = $val['site_bd_username'];
			$site_list[$val['site_id']]['site_bd_password'] = $val['site_bd_password'];
			$site_list[$val['site_id']]['site_bd_token'] = $val['site_bd_token'];
			
			$cpc_timing_info = read_static_cache("cpc/" . $val['site_id']);
			if(empty($cpc_timing_info))
			{
				$str_data = $site_list[$val['site_id']];
				$str_data['last_action_time'] = '0';
				$str_data['day_keyword_report'] = '0';
				$str_data['day_keyword_pc_report'] = '0';
				$str_data['day_keyword_mobile_report'] = '0';
				$str_data['day_account_report'] = '0';
				write_static_cache("cpc/" . $val['site_id'], $str_data);
				$last_action_time = 0;
				$day_keyword_report = 0;
				$day_account_report = 0;
			}
			else
			{
				$last_action_time = $cpc_timing_info['last_action_time'];
				$day_keyword_report = $cpc_timing_info['day_keyword_report'];
				$day_keyword_pc_report = $cpc_timing_info['day_keyword_pc_report'];
				$day_keyword_mobile_report = $cpc_timing_info['day_keyword_mobile_report'];
				$day_account_report = $cpc_timing_info['day_account_report'];
			}
			$site_list[$val['site_id']]['last_action_time'] = $last_action_time;
			$site_list[$val['site_id']]['day_keyword_report'] = $day_keyword_report;
			$site_list[$val['site_id']]['day_keyword_pc_report'] = $day_keyword_pc_report;
			$site_list[$val['site_id']]['day_keyword_mobile_report'] = $day_keyword_mobile_report;
			$site_list[$val['site_id']]['day_account_report'] = $day_account_report;
			
			/* 综合性 昨日 关键词报告*/
			if(($day_keyword_report == 0) || ($day_keyword_report != intval(date("d", time() - (3600 * 24)))))
			{
				$site_list[$val['site_id']]['dr'] = 0;
			}
			else
			{
				$site_list[$val['site_id']]['dr'] = 1;
			}

			/* PC端 昨日 关键词报告*/
			if(($day_keyword_pc_report == 0) || ($day_keyword_pc_report != intval(date("d", time() - (3600 * 24)))))
			{
				$site_list[$val['site_id']]['dr_pc'] = 0;
			}
			else
			{
				$site_list[$val['site_id']]['dr_pc'] = 1;
			}
			
			/* 移动端 昨日 关键词报告*/
			if(($day_keyword_mobile_report == 0) || ($day_keyword_mobile_report != intval(date("d", time() - (3600 * 24)))))
			{
				$site_list[$val['site_id']]['dr_mobile'] = 0;
			}
			else
			{
				$site_list[$val['site_id']]['dr_mobile'] = 1;
			}
			
			if(($day_account_report == 0) || ($day_account_report != intval(date("d", time() - (3600 * 24)))))
			{
				$site_list[$val['site_id']]['da'] = 0;
			}
			else
			{
				$site_list[$val['site_id']]['da'] = 1;
			}
		}
		$data['site_list'] = $site_list; 
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('cpc_report_timing', $data);
	}
	
	public function report_cache_ajax()
	{
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:'';
		if(empty($site_id) || empty($type))
		{
			echo json_encode(array('type' => 0, 'report_id' => ''));
			exit();
		}
		
		if($type == 'hour_keyword')
		{
			$startDate = date("Y-m-d", time()) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time()) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 0, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'hour_keyword_pc')
		{
			$startDate = date("Y-m-d", time()) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time()) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 1, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'hour_keyword_mobile')
		{
			$startDate = date("Y-m-d", time()) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time()) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 2, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'day_keyword')
		{
			$startDate = date("Y-m-d", time() - (3600 * 24)) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time() - (3600 * 24)) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 0, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'day_keyword_pc')
		{
			$startDate = date("Y-m-d", time() - (3600 * 24)) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time() - (3600 * 24)) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 1, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'day_keyword_mobile')
		{
			$startDate = date("Y-m-d", time() - (3600 * 24)) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time() - (3600 * 24)) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 2, 'levelOfDetails' => 11, 'reportType' => 14, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response->reportId))
			{
				$report_id = $output_response->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
		elseif($type == 'day_account')
		{
			$startDate = date("Y-m-d", time() - (3600 * 24)) . 'T00:00:00.000';
			$endDate = date("Y-m-d", time() - (3600 * 24)) . 'T23:59:59.000';
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 1, 'levelOfDetails' => 2, 'reportType' => 2, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response1 = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			$output_headers = array();
			$arguments = array('getProfessionalReportIdRequest' => array('reportRequestType' => array ('performanceData' => array('cost', 'cpc', 'click', 'impression', 'ctr', 'cpm'), 'device' => 2, 'levelOfDetails' => 2, 'reportType' => 2, 'startDate' => $startDate, 'endDate' => $endDate, 'format' => 2)));
			$output_response2 = $this->baidu->soapCall('getProfessionalReportId', $arguments, $output_headers);
			if(isset($output_response1->reportId))
			{
				$report_id = $output_response1->reportId . '_' . $output_response2->reportId;
				echo json_encode(array('type' => 1, 'report_id' => $report_id));
			}
			else
			{
				echo json_encode(array('type' => 2, 'report_id' => ''));
			}
		}
	}
	
	public function report_get_ajax()
	{
		$this->common->config('cpc_account');
		$site_id = isset($_REQUEST['site_id'])? $_REQUEST['site_id']:0;
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		$report_id = isset($_REQUEST['report_id'])? $_REQUEST['report_id']:0;
		if(empty($site_id) || empty($report_id) || empty($type))
		{
			echo 0;
			exit();
		}

		// 查询报告文件是否可以下载
		$cpc_timing_info = read_static_cache("cpc/" . $site_id);
		$this->load->library('baidu', array('serviceName' => 'ReportService', 'username' => $cpc_timing_info['site_bd_username'], 'password' => $cpc_timing_info['site_bd_password'], 'token' => $cpc_timing_info['site_bd_token']));
		$output_headers = array();
		
		if(($type == 'hour_keyword') || ($type == 'hour_keyword_pc') || ($type == 'hour_keyword_mobile'))
		{
			$arguments = array('getReportStateRequest' => array('reportId' => $report_id));
			$output_response = $this->baidu->soapCall('getReportState', $arguments, $output_headers);
			
			if(!isset($output_response->isGenerated))
			{
				echo 1;
				exit();
			}
			
			$state = $output_response->isGenerated;
			if($state == 1 || $state == 2)
			{
				echo 2;
				exit();
			}
			
			$arguments = array('getReportFileUrlRequest' => array('reportId' => $report_id));
			$output_response = $this->baidu->soapCall('getReportFileUrl', $arguments, $output_headers);
			$file = $output_response->reportFilePath;
			
			$report_content = file_get_contents($file);
			$report_content = explode("\r\n", $report_content);
			
			if($type == 'hour_keyword')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp');
				$db_table = $this->common->table('bd_cpc_hour_' . $site_id);
			}
			elseif($type == 'hour_keyword_pc')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp_pc');
				$db_table = $this->common->table('bd_cpc_hour_pc_' . $site_id);
			}
			elseif($type == 'hour_keyword_mobile')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp_mobile');
				$db_table = $this->common->table('bd_cpc_hour_mobile_' . $site_id);
			}
			
			$hour_id = $this->common->getOne("SELECT hour_id FROM " . $tmp_table . " WHERE site_id = $site_id AND hour_year = " . intval(date("Y")) . " AND hour_month = " . intval(date("m")) . " AND hour_day = " . intval(date("d")) . " AND hour_hour = " . (intval(date("H")) - 2) . " ORDER BY hour_id DESC LIMIT 1");

			$i = 1;
			$j = 1;
			$sql = "INSERT INTO " . $db_table . " (`bd_id`, `hour_year`, `hour_month`, `hour_day`, `hour_hour`, `hour_time`, `plan_id`, `group_id`, `key_id`, `word_id`, `key_word`, `hour_shows`, `hour_clicks`, `hour_cost`, `hour_click_lv`, `hour_price`, `qian_show_cost`) VALUES";
			$sql_tmp = "INSERT INTO " . $tmp_table . " (`site_id`, `bd_id`, `hour_year`, `hour_month`, `hour_day`, `hour_hour`, `plan_id`, `group_id`, `key_id`, `word_id`, `key_word`, `hour_shows`, `hour_clicks`, `hour_cost`, `hour_click_lv`, `hour_price`, `qian_show_cost`) VALUES";
			foreach($report_content as $key => $val)
			{
				if($key > 0)
				{
					if(!empty($val))
					{
						$report_data = explode("	", $val);
						$bd_id = $report_data[1];
						$hour_year = intval(date("Y"));
						$hour_month = intval(date("m"));
						$hour_day = intval(date("d"));
						$hour_hour = intval(date("H")) - 1;
						$hour_time = strtotime($hour_year . '-' . $hour_month . '-' . $hour_day . ' ' . $hour_hour . ':00:00');
						$plan_id = $report_data[3];
						$group_id = $report_data[5];
						$key_id = $report_data[7];
						$word_id = $report_data[8];
						$key_word = mb_convert_encoding($report_data[9],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$hour_shows = $report_data[10];
						$hour_clicks = $report_data[11];
						$hour_cost = $report_data[12];
						$hour_click_lv = $report_data[13];
						$hour_price = $report_data[14];
						$qian_show_cost = $report_data[15];

						if($j > 1)
						{
							$sql_tmp .= ",\r\n";
						}
						
						$sql_tmp .= "('" . $site_id . "', '" . $bd_id . "', '" . $hour_year . "', '" . $hour_month . "', '" . $hour_day . "', '" . $hour_hour . "', '" . $plan_id . "', '" . $group_id . "', '" . $key_id . "', '" . $word_id . "', '" . mysql_escape_string($key_word) . "', '" . $hour_shows . "', '" . $hour_clicks . "', '" . $hour_cost . "', '" . $hour_click_lv . "', '" . $hour_price . "', '" . $qian_show_cost . "')";
	
						if($hour_id > 0)
						{
							$log_last = $this->common->getRow("SELECT * FROM " . $tmp_table . " WHERE bd_id = $bd_id AND key_id = $key_id AND word_id = $word_id AND hour_year = $hour_year AND hour_month = $hour_month AND hour_day = $hour_day AND hour_hour = " . ($hour_hour - 1));
							if(!empty($log_last))
							{
								$hour_shows = abs($hour_shows - $log_last['hour_shows']);
								$hour_clicks = abs($hour_clicks - $log_last['hour_clicks']);
								$hour_cost = abs($hour_cost - $log_last['hour_cost']);
								
								$hour_click_lv = ($hour_shows > 0)? number_format((($hour_clicks / $hour_shows) * 100), 2, ".", ""):0;
								$hour_price = ($hour_clicks > 0)? number_format(($hour_cost / $hour_clicks), 2, ".", ""):0;
								$qian_show_cost = ($hour_shows > 0)? ($hour_cost / $hour_shows) * 1000:0;
							}
						}
						
						if($hour_shows > 0)
						{
							if($i > 1)
							{
								$sql .= ",\r\n";
							}
							$sql .= "('" . $bd_id . "', '" . $hour_year . "', '" . $hour_month . "', '" . $hour_day . "', '" . $hour_hour . "', '" . $hour_time . "', '" . $plan_id . "', '" . $group_id . "', '" . $key_id . "', '" . $word_id . "', '" . mysql_escape_string($key_word) . "', '" . $hour_shows . "', '" . $hour_clicks . "', '" . $hour_cost . "', '" . $hour_click_lv . "', '" . $hour_price . "', '" . $qian_show_cost . "')";
							$i ++;
						}
						$j ++;
					}
				}
			}
			
			//echo $sql;
			//echo $sql_tmp;
			$this->db->delete($tmp_table, array('site_id'=> $site_id, 'hour_year' => intval(date("Y")), 'hour_month' => intval(date("m")), 'hour_day' => intval(date("d")), 'hour_hour' => (intval(date("H")) - 2)));
			$this->db->query($sql);
			$this->db->query($sql_tmp);
		}
		elseif(($type == 'day_keyword') || ($type == 'day_keyword_pc') || ($type == 'day_keyword_mobile'))
		{
			$arguments = array('getReportStateRequest' => array('reportId' => $report_id));
			$output_response = $this->baidu->soapCall('getReportState', $arguments, $output_headers);
			
			if(!isset($output_response->isGenerated))
			{
				echo 1;
				exit();
			}
			
			$state = $output_response->isGenerated;
			if($state == 1 || $state == 2)
			{
				echo 2;
				exit();
			}
			
			$arguments = array('getReportFileUrlRequest' => array('reportId' => $report_id));
			$output_response = $this->baidu->soapCall('getReportFileUrl', $arguments, $output_headers);
			$file = $output_response->reportFilePath;
			
			$report_content = file_get_contents($file);
			$report_content = explode("\r\n", $report_content);
			
			if($type == 'day_keyword')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp');
				$db_table = $this->common->table('bd_cpc_day_' . $site_id);
			}
			elseif($type == 'day_keyword_pc')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp_pc');
				$db_table = $this->common->table('bd_cpc_day_pc_' . $site_id);
			}
			elseif($type == 'day_keyword_mobile')
			{
				$tmp_table = $this->common->table('bd_cpc_tmp_mobile');
				$db_table = $this->common->table('bd_cpc_day_mobile_' . $site_id);
			}
			
			// 昨日关键词报告
			$sql = "INSERT INTO " . $db_table . " (`bd_id`, `day_year`, `day_month`, `day_day`, `day_time`, `plan_id`, `group_id`, `key_id`, `word_id`, `key_word`, `day_shows`, `day_clicks`, `day_cost`, `day_click_lv`, `day_price`, `qian_show_cost`) VALUES";
			$i = 1;
			foreach($report_content as $key => $val)
			{
				if($key > 0)
				{
					if(!empty($val))
					{
						$report_data = explode("	", $val);
						$bd_id = $report_data[1];
						$day_year = intval(date("Y", time() - (3600 * 24)));
						$day_month = intval(date("m", time() - (3600 * 24)));
						$day_day = intval(date("d", time() - (3600 * 24)));
						$day_time = time() - 86400;
						$plan_id = $report_data[3];
						$group_id = $report_data[5];
						$key_id = $report_data[7];
						$word_id = $report_data[8];
						$key_word = mb_convert_encoding($report_data[9],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
						$day_shows = $report_data[10];
						$day_clicks = $report_data[11];
						$day_cost = $report_data[12];
						$day_click_lv = $report_data[13];
						$day_price = $report_data[14];
						$qian_show_cost = $report_data[15];
						
						if($i > 1)
						{
							$sql .= ',';
						}
						
						$sql .= "('" . $bd_id . "', '" . $day_year . "', '" . $day_month . "', '" . $day_day . "', '" . $day_time . "', '" . $plan_id . "', '" . $group_id . "', '" . $key_id . "', '" . $word_id . "', '" . mysql_escape_string($key_word) . "', '" . $day_shows . "', '" . $day_clicks . "', '" . $day_cost . "', '" . $day_click_lv . "', '" . $day_price . "', '" . $qian_show_cost . "')";
						$i ++;
					}
				}	
			}
			$this->db->query($sql);
			
			$this->db->delete($tmp_table, array('hour_year' => intval(date("Y")), 'hour_month' => intval(date("m")), 'hour_day' => intval(date("d", time() - 86400))));
			
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$str_data = $cpc_timing_info;
			$str_data['last_action_time'] = time();
			
			if($type == 'day_keyword')
			{
				$str_data['day_keyword_report'] = intval(date("d", time() - (3600 * 24)));
			}
			elseif($type == 'day_keyword_pc')
			{
				$str_data['day_keyword_pc_report'] = intval(date("d", time() - (3600 * 24)));
			}
			elseif($type == 'day_keyword_mobile')
			{
				$str_data['day_keyword_mobile_report'] = intval(date("d", time() - (3600 * 24)));
			}

			write_static_cache("cpc/" . $site_id, $str_data);
		}
		elseif($type == 'day_account')
		{
			$report_id = explode("_", $report_id);
			$pc_id = $report_id[0];
			$mobile_id = $report_id[1];
			
			// PC端
			$arguments = array('getReportStateRequest' => array('reportId' => $pc_id));
			$output_response = $this->baidu->soapCall('getReportState', $arguments, $output_headers);
			
			if(!isset($output_response->isGenerated))
			{
				echo 1;
				exit();
			}
			
			$state = $output_response->isGenerated;
			if($state == 1 || $state == 2)
			{
				echo 2;
				exit();
			}
			
			$arguments = array('getReportFileUrlRequest' => array('reportId' => $pc_id));
			$output_response = $this->baidu->soapCall('getReportFileUrl', $arguments, $output_headers);
			$file = $output_response->reportFilePath;
			
			$pc_reort = file_get_contents($file);
			$pc_reort = explode("\r\n", $pc_reort);
			$pc_reort = explode("	", $pc_reort[1]);
			
			$output_headers = array();
			$arguments = array('getReportFileUrlRequest' => array('reportId' => $mobile_id));
			$output_response = $this->baidu->soapCall('getReportFileUrl', $arguments, $output_headers);
			$file = $output_response->reportFilePath;
			
			$mobile_reort = file_get_contents($file);
			$mobile_reort = explode("\r\n", $mobile_reort);
			$mobile_reort = explode("	", $mobile_reort[1]);
			$time = time() - 86400;
			$account = array();
			$account = array('site_id' => $site_id,
							 'bd_year' => intval(date("Y", $time)),
						     'bd_month' => intval(date("m", $time)),
						     'bd_day' => intval(date("d", $time)),
							 'bd_pc_cost' => $pc_reort[5],
							 'bd_day_cost' => ($pc_reort[5] + $mobile_reort[5]),
			                 'bd_day_show' => ($pc_reort[3] + $mobile_reort[3]),
							 'bd_day_click' => ($pc_reort[4] + $mobile_reort[4]),
							 'bd_pc_show' => $pc_reort[3],
							 'bd_pc_click' => $pc_reort[4],
							 'bd_pc_click_lv' => $pc_reort[6],
							 'bd_pc_price' => $pc_reort[7],
							 'bd_pc_qsp' => $pc_reort[8],
							 'bd_mobile_cost' => $mobile_reort[5],
							 'bd_mobile_show' => $mobile_reort[3],
							 'bd_mobile_click' => $mobile_reort[4],
							 'bd_mobile_click_lv' => $mobile_reort[6],
							 'bd_mobile_price' => $mobile_reort[7],
							 'bd_mobile_qsp' => $mobile_reort[8],
							 'bd_addtime' => strtotime(date("Y-m-d", $time)));
							 
			$account['bd_day_click_lv'] = ($account['bd_day_show'] > 0)? number_format((($account['bd_day_click'] / $account['bd_day_show']) * 100), 2, ".", ""):0;
			$account['bd_day_price'] = ($account['bd_day_click'] > 0)? number_format((($account['bd_day_cost'] / $account['bd_day_click']) * 100), 2, ".", ""):0;
			$account['bd_day_qsp'] = ($account['bd_day_show'] > 0)? ($account['bd_day_cost'] / $account['bd_day_show']) * 1000:0;
			
			$this->db->insert($this->common->table('bd_cpc_account'), $account);
			
			$cpc_timing_info = read_static_cache("cpc/" . $site_id);
			$str_data = $cpc_timing_info;
			$str_data['last_action_time'] = time();
			$str_data['day_account_report'] = intval(date("d", time() - (3600 * 24)));
			write_static_cache("cpc/" . $site_id, $str_data);
		}
		
		echo 10;
	}
	
	public function site_visits_timing()
	{
		$data = array();
		$data = $this->common->config('site_visits_timing');

		$analytics = read_static_cache("analytics");
		if(empty($analytics) || (date("Y-m-d" ,$analytics[0]['action_time']) !== date("Y-m-d")))
		{
			$google_analytics = $this->common->getAll("SELECT * FROM " . $this->common->table('google_analytics') . " WHERE is_pass = 1 ORDER BY ana_id ASC");
			foreach($google_analytics as $val)
			{
				$str_data[$val['ana_id']]['ana_id'] = $val['ana_id'];
				$str_data[$val['ana_id']]['google_id'] = $val['google_id'];
				$str_data[$val['ana_id']]['project_id'] = $val['project_id'];
				$str_data[$val['ana_id']]['view_site_first'] = '1';
				$str_data[$val['ana_id']]['view_visitor_first'] = '1';
				$str_data[$val['ana_id']]['view_load_first']  = '1';
				$str_data[$val['ana_id']]['view_path_first']  = '1';
				$str_data[$val['ana_id']]['view_from_first']  = '1';
				$str_data[$val['ana_id']]['view_baidu_first']  = '1';
				$str_data[$val['ana_id']]['view_qq_first']  = '1';
				$str_data[0]['action_time']  = time();
			}
			write_static_cache("analytics", $str_data);
			$analytics = $str_data;
		}

		$data['analytics'] = $analytics; 
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_visits_timing', $data);
	}
        public function get_site(){
            $sql=isset($_REQUEST['sql'])?$_REQUEST['sql']:"";
          
            
            $site_array=$this->common->getAll($sql);
            $site=json_encode($site_array);
            echo $site;
            $data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		
        }
	public function analytics_ajax()
	{
		$this->common->config('site_visits_timing');
		$type = isset($_REQUEST['type'])? $_REQUEST['type']:0;
		//$first = isset($_REQUEST['first'])? $_REQUEST['first']:0;
		$ana_id = isset($_REQUEST['ana_id'])? $_REQUEST['ana_id']:0;
		if(empty($ana_id) || empty($type))
		{
			echo 0;
			exit();
		}
		$info = file_get_contents('http://i.renaidata.com/g/data.txt');
		
		if($info == "1")
		{
			echo "login";
			exit();
		}
		
		$info = explode("\r\n", $info);
		if($type == 'visitor')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$city = substr($in[1], 1, -1);
				$system = substr($in[2], 1, -1);
				$browser = substr($in[3], 1, -1);
				$device = substr($in[4], 1, -1);
				$screenResolution = substr($in[5], 1, -1);
				$hostname = substr($in[6], 1, -1);
				$visits = substr($in[7], 1, -1);
				$pageviews = substr($in[8], 1, -1);
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
				
				$sys_id = $this->model->google_system($system);
				$bro_id = $this->model->google_browser($browser);
				$scr_id = $this->model->google_screen($screenResolution);
				$dev_id = $this->model->google_device($device);

				if(!empty($site_id))
				{
					$arr = array('vis_cid' => $cid,
							     'site_id' => $site_id,
								 'domain_id' => $domain_id,
								 'vis_city' => $city,
								 'sys_id' => $sys_id,
								 'bro_id' => $bro_id, 
								 'scr_id' => $scr_id,
								 'dev_id' => $dev_id,
								 'vis_visits' => $visits,
								 'vis_pageviews' => $pageviews);
					$id = $this->common->getOne("SELECT vis_id FROM " . $this->common->table('google_visitor') . " WHERE vis_cid = '" . $cid . "'");
					if($id < 1)
					{
						$this->db->insert($this->common->table('google_visitor'), $arr);
					}
					else
					{
						$this->db->update($this->common->table('google_visitor'), $arr, array('vis_cid' => $cid));
					}
				}
				$i ++;
			}
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_visitor_first'] = $analytics[$ana_id]['view_visitor_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_visitor_first']));
			}
			else
			{
				$analytics[$ana_id]['view_visitor_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		elseif($type == 'site')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$hostname = substr($in[0], 1, -1);
				$visitBounceRate = substr($in[1], 1, -1);
				$visits = substr($in[2], 1, -1);
				$pageviews = substr($in[3], 1, -1);
				$avgTimeOnSite = substr($in[4], 1, -1);
				$percentNewVisits = substr($in[5], 1, -1);
				$pageviewsPerVisit = substr($in[6], 1, -1);
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;

				if($site_id)
				{
					$time = time() - 86400 * 1;
					$data_id = $this->common->getOne("SELECT data_id FROM " . $this->common->table('site_data') . " WHERE site_id = '" . $site_id . "' AND domain_id = '" . $domain_id . "' AND site_addtime = " .  strtotime(date("Y-m-d", $time)));
					$arr = array('site_id' => $site_id,
					             'domain_id' => $domain_id,
					             'site_year' => date("Y", $time),
								 'site_month' => date("m", $time),
								 'site_day' => date("d", $time),
								 'site_week' => date("w", $time),
								 'site_uv' => $visits,
								 'site_pv' => $pageviews,
								 'site_pageviewsPerVisit' => $pageviewsPerVisit,
								 'site_avgTimeOnSite' => $avgTimeOnSite,
								 'site_percentNewVisits' => $percentNewVisits,
								 'site_visitBounceRate' => $visitBounceRate,
								 'site_addtime' => strtotime(date("Y-m-d", $time)));
					if($data_id)
					{
						$this->db->update($this->common->table('site_data'), $arr, array('data_id' => $data_id));
					}
					else
					{
						$this->db->insert($this->common->table('site_data'), $arr);
						
						$data_id = $this->common->getOne("SELECT data_id FROM " . $this->common->table('site_data') . " WHERE site_id = '" . $site_id  . "' AND domain_id = 0 AND site_addtime = " .  strtotime(date("Y-m-d", $time)));
						
						if($data_id)
						{
							$sql = "UPDATE " . $this->common->table('site_data') . " SET site_uv = site_uv + $visits, site_pv = site_pv + $pageviews WHERE data_id = $data_id";
							$this->db->query($sql);
						}
						else
						{
							$arr['domain_id'] = 0;
							$this->db->insert($this->common->table('site_data'), $arr);
						}
					}				
				}
				$i ++;
			}

			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_site_first'] = $analytics[$ana_id]['view_site_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_site_first']));
			}
			else
			{
				$analytics[$ana_id]['view_site_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		/* 访客入口页信息 */
		elseif($type == 'load')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$dateHour = substr($in[1], 1, -1);
				$hostname = substr($in[2], 1, -1);
				$landingPage = $this->common->baidu_cpc_url(substr($in[3], 1, -1));
				$source = substr($in[4], 1, -1);
				$medium = substr($in[5], 1, -1);
				$dimension10 = substr($in[6], 1, -1);
				$timeOnPage = substr($in[7], 1, -1);
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
				$from_site_id = $this->common->getOne("SELECT site_id FROM " . $this->common->table('from_site') . " WHERE site_domain = '$source'");
				$from_type_id = $this->common->getOne("SELECT type_id FROM " . $this->common->table('from_type') . " WHERE type_name = '$medium'");
				$load_year = substr($dateHour, 0, 4);
				$load_month = substr($dateHour, 4, 2);
				$load_day = substr($dateHour, 6, 2);
				$load_hour = substr($dateHour, 8, 2);
				$load_time = $load_year . "-" . $load_month . "-" . $load_day . " " . $load_hour . ":00:00";
				$load_time = strtotime($load_time);
				$load_view_time = substr($dimension10, 0, 10);

				if(!empty($site_id))
				{
					$arr = array('load_cid' => $cid,
							     'domain_id' => $domain_id,
								 'from_site_id' => $from_site_id,
								 'from_type_id' => $from_type_id,
								 'from_url' => "",
								 'load_page' => $landingPage,
								 'load_view_time' => $load_view_time,
								 'load_year' => $load_year,
								 'load_month' => $load_month,
								 'load_day' => $load_day,
								 'load_hour' => $load_hour,
								 'load_time' => $load_time);
					$this->db->insert($this->common->table('google_load_' . $site_id), $arr);
				}
				$i ++;
			}
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_load_first'] = $analytics[$ana_id]['view_load_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_load_first']));
			}
			else
			{
				$analytics[$ana_id]['view_load_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		/* 访客来路记录 */
		elseif($type == 'path')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$dateHour = substr($in[1], 1, -1);
				$minute = substr($in[2], 1, -1);
				$pagePath = $this->common->baidu_cpc_url(substr($in[3], 1, -1));
				$dimension10 = substr($in[4], 1, -1);
				$hostname = substr($in[5], 1, -1);
				$title = substr($in[6], 1, -1);
				$title = mb_convert_encoding($title,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$timeOnPage = substr($in[7], 1, -1);
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
				$page_year = substr($dateHour, 0, 4);
				$page_month = substr($dateHour, 4, 2);
				$page_day = substr($dateHour, 6, 2);
				$page_hour = substr($dateHour, 8, 2);
				$page_time = $page_year . "-" . $page_month . "-" . $page_day . " " . $page_hour . ":" . $minute . ":00";
				$page_time = strtotime($page_time);
				$page_view_time = substr($dimension10, 0, 10);

				if(!empty($site_id))
				{
					$arr = array('path_cid' => $cid,
								 'domain_id' => $domain_id,
								 'path_vtime' => $page_view_time,
								 'path_url' => $pagePath,
								 'path_title' => $title,
								 'path_time' => $page_time);
					$this->db->insert($this->common->table('google_path_' . $site_id), $arr);
				}
				$i ++;
			}
			
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_path_first'] = $analytics[$ana_id]['view_path_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_path_first']));
			}
			else
			{
				$analytics[$ana_id]['view_path_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		/* 访客来路记录 */
		elseif($type == 'from')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$previousPagePath = substr($in[1], 1, -1);
				$dimension9 = substr($in[2], 1, -1);
				$keyword = substr($in[3], 1, -1);
				$hostname = substr($in[4], 1, -1);
				$pagePath = $this->common->baidu_cpc_url(substr($in[5], 1, -1));
				$dimension10 = substr($in[6], 1, -1);
				$keyword = mb_convert_encoding($keyword,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$timeOnPage = substr($in[7], 1, -1);
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
				$page_view_time = substr($dimension10, 0, 10);

				if(!empty($site_id))
				{
					$load_id = 0;
					/* 如果上一页地址为entrance 则表示当前页为入口页 */
					if($previousPagePath == '(entrance)')
					{
						$load_id = $this->common->getOne("SELECT load_id FROM " . $this->common->table("google_load_" . $site_id) . " WHERE load_cid = '" . $cid . "' AND load_view_time = $page_view_time AND load_page = '" . $pagePath . "'");
						if($load_id >= 1)
						{
							$this->db->update($this->common->table("google_load_" . $site_id), array('from_url' => $dimension9), array('load_id' => $load_id));
						}
					}
					
					$path_id = $this->common->getOne("SELECT path_id FROM " . $this->common->table("google_path_" . $site_id) . " WHERE path_cid = '" . $cid . "' AND path_vtime = $page_view_time AND path_url = '" . $pagePath . "'");
					if($path_id >= 1)
					{
						$this->db->update($this->common->table("google_path_" . $site_id), array('path_pre' => $dimension9, 'load_id' => $load_id), array('path_id' => $path_id));
					}
					
					if($keyword != '(not set)')
					{
						$key_id = $this->common->getOne("SELECT key_id FROM " . $this->common->table("keywords") . " WHERE key_keyword = '" . $keyword . "'");
						if($key_id < 1)
						{
							$this->db->insert($this->common->table("keywords"), array('key_keyword' => $keyword));
							$key_id = $this->db->insert_id();
						}
						if($load_id >= 1)
						{
							$this->db->insert($this->common->table("google_search"), array('site_id' => $site_id, 'load_id' => $load_id, 'key_id' => $key_id));
						}
					}
				}
				$i ++;
			}
			
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_from_first'] = $analytics[$ana_id]['view_from_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_from_first']));
			}
			else
			{
				$analytics[$ana_id]['view_from_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		/* 访客轨迹记录 */
		elseif($type == 'baidu')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$hostname = substr($in[1], 1, -1);
				$dimension10 = substr($in[2], 1, -1);
				$dimension6 = substr($in[3], 1, -1);
				$dimension5 = substr($in[4], 1, -1);
				$landingPagePath = $this->common->baidu_cpc_url(substr($in[5], 1, -1));
				
				$site_info = $this->model->get_siteid($hostname);
				$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
				$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
				$load_view_time = substr($dimension10, 0, 10);
				$id = explode("_",$dimension5);
				
				if(!isset($id[2]))
				{
					continue;
				}
				
				$key_id   = $id[0];
				$group_id = $id[1];
				$plan_id  = $id[2];
				
				$from_type = explode("_",$dimension6);
				$from_site_id = $this->common->getOne("SELECT site_id FROM " . $this->common->table('from_site') . " WHERE site_domain = '" . $from_type[0] . "'");
				$from_type_id = $this->common->getOne("SELECT type_id FROM " . $this->common->table('from_type') . " WHERE type_name = '" . $from_type[1] . "'");
				
				if($site_id)
				{
					$load_id = $this->common->getOne("SELECT load_id FROM " . $this->common->table('google_load_' . $site_id) . " WHERE load_view_time = '" . $load_view_time . "' AND load_cid = '" . $cid . "' AND load_page = '" . $landingPagePath . "'");
					if($load_id)
					{
						$this->db->update($this->common->table('google_load_' . $site_id), array('from_type_id' => $from_type_id, 'from_site_id' => $from_site_id), array('load_id' => $load_id));
						if(($from_type[1] == 'cpc') && ($from_type[0] == 'baidu'))
						{
							$arr = array('load_id' => $load_id,
										 'site_id' => $site_id,
										 'domain_id' => $domain_id,
							             'key_id' => $key_id,
										 'group_id' => $group_id,
										 'plan_id' => $plan_id);
							$this->db->insert($this->common->table('google_bd_cpc'), $arr);
						}
					}
				}

				$i ++;
			}
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_baidu_first'] = $analytics[$ana_id]['view_baidu_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_baidu_first']));
			}
			else
			{
				$analytics[$ana_id]['view_baidu_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
		/* 访客轨迹记录 */
		elseif($type == 'qq')
		{
			$i = 0;
			foreach($info as $val)
			{
				if(empty($val))
				{
					continue;
				}
				$in = explode("{}", $val);
				$cid = substr($in[0], 1, -1);
				$hostname = substr($in[1], 1, -1);
				$dimension10 = substr($in[2], 1, -1);
				$dimension9 = substr($in[3], 1, -1);
				$pagePath = substr($in[4], 1, -1);
				$dimension9 = $this->common->baidu_cpc_url($dimension9);
				$timeOnPage = substr($in[7], 1, -1);
				$dateHour = substr($in[5], 1, -1);
				$minute = substr($in[6], 1, -1);
				
				$load_view_time = substr($dimension10, 0, 10);
				if(substr($hostname, 0, 3) == 'qq.')
				{
					$domain = parse_url($dimension9);
					$site_info = $this->model->get_siteid($domain['host']);
					$site_id = isset($site_info['site_id'])? $site_info['site_id']:0;
					$domain_id = isset($site_info['domain_id'])? $site_info['domain_id']:0;
					$page_year = substr($dateHour, 0, 4);
					$page_month = substr($dateHour, 4, 2);
					$page_day = substr($dateHour, 6, 2);
					$page_hour = substr($dateHour, 8, 2);
					$page_time = $page_year . "-" . $page_month . "-" . $page_day . " " . $page_hour . ":" . $minute . ":00";
					$page_time = strtotime($page_time);
					$load_start_time = $page_year . "-" . $page_month . "-" . $page_day . " 00:00:00";
					$load_start_time = strtotime($load_start_time);
					$load_end_time = $page_year . "-" . $page_month . "-" . $page_day . " 23:59:00";
					$load_end_time = strtotime($load_end_time);
					$page_view_time = substr($dimension10, 0, 10);

					if($site_id)
					{
						$arr = array('path_cid' => $cid,
									 'domain_id' => $domain_id,
									 'path_vtime' => $page_view_time,
									 'path_pre' => $dimension9,
									 'path_url' => $pagePath,
									 'path_title' => '在线客服',
									 'path_time' => $page_time,
									 'is_ask' => 1);

						$load_id = $this->common->getOne("SELECT load_id FROM " . $this->common->table('google_load_' . $site_id) . " WHERE load_cid = '" . $cid . "' AND domain_id = $domain_id AND load_time >= $load_start_time AND load_time <= $load_end_time");
						if($load_id)
						{
							$this->db->update($this->common->table('google_load_' . $site_id), array('is_ask' => 1), array('load_id' => $load_id));
						}
						else
						{
							$load = array('load_cid' => $cid,
										  'domain_id' => $domain_id,
										  'from_url' => $dimension9,
										  'load_page' => $pagePath,
										  'load_view_time' => $page_view_time,
										  'load_year' => $page_year,
										  'load_month' => $page_month,
										  'load_day' => $page_day,
										  'load_hour' => $page_hour,
										  'load_time' => $page_time,
										  'is_ask' => 1);
							$this->db->insert($this->common->table('google_load_' . $site_id), $load);
							$load_id = $this->db->insert_id();
							$arr['load_id'] = $load_id;
						}

						$this->db->insert($this->common->table('google_path_' . $site_id), $arr);
					}
				}
				$i ++;
			}
			$analytics = read_static_cache("analytics");
			if($i >= 100)
			{
				$analytics[$ana_id]['view_qq_first'] = $analytics[$ana_id]['view_qq_first'] + 100;
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => $analytics[$ana_id]['view_qq_first']));
			}
			else
			{
				$analytics[$ana_id]['view_qq_first'] = '-1';
				$analytics[0]['action_time'] = time();
				write_static_cache("analytics", $analytics);
				echo json_encode(array('status' => 1,'first' => -1));
			}
		}
	}
}