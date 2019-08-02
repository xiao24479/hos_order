<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// 网站数据
class Swt extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->lang->load('swt');
    $this->load->model('sql_model');
	}

  public function item_add(){//添加项目
    $this->top_left('item_add',$data);
    $data['site_list'] = $this->sql_model->field_much('tbl_site','site_id,site_domain');
    $this->load->view('item_add', $data);
  }

  public function item_insert(){//项目插入数据库
    $arr['item_title'] = $this->input->post('item_title');
    if(strpos($arr['item_title'],'|')>-1){
      $row = explode('|',$arr['item_title']);
      $arr['item_old_id'] = $row['0'];
      $arr['item_title'] = $row['1'];
    }
    $arr['item_swt_id'] = $this->input->post('item_swt_id');
    $arr['item_desc'] = $this->input->post('item_desc');
    $insert_id = $this->sql_model->insert_one('tbl_swt_item',$arr);
    if($insert_id>0){
      $links = array(0=>array('href'=>SWT_ROOT.'&m=item_list','text'=>$this->lang->line('back_list')),
        array('href'=>SWT_ROOT.'&m=item_edit&item_id='.$insert_id,'text'=>$this->lang->line('back_edit')),
        array('href'=>SWT_ROOT.'&m=item_add','text'=>$this->lang->line('back_add'))
      );
      $this->common->msg($this->lang->line('swt_success'),0,$links);
    }
    else{
      $this->common->msg($this->lang->line('swt_error'),1);
    }
  }

  public function item_update(){//更新商务通项目
    $item_id = $this->input->get('item_id');
    $arr['item_title'] = $this->input->post('item_title');
    $arr['item_swt_id'] = $this->input->post('item_swt_id');
    $arr['item_desc'] = $this->input->post('item_desc');
    $if_no = $this->sql_model->update_arr('tbl_swt_item',$arr,array('item_id'=>$item_id));
    if($if_no>0){
      $links = array(0=>array('href'=>SWT_ROOT.'&m=item_list','text'=>$this->lang->line('back_list')),
        array('href'=>SWT_ROOT.'&m=item_edit&item_id='.$item_id,'text'=>$this->lang->line('back_edit')),
        array('href'=>SWT_ROOT.'&m=item_add','text'=>$this->lang->line('back_add'))
      );
      $this->common->msg($this->lang->line('swt_success'),0,$links);
    }
    else{
      $this->common->msg($this->lang->line('swt_error'),1);
    }
  }

  public function item_del(){//删除项目
    $item_id = $this->input->get('item_id');
    $if_no = $this->sql_model->delete_where('tbl_swt_item',"WHERE item_id='$item_id' LIMIT 1");
    if($if_no>0){
      $links = array(0=>array('href'=>SWT_ROOT.'&m=item_list','text'=>$this->lang->line('back_list')),
        array('href'=>SWT_ROOT.'&m=item_edit&item_id='.$item_id,'text'=>$this->lang->line('back_edit')),
        array('href'=>SWT_ROOT.'&m=item_add','text'=>$this->lang->line('back_add'))
      );
      $this->common->msg($this->lang->line('swt_success'),0,$links);
    }
    else{
      $this->common->msg($this->lang->line('swt_error'),1);
    }
  }

  public function item_edit(){//编辑项目
    $item_id = $this->input->get('item_id');
    $this->top_left($this->lang->line('item_edit'),$data);
    $data['item_info'] = $this->sql_model->field_one('tbl_swt_item','*',"WHERE item_id='$item_id'");
    $this->load->view('item_edit', $data);
  }

  public function item_list(){//项目列表
    $this->top_left('item_list',$data);
    $data['item_list'] = $this->sql_model->field_much('tbl_swt_item');
    $this->load->view('item_list', $data);
  }

  public function swt_edit(){//编辑商务通
    $swt_id = $this->input->get('swt_id');
    $arr = $this->sql_model->field_one('tbl_swt','*',"WHERE swt_id='$swt_id'");
    $data['html'] = $arr['swt_js'];
    $data['swt_id'] = $swt_id;
    $data['add_js'] ='$("a.swt_a").dblclick(function(){
      var e = $(this).index();
      $(this).css("cursor","se-resize");
      bindResize($(this).closest("div.swt_wrap").find("a.swt_a")[e]);
    });
  $("span.swt_off").dblclick(function(){
    var e = $(this).index();
    $(this).css("cursor","se-resize");
    bindResize($(".swt_off")[e]);
  });
  $("span.swt_num").dblclick(function(){
    var e = $(this).index();
    $(this).css("cursor","se-resize");
    bindResize($(".swt_num")[e]);
  });
  ';
    $this->load->view('swt_view',$data);
  }

  public function upload_img(){//ajax 更新前台商务通图片
    if($_FILES){
    $swt_id = $this->input->post('swt_id');
    $this->load->library(array('upload','my_upload'));
    $file_arr = $this->my_upload->upload_one($swt_id);
    $img_arr = getimagesize($file_arr['file_name']);
    echo '<script type="text/javascript">
var $ = window.parent.$,jQuery = window.parent.jQuery;
$("div.s_red",window.parent.document).css({"background":"url('.TEST_URL.$file_arr['file_name'].')","width":"'.$img_arr['0'].'px","height":"'.$img_arr['1'].'px"});
</script>';
    $img_src = $this->input->post('img_src');
    //删除原有图片
    if(file_exists("$img_src"))
      unlink(trim($img_src));
    }
  }

  public function upload_add(){//ajax 增加商务通图片
    if($_FILES){
    $swt_id = $this->input->post('swt_id');
    $this->load->library(array('upload','my_upload'));
    $file_arr = $this->my_upload->upload_one($swt_id);
    $img_arr = getimagesize($file_arr['file_name']);
    echo '<script type="text/javascript">
var $ = window.parent.$,jQuery = window.parent.jQuery;
$("#js_content",window.parent.document).append(\'<div class="swt_wrap"><div class="swt_cen"><div class="swt_img" style="background:url('.TEST_URL.$file_arr['file_name'].');width:'.$img_arr['0'].'px;height:'.$img_arr['1'].'px;top:0px;left:0px;"></div></div></div>\');
</script>';
    }
  }

  public function upload_del(){//ajax删除商务通图片
    $img_src = $this->input->post('img_src');
    //删除原有图片
    if(file_exists("$img_src"))
      unlink("$img_src");
    echo '<script type="text/javascript">
var $ = window.parent.$,jQuery = window.parent.jQuery;
$("div.s_red",window.parent.document).remove();
$("div.swt_img",window.parent.document).addClass("s_red");
</script>';
  }

  public function swt_a_del(){//ajax删除a标签
    $click_id = $this->input->post('click_id');
    $arr = explode(',',$click_id);
    foreach($arr as $v){
    $status = $this->sql_model->delete_where('tbl_swt_click',"WHERE click_id = '$v' LIMIT 1");
    }
    echo json_encode(array('status'=>$status));
  }

  public function swt_add(){//增加商务c通
    $this->top_left('swt_add',$data);
    $data['item_list'] = $this->sql_model->field_much('tbl_swt_item','item_id,item_title');
    $this->load->view('swt_add',$data);
  }

  public function upload(){//上传商务通图片并显示操作面版
    $arr['swt_width'] = $this->input->post('swt_width');
    $arr['swt_title'] = $this->input->post('swt_title');
    $arr['item_id'] = $this->input->post('item_id');
    $insert_id = $this->sql_model->insert_one('tbl_swt',$arr);
    if(empty($insert_id)){
      $this->common->msg('插入商务通标题失败');
    }
    $this->load->library(array('upload','my_upload'));
    $file_arr = $this->my_upload->upload_much($insert_id);
    if($file_arr['status'] ==1){
      $this->common->msg('图片上传不成功');
    }
    $shuju['swt_img'] = 'array(';
    foreach($file_arr['file'] as $k=>$v){
      if($k==0)
        $shuju['swt_img'] .= "'".$v."'";
      else
        $shuju['swt_img'] .= ",'".$v."'";
    }
    $shuju['swt_img'] .= ')';
    $this->sql_model->update_arr('tbl_swt',$shuju,array('swt_id'=>$insert_id));
    $data['html'] = '';
    foreach($file_arr['file'] as $k=>$v){
    $img_arr = getimagesize($v);
    $red = $k==0?' s_red':'';
    $data['swt_id'] = $insert_id;
    $data['html'] .= '<div class="swt_wrap"><div class="swt_cen"><div class="swt_img'.$red.'" style="background:url('.TEST_URL.$v.');width:'.$img_arr['0'].'px;height:'.$img_arr['1'].'px;top:0px;left:0px;"></div></div></div>';
    $data['file_num'] = count($file_arr['file']);
    }
    $this->load->view('swt_view', $data);
  }

  public function swt_ins(){//ajax a标签插入数据据
    $arr['click_data'] = explode(',',$this->input->post('data'));
    $num =count($arr['click_data']);
    $swt_id = $this->input->post('swt_id');
    $this->sql_model->delete_where('tbl_swt_click',"WHERE swt_id = $swt_id");
    for($i=0;$i<$num;$i++){
      $arr['swt_id'][] = $swt_id;
    }
    $link = $this->input->post('link');
    $link = str_replace('##','&',$link);
    $arr['click_link'] = explode(',',$link);
    $this->sql_model->insert_much('tbl_swt_click',$arr);
    if($click_id = $this->db->insert_id()){
      echo json_encode(array('click_id'=>$click_id,'num'=>$num));
    }
    else{
      echo json_encode(array('click_id'=>0));
    }
  }

  public function swt_js(){//预览时生成js文件及保存到数据库
    $data['swt_js'] = $this->input->post('swt_js');
    $arr['swt_id'] = $this->input->post('swt_id');
    $this->sql_model->update_arr('tbl_swt',$data,$arr);
    $filename="./js/".$arr['swt_id'].".js";
    $fp=fopen("$filename", "w+"); //打开文件指针，创建文件
    if ( !is_writable($filename) ){
      die('文件:' .$filename. '不可写，请检查！');
    }
    $dian = trim(str_replace("\"","'",$data['swt_js']));
    $dian = str_replace('##','&',$dian);
    $dian = str_replace("href='javascript:;' ",'',$dian);//去掉href属性
    $dian = str_replace("from='","href='",$dian);//替换成href属性
    $dian = str_replace("target='_parent","target='_blank",$dian);
    $dian = htmlspecialchars_decode($dian);
    $html = '$(function(){var html="<style type=\'text/css\'>.swt_a{width:39px;height:25px;position:absolute;left:0;top:0;cursor:pointer;display:inline-block}\n\
.swt_off,.swt_num{cursor:pointer;width:15px;height:15px;overflow:hidden;display:block;left:0;top:0;position:absolute}\n\
.swt_wrap{position:fixed;_position:absolute;left:0;top:0;_top:expression(documentElement.scrollTop);z-index:90;display:block;width:100%}\n\
.swt_top{top:0;_top:expression(documentElement.scrollTop)}\n\
.swt_bottom{bottom:0;_bottom:auto;_top:expression(eval(documentElement.scrollTop+documentElement.clientHeight+this.offsetHeight))}\n\
.swt_left_cen .swt_cen{width:1000px;margin:0 auto;height:0;display:block;position:relative}\n\
.swt_left_cen .swt_img{left:0}\n\
.swt_right_cen .swt_cen{width:1000px;margin:0 auto;height:0;display:block;position:relative}\n\
.swt_img{position:absolute}\n\
.swt_h{display:none}\n\
</style>\n\\'.$dian.'"
$("body").append(html);
$("span.swt_off").click(function(){//点击关闭弹窗
  var obj = $(this).closest("div.swt_wrap");
  obj.fadeOut();
  var time2 = obj.attr("time2")*1000;
  if(time2>0){//是否第二次自动弹出
    obj.delay(time2).fadeIn();
    obj.attr("time2","");
  }
});
if($("span.swt_num").length>0){
  $("span.swt_num").each(function(){
    var big = $(this).attr("big")*1;
    var small = $(this).attr("small")*1;
    var num = small+Math.round(Math.random()*(big-small));
    $(this).text(num);
  })
}
$("div.swt_wrap").each(function(){
  var time1 = $(this).attr("time1")*1000;
  if(time1>0){
    $(this).delay(time1).fadeIn();
  }
});
});';
    fwrite($fp, $html);
    fclose($fp);
    echo json_encode(array('data'=>1));
  }

  public function swt_data_list(){//导入的数据列表
    $this->top_left('swt_click_list',$data);
    $data['data_list'] = $this->sql_model->field_much('tbl_swt_data','*','ORDER BY data_id DESC');
    $this->load->view('swt_data_list', $data);
  }

  public function swt_click(){//ajax 记录a标签的点击数量
    $from = $this->input->get('from');
    $r = $this->input->get('r');
    $p = $this->input->get('p');
    $click_id = $this->input->get('click_id');
    $tbl = $this->db->dbprefix.'swt_click';
    $sql = $this->db->query("UPDATE $tbl SET click_num = click_num+1 WHERE click_id = '$click_id'");
  if(strpos($from,'?')>-1)
      header('location:'.$from.'&r='.$r.'&p='.$p);
    else
      header('location:'.$from.'?r='.$r.'&p='.$p);
  }

  public function swt_click_list(){//a标签列表页
    $page = !empty($_GET['per_page'])?$this->input->get('per_page'):0;
    $this->top_left('swt_click_list',$data);
    $data['swt_list'] = $this->sql_model->field_much('tbl_swt','swt_title,swt_id');
    $this->load->library('pagination');
    $config['base_url'] = SITE_URL.'?c=swt&m=swt_click_list';
    $config['total_rows'] = $this->sql_model->total_where('tbl_swt_click');
    $config['first_link'] = '首页';
    $config['prev_link'] = '上一页';
    $config['next_link'] = '下一页';
    $config['last_link'] = '尾页';
    $config['per_page'] = '20';
    $this->pagination->initialize($config);
    $data['pages'] = $this->pagination->create_links();
    $data['click_list'] = $this->sql_model->field_page('tbl_swt_click','*',$page,$config['per_page'],'ORDER BY click_id DESC');
    $this->load->view('swt_click_list', $data);
  }

  public function swt_list(){//商务通列表页
    $page = !empty($_GET['per_page'])?$this->input->get('per_page'):0;
    $this->top_left('swt_list',$data);
    //$data['swt_list'] = $this->sql_model->field_much('tbl_swt','*',"ORDER BY swt_id DESC");
    $this->load->library('pagination');
    $config['base_url'] = SITE_URL.'?c=swt&m=swt_list';
    $config['total_rows'] = $this->sql_model->total_where('tbl_swt');
    $config['first_link'] = '首页';
    $config['prev_link'] = '上一页';
    $config['next_link'] = '下一页';
    $config['last_link'] = '尾页';
    $config['per_page'] = '20';
    $this->pagination->initialize($config);
    $data['pages'] = $this->pagination->create_links();
    $data['swt_list'] = $this->sql_model->field_page('tbl_swt','*',$page,$config['per_page'],'ORDER BY swt_id DESC');
    $data['item_list'] = $this->sql_model->field_much('tbl_swt_item','item_id,item_title');
    $this->load->view('swt_list', $data);
  }

  public function swt_del(){//删除商务通
    $swt_id = $this->input->get('swt_id');
    $arr = $this->sql_model->field_one('tbl_swt','swt_js',"WHERE swt_id ='$swt_id'");
    //eval("\$row =$arr[swt_img];");
    $pattern ='#http+(.*)+(jpg|gif|png)#U';//[(/*)+.+(*/)]//"|http:\/\/(.*)?+(gif|png|jpg)|";
    preg_match_all($pattern,$arr['swt_js'],$row);
    foreach($row['0'] as $v){
      //$v = str_replace(')','',$v);
      $v = str_replace(SITE_URL,'',$v);
      if(file_exists($v))
        unlink($v);
    }
    $if_no1 = $this->sql_model->delete_where('tbl_swt_click',"WHERE swt_id='$swt_id' LIMIT 555");
    $if_no = $this->sql_model->delete_where('tbl_swt',"WHERE swt_id='$swt_id' LIMIT 1");
    if($if_no>0){
      if(file_exists('js/'.$swt_id.'.js'))
        unlink('js/'.$swt_id.'.js');
      $links = array(0=>array('href'=>'/?c=swt&m=swt_list','text'=>$this->lang->line('back_list')),
        array('href'=>'/?c=swt&m=swt_edit&item_id='.$swt_id,'text'=>$this->lang->line('back_edit')),
        array('href'=>'/?c=swt&m=swt_add','text'=>$this->lang->line('back_add'))
      );
      $this->common->msg($this->lang->line('swt_success'),0,$links);
    }
    else{
      $this->common->msg($this->lang->line('swt_error'),1);
    }
  }

  public function swt_data_add(){//导入csv数据
    $this->top_left('swt_data_add',$data);
    $this->load->view('swt_data_add', $data);
  }

  public function swt_data(){//csv文件中的数据插入数据库
   $file_date =fopen( $_FILES['userfile']['tmp_name'],'r');
   $k=0;
   while($row = fgetcsv($file_date)){
     if($k>2){
       $data['data_card_id'][] = $row['18']!=''?$row['18']."'":0;
       $data['data_view_time'][] = $row['1']!=''?"'".strtotime($row['1'])."'":0;
       $data['data_talk_time'][] = $row['2']!=''?"'".strtotime($row['2'])."'":0;
       $data['data_long_time'][] = $row['3']!=''?"'".fen_miao($row['3'])."'":0;
       $data['data_desc'][] = $row['23']!=''?"'".$row['23']."'":0;
     }
     $k++;
   }
   $status = $this->sql_model->insert_much('tbl_swt_data',$data);
   if($status>0){
     $links = array(0=>array('href'=>'/?c=swt&m=swt_list','text'=>$this->lang->line('back_list')),
       array('href'=>'/?c=swt&m=swt_add','text'=>$this->lang->line('back_add'))
     );
     $this->common->msg($this->lang->line('swt_success'),0,$links);
   }
   else{
     $this->common->msg($this->lang->line('swt_error'),1);
   }
	}

  public function swt_html(){//供预览用的html模板
    $data['swt_id'] = $this->input->get('swt_id');
    $this->load->view('swt_html',$data);
  }

  public function swt_search(){
    $key = $this->input->post('key');
    $this->top_left('swt_list',$data);
    $data['item_list'] = $this->sql_model->field_much('tbl_swt_item');
    if($this->input->post('catid')==1){
      $data['swt_list'] = $this->sql_model->field_much('tbl_swt','*',"WHERE swt_title LIKE '%$key%'");
    }
    else{
      $sql = $this->db->query("SELECT item_id FROM hui_swt_item WHERE item_title LIKE '%$key%'");
      foreach ($sql->result_array() as $row){
        $arr[] = $row['item_id'];
      }
      if(!empty($arr)){
        $item_id_str = implode(',',$arr);
        $data['swt_list'] = $this->sql_model->field_much('tbl_swt','*',"WHERE item_id IN ($item_id_str)");
      }else{
        $data['swt_list'] = array();
      }
    }
    $this->load->view('swt_list',$data);
  }

  public function swt_field(){
    $swt_id = $this->input->post('swt_id');
    $data['swt_title'] = $this->input->post('swt_title');
    $status = $this->sql_model->update_arr('tbl_swt', $data, array('swt_id'=>$swt_id));
    echo json_encode(array('status'=>$status));
  }

  private function top_left($current,&$data){//公用的头部和左边文件
    $data = array();
		$data = $this->common->config($current);
		$data['top'] = $this->load->view('top', $data, true);
		$data['themes_color_select'] = $this->load->view('themes_color_select',$data, true);
    $data['sider_menu'] = $this->load->view('sider_menu', $data, true);
  }

}
