<?php
	function apache_request_headers_custom() { // Use this if your host doesn't seem to support apache_request_headers. If it does, you can use that if you change everything in unset to lowercase.
		$arh = array();
		$rx_http = '/\AHTTP_/';
		foreach($_SERVER as $key => $val) {
			if(preg_match($rx_http, $key)) {
				$arh_key = preg_replace($rx_http, '', $key);
				$rx_matches = array();
				$rx_matches = explode('_', $arh_key);
				if(count($rx_matches) > 0 and strlen($arh_key) > 2) {
				foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				}
				$arh[$arh_key] = $val;
			}
		}
		return($arh);
	}

	$subdomain = array_shift((explode('.',$_SERVER['HTTP_HOST'])));
	$request_headers_raw = apache_request_headers_custom();
	$request_headers = array();
	$set = array('ACCEPT', 'ACCEPT-ENCODING', 'CONNECTION', 'USER-AGENT');
	foreach($set as $index => $header) {
		if (array_key_exists($header,$request_headers_raw)) {
			array_push($request_headers,"$header: ".$request_headers_raw[$header]);
		}
	}
	if ($subdomain != 'roblox-proxy') {
		$url = "https://$subdomain.roblox.com".$_SERVER['REQUEST_URI'];
	} else {
		$url = 'https://www.roblox.com'.$_SERVER['REQUEST_URI'];
	}
	$curl = curl_init($url);
	curl_setopt_array($curl,array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_HTTPHEADER => $request_headers
	));
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		curl_setopt_array($curl,array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => file_get_contents('php://input')
		));
	}
	$response = curl_exec($curl);
	$headerSize = curl_getinfo($curl,CURLINFO_HEADER_SIZE);
	$header = substr($response,0,$headerSize);
	foreach(explode("\n", $header) as $index => $field) {
		header($field);
	}
	echo substr($response,$headerSize);
?>