<?php

error_reporting(-1);

require_once("simfinDB.php");
require_once("simfinCreds.php");

function seedEntities($db){

	$url 	= "https://simfin.com/api/v1/info/all-entities?api-key=".API_KEY;
	$curl 	= curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$response = json_decode(curl_exec($curl), true);

	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if($httpCode != HTTP_SUCCESS)
		return false;

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

		if(!insertEntity($db, $sid, $tkr, $name))
			return false;

		if($idx == count($response) -1)
			echo("\n");
	}

	return true;
}

?>