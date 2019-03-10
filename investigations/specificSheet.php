<?php

require_once("apiKey.php");

$url = "https://simfin.com/api/v1/companies/id/18/statements/standardised?stype=pl&ptype=TTM-1.5&fyear=2018&api-key=".$apiKey;

$url = "https://simfin.com/api/v1/companies/id/18/statements/standardised?stype=pl&ptype=Q1&fyear=2018&api-key=".$apiKey;

$url = "https://simfin.com/api/v1/companies/id/18/ratios?api-key=".$apiKey;

$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

$responseArray = json_decode($response, true);

var_dump($responseArray);

echo(curl_getinfo($curl, CURLINFO_HTTP_CODE));

?>