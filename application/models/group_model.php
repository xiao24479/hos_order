<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 系统公告表 hui_system_message,     message_id,message_title,message_content,message_time,message_user,is_pass
//已读消息表hui_message_read     read_id , message_id, admin_id, read_time
class Group_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
        
	function add_group($parm=array()){
		$name   =isset($parm['name'])?trim($parm['name']):'';
		$add_time =isset($parm['add_time'])?trim($parm['add_time']):'';
		$data=array(
		   'name'   =>$name,
		   'parent_id'   =>$parm['parent_id'],
		   'hos_id'   =>$parm['hos_id'],
		   'keshi_id'   =>$parm['keshi_id'],
		   'add_time' =>$add_time
		);
		$this->db->trans_start();
		$rs=$this->db->insert($this->common->table('user_groups'),$data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		if($rs){
			return $id;
		}else{
			return 0;
		}
	}
}   