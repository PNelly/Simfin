<?php

require_once("config.php");

function prepend($message, $path){

	$context 	= stream_context_create();
	$original 	= fopen($path, "c+", 1, $context);
	$temporary	= tempnam(sys_get_temp_dir(), "php_prepend_");

	file_put_contents($temporary, $message);
	file_put_contents($temporary, $original, FILE_APPEND);

	fclose($original);
	unlink($path);
	rename($temporary, $path);
}

function logError($message){

	logMessage($message, ERROR_LOG_PATH, ERROR_LOG_LINES);
}

function logActivity($message){

	logMessage($message, ACTIVITY_LOG_PATH, ACTIVITY_LOG_LINES);
}

function logMessage($message, $path, $maxLines){

	// get time //

	$date = getdate(); 

	$year 	= $date["year"];
	$month 	= $date["mon"];
	$day 	= $date["mday"];
	$hour 	= $date["hours"];
	$min  	= $date["minutes"];
	$sec 	= $date["seconds"];

	$logTime = "[".$year."-".$month."-".$day." ".$hour.":".$min.":".$sec."]";

	$message = $logTime." ".$message."\n";

	// enforce length constraint //

	$file  = fopen($path, "c+");
	$lines = 0;

	while(!feof($file)){

		$line = fgets($file);
		++$lines;

		if($lines == $maxLines -1)
			ftruncate($file, ftell($file));
	}

	fclose($file);

	// include new message //

	prepend($message, $path);

	// notify stdout //

	echo($message);
}

?>