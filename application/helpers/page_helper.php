<?php
// 系统常用函数

function page_config()
{
	$config['full_tag_open'] = '<div class="custom-pagination pagination-centered"><ul>';
	$config['full_tag_close'] = '</ul></div>';
	$config['first_link'] = '<i class="icon-double-angle-left"></i>';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$config['last_link'] = '<i class="icon-double-angle-right"></i>';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['next_link'] = '<i class="icon-angle-right"></i>';
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';
	$config['prev_link'] = '<i class="icon-angle-left"></i>';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['cur_tag_open'] = '<li>';
	$config['cur_tag_close'] = '</li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	
	return $config;
}

function web_page_config()
{
		$config['full_tag_open'] = '<ul data-am-widget="pagination" class="am-pagination am-pagination-default">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<span class="am-icon-angle-double-left"></span>';
		$config['first_tag_open'] = '<li class="am-pagination-first ">';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '<span class="am-icon-angle-double-right"></span>';
		$config['last_tag_open'] = '<li class="am-pagination-next ">';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = '<span class="am-icon-angle-right"></span>';
		$config['next_tag_open'] = '<li class="am-pagination-last ">';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '<span class="am-icon-angle-left"></span>';
		$config['prev_tag_open'] = '<li class="am-pagination-prev ">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="am-active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';	

		return $config;
}