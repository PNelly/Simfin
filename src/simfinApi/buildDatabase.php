<?php

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDB.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

require_once("seedEntities.php");
require_once("supplementEntityDetails.php");
require_once("stdSheetsForEntity.php");
require_once("rptSheetsForEntity.php");
require_once("pricesForEntity.php");
require_once("sharesForEntity.php");

$entitiesUpdated = 0;
$entityLimit = 1;

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

for($idx = 0; $idx < count($entityIds); ++$idx){

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

	if($entitiesUpdated == $entityLimit)
		break;
}

$db->close();

// create new backup //

sqlDump();

?>