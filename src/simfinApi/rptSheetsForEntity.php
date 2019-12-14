<?php

// TODO:
// insertRptLineItems
// reconcileRptStmts
// Key iteration for function arguments

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDb.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

function rptSheetExists(
	$type,
	$year,
	$period,
	& $existing){

	for($idx = 0; $idx < count($existing); ++$idx){

		$sheetMeta = $existing[$idx];

		if($sheetMeta[COL_STMT_NAME] 	== $type
		&& $sheetMeta[COL_FYEAR] 		== $year
		&& $sheetMeta[COL_PERIOD_NAME] 	== $period){

			unset($existing[$idx]);

			$existing = array_values($existing);

			return true;
		}
	}

	return false;
}

function insertRptSheets($db, $entityId, $replaceAllSheets){

	$sheetsInserted = 0;

	$existingSheets = getRptStmtMetaDnrmlzd($db, $entityId);

	$apiIds = array();

	$urlA 	= "https://simfin.com/api/v1/companies/id/";
	$urlB 	= "/statements/list?api-key=";

	$url 	= $urlA.$entityId.$urlB.API_KEY;

	$data = simfinCurl($url);

	$httpCode = $data[0];
	$resp 		= $data[1];

	if($httpCode != HTTP_SUCCESS){

		$message  = "Reported sheets for entity - list curl failed ";
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

			if(!$replaceAllSheets && rptSheetExists($type, $fy, $pd, $existingSheets)){

				$message  = "Sheet type ".$type." fy ".$fy." pd ".$pd." ";
				$message .= "already exists for entity ".$entityId.", skipping";

				logActivity($message);

				continue;
			}

			$shtUrlA = "https://simfin.com/api/v1/companies/id/";
			$shtUrlB = "/statements/original?stype=";
			$shtUrlC = "&ptype=";
			$shtUrlD = "&fyear=";
			$shtUrlE = "&api-key=";

			$shtUrl  = $shtUrlA.$entityId;
			$shtUrl .= $shtUrlB.$type;
			$shtUrl .= $shtUrlC.$pd;
			$shtUrl .= $shtUrlD.$fy;
			$shtUrl .= $shtUrlE.API_KEY;

			$data = simfinCurl($shtUrl);

			$httpCode = $data[0];
			$shtData 	= $data[1];

			if($httpCode != HTTP_SUCCESS){

				if($httpCode == HTTP_SERVER_ERROR){

					$message  = "Reported sheets for entity - server error ".$httpCode." ";
					$message .= "on ".$shtUrl.", skipping sheet";

					logActivity($message);

					continue;

				} else {

					$message  = "Reported sheets for entity - sheet curl failed ";
					$message .= $httpCode.", ".$shtUrl." error ".$data[2];

					logError($message);

					return false;
				}
			}

			$periodId 				= getPeriodId($db, $pd);
			$statementTypeId 		= getStatementTypeId($db, $type);

			if(!$periodId || !$statementTypeId){

				$message  = "Reported sheets for entity - bad period id or stmt type id, ";
				$message .= "period id ".$periodId." type id ".$statementTypeId;

				logError($message);

				return false;
			}

			$hasCalculationScheme 	= false;
			$calculated 			= SQL_NULL;
			$shtDataKeys  			= array_keys($shtData);

			$fileDate 				= SQL_NULL;
			$publishDate 			= SQL_NULL;
			$restated 				= SQL_NULL;
			$sourceUrl 				= SQL_NULL;

			for($k = 0; $k < count($shtDataKeys); ++$k){

				$key = $shtDataKeys[$k];

				switch ($key){

					case KEY_SCHEME:
						$hasCalculationScheme = (count($shtData[$key]) != 0);
					break;
					case KEY_CALCULATED:
						$calculated = ($shtData[$key]) ? SQL_TRUE : SQL_FALSE;
					break;
					case KEY_METADATA:

						$metaArray = $shtData[$key][0];
						$metaKeys  = array_keys($metaArray);

						for($m = 0; $m < count($metaKeys); ++$m){

							$metaKey = $metaKeys[$m];
							$metaVal = $metaArray[$metaKey];

							switch ($metaKey){

								case KEY_FILING_DATE: 		$fileDate 		= $metaVal; break;
								case KEY_PUBLISHED_DATE:  	$publishDate 	= $metaVal; break;
								case KEY_SOURCE_URL: 		$sourceUrl 		= $metaVal; break;
								case KEY_RESTATED: 			
									$restated = ($metaVal) ? SQL_TRUE : SQL_FALSE;
								break;
							}
						}

					break;
				}
			}

			$statementId = insertRptStmtMeta(
				$db,
				$entityId,
				$statementTypeId,
				$fy,
				$periodId,
				$calculated,
				$restated,
				$publishDate,
				$fileDate,
				$sourceUrl
			);

			if(!$statementId){

				$message  = "Reported sheets for entity - invalid statement id ";
				$message .= $statementId;

				logError($message);

				return false;
			}

			$apiIds[$statementId] = $statementId;

			if(!clearRptCalcScheme($db, $statementId))
				return false;

			if($hasCalculationScheme)
				if(!insertRptCalcScheme($db, $statementId, $shtData[KEY_SCHEME]))
					return false;

			if(!insertRptLineItems($db, $statementId, $shtData[KEY_VALUES]))
				return false;

			$message  = "Reported sheets for entity - ".++$sheetsInserted." ";
			$message .= "sheets inserted for ".$entityId;

			logActivity($message);
		}
	}	

	if($replaceAllSheets && !reconcileRptStmts($db, $entityId, $apiIds)){

		logError("Reported sheets for entity - reconciliation failed");

		return false;		
	}

	$message = "Reported sheets for entity - completed sheets for ".$entityId;

	logActivity($message);

	return true;
}

?>