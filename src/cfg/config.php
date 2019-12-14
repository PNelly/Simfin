<?php

define("ERROR_LOG_PATH", 			dirname(__FILE__,3)."/log/errorLog.txt");
define("ERROR_LOG_LINES", 		1024);
define("ACTIVITY_LOG_PATH", 	dirname(__FILE__,3)."/log/activityLog.txt");
define("ACTIVITY_LOG_LINES",	1024);
define("SQL_DUMP_DIR", 				dirname(__FILE__,3)."/bkp/");
define("SQL_DUMP_NEW", 				"simfinDumpNew.sql.gz");
define("SQL_DUMP_CURRENT", 		"simfinDump.sql.gz");
define("CONFIG_JSON_PATH", 		dirname(__FILE__,1)."/config.json");
define("CONFIG_JSON_PATH_NEW",dirname(__FILE__,1)."/configNew.json");

$configs = json_decode(file_get_contents(CONFIG_JSON_PATH), true);

$simfinApiCallLimit = $configs["simfinApiCallLimit"];
$nextEntityToQuery  = $configs["nextEntityToQuery"];

function updateConfig($nextEntityToQuery){

	global $simfinApiCallLimit;

	$cfg = array(
		"simfinApiCallLimit" => $simfinApiCallLimit,
		"nextEntityToQuery"  => $nextEntityToQuery
	);

	$file = fopen(CONFIG_JSON_PATH_NEW, "w");

	fwrite($file, json_encode($cfg));
	fclose($file);

	if(file_exists(CONFIG_JSON_PATH)
	&& file_exists(CONFIG_JSON_PATH_NEW)){
		unlink(CONFIG_JSON_PATH);
		rename(CONFIG_JSON_PATH_NEW, CONFIG_JSON_PATH);
	}
}

?>