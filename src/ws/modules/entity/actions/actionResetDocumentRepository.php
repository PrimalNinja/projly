<?php
 
function actionResetDocumentRepository($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
	$strResult = "";

    if (dependencies('core/resetDocumentRepository')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'RST_DOCUMENTREPOSITORY', __FUNCTION__, true)) {return false;}

		// parameters
		$strDocumentRepositoryID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

		// initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];    
		
		dbBeginTrans($objConn_a, __FUNCTION__);
		$strResult = resetDocumentRepository($objConn_a, $strClientID, $strDocumentRepositoryID);
		
		if (dbEndTrans($objConn_a, __FUNCTION__)) {

			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'Document repository reset.', array());
		} 
		else 
		{
			$strDescription = dbErrorDescription(true);
			if (strlen($strDescription) == 0)
			{
				$strDescription = "Error resetting document repository.";
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
	}	
        
    return $strResult;
}
