<?php

// fetch and download published forms
function actionDownloadPublishedForms($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();
 
	// permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

	// initialisations

	$strClientID = $_SESSION['server_loggedin_clientid'];

	if (dependencies('sync/fetchPublishedForms,sync/fetchProjlyForms'))
	{
		if (APP_CODE === 'qims') 
		{
			$arrResult = fetchPublishedForms($objConn_a, $strClientID);			
		}
		else // projlyfms
		{
			$arrResult = fetchProjlyForms($objConn_a, $strClientID);	
		}
	}
	    
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
