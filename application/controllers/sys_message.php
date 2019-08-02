<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_message extends CI_Controller{
    
    public function __construct() {
        //继承父类 其中调用的方法，各个函数都能够使用
        parent::__construct();
        $this->load->model('Sys_message_model');
		$this->lang->load('order');
		$this->lang->load('common');
		$this->model = $this->Sys_message_model;
    }
    //系统公告添加
    public function add_mes(){
                $message_title   =isset($_REQUEST['message_title'])?trim($_REQUEST['message_title']):'';
                $message_content =isset($_REQUEST['message_content'])?trim($_REQUEST['message_content']):'';
                $message_user    =isset($_COOKIE['l_admin_name'])?trim($_COOKIE['l_admin_name']):'';
                $is_pass         =isset($_REQUEST['is_pass'])?intval($_REQUEST['is_pass']):0;
                $message=array(
                      'message_title'   =>$message_title,
                      'message_content' =>$message_content,
                      'message_user'    =>$message_user,
                      'is_pass'         =>$is_pass
            
                       );
        
        
        $res=$this->model->add_message($message);
        if($res==true){
            $link[0]=array('href'=>'?c=sys_message&m=mes_list','text'=>'公告列表');
            $this->common->msg('添加成功！',1,$link);
        }else{
            $this->common->msg('添加失败，返回！',0);
        }
        
        
        
        
    }
    //显示系统公告添加页面
    public function mes_add(){
       
        $data=array();
        $data=$this->common->config('mes_add');
        
        
        
        
       $data['top'] = $this->load->view('top', $data, true);
       $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
       $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
		
       $this->load->view('sys_message/add_message', $data);
        
    }
    //管理员的消息列表
    public function mes_list(){
        
          $data=array();
        $data=$this->common->config('mes_list');
        $sql="select * from ".$this->common->table('system_message')." where is_pass=1 order by message_time desc";
        $res=$this->common->getAll($sql);
        
        $data['mes_list']=$res;
       $data['top'] = $this->load->view('top', $data, true);
       $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
       $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
		
       $this->load->view('sys_message/mes_list', $data);
        
        
        
    }
    //用户的系统公告列表
    public function mess_list(){
        
          $data=array();
          
          
          
        $data=$this->common->config('index');
        $sql="select * from ".$this->common->table('system_message')." where is_pass=1 order by message_time desc";
        $res=$this->common->getAll($sql);
        $query="select message_id from ".$this->common->table('system_message')." where is_pass=1 and message_id not in(select message_id from ".$this->common->table('message_read')." where admin_id=".$_COOKIE['l_admin_id'].") order by message_time desc" ;                         
//        $query_2="select * from ".$this->table('system_message')." where is_pass=1 and message_id in(select message_id from ".$this->table('message_read')." where admin_id=".$_COOKIE['l_admin_id'].") order by message_time desc" ;
        
        $res1=$this->common->getAll($query);
//        $res_2=$this->getAll($query_2);
        $aa=array();
       foreach($res1 as $val){
           
           $aa[]=$val['message_id']; 
       }
        $data['no_read']=$aa;  
       
        $data['mes_list']=$res;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
		
        $this->load->view('sys_message/message_list', $data);
        
        
        
    }
   //编辑系统公告页面
    public function edit_mes(){
        $message_id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        $data=array();
        $data=$this->common->config('mes_list');
        $sql="select * from ".$this->common->table('system_message')." where  message_id=".$message_id;
        $res=$this->common->getAll($sql);
        
        $data['mes_list']=$res;
       $data['top'] = $this->load->view('top', $data, true);
       $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
       $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
		
       $this->load->view('sys_message/edit_mes', $data); 
        
        
    }
    //更新系统消息
    public function update_mes(){
        
                $message_title   =isset($_REQUEST['message_title'])?trim($_REQUEST['message_title']):'';
                $message_content =isset($_REQUEST['message_content'])?trim($_REQUEST['message_content']):'';
                $message_user    =isset($_COOKIE['l_admin_name'])?trim($_COOKIE['l_admin_name']):'';
                $is_pass         =isset($_REQUEST['is_pass'])?intval($_REQUEST['is_pass']):0;
                $message_id         =isset($_REQUEST['message_id'])?intval($_REQUEST['message_id']):0;
                $message=array(
                      'message_title'   =>$message_title,
                      'message_content' =>$message_content,
                      'message_user'    =>$message_user,
                      'is_pass'         =>$is_pass
            
                       );
                $this->db->trans_start();
                $this->db->where('message_id', $message_id);
                $res=$this->db->update($this->common->table('system_message'),$message);
                $this->db->trans_complete();
                 if($res==true){
            $link[0]=array('href'=>'?c=sys_message&m=mes_list','text'=>'公告列表');
            $this->common->msg('更新成功！',1,$link);
        }else{
            $this->common->msg('更新失败，返回！',0);
        }
                
    }
    //读取消息ajax
    public function read_mes_ajax(){
        
        $message_id=isset($_REQUEST['message_id'])?intval($_REQUEST['message_id']):0;
         $this->db->trans_start(); 
        $query="select message_title,message_content from ".$this->common->table('system_message')." where message_id=".$message_id;
        $res=$this->common->getAll($query);
        $query_2="insert into ".$this->common->table('message_read')."(message_id,admin_id) values(".$message_id.",".$_COOKIE['l_admin_id'].")";
        $this->db->query($query_2);
          $this->db->trans_complete();
        $data=array();
        $data['title']=$res[0]['message_title'];
        $data['content']=$res[0]['message_content'];
        $data = json_encode($data);
        echo $data;
        
    }
    
    public function del_mes(){
        
        $mes_id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        
        $this->db->trans_start(); 
        $query="delete from ".$this->common->table('message_read')." where message_id= ".$mes_id;
        $this->db->query($query);
          $this->db->trans_complete();
        if($this->db->affect_rows()>0){
            $link[0]=array('href'=>'?c=sys_message&m=mes_list','text'=>'公告列表');
            $this->common->msg('更新成功！',1,$link);
        }else{
            
             $this->common->msg('更新失败，返回！',0);
        }
    }
    //展示某条消息的内容
    public function message_content(){
        $mes_id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        $sql="select * from ".$this->common->table('system_message')." where message_id=".$mes_id;
        $rs=$this->common->getAll($sql);
        $data['mes_content']=$rs;
        $this->db->trans_start(); 
        $query_2="insert into ".$this->common->table('message_read')."(message_id,admin_id) values(".$mes_id.",".$_COOKIE['l_admin_id'].")";
        $this->db->query($query_2);
          $this->db->trans_complete();
        
        $this->load->view("sys_message/message_content",$data);
    }
    
    
}