<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author Administrator
 */
class Test extends CI_Controller {
    //put your code here
    function __construct() {
        parent::__construct();
    }
    
    //批量推送用户
    public function send_user_to_ireport(){ 
    	
    	$send_array = array();
    	$sql = "SELECT ireport_admin_id,admin_name,admin_id,admin_username,admin_password FROM hui_admin";
    	$data = $this->common->getAll($sql); 
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/
    		$send_data = array();
    		$send_data['admin_id'] = $data_temp['admin_id'];
    		$send_data['ireport_admin_id'] =0;
    		$send_data['admin_name'] = $data_temp['admin_name'];
    		$send_data['admin_username'] =$data_temp['admin_username'];
    		if(empty($send_data['admin_name'])){
    			$send_data['admin_name'] =$send_data['admin_username'];
    		}
    		$send_data['admin_password'] =$data_temp['admin_password'];
    		$send_data['is_pass'] = 0;//0 可以登录 1不可以登录
    		$send_array[] =$send_data;
    	} 
    	$this->sycn_user_data_to_ireport($send_array);
    	
    }
    //批量推送科室
    public function send_keshi_to_ireport(){
    	$send_array = array();
    	$keshi_and_jb_data_array = array();
    	$sql = "SELECT keshi_id,keshi_name,hos_id,ireport_hos_id FROM hui_keshi ";
    	$data = $this->common->getAll($sql);
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/ 
    		$send_data = array();
    		$send_data['keshi_id'] = $data_temp['keshi_id'];
    		$send_data['ireport_hos_id'] = $data_temp['ireport_hos_id'];
    		$send_data['keshi_name'] = $data_temp['keshi_name'];
    		$hos_data = $this->common->getAll("SELECT hos_name,ask_auth FROM " . $this->common->table('hospital') . " WHERE hos_id = ".$data_temp['hos_id']);
    		if(count($hos_data) > 0 && $hos_data[0]['ask_auth'] == 0){
				$send_data['hos_name'] = $hos_data[0]['hos_name'];
				$send_array[] =$send_data;
			}
    	}
		if(count($send_array)> 0){
			$this->sycn_keshi_data_to_ireport($send_array);
 
			//如果科室推送成功 则继续推送科室选择的疾病 
			$keshi_and_jb_data_array = array();
			$sql = "SELECT keshi_id,keshi_name,hos_id,ireport_hos_id FROM hui_keshi";
			$data = $this->common->getAll($sql);
			foreach ($data as $data_temp){
				$keshi_and_jb_data = array();
				$keshi_and_jb_data['hospital_id'] = $data_temp['ireport_hos_id'];
				$ireport_jb_id_str = '0';
				 
				$sql = "SELECT jb_id FROM hui_keshi_jibing where keshi_id =".$data_temp['keshi_id'];
				$jb_data = $this->common->getAll($sql);
				foreach($jb_data as $jb_data_t)
				{
					$ireport_jb_id = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$jb_data_t['jb_id']);
					if(!empty($ireport_jb_id)){
						if(empty($ireport_jb_id_str)){
							$ireport_jb_id_str = $ireport_jb_id;
						}else{
							$ireport_jb_id_str = $ireport_jb_id_str.','.$ireport_jb_id;
						}
					}
				}
				if(!empty($ireport_jb_id_str)){ 
					$keshi_and_jb_data['disease_id_str'] = $ireport_jb_id_str;
					$keshi_and_jb_data_array[] =$keshi_and_jb_data;
				}
			} 
			if(count($send_array)> 0){
				$this->sycn_keshi_and_jb_data_to_ireport($keshi_and_jb_data_array);
			}
			
		}
    	
    	
    }
    //批量推送病种
    public function send_jb_to_ireport(){
    	//先推第一遍
    	$send_array = array();
    	$sql = "SELECT jb_id,jb_name,jb_code,ireport_jb_id,parent_id FROM hui_jibing ";
    	$data = $this->common->getAll($sql);
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/ 
    		$send_data = array();
    		$send_data['jb_id'] = $data_temp['jb_id'];
    		$send_data['ireport_jb_id'] = 0; 
    		$send_data['parent_id'] = 0;
    		$send_data['jb_name'] = $data_temp['jb_name'];
    		$send_data['jb_code'] = $data_temp['jb_code']; 
    		$send_array[] = $send_data;
    	}
    	$this->sycn_jb_data_to_ireport($send_array); 
    	 
    	//等待第一遍 在数据中心添加完成  然后才能将有用上下级结构的 id更新过去
    	$send_array = array();
    	$sql = "SELECT jb_id,jb_name,jb_code,ireport_jb_id,parent_id FROM hui_jibing ";
    	$data = $this->common->getAll($sql);  
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/
    		$send_data = array();
    		$send_data['jb_id'] = $data_temp['jb_id'];
    		$send_data['ireport_jb_id'] = $data_temp['ireport_jb_id'];
    		if(empty($data_temp['parent_id'])){
    			$data_temp['parent_id'] =0 ;
    		}
    		$ireport_jb = $this->common->getOne("SELECT ireport_jb_id FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$data_temp['parent_id']);
    		$send_data['parent_id'] = 0;
    		if(!empty($ireport_jb)){
    			$send_data['parent_id'] = $ireport_jb;
    		}
    		$send_data['jb_name'] = $data_temp['jb_name'];
    		$send_data['jb_code'] = $data_temp['jb_code'];
    		$send_array[] = $send_data;
    	} 
    	$this->sycn_jb_data_to_ireport($send_array);
    	
    	
    }
    //批量推送途径
    public function send_order_form_to_ireport(){
    	$send_array = array();
    	$sql = "SELECT from_id,from_name,hos_id,keshi_id,is_show FROM hui_order_from ";
    	$data = $this->common->getAll($sql); 
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/ 
    		$send_data = array();
    		$send_data['from_id'] = $data_temp['from_id'];
    		$send_data['ireport_order_from_id'] = 0;
    		$send_data['from_name'] = $data_temp['from_name']; 
    		$send_data['hos_id'] = '0';
    		$send_data['keshi_id'] = '0';
    		if(!empty($data_temp['keshi_id'])){
    			$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$data_temp['keshi_id']);
    			if($ireport_hos_id > 0){
    				$send_data['keshi_id'] = $ireport_hos_id;
    			}
    		}
    		$send_data['is_show'] = $data_temp['is_show'];
    		$send_data['parent_id'] = 0; 
    		$send_array[] =$send_data;
    	} 
    	$this->sycn_order_from_data_to_ireport($send_array);
    	 
    	$send_array = array();
    	$sql = "SELECT from_id,from_name,hos_id,keshi_id,is_show,ireport_order_from_id,parent_id FROM hui_order_from ";
    	$data = $this->common->getAll($sql);
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/ 
    		$send_data = array();
    		$send_data['from_id'] = $data_temp['from_id'];
    		$send_data['ireport_order_from_id'] = $data_temp['ireport_order_from_id'];
    		$send_data['from_name'] = $data_temp['from_name']; 
    		$send_data['hos_id'] = '0';
    		$send_data['keshi_id'] = '0';
    		if(!empty($data_temp['keshi_id'])){
    			$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$data_temp['keshi_id']);
    			if($ireport_hos_id > 0){
    				$send_data['keshi_id'] = $ireport_hos_id;
    			}
    		}
    		$send_data['is_show'] = $data_temp['is_show'];
    		if(empty($data_temp['parent_id'])){
    			$data_temp['parent_id'] =0 ;
    		}
    		$ireport_id = $this->common->getOne("SELECT ireport_order_from_id FROM " . $this->common->table('order_from') . " WHERE from_id = ".$data_temp['parent_id']);
    		$send_data['parent_id'] = 0;
    		if($ireport_id > 0){
    			$send_data['parent_id'] = $ireport_id;
    			$send_array[] =$send_data;
    		}
    	} 
    	$this->sycn_order_from_data_to_ireport($send_array);
    }
    //批量推送性质
    public function send_order_type_to_ireport(){
    	$send_array = array();
    	$sql = "SELECT type_id,type_name,hos_id,keshi_id,type_desc,type_order FROM hui_order_type ";
    	$data = $this->common->getAll($sql);
    	foreach ($data as $data_temp){
    		/**
    		 * 推送数据到数据中心
    		 * **/ 
    		$send_data = array();
    		$send_data['type_id'] = $data_temp['type_id'];
    		$send_data['ireport_order_type_id'] = 0;
    		$send_data['type_name'] = $data_temp['type_name']; 
    		$send_data['hos_id'] = '0';
    		$send_data['keshi_id'] = '0';
    		if(!empty($data_temp['keshi_id'])){
    			$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$data_temp['keshi_id']);
    			if($ireport_hos_id > 0){
    				$send_data['keshi_id'] = $ireport_hos_id;
    			}
    		}
    		$send_data['type_desc'] = $data_temp['type_desc'];
    		$send_data['type_order'] = $data_temp['type_order'];
    		$send_data['parent_id'] = 0; 
    		$send_array[] =$send_data; 
    	}
    	$this->sycn_order_type_data_to_ireport($send_array); 
    }
   
    //批量推送预约数据
    public function send_irptorder_check_ireport(){ 
    	$where =  '  where admin_id >  0 and hos_id in(1,3,6,37) and keshi_id >0  and (ireport_order_id =0  or ireport_order_id  is null )  '; 
    	$page_size = 10000;
    	$page = 0 ;
    	$i =0 ;  
    	while(true){
    		$apisql = 'select order_id,ireport_order_id,admin_id,hos_id,keshi_id from '.$this->common->table('order').$where." order by order_id desc limit ".($page_size*$page).",".$page_size;
			$data = $this->common->getAll($apisql);
    		if(count($data) == 0){break;} 
    		$order_id_str = '';
    		$order_id_arr = array();
    		foreach ($data as $data_temp){
    			if(empty($data_temp['ireport_order_id']) && $data_temp['admin_id'] > 0 && $data_temp['hos_id'] > 0 && $data_temp['keshi_id'] > 0){
    				if(empty($order_id_str)){
    					$order_id_str =  $data_temp['order_id'];
    				}else{
    					$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    				}
    				$order_id_arr[] =$data_temp['order_id'];
    			}
    		}
    		
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select order_id from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);  
    			$order_id_str = '';
    			foreach ($order_id_arr as $order_id_arr_s){
    				$is=0;
    				foreach ($api_out_order_data as $api_out_order_data_s){
    					if($order_id_arr_s == $api_out_order_data_s['order_id']){
    						$is=1;break;
    					} 
    				}
    				if(empty($is)){
    					if(empty($order_id_str)){
    						$order_id_str =  $order_id_arr_s;
    					}else{
    						$order_id_str =  $order_id_str.','.$order_id_arr_s;
    					}
    				}
    			} 
    		}
    		if(!empty($order_id_str)){
    		     var_dump($order_id_str);
    		} 
    		$page++;
    	}
    	exit;
    }
    
	/**
	找出公海的已推送的订单id
	**/
	   public function ss(){
			$apisql = "select order_id,ireport_order_id from ".$this->common->table('gonghai_order')." where admin_id >  0 and keshi_id >0  and ireport_order_id > 0  ";
    		$data = $this->common->getAll($apisql);   
			$order_id_str = '';
    		foreach ($data as $data_temp){
				if(empty($order_id_str)){
					$order_id_str =  $data_temp['ireport_order_id'];
				}else{
					$order_id_str =  $order_id_str.','.$data_temp['ireport_order_id'];
				}
			}
			echo $order_id_str;exit;
	}
	
    /**
     * 检查输出 预约挂号系统 是否存在未推送的数据
     * **/
    public function check_order_to_ireport(){
    	$where =  '  where admin_id >  0 and keshi_id >0 and order_id >= 47895  and (ireport_order_id =0  or ireport_order_id  is null )';//  where order_id > 444608 ';// where admin_id = 8  ';
        $page_size = 100000;
    	$page = 0 ;
    	$i =0 ;
    	$all_cout =0 ;
    	$imgAdd = 'src="/static/js/ueditor/upload/';
    	while(true){
    		$wait_send_order  ='';
    		
    		$apisql = 'select admin_id,order_id,ireport_order_id,keshi_id from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql); 
	 
    		$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_check_sql.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    
    		if(count($data) == 0){break;}
    		$order_id_str = '';
    		$keshi_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($data_temp['ireport_order_id'])){
    				if(!empty($data_temp['order_id'])){
    					if(empty($order_id_str)){
    						$order_id_str =  $data_temp['order_id'];
    					}else{
    						$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    					}
    				}
    				if(!empty($data_temp['keshi_id'])){
    					if(empty($keshi_id_str)){
    						$keshi_id_str =  $data_temp['keshi_id'];
    					}else{
    						$keshi_id_str =  $keshi_id_str.','.$data_temp['keshi_id'];
    					}
    				}
    				if(!empty($data_temp['admin_id'])){
    					if(empty($admin_id_str)){
    						$admin_id_str  =  $data_temp['admin_id'];
    					}else{
    						$admin_id_str  =  $admin_id_str.','.$data_temp['admin_id'];
    					}
    				}
    			}
    		}
    		if(empty($order_id_str)){
    			$order_id_str =0 ;
    		}
    		if(empty($keshi_id_str)){
    			$keshi_id_str =0 ;
    		}
    		if(empty($admin_id_str)){
    			$admin_id_str =0 ;
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select order_id from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			//获取科室
    			$api_hospital_data =array();
    			if(!empty($keshi_id_str)){
    				$apisql = 'select ireport_hos_id,keshi_id from '.$this->common->table('keshi').' where keshi_id in('.$keshi_id_str.')';
    				$api_hospital_data = $this->common->getAll($apisql);
    			}
    			//获取用户
    			$api_admin_data = array();
    			if(!empty($admin_id_str)){
    				$apisql = 'select ireport_admin_id,admin_id,admin_name from '.$this->common->table('admin').' where admin_id in('.$admin_id_str.')';
    				$api_admin_data = $this->common->getAll($apisql);
    			}
    			//推送添加
    			$send_array = array();
				
    			foreach ($data as $data_temp){
    				if($data_temp['admin_id'] > 0 ){
    					$out_check =0 ;
    					foreach ($api_out_order_data as $out_order_data_temp){
    						if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    							$out_check =1;break;
    						}
    					}
    					if(empty($out_check)){
    						$order_id = $data_temp['order_id'];
    						/**
    						 * 推送数据到数据中心
    						 * **/
    						$send_data = array();
    						$send_data['id_hospital'] = 0;
    						foreach ($api_hospital_data as $api_hospital_data_temp){
    							if(strcmp($api_hospital_data_temp['keshi_id'],$data_temp['keshi_id']) == 0){
    								$send_data['id_hospital']  =$api_hospital_data_temp['ireport_hos_id'];break;
    							}
    						}
    						$ireport_admin_id =0 ;
    						$admin_name = '' ;
    						foreach ($api_admin_data as $api_admin_data_temp){
    							if(strcmp($api_admin_data_temp['admin_id'],$data_temp['admin_id']) == 0){
    								$ireport_admin_id =$api_admin_data_temp['ireport_admin_id'];
    								$admin_name = $api_admin_data_temp['admin_name'];
    								break;
    							}
    						}
    						if($ireport_admin_id > 0 && $send_data['id_hospital'] > 0){
								echo $order_id.','; 
    						}
    					}
    				}
    			}
    		} 
    		$page++;
    	}
    	exit;
    }
	
	//通过预约表查询重复的数据
    public function send_order_extis_ireport(){ 
    	$where =  '  where admin_id >  0 and keshi_id >0  ';//  where order_id > 444608 ';// where admin_id = 8  ';
		//$where =  '  where ireport_order_id > 0'; 
    	$page_size = 1000;
    	$page = 0 ;
    	$i =0 ;
    	while(true){
    		$apisql = 'select order_id,ireport_order_id,keshi_id,pat_id from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
			$data = $this->common->getAll($apisql); 
			$myfile = fopen("D://text/yuyue_order_exits.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
			
    		if(count($data) == 0){break;}  
			 
			$exits_one_id  = '';
			$exits_more_id  = '';
    		foreach ($data as $data_temp){
				$apisql = 'select id from '.$this->common->table('irpt_patient')." where id in(".$data_temp['ireport_order_id'].")"; 
				$api_irpt_patient_data = $this->common->getAll($apisql);  
				if(count($api_irpt_patient_data) == 0 || count($api_irpt_patient_data) > 1){
					//var_dump('sql:'.$apisql); echo  '<br/>';
					//var_dump('数量:'.count($api_irpt_patient_data ));echo  '<br/>';
				}
				if(count($api_irpt_patient_data) ==0 ){
					 if(empty($exits_one_id )){
						 $exits_one_id  = $data_temp['order_id'];
					 }else{
						 $exits_one_id  = $exits_one_id.','.$data_temp['order_id'];
					 }
				} else if(count($api_irpt_patient_data) > 1){
					 if(empty($exits_more_id )){
						 $exits_more_id  = $data_temp['id'];
					 }else{
						 $exits_more_id  = $exits_more_id.','.$data_temp['id'];
					 }
				} 
    		} 
			if(!empty($exits_one_id)){
				echo '<br/>'.'推送失败的数据'.$exits_one_id.'<br/>';
			}
			if(!empty($exits_more_id)){
				echo '<br/>'.'存在多条的数据'.$exits_more_id.'<br/>'; 
			} 
			
			echo '<br/>'.'当前页'.$page.'<br/>'; 
    		$page++;
    	}
    	exit;
    }
 
	//通过检查数据中心表 来判断是否推送了多条
    public function send_order_from_extis_ireport(){ 
    	$where =  '  '; 
    	$page_size = 1000;
    	$page = 0 ;
    	$i =0 ;
    	while(true){
			$exits_one_id  = ''; 
			$exits_more_id  = '';
    		$apisql = 'select id from '.$this->common->table('irpt_patient').$where." order by id asc limit ".($page_size*$page).",".$page_size;
			$data = $this->common->getAll($apisql);
			
			$myfile = fopen("D://text/yuyue_order_from_exits.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['id'].'\n');
    		}
    		fclose($myfile);
			
    		if(count($data) == 0){break;}  
			
    		foreach ($data as $data_temp){
				$apisql = 'select order_id from '.$this->common->table('order')." where ireport_order_id in(".$data_temp['id'].")";
				$api_order_data = $this->common->getAll($apisql);  
				if(count($api_order_data ) == 0 || count($api_order_data ) > 1){
					var_dump('sql:'.$apisql); echo  '<br/>';
					var_dump('数量:'.count($api_order_data ));echo  '<br/>';
				} 
				if(count($api_order_data) ==0 ){
					 if(empty($exits_one_id )){
						 $exits_one_id  = $data_temp['id'];
					 }else{
						 $exits_one_id  = $exits_one_id.','.$data_temp['id'];
					 }
				}  else if(count($api_order_data) > 1){
					 if(empty($exits_more_id )){
						 $exits_more_id  = $data_temp['id'];
					 }else{
						 $exits_more_id  = $exits_more_id.','.$data_temp['id'];
					 }
				} 
    		}
			if(!empty($exits_one_id)){
				echo '<br/>'.'推送失败的数据'.$exits_one_id.'<br/>';
			}
			if(!empty($exits_more_id)){
				echo '<br/>'.'存在多条的数据'.$exits_more_id.'<br/>'; 
			} 
			echo '<br/>'.'当前页'.$page.'<br/>'; 
    		$page++;
    	}
    	exit;
    }
	
    //批量推送预约数据
    public function send_order_to_ireport(){ 
		
    	$where =  '  where admin_id >  0 and keshi_id >0   and (ireport_order_id =0  or ireport_order_id  is null )';//  where order_id > 444608 ';// where admin_id = 8  ';
    	
    	$page_size = 1000;
    	$page = 0 ;
    	$i =0 ;
    	$all_cout =0 ; 
		$imgAdd = 'src="/static/js/ueditor/upload/';
    	while(true){
    		$send_array =array();
    		
    		$apisql = 'select * from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
		    $data = $this->common->getAll($apisql);   
			$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);  
			 
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		$pat_id_str = '';
    		$keshi_id_str = '';
    		$jb_id_str = '';
    		$type_id_str = '';
    		$from_id_str = '';
    		$admin_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($data_temp['ireport_order_id'])){
    				if(!empty($data_temp['order_id'])){
    					if(empty($order_id_str)){
    						$order_id_str =  $data_temp['order_id'];
    					}else{
    						$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    					}
    				} 
    				if(!empty($data_temp['pat_id'])){
    					if(empty($pat_id_str)){
    						$pat_id_str =  $data_temp['pat_id'];
    					}else{
    						$pat_id_str =  $pat_id_str.','.$data_temp['pat_id'];
    					}
    				}  
    				if(!empty($data_temp['keshi_id'])){
    					if(empty($keshi_id_str)){
    						$keshi_id_str =  $data_temp['keshi_id'];
    					}else{
    						$keshi_id_str =  $keshi_id_str.','.$data_temp['keshi_id'];
    					}
    				}
    				if(empty($data_temp['jb_id'])){
    					$data_temp['jb_id'] =0 ;
    				}
    				if(empty($data_temp['jb_parent_id'])){
    					$data_temp['jb_parent_id'] =0 ;
    				}
    				if(!empty($data_temp['jb_id'])){
    					if(empty($jb_id_str)){
    						$jb_id_str  =  $data_temp['jb_id'];
    					}else{
    						$jb_id_str  =  $jb_id_str.','.$data_temp['jb_id'];
    					}
    				}
    				if(!empty($data_temp['jb_parent_id'])){
    					if(empty($jb_id_str)){
    						$jb_id_str  =  $data_temp['jb_parent_id'];
    					}else{
    						$jb_id_str  =  $jb_id_str.','.$data_temp['jb_parent_id'];
    					}
    				}
    					
    				if(empty($type_id_str)){
    					$type_id_str  =  $data_temp['type_id'];
    				}else{
    					$type_id_str  =  $type_id_str.','.$data_temp['type_id'];
    				}
    				if(empty($data_temp['from_parent_id'])){
    					$data_temp['from_parent_id'] =0 ;
    				}
    				if(empty($data_temp['from_id'])){
    					$data_temp['from_id'] =0 ;
    				}
    				if(!empty($data_temp['from_parent_id'])){
    					if(empty($from_id_str)){
    						$from_id_str  =  $data_temp['from_parent_id'];
    					}else{
    						$from_id_str  =  $from_id_str.','.$data_temp['from_parent_id'];
    					}
    				}
    				if(!empty($data_temp['from_id'])){
    					if(empty($from_id_str)){
    						$from_id_str  =  $data_temp['from_id'];
    					}else{
    						$from_id_str  =  $from_id_str.','.$data_temp['from_id'];
    					}
    				}
    				if(!empty($data_temp['admin_id'])){
    					if(empty($admin_id_str)){
    						$admin_id_str  =  $data_temp['admin_id'];
    					}else{
    						$admin_id_str  =  $admin_id_str.','.$data_temp['admin_id'];
    					}
    				}
    			}
    		}
    		if(empty($order_id_str)){
    			$order_id_str =0 ;
    		}
    		if(empty($pat_id_str)){
    			$pat_id_str =0 ;
    		}
    		if(empty($keshi_id_str)){
    			$keshi_id_str =0 ;
    		}
    		if(empty($jb_id_str)){
    			$jb_id_str =0 ;
    		}
    		if(empty($type_id_str)){
    			$type_id_str =0 ;
    		}
    		if(empty($from_id_str)){
    			$from_id_str =0 ;
    		}
    		if(empty($admin_id_str)){
    			$admin_id_str =0 ;
    		} 
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			//获取订单患者信息
    			$api_patient_data = array();
    			if(!empty($pat_id_str)){
    				$apisql = 'select * from '.$this->common->table('patient')." where pat_id in(".$pat_id_str.")";
    				$api_patient_data = $this->common->getAll($apisql);
    			}
    			//region  地域id
    			$reagion_id_str = '';
    			foreach ($api_patient_data as $api_patient_data_temp){
    				if(empty($api_patient_data_temp['pat_province'])){
    					$api_patient_data_temp['pat_province'] =0 ;
    				}
    				if(empty($api_patient_data_temp['pat_city'])){
    					$api_patient_data_temp['pat_city'] =0 ;
    				}
    				if(empty($api_patient_data_temp['pat_area'])){
    					$api_patient_data_temp['pat_area'] =0 ;
    				}
    				if(!empty($api_patient_data_temp['pat_province'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_province'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_province'];
    					}
    				}
    				if(!empty($api_patient_data_temp['pat_city'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_city'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_city'];
    					}
    				}
    				if(!empty($api_patient_data_temp['pat_area'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_area'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_area'];
    					}
    				}
    			}
    			if(empty($reagion_id_str)){
    				$reagion_id_str =0 ;
    			}
    			 
    			//获取备注
    			$apisql = 'select mark_content,admin_name,mark_type,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.')   order by mark_time desc ';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    			//获取回访记录
    			$apisql = 'select con_content,order_id from '.$this->common->table('ask_content').' where order_id in('.$order_id_str.')';
    			$api_ask_content_data  = $this->common->getAll($apisql);
    			 
    			//获取科室
    			$api_hospital_data =array();
    			if(!empty($keshi_id_str)){
    				$apisql = 'select ireport_hos_id,keshi_id from '.$this->common->table('keshi').' where keshi_id in('.$keshi_id_str.')';
    				$api_hospital_data = $this->common->getAll($apisql);
    			}
    			//获取疾病
    			$api_jibing_data =array();
    			if(!empty($jb_id_str)){
    				$apisql = 'select jb_name,jb_id from '.$this->common->table('jibing').' where jb_id in('.$jb_id_str.')';
    				$api_jibing_data  = $this->common->getAll($apisql);
    			}
    			//获取性质
    			$api_order_type_data = array();
    			if(!empty($type_id_str)){
    				$apisql = 'select ireport_order_type_id,type_id from '.$this->common->table('order_type').' where type_id in('.$type_id_str.')';
    				$api_order_type_data  = $this->common->getAll($apisql);
    			}
    			//获取预约途径
    			$api_order_from_data = array();
    			if(!empty($from_id_str)){
    				$apisql = 'select ireport_order_from_id,from_id from '.$this->common->table('order_from').' where from_id in('.$from_id_str.')';
    				$api_order_from_data  = $this->common->getAll($apisql);
    			}
    			//获取用户
    			$api_admin_data = array();
    			if(!empty($admin_id_str)){
    				$apisql = 'select ireport_admin_id,admin_id,admin_name from '.$this->common->table('admin').' where admin_id in('.$admin_id_str.')';
    				$api_admin_data = $this->common->getAll($apisql);
    			}
    			//查询本地地域表
    			$api_region_data = array();
    			if(!empty($reagion_id_str)){
    				$apisql = 'select region_id,region_name from '.$this->common->table('region').' where region_id in('.$reagion_id_str.')';
    				$api_region_data  = $this->common->getAll($apisql);
    			}
    			//推送添加
    			$send_array = array(); 
    			foreach ($data as $data_temp){
    				//var_dump('admin_id:'.$data_temp['admin_id']);echo '<br/>';
    				if($data_temp['admin_id'] > 0 ){
    					$out_check =0 ;
    					foreach ($api_out_order_data as $out_order_data_temp){
    						if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    							$out_check =1;break;
    						}
    					} 
    					 //var_dump('out_check:'.$out_check);echo '<br/>';
    					if(empty($out_check)){
    						$order_id = $data_temp['order_id'];
    						/**
    						 * 推送数据到数据中心
    						 * **/
    						$send_data = array();
    						$send_data['order_id'] = $order_id;
							if(empty($data_temp['is_first'])){
								$data_temp['is_first'] =1; 
							}else{
								$data_temp['is_first'] =0;
							}
    						$send_data['yuyue_type'] = $data_temp['is_first'];
    						//患者信息
    						$ireport_order_patient_data =array() ;
    						foreach ($api_patient_data as $patient_data_temp){
    							if(strcmp($patient_data_temp['pat_id'],$data_temp['pat_id']) == 0){
    								$ireport_order_patient_data[]=$patient_data_temp;break;
    							}
    						}//var_dump('ireport_order_patient_data:'.count($ireport_order_patient_data));echo '<br/>';
    						if(count($ireport_order_patient_data) > 0){
    							$send_data['ireport_order_id'] =$data_temp['ireport_order_id'];
    							$send_data['operation_type'] = 'edit';//修改类型
    							if(empty($send_data['ireport_order_id'])){
    								$send_data['operation_type'] = 'add';//添加类型
    								unset($send_data['ireport_order_id']);
    							}
    							$send_data['add_time'] = $data_temp['order_addtime'];
    							/**预约主体 相关字段**/
    							$send_data['order_no'] = $data_temp['order_no'];// 预约编号,
    							$send_data['id_hospital'] = 0;
    							foreach ($api_hospital_data as $api_hospital_data_temp){
    								if(strcmp($api_hospital_data_temp['keshi_id'],$data_temp['keshi_id']) == 0){
    									$send_data['id_hospital']  =$api_hospital_data_temp['ireport_hos_id'];break;
    								}
    							}
    							$ireport_admin_id =0 ;
    							$admin_name = '' ;
    							foreach ($api_admin_data as $api_admin_data_temp){
    								if(strcmp($api_admin_data_temp['admin_id'],$data_temp['admin_id']) == 0){
    									$ireport_admin_id =$api_admin_data_temp['ireport_admin_id'];
    									$admin_name = $api_admin_data_temp['admin_name'];
    									break;
    								}
    							} 
								//var_dump('keshi_id:'.$data_temp['keshi_id'].'admin_id:'.$data_temp['admin_id'].'id_hospital:'.$send_data['id_hospital'].'ireport_admin_id:'.$ireport_admin_id);
    							//echo '<br/>';
								if($ireport_admin_id > 0 && $send_data['id_hospital'] > 0){
    								$send_data['id_consult'] =  $ireport_admin_id;//预约人员id' ,
    								$send_data['consult_name'] = $admin_name;//预约人员名字' ,
    								 
    								$send_data['date_day'] = $data_temp['order_time'];//预约日期' ;
    								$disease= '';
    								foreach ($api_jibing_data as $api_jibing_data_temp){
    									if(strcmp($api_jibing_data_temp['jb_id'],$data_temp['jb_parent_id']) == 0){
    										$disease=$api_jibing_data_temp['jb_name'];break;
    									}
    								}
    								foreach ($api_jibing_data as $api_jibing_data_temp){
    									if(strcmp($api_jibing_data_temp['jb_id'],$data_temp['jb_id']) == 0){
    										if(empty($disease)){
    											$disease= $api_jibing_data_temp['jb_name'];break;
    										}else{
    											$disease= $disease.' '.$api_jibing_data_temp['jb_name'];break;
    										}
    									}
    								}
    								$send_data['disease'] = $disease;//预约病种 名称' ;
    								//过滤回访备注和 预约备注
    								$api_order_remark_str = '';
    								foreach($api_order_remark_data as $api_order_remark_data_t){
    									if(empty($api_order_remark_data_t['mark_type'])){
    										$api_order_remark_data_t['mark_type'] =0;
    									}
    									if(!empty($api_order_remark_data_t['mark_time'])){
    										if(strcmp($api_order_remark_data_t['order_id'],$order_id) == 0 &&  $api_order_remark_data_t['mark_type'] != 3){
    											if(empty($api_order_remark_str)){
    												$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}else{
    												$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}
    										}
    									}
    								}
    								$send_data['memo'] = $api_order_remark_str;//预约备注' ;
    								 
    								//过滤回访备注和 预约备注
    								$api_order_remark_str = '';
    								$api_order_remark_index =0 ;
    								$api_order_remark_datetime =0 ;
    								foreach($api_order_remark_data as $api_order_remark_data_t){
    									if(empty($api_order_remark_data_t['mark_type'])){
    										$api_order_remark_data_t['mark_type'] =0;
    									}
    									if(!empty($api_order_remark_data_t['mark_time'])){
    										if(strcmp($api_order_remark_data_t['order_id'],$order_id) == 0 && strcmp($api_order_remark_data_t['mark_type'],3) == 0 ){
    											if(empty($api_order_remark_str)){
    												$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}else{
    												$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}
    											$api_order_remark_index++;
    											$api_order_remark_datetime= $api_order_remark_data_t['mark_time'];
    										}
    									}
    									 
    								}
    								$send_data['visite_times'] = $api_order_remark_index;
    								$send_data['visite_bycheck_desc'] = $api_order_remark_str;//回访备注' ;
    								$send_data['visite_datetime'] = $api_order_remark_datetime;
    								$send_data['visite_bycheck'] =0;
    								$send_data['track_next_time'] =$data_temp['order_time'];
    								 
    								$send_data['yuyue_time_duan'] = $data_temp['order_time_duan'];//预约时段' ;
    								$send_data['yuyue_time_type'] = $data_temp['duan_confirm'];//预约时段类型' ;
    								 
    								//获取对话记录
    								$send_data['dialogue_record'] = ''; 
									
    								foreach($api_ask_content_data as $api_ask_content_data_temp){
    									if(strcmp($api_ask_content_data_temp['order_id'],$order_id) == 0){
    										//$send_data['dialogue_record'] =base64_encode($api_ask_content_data_temp['con_content']);break;
											$send_data['dialogue_record'] = $api_ask_content_data_temp['con_content'];  
											if(!empty($send_data['dialogue_record']) && strpos('1'.$send_data['dialogue_record'],$imgAdd )){
												//过滤图片地址
												$send_data['dialogue_record'] =str_replace('src="/static/js/ueditor/upload/','src="_data/',$send_data['dialogue_record']);
											}  
											break;											
    									}
    								}
									
									/**
									获取到诊状态
									**/
									$send_data['visited'] =  0;//是否到诊'
									$send_data['visite_date'] =  '';//到诊日期'
									$send_data['doctor'] = '';//医生名字
										
									if($data_temp['is_come'] == 1){
										$send_data['visited'] =  1;//是否到诊'
										$send_data['visite_date'] =  $data_temp['come_time'];//到诊日期'
										$send_data['doctor'] =  $data_temp['doctor_name'];//医生名字
									} 
									
    								//获取来源网址
    								$send_data['source_url'] = $laiyuanweb;//来源网址' ;
    								$send_data['last_menstrual_period'] = $data_time;//末次月经' ;
    								$send_data['patient_name'] = $ireport_order_patient_data[0]['pat_name'];//患者名字' ;
    								$send_data['age'] = $ireport_order_patient_data[0]['pat_age'];//年龄' ;
    								 
    								//预约性质
    								$send_data['order_type'] = '0';
    								foreach($api_order_type_data as $api_order_type_data_temp){
    									if(strcmp($api_order_type_data_temp['type_id'],$data_temp['type_id']) == 0){
    										$send_data['order_type'] =$api_order_type_data_temp['ireport_order_type_id'];break;
    									}
    								}
    								//预约途径
    								$send_data['order_from'] = '0';
    								$send_data['order_from_two'] = '0';
    								$send_data['from_value'] = $data_temp['from_value'];
    								foreach($api_order_from_data as $api_order_from_data_temp){
    									if(strcmp($api_order_from_data_temp['from_id'],$data_temp['from_parent_id']) == 0){
    										$send_data['order_from'] =$api_order_from_data_temp['ireport_order_from_id'];break;
    									}
    								}
    								foreach($api_order_from_data as $api_order_from_data_temp){
    									if(strcmp($api_order_from_data_temp['from_id'],$data_temp['from_id']) == 0){
    										$send_data['order_from_two'] =$api_order_from_data_temp['ireport_order_from_id'];break;
    									}
    								}
    								$area = '';
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_province']){
    										$area = $api_region_data_s['region_name'] ;break;
    									}
    								}
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_city']){
    										$area = $area.' '.$api_region_data_s['region_name'] ;break;
    									}
    								}
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_area']){
    										$area = $area.' '.$api_region_data_s['region_name'] ;break;
    									}
    								}
    								$send_data['area'] = $area.' '.$ireport_order_patient_data[0]['pat_address'];//地区' ;
    								$send_data['phone'] = $ireport_order_patient_data[0]['pat_phone'];//电话' ;
    								$send_data['email'] = '';//邮箱' ,
    								if($ireport_order_patient_data[0]['pat_sex'] == 2){
    									$send_data['sex'] = '女';//性别' ,
    								}else{
    									$send_data['sex'] = '男';//性别' ,
    								}
    								
    								$send_array[] = $send_data; 
								   
									if(empty($send_data['order_no'])){
										$send_data['order_no'] ='' ;
									} 
									if(empty($send_data['yuyue_type'])){
										$send_data['yuyue_type'] ='' ;
									} 
									if(empty($send_data['id_hospital'])){
										$send_data['id_hospital'] ='';
									} 
									if(empty($send_data['id_consult'])){
										$send_data['id_consult'] ='';
									}
									if(empty($send_data['consult_name'])){
										$send_data['consult_name'] ='';
									}
									if(empty($send_data['date_day'])){
										$send_data['date_day'] ='';
									}
									if(empty($send_data['disease'])){
										$send_data['disease'] ='';
									}
									if(empty($send_data['memo'])){
										$send_data['memo'] ='';
									}
									if(empty($send_data['add_time'])){
										$send_data['add_time'] ='';
									}
									if(empty($send_data['yuyue_time_duan'])){
										$send_data['yuyue_time_duan'] ='';
									}
									if(empty($send_data['yuyue_time_type'])){
										$send_data['yuyue_time_type'] ='';
									}
									if(empty($send_data['dialogue_record'])){
										$send_data['dialogue_record'] ='';
									}
									if(empty($send_data['source_url'])){
										$send_data['source_url'] ='';
									}
									if(empty($send_data['last_menstrual_period'])){
										$send_data['last_menstrual_period'] ='';
									}
									if(empty($send_data['patient_name'])){
										$send_data['patient_name'] ='';
									}
									if(empty($send_data['age'])){
										$send_data['age'] ='';
									}
									if(empty($send_data['area'])){
										$send_data['area'] ='';
									}
									if(empty($send_data['phone'])){
										$send_data['phone'] ='';
									}
									if(empty($send_data['email'])){
										$send_data['email'] ='';
									}
									if(empty($send_data['sex'])){
										$send_data['sex'] ='';
									}		 
									
									if(empty($send_data['visite_times'])){
										$send_data['visite_times'] ='' ;
									} 
									if(empty($send_data['visite_datetime'])){
										$send_data['visite_datetime'] ='';
									} 
									if(empty($send_data['visite_bycheck_desc'])){
										$send_data['visite_bycheck_desc'] ='';
									}
									if(empty($send_data['visite_bycheck'])){
										$send_data['visite_bycheck'] ='0';
									} 
									if(empty($send_data['track_next_time'])){
										$send_data['track_next_time'] ='';
									}
									if(empty($send_data['order_type'])){
										$send_data['order_type'] ='';
									}
									if(empty($send_data['order_from'])){
										$send_data['order_from'] ='';
									}
									if(empty($send_data['order_from_two'])){
										$send_data['order_from_two'] ='';
									}
									if(empty($send_data['from_value'])){
										$send_data['from_value'] ='';
									} 
					
									$info_temp = array();
									$info_temp['gonghai_status'] = 0;
									$info_temp['yuyue_type'] =  $send_data['yuyue_type'];
									$info_temp['order_no'] =  $send_data['order_no'];
									$info_temp['id_hospital'] = $send_data['id_hospital'];
									$info_temp['id_consult'] = $send_data['id_consult'];
									$info_temp['consult_name'] = $send_data['consult_name'];
									$info_temp['date_day'] = $send_data['date_day'];
									$info_temp['disease'] = $send_data['disease'];
									$info_temp['memo'] = $send_data['memo'];
									$info_temp['add_time'] = $send_data['add_time'];
									$info_temp['booking_period'] = '';
									$info_temp['dialogue_record'] = $send_data['dialogue_record'];
									$info_temp['source_url'] = $send_data['source_url'];
									$info_temp['last_menstrual_period'] = $send_data['last_menstrual_period'];
									$info_temp['patient_name'] = $send_data['patient_name'];
									$info_temp['age'] = $send_data['age'];
									$info_temp['area'] = $send_data['area'];
									$info_temp['phone'] = $send_data['phone'];
									$info_temp['email'] = $send_data['email'];
									$info_temp['sex'] = $send_data['sex'];
									$info_temp['visite_times']=$send_data['visite_times'];//回访次数
									$info_temp['visite_datetime']=$send_data['visite_datetime'];//最后回访时间
									$info_temp['visite_bycheck_desc']=$send_data['visite_bycheck_desc'];//回访备注
									$info_temp['visite_bycheck']= $send_data['visite_bycheck'];//通过咨询回访后确定到诊;0没回访过，1回访已经到诊，2回访到诊审核不通过
									$info_temp['track_next_time']=$send_data['track_next_time'];//下次回访时间
									$info_temp['order_type']=$send_data['order_type'];//预约性质
									$info_temp['order_from']=$send_data['order_from'];//预约途径
									$info_temp['order_from_two']=$send_data['order_from_two'];//预约途径2
									$info_temp['from_value']=$send_data['from_value'];//预约途径的值
								     
									$info_temp['visited'] =  $send_data['visited'];//是否到诊'
									$info_temp['visite_date'] =  $send_data['visite_date'];//到诊日期'
									$info_temp['doctor'] =  $send_data['doctor'];//医生名字
									$info_temp['memo'] =$send_data['memo'];//导医备注'  
									
									//var_dump(json_encode($info_temp));echo  '<br/>';
								   
									$i = $this->db->insert($this->common->table('irpt_patient'),$info_temp); 
								    if($i){
										$inesrt =  $this->db->insert_id();
										$update = array();
										$update['ireport_order_id'] =$inesrt;
										$update['ireport_msg'] = '绑定成功';
										$this->db->update($this->common->table('order'), $update, array('order_id' => $data_temp['order_id']));
									}
								}
    						}
    					}
    				}
    			}
    		}   
    		if(count($send_array)> 0){
    			//$this->sycn_order_data_to_ireport($send_array);
    		} 
    		$page++;
    	} 
    }
    
	 
    public function gettest(){
		 
    	$where =  '  where keshi_id = 28  and ireport_order_id > 0 and order_addtime between '.strtotime("2017-11-17 00:00:00").' and '.strtotime("2017-11-17 23:59:59");
		$apisql = 'select ireport_order_id from '.$this->common->table('order').$where." order by order_id asc";
    	$data = $this->common->getAll($apisql);   
		$order_id_str = '';
		foreach ($data as $data_temp){
			if(empty($order_id_str)){
				$order_id_str =  $data_temp['ireport_order_id'];
			}else{
				$order_id_str =  $order_id_str.','.$data_temp['ireport_order_id'];
			}
		}
		echo $order_id_str;exit;
	}
    
	 public function gettesttet(){ 
		
		    		  $id = '';
					   
			$t =array();
			$sql = 'select ireport_order_id from '.$this->common->table('order').' where ireport_order_id in('.$id.') ';  
			$data = $this->common->getAll($sql); 	
             //var_dump($data);exit;			
			$sql = 'select ireport_order_id from '.$this->common->table('gonghai_order').' where ireport_order_id in('.$id.') ';  
			$gognhai_data = $this->common->getAll($sql); 			
			
			if(count($data) > 0){
				$id_arr = explode(",",$id);   
				$count = array();
				foreach($data as $is){
					$count[] = $is['ireport_order_id'];
				}  
				$gonghai_count = array();
				foreach($gognhai_data as $is){
					$gonghai_count[] = $is['ireport_order_id'];
				} 
				 
				foreach($id_arr as $is){
					if(!in_array($is,$count)){
						if(!in_array($is,$gonghai_count)){
							$t[] = $is;
						} 
					} 
				} 
			}
			var_dump(implode(',',$t));exit;
	}
	 
 
	
	//批量推送预约数据  
    public function send_order_new_ireport(){
		
    	$where =  '  where admin_id >  0 and keshi_id >0  and (ireport_order_id =0 or ireport_order_id is null)';//  where order_id > 444608 ';// where admin_id = 8  ';
    	
		$page_size = 10000;
    	$page = 0 ;
    	$i =0 ;
    	$all_cout =0 ; 
		$imgAdd = 'src="/static/js/ueditor/upload/';
    	//while(true){
    		$send_array =array();
    		
    		$apisql = 'select * from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);  
			
			$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);  
			 
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		$pat_id_str = '';
    		$keshi_id_str = '';
    		$jb_id_str = '';
    		$type_id_str = '';
    		$from_id_str = '';
    		$admin_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($data_temp['ireport_order_id'])){
    				if(!empty($data_temp['order_id'])){
    					if(empty($order_id_str)){
    						$order_id_str =  $data_temp['order_id'];
    					}else{
    						$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    					}
    				} 
    				if(!empty($data_temp['pat_id'])){
    					if(empty($pat_id_str)){
    						$pat_id_str =  $data_temp['pat_id'];
    					}else{
    						$pat_id_str =  $pat_id_str.','.$data_temp['pat_id'];
    					}
    				}  
    				if(!empty($data_temp['keshi_id'])){
    					if(empty($keshi_id_str)){
    						$keshi_id_str =  $data_temp['keshi_id'];
    					}else{
    						$keshi_id_str =  $keshi_id_str.','.$data_temp['keshi_id'];
    					}
    				}
    				if(empty($data_temp['jb_id'])){
    					$data_temp['jb_id'] =0 ;
    				}
    				if(empty($data_temp['jb_parent_id'])){
    					$data_temp['jb_parent_id'] =0 ;
    				}
    				if(!empty($data_temp['jb_id'])){
    					if(empty($jb_id_str)){
    						$jb_id_str  =  $data_temp['jb_id'];
    					}else{
    						$jb_id_str  =  $jb_id_str.','.$data_temp['jb_id'];
    					}
    				}
    				if(!empty($data_temp['jb_parent_id'])){
    					if(empty($jb_id_str)){
    						$jb_id_str  =  $data_temp['jb_parent_id'];
    					}else{
    						$jb_id_str  =  $jb_id_str.','.$data_temp['jb_parent_id'];
    					}
    				}
    					
    				if(empty($type_id_str)){
    					$type_id_str  =  $data_temp['type_id'];
    				}else{
    					$type_id_str  =  $type_id_str.','.$data_temp['type_id'];
    				}
    				if(empty($data_temp['from_parent_id'])){
    					$data_temp['from_parent_id'] =0 ;
    				}
    				if(empty($data_temp['from_id'])){
    					$data_temp['from_id'] =0 ;
    				}
    				if(!empty($data_temp['from_parent_id'])){
    					if(empty($from_id_str)){
    						$from_id_str  =  $data_temp['from_parent_id'];
    					}else{
    						$from_id_str  =  $from_id_str.','.$data_temp['from_parent_id'];
    					}
    				}
    				if(!empty($data_temp['from_id'])){
    					if(empty($from_id_str)){
    						$from_id_str  =  $data_temp['from_id'];
    					}else{
    						$from_id_str  =  $from_id_str.','.$data_temp['from_id'];
    					}
    				}
    				if(!empty($data_temp['admin_id'])){
    					if(empty($admin_id_str)){
    						$admin_id_str  =  $data_temp['admin_id'];
    					}else{
    						$admin_id_str  =  $admin_id_str.','.$data_temp['admin_id'];
    					}
    				}
    			}
    		}
    		if(empty($order_id_str)){
    			$order_id_str =0 ;
    		}
    		if(empty($pat_id_str)){
    			$pat_id_str =0 ;
    		}
    		if(empty($keshi_id_str)){
    			$keshi_id_str =0 ;
    		}
    		if(empty($jb_id_str)){
    			$jb_id_str =0 ;
    		}
    		if(empty($type_id_str)){
    			$type_id_str =0 ;
    		}
    		if(empty($from_id_str)){
    			$from_id_str =0 ;
    		}
    		if(empty($admin_id_str)){
    			$admin_id_str =0 ;
    		} 
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			//获取订单患者信息
    			$api_patient_data = array();
    			if(!empty($pat_id_str)){
    				$apisql = 'select * from '.$this->common->table('patient')." where pat_id in(".$pat_id_str.")";
    				$api_patient_data = $this->common->getAll($apisql);
    			}
    			//region  地域id
    			$reagion_id_str = '';
    			foreach ($api_patient_data as $api_patient_data_temp){
    				if(empty($api_patient_data_temp['pat_province'])){
    					$api_patient_data_temp['pat_province'] =0 ;
    				}
    				if(empty($api_patient_data_temp['pat_city'])){
    					$api_patient_data_temp['pat_city'] =0 ;
    				}
    				if(empty($api_patient_data_temp['pat_area'])){
    					$api_patient_data_temp['pat_area'] =0 ;
    				}
    				if(!empty($api_patient_data_temp['pat_province'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_province'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_province'];
    					}
    				}
    				if(!empty($api_patient_data_temp['pat_city'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_city'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_city'];
    					}
    				}
    				if(!empty($api_patient_data_temp['pat_area'])){
    					if(empty($reagion_id_str)){
    						$reagion_id_str  =  $api_patient_data_temp['pat_area'];
    					}else{
    						$reagion_id_str  =  $reagion_id_str.','.$api_patient_data_temp['pat_area'];
    					}
    				}
    			}
    			if(empty($reagion_id_str)){
    				$reagion_id_str =0 ;
    			}
    			 
    			//获取备注
    			$apisql = 'select mark_content,admin_name,mark_type,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.')   order by mark_time desc ';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    			//获取回访记录
    			$apisql = 'select con_content,order_id from '.$this->common->table('ask_content').' where order_id in('.$order_id_str.')';
    			$api_ask_content_data  = $this->common->getAll($apisql);
    			 
    			//获取科室
    			$api_hospital_data =array();
    			if(!empty($keshi_id_str)){
    				$apisql = 'select ireport_hos_id,keshi_id from '.$this->common->table('keshi').' where keshi_id in('.$keshi_id_str.')';
    				$api_hospital_data = $this->common->getAll($apisql);
    			}
    			//获取疾病
    			$api_jibing_data =array();
    			if(!empty($jb_id_str)){
    				$apisql = 'select jb_name,jb_id from '.$this->common->table('jibing').' where jb_id in('.$jb_id_str.')';
    				$api_jibing_data  = $this->common->getAll($apisql);
    			}
    			//获取性质
    			$api_order_type_data = array();
    			if(!empty($type_id_str)){
    				$apisql = 'select ireport_order_type_id,type_id from '.$this->common->table('order_type').' where type_id in('.$type_id_str.')';
    				$api_order_type_data  = $this->common->getAll($apisql);
    			}
    			//获取预约途径
    			$api_order_from_data = array();
    			if(!empty($from_id_str)){
    				$apisql = 'select ireport_order_from_id,from_id from '.$this->common->table('order_from').' where from_id in('.$from_id_str.')';
    				$api_order_from_data  = $this->common->getAll($apisql);
    			}
    			//获取用户
    			$api_admin_data = array();
    			if(!empty($admin_id_str)){
    				$apisql = 'select ireport_admin_id,admin_id,admin_name from '.$this->common->table('admin').' where admin_id in('.$admin_id_str.')';
    				$api_admin_data = $this->common->getAll($apisql);
    			}
    			//查询本地地域表
    			$api_region_data = array();
    			if(!empty($reagion_id_str)){
    				$apisql = 'select region_id,region_name from '.$this->common->table('region').' where region_id in('.$reagion_id_str.')';
    				$api_region_data  = $this->common->getAll($apisql);
    			}
    			//推送添加
    			$send_array = array(); 
    			foreach ($data as $data_temp){
    				//var_dump('admin_id:'.$data_temp['admin_id']);echo '<br/>';
    				if($data_temp['admin_id'] > 0 ){
    					$out_check =0 ;
    					foreach ($api_out_order_data as $out_order_data_temp){
    						if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    							$out_check =1;break;
    						}
    					} 
    					 //var_dump('out_check:'.$out_check);echo '<br/>';
    					if(empty($out_check)){
    						$order_id = $data_temp['order_id'];
    						/**
    						 * 推送数据到数据中心
    						 * **/
    						$send_data = array();
							$send_data['order_id'] = $order_id;
							if(empty($data_temp['is_first'])){
								$data_temp['is_first'] =1; 
							}else{
								$data_temp['is_first'] =0;
							}
    						$send_data['yuyue_type'] = $data_temp['is_first'];
    						//患者信息
    						$ireport_order_patient_data =array() ;
    						foreach ($api_patient_data as $patient_data_temp){
    							if(strcmp($patient_data_temp['pat_id'],$data_temp['pat_id']) == 0){
    								$ireport_order_patient_data[]=$patient_data_temp;break;
    							}
    						}//var_dump('ireport_order_patient_data:'.count($ireport_order_patient_data));echo '<br/>';
    						if(count($ireport_order_patient_data) > 0){
    							$send_data['ireport_order_id'] =$data_temp['ireport_order_id'];
    							$send_data['operation_type'] = 'edit';//修改类型
    							if(empty($send_data['ireport_order_id'])){
    								$send_data['operation_type'] = 'add';//添加类型
    								unset($send_data['ireport_order_id']);
    							}
    							$send_data['add_time'] = $data_temp['order_addtime'];
    							/**预约主体 相关字段**/
    							$send_data['order_no'] = $data_temp['order_no'];// 预约编号,
    							$send_data['id_hospital'] = 0;
    							foreach ($api_hospital_data as $api_hospital_data_temp){
    								if(strcmp($api_hospital_data_temp['keshi_id'],$data_temp['keshi_id']) == 0){
    									$send_data['id_hospital']  =$api_hospital_data_temp['ireport_hos_id'];break;
    								}
    							}
    							$ireport_admin_id =0 ;
    							$admin_name = '' ;
    							foreach ($api_admin_data as $api_admin_data_temp){
    								if(strcmp($api_admin_data_temp['admin_id'],$data_temp['admin_id']) == 0){
    									$ireport_admin_id =$api_admin_data_temp['ireport_admin_id'];
    									$admin_name = $api_admin_data_temp['admin_name'];
    									break;
    								}
    							} 
								//var_dump('keshi_id:'.$data_temp['keshi_id'].'admin_id:'.$data_temp['admin_id'].'id_hospital:'.$send_data['id_hospital'].'ireport_admin_id:'.$ireport_admin_id);
    							//echo '<br/>';
								if($ireport_admin_id > 0 && $send_data['id_hospital'] > 0){
    								$send_data['id_consult'] =  $ireport_admin_id;//预约人员id' ,
    								$send_data['consult_name'] = $admin_name;//预约人员名字'  
									 
									/***
    								$send_data['date_day'] = $data_temp['order_time'];//预约日期' ;
    								$disease= '';
    								foreach ($api_jibing_data as $api_jibing_data_temp){
    									if(strcmp($api_jibing_data_temp['jb_id'],$data_temp['jb_parent_id']) == 0){
    										$disease=$api_jibing_data_temp['jb_name'];break;
    									}
    								}
    								foreach ($api_jibing_data as $api_jibing_data_temp){
    									if(strcmp($api_jibing_data_temp['jb_id'],$data_temp['jb_id']) == 0){
    										if(empty($disease)){
    											$disease= $api_jibing_data_temp['jb_name'];break;
    										}else{
    											$disease= $disease.' '.$api_jibing_data_temp['jb_name'];break;
    										}
    									}
    								}
    								$send_data['disease'] = $disease;//预约病种 名称' ;
    								//过滤回访备注和 预约备注
    								$api_order_remark_str = '';
    								foreach($api_order_remark_data as $api_order_remark_data_t){
    									if(empty($api_order_remark_data_t['mark_type'])){
    										$api_order_remark_data_t['mark_type'] =0;
    									}
    									if(!empty($api_order_remark_data_t['mark_time'])){
    										if(strcmp($api_order_remark_data_t['order_id'],$order_id) == 0 &&  $api_order_remark_data_t['mark_type'] != 3){
    											if(empty($api_order_remark_str)){
    												$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}else{
    												$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}
    										}
    									}
    								}
    								$send_data['memo'] = $api_order_remark_str;//预约备注' ;
    								 
    								//过滤回访备注和 预约备注
    								$api_order_remark_str = '';
    								$api_order_remark_index =0 ;
    								$api_order_remark_datetime =0 ;
    								foreach($api_order_remark_data as $api_order_remark_data_t){
    									if(empty($api_order_remark_data_t['mark_type'])){
    										$api_order_remark_data_t['mark_type'] =0;
    									}
    									if(!empty($api_order_remark_data_t['mark_time'])){
    										if(strcmp($api_order_remark_data_t['order_id'],$order_id) == 0 && strcmp($api_order_remark_data_t['mark_type'],3) == 0 ){
    											if(empty($api_order_remark_str)){
    												$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}else{
    												$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
    											}
    											$api_order_remark_index++;
    											$api_order_remark_datetime= $api_order_remark_data_t['mark_time'];
    										}
    									}
    									 
    								}
    								$send_data['visite_times'] = $api_order_remark_index;
    								$send_data['visite_bycheck_desc'] = $api_order_remark_str;//回访备注' ;
    								$send_data['visite_datetime'] = $api_order_remark_datetime;
    								$send_data['visite_bycheck'] =0;
    								$send_data['track_next_time'] =$data_temp['order_time'];
    								 
    								$send_data['yuyue_time_duan'] = $data_temp['order_time_duan'];//预约时段' ;
    								$send_data['yuyue_time_type'] = $data_temp['duan_confirm'];//预约时段类型' ;
    								 
    								//获取对话记录
    								$send_data['dialogue_record'] = ''; 
									
    								foreach($api_ask_content_data as $api_ask_content_data_temp){
    									if(strcmp($api_ask_content_data_temp['order_id'],$order_id) == 0){
    										//$send_data['dialogue_record'] =base64_encode($api_ask_content_data_temp['con_content']);break;
											$send_data['dialogue_record'] = $api_ask_content_data_temp['con_content'];  
											if(!empty($send_data['dialogue_record']) && strpos('1'.$send_data['dialogue_record'],$imgAdd )){
												//过滤图片地址
												$send_data['dialogue_record'] =str_replace('src="/static/js/ueditor/upload/','src="_data/',$send_data['dialogue_record']);
											}  
											break;											
    									}
    								}
									
									
									//获取到诊状态
									 
									$send_data['visited'] =  0;//是否到诊'
									$send_data['visite_date'] =  '';//到诊日期'
									$send_data['doctor'] = '';//医生名字
										
									if($data_temp['is_come'] == 1){
										$send_data['visited'] =  1;//是否到诊'
										$send_data['visite_date'] =  $data_temp['come_time'];//到诊日期'
										$send_data['doctor'] =  $data_temp['doctor_name'];//医生名字
									} 
									
    								//获取来源网址
    								$send_data['source_url'] = $laiyuanweb;//来源网址' ;
    								$send_data['last_menstrual_period'] = $data_time;//末次月经' ;
    								$send_data['patient_name'] = $ireport_order_patient_data[0]['pat_name'];//患者名字' ;
    								$send_data['age'] = $ireport_order_patient_data[0]['pat_age'];//年龄' ;
    								 
    								//预约性质
    								$send_data['order_type'] = '0';
    								foreach($api_order_type_data as $api_order_type_data_temp){
    									if(strcmp($api_order_type_data_temp['type_id'],$data_temp['type_id']) == 0){
    										$send_data['order_type'] =$api_order_type_data_temp['ireport_order_type_id'];break;
    									}
    								}
    								//预约途径
    								$send_data['order_from'] = '0';
    								$send_data['order_from_two'] = '0';
    								$send_data['from_value'] = $data_temp['from_value'];
    								foreach($api_order_from_data as $api_order_from_data_temp){
    									if(strcmp($api_order_from_data_temp['from_id'],$data_temp['from_parent_id']) == 0){
    										$send_data['order_from'] =$api_order_from_data_temp['ireport_order_from_id'];break;
    									}
    								}
    								foreach($api_order_from_data as $api_order_from_data_temp){
    									if(strcmp($api_order_from_data_temp['from_id'],$data_temp['from_id']) == 0){
    										$send_data['order_from_two'] =$api_order_from_data_temp['ireport_order_from_id'];break;
    									}
    								}
    								$area = '';
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_province']){
    										$area = $api_region_data_s['region_name'] ;break;
    									}
    								}
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_city']){
    										$area = $area.' '.$api_region_data_s['region_name'] ;break;
    									}
    								}
    								foreach ($api_region_data as $api_region_data_s){
    									if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_area']){
    										$area = $area.' '.$api_region_data_s['region_name'] ;break;
    									}
    								}
    								$send_data['area'] = $area.' '.$ireport_order_patient_data[0]['pat_address'];//地区' ;
    								$send_data['phone'] = $ireport_order_patient_data[0]['pat_phone'];//电话' ;
    								$send_data['email'] = '';//邮箱' ,
    								if($ireport_order_patient_data[0]['pat_sex'] == 2){
    									$send_data['sex'] = '女';//性别' ,
    								}else{
    									$send_data['sex'] = '男';//性别' ,
    								} 
									**/
									
									$send_array[] = $send_data;
								}
    						}
    					}
    				}
    			}
    		}   
    		if(count($send_array)> 0){
				var_dump(json_encode($send_array));exit;
    			//$this->sycn_order_data_to_ireport($send_array);
    		} 
    		//$page++;
    	//} 
    }
	
    /**推送回访记录**/
    public function send_order_to_info_api_ireport(){
    	$where =  '  where ireport_order_id > 0 ';//  where order_id > 444608 ';// where admin_id = 8  ';
    	$page_size = 10;
    	$page = 0 ;
    	$i =0 ;
    	$all_cout =0 ;
    	while(true){
    		$send_array =array();
    
    		$apisql = 'select * from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    
    		$myfile = fopen("D://text/yuyue_order_info.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		foreach ($data as $data_temp){
    				if(empty($order_id_str)){
    					$order_id_str =  $data_temp['order_id'];
    				}else{
    					$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    				}
    		}
    		if(empty($order_id_str)){
    			$order_id_str =0 ;
    		}
    		if(!empty($order_id_str)){
    			//获取回访记录
    			$apisql = 'select con_content,order_id from '.$this->common->table('ask_content').' where order_id in('.$order_id_str.')';
    			$api_ask_content_data  = $this->common->getAll($apisql);
    
					//获取备注
    			$apisql = 'select mark_content,admin_name,mark_type,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.')   order by mark_time desc ';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    			
				

    			foreach ($data as $data_temp){
    				$order_id = $data_temp['order_id'];
    				$send_data = array();
    				$send_data['operation_type'] = 'editinfo';//必填
    				$send_data['order_id'] = $order_id;
    				$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
    
    				//获取对话记录
    				$send_data['dialogue_record'] = '';
    				foreach($api_ask_content_data as $api_ask_content_data_temp){
    					if(strcmp($api_ask_content_data_temp['order_id'],$order_id) == 0){
    						$send_data['dialogue_record'] =base64_encode($api_ask_content_data_temp['con_content']);break;
    					}
    				}
    				if(!empty($send_data['dialogue_record'])){ 
    					$send_array[] =$send_data;
    				}
    			}
    		}
    		if(count($send_array) > 0){
    			//var_dump($send_array);
    			$this->sycn_order_data_to_ireport($send_array);
    		}
    		$page++;
    	} 
    }
    
	/**推送备注**/
    public function send_order_to_remark_api_ireport(){
    	$where =  '  where ireport_order_id > 0 and order_id  >= 490478 ';//  where order_id > 444608 ';// where admin_id = 8  ';
    	$page_size = 10000;
    	$page = 0 ;
    	$i =0 ;
    	$all_cout =0 ;
    	while(true){
    		$send_array =array();
    
    		$apisql = 'select order_id,ireport_order_id from '.$this->common->table('order').$where." order by order_id asc limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    
    		$myfile = fopen("D://text/yuyue_order_info.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count(count($data)) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		foreach ($data as $data_temp){
    				if(empty($order_id_str)){
    					$order_id_str =  $data_temp['order_id'];
    				}else{
    					$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    				}
    		}
    		if(empty($order_id_str)){
    			$order_id_str =0 ;
    		}
    		if(!empty($order_id_str)){
				//获取备注
    			$apisql = 'select mark_content,admin_name,mark_type,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.')   order by mark_time desc ';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    			
    			foreach ($data as $data_temp){
    				$order_id = $data_temp['order_id'];
    				$send_data = array();
    				$send_data['operation_type'] = 'editinfo';//必填
    				$send_data['order_id'] = $order_id;
    				$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
    
					//过滤回访备注和 预约备注
					$api_order_remark_str = '';
					foreach($api_order_remark_data as $api_order_remark_data_t){
						if(empty($api_order_remark_data_t['mark_type'])){
							$api_order_remark_data_t['mark_type'] =0;
						}
						if(strcmp($api_order_remark_data_t['order_id'],$data_temp['order_id']) == 0 &&  $api_order_remark_data_t['mark_type'] != 3){
							if(empty($api_order_remark_str)){
								$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
							}else{
								$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
							}
						}
					}
					$send_data['memo']= '';
					if(!empty($api_order_remark_str)){
						$send_data['memo'] = $api_order_remark_str;//预约备注' ; 	 
						$info_temp = array();
						$info_temp['memo'] = $send_data['memo'];
						$this->db->update($this->common->table('irpt_patient'),$info_temp,array('id' => $data_temp['ireport_order_id']));  		
					}
					
    				//if(!empty($send_data['dialogue_record'])){ 
    					//$send_array[] =$send_data;
    				//}
    			}
    		}
    		if(count($send_array) > 0){
    			//var_dump($send_array);
    			//$this->sycn_order_data_to_ireport($send_array);
    		}
    		$page++;
    	} 
    }
    
	
    //回访
    public function send_order_hf_to_ireport(){
    	$where = ' where  ireport_order_id > 0 and order_id >= 470462 ';// where admin_id = 8  '; 
    	$page_size = 10000;
    	$page = 0 ;
    	$all_cout=0 ;
	
    	while(true){
    		//推送回访
    		$send_array = array();
    		$iss =0 ;
    		$apisql = 'select order_id,ireport_order_id,order_time from '.$this->common->table('order').$where."  order by order_id asc limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    		
    		$myfile = fopen("D://text/yuyue_order_sql_hf.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count($data) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		} 
    		fclose($myfile);
    		
    		if(count($data) == 0){
    			break;
    		} 

    		$order_id_str = ''; 
    		foreach ($data as $data_temp){
    			if(empty($order_id_str)){
					$order_id_str =  $data_temp['order_id'];
				}else{
					$order_id_str =  $order_id_str.','.$data_temp['order_id'];
				}
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			//获取备注
    			$apisql = 'select mark_content,mark_type,admin_name,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.') and mark_type = 3  order by mark_time desc';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    			
    			foreach ($data as $data_temp){
    				$out_check =0 ;
					foreach ($api_out_order_data as $out_order_data_temp){
						if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
							$out_check =1;break;
						}
					}
					if(empty($out_check)){
						$order_id = $data_temp['order_id'];
						$send_data = array();
						$send_data['operation_type'] = 'huifang';//必填
						$send_data['order_id'] = $order_id;
						$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
			
						//过滤回访备注和 预约备注
						$api_order_remark_str = '';
						$api_order_index =0;
						$api_visite_datetime =0;
						foreach ($api_order_remark_data as $api_order_remark_data_temp){
							if(strcmp($api_order_remark_data_temp['order_id'],$data_temp['order_id']) == 0  ){
								if(empty($api_order_remark_str)){
									$api_order_remark_str = $api_order_remark_data_temp['mark_content']."[".$api_order_remark_data_temp['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_temp['mark_time'])."]";
								}else{
									$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_temp['mark_content']."[".$api_order_remark_data_temp['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_temp['mark_time'])."]";
								}
								$api_order_index++;
								$api_visite_datetime =$api_order_remark_data_temp['mark_time'];
							}
						} 
						$send_data['visite_times'] = $api_order_index;
						$send_data['visite_bycheck_desc'] = $api_order_remark_str;//回访备注' ;
						$send_data['visite_datetime'] = $api_visite_datetime;
						$send_data['visite_bycheck'] =0;
						$send_data['track_next_time'] =$data_temp['order_time'];
						//var_dump($send_data);exit;
						
						//$send_array[] =$send_data; 
						
						$info_temp = array();
						$info_temp['id'] =  $send_data['ireport_order_id'];
						$info_temp['visite_times']=$send_data['visite_times'];//回访次数
						$info_temp['visite_datetime']=$send_data['visite_datetime'];//最后回访时间
						$info_temp['visite_bycheck_desc']=$send_data['visite_bycheck_desc'];//回访备注
						$info_temp['visite_bycheck']= $send_data['visite_bycheck'];//通过咨询回访后确定到诊;0没回访过，1回访已经到诊，2回访到诊审核不通过
						$info_temp['track_next_time']=$send_data['track_next_time'];//下次回访时间
						$iss++;
						$this->db->update($this->db->dbprefix . "irpt_patient", $info_temp, array('id' => $send_data['ireport_order_id']));
						
					}
    			} 
    		}
			 var_dump('更新数据'.$iss);
    		//var_dump($send_array);exit;
    		if(count($send_array) > 0){
    			//$this->sycn_order_data_to_ireport($send_array);
    		} 
			echo '<br/>'.'当前页'.$page.'<br/>'; 
    		$page++;
    	}  
    	
    }
    //来院
    public function send_order_ly_to_ireport(){

    	$where = ' where ireport_order_id > 0  ';//s where admin_id = 8  ';
		
		$where  = ' where order_id in(504794,504793,504656,381596,381586,381576,381563,381476,381329,381099,381057,381053,381026,381013,380993,380763,380697,380577,380507,380341,380337,380333,380315,380275,380172,380123,380087,380083,380050,380032,380022,380009,379915,379906,379693,379579,379567,379497,379483,379403,379402,379224,379200,379154,378893,378842,378774,378668,378465,378462,378383,378193,378183,378082,377868,377658,377615,377358,377136,377007,376725,376584,376564,376458,376401,376327,376230,376143,376130,376056,376007,375923,375904,375856,375818,375804,375718,375717,375677,375626,375620,375460,375414,375343,375297,375288,375155,375099,375068,374866,374822,374807,374804,374740,374735,374591,374402,374369,374305,374288,374215,374043,374016,373971,373967,373942,373904,373838,373836,373742,373696,373644,373396,373386,373378,373249,373238,373199,373192,373163,373072,373069,372917,372868,372803,372802,372771,372721,372639,372602,372574,372570,372503,372479,372455,372233,372023,371878,371861,371783,371761,371635,371568,371560,371558,371384,371134,371125,371116,371104,371081,370924,370906,370889,370888,370748,370729,370617,370594,370543,370473,370416,370248,370239,370193,370126,370013,369989,369924,369872,369813,369772,369758,369756,369560,369559,369466,369310,369096,368937,368927,368887,368880,368843,368798,368739,368579,368350,368174,368089,368015,367989,367894,367814,367769,367653,367612,367601,367544,367474,367417,367396,367352,367297,367275,367229,367181,367156,367155,367037,366973,366902,366821,366807,366785,366710,366639,366613,366607,366414,366396,366316,366282,366075,366060,365951,365765,365642,365578,365577,365563,365494,365345,365335,365334,365324,365296,365268,365212,365109,365057,364996,364971,364648,364453,364255,364136,364046,363873,363842,363584,363563,363364,363225,363215,363072,363034,362949,362892,362874,362721,362711,362647,362454,362317,362100,362031,361986,361798,361772,361728,361704,361692,361689,361627,361386,361364,361277,361166,361080,361072,360797,360529,360270,360229,360004,359996,359992,359990,359966,359806,359668,359552,359534,359477,359450,359389,359190,358743,358581,358424,358383,358343,358289,358021,357995,357941,357813,357755,357632,357623,357593,357574,357527,357429,357331,357257,357214,357148,357111,357109,357106,357102,356999,356899,356865,356782,356621,356485,356464,356410,356295,356283,356239,356086,355804,355767,355688,355670,355669,355517,355243,355055,354878,354855,354852,354638,354571,354514,354488,354392,354335,353899,353842,353832,353651,353626,353525,353359,353276,353214,353180,353072,353044,353013,352741,352740,352695,352550,352520,352465,352390,352356,352352,352172,352167,352156,351987,351888,351620,351608,351601,351585,351518,351388,351385,351361,351239,351170,351067,351066,350921,350893,350838,350835,350736,350714,350686,350649,350584,350556,350493,350462,350447,350445,350418,350191,350155,350062,349982,349869,349812,349796,349789,349711,349578,349561,349557,349382,349304,349245,349196,349046,349030,348994,348894,348841,348840,348807,348622,348593,348542,348350,348279,348254,348133,348130,348101,347962,347887,347866,347788,347760,347712,347653,347621,347536,347502,347500,347451,347347,347261,347180,347160,347100,346856,346707,346671,346463,346312,346284,346227,346145,346130,346113,346100,346006,345901,345799,345761,345652,345641,345533,345499,345419,345274,345269,345243,345234,345155,345078,345022,345012,344995,344963,344875,344836,344737,344734,344686,344629,344613,344473,344446,344378,344350,344301,344047,343920,343809,343797,343758,343742,343741,343656,343646,343636,343629,343572,343403,343402,343313,343238,343142,343136,343061,343047,342858,342824,342775,342707,342680,342659,342486,342475,342397,342285,342264,342132,342039,342021,341983,341976,341941,341856,341848,341707,341630,341579,341529,341492,341440,341393,341385,341264,341241,341155,341065,341017,340996,340992,340946,340861,340803,340784,340694,340657,340598,340558,340537,340489,340470,340463,340419,340344,340301,340145,340112,340099,339852,339759,339757,339730,339704,339697,339682,339654,339408,339371,339369,339351,339331,339296,339183,339079,338980,338978,338958,338922,338913,338699,338681,338680,338627,338545,338533,338506,338465,338459,338403,338393,338368,338345,338329,338289,338269,338241,338221,338216,338199,338189,338187,338182,338180,338143,338120,338110,338107,338104,338084,338081,338053,338016,337981,337959,337946,337944,337940,337938,337902,337876,337865,337862,337850,337833,337809,337807,337792,337789,337786,337784,337738,337734,337723,337722,337713,337704,337695,337667,337664,337663,337653,337652,337640,337639,337638,337628,337625,337624,337615,337609,337604,337603,337584,337582,337567,337561,337560,337518,337516,337453,337446,337443,337420,337406,337398,337379,337370,337337,337332,337327,337326,337323,337321,337320,337316,337305,337285,337284,337283,337229,337203,337190,337170,337165,337121,337120,337104,337064,337063,337060,337050,337021,336984,336971,336957,336932,336922,336901,336897,336892,336881,336869,336852,336840,336830,336819,336808,336804,336796,336746,336745,336742,336685,336622,336598,336593,336577,336576,336552,336550,336529,336508,336503,336497,336490,336476,336466,336454,336448,336420,336365,336348,336329,336323,336310,336286,336261,336220,336218,336205,336201,336188,336179,336165,336160,336137,336136,336134,336119,336111,336092,336090,336081,336075,336073,336059,336057,336037,336026,335988,335982,335973,335912,335902,335888,335887,335886,335880,335871,335866,335861,335848,335845,335843,335836,335820,335819,335816,335808,335807,335806,335804,335800,335785,335783,335770,335763,335742,335738,335723,335686,335659,335639,335638,335637,335627,335612,335598,335579,335573,335566,335550,335547,335542,335509,335504,335500,335498,335442,335411,335367,335365,335349,335343,335341,335339,335321,335317,335299,335283,335276,335272,335265,335259,335252,335246,335245,335237,335232,335221,335182,335167,335159,335158,335143,335123,335119,335106,335105,335102,335090,335075,335060,335057,335046,335038,335017,335014,335010,335007,335006,335005,334982,334966,334959,334950,334938,334914,334904,334903,334878,334855,334850,334842,334838,334836,334824,334809,334799,334798,334792,334786,334766,334762,334760,334759,334716,334709,334693,334672,334670,334663,334633,334632,334627,334607,334598,334597,334584,334572,334568,334551,334550,334549,334548,334537,334534,334533,334511,334446,334437,334435,334414,334400,334396,334393,334389,334388,334381,334380,334366,334360,334356,334325,334319,334314,334310,334309,334306,334302,334285,334284,334282,334273,334223,334220,334206,334202,334187,334178,334167,334162,334145,334110,334107,334100,334098,334085,334022,333998,333983,333981,333978,333976,333956,333955,333938,333923,333916,333912,333911,333861,333857,333852,333815,333813,333800,333798,333793,333774,333773,333761,333754,333746,333711,333694,333693,333690,333662,333660,333655,333642,333627,333613,333605,333601,333593,333590,333580,333558,333553,333550,333548,333539,333537,333533,333532,333530,333526,333525,333523,333522,333521,333506,333496,333489,333483,333482,333462,333449,333446,333440,333432,333428,333409,333387,333373,333371,333364,333349,333340,333336,333322,333301,333292,333282,333271,333263,333260,333248,333240,333236,333235,333227,333226,333225,333224,333213,333212,333208,333203,333200,333194,333185,333177,333175,333173,333170,333167,333166,333165,333149,333140,333139,333133,333126,333124,333112,333109,333104,333102,333087,333081,333078,333076,333075,333072,333067,333064,333052,333048,333045,333042,333041,333039,333035,333033,333017,333012,333011,333007,333006,333005,332995,332994,332992,332986,332980,332976,332966,332965,332943,332942,332928,332925,332917,332908,332906,332894,332880,332878,332864,332859,332854,332853,332852,332851,332849,332844,332824,332819,332792,332780,332765,332722,332717,332712,332709,332687,332686,332685,332684,332683,332680,332674,332655,332651,332645,332625,332613,332599,332587,332586,332579,332576,332574,332558,332556,332555,332532,332520,332506,332482,332477,332468,332467,332456,332450,332448,332444,332436,332427,332392,332388,332361,332360,332355,332347,332336,332335,332334,332329,332322,332320,332318,332310,332286,332283,332282,332277,332251,332250,332249,332228,332203,332200,332197,332191,332185,332174,332162,332156,332147,332146,332124,332107,332105,332095,332081,332067,332052,332038,332037,332035,332023,332018,332007,332002,331994,331993,331992,331988,331983,331973,331971,331970,331966,331954,331953,331947,331940,331935,331923,331910,331909,331904,331892,331887,331882,331866,331865,331862,331855,331847,331840,331835,331833,331825,331819,331816,331812,331810,331804,331778,331776,331767,331761,331753,331738,331728,331727,331723,331718,331706,331700,331696,331690,331689,331686,331681,331677,331644,331641,331636,331631,331622,331605,331603,331602,331572,331563,331560,331557,331540,331538,331530,331529,331522,331498,331497,331493,331492,331490,331482,331476,331472,331471,331463,331449,331440,331435,331433,331429,331413,331407,331404,331398,331395,331390,331377,331372,331365,331359,331356,331345,331342,331324,331323,331320,331310,331309,331299,331298,331290,331288,331286,331285,331280,331277,331276,331275,331268,331265,331218,331208,331207,331206,331205,331203,331201,331195,331192,331184,331181,331169,331164,331158,331153,331151,331142,331135,331131,331128,331122,331115,331114,331098,331095,331093,331090,331069,331055,331054,331050,331047,331043,331034,331023,331018,331017,331016,331015,330993,330986,330980,330979,330978,330977,330973,330965,330952,330944,330940,330931,330927,330924,330923,330922,330919,330911,330909,330895,330894,330893,330891,330890,330888,330878,330870,330859,330858,330855,330850,330840,330838,330822,330812,330810,330803,330799,330792,330791,330788,330780,330779,330767,330763,330741,330732,330720,330717,330704,330699,330691,330687,330685,330678,330677,330646,330644,330641,330640,330616,330615,330614,330612,330611,330604,330594,330590,330575,330571,330568,330567,330552,330547,330540,330534,330532,330530,330529,330527,330521,330516,330504,330503,330501,330496,330483,330480,330475,330469,330457,330447,330406,330387,330384,330370,330358,330350,330347,330339,330333,330322,330320,330319,330317,330299,330287,330286,330283,330276,330267,330262,330238,330221,330218,330216,330214,330208,330195,330193,330191,330190,330187,330179,330174,330173,330171,330165,330159,330158,330146,330145,330141,330139,330136,330122,330114,330109,330107,330104,330102,330085,330082,330081,330078,330064,330062,330055,330053,330041,330039,330038,330036,330035,330021,330017,330012,330010,330007,329999,329998,329995,329992,329978,329973,329970,329964,329963,329960,329959,329957,329946,329942,329941,329920,329917,329916,329914,329908,329907,329869,329866,329833,329817,329806,329795,329791,329785,329783,329761,329760,329753,329752,329750,329747,329744,329741,329740,329739,329736,329733,329729,329721,329718,329717,329716,329715,329706,329699,329694,329671,329666,329642,329640,329635,329630,329618,329617,329616,329609,329605,329598,329597,329584,329580,329574,329568,329566,329554,329553,329552,329550,329549,329548,329544,329526,329524,329518,329508,329507,329503,329502,329478,329476,329462,329442,329440,329433,329423,329414,329398,329397,329393,329390,329389,329381,329379,329373,329366,329355,329351,329345,329337,329332,329331,329329,329317,329313,329303,329301,329290,329281,329269,329265,329264,329263,329260,329259,329255,329250,329242,329233,329230,329223,329219,329210,329206,329188,329186,329170,329168,329155,329148,329145,329136,329132,329129,329121,329120,329118,329117,329113,329106,329100,329099,329085,329083,329077,329070,329065,329060,329053,329036,329032,329016,329015,329014,329000,328996,328993,328990,328989,328979,328978,328977,328970,328963,328955,328954,328942,328934,328928,328919,328913,328912,328900,328898,328896,328887,328886,328880,328877,328876,328869,328842,328837,328834,328833,328830,328827,328825,328817,328816,328813,328807,328806,328803,328791,328789,328786,328785,328781,328778,328776,328774,328770,328767,328763,328762,328758,328755,328750,328733,328725,328714,328695,328684,328683,328680,328678,328675,328671,328665,328660,328656,328643,328640,328639,328614,328611,328610,328608,328603,328596,328593,328587,328581,328577,328566,328562,328561,328560,328552,328547,328525,328518,328517,328494,328490,328489,328473,328471,328466,328456,328453,328451,328431,328428,328421,328416,328412,328409,328396,328395,328381,328379,328374,328365,328355,328336,328328,328322,328321,328320,328315,328301,328278,328277,328272,328253,328248,328241,328240,328238,328231,328230,328227,328224,328220,328211,328198,328191,328178,328177,328172,328168,328162,328160,328149,328148,328138,328131,328126,328124,328123,328112,328111,328109,328098,328092,328090,328086,328081,328067,328065,328056,328044,328042,328028,328020,328010,328002,327983,327981,327980,327962,327949,327948,327947,327934,327933,327925,327924,327922,327918,327880,327876,327862,327846,327842,327832,327815,327811,327805,327800,327797,327792,327791,327790,327786,327782,327770,327761,327760,327751,327750,327746,327743,327733,327731,327726,327695,327691,327689,327688,327686,327683,327679,327673,327666,327659,327655,327653,327652,327640,327632,327628,327626,327621,327619,327606,327596,327592,327583,327578,327570,327565,327564,327563,327561,327556,327544,327543,327540,327530,327525,327518,327510,327509,327502,327500,327495,327494,327491,327482,327481,327474,327458,327450,327444,327440,327424,327421,327414,327411,327383,327367,327360,327337,327326,327325,327321,327320,327317,327316,327313,327308,327303,327299,327296,327289,327286,327285,327281,327262,327261,327255,327254,327249,327247,327238,327231,327223,327219,327198,327192,327188,327187,327183,327174,327170,327167,327157,327154,327152,327147,327138,327136,327122,327109,327107,327105,327102,327097,327089,327082,327077,327065,327062,327057,327031,327030,327028,327025,327012,327009,327008,327003,327000,326999,326993,326969,326967,326954,326952,326950,326942,326941,326931,326915,326912,326906,326901,326896,326883,326876,326865,326863,326845,326839,326836,326827,326825,326814,326813,326811,326808,326807,326801,326786,326778,326771,326768,326763,326753,326751,326738,326730,326729,326725,326724,326715,326696,326694,326691,326683,326671,326661,326659,326652,326647,326612,326597,326596,326585,326575,326567,326565,326558,326555,326554,326539,326534,326529,326524,326516,326515,326505,326504,326496,326495,326477,326474,326470,326468,326445,326422,326418,326413,326411,326408,326407,326404,326390,326383,326382,326374,326372,326368,326358,326356,326352,326343,326342,326341,326327,326325,326322,326317,326287,326284,326277,326272,326268,326265,326262,326260,326259,326256,326253,326235,326228,326221,326212,326211,326207,326199,326195,326192,326187,326180,326165,326163,326161,326149,326148,326144,326140,326139,326132,326127,326125,326113,326112,326108,326100,326097,326091,326085,326083,326067,326060,326055,326048,326042,326041,326034,326029,326026,326014,326012,326005,325992,325969,325962,325960,325956,325948,325946,325944,325941,325939,325935,325918,325917,325915,325908,325902,325900,325898,325897,325891,325883,325881,325877,325864,325857,325855,325853,325847,325842,325839,325835,325821,325814,325805,325804,325799,325785,325784,325755,325749,325745,325735,325701,325700,325691,325686,325684,325683,325679,325676,325670,325650,325649,325645,325612,325608,325604,325597,325579,325572,325570,325564,325563,325562,325554,325552,325539,325533,325531,325526,325517,325516,325508,325507,325506,325494,325483,325481,325480,325473,325471,325470,325456,325455,325454,325450,325448,325437,325435,325405,325397,325388,325383,325380,325379,325377,325376,325374,325373,325372,325363,325355,325354,325349,325346,325340,325324,325319,325318,325312,325310,325301,325300,325299,325298,325297,325291,325279,325277,325263,325256,325252,325250,325245,325242,325240,325238,325223,325216,325205,325200,325190,325187,325186,325184,325183,325179,325173,325172,325156,325146,325138,325132,325126,325125,325123,325116,325115,325098,325096,325081,325067,325059,325058,325049,325047,325044,325043,325041,325032,325031,324968,324966,324957,324941,324927,324916,324912,324911,324909,324908,324903,324896,324891,324884,324874,324869,324867,324864,324856,324853,324846,324844,324839,324831,324812,324801,324800,324786,324779,324778,324777,324774,324769,324764,324751,324747,324746,324743,324738,324737,324736,324734,324728,324721,324720,324715,324707,324684,324681,324648,324645,324638,324635,324627,324625,324613,324603,324597,324593,324587,324576,324575,324572,324562,324560,324538,324537,324535,324534,324524,324518,324513,324510,324509,324505,324503,324500,324497,324492,324487,324486,324485,324480,324474,324473,324468,324461,324460,324459,324452,324440,324435,324432,324426,324421,324418,324413,324409,324398,324390,324389,324367,324365,324348,324341,324339,324333,324332,324328,324323,324320,324316,324304,324298,324295,324289,324285,324284,324271,324248,324244,324243,324240,324237,324221,324218,324207,324202,324200,324196,324185,324184,324178,324150,324141,324138,324132,324126,324123,324122,324120,324113,324108,324094,324076,324075,324073,324069,324063,324050,324048,324045,324040,324038,324037,324034,324030,324028,324026,324023,324021,324020,323998,323994,323989,323986,323984,323979,323971,323961,323946,323945,323941,323940,323931,323928,323927,323919,323914,323910,323899,323898,323889,323888,323885,323877,323870,323868,323860,323857,323846,323844,323835,323833,323830,323827,323826,323821,323813,323811,323798,323783,323782,323777,323757,323746,323731,323727,323719,323714,323705,323702,323696,323687,323676,323675,323673,323668,323664,323662,323661,323649,323643,323635,323631,323604,323603,323598,323597,323592,323579,323569,323568,323562,323538,323537,323535,323532,323530,323526,323525,323514,323493,323488,323481,323477,323476,323472,323470,323437,323433,323430,323419,323399,323379,323376,323373,323370,323368,323363,323362,323361,323360,323359,323357,323350,323335,323331,323323,323318,323314,323312,323306,323299,323297,323289,323286,323279,323276,323273,323272,323264,323255,323254,323241,323238,323228,323215,323213,323196,323195,323184,323178,323173,323172,323168,323167,323166,323165,323159,323158,323147,323142,323132,323130,323126,323125,323120,323117,323089,323085,323084,323083,323081,323077,323076,323039,323031,323029,323028,323027,323018,323015,323007,322999,322998,322989,322988,322960,322954,322952,322939,322936,322934,322931,322929,322928,322927,322914,322903,322900,322898,322897,322872,322869,322864,322854,322851,322850,322849,322847,322846,322832,322828,322824,322817,322816,322813,322776,322774,322770,322768,322754,322747,322724,322722,322720,322719,322706,322702,322698,322692,322689,322687,322675,322665,322662,322658,322656,322654,322653,322652,322649,322635,322632,322624,322623,322621,322617,322613,322609,322593,322579,322562,322561,322556,322542,322532,322531,322518,322512,322500,322497,322496,322494,322485,322482,322480,322460,322459,322455,322444,322440,322432,322426,322420,322408,322398,322396,322392,322383,322380,322379,322373,322366,322353,322350,322346,322344,322341,322334,322329,322319,322317,322313,322312,322310,322307,322295,322290,322283,322279,322277,322276,322272,322266,322260,322258,322254,322244,322237,322231,322230,322228,322227,322221,322219,322216,322207,322206,322204,322200,322191,322190,322189,322173,322172,322166,322165,322155,322147,322138,322134,322132,322131,322130,322129,322128,322124,322118,322105,322101,322100,322093,322070,322068,322059,322049,322030,322029,322028,322024,322019,322013,322012,321999,321993,321991,321989,321984,321979,321971,321967,321952,321951,321946,321944,321943,321942,321916,321912,321907,321904,321897,321894,321890,321888,321886,321878,321877,321871,321868,321861,321860,321857,321853,321851,321848,321847,321844,321843,321838,321835,321834,321827,321826,321823,321814,321813,321810,321809,321808,321793,321790,321787,321779,321765,321750,321740,321715,321712,321700,321694,321688,321683,321681,321678,321676,321661,321658,321653,321651,321649,321639,321628,321626,321617,321614,321608,321607,321600,321589,321585,321582,321573,321568,321566,321562,321556,321542,321540,321532,321528,321527,321526,321520,321516,321512,321501,321491,321487,321483,321479,321474,321472,321467,321447,321445,321444,321441,321435,321434,321433,321429,321428,321425,321424,321411,321393,321392,321388,321385,321375,321374,321359,321356,321353,321341,321340,321326,321324,321319,321308,321299,321286,321262,321261,321252,321250,321247,321239,321232,321223,321208,321199,321189,321187,321185,321181,321178,321172,321171,321160,321159,321157,321151,321149,321140,321138,321129,321120,321116,321115,321112,321110,321099,321092,321090,321080,321033,321028,321021,321020,321008,320999,320998,320995,320994,320993,320992,320983,320975,320974,320972,320956,320955,320950,320944,320925,320890,320889,320884,320873,320870,320868,320860,320839,320834,320833,320830,320826,320819,320806,320798,320797,320796,320793,320789,320775,320771,320747,320738,320735,320725,320723,320721,320716,320714,320713,320705,320701,320696,320684,320673,320667,320666,320652,320649,320648,320639,320636,320608,320606,320605,320602,320583,320582,320577,320569,320562,320559,320533,320525,320516,320509,320507,320506,320472,320470,320462,320457,320418,320416,320412,320410,320395,320392,320384,320382,320380,320371,320368,320365,320363,320360,320359,320358,320353,320348,320346,320339,320330,320314,320296,320285,320280,320276,320270,320264,320262,320255,320246,320244,320242,320241,320229,320212,320208,320207,320206,320202,320198,320186,320185,320175,320174,320162,320161,320159,320156,320153,320148,320147,320146,320140,320136,320128,320126,320123,320118,320115,320109,320106,320103,320101,320098,320094,320085,320084,320064,320062,320061,320057,320043,320041,320039,320036,320035,320026,320021,320020,320005,319987,319986,319979,319970,319968,319967,319964,319956,319950,319947,319946,319945,319933,319925,319923,319911,319908,319900,319897,319895,319890,319884,319882,319878,319871,319864,319856,319852,319850,319831,319827,319824,319821,319814,319802,319797,319795,319783,319780,319773,319764,319761,319758,319756,319755,319754,319751,319749,319744,319742,319733,319729,319700,319699,319691,319682,319677,319673,319654,319653,319649,319645,319635,319623,319619,319612,319609,319607,319597,319591,319589,319583,319582,319571,319553,319540,319539,319537,319535,319533,319530,319529,319526,319517,319514,319511,319510,319508,319507,319475,319469,319468,319463,319460,319447,319440,319438,319437,319436,319432,319423,319420,319417,319414,319413,319409,319408,319405,319403,319395,319394,319391,319390,319389,319388,319385,319373,319372,319369,319361,319348,319333,319331,319327,319326,319323,319320,319319,319318,319314,319309,319305,319302,319294,319292,319281,319268,319266,319260,319257,319256,319255,319227,319226,319202,319200,319191,319188,319160,319157,319154,319138,319135,319130,319124,319123,319119,319116,319109,319099,319097,319096,319095,319089,319086,319085,319080,319079,319077,319067,319057,319053,319048,319046,319045,319032,319030,319026,319022,319017,319016,319009,319004,318998,318996,318988,318978,318973,318950,318936,318931,318923,318921,318920,318894,318892,318879,318875,318867,318863,318862,318858,318856,318855,318845,318843,318839,318838,318832,318824,318823,318821,318803,318798,318795,318794,318789,318784,318778,318775,318770,318769,318760,318756,318749,318740,318737,318734,318728,318725,318719,318717,318700,318698,318689,318686,318669,318667,318662,318649,318639,318627,318620,318599,318598,318597,318582,318570,318559,318557,318550,318547,318546,318529,318524,318514,318510,318505,318497,318491,318489,318484,318480,318459,318457,318440,318437,318433,318426,318391,318373,318370,318360,318355,318344,318342,318339,318337,318333,318330,318321,318320,318316,318315,318314,318307,318306,318305,318304,318302,318300,318298,318288,318287,318272,318259,318256,318251,318239,318226,318195,318187,318186,318183,318155,318152,318147,318140,318138,318127,318123,318117,318114,318113,318108,318106,318105,318095,318093,318092,318083,318079,318078,318067,318061,318058,318057,318053,318050,318040,318024,318023,318021,318015,318013,318007,318004,318000,317998,317994,317992,317988,317977,317967,317960,317954,317950,317949,317939,317918,317897,317890,317879,317878,317869,317865,317864,317852,317849,317847,317839,317837,317811,317803,317789,317769,317768,317763,317762,317759,317756,317740,317731,317711,317708,317698,317684,317679,317678,317673,317667,317660,317653,317644,317642,317640,317636,317634,317628,317613,317612,317606,317601,317587,317585,317583,317576,317574,317548,317543,317542,317540,317538,317530,317519,317517,317505,317504,317503,317500,317494,317491,317481,317479,317478,317473,317458,317456,317455,317454,317443,317441,317434,317432,317426,317418,317417,317412,317411,317409,317406,317399,317398,317397,317393,317388,317386,317384,317382,317367,317365,317362,317356,317350,317345,317344,317341,317339,317334,317331,317327,317322,317303,317292,317287,317281,317267,317262,317240,317239,317234,317229,317222,317216,317213,317199,317198,317193,317190,317181,317177,317169,317163,317162,317157,317150,317146,317145,317118,317108,317090,317087,317074,317059,317051,317044,317043,317041,317028,317016,317015,317008,317007,317002,317001,316994,316992,316983,316981,316978,316977,316966,316958,316945,316943,316942,316941,316940,316939,316934,316912,316906,316896,316889,316884,316849,316845,316839,316832,316827,316823,316815,316814,316793,316783,316771,316766,316757,316756,316754,316728,316721,316718,316712,316710,316707,316706,316701,316691,316682,316675,316673,316667,316665,316652,316651,316634,316632,316631,316629,316611,316590,316586,316580,316573,316564,316553,316552,316550,316539,316524,316511,316499,316497,316494,316486,316483,316466,316452,316448,316440,316439,316428,316426,316419,316416,316408,316405,316394,316393,316379,316374,316371,316368,316364,316363,316351,316348,316343,316335,316331,316329,316328,316325,316317,316316,316305,316304,316299,316298,316295,316294,316292,316289,316285,316282,316271,316264,316262,316261,316247,316229,316223,316216,316208,316202,316197,316184,316180,316176,316171,316166,316163,316160,316134,316128,316123,316120,316108,316103,316101,316100,316088,316084,316078,316076,316063,316059,316054,316048,316042,316040,316025,316018,316017,316016,315993,315986,315962,315956,315952,315930,315921,315920,315909,315907,315906,315895,315890,315885,315874,315872,315865,315858,315825,315824,315820,315815,315781,315780,315767,315755,315754,315720,315715,315711,315702,315686,315681,315680,315677,315673,315670,315669,315667,315665,315664,315662,315659,315645,315644,315634,315620,315617,315612,315609,315606,315584,315580,315565,315563,315559,315554,315545,315542,315541,315529,315528,315524,315508,315501,315491,315489,315488,315487,315472,315466,315458,315456,315453,315447,315445,315433,315432,315430,315426,315405,315380,315375,315366,315361,315347,315337,315330,315316,315311,315287,315274,315271,315270,315267,315264,315260,315258,315257,315248,315228,315223,315222,315221,315215,315212,315193,315182,315180,315179,315163,315153,315152,315150,315137,315135,315133,315132,315128,315126,315125,315115,315096,315085,315083,315075,315062,315061,315056,315051,315042,315038,315031,315026,315025,315021,315020,315012,315010,315002,314993,314991,314973,314967,314958,314949,314948,314939,314938,314925,314920,314916,314900,314898,314894,314892,314866,314851,314841,314840,314837,314828,314826,314825,314823,314822,314788,314786,314778,314776,314750,314742,314736,314734,314723,314721,314712,314706,314705,314694,314689,314647,314637,314631,314629,314628,314620,314601,314598,314595,314590,314588,314568,314563,314544,314542,314539,314532,314522,314519,314511,314503,314498,314496,314480,314472,314469,314461,314438,314437,314436,314435,314431,314426,314423,314420,314412,314411,314408,314402,314392,314390,314386,314384,314383,314369,314364,314347,314343,314338,314337,314335,314330,314328,314320,314316,314313,314311,314306,314305,314303,314302,314282,314276,314266,314264,314255,314244,314239,314231,314221,314209,314207,314199,314183,314182,314181,314178,314177,314159,314157,314152,314151,314140,314130,314124,314101,314099,314098,314091,314090,314089,314088,314086,314085,314083,314082,314077,314071,314070,314068,314064,314053,314051,314050,314048,314045,314027,314026,314016,313996,313983,313976,313949,313946,313944,313943,313934,313929,313927,313921,313910,313881,313878,313859,313849,313847,313845,313844,313840,313839,313818,313808,313798,313797,313795,313783,313781,313778,313774,313745,313743,313726,313719,313713,313712,313711,313702,313695,313657,313655,313653,313640,313637,313619,313616,313615,313613,313611,313610,313605,313598,313595,313594,313584,313581,313580,313577,313565,313562,313561,313559,313553,313541,313538,313531,313509,313506,313505,313497,313482,313480,313475,313473,313471,313465,313463,313462,313461,313459,313456,313450,313441,313427,313414,313412,313408,313403,313397,313372,313369,313356,313352,313343,313338,313337,313336,313333,313332,313321,313319,313313,313307,313302,313299,313291,313289,313287,313280,313268,313264,313257,313256,313252,313245,313243,313239,313235,313216,313211,313208,313204,313200,313196,313191,313186,313184,313181,313173,313150,313147,313143,313141,313138,313133,313129,313114,313104,313096,313088,313071,313069,313054,313052,313050,313048,313045,313044,313036,313035,313030,313020,313015,313009,313004,312988,312984,312976,312968,312965,312964,312958,312948,312942,312940,312939,312934,312931,312911,312909,312904,312903,312898,312895,312893,312892,312887,312885,312881,312877,312862,312859,312851,312846,312838,312826,312825,312823,312820,312818,312815,312808,312800,312795,312793,312790,312788,312784,312783,312782,312778,312775,312774,312772,312769,312766,312764,312742,312737,312731,312728,312725,312718,312717,312711,312705,312704,312692,312676,312664,312663,312653,312648,312647,312639,312624,312612,312599,312595,312588,312576,312574,312569,312567,312549,312548,312546,312542,312529,312518,312507,312506,312499,312492,312464,312463,312450,312446,312426,312421,312413,312409,312408,312405,312403,312391,312390,312387,312385,312363,312358,312345,312342,312333,312330,312328,312312,312307,312298,312296,312294,312280,312278,312269,312268,312253,312230,312226,312225,312223,312219,312218,312201,312195,312185,312177,312172,312166,312155,312136,312133,312118,312117,312115,312114,312109,312071,312069,312067,312059,312052,312047,312045,312026,312015,312007,311997,311978,311977,311971,311965,311963,311958,311950,311947,311926,311914,311907,311903,311901,311900,311891,311888,311884,311882,311879,311876,311867,311852,311851,311847,311845,311843,311833,311825,311823,311822,311821,311812,311789,311788,311785,311779,311776,311767,311760,311758,311732,311731,311728,311713,311696,311684,311682,311680,311678,311677,311667,311659,311650,311645,311639,311628,311620,311594,311591,311590,311585,311584,311583,311565,311559,311558,311547,311544,311539,311527,311524,311521,311509,311508,311500,311495,311485,311484,311483,311480,311479,311450,311449,311447,311445,311440,311436,311431,311430,311423,311418,311405,311403,311383,311378,311370,311363,311360,311359,311354,311349,311346,311343,311338,311336,311332,311328,311317,311314,311311,311309,311280,311275,311274,311257,311234,311226,311225,311221,311218,311214,311205,311196,311186,311176,311175,311157,311156,311155,311141,311134,311131,311125,311123,311118,311112,311109,311089,311088,311087,311086,311080,311070,311066,311061,311059,311057,311054,311050,311044,311032,311024,311017,311014,311011,310998,310992,310984,310980,310978,310975,310969,310959,310954,310950,310947,310940,310939,310937,310936,310931,310928,310927,310921,310905,310903,310878,310876,310872,310870,310858,310857,310856,310849,310846,310839,310822,310820,310818,310817,310811,310809,310794,310792,310787,310776,310775,310774,310762,310760,310747,310744,310743,310736,310733,310717,310702,310701,310700,310693,310689,310683,310671,310668,310658,310653,310650,310649,310617,310616,310595,310592,310589,310583,310580,310575,310572,310570,310569,310568,310565,310564,310561,310556,310549,310540,310537,310534,310527,310521,310514,310512,310507,310502,310491,310485,310478,310468,310465,310459,310453,310448,310437,310436,310431,310408,310389,310329,310299,310293,310289,310287,310267,310266,310259,310239,310233,310231,310214,310208,310206,310201,310195,310189,310182,310161,310157,310152,310149,310142,310133,310130,310129,310122,310121,310117,310103,310102,310100,310095,310091,310076,310051,310043,310041,310039,310025,310022,310019,310016,310006,310005,309995,309977,309967,309964,309961,309960,309944,309942,309930,309922,309897,309892,309877,309854,309851,309849,309846,309823,309810,309807,309790,309788,309776,309773,309772,309769,309765,309762,309756,309748,309741,309739,309736,309735,309721,309720,309714,309713,309706,309698,309687,309686,309671,309654,309633,309631,309628,309621,309613,309599,309598,309597,309595,309583,309577,309566,309565,309560,309559,309557,309540,309538,309531,309529,309520,309504,309490,309484,309477,309476,309475,309470,309469,309464,309449,309441,309438,309437,309433,309423,309416,309404,309384,309383,309371,309369,309356,309348,309347,309341,309339,309337,309316,309301,309296,309291,309283,309278,309257,309248,309238,309235,309231,309224,309221,309219,309195,309193,309184,309182,309174,309172,309170,309168,309167,309146,309144,309135,309134,309133,309115,309113,309078,309077,309075,309069,309059,309057,309052,309048,309047,309046,309040,309035,309034,309029,309018,309015,309000,308999,308979,308959,308956,308946,308944,308941,308939,308937,308935,308932,308929,308927,308901,308895,308883,308880,308879,308878,308871,308868,308862,308858,308850,308845,308834,308827,308820,308815,308814,308810,308795,308787,308782,308781,308780,308771,308769,308760,308756,308751,308750,308749,308741,308730,308729,308726,308724,308717,308715,308714,308709,308699,308681,308665,308664,308661,308658,308644,308642,308637,308627,308614,308613,308611,308609,308607,308605,308604,308591,308584,308570,308562,308559,308547,308536,308529,308528,308527,308507,308478,308473,308451,308448,308447,308445,308426,308422,308410,308407,308395,308391,308387,308379,308373,308369,308367,308356,308354,308341,308333,308330,308329,308328,308325,308317,308311,308309,308306,308304,308302,308300,308287,308284,308283,308282,308268,308257,308252,308249,308247,308246,308225,308224,308214,308194,308182,308172,308169,308167,308166,308163,308155,308152,308151,308144,308133,308121,308113,308103,308101,308087,308086,308085,308084,308083,308041,308033,308030,308025,308024,308006,308003,307997,307993,307990,307977,307972,307969,307959,307950,307945,307942,307934,307927,307924,307920,307919,307917,307916,307913,307908,307899,307894,307889,307881,307877,307849,307816,307811,307784,307783,307780,307778,307771,307764,307763,307749,307737,307735,307732,307729,307719,307716,307714,307709,307708,307703,307682,307673,307665,307653,307645,307644,307642,307637,307636,307618,307617,307615,307606,307603,307601,307587,307575,307570,307567,307554,307551,307534,307524,307499,307498,307490,307486,307478,307475,307463,307462,307459,307434,307411,307401,307393,307379,307368,307366,307363,307362,307355,307354,307345,307325,307324,307298,307284,307280,307276,307275,307240,307238,307225,307223,307218,307216,307204,307181,307151,307133,307122,307114,307110,307062,307057,307055,307035,307031,306997,306995,306986,306983,306973,306963,306958,306953,306952,306912,306905,306902,306898,306895,306894,306893,306887,306873,306865,306850,306829,306828,306827,306817,306808,306807,306796,306791,306782,306777,306764,306762,306746,306742,306737,306730,306726,306719,306716,306709,306707,306705,306685,306676,306671,306663,306660,306646,306645,306634,306625,306617,306616,306613,306604,306588,306574,306572,306571,306568,306547,306519,306512,306509,306489,306488,306474,306469,306459,306458,306442,306441,306436,306431,306422,306417,306391,306389,306385,306384,306366,306364,306346,306334,306322,306315,306314,306306,306296,306288,306273,306266,306265,306251,306249,306245,306244,306243,306238,306237,306230,306216,306184,306182,306181,306180,306179,306172,306163,306162,306158,306156,306145,306144,306140,306134,306132,306127,306121,306120,306119,306085,306070,306068,306063,306057,306056,306054,306048,306043,306024,306003,306000,305998,305993,305986,305976,305961,305955,305951,305946,305942,305938,305937,305936,305926,305924,305922,305918,305917,305915,305904,305894,305882,305880,305877,305876,305841,305836,305826,305825,305811,305804,305800,305795,305792,305791,305789,305787,305783,305774,305764,305757,305754,305753,305752,305751,305736,305729,305720,305718,305695,305689,305684,305678,305670,305662,305657,305656,305654,305576,305565,305564,305563,305534,305531,305528,305498,305496,305475,305472,305471,305469,305459,305454,305448,305428,305415,305413,305403,305398,305392,305385,305380,305374,305361,305354,305342,305334,305320,305311,305306,305290,305289,305285,305283,305277,305275,305274,305270,305243,305236,305235,305233,305223,305217,305205,305193,305188,305186,305166,305147,305146,305144,305140,305126,305125,305124,305121,305120,305119,305114,305111,305098,305097,305080,305075,305072,305067,305061,305057,305052,305049,305048,305045,305039,305036,305034,305033,305032,305021,305019,305010,305007,305000,304992,304974,304962,304954,304948,304942,304937,304933,304930,304925,304924,304921,304910,304909,304904,304894,304889,304883,304879,304876,304873,304872,304868,304861,304843,304840,304837,304836,304833,304828,304825,304820,304818,304808,304807,304803,304793,304789,304786,304780,304778,304774,304764,304759,304757,304748,304746,304740,304739,304737,304723,304721,304716,304713,304703,304700,304691,304687,304682,304675,304667,304658,304642,304635,304622,304612,304600,304592,304589,304581,304580,304567,304557,304556,304554,304552,304551,304544,304527,304501,304499,304496,304493,304491,304474,304472,304470,304460,304453,304448,304416,304406,304402,304397,304392,304388,304382,304381,304379,304373,304372,304362,304361,304360,304358,304353,304344,304336,304335,304325,304324,304312,304296,304292,304283,304278,304276,304267,304264,304263,304249,304225,304214,304209,304184,304177,304167,304111,304106,304098,304083,304082,304081,304079,304077,304076,304057,304056,304044,304037,304032,304029,304027,304026,304024,304019,304017,304009,303955,303945,303938,303925,303921,303920,303913,303911,303900,303898,303879,303875,303866,303864,303851,303845,303844,303843,303825,303820,303815,303812,303811,303791,303769,303764,303760,303756,303751,303748,303739,303738,303731,303729,303728,303721,303717,303712,303711,303701,303696,303691,303687,303684,303675,303673,303671,303670,303667,303666,303649,303645,303644,303638,303625,303624,303622,303620,303614,303602,303594,303577,303573,303569,303559,303555,303537,303501,303493,303487,303481,303459,303456,303448,303424,303416,303411,303405,303402,303398,303393,303363,303362,303352,303351,303350,303348,303347,303346,303345,303341,303340,303335,303327,303325,303324,303322,303317,303314,303313,303308,303302,303298,303294,303291,303284,303282,303281,303279,303272,303270,303268,303258,303254,303247,303245,303242,303234,303233,303227,303221,303218,303216,303209,303203,303198,303186,303180,303175,303174,303164,303143,303137,303131,303129,303127,303126,303125,303124,303103,303101,303100,303096,303095,303086,303084,303080,303068,303057,303036,303028,303021,303002,303001,303000,302997,302996,302993,302992,302985,302969,302963,302959,302907,302894,302887,302883,302880,302879,302870,302868,302849,302843,302840,302836,302833,302815,302813,302784,302779,302775,302767,302763,302755,302753,302750,302749,302747,302722,302714,302708,302695,302694,302676,302647,302646,302636,302635,302634,302633,302632,302628,302625,302620,302619,302604,302596,302579,302575,302563,302553,302551,302549,302546,302538,302527,302521,302513,302505,302504,302496,302491,302468,302467,302451,302441,302436,302424,302421,302419,302416,302405,302400,302399,302388,302387,302380,302372,302371,302365,302358,302357,302350,302346,302342,302337,302335,302334,302332,302327,302326,302311,302302,302291,302290,302282,302277,302267,302265,302264,302250,302249,302246,302239,302234,302223,302202,302197,302195,302194,302175,302174,302172,302162,302144,302143,302140,302138,302136,302130,302126,302114,302091,302089,302086,302077,302074,302060,302028,302026,302025,302009,301997,301975,301972,301967,301962,301960,301958,301946,301938,301934,301932,301929,301928,301923,301921,301902,301894,301893,301890,301887,301886,301882,301880,301877,301875,301869,301853,301851,301850,301849,301828,301821,301820,301812,301811,301805,301803,301800,301794,301772,301763,301761,301760,301755,301745,301743,301741,301739,301736,301723,301721,301718,301701,301697,301688,301678,301675,301673,301670,301669,301664,301663,301650,301645,301641,301626,301580,301576,301571,301569,301555,301552,301547,301544,301533,301530,301529,301525,301518,301513,301510,301509,301494,301493,301487,301485,301482,301474,301464,301453,301452,301450,301447,301446,301436,301420,301416,301413,301411,301409,301402,301393,301387,301384,301382,301380,301379,301378,301370,301360,301359,301358,301356,301340,301334,301318,301307,301306,301303,301302,301298,301286,301283,301282,301275,301266,301261,301257,301254,301252,301244,301240,301237,301227,301226,301225,301223,301222,301218,301205,301203,301201,301192,301187,301181,301169,301168,301150,301149,301143,301137,301135,301128,301122,301110,301109,301101,301100,301096,301083,301078,301070,301058,301057,301053,301046,301040,301033,301030,301018,301004,301003,300999,300996,300993,300992,300991,300974,300969,300968,300961,300935,300931,300926,300925,300914,300912,300911,300908,300907,300901,300893,300880,300879,300870,300863,300862,300848,300811,300806,300801,300800,300786,300775,300757,300755,300752,300750,300748,300745,300742,300741,300740,300732,300731,300723,300717,300713,300703,300692,300677,300676,300674,300672,300669,300667,300662,300659,300655,300652,300644,300643,300641,300637,300629,300626,300623,300621,300614,300606,300599,300596,300595,300593,300587,300586,300555,300546,300543,300536,300528,300526,300523,300522,300514,300510,300497,300495,300493,300491,300483,300482,300462,300460,300456,300448,300445,300434,300432,300429,300421,300405,300404,300399,300395,300394,300392,300386,300378,300373,300369,300361,300350,300347,300346,300333,300332,300327,300326,300304,300302,300301,300300,300289,300282,300279,300278,300277,300271,300261,300254,300251,300250,300249,300248,300215,300203,300199,300196,300186,300184,300160,300148,300145,300138,300128,300117,300116,300105,300086,300081,300071,300067,300044,300033,300026,300022,300020,300019,300007,300006,300002,299999,299998,299996,299987,299983,299977,299973,299943,299939,299925,299924,299922,299915,299910,299909,299902,299900,299899,299895,299889,299882,299874,299863,299861,299842,299835,299832,299820,299815,299804,299783,299780,299768,299764,299763,299741,299733,299729,299727,299725,299724,299718,299716,299712,299710,299707,299701,299698,299695,299687,299677,299672,299666,299662,299661,299658,299656,299651,299650,299648,299647,299646,299645,299644,299639,299638,299634,299632,299623,299619,299613,299604,299588,299586,299585,299578,299566,299562,299558,299556,299555,299549,299547,299538,299532,299529,299520,299513,299505,299502,299500,299484,299480,299478,299477,299473,299470,299461,299451,299446,299442,299441,299435,299434,299432,299425,299424,299417,299416,299413,299412,299406,299401,299400,299395,299389,299388,299387,299385,299380,299375,299363,299355,299352,299351,299343,299338,299336,299335,299332,299330,299324,299289,299286,299275,299263,299261,299259,299242,299239,299235,299213,299212,299211,299204,299202,299188,299186,299185,299181,299171,299151,299150,299149,299148,299147,299146,299142,299141,299123,299119,299111,299103,299085,299084,299082,299075,299073,299064,299063,299062,299040,299039,299036,299026,299025,299023,299015,299014,298985,298974,298972,298967,298955,298938,298935,298933,298930,298928,298922,298918,298906,298902,298901,298899,298896,298894,298893,298865,298863,298856,298851,298850,298846,298844,298837,298832,298831,298828,298825,298813,298812,298810,298807,298796,298794,298791,298787,298786,298781,298779,298771,298767,298766,298762,298761,298757,298752,298734,298733,298722,298719,298708,298707,298706,298704,298692,298690,298689,298683,298678,298674,298673,298672,298664,298659,298651,298636,298620,298612,298601,298599,298593,298591,298590,298580,298576,298571,298570,298569,298568,298567,298560,298554,298552,298539,298532,298521,298517,298497,298488,298485,298470,298469,298464,298463,298461,298454,298453,298452,298446,298440,298439,298437,298435,298425,298424,298418,298416,298403,298396,298391,298389,298386,298383,298381,298380,298379,298375,298374,298363,298362,298361,298360,298335,298305,298291,298290,298289,298281,298278,298269,298264,298263,298261,298255,298253,298252,298250,298245,298243,298241,298239,298238,298234,298223,298222,298221,298211,298186,298179,298171,298170,298168,298166,298161,298155,298151,298150,298149,298144,298141,298135,298134,298130,298121,298119,298115,298114,298111,298104,298102,298101,298093,298086,298085,298084,298081,298080,298077,298073,298070,298065,298064,298063,298062,298059,298056,298052,298048,298045,298043,298041,298035,298034,298003,297997,297982,297981,297977,297970,297968,297956,297955,297954,297947,297941,297936,297935,297933,297932,297931,297929,297928,297914,297897,297895,297892,297891,297888,297887,297884,297882,297880,297879,297862,297847,297842,297841,297840,297839,297835,297833,297829,297828,297827,297825,297823,297822,297788,297775,297769,297759,297750,297748,297741,297738,297733,297717,297716,297706,297702,297681,297676,297673,297672,297666,297665,297650,297649,297646,297643,297624,297614,297613,297594,297593,297587,297583,297579,297578,297575,297573,297563,297560,297558,297549,297540,297528,297519,297518,297517,297510,297504,297491,297483,297478,297476,297475,297472,297453,297450,297448,297447,297439,297436,297432,297428,297427,297422,297418,297416,297400,297397,297392,297388,297382,297379,297375,297362,297360,297357,297350,297348,297347,297346,297341,297334,297328,297323,297320,297302,297300,297289,297287,297279,297276,297269,297261,297260,297257,297251,297248,297243,297239,297236,297234,297226,297221,297220,297212,297211,297204,297199,297190,297182,297166,297165,297162,297149,297148,297141,297123,297118,297110,297102,297101,297098,297096,297090,297089,297087,297068,297065,297051,297041,297039,297036,297032,297029,297027,297023,297016,297013,297010,297000,296988,296987,296984,296982,296980,296962,296961,296955,296952,296947,296942,296941,296935,296933,296930,296906,296902,296897,296892,296883,296880,296878,296872,296868,296866,296855,296852,296845,296844,296843,296837,296833,296824,296820,296819,296818,296811,296809,296804,296794,296792,296789,296788,296785,296781,296773,296768,296748,296731,296729,296720,296719,296717,296716,296714,296700,296694,296684,296678,296676,296673,296670,296668,296667,296664,296663,296661,296655,296654,296650,296647,296646,296644,296642,296640,296638,296634,296633,296630,296626,296623,296606,296593,296591,296588,296583,296582,296577,296575,296574,296573,296572,296571,296569,296566,296557,296545,296543,296542,296538,296531,296529,296526,296507,296504,296494,296492,296481,296480,296478,296476,296466,296459,296455,296453,296450,296441,296431,296429,296418,296414,296411,296403,296384,296379,296366,296362,296347,296342,296341,296340,296327,296325,296318,296315,296314,296311,296307,296305,296304,296299,296291,296288,296282,296280,296278,296277,296275,296274,296273,296272,296271,296270,296269,296268,296267,296266,296260,296257,296253,296252,296247,296243,296242,296240,296238,296235,296232,296227,296221,296218,296216,296215,296199,296192,296190,296185,296180,296172,296169,296168,296160,296156,296154,296143,296128,296125,296110,296105,296102,296095,296094,296089,296082,296077,296076,296075,296074,296073,296071,296063,296059,296058,296056,296050,296046,296042,296037,296034,296033,296021,296019,296018,296016,295995,295991,295989,295976,295970,295963,295959,295944,295942,295930,295929,295927,295924,295916,295914,295913,295910,295909,295905,295903,295900,295897,295892,295889,295888,295883,295882,295873,295870,295865,295862,295858,295857,295849,295844,295840,295837,295835,295833,295823,295815,295803,295799,295798,295797,295786,295785,295784,295783,295778,295776,295773,295771,295768,295766,295757,295751,295745,295732,295730,295726,295723,295693,295691,295684,295678,295675,295658,295657,295634,295630,295625,295623,295618,295609,295601,295599,295593,295592,295588,295585,295583,295581,295580,295574,295570,295569,295566,295565,295554,295547,295543,295541,295540,295539,295533,295528,295517,295514,295505,295492,295489,295472,295469,295466,295462,295446,295435,295429,295423,295418,295415,295413,295406,295401,295400,295399,295397,295396,295392,295391,295379,295377,295368,295363,295359,295358,295356,295345,295337,295336,295334,295322,295321,295309,295302,295290,295287,295280,295274,295269,295268,295264,295261,295257,295242,295241,295237,295234,295233,295231,295227,295216,295214,295210,295209,295205,295199,295198,295197,295194,295185,295178,295177,295174,295169,295164,295156,295155,295140,295139,295137,295124,295118,295115,295108,295101,295100,295079,295076,295072,295063,295062,295061,295057,295051,295050,295047,295037,295036,295032,295027,295025,295021,295014,295013,295006,294997,294992,294991,294985,294984,294983,294974,294968,294966,294965,294962,294955,294954,294947,294945,294944,294942,294941,294939,294927,294926,294924,294917,294909,294907,294890,294889,294887,294886,294885,294884,294882,294881,294868,294865,294858,294857,294856,294854,294853,294845,294844,294840,294838,294836,294829,294826,294825,294819,294812,294811,294802,294800,294788,294783,294779,294777,294776,294769,294764,294756,294755,294750,294747,294743,294736,294728,294720,294719,294718,294716,294715,294713,294710,294709,294693,294690,294679,294674,294673,294668,294662,294658,294656,294651,294637,294626,294622,294596,294585,294583,294575,294572,294571,294569,294568,294567,294561,294557,294553,294552,294550,294549,294548,294544,294528,294526,294525,294523,294513,294512,294505,294488,294481,294480,294477,294469,294468,294467,294463,294454,294446,294442,294437,294423,294420,294415,294414,294406,294404,294402,294399,294383,294381,294366,294361,294358,294350,294326,294322,294320,294314,294310,294307,294305,294304,294298,294296,294294,294292,294289,294279,294277,294274,294273,294272,294263,294260,294257,294250,294246,294240,294238,294232,294227,294224,294216,294212,294210,294209,294207,294206,294204,294203,294201,294198,294197,294192,294187,294183,294181,294177,294167,294165,294162,294157,294156,294154,294152,294151,294149,294148,294137,294132,294128,294124,294119,294105,294102,294096,294080,294076,294063,294061,294058,294057,294056,294049,294045,294044,294043,294042,294039,294034,294033,294021,294016,294010,294008,294007,294005,294003,294000,293997,293979,293970,293964,293962,293951,293950,293946,293944,293928,293927,293925,293922,293907,293902,293901,293896,293890,293888,293878,293867,293861,293860,293859,293858,293857,293850,293841,293821,293813,293812,293809,293801,293800,293791,293778,293771,293762,293761,293756,293754,293746,293734,293729,293728,293722,293718,293712,293707,293683,293679,293678,293671,293669,293668,293667,293665,293664,293663,293661,293648,293647,293641,293634,293628,293617,293608,293607,293587,293585,293583,293580,293573,293567,293563,293554,293553,293552,293551,293547,293543,293537,293531,293527,293524,293523,293522,293521,293518,293515,293514,293510,293509,293503,293499,293491,293488,293483,293481,293474,293463,293452,293450,293446,293438,293436,293430,293425,293424,293422,293419,293417,293404,293390,293383,293376,293357,293356,293353,293352,293345,293333,293331,293327,293326,293323,293319,293316,293312,293309,293308,293306,293296,293291,293290,293288,293287,293283,293282,293279,293273,293268,293250,293249,293237,293230,293228,293213,293210,293204,293198,293179,293170,293169,293164,293144,293142,293141,293136,293121,293119,293116,293102,293095,293090,293087,293085,293077,293070,293065,293061,293060,293058,293054,293050,293045,293032,293024,293020,293009,293006,292963,292959,292957,292956,292948,292939,292935,292917,292916,292913,292900,292899,292895,292883,292875,292864,292863,292862,292858,292857,292848,292844,292843,292839,292835,292830,292828,292827,292826,292824,292820,292815,292814,292804,292802,292800,292793,292792,292789,292785,292782,292780,292778,292771,292762,292759,292756,292754,292745,292741,292740,292739,292723,292713,292706,292695,292692,292686,292685,292677,292676,292671,292668,292653,292644,292641,292636,292635,292633,292632,292627,292626,292622,292616,292602,292600,292599,292595,292588,292585,292580,292579,292578,292573,292568,292560,292553,292551,292549,292544,292541,292537,292532,292531,292527,292526,292525,292522,292521,292515,292511,292501,292499,292496,292492,292490,292486,292483,292481,292479,292470,292467,292458,292452,292451,292450,292448,292447,292444,292434,292426,292424,292421,292420,292414,292412,292410,292407,292406,292403,292398,292396,292395,292394,292393,292390,292388,292387,292381,292377,292376,292359,292355,292354,292347,292334,292323,292316,292311,292301,292299,292297,292296,292290,292289,292287,292274,292271,292267,292266,292261,292260,292250,292243,292239,292238,292235,292223,292219,292218,292216,292213,292212,292211,292210,292207,292206,292197,292196,292194,292193,292191,292179,292166,292165,292159,292156,292154,292153,292151,292146,292144,292142,292127,292124,292123,292110,292109,292104,292103,292092,292089,292087,292083,292080,292075,292073,292071,292068,292065,292063,292060,292059,292053,292051,292042,292041,292040,292039,292035,292029,292028,292026,292025,292022,292020,292018,292014,292013,292009,292006,292003,291998,291993,291992,291991,291971,291970,291966,291964,291961,291951,291946,291943,291940,291936,291932,291928,291925,291923,291920,291919,291918,291916,291914,291906,291904,291899,291888,291883,291879,291873,291871,291870,291865,291864,291857,291855,291852,291846,291836,291833,291832,291830,291825,291820,291817,291813,291810,291805,291791,291790,291779,291775,291770,291768,291764,291762,291760,291752,291748,291741,291739,291737,291735,291733,291728,291719,291715,291701,291700,291698,291696,291695,291692,291689,291679,291675,291674,291671,291664,291661,291660,291658,291657,291651,291650,291648,291639,291636,291635,291634,291631,291626,291621,291619,291618,291617,291614,291613,291611,291610,291604,291601,291591,291585,291584,291578,291577,291572,291570,291569,291564,291560,291549,291547,291546,291538,291532,291531,291529,291526,291525,291524,291521,291519,291515,291513,291508,291505,291500,291495,291485,291483,291482,291481,291480,291479,291472,291468,291466,291461,291454,291452,291451,291443,291422,291420,291419,291418,291410,291408,291407,291404,291401,291398,291396,291394,291385,291377,291376,291375,291374,291370,291368,291367,291362,291359,291353,291351,291349,291346,291343,291342,291339,291335,291332,291328,291326,291324,291323,291321,291316,291315,291313,291311,291310,291306,291305,291295,291294,291293,291291,291289,291287,291276,291274,291271,291270,291265,291264,291263,291256,291249,291246,291243,291231,291230,291226,291222,291221,291216,291213,291208,291204,291201,291200,291195,291194,291188,291181,291177,291171,291167,291164,291163,291162,291159,291157,291155,291149,291147,291144,291140,291137,291135,291122,291117,291114,291110,291106,291103,291097,291096,291093,291092,291091,291089,291086,291078,291071,291069,291067,291066,291065,291064,291060,291059,291053,291050,291042,291040,291039,291038,291034,291033,291019,291018,291003,290992,290989,290985,290984,290976,290969,290963,290960,290957,290950,290947,290943,290939,290936,290935,290933,290923,290919,290912,290910,290906,290904,290896,290894,290893,290891,290890,290877,290868,290864,290862,290860,290859,290850,290848,290847,290844,290843,290842,290840,290837,290828,290827,290826,290823,290821,290817,290814,290812,290807,290805,290804,290803,290799,290794,290790,290786,290785,290772,290771,290764,290761,290757,290755,290754,290749,290746,290745,290742,290740,290733,290723,290720,290713,290707,290703,290693,290692,290679,290674,290672,290670,290669,290664,290663,290661,290658,290656,290650,290643,290640,290639,290630,290629,290628,290627,290625,290624,290622,290619,290617,290616,290611,290604,290595,290593,290592,290588,290580,290575,290574,290572,290570,290566,290562,290560,290559,290557,290551,290548,290543,290539,290535,290534,290527,290525,290521,290520,290515,290513,290499,290493,290490,290489,290488,290483,290473,290472,290461,290455,290451,290442,290441,290440,290439,290435,290431,290427,290423,290420,290417,290415,290414,290404,290403,290402,290398,290397,290392,290389,290386,290383,290379,290376,290375,290371,290369,290363,290361,290359,290358,290355,290353,290344,290343,290339,290331,290329,290324,290318,290314,290306,290301,290299,290296,290288,290287,290284,290278,290262,290259,290257,290256,290253,290247,290244,290243,290239,290236,290234,290231,290229,290220,290216,290205,290201,290199,290197,290196,290174,290169,290167,290165,290164,290158,290143,290139,290136,290128,290126,290125,290122,290121,290113,290105,290088,290079,290076,290064,290059,290058,290053,290048,290044,290035,290032,290027,290026,290025,290024,290019,290016,290013,290009,290008,290007,290004,290003,290002,290001,289995,289986,289982,289979,289978,289977,289968,289952,289951,289947,289945,289941,289940,289936,289934,289932,289925,289924,289920,289919,289913,289912,289911,289910,289906,289905,289903,289899,289898,289885,289880,289875,289872,289866,289856,289855,289851,289850,289849,289841,289833,289832,289826,289823,289822,289817,289816,289809,289807,289806,289795,289794,289789,289787,289779,289772,289761,289751,289750,289749,289748,289743,289741,289739,289738,289737,289732,289731,289727,289721,289720,289717,289716,289712,289709,289707,289704,289703,289701,289699,289694,289691,289687,289685,289683,289677,289673,289665,289654,289652,289648,289647,289646,289645,289644,289643,289640,289639,289638,289635,289629,289627,289625,289620,289614,289613,289609,289603,289593,289582,289580,289579,289577,289574,289573,289572,289570,289569,289566,289564,289559,289558,289557,289551,289550,289540,289539,289537,289536,289532,289525,289523,289521,289513,289505,289503,289502,289500,289496,289495,289491,289489,289488,289479,289478,289477,289476,289473,289472,289469,289468,289466,289462,289459,289450,289444,289438,289436,289433,289432,289431,289430,289428,289427,289426,289423,289410,289407,289392,289390,289384,289372,289371,289366,289362,289361,289359,289357,289355,289352,289343,289339,289334,289331,289329,289318,289316,289310,289309,289306,289305,289299,289298,289295,289288,289284,289281,289279,289271,289270,289268,289263,289261,289254,289252,289251,289249,289244,289243,289231,289223,289221,289214,289213,289206,289205,289204,289199,289195,289186,289185,289184,289183,289181,289176,289172,289167,289157,289156,289148,289146,289145,289138,289135,289134,289133,289126,289125,289119,289117,289112,289107,289106,289102,289094,289090,289089,289088,289075,289073,289070,289066,289061,289058,289057,289053,289050,289049,289042,289030,289020,289017,289014,289011,289010,289008,288986,288983,288970,288968,288960,288959,288958,288957,288952,288943,288942,288939,288938,288926,288924,288914,288905,288902,288901,288897,288896,288878,288872,288860,288859,288855,288851,288849,288847,288839,288835,288834,288832,288825,288818,288817,288816,288815,288814,288813,288807,288804,288799,288796,288787,288785,288781,288780,288778,288777,288771,288770,288768,288762,288758,288756,288755,288752,288746,288730,288729,288728,288727,288719,288717,288714,288713,288705,288702,288701,288699,288697,288694,288686,288681,288673,288672,288671,288670,288668,288666,288658,288655,288654,288653,288650,288636,288634,288633,288628,288625,288624,288622,288616,288611,288610,288604,288601,288597,288596,288582,288579,288574,288571,288570,288567,288566,288563,288558,288557,288546,288544,288543,288539,288538,288536,288527,288526,288524,288520,288519,288518,288517,288516,288512,288509,288506,288502,288498,288497,288492,288489,288485,288483,288482,288480,288475,288474,288473,288470,288466,288458,288451,288439,288437,288430,288426,288419,288418,288415,288413,288409,288400,288393,288386,288383,288381,288376,288371,288364,288353,288348,288346,288345,288338,288329,288322,288320,288319,288307,288305,288301,288298,288294,288293,288292,288288,288283,288280,288277,288275,288274,288273,288272,288270,288264,288259,288252,288251,288247,288246,288245,288243,288232,288231,288230,288224,288221,288217,288215,288212,288210,288209,288202,288201,288198,288186,288172,288171,288168,288167,288165,288162,288161,288153,288150,288144,288143,288142,288139,288127,288124,288121,288116,288108,288104,288102,288097,288083,288082,288079,288078,288076,288075,288069,288066,288048,288044,288038,288037,288036,288035,288032,288021,288017,288015,288012,288010,288009,288008,288007,288006,288005,288003,288001,287998,287997,287995,287986,287981,287979,287977,287973,287967,287966,287965,287963,287959,287957,287955,287953,287950,287946,287944,287941,287940,287939,287931,287930,287929,287922,287921,287919,287916,287913,287912,287909,287906,287903,287902,287900,287898,287895,287891,287890,287889,287888,287887,287883,287881,287877,287865,287863,287862,287859,287855,287851,287845,287843,287838,287835,287833,287830,287829,287828,287822,287820,287819,287809,287808,287803,287800,287798,287797,287792,287775,287769,287762,287761,287760,287758,287753,287744,287743,287740,287738,287728,287727,287716,287715,287714,287713,287709,287708,287704,287702,287697,287696,287692,287691,287689,287688,287682,287679,287676,287670,287664,287663,287658,287657,287656,287655,287654,287646,287645,287641,287640,287639,287636,287632,287628,287626,287625,287622,287617,287610,287606,287604,287603,287601,287598,287597,287594,287577,287567,287558,287555,287554,287553,287551,287539,287538,287535,287534,287527,287526,287524,287521,287516,287515,287513,287507,287504,287500,287498,287497,287495,287491,287477,287476,287474,287472,287465,287461,287459,287444,287441,287437,287436,287435,287432,287431,287427,287426,287425,287423,287421,287415,287414,287407,287406,287404,287401,287395,287393,287392,287389,287376,287373,287361,287360,287353,287352,287349,287347,287336,287335,287334,287332,287331,287330,287326,287324,287316,287313,287312,287309,287308,287306,287300,287299,287297,287295,287283,287282,287278,287276,287273,287271,287269,287267,287264,287256,287255,287242,287240,287230,287223,287221,287217,287216,287214,287211,287206,287205,287204,287197,287192,287190,287188,287184,287181,287173,287171,287164,287153,287148,287145,287138,287128,287126,287121,287113,287112,287110,287109,287105,287098,287095,287089,287079,287078,287076,287071,287070,287066,287065,287062,287058,287053,287052,287044,287043,287042,287041,287037,287031,287027,287026,287017,287014,287013,287002,287001,287000,286991,286984,286975,286973,286966,286959,286953,286952,286950,286933,286932,286931,286930,286929,286926,286925,286924,286920,286910,286892,286889,286878,286874,286873,286871,286870,286861,286859,286858,286854,286852,286851,286849,286848,286847,286846,286845,286840,286834,286822,286820,286819,286818,286812,286810,286803,286799,286796,286795,286784,286771,286768,286761,286759,286756,286748,286746,286744,286738,286736,286733,286710,286709,286708,286706,286705,286688,286682,286680,286677,286670,286669,286657,286649,286646,286644,286641,286639,286632,286626,286625,286624,286617,286589,286588,286586,286582,286576,286573,286571,286570,286567,286561,286559,286554,286553,286551,286550,286547,286539,286536,286535,286534,286533,286531,286527,286515,286514,286512,286510,286509,286504,286501,286500,286497,286495,286480,286477,286476,286465,286462,286455,286444,286443,286442,286433,286432,286430,286429,286425,286423,286420,286419,286418,286417,286416,286405,286404,286397,286394,286389,286381,286380,286374,286361,286360,286359,286357,286356,286355,286347,286339,286338,286324,286322,286319,286317,286314,286310,286307,286299,286292,286288,286287,286286,286278,286275,286271,286265,286256,286252,286251,286250,286249,286244,286239,286235,286234,286227,286225,286216,286215,286214,286213,286212,286209,286206,286204,286198,286191,286178,286171,286163,286162,286161,286158,286149,286144,286134,286133,286125,286114,286109,286108,286105,286087,286084,286083,286076,286073,286062,286060,286059,286057,286055,286045,286044,286042,286039,286036,286029,286026,286022,286021,286013,286012,285997,285993,285992,285986,285984,285981,285979,285976,285968,285964,285950,285946,285944,285941,285934,285932,285927,285922,285921,285919,285912,285910,285909,285908,285904,285903,285897,285894,285890,285888,285884,285876,285872,285869,285864,285860,285859,285858,285856,285854,285853,285852,285846,285845,285841,285837,285835,285834,285830,285829,285821,285819,285807,285805,285803,285800,285795,285785,285782,285779,285776,285771,285758,285754,285749,285748,285743,285740,285730,285728,285727,285724,285722,285720,285716,285715,285713,285712,285703,285692,285691,285690,285688,285687,285686,285684,285682,285681,285680,285674,285673,285672,285670,285669,285661,285660,285645,285642,285641,285640,285628,285625,285624,285621,285616,285612,285606,285605,285602,285598,285590,285587,285581,285576,285575,285570,285569,285567,285562,285560,285559,285555,285553,285552,285550,285544,285533,285529,285528,285525,285515,285514,285509,285505,285504,285493,285487,285481,285475,285463,285460,285459,285458,285438,285437,285436,285433,285431,285428,285422,285421,285413,285411,285410,285408,285404,285398,285396,285394,285391,285383,285381,285380,285360,285357,285351,285348,285346,285343,285342,285341,285337,285336,285329,285324,285323,285321,285320,285315,285314,285312,285311,285308,285304,285297,285290,285276,285264,285249,285245,285243,285242,285240,285239,285227,285226,285215,285209,285208,285206,285196,285194,285193,285189,285184,285177,285176,285175,285171,285161,285160,285155,285153,285150,285149,285141,285137,285136,285134,285127,285119,285118,285111,285109,285108,285106,285103,285101,285096,285095,285092,285079,285076,285063,285047,285043,285037,285035,285025,285018,285013,285012,285008,284996,284994,284991,284987,284985,284984,284981,284978,284975,284973,284963,284962,284957,284949,284946,284944,284940,284936,284935,284932,284931,284928,284927,284921,284909,284905,284902,284900,284898,284885,284882,284880,284877,284868,284861,284859,284848,284844,284836,284835,284830,284828,284824,284823,284820,284817,284812,284809,284808,284805,284799,284796,284795,284794,284792,284787,284780,284773,284768,284767,284761,284759,284749,284746,284743,284742,284741,284738,284737,284735,284734,284732,284731,284729,284720,284718,284715,284714,284713,284711,284710,284709,284706,284698,284694,284693,284690,284682,284679,284678,284677,284676,284675,284673,284672,284657,284656,284654,284642,284641,284634,284626,284624,284623,284622,284620,284617,284611,284602,284601,284599,284596,284593,284590,284583,284582,284571,284567,284560,284557,284548,284544,284538,284537,284532,284528,284526,284520,284519,284507,284504,284503,284501,284489,284479,284477,284471,284470,284466,284461,284457,284456,284444,284434,284416,284413,284406,284402,284395,284390,284384,284382,284378,284377,284376,284373,284372,284368,284366,284360,284359,284358,284354,284352,284350,284349,284346,284345,284342,284339,284338,284333,284332,284330,284327,284325,284324,284322,284316,284312,284307,284306,284305,284303,284300,284296,284295,284288,284287,284264,284261,284257,284250,284246,284242,284231,284229,284227,284226,284224,284222,284221,284220,284219,284218,284217,284213,284209,284203,284201,284192,284190,284186,284185,284167,284165,284163,284160,284147,284142,284140,284139,284133,284129,284128,284126,284118,284117,284116,284112,284109,284108,284107,284106,284104,284100,284093,284089,284085,284084,284083,284080,284079,284074,284072,284071,284067,284065,284060,284058,284057,284055,284054,284051,284050,284046,284044,284041,284036,284032,284031,284030,284019,284018,284012,284010,284006,284004,284003,284001,283992,283987,283978,283976,283965,283964,283963,283962,283959,283958,283957,283954,283951,283950,283943,283938,283937,283936,283933,283930,283928,283924,283922,283920,283919,283913,283912,283908,283899,283896,283895,283894,283891,283889,283885,283884,283880,283879,283878,283869,283868,283867,283864,283862,283861,283860,283858,283849,283845,283844,283842,283835,283827,283823,283820,283818,283815,283805,283800,283798,283795,283794,283790,283789,283779,283778,283769,283768,283762,283757,283755,283753,283747,283746,283742,283740,283739,283738,283734,283728,283727,283725,283723,283721,283719,283716,283713,283712,283710,283706,283700,283695,283678,283676,283674,283668,283666,283663,283662,283655,283649,283645,283644,283641,283636,283634,283631,283628,283627,283624,283608,283605,283604,283603,283601,283599,283596,283592,283590,283588,283585,283580,283578,283574,283571,283568,283565,283560,283553,283546,283544,283541,283538,283536,283531,283530,283528,283524,283521,283520,283519,283513,283509,283505,283504,283502,283499,283488,283486,283483,283478,283476,283472,283470,283468,283467,283461,283454,283449,283446,283445,283441,283440,283434,283432,283431,283430,283428,283426,283424,283423,283415,283413,283408,283407,283406,283400,283390,283385,283384,283383,283378,283374,283373,283371,283370,283367,283361,283355,283354,283342,283338,283336,283334,283333,283332,283327,283326,283325,283324,283322,283321,283319,283318,283315,283312,283310,283307,283302,283301,283293,283289,283275,283271,283270,283269,283265,283263,283261,283256,283254,283251,283249,283247,283244,283239,283232,283225,283221,283219,283218,283217,283214,283208,283207,283206,283205,283200,283199,283196,283195,283188,283181,283168,283167,283166,283165,283163,283162,283153,283151,283150,283149,283148,283146,283136,283131,283129,283124,283122,283121,283118,283112,283108,283096,283092,283090,283088,283087,283084,283083,283080,283077,283076,283071,283064,283052,283050,283045,283039,283038,283033,283021,283018,283013,283012,283011,283007,283005,283002,283000,282997,282988,282979,282978,282976,282972,282958,282957,282955,282954,282953,282949,282943,282940,282939,282933,282927,282915,282911,282907,282903,282898,282895,282893,282889,282883,282879,282866,282864,282861,282860,282858,282855,282853,282851,282848,282846,282845,282837,282831,282825,282824,282823,282821,282820,282819,282816,282814,282812,282808,282807,282802,282796,282787,282783,282782,282781,282775,282765,282764,282751,282745,282734,282724,282721,282715,282714,282713,282707,282702,282700,282696,282689,282688,282684,282681,282680,282679,282675,282669,282665,282664,282657,282654,282643,282642,282636,282633,282631,282620,282614,282613,282610,282605,282604,282601,282600,282589,282582,282579,282578,282577,282576,282575,282567,282565,282553,282551,282550,282549,282546,282543,282542,282541,282539,282537,282531,282530,282527,282520,282519,282514,282512,282510,282509,282502,282497,282486,282480,282479,282478,282471,282470,282466,282465,282464,282460,282459,282458,282454,282449,282445,282444,282442,282435,282434,282433,282428,282427,282419,282418,282417,282416,282414,282407,282404,282403,282401,282397,282396,282394,282393,282387,282386,282385,282384,282377,282376,282367,282364,282356,282354,282348,282341,282340,282339,282338,282333,282332,282317,282313,282310,282308,282301,282297,282293,282289,282285,282279,282271,282270,282268,282267,282264,282260,282252,282248,282247,282240,282237,282231,282223,282218,282216,282214,282213,282210,282208,282197,282196,282186,282183,282182,282175,282173,282170,282168,282164,282160,282153,282151,282144,282139,282138,282135,282130,282124,282122,282120,282117,282116,282115,282114,282113,282108,282103,282102,282091,282089,282085,282068,282067,282062,282053,282047,282042,282041,282036,282034,282031,282028,282026,282018,282014,282012,282011,282010,282007,282003,282001,282000,281994,281989,281988,281984,281982,281981,281975,281969,281964,281960,281956,281946,281942,281935,281931,281930,281923,281921,281917,281916,281907,281887,281886,281885,281884,281882,281873,281869,281868,281866,281855,281841,281837,281836,281835,281834,281833,281826,281824,281820,281811,281803,281799,281797,281794,281791,281788,281780,281778,281777,281776,281775,281772,281770,281768,281766,281760,281747,281746,281740,281732,281729,281724,281722,281716,281715,281714,281713,281709,281704,281702,281701,281699,281694,281692,281690,281682,281679,281676,281664,281663,281655,281650,281638,281635,281634,281613,281597,281594,281593,281591,281590,281589,281583,281580,281576,281575,281571,281566,281561,281560,281554,281547,281546,281545,281544,281537,281529,281519,281518,281514,281513,281511,281503,281502,281488,281475,281474,281471,281461,281459,281457,281456,281448,281447,281441,281430,281418,281414,281412,281405,281397,281394,281392,281379,281377,281376,281375,281356,281354,281351,281347,281346,281340,281336,281333,281332,281329,281322,281321,281318,281313,281312,281308,281307,281306,281305,281298,281293,281292,281289,281287,281286,281284,281276,281275,281271,281265,281260,281259,281254,281241,281239,281236,281235,281233,281232,281231,281227,281224,281217,281209,281204,281203,281201,281190,281185,281181,281180,281171,281166,281165,281164,281155,281153,281152,281146,281145,281141,281140,281139,281138,281135,281134,281133,281130,281128,281124,281118,281112,281109,281106,281104,281102,281098,281096,281095,281094,281092,281087,281085,281081,281078,281072,281069,281068,281067,281065,281050,281049,281046,281045,281042,281038,281036,281029,281020,281019,281013,281010,281008,281006,281004,281002,280998,280996,280995,280990,280989,280988,280987,280985,280984,280976,280975,280973,280971,280970,280953,280949,280948,280946,280945,280943,280941,280939,280938,280937,280936,280935,280934,280931,280924,280920,280914,280911,280908,280907,280902,280883,280882,280878,280877,280875,280874,280854,280852,280848,280842,280837,280835,280827,280825,280822,280819,280818,280816,280814,280811,280806,280801,280800,280795,280793,280788,280785,280783,280780,280776,280775,280774,280762,280757,280748,280747,280744,280734,280732,280728,280719,280713,280710,280709,280705,280704,280699,280692,280689,280687,280684,280683,280676,280675,280674,280673,280667,280666,280662,280660,280656,280651,280648,280642,280641,280634,280631,280630,280623,280621,280619,280610,280608,280607,280601,280599,280596,280593,280591,280583,280568,280566,280560,280556,280554,280550,280548,280545,280544,280543,280542,280539,280538,280535,280534,280532,280531,280522,280508,280505,280494,280488,280486,280483,280482,280479,280477,280475,280474,280473,280472,280458,280455,280451,280450,280447,280446,280440,280432,280431,280426,280425,280422,280411,280399,280396,280393,280391,280388,280386,280381,280377,280369,280366,280365,280360,280354,280348,280347,280342,280341,280338,280336,280333,280327,280326,280322,280321,280318,280316,280303,280300,280298,280297,280287,280284,280274,280273,280266,280265,280264,280263,280261,280253,280244,280243,280238,280231,280230,280227,280225,280223,280221,280220,280218,280213,280212,280211,280198,280197,280190,280188,280182,280179,280178,280175,280174,280172,280164,280162,280158,280157,280156,280155,280154,280152,280150,280149,280146,280143,280142,280141,280140,280138,280137,280133,280129,280122,280118,280114,280107,280105,280102,280095,280083,280074,280070,280069,280067,280066,280065,280064,280063,280061,280060,280058,280057,280053,280052,280049,280043,280039,280037,280028,280027,280024,280023,280022,280020,280018,280016,280013,280012,280010,280009,280006,279995,279993,279990,279988,279986,279983,279976,279975,279973,279961,279960,279949,279947,279941,279939,279938,279931,279922,279921,279920,279919,279917,279911,279897,279893,279890,279882,279878,279877,279868,279867,279866,279863,279859,279857,279852,279849,279847,279843,279840,279838,279831,279819,279813,279812,279811,279810,279802,279799,279796,279793,279788,279786,279778,279777,279773,279771,279770,279769,279767,279766,279765,279760,279756,279753,279751,279746,279742,279740,279735,279719,279717,279712,279708,279707,279703,279701,279700,279699,279693,279688,279685,279684,279682,279680,279678,279677,279675,279667,279664,279654,279650,279645,279642,279639,279637,279636,279629,279628,279625,279622,279620,279619,279616,279615,279611,279610,279608,279603,279602,279601,279600,279599,279597,279595,279594,279589,279580,279572,279570,279568,279562,279559,279557,279553,279551,279545,279544,279542,279540,279537,279532,279529,279527,279526,279521,279518,279516,279515,279514,279512,279510,279506,279505,279504,279500,279498,279494,279491,279487,279486,279479,279477,279476,279468,279460,279459,279455,279453,279451,279447,279443,279438,279435,279426,279425,279416,279412,279411,279408,279407,279406,279401,279397,279391,279389,279388,279386,279385,279382,279378,279372,279371,279368,279367,279364,279363,279360,279358,279354,279349,279347,279346,279341,279339,279338,279337,279334,279331,279320,279315,279314,279313,279310,279307,279306,279304,279302,279301,279299,279298,279294,279287,279286,279285,279278,279273,279272,279271,279267,279264,279260,279258,279249,279246,279245,279244,279240,279239,279235,279229,279228,279227,279224,279213,279211,279203,279194,279191,279190,279188,279186,279184,279183,279182,279179,279176,279170,279168,279163,279161,279157,279156,279153,279148,279137,279132,279129,279121,279117,279115,279110,279106,279103,279092,279087,279085,279082,279076,279073,279072,279071,279068,279063,279053,279052,279050,279046,279045,279041,279039,279035,279027,279023,279022,279019,279016,279013,279012,279009,279008,279002,279001,278997,278988,278987,278985,278981,278980,278978,278973,278972,278970,278967,278959,278958,278955,278954,278953,278950,278949,278947,278944,278942,278941,278931,278928,278926,278921,278917,278915,278913,278902,278899,278898,278897,278895,278894,278893,278892,278887,278886,278884,278879,278877,278875,278873,278872,278869,278868,278867,278859,278852,278850,278849,278848,278846,278843,278840,278839,278837,278835,278833,278831,278828,278825,278821,278817,278811,278801,278795,278788,278778,278769,278764,278761,278759,278757,278755,278751,278750,278747,278744,278742,278739,278738,278731,278718,278713,278707,278698,278697,278695,278694,278692,278688,278684,278683,278678,278675,278674,278666,278663,278658,278645,278639,278636,278631,278628,278621,278619,278617,278611,278609,278606,278603,278602,278598,278597,278581,278579,278578,278577,278569,278563,278562,278561,278558,278543,278540,278537,278536,278534,278530,278523,278522,278517,278512,278511,278510,278509,278507,278505,278503,278500,278497,278493,278492,278490,278479,278476,278465,278460,278459,278458,278455,278454,278447,278437,278431,278424,278419,278418,278413,278410,278409,278404,278402,278401,278400,278390,278388,278386,278378,278377,278375,278371,278369,278368,278366,278365,278363,278356,278353,278352,278351,278348,278345,278344,278343,278342,278341,278340,278339,278338,278334,278332,278331,278327,278325,278323,278321,278316,278313,278311,278295,278293,278292,278290,278288,278285,278284,278276,278274,278270,278266,278265,278264,278255,278254,278251,278246,278244,278236,278234,278228,278227,278226,278218,278214,278213,278207,278199,278191,278185,278183,278177,278175,278172,278169,278165,278164,278163,278161,278159,278153,278151,278145,278144,278143,278139,278135,278132,278127,278115,278114,278109,278108,278107,278104,278103,278099,278096,278094,278092,278087,278080,278079,278077,278075,278069,278067,278063,278055,278054,278053,278052,278049,278043,278035,278034,278033,278030,278029,278028,278025,278023,278022,278015,278014,278005,277999,277997,277991,277986,277978,277974,277973,277969,277963,277956,277955,277953,277952,277951,277949,277947,277941,277930,277925,277924,277918,277908,277904,277900,277892,277891,277880,277877,277875,277874,277872,277871,277868,277867,277866,277863,277858,277853,277851,277846,277839,277838,277830,277819,277816,277812,277809,277800,277797,277795,277793,277792,277789,277785,277784,277782,277781,277762,277760,277748,277747,277742,277741,277739,277737,277731,277730,277729,277727,277725,277724,277718,277715,277710,277705,277703,277702,277701,277695,277691,277688,277686,277684,277682,277680,277677,277674,277668,277665,277660,277652,277649,277647,277645,277632,277629,277626,277620,277615,277612,277611,277608,277606,277603,277596,277595,277581,277580,277577,277572,277571,277570,277567,277566,277565,277564,277550,277537,277533,277531,277528,277525,277523,277522,277518,277516,277511,277507,277500,277499,277498,277496,277492,277482,277478,277477,277476,277467,277461,277460,277457,277456,277448,277446,277433,277427,277425,277424,277422,277421,277420,277419,277417,277412,277408,277407,277403,277400,277393,277389,277388,277387,277383,277367,277366,277365,277362,277361,277359,277358,277355,277349,277348,277344,277343,277341,277339,277337,277325,277324,277316,277315,277312,277310,277306,277303,277302,277297,277295,277290,277288,277283,277280,277273,277272,277271,277269,277262,277260,277259,277256,277247,277246,277244,277240,277239,277238,277235,277234,277233,277232,277231,277230,277228,277225,277223,277222,277220,277217,277215,277209,277208,277205,277203,277191,277190,277184,277182,277179,277174,277173,277166,277164,277161,277160,277156,277153,277152,277151,277149,277148,277139,277136,277134,277131,277130,277127,277124,277120,277119,277117,277115,277109,277105,277103,277100,277098,277092,277089,277085,277084,277076,277069,277067,277065,277064,277062,277059,277058,277055,277051,277049,277046,277044,277038,277033,277032,277028,277027,277022,277021,277016,277005,277002,277001,277000,276999,276998,276997,276996,276995,276994,276993,276992,276991,276984,276982,276976,276973,276972,276971,276970,276967,276966,276961,276960,276953,276951,276948,276946,276945,276944,276942,276940,276938,276937,276934,276929,276928,276925,276923,276921,276920,276917,276915,276914,276912,276910,276909,276907,276906,276902,276899,276897,276895,276892,276888,276883,276881,276877,276876,276875,276874,276868,276862,276861,276852,276850,276849,276838,276836,276834,276830,276829,276823,276822,276821,276817,276806,276803,276802,276801,276800,276795,276787,276783,276773,276770,276765,276764,276753,276749,276746,276744,276742,276737,276736,276733,276730,276726,276718,276715,276714,276712,276709,276708,276705,276701,276700,276699,276692,276688,276685,276684,276683,276673,276663,276660,276655,276653,276651,276650,276648,276647,276646,276645,276640,276638,276635,276632,276631,276628,276624,276620,276619,276617,276616,276612,276611,276608,276597,276594,276591,276590,276585,276583,276582,276581,276580,276577,276575,276574,276567,276566,276563,276562,276555,276554,276553,276551,276550,276549,276548,276546,276544,276543,276538,276537,276536,276534,276531,276527,276526,276525,276522,276521,276514,276511,276509,276507,276505,276496,276495,276477,276476,276472,276469,276464,276460,276459,276445,276443,276442,276440,276438,276435,276432,276425,276424,276421,276415,276412,276403,276397,276393,276386,276383,276381,276374,276371,276369,276364,276362,276359,276358,276357,276356,276354,276348,276346,276341,276331,276328,276326,276324,276322,276317,276312,276311,276310,276309,276305,276304,276302,276301,276300,276299,276290,276289,276284,276281,276276,276274,276272,276268,276261,276259,276258,276257,276252,276251,276247,276245,276244,276243,276241,276240,276238,276234,276233,276223,276220,276219,276212,276211,276210,276203,276202,276199,276195,276189,276188,276183,276177,276175,276167,276162,276161,276155,276150,276148,276145,276141,276138,276134,276130,276125,276123,276099,276098,276096,276094,276093,276088,276087,276085,276082,276079,276076,276064,276061,276058,276057,276056,276055,276050,276049,276048,276047,276046,276041,276038,276032,276031,276030,276022,276021,276020,276015,276014,276012,276011,276009,276005,276004,276003,275998,275992,275984,275983,275982,275981,275978,275970,275969,275968,275965,275964,275963,275958,275950,275945,275943,275941,275934,275932,275930,275929,275928,275927,275923,275918,275917,275914,275913,275911,275910,275909,275904,275900,275899,275898,275893,275890,275888,275887,275880,275879,275877,275876,275873,275869,275864,275863,275858,275857,275855,275853,275850,275848,275843,275835,275834,275832,275831,275824,275816,275815,275813,275805,275800,275797,275792,275790,275788,275782,275778,275776,275773,275772,275769,275767,275757,275756,275754,275747,275745,275741,275740,275739,275736,275732,275730,275727,275726,275725,275724,275722,275715,275713,275708,275701,275698,275695,275694,275689,275684,275681,275677,275674,275671,275670,275669,275654,275650,275647,275639,275638,275637,275635,275634,275624,275623,275621,275614,275612,275598,275596,275595,275593,275591,275590,275588,275585,275580,275575,275573,275564,275562,275557,275555,275551,275544,275535,275533,275526,275523,275518,275517,275512,275511,275501,275500,275496,275492,275488,275487,275486,275480,275477,275475,275471,275466,275458,275449,275445,275431,275428,275426,275423,275421,275416,275412,275409,275408,275399,275395,275394,275382,275379,275377,275376,275372,275370,275359,275358,275355,275347,275327,275324,275322,275321,275319,275310,275305,275303,275302,275301,275286,275284,275269,275268,275257,275251,275249,275245,275244,275235,275232,275231,275228,275227,275222,275218,275217,275213,275211,275208,275207,275204,275202,275200,275199,275198,275197,275196,275189,275188,275183,275181,275180,275178,275176,275175,275170,275167,275164,275157,275156,275148,275139,275138,275137,275134,275131,275128,275127,275124,275114,275112,275110,275103,275102,275097,275095,275087,275085,275084,275080,275077,275068,275066,275063,275062,275060,275059,275056,275055,275053,275052,275050,275047,275041,275036,275030,275028,275027,275026,275022,275017,275016,275015,275014,275009,275006,274985,274984,274983,274975,274973,274965,274962,274959,274957,274956,274955,274951,274949,274945,274942,274938,274934,274932,274930,274929,274928,274925,274923,274915,274914,274913,274912,274906,274904,274900,274899,274897,274896,274893,274891,274889,274887,274882,274881,274876,274872,274871,274864,274861,274857,274856,274855,274853,274852,274847,274846,274836,274835,274829,274828,274827,274824,274823,274820,274819,274816,274814,274811,274810,274809,274808,274807,274806,274803,274800,274794,274793,274789,274778,274777,274773,274772,274769,274764,274763,274759,274757,274754,274753,274750,274748,274747,274746,274745,274743,274739,274735,274734,274731,274728,274722,274721,274718,274714,274713,274712,274711,274709,274705,274700,274698,274695,274690,274685,274683,274682,274681,274680,274678,274671,274670,274661,274660,274659,274658,274656,274655,274652,274651,274643,274642,274641,274640,274635,274633,274631,274625,274621,274616,274615,274614,274611,274608,274607,274598,274597,274588,274584,274583,274579,274574,274572,274571,274568,274566,274559,274558,274557,274554,274549,274548,274545,274544,274540,274535,274533,274528,274527,274524,274519,274517,274515,274514,274505,274501,274499,274498,274487,274484,274482,274475,274471,274470,274465,274464,274463,274462,274460,274459,274455,274444,274443,274437,274435,274433,274430,274426,274421,274420,274415,274413,274410,274409,274406,274402,274395,274390,274389,274384,274383,274382,274381,274377,274373,274371,274365,274356,274350,274349,274347,274345,274344,274342,274341,274336,274334,274327,274323,274320,274318,274315,274314,274305,274302,274301,274299,274296,274294,274291,274289,274287,274286,274285,274284,274283,274279,274278,274277,274273,274269,274261,274258,274251,274248,274241,274239,274237,274236,274235,274232,274231,274229,274228,274226,274224,274221,274218,274217,274216,274210,274203,274200,274199,274192,274191,274188,274187,274170,274169,274168,274167,274166,274164,274160,274150,274146,274145,274144,274142,274140,274138,274137,274136,274133,274132,274130,274126,274120,274116,274114,274112,274111,274103,274101,274096,274093,274090,274086,274083,274082,274080,274078,274076,274071,274067,274059,274056,274047,274044,274040,274035,274031,274027,274023,274020,274011,274009,274007,274003,273987,273986,273974,273971,273969,273967,273965,273962,273955,273954,273953,273950,273941,273935,273934,273919,273917,273911,273908,273906,273902,273898,273896,273891,273887,273883,273872,273871,273866,273864,273863,273861,273857,273856,273854,273847,273843,273842,273841,273834,273823,273822,273821,273815,273814,273813,273807,273805,273803,273801,273800,273794,273793,273791,273790,273789,273787,273786,273777,273776,273773,273770,273769,273767,273764,273762,273759,273754,273753,273736,273735,273734,273733,273732,273719,273715,273712,273711,273696,273694,273692,273687,273680,273678,273676,273668,273662,273661,273656,273654,273653,273643,273641,273633,273630,273624,273621,273619,273617,273609,273608,273606,273603,273602,273598,273590,273584,273582,273581,273579,273568,273565,273555,273550,273545,273542,273539,273537,273535,273534,273530,273526,273521,273517,273514,273511,273509,273505,273496,273479,273477,273476,273475,273474,273471,273469,273464,273462,273461,273460,273452,273448,273445,273430,273429,273428,273425,273422,273417,273412,273410,273409,273407,273398,273396,273385,273384,273382,273380,273377,273374,273373,273371,273366,273361,273360,273355,273352,273351,273347,273346,273345,273340,273339,273336,273332,273330,273325,273324,273323,273321,273315,273312,273311,273309,273308,273296,273295,273294,273287,273285,273282,273271,273270,273268,273267,273263,273261,273260,273252,273249,273246,273243,273239,273238,273233,273229,273227,273225,273223,273218,273212,273207,273206,273200,273199,273198,273195,273194,273192,273172,273171,273170,273169,273165,273160,273158,273157,273148,273146,273145,273144,273142,273141,273140,273135,273130,273128,273122,273121,273115,273114,273108,273102,273099,273097,273095,273094,273092,273091,273081,273080,273078,273066,273065,273064,273062,273060,273058,273057,273053,273049,273048,273046,273034,273032,273030,273029,273027,273026,273023,273020,273013,273005,273004,272997,272995,272994,272987,272986,272984,272976,272971,272968,272967,272965,272964,272963,272959,272956,272938,272935,272934,272933,272928,272927,272926,272924,272918,272914,272907,272905,272903,272902,272900,272899,272897,272896,272895,272893,272890,272887,272886,272885,272884,272883,272882,272880,272879,272877,272876,272874,272873,272869,272868,272865,272858,272857,272854,272852,272847,272846,272843,272838,272835,272833,272832,272829,272828,272827,272826,272825,272817,272813,272810,272806,272805,272801,272787,272785,272782,272779,272776,272774,272772,272771,272767,272761,272754,272753,272751,272750,272746,272745,272743,272740,272738,272733,272730,272729,272725,272723,272721,272709,272708,272706,272705,272703,272697,272691,272690,272689,272685,272680,272675,272671,272666,272662,272661,272658,272657,272656,272651,272649,272647,272645,272641,272640,272639,272636,272635,272631,272628,272620,272618,272617,272615,272613,272612,272610,272605,272600,272593,272592,272579,272577,272569,272567,272564,272557,272556,272553,272551,272548,272547,272546,272545,272544,272541,272539,272533,272532,272531,272527,272520,272517,272515,272512,272511,272509,272507,272504,272499,272498,272490,272488,272484,272483,272481,272480,272473,272464,272462,272460,272455,272453,272450,272449,272448,272446,272445,272436,272434,272431,272430,272429,272427,272426,272424,272417,272413,272412,272404,272401,272398,272394,272393,272385,272377,272373,272372,272371,272369,272367,272355,272353,272351,272347,272345,272338,272337,272333,272325,272322,272321,272317,272312,272309,272307,272306,272302,272299,272289,272288,272287,272284,272278,272275,272274,272273,272269,272268,272266,272264,272263,272262,272258,272257,272255,272253,272249,272246,272243,272236,272235,272234,272227,272226,272225,272224,272221,272220,272217,272214,272211,272210,272209,272205,272202,272201,272200,272199,272195,272192,272191,272190,272188,272186,272181,272180,272179,272170,272169,272165,272163,272159,272157,272146,272145,272143,272142,272141,272140,272139,272132,272128,272122,272121,272119,272116,272113,272111,272105,272092,272085,272079,272078,272076,272072,272067,272059,272049,272046,272042,272035,272034,272032,272028,272024,272021,272015,272010,272004,272000,271996,271987,271985,271975,271973,271968,271965,271961,271952,271951,271946,271944,271943,271942,271941,271939,271938,271937,271934,271927,271925,271917,271916,271910,271906,271905,271903,271902,271889,271884,271883,271880,271878,271877,271874,271870,271867,271863,271859,271855,271848,271846,271844,271843,271842,271841,271840,271836,271835,271831,271829,271825,271822,271820,271818,271817,271816,271814,271806,271805,271801,271793,271792,271791,271787,271786,271780,271772,271769,271767,271756,271752,271751,271750,271749,271747,271746,271745,271744,271742,271740,271738,271729,271714,271713,271702,271696,271691,271690,271689,271686,271684,271677,271676,271667,271663,271655,271653,271648,271647,271642,271641,271637,271636,271618,271617,271616,271605,271604,271600,271596,271589,271586,271585,271584,271577,271576,271575,271567,271566,271563,271559,271558,271557,271554,271548,271542,271541,271540,271539,271538,271532,271530,271527,271517,271516,271510,271498,271496,271491,271490,271489,271483,271480,271470,271467,271458,271456,271455,271452,271450,271445,271443,271434,271433,271429,271428,271412,271411,271403,271398,271396,271395,271388,271382,271379,271376,271375,271370,271367,271364,271358,271355,271353,271351,271350,271349,271348,271347,271346,271344,271343,271335,271326,271317,271312,271311,271306,271304,271302,271298,271296,271294,271284,271276,271275,271273,271269,271268,271265,271263,271259,271253,271249,271246,271241,271239,271234,271225,271222,271218,271215,271209,271208,271204,271202,271201,271200,271198,271197,271190,271185,271183,271179,271178,271176,271174,271173,271172,271170,271169,271163,271161,271160,271159,271155,271154,271153,271151,271149,271147,271143,271138,271136,271135,271133,271131,271130,271127,271126,271125,271117,271102,271100,271097,271089,271084,271083,271074,271073,271069,271066,271062,271060,271059,271057,271055,271054,271049,271043,271042,271039,271034,271033,271032,271031,271029,271026,271025,271022,271021,271020,271016,271014,271012,271011,271009,271004,271002,271001,270996,270994,270993,270988,270987,270982,270981,270978,270975,270972,270969,270968,270967,270965,270961,270957,270949,270948,270947,270940,270937,270936,270935,270930,270924,270923,270918,270917,270909,270906,270894,270891,270890,270888,270886,270884,270882,270881,270880,270867,270864,270863,270858,270857,270853,270852,270849,270848,270841,270839,270837,270835,270828,270816,270815,270806,270804,270795,270793,270791,270785,270784,270781,270767,270761,270760,270758,270755,270751,270745,270739,270737,270734,270730,270728,270726,270724,270723,270721,270719,270717,270713,270712,270711,270710,270707,270698,270690,270687,270671,270670,270668,270667,270666,270665,270663,270659,270657,270656,270654,270653,270652,270651,270645,270643,270641,270640,270638,270633,270632,270631,270630,270628,270625,270621,270620,270615,270614,270613,270612,270610,270606,270605,270604,270601,270600,270595,270594,270593,270588,270585,270583,270582,270578,270574,270571,270569,270568,270565,270561,270554,270549,270548,270547,270546,270545,270542,270536,270528,270527,270524,270523,270520,270519,270517,270515,270511,270509,270505,270496,270495,270494,270493,270490,270489,270488,270483,270478,270475,270469,270467,270466,270465,270462,270460,270453,270448,270445,270440,270439,270438,270437,270435,270430,270425,270410,270409,270408,270403,270402,270400,270399,270398,270397,270396,270394,270389,270388,270385,270384,270379,270375,270373,270368,270366,270365,270363,270361,270359,270358,270349,270345,270342,270340,270338,270337,270331,270330,270324,270322,270321,270315,270314,270310,270309,270307,270296,270294,270291,270290,270286,270283,270281,270280,270277,270275,270272,270268,270265,270264,270263,270262,270261,270258,270249,270242,270241,270240,270239,270237,270231,270229,270224,270222,270221,270218,270215,270213,270209,270203,270201,270198,270196,270193,270190,270188,270184,270179,270178,270164,270162,270161,270160,270158,270157,270154,270153,270147,270146,270145,270142,270137,270135,270134,270131,270130,270127,270120,270119,270118,270112,270111,270109,270105,270104,270093,270091,270090,270089,270088,270086,270083,270082,270080,270078,270077,270076,270074,270066,270065,270053,270052,270051,270050,270045,270044,270043,270042,270041,270040,270039,270035,270033,270031,270028,270023,270021,270017,270007,270002,269999,269996,269995,269991,269987,269986,269985,269981,269980,269976,269975,269963,269959,269955,269954,269953,269951,269944,269938,269936,269933,269927,269926,269924,269923,269918,269917,269916,269914,269912,269910,269905,269901,269898,269897,269892,269891,269888,269886,269885,269884,269883,269877,269876,269874,269871,269865,269863,269860,269856,269849,269843,269842,269841,269836,269826,269824,269822,269821,269819,269817,269815,269813,269812,269808,269795,269794,269792,269791,269781,269780,269779,269778,269768,269765,269764,269763,269759,269756,269755,269753,269750,269749,269748,269747,269746,269741,269739,269736,269735,269732,269731,269730,269728,269727,269720,269713,269710,269700,269699,269697,269693,269690,269687,269686,269685,269684,269680,269677,269676,269674,269673,269668,269667,269663,269662,269661,269656,269654,269653,269651,269648,269646,269645,269644,269642,269640,269639,269637,269635,269633,269630,269628,269627,269622,269621,269620,269615,269613,269610,269607,269604,269602,269598,269596,269589,269586,269585,269583,269582,269578,269577,269576,269575,269573,269571,269569,269562,269561,269560,269559,269558,269556,269554,269552,269551,269548,269546,269544,269543,269541,269539,269538,269537,269536,269535,269532,269530,269520,269519,269518,269517,269516,269514,269509,269505,269498,269496,269492,269490,269486,269485,269477,269471,269470,269469,269463,269460,269456,269453,269445,269439,269435,269434,269427,269423,269418,269414,269413,269411,269408,269407,269405,269402,269395,269392,269388,269387,269384,269381,269377,269375,269373,269371,269368,269365,269364,269363,269361,269359,269358,269351,269347,269343,269341,269339,269332,269331,269321,269320,269312,269311,269310,269309,269308,269306,269301,269299,269295,269293,269288,269285,269281,269279,269277,269273,269270,269268,269266,269263,269255,269253,269252,269251,269243,269242,269241,269237,269232,269231,269225,269223,269215,269212,269211,269208,269198,269197,269192,269191,269187,269186,269172,269170,269163,269162,269153,269152,269151,269149,269148,269146,269140,269136,269134,269131,269130,269128,269126,269125,269122,269116,269115,269113,269112,269110,269109,269105,269102,269100,269098,269096,269095,269090,269080,269075,269073,269069,269063,269062,269059,269058,269057,269056,269054,269052,269051,269049,269048,269046,269043,269040,269037,269034,269032,269030,269027,269024,269022,269021,269020,269018,269014,269012,269006,269002,268997,268996,268995,268989,268987,268982,268978,268975,268973,268970,268969,268968,268961,268957,268945,268936,268935,268932,268930,268929,268923,268915,268909,268908,268905,268902,268900,268898,268897,268896,268891,268890,268887,268884,268883,268879,268871,268870,268865,268862,268857,268856,268848,268844,268843,268841,268838,268832,268827,268823,268816,268815,268813,268810,268805,268804,268802,268797,268793,268792,268782,268780,268768,268763,268760,268759,268758,268755,268741,268738,268735,268716,268714,268711,268708,268705,268703,268699,268696,268695,268689,268679,268676,268673,268659,268657,268656,268652,268651,268649,268648,268644,268637,268636,268632,268628,268625,268624,268615,268614,268612,268609,268597,268596,268595,268590,268587,268584,268583,268582,268580,268577,268575,268569,268561,268558,268557,268554,268552,268549,268532,268530,268525,268519,268516,268513,268511,268509,268503,268498,268495,268494,268490,268488,268485,268483,268477,268470,268469,268463,268460,268459,268457,268456,268454,268443,268440,268437,268425,268420,268419,268414,268413,268412,268404,268402,268401,268396,268395,268390,268388,268387,268383,268382,268380,268379,268375,268374,268368,268367,268366,268365,268362,268361,268358,268357,268356,268354,268353,268351,268349,268346,268339,268334,268333,268329,268323,268318,268317,268314,268293,268287,268286,268276,268274,268273,268272,268269,268262,268259,268258,268257,268254,268253,268251,268250,268249,268245,268235,268234,268230,268229,268228,268227,268225,268222,268219,268216,268214,268208,268207,268199,268192,268190,268189,268184,268178,268177,268175,268164,268160,268159,268155,268148,268146,268145,268142,268136,268131,268130,268124,268123,268121,268118,268116,268113,268111,268108,268104,268100,268094,268086,268082,268081,268079,268077,268067,268058,268056,268054,268051,268046,268042,268038,268031,268030,268029,268024,268023,268022,268020,268018,268013,268010,268004,268003,267998,267997,267993,267992,267991,267989,267987,267975,267974,267973,267972,267966,267964,267962,267961,267957,267952,267949,267946,267942,267932,267931,267930,267929,267924,267920,267916,267915,267908,267906,267899,267898,267889,267887,267886,267883,267882,267879,267873,267871,267866,267864,267857,267854,267848,267846,267845,267841,267840,267836,267835,267833,267827,267826,267825,267822,267821,267816,267813,267809,267805,267798,267797,267796,267792,267788,267785,267780,267778,267775,267774,267771,267765,267764,267762,267757,267745,267743,267737,267735,267734,267733,267725,267723,267722,267719,267718,267716,267711,267710,267709,267707,267706,267705,267703,267701,267700,267699,267698,267696,267695,267694,267692,267691,267689,267686,267685,267683,267678,267673,267671,267667,267665,267662,267661,267660,267657,267653,267652,267647,267646,267644,267642,267641,267640,267638,267636,267633,267621,267616,267614,267606,267598,267597,267593,267591,267581,267579,267578,267576,267575,267573,267571,267568,267566,267558,267556,267552,267548,267546,267545,267540,267529,267524,267522,267516,267513,267510,267509,267502,267501,267498,267496,267492,267478,267477,267470,267468,267457,267456,267450,267435,267434,267425,267423,267421,267420,267419,267414,267412,267409,267408,267407,267404,267397,267396,267393,267388,267387,267386,267383,267380,267353,267352,267350,267348,267347,267346,267342,267337,267332,267325,267323,267321,267319,267318,267313,267312,267309,267300,267299,267298,267296,267292,267290,267289,267285,267281,267279,267274,267272,267268,267263,267262,267255,267252,267247,267246,267245,267237,267232,267231,267230,267229,267225,267223,267221,267212,267206,267202,267200,267198,267195,267191,267189,267188,267184,267181,267180,267177,267175,267169,267168,267159,267152,267145,267144,267143,267142,267138,267137,267135,267129,267128,267125,267122,267121,267119,267118,267116,267113,267110,267109,267108,267095,267094,267091,267089,267083,267081,267076,267074,267070,267068,267065,267061,267056,267048,267047,267046,267028,267026,267024,267022,267017,267015,267012,267010,267003,267001,266997,266996,266986,266978,266976,266975,266970,266963,266961,266957,266955,266951,266950,266948,266935,266930,266927,266926,266925,266924,266922,266921,266920,266917,266915,266911,266910,266909,266908,266904,266902,266900,266899,266896,266894,266893,266890,266887,266886,266881,266876,266875,266874,266871,266870,266863,266862,266861,266853,266851,266850,266844,266843,266841,266839,266836,266835,266831,266830,266825,266824,266823,266816,266813,266812,266810,266809,266806,266801,266799,266784,266783,266782,266781,266780,266778,266772,266765,266760,266757,266752,266751,266746,266744,266743,266742,266732,266730,266729,266718,266709,266708,266704,266703,266701,266700,266697,266695,266687,266685,266684,266677,266675,266673,266668,266667,266665,266662,266660,266656,266651,266642,266641,266636,266635,266629,266627,266625,266616,266610,266608,266595,266593,266592,266584,266583,266574,266570,266564,266561,266557,266550,266540,266538,266536,266533,266531,266529,266525,266523,266519,266516,266513,266512,266503,266502,266501,266499,266496,266494,266489,266487,266484,266483,266480,266475,266471,266465,266460,266457,266456,266448,266444,266436,266435,266427,266425,266424,266423,266413,266411,266410,266409,266405,266401,266400,266398,266395,266392,266391,266390,266385,266384,266382,266381,266378,266377,266376,266375,266374,266371,266369,266363,266359,266358,266357,266354,266353,266351,266347,266345,266342,266341,266340,266336,266334,266333,266329,266321,266320,266318,266315,266303,266302,266300,266292,266290,266287,266279,266264,266259,266257,266254,266252,266247,266242,266241,266240,266239,266236,266235,266234,266232,266226,266223,266221,266219,266217,266216,266215,266214,266212,266209,266206,266201,266199,266197,266195,266194,266189,266187,266181,266176,266166,266164,266163,266162,266157,266156,266151,266148,266146,266145,266144,266140,266138,266136,266135,266127,266124,266120,266119,266116,266115,266111,266107,266106,266102,266095,266088,266082,266081,266080,266074,266073,266072,266070,266065,266062,266057,266056,266055,266053,266050,266047,266042,266038,266037,266035,266032,266015,266008,266003,266002,265995,265992,265991,265989,265985,265983,265981,265977,265976,265975,265974,265971,265958,265956,265952,265949,265935,265932,265929,265922,265919,265918,265915,265911,265903,265901,265891,265888,265884,265881,265879,265874,265869,265868,265863,265862,265860,265858,265856,265855,265854,265849,265841,265838,265836,265833,265832,265829,265828,265827,265825,265824,265819,265817,265816,265815,265812,265811,265810,265809,265807,265800,265797,265795,265793,265792,265789,265788,265787,265785,265781,265779,265775,265773,265771,265770,265765,265759,265754,265753,265751,265750,265746,265738,265737,265733,265731,265721,265712,265710,265704,265703,265701,265698,265696,265691,265690,265689,265687,265685,265683,265680,265675,265670,265669,265668,265667,265665,265659,265655,265649,265647,265638,265636,265633,265627,265626,265616,265611,265608,265605,265597,265596,265590,265587,265585,265579,265572,265569,265568,265566,265564,265560,265556,265555,265554,265553,265552,265551,265550,265547,265545,265543,265536,265526,265523,265522,265521,265518,265517,265512,265506,265503,265500,265498,265493,265490,265487,265486,265484,265483,265479,265477,265473,265472,265469,265467,265462,265461,265459,265458,265453,265452,265451,265449,265442,265441,265439,265437,265434,265427,265426,265419,265418,265417,265414,265412,265407,265406,265405,265404,265397,265390,265384,265380,265379,265370,265367,265363,265360,265359,265357,265354,265350,265348,265335,265328,265321,265318,265315,265314,265313,265309,265307,265304,265301,265300,265295,265286,265285,265284,265283,265279,265277,265269,265268,265265,265264,265261,265258,265251,265248,265243,265241,265236,265226,265225,265224,265223,265220,265218,265214,265212,265211,265203,265202,265199,265198,265196,265195,265193,265192,265188,265186,265184,265182,265180,265179,265177,265176,265172,265171,265170,265166,265164,265159,265158,265157,265154,265151,265148,265141,265137,265136,265134,265130,265129,265127,265123,265117,265115,265114,265113,265106,265105,265104,265103,265102,265101,265099,265096,265094,265093,265091,265080,265076,265075,265069,265064,265056,265055,265050,265048,265047,265044,265032,265030,265023,265022,265021,265019,265017,265015,265010,265006,265001,264995,264992,264984,264980,264973,264971,264968,264965,264964,264953,264947,264944,264941,264938,264937,264935,264929,264926,264923,264922,264916,264915,264910,264904,264903,264902,264900,264899,264893,264887,264881,264872,264871,264862,264861,264860,264859,264856,264855,264849,264847,264844,264841,264840,264836,264834,264833,264832,264827,264825,264822,264819,264818,264816,264810,264802,264799,264795,264792,264791,264790,264788,264785,264783,264782,264780,264779,264778,264776,264774,264773,264772,264769,264768,264767,264763,264762,264756,264752,264749,264742,264741,264739,264737,264736,264735,264728,264726,264720,264719,264718,264709,264708,264705,264702,264699,264697,264696,264695,264692,264691,264682,264680,264679,264678,264674,264670,264669,264667,264665,264664,264662,264659,264658,264656,264650,264647,264643,264633,264628,264624,264623,264620,264619,264617,264608,264607,264606,264598,264591,264590,264588,264587,264585,264584,264583,264580,264577,264573,264570,264568,264564,264563,264561,264559,264550,264547,264542,264539,264538,264537,264534,264525,264522,264521,264520,264519,264517,264516,264514,264513,264512,264511,264509,264507,264505,264502,264498,264497,264493,264492,264491,264488,264487,264483,264480,264472,264471,264470,264469,264463,264461,264460,264459,264457,264452,264448,264446,264441,264440,264438,264432,264420,264418,264413,264412,264408,264407,264406,264405,264401,264397,264386,264378,264373,264370,264365,264364,264359,264358,264357,264356,264352,264344,264342,264339,264337,264331,264329,264328,264325,264321,264319,264316,264315,264314,264310,264309,264307,264304,264297,264294,264292,264291,264289,264282,264279,264278,264276,264275,264274,264266,264265,264262,264256,264255,264248,264245,264230,264223,264216,264214,264212,264203,264200,264195,264182,264180,264179,264177,264176,264172,264171,264170,264169,264164,264163,264158,264157,264156,264153,264152,264150,264147,264138,264135,264126,264123,264118,264117,264114,264109,264103,264100,264095,264093,264092,264091,264087,264086,264083,264081,264079,264075,264074,264068,264067,264064,264060,264052,264050,264048,264047,264042,264041,264035,264026,264021,264020,264017,264008,264007,264004,264003,264002,264000,263996,263995,263994,263990,263988,263986,263983,263982,263980,263979,263978,263971,263968,263966,263961,263960,263958,263957,263955,263941,263940,263937,263934,263931,263930,263927,263925,263923,263917,263913,263912,263911,263910,263898,263893,263885,263881,263879,263872,263871,263868,263866,263861,263859,263857,263856,263847,263845,263843,263842,263841,263839,263837,263832,263829,263827,263826,263825,263822,263816,263806,263801,263800,263792,263791,263790,263784,263779,263778,263777,263775,263774,263773,263772,263770,263759,263758,263757,263753,263746,263744,263743,263740,263734,263725,263721,263720,263719,263717,263716,263715,263711,263706,263702,263699,263694,263693,263691,263684,263683,263681,263680,263674,263672,263668,263667,263666,263663,263662,263651,263648,263647,263646,263643,263639,263638,263637,263636,263634,263633,263631,263623,263622,263617,263615,263605,263603,263602,263601,263599,263587,263586,263585,263584,263578,263576,263573,263563,263562,263559,263557,263556,263549,263545,263544,263540,263538,263534,263525,263512,263508,263507,263502,263500,263497,263493,263488,263484,263482,263481,263475,263473,263471,263470,263469,263468,263467,263465,263464,263461,263458,263455,263446,263438,263436,263433,263432,263431,263430,263426,263421,263417,263411,263408,263403,263398,263394,263392,263389,263388,263382,263377,263373,263369,263359,263356,263355,263346,263339,263338,263336,263335,263334,263331,263329,263328,263327,263326,263324,263318,263315,263312,263309,263308,263305,263303,263300,263298,263297,263295,263292,263291,263288,263286,263284,263280,263279,263278,263277,263275,263270,263268,263267,263262,263261,263258,263249,263248,263235,263230,263224,263220,263218,263216,263215,263210,263208,263204,263200,263199,263196,263193,263191,263189,263186,263182,263179,263177,263171,263169,263166,263165,263163,263160,263156,263155,263150,263144,263134,263128,263121,263120,263119,263118,263112,263111,263109,263100,263093,263089,263084,263081,263074,263073,263072,263071,263062,263059,263051,263050,263049,263048,263042,263038,263037,263023,263017,263016,263012,263005,263003,263001,262999,262995,262991,262990,262983,262975,262969,262966,262961,262960,262944,262942,262937,262935,262934,262931,262930,262929,262928,262911,262910,262907,262899,262893,262881,262876,262875,262872,262869,262868,262866,262865,262864,262862,262847,262845,262841,262835,262833,262823,262822,262821,262820,262814,262811,262807,262804,262798,262789,262788,262786,262776,262775,262773,262771,262770,262762,262761,262758,262752,262749,262741,262739,262736,262735,262733,262731,262730,262727,262725,262723,262722,262714,262713,262710,262709,262708,262705,262704,262700,262698,262697,262692,262690,262683,262680,262679,262672,262670,262668,262663,262662,262655,262654,262652,262650,262649,262646,262639,262638,262637,262626,262625,262624,262623,262619,262618,262617,262615,262609,262605,262595,262590,262589,262588,262578,262570,262569,262563,262561,262559,262557,262550,262549,262548,262546,262542,262539,262538,262536,262531,262527,262526,262525,262524,262522,262519,262516,262511,262507,262504,262499,262492,262491,262490,262487,262486,262483,262477,262473,262470,262466,262465,262462,262461,262457,262455,262453,262451,262441,262439,262426,262417,262412,262409,262408,262404,262403,262400,262392,262390,262388,262383,262380,262378,262374,262373,262367,262365,262364,262360,262357,262356,262351,262347,262344,262341,262340,262339,262335,262334,262333,262329,262328,262311,262310,262307,262302,262295,262293,262288,262284,262282,262281,262279,262277,262273,262269,262264,262261,262256,262249,262244,262243,262242,262233,262232,262228,262226,262215,262209,262207,262200,262196,262195,262194,262193,262191,262188,262186,262176,262164,262162,262156,262155,262145,262143,262142,262131,262130,262127,262121,262118,262116,262108,262102,262101,262096,262089,262085,262084,262083,262081,262079,262075,262071,262069,262068,262064,262063,262062,262055,262053,262050,262049,262047,262043,262042,262039,262036,262035,262034,262033,262032,262031,262029,262028,262027,262026,262025,262023,262021,262012,262008,262007,262006,262004,262002,262001,262000,261999,261998,261997,261995,261994,261987,261986,261983,261982,261980,261975,261972,261970,261969,261963,261959,261955,261952,261951,261949,261946,261933,261925,261921,261919,261901,261894,261892,261891,261886,261880,261877,261876,261869,261863,261861,261855,261853,261844,261842,261841,261838,261834,261833,261831,261830,261821,261815,261808,261805,261804,261801,261798,261796,261788,261780,261779,261776,261773,261772,261770,261767,261762,261759,261750,261748,261747,261746,261743,261742,261740,261738,261737,261728,261726,261725,261724,261723,261715,261713,261710,261707,261706,261705,261701,261693,261690,261681,261679,261672,261670,261668,261665,261661,261660,261659,261656,261655,261653,261651,261647,261645,261642,261641,261638,261636,261631,261628,261624,261613,261607,261604,261600,261596,261592,261591,261590,261586,261585,261581,261580,261572,261571,261568,261566,261562,261555,261553,261552,261547,261546,261534,261533,261532,261525,261520,261519,261518,261514,261511,261505,261502,261501,261499,261497,261496,261494,261492,261490,261483,261482,261472,261469,261468,261467,261462,261460,261459,261452,261449,261448,261447,261440,261433,261429,261425,261422,261416,261412,261409,261405,261404,261388,261385,261379,261370,261368,261367,261366,261359,261352,261348,261345,261344,261342,261341,261340,261317,261316,261315,261314,261313,261312,261311,261306,261305,261303,261301,261300,261294,261292,261282,261276,261272,261266,261263,261262,261257,261255,261253,261252,261250,261248,261247,261244,261240,261236,261233,261232,261231,261230,261228,261220,261216,261203,261199,261194,261193,261190,261188,261187,261185,261184,261179,261175,261174,261169,261164,261159,261157,261149,261147,261146,261135,261134,261131,261127,261122,261121,261120,261117,261114,261112,261109,261106,261105,261104,261082,261079,261075,261074,261069,261066,261065,261063,261060,261054,261046,261045,261040,261039,261038,261037,261033,261032,261028,261017,261011,261004,261003,261000,260997,260995,260991,260989,260984,260978,260975,260974,260968,260966,260965,260964,260962,260961,260960,260958,260953,260951,260950,260948,260947,260943,260939,260938,260937,260935,260931,260928,260927,260926,260924,260919,260915,260914,260913,260902,260901,260892,260891,260888,260887,260883,260880,260874,260872,260870,260868,260865,260863,260862,260856,260849,260841,260839,260838,260835,260834,260833,260830,260828,260827,260824,260817,260815,260814,260812,260810,260807,260802,260801,260800,260798,260796,260792,260790,260785,260780,260776,260772,260771,260770,260768,260764,260761,260760,260759,260756,260753,260738,260732,260728,260727,260724,260718,260713,260712,260707,260704,260700,260693,260692,260690,260688,260687,260685,260683,260677,260676,260675,260674,260672,260671,260670,260669,260667,260663,260662,260655,260651,260649,260648,260647,260646,260645,260644,260643,260638,260637,260635,260634,260632,260631,260630,260626,260625,260622,260620,260615,260614,260610,260609,260607,260606,260592,260587,260584,260583,260582,260581,260579,260578,260572,260570,260568,260562,260557,260543,260541,260540,260536,260532,260530,260524,260523,260522,260516,260511,260500,260499,260494,260491,260489,260488,260481,260479,260478,260476,260467,260466,260461,260459,260453,260449,260447,260446,260445,260435,260428,260423,260422,260421,260420,260419,260418,260415,260413,260410,260406,260396,260392,260390,260382,260379,260375,260371,260370,260364,260363,260356,260354,260353,260347,260346,260345,260344,260340,260336,260335,260334,260333,260332,260330,260328,260325,260319,260318,260315,260303,260302,260301,260299,260296,260285,260279,260278,260276,260274,260273,260271,260264,260260,260256,260253,260249,260234,260232,260231,260228,260227,260226,260225,260222,260211,260209,260202,260200,260198,260196,260192,260191,260184,260182,260178,260172,260169,260168,260166,260160,260158,260155,260152,260151,260149,260148,260146,260145,260143,260141,260139,260135,260133,260132,260131,260127,260126,260123,260119,260111,260109,260100,260098,260096,260092,260091,260087,260084,260082,260081,260080,260069,260066,260065,260061,260058,260050,260045,260040,260039,260036,260022,260019,260014,260011,259999,259998,259994,259993,259991,259987,259986,259983,259980,259979,259977,259974,259971,259970,259969,259965,259964,259959,259956,259955,259954,259953,259952,259951,259950,259947,259945,259944,259941,259940,259938,259937,259934,259929,259928,259927,259926,259920,259918,259917,259915,259913,259908,259907,259902,259898,259897,259896,259892,259889,259887,259886,259885,259881,259880,259879,259873,259870,259866,259865,259860,259858,259855,259854,259853,259852,259849,259848,259846,259842,259841,259839,259826,259823,259820,259810,259809,259806,259802,259798,259795,259794,259788,259787,259778,259777,259770,259768,259767,259766,259765,259764,259753,259744,259743,259742,259725,259722,259721,259717,259714,259713,259709,259696,259695,259694,259680,259679,259678,259676,259675,259674,259670,259668,259667,259665,259664,259663,259659,259654,259652,259651,259647,259644,259643,259639,259638,259636,259635,259626,259623,259622,259616,259615,259614,259608,259606,259602,259601,259598,259596,259593,259591,259585,259582,259581,259580,259579,259577,259573,259572,259571,259569,259565,259564,259561,259560,259559,259554,259548,259547,259546,259545,259544,259542,259540,259535,259533,259532,259531,259528,259527,259523,259519,259518,259516,259514,259513,259512,259510,259509,259508,259496,259492,259486,259485,259484,259483,259482,259481,259479,259478,259477,259476,259474,259472,259471,259464,259461,259460,259458,259457,259453,259451,259445,259441,259440,259438,259435,259434,259432,259431,259425,259415,259413,259410,259406,259402,259398,259393,259392,259388,259385,259382,259379,259376,259374,259372,259367,259356,259350,259348,259345,259343,259341,259337,259336,259331,259330,259327,259323,259322,259317,259316,259314,259311,259307,259302,259295,259291,259286,259284,259279,259276,259273,259269,259264,259249,259248,259246,259245,259244,259242,259239,259238,259235,259234,259233,259222,259220,259217,259215,259213,259209,259207,259206,259205,259202,259199,259198,259194,259192,259181,259179,259175,259174,259168,259159,259157,259155,259150,259149,259146,259138,259135,259133,259132,259131,259130,259128,259122,259120,259119,259118,259115,259113,259107,259104,259101,259098,259096,259095,259094,259092,259090,259083,259082,259080,259078,259077,259072,259071,259068,259067,259065,259055,259053,259048,259046,259045,259039,259035,259034,259026,259022,259021,259019,259015,259014,259013,259010,259004,259003,258993,258985,258983,258982,258981,258980,258979,258977,258972,258964,258962,258961,258954,258949,258947,258946,258945,258944,258939,258937,258936,258928,258923,258920,258914,258913,258912,258910,258907,258905,258901,258898,258897,258895,258891,258890,258889,258886,258875,258873,258872,258863,258856,258854,258849,258847,258846,258845,258844,258843,258840,258837,258829,258827,258825,258823,258822,258819,258815,258813,258810,258807,258806,258805,258795,258791,258788,258787,258783,258780,258774,258773,258772,258770,258755,258754,258752,258751,258744,258742,258740,258737,258736,258730,258716,258711,258710,258708,258707,258706,258705,258702,258700,258699,258697,258696,258693,258689,258688,258686,258684,258683,258681,258680,258679,258678,258677,258674,258671,258668,258667,258660,258654,258648,258645,258644,258641,258640,258639,258638,258633,258632,258629,258628,258625,258624,258623,258620,258619,258616,258615,258612,258610,258609,258608,258607,258606,258605,258601,258600,258596,258593,258591,258588,258586,258585,258577,258576,258573,258572,258567,258563,258561,258556,258554,258552,258550,258549,258547,258546,258544,258540,258535,258532,258527,258524,258518,258517,258506,258505,258494,258489,258487,258481,258477,258476,258473,258468,258466,258464,258455,258453,258446,258440,258439,258438,258436,258435,258432,258430,258426,258424,258416,258413,258412,258408,258405,258400,258399,258396,258395,258394,258392,258390,258387,258383,258381,258374,258369,258367,258364,258362,258361,258358,258352,258348,258341,258335,258324,258323,258321,258318,258313,258309,258308,258307,258304,258299,258298,258296,258289,258286,258284,258280,258279,258275,258271,258268,258266,258265,258256,258255,258252,258242,258234,258233,258232,258231,258227,258223,258222,258219,258215,258213,258212,258210,258209,258208,258207,258203,258201,258191,258184,258181,258180,258177,258176,258172,258169,258165,258164,258162,258161,258158,258157,258154,258150,258148,258147,258146,258144,258137,258135,258130,258125,258124,258123,258122,258121,258120,258113,258112,258111,258100,258099,258097,258096,258093,258090,258087,258080,258072,258071,258068,258065,258064,258061,258059,258056,258055,258053,258045,258042,258041,258033,258030,258029,258026,258022,258021,258020,258018,258015,258013,258011,258010,258006,257998,257997,257996,257994,257991,257990,257989,257986,257983,257979,257972,257967,257965,257963,257962,257961,257959,257957,257956,257952,257949,257947,257946,257943,257940,257938,257937,257933,257928,257927,257918,257915,257912,257907,257906,257905,257904,257903,257888,257886,257885,257884,257883,257879,257878,257877,257875,257874,257872,257871,257868,257866,257862,257859,257854,257850,257842,257838,257835,257831,257830,257829,257825,257814,257813,257809,257806,257801,257792,257790,257784,257776,257771,257764,257761,257760,257754,257749,257746,257744,257741,257740,257736,257733,257731,257725,257723,257722,257719,257718,257703,257696,257694,257691,257690,257685,257683,257679,257677,257662,257659,257654,257651,257650,257649,257647,257646,257644,257643,257642,257635,257634,257632,257631,257630,257628,257619,257618,257617,257616,257610,257605,257603,257600,257596,257595,257594,257588,257587,257578,257576,257573,257569,257568,257567,257563,257562,257561,257558,257555,257553,257552,257548,257544,257538,257537,257533,257526,257524,257522,257521,257517,257515,257514,257505,257493,257489,257488,257487,257483,257477,257474,257468,257467,257464,257456,257451,257450,257448,257446,257443,257442,257440,257439,257436,257435,257434,257433,257429,257424,257421,257416,257415,257413,257409,257408,257407,257406,257405,257404,257400,257399,257395,257394,257391,257389,257386,257382,257381,257378,257371,257369,257357,257356,257348,257346,257345,257344,257343,257342,257341,257340,257339,257338,257335,257334,257333,257331,257330,257329,257327,257322,257316,257313,257311,257307,257304,257303,257301,257300,257296,257291,257289,257286,257281,257276,257273,257264,257262,257260,257258,257257,257254,257250,257246,257245,257244,257243,257242,257241,257239,257237,257234,257233,257221,257220,257219,257215,257210,257207,257205,257204,257199,257198,257189,257187,257182,257169,257167,257164,257160,257156,257150,257149,257136,257132,257130,257129,257127,257125,257121,257119,257116,257110,257102,257100,257096,257094,257093,257091,257087,257084,257077,257075,257072,257069,257065,257064,257061,257060,257059,257057,257053,257051,257048,257046,257038,257034,257029,257028,257026,257017,257016,257015,257014,257006,257005,257004,257003,257001,256996,256995,256991,256990,256982,256974,256973,256972,256970,256968,256967,256965,256954,256953,256952,256950,256948,256946,256945,256944,256936,256935,256934,256933,256929,256924,256922,256920,256919,256916,256911,256910,256908,256904,256901,256899,256893,256889,256888,256880,256870,256867,256865,256862,256858,256852,256849,256845,256842,256840,256839,256837,256832,256830,256828,256821,256815,256811,256809,256804,256802,256801,256800,256798,256797,256786,256785,256784,256783,256779,256774,256772,256770,256769,256768,256764,256763,256762,256760,256759,256756,256751,256750,256747,256746,256744,256742,256739,256738,256737,256727,256723,256714,256706,256705,256704,256703,256691,256690,256689,256688,256687,256681,256680,256678,256673,256672,256670,256669,256668,256665,256660,256657,256653,256652,256649,256643,256640,256636,256631,256628,256625,256623,256622,256621,256619,256616,256609,256608,256601,256587,256583,256581,256579,256578,256574,256573,256572,256568,256566,256565,256563,256560,256557,256554,256537,256527,256525,256520,256518,256517,256513,256509,256503,256499,256498,256496,256489,256488,256487,256486,256484,256482,256480,256477,256476,256471,256470,256464,256463,256462,256461,256456,256455,256454,256453,256451,256442,256441,256440,256439,256438,256437,256434,256428,256426,256417,256412,256398,256397,256389,256383,256380,256378,256373,256367,256364,256360,256359,256356,256355,256354,256353,256351,256348,256345,256343,256339,256337,256336,256335,256334,256332,256331,256325,256324,256321,256319,256318,256316,256315,256312,256307,256305,256304,256302,256301,256290,256285,256282,256277,256276,256274,256269,256268,256265,256264,256260,256255,256254,256252,256250,256247,256246,256240,256239,256237,256235,256233,256228,256224,256215,256205,256204,256203,256202,256200,256197,256196,256195,256193,256190,256185,256183,256178,256175,256167,256159,256158,256151,256149,256148,256144,256139,256137,256131,256129,256125,256124,256121,256118,256116,256106,256105,256104,256100,256086,256081,256079,256078,256077,256072,256065,256061,256050,256048,256044,256040,256032,256027,256024,256019,256017,256016,256014,255998,255995,255994,255993,255992,255991,255990,255987,255986,255983,255982,255979,255977,255974,255967,255966,255964,255961,255960,255959,255958,255957,255955,255954,255941,255940,255933,255932,255931,255929,255927,255926,255925,255923,255922,255918,255914,255912,255911,255904,255903,255901,255899,255893,255891,255888,255885,255875,255872,255869,255868,255860,255855,255854,255851,255849,255848,255845,255844,255839,255837,255836,255835,255828,255824,255823,255819,255813,255808,255807,255805,255803,255794,255789,255783,255782,255781,255779,255775,255774,255773,255772,255769,255768,255767,255766,255763,255761,255759,255758,255757,255754,255750,255741,255740,255739,255735,255729,255726,255724,255722,255718,255710,255708,255707,255704,255703,255701,255699,255695,255693,255692,255691,255690,255684,255681,255680,255677,255676,255674,255668,255663,255659,255657,255653,255649,255640,255638,255635,255632,255625,255621,255620,255619,255617,255614,255610,255608,255599,255592,255589,255588,255584,255583,255582,255577,255574,255573,255570,255566,255555,255554,255553,255547,255545,255544,255543,255540,255539,255538,255536,255533,255530,255527,255525,255523,255519,255514,255511,255505,255503,255502,255501,255494,255493,255492,255489,255488,255486,255484,255483,255480,255479,255474,255473,255471,255470,255469,255466,255465,255463,255462,255457,255455,255454,255453,255445,255444,255441,255436,255435,255431,255429,255427,255426,255424,255423,255421,255420,255416,255415,255414,255413,255408,255405,255404,255402,255399,255398,255395,255392,255390,255387,255383,255380,255379,255378,255375,255374,255369,255368,255367,255362,255361,255356,255355,255354,255352,255351,255350,255341,255338,255337,255335,255333,255329,255328,255327,255324,255322,255313,255311,255310,255308,255305,255302,255294,255290,255289,255275,255273,255270,255269,255261,255256,255253,255246,255238,255236,255235,255230,255229,255228,255227,255226,255222,255220,255219,255214,255210,255208,255207,255201,255200,255197,255194,255192,255191,255189,255186,255185,255178,255174,255164,255163,255159,255153,255152,255149,255146,255137,255134,255133,255132,255130,255129,255122,255120,255105,255099,255096,255094,255091,255088,255085,255082,255065,255063,255062,255055,255054,255051,255049,255048,255042,255041,255040,255039,255036,255034,255033,255031,255026,255025,255021,255018,255013,255012,255010,255009,255005,255001,254991,254988,254987,254980,254979,254977,254970,254969,254967,254965,254963,254962,254961,254959,254957,254951,254949,254944,254941,254937,254932,254927,254925,254924,254921,254919,254918,254917,254915,254912,254910,254908,254907,254906,254901,254899,254889,254886,254885,254883,254882,254881,254877,254875,254872,254871,254867,254866,254863,254862,254860,254859,254858,254855,254852,254851,254847,254846,254845,254843,254841,254840,254839,254838,254835,254831,254829,254828,254824,254822,254815,254814,254810,254809,254803,254802,254800,254796,254795,254794,254792,254791,254786,254783,254780,254779,254778,254777,254776,254772,254767,254760,254757,254756,254740,254736,254731,254730,254727,254724,254721,254719,254714,254711,254706,254701,254696,254691,254690,254683,254680,254676,254675,254670,254666,254663,254655,254653,254651,254650,254629,254627,254626,254621,254620,254618,254616,254615,254613,254610,254608,254605,254603,254602,254601,254600,254596,254595,254594,254592,254591,254589,254587,254586,254585,254584,254582,254576,254573,254568,254565,254562,254559,254558,254557,254553,254552,254549,254548,254546,254543,254539,254534,254526,254525,254524,254522,254521,254520,254519,254517,254516,254515,254512,254511,254510,254507,254504,254502,254501,254500,254497,254496,254495,254494,254493,254489,254485,254484,254482,254476,254472,254469,254468,254467,254466,254465,254464,254463,254456,254455,254453,254452,254447,254444,254443,254441,254439,254436,254433,254432,254425,254417,254415,254414,254412,254410,254402,254399,254398,254396,254395,254393,254392,254391,254389,254383,254382,254377,254374,254371,254370,254367,254365,254364,254363,254361,254358,254352,254351,254350,254348,254347,254346,254345,254344,254336,254330,254328,254327,254326,254325,254323,254321,254319,254317,254316,254314,254313,254308,254306,254303,254298,254288,254284,254266,254264,254261,254255,254252,254247,254245,254243,254242,254241,254239,254235,254233,254222,254216,254210,254203,254201,254199,254188,254187,254184,254181,254180,254177,254174,254172,254171,254168,254166,254165,254163,254160,254156,254155,254154,254153,254151,254147,254145,254144,254139,254136,254135,254133,254128,254127,254126,254125,254109,254106,254090,254089,254088,254086,254085,254084,254080,254072,254070,254069,254060,254045,254032,254030,254027,254025,254024,254019,254018,254015,254013,254010,254006,254004,254003,253994,253993,253991,253990,253988,253986,253971,253970,253962,253961,253954,253949,253948,253947,253940,253939,253938,253934,253930,253928,253924,253920,253913,253910,253908,253906,253905,253902,253898,253895,253894,253890,253889,253882,253879,253877,253876,253872,253870,253869,253867,253861,253857,253855,253853,253851,253844,253843,253840,253836,253830,253826,253815,253812,253809,253806,253799,253795,253792,253787,253785,253780,253772,253771,253769,253766,253765,253761,253754,253750,253749,253746,253744,253742,253740,253739,253738,253737,253735,253729,253728,253721,253719,253718,253717,253713,253710,253702,253699,253696,253686,253685,253684,253673,253671,253670,253669,253663,253662,253661,253657,253656,253654,253652,253647,253646,253645,253642,253638,253637,253635,253633,253629,253628,253625,253624,253623,253622,253620,253616,253615,253609,253608,253606,253602,253600,253596,253592,253591,253590,253589,253588,253586,253585,253584,253583,253577,253576,253572,253569,253567,253563,253560,253558,253557,253556,253548,253544,253539,253536,253527,253524,253523,253516,253513,253512,253508,253507,253506,253505,253504,253502,253498,253497,253495,253494,253493,253490,253487,253486,253485,253480,253477,253474,253472,253471,253467,253463,253460,253458,253456,253452,253450,253449,253440,253439,253437,253432,253430,253426,253417,253414,253405,253403,253396,253394,253392,253389,253384,253383,253381,253372,253370,253367,253366,253365,253362,253352,253350,253348,253347,253346,253345,253344,253343,253338,253337,253335,253333,253332,253328,253323,253321,253318,253315,253312,253309,253301,253300,253299,253297,253286,253285,253281,253279,253275,253274,253273,253270,253269,253262,253259,253256,253251,253247,253240,253238,253233,253231,253222,253212,253211,253205,253200,253192,253184,253172,253164,253150,253149,253148,253144,253142,253137,253135,253123,253115,253113,253107,253105,253104,253099,253096,253093,253083,253075,253070,253063,253055,253053,253052,253051,253046,253043,253041,253039,253036,253030,253028,253026,253023,253022,253015,253014,253013,253012,253006,253001,253000,252996,252993,252990,252988,252987,252982,252978,252975,252974,252972,252969,252963,252960,252955,252953,252952,252941,252938,252932,252929,252928,252926,252923,252921,252919,252909,252908,252905,252904,252899,252895,252892,252868,252866,252864,252863,252855,252853,252851,252834,252833,252829,252826,252822,252821,252819,252816,252814,252813,252811,252810,252806,252797,252791,252790,252788,252787,252782,252780,252765,252764,252763,252762,252756,252755,252751,252749,252745,252744,252733,252732,252730,252721,252719,252717,252713,252711,252709,252703,252702,252701,252700,252698,252694,252693,252692,252691,252689,252687,252682,252681,252678,252674,252673,252667,252666,252665,252664,252661,252655,252651,252650,252645,252640,252638,252636,252633,252626,252625,252624,252623,252622,252617,252616,252614,252613,252610,252606,252603,252596,252591,252588,252584,252582,252581,252580,252577,252576,252574,252571,252570,252568,252566,252564,252563,252562,252559,252556,252553,252551,252550,252549,252543,252542,252539,252538,252537,252536,252533,252529,252527,252524,252515,252514,252512,252510,252508,252505,252504,252501,252493,252491,252490,252483,252481,252478,252476,252475,252474,252472,252469,252466,252465,252464,252452,252449,252446,252440,252438,252434,252431,252425,252424,252421,252416,252414,252411,252409,252405,252403,252402,252392,252391,252389,252385,252384,252381,252375,252373,252372,252370,252357,252354,252352,252350,252347,252343,252341,252326,252325,252323,252321,252320,252319,252318,252317,252316,252315,252313,252305,252297,252294,252288,252284,252277,252275,252273,252271,252263,252260,252255,252252,252251,252245,252243,252239,252235,252230,252228,252225,252215,252206,252202,252191,252189,252188,252183,252182,252175,252171,252169,252163,252155,252151,252147,252146,252145,252140,252127,252125,252117,252114,252106,252103,252101,252095,252091,252087,252085,252084,252083,252082,252079,252072,252068,252065,252063,252057,252055,252050,252047,252046,252039,252037,252035,252032,252031,252029,252028,252027,252024,252023,252021,252020,252019,252018,252017,252014,252013,252010,252009,252007,252003,252001,251994,251986,251985,251984,251981,251980,251978,251974,251969,251967,251965,251958,251955,251949,251940,251938,251935,251932,251930,251928,251925,251923,251922,251916,251907,251906,251903,251900,251897,251894,251890,251885,251884,251875,251872,251870,251867,251857,251855,251852,251851,251850,251849,251848,251841,251838,251836,251834,251832,251831,251829,251827,251825,251820,251817,251808,251799,251798,251797,251784,251782,251780,251778,251767,251763,251762,251761,251757,251755,251752,251749,251746,251743,251742,251741,251736,251732,251731,251730,251726,251725,251722,251720,251719,251717,251712,251710,251709,251703,251701,251699,251695,251693,251686,251684,251675,251664,251663,251661,251660,251659,251653,251638,251635,251634,251631,251630,251627,251626,251615,251614,251613,251609,251608,251607,251606,251603,251597,251591,251588,251583,251582,251580,251579,251578,251571,251568,251566,251564,251561,251552,251548,251546,251541,251540,251539,251535,251534,251527,251525,251524,251519,251518,251517,251516,251513,251512,251509,251502,251501,251494,251490,251488,251487,251486,251484,251479,251475,251471,251469,251465,251454,251448,251445,251443,251440,251437,251435,251431,251415,251411,251408,251404,251401,251393,251392,251388,251387,251384,251383,251382,251381,251377,251374,251373,251372,251371,251370,251362,251359,251358,251355,251354,251352,251350,251349,251346,251343,251341,251339,251337,251336,251335,251334,251328,251323,251321,251320,251318,251315,251314,251312,251311,251310,251307,251305,251304,251300,251294,251284,251282,251276,251270,251260,251257,251255,251253,251250,251245,251236,251235,251232,251229,251228,251220,251217,251216,251212,251208,251203,251202,251199,251193,251192,251182,251167,251162,251157,251152,251151,251147,251141,251137,251134,251133,251127,251124,251123,251122,251121,251119,251118,251117,251116,251102,251101,251096,251094,251087,251082,251079,251074,251071,251068,251067,251063,251061,251060,251058,251049,251047,251044,251043,251042,251041,251036,251032,251028,251027,251021,251018,251015,251011,251005,251004,251003,250998,250992,250982,250981,250976,250972,250970,250968,250967,250960,250947,250938,250934,250933,250927,250925,250922,250913,250912,250911,250910,250908,250905,250899,250898,250897,250893,250892,250888,250887,250882,250880,250877,250876,250874,250871,250869,250866,250862,250860,250858,250855,250853,250850,250849,250845,250844,250843,250842,250839,250838,250832,250831,250826,250824,250814,250813,250811,250809,250806,250804,250802,250798,250786,250780,250779,250777,250770,250768,250765,250764,250762,250761,250760,250758,250756,250752,250751,250750,250749,250747,250745,250744,250742,250740,250739,250731,250730,250724,250720,250719,250716,250715,250712,250711,250709,250708,250707,250706,250705,250704,250703,250698,250696,250694,250692,250688,250686,250685,250683,250675,250674,250673,250672,250670,250669,250663,250660,250659,250654,250648,250647,250644,250634,250630,250629,250628,250625,250623,250621,250619,250616,250613,250610,250608,250606,250605,250604,250602,250599,250597,250593,250591,250588,250583,250579,250566,250565,250564,250560,250558,250556,250550,250546,250541,250537,250536,250534,250524,250523,250521,250515,250510,250509,250506,250505,250503,250502,250499,250498,250495,250494,250493,250492,250491,250488,250485,250484,250483,250480,250479,250477,250475,250471,250468,250467,250466,250463,250460,250455,250452,250450,250449,250448,250437,250436,250432,250425,250424,250420,250414,250410,250408,250403,250402,250401,250400,250398,250397,250396,250393,250391,250385,250381,250380,250379,250378,250377,250376,250368,250358,250355,250354,250353,250352,250342,250338,250336,250329,250327,250325,250324,250323,250322,250319,250318,250316,250315,250314,250311,250307,250305,250304,250301,250300,250297,250292,250286,250285,250284,250280,250275,250271,250266,250261,250257,250256,250247,250246,250239,250238,250231,250229,250228,250225,250221,250220,250219,250218,250217,250215,250214,250213,250211,250210,250209,250208,250207,250204,250203,250198,250197,250195,250192,250188,250184,250182,250181,250180,250178,250177,250173,250171,250169,250167,250165,250163,250162,250161,250158,250157,250156,250154,250153,250149,250148,250146,250142,250141,250138,250136,250135,250131,250128,250124,250120,250119,250114,250112,250111,250110,250109,250107,250105,250103,250096,250084,250083,250080,250074,250073,250072,250069,250065,250062,250060,250057,250045,250041,250038,250036,250034,250027,250026,250020,250018,250015,250011,250009,250007,250006,250000,249999,249998,249996,249991,249989,249985,249981,249958,249957,249955,249953,249947,249946,249944,249943,249940,249936,249933,249932,249930,249926,249925,249923,249916,249915,249913,249912,249907,249906,249905,249904,249901,249897,249896,249893,249892,249886,249883,249882,249881,249877,249872,249871,249870,249868,249867,249866,249864,249861,249858,249856,249854,249853,249852,249851,249850,249846,249843,249842,249841,249840,249839,249835,249832,249829,249828,249817,249816,249811,249807,249806,249802,249801,249800,249798,249794,249793,249789,249786,249784,249782,249781,249779,249774,249773,249772,249758,249757,249750,249747,249741,249740,249738,249737,249727,249726,249725,249723,249721,249720,249719,249717,249713,249712,249708,249697,249692,249688,249687,249685,249684,249678,249675,249671,249669,249667,249666,249663,249658,249655,249652,249649,249647,249640,249639,249638,249635,249634,249629,249627,249621,249615,249613,249612,249610,249608,249596,249595,249593,249591,249588,249586,249583,249582,249581,249580,249579,249578,249571,249568,249563,249557,249556,249554,249551,249550,249545,249543,249541,249540,249533,249530,249529,249528,249524,249516,249514,249513,249512,249511,249508,249504,249503,249490,249488,249486,249480,249476,249472,249465,249463,249462,249459,249458,249457,249455,249454,249452,249442,249439,249438,249428,249426,249422,249421,249420,249410,249407,249405,249403,249396,249392,249391,249390,249382,249381,249379,249372,249366,249365,249359,249356,249353,249350,249346,249345,249341,249340,249337,249334,249332,249331,249328,249323,249319,249316,249313,249308,249305,249304,249303,249302,249300,249297,249291,249290,249288,249287,249286,249284,249281,249279,249278,249276,249274,249273,249270,249264,249260,249258,249252,249250,249249,249241,249238,249227,249226,249219,249215,249214,249210,249207,249199,249198,249195,249191,249189,249187,249183,249176,249172,249171,249169,249168,249167,249160,249150,249145,249144,249143,249141,249139,249137,249134,249132,249129,249125,249122,249119,249114,249110,249109,249108,249106,249104,249102,249101,249090,249087,249079,249078,249076,249066,249062,249061,249056,249055,249048,249046,249045,249043,249037,249033,249031,249030,249027,249020,249018,249014,249012,249007,249004,249003,249001,248999,248998,248997,248996,248995,248994,248992,248984,248983,248982,248978,248973,248970,248967,248966,248959,248950,248947,248946,248945,248938,248937,248928,248924,248923,248922,248920,248919,248918,248917,248916,248915,248912,248910,248909,248905,248904,248900,248899,248897,248896,248895,248892,248891,248884,248883,248882,248881,248873,248870,248868,248860,248852,248851,248850,248849,248846,248845,248844,248843,248841,248840,248836,248835,248833,248827,248824,248822,248818,248812,248811,248809,248804,248801,248797,248795,248794,248793,248791,248788,248784,248778,248770,248765,248759,248756,248755,248753,248752,248749,248742,248739,248735,248730,248729,248727,248723,248722,248719,248718,248714,248713,248709,248707,248706,248704,248702,248698,248695,248693,248691,248686,248685,248683,248682,248678,248675,248673,248672,248671,248663,248643,248640,248630,248625,248623,248622,248620,248619,248611,248609,248598,248597,248595,248593,248590,248587,248583,248581,248580,248579,248577,248575,248574,248571,248568,248567,248565,248563,248561,248558,248556,248544,248543,248542,248541,248539,248538,248536,248533,248532,248531,248524,248519,248517,248515,248513,248510,248508,248506,248504,248500,248492,248491,248489,248484,248482,248478,248477,248473,248471,248470,248468,248467,248466,248465,248462,248461,248460,248453,248452,248451,248448,248446,248442,248436,248434,248433,248432,248430,248426,248425,248424,248421,248412,248407,248405,248404,248403,248402,248400,248397,248392,248391,248390,248387,248386,248380,248372,248362,248359,248357,248355,248354,248353,248351,248350,248348,248342,248341,248336,248334,248333,248332,248331,248330,248322,248320,248317,248313,248309,248306,248305,248303,248299,248298,248297,248292,248291,248290,248287,248286,248285,248284,248283,248280,248278,248275,248271,248270,248267,248266,248262,248260,248259,248258,248249,248248,248243,248242,248240,248238,248236,248235,248231,248230,248229,248228,248227,248226,248225,248220,248208,248207,248206,248204,248203,248202,248201,248200,248198,248193,248191,248190,248185,248184,248183,248182,248181,248177,248176,248174,248173,248165,248163,248157,248155,248152,248151,248145,248143,248141,248139,248133,248127,248125,248121,248119,248117,248115,248114,248109,248107,248106,248104,248094,248092,248088,248087,248086,248084,248083,248080,248077,248074,248072,248071,248069,248068,248066,248064,248061,248060,248059,248058,248057,248053,248052,248051,248050,248049,248043,248030,248029,248026,248023,248020,248018,248017,248016,248015,248009,248006,248004,248003,247985,247982,247974,247973,247971,247970,247964,247961,247960,247958,247957,247956,247955,247954,247953,247945,247939,247938,247936,247934,247933,247931,247929,247928,247927,247926,247921,247918,247913,247912,247903,247900,247889,247888,247886,247877,247876,247875,247869,247867,247863,247862,247861,247860,247859,247858,247856,247855,247854,247852,247850,247848,247846,247843,247842,247838,247837,247834,247832,247828,247825,247821,247813,247810,247803,247796,247795,247794,247789,247783,247781,247779,247778,247768,247766,247765,247761,247758,247754,247753,247752,247748,247745,247742,247738,247726,247722,247717,247714,247713,247711,247708,247701,247699,247696,247694,247691,247689,247686,247684,247682,247681,247678,247672,247670,247669,247668,247666,247660,247657,247656,247649,247642,247639,247638,247635,247634,247633,247631,247627,247625,247621,247619,247616,247609,247607,247605,247603,247600,247599,247598,247595,247594,247585,247582,247579,247573,247571,247570,247568,247566,247562,247559,247557,247556,247552,247551,247550,247547,247544,247543,247541,247538,247536,247535,247531,247526,247524,247522,247521,247517,247516,247511,247510,247509,247502,247501,247500,247496,247495,247493,247492,247488,247484,247483,247479,247477,247476,247475,247474,247467,247465,247464,247460,247459,247455,247454,247453,247449,247448,247443,247442,247439,247428,247420,247419,247417,247416,247415,247412,247406,247403,247402,247397,247395,247391,247390,247384,247383,247380,247377,247373,247372,247371,247370,247369,247367,247366,247362,247355,247354,247350,247347,247343,247342,247339,247336,247334,247333,247326,247324,247318,247317,247315,247314,247313,247311,247310,247308,247307,247304,247300,247289,247286,247283,247281,247279,247273,247268,247259,247254,247251,247246,247244,247241,247240,247239,247236,247230,247228,247224,247223,247222,247215,247212,247210,247207,247203,247200,247199,247194,247192,247189,247187,247185,247171,247168,247164,247163,247162,247156,247154,247150,247147,247141,247137,247135,247134,247132,247129,247128,247125,247123,247118,247115,247110,247108,247107,247106,247105,247102,247100,247096,247094,247093,247092,247090,247081,247068,247066,247063,247062,247058,247055,247052,247049,247035,247034,247031,247030,247029,247021,247018,247017,247016,247015,247014,247013,247007,246999,246996,246995,246994,246992,246991,246989,246988,246985,246984,246982,246979,246977,246976,246975,246971,246964,246962,246958,246957,246956,246953,246951,246946,246937,246934,246931,246924,246923,246921,246920,246908,246905,246902,246901,246897,246896,246892,246888,246885,246884,246878,246873,246872,246864,246862,246859,246857,246856,246848,246840,246837,246836,246835,246833,246831,246830,246829,246824,246821,246819,246803,246800,246798,246794,246793,246789,246786,246783,246782,246780,246778,246776,246772,246769,246766,246760,246758,246757,246753,246752,246751,246749,246743,246739,246737,246735,246722,246720,246718,246710,246706,246702,246701,246698,246694,246691,246689,246686,246684,246678,246676,246674,246671,246664,246662,246659,246657,246654,246647,246646,246639,246637,246634,246633,246632,246631,246630,246620,246618,246617,246615,246613,246610,246604,246601,246593,246590,246589,246579,246578,246577,246568,246567,246565,246560,246556,246553,246552,246544,246543,246534,246531,246530,246528,246527,246522,246513,246506,246505,246501,246500,246495,246491,246488,246485,246484,246483,246475,246473,246471,246469,246468,246464,246463,246461,246460,246458,246457,246456,246455,246452,246449,246436,246428,246416,246412,246405,246404,246403,246401,246399,246393,246389,246377,246375,246370,246369,246364,246362,246360,246359,246354,246342,246334,246330,246326,246325,246324,246321,246320,246319,246315,246314,246313,246312,246310,246307,246305,246304,246303,246302,246298,246295,246292,246287,246286,246283,246282,246276,246271,246270,246268,246267,246264,246263,246262,246261,246259,246257,246256,246254,246252,246251,246247,246242,246241,246240,246239,246237,246235,246233,246229,246228,246223,246222,246217,246215,246213,246199,246197,246196,246192,246191,246190,246180,246172,246170,246167,246164,246161,246152,246143,246141,246140,246138,246137,246136,246135,246132,246131,246129,246125,246123,246122,246121,246118,246116,246114,246113,246109,246097,246089,246086,246082,246077,246076,246073,246072,246070,246066,246064,246062,246060,246055,246054,246053,246051,246044,246043,246042,246041,246039,246029,246028,246019,246018,246013,246012,246011,246009,246006,246004,246003,246001,245998,245997,245992,245989,245988,245987,245985,245978,245972,245970,245969,245962,245961,245960,245958,245956,245955,245954,245953,245938,245936,245935,245932,245930,245925,245916,245913,245908,245907,245898,245897,245888,245887,245876,245869,245867,245862,245855,245851,245849,245847,245846,245843,245841,245835,245834,245829,245825,245824,245819,245815,245814,245808,245807,245800,245795,245788,245787,245784,245782,245781,245780,245776,245774,245771,245766,245765,245760,245759,245755,245753,245752,245751,245749,245747,245746,245745,245743,245742,245737,245736,245733,245726,245725,245724,245723,245722,245720,245718,245717,245710,245704,245700,245696,245694,245691,245689,245688,245683,245680,245675,245673,245672,245671,245667,245665,245661,245660,245654,245651,245647,245643,245642,245636,245626,245625,245624,245621,245613,245611,245607,245603,245599,245596,245590,245586,245578,245577,245574,245572,245568,245567,245566,245554,245552,245551,245546,245543,245540,245535,245533,245531,245528,245527,245525,245524,245521,245517,245514,245511,245510,245508,245504,245502,245492,245488,245487,245486,245483,245480,245478,245476,245468,245467,245465,245463,245461,245454,245452,245450,245448,245438,245434,245426,245425,245424,245419,245416,245412,245411,245410,245406,245394,245391,245387,245385,245378,245377,245376,245372,245370,245369,245366,245365,245360,245359,245356,245351,245347,245342,245336,245329,245328,245326,245320,245311,245310,245307,245303,245302,245300,245299,245297,245296,245295,245292,245286,245284,245282,245278,245276,245275,245272,245271,245270,245268,245267,245264,245262,245261,245258,245256,245252,245248,245247,245243,245242,245238,245237,245236,245233,245230,245228,245226,245222,245218,245217,245214,245213,245209,245207,245206,245200,245198,245197,245189,245182,245181,245179,245177,245173,245172,245171,245167,245166,245165,245164,245163,245156,245155,245153,245152,245150,245147,245146,245143,245141,245132,245130,245128,245125,245123,245117,245113,245112,245110,245109,245107,245100,245099,245095,245088,245086,245084,245083,245082,245081,245077,245076,245075,245074,245073,245072,245064,245063,245060,245058,245056,245055,245053,245051,245048,245045,245044,245043,245040,245032,245029,245027,245026,245022,245021,245020,245019,245018,245016,245015,245014,245013,245010,245009,245007,245006,245003,245002,245000,244999,244996,244995,244994,244993,244989,244987,244986,244983,244982,244978,244976,244975,244973,244971,244967,244965,244962,244961,244960,244959,244957,244955,244954,244953,244951,244942,244930,244929,244923,244922,244920,244915,244913,244909,244908,244907,244906,244905,244904,244894,244892,244887,244886,244880,244879,244874,244872,244871,244870,244868,244866,244865,244862,244861,244859,244858,244856,244855,244852,244849,244848,244846,244845,244841,244839,244837,244835,244830,244827,244826,244819,244818,244814,244811,244809,244808,244800,244799,244797,244793,244791,244787,244786,244785,244778,244777,244770,244768,244767,244759,244757,244754,244753,244752,244750,244748,244747,244744,244743,244739,244738,244737,244728,244727,244726,244724,244722,244714,244712,244711,244710,244705,244704,244702,244700,244698,244687,244679,244670,244669,244667,244663,244662,244657,244647,244645,244644,244642,244641,244636,244635,244634,244633,244626,244624,244619,244617,244615,244611,244610,244608,244604,244595,244594,244592,244590,244588,244587,244584,244583,244582,244580,244569,244568,244560,244559,244558,244555,244551,244542,244541,244537,244525,244520,244519,244518,244515,244510,244505,244504,244503,244502,244501,244499,244498,244496,244494,244488,244485,244483,244482,244477,244476,244475,244474,244470,244469,244468,244461,244460,244459,244456,244451,244450,244449,244448,244447,244446,244444,244442,244440,244433,244432,244427,244421,244418,244417,244411,244410,244409,244405,244404,244403,244401,244399,244398,244397,244394,244392,244383,244380,244378,244377,244372,244370,244364,244363,244359,244357,244353,244350,244341,244335,244323,244322,244319,244317,244316,244303,244302,244292,244291,244289,244288,244287,244282,244281,244280,244279,244277,244276,244270,244268,244267,244257,244256,244255,244253,244252,244251,244249,244242,244241,244239,244237,244232,244227,244223,244222,244221,244220,244219,244212,244206,244205,244204,244203,244198,244197,244187,244184,244180,244178,244176,244174,244172,244171,244169,244168,244166,244162,244161,244160,244158,244154,244152,244148,244147,244145,244144,244143,244142,244141,244139,244137,244136,244135,244134,244132,244129,244127,244126,244124,244120,244119,244116,244113,244111,244105,244100,244099,244097,244096,244094,244093,244092,244090,244089,244088,244087,244085,244084,244083,244081,244080,244079,244076,244071,244068,244067,244065,244063,244059,244057,244055,244052,244051,244049,244047,244046,244044,244043,244042,244031,244029,244027,244025,244024,244019,244016,244013,244006,244005,244004,244002,244001,243996,243995,243994,243992,243988,243987,243986,243984,243979,243976,243975,243969,243967,243965,243961,243960,243957,243956,243954,243953,243949,243948,243946,243940,243939,243937,243931,243930,243924,243922,243921,243920,243918,243916,243914,243911,243909,243907,243906,243892,243889,243888,243887,243883,243881,243879,243875,243872,243870,243868,243864,243858,243855,243854,243853,243852,243850,243849,243848,243847,243843,243840,243839,243834,243830,243829,243827,243826,243825,243823,243821,243815,243810,243809,243805,243803,243799,243798,243795,243789,243785,243784,243783,243776,243775,243774,243772,243771,243769,243767,243753,243752,243750,243749,243748,243747,243746,243745,243744,243741,243740,243739,243738,243737,243736,243734,243733,243731,243730,243729,243728,243721,243716,243713,243707,243702,243696,243695,243691,243681,243679,243675,243673,243672,243670,243668,243667,243666,243665,243664,243662,243659,243652,243651,243650,243647,243646,243645,243642,243637,243632,243627,243626,243625,243623,243620,243619,243615,243612,243610,243603,243601,243594,243593,243589,243586,243579,243578,243576,243572,243570,243569,243567,243565,243563,243558,243556,243555,243551,243549,243548,243547,243541,243533,243532,243530,243529,243528,243524,243521,243520,243511,243510,243509,243506,243504,243503,243502,243499,243495,243494,243490,243486,243484,243481,243479,243478,243477,243476,243475,243472,243470,243469,243467,243464,243462,243459,243458,243457,243456,243455,243453,243452,243450,243447,243444,243439,243438,243437,243435,243433,243432,243423,243420,243417,243415,243414,243410,243407,243404,243400,243395,243394,243387,243386,243385,243382,243379,243376,243375,243369,243363,243359,243353,243351,243346,243345,243344,243342,243341,243338,243337,243334,243329,243323,243320,243313,243311,243306,243303,243296,243294,243293,243292,243289,243288,243285,243283,243280,243278,243274,243272,243269,243268,243264,243262,243258,243257,243256,243254,243242,243241,243235,243234,243233,243231,243229,243228,243227,243223,243213,243212,243209,243205,243198,243196,243193,243192,243191,243188,243181,243179,243177,243175,243174,243173,243171,243169,243168,243166,243165,243160,243155,243153,243152,243151,243150,243146,243141,243140,243139,243134,243131,243128,243124,243123,243122,243121,243119,243118,243116,243114,243112,243111,243100,243093,243087,243084,243083,243079,243078,243073,243070,243068,243067,243066,243063,243049,243046,243045,243042,243041,243040,243039,243037,243034,243033,243032,243031,243029,243028,243022,243021,243014,243009,243006,243003,243002,243001,242998,242995,242994,242993,242991,242988,242987,242983,242982,242975,242973,242972,242971,242970,242968,242964,242963,242958,242955,242954,242950,242949,242945,242942,242941,242939,242936,242932,242929,242928,242927,242925,242924,242920,242908,242902,242896,242894,242886,242885,242884,242882,242881,242880,242875,242874,242867,242864,242862,242858,242856,242855,242853,242849,242847,242845,242844,242841,242839,242836,242835,242831,242827,242826,242822,242818,242817,242811,242810,242807,242804,242803,242801,242793,242792,242788,242785,242777,242766,242763,242762,242761,242759,242757,242752,242749,242748,242745,242743,242740,242739,242733,242731,242729,242728,242726,242725,242722,242719,242718,242717,242716,242712,242711,242710,242708,242706,242705,242704,242700,242693,242689,242685,242681,242677,242676,242675,242672,242665,242661,242660,242654,242640,242639,242634,242628,242622,242621,242620,242615,242613,242607,242597,242595,242594,242592,242591,242590,242586,242584,242579,242574,242573,242572,242570,242561,242551,242550,242542,242540,242539,242538,242537,242536,242535,242533,242529,242527,242521,242520,242519,242517,242515,242513,242512,242507,242506,242504,242499,242497,242496,242493,242483,242481,242480,242475,242472,242471,242467,242466,242464,242462,242459,242455,242454,242446,242443,242440,242434,242433,242432,242431,242430,242424,242417,242416,242413,242410,242409,242408,242404,242401,242399,242398,242397,242396,242393,242392,242389,242388,242385,242383,242380,242379,242375,242374,242371,242368,242361,242360,242357,242356,242352,242349,242346,242344,242333,242332,242323,242321,242320,242316,242313,242312,242305,242298,242297,242291,242290,242287,242283,242273,242266,242264,242260,242259,242254,242252,242250,242249,242247,242246,242243,242239,242234,242230,242229,242219,242214,242210,242209,242206,242204,242202,242201,242199,242194,242191,242190,242184,242182,242176,242173,242170,242169,242167,242166,242165,242161,242155,242150,242145,242144,242141,242135,242129,242125,242112,242103,242100,242096,242091,242084,242081,242071,242069,242068,242066,242063,242061,242055,242054,242053,242051,242050,242040,242038,242034,242029,242028,242027,242025,242022,242017,242013,242012,242002,241993,241989,241981,241979,241973,241961,241955,241954,241953,241949,241947,241945,241942,241941,241937,241935,241929,241927,241926,241924,241920,241915,241913,241911,241903,241902,241900,241897,241896,241894,241889,241888,241884,241880,241878,241874,241871,241869,241866,241863,241860,241856,241855,241853,241847,241843,241841,241839,241835,241834,241832,241831,241830,241829,241825,241824,241822,241820,241819,241817,241810,241805,241804,241803,241802,241797,241794,241784,241783,241782,241781,241780,241776,241775,241774,241773,241767,241764,241761,241751,241750,241748,241744,241739,241736,241735,241733,241727,241725,241723,241722,241720,241719,241710,241709,241708,241703,241699,241697,241694,241692,241688,241687,241686,241682,241678,241677,241676,241672,241670,241668,241664,241659,241657,241655,241654,241650,241649,241643,241641,241640,241638,241633,241630,241612,241611,241608,241599,241598,241597,241595,241594,241589,241587,241585,241580,241577,241574,241572,241571,241569,241563,241560,241558,241546,241537,241535,241534,241533,241532,241531,241530,241527,241522,241521,241516,241512,241509,241507,241505,241501,241500,241499,241496,241489,241485,241481,241478,241477,241475,241474,241470,241469,241467,241465,241463,241458,241455,241454,241453,241451,241446,241444,241443,241437,241432,241430,241426,241422,241421,241419,241417,241416,241415,241412,241411,241410,241406,241398,241393,241386,241384,241382,241380,241379,241378,241372,241367,241366,241365,241362,241361,241353,241352,241351,241350,241347,241343,241342,241341,241340,241336,241332,241330,241328,241326,241325,241321,241320,241314,241312,241310,241308,241306,241305,241299,241297,241296,241295,241294,241293,241285,241282,241278,241277,241275,241274,241272,241267,241266,241260,241258,241249,241242,241233,241232,241228,241222,241220,241216,241213,241211,241206,241205,241203,241202,241201,241199,241192,241188,241186,241184,241172,241171,241165,241157,241155,241146,241145,241144,241136,241133,241129,241126,241124,241123,241122,241121,241120,241119,241114,241108,241106,241102,241101,241092,241090,241087,241084,241083,241079,241075,241073,241072,241071,241070,241067,241064,241060,241057,241054,241051,241048,241045,241044,241043,241037,241033,241028,241027,241023,241019,241017,241014,241013,241008,241005,240996,240995,240984,240974,240973,240972,240971,240970,240969,240968,240966,240965,240957,240953,240952,240951,240946,240945,240944,240943,240941,240938,240936,240934,240930,240929,240928,240927,240926,240921,240918,240914,240913,240912,240908,240904,240895,240891,240883,240880,240879,240878,240873,240872,240868,240861,240856,240855,240853,240848,240845,240844,240841,240840,240837,240836,240835,240834,240833,240830,240828,240826,240817,240816,240814,240812,240801,240798,240792,240790,240784,240782,240781,240779,240771,240770,240767,240766,240763,240762,240756,240750,240749,240741,240737,240729,240719,240710,240707,240706,240703,240702,240700,240698,240693,240689,240688,240686,240684,240682,240678,240675,240672,240669,240666,240663,240657,240655,240651,240645,240644,240640,240639,240637,240634,240632,240627,240621,240615,240614,240613,240601,240597,240592,240591,240590,240588,240586,240585,240584,240580,240577,240569,240564,240560,240555,240546,240543,240539,240536,240534,240529,240527,240526,240524,240523,240513,240512,240510,240509,240507,240505,240504,240503,240501,240500,240499,240498,240495,240492,240490,240487,240482,240480,240477,240475,240472,240469,240465,240463,240461,240460,240458,240456,240450,240448,240446,240445,240440,240436,240434,240432,240425,240423,240421,240416,240415,240409,240408,240406,240402,240401,240400,240399,240398,240395,240394,240392,240390,240389,240385,240383,240381,240380,240379,240377,240375,240371,240369,240364,240360,240359,240358,240353,240349,240347,240342,240340,240330,240323,240322,240319,240318,240308,240302,240299,240298,240295,240291,240287,240286,240283,240282,240279,240278,240276,240270,240263,240262,240259,240257,240256,240253,240252,240251,240250,240247,240246,240244,240242,240238,240237,240231,240225,240223,240220,240218,240216,240215,240213,240208,240206,240205,240193,240189,240187,240185,240184,240183,240179,240178,240177,240172,240169,240165,240162,240158,240152,240151,240148,240147,240145,240144,240134,240133,240129,240126,240125,240124,240123,240122,240121,240120,240117,240115,240102,240095,240090,240087,240083,240080,240078,240076,240075,240074,240073,240072,240065,240064,240058,240056,240055,240051,240050,240049,240048,240042,240034,240029,240027,240026,240025,240023,240022,240012,240011,240004,240003,240002,240001,239999,239995,239994,239993,239992,239988,239985,239984,239982,239979,239971,239970,239962,239961,239960,239959,239956,239944,239940,239939,239934,239933,239932,239929,239928,239925,239923,239920,239919,239915,239912,239907,239906,239905,239904,239903,239886,239884,239879,239876,239874,239873,239872,239869,239866,239863,239859,239857,239856,239853,239850,239848,239845,239841,239840,239839,239838,239835,239831,239829,239827,239826,239821,239813,239808,239806,239802,239799,239796,239786,239783,239782,239781,239771,239767,239762,239761,239759,239758,239757,239755,239753,239751,239749,239740,239738,239734,239733,239731,239730,239729,239727,239725,239724,239721,239719,239713,239712,239711,239707,239706,239705,239703,239702,239701,239700,239698,239693,239687,239673,239670,239669,239668,239667,239662,239660,239659,239657,239651,239649,239648,239647,239641,239639,239637,239632,239630,239627,239626,239622,239619,239615,239614,239610,239606,239605,239602,239600,239598,239596,239593,239591,239590,239589,239587,239585,239584,239583,239581,239580,239576,239575,239572,239566,239563,239561,239560,239554,239552,239544,239543,239542,239535,239530,239528,239527,239525,239522,239521,239519,239518,239515,239514,239513,239512,239509,239507,239506,239505,239503,239498,239497,239496,239492,239489,239488,239487,239486,239484,239480,239479,239475,239471,239468,239467,239466,239463,239462,239457,239455,239447,239442,239434,239429,239425,239424,239421,239420,239419,239418,239417,239412,239409,239403,239400,239395,239392,239380,239377,239374,239373,239370,239364,239360,239357,239356,239355,239354,239349,239348,239341,239338,239335,239331,239330,239327,239321,239316,239313,239310,239309,239305,239304,239301,239296,239295,239294,239293,239292,239289,239287,239285,239283,239282,239279,239276,239273,239271,239260,239259,239258,239252,239245,239243,239242,239241,239238,239234,239232,239220,239214,239212,239207,239205,239204,239202,239201,239198,239197,239194,239193,239192,239189,239185,239184,239176,239175,239174,239171,239169,239167,239163,239160,239159,239149,239146,239145,239136,239135,239131,239129,239128,239121,239120,239117,239112,239108,239106,239101,239100,239099,239098,239096,239080,239076,239075,239071,239070,239066,239064,239058,239054,239041,239040,239028,239027,239025,239018,239016,239015,239010,239007,238996,238995,238994,238988,238982,238978,238974,238971,238967,238965,238964,238962,238960,238954,238952,238949,238947,238945,238943,238941,238936,238934,238932,238928,238923,238918,238917,238914,238912,238899,238897,238891,238883,238881,238877,238876,238875,238874,238871,238869,238867,238862,238854,238853,238850,238836,238835,238833,238832,238829,238824,238822,238816,238814,238813,238811,238810,238807,238806,238804,238802,238801,238800,238794,238789,238779,238778,238772,238763,238759,238757,238756,238755,238752,238751,238747,238746,238745,238743,238741,238733,238728,238727,238724,238723,238721,238713,238712,238710,238709,238707,238703,238701,238700,238699,238696,238692,238691,238690,238685,238684,238682,238679,238676,238675,238674,238673,238671,238670,238665,238662,238660,238659,238656,238655,238654,238653,238651,238648,238647,238646,238645,238644,238643,238642,238641,238640,238638,238634,238633,238631,238618,238617,238611,238610,238609,238608,238606,238604,238603,238599,238595,238594,238590,238584,238581,238580,238573,238558,238557,238551,238543,238542,238539,238538,238534,238522,238518,238509,238507,238504,238503,238498,238494,238491,238489,238487,238482,238479,238478,238474,238472,238470,238469,238468,238466,238464,238462,238461,238459,238458,238456,238450,238448,238447,238445,238442,238440,238437,238435,238432,238430,238428,238427,238425,238424,238420,238419,238417,238415,238412,238411,238410,238409,238406,238405,238403,238402,238395,238394,238391,238390,238389,238387,238384,238378,238375,238374,238370,238369,238366,238364,238362,238349,238346,238341,238337,238336,238335,238325,238324,238321,238317,238315,238307,238301,238299,238296,238294,238290,238287,238285,238283,238280,238279,238278,238276,238275,238272,238269,238268,238267,238264,238261,238260,238259,238257,238255,238254,238253,238247,238246,238236,238234,238233,238229,238225,238223,238222,238221,238220,238217,238215,238213,238211,238204,238203,238195,238191,238190,238188,238172,238171,238170,238169,238166,238163,238160,238158,238154,238150,238149,238147,238144,238143,238141,238138,238124,238122,238110,238108,238104,238103,238101,238094,238092,238090,238089,238087,238086,238085,238084,238081,238080,238078,238076,238073,238069,238068,238066,238064,238063,238061,238058,238057,238055,238048,238047,238046,238041,238040,238038,238036,238034,238029,238022,238021,238016,238004,238002,237998,237996,237992,237989,237988,237987,237981,237979,237977,237975,237974,237972,237970,237969,237967,237965,237963,237962,237961,237957,237956,237955,237953,237952,237950,237949,237948,237946,237945,237943,237941,237940,237937,237932,237930,237927,237924,237922,237917,237916,237914,237909,237905,237899,237891,237886,237878,237876,237875,237873,237867,237866,237864,237860,237859,237858,237857,237856,237855,237849,237848,237845,237838,237835,237830,237828,237826,237823,237821,237820,237816,237815,237807,237799,237796,237794,237793,237792,237791,237790,237787,237774,237772,237771,237763,237761,237757,237756,237755,237753,237747,237746,237742,237740,237734,237731,237725,237724,237723,237718,237712,237711,237709,237705,237703,237700,237698,237696,237685,237681,237676,237675,237672,237671,237666,237662,237661,237660,237657,237656,237654,237653,237649,237646,237645,237644,237642,237641,237640,237638,237636,237629,237626,237624,237621,237620,237618,237615,237613,237612,237610,237609,237602,237596,237593,237589,237584,237580,237579,237577,237574,237571,237563,237559,237557,237556,237552,237551,237550,237549,237544,237541,237540,237536,237528,237524,237523,237518,237512,237510,237506,237505,237504,237503,237502,237500,237497,237495,237494,237492,237486,237485,237484,237481,237480,237479,237471,237470,237467,237466,237465,237460,237459,237455,237450,237447,237442,237439,237431,237423,237421,237417,237416,237414,237404,237402,237401,237400,237399,237398,237397,237396,237394,237389,237388,237385,237384,237383,237376,237375,237366,237362,237361,237360,237355,237351,237349,237348,237344,237337,237336,237330,237323,237320,237319,237316,237314,237313,237311,237310,237306,237304,237303,237302,237298,237295,237293,237291,237290,237289,237288,237286,237285,237284,237279,237278,237277,237276,237274,237272,237270,237269,237266,237265,237264,237262,237261,237259,237256,237254,237253,237251,237250,237245,237242,237241,237235,237234,237233,237231,237226,237225,237224,237223,237222,237218,237216,237212,237202,237200,237199,237196,237195,237194,237193,237191,237190,237189,237185,237184,237183,237180,237179,237177,237175,237173,237169,237163,237160,237158,237157,237155,237154,237151,237149,237147,237146,237142,237141,237139,237138,237137,237136,237135,237131,237129,237125,237122,237121,237120,237119,237117,237115,237111,237102,237101,237100,237098,237089,237088,237084,237081,237080,237078,237077,237075,237073,237066,237062,237060,237059,237053,237052,237051,237050,237048,237045,237043,237042,237041,237040,237035,237034,237033,237030,237027,237015,237011,237010,236999,236997,236995,236993,236982,236976,236974,236968,236960,236951,236950,236949,236946,236939,236935,236934,236933,236927,236920,236913,236911,236910,236909,236902,236900,236899,236898,236895,236894,236892,236889,236881,236877,236872,236871,236865,236864,236859,236858,236850,236849,236848,236847,236846,236845,236844,236838,236835,236829,236827,236826,236824,236818,236817,236808,236807,236805,236800,236799,236797,236790,236789,236787,236786,236785,236781,236777,236776,236775,236774,236770,236769,236767,236761,236752,236749,236747,236745,236734,236733,236732,236731,236729,236728,236725,236719,236716,236709,236708,236696,236691,236690,236689,236688,236687,236681,236676,236673,236671,236661,236660,236659,236658,236657,236656,236652,236643,236639,236638,236637,236635,236632,236631,236630,236623,236612,236610,236606,236604,236603,236600,236598,236597,236596,236593,236592,236587,236586,236583,236574,236569,236560,236559,236558,236557,236550,236546,236545,236539,236534,236532,236531,236523,236520,236517,236516,236511,236509,236507,236499,236498,236496,236495,236494,236493,236491,236490,236482,236479,236476,236470,236468,236467,236465,236463,236459,236458,236456,236448,236441,236440,236432,236429,236428,236424,236419,236412,236408,236407,236405,236403,236398,236397,236395,236392,236387,236386,236385,236383,236381,236379,236378,236375,236373,236372,236370,236367,236366,236363,236362,236353,236349,236345,236342,236340,236339,236335,236333,236332,236330,236328,236326,236320,236312,236311,236307,236306,236304,236302,236297,236296,236293,236292,236289,236287,236282,236279,236277,236274,236271,236270,236269,236268,236263,236262,236261,236257,236253,236251,236244,236243,236242,236235,236231,236228,236227,236225,236224,236222,236221,236220,236205,236204,236203,236202,236200,236192,236191,236184,236183,236177,236172,236170,236168,236158,236155,236151,236149,236148,236145,236144,236142,236141,236138,236137,236136,236135,236134,236131,236128,236127,236126,236121,236117,236111,236106,236099,236098,236097,236088,236087,236084,236072,236069,236068,236062,236061,236059,236057,236056,236050,236049,236047,236046,236045,236043,236042,236040,236032,236031,236026,236024,236022,236018,236011,236010,236008,236003,236000,235998,235991,235989,235987,235982,235978,235977,235975,235972,235969,235966,235964,235963,235962,235956,235955,235952,235951,235945,235944,235943,235940,235939,235937,235936,235923,235922,235921,235913,235911,235906,235898,235897,235896,235888,235883,235881,235877,235874,235872,235870,235865,235864,235863,235862,235857,235854,235853,235849,235844,235839,235838,235832,235828,235826,235825,235814,235810,235809,235808,235805,235801,235799,235798,235796,235795,235786,235776,235774,235773,235770,235763,235749,235744,235739,235732,235731,235726,235722,235721,235720,235719,235717,235713,235711,235709,235707,235704,235703,235702,235697,235696,235695,235694,235693,235690,235689,235685,235681,235678,235674,235667,235662,235661,235660,235659,235655,235652,235648,235647,235645,235644,235640,235637,235635,235634,235632,235631,235629,235628,235627,235626,235625,235624,235623,235620,235616,235609,235606,235600,235598,235593,235589,235583,235582,235579,235577,235567,235557,235556,235553,235544,235543,235542,235541,235539,235538,235527,235522,235521,235518,235517,235516,235515,235503,235501,235497,235495,235494,235493,235491,235486,235482,235480,235478,235476,235473,235471,235470,235468,235467,235461,235458,235456,235455,235450,235446,235439,235438,235437,235436,235435,235434,235431,235417,235416,235415,235414,235404,235401,235393,235392,235391,235389,235388,235380,235379,235376,235375,235373,235372,235371,235368,235367,235366,235361,235360,235356,235355,235352,235350,235343,235335,235334,235333,235332,235329,235327,235324,235323,235320,235317,235303,235301,235295,235294,235291,235290,235288,235285,235283,235279,235276,235275,235272,235266,235264,235263,235261,235256,235252,235250,235248,235246,235245,235244,235243,235241,235237,235234,235231,235230,235224,235219,235218,235215,235212,235209,235205,235204,235202,235196,235195,235194,235191,235189,235188,235187,235186,235184,235183,235182,235181,235174,235173,235170,235167,235164,235152,235148,235145,235143,235140,235137,235135,235134,235131,235128,235127,235125,235123,235119,235118,235113,235112,235111,235105,235103,235099,235098,235097,235096,235094,235093,235090,235082,235072,235069,235065,235063,235062,235051,235050,235048,235043,235042,235039,235037,235034,235033,235030,235026,235018,235008,235005,235004,235001,234992,234989,234987,234983,234977,234974,234971,234968,234967,234965,234963,234959,234956,234954,234952,234951,234950,234949,234945,234941,234940,234935,234922,234921,234919,234918,234912,234910,234900,234895,234891,234890,234879,234874,234872,234871,234870,234868,234867,234865,234864,234859,234857,234852,234851,234850,234849,234848,234847,234845,234842,234841,234839,234833,234832,234830,234828,234823,234822,234817,234816,234813,234812,234809,234808,234804,234800,234792,234791,234790,234786,234785,234777,234776,234772,234770,234769,234767,234766,234764,234762,234758,234755,234753,234748,234746,234745,234742,234740,234738,234737,234736,234735,234731,234730,234725,234723,234722,234720,234719,234717,234715,234713,234712,234709,234708,234706,234705,234698,234697,234694,234693,234691,234690,234689,234678,234676,234673,234672,234671,234665,234664,234663,234658,234657,234654,234651,234647,234646,234641,234640,234637,234634,234631,234629,234623,234618,234617,234615,234614,234613,234612,234609,234606,234605,234602,234593,234592,234590,234588,234585,234579,234571,234569,234556,234551,234549,234548,234546,234545,234540,234530,234528,234526,234523,234520,234517,234516,234515,234507,234501,234500,234495,234488,234487,234486,234484,234483,234475,234469,234465,234464,234462,234453,234447,234446,234444,234437,234436,234435,234434,234433,234430,234427,234422,234421,234420,234419,234415,234409,234407,234387,234380,234379,234378,234374,234373,234372,234371,234367,234361,234360,234359,234357,234355,234354,234353,234351,234342,234341,234337,234336,234326,234317,234316,234315,234308,234304,234300,234299,234296,234293,234285,234280,234279,234272,234271,234269,234261,234259,234255,234250,234249,234242,234239,234238,234237,234229,234228,234226,234220,234219,234218,234204,234200,234196,234194,234186,234182,234179,234178,234177,234158,234155,234145,234143,234142,234139,234134,234131,234130,234129,234126,234124,234123,234109,234108,234107,234105,234103,234098,234087,234086,234075,234074,234072,234070,234068,234067,234066,234065,234062,234059,234054,234053,234048,234046,234045,234041,234035,234034,234031,234029,234024,234022,234019,234018,234017,234008,234005,233999,233996,233995,233994,233993,233988,233987,233984,233982,233976,233975,233974,233973,233967,233961,233954,233952,233951,233945,233944,233936,233934,233928,233927,233926,233924,233921,233916,233915,233911,233909,233907,233906,233902,233895,233893,233892,233886,233882,233881,233880,233867,233861,233858,233854,233853,233848,233842,233841,233837,233836,233829,233828,233827,233826,233825,233820,233817,233815,233812,233806,233801,233799,233798,233796,233792,233789,233787,233783,233781,233779,233774,233770,233768,233767,233765,233764,233763,233758,233756,233747,233746,233740,233731,233730,233728,233723,233721,233718,233717,233716,233712,233709,233703,233701,233693,233691,233686,233680,233679,233674,233672,233671,233669,233668,233667,233666,233661,233660,233659,233657,233655,233653,233650,233649,233648,233647,233645,233643,233639,233635,233629,233628,233622,233621,233619,233618,233617,233616,233613,233611,233610,233609,233604,233600,233598,233597,233594,233593,233590,233584,233581,233579,233578,233571,233570,233566,233563,233561,233557,233553,233548,233545,233541,233537,233532,233531,233529,233527,233524,233520,233516,233515,233507,233500,233499,233498,233494,233487,233478,233468,233465,233464,233461,233459,233457,233452,233451,233450,233441,233432,233428,233427,233421,233419,233417,233416,233409,233401,233399,233385,233380,233379,233378,233375,233372,233370,233367,233366,233362,233358,233354,233350,233349,233348,233343,233340,233337,233334,233333,233332,233328,233327,233326,233325,233324,233323,233318,233317,233314,233313,233305,233298,233296,233293,233290,233287,233286,233285,233284,233283,233280,233279,233275,233274,233273,233270,233268,233264,233262,233261,233260,233259,233252,233242,233237,233236,233234,233214,233210,233207,233203,233197,233196,233185,233181,233180,233177,233171,233167,233166,233163,233160,233158,233157,233155,233153,233149,233148,233142,233141,233140,233139,233135,233133,233131,233130,233121,233120,233119,233114,233113,233112,233110,233109,233101,233099,233090,233087,233085,233081,233077,233076,233075,233073,233072,233068,233066,233056,233054,233051,233049,233045,233043,233029,233027,233017,233016,233015,233010,233009,233008,233006,233001,233000,232997,232996,232995,232994,232993,232991,232990,232989,232985,232982,232980,232978,232977,232975,232973,232970,232969,232966,232964,232963,232956,232955,232953,232952,232949,232948,232946,232931,232929,232927,232923,232922,232918,232911,232910,232904,232894,232893,232887,232886,232879,232878,232877,232872,232867,232866,232859,232857,232849,232843,232842,232840,232839,232837,232834,232831,232825,232824,232822,232821,232818,232817,232812,232810,232808,232806,232804,232797,232793,232792,232789,232787,232785,232783,232782,232780,232779,232777,232775,232773,232766,232765,232764,232760,232759,232755,232754,232752,232750,232746,232745,232744,232741,232740,232736,232734,232732,232730,232729,232728,232719,232718,232717,232713,232710,232706,232704,232702,232699,232697,232695,232694,232693,232688,232687,232685,232683,232680,232679,232677,232676,232675,232674,232670,232669,232667,232666,232665,232664,232663,232662,232661,232655,232654,232653,232652,232651,232649,232648,232645,232644,232641,232639,232638,232634,232633,232631,232628,232626,232624,232622,232613,232611,232610,232608,232607,232605,232601,232597,232594,232591,232589,232579,232571,232565,232560,232559,232555,232551,232549,232546,232545,232544,232543,232537,232536,232525,232518,232512,232511,232509,232503,232502,232501,232499,232496,232495,232493,232488,232485,232483,232482,232481,232478,232475,232473,232472,232469,232467,232466,232463,232459,232455,232454,232451,232448,232445,232443,232439,232437,232435,232434,232432,232430,232422,232417,232414,232412,232411,232410,232407,232400,232398,232396,232394,232388,232386,232380,232379,232375,232374,232369,232364,232361,232358,232356,232355,232352,232350,232349,232344,232342,232339,232333,232330,232327,232326,232325,232324,232322,232318,232306,232301,232296,232294,232291,232287,232286,232284,232279,232276,232273,232266,232263,232261,232258,232257,232256,232253,232251,232241,232240,232239,232236,232221,232219,232218,232216,232202,232201,232200,232198,232197,232189,232187,232175,232174,232173,232169,232168,232166,232160,232159,232158,232156,232151,232146,232138,232135,232130,232129,232127,232125,232123,232102,232099,232097,232092,232089,232088,232086,232085,232083,232082,232081,232080,232078,232077,232075,232074,232070,232068,232065,232064,232061,232059,232058,232054,232052,232051,232049,232047,232044,232036,232032,232031,232028,232025,232023,232021,232017,232013,232011,232010,232001,231999,231997,231986,231979,231977,231974,231971,231961,231954,231940,231935,231933,231928,231927,231917,231916,231914,231902,231899,231895,231894,231887,231885,231884,231882,231881,231879,231878,231875,231872,231871,231870,231869,231868,231864,231861,231860,231859,231854,231848,231842,231841,231839,231838,231837,231831,231828,231822,231821,231820,231798,231791,231789,231786,231779,231757,231754,231753,231751,231750,231746,231744,231742,231741,231740,231739,231733,231732,231731,231727,231725,231720,231719,231718,231716,231715,231714,231713,231712,231709,231708,231698,231695,231692,231685,231680,231679,231677,231676,231667,231663,231659,231658,231656,231652,231651,231649,231648,231645,231640,231635,231634,231632,231630,231629,231627,231625,231618,231615,231614,231610,231609,231604,231602,231598,231597,231595,231593,231592,231591,231590,231587,231586,231585,231584,231581,231580,231574,231573,231566,231565,231563,231560,231554,231553,231552,231543,231541,231540,231538,231536,231531,231530,231521,231519,231515,231513,231512,231511,231507,231503,231501,231499,231497,231495,231489,231488,231481,231480,231477,231474,231473,231472,231471,231469,231456,231451,231445,231444,231443,231439,231436,231422,231418,231414,231411,231407,231404,231403,231402,231400,231391,231390,231388,231387,231386,231383,231381,231379,231378,231377,231372,231364,231360,231359,231358,231355,231354,231348,231347,231345,231344,231342,231340,231339,231337,231330,231327,231326,231324,231323,231320,231319,231317,231315,231314,231311,231310,231309,231307,231303,231302,231300,231292,231291,231290,231288,231287,231286,231281,231280,231277,231275,231274,231273,231272,231270,231269,231267,231257,231250,231245,231240,231237,231227,231225,231220,231216,231211,231209,231207,231205,231202,231201,231190,231189,231188,231186,231183,231181,231176,231175,231174,231173,231169,231167,231165,231164,231161,231160,231157,231156,231155,231148,231147,231145,231144,231143,231128,231125,231123,231121,231115,231111,231110,231107,231106,231103,231102,231096,231092,231091,231087,231085,231084,231082,231080,231079,231078,231068,231066,231064,231059,231058,231057,231056,231055,231054,231053,231046,231045,231044,231042,231032,231028,231027,231023,231020,231016,231014,231012,231004,231002,230998,230993,230992,230991,230987,230984,230983,230980,230977,230967,230966,230961,230960,230959,230958,230957,230956,230952,230950,230948,230947,230945,230943,230942,230941,230938,230931,230930,230929,230915,230913,230912,230904,230903,230900,230898,230890,230885,230882,230880,230874,230873,230871,230869,230867,230863,230856,230855,230852,230850,230845,230844,230835,230833,230824,230822,230818,230811,230810,230809,230808,230807,230805,230804,230800,230797,230793,230792,230785,230779,230778,230775,230767,230764,230758,230757,230756,230753,230750,230749,230748,230747,230743,230742,230737,230733,230727,230724,230722,230721,230719,230718,230717,230715,230713,230708,230704,230700,230699,230698,230695,230687,230686,230681,230680,230679,230678,230676,230674,230672,230671,230670,230669,230668,230665,230664,230663,230660,230658,230655,230651,230647,230645,230639,230637,230636,230635,230634,230625,230620,230618,230616,230614,230613,230599,230598,230592,230591,230581,230578,230576,230575,230570,230562,230561,230555,230545,230542,230533,230523,230521,230519,230513,230512,230511,230509,230503,230501,230499,230493,230492,230489,230488,230484,230479,230477,230475,230474,230473,230471,230470,230468,230465,230459,230455,230453,230444,230438,230434,230426,230419,230416,230415,230414,230413,230412,230411,230409,230408,230407,230401,230400,230399,230365,230361,230358,230357,230356,230355,230351,230346,230338,230335,230334,230324,230320,230317,230314,230310,230309,230308,230305,230298,230297,230293,230284,230280,230279,230278,230276,230274,230273,230267,230265,230262,230260,230257,230254,230252,230247,230244,230227,230218,230214,230209,230207,230203,230199,230198,230197,230193,230192,230191,230187,230184,230181,230180,230179,230176,230174,230171,230162,230160,230157,230156,230154,230153,230152,230151,230144,230141,230140,230139,230129,230128,230125,230124,230123,230122,230119,230117,230116,230107,230099,230098,230097,230095,230086,230083,230079,230065,230064,230063,230062,230060,230058,230056,230055,230052,230051,230048,230041,230034,230033,230032,230031,230027,230023,230021,230020,230019,230013,230010,230003,230002,230000,229998,229994,229993,229990,229988,229982,229979,229973,229971,229970,229969,229964,229963,229962,229961,229956,229947,229944,229942,229941,229939,229938,229936,229925,229913,229911,229902,229901,229899,229898,229895,229890,229889,229888,229886,229882,229876,229871,229870,229869,229866,229863,229861,229859,229856,229854,229849,229847,229845,229838,229826,229824,229823,229821,229820,229817,229815,229811,229810,229799,229796,229795,229793,229780,229778,229777,229771,229770,229768,229767,229763,229762,229760,229759,229757,229756,229753,229752,229751,229750,229749,229748,229745,229740,229735,229734,229733,229731,229729,229727,229721,229720,229715,229714,229712,229710,229705,229703,229702,229701,229698,229697,229696,229690,229688,229686,229683,229677,229666,229664,229662,229657,229652,229650,229644,229640,229628,229624,229619,229617,229615,229614,229609,229608,229595,229593,229591,229589,229587,229583,229582,229581,229580,229578,229577,229567,229565,229564,229563,229562,229561,229559,229552,229551,229549,229548,229542,229541,229539,229538,229535,229531,229530,229528,229526,229519,229516,229515,229514,229512,229511,229510,229509,229505,229502,229501,229498,229496,229493,229492,229491,229489,229486,229482,229480,229471,229468,229467,229466,229465,229463,229461,229459,229455,229453,229450,229449,229447,229446,229444,229443,229441,229440,229436,229434,229431,229417,229416,229413,229412,229411,229410,229405,229403,229402,229401,229400,229398,229391,229389,229387,229385,229383,229382,229376,229375,229372,229370,229367,229365,229362,229360,229358,229355,229354,229353,229349,229346,229345,229344,229343,229342,229341,229340,229337,229333,229332,229331,229329,229327,229326,229325,229323,229321,229320,229318,229313,229305,229301,229299,229298,229296,229293,229292,229291,229289,229287,229286,229284,229279,229278,229273,229270,229268,229267,229262,229260,229254,229252,229247,229235,229225,229224,229221,229220,229219,229218,229216,229214,229210,229209,229204,229200,229196,229186,229185,229184,229182,229181,229176,229175,229173,229172,229170,229169,229168,229167,229163,229161,229158,229154,229152,229151,229146,229145,229144,229136,229135,229134,229133,229127,229125,229123,229106,229104,229101,229100,229094,229093,229092,229089,229084,229083,229082,229073,229072,229071,229070,229067,229066,229061,229056,229055,229049,229048,229045,229027,229026,229022,229020,229019,229013,229012,229011,229010,229009,229008,229007,229005,229002,229001,228999,228995,228994,228985,228982,228978,228973,228967,228965,228962,228961,228958,228954,228944,228942,228940,228937,228933,228932,228930,228927,228926,228924,228923,228921,228915,228914,228913,228910,228908,228898,228891,228888,228886,228881,228880,228879,228876,228875,228872,228871,228868,228866,228864,228863,228861,228860,228853,228849,228848,228847,228842,228838,228836,228835,228834,228826,228823,228821,228814,228808,228804,228799,228795,228790,228789,228788,228782,228779,228778,228777,228776,228774,228771,228766,228765,228761,228758,228755,228753,228752,228751,228748,228745,228744,228743,228739,228738,228736,228735,228734,228732,228730,228728,228725,228720,228718,228717,228710,228709,228706,228704,228702,228701,228699,228698,228695,228693,228690,228680,228678,228662,228659,228651,228650,228648,228647,228645,228643,228642,228641,228640,228639,228632,228630,228628,228627,228625,228624,228622,228620,228619,228616,228614,228613,228608,228607,228603,228598,228596,228595,228594,228593,228590,228588,228587,228586,228585,228584,228582,228580,228578,228574,228571,228570,228567,228566,228558,228556,228550,228549,228548,228538,228537,228536,228533,228521,228520,228517,228515,228508,228501,228500,228493,228488,228477,228467,228453,228452,228446,228445,228444,228443,228441,228437,228436,228431,228430,228429,228423,228422,228420,228419,228418,228415,228412,228411,228409,228404,228397,228396,228395,228394,228392,228388,228385,228384,228382,228381,228378,228376,228375,228373,228371,228369,228368,228366,228365,228363,228359,228358,228353,228343,228340,228338,228333,228328,228327,228321,228320,228319,228316,228312,228310,228302,228301,228300,228296,228290,228288,228281,228269,228267,228265,228262,228259,228257,228255,228252,228250,228249,228248,228246,228245,228243,228242,228239,228236,228235,228234,228232,228231,228229,228228,228227,228226,228222,228221,228215,228214,228213,228212,228211,228210,228209,228206,228205,228201,228198,228197,228193,228192,228191,228187,228185,228182,228177,228176,228169,228168,228167,228159,228148,228144,228143,228141,228139,228138,228136,228132,228131,228125,228118,228117,228112,228110,228109,228107,228106,228105,228104,228103,228102,228100,228097,228096,228095,228092,228091,228089,228088,228083,228080,228078,228073,228071,228069,228067,228066,228062,228061,228060,228059,228056,228054,228053,228050,228047,228042,228041,228039,228037,228036,228035,228030,228025,228023,228022,228019,228014,228013,228012,228007,228005,228002,227996,227994,227993,227992,227991,227989,227987,227983,227979,227977,227973,227972,227965,227962,227961,227956,227950,227947,227946,227939,227938,227936,227935,227934,227931,227930,227928,227925,227919,227916,227908,227907,227906,227900,227894,227892,227886,227884,227882,227880,227878,227877,227873,227870,227869,227868,227866,227865,227860,227857,227854,227852,227851,227849,227844,227842,227839,227838,227833,227831,227829,227825,227821,227819,227813,227811,227810,227805,227802,227799,227798,227796,227795,227790,227787,227785,227783,227782,227773,227768,227765,227764,227749,227747,227744,227742,227739,227738,227726,227716,227714,227713,227710,227709,227705,227704,227703,227702,227698,227695,227692,227689,227688,227687,227686,227683,227682,227681,227680,227679,227676,227671,227670,227667,227666,227665,227661,227659,227649,227647,227646,227637,227635,227632,227631,227630,227629,227627,227623,227621,227618,227617,227615,227612,227611,227606,227604,227602,227600,227599,227598,227595,227593,227592,227590,227586,227585,227584,227582,227581,227580,227578,227577,227575,227573,227572,227569,227568,227567,227563,227552,227550,227544,227542,227541,227540,227538,227535,227524,227519,227518,227514,227513,227512,227509,227508,227501,227499,227497,227494,227492,227486,227482,227478,227474,227471,227468,227467,227462,227461,227456,227452,227448,227443,227439,227438,227437,227434,227431,227429,227424,227418,227415,227413,227412,227410,227407,227404,227402,227401,227400,227399,227396,227394,227392,227387,227386,227383,227382,227380,227378,227377,227376,227374,227373,227372,227365,227364,227362,227359,227357,227354,227352,227346,227344,227338,227337,227336,227332,227330,227329,227326,227323,227322,227318,227317,227316,227299,227298,227296,227291,227283,227281,227280,227279,227278,227277,227275,227273,227272,227271,227269,227268,227267,227266,227262,227258,227257,227252,227249,227248,227247,227246,227244,227239,227234,227229,227228,227225,227224,227221,227217,227211,227209,227206,227205,227199,227196,227195,227189,227187,227185,227181,227180,227178,227174,227171,227165,227163,227160,227158,227157,227156,227155,227154,227152,227151,227148,227147,227146,227143,227142,227141,227140,227139,227135,227134,227133,227132,227131,227128,227125,227122,227114,227112,227106,227104,227103,227102,227101,227096,227095,227094,227093,227090,227089,227088,227087,227085,227080,227076,227075,227074,227073,227072,227067,227065,227061,227060,227059,227057,227056,227051,227050,227044,227042,227033,227032,227026,227023,227022,227017,227016,227015,227014,227012,227007,227006,227005,227004,227002,226991,226990,226989,226988,226987,226986,226985,226984,226983,226981,226978,226977,226970,226967,226966,226961,226958,226950,226948,226946,226945,226942,226940,226939,226938,226937,226929,226924,226923,226921,226920,226919,226917,226916,226910,226906,226903,226901,226900,226897,226894,226885,226882,226881,226879,226875,226874,226872,226865,226855,226851,226849,226846,226844,226841,226837,226836,226829,226828,226827,226825,226824,226820,226818,226812,226808,226806,226804,226802,226799,226797,226789,226787,226785,226780,226779,226774,226773,226768,226766,226762,226759,226756,226753,226751,226749,226748,226743,226742,226741,226740,226739,226735,226729,226728,226726,226724,226720,226716,226715,226712,226710,226708,226704,226702,226700,226699,226697,226695,226693,226690,226689,226688,226679,226678,226677,226676,226675,226671,226670,226669,226665,226662,226661,226659,226654,226650,226648,226647,226644,226643,226638,226634,226633,226631,226630,226626,226620,226616,226614,226609,226604,226603,226598,226597,226596,226591,226588,226585,226577,226573,226572,226570,226567,226563,226560,226558,226556,226555,226554,226553,226549,226544,226533,226529,226527,226525,226522,226519,226517,226516,226515,226513,226511,226510,226508,226502,226500,226498,226497,226494,226492,226490,226484,226480,226478,226477,226472,226468,226467,226464,226462,226458,226454,226453,226451,226443,226442,226441,226438,226437,226429,226427,226425,226424,226421,226418,226417,226415,226414,226406,226404,226399,226398,226392,226391,226387,226384,226381,226376,226375,226374,226373,226370,226369,226360,226358,226354,226346,226344,226343,226341,226332,226331,226329,226328,226326,226324,226320,226319,226318,226313,226307,226305,226302,226298,226297,226294,226292,226291,226286,226281,226279,226276,226274,226270,226263,226256,226255,226248,226247,226246,226242,226234,226232,226231,226229,226225,226219,226218,226217,226210,226209,226208,226206,226204,226198,226196,226194,226190,226186,226183,226182,226175,226173,226172,226169,226167,226162,226161,226160,226159,226157,226156,226152,226150,226148,226146,226145,226139,226138,226136,226134,226132,226124,226120,226116,226115,226114,226112,226109,226108,226100,226099,226097,226092,226091,226090,226088,226086,226081,226079,226063,226062,226061,226058,226057,226055,226054,226052,226050,226049,226048,226046,226045,226042,226038,226036,226035,226021,226013,226007,226004,226000,225999,225990,225989,225980,225979,225978,225976,225972,225965,225962,225961,225957,225956,225954,225949,225948,225944,225942,225941,225940,225939,225935,225934,225930,225924,225923,225922,225915,225914,225913,225912,225909,225907,225905,225904,225903,225902,225900,225896,225886,225885,225884,225878,225876,225874,225872,225870,225869,225866,225864,225862,225861,225860,225858,225857,225855,225854,225852,225850,225849,225846,225837,225834,225826,225822,225820,225816,225813,225812,225811,225809,225802,225801,225798,225795,225791,225790,225788,225786,225778,225773,225772,225769,225763,225760,225759,225756,225755,225751,225746,225740,225739,225734,225733,225727,225722,225721,225718,225717,225716,225712,225704,225703,225699,225697,225685,225682,225678,225672,225671,225668,225659,225658,225657,225654,225651,225645,225643,225638,225634,225633,225631,225629,225628,225627,225626,225624,225622,225620,225617,225616,225613,225606,225605,225603,225602,225601,225600,225599,225598,225596,225595,225586,225585,225584,225581,225580,225579,225578,225575,225574,225566,225563,225560,225559,225558,225557,225556,225553,225546,225540,225535,225533,225530,225526,225509,225508,225507,225506,225505,225501,225497,225495,225480,225477,225475,225466,225464,225462,225459,225444,225440,225436,225432,225427,225423,225420,225418,225414,225411,225410,225409,225407,225406,225405,225404,225400,225396,225395,225394,225391,225389,225379,225377,225376,225374,225368,225364,225356,225349,225345,225344,225343,225339,225335,225334,225318,225316,225315,225313,225308,225306,225304,225302,225294,225282,225279,225278,225264,225263,225261,225239,225216,225210,225208,225201,225200,225199,225190,225183,225180,225179,225174,225172,225170,225169,225168,225167,225161,225159,225155,225148,225136,225134,225126,225125,225114,225113,225112,225111,225110,225108,225101,225098,225095,225089,225087,225086,225083,225081,225077,225073,225070,225064,225062,225057,225056,225055,225052,225048,225044,225043,225041,225039,225038,225037,225034,225033,225032,225030,225029,225024,225019,225018,225017,225015,225014,225012,225011,225007,225006,224998,224997,224993,224990,224987,224985,224983,224978,224977,224975,224973,224972,224969,224964,224963,224962,224959,224955,224951,224950,224939,224938,224937,224935,224934,224933,224927,224925,224924,224921,224920,224919,224915,224914,224910,224908,224907,224906,224904,224895,224893,224892,224889,224888,224884,224882,224881,224879,224877,224876,224874,224872,224871,224866,224862,224859,224858,224857,224853,224852,224847,224844,224842,224841,224840,224838,224837,224833,224831,224829,224828,224827,224826,224819,224818,224816,224814,224811,224808,224806,224804,224803,224801,224800,224799,224794,224793,224792,224789,224782,224779,224774,224772,224762,224760,224758,224757,224756,224754,224749,224748,224747,224744,224741,224739,224735,224732,224731,224730,224729,224726,224725,224722,224721,224720,224718,224717,224716,224711,224710,224700,224699,224698,224692,224688,224685,224681,224679,224675,224673,224672,224671,224668,224667,224663,224660,224659,224656,224654,224651,224650,224648,224645,224644,224643,224642,224641,224639,224638,224637,224636,224635,224631,224630,224629,224627,224626,224624,224623,224618,224616,224615,224611,224610,224609,224601,224597,224592,224590,224585,224581,224580,224576,224575,224574,224573,224569,224565,224554,224553,224552,224551,224549,224546,224542,224539,224536,224531,224527,224525,224524,224522,224520,224519,224515,224509,224508,224504,224502,224501,224498,224496,224495,224490,224489,224487,224485,224484,224482,224481,224479,224476,224469,224468,224462,224461,224456,224452,224450,224449,224446,224438,224437,224429,224426,224424,224417,224411,224410,224405,224404,224402,224400,224392,224391,224389,224386,224385,224383,224382,224381,224379,224377,224376,224374,224373,224371,224370,224362,224361,224358,224355,224351,224347,224343,224342,224339,224338,224332,224331,224329,224328,224326,224321,224319,224318,224317,224316,224312,224309,224301,224300,224297,224292,224291,224286,224284,224279,224275,224274,224273,224272,224270,224269,224268,224265,224259,224258,224255,224246,224244,224243,224238,224237,224234,224232,224227,224225,224224,224223,224220,224219,224218,224217,224215,224212,224207,224205,224203,224201,224200,224198,224196,224194,224188,224187,224186,224185,224179,224178,224176,224167,224166,224165,224164,224162,224157,224155,224153,224152,224149,224147,224143,224138,224137,224136,224134,224133,224130,224129,224128,224127,224126,224125,224124,224123,224121,224118,224117,224116,224115,224111,224107,224104,224102,224098,224097,224095,224093,224092,224089,224087,224084,224083,224081,224080,224079,224078,224075,224072,224070,224069,224067,224065,224063,224061,224060,224056,224055,224053,224048,224044,224043,224042,224040,224038,224037,224036,224031,224028,224023,224019,224018,224015,224012,224011,224009,224008,224006,224003,223999,223998,223997,223996,223995,223990,223987,223986,223985,223984,223983,223977,223975,223974,223973,223971,223968,223966,223965,223961,223949,223944,223941,223940,223939,223937,223935,223932,223930,223929,223927,223924,223923,223922,223921,223920,223919,223918,223916,223915,223913,223909,223907,223905,223904,223901,223897,223892,223891,223889,223887,223885,223883,223882,223878,223877,223876,223873,223872,223871,223870,223868,223867,223866,223864,223860,223857,223856,223855,223853,223848,223846,223845,223844,223840,223837,223834,223830,223828,223826,223825,223824,223819,223811,223810,223806,223802,223801,223799,223798,223796,223793,223791,223788,223784,223782,223780,223777,223776,223774,223773,223771,223769,223767,223765,223756,223753,223751,223749,223748,223743,223742,223741,223740,223739,223735,223731,223730,223728,223727,223722,223716,223715,223714,223709,223708,223704,223701,223699,223696,223695,223694,223692,223689,223688,223687,223686,223679,223675,223673,223671,223665,223662,223659,223658,223656,223652,223648,223647,223645,223644,223636,223635,223634,223632,223630,223628,223626,223623,223621,223620,223616,223615,223613,223612,223609,223606,223599,223595,223593,223592,223590,223587,223586,223585,223580,223579,223578,223577,223576,223575,223572,223570,223565,223561,223560,223559,223557,223553,223552,223550,223548,223547,223544,223542,223536,223525,223524,223520,223518,223517,223516,223514,223513,223512,223511,223510,223505,223503,223502,223501,223495,223492,223485,223482,223479,223477,223474,223472,223470,223469,223465,223463,223462,223457,223456,223455,223453,223452,223449,223448,223445,223436,223434,223430,223429,223428,223427,223426,223423,223422,223417,223415,223414,223404,223401,223398,223395,223394,223393,223388,223387,223383,223382,223381,223380,223379,223378,223375,223374,223373,223372,223371,223368,223363,223361,223360,223359,223358,223356,223355,223354,223353,223352,223351,223350,223348,223344,223342,223340,223339,223337,223336,223335,223334,223332,223329,223327,223326,223325,223321,223320,223319,223318,223317,223315,223313,223312,223310,223306,223302,223299,223288,223287,223276,223273,223272,223271,223266,223262,223259,223257,223256,223254,223253,223251,223241,223235,223226,223225,223224,223223,223222,223221,223217,223215,223214,223213,223212,223211,223210,223207,223203,223201,223199,223197,223195,223194,223192,223191,223190,223189,223188,223187,223186,223185,223184,223183,223182,223181,223180,223178,223176,223175,223174,223171,223169,223165,223164,223163,223159,223155,223154,223148,223146,223145,223140,223139,223136,223134,223132,223130,223129,223128,223127,223125,223124,223123,223122,223120,223116,223115,223114,223112,223109,223108,223106,223105,223104,223103,223101,223098,223097,223094,223084,223083,223076,223074,223073,223072,223071,223066,223065,223064,223063,223058,223054,223053,223050,223049,223047,223045,223044,223035,223027,223021,223019,223014,223005,223004,223003,223002,223001,222999,222998,222995,222994,222993,222992,222987,222986,222985,222982,222975,222973,222972,222970,222968,222964,222962,222959,222951,222948,222940,222939,222936,222933,222930,222928,222924,222920,222919,222918,222917,222915,222914,222913,222910,222909,222906,222905,222902,222901,222900,222899,222896,222895,222894,222893,222892,222880,222875,222871,222867,222864,222861,222858,222851,222846,222845,222843,222842,222841,222839,222838,222837,222831,222826,222825,222824,222822,222821,222818,222815,222814,222806,222804,222802,222799,222798,222795,222793,222792,222790,222785,222784,222783,222782,222780,222776,222775,222774,222773,222769,222768,222767,222764,222762,222761,222760,222759,222757,222753,222750,222744,222743,222742,222740,222739,222738,222732,222731,222727,222726,222721,222719,222717,222713,222711,222709,222707,222703,222700,222699,222698,222697,222695,222693,222692,222686,222685,222684,222682,222681,222676,222674,222673,222672,222670,222667,222666,222665,222662,222661,222658,222650,222645,222644,222641,222640,222635,222634,222631,222629,222628,222622,222620,222615,222614,222612,222609,222608,222607,222604,222599,222595,222594,222593,222591,222590,222586,222584,222582,222581,222579,222572,222571,222568,222567,222566,222560,222556,222553,222551,222550,222547,222546,222545,222540,222537,222532,222531,222530,222525,222524,222522,222521,222520,222518,222515,222513,222512,222509,222507,222506,222501,222500,222499,222496,222495,222493,222492,222490,222487,222476,222472,222470,222469,222467,222461,222460,222459,222458,222457,222452,222448,222447,222446,222444,222441,222440,222439,222437,222435,222434,222433,222432,222431,222430,222422,222420,222419,222418,222417,222415,222411,222410,222407,222406,222404,222402,222399,222397,222391,222390,222388,222382,222380,222378,222377,222376,222374,222369,222367,222364,222363,222360,222359,222356,222354,222353,222351,222350,222345,222341,222340,222336,222335,222334,222333,222331,222330,222329,222327,222323,222322,222319,222318,222317,222307,222305,222302,222301,222300,222299,222297,222294,222292,222291,222289,222287,222285,222283,222280,222277,222276,222275,222273,222272,222271,222268,222267,222266,222265,222264,222260,222259,222258,222256,222254,222253,222252,222251,222249,222247,222243,222231,222230,222227,222226,222225,222222,222221,222220,222212,222210,222208,222206,222201,222200,222199,222197,222195,222194,222193,222189,222188,222187,222181,222178,222176,222172,222171,222170,222169,222166,222164,222162,222161,222159,222157,222154,222153,222152,222150,222146,222145,222141,222134,222129,222128,222124,222121,222113,222112,222111,222109,222106,222104,222102,222101,222099,222092,222090,222088,222081,222080,222073,222072,222067,222066,222065,222064,222062,222061,222058,222055,222053,222051,222049,222048,222042,222037,222036,222034,222033,222031,222029,222027,222015,222010,222009,222004,222003,222002,221998,221994,221991,221990,221985,221983,221982,221981,221975,221971,221969,221967,221966,221964,221962,221960,221958,221951,221950,221946,221945,221943,221938,221937,221936,221935,221934,221928,221927,221926,221925,221924,221923,221921,221919,221918,221916,221914,221913,221912,221910,221909,221908,221905,221902,221898,221897,221896,221889,221883,221879,221876,221874,221861,221860,221859,221858,221855,221854,221853,221851,221849,221844,221843,221841,221840,221837,221836,221835,221833,221832,221831,221830,221829,221828,221827,221826,221821,221818,221815,221814,221808,221805,221803,221802,221799,221796,221795,221791,221790,221786,221783,221782,221781,221777,221776,221774,221773,221771,221768,221765,221764,221763,221757,221755,221753,221746,221740,221739,221738,221734,221733,221728,221726,221722,221720,221719,221715,221713,221711,221708,221706,221703,221702,221698,221695,221694,221689,221688,221685,221682,221678,221677,221673,221670,221668,221667,221661,221660,221659,221658,221657,221655,221652,221648,221647,221646,221645,221644,221642,221640,221638,221637,221636,221635,221634,221633,221632,221631,221630,221628,221627,221625,221624,221623,221621,221619,221617,221616,221615,221612,221607,221605,221604,221602,221596,221594,221588,221585,221584,221582,221579,221576,221575,221574,221573,221571,221570,221569,221566,221565,221562,221561,221560,221559,221553,221551,221550,221549,221548,221547,221546,221545,221544,221543,221542,221541,221540,221539,221538,221537,221536,221534,221533,221532,221531,221530,221529,221525,221523,221519,221517,221513,221512,221507,221505,221502,221501,221500,221498,221497,221486,221485,221484,221482,221477,221476,221475,221474,221472,221468,221466,221463,221462,221461,221456,221455,221453,221452,221451,221450,221449,221447,221446,221444,221441,221439,221437,221431,221426,221422,221418,221417,221416,221414,221412,221409,221408,221407,221403,221402,221401,221398,221397,221396,221394,221390,221388,221385,221380,221379,221378,221377,221371,221369,221366,221365,221363,221360,221359,221357,221355,221352,221351,221348,221344,221341,221334,221331,221319,221316,221315,221312,221310,221309,221306,221304,221303,221300,221299,221298,221297,221296,221292,221286,221275,221271,221270,221268,221267,221266,221265,221264,221263,221262,221261,221258,221257,221255,221246,221240,221239,221237,221236,221229,221226,221225,221222,221221,221220,221219,221218,221214,221212,221211,221209,221208,221206,221204,221202,221199,221195,221194,221193,221191,221187,221186,221185,221184,221183,221182,221181,221178,221176,221175,221169,221167,221165,221160,221156,221153,221149,221148,221145,221142,221134,221133,221129,221123,221122,221112,221110,221108,221106,221096,221094,221093,221074,221073,221068,221064,221063,221062,221056,221053,221052,221051,221050,221048,221043,221037,221033,221032,221031,221027,221024,221022,221021,221020,221018,221011,221009,221007,221006,221005,221002,221001,220993,220991,220989,220988,220987,220984,220981,220980,220978,220976,220975,220973,220971,220970,220969,220967,220966,220965,220961,220960,220956,220955,220954,220953,220950,220949,220948,220946,220935,220934,220933,220928,220926,220925,220921,220916,220911,220904,220902,220901,220900,220898,220896,220894,220892,220891,220890,220889,220880,220879,220874,220873,220872,220868,220861,220860,220858,220857,220856,220853,220852,220850,220847,220846,220844,220843,220842,220841,220837,220833,220832,220830,220829,220828,220827,220826,220824,220820,220814,220813,220812,220811,220808,220807,220806,220805,220802,220797,220794,220790,220788,220786,220785,220783,220782,220779,220777,220773,220770,220768,220767,220766,220765,220763,220752,220749,220744,220743,220742,220739,220736,220734,220731,220730,220727,220726,220724,220723,220722,220720,220719,220715,220713,220712,220710,220708,220704,220703,220702,220701,220692,220687,220686,220684,220682,220681,220677,220675,220674,220667,220666,220665,220664,220662,220661,220659,220658,220657,220656,220652,220651,220649,220648,220646,220643,220640,220638,220636,220635,220632,220627,220626,220625,220623,220622,220621,220620,220616,220611,220607,220606,220605,220604,220600,220596,220595,220594,220593,220589,220585,220581,220579,220577,220572,220571,220570,220566,220565,220563,220554,220550,220549,220548,220547,220545,220542,220537,220534,220528,220521,220520,220519,220518,220510,220509,220507,220505,220497,220491,220489,220486,220485,220483,220473,220471,220469,220468,220467,220463,220455,220454,220450,220449,220442,220441,220440,220432,220431,220430,220429,220427,220426,220422,220420,220419,220418,220417,220411,220410,220409,220407,220404,220401,220397,220392,220386,220384,220382,220381,220380,220379,220377,220369,220368,220367,220364,220362,220361,220352,220351,220349,220345,220344,220343,220341,220339,220338,220336,220332,220329,220328,220324,220322,220321,220319,220314,220307,220303,220300,220299,220296,220293,220292,220288,220285,220282,220280,220277,220275,220272,220271,220268,220267,220265,220262,220258,220256,220246,220245,220239,220236,220235,220233,220232,220230,220229,220228,220227,220224,220223,220218,220217,220212,220208,220206,220204,220203,220200,220199,220198,220197,220196,220192,220183,220182,220181,220179,220178,220171,220168,220167,220163,220162,220161,220159,220158,220157,220153,220150,220149,220148,220146,220145,220143,220140,220137,220136,220129,220127,220121,220116,220114,220106,220103,220101,220100,220098,220097,220094,220092,220090,220085,220083,220080,220079,220077,220076,220075,220073,220072,220070,220069,220068,220066,220059,220057,220055,220052,220048,220046,220045,220041,220040,220038,220036,220035,220031,220030,220029,220026,220025,220019,220018,220017,220015,220010,220009,220008,220004,220003,220000,219999,219993,219987,219986,219985,219981,219979,219978,219977,219975,219971,219969,219968,219967,219966,219962,219957,219956,219955,219954,219953,219949,219948,219946,219945,219943,219940,219937,219936,219930,219927,219923,219920,219914,219913,219912,219908,219906,219900,219898,219894,219892,219891,219889,219888,219883,219882,219881,219880,219878,219877,219875,219872,219860,219859,219856,219852,219851,219849,219846,219840,219839,219837,219833,219832,219831,219829,219828,219824,219823,219822,219821,219819,219818,219815,219810,219803,219798,219797,219792,219791,219790,219789,219787,219786,219785,219784,219782,219781,219780,219778,219773,219772,219771,219770,219766,219764,219763,219761,219760,219759,219758,219753,219752,219748,219747,219744,219743,219740,219738,219735,219734,219731,219730,219729,219726,219718,219715,219714,219710,219709,219708,219704,219700,219696,219693,219690,219688,219686,219685,219684,219683,219681,219680,219677,219676,219674,219664,219662,219659,219658,219656,219654,219653,219651,219650,219649,219648,219646,219643,219640,219635,219632,219631,219630,219627,219624,219620,219619,219615,219614,219613,219612,219611,219610,219598,219596,219595,219592,219591,219587,219578,219576,219573,219572,219570,219569,219568,219564,219562,219560,219558,219547,219545,219544,219542,219541,219540,219539,219538,219534,219533,219532,219530,219525,219521,219519,219517,219516,219514,219513,219511,219509,219507,219505,219504,219502,219501,219500,219499,219498,219497,219496,219494,219491,219490,219488,219487,219486,219485,219480,219479,219478,219475,219473,219472,219471,219470,219467,219461,219456,219454,219453,219446,219443,219442,219441,219436,219435,219434,219429,219428,219423,219421,219417,219414,219412,219411,219409,219408,219404,219403,219400,219399,219394,219392,219391,219390,219389,219386,219385,219384,219383,219382,219380,219379,219371,219369,219368,219366,219365,219362,219359,219358,219357,219356,219354,219351,219346,219344,219343,219339,219338,219336,219335,219334,219332,219331,219329,219327,219323,219321,219320,219319,219318,219315,219314,219308,219307,219306,219305,219304,219299,219298,219296,219293,219291,219286,219281,219280,219279,219275,219272,219269,219268,219262,219260,219259,219253,219252,219251,219243,219242,219240,219236,219235,219233,219232,219231,219230,219227,219226,219223,219222,219221,219219,219218,219213,219205,219202,219201,219200,219193,219192,219189,219185,219184,219182,219181,219178,219168,219162,219161,219160,219154,219153,219151,219150,219149,219148,219147,219146,219145,219144,219140,219138,219137,219136,219124,219120,219119,219118,219111,219110,219109,219103,219099,219096,219092,219091,219088,219087,219086,219084,219083,219080,219079,219077,219074,219071,219069,219068,219066,219065,219064,219062,219061,219059,219055,219054,219053,219050,219049,219045,219044,219043,219036,219035,219033,219031,219030,219024,219023,219022,219020,219018,219013,219012,219011,219010,219009,219003,218997,218996,218992,218990,218989,218988,218987,218986,218983,218982,218978,218976,218971,218969,218968,218965,218962,218960,218956,218951,218950,218949,218948,218947,218945,218944,218942,218941,218940,218939,218937,218935,218934,218933,218931,218929,218927,218920,218905,218904,218892,218890,218882,218880,218877,218875,218874,218862,218859,218855,218854,218853,218851,218845,218844,218840,218839,218835,218832,218830,218826,218825,218822,218821,218820,218819,218818,218817,218811,218809,218808,218807,218806,218805,218804,218803,218800,218798,218797,218792,218791,218787,218785,218782,218781,218780,218777,218776,218774,218773,218772,218770,218764,218762,218761,218759,218757,218752,218751,218748,218746,218738,218737,218734,218732,218730,218729,218727,218721,218717,218716,218714,218713,218704,218695,218691,218689,218688,218686,218680,218678,218676,218672,218671,218669,218668,218667,218664,218663,218662,218659,218657,218656,218654,218653,218652,218650,218648,218645,218636,218632,218631,218630,218627,218626,218624,218622,218617,218614,218600,218599,218594,218593,218591,218590,218589,218584,218583,218582,218579,218577,218575,218572,218570,218566,218564,218561,218560,218558,218557,218554,218553,218548,218545,218544,218541,218540,218534,218533,218532,218530,218529,218528,218526,218525,218516,218514,218511,218508,218506,218502,218501,218500,218497,218492,218491,218486,218485,218483,218481,218480,218479,218478,218477,218474,218473,218471,218470,218468,218467,218466,218464,218462,218461,218460,218458,218457,218452,218450,218445,218442,218439,218438,218433,218432,218428,218427,218425,218421,218420,218419,218417,218415,218414,218411,218406,218400,218395,218390,218389,218387,218383,218377,218374,218372,218371,218370,218369,218368,218365,218364,218361,218359,218358,218357,218353,218351,218350,218349,218346,218340,218334,218333,218332,218330,218329,218327,218326,218322,218321,218320,218319,218317,218315,218314,218313,218312,218311,218306,218305,218304,218302,218300,218299,218298,218297,218296,218291,218289,218288,218284,218279,218278,218276,218275,218273,218272,218268,218267,218266,218264,218263,218262,218260,218259,218258,218256,218255,218254,218252,218246,218244,218243,218242,218241,218240,218237,218232,218230,218229,218227,218225,218222,218216,218206,218204,218203,218201,218200,218199,218197,218192,218191,218186,218181,218179,218178,218176,218170,218164,218160,218150,218146,218143,218142,218138,218135,218134,218131,218130,218124,218123,218119,218114,218107,218100,218099,218093,218088,218085,218084,218082,218065,218064,218061,218060,218058,218052,218051,218046,218041,218040,218038,218036,218032,218031,218030,218028,218024,218022,218020,218018,218016,218014,218013,218012,218011,218008,218007,218006,218005,218003,217997,217994,217991,217990,217988,217987,217985,217982,217981,217969,217968,217962,217960,217958,217955,217951,217950,217946,217928,217927,217926,217925,217924,217923,217922,217921,217920,217919,217918,217917,217916,217912,217911,217910,217908,217907,217905,217904,217900,217899,217898,217897,217894,217891,217890,217889,217888,217887,217885,217883,217882,217880,217878,217876,217874,217873,217871,217870,217861,217859,217858,217856,217853,217852,217850,217846,217843,217842,217839,217835,217831,217830,217823,217820,217818,217817,217813,217812,217811,217810,217805,217801,217800,217799,217793,217792,217791,217790,217787,217784,217781,217780,217779,217778,217777,217773,217772,217771,217768,217767,217766,217763,217762,217761,217753,217752,217745,217721,217719,217717,217716,217713,217711,217710,217705,217698,217697,217696,217695,217694,217691,217686,217685,217683,217682,217681,217678,217677,217673,217672,217671,217670,217669,217664,217659,217658,217657,217654,217646,217644,217641,217640,217638,217635,217631,217630,217628,217625,217624,217621,217619,217618,217617,217615,217614,217610,217609,217607,217605,217603,217601,217599,217598,217597,217596,217595,217594,217592,217587,217586,217583,217578,217577,217573,217572,217571,217570,217567,217565,217564,217563,217562,217561,217556,217552,217550,217545,217544,217543,217538,217537,217536,217533,217532,217531,217530,217526,217525,217524,217521,217520,217519,217515,217514,217513,217511,217508,217504,217503,217499,217497,217496,217492,217490,217489,217485,217480,217476,217472,217471,217469,217466,217465,217464,217462,217461,217460,217459,217457,217454,217453,217451,217448,217441,217440,217439,217437,217436,217434,217432,217430,217429,217426,217425,217416,217415,217414,217408,217406,217403,217401,217400,217397,217395,217393,217389,217388,217387,217386,217385,217379,217368,217364,217362,217360,217358,217357,217353,217351,217350,217344,217342,217340,217339,217338,217337,217336,217334,217329,217328,217326,217322,217321,217320,217319,217315,217313,217311,217309,217307,217306,217304,217302,217301,217300,217299,217293,217291,217289,217278,217276,217273,217272,217270,217269,217263,217254,217253,217252,217251,217250,217248,217247,217243,217241,217238,217234,217232,217229,217224,217218,217216,217214,217210,217209,217208,217206,217202,217201,217199,217196,217195,217194,217193,217187,217183,217178,217176,217175,217173,217172,217171,217167,217166,217163,217162,217158,217157,217149,217146,217145,217144,217143,217140,217138,217137,217136,217134,217133,217130,217125,217124,217121,217120,217116,217112,217111,217108,217105,217103,217099,217098,217093,217090,217087,217086,217081,217078,217075,217074,217070,217069,217068,217067,217066,217063,217060,217055,217051,217050,217049,217046,217045,217042,217035,217031,217026,217025,217023,217022,217021,217020,217018,217017,217015,217014,217010,217009,217008,217007,217001,216995,216994,216992,216988,216987,216983,216982,216979,216977,216974,216971,216963,216961,216960,216957,216954,216953,216952,216950,216949,216948,216947,216935,216932,216931,216928,216922,216920,216919,216916,216914,216909,216906,216902,216901,216900,216897,216891,216890,216887,216886,216882,216881,216879,216873,216872,216871,216870,216863,216860,216857,216854,216852,216850,216847,216844,216842,216838,216836,216833,216832,216830,216827,216825,216823,216818,216816,216814,216812,216811,216804,216802,216800,216796,216790,216789,216788,216787,216782,216780,216779,216777,216776,216768,216764,216761,216760,216758,216756,216755,216753,216752,216751,216748,216746,216744,216739,216738,216737,216733,216732,216724,216722,216721,216720,216719,216715,216714,216711,216708,216707,216706,216704,216702,216700,216694,216692,216691,216689,216685,216681,216680,216676,216675,216674,216671,216667,216666,216663,216662,216661,216660,216657,216649,216645,216642,216640,216637,216636,216634,216633,216631,216630,216628,216626,216622,216621,216619,216614,216608,216607,216606,216605,216604,216603,216598,216596,216595,216594,216593,216592,216587,216583,216580,216579,216575,216571,216567,216560,216559,216558,216552,216551,216549,216548,216546,216545,216543,216541,216535,216533,216529,216527,216525,216523,216517,216514,216511,216510,216509,216508,216507,216505,216504,216502,216501,216500,216495,216494,216492,216490,216489,216488,216487,216486,216484,216475,216474,216470,216467,216455,216449,216445,216441,216440,216438,216432,216430,216429,216426,216424,216420,216417,216415,216414,216412,216411,216410,216405,216404,216401,216399,216394,216392,216391,216387,216386,216381,216378,216377,216374,216360,216357,216355,216352,216351,216347,216346,216333,216332,216331,216329,216326,216324,216320,216319,216318,216317,216315,216313,216312,216307,216305,216304,216303,216301,216297,216296,216295,216289,216288,216287,216286,216285,216282,216281,216279,216274,216273,216270,216268,216267,216265,216263,216261,216260,216256,216252,216251,216249,216248,216243,216242,216241,216232,216228,216226,216220,216214,216211,216210,216209,216202,216201,216200,216196,216195,216194,216193,216192,216190,216188,216186,216185,216176,216172,216170,216168,216165,216163,216162,216157,216153,216152,216151,216148,216145,216139,216134,216132,216131,216130,216124,216123,216120,216119,216117,216113,216109,216107,216105,216102,216101,216099,216098,216095,216094,216093,216091,216084,216077,216073,216068,216063,216062,216061,216059,216058,216057,216054,216053,216052,216050,216047,216045,216041,216040,216039,216038,216036,216035,216034,216030,216024,216023,216022,216021,216016,216009,216008,216005,216002,216001,216000,215999,215994,215991,215989,215986,215985,215981,215980,215978,215977,215975,215974,215973,215972,215971,215970,215965,215963,215961,215960,215959,215957,215956,215955,215954,215949,215948,215947,215942,215941,215934,215929,215927,215926,215925,215924,215923,215920,215919,215917,215916,215915,215914,215912,215911,215910,215909,215908,215906,215905,215904,215902,215901,215897,215896,215895,215892,215891,215890,215885,215883,215880,215877,215871,215869,215865,215861,215860,215858,215856,215855,215853,215852,215850,215845,215843,215838,215837,215830,215829,215821,215819,215818,215813,215810,215805,215797,215794,215790,215788,215785,215784,215780,215779,215778,215777,215776,215775,215774,215773,215772,215770,215769,215765,215763,215758,215756,215755,215754,215753,215752,215750,215749,215747,215744,215743,215740,215733,215731,215726,215725,215722,215721,215719,215718,215711,215709,215701,215697,215695,215691,215690,215687,215684,215682,215681,215678,215677,215674,215673,215670,215661,215658,215653,215652,215651,215650,215643,215641,215639,215635,215633,215631,215629,215626,215625,215624,215623,215621,215618,215616,215612,215610,215602,215601,215594,215590,215588,215587,215584,215580,215577,215576,215571,215569,215568,215563,215560,215559,215550,215549,215547,215542,215541,215539,215537,215529,215528,215527,215526,215524,215523,215521,215519,215518,215513,215511,215510,215508,215506,215499,215496,215492,215489,215488,215485,215482,215481,215475,215473,215469,215468,215466,215461,215460,215457,215454,215451,215446,215444,215440,215438,215437,215436,215434,215433,215431,215429,215427,215425,215423,215422,215417,215416,215411,215410,215409,215404,215402,215399,215397,215396,215395,215392,215388,215387,215384,215382,215378,215375,215374,215371,215367,215365,215364,215363,215360,215355,215354,215349,215344,215335,215328,215327,215326,215325,215324,215323,215321,215320,215317,215315,215314,215313,215312,215311,215306,215298,215297,215292,215289,215287,215285,215284,215279,215277,215274,215272,215267,215265,215261,215259,215256,215255,215244,215243,215240,215239,215236,215234,215233,215232,215229,215223,215222,215220,215218,215209,215206,215205,215203,215202,215199,215198,215197,215196,215194,215193,215192,215189,215188,215186,215182,215180,215179,215178,215177,215169,215165,215164,215162,215154,215152,215149,215143,215142,215139,215137,215136,215133,215126,215125,215120,215114,215112,215108,215105,215104,215103,215100,215098,215095,215094,215093,215092,215081,215080,215078,215072,215070,215069,215064,215061,215050,215047,215046,215044,215043,215041,215031,215030,215028,215026,215023,215022,215020,215019,215017,215014,215013,215011,215008,215007,215004,215003,215002,214995,214994,214993,214990,214988,214986,214984,214982,214981,214980,214978,214977,214975,214971,214965,214963,214962,214959,214958,214956,214955,214954,214952,214951,214949,214941,214940,214935,214932,214931,214928,214916,214915,214907,214906,214900,214897,214896,214891,214890,214887,214885,214883,214879,214872,214871,214869,214867,214860,214854,214851,214848,214846,214843,214842,214841,214839,214838,214836,214835,214833,214832,214831,214828,214824,214815,214808,214806,214803,214802,214801,214800,214799,214794,214792,214788,214787,214786,214785,214784,214783,214782,214781,214780,214779,214778,214777,214774,214773,214771,214770,214765,214763,214762,214760,214757,214756,214750,214749,214747,214746,214744,214740,214738,214736,214728,214727,214726,214721,214717,214714,214713,214711,214708,214701,214700,214698,214696,214694,214691,214690,214688,214687,214686,214684,214683,214680,214672,214669,214667,214666,214665,214662,214660,214659,214658,214655,214649,214641,214640,214633,214625,214624,214623,214620,214618,214617,214612,214610,214608,214601,214600,214597,214596,214595,214594,214593,214591,214588,214587,214586,214582,214579,214578,214575,214570,214568,214564,214563,214561,214559,214555,214552,214550,214549,214548,214544,214542,214539,214534,214529,214521,214520,214519,214512,214509,214506,214497,214495,214493,214490,214478,214476,214473,214472,214469,214462,214457,214456,214451,214449,214448,214443,214441,214439,214437,214435,214432,214431,214430,214429,214427,214424,214422,214417,214416,214411,214408,214406,214405,214404,214401,214399,214398,214397,214395,214394,214393,214388,214386,214384,214381,214380,214378,214377,214376,214374,214373,214370,214369,214368,214367,214364,214361,214360,214354,214352,214348,214347,214345,214344,214341,214340,214339,214332,214330,214326,214325,214324,214323,214315,214313,214308,214307,214301,214300,214298,214291,214283,214281,214279,214277,214275,214274,214272,214271,214269,214268,214267,214263,214262,214256,214255,214244,214241,214240,214235,214233,214231,214228,214226,214221,214219,214216,214213,214212,214210,214205,214203,214202,214194,214192,214185,214183,214179,214178,214177,214173,214164,214163,214155,214153,214152,214150,214142,214139,214126,214125,214105,214102,214101,214094,214092,214089,214088,214087,214086,214069,214065,214062,214058,214057,214054,214051,214042,214041,214040,214038,214033,214029,214023,214020,214017,214014,214013,214008,214007,214005,214003,214000,213997,213992,213983,213981,213978,213974,213968,213967,213962,213953,213946,213945,213941,213932,213930,213921,213919,213917,213913,213912,213910,213906,213903,213901,213900,213897,213896,213893,213890,213878,213876,213873,213871,213870,213867,213864,213861,213858,213851,213849,213847,213841,213837,213835,213833,213832,213826,213820,213819,213818,213817,213814,213808,213803,213802,213799,213797,213796,213795,213790,213787,213786,213780,213772,213759,213757,213753,213750,213749,213747,213744,213743,213741,213737,213733,213729,213728,213724,213721,213720,213717,213715,213712,213708,213706,213704,213702,213701,213695,213689,213686,213683,213677,213674,213673,213664,213653,213652,213651,213650,213641,213636,213635,213630,213628,213626,213625,213620,213619,213616,213613,213611,213609,213607,213605,213601,213597,213594,213591,213588,213584,213583,213581,213580,213579,213574,213573,213572,213571,213568,213565,213563,213562,213556,213549,213538,213537,213533,213532,213531,213528,213527,213526,213524,213521,213518,213516,213514,213511,213510,213508,213507,213504,213501,213499,213497,213493,213490,213483,213482,213478,213477,213469,213468,213467,213463,213460,213458,213456,213446,213442,213440,213439,213438,213437,213435,213433,213432,213426,213424,213422,213412,213411,213410,213400,213396,213391,213390,213389,213388,213384,213380,213378,213375,213372,213370,213367,213365,213363,213360,213348,213345,213343,213342,213340,213335,213330,213326,213324,213323,213322,213320,213310,213305,213303,213302,213301,213298,213296,213288,213286,213285,213284,213281,213280,213279,213278,213274,213272,213271,213266,213265,213263,213262,213257,213252,213251,213250,213249,213248,213244,213240,213237,213229,213224,213220,213210,213209,213203,213200,213194,213190,213189,213188,213180,213175,213170,213169,213164,213162,213161,213158,213156,213152,213151,213142,213141,213137,213135,213132,213131,213129,213126,213124,213118,213117,213116,213113,213111,213108,213107,213096,213089,213088,213083,213082,213077,213076,213074,213072,213071,213070,213068,213067,213065,213064,213061,213060,213059,213057,213055,213052,213050,213049,213047,213042,213041,213039,213038,213037,213033,213029,213024,213022,213020,213019,213017,213015,213014,213013,213012,213011,213007,213006,213005,212999,212996,212993,212983,212980,212970,212967,212961,212959,212955,212954,212953,212951,212950,212948,212943,212940,212939,212938,212931,212930,212927,212926,212925,212923,212921,212919,212918,212917,212915,212914,212913,212909,212908,212900,212897,212894,212893,212882,212879,212874,212873,212871,212869,212865,212864,212862,212860,212846,212839,212838,212835,212833,212827,212826,212823,212818,212814,212813,212812,212810,212809,212806,212805,212804,212803,212802,212799,212798,212790,212787,212784,212783,212778,212777,212774,212770,212769,212768,212767,212760,212759,212753,212751,212749,212745,212743,212742,212741,212740,212739,212738,212736,212735,212734,212732,212731,212728,212726,212725,212724,212721,212719,212714,212713,212709,212708,212707,212702,212701,212694,212687,212686,212684,212680,212673,212669,212665,212662,212657,212654,212652,212651,212649,212646,212638,212634,212621,212620,212614,212599,212597,212596,212595,212587,212585,212583,212580,212578,212577,212570,212567,212565,212564,212563,212562,212559,212552,212551,212547,212545,212544,212543,212538,212535,212534,212531,212530,212526,212525,212524,212520,212519,212518,212516,212515,212511,212509,212507,212504,212500,212497,212496,212494,212491,212485,212484,212483,212480,212478,212475,212473,212470,212468,212467,212462,212452,212451,212450,212447,212445,212437,212435,212434,212432,212429,212428,212422,212420,212417,212416,212407,212405,212404,212403,212399,212395,212391,212390,212388,212384,212381,212379,212376,212374,212372,212369,212367,212363,212362,212361,212360,212359,212357,212356,212353,212349,212347,212346,212345,212343,212336,212331,212326,212324,212323,212318,212313,212310,212309,212305,212298,212296,212292,212291,212287,212286,212285,212283,212281,212274,212273,212269,212265,212259,212254,212253,212248,212246,212239,212237,212236,212235,212233,212232,212231,212230,212228,212227,212224,212222,212221,212220,212219,212218,212215,212211,212209,212208,212207,212204,212202,212201,212198,212196,212187,212185,212184,212183,212182,212180,212179,212177,212176,212168,212164,212161,212154,212147,212142,212139,212138,212132,212131,212130,212125,212118,212117,212116,212115,212111,212110,212109,212108,212106,212094,212093,212092,212089,212080,212079,212078,212077,212075,212072,212066,212063,212062,212061,212060,212056,212054,212051,212050,212048,212045,212039,212035,212033,212032,212031,212029,212028,212027,212023,212019,212018,212004,212003,212001,211998,211996,211991,211990,211989,211986,211981,211977,211976,211973,211972,211968,211967,211966,211962,211961,211959,211958,211957,211956,211954,211953,211948,211946,211940,211937,211936,211935,211930,211927,211925,211917,211916,211915,211913,211911,211909,211904,211901,211898,211896,211895,211892,211884,211881,211880,211876,211871,211869,211866,211865,211864,211859,211857,211853,211850,211843,211841,211840,211835,211834,211832,211831,211826,211824,211821,211820,211817,211813,211812,211809,211808,211807,211800,211797,211793,211789,211788,211787,211781,211779,211775,211774,211773,211772,211767,211765,211762,211761,211757,211756,211753,211752,211749,211748,211746,211745,211741,211740,211739,211738,211737,211736,211734,211731,211730,211727,211725,211723,211721,211720,211719,211718,211717,211712,211711,211705,211703,211697,211692,211686,211683,211681,211677,211673,211672,211671,211669,211667,211663,211659,211658,211655,211654,211652,211651,211647,211643,211642,211641,211638,211637,211636,211634,211633,211630,211625,211610,211604,211601,211595,211589,211585,211582,211580,211576,211573,211572,211562,211561,211558,211557,211553,211548,211539,211537,211524,211522,211520,211519,211518,211514,211512,211504,211502,211490,211489,211488,211485,211484,211483,211479,211474,211470,211469,211468,211465,211456,211455,211453,211451,211450,211449,211448,211447,211446,211442,211441,211440,211439,211430,211427,211426,211424,211422,211421,211419,211418,211413,211411,211410,211404,211403,211402,211398,211394,211393,211391,211386,211378,211377,211373,211371,211367,211366,211365,211364,211363,211359,211358,211354,211352,211347,211343,211342,211338,211336,211334,211330,211329,211328,211323,211317,211310,211301,211297,211289,211283,211282,211281,211277,211276,211271,211270,211267,211266,211265,211261,211259,211257,211254,211253,211248,211247,211241,211236,211235,211234,211231,211226,211225,211217,211214,211213,211212,211211,211207,211203,211191,211186,211184,211181,211180,211176,211174,211173,211172,211171,211170,211168,211166,211162,211160,211159,211157,211155,211154,211152,211144,211143,211142,211139,211138,211133,211129,211124,211121,211120,211119,211118,211117,211115,211114,211113,211099,211098,211097,211092,211089,211087,211085,211075,211074,211072,211070,211069,211067,211063,211052,211049,211046,211041,211040,211038,211036,211035,211034,211032,211030,211029,211027,211024,211023,211021,211018,211016,211015,211014,211013,211012,211011,211009,211008,211002,210996,210992,210987,210986,210983,210982,210978,210976,210973,210972,210971,210970,210963,210962,210961,210960,210959,210957,210956,210952,210948,210947,210946,210945,210943,210940,210930,210928,210921,210919,210914,210906,210900,210899,210894,210890,210889,210888,210887,210885,210882,210881,210880,210876,210875,210872,210871,210869,210868,210866,210865,210860,210853,210850,210849,210846,210843,210841,210838,210837,210835,210833,210827,210822,210817,210813,210810,210807,210806,210805,210804,210803,210800,210799,210798,210794,210791,210790,210787,210786,210785,210784,210779,210777,210772,210771,210768,210767,210766,210765,210763,210755,210749,210745,210744,210739,210733,210727,210724,210723,210719,210717,210714,210713,210706,210702,210699,210698,210697,210696,210695,210692,210688,210685,210684,210683,210681,210679,210676,210673,210672,210671,210670,210669,210664,210659,210658,210654,210649,210644,210643,210639,210635,210632,210629,210627,210626,210625,210624,210623,210619,210618,210614,210612,210611,210606,210604,210600,210597,210595,210593,210591,210589,210582,210578,210576,210575,210570,210565,210564,210563,210562,210560,210556,210551,210550,210549,210548,210545,210543,210542,210539,210538,210533,210531,210529,210527,210524,210521,210520,210519,210518,210515,210513,210508,210502,210495,210493,210492,210490,210487,210486,210482,210481,210472,210469,210466,210465,210459,210457,210454,210453,210452,210448,210447,210446,210441,210440,210427,210424,210422,210417,210415,210413,210405,210402,210399,210398,210397,210391,210390,210388,210387,210384,210383,210376,210364,210343,210342,210341,210340,210338,210337,210336,210335,210333,210332,210330,210329,210327,210324,210320,210319,210317,210316,210310,210309,210305,210297,210296,210294,210292,210289,210280,210277,210276,210274,210270,210268,210267,210266,210263,210262,210258,210256,210253,210252,210251,210248,210247,210244,210240,210236,210234,210233,210232,210229,210226,210225,210224,210222,210220,210218,210217,210214,210211,210210,210208,210207,210206,210205,210197,210191,210184,210182,210178,210173,210172,210169,210166,210157,210155,210153,210151,210150,210148,210146,210144,210143,210140,210139,210134,210133,210132,210130,210129,210128,210127,210125,210121,210120,210118,210117,210116,210115,210114,210113,210111,210108,210107,210105,210104,210103,210102,210098,210095,210092,210090,210078,210070,210068,210066,210064,210063,210058,210055,210053,210047,210043,210042,210041,210040,210039,210038,210037,210036,210035,210031,210029,210028,210026,210024,210023,210020,210019,210017,210015,210008,210005,209999,209995,209993,209989,209988,209986,209985,209983,209982,209979,209976,209974,209973,209972,209971,209958,209953,209951,209950,209940,209939,209936,209934,209933,209924,209923,209920,209919,209917,209915,209912,209911,209910,209907,209899,209897,209896,209895,209894,209892,209886,209878,209876,209875,209874,209873,209867,209864,209860,209856,209853,209847,209840,209836,209830,209828,209825,209813,209810,209807,209805,209804,209803,209801,209800,209799,209797,209792,209790,209788,209787,209783,209780,209779,209770,209767,209766,209765,209763,209762,209760,209759,209758,209756,209753,209752,209749,209748,209746,209744,209742,209736,209735,209731,209728,209727,209726,209724,209715,209706,209703,209702,209701,209699,209695,209692,209691,209690,209689,209688,209687,209686,209685,209677,209673,209670,209669,209667,209665,209661,209659,209658,209655,209654,209652,209650,209648,209647,209645,209644,209643,209642,209639,209636,209623,209621,209620,209617,209616,209615,209614,209605,209598,209594,209591,209590,209588,209586,209585,209583,209580,209574,209570,209569,209565,209564,209559,209558,209557,209556,209552,209549,209548,209542,209540,209537,209533,209532,209526,209524,209523,209522,209521,209518,209516,209514,209513,209511,209510,209508,209506,209504,209503,209502,209500,209492,209489,209482,209481,209480,209476,209468,209467,209464,209463,209462,209461,209458,209456,209455,209454,209453,209450,209447,209443,209439,209438,209436,209435,209430,209429,209428,209427,209425,209419,209418,209414,209413,209411,209409,209406,209405,209403,209402,209398,209396,209393,209387,209385,209380,209379,209378,209377,209376,209375,209372,209370,209368,209367,209366,209361,209360,209358,209357,209354,209352,209346,209342,209340,209335,209331,209323,209322,209312,209309,209301,209298,209297,209296,209292,209288,209287,209286,209285,209284,209283,209281,209275,209266,209263,209261,209260,209258,209256,209244,209240,209239,209237,209235,209234,209233,209232,209231,209230,209228,209222,209219,209218,209217,209215,209213,209212,209210,209209,209207,209206,209205,209203,209200,209196,209191,209190,209188,209187,209186,209185,209184,209182,209181,209177,209170,209169,209168,209165,209164,209163,209162,209158,209157,209156,209155,209154,209151,209149,209145,209144,209143,209142,209141,209137,209136,209134,209131,209130,209128,209126,209124,209122,209121,209119,209118,209117,209116,209112,209111,209109,209108,209107,209106,209104,209099,209093,209092,209082,209080,209077,209076,209074,209073,209070,209068,209066,209065,209061,209056,209055,209054,209051,209050,209046,209043,209038,209036,209034,209030,209028,209019,209007,209006,209002,209001,208996,208987,208986,208973,208972,208971,208969,208966,208965,208964,208963,208957,208937,208936,208935,208932,208931,208926,208921,208919,208913,208911,208910,208908,208907,208906,208905,208904,208902,208898,208896,208894,208889,208888,208887,208886,208884,208882,208877,208876,208874,208871,208866,208861,208860,208858,208857,208856,208854,208853,208849,208836,208832,208830,208827,208826,208824,208822,208818,208811,208807,208804,208803,208797,208794,208787,208786,208785,208782,208778,208775,208768,208767,208764,208761,208755,208750,208748,208745,208744,208738,208737,208734,208733,208732,208731,208730,208728,208726,208725,208724,208722,208721,208718,208715,208713,208712,208707,208706,208705,208700,208696,208694,208693,208692,208690,208689,208688,208687,208685,208684,208683,208682,208681,208675,208669,208667,208664,208661,208660,208659,208656,208652,208651,208650,208647,208640,208636,208635,208633,208626,208623,208622,208621,208620,208617,208616,208613,208611,208609,208605,208604,208601,208595,208594,208593,208592,208591,208590,208584,208583,208582,208581,208578,208571,208567,208564,208562,208560,208559,208558,208556,208555,208544,208543,208539,208529,208527,208526,208522,208512,208504,208500,208499,208498,208496,208493,208490,208489,208487,208486,208485,208484,208480,208478,208477,208470,208469,208465,208461,208458,208454,208452,208449,208448,208444,208443,208441,208437,208436,208432,208426,208411,208404,208399,208398,208397,208395,208393,208389,208388,208384,208383,208380,208371,208370,208369,208366,208364,208362,208347,208346,208334,208332,208328,208327,208321,208315,208314,208313,208312,208303,208302,208298,208296,208291,208290,208289,208288,208286,208285,208284,208283,208282,208280,208273,208271,208270,208269,208266,208264,208263,208262,208260,208259,208255,208252,208249,208247,208246,208244,208243,208242,208239,208235,208231,208230,208229,208228,208225,208222,208221,208219,208216,208210,208202,208199,208198,208196,208195,208193,208178,208175,208172,208170,208166,208165,208164,208163,208159,208158,208157,208154,208150,208146,208143,208142,208141,208140,208139,208135,208133,208132,208128,208126,208124,208123,208122,208121,208119,208114,208113,208105,208104,208103,208102,208101,208098,208095,208092,208091,208090,208087,208085,208081,208080,208074,208073,208072,208070,208069,208067,208066,208065,208063,208062,208060,208057,208053,208051,208050,208047,208046,208045,208044,208042,208040,208038,208037,208026,208023,208020,208019,208018,208007,208006,208004,208003,208002,208001,208000,207997,207995,207992,207990,207988,207984,207982,207979,207978,207977,207973,207972,207971,207970,207966,207965,207964,207962,207959,207956,207955,207954,207948,207947,207945,207940,207935,207933,207932,207931,207928,207926,207924,207921,207918,207917,207916,207915,207914,207910,207909,207903,207902,207901,207900,207899,207895,207894,207892,207887,207886,207885,207881,207880,207878,207875,207874,207873,207872,207871,207869,207867,207866,207863,207860,207859,207857,207856,207854,207853,207852,207849,207847,207846,207845,207843,207841,207837,207836,207835,207833,207832,207831,207830,207827,207826,207823,207820,207818,207817,207814,207812,207811,207810,207809,207805,207802,207801,207800,207798,207797,207796,207794,207793,207792,207791,207785,207782,207781,207778,207777,207774,207773,207772,207766,207759,207758,207757,207753,207751,207747,207743,207742,207741,207737,207733,207732,207731,207708,207707,207706,207705,207703,207702,207699,207696,207692,207691,207690,207685,207684,207683,207679,207678,207677,207676,207675,207672,207670,207668,207667,207666,207665,207661,207660,207658,207656,207655,207654,207652,207651,207648,207645,207639,207630,207627,207617,207612,207608,207606,207605,207603,207602,207597,207592,207591,207588,207584,207583,207582,207577,207573,207572,207570,207568,207567,207560,207554,207553,207552,207551,207546,207544,207542,207541,207537,207536,207534,207533,207531,207530,207526,207520,207519,207515,207512,207511,207510,207509,207507,207505,207504,207501,207499,207497,207493,207492,207488,207486,207485,207482,207479,207477,207475,207471,207470,207464,207461,207460,207459,207458,207454,207451,207449,207447,207445,207444,207443,207442,207440,207439,207438,207434,207431,207430,207428,207427,207426,207425,207424,207421,207416,207414,207413,207412,207409,207407,207403,207401,207399,207396,207395,207393,207391,207390,207388,207387,207386,207379,207378,207377,207376,207374,207372,207366,207364,207362,207355,207353,207346,207342,207335,207333,207331,207328,207327,207326,207321,207313,207310,207309,207308,207305,207303,207300,207298,207297,207296,207294,207292,207289,207288,207287,207285,207281,207278,207276,207275,207274,207273,207272,207269,207268,207261,207257,207255,207253,207251,207248,207243,207237,207235,207231,207229,207216,207213,207211,207210,207208,207207,207205,207204,207201,207200,207199,207198,207197,207196,207194,207192,207190,207189,207188,207187,207186,207183,207181,207176,207175,207167,207166,207164,207162,207159,207158,207156,207155,207153,207152,207149,207148,207147,207142,207137,207134,207131,207129,207124,207123,207122,207119,207118,207115,207114,207109,207107,207103,207100,207098,207096,207095,207092,207089,207085,207084,207072,207069,207068,207066,207063,207061,207060,207059,207056,207055,207054,207053,207051,207047,207046,207042,207041,207040,207039,207038,207037,207033,207030,207029,207028,207024,207022,207018,207015,207014,207011,207008,207004,207002,207001,207000,206999,206998,206993,206990,206984,206981,206979,206978,206977,206975,206974,206972,206971,206968,206967,206966,206956,206953,206952,206951,206950,206949,206948,206943,206941,206940,206939,206932,206931,206929,206923,206919,206918,206916,206915,206914,206913,206912,206910,206909,206907,206906,206905,206904,206903,206901,206900,206894,206893,206892,206891,206890,206886,206878,206877,206875,206873,206870,206864,206860,206859,206858,206855,206853,206852,206850,206849,206848,206847,206839,206837,206836,206833,206830,206829,206827,206820,206818,206817,206814,206812,206811,206806,206805,206803,206800,206796,206790,206786,206781,206780,206778,206777,206776,206775,206774,206773,206768,206765,206762,206760,206757,206755,206754,206750,206746,206744,206742,206737,206736,206735,206734,206726,206722,206718,206716,206714,206713,206712,206708,206707,206704,206699,206694,206692,206691,206689,206688,206687,206677,206675,206673,206672,206671,206670,206664,206659,206658,206650,206647,206640,206633,206629,206628,206626,206625,206624,206610,206609,206604,206601,206597,206594,206591,206590,206589,206588,206587,206586,206585,206584,206582,206580,206574,206571,206570,206568,206566,206565,206561,206558,206553,206549,206542,206539,206538,206536,206531,206527,206523,206518,206515,206512,206509,206507,206506,206505,206502,206501,206500,206498,206496,206495,206493,206491,206490,206489,206484,206482,206480,206479,206477,206475,206470,206466,206465,206462,206461,206460,206457,206455,206453,206449,206448,206444,206443,206427,206426,206421,206419,206418,206417,206411,206410,206409,206408,206399,206398,206395,206392,206391,206388,206383,206381,206379,206374,206366,206359,206358,206353,206347,206346,206345,206341,206340,206338,206334,206332,206330,206329,206325,206324,206323,206322,206319,206318,206317,206314,206312,206306,206305,206304,206299,206298,206297,206296,206294,206292,206291,206289,206282,206281,206280,206268,206267,206257,206250,206248,206246,206244,206243,206241,206234,206230,206224,206223,206222,206219,206216,206211,206210,206207,206205,206203,206201,206200,206199,206197,206194,206191,206190,206188,206185,206181,206180,206176,206174,206167,206159,206157,206153,206149,206147,206140,206139,206138,206135,206134,206133,206129,206128,206127,206125,206119,206118,206116,206110,206107,206105,206103,206099,206092,206091,206090,206089,206086,206084,206082,206078,206074,206072,206071,206069,206067,206066,206062,206061,206053,206052,206051,206047,206045,206044,206040,206037,206036,206027,206026,206025,206023,206022,206020,206019,206017,206015,206012,206009,206004,205997,205995,205994,205993,205989,205988,205986,205985,205984,205982,205980,205973,205971,205970,205966,205965,205960,205959,205958,205954,205953,205949,205945,205941,205940,205938,205937,205935,205923,205922,205919,205914,205913,205911,205910,205909,205907,205905,205904,205903,205901,205900,205897,205896,205895,205893,205892,205888,205885,205879,205877,205872,205868,205866,205865,205864,205862,205861,205860,205858,205857,205856,205855,205854,205853,205852,205851,205849,205845,205843,205842,205841,205840,205838,205837,205836,205834,205832,205831,205830,205828,205827,205826,205824,205823,205819,205817,205816,205815,205814,205810,205803,205800,205796,205795,205793,205791,205782,205774,205773,205771,205760,205756,205754,205753,205752,205751,205750,205747,205745,205744,205743,205741,205736,205735,205730,205728,205727,205726,205724,205717,205714,205713,205712,205711,205710,205709,205703,205699,205698,205697,205692,205690,205689,205688,205686,205685,205683,205682,205680,205679,205678,205674,205673,205672,205671,205669,205668,205667,205666,205665,205662,205660,205657,205656,205652,205651,205648,205645,205643,205642,205639,205638,205630,205627,205626,205623,205622,205619,205618,205613,205611,205610,205607,205605,205604,205602,205601,205600,205599,205597,205595,205588,205586,205581,205579,205578,205573,205572,205567,205565,205564,205563,205562,205559,205558,205556,205555,205553,205551,205549,205548,205545,205544,205543,205541,205539,205538,205536,205535,205532,205526,205524,205522,205521,205520,205518,205515,205514,205513,205511,205510,205509,205508,205506,205505,205503,205502,205501,205498,205497,205495,205494,205493,205492,205490,205487,205485,205484,205481,205480,205479,205478,205477,205476,205472,205470,205469,205466,205465,205460,205459,205458,205457,205456,205452,205448,205440,205436,205435,205434,205432,205431,205430,205429,205428,205427,205423,205422,205418,205417,205413,205411,205410,205409,205408,205405,205403,205399,205398,205393,205392,205390,205386,205385,205384,205383,205380,205374,205373,205371,205370,205369,205368,205364,205363,205362,205361,205360,205359,205356,205348,205345,205344,205342,205341,205340,205336,205333,205331,205329,205326,205325,205324,205323,205322,205319,205317,205314,205313,205312,205275,205247,205219,205171,205091,205031,205030,204830,204764,204702,204625,204616,204609,204607,204599,204572,204364,204347,204303,204299,204296,204294,204293,204292,204291,204290,204288,204287,204286,204280,204279,204276,204275,204269,204267,204265,204262,204260,204259,204255,204252,204251,204250,204247,204246,204241,204239,204238,204237,204236,204235,204232,204231,204230,204226,204225,204224,204223,204222,204217,204213,204212,204209,204208,204206,204204,204203,204202,204201,204194,204191,204190,204187,204184,204182,204179,204174,204171,204170,204169,204165,204164,204162,204161,204158,204153,204152,204151,204150,204149,204148,204144,204142,204140,204139,204137,204134,204132,204125,204121,204116,204115,204113,204110,204109,204105,204102,204101,204099,204097,204095,204093,204090,204089,204084,204082,204080,204079,204078,204077,204076,204072,204067,204066,204063,204062,204061,204060,204059,204058,204057,204054,204051,204049,204048,204047,204045,204044,204042,204039,204038,204037,204034,204033,204030,204029,204028,204025,204023,204022,204021,204020,204018,204016,204013,204012,204010,204009,204002,203998,203993,203992,203988,203987,203986,203983,203974,203969,203968,203966,203964,203961,203959,203957,203955,203953,203952,203947,203946,203942,203940,203938,203937,203936,203935,203932,203929,203928,203926,203924,203923,203922,203920,203916,203915,203913,203910,203909,203908,203907,203906,203905,203900,203898,203897,203896,203894,203893,203890,203889,203888,203885,203884,203883,203881,203879,203876,203874,203872,203870,203868,203867,203864,203861,203860,203859,203857,203853,203852,203842,203839,203838,203837,203836,203834,203833,203831,203829,203825,203823,203817,203814,203811,203809,203807,203806,203805,203802,203801,203799,203798,203794,203793,203792,203787,203786,203784,203782,203780,203770,203762,203759,203756,203753,203752,203751,203750,203747,203745,203737,203735,203734,203733,203732,203731,203729,203716,203713,203712,203711,203710,203709,203704,203701,203698,203689,203683,203682,203676,203674,203673,203672,203671,203670,203668,203659,203657,203656,203654,203651,203650,203649,203646,203645,203641,203639,203636,203635,203634,203633,203626,203621,203620,203612,203610,203608,203606,203605,203603,203598,203596,203592,203585,203583,203582,203579,203577,203569,203556,203551,203549,203546,203545,203540,203537,203535,203534,203531,203530,203523,203522,203517,203510,203507,203501,203498,203493,203491,203490,203488,203487,203486,203483,203481,203474,203473,203469,203468,203466,203465,203464,203461,203459,203458,203456,203453,203449,203445,203443,203442,203441,203440,203435,203433,203432,203429,203428,203423,203422,203421,203420,203419,203418,203417,203416,203413,203412,203409,203405,203404,203403,203396,203394,203389,203388,203387,203379,203375,203374,203372,203369,203368,203364,203360,203358,203349,203347,203344,203343,203342,203338,203333,203331,203325,203319,203318,203316,203313,203310,203309,203308,203307,203305,203304,203303,203298,203296,203295,203293,203292,203291,203290,203288,203287,203281,203278,203273,203271,203267,203264,203262,203261,203260,203259,203254,203252,203250,203246,203244,203243,203242,203239,203238,203237,203235,203233,203231,203224,203218,203213,203203,203200,203192,203189,203182,203180,203179,203177,203176,203174,203168,203167,203166,203163,203159,203158,203153,203151,203150,203149,203148,203147,203146,203145,203144,203143,203141,203139,203137,203136,203135,203133,203132,203131,203130,203129,203128,203125,203122,203121,203113,203112,203106,203102,203101,203099,203094,203089,203086,203085,203083,203082,203079,203078,203075,203074,203073,203072,203071,203068,203067,203056,203046,203045,203044,203040,203038,203037,203036,203031,203030,203027,203026,203024,203023,203021,203018,203017,203016,203012,203011,203010,203008,203007,203005,203003,203000,202991,202990,202989,202986,202984,202983,202982,202979,202977,202975,202974,202973,202972,202971,202964,202963,202962,202961,202960,202957,202955,202954,202953,202952,202951,202950,202949,202948,202947,202946,202945,202943,202940,202939,202935,202934,202933,202930,202929,202928,202927,202926,202923,202921,202919,202918,202916,202913,202911,202910,202909,202905,202904,202901,202899,202898,202896,202894,202891,202890,202889,202888,202886,202884,202883,202882,202880,202876,202872,202870,202869,202866,202862,202858,202856,202855,202852,202851,202849,202847,202845,202838,202837,202834,202833,202831,202830,202827,202818,202809,202807,202800,202799,202797,202794,202790,202789,202788,202787,202786,202784,202783,202782,202781,202780,202779,202777,202776,202775,202772,202771,202768,202765,202764,202762,202760,202759,202755,202752,202750,202746,202744,202739,202734,202733,202732,202731,202730,202729,202726,202725,202723,202719,202718,202714,202713,202706,202705,202703,202698,202697,202695,202692,202688,202686,202685,202684,202683,202681,202678,202673,202669,202668,202665,202664,202663,202660,202659,202656,202652,202649,202645,202641,202639,202636,202635,202632,202630,202625,202622,202619,202618,202617,202611,202609,202601,202600,202599,202598,202597,202596,202595,202592,202586,202584,202583,202582,202581,202573,202572,202571,202570,202569,202568,202567,202566,202565,202560,202559,202556,202548,202541,202539,202537,202529,202527,229340,229337,229333,229332,229331,229329,229327,229326,229325,229323,229321,229320,229318,229313,229305,229301,229299,229298,229296,229293,229292,229291,229289,229287,229286,229284,229279,229278,229273,229270,229268,229267,229262,229260,229254,229252,229247,229235,229225,229224,229221,229220,229219,229218,229216,229214,229210,229209,229204,229200,229196,229186,229185,229184,229182,229181,229176,229175,229173,229172,229170,229169,229168,229167,229163,229161,229158,229154,229152,229151,229146,229145,229144,229136,229135,229134,229133,229127,229125,229123,229106,229104,229101,229100,229094,229093,229092,229089,229084,229083,229082,229073,229072,229071,229070,229067,229066,229061,229056,229055,229049,229048,229045,229027,229026,229022,229020,229019,229013,229012,229011,229010,229009,229008,229007,229005,229002,229001,228999,228995,228994,228985,228982,228978,228973,228967,228965,228962,228961,228958,228954,228944,228942,228940,228937,228933,228932,228930,228927,228926,228924,228923,228921,228915,228914,228913,228910,228908,228898,228891,228888,228886,228881,228880,228879,228876,228875,228872,228871,228868,228866,228864,228863,228861,228860,228853,228849,228848,228847,228842,228838,228836,228835,228834,228826,228823,228821,228814,228808,228804,228799,228795,228790,228789,228788,228782,228779,228778,228777,228776,228774,228771,228766,228765,228761,228758,228755,228753,228752,228751,228748,228745,228744,228743,228739,228738,228736,228735,228734,228732,228730,228728,228725,228720,228718,228717,228710,228709,228706,228704,228702,228701,228699,228698,228695,228693,228690,228680,228678,228662,228659,228651,228650,228648,228647,228645,228643,228642,228641,228640,228639,228632,228630,228628,228627,228625,228624,228622,228620,228619,228616,228614,228613,228608,228607,228603,228598,228596,228595,228594,228593,228590,228588,228587,228586,228585,228584,228582,228580,228578,228574,228571,228570,228567,228566,228558,228556,228550,228549,228548,228538,228537,228536,228533,228521,228520,228517,228515,228508,228501,228500,228493,228488,228477,228467,228453,228452,228446,228445,228444,228443,228441,228437,228436,228431,228430,228429,228423,228422,228420,228419,228418,228415,228412,228411,228409,228404,228397,228396,228395,228394,228392,228388,228385,228384,228382,228381,228378,228376,228375,228373,228371,228369,228368,228366,228365,228363,228359,228358,228353,228343,228340,228338,228333,228328,228327,228321,228320,228319,228316,228312,228310,228302,228301,228300,228296,228290,228288,228281,228269,228267,228265,228262,228259,228257,228255,228252,228250,228249,228248,228246,228245,228243,228242,228239,228236,228235,228234,228232,228231,228229,228228,228227,228226,228222,228221,228215,228214,228213,228212,228211,228210,228209,228206,228205,228201,228198,228197,228193,228192,228191,228187,228185,228182,228177,228176,228169,228168,228167,228159,228148,228144,228143,228141,228139,228138,228136,228132,228131,228125,228118,228117,228112,228110,228109,228107,228106,228105,228104,228103,228102,228100,228097,228096,228095,228092,228091,228089,228088,228083,228080,228078,228073,228071,228069,228067,228066,228062,228061,228060,228059,228056,228054,228053,228050,228047,228042,228041,228039,228037,228036,228035,228030,228025,228023,228022,228019,228014,228013,228012,228007,228005,228002,227996,227994,227993,227992,227991,227989,227987,227983,227979,227977,227973,227972,227965,227962,227961,227956,227950,227947,227946,227939,227938,227936,227935,227934,227931,227930,227928,227925,227919,227916,227908,227907,227906,227900,227894,227892,227886,227884,227882,227880,227878,227877,227873,227870,227869,227868,227866,227865,227860,227857,227854,227852,227851,227849,227844,227842,227839,227838,227833,227831,227829,227825,227821,227819,227813,227811,227810,227805,227802,227799,227798,227796,227795,227790,227787,227785,227783,227782,227773,227768,227765,227764,227749,227747,227744,227742,227739,227738,227726,227716,227714,227713,227710,227709,227705,227704,227703,227702,227698,227695,227692,227689,227688,227687,227686,227683,227682,227681,227680,227679,227676,227671,227670,227667,227666,227665,227661,227659,227649,227647,227646,227637,227635,227632,227631,227630,227629,227627,227623,227621,227618,227617,227615,227612,227611,227606,227604,227602,227600,227599,227598,227595,227593,227592,227590,227586,227585,227584,227582,227581,227580,227578,227577,227575,227573,227572,227569,227568,227567,227563,227552,227550,227544,227542,227541,227540,227538,227535,227524,227519,227518,227514,227513,227512,227509,227508,227501,227499,227497,227494,227492,227486,227482,227478,227474,227471,227468,227467,227462,227461,227456,227452,227448,227443,227439,227438,227437,227434,227431,227429,227424,227418,227415,227413,227412,227410,227407,227404,227402,227401,227400,227399,227396,227394,227392,227387,227386,227383,227382,227380,227378,227377,227376,227374,227373,227372,227365,227364,227362,227359,227357,227354,227352,227346,227344,227338,227337,227336,227332,227330,227329,227326,227323,227322,227318,227317,227316,227299,227298,227296,227291,227283,227281,227280,227279,227278,227277,227275,227273,227272,227271,227269,227268,227267,227266,227262,227258,227257,227252,227249,227248,227247,227246,227244,227239,227234,227229,227228,227225,227224,227221,227217,227211,227209,227206,227205,227199,227196,227195,227189,227187,227185,227181,227180,227178,227174,227171,227165,227163,227160,227158,227157,227156,227155,227154,227152,227151,227148,227147,227146,227143,227142,227141,227140,227139,227135,227134,227133,227132,227131,227128,227125,227122,227114,227112,227106,227104,227103,227102,227101,227096,227095,227094,227093,227090,227089,227088,227087,227085,227080,227076,227075,227074,227073,227072,227067,227065,227061,227060,227059,227057,227056,227051,227050,227044,227042,227033,227032,227026,227023,227022,227017,227016,227015,227014,227012,227007,227006,227005,227004,227002,226991,226990,226989,226988,226987,226986,226985,226984,226983,226981,226978,226977,226970,226967,226966,226961,226958,226950,226948,226946,226945,226942,226940,226939,226938,226937,226929,226924,226923,226921,226920,226919,226917,226916,226910,226906,226903,226901,226900,226897,226894,226885,226882,226881,226879,226875,226874,226872,226865,226855,226851,226849,226846,226844,226841,226837,226836,226829,226828,226827,226825,226824,226820,226818,226812,226808,226806,226804,226802,226799,226797,226789,226787,226785,226780,226779,226774,226773,226768,226766,226762,226759,226756,226753,226751,226749,226748,226743,226742,226741,226740,226739,226735,226729,226728,226726,226724,226720,226716,226715,226712,226710,226708,226704,226702,226700,226699,226697,226695,226693,226690,226689,226688,226679,226678,226677,226676,226675,226671,226670,226669,226665,226662,226661,226659,226654,226650,226648,226647,226644,226643,226638,226634,226633,226631,226630,226626,226620,226616,226614,226609,226604,226603,226598,226597,226596,226591,226588,226585,226577,226573,226572,226570,226567,226563,226560,226558,226556,226555,226554,226553,226549,226544,226533,226529,226527,226525,226522,226519,226517,226516,226515,226513,226511,226510,226508,226502,226500,226498,226497,226494,226492,226490,226484,226480,226478,226477,226472,226468,226467,226464,226462,226458,226454,226453,226451,226443,226442,226441,226438,226437,226429,226427,226425,226424,226421,226418,226417,226415,226414,226406,226404,226399,226398,226392,226391,226387,226384,226381,226376,226375,226374,226373,226370,226369,226360,226358,226354,226346,226344,226343,226341,226332,226331,226329,226328,226326,226324,226320,226319,226318,226313,226307,226305,226302,226298,226297,226294,226292,226291,226286,226281,226279,226276,226274,226270,226263,226256,226255,226248,226247,226246,226242,226234,226232,226231,226229,226225,226219,226218,226217,226210,226209,226208,226206,226204,226198,226196,226194,226190,226186,226183,226182,226175,226173,226172,226169,226167,226162,226161,226160,226159,226157,226156,226152,226150,226148,226146,226145,226139,226138,226136,226134,226132,226124,226120,226116,226115,226114,226112,226109,226108,226100,226099,226097,226092,226091,226090,226088,226086,226081,226079,226063,226062,226061,226058,226057,226055,226054,226052,226050,226049,226048,226046,226045,226042,226038,226036,226035,226021,226013,226007,226004,226000,225999,225990,225989,225980,225979,225978,225976,225972,225965,225962,225961,225957,225956,225954,225949,225948,225944,225942,225941,225940,225939,225935,225934,225930,225924,225923,225922,225915,225914,225913,225912,225909,225907,225905,225904,225903,225902,225900,225896,225886,225885,225884,225878,225876,225874,225872,225870,225869,225866,225864,225862,225861,225860,225858,225857,225855,225854,225852,225850,225849,225846,225837,225834,225826,225822,225820,225816,225813,225812,225811,225809,225802,225801,225798,225795,225791,225790,225788,225786,225778,225773,225772,225769,225763,225760,225759,225756,225755,225751,225746,225740,225739,225734,225733,225727,225722,225721,225718,225717,225716,225712,225704,225703,225699,225697,225685,225682,225678,225672,225671,225668,225659,225658,225657,225654,225651,225645,225643,225638,225634,225633,225631,225629,225628,225627,225626,225624,225622,225620,225617,225616,225613,225606,225605,225603,225602,225601,225600,225599,225598,225596,225595,225586,225585,225584,225581,225580,225579,225578,225575,225574,225566,225563,225560,225559,225558,225557,225556,225553,225546,225540,225535,225533,225530,225526,225509,225508,225507,225506,225505,225501,225497,225495,225480,225477,225475,225466,225464,225462,225459,225444,225440,225436,225432,225427,225423,225420,225418,225414,225411,225410,225409,225407,225406,225405,225404,225400,225396,225395,225394,225391,225389,225379,225377,225376,225374,225368,225364,225356,225349,225345,225344,225343,225339,225335,225334,225318,225316,225315,225313,225308,225306,225304,225302,225294,225282,225279,225278,225264,225263,225261,225239,225216,225210,225208,225201,225200,225199,225190,225183,225180,225179,225174,225172,225170,225169,225168,225167,225161,225159,225155,225148,225136,225134,225126,225125,225114,225113,225112,225111,225110,225108,225101,225098,225095,225089,225087,225086,225083,225081,225077,225073,225070,225064,225062,225057,225056,225055,225052,225048,225044,225043,225041,225039,225038,225037,225034,225033,225032,225030,225029,225024,225019,225018,225017,225015,225014,225012,225011,225007,225006,224998,224997,224993,224990,224987,224985,224983,224978,224977,224975,224973,224972,224969,224964,224963,224962,224959,224955,224951,224950,224939,224938,224937,224935,224934,224933,224927,224925,224924,224921,224920,224919,224915,224914,224910,224908,224907,224906,224904,224895,224893,224892,224889,224888,224884,224882,224881,224879,224877,224876,224874,224872,224871,224866,224862,224859,224858,224857,224853,224852,224847,224844,224842,224841,224840,224838,224837,224833,224831,224829,224828,224827,224826,224819,224818,224816,224814,224811,224808,224806,224804,224803,224801,224800,224799,224794,224793,224792,224789,224782,224779,224774,224772,224762,224760,224758,224757,224756,224754,224749,224748,224747,224744,224741,224739,224735,224732,224731,224730,224729,224726,224725,224722,224721,224720,224718,224717,224716,224711,224710,224700,224699,224698,224692,224688,224685,224681,224679,224675,224673,224672,224671,224668,224667,224663,224660,224659,224656,224654,224651,224650,224648,224645,224644,224643,224642,224641,224639,224638,224637,224636,224635,224631,224630,224629,224627,224626,224624,224623,224618,224616,224615,224611,224610,224609,224601,224597,224592,224590,224585,224581,224580,224576,224575,224574,224573,224569,224565,224554,224553,224552,224551,224549,224546,224542,224539,224536,224531,224527,224525,224524,224522,224520,224519,224515,224509,224508,224504,224502,224501,224498,224496,224495,224490,224489,224487,224485,224484,224482,224481,224479,224476,224469,224468,224462,224461,224456,224452,224450,224449,224446,224438,224437,224429,224426,224424,224417,224411,224410,224405,224404,224402,224400,224392,224391,224389,224386,224385,224383,224382,224381,224379,224377,224376,224374,224373,224371,224370,224362,224361,224358,224355,224351,224347,224343,224342,224339,224338,224332,224331,224329,224328,224326,224321,224319,224318,224317,224316,224312,224309,224301,224300,224297,224292,224291,224286,224284,224279,224275,224274,224273,224272,224270,224269,224268,224265,224259,224258,224255,224246,224244,224243,224238,224237,224234,224232,224227,224225,224224,224223,224220,224219,224218,224217,224215,224212,224207,224205,224203,224201,224200,224198,224196,224194,224188,224187,224186,224185,224179,224178,224176,224167,224166,224165,224164,224162,224157,224155,224153,224152,224149,224147,224143,224138,224137,224136,224134,224133,224130,224129,224128,224127,224126,224125,224124,224123,224121,224118,224117,224116,224115,224111,224107,224104,224102,224098,224097,224095,224093,224092,224089,224087,224084,224083,224081,224080,224079,224078,224075,224072,224070,224069,224067,224065,224063,224061,224060,224056,224055,224053,224048,224044,224043,224042,224040,224038,224037,224036,224031,224028,224023,224019,224018,224015,224012,224011,224009,224008,224006,224003,223999,223998,223997,223996,223995,223990,223987,223986,223985,223984,223983,223977,223975,223974,223973,223971,223968,223966,223965,223961,223949,223944,223941,223940,223939,223937,223935,223932,223930,223929,223927,223924,223923,223922,223921,223920,223919,223918,223916,223915,223913,223909,223907,223905,223904,223901,223897,223892,223891,223889,223887,223885,223883,223882,223878,223877,223876,223873,223872,223871,223870,223868,223867,223866,223864,223860,223857,223856,223855,223853,223848,223846,223845,223844,223840,223837,223834,223830,223828,223826,223825,223824,223819,223811,223810,223806,223802,223801,223799,223798,223796,223793,223791,223788,223784,223782,223780,223777,223776,223774,223773,223771,223769,223767,223765,223756,223753,223751,223749,223748,223743,223742,223741,223740,223739,223735,223731,223730,223728,223727,223722,223716,223715,223714,223709,223708,223704,223701,223699,223696,223695,223694,223692,223689,223688,223687,223686,223679,223675,223673,223671,223665,223662,223659,223658,223656,223652,223648,223647,223645,223644,223636,223635,223634,223632,223630,223628,223626,223623,223621,223620,223616,223615,223613,223612,223609,223606,223599,223595,223593,223592,223590,223587,223586,223585,223580,223579,223578,223577,223576,223575,223572,223570,223565,223561,223560,223559,223557,223553,223552,223550,223548,223547,223544,223542,223536,223525,223524,223520,223518,223517,223516,223514,223513,223512,223511,223510,223505,223503,223502,223501,223495,223492,223485,223482,223479,223477,223474,223472,223470,223469,223465,223463,223462,223457,223456,223455,223453,223452,223449,223448,223445,223436,223434,223430,223429,223428,223427,223426,223423,223422,223417,223415,223414,223404,223401,223398,223395,223394,223393,223388,223387,223383,223382,223381,223380,223379,223378,223375,223374,223373,223372,223371,223368,223363,223361,223360,223359,223358,223356,223355,223354,223353,223352,223351,223350,223348,223344,223342,223340,223339,223337,223336,223335,223334,223332,223329,223327,223326,223325,223321,223320,223319,223318,223317,223315,223313,223312,223310,223306,223302,223299,223288,223287,223276,223273,223272,223271,223266,223262,223259,223257,223256,223254,223253,223251,223241,223235,223226,223225,223224,223223,223222,223221,223217,223215,223214,223213,223212,223211,223210,223207,223203,223201,223199,223197,223195,223194,223192,223191,223190,223189,223188,223187,223186,223185,223184,223183,223182,223181,223180,223178,223176,223175,223174,223171,223169,223165,223164,223163,223159,223155,223154,223148,223146,223145,223140,223139,223136,223134,223132,223130,223129,223128,223127,223125,223124,223123,223122,223120,223116,223115,223114,223112,223109,223108,223106,223105,223104,223103,223101,223098,223097,223094,223084,223083,223076,223074,223073,223072,223071,223066,223065,223064,223063,223058,223054,223053,223050,223049,223047,223045,223044,223035,223027,223021,223019,223014,223005,223004,223003,223002,223001,222999,222998,222995,222994,222993,222992,222987,222986,222985,222982,222975,222973,222972,222970,222968,222964,222962,222959,222951,222948,222940,222939,222936,222933,222930,222928,222924,222920,222919,222918,222917,222915,222914,222913,222910,222909,222906,222905,222902,222901,222900,222899,222896,222895,222894,222893,222892,222880,222875,222871,222867,222864,222861,222858,222851,222846,222845,222843,222842,222841,222839,222838,222837,222831,222826,222825,222824,222822,222821,222818,222815,222814,222806,222804,222802,222799,222798,222795,222793,222792,222790,222785,222784,222783,222782,222780,222776,222775,222774,222773,222769,222768,222767,222764,222762,222761,222760,222759,222757,222753,222750,222744,222743,222742,222740,222739,222738,222732,222731,222727,222726,222721,222719,222717,222713,222711,222709,222707,222703,222700,222699,222698,222697,222695,222693,222692,222686,222685,222684,222682,222681,222676,222674,222673,222672,222670,222667,222666,222665,222662,222661,222658,222650,222645,222644,222641,222640,222635,222634,222631,222629,222628,222622,222620,222615,222614,222612,222609,222608,222607,222604,222599,222595,222594,222593,222591,222590,222586,222584,222582,222581,222579,222572,222571,222568,222567,222566,222560,222556,222553,222551,222550,222547,222546,222545,222540,222537,222532,222531,222530,222525,222524,222522,222521,222520,222518,222515,222513,222512,222509,222507,222506,222501,222500,222499,222496,222495,222493,222492,222490,222487,222476,222472,222470,222469,222467,222461,222460,222459,222458,222457,222452,222448,222447,222446,222444,222441,222440,222439,222437,222435,222434,222433,222432,222431,222430,222422,222420,222419,222418,222417,222415,222411,222410,222407,222406,222404,222402,222399,222397,222391,222390,222388,222382,222380,222378,222377,222376,222374,222369,222367,222364,222363,222360,222359,222356,222354,222353,222351,222350,222345,222341,222340,222336,222335,222334,222333,222331,222330,222329,222327,222323,222322,222319,222318,222317,222307,222305,222302,222301,222300,222299,222297,222294,222292,222291,222289,222287,222285,222283,222280,222277,222276,222275,222273,222272,222271,222268,222267,222266,222265,222264,222260,222259,222258,222256,222254,222253,222252,222251,222249,222247,222243,222231,222230,222227,222226,222225,222222,222221,222220,222212,222210,222208,222206,222201,222200,222199,222197,222195,222194,222193,222189,222188,222187,222181,222178,222176,222172,222171,222170,222169,222166,222164,222162,222161,222159,222157,222154,222153,222152,222150,222146,222145,222141,222134,222129,222128,222124,222121,222113,222112,222111,222109,222106,222104,222102,222101,222099,222092,222090,222088,222081,222080,222073,222072,222067,222066,222065,222064,222062,222061,222058,222055,222053,222051,222049,222048,222042,222037,222036,222034,222033,222031,222029,222027,222015,222010,222009,222004,222003,222002,221998,221994,221991,221990,221985,221983,221982,221981,221975,221971,221969,221967,221966,221964,221962,221960,221958,221951,221950,221946,221945,221943,221938,221937,221936,221935,221934,221928,221927,221926,221925,221924,221923,221921,221919,221918,221916,221914,221913,221912,221910,221909,221908,221905,221902,221898,221897,221896,221889,221883,221879,221876,221874,221861,221860,221859,221858,221855,221854,221853,221851,221849,221844,221843,221841,221840,221837,221836,221835,221833,221832,221831,221830,221829,221828,221827,221826,221821,221818,221815,221814,221808,221805,221803,221802,221799,221796,221795,221791,221790,221786,221783,221782,221781,221777,221776,221774,221773,221771,221768,221765,221764,221763,221757,221755,221753,221746,221740,221739,221738,221734,221733,221728,221726,221722,221720,221719,221715,221713,221711,221708,221706,221703,221702,221698,221695,221694,221689,221688,221685,221682,221678,221677,221673,221670,221668,221667,221661,221660,221659,221658,221657,221655,221652,221648,221647,221646,221645,221644,221642,221640,221638,221637,221636,221635,221634,221633,221632,221631,221630,221628,221627,221625,221624,221623,221621,221619,221617,221616,221615,221612,221607,221605,221604,221602,221596,221594,221588,221585,221584,221582,221579,221576,221575,221574,221573,221571,221570,221569,221566,221565,221562,221561,221560,221559,221553,221551,221550,221549,221548,221547,221546,221545,221544,221543,221542,221541,221540,221539,221538,221537,221536,221534,221533,221532,221531,221530,221529,221525,221523,221519,221517,221513,221512,221507,221505,221502,221501,221500,221498,221497,221486,221485,221484,221482,221477,221476,221475,221474,221472,221468,221466,221463,221462,221461,221456,221455,221453,221452,221451,221450,221449,221447,221446,221444,221441,221439,221437,221431,221426,221422,221418,221417,221416,221414,221412,221409,221408,221407,221403,221402,221401,221398,221397,221396,221394,221390,221388,221385,221380,221379,221378,221377,221371,221369,221366,221365,221363,221360,221359,221357,221355,221352,221351,221348,221344,221341,221334,221331,221319,221316,221315,221312,221310,221309,221306,221304,221303,221300,221299,221298,221297,221296,221292,221286,221275,221271,221270,221268,221267,221266,221265,221264,221263,221262,221261,221258,221257,221255,221246,221240,221239,221237,221236,221229,221226,221225,221222,221221,221220,221219,221218,221214,221212,221211,221209,221208,221206,221204,221202,221199,221195,221194,221193,221191,221187,221186,221185,221184,221183,221182,221181,221178,221176,221175,221169,221167,221165,221160,221156,221153,221149,221148,221145,221142,221134,221133,221129,221123,221122,221112,221110,221108,221106,221096,221094,221093,221074,221073,221068,221064,221063,221062,221056,221053,221052,221051,221050,221048,221043,221037,221033,221032,221031,221027,221024,221022,221021,221020,221018,221011,221009,221007,221006,221005,221002,221001,220993,220991,220989,220988,220987,220984,220981,220980,220978,220976,220975,220973,220971,220970,220969,220967,220966,220965,220961,220960,220956,220955,220954,220953,220950,220949,220948,220946,220935,220934,220933,220928,220926,220925,220921,220916,220911,220904,220902,220901,220900,220898,220896,220894,220892,220891,220890,220889,220880,220879,220874,220873,220872,220868,220861,220860,220858,220857,220856,220853,220852,220850,220847,220846,220844,220843,220842,220841,220837,220833,220832,220830,220829,220828,220827,220826,220824,220820,220814,220813,220812,220811,220808,220807,220806,220805,220802,220797,220794,220790,220788,220786,220785,220783,220782,220779,220777,220773,220770,220768,220767,220766,220765,220763,220752,220749,220744,220743,220742,220739,220736,220734,220731,220730,220727,220726,220724,220723,220722,220720,220719,220715,220713,220712,220710,220708,220704,220703,220702,220701,220692,220687,220686,220684,220682,220681,220677,220675,220674,220667,220666,220665,220664,220662,220661,220659,220658,220657,220656,220652,220651,220649,220648,220646,220643,220640,220638,220636,220635,220632,220627,220626,220625,220623,220622,220621,220620,220616,220611,220607,220606,220605,220604,220600,220596,220595,220594,220593,220589,220585,220581,220579,220577,220572,220571,220570,220566,220565,220563,220554,220550,220549,220548,220547,220545,220542,220537,220534,220528,220521,220520,220519,220518,220510,220509,220507,220505,220497,220491,220489,220486,220485,220483,220473,220471,220469,220468,220467,220463,220455,220454,220450,220449,220442,220441,220440,220432,220431,220430,220429,220427,220426,220422,220420,220419,220418,220417,220411,220410,220409,220407,220404,220401,220397,220392,220386,220384,220382,220381,220380,220379,220377,220369,220368,220367,220364,220362,220361,220352,220351,220349,220345,220344,220343,220341,220339,220338,220336,220332,220329,220328,220324,220322,220321,220319,220314,220307,220303,220300,220299,220296,220293,220292,220288,220285,220282,220280,220277,220275,220272,220271,220268,220267,220265,220262,220258,220256,220246,220245,220239,220236,220235,220233,220232,220230,220229,220228,220227,220224,220223,220218,220217,220212,220208,220206,220204,220203,220200,220199,220198,220197,220196,220192,220183,220182,220181,220179,220178,220171,220168,220167,220163,220162,220161,220159,220158,220157,220153,220150,220149,220148,220146,220145,220143,220140,220137,220136,220129,220127,220121,220116,220114,220106,220103,220101,220100,220098,220097,220094,220092,220090,220085,220083,220080,220079,220077,220076,220075,220073,220072,220070,220069,220068,220066,220059,220057,220055,220052,220048,220046,220045,220041,220040,220038,220036,220035,220031,220030,220029,220026,220025,220019,220018,220017,220015,220010,220009,220008,220004,220003,220000,219999,219993,219987,219986,219985,219981,219979,219978,219977,219975,219971,219969,219968,219967,219966,219962,219957,219956,219955,219954,219953,219949,219948,219946,219945,219943,219940,219937,219936,219930,219927,219923,219920,219914,219913,219912,219908,219906,219900,219898,219894,219892,219891,219889,219888,219883,219882,219881,219880,219878,219877,219875,219872,219860,219859,219856,219852,219851,219849,219846,219840,219839,219837,219833,219832,219831,219829,219828,219824,219823,219822,219821,219819,219818,219815,219810,219803,219798,219797,219792,219791,219790,219789,219787,219786,219785,219784,219782,219781,219780,219778,219773,219772,219771,219770,219766,219764,219763,219761,219760,219759,219758,219753,219752,219748,219747,219744,219743,219740,219738,219735,219734,219731,219730,219729,219726,219718,219715,219714,219710,219709,219708,219704,219700,219696,219693,219690,219688,219686,219685,219684,219683,219681,219680,219677,219676,219674,219664,219662,219659,219658,219656,219654,219653,219651,219650,219649,219648,219646,219643,219640,219635,219632,219631,219630,219627,219624,219620,219619,219615,219614,219613,219612,219611,219610,219598,219596,219595,219592,219591,219587,219578,219576,219573,219572,219570,219569,219568,219564,219562,219560,219558,219547,219545,219544,219542,219541,219540,219539,219538,219534,219533,219532,219530,219525,219521,219519,219517,219516,219514,219513,219511,219509,219507,219505,219504,219502,219501,219500,219499,219498,219497,219496,219494,219491,219490,219488,219487,219486,219485,219480,219479,219478,219475,219473,219472,219471,219470,219467,219461,219456,219454,219453,219446,219443,219442,219441,219436,219435,219434,219429,219428,219423,219421,219417,219414,219412,219411,219409,219408,219404,219403,219400,219399,219394,219392,219391,219390,219389,219386,219385,219384,219383,219382,219380,219379,219371,219369,219368,219366,219365,219362,219359,219358,219357,219356,219354,219351,219346,219344,219343,219339,219338,219336,219335,219334,219332,219331,219329,219327,219323,219321,219320,219319,219318,219315,219314,219308,219307,219306,219305,219304,219299,219298,219296,219293,219291,219286,219281,219280,219279,219275,219272,219269,219268,219262,219260,219259,219253,219252,219251,219243,219242,219240,219236,219235,219233,219232,219231,219230,219227,219226,219223,219222,219221,219219,219218,219213,219205,219202,219201,219200,219193,219192,219189,219185,219184,219182,219181,219178,219168,219162,219161,219160,219154,219153,219151,219150,219149,219148,219147,219146,219145,219144,219140,219138,219137,219136,219124,219120,219119,219118,219111,219110,219109,219103,219099,219096,219092,219091,219088,219087,219086,219084,219083,219080,219079,219077,219074,219071,219069,219068,219066,219065,219064,219062,219061,219059,219055,219054,219053,219050,219049,219045,219044,219043,219036,219035,219033,219031,219030,219024,219023,219022,219020,219018,219013,219012,219011,219010,219009,219003,218997,218996,218992,218990,218989,218988,218987,218986,218983,218982,218978,218976,218971,218969,218968,218965,218962,218960,218956,218951,218950,218949,218948,218947,218945,218944,218942,218941,218940,218939,218937,218935,218934,218933,218931,218929,218927,218920,218905,218904,218892,218890,218882,218880,218877,218875,218874,218862,218859,218855,218854,218853,218851,218845,218844,218840,218839,218835,218832,218830,218826,218825,218822,218821,218820,218819,218818,218817,218811,218809,218808,218807,218806,218805,218804,218803,218800,218798,218797,218792,218791,218787,218785,218782,218781,218780,218777,218776,218774,218773,218772,218770,218764,218762,218761,218759,218757,218752,218751,218748,218746,218738,218737,218734,218732,218730,218729,218727,218721,218717,218716,218714,218713,218704,218695,218691,218689,218688,218686,218680,218678,218676,218672,218671,218669,218668,218667,218664,218663,218662,218659,218657,218656,218654,218653,218652,218650,218648,218645,218636,218632,218631,218630,218627,218626,218624,218622,218617,218614,218600,218599,218594,218593,218591,218590,218589,218584,218583,218582,218579,218577,218575,218572,218570,218566,218564,218561,218560,218558,218557,218554,218553,218548,218545,218544,218541,218540,218534,218533,218532,218530,218529,218528,218526,218525,218516,218514,218511,218508,218506,218502,218501,218500,218497,218492,218491,218486,218485,218483,218481,218480,218479,218478,218477,218474,218473,218471,218470,218468,218467,218466,218464,218462,218461,218460,218458,218457,218452,218450,218445,218442,218439,218438,218433,218432,218428,218427,218425,218421,218420,218419,218417,218415,218414,218411,218406,218400,218395,218390,218389,218387,218383,218377,218374,218372,218371,218370,218369,218368,218365,218364,218361,218359,218358,218357,218353,218351,218350,218349,218346,218340,218334,218333,218332,218330,218329,218327,218326,218322,218321,218320,218319,218317,218315,218314,218313,218312,218311,218306,218305,218304,218302,218300,218299,218298,218297,218296,218291,218289,218288,218284,218279,218278,218276,218275,218273,218272,218268,218267,218266,218264,218263,218262,218260,218259,218258,218256,218255,218254,218252,218246,218244,218243,218242,218241,218240,218237,218232,218230,218229,218227,218225,218222,218216,218206,218204,218203,218201,218200,218199,218197,218192,218191,218186,218181,218179,218178,218176,218170,218164,218160,218150,218146,218143,218142,218138,218135,218134,218131,218130,218124,218123,218119,218114,218107,218100,218099,218093,218088,218085,218084,218082,218065,218064,218061,218060,218058,218052,218051,218046,218041,218040,218038,218036,218032,218031,218030,218028,218024,218022,218020,218018,218016,218014,218013,218012,218011,218008,218007,218006,218005,218003,217997,217994,217991,217990,217988,217987,217985,217982,217981,217969,217968,217962,217960,217958,217955,217951,217950,217946,217928,217927,217926,217925,217924,217923,217922,217921,217920,217919,217918,217917,217916,217912,217911,217910,217908,217907,217905,217904,217900,217899,217898,217897,217894,217891,217890,217889,217888,217887,217885,217883,217882,217880,217878,217876,217874,217873,217871,217870,217861,217859,217858,217856,217853,217852,217850,217846,217843,217842,217839,217835,217831,217830,217823,217820,217818,217817,217813,217812,217811,217810,217805,217801,217800,217799,217793,217792,217791,217790,217787,217784,217781,217780,217779,217778,217777,217773,217772,217771,217768,217767,217766,217763,217762,217761,217753,217752,217745,217721,217719,217717,217716,217713,217711,217710,217705,217698,217697,217696,217695,217694,217691,217686,217685,217683,217682,217681,217678,217677,217673,217672,217671,217670,217669,217664,217659,217658,217657,217654,217646,217644,217641,217640,217638,217635,217631,217630,217628,217625,217624,217621,217619,217618,217617,217615,217614,217610,217609,217607,217605,217603,217601,217599,217598,217597,217596,217595,217594,217592,217587,217586,217583,217578,217577,217573,217572,217571,217570,217567,217565,217564,217563,217562,217561,217556,217552,217550,217545,217544,217543,217538,217537,217536,217533,217532,217531,217530,217526,217525,217524,217521,217520,217519,217515,217514,217513,217511,217508,217504,217503,217499,217497,217496,217492,217490,217489,217485,217480,217476,217472,217471,217469,217466,217465,217464,217462,217461,217460,217459,217457,217454,217453,217451,217448,217441,217440,217439,217437,217436,217434,217432,217430,217429,217426,217425,217416,217415,217414,217408,217406,217403,217401,217400,217397,217395,217393,217389,217388,217387,217386,217385,217379,217368,217364,217362,217360,217358,217357,217353,217351,217350,217344,217342,217340,217339,217338,217337,217336,217334,217329,217328,217326,217322,217321,217320,217319,217315,217313,217311,217309,217307,217306,217304,217302,217301,217300,217299,217293,217291,217289,217278,217276,217273,217272,217270,217269,217263,217254,217253,217252,217251,217250,217248,217247,217243,217241,217238,217234,217232,217229,217224,217218,217216,217214,217210,217209,217208,217206,217202,217201,217199,217196,217195,217194,217193,217187,217183,217178,217176,217175,217173,217172,217171,217167,217166,217163,217162,217158,217157,217149,217146,217145,217144,217143,217140,217138,217137,217136,217134,217133,217130,217125,217124,217121,217120,217116,217112,217111,217108,217105,217103,217099,217098,217093,217090,217087,217086,217081,217078,217075,217074,217070,217069,217068,217067,217066,217063,217060,217055,217051,217050,217049,217046,217045,217042,217035,217031,217026,217025,217023,217022,217021,217020,217018,217017,217015,217014,217010,217009,217008,217007,217001,216995,216994,216992,216988,216987,216983,216982,216979,216977,216974,216971,216963,216961,216960,216957,216954,216953,216952,216950,216949,216948,216947,216935,216932,216931,216928,216922,216920,216919,216916,216914,216909,216906,216902,216901,216900,216897,216891,216890,216887,216886,216882,216881,216879,216873,216872,216871,216870,216863,216860,216857,216854,216852,216850,216847,216844,216842,216838,216836,216833,216832,216830,216827,216825,216823,216818,216816,216814,216812,216811,216804,216802,216800,216796,216790,216789,216788,216787,216782,216780,216779,216777,216776,216768,216764,216761,216760,216758,216756,216755,216753,216752,216751,216748,216746,216744,216739,216738,216737,216733,216732,216724,216722,216721,216720,216719,216715,216714,216711,216708,216707,216706,216704,216702,216700,216694,216692,216691,216689,216685,216681,216680,216676,216675,216674,216671,216667,216666,216663,216662,216661,216660,216657,216649,216645,216642,216640,216637,216636,216634,216633,216631,216630,216628,216626,216622,216621,216619,216614,216608,216607,216606,216605,216604,216603,216598,216596,216595,216594,216593,216592,216587,216583,216580,216579,216575,216571,216567,216560,216559,216558,216552,216551,216549,216548,216546,216545,216543,216541,216535,216533,216529,216527,216525,216523,216517,216514,216511,216510,216509,216508,216507,216505,216504,216502,216501,216500,216495,216494,216492,216490,216489,216488,216487,216486,216484,216475,216474,216470,216467,216455,216449,216445,216441,216440,216438,216432,216430,216429,216426,216424,216420,216417,216415,216414,216412,216411,216410,216405,216404,216401,216399,216394,216392,216391,216387,216386,216381,216378,216377,216374,216360,216357,216355,216352,216351,216347,216346,216333,216332,216331,216329,216326,216324,216320,216319,216318,216317,216315,216313,216312,216307,216305,216304,216303,216301,216297,216296,216295,216289,216288,216287,216286,216285,216282,216281,216279,216274,216273,216270,216268,216267,216265,216263,216261,216260,216256,216252,216251,216249,216248,216243,216242,216241,216232,216228,216226,216220,216214,216211,216210,216209,216202,216201,216200,216196,216195,216194,216193,216192,216190,216188,216186,216185,216176,216172,216170,216168,216165,216163,216162,216157,216153,216152,216151,216148,216145,216139,216134,216132,216131,216130,216124,216123,216120,216119,216117,216113,216109,216107,216105,216102,216101,216099,216098,216095,216094,216093,216091,216084,216077,216073,216068,216063,216062,216061,216059,216058,216057,216054,216053,216052,216050,216047,216045,216041,216040,216039,216038,216036,216035,216034,216030,216024,216023,216022,216021,216016,216009,216008,216005,216002,216001,216000,215999,215994,215991,215989,215986,215985,215981,215980,215978,215977,215975,215974,215973,215972,215971,215970,215965,215963,215961,215960,215959,215957,215956,215955,215954,215949,215948,215947,215942,215941,215934,215929,215927,215926,215925,215924,215923,215920,215919,215917,215916,215915,215914,215912,215911,215910,215909,215908,215906,215905,215904,215902,215901,215897,215896,215895,215892,215891,215890,215885,215883,215880,215877,215871,215869,215865,215861,215860,215858,215856,215855,215853,215852,215850,215845,215843,215838,215837,215830,215829,215821,215819,215818,215813,215810,215805,215797,215794,215790,215788,215785,215784,215780,215779,215778,215777,215776,215775,215774,215773,215772,215770,215769,215765,215763,215758,215756,215755,215754,215753,215752,215750,215749,215747,215744,215743,215740,215733,215731,215726,215725,215722,215721,215719,215718,215711,215709,215701,215697,215695,215691,215690,215687,215684,215682,215681,215678,215677,215674,215673,215670,215661,215658,215653,215652,215651,215650,215643,215641,215639,215635,215633,215631,215629,215626,215625,215624,215623,215621,215618,215616,215612,215610,215602,215601,215594,215590,215588,215587,215584,215580,215577,215576,215571,215569,215568,215563,215560,215559,215550,215549,215547,215542,215541,215539,215537,215529,215528,215527,215526,215524,215523,215521,215519,215518,215513,215511,215510,215508,215506,215499,215496,215492,215489,215488,215485,215482,215481,215475,215473,215469,215468,215466,215461,215460,215457,215454,215451,215446,215444,215440,215438,215437,215436,215434,215433,215431,215429,215427,215425,215423,215422,215417,215416,215411,215410,215409,215404,215402,215399,215397,215396,215395,215392,215388,215387,215384,215382,215378,215375,215374,215371,215367,215365,215364,215363,215360,215355,215354,215349,215344,215335,215328,215327,215326,215325,215324,215323,215321,215320,215317,215315,215314,215313,215312,215311,215306,215298,215297,215292,215289,215287,215285,215284,215279,215277,215274,215272,215267,215265,215261,215259,215256,215255,215244,215243,215240,215239,215236,215234,215233,215232,215229,215223,215222,215220,215218,215209,215206,215205,215203,215202,215199,215198,215197,215196,215194,215193,215192,215189,215188,215186,215182,215180,215179,215178,215177,215169,215165,215164,215162,215154,215152,215149,215143,215142,215139,215137,215136,215133,215126,215125,215120,215114,215112,215108,215105,215104,215103,215100,215098,215095,215094,215093,215092,215081,215080,215078,215072,215070,215069,215064,215061,215050,215047,215046,215044,215043,215041,215031,215030,215028,215026,215023,215022,215020,215019,215017,215014,215013,215011,215008,215007,215004,215003,215002,214995,214994,214993,214990,214988,214986,214984,214982,214981,214980,214978,214977,214975,214971,214965,214963,214962,214959,214958,214956,214955,214954,214952,214951,214949,214941,214940,214935,214932,214931,214928,214916,214915,214907,214906,214900,214897,214896,214891,214890,214887,214885,214883,214879,214872,214871,214869,214867,214860,214854,214851,214848,214846,214843,214842,214841,214839,214838,214836,214835,214833,214832,214831,214828,214824,214815,214808,214806,214803,214802,214801,214800,214799,214794,214792,214788,214787,214786,214785,214784,214783,214782,214781,214780,214779,214778,214777,214774,214773,214771,214770,214765,214763,214762,214760,214757,214756,214750,214749,214747,214746,214744,214740,214738,214736,214728,214727,214726,214721,214717,214714,214713,214711,214708,214701,214700,214698,214696,214694,214691,214690,214688,214687,214686,214684,214683,214680,214672,214669,214667,214666,214665,214662,214660,214659,214658,214655,214649,214641,214640,214633,214625,214624,214623,214620,214618,214617,214612,214610,214608,214601,214600,214597,214596,214595,214594,214593,214591,214588,214587,214586,214582,214579,214578,214575,214570,214568,214564,214563,214561,214559,214555,214552,214550,214549,214548,214544,214542,214539,214534,214529,214521,214520,214519,214512,214509,214506,214497,214495,214493,214490,214478,214476,214473,214472,214469,214462,214457,214456,214451,214449,214448,214443,214441,214439,214437,214435,214432,214431,214430,214429,214427,214424,214422,214417,214416,214411,214408,214406,214405,214404,214401,214399,214398,214397,214395,214394,214393,214388,214386,214384,214381,214380,214378,214377,214376,214374,214373,214370,214369,214368,214367,214364,214361,214360,214354,214352,214348,214347,214345,214344,214341,214340,214339,214332,214330,214326,214325,214324,214323,214315,214313,214308,214307,214301,214300,214298,214291,214283,214281,214279,214277,214275,214274,214272,214271,214269,214268,214267,214263,214262,214256,214255,214244,214241,214240,214235,214233,214231,214228,214226,214221,214219,214216,214213,214212,214210,214205,214203,214202,214194,214192,214185,214183,214179,214178,214177,214173,214164,214163,214155,214153,214152,214150,214142,214139,214126,214125,214105,214102,214101,214094,214092,214089,214088,214087,214086,214069,214065,214062,214058,214057,214054,214051,214042,214041,214040,214038,214033,214029,214023,214020,214017,214014,214013,214008,214007,214005,214003,214000,213997,213992,213983,213981,213978,213974,213968,213967,213962,213953,213946,213945,213941,213932,213930,213921,213919,213917,213913,213912,213910,213906,213903,213901,213900,213897,213896,213893,213890,213878,213876,213873,213871,213870,213867,213864,213861,213858,213851,213849,213847,213841,213837,213835,213833,213832,213826,213820,213819,213818,213817,213814,213808,213803,213802,213799,213797,213796,213795,213790,213787,213786,213780,213772,213759,213757,213753,213750,213749,213747,213744,213743,213741,213737,213733,213729,213728,213724,213721,213720,213717,213715,213712,213708,213706,213704,213702,213701,213695,213689,213686,213683,213677,213674,213673,213664,213653,213652,213651,213650,213641,213636,213635,213630,213628,213626,213625,213620,213619,213616,213613,213611,213609,213607,213605,213601,213597,213594,213591,213588,213584,213583,213581,213580,213579,213574,213573,213572,213571,213568,213565,213563,213562,213556,213549,213538,213537,213533,213532,213531,213528,213527,213526,213524,213521,213518,213516,213514,213511,213510,213508,213507,213504,213501,213499,213497,213493,213490,213483,213482,213478,213477,213469,213468,213467,213463,213460,213458,213456,213446,213442,213440,213439,213438,213437,213435,213433,213432,213426,213424,213422,213412,213411,213410,213400,213396,213391,213390,213389,213388,213384,213380,213378,213375,213372,213370,213367,213365,213363,213360,213348,213345,213343,213342,213340,213335,213330,213326,213324,213323,213322,213320,213310,213305,213303,213302,213301,213298,213296,213288,213286,213285,213284,213281,213280,213279,213278,213274,213272,213271,213266,213265,213263,213262,213257,213252,213251,213250,213249,213248,213244,213240,213237,213229,213224,213220,213210,213209,213203,213200,213194,213190,213189,213188,213180,213175,213170,213169,213164,213162,213161,213158,213156,213152,213151,213142,213141,213137,213135,213132,213131,213129,213126,213124,213118,213117,213116,213113,213111,213108,213107,213096,213089,213088,213083,213082,213077,213076,213074,213072,213071,213070,213068,213067,213065,213064,213061,213060,213059,213057,213055,213052,213050,213049,213047,213042,213041,213039,213038,213037,213033,213029,213024,213022,213020,213019,213017,213015,213014,213013,213012,213011,213007,213006,213005,212999,212996,212993,212983,212980,212970,212967,212961,212959,212955,212954,212953,212951,212950,212948,212943,212940,212939,212938,212931,212930,212927,212926,212925,212923,212921,212919,212918,212917,212915,212914,212913,212909,212908,212900,212897,212894,212893,212882,212879,212874,212873,212871,212869,212865,212864,212862,212860,212846,212839,212838,212835,212833,212827,212826,212823,212818,212814,212813,212812,212810,212809,212806,212805,212804,212803,212802,212799,212798,212790,212787,212784,212783,212778,212777,212774,212770,212769,212768,212767,212760,212759,212753,212751,212749,212745,212743,212742,212741,212740,212739,212738,212736,212735,212734,212732,212731,212728,212726,212725,212724,212721,212719,212714,212713,212709,212708,212707,212702,212701,212694,212687,212686,212684,212680,212673,212669,212665,212662,212657,212654,212652,212651,212649,212646,212638,212634,212621,212620,212614,212599,212597,212596,212595,212587,212585,212583,212580,212578,212577,212570,212567,212565,212564,212563,212562,212559,212552,212551,212547,212545,212544,212543,212538,212535,212534,212531,212530,212526,212525,212524,212520,212519,212518,212516,212515,212511,212509,212507,212504,212500,212497,212496,212494,212491,212485,212484,212483,212480,212478,212475,212473,212470,212468,212467,212462,212452,212451,212450,212447,212445,212437,212435,212434,212432,212429,212428,212422,212420,212417,212416,212407,212405,212404,212403,212399,212395,212391,212390,212388,212384,212381,212379,212376,212374,212372,212369,212367,212363,212362,212361,212360,212359,212357,212356,212353,212349,212347,212346,212345,212343,212336,212331,212326,212324,212323,212318,212313,212310,212309,212305,212298,212296,212292,212291,212287,212286,212285,212283,212281,212274,212273,212269,212265,212259,212254,212253,212248,212246,212239,212237,212236,212235,212233,212232,212231,212230,212228,212227,212224,212222,212221,212220,212219,212218,212215,212211,212209,212208,212207,212204,212202,212201,212198,212196,212187,212185,212184,212183,212182,212180,212179,212177,212176,212168,212164,212161,212154,212147,212142,212139,212138,212132,212131,212130,212125,212118,212117,212116,212115,212111,212110,212109,212108,212106,212094,212093,212092,212089,212080,212079,212078,212077,212075,212072,212066,212063,212062,212061,212060,212056,212054,212051,212050,212048,212045,212039,212035,212033,212032,212031,212029,212028,212027,212023,212019,212018,212004,212003,212001,211998,211996,211991,211990,211989,211986,211981,211977,211976,211973,211972,211968,211967,211966,211962,211961,211959,211958,211957,211956,211954,211953,211948,211946,211940,211937,211936,211935,211930,211927,211925,211917,211916,211915,211913,211911,211909,211904,211901,211898,211896,211895,211892,211884,211881,211880,211876,211871,211869,211866,211865,211864,211859,211857,211853,211850,211843,211841,211840,211835,211834,211832,211831,211826,211824,211821,211820,211817,211813,211812,211809,211808,211807,211800,211797,211793,211789,211788,211787,211781,211779,211775,211774,211773,211772,211767,211765,211762,211761,211757,211756,211753,211752,211749,211748,211746,211745,211741,211740,211739,211738,211737,211736,211734,211731,211730,211727,211725,211723,211721,211720,211719,211718,211717,211712,211711,211705,211703,211697,211692,211686,211683,211681,211677,211673,211672,211671,211669,211667,211663,211659,211658,211655,211654,211652,211651,211647,211643,211642,211641,211638,211637,211636,211634,211633,211630,211625,211610,211604,211601,211595,211589,211585,211582,211580,211576,211573,211572,211562,211561,211558,211557,211553,211548,211539,211537,211524,211522,211520,211519,211518,211514,211512,211504,211502,211490,211489,211488,211485,211484,211483,211479,211474,211470,211469,211468,211465,211456,211455,211453,211451,211450,211449,211448,211447,211446,211442,211441,211440,211439,211430,211427,211426,211424,211422,211421,211419,211418,211413,211411,211410,211404,211403,211402,211398,211394,211393,211391,211386,211378,211377,211373,211371,211367,211366,211365,211364,211363,211359,211358,211354,211352,211347,211343,211342,211338,211336,211334,211330,211329,211328,211323,211317,211310,211301,211297,211289,211283,211282,211281,211277,211276,211271,211270,211267,211266,211265,211261,211259,211257,211254,211253,211248,211247,211241,211236,211235,211234,211231,211226,211225,211217,211214,211213,211212,211211,211207,211203,211191,211186,211184,211181,211180,211176,211174,211173,211172,211171,211170,211168,211166,211162,211160,211159,211157,211155,211154,211152,211144,211143,211142,211139,211138,211133,211129,211124,211121,211120,211119,211118,211117,211115,211114,211113,211099,211098,211097,211092,211089,211087,211085,211075,211074,211072,211070,211069,211067,211063,211052,211049,211046,211041,211040,211038,211036,211035,211034,211032,211030,211029,211027,211024,211023,211021,211018,211016,211015,211014,211013,211012,211011,211009,211008,211002,210996,210992,210987,210986,210983,210982,210978,210976,210973,210972,210971,210970,210963,210962,210961,210960,210959,210957,210956,210952,210948,210947,210946,210945,210943,210940,210930,210928,210921,210919,210914,210906,210900,210899,210894,210890,210889,210888,210887,210885,210882,210881,210880,210876,210875,210872,210871,210869,210868,210866,210865,210860,210853,210850,210849,210846,210843,210841,210838,210837,210835,210833,210827,210822,210817,210813,210810,210807,210806,210805,210804,210803,210800,210799,210798,210794,210791,210790,210787,210786,210785,210784,210779,210777,210772,210771,210768,210767,210766,210765,210763,210755,210749,210745,210744,210739,210733,210727,210724,210723,210719,210717,210714,210713,210706,210702,210699,210698,210697,210696,210695,210692,210688,210685,210684,210683,210681,210679,210676,210673,210672,210671,210670,210669,210664,210659,210658,210654,210649,210644,210643,210639,210635,210632,210629,210627,210626,210625,210624,210623,210619,210618,210614,210612,210611,210606,210604,210600,210597,210595,210593,210591,210589,210582,210578,210576,210575,210570,210565,210564,210563,210562,210560,210556,210551,210550,210549,210548,210545,210543,210542,210539,210538,210533,210531,210529,210527,210524,210521,210520,210519,210518,210515,210513,210508,210502,210495,210493,210492,210490,210487,210486,210482,210481,210472,210469,210466,210465,210459,210457,210454,210453,210452,210448,210447,210446,210441,210440,210427,210424,210422,210417,210415,210413,210405,210402,210399,210398,210397,210391,210390,210388,210387,210384,210383,210376,210364,210343,210342,210341,210340,210338,210337,210336,210335,210333,210332,210330,210329,210327,210324,210320,210319,210317,210316,210310,210309,210305,210297,210296,210294,210292,210289,210280,210277,210276,210274,210270,210268,210267,210266,210263,210262,210258,210256,210253,210252,210251,210248,210247,210244,210240,210236,210234,210233,210232,210229,210226,210225,210224,210222,210220,210218,210217,210214,210211,210210,210208,210207,210206,210205,210197,210191,210184,210182,210178,210173,210172,210169,210166,210157,210155,210153,210151,210150,210148,210146,210144,210143,210140,210139,210134,210133,210132,210130,210129,210128,210127,210125,210121,210120,210118,210117,210116,210115,210114,210113,210111,210108,210107,210105,210104,210103,210102,210098,210095,210092,210090,210078,210070,210068,210066,210064,210063,210058,210055,210053,210047,210043,210042,210041,210040,210039,210038,210037,210036,210035,210031,210029,210028,210026,210024,210023,210020,210019,210017,210015,210008,210005,209999,209995,209993,209989,209988,209986,209985,209983,209982,209979,209976,209974,209973,209972,209971,209958,209953,209951,209950,209940,209939,209936,209934,209933,209924,209923,209920,209919,209917,209915,209912,209911,209910,209907,209899,209897,209896,209895,209894,209892,209886,209878,209876,209875,209874,209873,209867,209864,209860,209856,209853,209847,209840,209836,209830,209828,209825,209813,209810,209807,209805,209804,209803,209801,209800,209799,209797,209792,209790,209788,209787,209783,209780,209779,209770,209767,209766,209765,209763,209762,209760,209759,209758,209756,209753,209752,209749,209748,209746,209744,209742,209736,209735,209731,209728,209727,209726,209724,209715,209706,209703,209702,209701,209699,209695,209692,209691,209690,209689,209688,209687,209686,209685,209677,209673,209670,209669,209667,209665,209661,209659,209658,209655,209654,209652,209650,209648,209647,209645,209644,209643,209642,209639,209636,209623,209621,209620,209617,209616,209615,209614,209605,209598,209594,209591,209590,209588,209586,209585,209583,209580,209574,209570,209569,209565,209564,209559,209558,209557,209556,209552,209549,209548,209542,209540,209537,209533,209532,209526,209524,209523,209522,209521,209518,209516,209514,209513,209511,209510,209508,209506,209504,209503,209502,209500,209492,209489,209482,209481,209480,209476,209468,209467,209464,209463,209462,209461,209458,209456,209455,209454,209453,209450,209447,209443,209439,209438,209436,209435,209430,209429,209428,209427,209425,209419,209418,209414,209413,209411,209409,209406,209405,209403,209402,209398,209396,209393,209387,209385,209380,209379,209378,209377,209376,209375,209372,209370,209368,209367,209366,209361,209360,209358,209357,209354,209352,209346,209342,209340,209335,209331,209323,209322,209312,209309,209301,209298,209297,209296,209292,209288,209287,209286,209285,209284,209283,209281,209275,209266,209263,209261,209260,209258,209256,209244,209240,209239,209237,209235,209234,209233,209232,209231,209230,209228,209222,209219,209218,209217,209215,209213,209212,209210,209209,209207,209206,209205,209203,209200,209196,209191,209190,209188,209187,209186,209185,209184,209182,209181,209177,209170,209169,209168,209165,209164,209163,209162,209158,209157,209156,209155,209154,209151,209149,209145,209144,209143,209142,209141,209137,209136,209134,209131,209130,209128,209126,209124,209122,209121,209119,209118,209117,209116,209112,209111,209109,209108,209107,209106,209104,209099,209093,209092,209082,209080,209077,209076,209074,209073,209070,209068,209066,209065,209061,209056,209055,209054,209051,209050,209046,209043,209038,209036,209034,209030,209028,209019,209007,209006,209002,209001,208996,208987,208986,208973,208972,208971,208969,208966,208965,208964,208963,208957,208937,208936,208935,208932,208931,208926,208921,208919,208913,208911,208910,208908,208907,208906,208905,208904,208902,208898,208896,208894,208889,208888,208887,208886,208884,208882,208877,208876,208874,208871,208866,208861,208860,208858,208857,208856,208854,208853,208849,208836,208832,208830,208827,208826,208824,208822,208818,208811,208807,208804,208803,208797,208794,208787,208786,208785,208782,208778,208775,208768,208767,208764,208761,208755,208750,208748,208745,208744,208738,208737,208734,208733,208732,208731,208730,208728,208726,208725,208724,208722,208721,208718,208715,208713,208712,208707,208706,208705,208700,208696,208694,208693,208692,208690,208689,208688,208687,208685,208684,208683,208682,208681,208675,208669,208667,208664,208661,208660,208659,208656,208652,208651,208650,208647,208640,208636,208635,208633,208626,208623,208622,208621,208620,208617,208616,208613,208611,208609,208605,208604,208601,208595,208594,208593,208592,208591,208590,208584,208583,208582,208581,208578,208571,208567,208564,208562,208560,208559,208558,208556,208555,208544,208543,208539,208529,208527,208526,208522,208512,208504,208500,208499,208498,208496,208493,208490,208489,208487,208486,208485,208484,208480,208478,208477,208470,208469,208465,208461,208458,208454,208452,208449,208448,208444,208443,208441,208437,208436,208432,208426,208411,208404,208399,208398,208397,208395,208393,208389,208388,208384,208383,208380,208371,208370,208369,208366,208364,208362,208347,208346,208334,208332,208328,208327,208321,208315,208314,208313,208312,208303,208302,208298,208296,208291,208290,208289,208288,208286,208285,208284,208283,208282,208280,208273,208271,208270,208269,208266,208264,208263,208262,208260,208259,208255,208252,208249,208247,208246,208244,208243,208242,208239,208235,208231,208230,208229,208228,208225,208222,208221,208219,208216,208210,208202,208199,208198,208196,208195,208193,208178,208175,208172,208170,208166,208165,208164,208163,208159,208158,208157,208154,208150,208146,208143,208142,208141,208140,208139,208135,208133,208132,208128,208126,208124,208123,208122,208121,208119,208114,208113,208105,208104,208103,208102,208101,208098,208095,208092,208091,208090,208087,208085,208081,208080,208074,208073,208072,208070,208069,208067,208066,208065,208063,208062,208060,208057,208053,208051,208050,208047,208046,208045,208044,208042,208040,208038,208037,208026,208023,208020,208019,208018,208007,208006,208004,208003,208002,208001,208000,207997,207995,207992,207990,207988,207984,207982,207979,207978,207977,207973,207972,207971,207970,207966,207965,207964,207962,207959,207956,207955,207954,207948,207947,207945,207940,207935,207933,207932,207931,207928,207926,207924,207921,207918,207917,207916,207915,207914,207910,207909,207903,207902,207901,207900,207899,207895,207894,207892,207887,207886,207885,207881,207880,207878,207875,207874,207873,207872,207871,207869,207867,207866,207863,207860,207859,207857,207856,207854,207853,207852,207849,207847,207846,207845,207843,207841,207837,207836,207835,207833,207832,207831,207830,207827,207826,207823,207820,207818,207817,207814,207812,207811,207810,207809,207805,207802,207801,207800,207798,207797,207796,207794,207793,207792,207791,207785,207782,207781,207778,207777,207774,207773,207772,207766,207759,207758,207757,207753,207751,207747,207743,207742,207741,207737,207733,207732,207731,207708,207707,207706,207705,207703,207702,207699,207696,207692,207691,207690,207685,207684,207683,207679,207678,207677,207676,207675,207672,207670,207668,207667,207666,207665,207661,207660,207658,207656,207655,207654,207652,207651,207648,207645,207639,207630,207627,207617,207612,207608,207606,207605,207603,207602,207597,207592,207591,207588,207584,207583,207582,207577,207573,207572,207570,207568,207567,207560,207554,207553,207552,207551,207546,207544,207542,207541,207537,207536,207534,207533,207531,207530,207526,207520,207519,207515,207512,207511,207510,207509,207507,207505,207504,207501,207499,207497,207493,207492,207488,207486,207485,207482,207479,207477,207475,207471,207470,207464,207461,207460,207459,207458,207454,207451,207449,207447,207445,207444,207443,207442,207440,207439,207438,207434,207431,207430,207428,207427,207426,207425,207424,207421,207416,207414,207413,207412,207409,207407,207403,207401,207399,207396,207395,207393,207391,207390,207388,207387,207386,207379,207378,207377,207376,207374,207372,207366,207364,207362,207355,207353,207346,207342,207335,207333,207331,207328,207327,207326,207321,207313,207310,207309,207308,207305,207303,207300,207298,207297,207296,207294,207292,207289,207288,207287,207285,207281,207278,207276,207275,207274,207273,207272,207269,207268,207261,207257,207255,207253,207251,207248,207243,207237,207235,207231,207229,207216,207213,207211,207210,207208,207207,207205,207204,207201,207200,207199,207198,207197,207196,207194,207192,207190,207189,207188,207187,207186,207183,207181,207176,207175,207167,207166,207164,207162,207159,207158,207156,207155,207153,207152,207149,207148,207147,207142,207137,207134,207131,207129,207124,207123,207122,207119,207118,207115,207114,207109,207107,207103,207100,207098,207096,207095,207092,207089,207085,207084,207072,207069,207068,207066,207063,207061,207060,207059,207056,207055,207054,207053,207051,207047,207046,207042,207041,207040,207039,207038,207037,207033,207030,207029,207028,207024,207022,207018,207015,207014,207011,207008,207004,207002,207001,207000,206999,206998,206993,206990,206984,206981,206979,206978,206977,206975,206974,206972,206971,206968,206967,206966,206956,206953,206952,206951,206950,206949,206948,206943,206941,206940,206939,206932,206931,206929,206923,206919,206918,206916,206915,206914,206913,206912,206910,206909,206907,206906,206905,206904,206903,206901,206900,206894,206893,206892,206891,206890,206886,206878,206877,206875,206873,206870,206864,206860,206859,206858,206855,206853,206852,206850,206849,206848,206847,206839,206837,206836,206833,206830,206829,206827,206820,206818,206817,206814,206812,206811,206806,206805,206803,206800,206796,206790,206786,206781,206780,206778,206777,206776,206775,206774,206773,206768,206765,206762,206760,206757,206755,206754,206750,206746,206744,206742,206737,206736,206735,206734,206726,206722,206718,206716,206714,206713,206712,206708,206707,206704,206699,206694,206692,206691,206689,206688,206687,206677,206675,206673,206672,206671,206670,206664,206659,206658,206650,206647,206640,206633,206629,206628,206626,206625,206624,206610,206609,206604,206601,206597,206594,206591,206590,206589,206588,206587,206586,206585,206584,206582,206580,206574,206571,206570,206568,206566,206565,206561,206558,206553,206549,206542,206539,206538,206536,206531,206527,206523,206518,206515,206512,206509,206507,206506,206505,206502,206501,206500,206498,206496,206495,206493,206491,206490,206489,206484,206482,206480,206479,206477,206475,206470,206466,206465,206462,206461,206460,206457,206455,206453,206449,206448,206444,206443,206427,206426,206421,206419,206418,206417,206411,206410,206409,206408,206399,206398,206395,206392,206391,206388,206383,206381,206379,206374,206366,206359,206358,206353,206347,206346,206345,206341,206340,206338,206334,206332,206330,206329,206325,206324,206323,206322,206319,206318,206317,206314,206312,206306,206305,206304,206299,206298,206297,206296,206294,206292,206291,206289,206282,206281,206280,206268,206267,206257,206250,206248,206246,206244,206243,206241,206234,206230,206224,206223,206222,206219,206216,206211,206210,206207,206205,206203,206201,206200,206199,206197,206194,206191,206190,206188,206185,206181,206180,206176,206174,206167,206159,206157,206153,206149,206147,206140,206139,206138,206135,206134,206133,206129,206128,206127,206125,206119,206118,206116,206110,206107,206105,206103,206099,206092,206091,206090,206089,206086,206084,206082,206078,206074,206072,206071,206069,206067,206066,206062,206061,206053,206052,206051,206047,206045,206044,206040,206037,206036,206027,206026,206025,206023,206022,206020,206019,206017,206015,206012,206009,206004,205997,205995,205994,205993,205989,205988,205986,205985,205984,205982,205980,205973,205971,205970,205966,205965,205960,205959,205958,205954,205953,205949,205945,205941,205940,205938,205937,205935,205923,205922,205919,205914,205913,205911,205910,205909,205907,205905,205904,205903,205901,205900,205897,205896,205895,205893,205892,205888,205885,205879,205877,205872,205868,205866,205865,205864,205862,205861,205860,205858,205857,205856,205855,205854,205853,205852,205851,205849,205845,205843,205842,205841,205840,205838,205837,205836,205834,205832,205831,205830,205828,205827,205826,205824,205823,205819,205817,205816,205815,205814,205810,205803,205800,205796,205795,205793,205791,205782,205774,205773,205771,205760,205756,205754,205753,205752,205751,205750,205747,205745,205744,205743,205741,205736,205735,205730,205728,205727,205726,205724,205717,205714,205713,205712,205711,205710,205709,205703,205699,205698,205697,205692,205690,205689,205688,205686,205685,205683,205682,205680,205679,205678,205674,205673,205672,205671,205669,205668,205667,205666,205665,205662,205660,205657,205656,205652,205651,205648,205645,205643,205642,205639,205638,205630,205627,205626,205623,205622,205619,205618,205613,205611,205610,205607,205605,205604,205602,205601,205600,205599,205597,205595,205588,205586,205581,205579,205578,205573,205572,205567,205565,205564,205563,205562,205559,205558,205556,205555,205553,205551,205549,205548,205545,205544,205543,205541,205539,205538,205536,205535,205532,205526,205524,205522,205521,205520,205518,205515,205514,205513,205511,205510,205509,205508,205506,205505,205503,205502,205501,205498,205497,205495,205494,205493,205492,205490,205487,205485,205484,205481,205480,205479,205478,205477,205476,205472,205470,205469,205466,205465,205460,205459,205458,205457,205456,205452,205448,205440,205436,205435,205434,205432,205431,205430,205429,205428,205427,205423,205422,205418,205417,205413,205411,205410,205409,205408,205405,205403,205399,205398,205393,205392,205390,205386,205385,205384,205383,205380,205374,205373,205371,205370,205369,205368,205364,205363,205362,205361,205360,205359,205356,205348,205345,205344,205342,205341,205340,205336,205333,205331,205329,205326,205325,205324,205323,205322,205319,205317,205314,205313,205312,205275,205247,205219,205171,205091,205031,205030,204830,204764,204702,204625,204616,204609,204607,204599,204572,204364,204347,204303,204299,204296,204294,204293,204292,204291,204290,204288,204287,204286,204280,204279,204276,204275,204269,204267,204265,204262,204260,204259,204255,204252,204251,204250,204247,204246,204241,204239,204238,204237,204236,204235,204232,204231,204230,204226,204225,204224,204223,204222,204217,204213,204212,204209,204208,204206,204204,204203,204202,204201,204194,204191,204190,204187,204184,204182,204179,204174,204171,204170,204169,204165,204164,204162,204161,204158,204153,204152,204151,204150,204149,204148,204144,204142,204140,204139,204137,204134,204132,204125,204121,204116,204115,204113,204110,204109,204105,204102,204101,204099,204097,204095,204093,204090,204089,204084,204082,204080,204079,204078,204077,204076,204072,204067,204066,204063,204062,204061,204060,204059,204058,204057,204054,204051,204049,204048,204047,204045,204044,204042,204039,204038,204037,204034,204033,204030,204029,204028,204025,204023,204022,204021,204020,204018,204016,204013,204012,204010,204009,204002,203998,203993,203992,203988,203987,203986,203983,203974,203969,203968,203966,203964,203961,203959,203957,203955,203953,203952,203947,203946,203942,203940,203938,203937,203936,203935,203932,203929,203928,203926,203924,203923,203922,203920,203916,203915,203913,203910,203909,203908,203907,203906,203905,203900,203898,203897,203896,203894,203893,203890,203889,203888,203885,203884,203883,203881,203879,203876,203874,203872,203870,203868,203867,203864,203861,203860,203859,203857,203853,203852,203842,203839,203838,203837,203836,203834,203833,203831,203829,203825,203823,203817,203814,203811,203809,203807,203806,203805,203802,203801,203799,203798,203794,203793,203792,203787,203786,203784,203782,203780,203770,203762,203759,203756,203753,203752,203751,203750,203747,203745,203737,203735,203734,203733,203732,203731,203729,203716,203713,203712,203711,203710,203709,203704,203701,203698,203689,203683,203682,203676,203674,203673,203672,203671,203670,203668,203659,203657,203656,203654,203651,203650,203649,203646,203645,203641,203639,203636,203635,203634,203633,203626,203621,203620,203612,203610,203608,203606,203605,203603,203598,203596,203592,203585,203583,203582,203579,203577,203569,203556,203551,203549,203546,203545,203540,203537,203535,203534,203531,203530,203523,203522,203517,203510,203507,203501,203498,203493,203491,203490,203488,203487,203486,203483,203481,203474,203473,203469,203468,203466,203465,203464,203461,203459,203458,203456,203453,203449,203445,203443,203442,203441,203440,203435,203433,203432,203429,203428,203423,203422,203421,203420,203419,203418,203417,203416,203413,203412,203409,203405,203404,203403,203396,203394,203389,203388,203387,203379,203375,203374,203372,203369,203368,203364,203360,203358,203349,203347,203344,203343,203342,203338,203333,203331,203325,203319,203318,203316,203313,203310,203309,203308,203307,203305,203304,203303,203298,203296,203295,203293,203292,203291,203290,203288,203287,203281,203278,203273,203271,203267,203264,203262,203261,203260,203259,203254,203252,203250,203246,203244,203243,203242,203239,203238,203237,203235,203233,203231,203224,203218,203213,203203,203200,203192,203189,203182,203180,203179,203177,203176,203174,203168,203167,203166,203163,203159,203158,203153,203151,203150,203149,203148,203147,203146,203145,203144,203143,203141,203139,203137,203136,203135,203133,203132,203131,203130,203129,203128,203125,203122,203121,203113,203112,203106,203102,203101,203099,203094,203089,203086,203085,203083,203082,203079,203078,203075,203074,203073,203072,203071,203068,203067,203056,203046,203045,203044,203040,203038,203037,203036,203031,203030,203027,203026,203024,203023,203021,203018,203017,203016,203012,203011,203010,203008,203007,203005,203003,203000,202991,202990,202989,202986,202984,202983,202982,202979,202977,202975,202974,202973,202972,202971,202964,202963,202962,202961,202960,202957,202955,202954,202953,202952,202951,202950,202949,202948,202947,202946,202945,202943,202940,202939,202935,202934,202933,202930,202929,202928,202927,202926,202923,202921,202919,202918,202916,202913,202911,202910,202909,202905,202904,202901,202899,202898,202896,202894,202891,202890,202889,202888,202886,202884,202883,202882,202880,202876,202872,202870,202869,202866,202862,202858,202856,202855,202852,202851,202849,202847,202845,202838,202837,202834,202833,202831,202830,202827,202818,202809,202807,202800,202799,202797,202794,202790,202789,202788,202787,202786,202784,202783,202782,202781,202780,202779,202777,202776,202775,202772,202771,202768,202765,202764,202762,202760,202759,202755,202752,202750,202746,202744,202739,202734,202733,202732,202731,202730,202729,202726,202725,202723,202719,202718,202714,202713,202706,202705,202703,202698,202697,202695,202692,202688,202686,202685,202684,202683,202681,202678,202673,202669,202668,202665,202664,202663,202660,202659,202656,202652,202649,202645,202641,202639,202636,202635,202632,202630,202625,202622,202619,202618,202617,202611,202609,202601,202600,202599,202598,202597,202596,202595,202592,202586,202584,202583,202582,202581,202573,202572,202571,202570,202569,202568,202567,202566,202565,202560,202559,202556,202548,202541,202539,202537,202529,202527) and ireport_order_id > 0 and is_come = 1 ';
    	$page_size = 1000;
    	$page = 0 ;
    	$all_cout=0 ;
    	while(true){
    		$iss =0;
    		//推送回访
    		$send_array = array();
    	
    		$apisql = 'select ireport_order_id,order_id,admin_id,is_come,come_time,doctor_name from '.$this->common->table('order').$where." order by order_id asc  limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    	
    		$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql_ly.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count($data) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    	
    		if(count($data) == 0){break;}
    	
    		$order_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($order_id_str)){
    				$order_id_str =  $data_temp['order_id'];
    			}else{
    				$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    			}
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			//获取备注
    			$apisql = 'select mark_content,mark_type,admin_name,mark_time,order_id from '.$this->common->table('order_remark').' where order_id in('.$order_id_str.')  and mark_type != 3  order by mark_time desc';
    			$api_order_remark_data  = $this->common->getAll($apisql);
    	
    			$update_id_sql = '';
    			foreach ($data as $data_temp){
    				$order_id = $data_temp['order_id'];
    				$out_check =0 ;
    				foreach ($api_out_order_data as $out_order_data_temp){
    					if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    						$out_check =1;break;
    					}
    				}
    				if(empty($out_check)){
    					$send_data = array();
    					$send_data['operation_type'] = 'daozhen';//必填
    					//获取数据中心 订单ID
    					$send_data['order_id'] = $order_id;
    					$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
    					if(!empty($data_temp['is_come'])){
    						$send_data['visited'] =  1;
    						$send_data['visite_date'] =  $data_temp['come_time'];
    						$send_data['doctor'] =  $data_temp['doctor_name'];
    					}else{
    						$send_data['visited'] =  0;
    						$send_data['visite_date'] =  '';
    						$send_data['doctor'] =  '';
    					}
    					//过滤回访备注和 预约备注
    					$api_order_remark_str = '';
    					foreach ($api_order_remark_data as $api_order_remark_data_temp){
    						if(strcmp($api_order_remark_data_temp['order_id'],$data_temp['order_id']) == 0){
    							if(empty($api_order_remark_str)){
    								$api_order_remark_str = $api_order_remark_data_temp['mark_content']."[".$api_order_remark_data_temp['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_temp['mark_time'])."]";
    							}else{
    								$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_temp['mark_content']."[".$api_order_remark_data_temp['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_temp['mark_time'])."]";
    							}
    						}
    					}
    					$send_data['memo'] = $api_order_remark_str.'#'.$remark.'['.$_COOKIE['l_admin_name'].']';//预约备注' ;
    						
    					$update_temp_temp = "update  irpt_patient  set visited ='".$send_data['visited']."',visite_date='".$send_data['visite_date']."',doctor='".$send_data['doctor']."' where id = ".$send_data['ireport_order_id'].';';
    					if(empty($update_id_sql)){
    						$update_id_sql = $update_temp_temp;
    					}else{
    						$update_id_sql =  $update_id_sql.$update_temp_temp;
    					}
    					/***
    					 if(empty($send_data['visite_date'])){
    					$send_data['visite_date'] = '';
    					}
    					if(empty($send_data['doctor'])){
    					$send_data['doctor'] = '';
    					}
    	
    					//直接插入数据库
    					$info_temp = array();
    					$info_temp['visited'] =  $send_data['visited'];//是否到诊'
    					$info_temp['visite_date'] =  $send_data['visite_date'];//到诊日期'
    					$info_temp['doctor'] =  $send_data['doctor'];//医生名字
    					$info_temp['memo'] =$send_data['memo'];//导医备注'
    					$this->db->update($this->db->dbprefix . "irpt_patient", $info_temp, array('id' => $data_temp['ireport_order_id']));
    					$iss++;
    					***/
    	
    					//$send_array[] =$send_data;
    				}
    			}
    			$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql_ly_text.sql", "a+") or die("Unable to open file!");
    			fwrite($myfile,$update_id_sql);
    			fclose($myfile); 
    		}
    		if(count($send_array) > 0){
    			//var_dump($send_array);
    			//$this->sycn_order_data_to_ireport($send_array);
    		}
    		$page++;
    	}
    	exit;
    	
    }
	
	
	 //时间更新
    public function send_gonghai_order_time_to_ireport(){
		
    	$where = ' where  ireport_order_id > 0  ';// where admin_id = 8  '; 
		$where =" where  ireport_order_id > 0 and come_time between ".strtotime('2017-08-01 00:00:00')." and ".strtotime('2017-11-30 23:59:59');
    	$where =" where  ireport_order_id > 0 ";
    	
		$page_size = 50000;
    	$page = 0 ;
    	$all_cout=0 ;
    	while(true){
			$sql = '';
    		//推送回访
    		$send_array = array();
    		$iss =0 ;
    		$apisql = 'select order_id,ireport_order_id,order_time,order_addtime,come_time,is_come,doctor_name as doctor from '.$this->common->table('gonghai_order').$where."  order by order_id asc limit ".($page_size*$page).",".$page_size;
			$data = $this->common->getAll($apisql);
			//var_dump(count($data));exit;
    		$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_gonghai_order_sql.sql", "a+") or die("Unable to open file!"); 
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count($data) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		} 
    		fclose($myfile);
    		
    		if(count($data) == 0){
    			break;
    		} 

    		$order_id_str = ''; 
    		foreach ($data as $data_temp){
    			if(empty($order_id_str)){
					$order_id_str =  $data_temp['order_id'];
				}else{
					$order_id_str =  $order_id_str.','.$data_temp['order_id'];
				}
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql); 
    			foreach ($data as $data_temp){
    				$out_check =0 ;
					foreach ($api_out_order_data as $out_order_data_temp){
						if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
							$out_check =1;break;
						}
					}
					if(empty($out_check)){ 
						if($data_temp['is_come'] == 1){ 
						//gonghai_status=1,
							if(empty($sql)){
								$sql ="update irpt_patient set doctor = '".$data_temp['doctor']."',visite_date = '".$data_temp['come_time']."',visited = 1,date_day = '".$data_temp['order_time']."',add_time = '".$data_temp['order_addtime']."' where id = ".$data_temp['ireport_order_id'].';';
							}else{
								$sql = $sql."update irpt_patient set doctor = '".$data_temp['doctor']."',visite_date = '".$data_temp['come_time']."',visited = 1,date_day = '".$data_temp['order_time']."',add_time = '".$data_temp['order_addtime']."' where id = ".$data_temp['ireport_order_id'].';';
							}
						}else{
							if(empty($sql)){
								$sql ="update irpt_patient set doctor = '',visited = 0,visite_date = '0',date_day = '".$data_temp['order_time']."',add_time = '".$data_temp['order_addtime']."' where id = ".$data_temp['ireport_order_id'].';';
							}else{
								$sql = $sql."update irpt_patient set doctor = '',visited = 0,visite_date = '0',date_day = '".$data_temp['order_time']."',add_time = '".$data_temp['order_addtime']."' where id = ".$data_temp['ireport_order_id'].';';
							}
						}  
					}
    			} 
    		}  
			echo '<br/>'.$sql.'<br/>'; 
			echo '<br/>'.'当前页'.$page.'<br/>'; exit;
    		$page++;
    	}   
    }
	
    //更新预约日期
    public function send_order_yydate_to_ireport(){
    	$where = ' where ireport_order_id > 0 and order_id >= 505308 ';//s where admin_id = 8  ';
    	$page_size = 10000;
    	$page = 0 ;
    	$all_cout=0 ;
    	while(true){
    		$iss =0;
    		//推送回访
    		$send_array = array();
    
    		$apisql = 'select ireport_order_id,order_id,order_time from '.$this->common->table('order').$where." order by order_id asc  limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    		var_dump(json_encode($data)); 
    		
    		$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql_yydate.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count($data) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($order_id_str)){
    				$order_id_str =  $data_temp['order_id'];
    			}else{
    				$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    			}
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			 
    			foreach ($data as $data_temp){
    				$order_id = $data_temp['order_id'];
    				$out_check =0 ;
    				foreach ($api_out_order_data as $out_order_data_temp){
    					if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    						$out_check =1;break;
    					}
    				}
    				if(empty($out_check)){
    					$send_data = array();
    					$send_data['operation_type'] = 'editDate';//必填
    					//获取数据中心 订单ID
    					$send_data['order_id'] = $order_id;
    					$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
    					$send_data['date_day'] =  $data_temp['order_time'];//预约日期'
    					$send_array[] =$send_data;
    				}
    			}
    		}
    		if(count($send_array) > 0){
    			//var_dump(json_encode($send_array));exit;
    			$this->sycn_order_data_to_ireport($send_array); exit;
    		}
    		$page++;
    	}
    	exit;
    }
	
	  //更新到诊 公海
    public function send_order_yytype_to_ireport(){
    	$where = ' where ireport_order_id > 0 and order_id >= 505103 ';//s where admin_id = 8  ';
    	$page_size = 10000;
    	$page = 0 ;
    	$all_cout=0 ;
    	while(true){
    		$iss =0;
    		//推送回访
    		$send_array = array();
    
    		$apisql = 'select ireport_order_id,order_id,order_time,is_first from '.$this->common->table('order').$where." order by order_id asc  limit ".($page_size*$page).",".$page_size;
    		$data = $this->common->getAll($apisql);
    		var_dump(json_encode($data));
    		
    		$myfile = fopen(dirname(dirname(__file__))."/logs/yuyue_order_sql_yytype.sql", "a+") or die("Unable to open file!");
    		fwrite($myfile,'\r\n'.$apisql.'\r\n');
    		fwrite($myfile,'\r\n'.count($data).'\n');
    		if(count($data) > 0){
    			fwrite($myfile,'\r\n'.$data[0]['order_id'].'\n');
    		}
    		fclose($myfile);
    
    		if(count($data) == 0){break;}
    
    		$order_id_str = '';
    		foreach ($data as $data_temp){
    			if(empty($order_id_str)){
    				$order_id_str =  $data_temp['order_id'];
    			}else{
    				$order_id_str =  $order_id_str.','.$data_temp['order_id'];
    			}
    		}
    		if(!empty($order_id_str)){
    			//获取取消订单记录
    			$apisql = 'select * from '.$this->common->table('order_out')." where order_id in(".$order_id_str.")";
    			$api_out_order_data = $this->common->getAll($apisql);
    			 
    			foreach ($data as $data_temp){
    				$order_id = $data_temp['order_id'];
    				$out_check =0 ;
    				foreach ($api_out_order_data as $out_order_data_temp){
    					if(strcmp($out_order_data_temp['order_id'],$data_temp['order_id']) == 0){
    						$out_check =1;break;
    					}
    				}
    				if(empty($out_check)){
    					$send_data = array();
    					$send_data['operation_type'] = 'editType';//必填
    					//获取数据中心 订单ID
    					$send_data['order_id'] = $order_id;
    					$send_data['ireport_order_id'] = $data_temp['ireport_order_id'];
						if(empty($data_temp['is_first'])){
							$data_temp['is_first'] =1; 
						}else{
							$data_temp['is_first'] =0;
						}
						$send_data['yuyue_type'] = $data_temp['is_first']; 
						$send_data['gonghai_status'] =  0;//公海状态
    					$send_array[] =$send_data;
    				}
    			}
    		}
    		if(count($send_array) > 0){
    			//var_dump(json_encode($send_array));exit;
    			$this->sycn_order_data_to_ireport($send_array); exit;
    		}
    		$page++;
    	}
    	exit;
    }
	
    /****
     * 同步疾病数据到 数据中心
    */
    private function sycn_jb_data_to_ireport($parm){
    	if(count($parm) > 0){
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
    }
    
    //科室推送
    private function sycn_keshi_data_to_ireport($parm){
    	if(count($parm) > 0){
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
    }
    
    /****
     * 同步科室和疾病数据到 数据中心
    */
    private function sycn_keshi_and_jb_data_to_ireport($parm){
    	if(count($parm) > 0){
    		 
    	header("Content-type: text/html; charset=utf-8");
    
    	$info = array();
    	foreach ($parm as $temps){ 
    		$info_temp = array();
    		$info_temp['hospital_id'] =  $temps['hospital_id'];
    		$info_temp['disease_id_str'] =  $temps['disease_id_str'];
    		$info[] = $info_temp;
    	} 
    
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
    }
    
    /****
     * 同步用户数据到 数据中心
    */
    private function sycn_user_data_to_ireport($parm){
    	if(count($parm) > 0){
    	$info = array();
    	foreach ($parm as $temps){
    		$info_temp = array();
    		if(empty($temps['ireport_admin_id'])){
    			$temps['ireport_admin_id'] = '';
    		}
    		$info_temp['renai_id'] = $temps['admin_id'];
    		$info_temp['id'] = $temps['ireport_admin_id'];
    		$info_temp['name'] = $temps['admin_name'];
    		$info_temp['login_name'] =$temps['admin_username'];
    		$info_temp['login_passwd'] =$temps['admin_password'];
    		$info_temp['is_delete'] =$temps['is_pass'];
    		$info[] = $info_temp;
    	}
    	$info=  json_encode($info);
      
    	//var_dump($info_temp);exit;
    	//推送数据到数据中心系统
    	$url = $this->config->item('ireport_url_send_user');
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
    		$api_request['apply_type']='添加或者更新用户账户';
    		$api_request['apply_name']='用户账户';
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
    						$update['ireport_admin_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '绑定成功';
    						$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
    					}
    				}else if(count($add_update_ok_status) > 0){
    					foreach ($add_update_ok_status as $temps){
    						$update = array();
    						$update['ireport_admin_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '更新成功';
    						$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
    					}
    				}else{
    					if(!empty($add_empty_status)){
    						foreach ($add_empty_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在空值';
    							$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
    						}
    					}else if(!empty($add_exits_status)){
    						foreach ($add_exits_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在同样的账户数据';
    							$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
    						}
    					}else if(!empty($add_add_error_status)){
    						foreach ($add_add_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心添加失败';
    							$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
    						}
    					}else if(!empty($add_update_error_status)){
    						foreach ($add_update_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心更新失败';
    							$this->db->update($this->db->dbprefix . "admin", $update, array('admin_id' => $temps['renai_id']));
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
    		$api_request['apply_type']='添加或者更新用户账户';
    		$api_request['apply_name']='用户账户';
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
    	
    } 
    
    
    /****
     * 同步预约性质数据到 数据中心
    */
    private function sycn_order_type_data_to_ireport($parm){
    	if(count($parm) > 0){
    	header("Content-type: text/html; charset=utf-8");
    		
    	$info = array();
    	foreach ($parm as $temps){
    		$info_temp = array();
    		if(empty($temps['hos_id'])){
    			$temps['hos_id'] = '';
    		}
    		if(empty($temps['keshi_id'])){
    			$temps['keshi_id'] = '';
    		}
    		$info_temp['id'] =  $temps['ireport_order_type_id'];
    		$info_temp['type_name'] = $temps['type_name'];
    		$info_temp['hos_id'] = $temps['hos_id'];
    		$info_temp['keshi_id'] = $temps['keshi_id'];
    		$info_temp['type_desc'] = $temps['type_desc'];
    		$info_temp['type_order'] = $temps['type_order'];
    
    		$info_temp['renai_id'] = $temps['type_id'];
    		$info[] = $info_temp;
    	}
    		
    	$info=  json_encode($info);
    
    	//var_dump($info_temp);exit;
    	//推送数据到数据中心系统
    	$url = $this->config->item('ireport_url_send_order_type');
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
    		$api_request['apply_type']='添加或者更新预约性质';
    		$api_request['apply_name']='预约性质';
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
    						$update['ireport_order_type_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '绑定成功';
    						$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
    					}
    				}else if(count($add_update_ok_status) > 0){
    					foreach ($add_update_ok_status as $temps){
    						$update = array();
    						$update['ireport_order_type_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '更新成功';
    						$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
    					}
    				}else{
    					if(!empty($add_empty_status)){
    						foreach ($add_empty_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在空值';
    							$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_exits_status)){
    						foreach ($add_exits_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在同样的账户数据';
    							$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_add_error_status)){
    						foreach ($add_add_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心添加失败';
    							$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_update_error_status)){
    						foreach ($add_update_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心更新失败';
    							$this->db->update($this->db->dbprefix . "order_type", $update, array('type_id' => $temps['renai_id']));
    
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
    	} else{
    		$api_request = array();
    		$api_request['apply_id']= '';
    		$api_request['apply_type']='添加或者更新预约性质';
    		$api_request['apply_name']='预约性质';
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
    }
     
    /****
     * 同步来源途径 数据到 数据中心
    */
    private function sycn_order_from_data_to_ireport($parm){
    	if(count($parm) > 0){
    	header("Content-type: text/html; charset=utf-8");
    
    	$info = array();
    	foreach ($parm as $temps){
    		$info_temp = array();
    		if(empty($temps['hos_id'])){
    			$temps['hos_id'] = '';
    		}
    		if(empty($temps['keshi_id'])){
    			$temps['keshi_id'] = '';
    		}
    		if(empty($temps['ireport_jb_id'])){
    			$temps['ireport_jb_id'] = '';
    		}
    		$info_temp['id'] =  $temps['ireport_order_from_id'];
    		$info_temp['from_name'] = $temps['from_name'];
    		$info_temp['hos_id'] = $temps['hos_id'];
    		$info_temp['keshi_id'] = $temps['keshi_id'];
    		$info_temp['parent_id'] = $temps['parent_id'];
    		$info_temp['is_show'] = $temps['is_show'];
    		$info_temp['renai_id'] = $temps['from_id'];
    		$info[] = $info_temp;
    	}
    	$info=  json_encode($info);
        
    	//推送数据到数据中心系统
    	$url = $this->config->item('ireport_url_send_order_from');
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
    		$api_request['apply_type']='添加或者更新预约途径';
    		$api_request['apply_name']='预约途径';
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
    						$update['ireport_order_from_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '绑定成功';
    						$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
    					}
    				}else if(count($add_update_ok_status) > 0){
    					foreach ($add_update_ok_status as $temps){
    						$update = array();
    						$update['ireport_order_from_id'] = $temps['active_id'];
    						$update['ireport_msg'] = '更新成功';
    						$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
    					}
    				}else{
    					if(!empty($add_empty_status)){
    						foreach ($add_empty_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在空值';
    							$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_exits_status)){
    						foreach ($add_exits_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。存在同样的账户数据';
    							$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_add_error_status)){
    						foreach ($add_add_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心添加失败';
    							$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
    						}
    					}else if(!empty($add_update_error_status)){
    						foreach ($add_update_error_status as $temps){
    							$update = array();
    							$update['ireport_msg'] = '操作失败。数据中心更新失败';
    							$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
    
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
    		$api_request['apply_type']='添加或者更新预约途径';
    		$api_request['apply_name']='预约途径';
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
    }
 
    	
    /****
     * 同步订单数据到 数据中心
    */
    private function sycn_order_data_to_ireport($parm){
    	 if(count($parm) > 0){ 
			header("Content-type: text/html; charset=utf-8");
			$info = array();
			foreach ($parm as $temps){
				$info_temp = array();
				$info_temp['operation_type'] = $temps['operation_type'];
				if(empty($temps['ireport_order_id'])){
					$temps['ireport_order_id'] = '';
				}
				if($temps['operation_type'] == 'add' ){
					$info_temp['order_no'] =  $temps['order_no'];
					$info_temp['id_hospital'] = $temps['id_hospital'];
					$info_temp['id_consult'] = $temps['id_consult'];
					$info_temp['consult_name'] = $temps['consult_name'];
					$info_temp['date_day'] = $temps['date_day'];
					$info_temp['disease'] = $temps['disease'];
					$info_temp['memo'] = $temps['memo'];
					$info_temp['add_time'] = $temps['add_time'];
					$info_temp['booking_period'] = '';
					$info_temp['dialogue_record'] = base64_encode($temps['dialogue_record']);
					$info_temp['source_url'] = $temps['source_url'];
					$info_temp['last_menstrual_period'] = $temps['last_menstrual_period'];
					$info_temp['patient_name'] = $temps['patient_name'];
					$info_temp['age'] = $temps['age'];
					$info_temp['area'] = $temps['area'];
					$info_temp['phone'] = $temps['phone'];
					$info_temp['email'] = $temps['email'];
					$info_temp['sex'] = $temps['sex'];
					$info_temp['visite_times']=$temps['visite_times'];
					$info_temp['visite_datetime']=$temps['visite_datetime'];
					$info_temp['visite_bycheck_desc']=$temps['visite_bycheck_desc'];
					$info_temp['visite_bycheck']= $temps['visite_bycheck'];
					$info_temp['track_next_time']=$temps['track_next_time'];
					$info_temp['order_type']=$temps['order_type'];
					$info_temp['order_from']=$temps['order_from'];
					$info_temp['order_from_two']=$temps['order_from_two'];
					$info_temp['from_value']=$temps['from_value'];
					$info_temp['gonghai_status'] = 0;
					$info_temp['yuyue_type'] = $temps['yuyue_type'];
				}else if($temps['operation_type'] == 'edit' ){
					$info_temp['id'] =  $temps['ireport_order_id'];
					$info_temp['order_no'] =  $temps['order_no'];
					$info_temp['id_hospital'] = $temps['id_hospital'];
					$info_temp['id_consult'] = $temps['id_consult'];
					$info_temp['consult_name'] = $temps['consult_name'];
					$info_temp['date_day'] = $temps['date_day'];
					$info_temp['disease'] = $temps['disease'];
					$info_temp['memo'] = $temps['memo'];
					$info_temp['add_time'] = $temps['add_time'];
					$info_temp['booking_period'] = '';
					$info_temp['dialogue_record'] = base64_encode($temps['dialogue_record']);
					$info_temp['source_url'] = $temps['source_url'];
					$info_temp['last_menstrual_period'] = $temps['last_menstrual_period'];
					$info_temp['patient_name'] = $temps['patient_name'];
					$info_temp['age'] = $temps['age'];
					$info_temp['area'] = $temps['area'];
					$info_temp['phone'] = $temps['phone'];
					$info_temp['email'] = $temps['email'];
					$info_temp['sex'] = $temps['sex'];
					$info_temp['order_type']=$temps['order_type'];
					$info_temp['order_from']=$temps['order_from'];
					$info_temp['order_from_two']=$temps['order_from_two'];
					$info_temp['from_value']=$temps['from_value'];
					$info_temp['gonghai_status'] = 0;
					$info_temp['yuyue_type'] = $temps['yuyue_type'];
				}else if($temps['operation_type'] == 'editDate' ){
					$info_temp['id'] =  $temps['ireport_order_id'];  
					$info_temp['date_day'] = $temps['date_day']; 
				}else if($temps['operation_type'] == 'editType' ){
					$info_temp['id'] =  $temps['ireport_order_id'];  
					$info_temp['gonghai_status'] = $temps['gonghai_status']; 
					$info_temp['yuyue_type'] = $temps['yuyue_type']; 
				}else if($temps['operation_type'] == 'huifang' ){
					$info_temp['id'] =  $temps['ireport_order_id'];
					$info_temp['visite_times']=$temps['visite_times'];
					$info_temp['visite_datetime']=$temps['visite_datetime'];
					$info_temp['visite_bycheck_desc']=$temps['visite_bycheck_desc'];
					$info_temp['visite_bycheck']= $temps['visite_bycheck'];
					$info_temp['track_next_time']=$temps['track_next_time'];
				}else if($temps['operation_type'] == 'daozhen' ){
					$info_temp['id'] =  $temps['ireport_order_id'];
					$info_temp['visited'] =  $temps['visited'];
					$info_temp['visite_date'] =  $temps['visite_date'];
					$info_temp['doctor'] =  $temps['doctor'];
					$info_temp['memo'] =$temps['memo'];
				}else if($temps['operation_type'] == 'quxiao_or_huifu' ){
					$info_temp['id'] =  $temps['ireport_order_id'];
					if(empty( $temps['order_out'])){
						$temps['order_out'] = 1;
					}
					$info_temp['order_out'] =  $temps['order_out'];
				}
				$info_temp['renai_id'] = $temps['order_id'];
				$info[] = $info_temp;
			}
			$info=  json_encode($info);
		
			$url = $this->config->item('ireport_url_add_order');
			if(!empty($url)){
				require_once(BASEPATH."/core/Decryption.php");
		
				$decryption = new Decryption();
				$mu_str= $this->config->item('renai_mu_word');
				$gong_str= $this->config->item('renai_mu_number');
				$key_user = $decryption->createRandKey($mu_str,$gong_str);
				$str = $this->config->item('ireport_name').date("Y-m-d H",time());
				$encryption_validate = $decryption->encryption($str,$key_user);
				$encryption_data = $decryption->encryption($info,$key_user);
					
				$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);
		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				curl_close($ch);
				var_dump($output);//exit;
		
				$api_request = array();
				$api_request['apply_id']= '';
				$api_request['apply_type']='订单添加或者更新';
				$api_request['apply_name']='订单';
				$api_request['apply_time']=date("Y-m-d H:i:s",time());
				$api_request['apply_address']=$url;
				$api_request['apply_data']=$info;
					
				$output_json = json_decode($output,true);
				if(!empty($output_json)){
					$output_data = json_decode($output_json['msg'],true);;
					$add_empty_status =$output_data['add_empty_status'];
					$add_exits_status  =$output_data['add_exits_status'];
					$add_add_ok_status  =$output_data['add_add_ok_status'];
					$add_add_error_status  =$output_data['add_add_error_status'];
					$update_ok_status  =$output_data['update_ok_status'];
					$update_error_status  =$output_data['update_error_status'];
		
					if(strcmp($output_json['code'],'info_empty') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'decryption_error') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'ok') ==0 ){
						$api_request['apply_status']='1';
						if(count($add_add_ok_status) > 0){
							foreach ($add_add_ok_status as $temps){
								$update = array();
								$update['ireport_order_id'] = $temps['active_id'];
								if($temps['operation_type'] == 'add' ){
									$update['ireport_msg'] = '绑定成功';
								}else if($temps['operation_type'] == 'edit' ){
									$update['ireport_msg'] = '更新成功';
								}else if($temps['operation_type'] == 'huifang' ){
									$update['ireport_msg'] = '回访成功';
								}else if($temps['operation_type'] == 'daozhen' ){
									$update['ireport_msg'] = '到诊完成';
								}
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
		
							}
						}else if(count($update_ok_status) > 0){
							foreach ($update_ok_status as $temps){
								$update = array();
								$update['ireport_order_id'] = $temps['active_id'];
								if($$temps['operation_type'] == 'add' ){
									$update['ireport_msg'] = '更新成功';
								}else if($temps['operation_type'] == 'edit' ){
									$update['ireport_msg'] = '修改成功';
								}else if($temps['operation_type'] == 'huifang' ){
									$update['ireport_msg'] = '回访成功';
								}else if($temps['operation_type'] == 'daozhen' ){
									$update['ireport_msg'] = '到诊完成';
								}
								$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
		
							}
						}else{
							if(!empty($add_empty_status)){
								foreach ($add_empty_status as $temps){
									$update = array();
									$update['ireport_msg'] = '存在空值';
									$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
		
								}
							}else if(!empty($add_exits_status)){
								foreach ($add_exits_status as $temps){
									$update = array();
									$update['ireport_msg'] = '存在相同的数据';
									$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
		
								}
							}else if(!empty($add_add_error_status)){
								foreach ($add_add_error_status as $temps){
									$update = array();
									$update['ireport_msg'] = '添加失败';
									$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								}
							}else if(!empty($update_error_status)){
								foreach ($update_error_status as $temps){
									$update = array();
									$update['ireport_msg'] = '更新失败';
									$this->db->update($this->db->dbprefix . "order", $update, array('order_id' => $temps['renai_id']));
								}
							}
						}
					}else{
						$output_json['msg'] = json_encode($output);
						$api_request['apply_status']='1';
					}
				}else{
					$output_json['msg'] = json_encode($output);
					$api_request['apply_status']='1';
				}
				$api_request['response_msg']=$output_json['msg'];
				$api_request['response_code']=$output_json['code'];
				$api_request['response_data']=$output;
				$this->db->insert($this->common->table("api_request_log"), $api_request);
			}  else{
				$api_request = array();
				$api_request['apply_id']= '';
				$api_request['apply_type']='订单添加或者更新';
				$api_request['apply_name']='订单';
				$api_request['apply_name']='';
				$api_request['apply_time']=date("Y-m-d H:i:s",time());
				$api_request['apply_address']=$url;
				$api_request['apply_data']=$info;
				$api_request['apply_status']=2;
				$api_request['response_msg']='接口异常';
				$api_request['response_code']='';
				$api_request['response_data']='';
				$this->db->insert($this->common->table("api_request_log"), $api_request);
			}
	   }
		
    }
    
    
    function index(){
        
       $a=$_COOKIE['l_hos_id'];
        var_dump($_COOKIE['l_hos_id']);
        $hos_id=explode(",", $a);
        var_dump($hos_id);
        if(in_array(3, $hos_id)||  in_array(15, $hos_id)){
            
            echo 'success';
        }else{
            
            echo 'failed';
        }
    }
    
    public function no(){
    	$one =0;
    	$sql = "SELECT count(order_id) as order_id FROM hui_gonghai_log where `action_type`  = '从公海捞取' ";
    	$count = $this->common->getOne($sql);
    	while($one < 6){
    		 
    		$sql = "SELECT order_id FROM hui_gonghai_log where `action_type`  = '从公海捞取' order by `action_time`  desc limit ".($one*1000).",1000";
    		$data = $this->common->getAll($sql);
    		foreach ($data as $data_temp){
    			$sql = "SELECT order_id FROM hui_order where order_id = ".$data_temp['order_id'];
    			$order_data = $this->common->getAll($sql);
    			if(count($order_data) == 0){
    				$sql = "SELECT order_id FROM hui_gonghai_order where order_id = ".$data_temp['order_id'];
    				$order_data = $this->common->getAll($sql);
    				if(count($order_data) == 0){
    					echo $data_temp['order_id'].'<br/>';
    				}
    			}
    		}
    		$one++;
    	}
    	 
    	exit;
    }
    
    
    
}
