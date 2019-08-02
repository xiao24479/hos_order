<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//掉公海规则

$config['allowed_drop_hos_id'] = array(6);

//留联公海规则
$config['dropSeaRuleLiulian'] = array(
    '1' => "登记时间超过30天",
    '2' => "无回访超过3天",
    '3' => "回访超过7天",
    '4' => "捞取时间超过十天未转预约",
);

//预约公海规则
$config['dropSeaRule'] = array(
    '1' => "登记时间超过30天掉公海(排除已捞出预约)",
    '2' => "登记时间30天内预到未到掉公海",
    '3' => "登记时间30天内超过7天未回访掉公海",
    '4' => "登记时间30天内登记时间超过7天且未回访掉公海",
    '5' => "超过捞取时间10天未到诊掉公海",
    '6' => "预到时间修改超过3次未到诊掉公海",
);


//首页男妇科区分
//索引是hos_id

$config['all_fuke'] = array(
	'0' => array(
		'hos_id' => '1',
		'keshi_id' => '1_167',
		'hos_name' => '深圳仁爱医院',
	),
	'1' => array(
		'hos_id' => '3_36',
		'keshi_id' => '110_94_86_26',
		'hos_name' => '台州五洲医院',
	),
	'2' => array(
		'hos_id' => '37',
		'keshi_id' => '166',
		'hos_name' => '温州五马医院',
	),
	'3' => array(
		'hos_id' => '54',
		'keshi_id' => '159_152',
		'hos_name' => '宁波鄞州新东方医院',
	),
	'4' => array(
		'hos_id' => '45_46',
		'keshi_id' => '114_117_127',
		'hos_name' => '厦门鹭港妇产医院',
	),
);

$config['all_nanke'] = array(
	'0' => array(
		'hos_id' => '3_36',
		'keshi_id' => '4_92_85_136',
		'hos_name' => '台州五洲医院',
	),
	'1' => array(
		'hos_id' => '6_21',
		'keshi_id' => '28_140_139_126_90_88_145_63_34',
		'hos_name' => '东莞东方医院',
	),
	'2' => array(
		'hos_id' => '37',
		'keshi_id' => '96_99_123_125_138',
		'hos_name' => '温州五马医院',
	),
	'3' => array(
		'hos_id' => '54',
		'keshi_id' => '151_154',
		'hos_name' => '宁波鄞州新东方医院',
	),
);

$config['all_byby'] = array(
	'0' => array(
		'hos_id' => '1',
		'keshi_id' => '32',
		'hos_name' => '深圳仁爱医院',
	),
	// '3' => array(
	// 	'hos_id' => '54',
	// 	'keshi_id' => '153',
	// 	'hos_name' => '宁波鄞州新东方医院',
	// ),
	// '4' => array(
	// 	'hos_id' => '45_46',
	// 	'keshi_id' => '116_129_119',
	// 	'hos_name' => '厦门鹭港妇产医院',
	// ),
);

$config['all_gc'] = array(
	'0' => array(
		'hos_id' => '9',
		'keshi_id' => '12_15_46',
		'hos_name' => '深圳仁爱医院',
	),
	'1' => array(
		'hos_id' => '38',
		'keshi_id' => '97_98',
		'hos_name' => '温州五马医院',
	),
	'2' => array(
		'hos_id' => '52',
		'keshi_id' => '146_147',
		'hos_name' => '深圳鹏程医院',
	),
	'3' => array(
		'hos_id' => '55',
		'keshi_id' => '168',
		'hos_name' => '宁波鄞州新东方医院',
	),

	'4' => array(
		'hos_id' => '16',
		'keshi_id' => '53_54',
		'hos_name' => '台州五洲生殖医院',
	),

);


//留联到诊报表男科妇科区分
//索引是hos_id
$config['liulian_dz_ks'] = array(
	'1' => array(
		array(
			'ks_ids'=>'32',
			'ks_name'=>'不孕不育'
		)
	),
	'3' => array(
		array(
			'ks_ids'=>'4_124_95_92',
			'ks_name'=>'男科'
		),
		array(
			'ks_ids'=>'110_26',
			'ks_name'=>'妇科'
		)
	),
	'54' => array(
		array(
			'ks_ids'=>'151',
			'ks_name'=>'男科'
		),
		array(
			'ks_ids'=>'152_153',
			'ks_name'=>'妇科'
		)
	),
);


//目前需要关联的his系统就诊号的项目
$config['open_his_item'] = array(3);

//到诊消费报表科室配置
$config['taizhou_nanke'] = array(4,124,95,92);
$config['taizhou_fuke'] = array(26,110);

//限制登录总开关
$config['switch'] = '1';//1代表开启，0代表关闭；

//限制登录指定IP和IP段
$config['ip'] = '119.147.210.143,183.47.45.220,14.21.67.236,14.21.67.228,115.238.135.82';//115.238.135.82为宁波医院固定IP
$config['ips'] = '116.24.100.*,116.24.101.*,116.24.102.*,116.24.103.*,116.24.104.*';

/***
  在这里配置 仁爱 妇科 预约单订单定时转换到公海的 时间范围。 day_time 只能赋值int类型的数字
**/
$config['day_time']	= '3';



/***
  在这里配置  东方 预约单订单定时转换到公海的 时间范围。 day_time 只能赋值int类型的数字
**/
$config['dongfang_day_time']	= '1';


/***
 * 2017 07 01 仁爱妇科 不孕流入公海
* 在这里配置 仁爱 妇科 + 不孕 预约单订单定时转换到公海的 时间范围。 day_time 只能赋值int类型的数字
*/
$config['renfi_fk_by_day_time']	= '7';//，默认 1天 
 

$config['keshi_check_ts']	= '4,26,92,94,95,110';//台州科室字符 
$config['zixun_check_ts']	= '347,202,298,196,192,187,360,344,292,291,240,223,195,183,17';//台州咨询员字符 


//数据中心地址
// $config['ireport_url_get_user']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=get_user&j=1';//获取用户接口
// $config['ireport_url_send_user']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_user&j=1';//推送用户数据接口
// $config['ireport_url_del_user']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_user&j=1';//推送用户数据接口
// $config['ireport_url_send_hospital']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_hospital&j=1';//推送医院数据接口

// $config['ireport_url_del_hospital']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_hospital&j=1';//推送医院数据接口

// $config['ireport_url_send_keshi']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_keshi&j=1';//推送科室数据接口
// $config['ireport_url_del_keshi']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_keshi&j=1';//推送疾病数据接口

// $config['ireport_url_send_jb']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_jb&j=1';//推送疾病数据接口
// $config['ireport_url_del_jb']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_jb&j=1';//推送疾病数据接口

// $config['ireport_url_send_keshi_and_jb']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_keshi_and_jb&j=1';//推送疾病数据接口

// $config['ireport_url_send_order_type']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_order_type&j=1';//推送疾病数据接口
// $config['ireport_url_del_order_type']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_order_type&j=1';//推送疾病数据接口

// $config['ireport_url_send_order_from']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_order_from&j=1';//推送疾病数据接口
// $config['ireport_url_del_order_from']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_order_from&j=1';//推送疾病数据接口


// $config['ireport_url_add_order']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=send_order&j=1';//添加预约单
// $config['ireport_url_edit_order']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=del_order&j=1';//更新预约单
// $config['ireport_url_huifang_order']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=huifang_order&j=1';//添加回访信息
// $config['ireport_url_daozhen_order']	= 'http://report.ra120.com/api/main.php?m=yuyue_api&a=daozhen_order&j=1';//预约到诊数据


// $config['ireport_name']	= 'irpt_user_api';//账号
// $config['ireport_pwd']	= '9e2354ea6ffb41f5eab719109ef47a2d';//密码  md5(base64_encode($this->config->item('ireport_name').'数据中心的用户密码'))

// //数据中心的API  加密字符串
// $config['renai_mu_word'] = 'OY45B2';
// $config['renai_mu_number'] = '#@*;&:';

//仁爱预约系统Api 参数
$config['mu_word'] = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890';
$config['mu_number'] = '@#&*:;';


/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['base_url']	= 'http://www.renaidata.com/';

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = 'index.php';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'chinese';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = TRUE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'zampo';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 7200;
$config['sess_expire_on_close']	= FALSE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
| 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';


/* End of file config.php */
/* Location: ./application/config/config.php */
