<?php
 
function actionResetPermission($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
	$strResult = "";

    if (dependencies('security/resetPermissions')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

		// parameters
		//$strFormEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'formentityid'), 'id', true);

		//$strRelativeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'relativeid'), 'id', false);
		//$strRelative = getJSONParameter($arrParameters_a, 'relative');
		//$strRelationship = getJSONParameter($arrParameters_a, 'relationship');

		// initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];    
		
		dbBeginTrans($objConn_a, __FUNCTION__);
		$strResult = resetPermissions($objConn_a, $strClientID);
		
		if (dbEndTrans($objConn_a, __FUNCTION__)) {

			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'Permissions reset.', array());
		} 
		else 
		{
			$strDescription = dbErrorDescription(true);
			if (strlen($strDescription) == 0)
			{
				$strDescription = "Error resetting permissions.";
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
	}	
        
    return $strResult;
}
