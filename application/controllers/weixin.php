<?php 
//多微信管理类  
// 微信帐号管理，栏目管理
class Weixin extends CI_Controller
{
	private $options = array();
	private $hospital = array();
	private $weixin_list = array();
	private $default_wx = array();
	private $hos = array();
	private $user_data = array();
	private $ins_user = array();
	private $hos_str;
	private $w_wx_id;
	public function __construct()
	{
		parent::__construct();
		$this->set_defalt();
		$this->_options();
		$this->load->library('wechat',$this->options);
		
	}
	 
	private function _options()
	{
		$this->get_weixin();
		$this->options = array(
						'appid'=>$this->default_wx['appid'], //填写你设定的key
						'appsecret'=>$this->default_wx['appsecret'],
						'token'=>$this->default_wx['token'],
						);
	
	}

	public function pos_info()
	{
		$pos = trim($_REQUEST['pos']);
		if(!empty($pos)){
			$arr = explode('|',$pos);
			$url = 'http://lbs.juhe.cn/api/getaddressbylngb?lngx='.$arr[1].'&lngy='.$arr[0];
			$res = $this->https_post($url);
			print_r($res);
		}
	}
	
	public function test()
	{
	
		
		$pid = empty($_REQUEST['pid']) ? '' : intval($_REQUEST['pid']);
		$tid = empty($_REQUEST['tid']) ? '' : intval($_REQUEST['tid']);
		$action = empty($_REQUEST['action']) ? '' : trim($_REQUEST['action']);
		$this->load->helper('url');
		$data = array();
		if($pid){
			// 填写图文信息
			$this->load->model('posts_model');
			// 文章信息
			$post_db = $this->posts_model->get_post_by_id('pid', $pid);
			
			if(empty($post_db)){
			
				$this->common->msg('文章参数出错');
			}
			
			$data = $this->common->config('manage');
			$data['pid'] = $pid;
			$data['action'] = 'insert';
			
			$data['title'] = $post_db['title'];
			$data['thumb'] = $post_db['thumb'];
			$data['digest'] = $post_db['description'];
			$data['content'] = $post_db['text'];
			$data['hos_id'] = $post_db['hos_id'];
			$data['img_url'] = empty($post_db['thumb']) ? '' : base_url().'static/upload/'.$post_db['thumb'];
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('content/articles_info',$data);
			
		}else if($tid){
			//编辑图文信息
			$sql = "select * from ".$this->common->table('wx_imgtxt')." where tid = $tid";
			$res = $this->common->getRow($sql);
			if(empty($res)){
				$this->common->msg('编辑内容不存在');
			}
			$data = $this->common->config('manage');
			$data['action'] 	= 'update';
			$data['title'] 		= $res['title'];
			$data['thumb'] 		= $res['thumb'];
			$data['author'] 	= $res['author'];
			$data['content'] 	= $res['content'];
			$data['digest'] 	= $res['digest'];
			$data['pid'] 		= $res['pid'];
			$data['tid'] 		= $res['tid'];
			$data['img_url'] 	= empty($res['thumb']) ? '' : base_url().'static/upload/'.$res['thumb'];
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('content/articles_info',$data);
			
		}else if('insert' == $action){
			// 添加图文信息
			$content = $this->get_insert_imgtxt();
			//图文数据缓存表
			$insert_imgtxt = array(
				'thumb' => $content['thumb'],
				'title' => $content['title'],
				'author' => $content['author'],
				'pid' => $content['pid'],
				'content' => $content['content'],
				'digest' => $content['digest'],
				'hos_id' => $content['hos_id'],
			
			);
			$this->db->insert($this->common->table("wx_imgtxt"), $insert_imgtxt);
			$tid = $this->db->insert_id();
			$links[0] = array('href' => '?c=weixin&m=test&tid=' . $tid, 'text' => $this->lang->line('edit_back'));
			$this->common->msg($this->lang->line('success'), 0, $links);
		}else if('update' == $action){
			$tid = empty($_REQUEST['txt_id']) ? '' : intval($_REQUEST['txt_id']);
			$content = $this->get_insert_imgtxt();
			$update_imgtxt = array(
				'thumb' => $content['thumb'],
				'title' => $content['title'],
				'author' => $content['author'],
				'pid' => $content['pid'],
				'content' => $content['content'],
				'digest' => $content['digest'],
			);
			
			$this->db->update($this->common->table("wx_imgtxt"), $update_imgtxt, "tid = ".$tid);
			$links[0] = array('href' => '?c=weixin&m=test&tid=' . $tid, 'text' => $this->lang->line('edit_back'));
			$this->common->msg($this->lang->line('success'), 0, $links);
		}else{
		
			 $this->common->msg('系统参数异常');
		}
	}
	
	public function tw_list()
	{
		$data = array();
		$data = $this->common->config('tw_list');
		$str = $this->hos_str;
		if(array_key_exists('wx_tid',$_COOKIE)){
			$has = $_COOKIE['wx_tid'];
		}
		if(!empty($has)){
			$sql = 'select tid,title from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.') and tid in ('.$has.')';
			$select = $this->common->getAll($sql);
			$data['select'] = $select;
		}
		$sql = 'select * from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.')';
		$list = $this->common->getAll($sql);
		$data['tw_list'] = $list;
		$data['weixin_list'] = $this->weixin_list;
		$data['w_wx_id'] = $this->w_wx_id;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/tw_list', $data);
		
		
	}
	
	public function tw_list_ajax()
	{
		$page = $this->input->post('page',true);
		$con_keyword = $this->input->post('con_keyword',true);
		$str = $this->hos_str;
		// 统计总数
		if(empty($con_keyword)){
			$list_count = $this->common->getOne('select count(1) from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.')');
		}else{
			$list_count = $this->common->getOne('select count(1) from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.') and `title` LIKE "%'.$con_keyword.'%"');
		}
		$num = 4;
		$flag = ceil($list_count/$num);
		if($page >= $flag){
			$limit = 0;
		}else{
			$limit = $page*$num;
		}
		if(empty($con_keyword)){
			$list = $this->common->getAll('select * from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.') limit '.$limit.','.$num);
		}else{
			$list = $this->common->getAll('select * from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.') and `title` LIKE "%'.$con_keyword.'%" limit '.$limit.','.$num);
		}
		$str = '';
		foreach($list as $val){
			$str.= '<tr>';
			$str.= '<td><a href="?c=weixin&m=test&tid='.$val['tid'].'">'.$val['title'].'</a></td>';
			$str.= '<td><a href="javascript:void();" onclick="putin(\''.$val['title'].'\','.$val['tid'].')">选取</a></td>';
			$str.= '<tr>';
		}
		echo $str;
	}
	
	public function ajax_select()
	{
		$tid = intval($_REQUEST['tid']);
		if(array_key_exists('wx_tid',$_COOKIE)){
			$has = $_COOKIE['wx_tid'];
		}
		if($has){
			if(strstr($has,$tid)){
				echo 1;
				exit();
			}
		}
		// 上传图文附件
		$res = $this->common->getRow('select thumb,thumb_id from '.$this->common->table('wx_imgtxt').' where tid = '.$tid);
		if($res['thumb']){
			if(empty($res['thumb_id'])){
				$thumb_id = $this->create_img($res['thumb']);
				$time = time()+3*86400;
				$thumb_id = $thumb_id.'|'.$time;
				
				$this->db->update($this->common->table('wx_imgtxt'), array('thumb_id'=>$thumb_id), array('tid' => $tid));
			}else{
				$arr = explode('|',$res['thumb_id']);
				$md5 = substr(md5($res['thumb']),4,8);
				if(time() > $arr[1]||$md5 != $arr[2]){
					$thumb_id = $this->create_img($res['thumb']);
					$time = time()+3*86400;
					$thumb_id = $thumb_id.'|'.$time.'|'.$md5;
					$this->db->update($this->common->table('wx_imgtxt'), array('thumb_id'=>$thumb_id), array('tid' => $tid));
				}
			}
		}
		if(empty($has)){
		
			$wx_id = $tid;
		}else{
			$wx_id = $has.','.$tid;
		}
		setcookie('wx_tid', $wx_id, time()+24*60*60, "/");
	}
	
	public function ajax_select_del()
	{
		$tid = intval($_REQUEST['tid']);
		if(array_key_exists('wx_tid',$_COOKIE)){
			$has = $_COOKIE['wx_tid'];
			$arr = explode(',',$has);
			$key = array_search($tid,$arr);
			unset($arr[$key]);
			$str = implode(",", $arr);
			setcookie('wx_tid', $str, time()+24*60*60, "/");
		}
	
	}
	
	public function send_imgtxt()
	{
		
		if(array_key_exists('wx_tid',$_COOKIE)){
			$tid = $_COOKIE['wx_tid'];
		}
		
		$sql = "select * from ".$this->common->table('wx_imgtxt')." where tid in ($tid)";
		
		
		$res = $this->common->getAll($sql);
		
		$media_id = $this->create_imgtxt($res);
		$data = array( "touser"=>array('odB7jjizxk3VX-3y3aQ6rYv0aKA4'), "mpnews"=>array( "media_id"=>$media_id ), "msgtype"=>"mpnews" );
	
		$res = $this->wechat->sendMassMessage($data);
		
		if($res['errcode']===0){
		
			$this->common->msg('已发送');
		}
	}
	public function send_mass_text($content)
	{

		$data = array("touser"=>array('odB7jjizxk3VX-3y3aQ6rYv0aKA4'),'msgtype'=>'text','text'=>array('content'=>$content));
		$res = $this->wechat->sendMassMessage($data);
		
		if($res['errcode']===0){
		
			return true;
		}
		return false;
	}
	
	private function create_imgtxt($data)
	{
		
		$articles = array();
		foreach($data as $key=>$val){
				$arr = explode('|',$val['thumb_id']);
				$articles[$key]['thumb_media_id'] = $arr[0];
				$articles[$key]['author'] = $val['author'];
				$articles[$key]['title'] = $val['title'];
				if($val['url']){
					$articles[$key]['content_source_url'] = $val['url'];
				}else{
					$articles[$key]['content_source_url'] = 'http://www.renaidata.com/?c=show&m=view&pid='.$val['pid'].'&to_wx='.$this->default_wx['wxid'];
				}
				$articles[$key]['content'] = $val['content'];
				$articles[$key]['digest'] = $val['digest'];
				if($key == 0){
				$articles[$key]['show_cover_pic'] = 1;
				}else{
				$articles[$key]['show_cover_pic'] = 0;
				}
			
		}
		$res = array('articles'=>$articles);
		$res = $this->wechat->uploadArticles($res);
		
		return $res['media_id'];
	}
	
	private function get_insert_imgtxt()
	{
		return $content = array(
			'title' => $_REQUEST['img_title'],
			'author' => $_REQUEST['author'],
			'content' => $_REQUEST['content'],
			'thumb' => $_REQUEST['thumb'],
			'digest' => $_REQUEST['digest'],
			'pid' => $_REQUEST['link_id'],
			'hos_id' => $_REQUEST['hos_id'],
		
		);
	}
	
	private function create_img($thumb){
		$type = "image";
		$filepath = '/home/ftp/1520/data_ra120_com-20140226-Ifs/data.ra120.com/static/upload/'.$thumb;
		$data = array("media" => "@".$filepath);
		
		
		$access_token = $this->get_access_token();
		

		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type"; 
		$result = $this->https_post($url, $data); 
		$json = json_decode($result,true);
		return $json['media_id'];
	}
	
	public function upload()
	{
		
			$this->load->library('upload');
			$config['upload_path'] = 'static/upload';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '2000';
			$zui = explode(".", $_FILES['file']['name']);
			$config['file_name'] = md5(uniqid(mt_rand())).'.'.$zui[1];

			$this->upload->initialize($config);
		
			if(!$this->upload->do_upload($filed = 'file'))
			{
				echo $this->upload->display_errors();
			}
			else
			{
				$upload_data = $this->upload->data();
				$this->load->helper('url');
				$base_url = base_url();
				$data['name']=$config['file_name'];
				$data['url']=$base_url.'static/upload/'.$config['file_name'];
				echo json_encode($data);
			}
			
	}
	
	private function _get_type($file)
	{
		$ext = '';
        
        $part = explode('.', $file);
        
        if (($length = count($part)) > 1) 
        {
            $ext = strtolower($part[$length - 1]);
        }
        
        return $ext;
	}
	
	
	private function get_access_token()
	{
		$access_token = $this->wechat->checkAuth($this->options['appid'],$this->options['appsecret']);
		return $access_token;
	}
	
	private function https_post($url, $data = null) { 
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
		if (!empty($data)){ curl_setopt($curl, CURLOPT_POST, 1); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); } 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($curl); curl_close($curl); return $output; 
	}
    
	private function https_get($url)
	{
		$oCurl = curl_init();
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
	public function weixin_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$hos_id          = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$wx_id          = isset($_REQUEST['wx_id'])? intval($_REQUEST['wx_id']):0;
		$wxname       	 = trim($_REQUEST['wxname']);
		$wxid	         = trim($_REQUEST['wxid']);
		$weixin	         = trim($_REQUEST['weixin']);
		$appid         	 = trim($_REQUEST['appid']);
		$appsecret     	 = trim($_REQUEST['appsecret']);
		$token           = trim($_REQUEST['token']);
		
		
		if(empty($hos_id)||empty($wxname)||empty($wxid)||empty($weixin)||empty($appid)||empty($appsecret)||empty($token))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$arr = array('hos_id' => $hos_id,
		             'wxname' => $wxname,
					 'wxid' => $wxid,
					 'weixin' => $weixin,
					 'appid' => $appid,
					 'appsecret' => $appsecret,
					 'token' => $token);
		if($form_action == 'update')
		{
			if(empty($wx_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('weixin_edit');
			
			$this->db->update($this->common->table("weixin"), $arr, array('wx_id' => $wx_id));
		}
		else
		{
			$this->common->config('weixin_add');
			
			$this->db->insert($this->common->table("weixin"), $arr);
			$wx_id = $this->db->insert_id();
		}
		$this->common->static_cache('delete', "weixin/" . $wx_id);
		$links[0] = array('href' => '?c=system&m=weixin_info&wx_id=' . $wx_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=system&m=weixin_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=weixin&m=weixin_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);

	}
	//微信号查询接口
	public function weixin_list()
	{
		$data = array();
		$data = $this->common->config('weixin_list');
		
		$data['weixin_list'] = $this->weixin_list;
		$data['hos'] = $this->hos;
		$data['default_wx'] = $this->default_wx;
		$data['w_wx_id'] = $this->w_wx_id;
		
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/weixin_list', $data);
	}
	
	private function set_defalt()
	{
		$hospital = $this->common->get_hosname();
		$str = '';
		$hos = array();
		foreach($hospital as $val){
		
			$str .= $val['hos_id'] . ',';
			$hos[$val['hos_id']] = $val['hos_name'];
		}
		$str = substr($str, 0, -1);
		
		$sql = 'select * from '.$this->common->table('weixin').' where hos_id in ('.$str.')';
		
		$weixin_list = $this->common->getAll($sql);
	
		$this->weixin_list = $weixin_list;
		
		$this->hospital = $hospital;
		
		$this->hos = $hos;
		
		$this->hos_str = $str;
		
		
	
	}
	
	
	
	//微信号删除操作
	public function weixin_delete()
	{
		$wx_id          = isset($_REQUEST['wx_id'])? intval($_REQUEST['wx_id']):0;
		if(empty($wx_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
	
		$this->common->config('weixin_delete');
		
		$this->db->delete($this->common->table('weixin'), array('wx_id' => $wx_id));
		$links[0] = array('href' => '?c=weixin&m=weixin_list', 'text' => $this->lang->line('list_back'));
		$data = $this->common->msg($this->lang->line('success'), 0, $links, false);
		$this->load->view('msg', $data);
	}
	public function user_delete_ajax()
	{
		$wx_uid          = isset($_REQUEST['wx_uid'])? intval($_REQUEST['wx_uid']):0;
		if(empty($wx_uid))
		{
			echo 1;
		}
		$this->common->config('user_delete');
		$res = $this->db->delete($this->common->table('wx_user'), array('wx_uid' => $wx_uid));
		if($res){
			echo 2;
		}
	}
	//微信栏目管理，必须设置操作的微信号才能进行操作
	public function weixin_menu()
	{
		$menu = $this->wechat->getMenu();
	}
	
	//栏目添加模版
	public function menu_info()
	{
		$data = array();
		$menu_id = empty($_REQUEST['menu_id'])? 0:intval($_REQUEST['menu_id']);
		
		if(empty($menu_id))
		{
			$data = $this->common->config('menu_add');
		}
		else
		{
			$data = $this->common->config('menu_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('wx_menu') ." WHERE menu_id = $menu_id");
			$data['info'] = $info;
			
			if($info['type'] !== 'view'){
				$key = intval($info['key']);
				$wb = $this->common->getRow("select * from ".$this->common->table('wx_wb')." where wx_key = $key");
				$data['wb'] = $wb;
				if($info['type'] == 'vclick'){
					$tid = $wb['wb'];
					$has_list = $this->common->getAll("select tid,title from ".$this->common->table('wx_imgtxt')." where tid in ($tid)");
					$data['has_list'] = $has_list;
				}
			}else{
				$data['wb'] = '';
			}
		}
		$str = $this->hos_str;
		// 统计总数
		$list_count = $this->common->getOne('select count(1) from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.')');
		//查看顶级微信栏目
		$wx_id = $_COOKIE['w_wx_id'];
		$data['list_count'] = ceil($list_count/2);
		$data['default_wx'] = $this->default_wx;
		$weixin_menu = $this->common->getAll("SELECT * FROM " . $this->common->table('wx_menu') ." WHERE wx_id = $wx_id and botton = 0");
		$data['weixin_list'] = $this->weixin_list;
		$data['w_wx_id'] = $this->w_wx_id;
		$data['weixin_menu'] = $weixin_menu;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/menu_info', $data);
	}
	
	// 自定义关键次回复
	public function con_info()
	{
		$data = array();
		$con_id = empty($_REQUEST['con_id'])? 0:intval($_REQUEST['con_id']);
		if(empty($con_id))
		{
			$data = $this->common->config('con_add');
		}
		else
		{
			$data = $this->common->config('con_edit');
			$info = $this->common->getRow("SELECT * FROM " . $this->common->table('wx_con') ." WHERE con_id = $con_id");
			$data['info'] = $info;
			// vclick 图文  click 文本
			if($info['type'] == 'vclick'){
				$tid = $info['wb'];
				$has_list = $this->common->getAll("select tid,title from ".$this->common->table('wx_imgtxt')." where tid in ($tid)");
				$data['has_list'] = $has_list;
			}
			
		}
		$str = $this->hos_str;
		// 统计总数
		$list_count = $this->common->getOne('select count(1) from '.$this->common->table('wx_imgtxt').' where hos_id in ('.$str.')');
		$data['list_count'] = ceil($list_count/2);
		$data['weixin_list'] = $this->weixin_list;
		$data['w_wx_id'] = $this->w_wx_id;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/con_info', $data);
	}
	// 关键词  状态    自动回复信息
	public function con_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$con_id          = isset($_REQUEST['con_id'])? intval($_REQUEST['con_id']):0;
		$con_name       	 = trim($_REQUEST['con_name']);
		$type	         = isset($_REQUEST['type'])? trim($_REQUEST['type']) : 'click';
		if($type=='click'){
			$wb = trim($_REQUEST['wb']);
			if(empty($wb)){
				
					$this->common->msg('文本'.$this->lang->line('no_empty'), 1);
			}
		}
		if($type=='vclick'){
			$tid = $this->input->post('tid',true);
			$wb = implode(',',$tid);
		}
		$arr_con = array(
		    'wb' => $wb,
			'con_name' => $con_name,
			'type' => $type,
			'wx_id'=> $this->default_wx['wx_id'],
		);
		
		if($form_action == 'update')
		{
			if(empty($con_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('con_edit');
			
			$this->db->update($this->common->table("wx_con"), $arr_con, array('con_id' => $con_id));
		}
		else
		{
			$this->common->config('con_add');
			
			$this->db->insert($this->common->table("wx_con"), $arr_con);
			$con_id = $this->db->insert_id();
		}
		 
		$links[0] = array('href' => '?c=weixin&m=con_info&con_id=' . $con_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=weixin&m=con_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=weixin&m=con_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	//修改和添加微信栏目   栏目表说明： button 菜单级别 (0 为一级) name , type, key,url, 关联微信号
	//关联表: 图文表。 [title,pic,url(图文信息的属性，文本信息为空)] content(详细描述),关联key
	//文本表  文本内容content，关联key,
	public function menu_update()
	{
		$form_action     = $_REQUEST['form_action'];
		$botton          = isset($_REQUEST['botton'])? intval($_REQUEST['botton']):0;
		$menu_id          = isset($_REQUEST['menu_id'])? intval($_REQUEST['menu_id']):0;
		$menu_name       	 = trim($_REQUEST['menu_name']);
		if($botton == 0){
			//检测栏目个数
			$type = null;
		}else{
			// 检测子栏目个数是否超出限制
			$type	         = !empty($_REQUEST['type'])? trim($_REQUEST['type']) : 'view';
			
		}
		if($type == 'view'){
			$url = trim($_REQUEST['url']);
			if(empty($url)){
			
				$this->common->msg('链接'.$this->lang->line('no_empty'), 1);
			}
			$wx_key = null;
		}elseif($type == 'click'){
			
			$url = '';
			$wb = trim($_REQUEST['wb']);
			if(empty($wb)){
			
				$this->common->msg('文本'.$this->lang->line('no_empty'), 1);
			}
			$wx_key = mt_rand();
			$arr_wb = array(
		             'wb' => $wb,
					 'wx_key' => $wx_key,
					  'state' => 1,
					 );
			$this->db->insert($this->common->table("wx_wb"), $arr_wb);
		}else if($type == 'vclick'){
			$url = '';
			$tid = $this->input->post('tid',true);
			if(empty($tid)){
				$this->common->msg('请选取图文', 1);
			}
			$wb = implode(',',$tid);
			$wx_key = mt_rand();
			$arr_wb = array(
		             'wb' => $wb,
					 'wx_key' => $wx_key,
					 'state' => 2,
					 );
			$this->db->insert($this->common->table("wx_wb"), $arr_wb);
		}else{
			$url = '';
			$wx_key = null;
		}
		$arr = array(
		             'name' => $menu_name,
					 'botton' => $botton,
					 'type' => $type,
					 'url' => $url,
					 'key' => $wx_key,
					 'wx_id' => $this->default_wx['wx_id']);
	
		if($form_action == 'update')
		{
			if(empty($menu_id))
			{
				$this->common->msg($this->lang->line('no_empty'), 1);
			}
			$this->common->config('menu_edit');
			
			$this->db->update($this->common->table("wx_menu"), $arr, array('menu_id' => $menu_id));
		}
		else
		{
			$this->common->config('menu_add');
			
			$this->db->insert($this->common->table("wx_menu"), $arr);
			$menu_id = $this->db->insert_id();
		}
		
		$links[0] = array('href' => '?c=weixin&m=menu_info&menu_id=' . $menu_id, 'text' => $this->lang->line('edit_back'));
		$links[1] = array('href' => '?c=weixin&m=menu_info', 'text' => $this->lang->line('add_back'));
		$links[2] = array('href' => '?c=weixin&m=menu_list', 'text' => $this->lang->line('list_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	
	}
	//删除微信栏目
	public function menu_delete()
	{
		$menu_id          = isset($_REQUEST['menu_id'])? intval($_REQUEST['menu_id']):0;
		if(empty($menu_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		$this->common->config('menu_delete');
		// 检测是否有子操作
		$check = $this->common->getRow('select * from '.$this->common->table('wx_menu').' where botton = '.$menu_id);
		if($check){
		
			$data = $this->common->msg('有子栏目，请先删除子栏目', 1, array(), false);
		}else{
			$has = $this->common->getRow('select * from '.$this->common->table('wx_menu').' where menu_id = '.$menu_id);
			if($has['type'] == 'click' || $has['type'] == 'vclick' ){
			
				$this->db->delete($this->common->table('wx_wb'), array('wx_key' => $has['key']));
			}
			$this->db->delete($this->common->table('wx_menu'), array('menu_id' => $menu_id));
			$links[0] = array('href' => '?c=weixin&m=menu_list', 'text' => $this->lang->line('list_back'));
			$data = $this->common->msg($this->lang->line('success'), 0, $links, false);
		}
		
		$this->load->view('msg', $data);
	}
	
	public function con_delete()
	{
		$this->common->config('con_delete');
		$con_id          = isset($_REQUEST['con_id'])? intval($_REQUEST['con_id']):0;
		if(empty($con_id))
		{
			$this->common->msg($this->lang->line('no_empty'), 1);
		}
		
		$this->db->delete($this->common->table('wx_con'), array('con_id' => $con_id));
		$links[0] = array('href' => '?c=weixin&m=con_list', 'text' => $this->lang->line('list_back'));
		$data = $this->common->msg($this->lang->line('success'), 0, $links, false);
		$this->load->view('msg', $data);
	}
	// 组装栏目信息并生成栏目
	public function check_weixin()
	{
		if(empty($this->weixin_list)){
		
			return false;
		}	
		return true;
	}
	public function get_weixin()
	{
		//检测是否添加微信号
		if(!$this->check_weixin()){
			$links[0] = array('href' => '?c=system&m=weixin_info', 'text' => '添加微信号');
			$this->common->msg('请先添加公众微信号', 0, $links, true, false);
		}
		if(isset($_COOKIE['w_wx_id'])){
			if(!empty($_COOKIE['w_wx_id'])){
				$this->w_wx_id = $_COOKIE['w_wx_id'];
				foreach($this->weixin_list as $row){
					if($row['wx_id']==$_COOKIE['w_wx_id']){
						
						$this->default_wx = $row;
						return;
					}
				}	
			}
		}
		
		
		//默认选取首个微信号并设置cookie信息
		$weixin = $this->weixin_list[0];
		if($weixin){
			$this->default_wx = $weixin;
			setcookie('w_wx_id', $this->weixin_list[0]['wx_id'], time()+24*60*60, "/");
			$this->w_wx_id = $this->weixin_list[0]['wx_id'];
		}else{
		
			$this-common-msg('请检查微信是否添加');
		}
	}
	
	//设置操作微信号，通过wx_id设置
	public function set_weixin()
	{
		$wx_id          = isset($_REQUEST['wx_id'])? intval($_REQUEST['wx_id']):0;
		
		foreach($this->weixin_list as $row){
			if($row['wx_id']==$wx_id){
				
				setcookie('w_wx_id', $row['wx_id'], time()+24*60*60, "/");
			}
		}
		$this->w_wx_id = $wx_id;
		$data['w_wx_id'] = $this->w_wx_id;
		$this->common->msg('微信号切换成功');
		
	}
	
	public function ajax_send()
	{
		$user_id = trim($_REQUEST['user_id']);
	
		$content = trim($_REQUEST['remark']);
		$data = array('touser'=>$user_id,'msgtype'=>'text','text'=>array('content'=>$content));
		$res = $this->wechat->sendCustomMessage($data);
		if($res){
		
			echo '发送成功';
		}else{
			echo '发送失败';
		}
		
	}
	public function ajax_send_all()
	{
		$content = trim($_REQUEST['remark']);
		$res = $this->send_mass_text($content);
		if($res){
			echo '发送成功';
		}else{
		
			echo '发送失败';
		}
	}
	
	// 组装 栏目信息
	private function menu_data_create()
	{
		//当前微信号
		$wx_id = $this->default_wx['wx_id'];
		$menu = $this->common->getAll('select * from '.$this->common->table('wx_menu').' where wx_id = '.$wx_id.' order by menu_id');
		$botton = array();
		foreach($menu as $val){
			if($val['botton'] == 0){
				$botton[$val['menu_id']]=array(
					'name'=>$val['name'],
					
				);
			
			}else{
				$sub_botton = array();
				$sub_botton = array('name'=>$val['name'],'type'=>$val['type']);
				
				
				if($val['type'] == 'view'){
				
					$sub_botton['url'] = $val['url'];
				}else{
					$sub_botton['key'] = $val['key'];
				}
				$botton[$val['botton']]['sub_button'][] = $sub_botton;
			}
		
		}
		$button = array();
		
		foreach($botton as $v){
		
			$button['button'][] = $v;
		}
		return $button;
	}
	
	// 查询客户的预约信息
	public function get_order_weixin()
	{
		$openid = $this->input->get('openid',true);
		
		$sql = "select o.*, p.*,k.keshi_name,u.admin_name as name from ".$this->common->table('order'). " o
				left join ".$this->common->table('patient'). " p on o.pat_id = p.pat_id
				left join ".$this->common->table('keshi'). " k on o.keshi_id = k.keshi_id
				left join ".$this->common->table('order_out'). " u on o.order_id = u.order_id
				where p.pat_weixin = '$openid'  order by order_addtime desc";
		$res = $this->common->getAll($sql);
		$data['order'] = $res;
		$this->load->view('web/user',$data);
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
						$group = $this->get_group();
						$openid = $this->wechat->getRevFrom();
						
						if($qrscene_id == '101'){
						
							$this->update_group_members($group['预约'],$openid);
						}elseif($qrscene_id == '102'){
						
							$this->update_group_members($group['到诊'],$openid);
						}
						if($event['event'] == 'subscribe'){
							$this->wechat->text('感谢您关注【深圳仁爱医院】官方微信，这是一个干净友好，和谐交流病情的平台，您有任何的问题都可以直接在线咨询。')->reply();
						}elseif($event['event'] == 'SCAN'){
							$this->wechat->text('欢迎再次光临【深圳仁爱医院】官方微信，我们为您提供优质的咨询服务。')->reply();
						}
					}
					exit;
					break;
			case 'image':
					$this->wechat->transfer_customer_service()->reply();
					exit;
					break;
			default:
					$this->wechat->transfer_customer_service()->reply();
		}
	
	}
	// 生成二维码【101 预约。102到诊】

	public function create_link()
	{
		$scene_id = 30;
		
		$type = 1;
		
		$expire = null;
		
		$res = $this->wechat->getQRCode($scene_id,$type,$expire);

		$ewm = $this->wechat->getQRUrl($res['ticket']);
		
		echo $ewm;
	
	}
	
	//  获取分组列表
	
	private function get_group()
	{
	
		$res = $this->wechat->getGroup();
		$group_tag = array();
		foreach($res['groups'] as $val)
		{
		
			$group_tag[$val['name']] = $val['id'];
						
		}
		
		return $group_tag;
	
	}
	
	// 移动分组 updateGroupMembers
	
	public function update_group_members($groupid,$openid)
	{
		$res = $this->wechat->updateGroupMembers($groupid,$openid);
		
		return res;
	
	}
	
	private function create_menu($newmenu){
	
		$res = $this->wechat->createMenu($newmenu);
		if(!$res){
			return $this->wechat->errCode;
		}
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
		// 获取当前微信的栏目信息
		$wx_id = $this->default_wx['wx_id'];
		$wx_menu = $this->common->getAll('select * from '.$this->common->table('wx_menu').' where wx_id = '.$wx_id. ' order by menu_id');
		$list = array();
		foreach($wx_menu as $val){
			if($val['botton'] == 0){
			
				$list[$val['menu_id']] = $val;
			}else{
				
				$list[$val['botton']]['son'][] = $val;
			}
		}
		$data['w_wx_id'] = $this->w_wx_id;
		$data['list'] = $list;
		$data['weixin_list'] = $this->weixin_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/menu_list', $data);
		
	}
	public function con_list()
	{
		$data = array();
		$data = $this->common->config('con_list');
		// 获取当前微信的栏目信息
		$wx_id = $this->default_wx['wx_id'];
		$wx_con = $this->common->getAll('select * from '.$this->common->table('wx_con').' where wx_id = '.$wx_id);
		
		$data['w_wx_id'] = $this->w_wx_id;
		$data['list'] = $wx_con;
		$data['weixin_list'] = $this->weixin_list;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/con_list', $data);
	}
	public function group_list()
	{
		$data = array();
		$data = $this->common->config('group_list');
		$group = $this->wechat->getGroup();
		$data['list'] = $group['groups'];
		$data['weixin_list'] = $this->weixin_list;
		$data['w_wx_id'] = $this->w_wx_id;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/group_list', $data);
	}
	
	public function create_group()
	{
		$name = trim($_REQUEST['name']);
		$res = $this->wechat->createGroup($name);
		if($res){
			echo '添加成功';
		}else{
			echo '添加失败';
		}
	}
	public function update_group()
	{
		$name = trim($_REQUEST['name']);
		$id = intval($_REQUEST['id']);
		$res = $this->wechat->updateGroup($id,$name);
		if($res){
			echo '修改成功';
		}else{
			echo '修改失败';
		}
	}
	public function user_list()
	{
		$data = array();
		$data = $this->common->config('user_list');
		
		$wx_id = $this->default_wx['wx_id'];
		
		$group_id = isset($_REQUEST['gid'])? intval($_REQUEST['gid']):0;
		
		$name = isset($_REQUEST['name'])? trim($_REQUEST['name']):'';
		
		$data['gid'] = $group_id;
		
		$where = '';
		
		
		$data['w_wx_id'] = $this->w_wx_id;
		
		if(!empty($name)){
		
			$where .= " and nickname like '%{$name}%'";
		}else{
			$where .= " and gid = $group_id";
		}
		
		$sql = 'select count(1) as count from '.$this->common->table('wx_user').' where wx_id = '.$wx_id.$where;
		
		$user_count = $this->common->getRow($sql);
	
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
		
		$data['now_page'] = $page;
		
		$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):10;

		$per_page = empty($per_page)? 10:$per_page;
		
		$this->load->library('pagination');

		$this->load->helper('page');
		
		$config = page_config();
		
		$config['total_rows'] = $user_count['count'];

		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;
		
		$config['base_url'] = '?c=weixin&m=user_list&p=' . $per_page . '&gid=' . $group_id;
		
		$this->pagination->initialize($config);
		
		$sql_list = "select * from " . $this->common->table('wx_user') . " where wx_id = $wx_id $where order by come_time desc LIMIT $page, $per_page";
		
		$user_info = $this->common->getAll($sql_list);
		
		$data['page'] = $this->pagination->create_links();
		$data['group'] = array_flip($this->get_group());
		$data['weixin_list'] = $this->weixin_list;
		$data['user_info'] = $user_info;
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
		$this->load->view('weixin/wx_user_list', $data);
	}
	
	public function user_info_ins(){
		$data = array();
		$data = $this->common->config('user_info_ins');
		
		$last = $this->get_ins_user();
		if(empty($last)){
			$links[0] = array('href' => '?c=weixin&m=user_list', 'text' => $this->lang->line('list_back'));
			$this->common->msg('已经是最新的用户数据', 0, $links, true, false);
			exit();
		}
		
		$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):1;
		$data['default_url'] = 'http://www.renaidata.com/?c=weixin&m=user_info_ins&per_page='.($page+1);
	
		$per_page = 15;
		
		$count = count($last);
		
		$page_count = ceil($count/$per_page);
		$data['page_log'] = (number_format($page/$page_count, 2, '.', ''))*100;
		if($page > $page_count){
			$links[0] = array('href' => '?c=weixin&m=user_list', 'text' => $this->lang->line('list_back'));
			$this->common->msg('更新成功', 0, $links, true, false);
			exit();
		}
		$user_info = array();
		if($page = $page_count){
			$key = ($page-1)*$per_page;
			$slice = array_slice($last,$key);
			foreach($slice as $val){
				$user = $this->wechat->getUserInfo($val);
				$gid = $this->wechat->userFrom($val);
				if(empty($user)||empty($gid)){
					$tach = array_flip($this->user_data['data']['openid']);
					$del = $tach[$val];
					unset($this->user_data['data']['openid'][$del]);
					write_static_cache('wx_user_data',$this->user_data);
					continue;
				}
				unset($user['remark']);
				unset($user['language']);
				$user['gid'] = $gid['groupid'];
				$user['wx_id'] = $this->default_wx['wx_id'];
				$user_info[] = $user;
			}
		}else{
			for($i=0,$j=$per_page;$i<$j;$i++){
				$key = ($page-1)*$per_page+$i;
				$openid = $last[$key];
				$user = $this->wechat->getUserInfo($openid);
				$gid = $this->wechat->userFrom($openid);
				if(empty($user)||empty($gid)){
					$tach = array_flip($this->user_data['data']['openid']);
					$del = $tach[$openid];
					unset($this->user_data['data']['openid'][$del]);
					write_static_cache('wx_user_data',$this->user_data);
					continue;
				}
				unset($user['remark']);
				unset($user['language']);
				
				$user['gid'] = $gid['groupid'];
				$user['wx_id'] = $this->default_wx['wx_id'];
				$user_info[] = $user;
			}
		}
		if(!empty($user_info)){
			$res = $this->db->insert_batch($this->common->table('wx_user'), $user_info);
		}else{
			$res = true;
		}
			$data['w_wx_id'] = $this->w_wx_id;
		if($res){
			$data['weixin_list'] = $this->weixin_list;
			$data['top'] = $this->load->view('top', $data, true);
			$data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
			$data['sider_menu'] = $this->load->view('sider_menu', $data, true);
			$this->load->view('weixin/user_ins', $data);
		}
	}
	
	public function get_ins_user(){
	
			// 每小时更新一次关注者列表
			$this->user_data = read_static_cache('wx_user_data');
			if(empty($this->user_data)){
				$this->user_data = $this->wechat->getUserList();
				$this->user_data['time_tag'] = time();
				write_static_cache('wx_user_data',$this->user_data);
			}else{
				$time_tag = $this->user_data['time_tag'];
				if(($time_tag+7200)<time()){
					$this->user_data = $this->wechat->getUserList();
					$this->user_data['time_tag'] = time();
					write_static_cache('wx_user_data',$this->user_data);
				}
			}
			//组装要插入的数据
			$all_opp = $this->user_data['data']['openid'];
			$sql = "select openid from ".$this->common->table('wx_user');
			$ins = $this->common->getAll($sql);
			$list_l = array();
			foreach($ins as $val){
			
				$list[] = $val['openid'];
			}
			$last_l = array_diff($all_opp,$list);
			$last = array();
			foreach($last_l as $val){
			
				$last[] = $val;
			}
			$this->ins_user = $last;
			
		
		return $this->ins_user;
	}
	
	
	//组装菜单信息并且更新到微信公众号上
	public function menu_data()
	{
		$newmenu = $this->menu_data_create();
		$res = $this->create_menu($newmenu);
		if($res){
			$this->common->msg('微信栏目更新成功');
		}else{
			$this->common->msg('栏目更新失败');
		}
	}
	
	
	public function query_order()
	{
		$user = trim($_REQUEST['user']);
		$cap = $this->captcha();
		$data = array();
		$data['user'] = $user;
		$data['title'] = '仁爱医院';
		$data['img'] = $cap['image'];
		$this->load->view('query_order',$data);
	}
	
	public  function captcha()
	{
		$ajax = isset($_REQUEST['ajax'])?intval($_REQUEST['ajax']):'';
		$this->load->helper('captcha');
		$pool = '0123456789';

			$str = '';
			for ($i = 0; $i < 4; $i++)
			{
				$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
			}

		$word = $str;
		$vals = array(
			'word'=>$word,
			'img_path' => 'static/captcha/',
			'img_url' => 'http://www.renaidata.com/static/captcha/',
			'img_width' => 60,
			'img_height' => 30,
		);

		$cap = create_captcha($vals);
		
		$data = array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		);
		
		// 删除旧的验证码
		$this->db->delete('captcha', array('ip_address' => $this->input->ip_address())); 

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);
		if($ajax){
		
			echo $cap['image'];
		}else{
			return $cap;
		}
	}
	
	public function get_order()
	{
		$phone = $_REQUEST['phone'];
		$yuyue = strtoupper($_REQUEST['yuyue']);
		$cap = $_REQUEST['cap'];
		$user = trim($_REQUEST['user']);
		$ip = $this->input->ip_address();
		$sql = 'select * from '.$this->common->table('captcha').' where word = '.$cap.' and ip_address = "'.$ip.'"';
		$res = $this->common->getRow($sql);
		if($res){
			$this->db->delete('captcha', array('cap_id' => $res['cap_id'])); 
			$sql = 'select  p.pat_name,o.order_addtime,o.order_time,o.is_come,o.come_time,h.hos_name,k.keshi_name,p.pat_id
					from '.$this->common->table('order').' o
					left join '.$this->common->table('patient').' p on o.pat_id = p.pat_id
					left join '.$this->common->table('hospital').' h on o.hos_id = h.hos_id
					left join '.$this->common->table('keshi').' k on o.keshi_id = k.keshi_id
					where p.pat_phone = '.$phone.' and o.order_no = "'.$yuyue.'"';
			$res = $this->common->getRow($sql);
			if($res){
				$this->db->update($this->common->table("patient"), array('pat_weixin'=>$user), array('pat_id' => $res['pat_id']));
				$res['order_time'] = date('Y年m月d日H时',$res['order_time']);
				$res['order_addtime'] = date('Y年m月d日H时',$res['order_addtime']);
				if($res['is_come']){
				
					$res['is_come'] = '已来院';
					$res['come_time'] = date('Y年m月d日H时i分',$res['come_time']);
				}else{
					$res['is_come'] = '未来院';
					$res['come_time'] = '';
				}
				
				print_r(json_encode($res));
			}else{
				echo 2;
			}
		}else{
			
			echo 1;
		}
	}
	
	
}
?>