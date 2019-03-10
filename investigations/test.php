<?php

/*
	reference url:

	https://simfin.com/api/v1/info/find-id/ticker/{ticker}?api-key={api_key}
*/

require_once("apiKey.php");

$url = "https://simfin.com/api/v1/info/all-entities?api-key=".$apiKey;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

$responseArray = json_decode($response, true);

for($idx1 = 0; $idx1 < count($responseArray); ++$idx1){

	$entity = $responseArray[$idx1];
	$keys   = array_keys($entity);

	var_dump($entity);
	echo("---------------"."\n");
	var_dump($keys);
	echo("///////////////"."\n");

	for($idx2 = 0; $idx2 < count($keys); ++$idx2){

		$key = $keys[$idx2];
		$val = $entity[$key];

		echo("idx1 ".$idx1." idx2 ".$idx2." key ".$key." val ".$val."\n");
	}
}

?>