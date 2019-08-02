<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Posts extends CI_Controller
{
	private $_data = array();
	private $_hos_id;
	private $_pid;
	private $_domain;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library(array('input','form_validation','session'));
		$this->load->model('Posts_model');
		$this->load->model('Metas_model');
	}
	//文章内容获取入口
	public function get_content_list()
	{
		$this->_data = $this->common->config('st_content_list');
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$do_id = isset($_REQUEST['do_id'])? intval($_REQUEST['do_id']):0;
		$st_id = isset($_REQUEST['st_id'])? intval($_REQUEST['st_id']):0;
		$cate = isset($_REQUEST['cate'])? intval($_REQUEST['cate']):0;
		$cate_1 = isset($_REQUEST['cate_1'])? intval($_REQUEST['cate_1']):0;
		$cate_2 = isset($_REQUEST['cate_2'])? intval($_REQUEST['cate_2']):0;
		$cate_3 = isset($_REQUEST['cate_3'])? intval($_REQUEST['cate_3']):0;
		$page = isset($_REQUEST['page'])? intval($_REQUEST['page']):0;
		$title = isset($_REQUEST['title'])? strip_tags(trim($_GET['title'])):'';
		
		if($cate_3&&$cate_2&&$cate_1&&$cate){
			$catid = $cate_3;
		}else if($cate_2&&$cate_1&&$cate){
			$catid = $cate_2;
		}else if($cate_1&&$cate){
			$catid = $cate_1;
		}else if($cate){
			$catid = $cate;
		}else{
			$catid = 0;
		}
		
			$domain = $this->get_domain($do_id);
			if(!empty($title)){
				$content_list = $this->get_list($domain,$st_id,0,$page,$title);
				$this->_data['search'] = $title;
			}else{
				$content_list = $this->get_list($domain,$st_id,$catid,$page);
			}
			$number = $content_list['number'];
                        $str='';
			if($number>20){
				// 总页数
				$pages = ceil($number / 20);
				// 当前页
				if($page<1){
					$page =1;
				}else if($page>$pages){
					$page = $pages;
				}
				$url='?c=posts&m=get_content_list&hos_id='.$hos_id.'&do_id='.$do_id.'&st_id='.$st_id.'&cate='.$cate.'&cate_1='.$cate_1.'&cate_2='.$cate_2.'&cate_3='.$cate_3.'&title='.$title;
				if($pages>1){
					$str = '<div class="custom-pagination pagination-centered">';
					$str .= '<ul>';
					if($page>1){
						$str .=	'<li><a href="'.$url.'&page=1">首页</a></li>';
						$left = $page -1;
						$str .=	'<li><a href="'.$url.'&page='.$left.'">上一页</a></li>';
					}
					$str .=	'<li>'.$page.'</li>';
					if($page<$pages){
						$left = $page +1;
						$str .=	'<li><a href="'.$url.'&page='.$left.'">下一页</a></li>';
						$str .=	'<li><a href="'.$url.'&page='.$pages.'">尾页</a></li>';
					}
					$str .= '</ul>';
				}
			}else{
				$str .= null;
			}
		$this->_data['hospital'] = $this->common->get_hosname();
		$this->_data['hos_id'] = $hos_id;
		$this->_data['do_id'] = $do_id;
		$this->_data['st_id'] = $st_id;
		$this->_data['catid'] = $catid;
		$this->_data['cate'] = $cate;
		$this->_data['cate_1'] = $cate_1;
		$this->_data['cate_2'] = $cate_2;
		$this->_data['cate_3'] = $cate_3;
		$this->_data['content_list'] = $content_list;
		$this->_data['pages'] = $str;
		$this->_data['top'] = $this->load->view('top', $this->_data, true);
		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		$this->load->view('content/content_list', $this->_data);
		
	}
	public function content_info()
	{
		$this->_data = $this->common->config('st_content_info');
		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;
		$do_id = isset($_REQUEST['do_id'])? intval($_REQUEST['do_id']):0;
		$st_id = isset($_REQUEST['st_id'])? intval($_REQUEST['st_id']):0;
		$catid = isset($_REQUEST['catid'])? intval($_REQUEST['catid']):0;
		$id = isset($_REQUEST['id'])? intval($_REQUEST['id']):0;
		if(empty($hos_id)||empty($do_id)||empty($st_id)||empty($catid)||empty($id)){
			$this->common->msg('传参异常');
		}
		$domain = $this->get_domain($do_id);
		$info = $this->get_content_info($domain,$st_id,$catid,$id);
		$this->_data['hos_id'] = $hos_id;
		$this->_data['title'] = $info->title;
		$this->_data['description'] = $info->description;
		$this->_data['content'] = $info->content;
		$this->_data['username'] = $info->username;
		$this->_data['url'] = $info->url;
		$this->_data['top'] = $this->load->view('top', $this->_data, true);
		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		$this->load->view('content/cms_info', $this->_data);
	}
	
	public function tw_insert()
	{
		$con = $this->get_insert_imgtxt();
		$title = trim($con['title']);
		if(empty($title)){
			$this->common->msg('标题不能为空');
		}
		$content = trim($con['content']);
		if(empty($content)){
			$this->common->msg('内容不能为空');
		}
		$author = trim($con['author']);
		$thumb = trim($con['thumb']);
		$digest = trim($con['digest']);
		$url = trim($con['url']);
		$hos_id = intval($con['hos_id']);
		$insert_imgtxt = array(
				'thumb' => $thumb,
				'title' => $title,
				'author' => $author,
				'content' => $content,
				'digest' => $digest,
				'hos_id' => $hos_id,
				'url' => $url
			);
		$this->db->insert($this->common->table("wx_imgtxt"), $insert_imgtxt);
		$tid = $this->db->insert_id();
		$links[0] = array('href' => '?c=weixin&m=test&tid=' . $tid, 'text' => $this->lang->line('edit_back'));
		$this->common->msg($this->lang->line('success'), 0, $links);
	}
	private function get_insert_imgtxt()
	{
		return $content = array(
			'title' => $_REQUEST['img_title'],
			'author' => $_REQUEST['author'],
			'content' => $_REQUEST['content'],
			'thumb' => $_REQUEST['thumb'],
			'digest' => $_REQUEST['digest'],
			'hos_id' => $_REQUEST['hos_id'],
			'url' => $_REQUEST['url'],
		
		);
	}
	//ajax获取当前医院下的网站列表
	public function get_domain_ajax()
	{

		$hos_id = isset($_REQUEST['hos_id'])? intval($_REQUEST['hos_id']):0;

		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;

		if(empty($hos_id))

		{

			exit();

		}

		$site_list = $this->common->static_cache('read','site/'.$hos_id,'site',array('hos_id'=>$hos_id));
		$str = '<option value="">请选择网站...</option>';
		if(empty($site_list))

		{
			echo $str;
			exit();

		}
		

		foreach($site_list as $val)

		{

			$str .= '<option value="' . $val['st_id'] . '"';

			if($val['st_id'] == $check_id)

			{

				$str .= " selected";

			}

			$str .= '>' . $val['sitename'] . '</option>';

		}
		echo $str;		
	}
	//ajax获取当前网站的站点列表
	public function get_site_ajax()
	{
		$do_id = isset($_REQUEST['do_id'])? intval($_REQUEST['do_id']):0;

		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;
		$str = '<option value="">请选择站点...</option>';
		if(empty($do_id))

		{
			echo $str;
			exit();

		}
		// 获取网站的域名
		$domain = $this->get_domain($do_id);
		if(empty($domain)){
			exit();
		}
		// 获取网站下面的站点
		$sites = $this->get_site($domain);
		
		if(empty($sites))
		{
			echo $str;
			exit();
		}
		foreach($sites as $val)
		{
			$str .= '<option value="' . $val->siteid . '"';

			if($val->siteid == $check_id)

			{

				$str .= " selected";

			}

			$str .= '>' . $val->name . '</option>';
		}
		echo $str;
	}
	private function get_domain($do_id)
	{
		if(empty($do_id)){$do_id = 2;};
		$domain = $this->common->getOne('select domain from '.$this->common->table('st').' where st_id = '.$do_id);
		return $domain;
	}
	private function get_site($domain)
	{
		$url = 'http://'.$domain.'/zampo.php';
		$code = sys_auth('action=site_list');
		$data = array("op" =>"content",'code'=>$code );
		$sites = https_post($url, $data);
		return json_decode($sites);
	}
	//domain 网址，st_id 站点id
	private function get_cate($domain,$st_id)
	{
		$url = 'http://'.$domain.'/zampo.php';
		$code = sys_auth('action=public_categorys&siteid='.$st_id);
		$data = array("op" =>"content",'code'=>$code );
		$cates = https_post($url, $data);
		return json_decode($cates);
	}
	private function get_list($domain,$st_id,$catid,$page,$title='')
	{
		$url = 'http://'.$domain.'/zampo.php';
		$code = sys_auth('action=get_content_list&siteid='.$st_id.'&catid='.$catid.'&page='.$page.'&title='.$title);
		$data = array("op" =>"content",'code'=>$code );
		$cates = https_post($url, $data);
		return json_decode($cates);
	}
	private function get_content_info($domain,$st_id,$catid,$id)
	{
		$url = 'http://'.$domain.'/zampo.php';
		$code = sys_auth('action=content_info&siteid='.$st_id.'&catid='.$catid.'&id='.$id);
		$data = array("op" =>"content",'code'=>$code );
		$cates = https_post($url, $data);
		return json_decode($cates);
	}
	//ajax获取当前站点的分类
	public function get_cate_ajax()
	{
		$do_id = isset($_REQUEST['do_id'])? intval($_REQUEST['do_id']):0;
		$st_id = isset($_REQUEST['st_id'])? intval($_REQUEST['st_id']):0;
		$fid  	=  isset($_REQUEST['fid'])? intval($_REQUEST['fid']):0;
		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;
		$str = '<option value="">请选择分类...</option>';
		if(empty($do_id)||empty($st_id)){
			echo $str;
			exit();
		}
		$domain = $this->get_domain($do_id);
		$cates = $this->get_cate($domain,$st_id);
		$category = array();
		foreach($cates as $val){
			if($val->parentid == $fid){
				$category[] = array('catid'=>$val->catid,'catname'=>$val->catname);
			}
		}
		
		if(empty($category)){
			echo $str;
			exit();
		}
		
		foreach($category as $val)
		{
			$str .= '<option value="' . $val['catid'] . '"';

			if($val['catid'] == $check_id)

			{

				$str .= " selected";

			}

			$str .= '>' . $val['catname'] . '</option>';
		}
		echo $str;
	}
	public function index()
	{
	
		redirect('c=pages&m=write');
	}
	
	public function write()
	{
		$pid = $this->input->get('pid',true);
		if (empty($pid))
		{
			$this->_write();
		}
		else
		{
			is_numeric($pid)?$this->_edit($pid):show_error('禁止访问：危险操作');
		}
	}
	
	private function _write()
	{
		
		$this->_data = $this->common->config('write_post');
		
		//操作用户所关联的医院
		$hospital = $this->common->get_hosname();
		
		$hos_id = $this->input->get('hos_id',true);
		
		// 默认展示的医院
		if(empty($hos_id)){
			$hos_id = $hospital[0]['hos_id'];
		}
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		//分类列表
		$this->_data['all_categories'] = $this->Metas_model->list_metas('category',$hos_id);
		
		
		
		//标签库
		$tags = $this->Metas_model->list_metas('tag',$hos_id);
		$tag_str = '';
		foreach($tags as $val){
			
			$tag_str .= '"'.$val['name'].'",';
		
		}
		$tag_str = substr($tag_str,0,-1);
		
		$this->_data['tag_str'] = $tag_str;
		
		$this->_data['top'] = $this->load->view('top', $this->_data, true);

		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);

		$this->_data['pop_fid'] = array();
	
		$this->_load_validation_rules();
	
		if($this->form_validation->run() === FALSE)
		{
			$content = $this->_get_form_data();
			if(!empty($content)){
				$cates = $content['category'];
				if(!empty($cates)){
					$cate = implode(',',$cates);
					$sql = "select distinct(fid) from ".$this->common->table('metas')." where mid in ($cate)";
					$arr = $this->common->getAll($sql);
					
					foreach($arr as $val){
						$metas_show[] = $this->Metas_model->list_metas('category','',$val['fid']);
						$this->_data['pop_fid'][] = $val['fid'];
					}
					$str = '';
					foreach($metas_show as $v){
						foreach($v as $val){
							$str .='<li class="cate_'.$val['fid'].'">';
							if(in_array($val['mid'],$cates)){
								$str .='<input name="category[]" value="'. $val['mid'].'" checked type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
							}else{
								$str .='<input name="category[]" value="'. $val['mid'].'" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
							}
							$str .='</li>';
						}
					}
					$this->_data['str'] = $str;
				}
				$this->_data['title_post'] = $content['title'];
				$this->_data['img'] = $content['thumb'];
				$this->_data['text'] = $content['text'];
				$this->_data['description'] = $content['description'];
				$this->_data['tags'] = $content['tags'];
				$this->_data['slug'] = $content['slug'];
				if(!empty($content['hos_id'])){
					$this->_data['hos_id'] = $content['hos_id'];
				}
				$this->_data['order'] = $content['order'];
				$this->_data['thumb'] = empty($content['thumb']) ? '' : base_url().'static/upload/'.$content['thumb'];
			
			}
			$view = $this->load->view('content/write_post', $this->_data,true);
			print_r($view);
		}
		else
		{
			$this->_insert_post();
		}
	}
	
	//数据添加
	private function _insert_post()
	{
		// 获取要插入的数据
		$content = $this->_get_form_data();
		// 判断插入文章状态【用户选择保存，文章状态为草稿，用户选择更新，根据权限判断，如果用户有更新权限，则更新，没有则进去待审核区】
		$draft = $this->input->post('draft', TRUE);
		$content['status'] = $draft ? 'draft' : (($this->common->auth('post_editor') && !$draft) ? 'publish' : 'waiting');
		// 处理相关附加属性
		$content['type'] = 'post';
		$content['created'] = time();
		$content['click'] = 0;
		$content['admin_id'] = $_COOKIE['l_admin_id'];
		
		$insert_struct = array(
            'title'         =>  empty($content['title']) ? NULL : $content['title'],
            'description'   =>  empty($content['description']) ? NULL : $content['description'],
            'created'       =>  empty($content['created']) ? time() : $content['created'],
            'modified'      =>  time(),
            'text'          =>  empty($content['text']) ? NULL : $content['text'],
            'order'         =>  empty($content['order']) ? 0 : intval($content['order']),
            'authorId'      =>  isset($content['admin_id']) ? $content['admin_id'] : $_COOKIE['l_admin_id'],
            'type'          =>  empty($content['type']) ? 'post' : $content['type'],
            'status'        =>  empty($content['status']) ? 'publish' : $content['status'],
            'click'  		=>  empty($content['click']) ? 0 : intval($content['click']),
            'hos_id'  		=>  empty($content['hos_id']) ? 0 : intval($content['hos_id']),
            'thumb'  		=>  empty($content['thumb']) ? '' : trim($content['thumb']),
           
        );
		
		$insert_id = $this->Posts_model->add_post($insert_struct);
		$this->_apply_slug($insert_id);
		if($insert_id >0)
		{
			/** 插入分类 */
            $this->_set_categories($insert_id, $content['category'], false, 'publish' == $content['status']);
            
            /** 插入标签 */
            $this->_set_tags($insert_id, empty($content['tags']) ? NULL : $content['tags'],$content['hos_id'], false, 'publish' == $content['status']);
            
		}
		
		if($content['status'] == 'draft')
		{
			$this->session->set_flashdata('success', '草稿"'.$content['title'].'"已经保存');
			redirect('c=posts&m=write&pid='.$insert_id);
		}
		else
		{
			$this->session->set_flashdata('success', '文章 <b>'.$content['title'].'</b> 已经被创建');
			redirect('c=posts&m=manage');
		}
	}
	
	/**
     * 修改一个日志（与用户交互）
     *
     * @access private
     * @return void
     */
	private function _edit($pid)
	{
		/** get post data **/
		$post_db = $this->Posts_model->get_post_by_id('pid', $pid);
		$this->_pid = $pid;
		/** test if it exists or not **/
		if(empty($post_db))
		{
			show_error('发生错误：文章不存在或已被删除。');
			exit();
		}
		
		/** contributor can modify the post from himself ONLY **/
		if(!$this->common->auth('post_editor') && $_COOKIE['l_amdin_id'] != $post_db['authorId'])
		{
			show_error('权限错误：你仅能修改自己的文章。');
			exit();
		}
		$this->_data = $this->common->config('post_editor');
		//操作用户所关联的医院
		$hospital = $this->common->get_hosname();
		
		$hos_id = $post_db['hos_id'];
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		//标签库
		$tags = $this->Metas_model->list_metas('tag',$hos_id);
		$tag_str = '';
		foreach($tags as $val){
			
			$tag_str .= '"'.$val['name'].'",';
		
		}
		$tag_str = substr($tag_str,0,-1);
		
		$this->_data['tag_str'] = $tag_str;
		
		//populated data: tags and categories
		$this->Metas_model->get_metas($pid);
		$pop_categories = Common::array_flatten($this->Metas_model->metas['category'], 'mid');
		$pop_fid = Common::array_flatten($this->Metas_model->metas['category'], 'fid');
		$pop_tags = Common::format_metas($this->Metas_model->metas['tag'], ',' , FALSE);
	
		//展开文章所属分类
		foreach($pop_fid as $val){
			$metas_show[] = $this->Metas_model->list_metas('category','',$val);
		}
		$str = '';
		foreach($metas_show as $v){
			foreach($v as $val){
				$str .='<li class="cate_'.$val['fid'].'">';
				if(in_array($val['mid'],$pop_categories)){
					$str .='<input name="category[]" value="'. $val['mid'].'" checked type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
				}else{
					$str .='<input name="category[]" value="'. $val['mid'].'" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
				}
				$str .='</li>';
			}
		}
		//populated the rest data to the view
	
		$this->_data['all_categories'] = $this->Metas_model->list_metas('category',$hos_id);
		$this->_data['all_tags'] = $this->Metas_model->list_metas('tag',$hos_id);
		$this->_data['pid'] = $pid;
		$this->_data['title_post'] = $post_db['title'];
		$this->_data['text'] = $post_db['text'];
		$this->_data['post_category'] = $pop_categories;
		$this->_data['pop_fid'] = $pop_fid;
		$this->_data['created'] = $post_db['created'];
		$this->_data['slug'] = $post_db['slug'];
		$this->_data['tags'] = $pop_tags;
		$this->_data['str'] = $str;
		$this->_data['order'] = $post_db['order'];
		$this->_data['description'] = $post_db['description'];
		
		$this->_data['thumb'] = empty($post_db['thumb']) ? '' : base_url().'static/upload/'.$post_db['thumb'];
		
		$this->_data['top'] = $this->load->view('top', $this->_data, true);

		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		
		//validation stuff
		$this->_load_validation_rules();
		
		//validation passed or failed?
		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('content/write_post',$this->_data);
		}
		else
		{
			$this->_update_post($pid, $post_db);	
		}
	}
	
	/**
     * 修改一个日志（与数据库交互）
     *
     * @access private
     * @return void
     */
	private function _update_post($pid, $exist_post)
	{
		/** 获取表单数据 */
		$content = $this->_get_form_data();
		/** 文章类型 */
		$content['type'] = 'post';
		// 判断插入文章状态【用户选择保存，文章状态为草稿，用户选择更新，根据权限判断，如果用户有更新权限，则更新，没有则进去待审核区】
		$draft = $this->input->post('draft', TRUE);
		$content['status'] = $draft ? 'draft' : (($this->common->auth('post_editor') && !$draft) ? 'publish' : 'waiting');

		
		$update_struct = array(
            'title'         =>  empty($content['title']) ? NULL : $content['title'],
            'description'   =>  empty($content['description']) ? NULL : $content['description'],
            'modified'      =>  time(),
            'text'          =>  empty($content['text']) ? NULL : $content['text'],
            'order'         =>  empty($content['order']) ? 0 : intval($content['order']),
            'type'          =>  empty($content['type']) ? 'post' : $content['type'],
            'status'        =>  empty($content['status']) ? 'publish' : $content['status'],
            'hos_id'  		=>  empty($content['hos_id']) ? 0 : intval($content['hos_id']),
			'thumb'  		=>  empty($content['thumb']) ? '' : trim($content['thumb']),
        );
        
        /** 核心数据进主库 */
		$updated_rows = $this->Posts_model->update_post($pid, $update_struct);
		$this->_apply_slug($pid);
		if($updated_rows >0)
		{
			/** 插入分类 */
            $this->_set_categories($pid, $content['category'], 'publish' == $exist_post['status'], 'publish' == $content['status']);
            
            /** 插入标签 */
            $this->_set_tags($pid, empty($content['tags']) ? NULL : $content['tags'], $content['hos_id'], 'publish' == $exist_post['status'], 'publish' == $content['status']);
            
		}
		
				
		if($content['status'] == 'draft')
		{
			$this->session->set_flashdata('success', '草稿"'.$content['title'].'"已经保存');
			redirect('c=posts&m=write&pid='.$pid);
		}
		else
		{
			$this->session->set_flashdata('success', '文章 <b>'.$content['title'].'</b> 修改成功');
			redirect('c=posts&m=manage');
		}
	}
	
	private function _apply_slug($pid)
	{
		$slug = $this->input->post('slug',TRUE);
		$slug = (!empty($slug))?$slug:NULL;
		$slug = Common::repair_slugName($slug,$pid);
		
		$this->Posts_model->update_post($pid, array('slug' => $this->Posts_model->get_slug_name($slug, $pid)));
	}
	
	
	/**
     * 设置内容标签
     * 
     * @access public
     * @param integer $cid
     * @param string $tags
     * @param boolean $count 是否参与计数
     * @return string
     */
    private function _set_tags($pid, $tags, $hos_id, $before_count = true, $after_count = true)
    {
        $tags = str_replace('，', ',', $tags);
        $tags = array_unique(array_map('trim', explode(',', $tags)));
        
        /** 取出已有meta */
        $this->Metas_model->get_metas($pid);

        /** 取出已有tag */
        $exist_tags = Common::array_flatten($this->Metas_model->metas['tag'], 'mid');
        
        /** 删除已有tag */
        if ($exist_tags) 
        {
            foreach ($exist_tags as $tag) 
            {
                $this->Metas_model->remove_relationship_strict($pid, $tag);
                
                if ($before_count) 
                {
                    $this->Metas_model->meta_num_minus($tag);
                }
            }
        }
        
        /** 取出插入tag */
        $insert_tags = $this->Metas_model->scan_tags($tags,$hos_id);
        
        /** 插入tag */
        if ($insert_tags) 
        {
            foreach ($insert_tags as $tag) 
            {
                $this->Metas_model->add_relationship(array('pid' => $pid,'mid' => $tag));
                
                if ($after_count)
                {
                    $this->Metas_model->meta_num_plus($tag);
                }
            }
        }
    }
	
	/**
     * 设置分类
     * 
     * @access public
     * @param integer $cid 内容id
     * @param array $categories 分类id的集合数组
     * @param boolean $count 是否参与计数
     * @return integer
     */
    public function _set_categories($pid, $categories = array(), $before_count = true, $after_count = true)
    {
        $categories = array_unique(array_map('trim', $categories));
        
        /** 取出已有meta */
        $this->Metas_model->get_metas($pid);

        /** 取出已有category */
        $exist_categories = Common::array_flatten($this->Metas_model->metas['category'], 'mid');
        
        /** 删除已有category */
        if ($exist_categories) 
        {
            foreach ($exist_categories as $category) 
            {
                $this->Metas_model->remove_relationship_strict($pid, $category);
                
                if ($before_count) 
                {
                    $this->Metas_model->meta_num_minus($category);
                }
            }
        }
        
        /** 插入新的category */
        if ($categories) 
        {
            foreach ($categories as $category) 
            {
                /** 如果分类不存在 */
                if (!$this->Metas_model->get_meta('BYID', $category)) 
                {
                    continue;
                }
            
                $this->Metas_model->add_relationship(array('pid' => $pid,'mid' => $category));
                
                if ($after_count) 
                {
                    $this->Metas_model->meta_num_plus($category);
                }
            }
        }
    }
	
	private function _get_form_data()
	{
		return array(
			'title' 		=> 	$this->input->post('title',TRUE),
			'description' 	=> 	$this->input->post('description',TRUE),
			'text' 			=> 	$this->input->post('text',TRUE),
			'tags' 			=> 	$this->input->post('tags',TRUE),
			'category' 		=> 	$this->input->post('category',TRUE),
			'slug' 			=> 	$this->input->post('slug',TRUE),
			'hos_id' 		=> 	$this->input->post('hos_id',TRUE),
			'order' 		=> 	$this->input->post('order',TRUE),
			'thumb' 		=> 	$this->input->post('img',TRUE)
		);
	}
	
	//表单验证规则
	private function _load_validation_rules()
	{
		$this->form_validation->set_rules('title', '标题', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('description', '描述', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('text', '内容', 'required|trim');
		$this->form_validation->set_rules('tags', '标签', 'trim|htmlspecialchars');
		$this->form_validation->set_rules('category[]', '分类', 'required|trim');	
		$this->form_validation->set_rules('slug', '缩略名', 'trim|alpha_dash|htmlspecialchars');	
		$this->form_validation->set_rules('order', '排序', 'trim|integer');	
	}
	
	public function _slug_check($str)
	{
		
	
		if($this->Posts_model->check_exist(Common::repair_slugName($str),$this->_pid))
		{
			$this->form_validation->set_message('_slug_check', '已经存在一个为 '.$str.' 的缩略名');
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
     * 批量操作文章
     *
     * @access private
     * @return void
     */
	public function operate()
	{
		/** 尝试get获取数据 */
		$action = $this->input->get('do',TRUE);
		
		switch($action)
		{
			case 'delete':
				$this->_remove();
				break;
			case 'approved':
				$this->_approved();
				break;
			default:
				show_404();
				break;
		}
	}
	
	/**
     * 批量删除文章
     *
     * @access private
     * @return void
     */
	private function _remove()
	{
		$posts = $this->input->get('posts',TRUE);
		$posts =  substr($posts, 0, -1);
		$posts = explode(',',$posts);
		$deleted = 0;
		// 有删除权限的管理着可删除文章
		if($posts && is_array($posts))
		{
			foreach($posts as $post){
				if(empty($post)){
					continue;
				}
				$content = $this->Posts_model->get_post_by_id('pid', $post);
				if($content && $this->common->auth('posts_del'))
				{
					$this->Posts_model->remove_post($post);
					$this->Posts_model->remove_imgtxt($post);
					$this->Metas_model->remove_relationship('pid', $post);
					if($content['status'] == 'publish'){
						$metas = $this->Metas_model->get_metas($post, TRUE);
						foreach($metas as $meta){
							$this->Metas_model->meta_num_minus($meta['mid']);
						}
					}
					$deleted++;
				}
				$content = null;
			}
		}
		$msg = ($deleted>0) ? '项目删除成功' : '没有项目被删除';
        $notify = ($deleted>0) ? 'success':'error';
        
        $this->session->set_flashdata($notify, $msg);
		go_back();
	}

	/**
     * 管理日志
     *
     * @access public
     * @return void
     */
	public function manage() // 文章有三种类型：已发布，草稿，待审核
	{
		// 组装查询条件
		$hos_id = $this->input->get('hos_id',true);
		$status = $this->input->get('status',true);
		$keywords = $this->input->get_post('keywords',true);
		$mid = $this->input->get_post('mid',true);
		$status = empty($status) ? 'publish' : trim($status);
		if(!in_array($status,array('publish', 'draft', 'waiting'))){
		
			redirect('c=posts&m=manage');
		}
		
		// 获取用户所在医院信息
		$hospital = $this->common->get_hosname();
		
		$this->_data = $this->common->config('post_manage');
		
		// 默认展示的医院
		if(empty($hos_id)){
			$hos_id = $hospital[0]['hos_id'];
		}
		
		// 医院下的分类
		$this->_data['category'] = $this->Metas_model->list_metas('category',$hos_id);
		
		
		// 文章状态
		$where = ' and status = "'.$status.'"';
		$this->_data['type'] = $status;
		$query = array();
		
		$query[] = 'status='.$status;
		//*******
		// 所属医院
		if(!empty($hos_id)&&is_numeric($hos_id)){
		
			$where .= ' and hos_id = '.$hos_id;
			$query[] = 'hos_id='.$hos_id;
		}
		//*********
		
		// 文章分类
		$mid = (!empty($mid)) ? intval($mid) : 0;
		
		if(!empty($mid) && is_numeric($mid)){
			$fid = $this->input->get_post('fid',true);
			$query[] = 'mid='.$mid;
			$query[] = 'fid='.$fid;
			$this->_data['fid'] = $fid;
			$this->_data['mid'] = $mid;
		}
		//************
		
		// 标题关键字筛选
		$keywords = strip_tags($keywords);
		
		if(!empty($keywords)){
		
			$where .= ' and title like "%'.$keywords.'%"';
			$query[] = 'keywords='.$keywords;
			$this->_data['keywords'] = $keywords;
		}
		//********
		
		// 分页操作
		if(isset($_POST['mid'])||isset($_POST['keywords'])){
			$page = 0;
			$per_page = 10;
			
		}else{
			$page = isset($_REQUEST['per_page'])? intval($_REQUEST['per_page']):0;
			$per_page = isset($_REQUEST['p'])? intval($_REQUEST['p']):10;
			$per_page = empty($per_page)? 10:$per_page;
		}
		$this->_data['now_page'] = $page;
		$posts = $this->Posts_model->get_posts('post',$per_page,$page,$where,$mid);
		
		foreach($posts as $key=>$post){
		
			// 获取文章分类信息
			
			$this->Metas_model->get_metas($post['pid']);
			$posts[$key]['categories']= $this->Metas_model->metas['category'];
		}
		
		$this->_data['posts'] = $posts;
		
		$posts_count = $this->Posts_model->posts_count('post',$where,$mid);
		$this->load->library('pagination');

		$this->load->helper('page');
		
		$config = page_config();
		
		$config['total_rows'] = $posts_count['count'];
		
		if(isset($_POST['mid'])||isset($_POST['keywords'])){
			$config['set_base'] = true;
		}
		$config['per_page'] = $per_page;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;
		
		$config['base_url'] = '?c=posts&m=manage&p='.$per_page.'&'.implode('&',$query);
		
		$this->pagination->initialize($config);
		
		$this->_data['page'] = $this->pagination->create_links();
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		$this->_data['status'] = 'post';
		
		$this->_data['top'] = $this->load->view('top', $this->_data, true);

		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		
		$view = $this->load->view('content/post_manage',$this->_data,true);
		
		print_r($view);
		
	}
	public function cate_list_ajax()
	{
		$fid = isset($_REQUEST['fid'])? intval($_REQUEST['fid']):0;
		$check_id = isset($_REQUEST['check_id'])? intval($_REQUEST['check_id']):0;
		
		
		if(empty($fid))
		{
			exit();
		}
		$cate_list = $this->common->getAll('select name,mid from '.$this->common->table('metas').' where fid = '.$fid.' order by `order` desc');
		
		if(empty($cate_list))
		{
			exit();
		}
		$str = '<option value="0">请选择子分类</optioon>';
		foreach($cate_list as $val){
		
			$str .= '<option value="'.$val['mid'].'">'.$val['name'].'</optioon>';
		}
		echo $str;
	}

}