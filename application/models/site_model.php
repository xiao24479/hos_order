<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页model
class site_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function from_site_count()
	{
		$where = 1;
		$sql = "SELECT COUNT(*) FROM " . $this->common->table('from_site') . " WHERE $where";
		return $this->common->getOne($sql);
	}
	
	function from_site_list($first = 0, $size = 0)
	{
		$where = 1;
		$order = 'site_id DESC';
		$sql = "SELECT * FROM " . $this->common->table('from_site') . " WHERE $where ORDER BY $order LIMIT $first, $size";
		$arr = $this->common->getAll($sql);
		return $arr;
	}

	function site_info($site_id)
	{
		if(empty($site_id))
		{
			return false;
		}
		$sql = "SELECT s.*, si.sys_id, si.site_ba_no, si.xz_id, si.site_ba_com, si.site_ba_name, si.site_ba_time, si.site_ip, si.site_host, si.site_host_system, si.site_domain_time, ss.sys_name, bx.xz_name FROM " .$this->common->table('site') ." s
		       LEFT JOIN " .$this->common->table('site_info') . " si ON si.site_id = s.site_id
			   LEFT JOIN " .$this->common->table('site_system') . " ss ON ss.sys_id = si.sys_id
			   LEFT JOIN " .$this->common->table('ba_xz') . " bx ON bx.xz_id = si.xz_id WHERE s.site_id = $site_id";
		$arr = $this->common->getRow($sql);
		$arr['site_time'] = date("Y-m-d", $arr['site_time']);
		$arr['site_ba_time'] = date("Y-m-d", $arr['site_ba_time']);
		$arr['site_domain_time'] = date("Y-m-d", $arr['site_domain_time']);
		$sql = "SELECT rank_id FROM " . $this->common->table('site_rank') . " WHERE site_id = $site_id";
		$rank_id_arr = $this->common->getAll($sql);
		$sql = "SELECT key_keyword FROM " . $this->common->table('site_keywords') . " WHERE site_id = $site_id";
		
		$keywords = $this->common->getAll($sql);
		
		return array('info' => $arr, 'rank_id_arr' => $rank_id_arr, 'keywords' => $keywords);
	}
	
	function site_count($rank_id)
	{
		$where = 1;
		if($rank_id != 1)
		{
			$where .= " AND r.rank_id = $rank_id";
		}
		$sql = "SELECT COUNT(*) FROM " . $this->common->table('site') . " s
				LEFT JOIN " . $this->common->table('site_rank') . " r ON r.site_id = s.site_id 
				WHERE $where
				GROUP BY s.site_id";
		return $this->common->getOne($sql);
	}
	
	function site_list($first = 0, $size = 20, $rank_id)
	{
		$where = 1;
		if($rank_id != 1)
		{
			$where .= " AND r.rank_id = $rank_id";
		}
		$time = strtotime(date("Y-m-d",time() - 86400));
		$order = ' s.site_order ASC, s.site_id DESC';
		$sql = "SELECT s.site_id AS s_id, s.site_domain AS site_show_domain, s.site_mobile_domain, s.site_name, s.site_time, s.site_bd
		        FROM " . $this->common->table('site') . " s
				LEFT JOIN " . $this->common->table('site_rank') . " r ON r.site_id = s.site_id
				WHERE $where
				GROUP BY s.site_id
				ORDER BY $order
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();
		$site_id_arr = array();
		foreach($row as $val)
		{
			$arr[$val['s_id']]['site_id'] = $val['s_id'];
			$arr[$val['s_id']]['site_bd'] = $val['site_bd'];
			$arr[$val['s_id']]['site_name'] = $val['site_name'];
			$arr[$val['s_id']]['site_show_domain'] = $val['site_show_domain'];
			$arr[$val['s_id']]['site_mobile_domain'] = $val['site_mobile_domain'];
			$arr[$val['s_id']]['site_time'] = date("Y-m-d", $val['site_time']);
			$site_id_arr[] = $val['s_id'];
			$arr[$val['s_id']]['data_count'] = 0;
		}
		
		$sql = "SELECT * FROM " . $this->common->table('site_data') . " WHERE site_id IN (" . implode(",", $site_id_arr) . ") AND site_addtime = " . $time . "";
		$row = $this->common->getAll($sql);
		foreach($row as $val)
		{
			$key = $val['domain_id'];
			$domain = '';
			if($val['domain_id'] == 0 || $val['domain_id'] == 1)
			{
				$domain = $arr[$val['site_id']]['site_show_domain'];
			}
			else
			{
				$domain = $arr[$val['site_id']]['site_mobile_domain'];
			}
			$arr[$val['site_id']]['data_count'] ++;
			$arr[$val['site_id']]['data'][$key]['domain_id'] = $val['domain_id'];
			$arr[$val['site_id']]['data'][$key]['site_ip'] = $val['site_ip'];
			$arr[$val['site_id']]['data'][$key]['site_id'] = $val['site_id'];
			$arr[$val['site_id']]['data'][$key]['site_order'] = $val['site_order'];
			$arr[$val['site_id']]['data'][$key]['site_ask'] = $val['site_ask'];
			$arr[$val['site_id']]['data'][$key]['site_domain'] = $domain;
			$arr[$val['site_id']]['data'][$key]['site_uv'] = $val['site_uv'];
			$arr[$val['site_id']]['data'][$key]['site_pv'] = $val['site_pv'];
			$arr[$val['site_id']]['data'][$key]['site_pageviewsPerVisit'] = $val['site_pageviewsPerVisit'];
			$arr[$val['site_id']]['data'][$key]['site_avgTimeOnSite'] = $val['site_avgTimeOnSite'];
			$arr[$val['site_id']]['data'][$key]['site_percentNewVisits'] = $val['site_percentNewVisits'];
			$arr[$val['site_id']]['data'][$key]['site_visitBounceRate'] = $val['site_visitBounceRate'];
			/*$arr[$val['site_id']]['data'][0]['site_ip'] = isset($arr[$val['site_id']]['data'][0]['site_ip'])? ($arr[$val['site_id']]['data'][0]['site_ip'] + $val['site_ip']):$val['site_ip'];
			$arr[$val['site_id']]['data'][0]['site_order'] = isset($arr[$val['site_id']]['data'][0]['site_order'])? ($arr[$val['site_id']]['data'][0]['site_order'] + $val['site_order']):$val['site_order'];
			$arr[$val['site_id']]['data'][0]['site_ask'] = isset($arr[$val['site_id']]['data'][0]['site_ask'])? ($arr[$val['site_id']]['data'][0]['site_ask'] + $val['site_ask']):$val['site_ask'];
			$arr[$val['site_id']]['data'][0]['site_uv'] = isset($arr[$val['site_id']]['data'][0]['site_uv'])? ($arr[$val['site_id']]['data'][0]['site_uv'] + $val['site_uv']):$val['site_uv'];
			$arr[$val['site_id']]['data'][0]['site_pv'] = isset($arr[$val['site_id']]['data'][0]['site_pv'])? ($arr[$val['site_id']]['data'][0]['site_pv'] + $val['site_pv']):$val['site_pv'];*/
		}
		return $arr;
	}
	
	function site_bd_info($site_id)
	{
		$site_info = $this->common->getRow("SELECT s.site_id, s.site_domain, s.site_name, s.site_bd_username, s.site_bd_password, s.site_bd_token, b.bd_url_down, b.bd_url_up,
											b.bd_id, b.bd_balance, b.bd_cost, b.bd_payment, b.bd_budget, b.bd_opendomains, b.bd_regdomain, b.bd_regiontarget, b.bd_update 
		                                    FROM " . $this->common->table('site') . " s
											LEFT JOIN " . $this->common->table('site_bd') . " b ON b.site_id = s.site_id WHERE s.site_id = $site_id");
		return $site_info;
	}
	
	function data($site_id, $domain_id, $start, $end)
	{
		$start = strtotime($start);
		$end = strtotime($end);
		$where = '1';
		$sql = "SELECT s.site_month, s.site_day, s.site_ip, s.site_uv, s.site_pv,
				s.site_ask, s.site_order, s.site_daozhen
				FROM " . $this->common->table('site_data') . " s
				WHERE $where AND s.site_addtime <= $end AND s.site_addtime >= $start AND s.site_id = $site_id AND domain_id = '" . $domain_id . "' 
				ORDER BY s.data_id ASC";
		$row = $this->common->getAll($sql);
		if(empty($row))
		{
			return false;
		}
		foreach($row as $val)
		{
			$time_arr[] = $val['site_month'] . '月' . $val['site_day'] . "日";
			$ip_arr[] = $val['site_ip'];
			$uv_arr[] = $val['site_uv'];
			$pv_arr[] = $val['site_pv'];
			$ask_arr[] = $val['site_ask'];
			$order_arr[] = $val['site_order'];
			$daozhen_arr[] = $val['site_daozhen'];
		}
		
		return array('arr' => $row, 'time_arr' => $time_arr, 'ip_arr' => $ip_arr, 'uv_arr' => $uv_arr, 'pv_arr' => $pv_arr, 'ask_arr' => $ask_arr, 'order_arr' => $order_arr, 'daozhen_arr' => $daozhen_arr);
	}
	
	function area_data($site_id, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$where = '1';
		$sql = "SELECT s.vis_city, COUNT(s.vis_id) AS uv, SUM(v.view_pv) AS pv
				FROM " . $this->common->table('visitor_' . $site_id) . " s
				LEFT JOIN " . $this->common->table('view_' . $site_id) . " v ON v.view_cookie_id = s.vis_cookie_id
				WHERE $where AND v.view_time <= $end AND v.view_time >= $start 
				GROUP BY s.vis_city 
				ORDER BY uv DESC
				LIMIT 0, 10";

		$row = $this->common->getAll($sql);
		
		if(empty($row))
		{
			return false;
		}
		
		$arr = array();
		foreach($row as $key => $val)
		{
			$arr['city'][$key] = $val['vis_city'];
			$arr['uv'][$key] = $val['uv'];
			$arr['pv'][$key] = $val['pv'];
		}
		
		$arr['city'] = array_reverse($arr['city']);
		$arr['uv'] = array_reverse($arr['uv']);
		$arr['pv'] = array_reverse($arr['pv']);
		return $arr;
	}
	
	function order_area_data($site_id, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$where = '1';
		$sql = "SELECT d.*, i.*, a.admin_name 
				FROM " . $this->common->table('ask_data') . " d
				LEFT JOIN " . $this->common->table('ask_data_info') . " i ON d.data_id = i.data_id
				LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = d.admin_id
				WHERE  d.site_id = $site_id AND d.data_time <= $end AND d.data_time >= $start 
				ORDER BY d.data_id ASC";
		$row = $this->common->getAll($sql);
		$arr = array();
		$arr['area'] = array();
		$arr['asker'] = array();
		foreach($row as $val)
		{
			if(empty($val['data_city']))
			{
				$val['data_city'] = '其它';
			}
			$arr['area'][$val['data_city']]['ask'] = !isset($arr['area'][$val['data_city']]['ask'])? 1:($arr['area'][$val['data_city']]['ask'] + 1);
			$arr['area'][$val['data_city']]['order'] = isset($arr['area'][$val['data_city']]['order'])? $arr['area'][$val['data_city']]['order']:0;
			$arr['area'][$val['data_city']]['dao'] = isset($arr['area'][$val['data_city']]['dao'])? $arr['area'][$val['data_city']]['dao']:0;
			$arr['area'][$val['data_city']]['ask_1'] = isset($arr['area'][$val['data_city']]['ask_1'])? $arr['area'][$val['data_city']]['ask_1']:0;
			$arr['area'][$val['data_city']]['ask_2'] = isset($arr['area'][$val['data_city']]['ask_2'])? $arr['area'][$val['data_city']]['ask_2']:0;
			$arr['area'][$val['data_city']]['ask_3'] = isset($arr['area'][$val['data_city']]['ask_3'])? $arr['area'][$val['data_city']]['ask_3']:0;
			
			$arr['asker'][$val['admin_name']]['ask'] = !isset($arr['asker'][$val['admin_name']]['ask'])? 1:($arr['asker'][$val['admin_name']]['ask'] + 1);
			$arr['asker'][$val['admin_name']]['order'] = isset($arr['asker'][$val['admin_name']]['order'])? $arr['asker'][$val['admin_name']]['order']:0;
			$arr['asker'][$val['admin_name']]['dao'] = isset($arr['asker'][$val['admin_name']]['dao'])? $arr['asker'][$val['admin_name']]['dao']:0;
			$arr['asker'][$val['admin_name']]['ask_1'] = isset($arr['asker'][$val['admin_name']]['ask_1'])? $arr['asker'][$val['admin_name']]['ask_1']:0;
			$arr['asker'][$val['admin_name']]['ask_2'] = isset($arr['asker'][$val['admin_name']]['ask_2'])? $arr['asker'][$val['admin_name']]['ask_2']:0;
			$arr['asker'][$val['admin_name']]['ask_3'] = isset($arr['asker'][$val['admin_name']]['ask_3'])? $arr['asker'][$val['admin_name']]['ask_3']:0;
			
			if($val['is_order'] == 1)
			{
				$arr['area'][$val['data_city']]['order'] ++;
				$arr['asker'][$val['admin_name']]['order'] ++;
			}
			if($val['is_dao'] == 1)
			{
				$arr['area'][$val['data_city']]['dao'] ++;
				$arr['asker'][$val['admin_name']]['dao'] ++;
			}
			if($val['data_viewer_words'] > 3 && $val['data_viewer_words'] <= 10)
			{
				$arr['area'][$val['data_city']]['ask_2'] ++;
				$arr['asker'][$val['admin_name']]['ask_1'] ++;
			}
			elseif($val['data_viewer_words'] > 0 && $val['data_viewer_words'] <= 3)
			{
				$arr['area'][$val['data_city']]['ask_1'] ++;
				$arr['asker'][$val['admin_name']]['ask_2'] ++;
			}
			else
			{
				$arr['area'][$val['data_city']]['ask_3'] ++;
				$arr['asker'][$val['admin_name']]['ask_3'] ++;
			}
		}
		return $arr;
	}
	
	function cpc($site_id, $start, $end)
	{
		$start = strtotime($start);
		$end = strtotime($end);
		$where = '1';
		$sql = "SELECT * FROM " . $this->common->table('bd_cpc_account') . " WHERE $where AND site_id = $site_id AND bd_addtime <= $end AND bd_addtime >= $start ORDER BY data_id ASC";
		$row = $this->common->getAll($sql);
		if(empty($row))
		{
			return false;
		}
		
		$arr = array();
		$arr['arr'] = $row;
		foreach($row as $val)
		{
			$arr['time_arr'][] = $val['bd_month'] . '月' . $val['bd_day'] . "日";
			$arr['cost'][] = $val['bd_day_cost'];
			$arr['show'][] = $val['bd_day_show'];
			$arr['click'][] = $val['bd_day_click'];
			$arr['click_lv'][] = $val['bd_day_click_lv'];
			$arr['pc_cost'][] = $val['bd_pc_cost'];
			$arr['pc_show'][] = $val['bd_pc_show'];
			$arr['pc_click'][] = $val['bd_pc_click'];
			$arr['pc_click_lv'][] = $val['bd_pc_click_lv'];
			$arr['mobile_cost'][] = $val['bd_mobile_cost'];
			$arr['mobile_show'][] = $val['bd_mobile_show'];
			$arr['mobile_click'][] = $val['bd_mobile_click'];
			$arr['mobile_click_lv'][] = $val['bd_mobile_click_lv'];
		}
		return $arr;
	}
	
	function cpc_keyword_count($site_id, $type, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		
		if(empty($type))
		{
			$table = $this->common->table('bd_cpc_day_' . $site_id);
		}
		elseif($type == 'pc')
		{
			$table = $this->common->table('bd_cpc_day_pc_' . $site_id);
		}
		else
		{
			$table = $this->common->table('bd_cpc_day_mobile_' . $site_id);
		}
		
		$where = '1';
		$sql = "SELECT COUNT(*) FROM 
					(SELECT COUNT(*) AS count
					FROM " . $table ." 
					WHERE $where AND day_time <= $end AND day_time >= $start 
					GROUP BY key_id) c";
		$count = $this->common->getOne($sql);
		return $count;
	}
	
	function cpc_keyword($site_id, $type, $start, $end, $order_by, $page, $size)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$where = 1;
		if(empty($type))
		{
			$table = $this->common->table('bd_cpc_day_' . $site_id);
			$sql = "SELECT c.plan_id, c.group_id, c.key_id, c.word_id, c.key_word, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT d.key_id, COUNT(d.data_id) AS ask_count, SUM(d.is_order) AS order_count, SUM(d.is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " d
								   LEFT JOIN " . $this->common->table('ask_data_info') . " i ON d.data_id = i.data_id
						 		   WHERE d.key_id > 0 AND d.data_time <= $end AND d.data_time >= $start AND i.data_viewer_words >= 3
								   GROUP BY d.key_id) a ON a.key_id = c.key_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.key_id
					ORDER BY $order_by, c.key_id DESC
					LIMIT $page, $size";
		}
		elseif($type == 'pc')
		{
			$table = $this->common->table('bd_cpc_day_pc_' . $site_id);
			$sql = "SELECT c.plan_id, c.group_id, c.key_id, c.word_id, c.key_word, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT key_id, COUNT(data_id) AS ask_count, SUM(is_order) AS order_count, SUM(is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " 
						 		   WHERE key_id > 0 AND data_time <= $end AND data_time >= $start AND data_type = 1
								   GROUP BY key_id) a ON a.key_id = c.key_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.key_id
					ORDER BY $order_by, c.key_id DESC
					LIMIT $page, $size";
		}
		else
		{
			$table = $this->common->table('bd_cpc_day_mobile_' . $site_id);
			$sql = "SELECT c.plan_id, c.group_id, c.key_id, c.word_id, c.key_word, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT key_id, COUNT(data_id) AS ask_count, SUM(is_order) AS order_count, SUM(is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " 
						 		   WHERE key_id > 0 AND data_time <= $end AND data_time >= $start AND data_type = 2
								   GROUP BY key_id) a ON a.key_id = c.key_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.key_id
					ORDER BY $order_by, c.key_id DESC
					LIMIT $page, $size";
		}
		//echo $sql;
		$row = $this->common->getAll($sql);
		//print_r($row);
		/*if(empty($row))
		{
			return false;
		}
		$arr = array();
		foreach($row as $key=>$val)
		{
			$arr['keyword'][$key] = $val['key_word'];
			$arr['shows'][$key]   = $val['shows'];
			$arr['clicks'][$key]  = $val['clicks'];
			$arr['cost'][$key]    = $val['cost'];
			$arr['click_lv'][$key] = number_format(($val['click_lv'] * 100), 2, ".", "");
			$arr['price'][$key] = number_format($val['price'], 2, ".", "");
			$arr['qian_show_cost'][$key] = number_format(($val['qian_show_cost'] * 1000), 2, ".", "");
		}
		$key_re = array_repeat($arr['keyword']);

		foreach($arr['keyword'] as $k => $v)
		{
			foreach($key_re as $key => $val)
			{
				if($val == $v)
				{
					$arr['keyword'][$k] = $v . "(*)";
					unset($key_re[$key]);
					continue;
				}
			}
		}
		
		$arr['keyword'] = array_reverse($arr['keyword']);
		$arr['shows'] = array_reverse($arr['shows']);
		$arr['clicks'] = array_reverse($arr['clicks']);
		$arr['cost'] = array_reverse($arr['cost']);
		$arr['click_lv'] = array_reverse($arr['click_lv']);
		$arr['price'] = array_reverse($arr['price']);
		$arr['qian_show_cost'] = array_reverse($arr['qian_show_cost']);

		return $arr;*/
		
		$arr = array();
		foreach($row as $key=>$val)
		{
			$arr[$key]['keyword'] = $val['key_word'];
			$arr[$key]['shows']   = $val['shows'];
			$arr[$key]['clicks']  = $val['clicks'];
			$arr[$key]['cost']    = $val['cost'];
			$arr[$key]['ask_count']    = $val['ask_count'];
			$arr[$key]['ask_cb']    = number_format($val['ask_cb'], 2, ".", "");
			if($val['clicks'] > 0)
			{
				$arr[$key]['ask_lv']    = number_format((($val['ask_count']/$val['clicks']) * 100), 2, ".", "");
			}
			else
			{
				$arr[$key]['ask_lv']    = 0;
			}
			$arr[$key]['order_count']    = $val['order_count'];
			$arr[$key]['order_lv']    = number_format($val['order_lv'], 2, ".", "");
			$arr[$key]['dao_count']    = $val['dao_count'];
			$arr[$key]['dao_lv']    = number_format($val['dao_lv'], 2, ".", "");
			$arr[$key]['click_lv'] = number_format(($val['click_lv'] * 100), 2, ".", "");
			$arr[$key]['price'] = number_format($val['price'], 2, ".", "");
			$arr[$key]['qian_show_cost'] = number_format(($val['qian_show_cost'] * 1000), 2, ".", "");
		}
		
		return $arr;
	}
	
	function cpc_plan_count($site_id, $type, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		
		if(empty($type))
		{
			$table = $this->common->table('bd_cpc_day_' . $site_id);
		}
		elseif($type == 'pc')
		{
			$table = $this->common->table('bd_cpc_day_pc_' . $site_id);
		}
		else
		{
			$table = $this->common->table('bd_cpc_day_mobile_' . $site_id);
		}
		
		$where = '1';
		$sql = "SELECT COUNT(*) FROM 
					(SELECT COUNT(*) AS count
					FROM " . $table ." 
					WHERE $where AND day_time <= $end AND day_time >= $start 
					GROUP BY plan_id) c";
		$count = $this->common->getOne($sql);
		return $count;
	}
	
	function cpc_plan_data($site_id, $type, $start, $end, $order_by, $page, $size)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$where = '1';
		if(empty($type))
		{
			$table = $this->common->table('bd_cpc_day_' . $site_id);
			$sql = "SELECT c.plan_id, p.plan_name, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT plan_id, COUNT(data_id) AS ask_count, SUM(is_order) AS order_count, SUM(is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " 
						 		   WHERE key_id > 0 AND data_time <= $end AND data_time >= $start
								   GROUP BY plan_id) a ON a.plan_id = c.plan_id
					LEFT JOIN " . $this->common->table('bd_plan') . " p ON p.plan_id = c.plan_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.plan_id
					ORDER BY $order_by, c.plan_id DESC
					LIMIT $page, $size";
		}
		elseif($type == 'pc')
		{
			$table = $this->common->table('bd_cpc_day_pc_' . $site_id);
			$sql = "SELECT c.plan_id, p.plan_name, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT plan_id, COUNT(data_id) AS ask_count, SUM(is_order) AS order_count, SUM(is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " 
						 		   WHERE key_id > 0 AND data_time <= $end AND data_time >= $start AND data_type = 1
								   GROUP BY plan_id) a ON a.plan_id = c.plan_id
					LEFT JOIN " . $this->common->table('bd_plan') . " p ON p.plan_id = c.plan_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.plan_id
					ORDER BY $order_by, c.plan_id DESC
					LIMIT $page, $size";
		}
		else
		{
			$table = $this->common->table('bd_cpc_day_mobile_' . $site_id);
			$sql = "SELECT c.plan_id, p.plan_name, SUM(c.day_shows) AS shows, SUM(c.day_clicks) AS clicks, 
					SUM(c.day_cost) AS cost, (SUM(c.day_clicks)/SUM(c.day_shows)) AS click_lv,
					(SUM(c.day_cost)/SUM(c.day_clicks)) AS price,
					(SUM(c.day_cost)/SUM(c.day_shows)) AS qian_show_cost,
					a.ask_count, a.order_count, a.dao_count,
					(SUM(c.day_cost)/a.ask_count) AS ask_cb, (a.ask_count/COUNT(c.day_clicks)) AS ask_lv,
					(a.order_count/a.ask_count) AS order_lv, (a.dao_count/a.order_count) AS dao_lv
					FROM " . $table ." c
					LEFT JOIN (SELECT plan_id, COUNT(data_id) AS ask_count, SUM(is_order) AS order_count, SUM(is_dao) AS dao_count
							       FROM " . $this->common->table('ask_data') . " 
						 		   WHERE key_id > 0 AND data_time <= $end AND data_time >= $start AND data_type = 2
								   GROUP BY plan_id) a ON a.plan_id = c.plan_id
					LEFT JOIN " . $this->common->table('bd_plan') . " p ON p.plan_id = c.plan_id
					WHERE $where AND c.day_time <= $end AND c.day_time >= $start
					GROUP BY c.plan_id
					ORDER BY $order_by, c.plan_id DESC
					LIMIT $page, $size";
		}
		
		$row = $this->common->getAll($sql);
		
		$arr = array();
		foreach($row as $key=>$val)
		{
			$arr[$key]['plan_name'] = $val['plan_name'];
			$arr[$key]['shows']   = $val['shows'];
			$arr[$key]['clicks']  = $val['clicks'];
			$arr[$key]['cost']    = $val['cost'];
			$arr[$key]['ask_count']    = $val['ask_count'];
			$arr[$key]['ask_cb']    = number_format($val['ask_cb'], 2, ".", "");
			$arr[$key]['ask_lv']    = number_format($val['ask_lv'], 2, ".", "");
			$arr[$key]['order_count']    = $val['order_count'];
			$arr[$key]['order_lv']    = number_format($val['order_lv'], 2, ".", "");
			$arr[$key]['dao_count']    = $val['dao_count'];
			$arr[$key]['dao_lv']    = number_format($val['dao_lv'], 2, ".", "");
			$arr[$key]['click_lv'] = number_format(($val['click_lv'] * 100), 2, ".", "");
			$arr[$key]['price'] = number_format($val['price'], 2, ".", "");
			$arr[$key]['qian_show_cost'] = number_format(($val['qian_show_cost'] * 1000), 2, ".", "");
		}
		
		return $arr;
	}
	
	function cpc_hour($site_id, $type, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		if(empty($type))
		{
			$table = $this->common->table('bd_cpc_hour_' . $site_id);
		}
		elseif($type == 'pc')
		{
			$table = $this->common->table('bd_cpc_hour_pc_' . $site_id);
		}
		else
		{
			$table = $this->common->table('bd_cpc_hour_mobile_' . $site_id);
		}
		$where = '1';
		$sql = "SELECT hour_hour, SUM(hour_shows) AS shows, SUM(hour_clicks) AS clicks, 
				SUM(hour_cost) AS cost, (SUM(hour_clicks)/SUM(hour_shows)) AS click_lv,
				(SUM(hour_cost)/SUM(hour_clicks)) AS price
				FROM " . $table . " 
				WHERE $where AND hour_time <= $end AND hour_time >= $start
				GROUP BY hour_hour
				ORDER BY hour_hour DESC";
		$row = $this->common->getAll($sql);
		if(empty($row))
		{
			return false;
		}
		$arr = array();
		foreach($row as $key=>$val)
		{
			$arr['hour_hour'][$key]   = $val['hour_hour'];
			$arr['shows'][$key]   = $val['shows'];
			$arr['clicks'][$key]  = $val['clicks'];
			$arr['cost'][$key]    = $val['cost'];
			$arr['click_lv'][$key] = number_format(($val['click_lv'] * 100), 2, ".", "");
			$arr['price'][$key] = number_format($val['price'], 2, ".", "");
		}

		$arr['hour_hour'] = array_reverse($arr['hour_hour']);
		$arr['shows'] = array_reverse($arr['shows']);
		$arr['clicks'] = array_reverse($arr['clicks']);
		$arr['cost'] = array_reverse($arr['cost']);
		$arr['click_lv'] = array_reverse($arr['click_lv']);
		$arr['price'] = array_reverse($arr['price']);
		return $arr;
	}
	
	function search_keyword_list($pro_id, $first, $size)
	{
		$sql = "SELECT * 
				FROM ". $this->common->table('search_keyword') . " 
				WHERE pro_id = $pro_id 
				ORDER BY key_search DESC
				LIMIT $first, $size";
		return $this->common->getAll($sql);
	}
	
	function get_search_report_cat($pro_id, $type = '')
	{
		if($type != "")
		{
			$sql = "SELECT COUNT(k.key_id) AS key_count, SUM(k.key_search) AS search_sum, SUM(k.bd_site) AS bd_site_sum, 
					SUM(k.google_site) AS google_site_sum, SUM(k.bd_cpc) AS bd_cpc_sum, k.area_name AS name
					FROM ". $this->common->table('search_keyword') . " k
					LEFT JOIN ". $this->common->table('search_category') . " c ON c.cat_id = k.cat_id
					WHERE k.pro_id = $pro_id AND k.area_name != ''
					GROUP BY k.area_name
					ORDER BY search_sum DESC
					LIMIT 0, 20";
		}
		else
		{
			$sql = "SELECT COUNT(k.key_id) AS key_count, SUM(k.key_search) AS search_sum, SUM(k.bd_site) AS bd_site_sum, 
					SUM(k.google_site) AS google_site_sum, SUM(k.bd_cpc) AS bd_cpc_sum, c.cat_name AS name
					FROM ". $this->common->table('search_keyword') . " k
					LEFT JOIN ". $this->common->table('search_category') . " c ON c.cat_id = k.cat_id
					WHERE k.pro_id = $pro_id AND k.cat_id != 0
					GROUP BY k.cat_id
					ORDER BY search_sum DESC";
		}
		$row = $this->common->getAll($sql);
		$count = array();
		$count['key_count'] = 0;
		$count['search_sum'] = 0;
		$count['bd_site_sum'] = 0;
		$count['google_site_sum'] = 0;
		foreach($row as $val)
		{
			$count['key_count'] += $val['key_count'];
			$count['search_sum'] += $val['search_sum'];
			$count['bd_site_sum'] += $val['bd_site_sum'];
			$count['google_site_sum'] += $val['google_site_sum'];
		}
		$arr = array();
		foreach($row as $key=>$val)
		{
			$arr['name'][$key] = $val['name'];
			$arr['key_count'][$key] = $val['key_count'];
			$arr['key_count_lv'][$key] = number_format((($val['key_count'] / $count['key_count']) * 100), 2, ".", "");
			$arr['search_sum'][$key] = $val['search_sum'];
			$arr['search_sum_lv'][$key] = number_format((($val['search_sum'] / $count['search_sum']) * 100), 2, ".", "");
			$arr['bd_site_sum'][$key] = $val['bd_site_sum'];
			$arr['bd_site_sum_lv'][$key] = number_format((($val['bd_site_sum'] / $count['bd_site_sum']) * 100), 2, ".", "");
			$arr['google_site_sum'][$key] = $val['google_site_sum'];
			$arr['google_site_sum_lv'][$key] = number_format((($val['google_site_sum'] / $count['google_site_sum']) * 100), 2, ".", "");
			$arr['bd_cpc_sum'][$key] = $val['bd_cpc_sum'];
		}
		return $arr;
	}
	
	function site_from_data($site_id, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$sql = "SELECT v.from_site, t.type_name, COUNT(v.view_id) AS views, SUM(v.view_pv) AS pv,
				SUM(IF((v.view_times - 1), 0, 1)) AS v_time, SUM(IF((v.view_pv - 1), 0, 1)) AS v_pv 
				FROM " . $this->common->table("view_" . $site_id) . " v
				LEFT JOIN " . $this->common->table("from_type") . " t ON t.type_id = v.from_type_id
				WHERE v.view_time <= $end AND v.view_time >= $start
				GROUP BY v.from_site, v.from_type_id
				ORDER BY views DESC
				LIMIT 0, 20";
		$row = $this->common->getAll($sql);
		return $row;
	}
	
	function site_page_views($site_id, $start, $end)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		$sql = "SELECT p.path_view, COUNT(p.path_id) AS path_times, SUM(p.path_time) AS path_view_time, COUNT(DISTINCT p.view_id) AS views,
				SUM(IF((v.view_times - 1), 0, 1)) AS v_time, SUM(IF((v.view_pv - 1), 0, 1)) AS v_pv
				FROM " . $this->common->table("view_path_" . $site_id) . " p
				LEFT JOIN " . $this->common->table("view_" . $site_id) . " v ON v.view_id = p.view_id
				WHERE v.view_time <= $end AND v.view_time >= $start
				GROUP BY p.path_view
				ORDER BY path_times DESC
				LIMIT 0, 50";
		$row = $this->common->getAll($sql);
		//print_r($row);
		return $row;
	}
	
	/*新加内容*/
		/*获取内容列表信息的用户名*/
	function site_seo_con_username()
	{
		$sql = "SELECT admin_name, con_id , admin_id FROM " . $this->common->table('seo_content') ." GROUP BY admin_id";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['con_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['con_id']]['admin_id'] = $val['admin_id'];
		}
		return $arr;
	}
	/*获取外链列表信息的用户名*/
	function site_seo_link_username()
	{
		$sql = "SELECT admin_name, link_id , admin_id FROM " . $this->common->table('seo_link') ." GROUP BY admin_id";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['link_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['link_id']]['admin_id'] = $val['admin_id'];
		}
		return $arr;
	}
	/*获取内容列表信息的站点下拉表*/
	function site_seo_con_site_point()
	{
		$sql = "SELECT si.site_id, si.site_domain, seo_co.con_id, seo_co.site_id FROM " . $this->common->table('site') . " si LEFT JOIN " .$this->common->table('seo_content') . " seo_co ON si.site_id = seo_co.site_id  WHERE si.site_id = seo_co.site_id GROUP BY si.site_id";
		$row = $this->common->getAll($sql);
		return $row;
	}
	/*获取外链列表信息的站点下拉表*/
	function site_seo_link_site_point()
	{
		$sql = "SELECT si.site_id, si.site_domain, seo_li.link_id, seo_li.site_id FROM " . $this->common->table('site') . " si LEFT JOIN " .$this->common->table('seo_link') . " seo_li ON si.site_id = seo_li.site_id  WHERE si.site_id = seo_li.site_id GROUP BY si.site_id";
		$row = $this->common->getAll($sql);
		return $row;
	}
	/*查询内容列表信息的的数据*/
	function site_seo_con($admin_id)
	{
		$sql = "SELECT admin_id, con_id, site_id, con_url, add_time, is_index, index_time, bd_pm, con_keyword FROM " . $this->common->table('seo_content') . " WHERE admin_id = $admin_id order by add_time desc ";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['con_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['con_id']]['con_url'] = $val['con_url'];
			$arr[$val['con_id']]['con_keyword'] = $val['con_keyword'];
			$arr[$val['con_id']]['is_index'] = $val['is_index'];
			$arr[$val['con_id']]['bd_pm'] = $val['bd_pm'];
			$arr[$val['con_id']]['add_time'] = date("Y-m-d",$val['add_time']);
			$arr[$val['con_id']]['index_time'] = date("Y-m-d",$val['index_time']);
		}
		return $arr;		
	}
	/*查询外链列表信息的的数据*/
	function site_seo_link($admin_id)
	{
		$sql = "SELECT link_id, site_id, link_url, add_time, is_index, index_time, link_keyword FROM " . $this->common->table('seo_link') . " WHERE admin_id = $admin_id order by add_time desc ";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['link_id']]['link_url'] = $val['link_url'];
			$arr[$val['link_id']]['link_keyword'] = $val['link_keyword'];
			$arr[$val['link_id']]['is_index'] = $val['is_index'];
			$arr[$val['link_id']]['add_time'] = date("Y-m-d",$val['add_time']);
			$arr[$val['link_id']]['index_time'] = date("Y-m-d",$val['index_time']);
		}
		return $arr;
	}

	/*优化收录数据*/
	function site_seo_data($site_id)
	{
		$sql = "SELECT * FROM " . $this->common->table('site_seo') . " WHERE site_id = $site_id";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $key => $val)
		{
			$arr[$key]['site_id'] = $val['site_id'];
			$arr[$key]['site_year'] = $val['site_year'];
			$arr[$key]['site_month'] = $val['site_month'];
			$arr[$key]['site_day'] = $val['site_day'];
			if($val['site_month'] < 10)
			{
			 	$val['site_month'] = "0" . $val['site_month'] ;
			}
			if($val['site_day'] < 10)
			{
			 	$val['site_day'] = "0" . $val['site_day'] ;
			}
			$arr[$key]['site_time'] = $val['site_year'] . "-" . $val['site_month'] . "-" . $val['site_day'];
			$arr[$key]['site_week'] = $val['site_week'];
			$arr[$key]['site_addinfo'] = $val['site_addinfo'];
			$arr[$key]['site_addlink'] = $val['site_addlink'];
			$arr[$key]['site_baidusite'] = $val['site_baidusite'];
			$arr[$key]['site_googlelink'] = $val['site_googlelink'];
			$arr[$key]['site_baidudomain'] = $val['site_baidudomain'];
			$arr[$key]['site_googlesite'] = $val['site_googlesite'];
			$arr[$key]['seo_info_change'] = $val['seo_info_change'];
			$arr[$key]['seo_link_change'] = $val['seo_link_change'];
			$arr[$key]['seo_baidutime_type'] = $val['seo_baidutime_type'];
			$arr[$key]['site_br'] = $val['site_br'];
			$arr[$key]['site_pr'] = $val['site_pr'];
			$arr[$key]['site_baidutime'] = date("Y-m-d",$val['site_baidutime']);
		}
		/*将查询出的数组按照时间排序*/
		function array_sort($arr,$keys,$type='asc')
		{ 
			$keysvalue = $new_array = array();
			foreach ($arr as $k=>$v){
				$keysvalue[$k] = $v[$keys];
			}
			if($type == 'asc'){
				arsort($keysvalue);
			}
			else
			{
				asort($keysvalue);
			}
			reset($keysvalue);
			foreach ($keysvalue as $k=>$v)
			{
				$new_array[$k] = $arr[$k];
			}
			return $new_array; 
		} 
		$arr = array_sort($arr, 'site_time');
		return $arr;
	}
	/* 优化收录数据页面的网站详细信息参数获取*/
	function site_seo_siteinfo($site_id)
	{	   
		$sql = "SELECT si.site_id, si.site_domain, si.site_name, si.site_time, si.swt_url, info.*, sys.*, ba.* FROM " .$this->common->table('site') ." si
				LEFT JOIN " .$this->common->table('site_info') . " info ON si.site_id = info.site_id 
				LEFT JOIN " .$this->common->table('site_system') . " sys ON info.sys_id = sys.sys_id
				LEFT JOIN " .$this->common->table('ba_xz') . " ba ON info.xz_id = ba.xz_id WHERE si.site_id = $site_id ";
		$arr = $this->common->getRow($sql);
		$arr['site_time'] = date("Y-m-d", $arr['site_time']);
		$arr['site_ba_time'] = date("Y-m-d", $arr['site_ba_time']);
		$arr['site_domain_time'] = date("Y-m-d", $arr['site_domain_time']);
		return $arr;
	}
	/*获取当前站点的所有关键词*/
	function site_seo_keywords($site_id)
	{
		$sql = "SELECT site_id, key_id, key_keyword FROM " . $this->common->table('site_keywords') . " WHERE site_id = $site_id";
		$arr = $this->common->getAll($sql);
		return $arr;
	}
	/*当前站点的关键词排名数据*/
	function site_keyword_log($site_id)
	{
		$date = date("Y-m-d");
		$date = explode("-",$date);
		$sql = "SELECT k.key_id, k.log_id, k.log_year, k.log_month, k.log_day, k.bd_pm, k.google_pm, k.360_pm, k.bd_pm_change, k.google_pm_change, k.360_pm_change, si.site_id, si.key_id,                si.key_keyword  FROM " .$this->common->table('site_keyword_log') . " k 
		       LEFT JOIN " . $this->common->table('site_keywords') . " si ON k.key_id = si.key_id WHERE si.site_id = $site_id AND k.log_year = $date[0] AND k.log_month = $date[1] AND                k.log_day = $date[2]";
		$arr = $this->common->getAll($sql); 
		foreach($arr as $key => $val)
		{
			$arr[$key]['key_id'] = $val['key_id'];
			$arr[$key]['key_keyword'] = $val['key_keyword'];
			$arr[$key]['log_year'] = $val['log_year'];
			$arr[$key]['log_month'] = $val['log_month'];
			$arr[$key]['log_day'] = $val['log_day'];
			$arr[$key]['bd_pm'] = $val['bd_pm'];
			$arr[$key]['google_pm'] = $val['google_pm'];
			$arr[$key]['360_pm'] = $val['360_pm'];
			$arr[$key]['ba_pm_change'] = $val['bd_pm_change'];
			$arr[$key]['google_pm_change'] = $val['google_pm_change'];
			$arr[$key]['360_pm_change'] = $val['360_pm_change'];
			if($val['log_month'] < 10)
			{
			 	$val['log_month'] = "0" . $val['log_month'] ;
			}
			if($val['log_day'] < 10)
			{
			 	$val['log_day'] = "0" . $val['log_day'] ;
			}
			$arr[$key]['log_time'] =  $val['log_year'] . "-" . $val['log_month'] . "-" . $val['log_day'];
		}
		return $arr;
	}
	/*每天的关键词排名数据*/
	function log_keywords($site_id,$key_id)
	{
		$sql = "SELECT k.key_id, k.log_id, k.log_year, k.log_month, k.log_day, k.bd_pm, k.google_pm, k.360_pm, k.bd_pm_change, k.google_pm_change, k.360_pm_change, si.site_id, si.key_id,                si.key_keyword  FROM " .$this->common->table('site_keyword_log') . " k 
		       LEFT JOIN " . $this->common->table('site_keywords') . " si ON k.key_id = si.key_id WHERE si.site_id = $site_id AND si.key_id = $key_id";
		$arr = $this->common->getAll($sql);
		foreach($arr as $key => $val)
		{
			$arr[$key]['key_id'] = $val['key_id'];
			$arr[$key]['key_keyword'] = $val['key_keyword'];
			$arr[$key]['log_year'] = $val['log_year'];
			$arr[$key]['log_month'] = $val['log_month'];
			$arr[$key]['log_day'] = $val['log_day'];
			$arr[$key]['bd_pm'] = $val['bd_pm'];
			$arr[$key]['google_pm'] = $val['google_pm'];
			$arr[$key]['360_pm'] = $val['360_pm'];
			$arr[$key]['ba_pm_change'] = $val['bd_pm_change'];
			$arr[$key]['google_pm_change'] = $val['google_pm_change'];
			$arr[$key]['360_pm_change'] = $val['360_pm_change'];
			if($val['log_month'] < 10)
			{
			 	$val['log_month'] = "0" . $val['log_month'] ;
			}
			if($val['log_day'] < 10)
			{
			 	$val['log_day'] = "0" . $val['log_day'] ;
			}
			$arr[$key]['log_time'] =  $val['log_year'] . "-" . $val['log_month'] . "-" . $val['log_day'];
		}
		/*将查询出的数组按照时间排序*/
		function array_sort($arr,$keys,$type='asc')
		{ 
			$keysvalue = $new_array = array();
			foreach ($arr as $k=>$v){
				$keysvalue[$k] = $v[$keys];
			}
			if($type == 'asc'){
				arsort($keysvalue);
			}
			else
			{
				asort($keysvalue);
			}
			reset($keysvalue);
			foreach ($keysvalue as $k=>$v)
			{
				$new_array[$k] = $arr[$k];
			}
			return $new_array; 
		} 
		$arr = array_sort($arr, 'log_time');
		return $arr;
	}
	
	function get_siteid($hostname)
	{
		$site_list = read_static_cache('site');
		foreach($site_list as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				if($hostname == $k)
				{
					return array('site_id' => $key, 'domain_id' => $v);
					continue;
				}
			}
		}
	}
	
	function google_system($system)
	{
		$sys_id = $this->common->getOne("SELECT sys_id FROM " . $this->common->table('google_system') . " WHERE sys_name = '" . $system . "'");
		if($sys_id < 1)
		{
			$this->db->query("INSERT INTO " . $this->common->table('google_system') . " VALUES (NULL, '" . $system . "')");
			$sys_id = $this->db->insert_id();
			
			$row = $this->common->getAll("SELECT sys_id, sys_name FROM " . $this->common->table('google_system') . " ORDER BY sys_id ASC");
			$google_system = array();
			foreach($row as $val)
			{
				$google_system[$val['sys_id']]['sys_id'] = $val['sys_id'];
				$google_system[$val['sys_id']]['sys_name'] = $val['sys_name'];
			}
			write_static_cache("google_system", $google_system);
		}
		return $sys_id;
	}
	
	function google_browser($browser)
	{
		$bro_id = $this->common->getOne("SELECT bro_id FROM " . $this->common->table('google_browser') . " WHERE bro_name = '" . $browser . "'");
		if($bro_id < 1)
		{
			$this->db->query("INSERT INTO " . $this->common->table('google_browser') . " VALUES (NULL, '" . $browser . "')");
			$bro_id = $this->db->insert_id();
			
			$row = $this->common->getAll("SELECT bro_id, bro_name FROM " . $this->common->table('google_browser') . " ORDER BY bro_id ASC");
			$google_browser = array();
			foreach($row as $val)
			{
				$google_browser[$val['bro_id']]['bro_id'] = $val['bro_id'];
				$google_browser[$val['bro_id']]['bro_name'] = $val['bro_name'];
			}
			write_static_cache("google_browser", $google_browser);
		}
		return $bro_id;
	}
	
	function google_screen($screen)
	{
		$id = $this->common->getOne("SELECT scr_id FROM " . $this->common->table('google_screen') . " WHERE scr_screen = '" . $screen . "'");
		if($id < 1)
		{
			$this->db->query("INSERT INTO " . $this->common->table('google_screen') . " VALUES (NULL, '" . $screen . "')");
			$id = $this->db->insert_id();
			
			$row = $this->common->getAll("SELECT scr_id, scr_screen FROM " . $this->common->table('google_screen') . " ORDER BY scr_id ASC");
			$google_screen = array();
			foreach($row as $val)
			{
				$google_screen[$val['scr_id']]['scr_id'] = $val['scr_id'];
				$google_screen[$val['scr_id']]['scr_name'] = $val['scr_screen'];
			}
			write_static_cache("google_screen", $google_screen);
		}
		return $id;
	}
	
	function google_device($device)
	{
		$id = $this->common->getOne("SELECT dev_id FROM " . $this->common->table('google_device') . " WHERE dev_name = '" . $device . "'");
		if($id < 1)
		{
			$this->db->query("INSERT INTO " . $this->common->table('google_device') . " VALUES (NULL, '" . $device . "')");
			$id = $this->db->insert_id();
			
			$row = $this->common->getAll("SELECT dev_id, dev_name FROM " . $this->common->table('google_device') . " ORDER BY dev_id ASC");
			$google_device = array();
			foreach($row as $val)
			{
				$google_device[$val['dev_id']]['dev_id'] = $val['dev_id'];
				$google_device[$val['dev_id']]['dev_name'] = $val['dev_name'];
			}
			write_static_cache("google_device", $google_device);
		}
		return $id;
	}
	
	function page_path_count($site_id, $domain_id, $start, $end, $where)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		
		if($domain_id != 0)
		{
			$where .= " AND l.domain_id = $domain_id";
		}

		$sql = "SELECT DISTINCT l.load_cid FROM " . $this->common->table('google_load_' . $site_id) . " l WHERE $where AND l.load_time >= $start AND l.load_time <= $end";
		$row = $this->common->getAll($sql);
		return count($row);
	}
	
	function page_path($site_id, $domain_id, $start, $end, $first, $size, $where)
	{
		$start = strtotime($start . ' 00:00:00');
		$end = strtotime($end . ' 23:59:59');
		
		$sql = "SELECT l.from_site_id, l.from_type_id, l.from_url, l.load_time, l.is_ask, v.vis_city, v.sys_id, v.bro_id, v.scr_id, v.dev_id, v.vis_cid, k.key_keyword
				FROM " . $this->common->table('google_load_' . $site_id) . " l
				LEFT JOIN " . $this->common->table('google_visitor') . " v ON v.site_id = $site_id AND l.load_cid = v.vis_cid
				LEFT JOIN " . $this->common->table('google_search') . " s ON l.load_id = s.load_id AND s.site_id = $site_id
				LEFT JOIN " . $this->common->table('keywords') . " k ON k.key_id = s.key_id
				WHERE $where ";
		if($domain_id != 0)
		{
			$sql .= "AND l.domain_id = $domain_id ";
		}

		$sql .= "AND l.load_time >= $start AND l.load_time <= $end
				ORDER BY l.load_time DESC
				LIMIT $first, $size";

		$row = $this->common->getAll($sql);
//echo $sql;
		$visitor = array();
		$visit_id = array();
		foreach($row as $val)
		{
			$visitor[$val['vis_cid']]['vis_cid'] = $val['vis_cid'];
			$visitor[$val['vis_cid']]['vis_city'] = $val['vis_city'];
			$visitor[$val['vis_cid']]['load_time'] = $val['load_time'];
			$visitor[$val['vis_cid']]['from_url'] = $val['from_url'];
			$visitor[$val['vis_cid']]['from_type_id'] = $val['from_type_id'];
			$visitor[$val['vis_cid']]['from_site_id'] = $val['from_site_id'];
			$visitor[$val['vis_cid']]['sys_id'] = $val['sys_id'];
			$visitor[$val['vis_cid']]['bro_id'] = $val['bro_id'];
			$visitor[$val['vis_cid']]['dev_id'] = $val['dev_id'];
			$visitor[$val['vis_cid']]['scr_id'] = $val['scr_id'];
			$visitor[$val['vis_cid']]['is_ask'] = $val['is_ask'];
			$visitor[$val['vis_cid']]['key_name'] = $val['key_keyword'];

			$visit_id[] = "'" . $val['vis_cid'] . "'";
		}
		
		if(empty($visit_id))
		{
			return array('visitor' => $visitor, 'path' => array());
		}
		$sql = "SELECT path_cid, path_pre, path_url, path_title, path_time, is_ask, COUNT(path_cid) AS path_url_reload
				FROM " . $this->common->table('google_path_' . $site_id) . " 
				WHERE path_cid IN (" . implode(",", $visit_id) . ") ";
		
		if($domain_id != 0)
		{
			$sql .= " AND domain_id = $domain_id ";
		}

		$sql .= "AND path_time >= $start AND path_time <= $end
				GROUP BY path_cid, path_url
				ORDER BY path_vtime ASC, path_time DESC";
		//echo $sql;
		$row = $this->common->getAll($sql);
		$path = array();
		foreach($row as $key=>$val)
		{
			$path[$val['path_cid']][$key]['path_cid'] = $val['path_cid'];
			$path[$val['path_cid']][$key]['path_pre'] = $val['path_pre'];
			$path[$val['path_cid']][$key]['path_url'] = $val['path_url'];
			$path[$val['path_cid']][$key]['path_title'] = $val['path_title'];
			$path[$val['path_cid']][$key]['path_time'] = $val['path_time'];
			$path[$val['path_cid']][$key]['is_ask'] = $val['is_ask'];
			$path[$val['path_cid']][$key]['path_url_reload'] = $val['path_url_reload'];
		}

		return array('visitor' => $visitor, 'path' => $path);
	}
}