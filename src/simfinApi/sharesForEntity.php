<?php 

error_reporting(-1);

require_once(dirname(__FILE__,2)."/cfg/simfinCreds.php");
require_once(dirname(__FILE__,2)."/db/simfinDB.php");
require_once(dirname(__FILE__,2)."/util/logging.php");
require_once(dirname(__FILE__,2)."/util/util.php");

function insertSharesOutstandingForEntity($db, $entityId){

	$urlA = "https://simfin.com/api/v1/companies/id/";
	$urlB = "/shares/aggregated?api-key=";

	$url  = $urlA.$entityId.$urlB.API_KEY;

	$data = simfinCurl($url);

	$httpCode = $data[0];
	$resp 		= $data[1];

	if($httpCode != HTTP_SUCCESS){

		$message  = "shares outstanding for entity - curl failed ";
		$message .= $httpCode.", ".$url." error ".$data[2];

		logError($message);

		return false;
	}

	// iterate over shares outstanding data

	for($idx = 0; $idx < count($resp); ++$idx){

		$elem = $resp[$idx];
		$keys = array_keys($elem);

		// skip if not diluted shares over period

		$diluted 	= false;
		$overPeriod = false;

		for($k = 0; $k < count($keys); ++$k){

			$key = $keys[$k];

			if($key == KEY_SHARE_FIGURE
			&& $elem[$key] == VAL_DILUTED)
				$diluted = true;

			if($key == KEY_MEASURE
			&& $elem[$key] == VAL_PERIOD)
				$overPeriod = true;
		}

		if($diluted === false
		|| $overPeriod === false)
			continue;

		$fy  	= SQL_NULL;
		$pd  	= SQL_NULL;
		$shares = SQL_NULL;

		for($k = 0; $k < count($keys); ++$k){

			$key = $keys[$k];
			$val = $elem[$key];

			switch ($key) {

				case KEY_FYEAR: 	$fy = $val;		break;
				case KEY_PERIOD: 	$pd = $val; 	break;
				case KEY_VALUE: 	$shares = $val; break;
			}
		}

		// skip if trailing twelve months

		if(substr($pd, 0, 3) == TRAILING_TWELVE)
			continue;

		if(!insertSharesOutstanding($db, $entityId, $fy, $pd, $shares)){

			$message  = "shares outstanding for entity - insert failed ";
			$message .= $entityId." fy ".$fy." pd ".$pd." shares ".$shares;

			logError($message);

			return false;
		}
	}

	$message = "shares outstanding for entity - completed ".$entityId;

	logActivity($message);

	return true;	
}

?>