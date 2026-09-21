<?php

function actionPrintPrintJobCompleted($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('print/printJobComplete')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
		
        // parameters
        $strPrintJobID = revertSecuredValue(getJSONParameter($arrParameters_a, 'printjob_id'), 'printjob_id', true);

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];

        dbBeginTrans($objConn_a, __FUNCTION__);

        printJobComplete($objConn_a, $strClientID, $strPrintJobID);
        
        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
            $arrResult[] = array("id" => secureEntityValue('PRINTJOB', $strPrintJobID));

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error updating Print Job Status', array());
        }
    }

    return $strResult;
}
