<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Api接口
 * Author Henry
 * Date 2018-12-13
 */

class ApiLib
{
    private $token;


    private $input;



    public function __construct($params) {

        $CI = & get_instance();

        $this->input = $CI->input;

        $this->token = $params['token'];

    }

    /**
     * 验证身份
     * @return boolean [description]
     */
    public function is_valid($timeStamp,$randomStr,$signature){
        $str = $this -> valid($timeStamp,$randomStr);
        if($str != $signature){
            return false;
        }
        return true;
    }


    /**
     * 验证算法
     * @param $timeStamp 时间戳
     * @param $randomStr 随机字符串
     * @return string 返回签名
     */
    private function valid($timeStamp,$randomStr){
        $arr['timeStamp'] = $timeStamp;
        $arr['randomStr'] = $randomStr;
        $arr['token'] = $this->token;
        //按照首字母大小写顺序排序
        sort($arr,SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密
        $signature = sha1($str);
        $signature = md5($signature);
        //转换成大写
        $signature = strtoupper($signature);
        return $signature;
    }



}

