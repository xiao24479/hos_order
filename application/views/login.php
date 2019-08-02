<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta name="robots" content="NOINDEX,NOFOLLOW,noarchive">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0,user-scalable=no">
    <title>恒信健网络预约挂号管理系统</title>
    <link rel="stylesheet" href="/static/css/login.css">
</head>
<body>
<div class="container">
    <div class="header">
        <img src="/static/img/login/logo.png">
    </div>
    <div class="body">
        <div class="pannel">
            <div class="tit">
                <img src="/static/img/login/tit.png">
            </div>
            <div class="form-box">
                <form class="form" action="?c=index&m=login_ck"method="post" id="login_submit">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-icon input-icon-user" ></div>
                            <input type="text" placeholder="用户名" autocomplete="off" class="form-control" name="admin_username"  id="admin_username">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-icon input-icon-lock" ></div>
                            <input type="password" placeholder="密码" autocomplete="off" class="form-control" name="admin_password">
                        </div>
                    </div>
                    <button type="button" onclick="this.form.submit();" id="login_button" >登陆</button>
                    <div class="f_o">
                        <div class="f_lb">
                            <label for="remeber"><input type="checkbox" id="remeber" name="remeber">下次自动登录</label>
                        </div>
                        <div class="f_rb">
                            <a href="javascript:;" onclick="alert('亲，忘记密码时，不要着急，请联系技术部相关人员哦！');">忘记密码？</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>维护：恒信健网络科技有限公司</p>
    </div>
</div>
<script language="javascript" type="text/javascript"  src="static/js/jquery.js"></script>
<script language="javascript" type="text/javascript">
    $(function(){
        $('#admin_username').bind('input propertychange', function() {
            $.ajax({
                type:'post',
                url:'?c=index&m=check_user_name',
                data:'username='+$(this).val(),
                success:function(data)
                {
                    if(data == 0){
                        $("#login_button").removeAttr("disabled");
                        $("#login_button").attr("style","background-color:#00a186;cursor:pointer");
                        $("#login_button").text("登陆");
                    }else if(data == 3){
                        $("#login_button").attr("disabled","disabled");
                        $("#login_button").attr("style","background-color:#ec4f4f;cursor:not-allowed;");
                        $("#login_button").text("账号为空");
                    }else if(data == 4){
                        $("#login_button").attr("disabled","disabled");
                        $("#login_button").attr("style","background-color:#ec4f4f;cursor:not-allowed;");
                        $("#login_button").text("账号不存在");
                    }
                },
                complete: function (XHR, TS)
                {
                    XHR = null;
                }
            });
        });

        $(document).keydown(function (event) {
            if(event.keyCode == 13 && $('#login_button').text() == '登陆'){
                $("#login_submit").submit();
            }
        });

    });
</script>
</body>
</html>