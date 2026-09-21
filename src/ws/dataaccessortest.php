<?php

require_once('inc-env.php');
require_once('inc-constants.php');
require_once('inc-settings.php');
require_once('inc-temporaryvalues.php');
require_once('inc-app-' . APP_CODE . '.php');
require_once('inc-dbsettings-client.php');
require_once('inc-dbsettings-system.php');
require_once('modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

// global dictionary
$g_arrDefaultDictionary = array();	// defaults go in here        
$g_arrDictionary = array();

if (dependencies('system/debug,system/generic,system/logging,system/sessions,system/webService') &&
	dependencies('utils/database,utils/dbConnection,utils/docs,utils/entity,utils/file,utils/forms,utils/formtools,utils/general,utils/dates,utils/import,utils/json,utils/messaging,utils/money,utils/reports,utils/security,utils/xml'))
{
    $api = new webService;
    $api->initSession();

    $objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);

    require_once('modules/entity/dataaccess/gender.php');

    /*    
    $strResult = add_gender($objConn, [], [
        [
            "f" => "field1",
            "v" => 199,
            "d" => "N"
        ],
        [
            "f" => "field2",
            "v" => NULL,
            "d" => "NN"
        ],
        [
            "f" => "field3",
            "v" => "value2",
            "d" => "s"
        ],
        [
            "f" => "field4",
            "v" => NULL,
            "d" => "sn"
        ],
        [
            "f" => "field5",
            "v" => "2020-10-19",
            "d" => "d"
        ],
        [
            "f" => "field6",
            "v" => NULL,
            "d" => "dn"
        ]
    ]);
    */

    $strResult = update_gender($objConn, [], 999, NULL, [
        [
            "f" => "field1",
            "v" => 199,
            "d" => "N"
        ],
        [
            "f" => "field2",
            "v" => NULL,
            "d" => "NN"
        ],
        [
            "f" => "field3",
            "v" => "value2",
            "d" => "s"
        ],
        [
            "f" => "field4",
            "v" => NULL,
            "d" => "sn"
        ],
        [
            "f" => "field5",
            "v" => "2020-10-19",
            "d" => "d"
        ],
        [
            "f" => "field6",
            "v" => NULL,
            "d" => "dn"
        ]
    ]);
            

    //$strResult = delete_gender($objConn, 999, "field1=1111");       
    //$strResult = fetch_gender($objConn, 999, "");       
    //$strResult = fetchValue_gender($objConn, "field1", 999, "field=999");       
    echo $strResult;
}
else
{
    echo "dependencies error";
}
/*
$intExecutionTime = ini_get('max_execution_time');
set_time_limit(intval(BATCH_MAX_RUN_TIME, 10));
date_default_timezone_set(TIMEZONE);

set_time_limit($intExecutionTime);
*/
?>