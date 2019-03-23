<?php

error_reporting(-1);

require_once("simfinDB.php");
require_once("simfinCreds.php");

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

	if($httpCode != HTTP_SUCCESS)
		return false;

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

			if(!$replaceAllSheets && sheetExists($type, $fy, $pd, $existingSheets))
				continue;

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

			if($httpCode != HTTP_SUCCESS)
				return false;

			$shtData = json_decode($shtCurlResponse, true);

			$periodId 				= getPeriodId($db, $pd);
			$statementTypeId 		= getStatementTypeId($db, $type);

			if(!$periodId || !$statementTypeId)
				return false;

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

			if(!$industryTemplateId)
				return false;

			$statementId = insertStatementMetadata(
				$db,
				$entityId,
				$statementTypeId,
				$fy,
				$periodId,
				$calculated,
				$industryTemplateId
			);

			if(!$statementId)
				return false;

			$apiIds[$statementId] = $statementId;

			if(!clearCalculationScheme($db, $statementId))
				return false;

			if($hasCalculationScheme)
				if(!insertCalculationScheme($db, $statementId, $shtData[KEY_SCHEME]))
					return false;

			if(!insertStatementLineItems($db, $statementId, $shtData[KEY_VALUES]))
				return false;

			echo("\rSheetsInserted: ".++$sheetsInserted."\t\t");
		}
	}

	if($replaceAllSheets)
		if(!reconcileStatements($db, $entityId, $apiIds))
			return false;

	return true;
}

?>