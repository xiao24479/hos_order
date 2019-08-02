<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class His extends CI_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model('Order_model');
        $this->model = $this->Order_model;
        // $config = array('server'=>'221.12.98.234','user'=>'renai','pass'=>'449155917','dbname'=>'HCRM');
        // $this->load->library('sqlsrv',$config);
    }

    public function hindex1() {
		$data = array();
        $data = $this->common->config('hindex');
  		//$res = $this->sqlsrv->fetch_all("select * from his_record");
		// p($res);
		// try{
		// 	$pdo = new PDO("sqlsrv:Server=221.12.98.234,1433;Database=HCRM","renai","449155917");
		// }catch(PDOException $e){
		// 	echo "ERROR:".$e->getMessage();
		// }
		// if($pdo)echo "OK!Connected!<br />";
		// $sql = 'select * from his_record';
		// $sth = $pdo->prepare($sql);
		// $sth->execute();
		// $res = $sth->fetchAll(PDO::FETCH_ASSOC);
		// p($res);

		$hos_id = isset($_GET['hos_id'])?intval($_GET['hos_id']):0;
		$phone = isset($_GET['phone'])?$_GET['phone']:'';

		try{

			if ($hos_id === 3) {
				$pdo = new PDO("sqlsrv:Server=127.0.0.1,1433;Database=tzwzdb","sa","449155917");
				//$pdo = new PDO("sqlsrv:Server=221.12.98.234,1433;Database=singledb","renai","449155917");
			}

		}catch(PDOException $e){
			echo "ERROR:".$e->getMessage();
		}
		//if($pdo)echo "OK!Connected!<br />";
		//$sql = 'select top 1000 * from gk_gkjbxx order by lrsj desc';
		/**
		 * gk_com_visittype 初诊复诊类型
		 * gk_scbmry 渠道
		 * a_employee_mi 医生表
		 * zd_unit_code 科室表
		 * gk_gkjbxx 患者表
		 * gk_gkjzxx 患者就诊信息表
		 * yp_mz_fytj 门诊药物费用表
		 * gk_ys_mzcf 门诊检查费用表
		 * gk_mz_settle 费用总额
		 * gk_zy_detail 住院费用表
		 * zd_charge_item 手术费用
		 */
		// $date_start ="2018-8-2";
		// $date_end ="2018-8-2";

		// $ds = date('Y-m-d 00:00:00.000',strtotime($date_start));
		// $de = date('Y-m-d 23:59:59.999',strtotime($date_end));
		// $phone = '13666849531';
		//$where = array($phone,$ds,$de);
		//$sql = "select a.gkbh,b.xm,a.sfxmmc,c.name as doc_name,d.name as keshi,a.dj,a.gg,a.dw,a.ysje,a.zk,a.ssje,a.sl,b.jzkh,a.lrsj from gk_ys_mzcf as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join a_employee_mi as c on a.kdys = c.seq left join zd_unit_code as d on a.sfbm = d.seq where b.lxdh = ? and a.lrsj between ? and ? order by b.lrsj desc";


		//$phone = '13666849531';
		$where = array($phone);
		$sql = "select a.gkbh,b.xm,a.sfxmmc,c.name as doc_name,d.name as keshi,a.dj,a.gg,a.dw,a.ysje,a.zk,a.ssje,a.sl,b.jzkh,a.lrsj from gk_ys_mzcf as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join a_employee_mi as c on a.kdys = c.seq left join zd_unit_code as d on a.sfbm = d.seq where b.lxdh = ? order by a.lrsj asc";
		$sth = $pdo->prepare($sql);
		$sth->execute($where);
		$res = $sth->fetchAll(PDO::FETCH_ASSOC);

		//p($res);die();

		//加序号
		for ($i=0; $i < count($res); $i++) {
			$res[$i]['num'] = $i+1;
			//$res[$i]['date'] = date('Y-m-d',strtotime($res[$i]['lrsj']));
		}

		$ss_amount = '';//实收
		$ys_amount = '';//应收
		foreach ($res as $key => $value) {
			$ss_amount += $value['ssje'];
			$ys_amount += $value['ysje'];
		}

		$new_res = array();
		foreach ($res as $key => $value) {
			$new_res[$value['gkbh']]['patient_id'] = $value['gkbh'];
			$new_res[$value['gkbh']]['name'] = $value['xm'];
			$new_res[$value['gkbh']]['doc_name'] = $value['doc_name'];
			$new_res[$value['gkbh']]['keshi'] = $value['keshi'];
			$new_res[$value['gkbh']]['lrsj'] = date('Y-m-d H:i:s',strtotime($value['lrsj']));
			$new_res[$value['gkbh']]['ss_amount'] = number_format($ss_amount,2);
			$new_res[$value['gkbh']]['ys_amount'] = number_format($ys_amount,2);
			$new_res[$value['gkbh']]['sub'][$key] = $value;
		}


		//树形患者消费记录
		// foreach ($res as $key => $value) {
		// 	$new_res[$value['gkbh']]['ss_amount'] = number_format($ss_amount,2);
		// 	$new_res[$value['gkbh']]['ys_amount'] = number_format($ys_amount,2);
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['sj'] = $value['date'];
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['patient_id'] = $value['gkbh'];
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['name'] = $value['xm'];
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['doc_name'] = $value['doc_name'];
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['keshi'] = $value['keshi'];
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['lrsj'] = date('Y-m-d H:i:s',strtotime($value['lrsj']));
		// 	$new_res[$value['gkbh']]['stage'][$value['date']]['sub'][$key] = $value;
		// }


		$new_res1 = array();
		foreach ($new_res as $key => $value) {
			$new_res1 = $value;
		}

		//p($new_res1);
		//var_dump($count_amount);

		$data['info'] = $new_res1;
        $this->load->view('his', $data);

    }



    public function hindex() {
    	//$this->output->enable_profiler(true);
		$data = array();
        $data = $this->common->config('hindex');

        $his_id = trim(intval($_REQUEST['his_id']));
        $date = trim($_REQUEST['date']);

        $parse = '';

        if (!empty($date) && !empty($his_id)) {

	        if (!checkDateTime($date)) {
	        	show_404();
	        }

           	$his_config = $this->db->get_where("hui_his", array(
	            'id' => $his_id
	        ))->row_array();
	        $id_array = explode('|', $his_config['hos_id']);
	        $parse .= '&his_id='.$his_id;

	        $time = strtotime($date);

	        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
	        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
	        $start_time = strtotime($s_time . ' 00:00:00');
	        $end_time = strtotime($e_time . ' 23:59:59');

			//总到诊
	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->group_by("hui_admin.admin_id");
			$info = $this->db->get()->result_array();
			//p($info);die();
			$new_info = array();
			foreach ($info as $key => $value) {
				$new_info[$value['gid']]['gid'] = $value['gid'];
				$new_info[$value['gid']]['gname'] = $value['gname'];
				$new_info[$value['gid']]['sub'][$value['admin_id']] = $value;
			}
			//p($new_info);die();
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['g_come_sum'] += $val['come_num'];
				}
			}

			//男科到诊
			//从配置文件中获取男科科室
			$nk_array = $this->config->item($his_config['cname'].'_nanke');

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->group_by("hui_admin.admin_id");
			$info_nk = $this->db->get()->result_array();
			//p($info_nk);die();

			$new_info_nk = array();
			foreach ($info_nk as $key => $value) {
				$new_info_nk[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk'] = !empty($val['come_num_nk'])?$val['come_num_nk']:'0';
					$new_info[$key]['g_come_sum_nk'] += $val['come_num_nk'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk'] = !empty($val['come_num_nk'])?$val['come_num_nk']:'0';
				}
			}


			//男科竞价到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_jj');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 254);//竞价
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_jj = $this->db->get()->result_array();
			//p($info_nk);die();

			$new_info_nk_jj = array();
			foreach ($info_nk_jj as $key => $value) {
				$new_info_nk_jj[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_jj[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_jj[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_jj as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_jj'] = !empty($val['come_num_nk_jj'])?$val['come_num_nk_jj']:'0';
					$new_info[$key]['g_come_sum_nk_jj'] += $val['come_num_nk_jj'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_jj'] = !empty($val['come_num_nk_jj'])?$val['come_num_nk_jj']:'0';
				}
			}



			//男科优化到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_yh');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 255);//优化
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_yh = $this->db->get()->result_array();
			//p($info_nk_yh);die();

			$new_info_nk_yh = array();
			foreach ($info_nk_yh as $key => $value) {
				$new_info_nk_yh[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_yh[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_yh[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_yh as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_yh'] = !empty($val['come_num_nk_yh'])?$val['come_num_nk_yh']:'0';
					$new_info[$key]['g_come_sum_nk_yh'] += $val['come_num_nk_yh'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_yh'] = !empty($val['come_num_nk_yh'])?$val['come_num_nk_yh']:'0';
				}
			}

			//男科其他途径到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_yh');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 256);//其他途径
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_jj = $this->db->get()->result_array();
			//p($info_nk_qt);die();

			$new_info_nk_qt = array();
			foreach ($info_nk_qt as $key => $value) {
				$new_info_nk_qt[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_qt[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_qt[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_qt as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_qt'] = !empty($val['come_num_nk_qt'])?$val['come_num_nk_qt']:'0';
					$new_info[$key]['g_come_sum_nk_qt'] += $val['come_num_nk_qt'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_qt'] = !empty($val['come_num_nk_qt'])?$val['come_num_nk_qt']:'0';
				}
			}


			//妇科到诊
			//从配置文件中获取妇科科室
			$fk_array = $this->config->item($his_config['cname'].'_fuke');

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_fk');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $fk_array);
			$this->db->group_by("hui_admin.admin_id");
			$info_fk = $this->db->get()->result_array();

			$new_info_fk = array();
			foreach ($info_fk as $key => $value) {
				$new_info_fk[$value['gid']]['gid'] = $value['gid'];
				$new_info_fk[$value['gid']]['gname'] = $value['gname'];
				$new_info_fk[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_fk as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_fk'] = !empty($val['come_num_fk'])?$val['come_num_fk']:'0';
					$new_info[$key]['g_come_sum_fk'] += $val['come_num_fk'];
				}
			}

			//p($new_info);die();

			//其他科室到诊
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_other_all'] = $val['come_num']-$val['come_num_nk'];
					$new_info[$key]['g_come_num_other_all'] += $val['come_num_other_all'];
				}
			}

			//子集其他科室到诊
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_other'] = $val['come_num']-$val['come_num_nk']-$val['come_num_fk'];
					$new_info[$key]['g_come_num_other'] += $val['come_num_other'];
				}
			}
            //p($new_info);die();
			//获取电话号码
	        $this->db->select('hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,hui_order.pat_id,hui_patient.pat_name,hui_patient.pat_phone');
			$this->db->from('hui_admin');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->join('hui_patient', 'hui_patient.pat_id = hui_order.pat_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$info_pat = $this->db->get()->result_array();

			$new_info_pat = array();
			foreach ($info_pat as $key => $value) {
				$new_info_pat[$value['admin_id']]['phone'] .= "'".$value['pat_phone']."',";
				$new_info_pat[$value['admin_id']]['sub'][] = $value;
			}


			//获取对话总数
	        $info_dia = $this->db->select('admin_id,dialog_num,start_date,end_date')->get_where("hui_dialog_num", array(
	            'start_date >=' => $start_time,
	            'end_date <=' => $end_time
	        ))->result_array();

			$new_info_dia = array();
			foreach ($info_dia as $key => $value) {
				$new_info_dia[$value['admin_id']]['sub'][] = $value;
			}
			foreach ($new_info_dia as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info_dia[$key]['dialog_num'] += $val['dialog_num'];
					$new_info_dia[$key]['sub'][$k]['start_date_format'] = date('Y-m-d H:i:s',$val['start_date']);
					$new_info_dia[$key]['sub'][$k]['end_date_format'] = date('Y-m-d H:i:s',$val['end_date']);
				}
			}
	        //p($new_info_dia);die();


			//电话号码、对话数植入小组中
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['phone'] = substr($new_info_pat[$k]['phone'], 0, -1);
					$new_info[$key]['sub'][$k]['dialog_num'] = $new_info_dia[$k]['dialog_num'];
				}
			}

			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					//从HIS系统取数据
					$new_info[$key]['sub'][$k]['amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,1);
					$new_info[$key]['sub'][$k]['un_match_phone'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,2);
					$new_info[$key]['sub'][$k]['cz_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,3);
					$new_info[$key]['sub'][$k]['fz_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,4);
					$new_info[$key]['sub'][$k]['fk_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,5);
					$new_info[$key]['sub'][$k]['gc_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,6);
					$new_info[$key]['sub'][$k]['jj_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,7);
					$new_info[$key]['sub'][$k]['yh_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,8);
					$new_info[$key]['sub'][$k]['other_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,9);
				}
			}

			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					unset($new_info[$key]['sub'][$k]['phone']);
				}
			}

			$new_infos = array();
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_infos[$key]['gid'] = $value['gid'];
					$new_infos[$key]['gname'] = $value['gname'];
					$new_infos[$key]['g_come_num'] += $val['come_num'];
					$new_infos[$key]['g_come_num_nk'] += $val['come_num_nk'];
					$new_infos[$key]['g_come_num_nk_jj'] += $val['come_num_nk_jj'];
					$new_infos[$key]['g_come_num_nk_yh'] += $val['come_num_nk_yh'];
					$new_infos[$key]['g_come_num_nk_qt'] += $val['come_num_nk_qt'];
					$new_infos[$key]['g_jj_zero_ptient'] += $val['jj_zero_ptient'];
					$new_infos[$key]['g_yh_zero_ptient'] += $val['yh_zero_ptient'];
					$new_infos[$key]['g_other_zero_ptient'] += $val['other_zero_ptient'];

					$new_infos[$key]['g_come_num_other_all'] += $val['come_num_other_all'];
					$new_infos[$key]['g_come_num_fk'] += $val['come_num_fk'];
					$new_infos[$key]['g_come_num_other'] += $val['come_num_other'];

					$new_infos[$key]['g_dialog_num'] += $val['dialog_num'];
					$new_infos[$key]['g_amount'] += $val['amount'];
					$new_infos[$key]['g_cz_amount'] += $val['cz_amount'];
					$new_infos[$key]['g_fz_amount'] += $val['fz_amount'];
					$new_infos[$key]['g_fk_amount'] += $val['fk_amount'];
					$new_infos[$key]['g_gc_amount'] += $val['gc_amount'];
					$new_infos[$key]['sub'][] = $val;
				}
			}

			$all_info = array();
			foreach ($new_infos as $key => $value) {
				$all_info['all_come_num'] += $value['g_come_num'];
				$all_info['all_come_num_nk'] += $value['g_come_num_nk'];
				$all_info['all_come_num_nk_jj'] += $value['g_come_num_nk_jj'];
				$all_info['all_come_num_nk_yh'] += $value['g_come_num_nk_yh'];
				$all_info['all_come_num_nk_qt'] += $value['g_come_num_nk_qt'];
				$all_info['all_dialog_num'] += $value['g_dialog_num'];
				$all_info['all_amount'] += $value['g_amount'];
				$all_info['all_cz_amount'] += $value['g_cz_amount'];
				$all_info['all_fz_amount'] += $value['g_fz_amount'];
				$all_info['all_fk_amount'] += $value['g_fk_amount'];
				$all_info['all_gc_amount'] += $value['g_gc_amount'];
				$all_info['all_jj_zero_ptient'] += $value['g_jj_zero_ptient'];
				$all_info['all_yh_zero_ptient'] += $value['g_yh_zero_ptient'];
				$all_info['all_other_zero_ptient'] += $value['g_other_zero_ptient'];

				$all_info['all_come_num_other_all'] += $value['g_come_num_other_all'];
				$all_info['all_come_num_fk'] += $value['g_come_num_fk'];
				$all_info['all_come_num_other'] += $value['g_come_num_other'];
			}


			//p($new_infos);die();

			$data['all_info'] = $all_info;

	        $data['new_infos'] = $new_infos;

	        $parse .= "&date=".$date;

        } else {
	        $his_id = '';
	        $parse .= '';//默认空
        }

        $item = $this->db->get_where("hui_his", array(
            'is_show' => 1
        ))->result_array();

        if (empty($date)) {
        	$data['date'] = date('Y-m',time());
        } else {
        	$data['date'] = $date;
        }

        $data['item'] = $item;
        $data['his_id'] = $his_id;
        $data['parse'] = $parse;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('his/hindex', $data);

    }


    public function export_his(){
		$data = array();
        $data = $this->common->config('export_his');

        $his_id = trim(intval($_REQUEST['his_id']));
        $date = trim($_REQUEST['date']);

        if (!checkDateTime($date)) {
        	show_404();
        }

        if (!empty($date) && !empty($his_id)) {
           	$his_config = $this->db->get_where("hui_his", array(
	            'id' => $his_id
	        ))->row_array();
	        $id_array = explode('|', $his_config['hos_id']);
	        $parse .= '&his_id='.$id_str;

	        $time = strtotime($date);

	        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
	        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
	        $start_time = strtotime($s_time . ' 00:00:00');
	        $end_time = strtotime($e_time . ' 23:59:59');

			//总到诊
	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->group_by("hui_admin.admin_id");
			$info = $this->db->get()->result_array();
			//p($info);die();
			$new_info = array();
			foreach ($info as $key => $value) {
				$new_info[$value['gid']]['gid'] = $value['gid'];
				$new_info[$value['gid']]['gname'] = $value['gname'];
				$new_info[$value['gid']]['sub'][$value['admin_id']] = $value;
			}
			//p($new_info);die();
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['g_come_sum'] += $val['come_num'];
				}
			}

			//男科到诊
			//从配置文件中获取男科科室
			$nk_array = $this->config->item($his_config['cname'].'_nanke');

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->group_by("hui_admin.admin_id");
			$info_nk = $this->db->get()->result_array();
			//p($info_nk);die();

			$new_info_nk = array();
			foreach ($info_nk as $key => $value) {
				$new_info_nk[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk'] = !empty($val['come_num_nk'])?$val['come_num_nk']:'0';
					$new_info[$key]['g_come_sum_nk'] += $val['come_num_nk'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk'] = !empty($val['come_num_nk'])?$val['come_num_nk']:'0';
				}
			}


			//男科竞价到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_jj');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 254);//竞价
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_jj = $this->db->get()->result_array();
			//p($info_nk);die();

			$new_info_nk_jj = array();
			foreach ($info_nk_jj as $key => $value) {
				$new_info_nk_jj[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_jj[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_jj[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_jj as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_jj'] = !empty($val['come_num_nk_jj'])?$val['come_num_nk_jj']:'0';
					$new_info[$key]['g_come_sum_nk_jj'] += $val['come_num_nk_jj'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_jj'] = !empty($val['come_num_nk_jj'])?$val['come_num_nk_jj']:'0';
				}
			}



			//男科优化到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_yh');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 255);//优化
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_yh = $this->db->get()->result_array();
			//p($info_nk_yh);die();

			$new_info_nk_yh = array();
			foreach ($info_nk_yh as $key => $value) {
				$new_info_nk_yh[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_yh[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_yh[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_yh as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_yh'] = !empty($val['come_num_nk_yh'])?$val['come_num_nk_yh']:'0';
					$new_info[$key]['g_come_sum_nk_yh'] += $val['come_num_nk_yh'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_yh'] = !empty($val['come_num_nk_yh'])?$val['come_num_nk_yh']:'0';
				}
			}

			//男科其他途径到诊
			//从配置文件中获取男科科室

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_nk_yh');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $nk_array);
			$this->db->where('hui_order.from_parent_id', 256);//其他途径
			$this->db->group_by("hui_admin.admin_id");
			$info_nk_jj = $this->db->get()->result_array();
			//p($info_nk_qt);die();

			$new_info_nk_qt = array();
			foreach ($info_nk_qt as $key => $value) {
				$new_info_nk_qt[$value['gid']]['gid'] = $value['gid'];
				$new_info_nk_qt[$value['gid']]['gname'] = $value['gname'];
				$new_info_nk_qt[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_nk_qt as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_qt'] = !empty($val['come_num_nk_qt'])?$val['come_num_nk_qt']:'0';
					$new_info[$key]['g_come_sum_nk_qt'] += $val['come_num_nk_qt'];
				}
			}

			//给不存在男科患者的咨询到诊人数缺省为0
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_nk_qt'] = !empty($val['come_num_nk_qt'])?$val['come_num_nk_qt']:'0';
				}
			}


			//妇科到诊
			//从配置文件中获取妇科科室
			$fk_array = $this->config->item($his_config['cname'].'_fuke');

	        $this->db->select('hui_user_groups.id as gid,hui_user_groups.name as gname,hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,count(hui_order.order_id) as come_num_fk');
			$this->db->from('hui_user_groups');
			$this->db->join('hui_admin', 'hui_user_groups.id = hui_admin.admin_group','left');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$this->db->where_in('hui_order.keshi_id', $fk_array);
			$this->db->group_by("hui_admin.admin_id");
			$info_fk = $this->db->get()->result_array();

			$new_info_fk = array();
			foreach ($info_fk as $key => $value) {
				$new_info_fk[$value['gid']]['gid'] = $value['gid'];
				$new_info_fk[$value['gid']]['gname'] = $value['gname'];
				$new_info_fk[$value['gid']]['sub'][$value['admin_id']] = $value;
			}

			foreach ($new_info_fk as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_fk'] = !empty($val['come_num_fk'])?$val['come_num_fk']:'0';
					$new_info[$key]['g_come_sum_fk'] += $val['come_num_fk'];
				}
			}

			//p($new_info);die();

			//其他科室到诊
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_other_all'] = $val['come_num']-$val['come_num_nk'];
					$new_info[$key]['g_come_num_other_all'] += $val['come_num_other_all'];
				}
			}

			//子集其他科室到诊
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['come_num_other'] = $val['come_num']-$val['come_num_nk']-$val['come_num_fk'];
					$new_info[$key]['g_come_num_other'] += $val['come_num_other'];
				}
			}
            //p($new_info);die();
			//获取电话号码
	        $this->db->select('hui_admin.admin_id,hui_admin.admin_name,hui_order.hos_id,hui_order.pat_id,hui_patient.pat_name,hui_patient.pat_phone');
			$this->db->from('hui_admin');
			$this->db->join('hui_order', 'hui_admin.admin_id = hui_order.admin_id','left');
			$this->db->join('hui_patient', 'hui_patient.pat_id = hui_order.pat_id','left');
			$this->db->where('hui_order.come_time >=', $start_time);
			$this->db->where('hui_order.come_time <=', $end_time);
			$this->db->where('hui_order.is_come', 1);
			$this->db->where_in('hui_order.hos_id', $id_array);
			$info_pat = $this->db->get()->result_array();

			$new_info_pat = array();
			foreach ($info_pat as $key => $value) {
				$new_info_pat[$value['admin_id']]['phone'] .= "'".$value['pat_phone']."',";
				$new_info_pat[$value['admin_id']]['sub'][] = $value;
			}


			//获取对话总数
	        $info_dia = $this->db->select('admin_id,dialog_num,start_date,end_date')->get_where("hui_dialog_num", array(
	            'start_date >=' => $start_time,
	            'end_date <=' => $end_time
	        ))->result_array();

			$new_info_dia = array();
			foreach ($info_dia as $key => $value) {
				$new_info_dia[$value['admin_id']]['sub'][] = $value;
			}
			foreach ($new_info_dia as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info_dia[$key]['dialog_num'] += $val['dialog_num'];
					$new_info_dia[$key]['sub'][$k]['start_date_format'] = date('Y-m-d H:i:s',$val['start_date']);
					$new_info_dia[$key]['sub'][$k]['end_date_format'] = date('Y-m-d H:i:s',$val['end_date']);
				}
			}
	        //p($new_info_dia);die();


			//电话号码、对话数植入小组中
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_info[$key]['sub'][$k]['phone'] = substr($new_info_pat[$k]['phone'], 0, -1);
					$new_info[$key]['sub'][$k]['dialog_num'] = $new_info_dia[$k]['dialog_num'];
				}
			}

			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					//从HIS系统取数据
					$new_info[$key]['sub'][$k]['amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,1);
					$new_info[$key]['sub'][$k]['un_match_phone'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,2);
					$new_info[$key]['sub'][$k]['cz_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,3);
					$new_info[$key]['sub'][$k]['fz_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,4);
					$new_info[$key]['sub'][$k]['fk_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,5);
					$new_info[$key]['sub'][$k]['gc_amount'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,6);
					$new_info[$key]['sub'][$k]['jj_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,7);
					$new_info[$key]['sub'][$k]['yh_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,8);
					$new_info[$key]['sub'][$k]['other_zero_ptient'] = $this->getHisData($val['phone'],$his_config,$start_time,$end_time,9);
				}
			}

			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					unset($new_info[$key]['sub'][$k]['phone']);
				}
			}

			$new_infos = array();
			foreach ($new_info as $key => $value) {
				foreach ($value['sub'] as $k => $val) {
					$new_infos[$key]['gid'] = $value['gid'];
					$new_infos[$key]['gname'] = $value['gname'];
					$new_infos[$key]['g_come_num'] += $val['come_num'];
					$new_infos[$key]['g_come_num_nk'] += $val['come_num_nk'];
					$new_infos[$key]['g_come_num_nk_jj'] += $val['come_num_nk_jj'];
					$new_infos[$key]['g_come_num_nk_yh'] += $val['come_num_nk_yh'];
					$new_infos[$key]['g_come_num_nk_qt'] += $val['come_num_nk_qt'];
					$new_infos[$key]['g_jj_zero_ptient'] += $val['jj_zero_ptient'];
					$new_infos[$key]['g_yh_zero_ptient'] += $val['yh_zero_ptient'];
					$new_infos[$key]['g_other_zero_ptient'] += $val['other_zero_ptient'];

					$new_infos[$key]['g_come_num_other_all'] += $val['come_num_other_all'];
					$new_infos[$key]['g_come_num_fk'] += $val['come_num_fk'];
					$new_infos[$key]['g_come_num_other'] += $val['come_num_other'];

					$new_infos[$key]['g_dialog_num'] += $val['dialog_num'];
					$new_infos[$key]['g_amount'] += $val['amount'];
					$new_infos[$key]['g_cz_amount'] += $val['cz_amount'];
					$new_infos[$key]['g_fz_amount'] += $val['fz_amount'];
					$new_infos[$key]['g_fk_amount'] += $val['fk_amount'];
					$new_infos[$key]['g_gc_amount'] += $val['gc_amount'];
					$new_infos[$key]['sub'][] = $val;
				}
			}

			$all_info = array();
			foreach ($new_infos as $key => $value) {
				$all_info['all_come_num'] += $value['g_come_num'];
				$all_info['all_come_num_nk'] += $value['g_come_num_nk'];
				$all_info['all_come_num_nk_jj'] += $value['g_come_num_nk_jj'];
				$all_info['all_come_num_nk_yh'] += $value['g_come_num_nk_yh'];
				$all_info['all_come_num_nk_qt'] += $value['g_come_num_nk_qt'];
				$all_info['all_dialog_num'] += $value['g_dialog_num'];
				$all_info['all_amount'] += $value['g_amount'];
				$all_info['all_cz_amount'] += $value['g_cz_amount'];
				$all_info['all_fz_amount'] += $value['g_fz_amount'];
				$all_info['all_fk_amount'] += $value['g_fk_amount'];
				$all_info['all_gc_amount'] += $value['g_gc_amount'];
				$all_info['all_jj_zero_ptient'] += $value['g_jj_zero_ptient'];
				$all_info['all_yh_zero_ptient'] += $value['g_yh_zero_ptient'];
				$all_info['all_other_zero_ptient'] += $value['g_other_zero_ptient'];

				$all_info['all_come_num_other_all'] += $value['g_come_num_other_all'];
				$all_info['all_come_num_fk'] += $value['g_come_num_fk'];
				$all_info['all_come_num_other'] += $value['g_come_num_other'];
			}


			//p($new_infos);die();

        } else {
	        show_404();
        }

        $his_name = $this->db->select('his_item')->get_where("hui_his", array(
            'id' => $his_id
        ))->row_array();

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle($date.$his_name['his_item'].'到诊消费明细表');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:X1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:A5');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:B5');
        $objPHPExcel->getActiveSheet()->mergeCells('C2:J2');
        $objPHPExcel->getActiveSheet()->mergeCells('C3:C5');
        $objPHPExcel->getActiveSheet()->mergeCells('D3:F4');
        $objPHPExcel->getActiveSheet()->mergeCells('G3:G5');
        $objPHPExcel->getActiveSheet()->mergeCells('H3:J3');
        $objPHPExcel->getActiveSheet()->mergeCells('H4:H5');
        $objPHPExcel->getActiveSheet()->mergeCells('I4:I5');
        $objPHPExcel->getActiveSheet()->mergeCells('J4:J5');
        $objPHPExcel->getActiveSheet()->mergeCells('K2:O3');
        $objPHPExcel->getActiveSheet()->mergeCells('K4:K5');
        $objPHPExcel->getActiveSheet()->mergeCells('L4:L5');
        $objPHPExcel->getActiveSheet()->mergeCells('M4:M5');
        $objPHPExcel->getActiveSheet()->mergeCells('N4:N5');
        $objPHPExcel->getActiveSheet()->mergeCells('O4:O5');
        $objPHPExcel->getActiveSheet()->mergeCells('P2:P5');
        $objPHPExcel->getActiveSheet()->mergeCells('Q2:V2');
        $objPHPExcel->getActiveSheet()->mergeCells('Q3:Q5');
        $objPHPExcel->getActiveSheet()->mergeCells('R3:R5');
        $objPHPExcel->getActiveSheet()->mergeCells('S3:T3');
        $objPHPExcel->getActiveSheet()->mergeCells('S4:S5');
        $objPHPExcel->getActiveSheet()->mergeCells('T4:T5');
        $objPHPExcel->getActiveSheet()->mergeCells('U3:U5');
        $objPHPExcel->getActiveSheet()->mergeCells('V3:V5');
        $objPHPExcel->getActiveSheet()->mergeCells('W2:W5');
        $objPHPExcel->getActiveSheet()->mergeCells('X2:X5');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$date.$his_name['his_item'].'到诊消费明细表');
        $objPHPExcel->getActiveSheet()->setCellValue('A2','小组');
        $objPHPExcel->getActiveSheet()->setCellValue('B2',"类型\n姓名");
        $objPHPExcel->getActiveSheet()->setCellValue('C2','男科到诊');
        $objPHPExcel->getActiveSheet()->setCellValue('C3','总到诊');
        $objPHPExcel->getActiveSheet()->setCellValue('D3','0-消费');
        $objPHPExcel->getActiveSheet()->setCellValue('D5','竞');
        $objPHPExcel->getActiveSheet()->setCellValue('E5','优');
        $objPHPExcel->getActiveSheet()->setCellValue('F5','它');
        $objPHPExcel->getActiveSheet()->setCellValue('G3','有效');
        $objPHPExcel->getActiveSheet()->setCellValue('H3','主要渠道');
        $objPHPExcel->getActiveSheet()->setCellValue('H4','竞价');
        $objPHPExcel->getActiveSheet()->setCellValue('I4','优化');
        $objPHPExcel->getActiveSheet()->setCellValue('J4','其他');

        $objPHPExcel->getActiveSheet()->setCellValue('K2','其他科室到诊');
        $objPHPExcel->getActiveSheet()->setCellValue('K4','总');
        $objPHPExcel->getActiveSheet()->setCellValue('L4','有效');
        $objPHPExcel->getActiveSheet()->setCellValue('M4','妇科');
        $objPHPExcel->getActiveSheet()->setCellValue('N4','肛肠');
        $objPHPExcel->getActiveSheet()->setCellValue('O4','其它');

        $objPHPExcel->getActiveSheet()->setCellValue('P2','总对话');
        $objPHPExcel->getActiveSheet()->setCellValue('Q2','消费');
        $objPHPExcel->getActiveSheet()->setCellValue('Q3','男科初诊消费');

        $objPHPExcel->getActiveSheet()->setCellValue('R3','男科复诊');
        $objPHPExcel->getActiveSheet()->setCellValue('S3','其它科室消费');

        $objPHPExcel->getActiveSheet()->setCellValue('S4','妇科');
        $objPHPExcel->getActiveSheet()->setCellValue('T4','肛肠科');
        $objPHPExcel->getActiveSheet()->setCellValue('U3','男科初复诊消费');
        $objPHPExcel->getActiveSheet()->setCellValue('V3','总消费');
        $objPHPExcel->getActiveSheet()->setCellValue('W2','人均消费');
        $objPHPExcel->getActiveSheet()->setCellValue('X2','转化率');



       //设置列宽和行高
        // $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(5);

        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Microsoft Yahei');

        //设置单元格内文字自动换行
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);


        //设置单元格文字颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:X5')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        //设置背景色
        $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle( 'A5')->getFill()->getStartColor()->setARGB('0000b0f0');

        //冻结前两行
        $objPHPExcel->getActiveSheet()->freezePane('A1');
        $objPHPExcel->getActiveSheet()->freezePane('A6');

        //设置前两行字体
        $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);

        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:X5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:X5')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

        $all_num = count($new_infos);

        $num = 0;
        $s = 0;
        $c = 5;
        foreach ($new_infos as $key => $value) {
            for ($i = 6; $i <= count($value['sub'])+6; $i++) {
                if ($i == count($value['sub'])+6) {
                    $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c+1) .':A' . ($i+$num+$s));

	                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), '');
	                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), '小计');
	                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), !empty($value['g_come_num_nk'])?$value['g_come_num_nk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), !empty($value['g_jj_zero_ptient'])?$value['g_jj_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), !empty($value['g_yh_zero_ptient'])?$value['g_yh_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), !empty($value['g_other_zero_ptient'])?$value['g_other_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), !empty($value['g_come_num_nk'])?$value['g_come_num_nk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), !empty($value['g_come_num_nk_jj'])?$value['g_come_num_nk_jj']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), !empty($value['g_come_num_nk_yh'])?$value['g_come_num_nk_yh']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), !empty($value['g_come_num_nk_qt'])?$value['g_come_num_nk_qt']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i+$num+$s), !empty($value['g_come_num_other_all'])?$value['g_come_num_other_all']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i+$num+$s), !empty($value['g_come_num_other_all'])?$value['g_come_num_other_all']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i+$num+$s), !empty($value['g_come_num_fk'])?$value['g_come_num_fk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i+$num+$s), '');
	                $objPHPExcel->getActiveSheet()->setCellValue('O' . ($i+$num+$s), !empty($value['g_come_num_other'])?$value['g_come_num_other']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('P' . ($i+$num+$s), !empty($value['g_dialog_num'])?$value['g_dialog_num']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($i+$num+$s), !empty($value['g_cz_amount'])?$value['g_cz_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('R' . ($i+$num+$s), !empty($value['g_fz_amount'])?$value['g_fz_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('S' . ($i+$num+$s), !empty($value['g_fk_amount'])?$value['g_fk_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('T' . ($i+$num+$s), !empty($value['g_gc_amount'])?$value['g_gc_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('U' . ($i+$num+$s), $value['g_cz_amount']+$value['g_fz_amount']);
	                $objPHPExcel->getActiveSheet()->setCellValue('V' . ($i+$num+$s), !empty($value['g_amount'])?$value['g_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('W' . ($i+$num+$s), number_format((($value['g_amount']/$value['g_come_num'])) , 2, '.', ''));
	                $objPHPExcel->getActiveSheet()->setCellValue('X' . ($i+$num+$s), number_format((($value['g_come_num']/$value['g_dialog_num'])) , 2, '.', '')."%");

                    $objPHPExcel->getActiveSheet()->getStyle('B' . ($i+$num+$s) . ':X' . ($i+$num+$s))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
                    $c = $i+$num+$s;
                } else {
	                $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+$num+$s), $value['gname']);
	                $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+$num+$s), $value['sub'][$i-6]['admin_name']);
	                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_nk'])?$value['sub'][$i-6]['come_num_nk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+$num+$s), !empty($value['sub'][$i-6]['jj_zero_ptient'])?$value['sub'][$i-6]['jj_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+$num+$s), !empty($value['sub'][$i-6]['yh_zero_ptient'])?$value['sub'][$i-6]['yh_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+$num+$s), !empty($value['sub'][$i-6]['other_zero_ptient'])?$value['sub'][$i-6]['other_zero_ptient']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_nk'])?$value['sub'][$i-6]['come_num_nk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_nk_jj'])?$value['sub'][$i-6]['come_num_nk_jj']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_nk_yh'])?$value['sub'][$i-6]['come_num_nk_yh']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('J' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_nk_qt'])?$value['sub'][$i-6]['come_num_nk_qt']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_other_all'])?$value['sub'][$i-6]['come_num_other_all']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_other_all'])?$value['sub'][$i-6]['come_num_other_all']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('M' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_fk'])?$value['sub'][$i-6]['come_num_fk']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('N' . ($i+$num+$s), '');
	                $objPHPExcel->getActiveSheet()->setCellValue('O' . ($i+$num+$s), !empty($value['sub'][$i-6]['dialog_num'])?$value['sub'][$i-6]['dialog_num']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('P' . ($i+$num+$s), !empty($value['sub'][$i-6]['come_num_other'])?$value['sub'][$i-6]['come_num_other']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($i+$num+$s), !empty($value['sub'][$i-6]['cz_amount'])?$value['sub'][$i-6]['cz_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('R' . ($i+$num+$s), !empty($value['sub'][$i-6]['fz_amount'])?$value['sub'][$i-6]['fz_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('S' . ($i+$num+$s), !empty($value['sub'][$i-6]['fk_amount'])?$value['sub'][$i-6]['fk_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('T' . ($i+$num+$s), !empty($value['sub'][$i-6]['gc_amount'])?$value['sub'][$i-6]['gc_amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('U' . ($i+$num+$s), $value['sub'][$i-6]['cz_amount']+$value['sub'][$i-6]['fz_amount']);
	                $objPHPExcel->getActiveSheet()->setCellValue('V' . ($i+$num+$s), !empty($value['sub'][$i-6]['amount'])?$value['sub'][$i-6]['amount']:'0');
	                $objPHPExcel->getActiveSheet()->setCellValue('W' . ($i+$num+$s), number_format((($value['sub'][$i-6]['amount']/$value['sub'][$i-6]['come_num'])) , 2, '.', ''));
	                $objPHPExcel->getActiveSheet()->setCellValue('X' . ($i+$num+$s), number_format((($value['sub'][$i-6]['come_num']/$value['sub'][$i-6]['dialog_num'])) , 2, '.', '')."%");
                }


                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':X' . ($i+$num+$s))->getFont()->setSize(10);
                //边框
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':X' . ($i+$num+$s))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($i+$num+$s) . ':X' . ($i+$num+$s))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
            }
            $num += count($value['sub']);
            $s ++;
        }

        $objPHPExcel->getActiveSheet()->mergeCells('A' . ($c+1) . ':B' . ($c+1));
        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($c+1), '合计');
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($c+1), !empty($all_info['all_come_num'])?$all_info['all_come_num_nk']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($c+1), !empty($all_info['all_jj_zero_ptient'])?$all_info['all_jj_zero_ptient']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($c+1), !empty($all_info['all_yh_zero_ptient'])?$all_info['all_yh_zero_ptient']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($c+1), !empty($all_info['all_other_zero_ptient'])?$all_info['all_other_zero_ptient']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($c+1), !empty($all_info['all_come_num_nk'])?$all_info['all_come_num_nk']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($c+1), !empty($all_info['all_come_num_nk_jj'])?$all_info['all_come_num_nk_jj']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($c+1), !empty($all_info['all_come_num_nk_yh'])?$all_info['all_come_num_nk_yh']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($c+1), !empty($all_info['all_come_num_nk_qt'])?$all_info['all_come_num_nk_qt']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($c+1), !empty($all_info['all_come_num_other_all'])?$all_info['all_come_num_other_all']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($c+1), !empty($all_info['all_come_num_other_all'])?$all_info['all_come_num_other_all']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($c+1), !empty($all_info['all_come_num_fk'])?$all_info['all_come_num_fk']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($c+1), '');
        $objPHPExcel->getActiveSheet()->setCellValue('O' . ($c+1), !empty($all_info['all_come_num_other'])?$all_info['all_come_num_other']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('P' . ($c+1), !empty($all_info['all_dialog_num'])?$all_info['all_dialog_num']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($c+1), !empty($all_info['all_cz_amount'])?$all_info['all_cz_amount']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($c+1), !empty($all_info['all_fz_amount'])?$all_info['all_fz_amount']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('S' . ($c+1), !empty($all_info['all_fk_amount'])?$all_info['all_fk_amount']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('T' . ($c+1), !empty($all_info['all_gc_amount'])?$all_info['all_gc_amount']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('U' . ($c+1), $all_info['all_cz_amount']+$all_info['all_fz_amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('V' . ($c+1), !empty($all_info['all_amount'])?$all_info['all_amount']:'0');
        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($c+1), number_format((($all_info['all_amount']/$all_info['all_come_num'])) , 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($c+1), number_format((($all_info['all_come_num']/$all_info['all_dialog_num']) * 100) , 2, '.', '')."%");
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':X' . ($c+1))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);

        $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':X' . ($c+1))->getFont()->setSize(10);
        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':X' . ($c+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($c+1) . ':X' . ($c+1))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');


        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename='.$date.$his_name['his_item'].'到诊消费明细表.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }


    private function getHisData($phones,$his_config,$start_time,$end_time,$type=1) {

		try{
			//$pdo = new PDO("sqlsrv:Server=127.0.0.1,1433;Database=tzwzdb","sa","449155917");
			$pdo = new PDO("sqlsrv:Server={$his_config['his_server']},1433;Database={$his_config['his_db']}","{$his_config['his_user']}","{$his_config['his_pwd']}");
			//$pdo = new PDO("sqlsrv:Server=221.12.98.234,1433;Database=singledb","renai","449155917");

		}catch(PDOException $e){
			echo "ERROR:".$e->getMessage();
		}
		//if($pdo)echo "OK!Connected!<br />";
		/**
		 * gk_com_visittype 初诊复诊类型
		 * gk_scbmry 渠道
		 * a_employee_mi 医生表
		 * zd_unit_code 科室表
		 * gk_gkjbxx 患者表
		 * gk_gkjzxx 患者就诊信息表
		 * yp_mz_fytj 门诊药物费用表
		 * gk_ys_mzcf 门诊检查费用表
		 * gk_mz_settle 费用总额
		 * gk_zy_detail 住院费用表
		 * zd_charge_item 手术费用
		 */

		$ds = date('Y-m-d 00:00:00.000',$start_time);
		$de = date('Y-m-d 23:59:59.999',$end_time);

		$where = array(
			':ds' => $ds,
			':de' => $de
		);

		if ($type == 1) {//获取总消费
			$sql = "select a.gkbh,a.ssze,a.jssj from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh where b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['sub'][] = $value;
			}

			foreach ($new_res as $key => $value) {
				$amount += $value['amount'];
			}

			return $amount;
		} else if ($type == 2) {//获取不存在手机号码，即预约系统与HIS系统不匹配的，号码必须改成一致
			$sql = "select b.lxdh from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh where a.jzsj between :ds and :de ";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($res as $key => $value) {
				$phone_array[] = "'".$value['lxdh']."'";
			}
			$phone_array = array_unique($phone_array);
			$phoness = explode(',', $phones);
			foreach ($phone_array as $key => $value) {
				if (in_array($value, $phoness)) {
					$phones = str_replace($value, '', $phones);
				}
			}
			$phones = str_replace(',', '', $phones);
			$phones = str_replace("''", ',', $phones);
			$phones = str_replace("'", '', $phones);
			return $phones;
		} else if ($type == 3) {//获取男科初诊消费
			$sql = "select a.gkbh,a.ssze,a.jssj,b.xm,b.lrsj from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh where c.jzzt = 1 and c.zxbm = 37 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['sub'][] = $value;
			}

			foreach ($new_res as $key => $value) {
				$amount += $value['amount'];
			}

			return $amount;
		} else if ($type == 4) {//获取男科复诊消费
			$sql = "select a.gkbh,a.ssze,a.jssj,b.xm,b.lrsj from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh where c.jzzt = 16 and c.zxbm = 37 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['sub'][] = $value;
			}

			foreach ($new_res as $key => $value) {
				$amount += $value['amount'];
			}

			return $amount;
		} else if ($type == 5) {//获取妇科消费
			$sql = "select a.gkbh,a.ssze,a.jssj,b.xm,b.lrsj from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh where c.zxbm = 22 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['sub'][] = $value;
			}

			foreach ($new_res as $key => $value) {
				$amount += $value['amount'];
			}

			return $amount;
		} else if ($type == 6) {//获取肛肠腋臭消费
			$sql = "select a.gkbh,a.ssze,a.jssj,b.xm,b.lrsj from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh where c.zxbm in ('35','51') and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['sub'][] = $value;
			}

			foreach ($new_res as $key => $value) {
				$amount += $value['amount'];
			}

			return $amount;
		} else if ($type == 7) {//获取男科竞价零消费
			$sql = "select a.gkbh,a.ssze,b.xm,b.lxdh from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh left join gk_scbmry as d on b.lymtdy = d.bh where d.bh in ('20','22') and c.zxbm = 37 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['lxdh'] = $value['lxdh'];
				$new_res[$value['gkbh']]['xm'] = $value['xm'];
			}

			$count = 0;
			foreach ($new_res as $key => $value) {
				if ( $value['amount'] <= 100 ) {
					$count ++;
					//$zero_patient .= $value['xm'].'('.$value['lxdh'].'),';
				}
			}

			return $count;
			//return substr($zero_patient, 0, -1);

		} else if ($type == 8) {//获取男科优化零消费
			$sql = "select a.gkbh,a.ssze,b.xm,b.lxdh from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh left join gk_scbmry as d on b.lymtdy = d.bh where d.bh in ('21','23') and c.zxbm = 37 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['lxdh'] = $value['lxdh'];
				$new_res[$value['gkbh']]['xm'] = $value['xm'];
			}

			$count = 0;
			foreach ($new_res as $key => $value) {
				if ( $value['amount'] <= 100 ) {
					$count ++;
					//$zero_patient .= $value['xm'].'('.$value['lxdh'].'),';
				}
			}

			return $count;
			//return substr($zero_patient, 0, -1);

		} else if ($type == 9) {//获取男科其他零消费
			$sql = "select a.gkbh,a.ssze,b.xm,b.lxdh from gk_mz_settle as a left join gk_gkjbxx as b on a.gkbh = b.gkbh left join gk_gkjzxx as c on b.gkbh = c.gkbh left join gk_scbmry as d on b.lymtdy = d.bh where d.bh not in ('20','21','22','23') and c.zxbm = 37 and b.lxdh in (".$phones.") and a.jzsj between :ds and :de order by a.jzsj desc";
			$sth = $pdo->prepare($sql);
			$sth->execute($where);
			$res = $sth->fetchAll(PDO::FETCH_ASSOC);

			$new_res = array();
			foreach ($res as $key => $value) {
				$new_res[$value['gkbh']]['amount'] += $value['ssze'];
				$new_res[$value['gkbh']]['lxdh'] = $value['lxdh'];
				$new_res[$value['gkbh']]['xm'] = $value['xm'];
			}

			$count = 0;
			foreach ($new_res as $key => $value) {
				if ( $value['amount'] <= 100 ) {
					$count ++;
					//$zero_patient .= $value['xm'].'('.$value['lxdh'].'),';
				}
			}

			return $count;
			//return substr($zero_patient, 0, -1);
		}

    }



    public function setHis() {
		$data = array();
        $data = $this->common->config('setHis');

        $hospital = $this->model->hospital_order_list();
        $data['hospital'] = $this->model->hospital_order_list();

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('his/setHis', $data);

    }


    public function addHisAjax()
    {
    	if (!$this->input->is_ajax_request()) {
    		show_404();
    	}

    	$his_item = !empty($_POST['his_item'])?trim($_POST['his_item']):'';
    	$his_server = !empty($_POST['his_server'])?trim($_POST['his_server']):'';
    	$his_db = !empty($_POST['his_db'])?trim($_POST['his_db']):'';
    	$his_user = !empty($_POST['his_user'])?trim($_POST['his_user']):'';
    	$his_pwd = !empty($_POST['his_pwd'])?trim($_POST['his_pwd']):'';
    	$is_show = !empty($_POST['is_show']) && $_POST['is_show'] == 'on'?1:0;
    	$hos_id_array = !empty($_POST['hos_id'])?$_POST['hos_id']:'';

    	$hos_id_str = implode('|', $hos_id_array);

    	$data = array(
		    'his_item' => $his_item,
		    'his_server' => $his_server,
		    'his_db' => $his_db,
		    'his_user' => $his_user,
		    'his_pwd' => $his_pwd,
		    'is_show' => $is_show,
		    'cname' => $cname,
		    'hos_id' => $hos_id_str
		);

		$this->db->insert('hui_his', $data);

		if ($this->db->affected_rows()>0) {
			$ajaxReturn = array('code'=>1,'msg'=>'添加成功！');
			//$ajaxReturn = array('code'=>1,'msg'=>'request success!', 'data' =>$data);
		} else {
			$ajaxReturn = array('code'=>0,'msg'=>'添加失败！');
		}
    	echo json_encode($ajaxReturn);
    	exit();
    }


    public function ListHisAjax()
    {
    	if (!$this->input->is_ajax_request()) {
    		show_404();
    	}

    	$row = $this->db->get('hui_his')->result_array();

        foreach ($row as $key => $value) {
            $row[$key]['show'] = $value['is_show'] == 1 ? '<button class="layui-btn layui-btn-normal layui-btn-xs">启用</button>':'<button class="layui-btn layui-btn-primary layui-btn-xs">停用</button>';
        }

        $result = array('code'=>0,'count'=>count($row),'data'=>$row,'msg'=>'');
        echo json_encode($result);
    }


    public function delHisAjax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // $data = array();
        //$data = $this->common->config('delHisAjax');

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';

        $res = $this->db->delete("hui_his", array(
            'id' => $id
        ));

        if ($res) {
        	$ajaxReturn = array('code'=>1,'msg'=>'删除成功！');
        } else {
            $ajaxReturn = array('code'=>0,'msg'=>'删除失败！');
        }

        echo json_encode($ajaxReturn);
    	exit();
    }


    public function delBatchHisAjax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // $data = array();
        //$data = $this->common->config('delBatchHisAjax');

        $ids = !empty($_REQUEST['ids']) ? trim($_REQUEST['ids']) : '';

        $array_ids = explode(',', $ids);

        $res = $this->db->where_in('id', $array_ids)->delete("hui_his");

        if ($res) {
        	$ajaxReturn = array('code'=>1,'msg'=>'删除成功！');
        } else {
            $ajaxReturn = array('code'=>0,'msg'=>'删除失败！');
        }

        echo json_encode($ajaxReturn);
    	exit();

    }


    public function editHisAjax()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // $data = array();
        //$data = $this->common->config('editHisAjax');

        $id = !empty($_POST['id'])?trim(intval($_POST['id'])):'';

        if (empty($id)) {
        	$ajaxReturn = array('code'=>0,'msg'=>'参数错误！');
        	echo json_encode($ajaxReturn);
    		exit();
        }

    	$his_item = !empty($_POST['his_item'])?trim($_POST['his_item']):'';
    	$cname = !empty($_POST['cname'])?trim($_POST['cname']):'';
    	$his_server = !empty($_POST['his_server'])?trim($_POST['his_server']):'';
    	$his_db = !empty($_POST['his_db'])?trim($_POST['his_db']):'';
    	$his_user = !empty($_POST['his_user'])?trim($_POST['his_user']):'';
    	$his_pwd = !empty($_POST['his_pwd'])?trim($_POST['his_pwd']):'';
    	$is_show = !empty($_POST['is_show']) && $_POST['is_show'] == 'on'?1:0;
    	$hos_id_array = !empty($_POST['hos_id'])?$_POST['hos_id']:'';

    	$hos_id_str = implode('|', $hos_id_array);

    	$data = array(
		    'his_item' => $his_item,
		    'his_server' => $his_server,
		    'his_db' => $his_db,
		    'his_user' => $his_user,
		    'his_pwd' => $his_pwd,
		    'is_show' => $is_show,
		    'cname' => $cname,
		    'hos_id' => $hos_id_str
		);

        $res = $this->db->update('hui_his', $data, array('id' => $id));

        if ($res) {
        	$ajaxReturn = array('code'=>1,'msg'=>'编辑成功！');
        } else {
            $ajaxReturn = array('code'=>0,'msg'=>'编辑失败！');
        }

        echo json_encode($ajaxReturn);
    	exit();
    }


    public function defaultHosAjax(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = !empty($_REQUEST['id']) ? trim(intval($_REQUEST['id'])) : '0';

        $res = $this->db->select('hos_id')->get_where("hui_his", array(
            'id' => $id
        ))->row_array();

        if (empty($res)) {
        	exit();
        }

        $id_array = explode('|', $res['hos_id']);

        $hos_array = $this->db->select('hos_id,hos_name')->get_where('hui_hospital', array(
            'ask_auth' => 0
        ))->result_array();

        $return_str = '';
        foreach ($hos_array as $key => $value) {
        	if (in_array($value['hos_id'], $id_array)) {
        		$return_str .= "<input type='checkbox' checked='' name='hos_id[".$value['hos_id']."]' value='".$value['hos_id']."' title='".$value['hos_name']."'>";
        	} else {
        		$return_str .= "<input type='checkbox' name='hos_id[".$value['hos_id']."]' value='".$value['hos_id']."' title='".$value['hos_name']."'>";
        	}
        }

		echo $return_str;
		exit();

    }

    /**
     * 患者消费列表
     */
    public function ConsumptionList()
    {
		$data = array();
        $data = $this->common->config('ConsumptionList');

        $hos_id = intval($this->input->get('hos_id'));
        //$keshi_id = intval($this->input->get('keshi_id'));
        $keshi_id = 32;
        $date = trim($this->input->get('date'));
        $order_no = trim($this->input->get('order_no'));
        $pat_name = trim($this->input->get('pat_name'));
        $admin_name = trim($this->input->get('admin_name'));

        $parse = '';

        if (checkDateTime($date) && !empty($hos_id) && !empty($keshi_id)) {

	        $time = strtotime($date);

	        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
	        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
	        $start_time = $s_time . ' 00:00:00';
	        $end_time = $e_time . ' 23:59:59';

	        $s_t = strtotime($s_time . ' 00:00:00');
	        $e_t = strtotime($e_time . ' 23:59:59');

	        $s_ts = $s_time . ' 00:00:00';
	        $e_ts = $e_time . ' 23:59:59';

	        $where = array(
	            'a.d_time >=' => $start_time,
	            'a.d_time <=' => $end_time,
	        );

	        //1882 白春妹
	        //可以看到全部消费
	        if ($_COOKIE['l_admin_action'] != 'all' && !in_array($_COOKIE['l_admin_id'], array(1882))) {
		        $where = array(
		        	'a.hos_id' => $hos_id,
		        	'a.keshi_id' => $keshi_id,
		        	'a.admin_id' => $_COOKIE['l_admin_id'],
		        	'a.is_come' => 1,
		            // 'a.come_time >=' => $s_t,
		            // 'a.come_time <=' => $e_t,
		            'f.d_time >=' => $s_ts,
		            'f.d_time <=' => $e_ts,
		        );
		    } else {
		    	$where = array(
		    		'a.hos_id' => $hos_id,
		    		'a.keshi_id' => $keshi_id,
		    		'a.is_come' => 1,
		    	    // 'a.come_time >=' => $s_t,
		    	    // 'a.come_time <=' => $e_t,
		    	    'f.d_time >=' => $s_ts,
		    	    'f.d_time <=' => $e_ts,
		    	);
		    }

	        if (!empty($admin_name)) {
	        	$where1 = array('a.admin_name' => $admin_name);
	        	$where = array_merge($where,$where1);
	        }

	        if (!empty($pat_name)) {
	        	$where1 = array('f.patient_name' => $pat_name);
	        	$where = array_merge($where,$where1);
	        }

	        if (!empty($order_no)) {
	        	$where1 = array('a.order_no' => $order_no);
	        	$where = array_merge($where,$where1);
	        }

	        $this->db->set_dbprefix("");
	        $this->db->select('a.order_id,a.order_no,a.hos_id,a.keshi_id,a.his_jzkh,a.admin_id,a.come_time,b.admin_name,c.hos_name,d.keshi_name,e.pat_name,f.mz_id,f.zy_id,f.c_patient_id,f.patient_name,f.c_name,f.C_drug_spec,f.c_units,f.i_num,f.d_cost,f.d_ys_sum,f.d_ss_sum,f.d_time,f.c_doctor_name');
	       	$this->db->from('hui_order as a');
	       	$this->db->join('hui_admin as b','a.admin_id = b.admin_id');
	       	$this->db->join('hui_hospital as c','a.hos_id = c.hos_id');
	       	$this->db->join('hui_keshi as d','a.keshi_id = d.keshi_id');
	       	$this->db->join('hui_patient as e','a.pat_id = e.pat_id');
	       	$this->db->join('henry_patient_consumption as f','f.order_no = a.order_no');
	       	$this->db->where($where);
	       	$this->db->order_by('a.come_time','DESC');
	       	$order_info = $this->db->get()->result_array();

	       	//p($order_info);die();

	        foreach ($order_info as $key => $value) {
	        	if (!empty($value['order_no'])) {
	        		$new_order_info[$value['order_no']][] = $value;
	        	}
	        }

	        $info_data = array();
	        foreach ($new_order_info as $key => $value) {
	        	foreach ($value as $val) {
	        		$info_data[$key]['order_id'] = $val['order_id'];
	        		$info_data[$key]['order_no'] = $val['order_no'];
	        		$info_data[$key]['hos_id'] = $val['hos_id'];
	        		$info_data[$key]['keshi_id'] = $val['keshi_id'];
	        		$info_data[$key]['his_jzkh'] = $val['his_jzkh'];
	        		$info_data[$key]['admin_id'] = $val['admin_id'];
	        		$info_data[$key]['admin_name'] = $val['admin_name'];
	        		$info_data[$key]['come_time'] = $val['come_time'];
	        		$info_data[$key]['hos_name'] = $val['hos_name'];
	        		$info_data[$key]['keshi_name'] = $val['keshi_name'];
	        		$info_data[$key]['pat_name'] = $val['pat_name'];
	        		if (!empty($val['mz_id'])) {
	        			$info_data[$key]['mz'] += $val['d_ss_sum'];
	        		} elseif (!empty($val['zy_id'])) {
	        			$info_data[$key]['zy'] += $val['d_ss_sum'];
	        		}
	        		$info_data[$key]['ssje'] += $val['d_ss_sum'];
	        	}
	        }

	        //p($info_data);die();

	        foreach ($info_data as $key => $value) {
	        	if (!empty($value['mz'])) {
	        		$all_sum['mz'] += $value['mz'];
	        	}
	        	if (!empty($value['zy'])) {
	        		$all_sum['zy'] += $value['zy'];
	        	}
	        }
	        $data['all_sum'] = $all_sum;

	        $per_page_num = 20;
	        $per_page = !empty($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;


	        $result = array_slice($info_data,$per_page,$per_page_num);

			$this->load->library('pagination');
			$this->load->helper('page');//调用CI自带的page分页类
			$config = page_config();
			$config['base_url'] = '?c=his&m=ConsumptionList&date='.$date.'&hos_id='.$hos_id.'&kehsi_id='.$kehsi_id.'&order_name='.$order_name.'&admin_name='.$admin_name.'&pat_name='.$pat_name;

			$config['total_rows'] = count($info_data);
			$config['per_page'] = $per_page_num;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);

			$data['page'] = $this->pagination->create_links();

			$data['data_num'] = count($info_data);

	        $data['new_infos'] = $result;

	        $parse .= "&date=".$date;

        } else {
	        $hos_id = '';
	        $parse .= '';//默认空
        }


        if ($_COOKIE['l_admin_action'] != 'all') {
	        $where = array(
	        	'a.admin_id' => $_COOKIE['l_admin_id']
	        );

	        $this->db->set_dbprefix("");
			$this->db->select('hos_id,keshi_id');
			$this->db->from('hui_admin as a');
			$this->db->join('hui_rank as b','a.rank_id = b.rank_id');
			$this->db->join('hui_rank_power as c','b.rank_id = c.rank_id');
			$this->db->where($where);
			$admin_hos = $this->db->get()->result_array();

			$ah = array();
			foreach ($admin_hos as $key => $value) {
				$ah[] = $value['hos_id'];
			}

			$ah = array_unique($ah);

			$this->db->select('*');
			$this->db->from('hui_hospital');
			$this->db->where(array('ask_auth' => 0));
			$this->db->where_in('hos_id',$ah);

	        $item = $this->db->get()->result_array();
        } else {
			$this->db->select('*');
			$this->db->from('hui_hospital');
			$this->db->where(array('ask_auth' => 0));

	        $item = $this->db->get()->result_array();
        }

        if (empty($date)) {
        	$data['date'] = date('Y-m',time());
        } else {
        	$data['date'] = $date;
        }

        //p($data['new_infos']);die();

        $data['item'] = $item;
        $data['hos_id'] = $hos_id;
        $data['order_no'] = $order_no;
        $data['pat_name'] = $pat_name;
        $data['admin_name'] = $admin_name;
        $data['parse'] = $parse;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('his/ConsumptionList', $data);

    }

    /**
     * 获取某个患者的消费详细记录
     * @return [type] [description]
     */
    public function getHisSingleComsumption()
    {
    	$order_no = trim($this->input->get('order_no'));
    	$date = $this->input->get('date');

    	$final_info = array();
        if (checkDateTime($date) && !empty($order_no)) {

	        $time = strtotime($date);

	        $s_time = date('Y',$time).'-'.(date('m',$time)).'-01';
	        $e_time = date('Y-m-d',strtotime("$s_time +1 month -1 day"));
	        $start_time = $s_time . ' 00:00:00';
	        $end_time = $e_time . ' 23:59:59';

	        $s_t = $s_time . ' 00:00:00';
	        $e_t = $e_time . ' 23:59:59';

		   	$this->db->set_dbprefix("");
			$result = $this->db->order_by('d_time', 'ASC')->get_where('henry_patient_consumption', array('order_no' => $order_no,'d_time >=' => $s_t,'d_time <=' => $e_t))->result_array();

			foreach ($result as $key => $value) {
				if (!empty($value['mz_id'])) {
					$final_info['mz']['ys_sum'] += $value['d_ys_sum'];
					$final_info['mz']['ss_sum'] += $value['d_ss_sum'];
					$final_info['mz']['sub'][] = $value;
				}
				if (!empty($value['zy_id'])) {
					$final_info['zy']['ys_sum'] += $value['d_ys_sum'];
					$final_info['zy']['ss_sum'] += $value['d_ss_sum'];
					$final_info['zy']['sub'][] = $value;
				}
			}

		}

		//p($final_info);die();

    	$data['info'] = $final_info;

    	$this->load->view('his/his_single_comsumption', $data);
    }

}