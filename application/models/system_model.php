<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页model
class System_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function themes_count($where)
	{
		$sql = "SELECT COUNT(*) FROM " . $this->common->table('sms_themes') . " t WHERE $where";
		return $this->common->getOne($sql);
	}
	
	function themes($where, $first, $size)
	{
		$sql = "SELECT t.themes_id, t.themes_name, t.themes_content, h.hos_name, k.keshi_name
				FROM " . $this->common->table('sms_themes') . " t
				LEFT JOIN " . $this->common->table('hospital') . " h ON h.hos_id = t.hos_id
				LEFT JOIN " . $this->common->table('keshi') . " k ON k.keshi_id = t.keshi_id
				WHERE $where
				LIMIT $first, $size";
		return $this->common->getAll($sql);
	}
	
	function sms_log_count($where)
	{
		$sql = "SELECT COUNT(*) FROM " . $this->common->table('sms_send') . " s WHERE $where";
		return $this->common->getOne($sql);
	}
	
	function sms_log_list($where, $first, $size)
	{
		$sql = "SELECT s.send_id, s.send_content, s.send_time, s.send_phone, s.send_type, s.type_value,
				s.hos_id, s.keshi_id, s.admin_id, s.admin_name, s.send_status, h.hos_name, k.keshi_name, s.send_status
				FROM " . $this->common->table('sms_send') . " s
				LEFT JOIN " . $this->common->table('hospital') . " h ON h.hos_id = s.hos_id
				LEFT JOIN " . $this->common->table('keshi') . " k ON k.keshi_id = s.keshi_id where $where
				ORDER BY s.send_id DESC
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		return $row;
	}
}