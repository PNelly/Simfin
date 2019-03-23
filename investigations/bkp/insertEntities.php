<?php

require_once("simfinDB.php");
require_once("simfinCreds.php");

// High Level Entity Information From Master List //

$db = new mysqli($server, $user, $pass, $usedb);

if($db->connect_error)
	die("Could not connect to DB: ".$db->connect_error);

$url 	= "https://simfin.com/api/v1/info/all-entities?api-key=".$apiKey;
$curl 	= curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($curl), true);

for($idx = 0; $idx < count($response); ++$idx){

	echo("\r[i]: ".$idx."\t");

	$entity = $response[$idx];
	$keys   = array_keys($entity);

	$sid  = SQL_NULL;
	$tkr  = SQL_NULL;
	$name = SQL_NULL;

	for($k = 0; $k < count($keys); ++$k){

		$key = $keys[$k];
		$val = $entity[$key];

		switch($key){

			case KEY_SIMFIN_ID:  	$sid  = $val; 	break;
			case KEY_TICKER: 		$tkr  = $val; 	break;
			case KEY_ENTITY_NAME:   $name = $val; 	break;
		}
	}

	insertEntity($db, $sid, $tkr, $name);

	if($idx == count($response) -1)
		echo("\n");
}

$db->close();

?>