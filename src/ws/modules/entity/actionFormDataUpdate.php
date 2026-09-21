<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataUpdate, actionFormUpdate and actionFormDataUpdate - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can update their own systemform
// 
// PURPOSE: save entered forms
// 
// PARAMETERS: entitycode, formentitycode, jsondata, formentitydataid (o), submittoclientid (o)
// 
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is updated for current client only
//		D: update the provided json data to the entity (which can be an entity or formentity)
//		E: return the result
//
function actionFormDataUpdate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";

    if (dependencies('entity/formDataUpdate')) 
	{ 
		// A:
		$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
		$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');

		$strRelativeID = getJSONParameter($arrParameters_a, 'relativeid');
		$strRelativeID = translateCustomEntityIDs($objConn_a, $strRelativeID, true);
		$strRelativeID = revertSecuredValue($strRelativeID, 'id', false);

		$strRelative = getJSONParameter($arrParameters_a, 'relative');
		$strRelationship = getJSONParameter($arrParameters_a, 'relationship');
		// A:end
		
		if (ffel($strEntityCode) != 'systemform')
		{
			safetyDie('invalid entity');
		}
		
        // permission check
		// B:
		if (!hasPermission($objConn_a, 'EDT_' . ffeu($strFormEntityCode), __FUNCTION__, true)) {return false;}
		// B:end

        // parameters
		//$strEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entityid'), 'entityid', true);
		$strFormEntityDataID = getJSONParameter($arrParameters_a, 'formentitydataid');
		$strFormEntityDataID = translateCustomEntityIDs($objConn_a, $strFormEntityDataID, true);
		$strFormEntityDataID = revertSecuredValue($strFormEntityDataID, 'formentitydataid', true);

        $arrJSONData = getJSONParameter($arrParameters_a, 'jsondata');
        $arrJSONData = formRevertSecuredIDs($arrJSONData);
		$strSubmitToClientID = revertSecuredValue(getJSONParameter($arrParameters_a, 'submittoclientid'), 'submittoclientid', false);

        // initialisations
		// C:
		$strClientID = $_SESSION['server_loggedin_clientid'];    
		// C:end

		if (strlen($strSubmitToClientID) == 0)
		{
			$strSubmitToClientID = $strClientID;
		}
                
		// temporarily fetch based on entitycode if entityid is not known
		$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~ENTITYCODE~', ffel($strEntityCode), $strSQL);
		$strEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		dbBeginTrans($objConn_a, __FUNCTION__);
		// D:
		$blnResult = formDataUpdate($objConn_a, $strSubmitToClientID, $strEntityID, $strEntityCode, $strFormEntityCode, $strFormEntityDataID, $strClientID, $arrJSONData, $strRelativeID, $strRelative, $strRelationship);
		// D:end
		
		// E:
		if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
			$strDescription = dbSuccessMessage();
			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, array());
		} 
		else 
		{
			$strDescription = dbErrorDescription(true);
			if (strlen($strDescription) == 0)
			{
				$strSQL = "select description returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~ENTITYCODE~', ffel($strFormEntityCode), $strSQL);
				$strDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				$strDescription = 'Error updating ' . $strDescription . ' data.';
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
		// E:end
    }
    
    return $strResult;
}
