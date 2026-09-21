<?php

// install a file format
// note: if installing into the sysadmin area they come from the default area, otherwise they come from the sysadmin area
function actionImportFileFormatInstall($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('import/fileFormatInstall')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'INS_FILEFORMAT', __FUNCTION__, true)) {return false;}

        // parameters
        $strFileFormatIDFrom = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
        $blnSystemArea = $_SESSION['server_loggedin_system'];
        $strClientIDTo = $_SESSION['server_loggedin_clientid'];
        $strClientIDFrom = "";
		
        if ($blnSystemArea) 
		{
            // install from the default area
            $strClientIDFrom = getDefaultClientID($objConn_a);
        } 
		else 
		{
            // install from the system area
            $strClientIDFrom = getSystemClientID($objConn_a);
        }

        dbBeginTrans($objConn_a, __FUNCTION__);
        $strFileFormatIDTo = fileFormatInstall($objConn_a, $strClientIDFrom, $strClientIDTo, $strFileFormatIDFrom);
        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
			$arrResult = array();
            $arrResult[] = array("id" => secureEntityValue('FILEFORMAT', $strFileFormatIDTo));

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error installing file format.', array());
        }
    }

    return $strResult;
}
