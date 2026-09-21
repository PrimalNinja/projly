<?php
 
function actionProfilePermission_PermissionAddTo($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
	$strResult = "";
	
    if (dependencies('security/profilePermission_PermissionAddTo')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

		// parameters
		$strFormEntityIDList = getParameterIDList($arrParameters_a, true);

		//$strFormEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'formentityid'), 'id', true);

		$strRelativeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'relativeid'), 'id', false);
		$strRelative = getJSONParameter($arrParameters_a, 'relative');
		$strRelationship = getJSONParameter($arrParameters_a, 'relationship');

		// initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];    
		
		dbBeginTrans($objConn_a, __FUNCTION__);
		$strResult = profilePermission_PermissionAddTo($objConn_a, $strClientID, $strFormEntityIDList, $strRelativeID, $strRelationship);
		
		if (dbEndTrans($objConn_a, __FUNCTION__)) {

			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'Permission added.', array());
		} 
		else 
		{
			$strDescription = dbErrorDescription(true);
			if (strlen($strDescription) == 0)
			{
				$strDescription = "Error adding permission to profile.";
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
	}	
        
    return $strResult;
}
