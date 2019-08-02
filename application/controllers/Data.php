<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Data extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    function index(){
         echo '非法访问';exit;
       $query=$this->db->query('show tables');
        //$query=$this->db->get('admin');
        //$query=$this->db->get('admin_log');
      $aa=$query->result();
      $bb=$this->db->affected_rows();
      foreach($aa as $cc){
         print_r($cc);
          echo '<br/><br/>';
          
      }
       echo $bb; 
        
        
    }
    
    function test(){
        
        $this->common->test();
        
    }
}