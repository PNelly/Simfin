<?php

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDB.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

function seedEntities($db){

	$url 	= "https://simfin.com/api/v1/info/all-entities?api-key=".API_KEY;

	$data = simfinCurl($url);

	$httpCode = $data[0];
	$response = $data[1];

	if($httpCode != HTTP_SUCCESS){

		$message  = "Seed Entities - curl failed ".$httpCode;
		$message .= ", ".$url." error ".$data[2];

		logError($message);

		return false;
	}

	logActivity("Successfully curled entities");

	for($idx = 0; $idx < count($response); ++$idx){

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

		if(!insertEntity($db, $sid, $tkr, $name)){

			$message  = "Seed Entities - Insertion failed for ";
			$message .= "id ".$sid." ticker ".$tkr." name ".$name;

			logError($message);

			return false;
		}
	}

	logActivity("Seeded ".count($response)." entities");

	return true;
}

?>