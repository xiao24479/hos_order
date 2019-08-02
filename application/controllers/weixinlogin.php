<?php
header("Content-type: text/html; charset=utf-8"); 
defined('BASEPATH') OR exit('No direct script access allowed');

class weixinlogin extends CI_Controller {
	 
     public function __construct(){
	   parent::__construct(); 
	   $this->load->database();
	   $this->load->library('session');
	}
 
	/**
	 * 采用静默授权
	 * 1.已关注的用户，可以直接授权进入该微网站
	 * 2.没有关注的用户且从来没有关注过的则跳转关注页面，要求用户关注 
	 */
	public function index(){
		$code =$this->input->get("code");
		$code   = trim($code); 
		//需要 和 login_weixin_scan.php 中配置一样
        $appid = 'wxe747c8ddc2edaef9';
		$secret = '76422de10457fc2afcff871a7224bace';
		//需要 和 login_weixin_scan.php 中配置一样
		$appid = 'wx745a64041574e32b';
		$secret = '69f8b42224415cf9a618119300fdd36e'; 
	 
		//取得openid
		$oauthUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
		$html = file_get_contents($oauthUrl);  
		$oauth = json_decode($html,true); 
		if(isset($oauth['openid'])){
			//检查当前openid是否已经绑定了预约系统的账号  已绑定的直接登录成功 未绑定的先绑定 ，然后执行登录
			//拿到openid 根据openID获取到用户基本信息 然后登录成功 跳往系统首页
			$row = $this->common->getRow("SELECT admin_id FROM " . $this->common->table("admin")." where openid = '".$oauth['openid']."'");
			if($row)
			{
				$newtime = $this->input->get("newtime");
				if(!empty($newtime)){
					//绑定
					$arr = array('new_apply_sign_time' => $this->input->get("newtime"));
					$this->db->where('admin_id', $row['admin_id']);
					$this->db->update($this->common->table('admin'), $arr);

					//写入cookie
					setcookie('l_openid', $oauth['openid'], 0, "/");
					setcookie('l_newtime', $newtime, 0, "/");
					
					//进入绑定账号界面
					$data = array();
					$data['msg'] = '已经绑定账户,PC系统已经自动登录。'; 
					$this->load->view('login_weixin_msg',$data);
				}else{
					$this->load->view('login',array()); 
				} 
			}else{
				//进入绑定账号界面
				$data = array();
				$data['msg'] = '请输入你的预约系统账户和密码，并确认绑定。';
				$data['openid'] = $oauth['openid'];
				$data['newtime'] = $this->input->get("newtime");//随机编码
				$this->load->view('login_weixin_bd',$data);
			}
		}else{
			$this->load->view('login',array());
		}  
	}
}
