<?php
function actionRosterTemplateCreateRoster($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = '';

    if (dependencies('projly/rosterTemplateCreateRoster')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid']; 

        dbBeginTrans($objConn_a, __FUNCTION__);

        rosterTemplateCreateRoster($objConn_a, $strClientID, $strID);

        if (dbEndTrans($objConn_a, __FUNCTION__)) {

			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'Roster created.', array());
		} 
		else 
		{
			$strDescription = dbErrorDescription(true);
			if (strlen($strDescription) == 0)
			{
				$strDescription = "Error encountered when creating a roster.";
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
    }

    return $strResult;
}