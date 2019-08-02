<?php
class Baidu{
	private $soapClient;
	var $url = 'https://api.baidu.com';
	/**
	 * construcor of Baidu_Api_Client_Core, only need the service name.
	 * @param String $serviceName
	 */
	public function __construct($params) {
		$serviceName = $params['serviceName'];
		$this->soapClient = new SoapClient ($this->url . '/sem/sms/v3/' . $serviceName . '?wsdl', array ('trace' => TRUE, 'connection_timeout' => 30 ) );
		
		// Prepare SoapHeader parameters 
		$sh_param = array ('username' => $params['username'], 'password' => $params['password'], 'token' => $params['token']);
		$headers = new SoapHeader ( 'http://api.baidu.com/sem/common/v2', 'AuthHeader', $sh_param );
		
		// Prepare Soap Client 
		$this->soapClient->__setSoapHeaders (array ($headers));
	}
	
	public function getFunctions() {
		return $this->soapClient->__getFunctions();
	}
	
	public function getTypes() {
		return $this->soapClient->__getTypes();
	}
	
	public function soapCall($function_name, array $arguments, array &$output_headers) {
		return $this->soapClient->__soapCall($function_name, $arguments, null, null, $output_headers);
	}
}
?>