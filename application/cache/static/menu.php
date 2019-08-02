<?php
$data = array (
  0 => 
  array (
    'act_id' => '19',
    'act_name' => '系统首页',
    'act_action' => 'index',
    'parent_id' => '0',
    'act_order' => '0',
    'act_url' => '?c=index&m=index',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '155',
        'act_name' => '首页敏感信息',
        'act_action' => 'current_month_data',
        'parent_id' => '19',
        'act_order' => '1',
        'act_url' => '?c=index&m=index',
        'is_show' => '0',
      ),
    ),
  ),
  1 => 
  array (
    'act_id' => '200',
    'act_name' => '泌尿外科首页',
    'act_action' => 'index_nanke',
    'parent_id' => '0',
    'act_order' => '1',
    'act_url' => '?c=index&m=index_nanke',
    'is_show' => '1',
  ),
  2 => 
  array (
    'act_id' => '203',
    'act_name' => '肛肠首页',
    'act_action' => 'index_gc',
    'parent_id' => '0',
    'act_order' => '1',
    'act_url' => '?c=index&m=index_gc',
    'is_show' => '1',
  ),
  3 => 
  array (
    'act_id' => '201',
    'act_name' => '妇科首页',
    'act_action' => 'index_fuke',
    'parent_id' => '0',
    'act_order' => '1',
    'act_url' => '?c=index&m=index_fuke',
    'is_show' => '1',
  ),
  4 => 
  array (
    'act_id' => '1',
    'act_name' => '系统管理',
    'act_action' => 'system',
    'parent_id' => '0',
    'act_order' => '1',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '174',
        'act_name' => '宝宝游泳排期管理',
        'act_action' => 'baby',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=baby',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '175',
        'act_name' => '宝宝游泳排期修改',
        'act_action' => 'baby_edit',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=baby_info&id={$id}',
        'is_show' => '0',
      ),
      2 => 
      array (
        'act_id' => '176',
        'act_name' => '宝宝游泳排期添加',
        'act_action' => 'baby_add',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=baby_info',
        'is_show' => '1',
      ),
      3 => 
      array (
        'act_id' => '177',
        'act_name' => '宝宝游泳排期报表查询',
        'act_action' => 'baby_repost',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=order&m=baby_show_window',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '132',
        'act_name' => '医生排班',
        'act_action' => 'book_list',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=book_list',
        'is_show' => '1',
      ),
      5 => 
      array (
        'act_id' => '114',
        'act_name' => '编辑医生',
        'act_action' => 'docter_edit',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=docter_info&doc_id={$doc_id}',
        'is_show' => '0',
      ),
      6 => 
      array (
        'act_id' => '115',
        'act_name' => '医生管理',
        'act_action' => 'docter',
        'parent_id' => '1',
        'act_order' => '0',
        'act_url' => '?c=system&m=docter',
        'is_show' => '1',
      ),
      7 => 
      array (
        'act_id' => '145',
        'act_name' => '手机号获取',
        'act_action' => 'get_phone',
        'parent_id' => '1',
        'act_order' => '1',
        'act_url' => '?c=phone&m=index',
        'is_show' => '1',
      ),
      8 => 
      array (
        'act_id' => '8',
        'act_name' => '医院信息',
        'act_action' => 'hospital',
        'parent_id' => '1',
        'act_order' => '1',
        'act_url' => '?c=system&m=hospital',
        'is_show' => '1',
      ),
      9 => 
      array (
        'act_id' => '42',
        'act_name' => '添加医院',
        'act_action' => 'hospital_add',
        'parent_id' => '1',
        'act_order' => '2',
        'act_url' => '?c=system&m=hospital_info',
        'is_show' => '1',
      ),
      10 => 
      array (
        'act_id' => '11',
        'act_name' => '操作列表',
        'act_action' => 'admin_action',
        'parent_id' => '1',
        'act_order' => '3',
        'act_url' => '?c=index&m=admin_action',
        'is_show' => '1',
      ),
      11 => 
      array (
        'act_id' => '44',
        'act_name' => '添加科室',
        'act_action' => 'keshi_add',
        'parent_id' => '1',
        'act_order' => '3',
        'act_url' => '?c=system&m=keshi_info',
        'is_show' => '1',
      ),
      12 => 
      array (
        'act_id' => '7',
        'act_name' => '病种管理',
        'act_action' => 'jibing',
        'parent_id' => '1',
        'act_order' => '4',
        'act_url' => '?c=system&m=jibing',
        'is_show' => '1',
      ),
      13 => 
      array (
        'act_id' => '58',
        'act_name' => 'google统计',
        'act_action' => 'google_analytics',
        'parent_id' => '1',
        'act_order' => '10',
        'act_url' => '?c=system&m=google_analytics',
        'is_show' => '1',
      ),
      14 => 
      array (
        'act_id' => '71',
        'act_name' => '短信模板',
        'act_action' => 'sms_themes',
        'parent_id' => '1',
        'act_order' => '12',
        'act_url' => '?c=system&m=sms_themes',
        'is_show' => '1',
      ),
      15 => 
      array (
        'act_id' => '72',
        'act_name' => '短信发送日志',
        'act_action' => 'sms_send_log',
        'parent_id' => '1',
        'act_order' => '13',
        'act_url' => '?c=system&m=sms_send_log',
        'is_show' => '1',
      ),
      16 => 
      array (
        'act_id' => '73',
        'act_name' => '手动发送',
        'act_action' => 'sms_action',
        'parent_id' => '1',
        'act_order' => '14',
        'act_url' => '?c=system&m=sms_action',
        'is_show' => '1',
      ),
      17 => 
      array (
        'act_id' => '74',
        'act_name' => '编辑短信模板',
        'act_action' => 'sms_themes_edit',
        'parent_id' => '1',
        'act_order' => '50',
        'act_url' => '?c=index&m=sms_themes_edit&id={$Id}',
        'is_show' => '0',
      ),
      18 => 
      array (
        'act_id' => '43',
        'act_name' => '编辑医院信息',
        'act_action' => 'hospital_edit',
        'parent_id' => '1',
        'act_order' => '50',
        'act_url' => '?c=system&m=hospital_info&hos_id={$hos_id}',
        'is_show' => '0',
      ),
      19 => 
      array (
        'act_id' => '144',
        'act_name' => '短信群发',
        'act_action' => 'sms_send_all',
        'parent_id' => '1',
        'act_order' => '50',
        'act_url' => '?c=order&m=sms_send_all',
        'is_show' => '1',
      ),
      20 => 
      array (
        'act_id' => '75',
        'act_name' => '添加短信模板',
        'act_action' => 'sms_themes_add',
        'parent_id' => '1',
        'act_order' => '50',
        'act_url' => '?c=system&m=sms_themes_add',
        'is_show' => '0',
      ),
      21 => 
      array (
        'act_id' => '45',
        'act_name' => '编辑科室信息',
        'act_action' => 'keshi_edit',
        'parent_id' => '1',
        'act_order' => '52',
        'act_url' => '?c=system&m=keshi_info&keshi_id={$keshi_id}',
        'is_show' => '0',
      ),
      22 => 
      array (
        'act_id' => '46',
        'act_name' => '添加病种',
        'act_action' => 'jibing_add',
        'parent_id' => '1',
        'act_order' => '53',
        'act_url' => '?c=system&m=jibing_info',
        'is_show' => '0',
      ),
      23 => 
      array (
        'act_id' => '47',
        'act_name' => '编辑病种信息',
        'act_action' => 'jibing_edit',
        'parent_id' => '1',
        'act_order' => '54',
        'act_url' => '?c=system&m=jibing_info&jb_id={$jb_id}',
        'is_show' => '0',
      ),
      24 => 
      array (
        'act_id' => '113',
        'act_name' => '添加医生',
        'act_action' => 'docter_add',
        'parent_id' => '1',
        'act_order' => '101',
        'act_url' => '?c=system&m=docter_info',
        'is_show' => '0',
      ),
    ),
  ),
  5 => 
  array (
    'act_id' => '202',
    'act_name' => '不孕首页',
    'act_action' => 'index_byby',
    'parent_id' => '0',
    'act_order' => '1',
    'act_url' => '?c=index&m=index_byby',
    'is_show' => '1',
  ),
  6 => 
  array (
    'act_id' => '105',
    'act_name' => '微网站内容管理',
    'act_action' => 'cms',
    'parent_id' => '0',
    'act_order' => '2',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '127',
        'act_name' => '抓取素材',
        'act_action' => 'st_content_info',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=posts&m=content_info',
        'is_show' => '0',
      ),
      1 => 
      array (
        'act_id' => '116',
        'act_name' => '修改单页面',
        'act_action' => 'edit_page',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=pages&m=write&pid={$pid}',
        'is_show' => '0',
      ),
      2 => 
      array (
        'act_id' => '131',
        'act_name' => '删除抓取网站',
        'act_action' => 'st_delete',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=system&m=st_delete',
        'is_show' => '0',
      ),
      3 => 
      array (
        'act_id' => '130',
        'act_name' => '添加抓取网站',
        'act_action' => 'st_add',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=system&m=site_info',
        'is_show' => '0',
      ),
      4 => 
      array (
        'act_id' => '129',
        'act_name' => '编辑抓取网站',
        'act_action' => 'st_edit',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=system&m=site_info&st_id={$st_id}',
        'is_show' => '0',
      ),
      5 => 
      array (
        'act_id' => '128',
        'act_name' => '抓取网站列表',
        'act_action' => 'st_list',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=system&m=site_list',
        'is_show' => '1',
      ),
      6 => 
      array (
        'act_id' => '126',
        'act_name' => '抓取内容',
        'act_action' => 'st_content_list',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=posts&m=get_content_list',
        'is_show' => '1',
      ),
      7 => 
      array (
        'act_id' => '111',
        'act_name' => '管理单页',
        'act_action' => 'page_manage',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=pages&m=manage',
        'is_show' => '1',
      ),
      8 => 
      array (
        'act_id' => '110',
        'act_name' => '添加单页',
        'act_action' => 'write_page',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=pages&m=write',
        'is_show' => '0',
      ),
      9 => 
      array (
        'act_id' => '106',
        'act_name' => '分类管理',
        'act_action' => 'manage',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=metas&m=manage',
        'is_show' => '1',
      ),
      10 => 
      array (
        'act_id' => '107',
        'act_name' => '编辑文章',
        'act_action' => 'post_editor',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=posts&m=write&pid={$pid}',
        'is_show' => '0',
      ),
      11 => 
      array (
        'act_id' => '108',
        'act_name' => '撰写文章',
        'act_action' => 'write_post',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=posts&m=write',
        'is_show' => '0',
      ),
      12 => 
      array (
        'act_id' => '109',
        'act_name' => '管理文章',
        'act_action' => 'post_manage',
        'parent_id' => '105',
        'act_order' => '0',
        'act_url' => '?c=posts&m=manage',
        'is_show' => '1',
      ),
    ),
  ),
  7 => 
  array (
    'act_id' => '2',
    'act_name' => '网站管理',
    'act_action' => 'site',
    'parent_id' => '0',
    'act_order' => '2',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '5',
        'act_name' => '网站列表',
        'act_action' => 'site_list',
        'parent_id' => '2',
        'act_order' => '1',
        'act_url' => '?c=site&m=site_list',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '32',
        'act_name' => '竞价报告获取',
        'act_action' => 'cpc_report_timing',
        'parent_id' => '2',
        'act_order' => '2',
        'act_url' => '?c=site&m=cpc_report_timing',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '6',
        'act_name' => '添加网站',
        'act_action' => 'site_add',
        'parent_id' => '2',
        'act_order' => '2',
        'act_url' => '?c=site&m=site_info',
        'is_show' => '1',
      ),
      3 => 
      array (
        'act_id' => '21',
        'act_name' => '渠道类型',
        'act_action' => 'from_type_list',
        'parent_id' => '2',
        'act_order' => '3',
        'act_url' => '?c=site&m=from_type_list',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '22',
        'act_name' => '渠道网站',
        'act_action' => 'from_site_list',
        'parent_id' => '2',
        'act_order' => '4',
        'act_url' => '?c=site&m=from_site_list',
        'is_show' => '1',
      ),
      5 => 
      array (
        'act_id' => '23',
        'act_name' => '添加渠道类型',
        'act_action' => 'from_type_add',
        'parent_id' => '2',
        'act_order' => '5',
        'act_url' => '?c=site&m=from_type_info',
        'is_show' => '0',
      ),
      6 => 
      array (
        'act_id' => '24',
        'act_name' => '编辑渠道类型',
        'act_action' => 'from_type_edit',
        'parent_id' => '2',
        'act_order' => '6',
        'act_url' => '?c=site&m=from_type_info&type_id={$type_id}',
        'is_show' => '0',
      ),
      7 => 
      array (
        'act_id' => '25',
        'act_name' => '删除渠道类型',
        'act_action' => 'from_type_delete',
        'parent_id' => '2',
        'act_order' => '7',
        'act_url' => '?c=site&m=from_type_del&type_id={$type_id}',
        'is_show' => '0',
      ),
      8 => 
      array (
        'act_id' => '26',
        'act_name' => '添加渠道网站',
        'act_action' => 'from_site_add',
        'parent_id' => '2',
        'act_order' => '8',
        'act_url' => '?c=site&m=from_site_info',
        'is_show' => '0',
      ),
      9 => 
      array (
        'act_id' => '27',
        'act_name' => '编辑渠道网站',
        'act_action' => 'from_site_edit',
        'parent_id' => '2',
        'act_order' => '9',
        'act_url' => '?c=site&m=from_site_info&site_id={$site_id}',
        'is_show' => '0',
      ),
      10 => 
      array (
        'act_id' => '28',
        'act_name' => '删除渠道网站',
        'act_action' => 'from_site_delete',
        'parent_id' => '2',
        'act_order' => '10',
        'act_url' => '?c=site&m=from_site_delete&site_id={$site_id}',
        'is_show' => '0',
      ),
      11 => 
      array (
        'act_id' => '29',
        'act_name' => '远程流量数据',
        'act_action' => 'site_visits_timing',
        'parent_id' => '2',
        'act_order' => '11',
        'act_url' => '?c=site&m=site_visits_timing',
        'is_show' => '1',
      ),
      12 => 
      array (
        'act_id' => '30',
        'act_name' => '编辑网站',
        'act_action' => 'site_edit',
        'parent_id' => '2',
        'act_order' => '15',
        'act_url' => '?c=site&m=site_info&site_id={$site_id}',
        'is_show' => '0',
      ),
      13 => 
      array (
        'act_id' => '59',
        'act_name' => '搜索项目',
        'act_action' => 'search_project',
        'parent_id' => '2',
        'act_order' => '20',
        'act_url' => '?c=site_seo&m=search_project',
        'is_show' => '1',
      ),
      14 => 
      array (
        'act_id' => '36',
        'act_name' => '优化工作记录',
        'act_action' => 'site_seo_add',
        'parent_id' => '2',
        'act_order' => '21',
        'act_url' => '?c=site&m=site_seo_add',
        'is_show' => '0',
      ),
      15 => 
      array (
        'act_id' => '37',
        'act_name' => 'SEO内容',
        'act_action' => 'site_seo_con',
        'parent_id' => '2',
        'act_order' => '22',
        'act_url' => '?c=site&m=site_seo_con',
        'is_show' => '0',
      ),
      16 => 
      array (
        'act_id' => '38',
        'act_name' => 'SEO外链',
        'act_action' => 'site_seo_link',
        'parent_id' => '2',
        'act_order' => '23',
        'act_url' => '?c=site&m=site_seo_link',
        'is_show' => '0',
      ),
      17 => 
      array (
        'act_id' => '39',
        'act_name' => '收录数据',
        'act_action' => 'seo_data',
        'parent_id' => '2',
        'act_order' => '24',
        'act_url' => '?c=site&m=seo_data',
        'is_show' => '0',
      ),
      18 => 
      array (
        'act_id' => '40',
        'act_name' => '关键词排名',
        'act_action' => 'seo_keywords_pm',
        'parent_id' => '2',
        'act_order' => '25',
        'act_url' => '?c=site&m=seo_keywords_pm',
        'is_show' => '0',
      ),
      19 => 
      array (
        'act_id' => '33',
        'act_name' => '竞价账户',
        'act_action' => 'cpc_account',
        'parent_id' => '2',
        'act_order' => '51',
        'act_url' => '?c=site&m=cpc_account&site_id={$site_id}',
        'is_show' => '0',
      ),
      20 => 
      array (
        'act_id' => '34',
        'act_name' => '网站数据',
        'act_action' => 'site_data',
        'parent_id' => '2',
        'act_order' => '52',
        'act_url' => '?c=site&m=site_data&site_id={$site_id}',
        'is_show' => '0',
      ),
      21 => 
      array (
        'act_id' => '35',
        'act_name' => '网站竞价数据',
        'act_action' => 'site_cpc',
        'parent_id' => '2',
        'act_order' => '53',
        'act_url' => '?c=site&m=site_cpc&site_id={$site_id}',
        'is_show' => '0',
      ),
      22 => 
      array (
        'act_id' => '60',
        'act_name' => '关键词项目类型',
        'act_action' => 'search_category',
        'parent_id' => '2',
        'act_order' => '60',
        'act_url' => '?c=site_seo&m=search_category&pro_id={$pro_id}',
        'is_show' => '0',
      ),
      23 => 
      array (
        'act_id' => '61',
        'act_name' => '导入项目excel',
        'act_action' => 'search_excel',
        'parent_id' => '2',
        'act_order' => '61',
        'act_url' => '?c=site_seo&m=search_excel&pro_id={$pro_id}',
        'is_show' => '0',
      ),
      24 => 
      array (
        'act_id' => '62',
        'act_name' => '搜索关键词',
        'act_action' => 'search_keyword',
        'parent_id' => '2',
        'act_order' => '63',
        'act_url' => '?c=site_seo&m=search_keyword&pro_id={$pro_id}',
        'is_show' => '0',
      ),
      25 => 
      array (
        'act_id' => '63',
        'act_name' => '项目报告',
        'act_action' => 'search_report',
        'parent_id' => '2',
        'act_order' => '64',
        'act_url' => '?c=site_seo&m=search_report&pro_id={$pro_id}',
        'is_show' => '0',
      ),
    ),
  ),
  8 => 
  array (
    'act_id' => '3',
    'act_name' => '成员管理',
    'act_action' => 'admin',
    'parent_id' => '0',
    'act_order' => '3',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '9',
        'act_name' => '所有成员',
        'act_action' => 'admin_list',
        'parent_id' => '3',
        'act_order' => '1',
        'act_url' => '?c=index&m=admin_list',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '10',
        'act_name' => '添加成员',
        'act_action' => 'admin_add',
        'parent_id' => '3',
        'act_order' => '2',
        'act_url' => '?c=index&m=admin_info',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '20',
        'act_name' => '编辑成员信息',
        'act_action' => 'admin_edit',
        'parent_id' => '3',
        'act_order' => '2',
        'act_url' => '?c=index&m=admin_info&admin_id={$admin_id}',
        'is_show' => '0',
      ),
      3 => 
      array (
        'act_id' => '12',
        'act_name' => '编辑操作',
        'act_action' => 'admin_action_edit',
        'parent_id' => '3',
        'act_order' => '4',
        'act_url' => '?c=index&m=admin_action_info&act_id={$act_id}',
        'is_show' => '0',
      ),
      4 => 
      array (
        'act_id' => '13',
        'act_name' => '添加操作',
        'act_action' => 'admin_action_add',
        'parent_id' => '3',
        'act_order' => '5',
        'act_url' => '?c=index&m=admin_action_info',
        'is_show' => '1',
      ),
      5 => 
      array (
        'act_id' => '14',
        'act_name' => '岗位管理',
        'act_action' => 'rank_list',
        'parent_id' => '3',
        'act_order' => '6',
        'act_url' => '?c=index&m=rank_list',
        'is_show' => '1',
      ),
      6 => 
      array (
        'act_id' => '17',
        'act_name' => '添加岗位',
        'act_action' => 'rank_add',
        'parent_id' => '3',
        'act_order' => '8',
        'act_url' => '?c=index&m=rank_info',
        'is_show' => '1',
      ),
      7 => 
      array (
        'act_id' => '18',
        'act_name' => '编辑岗位',
        'act_action' => 'rank_edit',
        'parent_id' => '3',
        'act_order' => '9',
        'act_url' => '?c=index&m=rank_info&rank_id={$rank_id}',
        'is_show' => '0',
      ),
      8 => 
      array (
        'act_id' => '68',
        'act_name' => '操作日志',
        'act_action' => 'admin_log',
        'parent_id' => '3',
        'act_order' => '10',
        'act_url' => '?c=index&m=admin_log',
        'is_show' => '1',
      ),
      9 => 
      array (
        'act_id' => '31',
        'act_name' => '个人信息',
        'act_action' => 'my_profile',
        'parent_id' => '3',
        'act_order' => '10',
        'act_url' => '?c=index&m=my_profile',
        'is_show' => '0',
      ),
      10 => 
      array (
        'act_id' => '65',
        'act_name' => '个人设置',
        'act_action' => 'my_setting',
        'parent_id' => '3',
        'act_order' => '11',
        'act_url' => '?c=index&m=my_setting',
        'is_show' => '0',
      ),
    ),
  ),
  9 => 
  array (
    'act_id' => '41',
    'act_name' => '预约管理',
    'act_action' => 'order',
    'parent_id' => '0',
    'act_order' => '4',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '165',
        'act_name' => '留联修改',
        'act_action' => 'order_edit_liulian',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_info_liulian',
        'is_show' => '0',
      ),
      1 => 
      array (
        'act_id' => '163',
        'act_name' => '留联列表',
        'act_action' => 'order_list_liulian',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_list_liulian',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '172',
        'act_name' => '个人留联列表',
        'act_action' => 'order_list_person_liulian',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_list_person_liulian',
        'is_show' => '1',
      ),
      3 => 
      array (
        'act_id' => '206',
        'act_name' => '留联公海捞取',
        'act_action' => 'fetch',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=liuliangonghai&m=fetch',
        'is_show' => '0',
      ),
      4 => 
      array (
        'act_id' => '173',
        'act_name' => '留联确认',
        'act_action' => 'order_info_liulian_setok',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_info_liulian_setok',
        'is_show' => '0',
      ),
      5 => 
      array (
        'act_id' => '164',
        'act_name' => '留联添加',
        'act_action' => 'order_add_liulian',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_info_liulian',
        'is_show' => '1',
      ),
      6 => 
      array (
        'act_id' => '178',
        'act_name' => '留联报表导出',
        'act_action' => 'order_liulian_list_down',
        'parent_id' => '41',
        'act_order' => '0',
        'act_url' => '?c=order&m=order_liulian_list_down',
        'is_show' => '0',
      ),
      7 => 
      array (
        'act_id' => '205',
        'act_name' => '留联公海',
        'act_action' => 'llindex',
        'parent_id' => '41',
        'act_order' => '1',
        'act_url' => '?c=liuliangonghai&m=llindex',
        'is_show' => '1',
      ),
      8 => 
      array (
        'act_id' => '143',
        'act_name' => '编辑所有',
        'act_action' => 'edit_dao',
        'parent_id' => '41',
        'act_order' => '1',
        'act_url' => '',
        'is_show' => '0',
      ),
      9 => 
      array (
        'act_id' => '54',
        'act_name' => '添加预约',
        'act_action' => 'order_add',
        'parent_id' => '41',
        'act_order' => '1',
        'act_url' => '?c=order&m=order_info',
        'is_show' => '1',
      ),
      10 => 
      array (
        'act_id' => '182',
        'act_name' => '来源网址列表',
        'act_action' => 'keshiurl_list',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=keshiurl&m=lists',
        'is_show' => '1',
      ),
      11 => 
      array (
        'act_id' => '181',
        'act_name' => '红包列表',
        'act_action' => 'bonous_list',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=bonous&m=lists',
        'is_show' => '1',
      ),
      12 => 
      array (
        'act_id' => '57',
        'act_name' => '预约列表',
        'act_action' => 'order_list',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=order&m=order_list',
        'is_show' => '1',
      ),
      13 => 
      array (
        'act_id' => '154',
        'act_name' => '预约查看全部数据',
        'act_action' => 'order_query_seven_data',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=order&m=order_edit',
        'is_show' => '0',
      ),
      14 => 
      array (
        'act_id' => '179',
        'act_name' => '预约电话查看',
        'act_action' => 'order_phone_query',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=order&m=order_phone_query',
        'is_show' => '0',
      ),
      15 => 
      array (
        'act_id' => '183',
        'act_name' => '来源网址添加',
        'act_action' => 'keshiurl_add',
        'parent_id' => '41',
        'act_order' => '2',
        'act_url' => '?c=keshiurl&m=add',
        'is_show' => '0',
      ),
      16 => 
      array (
        'act_id' => '51',
        'act_name' => '预约途径',
        'act_action' => 'order_from',
        'parent_id' => '41',
        'act_order' => '3',
        'act_url' => '?c=order&m=order_from',
        'is_show' => '1',
      ),
      17 => 
      array (
        'act_id' => '48',
        'act_name' => '预约性质',
        'act_action' => 'order_type',
        'parent_id' => '41',
        'act_order' => '3',
        'act_url' => '?c=order&m=order_type',
        'is_show' => '1',
      ),
      18 => 
      array (
        'act_id' => '136',
        'act_name' => '个人预约',
        'act_action' => 'person_gonghai_list',
        'parent_id' => '41',
        'act_order' => '3',
        'act_url' => '?c=gonghai&m=person_gonghai_list',
        'is_show' => '1',
      ),
      19 => 
      array (
        'act_id' => '180',
        'act_name' => '公海捞取(个人)',
        'act_action' => 'gonghai_get',
        'parent_id' => '41',
        'act_order' => '4',
        'act_url' => '?c=order&m=gonghai_get',
        'is_show' => '0',
      ),
      20 => 
      array (
        'act_id' => '184',
        'act_name' => '公海捞取(全部捞取)',
        'act_action' => 'gonghai_get',
        'parent_id' => '41',
        'act_order' => '4',
        'act_url' => '?c=gonghai&m=update_name',
        'is_show' => '0',
      ),
      21 => 
      array (
        'act_id' => '138',
        'act_name' => '公海患者',
        'act_action' => 'gonghai',
        'parent_id' => '41',
        'act_order' => '4',
        'act_url' => '?c=gonghai&m=gonghai',
        'is_show' => '1',
      ),
      22 => 
      array (
        'act_id' => '50',
        'act_name' => '编辑性质',
        'act_action' => 'type_edit',
        'parent_id' => '41',
        'act_order' => '5',
        'act_url' => '?c=order&m=order_info&type_id={$type_id}',
        'is_show' => '0',
      ),
      23 => 
      array (
        'act_id' => '66',
        'act_name' => '编辑预约',
        'act_action' => 'order_edit',
        'parent_id' => '41',
        'act_order' => '5',
        'act_url' => '?c=order&m=order_info&order_id={$order_id}',
        'is_show' => '0',
      ),
      24 => 
      array (
        'act_id' => '148',
        'act_name' => '编辑预约(更改咨询员)',
        'act_action' => 'order_edit_consultants',
        'parent_id' => '41',
        'act_order' => '5',
        'act_url' => '?c=order&m=order_edit',
        'is_show' => '0',
      ),
      25 => 
      array (
        'act_id' => '149',
        'act_name' => '编

辑预约(更改患者个人信息)',
        'act_action' => 'order_edit_person_info',
        'parent_id' => '41',
        'act_order' => '5',
        'act_url' => '?c=order&m=order_edit',
        'is_show' => '0',
      ),
      26 => 
      array (
        'act_id' => '124',
        'act_name' => '回访记录',
        'act_action' => 'huifang_list',
        'parent_id' => '41',
        'act_order' => '6',
        'act_url' => '?c=order&m=huifang_list',
        'is_show' => '1',
      ),
      27 => 
      array (
        'act_id' => '123',
        'act_name' => '留言列表',
        'act_action' => 'message_list',
        'parent_id' => '41',
        'act_order' => '6',
        'act_url' => '?c=order&m=message_list',
        'is_show' => '1',
      ),
      28 => 
      array (
        'act_id' => '122',
        'act_name' => '预约卡编辑',
        'act_action' => 'card_edit',
        'parent_id' => '41',
        'act_order' => '7',
        'act_url' => '?c=order&m=order_card&card_id={$card_id}',
        'is_show' => '0',
      ),
      29 => 
      array (
        'act_id' => '135',
        'act_name' => '四维排期表',
        'act_action' => 'siwei_show',
        'parent_id' => '41',
        'act_order' => '8',
        'act_url' => '?c=order&m=siwei_show',
        'is_show' => '1',
      ),
      30 => 
      array (
        'act_id' => '81',
        'act_name' => '就诊登记',
        'act_action' => 'pat_order',
        'parent_id' => '41',
        'act_order' => '10',
        'act_url' => '?c=order&m=pat_order',
        'is_show' => '1',
      ),
      31 => 
      array (
        'act_id' => '82',
        'act_name' => '就诊列表',
        'act_action' => 'pat_list',
        'parent_id' => '41',
        'act_order' => '11',
        'act_url' => '?c=order&m=pat_list',
        'is_show' => '1',
      ),
      32 => 
      array (
        'act_id' => '67',
        'act_name' => '未到诊原因',
        'act_action' => 'dao_false',
        'parent_id' => '41',
        'act_order' => '12',
        'act_url' => '?c=order&m=dao_false',
        'is_show' => '1',
      ),
      33 => 
      array (
        'act_id' => '70',
        'act_name' => '来源轨迹',
        'act_action' => 'order_swt',
        'parent_id' => '41',
        'act_order' => '20',
        'act_url' => '?c=order&m=order_swt',
        'is_show' => '1',
      ),
      34 => 
      array (
        'act_id' => '125',
        'act_name' => '删除留言',
        'act_action' => 'message_del',
        'parent_id' => '41',
        'act_order' => '20',
        'act_url' => '?c=show&m=message_del&order_id={$order_id}',
        'is_show' => '0',
      ),
      35 => 
      array (
        'act_id' => '120',
        'act_name' => '添加预约卡',
        'act_action' => 'card_add',
        'parent_id' => '41',
        'act_order' => '30',
        'act_url' => '?c=order&m=order_card',
        'is_show' => '0',
      ),
      36 => 
      array (
        'act_id' => '121',
        'act_name' => '预约卡管理',
        'act_action' => 'card_list',
        'parent_id' => '41',
        'act_order' => '31',
        'act_url' => '?c=order&m=card_list',
        'is_show' => '1',
      ),
      37 => 
      array (
        'act_id' => '49',
        'act_name' => '添加类型',
        'act_action' => 'type_add',
        'parent_id' => '41',
        'act_order' => '50',
        'act_url' => '?c=order&m=order_info',
        'is_show' => '0',
      ),
      38 => 
      array (
        'act_id' => '52',
        'act_name' => '添加预约途径',
        'act_action' => 'order_from_add',
        'parent_id' => '41',
        'act_order' => '53',
        'act_url' => '?c=order&m=from_info',
        'is_show' => '0',
      ),
      39 => 
      array (
        'act_id' => '53',
        'act_name' => '编辑预约途径',
        'act_action' => 'order_from_edit',
        'parent_id' => '41',
        'act_order' => '54',
        'act_url' => '?c=order&m=from_info&from_id={$from_id}',
        'is_show' => '0',
      ),
      40 => 
      array (
        'act_id' => '55',
        'act_name' => '删除预约性质',
        'act_action' => 'type_del',
        'parent_id' => '41',
        'act_order' => '55',
        'act_url' => '?c=order&m=order_del&type_id={$type_id}',
        'is_show' => '0',
      ),
      41 => 
      array (
        'act_id' => '56',
        'act_name' => '删除预约途径',
        'act_action' => 'from_del',
        'parent_id' => '41',
        'act_order' => '56',
        'act_url' => '?c=order&m=from_del&from_id={$from_id}',
        'is_show' => '0',
      ),
      42 => 
      array (
        'act_id' => '150',
        'act_name' => '下载留言',
        'act_action' => 'down_message',
        'parent_id' => '41',
        'act_order' => '62',
        'act_url' => '?c=order&m=down_message',
        'is_show' => '0',
      ),
      43 => 
      array (
        'act_id' => '133',
        'act_name' => '院长信箱',
        'act_action' => 'dean_list',
        'parent_id' => '41',
        'act_order' => '100',
        'act_url' => '?c=order&m=dean_list',
        'is_show' => '1',
      ),
      44 => 
      array (
        'act_id' => '134',
        'act_name' => '信件删除',
        'act_action' => 'dean_del',
        'parent_id' => '41',
        'act_order' => '100',
        'act_url' => '?c=show&m=dean_del&ask_id={$ask_id}',
        'is_show' => '0',
      ),
      45 => 
      array (
        'act_id' => '185',
        'act_name' => '黑名单列表',
        'act_action' => 'blackList',
        'parent_id' => '41',
        'act_order' => '100',
        'act_url' => '?c=order&m=blackList',
        'is_show' => '1',
      ),
      46 => 
      array (
        'act_id' => '186',
        'act_name' => '产品渠道查看',
        'act_action' => 'proWatch',
        'parent_id' => '41',
        'act_order' => '100',
        'act_url' => '?c=order&m=proWatch',
        'is_show' => '1',
      ),
      47 => 
      array (
        'act_id' => '69',
        'act_name' => '下载数据',
        'act_action' => 'order_list_down',
        'parent_id' => '41',
        'act_order' => '101',
        'act_url' => '?c=order&m=order_list_down',
        'is_show' => '0',
      ),
      48 => 
      array (
        'act_id' => '64',
        'act_name' => '对话数据导入',
        'act_action' => 'ask_data_input',
        'parent_id' => '41',
        'act_order' => '104',
        'act_url' => '?c=order&m=ask_data_input',
        'is_show' => '1',
      ),
      49 => 
      array (
        'act_id' => '191',
        'act_name' => '预约修改日志',
        'act_action' => 'logRecord',
        'parent_id' => '41',
        'act_order' => '500',
        'act_url' => '?c=order&m=logRecord',
        'is_show' => '1',
      ),
      50 => 
      array (
        'act_id' => '198',
        'act_name' => '电话显示统计',
        'act_action' => 'showPhoneRecord',
        'parent_id' => '41',
        'act_order' => '600',
        'act_url' => '?c=order&m=showPhoneRecord',
        'is_show' => '1',
      ),
    ),
  ),
  10 => 
  array (
    'act_id' => '15',
    'act_name' => '医保管理',
    'act_action' => 'yibao',
    'parent_id' => '0',
    'act_order' => '5',
    'act_url' => '',
    'is_show' => '0',
  ),
  11 => 
  array (
    'act_id' => '93',
    'act_name' => '微信管理',
    'act_action' => 'weixin',
    'parent_id' => '0',
    'act_order' => '20',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '94',
        'act_name' => '微信添加',
        'act_action' => 'weixin_add',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=system&m=weixin_info',
        'is_show' => '0',
      ),
      1 => 
      array (
        'act_id' => '95',
        'act_name' => '编辑微信',
        'act_action' => 'weixin_edit',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=system&m=weixin_info&wx_id={$wx_id}',
        'is_show' => '0',
      ),
      2 => 
      array (
        'act_id' => '98',
        'act_name' => '菜单添加',
        'act_action' => 'menu_add',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=menu_info',
        'is_show' => '0',
      ),
      3 => 
      array (
        'act_id' => '96',
        'act_name' => '微信帐号',
        'act_action' => 'weixin_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=weixin_list',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '97',
        'act_name' => '删除微信',
        'act_action' => 'weixin_delete',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=weixin_delete&wx_id={$wx_id}',
        'is_show' => '0',
      ),
      5 => 
      array (
        'act_id' => '118',
        'act_name' => '修改回复',
        'act_action' => 'con_edit',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=con_info&con_id={$con_id}',
        'is_show' => '0',
      ),
      6 => 
      array (
        'act_id' => '119',
        'act_name' => '回复列表',
        'act_action' => 'con_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=con_list',
        'is_show' => '1',
      ),
      7 => 
      array (
        'act_id' => '99',
        'act_name' => '编辑菜单',
        'act_action' => 'menu_edit',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=menu_info&menu_id={$menu_id}',
        'is_show' => '0',
      ),
      8 => 
      array (
        'act_id' => '100',
        'act_name' => '菜单管理',
        'act_action' => 'menu_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=menu_list',
        'is_show' => '1',
      ),
      9 => 
      array (
        'act_id' => '101',
        'act_name' => '更新菜单',
        'act_action' => 'menu_data',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=menu_data',
        'is_show' => '0',
      ),
      10 => 
      array (
        'act_id' => '102',
        'act_name' => '分组管理',
        'act_action' => 'group_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=group_list',
        'is_show' => '1',
      ),
      11 => 
      array (
        'act_id' => '103',
        'act_name' => '用户管理',
        'act_action' => 'user_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=user_list',
        'is_show' => '1',
      ),
      12 => 
      array (
        'act_id' => '104',
        'act_name' => '用户更新',
        'act_action' => 'user_info_ins',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=user_info_ins',
        'is_show' => '0',
      ),
      13 => 
      array (
        'act_id' => '112',
        'act_name' => '图文素材',
        'act_action' => 'tw_list',
        'parent_id' => '93',
        'act_order' => '0',
        'act_url' => '?c=weixin&m=tw_list',
        'is_show' => '1',
      ),
      14 => 
      array (
        'act_id' => '117',
        'act_name' => '添加回复',
        'act_action' => 'con_add',
        'parent_id' => '93',
        'act_order' => '10',
        'act_url' => '?c=weixin&m=con_info',
        'is_show' => '0',
      ),
    ),
  ),
  12 => 
  array (
    'act_id' => '84',
    'act_name' => '客服管理',
    'act_action' => 'swt',
    'parent_id' => '0',
    'act_order' => '100',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '87',
        'act_name' => '客服列表',
        'act_action' => 'swt_list',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_list',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '88',
        'act_name' => '导入数据',
        'act_action' => 'swt_data_add',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_data_add',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '89',
        'act_name' => '添加项目',
        'act_action' => 'item_add',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=item_add',
        'is_show' => '1',
      ),
      3 => 
      array (
        'act_id' => '90',
        'act_name' => '数据列表',
        'act_action' => 'swt_data_list',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_data_list',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '91',
        'act_name' => '项目列表',
        'act_action' => 'item_list',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=item_list',
        'is_show' => '1',
      ),
      5 => 
      array (
        'act_id' => '92',
        'act_name' => '链接列表',
        'act_action' => 'swt_click_list',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_click_list',
        'is_show' => '1',
      ),
      6 => 
      array (
        'act_id' => '86',
        'act_name' => '编辑客服',
        'act_action' => 'swt_edit',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_edit',
        'is_show' => '0',
      ),
      7 => 
      array (
        'act_id' => '85',
        'act_name' => '添加客服',
        'act_action' => 'swt_add',
        'parent_id' => '84',
        'act_order' => '0',
        'act_url' => '/?c=swt&m=swt_add',
        'is_show' => '1',
      ),
    ),
  ),
  13 => 
  array (
    'act_id' => '76',
    'act_name' => '数据分析',
    'act_action' => 'analytics',
    'parent_id' => '0',
    'act_order' => '100',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '83',
        'act_name' => '对话添加',
        'act_action' => 'talk_add',
        'parent_id' => '76',
        'act_order' => '0',
        'act_url' => '?c=order&m=talk_add',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '79',
        'act_name' => '预约分析',
        'act_action' => 'order_data',
        'parent_id' => '76',
        'act_order' => '1',
        'act_url' => '?c=order&m=order_data',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '78',
        'act_name' => '到诊分析',
        'act_action' => 'miss_patient',
        'parent_id' => '76',
        'act_order' => '2',
        'act_url' => '?c=order&m=miss_patient',
        'is_show' => '1',
      ),
      3 => 
      array (
        'act_id' => '77',
        'act_name' => '未到诊分析',
        'act_action' => 'miss_order',
        'parent_id' => '76',
        'act_order' => '3',
        'act_url' => '?c=order&m=miss_order',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '80',
        'act_name' => '咨询分析',
        'act_action' => 'zx_data',
        'parent_id' => '76',
        'act_order' => '4',
        'act_url' => '?c=order&m=zx_data',
        'is_show' => '1',
      ),
      5 => 
      array (
        'act_id' => '153',
        'act_name' => '预约报表查询与下载',
        'act_action' => 'order_report_list',
        'parent_id' => '76',
        'act_order' => '5',
        'act_url' => '?c=order&m=order_report_list',
        'is_show' => '1',
      ),
    ),
  ),
  14 => 
  array (
    'act_id' => '139',
    'act_name' => '系统公告',
    'act_action' => 'system_message',
    'parent_id' => '0',
    'act_order' => '200',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '140',
        'act_name' => '编辑公告',
        'act_action' => 'mes_list',
        'parent_id' => '139',
        'act_order' => '1',
        'act_url' => '?c=sys_message&m=mes_list',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '141',
        'act_name' => '添加公告',
        'act_action' => 'mes_add',
        'parent_id' => '139',
        'act_order' => '2',
        'act_url' => '?c=sys_message&m=mes_add',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '142',
        'act_name' => '公告列表',
        'act_action' => 'mess_list',
        'parent_id' => '139',
        'act_order' => '3',
        'act_url' => '?c=sys_message&m=mess_list',
        'is_show' => '1',
      ),
    ),
  ),
  15 => 
  array (
    'act_id' => '166',
    'act_name' => '小组',
    'act_action' => 'group_person',
    'parent_id' => '0',
    'act_order' => '300',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '167',
        'act_name' => '添加小组',
        'act_action' => 'group_person_add',
        'parent_id' => '166',
        'act_order' => '1',
        'act_url' => '?c=group_person&m=group_person_add',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '168',
        'act_name' => '编辑小组',
        'act_action' => 'group_person_update',
        'parent_id' => '166',
        'act_order' => '2',
        'act_url' => '?c=group_person&m=group_person_update',
        'is_show' => '0',
      ),
      2 => 
      array (
        'act_id' => '169',
        'act_name' => '删除小组',
        'act_action' => 'group_person_del',
        'parent_id' => '166',
        'act_order' => '3',
        'act_url' => '?c=group_person&m=group_person_del',
        'is_show' => '0',
      ),
      3 => 
      array (
        'act_id' => '170',
        'act_name' => '小组列表',
        'act_action' => 'group_person_list',
        'parent_id' => '166',
        'act_order' => '4',
        'act_url' => '?c=group_person&m=group_person_list',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '171',
        'act_name' => '归类到小组',
        'act_action' => 'group_person_set',
        'parent_id' => '166',
        'act_order' => '5',
        'act_url' => '?c=group_person&m=group_person_set',
        'is_show' => '0',
      ),
    ),
  ),
  16 => 
  array (
    'act_id' => '187',
    'act_name' => '报表管理',
    'act_action' => 'report',
    'parent_id' => '0',
    'act_order' => '301',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '188',
        'act_name' => '预约报表',
        'act_action' => 'rindex',
        'parent_id' => '187',
        'act_order' => '0',
        'act_url' => '?c=report&m=rindex',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '192',
        'act_name' => '留联预约到诊',
        'act_action' => 'lindex',
        'parent_id' => '187',
        'act_order' => '1',
        'act_url' => '?c=report&m=lindex',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '193',
        'act_name' => '留联预约到诊下载',
        'act_action' => 'dump_liulian',
        'parent_id' => '187',
        'act_order' => '2',
        'act_url' => '?c=report&m=dump_liulian',
        'is_show' => '0',
      ),
      3 => 
      array (
        'act_id' => '189',
        'act_name' => '区域病种分析',
        'act_action' => 'analyze',
        'parent_id' => '187',
        'act_order' => '3',
        'act_url' => '?c=report&m=analyze',
        'is_show' => '1',
      ),
      4 => 
      array (
        'act_id' => '190',
        'act_name' => '病种管理',
        'act_action' => 'bzindex',
        'parent_id' => '187',
        'act_order' => '500',
        'act_url' => '?c=report&m=bzindex',
        'is_show' => '1',
      ),
    ),
  ),
  17 => 
  array (
    'act_id' => '195',
    'act_name' => '财务管理',
    'act_action' => 'his',
    'parent_id' => '0',
    'act_order' => '600',
    'act_url' => '',
    'is_show' => '1',
    'son' => 
    array (
      0 => 
      array (
        'act_id' => '196',
        'act_name' => '到诊消费报表',
        'act_action' => 'hindex',
        'parent_id' => '195',
        'act_order' => '0',
        'act_url' => '?c=his&m=hindex',
        'is_show' => '1',
      ),
      1 => 
      array (
        'act_id' => '199',
        'act_name' => '消费列表',
        'act_action' => 'ConsumptionList',
        'parent_id' => '195',
        'act_order' => '0',
        'act_url' => '?c=his&m=ConsumptionList',
        'is_show' => '1',
      ),
      2 => 
      array (
        'act_id' => '197',
        'act_name' => '设置HIS',
        'act_action' => 'setHis',
        'parent_id' => '195',
        'act_order' => '1',
        'act_url' => '?c=his&m=setHis',
        'is_show' => '1',
      ),
    ),
  ),
);
?>