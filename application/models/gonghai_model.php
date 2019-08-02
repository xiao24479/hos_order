<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页model
class Gonghai_model extends CI_Model
{
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}

		 //系统日志信息
        function order_log($hos_id,$keshi_id,$jb_parent_id,$jb_id,$from_parent_id,$from_id,$type_id){
            $hospital = $this->hospital_order_list();
			$keshi = $this->keshi_order_list();
			$keshi_arr = array();
			//生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
			foreach($keshi as $val)
			{
				$keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
				$keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
				$keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
				$keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
				$keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
				$keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
			}

			$from_list = $this->from_order_list(-1);
			$par_from_arr = array();
			$from_arr = array();//生成二维数组，把每条记录作为数组存进$from_arr
			foreach($from_list as $val)
			{
				if($val['parent_id'] == 0)
				{
					$par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
					$par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
					$par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
				}
				else
				{
					$from_arr[$val['from_id']]['from_id'] = $val['from_id'];
					$from_arr[$val['from_id']]['from_name'] = $val['from_name'];
					$from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
				}
			}
			$from_list = $par_from_arr;
			//获取预约类别表Order_type中的所有数据
			$type_list = $this->type_order_list();
			$type_arr = array();
			foreach($type_list as $val)
			{
				$type_arr[$val['type_id']]['type_id'] = $val['type_id'];
				$type_arr[$val['type_id']]['type_name'] = $val['type_name'];
				$type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
				$type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
				$type_arr[$val['type_id']]['type_order'] = $val['type_order'];
			}
			$type_list = $type_arr;
			$jibing = read_static_cache('jibing_list');
			$jibing_list = array();
			$jibing_parent = array();
			if(!empty($jibing))
			{
				foreach($jibing as $val)
				{
					$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
					if($val['jb_level'] == 2)
					{
						$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
					}
				}
			}

			foreach($hospital as $t){
			if($t['hos_id']==$hos_id){
				$data['hos_name']=$t['hos_name'];//保存医院姓名
			}
			}
			$data['keshi_name']=$keshi_arr[$keshi_id]['keshi_name'];
			$data['jb_parent_name']=$jibing_list[$jb_parent_id]['jb_name'];
			$data['jb_name']=$jibing_list[$jb_id]['jb_name'];
			$data['from_parent_name']=$from_list[$from_parent_id]['from_name'];
			$data['from_name']=$from_arr[$from_id]['from_name'];
			$data['type_name']=$from_list[$type_id]['type_name'];
			return $data;

        }

		 //系统修改记录收集方法
        function order_update_log($order_id,$update_info=array()){

            $info=$this->order_info($order_id);
            $id_name=array();
            $id_name=$this->order_log($update_info['hos_id'],$update_info['keshi_id'],$update_info['jb_parent_id'],$update_info['jb_id'],$update_info['from_parent_id'],$update_info['from_id'],$update_info['type_id']);
            $message="";
            if($info['from_parent_id']!=$update_info['from_parent_id']){
                $message.="大途径修改为：".$id_name['from_parent_name']."；";
            };
            if($info['from_id']!=$update_info['from_id']){
                $message.="小途径修改为：".$id_name['from_name']."；";
            };
            if(!empty($update_info['pat_name']) && $info['pat_name']!=$update_info['pat_name']){
                $message.="患者姓名修改为：".$update_info['pat_name']."；";
            };
            if(!empty($update_info['phone']) && $info['pat_phone']!=$update_info['phone']){
                $message.="患者电话修改为：".$update_info['phone']."；";
            };
            if($info['pat_sex']!=$update_info['pat_sex']){
                if(intval($update_info['pat_sex'])==1){
                   $sex='男';
                }elseif(intval($update_info['pat_sex'])==2){
                    $sex='女';
                }
                $message.="性别修改为：".$sex."；";
            };
            if($info['pat_age']!=$update_info['pat_age']){
                $message.="年龄修改为：".$update_info['pat_age']."；";
            };
            if($info['is_first']!=$update_info['is_first']){
                if($update_info['is_first']==1){
                   $update_info['is_first']="初诊";
                }elseif($update_info['is_first']==0){
                    $update_info['is_first']="复诊";
                }
                $message.="初/复诊修改为：".$update_info['is_first']."；";
            };
            if($info['hos_id']!=$update_info['hos_id']){
                $message.="医院修改为：".$id_name['hos_name']."；";
            };
             if($info['keshi_id']!=$update_info['keshi_id']){
                $message.="科室修改为：".$id_name['keshi_name']."；";
            };
             if($info['jb_parent_id']!=$update_info['jb_parent_id']){
                $message.="大病种修改为：".$id_name['jb_parent_name']."；";
            };
            if($info['jb_id']!=$update_info['jb_id']){
                $message.="小病种修改为：".$id_name['jb_name']."；";
            };
             if($info['type_id']!=$update_info['type_id']){
                $message.="性质修改为：".$id_name['type_name']."；";
            };

            if(!empty($update_info['admin_name']) && $info['admin_name']!=$update_info['admin_name']){
                $message.="咨询修改为：".$update_info['admin_name']."；";
            };

            if(!empty($update_info['order_time'])){
            	if(strpos($update_info['order_time'],'-') > 0){
            		if($info['order_time']!= strtotime($update_info['order_time'])){
            			$message.="预到时间修改为：".$update_info['order_time']."；";
            		};
            	}else{
            		if($info['order_time']!= $update_info['order_time']){
            			$message.="预到时间修改为：".date("Y-m-d H:i:s",$update_info['order_time'])."；";
            		};
            	}
            }
            if(!empty($update_info['come_time'])){
	            if(strpos($update_info['come_time'],'-') > 0){
	            	if($info['come_time']!= strtotime($update_info['come_time'])){
	            		$message.="到诊时间修改为：".$update_info['come_time']."；";
	            	};
	            }else{
	            	if($info['come_time']!= $update_info['come_time']){
	            		$message.="到诊时间修改为：".date("Y-m-d H:i:s",$update_info['come_time'])."；";
	            	};
	            }
            }
            return $message;
        }

        //公海列表显示分别从4个表中提取相应的信息通过左连接的方式
         function gonghai_list($where, $first = 0, $size = 20, $orderby)
	{
//
//		$sql = "SELECT o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id,o.first_timeout,o.gonghai_type, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
//				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin, o.from_parent_id, o.from_id, o.is_come,
//				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
//				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
//				FROM " . $this->common->table('gonghai_order') . " o
//				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
//				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
//				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
//				WHERE $where  AND o.gonghai_type='公海' " .
//				$orderby . "
//				LIMIT $first, $size";
                $sql = "SELECT o.ireport_order_id,og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id,o.first_timeout,o.gonghai_type, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('gonghai_order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
				WHERE $where  " .
				$orderby . "
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();
                //重新把获取到的信息存进二维数组$arr中
		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
			$arr[$val['order_id']]['ireport_order_id'] = $val['ireport_order_id'];
			$arr[$val['order_id']]['is_to_order'] = $val['is_to_order'];
			$arr[$val['order_id']]['is_to_gonghai'] = $val['is_to_gonghai'];
			$arr[$val['order_id']]['order_no'] = $val['order_no'];
			$arr[$val['order_id']]['is_first'] = $val['is_first'];
			$arr[$val['order_id']]['pat_name'] = $val['pat_name'];
			$arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
			$arr[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
			if($val['pat_sex'] == 1)
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('man');
			}
			else
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('woman');
			}

			$arr[$val['order_id']]['pat_age'] = $val['pat_age'];
			$arr[$val['order_id']]['pat_province'] = $val['pat_province'];
			$arr[$val['order_id']]['pat_city'] = $val['pat_city'];
			$arr[$val['order_id']]['pat_area'] = $val['pat_area'];
			$arr[$val['order_id']]['pat_qq'] = $val['pat_qq'];
			$arr[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
			$arr[$val['order_id']]['pat_blacklist'] = $val['pat_blacklist'];
			$arr[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
			if(!empty($val['order_time']))
			{
				$arr[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
			}
			else
			{
				$arr[$val['order_id']]['order_time'] = $val['order_null_time'];
			}
			$arr[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];
			$arr[$val['order_id']]['come_time'] = $val['come_time'];
			$arr[$val['order_id']]['doctor_time'] = $val['doctor_time'];
			$arr[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];
			$arr[$val['order_id']]['from_id'] = $val['from_id'];
			$arr[$val['order_id']]['hos_id'] = $val['hos_id'];
			$arr[$val['order_id']]['keshi_id'] = $val['keshi_id'];
			$arr[$val['order_id']]['data_time'] = $val['data_time'];
			$arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
			$arr[$val['order_id']]['jb_id'] = $val['jb_id'];
			$arr[$val['order_id']]['order_type'] = $val['order_type'];
			$arr[$val['order_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['order_id']]['pat_id'] = $val['pat_id'];
			$arr[$val['order_id']]['doctor_name'] = $val['doctor_name'];
			$arr[$val['order_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['order_id']]['is_come'] = $val['is_come'];

			$arr[$val['order_id']]['from_value'] = $val['from_value'];
			$arr[$val['order_id']]['zampo'] = $val['zampo'];
			$arr[$val['order_id']]['z_name'] = $val['z_name'];
                        //把相关字段添加进去
                        $arr[$val['order_id']]['gonghai_type'] = $val['gonghai_type'];
                        $arr[$val['order_id']]['first_timeout']=$val['first_timeout'];
		}

		return $arr;
	}
        //个人预约列表
        function person_gonghai_list($where, $first = 0, $size = 20, $orderby)
	{
//
//		$sql = "SELECT o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id,o.first_timeout,o.gonghai_type, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
//				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin, o.from_parent_id, o.from_id, o.is_come,
//				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
//				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
//				FROM " . $this->common->table('gonghai_order') . " o
//				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
//				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
//				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
//				WHERE $where  AND o.gonghai_type='公海' " .
//				$orderby . "
//				LIMIT $first, $size";
                $sql = "SELECT o.ireport_order_id,o.ireport_msg,og.order_id,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
				WHERE $where  " .
				$orderby . "
				LIMIT $first, $size";

		$row = $this->common->getAll($sql);
		$arr = array();

		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
			$arr[$val['order_id']]['ireport_order_id'] = $val['ireport_order_id'];
			$arr[$val['order_id']]['ireport_msg'] = $val['ireport_msg'];
			$arr[$val['order_id']]['is_to_order'] = $val['is_to_order'];
			$arr[$val['order_id']]['is_to_gonghai'] = $val['is_to_gonghai'];
			$arr[$val['order_id']]['order_no'] = $val['order_no'];
			$arr[$val['order_id']]['is_first'] = $val['is_first'];
			$arr[$val['order_id']]['pat_name'] = $val['pat_name'];
			$arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
			$arr[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
			if($val['pat_sex'] == 1)
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('man');
			}
			else
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('woman');
			}

			$arr[$val['order_id']]['pat_age'] = $val['pat_age'];
			$arr[$val['order_id']]['pat_province'] = $val['pat_province'];
			$arr[$val['order_id']]['pat_city'] = $val['pat_city'];
			$arr[$val['order_id']]['pat_area'] = $val['pat_area'];
			$arr[$val['order_id']]['pat_qq'] = $val['pat_qq'];
			$arr[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
			$arr[$val['order_id']]['pat_blacklist'] = $val['pat_blacklist'];
			$arr[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
			if(!empty($val['order_time']))
			{
				$arr[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
			}
			else
			{
				$arr[$val['order_id']]['order_time'] = $val['order_null_time'];
			}
			$arr[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];
			$arr[$val['order_id']]['come_time'] = $val['come_time'];
			$arr[$val['order_id']]['doctor_time'] = $val['doctor_time'];
			$arr[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];
			$arr[$val['order_id']]['from_id'] = $val['from_id'];
			$arr[$val['order_id']]['hos_id'] = $val['hos_id'];
			$arr[$val['order_id']]['keshi_id'] = $val['keshi_id'];
			$arr[$val['order_id']]['data_time'] = $val['data_time'];
			$arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
			$arr[$val['order_id']]['jb_id'] = $val['jb_id'];
			$arr[$val['order_id']]['order_type'] = $val['order_type'];
			$arr[$val['order_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['order_id']]['pat_id'] = $val['pat_id'];
			$arr[$val['order_id']]['doctor_name'] = $val['doctor_name'];
			$arr[$val['order_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['order_id']]['is_come'] = $val['is_come'];
			$arr[$val['order_id']]['from_value'] = $val['from_value'];
			$arr[$val['order_id']]['zampo'] = $val['zampo'];
			$arr[$val['order_id']]['z_name'] = $val['z_name'];


		}

		return $arr;
	}

        function order_remark($order_id)
	{
		$sql = "SELECT r.*
				FROM " . $this->common->table('order_remark') . " r
				WHERE r.order_id = $order_id
				ORDER BY r.mark_id DESC";
		$row = $this->common->getAll($sql);
//		print_r($row);
		return $row;
	}

        /* $type 为 1时表示获取全部咨询员导医的信息 */
		function asker_list($type = 0)
		{
			$where = 1;

			if($type == 0)
			{
				if(!empty($_COOKIE["l_hos_id"]))
				{
					$where .= " AND p.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
				}

				if(!empty($_COOKIE["l_keshi_id"]))
				{
					$where .= " AND p.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}

			$sql = "SELECT Distinct a.admin_id, a.admin_name
					FROM " . $this->common->table('admin') . " a
					LEFT JOIN " . $this->common->table('rank') . " r ON r.rank_id = A.rank_id
					LEFT JOIN " . $this->common->table('rank_power') . " p ON r.rank_id = p.rank_id
					WHERE $where AND (r.rank_type = 2 OR r.rank_type = 3) ORDER BY CONVERT(a.admin_name USING gbk) asc";
			$arr = $this->common->getAll($sql);
			return $arr;
		}

        //根据登录用户的hos_id查询相关的医院列表
        function hospital_order_list()
	{
		$where = 1;

		if(!empty($_COOKIE["l_hos_id"]))
		{
			$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
		}
		$sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE $where and ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc";
		return $this->common->getAll($sql);
	}
        //相关公海预约操作日志
        function gonghai_log_list($order_id=0){

            $sql="select * from ".$this->common->table('gonghai_log')." where order_id=".$order_id." order by log_id desc";;

            $arr=$this->common->getAll($sql);
            return $arr;
        }

		function keshi_order_list($hos_id = 0)
		{
			$where = 1;

			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}
			if(!empty($hos_id))
			{
				$where .= " AND hos_id = $hos_id";
			}

			$sql = "SELECT *
					FROM " . $this->common->table('keshi') . "
			 		WHERE $where ORDER BY keshi_order ASC, hos_id ASC";
			$arr = $this->common->getAll($sql);
	               //重新按照科室ID存储进$keshi数组
			$keshi = array();
			foreach($arr as $val)
			{
				$keshi[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
				$keshi[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
				$keshi[$val['keshi_id']]['parent_id'] = $val['parent_id'];
				$keshi[$val['keshi_id']]['hos_id'] = $val['hos_id'];
				$keshi[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
				$keshi[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
			}
			return $arr;
		}
		//根据用户的hos_id和keshi_id从order_type表中提取相关的预约性质
		function type_order_list()
		{
			$where = 1;

			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= " AND hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
			}

			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
			}

			$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('order_type') . " WHERE $where ORDER BY type_order ASC, type_id DESC");
			return $arr;
		}
        //根据用户的hos_id和keshi_id从order_from中提取相关的数据
        function from_order_list($parent_id = 0,$tag = '')
		{
			$where = 1;

			if(!empty($_COOKIE["l_hos_id"]))
			{
				$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
			}

			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= " AND (keshi_id IN (" . $_COOKIE["l_keshi_id"] . ") OR keshi_id = 0)";
			}

			if($parent_id == -1)
			{
				$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('order_from') . " WHERE $where ORDER BY from_order ASC, from_id DESC");
			}
			else
			{
				if($tag !== -1){
				$where .= " AND is_show = 0";
				}
				$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('order_from') . " WHERE $where AND parent_id = $parent_id ORDER BY from_order ASC, from_id DESC");
			}
			return $arr;
		}
		//根据岗位来调取相关的权限级别rank_type
        function rank_type()
	{
		$rank_id = $_COOKIE['l_rank_id'];
		$rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
		return $rank[$rank_id]['rank_type'];
	}
        //获取地区id和地区名字
		function area()
		{
			$row = $this->common->getAll("SELECT region_id, region_name FROM " . $this->common->table('region') . " ORDER BY region_id DESC");
			$arr = array();
			foreach($row as $val)
			{
				$arr[$val['region_id']]['region_id'] = $val['region_id'];
				$arr[$val['region_id']]['region_name'] = $val['region_name'];
			}
			return $arr;
		}
        //统计相关的预约总数，实到人数和就诊人数
        function order_count($where)
	{
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
		        FROM " . $this->common->table('gonghai_order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where";
		$row = $this->common->getRow($sql);
		return $row;
	}
        //统计相关的预约总数，实到人数和就诊人数
        function order_count_new($where)
	{
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where";
		$row = $this->common->getRow($sql);
		return $row;
	}
        function order_list($where, $first = 0, $size = 20, $orderby)
	{

		$sql = "SELECT og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
				WHERE $where " .
				$orderby . "
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
			$arr[$val['order_id']]['is_to_order'] = $val['is_to_order'];
			$arr[$val['order_id']]['is_to_gonghai'] = $val['is_to_gonghai'];
			$arr[$val['order_id']]['order_no'] = $val['order_no'];
			$arr[$val['order_id']]['is_first'] = $val['is_first'];
			$arr[$val['order_id']]['pat_name'] = $val['pat_name'];
			$arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
			$arr[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
			if($val['pat_sex'] == 1)
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('man');
			}
			else
			{
				$arr[$val['order_id']]['pat_sex'] = $this->lang->line('woman');
			}

			$arr[$val['order_id']]['pat_age'] = $val['pat_age'];
			$arr[$val['order_id']]['pat_province'] = $val['pat_province'];
			$arr[$val['order_id']]['pat_city'] = $val['pat_city'];
			$arr[$val['order_id']]['pat_area'] = $val['pat_area'];
			$arr[$val['order_id']]['pat_qq'] = $val['pat_qq'];
			$arr[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
			$arr[$val['order_id']]['pat_blacklist'] = $val['pat_blacklist'];
			$arr[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
			if(!empty($val['order_time']))
			{
				$arr[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
			}
			else
			{
				$arr[$val['order_id']]['order_time'] = $val['order_null_time'];
			}
			$arr[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];
			$arr[$val['order_id']]['come_time'] = $val['come_time'];
			$arr[$val['order_id']]['doctor_time'] = $val['doctor_time'];
			$arr[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];
			$arr[$val['order_id']]['from_id'] = $val['from_id'];
			$arr[$val['order_id']]['hos_id'] = $val['hos_id'];
			$arr[$val['order_id']]['keshi_id'] = $val['keshi_id'];
			$arr[$val['order_id']]['data_time'] = $val['data_time'];
			$arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
			$arr[$val['order_id']]['jb_id'] = $val['jb_id'];
			$arr[$val['order_id']]['order_type'] = $val['order_type'];
			$arr[$val['order_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['order_id']]['pat_id'] = $val['pat_id'];
			$arr[$val['order_id']]['doctor_name'] = $val['doctor_name'];
			$arr[$val['order_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['order_id']]['is_come'] = $val['is_come'];
			$arr[$val['order_id']]['from_value'] = $val['from_value'];
			$arr[$val['order_id']]['zampo'] = $val['zampo'];
			$arr[$val['order_id']]['z_name'] = $val['z_name'];

		}

		return $arr;
	}

        public function timeout_gonghai($order_id){
                    $o_id=isset($order_id)?$intval($order_id):0;

                        $query2="update hui_gonghai_order set gonghai_type='gonghai' where order_id=".$o_id;
                        $this->db->query($query2);
                        if($this->db->affected_rows>0){
                          return true;

                        }else{

                           return false;

                        }

//                    $sql="INSERT INTO `hui_gonghai_order`(`order_id`, `order_no`, `is_first`, `admin_id`, `admin_name`, `pat_id`, `from_parent_id`, `from_id`, `from_value`, `hos_id`, `keshi_id`,
//                        `type_id`, `jb_parent_id`, `jb_id`, `order_addtime`, `order_time`, `order_null_time`, `doctor_time`, `doctor_id`, `doctor_name`, `come_time`, `order_time_duan`, `duan_confirm`, `is_come`)
//                        select * from hui_order where order_id=0";
//                  $sql2="update hui_gonghai_order set first_timeout='333',gonghai_type='gonghai' where order_id=0";
//                  $sql3="delete from hui_order where order_id=14";
//

                }

		//把相关数据从公海中提取到预约表中 传入相关的$order_id和$admin_name
        public function update_order_name($order_id=0,$admin_name='',$admin_id=''){
        	$bol  = false;
            $orderid=isset($order_id)?intval($order_id):0;
            if(!empty($orderid)){
            	//更新公海中的相关数据
            	if(empty($admin_name)){
            		$admin_name = '';
            	}
            	if(empty($admin_id)){
            		$admin_id = '';
            	}
            	if($this->db->query("update ".$this->common->table('gonghai_order')." set  admin_id=".$admin_id.",admin_name='".$admin_name."' where order_id=".$order_id)){
            		//插入更新记录
            		if(empty($_COOKIE['l_admin_name'])){
            			$_COOKIE['l_admin_name'] = '';
            		}
            		if($this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$orderid,'action_type'=>'从公海捞取','action_name'=>$admin_name,'action_id'=>$admin_id,'action_time'=>time(),'is_come'=>1))){
            			$sql = "SELECT gonghai_id,order_time FROM " . $this->common->table('gonghai_order')." where order_id=".$order_id.' and hos_id =1 and keshi_id in(1)';
            			$hos_id_row = $this->common->getAll($sql);
            			if(count($hos_id_row) > 0 && !empty($hos_id_row[0]['order_time'])){//属于仁爱妇科
            				$order_reanai_sum_data  = $this->common->getAll("SELECT order_id FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
            				if(count($order_reanai_sum_data) > 0){
            					$dy = $this->config->item('renfi_fk_by_day_time');
            					if(!is_numeric($dy)){
            						$dy = 7;
            					}else if(floor($dy) == 0){
            						$dy = 7;
            					}else if(floor($dy) != $dy){
            						$dy = 7;
            					}
            					//更新预约预到时间 同时  $dy 天修改次数重置为0  ，从新开始计算 。 时间更新为预到时间的当天 23:59:59
            					$hos_id_row[0]['order_time'] =strtotime(date("Y-m-d",$hos_id_row[0]['order_time']+($dy*24*60*60))." 23:59:59");
            					//重置限制条件
            					$query="update ".$this->common->table('order_reanai_sum')." set order_time=".$hos_id_row[0]['order_time'].",sum=0 where order_id=".$order_id;
            					$this->db->query($query);
            				}
            			}
            			//删除预约表中重复信息
            			//$this->db->query("delete from ".$this->common->table('order')." where order_id=".$order_id);

            			//查询公海订单数据
            			$sql = "SELECT * FROM " . $this->common->table('gonghai_order')." where order_id=".$order_id;
            			$order_query_row = $this->common->getRow($sql);

            			if(empty($order_query_row['order_id'])){$order_query_row['order_id']=0;}
            			if(empty($order_query_row['order_no'])){$order_query_row['order_no']='';}
            			if(empty($order_query_row['is_first'])){$order_query_row['is_first']=0;}
            			if(empty($order_query_row['admin_id'])){$order_query_row['admin_id']=0;}
            			if(empty($order_query_row['admin_name'])){$order_query_row['admin_name']='';}
            			if(empty($order_query_row['pat_id'])){$order_query_row['pat_id']=0;}
            			if(empty($order_query_row['from_parent_id'])){$order_query_row['from_parent_id']=0;}
            			if(empty($order_query_row['from_id'])){$order_query_row['from_id']=0;}
            			if(empty($order_query_row['from_value'])){$order_query_row['from_value']='';}
            			if(empty($order_query_row['hos_id'])){$order_query_row['hos_id']=0;}
            			if(empty($order_query_row['keshi_id'])){$order_query_row['keshi_id']=0;}
            			if(empty($order_query_row['type_id'])){$order_query_row['type_id']=0;}
            			if(empty($order_query_row['jb_parent_id'])){$order_query_row['jb_parent_id']=0;}
            			if(empty($order_query_row['jb_id'])){$order_query_row['jb_id']=0;}
            			if(empty($order_query_row['order_addtime'])){$order_query_row['order_addtime']=0;}
            			if(empty($order_query_row['order_time'])){$order_query_row['order_time']=0;}
            			if(empty($order_query_row['order_null_time'])){$order_query_row['order_null_time']='';}
            			if(empty($order_query_row['doctor_time'])){$order_query_row['doctor_time']=0;}
            			if(empty($order_query_row['doctor_id'])){$order_query_row['doctor_id']=0;}
            			if(empty($order_query_row['doctor_name'])){$order_query_row['doctor_name']='';}
            			if(empty($order_query_row['come_time'])){$order_query_row['come_time']=0;}
            			if(empty($order_query_row['order_time_duan'])){$order_query_row['order_time_duan']='';}
            			if(empty($order_query_row['duan_confirm'])){$order_query_row['duan_confirm']=0;}
            			if(empty($order_query_row['is_come'])){$order_query_row['is_come']=0;}
            			if(empty($order_query_row['keshiurl_id'])){$order_query_row['keshiurl_id']=0;}
            			if(empty($order_query_row['ireport_order_id'])){$order_query_row['ireport_order_id']=0;}
            			if(empty($order_query_row['ireport_msg'])){$order_query_row['ireport_msg']='';}

            			$insert_str = ''.

            			"".$order_query_row['order_id'].",".
            			"'".$order_query_row['order_no']."',".
            			"".$order_query_row['is_first'].",".
            			"".$order_query_row['admin_id'].",".
            			"'".$order_query_row['admin_name']."',".
            			"".$order_query_row['pat_id'].",".
            			"".$order_query_row['from_parent_id'].",".
            			"".$order_query_row['from_id'].",".
            			"'".$order_query_row['from_value']."',".
            			"".$order_query_row['hos_id'].",".
            			"".$order_query_row['keshi_id'].",".
            			"".$order_query_row['type_id'].",".
            			"".$order_query_row['jb_parent_id'].",".
            			"".$order_query_row['jb_id'].",".
            			"".$order_query_row['order_addtime'].",".
            			"".$order_query_row['order_time'].",".
            			"'".$order_query_row['order_null_time']."',".
            			"".$order_query_row['doctor_time'].",".
            			"".$order_query_row['doctor_id'].",".
            			"'".$order_query_row['doctor_name']."',".
            			"".$order_query_row['come_time'].",".
            			"'".$order_query_row['order_time_duan']."',".
            			"".$order_query_row['duan_confirm'].",".
            			"".$order_query_row['is_come'].",".
            			"".$order_query_row['keshiurl_id'].",".
            			"'".$order_query_row['ireport_order_id']."',".
            			"'".$order_query_row['ireport_msg']."'";
            			$query1="insert into ".$this->common->table('order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
										jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) values(".$insert_str.")";
            			if($this->db->query($query1)){
            				$this->db->query("delete from ".$this->common->table('gonghai_order')." where order_id=".$order_id);
            				$bol  = true;
            			}
            		}
            	}
            }
            return $bol;
        }

        /**
         * 仁爱 台州 东方  温州 成都  掉30号之前的所有数据
         *
         * @return boolean
         */
        public function update_to_gonghai_all(){
        	$bol = false;
        	//翌日0点到1点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){
        	if(time() >= strtotime($time." 07:00:00") && time() < strtotime($time." 23:00:00")){
        		//获取所有orer_id
        		$time_str= date("Y-m-d",time()-31*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$time_str= date("Y-m-d",time()-31*24*60*60);
        		$start_time=strtotime($time_str." 23:59:59");
        		$sql ="select order_id,max(action_time) as action_time from hui_gonghai_log where `action_type` = '从公海捞取' and is_come = 0  group by order_id";
        		//echo $sql."<br/>";
        		//$sql = " select o.order_id,o.admin_name from hui_gonghai_log as l,hui_order as o where l.`action_type` = '从公海捞取' and l.action_time < ".$start_time." and o.keshi_id in(4,95,92,28,33,34,35,90,96,123,1,32) AND o.from_parent_id not in(169,170) AND o.from_id not in(213,195)  AND o.is_come=0 AND o.order_id = l.order_id order by o.order_addtime desc";
        		$order_id_str = '';
        		$order_reanai_sum_res=$this->common->getAll($sql);
        		foreach ($order_reanai_sum_res as $temp){
        			if($temp['action_time'] <= $start_time){
        				if(empty($order_id_str)){
        					$order_id_str  = "'".$temp['order_id']."'";
        				}else{
        					$order_id_str .= ",'".$temp['order_id']."'";
        				}
        			}
        		}
        		if(empty($order_id_str)){
        			$order_id_str = 0;
        		}
        		$sql ="select order_id  from hui_order where order_id in(".$order_id_str.") and keshi_id in(4,124,110,95,92,28,33,34,35,90,26,96,123,1,106,111,109) AND from_parent_id not in(169,170,117) AND from_id not in(213,195,249)  AND is_come=0 ";
        		$order_id_str = '';
        		$order_id_res=$this->common->getAll($sql);
        		foreach($order_id_res as $temp){
        			if(empty($order_id_str)){
        				$order_id_str  = "'".$temp['order_id']."'";
        			}else{
        				$order_id_str .= ",'".$temp['order_id']."'";
        			}
        		}
        		if(!empty($order_id_str)){
        			$sql = 'select o.order_id,o.admin_name from hui_order o where o.order_id in('.$order_id_str.')';
        			//echo $sql;exit;
        			$bol = $this->tz_df_gonghai_add($sql);
        		}
        	}
        	return $bol;
        }


        /**
         * 温州男科 外线科  掉登记时间超过40天的所有数据
         * 1点到2点 只要登记时间超过40天， 强制掉公海
         * @return boolean
         */
        public function update_gonghai_addtime_timeout_other_wenzhou(){
        	$bol = false;
        	//翌日0点到1点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){
        		//获取所有order_id
        		$time_str= date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$sql = 'select o.order_id,o.admin_name from hui_order o where o.hos_id in(37) AND o.keshi_id in(96,123) AND o.from_parent_id not in(230) AND o.from_id not in(195,213,249)  AND o.is_come=0 AND o.order_addtime < '.$start_time.' order by o.order_addtime desc;';
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }


        /**
         *
         * 温州 男科  外线科 登记时间40天内 预到而未到的 翌日流入公海
         * 2点到3点 如果在40天内的， 按照预诊时间来掉
         */
        public function update_dongfang_order_timeout_wenzhou(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." where  hos_id= 37 AND keshi_id in(96,123) AND from_parent_id not in(230) AND from_id not in(195,213,249)  AND is_come=0 AND order_addtime between ".$start_time." AND ".$end_time." AND order_time <= ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }



        /**
         *
         * 温州 男科  外线科 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 2点到3点 如果在40天内的， 按照回访时间来掉
         */
        public function update_dongfang_order_timeout_wenzhou_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." where  hos_id= 37 AND keshi_id in(96,123) AND from_parent_id not in(230) AND from_id not in(195,213,249)  AND is_come=0 AND order_addtime between ".$start_time." AND ".$end_time." AND order_time > ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf');
        	//}
        	return $bol;
        }

        /**
         *
         * 温州 男科  外线科 捞取时间40天内 预到而未到的 翌日流入公海
         * 2点到3点 如果在40天内的， 按照回访时间来掉
         */
        public function update_dongfang_order_timeout_wenzhou_gh(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id= 37 AND a.keshi_id in(96,123) AND a.from_parent_id not in(230) AND a.from_id not in(195,213,249)  AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time <= ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'',1);
        	//}
        	return $bol;
        }


        /**
         *
         * 温州 男科  外线科 捞取时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 2点到3点 如果在40天内的， 按照回访时间来掉
         */
        public function update_dongfang_order_timeout_wenzhou_gh_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 01:00:00") && time() < strtotime($time." 02:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id= 37 AND a.keshi_id in(96,123) AND a.from_parent_id not in(230) AND a.from_id not in(195,213,249)  AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time > ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf',1);
        	//}
        	return $bol;
        }




        /**
         * 台州  男科、妇科、台州公共组、夜班外线 妇科
         * 掉登记时间超过40天的所有数据
         * 1点到2点 只要登记时间超过40天， 强制掉公海
         * @return boolean
         */
        public function update_gonghai_addtime_timeout_other(){
        	$bol = false;
        	//翌日0点到1点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){
        		//获取所有order_id
        		$time_str= date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$sql = 'select o.order_id,o.admin_name,o.admin_id from hui_order o where o.hos_id = 3 AND o.keshi_id in(4,124,95,92) AND o.from_parent_id not in(169,170,117) AND o.from_id not in(249) AND o.is_come=0 AND o.order_addtime < '.$start_time.' order by o.order_addtime desc;';
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }



        /**
         * 2点到3点 台州同步程序 登记时间40天内 预到而未到的 翌日流入公海
         * 如果在40天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_tz_df_order_timeout(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = 'select o.order_id,o.admin_name,o.admin_id from hui_order o where o.hos_id = 3 AND o.keshi_id in(4,124,95,92) AND o.from_parent_id not in(169,170,117) AND o.from_id not in(249) AND o.is_come=0 AND o.order_addtime between '.$start_time.' AND '.$end_time.' AND o.order_time <= '.$end_time;

        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }


        /**
         * 2点到3点 台州同步程序 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在40天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_tz_df_order_timeout_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = 'select o.order_id,o.admin_name,o.admin_id from hui_order o where o.hos_id = 3 AND o.keshi_id in(4,124,95,92) AND o.from_parent_id not in(169,170,117) AND o.from_id not in(249) AND o.is_come=0 AND o.order_addtime between '.$start_time.' AND '.$end_time.' AND o.order_time > '.$end_time;

        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf');
        	//}
        	return $bol;
        }


        /**
         * 2点到3点 台州同步程序 捞取时间40天内 预到而未到的 翌日流入公海
         * 如果在40天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_tz_df_order_timeout_gh(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

				$sql = " select a.order_id,a.admin_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id = 3 AND a.keshi_id in(4,124,95,92) AND a.from_parent_id not in(169,170,117) AND a.from_id not in(249) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time <= ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'',1);
        	//}
        	return $bol;
        }


        /**
         * 2点到3点 台州同步程序 捞取时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在40天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_tz_df_order_timeout_gh_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 02:00:00") && time() < strtotime($time." 03:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

				$sql = " select a.order_id,a.admin_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id = 3 AND a.keshi_id in(4,124,95,92) AND a.from_parent_id not in(169,170,117) AND a.from_id not in(249) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time > ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf',1);
        	//}
        	return $bol;
        }







	    /**
	     * 东莞 登记时间超过30天掉公海(排除已捞出预约)
	     * @return boolean
	     */
	    public function drop_order_addtime_exceed_30()
	    {
	        $bol = false;

	        //从公海捞取的order_id
	        $sql = " select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come=0 ";
	        $order_id_str_all = '';
	        $result = $this->common->getAll($sql);
	        if (count($result) > 0) {
	            foreach ($result as $temp) {
	                if (empty($order_id_str_all)) {
	                    $order_id_str_all = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str_all .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }
	        $sql = " select t.order_id from hui_gonghai_log as t where t.action_time=(select max(t1.action_time) from hui_gonghai_log as t1 where t.order_id = t1.order_id and t1.action_type = '从公海捞取') and t.action_type = '从公海捞取' and t.order_id in (".$order_id_str_all.") " ;
	        $rs = $this->common->getAll($sql);
	        $fish_order_id = array();
	        if (count($rs) > 0) {
	            foreach ($rs as $temp) {
	                $fish_order_id[] = $temp['order_id'];
	            }
	        }

	        $start_time = strtotime(date("Y-m-d 00:00:00", time() - 30 * 24 * 60 * 60));
	        $sql = 'select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come = 0 AND order_addtime < ' . $start_time ;
	        $order_id_str = '';
	        $result = $this->common->getAll($sql);
	        foreach ($result as $temp) {
	            //排除捞取的
	            if (!in_array($temp['order_id'],$fish_order_id)){
	                if (empty($order_id_str)) {
	                    $order_id_str = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }
	        //p($order_id_str);die();
	        return $this->droptosea($order_id_str,1);
	    }

	    /**
	     * 东莞 登记时间30天内遇到未到掉公海
	     * @return boolean
	     */
	    public function drop_order_addtime_under_30_notcome()
	    {
	        $bol = false;
	        $order_addtime30 = strtotime(date("Y-m-d 00:00:00", time() - 30 * 24 * 60 * 60));
	        $endtime30 = strtotime(date("Y-m-d 23:59:59", time() - 1 * 24 * 60 * 60));
	        $sql = 'select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come = 0 AND order_time <= '. $endtime30 .' AND order_addtime between ' . $order_addtime30 . ' AND ' . $endtime30;
	        $order_id_str = '';
	        $result = $this->common->getAll($sql);
	        foreach ($result as $temp) {
	            if (empty($order_id_str)) {
	                $order_id_str = "'" . $temp['order_id'] . "'";
	            } else {
	                $order_id_str .= ",'" . $temp['order_id'] . "'";
	            }
	        }
	        //p($result);die();
	        return $this->droptosea($order_id_str,2);
	    }

	    /**
	     * 东莞 登记时间30天内超过7天未回访掉公海
	     * @return bool
	     */
	    public function drop_order_addtime_under_30_exceed7_notcalled()
	    {
	        $bol = false;

	        //30天内
	        $order_addtime30 = strtotime(date("Y-m-d 00:00:00", time() - 30 * 24 * 60 * 60));
	        $endtime30 = strtotime(date("Y-m-d 23:59:59", time() - 1 * 24 * 60 * 60));
	        $sql = 'select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come = 0 AND order_addtime between ' . $order_addtime30 . ' AND ' . $endtime30;
	        $order_id_str30 = '';
	        $result = $this->common->getAll($sql);
	        foreach ($result as $temp) {
	            if (empty($order_id_str30)) {
	                $order_id_str30 = "'" . $temp['order_id'] . "'";
	            } else {
	                $order_id_str30 .= ",'" . $temp['order_id'] . "'";
	            }
	        }

	        $order_id_str = '';
	        if (!empty($order_id_str30)) {
	            //有回访过
	            $called_time7 = strtotime(date("Y-m-d 00:00:00", time() - 7 * 24 * 60 * 60));
	            $sql_lq = " select t.order_id,t.mark_time from " . $this->common->table('order_remark') . " as t where order_id in(" . $order_id_str30 . ") and mark_time=(select max(t1.mark_time) from " . $this->common->table('order_remark') . " as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time < " . $called_time7 . " and t.mark_type = 3 order by t.mark_time desc";
	            $lq_res = $this->common->getAll($sql_lq);
	            for ($i = 0; $i < count($lq_res); $i++) {
	                $lq_res[$i]['mark_time'] = date('Y-m-d', $lq_res[$i]['mark_time']);
	            }
	            foreach ($lq_res as $temp) {
	                if (empty($order_id_str)) {
	                    $order_id_str = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }
	        //p($order_id_str);die();
	        return $this->droptosea($order_id_str,3);
	    }

	    /**
	     * 东莞 登记时间30天内登记时间超过7天且未回访掉公海
	     * @return bool
	     */
	    public function drop_order_addtime_under_30_notcalled()
	    {
	        $bol = false;

	        //30天内
	        $order_addtime30 = strtotime(date("Y-m-d 00:00:00", time() - 30 * 24 * 60 * 60));
	        $endtime30 = strtotime(date("Y-m-d 23:59:59", time() - 1 * 24 * 60 * 60));
	        $sql = 'select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come = 0 AND order_addtime between ' . $order_addtime30 . ' AND ' . $endtime30;
	        $order_id_str30 = '';
	        $result = $this->common->getAll($sql);
	        foreach ($result as $temp) {
	            if (empty($order_id_str30)) {
	                $order_id_str30 = "'" . $temp['order_id'] . "'";
	            } else {
	                $order_id_str30 .= ",'" . $temp['order_id'] . "'";
	            }
	        }

	        $order_id_str = '';
	        if (!empty($order_id_str30)) {
	            //没有回访，那就登记时间超过7天掉
	            //过滤回访时间为空且登记时间未超过7天的
	            //获取有回访的

	            $order_addtime7 = strtotime(date("Y-m-d 00:00:00", time() - 7 * 24 * 60 * 60));
	            $sqls = "select DISTINCT order_id from " . $this->common->table('order_remark') . " where order_id in(" . $order_id_str30 . ") AND mark_type = 3";
	            $result = $this->common->getAll($sqls);

	            foreach ($result as $key => $value) {
	                $new_result[] = $value['order_id'];
	            }

	            $order_id_str30 = str_replace("'", "", $order_id_str30);
	            $order_all_array = explode(',', $order_id_str30);

	            foreach ($order_all_array as $key => $value) {
	                if (!in_array($value, $new_result)) {
	                    $empty_hf_array[] = $value;
	                }
	            }

	            $order_id_str = '';
	            if (!empty($empty_hf_array)) {
	                $empty_hf_order_id_str = implode(',', $empty_hf_array);

	                $sqls = "select order_id, order_addtime from " . $this->common->table('order') . " where order_id in(" . $empty_hf_order_id_str . ") AND order_addtime < " . $order_addtime7;
	                $empty_hf_result = $this->common->getAll($sqls);

	                for ($i = 0; $i < count($empty_hf_result); $i++) {
	                    $empty_hf_result[$i]['order_addtime'] = date('Y-m-d H:i', $empty_hf_result[$i]['order_addtime']);
	                }

	                if (count($empty_hf_result) > 0) {
	                    foreach ($empty_hf_result as $temp) {
	                        if (empty($order_id_str)) {
	                            $order_id_str = "'" . $temp['order_id'] . "'";
	                        } else {
	                            $order_id_str .= ",'" . $temp['order_id'] . "'";
	                        }
	                    }
	                }
	            }
	        }
	        //p($order_id_str);die();
	        return $this->droptosea($order_id_str,4);
	    }

	    /**
	     * 东莞 超过捞取时间10天未到诊掉公海
	     * @return bool
	     */
	    public function drop_fish_time_exceed_10_notcome()
	    {
	        $bol = false;
	        $fishtime10 = strtotime(date("Y-m-d 00:00:00", time() - 10 * 24 * 60 * 60));
	        $sql = " select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come=0 ";

	        $order_id_str_all = '';
	        $result = $this->common->getAll($sql);
	        if (count($result) > 0) {
	            foreach ($result as $temp) {
	                if (empty($order_id_str_all)) {
	                    $order_id_str_all = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str_all .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }

	        $sql = " select t.order_id from hui_gonghai_log as t where t.action_time=(select max(t1.action_time) from hui_gonghai_log as t1 where t.order_id = t1.order_id and t1.action_type = '从公海捞取') and t.action_time < ".$fishtime10." and t.action_type = '从公海捞取' and t.order_id in (".$order_id_str_all.") " ;
	        $rs = $this->common->getAll($sql);
	        $order_id_str = '';
	        if (count($rs) > 0) {
	            foreach ($rs as $temp) {
	                if (empty($order_id_str)) {
	                    $order_id_str = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }
	        //p($order_id_str);die();
	        return $this->droptosea($order_id_str,5);
	    }

	    /**
	     * 东莞 预到时间修改超过3次未到诊掉公海
	     * @return bool
	     */
	    public function drop_order_time_modify3_notcome()
	    {
	        $bol = false;

	        $under_order_addtime30 = strtotime(date("Y-m-d 00:00:00", time() - 30 * 24 * 60 * 60));
	        $sql = 'select order_id,order_no from hui_order where hos_id = 6 AND keshi_id in(28,33,34,35,90) AND from_parent_id not in(169,170,117) AND from_id not in(249) AND is_come = 0 AND order_addtime >= ' . $under_order_addtime30 ;
	        $order_id_str30 = '';
	        $result = $this->common->getAll($sql);
	        if (count($result) > 0) {
	            foreach ($result as $temp) {
	                if (empty($order_id_str30)) {
	                    $order_id_str30 = "'" . $temp['order_id'] . "'";
	                } else {
	                    $order_id_str30 .= ",'" . $temp['order_id'] . "'";
	                }
	            }
	        }

	        $sql = "select order_id,count(order_id) as count from henry_ordertime_update_record where order_id in(".$order_id_str30.") group by order_id";
	        $res = $this->db->query($sql)->result_array();

	        $order_id_str = '';
	        if (count($res) > 0) {
	            foreach ($res as $temp) {
	                if ($temp['count'] > 3){
	                    if (empty($order_id_str)) {
	                        $order_id_str = "'" . $temp['order_id'] . "'";
	                    } else {
	                        $order_id_str .= ",'" . $temp['order_id'] . "'";
	                    }
	                }
	            }
	        }

	        //p($order_id_str);die();
	        return $this->droptosea($order_id_str,6);
	    }

	    /**
	     * 东莞统一掉公海
	     * @param $order_id_str
	     * @return bool
	     */
	    public function droptosea($order_id_str,$type)
	    {

	        $bol = false;
	        $time = time();

	        if (!empty($order_id_str)) {
	            //往公海插入数据
	            $sql = "insert into " . $this->common->table('gonghai_order') . "(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from " . $this->common->table('order') . " where order_id in(" . $order_id_str . ") and order_id not in( select " . $this->common->table('gonghai_order') . ".order_id from " . $this->common->table('gonghai_order') . " where " . $this->common->table('gonghai_order') . ".order_id in(" . $order_id_str . ") )";

	            if ($this->db->query($sql)) {
	                if ($this->db->query("delete from " . $this->common->table('order') . " where order_id in(" . $order_id_str . ")")) {
	                    $bol = true;
	                }
	                if ($this->db->query("update " . $this->common->table('gonghai_order') . " set first_timeout=" . time() . ",gonghai_type='gonghai' where order_id in(" . $order_id_str . ")")) {
	                    //掉入公海记录
	                    $order_id_diaoru = explode(",", $order_id_str);
	                    foreach ($order_id_diaoru as $k => $val){
	                        $ids_all[] = trim($val,"'");
	                    }
	                    foreach ($ids_all as $res_temp) {
	                        $this->db->query("insert into " . $this->common->table('gonghai_log') . "(order_id,action_type,action_name,action_time,is_come) values('" . $res_temp . "','掉入公海','','" . time() . "',0)");
	                        $gh_id = $this->db->insert_id();
	                        $this->db->query("insert into henry_drop_sea_log (order_id,type,drop_time,gh_id) values(" . $res_temp . ",".$type.",".$time.",".$gh_id.")");
	                    }
	                }
	                $this->db->query("delete from henry_ordertime_update_record where order_id in(" . $order_id_str . ")");
	            }
	        }
	        return $bol;
	    }











        /**
         * 成都 男科 外线科
         * 成都合作 男科
         * 掉登记时间超过40天的所有数据
         * 1点到2点 只要登记时间超过40天， 强制掉公海
         * @return boolean
         */

        public function update_gonghai_addtime_timeout_other_chengdu(){
        	$bol = false;
        	//翌日0点到1点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){
        		//获取所有order_id
        		$time_str= date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$sql = 'select o.order_id,o.admin_name from hui_order o where o.hos_id in(42,44) AND o.keshi_id in(106,111,109) AND o.from_parent_id not in(117) AND from_id not in(195,196,197,207,210,249) AND o.admin_id not in(1832,1661) AND o.is_come=0 AND o.order_addtime < '.$start_time.' order by o.order_addtime desc;';
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }



        /**
         * 成都 男科  外线科
         * 成都合作 男科
         * 2点到3点 登记时间40天内 预到而未到的 翌日流入公海
         * 如果在40天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_chengdu(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(42,44) AND o.keshi_id in(106,111,109) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.admin_id not in(1832,1661) AND o.is_come=0 AND o.order_addtime between ".$start_time." AND ".$end_time." AND o.order_time <= ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }


        /**
         * 成都 男科  外线科
         * 成都合作 男科
         * 2点到3点 登记时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在40天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_chengdu_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(42,44) AND o.keshi_id in(106,111,109) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.admin_id not in(1832,1661) AND o.is_come=0 AND o.order_addtime between ".$start_time." AND ".$end_time." AND o.order_time > ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf');
        	//}
        	return $bol;
        }

        /**
         * 成都 男科  外线科
         * 成都合作 男科
         * 2点到3点 捞取时间40天内 预到而未到的 翌日流入公海
         * 如果在40天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_chengdu_gh(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id in(42,44) AND a.keshi_id in(106,111,109) AND a.from_parent_id not in(117) AND a.from_id not in(195,196,197,207,210,249) AND a.admin_id not in(1832,1661) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time <= ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'',1);
        	//}
        	return $bol;
        }


        /**
         * 成都 男科  外线科
         * 成都合作 男科
         * 2点到3点 捞取时间40天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在40天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_chengdu_gh_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-40*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id in(42,44) AND a.keshi_id in(106,111,109) AND a.from_parent_id not in(117) AND a.from_id not in(195,196,197,207,210,249) AND a.admin_id not in(1832,1661) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time > ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf',1);
        	//}
        	return $bol;
        }






        /**
         * 宁波男科
         * 掉登记时间超过60天的所有数据
         * 1点到2点 只要登记时间超过60天， 强制掉公海
         * @return boolean
         */

        public function update_gonghai_addtime_timeout_other_ningbo(){
        	$bol = false;
        	//翌日0点到1点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){
        		//获取所有order_id
        		$time_str= date("Y-m-d",time()-60*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$sql = 'select o.order_id,o.admin_name from hui_order o where o.hos_id in(54) AND o.keshi_id in(151) AND o.from_parent_id not in(117) AND from_id not in(195,196,197,207,210,249) AND o.is_come=0 AND o.order_addtime < '.$start_time.' order by o.order_addtime desc;';
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }



        /**
         * 宁波男科
         * 2点到3点 登记时间60天内 预到而未到的 翌日流入公海
         * 如果在60天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_ningbo(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-60*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(54) AND o.keshi_id in(151) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.is_come=0 AND o.order_addtime between ".$start_time." AND ".$end_time." AND o.order_time <= ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql);
        	//}
        	return $bol;
        }


        /**
         * 宁波男科
         * 2点到3点 登记时间60天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在60天内的， 按照预诊时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_ningbo_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-60*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(54) AND o.keshi_id in(151) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.is_come=0 AND o.order_addtime between ".$start_time." AND ".$end_time." AND o.order_time > ".$end_time;
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf');
        	//}
        	return $bol;
        }

        /**
         * 宁波男科
         * 2点到3点 捞取时间60天内 预到而未到的 翌日流入公海
         * 如果在60天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_ningbo_gh(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-60*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id in(54) AND a.keshi_id in(151) AND a.from_parent_id not in(117) AND a.from_id not in(195,196,197,207,210,249) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time <= ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'',1);
        	//}
        	return $bol;
        }


        /**
         * 宁波男科
         * 2点到3点 捞取时间60天内 没到预到时间但回访时间超过10天 翌日流入公海
         * 如果在60天内的， 按照捞取时间来掉
         * @return boolean
         */
        public function update_dongfang_order_timeout_ningbo_gh_hf(){
        	$bol = false;
        	//翌日1点到2点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	//if(time() >= strtotime($time." 03:00:00") && time() < strtotime($time." 04:00:00")){

        		$time_dat = date("Y-m-d",time()-60*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$end_time=strtotime($time_dat." 23:59:59");

        		$sql = " select a.order_id from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id where a.hos_id in(54) AND a.keshi_id in(151) AND a.from_parent_id not in(117) AND a.from_id not in(195,196,197,207,210,249) AND a.is_come=0 AND b.action_time between ".$start_time." AND ".$end_time." AND a.order_time > ".$end_time." and b.action_type like '从公海捞取'";
        		//echo $sql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'hf',1);
        	//}
        	return $bol;
        }


        /**
         * 宁波男科
         * 2点到3点 回访时间超过15天且
         * 未回访按登记时间超过15天
         * 翌日流入公海
         * @return boolean
         */
        public function ningbo_drop_sea(){

        	$time = time();
			$time_dat = date("Y-m-d",time()-15*24*60*60);
			$start_time=strtotime($time_dat." 00:00:00");

			//所有
			$sql = " select order_id,admin_name from ".$this->common->table('order')." o where  o.hos_id in(54) AND o.keshi_id in(151) AND o.from_parent_id not in(117) AND o.from_id not in(195,196,197,207,210,249) AND o.is_come=0 ";

			$all_orders=$this->common->getAll($sql);

			if (empty($all_orders)) {
				return true;
			}

			$all_order_ids = array();
			foreach ($all_orders as $order) {
				$all_order_ids[] = $order['order_id'];
			}

			$order_id_str = implode(",", $all_order_ids);

			//回访未超过15天
			$sql = " select t.order_id,t.mark_time from ".$this->common->table('order_remark')." as t where order_id in(".$order_id_str.") and mark_time=(select max(t1.mark_time) from ".$this->common->table('order_remark')." as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time >= ".$start_time." and t.mark_type = 3 order by t.mark_time desc" ;

		    $under_15_hf_orders=$this->common->getAll($sql);

		    $under_15_hf_order_ids = array();
		    foreach ($under_15_hf_orders as $order) {
		    	$under_15_hf_order_ids[] = $order['order_id'];
		    }

	    	//回访超过15天
	    	$sql = " select t.order_id,t.mark_time from ".$this->common->table('order_remark')." as t where order_id in(".$order_id_str.") and mark_time=(select max(t1.mark_time) from ".$this->common->table('order_remark')." as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time < ".$start_time." and t.mark_type = 3 order by t.mark_time desc" ;

	        $exceed_15_hf_orders=$this->common->getAll($sql);

	        $exceed_15_hf_order_ids = array();
	        foreach ($exceed_15_hf_orders as $order) {
	        	$exceed_15_hf_order_ids[] = $order['order_id'];
	        }


	        //所有回访为空的
		    $null_hf_orders = array_diff($all_order_ids, $under_15_hf_order_ids, $exceed_15_hf_order_ids);

		    $null_hf_order_ids = array();
			foreach ($null_hf_orders as $id) {
				$null_hf_order_ids[] = $id;
			}

			if (empty($null_hf_order_ids)) {
				$order_id_str = 0;
			}

			$order_id_str = implode(",", $null_hf_order_ids);

			//超过登记时间15天回访为空的
		    $sql = "select order_id from ".$this->common->table('order')." where order_id in (".$order_id_str.") and order_addtime < ".$start_time ;

		    $exceed_addtime15_null_hf_orders=$this->common->getAll($sql);

	        $exceed_addtime15_null_hf_orders_ids = array();
	        foreach ($exceed_addtime15_null_hf_orders as $order) {
	        	$exceed_addtime15_null_hf_orders_ids[] = $order['order_id'];
	        }

	        //超过登记时间15天回访为空的，回访超过15天掉公海
	       	$final_order_ids = array_merge($exceed_15_hf_order_ids,$exceed_addtime15_null_hf_orders_ids);

	       	$final_order_ids_str = implode(",", $final_order_ids);

	       	//p($final_order_ids);die();

	       	if(!empty($final_order_ids)){

	       		$this->db->trans_begin();

	       		try {

	        		//往公海插入数据
	        		$sql="insert into ".$this->common->table('gonghai_order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
	                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
	                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('order')." where order_id in(".$final_order_ids_str.")
	                                		and order_id not in( select ".$this->common->table('gonghai_order').".order_id from ".$this->common->table('gonghai_order')." where ".$this->common->table('gonghai_order').".order_id in(".$final_order_ids_str.") )";
	                $del_sql = "delete from ".$this->common->table('order')." where order_id in(".$final_order_ids_str.")";
	                $up_sql = "update ".$this->common->table('gonghai_order')." set first_timeout=".time().",gonghai_type='gonghai' where order_id in(".$final_order_ids_str.")";
	        		if($this->db->query($sql)){

	        			if($this->db->query($del_sql) && $this->db->query($up_sql)){
							$part_sql = '';
	                        foreach ($final_order_ids as $id) {
	                            $part_sql .= "($id,'掉入公海','',$time,0),";
	                        }
	                        $part_sql = substr($part_sql,0,-1);

	                        //掉入公海记录
	                        $sql = "insert into ".$this->common->table('gonghai_log')." (order_id,action_type,action_name,action_time,is_come) values ".$part_sql;
	                        if (!$this->db->query($sql)) {
	                            throw new Exception ('插入公海日志失败！');
	                        }

	        			} else {
	                        throw new Exception ('删除相应预约信息失败！');
	                    }

	                } else {
	                    throw new Exception ('数据掉公海失败！');
	                }

		        } catch (Exception $exception) {
		            $this->db->trans_rollback();
		            return false;
		        }

		        $this->db->trans_commit();

        	}

        	return true;
        }



        /**
         *
         * 4点到5点 仁爱医院同步程序
         * 2017 07 01
         *   仁爱妇科+不孕  当天预到而未到的，翌日流入公海
         * @return boolean
         */
        public function update_rafk_order_timeout(){
        	$bol = false;
        	//翌日3点到4点之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	if(time() >= strtotime($time." 04:00:00") && time() < strtotime($time." 05:00:00")){
        		$time_dat = date("Y-m-d",time()-1*24*60*60);
        		$start_time=strtotime($time_dat." 00:00:00");
        		$end_time=strtotime($time_dat." 23:59:59");

        		$order_mysql = " select order_id,admin_name from ".$this->common->table('order')." where  hos_id= 1 and keshi_id in(1) AND is_come=0 AND order_time between ".$start_time." and ".$end_time;
        		$order_res=$this->common->getAll($order_mysql);
        		$order_id_str = "";
        		foreach ($order_res as $order_res_temp){
        			if(empty($order_id_str)){
        				$order_id_str  = "'".$order_res_temp['order_id']."'";
        			}else{
        				$order_id_str .= ",'".$order_res_temp['order_id']."'";
        			}
        		}
        		if(!empty($order_id_str)){
        			/**
        			 * 过滤被取消的
        			 * **/
        			$order_mysql = " select DISTINCT order_id from ".$this->common->table('order_out')." where order_id in(".$order_id_str.")" ;
        			$order_out_res=$this->common->getAll($order_mysql);
        			if(count($order_out_res) > 0){
        				foreach($order_out_res as $order_out_res_temp){
        					$order_id_str  =  str_replace("'".$order_out_res_temp['order_id']."'", "'0'",$order_id_str);
        				}
        			}
        		}

        		$order_id_str  =  str_replace(",'0',", ",",$order_id_str);
        		$order_id_str  =  str_replace("'0',", "",$order_id_str);
        		$order_id_str  =  str_replace(",'0'", "",$order_id_str);
        		$order_id_str  =  str_replace("'", "",$order_id_str);

        		if(!empty($order_id_str)){
        			$order_in_array = explode(',',$order_id_str);
        			foreach ($order_in_array as $order_in_array_temp){
        				/***
        				 * 判断是否 在昨天 回访过的,未回访的掉入公海
        				*/
        				$order_remark_check =0;
        				/**
        				$order_mysql = " select mark_time from ".$this->common->table('order_remark')." where order_id in(".$order_in_array_temp.") and mark_type = 3 order by mark_time desc limit 0,1" ;
        				$order_remark_res=$this->common->getOne($order_mysql);
        				$order_remark_check =0;
        				if($order_remark_res == false){
        					$order_remark_check =1;
        				}else if(strcmp(date("Y-m-d",time()-1*24*60*60),date("Y-m-d",$order_remark_res)) != 0){
        					$order_remark_check =1;
        				}
        				**/
        				if(strcmp(date("Y-m-d",time()-1*24*60*60),date("Y-m-d",$order_remark_res)) != 0){
        					$order_remark_check =1;
        				}
        				if($order_remark_check == 1){
        					//往公海插入数据
        					$sql="insert into ".$this->common->table('gonghai_order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('order')." where order_id in(".$order_in_array_temp.")
                                		and order_id not in( select ".$this->common->table('gonghai_order').".order_id from ".$this->common->table('gonghai_order')." where ".$this->common->table('gonghai_order').".order_id in(".$order_in_array_temp.") ) ";
        					if($this->db->query($sql)){
        						if($this->db->query("delete from ".$this->common->table('order')." where order_id in(".$order_in_array_temp.")")){
        							$bol =  true;
        						}

        						if($this->db->query("update ".$this->common->table('gonghai_order')." set  first_timeout=".time().",gonghai_type='gonghai' where order_id in(".$order_in_array_temp.")")){
        							foreach($order_res as $val){
        								if($val['order_id'] == $order_in_array_temp){
        									$this->db->query("insert into ".$this->common->table('gonghai_log')."(order_id,action_type,action_name,action_time,is_come) values('".$val['order_id']."','掉入公海','','".time()."',0)");
        									break;
        								}
        							}

        						}
        					}
        				}
        			}
        		}
        	}
        	return $bol;
        }

        /**
         * 掉30号之前的所有数据 仁爱 妇科+不孕
         *
         * 仁爱 妇科+不孕
         * @return boolean
         */
        public function update_rafk_addtime_timeout_other(){
        	$bol = false;
        	//翌日5点到6点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	if(time() >= strtotime($time." 05:00:00") && time() < strtotime($time." 06:00:00")){
        		//获取所有orer_id
        		$time_str= date("Y-m-d",time()-31*24*60*60);
        		$start_time=strtotime($time_str." 00:00:00");

        		$time_str= date("Y-m-d",time()-1*24*60*60);
        		$today_time=strtotime($time_str." 00:00:00");

        		$sql = " select order_id,admin_name from ".$this->common->table('order')." where  hos_id= 1 and keshi_id in(1) AND is_come=0 AND order_addtime < ".$start_time."  and  order_time < ".$today_time;
        	    //echo $order_mysql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'ra');
        	}
        	return $bol;
        }

        /**
         * 5点到6点 仁爱 妇科+不孕   以添加时为准 超过添加时间30天的。
         *
         *  仁爱 妇科+不孕
         * @return boolean
         */
        public function update_rafk_order_timeout_other(){
        	$bol = false;
        	//翌日5点到6点 之内执行此代码块    数据流入公海
        	$time = date("Y-m-d",time());
        	if(time() >= strtotime($time." 05:00:00") && time() < strtotime($time." 06:00:00")){

        		$time_str= date("Y-m-d",time()-30*24*60*60);
        		$end_time=strtotime($time_str." 23:59:59");
        		$start_time=strtotime($time_str." 00:00:00");
        		//获取所有orer_id
        		$sql = " select order_id,admin_name from ".$this->common->table('order')." where  hos_id= 1 and keshi_id in(1) AND is_come=0 AND order_addtime between ".$start_time." and ".$end_time;
        		//echo $order_mysql;exit;
        		$bol = $this->tz_df_gonghai_add($sql,'ra');
        	}
        	return $bol;
        }

        /**
         * 台州东方温州成都 公用 同步代码块
         * @param unknown $sql $type 是否仁爱项目  $gh 是否来自公海
         * @return boolean
         */
        public function tz_df_gonghai_add($sql,$type,$gh){

        	$bol =  false;
        	$order_id_str = '';
        	$order_reanai_sum_res=$this->common->getAll($sql);
			//echo $sql;
			//二维数组删除相同值
			$tmp_key[] = array();
			foreach ($order_reanai_sum_res as $key => &$item) {
				if ( is_array($item) && isset($item['order_id']) ) {
				  if ( in_array($item['order_id'], $tmp_key) ) {
				    unset($order_reanai_sum_res[$key]);
				  } else {
				    $tmp_key[] = $item['order_id'];
				  }
				}
			}

        	//var_dump($order_reanai_sum_res);

        	foreach($order_reanai_sum_res as $temp){
        		if(empty($order_id_str)){
        			$order_id_str  = "'".$temp['order_id']."'";
        		}else{
        			$order_id_str .= ",'".$temp['order_id']."'";
        		}
        	}

        	$init_order_id_str = $order_id_str;

        	//var_dump($order_id_str);

        	//除了仁爱 其他医院用此函数
        	//过滤回访时间未超过10天的
        	if ($type =='hf') {
        		if(!empty($order_id_str)){

	        		$time_str= date("Y-m-d",time()-10*24*60*60);
        			$start_time=strtotime($time_str." 00:00:00");//10天前当天零点时间

                    //var_dump(date("Y-m-d H:i:s",$start_time));
        			//过滤回访时间未超过10天的

	        		$sql_lq = " select t.order_id,t.mark_time from ".$this->common->table('order_remark')." as t where order_id in(".$order_id_str.") and mark_time=(select max(t1.mark_time) from ".$this->common->table('order_remark')." as t1 where t.order_id = t1.order_id and t1.mark_type = 3) and t.mark_time >= ".$start_time." and t.mark_type = 3 order by t.mark_time desc" ;
	        		$lq_res=$this->common->getAll($sql_lq);
	        		for ($i=0; $i < count($lq_res); $i++) {
	        			$lq_res[$i]['mark_time'] = date('Y-m-d',$lq_res[$i]['mark_time']);
	        		}
	        		//echo $sql_lq;
	        		//p($lq_res);

	        		if(count($lq_res) > 0){
	        			foreach($lq_res as $lq_res_temp){
	        				$order_id_str  =  str_replace("'".$lq_res_temp['order_id']."'", "'0'",$order_id_str);
	        			}
	        		}



	        		//如果没有回访，那就登记时间超过10天掉
	        		//过滤回访时间为空且登记时间未超过10天的
	        		$sqls = "select DISTINCT order_id from ".$this->common->table('order_remark')." where order_id in(".$init_order_id_str.") AND mark_type = 3" ;
	        		$result=$this->common->getAll($sqls);
	        		//p($result);

	        		foreach ($result as $key => $value) {
	        			$new_result[] = $value['order_id'];
	        		}

	        		//p($new_result);

	        		$init_order_id_str = str_replace("'", "",$init_order_id_str);
	        		$order_all_array = explode(',', $init_order_id_str);
	        		//p($order_all_array);

	        		foreach ($order_all_array as $key => $value) {
	        			if (!in_array($value, $new_result)) {
	        				$empty_hf_array[] = $value;
	        			}
	        		}

	        		if (!empty($empty_hf_array)) {
	        			$empty_hf_order_id_str = implode(',', $empty_hf_array);

		        		//p($empty_hf_order_id_str);

		        		$sqls = "select order_id, order_addtime from ".$this->common->table('order')." where order_id in(".$empty_hf_order_id_str.") AND order_addtime > ".$start_time ;
		        		$empty_hf_result=$this->common->getAll($sqls);

		        		for ($i=0; $i < count($empty_hf_result); $i++) {
		        			$empty_hf_result[$i]['order_addtime'] = date('Y-m-d H:i',$empty_hf_result[$i]['order_addtime']);
		        		}
		        		//p($empty_hf_result);

		        		if(count($empty_hf_result) > 0){
		        			foreach($empty_hf_result as $lq_res_temp){
		        				$order_id_str  =  str_replace("'".$lq_res_temp['order_id']."'", "'0'",$order_id_str);
		        			}
		        		}
	        		}

	        		//var_dump($order_id_str);

	        	}
        	}

        	//除了仁爱 其他医院用此函数的不过滤取消的订单
        	//之前取消的订单没做恢复，要和数据中心保持一致
        	if ($type=='ra') {
        		if(!empty($order_id_str)){
	        		/**
	        		 * 过滤被取消的
	        		 * **/
	        		$order_mysql = " select DISTINCT order_id from ".$this->common->table('order_out')." where order_id in(".$order_id_str.")" ;
	        		$order_out_res=$this->common->getAll($order_mysql);
	        		if(count($order_out_res) > 0){
	        			foreach($order_out_res as $order_out_res_temp){
	        				$order_id_str  =  str_replace("'".$order_out_res_temp['order_id']."'", "'0'",$order_id_str);
	        			}
	        		}
	        	}
        	}


//var_dump($order_id_str);
        	//除了仁爱和第3个参数不为1 其他医院用此函数
        	//从公海捞出的订单根据捞出时间超过40天掉入公海
        	if ($type!='ra' && $gh != 1) {
        		if(!empty($order_id_str)){

	        		$time_str= date("Y-m-d",time()-40*24*60*60);
        			$start_time=strtotime($time_str." 00:00:00");//40天前当天零点时间

                    //var_dump(date("Y-m-d H:i:s",$start_time));
        			//过滤捞出时间在40天以内的
	        		$sql_lq = " select DISTINCT order_id from ".$this->common->table('gonghai_log')." where order_id in(".$order_id_str.") and action_time >= ".$start_time." and action_type like '从公海捞取'" ;
	        		$lq_res=$this->common->getAll($sql_lq);
	        		//var_dump($lq_res);

	        		if(count($lq_res) > 0){
	        			foreach($lq_res as $lq_res_temp){
	        				$order_id_str  =  str_replace("'".$lq_res_temp['order_id']."'", "'0'",$order_id_str);
	        			}
	        		}
	        	}
        	}


        	//$order_id_str = "'0','0','0','0','0','0','0','0','0','0','0'";
        	$order_id_str  =  str_replace(",'0',", ",",$order_id_str);
        	$order_id_str  =  str_replace("'0',", "",$order_id_str);
        	$order_id_str  =  str_replace(",'0'", "",$order_id_str);
        	$order_id_str  =  str_replace("'", "",$order_id_str);

        	//var_dump($order_id_str);
        	//exit();

        	if(!empty($order_id_str)){
        		//往公海插入数据
        		$sql="insert into ".$this->common->table('gonghai_order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('order')." where order_id in(".$order_id_str.")
                                		and order_id not in( select ".$this->common->table('gonghai_order').".order_id from ".$this->common->table('gonghai_order')." where ".$this->common->table('gonghai_order').".order_id in(".$order_id_str.") )";
        		if($this->db->query($sql)){
        			if($this->db->query("delete from ".$this->common->table('order')." where order_id in(".$order_id_str.")")){
        				$bol =  true;
        			}
        			if($this->db->query("update ".$this->common->table('gonghai_order')." set first_timeout=".time().",gonghai_type='gonghai' where order_id in(".$order_id_str.")")){
        				//掉入公海记录
        				$order_id_diaoru = explode(",",$order_id_str);
        				foreach($order_id_diaoru as $order_id_diaoru_temp){
        					if($order_id_diaoru_temp != 0){
        						foreach($order_reanai_sum_res as $res_temp){
        							if(strcmp($res_temp['order_id'],$order_id_diaoru_temp) == 0){
        								$this->db->query("insert into ".$this->common->table('gonghai_log')."(order_id,action_type,action_name,action_time,is_come) values('".$res_temp['order_id']."','掉入公海','','".time()."',0)");
        								break;
        							}
        						}
        					}
        				}
        			}
        		}
        	}
        	return $bol;
        }

        /**
         * 捞取公海指定部分数据到预约
         * @return boolean
         */
        public function come_from_gonghaiss(){
        	$order_id_str = 37;
        	$query1="insert into ".$this->common->table('order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('gonghai_order')." where hos_id in(".$order_id_str.")";
        	if($this->db->query($query1)){
        		//公海捞出 记录日志
        		$gonghai_order_lq_res=$this->common->getAll(" select order_id,admin_id,admin_name from ".$this->common->table('gonghai_order')." where hos_id in(".$order_id_str.") ");
        		foreach($gonghai_order_lq_res as $gonghai_order_lq_res_temp){
        			$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$gonghai_order_lq_res_temp['order_id'],'action_type'=>'从公海捞取','action_name'=>$gonghai_order_lq_res_temp['admin_name'],'action_id'=>$gonghai_order_lq_res_temp['admin_id'],'action_time'=>time(),'is_come'=>1));
        		}
        		//$this->db->query("delete from ".$this->common->table('gonghai_order')." where order_id in(".$order_id_str.")");
        	}
        	exit;
        }

        /**
         * 如果公海中的预约到诊了，则把相关信息从公海中提取到正常预约中
         * 把公海中已经到诊的用户捞到个人预约中
         * @return boolean
         */
        public function come_from_gonghai(){
        	$bol = false;
        	$order_res=$this->common->getAll("select order_id from ".$this->common->table('gonghai_order')." where is_come=1 ");
        	$order_id_str = '';
        	foreach($order_res as $temp){
        		if(empty($order_id_str)){
        			$order_id_str  = $temp['order_id'];
        		}else{
        			$order_id_str .= ','.$temp['order_id'];
        		}
        	}
        	if(!empty($order_id_str)){
        		$this->db->query("delete from ".$this->common->table('order')." where order_id in(".$order_id_str.")");

        		$query1="insert into ".$this->common->table('order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('gonghai_order')." where order_id in(".$order_id_str.")";
        		if($this->db->query($query1)){
        			//公海捞出 记录日志
        			$gonghai_order_lq_res=$this->common->getAll(" select order_id,admin_id,admin_name from ".$this->common->table('gonghai_order')." where order_id in(".$order_id_str.") ");
        			foreach($gonghai_order_lq_res as $gonghai_order_lq_res_temp){
        				$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$gonghai_order_lq_res_temp['order_id'],'action_type'=>'从公海捞取','action_name'=>$gonghai_order_lq_res_temp['admin_name'],'action_id'=>$gonghai_order_lq_res_temp['admin_id'],'action_time'=>time(),'is_come'=>1));
        			}
        			$this->db->query("delete from ".$this->common->table('gonghai_order')." where order_id in(".$order_id_str.")");
        			return true;
        		}
        	}
        	return $bol;

        }

        function order_info($order_id)
	{
		$where = '1';
		if(!empty($_COOKIE["l_hos_id"]))
		{
			$where .= " AND o.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";
		}

		if(!empty($_COOKIE["l_keshi_id"]))
		{
			$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
		}
		$sql = "SELECT o.*, p.*, p.pat_id AS p_id
				FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where AND o.order_id = $order_id";
		$row = $this->common->getRow($sql);
		return $row;
	}

}