<?php

define("ERROR_LOG_PATH", 			dirname(__FILE__,3)."/log/errorLog.txt");
define("ERROR_LOG_LINES", 		1024);
define("ACTIVITY_LOG_PATH", 	dirname(__FILE__,3)."/log/activityLog.txt");
define("ACTIVITY_LOG_LINES",	1024);
define("SQL_DUMP_DIR", 				dirname(__FILE__,3)."/bkp/");
define("SQL_DUMP_NEW", 				"simfinDumpNew.sql.gz");
define("SQL_DUMP_CURRENT", 		"simfinDump.sql.gz");

?>