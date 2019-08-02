<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 系统设置
class Site_seo extends CI_Controller
{
	var $model;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Site_model');
		$this->lang->load('site');
		$this->lang->load('common');
		
		$this->model = $this->Site_model;
	}
	
	/*关键词搜索项目*/
	public function search_project()
	{
		$data = array();
		$data = $this->common->config('search_project');
		
		$sql = "SELECT pro_name, pro_description, pro_id FROM ". $this->common->table('search_project') . " ORDER BY pro_id";
		$pro_list = $this->common->getAll($sql);
		$data['pro_list'] = $pro_list;
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('search_project', $data);
	}
	
	public function search_update()
	{
		$pro_name = isset($_REQUEST['pro_name'])? trim($_REQUEST['pro_name']):"";
		$pro_description = isset($_REQUEST['pro_description'])? trim($_REQUEST['pro_description']):"";
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		$form_action = $_REQUEST['form_action'];
		
		if(empty($pro_name) || empty($pro_description))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$project = array('pro_name' => $pro_name,
						 'pro_description' => $pro_description);
						 
		if($form_action == 'add')
		{
			$this->db->insert($this->common->table('search_project'), $project);
		}
		else
		{
			$this->db->update($this->common->table('search_project'), $project, array('pro_id' => $pro_id));
		}
		header("location:?c=site_seo&m=search_project\r\n");
	}
	
	public function project_ajax()
	{
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($pro_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$info = $this->common->getRow("SELECT * FROM " . $this->common->table('search_project') . " WHERE pro_id = $pro_id");
		echo json_encode($info);
	}
	
	/*删除搜索数据项目*/
	public function search_del()
	{
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($pro_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}	
		$this->db->delete($this->common->table("search_project"), array('pro_id' => $pro_id));
		$this->db->delete($this->common->table("search_category"), array('pro_id' => $pro_id));
		$this->db->delete($this->common->table("search_keyword"), array('pro_id' => $pro_id));
		$links[0] = array('href' => '?c=site_seo&m=search_project', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	/*关键词类型*/
	public function search_category()
	{
		$data = array();
		$data = $this->common->config('search_category');
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($pro_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$pro_info = $this->common->getRow("SELECT * FROM " . $this->common->table('search_project') . " WHERE pro_id = $pro_id");
		$sql = "SELECT * FROM ". $this->common->table('search_category') . " WHERE pro_id = $pro_id ORDER BY cat_order ASC, pro_id DESC";
		$cat_list = $this->common->getAll($sql);
		$data['cat_list'] = $cat_list;
		$data['pro_info'] = $pro_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('search_category', $data);
	}
	
	public function search_cat_update()
	{
		$form_action = $_REQUEST['form_action'];
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		$cat_name = isset($_REQUEST['cat_name'])? trim($_REQUEST['cat_name']):"";
		$cat_order = isset($_REQUEST['cat_order'])? intval($_REQUEST['cat_order']):0;
		$cat_id = isset($_REQUEST['cat_id'])? intval($_REQUEST['cat_id']):0;
		if(empty($cat_name) || empty($cat_order) || $pro_id == 0)
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$key_cat = array('pro_id' => $pro_id,
						 'cat_name' => $cat_name,
						 'cat_order' => $cat_order);
						 
		if($form_action == 'add')
		{
			$this->db->insert($this->common->table('search_category'), $key_cat);
		}
		else
		{
			$this->db->update($this->common->table('search_category'), $key_cat, array('cat_id' => $cat_id));
		}
		
		$links[0] = array('href' => '?c=site_seo&m=search_category&pro_id=' . $pro_id, 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function ajax_search_cat()
	{
		$cat_id = isset($_REQUEST['cat_id'])? intval($_REQUEST['cat_id']):0;
		if(empty($cat_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$info = $this->common->getRow("SELECT * FROM " . $this->common->table('search_category') . " WHERE cat_id = $cat_id");
		echo json_encode($info);
	}
	
	public function search_cat_del()
	{
		$cat_id = isset($_REQUEST['cat_id'])? intval($_REQUEST['cat_id']):0;
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($cat_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->db->delete($this->common->table('search_category'), array('cat_id' => $cat_id));
		
		$links[0] = array('href' => '?c=site_seo&m=search_category&pro_id=' . $pro_id, 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	/*导入excel关键词列表*/
	public function search_excel()
	{
		$data = array();
		$data = $this->common->config('search_excel');
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		$pro_info = $this->common->getRow("SELECT * FROM " . $this->common->table('search_project') . " WHERE pro_id = $pro_id");
		
		$data['pro_info'] = $pro_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('search_excel', $data);
	}
	
	public function search_excel_upload()
	{
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($pro_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->common->config('search_excel');
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
		
		$this->load->library('excel');
		$this->excel->setOutputEncoding('CP936');
		$this->excel->_encoderFunction = 'mb_convert_encoding';
		$this->excel->read($file);
		foreach($this->excel->sheets[0]['cells'] as $key => $val)
		{
			if($key !== 1)
			{
				$val[2] = mb_convert_encoding($val[2],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
				$arr = array('pro_id' => $pro_id,
							 'key_name' => $val[2],
							 'key_search' => $val[3],
							 'bd_site' => $val[4],
							 'bd_cpc' => $val[5],
							 'google_site' => $val[6]);
				/*重复数据过滤*/
				$sql = "SELECT key_id FROM " . $this->common->table('search_keyword') . " WHERE pro_id = $pro_id AND key_name = '" . $val[2] . "'";
				$is_havd = $this->common->getOne($sql);
				if(!$is_havd)
				{
					$this->db->insert($this->common->table('search_keyword'),$arr);
				}
			}
		}
		$file_zui = explode(".", $file);
		if(isset($file_zui[1]))
		{
			unlink($file);
		}
	}
	
	/*关键词搜索数据*/
	public function search_keyword()
	{
		$data = array();
		$data = $this->common->config('search_keyword');
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		
		$this->load->helper('page');
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$this->load->library('pagination');
		
		$count = $this->common->getOne("SELECT COUNT(*) FROM " . $this->common->table('search_keyword') .  " WHERE pro_id = $pro_id");
		$config = page_config();
		$config['base_url'] = '?c=site_seo&m=search_keyword&pro_id=' . $pro_id;
		$config['total_rows'] = $count;
		$config['per_page'] = '50';
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$data['page'] = $this->pagination->create_links();
		$data['keyword_list'] = $this->model->search_keyword_list($pro_id, $page, $config['per_page']);
		$data['per_page'] = $page;
		$data['cat'] = $this->common->getAll("SELECT cat_id, cat_name FROM ". $this->common->table('search_category') . " WHERE pro_id = $pro_id");
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('search_keyword', $data);
	}
	
	public function search_keyword_update()
	{
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		$key_id = isset($_REQUEST['key_id'])? $_REQUEST['key_id']:array();
		$cat_id = isset($_REQUEST['cat_id'])? $_REQUEST['cat_id']:array();
		$area_name = isset($_REQUEST['area_name'])? $_REQUEST['area_name']:array();
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		$key_arr = array();
		foreach($key_id as $key => $val)
		{
			$key_arr[$key]['key_id'] = $val;
			$key_arr[$key]['cat_id'] = $cat_id[$key];
			$key_arr[$key]['area_name'] = trim($area_name[$key]);
		}

		foreach($key_arr as $key => $val)
		{
			$area = array('area_name' => $val['area_name'], 'cat_id' => $val['cat_id']);
			$this->db->update($this->common->table('search_keyword'), $area, array('key_id' => $val['key_id']));
		}
		$links[0] = array('href' => '?c=site_seo&m=search_keyword&pro_id=' . $pro_id . '&per_page=' . $page, 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	/*删除搜索关键词*/
	public function search_key_delete()
	{
		$key_id = isset($_REQUEST['key_id'])? intval($_REQUEST['key_id']):0;
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		if(empty($key_id))
		{
			echo 0;
		}	
		$this->db->delete($this->common->table("search_keyword"), array('key_id' => $key_id));
		echo 1;
	}
	
	/*关键词搜索数据项目报表*/
	public function search_report()
	{
		$data = array();
		$data = $this->common->config('search_report');
		$pro_id = isset($_REQUEST['pro_id'])? intval($_REQUEST['pro_id']):0;
		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';
		$report = $this->model->get_search_report_cat($pro_id, $type);
		$pro_info = $this->common->getRow("SELECT * FROM " . $this->common->table('search_project') . " WHERE pro_id = $pro_id");
		$data['type'] = $type;
		$data['pro_info'] = $pro_info;
		$data['report'] = $report;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('search_report', $data);
	}
	
	/*新添加内容*/
	public function site_seo_add()
	{
		$data = array();
		$data = $this->common->config('site_seo_add');

		if($_COOKIE['l_rank_id'] > 1)
		{
			$rank_info = $this->common->getRow("SELECT rank_level, parent_id FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
			$rank = read_static_cache('rank_arr');
			$rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $rank_info['parent_id']);
			$ranks = array();
			
			// 把同一组的全部获取出来
			foreach($rank as $key => $val)
			{
				if($val['rank_id'] == $_COOKIE['l_rank_id'] )
				{
					$ranks = $val;
				}
			}
			$ranks = recursive_merge($ranks, 'rank_id');
			$rank = array();
			$rank = array_keys($ranks);
			
			$where = "rank_id IN (" . implode(",", $rank) . ")";
		}
		else
		{
			$rank[] = 'all';
			$where = 1;
		}
		
		$user_list = $this->common->getAll("SELECT admin_id, admin_username FROM " . $this->common->table('admin')." WHERE $where");
		$site = $this->common->getAll("SELECT s.site_id, s.site_domain FROM " . $this->common->table('site') . " s, " . $this->common->table('site_rank') . " sr WHERE $where AND sr.site_id = s.site_id GROUP BY s.site_id ORDER BY s.site_id DESC");
		$data['user_list'] = $user_list;
		$data['site'] = $site;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_seo_add', $data);
	}
	
	public function site_seo_update()
	{
		$admin_id = $_COOKIE['l_admin_id'];
		$info = $this->common->getRow("SELECT admin_username FROM " . $this->common->table('admin')." WHERE admin_id = $admin_id");
		$record_type = isset($_REQUEST['record_type'])? intval($_REQUEST['record_type']):0;
		$site_seo_content = isset($_REQUEST['site_seo_content'])? trim($_REQUEST['site_seo_content']):"";
		$con_time = isset($_REQUEST['con_time'])? trim($_REQUEST['con_time']):"";
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$con_time = strtotime($con_time);
		$add_time = time();
		
		$data['info'] = $info;
		$admin_name = $info['admin_username'];
		
		if(empty($site_seo_content) || $record_type == 0 || $site_id ==0)
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$site_seo_content = explode("\r\n" , $site_seo_content);
		
		foreach($site_seo_content as $val)
		{
			$url_keyword = $val;				
			$url_keyword = explode("|",$url_keyword);
			if(!isset($url_keyword[1]))
			{
				continue;
			}
			
			if($record_type == 1)
			{
				$seo_content = array('admin_id' => $admin_id,
							   'admin_name' => $admin_name,
							   'con_url' => $url_keyword[0],
							   'con_keyword' => $url_keyword[1],
							   'site_id' => $site_id,
							   'con_time' => $con_time,
							   'add_time' => $add_time);
				$this->db->insert($this->common->table("seo_content"), $seo_content);
				$links[0] = array('href' => '?c=site&m=site_seo_con', 'text' => $this->lang->line('list_back'));
			}
			elseif($record_type == 2)
			{
				$seo_link = array('admin_id' => $admin_id,
								  'admin_name' => $admin_name,
								  'link_url' => $url_keyword[0],
								  'link_keyword' => $url_keyword[1],
								  'site_id' => $site_id,
								  'link_time' => $record_time,
								  'add_time' => $add_time);
				$this->db->insert($this->common->table("seo_link"), $seo_link);
				$links[0] = array('href' => '?c=site&m=site_seo_link', 'text' => $this->lang->line('list_back'));
			}		
		}
		
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function site_seo_con()
	{
		$data = array();
		$data = $this->common->config('site_seo_con');
		$admin_id = isset($_REQUEST['admin_id'])? intval($_REQUEST['admin_id']):0;
		
		if($_COOKIE['l_rank_id'] > 1)
		{
			$rank_info = $this->common->getRow("SELECT rank_level, parent_id FROM " . $this->common->table('rank') . " WHERE rank_id = " . $_COOKIE['l_rank_id']);
			$rank = read_static_cache('rank_arr');
			$rank = getDataTree($rank, 'rank_id', 'parent_id', 'child', $rank_info['parent_id']);
			$ranks = array();
			
			// 把同一组的全部获取出来
			foreach($rank as $key => $val)
			{
				if($val['rank_id'] == $_COOKIE['l_rank_id'])
				{
					$ranks = $val;
				}
			}
			$ranks = recursive_merge($ranks, 'rank_id');
			$rank = array();
			$rank = array_keys($ranks);
			
			$where = "rank_id IN (" . implode(",", $rank) . ")";
		}
		else
		{
			$rank[] = 'all';
			$where = 1;
		}
		
		$user_list = $this->common->getAll("SELECT admin_id, admin_username FROM " . $this->common->table('admin')." WHERE $where");
		$site = $this->common->getAll("SELECT s.site_id, s.site_domain FROM " . $this->common->table('site') . " s, " . $this->common->table('site_rank') . " sr WHERE $where AND sr.site_id = s.site_id GROUP BY s.site_id ORDER BY s.site_id DESC");
		
		$data['user_list'] = $user_list;
		$data['site'] = $site;
		$data['admin_id'] = $admin_id;
		
		//$data['site'] = $this->model->site_seo_con_site_point();
		$username_info = $this->model->site_seo_con_username(); 
		$site_seo_con = $this->model->site_seo_con($admin_id);
		$data['seo_info'] = $site_seo_con;
		$data['info'] = $username_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_seo_con', $data);
	}
	
	public function site_seo_link()
	{
		$data = array();
		$data = $this->common->config('site_seo_link');
		$admin_id = $_COOKIE['l_admin_id'];
		$data['site'] = $this->model->site_seo_link_site_point();
		$username_info = $this->model->site_seo_link_username(); 
		$site_seo_link = $this->model->site_seo_link($admin_id);
		$data['seo_info'] = $site_seo_link;
		$data['info'] = $username_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('site_seo_link', $data);
	}
	
	public function seo_data()
	{
		$data = array();
		$data = $this->common->config('seo_data');
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$data['site_info'] = $this->model->site_seo_siteinfo($site_id);
		$site_seo = $this->model->site_seo_data($site_id);
		$data['site_seo'] = $site_seo;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('seo_data', $data);
	}
	
	public function seo_data_update()
	{
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		$site_time = isset($_REQUEST['site_time'])? trim($_REQUEST['site_time']):"";
		$site_week = isset($_REQUEST['site_week'])? intval($_REQUEST['site_week']):0;
		$site_baidusite = isset($_REQUEST['site_baidusite'])? intval($_REQUEST['site_baidusite']):0;
		$site_googlesite = isset($_REQUEST['site_googlesite'])? intval($_REQUEST['site_googlesite']):0;
		$site_googlelink = isset($_REQUEST['site_googlelink'])? intval($_REQUEST['site_googlelink']):0;
		$site_baidudomain = isset($_REQUEST['site_baidudomain'])? intval($_REQUEST['site_baidudomain']):0;
		$site_baidutime = isset($_REQUEST['site_baidutime'])? trim($_REQUEST['site_baidutime']):"";
		$site_br = isset($_REQUEST['site_br'])? intval($_REQUEST['site_br']):0;
		$site_pr = isset($_REQUEST['site_pr'])? intval($_REQUEST['site_pr']):0;
		$site_addinfo = isset($_REQUEST['site_addinfo'])? intval($_REQUEST['site_addinfo']):0;
		$site_addlink = isset($_REQUEST['site_addlink'])? intval($_REQUEST['site_addlink']):0;
		$seo_info_change = isset($_REQUEST['seo_info_change'])? intval($_REQUEST['seo_info_change']):0;
		$seo_link_change = isset($_REQUEST['seo_link_change'])? intval($_REQUEST['seo_link_change']):0;
		$site_baidutime = strtotime($site_baidutime);		
		
		/*判断当天的数据是否已经更新*/
		$site_seo = $this->model->site_seo_data($site_id);
		foreach($site_seo as $val)
		{
			if($site_time == $val['site_time'])
		    {
				$this->common->msg($this->lang->line('have_update'), 1);
		    }
		}
		if($site_baidusite <0 || $site_googlesite < 0 || $site_googlelink < 0 || $site_baidudomain < 0 || $site_br < 0 || $site_pr < 0 || $site_addinfo < 0 || $site_addlink < 0)
		{
			$this->common->msg($this->lang->line('error_data'), 1);
		}
		if(strtotime($site_time) == $site_baidutime)
		{
			$seo_baidutime_type = 1;
		}
		if((strtotime($site_time) - $site_baidutime) == 60*60*24)
		{
			$seo_baidutime_type = 2;
		}
		if((strtotime($site_time) - $site_baidutime) > 60*60*24)
		{
			$seo_baidutime_type = 0;
		}
				
		$site_time = explode("-",$site_time);
		$seo_log = array("site_id" => $site_id,
						 "site_year" => $site_time[0],
						 "site_month" => $site_time[1],
						 "site_day" => $site_time[2],
						 "seo_baidutime_type" => $seo_baidutime_type,
						 "site_week" => $site_week,
						 "site_addinfo" => $site_addinfo,
						 "site_addlink" => $site_addlink,
						 "site_baidusite" => $site_baidusite,
						 "site_baidudomain" => $site_baidudomain,
						 "site_baidutime" => $site_baidutime,
						 "site_googlesite" => $site_googlesite,
						 "site_googlelink" => $site_googlelink,
						 "site_br" => $site_br,
						 "site_pr" => $site_pr,
						 "seo_info_change" => $seo_info_change,
						 "seo_link_change" => $seo_link_change);
						
		$this->db->insert($this->common->table("site_seo"), $seo_log);
		$links[0] = array('href' => '?c=site&m=seo_data&site_id='. $site_id, 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function seo_keywords_pm()
	{
		$data = array();
		$data = $this->common->config('seo_keywords_pm');
		$site_id = isset($_REQUEST['site_id'])?intval($_REQUEST['site_id']):0;
		$data['site_info'] = $this->model->site_seo_siteinfo($site_id);
		$site_keywords= $this->model->site_seo_keywords($site_id);
		$data['site_keywords'] = $site_keywords;
	    $keyword_log = $this->model->site_keyword_log($site_id);
		$data['keyword_log'] = $keyword_log;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('seo_keywords_pm', $data);
	}
	
	public function seo_keywords_pm_update()
	{		
		$keywords = isset($_REQUEST['keyword'])? $_REQUEST['keyword']:array();
		$log_time = isset($_REQUEST['log_time'])? $_REQUEST['log_time']:array();
		$bd_pm = isset($_REQUEST['bd_pm'])? $_REQUEST['bd_pm']:array();
		$google_pm = isset($_REQUEST['google_pm'])? $_REQUEST['google_pm']:array();
		$log_360_pm = isset($_REQUEST['log_360_pm'])? $_REQUEST['log_360_pm']:array();
		$baidu_pm_change = isset($_REQUEST['baidu_pm_change'])? $_REQUEST['baidu_pm_change']:array();
		$google_pm_change = isset($_REQUEST['google_pm_change'])? $_REQUEST['google_pm_change']:array();
		$log_360_pm_change = isset($_REQUEST['log_360_pm_change'])? $_REQUEST['log_360_pm_change']:array();
		$site_id = isset($_REQUEST['site_id'])? intval($_REQUEST['site_id']):0;
		
		$log_arr = array();
		foreach($keywords as $key=>$val)
		{
			$log_arr[$key]['key_id'] = $key;
			$log_arr[$key]['log_time'] = $log_time[$key];
			$log_arr[$key]['bd_pm'] = $bd_pm[$key];
			$log_arr[$key]['google_pm'] = $google_pm[$key];
			$log_arr[$key]['log_360_pm'] = $log_360_pm[$key];
			$log_arr[$key]['baidu_pm_change'] = $baidu_pm_change[$key];
			$log_arr[$key]['google_pm_change'] = $google_pm_change[$key];
			$log_arr[$key]['log_360_pm_change'] = $log_360_pm_change[$key];
			$log_arr[$key]['log_time'] = explode("-", $log_arr[$key]['log_time']);
		}

		foreach($log_arr as $key => $val)
		{
			if($val['bd_pm'] < 0 || $val['google_pm'] < 0 || $val['log_360_pm'] < 0)
			{
				$this->common->msg($this->lang->line('error_data'), 1);
			}
			$keywords = array("key_id" => $val['key_id'],
							  "log_year" => $val['log_time'][0],
							  "log_month" => $val['log_time'][1],
							  "log_day" => $val['log_time'][2],
							  "bd_pm" => $val['bd_pm'],
							  "google_pm" => $val['google_pm'],
							  "360_pm" => $val['log_360_pm'],
							  "bd_pm_change" => $val['baidu_pm_change'],
							  "google_pm_change" => $val['google_pm_change'],
							  "360_pm_change" => $val['log_360_pm_change']);
							  
			$this->db->insert($this->common->table("site_keyword_log"), $keywords);
		}
	
		$links[0] = array('href' => '?c=site&m=seo_keywords_pm&site_id='. $site_id, 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	
	public function log_keywords_pm()
	{
		$data = array();
		$data = $this->common->config('log_keywords_pm');
		$site_id = isset($_REQUEST['site_id'])?intval($_REQUEST['site_id']):0;
		$key_id = isset($_REQUEST['key_id'])?intval($_REQUEST['key_id']):0;
		$data['site_info'] = $this->model->site_seo_siteinfo($site_id);
		$data['log_keywords'] = $this->model->log_keywords($site_id,$key_id);
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('log_keywords_pm', $data);
	}
}