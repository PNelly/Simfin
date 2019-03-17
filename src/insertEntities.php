<?php

require_once("simfinDB.php");

require_once("simfinCreds.php");

// High Level Entity Information From Master List //

$url 	= "https://simfin.com/api/v1/info/all-entities?api-key=".$apiKey;

$curl 	= curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$responseArray = json_decode(curl_exec($curl), true);

$db = new mysqli($server, $user, $pass, $usedb);

if($db->connect_error)
	die("Could not connect to DB: ".$db->connect_error);

for($idx = 0; $idx < count($responseArray); ++$idx){

	echo("\r[i]: ".$idx."\t");

	$entity = $responseArray[$idx];
	$keys   = array_keys($entity);

	$sid  = false;
	$tkr  = false;
	$name = false;

	for($k = 0; $k < count($keys); ++$k){

		$key = $keys[$k];
		$val = $entity[$key];

		switch($key){

			case KEY_SIMFIN_ID:  	$sid  = $val; 							break;
			case KEY_TICKER: 		$tkr  = $db->real_escape_string($val); 	break;
			case KEY_ENTITY_NAME:   $name = $db->real_escape_string($val); 	break;
		}
	}

	if($sid === false || $tkr === false || $name === false){
		echo("\nCan't insert values sid ".$sid." tkr ".$tkr." name".$name."\n");
		continue;
	}

	$sql = "INSERT INTO ".TBL_ENTITIES." (".COL_SIMFIN_ID.", ".COL_TICKER.", ".COL_ENTITY_NAME.") ";
	$sql = $sql."VALUES (".$sid.", '".$tkr."', '".$name."');";

	if($db->query($sql) !== true){
		echo("\nCould not insert, statement:\n");
		echo($sql."\n");
		echo(($db->error)."\n");
	}

	if($idx == count($responseArray) -1)
		echo("\n");
}

$db->close();

?>