<?php

require_once("simfinDB.php");

require_once("simfinCreds.php");

// One by One Entity Details //

$urlA 	= "https://simfin.com/api/v1/companies/id/";
$urlB 	= "?api-key=";

$db = new mysqli($server, $user, $pass, $usedb);

if($db->connect_error)
	die("Could not connect to DB: ".$db->connect_error);

$ids = getEntityIds($db);

if(count($ids) <= 0) 
	die("No ids in selection");

for($idx = 0; $idx < count($ids); ++$idx){

	$sid  = $ids[$idx];

	$url  = $urlA.$sid.$urlB.$apiKey;

	$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$responseArray = json_decode(curl_exec($curl), true);

	$keys = array_keys($responseArray);

	$sectorId 	= null;
	$industryId = null;

	$sid  = null;
	$fye  = null;
	$emp  = null;
	$scn  = null;
	$scc  = null;
	$inn  = null;
	$inc  = null;

	for($idx = 0; $idx < count($keys); ++$idx){

		$key = $keys[$idx];
		$val = $responseArray[$key];

		switch($key){

			case KEY_SIMFIN_ID: 	$sid = $val; break;
			case KEY_FYEND: 		$fye = $val; break;
			case KEY_EMPLOYEES: 	$emp = $val; break;
			case KEY_SECTOR_NAME: 	$inn = $db->real_escape_string($val); break;
			case KEY_SECTOR_CODE:
				$inc = $val;
				$scc = sectorCodeFromIndustryCode($val);
				$scn = $db->real_escape_string(sectorNameFromIndustryCode($val));
			break;
		}
	}

	$sectorId 	= getSectorId($db, $scc, $scn);
	$industryId = getIndustryId($db, $inc, $inn);

	if($sid === null) $sid = SQL_NULL;
	if($fye === null) $fye = SQL_NULL;
	if($emp === null) $emp = SQL_NULL;
	if($scn === null) $scn = SQL_NULL;
	if($scc === null) $scc = SQL_NULL;
	if($inn === null) $icn = SQL_NULL;
	if($inc === null) $icc = SQL_NULL;

	$sql  = "UPDATE ".TBL_ENTITIES." SET ";
	$sql .= COL_FYEAR_END." = ".$fye.", ";
	$sql .= COL_EMPLOYEES." = ".$emp.", ";
	$sql .= COL_SECTOR_ID." = ".$sectorId.", ";
	$sql .= COL_INDUSTRY_ID." = ".$industryId." ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ".$sid.";";

	if($db->query($sql) !== true){
		echo("could not update, statement:\n");
		echo($sql."\n");
		echo($db->error."\n");
	}

	break;
}

$db->close();

?>