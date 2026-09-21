<?php

require_once('../inc-env.php');
require_once('../inc-constants.php');
require_once('../inc-settings.php');
require_once('../inc-temporaryvalues.php');
require_once('../inc-app-' . APP_CODE . '.php');
require_once('../inc-dbsettings-client.php');
require_once('../inc-dbsettings-system.php');
require_once('../modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

$strServerStatus = "";
$strServerMessage = "";
$strCheckboxValue = "";

if (dependencies('system/debug,system/logging') &&
	dependencies('utils/database,utils/dbConnection,utils/general,utils/dates,utils/security'))
{        
    $strError = '';
    $objConnSystem = null;
    try
    {
        $objConnSystem = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);

        if (isset($_POST["ge-submit-button"]) )
        {
            if (isset( $_POST["ge-serverdisabled-field"]))
            {
                $strSQL = "update ~TABLENAMESYSTEM~ set value = 'TRUE' where code = 'SERVER_DISABLED'";
				$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
                dbExecuteSQL($objConnSystem, $strSQL, __FUNCTION__);
				echo '<script language="javascript">';
				echo 'alert("IMPORTANT NOTE:  Server has been disabled with a message of << ' . $_POST["ge-servermessage-field"] . ' >>")';
				echo '</script>';
            }
            else
            {
                $strSQL = "update ~TABLENAMESYSTEM~ set value = 'FALSE' where code = 'SERVER_DISABLED'";
				$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
                dbExecuteSQL($objConnSystem, $strSQL, __FUNCTION__);
				echo '<script language="javascript">';
				echo 'alert("IMPORTANT NOTE: Server has been enabled.")';
				echo '</script>';
            }

            $strSQL = "update ~TABLENAMESYSTEM~ set value = '" . $_POST["ge-servermessage-field"] . "' where code = 'SERVER_DISABLED_REASON'";
			$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
            dbExecuteSQL($objConnSystem, $strSQL, __FUNCTION__);
        }

        $strSQL = "select value returnvalue from ~TABLENAMESYSTEM~ where code = 'SERVER_DISABLED'";
		$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
        $strServerStatus = dbReadValue($objConnSystem, $strSQL, __FUNCTION__);

        if ($strServerStatus == "TRUE")
        {
            $strCheckboxValue = "checked='checked'";
        }

        $strSQL = "select value returnvalue from ~TABLENAMESYSTEM~ where code = 'SERVER_DISABLED_REASON'";
		$strSQL = str_replace("~TABLENAMESYSTEM~", CORE_SYSTEM, $strSQL);
        $strServerMessage = dbReadValue($objConnSystem, $strSQL, __FUNCTION__);
		
		dbClose($objConnSystem);
    }
    catch (PDOException $e)
    {
        $strError = 'could not connect - ' . $e->getMessage();
    }
}


?>

<html>
    <head>
        <title>Server Disabled Configuration</title>
    </head>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            Server Disabled: <input type="checkbox" name="ge-serverdisabled-field" <?php echo $strCheckboxValue; ?>" />
            <br />
            Server Disabled Message: <input type="text" name="ge-servermessage-field" value="<?php echo($strServerMessage); ?>" style="width: 400px;" />
            <br />
            <br />
            <input name="ge-submit-button" type="submit">
        </form>
    </body>
</html>

