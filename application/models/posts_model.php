<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Posts_model extends CI_Model {

	const TBL_POSTS = 'hui_posts';
	const TBL_METAS = 'hui_metas';
	const TBL_WX_IMGTXT = 'hui_wx_imgtxt';
	const TBL_RELATIONSHIPS = 'hui_relationships';
	
	
	// 内容类型 日志/附件/独立页面
	private $_post_type = array('post', 'attachment', 'page');
	
	//内容状态：发布/草稿/未归档/等待审核
	private $_post_status = array('publish', 'draft', 'unattached', 'attached', 'waiting');
	
	//内容的唯一栏：pid/slug
	private $_post_unique_field = array('pid','slug');
	
	function __construct()
	{
		parent::__construct();
	}
	
	//获取类容列表
	public function get_posts($type = 'post',$limit = NULL,$offset = NULL,$where=NULL,$mid=null)
	{
		if(empty($mid)){
			$sql = "select p.*,a.admin_name from " .$this->common->table('posts'). " p 
			LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = p.authorId
			where p.type = '$type' $where order by p.order desc,p.modified desc limit $offset,$limit";
		}else{
			$sql = "select p.*,a.admin_name from ".$this->common->table('relationships'). " r
			LEFT JOIN " . $this->common->table('posts') . " p ON r.pid = p.pid
			LEFT JOIN " . $this->common->table('admin') . " a ON a.admin_id = p.authorId
			where p.type = '$type' and r.mid = $mid $where limit $offset,$limit";
		}
		return	$this->common->getAll($sql);
	}
	
	//统计类容数目
	public function posts_count($type = 'post',$where,$mid=null)
	{
		if(empty($mid)){
			$sql = "select count(1) as count from " .$this->common->table('posts'). " where type = '$type' $where";
		}else{
			$sql = "select count(1) as count from ".$this->common->table('relationships'). " r
			LEFT JOIN " . $this->common->table('posts') . " p ON r.pid = p.pid
			where type = '$type' and r.mid = $mid $where";
		}
		return $this->common->getRow($sql);
	}
	//重置slug
	public function get_slug_name($slug, $pid)
	{
		$result = $slug;
		$count = 1;
		
		while($this->db->select('pid')->where('slug',$result)->where('pid <>',$pid)->get(self::TBL_POSTS)->num_rows() > 0)
		{
			$result = $slug . '_' . $count;
			$count ++;
		}
		
		return $result;
	}
	
	public function get_post_by_id($identity, $value)
	{
		if(!in_array($identity, $this->_post_unique_field))
		{
			return FALSE;
		}
		if($identity == 'slug')
		{
			$value = "'$value'";
		}
		$sql = "select * from " .$this->common->table('posts'). " where $identity = $value";

		return $this->common->getRow($sql);
	}
	
	public function remove_post($pid)
	{
		$this->db->delete(self::TBL_POSTS, array('pid' => intval($pid)));
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
		
		
	}
	public function remove_imgtxt($pid)
	{
		$this->db->delete(self::TBL_WX_IMGTXT, array('pid' => intval($pid)));
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	public function add_post($content_data)
	{
		$this->db->insert(self::TBL_POSTS, $content_data);
		
		return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
	}
	
	/**
    * 修改一个内容
    * 
    * @access public
	* @param int $pid 内容ID
	* @param array   $data 内容数组
    * @return boolean 成功或失败
    */	
	public function update_post($pid,$data)
	{
		$this->db->where('pid', intval($pid));
		$this->db->update(self::TBL_POSTS, $data);
		
		return ($this->db->affected_rows() == 1)?TRUE:FALSE;
	}
	
	public function up_num($pid)
	{
	
		$this->db->query('UPDATE '.$this->common->table('posts').' SET `good` = `good`+1 WHERE `pid`='.$pid);
	}
	
	public function check_exist($slug,$exclude_pid=0){
	
		$this->db->select('pid')->from(self::TBL_POSTS)->where('slug', trim($slug));
		
		if(!empty($exclude_pid) && is_numeric($exclude_pid))
		{
			$this->db->where('pid !=', $exclude_pid);	
		}
		
		$query = $this->db->get();
		
		$num = $query->num_rows();
		
		$query->free_result();
		
		return ($num > 0) ? TRUE : FALSE;	
	}
	
	public function get_posts_by_meta($mid, $meta_type = 'category', $post_type = 'post', $post_status = 'publish', $fields = 'hui_posts.*', $limit = NULL, $offset = NULL)
	{
		$this->db->select($fields . ',hui_admin.admin_name,hui_metas.name as m_name');
		$this->db->from('hui_posts,hui_metas,hui_relationships');
		$this->db->join('hui_admin','hui_admin.admin_id = posts.authorId');
		$this->db->where('hui_posts.pid = hui_relationships.pid');
		$this->db->where('hui_posts.type', $post_type);
		$this->db->where('hui_posts.status', $post_status);
		$this->db->where('hui_metas.mid = hui_relationships.mid');
		$this->db->where('hui_metas.type',$meta_type);
		$this->db->where('hui_metas.mid',$mid);
		$this->db->order_by('hui_posts.created','DESC');
		
		
		if($limit && is_numeric($limit))
		{
			$this->db->limit(intval($limit));
		}
		
		if($offset && is_numeric($offset))
		{
			$this->db->offset(intval($limit));
		}
		
		return $this->db->get();
	}

}