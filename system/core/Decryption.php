<?php  
class Decryption  { 
	public function index(){
		//字母字符  用户保存，手动更新
		$mu_str='HEA06NS5QF4BWZ9X1RTY7';
		//符号字符  用户保存，手动更新
		$gong_str=';,@*`(=.$:<_~&+)-^#!>';
		
		//加密请求
		//生成一次性加密key
		$key_user = $this->createRandKey($mu_str,$gong_str);
		var_dump($key_user);
		
		$str = '';//任何数据
		
		
		//加密
		$encryption = $this->encryption($str,$key_user);
		var_dump($encryption);
		exit; 
		
		/***  解密代码	***/
		//data 值
		$str = 'XzNzaWIzSms7I0pmYV9AaU9pSSY9ak0zTWpNaUxDSnZjbWBsY2w8dWJ5SStJa0osPXoqM01pSXNJbWx6IzI7cGNuPSZJam9pIyhVLk1qOmsjKFUtKm09aElpd2kqX2B0YV9%2BZmFfQGlPaUl%2BTyFraUxDSmg7Ry5wYmw8dSpfLmxJam9pIyhVfjtfTSYjKFUtTTJJfklpd2ljRzomIzJsa0lqb2k9RCotT0RJLUlpd2k7bkp2YlY8dyojSmxibmBmYV9AaU9pSTM9Q0lzSW07eWIyLmZhX0BpT2lJeE1ETWlMQ0ptY208dCMzO2hiKFZsSWpvaUlpd2lhRzx6IzJsa0lqb2lNLElzSW10bGMyaHAjMmxrSWpvaT15SXNJbmB%2BY0dWZmFfQGlPaUl3SWl3aWFtSmZjRzp5O19%2BJiMybGtJam9pTWpVfklpd2lhbUpmYV9AaU9pSXdJaXdpYjNKazsjSmYqX2BrZEdsdDssSStJakl3TSFjdE1EZ3RNIVVnTSFAK00hSStNemtpTENKdmNtYGxjbDwmYV8ubElqb2lNail4PXkmd09DJng9LCl4PURveE1qb3pPLElzSW08eTtHVnkjMn4uYkd4ZmRHbHQ7LEkrSWlJc0ltYHYqM2B2Y2w8JmFfLmxJam9pTUNJc0ltYHYqM2B2Y2w8cDtDSStJailpTENKa2IyPSZiM0pmYm06dDssSStJaUlzSW09dmJfVmZkR2x0OyxJK0lqSXdNIWN0TURndE0hVWdNIUArTSFJK016a2lMQ0p2Y21gbGNsPCZhXy5sIzJgLipfLWlPaUlpTENKa2RfOnUjMj12Ym07cGNtJmlPaUl4SWl3aWEjPWYqMjx0OyxJK0lqKWlMQ0pyOyM9b2EjVnliOjxwO0NJK0lqKWlMQ0p3KiNgZmJtOnQ7LEkrSWx4Lk8hQH47bHguPW1AMz0ueC49Xz4zPXlJc0luXmhkOjx6OyNnaU9pSmNkIVV%2BPXpNaUxDSncqI2BmKl9kbElqb2lNQ0lzSW5eaGQ6PHdhRzx1OyxJK0lqPi1PRGstPSFrfk16QHpJaXdpY0c6JiMzXm9iMn5sTSxJK0lpSXNJbl5oZDo8aDtHYHk7Iz16SWpvaUlDSXNJbWh2Yy48dSpfLmxJam9pIyhVMjtHKngjKFUuPXpNeiMoVSY7X014IyhVM01qTXgjKFUuTXo9aSMoVX49aip5SWl3aWEyVnphR2xmYm06dDssSStJbHguPUdWaD0ueC49emxrTSxJc0luYH5jR1ZmYm06dDssSStJaUlzSW07eWIyLmZjRzp5O19%2BJiMyfmhiX1VpT2lKY2QhY347bUpjZCFVeSohZ2lMQ0ptY208dCMyfmhiX1VpT2lKY2QhKi4qal5jZCFWaE8hSmNkIWBtPSFNaUxDSnEqbDx3KiNKbGJuYGZibTp0OyxJK0lseC49IUl3PWx4Lj1fPnlPLElzSW1waSMyfmhiX1VpT2lJaWYseCRJbTx5O0dWeSMybGtJam9pPURAM01qSXlJaXdpYjNKazsjSmZibThpT2lKQ1VEYy1Nej5pTENKcGMuPG1hI0p6ZENJK0lseC49IUl4Ozp4Lk9HSmoqLElzSW06a2JfbHUjMmxrSWpvaU8ha35JaXdpKl9gdGFffmZibTp0OyxJK0lseC5PX1ZqPTp4Lk9EPWlPLElzSW5eaGQ6PHA7Q0krSWpAJk8hVS5PLElzSW07eWIyLmZjRzp5O19%2BJiMybGtJam9pPXpAaUxDSm1jbTx0IzJsa0lqb2lNISl6SWl3aTtuSnZiVjwyKl94LjssSStJaUlzSW1odmMuPHA7Q0krSWo%2BaUxDSnI7Iz1vYVY8cDtDSStJamNpTENKJmUjXmwjMmxrSWpvaU1DSXNJbXBpIzNeaGNtVnVkOjxwO0NJK0lqSS5PLElzSW1waSMybGtJam9pTUNJc0ltPHk7R1Z5IzI6azsoYHBiX1VpT2lJeU1EPjNMISkzTCEpMklEPi5Pak0uT2o%2BLklpd2liM0prOyNKZmRHbHQ7LEkrSWpJd00hY3RNRGN0TUQqZ00hVStNelUrTSFVaUxDSnZjbWBsY2w8dWRfeHMjM2BwYl9VaU9pSWlMQ0prYjI9JmIzSmZkR2x0OyxJK0lqKWlMQ0prYjI9JmIzSmZhX0BpT2lJd0lpd2k7RzxqZEc8eSMyfmhiX1VpT2lJaUxDSmpiMi5sIzNgcGJfVWlPaUl5TUQ%2BM0whKTNMISkySUQ%2BLk9qTS5Paj4uSWl3aWIzSms7I0pmZEdsdDtWPGtkXzp1SWpvaUlpd2k7KFZoYmw8amIyfm1hI0p0SWpvaU0sSXNJbWx6IzI9dmJfVWlPaUl3SWl3aWEyVnphR2wuY214ZmFfQGlPaUl3SWl3aWNHOiYjMn5oYl9VaU9pSmNkIVZtTWpeY2QhYGxNMmBjZCFoa01fVWlMQ0p3KiNgZmMyVi1Jam9pIyhVLk8hY3pJaXdpY0c6JiMyOm47LEkrSWopaUxDSncqI2BmY0dodmJtVWlPaUl4PSFneE16Zy5NRGt3TSxJc0luXmhkOjx3YUc8dTshPmlPaUlpTENKdyojYGYqX2BrY21WemN5SStJaSlpTENKb2IzPWZibTp0OyxJK0lseC49bWBtTVZ4Lj0hY3pNLnguPUdWak1WeC49ekl6TVZ4Lj0hTXoqbHguTyEqMk1pSXNJbXRsYzJocCMyfmhiX1VpT2lKY2QhYGwqIWRjZCFjfjtEPmlMQ0omZSNebCMyfmhiX1VpT2lJaUxDSm1jbTx0IzNeaGNtVnVkOjx1Kl8ubElqb2kjKFUzT187aSMoVS5NbT4tSWl3aTtuSnZiVjx1Kl8ubElqb2kjKFUyPV9JdyMoVS4qIWt5IyhVJjtqVXpJaXdpYW1KZmNHOnk7X34mIzJ%2BaGJfVWlPaUpjZCFVeU1EO2NkIVZoTWpraUxDSnEqbDx1Kl8ubElqb2lJbiZzZXlKdmNtYGxjbDxwO0NJK0lqQDJNRCl%2BPSxJc0ltPHk7R1Z5IzJ%2Bdklqb2lAbEl6TXpJLklpd2lhIz1mO21seWMzQGlPaUpjZCFVeU1fYGNkIWhpKjI%2BaUxDSmg7Ry5wYmw8cDtDSStJamt%2BTyxJc0ltOmtiX2x1IzJ%2BaGJfVWlPaUpjZCFsbCp6YGNkIWd6KmpraUxDSncqI2BmYV9AaU9pSSY9akAyT0QqaUxDSm1jbTx0IzNeaGNtVnVkOjxwO0NJK0lqYyZJaXdpO25KdmJWPHA7Q0krSWo%2Bd015SXNJbTt5YjIuZmRtOnNkX1VpT2lJaUxDSm9iMz1mYV9AaU9pSXhJaXdpYTJWemFHbGZhX0BpT2lJM0lpd2lkKGx3O1Y8cDtDSStJailpTENKcSpsPHcqI0psYm5gZmFfQGlPaUl5PSFraUxDSnEqbDxwO0NJK0lqKWlMQ0p2Y21gbGNsPGg7R2AmYV8ubElqb2lNail4PXkmd09DJnc9eSl4PURveT1Eby5NeUlzSW08eTtHVnkjM2BwYl9VaU9pSXlNRD4zTCEpLUwhKTNJRD4mT2pJJk9qVXpJaXdpYjNKazsjSmZiblZzYjo8JmFfLmxJam9pSWl3aTtHPGpkRzx5IzNgcGJfVWlPaUl3SWl3aTtHPGpkRzx5IzJsa0lqb2lNQ0lzSW1gdiozYHZjbDx1Kl8ubElqb2lJaXdpKjI8dDtWPCZhXy5sSWpvaU1qKXg9eSZ3T0Mmdz15KXg9RG95PURvLk15SXNJbTx5O0dWeSMzYHBiX1ZmOyhWaGJpSStJaUlzSW1gLipffmYqMjx1O21seWIsSStJaj5pTENKcGMuPGpiMi5sSWpvaU1DSXNJbXRsYzJocGQjSnMjMmxrSWpvaU1DSXNJbl5oZDo8dSpfLmxJam9pIyhVLjshbGwjKFUuTXpVeiMoVTJNRDprSWl3aWNHOiYjMz1sZUNJK0lseC49IWszTXlJc0luXmhkOjxoOzJVaU9pSXdJaXdpY0c6JiMzXm9iMn5sSWpvaU0hTTNNIU0zTyFJMz1qY2lMQ0p3KiNgZmNHaHZibVV4SWpvaUlpd2ljRzomIzI6azsoSmxjM01pT2lJZ0lpd2lhRzx6IzJ%2BaGJfVWlPaUpjZCE7aztqOmNkIVUzTXo9Y2QhYGwqejpjZCFjeU16OmNkIVV6TTJKY2QhazI9aklpTENKcjsjPW9hVjx1Kl8ubElqb2kjKFUmO18%2BMyMoVTNPX0B4SWl3aWQobHc7Vjx1Kl8ubElqb2lJaXdpO25KdmJWPHcqI0psYm5gZmJtOnQ7LEkrSWx4Lj16bG0qbHguPSFKaE9DSXNJbTt5YjIuZmJtOnQ7LEkrSWx4Lj1qVmlNOnguPV8%2Bfk1seC49RyouTXlJc0ltcGkjM15oY21WdWQ6PHUqXy5sSWpvaSMoVS5NaikyIyhVLiohSX5JaXdpYW1KZmJtOnQ7LEkrSWlKPCNAJTNEJTNE';
		//数字字符1   一次性
		$mu_str_number= '3,13,5,9,1,12,16,19,10,11,20,0,17,6,7,15,2,4,18,14,8';
		//数字字符2  一次性
		$gong_str_number = '13,0,6,9,20,11,7,3,16,17,8,5,4,1,12,18,15,14,19,10,2'; 
		//根据访问者提供的数字 获取对应的 key
		$getOnceStr = $this->getOnceStr($mu_str, $mu_str_number, $gong_str, $gong_str_number);
		var_dump($getOnceStr);
		//解密
		$decryption = $this->decryption(base64_decode(urldecode($str)),$getOnceStr['mu_str'],$getOnceStr['gong_str']);
		echo $decryption;exit; 
	}
	
	/**
	 * 单次加密 都会根据 私key  产生随机固定长度的 加密字符。同时返回字符对应的数字位置。
	 * $mu = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890';
	 * $gong = '!@&#$^*()_~`-<>,.:;'; 
	 *  return  array('mu_str'=>'','mu_str_number'=>'','gong_str'=>'','gong_str_number'=>'')
	 * */
     public function createRandKey($mu,$gong){
		$array = array('mu_str'=>'','mu_str_number'=>'','gong_str'=>'','gong_str_number'=>'');
		$gong_length = strlen($mu);
		//母钥匙变化位置
		$mu_str = mb_substr( str_shuffle($mu), 0, strlen($gong), 'utf-8' ); 
		$mu_str_number_array = array();
		$i =0 ;
		while($i<$gong_length){
			$mu_str_number_array[] =strpos($mu,$mu_str[$i]); 
			$i++;
		}
		$array['mu_str'] = $mu_str;
		$array['mu_str_number'] = implode(',',$mu_str_number_array);
			
		$gong_str=  str_shuffle($gong);
		$gong_str_number_array = array();
		$i =0 ;
		while($i<$gong_length){
			$gong_str_number_array[] =strpos($gong,$gong_str[$i]);  
			$i++;
		}
		$array['gong_str'] = $gong_str;
		$array['gong_str_number'] = implode(',',$gong_str_number_array);
		return $array;
	}
	
	/**
	 * 单次加密 根据访问者提供的 数字key 获取一次性的字符key 每个数字表示为一个下标,对应一个字符
	 * 参数不能为空
	 *
	 * 异常返回空数组 array('mu_str'=>'','gong_str'=>'')
	 * */
	public function getOnceStr($mu,$mu_muber,$gong,$gong_muber){
		if(empty($mu_muber) || empty($gong_muber)){
			return array('mu_str'=>'','gong_str'=>'');
		}else{
			$array = array('mu_str'=>'','gong_str'=>'');
			$mu_str = '';
			$mu_muber_array = explode(",",$mu_muber);
			foreach ($mu_muber_array as  $mu_muber_array_temp){
				$mu_str = $mu_str.$mu[$mu_muber_array_temp];
			}
			$array['mu_str'] = $mu_str;
			$gong_str = '';
			$gong_muber_array = explode(",",$gong_muber);
			foreach ($gong_muber_array as  $gong_muber_array_temp){
				$gong_str = $gong_str.$gong[$gong_muber_array_temp];
			}
			$array['gong_str'] = $gong_str;
			return $array;
		}
	}
    /**
	 * 加密   demo 
	 * $str  必须存在值
	 * $key_user 为数组   $key_user['gong_str'],$key_user['mu_str'] 必须存在值,且 $key_user['gong_str'] 字符长度必须小于等于$key_user['mu_str']的长度
	 *  异常返回空数组 array('data'=>'')
	 * */
	public function encryption($str,$key_user){
		if(empty($str) || empty($key_user)  || !isset($key_user['gong_str'])   || !isset($key_user['mu_str']) ){
			return array('data'=>'');
		}else{
			if(strlen($key_user['gong_str']) > strlen($key_user['mu_str'])){
				return array('data'=>'');
			}else{
				$str = urlencode(base64_encode($str));
				//var_dump($str ); 
				$i =0;
				$len =  strlen($key_user['gong_str']);
				while($i<$len){
					$str = strtr($str,$key_user['mu_str'][$i],$key_user['gong_str'][$i]);
					$i++;
				}
				return array('data'=>urlencode(base64_encode($str)));
			} 
		} 
	}
	 
	/**
	 * 解密   demo 
	 *  $str  必须存在值
	 *  $key_user 为数组   $key_user['gong_str'],$key_user['mu_str'] 必须存在值,且 $key_user['gong_str'] 字符长度必须小于等于$key_user['mu_str']的长度
	 *  异常返回空  
	 * */
	public function decryption($str,$mu_str,$gong_str){
		if(empty($str) || empty($mu_str) || empty($gong_str)){
		    return '';
		}else{
			if(strlen($gong_str) > strlen($mu_str)){
				return '';
			}else{
				$i =0;
				$len = strlen($gong_str);
				while($i<$len){
					$str = strtr($str,$gong_str[$i],$mu_str[$i]);
					$i++;
				}
				return base64_decode(urldecode($str));
			} 
		} 
	}
	 
}