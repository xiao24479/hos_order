<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sql_model extends CI_Model { //数据库

  public function __construct(){
    parent::__construct();
    $this->load->database();
    $this->load->helper('lx');
  }

  /*
   *公用sql函数
   */
  public function field_one($tbl,$field='*',$where=''){//查询一条数据
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $sql= $this->db->query("SELECT $field FROM $tbl $where LIMIT 1");
    return $sql->row_array();
  }

  public function field_much($tbl,$field='*',$where=''){//查询多条数据
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $sql= $this->db->query("SELECT $field FROM $tbl $where LIMIT 9999");
    return $sql->result_array();
  }

  public function field_page($tbl,$field='*',$page=1,$pagesize=12,$where=''){//按页码查询多条数据
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $start= $page;
    $sql= $this->db->query("SELECT $field FROM $tbl $where LIMIT $start,$pagesize");
    return $sql->result_array();
  }

  public function total_where($tbl,$where=''){//统计总数
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $sql= $this->db->query("SELECT * FROM $tbl $where");
    return $sql->num_rows();
  }

  public function insert_one($tbl,$data){//插入一条数据
    $tbl= get_my_var($tbl);
    $this->db->insert($tbl,$data);
    return $this->db->insert_id();
  }

  public function insert_much($tbl,$data){//插入多条数据
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $str='';
    $field='';
    foreach($data as $k=>$v){
      $field.= $str.'`'.$k.'`';
      foreach($v as $k1=>$v1){
        $val_arr[$k1][]= $v1;
      }
      $str=',';
    }
    $value='';
    $str='';
    foreach($val_arr as $k=>$v){
      $value.= $str.'('.implode(',',$v).')';
      $str=',';
    }
    $resource= $this->db->query("INSERT INTO $tbl($field) values $value");
    return mysql_affected_rows();
  }

  public function update_arr($tbl,$data,$arr){//更新一条数据,arr为一个数组
    $tbl= get_my_var($tbl);
    $resource= $this->db->update($tbl,$data,$arr);
    return mysql_affected_rows();
  }

  public function update_set($tbl,$set){//更新特殊条件的sql，如field=field+1;
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $resource= $this->db->query("UPDATE $tbl SET $set");
    return mysql_affected_rows();
  }

  public function update_much_key($tbl,$data,$key='id'){//更新多条数据
    $tbl= get_my_var($tbl);
    foreach($data as $k=>$val){
      foreach($val as $k2=>$value){
        $row[$k2][$k]= $data[$k][$k2];
      }
    }
    $resource= $this->db->update_batch($tbl,$row,$key);
    return mysql_affected_rows();
  }

  public function delete_where($tbl,$where){//按条件删除一条或多条数据
    $tbl= $this->db->dbprefix.get_my_var($tbl);
    $resource= $this->db->query("DELETE FROM $tbl $where");
    return mysql_affected_rows();
  }

  /*
   *公用sql函数
   */
}
