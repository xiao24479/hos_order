<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Metas extends CI_Controller
{
	
	private $_data = array();	//传递到视图的数据
	private $_mid = 0;	//当前操作Meta的ID
	private $_type = 'category';	//当前操作Meta类型
	private $_map = array('category' => '分类', 'tag' => '标签');	//中英文转化表
	private $_hos_id = 0;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library(array('input','form_validation','session'));
		$this->load->model('Metas_model');
		$this->model = $this->Metas_model; 	//加载数据操作类 
	}
	
	public function index()
	{
		redirect('http://www.renaidata.com/?c=metas&m=manage');
	}
	
	public function manage()
	{
		$type = ($this->input->get('type',true)) ? $this->input->get('type',true) : 'category';
		
		$mid = $this->input->get('mid',true);
		
		$hos_id = $this->input->get('hos_id',true);
		
		$this->_data = $this->common->config('manage');
		
		$hospital = $this->common->get_hosname();
		
		$this->_data['hospital'] = $hospital;
		
		// 默认展示的医院
		if(empty($hos_id)){
			$hos_id = $hospital[0]['hos_id'];
		}
		
		$this->_hos_id = $hos_id;
		
		$this->_data['hos_id'] = $hos_id;
		
		$this->_data['type'] = $type;
		
		$this->_data[$type] = $this->Metas_model->list_metas($type,$hos_id);
		
		$this->_data['fid'] = 0;
		
		// 有传递id的情况
		if($mid && is_numeric($mid))
		{
			$this->_data['mid'] = $mid;
		
			$meta = $this->Metas_model->get_meta('BYID', $mid);
			
			$this->_data['name'] = $meta['name'];
			$this->_data['slug'] = $meta['slug'];
			$this->_data['description'] = $meta['description'];
			$this->_data['hos_id'] = $meta['hos_id'];
			$this->_data['fid'] = $meta['fid'];
			$this->_data['order'] = $meta['order'];
			
			unset($meta);
		}
		
		$this->_operate($type, $mid);
		
		$this->_data['top'] = $this->load->view('top', $this->_data, true);

		$this->_data['themes_color_select'] = $this->load->view('themes_color_select', '', true);

		$this->_data['sider_menu'] = $this->load->view('sider_menu', $this->_data, true);
		
		
		$view = $this->load->view('content/manage_metas', $this->_data,true);
		print_r($view);
		
	}
	
	public function cate_list_ajax()
	{
		$mid = $this->input->post('mid',true);
		$type = $this->input->post('type',true);
		$pid = $this->input->post('pid',true);
	
		$metas = $this->Metas_model->list_metas('category','',$mid);
		$str = '';
		if($type==1){
			if($pid){
				$this->Metas_model->get_metas($pid);
				$pop_categories = Common::array_flatten($this->Metas_model->metas['category'], 'mid');
			
				foreach($metas as $val) {
					$str .='<li class="cate_'.$mid.'">';
					if(in_array($val['mid'],$pop_categories)){
						$str .='<input name="category[]" value="'. $val['mid'].'" checked type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
					}else{
						$str .='<input name="category[]" value="'. $val['mid'].'" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
					}
					$str .='</li>';
				}
			}else{
				foreach($metas as $val) {
					$str .='<li class="cate_'.$mid.'">';
					$str .='<input name="category[]" value="'. $val['mid'].'" type="checkbox" style="margin-top: -1px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$val['name'];
					$str .='</li>';
				}
			}
		}else{
			foreach($metas as $val) {
				$str .= '<tr class="cate_'.$mid.'">';
					$str .= '<td> &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="mid[]" value="'.$val['mid'].'" style="margin-top: -3px;"></td>';
					$str .= '<td>&nbsp;&nbsp;&nbsp;&nbsp;||----<a href="?c=metas&m=manage&type=category&hos_id='.$val['hos_id'].'&mid='.$val['mid'].'">'.$val['name'].'</td>';
					$str .= '<td>'.$val['slug'].'</td>';
					$str .= '<td>'.$val['count'].'</td>';
				$str .= '</tr>';
			}
		}
		echo $str;
	}
	
	// 新增或编辑分类/标签
	private function _operate($type, $mid)
	{
		$this->_type = $type;
		$this->_mid = $mid;
		$this->_load_validation_rules();
		
		if($this->form_validation->run() === FALSE)
		{
			return;
		}
		else
		{
			$action = $this->input->post('do',TRUE);
			$name = $this->input->post('name',TRUE);
			$slug = $this->input->post('slug',TRUE);
			$description = $this->input->post('description',TRUE);
			$hos_id = $this->input->post('hos_id',TRUE);
			$order = $this->input->post('order',TRUE);
			$fid = $this->input->post('fid',TRUE);
			
			$data = array(
				'name' => $name,
				'type' => $type,
				'slug' => Common::repair_slugName((!empty($slug))?$slug:$name),
				'description' => (!$description)? NULL : $description,
				'hos_id' => intval($hos_id),
				'fid' => intval($fid),
				'order' => intval($order),
			);
			
			
			if('insert' == $action)
			{
				$this->Metas_model->add_meta($data);
				
				//数据添加成功提示并返回操作页
				$this->session->set_flashdata('success', $this->_type.'添加成功');
			}
			
			if('update' == $action)
			{
				$this->Metas_model->update_meta($mid, $data);
				
				//数据更新成功提示并返回操作页
				$this->session->set_flashdata('success', $this->_type.'更新成功');
			}
			
			go_back();
		}
	}
	
	private function _load_validation_rules()
	{
		$this->form_validation->set_rules('name', '名称', 'required|trim|callback__name_check|callback__name_to_slug|htmlspecialchars');
		
		if('category' == $this->_type)
		{
			$this->form_validation->set_rules('slug', '缩略名', 'trim|callback__slug_check|alpha_dash|htmlspecialchars');
		}
		else
		{
			$this->form_validation->set_rules('slug', '缩略名', 'trim|callback__slug_check|htmlspecialchars');	
		}
		
		$this->form_validation->set_rules('description', '描述', 'trim|htmlspecialchars');	
		
		$this->form_validation->set_rules('order', '排序', 'trim|integer');	
	
	}
	
	public function operate()
	{
		$action = $this->input->get('do',TRUE);
		$metas = $this->input->get('metas',TRUE);
		$metas =  substr($metas, 0, -1);;
		$type = explode(',',$metas);
		switch ($action)
		{
			case 'delete':
					$this->_remove($type);
					break;
			case 'refresh':
					$this->_refresh($type);
					break;
			case 'merge':
					$this->_merge($type);
					break;
			default:
					show_404();
					break;
		}
	}
		
		
		
	private function _remove($type)
	{
        $deleted = 0;
        
        if ($type && is_array($type)) 
        {
            foreach ($type as $meta) 
            {
                if($this->Metas_model->remove_meta($meta))
                {
                	$this->Metas_model->remove_relationship('mid',$meta);
                	$deleted ++;
                }
            }
        }
        
        $msg = ($deleted>0) ? '项目删除成功' : '没有项目被删除';
        $notify = ($deleted>0) ? 'success':'error';
        
        $this->session->set_flashdata($notify, $msg);
		go_back();
	}
	
	
	public function _name_check($str)
	{
		if($this->Metas_model->check_exist($this->_type, 'name', $str, $this->_mid, $this->_hos_id))
		{
			$this->form_validation->set_message('_name_check', '已经存在一个为 '.$str.' 的名称');
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	 /**
     * 回调函数：检查Slug是否唯一
     * 
     * @access public
     * @param $str 输入值
     * @return bool
     */
	public function _slug_check($str)
	{
	
		if($this->Metas_model->check_exist($this->_type, 'slug', Common::repair_slugName($str), $this->_mid,$this->_hos_id))
		{
			$this->form_validation->set_message('_slug_check', '已经存在一个为 '.$str.' 的缩略名');
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	 /**
     * 回调函数：名称转化为缩略名
     * 
     * @access public
     * @param $str 输入值
     * @return bool
     */
	public function _name_to_slug($str)
	{
		$slug = Common::repair_slugName($str);
		
        if(empty($slug) || $this->Metas_model->check_exist($this->_type, 'slug',$slug, $this->_mid)) 
        {
        	$this->form_validation->set_message('_name_to_slug', '分类无法转换为缩略名');
        	return FALSE;
        }
        
        return TRUE;
	}
		
		
	function test()
	{
		$str = Common::repair_slugName('test');
		$data = $this->Metas_model->check_exist($this->_type, 'slug',$str, 18);
		var_dump($data);
	}
	private function _merge($type)
	{
		$metas = $this->input->post('mid',TRUE);
		
		if($metas && is_array($metas))
		{
			$merge = $this->input->post('merge',TRUE);

			if('tag' == $type)
			{
				$merge = $this->metas_mdl->scan_tags($merge);
				
				if(empty($merge))
				{
					$this->session->set_flashdata('error', '合并到的标签名不合法');
					redirect('admin/metas/manage/tag');
				}
			}
			
			$this->metas_mdl->merge_meta($merge, $type, $metas);
			
			$this->session->set_flashdata('success', $this->_map[$type].'已被合并');
		}
		else
		{
			$this->session->set_flashdata('error', '请选择需要合并到的'.$this->_map[$type]);
		}
		
		go_back();
	}
	
	
}