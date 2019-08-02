<?php
//判断输入的网址是不是存在，不存在退出并且弹出错误信息！
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页model
/*
 *本模型文件中主要有7个方法：分别如下：
 * action_info($act_id)
 * 此方法是通过传入的$act_id从action表中取出相应的数组信息
 *
 * admin_count()
 * 从admin表中获取相关信息，以数组的形式返回，如果没有相关数据返回false
 *
 * admin_info($admin_id)
 * 通过左外连接从admin,admin_info, rank,action表中取出
 * a.*, i.*, r.rank_name ,s.act_id,s.act_name这些数据
 *
 * get_admin_list($first,$size)
 * 此方法可以获取到a.admin_id, a.admin_name,a.admin_username,
 * a.rank_id, a.admin_logintimes, a.is_pass, i.admin_sex, i.admin_tel, i.admin_tel_duan,
 * i.admin_qq, i.admin_email,a.admin_lasttime,a.admin_nowtime,a.admin_logintimes,
 *  r.rank_name其中a代表admin表,i代表admin_info表,r代表rank表基本上已经囊括了用户所有信息。
 *
 *
 * log_list($where,$page,$per_page)
 * 从admin_log表中取出相对应的日志信息$where代表where语句
 * $page,$per_page分别代表起始记录id和获取的记录条数
 *
 * order_count($start_time,$end_time,$hos_id)
 *  根据输入的起始和结束日期，还有医院的编号，对近期
 * 预约人数，预到人数，实到人数进行相关的统计，并且在系统首页显示出来。
 *
 * rank_hos($rank_id)
 * 从rank_power,hospital,keshi 三张表中查找出p.hos_id, p.keshi_id, h.hos_name, k.keshi_name
 * 以数组形式返回。
 *  */
class Index_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function action_info($act_id)
	{
		$sql = "SELECT * FROM " . $this->db->dbprefix . "action WHERE act_id = $act_id";
		$query = $this->db->query($sql);
		$arr = $query->result_array();
		return $arr[0];
	}

	function get_admin_list($first = 0, $size = 20,$rank_id_arr)
	{
             $str1=isset($_COOKIE['l_rank_id'])?intval($_COOKIE['l_rank_id']):0;
            foreach($rank_id_arr as $key=>$value){
                if($value==$str1){

                    unset($rank_id_arr[$key]);
                }


            }



               if(!empty($rank_id_arr)){
                    $rank_arr=implode(',', $rank_id_arr);
               }else{
                   $rank_arr=$str1;
               }

		$where = 1;
		if($_COOKIE['l_admin_action'] != 'all')
		{
			$where .= " AND a.is_pass = 1 AND a.rank_id in($rank_arr)";
		}
		$order = 'a.is_pass desc, r.rank_level ASC';

                //原先获取数据时没有获取到admin_username的相关数据
		$sql = "SELECT a.ireport_admin_id,a.ireport_msg,a.admin_id, a.admin_name,a.admin_username, a.rank_id, a.admin_logintimes, a.is_pass, i.admin_sex, i.admin_tel, i.admin_tel_duan, i.admin_qq, i.admin_email,a.admin_lasttime,a.admin_nowtime,a.admin_logintimes, r.rank_name
				FROM " . $this->common->table('admin') . " a
				LEFT JOIN " . $this->common->table('admin_info') . " i ON i.admin_id = a.admin_id
				LEFT JOIN " . $this->common->table('rank') . " r ON r.rank_id = a.rank_id
				WHERE $where AND a.rank_id != 1
				ORDER BY $order, a.admin_id desc
				LIMIT $first, $size ";
		$arr = $this->common->getAll($sql);

		return $arr;
	}

	function admin_count()
	{
		$where = 1;
		if($_COOKIE['l_admin_action'] == 'all')
		{
			$sql = "SELECT COUNT(*) FROM " . $this->common->table('admin') . " WHERE $where AND rank_id != 1";
		}
		else
		{
			$sql = "SELECT COUNT(*) FROM " . $this->common->table('admin') . " WHERE $where AND rank_id != 1 AND is_pass = 1";
		}
		$count = $this->common->getOne($sql);
		return $count;
	}

	function admin_info($admin_id)
	{
		$sql = "SELECT a.*, i.*, r.rank_name ,s.act_id,s.act_name
		        FROM " . $this->common->table('admin') . " a
				LEFT JOIN " . $this->common->table('admin_info') . " i ON a.admin_id = i.admin_id
				LEFT JOIN " . $this->common->table('rank') . " r ON r.rank_id = a.rank_id
				LEFT JOIN " . $this->common->table('action') . " s ON s.parent_id = r.parent_id
				WHERE a.admin_id = $admin_id";
		$row = $this->common->getRow($sql);
		$row['admin_addtime'] = date("Y-m-d H:i:s", $row['admin_addtime']);
		$row['admin_lasttime'] = date("Y-m-d H:i:s", $row['admin_lasttime']);
		$row['admin_nowtime'] = date("Y-m-d H:i:s", $row['admin_nowtime']);
		return $row;
	}

	function rank_hos($rank_id)
	{
		$sql = "SELECT p.hos_id, p.keshi_id, h.hos_name, k.keshi_name
				FROM " . $this->common->table('rank_power') . " p
				LEFT JOIN " . $this->common->table('hospital') . " h ON p.hos_id = h.hos_id
				LEFT JOIN " . $this->common->table('keshi') . " k ON p.keshi_id = k.keshi_id
				WHERE p.rank_id = $rank_id ORDER BY p.hos_id ASC";
		$row = $this->common->getAll($sql);

		return $row;
	}

	function log_list($where, $page, $per_page)
	{
		$row = $this->common->getAll("SELECT * FROM " . $this->common->table('admin_log') . " WHERE $where ORDER BY log_id DESC LIMIT $page, $per_page");
		$arr = array();
		foreach($row as $val)
		{
			$arr[$val['log_id']]['log_id'] = $val['log_id'];
			$arr[$val['log_id']]['admin_id'] = $val['admin_id'];
			$arr[$val['log_id']]['admin_name'] = $val['admin_name'];
			$arr[$val['log_id']]['log_ip'] = $val['log_ip'];
			$arr[$val['log_id']]['mac_address'] = $val['mac_address'];
			$arr[$val['log_id']]['log_action'] = $val['log_action'];
			$arr[$val['log_id']]['log_time'] = date("Y-m-d H:i:s", $val['log_time']);
			$arr[$val['log_id']]['log_data'] = json_decode($val['log_data']);
		}

		return $arr;
	}

	function order_count($start_time, $end_time,$hos_id = '',$keshi_id='',$rank_id='',$special_keshi_id='')
	{
		$where = 1;

            if($hos_id  == '1_1'){
            	$where .= " AND o.hos_id = 1 AND o.keshi_id = 1";
            } elseif($hos_id  == '45_114_116'){
				$hos_id_array = explode('_',$hos_id);

				if(count($hos_id_array) >2){
					  $where .= " AND o.keshi_id IN (" . $hos_id_array[1].','.$hos_id_array[2] . ")";
				}
				$where .= " AND o.hos_id = ".$hos_id_array[0];
			}else if($hos_id  == '1_7' || $hos_id  == '45_115' || $hos_id  == '1_32' || $hos_id  == '54_153'){
				 $hos_id_array = explode('_',$hos_id);
				if(count($hos_id_array) > 1){
					  $where .= " AND o.keshi_id IN (" . $hos_id_array[1] . ")";
				}
				$where .= " AND o.hos_id = ".$hos_id_array[0];
			}else{

				 $special_keshi_id_array = explode(',',$special_keshi_id);
				if(count($special_keshi_id_array) > 1){
					/***

					 * 2017 06 28 添加功能  开始

					*/



					//判斷是否是人流

					if($special_keshi_id_array[0] == 'js'){

						$where .= " AND o.jb_parent_id IN (" . $special_keshi_id_array[1] . ")";

					}else{

						$where .= " AND o.keshi_id IN (" . $special_keshi_id . ")";

					}



					/***

					 * 2017 06 28 添加功能  结束

					*/
				}else if(!empty($keshi_id)){
					$where .= " AND o.keshi_id=".$keshi_id;
				}else{
					if(!empty($_COOKIE["l_keshi_id"]))
		   {
	$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				  }
				}

				if(!empty($hos_id))
				{
					$where .= " AND o.hos_id = ".$hos_id;
				}
		    }

		$sql = "SELECT COUNT(*) AS count, o.order_addtime
		        FROM " . $this->common->table('order') . " o
				WHERE $where AND o.order_addtime <= " . $start_time . " AND o.order_addtime >= " . $end_time . "
				GROUP BY o.order_addtime";

		$add_count = $this->common->getAll($sql);

		$sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count, o.come_time
		        FROM " . $this->common->table('order') . " o
				WHERE $where AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time . "
				GROUP BY o.come_time";
		$come_count = $this->common->getAll($sql);
                //添加代码开始
                $sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count, o.come_time
		        FROM " . $this->common->table('gonghai_order') . " o
				WHERE $where AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time . "
				GROUP BY o.come_time";
		$gonghai_come_count = $this->common->getAll($sql);
                $sql = "SELECT COUNT(*) AS count, o.order_time
		        FROM " . $this->common->table('gonghai_order') . " o
				WHERE $where AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time . "
				GROUP BY o.order_time";
		$gonghai_order_count = $this->common->getAll($sql);


		//添加代码结束
		$sql = "SELECT COUNT(*) AS count, o.order_time
		        FROM " . $this->common->table('order') . " o
				WHERE $where AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time . "
				GROUP BY o.order_time";
		$order_count = $this->common->getAll($sql);
		$order = array();
		$yue = array();

		if(empty($hos_id) && empty($keshi_id) &&($rank_id == 332 ||  $rank_id == 186 ||  $rank_id == 1 ||  $rank_id == 71 ||  $rank_id == 3  ||  $rank_id == 5  ||  $rank_id == 21  ||  $rank_id == 7  ||  $rank_id == 28  ||  $rank_id == 9 ||  $rank_id == 82) ){
			$order[0]['renai_ck_add'] = isset($order[0]['renai_ck_add'])? $order[0]['renai_ck_add']:0;
			 $order[0]['renai_ck_add_to'] = isset($order[0]['renai_ck_add_to'])? $order[0]['renai_ck_add_to']:0;
			 $order[0]['renai_fk_order'] = isset($order[0]['renai_fk_order'])? $order[0]['renai_fk_order']:0;
			 $order[0]['renai_fk_order_to'] = isset($order[0]['renai_fk_order_to'])? $order[0]['renai_fk_order_to']:0;

			$renai_wu_time = strtotime(date("Y-m-d", $start_time - (86400 * 0)) . " 23:59:59");
			$renai_ling_time = strtotime(date("Y-m-d", $start_time - (86400 * 0)) . " 00:00:00");
			// 单独计算仁爱 预约数据
			$sql = "SELECT o.order_addtime,o.hos_id,o.keshi_id
					FROM " . $this->common->table('order') . " o
					WHERE hos_id = 1 AND keshi_id in(1,7,32)  AND o.order_addtime <= " . $renai_wu_time . " AND o.order_addtime >= " . $renai_ling_time . " order by o.order_addtime desc ";
			$renai_come_count = $this->common->getAll($sql);
			foreach($renai_come_count as $val)
			{
				//单独计算仁爱医院 妇科|产科 今日到诊人数
				if($val['hos_id'] == 1)
				{
					if($val['keshi_id'] ==  7){
						$order[0]['renai_ck_order_add'] = isset($order[0]['renai_ck_order_add'])? (1 + $order[0]['renai_ck_order_add']):1;
						$order[0]['renai_ck_hos_id']=$val['hos_id'];
						$order[0]['renai_ck_keshi_id']=$val['keshi_id'];
					}else if($val['keshi_id'] ==  1  || $val['keshi_id'] ==  32){
						$order[0]['renai_ck_order_add_to'] = isset($order[0]['renai_ck_order_add_to'])? (1 + $order[0]['renai_ck_order_add_to']):1;
						$order[0]['renai_fk_hos_id']=$val['hos_id'];
						$order[0]['renai_fk_keshi_id']=1;
					}
				}
			}

			// 单独计算仁爱的数据
			$sql = "SELECT o.order_time,o.hos_id,o.keshi_id
					FROM " . $this->common->table('order') . " o
					WHERE hos_id = 1 AND keshi_id in(1,7,32) AND o.order_time <= " . $renai_wu_time . " AND o.order_time >= " . $renai_ling_time . " order by o.order_time desc ";
			$renai_add_count = $this->common->getAll($sql);

			foreach($renai_add_count as $val)
			{
				//单独计算仁爱医院 妇科|产科 今日预约人数，仁爱医院 默认ID为1，不可更改
					if($val['hos_id'] == 1)
					{
						if($val['keshi_id'] ==  7){
							$order[0]['renai_ck_add'] = isset($order[0]['renai_ck_add'])? (1 + $order[0]['renai_ck_add']):1;
							$order[0]['renai_ck_hos_id']=$val['hos_id'];
							$order[0]['renai_ck_keshi_id']=$val['keshi_id'];
						}else if($val['keshi_id'] ==  1 || $val['keshi_id'] ==  32){
							$order[0]['renai_fk_order'] = isset($order[0]['renai_fk_order'])? (1 + $order[0]['renai_fk_order']):1;
							$order[0]['renai_fk_hos_id']=$val['hos_id'];
							$order[0]['renai_fk_keshi_id']=1;
						}
					}
			}
			$sql = "SELECT o.come_time,o.hos_id,o.keshi_id
					FROM " . $this->common->table('order') . " o
					WHERE hos_id = 1 AND keshi_id in(1,7,32) AND o.is_come = 1  AND o.come_time <= " . $renai_wu_time . " AND o.come_time >= " . $renai_ling_time . " order by o.come_time desc ";
			$renai_come_count = $this->common->getAll($sql);
			foreach($renai_come_count as $val)
			{
				//单独计算仁爱医院 妇科|产科 今日到诊人数
				if($val['hos_id'] == 1)
				{
					if($val['keshi_id'] ==  7){
						$order[0]['renai_ck_add_to'] = isset($order[0]['renai_ck_add_to'])? (1 + $order[0]['renai_ck_add_to']):1;
						$order[0]['renai_ck_hos_id']=$val['hos_id'];
						$order[0]['renai_ck_keshi_id']=$val['keshi_id'];
					}else if($val['keshi_id'] ==  1  || $val['keshi_id'] ==  32){
						$order[0]['renai_fk_order_to'] = isset($order[0]['renai_fk_order_to'])? (1 + $order[0]['renai_fk_order_to']):1;
						$order[0]['renai_fk_hos_id']=$val['hos_id'];
						$order[0]['renai_fk_keshi_id']=1;
					}
				}
			}
		}

		$yue_time = strtotime(date("Y-m", $start_time) . "-01 00:00:00");
		for($i = 0; $i <= 30; $i ++)
		{
			$wu_time = strtotime(date("Y-m-d", $start_time - (86400 * $i)) . " 23:59:59");
			$ling_time = strtotime(date("Y-m-d", $start_time - (86400 * $i)) . " 00:00:00");
			$order[$i]['time'] = $wu_time;
			$order[$i]['add'] = isset($order[$i]['add'])? $order[$i]['add']:0;
			$order[$i]['come'] = isset($order[$i]['come'])? $order[$i]['come']:0;
			$order[$i]['order'] = isset($order[$i]['order'])? $order[$i]['order']:0;
			//添加代码
                        $order[$i]['gonghai_come'] = isset($order[$i]['gonghai_come'])? $order[$i]['gonghai_come']:0;
                         $order[$i]['gonghai_order'] = isset($order[$i]['gonghai_order'])? $order[$i]['gonghai_order']:0;
                        //代码结束
			foreach($add_count as $val)
			{
				if(($val['order_addtime'] <= $wu_time) && ($val['order_addtime'] >= $ling_time))
				{
					$order[$i]['add'] = isset($order[$i]['add'])? ($val['count'] + $order[$i]['add']):$val['count'];
				}
			}
			foreach($come_count as $val)
			{
				if(($val['come_time'] <= $wu_time) && ($val['come_time'] >= $ling_time))
				{
					$order[$i]['come'] = isset($order[$i]['come'])? ($val['count'] + $order[$i]['come']):$val['count'];
				}
			}
                        //添加代码
                        foreach($gonghai_come_count as $val)
			{
				if(($val['come_time'] <= $wu_time) && ($val['come_time'] >= $ling_time))
				{
					$order[$i]['gonghai_come'] = isset($order[$i]['gonghai_come'])? ($val['count'] + $order[$i]['gonghai_come']):$val['count'];
				}
			}
                         foreach($gonghai_order_count as $val)
			{
				if(($val['order_time'] <= $wu_time) && ($val['order_time'] >= $ling_time))
				{
					$order[$i]['gonghai_order'] = isset($order[$i]['gonghai_order'])? ($val['count'] + $order[$i]['gonghai_order']):$val['count'];
				}
			}
                       //代码结束
			foreach($order_count as $val)
			{
				if(($val['order_time'] <= $wu_time) && ($val['order_time'] >= $ling_time))
				{
					$order[$i]['order'] = isset($order[$i]['order'])? ($val['count'] + $order[$i]['order']):$val['count'];
				}
			}
		}
//		krsort($order);//对数组按照键值进行逆向排序
                ksort($order);//对数组进行正向排序

		foreach($add_count as $val)
		{
			if($val['order_addtime'] >= $yue_time)
			{
				$yue['add'] = isset($yue['add'])? ($val['count'] + $yue['add']):$val['count'];
			}
		}
		foreach($come_count as $val)
		{
			if($val['come_time'] >= $yue_time)
			{
				$yue['come'] = isset($yue['come'])? ($val['count'] + $yue['come']):$val['count'];
			}
		}
                //添加代码开始
                foreach($gonghai_come_count as $val)
		{
			if($val['come_time'] >= $yue_time)
			{
				$yue['gonghai_come'] = isset($yue['gonghai_come'])? ($val['count'] + $yue['gonghai_come']):$val['count'];
			}
		}
                //添加代码结束
		foreach($order_count as $val)
		{
			if($val['order_time'] >= $yue_time)
			{
				$yue['order'] = isset($yue['order'])? ($val['count'] + $yue['order']):$val['count'];
			}
		}

		return array('order' => $order, 'yue' => $yue);
	}

	/**统计指定时间的数据**/
	function order_count_by_set_time($start_time, $end_time,$hos_id = '',$keshi_id='',$rank_id='',$special_keshi_id='')

	{

		$where = 1;



		if($hos_id  == '1_1'){
        	$where .= " AND o.hos_id = 1 AND o.keshi_id = 1";
        } elseif($hos_id  == '45_114_116'){

			$hos_id_array = explode('_',$hos_id);



			if(count($hos_id_array) >2){

				$where .= " AND o.keshi_id IN (" . $hos_id_array[1].','.$hos_id_array[2] . ")";

			}

			$where .= " AND o.hos_id = ".$hos_id_array[0];

		}else if($hos_id  == '1_7' || $hos_id  == '45_115' || $hos_id  == '1_32' || $hos_id  == '54_153'){

			$hos_id_array = explode('_',$hos_id);

			if(count($hos_id_array) > 1){

				$where .= " AND o.keshi_id IN (" . $hos_id_array[1] . ")";

			}

			$where .= " AND o.hos_id = ".$hos_id_array[0];

		}else{



			$special_keshi_id_array = explode(',',$special_keshi_id);

			if(count($special_keshi_id_array) > 1){
				/***

				 * 2017 06 28 添加功能  开始

				*/

				//判斷是否是人流

				if($special_keshi_id_array[0] == 'js'){

					$where .= " AND o.jb_parent_id IN (" . $special_keshi_id_array[1] . ")";

				}else{

					$where .= " AND o.keshi_id IN (" . $special_keshi_id . ")";

				}

				/***

				 * 2017 06 28 添加功能  结束

				*/

			}else if(!empty($keshi_id)){

				$where .= " AND o.keshi_id=".$keshi_id;

			}else{

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";



				}

			}



			if(!empty($hos_id))

			{

				$where .= " AND o.hos_id = ".$hos_id;

			}

		}



		$sql = "SELECT count(order_id) AS count

		        FROM " . $this->common->table('order') . " o

			        WHERE $where AND o.order_addtime <= " . $start_time . " AND o.order_addtime >= " . $end_time ;

		//echo $sql."<br/>";
		$add_count = $this->common->getAll($sql);

		$sql = "SELECT count(o.order_id) AS count

		        FROM " . $this->common->table('order') . " o

			        WHERE $where AND o.is_come = 1 AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time;

		//echo $sql."<br/>";
		$come_count = $this->common->getAll($sql);

		$sql = "SELECT count(order_id) AS count

		        FROM " . $this->common->table('gonghai_order') . " o

			        WHERE $where AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time;

		//echo $sql."<br/>";
		$gonghai_order_count = $this->common->getAll($sql);
		$sql = "SELECT count(o.order_id) AS count

		        FROM " . $this->common->table('gonghai_order') . " o

				        WHERE $where AND o.is_come = 1 AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time;

		//echo $sql."<br/>";
		$gonghai_come_count = $this->common->getAll($sql);

		$yue['come']= intval($come_count[0]['count']);
		$yue['gonghai_come']=  intval($gonghai_come_count[0]['count']);
		//$yue['add']= intval($add_count[0]['count'])+intval($gonghai_order_count[0]['count']);
		$yue['add']= intval($add_count[0]['count']);
		//exit;
		//查询每日到诊
		$sql = "SELECT COUNT(*) AS count, o.order_addtime

		        FROM " . $this->common->table('order') . " o

				        WHERE $where AND o.order_addtime <= " . $start_time . " AND o.order_addtime >= " . $end_time . "

				GROUP BY o.order_addtime";

		$add_count = $this->common->getAll($sql);



		$sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count, o.come_time

		        FROM " . $this->common->table('order') . " o

				        WHERE $where AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time . "

				GROUP BY o.come_time";

		$come_count = $this->common->getAll($sql);

		//添加代码开始

		$sql = "SELECT SUM(IF(o.is_come, 1, 0)) AS count, o.come_time

		        FROM " . $this->common->table('gonghai_order') . " o

				        WHERE $where AND o.come_time <= " . $start_time . " AND o.come_time >= " . $end_time . "

				GROUP BY o.come_time";

		$gonghai_come_count = $this->common->getAll($sql);

		$sql = "SELECT COUNT(*) AS count, o.order_time

		        FROM " . $this->common->table('gonghai_order') . " o

				        WHERE $where AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time . "

				GROUP BY o.order_time";

		$gonghai_order_count = $this->common->getAll($sql);





		//添加代码结束

		$sql = "SELECT COUNT(*) AS count, o.order_time

		        FROM " . $this->common->table('order') . " o

				        WHERE $where AND o.order_time <= " . $start_time . " AND o.order_time >= " . $end_time . "

				GROUP BY o.order_time";

		$order_count = $this->common->getAll($sql);

		$order = array();

		for($i = 0; $i <= 30; $i ++)

		{

		$wu_time = strtotime(date("Y-m-d", $start_time - (86400 * $i)) . " 23:59:59");

				$ling_time = strtotime(date("Y-m-d", $start_time - (86400 * $i)) . " 00:00:00");

						$order[$i]['time'] = $wu_time;

						$order[$i]['add'] = isset($order[$i]['add'])? $order[$i]['add']:0;

						$order[$i]['come'] = isset($order[$i]['come'])? $order[$i]['come']:0;

						$order[$i]['order'] = isset($order[$i]['order'])? $order[$i]['order']:0;

						//添加代码

						$order[$i]['gonghai_come'] = isset($order[$i]['gonghai_come'])? $order[$i]['gonghai_come']:0;

						$order[$i]['gonghai_order'] = isset($order[$i]['gonghai_order'])? $order[$i]['gonghai_order']:0;

								//代码结束

								foreach($add_count as $val)

						{

						if(($val['order_addtime'] <= $wu_time) && ($val['order_addtime'] >= $ling_time))

						{

						$order[$i]['add'] = isset($order[$i]['add'])? ($val['count'] + $order[$i]['add']):$val['count'];

						}

						}

			foreach($come_count as $val)

			{

					if(($val['come_time'] <= $wu_time) && ($val['come_time'] >= $ling_time))

					{

					$order[$i]['come'] = isset($order[$i]['come'])? ($val['count'] + $order[$i]['come']):$val['count'];

					}

					}

					//添加代码

					foreach($gonghai_come_count as $val)

					{

					if(($val['come_time'] <= $wu_time) && ($val['come_time'] >= $ling_time))

					{

					$order[$i]['gonghai_come'] = isset($order[$i]['gonghai_come'])? ($val['count'] + $order[$i]['gonghai_come']):$val['count'];

				}

					}

					foreach($gonghai_order_count as $val)

			{

							if(($val['order_time'] <= $wu_time) && ($val['order_time'] >= $ling_time))

							{

							$order[$i]['gonghai_order'] = isset($order[$i]['gonghai_order'])? ($val['count'] + $order[$i]['gonghai_order']):$val['count'];

							}

							}

									//代码结束

									foreach($order_count as $val)

									{

									if(($val['order_time'] <= $wu_time) && ($val['order_time'] >= $ling_time))

									{

									$order[$i]['order'] = isset($order[$i]['order'])? ($val['count'] + $order[$i]['order']):$val['count'];

									}

									}

									}

									//		krsort($order);//对数组按照键值进行逆向排序

									ksort($order);//对数组进行正向排序

				return array('order' => $order, 'yue' => $yue);

	}
}