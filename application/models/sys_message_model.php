<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 系统公告表 hui_system_message,     message_id,message_title,message_content,message_time,message_user,is_pass
//已读消息表hui_message_read     read_id , message_id, admin_id, read_time
class Sys_message_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
        
        function add_message($message=array()){
            $message_title   =isset($message['message_title'])?trim($message['message_title']):'';
            $message_content =isset($message['message_content'])?trim($message['message_content']):'';
            $message_time    =time();
            $message_user    =isset($message['message_user'])?trim($message['message_user']):'';
            $is_pass         =isset($message['is_pass'])?trim($message['is_pass']):'';
            
            
            $data=array(
               'message_title'   =>$message_title,
               'message_content' =>$message_content,
               'message_user'    =>$message_user,
               'is_pass'         =>$is_pass,
               'message_time'   =>$message_time
                       );
            $this->db->trans_start();
            $rs=$this->db->insert($this->common->table('system_message'),$data);
            $this->db->trans_complete();
          
             if($rs){
                
               return true;
                
            }else{
                
                 return false;
                
            }
            
            
            
            
            
            
            
            
            
            
        }      
        
        
        
        
        
        
        
}   