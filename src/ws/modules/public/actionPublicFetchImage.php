<?php

// fetch an image
// example URL: http://localhost/jsoncv/fetch.php?image=abc123
function actionPublicFetchImage($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    if (dependencies('docs/documentDownload')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // parameters
        $strDocumentID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
        $blnForceDownload = false;

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];

		documentDownload($objConn_a, $strClientID, $strDocumentID, $blnForceDownload);
    }
}
