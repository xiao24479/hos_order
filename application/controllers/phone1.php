<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 首页入口
class Phone extends CI_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		
	}
        
        public function get_phone(){
            
       require_once 'static/snoopy/Snoopy.class.php';
       $url = "http://sj.11413.com.cn/get/index.php?action=login";
       $snoopy = new Snoopy;
       $formvars["username"] = "shouji4897";
       $formvars["password"] = "Pf11IE8wxgAVzx2";
       $action = "http://sj.11413.com.cn/get/index.php?action=login";//表单提交地址
        $snoopy->submit($action,$formvars);//$formvars为提交的数组
        $snoopy->setcookies();
        $cookies = $snoopy->cookies;
        //$snoopy->rawheaders["COOKIE"]="PHPSESSID=ljkquo6pv7uvo029l8ceocvlq6;";
        //$snoopy->rawheaders["COOKIE"]=$cookies;
        
        $content=" ";
        for($i=1;$i<=8;$i++){
        $get_url="http://sj.11413.com.cn/get/index.php?action=qqsList&page=".$i;
        $snoopy->fetch($get_url);
        $content=$snoopy->results; //获取表单提交后的 返回的结果 
        $search='%<tr height="30" class="left_txt2"(.*?)</tr>%si';
         preg_match_all($search,$content,$r);

        foreach($r[1] as $k=>$v){
           $str[$k]=$v; 

        } 
       
        foreach($str as $k=>$val){
            $sea='%<td>(.*?)</td>%si';
            $info=array();
            preg_match_all($sea,$val,$info);


            $arr[$k]['time']=$info[1][0];
            $arr[$k]['phone']=$info[1][1];
            $arr[$k]['ss']=$info[1][2];
            $arr[$k]['ip_addr']=$info[1][3];
            $arr[$k]['web_addr']=$info[1][4];
            $arr[$k]['keywords']=$info[1][5];
            $arr[$k]['tips']=$info[1][6];


        }
//        var_dump($arr);
        
        foreach($arr as $k=>$v){
          $input_arr=array(
              'time'=>  strtotime($v['time']),
              'phone'=>strip_tags($v['phone']),
              'ss'=>$v['ss'],
              'ip_addr'=>strip_tags($v['ip_addr']),
              
              'keywords'=>$v['keywords'],
              
              
              
          );
         
//          $this->db->insert($this->common->table('phone'),$input_arr);
          
        if(empty($v['phone'])){   
              continue;
          }else{
             $query="select phone_id from ".$this->common->table('phone')." where phone='".strip_tags($v['phone'])."'"; 
             $res=$this->common->getOne($query);
             
             if(!empty($res)){
               continue;
      
             }else{
               
            $this->db->insert($this->common->table('phone'),$input_arr);
             }
              
              
              
          }

        }
            
            
            
            
            
        }     
            
      
        
  echo "<script> window.location.href='?c=phone&m=index';</script>";
        
        }
       //手机号码获取函数结束
       public function index(){
           $data=array();
           
          $data=$this->common->config('get_phone');
          $phone=isset($_REQUEST['phone'])?trim($_REQUEST['phone']):'';
          $now_time=date('Y年m月d日',time())."-".date('Y年m月d日',time());
          $date=isset($_REQUEST['date'])?trim($_REQUEST['date']):$now_time;
          
          $t_date=explode('-', $date);
          $start=trim($t_date[0]);//年月日格式的日期
          $end=trim($t_date[1]);
          $start_date=str_replace(array("年", "月", "日"), "-", $start);//转换成Y-m-d格式的日期
          $end_date= str_replace(array("年", "月", "日"), "-", $end);
          $data['start']=$start;
          $data['end']=$end;
          $data['start_date']=substr($start_date,0,-1);
          $data['end_date']=substr($end_date,0,-1);
          $s_time=substr($start_date,0,-1)." 00:00:00";
          $e_time=substr($end_date,0,-1)." 23:59:59";
          $s_t=intval(strtotime($s_time));
          $e_t=intval(strtotime($e_time));
          $mark_content=isset($_REQUEST['mark_content'])?trim($_REQUEST['mark_content']):'';
          $zx_name=isset($_REQUEST['zx_name'])?trim($_REQUEST['zx_name']):'';
          $per_page=isset($_REQUEST['per_page'])?intval($_REQUEST['per_page']):0;
          $data['per_page']=$per_page;
          $data['phone']=$phone;
          $data['mark_content']=$mark_content;
          $data['zx_name']=$zx_name;
           $limit_time=time()-15*60;
          
          $where="  where 1 and time<".$limit_time." and time>=".$s_t." and time<".$e_t." ";
           if(!empty($phone)){
             $where=" where phone like '%".$phone."%' ";  
            
           }
           if(!empty($mark_content)){
              $where=" where phone_id in (select phone_id from ".$this->common->table('phone_mark')." where mark_content like '%".$mark_content."%') ";
             
           }
           if(!empty($zx_name)){
              $where=" where phone_id in (select phone_id from ".$this->common->table('phone_mark')." where admin_name like '%".$zx_name."%') ";
              
           }
           
          $orderby=" order by time desc limit ".$per_page.",30";
          
         
          $query="select * from ".$this->common->table('phone').$where.$orderby;
          $rs=$this->common->getAll($query);
          $data['phone_list']=$rs;
          $query="select count(*) from ".$this->common->table('phone').$where;
          $all_count=$this->common->getOne($query);
          $this->load->library('pagination');
         
          $config['base_url'] = '?c=phone&m=index&date='.$data['start'].'+-+'.$data['end'].'&phone='.$data['phone'].'&mark_content='.$data['mark_content'].'&zx_name='.$zx_name;
          $config['total_rows'] = $all_count;
          $config['per_page'] = 30;

          $this->pagination->initialize($config);

          $page=$this->pagination->create_links();
          
          $data['page']=$page;
          
          
          
          
          $data['top'] = $this->load->view('top', $data, true);
          $data['themes_color_select'] = $this->load->view('themes_color_select', '', true);
          $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
          $this->load->view('get_phone/get_phone', $data);
           
           
           
       } 
        
       public function mark_add_ajax(){
           $phone_id=isset($_REQUEST['phone_id'])?intval($_REQUEST['phone_id']):0;
           $mark_content=isset($_REQUEST['mark_content'])?trim($_REQUEST['mark_content']):'';
           $admin_id=isset($_COOKIE['l_admin_id'])?intval($_COOKIE['l_admin_id']):0;
           $admin_name=isset($_COOKIE['l_admin_name'])?trim($_COOKIE['l_admin_name']):0;
           $mark_time=time();
           $arr=array(
               'phone_id'=>$phone_id,
               'mark_content'=>$mark_content,
               'admin_id'=>$admin_id,
               'admin_name'=>$admin_name,
               'mark_time'=>$mark_time
                       
               
           );
           
           $rs=$this->db->insert($this->common->table('phone_mark'),$arr);
           if($rs){
               echo "<blockquote><p style='color:red;width:180px;font-size:14px;'>".$mark_content."</p><small>".$admin_name."&nbsp;".date('m-d H:i',$mark_time)."</small></blockquote>";
           }
           
       }
       
       
       
       
       
       
	public function mark_list_ajax()

	{

		$phone_id = isset($_REQUEST['phone_id'])? trim($_REQUEST['phone_id']):'';

		$phone_id = substr($phone_id, 0, -1);

		$sql = "SELECT * FROM " . $this->common->table('phone_mark') . " WHERE phone_id IN (".$phone_id.") ORDER BY phone_id DESC,mark_time DESC";

		$row = $this->common->getAll($sql);

		$arr = array();

		foreach($row as $val)

		{

			$arr[$val['mark_id']] = $val;//主意键值必须唯一，不能重复，不然只能获取一条数据

			$arr[$val['mark_id']]['mark_time'] = date("m-d H:i", $val['mark_time']);

		}

		echo json_encode($arr);

	}
       
         public function update_status_ajax(){
             $status=isset($_REQUEST['status'])?intval($_REQUEST['status']):0;
             $phone_id=isset($_REQUEST['phone_id'])?intval($_REQUEST['phone_id']):0;
             if($status==1){
                 $this->db->where('phone_id', $phone_id);
                 $this->db->update($this->common->table('phone'),array('status'=>1));
             }elseif($status==0){
                 $this->db->where('phone_id', $phone_id);
                 $this->db->update($this->common->table('phone'),array('status'=>0));
             }
             echo 'yes';
         }
       
       
       
}

?>