<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_person extends CI_Controller{
    
    public function __construct() {
        //继承父类 其中调用的方法，各个函数都能够使用
        parent::__construct();
        $this->load->model('Group_model');
		$this->lang->load('order');
		$this->lang->load('common');
		$this->model = $this->Group_model;
    }
    
    //系统组  设置
    public function set_group_person(){
	    $rank_id = $_REQUEST['rank_id'];//岗位ID
	    $id = $_REQUEST['id'];//组id  
	    if($rank_id > 0 && $id >0 ){
	    	//通过岗位ID
	    	$sql="select admin_id from ".$this->common->table('admin')." where is_pass=1 AND rank_id=".$rank_id;
	    	$user=$this->common->getAll($sql);
	    	$admin_id_str= array();
	    	foreach($user as $user_temp){
	    		$admin_id_str[] =$user_temp['admin_id'];
	    	}
	    	$group=array( 'admin_group' =>$id);
	    	$this->db->trans_start();
	    	$this->db->where_in('admin_id', $admin_id_str);
	    	$res=$this->db->update($this->common->table('admin'),$group);
	    	$this->db->trans_complete();
	    	if($res){
	    		$msg_detail = $this->lang->line('success');
	    	}else{ 
	    		$msg_detail = $this->lang->line('fail');
	    	}
	    	$links[1] = array('href' => '?c=index&m=rank_list', 'text' => '查看岗位列表');
	    	$links[2] = array('href' => '?c=group&m=group_list', 'text' => '查看组列表');
	    	$this->common->msg($msg_detail, 0, $links, true, true,3);
	    	
	    }else{
	    	$data=array();
	    	$data=$this->common->config('set_group');
	    	$data['top'] = $this->load->view('top', $data, true); 
	    	$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
	    	$data['sider_menu'] = $this->load->view('sider_menu', $data,true);
	    	$data['rank_id'] =$rank_id;
	    	$data['admin_group'] = 0;
	    	
	    	//通过岗位ID
	    	$sql="select admin_group from ".$this->common->table('admin')." where is_pass=1 AND rank_id=".$rank_id;
	    	$user=$this->common->getAll($sql);
	    	foreach($user as $user_temp){
	    		if($user_temp['admin_group'] > 0){ 
	    			$data['admin_group'] = $user_temp['admin_group'];break;;
	    		}
	    	} 
	    	$sql="select * from ".$this->common->table('user_groups')." where parent_id = 0   ORDER  BY CONVERT( name USING gbk )  COLLATE gbk_chinese_ci ASC";
	    	$data['parnt_list']=$this->common->getAll($sql);
	    	
	    	$sql="select * from ".$this->common->table('user_groups')." where parent_id > 0   ORDER  BY CONVERT( name USING gbk )  COLLATE gbk_chinese_ci ASC";
	    	$data['parnt_two_list']=$this->common->getAll($sql); 
	    	$this->load->view('group_person/group_person_set', $data);
	    }
    }
     
    //系统组添加
    public function add_group_person(){
		$name   =isset($_REQUEST['name'])?trim($_REQUEST['name']):''; 
		$parent_id   = $_REQUEST['parent_id'];
		if(empty($parent_id)){
			$parent_id = 0;
		}
		$parm=array(
			  'name'   =>$name,
			  'parent_id'   =>$parent_id,
			  'hos_id'   =>$_REQUEST['hos_id'],
			  'keshi_id'   =>$_REQUEST['keshi_id'],
			  'admin_id'   =>$_REQUEST['admin_id'],
			  'add_time' =>time()
		);  
        $id=$this->model->add_group($parm);
        if($id > 0){
        	$admin_id_str =$_REQUEST['admin_id_str']; 
        	if(!empty($admin_id_str)){
        		$admin_id_array = explode(',',$admin_id_str);
        		if(!empty($admin_id_array) && count($admin_id_array) > 0){
        			$group=array( 'admin_group'   =>$id); 
        			$this->db->where_in('admin_id', $admin_id_array);
        			$res=$this->db->update($this->common->table('admin'),$group); 
        		}
        	}
            $link[0]=array('href'=>'?c=group_person&m=group_person_list','text'=>'组列表');
            $this->common->msg('添加成功！',0,$link);
        }else{
            $this->common->msg('添加失败，返回！',1);
        }
    }
     
    /**根据医院和科室获取用户**/
    public function admin_list_ajax()
    {
    	$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
    	$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
    	if(empty($hos_id)){exit();} 
    	if(empty($keshi_id)){
    		//$sql="select a.admin_id,a.admin_name from ".$this->common->table('rank_power')." as rp,".$this->common->table('admin')." as a,".$this->common->table('rank')." as r where rp.hos_id = ".$hos_id."  and rp.rank_id  = r.rank_id and r.rank_id = a.rank_id  and (a.admin_group =0 or a.admin_group = '' or a.admin_group is null)  and  a.is_pass = 1 ORDER  BY CONVERT( a.admin_username USING gbk )  COLLATE gbk_chinese_ci ASC ";
	        $sql="select rp.rank_id from ".$this->common->table('rank_power')." as rp where rp.hos_id = ".$hos_id;

    	}else{
    		//$sql="select a.admin_id,a.admin_name from ".$this->common->table('rank_power')." as rp,".$this->common->table('admin')." as a,".$this->common->table('rank')." as r where rp.hos_id = ".$hos_id." and rp.keshi_id = ".$keshi_id." and rp.rank_id  = r.rank_id and r.rank_id = a.rank_id  and (a.admin_group =0 or a.admin_group = '' or a.admin_group is null)  and  a.is_pass = 1    ORDER  BY CONVERT( a.admin_username USING gbk )  COLLATE gbk_chinese_ci ASC ";
			$sql="select rp.rank_id from ".$this->common->table('rank_power')." as rp  where rp.hos_id = ".$hos_id." and rp.keshi_id = ".$keshi_id;
    	} 
		$rank_list=$this->common->getAll($sql);
		$rank_str = '';
		foreach($rank_list as $rank_list_temp){
			if($rank_str == ''){
				$rank_str = $rank_list_temp['rank_id'];
			}else{
			    $rank_str .=  ','.$rank_list_temp['rank_id'];
			}
		}
		if(empty($rank_str)){
			echo "";
    	    exit;
		}
		
		$sql="select a.admin_id,a.admin_name from ".$this->common->table('admin')." as a where a.rank_id  in(".$rank_str.") and (a.admin_group =0 or a.admin_group = '' or a.admin_group is null)  and  a.is_pass = 1    ORDER  BY CONVERT( a.admin_username USING gbk )  COLLATE gbk_chinese_ci ASC "; 
    	$admin_list=$this->common->getAll($sql);
	 
    	$admin_list_temp = array();
    	foreach($admin_list as $admin_list_te){
    		$cc =0 ;
    		foreach($admin_list_temp as $admin_list_temp_tt){
    			if($admin_list_temp_tt['admin_id'] == $admin_list_te['admin_id']){
    				$cc =1;break;
    			}
    		}
    		if($cc == 0){
    			$admin_list_temp[] = $admin_list_te;
    		}
    	}
    	
    	if(empty($admin_list_temp)){exit();}
    	$json = array();
    	foreach($admin_list_temp as $val)
    	{
    		$json_temp = array();
    		$json_temp['id'] =$val['admin_id'];
    		$json_temp['Name'] = $val['admin_name']; 
    		$json[] =$json_temp;
    	}
    	//var myJson = [{ "id": "1", "Name": "刘德华"},{ "id": "2", "Name": "文章"},{"id":"3","Name":"孙红雷"},{ "id": "4", "Name": "葛优"}];
    	echo json_encode($json);
    	exit;
    }
     
    //显示系统组添加页面
    public function group_person_add(){
       $data=array(); 
       $data=$this->common->config('group_person_add');
       $data['top'] = $this->load->view('top', $data, true);
       $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
       $data['sider_menu'] = $this->load->view('sider_menu', $data,true); 
       $data['rightUL_admin'] = json_encode(array());
	   
       $where = 1;
       if(!empty($_COOKIE["l_hos_id"])) {
       	$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
       }
       $sql = "SELECT hos_id,hos_name FROM " . $this->common->table('hospital') . " WHERE $where  and ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc ";
       $data['hospital'] = $this->common->getAll($sql); 
       
       $sql = "SELECT admin_id,admin_name,admin_username FROM " . $this->common->table('admin') . " WHERE is_pass = 1 and admin_name != '' ORDER BY CONVERT(admin_name USING gbk) asc ";
       $data['admin'] = $this->common->getAll($sql);
       
       $this->load->view('group_person/group_person_add', $data);
    }
    //列表
    public function group_person_list(){
        $data=array();
        $data=$this->common->config('group_person_list');
		$where  = '1=1 ';
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
			//$where .=  " and keshi_id in(".$_COOKIE["l_keshi_id"].")";		
		} 
		 
		$parent_id =isset($_REQUEST['parent_id'])?trim($_REQUEST['parent_id']):'0';
		if(!empty($parent_id)){
			$where  .= ' and (  parent_id = '.$parent_id." or id = ".$parent_id.' ) ';
		} 
	  $group_list=$this->common->getAll("select * from ".$this->common->table('user_groups')."  where ".$where."   ORDER  BY CONVERT( name USING gbk )  COLLATE gbk_chinese_ci ASC");
	  $data['group_list'] =$group_list;
	  
	  $admin_id_str = 0;
	   foreach ($group_list as $group_list_ts){
	   	if(!empty($group_list_ts['admin_id'])){
	   		if(empty($admin_id_str)){
	   			$admin_id_str  = $group_list_ts['admin_id'];
	   		}else{
	   			$admin_id_str  =$admin_id_str.','.$group_list_ts['admin_id'];
	   		}
	   	} 
	   }
	   $admin_sql = "SELECT admin_id,admin_name,admin_username FROM " . $this->common->table('admin') . " WHERE admin_id in(".$admin_id_str.") ";
	   $data['admin_list']=$this->common->getAll($admin_sql);
	    
	   $data['top'] = $this->load->view('top', $data, true);
       $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
       $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
	    $data['hos_id'] = $hos_id;
		$data['keshi_id']=$keshi_id;
		$data['parent_id']=$parent_id;
		 
		//根据rank获取医院科室
		$where = 1;
		if(!empty($_COOKIE["l_hos_id"])) {
			$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
		} 
		 $sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE $where  and ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc ";
   		 $data['hospital'] = $this->common->getAll($sql);  
		if(!empty($hos_id)){
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE hos_id =".$hos_id." and  keshi_id  in(".$_COOKIE["l_keshi_id"].")  ORDER BY CONVERT(keshi_name USING gbk) asc ";
			}else{
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE hos_id =".$hos_id."  ORDER BY CONVERT(keshi_name USING gbk) asc ";
			}
			$data['keshi'] = $this->common->getAll($sql);

			//展览数据用
			$data['keshi_show']= $data['keshi'];
		}else{ 
			//展览数据用
		    if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE   keshi_id  in(".$_COOKIE["l_keshi_id"].")  ORDER BY CONVERT(keshi_name USING gbk) asc ";
			}else{
				$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " ORDER BY CONVERT(keshi_name USING gbk) asc ";
			} $data['keshi_show'] = $this->common->getAll($sql); 
		}
		 
       $this->load->view('group_person/group_person_list', $data);
    }
   
   //编辑系统组页面
    public function update_group_person(){
		$data=array(); 
        $data=$this->common->config('group_person_update');
   
        $id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        $sql="select * from ".$this->common->table('user_groups')." where  id=".$id;
        $data['group_list']=$this->common->getAll($sql);
        
        $sql = "SELECT admin_id,admin_name,admin_username FROM " . $this->common->table('admin') . " WHERE is_pass = 1 and admin_name != '' ORDER BY CONVERT(admin_name USING gbk) asc ";
        $data['admin'] = $this->common->getAll($sql);
         
		
        //根据rank获取医院科室
        $where = 1;
        if(!empty($_COOKIE["l_hos_id"])) {
        	$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
        }
        $sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE $where  and ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc ";
        $data['hospital'] = $this->common->getAll($sql);
        if(!empty($data['group_list'][0]['keshi_id'])){
        	$sql = "SELECT keshi_id,keshi_name,hos_id FROM " . $this->common->table('keshi') . " WHERE  keshi_id  in(".$data['group_list'][0]['keshi_id'].")   ";
        	$data['keshi'] = $this->common->getAll($sql); 
        }
        
	    $sql="select * from ".$this->common->table('user_groups')." where parent_id = 0   ORDER  BY CONVERT( name USING gbk )  COLLATE gbk_chinese_ci ASC";
        $data['parnt_list']=$this->common->getAll($sql);
 
        $sql="select  admin_id,admin_name from ".$this->common->table('admin')." where is_pass = 1 and admin_group = ".$id;
        $admin_list=$this->common->getAll($sql);
        $json = array();
        $html = '';
        foreach($admin_list as $val)
        {
        	$json_temp = array();
        	$json_temp['id'] =$val['admin_id'];
        	$json_temp['Name'] = $val['admin_name'];
        	$json[] =$json_temp;
        	
        	if($html == ''){
        		$html = "<li class='change_li' id='". $val['admin_id']. "'><a class='lia' href='javaScript:void(0);'>". $val['admin_name']."</a></li>";
        	}else{
        		$html = $html."<li class='change_li' id='". $val['admin_id']. "'><a class='lia' href='javaScript:void(0);'>". $val['admin_name']."</a></li>";
        	} 
        }
        //var myJson = [{ "id": "1", "Name": "刘德华"},{ "id": "2", "Name": "文章"},{"id":"3","Name":"孙红雷"},{ "id": "4", "Name": "葛优"}];
        $data['rightUL_admin'] = json_encode($json); 
        $data['rightUL_html'] = $html;  
        
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data,true);
        $this->load->view('group_person/group_person_update', $data);  
    }
    //更新系统消息
    public function group_person_update(){
    	$data=$this->common->config('group_person_update');
    	  
		$name =isset($_REQUEST['name'])?trim($_REQUEST['name']):'';
		$id  =isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
		if($id == 0){
			$this->common->msg('更新失败，返回！',1);
		}else{ 
			$parent_id   = $_REQUEST['parent_id'];
			if(empty($parent_id)){
				$parent_id = 0;
			}
		
			$sql="select hos_id,keshi_id from ".$this->common->table('user_groups')." where id = ".$id;
			$admin_list=$this->common->getAll($sql);
			if(count($admin_list)  == 0 ){
				$this->common->msg('更新失败，返回！',1);
			}
			
			$hos_id   = $_REQUEST['hos_id'];
			$keshi_id   = $_REQUEST['keshi_id']; 
			if(empty($hos_id)){
				$this->common->msg('更新失败,科室必须存在，返回！',1);
			}
			if(empty($keshi_id)){
				$keshi_id = 0;
			}
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
				if (!in_array($hos_id, $l_hos_id)){
					$this->common->msg('更新失败,医院权限缺少,返回！',1);
				}
				if(!empty($keshi_id)){
					$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
					if (!in_array($keshi_id, $l_keshi_id)){
						$this->common->msg('更新失败,科室权限缺少,返回！',1);
					} 
				} 
			}
			$admin_id = $_REQUEST['admin_id']; 
			$group=array( 'name'   =>$name,'admin_id'   =>$admin_id,'hos_id'   =>$hos_id,'keshi_id'   =>$keshi_id,'edit_time'   =>time(),'parent_id'=>$parent_id );
			$this->db->trans_start();
			$this->db->where('id', $id);
			$res=$this->db->update($this->common->table('user_groups'),$group);
			$this->db->trans_complete();
			if($res ){ 
				//清理旧数据
				$sql="select admin_id from ".$this->common->table('admin')." where admin_group =".$id;
				$user_groups_list=$this->common->getAll($sql);
				$user_groups_array = array();
				foreach($user_groups_list as $user_groups_list_val){
					$user_groups_array[] = $user_groups_list_val['admin_id'];
				}
				//添加新数据
				$admin_id_array = array();
				$admin_id_str =$_REQUEST['admin_id_str'];
				if(!empty($admin_id_str)){
					$admin_id_array = explode(',',$admin_id_str);
				}

				//寻找差异
				$del_array = array_diff($user_groups_array,$admin_id_array);
				if(count($del_array) > 0){
					$group=array( 'admin_group'   =>'0');
					$this->db->trans_start();
					$this->db->where_in('admin_id', $del_array);
					$res=$this->db->update($this->common->table('admin'),$group);
					$this->db->trans_complete();
				}
				//寻找差异
				$add_array = array_diff($admin_id_array,$user_groups_array);  
				if(count($add_array) > 0){
					$group=array( 'admin_group'   =>$id);
					$this->db->trans_start();
					$this->db->where_in('admin_id', $add_array);
					$res=$this->db->update($this->common->table('admin'),$group);
					$this->db->trans_complete();
				}
				
				$link[0]=array('href'=>'?c=group_person&m=group_person_list','text'=>'组列表');
				$this->common->msg('更新成功！',0,$link);
			}else{
				$this->common->msg('更新失败，返回！',1);
			}
		} 
    }

    public function del_group_person(){
        $group_id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
    
        $sql="select hos_id,keshi_id from ".$this->common->table('user_groups')." where id = ".$group_id;
        $admin_list=$this->common->getAll($sql);
        if(count($admin_list)  == 0 ){
        	$this->common->msg('删除失败，查询无记录，返回！',1);
        }
        if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
        	$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
        	if (!in_array($admin_list[0]['hos_id'], $l_hos_id)){
        		$this->common->msg('删除失败,医院权限缺少,返回！',1);
        	}
        	
        	$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
        	if (!in_array($admin_list[0]['keshi_id'], $l_keshi_id)){
        		$this->common->msg('删除失败,科室权限缺少,返回！',1);
        	}
        }
        
        $this->db->trans_start(); 
        $query="delete from ".$this->common->table('user_groups')." where id= ".$group_id;
		$bol = $this->db->query($query);
        $this->db->trans_complete();
        //查询所有下级
        $sql="select id from ".$this->common->table('user_groups')." where parent_id =".$group_id;
        $user_groups_list =$this->common->getAll($sql);
         
		$this->db->trans_start(); 
        $query="delete from ".$this->common->table('user_groups')." where parent_id= ".$group_id;
		$this->db->query($query);
        $this->db->trans_complete(); 
        if($bol){
        	//通过岗位ID
        	$admin_id_str= array();
        	$admin_id_str[] = $group_id; 
        	foreach($user_groups_list as $user_temp){
        		$admin_id_str[] =$user_temp['id'];
        	}
        	if(count($admin_id_str) > 0){
        		$group=array( 'admin_group'   => '');
        		$this->db->trans_start();
        		$this->db->where_in('admin_group', $admin_id_str);
        		$res=$this->db->update($this->common->table('admin'),$group);
        		$this->db->trans_complete();
        	}
        	
            $link[0]=array('href'=>'?c=group_person&m=group_person_list','text'=>'组列表');
            $this->common->msg('删除成功！',0,$link);
        }else{
             $this->common->msg('删除失败，返回！',1);
        }
    }
	 
	public function group_list_ajax(){
		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0; 
		if(empty($keshi_id)){exit();}
		   
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$l_keshi_id = explode(",",$_COOKIE["l_keshi_id"]);
			if (!in_array($keshi_id, $l_keshi_id)){
				exit();
			}
		} 
		
		$sql="select id,name from ".$this->common->table('user_groups')." where keshi_id =".$keshi_id." and parent_id = 0"; 
		$keshi_list=$this->common->getAll($sql); 
		if(empty($keshi_list)){exit();}
		$str = '<option value="0">请选择</option>';
		foreach($keshi_list as $val){
			$str .= '<option value="' . $val['id'] . '"'; 
			$str .= '>' . $val['name'] . '</option>';
		}
		echo $str;
	}
}