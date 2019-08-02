<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Pages extends CI_Controller
{
	private $_data = array();
	private $_pid;
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library(array('input','form_validation','session'));
		$this->load->model('Posts_model');
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
		$this->_data = $this->common->config('write_page');
		
		//操作用户所关联的医院
		$hospital = $this->common->get_hosname();
		
		$hos_id = $this->input->get('hos_id',true);
		
		// 默认展示的医院
		if(empty($hos_id)){
			$hos_id = $hospital[0]['hos_id'];
		}
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		$this->_load_validation_rules();
		
		if($this->form_validation->run() === FALSE)
		{
			$content = $this->_get_form_data();
			
			if(!empty($content)){
			
				$this->_data['title_post'] = $content['title'];
			
				$this->_data['thumb'] = empty($content['thumb']) ? '' : base_url().'static/upload/'.$content['thumb'];
				
				$this->_data['order'] = $content['order'];
				
				$this->_data['slug'] = $content['slug'];
				
				$this->_data['img'] = $content['thumb'];
				$this->_data['text'] = $content['text'];
				$this->_data['description'] = $content['description'];
				
				if(!empty($content['hos_id'])){
					$this->_data['hos_id'] = $content['hos_id'];
				}
			}
			
			$this->_data['top'] = $this->load->view('top', $this->_data, true);

			$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
			
			$view = $this->load->view('content/write_page', $this->_data,true);
			
			print_r($view);
		}
		else
		{
			$this->_insert_page();
		}
	
	}
	
	private function _edit($pid){
		
		$post_db = $this->Posts_model->get_post_by_id('pid', $pid);
		if(empty($post_db))
		{
			show_error('发生错误：文章不存在或已被删除。');
			exit();
		}
		$this->_pid = $pid;
		$this->_data = $this->common->config('edit_page');
		
		//操作用户所关联的医院
		$hospital = $this->common->get_hosname();
		
		$hos_id = $post_db['hos_id'];
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		
		$this->_data['pid'] = $pid;
		$this->_data['title_post'] = $post_db['title'];
		$this->_data['text'] = $post_db['text'];
		$this->_data['slug'] = $post_db['slug'];
		$this->_data['order'] = $post_db['order'];
		$this->_data['description'] = $post_db['description'];
		$this->_data['img'] = $post_db['thumb'];
		
		$this->_data['thumb'] = empty($post_db['thumb']) ? '' : base_url().'static/upload/'.$post_db['thumb'];
	
		$this->_load_validation_rules();
		
		if($this->form_validation->run() === FALSE)
		{
			$this->_data['top'] = $this->load->view('top', $this->_data, true);

			$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

			$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
			
			$view = $this->load->view('content/write_page', $this->_data,true);
			
			print_r($view);
		}
		else
		{
		
			$this->_update_page();
		}
	}
	
	private function _load_validation_rules()
	{
		$this->form_validation->set_rules('title', '标题', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('description', '描述', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('text', '内容', 'required|trim');	
		$this->form_validation->set_rules('slug', '缩略名', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('order', '页面顺序', 'required|trim|integer');	
	}
	
	private function _update_page()
	{
		$content = $this->_get_form_data();
		$draft = $this->input->post('draft', TRUE);
		$content['status'] = $draft ? 'draft' : (($this->common->auth('page_editor') && !$draft) ? 'publish' : 'draft');
		
		$update_struct = array(
            'title'         =>  empty($content['title']) ? NULL : $content['title'],
            'modified'      =>  time(),
            'text'          =>  empty($content['text']) ? NULL : $content['text'],
            'order'         =>  empty($content['order']) ? 0 : intval($content['order']),
            'status'        =>  empty($content['status']) ? 'publish' : $content['status'],
			'thumb'  		=>  empty($content['thumb']) ? '' : trim($content['thumb']),
			'hos_id'  		=>  empty($content['hos_id']) ? 0 : intval($content['hos_id']),
			'description'  	=>  empty($content['description']) ? '' : trim($content['description']),
        	);
			
		$updated_rows = $this->Posts_model->update_post($this->_pid, $update_struct);
		$this->_apply_slug($this->_pid);
		if($content['status'] == 'draft')
		{
			$this->session->set_flashdata('success', '页面草稿"'.$content['title'].'"已经保存');
			redirect('c=pages&m=write&pid='. $this->_pid);
		}
		else
		{
			$this->session->set_flashdata('success', '页面 <b>'.$content['title'].'</b> 已经被创建');
			redirect('c=pages&m=manage');
		}
	}
	
	private function _insert_page()
	{
		$content = $this->_get_form_data();
		$draft = $this->input->post('draft', TRUE);
		$content['status'] = $draft ? 'draft' : (($this->common->auth('page_editor') && !$draft) ? 'publish' : 'draft');
	
		$content['type'] = 'page';
		$content['created'] = time();
		$content['click'] = 0;
		$content['admin_id'] = $_COOKIE['l_admin_id'];
		
		$insert_struct = array(
            'title'         =>  empty($content['title']) ? NULL : $content['title'],
            'created'       =>  empty($content['created']) ? time() : $content['created'],
            'modified'      =>  time(),
            'text'          =>  empty($content['text']) ? NULL : $content['text'],
            'description'   =>  empty($content['description']) ? NULL : $content['text'],
            'order'         =>  empty($content['order']) ? 0 : intval($content['order']),
            'authorId'      =>  isset($content['admin_id']) ? $content['admin_id'] : '',
            'type'          =>  empty($content['type']) ? 'page' : $content['type'],
            'status'        =>  empty($content['status']) ? 'publish' : $content['status'],
			'thumb'  		=>  empty($content['thumb']) ? '' : trim($content['thumb']),
			'hos_id'  		=>  empty($content['hos_id']) ? 0 : intval($content['hos_id']),
        	);
		
		$insert_id = $this->Posts_model->add_post($insert_struct);
		$this->_apply_slug($insert_id);
		
		if($content['status'] == 'draft')
		{
			$this->session->set_flashdata('success', '页面草稿"'.$content['title'].'"已经保存');
			redirect('c=pages&m=write&pid='. $insert_id);
		}
		else
		{
			$this->session->set_flashdata('success', '页面 <b>'.$content['title'].'</b> 已经被创建');
			redirect('c=pages&m=manage');
		}
	}
	
	public function manage()
	{
		$hos_id = $this->input->get('hos_id',true);
		$status = $this->input->get('status',true);
		$keywords = $this->input->get('keywords',true);
		$status = empty($status) ? 'publish' : trim($status);
		if(!in_array($status,array('publish', 'draft'))){
		
			redirect('c=pages&m=manage');
		}
		
		$hospital = $this->common->get_hosname();
		$this->_data = $this->common->config('page_manage');
		if(empty($hos_id)){
			$hos_id = $hospital[0]['hos_id'];
		}
		$where = ' and status = "'.$status.'"';
		$this->_data['type'] = $status;
		$query = array();
		$query[] = 'status='.$status;
		
		if(!empty($hos_id)&&is_numeric($hos_id)){
		
			$where .= ' and hos_id = '.$hos_id;
			$query[] = 'hos_id='.$hos_id;
		}
		
		$keywords = strip_tags($keywords);
		
		if(!empty($keywords)){
		
			$where .= ' and title like "'.$keywords.'"';
			$query[] = 'keywords='.$keywords;
		}
		
		$page = $this->input->get('per_page',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 0;
		$limit = 10;
		$offset = $page * $limit;
		if($offset < 0)
		{
			redirect('c=pages&m=manage');
		}
		$posts = $this->Posts_model->get_posts('page',$limit,$offset,$where);
		
		$this->_data['posts'] = $posts;
		
		$posts_count = $this->Posts_model->posts_count('page',$where);
		
		$this->load->library('pagination');

		$this->load->helper('page');
		
		$config = page_config();
		
		$config['total_rows'] = $posts_count['count'];

		$config['per_page'] = $limit;

		$config['uri_segment'] = 10;

		$config['num_links'] = 5;
		
		$config['base_url'] = '?c=posts&m=manage&'.implode('&',$query);
		
		$this->pagination->initialize($config);
		
		$this->_data['page'] = $this->pagination->create_links();
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['hospital'] = $hospital;
		
		$this->_data['status'] = 'page';
		
		$this->_data['top'] = $this->load->view('top', $this->_data, true);

		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		
		$view = $this->load->view('content/page_manage',$this->_data,true);
		
		print_r($view);
	}

	private function _get_form_data()
	{
		$form_data = array();
		
		$form_data = array(
			'title' 		=> 	$this->input->post('title', TRUE),
			'text' 			=> 	$this->input->post('text'),
			'slug' 			=> 	$this->input->post('slug', TRUE),
			'thumb'			=>	$this->input->post('img', TRUE),
			'hos_id'		=>	$this->input->post('hos_id', TRUE),
			'order'			=>	$this->input->post('order', TRUE),
			'description'   =>	$this->input->post('description', TRUE)
			
		);
		
		return $form_data;
	
	}
	
	private function _apply_slug($pid)
	{
		$slug = $this->input->post('slug',TRUE);
		$slug = (!empty($slug))?$slug:NULL;
		$slug = Common::repair_slugName($slug,$pid);
		
		$this->Posts_model->update_post($pid, array('slug' => $this->Posts_model->get_slug_name($slug, $pid)));
	}
	
		/**
     * 批量删除页面
     *
     * @access public
     * @return void
     */
	public function remove()
	{
		
		$pages = $this->input->get('pid', TRUE);
		$pages =  substr($pages, 0, -1);;
		$pages = explode(',',$pages);
		$deleted = 0;
		if($pages && is_array($pages))
		{
			foreach($pages as $page)
			{
				if(empty($page))
				{
					continue;
				}
				/** remove post */
				$this->Posts_model->remove_post($page);
				$this->Posts_model->remove_imgtxt($page);
				$deleted++;
			}			
		
		}
		
		($deleted > 0)
					?$this->session->set_flashdata('success', '成功删除页面及其附件')
					:$this->session->set_flashdata('error', '没有页面被删除');
		
		go_back();
	}
	

}