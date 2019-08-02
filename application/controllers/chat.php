<?php 

// 微信管理类
class Chat extends CI_Controller
{
	private $options = array();
	private $_input = array();
	private $wx_id;
	private $openid;
	public function __construct()
	{
		parent::__construct();
		$postStr = file_get_contents("php://input");
		if (!empty($postStr)) {
			$this->_input = (array)simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->_options($this->_input['ToUserName']);
		}
		
		$this->load->library('wechat',$this->options);
	}
	
	private function _options($user=''){
		if($user = 'gh_405f6d311507'){
			$this->options = array(
				'appid'=>'wx745a64041574e32b', //填写你设定的key
				'appsecret'=>'a92fd833fd805a180bc7251804aa3db8',
				'token'=>'shenzhenrenaiyiyuanshen2014'
				);
			$this->wx_id = 2;
		}
	
	}
	
	public function index()
	{
		$this->wechat->valid();
		$type = $this->wechat->getRev()->getRevType();
		switch($type) {
			case 'event':
					$event = $this->wechat->getRev()->getRevEvent();
					if($event['event'] == 'subscribe' || $event['event'] == 'SCAN'){
					
						
						$qrscene_id = $this->wechat->getRevSceneId();
						$qrscene_id = intval($qrscene_id);
						$group = $this->get_group();
						$openid = $this->wechat->getRevFrom();
						
						if($qrscene_id == 101){
						
							$this->update_group_members($group['预约'],$openid);
						}elseif($qrscene_id == 102){
						
							$this->update_group_members($group['到诊'],$openid);
						}else if($qrscene_id == 30){
							$this->update_group_members($group['预约'],$openid);
							$this->wechat->text('请输入您预约时提供的手机号码和姓名,用空格分开')->reply();
							exit();
						}else if($qrscene_id > 90000){
						
							//跟新数据
							$id = $qrscene_id - 90000;
							$openid = $this->wechat->getRevFrom();
							$this->db->update($this->common->table("weixin_login"), array('openid'=>$openid), array('id' => $id));
							$this->wechat->text('扫描成功，正在执行登录操作')->reply();
							exit();
						}else if(80000 < $qrscene_id && $qrscene_id< 90000){
							$id = $qrscene_id - 80000;
							$openid = $this->wechat->getRevFrom();
							$this->db->update($this->common->table("admin"), array('admin_openid'=>$openid), array('admin_id' => $id));
							$this->wechat->text('已经绑定微信号')->reply();
							exit();
						}
						
						if($event['event'] == 'subscribe'){
							//获取关注者的用户信息,添加到数据库
							$this->check_openid();
							$this->wechat->text('感谢您关注【深圳仁爱医院】官方微信，这是一个干净友好，和谐交流病情的平台，您有任何的问题都可以直接在线咨询。wifi密码:88308188x')->reply();
						}elseif($event['event'] == 'SCAN'){
							$this->wechat->text('欢迎再次光临【深圳仁爱医院】官方微信，我们为您提供优质的咨询服务。wifi密码:88308188x')->reply();
						}
					}elseif($event['event'] == 'LOCATION'){
						$position = $this->wechat->getRevEventGeo();
						$time = time();
						if($position){
							$pos_str = $position['x'].'|'.$position['y'];
							$openid = $this->wechat->getRevFrom();
							$this->db->update($this->common->table("wx_user"), array('pos'=>$pos_str,'come_time'=>$time), array('openid' => $openid));
						}else{
							$this->db->update($this->common->table("wx_user"),array('come_time'=>$time),array('openid' => $openid));
						}
					}elseif($event['event'] == 'CLICK'){
						$key = $event['key'];
						if($key == 1094404144){
							$user = $this->wechat->getRevFrom();
							//查询用户已经绑定的预约信息
							$sql = "select * from ".$this->common->table('order'). " o
									left join ".$this->common->table('patient'). " p on o.pat_id = p.pat_id
									where p.pat_weixin = '$user' order by order_addtime desc";
							$res = $this->common->getRow($sql);
							if($res){
								$yuyue = array(
									"touser"=>$user,
									"template_id"=>"XnVv9Mth86aeFUiME2TFQHJCBMRiHuOJuqrhLPJh03c",
									"url"=>"http://www.renaidata.com/?c=weixin&m=query_order&user=$user",
									"topcolor"=>"#FF0000",
									"data"=>array(
										'first'=>array('value'=>'您好，您的预约信息如下',"color"=>"#173177"),
										'keynote1'=>array('value'=>$res['order_id'],"color"=>"#173177"),
										'keynote2'=>array('value'=>$res['order_no'],"color"=>"#173177"),
										'keynote3'=>array('value'=>$res['pat_name'],"color"=>"#173177"),
										'keynote4'=>array('value'=>date('Y-m-d h:i:s',$res['order_time']),"color"=>"#173177"),
										'remark'=>array('value'=>'请及时就诊，过时作废，点击查询更多预约信息',"color"=>"#173177")
									
									)
								
								);
								$this->wechat->sendTplMessage($yuyue);
							
							}else{
							$this->wechat->text('登录我院手机快速查询通道，便可立即查询预约信息，【查询地址】<a href="http://www.renaidata.com/?c=weixin&m=query_order&user='.$user.'">点击这里，立即查询</a>')->reply();
							
							}
							exit();
						}
						$sql = 'select wb,state from '.$this->common->table('wx_wb').' where wx_key = '.$key;
						$wb = $this->common->getRow($sql);
						if($wb){
							if($wb['state']==2){
								$tid = $wb['wb'];
								$has_list = $this->common->getAll("select pid,title,digest,thumb,url from ".$this->common->table('wx_imgtxt')." where tid in ($tid)");
								$new = array();
								foreach($has_list as $key => $val){
									$new[$key]['Title'] = $val['title'];
									$new[$key]['Description'] = $val['digest'];
									$new[$key]['PicUrl'] = 'http://www.renaidata.com/static/upload/'.$val['thumb'];
									if($val['url']){
										$new[$key]['Url'] = $val['url'];
									}else{
										$new[$key]['Url'] = 'http://www.renaidata.com/?c=show&m=view&pid='.$val['pid'];
									}
								
								}
								$this->wechat->news($new)->reply();
							}else{
								$this->wechat->text($wb['wb'])->reply();
							}
							exit();
						
						}
					}elseif($event['event'] == 'unsubscribe'){
						$openid = $this->wechat->getRevFrom();	
						$this->db->update($this->common->table("wx_user"), array('subscribe'=>0), array('openid' => $openid));
					}
					exit;
					break;
			case 'image':
					$this->wechat->transfer_customer_service()->reply();
					exit;
					break;
			case 'text':
				$content = $this->wechat->getRev()->getRevContent();
				if($content == '首页'){
					$this->wechat->text('<a herf="http://www.renaidata.com/?c=show">仁爱首页</a>')->reply();
					return;
				}else if($content == '90120'){
					$openid = $this->wechat->getRevFrom();
					$new = array();
					$new[0]['Title'] = '接诊数据';
					$new[0]['Description'] = '每日病人预到实到数据';
					$new[0]['PicUrl'] = '';
					$new[0]['Url'] = 'http://www.renaidata.com/?c=show&m=order_data&op='.$openid;
					$this->wechat->news($new)->reply();
					exit;
				}else if(preg_match("/^13[0-9]{1}[0-9]{8}\s+[\x7f-\xff]+$|15[012356789]{1}[0-9]{8}\s+[\x7f-\xff]+$|18[012356789]{1}[0-9]{8}\s+[\x7f-\xff]+$/",$content)){
					// 查询预订信息
					$array = explode(' ',$content);
					$sql = "select * from ".$this->common->table('order'). " o
									left join ".$this->common->table('patient'). " p on o.pat_id = p.pat_id
									where p.pat_phone = $array[0] and pat_name = '$array[1]' order by order_addtime desc";
					$res = $this->common->getAll($sql);
					if($res){
						$user = $this->wechat->getRevFrom();
						$to_wx = $this->wechat->getRevTo();
						$update = array();
						foreach($res as $key=>$val){
							if($key==0){
								$yuyue = array(
									"touser"=>$user,
									"template_id"=>"XnVv9Mth86aeFUiME2TFQHJCBMRiHuOJuqrhLPJh03c",
									"url"=>"http://www.renaidata.com/?c=show&m=get_order_weixin&openid=$user&to_wx=$to_wx",
									"topcolor"=>"#FF0000",
									"data"=>array(
										'first'=>array('value'=>'您好，您的预约信息如下',"color"=>"#173177"),
										'keynote1'=>array('value'=>$val['order_id'],"color"=>"#173177"),
										'keynote2'=>array('value'=>$val['order_no'],"color"=>"#173177"),
										'keynote3'=>array('value'=>$val['pat_name'],"color"=>"#173177"),
										'keynote4'=>array('value'=>date('Y-m-d h:i:s',$val['order_time']),"color"=>"#173177"),
										'remark'=>array('value'=>'请及时就诊，过时作废，点击查询更多预约信息',"color"=>"#173177")
									)
								);
								if(empty($val['order_time'])){
									$yuyue['data']['keynote4']['value'] = '未定';
								}
								$this->wechat->sendTplMessage($yuyue);
							}
							
							$update[] = array('pat_id'=>$val['pat_id'],'pat_weixin'=>$user);
						}
						$this->db->update_batch($this->common->table('patient'),$update,'pat_id');
						exit();
					}else{
					
						$this->wechat->text('没有查询到您的预约信息,请确认输入是否正确')->reply();
					}
				}else if($content=='wifi密码'||$content=='wifi'){
					$this->wechat->text('仁爱医院wifi密码:88308188x,欢迎您的到来')->reply();
				}else{
					// 匹配自动回复
					$sql = 'select wb,type from '.$this->common->table('wx_con').' where wx_id = '.$this->wx_id.' and con_name = "'.$content.'"';
					$wb = $this->common->getRow($sql);
					if(empty($wb)){
						$this->wechat->transfer_customer_service()->reply();
					}else{
						if($wb['type']=='vclick'){
							$tid = $wb['wb'];
							$has_list = $this->common->getAll("select pid,title,digest,thumb from ".$this->common->table('wx_imgtxt')." where tid in ($tid)");
							$new = array();
							foreach($has_list as $key => $val){
								$new[$key]['Title'] = $val['title'];
								$new[$key]['Description'] = $val['digest'];
								$new[$key]['PicUrl'] = 'http://www.renaidata.com/static/upload/'.$val['thumb'];
								$new[$key]['Url'] = 'http://www.renaidata.com/?c=show&m=view&pid='.$val['pid'];
							
							}
							$this->wechat->news($new)->reply();
						}else{
							$this->wechat->text($wb['wb'])->reply();
						}
					}
				}
			default:
					$this->wechat->transfer_customer_service()->reply();
		}
	}
	
	// 检测用户信息
	private function check_openid()
	{
		$openid = $this->wechat->getRevFrom();
		
		$flag = $this->common->getRow('select wx_uid,gid from '.$this->common->table('wx_user').' where openid = "'.$openid.'"');
		if(empty($flag['wx_uid'])){
			$user_info = $this->wechat->getUserInfo($openid);
			$gid = $this->wechat->userFrom($openid);
			unset($user_info['remark']);
			unset($user_info['language']);
				
			$user_info['gid'] = $gid['groupid'];
			$user_info['wx_id'] = $this->wx_id;
			$this->db->insert($this->common->table('wx_user'), $user_info); 
			$wx_uid = $this->db->insert_id();
		}else{
			$user_info = $this->wechat->getUserInfo($openid);
			$gid = $this->wechat->userFrom($openid);
			
			$data = array(
				'subscribe'=>1,
				'gid' => $gid['groupid'],
				'subscribe_time' => $user_info['subscribe_time'],
				'city' => $user_info['city'],
				'province' => $user_info['province'],
				'country' => $user_info['country'],
				
			);
			$position = $this->wechat->getRevEventGeo();
			if($position){
				$pos_str = $position['x'].'|'.$position['y'];
				$data['pos'] = $pos_str;
			}
			$this->db->update($this->common->table("wx_user"), $data, array('openid' => $openid));
		}
	
	}
	
	//  获取分组列表
	
		public function get_group()
	{
	
		$res = $this->wechat->getGroup();
		$group_tag = array();
		foreach($res['groups'] as $val)
		{
		
			$group_tag[$val['name']] = $val['id'];
						
		}
		
		return $group_tag;
	
	}
	
	public function check_utf8($str)  
	{  
		$len = strlen($str);  
		for($i = 0; $i < $len; $i++){  
			$c = ord($str[$i]);  
			if ($c > 128) {  
				if (($c > 247)) return false;  
				elseif ($c > 239) $bytes = 4;  
				elseif ($c > 223) $bytes = 3;  
				elseif ($c > 191) $bytes = 2;  
				else return false;  
				if (($i + $bytes) > $len) return false;  
				while ($bytes > 1) {  
					$i++;  
					$b = ord($str[$i]);  
					if ($b < 128 || $b > 191) return false;  
					$bytes--;  
				}  
			}  
		}  
		return true;  
	}
	public function test()
	{
		$str = "13316592608 任博";
		if (preg_match("/^13[0-9]{1}[0-9]{8}\s+[\x7f-\xff]+$|15[012356789]{1}[0-9]{8}\s+[\x7f-\xff]+$|18[012356789]{1}[0-9]{8}\s+[\x7f-\xff]+$/",$str)) {
			print("该字符串全部是中文");
		} else {
			print("该字符串不全部是中文");
		}
	}
	
	// 移动分组 updateGroupMembers
	
	public function update_group_members($groupid,$openid)
	{
		$res = $this->wechat->updateGroupMembers($groupid,$openid);
		
		return res;
	
	}
	
	private function create_menu($newmenu){
	
		$res = $this->wechat->createMenu($newmenu);
		
		return $res;
	}
	//获取微信的菜单信息
	private function get_menu(){
	
		$menu = $this->wechat->getMenu();
		
		return $menu;
	}
	// 栏目表，name type key url info fid;通过type值来确认选择url或者info，仅支持url和文字信息输出
	public function menu_list(){
		$data = array();
		$data = $this->common->config('menu_list');
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('jibing', $data);
		
	}
	public function zampo()
	{			
				$content = 'renbo';
				$sql = 'select wb,type from '.$this->common->table('wx_con').' where wx_id = '.$this->wx_id.' and con_name = "'.$content.'"';
				$wb = $this->common->getRow($sql);
				if(empty($wb)){
					$this->wechat->transfer_customer_service()->reply();
				}else{
					if($wb['type']=='vclick'){
						$tid = $wb['wb'];
						$has_list = $this->common->getAll("select pid,title,digest,thumb from ".$this->common->table('wx_imgtxt')." where tid in ($tid)");
						$new = array();
						foreach($has_list as $key => $val){
							$new[$key]['Title'] = $val['title'];
							$new[$key]['Description'] = $val['digest'];
							$new[$key]['PicUrl'] = 'http://www.renaidata.com/static/upload/'.$val['thumb'];
							$new[$key]['Url'] = 'http://www.renaidata.com/?c=show&m=view&pid='.$val['pid'];
						
						}
						$this->wechat->news($new)->reply();
					}else{
						$this->wechat->text($wb['wb'])->reply();
					}
				}
					
		echo '<pre>';
			print_r($new);
		echo '</pre>';
	}
}
?>