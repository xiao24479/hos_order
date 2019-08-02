<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
<meta charset="utf-8" />
<title>商务通在线编辑面版</title>
<style type="text/css">
  .span_a{color:blue}
  span.span_a:hover {text-decoration:underline}
  #show_help{font-size:15px;cursor:pointer}
  #hide_help{position:absolute;display:none;top:33px;left:420px;width:473px;background:#f1fedd;text-align:left;overflow:hidden;height:207px;padding:9px;}
</style>
<!--[if lt IE 10]>
  <script type="text/javascript" src="/static/js/PIE.js"></script>
<![endif]-->
<script type="text/javascript" src="/static/js/jquery-1.8.3.min.js"></script>
<script language="javascript">
$(function() {
  if (window.PIE) {
    $('.rounded').each(function() {
      PIE.attach(this);
    });
  }

  var zindex = 8999;//索引叠加初始值
  function mouse_move(obj){//移动函数<!--{{{-->
    $(obj).mousedown(function(event){//设置鼠标事件
      var this_obj = $(this);
      var position = this_obj.position();
      var _x = event.pageX - position.left;
      var _y = event.pageY - position.top;
      zindex=zindex+1;
      this_obj.css({"zIndex":zindex});
      this_obj.mousemove(function(e) {//鼠标移动事件
        var _xx = e.pageX - _x;
        var _yy = e.pageY - _y;
        this_obj.css({"left":_xx+"px","top":_yy+"px"});
      }).mouseleave(function(){//放开鼠标时移除鼠标移动事件
        this_obj.unbind('mousemove');
      }).mouseup(function(){
      this_obj.unbind('mousemove');
    });
      event.stopPropagation();//阻止事件冒泡
    });
  }
  function bindResize(el){//拖动改变大小
    $('div.s_red').unbind('mousedown').unbind('mouseup');//在拖动时阻止长辈元素移动
    //初始化参数
    var els = el.style,
    //鼠标的 X 和 Y 轴坐标
    x = y = 0;
    //邪恶的食指
    $(el).click(function(e){
      e.stopPropagation();//阻止父元素在单击时触发移动事件
    });
    $(el).dblclick(function(){
      $(el).unbind();//移除'拖动改变大小'
      $(el).css('cursor','move');
      mouse_move(el);//增加移动功能
      $(el).dblclick(function(){
        $(el).unbind();//移除"移动功能"
        $(el).css('cursor','se-resize');
        bindResize(el);//增加"拖动改变大小"
      });
    });
    $(el).mousedown(
      function (e)
      {
        //按下元素后，计算当前鼠标与对象计算后的坐标
        x = e.clientX - el.offsetWidth,
        y = e.clientY - el.offsetHeight;
        //在支持 setCapture 做些东东
        el.setCapture ? (
          //捕捉焦点
          el.setCapture(),
          //设置事件
          el.onmousemove = function (ev)
          {
            mouseMove(ev || event);
          },
          el.onmouseup = mouseUp
        ) : (
        //绑定事件
        $(document).bind("mousemove", mouseMove).bind("mouseup", mouseUp)
        );
        //防止默认事件发生
        e.preventDefault();
      });
      //移动事件
      function mouseMove(e)
      {
        //宇宙超级无敌运算中...
        els.width = e.clientX - x + 'px',
        els.height = e.clientY - y + 'px';
      }
      //停止事件
      function mouseUp()
      {
        //在支持 releaseCapture 做些东东
        el.releaseCapture ? (
          //释放焦点
          el.releaseCapture(),
          //移除事件
          el.onmousemove = el.onmouseup = null
        ) : (
        //卸载事件
        $(document).unbind("mousemove", mouseMove).unbind("mouseup", mouseUp)
        );
      }
  }//<!--}}}-->

  $("div.swt_img").live("click",function(){//单击带背景的div时增加红色边框，且绑定移动函数
    if(!$(this).hasClass('s_red')){
      $("div.s_red").removeClass('s_red');
      $(this).addClass('s_red');
    }
    var closest = $(this).closest('div.swt_wrap');
    closest.css('zIndex',zindex);
    mouse_move($(this));
    });

    <?php if(!empty($add_js)) echo $add_js;?>

  $("#s_btn1").click(function(){//使选中的红色框向左边对齐{{{
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({right:'auto',left:'0px'});
    if(swt_wrap.hasClass('swt_left_cen'))
      swt_wrap.removeClass('swt_left_cen');
    if(swt_wrap.hasClass('swt_right_cen'))
      swt_wrap.removeClass('swt_right_cen');
  });
  $("#s_btn2").click(function(){//使选中的红色框向左靠近网站内容左边
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({right:'auto',left:'0px'});
    swt_wrap.addClass('swt_left_cen');
    if(swt_wrap.hasClass('swt_right_cen'))
      swt_wrap.removeClass('swt_right_cen');
  });
  $("#s_btn3").click(function(){//使选中的红色框向右靠近网站内容右边
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({right:'0px',left:'auto'});
    swt_wrap.addClass('swt_right_cen');
    if(swt_wrap.hasClass('swt_left_cen'))
      swt_wrap.removeClass('swt_left_cen');
  });
  $("#s_btn4").click(function(){//使选中的红色框右对齐
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({right:'0px',left:'auto'});
    if(swt_wrap.hasClass('swt_left_cen'))
      swt_wrap.removeClass('swt_left_cen');
    if(swt_wrap.hasClass('swt_right_cen'))
      swt_wrap.removeClass('swt_right_cen');
  });
  $("#s_btn5").click(function(){//使选的红色向顶部对齐
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({top:0,bottom:'auto'});
    $("div.s_red").closest('div.swt_wrap').css({top:0,bottom:'auto'});
    swt_wrap.addClass('swt_top');
    if(swt_wrap.hasClass('swt_bottom'))
      swt_wrap.removeClass('swt_bottom');
  });
  $("#s_btn6").click(function(){//使选中的红色框向底部对齐
    var s_red = $("div.s_red");
    var swt_wrap = s_red.closest('div.swt_wrap');
    s_red.css({top:'auto',bottom:0});
    $("div.s_red").closest('div.swt_wrap').css({top:'auto',bottom:0});
    swt_wrap.addClass('swt_bottom');
    if(swt_wrap.hasClass('swt_top'))
      swt_wrap.removeClass('swt_top');
  });//}}}

  $("#s_btn7").click(function(){//增加商务通链接<!--{{{-->
    $("#s_filter").fadeTo('normal',0.4);
    $("#pop_wrap").fadeIn('fast');
    $("#pop").html('<div>事件类别：<select id="a_rel" style="width:150px;"><option value="右侧漂浮">右侧漂浮</option><option value="左侧漂浮">左侧漂浮</option><option value="右栏固定">右栏固定</option><option value="右栏浮动">右栏浮动</option><option value="左栏固定">左栏固定</option><option value="左栏浮动">左栏浮动</option><option value="右下漂浮">右下漂浮</option><option value="左下漂浮">左下漂浮</option><option value="顶部通栏">顶部通栏</option><option value="底部通栏">底部通栏</option><option value="文章头部">文章头部</option><option value="文章内部">文章内部</option><option value="文章底部">文章底部</option><option value="专家链接">专家链接</option><option value="中间弹出">中间弹出</option><option value="主导航">主导航</option><option value="其它">其它</option></select></div><div>事件操作：<select id="a_name" style="width:150px;"><option value="打开">打开</option><option value="关闭">关闭</option><option value="拨打电话">拨打电话</option></select></div><div>事件标签：<input type="text" id="a_title" /></div><div>链接地址：<input type="text" id="a_url" /></div>');
    $("#a_stn").one("click",function(){
      if($("#a_data").val()=='' || $("#a_url").val()==''){
        alert('请填写完整');
        return false;
      }
      var rel = $("#a_rel").val();
	  var name = $("#a_name").val();
	  var title = $("#a_title").val();
      $("div.s_red").append("<a href='javascript:;' target='_blank' from='"+$("#a_url").val()+"' rel='"+rel+"' name='"+name+"' title='"+title+"' class='swt_a'></a>");

      $("#pop_wrap,#s_filter").fadeOut();
      var index = $("div.s_red>a.swt_a").length-1;//a标签的索引数量
      bindResize($('div.s_red>.swt_a')[index]);
    });
  });

  $("#s_off").click(function(){//点击关闭弹窗
    $("#pop_wrap").css({'width':'350px','top':'220px'});
    $("#pop_wrap,#s_filter").fadeOut();
  });

  $("#a_rtn").live("click",function(){//重置按钮，清空文本框，因有新增加数据要用live函数
    $("#pop input").each(function(){
      $(this).val('');
    });
  });

  $("#s_btn8").click(function(){//增加关闭按钮
    if($("div.s_red>span.swt_off").length){
      if(confirm('确定要删除选中部分的关闭按钮?')){
        $("div.s_red>span.swt_off").remove();
      }
      return false;
    }
    $("div.s_red").append('<span class="swt_off" title="close"></span>');
    var index = $("div.s_red>span.swt_off").length-1;
    bindResize($('div.s_red>.swt_off')[index]);
  });

  $("#s_btn9").click(function(){//弹窗间隔时间设置
    var par = $("div.s_red").closest('div.swt_wrap');
    if(!$("div.s_red>span.swt_off").length){
      alert('没有关闭按钮，不能设置弹窗时间');
      return false;
    }
    if(par.attr('time1')){
      var time1 = par.attr('time1');
      var time3 = par.attr('time2');
    }else{
      var time1 = '';
      var time3 = '';
    }
    $("#pop_wrap").fadeIn('fast');
    $("#s_filter").fadeTo('normal',0.4);
    $("#pop").html('<div>第一次弹出：<input type="text" id="a_data" value="'+time1+'" />秒</div><div>第二次弹出：<input type="text" id="a_url" value="'+time3+'" />秒</div>');
    $("#a_stn").one("click",function(){
      if($("#a_data").val()=='' || $("#a_data").val()==0){
        if(par.attr('time1')>0){
          if(confirm('确定要删除按时间弹出的功能？')){
            par.attr('time1','');
            par.attr('time2','');
            par.removeClass('swt_h');
          }
        }else{
          alert('数值不能为空、不能为0');
        }
        $("#pop_wrap,#s_filter").fadeOut();
        return false;
      }
      if($("#a_url").val()=='')
        var time2 = '';
      else
        var time2 = $("#a_url").val();
      $("div.s_red").closest("div.swt_wrap").attr({'time1':$("#a_data").val(),'time2':time2}).addClass('swt_h');
      $("#pop_wrap,#s_filter").fadeOut();
    });
  });

  $("#s_btn10").click(function(){//修改html和js
    $("#pop_wrap").css({'width':'90%','top':'30px'}).fadeIn('fast');
    $("#s_filter").fadeTo('normal',0.4);
    var all_str = $("#js_content").html();
    $("#pop").html('<div id="show_help">修改html和js？ <span style="color:#aaa">▼</span> ( <span onClick="window.open(\'http://tool.lanrentuku.com/jsformat/\')" class="span_a">格式化网址</span> )<p id="hide_help">1、格式化网址中有在线格式源码功能<br />2、推荐在第三方平台或者编辑器编辑此代码<br />3、尽量不要修改原来的div与css,新增的div与css也不能与其重复<br />3、&lt;script&gt;&lt;/script&gt; 这样增加的js在系统js执行之前执行<br />4、&lt;script&gt;$(function(){});&lt;/script&gt; 此js在系统js后执行 <br />5、可以选择性的去掉top、left数字后面的小数<br />6、在编辑完后必须压缩成“一行”</p></div><div style="height:auto;overflow:hidden;text-align:center"><textarea id="a_css" style="width:99.5%;height:400px;margin:0 auto;">'+all_str+'</textarea></div>');

    $("#show_help").hover(function(){
      $("#hide_help").stop().slideToggle('fast');
    });

    $("#a_stn").one("click",function(){
      $("#js_content").html(htmlspecialchars($("#a_css").val()));
      $("#pop_wrap").css({'width':'350px','top':'220px'});
      $("#pop_wrap,#s_filter").fadeOut();
    });
  });//<!--}}}-->


  $("#s_btn11").click(function(){//预览商务通{{{
    var width = $(window).width()*1;
    $("div.swt_img").each(function(){
      var left = $(this).css('left')=='auto' ? 'auto':parseInt($(this).css('left'));//把带px的字符转换成数字
      var right = $(this).css('right')=='auto' ? 'auto':parseInt($(this).css('right'));
      if(left>0 && right>=0){
        if($(this).closest('.swt_right_cen').length>0){
          $(this).css({right:'auto'});//没点"右边"按钮right应该为auto
        }else{
          var this_width = $(this).width()*1;
          $(this).css({left:'auto',right:(width-left-this_width)+'px'});//在点击"右边"按钮后得把left转换成right
        }
      }
    });
    var swt_id = $(this).attr('swt_id');
    var t_index = $("div.s_red").closest('div.swt_wrap').index();
    $("div.s_red").removeClass('s_red');

    /*if($("a.swt_a").length>0){
    var data = new  Array();
    var link = new Array(); 
    $('a.swt_a').each(function(nn){
      data[nn] = '"'+$(this).attr('data')+'"';
      link[nn] = '"'+$(this).attr('from')+'"';
      link[nn] = link[nn].replace(/&/g,'##');
    });
    $.ajax({
      url: '/?c=swt&m=swt_ins',
      type: 'post',
      dataType: 'json',
      data: 'data='+data+'&swt_id='+swt_id+'&link='+link,
      async: false,//同步传输才能给外面的变量传值
      success: function(json){
        if(json.click_id!=0){
          $('a.swt_a').each(function(e){
            $(this).attr('click_id',json.click_id*1+e);
          });
        }
      },
      error: function(){
        ajax_by_form('/?c=swt&m=swt_ins','data='+data+'&swt_id='+swt_id+'&link='+link);
      }
    });
    }*/

    $('a.swt_a,span.swt_off').css('cursor','pointer');
    var js_con = $("#js_content").html();
    js_con = js_con.replace(/&/g,'##');
    $("div.swt_wrap").eq(t_index).find(".swt_img").addClass('s_red');
    js_con = js_con.replace(/ left: auto;| LEFT: auto;| left: auto| LEFT: auto| right: auto;| right: auto| RIGHT: auto;| RIGHT: auto|\r\n/g,'');//去掉没必要的样式
    $.ajax({
      url: '/?c=swt&m=swt_js',
      type: 'post',
      dataType: 'json',
      data: 'swt_js='+js_con+'&swt_id='+swt_id,
      async: false,//同步传输才能给外面的变量传值
      success: function(json){
        if(json.data==1)
          window.open('/?c=swt&m=swt_html&swt_id='+swt_id);
      },
      error: function(){
        alert('数据调用出错!');
      }
    });
  });//}}}

function ajax_by_form(){
  var url = arguments[0];
  var data = arguments[1]? arguments[1] : '';
  if(data!=''){
   var input='';
   var arr=data.split('&');
   var key;
   for(var i=0 ; i< arr.length ; i++){
     key=arr[i].split('=');
     input+='<input type="text" name="'+key['0']+'" value="'+key[1]+'" />';
   }
   $('body').append('<form id="ajax_form2" target="_blank" method="post" action="'+url+'">'+input+'</form>');
   $("#ajax_form2").submit();
  }
}


$("#s_btn12").click(function(){//编辑商务通
  var htm = '';
  var from = '';
  var rel = '';
  var title = '';
  var name = '';
  var option_str;
  var option_str2;
  var cat_arr = ['右侧漂浮', '左侧漂浮', '右栏固定', '右栏浮动', '左栏固定', '左栏浮动', '右下漂浮', '左下漂浮', '顶部通栏', '底部通栏', '文章头部', '文章内部', '文章底部', '专家链接', '中间弹', '主导航', '其它'];
  var action_arr = ['打开','关闭','拨打电话'];
  $('div.s_red a.swt_a').each(function(){
    from = $(this).attr('from');
    rel = ($(this).attr('rel') == "undefined")? 'rel':$(this).attr('rel');
    title = ($(this).attr('title') == "undefined")? 'title':$(this).attr('title');
    name = ($(this).attr('name') == "undefined")? 'name':$(this).attr('name');
    option_str = '';
    option_str2 = '';
    for (var i = 0, l = cat_arr.length; i < l; i++) {
      if(rel==cat_arr[i])
        option_str += '<option value="'+cat_arr[i]+'" selected>'+cat_arr[i]+'</option>';
      else
        option_str += '<option value="'+cat_arr[i]+'">'+cat_arr[i]+'</option>';
    }
    option_str2 = '';
    for (var i = 0, l = action_arr.length; i < l; i++) {
      if(name==action_arr[i])
        option_str2 += '<option value="'+action_arr[i]+'" selected>'+action_arr[i]+'</option>';
      else
        option_str2 += '<option value="'+action_arr[i]+'">'+action_arr[i]+'</option>';
    }
    htm += '<div>事件类别：<select class="rel1" style="width:198px;">'+option_str+'</select></div><div>事件操作：<select class="name1" style="width:198px;">'+option_str2+'<option value="" style="color:red">清徐a标签</option></select></div><div>事件标签：<input type="text" class="title1" style="width:198px;" value="'+title+'" /></div><div>链接地址：<input type="text" class="href1" value="'+from+'" style="width:198px" /></div>';
  });
  $("#pop_wrap").fadeIn('fast');
  $("#s_filter").fadeTo('normal',0.4);
  var img_src = $("div.s_red").css("background-image").replace('url(<?php echo SITE_URL;?>','').replace(')','');
  $("#pop").html(htm+'<div><iframe src="" name="iframe" style="display:none" iframeborder="0"></iframe><form method="post" action="/?c=swt&m=upload_img" enctype="multipart/form-data" id="upload_img" target="iframe">更新背景：<input type="file" name="userfile" style="width:153px" /><a href="javascript:;" style="line-height:16px;margin:0 2px" id="a_add">+</a> <a href="javascript:;" style="line-height:16px;margin:0 2px" id="a_del">X</a><input type="hidden" value="<?php echo $swt_id;?>" name="swt_id" /><input type="hidden" name="img_src" value="'+img_src+'" /></form></div>');
  $("#a_add").click(function(){
    $("#upload_img").attr('action','/?c=swt&m=upload_add').submit();
    $("#pop_wrap,#s_filter").fadeOut();
  });
  $("#a_del").click(function(){
    if(confirm('确定要删除图片?')){
      $("#upload_img").attr('action','/?c=swt&m=upload_del').submit();
      $("#pop_wrap,#s_filter").fadeOut();
    }
  });
  $("#a_stn").one('click',function(){
    if($("input:file").val()!=''){
      $("#upload_img").submit();
    }
    var swt_id_str = '';
    $('div.s_red a.swt_a').each(function(nn){
      if($('select.name1').eq(nn).val()==''){
        /*if(swt_id_str=='')
          swt_id_str += $(this).attr('click_id');
        else
          swt_id_str += ','+$(this).attr('click_id');
          */
        $('a.swt_a').eq(nn).remove();
      }else{
        $(this).attr('from',$('input.href1').eq(nn).val());
        $(this).attr('rel',$('select.rel1').eq(nn).val());
        $(this).attr('name',$('select.name1').eq(nn).val());
        $(this).attr('title',$('input.title1').eq(nn).val());
      }
    });
    $("#pop_wrap,#s_filter").fadeOut();
    if(swt_id_str==''){
      return false;
    }
    /*$.ajax({
url: '/?c=swt&m=swt_a_del',
type: 'post',
dataType: 'json',
data: 'click_id='+swt_id_str,
async: false,//同步传输才能给外面的变量传值
success: function(json){
    //alert(json.status);
    },
error: function(){
alert('数据调用出错!');
}
});*/
});
});

if(!$("div.s_red").length){//如果不存在选中（.s_red）状态，则选中第一个swt_img
  $("div.swt_img").eq(0).addClass('s_red');
}


function htmlspecialchars (string) {//替换js起始与结束标签，防止执行
  string = string.replace('<script>','&lt;script&gt;');
  string = string.replace('<script type="text\/javascript">','&lt;script type="text\/javascript"&gt;');
  string = string.replace("<script type='text\/javascript'>","&lt;script type='text\/javascript'&gt;");
  string = string.replace('<\/script>','&lt;/script&gt;');
  return string;
}


  /*$(document).bind('keydown', 'f5',function (evt){
    evt = window.event||evt;
    if(evt.keyCode==116){
      if(confirm('是否预览保存之后再刷新页面?')){
        evt.keyCode=0;
        $("#s_btn11").click();
        return false;
      }
    }
  });*/
});
    </script>
    <!--{{{-->
      <style type="text/css">
      body{font-size:12px;padding:0;margin:0}
      a{color:#111;text-decoration:none}
      .s_border{-moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px;-moz-box-shadow:0px 4px 5px #9C9C9C; -webkit-box-shadow:0px 4px 5px #9C9C9C; box-shadow:0px 4px 5px #9C9C9C;behavior: url(/static/js/pie.htc);background:#EFF;position:fixed;top:500px;right:0;left:0;width:350px;margin:0 auto;z-index:990;_position:absolute;_top:expression(documentElement.scrollTop);height:120px;overflow:hidden;display:block;text-align:center}
      .s_red{-moz-border-radius:7px; -webkit-border-radius:7px; border-radius:7px;-moz-box-shadow:0px 6px 7px #F00; -webkit-box-shadow:0px 6px 7px #F00; box-shadow:0px 6px 7px #F00;behavior:url(/static/js/pie.htc);background:#efefef;cursor:move}
      .s_border a{background:#87bb33;color:#FFF;margin:15px 10px;padding:3px 5px;display:inline-block}
      .s_border a:hover {background:#78a300}
      #s_filter{filter:alpha(Opacity=30);position:relative;width:100%;height:100%;background:#000;opacity:0.3;z-index:99998}
      #s_off{width:11px;height:11px;margin:2px;cursor:pointer;float:right}
      #pop{clear:both;padding:9px 9px 0}
      #pop div{height:30px;line-height:30px}
      #pop input{border:1px solid #DDD}

      .swt_wrap{position:fixed;z-index:90;_position:absolute;display:block}
      .swt_a{width:39px;height:25px;background:#F00;position:absolute;left:0;top:0;cursor:se-resize;display:inline-block}
      a.swt_a:hover{background:#fe8989}
      .swt_off,.swt_num{cursor:se-resize;width:15px;height:15px;overflow:hidden;display:block;left:0;top:0;background:#FF0;position:absolute}
      .swt_num{background:#87bb33}
      .swt_wrap{position:fixed;_position:absolute;width:100%}
      .swt_top{top:0;_top:expression(documentElement.scrollTop);}
      .swt_bottom{bottom:0;_bottom:auto;_top:expression(eval(documentElement.scrollTop+documentElement.clientHeight+this.offsetHeight))}
      .swt_left_cen .swt_cen{width:1000px;margin:0 auto;height:0;display:block;position:relative}
      .swt_left_cen .swt_img{left:0}
      .swt_right_cen .swt_cen{width:1000px;margin:0 auto;height:0;display:block;position:relative}
      .swt_img{position:absolute}
      </style>
      <!--}}}-->
      </head>
      <body style="height:1900px"><!--{{{-->
        <div id="js_content">
        <?php echo $html = str_replace('##','&',$html);;?>
        </div>
        <div class="s_border" style="position:absolute; z-index:99999;">
        <a href="javascript:;" id="s_btn1">左边</a><a href="javascript:;" id="s_btn2" title="靠近网站内容左边">左中</a><a href="javascript:;" id="s_btn3" title="靠近网站内容右边">右中</a><a href="javascript:;" id="s_btn4">右边</a><a href="javascript:;" id="s_btn5">头部</a><a href="javascript:;" id="s_btn6">底部</a><a href="javascript:;" id="s_btn7" title="增加链接">链接</a><a href="javascript:;" id="s_btn8" title="增加删除关闭按钮">关闭</a><a href="javascript:;" id="s_btn9" title="增加删除弹出时间">时间</a><a href="javascript:;" id="s_btn10" title="修改html和js">源码</a><a href="javascript:;" id="s_btn11" swt_id="<?php echo $swt_id;?>" title="预览保存">预览</a><a href="javascript:;" id="s_btn12" title="编辑图片和链接">编辑</a>
        </div>
        <div id="pop_wrap" class="swt_wrap s_border" style="width:350px;height:auto;display:none;z-index:99999;top:220px"><span id="s_off" title="关闭"><img src="/static/images/swt_off.jpg" /></span>
        <div id="pop"></div>
        <div><a href="javascript:;" id="a_stn">提交</a><a href="javascript:;" id="a_rtn">重置</a></div>
        </div>
        <div id="s_filter" style='display:none'></div>
        <div id="html"></div>
    </body><!--}}}-->
    </html>
