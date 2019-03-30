<?php

error_reporting(-1);

define("DUPLICATE_ENTRY", 	1062);
define("HTTP_SUCCESS", 		200);
define("HTTP_SERVER_ERROR", 500);
define("TRAILING_TWELVE", 	"TTM");
define("DEFAULT_FY_MO_END", 12);

define("PERIOD_Q1", "Q1");
define("PERIOD_Q2", "Q2");
define("PERIOD_Q3", "Q3");
define("PERIOD_Q4", "Q4");
define("PERIOD_H1", "H1");
define("PERIOD_H2", "H2");
define("PERIOD_9M", "9M");
define("PERIOD_FY", "FY");

$__PD_BEG_MO_OFFSETS = array(

	PERIOD_Q1 	=> 	-11,
	PERIOD_Q2 	=> 	-8,
	PERIOD_Q3	=> 	-5,
	PERIOD_Q4 	=> 	-2,
	PERIOD_H1	=> 	-11,
	PERIOD_H2 	=> 	-5,
	PERIOD_9M 	=> 	-11,
	PERIOD_FY 	=> 	-11,
);

$__PD_END_MO_OFFSETS = array(

	PERIOD_Q1 	=> 	-9,
	PERIOD_Q2 	=> 	-6,
	PERIOD_Q3	=> 	-3,
	PERIOD_Q4 	=> 	-0,
	PERIOD_H1	=> 	-6,
	PERIOD_H2 	=> 	-0,
	PERIOD_9M 	=> 	-3,
	PERIOD_FY 	=> 	-0,
);

define("SEC_IND", 			"Industrials");
define("SEC_TECH" ,			"Technology");
define("SEC_CON_DEF", 		"Consumer Defensive");
define("SEC_CON_CYC", 		"Consumer Cyclical");
define("SEC_FIN_SER", 		"Financial Services");
define("SEC_UTL", 			"Utilities");
define("SEC_HEALTH", 		"Healthcare");
define("SEC_ENG", 			"Energy");
define("SEC_BUS_SER", 		"Business Services");
define("SEC_RLST", 			"Real Estate");
define("SEC_BSCMAT", 		"Basic Materials");
define("SEC_OTR", 			"Other");
define("SEC_COMM_SER", 		"Communication Services");

define("SEC_IND_CODE", 		100);
define("SEC_TECH_CODE", 	101);
define("SEC_CON_DEF_CODE", 	102);
define("SEC_CON_CYC_CODE", 	103);
define("SEC_FIN_SER_CODE", 	104);
define("SEC_UTL_CODE", 		105);
define("SEC_HEALTH_CODE", 	106);
define("SEC_ENG_CODE", 		107);
define("SEC_BUS_SER_CODE", 	108);
define("SEC_RLST_CODE", 	109);
define("SEC_BSCMAT_CODE", 	110);
define("SEC_OTR_CODE", 		111);
define("SEC_COMM_SER_CODE", 112);

$__SECTORS = array(

	SEC_IND_CODE 		=> 	SEC_IND,
	SEC_TECH_CODE 		=> 	SEC_TECH,
	SEC_CON_DEF_CODE 	=> 	SEC_CON_DEF,
	SEC_CON_CYC_CODE 	=> 	SEC_CON_CYC,
	SEC_FIN_SER_CODE 	=> 	SEC_FIN_SER,
	SEC_UTL_CODE	 	=> 	SEC_UTL,
	SEC_HEALTH_CODE 	=> 	SEC_HEALTH,
	SEC_ENG_CODE 		=> 	SEC_ENG,
	SEC_BUS_SER_CODE 	=> 	SEC_BUS_SER,
	SEC_RLST_CODE 		=> 	SEC_RLST,
	SEC_BSCMAT_CODE 	=> 	SEC_BSCMAT,
	SEC_OTR_CODE 		=> 	SEC_OTR,
	SEC_COMM_SER_CODE 	=> 	SEC_COMM_SER
);

define("KEY_PERIOD", 		"period");
define("KEY_FYEAR", 		"fyear");
define("KEY_SIGN", 			"sign");
define("KEY_TID", 			"tid");
define("KEY_UID", 			"uid");
define("KEY_NAME", 			"standardisedName");
define("KEY_PARENT", 		"parent_tid");
define("KEY_DISPLAY", 		"displayLevel");
define("KEY_VAL_CHOSEN", 	"valueChosen");
define("KEY_CALCULATED", 	"calculated");
define("KEY_SCHEME", 		"calculationScheme");
define("KEY_TEMPALTE", 		"industryTemplate");
define("KEY_VALUES", 		"values");
define("KEY_SIMFIN_ID", 	"simId");
define("KEY_FYEND", 		"fyearEnd");
define("KEY_EMPLOYEES", 	"employees");
define("KEY_SECTOR_NAME", 	"sectorName");
define("KEY_SECTOR_CODE", 	"sectorCode");
define("KEY_TICKER", 		"ticker");
define("KEY_ENTITY_NAME", 	"name");
define("KEY_SHRCLS_NAME", 	"shareClassName");
define("KEY_SHRCLS_TYPE", 	"shareClassType");
define("KEY_PRICE_DATA", 	"priceData");
define("KEY_PRICE_DATE", 	"date");
define("KEY_CLOSE_ADJ", 	"closeAdj");
define("KEY_SPLIT_COEF", 	"splitCoef");
define("KEY_SHARE_FIGURE", 	"figure");
define("KEY_MEASURE", 		"measure");
define("KEY_VALUE", 		"value");

define("VAL_DILUTED", 		"common-outstanding-diluted");
define("VAL_PERIOD", 		"period");

define("TBL_STMT_META", 	"STD_STATEMENT_META");
define("TBL_SCHEMES", 		"STD_STATEMENT_CALCULATION_SCHEMES");
define("TBL_VALUES", 		"STD_STATEMENT_VALUES");
define("TBL_TEMPLATES", 	"STD_INDUSTRY_TEMPLATES");
define("TBL_STMT_TYPES", 	"STD_STATEMENT_TYPES");
define("TBL_PERIODS", 		"PERIODS");
define("TBL_VAL_TYPES", 	"STD_STATEMENT_VALUE_NAMES");
define("TBL_ENTITIES", 		"ENTITIES");
define("TBL_SECTOR", 		"STD_SECTORS");
define("TBL_INDUSTRY", 		"STD_INDUSTRIES");
define("TBL_SHRCLS_TYPES", 	"SHARE_CLASS_TYPES");
define("TBL_SHRCLS_NAMES", 	"SHARE_CLASS_NAMES");
define("TBL_PRICES", 		"PRIMARY_SHARE_CLASS_PRICES");
define("TBL_SHARES", 		"SHARES_OUTSTANDING");

define("COL_STMT_ID", 		"STD_STATEMENT_ID");
define("COL_SIMFIN_ID", 	"SIMFIN_ID");
define("COL_STMT_TYPE_ID", 	"STD_STATEMENT_TYPE_ID");
define("COL_FYEAR", 		"FYEAR");
define("COL_PERIOD_ID", 	"PERIOD_ID");
define("COL_BEG_DATE", 		"PERIOD_BEGIN_DATE");
define("COL_END_DATE", 		"PERIOD_END_DATE");
define("COL_CALCULATED", 	"CALCULATED");
define("COL_TEMPLATE_ID", 	"STD_INDUSTRY_TEMPLATE_ID");
define("COL_TEMPLATE_NAME", "STD_INDUSTRY_TEMPLATE_NAME");
define("COL_SIGN", 			"SIGN");
define("COL_TID", 			"TEMPLATE_ID");
define("COL_UID", 			"UNIVERSAL_ID");
define("COL_NAME_ID", 		"STD_NAME_ID");
define("COL_PARENT_ID", 	"PARENT_TEMPLATE_ID");
define("COL_DISPLAY", 		"DISPLAY_TYPICAL");
define("COL_VALUE", 		"CHOSEN_VALUE");
define("COL_STMT_NAME", 	"STD_STATEMENT_NAME");
define("COL_PERIOD_NAME", 	"PERIOD_NAME");
define("COL_VAL_ID", 		"VALUE_ID");
define("COL_VAL_NAME", 		"VALUE_NAME");
define("COL_SECTOR_ID", 	"SECTOR_ID");
define("COL_SECTOR_CODE", 	"SECTOR_CODE");
define("COL_SECTOR_NAME", 	"SECTOR_NAME");
define("COL_INDUSTRY_ID", 	"INDUSTRY_ID");
define("COL_INDUSTRY_CODE", "INDUSTRY_CODE");
define("COL_INDUSTRY_NAME", "INDUSTRY_NAME");
define("COL_FYEAR_END", 	"FYEAR_MONTH_ENDING");
define("COL_EMPLOYEES", 	"EMPLOYEES");
define("COL_TICKER", 		"TICKER");
define("COL_ENTITY_NAME", 	"NAME");
define("COL_PRIMARY_SHRCLS","PRIMARY_SHARE_CLASS_ID");
define("COL_SHRCLS_TYPE_ID","CLASS_TYPE_ID");
define("COL_SHRCLS_TYPE_NM","CLASS_TYPE_NAME");
define("COL_SHRCLS_NAME_ID","CLASS_NAME_ID");
define("COL_SHRCLS_NAME", 	"CLASS_NAME");
define("COL_PRICE_DATE", 	"CLOSING_DATE");
define("COL_PRICE", 		"CLOSING_PRICE");
define("COL_SPLIT_COEF", 	"SPLIT_COEFFICIENT");
define("COL_SHARES", 		"SHARES_OUTSTANDING");

define("SQL_TRUE", 			"TRUE");
define("SQL_FALSE", 		"FALSE");
define("SQL_NULL", 			"NULL");

function insertEntity($db, $simfinId, $ticker, $name){

	$ticker = $db->real_escape_string($ticker);
	$name 	= $db->real_escape_string($name);

	// Cannot use REPLACE INTO because partcipating
	// delete will remove all related sheets on cascade

	$sql  = "INSERT INTO ".TBL_ENTITIES." ";
	$sql .= "(".COL_SIMFIN_ID.", ".COL_TICKER.", ";
	$sql .= COL_ENTITY_NAME.") VALUES ( ";
	$sql .= $simfinId.", '".$ticker."', '".$name."' );";

	if($db->query($sql) !== true){

		if($db->errno == DUPLICATE_ENTRY){

			$sql  = "UPDATE ".TBL_ENTITIES." SET ";
			$sql .= COL_TICKER." = '".$ticker."', ";
			$sql .= COL_ENTITY_NAME." = '".$name."' ";
			$sql .= "WHERE ".COL_SIMFIN_ID." = ".$simfinId.";";

			if($db->query($sql) !== true){

				$message  = "Entity overwrite failed, statement: ";
				$message .= "<".$sql."> error: <".$db->error.">";

				logError($message);

				return false;
			}

		} else {

			$message  = "Entity insertion failed, statement: ";
			$message .= "<".$sql."> error: <".$db->error.">";

			logError($message);

			return false;
		}
	}

	return true;
}

function updateEntity($db, $simId, $fye, $emp, $secId, $indId){

	$sql  = "UPDATE ".TBL_ENTITIES." SET ";
	$sql .= COL_FYEAR_END." = ".$fye.", ";
	$sql .= COL_EMPLOYEES." = ".$emp.", ";
	$sql .= COL_SECTOR_ID." = ".$secId.", ";
	$sql .= COL_INDUSTRY_ID." = ".$indId." ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ".$simId.";";

	if($db->query($sql) !== true){

		$message  = "Entity update failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function sectorCodeFromIndustryCode($industryCode){

	return (int) substr( (string) $industryCode, 0, 3);
}

function sectorNameFromIndustryCode($industryCode){

	global $__SECTORS;

	$sectorCode = sectorCodeFromIndustryCode($industryCode);
	$sectorName = $__SECTORS[$sectorCode];

	if(is_null($sectorName))
		logError("Invalid sector name, industry code ".$industryCode);

	return (is_null($sectorName) ? false : $sectorName);
}

function getSectorId($db, $sectorCode, $sectorName){

	$sectorName = $db->real_escape_string($sectorName);

	$sql  = "SELECT ".COL_SECTOR_ID." "; 
	$sql .= "FROM ".TBL_SECTOR." ";
	$sql .= "WHERE ".COL_SECTOR_CODE." = ";
	$sql .= $sectorCode." ;";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_SECTOR." ";
	$sql .= "(".COL_SECTOR_CODE.", ".COL_SECTOR_NAME.") ";
	$sql .= "VALUES (".$sectorCode.", ";
	$sql .= "'".$sectorName."');";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Sector add failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function getIndustryId($db, $industryCode, $industryName){

	$industryName = $db->real_escape_string($industryName);

	$sql  = "SELECT ".COL_INDUSTRY_ID." ";
	$sql .= "FROM ".TBL_INDUSTRY." ";
	$sql .= "WHERE ".COL_INDUSTRY_CODE." = ";
	$sql .= $industryCode." ;";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_INDUSTRY." ";
	$sql .= "(".COL_INDUSTRY_CODE.", ".COL_INDUSTRY_NAME.") ";
	$sql .= "VALUES (".$industryCode.", ";
	$sql .= "'".$industryName."');";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Industry id add failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function insertStatementMetadata($db, $simfinId, $statementTypeId,
	$fyear,
	$periodId,
	$calculated,
	$industryTemplateId){

	$pdName  = getPeriodName($db, $periodId);

	$begDate = getPeriodBegDate($db, $simfinId, $fyear, $pdName);
	$endDate = getPeriodEndDate($db, $simfinId, $fyear, $pdName);

	if($begDate === false || $endDate === false){

		$message  = "Could not establish statement beg/end dates ";
		$message .= "period id ".$periodId." bd ".$begDate." ed ";
		$message .= $endDate;

		logError($message);

		return false;
	}

	// Use of REPLACE INTO acceptable here because related
	// statement values will be updated shortly after

	$sql  = "REPLACE INTO ".TBL_STMT_META." ";
	$sql .= "(".COL_SIMFIN_ID.", ".COL_STMT_TYPE_ID.", ".COL_FYEAR.", ";
	$sql .= COL_PERIOD_ID.", ".COL_BEG_DATE.", ".COL_END_DATE.", ";
	$sql .= COL_CALCULATED.", ".COL_TEMPLATE_ID.") ";
	$sql .= "VALUES (".$simfinId.", ".$statementTypeId.", ";
	$sql .= $fyear.", ".$periodId.", '".$begDate."', '".$endDate."', ";
	$sql .= $calculated.", ".$industryTemplateId.");";

	if($db->query($sql) !== true){

		$message  = "Statement meta insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function clearCalculationScheme($db, $statementId){

	$sql  = "DELETE FROM ".TBL_SCHEMES." ";
	$sql .= "WHERE ".COL_STMT_ID." = ".$statementId;

	if($db->query($sql) !== true){

		$message  = "calculation scheme clear failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function insertCalculationScheme($db, $statementId, $scheme){

	for($idx = 0; $idx < count($scheme); ++$idx){

		$schemeElement = $scheme[$idx];

		$keys = array_keys($schemeElement);

		$pd = SQL_NULL;
		$fy = SQL_NULL;
		$sn = SQL_NULL;

		for($k = 0; $k < count($keys); ++$k){

			$key = $keys[$k];
			$val = $schemeElement[$key];

			switch($key){

				case KEY_PERIOD: 	$pd = $val; 	break;
				case KEY_FYEAR: 	$fy = $val; 	break;
				case KEY_SIGN: 		$sn = $val; 	break;
			}
		}

		$periodId = getPeriodId($db, $pd);

		$sql  = "REPLACE INTO ".TBL_SCHEMES." ";
		$sql .= "(".COL_STMT_ID.", ".COL_FYEAR.", ";
		$sql .= COL_PERIOD_ID.", ".COL_SIGN.") ";
		$sql .= " VALUES (".$statementId.", ";
		$sql .= $fy.", ".$periodId.", ".$sn.");";

		if($db->query($sql) !== true){

			$message  = "calculation scheme element insert failed, statement: ";
			$message .= "<".$sql."> error: <".$db->error.">";

			logError($message);

			return false;
		}
	}

	return true;
}

function insertStatementLineItems($db, $statementId, $lineItems){

	$valueIds 		= array();

	for($idx = 0; $idx < count($lineItems); ++$idx){

		$lineItem 		= $lineItems[$idx];
		$keys 			= array_keys($lineItem);

		$templateId 	= SQL_NULL;
		$universalId 	= SQL_NULL;
		$valueNameId 	= SQL_NULL;
		$parentId 		= SQL_NULL;
		$display 		= SQL_NULL;
		$value 			= SQL_NULL;

		for($k = 0; $k < count($keys); ++$k){

			$key = $keys[$k];
			$val = $lineItem[$key];

			switch($key){

				case KEY_TID:
					$templateId = $val;
				break;
				case KEY_UID:
					$universalId = $val;
				break;
				case KEY_NAME:
					$valueNameId = getStatementValueNameId($db, $val);
					$valueIds[$valueNameId] = $valueNameId;
				break;
				case KEY_PARENT:
					$parentId = $val;
				break;
				case KEY_DISPLAY:
					$display = $val;
				break;
				case KEY_VAL_CHOSEN:
					$value = $val;
				break;
			}
		}

		if($value !== null){

			$sql  = "REPLACE INTO ".TBL_VALUES." ";
			$sql .= "(".COL_STMT_ID.", ".COL_TID.", ";
			$sql .= COL_UID.", ".COL_NAME_ID.", ";
			$sql .= COL_PARENT_ID.", ".COL_DISPLAY.", ";
			$sql .= COL_VALUE.") VALUES (";
			$sql .= $statementId.", ".$templateId.", ";
			$sql .= $universalId.", ".$valueNameId.", ";
			$sql .= $parentId.", ".$display.", ";
			$sql .= $value.");";

			if($db->query($sql) !== true){

				$message  = "line item insert failed, statement: ";
				$message .= "<".$sql."> error: <".$db->error.">";

				logError($message);

				return false;
			}
		}
	}

	// remove any line items not present in the api response

	$sql  = "DELETE FROM ".TBL_VALUES." ";
	$sql .= "WHERE ".COL_STMT_ID." = ".$statementId." ";
	$sql .= "AND ".COL_NAME_ID." NOT IN ( ";

	$keys = array_keys($valueIds);

	for($k = 0; $k < count($keys); ++$k)
		if($k == count($keys) -1)
			$sql .= ($keys[$k]).");";
		else 
			$sql .= ($keys[$k]).", ";

	if($db->query($sql) !== true){

		$message  = "line item reconciliation failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function reconcileStatements($db, $simId, $statementIds){

	// remove any statements not received in last api call

	$sql  = "DELETE FROM ".TBL_STMT_META. " ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ".$simId." ";
	$sql .= "AND ".COL_STMT_ID." NOT IN ( ";

	$keys = array_keys($statementIds);

	for($k = 0; $k < count($keys); ++$k)
		if($k == count($keys) -1)
			$sql .= ($keys[$k]).");";
		else
			$sql .= ($keys[$k]).", ";

	if($db->query($sql) !== true){

		$message  = "sheet reconciliation failed, statement";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function getIndustryTemplateId($db, $templateName){

	$templateName = $db->real_escape_string($templateName);

	$sql  = "SELECT ".COL_TEMPLATE_ID." "; 
	$sql .= "FROM ".TBL_TEMPLATES." ";
	$sql .= "WHERE ".COL_TEMPLATE_NAME." = ";
	$sql .= "'".$templateName."' ;";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_TEMPLATES." ";
	$sql .= "(".COL_TEMPLATE_NAME.") ";
	$sql .= "VALUES ('".$templateName."');";

	if($db->query($sql) !== true){

		$message  = "Industry template insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function getStatementTypeId($db, $statementType){

	$statementType = $db->real_escape_string($statementType);

	$sql  = "SELECT ".COL_STMT_TYPE_ID." "; 
	$sql .= "FROM ".TBL_STMT_TYPES." ";
	$sql .= "WHERE ".COL_STMT_NAME." = ";
	$sql .= "'".$statementType."' ;";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_STMT_TYPES." ";
	$sql .= "(".COL_STMT_NAME.") ";
	$sql .= "VALUES ('".$statementType."');";

	if($db->query($sql) !== true){

		$message  = "Statement type insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function getPeriodId($db, $period){

	$period = $db->real_escape_string($period);

	$sql  = "SELECT ".COL_PERIOD_ID." ";
	$sql .= "FROM ".TBL_PERIODS." ";
	$sql .= "WHERE ".COL_PERIOD_NAME." = ";
	$sql .= "'".$period."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_PERIODS." ";
	$sql .= "(".COL_PERIOD_NAME.") ";
	$sql .= "VALUES ('".$period."');";

	if($db->query($sql) !== true){

		$message  = "Period name insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id 
			: SQL_NULL;	
}

function getPeriodName($db, $periodId){

	$sql  = "SELECT ".COL_PERIOD_NAME." ";
	$sql .= "FROM ".TBL_PERIODS." ";
	$sql .= "WHERE ".COL_PERIOD_ID." = ";
	$sql .= $periodId.";";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Period name query failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);
	else
		return false;
}

function getStatementValueNameId($db, $name){

	$name = $db->real_escape_string($name);

	$sql  = "SELECT ".COL_VAL_ID." ";
	$sql .= "FROM ".TBL_VAL_TYPES." ";
	$sql .= "WHERE ".COL_VAL_NAME." = ";
	$sql .= "'".$name."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_VAL_TYPES." ";
	$sql .= "(".COL_VAL_NAME.") ";
	$sql .= "VALUES ('".$name."');";

	if($db->query($sql) !== true){

		$message  = "Value name insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return SQL_NULL;
	}

	return 	( $db->insert_id > 0)
			? $db->insert_id 
			: SQL_NULL;
}

function getShareClassTypeId($db, $type){

	$type = $db->real_escape_string($type);

	$sql  = "SELECT ".COL_SHRCLS_TYPE_ID." ";
	$sql .= "FROM ".TBL_SHRCLS_TYPES." ";
	$sql .= "WHERE ".COL_SHRCLS_TYPE_NM." = ";
	$sql .= "'".$type."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_SHRCLS_TYPES." ";
	$sql .= "(".COL_SHRCLS_TYPE_NM.") ";
	$sql .= "VALUES ('".$type."');";

	if($db->query($sql) !== true){

		$message  = "Share class type insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return  ( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function getShareClassNameId($db, $name, $typeId){

	$name = $db->real_escape_string($name);

	$sql  = "SELECT ".COL_SHRCLS_NAME_ID." ";
	$sql .= "FROM ".TBL_SHRCLS_NAMES." ";
	$sql .= "WHERE ".COL_SHRCLS_NAME." = ";
	$sql .= "'".$name."';";

	$result = $db->query($sql);

	if($result->num_rows > 0)
		return (($result->fetch_row())[0]);

	$sql  = "INSERT INTO ".TBL_SHRCLS_NAMES." ";
	$sql .= "(".COL_SHRCLS_NAME.", ".COL_SHRCLS_TYPE_ID.") ";
	$sql .= "VALUES ('".$name."', ".$typeId.");";

	if($db->query($sql) !== true){

		$message  = "Share class name insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return  ( $db->insert_id > 0)
			? $db->insert_id
			: SQL_NULL;
}

function statementMetaExists($db, $simfinId, $type, $fyear, $period){

	$typeId 	= getStatementTypeId($db, $type);
	$periodId 	= getPeriodId($db, $period);

	$sql  = "SELECT ".COL_STMT_ID." ";
	$sql .= "FROM ".TBL_STMT_META." ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ".$simfinId." ";
	$sql .= "AND ".COL_STMT_TYPE_ID." = ".$typeId." ";
	$sql .= "AND ".COL_FYEAR." = ".$fyear." ";
	$sql .= "AND ".COL_PERIOD_ID." = ".$periodId.";";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Sheet meta exists failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);
	}

	return ($result->num_rows > 0);
}

function updateEntityShareClass($db, $simfinId, $shareClassId){

	$sql  = "UPDATE ".TBL_ENTITIES." SET ";
	$sql .= COL_PRIMARY_SHRCLS." = ".$shareClassId." ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ".$simfinId.";";

	if($db->query($sql) !== true){

		$message  = "Entity update failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function insertPricePoint($db, $simfinId, $date, $price, $coeff){

	$date = $db->real_escape_string($date);

	$sql  = "REPLACE INTO ".TBL_PRICES." (";
	$sql .= COL_SIMFIN_ID.", ".COL_PRICE_DATE.", ";
	$sql .= COL_PRICE.", ".COL_SPLIT_COEF.") ";
	$sql .= "VALUES (".$simfinId.", '".$date."', ";
	$sql .= $price.", ".$coeff.");";

	if($db->query($sql) !== true){

		$message  = "Price point insert failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

function getEntityIds($db){

	$sql  = "SELECT ".COL_SIMFIN_ID." ";
	$sql .= "FROM ".TBL_ENTITIES." ";
	$sql .= "ORDER BY ".COL_SIMFIN_ID." ASC;";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Entity ids query failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	$ids = array();
	$idx = 0;

	while(($row = $result->fetch_assoc()) !== null)
		$ids[$idx++] = (int) $row[COL_SIMFIN_ID];

	return $ids;
}

function getStmtMetaDenormalized($db, $simfinId){

	$sql  = "SELECT ".COL_STMT_NAME.", ";
	$sql .= COL_FYEAR.", ".COL_PERIOD_NAME." ";
	$sql .= "FROM ".TBL_STMT_META." ";
	$sql .= "INNER JOIN ".TBL_STMT_TYPES." ON ";
	$sql .= TBL_STMT_TYPES.".".COL_STMT_TYPE_ID;
	$sql .= " = ".TBL_STMT_META.".".COL_STMT_TYPE_ID." ";
	$sql .= "INNER JOIN ".TBL_PERIODS." ON ";
	$sql .= TBL_PERIODS.".".COL_PERIOD_ID." = ";
	$sql .= TBL_STMT_META.".".COL_PERIOD_ID." ";
	$sql .= "WHERE ".TBL_STMT_META.".".COL_SIMFIN_ID;
	$sql .= " = ".$simfinId.";";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Sheet data query failed, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;

	} else {

		return ($result->fetch_all(MYSQLI_ASSOC));
	}
}

function getFYMonthEnding($db, $simfinId){

	$sql  = "SELECT ".COL_FYEAR_END." ";
	$sql .= "FROM ".TBL_ENTITIES." ";
	$sql .= "WHERE ".COL_SIMFIN_ID." = ";
	$sql .= $simfinId.";";

	$result = $db->query($sql);

	if($result === false){

		$message  = "Could not get fy month ending, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	if($result->num_rows <= 0){

		$message  = "no result from fy month query, simid ";
		$message .= $simfinId;

		logError($message);

		return false;

	} else {

		return (int) (($result->fetch_row())[0]);
	}
}

function getPeriodBegMoOffset($period){

	global $__PD_BEG_MO_OFFSETS;

	$offset = $__PD_BEG_MO_OFFSETS[$period];

	if(is_null($offset)){

		$message = "Invalid period offset parameter - ".$period;

		logError($message);

		return false;
	}

	return $offset;
}

function getPeriodEndMoOffset($period){

	global $__PD_END_MO_OFFSETS;

	$offset = $__PD_END_MO_OFFSETS[$period];

	if(is_null($offset)){

		$message = "Invalid period offset parameter - ".$period;

		logError($message);

		return false;
	}

	return $offset;
}

function getPeriodEndDate($db, $simfinId, $fy, $period){

	$monthEnding = getFYMonthEnding($db, $simfinId);
	$calYear 	 = $fy;

	if($monthEnding === false){

		$message  = "Could not get fy mo end for ".$simfinId;
		$message .= ", statement: <".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	$endMoOffset = getPeriodEndMoOffset($period);

	if($endMoOffset === false){

		$message  = "Invalid month offset ".$endMoOffset;

		logError($message);

		return false;
	}

	$pdEndMo = $monthEnding +$endMoOffset;

	if($pdEndMo <= 0){

		--$calYear;
		$pdEndMo += 12;
	}

	$pdEndMo = ($pdEndMo < 10)
			 ? "0".strval($pdEndMo)
			 : strval($pdEndMo);

	$dateString = $calYear."-".$pdEndMo."-01";

	$date = new DateTime($dateString);

	return $date->format("Y-m-t");
}

function getPeriodBegDate($db, $simfinId, $fy, $period){

	$monthEnding = getFYMonthEnding($db, $simfinId);
	$calYear 	 = $fy;

	if($monthEnding === false){

		$message  = "Could not get fy mo end for ".$simfinId;
		$message .= ", statement: <".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	$begMoOffset = getPeriodBegMoOffset($period);

	if($begMoOffset === false){

		$message  = "Invalid month offset";

		logError($message);

		return false;
	}

	$pdBegMo = $monthEnding +$begMoOffset;

	if($pdBegMo <= 0){

		--$calYear;
		$pdBegMo += 12;
	}

	$pdBegMo = ($pdBegMo < 10)
			 ? "0".strval($pdBegMo)
			 : strval($pdBegMo);

	$dateString = $calYear."-".$pdBegMo."-01";

	$date = new DateTime($dateString);

	return $date->format("Y-m-d");
}

function insertSharesOutstanding($db, $simfinId, $fyear, $period, $shares){

	$periodId  = getPeriodId($db, $period);
	$pdBegDate = getPeriodBegDate($db, $simfinId, $fyear, $period);
	$pdEndDate = getPeriodEndDate($db, $simfinId, $fyear, $period);

	if($periodId  === false
	|| $pdBegDate === false
	|| $pdEndDate === false){

		$message  = "invalid date/period params for shares outstanding ";
		$message .= "periodId ".$periodId." begin date ".$pdBegDate." ";
		$message .= "end date ".$pdEndDate;

		logError($message);

		return false;
	}

	$sql  = "REPLACE INTO ".TBL_SHARES." (";
	$sql .= COL_SIMFIN_ID.", ".COL_FYEAR.", ";
	$sql .= COL_PERIOD_ID.", ".COL_BEG_DATE.", ";
	$sql .= COL_END_DATE.", ".COL_SHARES.") ";
	$sql .= "VALUES (".$simfinId.", ".$fyear.", ";
	$sql .= $periodId.", '".$pdBegDate."', '";
	$sql .= $pdEndDate."', ".$shares.");";

	if($db->query($sql) !== true){

		$message  = "could not insert shares outstanding, statement: ";
		$message .= "<".$sql."> error: <".$db->error.">";

		logError($message);

		return false;
	}

	return true;
}

?>