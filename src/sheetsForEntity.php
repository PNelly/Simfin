<?php

error_reporting(-1);

require_once("simfinDB.php");
require_once("simfinCreds.php");
require_once("logging.php");

function sheetExists(
	$type,
	$year,
	$period,
	& $existingSheets){

	for($idx = 0; $idx < count($existingSheets); ++$idx){

		$sheetMeta = $existingSheets[$idx];

		if($sheetMeta[COL_STMT_NAME] 	== $type
		&& $sheetMeta[COL_FYEAR] 		== $year
		&& $sheetMeta[COL_PERIOD_NAME] 	== $period){

			unset($existingSheets[$idx]);

			$existingSheets = array_values($existingSheets);

			return true;
		}
	}

	return false;
}

function insertSheetsForEntity($db, $entityId, $replaceAllSheets){

	echo("\n");

	$sheetsInserted = 0;

	$existingSheets = getStmtMetaDenormalized($db, $entityId);

	$apiIds = array();

	$urlA 	= "https://simfin.com/api/v1/companies/id/";
	$urlB 	= "/statements/list?api-key=";

	$url 	= $urlA.$entityId.$urlB.API_KEY;

	$curl 	= curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$resp 	= json_decode(curl_exec($curl), true);

	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if($httpCode != HTTP_SUCCESS){

		$message  = "Sheets for Entity - list curl failed ";
		$message .= $httpCode.", ".$url;

		logError($message);

		return false;
	}

	$types 	= array_keys($resp);

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

			if(!$replaceAllSheets && sheetExists($type, $fy, $pd, $existingSheets)){

				$message  = "Sheet type ".$type." fy ".$fy." pd ".$pd." ";
				$message .= "already exists for entity ".$entityId.", skipping";

				logActivity($message);

				continue;
			}

			$shtUrlA = "https://simfin.com/api/v1/companies/id/";
			$shtUrlB = "/statements/standardised?stype=";
			$shtUrlC = "&ptype=";
			$shtUrlD = "&fyear=";
			$shtUrlE = "&api-key=";

			$shtUrl  = $shtUrlA.$entityId;
			$shtUrl .= $shtUrlB.$type;
			$shtUrl .= $shtUrlC.$pd;
			$shtUrl .= $shtUrlD.$fy;
			$shtUrl .= $shtUrlE.API_KEY;

			$shtCurl = curl_init($shtUrl);
				curl_setopt($shtCurl, CURLOPT_RETURNTRANSFER, true);

			$shtCurlResponse = curl_exec($shtCurl);

			$httpCode = curl_getinfo($shtCurl, CURLINFO_HTTP_CODE);

			if($httpCode != HTTP_SUCCESS){

				if($httpCode == HTTP_SERVER_ERROR){

					$message  = "Sheets for entity - server error ".$httpCode." ";
					$message .= "on ".$shtUrl.", skipping sheet";

					logActivity($message);

					continue;

				} else {

					$message  = "Sheets for entity - sheet curl failed ";
					$message .= $httpCode.", ".$shtUrl;

					logError($message);

					return false;
				}
			}

			$shtData = json_decode($shtCurlResponse, true);

			$periodId 				= getPeriodId($db, $pd);
			$statementTypeId 		= getStatementTypeId($db, $type);

			if(!$periodId || !$statementTypeId){

				$message  = "Sheets for entity - bad period id or stmt type id, ";
				$message .= "period id ".$periodId." type id ".$statementTypeId;

				logError($message);

				return false;
			}

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

			if(!$industryTemplateId){

				$message  = "Sheets for entity - invalid industry template id ";
				$message .= $industryTemplateId;

				logError($message);

				return false;
			}

			$statementId = insertStatementMetadata(
				$db,
				$entityId,
				$statementTypeId,
				$fy,
				$periodId,
				$calculated,
				$industryTemplateId
			);

			if(!$statementId){

				$message  = "Sheets for entity - invalid statement id ";
				$message .= $statementId;

				logError($message);

				return false;
			}

			$apiIds[$statementId] = $statementId;

			if(!clearCalculationScheme($db, $statementId))
				return false;

			if($hasCalculationScheme)
				if(!insertCalculationScheme($db, $statementId, $shtData[KEY_SCHEME]))
					return false;

			if(!insertStatementLineItems($db, $statementId, $shtData[KEY_VALUES]))
				return false;

			$message  = "Sheets for entity - ".++$sheetsInserted." ";
			$message .= "sheets inserted for ".$entityId;

			logActivity($message);
		}
	}

	if($replaceAllSheets && !reconcileStatements($db, $entityId, $apiIds)){

		logError("Sheets for entity - reconciliation failed");

		return false;		
	}

	return true;
}

?>