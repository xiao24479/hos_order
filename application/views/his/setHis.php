<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->


<head>

<meta charset="utf-8" />

<title><?php echo $admin['name'] . '-' . $title; ?></title>

<meta content="width=device-width, initial-scale=1.0" name="viewport" />

<meta content="" name="description" />

<meta content="" name="author" />

<link href="static/css/bootstrap.min.css" rel="stylesheet" />

<link href="static/css/font-awesome.css" rel="stylesheet" />

<link href="static/css/style.css" rel="stylesheet" />

<link href="static/css/style-default.css" rel="stylesheet" id="style_color" />

<link rel="stylesheet" href="static/vendor/layui/css/layui.css" >

</head>

<body class="fixed-top" style="width:100%;margin:0 auto; ">

  <!--遮罩层使用 div -->
  <div id="mask" class="mask"></div>


  <?php echo $top; ?>

  <div id="container" class="row-fluid" >

    <?php echo $sider_menu; ?>

    <div id="main-content" style="margin-left:180px;background-color:#fff;overflow-x: hidden;">

      <!-- BEGIN PAGE CONTAINER-->
      <div class="container-fluid">
        <!-- BEGIN PAGE HEADER-->
        <?php echo $themes_color_select; ?>
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">

            <div class="layui-btn-group" style="margin-top: 10px;">
              <button data-method="add_his" class="layui-btn" >添加HIS</button>
              <button class="layui-btn layui-btn-demoTable" data-type="getCheckData">批量删除</button>
<!--               <button class="layui-btn layui-btn-demoTable" data-type="getCheckLength">获取选中数目</button>
              <button class="layui-btn layui-btn-demoTable" data-type="isAll">验证是否全选</button> -->
            </div>

            <table class="layui-table" lay-data="{height:'full-180', url:'?c=his&m=ListHisAjax', id:'idTest'}" lay-filter="demo">
              <thead>
                <tr>
                  <th lay-data="{type:'checkbox'}"></th>
                  <th lay-data="{field:'id', align: 'center'}">ID</th>
                  <th lay-data="{field:'his_item', align: 'left'}">HIS名</th>
                  <th lay-data="{field:'cname', align: 'left'}">别名</th>
                  <th lay-data="{field:'his_server', align: 'left'}">服务器</th>
                  <th lay-data="{field:'his_db', align: 'left'}">数据库</th>
                  <th lay-data="{field:'his_user', align: 'left'}">用户名</th>
                  <th lay-data="{field:'his_pwd', align: 'left'}">密码</th>
                  <th lay-data="{field:'hos_id', align: 'center'}">所属医院</th>
                  <th lay-data="{field:'show', align: 'center'}">状态</th>
                  <th lay-data="{align:'center', toolbar: '#barDemo',fixed: 'right'}">操作</th>
                </tr>
              </thead>
            </table>

            <script type="text/html" id="barDemo">
              <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
              <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            </script>

  </div>

</div>

  <script src="static/js/jquery-1.8.3.min.js"></script>

  <script src="static/js/jquery.nicescroll.js" type="text/javascript"></script>

  <script src="static/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="static/js/jquery.slimscroll.min.js"></script>

  <!-- ie8 fixes -->

  <!--[if lt IE 9]>

  <script src="static/js/excanvas.js"></script>

  <script src="static/js/respond.js"></script>

  <![endif]-->

  <script src="static/js/common-scripts.js"></script>

  <script type="text/javascript" src="static/js/date.js"></script>

  <script type="text/javascript" src="static/js/daterangepicker.js"></script>

  <script src="static/js/datepicker/js/datepicker.js"></script>

  <script src="static/vendor/layui/layui.js"></script>

  <style>
    .popup-box{
      padding: 20px;
    }
    .popup-box .layui-input, .popup-box .layui-select, .layui-textarea {
        height: 38px;
        line-height: 1.3;
        line-height: 38px\9;
        border-width: 1px;
        border-style: solid;
        background-color: #fff;
        border-radius: 2px;
        padding-left: 10px;
    }
    .popup-box .layui-form-switch{
      border-radius: 20px !important;
    }
  </style>

  <script>

  layui.use(['layer', 'form', 'element','jquery'],  function(){ //独立版的layer无需执行这一句
    var $ = layui.jquery,
        layer = layui.layer,
        form = layui.form,
        element = layui.element //独立版的layer无需执行这一句

    //触发事件
    var active = {
      add_his: function(){
        var that = this;

        var __HTML = "  <div class=\"popup-box\"><form class=\"layui-form\" onsubmit=\"return false;\">"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">HIS名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"his_item\" required  lay-verify=\"required\" placeholder=\"请输入HIS名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">别名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"cname\" required  lay-verify=\"required\" placeholder=\"请输入别名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">服务器</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"his_server\" required  lay-verify=\"required\" placeholder=\"请输入服务器IP\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">数据库</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"his_db\" required  lay-verify=\"required\" placeholder=\"请输入数据库\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">用户名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"his_user\" required  lay-verify=\"required\" placeholder=\"请输入用户名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">密码</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" name=\"his_pwd\" required  lay-verify=\"required\" placeholder=\"请输入密码\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"   <div class=\"layui-form-item\">"+
"     <label class=\"layui-form-label\">所属医院</label>"+
"     <div class=\"layui-input-block\">"+
"       <?php if (!empty($hospital)) { foreach ($hospital as $key => $value) { ?>"+
"         <input type=\"checkbox\" name=\"hos_id[<?php echo $key ?>]\" value=\"<?php echo $value['hos_id'] ?>\" title=\"<?php echo $value['hos_name'] ?>\">"+
"       <?php }} ?>"+
"     </div>"+
"   </div>"+
"   <div class=\"layui-form-item\">"+
"     <label class=\"layui-form-label\">启用</label>"+
"     <div class=\"layui-input-block\">"+
"         <input type=\"checkbox\" checked=\"\" name=\"is_show\" lay-skin=\"switch\" lay-text=\"开|关\">"+
"     </div>"+
"   </div>"+
"   <div class=\"layui-form-item\">"+
"     <div class=\"layui-input-block\">"+
"         <button class=\"layui-btn\" lay-submit lay-filter=\"addHis\">立即提交</button>"+
"         <button type=\"reset\" class=\"layui-btn layui-btn-primary\">重置</button>"+
"     </div>"+
"   </div>"+
" </form></div>";

        layer.open({
          type: 1,
          title: '添加HIS',
          shade: 0.8,
          area: ['700px', '600px'], //宽高
          content: __HTML
        });
        form.render();
      }
    };

    $('.layui-btn').on('click', function(){
      var othis = $(this), method = othis.data('method');
      active[method] ? active[method].call(this, othis) : '';
    });

  });


  layui.use('form', function(){
    var form = layui.form;
    var $$ = layui.jquery;
    //监听提交
    form.on('submit(addHis)', function(data){
      var main = data.field;
    $$.ajax({//异步请求返回给后台
      url:'?c=his&m=addHisAjax',
      type:'post',
      data:main,
      dataType:'json',
      success:function(res){
        if (res.code == 1) {
          layer.msg(res.msg, {icon: 1});
          setTimeout(function(){
            window.location.reload();
          },2000);
        } else {
          layer.msg(res.msg, {icon: 5});
        }
      }
    });
    return false;
    });
  });


  layui.use('form', function(){
    var form = layui.form;
    var $$ = layui.jquery;
    //监听提交
    form.on('submit(editHis)', function(data){
      var main = data.field;
    $$.ajax({//异步请求返回给后台
      url:'?c=his&m=editHisAjax',
      type:'post',
      data:main,
      dataType:'json',
      success:function(res){
        if (res.code == 1) {
          layer.msg(res.msg, {icon: 1});
          setTimeout(function(){
            window.location.reload();
          },2000);
        } else {
          layer.msg(res.msg, {icon: 5});
        }
      }
    });
    return false;
    });
  });



  layui.use(['layer', 'form', 'element','jquery','table'], function(){
    var $ = layui.jquery,
        layer = layui.layer,
        form = layui.form,
        element = layui.element,
        table = layui.table;

    //监听表格复选框选择
    table.on('checkbox(demo)', function(obj){
      //console.log(obj)
    });
    //监听工具条
    table.on('tool(demo)', function(obj){
      var data = obj.data;
      if(obj.event === 'del'){
        layer.confirm('真的删除 <font color=red size=3>'+$.trim(data.his_item)+'</font> 么?', function(index){
          $.ajax({//异步请求返回给后台
            url:'?c=his&m=delHisAjax',
            type:'post',
            data:{id:data.id},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg(res.msg, {icon: 1,time: 1000});
                obj.del();
                // setTimeout(function(){
                //   window.parent.location.reload();
                // },1000);
              } else {
                layer.msg(res.msg, {icon: 5,time: 1000});
              }
            }
          });
        });
      } else if(obj.event === 'edit'){
        //layer.alert('编辑行：<br>'+ JSON.stringify(data))
        var is_show_judge;
        if(data.is_show == 1) {
          is_show_judge ="<input type=\"checkbox\" checked=\"\" name=\"is_show\" lay-skin=\"switch\" lay-text=\"开|关\">";
        } else {
          is_show_judge ="<input type=\"checkbox\" name=\"is_show\" lay-skin=\"switch\" lay-text=\"开|关\">";
        }

        var __HTMLEDIT = "  <div class=\"popup-box\"><form class=\"layui-form\" onsubmit=\"return false;\">"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">HIS名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" value=\""+data.his_item+"\" name=\"his_item\" required  lay-verify=\"required\" placeholder=\"请输入HIS名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">别名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" value=\""+data.cname+"\" name=\"cname\" required  lay-verify=\"required\" placeholder=\"请输入别名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">服务器</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" value=\""+data.his_server+"\" name=\"his_server\" required  lay-verify=\"required\" placeholder=\"请输入服务器IP\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">数据库</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" value=\""+data.his_db+"\" name=\"his_db\" required  lay-verify=\"required\" placeholder=\"请输入数据库\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">用户名</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\"  value=\""+data.his_user+"\" name=\"his_user\" required  lay-verify=\"required\" placeholder=\"请输入用户名\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"     <div class=\"layui-form-item\">"+
"       <label class=\"layui-form-label\">密码</label>"+
"       <div class=\"layui-input-block\">"+
"           <input type=\"text\" value=\""+data.his_pwd+"\" name=\"his_pwd\" required  lay-verify=\"required\" placeholder=\"请输入密码\" autocomplete=\"off\" class=\"layui-input\">"+
"       </div>"+
"     </div>"+
"   <div class=\"layui-form-item\">"+
"     <label class=\"layui-form-label\">所属医院</label>"+
"     <div class=\"layui-input-block\" id=\"belongHos\">"+
"     </div>"+
"   </div>"+
"   <div class=\"layui-form-item\">"+
"     <label class=\"layui-form-label\">启用</label>"+
"     <div class=\"layui-input-block\">"+ is_show_judge +
"     </div>"+
"   </div>"+
"   <div class=\"layui-form-item\">"+
"     <div class=\"layui-input-block\">"+
"         <input type=\"hidden\"  value=\""+data.id+"\" name=\"id\">"+
"         <button class=\"layui-btn\" lay-submit lay-filter=\"editHis\">立即提交</button>"+
"     </div>"+
"   </div>"+
" </form></div>";

        layer.open({
          type: 1,
          title: '编辑HIS',
          shade: 0.8,
          area: ['700px', '600px'], //宽高
          content: __HTMLEDIT,
          success: function(layero, index){
            $.ajax({
              url: '?c=his&m=defaultHosAjax',
              type: 'POST',
              dataType: 'html',
              data: {id: data.id},
              success:function(res){
                $('#belongHos').append(res);
                form.render();
              }
            })
          }
        });
        form.render();
      }
    });

    var $ = layui.$, active = {
      getCheckData: function(){ //获取选中数据
        var checkStatus = table.checkStatus('idTest')
        ,data = checkStatus.data;
        //layer.alert(JSON.stringify(data));
        var x
        var arr_id = new Array();
        var arr_name = new Array();
        for (x in data){
          arr_id.push(data[x].id);
          arr_name.push(data[x].his_item);
        }
        ids = arr_id.toString();
        ids = ids.substr(0,ids.length-1);
        console.log(ids);
        name = arr_name.toString();
        name = name.substr(0,name.length-1);
        console.log(name);

        if(ids ==""){
            layer.alert("请选择你要删除的记录再进行此操作！");
            return false;
        }
        layer.confirm('真的删除 <font color=red size=3>'+$.trim(name)+'</font> 么?', function(index){
          $.ajax({//异步请求返回给后台
            url:'?c=his&m=delBatchHisAjax',
            type:'post',
            data:{ids:ids},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg(res.msg, {icon: 1,time: 1000});
                setTimeout(function(){
                  window.parent.location.reload();
                },1000);
              } else {
                layer.msg(res.msg, {icon: 5,time: 1000});
              }
            }
          });
        })

      }
      ,getCheckLength: function(){ //获取选中数目
        var checkStatus = table.checkStatus('idTest')
        ,data = checkStatus.data;
        layer.msg('选中了：'+ data.length + ' 个');
      }
      ,isAll: function(){ //验证是否全选
        var checkStatus = table.checkStatus('idTest');
        layer.msg(checkStatus.isAll ? '全选': '未全选')
      }
    };

    $('.layui-btn-demoTable').on('click', function(){
      var type = $(this).data('type');
      active[type] ? active[type].call(this) : '';
    });
  });

  </script>




  </body>
</html>
