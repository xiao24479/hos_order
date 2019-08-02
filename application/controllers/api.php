<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 接口控制器
 * User: Henry
 * Date: 2018/12/31
 */

class Api extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }


    public function pushPatientNumber()
    {
        header("Content-type:application/json");

        $exec_time = time();
        $exec_st = strtotime(date('Y-m-d 00:00:00',time()));
        $exec_et = strtotime(date('Y-m-d 02:00:00',time()));

        //限制访问时间
        if ($exec_st > $exec_time || $exec_et < $exec_time ) {
            $status = 400;
            $msg = 'request at invalid time';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        if (strtolower($this->input->server('REQUEST_METHOD')) != "post") {
            $status = 400;
            $msg = 'bad request!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $timeStamp = $this->input->post('t');
        $randomStr = $this->input->post('r');
        $signature = $this->input->post('s');

        $params = array('token' => 'cO$zu&U2Prcb3Yri4xjZlp8Pply0PC$9');

        $this->load->library('apiLib',$params);

        if (!$this->apilib->is_valid($timeStamp,$randomStr,$signature)) {
            $status = 400;
            $msg = 'invalid parameters!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        //只针对仁爱不孕不育
        $hos_id = 1;
        $keshi_id = 32;

        //每次获取当月所有就诊卡号
        //以便获取当月复诊的患者数据

        //获取当月数据
        // $time = time();
        // $cur_month_start=date('Y',$time).'-'.(date('m',$time)).'-01';
        // $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
        // $s_time = strtotime($cur_month_start . ' 00:00:00');
        // $e_time = strtotime($cur_month_end . ' 23:59:59');


        //从2019年一月起算
        $start_year = "2019-01";
        $end_year = date('Y-m');

        $date1 = explode('-',$end_year);
        $date2 = explode('-',$start_year);
        $yue = abs($date1[0] - $date2[0]) * 12 - $date2[1] + abs($date1[1])+1;//相差月份

        $sst = date('Y-m-d 00:00:00');
        $sse = date('Y-m-d 23:59:59');

        //执行定时任务
        //判断该月是否在当天已提交
        //每月数据都进行一次删除更新，保持数据有效性
        for ($i = 1; $i < $yue+1; $i++) {

              $cur_date = date('Y-m',strtotime($date2[0] + floor($i/12).'-'.$i%12));

              $rs = $this->db->query("select * from henry_patient_consumption_record where data_time = '$cur_date' and add_time >= '$sst' and add_time <= '$sse'")->result_array();

              if (!$rs) {
                  break;
              }

              if ($i == $yue) {
                $srt = true;
              }

        }

        //阻止重复插入
        if ($srt) {
            $status = 400;
            $msg = 'repeat operation: '.$cur_date.' data had been updated!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
        }

        $time = strtotime($cur_date);
        $cur_month_start=date('Y',$time).'-'.(date('m',$time)).'-01';
        $cur_month_end=date('Y-m-d',strtotime("$cur_month_start +1 month -1 day"));
        $s_time = strtotime($cur_month_start . ' 00:00:00');
        $e_time = strtotime($cur_month_end . ' 23:59:59');

        //*********警告***********
        //时间只能整月不能跨月

        //自定义按时间选择要导入的ho数据
        // $s_time = strtotime("2019-01-01 00:00:00");
        // $e_time = strtotime("2019-01-31 23:59:59");

        $this->db->select('order_no,his_jzkh,come_time');
        $this->db->where('hos_id',$hos_id);
        $this->db->where('keshi_id',$keshi_id);
        $this->db->where('come_time >=',$s_time);
        $this->db->where('come_time <=',$e_time);
        $info = $this->db->get('order')->result_array();
        //$this->db->last_query();die();
        if (empty($info)) {
            $status = 400;
            $msg = 'fail! maybe doesn\'t satisfy the qurey condition or internal error.';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $status = 200;
        $msg = 'success!';
        $this->log($status,json_encode($info),$msg);
        echo returnApiData($status,$info,$msg);
        exit;

    }

    /**
     * 接收院内定时发送过来的数据并插入数据
     * @return [type] [description]
     */
    public function receiveHoData()
    {
        header("Content-type:application/json");
        if (strtolower($this->input->server('REQUEST_METHOD')) != "post") {
            $status = 400;
            $msg = 'bad request!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $timeStamp = $this->input->post('t');
        $randomStr = $this->input->post('r');
        $signature = $this->input->post('s');
        $info = $this->input->post('data');

        $params = array('token' => 'cO$zu&U2Prcb3Yri4xjZlp8Pply0PC$9');

        $this->load->library('apiLib',$params);

        if (!$this->apilib->is_valid($timeStamp,$randomStr,$signature)) {
            $status = 400;
            $msg = 'invalid parameters!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $info = unserialize(base64_decode($info));

        if (!empty($info['error'])) {
            $status = 400;
            $msg = 'find empty data according to renaidata sys jzkh!';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $this->db->set_dbprefix("henry_");


        //****警告****
        //必须保证data_time有值
        //根据该批次请求数据的到诊时间，获取请求数据的最新添加时间
        //根据最新添加时间，然后删除最近一次的添加的消费数据
        if (!empty($info['data_time'])) {

            $where = array(
                'data_time' => date('Y-m',strtotime($info['data_time']))
            );

            $last_add_time = $this->db->select('max(add_time) as add_time')->get_where('patient_consumption_record',$where)->row_array();

            if (!empty($last_add_time['add_time'])) {
                $this->db->delete('patient_consumption', array('add_time' => $last_add_time['add_time']));
                $status = 200;
                $msg = 'delelte '.$last_add_time['add_time'].' data success,affected row: '.$this->db->affected_rows();
                $this->log($status,'',$msg);
            }

        }

        $add_mark_time = $info['add_time'];
        if (!empty($info['mz'])) {
            // foreach ($info['mz'] as $key => $value) {
            //     $info['mz'][$key] = convert_arr_charset($value);
            // }
           $this->db->insert_batch('patient_consumption', $info['mz']);
        }

        if (!empty($info['zy'])) {
            // foreach ($info['zy'] as $key => $value) {
            //     $info['zy'][$key] = convert_arr_charset($value);
            // }
            $this->db->insert_batch('patient_consumption', $info['zy']);
        }

        //保存最新添加数据时间
        $this->db->insert('patient_consumption_record', array('data_time' => date('Y-m',strtotime($info['data_time'])),'add_time' => $add_mark_time));


        $res_num = $this->db->select('count(add_time) as count')->get_where('patient_consumption',array('add_time' => $add_mark_time))->row_array();


        // $this->db->last_query();

        if ($res_num['count'] == 0) {
            $status = 400;
            $msg = 'fail! maybe doesn\'t satisfy the qurey condition (no patient see the doctor) or internal error.';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }

        $affected_row = array(
            'affected_row' => array(
                'row' => $res_num['count'],
            )
        );
        $status = 200;
        $msg = 'success!';
        $this->log($status,json_encode($affected_row),$msg);
        echo returnApiData($status,$affected_row,$msg);
        exit;

    }

    private function log($status,$data='',$msg)
    {
        $data = array(
            'ip'       => ips(),
            'referer'  => $this->input->server('HTTP_REFERER'),
            'current_url'  => $this->input->server('HTTP_HOST').$this->input->server('REQUEST_URI'),
            'date'     => date('Y-m-d H:i:s',time()),
            'data'  => $data,
            'msg'  => $msg,
            'status'   => $status,
        );
        $this->db->set_dbprefix("henry_");
        $this->db->insert('patient_consumption_log', $data);
    }

    /**
     *  不孕不育关爱工程
     *  根据患者提交的预约号、电话号码查询是否存在该预约号
     *
     */
    public function fetchPatientUid()
    {
        header("Content-type:application/json");

        if (strtolower($this->input->server('REQUEST_METHOD')) != "post") {
            $status = 400;
            $msg = 'fail :(';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }



        $timeStamp = $this->input->post('t');
        $randomStr = $this->input->post('r');
        $signature = $this->input->post('s');

        $order_no = trim($this->input->post('order_no',true));
        $phone = trim($this->input->post('phone',true));
        $patient_name = trim($this->input->post('patient_name',true));

        $params = array('token' => '1anHI^QUirKU3XTTmBE2OtRSp1zldvH*');

        $this->load->library('apiLib',$params);

        if (!$this->apilib->is_valid($timeStamp,$randomStr,$signature)) {
            $status = 410;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        }

        $orderWhere = array(
            'order_no' => $order_no
        );

        $order = $this->db->get_where('order',$orderWhere)->row_array();

        if (empty($order)) {
            $status = 420;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        }

        $patient = $this->db->where('pat_phone', $phone)->like('pat_name', $patient_name, 'after')->get('patient')->row_array();

        if (empty($patient)) {
            $status = 430;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        } else {
            $this->db->set_dbprefix("henry_");

            //已经领取了0元查不孕活动且未超过24小时就不能领取这个活动
            $card = $this->db->get_where('guanai_card',$orderWhere)->row_array();
            $freeCard = $this->db->get_where('guanai_check_card',$orderWhere)->row_array();
            if (!empty($freeCard) && empty($card)) {
                $cur_time = time();
                $add_time = strtotime($freeCard['add_time']);
                if ($cur_time - $add_time <= 24*60*60) {
                    $status = 450;
                    $msg = 'fail :(';
                    echo returnApiData($status,'',$msg);
                    exit;
                }
            }

            //已经提交成功的患者，始终只能获取一个电子卡号
            if (!empty($card)) {

                //如果上次短信发送失败就重新发送
                if ($card['is_sendMsg'] == 0) {

                    $result = $this->sendMsg($card['card_no'],$phone,$patient_name,$order_no);

                    if ($result > 0) {
                        $sendMsg = 1;
                    } else {
                        $sendMsg = 0;
                    }

                    $updatedata = array('is_sendMsg'=>$sendMsg,'send_time'=>date('Y-m-d H:i:s'));

                    $this->db->update('guanai_card', $updatedata, array('card_no' => $card['card_no']));

                    $status = 200;
                    $msg = 'success!';
                    $info = array('order_no'=>$order_no,'card_no'=>$card['card_no'],'sendMsg' => $sendMsg);

                } else {
                    $status = 200;
                    $msg = 'success!';
                    $info = array('order_no'=>$order_no,'card_no'=>$card['card_no'],'sendMsg' => $card['is_sendMsg']);
                }

                echo returnApiData($status,$info,$msg);
                exit;

            }

            $uuid = $this->make_uid();

            $is_res = $this->db->get_where('guanai_card',array('card_no'=>$uuid))->row_array();

            //循环查找是否存在相同uuid，直到查不到为止
            while ($is_res) {
                $uuid = $this->make_uid();
                $is_res = $this->db->get_where('guanai_card',array('card_no'=>$uuid))->row_array();
            }


            $inert_data = array(
                'order_no' => $order_no,
                'card_no'  => $uuid,
                'add_time'  => date('Y-m-d H:i:s')
            );

            $bool = $this->db->insert('guanai_card',$inert_data);

            if (!$bool) {
                $status = 440;
                $msg = 'fail :(';
                echo returnApiData($status,'',$msg);
                exit;
            }

            $insert_id = $this->db->insert_id();

            $result = $this->sendMsg($uuid,$phone,$patient_name,$order_no);

            if ($result > 0) {
                $sendMsg = 1;
            } else {
                $sendMsg = 0;
            }

            $updatedata = array('is_sendMsg'=>$sendMsg,'send_time'=>date('Y-m-d H:i:s'));

            $this->db->update('guanai_card', $updatedata, array('id' => $insert_id));

            //print_r($this->db->last_query());

            //备注电子卡号
            $remark = array(
                'order_id' => $order['order_id'],
                'admin_id' => 0,
                'admin_name' => $phone,
                'mark_content' => $uuid,
                'mark_time' => time(),
                'mark_type' => 7,
                'type_id' => 0
            );

            $this->db->set_dbprefix("hui_");
            $this->db->insert('order_remark', $remark);

            $status = 200;
            $msg = 'success!';
            $info = array('order_no'=>$order_no,'card_no'=>$uuid,'is_sendMsg' => $sendMsg);
            echo returnApiData($status,$info,$msg);
            exit;
        }



    }


    /**
     * 生成订单ID
     * @return [type] [description]
     */
    private function make_uid(){
       return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);//2019012887876
    }


    /**
     * 发送短信
     * @param  [type] $uuid         [description]
     * @param  [type] $phone        [description]
     * @param  [type] $patient_name [description]
     * @param  [type] $order_no     [description]
     * @return [type]               [description]
     */
    private function sendMsg($uuid,$phone,$patient_name,$order_no){

        //短信接口配置信息
        $content = $uuid.'这是您的广东省孕育关爱工程99元造影电子卡号，请您保存!您的预约号：'.$order_no.',姓名：'.$patient_name.'，电话：'.$phone.'.如有疑问请拨打我们关爱办电话：0755-88308018。【广东省孕育关爱工程办公室】';
        $account = 'sdk_gdguanai';
        $password = 'guanai2019';

        $this->load->helper(sms);

        return sms_jianzhou($phone,$content,$account,$password);
    }



    /**
     *  0元查不孕
     *  根据患者提交的预约号、电话号码查询是否存在该预约号
     *
     */
    public function freeCheckBuYun()
    {
        header("Content-type:application/json");

        if (strtolower($this->input->server('REQUEST_METHOD')) != "post") {
            $status = 400;
            $msg = 'fail :(';
            $this->log($status,'',$msg);
            echo returnApiData($status,'',$msg);
            exit;
        }



        $timeStamp = $this->input->post('t');
        $randomStr = $this->input->post('r');
        $signature = $this->input->post('s');

        $order_no = trim($this->input->post('order_no',true));
        $phone = trim($this->input->post('phone',true));
        $patient_name = trim($this->input->post('patient_name',true));

        $params = array('token' => '%@d^v#*YTmDRW7hVptu1@90&18vs4oJX');

        $this->load->library('apiLib',$params);

        if (!$this->apilib->is_valid($timeStamp,$randomStr,$signature)) {
            $status = 410;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        }

        $orderWhere = array(
            'order_no' => $order_no
        );

        $order = $this->db->get_where('order',$orderWhere)->row_array();

        if (empty($order)) {
            $status = 420;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        }

        $patient = $this->db->where('pat_phone', $phone)->like('pat_name', $patient_name, 'after')->get('patient')->row_array();

        if (empty($patient)) {
            $status = 430;
            $msg = 'fail :(';
            echo returnApiData($status,'',$msg);
            exit;
        } else {
            $this->db->set_dbprefix("henry_");

            //已经领取了99照影且未超过24小时就不能领取这个活动
            $card = $this->db->get_where('guanai_check_card',$orderWhere)->row_array();
            $zyCard = $this->db->get_where('guanai_card',$orderWhere)->row_array();

            if (!empty($zyCard) && empty($card)) {
                $cur_time = time();
                $add_time = strtotime($zyCard['add_time']);
                if ($cur_time - $add_time <= 24*60*60) {
                    $status = 450;
                    $msg = 'fail :(';
                    echo returnApiData($status,'',$msg);
                    exit;
                }
            }

            //已经提交成功的患者，始终只能获取一个电子卡号
            if (!empty($card)) {

                //如果上次短信发送失败就重新发送
                if ($card['is_sendMsg'] == 0) {

                    $result = $this->sendMsgByFreeCheck($card['card_no'],$phone,$patient_name,$order_no);

                    if ($result > 0) {
                        $sendMsg = 1;
                    } else {
                        $sendMsg = 0;
                    }

                    $updatedata = array('is_sendMsg'=>$sendMsg,'send_time'=>date('Y-m-d H:i:s'));

                    $this->db->update('guanai_check_card', $updatedata, array('card_no' => $card['card_no']));

                    $status = 200;
                    $msg = 'success!';
                    $info = array('order_no'=>$order_no,'card_no'=>$card['card_no'],'sendMsg' => $sendMsg);

                } else {
                    $status = 200;
                    $msg = 'success!';
                    $info = array('order_no'=>$order_no,'card_no'=>$card['card_no'],'sendMsg' => $card['is_sendMsg']);
                }

                echo returnApiData($status,$info,$msg);
                exit;

            }

            $uuid = $this->make_uid();

            $is_res = $this->db->get_where('guanai_check_card',array('card_no'=>$uuid))->row_array();

            //循环查找是否存在相同uuid，直到查不到为止
            while ($is_res) {
                $uuid = $this->make_uid();
                $is_res = $this->db->get_where('guanai_check_card',array('card_no'=>$uuid))->row_array();
            }


            $inert_data = array(
                'order_no' => $order_no,
                'card_no'  => $uuid,
                'add_time'  => date('Y-m-d H:i:s')
            );

            $bool = $this->db->insert('guanai_check_card',$inert_data);

            if (!$bool) {
                $status = 440;
                $msg = 'fail :(';
                echo returnApiData($status,'',$msg);
                exit;
            }

            $insert_id = $this->db->insert_id();

            $result = $this->sendMsgByFreeCheck($uuid,$phone,$patient_name,$order_no);

            if ($result > 0) {
                $sendMsg = 1;
            } else {
                $sendMsg = 0;
            }

            $updatedata = array('is_sendMsg'=>$sendMsg,'send_time'=>date('Y-m-d H:i:s'));

            $this->db->update('guanai_check_card', $updatedata, array('id' => $insert_id));

            //print_r($this->db->last_query());

            //备注电子卡号
            $remark = array(
                'order_id' => $order['order_id'],
                'admin_id' => 0,
                'admin_name' => $phone,
                'mark_content' => $uuid,
                'mark_time' => time(),
                'mark_type' => 8,
                'type_id' => 0
            );

            $this->db->set_dbprefix("hui_");
            $this->db->insert('order_remark', $remark);

            $status = 200;
            $msg = 'success!';
            $info = array('order_no'=>$order_no,'card_no'=>$uuid,'is_sendMsg' => $sendMsg);
            echo returnApiData($status,$info,$msg);
            exit;
        }

    }


    /**
     * 0元查不孕发送短信
     * @param  [type] $uuid         [description]
     * @param  [type] $phone        [description]
     * @param  [type] $patient_name [description]
     * @param  [type] $order_no     [description]
     * @return [type]               [description]
     */
    private function sendMsgByFreeCheck($uuid,$phone,$patient_name,$order_no){

        //短信接口配置信息
        $content = $uuid.'这是您的广东省孕育关爱工程电子卡号，请您保存!您的预约号：'.$order_no.',姓名：'.$patient_name.'，电话：'.$phone.'.如有疑问请拨打电话：0755-88308018。【广东省孕育关爱工程办公室】';
        $account = 'sdk_gdguanai';
        $password = 'guanai2019';

        $this->load->helper(sms);

        return sms_jianzhou($phone,$content,$account,$password);
    }



}