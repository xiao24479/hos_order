<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Show extends CI_Controller

{

	private $_data = array();

	private $hos_id;

	private $wx_id;

	private $hos_list = array(1=>'深圳仁爱医院',2=>'深圳鹏程医院',3=>'台州五洲生殖医院',7=>'测试医院');

	private $menu_ditu = array(1=>'renaiditu',2=>'pengchengditu',3=>'wuzhouditu',7=>'ceshiditu');

	private $swt_url = array(1=>'http://www.renaidata.com/hos_1.html',2=>'http://kfr.zoossoft.cn/LR/Chatpre.aspx?id=KFR31671888',3=>'http://kfr.zoossoft.cn/LR/Chatpre.aspx?id=KFR31671888',7=>'http://kfr.zoossoft.cn/LR/Chatpre.aspx?id=KFR31671888');

	private $hos_position = array(1=>'22.5203135523|114.0411331694',2=>'22.5203135523|114.0411331694',3=>'22.5203135523|114.0411331694',7=>'22.5203135523|114.0411331694');

	private $appid = 'wx745a64041574e32b';

	private $appsecret = 'a92fd833fd805a180bc7251804aa3db8';

	private $access_token;

	const OAUTH_PREFIX = 'https://open.weixin.qq.com/connect/oauth2';

	const OAUTH_AUTHORIZE_URL = '/authorize?';

	const OAUTH_TOKEN_PREFIX = 'https://api.weixin.qq.com/sns/oauth2';

	const OAUTH_USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo?';

	const OAUTH_TOKEN_URL = '/access_token?';

	const API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin';

	const AUTH_URL = '/token?grant_type=client_credential&';

	const USER_INFO_URL='/user/info?';

	const GROUP_ID_URL='/groups/getid?';

	public function __construct()

	{

		parent::__construct();

		$this->load->helper('url');

		$this->load->library(array('form_validation','session'));

		$this->load->model('posts_model');

		$this->load->model('metas_model');

		// 获取医院id

		$this->get_hos_id();

		$this->_data['openid'] = isset($_COOKIE['u_openid']) ? $_COOKIE['u_openid'] : '';

		$this->_data['ditu'] = $this->menu_ditu[$this->hos_id];

		$this->_data['swt_url'] = $this->swt_url[$this->hos_id];

		$this->_data['hos_id'] = $this->hos_id;

		$this->_data['code_url'] = isset($_COOKIE['u_openid']) ? 'http://www.renaidata.com/?c=show&m=order' : $this->getOauthRedirect('http://www.renaidata.com/?c=show&m=order',1,'snsapi_base','http://www.renaidata.com/?c=show&m=order');

		$this->_data['user_url'] = isset($_COOKIE['u_openid']) ? 'http://www.renaidata.com/?c=show&m=get_order_weixin&openid='.$_COOKIE['u_openid'] : $this->getOauthRedirect('http://www.renaidata.com/?c=show&m=get_order_weixin',2,'snsapi_userinfo','');



	}



	public function get_ditu()

	{

		$openid = $_COOKIE['u_openid'];

		$hos_position= $this->hos_position[$this->hos_id];

		$this->_data['hos_pos'] = explode('|',$hos_position);

		$pos = $this->common->getOne('select pos from '.$this->common->table('wx_user').' where openid = "'.$openid.'"');

		$position = explode('|',$pos);

		$this->_data['position'] = $position;

		$this->load->view('web/position',$this->_data);

	}

	//获取授权code

	private function getOauthAccessToken($code,$state=''){

		if (!$code) return false;

		$result = $this->http_get(self::OAUTH_TOKEN_PREFIX.self::OAUTH_TOKEN_URL.'appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code');

		if ($result)

		{

			$json = json_decode($result,true);

			if (!$json || !empty($json['errcode'])) {

				$this->errCode = $json['errcode'];

				$this->errMsg = $json['errmsg'];

				return false;

			}

			//对用户信息进行纠正

			$flag = $this->user_info_check($json['openid']);

			if(!$flag && $state){

				$this->user_info_unsub($json['access_token'],$json['openid']);

			}

			return $json;

		}

		return false;

	}



	private function user_info_unsub($access_token,$openid)

	{

		$user_info = $this->getOauthUserinfo($access_token,$openid);

		$data = array(

			'openid' =>$user_info['openid'],

			'nickname' =>$user_info['nickname'],

			'sex' =>$user_info['sex'],

			'headimgurl' =>$user_info['headimgurl'],

			'province' =>$user_info['province'],

			'city' =>$user_info['city'],

			'country' =>$user_info['country'],

			'subscribe' =>0,

			'wx_id' => $this->wx_id,

		);

		$this->db->insert($this->common->table('wx_user'), $data);

	}



	private function getOauthUserinfo($access_token,$openid){

		$result = $this->http_get(self::OAUTH_USERINFO_URL.'access_token='.$access_token.'&openid='.$openid);

		if ($result)

		{

			$json = json_decode($result,true);

			if (!$json || !empty($json['errcode'])) {

				$this->errCode = $json['errcode'];

				$this->errMsg = $json['errmsg'];

				return false;

			}

			return $json;

		}

		return false;

	}

	private function getUserInfo($openid){

		if (!$this->access_token && !$this->checkAuth()) return false;

		$result = $this->http_get(self::API_URL_PREFIX.self::USER_INFO_URL.'access_token='.$this->access_token.'&openid='.$openid);

		if ($result)

		{

			$json = json_decode($result,true);

			if (isset($json['errcode'])) {

				$this->errCode = $json['errcode'];

				$this->errMsg = $json['errmsg'];

				return false;

			}

			return $json;

		}

		return false;

	}



	private function user_info_check($openid)

	{

		$user_info = $this->getUserInfo($openid);

		$sql = 'select wx_uid from '.$this->common->table('wx_user').' where openid = "'.$openid.'"';

		$wx_uid = $this->common->getOne($sql);

		if($wx_uid){

			return $wx_uid;

		}

		$sun_flag = $user_info['subscribe'] ? true : false;

		if($sun_flag){

				unset($user_info['remark']);

				unset($user_info['language']);

				$gid = $this->userFrom($openid);

				$user_info['gid'] = $gid['groupid'];

				$user_info['wx_id'] = 2;

				$this->db->insert($this->common->table('wx_user'), $user_info);

				$wx_uid = $this->db->insert_id();

				return $wx_uid;

		}else{

			return false;

		}

	}

	public function order_data()

	{

		$op = trim($_REQUEST['op']);

		if(empty($op)){

			if(!isset($_COOKIE['l_admin_id'])){

				redirect('c=show');

			}

		}else{

			$check = $this->admin_check($op);

			if(!check){

				redirect('c=show');

			}

		}

		//统计每日预约，遇到，实到，信息

		// 组装时间参数，默认当天

		$d = $this->input->get('p',true);

		$hos_id = $this->input->get('hos_id',true);

		$hos = $this->common->get_hosname();

		$hos_data = array();

		foreach($hos as $val){

			$hos_data[$val['hos_id']] = $val['hos_name'];

		}

		$this->_data['hos_data'] = $hos_data;

		$this->_data['hos'] = $hos;

		$hos_id = empty($hos_id) ?  $hos[0]['hos_id'] : intval($hos_id);

		$this->_data['hos_id'] = $hos_id;

		$time = time();

		if(!empty($d)){

			$this->_data['d'] = $d;

			$time = $time - $d*24*3600;

		}else{

			$this->_data['d'] = 0;

		}

		$day = date("Y-m-d", $time);

		$this->_data['day'] = $day;

		$w_start = strtotime($day . ' 00:00:00');

		$w_end = strtotime($day . ' 23:59:59');

		$this->load->model('order_model');

		$res = $this->order_model->order_data($w_end,$w_start,$hos_id);

		$w_start -= 24*3600;

		$w_end   -= 24*3600;

		$last_res = $this->order_model->order_data($w_end,$w_start,$hos_id);

		$this->_data['order'] = $res;

		$this->_data['last_order'] = $last_res;

		$this->load->view('web/order_data',$this->_data);

	}

	public function order_data_type()

	{

		if(!isset($_COOKIE['l_admin_id'])){

			redirect('c=show');

		}

		$hos_id = $this->input->get('hos_id',true);

		$hos = $this->common->get_hosname();



		$hos_data = array();

		foreach($hos as $val){

			$hos_data[$val['hos_id']] = $val['hos_name'];

		}

		$this->_data['hos_data'] = $hos_data;

		$this->_data['hos'] = $hos;

		$type = $this->input->get('type',true);

		$this->_data['type'] = $type;

		$hos_id = empty($hos_id) ?  $hos[0]['hos_id'] : intval($hos_id);

		$this->_data['hos_id'] = $hos_id;

		//默认显示天数据

		$time = time();

		$yue = date('m',$time);

		$year = date('Y',$time);

		$this->_data['year'] = $year;

		$this->_data['yue'] = $yue;

		if('zhou'==$type){

			$tag_str = '%u';

			//统计最近6周的数据

			$w_start = strtotime("-5 week");

			$this->_data['title'] = '周数据对比';

		}else if('yue'==$type){

			$tag_str = '%c';

			// 统计最近6个月的数据

			$this->_data['title'] = '月数据对比';

			$w_start = strtotime("-5 month");

			$w_start = strtotime(date('Y-m-1',$w_start));

			$yue = date('m',$w_start);

		}else{

			$tag_str = '%d';

			// 统计最近7天的数据

			$this->_data['title'] = '日数据对比';

			//初始时间

			$has = $time-strtotime(date('Y-m-d'));

			$w_start = strtotime("-6 day")-$has;

		}

		$w_end = $time;

		$this->load->model('order_model');

		$res = $this->order_model->order_data_type($w_end,$w_start,$hos_id,$tag_str);

		$order_data = array();

		foreach($res['count'] as $val){

			$order_data[$val['tag']]['count'] = $val[count];

		}

		foreach($res['come'] as $val){

			$order_data[$val['tag']]['come'] = $val[count];

		}

		foreach($res['will_come'] as $val){

			$order_data[$val['tag']]['will_come'] = $val[count];

		}

		if($type=='yue'){

			$flag = array();

			$yue = intval($yue);

			if($yue<=7){

				for($yue,$j=$yue+5;$yue<=$j;$yue++){

					$flag[$yue] = $order_data[$yue];

				}

			}else{

				$i = 0;

				for($yue;$yue<=12;$yue++){

					$flag[$yue] = $order_data[$yue];

					$i++;

				}

				$j = 6-$i;

				for($i=1;$i<=$j;$i++){

					$flag[$i] = $order_data[$i];

				}

			}

			$order_data = $flag;

		}else if($type=='zhou'){

			$w = intval(date('W'));

			if($w<7){

				$flag = array();

				for($i=1,$j=$w+1;$i<$j;$i++){

					$tag = '0'.$i;

					$flag[$tag] = $order_data[$tag];

					unset($order_data[$tag]);

					$order_data[$tag] = $flag[$tag];

				}

			}

		}else{

			$j = date('j');

			//对本月.本年前六天数据调整，

			if($j<7){

				$one = array();

				$two = array();

				$y = date('Y');

				$n = date('n');

				if($n!=1){

					foreach($order_data as $key=>$val){

						if($key>7){

							$new_yue = $n-1;

							$new_tag = $y.'-'.$new_yue.'-'.$key;

							$one[$new_tag] = $val;

						}else{

							$new_yue = $n;

							$new_tag = $y.'-'.$new_yue.'-'.$key;

							$two[$new_tag] = $val;

						}



					}

				}else{

					foreach($order_data as $key=>$val){

						if($key>7){

							$new_year = $y-1;

							$new_yue = $n-1;

							$new_tag = $y.'-'.$new_yue.'-'.$key;

							$one[$new_tag] = $val;

						}else{

							$new_year = $y;

							$new_yue = $n;

							$new_tag = $y.'-'.$new_yue.'-'.$key;

							$two[$new_tag] = $val;

						}

					}

				}

				$order_data = array_merge($one,$two);

			}

		}

		$this->_data['order'] = $order_data;

		$this->load->view('web/order_data_type',$this->_data);

	}

	public function choujiang()

	{

		$openid = $this->input->get('openid',true);

		// 检测抽奖有效期

		$this->check_chou_time();

		// 检测是否关注微信

		$user_info = $this->getUserInfo($openid);

		$gid = $this->userFrom($openid);

		$sun_flag = $user_info['subscribe'] ? true : false;

		//检查当前用户是否已经添加到系统数据库中

		if($sun_flag){

			$sql = 'select wx_uid from '.$this->common->table('wx_user').' where openid = "'.$openid.'"';

			$wx_uid = $this->common->getOne($sql);

			if(empty($wx_uid)){

				unset($user_info['remark']);

				unset($user_info['language']);



				$user_info['gid'] = $gid['groupid'];

				$user_info['wx_id'] = 2;

				$this->db->insert($this->common->table('wx_user'), $user_info);

				$wx_uid = $this->db->insert_id();

			}



		}

		//检查当前用户的抽奖次数(用户id,抽奖时间,抽取到的id)

		$num = $this->user_chou_num($wx_uid);



		$this->_data['sun_flag'] = $sun_flag;

		$this->_data['prize_num'] = $num;

		$this->_data['wx_uid'] = $wx_uid;

		$this->load->view('web/choujiang',$this->_data);

	}



	private function check_chou_time()

	{

		$time = time();



		$start_time = strtotime('2014-11-5');

		$end_time = $start_time+15*24*3600;

		if($time<$start_time ){

			$this->common->msg('抽奖活动尚未开始');

		}else if($time>$end_time){



			$this->common->msg('抽奖活动已经结束');

		}



	}



	private function user_chou_num($wx_uid)

	{

		$sql = 'select count(1) as count from '.$this->common->table('prize_data').' where wx_uid = '.$wx_uid;

		$count = $this->common->getOne($sql);

		return $count;

	}

	// 获取用户所在分组信息

	public function userFrom($openid){

		if (!$this->access_token && !$this->checkAuth()) return false;

		$data = array(

				'openid'=>$openid

		);

		$result = $this->http_post(self::API_URL_PREFIX.self::GROUP_ID_URL.'access_token='.$this->access_token,self::json_encode($data));

		if ($result)

		{

			$json = json_decode($result,true);

			if (isset($json['errcode'])) {

				$this->errCode = $json['errcode'];

				$this->errMsg = $json['errmsg'];

				return false;

			}

			return $json;

		}

		return false;



	}





	public function chou_data()

	{

		$wx_uid = $this->input->post('wx_uid',true);

		// 奖项数据

		$prize_arr = array(

			'0' => array('id'=>1,'min'=>array(0,339),'max'=>array(21,360),'prize'=>'特等奖','v'=>1,'num'=>1),

			'1' => array('id'=>2,'min'=>249,'max'=>292.5,'prize'=>'一等奖','v'=>2,'num'=>4),

			'2' => array('id'=>3,'min'=>159,'max'=>202.5,'prize'=>'二等奖','v'=>2,'num'=>2),

			'3' => array('id'=>4,'min'=>array(69,294),'max'=>array(112.5,337.5),'prize'=>'三等奖','v'=>3,'num'=>10),

			'4' => array('id'=>5,'min'=>array(24,114,204),'max'=>array(67.5,157.5,247.5),'prize'=>'纪念奖','v'=>92,'num'=>20),

		);

		// 统计当前奖项库存

		$has_prize = $this->prize_stock();

		foreach ($prize_arr as $key => $val){

				$arr[$val['id']] = $val['v'];

		}



		$rid = $this->getRand($arr); //根据概率获取奖项id

		//检测库存,如果库存已经完结,发给用户纪念

		if($rid != 5){

			if($has_prize[$rid] >= $prize_arr[$rid-1]['num']){

				$rid = 5;

			}

		}

		$res = $prize_arr[$rid-1]; //中奖项



		// 进行库存检测,如果当前商品抽完,重新抽奖



		$data = array(

			'wx_uid'=>$wx_uid,

			'time'=>time(),

			'pid'=>$rid

		);

		$this->db->insert($this->common->table('prize_data'),$data);

		$min = $res['min'];

		$max = $res['max'];

		if($res['id']==5){

			$i = mt_rand(0,2);

			$result['angle'] = mt_rand($min[$i],$max[$i]);

		}else if($res['id']==1 || $res['id']==4){

			$i = mt_rand(0,1);

			$result['angle'] = mt_rand($min[$i],$max[$i]);

		}else{

			$result['angle'] = mt_rand($min,$max); //随机生成一个角度

		}

		$result['prize'] = $res['prize'];



		echo json_encode($result);



	}

	public function prize_stock()

	{

		$sql = 'select count(1) as count , pid  from '.$this->common->table('prize_data').' group by pid';

		$res = $this->common->getAll($sql);

		$data = array();

		foreach ($res as $val){

			$data[$val['pid']] = $val['count'];

		}

		return $data;

	}

	// 随机取获奖信息

	private function getRand($proArr)

	{

		$result = '';



		//概率数组的总概率精度

		$proSum = array_sum($proArr);



		//概率数组循环

		foreach ($proArr as $key => $proCur) {

			$randNum = mt_rand(1, $proSum);

			if ($randNum <= $proCur) {

				$result = $key;

				break;

			} else {

				$proSum -= $proCur;

			}

		}

		unset ($proArr);



		return $result;

	}



	public function checkAuth($appid='',$appsecret='')

	{

		$token = $this->read_txt('token');



		$token = explode("|", $token);

		if(isset($token[1]) && ((time() - $token[1]) < 7200))

		{

			$this->access_token = $token[0];

			return $this->access_token;

		}else{



			if (!$appid || !$appsecret) {

				$appid = $this->appid;

				$appsecret = $this->appsecret;

			}

			//TODO: get the cache access_token

			$result = $this->http_get(self::API_URL_PREFIX.self::AUTH_URL.'appid='.$appid.'&secret='.$appsecret);

			if ($result)

			{

				$json = json_decode($result,true);

				if (!$json || isset($json['errcode'])) {

					$this->errCode = $json['errcode'];

					$this->errMsg = $json['errmsg'];

					return false;

				}

				$this->access_token = $json['access_token'];

				$expire = $json['expires_in'] ? intval($json['expires_in'])-100 : 3600;

				//TODO: cache access_token

				$this->write_txt('token',$this->access_token . '|' . time());

				return $this->access_token;

			}

			return false;

		}

	}



	// 写入缓存数据

	private function write_txt($cache_name,$string){



		 $cache_file_path = APPPATH . 'cache/static/' . $cache_name . '.txt';



		 file_put_contents($cache_file_path, $string);

	}



	private function read_txt($cache_name)

	{

		$cache_file_path = APPPATH . 'cache/static/' . $cache_name . '.txt';



		$token = file_get_contents($cache_file_path);

	}



	// 组装获取code的链接

	private function getOauthRedirect($callback,$state='',$scope='snsapi_userinfo',$default=''){

		if(strpos($_SERVER['HTTP_USER_AGENT'],"MicroMessenger"))

		{

			return self::OAUTH_PREFIX.self::OAUTH_AUTHORIZE_URL.'appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';

		}else{

			return $default;

		}

	}

	private function get_hos_id()

	{

		if(isset($_COOKIE['u_hos_id'])){

			$this->hos_id = $_COOKIE['u_hos_id'];

		}else{

			$this->hos_id = 1;

			$this->set_hos_id(1);

		}

		$this->_data['hos_name'] = $this->hos_list[$this->hos_id];

	}

	public function index()

	{

		$hos_id = $this->hos_id;

		// 获取当前医院下的所有文章

		$where = " and p.status = 'publish' and p.hos_id = $hos_id";

		//分页操作

		$page = $this->input->get('per_page',TRUE);

		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 0;

		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):10;

		$per_page = empty($per_page)? 10:$per_page;



		$posts = $this->posts_model->get_posts('post',$per_page,$page,$where);



		$posts_all = $this->posts_model->get_posts('post',100000,0,$where);

		$posts_count = count($posts_all);



		foreach($posts as $key=>$post){



			// 获取文章分类信息

			$this->metas_model->get_metas($post['pid']);

			$posts[$key]['categories']= $this->metas_model->metas['category'];

		}

		$this->_data['posts'] = $posts;

		$this->load->library('pagination');



		$this->load->helper('page');



		$config = web_page_config();



		$config['total_rows'] = $posts_count;



		$config['per_page'] = $per_page;



		$config['uri_segment'] = 10;



		$config['num_links'] = 5;



		$config['base_url'] = '?c=show&m=index&p='.$per_page;



		$this->pagination->initialize($config);



		$this->_data['page'] = $this->pagination->create_links();



		$this->load->view('web/index',$this->_data);

	}



	private function set_hos_id($hos_id)

	{

		$this->hos_id = $hos_id;

		setcookie('u_hos_id', $hos_id, time()+24*60*60, "/");

	}

	private function set_wx_id($wx_id)

	{

		$this->wx_id = $wx_id;

		setcookie('u_wx_id', $wx_id, time()+24*60*60, "/");

	}



	private function set_openid($openid)

	{

		setcookie('u_openid', $openid, time()+24*60*60, "/");

	}



	public function user_edit()

	{

		$pat_id = $this->input->get('pat_id',true);

		$openid = $_COOKIE['u_openid'];

		$sql = 'select username,phone,age,qq,email,sex from '.$this->common->table('wx_user').' where openid = "'.$openid.'"';

		$wx_info = $this->common->getRow($sql);

		if(empty($wx_info['username'])){

			$sql = 'select pat_name,pat_phone,pat_sex,pat_qq,pat_email,pat_age from '.$this->common->table('patient').' where pat_id = '.$pat_id;

			$user_info = $this->common->getRow($sql);

			$this->_data['username'] = $user_info['pat_name'];

			$this->_data['phone'] = $user_info['pat_phone'];

			$this->_data['sex'] = $user_info['pat_sex'];

			$this->_data['qq'] = $user_info['pat_qq'];

			$this->_data['email'] = $user_info['pat_email'];

			$this->_data['age'] = $user_info['pat_age'];

		}else{

			$this->_data['username'] = $wx_info['username'];

			$this->_data['phone'] = $wx_info['phone'];

			$this->_data['sex'] = $wx_info['sex'];

			$this->_data['qq'] = $wx_info['qq'];

			$this->_data['email'] = $wx_info['email'];

			$this->_data['age'] = $wx_info['age'];

		}



		$content = $this->get_form_data();



		if(empty($content['username'])||empty($content['sex'])||empty($content['age'])||empty($content['phone'])||empty($content['qq'])){



			$this->load->view('web/user_edit',$this->_data);

		}else{

			$this->user_ins($content,$openid);

		}

	}



	private function user_ins($content,$openid)

	{

		$data = array(

			'username' 	=> empty($content['username']) ? '' : trim($content['username']),

			'sex' 	   	=>  intval($content['sex']),

			'age' 		=> empty($content['age']) ? 0 : intval($content['age']),

			'phone' 	=> empty($content['phone']) ? null : intval($content['phone']),

			'qq' 		=> empty($content['qq']) ? null : intval($content['qq']),

			'email'  	=> empty($content['email']) ? null : trim($content['email']),

		);



		$this->db->update($this->common->table('wx_user'), $data, array('openid' => $openid));

		redirect('c=show&m=get_order_weixin&openid='.$openid);

	}



	private function get_form_data()

	{

		return array(

			'username' =>$this->input->post('username',true),

			'sex' =>$this->input->post('sex',true),

			'age' =>$this->input->post('age',true),

			'phone' =>$this->input->post('phone',true),

			'qq' =>$this->input->post('qq',true),

			'email' =>$this->input->post('email',true),

		);

	}

	public function view()

	{

		$slug = $this->input->get('slug',true);

		$pid = $this->input->get('pid',true);

		$to_wx = $this->input->get('to_wx',true);

		if($to_wx){



			$this->setHosIdByWx($to_wx);

		}

		if(!empty($slug)){

			$post = $this->posts_model->get_post_by_id('slug', $slug);



		}else if(!empty($pid)){



			$post = $this->posts_model->get_post_by_id('pid', $pid);

		}else{



			redirect('c=show');

		}





		if(!$post){

			show_404();

		}

		/*

		$this->set_hos_id($post['hos_id']);

		// 设置用户访问的医院域

		$this->set_hos_id($post['hos_id']);

		*/

		$click = $post['click']+1;

		$this->posts_model->update_post($post['pid'],array('click'=>$click));

		//所属分类

		$metas = $this->metas_model->get_metas($post['pid']);

		$this->_data['category'] = $metas['category'];



		$this->_data['tag'] = $metas['tag'];

		$this->_data['title'] = $post['title'];

		$this->_data['content'] = $post['text'];

		$this->_data['modified'] = date('Y-m-d',$post['modified']);

		$this->_data['click'] = $click;

		$this->_data['pid'] = $post['pid'];

		$this->_data['good'] = $post['good'];

		$this->load->view('web/show',$this->_data);



	}



	public function cate()

	{

		$mid = $this->input->get('mid',true);



		//分页操作

		$page = $this->input->get('per_page',TRUE);

		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 0;

		$offset = 10;

		$limit = $page * $offset;

		if($page < 0)

		{

			redirect('c=show&m=cate&mid='.$mid);

		}



		$posts = $this->posts_model->get_posts_by_meta($mid, 'category', 'post', 'publish', 'posts.*',  $offset,$limit)->result_array();



		$this->_data['posts'] = $posts;

		$this->set_hos_id($posts[0]['hos_id']);

		$posts_count = $this->posts_model->get_posts_by_meta($mid, 'category', 'post', 'publish', 'posts.*', 10000, 0)->num_rows();

		if($posts_count){

			$this->_data['meta_name'] = $posts[0]['m_name'];

		}else{

			$this->_data['meta_name'] = '';

		}

		$this->load->library('pagination');



		$this->load->helper('page');



		$config = web_page_config();



		$config['total_rows'] = $posts_count;



		$config['per_page'] = $offset;



		$config['uri_segment'] = 10;



		$config['num_links'] = 5;



		$config['base_url'] = '?c=show&m=cate&mid='.$mid;



		$this->pagination->initialize($config);



		$this->_data['page'] = $this->pagination->create_links();



		$this->load->view('web/list',$this->_data);

	}



	private function setHosIdByWx($to_wx)

	{

		$wxid = trim($to_wx);

		$data = $this->common->getRow('select hos_id,wx_id from '.$this->common->table('weixin').' where wxid = "'.$wxid.'"');

		$this->set_hos_id($data['hos_id']);

		$this->set_wx_id($data['wx_id']);

	}

	private function admin_login($openid)

	{

		if(!empty($openid))

		{

			$row = $this->common->getRow("SELECT * FROM " . $this->common->table('admin') . " WHERE admin_openid = '" . $openid . "'");

			if(!$row){

				return false;

			}

			$cookie_time = 0;

			$hos_keshi = $this->common->getAll("SELECT hos_id, keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $row['rank_id']);

			$hos_str = '';

			$keshi_str = '';

			if(!empty($hos_keshi))

			{

				foreach($hos_keshi as $val)

				{

					$hos_str .= $val['hos_id'] . ",";

					$keshi_str .= $val['keshi_id'] . ",";

				}

				$hos_str = substr($hos_str, 0, -1);

				$keshi_str = substr($keshi_str, 0, -1);

			}

			setcookie('l_admin_id', $row['admin_id'], $cookie_time, "/");

			setcookie('l_admin_username', $row['admin_username'], $cookie_time, "/");

			setcookie('l_admin_name', $row['admin_name'], $cookie_time, "/");

			setcookie('l_rank_id', $row['rank_id'], $cookie_time, "/");

			setcookie('l_admin_action', $row['admin_action'], $cookie_time, "/");

			setcookie('l_hos_id', $hos_str, $cookie_time, "/");

			setcookie('l_keshi_id', $keshi_str, $cookie_time, "/");

			$arr = array('admin_lasttime' => $row['admin_nowtime'],

						 'admin_nowtime' => time(),

						 'admin_logintimes' => $row['admin_logintimes'] + 1,

						 'admin_lastip' => $row['admin_nowip'],

						 'admin_nowip' => getip(),

						 'log_errs' => 0);

			$this->db->update($this->common->table('admin'), $arr, array('admin_id' => $row['admin_id']));

			redirect('c=show&m=order_data&op='.$openid);

		}

	}

	public function admin_check($openid)

	{

		$row = $this->common->getRow("SELECT * FROM " . $this->common->table('admin') . " WHERE admin_openid = '" . $openid . "'");

		if(!$row){

			return false;

		}

		$cookie_time = 0;

		$hos_keshi = $this->common->getAll("SELECT hos_id, keshi_id FROM " . $this->common->table('rank_power') . " WHERE rank_id = " . $row['rank_id']);

		$hos_str = '';

		$keshi_str = '';

		if(!empty($hos_keshi))

		{

			foreach($hos_keshi as $val)

			{

				$hos_str .= $val['hos_id'] . ",";

				$keshi_str .= $val['keshi_id'] . ",";

			}

			$hos_str = substr($hos_str, 0, -1);

			$keshi_str = substr($keshi_str, 0, -1);

		}

		setcookie('l_admin_id', $row['admin_id'], $cookie_time, "/");

		setcookie('l_admin_username', $row['admin_username'], $cookie_time, "/");

		setcookie('l_admin_name', $row['admin_name'], $cookie_time, "/");

		setcookie('l_rank_id', $row['rank_id'], $cookie_time, "/");

		setcookie('l_admin_action', $row['admin_action'], $cookie_time, "/");

		setcookie('l_hos_id', $hos_str, $cookie_time, "/");

		setcookie('l_keshi_id', $keshi_str, $cookie_time, "/");

		return true;

	}



	public function get_order_weixin()

	{

		$openid = $this->input->get('openid',true);

		//检测是否是admin账户，如果是amin账户自动登录

		$this->admin_login($openid);



		$to_wx = $this->input->get('to_wx',true);

		if($to_wx){



			$this->setHosIdByWx($to_wx);

		}

		$sql = 'select username,phone,age,qq,email,sex from '.$this->common->table('wx_user').' where openid = "'.$openid.'"';

		$wx_info = $this->common->getRow($sql);

		if(!empty($wx_info['username'])){

			$this->_data['user_wx_info'] = $wx_info;

		}

		$code = $this->input->get('code',true) ? $this->input->get('code',true) : '';

		if(!empty($code)){

			$state = $this->input->get('state',true);

			$res = $this->getOauthAccessToken($code,$state);

			$openid = $res['openid'];

		}

		$this->set_openid($openid);

		$sql = "select o.*, p.*,k.keshi_name,k.hos_id,u.admin_name as name from ".$this->common->table('order'). " o

				left join ".$this->common->table('patient'). " p on o.pat_id = p.pat_id

				left join ".$this->common->table('keshi'). " k on o.keshi_id = k.keshi_id

				left join ".$this->common->table('order_out'). " u on o.order_id = u.order_id

				where p.pat_weixin = '$openid'  order by order_addtime desc";

		$res = $this->common->getAll($sql);

		if($res){

			$this->_data['hos_id'] = $this->hos_id;

		}

		$this->_data['order'] = $res;

		$this->load->view('web/user',$this->_data);

	}

	public function get_order_id(){

		$order_id = $this->input->get('order_id',true);



		$sql = "select o.*, p.*,k.keshi_name,k.hos_id,u.admin_name as name from ".$this->common->table('order'). " o

				left join ".$this->common->table('patient'). " p on o.pat_id = p.pat_id

				left join ".$this->common->table('keshi'). " k on o.keshi_id = k.keshi_id

				left join ".$this->common->table('order_out'). " u on o.order_id = u.order_id

				where o.order_id = $order_id";



		$res = $this->common->getAll($sql);

		$this->_data['hos_id'] = $this->hos_id;

		$this->_data['order'] = $res;

		$this->load->view('web/user',$this->_data);

	}







	public function up_num()

	{

		$pid = $this->input->post('pid',true);

		$this->posts_model->up_num($pid);

	}

	//栏目展示

	private function menu()

	{

		return array(

			1=>array(

				'医院'=>array(

					array('text'=>'医院概况','url'=>''),

					array('text'=>'科室介绍','url'=>''),

					array('text'=>'进入医院','url'=>''),

				),

				'专家'=>$this->get_doc_hot(1),

				'产科中心'=>array(

					array('text'=>'产前检查','url'=>''),

					array('text'=>'四维彩超','url'=>''),

					array('text'=>'孕妈课堂','url'=>''),

					array('text'=>'分娩套餐','url'=>''),

					array('text'=>'预约分娩','url'=>''),

					array('text'=>'DNA检测','url'=>''),

					array('text'=>'月子中心','url'=>''),

					array('text'=>'产后恢复','url'=>''),

					array('text'=>'孕妈工具','url'=>''),

				),

				'健康常识'=>array(

					array('text'=>'孕妈工具','url'=>''),

					array('text'=>'孕妈工具','url'=>''),

					array('text'=>'孕妈工具','url'=>''),

				),

				'活动中心'=>array(

				)

			),

		);

	}



	private function get_doc_hot($hos_id)

	{

		//通过医院id查看医院下面的医生分类





	}



	public function order()

	{

		$user = $this->input->get('docter_name',true);

		$hos_id = $this->hos_id;

		$this->_data['hos_id'] = $this->hos_id;

		$code = $this->input->get('code',true) ? $this->input->get('code',true) : '';

		if(!empty($code)){



			$res = $this->getOauthAccessToken($code);

			$this->_data['openid'] = $res['openid'];

			$this->set_openid($res['openid']);

		}

		if(isset($_COOKIE['u_openid'])){

			$this->_data['openid'] = $_COOKIE['u_openid'];

		}

		if(!empty($user)){

			$user = trim($user);

			//通过医生姓名匹配医生信息

			$keshi = $this->common->getRow('select k.keshi_id,k.keshi_name from '.$this->common->table('docter').' d left join '.$this->common->table('keshi').' k on k.keshi_id = d.keshi_id where d.hos_id = '.$hos_id.' and d.doc_name = "'.$user.'"');

			$this->_data['doc_name'] = $user;

			$this->_data['keshi'] = $keshi;

		}else{

			//取当前医院下的科室

			$keshi_list = $this->common->getAll('select keshi_id,keshi_name from '.$this->common->table('keshi').' where hos_id = '.$hos_id);

			$this->_data['keshi_list'] = $keshi_list;

		}

		$this->load->view('web/order',$this->_data);

	}

	public function dean()

	{

		$this->load->view('web/dean',$this->_data);

	}

	public function ask_dean()

	{

		$name = $this->input->post('name',true);

		$phone = $this->input->post('phone',true);

		$email = $this->input->post('email',true);

		$topic = $this->input->post('topic',true);

		$content = $this->input->post('content',true);

		$ask_dean = array('phone'=>$phone,'name'=>$name,'email'=>$email,'topic'=>$topic,'content'=>$content);

		$this->db->insert($this->common->table("ask_dean"), $ask_dean);

		$ask_id = $this->db->insert_id();

		if($ask_id){

			$post['to'] = '2020912290@qq.com';

			$post['topic'] = $topic;

			$post['content'] = $content.'<br/>姓名：'.$name.'<br/>电话：'.$phone;

			//发送邮件

			$this->send_mail($post);

			$msg_detail = '给院长留言成功';

		}else{

			$msg_detail = '给院长留言失败';

		}

		$data['msg_detail'] = $msg_detail;

		$veiw = $this->load->view('web/message',$data,true);

		echo $veiw;

	}

	private function send_mail($post)

	{

		$config['protocol'] = 'smtp';

		$config['smtp_host'] = 'smtp.163.com';

		$config['smtp_user'] = 'ren_1991qing@163.com';

		$config['smtp_pass'] = 'ren5658bo';

		$config['charset'] = 'utf-8';

		$config['wordwrap'] = TRUE;

		$config['mailtype'] = "html";

		$this->load->library('email');

		$this->email->initialize($config);

		$this->email->from('ren_1991qing@163.com', '仁爱邮件发送平台');

		$this->email->to($post['to']);

		$this->email->subject($post['topic']);

		$this->email->message($post['content']);

		$this->email->send();

	}

	public function order_ins_web()

	{

		$pat_name = $_REQUEST['fromname'];

		$pat_phone = $this->input->post('lianxi',true);

		$mark_content = $_REQUEST['note'];

		$email = $_REQUEST['email'];

		$from_value = $this->input->post('p',true);

		$pat_age = $this->input->post('age',true);

		$pat_sex = $this->input->post('sex',true);

		$hos_id = $this->input->post('hos_id',true);

		$keshi_id = $this->input->post('keshi_id',true);



		$type = $this->input->post('type',true);

		//如果$type = 1 ，hos_id = 3的时候，默认为妇科

		if(empty($keshi_id) && $hos_id == 3){

			$keshi_id = 4;

			if($type == 1){

				$keshi_id = 26;

			}

		}



		$jb_parent_id 	 = $this->input->post('p_jb',true);

		$order_time = $this->input->post('ordertime',true);

		if(empty($order_time)){

			$order_time = time();

		}

		if(mb_detect_encoding($pat_name, 'UTF-8,GBK')=='CP936'){

			$mark_content = mb_convert_encoding($mark_content, "UTF-8", "GBK");

			$pat_name = mb_convert_encoding($pat_name, "UTF-8", "GBK");

			$email = mb_convert_encoding($email, "UTF-8", "GBK");

		}



		if(empty($pat_phone)||!is_numeric($pat_phone)){

			$msg_detail = '留言失败 ,您的手机号码不合法';

			$data['msg_detail'] = $msg_detail;

			$veiw = $this->load->view('web/message',$data,true);

			echo $veiw;

			exit();

		}



		$name_len = mb_strlen($pat_name);

		if(empty($pat_name)||$name_len>5){

			$msg_detail = '留言失败，您输入的姓名不合法';

			$data['msg_detail'] = $msg_detail;

			$veiw = $this->load->view('web/message',$data,true);

			echo $veiw;

			exit();

		}

		if($this->text_check($mark_content)){

			$msg_detail = '留言失败，您输入的内容不合法';

			$data['msg_detail'] = $msg_detail;

			$veiw = $this->load->view('web/message',$data,true);

			echo $veiw;

			exit();

		}

		if ($hos_id == 1 && $keshi_id == 32) {
			$pat_where = array('pat_phone'=>$pat_phone,'pat_name'=>$pat_name);
			$res = $this->db->get_where($this->common->table("pat_data"),$pat_where)->result_array();
			if (!empty($res)) {
				$msg_detail = '留言已提交过，我们会尽快联系您';
				$data['msg_detail'] = $msg_detail;
				$veiw = $this->load->view('web/message',$data,true);
				echo $veiw;
				exit();
			}
		}

		$patient = array('pat_phone'=>$pat_phone,'pat_name'=>$pat_name,'pat_email'=>$email,'pat_age'=>$pat_age,'pat_sex'=>$pat_sex);

		$patient = $this->phone_to_tag($patient,$pat_phone);

		$this->db->insert($this->common->table("pat_data"), $patient);

		$pat_id = $this->db->insert_id();



		if($pat_id){

			$order['pat_id'] = $pat_id;

			$order['hos_id'] = intval($hos_id);

			$order['keshi_id'] = intval($keshi_id);

			$order['order_addtime'] = time();

			$order['from_parent_id'] = 96;

			$order['from_value'] = trim($from_value);

			$order['jb_parent_id'] = intval($jb_parent_id);

			if(empty($order_time)){

				$order['order_null_time'] = '未定';

			}else{

				$order_time = trim($order_time);

				$order['order_time'] = strtotime($order_time);

			}

			$this->db->insert($this->common->table('order_mes'),$order);

			$order_id = $this->db->insert_id();

			// 添加用户备注信息

			if(!empty($mark_content)){

				$remark = array('order_id' => $order_id,



								'mark_content' => $mark_content,



								'mark_time' => time(),



								);



				$this->db->insert($this->common->table("mes_content"), $remark);

			}

			if($order_id > 0){

				$msg_detail = '留言成功，我们会尽快联系您';

				//来自仁爱不孕关爱工程99套餐
				//发送短信
				$rabyga = $this->input->post('rabyga',true);
				if ($hos_id == 1 && $keshi_id == 32 && $rabyga == 1) {
					//短信接口配置信息
					$content = "您已报名成功，稍后我们的工作人员将会与您联系确认预约。如有疑问请拨打我们关爱办电话：0755-88308018。【广东省孕育关爱工程办公室】";
					$account = 'sdk_gdguanai';
					$password = 'guanai2019';

					$this->load->helper(sms);
					sms_jianzhou($pat_phone,$content,$account,$password);
				}

				//来自仁爱不孕关爱工程官网
				//发送短信
				$rabygaweb = $this->input->post('rabygaweb',true);
				$cur_hours = intval(date('H'));
				if ($hos_id == 1 && $keshi_id == 32 && $rabygaweb == 1 && ($cur_hours>=18 || $cur_hours < 8)) {
					//短信接口配置信息
					$content = "广东省孕育关爱工程留言，姓名：{$pat_name}  电话：{$pat_phone}。【广东省孕育关爱工程办公室】";
					$account = 'sdk_gdguanai';
					$password = 'guanai2019';

					$this->load->helper(sms);
					sms_jianzhou(19925447416,$content,$account,$password);
				}

			}

		}else{

			$msg_detail = '留言失败,您的信息没有被记录';

		}

		$data['msg_detail'] = $msg_detail;

		$this->load->view('web/message',$data);

	}



	public function order_ins_web_return_msg()

	{

		$pat_name = $_REQUEST['fromname'];

		$pat_phone = $this->input->post('lianxi',true);

		$mark_content = $_REQUEST['note'];

		$email = $_REQUEST['email'];

		$from_value = $this->input->post('p',true);

		$pat_age = $this->input->post('age',true);

		$pat_sex = $this->input->post('sex',true);

		$hos_id = $this->input->post('hos_id',true);

		$keshi_id = $this->input->post('keshi_id',true);

		$jb_parent_id 	 = $this->input->post('p_jb',true);

		$order_time = $this->input->post('ordertime',true);

		if(mb_detect_encoding($pat_name, 'UTF-8,GBK')=='CP936'){

			$mark_content = mb_convert_encoding($mark_content, "UTF-8", "GBK");

			$pat_name = mb_convert_encoding($pat_name, "UTF-8", "GBK");

			$email = mb_convert_encoding($email, "UTF-8", "GBK");

		}



		if(empty($pat_phone)||!is_numeric($pat_phone)){

			echo  '留言失败 ,您的手机号码不合法';

			exit();

		}



		$name_len = mb_strlen($pat_name);

		if(empty($pat_name)||$name_len>5){

			echo '留言失败，您输入的姓名不合法';

			exit();

		}

		if($this->text_check($mark_content)){

			echo '留言失败，您输入的内容不合法';

			exit();

		}

		$patient = array('pat_phone'=>$pat_phone,'pat_name'=>$pat_name,'pat_email'=>$email,'pat_age'=>$pat_age,'pat_sex'=>$pat_sex);

		$patient = $this->phone_to_tag($patient,$pat_phone);

		$this->db->insert($this->common->table("pat_data"), $patient);

		$pat_id = $this->db->insert_id();



		if($pat_id){

			$order['pat_id'] = $pat_id;

			$order['hos_id'] = intval($hos_id);

			$order['keshi_id'] = intval($keshi_id);

			$order['order_addtime'] = time();

			$order['from_parent_id'] = 96;

			$order['from_value'] = trim($from_value);

			$order['jb_parent_id'] = intval($jb_parent_id);

			if(empty($order_time)){

				$order['order_null_time'] = '未定';

			}else{

				$order_time = trim($order_time);

				$order['order_time'] = strtotime($order_time);

			}



			$this->db->insert($this->common->table('order_mes'),$order);

			$order_id = $this->db->insert_id();

			// 添加用户备注信息

			if(!empty($mark_content)){

				$remark = array('order_id' => $order_id,



								'mark_content' => $mark_content,



								'mark_time' => time(),



								);



				$this->db->insert($this->common->table("mes_content"), $remark);

			}

			if($order_id > 0){

				$msg_detail = '留言成功，我们会尽快联系您';

			}

		}else{

			$msg_detail = '留言失败,您的信息没有被记录';

		}

		echo $msg_detail;

		exit();

	}





	public function message_del()

	{

		$this->common->config('message_del');

		$order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;

		$sql = 'select pat_id from '.$this->common->table('order_mes').' where order_id = '.$order_id;

		$pat_id = $this->common->getOne($sql);

		$this->db->delete($this->common->table('order_mes'), array('order_id' => $order_id));

		$this->db->delete($this->common->table('pat_data'), array('pat_id' => $pat_id));

		$this->db->delete($this->common->table('mes_content'), array('order_id' => $order_id));

		$this->common->msg('该条留言已经删除');

	}

	public function dean_del()

	{

		$this->common->config('dean_del');

		$ask_id = isset($_REQUEST['ask_id']) ? intval($_REQUEST['ask_id']) : 0;

		$this->db->delete($this->common->table('ask_dean'), array('ask_id' => $ask_id));

		$this->common->msg('该条留言已经删除');

	}

	public function text_check($text)

	{

		if(preg_match("/http:[\/]{2}[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*[.]*[html|php|jsp|asp]/",$text)){

			return true;

		}

		return false;

	}

	public function order_ins()

	{



		$hos_id = $_COOKIE['u_hos_id'];

		$doc_name = $this->input->post('doc_name',true);

		$pat_name = $this->input->post('pat_name',true);

		$pat_phone = $this->input->post('pat_phone',true);

		$keshi_id = $this->input->post('keshi',true);

		$order_time = $this->input->post('order_time',true);

		$mark_content = $this->input->post('mark_content',true);

		$openid = $this->input->post('openid',true);

		if(!$this->phone_check($pat_phone)){

			$this->common->msg('手机号码不正确');

		}

		if(!empty($doc_name)){



		}

			$patient = array('pat_phone'=>$pat_phone,'pat_name'=>$pat_name,'pat_weixin'=>$openid);

			$patient = $this->phone_to_tag($patient,$pat_phone);

			$this->db->insert($this->common->table("patient"), $patient);

			$pat_id = $this->db->insert_id();



			if($pat_id){

				$order['pat_id'] = $pat_id;

				$order['hos_id'] = $hos_id;

				$order['keshi_id'] = $keshi_id;

				$order['admin_id'] = 212;

				$order['admin_name'] = '微信预约';

				$order['from_parent_id'] = 74;

				$order['from_id'] = 101;

				$order['order_addtime'] = time();

				if(empty($order_time)){

					$order['order_null_time'] = '未定';

				}else{



					$order['order_time'] = time()+$order_time*24*3600;

				}

				$order['order_no'] = $this->get_order_no();



				$this->db->insert($this->common->table('order'),$order);

				$order_id = $this->db->insert_id();

				if(isset($order['order_time'])){

					$send_content = $pat_name."先生您好,您在".date('Y-m-d H:i',$order['order_addtime'])."预订了我院的诊号,预约好号为:{$order['order_no']},预约时间为".date('Y-m-d H:i',$order['order_time'])."请安排好您的时间,按时就诊【仁爱医院】";

				}else{

					$send_content = $pat_name."先生您好,您在".date('Y-m-d H:i',$order['order_addtime'])."预订了我院的诊号,预约好号为:{$order['order_no']},预约时间为未定,请安排好您的时间,及时时就诊【仁爱医院】";

				}

				$res_msg = $this->send_sms($hos_id,$send_content,$pat_phone);

				// 添加用户备注信息

				if(!empty($mark_content)){

					$remark = array('order_id' => $order_id,



								'admin_id' => $pat_id,



								'admin_name' => '用户留言',



								'mark_content' => $mark_content,



								'mark_time' => time(),



								'mark_type' => 6);



					$this->db->insert($this->common->table("order_remark"), $remark);

				}

				if($res_msg>0){

					if(empty($openid)){

						redirect("c=show&m=get_order_id&order_id=$order_id");

					}

					redirect("c=show&m=get_order_weixin&openid=$openid");

				}

			}



	}



	private function send_sms($hos_id,$send_content,$phone)

	{

		$hospital = $this->common->static_cache('read', "hospital/" . $hos_id, 'hospital', array('hos_id' => $hos_id));

		$msg = mb_convert_encoding($send_content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));

		require_once('application/libraries/sms/nusoap.php');

		$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);

		$client->soap_defencoding = 'utf-8';

		$client->decode_utf8      = false;

		$client->xml_encoding     = 'utf-8';



			$params = array(

				'account' => $hospital['sms_name'],

				'password' => $hospital['sms_pwd'],

				'destmobile' => $phone,

				'msgText' => $msg,

			);



		$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');

		$send_status = $result['sendBatchMessageReturn'];





		return $send_status;

	}



	public function get_order_no()

	{

		$url = base_url().'?c=order&m=order_no_ajax';

		$res = $this->http_get($url);

		return $res;

	}



	public function http_get($url){

		$oCurl = curl_init();

		if(stripos($url,"https://")!==FALSE){

			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);

		}

		curl_setopt($oCurl, CURLOPT_URL, $url);

		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );

		$sContent = curl_exec($oCurl);

		$aStatus = curl_getinfo($oCurl);

		curl_close($oCurl);

		if(intval($aStatus["http_code"])==200){

			return $sContent;

		}else{

			return false;

		}

	}







	private function http_post($url,$param){

		$oCurl = curl_init();

		if(stripos($url,"https://")!==FALSE){

			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);

		}

		if (is_string($param)) {

			$strPOST = $param;

		} else {

			$aPOST = array();

			foreach($param as $key=>$val){

				$aPOST[] = $key."=".urlencode($val);

			}

			$strPOST =  join("&", $aPOST);

		}

		curl_setopt($oCurl, CURLOPT_URL, $url);

		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );

		curl_setopt($oCurl, CURLOPT_POST,true);

		curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);

		$sContent = curl_exec($oCurl);

		$aStatus = curl_getinfo($oCurl);

		curl_close($oCurl);

		if(intval($aStatus["http_code"])==200){

			return $sContent;

		}else{

			return false;

		}

	}

	static function json_encode($arr) {

		$parts = array ();

		$is_list = false;

		//Find out if the given array is a numerical array

		$keys = array_keys ( $arr );

		$max_length = count ( $arr ) - 1;

		if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1

			$is_list = true;

			for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position

				if ($i != $keys [$i]) { //A key fails at position check.

					$is_list = false; //It is an associative array.

					break;

				}

			}

		}

		foreach ( $arr as $key => $value ) {

			if (is_array ( $value )) { //Custom handling for arrays

				if ($is_list)

					$parts [] = self::json_encode ( $value ); /* :RECURSION: */

				else

					$parts [] = '"' . $key . '":' . self::json_encode ( $value ); /* :RECURSION: */

			} else {

				$str = '';

				if (! $is_list)

					$str = '"' . $key . '":';

				//Custom handling for multiple data types

				if (is_numeric ( $value ) && $value<2000000000)

					$str .= $value; //Numbers

				elseif ($value === false)

				$str .= 'false'; //The booleans

				elseif ($value === true)

				$str .= 'true';

				else

					$str .= '"' . addslashes ( $value ) . '"'; //All other things

				// :TODO: Is there any more datatype we should be in the lookout for? (Object?)

				$parts [] = $str;

			}

		}

		$json = implode ( ',', $parts );

		if ($is_list)

			return '[' . $json . ']'; //Return numerical JSON

		return '{' . $json . '}'; //Return associative JSON

	}



	private function phone_check($phone)

	{

		if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$/",$phone)){

			return true;

		}

		return false;

	}

	public function doc_long()

	{

		$doc_id = $this->input->get('doc_id',true);

		$doc_content = $this->common->getRow('select * from '.$this->common->table('docter').' where doc_id = '.$doc_id);

		$this->_data['doc_content'] = $doc_content;

		$this->load->view('web/doc_content',$this->_data);

	}

	public function get_all_docter()

	{

		$hos_id = $this->hos_id;

		//要显示的页面

		$p = $this->input->get('p',true) ? $this->input->get('p',true) : 0;



		//每页显示数

		$num = 4;



		if($p <1){

			$p = 1;

		}

		// 总条数



		$sql_count = 'select count(1) from '.$this->common->table('docter').' where hos_id = '.$hos_id;

		$count = $this->common->getOne($sql_count);

		$page = ceil($count/4);

		if($p>$page&&$page!=0){

			$p = $page;

		}

		$limit = ($p-1)*4;

		$rows = 4;

	    $this->_data['p']=$p;

		$sql = 'select d.doc_name, d.doc_id , d.doc_zc,d.doc_img, d.keshi_id, k.keshi_name from '.$this->common->table('docter'). ' d left join '.$this->common->table('keshi').' k on k.keshi_id = d.keshi_id where d.hos_id = '.$hos_id.' ORDER BY d.`is_rec` DESC, d.`doc_order` DESC limit '.$limit.','.$rows;

		$res = $this->common->getAll($sql);

		$this->_data['docter'] = $res;

		$this->load->view('web/doc_list',$this->_data);

	}



	public function phone_to_tag($patient,$phone)

	{

		$phone = $patient['pat_phone'];

		if(empty($phone)){



			return $patient;

		}

		$area = $this->common->get_area_phone($phone);

		$area_data = read_static_cache('region');



		$province =  trim($area['province']);



		$city = trim($area['city']);



		if(in_array($province,$area_data)){

						$patient['pat_province'] = array_search($province,$area_data);

				}

		if(in_array($city,$area_data)){

						$patient['pat_city'] = array_search($city,$area_data);

				}



		return $patient;



	}





}