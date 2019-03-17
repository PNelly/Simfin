<?php

require_once("simfinDB.php");

require_once("simfinCreds.php");

// begin script //

$statementsProcessed = 0;
$statementLimit 	 = 5;

$db 	= new mysqli($server, $user, $pass, $usedb);

if($db->connect_error)
	die("Failed DB Connection: ".$db->connect_error."\n");

$ids = getEntityIds($db);

if(count($ids) <= 0) 
	die("No ids in selection");

// statements for each entity //

for($idx = 0; $idx < count($ids); ++$idx){

	// get available statements //

	$urlA 	= "https://simfin.com/api/v1/companies/id/";
	$urlB 	= "/statements/list?api-key=";

	$url 	= $urlA.($ids[$idx]).$urlB.$apiKey;

	$curl 	= curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$resp 	= json_decode(curl_exec($curl), true);

	$types 	= array_keys($resp);

	// call each available statement //

	for($t = 0; $t < count($types); ++$t){

		$type 		= $types[$t];
		$sheetMeta 	= $resp[$type];

		for($s = 0; $s < count($sheetMeta); ++$s){

			$pd = $fy = $ca = null;

			$pd = $sheetMeta[$s][KEY_PERIOD];
			$fy = $sheetMeta[$s][KEY_FYEAR];
			$ca = $sheetMeta[$s][KEY_CALCULATED];

			if(is_null($pd) || is_null($fy) || is_null($ca))
				continue;

			if(substr($pd, 0, 3) == TRAILING_TWELVE)
				continue;

			$shtUrlA = "https://simfin.com/api/v1/companies/id/";
			$shtUrlB = "/statements/standardised?stype=";
			$shtUrlC = "&ptype=";
			$shtUrlD = "&fyear=";
			$shtUrlE = "&api-key=";

			$shtUrl  = $shtUrlA.$ids[$idx];
			$shtUrl .= $shtUrlB.$type;
			$shtUrl .= $shtUrlC.$pd;
			$shtUrl .= $shtUrlD.$fy;
			$shtUrl .= $shtUrlE.$apiKey;

			$shtCurl = curl_init($shtUrl);
				curl_setopt($shtCurl, CURLOPT_RETURNTRANSFER, true);

			$shtCurlResponse = curl_exec($shtCurl);

			$httpCode = curl_getinfo($shtCurl, CURLINFO_HTTP_CODE);

			if($httpCode != HTTP_SUCCESS)
				continue;

			$shtData = json_decode($shtCurlResponse, true);

			// meta data

			$periodId 				= getPeriodId($db, $pd);
			$statementTypeId 		= getStatementTypeId($db, $type);
			$industryTemplateId 	= null;
			$hasCalculationScheme 	= false;
			$calculated 			= null;
			$shtDataKeys = array_keys($shtData);

			for($k = 0; $k < count($shtDataKeys); ++$k){

				$key = $shtDataKeys[$k];

				switch ($key){

					case KEY_SCHEME:
						$hasCalculationScheme = (count($shtData[$key]) != 0);
					break;
					case KEY_CALCULATED:
						$calculated = ($shtData[$key]) ? SQL_TRUE : SQL_FALSE;
					break;
					case KEY_TEMPALTE:
						$industryTemplateId = getIndustryTemplateId($db, $shtData[$key]);
					break;
				}
			}

			$statementId = insertStatementMetadata(
				$db,
				$ids[$idx],
				$statementTypeId,
				$fy,
				$periodId,
				$calculated,
				$industryTemplateId
			);

			// calculation scheme

			if($hasCalculationScheme)
				insertCalculationScheme($db, $statementId, $shtData[KEY_SCHEME]);

			// line items

			insertStatementLineItems($db, $statementId, $shtData[KEY_VALUES]);

			++$statementsProcessed;

			echo("\rStatements Processed: ".$statementsProcessed."\t\t");

			if($statementsProcessed == $statementLimit){
				echo("\n");
				break;
			}
		}

		break;
	}

	break;
}

$db->close();

?>