<?php

error_reporting(-1);

require_once("simfinDB.php");
require_once("simfinCreds.php");

function supplementEntityDetails($db, $entityId){

	$urlA 	= "https://simfin.com/api/v1/companies/id/";
	$urlB 	= "?api-key=";

	$url  = $urlA.$entityId.$urlB.API_KEY;

	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$response = json_decode(curl_exec($curl), true);

	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if($httpCode != HTTP_SUCCESS)
		return false;

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

	if($scn === false)
		return false;

	if($sid == null) $sid = SQL_NULL;
	if($fye == null) $fye = SQL_NULL;
	if($emp == null) $emp = SQL_NULL;
	if($scn == null) $scn = SQL_NULL;
	if($scc == null) $scc = SQL_NULL;
	if($inn == null) $inc = SQL_NULL;

	$secId = getSectorId(  $db, $scc, $scn);
	$indId = getIndustryId($db, $inc, $inn);

	if($secId === false || $indId === false)
		return false;

	return updateEntity($db, $sid, $fye, $emp, $secId, $indId);
}

?>