<?php

class WPWF_200 {
	
	private static $api_key;
	private static $api_url;

	public static function SetData( $api_url, $api_key ) {
		self::$api_url = $api_url;
		self::$api_key = $api_key;
	}

	public static function Request( $controller, $action, $params = array() ) {
		if( is_array($params) ) {
			$params['api_key'] 		= self::$api_key; 
			$params['controller'] 	= $controller;
			$params['action'] 		= $action;
		}
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, self::$api_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,'10');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		$curlResp 	= curl_exec($ch);
		$curlError 	= curl_error($ch);

		if ($curlError != ''){
			$result = array(
				'controller' => 'invalid',
				'action' => 'invalid',
				'status' => 'error',
				'date' => date('c'),
				'errors' => array($curlError)
			);
		}else{

			$result = json_decode($curlResp, true);
		}
		
		return $result;
	}
}