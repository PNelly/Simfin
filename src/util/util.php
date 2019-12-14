<?php 

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/config.php");
require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/util/logging.php");

$simfinApiCalls = 0;

function simfinCurl($url){

	global $simfinApiCalls;
	global $simfinApiCallLimit;

	if($simfinApiCalls >= $simfinApiCallLimit)
		return array(
			0 => 400,
			1 => "",
			2 => "simfin API call limit reached"
		);

	$curl = curl_init($url);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$response = json_decode(curl_exec($curl), true);

	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	$error = "";

	if($httpCode != HTTP_SUCCESS)
		$error = curl_error($curl);

	++$simfinApiCalls;

	return array(
		0 => $httpCode,
		1 => $response,
		2 => $error
	);
}

function sqlDump(){

	// dump database contents into compressed backup //

	if(!file_exists(SQL_DUMP_DIR) && !is_dir(SQL_DUMP_DIR)){
		logActivity("SQL Dump - directory ".SQL_DUMP_DIR." does not exist, creating");
		mkdir(SQL_DUMP_DIR);
	}

	if(file_exists(SQL_DUMP_DIR.SQL_DUMP_NEW)){
		logActivity("SQL Dump - dump exists ".SQL_DUMP_DIR.SQL_DUMP_NEW.", deleting");
		unlink(SQL_DUMP_DIR.SQL_DUMP_NEW);
	}

	logActivity("SQL Dump - dumping to: ".SQL_DUMP_DIR.SQL_DUMP_NEW);

	$cmd  = "mysqldump --user=".DB_READ_USER;
	$cmd .= " --password=".DB_READ_PASS;
	$cmd .= " --host=".DB_SERVER;
	$cmd .= " --single-transaction";
	$cmd .= " ".DB_USE." | gzip > ".SQL_DUMP_DIR.SQL_DUMP_NEW;

	exec($cmd);

	if(file_exists(SQL_DUMP_DIR.SQL_DUMP_CURRENT)
	&&(file_exists(SQL_DUMP_DIR.SQL_DUMP_NEW))){
		logActivity("SQL Dump - removing old sql dump");
		unlink(SQL_DUMP_DIR.SQL_DUMP_CURRENT);
	}

	if(file_exists(SQL_DUMP_DIR.SQL_DUMP_NEW)){
		logActivity("SQL Dump - fresh dump promoted to current");
		rename(SQL_DUMP_DIR.SQL_DUMP_NEW,SQL_DUMP_DIR.SQL_DUMP_CURRENT);
	}
}

?>