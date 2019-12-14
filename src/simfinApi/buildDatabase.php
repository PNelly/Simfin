<?php

error_reporting(-1);

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__,2)."/cfg/");
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__,2)."/db/");
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__,2)."/util/");

require_once(dirname(__FILE__,2)."/cfg/config.php");
require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDb.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

require_once("seedEntities.php");
require_once("supplementEntityDetails.php");
require_once("stdSheetsForEntity.php");
require_once("rptSheetsForEntity.php");
require_once("pricesForEntity.php");
require_once("sharesForEntity.php");

$entitiesUpdated = 0;
$entityLimit = 2;

if(!isset($argv[1]))
	die("no argument whether to replace data");

if($argv[1] != "true" && $argv[1] != "false")
	die("replace data must be \"true\" or \"false\"");

$replaceData = false;

if($argv[1] == "true")
	$replaceData = true;
else if ($argv[1] == "false")
	$replaceData = false;

$db = new mysqli(DB_SERVER, DB_WRITE_USER, DB_WRITE_PASS, DB_USE);

if($db->connect_error){

	logError("Build database - DB Connect Error: ".$db->connect_error);

	die();
}

$db->autocommit(false);

// transaction to seed entities //

$db->begin_transaction();

if(!seedEntities($db)){

	$db->rollback();
	$db->close();

	logError("Build database - Could not seed entities");

	die();
}

$db->commit();

// acquire list of entities //

$entityIds = getEntityIds($db);

if(!$entityIds){

	$db->close();

	logError("Build database - Could not query entity ids");

	die();
}

// transaction for each entity's data //

$idx = 0;

if($nextEntityToQuery >= 0){
	$last = array_search($nextEntityToQuery, $entityIds);
	$idx 	= ($last !== false) ? $last : 0;
	echo("next entity to query: ".$nextEntityToQuery." found idx: ".$last."\n");
}

for(; $idx < count($entityIds); ++$idx){

	if($simfinApiCalls >= $simfinApiCallLimit){
		logError("Build database - simfin api call limit reached");
		break;
	}

	$db->begin_transaction();

	$details = supplementEntityDetails($db, $entityIds[$idx]);

	if(!$details){

		logError("Build database - details update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$prices = insertSharePricesForEntity($db, $entityIds[$idx]);

	if(!$prices){

		logError("Build database - prices update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$shares = insertSharesOutstandingForEntity($db, $entityIds[$idx]);

	if(!$shares){

		logError("Build database - shares update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$rptSheets = insertRptSheets($db, $entityIds[$idx], $replaceData);

	if(!$rptSheets){

		logError("Build database - reported sheets updated failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$stdSheets = insertStdSheets($db, $entityIds[$idx], $replaceData);

	if(!$stdSheets){

		logError("Build database - standardized sheets update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$db->commit();

	logActivity("Build database - Completed update for entity ".$entityIds[$idx]);

	++$entitiesUpdated;

	$nextEntityToQuery = $entityIds[ ($idx +1) % count($entityIds)];

	if($entitiesUpdated == $entityLimit)
		break;
}

$db->close();

// update config to pick up where left off //

updateConfig($nextEntityToQuery);

// create new backup //

sqlDump();

?>