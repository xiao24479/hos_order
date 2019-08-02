<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Metas_model extends CI_Model
{
	const TBL_METAS = 'metas';
	const TBL_RELATIONSHIPS = 'relationships';
	const TBL_POSTS = 'posts';
	
	//内容类型： 分类/标签
	private $_type = array('category','tag');
	
	public $metas = NULL;
	
	function __construct()
	{
		parent::__construct();
	}
	
	//获取所有metas数据
	
	public function list_metas($type = 'category',$hos_id=null,$fid=0)
	{
		if(in_array($type , $this->_type)){
		
			if(!empty($hos_id)){
			
				$where = ' and hos_id = '.$hos_id;
			}else{
				$where = '';
			}
		
			return $this->common->getAll('select * from '.$this->common->table('metas').' where type = "'.$type.'" '.$where.' and fid = '.$fid);
		}
	
	}
	
	/**
     *  获取元数据
     * 
     *  @access public
	 *	@param string $type 元数据类别：｛"category"|"tag"|"byID"｝
	 *	@param string $name 元数据名称
	 *	@return object － result object
     */
	public function get_meta($type = 'category', $name = '')
	{
		if(empty($name)) exit();
		
		if($type && in_array($type, $this->_type))
		{
			return $this->common->getAll('select * from '.$this->common->table('metas').' where type = '.$type.' and name = '.$name);
		}
		
		if($type && strtoupper($type) == 'BYID')
		{
			$mid = intval($name);
			return $this->common->getRow('select * from '.$this->common->table('metas').' where mid = '.$mid);
		}
	}
	
	public function get_metas($pid = 0, $return = FALSE)
	{
		$this->metas = NULL;
	
		$metas = array();
		
		
		$sql = "select m.*,r.pid
				from " .$this->common->table('metas'). " m
				inner join " .$this->common->table('relationships'). " r on m.mid = r.mid
				where r.pid = $pid";
		$metas = $this->common->getAll($sql);
		
		if($return)
		{
		
			return $metas;
		}
		
		//初始化一个metas数组
		foreach($this->_type as $type)
		{
			$this->metas[$type] = array();
		}
	
		if(!empty($metas))
		{
			//根据不同的metas类型自动push进对应的数组
			foreach($metas as $meta)
			{
				foreach($this->_type as $type)
				{
					if($type == $meta['type'])
					{
						array_push($this->metas[$type], $meta);
					}
				}
			}	
		}
		return $this->metas;
		
	}
	
	public function meta_num_minus($mid)
	{
	
		$this->db->query('UPDATE '.$this->common->table('metas').' SET `count` = `count`-1 WHERE `mid`='.$mid.'');
	}
	
	 /**
     * meta个数自增一
     * 
     * @access public
     * @param int $mid meta id
     * @return void
     */
	public function meta_num_plus($mid)
	{
		$this->db->query('UPDATE '.$this->common->table('metas').' SET `count` = `count`+1 WHERE `mid`='.$mid.'');
	}
	
	/**
     * 添加元数据/内容关系
     * 
     * @access public
	 * @param  array $relation_data  内容
     * @return boolean 成功与否
     */
	public function add_relationship($relation_data)
	{
		$this->db->insert(self::TBL_RELATIONSHIPS, $relation_data);
		
		return ($this->db->affected_rows()==1) ? $this->db->insert_id() : FALSE;
	}
	
	
	/**
     * 根据tag获取ID
     * 
     * @access public
     * @param  mixed $inputTags 标签名
     * @return mixed
     */
    public function scan_tags($inputTags,$hos_id)
    {
        $tags = is_array($inputTags) ? $inputTags : array($inputTags);
        $result = array();
        
        foreach ($tags as $tag) 
        {
            if (empty($tag)) 
            {
                continue;
            }
        
        	$row = $this->db->select('*')
        					->from(self::TBL_METAS)
        					->where('type','tag')
        					->where('name',$tag)
        					->where('hos_id',$hos_id)
        					->limit(1)
        					->get()
        					->row();
            
            if ($row) 
            {
                $result[] = $row->mid;
            } 
            else 
            {
                $slug = Common::repair_slugName($tag);
                
                if ($slug) 
                {
                    $result[] = $this->add_meta(array(
			                        'name'  =>  $tag,
			                        'slug'  =>  $slug,
			                        'type'  =>  'tag',
			                        'count' =>  0,
			                        'order' =>  0,
			                        'hos_id' =>  $hos_id,
			                    ));
                }
            }
        }
        
        return is_array($inputTags) ? $result : current($result);
    }
	
		/**
     * 删除关系
     * 
     * @access public
	 * @param  int   $pid  内容ID
	 * @param  int 	 $mid  meta ID
     * @return boolean 成功与否
     */
	public function remove_relationship_strict($pid, $mid)
	{
		$this->db->delete(self::TBL_RELATIONSHIPS,
						  array(
						  	'pid'=> intval($pid),
						  	'mid'=> intval($mid)
						 )); 
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	/**
     * 检查meta是否存在
     * 
     * @access public
	 * @param string - $type 类型
	 * @param string - $key 栏位名
	 * @param string - $value 内容
	 * @param int    - $exclude_mid 要排除的mid
     * @return bool
     */
	public function check_exist($type = 'category', $key = 'name', $value = '', $exclude_mid = 0, $hos_id='')
	{
		$this->db->select('mid')->from(self::TBL_METAS)->where($key, trim($value));
		
		if(!empty($exclude_mid) && is_numeric($exclude_mid))
		{
			$this->db->where('mid !=', $exclude_mid);	
		}
		
		if($type && in_array($type, $this->_type))
		{
			$this->db->where('type', $type);
		}
		
		if(!empty($hos_id) && is_numeric($hos_id))
		{
			$this->db->where('hos_id', $hos_id);
		}
		
		$query = $this->db->get();
		
		$num = $query->num_rows();
		
		$query->free_result();
		
		return ($num > 0) ? TRUE : FALSE;	
	}
	
	
	/**
     * 添加meta
     * 
     * @access public
	 * @param  array $meta_data  内容
     * @return boolean 成功与否
     */
	public function add_meta($meta_data)
	{
		$this->db->insert(self::TBL_METAS, $meta_data);
		
		return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
	}
	
	/**
    * 修改内容
    * 
    * @access public
	* @param int - $data 内容信息
    * @return boolean - success/failure
    */	
	public function update_meta($mid, $data)
	{
		$this->db->update(self::TBL_METAS, $data, array('mid' => intval($mid)));
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	/**
     * 删除一个内容
     * 
     * @access public
	 * @param int - $mid 内容id
     * @return boolean - success/failure
     */
	public function remove_meta($mid)
	{
		$this->db->delete(self::TBL_METAS, array('mid' => intval($mid))); 
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	/**
     * 删除关系
     * 
     * @access public
	 * @param  string   $column  唯一PK
	 * @param  int $value  值
     * @return boolean 成功与否
     */
	public function remove_relationship($column = 'pid', $value)
	{
		$this->db->delete(self::TBL_RELATIONSHIPS, array($column => intval($value))); 
	
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}

}