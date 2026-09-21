<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataDelete, actionFormDelete and actionFormDataDelete - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can delete their own systemform data
// 
// PURPOSE: delete a filled in form
// 
// PARAMETERS: entitycode, formentitycode, formentitydataid
//
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is added for current client only
//		D: delete the entity (which can be an entity or formentity)
//		E: return the result
//
function actionFormDataDelete($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/formDataDelete')) 
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
		if (!hasPermission($objConn_a, 'DEL_' . ffeu($strFormEntityCode), __FUNCTION__, true)) {return false;}
		// B:end

        // parameters
        //$strEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entityid'), 'entityid', true);
		//$strFormEntityDataID = getJSONParameter($arrParameters_a, 'formentitydataid');
		//$strFormEntityDataID = translateCustomEntityIDs($objConn_a, $strFormEntityDataID, true);
		//$strFormEntityDataID = revertSecuredValue($strFormEntityDataID, 'formentitydataid', true);

		$strFormEntityDataIDList = getParameterIDList($arrParameters_a, true);

        // initialisations
		// C:
        $strClientID = $_SESSION['server_loggedin_clientid'];
		// C:end
                        
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		// D:
		formDataDelete($objConn_a, $strClientID, $strEntityCode, $strFormEntityCode, $strFormEntityDataIDList, $strRelativeID, $strRelative, $strRelationship);        
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

				if (dbErrorCode() == INTEGRITYVIOLATION) 
				{
					$strDescription = getIntegrityErrorDescription($objConn_a, dbErrorDescription(false), $strDescription);
				} 
				else 
				{
					$strDescription = 'Error deleting ' . $strDescription . ' data.';
				}
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
		}
		// E:end
    }
    
    return $strResult;
}
