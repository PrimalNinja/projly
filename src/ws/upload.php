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

set_time_limit(intval(WS_MAX_RUN_TIME, 10));
date_default_timezone_set(TIMEZONE);

if (dependencies('system/debug,system/generic,system/logging,system/sessions,system/webService') &&
	dependencies('utils/database,utils/dbConnection,utils/docs,utils/entity,utils/file,utils/forms,utils/formtools,utils/general,utils/dates,utils/import,utils/json,utils/money,utils/reports,utils/security') &&
	dependencies('3p/UploadHandler'))
{
	$upload_handler = new UploadHandler(array('upload_dir' => TEMP_UPLOAD_PATH));
}

?>