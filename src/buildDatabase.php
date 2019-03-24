<?php

error_reporting(-1);

require_once("simfinCreds.php");
require_once("simfinDB.php");
require_once("logging.php");

require_once("seedEntities.php");
require_once("supplementEntityDetails.php");
require_once("sheetsForEntity.php");
require_once("pricesForEntity.php");

$entitiesUpdated = 0;
$entityLimit = 2;

if(!isset($argv[1]))
	die("no boolean whether to replace data");

$replaceData = $argv[1];

$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_USE);

if($db->connect_error){

	logError("DB Connect Error: ".$db->connect_error);

	die();
}

$db->autocommit(false);

// transaction to seed entities //

$db->begin_transaction();

if(!seedEntities($db)){

	$db->rollback();
	$db->close();

	logError("Could not seed entities");

	die();
}

$db->commit();

// acquire list of entities //

$entityIds = getEntityIds($db);

if(!$entityIds){

	$db->close();

	logError("Could not query entity ids");

	die();
}

// transaction for each entity's data //

for($idx = 0; $idx < count($entityIds); ++$idx){

	$db->begin_transaction();

	$details = supplementEntityDetails($db, $entityIds[$idx]);

	if(!$details){

		logError("details update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$sheets = insertSheetsForEntity($db, $entityIds[$idx], $replaceData);

	if(!$sheets){

		logError("sheets update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$prices = insertSharePricesForEntity($db, $entityIds[$idx]);

	if(!$prices){

		logError("prices update failed for ".$entityIds[$idx]);

		$db->rollback();
		continue;
	}

	$db->commit();

	logActivity("Completed update for entity ".$entityIds[$idx]);

	++$entitiesUpdated;

	if($entitiesUpdated == $entityLimit)
		break;
}

$db->close();

?>