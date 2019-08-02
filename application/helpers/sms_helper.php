<?php
	// ʹ�ý��ܶ��Žӿ�
	function sms_jianzhou($phone,$content,$account,$password)
	{
		require_once('application/libraries/sms/nusoap.php');
		$client = new nusoap_client('http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService?wsdl', true);
		$client->soap_defencoding = 'utf-8';
		$client->decode_utf8      = false;
		$client->xml_encoding     = 'utf-8';
		$params = array(
				'account' => $account,
				'password' => $password,
				'destmobile' => $phone,
				'msgText' => $content,
			);
		$result = $client->call('sendBatchMessage', $params, 'http://www.jianzhou.sh.cn/JianzhouSMSWSServer/services/BusinessService');
		$send_status = $result['sendBatchMessageReturn'];
		return $send_status;
//            //短信应急代码开始
//            $ch = curl_init();
//            // 2. 设置选项，包括URL
//            curl_setopt($ch, CURLOPT_URL, "http://www.gdz52.com/demo.php?account=".$account."&password=".$password."&phone=".$phone."&content=".$content);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 0); 
//            // 3. 执行并获取HTML文档内容
//            $output = curl_exec($ch);
//            // 4. 释放curl句柄
//            curl_close($ch);
//            return $output;
//            
//            
//            
//            //短信应急代码结束
	}
       
	function sms_yun($phone,$content,$account,$password)
	{
		require_once('application/libraries/sms/CCPRestSmsSDK.php');
		//���ʺ�,��Ӧ�����������˺��µ� ACCOUNT SID
		$accountSid= $account;
		//���ʺ�����,��Ӧ���������˺��µ� AUTH TOKEN
		$accountToken= $password;
		
		//Ӧ��Id���ڹ���Ӧ���б��е��Ӧ�ã���ӦӦ�������е�APP ID
		//�ڿ������Ե�ʱ�򣬿���ʹ�ù����Զ�Ϊ�����Ĳ���Demo��APP ID
		$appId='8a48b5514a61a814014a7b80515e1007';
		
		//�����ַ
		//ɳ�л���������Ӧ�ÿ������ԣ���sandboxapp.cloopen.com
		//������û�Ӧ������ʹ�ã���app.cloopen.com
		$serverIP='sandboxapp.cloopen.com';
		
		
		//����˿ڣ������ɳ�л���һ��
		$serverPort='8883';
		
		//REST�汾�ţ��ڹ����ĵ�REST�����л�á�
		$softVersion='2013-12-26';
		$rest = new REST($serverIP,$serverPort,$softVersion);
		$rest->setAccount($accountSid,$accountToken);
		$rest->setAppId($appId);
		$data = array($content);
		$result = $rest->sendTemplateSMS($phone,$data,1);
		$send_status = $result->statusCode;
		return $send_status;
	}


?>