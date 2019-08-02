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

<link rel="stylesheet" href="static/vendor/layui/css/layui.css"  media="all">

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
              <button data-method="add_bz" class="layui-btn" >添加病种</button>
              <button class="layui-btn layui-btn-demoTable" data-type="getCheckData">批量删除</button>
<!--               <button class="layui-btn layui-btn-demoTable" data-type="getCheckLength">获取选中数目</button>
              <button class="layui-btn layui-btn-demoTable" data-type="isAll">验证是否全选</button> -->
            </div>

            <table class="layui-table" lay-data="{height:'full-180', url:'?c=report&m=bz_list_ajax', id:'idTest'}" lay-filter="demo">
              <thead>
                <tr>
                  <th lay-data="{type:'checkbox'}"></th>
                  <th lay-data="{field:'id', align: 'center'}">ID</th>
                  <th lay-data="{field:'bz_name_c', align: 'left'}">病种</th>
                  <th lay-data="{field:'order', align: 'center'}">排序</th>
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

  <script>

  layui.use('layer', function(){ //独立版的layer无需执行这一句
    var $ = layui.jquery, layer = layui.layer; //独立版的layer无需执行这一句

    //触发事件
    var active = {
      add_bz: function(){
        var that = this;
        //多窗口模式，层叠置顶
        parent.layer.open({
          type: 2,
          title: '添加病种',
          shadeClose: true,
          shade: 0.8,
          area: ['500px', '500px'],
          content: '?c=report&m=add_bz' //iframe的url
        });
      }
    };

    $('.layui-btn').on('click', function(){
      var othis = $(this), method = othis.data('method');
      active[method] ? active[method].call(this, othis) : '';
    });

  });


  layui.use('table', function(){
    var table = layui.table;

    //监听表格复选框选择
    table.on('checkbox(demo)', function(obj){
      console.log(obj)
    });
    //监听工具条
    table.on('tool(demo)', function(obj){
      var data = obj.data;
      if(obj.event === 'del'){
        layer.confirm('真的删除 <font color=red size=3>'+$.trim(data.bz_name)+'</font> 么?', function(index){
          $.ajax({//异步请求返回给后台
            url:'?c=report&m=delete_bz_ajax',
            type:'post',
            data:{id:data.id},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg('删除成功！', {icon: 1,time: 1000});
                obj.del();
                layer.close(index);
                // setTimeout(function(){
                //   window.parent.location.reload();
                // },1000);
              } else {
                layer.msg('删除失败！', {icon: 5,time: 1000});
              }
            }
          });
        });
      } else if(obj.event === 'edit'){
        //layer.alert('编辑行：<br>'+ JSON.stringify(data))
        layer.open({
          type: 2,
          title: '编辑病种',
          shadeClose: true,
          shade: 0.8,
          area: ['500px', '500px'],
          content: '?c=report&m=edit_bz&id='+data.id
        });
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
          arr_name.push(data[x].bz_name);
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
            url:'?c=report&m=delete_batch_bz_ajax',
            type:'post',
            data:{ids:ids},
            dataType:'json',
            success:function(res){
              //console.log(res);
              if (res.code == 1) {
                layer.msg('删除成功！', {icon: 1,time: 1000});
                setTimeout(function(){
                  window.parent.location.reload();
                },1000);
              } else {
                layer.msg('删除失败！', {icon: 5,time: 1000});
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
