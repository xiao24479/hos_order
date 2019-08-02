<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页model
class Order_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function from_list()
	{
		$sql = "SELECT f.*, h.hos_name, k.keshi_name
				FROM " . $this->common->table('order_from') . " f
				LEFT JOIN " . $this->common->table('hospital') . " h ON h.hos_id = f.hos_id
				LEFT JOIN " . $this->common->table('keshi') . " k ON k.keshi_id = f.keshi_id
				ORDER BY f.from_order ASC, f.from_id DESC";
		$row = $this->common->getAll($sql);
		$row = getDataTree($row, 'from_id', 'parent_id', 'child');
		return $row;
	}

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
        //获取本项目组的医生名单，以便导医勾选，此为代码开始
        function doctor_list($hos_arr,$keshi_arr){
            $where=" 1 AND r.rank_type=1 AND a.is_pass=1";
            if(!empty($hos_arr)){
                $where.=" AND p.hos_id in (".$hos_arr.")";
            }
            if(!empty($keshi_arr)){
                $where.=" AND p.hos_id in (".$keshi_arr.")";

            }

            $sql="SELECT distinct(a.admin_name) FROM ".$this->common->table('admin')." a left join ".$this->common->table('rank')." r on a.rank_id=r.rank_id"
                    . " left join ".$this->common->table('rank_power')." p on r.rank_id=p.rank_id where ".$where;



            $rs=$this->common->getAll($sql);
            return $rs;
        }
         //获取本项目组的医生名单，以便导医勾选，此为代码结束
	function hospital_order_list()
	{
		$where = 1;

		if(!empty($_COOKIE["l_hos_id"]))
		{
			$where .= " AND (hos_id IN (" . $_COOKIE["l_hos_id"] . ") OR hos_id = 0)";
		}
		//$sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE $where  and ask_auth != 1 ORDER BY hos_id ASC";
		$sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE $where  and ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc ";

		return $this->common->getAll($sql);
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
                //以下代码没什么意义
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


	//获取所有医院
	function hospital_order_list_all()
	{

		$sql = "SELECT * FROM " . $this->common->table('hospital') . " WHERE ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc ";

		return $this->common->getAll($sql);
	}

	//获取所有科室
	function keshi_order_list_all($hos_id = 0)
	{
		$where = 1;

		if(!empty($hos_id))
		{
			$where .= " AND hos_id = $hos_id";
		}

		$sql = "SELECT *
				FROM " . $this->common->table('keshi') . "
		 		WHERE $where ORDER BY keshi_order ASC, hos_id ASC";
		$arr = $this->common->getAll($sql);
                //以下代码没什么意义
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

	function jb_order_list($keshi_id = 0)
	{
		$sql = "SELECT *  FROM " . $this->common->table('jibing') . "  WHERE jb_id  in(SELECT jb_id  FROM " . $this->common->table('keshi_jibing') . "  WHERE keshi_id =  ".$keshi_id." )  ORDER BY jb_id ASC ";
		$jibing = $this->common->getAll($sql);
        //以下代码没什么意义
		$jibing = array();
		foreach($jibing as $val)
		{
			$keshi[$val['jb_id']]['jb_id'] = $val['jb_id'];
			$keshi[$val['jb_id']]['jb_name'] = $val['jb_name'];
			$keshi[$val['jb_id']]['parent_id'] = $val['parent_id'];
		}
		return $jibing;
	}

	function type_order_list()
	{
		$where = 1;

		if(!empty($_COOKIE["l_hos_id"]))
		{
			$where .= " AND ( hos_id IN (" . $_COOKIE["l_hos_id"] . ") or hos_id =0 )";
		}

		if(!empty($_COOKIE["l_keshi_id"]))
		{
			$where .= " AND ( keshi_id IN (" . $_COOKIE["l_keshi_id"] . ") or keshi_id =0 )";
		}

		//$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('order_type') . " WHERE $where  ORDER BY CONVERT(type_name USING gbk) asc");
		$arr = $this->common->getAll("SELECT * FROM " . $this->common->table('order_type') . " WHERE $where  ORDER BY type_order asc");
		return $arr;
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
				WHERE $where AND a.is_pass=1 AND (r.rank_type = 2 or r.rank_type=5)  ORDER BY CONVERT(a.admin_name USING gbk) asc";
		$arr = $this->common->getAll($sql);
		return $arr;
	}


//	function order_count($where)
//	{
//		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao,SUM(IF(r.mark_type=3 and r.mark_content='', 1, 0)) AS not_hf
//		        FROM " . $this->common->table('order') . " o
//				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id
//				WHERE $where";
//		$row = $this->common->getRow($sql);
//		return $row;
//	}
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
            if($info['pat_name']!=$update_info['pat_name']){
                $message.="患者姓名修改为：".$update_info['pat_name']."；";
            };
            if($info['pat_phone']!=$update_info['phone']){
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
            if($info['admin_name']!=$update_info['admin_name']){
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

        function order_count($where)
	{
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where";
		$row = $this->common->getRow($sql);
                $sql1= "SELECT COUNT(distinct(r.order_id)) AS count
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id
				WHERE $where and r.mark_type=3 and o.is_come=0";
               $row1=$this->common->getRow($sql1);
                 $sql2= "SELECT COUNT(distinct(o.order_id)) AS count
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id
				WHERE $where and o.is_come=1 ";
                 $row2=$this->common->getRow($sql2);
               $row['not_hf']=$row['count']-$row1['count']-$row2['count'];
		return $row;
	}



 /**
	      检查7天有效时间的 预约订单查询方法  统计数量

	   ***/
        function seven_time_query_order_count($where,$parm= array())
	{

		$row  = array();
		//查询时间限制为 前后3天，前3天+后3天+今天=7天
		if(isset($parm['order_query_seven_data']) && isset($parm['order_check_seven_data']) &&  $parm['order_query_seven_data'] == '0' && $parm['order_check_seven_data'] == '0'){
			//如果当前搜索的开始时间 比 当前时间加上3天之后的时间还要大， 则不搜索指定科室的数据
		   //如果当前搜索结束时间 比 当前时间减去3天之后的时间还要小， 则不搜索指定科室的数据
		 	 $whereTwo = ' 1= 1';
			  if($parm['huanzhe_check'] == '0'){
				   $whereTwo = " 1  AND o.order_time >= " . $parm['w_start'] . " AND o.order_time <= " . $parm['w_end'];
		      }

			  if($parm['huanzhe_check'] == '1'){// 使用用户信息来搜索，但是用户信息的预到时间必须满足当前7天以内的数据
				  $parm_str_s = " AND (case
				 when o.keshi_id in(".$parm['keshi_id_str'].")
				 then
					case
					when  o.order_time between unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id in(".$parm['keshi_id_str'].")
					when   o.order_time not between  unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and       unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id not in(".$parm['keshi_id_str'].")
					 else
						1 = 1
					end
				else
				  1 = 1
				end ) ";
		     }else{// 使用非用户个人信息 来搜索 需要对时间范围和其他非用户信息的条件来 刷选
			     $parm_str_s = " AND (case
				 when o.keshi_id in(".$parm['keshi_id_str'].")
				 then
					case
					when  ".$parm['w_end']." < unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) or ".$parm['w_start']." > unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id not in(".$parm['keshi_id_str'].")
					when
						".$parm['w_start']." >= unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  ".$parm['w_end']." <= unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND o.order_time >= ".$parm['w_start']." AND o.order_time <= ".$parm['w_end']."
					when
						".$parm['w_end']." BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND o.order_time BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second))  AND ".$parm['w_end']."
					when
						".$parm['w_start']." BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND o.order_time BETWEEN  ".$parm['w_start']."  AND unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					else
						1 AND o.order_time BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second))  AND unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					end
				else
					". $whereTwo ."
				end ) ";//  过滤条件参数

			 }
		   $sql= "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where " .$parm_str_s;
		  $row = $this->common->getRow($sql);

		  $sql1= "SELECT COUNT(distinct(r.order_id)) AS count FROM " . $this->common->table('order') . " o
			LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id WHERE $where and r.mark_type=3 and o.is_come=0 ". $parm_str_s;
		   $row1=$this->common->getRow($sql1);

		   $sql2= "SELECT COUNT(distinct(o.order_id)) AS count FROM " . $this->common->table('order') . " o
			LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id WHERE $where and o.is_come=1 ". $parm_str_s;
		   $row2=$this->common->getRow($sql2);
		}else{
			if(isset($parm['huanzhe_check']) && isset($parm['order_type']) && $parm['huanzhe_check'] == '0'){
				    if($parm['order_type'] == 1)
					{//预约登记时间
						$where .= " AND o.order_addtime >= ".$parm['w_start']." AND o.order_addtime <= ".$parm['w_end'];
					}
					elseif($parm['order_type'] == 2)
					{//预约时间
						$where .= " AND o.order_time >= " . $parm['w_start'] . " AND o.order_time <= " . $parm['w_end'];
					}
					elseif($parm['order_type'] == 3)
					{//实到时间<br />
						$where .= " AND o.come_time >= " . $parm['w_start'] . " AND o.come_time <= " . $parm['w_end'];
					}
					elseif($parm['order_type'] == 4)
					{
						//医生排班时间
						$where .= " AND o.doctor_time >= " . $parm['w_start'] . " AND o.doctor_time <= " . $parm['w_end'];
					}
		    }

			$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
					FROM " . $this->common->table('order') . " o
					LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
					WHERE $where";

			$row = $this->common->getRow($sql);

			$sql1= "SELECT COUNT(distinct(r.order_id)) AS count FROM " . $this->common->table('order') . " o
			LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id WHERE $where and r.mark_type=3 and o.is_come=0";
		   $row1=$this->common->getRow($sql1);
			 $sql2= "SELECT COUNT(distinct(o.order_id)) AS count FROM " . $this->common->table('order') . " o
			LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id LEFT JOIN ".$this->common->table('order_remark')." r ON o.order_id=r.order_id WHERE $where and o.is_come=1 ";
			 $row2=$this->common->getRow($sql2);

		}

	   $row['not_hf']=$row['count']-$row1['count']-$row2['count'];
		return $row;
	}



	function message_count($where)
	{
		$sql = "SELECT COUNT(*) AS count
		        FROM " . $this->common->table('order_mes') . " o
				LEFT JOIN " . $this->common->table('pat_data') . " p ON p.pat_id = o.pat_id
				WHERE $where";
		$row = $this->common->getRow($sql);
		return $row;
	}

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



	 function baby_list($time=''){
		      //定义星期 数据
            $weekarray=array("日","一","二","三","四","五","六");

            $now_time=date("Y-m-d",time());
            if(isset($time)||!empty($time)){
                $get_time=$time;
                $t=strtotime($get_time.'00:00:00');
            }else{
            	$t=strtotime($now_time.'00:00:00');
            }
            $start_day = date("Y-m-d",$t);
            $next_day=date("Y-m-d",$t+(30*24*60*60));
            $query="select bt.*,b.id as bid,b.time_start,b.time_end,b.jb_id from ".$this->common->table('baby_select_type')." as bt,".$this->common->table('baby_type')." as b where bt.days between '".$start_day."' AND '".$next_day."' and bt.baby_type_id = b.id order by bt.days,b.time_start asc ";
		    $baby_select_type=$this->common->getAll($query);

			$data = array();
			$i =0 ;
			while($i<30){
				$data_temp = array();
				$time_query= $t+($i*24*60*60);
				$time_day = date("Y-m-d",$time_query);

				$data_temp = array();
				$data_temp ['date'] = $time_day.'(星期'.$weekarray[date("w",$time_query)].'）';
				$sum =0 ;
				$temp = array();
				foreach( $baby_select_type as $time_start_key => $time_start_temp){
					if(strcmp($time_start_temp['days'],$time_day) ==0 ){
						 $sum =$sum+intval($time_start_temp['sum']);
						 $temp[] =$time_start_temp;
					}
				}
				$data_temp ['all_sum'] = $sum;
				$data_temp ['data'] = $temp;
				$data[] =  $data_temp;
				$i++;
		    }
			 $data['baby_select_type'] = $data;
			 //var_dump(json_encode($data['baby_select_type']) );exit;
			 $query="select id,jb_id,time_start,time_end from  ".$this->common->table('baby_type')." where jb_id = 300 order by id asc";
			$data['baby_type'] = $this->common->getAll($query);
			$data['order_time']  = $start_day;
		    return $data;
        }


        function siwei_list($time=''){

            $now_time=date("Y-m-d",time());
            if(isset($time)||!empty($time)){
                $get_time=$time;
                $t=strtotime($get_time.'00:00:00');

            }else{
            $t=strtotime($now_time.'00:00:00');
            }
             $data=array();
             $dat=array();


            $start_time=$t;

            $next_time=$start_time+(30*24*60*60);

            if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
            	$sql ="select ot.order_id from  ".$this->common->table('order_out')." as ot,".$this->common->table('order')." as o  where o.order_id = ot.order_id and o.hos_id in(".$_COOKIE["l_hos_id"].")";
            }else{
            	$sql = "select ot.order_id from  ".$this->common->table('order_out')." as ot";
            }
            $order_out_data = $this->common->getAll($sql);
            $order_out_str = '';
            foreach($order_out_data as $order_out_data_temp){
            	if(empty($order_out_str)){
            		$order_out_str = $order_out_data_temp['order_id'];
            	}else{
            		$order_out_str .= ','.$order_out_data_temp['order_id'];
            	}
            }
            // if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
            // 	if(empty($order_out_str)){
            // 		$query="select order_time,order_time_duan from ".$this->common->table('order')." where jb_parent_id=149 AND  hos_id in(".$_COOKIE["l_hos_id"].") AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            // 	}else{
            // 		$query="select order_time,order_time_duan from ".$this->common->table('order')." where jb_parent_id=149 and order_id not in(".$order_out_str.") AND  hos_id in(".$_COOKIE["l_hos_id"].") AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            // 	}
            // }else{
            // 	if(empty($order_out_str)){
            // 		$query="select order_time,order_time_duan from ".$this->common->table('order')." where jb_parent_id=149  AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            // 	}else{
            // 		$query="select order_time,order_time_duan from ".$this->common->table('order')." where jb_parent_id=149 and order_id not in(".$order_out_str.")  AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            // 	}
            // }

            if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
            	if(empty($order_out_str)){
            		$query="select order_time,order_time_duan from ".$this->common->table('order')." where (jb_parent_id=149 OR jb_id=434) AND  hos_id in(".$_COOKIE["l_hos_id"].") AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            	}else{
            		$query="select order_time,order_time_duan from ".$this->common->table('order')." where (jb_parent_id=149 OR jb_id=434)  and order_id not in(".$order_out_str.") AND  hos_id in(".$_COOKIE["l_hos_id"].") AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            	}
            }else{
            	if(empty($order_out_str)){
            		$query="select order_time,order_time_duan from ".$this->common->table('order')." where (jb_parent_id=149 OR jb_id=434)   AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            	}else{
            		$query="select order_time,order_time_duan from ".$this->common->table('order')." where (jb_parent_id=149 OR jb_id=434)  and order_id not in(".$order_out_str.")  AND order_time>=".$start_time." AND order_time<".$next_time." limit 0,1000";
            	}
            }
			 $a=$this->common->getAll($query);
//
            $array=array();
           foreach($a as $c){

              $str1=!empty($c['order_time_duan'])?trim(str_replace('：',':',$c['order_time_duan'])):"00:00:00";
              $str=$c['order_time'];
              $aa=date("Y-m-d",$str);
              $bb=$aa.$str1;
              $cc=strtotime($bb);//$cc代表的是预约时间和预约详细时间的组合起来的值的时间戳
              $array[]=$cc;

              // echo $c['order_time']."<br/>";
//               echo $c['count(*)'];
           }


         sort($array);


         $day=array();
         for($i=0;$i<count($array);$i++){
             for($j=0;$j<30;$j++){
                 $a=($j*24*60*60)+24*60*60+$start_time;
                 $b=($j*24*60*60)+$start_time;
                 if($array[$i]<$a&&$array[$i]>=$b){

                     $day[$j][]=$array[$i];
                 }
             }

         }


         $num=array();

         for($aa=0;$aa<30;$aa++){
               $day_t=array();
               @$c=$day[$aa];
             for($i=0;$i<count($c);$i++){

                 $date=date("Y-m-d",$c[$i]);
                 $first_time=strtotime($date);

             for($j=0;$j<21;$j++){
                 $a=($j*30*60)+8*60*60+$first_time+1800;
                 $b=($j*30*60)+8*60*60+$first_time;
                 if($c[$i]<$a&&$c[$i]>=$b){

                     $day_t[$j][]=$c[$i];
                 }
             }

         }

            $data[$aa][]=$day_t;

         }

         $dat['order_time']=date("Y-m-d",$t);
         $dat['data']=$data;


            return $dat;



        }
	function order_list($where, $first = 0, $size = 20, $orderby)
	{

		$sql = "SELECT og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id,o.hzsjd, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone,p.pat_weixin,p.pat_qq,p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
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

			//东莞医生不能看电话
			$hosArray  = array(6);
            if (in_array($val['hos_id'],$hosArray) && $_COOKIE['l_rank_type'] == 1) {

                if (!empty($val['pat_phone'])) {
                    $start_str = substr($val['pat_phone'],0,3);
                    $end_str = substr($val['pat_phone'],-4);
                    $arr[$val['order_id']]['pat_phone']  =  $start_str.'****'.$end_str;
                }
                if (!empty($val['pat_phone1'])) {
                    $start_str = substr($val['pat_phone1'],0,3);
                    $end_str = substr($val['pat_phone1'],-4);
                    $arr[$val['order_id']]['pat_phone1']  =  $start_str.'****'.$end_str;
                }

            } else {
                $arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
                $arr[$val['order_id']]['pat_phone1'] =$val['pat_phone1'];
            }

			$arr[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
			$arr[$val['order_id']]['pat_qq'] = $val['pat_qq'];
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
			$arr[$val['order_id']]['hzsjd'] = $val['hzsjd'];

		}

		return $arr;
	}


 /**
	      检查7天有效时间的 预约订单查询方法

	   ***/
	   function seven_time_query_order_list($where, $first = 0, $size = 20, $orderby,$parm = array())
	{

		/**
		echo "<br/>开始<br/>";
		echo date("Y-m-d H:i:s");
		echo "<br/>";
		**/

		$row  = array();
		//查询时间限制为 前后3天，前3天+后3天+今天=7天
		if(false){
			//如果当前搜索的开始时间 比 当前时间加上3天之后的时间还要大， 则不搜索指定科室的数据
		   //如果当前搜索结束时间 比 当前时间减去3天之后的时间还要小， 则不搜索指定科室的数据
		  	 $whereTwo = ' 1=1 ';
			  if($parm['huanzhe_check'] == '0'){
				   $whereTwo = " 1 AND o.order_time >= " . $parm['w_start'] . " AND o.order_time <= " . $parm['w_end'];
		      }

		  $sql = " SELECT o.ireport_order_id,o.ireport_msg,og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
				WHERE $where ";

		     if($parm['huanzhe_check'] == '1'){// 使用用户信息来搜索，但是用户信息的预到时间必须满足当前7天以内的数据
				  $sql .= " AND (case
				 when o.keshi_id in(".$parm['keshi_id_str'].")
				 then
					case
					when  o.order_time between unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id in(".$parm['keshi_id_str'].")
					when   o.order_time not between  unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and       unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id not in(".$parm['keshi_id_str'].")
					 else
						1 = 1
					end
				else
				  1 = 1
				end ) ";
		     }else{// 使用非用户个人信息 来搜索 需要对时间范围和其他非用户信息的条件来 刷选
				 $sql .= " AND (case
				 when o.keshi_id in(".$parm['keshi_id_str'].")
				 then
					case
					when  ".$parm['w_end']." < unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) or ".$parm['w_start']." >  unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND keshi_id not in(".$parm['keshi_id_str'].")
					when
						".$parm['w_start']." >= unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  ".$parm['w_end']." <= unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						".$whereTwo."
					when
						".$parm['w_end']." BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND o.order_time BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second))  AND ".$parm['w_end']."
					when
						".$parm['w_start']." BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second)) and  unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					then
						1 AND o.order_time BETWEEN  ".$parm['w_start']."  AND unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1) second))
					else
						1 AND o.order_time BETWEEN unix_timestamp(DATE_SUB(CURDATE(), interval (60*60*24*3-1) second))  AND unix_timestamp(DATE_ADD(CURDATE(), interval (60*60*24*4-1 ) second))
					end
				else
				  ".$whereTwo."
				end ) ";
			 }
			 $sql .= $orderby . " LIMIT $first, $size";
		}else{
			if($parm['huanzhe_check'] == '0'){
				if($parm['order_type'] == 1)
				{//预约登记时间
					$where .= " AND o.order_addtime >= ".$parm['w_start']." AND o.order_addtime <= ".$parm['w_end'];
				}
				elseif($parm['order_type'] == 2)
				{//预约时间
					$where .= " AND o.order_time >= " . $parm['w_start'] . " AND o.order_time <= " . $parm['w_end'];
				}
				elseif($parm['order_type'] == 3)
				{//实到时间<br />
					$where .= " AND o.come_time >= " . $parm['w_start'] . " AND o.come_time <= " . $parm['w_end'];
				}
				elseif($parm['order_type'] == 4)
				{
					//医生排班时间
					$where .= " AND o.doctor_time >= " . $parm['w_start'] . " AND o.doctor_time <= " . $parm['w_end'];
				}
		    }

		  $sql = "SELECT o.ireport_order_id,o.ireport_msg,og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
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


		}

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
			// $arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
			// $arr[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
			// if(isset($parm['rank_id'])){
			// 	/**2016 12 07 将属于仁爱 产科的，预约途径为其他的数据，如果是 仁爱妇科咨询员查询，则将电话号码后四位隐藏。产科健康中心干事 查询不影响**/
			// 	 if($val['keshi_id'] == 7 && $val['from_parent_id'] == 23 && ($parm['rank_id'] == 21 || $parm['rank_id'] == 28)){
			// 		 	$arr[$val['order_id']]['pat_phone'] = substr($val['pat_phone'],0, -4)."****";
			// 			$arr[$val['order_id']]['pat_phone1'] =substr($val['pat_phone1'],0, -4)."****";
			// 	 }
			// }

			//所有项目电话号码都隐藏除了咨询自己的患者
			$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
			if (($val['admin_id'] != $_COOKIE['l_admin_id']) && ($_COOKIE['l_admin_action'] != 'all') && !in_array(198, $l_admin_action)) {
				$arr[$val['order_id']]['pat_phone'] = substr($val['pat_phone'],0, -4)."****";
				$arr[$val['order_id']]['pat_phone1'] =substr($val['pat_phone1'],0, -4)."****";
			} else {
				$arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
				$arr[$val['order_id']]['pat_phone1'] =$val['pat_phone1'];
			}

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

        //帮助导医从公海中提取相关的数据 代码开始
        function dy_order_list($where, $first = 0, $size = 20, $orderby)
	{

		$sql = "SELECT o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('gonghai_order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				WHERE $where " .
				$orderby . "
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();

		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
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
         //帮助导医从公海中提取相关的数据 代码开始

	function message_list($where, $first = 0, $size = 20, $orderby)
	{
		$sql = "SELECT o.order_id, o.pat_id, p.pat_name, p.pat_sex, o.from_value,a.admin_name,p.pat_age,
				p.pat_address, p.pat_phone, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, o.admin_id,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.jb_parent_id,o.order_null_time
				FROM " . $this->common->table('order_mes') . " o
				LEFT JOIN " . $this->common->table('pat_data') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = o.admin_id
				WHERE $where " .
				$orderby . "
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();

		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
			$arr[$val['order_id']]['pat_name'] = $val['pat_name'];
			$arr[$val['order_id']]['pat_phone'] = $val['pat_phone'];
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
			$arr[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
			if(!empty($val['order_time']))
			{
				$arr[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
			}
			else
			{
				$arr[$val['order_id']]['order_time'] = $val['order_null_time'];
			}
			$arr[$val['order_id']]['hos_id'] = $val['hos_id'];
			$arr[$val['order_id']]['keshi_id'] = $val['keshi_id'];
			$arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
			$arr[$val['order_id']]['from_value'] = $val['from_value'];
			$arr[$val['order_id']]['admin_name'] = $val['admin_name'];

		}
		return $arr;
	}
	function order_swt_count($where)
	{
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.come_time, 1, 0)) AS come, SUM(IF(o.doctor_time, 1, 0)) AS dao
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('order_swt') . " s ON s.order_id = o.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where";
		$row = $this->common->getRow($sql);
		return $row;
	}

	function order_swt_list($where, $first = 0, $size = 20, $orderby)
	{
		$sql = "SELECT o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, s.swt_keyword, s.swt_url, s.swt_type
				FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('order_swt') . " s ON s.order_id = o.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				WHERE $where " .
				$orderby . "
				LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		$arr = array();

		foreach($row as $val)
		{
			$arr[$val['order_id']]['order_id'] = $val['order_id'];
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
			$arr[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
			$arr[$val['order_id']]['jb_id'] = $val['jb_id'];
			$arr[$val['order_id']]['order_type'] = $val['order_type'];
			$arr[$val['order_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['order_id']]['pat_id'] = $val['pat_id'];
			$arr[$val['order_id']]['doctor_name'] = $val['doctor_name'];
			$arr[$val['order_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['order_id']]['is_come'] = $val['is_come'];
			$arr[$val['order_id']]['swt_keyword'] = $val['swt_keyword'];
			$arr[$val['order_id']]['swt_url'] = $val['swt_url'];
			$arr[$val['order_id']]['swt_type'] = $val['swt_type'];
		}

		return $arr;
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

	function rank_type()
	{
		$rank_id = $_COOKIE['l_rank_id'];
		$rank = $this->common->static_cache('read', "rank_arr", 'rank_arr');
		return $rank[$rank_id]['rank_type'];
	}

	function order_count_data($start_time, $end_time,$where = '',$tag = 0, $order_type = 1,$str='%d')
	{

		$orderby = '';
		/* 时间条件 */

		if($order_type == 1)

		{

			$where .= " AND o.order_addtime >= " . $start_time . " AND o.order_addtime <= " . $end_time;//登记时间

			$orderby .= 'o.order_addtime DESC ';

			$time = 'order_addtime';

		}

		elseif($order_type == 2)

		{

			$where .= " AND o.order_time >= " . $start_time . " AND o.order_time <= " . $end_time;//预到时间

			$orderby .= 'o.order_time DESC ';

			$time = 'order_time';

		}

		elseif($order_type == 3)

		{

			$where .= " AND o.come_time >= " . $start_time . " AND o.come_time <= " . $end_time;//实到时间

			$orderby .= 'o.come_time DESC ';

			$time = 'come_time';

		}

		if(0 == $tag){
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, FROM_UNIXTIME( o.{$time}, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				WHERE $where group by tag";
		}
		if(1 == $tag){
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come, k.keshi_name
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN " . $this->common->table('keshi') . " k ON k.keshi_id = o.keshi_id
				WHERE $where group by k.keshi_name";
		}
		if(2 == $tag){
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.is_come, 1, 0)) AS come,FROM_UNIXTIME( o.{$time}, '{$str}' ) as tag
		        FROM " . $this->common->table('order_remark') . " k
				LEFT JOIN " . $this->common->table('order') . " o ON o.order_id = k.order_id
				WHERE $where and k.mark_type = 3 group by tag";
		}
		if(3 == $tag){
			$sql = "SELECT count(o.order_id) as count,  k.type_id
		        FROM " . $this->common->table('order') . " o
				LEFT JOIN  (select a.order_id,a.type_id,a.mark_time from " . $this->common->table('order_remark') . " a where a.mark_type=3 and a.mark_time = (select max(mark_time) from ".$this->common->table('order_remark')." where order_id = a.order_id and type_id<>0)) k ON o.order_id = k.order_id
				WHERE $where and o.is_come=0 group by k.type_id";
		}
		if(4 == $tag){
		$sql = "SELECT COUNT(*) AS count, SUM(IF(o.order_time=0,1,0)) AS come, FROM_UNIXTIME( o.{$time}, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				WHERE $where group by tag";
		}

		if(5 == $tag){
		$sql = "SELECT COUNT(*) AS count, o.jb_parent_id, SUM(IF(o.is_come, 1, 0)) AS come
		        FROM " . $this->common->table('order') . " o
				WHERE $where group by o.jb_parent_id order by count";
		}

		if(6 == $tag){
		$sql = "SELECT COUNT(*) AS count, o.jb_parent_id
		        FROM " . $this->common->table('order') . " o
				WHERE $where and is_come = 1 group by o.jb_parent_id order by count";
		}
		if(7 == $tag){
			$sql = "SELECT COUNT(*) AS count,COUNT(distinct o.order_id) AS oid, count(distinct (IF(o.is_come = 0,o.order_id , 0))) AS come,FROM_UNIXTIME( o.{$time}, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				, " . $this->common->table('order_remark') . " k
				WHERE $where and k.mark_type = 3 and o.order_id = k.order_id group by tag";
		}
		$row = $this->common->getAll($sql);
		return $row;
	}
	function order_data($start_time, $end_time,$hos_id)
	{
		// 统计预约数据
		$sql = "SELECT COUNT(*) AS count,SUM(IF(o.order_time=0,1,0)) AS mo
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id and o.order_addtime <= " . $start_time . " AND o.order_addtime >= " . $end_time ;
		$add_count = $this->common->getRow($sql);
		// 统计来院人数
		$sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time ;
		$come_count = $this->common->getOne($sql);
		// 统计预到人数
		$sql = "SELECT COUNT(*) AS count,SUM(IF(o.is_come, 1, 0)) AS will_c
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time ;
		$order_count = $this->common->getRow($sql);
		return array('count'=>$add_count['count'],'mo'=>$add_count['mo'],'will_come'=>$order_count['count'],'will_c'=>$order_count['will_c'],'come'=>$come_count);

	}
	function order_data_type($start_time, $end_time,$hos_id,$str)
	{
		$sql = "SELECT COUNT(*) AS count,FROM_UNIXTIME( o.order_addtime, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id and o.order_addtime <= " . $start_time . " AND o.order_addtime >= " . $end_time
				." group by tag";
		$add_count = $this->common->getAll($sql);
		// 统计来院人数
		$sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count,FROM_UNIXTIME( o.come_time, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time
				." group by tag";
		$come_count = $this->common->getAll($sql);
		// 统计预到人数
		$sql = "SELECT COUNT(*) AS count,FROM_UNIXTIME( o.order_time, '{$str}' ) as tag
		        FROM " . $this->common->table('order') . " o
				WHERE o.hos_id = $hos_id AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time
				." group by tag";
		$order_count = $this->common->getAll($sql);
		return array('count'=>$add_count,'will_come'=>$order_count,'come'=>$come_count);
	}
	function pat_list($start_time, $end_time,$where = '',$first = 0, $size = 20){


		$sql = "select * from " . $this->common->table('pat_enter') . " where $where AND add_time >= " . $start_time . " AND add_time <= " . $end_time ." order by add_time desc LIMIT $first, $size";
		$row = $this->common->getAll($sql);
		return $row;
	}
	function pat_list_count($start_time, $end_time,$where = ''){


		$sql = "select count(*) count from " . $this->common->table('pat_enter') . " where $where AND add_time >= " . $start_time . " AND add_time <= " . $end_time;
		$row = $this->common->getOne($sql);
		return $row;
	}

	/**
	 * 获取黑名单患者
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
	function getBlack($where){

		$row  = array();

		$sql = "SELECT o.ireport_order_id,o.ireport_msg,og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
			p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin,p.pat_blacklist, o.from_parent_id, o.from_id, o.is_come,
			o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
			o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name,b.add_time as b_add_time,b.admin_name as black_admin,b.admin_id as b_admin_id
			FROM " . $this->common->table('order_black') . " b
			LEFT JOIN " . $this->common->table('order') . " o ON b.order_id = o.order_id
			LEFT JOIN " . $this->common->table('order_data') . " d ON b.order_id = d.order_id
			LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = b.pat_id
			LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = b.order_id
			LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = b.order_id
			WHERE $where ";


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
			if(isset($parm['rank_id'])){
				/**2016 12 07 将属于仁爱 产科的，预约途径为其他的数据，如果是 仁爱妇科咨询员查询，则将电话号码后四位隐藏。产科健康中心干事 查询不影响**/
				 if($val['keshi_id'] == 7 && $val['from_parent_id'] == 23 && ($parm['rank_id'] == 21 || $parm['rank_id'] == 28)){
					 	$arr[$val['order_id']]['pat_phone'] = substr($val['pat_phone'],0, -4)."****";
						$arr[$val['order_id']]['pat_phone1'] =substr($val['pat_phone1'],0, -4)."****";
				 }
			}
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
			$arr[$val['order_id']]['add_time'] = $val['z_name'];
			$arr[$val['order_id']]['black_admin'] = $val['black_admin'];
			$arr[$val['order_id']]['b_admin_id'] = $val['b_admin_id'];
			$arr[$val['order_id']]['b_add_time'] = $val['b_add_time'];
		}
		return $arr;
	}

}