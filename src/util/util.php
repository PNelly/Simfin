<?php 

	error_reporting(-1);

	$simfinApiCalls = 0;

	function simfinCurl($url){

		global $simfinApiCalls;

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = json_decode(curl_exec($curl), true);

		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$error = "";

		if($httpCode != HTTP_SUCCESS)
			$error = curl_error($curl);

		++$simfinApiCalls;

		return array(
			0 => $httpCode,
			1 => $response,
			2 => $error
		);
	}

?>