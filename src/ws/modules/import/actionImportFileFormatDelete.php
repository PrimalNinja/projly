<?php

// delete a file format
function actionImportFileFormatDelete($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('import/fileFormatDelete')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'DEL_FF', __FUNCTION__, true)) {return false;}

        // parameters
        $strFileFormatIDList = getParameterIDList($arrParameters_a, true);

        if (strlen($strFileFormatIDList) > 0) 
		{
            // initialisations
            $strClientID = $_SESSION['server_loggedin_clientid'];
            $strUserID = $_SESSION['server_loggedin_userid'];

            dbBeginTrans($objConn_a, __FUNCTION__);
            fileFormatDelete($objConn_a, $strClientID, $strFileFormatIDList);
            if (dbEndTrans($objConn_a, __FUNCTION__)) 
			{
                $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
            } 
			else 
			{
				if (dbErrorCode() == INTEGRITYVIOLATION) 
				{
					$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'File format cannot be deleted, try deactivating it instead.', array());
				} 
				else 
				{
					$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error deleting file format.', array());
				}
            }
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        }
    }

    return $strResult;
}
