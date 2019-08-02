<?php
if( ! function_exists('get_my_var')){
function get_my_var($tbl){
  return $tbl = str_replace('tbl_','',$tbl);
}
}

 /**
	* 取得数字ID分段目录
	* 默认取三层
	* 参数 $root_dir, 相对于当前执行脚步的相对起始目录， 包含结尾目录分隔符号 "/"
	* 参数 $len 传入的数字ID最大长度，不足前面补0(补0操作由函数自动完成)
	* 参数 $num_id 传入的数字ID
	* 参数 $size 分段目录层次
	* 返回值：相对当前脚本的数字ID层次目录, 包含结尾目录分隔符号 "/"
*/
if ( ! function_exists('get_file_path')){
		function get_file_path($root_dir, $num_id, $len=8, $size=3){

				if( !file_exists($root_dir))
						return false;

				$cnt = 0;
				$j = 0;
				$s = '';
				$num_arr = array();
				$str = sprintf('%0'.$len.'s', $num_id);
				for($i=0; $i<strlen($str); $i++){
						$cnt++;
						$s .= $str[$i];
						if($cnt == 2){
								$cnt = 0;
								$num_arr[$j++] = $s;
								$s = '';
						}
				}

				$dir = $root_dir;
				foreach($num_arr as $k=>$e){
						if($k==$size)
								break;
						$dir .= $e .'/';
				}

				return $dir;
		}
}

/**
	* 取得数字ID分段目录
	* 默认取三层
	* 参数 $root_dir, 相对于当前执行脚步的相对起始目录， 包含结尾目录分隔符号 "/"
	* 参数 $len 传入的数字ID最大长度，不足前面补0(补0操作由函数自动完成)
	* 参数 $num_dir 传入的数字ID
	* 参数 $size 分段目录层次
	* 参数 $perm 创建的目录权限
	* 返回值：相对当前脚本的数字ID层次目录, 包含结尾目录分隔符号 "/", 创建失败返回 false
*/
if ( ! function_exists('create_file_path')){
		function create_file_path($root_dir, $num_id, $len=8, $size=3, $perm=0701){
				if( ! file_exists($root_dir))
						return false;

				$cnt = 0;
				$j = 0;
				$s = '';
				$num_arr = array();
				$str = sprintf('%0'.$len.'s', $num_id);
				for($i=0; $i<strlen($str); $i++){
						$cnt++;
						$s .= $str[$i];
						if($cnt == 2){
								$cnt = 0;
								$num_arr[$j++] = $s;
								$s = '';
						}
				}
				$oldmask = umask(0);

				$dir = $root_dir;
				if( !is_dir( $dir ) )
						@mkdir($dir, $perm);

				foreach($num_arr as $k=>$e){
						if($k==$size)
								break;
						$dir .= $e . '/';
						if( !is_dir( $dir ) )
								@mkdir($dir, $perm);
				}
				umask($oldmask);

				return $dir;
		}
} 
if ( ! function_exists('fen_miao')){
  function fen_miao($str){
    $arr = explode(':',$str);
    if($arr['0']!=0){
      return $arr['0']*3600+$arr['1']*60+$arr['2'];
    }
    elseif($arr['0']==0 && $arr['1']!=0){
      return $arr['1']*60+$arr['2'];
    }
    else{
      return $arr['2'];
    }
  }
}
if ( ! function_exists('miao_fen')){
  function miao_fen($str){
    $shi = floor($str/3600);
    $fen = floor(($str-$shi*3600)/60);
    $miao = $str-$shi*3600-$fen*60;
    return $shi.':'.$fen.':'.$miao;
  }
}
