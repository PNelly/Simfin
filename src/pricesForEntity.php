<?php

error_reporting(-1);

require_once("simfinDB.php");
require_once("simfinCreds.php");
require_once("logging.php");

function insertSharePricesForEntity($db, $entityId){

	$urlA = "https://simfin.com/api/v1/companies/id/";
	$urlB = "/shares/prices?api-key=";

	$url  = $urlA.$entityId.$urlB.API_KEY;

	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$resp = json_decode(curl_exec($curl), true);

	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if($httpCode != HTTP_SUCCESS){

		$message  = "Prices for entity - curl failed ";
		$message .= $httpCode.", ".$url;

		logError($message);

		return false;
	}

	$keys = array_keys($resp);

	// establish class and type

	$shareClassTypeId = SQL_NULL;
	$shareClassNameId = SQL_NULL;

	for($k = 0, $typeFound = false; $k < count($keys); ++$k){

		$key = $keys[$k];

		switch ($key){

			case KEY_SHRCLS_TYPE:

				if(!$typeFound){

					$shareClassTypeId 	= getShareClassTypeId($db, $resp[$key]);
					$typeFound 			= true;
					$k 					= 0;

					continue;
				}
				
			break;

			case KEY_SHRCLS_NAME:

				if($typeFound)
					$shareClassNameId = getShareClassNameId($db, $resp[$key], $shareClassTypeId);
			break;

		}
	}

	if(!$shareClassTypeId){

		logError("Prices for entity - invalid share class type id ".$shareClassTypeId);

		return false;
	}

	if(!$shareClassNameId){

		logError("Prices for entity - invalid share class name id ".$shareClassNameId);

		return false;
	}

	// update entity with share class data

	if(!updateEntityShareClass($db, $entityId, $shareClassNameId)){

		logError("Prices for entity - share class update failed for ".$entityId);

		return false;
	}

	// iterate over prices

	for($k = 0; $k < count($keys); ++$k){

		$key = $keys[$k];

		if($key == KEY_PRICE_DATA){

			$priceData = $resp[$key];

			for($p = 0; $p < count($priceData); ++$p){

				$priceElement = $priceData[$p];
				$priceElmKeys = array_keys($priceElement);

				$date 	  = SQL_NULL;
				$price    = SQL_NULL;
				$coeff    = SQL_NULL;

				for($pidx = 0; $pidx < count($priceElmKeys); ++$pidx){

					$elemKey  = $priceElmKeys[$pidx];
					$elemVal  = $priceElement[$elemKey];

					switch($elemKey){

						case KEY_PRICE_DATE:	$date  = $elemVal; 	break;
						case KEY_CLOSE_ADJ: 	$price = $elemVal; 	break;
						case KEY_SPLIT_COEF: 	$coeff = $elemVal; 	break;
					}
				}

				if(!insertPricePoint($db, $entityId, $date, $price, $coeff)){

					$message  = "Prices for entity - price point insert failed ";
					$message .= "id ".$entityId." date ".$date." price ".$price." ";
					$message .= "split coefficient ".$coeff;

					logError($message);

					return false;
				}
			}

			// found what we're looking for //

			break;
		}
	}

	$message = "Prices for entity - completed insertions for ".$entityId;

	logActivity($message);

	return true;	
}

?>