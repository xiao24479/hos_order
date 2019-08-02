<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Order extends CI_Controller

{

	var $model;



	public function __construct()

	{

		parent::__construct();

		$this->load->model('Order_model');

		$this->lang->load('order');

		$this->lang->load('common');

		$this->model = $this->Order_model;

	}

        //获取短信模板的内容，代码开始

        public function ms_content_ajax(){

            $themes_id=isset($_REQUEST['themes_id'])?$_REQUEST['themes_id']:0;

            $themes_content = $this->common->getOne("SELECT themes_content FROM " . $this->common->table('sms_themes') . " WHERE themes_id = $themes_id");

            echo $themes_content;

        }

        public function ms_all_sent(){

              header("Content-Type:text/html;charset=utf-8");

            $hos_id=isset($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):0;

            $ms_content=isset($_REQUEST['ms_content'])?trim($_REQUEST['ms_content']):'';

            $ms_list=isset($_REQUEST['ms_list'])?trim($_REQUEST['ms_list']):'';



		        $this->load->helper(sms);

			$send_content = isset($_REQUEST['ms_content'])? trim($_REQUEST['ms_content']):'';

			$send_phone   = isset($_REQUEST['ms_list'])? trim($_REQUEST['ms_list']):'';

			$hos_id       = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

			$keshi_id     = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

			$send_phone   =substr($send_phone,0,-1);

			if(empty($send_content)|| empty($send_phone))

			{

				$this->common->msg($this->lang->line('no_empty'), 1);

			}



			$send_phone   = explode(";", $send_phone);



                //判断重复短信功能代码开始

                $phone_1=implode(",", $send_phone);

                $phone_1 = "'".str_replace(",","','",$phone_1)."'";

                $limit_time=time()-60*60;//判断1小时内是否发送重复的内容

                $sql_1="select * from ".$this->common->table('sms_send')." where 1 and send_status=0  and send_time>".$limit_time." and send_phone in (".$phone_1.") and send_content='".$send_content."' and hos_id=".$hos_id." limit 0,1";

                $res=$this->common->getAll($sql_1);



                if(!empty($res)){

                   echo '该短信已成功发送，请勿重复发送！';

                   exit();

                }else{

              //判断重复短信功能代码结束

                        $n_time=time()-90*24*60*60;

			$send_id_arr  = array();

                        $rs="select o.order_id,p.pat_phone from ".$this->common->table('order')." o left join ".$this->common->table('patient')." p on o.pat_id=p.pat_id where p.pat_phone in(".$phone_1.") and o.order_addtime>".$n_time;

			$order_phone=$this->common->getAll($rs);

                        foreach($order_phone as $val){

                            $order_id=intval($val['order_id']);

                            $this->db->insert($this->common->table('sms_send'), array('send_content' => $send_content, 'send_time' => time(), 'send_type' =>4, 'send_phone' => $val['pat_phone'], 'type_value'=>$order_id, 'hos_id' => $hos_id, 'keshi_id' => $keshi_id, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name']));

                            $send_id_arr[] = $this->db->insert_id();



                        }



			$send_phone   = implode(";", $send_phone);



			$send_id_str = implode(",", $send_id_arr);

			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

			$func_name = 'sms_'.$hospital['sms_int'];

			if ( function_exists($func_name)){

				$msg = mb_convert_encoding($send_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

				$send_status = sms_jianzhou($send_phone,$msg,$hospital['sms_name'],$hospital['sms_pwd']);

				$status = $this->lang->line('sms_send_status');

				if($send_status >= 0)

				{

					$msg_detail = "短信发送成功！" . $send_status;

					$send_status = 0;

				}

				else

				{

					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";

				}

			}else{

				$msg_detail = "短信发送失败，失败原因为：未设置短信接口！";

				$send_status = 108;

			}

			$sql = "UPDATE " . $this->common->table('sms_send') . " SET send_status = '" . $send_status . "' WHERE send_id IN ($send_id_str)";

			$this->db->query($sql);

		        echo $msg_detail;



//		$links[0] = array('href' => '?c=system&m=sms_send_log', 'text' => $this->lang->line('list_back'));

//		$this->common->msg($msg_detail, 0, $links, true, false);



                }



        }



        //判断短信是否重复的ajax

        public function sms_only_ajax(){

           $send_content = isset($_REQUEST['ms_content'])? trim($_REQUEST['ms_content']):'';

	   $send_phone   = isset($_REQUEST['ms_list'])? trim($_REQUEST['ms_list']):'';

	   $hos_id       = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;;

           $send_phone   =substr($send_phone,0,-1);

           $send_phone   = explode(";", $send_phone);



                //判断重复短信功能代码开始

                $phone_1=implode(",", $send_phone);

                $phone_1 = "'".str_replace(",","','",$phone_1)."'";

                $limit_time=time()-60*60;//判断1小时内是否发送重复的内容

                $sql_1="select * from ".$this->common->table('sms_send')." where 1 and send_status=0  and send_time>".$limit_time." and send_phone in (".$phone_1.") and send_content='".$send_content."' and hos_id=".$hos_id." limit 0,1";

                $res=$this->common->getAll($sql_1);



                if(!empty($res)){

                   echo "存在已发送的短信，请勿在1小时内发送重复的短信！";

                }else{

                    echo "短信正在发送中...";

                }







        }



        //四维表格显示

        public function siwei_show(){

            $dat=array();

            $now_time=date("Y-m-d",time());



           $a=isset($_POST['siwei_time'])?$_POST['siwei_time']:$now_time;



            $dat=$this->common->config('siwei_show');

        $dat['siwei']=$this->model->siwei_list($a);

        $dat['top'] = $this->load->view('top', $dat, true);

	$dat['themes_color_select'] = $this->load->view('themes_color_select', '', true);

	$dat['sider_menu'] = $this->load->view('sider_menu', $dat, true);

        $this->load->view('siwei/order_siwei',$dat);





        }

        public function siwei_show_window(){

            $dat=array();

            $now_time=date("Y-m-d",time());

            $a=isset($_POST['siwei_time'])?$_POST['siwei_time']:$now_time;

            //$dat=$this->common->config('siwei_show');

			$dat['siwei']=$this->model->siwei_list($a);

			$this->load->view('siwei/siwei_show_window',$dat);

        }



		public function baby_show_window()

		{

		   $dat=$this->common->config('baby_repost');

		   $now_time=date("Y-m-d",time());

		   $a=isset($_POST['baby_time'])?$_POST['baby_time']:$now_time;

		   $dat['baby']=$this->model->baby_list($a);

		   $dat['top'] = $this->load->view('top', $dat, true);

			$dat['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$dat['sider_menu'] = $this->load->view('sider_menu', $dat, true);

		   $this->load->view('baby/baby_show_window',$dat);

		}





        public function gonghai(){





            $this->load->view('siwei/gonghai');

        }

        public function docter_index(){



            //预约列表





		$data = array();

		$data           = $this->common->config('order_list');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind         = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;



		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;



                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

                //而$[start_date]表示xx-xx-xx这种格式的日期







                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);

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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			  //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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
		}



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';





		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{



			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";



		}



		if(!empty($_COOKIE["l_rank_id"])){

			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

			$rank_type = $this->common->getRow($sql);

//			if($rank_type['rank_type'] == 1){

//				$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

//			}

		}



		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";



		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;



		}



		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}


		if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=docter_index&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}else{
			$config['base_url'] =  '?c=order&m=docter_index&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}



 		//东方接诊医生查看当天预约患者信息
        $allowDocRankIds = array(65);

        if($where==1){
            if (!in_array($_COOKIE['l_rank_id'],$allowDocRankIds)) {
                $where.=" AND o.doctor_name='".$_COOKIE['l_admin_name']."' ";
            }
        }

        // //仁爱医生显示过去一周接诊过的患者
        // $hosid_arr = explode(",", $_COOKIE["l_hos_id"]);
        // if (in_array(1, $hosid_arr)) {
        // 	$now_time = strtotime("-1 week +1 day");
        // 	//$now_time = strtotime("-1 month +1 day");
        // } else {
        // 	$now_time = strtotime(date("Y-m-d"));
        // }

        //东方接诊医生查看当天预约患者信息
        if (!in_array($_COOKIE['l_rank_id'],$allowDocRankIds)) {
            $now_time = strtotime(date("Y-m-d"));
            $where.=" AND o.come_time>".$now_time;
        } else {
            $now_time = strtotime(date("Y-m-d"));
            $end_time = strtotime(date("Y-m-d 23:59:59"));
            $where.=" AND o.order_time between $now_time AND $end_time" ;
        }


        if(!empty($_COOKIE["l_hos_id"])){

			$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

		}





		$order_list = $this->model->order_list($where, 0, 200, $orderby);

		//$dy_order_list=$this->model->dy_order_list($where, 0, 200, $orderby);

		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		$dao_false_arr = array();

		foreach($dao_false as $val)

		{

			$dao_false_arr[$val['false_id']] = $val['false_name'];

		}

                $hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

                $keshi_array=isset($_COOKIE['l_keshi_id'])?$_COOKIE['l_keshi_id']:'';//获取所属医院所属科室字符串

                $doctor_list=$this->model->doctor_list($hos_array,$keshi_array);//依据字符串调用相关功能



		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



                $data['doctor_list']=$doctor_list;//新增医生列表

		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['dao_false'] = $dao_false;

		$data['huifang'] = $this->set_huifang();

		$data['dao_false_arr'] = $dao_false_arr;

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

                $data['dy_order_list'] = $dy_order_list;

//		$data['order_count'] = $order_count;

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		 $this->load->view('docter/docter_index',$data);

	}



    /**
     * 厦门医生页面  ADD 2018-6-4
     * @return [type] [description]
     */
    public function docter_index_xiamen() {
        header("Content-type:text/html;charset=utf-8");
        //预约列表
        $data = array();
        $data = $this->common->config('order_list');
        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : ''; //日期
        $hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0; //预约医院编号
        $keshi_id = isset($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0; //科室编号
        $patient_name = isset($_REQUEST['p_n']) ? trim($_REQUEST['p_n']) : ''; //病人名称
        $patient_phone = isset($_REQUEST['p_p']) ? trim($_REQUEST['p_p']) : ''; //病人手机电话
        $order_no = isset($_REQUEST['o_o']) ? trim($_REQUEST['o_o']) : ''; //预约编号
        $from_parent_id = isset($_REQUEST['f_p_i']) ? intval($_REQUEST['f_p_i']) : 0;
        $from_id = isset($_REQUEST['f_i']) ? intval($_REQUEST['f_i']) : 0;
        $asker_name = isset($_REQUEST['a_i']) ? trim($_REQUEST['a_i']) : '';
        $status = isset($_REQUEST['s']) ? intval($_REQUEST['s']) : 0;
        $bind = isset($_REQUEST['wx']) ? intval($_REQUEST['wx']) : 0;
        $type_id = isset($_REQUEST['o_t']) ? intval($_REQUEST['o_t']) : 0;
        $order_type = isset($_REQUEST['t']) ? intval($_REQUEST['t']) : 2;
        $p_jb = isset($_REQUEST['p_jb']) ? intval($_REQUEST['p_jb']) : 0;
        $jb = isset($_REQUEST['jb']) ? intval($_REQUEST['jb']) : 0;
        $wu = isset($_REQUEST['wu']) ? intval($_REQUEST['wu']) : 0;
        $type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
        $pro = isset($_REQUEST['province']) ? intval($_REQUEST['province']) : 0;
        $city = isset($_REQUEST['city']) ? intval($_REQUEST['city']) : 0;
        $are = isset($_REQUEST['area']) ? intval($_REQUEST['area']) : 0;
        /* 未定患者 */
        $order_time_type = isset($_REQUEST['order_time_type']) ? intval($_REQUEST['order_time_type']) : 0;
        $data['hos_id'] = $hos_id;
        $data['keshi_id'] = $keshi_id;
        $data['p_n'] = $patient_name;
        $data['p_p'] = $patient_phone;
        $data['o_o'] = $order_no;
        $data['f_p_i'] = $from_parent_id;
        $data['f_i'] = $from_id;
        $data['a_i'] = $asker_name;
        $data['s'] = $status;
        $data['wx'] = $bind;
        $data['o_t'] = $type_id;
        $data['t'] = $order_type;
        $data['p_jb'] = $p_jb;
        $data['jb'] = $jb;
        //判断日期是否为空
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];
            if (!$this->dateCheck($start) || !$this->dateCheck($end)) {
                $start = date("Y年m月d日");
                $end = $start;
            }
            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
            $data['start_date'] = $start;
            $data['end_date'] = $end;
            setcookie('start_time', strtotime($start));
            setcookie('end_time', strtotime($end));
        } else {
            if (isset($_COOKIE['start_time'])) {
                $start = date("Y-m-d", $_COOKIE['start_time']);
                $end = date("Y-m-d", $_COOKIE['end_time']);
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
                $data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
            } else {
                $start = date("Y-m-d", time());
                $end = date("Y-m-d", time());
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", time());
                $data['end'] = date("Y年m月d日", time());
            }
        }
        //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，
        //而$[start_date]表示xx-xx-xx这种格式的日期
        //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表
        $hospital = $this->model->hospital_order_list();
        $keshi = $this->model->keshi_order_list();
        $keshi_arr = array();
        //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
        foreach ($keshi as $val) {
            $keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
            $keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
            $keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
            $keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
            $keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
            $keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
        }
        $from_list = $this->model->from_order_list(-1);
        $par_from_arr = array();
        $from_arr = array(); //生成二维数组，把每条记录作为数组存进$from_arr
        foreach ($from_list as $val) {
            if ($val['parent_id'] == 0) {
                $par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            } else {
                $from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            }
        }
        $from_list = $par_from_arr;
        //获取订单类别表Order_type中的所有数据
        $type_list = $this->model->type_order_list();
        $type_arr = array();
        foreach ($type_list as $val) {
            $type_arr[$val['type_id']]['type_id'] = $val['type_id'];
            $type_arr[$val['type_id']]['type_name'] = $val['type_name'];
            $type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
            $type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
            $type_arr[$val['type_id']]['type_order'] = $val['type_order'];
        }
        $type_list = $type_arr;
        $hos_id_arr = array();
        $keshi_id_arr = array();
        $jibing = read_static_cache('jibing_list');
        $jibing_list = array();
        $jibing_parent = array();
        if (!empty($jibing)) {
            //根据科室查询疾病
            if (!empty($data['keshi_id'])) {
                $keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = " . $data['keshi_id']);
                foreach ($jibing as $val) {
                    $jb_exit = 0;
                    foreach ($keshi_jb as $keshi_jb_val) {
                        if (strcmp($keshi_jb_val['jb_id'], $val['parent_id']) == 0) {
                            $jb_exit = $val['jb_id'];
                            break;
                        }
                    }
                    if (!empty($jb_exit)) {
                        $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        if ($val['jb_level'] == 2) {
                            $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                            $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        }
                    }
                }
            } else {
                foreach ($jibing as $val) {
                    $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                    $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    if ($val['jb_level'] == 2) {
                        $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    }
                }
            }
        }
        $data['from_arr'] = $from_arr;
        $data['keshi'] = $keshi_arr;
        $data['jibing'] = $jibing_list;
        $data['jibing_parent'] = $jibing_parent;
        $hos_auth = array();
        foreach ($hospital as $val) {
            $hos_id_arr[] = $val['hos_id'];
            if ($val['ask_auth']) {
                $hos_auth[] = $val['hos_id'];
            }
        }
        foreach ($keshi as $val) {
            $keshi_id_arr[] = $val['keshi_id'];
        }
        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $data['now_page'] = $page;
        $per_page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 30;
        $per_page = empty($per_page) ? 30 : $per_page;
        $this->load->library('pagination');
        $this->load->helper('page'); //调用CI自带的page分页类
        /* 处理判断条件 */
        $where = 1;
        $orderby = '';
        if ($wu == 1) {
            $w_start = strtotime($start . ' 00:00:00') - 43200;
            $w_end = strtotime($end . ' 11:59:59');
        } else {
            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
        }

        $where.= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
        $orderby.= ',o.come_time DESC ';

        /* 当输入患者的信息时，其他的搜索条件都不需要了 */
        if (!empty($patient_name)) {
            $where = " p.pat_name = '" . $patient_name . "'";
            $data['p_n'] = $patient_name;
            $data['p_p'] = "";
            $data['o_o'] = "";
        }
        if (!empty($_COOKIE["l_rank_id"])) {
            $sql = 'select rank_type from ' . $this->common->table('rank') . ' where rank_id = ' . $_COOKIE["l_rank_id"];
            $rank_type = $this->common->getRow($sql);
            //          if($rank_type['rank_type'] == 1){
            //              $where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";
            //          }

        }
        if (!empty($patient_phone)) {
            $where = " (p.pat_phone = '" . $patient_phone . "' OR p.pat_phone1 = '" . $patient_phone . "')";
            $data['p_n'] = "";
            $data['p_p'] = $patient_phone;
            $data['o_o'] = "";
        }
        if (!empty($order_no)) {
            $where = " o.order_no = '" . $order_no . "'";
            $data['p_n'] = "";
            $data['p_p'] = "";
            $data['o_o'] = $order_no;
        }
        if ($orderby == '') {
            $orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';
        } else {
            $orderby = substr($orderby, 1);
            $orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
        }
        if ($type == 'mi') {
            $config['base_url'] = '?c=order&m=docter_index&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' . $_REQUEST['f_i'] . '&a_i=' . $_REQUEST['a_i'] . '&s=' . $_REQUEST['s'] . '&p=' . $per_page . '&t=' . $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' . $_REQUEST['o_t'] . '&wu=' . $_REQUEST['wu'] . '&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city'] . '&area=' . $_REQUEST['area'] . '&wx=' . $_REQUEST['wx'] . '&p_jb=' . $_REQUEST['p_jb'] . '&jb=' . $_REQUEST['jb'];
        } else {
            $config['base_url'] = '?c=order&m=docter_index&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' . $_REQUEST['f_i'] . '&a_i=' . $_REQUEST['a_i'] . '&s=' . $_REQUEST['s'] . '&p=' . $per_page . '&t=' . $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' . $_REQUEST['o_t'] . '&wu=' . $_REQUEST['wu'] . '&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city'] . '&area=' . $_REQUEST['area'] . '&wx=' . $_REQUEST['wx'] . '&p_jb=' . $_REQUEST['p_jb'] . '&jb=' . $_REQUEST['jb'];
        }
        if ($where == 1) {
            $where.= " AND o.doctor_name='" . $_COOKIE['l_admin_name'] . "' ";
        }
        // $now_time = strtotime(date("Y-m-d", time()));
        // $where.= " AND o.come_time>" . $now_time;
        if (!empty($_COOKIE["l_hos_id"])) {
            $where.= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
        }
        //exit($where);
        $order_list = $this->model->order_list($where, 0, 200, $orderby);
        $dy_order_list = $this->model->dy_order_list($where, 0, 200, $orderby);
        $dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");
        $dao_false_arr = array();
        foreach ($dao_false as $val) {
            $dao_false_arr[$val['false_id']] = $val['false_name'];
        }
        $hos_array = isset($_COOKIE['l_hos_id']) ? $_COOKIE['l_hos_id'] : ''; //获取所属医院字符串
        $keshi_array = isset($_COOKIE['l_keshi_id']) ? $_COOKIE['l_keshi_id'] : ''; //获取所属医院所属科室字符串
        $doctor_list = $this->model->doctor_list($hos_array, $keshi_array); //依据字符串调用相关功能
        $rank_type = $this->model->rank_type();
        $area = $this->model->area();
        $province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
        $flag_1 = array();
        $flag_2 = array();
        foreach ($province as $val) {
            if (trim($val['region_name']) == '广东' || trim($val['region_name']) == '浙江') {
                $flag_1[] = $val;
            } else {
                $flag_2[] = $val;
            }
        }
        $province_list = array_merge($flag_1, $flag_2);
        $data['doctor_list'] = $doctor_list; //新增医生列表
        $data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL
        $data['area'] = $area;
        $data['province'] = $province_list;
        $data['rank_type'] = $rank_type;
        $data['dao_false'] = $dao_false;
        $data['huifang'] = $this->set_huifang();
        $data['dao_false_arr'] = $dao_false_arr;
        $data['page'] = $this->pagination->create_links();
        $data['per_page'] = $per_page;
        $data['order_list'] = $order_list;
        $data['dy_order_list'] = $dy_order_list;
        //      $data['order_count'] = $order_count;
        $data['type_list'] = $type_list;
        $data['from_list'] = $from_list;
        $data['hospital'] = $hospital;
        $data['hos_auth'] = $hos_auth;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('docter/docter_index_xiamen', $data);
    }



     /*

      * 以下是和医院合作的自媒体的相关的数据信息展示调用

      *

      *

      */





        public function zmt_index(){



            //预约列表





		$data = array();

		$data           = $this->common->config('my_profile');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind         = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;



		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

//判断日期是否为空

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}

                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

                //而$[start_date]表示xx-xx-xx这种格式的日期







                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);

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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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

		}



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}





		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}





		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



//		if(!empty($asker_name))

//		{

//			$where .= " AND o.admin_name = '" . $asker_name . "'";

//		}



		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if($bind == 1){



			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){



			$where .= ' AND  p.pat_weixin <> ""';

		}



		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if($order_time_type == 1)

		{

			$where .= ' AND o.order_time = 0';

		}

		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}



		/* 时间条件 */

		if($order_type == 1)

		{//预约登记时间

			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

			$orderby .= ',o.order_addtime DESC ';

		}

		elseif($order_type == 2)

		{//预约时间

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

		}

		elseif($order_type == 3)

		{//实到时间

			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

			$orderby .= ',o.come_time DESC ';

		}

		elseif($order_type == 4)

		{

                    //医生排班时间

			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

			$orderby .= ',o.doctor_time DESC ';

		}



		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{



			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}



			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($_COOKIE["l_rank_id"])){

			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

			$rank_type = $this->common->getRow($sql);

//			if($rank_type['rank_type'] == 1){

//				$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

//			}

		}



		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}



			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}



		$order_count = $this->model->order_count($where);

		$order_count['wei'] = $order_count['count'] - $order_count['come'];

		$config = page_config();


		if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=zmt_index&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}else{
			$config['base_url'] =  '?c=order&m=zmt_index&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}


		$config['total_rows'] = $order_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

                //从预约列表中获取相关数据

                //限制预约列表中只能显示自己的相关预约信息

                $where.=" and o.admin_id=".$_COOKIE['l_admin_id'];

		$order_list = $this->model->order_list($where, $page, $per_page, $orderby);

		$dy_order_list=$this->model->dy_order_list($where, $page, $per_page, $orderby);

		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		$dao_false_arr = array();

		foreach($dao_false as $val)

		{

			$dao_false_arr[$val['false_id']] = $val['false_name'];

		}

                $hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

                $keshi_array=isset($_COOKIE['l_keshi_id'])?$_COOKIE['l_keshi_id']:'';//获取所属医院所属科室字符串

                $doctor_list=$this->model->doctor_list($hos_array,$keshi_array);//依据字符串调用相关功能



		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



                $data['doctor_list']=$doctor_list;//新增医生列表

		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['dao_false'] = $dao_false;

		$data['huifang'] = $this->set_huifang();

		$data['dao_false_arr'] = $dao_false_arr;

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

                $data['dy_order_list'] = $dy_order_list;

//		$data['order_count'] = $order_count;

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		 $this->load->view('zmt/zmt_index',$data);

	}

        public function daoyi_index(){



            //预约列表





		$data = array();

		$data           = $this->common->config('order_list');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind         = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;



		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

//判断日期是否为空

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}

                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

                //而$[start_date]表示xx-xx-xx这种格式的日期







                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);

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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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

		}



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}





		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}





		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}



		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if($bind == 1){



			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){



			$where .= ' AND  p.pat_weixin <> ""';

		}



		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if($order_time_type == 1)

		{

			$where .= ' AND o.order_time = 0';

		}

		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}



		/* 时间条件 */

		if($order_type == 1)

		{//预约登记时间

			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

			$orderby .= ',o.order_addtime DESC ';

		}

		elseif($order_type == 2)

		{//预约时间

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

		}

		elseif($order_type == 3)

		{//实到时间

			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

			$orderby .= ',o.come_time DESC ';

		}

		elseif($order_type == 4)

		{

                    //医生排班时间

			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

			$orderby .= ',o.doctor_time DESC ';

		} else {//预约时间

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

		}



		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{



			$where = " p.pat_name = '". $patient_name . "'";
			$ll_where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				$ll_where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}



			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				$ll_where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($_COOKIE["l_rank_id"])){

			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

			$rank_type = $this->common->getRow($sql);

//			if($rank_type['rank_type'] == 1){

//				$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

//			}

		}



		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";
			$ll_where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				$ll_where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				$ll_where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";
			$ll_where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				$ll_where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}



			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				$ll_where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}



		$order_count = $this->model->order_count($where);

		$order_count['wei'] = $order_count['count'] - $order_count['come'];

		$config = page_config();

        if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=daoyi_index&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}else{
			$config['base_url'] =  '?c=order&m=daoyi_index&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}


		$config['total_rows'] = $order_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

        //从预约列表中获取相关数据
		$order_list = $this->model->order_list($where, $page, $per_page, $orderby);


		//搜索预约号电话姓名时显示留联搜索结果
		if (!empty($patient_name) || !empty($patient_phone) || !empty($order_no)) {
			$liulian_list = $this->common->getAll("SELECT o.*,p.*,od.is_come as order_is_come FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p on o.pat_id = p.pat_id left join " . $this->common->table('order') . " as od on o.order_no_yy is not null and o.order_no_yy != '' and od.order_no = o.order_no_yy  WHERE 1 and ".$ll_where);
			$data['liulian_list'] = $liulian_list;
			$sql = "SELECT o.*,p.* FROM henry_gonghai_liulian  as o left join " . $this->common->table('patient') . " as p on o.pat_id = p.pat_id  WHERE 1 and ".$ll_where;
            $liulian_gh_list = $this->common->getAll($sql);
            $data['liulian_gh_list'] = $liulian_gh_list;
		}


        //剔除黑名单患者
        //获取最新添加时间的对应记录
        $black_sql = "select order_id from hui_order_black as t where  add_time=(select max(t1.add_time) from hui_order_black as t1 where t.order_id = t1.order_id ) and type = 1";
        $black_order = $this->db->query($black_sql)->result_array();
        foreach ($order_list as $key => $value) {
            foreach ($black_order as $k => $val) {
                if ($value['order_id'] == $val['order_id']) {
                    unset($order_list[$val['order_id']]);
                }
            }
        }
        //p($black_order);



		$dy_order_list=$this->model->dy_order_list($where, $page, $per_page, $orderby);

		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		$dao_false_arr = array();

		foreach($dao_false as $val)

		{

			$dao_false_arr[$val['false_id']] = $val['false_name'];

		}

                $hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

                $keshi_array=isset($_COOKIE['l_keshi_id'])?$_COOKIE['l_keshi_id']:'';//获取所属医院所属科室字符串

                $doctor_list=$this->model->doctor_list($hos_array,$keshi_array);//依据字符串调用相关功能



		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



                $data['doctor_list']=$doctor_list;//新增医生列表

		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['dao_false'] = $dao_false;

		$data['huifang'] = $this->set_huifang();

		$data['dao_false_arr'] = $dao_false_arr;

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

                $data['dy_order_list'] = $dy_order_list;

//		$data['order_count'] = $order_count;

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		 $this->load->view('daoyi/daoyi_index',$data);

	}







        //ajax根据科室获取所属科室的医生 代码开始

        public function ajax_get_docter(){

            $where=" 1 AND r.rank_type=1 and a.is_pass=1";

            if(!empty($_REQUEST['hos_id'])){



                $where.=" AND p.hos_id=".$_REQUEST['hos_id'];

            }

             if(!empty($_REQUEST['keshi_id'])){



                $where.=" AND p.keshi_id=".$_REQUEST['keshi_id'];

            }





             $sql="SELECT distinct(a.admin_name) FROM ".$this->common->table('admin')." a left join ".$this->common->table('rank')." r on a.rank_id=r.rank_id"

                    . " left join ".$this->common->table('rank_power')." p on r.rank_id=p.rank_id where ".$where;

            $rs=$this->common->getAll($sql);



            echo "<option select='selected'>请选择医生···</option>";

            foreach($rs as $k=>$v){

                echo "<option>".$v['admin_name']."</option>";

            }





        }

         //ajax根据科室获取所属科室的医生 代码结束

	public function search_ajax()

	{

		$search = $_REQUEST['search_text'];

        $sql = 'select a.admin_name, a.admin_id, p.hos_id  from '. $this->common->table('admin') .' a

		left join ' . $this->common->table('rank') . ' r on a.rank_id = r.rank_id

		left join ' . $this->common->table('rank_power') . ' p on a.rank_id = p.rank_id

		where r.rank_type = 1';

		$arr = $this->common->getAll($sql);

		$list = array();

		foreach($arr as $val){

			$list[$val['admin_id']] = $val;

		}



		if($_COOKIE['l_admin_id'] == 1||$_COOKIE['l_rank_id']==1){

			$sql = 'select hos_id from '. $this->common->table('hospital');

			$hos_arr = $this->common->getAll($sql);

			$hos = array();

			foreach($hos_arr as $val){



				$hos[] = $val['hos_id'];

			}

		}else{

			$hos = explode(',',$_COOKIE['l_hos_id']);

		}



		$res = array();



		foreach($list as $val){

			if(in_array($val['hos_id'],$hos)){



				if(strstr($val['admin_name'],$search)){

					$res[] = $val['admin_name'];

				}

			}

		}



		print_r(json_encode($res));



	}



	public function swt_talk()

	{

		$file_path = './static/swt/';

		$content = isset($_REQUEST['data'])? $_REQUEST['data']:'';

		$cid  = isset($_REQUEST['cid'])? $_REQUEST['cid']:'';

		$gid  = isset($_REQUEST['gid'])? $_REQUEST['gid']:'';

		$first = isset($_REQUEST['first'])? $_REQUEST['first']:1;

		$sid = isset($_REQUEST['sid'])? $_REQUEST['sid']:'DLT92296270';



		if(!empty($gid))

		{

			$gid = explode(".", $gid);

			$gid = $gid[count($gid) - 2] . "." . $gid[count($gid) - 1];

			$gid = sub_str($gid, 35);

		}



		$content = cut_html(urldecode($content));



		if($first == 1)

		{

			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('swt') . " WHERE cid = '" . $cid . "' AND gid = '" . $gid . "'");

			if(empty($info))

			{

				$this->db->insert($this->common->table('swt'), array('cid' => $cid, 'gid' => $gid, 'sid' => $sid));

			}

		}



		$cid_url = substr($cid, 0, 3) . "/" . substr($cid, 3, 3) . "/" . substr($cid, 6, 4) . "/" . substr($cid, 10, 3) . "/" . substr($cid, 13) . "/";

		$file_path = $file_path . $cid_url;



		if(!file_exists($file_path))

		{

			mkdirs($file_path);

		}



		$cid_url_file = empty($gid)? 'index.php':$gid . ".php";

		$cache_file_path =  $file_path . $cid_url_file;

		$swt_list = array();

		$swt_info = array('asker' => 0, 'admin' => 0, 'asktime' => 0, 'last' => '');

		$add = 0;



		if(file_exists($cache_file_path))

		{

			include_once($cache_file_path);

       		$swt_list = $data['swt_list'];

        	$swt_info = $data['swt_info'];

		}



		preg_match_all("|<p><font\sclass=\"(.*)namefont\">(.*)</font></p>|U", $content, $user);

		$word = preg_split("|<p><font\sclass=\"(.*)namefont\">(.*)</font></p>|U", $content); // 分割对话，提取对话的内容，避免对话中出现div的错误

		//preg_match_all("|<div\sstyle=\"margin-left:5px\"\sclass=\"(.*)font\">(.*)</div>|U", $content, $word);



		if(isset($user[2]))

		{

			if(!isset($swt_list[$user[2][count($user[2]) - 1]]))

			{

				$add = 1;

			}

			foreach($user[2] as $key => $val)

			{

				$swt_list[$user[2][$key]]['type'] = $user[1][$key];

				$swt_list[$user[2][$key]]['user'] = $user[2][$key];

				$swt_list[$user[2][$key]]['word'] = $word[$key + 1];

			}

			$swt_info['admin'] = 0;

			$swt_info['asker'] = 0;

			foreach($swt_list as $val)

			{

				if($val['type'] == 'operator')

				{

					$swt_info['admin'] ++;

				}

				else

				{

					$swt_info['asker'] ++;

				}

			}



			$swt_info['last'] = $user[1][count($user[1]) - 1];

			$start_time = $user[2][0];

			$end_time = $user[2][count($user[2]) - 1];

			$start_time = substr($start_time, -14);

			$end_time = substr($end_time, -14);

			$start_time = strtotime(date("Y") . "-" . $start_time);

			$end_time = strtotime(date("Y") . "-" . $end_time);

			if($first == 1 || $add == 1)

			{

				$swt_info['asktime'] = $swt_info['asktime'] + ($end_time - $start_time);

			}



			$swt_data['swt_info'] = $swt_info;

			$swt_data['swt_list'] = $swt_list;



			if(!file_exists($cache_file_path))

			{

				$fp = @fopen($cache_file_path, "a+");

				fwrite($fp, '1');

				fclose($fp);

			}

			$content = "<?php\r\n";

			$content .= "\$data = " . var_export($swt_data, true) . ";\r\n";

			$content .= "?>";

			file_put_contents($cache_file_path, $content, LOCK_EX);

		}

	}



	public function order_type()

	{

		$data = array();

		$data = $this->common->config('order_type');

		$type_list = $this->common->static_cache('read', "order_type", 'order_type');

		$data['type_list'] = $type_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_type', $data);

	}



	public function type_info()

	{

		$data = array();

		$type_id = empty($_REQUEST['type_id'])? 0:intval($_REQUEST['type_id']);

		if(empty($type_id))

		{

			$data = $this->common->config('type_add');

		}

		else

		{

			$data = $this->common->config('type_edit');

			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('order_type') ." WHERE type_id = $type_id");

			$data['info'] = $info;

		}

		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc");

		$data['hospital'] = $hospital;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('type_info', $data);

	}



	public function type_update()

	{

		$form_action     = $_REQUEST['form_action'];

		$type_id         = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;

		$type_name       = trim($_REQUEST['type_name']);

		$hos_id          = trim($_REQUEST['hos_id']);

		$keshi_id        = trim($_REQUEST['keshi_id']);

		$type_desc       = trim($_REQUEST['type_desc']);

		$type_order      = trim($_REQUEST['type_order']);



		if(empty($type_name))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		$arr = array('type_name' => $type_name,

					 'hos_id' => $hos_id,

					 'keshi_id' => $keshi_id,

					 'type_desc' => $type_desc,

					 'type_order' => $type_order);



		if($form_action == 'update')

		{

			if(empty($type_id))

			{

				$this->common->msg($this->lang->line('no_empty'), 1);

			}

			$this->common->config('type_edit');

			$this->db->update($this->common->table("order_type"), $arr, array('type_id' => $type_id));

			$send_array = array();
			$send_data = array();
			$send_data['type_id'] = $type_id;
			$ireport_id = $this->common->getOne("SELECT ireport_order_type_id FROM " . $this->common->table('order_type') . " WHERE type_id = ".$type_id);
			if(empty($ireport_id)){
				$ireport_id =0 ;
			}
			$send_data['ireport_order_type_id'] =$ireport_id;
			$send_data['type_name'] = $arr['type_name'];
			$send_data['hos_id'] = '0';
			$send_data['keshi_id'] = '0';
			if(!empty($arr['keshi_id'])){
				$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$arr['keshi_id']);
				if($ireport_hos_id > 0){
					$send_data['keshi_id'] = $ireport_hos_id;
				}
			}
			$send_data['type_desc'] = $arr['type_desc'];
			$send_data['type_order'] = $arr['type_order'];
			if(empty($parent_id)){
				$parent_id =0 ;
			}
			$ireport_id = $this->common->getOne("SELECT ireport_order_type_id FROM " . $this->common->table('order_type') . " WHERE type_id = ".$parent_id);
			$send_data['parent_id'] = 0;
			if($ireport_id > 0){
				$send_data['parent_id'] = $ireport_id;
			}
			$send_array[] =$send_data;
			$this->sycn_order_type_data_to_ireport($send_array);

		}

		else

		{

			$this->common->config('type_add');

			$this->db->insert($this->common->table("order_type"), $arr);

			$type_id = $this->db->insert_id();

			$send_array = array();
			$send_data = array();
			$send_data['type_id'] = $type_id;
			$send_data['type_name'] = $arr['type_name'];
			$send_data['hos_id'] = '0';
			$send_data['keshi_id'] = '0';
			if(!empty($arr['keshi_id'])){
				$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$arr['keshi_id']);
				if($ireport_hos_id > 0){
					$send_data['keshi_id'] = $ireport_hos_id;
				}
			}
			$send_data['type_desc'] = $arr['type_desc'];
			$send_data['type_order'] = $arr['type_order'];
			if(empty($parent_id)){
				$parent_id =0 ;
			}
			$ireport_id = $this->common->getOne("SELECT ireport_order_type_id FROM " . $this->common->table('order_type') . " WHERE type_id = ".$parent_id);
			$send_data['parent_id'] = 0;
			if($ireport_id > 0){
				$send_data['parent_id'] = $ireport_id;
			}
			$send_array[] =$send_data;
			$this->sycn_order_type_data_to_ireport($send_array);
		}

		$this->common->static_cache('delete', "order_type");

		$links[0] = array('href' => '?c=order&m=type_info&type_id=' . $type_id, 'text' => $this->lang->line('edit_back'));

		$links[1] = array('href' => '?c=order&m=type_info', 'text' => $this->lang->line('add_back'));

		$links[2] = array('href' => '?c=order&m=order_type', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function type_del()

	{

		$this->common->config('type_del');

		$type_id = isset($_REQUEST['type_id'])? intval($_REQUEST['type_id']):0;

		if(empty($type_id))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}

		$ireport_id = $this->common->getOne("SELECT ireport_order_type_id FROM " . $this->common->table('order_type') . " WHERE type_id = ".$type_id);
		if(!empty($ireport_id)){
			$send_data = array();
			$send_data['ireport_order_type_id'] = $ireport_id;
			$this->sycn_del_order_type_data_to_ireport($send_data);
		}

		$this->db->delete($this->common->table("order_type"), array('type_id' => $type_id));

		$this->common->static_cache('delete', "order_type");

		$links[0] = array('href' => '?c=order&m=order_type', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function order_from()

	{

		$data = array();

		$data = $this->common->config('order_from');

		$from_list = $this->model->from_list();

		$data['from_list'] = $from_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_from', $data);

	}



	public function from_info()

	{

		$data = array();

		$from_id = empty($_REQUEST['from_id'])? 0:intval($_REQUEST['from_id']);

		if(empty($from_id))

		{

			$data = $this->common->config('order_from_add');

		}

		else

		{

			$data = $this->common->config('order_from_edit');

			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('order_from') ." WHERE from_id = $from_id");

			$data['info'] = $info;

		}

		$from_list = $this->common->getAll("SELECT * FROM " . $this->common->table('order_from') . " WHERE parent_id = 0 ORDER BY from_order ASC, from_id DESC");

		$hospital = $this->common->getAll("SELECT * FROM " . $this->common->table('hospital') . " where ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc");

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('from_info', $data);

	}



	public function from_update()

	{

		$form_action     = $_REQUEST['form_action'];

		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;

		$from_name       = trim($_REQUEST['from_name']);

		$hos_id          = trim($_REQUEST['hos_id']);

		$keshi_id        = trim($_REQUEST['keshi_id']);

		$parent_id       = trim($_REQUEST['parent_id']);

		$from_order      = trim($_REQUEST['from_order']);

		$is_show		 = $_REQUEST['is_show'];



		if(empty($from_name))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		$arr = array('from_name' => $from_name,

					 'hos_id' => $hos_id,

					 'keshi_id' => $keshi_id,

					 'parent_id' => $parent_id,

					 'from_order' => $from_order,

					 'is_show'	=> $is_show);



		if($form_action == 'update')

		{

			if(empty($from_id))

			{

				$this->common->msg($this->lang->line('no_empty'), 1);

			}

			$this->common->config('order_from_edit');

			$this->db->update($this->common->table("order_from"), $arr, array('from_id' => $from_id));

			// $send_array = array();
			// $send_data = array();
			// $send_data['from_id'] = $from_id;
			// $ireport_id = $this->common->getOne("SELECT ireport_order_from_id FROM " . $this->common->table('order_from') . " WHERE from_id = ".$from_id);
			// if(empty($ireport_id)){
			// 	$ireport_id =0 ;
			// }
			// $send_data['ireport_order_from_id'] =$ireport_id;
			// $send_data['from_name'] = $arr['from_name'];
			// $send_data['hos_id'] = '0';
			// $send_data['keshi_id'] = '0';
			// if(!empty($arr['keshi_id'])){
			// 	$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$arr['keshi_id']);
			// 	if($ireport_hos_id > 0){
			// 		$send_data['keshi_id'] = $ireport_hos_id;
			// 	}
			// }
			// $send_data['is_show'] = $arr['is_show'];
			// if(empty($parent_id)){
			// 	$parent_id =0 ;
			// }
			// $ireport_id = $this->common->getOne("SELECT ireport_order_from_id FROM " . $this->common->table('order_from') . " WHERE from_id = ".$parent_id);
			// $send_data['parent_id'] = 0;
			// if($ireport_id > 0){
			// 	$send_data['parent_id'] = $ireport_id;
			// }
			// $send_array[] =$send_data;
   //          //$this->sycn_order_from_data_to_ireport($send_array);

   //          //剔除仁爱数据推送
   //          if ($hos_id != '1') {
   //              $this->sycn_order_from_data_to_ireport($send_array);
   //          }

		}

		else

		{

			$this->common->config('order_from_add');

			$this->db->insert($this->common->table("order_from"), $arr);

			$from_id = $this->db->insert_id();

			// $send_array = array();
			// $send_data = array();
			// $send_data['from_id'] = $from_id;
			// $send_data['from_name'] = $arr['from_name'];
			// $send_data['hos_id'] = '0';
			// $send_data['keshi_id'] = '0';
			// $send_data['from_order'] = $from_order;
			// if(!empty($arr['keshi_id'])){
			// 	$ireport_hos_id = $this->common->getOne("SELECT ireport_hos_id FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$arr['keshi_id']);
			// 	if($ireport_hos_id > 0){
			// 		$send_data['keshi_id'] = $ireport_hos_id;
			// 	}
			// }
			// $send_data['is_show'] = $arr['is_show'];
			// if(empty($parent_id)){
			// 	$parent_id =0 ;
			// }
			// $ireport_id = $this->common->getOne("SELECT ireport_order_from_id FROM " . $this->common->table('order_from') . " WHERE from_id = ".$parent_id);
			// $send_data['parent_id'] = 0;
			// if($ireport_id > 0){
			// 	$send_data['parent_id'] = $ireport_id;
			// }
			// $send_array[] =$send_data;

   //          //$this->sycn_order_from_data_to_ireport($send_array);
   //          //剔除仁爱数据推送
   //          if ($hos_id != '1') {
   //              $this->sycn_order_from_data_to_ireport($send_array);
   //          }

		}



		$links[0] = array('href' => '?c=order&m=from_info&from_id=' . $from_id, 'text' => $this->lang->line('edit_back'));

		$links[1] = array('href' => '?c=order&m=from_info', 'text' => $this->lang->line('add_back'));

		$links[2] = array('href' => '?c=order&m=order_from', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function from_del()

	{

		$this->common->config('from_del');

		$from_id = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;

		if(empty($from_id))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}

		$ireport_id = $this->common->getOne("SELECT ireport_order_from_id FROM " . $this->common->table('order_from') . " WHERE from_id = ".$from_id);
		if(!empty($ireport_id)){
			$send_data = array();
			$send_data['ireport_order_from_id'] = $ireport_id;
			$this->sycn_del_order_from_data_to_ireport($send_data);
		}


		$this->db->delete($this->common->table("order_from"), array('from_id' => $from_id));

		$this->common->static_cache('delete', "order_from");

		$links[0] = array('href' => '?c=order&m=order_from', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



    public function zmt_order_info()

	{

		$data = array();

		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);

		$type     = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);

		$p = isset($_REQUEST['p'])? $_REQUEST['p']:0;

		if(empty($order_id))

		{

			$data = $this->common->config('my_profile');

		}

		else

		{

			if(($p == 1) || ($p == 2))

			{

				$data = $this->common->config('my_profile');

			}

			else

			{

				$data = $this->common->config('my_profile');

			}

			$info = $this->model->order_info($order_id);

			if(empty($info))

			{

				exit('错误的授权！');

			}

			if(($p == 2) && ($info['admin_id'] != $_COOKIE['l_admin_id']))

			{

				$data = $this->common->config('my_profile');

			}

			if($info['admin_id'] == 0)

			{

				$data = $this->common->config('my_profile');

				$p = 2;

			}

			$data['info'] = $info;

			$remark = $this->model->order_remark($order_id);

			$data['remark'] = $remark;

			$con_content = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");

			$data['con_content'] = $con_content;

			$order_data = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

			$data['order_data'] = $order_data;

		}

		$data['p'] = $p;

		$from_list = $this->model->from_order_list();

		$hospital = $this->model->hospital_order_list();

		//$keshi = $this->model->keshi_order_list();

		$type_list = $this->model->type_order_list();

		$asker_list = $this->model->asker_list(0);

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;



			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



		if(isset($_COOKIE['l_hos_id']))

		{

			$l_hos_id = explode(",", $_COOKIE['l_hos_id']);

			krsort($l_hos_id);

			$data['l_hos_id'] = $l_hos_id[0];

		}

		else

		{

			$data['l_hos_id'] = 1;

		}

		$rank_type = $this->model->rank_type();

		$data['rank_type'] = $rank_type;

		$data['type'] = $type;

		$data['province'] = $province_list;

		$data['asker_list'] = $asker_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		//$data['keshi'] = $keshi;

		$data['type_list'] = $type_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('zmt/order_info' . $type, $data);

	}



	public function order_info()

	{
		$data = array();

		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);

		$type     = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);

		$p = isset($_REQUEST['p'])? $_REQUEST['p']:0;

		if(empty($order_id))

		{

			$data = $this->common->config('order_add');

		}

		else

		{

			if(($p == 1) || ($p == 2))

			{

				$data = $this->common->config('order_add');

			}

			else

			{

				$data = $this->common->config('order_edit');

			}

			$info = $this->model->order_info($order_id);

			if(empty($info))

			{

				exit('错误的授权！');

			}

			if(($p == 2) && ($info['admin_id'] != $_COOKIE['l_admin_id']))

			{

				$data = $this->common->config('order_edit');

			}

			if($info['admin_id'] == 0)

			{

				$data = $this->common->config('order_add');

				$p = 2;

			}

		    $laiyuanwebdata =  $this->common->getAll("SELECT form,guanjianzi FROM " . $this->common->table("order_laiyuanweb") . " WHERE order_id = $order_id");
			$info['laiyuanweb'] = '';
			$info['guanjianzi'] = '';
			if(count($laiyuanwebdata) > 0){
				$info['laiyuanweb'] = $laiyuanwebdata[0]['form'];
				$info['guanjianzi'] = $laiyuanwebdata[0]['guanjianzi'];
			}
			$data['info'] = $info;

			$remark = $this->model->order_remark($order_id);

			$data['remark'] = $remark;

			$con_content = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");
			//如果权限是东莞导医则不显示
			if($_COOKIE["l_rank_id"] ==103){
				$data['con_content'] = '';
			}else{
				$data['con_content'] = $con_content;
			}
			$order_data = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

			$data['order_data'] = $order_data;

			/***
			 * 2017 07 28 获取来源网址
			*/
			$data['keshiurl_data'] = array();
			if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
				$l_hos_id = explode(",",$_COOKIE["l_hos_id"]);
				if (in_array($data['info']['hos_id'], $l_hos_id)){
					$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =".$data['info']['hos_id']." and ( keshi_id =  0 or  keshi_id in(".$_COOKIE["l_keshi_id"].")) and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
					$data['keshiurl_data'] = $this->common->getAll($sql);
				}
			}else{
				$sql = "SELECT id,title,url FROM " . $this->common->table('keshiurl') . " WHERE hos_id =".$data['info']['hos_id']." and status = 1  ORDER BY CONVERT(title USING gbk) asc ";
				$data['keshiurl_data'] = $this->common->getAll($sql);
			}
		}

		$data['p'] = $p;

		$from_list = $this->model->from_order_list();

		$hospital = $this->model->hospital_order_list();

		//$keshi = $this->model->keshi_order_list();

		$type_list = $this->model->type_order_list();

		$asker_list = $this->model->asker_list(0);

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;



			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



		if(isset($_COOKIE['l_hos_id']))

		{

			$l_hos_id = explode(",", $_COOKIE['l_hos_id']);

			krsort($l_hos_id);

			$data['l_hos_id'] = $l_hos_id[0];

		}

		else

		{

			$data['l_hos_id'] = 1;

		}

		$rank_type = $this->model->rank_type();

		$data['rank_type'] = $rank_type;

		$data['type'] = $type;

		$data['province'] = $province_list;

		$data['asker_list'] = $asker_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		//$data['keshi'] = $keshi;

		$data['type_list'] = $type_list;

		 /**2016 10 27   不具备修改 咨询员权限的人 不能更改咨询员信息**/
		 /**2016 10 27   不具备修改 患者个信息的人 不能改**/
		if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(148,$l_admin_action)){
		     	$data['order_edit_consultants'] = '1';
	         }
	         if(in_array(149,$l_admin_action)){
		     	$data['order_edit_person_info'] = '1';
	         }
		}else{
			$data['order_edit_consultants'] = '1';
			$data['order_edit_person_info'] = '1';
		}

		//寻找留联信息

		$order_liulian_id = empty($_REQUEST['order_liulian_id'])? 0:intval($_REQUEST['order_liulian_id']);

		if(!empty($order_liulian_id)){

			$data['order_liulian_id'] =$order_liulian_id;

			$sql_liulian = "SELECT o.*, p.*, p.pat_id AS p_id

				FROM " . $this->common->table('order_liulian') . " o

				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id

						WHERE o.order_id = ".$order_liulian_id;

			$liulian_info = $this->common->getRow($sql_liulian);

			if(count($liulian_info) > 0){

				$data['liulian_info'] =$liulian_info;

				 //查询 预约途径
				$sql_liulian = "SELECT * FROM " . $this->common->table('order_from') . "  WHERE  parent_id= ".$liulian_info['from_parent_id'];
				$data['order_from_two'] = $this->common->getAll($sql_liulian);

			}

			//henry 2018-05-05
			//留联备注赋值到添加预约表单中
            $sql_ll = "SELECT mark_content FROM " . $this->common->table('order_liulian_remark') . " WHERE order_id = " . $order_liulian_id;
            $ll_info = $this->common->getRow($sql_ll);
            $data['remark'] = $ll_info['mark_content'];

		}

		/***
		 * 2017 07 01 仁爱妇科 不孕
		*  查询 仁爱预约数量
		*/
		$sql_order_reanai_sum = "SELECT * FROM " . $this->common->table('order_reanai_sum') . "  WHERE  order_id = ".$order_id;
		$data['info_yd_time'] = $this->common->getRow($sql_order_reanai_sum);



		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);



		//判断当天当前用户是否已经点击显示号码
		$admin_id = intval($_COOKIE['l_admin_id']);
		$time = time();
		$s_t = strtotime(date('Y-m-d 00:00:00',$time));
		$e_t = strtotime(date('Y-m-d 23:59:59',$time));
		$where = array('admin_id'=>$admin_id,'order_id'=>$order_id,'add_time >='=>$s_t,'add_time <='=>$e_t);
		$this->db->set_dbprefix("henry_");
		$records = $this->db->select('order_id,pat_id')->get_where('show_phone_record', $where)->result_array();
		if ($records) {
			$data['phone_show'] = 1;
		} else {
			$data['phone_show'] = 0;
		}


		$this->load->view('order_info' . $type, $data);

	}



	 	/**添加留联**/

	public function order_info_liulian()

	{

		$data = array();

		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);

		$type     = empty($_REQUEST['type'])? '':trim($_REQUEST['type']);

		$p = isset($_REQUEST['p'])? $_REQUEST['p']:0;

		if(empty($order_id))

		{

			$data = $this->common->config('order_add_liulian');

		}

		else

		{

			if(($p == 1))

			{

				$data = $this->common->config('order_add_liulian');

			}else{

				$data = $this->common->config('order_edit_liulian');

			}

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

				FROM " . $this->common->table('order_liulian') . " o

				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id

						WHERE $where AND o.order_id = $order_id";

			$info = $this->common->getRow($sql);

			if(empty($info))

			{

				exit('错误的授权！');

			}

			if($info['admin_id'] == 0)

			{

				$data = $this->common->config('order_liulian_add');

				$p = 2;

			}

			$data['info'] = $info;

			//$remark = $this->model->order_remark($order_id);

			$data['remark'] = array();

			//$con_content = $this->common->getOne("SELECT con_content FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");

			$data['con_content'] = array();

			//$order_data = $this->common->getRow("SELECT data_time FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

			$data['order_data'] = array();//$order_data;

			//疾病

			$data['jb1_data'] = array();

			$data['jb2_data'] = array();

			$data['keshi_data'] = array();

			if(!empty($data['info']['keshi_id'])){

				if(!empty($data['info']['jb_id'])){

					$data['jb2_data'] = $this->common->getAll("SELECT * FROM " . $this->common->table('jibing') . " WHERE jb_id = ".$data['info']['jb_id']." ORDER BY jb_id ASC");

				}

				if(!empty($data['info']['jb_parent_id']) && !empty($data['info']['keshi_id'])){

					$keshi_data = $this->common->getAll("SELECT keshi_name FROM " . $this->common->table('keshi') . " WHERE keshi_id = ".$data['info']['keshi_id']);

					//$jibing_data = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('jibing') . " where   parent_id =0 and jb_name= '".trim($keshi_data[0]['keshi_name'])."' ORDER BY jb_id ASC");

					//if(count($jibing_data) >0){

						$data['jb1_data'] =  $this->common->getAll("SELECT * FROM " . $this->common->table('jibing') . " where   jb_id = '".$data['info']['jb_parent_id']."' ORDER BY jb_id ASC");

					//}

				}

				if(!empty($data['info']['hos_id'])){

					$data['keshi_data'] = $this->common->getAll("SELECT * FROM " . $this->common->table('keshi') . " WHERE hos_id = ".$data['info']['hos_id']." ORDER BY keshi_id ASC");

				}

			}

			//p($data['jb1_data']);die();

			//渠道二级数据

			$data['form2_data'] = array();

			if(!empty($data['info']['from_parent_id'])){

				$data['form2_data'] = $this->common->getAll("SELECT * FROM " . $this->common->table('order_from') . " WHERE parent_id = ".$data['info']['from_parent_id']." ORDER BY from_id ASC");

			}

		}

		$data['p'] = $p;



		$from_list = $this->model->from_order_list();



		$hospital = $this->model->hospital_order_list();

		//$keshi = $this->model->keshi_order_list();

		$type_list = $this->model->type_order_list();

		$asker_list = $this->model->asker_list(0);

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);

		if(isset($_COOKIE['l_hos_id']))

		{

			$l_hos_id = explode(",", $_COOKIE['l_hos_id']);

			krsort($l_hos_id);

			$data['l_hos_id'] = $l_hos_id[0];

		}

		else

		{

			$data['l_hos_id'] = 1;

		}

		$rank_type = $this->model->rank_type();

		$data['rank_type'] = $rank_type;

		$data['type'] = $type;

		$data['province'] = $province_list;

		$data['asker_list'] = $asker_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		//$data['keshi'] = $keshi;

		$data['type_list'] = $type_list;

		/**2016 10 27   不具备修改 咨询员权限的人 不能更改咨询员信息**/
		/**2016 10 27   不具备修改 患者个信息的人 不能改**/
		if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(148,$l_admin_action)){
		     	$data['order_edit_consultants'] = '1';
	         }
	         if(in_array(149,$l_admin_action)){
		     	$data['order_edit_person_info'] = '1';
	         }
		}else{
			$data['order_edit_consultants'] = '1';
			$data['order_edit_person_info'] = '1';
		}


		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_info_liulian' . $type, $data);

	}



	/**留联预约单添加**/

	public function order_update_liulian()

	{

		$form_action     = $_REQUEST['form_action'];

		$order_id        = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$p_id            = isset($_REQUEST['pad_id'])? intval($_REQUEST['pad_id']):0;

		$p               = isset($_REQUEST['p'])? intval($_REQUEST['p']):0;

		$remark          = trim($_REQUEST['remark']);

		$sms_themes      = isset($_REQUEST['sms_themes'])? intval($_REQUEST['sms_themes']):0;

		$sms_content     = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';

		$type            = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$order_no        = trim($_REQUEST['order_no']);

		$from_parent_id  = intval($_REQUEST['from_parent_id']);

		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']) : 0;

		$is_first        = intval($_REQUEST['is_first']);

		$from_value      = trim($_REQUEST['from_value']);

		$hos_id          = intval($_REQUEST['hos_id']);

		$keshi_id        = intval($_REQUEST['keshi_id']);

		$type_id         = intval($_REQUEST['type_id']);

		$jb_parent_id    = intval($_REQUEST['jibing_parent_id']);

		$jb_id           = intval($_REQUEST['jibing_id']);

		$admin_id        = intval($_REQUEST['admin_id']);

		$pat_name        = trim($_REQUEST['patient_name']);

		$pat_sex         = intval($_REQUEST['pat_sex']);

		$pat_phone       = trim($_REQUEST['patient_phone']);

		$laiyuanweb       = trim($_REQUEST['laiyuanweb']);//来源网址

		$guanjianzi       = trim($_REQUEST['guanjianzi']);

		$pat_qq       = trim($_REQUEST['patient_qq']);

		$pat_weixin       = trim($_REQUEST['patient_weixin']);

		$pat_age         = trim($_REQUEST['patient_age']);

		$order_time     = !empty($_REQUEST['order_time'])? trim($_REQUEST['order_time']):time();

		$order_time_duan_d = !empty($_REQUEST['order_time_duan_d'])? trim($_REQUEST['order_time_duan_d']):'';

		$order_time_duan_j = !empty($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';

		$duan_confirm      = intval($_REQUEST['duan_confirm']);

		$pat_province    = intval($_REQUEST['province']);

		$pat_city        = intval($_REQUEST['city']);

		$pat_area        = intval($_REQUEST['area']);

		$pat_address     = trim($_REQUEST['patient_address']);

		$con_content =  stripslashes(trim($_REQUEST['con_content']));

		$data_time       = trim($_REQUEST['data_time']);

		$data_time       = strtotime($data_time);

		$yunzhou         = intval($_REQUEST['yunzhou']);

		$order_time_duan_j=  substr($order_time_duan_j,0,5);

		$order_time_duan = ($duan_confirm == 1)? $order_time_duan_d:$order_time_duan_j;

		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';

		$order_time_type = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		if(empty($pat_name))

		{

			$this->common->msg("患者姓名不能为空！", 1);

		}

		if(empty($admin_id))

		{

			$this->common->msg("咨询员不能为空！", 1);

		}

		if(empty($order_no))//预约单号自增

		{

			$order_no = file_get_contents("./application/cache/static/order_no_liulian.txt");

			$zm = array('1' => 'A',



					'2' => 'B',



					'3' => 'C',



					'4' => 'D',



					'5' => 'E',



					'6' => 'F',



					'7' => 'G',



					'8' => 'H',



					'9' => 'I',



					'10' => 'J',



					'11' => 'K',



					'12' => 'L',



					'13' => 'M',



					'14' => 'N',



					'15' => 'O',



					'16' => 'P',



					'17' => 'Q',



					'18' => 'R',



					'19' => 'S',



					'20' => 'T',



					'21' => 'U',



					'22' => 'V',



					'23' => 'W',



					'24' => 'X',



					'25' => 'Y',



					'26' => 'Z');





			if(empty($order_no)){
				$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
			}

			if(empty($order_no))



			{



				$order_no = "AA0001";



			}



			else



			{



				$order_no_zimu = substr($order_no, 0, 2);



				$order_no_shuzi = substr($order_no, 2, 4);



				$order_no_shuzi = intval($order_no_shuzi) + 1;







				if($order_no_shuzi >= 9999)



				{



					$order_no_zimu_a = substr($order_no_zimu, 0, 1);



					$order_no_zimu_b = substr($order_no_zimu, 1, 1);



					$mz = array_flip($zm);



					if($order_no_zimu_b == 'Z')



					{



						$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];



						$order_no_zimu_b = 'A';



					}



					else



					{



						$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];



					}



					$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;



					$order_no_shuzi = '0001';



				}



				else



				{



					$zimu_len = 4 - strlen($order_no_shuzi);



					for($i = 1; $i <= $zimu_len; $i ++)



					{



					$order_no_shuzi = "0" . $order_no_shuzi;



					}



					}



					$order_no = $order_no_zimu . $order_no_shuzi;



			}



			file_put_contents("./application/cache/static/order_no_liulian.txt", $order_no);



		}



		$pat_phone = explode("/", $pat_phone);



		$patient = array(

		'pat_name' => $pat_name,

		'pat_sex' => $pat_sex,

		'pat_age' => $pat_age,

		'pat_province' => $pat_province,

		'pat_city' => $pat_city,

		'pat_area' => $pat_area,

		'pat_address' => $pat_address,

		'pat_qq' => $pat_qq,

		'pat_weixin' => $pat_weixin,

		'pat_phone' => $pat_phone[0],

		'pat_phone1' => isset($pat_phone[1])? $pat_phone[1]:'');

		$admin_name = $this->common->getOne("SELECT admin_name FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");

		$order = array('order_no' => $order_no,

		'is_first' => $is_first,

		'admin_id' => $admin_id,

		'admin_name' => $admin_name,

		'order_time' => '',

		'from_parent_id' => $from_parent_id,

		'from_id' => $from_id,

		'from_value' => $from_value,

		'hos_id' => $hos_id,

		'keshi_id' => $keshi_id,

		'type_id' => $type_id,

		'jb_parent_id' => $jb_parent_id,

		'laiyuanweb' => $laiyuanweb,

		'guanjianzi' => $guanjianzi,

		'remark' => '',

		'con_content' => $con_content,

		'jb_id' => $jb_id);



		if($form_action == 'update')

		{

			$come_time   = isset($_REQUEST['come_time'])? trim($_REQUEST['come_time']):'';

			$doctor_name = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';

			if($come_time != "")

			{

				$order['come_time'] = strtotime($come_time);

				$order['doctor_name'] = $doctor_name;

			}

			$this->db->update($this->common->table("patient"), $patient, array('pat_id' => $p_id));

			$this->db->update($this->common->table("order_liulian"), $order, array('order_id' => $order_id));


			//插入备注
			if(!empty($remark)){
				$order_remark  = array();
				$order_remark['order_id'] = $order_id;
				$order_remark['mark_content'] = $remark;
				$order_remark['admin_id'] = $_COOKIE['l_admin_id'];
				$order_remark['admin_name'] = $_COOKIE['l_admin_name'];
				if(empty($order_remark['admin_name'])){
					$order_remark['admin_name'] = '';
				}
				$order_remark['mark_time'] = time();
				$order_remark['mark_type'] = 1;
				$order_remark['type_id'] =0;
				$this->db->insert($this->common->table("order_liulian_remark"), $order_remark);
			}

		}



		else



		{    $this->common->config('order_add_liulian');







		$is_order = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order_liulian') . " WHERE order_no = '" . $order_no . "'");



		if($is_order)



		{



			$this->common->msg("请勿重复提交！", 1);



		}



		$this->db->insert($this->common->table("patient"), $patient);







		$pat_id = $this->db->insert_id();



		$order['pat_id'] = $pat_id;



		$order['order_addtime'] = time();



		$this->db->insert($this->common->table("order_liulian"), $order);



		$order_id = $this->db->insert_id();

		//插入备注
			if(!empty($remark)){
				$order_remark  = array();
				$order_remark['order_id'] = $order_id;
				$order_remark['mark_content'] = $remark;
				$order_remark['admin_id'] = $_COOKIE['l_admin_id'];
				$order_remark['admin_name'] = $_COOKIE['l_admin_name'];
				if(empty($order_remark['admin_name'])){
					$order_remark['admin_name'] = '';
				}
				$order_remark['mark_time'] = time();
				$order_remark['mark_type'] = 1;
				$order_remark['type_id'] =0;
				$this->db->insert($this->common->table("order_liulian_remark"), $order_remark);
			}

		}





		$msg_detail = $this->lang->line('success');



		if($type == 'mi')



		{



			echo "<script language=\"javascript\">window.opener.location.reload(); window.close();</script>";



		}else{



			$links[1] = array('href' => '?c=order&m=order_info_liulian', 'text' => $this->lang->line('add_back'));



			$links[2] = array('href' => '?c=order&m=order_list_liulian', 'text' => $this->lang->line('list_back'));



			$this->common->msg($msg_detail, 0, $links, true, true,3);



		}

	}



	/**添加留联**/

	public function order_info_liulian_setok()

	{

		$data = array();

		$order_id = empty($_REQUEST['order_id'])? 0:intval($_REQUEST['order_id']);

		$data = $this->common->config('order_info_liulian_setok');



		$where = '1';

		if(!empty($_COOKIE["l_hos_id"]))

		{

			$where .= " AND o.hos_id IN (" . $_COOKIE["l_hos_id"] . ")";

		}

		if(!empty($_COOKIE["l_keshi_id"]))

		{

			$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

		}

		$sql = "SELECT o.order_id FROM " . $this->common->table('order_liulian') . " o WHERE $where AND o.order_id = $order_id";

		$info = $this->common->getRow($sql);

		if(empty($info))

		{

			exit('错误的授权！');

		}

		if($info['admin_id'] == 0)

		{

			$data = $this->common->config('order_info_liulian_setok');

			$p = 2;

		}

		$data['info'] = $info;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_info_liulian_setok' . $type, $data);

	}



	/**留联预约单添加**/

	public function order_update_liulian_setok()

	{

		$order_id        = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$order_no_yy        = isset($_REQUEST['order_no_yy'])? intval($_REQUEST['order_no_yy']):0;

		if(empty($order_no_yy))

		{

			$this->common->msg("预约单号不能为空！", 1);

		}

		if(empty($_COOKIE['l_admin_id']))

		{

			$this->common->msg("咨询员不能为空！", 1);

		}

		$order = array();

		$order['order_no_yy']= $order_no_yy;
		$order['order_no_yy_time']= time();

		if($_COOKIE['l_rank_id'] == 1 || $_COOKIE['l_rank_id'] == 2 || $_COOKIE['l_rank_id'] == 3){

			$this->db->update($this->common->table("order_liulian"), $order, array('order_id' => $order_id));

		}else{

			$this->db->update($this->common->table("order_liulian"), $order, array('order_id' => $order_id,'admin_id'=>$_COOKIE['l_admin_id']));

		}

		echo "<script language=\"javascript\">window.opener.location.reload(); window.close();</script>";

    }



	public function order_no_liulian_ajax()

	{

		$order_no = file_get_contents("./application/cache/static/order_no_liulian.txt");

		$zm = array('1' => 'A',

				'2' => 'B',

				'3' => 'C',

				'4' => 'D',

				'5' => 'E',

				'6' => 'F',

				'7' => 'G',

				'8' => 'H',

				'9' => 'I',

				'10' => 'J',

				'11' => 'K',

				'12' => 'L',

				'13' => 'M',

				'14' => 'N',

				'15' => 'O',

				'16' => 'P',

				'17' => 'Q',

				'18' => 'R',

				'19' => 'S',

				'20' => 'T',

				'21' => 'U',

				'22' => 'V',

				'23' => 'W',

				'24' => 'X',

				'25' => 'Y',

				'26' => 'Z');

		if(empty($order_no)){
			$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
		}
		if(empty($order_no))

		{

			$order_no = "AA0001";

		}

		else

		{

			$order_no_zimu = substr($order_no, 0, 2);

			$order_no_shuzi = substr($order_no, 2, 4);

			$order_no_shuzi = intval($order_no_shuzi) + 1;

			if($order_no_shuzi >= 9999)

			{

				$order_no_zimu_a = substr($order_no_zimu, 0, 1);

				$order_no_zimu_b = substr($order_no_zimu, 1, 1);

				$mz = array_flip($zm);

				if($order_no_zimu_b == 'Z')

				{

					$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];

					$order_no_zimu_b = 'A';

				}

				else

				{

					$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];

				}

				$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;

				$order_no_shuzi = '0001';

			}

			else

			{

				$zimu_len = 4 - strlen($order_no_shuzi);

				for($i = 1; $i <= $zimu_len; $i ++)

				{

				$order_no_shuzi = "0" . $order_no_shuzi;

				}

			}

			$order_no = $order_no_zimu . $order_no_shuzi;

			}

			file_put_contents("./application/cache/static/order_no_liulian.txt", $order_no);

			echo $order_no;

	}



	public function use_no_liulian_ajax()

	{

		$order_no = trim($_REQUEST['order_no']);

		$havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order_liulian') . " WHERE order_no = '" . $order_no . "'");

		if($havd)

		{

			echo 1;exit();

		}

		else

		{

			$str_len = strlen($order_no);

		    if($str_len != 6)

			{

				echo 2;exit();

			}

		}

	}



	//预约列表

	public function order_list_liulian()

	{

		$data = array();

		$data           = $this->common->config('order_list_liulian');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$patient_weixin  = isset($_REQUEST['p_w'])? trim($_REQUEST['p_w']):'';//病人微信

		$patient_qq  = isset($_REQUEST['p_q'])? trim($_REQUEST['p_q']):'';//病人QQ

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind           = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;

		$excel_status           = isset($_REQUEST['excel_status'])? trim($_REQUEST['excel_status']):'';//导出excel的标志  有值则导出  无值则查询

		$order_no_yy_check  = isset($_REQUEST['order_no_yy_check'])? intval($_REQUEST['order_no_yy_check']):0;//是否转为预约

		$group       = isset($_REQUEST['group'])? intval($_REQUEST['group']):0;

		$expired       = isset($_REQUEST['expired'])? intval($_REQUEST['expired']):0;


		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['p_w']      = $patient_weixin;

		$data['p_q']      = $patient_qq;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

		$data['order_no_yy_check']       = $order_no_yy_check;

		$data['is_group']        = $group;

		$data['is_expired']        = $expired;


		//判断日期是否为空

		$parm = array();//关于咨询员查看数据的参数 数组

		$order_query_seven_data = '0';

		$order_check_seven_data = '0';//判斷是否是今天

		//时间搜索权限

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			$cookie_start_time =  strtotime($start);

			$cookie_end_time =  strtotime($end);

			setcookie('start_time',$cookie_start_time);

			setcookie('end_time',$cookie_end_time);

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

				$order_check_seven_data = '1';

			}

		}



		//关于咨询员查看数据的参数 数组

		$data['order_query_seven_data']       = $order_query_seven_data;

		$parm['order_query_seven_data'] = $order_query_seven_data;

		$parm['order_check_seven_data'] = $order_check_seven_data;

		$parm['w_start'] = '';

		$parm['w_end'] = '';

		$parm['keshi_id_str'] = '4,85,28,88';

		$parm['order_type'] = $order_type;

		$parm['huanzhe_check'] = '0';

		//以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

		//而$[start_date]表示xx-xx-xx这种格式的日期

		//以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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

		$from_list = $this->model->from_order_list(-1);

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

		//获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();

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

		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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

		}
		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}

		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类

		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}

		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}

		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}

		if(!empty($from_id))

		{

		    $where .= ' AND o.from_id = ' . $from_id;

		}

		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}

		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}

		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}

		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		if($bind == 1){

			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){

			$where .= ' AND  p.pat_weixin <> ""';

		}

		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}

		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}

		if(!empty($order_no_yy_check))

		{

			if($order_no_yy_check == 1){

				$where .= " AND o.order_no_yy is null";

			}else if($order_no_yy_check == 2){

				$where .= " AND o.order_no_yy is not null ";

			}

		}

		if($order_time_type == 1)

		{

			$where .= ' AND o.order_time = 0';

		}

		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}

		$where .= " AND o.order_addtime >= ".$w_start." AND o.order_addtime <= ".$w_end;

		// if($order_type == 1)

		// {
		// 	//预约登记时间

		// 	$where .= " AND o.order_addtime >= ".$w_start." AND o.order_addtime <= ".$w_end;

		// 	$orderby .= ',o.order_addtime DESC ';

		// 	//关于咨询员查看数据的参数 数组

		// 	$parm['w_start'] = $w_start;

		// 	$parm['w_end'] = $w_end;

		// }



        if (!empty($expired)) {

			$hf_time = time() - 30*24*60*60;
			$ex_time = time() - 3*30*24*60*60;

	        //p($this->db->last_query());die();

	        //回访超过一个月的orderid
	        $o_h_l = $this->common->getAll("SELECT order_id FROM hui_order_liulian_remark as t WHERE t.mark_type =3 and t.mark_time < $hf_time and  t.mark_time = (SELECT max(mark_time) FROM hui_order_liulian_remark WHERE order_id = t.order_id )");
	        $hf_expired_ids = array();
	        if (!empty($o_h_l)) {
	        	foreach ($o_h_l as $value) {
	        		$hf_expired_ids[] = $value['order_id'];
	        	}
	        }

	        //登记超过3个月的orderid
	        $o_expired_ids = array();
	        $o_o_l = $this->common->getAll("select order_id from hui_order_liulian as o where $where and o.order_addtime < $ex_time ");
	        if (!empty($o_o_l)) {
	        	foreach ($o_o_l as $value) {
	        		$o_expired_ids[] = $value['order_id'];
	        	}
	        }

	        //回访未超过一个月的orderid
	        $w_o_h_l = $this->common->getAll("SELECT order_id FROM   " . $this->common->table('order_liulian_remark') . "  WHERE mark_type = 3 and mark_time >= " . $hf_time . " order by mark_time desc");
	        $w_hf_expired_ids = array();
	        if (!empty($w_o_h_l)) {
	        	foreach ($w_o_h_l as $value) {
	        		$w_hf_expired_ids[] = $value['order_id'];
	        	}
	        }

	        //登记未超过3个月的orderid
	        $w_o_expired_ids = array();
	        $w_o_o_l = $this->common->getAll("select order_id,order_addtime from hui_order_liulian as o where $where and o.order_addtime >= $ex_time ");
	        // if (!empty($w_o_o_l)) {
	        // 	foreach ($w_o_o_l as $value) {
	        // 		$w_o_expired_ids[] = $value['order_id'];
	        // 	}
	        // }

	        //登记时间在3个月内并且未回访的orderid
	        $null_remark_ids = array();
	        foreach ($w_o_o_l as $w_o_expired) {
	        	if (!in_array($w_o_expired['order_id'], $w_hf_expired_ids) && $w_o_expired['order_addtime']<$hf_time) {
	        		//p($w_o_expired['order_id']);
	        		$null_remark_ids[] = $w_o_expired['order_id'];
	        	}
	        }


	        $merge_expired = array_merge($hf_expired_ids,$o_expired_ids,$null_remark_ids);

	        $liulian_expired_ids = array_unique($merge_expired);


	        //p($null_remark_ids);die();


        	$liulian_expired_ids_str = implode(',', $liulian_expired_ids);

        	if ($expired == 1 && !empty($liulian_expired_ids)) {
        		//已过期
        		$where .= " AND o.order_id in($liulian_expired_ids_str) ";
        	} elseif ($expired == 2 && !empty($liulian_expired_ids)){
        		//未过期
        		$where .= " AND o.order_id not in($liulian_expired_ids_str) ";
        	}
        }



		//获取留联分组
		$ll_group_a_arr = $this->db->query("select liulian_id from henry_liulian_group where type = 1")->result_array();
		$ll_group_b_arr = $this->db->query("select liulian_id from henry_liulian_group where type = 2")->result_array();
		$ll_group_c_arr = $this->db->query("select liulian_id from henry_liulian_group where type = 3")->result_array();
		$ll_group_d_arr = $this->db->query("select liulian_id from henry_liulian_group where type = 4")->result_array();

		if (!empty($ll_group_a_arr)) {
			foreach ($ll_group_a_arr as $value) {
				$ll_group_a_ids[] = $value['liulian_id'];
			}
		} else {
			$ll_group_a_ids = array();
		}
		if (!empty($ll_group_b_arr)) {
			foreach ($ll_group_b_arr as $value) {
				$ll_group_b_ids[] = $value['liulian_id'];
			}
		} else {
			$ll_group_b_ids = array();
		}
		if (!empty($ll_group_c_arr)) {
			foreach ($ll_group_c_arr as $value) {
				$ll_group_c_ids[] = $value['liulian_id'];
			}
		} else {
			$ll_group_c_ids = array();
		}
		if (!empty($ll_group_d_arr)) {
			foreach ($ll_group_d_arr as $value) {
				$ll_group_d_ids[] = $value['liulian_id'];
			}
		} else {
			$ll_group_d_ids = array();
		}

		if (!empty($group)) {

			//BCD组所有ID
			$bc_groups = array_merge($ll_group_b_ids,$ll_group_c_ids,$ll_group_d_ids);

			$liulian_a_ids_str = implode(',', $ll_group_a_ids);
			$bc_groups_ids_str = implode(',', $bc_groups);
			$liulian_b_ids_str = implode(',', $ll_group_b_ids);
			$liulian_c_ids_str = implode(',', $ll_group_c_ids);
			$liulian_d_ids_str = implode(',', $ll_group_d_ids);

			if ($group == 1 && !empty($bc_groups)) {
				$where .= " AND o.order_id not in($bc_groups_ids_str) ";
			}
			if ($group == 2 && !empty($liulian_b_ids_str)) {
				$where .= " AND o.order_id in($liulian_b_ids_str) ";
			} elseif ($group == 2 && empty($liulian_b_ids_str)) {
				$where .= " AND o.order_id in(0) ";
			}
			if ($group == 3 && !empty($liulian_c_ids_str)) {
				$where .= " AND o.order_id in($liulian_c_ids_str) ";
			} elseif ($group == 3 && empty($liulian_c_ids_str)) {
				$where .= " AND o.order_id in(0) ";
			}
			if ($group == 4 && !empty($liulian_d_ids_str)) {
				$where .= " AND o.order_id in($liulian_d_ids_str) ";
			} elseif ($group == 4 && empty($liulian_d_ids_str)) {
				$where .= " AND o.order_id in(0) ";
			}
		}


		//查询回访人员
		$hf_name     = $_REQUEST['a_h'];
		$data['a_h'] = $hf_name;
		if(!empty($hf_name)){
			$order_remark =  $this->common->getAll("select DISTINCT order_id from " . $this->common->table('order_liulian_remark') . " where admin_name = '".$hf_name."' and  mark_type = 3   AND mark_time between ".$w_start." AND ".$w_end );
			$order_remark_order_no_str = '';
			foreach ($order_remark as $order_remark_temp){
				if(empty($order_remark_order_no_str)){
					$order_remark_order_no_str = $order_remark_temp['order_id'];
				}else{
					$order_remark_order_no_str .= ','.$order_remark_temp['order_id'];
				}
			}
			$parm = array();
			if(!empty($order_remark_order_no_str)){
				$where = " o.order_id in(".$order_remark_order_no_str.")";
			}else{
				$w_start = strtotime(date("Y-m-d",time()) . ' 00:00:00');
				$w_end = strtotime(date("Y-m-d",time()) . ' 23:59:59');
				$where = " o.order_id in(0)";
			}
		}else{

			/* 当输入患者的信息时，其他的搜索条件都不需要了 */

			if(!empty($patient_name))

			{

				$where = " p.pat_name = '". $patient_name . "'";

				$data['p_n']      = $patient_name;

				$data['p_p']      = "";

				$data['p_w']      = '';

				$data['p_q']      = '';

				$data['o_o']      = "";

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}

			if(!empty($_COOKIE["l_rank_id"])){

				$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

				$rank_type = $this->common->getRow($sql);

			}

			if(!empty($patient_phone))

			{

				$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

				$data['p_n']      = "";

				$data['p_p']      = $patient_phone;

				$data['o_o']      = "";

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}

			if(!empty($patient_weixin))

			{

				$where = " (p.pat_weixin = '". $patient_weixin . "')";

				$data['p_w']      = $patient_weixin;

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

			}



			if(!empty($patient_qq))

			{

				$where = " (p.pat_qq = '". $patient_qq . "')";

				$data['p_q']      = $patient_qq;

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

			}



			if(!empty($order_no))

			{

				$where = " ( o.order_no = '" . $order_no . "'  or  o.order_no_yy = '" . $order_no . "' ) ";

				$data['p_n']      = "";

				$data['p_p']      = "";

				$data['o_o']      = $order_no;

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}

		}

		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ",o.order_id DESC";

		}

		//订单编号  患者名称 电话,微信,QQ  咨询员名称 搜索 是独立的 不带其他条件

		if(!empty($patient_name) || !empty($order_no) || !empty($patient_phone)  || !empty($patient_weixin)  || !empty($patient_qq) )

		{

			$where = ' 1=1 ';

			if(!empty($patient_name))

			{

				// $where  .= " and p.pat_name like  '%". $patient_name . "%'";
				$where  .= " and p.pat_name =  '". $patient_name . "'";

			}

			if(!empty($patient_phone))

			{

				//$where .= " and (p.pat_phone like '%". $patient_phone . "%' OR p.pat_phone1 like '%". $patient_phone . "%')";
				$where .= " and (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			}

			if(!empty($patient_weixin))

			{

				//$where .= " and (p.pat_weixin like '%". $patient_weixin . "%')";
				$where .= " and (p.pat_weixin = '". $patient_weixin . "')";

			}

			if(!empty($patient_qq))

			{

				//$where .= " and (p.pat_qq like '%". $patient_qq . "%')";
				$where .= " and (p.pat_qq = '". $patient_qq . "')";

			}

			if(!empty($order_no))

			{

				$where .= " and (o.order_no = '" . $order_no . "' or o.order_no_yy = '" . $order_no . "')";

			}

		}



		$order_count =  $this->common->getAll("SELECT count(order_id) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id  WHERE 1 and ".$where);

		$data['total_rows'] = $order_count[0]['count'];

		if(!empty($order_no))

		{

			$order_ok_count =  $this->common->getAll("SELECT count(order_id) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id  WHERE ".$where);

			$order_come_count =  $this->common->getAll("SELECT count(o.order_no_yy) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('order') . " as ol  on ol.order_no = o.order_no_yy left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id WHERE ol.is_come = 1 and ".$where);
		} else {
			$order_ok_count =  $this->common->getAll("SELECT count(order_id) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id  WHERE o.order_no_yy is not null  and ".$where);
			$order_come_count =  $this->common->getAll("SELECT count(o.order_no_yy) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('order') . " as ol  on ol.order_no = o.order_no_yy left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id WHERE ol.is_come = 1 and o.order_no_yy is not null and o.order_no_yy != '' and  ".$where);
		}


		$data['total_ok_rows'] = $order_ok_count[0]['count'];

		 //查询到诊人数

		$data['dz_rows'] = $order_come_count[0]['count'];


		$config = page_config();



		$data['down_url'] = '?c=order&m=order_liulian_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL





		$config['total_rows'] = $order_count[0]['count'];


	    if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=order_list_liulian&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'].'&group=' . $_REQUEST['group']. '&expired=' . $_REQUEST['expired']. '&order_no_yy_check=' . $_REQUEST['order_no_yy_check'];
		}else{
			$config['base_url'] =  '?c=order&m=order_list_liulian&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'].'&group=' . $_REQUEST['group']. '&expired=' . $_REQUEST['expired']. '&a_h=' . $_REQUEST['a_h']. '&order_no_yy_check=' . $_REQUEST['order_no_yy_check'];
		}


		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

		//获取相关数据

		$parm['rank_id'] = $data['admin']['rank_id'];

         $order_list = $this->common->getAll("SELECT o.*,p.*,od.is_come as order_is_come FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p on o.pat_id = p.pat_id left join " . $this->common->table('order') . " as od on o.order_no_yy is not null and o.order_no_yy != '' and od.order_no = o.order_no_yy  WHERE 1 and ".$where.$orderby." LIMIT ".$page.",".$per_page);
         $order_id_str = 0 ;
        foreach($order_list  as $order_list_data){
        	if($order_id_str == 0){
        		$order_id_str = $order_list_data['order_id'];
        	}else{
        		$order_id_str =  $order_id_str.','.$order_list_data['order_id'];
        	}
        }

        $data['order_remark_list'] = $this->common->getAll("SELECT * FROM   " . $this->common->table('order_liulian_remark') . "  WHERE  order_id in(".$order_id_str.")  order by mark_time desc");

        $order_hf_lists = array();
        foreach ($data['order_remark_list'] as $key => $value) {
        	if ($value['mark_type'] == 3) {
        		$order_hf_lists[$value['order_id']][] = $value;
        	}
        }


    	foreach ($order_list as $key => $value) {
    		if (in_array($value['order_id'], $ll_group_a_ids)) {
    			$order_list[$key]['is_group'] = 1;
    		} elseif (in_array($value['order_id'], $ll_group_b_ids)) {
    			$order_list[$key]['is_group'] = 2;
    		} elseif (in_array($value['order_id'], $ll_group_c_ids)) {
    			$order_list[$key]['is_group'] = 3;
    		} elseif (in_array($value['order_id'], $ll_group_d_ids)) {
    			$order_list[$key]['is_group'] = 4;
    		} else {
    			$order_list[$key]['is_group'] = 1;
    		}

    		if ( $value['hos_id'] == 1 && $value['keshi_id'] == 32 ) {
    			//仁爱不孕不育
    			$c_time = time();
    			$ex_time = $value['order_addtime']+30*24*60*60*3;
    			$last_visit_time = $order_hf_lists[$value['order_id']][0]['mark_time'];

    			$v_time = $last_visit_time+30*24*60*60;
    			$vx_time = $value['order_addtime']+30*24*60*60;

    			if ( $ex_time < $c_time ) {
    				//登记时间超过3个月过期
    				$order_list[$key]['is_expired'] = 1;
    				$order_list[$key]['is_expired_msg'] = '登记时间超过3个月过期';
    			} elseif (!empty($last_visit_time) && $v_time < $c_time) {
    				//最新回访超过一个月过期
    				$order_list[$key]['is_expired'] = 1;
    				$order_list[$key]['is_expired_msg'] = '最新回访超过一个月过期';
    			} elseif (empty($last_visit_time) && $vx_time < $c_time){
    				//登记时间超过一个月并且未回访过期
    				$order_list[$key]['is_expired'] = 1;
    				$order_list[$key]['is_expired_msg'] = '登记时间超过一个月并且未回访过期';
    			} else {
    				$order_list[$key]['is_expired'] = 0;
    			}
    		}

        }

		//添加所属医院所有医生代码开始

		$hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

		$doctor_list=$this->model->doctor_list($hos_array);//依据字符串调用相关功能

		$data['doctor_list']=$doctor_list;//新增医生列表

		//添加所属医院所有医生代码结束

		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);

		/** 2016 10 27  新增加 表头显示下拉框的 效果**/

		$appointment_route = array();//预约途径

		$appointment_section= array();//预约科室

		$appointment_disease= array();//预约病种

		$consult_a_doctor= array();//咨询医生

		$data['appointment_route'] = $appointment_route;

		$data['appointment_section'] = $appointment_section;

		$data['appointment_disease'] = $appointment_disease;

		$data['consult_a_doctor'] = $consult_a_doctor;

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['huifang'] = $this->set_huifang();

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

		$data['order_count'] = $order_count[0]['count'];

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		if($type == 'mi')

		{

			$this->load->view('order_list_mi_liulian', $data);

		}

		else

		{

			$this->load->view('order_list_liulian', $data);

		}

	}

	//显示整个订单的详情，包括订单的日志信息

	public function order_detail_liulian(){

		$order_id=isset($_REQUEST['order_id'])?$_REQUEST['order_id']:0;

		$where=" o.order_id=".$order_id;

		$orderby=" order by o.order_id";

		$order_detail=$this->model->order_list($where,0,1,$orderby);

		if($order_id == 0){

			echo '';exit;

		}

		$sql = "SELECT o.*, p.* FROM " . $this->common->table('order_liulian') . " o

				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id

				WHERE o.order_id = ".$order_id;

		$row = $this->common->getAll($sql);

		$order_detail = array();

		foreach($row as $val)

		{

			$order_detail[$val['order_id']]['order_id'] = $val['order_id'];

			$order_detail[$val['order_id']]['is_to_order'] = $val['is_to_order'];

			$order_detail[$val['order_id']]['remark'] = $val['remark'];

			$order_detail[$val['order_id']]['con_content'] = $val['con_content'];

			$order_detail[$val['order_id']]['is_to_gonghai'] = $val['is_to_gonghai'];

			$order_detail[$val['order_id']]['order_no'] = $val['order_no'];

			$order_detail[$val['order_id']]['is_first'] = $val['is_first'];

			$order_detail[$val['order_id']]['pat_name'] = $val['pat_name'];

			$order_detail[$val['order_id']]['pat_phone'] = $val['pat_phone'];

			$order_detail[$val['order_id']]['pat_qq'] = $val['pat_qq'];

			$order_detail[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];

			$order_detail[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];

			if($val['pat_sex'] == 1)

			{

				$order_detail[$val['order_id']]['pat_sex'] = $this->lang->line('man');

			}

			else

			{

				$order_detail[$val['order_id']]['pat_sex'] = $this->lang->line('woman');

			}

			$order_detail[$val['order_id']]['pat_age'] = $val['pat_age'];

			$order_detail[$val['order_id']]['pat_province'] = $val['pat_province'];

			$order_detail[$val['order_id']]['pat_city'] = $val['pat_city'];

			$order_detail[$val['order_id']]['pat_area'] = $val['pat_area'];

			$order_detail[$val['order_id']]['pat_qq'] = $val['pat_qq'];

			$order_detail[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];

			$order_detail[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);

			if(!empty($val['order_time']))

			{

				$order_detail[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);

			}

			else

			{

				$order_detail[$val['order_id']]['order_time'] = date("Y-m-d",time());

			}

			$order_detail[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];

			$order_detail[$val['order_id']]['come_time'] = $val['come_time'];

			$order_detail[$val['order_id']]['doctor_time'] = $val['doctor_time'];

			$order_detail[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];

			$order_detail[$val['order_id']]['from_id'] = $val['from_id'];

			$order_detail[$val['order_id']]['hos_id'] = $val['hos_id'];

			$order_detail[$val['order_id']]['keshi_id'] = $val['keshi_id'];

			$order_detail[$val['order_id']]['data_time'] = $val['data_time'];

			$order_detail[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];

			$order_detail[$val['order_id']]['jb_id'] = $val['jb_id'];

			$order_detail[$val['order_id']]['order_type'] = $val['order_type'];

			$order_detail[$val['order_id']]['admin_name'] = $val['admin_name'];

			$order_detail[$val['order_id']]['doctor_name'] = $val['doctor_name'];

			$order_detail[$val['order_id']]['admin_id'] = $val['admin_id'];

			$order_detail[$val['order_id']]['is_come'] = $val['is_come'];

			$order_detail[$val['order_id']]['from_value'] = $val['from_value'];

		}

		//获取相关科室和疾病的信息

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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

		$from_list = $this->model->from_order_list(-1);



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

		$type_list = $this->model->type_order_list();

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

		$hos_id_arr = array();

		$keshi_id_arr = array();

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

		$data['from_arr'] = $from_arr;

		$data['from_list'] = $from_list;

		$data['type_list']=$type_list;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$data['hospital'] = $hospital;

		//获取相关地区的信息

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		//公海记录
        $sql = "select a.*,b.admin_name from henry_gonghai_liulian_log as a left join hui_admin as b on a.admin_id = b.admin_id where a.order_id=".$order_id." order by a.id desc";
        $logData = $this->common->getAll($sql);

        $data['gonghai_log'] = $logData;


		$province_list = array_merge($flag_1,$flag_2);

		$data['order_detail']=$order_detail;

		$data['province']=$province_list;

		$data['area']=$area;

		$this->load->view('order_detail_liulian.php',$data);

	}



	//预约列表  个人预约列表

	public function order_list_person_liulian()

	{

		$data = array();

		$data           = $this->common->config('order_list_person_liulian');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$patient_weixin  = isset($_REQUEST['p_w'])? trim($_REQUEST['p_w']):'';//病人微信

		$patient_qq  = isset($_REQUEST['p_q'])? trim($_REQUEST['p_q']):'';//病人QQ



		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind           = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;

		$excel_status           = isset($_REQUEST['excel_status'])? trim($_REQUEST['excel_status']):'';//导出excel的标志  有值则导出  无值则查询

		$order_no_yy_check  = isset($_REQUEST['order_no_yy_check'])? intval($_REQUEST['order_no_yy_check']):0;//是否转为预约





		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['p_w']      = $patient_weixin;

		$data['p_q']      = $patient_qq;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

		$data['order_no_yy_check']       = $order_no_yy_check;

		//判断日期是否为空

		$parm = array();//关于咨询员查看数据的参数 数组

		$order_query_seven_data = '0';

		$order_check_seven_data = '0';//判斷是否是今天

		//时间搜索权限

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			$cookie_start_time =  strtotime($start);

			$cookie_end_time =  strtotime($end);

			setcookie('start_time',$cookie_start_time);

			setcookie('end_time',$cookie_end_time);

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

				$order_check_seven_data = '1';

			}

		}



		//关于咨询员查看数据的参数 数组

		$data['order_query_seven_data']       = $order_query_seven_data;

		$parm['order_query_seven_data'] = $order_query_seven_data;

		$parm['order_check_seven_data'] = $order_check_seven_data;

		$parm['w_start'] = '';

		$parm['w_end'] = '';

		$parm['keshi_id_str'] = '4,85,28,88';

		$parm['order_type'] = $order_type;

		$parm['huanzhe_check'] = '0';

		//以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

		//而$[start_date]表示xx-xx-xx这种格式的日期

		//以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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

		$from_list = $this->model->from_order_list(-1);

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

		//获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();

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

		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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
		}

		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}

		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类

		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}

		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}

		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}

		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}

		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}

		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}

		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}

		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		if($bind == 1){

			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){

			$where .= ' AND  p.pat_weixin <> ""';

		}

		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}

		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if(!empty($order_no_yy_check))

		{

			if($order_no_yy_check == 1){

				$where .= " AND o.order_no_yy is null";

			}else if($order_no_yy_check == 2){

				$where .= " AND o.order_no_yy is not null ";

			}

		}



		if($order_time_type == 1)

		{

			$where .= ' AND o.order_time = 0';

		}

		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}

		if($order_type == 1)

		{//预约登记时间

			$where .= " AND o.order_addtime >= ".$w_start." AND o.order_addtime <= ".$w_end;

			$orderby .= ',o.order_addtime DESC ';

			//关于咨询员查看数据的参数 数组

			$parm['w_start'] = $w_start;

			$parm['w_end'] = $w_end;

		}

		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{

			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

			$parm['huanzhe_check'] = '1';

		}

		if(!empty($_COOKIE["l_rank_id"])){

			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

			$rank_type = $this->common->getRow($sql);

		}

		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

			$parm['huanzhe_check'] = '1';

		}

		if(!empty($patient_weixin))

		{

			$where = " (p.pat_weixin = '". $patient_weixin . "')";

			$data['p_w']      = $patient_weixin;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($patient_qq))

		{

			$where = " (p.pat_qq = '". $patient_qq . "')";

			$data['p_q']      = $patient_qq;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

			$parm['huanzhe_check'] = '1';

		}

		if(!empty($_COOKIE["l_admin_id"]))

		{

			$where .= " AND o.admin_id IN (" . $_COOKIE["l_admin_id"] . ")";

		}

		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ",o.order_id DESC";

		}



		//订单编号  患者名称 电话,微信,QQ  咨询员名称 搜索 是独立的 不带其他条件

		if(!empty($patient_name) || !empty($order_no) || !empty($patient_phone)  || !empty($patient_weixin)  || !empty($patient_qq) )

		{

			$where = ' 1=1 ';

			if(!empty($patient_name))

			{
				//$where  .= " and p.pat_name like  '%". $patient_name . "%'";
				$where  .= " and p.pat_name = '". $patient_name . "'";

			}

			if(!empty($patient_phone))

			{
				//$where .= " and (p.pat_phone like '%". $patient_phone . "%' OR p.pat_phone1 like '%". $patient_phone . "%')";
				$where .= " and (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			}

			if(!empty($patient_weixin))

			{
				//$where .= " and (p.pat_weixin like '%". $patient_weixin . "%')";
				$where .= " and (p.pat_weixin = '". $patient_weixin . "')";

			}

			if(!empty($patient_qq))

			{
				//$where .= " and (p.pat_qq like '%". $patient_qq . "%')";
				$where .= " and (p.pat_qq = '". $patient_qq . "')";

			}

			if(!empty($order_no))

			{

				$where .= " and o.order_no = '" . $order_no . "'";

			}

		}



		$order_count =  $this->common->getAll("SELECT count(order_id) as count FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p  on o.pat_id = p.pat_id  WHERE   o.admin_id = ".$_COOKIE["l_admin_id"]." and ".$where.$orderby);

		$data['total_rows'] = $order_count[0]['count'];

		$config = page_config();

		$config['total_rows'] = $order_count[0]['count'];

		if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=order_list_person_liulian&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}else{
			$config['base_url'] =  '?c=order&m=order_list_person_liulian&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];
		}

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

		//获取相关数据

		$parm['rank_id'] = $data['admin']['rank_id'];

		 $order_list = $this->common->getAll("SELECT o.*,p.*,od.is_come as order_is_come FROM " . $this->common->table('order_liulian') . "  as o left join " . $this->common->table('patient') . " as p on o.pat_id = p.pat_id left join " . $this->common->table('order') . " as od on o.order_no_yy is not null and o.order_no_yy != '' and od.order_no = o.order_no_yy  WHERE 1 and ".$where.$orderby." LIMIT ".$page.",".$per_page);
		 $order_id_str = 0 ;
		foreach($order_list  as $order_list_data){
			if($order_id_str == 0){
				$order_id_str = $order_list_data['order_id'];
			}else{
				$order_id_str .=  ','.$order_list_data['order_id'];
			}
		}
		//添加回访
		 $data['order_remark_list'] = $this->common->getAll("SELECT * FROM   " . $this->common->table('order_liulian_remark') . "  WHERE  order_id in(".$order_id_str.")  order by mark_time desc");
         //var_dump(json_encode($data['order_remark_list']));exit;

		//添加所属医院所有医生代码开始

		$hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

		$doctor_list=$this->model->doctor_list($hos_array);//依据字符串调用相关功能

		$data['doctor_list']=$doctor_list;//新增医生列表

		//添加所属医院所有医生代码结束

		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);

		/** 2016 10 27  新增加 表头显示下拉框的 效果**/

		$appointment_route = array();//预约途径

		$appointment_section= array();//预约科室

		$appointment_disease= array();//预约病种

		$consult_a_doctor= array();//咨询医生

		$data['appointment_route'] = $appointment_route;

		$data['appointment_section'] = $appointment_section;

		$data['appointment_disease'] = $appointment_disease;

		$data['consult_a_doctor'] = $consult_a_doctor;

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['huifang'] = $this->set_huifang();

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

		$data['order_count'] = $order_count[0]['count'];

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		if($type == 'mi')

		{

			$this->load->view('order_list_mi_person_liulian', $data);

		}

		else

		{

			$this->load->view('order_list_person_liulian', $data);

		}

	}



	//预约卡管理，定位坐标，文本替换

	public function order_card()

	{

		$data = array();

		$card_id = empty($_REQUEST['card_id'])? 0:intval($_REQUEST['card_id']);

		if(empty($card_id))

		{

			$data = $this->common->config('card_add');

		}

		else

		{

			$data = $this->common->config('card_edit');

			$info = $this->common->getRow('select * from '.$this->common->table('order_card').' where card_id = '.$card_id);

			$card_content = $info['card_content'];

			$tag = explode(',',$card_content);

			$list = array();

			foreach($tag as $val){

				$arr = array();

				if(strstr($val,'username')){

					$arr = explode('-',$val);

					$info['username'] = $arr[1].'-'.$arr[2];

				}else if(strstr($val,'age')){

					$arr = explode('-',$val);

					$info['age'] = $arr[1].'-'.$arr[2];

				}else if(strstr($val,'phone')){

					$arr = explode('-',$val);

					$info['phone'] = $arr[1].'-'.$arr[2];

				}else if(strstr($val,'jibing')){

					$arr = explode('-',$val);

					$info['jibing'] = $arr[1].'-'.$arr[2];

				}else if(strstr($val,'orderno')){

					$arr = explode('-',$val);

					$info['orderno'] = $arr[1].'-'.$arr[2];

				}else if(strstr($val,'ordertime')){

					$arr = explode('-',$val);

					$info['ordertime'] = $arr[1].'-'.$arr[2];

				}

			}

			$data['info'] = $info;

		}

		// 获取医院信息
		if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$hospital_sql = "SELECT * FROM " . $this->common->table('hospital') . " where ask_auth != 1 and hos_id in(".$_COOKIE["l_hos_id"].") ORDER BY CONVERT(hos_name USING gbk) asc";
		}else{
			$hospital_sql = "SELECT * FROM " . $this->common->table('hospital') . " where ask_auth != 1 ORDER BY CONVERT(hos_name USING gbk) asc";
		}

		$hospital = $this->common->getAll($hospital_sql);

		$data['hospital'] = $hospital;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_card', $data);

	}



	public function card_info()

	{

		$order_id = intval($_REQUEST['order_id']);

		$url = $this->common->getOne('select img from '.$this->common->table('card_data').' where order_id = '.$order_id);

		$type = trim($_REQUEST['type']);

		if($type=='reset'){

			//删除图片

			$del = explode('/',$url);

			unlink ('static/images/'.$del[5]);

			$url = null;

		}

		if(!empty($url)){

			echo '<img src="'.$url.'">';

		}else{

			// 生成预约卡操作

			$res = $this->get_info_by_id($order_id);

			$hos_id = $res['hos_id'];

			$card_info = $this->get_card($hos_id);



			if(empty($card_info)){

				echo 1;

				exit();

			}

			if(empty($res['order_time'])){

				$order_time = '未定';

			}else{

				$order_time = date('Y年m月d日',$res['order_time']);

			}

			if(empty($res['pat_age'])){

				$res['pat_age'] = '未知';

			}

			$arr = array(

				'patient_name'	=>	$res['pat_name'],

				'patient_phone'	=>	$res['pat_phone'],

				'order_time'	=>	$order_time,

				'patient_age'	=>	$res['pat_age'],

				'jibing_parent'	=>	$res['jb_name'],

				'order_no'		=>	$res['order_no'],

			);

			$list = $this->card_info_arr($arr,$card_info['card_content']);

			$url = $this->create_card($card_info['img'],$list);

			//关联预约卡

			if($url){

				if($type=='reset'){

					$this->db->update($this->common->table("card_data"),array('img' => $url),array('order_id' => $order_id));

				}else{

					$this->db->insert($this->common->table("card_data"),array('order_id' => $order_id,'img' => $url));

				}

				echo '<img src="'.$url.'">';

			}else{

				echo 2;

			}

		}

	}

	private function get_info_by_order_id($id)

	{

		if(file_exists('static/images/ok.jpg')){

			unlink ('static/images/ok.jpg');

		}

	}



	public function card_update()

	{

		$form_action     = $_REQUEST['form_action'];

		$card_id         = isset($_REQUEST['card_id'])? intval($_REQUEST['card_id']):0;

		$card_name 		 = trim($_REQUEST['card_name']);

		$hos_id          = intval($_REQUEST['hos_id']);

		$keshi_id        = intval($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0;

		$card_content = '';

		$username = trim($_REQUEST['username']);

		if(!empty($username)){

			$card_content .= '{username}-'.$username;

		}

		$age = trim($_REQUEST['age']);

		if(!empty($age)){

			$card_content .= ',{age}-'.$age;

		}

		$phone = trim($_REQUEST['phone']);

		if(!empty($phone)){

			$card_content .= ',{phone}-'.$phone;

		}

		$jibing = trim($_REQUEST['jibing']);

		if(!empty($jibing)){

			$card_content .= ',{jibing}-'.$jibing;

		}

		$ordertime = trim($_REQUEST['ordertime']);

		if(!empty($ordertime)){

			$card_content .= ',{ordertime}-'.$ordertime;

		}

		$orderno = trim($_REQUEST['orderno']);

		if(!empty($orderno)){

			$card_content .= ',{orderno}-'.$orderno;

		}

		$img    		 = trim($_REQUEST['img']);

		if(empty($card_name)){

			$this->common->msg('预约卡名称不能为空');

		}

		if(empty($hos_id)){

			$this->common->msg('请选择医院');

		}

		if(empty($card_content)){

			$this->common->msg('预设信息不能为空');

		}

		if(empty($img)){

			$this->common->msg('背景图不能为空');

		}

		$arr_card = array(

			'card_name'	=> $card_name,

			'hos_id'	=> $hos_id,

			'keshi_id'	=> $keshi_id,

			'card_content'	=> $card_content,

			'img'	=> $img,

		);



		if($form_action == 'update'){



			if(empty($card_id))

			{

				$this->common->msg($this->lang->line('no_empty'), 1);

			}

			$this->common->config('card_edit');



			$this->db->update($this->common->table("order_card"), $arr_card, array('card_id' => $card_id));



		}else{

			//添加预约卡操作

			$this->common->config('card_add');

			// 检测当前科室下是否有预约卡

			$has = $this->common->getOne('select card_id from '.$this->common->table('order_card').' where hos_id = '.$hos_id);

			if(empty($has)){

				$this->db->insert($this->common->table("order_card"), $arr_card);

				$card_id = $this->db->insert_id();

			}else{

				$card_id = $has;

				$this->db->update($this->common->table("order_card"), $arr_card, array('card_id' => $card_id));

			}

		}

		$links[0] = array('href' => '?c=order&m=order_card&card_id=' . $card_id, 'text' => $this->lang->line('edit_back'));

		$links[1] = array('href' => '?c=order&m=order_card', 'text' => $this->lang->line('add_back'));

		$links[2] = array('href' => '?c=order&m=card_list', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function card_list()

	{

		$data = array();

		$data = $this->common->config('card_list');

	    if(strcmp($_COOKIE["l_admin_action"],'all') !=0){//不是管理员
			$sql = 'select o.card_id,o.card_name,k.keshi_name,h.hos_name from '.$this->common->table('order_card').' o

				left join '.$this->common->table('hospital').' h on h.hos_id = o.hos_id

				left join '.$this->common->table('keshi').' k on k.keshi_id = o.keshi_id  where o.hos_id in('.$_COOKIE["l_hos_id"].')';
		}else{
			$sql = 'select o.card_id,o.card_name,k.keshi_name,h.hos_name from '.$this->common->table('order_card').' o

				left join '.$this->common->table('hospital').' h on h.hos_id = o.hos_id

				left join '.$this->common->table('keshi').' k on k.keshi_id = o.keshi_id ';
		}


		$list = $this->common->getAll($sql);

		$data['list'] = $list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('card_list', $data);

	}

	public function card_delete()

	{

		$this->common->config('card_delete');

		$card_id          = isset($_REQUEST['card_id'])? intval($_REQUEST['card_id']):0;

		$this->db->delete($this->common->table('order_card'), array('card_id' => $card_id));

	}

	public function card_set_ajax()

	{

		$hos_id = intval($_REQUEST['hos_id']);

		$res = $this->get_card($hos_id);

		if(empty($res)){

			echo 1;

		}else{

			$order_time = trim($_REQUEST['order_time']);

			if(empty($order_time)){

				$order_time = '未定';

			}else{

				$order_time = date('Y年m月d日',strtotime($order_time));

			}

			$order_time = date('Y年m月d日',strtotime($order_time));

			$arr = array(

				'patient_name'	=>	trim($_REQUEST['patient_name']),

				'patient_phone'	=>	trim($_REQUEST['patient_phone']),

				'order_time'	=>	$order_time,

				'patient_age'	=>	trim($_REQUEST['patient_age']),

				'jibing_parent'	=>	trim($_REQUEST['jibing_parent']),

				'order_no'		=>	trim($_REQUEST['order_no']),

			);

			$list = $this->card_info_arr($arr,$res['card_content']);

			$html = $this->create_card($res['img'],$list);

			if(!$html){

				echo 2;

			}else{

				$img = trim($_REQUEST['img']);

				if(!empty($img)){

					$src = explode('/',$img);

					$tmp = $src[5];

					if(file_exists('static/images/'.$tmp)){

						unlink('static/images/'.$tmp);

					}

				}

				echo $html;

			}

		}

	}

	private function get_card($hos_id,$keshi_id = null)

	{

		if(empty($keshi_id)){

			$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id);

		}else{

			$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id.' and keshi_id = '.$keshi_id);

			if(empty($res)){

				$res = $this->common->getRow('select img,card_content from '.$this->common->table('order_card').' where hos_id = '.$hos_id);

			}

		}

		return $res;

	}

	private function get_info_by_id($order_id)

	{

		$sql = 'select o.hos_id,o.keshi_id,o.order_time,o.order_no,j.jb_name,p.pat_name,p.pat_age,p.pat_phone

				from '.$this->common->table('order').' o

				left join '.$this->common->table('patient').' p on o.pat_id = p.pat_id

				left join '.$this->common->table('jibing').' j on o.jb_parent_id = j.jb_id

				where order_id = '.$order_id;

		$res = $this->common->getRow($sql);

		return $res;

	}



	public function get_info_by_phone()

	{

		$pat_phone = trim($_REQUEST['pat_phone']);

		if(empty($pat_phone)){

			exit;

		}

		$sql = 'select o.hos_id,o.keshi_id,o.order_time,o.order_no,j.jb_name,p.pat_name,p.pat_age,p.pat_phone

				from '.$this->common->table('order').' o

				left join '.$this->common->table('patient').' p on o.pat_id = p.pat_id

				left join '.$this->common->table('jibing').' j on o.jb_parent_id = j.jb_id

				where p.pat_phone = '.$pat_phone;

		$res = $this->common->getAll($sql);

		print_r(json_encode($res));

	}



	private function card_info_arr($arr,$string)

	{

		$replace = array('{username}', '{phone}', '{ordertime}', '{age}','{jibing}', '{orderno}');

		$value = array($arr['patient_name'], $arr['patient_phone'], $arr['order_time'], $arr['patient_age'], $arr['jibing_parent'],$arr['order_no']);

		$card_content = str_replace($replace, $value, $string);

		$tag = explode(',',$card_content);

		$list = array();

		foreach($tag as $key=>$val){

			$list[$key] = explode('-',$val);

		}

		return $list;

	}

	public function card_set()

	{

		$img_suo = trim($_REQUEST['img_suo']);

		if(!empty($img_suo)){

			$src = explode('/',$img_suo);

			$tmp = $src[5];



			if(file_exists('static/images/'.$tmp)){

				unlink('static/images/'.$tmp);

			}

		}

		$card_content = '';

		$username = trim($_REQUEST['username']);

		if(!empty($username)){

			$card_content .= '姓名位-'.$username;

		}

		$age = trim($_REQUEST['age']);

		if(!empty($age)){

			$card_content .= ',年龄位-'.$age;

		}

		$phone = trim($_REQUEST['phone']);

		if(!empty($phone)){

			$card_content .= ',手机位-'.$phone;

		}

		$jibing = trim($_REQUEST['jibing']);

		if(!empty($jibing)){

			$card_content .= ',疾病位-'.$jibing;

		}

		$ordertime = trim($_REQUEST['ordertime']);

		if(!empty($ordertime)){

			$card_content .= ',预约时间-'.$ordertime;

		}

		$orderno = trim($_REQUEST['orderno']);

		if(!empty($orderno)){

			$card_content .= ',预约号-'.$orderno;

		}

		$img = trim($_REQUEST['img']);

		$tag = explode(',',$card_content);

		$list = array();

		foreach($tag as $key=>$val){

			$list[$key] = explode('-',$val);

		}

		$html = $this->create_card($img,$list);

		echo $html;

	}



    public function zmt_order_update()

	{

		$form_action     = $_REQUEST['form_action'];

		$order_id        = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$p_id            = isset($_REQUEST['pad_id'])? intval($_REQUEST['pad_id']):0;

		$p               = isset($_REQUEST['p'])? intval($_REQUEST['p']):0;

		$remark          = trim($_REQUEST['remark']);

		$sms_themes      = isset($_REQUEST['sms_themes'])? intval($_REQUEST['sms_themes']):0;

		$sms_content     = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';

		$type            = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$order_no        = trim($_REQUEST['order_no']);

		$from_parent_id  = intval($_REQUEST['from_parent_id']);

		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']) : 0;

		$is_first        = intval($_REQUEST['is_first']);

		$from_value      = trim($_REQUEST['from_value']);

		$hos_id          = intval($_REQUEST['hos_id']);

		$keshi_id        = intval($_REQUEST['keshi_id']);

		$type_id         = intval($_REQUEST['type_id']);

		$jb_parent_id    = intval($_REQUEST['jibing_parent_id']);

		$jb_id           = intval($_REQUEST['jibing_id']);

		$admin_id        = intval($_REQUEST['admin_id']);

		$pat_name        = trim($_REQUEST['patient_name']);

		$pat_sex         = intval($_REQUEST['pat_sex']);

		$pat_phone       = trim($_REQUEST['patient_phone']);

		$pat_age         = trim($_REQUEST['patient_age']);

		$order_time      = trim($_REQUEST['order_time']);

		$order_time_duan_d = !empty($_REQUEST['order_time_duan_d'])? trim($_REQUEST['order_time_duan_d']):'';

		$order_time_duan_j = !empty($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';

		$duan_confirm      = intval($_REQUEST['duan_confirm']);

		$pat_province    = intval($_REQUEST['province']);

		$pat_city        = intval($_REQUEST['city']);

		$pat_area        = intval($_REQUEST['area']);

		$pat_address     = trim($_REQUEST['patient_address']);

		$con_content =  stripslashes(trim($_REQUEST['con_content']));

		$data_time       = trim($_REQUEST['data_time']);

		$data_time       = strtotime($data_time);

		$yunzhou         = intval($_REQUEST['yunzhou']);



		$order_time_duan = ($duan_confirm == 1)? $order_time_duan_d:$order_time_duan_j;

		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';

		$order_time_type = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;



		if(empty($pat_name))

		{

			$this->common->msg("患者姓名不能为空！", 1);

		}



		if(empty($hos_id))

		{

			$this->common->msg("医院不能为空！", 1);

		}



		if(empty($keshi_id))

		{

			$this->common->msg("科室不能为空！", 1);

		}



		if(empty($admin_id))

		{

			$this->common->msg("咨询员不能为空！", 1);

		}



		if(empty($order_no))//预约单号自增

		{

			$order_no = file_get_contents("./application/cache/static/order_no.txt");

			$zm = array(            '1' => 'A',

						'2' => 'B',

						'3' => 'C',

						'4' => 'D',

						'5' => 'E',

						'6' => 'F',

						'7' => 'G',

						'8' => 'H',

						'9' => 'I',

						'10' => 'J',

						'11' => 'K',

						'12' => 'L',

						'13' => 'M',

						'14' => 'N',

						'15' => 'O',

						'16' => 'P',

						'17' => 'Q',

						'18' => 'R',

						'19' => 'S',

						'20' => 'T',

						'21' => 'U',

						'22' => 'V',

						'23' => 'W',

						'24' => 'X',

						'25' => 'Y',

						'26' => 'Z');



			if(empty($order_no)){
				$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
			}

			if(empty($order_no))

			{

				$order_no = "AA0001";

			}

			else

			{

				$order_no_zimu = substr($order_no, 0, 2);

				$order_no_shuzi = substr($order_no, 2, 4);

				$order_no_shuzi = intval($order_no_shuzi) + 1;



				if($order_no_shuzi >= 9999)

				{

					$order_no_zimu_a = substr($order_no_zimu, 0, 1);

					$order_no_zimu_b = substr($order_no_zimu, 1, 1);

					$mz = array_flip($zm);

					if($order_no_zimu_b == 'Z')

					{

						$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];

						$order_no_zimu_b = 'A';

					}

					else

					{

						$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];

					}

					$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;

					$order_no_shuzi = '0001';

				}

				else

				{

					$zimu_len = 4 - strlen($order_no_shuzi);

					for($i = 1; $i <= $zimu_len; $i ++)

					{

						$order_no_shuzi = "0" . $order_no_shuzi;

					}

				}

				$order_no = $order_no_zimu . $order_no_shuzi;

			}

			file_put_contents("./application/cache/static/order_no.txt", $order_no);

		}

		$pat_phone = explode("/", $pat_phone);

		$patient = array('pat_name' => $pat_name,

					     'pat_sex' => $pat_sex,

					     'pat_age' => $pat_age,

					     'pat_province' => $pat_province,

					     'pat_city' => $pat_city,

						 'pat_area' => $pat_area,

						 'pat_address' => $pat_address,

						 'pat_phone' => $pat_phone[0],

						 'pat_phone1' => isset($pat_phone[1])? $pat_phone[1]:'');



		$admin_name = $this->common->getOne("SELECT admin_name FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");

		$order = array('order_no' => $order_no,

					   'is_first' => $is_first,

					   'admin_id' => $admin_id,

						'admin_name' => $admin_name,

					   'from_parent_id' => $from_parent_id,

					   'from_id' => $from_id,

					   'from_value' => $from_value,

					   'hos_id' => $hos_id,

					   'keshi_id' => $keshi_id,

					   'type_id' => $type_id,

					   'jb_parent_id' => $jb_parent_id,

					   'jb_id' => $jb_id,

					   'order_time_duan' => $order_time_duan,

					   'duan_confirm' => $duan_confirm);



		if($order_time_type == 1)

		{

			$order['order_time'] = strtotime($order_time);

			$order['order_null_time'] = '';

		}

		elseif($order_time_type == 2)

		{



                   if($form_action == 'update')

				{

					if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){



					}else{

					  $order['order_time'] = 0;

					  $order['order_null_time'] = empty($order_null_time)? '未定':$order_null_time;

				    }

				}else{

					/**

					 2016 1111   carlcao  修改 如果类型为未定，系统默认延后一个月时间

					 台州东方男科 默认7天，其他科室 默认一个月

					**/

					 $order_time =time()+30*24*60*60;

                    if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){

                         $order_time =time()+7*24*60*60;

					}

					$order['order_time'] =  $order_time;

					$order['order_null_time'] = '';



				}

		}



		if($form_action == 'update')

		{

			if(($p == 1) || ($p == 2))

			{

				$data = $this->common->config('my_profile');

			}

			else

			{

				$data = $this->common->config('my_profile');

			}



			$come_time   = isset($_REQUEST['come_time'])? trim($_REQUEST['come_time']):'';

			$doctor_name = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';

			if($come_time != "")

			{

				$order['come_time'] = strtotime($come_time);

				$order['doctor_name'] = $doctor_name;

			}



			$this->db->update($this->common->table("patient"), $patient, array('pat_id' => $p_id));

			$this->db->update($this->common->table("order"), $order, array('order_id' => $order_id));

			$con_id = $this->common->getOne("SELECT con_id FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");

			if($con_id)

			{

				$this->db->update($this->common->table("ask_content"), array('con_content' => $con_content), array('order_id' => $order_id));

			}

			else

			{

				$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));

			}

		}

		else

		{

			$this->common->config('my_profile');

			$is_order = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE order_no = '" . $order_no . "'");

			if($is_order)

			{

				$this->common->msg("请勿重复提交！", 1);

			}

			$this->db->insert($this->common->table("patient"), $patient);

			$pat_id = $this->db->insert_id();

			$order['pat_id'] = $pat_id;

			$order['order_addtime'] = time();

			$this->db->insert($this->common->table("order"), $order);

			$order_id = $this->db->insert_id();

			if($hos_id==1){

				$regis = array();

				$regis['username'] = $patient['pat_phone'];

				$regis['nickname'] = $patient['pat_name'];

				$regis['password'] = $order['order_no'];

				$this->register($regis);

			}

			if(isset($_REQUEST['card'])){

				$card = trim($_REQUEST['card']);

			}

			if(!empty($card)){

				$this->db->insert($this->common->table("card_data"),array('order_id' => $order_id,'img' => $card));

			}

			$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));

		}



		$this->db->query("DELETE FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

		if(!empty($yunzhou))

		{

			$this->db->insert($this->common->table("order_data"), array('order_id' => $order_id, 'data_time' => $data_time));

		}

		if(!empty($remark))

		{

			$remark = array('order_id' => $order_id,

							'admin_id' => $_COOKIE['l_admin_id'],

							'admin_name' => $_COOKIE['l_admin_name'],

							'mark_content' => $remark,

							'mark_time' => time(),

							'mark_type' => 1);



			$this->db->insert($this->common->table("order_remark"), $remark);

		}

		$msg_detail = $this->lang->line('success');



		if($sms_themes > 0)

		{

			$arr = array('send_content' => $sms_content,

						'send_time' => time(),

						 'send_phone' => $pat_phone[0],

						 'send_type' => 1,

						 'type_value' => $order_id,

						 'hos_id' => $hos_id,

						 'keshi_id' => $keshi_id,

						 'admin_id' => $_COOKIE['l_admin_id'],

						 'admin_name' => $_COOKIE['l_admin_name']);



			$this->db->insert($this->common->table("sms_send"), $arr);

			$msgid = $this->db->insert_id();

			$sms_content = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

			$send_phone = implode(";", $pat_phone);

                        //应急短信代码开始

                        $this->load->helper(sms);

                        $send_phone = implode(";", $pat_phone);

                        $send_status =sms_jianzhou($send_phone,$sms_content,$hospital['sms_name'],$hospital['sms_pwd']);

                         //应急短信代码结束

//			require_once('application/libraries/sms/nusoap.php');

//			$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);

//			$client->soap_defencoding = 'utf-8';

//			$client->decode_utf8      = false;

//			$client->xml_encoding     = 'utf-8';

//

//

//			$params = array(

//				'account' => $hospital['sms_name'],

//				'password' => $hospital['sms_pwd'],

//				'destmobile' => $send_phone,

//				'msgText' => $sms_content,

//			);

//

//			$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');

//			$send_status = $result['sendBatchMessageReturn'];

			$status = $this->lang->line('sms_send_status');

			if($send_status >= 0)

			{

				$msg_detail = "短信发送成功！";

				$send_status = 0;

			}

			else

			{

				$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";

			}

			$this->db->update($this->common->table('sms_send'), array('send_status' => $send_status), array('send_id' => $msgid));

			$msg_detail = mb_convert_encoding($msg_detail,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			header('Cache-control: private');

    		header('Content-type: text/html; charset=utf-8');

			echo "<script language=\"javascript\">alert('" . $msg_detail . "');</script>";

		}



		if($type == 'mi')

		{

                     $time1=time();

                    $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'更新预约','action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time1,'action_id'=>$_COOKIE['l_admin_id']));

			echo "<script language=\"javascript\">window.opener.location.reload(); window.close();</script>";

		}

		else

		{

                     $time=time();

                    $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'更新预约','action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time,'action_id'=>$_COOKIE['l_admin_id']));

			$links[0] = array('href' => '?c=order&m=order_info&order_id=' . $order_id . '&p=' . $p, 'text' => $this->lang->line('edit_back'));

			$links[1] = array('href' => '?c=order&m=order_info', 'text' => $this->lang->line('add_back'));

			$links[2] = array('href' => '?c=order&m=order_list', 'text' => $this->lang->line('list_back'));

			$this->common->msg($msg_detail, 0, $links, true, false,0);

		}

	}

	public function order_update()

	{

		$form_action     = $_REQUEST['form_action'];

		$order_id        = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$p_id            = isset($_REQUEST['pad_id'])? intval($_REQUEST['pad_id']):0;

		$p               = isset($_REQUEST['p'])? intval($_REQUEST['p']):0;

		$remark          = trim($_REQUEST['remark']);

		$sms_themes      = isset($_REQUEST['sms_themes'])? intval($_REQUEST['sms_themes']):0;

		$sms_content     = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';

		$type            = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$order_no        = trim($_REQUEST['order_no']);

		$from_parent_id  = intval($_REQUEST['from_parent_id']);

		$from_id         = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']) : 0;

		$is_first        = intval($_REQUEST['is_first']);

		$from_value      = trim($_REQUEST['from_value']);

		$hos_id          = intval($_REQUEST['hos_id']);

		$keshi_id        = intval($_REQUEST['keshi_id']);

		$type_id         = intval($_REQUEST['type_id']);

		$jb_parent_id    = intval($_REQUEST['jibing_parent_id']);

		$jb_id           = intval($_REQUEST['jibing_id']);

		$admin_id        = intval($_REQUEST['admin_id']);

		$pat_name        = trim($_REQUEST['patient_name']);

		$pat_sex         = intval($_REQUEST['pat_sex']);

		$pat_phone       = trim($_REQUEST['patient_phone']);

		$pat_age         = trim($_REQUEST['patient_age']);

		$pat_weixin       = trim($_REQUEST['patient_weixin']);
		if(empty($pat_weixin)){
			$pat_weixin = '';
		}
		$pat_qq         = trim($_REQUEST['patient_qq']);
		if(empty($pat_qq)){
			$pat_qq = '';
		}
		$order_time      = trim($_REQUEST['order_time']);

		$order_time_duan_d = !empty($_REQUEST['order_time_duan_d'])? trim($_REQUEST['order_time_duan_d']):'';

		$order_time_duan_j = !empty($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';

		if($jb_id == 300 || $jb_id == 301){

			$order_time_duan_j = !empty($_REQUEST['order_time_duan_b'])? trim($_REQUEST['order_time_duan_b']):'';

		}

		$duan_confirm    = intval($_REQUEST['duan_confirm']);

		$pat_province    = intval($_REQUEST['province']);

		$pat_city        = intval($_REQUEST['city']);

		$pat_area        = intval($_REQUEST['area']);

		$pat_address     = trim($_REQUEST['patient_address']);

		$con_content     = stripslashes(trim($_REQUEST['con_content']));

		$data_time       = trim($_REQUEST['data_time']);

		$data_time       = strtotime($data_time);

		$yunzhou         = intval($_REQUEST['yunzhou']);

		if($jb_id != 300 && $jb_id != 301){

			$order_time_duan_j=  substr($order_time_duan_j,0,5);

		}

		$order_time_duan = ($duan_confirm == 1)? $order_time_duan_d:$order_time_duan_j;

		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'未定';

		$order_time_type = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$keshiurl_id = isset($_REQUEST['keshiurl_id'])? intval($_REQUEST['keshiurl_id']):0;

		$laiyuanweb       = trim($_REQUEST['laiyuanweb']);//来源网址
		$guanjianzi       = trim($_REQUEST['guanjianzi']);//关键字
		if(empty($pat_name))

		{

			$this->common->msg("患者姓名不能为空！", 1);

		}



		if(empty($hos_id))

		{

			$this->common->msg("医院不能为空！", 1);

		}



		if(empty($keshi_id))

		{

			$this->common->msg("科室不能为空！", 1);

		}



		if(empty($admin_id))

		{

			$this->common->msg("咨询员不能为空！", 1);

		}



		if(empty($order_no))//预约单号自增

		{

			$order_no = file_get_contents("./application/cache/static/order_no.txt");

			$zm = array(            '1' => 'A',

						'2' => 'B',

						'3' => 'C',

						'4' => 'D',

						'5' => 'E',

						'6' => 'F',

						'7' => 'G',

						'8' => 'H',

						'9' => 'I',

						'10' => 'J',

						'11' => 'K',

						'12' => 'L',

						'13' => 'M',

						'14' => 'N',

						'15' => 'O',

						'16' => 'P',

						'17' => 'Q',

						'18' => 'R',

						'19' => 'S',

						'20' => 'T',

						'21' => 'U',

						'22' => 'V',

						'23' => 'W',

						'24' => 'X',

						'25' => 'Y',

						'26' => 'Z');



			if(empty($order_no)){
				$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
			}

			if(empty($order_no))

			{

				$order_no = "AA0001";

			}

			else

			{

				$order_no_zimu = substr($order_no, 0, 2);

				$order_no_shuzi = substr($order_no, 2, 4);

				$order_no_shuzi = intval($order_no_shuzi) + 1;



				if($order_no_shuzi >= 9999)

				{

					$order_no_zimu_a = substr($order_no_zimu, 0, 1);

					$order_no_zimu_b = substr($order_no_zimu, 1, 1);

					$mz = array_flip($zm);

					if($order_no_zimu_b == 'Z')

					{

						$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];

						$order_no_zimu_b = 'A';

					}

					else

					{

						$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];

					}

					$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;

					$order_no_shuzi = '0001';

				}

				else

				{

					$zimu_len = 4 - strlen($order_no_shuzi);

					for($i = 1; $i <= $zimu_len; $i ++)

					{

						$order_no_shuzi = "0" . $order_no_shuzi;

					}

				}

				$order_no = $order_no_zimu . $order_no_shuzi;

			}

			file_put_contents("./application/cache/static/order_no.txt", $order_no);

		}

		$pat_phone = explode("/", $pat_phone);

		$patient = array('pat_name' => $pat_name,

					     'pat_sex' => $pat_sex,

					     'pat_age' => $pat_age,

					     'pat_province' => $pat_province,

					     'pat_city' => $pat_city,

						 'pat_area' => $pat_area,

						 'pat_address' => $pat_address,

						 'pat_phone' => $pat_phone[0],

						 'pat_weixin' => $pat_weixin,
						 'pat_qq' => $pat_qq,

						 'pat_phone1' => isset($pat_phone[1])? $pat_phone[1]:'');

		$admin_name = $this->common->getOne("SELECT admin_name FROM " . $this->common->table('admin') . " WHERE admin_id = $admin_id");


		//查询没修改前的数据
        $this->db->set_dbprefix("hui_");
        $order_row_res = $this->db->get_where("order",array('order_id' => $order_id))->row_array();


		//检查是否设置患者时间段
		if (isset($_REQUEST['hzsjd'])) {
			$order = array('order_no' => $order_no,

		   		'is_first' => $is_first,

			   'admin_id' => $admin_id,

				'admin_name' => $admin_name,

			   'from_parent_id' => $from_parent_id,

			   'from_id' => $from_id,

			   'from_value' => $from_value,

			   'hos_id' => $hos_id,

			   'keshi_id' => $keshi_id,

			   'type_id' => $type_id,

			   'jb_parent_id' => $jb_parent_id,

			   'jb_id' => $jb_id,

			   'order_time_duan' => $order_time_duan,

				'keshiurl_id' => $keshiurl_id,

			   'duan_confirm' => $duan_confirm,

			   'hzsjd' => intval($_REQUEST['hzsjd'])
			);
		} else {
			$order = array('order_no' => $order_no,

			   	'is_first' => $is_first,

			    'admin_id' => $admin_id,

				'admin_name' => $admin_name,

			    'from_parent_id' => $from_parent_id,

			    'from_id' => $from_id,

			    'from_value' => $from_value,

			    'hos_id' => $hos_id,

			    'keshi_id' => $keshi_id,

			    'type_id' => $type_id,

			    'jb_parent_id' => $jb_parent_id,

			    'jb_id' => $jb_id,

			    'order_time_duan' => $order_time_duan,

				'keshiurl_id' => $keshiurl_id,

			    'duan_confirm' => $duan_confirm

			);
		}


		if($order_time_type == 1)

		{

			$order['order_time'] = strtotime($order_time);

			$order['order_null_time'] = '';

		}

		elseif($order_time_type == 2)

		{

                if($form_action == 'update')

				{

					if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){



					}else{

					  $order['order_time'] = 0;

					  $order['order_null_time'] = empty($order_null_time)? '未定':$order_null_time;

				    }

				}else{

					if($keshi_id != 12 && $keshi_id != 46 && $keshi_id != 15){

						/**

						 2016 1111   carlcao  修改 如果类型为未定，系统默认延后一个月时间

						 台州东方男科 默认7天，其他科室 默认一个月

						**/

						 $order_time =time()+30*24*60*60;

						if(($hos_id == 3 &&  $keshi_id == 4 ) || ($hos_id == 6 &&  $keshi_id == 28)){

							 $order_time =time()+7*24*60*60;

						}

				    } else{

					   $order_time = '0';

					}

					$order['order_time'] =  $order_time;

					$order['order_null_time'] = '';



				}

		}

		$come_time   = isset($_REQUEST['come_time'])? trim($_REQUEST['come_time']):'';

		$doctor_name = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';

		if($come_time != "")

		{

			$order['come_time'] = strtotime($come_time);

			$order['doctor_name'] = $doctor_name;

		}


		if($form_action == 'update')

		{



                     $update_content=array(

                    'pat_name' =>$pat_name,

                    'phone'=>$pat_phone[0],

                    'pat_sex'=>$pat_sex,//注意'pat_sex'不能写成'pat_sex ',多余的空格去掉

                    'pat_age'=>$pat_age,

                    'is_first'=>$is_first,

                    'hos_id'=>$hos_id,

                    'keshi_id'=>$keshi_id,

                    'jb_parent_id'=>$jb_parent_id,

                    'jb_id'=>$jb_id,

                    'type_id'=>$type_id,

                    'from_parent_id'=>$from_parent_id,

                    'from_id'=>$from_id,

                    'admin_name'=>$admin_name





                 );

                     //咨询只能看修改自己的电话
                     $keshi_check_ts = $this->config->item('keshi_check_ts');
                     $keshi_check_ts = explode(",",$keshi_check_ts);
                     $zixun_check_ts = $this->config->item('zixun_check_ts');
                     $zixun_check_ts = explode(",",$zixun_check_ts);

                     $rank_type = $this->model->rank_type();
                     if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $hos_id == 3 && in_array($keshi_id, $keshi_check_ts)){
                     	if($_COOKIE['l_admin_id'] != $admin_id){
                     		//unset($update_content['phone']);
                     	}
                     }
                     $update_info=$this->model->order_update_log($order_id,array_merge($order,$update_content));

                     $time1=time();

                     if(empty($update_info)){

                     $update_info="暂无重要信息修改记录！";

                     $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>$update_info,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time1,'action_id'=>$_COOKIE['l_admin_id']));

                     }else{

                         $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>$update_info,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time1,'action_id'=>$_COOKIE['l_admin_id']));

                     }

			if(($p == 1) || ($p == 2))

			{

				$data = $this->common->config('order_add');

			}

			else

			{

				$data = $this->common->config('order_edit');

			}

			$defult_patient_phone       = trim($_REQUEST['defult_patient_phone']);

			if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
				 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
				 //  更改 咨询员名称
			     if(!in_array(148,$l_admin_action)){
			     	 unset($order['admin_id']);
			     	 unset($order['admin_name']);
		         }

		         if(empty($defult_patient_phone)){
					/** 如果一开始咨询员未录入电话号码  则默认添加电话号码
					已录入电话号码的 就需要权限修改了。
					*/
					 if(!in_array(149,$l_admin_action)){
					 	unset($patient['pat_name']);
					 	unset($patient['pat_phone1']);
			         }
		         }else{
		         	if(!in_array(149,$l_admin_action)){
		         		unset($patient['pat_name']);
		         		unset($patient['pat_phone']);
		         		unset($patient['pat_phone1']);
		         		unset($patient['pat_qq']);
		         		unset($patient['pat_weixin']);
			         }
		         }

			}

			//咨询只能看修改自己的电话
			$keshi_check_ts = $this->config->item('keshi_check_ts');
			$keshi_check_ts = explode(",",$keshi_check_ts);
			$zixun_check_ts = $this->config->item('zixun_check_ts');
			$zixun_check_ts = explode(",",$zixun_check_ts);

			$rank_type = $this->model->rank_type();
			if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $hos_id == 3 && in_array($keshi_id, $keshi_check_ts)){
				if($_COOKIE['l_admin_id'] != $admin_id){
					//unset($patient['pat_phone']);
					//unset($patient['pat_phone1']);
					//unset($patient['pat_qq']);
					//unset($patient['pat_weixin']);
				}
			}
			$this->db->update($this->common->table("patient"), $patient, array('pat_id' => $p_id));

			//先查询订单数据 后面 宝宝 分缸有用

			$order_time_duan_data  = $this->common->getAll("SELECT o.order_time_duan,o.order_time,o.jb_id,ot.baby_select_type_id FROM " . $this->common->table('order') ." as o left join " . $this->common->table('baby_stype_order') ." as ot on o.order_no = ot.order_no  WHERE o.order_id = ".$order_id);

			/**
			 *
			 * 2017 0629 添加功能
			 * 记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
			 * **/
			/**
			if($order['keshi_id'] == 1 || $order['keshi_id'] == 32){
				if(empty($order['order_time'])){
					$this->db->delete($this->common->table('order_reanai_sum'), array('order_id' =>$order_id));
				}else{
					$order_time_data  = $this->common->getAll("SELECT order_time,admin_id FROM " . $this->common->table('order') ."  WHERE order_id = ".$order_id);
					//判断咨询员是否变更，如果变更 计数归0.未变更的 则计数累积。
					if($admin_id == $order_time_data[0]['admin_id']){
						//时间相同 不用改
						if(strcmp($order_time_data[0]['order_time'],$order['order_time']) != 0 ){
							$order_reanai_sum_data  = $this->common->getAll("SELECT sum FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
							if(count($order_reanai_sum_data) > 0){
								if($order_time_data[0]['admin_id'] == $_COOKIE['l_admin_id'] ){//咨询员自己改时间
									if($order_reanai_sum_data[0]['sum'] == 3){
										unset($order['order_time']);//超过3次的不能继续修改预到时间
									}else{
										$hui_order_reanai_sum_data = array();
										$hui_order_reanai_sum_data['sum'] = intval($order_reanai_sum_data[0]['sum'])+1;
										$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
									}
								}else{//非咨询员改时间   最大流入公海时间节点修改为 现在的预到时间
									if(in_array($_COOKIE['l_rank_id'],array("21","5","1"))){
										$hui_order_reanai_sum_data = array();
										// 以当天预到时间的23:59:59为最后流入公海判断时间  等于或者超过改时间，而未就诊的 自动掉入公海
										$hui_order_reanai_sum_data['order_time']  =strtotime(date("Y-m-d",$order['order_time'])." 23:59:59");
										$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));
									}else{
										unset($order['order_time']);//非管理员  项目运营 咨询主管  自己 之外 的 人  默认不能修改预到时间
									}
								}
							}else{
								//config 设置的 日期天
								$dy = $this->config->item('renfi_fk_by_day_time');
								if(!is_numeric($dy)){
									$dy = 7;
								}else if(floor($dy) == 0){
									$dy = 7;
								}else if(floor($dy) != $dy){
									$dy = 7;
								}
								//第一次预到时间 在加 $dy 天
								$hui_order_reanai_sum = array();
								$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
								$hui_order_reanai_sum['order_id'] =$order_id;
								$hui_order_reanai_sum['sum'] = 0;
								$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
							}
						}
					}else{
						$order_reanai_sum_data  = $this->common->getAll("SELECT order_id FROM " . $this->common->table('order_reanai_sum') ."  WHERE order_id = ".$order_id);
						if(count($order_reanai_sum_data) > 0){
							//咨询员变了
							//config 设置的 日期天
							$dy = $this->config->item('renfi_fk_by_day_time');
							if(!is_numeric($dy)){
								$dy = 7;
							}else if(floor($dy) == 0){
								$dy = 7;
							}else if(floor($dy) != $dy){
								$dy = 7;
							}
							$hui_order_reanai_sum_data = array();
							$hui_order_reanai_sum_data['sum'] = 0;
							// 以当天预到时间的23:59:59为最后流入公海判断时间  等于或者超过改时间，而未就诊的 自动掉入公海
							$hui_order_reanai_sum_data['order_time']  = strtotime(date("Y-m-d", time()+($dy*24*60*60))." 23:59:59");
							$order['order_time']  = strtotime(date("Y-m-d", time())." 00:00:00"); //更改咨询 导致 预到时间更新为当天
							$this->db->update($this->common->table("order_reanai_sum"), $hui_order_reanai_sum_data,array('order_id' => $order_id));

						}else{
							//config 设置的 日期天
							$dy = $this->config->item('renfi_fk_by_day_time');
							if(!is_numeric($dy)){
								$dy = 7;
							}else if(floor($dy) == 0){
								$dy = 7;
							}else if(floor($dy) != $dy){
								$dy = 7;
							}
							//第一次预到时间 在加 $dy 天
							$hui_order_reanai_sum = array();
							$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
							$hui_order_reanai_sum['order_id'] =$order_id;
							$hui_order_reanai_sum['sum'] = 0;
							$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
						}
					}
				}
			}
			**/

			$this->db->update($this->common->table("order"), $order, array('order_id' => $order_id));

			//更新来源网址
			$order_laiyuanweb = array();
			$order_laiyuanweb['order_id'] = $order_id;
			$order_laiyuanweb['form'] = $laiyuanweb;
			$order_laiyuanweb['guanjianzi'] = $guanjianzi;

			//先判断表中是否有order_id,再做插入或更新操作
			$is_ol_exist = $this->db->get_where($this->common->table("order_laiyuanweb"),array('order_id' => $order_id))->result_array();

			if ($is_ol_exist) {
				$this->db->update($this->common->table("order_laiyuanweb"), $order_laiyuanweb, array('order_id' => $order_id));
			} else {
				$this->db->insert($this->common->table("order_laiyuanweb"), $order_laiyuanweb);
			}

			//记录宝宝缸数

			if($order['jb_id'] == 300 || $order['jb_id'] == 301 ){

				if(!empty($order_time_duan_data[0]['baby_select_type_id'])){

					 $sql = 'select id,sum from '.$this->common->table('baby_select_type')." where id =  ".$order_time_duan_data[0]['baby_select_type_id'];

					$baby_select_type_data = $this->common->getAll($sql);

					if(count($baby_select_type_data) > 0){

						$baby_select_type_array = array();

						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])-1;

						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));

					}

				}

				//删除旧的预约单关联记录

				$this->db->delete($this->common->table('baby_stype_order'), array('order_no' =>$order['order_no']));



				$order_time_duan_array = explode('~',$order['order_time_duan'] );

				$sql = 'select id from '.$this->common->table('baby_type')." where jb_id  = ".$order['jb_id']." and time_start = '".$order_time_duan_array[0]."' and time_end = '".$order_time_duan_array[1]."'";

				$baby_type_data = $this->common->getone($sql);

				if(!empty($baby_type_data)){

					$sql = 'select id,sum from '.$this->common->table('baby_select_type')." where baby_type_id =  ".$baby_type_data." and days = '".date('Y-m-d',$order['order_time'])."'";

					$baby_select_type_data = $this->common->getAll($sql);

					if(count($baby_select_type_data) > 0){

						$baby_select_type_array = array();

						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])+1;

						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));

						//增加预约单和宝宝缸表 关联表

						$baby_type_order_array = array();

						$baby_type_order_array['baby_select_type_id'] = $baby_select_type_data[0]['id'];

						$baby_type_order_array['order_no'] =$order['order_no'];

						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);

					}else{

						$baby_select_type_array = array();

						$baby_select_type_array['baby_type_id'] = $baby_type_data;

						$baby_select_type_array['days'] =date('Y-m-d',$order['order_time']);

						$baby_select_type_array['sum'] =1;

						$baby_select_type_array['add_time'] =date('Y-m-d H:i:s',time());

						$this->db->insert($this->common->table("baby_select_type"), $baby_select_type_array);



						//增加预约单和宝宝缸表 关联表

						$baby_type_order_array = array();

						$baby_type_order_array['baby_select_type_id'] = $this->db->insert_id();

						$baby_type_order_array['order_no'] =$order['order_no'];

						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);

					}

				}

			}else{

				if(!empty($order_time_duan_data[0]['baby_select_type_id'])){

					 $sql = 'select id,sum from '.$this->common->table('baby_select_type')." where id =  ".$order_time_duan_data[0]['baby_select_type_id'];

					$baby_select_type_data = $this->common->getAll($sql);

					if(count($baby_select_type_data) > 0){

						$baby_select_type_array = array();

						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])-1;

						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));

					}

				}

				if(in_array($order_time_duan_data[0]['jb_id'],array(300,301))){

					//删除旧的预约单关联记录

					$this->db->delete($this->common->table('baby_stype_order'), array('order_no' =>$order['order_no']));

				}

			}



			$con_id = $this->common->getOne("SELECT con_id FROM " . $this->common->table("ask_content") . " WHERE order_id = $order_id");



			if($con_id)

			{

				$this->db->update($this->common->table("ask_content"), array('con_content' => $con_content), array('order_id' => $order_id));

			}

			else

			{

				$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));

			}







		}

		else

		{

			$this->common->config('order_add');

			$is_order = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE order_no = '" . $order_no . "'");

			if($is_order)

			{

				$this->common->msg("请勿重复提交！", 1);

			}

			$this->db->insert($this->common->table("patient"), $patient);



			$pat_id = $this->db->insert_id();

			$order['pat_id'] = $pat_id;

			$order['order_addtime'] = time();

			$this->db->insert($this->common->table("order"), $order);

			$order_id = $this->db->insert_id();


			//添加来源网址
			$order_laiyuanweb = array();
			$order_laiyuanweb['form'] = $laiyuanweb;
			$order_laiyuanweb['guanjianzi'] = $guanjianzi;
			$order_laiyuanweb['order_id'] = $order_id;
			$this->db->insert($this->common->table("order_laiyuanweb"), $order_laiyuanweb);

			/**
			 *
			 * 2017 0629 添加功能
			 * 记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
			 * **/
			if(($order['keshi_id'] == 1 || $order['keshi_id'] == 32) && !empty($order['order_time']) ){
				$hui_order_reanai_sum = array();
				//config 设置的 日期天
				$dy = $this->config->item('renfi_fk_by_day_time');
				if(!is_numeric($dy)){
					$dy = 7;
				}else if(floor($dy) == 0){
					$dy = 7;
				}else if(floor($dy) != $dy){
					$dy = 7;
				}
				//第一次预到时间 在加 $dy 天
				$hui_order_reanai_sum['order_time']  = strtotime(date("Y-m-d", $order['order_time']+($dy*24*60*60))." 23:59:59");
				$hui_order_reanai_sum['order_id'] =$order_id;
				$hui_order_reanai_sum['sum'] = 0;
				$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
			}



			//记录宝宝缸数

			if($order['jb_id'] == 300 || $order['jb_id'] == 301 ){

				$order_time_duan_array = explode('~',$order['order_time_duan'] );

				$sql = 'select id from '.$this->common->table('baby_type')." where jb_id  = ".$order['jb_id']." and time_start = '".$order_time_duan_array[0]."' and time_end = '".$order_time_duan_array[1]."'";

				$baby_type_data = $this->common->getone($sql);

				if(!empty($baby_type_data)){

					$sql = 'select id,sum from '.$this->common->table('baby_select_type')." where baby_type_id =  ".$baby_type_data." and days = '".date('Y-m-d',$order['order_time'])."'";

					$baby_select_type_data = $this->common->getAll($sql);

					if(count($baby_select_type_data) > 0){

						$baby_select_type_array = array();

						$baby_select_type_array['sum'] =intval($baby_select_type_data[0]['sum'])+1;

						$this->db->update($this->common->table("baby_select_type"), $baby_select_type_array,array('id' => $baby_select_type_data[0]['id']));

						//增加预约单和宝宝缸表 关联表

						$baby_type_order_array = array();

						$baby_type_order_array['baby_select_type_id'] = $baby_select_type_data[0]['id'];

						$baby_type_order_array['order_no'] =$order['order_no'];

						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);

					}else{

						$baby_select_type_array = array();

						$baby_select_type_array['baby_type_id'] = $baby_type_data;

						$baby_select_type_array['days'] =date('Y-m-d',$order['order_time']);

						$baby_select_type_array['sum'] =1;

						$baby_select_type_array['add_time'] =date('Y-m-d H:i:s',time());

						$this->db->insert($this->common->table("baby_select_type"), $baby_select_type_array);

						//增加预约单和宝宝缸表 关联表

						$baby_type_order_array = array();

						$baby_type_order_array['baby_select_type_id'] = $this->db->insert_id();

						$baby_type_order_array['order_no'] =$order['order_no'];

						$this->db->insert($this->common->table("baby_stype_order"), $baby_type_order_array);

					}

			    }

			}



			//如果是留联转换过来的 则将新单号更新至留联记录单号里面

			$order_liulian_id = empty($_REQUEST['order_liulian_id'])? 0:intval($_REQUEST['order_liulian_id']);

			if(!empty($order_liulian_id)){

				$order_liulian_update = array();

				$order_liulian_update['order_no_yy'] = $order_no ;
				$order_liulian_update['order_no_yy_time'] = $order['order_addtime'] ;
				$this->db->update($this->common->table("order_liulian"), $order_liulian_update, array('order_id' => $order_liulian_id));

			}



//			if($hos_id==1){

//				$regis = array();

//				$regis['username'] = $patient['pat_phone'];

//				$regis['nickname'] = $patient['pat_name'];

//				$regis['password'] = $order['order_no'];

//				$this->register($regis);

//			}



			if(isset($_REQUEST['card'])){

				$card = trim($_REQUEST['card']);

			}

			if(!empty($card)){

				$this->db->insert($this->common->table("card_data"),array('order_id' => $order_id,'img' => $card));

			}



			$this->db->insert($this->common->table("ask_content"), array('order_id' => $order_id, 'con_content' => $con_content, 'con_addtime' => time()));



                         $id_name=array();

                     $id_name=$this->model->order_log($hos_id,$keshi_id,$jb_parent_id,$jb_id,$from_parent_id,$from_id,$type_id);

                         if($pat_sex==1){

                        $pat_sex="男";

                    }elseif($pat_sex==2){

                        $pat_sex="女";

                    }



                    $add_info="新增数据||姓名：".$pat_name."；电话：".$pat_phone[0]."；性别：".$pat_sex."；年龄：".$pat_age."；初/复诊：".$is_first."；医院：".$id_name['hos_name']."；科室：".$id_name['keshi_name']."；病种：".$id_name['jb_parent_name']."-".$id_name['jb_name']."；性质：".$id_name['type_name']."；途径：".$id_name['from_parent_name']."-".$id_name['from_name']."；咨询员：".$admin_name."；预到时间：".$order_time."；";

                    $time2=time();

                     $this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>$add_info,'action_name'=>$_COOKIE['l_admin_name'],'action_time'=>$time2,'action_id'=>$_COOKIE['l_admin_id']));

		}



		$this->db->query("DELETE FROM " . $this->common->table("order_data") . " WHERE order_id = $order_id");

		if(!empty($yunzhou))

		{

			$this->db->insert($this->common->table("order_data"), array('order_id' => $order_id, 'data_time' => $data_time));

		}

		if(!empty($remark))

		{

			$remark = array('order_id' => $order_id,

							'admin_id' => $_COOKIE['l_admin_id'],

							'admin_name' => $_COOKIE['l_admin_name'],

							'mark_content' => $remark,

							'mark_time' => time(),

							'mark_type' => 1);



			$this->db->insert($this->common->table("order_remark"), $remark);

		}


		if (in_array($hos_id, $this->config->item("allowed_drop_hos_id"))) {
			if ($form_action == "add") {
			    //记录预到时间用于判断咨询修改预约次数
			    $ordertime_records = array(
			        'order_id' => $order_id,
			        'admin_id' => $_COOKIE['l_admin_id'],
			        'order_time' => $order['order_time'],
			        'action_time' => time(),
			        'action_type' => 1,
			    );
			    $this->db->set_dbprefix("henry_");
			    $this->db->insert("ordertime_update_record", $ordertime_records);
			    //p($this->db->last_query());die();
			} elseif ($form_action == "update") {
			    if ($order_row_res['order_time'] != $order['order_time'] ) {
			        $this->db->set_dbprefix("henry_");
			        //记录预到时间用于判断咨询修改预约次数
			        $ordertime_records = array(
			            'order_id' => $order_id,
			            'admin_id' => $_COOKIE['l_admin_id'],
			            'order_time' => $order['order_time'],
			            'action_time' => time(),
			            'action_type' => 2,
			        );
			        $this->db->insert("ordertime_update_record", $ordertime_records);
			        //p($this->db->last_query());die();
			    }
			}
		}

        $this->db->set_dbprefix("hui_");




		$msg_detail = $this->lang->line('success');

	    /**
	     * 推送数据到数据中心
	     * */
		//$this->send_order_shujuzhognxin($order_id);

		if($sms_themes > 0)

		{

			$arr = array('send_content' => $sms_content,

						'send_time' => time(),

						 'send_phone' => $pat_phone[0],

						 'send_type' => 1,

						 'type_value' => $order_id,

						 'hos_id' => $hos_id,

						 'keshi_id' => $keshi_id,

						 'admin_id' => $_COOKIE['l_admin_id'],

						 'admin_name' => $_COOKIE['l_admin_name']);


			$this->db->insert($this->common->table("sms_send"), $arr);

			$msgid = $this->db->insert_id();

			$sms_content = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

			$send_phone = implode(";", $pat_phone);

                        //应急短信代码开始

                        $this->load->helper(sms);

                        $send_phone = implode(";", $pat_phone);

                        $send_status =sms_jianzhou($send_phone,$sms_content,$hospital['sms_name'],$hospital['sms_pwd']);



			$status = $this->lang->line('sms_send_status');

			if($send_status >= 0)

			{

				$msg_detail = "短信发送成功！";

				$send_status = 0;

			}

			else

			{

				$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";

			}

			$this->db->update($this->common->table('sms_send'), array('send_status' => $send_status), array('send_id' => $msgid));

			$msg_detail = mb_convert_encoding($msg_detail,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

			header('Cache-control: private');

    		header('Content-type: text/html; charset=utf-8');

			echo "<script language=\"javascript\">alert('" . $msg_detail . "');</script>";

		}

		if($type == 'mi')

		{



			echo "<script language=\"javascript\">window.opener.location.reload(); window.close();</script>";

                }else{



			$links[0] = array('href' => '?c=order&m=order_info&order_id=' . $order_id . '&p=' . $p, 'text' => $this->lang->line('edit_back'));

			$links[1] = array('href' => '?c=order&m=order_info', 'text' => $this->lang->line('add_back'));

			$links[2] = array('href' => '?c=order&m=order_list', 'text' => $this->lang->line('list_back'));

			$this->common->msg($msg_detail, 0, $links, true, false,0);

		}

	}

	public function dean_list()

	{

		$data = array();

		$data           = $this->common->config('dean_list');

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';

		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):20;

		$per_page = empty($per_page)? 20:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');

		$where = 1;

		if(!empty($patient_name))

		{
			//$where = " name like '%". $patient_name . "%'";
			$where = " name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$query[]='p_n='.$patient_name;

		}

		if(!empty($patient_phone))

		{

			$where = " phone = '". $patient_phone ."'" ;

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$query[]='p_p='.$patient_phone;

		}

		$sql = 'select count(1) as count from '.$this->common->table('ask_dean').' where '.$where;

		$message_count = $this->common->getone($sql);

		$config = page_config();

		$config['base_url'] = '?c=order&m=dean_list'.implode('&',$query);

		$config['total_rows'] = $message_count;

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

		$sql = 'select * from '.$this->common->table('ask_dean').' where '.$where.' limit '.$page.','.$per_page ;

		$message_list = $this->common->getAll($sql);

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['message_list'] = $message_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('dean_list', $data);

	}



	public function message_list()

	{

		$data = array();

		$data           = $this->common->config('message_list');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		 $keshi_id         = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$jb_parent_id         = isset($_REQUEST['jb_id'])? intval($_REQUEST['jb_id']):0;

		$handle         = isset($_REQUEST['handle'])? intval($_REQUEST['handle']):0;

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):1;

		$data['t']  = $order_type;

		$data['hos_id']   = $hos_id;

		$data['keshi_id']   = $keshi_id;

		$data['jb_id']   = $jb_parent_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;



		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}



		$hospital = $this->model->hospital_order_list();

		$data['hospital'] = $hospital;

	    $data['keshi'] = $this->model->keshi_order_list($hos_id);
		$data['jibing'] = $this->model->jb_order_list($keshi_id);


		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');

		/* 处理判断条件 */



		$where = 1;

		$orderby = '';

		$query = array();

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

			$query[]='hos_id='.$hos_id;

		}

		/**
		//台州男科不能看妇科  台州妇科只能看妇科
		if($_COOKIE["l_rank_id"] == 17){
			if(empty($keshi_id)){
				 $where .= ' AND o.keshi_id in(0,4,92,95) ';
			}else{
				if($keshi_id == 4 || $keshi_id == 92 || $keshi_id == 95){
					$where .= ' AND o.keshi_id in(0,'.$keshi_id.') ';
				}else{
					 $where .= ' AND o.keshi_id in(0,4,92,95) ';
				}
			}
		}else if($_COOKIE["l_rank_id"] == 196 || $_COOKIE["l_rank_id"] == 192){//属于妇科
		    if(empty($keshi_id)){
				 $where .= ' AND o.keshi_id in(0,26,94,95) ';
			}else{
				if($keshi_id == 26  || $keshi_id == 94 || $keshi_id == 95){
					$where .= ' AND o.keshi_id in(0,'.$keshi_id.') ';
				}else{
					 $where .= ' AND o.keshi_id in(0,26,94,95) ';
				}
			}
		}else{
			if(empty($keshi_id)){
				if(!empty($_COOKIE["l_keshi_id"]))
				{
					$where .= ' AND o.keshi_id IN (0,' . $_COOKIE["l_keshi_id"] . ')';
				}
			}else{
				$where .= ' AND o.keshi_id = ' . $keshi_id;
				$query[]='hos_id='.$keshi_id;
			}
		}
		**/

		if(empty($keshi_id)){
			if(!empty($_COOKIE["l_keshi_id"]))
			{
				$where .= ' AND o.keshi_id IN (0,' . $_COOKIE["l_keshi_id"] . ')';
			}
		}else{
			$where .= ' AND o.keshi_id = ' . $keshi_id;
			$query[]='hos_id='.$keshi_id;
		}

		if(!empty($jb_parent_id))

		{

			$where .= ' AND o.jb_parent_id = ' . $jb_parent_id;

			$query[]='jb_parent_id='.$jb_parent_id;

		}


		if($handle)

		{

			if($handle == 1){

				$where .= ' AND o.admin_id = 0';

			}else{

				$where .= ' AND o.admin_id > 0';

			}

			$data['handle'] = $handle;

			$query[]='handle='.$handle;

		}



		/* 订单状态 */

		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

			$query[]='province='.$pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

			$query[]='city='.$city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

			$query[]='area='.$are;

		}



		$w_start = strtotime($start . ' 00:00:00');

		$w_end = strtotime($end . ' 23:59:59');

		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_id DESC ';

		}

		/* 时间条件 */



		if($order_type == 1)

		{

			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

			$orderby .= ',o.order_addtime DESC ';

			$query[]='t=1';

		}

		elseif($order_type == 2)

		{

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

			$query[]='t=2';

		}

		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{
			//$where = " p.pat_name like '%". $patient_name . "%'";
			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$query[]='p_n='.$patient_name;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$query[]='p_p='.$patient_phone;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}



		$message_count = $this->model->message_count($where);

		$config = page_config();

		$config['base_url'] = '?c=order&m=message_list&'.implode('&',$query);

		$config['total_rows'] = $message_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

		$message_list = $this->model->message_list($where, $page, $per_page, $orderby);

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");



		$flag_1 = array();

		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;



			}else{

				$flag_2[] = $val;

			}

		}

		$area = $this->model->area();

		$province_list = array_merge($flag_1,$flag_2);

		$data['area'] = $area;

		$rank_type = $this->model->rank_type();

		$data['rank_type'] = $rank_type;


		$data['jibing'] = $this->common->getAll("SELECT * FROM " . $this->common->table('jibing'));


		$data['province'] = $province_list;

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['message_list'] = $message_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);



		/**2016 10 27   不具备修改 咨询员权限的人 不能更改咨询员信息**/
		/**2016 11 18   下载留言 **/
		if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(148,$l_admin_action)){
		     	$data['order_edit_consultants'] = '1';
	         }
	         if(in_array(150,$l_admin_action)){
		     	$data['down_message'] = '1';
	         }
		}else{
			$data['order_edit_consultants'] = '1';
			$data['down_message'] = '1';
		}


		/**

		     检查是否是需要导出excel

		***/

		$excel_status         = isset($_REQUEST['excel_status'])? intval($_REQUEST['excel_status']):0;



		if($excel_status == 1){

			   // 清空输出缓冲区

				//ob_clean();

				// 载入PHPExcel类库

				$this->load->library('PHPExcel');

				$this->load->library('PHPExcel/IOFactory');



				// 创建PHPExcel对象

				$objPHPExcel = new PHPExcel();

				// 设置excel文件属性描述

				$objPHPExcel->getProperties()

							->setTitle(time())

							->setDescription(time());

				// 设置当前工作表

				$objPHPExcel->setActiveSheetIndex(0);

				// 设置表头

				$fields = array('序号','患者名称','性别','年龄','电话','地区','QQ','登记时间','预到时间','提交网址','预约病种','患者留言','咨询备注','记录');

				// 列编号从0开始，行编号从1开始

				$col = 0;

				$row = 1;

				foreach($fields as $field)

				{

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);

					//$objPHPExcel->getActiveSheet() ->getStyle($col)->getAlignment()->setShrinkToFit(true);//字体变小以适应宽

					//$objPHPExcel->getActiveSheet() ->getStyle($col)->getAlignment()->setWrapText(true);//自动换行

					$col++;

				}





				// 从第二行开始输出数据内容

				$row = 2;

				$row_index = 1;



				$sql = "SELECT o.order_id, o.pat_id, p.pat_name, p.pat_sex, o.from_value,a.admin_name,p.pat_age,

				p.pat_address, p.pat_phone, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, o.admin_id,

				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.jb_parent_id,o.order_null_time

				FROM " . $this->common->table('order_mes') . " o

				LEFT JOIN " . $this->common->table('pat_data') . " p ON p.pat_id = o.pat_id

				LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = o.admin_id

				WHERE  " .$where.$orderby;

		        $message_list = $this->common->getAll($sql);

		         foreach($message_list as $key=>$item)

				{



					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$row_index);

					$row_index++;



					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $item['pat_name']);

					 if($item['pat_sex'] == 0){

						  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row,'女');

					}else{

						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, '男');

					}



					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row,$item['pat_age']);



					 if($rank_type == 2 || $_COOKIE['l_admin_action'] == 'all'  || $rank_type == 4){

                          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row,$item['pat_phone']);

						   if(!empty($item['pat_phone1'])){

							       $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row,$item['pat_phone1']);

						   }



					 }else{

						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row,substr($item['pat_phone'],0, -4)."****");

						 if(!empty($item['pat_phone1'])){

							   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row,substr($item['pat_phone1'],0, -4)."****");

						 }

					 }



		            $persongInfomsg  ='';

				    if($item['pat_province'] > 0){

						 $persongInfomsg .=$area[$item['pat_province']]['region_name'].PHP_EOL;

					}

					if($item['pat_city'] > 0){

						  $persongInfomsg .="、" . $area[$item['pat_city']]['region_name'].PHP_EOL;

				    }

					if($item['pat_area'] > 0){

					    $persongInfomsg .="、" . $area[$item['pat_area']]['region_name'].PHP_EOL;

					}

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row,$persongInfomsg);

					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row,$item['pat_qq']);

					 if(empty($item['order_addtime'])){

					    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,'');

					 }else{

					    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,date("Y-m-d H:i:s",$item['order_addtime']));

					 }



					  if(empty($item['order_time'])){

						  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row,'');

					  }  else{

						  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row,date("Y-m-d H:i:s",$item['order_time']));

					  }





					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$item['from_value']);

					$persongInfomsg  ='';



					if(empty($jibing[$item['jb_parent_id']])){

						 $persongInfomsg ='没有选择病种';

				    }else{

						 $persongInfomsg  =$jibing[$item['jb_parent_id']]['jb_name'];

					}

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $persongInfomsg );



					$sql = "SELECT * FROM " . $this->common->table('mes_content') . " WHERE order_id IN (".$item['order_id'].") ORDER BY mark_id DESC,mark_time DESC";

					$mes_content_row = $this->common->getAll($sql);

					$remark_str = "";

					$visit_str = "";

					foreach($mes_content_row as $mes_content_row_val)



					{

						$mes_content_row_val['mark_time'] = date("m-d H:i", $mes_content_row_val['mark_time']);

						$remark_str .= $mes_content_row_val['mark_content'].PHP_EOL;

						$visit_str .= $mes_content_row_val['mark_content'].PHP_EOL;

					}



					foreach($mes_content_row as $mes_content_row_val)



					{

						$mes_content_row_val['mark_time'] = date("m-d H:i", $mes_content_row_val['mark_time']);

						if($mes_content_row_val['mark_type'] == 0){

							$remark_str .= "病人留言".$mes_content_row_val['mark_time'].PHP_EOL;break;

						}else{

							$visit_str .= "咨询备注". $mes_content_row_val['mark_time'].PHP_EOL;break;

						}

					}





					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row,$remark_str);



					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row,$visit_str);

					$persongInfomsg  ='';

					if(empty($item['admin_name'])){

					}else{

					   $persongInfomsg  .=$item['admin_name'].'已处理'.PHP_EOL;

					}

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row,$persongInfomsg);

					$row++;

				}



				//输出excel文件

				$objPHPExcel->setActiveSheetIndex(0);

				// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)

				$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

				// 设置HTTP头

				header('Content-Type: application/vnd.ms-excel; charset=utf-8');

				header('Content-Disposition: attachment;filename="'.mb_convert_encoding('留言列表'.time(), "GB2312", "UTF-8").'.xls"');

				header('Cache-Control: max-age=0');

				$objWriter->save('php://output');



	    }else{

			$this->load->view('message_list', $data);

	    }

	}


		   	/**
	日期合法校验
	**/
	public function dateCheck($data){
			$data = str_replace(array("年", "月"), "-", $data);
			$data = str_replace(array("日"), "", $data);
			$is_date=strtotime($data)?strtotime($data):false;
			if($is_date===false){
				return false;
			}else{
				return true;
			}
	}

    //预约列表

	public function order_list()

	{
		header("Content-type: text/html; charset=utf-8");


		$data = array();

		$data           = $this->common->config('order_list');



		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';//病人名称

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';//病人手机电话

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';//预约编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind           = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;



        $excel_status           = isset($_REQUEST['excel_status'])? trim($_REQUEST['excel_status']):'';//导出excel的标志  有值则导出  无值则查询



		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;

		$jz_type       = isset($_REQUEST['jz_type'])? intval($_REQUEST['jz_type']):0;

		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

		$data['jz_type']   = $jz_type;

//判断日期是否为空





        /** 2016 11 28   判断是否具有 咨询员的7天查询时间限制,以当前时间为准

		    改功能 只针对 东方,台州 所有男科数据



		**/

		$parm = array();//关于咨询员查看数据的参数 数组



		$order_query_seven_data = '0';

		$order_check_seven_data = '0';//判斷是否是今天

		if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(154,$l_admin_action)){
		     	$order_query_seven_data = '1';
	         }
		}else{
			$order_query_seven_data = '1';
		}
		//时间搜索权限

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);



			$data['start_date'] = $start;

			$data['end_date'] = $end;

			$cookie_start_time =  strtotime($start);

			$cookie_end_time =  strtotime($end);

			setcookie('start_time',$cookie_start_time);

			setcookie('end_time',$cookie_end_time);



		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

				$order_check_seven_data = '1';

			}

		}

	   //关于咨询员查看数据的参数 数组

	  $data['order_query_seven_data']       = $order_query_seven_data;

	   $parm['order_query_seven_data'] = $order_query_seven_data;

	   $parm['order_check_seven_data'] = $order_check_seven_data;

	   $parm['w_start'] = '';

	   $parm['w_end'] = '';

	   $parm['keshi_id_str'] = '4,85,28,88';

	   $parm['order_type'] = $order_type;

	   $parm['huanzhe_check'] = '0';

       //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

       //而$[start_date]表示xx-xx-xx这种格式的日期







        //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);

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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			//根据科室查询疾病
			// if(!empty($data['keshi_id'])){
			// 	$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
			// 	foreach($jibing as $val)
			// 	{   $jb_exit = 0;
			// 		foreach($keshi_jb as $keshi_jb_val)
			// 		{
			// 			if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
			// 				 $jb_exit =$val['jb_id'];break;
			// 			}
			// 		}
			// 		if(!empty($jb_exit)){
			// 			$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
			// 			$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
			// 			if($val['jb_level'] == 2)
			// 			{
			// 				$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
			// 				$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
			// 			}
			// 		}
			// 	}

			// }else{
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
			//}

		}



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}





		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}





		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}


        if ($jz_type == 1) {

            $where .= " AND o.is_first = 1";

        } elseif ($jz_type == 2) {

            $where .= " AND o.is_first = 0";

        }


		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if($bind == 1){



			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){



			$where .= ' AND  p.pat_weixin <> ""';

		}



		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if($order_time_type == 1)

		{

			$where .= ' AND o.order_time = 0';

		}

		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}





		if($order_type == 1)

		{//预约登记时间

			//$where .= " AND o.order_addtime >= ".$w_start." AND o.order_addtime <= ".$w_end;

			$orderby .= ',o.order_addtime DESC ';



			//关于咨询员查看数据的参数 数组

	   		$parm['w_start'] = $w_start;

			$parm['w_end'] = $w_end;



		}

		elseif($order_type == 2)

		{//预约时间

			//$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

			//关于咨询员查看数据的参数 数组

	   		$parm['w_start'] = $w_start;

			$parm['w_end'] = $w_end;

		}

		elseif($order_type == 3)

		{//实到时间<br />

			//$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

			$orderby .= ',o.come_time DESC ';

			//关于咨询员查看数据的参数 数组

	   		$parm['w_start'] = $w_start;

			$parm['w_end'] = $w_end;

		}

		elseif($order_type == 4)

		{

            //医生排班时间

			//$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

			$orderby .= ',o.doctor_time DESC ';

			//关于咨询员查看数据的参数 数组

	   		$parm['w_start'] = $w_start;

			$parm['w_end'] = $w_end;

        } elseif ($order_type == 5) {
        	//获取最新从公海捞取的order_id
        	//与gonghai_order表去比对order_id
        	//存在的order_id都是还在公海里面的，不存在的基本是被捞取了的
        	//由于公海查询开始时间如果超过当天会产生慢查询，所以通过时间判断避开
        	$max_today = strtotime(date('Y-m-d 23:59:59'),time());
            if ($w_start<=$max_today) {
                $sql_dump = " select t.order_id from ".$this->common->table('gonghai_log')." as t where t.action_time=(select max(t1.action_time) from ".$this->common->table('gonghai_log')." as t1 where t.order_id = t1.order_id and t1.action_type = '从公海捞取') and t.action_time >= ".$w_start." and t.action_time <= ".$w_end." and t.action_type = '从公海捞取' " ;

                $dump_res=$this->common->getAll($sql_dump);
                //echo $sql_dump;die();
                if (!empty($dump_res)) {
                    $dump_order_id = '';
                    foreach ($dump_res as $key => $value) {
                        $dump_order_id .= $value['order_id'].',';
                    }

                    $dump_order_id = substr($dump_order_id, 0, -1);

                    //p($dump_order_id);exit();

                    $where.= " AND o.order_id IN (".$dump_order_id.")";
                } else {
                    $where.= " AND o.order_id IN ('')";
                }
            } else {
                $where.= " AND o.order_id IN ('')";
            }
        }

		//查询回访人员
		$hf_name     = $_REQUEST['a_h'];
		$data['a_h'] = $hf_name;
		if(!empty($hf_name)){
			$order_remark =  $this->common->getAll("select DISTINCT order_id from " . $this->common->table('order_remark') . " where admin_name = '".$hf_name."' and  mark_type = 3   AND mark_time between ".$w_start." AND ".$w_end );
			$order_remark_order_no_str = '';
			foreach ($order_remark as $order_remark_temp){
				if(empty($order_remark_order_no_str)){
					$order_remark_order_no_str = $order_remark_temp['order_id'];
				}else{
					$order_remark_order_no_str .= ','.$order_remark_temp['order_id'];
				}
			}
			$parm = array();
			if(!empty($order_remark_order_no_str)){
				$where = " o.order_id in(".$order_remark_order_no_str.")";
			}else{
				$w_start = strtotime(date("Y-m-d",time()) . ' 00:00:00');
				$w_end = strtotime(date("Y-m-d",time()) . ' 23:59:59');
				$where = " o.order_id in(0)";
			}
		}else{
			/* 当输入患者的信息时，其他的搜索条件都不需要了 */

			if(!empty($patient_name))

			{



				$where = " p.pat_name = '". $patient_name . "'";

				$data['p_n']      = $patient_name;

				$data['p_p']      = "";

				$data['o_o']      = "";

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}



				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}

			if(!empty($_COOKIE["l_rank_id"])){

				$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

				$rank_type = $this->common->getRow($sql);

				//			if($rank_type['rank_type'] == 1){

				//				$where .= " AND o.doctor_name = '".$_COOKIE["l_admin_name"]."'";

				//			}

			}



			if(!empty($patient_phone))

			{

				$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

				$data['p_n']      = "";

				$data['p_p']      = $patient_phone;

				$data['o_o']      = "";

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}

				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}



			if(!empty($order_no))

			{

				$where = " o.order_no = '" . $order_no . "'";

				$data['p_n']      = "";

				$data['p_p']      = "";

				$data['o_o']      = $order_no;

				if(!empty($_COOKIE["l_hos_id"]))

				{

					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

				}



				if(!empty($_COOKIE["l_keshi_id"]))

				{

					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

				}

				$parm['huanzhe_check'] = '1';

			}

		}

		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}


		//$order_count = $this->model->order_count($where,$parm);

		$order_count = $this->model->seven_time_query_order_count($where,$parm);


		$order_count['wei'] = $order_count['count'] - $order_count['come'];

		$config = page_config();

		if($type == 'mi'){
			$config['base_url'] =   '?c=order&m=order_list&a_h='.$hf_name.'&&type=mi&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $order_type . '&date=' . $data['start'] . ' - ' . $data['end'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb']. '&jz_type=' . $_REQUEST['jz_type'];
		}else{
			$config['base_url'] =  '?c=order&m=order_list&a_h='.$hf_name.'&&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $order_type . '&date=' . $data['start'] . ' - ' . $data['end'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb']. '&jz_type=' . $_REQUEST['jz_type'];
		}

		$config['total_rows'] = $order_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);

        //获取相关数据

		$parm['rank_id'] = $data['admin']['rank_id'];

		$order_list = $this->model->seven_time_query_order_list($where, $page, $per_page, $orderby,$parm);


        //获取黑名单order_id
        $black_sql = "select order_id from hui_order_black as t where  add_time=(select max(t1.add_time) from hui_order_black as t1 where t.order_id = t1.order_id ) and type = 1";
        $black_order = $this->db->query($black_sql)->result_array();
        $black_list_ids = array();
        foreach ($black_order as $key => $value) {
            $black_list_ids[] = $value['order_id'];
        }
        $black_list_ids_str = implode(',', $black_list_ids);

        foreach ($order_list as $key => $value) {
            if (preg_match('/'.$value['order_id'].'/', $black_list_ids_str)) {
                $order_list[$key]['is_black'] = 1;
            } else {
                $order_list[$key]['is_black'] = 0;
            }
            //预到时间修改次数
            $sql = "select order_id,count(order_id) as count from henry_ordertime_update_record where order_id = {$value['order_id']}";
            $row = $this->db->query($sql)->row_array();

            if ($row){
                $order_list[$key]['ot_times'] = $row['count'];
            } else {
                $order_list[$key]['ot_times'] = 0;
            }
        }
        //p($order_list);die;



		//$order_list = $this->model->order_list($where, $page, $per_page, $orderby,$parm);


		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		$dao_false_arr = array();

		foreach($dao_false as $val)

		{

			$dao_false_arr[$val['false_id']] = $val['false_name'];

		}

		//添加所属医院所有医生代码开始

		$hos_array=isset($_COOKIE['l_hos_id'])?$_COOKIE['l_hos_id']:'';//获取所属医院字符串

		$doctor_list=$this->model->doctor_list($hos_array);//依据字符串调用相关功能

		$data['doctor_list']=$doctor_list;//新增医生列表

		//添加所属医院所有医生代码结束

		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

		$flag_1 = array();



		$flag_2 = array();

		foreach($province as $val){

			if(trim($val['region_name'])=='广东'||trim($val['region_name'])=='浙江'){

				$flag_1[] = $val;

			}else{

				$flag_2[] = $val;

			}

		}

		$province_list = array_merge($flag_1,$flag_2);



		/** 2016 10 27  新增加 表头显示下拉框的 效果**/

		$appointment_route = array();//预约途径

		$appointment_section= array();//预约科室

		$appointment_disease= array();//预约病种

		$consult_a_doctor= array();//咨询医生

		 $order_cotent_str= '';//订单ID 字符
		foreach($order_list as $key=>$val){
			//获取公海捞取时间
			$order_list[$key]['gonghai_time'] = 0;
			$action_time = $this->common->getAll("SELECT max(action_time) as action_time FROM " . $this->common->table("gonghai_log") . " WHERE order_id in(".$val['order_id'].") and action_type = '从公海捞取'");
			if(count($action_time) > 0 && !empty($action_time[0]['action_time'])){
				$order_list[$key]['gonghai_time'] = $action_time[0]['action_time'];
			}
			/***
			if($val['keshi_id'] == 1 || $val['keshi_id'] == 32){
				//如果仁爱妇科+不孕的超过7天的 则自动将次数修改为3次
				$order_reanai_sum = $this->common->getAll("SELECT id,order_time,sum FROM " . $this->common->table("order_reanai_sum") . " WHERE order_id = ".$val['order_id']);
				if(!empty($order_reanai_sum)){
					if(count($order_reanai_sum) > 0){
						if(time() > $order_reanai_sum[0]['order_time'] && $order_reanai_sum[0]['sum'] < 3){
							$order_reanai_sum= array();
							$order_reanai_sum['sum'] = 3;
							$this->db->update($this->common->table("order_reanai_sum"), $order_reanai_sum, array('order_id' => $val['order_id']));
						}
					}
				}
			}**/

			/**
			 *
			 * 2017 0629 添加功能
			 *  判断是否是  仁爱的 不孕 或者妇科
			 *  由于存在历史的数据 需要处理为 现在的7天控制机制
			 *
			 *  修改为以今天为止  +7天。
			 *
			 *  记录仁爱 妇科 不孕的预到时间 到 预到时间统计表
			 *
			if(($val['keshi_id'] == 1 || $val['keshi_id'] == 32) && $val['is_come'] == 0 && $val['come_time'] == 0 && !empty($val['order_time'])){
				$order_reanai_sum = $this->common->getAll("SELECT order_id FROM " . $this->common->table('order_reanai_sum') . "  where order_id in(".$val['order_id'].")" );
				if(count($order_reanai_sum) == 0){
					$hui_order_reanai_sum = array();
					//第一次预到时间 在加 $dy 天
					//$hui_order_reanai_sum['order_time']  = strtotime($val['order_time']." 23:59:59")+$dy*24*60*60;
					$hui_order_reanai_sum['order_time']  = time()+$dy*24*60*60;
					$hui_order_reanai_sum['order_id'] =$val['order_id'];
					$hui_order_reanai_sum['sum'] = 0;
					$this->db->insert($this->common->table("order_reanai_sum"), $hui_order_reanai_sum);
				}
			}**/


			if(empty($order_cotent_str)){
				$order_cotent_str= $val['order_id'];
			}else{
				$order_cotent_str  .= ','.$val['order_id'];
			}

			$parm_count = 0;

			foreach($appointment_route as $appointment_route_key=>$appointment_route_val){

				if($appointment_route_val['from_id'] == $val['from_parent_id']){

					$parm_count = $appointment_route_val;

					$appointment_route[$appointment_route_key]['order_id']= $appointment_route[$appointment_route_key]['order_id'].','.$val['order_id'];

					$appointment_route[$appointment_route_key]['order_count']+=1;

					break;

			    }

			}

			if($parm_count == 0){

				foreach($from_list as $from_list_key=>$from_list_val){

					if($from_list_val['from_id'] == $val['from_parent_id']){

					 	$parm = array();//预约途径

						$parm['from_id'] = $from_list_val['from_id'];

						$parm['from_name'] = $from_list_val['from_name'];

						$parm['order_id'] = $val['order_id'];

						$parm['order_count'] +=1;

						$appointment_route[]  =$parm;

					    break;

					}

				}

			}

			$parm_count = 0;

			foreach($appointment_section as $appointment_section_key=>$appointment_section_val){

				if($appointment_section_val['keshi_id'] == $val['keshi_id']){

					$parm_count = $appointment_section_val;

					$appointment_section[$appointment_section_key]['order_id']= $appointment_section[$appointment_section_key]['order_id'].','.$val['order_id'];

					$appointment_section[$appointment_section_key]['order_count']+=1;

					break;

			    }

			}

			if($parm_count == 0){

				foreach($keshi as $keshi_key=>$keshi_val){

					if($keshi_val['keshi_id'] == $val['keshi_id']){

					 	$parm = array();//预约科室

						$parm['keshi_id'] = $keshi_val['keshi_id'];

						$parm['keshi_name'] = $keshi_val['keshi_name'];

						$parm['order_id'] = $val['order_id'];

						$parm['order_count'] +=1;

						$appointment_section[]  =$parm;

					    break;

					}

				}

			}

			$parm_count = 0;

			foreach($appointment_disease as $jibing_key=>$jibing_val){

				if($jibing_val['jb_id'] == $val['jb_parent_id']){

					$parm_count = $jibing_val;

					$appointment_disease[$jibing_key]['order_id']= $appointment_disease[$jibing_key]['order_id'].','.$val['order_id'];

					$appointment_disease[$jibing_key]['order_count']+=1;

					break;

			    }

			}

			if($parm_count == 0){

				foreach($jibing as $jibing_key=>$jibing_val){

					if($jibing_val['jb_id'] == $val['jb_parent_id']){

					 	$parm = array();//预约科室

						$parm['jb_id'] = $jibing_val['jb_id'];

						$parm['jb_name'] = $jibing_val['jb_name'];

						$parm['order_id'] = $val['order_id'];

						$parm['order_count'] +=1;

						$appointment_disease[]  =$parm;

					    break;

					}

				}

			}

			 $parm_count = 0;

			foreach($consult_a_doctor as $consult_a_doctor_key=>$consult_a_doctor_val){

				if($consult_a_doctor_val['admin_name'] == $val['admin_name']){

					$parm_count = $consult_a_doctor_val;

					$consult_a_doctor[$consult_a_doctor_key]['order_id']= $consult_a_doctor[$consult_a_doctor_key]['order_id'].','.$val['order_id'];

					$consult_a_doctor[$consult_a_doctor_key]['order_count']+=1;

					break;

			    }

			}

			if($parm_count == 0){

				$parm = array();//预约科室

				$parm['admin_name'] = $val['admin_name'];

				$parm['order_id'] = $val['order_id'];

				$parm['order_count'] +=1;

				$consult_a_doctor[]  =$parm;

			}

		}

		$data['appointment_route'] = $appointment_route;

		$data['appointment_section'] = $appointment_section;

		$data['appointment_disease'] = $appointment_disease;

		$data['consult_a_doctor'] = $consult_a_doctor;

        if(empty($order_cotent_str)){$order_cotent_str = 0;}
		$data['order_content'] = $this->common->getAll("SELECT * FROM " . $this->common->table('ask_content') . "  where order_id in(".$order_cotent_str.")" );

		$data['order_laiyuanweb'] = $this->common->getAll("SELECT order_id,form FROM " . $this->common->table('order_laiyuanweb') . "  where order_id in(".$order_cotent_str.")" );


		$data['down_url'] = '?c=order&m=order_list_down&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu. '&jz_type=' . $jz_type; // 导出数据的URL

		$data['area'] = $area;

		$data['province'] = $province_list;

		$data['rank_type'] = $rank_type;

		$data['dao_false'] = $dao_false;

		$data['huifang'] = $this->set_huifang();

		$data['dao_false_arr'] = $dao_false_arr;

		$data['page'] = $this->pagination->create_links();

		$data['per_page'] = $per_page;

		$data['order_list'] = $order_list;

		$data['order_count'] = $order_count;

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);


		 //计算推送的数量
		 $data['ireport_order_ount'] =0;
		 if(strcmp($_COOKIE["l_admin_action"],'all') ==0){
			 $where_ts = ' ireport_order_id > 0';
			if(empty($hos_id)){
				if(!empty($_COOKIE["l_hos_id"])){
					$where_ts .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
			}else{
				$where_ts .= ' AND o.hos_id = ' . $hos_id;
			}
			if(empty($keshi_id)){
				if(!empty($_COOKIE["l_keshi_id"])){
					$where_ts .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}else{
				$where_ts .= ' AND o.keshi_id = ' . $keshi_id;
			}
			if($order_type == 1){//预约登记时间
				$where_ts .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
			}else if($order_type == 2){//预约时间
				$where_ts .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
			}else if($order_type == 3){//实到时间
				$where_ts .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
			}else if($order_type == 4){//医生排班时间
				$where_ts .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;
			}
			if(strcmp($where_ts,' ireport_order_id > 0') !=0){
				$sql_ts = "SELECT count(o.ireport_order_id) as count FROM " . $this->common->table('order') ."  as o where ".$where_ts;
				$gonghai_ts_order_data = $this->common->getAll($sql_ts);
				//echo $config['total_rows'].'/'.$gonghai_ts_order_data[0]['count'].'/'.$sql_ts;exit;
				if(count($gonghai_ts_order_data) > 0){
					if(empty($gonghai_ts_order_data[0]['count'])){
						$gonghai_ts_order_data[0]['count'] =0 ;
					}
					$data['ireport_order_ount'] =$config['total_rows'] - $gonghai_ts_order_data[0]['count'];
				}
			}
		 }


		if($type == 'mi')

		{

			$this->load->view('order_list_mi', $data);

		}

		else

		{

			$this->load->view('order_list', $data);

		}



	}







	 //预约列表

	public function order_report_list()

	{

		$data = array();

		$data           = $this->common->config('order_report_list');

		$year           = isset($_REQUEST['year'])? trim($_REQUEST['year']):date('Y',time());//年份

		$moth           = isset($_REQUEST['moth'])? trim($_REQUEST['moth']):date('m',time());//月份

		/***

		获取指定月份 的  月初月末的时间

		**/

		$BeginDate=$year.'-'.$moth.'-01'; //获取当前月份第一天

		$moth_first = $BeginDate;

		$moth_last_date = strtotime("$BeginDate +1 month -1 day");

		$moth_last = date('Y-m-d',$moth_last_date);    // 加一个月减去一天

		//2016年11月08日 - 2016年11月08日

		$date = $year.'年'.$moth.'月01日-'.date('Y',$moth_last_date).'年'.date('m',$moth_last_date).'月'.date('d',$moth_last_date).'日';

		//echo $date;exit;

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';



        $excel_status           = isset($_REQUEST['excel_status'])? trim($_REQUEST['excel_status']):'';//导出excel的标志  有值则导出  无值则查询



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['a_i']      = $asker_name;

//判断日期是否为空

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }

			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}



                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

                //而$[start_date]表示xx-xx-xx这种格式的日期



                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);



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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

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



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');//调用CI自带的page分页类



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}





		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}

		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}

	    $excel_status         = isset($_REQUEST['excel_status'])? intval($_REQUEST['excel_status']):0;

		/* 时间条件 */



		$w_start = strtotime($moth_first . ' 00:00:00');

		$w_end = strtotime($moth_first . ' 23:59:59');

        /**

		$where .= " AND ( ( o.order_addtime >= ".$w_start." AND o.order_addtime <= " .$w_end." )"

		            ."  or (  o.order_time >= " .$w_start. " AND o.order_time <= " .$w_end." ) "

					."  or (  o.come_time >= " .$w_start. " AND o.come_time <= " .$w_end."))";

			**/





        // 计算月初 月末的时间



		 $where .= " AND ( ( o.order_addtime >= ".strtotime($moth_first)." AND o.order_addtime <= " .strtotime($moth_last)." )"

		            ."  or (  o.order_time >= " .strtotime($moth_first). " AND o.order_time <= " .strtotime($moth_last)." ) "

					."  or (  o.come_time >= " .strtotime($moth_first). " AND o.come_time <= " .strtotime($moth_last)."))";





		if(!empty($_COOKIE["l_rank_id"])){

			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

			$rank_type = $this->common->getRow($sql);

		}



		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}



        //获取相关数据

		$order_list = $this->common->getAll("SELECT * FROM " . $this->common->table('order') . "  as o where o.keshi_id not in(7,17,18,19,31) and ".$where );



		$data['rank_type'] = $rank_type;

		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$data['year'] = $year;

		$data['moth'] = $moth;





 		// 判断当前的科室

		$field_name = '待选医院';

		if(empty($keshi_id)){

			// 判断是否是 仁爱医院  仁爱医院 不选择科室的是 默认 仁爱妇科加不孕，其他可以不选科室，则为空

			if($hos_id == 1){

				$field_name = '仁爱妇科+不孕不育';

			}else{

				$sql = "SELECT h.hos_name FROM " . $this->common->table('hospital') . " h  WHERE h.hos_id = ".$hos_id;

				$hospital_row = $this->common->getAll($sql);

				foreach($hospital_row as $hospital_val)

				{

					$field_name =$hospital_val['hos_name'];

				}

			}

		}else{

			$sql = "SELECT h.hos_name,k.keshi_name FROM " . $this->common->table('hospital') . " h, " . $this->common->table('keshi') . " k   WHERE h.hos_id = ".$hos_id." and k.keshi_id = ".$keshi_id;

			$hospital_row = $this->common->getAll($sql);

			foreach($hospital_row as $hospital_val)

			{

				$field_name =$hospital_val['hos_name'].$hospital_val['keshi_name'];

			}

		}

		$data['field_name'] = $field_name;



		//星期一 māng děi星期二 tū s děi星期三 wān s děi星期四 sē s děi 发s的音时,牙齿咬着舌头发.星期五 f rāi děi星期六 sā tě děi星期天

		$weeek_arr=array("日","一","二","三","四","五","六");



		/***

  	    echo  "<br/>数组从组开始<br/>";

		 echo date("Y-m-d H:i:s",time()) ;

		 ****/



        // 设置表头

		$fields = array();

		$day_last = date('d', strtotime("$BeginDate +1 month -1 day"));// 加一个月减去一天



		$zixun_arr = array();

		$zixun_arr_name = array();

		foreach($order_list as $order_list_val){

 					$zixun_check =0;

					foreach($zixun_arr as $zixun_arr_key=>$zixun_arr_val){



						 if($zixun_arr_val['admin_id'] == $order_list_val['admin_id']){

							    // 必须在当前年份 月份 之中

								 if(!empty($order_list_val['order_addtime'])){

										if( intval(date("Y",$order_list_val['order_addtime'])) == $year &&  intval(date("m",$order_list_val['order_addtime'])) == intval($moth)){

											$order_addtime_index = intval(date("d",$order_list_val['order_addtime']))-1;

											 if(!isset($zixun_arr[$zixun_arr_key]['order_addtime'][$order_addtime_index])){

												$zixun_arr[$zixun_arr_key]['order_addtime'][$order_addtime_index] = 0;

											}

											$zixun_arr[$zixun_arr_key]['order_addtime'][$order_addtime_index]=$zixun_arr[$zixun_arr_key]['order_addtime'][$order_addtime_index]+1;



										}

								}

								if(!empty($order_list_val['order_time'])){

										if( intval(date("Y",$order_list_val['order_time'])) == $year &&  intval(date("m",$order_list_val['order_time'])) == intval($moth)){

										    $order_time_index = intval(date("d",$order_list_val['order_time']))-1;

											 if(!isset($zixun_arr[$zixun_arr_key]['order_time'][$order_time_index])){

												$zixun_arr[$zixun_arr_key]['order_time'][$order_time_index] = 0;

											}

											$zixun_arr[$zixun_arr_key]['order_time'][$order_time_index]=$zixun_arr[$zixun_arr_key]['order_time'][$order_time_index]+1;

										}

								}



								if(!empty($order_list_val['come_time'])){//判断预约 预到 到诊 每项数据

										if( intval(date("Y",$order_list_val['come_time'])) == $year &&  intval(date("m",$order_list_val['come_time'])) == intval($moth)){

										 $come_time_index = intval(date("d",$order_list_val['come_time']))-1;

										 if(!isset($zixun_arr[$zixun_arr_key]['come_time'][$come_time_index])){

											$zixun_arr[$zixun_arr_key]['come_time'][$come_time_index] = 0;

										}

										$zixun_arr[$zixun_arr_key]['come_time'][$come_time_index]=$zixun_arr[$zixun_arr_key]['come_time'][$come_time_index]+1;



									}



								}





								$zixun_check =1;break;

						}

					}

					if($zixun_check == 0){

						$zixun_arr_temp = array();

						$zixun_arr_temp['admin_id'] = $order_list_val['admin_id'];

						$day_index =0;

						$day_array= array();

						while($day_index < $day_last){

							$day_array[] = 0;

							$day_index++;

						}

						$zixun_arr_temp['order_addtime'] = $day_array;

						$zixun_arr_temp['order_time'] = $day_array;

						$zixun_arr_temp['come_time'] = $day_array;



						$day_index = 0;

						if(!empty($order_list_val['come_time'])){

							// 必须在当前年份 月份 之中

							$date_index =intval(date("m",$order_list_val['come_time']));

							if(intval(date("Y",$order_list_val['come_time'])) == $year &&  $date_index == intval($moth)){

								$zixun_arr_temp['come_time'][$date_index] = 1;

							}

						}

						if(!empty($order_list_val['order_time'])){

							// 必须在当前年份 月份 之中

							$date_index =intval(date("m",$order_list_val['order_time']));

							if( intval(date("Y",$order_list_val['order_time'])) == $year && $date_index == intval($moth)){



								$zixun_arr_temp['order_time'][$date_index] = 1;

							}

						}

						if(!empty($order_list_val['order_addtime'])){

							// 必须在当前年份 月份 之中

							$date_index =intval(date("m",$order_list_val['order_addtime']));

							if( intval(date("Y",$order_list_val['order_addtime'])) == $year &&  $date_index == intval($moth)){

								$zixun_arr_temp['order_addtime'][$date_index ] = 1;

							}

						}



						$zixun_arr[] = $zixun_arr_temp;

						$zixun_arr_name[$order_list_val['admin_id']] = $order_list_val['admin_name'];

					}



		}



	    $day_index =1;

		while($day_index <= $day_last){

			$moth_str= $moth;

			if($moth < 10 ){

				$moth_str= '0'.$moth;

		    }

			$day_index_str= $day_index;

			if($day_index < 10 ){

				$day_index_str= '0'.$day_index;

		    }

			$day_index_ling_time = strtotime($year.'-'.$moth_str.'-'.$day_index_str.' 01:00:00');

			  if($day_index < 8){

				   $fields[$day_index.'日('.$weeek_arr[date("w",$day_index_ling_time)].')'] = $day_index;

			 }else{

				$fields[$day_index.'日'] = $day_index;

			}

			 $day_index++;

		}

		$w_start_strtotime  = strtotime(date("Y-m-d",$w_start));





		 /**2016 11 21  下载查询预约报表数据 **/

		 $order_report_list = 0;
		 if(strcmp($_COOKIE['l_admin_action'],'all') != 0){
			 $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
		     if(in_array(153,$l_admin_action)){
		     	$order_report_list = 1;
	         }
		}else{
			$order_report_list = 1;
		}

		if($order_report_list == 0){

			 $zixun_arr = array();

		} else{

		   $data['order_report_list'] =$menu_son_item['act_action'];

		}

		$data['order_list'] = $zixun_arr;

	    $data['fields'] = $fields;

		$data['zixun_arr_name'] = $zixun_arr_name;



		/***

		   2016 11 18 导出excel数据

		****/

		/**

		     检查是否是需要导出excel

		***/

		if($excel_status == 1){

			   // 清空输出缓冲区

				//ob_clean();

				// 载入PHPExcel类库

				$this->load->library('PHPExcel');

				$this->load->library('PHPExcel/IOFactory');



				// 创建PHPExcel对象

				$objPHPExcel = new PHPExcel();

				// 设置excel文件属性描述

				$objPHPExcel->getProperties()

							->setTitle(time())

							->setDescription(time());

				// 设置当前工作表

				$objPHPExcel->setActiveSheetIndex(0);



				// 列编号从0开始，行编号从1开始



				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,$field_name );

				$col = 1;

				$row = 2;

				foreach($fields as $field_key=>$field)

				{

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row,$field_key);

					$col = $col+2;

				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row,'汇总');

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, $row,'到诊率');

				$row = 3;

				$col = 1;

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,"咨询员");

				foreach($fields as $field_key=>$field)

				{

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row,'约');

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row,'到');

				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row,'约');

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col++, $row,'到');



				//默认excel字母数组

				$wrod_arr = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ");



				// 从第二行开始输出数据内容

				$row = 4;

				$zixun_arr_day_total_order_addtime = array();// 每天的预约总计

				$zixun_arr_day_total_come_time = array();// 每天的到诊总计

				$zixun_arr_total_count_all_order_addtime =0 ;// 一个月所有人的预约 总和

				$zixun_arr_total_count_all_come_time =0 ;// 一个月所有人的到诊 总和

				foreach($zixun_arr as $key=>$item)

				{

				    $zixun_percent_count_order_addtime = 0;//单个人的当月 预约 总和

					$zixun_percent_count_order_time = 0;//单个人的当月 预到 总和

					$zixun_percent_count_come_time = 0;//单个人的当月 到诊 总和

					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,$zixun_arr_name[$item['admin_id']]);

					$item_index= 1;

					foreach($item['order_addtime'] as $item_key=>$item_val)

					{

						//当天时间判断

						$date_bol = 0;

						if($year <= date('Y',time()) && $moth <= date('m',time()) && ($item_key+1) <= date('d',time()) ){

							$date_bol = 1;

						}

						if(empty( $item_val )){

							  $item_val  = 0;

						}

						//每个人的当月的 预约 总和

						$zixun_percent_count_order_addtime =$zixun_percent_count_order_addtime+$item_val;



						//每人每天的 预约  统计

						if(isset($zixun_arr_day_total_order_addtime[$item_key])){

						   $zixun_arr_day_total_order_addtime[$item_key]	 = $zixun_arr_day_total_order_addtime[$item_key] + $item_val;

						}else{

						   $zixun_arr_day_total_order_addtime[$item_key]	 = $item_val;

						}

						if($item_key == 0){

							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row,$item_val);

							//echo  $item_key.'/'.$wrod_arr[$item_index-1]."<br/>";

							if(intval($item_val) == 0  && $date_bol == 1){

								 //设置填充的样式和背景色

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[1].$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[1].$row)->getFill()->getStartColor()->setARGB("00f7ef36");

							}

						}else{

							$item_index=$item_index+2;

						    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$item_val);

							//echo  $item_key.'/'.$wrod_arr[$item_index-1]."<br/>";

							if(intval($item_val) == 0  && $date_bol == 1){

								 //设置填充的样式和背景色

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[$item_index].$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[$item_index].$row)->getFill()->getStartColor()->setARGB("00f7ef36");

							}

						}



					}



					$item_index= 2;

					foreach($item['come_time'] as $item_key=>$item_val)

					{

						//当天时间判断

						$date_bol = 0;

						if($year <= date('Y',time()) && $moth <= date('m',time()) && ($item_key+1) <= date('d',time()) ){

							$date_bol = 1;

						}

						if(empty( $item_val )){

							  $item_val  = 0;

						}

						//每个人的当月的 预约 总和

						$zixun_percent_count_come_time =$zixun_percent_count_come_time+$item_val;

						//每个人的当月的 到诊 总和

						if(!isset($item['order_time'][$item_key])){

							$item['order_time'][$item_key] = 0;

						}

						$zixun_percent_count_order_time =$zixun_percent_count_order_time+$item['order_time'][$item_key];





						//每人每天的 预约  统计

						if(isset($zixun_arr_day_total_come_time[$item_key])){

						   $zixun_arr_day_total_come_time[$item_key]	 = $zixun_arr_day_total_come_time[$item_key] + $item_val;

						}else{

						   $zixun_arr_day_total_come_time[$item_key]	 = $item_val;

						}

						if($item_key == 0){

							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row,$item_val);

							//echo  $item_key.'/'.$wrod_arr[$item_index-1]."<br/>";

							if(intval($item_val) == 0  && $date_bol == 1){

								 //设置填充的样式和背景色

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[2].$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[2].$row)->getFill()->getStartColor()->setARGB("00f7ef36");

							}

						}else{

							$item_index=$item_index+2;

						    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$item_val);

							//echo  $item_key.'/'.$wrod_arr[$item_index-1]."<br/>";

							if(intval($item_val) == 0  && $date_bol == 1){

								 //设置填充的样式和背景色

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[$item_index].$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

							   $objPHPExcel->getActiveSheet()->getStyle($wrod_arr[$item_index].$row)->getFill()->getStartColor()->setARGB("00f7ef36");

							}

						}



					}

					 $item_index++;



					$zixun_arr_total_count_all_order_addtime= $zixun_arr_total_count_all_order_addtime+$zixun_percent_count_order_addtime;

					$zixun_arr_total_count_all_come_time= $zixun_arr_total_count_all_come_time+$zixun_percent_count_come_time;



					  // 单人当月总和

					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$zixun_percent_count_order_addtime);

					  $item_index++;

					 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$zixun_percent_count_come_time);

					  $item_index++;

					  if($zixun_percent_count_order_addtime > 0){

						  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,round(($zixun_percent_count_come_time/$zixun_percent_count_order_time)*100).'%');

					  }else{

					      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,'0%');

					  }

					 $row++;

				}



				$row = count($zixun_arr)+4;

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row,'总计');

				$item_index=1 ;

				foreach($zixun_arr_day_total_order_addtime as $zixun_arr_day_total_key=>$zixun_arr_day_total_item)

				{

						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$zixun_arr_day_total_item);

						$item_index++;

						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$zixun_arr_day_total_come_time[$zixun_arr_day_total_key]);

						$item_index++;

				}



				 // 所有人 全月总和

				  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index, $row,$zixun_arr_total_count_all_order_addtime);

				  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($item_index+1, $row,$zixun_arr_total_count_all_come_time);





				//最后的总和数据写入

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(count($fields)*2+3, $row,$zixun_arr_total_count_all_order_addtime+$zixun_arr_total_count_all_come_time);

 		     //exit;

				//输出excel文件

				$objPHPExcel->setActiveSheetIndex(0);

				// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)

				$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

				// 设置HTTP头

				header('Content-Type: application/vnd.ms-excel; charset=utf-8');

				header('Content-Disposition: attachment;filename="'.mb_convert_encoding($field_name.'列表'.time(), "GB2312", "UTF-8").'.xls"');

				header('Cache-Control: max-age=0');

				$objWriter->save('php://output');

	    }else{

			$this->load->view('order_report_list', $data);

		}

	}







	/**留联报表导出*/

	public function order_liulian_list_down()

	{
		$data = array();

		$data = $this->common->config('order_liulian_list_down');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;



		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time() - 86400);

				$data['end'] = date("Y年m月d日", time());

			}

		}



		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

		$keshi_arr = array();

		foreach($keshi as $val)

		{

			$keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];

			$keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];

			$keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];

			$keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];

			$keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];

			$keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];

		}

		$from_list = $this->model->from_order_list(-1);

		$par_from_arr = array();

		$from_arr = array();

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

		$from_list_son = $from_arr;

		$type_list = $this->model->type_order_list();

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

		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			foreach($jibing as $val)

			{

				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];

				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];

				$jibing_list[$val['jb_id']]['jb_code'] = $val['jb_code'];

				if($val['jb_level'] == 2)

				{

					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];

					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];

					$jibing_parent[$val['jb_id']]['jb_code'] = $val['jb_code'];

				}

			}

		}



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;



		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

		}

		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

		$per_page = empty($per_page)? 30:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');



		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}



		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}



		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}



		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 25200;

			$w_end = strtotime($end . ' 17:00:00');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}



		/* 时间条件 */

		if($order_type == 1)

		{

			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

			$orderby .= ',o.order_addtime DESC ';

		}

		elseif($order_type == 2)

		{

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

		}

		elseif($order_type == 3)

		{

			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

			$orderby .= ',o.come_time DESC ';

		}

		elseif($order_type == 4)

		{

			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

			$orderby .= ',o.doctor_time DESC ';

		}



		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{

			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";



			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($patient_phone))

		{

			$where = " p.pat_phone = '". $patient_phone . "'";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";



			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;



			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_id DESC";

		}



		$order_list = $this->common->getAll("SELECT o.*,p.* FROM " . $this->common->table('order_liulian') . " as o  LEFT JOIN " . $this->common->table('patient') . " as  p ON p.pat_id = o.pat_id   WHERE ".$where .$orderby." LIMIT 0, 10000");



		$area = $this->model->area();

		// 清空输出缓冲区

		//ob_clean();

		// 载入PHPExcel类库


		set_time_limit(0);

		ini_set('memory_limit', '256M');

		$this->load->library('PHPExcel');

		$this->load->library('PHPExcel/IOFactory');

		// 创建PHPExcel对象

		$objPHPExcel = new PHPExcel();

		// 设置excel文件属性描述

		$objPHPExcel->getProperties()

					->setTitle(time())

					->setDescription(time());

		// 设置当前工作表

		$objPHPExcel->setActiveSheetIndex(0);

		// 设置表头

		$fields = array('预约日期','预约到诊日期','留联编号','预约编号', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式','方式子类', '地区', '预约性质', '预约人', '来源网址','关键词', '备注', '其他记录');


		// 列编号从0开始，行编号从1开始

		$col = 0;

		$row = 1;

		foreach($fields as $field)

		{

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);

			$col++;

		}

		// 从第二行开始输出数据内容

		$row = 2;

		$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

		$rank_type = $this->common->getRow($sql);

		foreach($order_list as $val)

		{

			$val["order_addtime"] = date('Y-m-d H:i:s',$val["order_addtime"]);
			if(!empty($val["order_time"])){
			$val["order_time"] = date('Y-m-d',$val["order_time"]);
			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $val["order_no"]);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no_yy"]);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["pat_name"]);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_age"]);

			if($rank_type['rank_type']==2||$rank_type['rank_type']==3||$_COOKIE['l_admin_action'] == 'all'||$_COOKIE['l_admin_id']=129){

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_phone"]);

			}else{

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, substr_replace($val["pat_phone"],'****',7,4));

			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, (isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($jibing_list[$val["jb_id"]])? $jibing_list[$val["jb_id"]]['jb_name']:''));

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_code']:'');

			$from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name']:'';

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $from_name);

			$from_name_son = isset($from_list_son[$val["from_id"]])? $from_list_son[$val["from_id"]]['from_name']:'';

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $from_name_son);

			$a = '';

			if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}

		    if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}

		    if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val["admin_name"]);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, isset($val['laiyuanweb'])? $val['laiyuanweb']:'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, isset($val['guanjianzi'])? $val['guanjianzi']:'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, isset($val['remark'])? $val['remark']:'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, isset($val['con_content'])? $val['con_content']:'');



			$row++;

		}

		//输出excel文件

		$objPHPExcel->setActiveSheetIndex(0);

		// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)

		$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

		// 设置HTTP头

		header('Content-Type: application/vnd.ms-excel; charset=utf-8');

		header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');

		header('Cache-Control: max-age=0');

		$objWriter->save('php://output');

	}







    public function order_list_down()

    {


        $data = array();

        $data = $this->common->config('order_list_down');

        $date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';

        $hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

        $keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

        $patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';

        $patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';

        $order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';

        $from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

        $from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

        $asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

        $status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

        $type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

        $order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

        $p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

        $jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

        $wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

        $type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

        $jz_type        = isset($_REQUEST['jz_type'])? intval(trim($_REQUEST['jz_type'])):0;

        $data['hos_id']   = $hos_id;

        $data['keshi_id'] = $keshi_id;

        $data['p_n']      = $patient_name;

        $data['p_p']      = $patient_phone;

        $data['o_o']      = $order_no;

        $data['f_p_i']    = $from_parent_id;

        $data['f_i']      = $from_id;

        $data['a_i']      = $asker_name;

        $data['s']        = $status;

        $data['o_t']      = $type_id;

        $data['t']        = $order_type;

        $data['p_jb']     = $p_jb;

        $data['jb']       = $jb;



        if(!empty($date))

        {

            $date = explode(" - ", $date);

            $start = $date[0];

            $end = $date[1];

             if(!$this->dateCheck($start) || !$this->dateCheck($end)){
              $start = date("Y年m月d日");
              $end = $start;
           }


            $data['start'] = $start;

            $data['end'] = $end;

            $start = str_replace(array("年", "月", "日"), "-", $start);

            $end = str_replace(array("年", "月", "日"), "-", $end);

            $start = substr($start, 0, -1);

            $end = substr($end, 0, -1);

            $data['start_date'] = $start;

            $data['end_date'] = $end;

            setcookie('start_time', strtotime($start));

            setcookie('end_time', strtotime($end));

        }

        else

        {

            if(isset($_COOKIE['start_time']))

            {

                $start = date("Y-m-d", $_COOKIE['start_time']);

                $end = date("Y-m-d", $_COOKIE['end_time']);

                $data['start_date'] = $start;

                $data['end_date'] = $end;

                $data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

                $data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

            }

            else

            {

                $start = date("Y-m-d", time());

                $end = date("Y-m-d", time());

                $data['start_date'] = $start;

                $data['end_date'] = $end;

                $data['start'] = date("Y年m月d日", time() - 86400);

                $data['end'] = date("Y年m月d日", time());

            }

        }



        $hospital = $this->model->hospital_order_list();

        $keshi = $this->model->keshi_order_list();

        $keshi_arr = array();

        foreach($keshi as $val)

        {

            $keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];

            $keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];

            $keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];

            $keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];

            $keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];

            $keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];

        }

        $from_list = $this->model->from_order_list(-1);

        $par_from_arr = array();

        $from_arr = array();

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

        $from_list_son = $from_arr;

        $type_list = $this->model->type_order_list();

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

        $hos_id_arr = array();

        $keshi_id_arr = array();

        $jibing = read_static_cache('jibing_list');

        $jibing_list = array();

        $jibing_parent = array();

        if(!empty($jibing))

        {

            foreach($jibing as $val)

            {

                $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];

                $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];

                $jibing_list[$val['jb_id']]['jb_code'] = $val['jb_code'];

                if($val['jb_level'] == 2)

                {

                    $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];

                    $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];

                    $jibing_parent[$val['jb_id']]['jb_code'] = $val['jb_code'];

                }

            }

        }



        $data['from_arr'] = $from_arr;

        $data['keshi'] = $keshi_arr;

        $data['jibing'] = $jibing_list;

        $data['jibing_parent'] = $jibing_parent;



        foreach($hospital as $val)

        {

            $hos_id_arr[] = $val['hos_id'];

        }

        foreach($keshi as $val)

        {

            $keshi_id_arr[] = $val['keshi_id'];

        }



        $page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

        $per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;

        $per_page = empty($per_page)? 30:$per_page;

        $this->load->library('pagination');

        $this->load->helper('page');



        /* 处理判断条件 */

        $where = 1;

        $orderby = '';

        if(empty($hos_id))

        {

            if(!empty($_COOKIE["l_hos_id"]))

            {

                $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

            }

        }

        else

        {

            $where .= ' AND o.hos_id = ' . $hos_id;

        }



        if(empty($keshi_id))

        {

            if(!empty($_COOKIE["l_keshi_id"]))

            {

                $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

            }

        }

        else

        {

            $where .= ' AND o.keshi_id = ' . $keshi_id;

        }



        if(!empty($from_parent_id))

        {

            $where .= ' AND o.from_parent_id = ' . $from_parent_id;

        }



        if(!empty($from_id))

        {

            $where .= ' AND o.from_id = ' . $from_id;

        }



        if(!empty($p_jb))

        {

            $where .= ' AND o.jb_parent_id = ' . $p_jb;

        }



        if(!empty($jb))

        {

            $where .= ' AND o.jb_id = ' . $jb;

        }



        if(!empty($asker_name))

        {

            $where .= " AND o.admin_name = '" . $asker_name . "'";

        }

        if ($jz_type == 1) {

            $where .= " AND o.is_first = 1";

        } elseif ($jz_type == 2) {

            $where .= " AND o.is_first = 0";

        }

        /* 订单状态 */

        if($status == 1)

        {

            $where .= ' AND o.is_come = 0';

        }

        elseif($status == 2)

        {

            $where .= ' AND o.is_come = 1';

        }

        elseif($status == 3)

        {

            $where .= ' AND o.doctor_time > 0';

        }



        if(!empty($type_id))

        {

            $where .= " AND o.type_id = $type_id";

        }



        if($wu == 1)

        {

            $w_start = strtotime($start . ' 00:00:00') - 25200;

            $w_end = strtotime($end . ' 17:00:00');

        }

        else

        {

            $w_start = strtotime($start . ' 00:00:00');

            $w_end = strtotime($end . ' 23:59:59');

        }



        /* 时间条件 */

        if($order_type == 1)

        {

            $where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

            $orderby .= ',o.order_addtime DESC ';

        }

        elseif($order_type == 2)

        {

            $where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

            $orderby .= ',o.order_time DESC ';

        }

        elseif($order_type == 3)

        {

            $where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

            $orderby .= ',o.come_time DESC ';

        }

        elseif($order_type == 4)

        {

            $where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

            $orderby .= ',o.doctor_time DESC ';

        }



        /* 当输入患者的信息时，其他的搜索条件都不需要了 */

        if(!empty($patient_name))

        {

            $where = " p.pat_name = '". $patient_name . "'";

            $data['p_n']      = $patient_name;

            $data['p_p']      = "";

            $data['o_o']      = "";



            if(!empty($_COOKIE["l_hos_id"]))

            {

                $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

            }

            if(!empty($_COOKIE["l_keshi_id"]))

            {

                $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

            }

        }



        if(!empty($patient_phone))

        {

            $where = " p.pat_phone = '". $patient_phone . "'";

            $data['p_n']      = "";

            $data['p_p']      = $patient_phone;

            $data['o_o']      = "";



            if(!empty($_COOKIE["l_hos_id"]))

            {

                $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

            }

            if(!empty($_COOKIE["l_keshi_id"]))

            {

                $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

            }

        }



        if(!empty($order_no))

        {

            $where = " o.order_no = '" . $order_no . "'";

            $data['p_n']      = "";

            $data['p_p']      = "";

            $data['o_o']      = $order_no;



            if(!empty($_COOKIE["l_hos_id"]))

            {

                $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

            }

            if(!empty($_COOKIE["l_keshi_id"]))

            {

                $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

            }

        }

        if($orderby == '')

        {

            $orderby = ' ORDER BY o.order_id DESC ';

        }

        else

        {

            $orderby = substr($orderby, 1);

            $orderby = ' ORDER BY ' . $orderby . ", o.order_id DESC";

        }


        $order_list = $this->model->order_list($where, 0, 10000, $orderby);

        $order_id_arr = array();

        foreach($order_list as $key => $val)

        {
            $order_id_arr[] = $key;

        }

        $area = $this->model->area();

        // 查询获取备注信息

        $sql = "SELECT * FROM " . $this->common->table('order_remark') . " WHERE order_id IN (" . implode(",", $order_id_arr) . ") ORDER BY mark_id DESC";

        $row = $this->common->getAll($sql);

        foreach($row as $val)

        {

            if(empty($order_list[$val['order_id']]['max_time'])){

                $order_list[$val['order_id']]['max_time'] = $val['mark_time'];

            }

            if($val['mark_type'] == 3){

                $order_list[$val['order_id']]['back'] = isset($order_list[$val['order_id']]['back'])? $order_list[$val['order_id']]['back']:'';

                $order_list[$val['order_id']]['back'] .= $val['mark_content'] . "[" . $val['admin_name'] . " @" . date("m-d H:i", $val['mark_time']) . "]";

             }else{

                $order_list[$val['order_id']]['mark'] = isset($order_list[$val['order_id']]['mark'])? $order_list[$val['order_id']]['mark']:'';

                $order_list[$val['order_id']]['mark'] .= $val['mark_content'] . "[" . $val['admin_name'] . " @" . date("m-d H:i", $val['mark_time']) . "]";

             }



        }

        // 查询获取回访记录信息
        $sql = "SELECT * FROM " . $this->common->table('ask_content') . " WHERE order_id IN (" . implode(",", $order_id_arr) . ")";
        $row = $this->common->getAll($sql);
        foreach($row as $val)
        {
            $order_list[$val['order_id']]['con_content'] = isset($val['con_content'])? $val['con_content']:'';
        }



        //标注是否留联转入

        $sql = "select o.order_id from " . $this->common->table("order_liulian") . " as l," . $this->common->table("order") . " as o where o.order_id in(" . implode(",", $order_id_arr) . ") and o.order_no = l.order_no_yy";
        $order_from_liulian = $this->common->getAll($sql);

        foreach ($order_from_liulian as $val) {
            $order_list[$val['order_id']]['order_from_liulian'] = '（留联）';
        }


        //查询来源网址
        $laiyuanwedata  =  $this->common->getAll("SELECT form,guanjianzi,order_id FROM " . $this->common->table("order_laiyuanweb") . " WHERE order_id IN (" . implode(",", $order_id_arr) . ")");


        // 清空输出缓冲区

        //ob_clean();

        // 载入PHPExcel类库

        $this->load->library('PHPExcel');

        $this->load->library('PHPExcel/IOFactory');

        // 创建PHPExcel对象

        $objPHPExcel = new PHPExcel();

        // 设置excel文件属性描述

        $objPHPExcel->getProperties()

                    ->setTitle(time())

                    ->setDescription(time());

        // 设置当前工作表

        $objPHPExcel->setActiveSheetIndex(0);

        // 设置表头
        $fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '类型','联系电话', '预约内容', '预约病种', '预约方式','方式子类', '地区', '预约性质', '预约人', '备注', '回访记录', '是否3天未回访登记(是/否)', '来源网址','微信','QQ','关键字','患者时间段');//


        // 列编号从0开始，行编号从1开始

        $col = 0;

        $row = 1;

        foreach($fields as $field)

        {

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);

            $col++;

        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);

        // 从第二行开始输出数据内容

        $row = 2;

        $sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];

        $rank_type = $this->common->getRow($sql);

        if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
            $l_admin_action  = array("179");
        }else{
            $l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
        }



        foreach($order_list as $val)

        {

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val["is_come"] == 1)? (!empty($val["come_time"])? date("Y-m-d H:i", $val["come_time"]):''):'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no"]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["doctor_name"]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_name"]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_age"]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val["is_first"]==1?"初诊":"复诊");

            //更加查看电话号码的权限 过来电话
            // if(!in_array('179',$l_admin_action)){
            //     $val['pat_phone']  =  $val['pat_phone'][0].$val['pat_phone'][1].$val['pat_phone'][2].'*****';
            //     $val['pat_phone1']  =  $val['pat_phone1'][0].$val['pat_phone1'][1].$val['pat_phone1'][2].'*****';
            // }

            if($_COOKIE['l_admin_action'] != 'all' && !in_array($val['hos_id'], array(38,48,52,9,16,18)) && !in_array($_COOKIE['l_admin_id'], array(2522))){//肛肠腋臭除和指定admin_id

            	if (!empty($val['pat_phone'])) {
            		$start_str = substr($val['pat_phone'],0,3);
            		$end_str = substr($val['pat_phone'],-4);
            		$val['pat_phone']  =  $start_str.'****'.$end_str;
            	}
            	if (!empty($val['pat_phone1'])) {
            		$start_str = substr($val['pat_phone1'],0,3);
            		$end_str = substr($val['pat_phone1'],-4);
            		$val['pat_phone1']  =  $start_str.'****'.$end_str;
            	}
            }

            if (!empty($val['pat_phone1'])) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val["pat_phone"].'/'.$val['pat_phone1']);
            } else {
            	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val["pat_phone"]);
            }

            // if($rank_type['rank_type']==2||$rank_type['rank_type']==3||$_COOKIE['l_admin_action'] == 'all'||$_COOKIE['l_admin_id']=129){

            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $val["pat_phone"]);

            // }else{

            //     $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, substr_replace($val["pat_phone"],'****',7,4));

            // }

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, (isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($jibing_list[$val["jb_id"]])? $jibing_list[$val["jb_id"]]['jb_name']:''));

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_code']:'');

            $from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name'].$val["order_from_liulian"]:'';

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $from_name);

            $from_name_son = isset($from_list_son[$val["from_id"]])? $from_list_son[$val["from_id"]]['from_name']:'';

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $from_name_son);

            $a = '';

            if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}

            if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}

            if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $a);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val["admin_name"]);


            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row, isset($val['mark'])? $val['mark']:'');

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row, isset($val['back'])? $val['back']:'');

            //判断是否超过3天未回访的  未到诊 未来院的

            if(empty($val['come_time']) || empty($val['is_come'])){

                if((strtotime($val['order_time'].' 23:59:59') - 3*24*60*60) > $val['max_time'] ){

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row, '是');

                }else{

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,"否");

                }

            }else{

               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,"否");

            }


            $con_content_str = '';
            $con_content = explode('进入:<a href="',$val['con_content']);
            if(count($con_content) > 1){
                $con_content = explode('">',$con_content[1]);
                if(count($con_content) > 1){
                    $con_content_str = $con_content[0];
                }
            }

            $laiyuanweb = '';
            $guanjianzi = '';
            if(count($laiyuanwedata) > 0){
                foreach ($laiyuanwedata as $temp){
                    if(strcmp($temp['order_id'],$val['order_id']) ==0 ){
                        $laiyuanweb = $temp['form'];
                        $guanjianzi = $temp['guanjianzi'];break;
                    }
                }
            }


            if(empty($con_content_str)){
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,$laiyuanweb);
            }else{
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, $row,$con_content_str);
            }


            //更加查看电话号码的权限 过来电话
            if(!in_array('179',$l_admin_action)){
                $val['pat_weixin']  =   '*****';
                $val['pat_qq']  =   '*****';
            }
            if($rank_type['rank_type']==2||$rank_type['rank_type']==3||$_COOKIE['l_admin_action'] == 'all'||$_COOKIE['l_admin_id']=129){

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,$val["pat_weixin"]);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row,$val["pat_qq"]);

            }else{

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, $row,"****");
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, $row,"****");

            }

            //设置为文本
            //$objPHPExcel->getActiveSheet()->getStyle('V')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22, $row,' '.$guanjianzi);

            if ($val["hzsjd"] == 0) {
            	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '');
            } elseif ($val["hzsjd"] == 1) {
            	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '一年以内');
            } elseif ($val["hzsjd"] == 2) {
            	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '一年以上');
            }

            $row++;

        }

        //输出excel文件

        $objPHPExcel->setActiveSheetIndex(0);

        // 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)

        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

        // 设置HTTP头

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');

        header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');

        header('Cache-Control: max-age=0');

        $objWriter->save('php://output');

    }





	public function order_swt()

	{

		$data = array();

		$data = $this->common->config('order_swt');

		$down           = isset($_REQUEST['down'])? intval($_REQUEST['down']):0;

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';

		$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';

		$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):2;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$swt_url        = isset($_REQUEST['swt_url'])? trim($_REQUEST['swt_url']):'';

		$swt_keyword    = isset($_REQUEST['swt_keyword'])? trim($_REQUEST['swt_keyword']):'';

		$swt_type       = isset($_REQUEST['swt_type'])? trim($_REQUEST['swt_type']):'';



		$data['swt_url']     = $swt_url;

		$data['swt_keyword'] = $swt_keyword;

		$data['swt_type']    = $swt_type;

		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['p_n']      = $patient_name;

		$data['p_p']      = $patient_phone;

		$data['o_o']      = $order_no;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;



		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}



		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

		$keshi_arr = array();

		foreach($keshi as $val)

		{

			$keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];

			$keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];

			$keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];

			$keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];

			$keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];

			$keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];

		}

		$from_list = $this->model->from_order_list(-1);

		$par_from_arr = array();

		$from_arr = array();

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

		$type_list = $this->model->type_order_list();

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

		$hos_id_arr = array();

		$keshi_id_arr = array();

		$jibing = read_static_cache('jibing_list');

		$jibing_list = array();

		$jibing_parent = array();

		if(!empty($jibing))

		{

			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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

		}

		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

		}

		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;

		$data['now_page'] = $page;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):200;

		$per_page = empty($per_page)? 200:$per_page;

		$this->load->library('pagination');

		$this->load->helper('page');

		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}





		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}



		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}



		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}



		if(!empty($swt_keyword))

		{

			$where .= " AND s.swt_keyword = '" . $swt_keyword . "'";

		}



		if(!empty($swt_url))

		{

			$where .= " AND s.swt_url = '" . $swt_url . "'";

		}



		if(!empty($swt_type))

		{

            if($swt_type == '未标注')

			{

			$where .= " AND s.swt_type is null";

			}

			else

			{

			$where .= " AND s.swt_type = '" . $swt_type . "'";

			}

		}



		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}



		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 25200;

			$w_end = strtotime($end . ' 17:00:00');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}



		/* 时间条件 */

		if($order_type == 1)

		{

			$where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;

			$orderby .= ',o.order_addtime DESC ';

		}

		elseif($order_type == 2)

		{

			$where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby .= ',o.order_time DESC ';

		}

		elseif($order_type == 3)

		{

			$where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;

			$orderby .= ',o.come_time DESC ';

		}

		elseif($order_type == 4)

		{

			$where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;

			$orderby .= ',o.doctor_time DESC ';

		}

		/* 当输入患者的信息时，其他的搜索条件都不需要了 */

		if(!empty($patient_name))

		{

			$where = " p.pat_name = '". $patient_name . "'";

			$data['p_n']      = $patient_name;

			$data['p_p']      = "";

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($patient_phone))

		{

			$where = " (p.pat_phone = '". $patient_phone . "' OR p.pat_phone1 = '". $patient_phone . "')";

			$data['p_n']      = "";

			$data['p_p']      = $patient_phone;

			$data['o_o']      = "";

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if(!empty($order_no))

		{

			$where = " o.order_no = '" . $order_no . "'";

			$data['p_n']      = "";

			$data['p_p']      = "";

			$data['o_o']      = $order_no;

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}



		if($orderby == '')

		{

			$orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';

		}

		else

		{

			$orderby = substr($orderby, 1);

			$orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";

		}



		$order_count = $this->model->order_swt_count($where);

		$order_count['wei'] = $order_count['count'] - $order_count['come'];

		$config = page_config();

        $config['base_url'] =  '?c=order&m=order_swt&hos_id=' . $_REQUEST['hos_id'] . '&keshi_id=' . $_REQUEST['keshi_id'] . '&p_n=' . $_REQUEST['p_n'] . '&p_p=' . $_REQUEST['p_p'] . '&o_o=' . $_REQUEST['o_o'] . '&f_p_i=' . $_REQUEST['f_p_i'] . '&f_i=' .  $_REQUEST['f_i'] . '&a_i=' .  $_REQUEST['a_i'] . '&s=' .  $_REQUEST['s'] . '&p=' . $per_page . '&t=' .  $_REQUEST['t'] . '&date=' . $_REQUEST['date'] . '&o_t=' .  $_REQUEST['o_t'] . '&wu=' .  $_REQUEST['wu'].'&province=' . $_REQUEST['province'] . '&city=' . $_REQUEST['city']. '&area=' . $_REQUEST['area']. '&wx=' . $_REQUEST['wx']. '&p_jb=' . $_REQUEST['p_jb']. '&jb=' . $_REQUEST['jb'];



		$config['total_rows'] = $order_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;

		$this->pagination->initialize($config);



		$order_list = $this->model->order_swt_list($where, $page, $per_page, $orderby);

		$order_list_down = $this->model->order_swt_list($where, 0, $order_count['count'], $orderby);

		$rank_type = $this->model->rank_type();

		$area = $this->model->area();

		if($down == 1)

		{

			// 清空输出缓冲区

			//ob_clean();

			// 载入PHPExcel类库

			$this->load->library('PHPExcel');

			$this->load->library('PHPExcel/IOFactory');

			// 创建PHPExcel对象

			$objPHPExcel = new PHPExcel();

			// 设置excel文件属性描述

			$objPHPExcel->getProperties()

						->setTitle(time())

						->setDescription(time());

			// 设置当前工作表

			$objPHPExcel->setActiveSheetIndex(0);

			// 设置表头

			$fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式', '地区', '预约性质', '关键词', '轨迹来源', '网址');

			// 列编号从0开始，行编号从1开始

			$col = 0;

			$row = 1;

			foreach($fields as $field)

			{

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);

				$col++;

			}



			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);

			// 从第二行开始输出数据内容

			$row = 2;

			foreach($order_list_down as $val)

			{

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val["is_come"] == 1)? (!empty($val["come_time"])? date("Y-m-d H:i", $val["come_time"]):''):'');

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["doctor_name"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_name"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_age"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val["pat_phone"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (isset($data['jibing'][$val["jb_parent_id"]])? $data['jibing'][$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($data['jibing'][$val["jb_id"]])? $data['jibing'][$val["jb_id"]]['jb_name']:''));

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, isset($data['jibing'][$val["jb_parent_id"]])? $data['jibing'][$val["jb_parent_id"]]['jb_code']:'');

				$from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name']:'';

				if(($from_name == '商务通') || ($from_name == 'QQ') || ($from_name == '美洽') || ($from_name == '百度商桥') || ($from_name == 'PC') || ($from_name == '移动'))

				{

					$from_name = '网络';

				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $from_name);

				$a = '';

				if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}

				if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}

				if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $a);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, $val["swt_keyword"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val["swt_type"]);

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row, $val["swt_url"]);

				$row++;

			}

			//输出excel文件

			$objPHPExcel->setActiveSheetIndex(0);

			// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)

			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');

			// 设置HTTP头

			header('Content-Type: application/vnd.ms-excel; charset=utf-8');

			header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');

			header('Cache-Control: max-age=0');

			$objWriter->save('php://output');

		}

		else

		{

			$data['down_url'] = '?c=order&m=order_swt&down=1&hos_id=' . $hos_id . '&keshi_id=' . $keshi_id . '&p_n=' . $patient_name . '&p_p=' . $patient_phone . '&o_o=' . $order_no . '&f_p_i=' . $from_parent_id . '&f_i=' . $from_id . '&a_i=' . $asker_name . '&s=' . $status . '&p=' . $per_page . '&t=' . $order_type . '&date=' . $data['start'] . '+-+' . $data['end'] . '&o_t=' . $type_id . '&wu=' . $wu; // 导出数据的URL

			$data['rank_type'] = $rank_type;

			$data['page'] = $this->pagination->create_links();

			$data['per_page'] = $per_page;

			$data['order_list'] = $order_list;

			$data['order_count'] = $order_count;

			$data['type_list'] = $type_list;

			$data['from_list'] = $from_list;

			$data['hospital'] = $hospital;

			$data['top'] = $this->load->view('top', $data, true);

			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

			$this->load->view('order_swt', $data);

		}

	}



	public function order_swt_update()

	{

		$type        = $_REQUEST['type'];

		$order_id    = $_REQUEST['order_id'];

		$value       = $_REQUEST['value'];



		$arr = array('order_id' => $order_id,

			         "$type" => $value);



		$is_havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order_swt') . " WHERE order_id = $order_id");

		if($is_havd)

		{

			$this->db->update($this->common->table('order_swt'), $arr, array('order_id' => $order_id));

		}

		else

		{

			$this->db->insert($this->common->table('order_swt'), $arr);

		}

		echo 1;

	}



	public function ask_data_input()

	{

		$data = array();

		$data = $this->common->config('ask_data_input');

		$site_list = $this->common->getAll("SELECT site_id, site_mobile_domain, site_domain FROM " . $this->common->table('site') . " ORDER BY site_order ASC, site_id DESC");

		$data['site_list'] = $site_list;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('ask_data_input', $data);

	}



	public function ask_data_update()

	{

		$this->common->config('ask_data_input');

		$data_type = intval($_REQUEST['data_type']);

		$data_site = intval($_REQUEST['data_site']);

		$data_time = trim($_REQUEST['data_time']);



		if(empty($data_type))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		if(($data_type == 2) && empty($data_site))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		$config['upload_path'] = 'static/upload/';

		$config['allowed_types'] = 'xls|xlsx|csv';

		$config['max_size'] = '2000';

		$zui = explode(".", $_FILES['file']['name']);

		$zui = $zui[sizeof($zui)- 1];

		$config['file_name'] = time() . "." . $zui;

		$this->load->library('upload', $config);

		$this->upload->do_upload('file');

		$data =  $this->upload->data();

		$file = $data['full_path'];



		if($zui == 'csv')

		{

			$fp = fopen($file,'r');

			$i = 1;

			while ($val = fgetcsv($fp))

			{

				if($i > 1)

				{

					if($data_type == 2)

					{

						$val[3] = mb_convert_encoding($val[3],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

						$val[11] = mb_convert_encoding($val[11],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

						$val[15] = mb_convert_encoding($val[15],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

						$val[17] = isset($val[17])? mb_convert_encoding($val[17],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';

						$val[18] = isset($val[18])? mb_convert_encoding($val[18],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';

						$val[21] = mb_convert_encoding($val[21],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

						$val[23] = isset($val[23])? mb_convert_encoding($val[23],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';

						$val[24] = isset($val[24])? mb_convert_encoding($val[24],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK')):'';

						$val[26] = trim($val[26]);

						if($val[15] == '访客发起')

						{

							$qiao_start_type = 1;

						}

						else

						{

							$qiao_start_type = 2;

						}

						$qiao_ask_time = $val[2];

						$site_addtime = $data_time;

						$admin_id = $this->common->getOne("SELECT admin_id FROM " . $this->common->table('asker') . " WHERE asker_qiao_name = '" . $val[11] . "'");

						if(!empty($admin_id) && $val[12] >= 3)

						{

							$this->db->query("UPDATE " . $this->common->table('asker') . " SET asker_ask = asker_ask + 1 WHERE admin_id = $admin_id");

							$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_ask	= site_ask + 1 WHERE site_id = $data_site AND site_addtime = " . strtotime($site_addtime));

						}



						$key_id = 0;

						$plan_id = 0;

						$search_key = 0;

						$qiao_from_type = $val[21];

						if($qiao_from_type == '直接访问')

						{

							$from_site_id = 1;

							$from_type_id = 3;

						}

						elseif($qiao_from_type == '百度搜索推广')

						{

							$from_site_id = 4;

							$from_type_id = 4;

							if(!empty($val[23]))

							{

								$key_info = $this->common->getRow("SELECT key_id, plan_id FROM " . $this->common->table('bd_cpc_day_' . $data_site) . " WHERE key_word = '" . $val[23] . "'");

								$key_id = $key_info['key_id'];

								$plan_id = $key_info['plan_id'];

							}

							if(!empty($val[24]))

							{

								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[24] . "'");

								if(!$search_key)

								{

									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[24]));

									$search_key = $this->db->insert_id();

								}

							}

						}

						elseif($qiao_from_type == '百度自然搜索')

						{

							$from_site_id = 6;

							$from_type_id = 2;

							if(!empty($val[23]))

							{

								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");

								if(!$search_key)

								{

									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[23]));

									$search_key = $this->db->insert_id();

								}

							}

						}

						elseif($qiao_from_type == '搜狗')

						{

							$from_site_id = 12;

							$from_type_id = 2;

							if(!empty($val[23]))

							{

								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");

								if(!$search_key)

								{

									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[23]));

									$search_key = $this->db->insert_id();

								}

							}

						}

						else

						{

							if(strstr($qiao_from_type, '360.cn'))

							{

								$from_site_id = 12;

								$from_type_id = 2;

							}

							else

							{

								$from_site_id = 0;

								$from_type_id = 0;

							}

							if(!empty($val[23]))

							{

								$search_key = $this->common->getOne("SELECT key_id FROM " . $this->common->table('keywords') . " WHERE key_keyword = '" . $val[23] . "'");

								if(!$search_key)

								{

									$this->db->insert($this->common->table('keywords'), array('key_keyword' => $val[24]));

									$search_key = $this->db->insert_id();

								}

							}

						}

						$sys_type = 1;

						if(strstr($val[25], 'iPhone')){

							$sys_type = 2;

						}elseif(strstr($val[25], 'Android'))

						{

							$sys_type = 2;

						}elseif(strstr($val[25], 'Linux'))

						{

							$sys_type = 2;

						}



						/* 根据IP获取地区 */

						$area_info = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $val[27]);

						$area_info = json_decode($area_info);

						$data_province = $area_info->data->region;

						$data_city = $area_info->data->city;

						$arr = array('admin_id' => $admin_id,

									 'site_id' => $data_site,

									 'from_site_id' => $from_site_id,

									 'from_type_id' => $from_type_id,

									 'plan_id' => $plan_id,

									 'key_id' => $key_id,

									 'search_key' => $search_key,

									 'ask_from_id' => 3,

									 'data_time' => strtotime($qiao_ask_time),

									 'data_year' => date("Y", strtotime($qiao_ask_time)),

									 'data_month' => date("m", strtotime($qiao_ask_time)),

									 'data_day' => date("d", strtotime($qiao_ask_time)),

									 'data_week' => date("w", strtotime($qiao_ask_time)),

									 'data_hour' => date("H", strtotime($qiao_ask_time)),

									 'ask_from_value' => $val[26],

									 'data_type' => $sys_type);



						/* 查看是否预约 */

						$order_id = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE from_parent_id = 3 AND from_value = '" . $val[26] . "' AND order_addtime <= '" . strtotime($data_time . " 23:59:59") . "' AND order_addtime >= '" . strtotime($data_time . " 00:00:00") . "'");

						if($order_id)

						{

							$arr['is_order'] = 1;

							$arr['order_id'] = $order_id;

							$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_order = site_order + 1 WHERE site_id = $data_site AND site_addtime = " . strtotime($site_addtime));

						}



						$this->db->insert($this->common->table('ask_data'), $arr);

						$data_id = $this->db->insert_id();



						$arr = array('data_id' => $data_id,

									 'data_ip' => $val[27],

									 'data_province' => $data_province,

									 'data_city' => $data_city,

									 'data_viewer_words' => $val[12],

									 'data_asker_words' => $val[13],

									 'data_start_type' => $qiao_start_type);

						$this->db->insert($this->common->table('ask_data_info'), $arr);



						$arr = array('data_id' => $data_id,

									 'qiao_ask_time' => strtotime($qiao_ask_time),

									 'qiao_view_times' => $val[4],

									 'qiao_view_pages' => $val[5],

									 'qiao_this_pages' => $val[6],

									 'qiao_asker' => $val[11],

									 'admin_id' => $admin_id,

									 'qiao_viewer_words' => $val[12],

									 'qiao_asker_words' => $val[13],

									 'qiao_start_type' => $qiao_start_type,

									 'qiao_viewer_name' => $val[17],

									 'qiao_viewer_others' => $val[18],

									 'qiao_from_page' => isset($val[20])? $val[20]:'',

									 'qiao_from_type' => $val[21],

									 'qiao_keyword' => $val[23],

									 'qiao_search_keyword' => $val[24],

									 'qiao_only_id' => $val[26],

									 'qiao_ip' => $val[27]);



						$this->db->insert($this->common->table('ask_data_qiao'), $arr);

					}

					elseif($data_type == 1)

					{

					}

				}

				$i ++;

			}

			fclose($fp);



		}

		elseif($zui == 'xls')

		{

			$this->load->library('excel');

			$this->excel->setOutputEncoding('CP936');

			$this->excel->_encoderFunction = 'mb_convert_encoding';

			$this->excel->read($file);

		}

		$file_zui = explode(".", $file);

		if(isset($file_zui[1]))

		{

			unlink($file);

		}

		$links[0] = array('href' => '?c=order&m=ask_data_input', 'text' => $this->lang->line('go_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function dao_false()

	{

		$data = array();

		$data = $this->common->config('dao_false');

		$false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		$data['false'] = $false;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('dao_false', $data);

	}



	public function dao_false_update()

	{

		$this->common->config('dao_false');

		$form_action  = $_REQUEST['form_action'];

		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;

		$false_name   = isset($_REQUEST['false_name'])? trim($_REQUEST['false_name']):'';

		$false_order  = isset($_REQUEST['false_order'])? intval($_REQUEST['false_order']):0;

		if(empty($false_name))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}

		$arr = array('false_name' => $false_name,

					 'false_order' => $false_order);



		if($form_action == 'update')

		{

			if(empty($false_id))

			{

				$this->common->msg($this->lang->line('no_empty'), 1);

			}

			$this->db->update($this->common->table('dao_false'), $arr, array('false_id' => $false_id));

		}

		else

		{

			$this->db->insert($this->common->table('dao_false'), $arr);

		}



		$links[0] = array('href' => '?c=order&m=dao_false', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function dao_false_ajax()

	{

		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;

		if(empty($false_id))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		$info = $this->common->getRow("SELECT * FROM " . $this->common->table('dao_false') . " WHERE false_id = $false_id");

		echo json_encode($info);

	}



	public function dao_false_del()

	{

		$false_id     = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;

		if(empty($false_id))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}



		$this->db->delete($this->common->table('dao_false'), array('false_id' => $false_id));

		$links[0] = array('href' => '?c=order&m=dao_false', 'text' => $this->lang->line('list_back'));

		$this->common->msg($this->lang->line('success'), 0, $links);

	}



	public function order_info_ajax()

	{

		$order_id     = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		if(empty($order_id))

		{

			$this->common->msg($this->lang->line('no_empty'), 1);

		}

		$info = $this->model->order_info($order_id);

		if($info['pat_sex'] == 1)

		{

			$info['sex'] = $this->lang->line('man');

		}

		else

		{

			$info['sex'] = $this->lang->line('woman');

		}

		$info['addtime'] = date("Y-m-d H:i", $info['order_addtime']);

		if(empty($info['order_time']))

		{

			$info['ordertime'] = $info['order_null_time'];

		}



		else



		{



			$info['ordertime'] = date("Y-m-d", $info['order_time']);



			if(!empty($info['order_time_duan']))



			{



				$dt = $this->lang->line('day_time');



				$info['ordertime'] .= $dt[$info['order_time_duan']];



			}



		}



		echo json_encode($info);



	}





	public function order_out_ajax()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$sql = "select rank_type from ".$this->common->table('rank')." where rank_id = ".$_COOKIE['l_rank_id'];

		$type = $this->common->getOne($sql);

		if($_COOKIE['l_rank_id'] == 1 || $type == 2){



			$sql = 'select * from '.$this->common->table('order_out').' where order_id = '.$order_id;



			$res = $this->common->getRow($sql);



			if($res){



				$this->db->delete($this->common->table('order_out'),array('order_id' => $order_id));



				$str = '<blockquote><p><font color=#FF0000>（取消用户不来院的提示信息）</font></p><small><a>' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

				$tag = 1;

				/**
				 * 推送数据到数据中心
				 * */
				// $this->send_order_shujuzhognxin($order_id);

				// $send_array = array();
				// $send_data = array();
				// $send_data['operation_type'] = 'quxiao_or_huifu';
				// $apisql = 'select ireport_order_id from '.$this->common->table('order')." where order_id  = '".$order_id."'";
				// $ireport_order_data = $this->common->getAll($apisql);
				// $send_data['order_id'] = $order_id;
				// $send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];
				// $send_data['order_out'] =  0;
				// $send_array[] =$send_data;
    //             //$this->sycn_order_data_to_ireport($send_array);
    //             //剔除仁爱数据推送
    //             $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
    //             if(!$order_hos_id){
    //                 $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
    //             }

    //             if (!in_array('1',$order_hos_id)) {
    //                 $this->sycn_order_data_to_ireport($send_array);
    //             }
			}else{



				$this->db->insert($this->common->table('order_out'), array('order_id' => $order_id,  'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() , 'type' => 1));



				$str = '<blockquote><p><font color=#FF0000>（该预约用户确认不来院）</font></p><small><a>' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';

				$tag = 2;

				/**
				 * 推送数据到数据中心
				 * */
				// $this->send_order_shujuzhognxin($order_id);

				// $send_array = array();
				// $send_data = array();
				// $send_data['operation_type'] = 'quxiao_or_huifu';
				// $apisql = 'select ireport_order_id from '.$this->common->table('order')." where order_id  = '".$order_id."'";
				// $ireport_order_data = $this->common->getAll($apisql);
				// $send_data['order_id'] = $order_id;
				// $send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];
				// $send_data['order_out'] =  1;
				// $send_array[] = $send_data;
    //             //$this->sycn_order_data_to_ireport($send_array);
    //             //剔除仁爱数据推送
    //             $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
    //             if(!$order_hos_id){
    //                 $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
    //             }

    //             if (!in_array('1',$order_hos_id)) {
    //                 $this->sycn_order_data_to_ireport($send_array);
    //             }

			}

			 print_r(json_encode(array('str'=>$str,'tag'=>$tag)));

		}



	}



	public function user_out_ajax()

	{

		$order_id = $this->input->post('order_id',true);

		if(!empty($order_id)&&is_numeric($order_id)){

			$sql = 'select * from '.$this->common->table('order_out').' where order_id = '.$order_id;



			$res = $this->common->getRow($sql);



			if(empty($res)){

				$user = $this->common->getRow('select p.pat_id,p.pat_name from '.$this->common->table('patient').' p left join '.$this->common->table('order').' o on p.pat_id = o.pat_id where o.order_id = '.$order_id);

				$this->db->insert($this->common->table('order_out'), array('order_id' => $order_id,  'admin_id' => $user['pat_id'], 'admin_name' => $user['pat_name'], 'mark_time' => time() , 'type' => 1));

			}

		}

	}



	public function user_time_ajax()

	{

		$order_id = $this->input->post('order_id',true);

		$order_time = $this->input->post('order_time',true);

		if(!empty($order_id)&&is_numeric($order_id)){

			$order_time = strtotime($order_time);

			$res = $this->db->update($this->common->table('order'),array('order_time'=>$order_time),array('order_id'=>$order_id));

			if($res){



				$this->db->delete($this->common->table('order_out'),array('order_id' => $order_id));



				echo 1;

			}

		}

	}

	public function huifang_list()

	{

		$data = array();

		$data = $this->common->config('huifang_list');

		// 是否是通过姓名查找

		$name = isset($_REQUEST['name']) ? trim($_REQUEST['name']) : '';

		$a_i = isset($_REQUEST['a_i']) ? trim($_REQUEST['a_i']) : '';

		$hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;

		$type_id = isset($_REQUEST['type_id']) ? intval($_REQUEST['type_id']) : 0;

		if(empty($type_id)||$type_id == 1){

			$type = 3;

		}else{

			$type = 1;

		}

		$hos = $this->common->get_hosname();

		$data['hospital'] = $hos;

		if(empty($hos_id)){

			foreach($hos as $val)

			{

				$hos_arr [] = $val['hos_id'];

			}

			$hos_str = implode(',',$hos_arr);

		}else{

			$hos_str = $hos_id;

		}



		$data['huifang'] = $this->set_huifang();



		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;



		$data['now_page'] = $page;



		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):10;



		$per_page = empty($per_page)? 10:$per_page;



		$this->load->library('pagination');



		$this->load->helper('page');



		$config = page_config();



		$config['per_page'] = $per_page;



		$config['uri_segment'] = 10;



		$config['num_links'] = 5;



		//处理搜索条件

		$huifang_d = isset($_REQUEST['huifang_d']) ? intval($_REQUEST['huifang_d']) : 0;

		$zt_id = isset($_REQUEST['zt_id']) ? intval($_REQUEST['zt_id']) : 0;

		$lx_id = isset($_REQUEST['lx_id']) ? intval($_REQUEST['lx_id']) : 0;

		$jg_id = isset($_REQUEST['jg_id']) ? intval($_REQUEST['jg_id']) : 0;

		$ls_id = isset($_REQUEST['ls_id']) ? intval($_REQUEST['ls_id']) : 0;

		$date  = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : '';

		$query = array();

		$where = 1;

		if(!empty($huifang_d) && empty($name)){

			$time = strtotime(date('Y-m-d'));

			if(1 == $huifang_d){

				$query[] = 'huifang_d=1';

				$start = $time-24*3600*7;

				$where .= ' and r.mark_time = '.$time;

			}else if(2 == $huifang_d){

				$query[] = 'huifang_d=2';

				$start = $time-24*3600;

				$where .= ' and r.mark_time = '.$start;

			}else if(3 == $huifang_d){

				$query[] = 'huifang_d=3';

				$start = $time+24*3600;

				$where .= ' and r.mark_time = '.$start;

			}

			$start = date("Y-m-d", time());



			$end = date("Y-m-d", time());



			$data['start_date'] = $start;



			$data['end_date'] = $end;

		}else{

			if(!empty($date))



			{

				$query[] = 'date='.$date;

				$data['date'] = $date;

				$date = explode(" - ", $date);



				$start = $date[0];



				$end = $date[1];


				$data['start_date'] = $start;

				$data['end_date'] = $end;


				 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
					  $start = date("Y年m月d日");
					  $end = $start;
				   }



				$start = str_replace(array("年", "月", "日"), "-", $start);



				$end = str_replace(array("年", "月", "日"), "-", $end);



				$start = substr($start, 0, -1);



				$end = substr($end, 0, -1);



				$w_start = strtotime($start . ' 00:00:00');



				$w_end = strtotime($end . ' 23:59:59');



				$where .= ' and r.mark_time > '.$w_start.' and r.mark_time < '.$w_end;

			}else{

				$start = date("Y年m月d日", time());

				$end = date("Y年m月d日", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$w_start = strtotime($start . ' 00:00:00');
				$w_end = strtotime($end . ' 23:59:59');

				$where .= ' and r.mark_time > '.$w_start.' and r.mark_time < '.$w_end;

			}



		}

		if(!empty($zt_id)){

			$query[] = 'zt_id='.$zt_id;

			$where .= ' and zt_id = '.$zt_id;

		}

		if(!empty($lx_id)){

			$query[] = 'lx_id='.$lx_id;

			$where .= ' and lx_id = '.$lx_id;

		}

		if(!empty($jg_id)){

			$query[] = 'jg_id='.$jg_id;

			$where .= ' and jg_id = '.$jg_id;

		}

		if(!empty($ls_id)){

			$query[] = 'ls_id='.$ls_id;

			$where .= ' and ls_id = '.$ls_id;

		}

		if(empty($name)){

			if(!empty($a_i)){

				$query[] = 'a_i='.$a_i;

				$where .= ' and r.admin_name = "'.$a_i.'"';

			}

			// 统计回访条数

			$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r

					inner join '.$this->common->table('order').' o on o.order_id = r.order_id

					inner join '.$this->common->table('remark_extend').' e on r.mark_id = e.mark_id

					where r.mark_type = '.$type.' and '.$where.' and o.hos_id in ('.$hos_str.')';

			$count = $this->common->getOne($sql);

			if($count>0){

			$config['base_url'] = '?c=order&m=huifang_list&p=' . $per_page .'&'.implode('&',$query);



			$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.order_no,o.pat_id,o.is_come,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx

					from '.$this->common->table('order_remark').' r

					left join '.$this->common->table('order').' o on o.order_id = r.order_id

					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id

					where r.mark_type = '.$type.' and '.$where.' and o.hos_id in ('.$hos_str.') order by r.order_id desc LIMIT '.$page.', '.$per_page;

			$huifang_info = $this->common->getAll($sql_list);

			// 统计病人信息

			foreach($huifang_info as $val)

			{

				$pat_arr [] = $val['pat_id'];

			}

			$pat_str = implode(',',$pat_arr);

			$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_id in ('.$pat_str.')';

			$pat_list = $this->common->getAll($pat_info);

			$pat_cache = array();

			foreach($pat_list as $val){

				$pat_cache[$val['pat_id']] = $val;

			}

			}else{

				$pat_cache = array();

				$huifang_info = array();

			}

		}else{

			// 查找符合姓名的患者信息

			if(preg_match("/^[A-Za-z]*$/",$name)){

				$order_no = strtoupper($name);

				$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r

					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id

					left join '.$this->common->table('order').' o on o.order_id = r.order_id

					where mark_type = '.$type.' and o.order_no = "'.$name.'" and '.$where.' and hos_id in ('.$hos_str.')';

				$count = $this->common->getOne($sql);

				if($count>0){

					$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.pat_id,o.order_no,o.order_addtime,o.pat_id,o.is_come,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx

					from '.$this->common->table('order_remark').' r

					left join '.$this->common->table('order').' o on o.order_id = r.order_id

					left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id

					where r.mark_type = '.$type.' and '.$where.' and o.order_no = "'.$name.'"';

					$huifang_info = $this->common->getAll($sql_list);

					$pat_id = $huifang_info[0]['pat_id'];

					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_id = '.$pat_id;

					$pat_list = $this->common->getAll($pat_info);

					$pat_cache = array();

					foreach($pat_list as $val){

						$pat_cache[$val['pat_id']] = $val;

					}

				}else{

					$pat_cache = array();

					$huifang_info = array();

				}

			}else{

				if(intval($name)==0){

					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_name like "%'.$name.'%"';

				}else{

					$pat_info = 'select pat_id,pat_name,pat_phone from '.$this->common->table('patient').' where pat_phone = "'.$name.'"';

				}

				$pat_list = $this->common->getAll($pat_info);

				$pat_cache = array();

				$pat_id_arr = array();

				foreach($pat_list as $val){

					$pat_id_arr[] = $val['pat_id'];

					$pat_cache[$val['pat_id']] = $val;

				}

				$pat_id_str = implode(',',$pat_id_arr);

				// 查找符合条件的订单

				$sql_order = 'select order_id from '.$this->common->table('order').' where hos_id in ('.$hos_str.') and pat_id in ('.$pat_id_str.')';

				$arr = $this->common->getAll($sql_order);

				foreach($arr as $val){

					$order_arr[] = $val['order_id'];

				}

				$order_str = implode(',',$order_arr);

				// 统计回访信息

				$sql = 'select count(r.mark_id) from '.$this->common->table('order_remark').' r

						left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id

						where mark_type = '.$type.' and '.$where.' and order_id in ('.$order_str.')';

				$count = $this->common->getOne($sql);



				$config['base_url'] = '?c=order&m=huifang_list&p=' . $per_page . '&name='.$name .'&'.implode($query);







				$sql_list = 'select r.mark_id,r.order_id,r.mark_content,r.admin_name,r.mark_time,o.order_id,o.order_no,o.order_addtime,o.pat_id,e.zt_id,e.lx_id,e.jg_id,e.ls_id,e.date_lx

						from '.$this->common->table('order_remark').' r

						left join '.$this->common->table('order').' o on o.order_id = r.order_id

						left join '.$this->common->table('remark_extend').' e on e.mark_id = r.mark_id

						where r.mark_type = '.$type.' and '.$where.' and o.order_id in ('.$order_str.') order by r.order_id desc LIMIT '.$page.', '.$per_page;

				$huifang_info = $this->common->getAll($sql_list);

			}

			$data['name'] = $name;

		}

		// 根据订单id归类

		$res = array();

		foreach($huifang_info as $val){

			$res[$val['order_id']][] = $val;

		}



		$config['total_rows'] = $count;

		$this->pagination->initialize($config);

		$data['huifang_list'] = $res;

		$data['pat_cache'] = $pat_cache;

		$data['page'] = $this->pagination->create_links();

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('huifang_list', $data);

	}

	public function order_update_ajax()

	{



		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;


		$type = $_REQUEST['type'];



		$remark = isset($_REQUEST['remark'])? trim($_REQUEST['remark']):'';

		$jzkh = isset($_REQUEST['jzkh']) ? trim($_REQUEST['jzkh']) : '';

		$dao_type = isset($_REQUEST['dao_type']) ? intval(trim($_REQUEST['dao_type'])) : 1;

		if($type == 'visit')



		{



			$false_id = isset($_REQUEST['false_id'])? intval($_REQUEST['false_id']):0;

			if(empty($_COOKIE['l_admin_name'])){
				$_COOKIE['l_admin_name'] = '';
			}
			$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 3, 'type_id' => $false_id));



			$mark_id = $this->db->insert_id();



			$zt_id = isset($_REQUEST['zt_id'])? intval($_REQUEST['zt_id']):0;

			$lx_id = isset($_REQUEST['lx_id'])? intval($_REQUEST['lx_id']):0;

			$jg_id = isset($_REQUEST['jg_id'])? intval($_REQUEST['jg_id']):0;

			$ls_id = isset($_REQUEST['ls_id'])? intval($_REQUEST['ls_id']):0;

			$date_lx = isset($_REQUEST['date_lx'])? trim($_REQUEST['date_lx']):'';

			$datehour = isset($_REQUEST['datehour'])? trim($_REQUEST['datehour']):'';

			if(!empty($datehour)){

				$date_lx .= ' '.$datehour;

			}



			$remark_extend = array(

				'mark_id' 	=> $mark_id,

				'zt_id'		=> $zt_id,

				'lx_id'		=> $lx_id,

				'jg_id'		=> $jg_id,

				'ls_id'		=> $ls_id,

				'date_lx'	=> strtotime($date_lx),

			);



			$this->db->insert($this->common->table('remark_extend'), $remark_extend);



			$str = '<blockquote><p>' . $remark;







			if(!empty($false_id))



			{



				$false_str = $this->common->getOne("SELECT false_name FROM " . $this->common->table('dao_false') . " WHERE false_id = $false_id");



				$str .= '<font color=#FF0000>（未到诊原因：' . $false_str . '）</font>';



			}







			$str .= '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';


			/***
			 * 推送数据到数据中心
			*/
			//$this->send_order_shujuzhognxin($order_id);

			/***
			 * 推送数据到数据中心
			 */
			//$this->send_huifang_to_shujuzhongxin($order_id);

		}



		elseif($type == 'dao')



		{



			$doctor_name = $_REQUEST['doctor_name'];



			$this->db->update($this->common->table('order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));

                       //新增操作公海到诊代码开始

                        if($this->db->query("select * from  ".$this->common->table('gonghai_order')." where order_id=".$order_id)){

                           $this->db->update($this->common->table('gonghai_order'), array('come_time' => time(), 'doctor_name' => $doctor_name, 'is_come' => 1), array('order_id' => $order_id));

                        }

                        //新增操作公海到诊代码结束



			$str = array('come_time' => date("Y-m-d H:i"));



			$info = $this->model->order_info($order_id);

			if($info['hos_id']==1){

				$username = $info['pat_phone'];

				$this->group_change($username);

			}



			$this->db->update($this->common->table('ask_data'), array('is_dao' => 1), array('order_id' => $order_id));



			$site_addtime = $this->common->getOne("SELECT order_addtime FROM " . $this->common->table('order') . " WHERE order_id = $order_id");



			$site_addtime = strtotime(date("Y-m-d", $site_addtime) . ' 00:00:00');



			$this->db->query("UPDATE " . $this->common->table('site_data') . " SET site_daozhen = site_daozhen + 1 WHERE site_addtime = " . $site_addtime);


			//检查是否在公海
			if($this->common->getOne("select order_id from  ".$this->common->table('gonghai_order')." where order_id=".$order_id)){
				//公海捞回到预约
				$query1="insert into ".$this->common->table('order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id,ireport_order_id,ireport_msg from ".$this->common->table('gonghai_order')." where order_id in(".$order_id.")";
				if($this->db->query($query1)){
					//公海捞出 记录日志
					$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'从公海捞取','action_name'=>$_COOKIE['l_admin_name'],'action_id'=>$_COOKIE['l_admin_id'],'action_time'=>time(),'is_come'=>1));
					$this->db->query("delete from ".$this->common->table('gonghai_order')." where order_id in(".$order_id.")");
				}
			}


			//记录操作日志
			if(empty($_COOKIE['l_admin_name'])){
				$_COOKIE['l_admin_name'] = '';
			}
			$this->db->insert($this->common->table('turn_on'),array('order_id'=>$order_id,'admin_name'=>$_COOKIE['l_admin_name'],'admin_id'=>$_COOKIE['l_admin_id'],'time'=>time(),'turn_on'=>1));


            if ($dao_type == 1) {
                $dao_type_str = '初诊';
            } else {
                $dao_type_str = '复诊';
            }

            if (!empty($jzkh)) {

                //导医操作加入HIS系统里面的就诊卡号，与预约系统关联起来
                $this->db->update($this->common->table('order') , array(
                    'is_first' => $dao_type,
                    'his_jzkh' => $jzkh
                ) , array(
                    'order_id' => $order_id
                ));


                if (!empty($remark)) {
                    $this->db->insert($this->common->table('order_remark') , array(
                        'order_id' => $order_id,
                        'mark_content' => '就诊卡号为：'.$jzkh.'<br />'.$remark,
                        'admin_id' => $_COOKIE['l_admin_id'],
                        'admin_name' => $_COOKIE['l_admin_name'],
                        'mark_time' => time() ,
                        'mark_type' => 2
                    ));
                    $str['remark'] = '<blockquote class="d"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
                } else {
                    $this->db->insert($this->common->table('order_remark') , array(
                        'order_id' => $order_id,
                        'mark_content' => '就诊卡号为：'.$jzkh,
                        'admin_id' => $_COOKIE['l_admin_id'],
                        'admin_name' => $_COOKIE['l_admin_name'],
                        'mark_time' => time() ,
                        'mark_type' => 2
                    ));
                    $str['remark'] = '<blockquote class="d"><p>就诊卡号为：' . $jzkh . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
                }

            } else {

                $this->db->update($this->common->table('order') , array(
                    'is_first' => $dao_type
                ) , array(
                    'order_id' => $order_id
                ));

                if (!empty($remark)) {
                    $this->db->insert($this->common->table('order_remark') , array(
                        'order_id' => $order_id,
                        'mark_content' => $remark,
                        'admin_id' => $_COOKIE['l_admin_id'],
                        'admin_name' => $_COOKIE['l_admin_name'],
                        'mark_time' => time() ,
                        'mark_type' => 2
                    ));
                    $str['remark'] = '<blockquote class="d"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';
                }
            }

			$str = json_encode($str);


			/***
			 * 推送数据到数据中心
			*/
			//$this->send_order_shujuzhognxin($order_id);

			/***
			 * 推送数据到数据中心
			*/
			//$this->send_daozhen_to_shujuzhongxin($remark,$order_id);
		}



		elseif($type == 'doctor')



		{



			$come_time = $this->common->getOne("SELECT come_time FROM " . $this->common->table('order') . " WHERE order_id = $order_id");



			if(empty($come_time))



			{



				$come_time = time();



			}



			$doctor_name = $_REQUEST['doctor_name'];



			$this->db->update($this->common->table('order'), array('come_time' => $come_time, 'doctor_name' => $doctor_name, 'doctor_time' => time()), array('order_id' => $order_id));



			$str = array('come_time' => date("Y-m-d H:i", $come_time));



			$str = array('doctor_time' => date("Y-m-d H:i"));







			if(!empty($remark))



			{



				$this->db->insert($this->common->table('order_remark'), array('order_id' => $order_id, 'mark_content' => $remark, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'mark_time' => time() ,'mark_type' => 5));



				$str['remark'] = '<blockquote class="doc"><p>' . $remark . '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';



			}







			$str = json_encode($str);



		}







		echo $str;



	}



	public function mes_content_ajax()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$remark = isset($_REQUEST['remark'])? trim($_REQUEST['remark']):'';

		if($order_id <= 0 || empty($remark))

		{

			echo 0;

			exit();

		}

		$this->db->insert($this->common->table('mes_content'), array('order_id' => $order_id, 'mark_content' => $remark, 'mark_time' => time() ,'mark_type' => 3));

		$this->db->update($this->common->table('order_mes'), array('admin_id' => $_COOKIE['l_admin_id'],), array('order_id' => $order_id));

		$str = '<blockquote><p>' . $remark;



		$str .= '</p><small><a href="###">' . $_COOKIE['l_admin_name'] . '</a> <cite>' . date("m-d H:i") . '</cite></small></blockquote>';



		echo $str;

	}







	public function from_order_ajax()



	{



		$parent_id = isset($_REQUEST['parent_id'])? intval($_REQUEST['parent_id']):0;



		$from_id = isset($_REQUEST['from_id'])? intval($_REQUEST['from_id']):0;

		$_COOKIE["l_hos_id"] = isset($_REQUEST['from_id'])? intval($_REQUEST['hos_id']):0;
		//$_COOKIE["l_keshi_id"] = isset($_REQUEST['from_id'])? intval($_REQUEST['keshi_id']):0;

		$tag = isset($_REQUEST['tag'])? intval($_REQUEST['tag']):0;



		echo "<option value=\"0\">" . $this->lang->line('please_select') . "</option>";



		$from_list = $this->model->from_order_list($parent_id,$tag);



		if(empty($from_list))



		{



			exit();



		}



		foreach($from_list as $val)



		{



			echo "<option value=\"" . $val['from_id'] . "\"";



			if($val['from_id'] == $from_id)



			{



				echo " selected";



			}



			echo ">" . $val['from_name'] . "</option>";



		}



	}





	public function form_list_ajax()

	{

		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;

		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		echo "<option value='0'>请选择</option>";

		if($hos_id == 0){ exit; }

		$sql = 'select from_id,from_name,parent_id from '. $this->common->table('order_from') .' where (hos_id ='.$hos_id.' or hos_id =0 ) and parent_id = 0  and is_show = 0 ';

		$list = $this->common->getAll($sql);

		if(empty($list)){exit();}

		foreach($list as $val)

		{

			echo "<option value=\"" . $val['from_id'] . "\"";

			if($val['from_id'] == $check_id)

			{

				echo " selected ";

			}

			echo ">" . $val['from_name'] . "</option>";

		}

	}







	public function jibing_ajax()



	{



		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;



		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;



		$parent_id = isset($_REQUEST['parent_id'])? intval($_REQUEST['parent_id']):0;



		if($parent_id == 0)



		{



			echo "<option value=\"0\">" . $this->lang->line('jb_parent_select') . "</option>";



			$sql = "SELECT j.jb_id, j.jb_name



					FROM " . $this->common->table('jibing') . " j



					LEFT JOIN  " . $this->common->table('keshi_jibing') . " jk ON jk.keshi_id = $keshi_id



					WHERE jk.jb_id = j.parent_id AND j.jb_level = 2 order by j.jb_order asc";



		}



		else



		{



			echo "<option value=\"0\">" . $this->lang->line('jb_child_select') . "</option>";



			$sql = "SELECT j.jb_id, j.jb_name



					FROM " . $this->common->table('jibing') . " j



					WHERE j.parent_id = $parent_id";



		}







		$list = $this->common->getAll($sql);



		if(empty($list))



		{



			exit();



		}



		foreach($list as $val)



		{



			echo "<option value=\"" . $val['jb_id'] . "\"";



			if($val['jb_id'] == $check_id)



			{



				echo " selected ";



			}



			echo ">" . $val['jb_name'] . "</option>";



		}



	}







	 /***

	   根据医院和科室ID 来区分唯一的电话，医院和科室ID 必须大于0，如果为0，则返回错误

	 ***/

     public function phone_hos_keshi_check_ajax()

	{

		$phone = isset($_REQUEST['phone'])? trim($_REQUEST['phone']):'';

		$data = array();

		$data['over'] = '';

		if(empty($phone))

		{

			$data['type'] = 0;

			$data = json_encode($data);

			echo $data;

			exit();

		}



		$phones = explode("/", $phone);

		//$phone = $phone[0];

		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'0';

		$hos_id = isset($_REQUEST['hos_id'])? trim($_REQUEST['hos_id']):'0';

		$keshi_id = isset($_REQUEST['keshi_id'])? trim($_REQUEST['keshi_id']):'0';

		//医院和科室不能为空

		if($hos_id == 0 || $keshi_id == 0){

			 if($hos_id != 6 && $hos_id != 21){

				$data['type'] = 6;

				$data = json_encode($data);

				echo $data;

				exit();

			 }

		}

		$where  = " and o.hos_id = ".$hos_id." and o.keshi_id = ".$keshi_id."";

		/* 公海 检查此手机号码之前是否预约过 */

		if($hos_id == 3 && ( $keshi_id == 4 ||  $keshi_id == 85)  ){

			$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(4,85) ";

		}else if($hos_id == 3 && ( $keshi_id == 26 ||  $keshi_id == 86)  ){

			$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(26,86) ";

		}else if($hos_id == 6 && ( $keshi_id == 28 ||  $keshi_id == 88) ){

			$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(28,88) ";

		}else if($hos_id == 6 &&  ( $keshi_id == 33 ||  $keshi_id == 87)  ){

			$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(33,87) ";

		}else if($hos_id == 6 &&  ( $keshi_id == 34 ||  $keshi_id == 89)  ){

			$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(34,89) ";

		}


		if($hos_id == 6 || $hos_id == 21  || $hos_id == 40){//东方的只判断医院 不判断科室
		    $where = " and o.hos_id in(6,21,40)";
		}else if($hos_id == 3  || $hos_id == 36 || $hos_id == 16 ){ //台州的只判断医院 不判断科室
			 $where = " and o.hos_id in(3,36,16)";
		} else if($hos_id == 1 ){ //仁爱的只判断医院 不判断科室
			 $where = " and o.hos_id = 1";
		} else if($hos_id == 9){ //仁爱肛肠腋臭,判断科室 即：相同号码可以在不同科室使用
			//$where = " and o.hos_id = 9";
			switch ($keshi_id) {
				case 12:
					$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(12) ";
					break;
				case 15:
					$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(15) ";
					break;
				case 46:
					$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(46) ";
					break;

				default:
					$where = " and o.hos_id = 9";
					break;
			}
		} else if($hos_id == 45 || $hos_id == 46 ){ //厦门鹭港医院
			 $where = " and o.hos_id in(45,46)";
		} else if($hos_id == 42 || $hos_id == 44 ){ //成都自媒体
			 $where = " and o.hos_id in(42,44)";
		} else if($hos_id == 39 || $hos_id == 47 ){ //郑州
			 $where = " and o.hos_id in(39,47)";
		} else if($hos_id == 37){ //温州男科电话不能重复登记
			 $where = " and o.hos_id in(37)";
		} else if($hos_id == 54 ){ //宁波的只判断医院 不判断科室
			 $where = " and o.hos_id in(54)";
		} else if($hos_id == 38){ //温州肛肠腋臭,判断科室 即：相同号码可以在不同科室使用
			//$where = " and o.hos_id = 9";
			switch ($keshi_id) {
				case 97:
					$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(97) ";
					break;
				case 98:
					$where = " and o.hos_id = ".$hos_id." and o.keshi_id in(98) ";
					break;

				default:
					$where = " and o.hos_id = 38";
					break;
			}
		}


        foreach ($phones as $phone) {
            $isMob = "/^1[3-5,8]{1}[0-9]{9}$/";
            $isTel = "/^([0-9]{3,5})?[0-9]{7,8}$/";
            if (!preg_match($isMob, $phone) && !preg_match($isTel, $phone)) {
                $data['type'] = 1;
                $data = json_encode($data);
                echo $data;
                exit();
            }
            /* 检查此手机号码之前是否预约过 */
            $order_over = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('order') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '" . $phone . "'  " . $where . "  ORDER BY o.order_id DESC");
            //编辑时除去自身，不然一直提示号码预约过
            foreach ($order_over as $item) {
                if ($item['order_id'] == $order_id) {
                    $order_over = array();
                    break;
                }
             }
            if (!empty($order_over)) {
                $chek_month == 0;
                if (!empty($order_id)) {
                    foreach ($order_over as $val) {
                        $data['over'][$val['order_id']] = $val;
                        $data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
                        //2016 12 01    限制不能添加2个月以内的电话号码
                        if ($order_id != $val['order_id']) {
                            if ($chek_month == 0 && strtotime('-2 month') < $val['order_addtime']) { //  2个月之内的不能重复添加
                                $chek_month = 1;
                            }
                        }
                        //查询最后一次回访时间
                        $order_remark = $this->common->getAll("SELECT mark_time  FROM " . $this->common->table('order_remark') . " where order_id = " . $val['order_id'] . " and mark_type = 3 order by mark_time desc limit 0,1");
                        $data['over'][$val['order_id']]['mark_time'] = '';
                        $data['over'][$val['order_id']]['from'] = '预约';
                        if (count($order_remark) > 0) {
                            $data['over'][$val['order_id']]['mark_time'] = date("Y-m-d H:i:s", $order_remark[0]['mark_time']);
                        }
                    }
                } else {
                    foreach ($order_over as $val) {
                        $data['over'][$val['order_id']] = $val;
                        $data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
                        if ($chek_month == 0 && strtotime('-2 month') < $val['order_addtime']) { //  2个月之内的不能重复添加
                            $chek_month = 1;
                        }
                        //查询最后一次回访时间
                        $order_remark = $this->common->getAll("SELECT mark_time  FROM " . $this->common->table('order_remark') . " where order_id = " . $val['order_id'] . " and mark_type = 3 order by mark_time desc limit 0,1");
                        $data['over'][$val['order_id']]['mark_time'] = '';
                        $data['over'][$val['order_id']]['from'] = '预约';
                        if (count($order_remark) > 0) {
                            $data['over'][$val['order_id']]['mark_time'] = date("Y-m-d H:i:s", $order_remark[0]['mark_time']);
                        }
                    }

                }
                $data['type'] = 2;
                if ($chek_month == 1) {
                    $data['type'] = 5;
                }
                //仁爱 科室 不限制
                if ($keshi_id == 32 || $keshi_id == 7 || $keshi_id == 31 || $keshi_id == 1 || $keshi_id == 17 || $keshi_id == 18 || $keshi_id == 19) {
                    $data['type'] = 2;
                }
            }



            if ($data['type'] != 5) {
                $gonghai_order_over = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('gonghai_order') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '" . $phone . "' " . $where . "  ORDER BY o.order_id DESC");
                if (!empty($gonghai_order_over)) {
                    $chek_month = 0;
                    if (!empty($order_id)) {
                        foreach ($gonghai_order_over as $val) {
                            $data['over'][$val['order_id']] = $val;
                            $data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
                            //2016 12 01    限制不能添加2个月以内的电话号码
                            if ($order_id != $val['order_id']) {
                                if ($chek_month == 0 && strtotime('-2 month') < $val['order_addtime']) { //  2个月之内的不能重复添加
                                    $chek_month = 1;
                                }
                            }
                            //查询最后一次回访时间
                            $order_remark = $this->common->getAll("SELECT mark_time  FROM " . $this->common->table('order_remark') . " where order_id = " . $val['order_id'] . " and mark_type = 3 order by mark_time desc limit 0,1");
                            $data['over'][$val['order_id']]['mark_time'] = '';
                            $data['over'][$val['order_id']]['from'] = '公海';
                            if (count($order_remark) > 0) {
                                $data['over'][$val['order_id']]['mark_time'] = date("Y-m-d H:i:s", $order_remark[0]['mark_time']);
                            }
                        }
                    } else {
                        foreach ($gonghai_order_over as $val) {
                            $data['over'][$val['order_id']] = $val;
                            $data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
                            //2016 12 01    限制不能添加2个月以内的电话号码
                            if ($chek_month == 0 && strtotime('-2 month') < $val['order_addtime']) { //  2个月之内的不能重复添加
                                $chek_month = 1;
                            }
                            //查询最后一次回访时间
                            $order_remark = $this->common->getAll("SELECT mark_time  FROM " . $this->common->table('order_remark') . " where order_id = " . $val['order_id'] . " and mark_type = 3 order by mark_time desc limit 0,1");
                            $data['over'][$val['order_id']]['mark_time'] = '';
                            $data['over'][$val['order_id']]['from'] = '公海';
                            if (count($order_remark) > 0) {
                                $data['over'][$val['order_id']]['mark_time'] = date("Y-m-d H:i:s", $order_remark[0]['mark_time']);
                            }
                        }
                    }
                    $data['type'] = 2;
                    if ($chek_month == 1) {
                        $data['type'] = 5;
                    }
                    //仁爱 科室 不限制
                    if ($keshi_id == 32 || $keshi_id == 7 || $keshi_id == 31 || $keshi_id == 1 || $keshi_id == 17 || $keshi_id == 18 || $keshi_id == 19) {
                        $data['type'] = 2;
                    }
                }
            }

            //排除自身留联电话号码
            $sql = "select pat_phone  from hui_patient where pat_id  = (select pat_id  from hui_order_liulian where order_no_yy = (select order_no from hui_order where order_id = $order_id)) ";
            $pat_phone= $this->common->getOne($sql);

            //排除妇科项目判断留联是否存在重复
            if( !in_array($hos_id, array(1,9,45,46)) && $pat_phone != $phone){
	            $is_liulian = isset($_REQUEST['is_liulian']) ? trim($_REQUEST['is_liulian']) : 0;
	            //判断是否从留联转入
	            //is_liulian 为1 表示不是从留联转入 所以要检查此手机号码是否与留联重复
	            if ($is_liulian == 1) {
	                /* 检查此手机号码是否与留联重复 */
	                $order_over_ll = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('order_liulian') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '" . $phone . "'  " . $where . "  ORDER BY o.order_id DESC");

	                if (!empty($order_over_ll)) {
	                    $chek_month == 0;
	                    if (!empty($order_over_ll)) {
	                        foreach ($order_over_ll as $val) {
	                            $data['over'][$val['order_id']] = $val;
	                            $data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);
	                            $data['over'][$val['order_id']]['from'] = '留联';
	                        }
	                    }
	                    $data['type'] = 7;

	                }
	            }
	        }


           // p($data);die();
        }

        if ($data['type'] != 2 && $data['type'] != 5 & $data['type'] != 7) {
            $data['type'] = 4;
        }
		$data = json_encode($data);
		echo $data;exit;

	}





    public function phone_ajax()



	{



		$phone = isset($_REQUEST['phone'])? trim($_REQUEST['phone']):'';



		$data = array();



		$data['over'] = '';



		if(empty($phone))



		{



			$data['type'] = 0;



			$data = json_encode($data);



			echo $data;



			exit();



		}



		$phone = explode("/", $phone);



		$phone = $phone[0];



		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'0';



		/* 公海 检查此手机号码之前是否预约过 */

  		$gonghai_order_over = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('gonghai_order') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '".$phone."' ORDER BY o.order_id DESC");





		/* 检查此手机号码之前是否预约过 */



		$order_over = $this->common->getAll("SELECT o.order_id,o.order_no,o.order_addtime,o.admin_name, p.pat_name,h.hos_name FROM " . $this->common->table('order') . " o LEFT JOIN " . $this->common->table('hospital') . " h ON o.hos_id = h.hos_id LEFT JOIN " . $this->common->table('patient') . " p ON o.pat_id = p.pat_id WHERE p.pat_phone = '".$phone."' ORDER BY o.order_id DESC");



		if(!empty($order_over))



		{

            $chek_month = 0;

			foreach($order_over as $val)



			{



				$data['over'][$val['order_id']] = $val;



				$data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);



				/**

				//2016 12 01    限制不能添加2个月以内的电话号码

				if($order_id != $val['order_id'] && $chek_month == 0){

					if($chek_month ==0 && strtotime('-2 month') < $val['order_addtime'] ){//  2个月之内的不能重复添加

						 $chek_month = 1;

					}

				} **/

			}

			$data['type'] = 2;

			/**

            if( $chek_month == 0){

				 $data['type'] = 2;

			}else{

			     $data['type'] = 5;

			}**/



		}else if(!empty($gonghai_order_over)) {



			 $chek_month = 0;



			foreach($gonghai_order_over as $val)



			{



				$data['over'][$val['order_id']] = $val;



				$data['over'][$val['order_id']]['addtime'] = date("Y-m-d H:i:s", $val['order_addtime']);



				/**

				//2016 12 01   限制不能添加2个月以内的电话号码

				if($order_id != $val['order_id'] && $chek_month == 0){

					if($chek_month ==0 && strtotime('-2 month') < $val['order_addtime'] ){//  2个月之内的不能重复添加

						 $chek_month = 1;

					}

				}  **/

			}

			 $data['type'] = 2;



			 /***

			if( $chek_month == 0){

				 $data['type'] = 2;

			}else{

			     $data['type'] = 5;

			}

			**/

		}else {



//			$xml = file_get_contents("http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi?chgmobile=" . $phone);

//

//			preg_match_all( "/\<city\>(.*?)\<\/city\>/s", $xml, $city);

//

//			if(!isset($city[1][0]))

//

//			{

//

//				$data['type'] = 1;

//

//				$data = json_encode($data);

//

//				echo $data;

//

//				exit();

//

//			}

                    $isMob="/^1[3-5,8]{1}[0-9]{9}$/";

                   $isTel="/^([0-9]{3,5})?[0-9]{7,8}$/";

                    if(!preg_match($isMob,$phone) && !preg_match($isTel,$phone)){



                        $data['type'] = 1;



				$data = json_encode($data);



				echo $data;



				exit();



                    }



//			$city[1][0] = mb_convert_encoding($city[1][0],'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

//

//			$area_info = $this->common->getRow("SELECT region_id, parent_id FROM " . $this->common->table('region') . " WHERE region_name = '" . trim($city[1][0]) . "' AND region_type = 2");



			$data['type'] = 4;



//			$data['info'] = $area_info;



		}



		$data = json_encode($data);



		echo $data;



	}







	public function order_no_ajax()



	{



		$order_no = file_get_contents("./application/cache/static/order_no.txt");



		$zm = array('1' => 'A',



					'2' => 'B',



					'3' => 'C',



					'4' => 'D',



					'5' => 'E',



					'6' => 'F',



					'7' => 'G',



					'8' => 'H',



					'9' => 'I',



					'10' => 'J',



					'11' => 'K',



					'12' => 'L',



					'13' => 'M',



					'14' => 'N',



					'15' => 'O',



					'16' => 'P',



					'17' => 'Q',



					'18' => 'R',



					'19' => 'S',



					'20' => 'T',



					'21' => 'U',



					'22' => 'V',



					'23' => 'W',



					'24' => 'X',



					'25' => 'Y',



					'26' => 'Z');



		if(empty($order_no)){
			$order_no =  $this->common->getOne("select order_no from " . $this->common->table('order') . " order by order_addtime desc limit 0,1" );
		}

		if(empty($order_no))



		{



			$order_no = "AA0001";



		}



		else



		{



			$order_no_zimu = substr($order_no, 0, 2);



			$order_no_shuzi = substr($order_no, 2, 4);







			$order_no_shuzi = intval($order_no_shuzi) + 1;



			if($order_no_shuzi >= 9999)



			{



				$order_no_zimu_a = substr($order_no_zimu, 0, 1);



				$order_no_zimu_b = substr($order_no_zimu, 1, 1);



				$mz = array_flip($zm);



				if($order_no_zimu_b == 'Z')



				{



					$order_no_zimu_a = $zm[$mz[$order_no_zimu_a] + 1];



					$order_no_zimu_b = 'A';



				}



				else



				{



					$order_no_zimu_b = $zm[$mz[$order_no_zimu_b] + 1];



				}



				$order_no_zimu = $order_no_zimu_a . $order_no_zimu_b;



				$order_no_shuzi = '0001';



			}



			else



			{



				$zimu_len = 4 - strlen($order_no_shuzi);



				for($i = 1; $i <= $zimu_len; $i ++)



				{



					$order_no_shuzi = "0" . $order_no_shuzi;



				}



			}







			$order_no = $order_no_zimu . $order_no_shuzi;



		}



		file_put_contents("./application/cache/static/order_no.txt", $order_no);







		echo $order_no;



	}







	public function use_no_ajax()



	{



		$order_no = trim($_REQUEST['order_no']);



		$havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE order_no = '" . $order_no . "'");



		if($havd)



		{



			echo 1;



			exit();



		}



		else



		{



			$str_len = strlen($order_no);



			if($str_len != 6)



			{



				echo 2;



				exit();



			}



		}



	}







	public function keshi_list_ajax()



	{



		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;



		$type = isset($_REQUEST['type'])? intval($_REQUEST['type']):1;







		if($type == 1)



		{



			echo "<option value=\"0\">请选择科室...</option>";



		}







		if(empty($hos_id))



		{



			exit();



		}







		$keshi_list = $this->model->keshi_order_list($hos_id);



		if(empty($keshi_list))



		{



			exit();



		}







		$str = '';



		foreach($keshi_list as $val)



		{



			$str .= '<option value="' . $val['keshi_id'] . '"';



			if($val['keshi_id'] == $check_id)



			{



				$str .= " selected";



			}



			$str .= '>' . $val['keshi_name'] . '</option>';



		}







		echo $str;



	}







	public function sms_themes_ajax()



	{



		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;



		$where = 1;



		if(!empty($hos_id))



		{



			$where .= " AND hos_id = $hos_id";



		}



		if(!empty($keshi_id))



		{



			$where .= " AND keshi_id = $keshi_id";



		}



		$str = '<option value=\"0\">请选择...</option>';







		$row = $this->common->getAll("SELECT themes_id, themes_name FROM " . $this->common->table('sms_themes') . " WHERE $where");



		foreach($row as $val)



		{



			$str .= '<option value="' . $val['themes_id'] . '"';



			$str .= '>' . $val['themes_name'] . '</option>';



		}









		echo $str;



	}



	//短信发送ajax



	public function sms_ajax()



	{

        /*

         * 主要传入患者姓名、患者电话、预到时间、登记时间、未定时间、预到类别、大概时间、预约号、预约id

         * 短信id、类别、短信内容

         * 如果短信内容为空，返回1，退出执行

         * 如果类别为list则把预到时间和预约时间赋值

         * 这个方法主要的功能就是把{username}···等赋予相关的值。仅此而已。

         *

         */

		$pat_name = isset($_REQUEST['pat_name'])? trim($_REQUEST['pat_name']):'';



		$pat_phone = isset($_REQUEST['pat_phone'])? trim($_REQUEST['pat_phone']):'';



		$order_time = isset($_REQUEST['order_time'])? trim($_REQUEST['order_time']):'';



		$order_addtime = isset($_REQUEST['order_addtime'])? trim($_REQUEST['order_addtime']):'';



		$order_null_time = isset($_REQUEST['order_null_time'])? trim($_REQUEST['order_null_time']):'';



		$order_time_type = isset($_REQUEST['order_time_type'])? trim($_REQUEST['order_time_type']):'';



		$order_time_duan = isset($_REQUEST['order_time_duan'])? trim($_REQUEST['order_time_duan']):'';



		$order_no = isset($_REQUEST['order_no'])? trim($_REQUEST['order_no']):'';



		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$themes_id = isset($_REQUEST['themes_id'])? intval($_REQUEST['themes_id']):0;



		$type = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';







		$themes_content = $this->common->getOne("SELECT themes_content FROM " . $this->common->table('sms_themes') . " WHERE themes_id = $themes_id");



		if(empty($themes_content))



		{



			echo 1;



			exit();



		}







		if($type == 'list')



		{



			$addtime = $order_addtime;



			$ordertime = $order_time;



			if(!empty($order_time_duan))



			{



				$ordertime .= " " . $order_time_duan;



			}



		}



		else



		{



			if(!empty($order_id))



			{



				$order_info = $this->common->getRow("SELECT * FROM " . $this->common->table('order') . " WHERE order_id = $order_id");



				$addtime = date("m-d H:i", $order_info['order_addtime']);



			}



			else



			{



				$addtime = date("m-d H:i");



			}







			if($order_time_type == 1)



			{



				$ordertime = $order_time;



			}



			else



			{



				$ordertime = $order_null_time;



			}







			$duan = $this->lang->line('day_time');



			if($order_time_duan > 0)



			{



				$ordertime .= " " . $duan[$order_time_duan];



			}



		}







		$replace = array('{username}', '{phone}', '{ordertime}', '{addtime}', '{orderno}');



		$value = array($pat_name, $pat_phone, $ordertime, $addtime, $order_no);







		$sms = str_replace($replace, $value, $themes_content);



		echo $sms;



	}


	  //判断该预约是否是留联预约
        public function ajax_is_liulian(){
			$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';
			$order_id = substr($order_id, 0, -1);
			$sql="select o.order_id from ".$this->common->table("order_liulian")." as l,".$this->common->table("order")." as o where o.order_id in(".$order_id.") and o.order_no = l.order_no_yy";
			$rs=$this->common->getAll($sql);
			$rs=  json_encode($rs);
			echo $rs;
		}



        //判断该预约是否是公海预约

        public function ajax_is_gonghai(){

            $order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);

                $sql="select order_id from ".$this->common->table("gonghai_log")." where order_id in(".$order_id.") and action_type='掉入公海'";

                $rs=$this->common->getAll($sql);

                $rs=  json_encode($rs);

                echo $rs;





        }

       //判定短信是否为空

        public function ajax_sms_isnull(){



                $order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);

                $sql="select type_value from ".$this->common->table("sms_send")." where type_value in(".$order_id.")";

                $rs=$this->common->getAll($sql);

                $rs=  json_encode($rs);

                echo $rs;

        }

        //判定对话是否为空

        public function ajax_talk_isnull(){



                $order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);



                $sql="select order_id from ".$this->common->table("ask_content")." where order_id in(".$order_id.") and con_content!=''";

                $rs=$this->common->getAll($sql);

                $rs=  json_encode($rs);

                echo $rs;

        }



	public function ajax_remark_list()



	{



		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);



		$sql = "SELECT * FROM " . $this->common->table('order_remark') . " WHERE order_id IN ($order_id) ORDER BY mark_id DESC,mark_time DESC";



		$row = $this->common->getAll($sql);



		$arr = array();



		foreach($row as $val)



		{



			$arr[$val['mark_id']] = $val;



			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);



		}



		echo json_encode($arr);



	}

	public function ajax_liulian_remark_list()



	{



		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);



		$sql = "SELECT * FROM " . $this->common->table('order_liulian_remark') . " WHERE order_id IN ($order_id) ORDER BY mark_id DESC,mark_time DESC";



		$row = $this->common->getAll($sql);



		$arr = array();



		foreach($row as $val)



		{



			$arr[$val['mark_id']] = $val;



			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);



		}



		echo json_encode($arr);



	}

	public function ajax_message_list()



	{



		$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):'';



		$order_id = substr($order_id, 0, -1);



		$sql = "SELECT * FROM " . $this->common->table('mes_content') . " WHERE order_id IN ($order_id) ORDER BY mark_id DESC,mark_time DESC";



		$row = $this->common->getAll($sql);



		$arr = array();



		foreach($row as $val)



		{



			$arr[$val['mark_id']] = $val;



			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);



		}



		echo json_encode($arr);



	}


	public function change_order_status()



	{


		if(empty($_COOKIE['l_admin_name'])){
			$_COOKIE['l_admin_name']= '';
		}
		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		if($order_id <= 0)



		{



			echo 0;



			exit();



		}




		$is_come = $this->common->getOne("SELECT is_come FROM " . $this->common->table('order') . " WHERE order_id = $order_id");


		$rank_type = $this->common->getOne("SELECT rank_type FROM " . $this->common->table('rank') . " WHERE rank_id = ".$_COOKIE['l_rank_id']);

		if($is_come == 1 && ($_COOKIE['l_admin_action'] == 'all' || $rank_type == 4))



		{



			$this->db->update($this->common->table('order'), array('is_come' => 0), array('order_id' => $order_id));

                        if($this->db->query("select * from ".$this->common->table('gonghai_order')." where order_id=".$order_id)){

                        $this->db->update($this->common->table('gonghai_order'), array('is_come' => 0), array('order_id' => $order_id));

                        }
            //记录操作日志
            $this->db->insert($this->common->table('turn_on'),array('order_id'=>$order_id,'admin_name'=>$_COOKIE['l_admin_name'],'admin_id'=>$_COOKIE['l_admin_id'],'time'=>time(),'turn_on'=>0));

			/***
			 * 推送数据到数据中心
			*/
			//$this->send_order_shujuzhognxin($order_id);

            /***
             * 推送数据到数据中心
            */
            //$this->send_daozhen_to_shujuzhongxin('',$order_id);

		}



		else

		{



			$this->db->update($this->common->table('order'), array('come_time' => time(), 'is_come' => 1), array('order_id' => $order_id));

                          if($this->db->query("select * from ".$this->common->table('gonghai_order')." where order_id=".$order_id)){

                        $this->db->update($this->common->table('gonghai_order'), array('come_time' => time(), 'is_come' => 1), array('order_id' => $order_id));

                        }



			$info = $this->model->order_info($order_id);

			if($info['hos_id']==1){

				$username = $info['pat_phone'];

				$this->group_change($username);

			}

			//检查是否在公海
			if($this->common->getOne("select order_id from  ".$this->common->table('gonghai_order')." where order_id=".$order_id)){
				//公海捞回到预约
				$query1="insert into ".$this->common->table('order')."(order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id) select order_id,order_no,is_first,admin_id,admin_name,pat_id,from_parent_id,from_id,from_value,hos_id,keshi_id,type_id,
                                jb_parent_id,jb_id,order_addtime,order_time,order_null_time,doctor_time,doctor_id,doctor_name,come_time,order_time_duan,duan_confirm,is_come,keshiurl_id from ".$this->common->table('gonghai_order')." where order_id in(".$order_id.")";
				if($this->db->query($query1)){
					//公海捞出 记录日志
					$this->db->insert($this->common->table('gonghai_log'),array('order_id'=>$order_id,'action_type'=>'从公海捞取','action_name'=>$_COOKIE['l_admin_name'],'action_id'=>$_COOKIE['l_admin_id'],'action_time'=>time(),'is_come'=>1));
					$this->db->query("delete from ".$this->common->table('gonghai_order')." where order_id in(".$order_id.")");
				}
			}

			//记录操作日志
			$this->db->insert($this->common->table('turn_on'),array('order_id'=>$order_id,'admin_name'=>$_COOKIE['l_admin_name'],'admin_id'=>$_COOKIE['l_admin_id'],'time'=>time(),'turn_on'=>1));

			/***
			 * 推送数据到数据中心
			*/
			//$this->send_order_shujuzhognxin($order_id);

			/***
			 * 推送数据到数据中心
			*/
			//$this->send_daozhen_to_shujuzhongxin('',$order_id);

		}
		echo date("Y-m-d H:i");



	}



	public function siwei()



	{



		$hos_id            = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$order_id          = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$order_time        = isset($_REQUEST['order_time'])? trim($_REQUEST['order_time']):'';



		$order_time_duan_j = isset($_REQUEST['order_time_duan_j'])? trim($_REQUEST['order_time_duan_j']):'';





		if(empty($hos_id) || empty($order_time) || empty($order_time_duan_j))



		{



			echo 1;



			exit();



		}



		$order_time = strtotime($order_time);



		$where = 1;



		if($order_id > 0)



		{



			$where .= " AND order_id != $order_id";



		}







		$is_havd = $this->common->getOne("SELECT order_id FROM " . $this->common->table('order') . " WHERE $where AND hos_id = $hos_id AND jb_parent_id = 149 AND order_time = $order_time AND order_time_duan = '" . $order_time_duan_j . "'");



		if($is_havd)



		{



			echo 1;



			exit();



		}



		else



		{



			echo 0;



		}



	}







	public function kefu_talk()



	{



		$type          = isset($_REQUEST['type'])? intval($_REQUEST['type']):0;



		$from_value    = isset($_REQUEST['from_value'])? trim($_REQUEST['from_value']):'';







		if(empty($type) || empty($from_value))



		{



			echo 0;



			exit();



		}







		if($type == 1)



		{



			$gid_arr = $this->common->getAll("SELECT gid FROM " . $this->common->table('swt') . " WHERE cid = '" . $from_value . "' ORDER BY swt_id ASC");



			$from_value = substr($from_value, 0, 3) . "/" . substr($from_value, 3, 3) . "/" . substr($from_value, 6, 4) . "/" . substr($from_value, 10, 3) . "/" . substr($from_value, 13) . "/";



			$file_path = './static/swt/' . $from_value;



			$str = '';



			$gid_str = '';



			foreach($gid_arr as $val)



			{



				if(empty($val['gid']))



				{



					$val['gid'] = 'index';



				}



				else



				{



					$gid_str .= $val['gid'] . ',';



				}







				include_once($file_path . $val['gid'] . ".php");



				foreach($data['swt_list'] as $val)



				{



					$val['user'] = str_replace("您", "患者", $val['user']);



					$str .= "<p><b>" . $val['user'] . "</b></p>";



					$str .= $val['word'] . "<p>&nbsp;</p>";



				}



			}



			echo json_encode(array('gid' => $gid_str, 'str' => $str));



		}



	}







	public function page_path()



	{



		$from_value    = isset($_REQUEST['from_value'])? trim($_REQUEST['from_value']):'';



		$gid_arr = $this->common->getAll("SELECT gid FROM " . $this->common->table('swt') . " WHERE cid = '" . $from_value . "' ORDER BY swt_id ASC");



		$str = "";



		foreach($gid_arr as $val)



		{



			if(!empty($val['gid']))



			{



				$site_id = $this->common->getOne("SELECT site_id FROM " . $this->common->table('google_visitor') . " WHERE vis_cid = '" . $val['gid'] . "'");



				$from_site_list  = read_static_cache('from_site_list');



				$from_type_list  = read_static_cache('from_type_list');



				if($site_id)



				{



					$sql = "SELECT path_cid, path_pre, path_url, path_title, path_time, is_ask, load_id, COUNT(path_cid) AS path_url_reload



							FROM " . $this->common->table('google_path_' . $site_id) . "



							WHERE path_cid = '" . $val['gid'] . "'



							GROUP BY path_url



							ORDER BY path_vtime ASC, path_time DESC";



					$row = $this->common->getAll($sql);



					$i = 1;



					foreach($row as $key=>$val)



					{



						if($val['load_id'] >= 1)



						{



							$load_info = $this->common->getRow("SELECT l.from_site_id, l.from_type_id, l.from_url, k.key_keyword



																FROM " . $this->common->table('google_load_' . $site_id) . " l



																LEFT JOIN " . $this->common->table('google_search') . " s ON s.load_id = l.load_id AND s.site_id = $site_id



																LEFT JOIN " . $this->common->table('keywords') . " k ON s.key_id = k.key_id



																WHERE l.load_id = " . $val['load_id']);



						}



						$str .= "<p>" . $i . "、" . date("Y-m-d H:i:s", $val['path_time']) . "<br/>";



						if($i == 1)



						{



							if(isset($load_info['from_site_id']) && !empty($load_info['from_site_id']))



							{



							    $str .=  "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">在</font> " . $from_site_list[$load_info['from_site_id']]['site_name'] . " <font color=\"#FF0000\">以</font> ";



								$str .=  $from_type_list[$load_info['from_type_id']]['type_name'] . " 方式";



								if(!empty($load_info['key_keyword']))



								{



									$str .=  "搜索关键词 <font color=\"#FF0000\" title=\"" . $load_info['key_keyword'] . "\">" . sub_str($load_info['key_keyword'], 20) . "</font>";



								}



							}



							elseif(isset($load_info['from_url']) && !empty($load_info['from_url']))



							{



							    $str .=  "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">在</font> <a href=\"" . $load_info['from_url'] . "\" target=\"_blank\" title=\"" . $load_info['from_url'] . "\">" . sub_str($load_info['from_url'], 30) . "</a>";



							}



							else



							{



								$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> 直接输入";



							}



						}



						elseif(empty($val['path_pre']))



						{



							$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> 直接输入";



						}



						else



						{



							$str .= "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">从</font> <a href=\"" . $val['path_pre'] . "\" target=\"_blank\">" . $val['path_pre'] . "</a>";



						}



						$str .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"#FF0000\">到</font> <a title=\"" . $val['path_title'] . "\" href=\"http://" . $val['path_url'] . "\" target=\"_blank\">http://" . $val['path_url'] . "</a></p>";



						$i ++;



					}



				}



			}



		}



		echo $str;



	}



	public function talk_info_liulian()

	{

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$info = '<div class="left" style="width:65%; height:400px; float:left; overflow-y: auto; overflow-x: hidden;">';

		$content = $this->common->getOne("SELECT con_content FROM " . $this->common->table('order_liulian') . " WHERE order_id = $order_id");

		$info .=$content;

		$info .= '</div>';

		echo $info;

	}


	public function talk_info_liulian_gh()

    {

        $order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

        $info = '<div class="left" style="width:65%; height:400px; float:left; overflow-y: auto; overflow-x: hidden;">';

        $content = $this->common->getOne("SELECT con_content FROM henry_gonghai_liulian WHERE order_id = $order_id");

        $info .=$content;

        $info .= '</div>';

        echo $info;

    }



	/**回访记录添加**/

	public function ajax_hf_add_liulian()

	{

		$order_id = empty($_REQUEST['hf_order_id'])? 0:intval($_REQUEST['hf_order_id']);
		$msg = empty($_REQUEST['msg_hf'])? '':$_REQUEST['msg_hf'];

		$order_remark  = array();
		$order_remark['order_id'] = $order_id;
		$order_remark['mark_content'] = $msg;
		$order_remark['admin_id'] = $_COOKIE['l_admin_id'];
		$order_remark['admin_name'] = $_COOKIE['l_admin_name'];
		if(empty($order_remark['admin_name'])){
			$order_remark['admin_name'] = '';
		}
		$order_remark['mark_time'] = time();
		$order_remark['mark_type'] = 3;
		$order_remark['type_id'] =0;
		$i = $this->db->insert($this->common->table("order_liulian_remark"), $order_remark);
		if($i){
			if($this->db->insert_id() > 0){
				echo "<span style='color:#999999'>回访内容:</span>".$order_remark['mark_content']."<br/>".'<span style="color:#999999">时间:</span>'.date('Y-m-d H:s:i',$order_remark['mark_time']).'<span style="color:#999999">操作人:</span>'.$order_remark['admin_name']."<br/><br/>";
			}else{echo '';}
		}else{echo '';}
		exit;

	}



	public function hf_info_liulian()

	{


		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$content = $this->common->getAll("SELECT * FROM " . $this->common->table('order_liulian_remark') . " WHERE mark_type = 3 and  order_id = $order_id  order by mark_time desc ");

		$admin_name = $this->common->getOne("SELECT admin_name FROM " . $this->common->table('order_liulian') . " WHERE order_id = $order_id");

		$info='';

		foreach ($content as $content_temp){
			$info = $info."<span style='color:#999999'>回访内容:</span>".$content_temp['mark_content']."<br/>".'<span style="color:#999999">时间:</span>'.date('Y-m-d H:s:i',$content_temp['mark_time']).'<span style="color:#999999">操作人:</span>'.$content_temp['admin_name']."<br/><br/>";
		}

		echo $info;

	}



	public function talk_info()



	{



		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$info = '<div class="left" style="width:65%; height:400px; float:left; overflow-y: auto; overflow-x: hidden;">';



		$content = $this->common->getOne("SELECT con_content FROM " . $this->common->table('ask_content') . " WHERE order_id = $order_id");

		$info .=$content;

		$info .= '</div>';

		if(!empty($content)){

		$sql = "SELECT p.*, a.admin_name, a.admin_id

				FROM " . $this->common->table('asker_ping') . " p

				LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = p.uid

				where p.order_id = $order_id ORDER BY p.tid ASC ";



		$ping = $this->common->getAll($sql);



		$info .= '<div class="pinglun">';

		$info .= '<h4><i class="icon-bell"></i>&nbsp;点评</h4>';

		$info .= '<dl class="contentz">';

		foreach($ping as $val)

		{

		$time = date('Y-m-d H:s:i',$val['time']);

		$info .= '<dt class="text_title">'.$val['admin_name'].'&nbsp;发表于'.$time.'</dt>';

		$info .= '<dd class="text_con">'.$val['content'].'</dd>';

                $info .='<hr class="content_hr" />';

		}



		$info .= '</dl>';

		$info .= '<div class="tijiao">';

		$info .= '<textarea class="input-large" style="width:80%" rows="5" onClick="bigTxt()" id="textareaz">';

		$info .= '我也说一句';

		$info .= '</textarea>';

		$info .= '<button class="btn" data-toggle="modal" onClick="insert_talk(\''.$order_id.'\')"> 发表 </button>';

		$info .= '</div>';





		$info .= '</div>';

		}



		echo $info;





	}



	public function insert_info()

	{

	// tid content order_id fid uid

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;

		$data = $_REQUEST['content'];

		$admin_name = $_COOKIE['l_admin_name'];

		$uid = $_COOKIE['l_admin_id'];



		$time = time();

		$arr = array(

				'content' => $data,

				'order_id' => $order_id,

				'uid' => $uid,

				'fid' => '0',

				'time'=>$time,

		);

		$this->db->insert($this->common->table("asker_ping"), $arr);

		$time = date('Y-m-d H:s:i',$time);

		$info = '';



		//$info .= '<div class="con">';

		$info .= '<dt class="text_title"><span style="color:gray;">'.$admin_name.'&nbsp;发表于'.$time.'</span></dt>';



		$info .= '<dd class="text_con">'.$data.'<dd>';

		$info .= '<hr class="content_hr" />';

		echo $info;

	}







	public function sms_content()



	{

          /*

           * 获取短信的内容

           * send_type=1代表成功发送的短信，send_type=3代表成功获取的短信

           * 根据type_value=$order_id来获取短信的内容，并根据短信发送时间来降序排列

           * 新建一个$arr二维数组，用来存放短信发送的相关信息，并格式化相关的send_time的格式和短信发送状态

           * 最后把$arr通过json_encode格式化

           *

           *

           */

		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$sql = "SELECT send_id, send_content, send_time, send_phone, type_value, admin_id, admin_name, send_status, send_type



				FROM " . $this->common->table('sms_send') . "



				WHERE (send_type = 1 OR send_type = 3 or send_type = 4 ) AND type_value = $order_id



				ORDER BY send_time DESC";



		$row = $this->common->getAll($sql);



		$arr = array();



		$status = $this->lang->line('sms_send_status');



		foreach($row as $key=>$val)



		{



			$arr[$key] = $val;



			$arr[$key]['send_time'] = date("m-d H:i", $val['send_time']);



			$arr[$key]['status'] = isset($status[$val['send_status']])? $status[$val['send_status']]:$val['send_status'];



		}







		echo json_encode($arr);



	}







	public function sms_reply()



	{

        /*

         * 获取短信回复，目前只支持获取单个医院的短信回复

         *

         *

         */

        header("Content-Type:application/x-www-form-urlencoded;charset=utf-8");

		$type = isset($_REQUEST['type'])? intval($_REQUEST['type']):1;

                $hos_id=isset($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):1;

		if($type == 1)



		{



//			$date = file_get_contents('./application/cache/static/sms_time.txt');



			echo date("Y-m-d H:i:s",time());



		}



		else





		{



			$date = file_get_contents('./application/cache/static/sms_time.txt');



			$hospital = $this->common->static_cache('read', "hospital/".$hos_id, 'hospital', array('hos_id' => $hos_id));



			$username = urlencode(trim($hospital['sms_name']));



			$password = urlencode(trim($hospital['sms_pwd']));







			require_once('application/libraries/sms/nusoap.php');



			$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);



			$client->soap_defencoding = 'utf-8';



			$client->decode_utf8      = false;



			$client->xml_encoding     = 'utf-8';







			$params = array(



				'account' => $hospital['sms_name'],



				'password' => $hospital['sms_pwd'],



				'param' => 0,



			);







			$result = $client->call('getReceivedMsg', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');







			if(!isset($result['getReceivedMsgReturn']) || empty($result['getReceivedMsgReturn']))



			{



				echo 1;



				exit();



			}







			$str = explode("|", $result['getReceivedMsgReturn']);



			if(!isset($str[1]))



			{



				echo 1;



				exit();



			}



			foreach($str as $val)



			{



				if(empty($val))



				{



					continue;



				}



				$str_arr = explode(",", $val);



				$phone = $str_arr[0];



				$time = substr($str_arr[2], 0, 4) . "-" . substr($str_arr[2], 4, 2) . "-" . substr($str_arr[2], 6, 2) . " " . substr($str_arr[2], 8, 2) . ":" . substr($str_arr[2], 10, 2) . ":" . substr($str_arr[2], 12, 2);



				$str_arr[1] = str_replace("\\","%",$str_arr[1]);



				$content = unescape($str_arr[1]);//unicode码转汉字



				$order_id = $this->common->getOne("SELECT type_value FROM " . $this->common->table('sms_send') . " WHERE send_type = 1 AND send_phone = '" . $phone . "' AND send_time >= " . (time() - 86400 * 90) . " ORDER BY send_id LIMIT 1");







				$send = array('send_content' => $content,



				              'send_time' => strtotime($time),



							  'send_phone' => $phone,



							  'admin_name' => $phone,



							  'send_status' => 0,



							  'send_type' => 0,



							  'type_value' => 0);







				if($order_id)



				{



					$remark = array('order_id' => $order_id,



									'admin_id' => 0,



									'admin_name' => $phone,



									'mark_content' => $content,



									'mark_time' => strtotime($time),



									'mark_type' => 4);



					$this->db->insert($this->common->table("order_remark"), $remark);



					$send['send_type'] = 3;



					$send['type_value'] = $order_id;



				}



				$this->db->insert($this->common->table("sms_send"), $send);



			}



			file_put_contents('./application/cache/static/sms_time.txt', time());



			echo 1;



		}



	}



	public function sms_send_ajax()

	{      /*短信发送功能

               *输入预约id,患者号码，短信内容，医院id 短信模板id 科室_id

         * 把患者姓名用/分割

               *

               */



		$order_id = isset($_REQUEST['order_id'])? intval($_REQUEST['order_id']):0;



		$pat_phone = isset($_REQUEST['pat_phone'])? trim($_REQUEST['pat_phone']):'';



		$sms_content = isset($_REQUEST['sms_content'])? trim($_REQUEST['sms_content']):'';



		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$sms_id = isset($_REQUEST['sms_id'])? intval($_REQUEST['sms_id']):0;



		$keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

                if(empty($sms_content) || ($hos_id <= 0))

		{

			echo "内容为空！";

			exit();

                }





		$pat_phone = explode("/", $pat_phone);

                //判断重复短信功能代码开始

                $phone_1=implode(",", $pat_phone);

                $phone_1 = "'".str_replace(",","','",$phone_1)."'";

                $limit_time=time()-60*60;//判断8小时内是否发送重复的内容

                $sql_1="select * from ".$this->common->table('sms_send')." where 1 and send_status=0  and send_time>".$limit_time." and send_phone in (".$phone_1.") and send_content='".$sms_content." ' and hos_id=".$hos_id." limit 0,2";

                $res=$this->common->getAll($sql_1);



                if(!empty($res)){

                    echo "该短信已成功发送！请勿重复提交！";

                   exit();

                }else{

              //判断重复短信功能代码结束





		$this->load->helper(sms);

		if($sms_id){

			//获取配置信息

			$sms_config = $this->common->getRow("select sms_int,account,password from ".$this->common->table('sms_config')." where sms_id = $sms_id");

			$func_name = 'sms_'.$sms_config['sms_int'];

			$send_phone = implode(",", $pat_phone);

			if ( function_exists($func_name)){



				$msg = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

				$send_status = $func_name($send_phone,$msg,$sms_config['account'],$sms_config['password']);

				$status = $this->lang->line('sms_send_status');

				if($send_status >= 0)

				{

					$msg_detail = "短信发送成功！";

					$send_status = 0;

				}

				else

				{

					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";

				}

			}else{

				$msg_detail = "短信发送失败，失败原因：没有可用关口！";

				$send_status = 108;

			}

		}else{

			$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

			$func_name = 'sms_'.$hospital['sms_int'];

			$send_phone = implode(";", $pat_phone);

			if ( function_exists($func_name)){



				$msg = mb_convert_encoding($sms_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

				$send_status = sms_jianzhou($send_phone,$msg,$hospital['sms_name'],$hospital['sms_pwd']);

				$status = $this->lang->line('sms_send_status');

				if($send_status >=0)

				{

					$msg_detail = "短信发送成功！";

					$send_status = 0;

				}

				else

				{

					$msg_detail = "短信发送失败，失败原因为：" . $status[$send_status] . "！";

				}

			}else{

				$msg_detail = "短信发送失败，失败原因：没有可用关口！";

				$send_status = 108;

			}

		}

		$this->db->insert($this->common->table('sms_send'), array('send_content' => $sms_content, 'send_time' => time(), 'send_type' => 1, 'type_value' => $order_id, 'send_phone' => $send_phone, 'hos_id' => $hos_id, 'keshi_id' => $keshi_id, 'admin_id' => $_COOKIE['l_admin_id'], 'admin_name' => $_COOKIE['l_admin_name'], 'send_status' => $send_status));



		echo $msg_detail;

                }

	}



         //短信群发功能 代码开始

        public function sms_send_all(){









		$data = array();

		$data           = $this->common->config('sms_send_all');

		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';//日期

		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;//预约医院编号

		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;//科室编号

		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;

		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;

		$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';

		$status         = isset($_REQUEST['s'])? intval($_REQUEST['s']):0;

		$bind           = isset($_REQUEST['wx'])? intval($_REQUEST['wx']):0;

		$type_id        = isset($_REQUEST['o_t'])? intval($_REQUEST['o_t']):0;

		$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;

		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;

		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;

		$wu             = isset($_REQUEST['wu'])? intval($_REQUEST['wu']):0;

		$type           = isset($_REQUEST['type'])? trim($_REQUEST['type']):'';

		$pro           	= isset($_REQUEST['province'])? intval($_REQUEST['province']):0;

		$city           = isset($_REQUEST['city'])? intval($_REQUEST['city']):0;

		$are            = isset($_REQUEST['area'])? intval($_REQUEST['area']):0;



		/* 未定患者 */

		$order_time_type      = isset($_REQUEST['order_time_type'])? intval($_REQUEST['order_time_type']):0;



		$data['hos_id']   = $hos_id;

		$data['keshi_id'] = $keshi_id;

		$data['f_p_i']    = $from_parent_id;

		$data['f_i']      = $from_id;

		$data['a_i']      = $asker_name;

		$data['s']        = $status;

		$data['wx']        = $bind;

		$data['o_t']      = $type_id;

		$data['t']        = $order_type;

		$data['p_jb']     = $p_jb;

		$data['jb']       = $jb;

//判断日期是否为空

		if(!empty($date))

		{

			$date = explode(" - ", $date);

			$start = $date[0];

			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;

			$data['end'] = $end;

			$start = str_replace(array("年", "月", "日"), "-", $start);//把初始日期中的年月日替换成—

			$end = str_replace(array("年", "月", "日"), "-", $end);

			$start = substr($start, 0, -1);

			$end = substr($end, 0, -1);

			$data['start_date'] = $start;

			$data['end_date'] = $end;

			setcookie('start_time', strtotime($start));

			setcookie('end_time', strtotime($end));

		}

		else

		{

			if(isset($_COOKIE['start_time']))

			{

				$start = date("Y-m-d", $_COOKIE['start_time']);

				$end = date("Y-m-d", $_COOKIE['end_time']);

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);

				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);

			}

			else

			{

				$start = date("Y-m-d", time());

				$end = date("Y-m-d", time());

				$data['start_date'] = $start;

				$data['end_date'] = $end;

				$data['start'] = date("Y年m月d日", time());

				$data['end'] = date("Y年m月d日", time());

			}

		}

                      //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，

                //而$[start_date]表示xx-xx-xx这种格式的日期







                //以下是按照$_COOKIE["l_hos_id"]和$_COOKIE['l_keshi_id']获取相应结果列表

		$hospital = $this->model->hospital_order_list();

		$keshi = $this->model->keshi_order_list();

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



		$from_list = $this->model->from_order_list(-1);

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

                //获取订单类别表Order_type中的所有数据

		$type_list = $this->model->type_order_list();



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



		$hos_id_arr = array();

		$keshi_id_arr = array();

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



		$data['from_arr'] = $from_arr;

		$data['keshi'] = $keshi_arr;

		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;

		$hos_auth = array();

		foreach($hospital as $val)

		{

			$hos_id_arr[] = $val['hos_id'];

			if($val['ask_auth']){

				$hos_auth[] = $val['hos_id'];

			}

		}



		foreach($keshi as $val)

		{

			$keshi_id_arr[] = $val['keshi_id'];

		}



		$page=0;

		$per_page =300;





		/* 处理判断条件 */

		$where = 1;

		$orderby = '';

		if(empty($hos_id))

		{

			if(!empty($_COOKIE["l_hos_id"]))

			{

				$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';

			}

		}

		else

		{

			$where .= ' AND o.hos_id = ' . $hos_id;

		}



		if(empty($keshi_id))

		{

			if(!empty($_COOKIE["l_keshi_id"]))

			{

				$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";

			}

		}

		else

		{

			$where .= ' AND o.keshi_id = ' . $keshi_id;

		}





		if(!empty($from_parent_id))

		{

			$where .= ' AND o.from_parent_id = ' . $from_parent_id;

		}



		if(!empty($from_id))

		{

			$where .= ' AND o.from_id = ' . $from_id;

		}



		if(!empty($p_jb))

		{

			$where .= ' AND o.jb_parent_id = ' . $p_jb;

		}





		if(!empty($jb))

		{

			$where .= ' AND o.jb_id = ' . $jb;

		}



		if(!empty($asker_name))

		{

			$where .= " AND o.admin_name = '" . $asker_name . "'";

		}



		/* 订单状态 */

		if($status == 1)

		{

			$where .= ' AND o.is_come = 0';

		}

		elseif($status == 2)

		{

			$where .= ' AND o.is_come = 1';

		}

		elseif($status == 3)

		{

			$where .= ' AND o.doctor_time > 0';

		}



		if($bind == 1){



			$where .= ' AND p.pat_weixin is null';

		}elseif($bind == 2){



			$where .= ' AND  p.pat_weixin <> ""';

		}



		if(!empty($pro)&&is_numeric($pro)){

			$where .= ' AND  p.pat_province = '.$pro;

			$data['pro'] = $pro;

		}else{

			$data['pro'] = 0;

		}

		if(!empty($city)&&is_numeric($city)){

			$where .= ' AND  p.pat_city = '.$city;

			$data['city'] = $city;

		}

		if(!empty($are)&&is_numeric($are)){

			$where .= ' AND  p.pat_area = '.$are;

			$data['are'] = $are;

		}



		if(!empty($type_id))

		{

			$where .= " AND o.type_id = $type_id";

		}





		if($wu == 1)

		{

			$w_start = strtotime($start . ' 00:00:00') - 43200;

			$w_end = strtotime($end . ' 11:59:59');

		}

		else

		{

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');

		}













               $where .= " AND p.pat_phone!='' AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;

			$orderby = ' order by o.is_come asc';



                //从预约列表中获取相关数据

		$order_list = $this->model->order_list($where, $page, $per_page, $orderby);









		$data['huifang'] = $this->set_huifang();





		$data['order_list'] = $order_list;





		$data['type_list'] = $type_list;

		$data['from_list'] = $from_list;

		$data['hospital'] = $hospital;

		$data['hos_auth'] = $hos_auth;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		 $this->load->view('sms/sms_send_all',$data);













        }







        //短信群发功能 代码结束



	public function miss_patient()

	{

		$data = array();

		$data = $this->common->config('miss_patient');



		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;





		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';



		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;



		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;



		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;



		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;



		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;



		if(1 == $tj_lx){



			$tag_str = '%u';

		}else if(2 == $tj_lx){

			$tag_str = '%c';

		}else{

			$tag_str = '%d';

		}



		// 组装where条件

		$where = 1;







		if(!empty($keshi_id))

		{

			$where .= " AND o.keshi_id = ".$keshi_id;

		}

		if(!empty($hos_id))

		{

			$where .= " AND o.hos_id = ".$hos_id;

		}

		if(!empty($from_parent_id))



		{



			$where .= ' AND o.from_parent_id = ' . $from_parent_id;



		}







		if(!empty($from_id))



		{



			$where .= ' AND o.from_id = ' . $from_id;



		}







		if(!empty($p_jb))



		{



			$where .= ' AND o.jb_parent_id = ' . $p_jb;



		}







		if(!empty($jb))



		{



			$where .= ' AND o.jb_id = ' . $jb;



		}





		//组装时间参数，以月为单位进行统计，默认显示当前月的数据



		if(!empty($date))



		{



			$date = explode(" - ", $date);



			$start = $date[0];



			$end = $date[1];


			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }



			$data['start'] = $start;



			$data['end'] = $end;



			$start = str_replace(array("年", "月", "日"), "-", $start);



			$end = str_replace(array("年", "月", "日"), "-", $end);



			$start = substr($start, 0, -1);



			$end = substr($end, 0, -1);



			$data['start_date'] = $start;



			$data['end_date'] = $end;



			setcookie('start_time', strtotime($start));



			setcookie('end_time', strtotime($end));



		}



		else



		{



			if(isset($_COOKIE['start_time']))



			{



				$start = date("Y-m-d", $_COOKIE['start_time']);



				$end = date("Y-m-d", $_COOKIE['end_time']);



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);



				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);



			}



			else



			{



				$start = date("Y-m-d", time());



				$end = date("Y-m-d", time());



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", time());



				$data['end'] = date("Y年m月d日", time());



			}



		}



		// end 时间组装



		// 预约来源

		$from_list = $this->model->from_order_list(-1);



		$par_from_arr = array();



		$from_arr = array();



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



		$jibing = read_static_cache('jibing_list');



		$jibing_list = array();



		$jibing_parent = array();

		$jb_arr = array();

		if(!empty($jibing))



		{



			foreach($jibing as $val)



			{

				$jb_arr[$val['jb_id']] = $val['jb_name'];



				$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];



				$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];



				if($val['jb_level'] == 2)



				{



					$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];



					$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];



				}



			}



		}







		$data['jibing'] = $jibing_list;

		$data['jb_arr'] = $jb_arr;

		$data['jibing_parent'] = $jibing_parent;



		$from_list = $par_from_arr;

		$data['from_list'] = $from_list;

		$data['p_jb'] = $p_jb;

		$data['jb'] = $jb;

		$data['f_p_i'] = $from_parent_id;

		$data['f_i'] = $from_id;

		$hospital = $this->model->hospital_order_list();

		$data['hospital'] = $hospital;

		$data['hos_id'] = $hos_id;

		$data['keshi_id'] = $keshi_id;



		$w_start = strtotime($start . ' 00:00:00');

		$w_end = strtotime($end . ' 23:59:59');



		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,1,$tag_str); //统计到诊率

		$order_feibu = $this->model->order_count_data($w_start,$w_end,$where,1); //统计分布情况



		$order_jb = $this->model->order_count_data($w_start,$w_end,$where,6); //到诊病种分布

		$lin = 0;

		foreach($order_jb as $val){



			$lin += $val['count'];

		}

		$little = array();

		$big = array();

		$little_c = 0;

		foreach($order_jb as $val){



			if($val['count']<$lin*0.05){

				$little[] = $val;

				$little_c += $val['count'];

			}else{

				$big[] = $val;

			}

		}



		$data['little'] = $little;

		$data['big'] = $big;

		$data['little_c'] = $little_c;

		$data['order'] = $order_count;

		$data['feibu'] = $order_feibu;



		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('miss_patient', $data);





	}

	private function set_huifang()

	{

		$data = array();

		$data['zt'] = array(

						1	=>	'确定预约时间',

						2	=>	'了解未到诊原因',

						3	=>	'了解未消费原因',

						4	=>	'去电详细咨询',

						);

		$data['lx'] = array(

						1	=>	'未预约回访',

						2	=>	'未到诊回访',

						3	=>	'未消费回访',

						4	=>	'到诊前回访',

						5	=>	'到诊后回访',

						6	=>	'手术前回访',

						7	=>	'手术后回访',

						8	=>	'手术后复查',

						9	=>	'活动回访',

						10	=>	'投拆回访',

						11	=>	'其他',

						);

		$data['jg'] = array(

						1	=>	'接通',

						2	=>	'关机',

						3	=>	'无人接听',

						4	=>	'拒接',

						5	=>	'停机/无法接通',

						6	=>	'空号/错号',

						);

		$data['ls'] = array(

						1	=>	'无',

						2	=>	'竞争对手',

						3	=>	'公立医院',

						4	=>	'自然流失',

						);

		return $data;

	}

		public function miss_order()

		{

		$data = array();

		$data = $this->common->config('miss_order');



		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;





		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';



		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;



		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;



		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;



		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;



		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;



		if(1 == $tj_lx){



			$tag_str = '%u';

		}else if(2 == $tj_lx){

			$tag_str = '%c';

		}else{

			$tag_str = '%d';

		}



		// 组装where条件

		$where = 1;







		if(!empty($keshi_id))

		{

			$where .= " AND o.keshi_id = ".$keshi_id;

		}

		if(!empty($hos_id))

		{

			$where .= " AND o.hos_id = ".$hos_id;

		}

		if(!empty($from_parent_id))



		{



			$where .= ' AND o.from_parent_id = ' . $from_parent_id;



		}







		if(!empty($from_id))



		{



			$where .= ' AND o.from_id = ' . $from_id;



		}







		if(!empty($p_jb))



		{



			$where .= ' AND o.jb_parent_id = ' . $p_jb;



		}







		if(!empty($jb))



		{



			$where .= ' AND o.jb_id = ' . $jb;



		}





		//组装时间参数，以月为单位进行统计，默认显示当前月的数据



		if(!empty($date))



		{



			$date = explode(" - ", $date);



			$start = $date[0];



			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }



			$data['start'] = $start;



			$data['end'] = $end;



			$start = str_replace(array("年", "月", "日"), "-", $start);



			$end = str_replace(array("年", "月", "日"), "-", $end);



			$start = substr($start, 0, -1);



			$end = substr($end, 0, -1);



			$data['start_date'] = $start;



			$data['end_date'] = $end;



			setcookie('start_time', strtotime($start));



			setcookie('end_time', strtotime($end));



		}



		else



		{



			if(isset($_COOKIE['start_time']))



			{



				$start = date("Y-m-d", $_COOKIE['start_time']);



				$end = date("Y-m-d", $_COOKIE['end_time']);



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);



				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);



			}



			else



			{



				$start = date("Y-m-d", time());



				$end = date("Y-m-d", time());



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", time());



				$data['end'] = date("Y年m月d日", time());



			}



		}



		// end 时间组装



		// 预约来源

		$from_list = $this->model->from_order_list(-1);



		$par_from_arr = array();



		$from_arr = array();



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







		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;



		$from_list = $par_from_arr;

		$data['from_list'] = $from_list;

		$data['p_jb'] = $p_jb;

		$data['jb'] = $jb;

		$data['f_p_i'] = $from_parent_id;

		$data['f_i'] = $from_id;

		$hospital = $this->model->hospital_order_list();

		$data['hospital'] = $hospital;

		$data['hos_id'] = $hos_id;

		$data['keshi_id'] = $keshi_id;



		$w_start = strtotime($start . ' 00:00:00');

		$w_end = strtotime($end . ' 23:59:59');



		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,1,$tag_str); //统计到诊率

		//回访数统计

		$order_huifan = $this->model->order_count_data($w_start,$w_end,$where,2,1,$tag_str);

		$order_huifan_only = $this->model->order_count_data($w_start,$w_end,$where,7,1,$tag_str);





		$order_res = $this->model->order_count_data($w_start,$w_end,$where,3); //



		$dao_false = $this->common->getAll("SELECT * FROM " . $this->common->table('dao_false') . " ORDER BY false_order ASC, false_id DESC");

		foreach($dao_false as $val){



			$dao[$val['false_id']] = $val['false_name'];

		}

		$s = date('d',$w_start);

		$e = date('d',$w_end);

		$end = $e - $s +1;

		$hf = array();

		//数组长度

		$count = count($order_huifan);

		if($count<$end){

			$tag = array();

			$res = array();

			foreach($order_huifan as $val){



				$tag[] = intval($val['tag']);



				$res[intval($val['tag'])] = $val;



			}

			for($i=0;$i<$end;$i++){



			if(in_array($i+1,$tag)){

				$hf[$i] = $res[$i+1];



			}else{







				$hf[$i] = array('count'=>0,'come'=>0,'tag'=>$i+1);

			}

			}

		}else{



			$hf = $order_huifan;

		}

		$hf_o = array();

		$count = count($order_huifan_only);

		if($count<$end){

			$tag = array();

			$res = array();

			foreach($order_huifan_only as $val){



				$tag[] = intval($val['tag']);



				$res[intval($val['tag'])] = $val;



			}

			for($i=0;$i<$end;$i++){



			if(in_array($i+1,$tag)){

				$hf_o[$i] = $res[$i+1];



			}else{







				$hf_o[$i] = array('count'=>0,'oid'=>0,'come'=>0,'tag'=>$i+1);

			}

			}

		}else{



			$hf_o = $order_huifan_only;

		}



		$data['res'] = $order_res;

		$data['dao_false'] = $dao;

		$data['order_huifan'] = $hf;

		$data['order_huifan_only'] = $hf_o;

		$data['order'] = $order_count;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('miss_order', $data);



		}



		public function order_data()

		{

		$data = array();

		$data = $this->common->config('order_data');



		$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;



		$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;





		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';



		$from_parent_id = isset($_REQUEST['f_p_i'])? intval($_REQUEST['f_p_i']):0;



		$from_id        = isset($_REQUEST['f_i'])? intval($_REQUEST['f_i']):0;



		$p_jb           = isset($_REQUEST['p_jb'])? intval($_REQUEST['p_jb']):0;



		$jb             = isset($_REQUEST['jb'])? intval($_REQUEST['jb']):0;



		$tj_lx             = isset($_REQUEST['tj_lx'])? intval($_REQUEST['tj_lx']):0;



		if(1 == $tj_lx){



			$tag_str = '%u';

		}else if(2 == $tj_lx){

			$tag_str = '%c';

		}else{

			$tag_str = '%d';

		}

		// 组装where条件

		$where = 1;







		if(!empty($keshi_id))

		{

			$where .= " AND o.keshi_id = ".$keshi_id;

		}

		if(!empty($hos_id))

		{

			$where .= " AND o.hos_id = ".$hos_id;

		}

		if(!empty($from_parent_id))



		{



			$where .= ' AND o.from_parent_id = ' . $from_parent_id;



		}







		if(!empty($from_id))



		{



			$where .= ' AND o.from_id = ' . $from_id;



		}







		if(!empty($p_jb))



		{



			$where .= ' AND o.jb_parent_id = ' . $p_jb;



		}







		if(!empty($jb))



		{



			$where .= ' AND o.jb_id = ' . $jb;



		}





		//组装时间参数，以月为单位进行统计，默认显示当前月的数据



		if(!empty($date))



		{



			$date = explode(" - ", $date);



			$start = $date[0];



			$end = $date[1];


			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


			$data['start'] = $start;



			$data['end'] = $end;



			$start = str_replace(array("年", "月", "日"), "-", $start);



			$end = str_replace(array("年", "月", "日"), "-", $end);



			$start = substr($start, 0, -1);



			$end = substr($end, 0, -1);



			$data['start_date'] = $start;



			$data['end_date'] = $end;



			setcookie('start_time', strtotime($start));



			setcookie('end_time', strtotime($end));



		}



		else



		{



			if(isset($_COOKIE['start_time']))



			{



				$start = date("Y-m-d", $_COOKIE['start_time']);



				$end = date("Y-m-d", $_COOKIE['end_time']);



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);



				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);



			}



			else



			{



				$start = date("Y-m-d", time());



				$end = date("Y-m-d", time());



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", time());



				$data['end'] = date("Y年m月d日", time());



			}



		}



		// end 时间组装



		// 预约来源

		$from_list = $this->model->from_order_list(-1);



		$par_from_arr = array();



		$from_arr = array();



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



		$jibing = read_static_cache('jibing_list');



		$jibing_list = array();



		$jibing_parent = array();



		$jb_arr = array();



		if(!empty($jibing))



		{



			 //根据科室查询疾病
			if(!empty($data['keshi_id'])){
				$keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = ".$data['keshi_id']);
				foreach($jibing as $val)
				{   $jb_exit = 0;
					foreach($keshi_jb as $keshi_jb_val)
					{
						if(strcmp($keshi_jb_val['jb_id'],$val['parent_id']) == 0){
							 $jb_exit =$val['jb_id'];break;
						}
					}
					if(!empty($jb_exit)){
						$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
						if($val['jb_level'] == 2)
						{
							$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
							$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						}
					}
				}

			}else{
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


		}







		$data['jibing'] = $jibing_list;

		$data['jibing_parent'] = $jibing_parent;



		$from_list = $par_from_arr;

		$data['from_list'] = $from_list;

		$data['p_jb'] = $p_jb;

		$data['jb'] = $jb;

		$data['f_p_i'] = $from_parent_id;

		$data['f_i'] = $from_id;

		$hospital = $this->model->hospital_order_list();

		$data['hospital'] = $hospital;

		$data['hos_id'] = $hos_id;

		$data['keshi_id'] = $keshi_id;



		$w_start = strtotime($start . ' 00:00:00');

		$w_end = strtotime($end . ' 23:59:59');



		$order_list = $this->model->order_count_data($w_start,$w_end,$where,4,1,$tag_str); // 预定未定统计

		$order_count = $this->model->order_count_data($w_start,$w_end,$where,0,2,$tag_str); //遇到到诊统计

		//预约病种统计

		$order_jb = $this->model->order_count_data($w_start,$w_end,$where,5);	//病种统计

		$lin = 0;

		foreach($order_jb as $val){



			$lin += $val['count'];

		}

		$little = array();

		$big = array();

		$little_c = 0;

		foreach($order_jb as $val){



			if($val['count']<$lin*0.05){

				$little[] = $val;

				$little_c += $val['count'];

			}else{

				$big[] = $val;

			}

		}

		$data['little'] = $little;

		$data['big'] = $big;

		$data['little_c'] = $little_c;



		$data['order'] = $order_count;

		$data['order_list'] = $order_list;

		$data['jb_arr'] = $jb_arr;

		$data['order_jb'] = $order_jb;

		$data['top'] = $this->load->view('top', $data, true);

		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

		$this->load->view('order_data', $data);



		}



		//对话登记

		public function talk_add()

		{

			$data = array();

			$data = $this->common->config('talk_add');

			$data['add_id'] = $_COOKIE['l_admin_id'];

			$data['add_name'] = $_COOKIE['l_admin_name'];



			//预约途径

			$from_list = $this->model->from_order_list(-1);



			$par_from_arr = array();



			$from_arr = array();



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







			$data['from_list'] = $from_list;

			$data['info'] = '';



			$data['top'] = $this->load->view('top', $data, true);

			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

			$this->load->view('talk_add', $data);

		}



		public function talk_update()

		{



			$admin_id = isset($_REQUEST['l_admin_id'])? intval($_REQUEST['l_admin_id']):0;



			$from_id = isset($_REQUEST['zx_from'])? intval($_REQUEST['zx_from']):0;



			$date = isset($_REQUEST['zx_data'])? strval($_REQUEST['zx_data']):'';



			$num = isset($_REQUEST['zx_num'])? intval($_REQUEST['zx_num']):0;



			$form_action = $_REQUEST['form_action'];



			$timetag = strtotime($date);

			$arr = array('admin_id' => $admin_id,

		                     'from_id' => $from_id,

				     'date' => $timetag,

				     'num' => $num,

					 );

			if($form_action == 'add'){



				$sql = "select talk_id from " .$this->common->table('talk'). " where date = $timetag and admin_id = $admin_id";

				$row = $this->common->getOne($sql);

				if($row){

					$talk_id = $row;

					$this->db->update($this->db->dbprefix . "talk", $arr, array('talk_id' => $talk_id));

				}else{



				$this->db->insert($this->db->dbprefix . "talk", $arr);



				$talk_id = $this->db->insert_id();



				}



			}

			if($talk_id){

				$this->common->msg($this->lang->line('success'));

			}else{



				$this->common->msg('操作失败');

			}

		}

                //添加对话数 代码开始

                public function add_talk(){

                    $admin_name=isset($_REQUEST['admin_name'])?trim($_REQUEST['admin_name']):'';

                    $from_id=isset($_REQUEST['from_id'])?intval($_REQUEST['from_id']):0;

                    $talk_date=isset($_REQUEST['talk_date'])?strtotime($_REQUEST['talk_date']):0;

                    $talk_num=isset($_REQUEST['talk_num'])?intval($_REQUEST['talk_num']):0;

                    $arr=array(

                        'talk_id'=>'',

                        'admin_name'=>$admin_name,

                        'from_id'=>$from_id,

                        'talk_date'=>$talk_date,

                        'talk_num'=>$talk_num



                    );

                    $this->db->trans_start();

                    $rs=$this->db->insert($this->common->table('talk'),$arr);

                    $this->db->trans_complete();

                    if($rs){

                        $links[0]=array('href' => '?c=order&m=zx_data', 'text' =>'咨询分析 ');

                        $this->common->msg('添加对话数成功！返回页面',0,$links);

                    }else{

                        $this->common->msg('添加对话数失败！返回页面',0);

                    }

                }

                //添加对话数 代码结束

                //个人每月对话数，代码开始

                public function person_talk(){









                    $start=date("Y-m-d",time())." 23:59:59";

                    $t=strtotime(date("Y-m-d",time()))-30*24*60*60;



                    $time_start=$t;

                    $time_end=strtotime($start);

                    $st=intval(date("d",$time_start));

                    $en=intval(date("d",$time_end));



                    $hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

                    $admin_name=isset($_REQUEST['admin_name'])?trim($_REQUEST['admin_name']):'';

                    $sql = "select admin_name,hos_id,COUNT(*) AS count, SUM(IF(is_come, 1, 0)) AS come, SUM(IF(order_time=0,1,0)) AS Ncome,SUM(IF(order_time!=0,1,0)) AS Ycome, FROM_UNIXTIME(order_addtime, '%Y-%m-%d' ) as tag from " .$this->common->table('order') . " where admin_name=".$admin_name." AND hos_id=".$hos_id." AND order_addtime >= " . $time_start . " AND order_addtime < " . $time_end ." group by tag";

                    $rs=$this->common->getAll($sql);

                    $query="select sum(talk_num) as total,talk_date from ".$this->common->table('talk')." where admin_name=".$admin_name." AND talk_date>=".$time_start." AND talk_date<".$time_end." group by talk_date ";

                    $rs1=$this->common->getALL($query);

                    $list = array();



			$null = array('count' => 0,'come' => 0,'Ncome' => 0,'Ycome' => 0);



			foreach($rs as $val){

				$list[$val['admin_name']][strtotime($val['tag'])] = $val;

			}

			$res = array();

			foreach($list as $key=>$val){

				for($i=$time_start; $i<=$time_end; $i+=24*60*60){

					if(array_key_exists($i,$val)){



						$res[$i] = $val[$i];

                                                foreach($rs1 as $k=>$v){

                                                    if($i==$v['talk_date']){

                                                        $res[$i]['total']=$v['total'];

                                                    }

                                                }

					}else{



						$res[$i] = $null;

                                                foreach($rs1 as $k=>$v){

                                                    if($i==$v['talk_date']){

                                                        $res[$i]['total']=$v['total'];

                                                    }

                                                }

					}

				}

			}

                    $data=array();

                    $data['rs']=$res;

                    $data['st']=$st;

                    $data['en']=$en;

                    $this->load->view("person_talk/person_talk",$data);





                }





















                //个人每月对话数，代码结束

		public function zx_data()

		{

			$data = array();

			$data = $this->common->config('zx_data');

			$hospital = $this->common->get_hosname();

			$asker_list = $this->model->asker_list(0);



			$data['asker_list'] = $asker_list;

                        //预约途径 代码开始

			$from_list = $this->model->from_order_list(-1);



			$par_from_arr = array();



			$from_arr = array();



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







			$data['from_list'] = $from_list;

                    //预约途径代码结束



			$date= isset($_REQUEST['date'])? trim($_REQUEST['date']):'';



			$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):$hospital[0]['hos_id'];



			$sel = isset($_REQUEST['sel'])? strval($_REQUEST['sel']):'count';



			$data['hos_id'] = $hos_id;



			$data['sel'] = $sel;



			if(!empty($date))



			{



				$date = explode(" - ", $date);



				$start = $date[0];



				$end = $date[1];


				 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


				$data['start'] = $start;



				$data['end'] = $end;



				$start = str_replace(array("年", "月", "日"), "-", $start);



				$end = str_replace(array("年", "月", "日"), "-", $end);



				$start = substr($start, 0, -1);



				$end = substr($end, 0, -1);



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				setcookie('start_time', strtotime($start));



				setcookie('end_time', strtotime($end));



			}



			else



			{



				if(isset($_COOKIE['start_time']))



				{



					$start = date("Y-m-d", $_COOKIE['start_time']);



					$end = date("Y-m-d", $_COOKIE['end_time']);



					$data['start_date'] = $start;



					$data['end_date'] = $end;



					$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);



					$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);



				}



				else



				{



					$start = date("Y-m-d", time());



					$end = date("Y-m-d", time());



					$data['start_date'] = $start;



					$data['end_date'] = $end;



					$data['start'] = date("Y年m月d日", time());



					$data['end'] = date("Y年m月d日", time());



				}



			}

			$w_start = strtotime($start . ' 00:00:00');

			$w_end = strtotime($end . ' 23:59:59');



			$st = intval(date('d',$w_start));



			$en = intval(date('d',$w_end));





			$data['hospital'] = $hospital;



			$sql = "select admin_name,hos_id,COUNT(*) AS count, SUM(IF(is_come, 1, 0)) AS come, SUM(IF(order_time=0,1,0)) AS Ncome,SUM(IF(order_time!=0,1,0)) AS Ycome, FROM_UNIXTIME(order_addtime, '%d' ) as tag from " .$this->common->table('order') . " where hos_id = " .$hos_id . " AND order_addtime >= " . $w_start . " AND order_addtime <= " . $w_end ." group by admin_name,tag";



			$info = $this->common->getAll($sql);



			$list = array();



			$null = array('count' => 0,'come' => 0,'Ncome' => 0,'Ycome' => 0);



			foreach($info as $val){

				$list[$val['admin_name']][intval($val['tag'])] = $val;

			}

			$res = array();

			foreach($list as $key=>$val){

				for($i=$st; $i<=$en; $i++){

					if(array_key_exists($i,$val)){



						$res[$key][$i] = $val[$i];

					}else{



						$res[$key][$i] = $null;

					}

				}

			}

			$des = array();

			foreach($list as $key=>$val){

				$des[$key]['count'] = 0;

				$des[$key]['come'] = 0;

				$des[$key]['Ycome'] = 0;

				$des[$key]['Ncome'] = 0;

				foreach($val as $k=>$v){



					$des[$key]['count'] += $v['count'];

					$des[$key]['come'] += $v['come'];

					$des[$key]['Ycome'] += $v['Ycome'];

					$des[$key]['Ncome'] += $v['Ncome'];



				}





			}

                          //取出对话数 代码开始

                      $query="select admin_name, sum(talk_num) as all_talk  from ".$this->common->table('talk')." where talk_date>=".$w_start." and talk_date<=".$w_end." group by admin_name";

                        $rs=$this->common->getAll($query);

                        $talk=array();

                        foreach($rs as $k=>$val){



                            $talk[$val['admin_name']]['talk_num']=$val['all_talk'];

                        }

                        //取出对话数 代码结束

			$data['talk']=$talk;



			$sql_last = "select admin_name,hos_id,COUNT(*) AS count, SUM(IF(is_come, 1, 0)) AS come, SUM(IF(order_time=0,1,0)) AS Ncome,SUM(IF(order_time!=0,1,0)) AS Ycome from " .$this->common->table('order') . " where hos_id = " .$hos_id . " AND order_addtime >= " . $w_start . " AND order_addtime <= " . $w_end ." group by admin_name";



			$info_last = $this->common->getAll($sql_last);

			$count_s = 0;

			$come_s = 0;

			$Ycome_s = 0;

			foreach($info_last as $val){

				$count_s += $val['count'];

				$come_s += $val['come'];

				$Ycome_s += $val['Ycome'];

			}



			$data['info_last'] = $info_last;

			$data['count_s'] = $count_s;

			$data['come_s'] = $come_s;

			$data['Ycome_s'] = $Ycome_s;



			$data['st'] = $st;



			$data['en'] = $en;

			$data['res'] = $res;

			$data['des'] = $des;









			$data['top'] = $this->load->view('top', $data, true);

			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

			$this->load->view('zx_data', $data);

		}



		public function text(){

			$this->load->helper('url');

			$base_url = base_url();

			// 获取订单no



			$curlPost1 = '';

			$ch1 = curl_init();//初始化curl



			curl_setopt($ch1,CURLOPT_URL,$base_url.'?c=order&m=order_no_ajax');//抓取指定网页



			curl_setopt($ch1, CURLOPT_HEADER, 0);//设置header



			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上



			curl_setopt($ch1, CURLOPT_POST, 1);//post提交方式



			curl_setopt($ch1, CURLOPT_POSTFIELDS, $curlPost1);



			$data1 = curl_exec($ch1);//运行curl



			curl_close($ch1);



			$order_no = $data1;//输出结果

			// 医院 科室  医生名称 电话

			$keshi_id           = isset($_REQUEST['keshi_id'])? trim($_REQUEST['keshi_id']):'';



			$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):'';

			$doctor_name = isset($_REQUEST['doctor_name'])? strval($_REQUEST['doctor_name']):'';



			$pat_phone = isset($_REQUEST['pat_phone'])? strval($_REQUEST['pat_phone']):'';

			$pat_name = isset($_REQUEST['pat_name'])? strval($_REQUEST['pat_name']):'';



			$send_content = '测试';



			$curlPost = array('send_content'=>$send_content,'send_phone'=>$pat_phone,'hos_id'=>$hos_id,'keshi_id'=>$keshi_id);

			$ch = curl_init();//初始化curl



			curl_setopt($ch,CURLOPT_URL,$base_url.'?c=system&m=just_sms');//抓取指定网页



			curl_setopt($ch, CURLOPT_HEADER, 0);//设置header



			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上



			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式



			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);



			$data = curl_exec($ch);//运行curl



			curl_close($ch);



			if($data==0){



				$pat = array(	'pat_name' => $pat_name,



									'pat_phone' => $pat_phone,



									);

				$this->db->insert($this->common->table("patient"), $pat);

				$pat_id = $this->db->insert_id();

				$remark = array('order_no' => $order_no,



									'keshi_id' => $keshi_id,



									'hos_id' => $hos_id,



									'doctor_name' => $doctor_name,



									'pat_id' => $pat_id,

                                                                        'order_addtime' => time(),



									);



				$this->db->insert($this->common->table("order"), $remark);



				$order_id = $this->db->insert_id();



				if($order_id){

					$msg_detail = "短信预约成功！";

				}else{

					$msg_detail = "短信预约失败！";

				}

				$links[0] = array('href' => '?c=order&m=doc_show', 'text' => $this->lang->line('list_back'));

				$this->common->msg($msg_detail, 0, $links, true, false);

			}





		}



		public function doc_show(){



			$this->load->view('doc_show');



		}

				public function pat_order(){



			$enter_id = isset($_REQUEST['pat_id'])? intval($_REQUEST['pat_id']):0;

			$info = $this->common->getRow("select * from " . $this->common->table('pat_enter') . " where enter_id = ". $enter_id);

			$data = array();

			$data = $this->common->config('pat_order');

			$keshi = read_static_cache('keshi_list_true');

			if(empty($keshi)){

			$keshi_list = array(

				array(keshi_id=>1,keshi_name=>'炎症'),

				array(keshi_id=>2,keshi_name=>'计生'),

				array(keshi_id=>3,keshi_name=>'不孕'),

				array(keshi_id=>4,keshi_name=>'产科'),

				array(keshi_id=>5,keshi_name=>'内科'),

				array(keshi_id=>6,keshi_name=>'外科'),

				array(keshi_id=>7,keshi_name=>'男科'),

				array(keshi_id=>8,keshi_name=>'皮肤科'),

				array(keshi_id=>9,keshi_name=>'肛肠科'),

				array(keshi_id=>10,keshi_name=>'五官科'),

				array(keshi_id=>11,keshi_name=>'皮肤美容科'),

				array(keshi_id=>12,keshi_name=>'儿科'),

				array(keshi_id=>13,keshi_name=>'消化科'),

				array(keshi_id=>13,keshi_name=>'口腔科')

			);



			write_static_cache('keshi_list_true',$keshi_list);

			$keshi = $keshi_list;

			}



			$data['keshi'] = $keshi;

			$province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");

			$data['info'] = $info;

			$data['province'] = $province;

			$data['top'] = $this->load->view('top', $data, true);

			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

			$this->load->view('pat_order', $data);



		}



		public function pat_update(){







			$form_action     = $_REQUEST['form_action'];



			$enter_id        = isset($_REQUEST['enter_id'])? intval($_REQUEST['enter_id']):0;





			$remark          = trim($_REQUEST['remark']);





			$order_no        = trim($_REQUEST['order_no']);



			$keshi_id        = intval($_REQUEST['keshi_id']);



			$jb_parent_id    = trim($_REQUEST['jibing_parent_id']);







			$admin_id        = trim($_REQUEST['admin_id']);



			$from_parent_id  = trim($_REQUEST['from_parent_id']);



			$from_id         = trim($_REQUEST['from_id']);



			$is_first        = intval($_REQUEST['is_first']);







			$pat_name        = trim($_REQUEST['patient_name']);



			$pat_sex         = intval($_REQUEST['pat_sex']);



			$pat_phone       = trim($_REQUEST['patient_phone']);



			$pat_age         = trim($_REQUEST['patient_age']);











			$pat_province    = intval($_REQUEST['province']);



			$pat_city        = intval($_REQUEST['city']);



			$pat_area        = intval($_REQUEST['area']);



			$pat_address     = trim($_REQUEST['patient_address']);

			$doctor_name     = trim($_REQUEST['doctor_name']);





			$data = array(

						'order_no' => $order_no,  //预约号



						'keshi_id' => $keshi_id, //科室id



						'jb_parent_id' => $jb_parent_id,  //大病种





						//预约途径



						'from_parent_id' => $from_parent_id,

						'from_id' => $from_id,



						'admin_id' => $admin_id,

						'pat_name' => $pat_name,

						'pat_sex' => $pat_sex,

						'pat_phone' => $pat_phone,

						'pat_age' => $pat_age,



						'is_first' => $is_first,





						'doctor_name' => $doctor_name,



						'pat_province' => $pat_province,



						'pat_city'=>$pat_city,

						'pat_area'=>$pat_area,

						'pat_address'=>$pat_address,

						'remark'=>$remark,

						'add_time' =>time()



									);

			if($form_action=='update'){

				$links[0] = array('href' => '?c=order&m=pat_list', 'text' => $this->lang->line('list_back'));

				$pat_id = $this->db->update($this->common->table("pat_enter"), $data, array('enter_id' => $enter_id));

				if($pat_id){



				$this->common->msg('病人信息更改成功',0,$links);

				}

			}elseif($form_action=='add'){

				$pat_id = $this->db->insert($this->common->table("pat_enter"), $data);

				if($pat_id){



				$this->common->msg('病人信息登记成功');

				}



			}



		}



		public function pat_list(){



			$data = array();



			$data           = $this->common->config('pat_list');





		$keshi_id       = isset($_REQUEST['keshi_id'])? trim($_REQUEST['keshi_id']):'';





		$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';



		$date_get 		= $date;



		$from_parent_id = isset($_REQUEST['f_p_i'])? trim($_REQUEST['f_p_i']):0;



		$from_id        = isset($_REQUEST['f_i'])? trim($_REQUEST['f_i']):0;



		$p_jb           = isset($_REQUEST['p_jb'])? trim($_REQUEST['p_jb']):'';



		$patient_name           = isset($_REQUEST['patient_name'])? trim($_REQUEST['patient_name']):'';

		$admin_id           = isset($_REQUEST['admin_id'])? trim($_REQUEST['admin_id']):'';

		$patient_phone           = isset($_REQUEST['patient_phone'])? trim($_REQUEST['patient_phone']):'';

		$doctor_name           = isset($_REQUEST['doctor_name'])? trim($_REQUEST['doctor_name']):'';

		$order_no           = isset($_REQUEST['order_no'])? trim($_REQUEST['order_no']):'';



		// 组装where条件

		$where = 1;







		if(!empty($keshi_id))

		{

			$where .= " AND keshi_id = '".$keshi_id."'";

			$data['keshi_id'] = $keshi_id;

		}

		if(!empty($patient_name))

		{

			$where .= " AND pat_name = '".$patient_name."'";

			$data['patient_name'] = $patient_name;

		}

		if(!empty($doctor_name))

		{

//			$where .= " AND doctor_name = '".$doctor_name."'";

			$data['doctor_name'] = $doctor_name;

		}

		if(!empty($order_no))

		{

			$where .= " AND order_no = '".$order_no."'";

			$data['order_no'] = $order_no;

		}

		if(!empty($patient_phone))

		{

			$where .= " AND pat_phone = ".$patient_phone;

			$data['patient_phone'] = $patient_phone;

		}

		if(!empty($admin_id))

		{

//			$where .= " AND admin_id = '".$admin_id."'";

			$data['admin_id'] = $admin_id;

		}

		if(!empty($from_parent_id))



		{



			$where .= " AND from_parent_id = '". $from_parent_id."'";

			$data['from_parent_id'] = $from_parent_id;



		}



		if(!empty($from_id))



		{



			$where .= " AND from_id = '". $from_id."'";

			$data['from_id'] = $from_id;



		}











		if(!empty($p_jb))



		{



			$where .= " AND jb_parent_id = '". $p_jb."'";

			$data['p_jb'] = $p_jb;

		}









		//组装时间参数，以月为单位进行统计，默认显示当前月的数据



		if(!empty($date))



		{



			$date = explode(" - ", $date);



			$start = $date[0];



			$end = $date[1];

			 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }



			$data['start'] = $start;



			$data['end'] = $end;



			$start = str_replace(array("年", "月", "日"), "-", $start);



			$end = str_replace(array("年", "月", "日"), "-", $end);



			$start = substr($start, 0, -1);



			$end = substr($end, 0, -1);



			$data['start_date'] = $start;



			$data['end_date'] = $end;



			setcookie('start_time', strtotime($start));



			setcookie('end_time', strtotime($end));



		}



		else



		{



			if(isset($_COOKIE['start_time']))



			{



				$start = date("Y-m-d", $_COOKIE['start_time']);



				$end = date("Y-m-d", $_COOKIE['end_time']);



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);



				$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);



			}



			else



			{



				$start = date("Y-m-d", time());



				$end = date("Y-m-d", time());



				$data['start_date'] = $start;



				$data['end_date'] = $end;



				$data['start'] = date("Y年m月d日", time());



				$data['end'] = date("Y年m月d日", time());



			}



		}



		$w_start = strtotime($start . ' 00:00:00');

		$w_end = strtotime($end . ' 23:59:59');



		// end 时间组装



			$keshi = read_static_cache('keshi_list_true');

			$keshi_list = array();

			foreach($keshi as $val){

				$keshi_list[$val['keshi_id']] = $val['keshi_name'];





			}

			$data['keshi'] = $keshi;

			$data['keshi_list'] = $keshi_list;

			$data['f_p_i'] = $from_parent_id;

			$data['f_i'] = $from_id;

			$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;



			$data['now_page'] = $page;



			$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;



			$per_page = empty($per_page)? 30:$per_page;



			$this->load->library('pagination');



			$this->load->helper('page');

			$pat_list_count = $this->model->pat_list_count($w_start,$w_end,$where);



			$config = page_config();

			$config['base_url'] = '?c=order&m=pat_list&keshi_id='.$keshi_id.'&f_p_i='.$from_parent_id.'&f_i='.$from_id.'&p_jb='.$p_jb.'&date='.$date_get.'&patient_name='.$patient_name.'&admin_id='.$admin_id.'&patient_phone='.$patient_phone.'&doctor_name='.$doctor_name.'&order_no='.$order_no;

			$config['total_rows'] = $pat_list_count;



			$config['per_page'] = $per_page;



			$config['uri_segment'] = 10;



			$config['num_links'] = 5;



			$this->pagination->initialize($config);



			$pat_list = $this->model->pat_list($w_start,$w_end,$where, $page, $per_page);

			$data['page'] = $this->pagination->create_links();





			$data['pat_list'] = $pat_list;

			$data['top'] = $this->load->view('top', $data, true);

			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);

			$this->load->view('pat_list', $data);



		}

		private function create_card($img,$card_content,$name='')

		{



			if (!file_exists('static/upload/'.$img)) {

				return false;

			}

			$this->load->library('image_lib');

			$this->load->helper('url');

			$last = count($card_content)-1;

			if(empty($name)){

				$tmp = md5(uniqid(mt_rand()));

			}else{

				$tmp = $name;

			}

			foreach($card_content as $key=>$val){

				$config = array();

				if($key == 0){

					$config['image_library'] = 'gd2';

					$config['source_image'] = 'static/upload/'.$img;

					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';

					$config['wm_text'] = $val[0];

					$config['wm_font_size'] = '10';

					$config['wm_font_color'] = '070707';

					$config['wm_vrt_alignment'] = 'top';

					$config['wm_hor_alignment'] = 'left';

					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）

					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）

					$config['new_image'] = 'static/images/ok.jpg';

				}else if($key == $last){

					$config['image_library'] = 'gd2';

					$config['source_image'] = 'static/images/ok.jpg';

					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';

					$config['wm_text'] = $val[0];

					$config['wm_font_size'] = '10';

					$config['wm_font_color'] = '070707';

					$config['wm_vrt_alignment'] = 'top';

					$config['wm_hor_alignment'] = 'left';

					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）

					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）

					// 生成最终的图片名称

					$config['new_image'] = 'static/images/'.$tmp.'.jpg';

				}else{

					$config['image_library'] = 'gd2';

					$config['source_image'] = 'static/images/ok.jpg';

					$config['wm_font_path'] = dirname(__FILE__).'/simsun.ttc';

					$config['wm_text'] = $val[0];

					$config['wm_font_size'] = '10';

					$config['wm_font_color'] = '070707';

					$config['wm_vrt_alignment'] = 'top';

					$config['wm_hor_alignment'] = 'left';

					$config['wm_vrt_offset'] = $val[2];//指定一个垂直偏移量（以像素为单位）

					$config['wm_hor_offset'] = $val[1];//指定一个横向偏移量（以像素为单位）

					$config['new_image'] = 'static/images/ok.jpg';

				}

				$this->image_lib->initialize($config);

				$this->image_lib->watermark();

			}

			if(file_exists('static/images/'.$tmp.'.jpg')){

				$html = base_url().'static/images/'.$tmp.'.jpg';

				return $html;

			}else{

				return false;

			}

		}

		/*

		* 与phpcms交互

		*

		*/

		private function register($register)

		{

			$url = 'http://www.ra120.com/api.php';

			$code = sys_auth('action=number_add&username='.$register['username'].'&nickname='.$register['nickname'].'&password='.$register['password'],'ENCODE','c7FfDNAI8B7NEvd708LakK0AuSMHvhrU');

			$url .= '?op=uc&code='.$code;

			https_get($url);

		}

		private function group_change($username)

		{

			$url = 'http://www.ra120.com/api.php';

			$code = sys_auth('action=group_change&username='.$username,'ENCODE','c7FfDNAI8B7NEvd708LakK0AuSMHvhrU');

			$url .= '?op=uc&code='.$code;

			https_get($url);

		}


		public function setlyweb(){
			$order_id = isset($_REQUEST['order_id'])? trim($_REQUEST['order_id']):0;
		    $weburl        = isset($_REQUEST['weburl'])? trim($_REQUEST['weburl']):'';
		    if(intval($order_id ) >  0 && !empty($weburl)){
				$sql_1="select con_id,con_content from ".$this->common->table('ask_content')." where order_id  =".$order_id;
                $res=$this->common->getAll($sql_1);
				if(count($res) > 0){
					$update =array();
					$update['con_content'] = ' 进入:<a href="'. $weburl.'">'. $weburl.'</a>   '.$res[0]['con_content'];
					$this->db->update($this->common->table("ask_content"), $update, array('con_id' => $res[0]['con_id']));
					echo 1;exit;
				}else{
					$this->db->insert($this->common->table('ask_content'), array('order_id' => $order_id, 'con_addtime' => time(), 'con_content' =>' 进入:<a href="'. $weburl.'">'. $weburl.'</a>'));
                    if($this->db->insert_id() > 0){
					   echo 1;exit;
					}
				}
		    }
			echo 0;exit;
		}


		//通过电话和名称查询公海和 预约
		public function order_and_gonghaiorder_search(){
			$p_p = isset($_REQUEST['p_p'])? $_REQUEST['p_p']:0;
			$p_n = isset($_REQUEST['p_n'])? $_REQUEST['p_n']:0;

			$data = array();
			$patient = array();
			if(!empty($p_p) && !empty($p_n)){
				//$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE p.pat_name like '%".$p_n."%' or p.pat_phone like '%".$p_p."%'");
				$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE p.pat_name = '".$p_n."' or p.pat_phone = '".$p_p."'");
			}else if(!empty($p_p)){
				//$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE  p.pat_phone like '%".$p_p."%'");
				$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE  p.pat_phone = '".$p_p."'");
			}else if(!empty($p_n)){
				//$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE p.pat_name like '%".$p_n."%' ");
				$patient = $this->common->getAll("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE p.pat_name = '".$p_n."' ");
			}

			$patient_id_str= '';
			foreach ($patient as $patient_temp){
				if(empty($patient_id_str)){
					$patient_id_str =  $patient_temp['pat_id'];
				}else{
					$patient_id_str .= ','.$patient_temp['pat_id'];
				}
			}
			if(!empty($patient_id_str)){
				$data['gonghai_order'] = $this->common->getAll("SELECT o.pat_id,o.order_no,o.admin_name,o.hos_id,o.keshi_id,o.admin_id,o.order_addtime,o.order_time,o.come_time,o.is_come,h.hos_name,k.keshi_name FROM " . $this->common->table("gonghai_order") . " as o," . $this->common->table("hospital") . " as h," . $this->common->table("keshi") . " as k  WHERE o.pat_id in(".$patient_id_str.") and o.hos_id = h.hos_id and o.keshi_id = k.keshi_id ");
				$data['order'] = $this->common->getAll("SELECT o.pat_id,o.order_no,o.admin_name,o.hos_id,o.keshi_id,o.admin_id,o.order_addtime,o.order_time,o.come_time,o.is_come,h.hos_name,k.keshi_name FROM " . $this->common->table("order") . " as o," . $this->common->table("hospital") . " as h," . $this->common->table("keshi") . " as k  WHERE o.pat_id in(".$patient_id_str.") and o.hos_id = h.hos_id and o.keshi_id = k.keshi_id ");
				$data['ll_order'] = $this->common->getAll("SELECT o.pat_id,o.order_no,o.admin_name,o.hos_id,o.keshi_id,o.admin_id,o.order_addtime,o.order_time,o.come_time,o.is_come,h.hos_name,k.keshi_name FROM " . $this->common->table("order_liulian") . " as o," . $this->common->table("hospital") . " as h," . $this->common->table("keshi") . " as k  WHERE o.pat_id in(".$patient_id_str.") and o.hos_id = h.hos_id and o.keshi_id = k.keshi_id ");
				$data['ll_gh_order'] = $this->common->getAll("SELECT o.pat_id,o.order_no,o.admin_name,o.hos_id,o.keshi_id,o.admin_id,o.order_addtime,o.order_time,o.come_time,o.is_come,h.hos_name,k.keshi_name FROM henry_gonghai_liulian as o," . $this->common->table("hospital") . " as h," . $this->common->table("keshi") . " as k  WHERE o.pat_id in(".$patient_id_str.") and o.hos_id = h.hos_id and o.keshi_id = k.keshi_id ");
			}
			$data['rank_type'] = $this->model->rank_type();
			if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
				$l_admin_action  = array("179");
			}else{
				$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
			}
			if(!in_array('179',$l_admin_action)){
				foreach ($patient as $patient_key => $patient_temp){
					$patient[$patient_key]['pat_phone']  =  @$patient_temp['pat_phone'][0].@$patient_temp['pat_phone'][1].@$patient_temp['pat_phone'][2].'*****';
					$patient[$patient_key]['pat_phone1']  =  @$patient_temp['pat_phone1'][0].@$patient_temp['pat_phone1'][1].@$patient_temp['pat_phone1'][2].'*****';
				}
			}

			$order = $data['order'];
			$gonghai_order = $data['gonghai_order'];
			$ll_order = $data['ll_order'];
			//咨询只能看自己的电话 其他电话不可见
			if(false){
			//if($rank_type == 2){
				$keshi_check_ts = $this->config->item('keshi_check_ts');
				$keshi_check_ts = explode(",",$keshi_check_ts);
				$zixun_check_ts = $this->config->item('zixun_check_ts');
				$zixun_check_ts = explode(",",$zixun_check_ts);

				foreach ($patient as $patient_key => $patient_temp){
					$check = 0 ;
					foreach ($order as $item){
						if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $item['pat_id'] == $patient_temp['pat_id'] && $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts) && $_COOKIE['l_admin_id'] != $item['admin_id']){
							$patient[$patient_key]['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
							$patient[$patient_key]['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
							$check =1;
							break;
						}
					}
					if(empty($check)){
						foreach ($gonghai_order as $item){
							if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $item['pat_id'] == $patient_temp['pat_id'] &&   $item['hos_id'] == 3 && in_array($item['keshi_id'], $keshi_check_ts) && $_COOKIE['l_admin_id'] != $item['admin_id']){
								$patient[$patient_key]['pat_phone']  =  $item['pat_phone'][0].$item['pat_phone'][1].$item['pat_phone'][2].'*****';
								$patient[$patient_key]['pat_phone1']  =  $item['pat_phone1'][0].$item['pat_phone1'][1].$item['pat_phone1'][2].'*****';
								break;
							}
						}
					}
				}
			}
			$data['patient'] = $patient;

			$this->load->view('order_gonghaiorder_search', $data);
		}


		/**
		 * 公海数据导出
		 * **/
		public function gonghai_order_list_down(){
			$data = array();

			$data = $this->common->config('order_list_down');
			$date           = isset($_REQUEST['date'])? trim($_REQUEST['date']):'';
			$hos_id         = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
			$keshi_id       = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;
			$patient_name   = isset($_REQUEST['p_n'])? trim($_REQUEST['p_n']):'';
			$patient_phone  = isset($_REQUEST['p_p'])? trim($_REQUEST['p_p']):'';
			$order_no       = isset($_REQUEST['o_o'])? trim($_REQUEST['o_o']):'';
			$asker_name     = isset($_REQUEST['a_i'])? trim($_REQUEST['a_i']):'';
			$order_type     = isset($_REQUEST['t'])? intval($_REQUEST['t']):2;
			$gonghai_type          = isset($_REQUEST['gonghai_type'])? trim($_REQUEST['gonghai_type']):'';

			$data['hos_id']   = $hos_id;
			$data['keshi_id'] = $keshi_id;
			$data['p_n']      = $patient_name;
			$data['p_p']      = $patient_phone;
			$data['o_o']      = $order_no;
			$data['a_i']      = $asker_name;
			$data['t']        = $order_type;
			$data['gonghai_type ']        = $gonghai_type;
			if(!empty($date)){
				$date = explode(" - ", $date);
				$start = $date[0];
				$end = $date[1];

				 if(!$this->dateCheck($start) || !$this->dateCheck($end)){
			  $start = date("Y年m月d日");
			  $end = $start;
		   }


				$data['start'] = $start;
				$data['end'] = $end;
				$start = str_replace(array("年", "月", "日"), "-", $start);
				$end = str_replace(array("年", "月", "日"), "-", $end);
				$start = substr($start, 0, -1);
				$end = substr($end, 0, -1);
				$data['start_date'] = $start;
				$data['end_date'] = $end;
				setcookie('start_time', strtotime($start));
				setcookie('end_time', strtotime($end));
			}else{
				if(isset($_COOKIE['start_time'])){
					$start = date("Y-m-d", $_COOKIE['start_time']);
					$end = date("Y-m-d", $_COOKIE['end_time']);
					$data['start_date'] = $start;
					$data['end_date'] = $end;
					$data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
					$data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
				}else{
					$start = date("Y-m-d", time());
					$end = date("Y-m-d", time());
					$data['start_date'] = $start;
					$data['end_date'] = $end;
					$data['start'] = date("Y年m月d日", time() - 86400);
					$data['end'] = date("Y年m月d日", time());
				}
			}
			$hospital = $this->model->hospital_order_list();
			$keshi = $this->model->keshi_order_list();
			$keshi_arr = array();
			foreach($keshi as $val){
				$keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
				$keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
				$keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
				$keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
				$keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
				$keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
			}
			$from_list = $this->model->from_order_list(-1);
			$par_from_arr = array();
			$from_arr = array();
			foreach($from_list as $val){
				if($val['parent_id'] == 0){
					$par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
					$par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
					$par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
				}else{
					$from_arr[$val['from_id']]['from_id'] = $val['from_id'];
					$from_arr[$val['from_id']]['from_name'] = $val['from_name'];
					$from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
				}
			}
			$from_list = $par_from_arr;
			$from_list_son = $from_arr;
			$type_list = $this->model->type_order_list();
			$type_arr = array();
			foreach($type_list as $val){
				$type_arr[$val['type_id']]['type_id'] = $val['type_id'];
				$type_arr[$val['type_id']]['type_name'] = $val['type_name'];
				$type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
				$type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
				$type_arr[$val['type_id']]['type_order'] = $val['type_order'];
			}

			$type_list = $type_arr;
			$hos_id_arr = array();
			$keshi_id_arr = array();
			$jibing = read_static_cache('jibing_list');
			$jibing_list = array();
			$jibing_parent = array();
			if(!empty($jibing)){
				foreach($jibing as $val){
					$jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
					$jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
					$jibing_list[$val['jb_id']]['jb_code'] = $val['jb_code'];
					if($val['jb_level'] == 2){
						$jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
						$jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
						$jibing_parent[$val['jb_id']]['jb_code'] = $val['jb_code'];
					}
				}
			}

			$data['from_arr'] = $from_arr;
			$data['keshi'] = $keshi_arr;
			$data['jibing'] = $jibing_list;
			$data['jibing_parent'] = $jibing_parent;
			foreach($hospital as $val){
				$hos_id_arr[] = $val['hos_id'];
			}
			foreach($keshi as $val){
				$keshi_id_arr[] = $val['keshi_id'];
			}
			$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
			$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):30;
			$per_page = empty($per_page)? 30:$per_page;
			$this->load->library('pagination');
			$this->load->helper('page');

			/* 处理判断条件 */
			$where = 1;
			$orderby = '';
			if(empty($hos_id)){
				if(!empty($_COOKIE["l_hos_id"])){
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
			}else{
				$where .= ' AND o.hos_id = ' . $hos_id;
			}
			if(empty($keshi_id)){
				if(!empty($_COOKIE["l_keshi_id"])){
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}else{
				$where .= ' AND o.keshi_id = ' . $keshi_id;
			}
			if(!empty($asker_name)){
				$where .= " AND o.admin_name = '" . $asker_name . "'";
			}

            $w_start = strtotime($start . ' 00:00:00');
            $w_end = strtotime($end . ' 23:59:59');
            /* 时间条件 */
            if($order_type == 1){
                $where .= " AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end;
                $orderby .= ',o.order_addtime DESC ';
            }elseif($order_type == 2){
                $where .= " AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end;
                $orderby .= ',o.order_time DESC ';
            }elseif($order_type == 3){
                $where .= " AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end;
                $orderby .= ',o.come_time DESC ';
            }elseif($order_type == 4){
                $where .= " AND o.doctor_time >= " . $w_start . " AND o.doctor_time <= " . $w_end;
                $orderby .= ',o.doctor_time DESC ';
            } elseif ($order_type == 5) {
                //获取最新掉入公海的order_id
                //与gonghai_order表去比对order_id
                //存在的order_id都是还在公海里面的，不存在的基本是被捞取了的
                //由于公海查询开始时间如果超过当天会产生慢查询，所以通过时间判断避开
                $max_today = strtotime(date('Y-m-d 23:59:59'),time());
                if ($_COOKIE['start_time'] <= $max_today) {
                    $sql_dump = " select t.order_id from ".$this->common->table('gonghai_log')." as t where t.action_time=(select max(t1.action_time) from ".$this->common->table('gonghai_log')." as t1 where t.order_id = t1.order_id and t1.action_type = '掉入公海') and t.action_time >= ".$w_start." and t.action_time <= ".$w_end." and t.action_type = '掉入公海' " ;
                    $dump_res=$this->common->getAll($sql_dump);
                    if (!empty($dump_res)) {
                        $dump_order_id = '';
                        foreach ($dump_res as $key => $value) {
                            $dump_order_id .= $value['order_id'].',';
                        }

                        $dump_order_id = substr($dump_order_id, 0, -1);

                        //p($dump_order_id);exit();

                        $where.= " AND o.order_id IN (".$dump_order_id.")";
                    } else {
                        $where.= " AND o.order_id IN ('')";
                    }
                } else {
                    $where.= " AND o.order_id IN ('')";
                }
            } else {
                $where .= " AND o.order_time >= " . $_COOKIE['start_time'] . " AND o.order_time <= " . $_COOKIE['end_time'];
                $orderby .= ',o.order_time DESC ';
            }
			/* 当输入患者的信息时，其他的搜索条件都不需要了 */
			if(!empty($patient_name)){
				$where = " p.pat_name = '". $patient_name . "'";
				$data['p_n']      = $patient_name;
				$data['p_p']      = "";
				$data['o_o']      = "";
				if(!empty($_COOKIE["l_hos_id"])){
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
				if(!empty($_COOKIE["l_keshi_id"])){
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}
			if(!empty($patient_phone)){
				$where = " p.pat_phone = '". $patient_phone . "'";
				$data['p_n']      = "";
				$data['p_p']      = $patient_phone;
				$data['o_o']      = "";
				if(!empty($_COOKIE["l_hos_id"])){
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
				if(!empty($_COOKIE["l_keshi_id"])){
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}
			if(!empty($order_no)){
				$where = " o.order_no = '" . $order_no . "'";
				$data['p_n']      = "";
				$data['p_p']      = "";
				$data['o_o']      = $order_no;
				if(!empty($_COOKIE["l_hos_id"])){
					$where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
				}
				if(!empty($_COOKIE["l_keshi_id"])){
					$where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
				}
			}
			if($orderby == ''){
				$orderby = ' ORDER BY o.order_id DESC ';
			}else{
				$orderby = substr($orderby, 1);
				$orderby = ' ORDER BY ' . $orderby . ", o.order_id DESC";
			}

			$sql = "SELECT count(o.order_id) as order_id FROM " . $this->common->table('gonghai_order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
						WHERE $where  " .
						$orderby ;
			$count = $this->common->getOne($sql);
			if($count > 50000){
				header("Content-Type: text/html; charset=utf-8");
				echo '当前导出数量为'.$count.',已经超过50000导出的限制';exit;
			}
			$sql = "SELECT og.is_to_order,og.is_to_gonghai,o.order_id, o.order_no, o.is_first, o.admin_id, o.admin_name, o.pat_id,o.first_timeout,o.gonghai_type, p.pat_name, p.pat_sex, p.pat_age, o.come_time, o.from_value,
				p.pat_address, p.pat_phone, p.pat_phone1, p.pat_qq, p.pat_province, p.pat_city, p.pat_area, p.pat_weixin, o.from_parent_id, o.from_id, o.is_come,
				o.from_value, o.order_addtime, o.order_time, o.hos_id, o.keshi_id, o.type_id AS order_type, o.jb_parent_id, o.jb_id,
				o.doctor_time, o.order_time_duan, o.doctor_id, o.doctor_name, o.order_null_time, d.data_time,u.mark_time as zampo,u.admin_name as z_name
				FROM " . $this->common->table('gonghai_order') . " o
				LEFT JOIN " . $this->common->table('order_data') . " d ON o.order_id = d.order_id
				LEFT JOIN " . $this->common->table('patient') . " p ON p.pat_id = o.pat_id
				LEFT JOIN " . $this->common->table('order_out') . " u ON u.order_id = o.order_id
				LEFT JOIN " . $this->common->table('order_and_gonghai') . " og ON og.order_id = o.order_id
						WHERE $where  " .
						$orderby ;
			$row = $this->common->getAll($sql);
			$order_list = array();
			$order_id_arr = array();
			//重新把获取到的信息存进二维数组$arr中
			foreach($row as $key=> $val)
			{
				$order_id_arr[] = $val['order_id'];

				$order_list[$val['order_id']]['order_id'] = $val['order_id'];
				$order_list[$val['order_id']]['is_to_order'] = $val['is_to_order'];
				$order_list[$val['order_id']]['is_to_gonghai'] = $val['is_to_gonghai'];
				$order_list[$val['order_id']]['order_no'] = $val['order_no'];
				$order_list[$val['order_id']]['is_first'] = $val['is_first'];
				$order_list[$val['order_id']]['pat_name'] = $val['pat_name'];
				$order_list[$val['order_id']]['pat_phone'] = $val['pat_phone'];
				$order_list[$val['order_id']]['pat_phone1'] = $val['pat_phone1'];
				if($val['pat_sex'] == 1)
				{
					$order_list[$val['order_id']]['pat_sex'] = $this->lang->line('man');
				}
				else
				{
					$order_list[$val['order_id']]['pat_sex'] = $this->lang->line('woman');
				}

				$order_list[$val['order_id']]['pat_age'] = $val['pat_age'];
				$order_list[$val['order_id']]['pat_province'] = $val['pat_province'];
				$order_list[$val['order_id']]['pat_city'] = $val['pat_city'];
				$order_list[$val['order_id']]['pat_area'] = $val['pat_area'];
				$order_list[$val['order_id']]['pat_qq'] = $val['pat_qq'];
				$order_list[$val['order_id']]['pat_weixin'] = $val['pat_weixin'];
				$order_list[$val['order_id']]['order_addtime'] = date("Y-m-d H:i", $val['order_addtime']);
				if(!empty($val['order_time']))
				{
					$order_list[$val['order_id']]['order_time'] = date("Y-m-d", $val['order_time']);
				}
				else
				{
					$order_list[$val['order_id']]['order_time'] = $val['order_null_time'];
				}
				$order_list[$val['order_id']]['order_time_duan'] = $val['order_time_duan'];
				$order_list[$val['order_id']]['come_time'] = $val['come_time'];
				$order_list[$val['order_id']]['doctor_time'] = $val['doctor_time'];
				$order_list[$val['order_id']]['from_parent_id'] = $val['from_parent_id'];
				$order_list[$val['order_id']]['from_id'] = $val['from_id'];
				$order_list[$val['order_id']]['hos_id'] = $val['hos_id'];
				$order_list[$val['order_id']]['keshi_id'] = $val['keshi_id'];
				$order_list[$val['order_id']]['data_time'] = $val['data_time'];
				$order_list[$val['order_id']]['jb_parent_id'] = $val['jb_parent_id'];
				$order_list[$val['order_id']]['jb_id'] = $val['jb_id'];
				$order_list[$val['order_id']]['order_type'] = $val['order_type'];
				$order_list[$val['order_id']]['admin_name'] = $val['admin_name'];
				$order_list[$val['order_id']]['doctor_name'] = $val['doctor_name'];
				$order_list[$val['order_id']]['admin_id'] = $val['admin_id'];
				$order_list[$val['order_id']]['is_come'] = $val['is_come'];

				$order_list[$val['order_id']]['from_value'] = $val['from_value'];
				$order_list[$val['order_id']]['zampo'] = $val['zampo'];
				$order_list[$val['order_id']]['z_name'] = $val['z_name'];
				//把相关字段添加进去
				$order_list[$val['order_id']]['gonghai_type'] = $val['gonghai_type'];
				$order_list[$val['order_id']]['first_timeout']=$val['first_timeout'];
			}
			$area = $this->model->area();
			// 查询获取备注信息
			$sql = "SELECT * FROM " . $this->common->table('order_remark') . " WHERE order_id IN (" . implode(",", $order_id_arr) . ") ORDER BY mark_id DESC";
			$order_remark = $this->common->getAll($sql);

			// 查询获取回访记录信息
			$sql = "SELECT * FROM " . $this->common->table('ask_content') . " WHERE order_id IN (" . implode(",", $order_id_arr) . ")";
			$ask_content = $this->common->getAll($sql);

			// 清空输出缓冲区
			//ob_clean();
			// 载入PHPExcel类库
			$this->load->library('PHPExcel');
			$this->load->library('PHPExcel/IOFactory');
			// 创建PHPExcel对象
			$objPHPExcel = new PHPExcel();
			// 设置excel文件属性描述
			$objPHPExcel->getProperties()
			->setTitle("公海预约数据导出")
			->setDescription("公海预约数据导出");
			// 设置当前工作表
			$objPHPExcel->setActiveSheetIndex(0);
			// 设置表头
			// $fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式','方式子类', '地区', '预约性质', '预约人', '备注', '回访记录', '是否3天未回访登记(是/否)', '来源网址');
			$fields = array('预约日期','预约到诊日期', '实际到诊日期','预约编号','接诊医生', '病人姓名', '年龄', '联系电话', '预约内容', '预约病种', '预约方式','方式子类', '地区', '预约性质', '预约人', '', '', '是否3天未回访登记(是/否)', '来源网址');
			// 列编号从0开始，行编号从1开始
			$col = 0;
			$row = 1;
			foreach($fields as $field){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $field);
				$col++;
			}
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			// 从第二行开始输出数据内容
			$sql = 'select rank_type from '. $this->common->table('rank') .' where rank_id = '.$_COOKIE["l_rank_id"];
			$rank_type = $this->common->getRow($sql);
			if(strcmp($_COOKIE['l_admin_action'],'all') == 0){
				$l_admin_action  = array("179");
			}else{
				$l_admin_action  = explode(',',$_COOKIE['l_admin_action']);
			}
			$row = 2;
			foreach($order_list as $val){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $val["order_addtime"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $val["order_time"] . " " . $val['order_time_duan']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, ($val["is_come"] == 1)? (!empty($val["come_time"])? date("Y-m-d H:i", $val["come_time"]):''):'');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $val["order_no"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $val["doctor_name"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $val["pat_name"]);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $val["pat_age"]);
				//更加查看电话号码的权限 过来电话
				if(!in_array('179',$l_admin_action)){
					$val['pat_phone']  =  $val['pat_phone'][0].$val['pat_phone'][1].$val['pat_phone'][2].'*****';
					$val['pat_phone1']  =  $val['pat_phone1'][0].$val['pat_phone1'][1].$val['pat_phone1'][2].'*****';
				}
				if($rank_type['rank_type']==2||$rank_type['rank_type']==3||$_COOKIE['l_admin_action'] == 'all'||$_COOKIE['l_admin_id']=129){
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $val["pat_phone"]);
				}else{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, substr_replace($val["pat_phone"],'****',7,4));
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, (isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_name']:'') . " " . (isset($jibing_list[$val["jb_id"]])? $jibing_list[$val["jb_id"]]['jb_name']:''));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, isset($jibing_list[$val["jb_parent_id"]])? $jibing_list[$val["jb_parent_id"]]['jb_code']:'');
				$from_name = isset($from_list[$val["from_parent_id"]])? $from_list[$val["from_parent_id"]]['from_name']:'';
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $from_name);
				$from_name_son = isset($from_list_son[$val["from_id"]])? $from_list_son[$val["from_id"]]['from_name']:'';
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $from_name_son);
				$a = '';
				if($val['pat_province'] > 0){ $a .= $area[$val['pat_province']]['region_name'];}
				if($val['pat_city'] > 0){ $a .= "、" . $area[$val['pat_city']]['region_name'];}
				if($val['pat_area'] > 0){ $a .= "、" . $area[$val['pat_area']]['region_name'];}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $a);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, isset($type_arr[$val["order_type"]])? $type_arr[$val["order_type"]]['type_name']:'');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $row, $val["admin_name"]);


				$mark = isset($val['back'])? $val['back']:'';
				$back= isset($val['mark'])? $val['mark']:'';
				$max_time= 0;
				foreach($order_remark as $order_remark_val){
					if($order_remark_val['order_id'] == $val['order_id']){
						$max_time= $order_remark_val['mark_time'];
						if($order_remark_val['mark_type'] == 3){
							$back .= $order_remark_val['mark_content'] . "[" . $order_remark_val['admin_name'] . " @" . date("m-d H:i", $order_remark_val['mark_time']) . "]";
						}else{
							$mark .= $order_remark_val['mark_content'] . "[" . $order_remark_val['admin_name'] . " @" . date("m-d H:i", $order_remark_val['mark_time']) . "]";
						}
					}
				}

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,'');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,'');
				// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $row,$mark);
				// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $row,$back);
				//判断是否超过3天未回访的  未到诊 未来院的
				if(empty($val['come_time']) || empty($val['is_come'])){
					if((strtotime($val['order_time'].' 23:59:59') - 3*24*60*60) > $max_time ){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,'是');
					}else{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,"否");
					}
				}else{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, $row,"否");
				}
				$con_content= '';
				foreach($ask_content as $ask_content_temp)
				{
					if($ask_content_temp['order_id'] == $val['order_id']){
						$con_content = $ask_content_temp['con_content'];break;
					}
				}

				$con_content_str = '';
				$con_content = explode('进入:<a href="',$con_content);
				if(count($con_content) > 1){
					$con_content = explode('">',$con_content[1]);
					if(count($con_content) > 1){
						$con_content_str = $con_content[0];
					}
				}else{
					$con_content_str = $con_content[0];
				}
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, $row,$con_content_str);

				$row++;
			}
			//输出excel文件
			$objPHPExcel->setActiveSheetIndex(0);
			// 第二个参数可取值：CSV、Excel5(生成97-2003版的excel)、Excel2007(生成2007版excel)
			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			// 设置HTTP头
			header('Content-Type: application/vnd.ms-excel; charset=utf-8');
			header('Content-Disposition: attachment;filename="'.mb_convert_encoding(time(), "GB2312", "UTF-8").'.xls"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}



		//展示预约卡
		public function yycard(){
			$order_id = isset($_REQUEST['order_id'])? $_REQUEST['order_id']:0;
			$data = array();
			if(!empty($order_id)){
				$data['order'] = $this->common->getRow("SELECT o.hos_id,o.keshi_id,o.admin_id,o.order_no,o.order_time,k.keshi_name,o.pat_id FROM " . $this->common->table("order") . " as o," . $this->common->table("keshi") . " as k   WHERE o.order_id = ".$order_id." and o.keshi_id = k.keshi_id");
				if(isset($data['order'])){
					$data['patient'] = $this->common->getRow("SELECT p.pat_id,p.pat_name,p.pat_phone,p.pat_age,p.pat_phone1 FROM  " . $this->common->table("patient") . " as p  WHERE p.pat_id = ".$data['order']['pat_id']);

					$keshi_check_ts = $this->config->item('keshi_check_ts');
					$keshi_check_ts = explode(",",$keshi_check_ts);
					$zixun_check_ts = $this->config->item('zixun_check_ts');
					$zixun_check_ts = explode(",",$zixun_check_ts);
					$rank_type  = $this->model->rank_type();
					//咨询只能看自己的电话 其他电话不可见
					if(in_array($_COOKIE["l_rank_id"], $zixun_check_ts) && $rank_type == 2 && $data['order']['hos_id'] == 3 && in_array($data['order']['keshi_id'], $keshi_check_ts)){
						if($_COOKIE['l_admin_id'] != $data['order']['admin_id']){
							//$data['patient']['pat_phone']  =  $data['patient']['pat_phone'][0].$data['patient']['pat_phone'][1].$data['patient']['pat_phone'][2].'*****';
						}
					}
				}
			}
			$data['title'] = '预约卡';
			$this->load->view('yycard', $data);
		}


		//获取打勾记录
		public function get_dagou(){
			$order_id = isset($_REQUEST['order_id'])? $_REQUEST['order_id']:0;
			$data = array();
			$data['order'] =array();
			if(!empty($order_id)){
				$data['order'] = $this->common->getAll("SELECT * FROM " . $this->common->table("turn_on") . " WHERE  order_id = ".$order_id."  order by time desc ");
			}
			$data['title'] = '打勾记录';
			$this->load->view('get_dagou', $data);
		}


		//添加备注记录
		public function get_fz(){
			$order_id = isset($_REQUEST['order_id'])? $_REQUEST['order_id']:0;
			$data = array();
			$data['order'] =array();
			if(!empty($order_id)){
				$data['order'] = $this->common->getAll("SELECT * FROM " . $this->common->table("order_fz") . " WHERE  order_id = ".$order_id."  order by fztime desc ");
			}
			$data['order_id']=$order_id;
			$data['title'] = '复诊记录';
			$this->load->view('get_fz', $data);
		}

		//添加备注记录
		public function add_fz_ajax(){
			$msg =array();
			$msg['id'] = 0;
			$order_id = isset($_REQUEST['order_id'])? $_REQUEST['order_id']:0;
			$fz_remark = isset($_REQUEST['fz_remark'])? $_REQUEST['fz_remark']:0;
			if(empty($fz_remark) || empty($order_id)){
				echo json_encode($msg);exit;
			}
			$order = $this->common->getAll("SELECT hos_id,keshi_id FROM " . $this->common->table("order") . " WHERE  order_id = ".$order_id);
			$adddata = array();
			$adddata['fztime'] = time();
			$adddata['fzremark'] = $fz_remark;
			$adddata['order_id'] = $order_id;
			$adddata['hos_id'] = $order[0]['hos_id'];
			$adddata['keshi_id'] =$order[0]['keshi_id'];
			$adddata['admin_id'] =$_COOKIE['l_admin_id'];
			$adddata['admin_name'] =$_COOKIE['l_admin_name'];
			$bol = $this->db->insert($this->common->table('order_fz'),$adddata);
			if($bol ){
				$msg['id'] =$this->db->insert_id();
				$msg['admin_name'] ='';
				$msg['fztime'] =date("Y-m-d H:i:s",time());
				echo json_encode($msg);exit;
			}
			echo json_encode($msg);exit;
		}


		/****
		 * 推送预约性质
		*/
		private function sycn_order_type_data_to_ireport($parm){

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
				$info_temp['hos_id'] = $$temps['hos_id'];
				$info_temp['keshi_id'] = $temps['keshi_id'];
				$info_temp['type_desc'] = $temps['type_desc'];
				$info_temp['type_order'] = $temps['type_order'];

				$info_temp['renai_id'] = $temps['type_id'];
				$info[] = $info_temp;
			}
			$info=  json_encode($info);

			$url = $this->config->item('ireport_url_send_order_type');
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
				//var_dump($output);exit;

				$api_request = array();
				$api_request['apply_id']= '';
				$api_request['apply_type']='预约性质添加或者修改';
				$api_request['apply_name']='预约性质';
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
					$add_update_ok_status  =$output_data['add_update_ok_status'];
					$add_update_error_status  =$output_data['add_update_error_status'];

					if(strcmp($output_json['code'],'info_empty') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'decryption_error') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'ok') ==0 ){
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
									$update['ireport_msg'] = '操作失败。存在同样的数据';
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
					}else{
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

		/****
		 *  删除预约性质
		*/
		private function sycn_del_order_type_data_to_ireport($parm){
			header("Content-type: text/html; charset=utf-8");

			$info = array();
			$info_temp = array();
			$info_temp['id'] =  $parm['ireport_order_type_id'];

			$info[] = $info_temp;

			$url = $this->config->item('ireport_url_del_order_type');
			if(!empty($url)){
				require_once(BASEPATH."/core/Decryption.php");

				$decryption = new Decryption();
				$mu_str= $this->config->item('renai_mu_word');
				$gong_str= $this->config->item('renai_mu_number');
				$key_user = $decryption->createRandKey($mu_str,$gong_str);
				$str = $this->config->item('ireport_name').date("Y-m-d H",time());
				$encryption_validate = $decryption->encryption($str,$key_user);

				$encryption_data = $decryption->encryption(json_encode($info),$key_user);

				$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				curl_close($ch);

			}

		}

		/****
		 * 预约途径更新或者修改
		*/
		private function sycn_order_from_data_to_ireport($parm){

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
				$info_temp['from_order'] = $temps['from_order'];
				$info[] = $info_temp;
			}
			$info=  json_encode($info);
			$url = $this->config->item('ireport_url_send_order_from');
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
				$api_request = array();
				$api_request['apply_id']= '';
				$api_request['apply_type']='预约途径添加或者修改';
				$api_request['apply_name']='预约途径';
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
					$add_update_ok_status  =$output_data['add_update_ok_status'];
					$add_update_error_status  =$output_data['add_update_error_status'];

					if(strcmp($output_json['code'],'info_empty') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'decryption_error') ==0 ){
						$api_request['apply_status']='2';
					}else if(strcmp($output_json['code'],'ok') ==0 ){
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
									$update['ireport_msg'] = '存在空值';
									$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
								}
							}else if(!empty($add_exits_status)){
								foreach ($add_exits_status as $temps){
									$update = array();
									$update['ireport_msg'] = '存在相同的值';
									$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
								}
							}else if(!empty($add_add_error_status)){
								foreach ($add_add_error_status as $temps){
									$update = array();
									$update['ireport_msg'] = '添加失败';
									$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
								}
							}else if(!empty($add_update_error_status)){
								foreach ($add_update_error_status as $temps){
									$update = array();
									$update['ireport_msg'] = '更新失败';
									$this->db->update($this->db->dbprefix . "order_from", $update, array('from_id' => $temps['renai_id']));
								}
							}
						}
					}else{
						$output_json['msg'] = '接口异常';
						$api_request['apply_status']='1';
					}

				}else{

					$output_json['msg'] = '接口异常';
					$api_request['apply_status']='1';

				}
				$api_request['response_msg']=$output_json['msg'];
				$api_request['response_code']=$output_json['code'];
				$api_request['response_data']=$output;
				$this->db->insert($this->common->table("api_request_log"), $api_request);

			}else{
				$api_request = array();
				$api_request['apply_id']= '';
				$api_request['apply_type']='预约途径添加或者更新';
				$api_request['apply_name']='预约途径';
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

		/****
		 * 预约途径删除
		*/
		private function sycn_del_order_from_data_to_ireport($parm){
			header("Content-type: text/html; charset=utf-8");

			$info = array();
			$info_temp = array();
			$info_temp['id'] =  $parm['ireport_order_from_id'];

			$info[] = $info_temp;
			$url = $this->config->item('ireport_url_del_order_from');
			if(!empty($url)){
				require_once(BASEPATH."/core/Decryption.php");

				$decryption = new Decryption();
				$mu_str= $this->config->item('renai_mu_word');
				$gong_str= $this->config->item('renai_mu_number');
				$key_user = $decryption->createRandKey($mu_str,$gong_str);
				$str = $this->config->item('ireport_name').date("Y-m-d H",time());
				$encryption_validate = $decryption->encryption($str,$key_user);

				$encryption_data = $decryption->encryption(json_encode($info),$key_user);

				$post_data = array("appid"=>$this->config->item('ireport_name'),"appkey"=>$this->config->item('ireport_pwd'),"verification"=>$encryption_validate['data'],"key_one"=>$key_user['mu_str_number'],"key_two"=>$key_user['gong_str_number'],"info"=>$encryption_data['data']);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				$output = curl_exec($ch);
				curl_close($ch);
			}


		}

		/***
		 * 推送到诊信息到数据中心
		* */
		private function send_daozhen_to_shujuzhongxin($remark,$order_id){
			$send_array = array();
			$send_data = array();
			$send_data['operation_type'] = 'daozhen';
			$apisql = 'select doctor_name,ireport_order_id,come_time,is_come from '.$this->common->table('order')." where order_id  = '".$order_id."'";
			$ireport_order_data = $this->common->getAll($apisql);
			if(!empty($ireport_order_data[0]['ireport_order_id'])){
				$send_data['order_id'] = $order_id;
				$send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];
				if(!empty($ireport_order_data[0]['is_come'])){
					$send_data['visited'] =  1;
					$send_data['visite_date'] =  $ireport_order_data[0]['come_time'];
					$send_data['doctor'] =  $ireport_order_data[0]['doctor_name'];
				}else{
					$send_data['visited'] =  0;
					$send_data['visite_date'] =  '';
					$send_data['doctor'] =  '';
				}
				$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type != 3';
				$api_order_remark_data  = $this->common->getAll($apisql);
				$api_order_remark_str = '';
				foreach($api_order_remark_data as $api_order_remark_data_t){
					if(empty($api_order_remark_str)){
						$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
					}else{
						$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
					}
				}
				$send_data['memo'] = $api_order_remark_str.'#'.$remark.'['.$_COOKIE['l_admin_name'].']';
				$send_array[] =$send_data;
	            //$this->sycn_order_data_to_ireport($send_array);

	            //剔除仁爱数据推送
	            $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
	            if(!$order_hos_id){
	                $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
	            }

	            if (!in_array('1',$order_hos_id)) {
	                $this->sycn_order_data_to_ireport($send_array);
	            }

			}

		}

		/**推送回访信息到数据中心
		 *
		* **/
		private function send_huifang_to_shujuzhongxin($order_id){
			$send_array = array();
			$send_data = array();
			$send_data['operation_type'] = 'huifang';
			$apisql = 'select ireport_order_id,order_time,is_come from '.$this->common->table('order')." where order_id  = '".$order_id."'";
			$ireport_order_data = $this->common->getAll($apisql);
			if($ireport_order_data[0]['is_come'] == 0 && !empty($ireport_order_data[0]['ireport_order_id'])){
				$send_data['order_id'] = $order_id;
				$send_data['ireport_order_id'] = $ireport_order_data[0]['ireport_order_id'];

				$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type in(3)';
				$api_order_remark_data  = $this->common->getAll($apisql);
				$api_order_remark_str = '';
				foreach($api_order_remark_data as $api_order_remark_data_t){
					if(empty($api_order_remark_str)){
						$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
					}else{
						$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
					}
				}
				$send_data['visite_times'] = count($api_order_remark_data);
				$send_data['visite_bycheck_desc'] = $api_order_remark_str;
				$apisql = 'select max(mark_time) as mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type = 3';
				$send_data['visite_datetime'] = $this->common->getOne($apisql);
				$send_data['visite_bycheck'] =0;
				$send_data['track_next_time'] =$ireport_order_data[0]['order_time'];
				$send_array[] =$send_data;
	            //$this->sycn_order_data_to_ireport($send_array);

	            //剔除仁爱数据推送
	            $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
	            if(!$order_hos_id){
	                $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
	            }

	            if (!in_array('1',$order_hos_id)) {
	                $this->sycn_order_data_to_ireport($send_array);
	            }

			}
		}
		/***
		 * 推送或者更新订单数据到数据中心
		* */
		private function send_order_shujuzhognxin($order_id){
			$send_array = array();
			$send_data = array();

			$apisql = 'select * from '.$this->common->table('order')." where order_id  = '".$order_id."'";
			$ireport_order_data = $this->common->getAll($apisql);
			$send_data['gonghai_status'] =0;
			if(count($ireport_order_data) ==0 ){
				$apisql = 'select * from '.$this->common->table('gonghai_order')." where order_id  = '".$order_id."'";
				$ireport_order_data = $this->common->getAll($apisql);
				$send_data['gonghai_status'] =1;
			}

			$send_data['order_id'] = $ireport_order_data[0]['order_id'];
			if(empty($ireport_order_data[0]['is_first'])){
				$ireport_order_data[0]['is_first'] =1;
			}else{
				$ireport_order_data[0]['is_first'] =0;
			}
			$send_data['yuyue_type'] = $ireport_order_data[0]['is_first'];
			$apisql = 'select * from '.$this->common->table('patient')." where pat_id  = '".$ireport_order_data[0]['pat_id']."'";
			$ireport_order_patient_data = $this->common->getAll($apisql);

			$send_data['ireport_order_id'] =$ireport_order_data[0]['ireport_order_id'];
			$send_data['operation_type'] = 'edit';
			if(empty($send_data['ireport_order_id'])){
				$send_data['operation_type'] = 'add';
				unset($send_data['ireport_order_id']);
			}
			$send_data['add_time'] = $ireport_order_data[0]['order_addtime'];

			$send_data['order_no'] = $ireport_order_data[0]['order_no'];
			$apisql = 'select ireport_hos_id from '.$this->common->table('keshi')." where keshi_id  = '".$ireport_order_data[0]['keshi_id']."'";
			$send_data['id_hospital'] = $this->common->getone($apisql);

			$apisql = 'select ireport_admin_id,admin_name from '.$this->common->table('admin')." where admin_id  = '".$ireport_order_data[0]['admin_id']."'";
			$api_admin_data = $this->common->getAll($apisql);

			$send_data['id_consult'] = $api_admin_data[0]['ireport_admin_id'];
			$send_data['consult_name'] = $api_admin_data[0]['admin_name'];
			$send_data['date_day'] = $ireport_order_data[0]['order_time'];
			$disease= '';
			if(empty($ireport_order_data[0]['jb_id'])){
				$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_parent_id']."'";
				$api_jibing_data  = $this->common->getone($apisql);
				if(!empty($api_jibing_data)){
					$disease= $api_jibing_data;
				}
			}else if(!empty($ireport_order_data[0]['jb_id'])){
				$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_parent_id']."'";
				$api_jibing_data  = $this->common->getone($apisql);
				if(!empty($api_jibing_data)){
					$disease= $api_jibing_data;
				}

				$apisql = 'select jb_name from '.$this->common->table('jibing')." where jb_id  = '".$ireport_order_data[0]['jb_id']."'";
				$api_jibing_data  = $this->common->getone($apisql);
				if(!empty($api_jibing_data)){
					$disease= $disease.' '.$api_jibing_data;
				}
			}
			$send_data['disease'] = $disease;

			$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type != 3';
			$api_order_remark_data  = $this->common->getAll($apisql);
			$api_order_remark_str = '';
			foreach($api_order_remark_data as $api_order_remark_data_t){
				if(empty($api_order_remark_str)){
					$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}else{
					$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}
			}
			$send_data['memo'] = $api_order_remark_str;
		    //到诊状态
			if(!empty($ireport_order_data[0]['is_come'])){
				$send_data['visited']  =  1;
				$send_data['visite_date']  =  $ireport_order_data[0]['come_time'];
				$send_data['doctor']  =  $ireport_order_data[0]['doctor_name'];
			}
			$apisql = 'select mark_content,admin_name,mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type = 3';
			$api_order_remark_data  = $this->common->getAll($apisql);
			$api_order_remark_str = '';
			foreach($api_order_remark_data as $api_order_remark_data_t){
				if(empty($api_order_remark_str)){
					$api_order_remark_str = $api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}else{
					$api_order_remark_str = $api_order_remark_str.'#'.$api_order_remark_data_t['mark_content']."[".$api_order_remark_data_t['admin_name']."-".date("Y-m-d H:i:s", $api_order_remark_data_t['mark_time'])."]";
				}
			}
			$send_data['visite_times'] = count($api_order_remark_data);
			$send_data['visite_bycheck_desc'] = $api_order_remark_str;
			$apisql = 'select max(mark_time) as mark_time from '.$this->common->table('order_remark').' where order_id = '.$order_id.' and mark_type in(3)';
			$send_data['visite_datetime'] = $this->common->getOne($apisql);
			$send_data['visite_bycheck'] =0;
			$send_data['track_next_time'] =$ireport_order_data[0]['order_time'];

			$send_data['yuyue_time_duan'] = $ireport_order_data[0]['order_time_duan'];
			$send_data['yuyue_time_type'] = $ireport_order_data[0]['duan_confirm'];

			$send_data['dialogue_record'] = '';

			$apisql = 'select con_content from '.$this->common->table('ask_content').' where order_id = '.$ireport_order_data[0]['order_id'];
			$api_ask_content_data  = $this->common->getAll($apisql);
			if(count($api_ask_content_data) > 0){
				$send_data['dialogue_record'] = $api_ask_content_data[0]['con_content'];
			}

			//添加来源网址
			$apisql = 'select form,guanjianzi from '.$this->common->table('order_laiyuanweb').' where order_id = '.$ireport_order_data[0]['order_id'];
			$api_form_data  = $this->common->getAll($apisql);
			$send_data['keywords'] = '';
			if(count($api_form_data) > 0){
				$send_data['keywords'] = mb_substr($api_form_data[0]['guanjianzi'],0,90,'utf-8');
			}
			$send_data['source_url'] = '';
			if(!empty($ireport_order_data[0]['keshiurl_id'])){
				$apisql = 'select url from '.$this->common->table('keshiurl').' where id = '.$ireport_order_data[0]['keshiurl_id'];
				$api_keshiurl_data  = $this->common->getAll($apisql);
				if(count($api_keshiurl_data) > 0){
					$send_data['source_url'] = $api_keshiurl_data[0]['url'];
				}
			}else{
				if(count($api_form_data) > 0){
					$send_data['source_url'] = $api_form_data[0]['form'];
				}
			}
			//末次月经查询
			$send_data['last_menstrual_period']= '';
			$apisql = 'select data_time from '.$this->common->table('order_data').' where order_id = '.$ireport_order_data[0]['order_id'];
			$data_time_data  = $this->common->getAll($apisql);
			if(count($data_time_data) > 0){
				$send_data['last_menstrual_period'] = $data_time_data[0]['data_time'];
			}

			$send_data['patient_name'] = $ireport_order_patient_data[0]['pat_name'];
			$send_data['age'] = $ireport_order_patient_data[0]['pat_age'];


			$send_data['order_type'] = '0';
			$apisql = 'select ireport_order_type_id from '.$this->common->table('order_type').' where type_id = '.$ireport_order_data[0]['type_id'];
			$api_order_type_data  = $this->common->getAll($apisql);
			if(count($api_order_type_data) > 0){
				if(!empty( $api_order_type_data[0]['ireport_order_type_id'])){
					$send_data['order_type'] = $api_order_type_data[0]['ireport_order_type_id'];
				}
			}

			$send_data['order_from'] = '0';
			$send_data['order_from_two'] = '0';
			$send_data['from_value'] = $ireport_order_data[0]['from_value'];
			$apisql = 'select ireport_order_from_id from '.$this->common->table('order_from').' where from_id = '.$ireport_order_data[0]['from_parent_id'];
			$api_order_from_1_data  = $this->common->getAll($apisql);
			if(count($api_order_from_1_data) > 0){
				if(!empty( $api_order_from_1_data[0]['ireport_order_from_id'])){
					$send_data['order_from'] = $api_order_from_1_data[0]['ireport_order_from_id'];
				}
			}
			$apisql = 'select ireport_order_from_id from '.$this->common->table('order_from').' where from_id = '.$ireport_order_data[0]['from_id'];
			$api_order_from_2_data  = $this->common->getAll($apisql);
			if(count($api_order_from_2_data) > 0){
				if(!empty( $api_order_from_2_data[0]['ireport_order_from_id'])){
					$send_data['order_from_two'] = $api_order_from_2_data[0]['ireport_order_from_id'];
				}
			}

			$apisql = 'select region_id,region_name from '.$this->common->table('region');
			$api_region_data  = $this->common->getAll($apisql);
			$area = '';
			foreach ($api_region_data as $api_region_data_s){
				if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_province']){
					$area = $api_region_data_s['region_name'] ;
				}
				if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_city']){
					$area = $area.' '.$api_region_data_s['region_name'] ;
				}
				if($api_region_data_s['region_id'] == $ireport_order_patient_data[0]['pat_area']){
					$area = $area.' '.$api_region_data_s['region_name'] ;
				}
			}
			$send_data['area'] = $area.' '.$ireport_order_patient_data[0]['pat_address'];
			$send_data['phone'] = $ireport_order_patient_data[0]['pat_phone'];
			$send_data['email'] = '';
			if($ireport_order_patient_data[0]['pat_sex'] == 2){
				$send_data['sex'] = '女';
			}else{
				$send_data['sex'] = '男';
			}
			$send_array[] = $send_data;
	        //$this->sycn_order_data_to_ireport($send_array);



	        //剔除仁爱数据推送
	        $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('order') . " WHERE order_id = $order_id");
	        if(!$order_hos_id){
	            $order_hos_id = $this->common->getRow("SELECT hos_id FROM " . $this->common->table('gonghai_order') . " WHERE order_id = $order_id");
	        }

	        if (!in_array('1',$order_hos_id)) {
	            $this->sycn_order_data_to_ireport($send_array);
	        }

		}

		/****
		 * 订单添加或者更新
		*/
		private function sycn_order_data_to_ireport($parm){
			//if($_COOKIE['l_admin_id'] == 849 or  $_COOKIE['l_admin_id'] == 1938  or $_COOKIE['l_admin_id'] == 879){

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
					$info_temp['keywords'] = $temps['keywords'];
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
					if(!empty($temps['visite_date'])){
						$info_temp['visite_date'] =  $temps['visite_date'];
					}
					if(!empty($temps['doctor'])){
						$info_temp['doctor'] =  $temps['doctor'];
					}
					if(!empty($temps['visited'])){
						$info_temp['visited'] =  $temps['visited'];
					}
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
					$info_temp['keywords'] = $temps['keywords'];
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
					$info_temp['gonghai_status'] = $temps['gonghai_status'];;
					$info_temp['yuyue_type'] = $temps['yuyue_type'];

					if(!empty($temps['visite_date'])){
						$info_temp['visite_date'] =  $temps['visite_date'];
					}
					if(!empty($temps['doctor'])){
						$info_temp['doctor'] =  $temps['doctor'];
					}
					if(!empty($temps['visited'])){
						$info_temp['visited'] =  $temps['visited'];
					}
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
					if(empty($temps['order_out'])){
						$temps['order_out'] = 0;
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
				//var_dump($output);exit;

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
								if($temps['operation_type'] == 'add' ){
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
						$output_json['msg'] = json_encode($output);;
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
			//}
		}

		/**
		 * HENRY ADD  =======================================================
		 */


	    /**
	     * AJAX 获取相应医院拥有的父类预约途径
	     * @return string
	     */
	    public function ajax_get_parent_channel() {
	        if (!$this->input->is_ajax_request()) {
	            show_404();
	        }
	        $keshi_id = isset($_REQUEST['keshi_id']) ? intval($_REQUEST['keshi_id']) : 0;
	        $hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;
	        $parent_id = isset($_REQUEST['parent_id']) ? intval($_REQUEST['parent_id']) : 0;
	        echo "<option value='0'>请选择</option>";
	        if ($hos_id == 0) {
	            exit;
	        }


	        if (!empty($keshi_id)) {
	            $where = "(hos_id = ".$hos_id." AND (keshi_id = ".$keshi_id." or keshi_id =0) and parent_id = 0  and is_show = 0) or ( hos_id = 0 AND keshi_id = 0 and parent_id = 0  and is_show = 0)";
	        } else {
	            $where = "(hos_id = ".$hos_id." AND parent_id = 0  and is_show = 0) or (hos_id = 0 AND parent_id = 0  and is_show = 0)";
	        }
	        $sql = 'select from_id,from_name,parent_id from ' . $this->common->table('order_from') . ' where '.$where;
	        $list = $this->common->getAll($sql);
	        //var_dump($sql);
	    	//var_dump($list);
	        if (empty($list)) {
	            exit();
	        }
	        foreach ($list as $val) {
	            echo "<option value=\"" . $val['from_id'] . "\"";
	            if ($val['from_id'] == $parent_id) {
	                echo " selected ";
	            }
	            echo ">" . $val['from_name'] . "</option>";
	        }
	    }


		/**
	     * AJAX 根据医院科室父类途径获取子类预约途径
	     * @return string
	     */
	    public function ajax_get_son_channel() {
	        if (!$this->input->is_ajax_request()) {
	            show_404();
	        }
	        //print_r($_REQUEST);
	        $parent_id = isset($_REQUEST['parent_id']) ? intval($_REQUEST['parent_id']) : 0;
	        $from_id = isset($_REQUEST['from_id']) ? intval($_REQUEST['from_id']) : 0;
	        $hos_id = isset($_REQUEST['hos_id']) ? intval($_REQUEST['hos_id']) : 0;
	        $keshi_id = isset($_REQUEST['keshi_id'])? intval($_REQUEST['keshi_id']):0;

	        echo "<option value=\"0\">" . $this->lang->line('please_select') . "</option>";


	        //根据from_id 即hos_id 获取子类预约途径
	        //代码开始
	        if (!empty($parent_id)) {
	            if (!empty($keshi_id)) {
	                $where = "(hos_id = ".$hos_id." AND parent_id = ".$parent_id." AND (keshi_id = ".$keshi_id." or keshi_id =0) AND is_show = 0) or ( hos_id = 0 AND parent_id = ".$parent_id." AND keshi_id = 0 AND is_show = 0)";
	            } else {
	                $where = "(hos_id = ".$hos_id." AND parent_id = ".$parent_id." AND is_show = 0) or ( hos_id = 0 AND parent_id = ".$parent_id." AND is_show = 0)";
	            }
	            $sql = "SELECT * FROM " . $this->common->table('order_from') . " WHERE ".$where." ORDER BY from_order ASC, from_id DESC";
	            $from_list = $this->common->getAll($sql);
	            //代码结束
	            //print_r($sql);
	    		//print_r($from_list);

	            if (empty($from_list)) {
	                exit();
	            }
	            foreach ($from_list as $val) {
	                echo "<option value=\"" . $val['from_id'] . "\"";
	                if ($val['from_id'] == $from_id) {
	                    echo " selected";
	                }
	                echo ">" . $val['from_name'] . "</option>";
	            }
	        }

	    }

    /**
     * 加入黑名单
     */
    public function ajax_addBlack(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $order_id = $_REQUEST['order_id'];
        $pat_id = $_REQUEST['pat_id'];

        $this->db->insert($this->common->table('order_black') , array(
            'order_id' => $order_id,
            'pat_id' => $pat_id,
            'admin_id' => $_COOKIE['l_admin_id'],
            'admin_name' => $_COOKIE['l_admin_name'],
            'add_time' => time(),
            'type' => 1
        ));
        if ($this->db->insert_id()) {
            $result = array('code' => 1, 'msg' => '加入成功');
            echo json_encode($result);
        } else {
            $result = array('code' => 2, 'msg' => '加入失败');
            echo json_encode($result);
        }

    }


    /**
     * 恢复黑名单
     */
    public function ajax_backBlack(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $order_id = $_REQUEST['order_id'];
        $pat_id = $_REQUEST['pat_id'];

        $this->db->insert($this->common->table('order_black') , array(
            'order_id' => $order_id,
            'pat_id' => $pat_id,
            'admin_id' => $_COOKIE['l_admin_id'],
            'admin_name' => $_COOKIE['l_admin_name'],
            'add_time' => time(),
            'type' => 2
        ));

        if ($this->db->insert_id()) {
            $result = array('code' => 1, 'msg' => '恢复成功');
            echo json_encode($result);
        } else {
            $result = array('code' => 2, 'msg' => '恢复失败');
            echo json_encode($result);
        }

    }

    /**
     * 黑名单列表
     * @return [type] [description]
     */
    public function blackList() {
        header("Content-type: text/html; charset=utf-8");
        $data = array();
        $data = $this->common->config('blackList');
        $where = 1;
        if ($_COOKIE["l_admin_action"] == "all" && empty($_COOKIE["l_hos_id"])) {
            $where .= "";
        }
        if (!empty($_COOKIE["l_hos_id"])) {
            $where .= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
        }
        if (!empty($_COOKIE["l_keshi_id"])) {
            $where .= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
        }

        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $per_page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 30;
        $per_page = empty($per_page) ? 30 : $per_page;
        $this->load->library('pagination');
        $this->load->helper('page'); //调用CI自带的page分页类

        $order_list = $this->model->getBlack($where);

        //剔除已恢复的黑名单患者
        //获取最新添加时间的对应记录
        $black_sql = "select order_id from hui_order_black as t where  add_time=(select max(t1.add_time) from hui_order_black as t1 where t.order_id = t1.order_id ) and type = 2";
        $black_order = $this->db->query($black_sql)->result_array();
        foreach ($order_list as $key => $value) {
            foreach ($black_order as $k => $val) {
                if ($value['order_id'] == $val['order_id']) {
                    unset($order_list[$val['order_id']]);
                }
            }
        }

        $order_list_count = $order_list;
        $order_list = array_slice($order_list, $page, $per_page);
		$config = page_config();

		$config['base_url'] = '?c=order&m=blackList&p=' . $per_page;
		$config['total_rows'] = count($order_list_count);
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 10;
		$config['num_links'] = 5;
		$this->pagination->initialize($config);

		$data['page'] = $this->pagination->create_links();



        $keshi = $this->model->keshi_order_list();
        $keshi_arr = array();
        //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
        foreach ($keshi as $val) {
            $keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
            $keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
            $keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
            $keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
            $keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
            $keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
        }

        $from_list = $this->model->from_order_list(-1);
        $par_from_arr = array();
        $from_arr = array(); //生成二维数组，把每条记录作为数组存进$from_arr
        foreach ($from_list as $val) {
            if ($val['parent_id'] == 0) {
                $par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            } else {
                $from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            }
        }
        $from_list = $par_from_arr;

        //获取订单类别表Order_type中的所有数据
        $type_list = $this->model->type_order_list();
        $type_arr = array();
        foreach ($type_list as $val) {
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
        if (!empty($jibing)) {
            //根据科室查询疾病
            if (!empty($data['keshi_id'])) {
                $keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = " . $data['keshi_id']);
                foreach ($jibing as $val) {
                    $jb_exit = 0;
                    foreach ($keshi_jb as $keshi_jb_val) {
                        if (strcmp($keshi_jb_val['jb_id'], $val['parent_id']) == 0) {
                            $jb_exit = $val['jb_id'];
                            break;
                        }
                    }
                    if (!empty($jb_exit)) {
                        $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        if ($val['jb_level'] == 2) {
                            $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                            $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        }
                    }
                }
            } else {
                foreach ($jibing as $val) {
                    $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                    $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    if ($val['jb_level'] == 2) {
                        $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    }
                }
            }
        }

        $area = $this->model->area();
        $data['area'] = $area;

        $data['keshi'] = $keshi_arr;
        $data['from_list'] = $from_list;
        $data['from_arr'] = $from_arr;
        $data['type_list'] = $type_list;
        $data['keshi'] = $keshi_arr;
        $data['jibing'] = $jibing_list;
        $data['jibing_parent'] = $jibing_parent;
        $data['order_list'] = $order_list;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('order_list_black', $data);
    }


    /**
     * 产品开发小程序、公众号渠道查看
     * @return [type] [description]
     */
    public function proWatch() {
        //$this->output->enable_profiler(TRUE);
        //预约列表
        $data = array();
        $data = $this->common->config('proWatch');

        $date = isset($_REQUEST['date']) ? trim($_REQUEST['date']) : ''; //日期
        $s_type = isset($_REQUEST['s_type']) ? intval($_REQUEST['s_type']) : 1;
        $order_type = isset($_REQUEST['t']) ? intval($_REQUEST['t']) : 2;
        $data['t'] = $order_type;

        //判断日期是否为空
        if (!empty($date)) {
            $date = explode(" - ", $date);
            $start = $date[0];
            $end = $date[1];
            if (!$this->dateCheck($start) || !$this->dateCheck($end)) {
                $start = date("Y年m月d日");
                $end = $start;
            }
            $data['start'] = $start;
            $data['end'] = $end;
            $start = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $start); //把初始日期中的年月日替换成—
            $end = str_replace(array(
                "年",
                "月",
                "日"
            ) , "-", $end);
            $start = substr($start, 0, -1);
            $end = substr($end, 0, -1);
            $data['start_date'] = $start;
            $data['end_date'] = $end;
            setcookie('start_time', strtotime($start));
            setcookie('end_time', strtotime($end));
        } else {
            if (isset($_COOKIE['start_time'])) {
                $start = date("Y-m-d", $_COOKIE['start_time']);
                $end = date("Y-m-d", $_COOKIE['end_time']);
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", $_COOKIE['start_time']);
                $data['end'] = date("Y年m月d日", $_COOKIE['end_time']);
            } else {
                $start = date("Y-m-d", time());
                $end = date("Y-m-d", time());
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start'] = date("Y年m月d日", time());
                $data['end'] = date("Y年m月d日", time());
            }
        }
        //以上的语句都是对日期数据的处理，$['start']$['end']表示xx年xx月xx日这种格式的日期，
        //而$[start_date]表示xx-xx-xx这种格式的日期



        //获取所有医院和科室
        $hospital = $this->model->hospital_order_list_all();
        $keshi = $this->model->keshi_order_list_all();
        $keshi_arr = array();
        //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
        foreach ($keshi as $val) {
            $keshi_arr[$val['keshi_id']]['keshi_id'] = $val['keshi_id'];
            $keshi_arr[$val['keshi_id']]['keshi_name'] = $val['keshi_name'];
            $keshi_arr[$val['keshi_id']]['parent_id'] = $val['parent_id'];
            $keshi_arr[$val['keshi_id']]['hos_id'] = $val['hos_id'];
            $keshi_arr[$val['keshi_id']]['keshi_level'] = $val['keshi_level'];
            $keshi_arr[$val['keshi_id']]['keshi_order'] = $val['keshi_order'];
        }
        //生成一个二维数组，从科室结果中取出相应数据写入$keshi_arr
        foreach ($hospital as $val) {
            $hos_arr[$val['hos_id']]['hos_id'] = $val['hos_id'];
            $hos_arr[$val['hos_id']]['hos_name'] = $val['hos_name'];
        }
        $from_list = $this->model->from_order_list(-1);
        $par_from_arr = array();
        $from_arr = array(); //生成二维数组，把每条记录作为数组存进$from_arr
        foreach ($from_list as $val) {
            if ($val['parent_id'] == 0) {
                $par_from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $par_from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $par_from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            } else {
                $from_arr[$val['from_id']]['from_id'] = $val['from_id'];
                $from_arr[$val['from_id']]['from_name'] = $val['from_name'];
                $from_arr[$val['from_id']]['parent_id'] = $val['parent_id'];
            }
        }
        $from_list = $par_from_arr;
        //获取订单类别表Order_type中的所有数据
        $type_list = $this->model->type_order_list();
        $type_arr = array();
        foreach ($type_list as $val) {
            $type_arr[$val['type_id']]['type_id'] = $val['type_id'];
            $type_arr[$val['type_id']]['type_name'] = $val['type_name'];
            $type_arr[$val['type_id']]['hos_id'] = $val['hos_id'];
            $type_arr[$val['type_id']]['keshi_id'] = $val['keshi_id'];
            $type_arr[$val['type_id']]['type_order'] = $val['type_order'];
        }
        $type_list = $type_arr;
        $hos_id_arr = array();
        $keshi_id_arr = array();
        $jibing = read_static_cache('jibing_list');
        $jibing_list = array();
        $jibing_parent = array();
        if (!empty($jibing)) {
            //根据科室查询疾病
            if (!empty($data['keshi_id'])) {
                $keshi_jb = $this->common->getAll("SELECT jb_id FROM " . $this->common->table('keshi_jibing') . " where keshi_id = " . $data['keshi_id']);
                foreach ($jibing as $val) {
                    $jb_exit = 0;
                    foreach ($keshi_jb as $keshi_jb_val) {
                        if (strcmp($keshi_jb_val['jb_id'], $val['parent_id']) == 0) {
                            $jb_exit = $val['jb_id'];
                            break;
                        }
                    }
                    if (!empty($jb_exit)) {
                        $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        if ($val['jb_level'] == 2) {
                            $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                            $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                        }
                    }
                }
            } else {
                foreach ($jibing as $val) {
                    $jibing_list[$val['jb_id']]['jb_id'] = $val['jb_id'];
                    $jibing_list[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    if ($val['jb_level'] == 2) {
                        $jibing_parent[$val['jb_id']]['jb_id'] = $val['jb_id'];
                        $jibing_parent[$val['jb_id']]['jb_name'] = $val['jb_name'];
                    }
                }
            }
        }
        $data['from_arr'] = $from_arr;
        $data['keshi'] = $keshi_arr;
        $data['jibing'] = $jibing_list;
        $data['jibing_parent'] = $jibing_parent;
        $hos_auth = array();
        foreach ($hospital as $val) {
            $hos_id_arr[] = $val['hos_id'];
            if ($val['ask_auth']) {
                $hos_auth[] = $val['hos_id'];
            }
        }
        foreach ($keshi as $val) {
            $keshi_id_arr[] = $val['keshi_id'];
        }
        $page = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 0;
        $data['now_page'] = $page;
        $per_page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 30;
        $per_page = empty($per_page) ? 30 : $per_page;
        $this->load->library('pagination');
        $this->load->helper('page'); //调用CI自带的page分页类
        /* 处理判断条件 */
        $where = 1;
        $orderby = '';
        // if (empty($hos_id)) {
        //     if (!empty($_COOKIE["l_hos_id"])) {
        //         $where.= ' AND o.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
        //     }
        // } else {
        //     $where.= ' AND o.hos_id = ' . $hos_id;
        // }
        // if (empty($keshi_id)) {
        //     if (!empty($_COOKIE["l_keshi_id"])) {
        //         $where.= " AND o.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
        //     }
        // } else {
        //     $where.= ' AND o.keshi_id = ' . $keshi_id;
        // }



        /* 时间条件 */
        $w_start = strtotime($start . ' 00:00:00');
        $w_end = strtotime($end . ' 23:59:59');
        // if ($order_type == 1) { //预约登记时间
        //     $where.= " AND (o.from_parent_id in (222) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end .") OR (o.from_id in (223,224,231) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end.")";
        //     $orderby.= ',o.order_addtime DESC ';
        // } elseif ($order_type == 2) { //预约时间
        //     $where.= " AND (o.from_parent_id in (222) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end .") OR (o.from_id in (223,224,231) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end.")";
        //     $orderby.= ',o.order_time DESC ';
        // } elseif ($order_type == 3) { //实到时间
        //     $where.= " AND (o.from_parent_id in (222) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end .") OR (o.from_id in (223,224,231) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end.")";
        //     $orderby.= ',o.come_time DESC ';
        // }

        //需要公众号则开启
        //$s_type
        //1====小程序
        //2====公众号
        //3====两性微课
        if ($order_type == 1) { //预约登记时间
            if ($s_type == 1) {
                $where.= " AND (o.from_parent_id in (222) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end .") OR (o.from_id in (223,224,231,276) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end.")";
            } elseif ($s_type == 2) {
                $where.= " AND (o.from_parent_id in (191,227) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end.")";
            } elseif ($s_type == 3)  {
                $where.= " AND (o.from_parent_id in (238) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end .") OR (o.from_id in (277) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end.")";
            } elseif ($s_type == 4)  {
                $where.= " AND (o.from_parent_id in (251) AND o.order_addtime >= " . $w_start . " AND o.order_addtime <= " . $w_end .")";
            }
            $orderby.= ',o.order_addtime DESC ';
        } elseif ($order_type == 2) { //预约时间
            if ($s_type == 1) {
                $where.= " AND (o.from_parent_id in (222) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end .") OR (o.from_id in (223,224,231,276) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end.")";
            } elseif ($s_type == 2) {
                $where.= " AND (o.from_parent_id in (191,227) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end.")";
            } elseif ($s_type == 3) {
                $where.= " AND (o.from_parent_id in (238) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end .") OR (o.from_id in (277) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end.")";
            } elseif ($s_type == 4) {
                $where.= " AND (o.from_parent_id in (251) AND o.order_time >= " . $w_start . " AND o.order_time <= " . $w_end .")";
            }
            $orderby.= ',o.order_time DESC ';
        } elseif ($order_type == 3) { //实到时间
            if ($s_type == 1) {
                $where.= " AND (o.from_parent_id in (222) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end .") OR (o.from_id in (223,224,231,276) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end.")";
            } elseif ($s_type == 2) {
                $where.= " AND (o.from_parent_id in (191,227) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end.")";
            } elseif ($s_type == 3)  {
                $where.= " AND (o.from_parent_id in (238) AND o.from_id in (277) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end .") OR (o.from_id in (277) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end.")";
            } elseif ($s_type == 4)  {
                $where.= " AND (o.from_parent_id in (251) AND o.come_time >= " . $w_start . " AND o.come_time <= " . $w_end .")";
            }
            $orderby.= ',o.come_time DESC ';
        }



        if ($orderby == '') {
            $orderby = ' ORDER BY o.order_time_duan ASC,  o.order_id DESC ';
        } else {
            $orderby = substr($orderby, 1);
            $orderby = ' ORDER BY ' . $orderby . ", o.order_time_duan ASC, o.order_id DESC";
        }
        $order_count = $this->model->order_count($where);
        $order_count['wei'] = $order_count['count'] - $order_count['come'];
        $config = page_config();

        $config['base_url'] = '?c=order&m=proWatch&date='. $_REQUEST['date'].'&t='. $_REQUEST['t'].'&s_type=' . $_REQUEST['s_type']. '&p=' . $per_page;

        $config['total_rows'] = $order_count['count'];
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 10;
        $config['num_links'] = 5;
        $this->pagination->initialize($config);
        //从预约列表中获取相关数据
        $order_list = $this->model->order_list($where, $page, $per_page, $orderby);


        //剔除黑名单患者
        //获取最新添加时间的对应记录
        $black_sql = "select order_id from hui_order_black as t where  add_time=(select max(t1.add_time) from hui_order_black as t1 where t.order_id = t1.order_id ) and type = 1";
        $black_order = $this->db->query($black_sql)->result_array();
        foreach ($order_list as $key => $value) {
            foreach ($black_order as $k => $val) {
                if ($value['order_id'] == $val['order_id']) {
                    unset($order_list[$val['order_id']]);
                }
            }
        }
        //p($black_order);

        $hos_array = isset($_COOKIE['l_hos_id']) ? $_COOKIE['l_hos_id'] : ''; //获取所属医院字符串
        $keshi_array = isset($_COOKIE['l_keshi_id']) ? $_COOKIE['l_keshi_id'] : ''; //获取所属医院所属科室字符串
        $doctor_list = $this->model->doctor_list($hos_array, $keshi_array); //依据字符串调用相关功能
        $rank_type = $this->model->rank_type();
        $area = $this->model->area();
        $province = $this->common->getAll("SELECT * FROM " . $this->common->table('region') . " WHERE parent_id = 1 ORDER BY region_id ASC");
        $flag_1 = array();
        $flag_2 = array();
        foreach ($province as $val) {
            if (trim($val['region_name']) == '广东' || trim($val['region_name']) == '浙江') {
                $flag_1[] = $val;
            } else {
                $flag_2[] = $val;
            }
        }
        $province_list = array_merge($flag_1, $flag_2);
        $data['s_type'] = $s_type;
        $data['doctor_list'] = $doctor_list; //新增医生列表
        $data['order_count'] = $order_count;
        $data['area'] = $area;
        $data['province'] = $province_list;
        $data['rank_type'] = $rank_type;

        $data['huifang'] = $this->set_huifang();
        $data['page'] = $this->pagination->create_links();
        $data['per_page'] = $per_page;
        $data['order_list'] = $order_list;

        $data['type_list'] = $type_list;
        $data['from_list'] = $from_list;
        $data['hospital'] = $hos_arr;

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('order_list_proWatch', $data);
    }

    /**
     * 根据时间和项目查询预约表单的修改记录
     */
    public function logRecord(){
        //$this->output->enable_profiler(TRUE);
        //预约列表
        $data = array();
        $data = $this->common->config('logRecord');

        $hos_id = !empty($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):'0';
        $date = !empty($_REQUEST['date'])?$_REQUEST['date']:'';

        if (empty($hos_id) || empty($date)) {

            $data['hos_id'] = '';
            $start_time = strtotime(date('Y-m-d 00:00:00',time()));
            $end_time = strtotime(date('Y-m-d 23:59:59',time()));
            $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);

        } else {

            if (!empty($date)) {
                $arr_dt = explode(' - ', $date);
                if (count($arr_dt)<2) {
                    $data['dateMsg'] = array('code'=>1,'msg'=>'日期格式错误');
                }
                foreach ($arr_dt as $key => $value) {
                    if (!preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $value)) {
                        $data['dateMsg'] = array('code'=>2,'msg'=>'日期格式错误');
                    }
                }

                $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
                $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);

                $start_time = strtotime($s_t." 00:00:00");
                $end_time = strtotime($e_t." 23:59:59");
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            } else {
                $start_time = strtotime(date('Y-m-d 00:00:00',time()));
                $end_time = strtotime(date('Y-m-d 23:59:59',time()));
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            }

            $per_page_num = 30;
            $per_page = !empty($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;

            $where = " where 1 ";
            $limit = " limit {$per_page},{$per_page_num}";
            $orderby = " order by b.action_time desc";

            if ($_COOKIE["l_admin_action"] == "all" && empty($_COOKIE["l_hos_id"])) {
                $where .= "";
            }

            if (!empty($hos_id)) {
                $where .= ' AND a.hos_id = '.$hos_id;
            } else {
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND a.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND a.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($date)) {
                $where .= ' AND b.action_time between '.$start_time.' and '.$end_time;
            }

            //$where .= " AND (b.action_type like '%大途径%' or b.action_type like '%小途径%' )";
            $where .= " AND (b.action_type like '大途径%' or b.action_type like '小途径%' )";

            $sql="select a.hos_id,a.order_id,a.order_no,b.action_type,b.action_name,b.action_time,c.hos_name from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id left join ".$this->common->table('hospital')." as c on a.hos_id = c.hos_id".$where.$orderby.$limit;

            $result = $this->common->getAll($sql);

            $res = array();
            foreach ($result as $key => $value) {
                $res[$value['order_id']][] = $value;
            }

            $data['info'] = $res;

            //p($data['info']);die();

            $sql_count="select count(a.order_id) as count from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id left join ".$this->common->table('hospital')." as c on a.hos_id = c.hos_id".$where;
            $result_count = $this->common->getRow($sql_count);


            $this->load->library('pagination');
            $this->load->helper('page');//调用CI自带的page分页类
            $config = page_config();
            $config['base_url'] = '?c=order&m=logRecord&date='.$date.'&hos_id='.$hos_id;

            $config['total_rows'] = $result_count['count'];
            $config['per_page'] = $per_page_num;
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);

            $data['page'] = $this->pagination->create_links();
            $data['count'] = $result_count['count'];
            $data['hos_id'] = $hos_id;
            $data['parse'] = '&date='.$data['date'].'&hos_id='.$hos_id;

        }


        $data['hospital'] = $this->model->hospital_order_list();

        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('order_log_record', $data);
    }




    public function logRecordExport(){
        header("Content-Type:text/html;charset=utf-8");

        $data = array();
        $data = $this->common->config('logRecord');

        $hos_id = !empty($_REQUEST['hos_id'])?intval($_REQUEST['hos_id']):'0';
        $date = !empty($_REQUEST['date'])?$_REQUEST['date']:'';

        if (empty($hos_id) || empty($date)) {

            $data['hos_id'] = '';
            $start_time = strtotime(date('Y-m-d 00:00:00',time()));
            $end_time = strtotime(date('Y-m-d 23:59:59',time()));
            $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);

        } else {

            if (!empty($date)) {
                $arr_dt = explode(' - ', $date);
                if (count($arr_dt)<2) {
                    $data['dateMsg'] = array('code'=>1,'msg'=>'日期格式错误');
                    exit('日期格式错误');
                }
                foreach ($arr_dt as $key => $value) {
                    if (!preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $value)) {
                        $data['dateMsg'] = array('code'=>2,'msg'=>'日期格式错误');
                        exit('日期格式错误');
                    }
                }

                $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
                $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);

                $start_time = strtotime($s_t." 00:00:00");
                $end_time = strtotime($e_t." 23:59:59");
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            } else {
                $start_time = strtotime(date('Y-m-d 00:00:00',time()));
                $end_time = strtotime(date('Y-m-d 23:59:59',time()));
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            }


            $where = " where 1 ";
            $orderby = " order by b.action_time desc";

            if ($_COOKIE["l_admin_action"] == "all" && empty($_COOKIE["l_hos_id"])) {
                $where .= "";
            }

            if (!empty($hos_id)) {
                $where .= ' AND a.hos_id = '.$hos_id;
            } else {
                if (!empty($_COOKIE["l_hos_id"])) {
                    $where .= ' AND a.hos_id IN (' . $_COOKIE["l_hos_id"] . ')';
                }
                if (!empty($_COOKIE["l_keshi_id"])) {
                    $where .= " AND a.keshi_id IN (" . $_COOKIE["l_keshi_id"] . ")";
                }
            }

            if (!empty($date)) {
                $where .= ' AND b.action_time between '.$start_time.' and '.$end_time;
            }
            //$where .= " AND (b.action_type like '%大途径%' or b.action_type like '%小途径%' )";
            $where .= " AND (b.action_type like '大途径%' or b.action_type like '小途径%' )";

            $sql="select a.hos_id,a.order_id,a.order_no,b.action_type,b.action_name,b.action_time,c.hos_name from ".$this->common->table('order')." as a left join ".$this->common->table('gonghai_log')." as b on a.order_id = b.order_id left join ".$this->common->table('hospital')." as c on a.hos_id = c.hos_id".$where.$orderby;
            //p($sql);
            $result = $this->common->getAll($sql);

            $row = array();
            foreach ($result as $key => $value) {
                $row[$value['order_id']][] = $value;
            }
        }
        //p($row);exit();



        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('预约表单操作日志');

        $objPHPExcel->getActiveSheet()->mergeCells('A1:E2');

        $objPHPExcel->getActiveSheet()->setCellValue('A1',$date.'预约表单操作日志');
        $objPHPExcel->getActiveSheet()->setCellValue('A3','项目');
        $objPHPExcel->getActiveSheet()->setCellValue('B3','预约号');
        $objPHPExcel->getActiveSheet()->setCellValue('C3','操作时间');
        $objPHPExcel->getActiveSheet()->setCellValue('D3','操作人');
        $objPHPExcel->getActiveSheet()->setCellValue('E3','详细');

       //设置列宽和行高
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(100);
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(18);
        $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);

        //垂直水平居中
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置字体
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Microsoft Yahei');

        //设置单元格内文字自动换行
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment()->setWrapText(true);


        //设置单元格文字颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1:E2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        //设置背景色
        $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle( 'A1')->getFill()->getStartColor()->setARGB('0000b0f0');

        //冻结
        $objPHPExcel->getActiveSheet()->freezePane('A4');

        //设置前两行字体
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setSize(13);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);

        //边框
        $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('A1:E3')->getBorders()->getAllBorders()->getColor()->setARGB('00000000');

        $n = 4;
        $q = 0;
        foreach ($row as $key => $value) {
            $num = count($value);
            $s = 0;
            foreach ($value as $k => $val) {
                if ($val['order_id'] == $key && $k == 0 && $num > 1) {
                    //var_dump(($n+$s+$q).'=='.($n+$s+$q+$num))."<br>";
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.($n+$s+$q).':A'.($n+$s+$q+$num-1));
                    $objPHPExcel->getActiveSheet()->mergeCells('B'.($n+$s+$q).':B'.($n+$s+$q+$num-1));
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.($n+$s+$q), $val['hos_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.($n+$s+$q), $val['order_no']);
                } else {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.($n+$s+$q), $val['hos_name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.($n+$s+$q), $val['order_no']);
                }
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n+$s+$q), date('Y-m-d H:i',$val['action_time']));
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n+$s+$q), $val['action_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n+$s+$q), $val['action_type']);
                $objPHPExcel->getActiveSheet()->getStyle('A'.($n+$s+$q).':E'.($n+$s+$q))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                $objPHPExcel->getActiveSheet()->getStyle('A'.($n+$s+$q).':E'.($n+$s+$q))->getBorders()->getAllBorders()->getColor()->setARGB('00000000');
                $s++;
                //var_dump($s)."<br>";
            }
            //$objPHPExcel->getActiveSheet()->mergeCells('A'.($n+$s+$q).':E'.($n+$s+$q));
            //$objPHPExcel->getActiveSheet()->getRowDimension(($n+$s+$q))->setRowHeight(10);
            //$q += $num+1;
            $q += $num;
            //var_dump($q)."<br>";
        }

        //输出到浏览器
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename='.$date.'预约表单操作日志.xls');
        header("Content-Transfer-Encoding:binary");
        $objWriter -> save('php://output');
    }

    /**
     * ajax获取患者电话号码
     */
    public function OrderShowPhone()
    {

    	header('content-type:application/json;charset=utf-8');

    	if (!$this->input->is_ajax_request()) {
    		show_404();
    	}

    	$order_id = intval($this->input->post('order_id'));
    	$pat_id = intval($this->input->post('pat_id'));
    	$admin_id = intval($_COOKIE['l_admin_id']);
    	$time = time();
    	$s_t = strtotime(date('Y-m-d 00:00:00',$time));
    	$e_t = strtotime(date('Y-m-d 23:59:59',$time));

    	$result = array();
    	$is_order_exist = $this->db->select('order_id')->where('order_id', $order_id)->get('hui_order')->row_array();
    	$is_pat_exist = $this->db->select('pat_phone,pat_phone1')->where('pat_id', $pat_id)->get('hui_patient')->row_array();

    	$result = array_merge($is_order_exist,$is_pat_exist);

    	if ($is_order_exist && $is_pat_exist) {

    		$where = array('admin_id'=>$admin_id,'order_id'=>$order_id,'pat_id' => $pat_id,'add_time >='=>$s_t,'add_time <='=>$e_t);

    		//当前日期是否已经显示过号码，防止重复添加
    		$this->db->set_dbprefix("henry_");
    		$is_record_exist = $this->db->get_where('show_phone_record', $where)->row_array();

    		if (!$is_record_exist) {
    			if (!empty($is_pat_exist['pat_phone1'])) {
    				$phone =$is_pat_exist['pat_phone'].'/'.$is_pat_exist['pat_phone1'];
    			} else {
    				$phone =$is_pat_exist['pat_phone'];
    			}

    			$data = array(
    			    'admin_id' => $admin_id,
    			    'order_id' => $order_id,
    			    'pat_id'   => $pat_id,
    			    'add_time' => $time
    			);

    			$this->db->set_dbprefix("henry_");
    			$this->db->insert('show_phone_record', $data);

    			if ($this->db->insert_id()) {
    				$returnData = array('order_id' => $order_id,'pat_id' => $pat_id,'phone' => $phone);
    				$ajax_return = array('code'=>1,'msg'=>'获取成功！','data'=>$returnData);
    			} else {
    				$ajax_return = array('code'=>0,'msg'=>'获取失败！','data'=>'');
    			}
    		} else {
    			$ajax_return = array('code'=>2,'msg'=>'今日已经显示过了','data'=>'');
    		}

    	} else {
    		$ajax_return = array('code'=>0,'msg'=>'获取失败！','data'=>'');
    	}

    	echo json_encode($ajax_return);

    }


    /**
     * ajax显示当天是否已经点击过的电话号码
     */
    public function ajax_show_clicked_phone()
    {

    	header('content-type:application/json;charset=utf-8');

    	if (!$this->input->is_ajax_request()) {
    		show_404();
    	}

    	$admin_id = intval($_COOKIE['l_admin_id']);
    	$time = time();
    	$s_t = strtotime(date('Y-m-d 00:00:00',$time));
    	$e_t = strtotime(date('Y-m-d 23:59:59',$time));

    	//查找当天当前用户已经点击显示过号码的order_id,pat_id
    	$where = array('admin_id'=>$admin_id,'add_time >='=>$s_t,'add_time <='=>$e_t);
    	$this->db->set_dbprefix("henry_");
    	$records = $this->db->select('order_id,pat_id')->get_where('show_phone_record', $where)->result_array();

    	$pat_id_array = array();
    	foreach ($records as $key => $value) {
    		$pat_id_array[] = $value['pat_id'];
    	}

    	//获取电话号码
    	$this->db->set_dbprefix("hui_");
    	$info = $this->db->select('pat_id,pat_phone,pat_phone1')->where_in('pat_id', $pat_id_array)->get('patient')->result_array();

    	//重组数据，输出最终数据
    	$final = array();
    	foreach ($info as $key => $value) {
    		foreach ($records as $k => $val) {
    			if ($value['pat_id'] == $val['pat_id']) {
    				$final[$key]['order_id'] = $val['order_id'];
    				$final[$key]['pat_id'] = $val['pat_id'];
    				if (!empty($value['pat_phone1'])) {
    					$final[$key]['pat_phone'] = $value['pat_phone'].'/'.$value['pat_phone1'];
    				} else {
    					$final[$key]['pat_phone'] = $value['pat_phone'];
    				}
    			}
    		}
    	}

    	if ($final) {
			$ajax_return = array('code'=>1,'msg'=>'获取成功！','data'=>$final);
    	} else {
    		$ajax_return = array('code'=>0,'msg'=>'获取失败！','data'=>'');
    	}

    	echo json_encode($ajax_return);

    }


    /**
     * 根据时间和项目查询点击显示电话记录
     */
    public function showPhoneRecord(){
        //$this->output->enable_profiler(TRUE);
        $data = array();
        $data = $this->common->config('showPhoneRecord');

        $date = $this->input->get('date',true);
        $time = time();

        if (empty($date)) {

            $start_time = strtotime(date('Y-m-d 00:00:00',$time));
            $end_time = strtotime(date('Y-m-d 23:59:59',$time));
            $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);

        } else {

            $arr_dt = explode(' - ', $date);
            $reg = '/^\d{4}年\d{1,2}月\d{1,2}日$/';
            if ( count($arr_dt)>1 && preg_match($reg, $arr_dt[0]) && preg_match($reg, $arr_dt[1]) ) {
                $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
                $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);
                $start_time = strtotime($s_t." 00:00:00");
                $end_time = strtotime($e_t." 23:59:59");
                $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            } else {
            	$start_time = strtotime(date('Y-m-d 00:00:00',$time));
            	$end_time = strtotime(date('Y-m-d 23:59:59',$time));
            	$data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
            }

        }

        $per_page_num = 15;
        $per_page = !empty($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;

    	$where = array('a.add_time >='=>$start_time,'a.add_time <='=>$end_time);

    	$hos_id_arr = array_unique(explode(',', $_COOKIE['l_hos_id']));
    	$keshi_id_arr = array_unique(explode(',', $_COOKIE['l_keshi_id']));

        $this->db->set_dbprefix("");
        $this->db->select('a.admin_id,a.order_id,a.pat_id,a.add_time,count(a.admin_id) as number,b.admin_username,b.admin_name,c.hos_id,c.keshi_id,g.rank_name');
        $this->db->from('henry_show_phone_record as a');
        $this->db->join('hui_admin as b', 'b.admin_id = a.admin_id','left');
        $this->db->join('hui_order as c', 'c.order_id = a.order_id','left');
        $this->db->join('hui_rank as g', 'g.rank_id = b.rank_id','left');
        $this->db->where($where);
        //排除超级管理员
        if ($_COOKIE['l_admin_action'] != 'all') {
        	$this->db->where_in('c.hos_id',$hos_id_arr);
        	$this->db->where_in('c.keshi_id',$keshi_id_arr);
        }
        $this->db->group_by('a.admin_id');
        $this->db->limit($per_page_num,$per_page);
        $this->db->order_by('number', 'DESC');

        $info = $this->db->get()->result_array();

        //p($this->db->last_query());die();
        //p($info);die();

        $this->db->select('a.admin_id,a.order_id,a.pat_id,a.add_time,count(a.admin_id) as number,c.hos_id');
        $this->db->from('henry_show_phone_record as a');
        $this->db->join('hui_order as c', 'c.order_id = a.order_id','left');
        $this->db->where($where);
        //排除超级管理员
    	if ($_COOKIE['l_admin_action'] != 'all') {
    		$this->db->where_in('c.hos_id',$hos_id_arr);
    		$this->db->where_in('c.keshi_id',$keshi_id_arr);
    	}
        $this->db->group_by('a.admin_id');
        $res = $this->db->get()->result_array();
        //p($res);die();

        $this->load->library('pagination');
        $this->load->helper('page');//调用CI自带的page分页类
        $config = page_config();
        $config['base_url'] = '?c=order&m=showPhoneRecord&date='.$date;

        $config['total_rows'] = count($res);
        $config['per_page'] = $per_page_num;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $data['page'] = $this->pagination->create_links();
        $data['parse'] = '&date='.$data['date'];


        $data['info'] = $info;
        $data['top'] = $this->load->view('top', $data, true);
        $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
        $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
        $this->load->view('order_show_phone_record', $data);
    }



    /**
     * ajax获取具体点击显示过的号码记录
     */
    public function showPhoneDetailRecord()
    {

    	header('content-type:application/json;charset=utf-8');

    	if (!$this->input->is_ajax_request()) {
    		show_404();
    	}

    	$admin_id = intval($this->input->post('aid'));
    	$date = $this->input->post('date',true);
        $time = time();

    	if (!empty($date)) {
    	    $arr_dt = explode(' - ', $date);
    	    if (count($arr_dt)<2) {
    	        $ajax_return = array('code'=>0,'msg'=>'获取失败！日期格式错误','data'=>'');
    	        echo json_encode($ajax_return);
    	        exit();
    	    }
    	    foreach ($arr_dt as $key => $value) {
    	        if (!preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $value)) {
    	            $ajax_return = array('code'=>0,'msg'=>'获取失败！日期格式错误','data'=>'');
    	            echo json_encode($ajax_return);
    	            exit();
    	        }
    	    }

    	    $s_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[0]),0,-1);
    	    $e_t = substr(str_replace(array("年","月","日"), "-", $arr_dt[1]),0,-1);

    	    $start_time = strtotime($s_t." 00:00:00");
    	    $end_time = strtotime($e_t." 23:59:59");
    	    $data['date'] = date('Y年m月d日',$start_time).' - '.date('Y年m月d日',$end_time);
    	} else {
    	    $start_time = strtotime(date('Y-m-d 00:00:00',$time));
    	    $end_time = strtotime(date('Y-m-d 23:59:59',$time));
    	}



    	//查找用户已经点击显示过号码记录
    	$where = array('a.admin_id'=>$admin_id,'a.add_time >='=>$start_time,'a.add_time <='=>$end_time);

    	$hos_id_arr = array_unique(explode(',', $_COOKIE['l_hos_id']));
    	$keshi_id_arr = array_unique(explode(',', $_COOKIE['l_keshi_id']));

    	$this->db->set_dbprefix("");
    	$this->db->select('a.admin_id,a.add_time,a.order_id,c.order_no,c.hos_id,c.keshi_id,e.hos_name,f.keshi_name,d.pat_phone,d.pat_phone1');
    	$this->db->from('henry_show_phone_record as a');
    	$this->db->join('hui_admin as b', 'b.admin_id = a.admin_id','left');
    	$this->db->join('hui_order as c', 'c.order_id = a.order_id','left');
    	$this->db->join('hui_patient as d', 'd.pat_id = c.pat_id','left');
    	$this->db->join('hui_hospital as e', 'e.hos_id = c.hos_id','left');
    	$this->db->join('hui_keshi as f', 'f.keshi_id = c.keshi_id','left');
    	$this->db->where($where);
    	//排除超级管理员
    	if ($_COOKIE['l_admin_action'] != 'all') {
    		$this->db->where_in('c.hos_id',$hos_id_arr);
    		$this->db->where_in('c.keshi_id',$keshi_id_arr);
    	}
    	$this->db->order_by('a.add_time', 'DESC');
    	$result = $this->db->get()->result_array();

    	foreach ($result as $key => $value) {
    		$result[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
    	}

    	if ($result) {
			$ajax_return = array('code'=>1,'msg'=>'获取成功！','data'=>$result);
    	} else {
    		$ajax_return = array('code'=>0,'msg'=>'获取失败！','data'=>'');
    	}

    	echo json_encode($ajax_return);

    }


    /**
     * 设置不孕留联分组
     */
    public function ajax_set_priority(){
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $order_id = intval($_REQUEST['order_id']);
        $pat_group = intval($_REQUEST['pat_group']);
        if ($pat_group == 1) {
        	$groupText = "A组";
        } elseif ($pat_group == 2) {
        	$groupText = "B组";
        } elseif ($pat_group == 3) {
        	$groupText = "C组";
        } elseif ($pat_group == 4) {
        	$groupText = "D组";
        }

        $this->db->set_dbprefix("henry_");

        $res = $this->db->get_where('liulian_group', array('liulian_id' => $order_id))->row_array();


        if (!empty($res)) {

        	$this->db->update($this->common->table('liulian_group'),array('type' => $pat_group),array('liulian_id' => $order_id ));

    	    $result = array('code' => 1, 'msg' => '修改成功', 'group' => $pat_group, 'groupText' => $groupText);
    	    echo json_encode($result);
    	    exit();

        } else {
        	$this->db->insert($this->common->table('liulian_group') , array(
        	    'liulian_id' => $order_id,
        	    'admin_id' => intval($_COOKIE['l_admin_id']),
        	    'add_time' => time(),
        	    'type' => $pat_group
        	));
        }

        if ($this->db->insert_id()) {
            $result = array('code' => 1, 'msg' => '加入成功', 'group' => $pat_group, 'groupText' => $groupText);
            echo json_encode($result);
        } else {
            $result = array('code' => 2, 'msg' => '加入失败');
            echo json_encode($result);
        }

        exit();

    }




}