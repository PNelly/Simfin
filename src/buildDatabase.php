<?php

error_reporting(-1);

require_once("simfinCreds.php");
require_once("simfinDB.php");

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

if($db->connect_error)
	die("DB Connect Error: ".$db->connect_error."\n");

$db->autocommit(false);

// transaction to seed entities //

$db->begin_transaction();

if(!seedEntities($db)){

	$db->rollback();
	$db->close();

	die("Could not seed entities");
}

$db->commit();

// acquire list of entities //

$entityIds = getEntityIds($db);

if(!$entityIds){

	$db->close();

	die("Could not query entity ids");
}

// transaction for each entity's data //

for($idx = 0; $idx < count($entityIds); ++$idx){

	$db->begin_transaction();
		echo("details\n");
	$details = supplementEntityDetails($db, $entityIds[$idx]);

	if(!$details){

		echo("details update failed for ".$entityIds[$idx]."\n");

		$db->rollback();
		continue;
	}
		echo("sheets\n");
	$sheets = insertSheetsForEntity($db, $entityIds[$idx], $replaceData);

	if(!$sheets){

		echo("sheets update failed for ".$entityIds[$idx]."\n");

		$db->rollback();
		continue;
	}
		echo("prices\n");
	$prices = insertSharePricesForEntity($db, $entityIds[$idx]);

	if(!$prices){

		echo("prices update failed for ".$entityIds[$idx]."\n");

		$db->rollback();
		continue;
	}

	$db->commit();

	echo("Completed update for entity ".$entityIds[$idx]."\n");

	++$entitiesUpdated;

	if($entitiesUpdated == $entityLimit)
		break;
}

$db->close();

?>