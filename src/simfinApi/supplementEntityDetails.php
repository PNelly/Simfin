<?php

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDb.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

function supplementEntityDetails($db, $entityId){

	$urlA 	= "https://simfin.com/api/v1/companies/id/";
	$urlB 	= "?api-key=";

	$url  = $urlA.$entityId.$urlB.API_KEY;

	$data = simfinCurl($url);

	$httpCode = $data[0];
	$response = $data[1];

	if($httpCode != HTTP_SUCCESS){

		logError("Entity details - curl failed ".$httpCode);

		return false;
	}

	$keys = array_keys($response);

	$secId = null;
	$indId = null;

	$sid  = SQL_NULL;
	$fye  = SQL_NULL;
	$emp  = SQL_NULL;
	$scn  = SQL_NULL;
	$scc  = SQL_NULL;
	$inn  = SQL_NULL;
	$inc  = SQL_NULL;

	for($idx = 0; $idx < count($keys); ++$idx){

		$key = $keys[$idx];
		$val = $response[$key];

		switch($key){

			case KEY_SIMFIN_ID: 	$sid = $val; break;
			case KEY_FYEND: 		$fye = $val; break;
			case KEY_EMPLOYEES: 	$emp = $val; break;
			case KEY_SECTOR_NAME: 	$inn = $val; break;
			case KEY_SECTOR_CODE:
				$inc = $val;
				$scc = sectorCodeFromIndustryCode($val);
				$scn = sectorNameFromIndustryCode($val);
			break;
		}
	}

	if($scn === false){

		logError("Entity details - invalid sector name");

		return false;
	}

	if($sid == null) $sid = SQL_NULL;
	if($fye == null) $fye = DEFAULT_FY_MO_END;
	if($emp == null) $emp = SQL_NULL;
	if($scn == null) $scn = SQL_NULL;
	if($scc == null) $scc = SQL_NULL;
	if($inn == null) $inc = SQL_NULL;

	$secId = getSectorId(  $db, $scc, $scn);
	$indId = getIndustryId($db, $inc, $inn);

	if($secId === false){

		logError("Entity details - invalid sector id");

		return false;

	} else if ($indId === false){

		logError("Entity details - invalid industry id");

		return false;
	}

	return updateEntity($db, $sid, $fye, $emp, $secId, $indId);
}

?>