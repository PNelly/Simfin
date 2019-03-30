<?php

require_once("apiKey.php");

$urlA = "https://simfin.com/api/v1/companies/id/";
$urlB = "/shares/classes/list?api-key=";

$url  = $urlA."242".$urlB.$apiKey;

$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

$responseArray = json_decode($response, true);

var_dump($responseArray);

?>