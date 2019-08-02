<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8" />
<title>用户短信预约页</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta content="" name="description" />
<meta content="" name="author" />
<link href="static/css/bootstrap.min.css" rel="stylesheet" />
<link href="static/css/bootstrap-responsive.min.css" rel="stylesheet" />
<link href="static/css/font-awesome.css" rel="stylesheet" />
<link href="static/css/style.css" rel="stylesheet" />
<link href="static/css/style-responsive.css" rel="stylesheet" />
<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />
<link rel="stylesheet" type="text/css" href="static/css/datepicker.css" />
<style>
.list {margin: 0 auto;width:980px;min-height: 645px;_height: 645px;padding: 5px 0;font-size:12px;}
.list dl {float: left;width: 457px;background-color: #f1f1f1;padding: 10px;margin: 5px;display: inline; position:relative;}
.list dl dt {float: left;width: 150px;margin-right: 10px;}
.list dl .name {color: #0094ab;height: 30px;line-height: 50px;margin-bottom: 10px;}
.list dl .name h3 {font-size: 16px;float: left;padding-right: 5px;line-height: 30px;}
.list dl .zhicheng {border-bottom: 1px dotted #ccc;border-top: 1px dotted #ccc;margin-bottom: 5px;padding: 5px 0;font-size: 14px;}
.list dl .desc {line-height: 20px;height: 75px;_height: 71px;overflow: hidden;width: 294px;font-size: 12px;}

.Button1{color:#0094ab;}

.black_overlay{
display: none;
position: absolute;
top: 0%;
left: 0%;
width: 100%;
height: 100%;
background-color: black;
z-index:1001;
-moz-opacity: 0.8;
opacity:.80;
filter: alpha(opacity=80);
}
.white_content {
display: none;
position: absolute;
top: 10%;
left: 10%;
width: 80%;
height: 80%;
border: 16px solid lightblue;
background-color: white;
z-index:1002;
overflow: auto;
}

</style>
</head>

<body class="fixed-top">
<div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <div class="navbar-inner">
           <div class="container-fluid">

               <!-- BEGIN LOGO -->

               
               <!-- END LOGO -->



           </div>
       </div>
       <!-- END TOP NAVIGATION BAR -->
   </div>
<div id="container" class="row-fluid">


<div id="main-content" style="margin-left: 0px; z-index: 100;">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
               <div class="span12">
                   <!-- BEGIN THEME CUSTOMIZER-->
                   <div id="theme-change" class="hidden-phone">
                       <i class="icon-cogs"></i>
                        <span class="settings">
                            <span class="text">Theme Color:</span>
                            <span class="colors">
                                <span class="color-default" data-style="default"></span>
                                <span class="color-green" data-style="green"></span>
                                <span class="color-gray" data-style="gray"></span>
                                <span class="color-purple" data-style="purple"></span>
                                <span class="color-red" data-style="red"></span>
                            </span>
                        </span>
                   </div>
                   <!-- END THEME CUSTOMIZER-->
                   <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">短信预约</h3>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

			<div class="row-fluid">
				<div class="span12">
					<div class="widget green">
							<div class="widget-title">
							<h4><i class="icon-reorder"></i>短信预约展示页</h4>
							<span class="tools">
								<a href="javascript:;" class="icon-chevron-down"></a>
								<a href="javascript:;" class="icon-remove"></a>
							</span>
							</div>
							<div class="widget-body" style="height:auto;">
								<div class="list">
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2014/0122/thumb_150_185_20140122090046961.jpg" alt="徐宏里" width="150"></dt>
      <dd class="name"><h3>徐宏里</h3>Xuhongli Expert</dd>
      <dd class="zhicheng"><b>主任医师 硕士研究生导师 </b></dd>
      <dd class="desc"><b>专家介绍：</b>徐宏里主任，是国内知名妇产科专家(原深圳市人民医院妇产科主任)。1970年毕业于白求恩医科大学，毕业后留校一直从事妇产科的临床医疗、教学和科研工作，被聘为暨南大学教授、临床研究生导师。</dd>
      <dd><input class="Button1"  type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','徐宏里','妇产科',7,39)" /></dd>
    </dl>
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2014/0122/thumb_150_185_20140122090104637.jpg" alt="柳晓春" width="150"></dt>
      <dd class="name"><h3>柳晓春</h3>Liuxiaochun Expert</dd>
      <dd class="zhicheng"><b>主任医师 妇科肿瘤专家</b></dd>
      <dd class="desc"><b>专家介绍：</b>柳晓春主任是国内最先进行“经阴道子宫肌瘤剔除术”研究的妇科专家，被业界称为“妇科阴式手术第一人”，她有近30年妇科临床经验!</dd>
      <dd"><input class="Button1" type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','柳晓春','妇产科',7,39)" /></dd>
    </dl>
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2012/1108/thumb_150_185_20121108092609786.jpg" alt="李莉" width="150"></dt>
      <dd class="name"><h3>李莉</h3> Expert</dd>
      <dd class="zhicheng"><b>首席腔镜专家  副主任医师</b></dd>
      <dd class="desc"><b>专家介绍：</b>毕业于北京医科大学，理论基础扎实，技术操作规范。曾在上海国际和平妇幼保健院进修妇产科，曾在北京复兴医院腔镜中心进修妇科腔镜手术，能独立而娴熟地进行宫腹腔镜下子宫肌瘤、卵巢囊肿、宫</dd>
      <dd"><input class="Button1" type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','李莉','妇产科',7,39)" /></dd>
    </dl>
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2012/1108/thumb_150_185_20121108093412707.jpg" alt="黄艳" width="150"></dt>
      <dd class="name"><h3>黄艳</h3>Huangyan Expert</dd>
      <dd class="zhicheng"><b>副主任医师 宫颈疾病专家 </b></dd>
      <dd class="desc"><b>专家介绍：</b>毕业于哈尔滨医科大学，从事临床医疗妇产科专业20年。曾在哈尔滨医科大学附属第二医院妇产科进修一年，发表国家级论文十余篇，参编论著一部。</dd>
      <dd"><input class="Button1" type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','黄艳','妇产科',7,39)" /></dd>
    </dl>
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2013/1221/thumb_150_185_20131221033345122.jpg" alt="黄波" width="150"></dt>
      <dd class="name"><h3>黄波</h3>Hangbo Expert</dd>
      <dd class="zhicheng"><b>主治医师</b></dd>
      <dd class="desc"><b>专家介绍：</b>毕业于黑龙江齐齐哈尔医学院，从事妇产科临床工作十余年，对各种妇科常见、多发及疑难病症有丰富的诊治经验，熟练掌握各类妇产科常规手术操作。</dd>
      <dd><input class="Button1" type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','黄波','妇产科',7,39)" /></dd>
    </dl>
        <dl>
      <dt><img src="http://www.ra120.com/uploadfile/2014/0516/thumb_150_185_20140516021751637.jpg" alt="万质纯" width="150"></dt>
      <dd class="name"><h3>万质纯</h3> Expert</dd>
      <dd class="zhicheng"><b>妇科主治医师</b></dd>
      <dd class="desc"><b>专家介绍：</b>毕业于南华大学衡阳医学院，从事妇产科临床20余年，多次在湘雅附一、附二进修学习，在国家级、省级杂志发表论文20余篇。具有扎实的基础理论知识及丰富的临床经验，擅长各类妇科疾病的诊治和各</dd>
      <dd><input class="Button1" type="button" value="短信预约" onclick="ShowDiv('MyDiv','fade','万质纯','妇产科',7,39)" /></dd>
    </dl>

        </div>
							</div>
					</div>
				</div>
			</div>
		</div>
</div>
</div>
<div id="fade" class="black_overlay">
</div>

					<div class="widget green white_content" id="MyDiv">
							<div class="widget-title">
							<h4><i class="icon-reorder"></i> 短信预约登记</h4>
							<span class="tools">


								<span style="font-size: 14px;line-height:12px;color:#fff;cursor: pointer;" onclick="CloseDiv('MyDiv','fade')">关闭</span>
							</span>
							</div>
							<div class="widget-body">

								<span style="font-size:16px;color:#74B749;;">深圳仁爱医院</span><br>
								<form action="?c=order&amp;m=text" method="post" class="form-horizontal">


<div class="control-group">
	<label class="control-label" >预约科室</label>
	<div class="controls" id="keshi" style="padding-top:5px;">

	</div>
</div>
<div class="control-group">
	<label class="control-label">预约专家</label>
	<div class="controls" id="doctor_name" style="padding-top:5px;">

	</div>
</div>
<div class="control-group">
	<label class="control-label">姓名</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="pat_name">
	</div>
</div>
<div class="control-group">
	<label class="control-label">输入手机号码</label>
	<div class="controls">
		<input type="text" value="" class="input-xlarge" name="pat_phone">
	</div>
</div>

<div class="control-group">
	<div class="controls">
		<input type="submit" name="submit" value=" 提交 " class="btn btn-success">  <input name="reset" type="reset" value=" 重置 " class="btn">
	</div>
</div>
	<input type="hidden" name="hos_id" value="" id="hi_hos">
	<input type="hidden" name="keshi_id" value="" id="hi_keshi">
	<input type="hidden" name="doctor_name" value="" id="hi_doc">
  </form>
							</div>
					</div>
	<script type="text/javascript">
		//弹出隐藏层
		function ShowDiv(show_div,bg_div,doctor_name,keshi,hi_hos,hi_keshi){
		$(document).scrollTop(0);
		document.getElementById(show_div).style.display='block';
		document.getElementById(bg_div).style.display='block' ;
		var bgdiv = document.getElementById(bg_div);
		bgdiv.style.width = document.body.scrollWidth;
		// bgdiv.style.height = $(document).height();
		$("#"+bg_div).height($(document).height());
		$('#doctor_name').text(doctor_name);
		$('#keshi').text(keshi);
		$('#hi_doc').val(doctor_name);
		$('#hi_hos').val(hi_hos);
		$('#hi_keshi').val(hi_keshi);
		};
		//关闭弹出层
		function CloseDiv(show_div,bg_div)
		{
		document.getElementById(show_div).style.display='none';
		document.getElementById(bg_div).style.display='none';
		};
	</script>
   <script src="static/js/jquery.js"></script>
   <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>
   <script src="static/js/bootstrap.min.js"></script>
   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="static/js/excanvas.js"></script>
   <script src="static/js/respond.js"></script>
   <![endif]-->
   <script src="static/js/common-scripts.js"></script>
</body>
</html>