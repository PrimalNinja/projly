<?php

// download a document
function actionDocsDocumentDownload($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    if (dependencies('docs/documentDownload')) 
	{
        $arrResult = array();

        // permission check
        if (!hasPermission($objConn_a, 'VW_DOCUMENT', __FUNCTION__, true)) {return false;}

        // parameters
        $strDocumentID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
        $blnForceDownload = toBoolean(getJSONParameter($arrParameters_a, 'download'));

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];

		documentDownload($objConn_a, $strClientID, $strDocumentID, $blnForceDownload);
    }
}
