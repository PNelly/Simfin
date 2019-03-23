<?php

require_once("simfinDB.php");

require_once("simfinCreds.php");

// begin script //

$entitiesProcessed 	= 0;
$entityLimit 	 	= 5;

$db 	= new mysqli($server, $user, $pass, $usedb);

if($db->connect_error)
	die("Failed DB Connection: ".$db->connect_error."\n");

$ids = getEntityIds($db);

if(count($ids) <= 0) 
	die("No ids in selection");

// prices for each entity //

for($idx = 0; $idx < count($ids); ++$idx){

	$urlA = "https://simfin.com/api/v1/companies/id/";
	$urlB = "/shares/prices?api-key=";

	$url  = $urlA.($ids[$idx]).$urlB.$apiKey;

	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$resp = json_decode(curl_exec($curl), true);

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

	// update entity with share class data

	updateEntityShareClass($db, $ids[$idx], $shareClassNameId);

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

				// insert record

				insertPricePoint($db, $ids[$idx], $date, $price, $coeff);
			}

			break;
		}
	}

	++$entitiesProcessed;

	echo("\rEntities Processed: ".$entitiesProcessed."\t\t");

	if($entitiesProcessed == $entityLimit){
		echo("\n");
		break;
	}
}

$db->close();

?>