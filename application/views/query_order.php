<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-tap-highlight" content="no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo $title;?>-预约信息查询网页版</title>
    
    <link type="text/css" href="https://mobile.cmbchina.com/MobileHtml/Base/doc/styles/LoginNew.css" rel="Stylesheet">
    <link id="SwtichCssLink" type="text/css" rel="Stylesheet" href="https://mobile.cmbchina.com/MobileHtml/Base/doc/styles/webkit/switch.css">
    
    <script type="text/javascript" src="http://www.ra120.com/new_statics/js/jquery.js"></script>
</head>
<body>
 
    <div id="Main">
        
        
        <div id="TitleBar">
        
            
            <div id="LogoBg_Topbar">
                <div id="Logo" style="background: url('http://www.renaidata.com/static/images/logo.png') no-repeat;">
                </div>
            </div>
            
        </div>
        
        
        
        <div id="InputBlock">
        
            <form name="form1"  action="?c=weixin&m=get_order" method="post">
            <div id="InputInfoTable">
            
                
                <div class="line5px"></div>
                
                
                <table id="LgByPidInput" class="InputLine">
                    <tbody>
                        <tr>
                            <td class="left" align="right">
                                <table class="AutoAlignLabel">
                                    <tbody><tr>
                                        <td class="BeginWord">手</td>
                                        <td class="MidWord">机</td>
                                        <td class="MidWord">号</td>
                                        <td class="EndWord">码</td>
                                    </tr>
                                </tbody></table>
                            </td>
                            <td class="right" align="left">
                                <input type="text" class="NoneInput90" id="DIDNoCNotice" value="请输入手机号码"   onfocus="if(value=='请输入手机号码'){value=''}" onblur="if(value==''){value='请输入手机号码'}" style="display: inline;">
                            </td>
                        </tr>
                    </tbody>
                </table>
   
                
                <div class="line0px"></div>
                
                
                <table class="InputLine">
                    <tbody>
                        <tr>
                            <td class="left" align="right">
                                <table class="AutoAlignLabel">
                                    <tbody><tr>
                                        <td class="BeginWord">预</td>
                                        <td class="MidWord">约</td>
                                        <td class="EndWord">码</td>
                                    </tr>
                                </tbody></table>
                            </td>
                            <td class="right" align="left">
                                <input type="text" class="NoneInput90" id="PwdCNotice" value="请输入预约码" onfocus="if(value=='请输入预约码'){value=''}" onblur="if(value==''){value='请输入预约码'}" style="display: inline;">
                            </td>
                        </tr>
                    </tbody>
                </table>
                                
                
                
                <div class="line0px"></div>
                
                
                <table class="InputLine">
                    <tbody>
                        <tr>
                            <td class="left" align="right">
                                <table class="AutoAlignLabel">
                                    <tbody><tr>
                                        <td class="BeginWord">附</td>
                                        <td class="MidWord">加</td>
                                        <td class="EndWord">码</td>
                                    </tr>
                                </tbody></table>
                            </td>
                            <td class="right" style="width: 123px;" align="left">
                                <input type="text" class="NoneInput90" id="ExtraPwdCNotice" value="点击附加码可刷新"  onfocus="if(value=='点击附加码可刷新'){value=''}" onblur="if(value==''){value='点击附加码可刷新'}" style="display: inline;">
                            </td>
                            
							<td class="right_1" align="left">
                                <?php echo $img;?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                
                
                <div class="line0px"></div>
    
            </div>
            
            
            <div class="HorizontalDiv"></div>
            <div class="HorizontalDiv"></div>
            <div class="HorizontalDiv"></div>
            <div class="HorizontalDiv"></div>
            <div id="LoginBar">
                <table id="LoginBtn">
                    <tbody><tr>
                        <td>
                            查询
                        </td>
                    </tr>
                </tbody></table>
            </div>
        </form>
        </div>
        
        <div class="HorizontalDiv"></div>
        <div class="pat_info" style="height: auto;padding: 0px 15px 0px 15px;display:none;">
		  <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">姓</td>
												
												<td class="EndWord">名</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left" id="pat_name">
										
									</td>
								</tr>
							</tbody>
						</table>
						 <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">登</td>
												<td class="MidWord">记</td>
												<td class="MidWord">时</td>
												<td class="EndWord">间</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left" id="order_addtime">
										
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">预</td>
												<td class="MidWord">约</td>
												<td class="MidWord">时</td>
												<td class="EndWord">间</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left" id="order_time">
										
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">医</td>
												<td class="MidWord">院</td>
												<td class="MidWord">名</td>
												<td class="EndWord">称</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left" id="hos_name">
										
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">科</td>
												<td class="MidWord">室</td>
												<td class="MidWord">名</td>
												<td class="EndWord">称</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left" id="keshi_name">
						
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		   <table class="InputLine">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">是</td>
												<td class="MidWord">否</td>
												<td class="MidWord">来</td>
												<td class="EndWord">院</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left"  id="is_come">
						
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		   <table class="InputLine" id="come_delete">
							<tbody>
								<tr>
									<td class="left" align="right">
										<table class="AutoAlignLabel">
											<tbody><tr>
												<td class="BeginWord">来</td>
												<td class="MidWord">院</td>
												<td class="MidWord">时</td>
												<td class="EndWord">间</td>
											</tr>
										</tbody></table>
									</td>
									<td class="right" align="left"  id="come_time">
						
									</td>
								</tr>
							</tbody>
						</table>
		  <div class="line5px"></div>
		</div>
 
        
    </div>
    <script type="text/javascript">
		$('.right_1').click(function(){
			$.ajax({

				type:'post',

				url:'?c=weixin&m=captcha',

				data:'ajax=3',

				success:function(data)

				{

					$('.right_1').html(data)

				},

				complete: function (XHR, TS)

				{

				   XHR = null;


				}

			});
			
		
		});
		$('#LoginBtn').click(function(){
			var phone = $('#DIDNoCNotice').val();
			var yuyue = $('#PwdCNotice').val();
			var cap = $('#ExtraPwdCNotice').val();
			if(phone == '请输入手机号码'){
			
				alert('请输入手机号码');
				$('#DIDNoCNotice').focus(); 
				return false;
			}
			if(yuyue == '请输入预约码'){
			
				alert('请输入预约码');
				$('#PwdCNotice').focus(); 
				return false;
			}
			if(cap == '点击附加码可刷新'){
			
				alert('请输入附加码');
				$('#ExtraPwdCNotice').focus(); 
				return false;
			}
			if(!$('#DIDNoCNotice').val().match(/^1[3|4|5|8][0-9]\d{4,8}$/)){
				alert('手机号码不合法');
				$('#DIDNoCNotice').focus(); 
				return false;
			}
			
			$.ajax({

				type:'post',

				url:'?c=weixin&m=get_order',

				data:'phone='+phone+'&yuyue='+yuyue+'&cap='+cap+'&user=<?php echo $user;?>',

				success:function(data)

				{
					$('.pat_info').show();
					var jsonobj=eval('('+data+')');
					$('#pat_name').html(jsonobj.pat_name);
					$('#order_time').html(jsonobj.order_time);
					$('#order_addtime').html(jsonobj.order_addtime);
					$('#hos_name').html(jsonobj.hos_name);
					$('#keshi_name').html(jsonobj.keshi_name);
					$('#is_come').html(jsonobj.is_come);
					if(jsonobj.is_come == '未来院'){
						$('#come_delete').hide();
					}else{
						$('#come_time').html(jsonobj.come_time);
					}

				},

				complete: function (XHR, TS)

				{

				   XHR = null;


				}

			});
		});
	</script>

</body></html>