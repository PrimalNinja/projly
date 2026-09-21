<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataUpdate, actionFormUpdate and actionFormDataUpdate - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can update their own entity
// 
// PURPOSE: update non-form entities data (entitycode and dataentitycode should be the same for non-forms)
//
// PARAMETERS: entitycode, entitydataid, dataentitycode, jsondata
// 
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is updated for current client only
//		D: update the provided json data to the entity (which can be an entity only)
//		E: return the result
//
function actionEntityDataUpdate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/entityDataUpdate')) 
	{ 
		// A:
		$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');

		$strRelativeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'relativeid'), 'id', false);
		$strRelative = getJSONParameter($arrParameters_a, 'relative');
		$strRelationship = getJSONParameter($arrParameters_a, 'relationship');
		// A:end

        // permission check
		// B:
        if (!hasPermission($objConn_a, 'EDT_' . ffeu($strEntityCode), __FUNCTION__, true)) {return false;}
		// B:end

        // parameters
		$strEntityDataID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entitydataid'), 'entitydataid', true);
		$strDataEntityCode = getJSONParameter($arrParameters_a, 'dataentitycode');
        $arrJSONData = getJSONParameter($arrParameters_a, 'jsondata');
        $arrJSONData = formRevertSecuredIDs($arrJSONData);
                
        // initialisations
		// C:
        $strClientID = $_SESSION['server_loggedin_clientid'];
		// C:end
                                
        dbBeginTrans($objConn_a, __FUNCTION__);
		// D:
		$blnResult = entityDataUpdate($objConn_a, $strClientID, $strEntityCode, $strDataEntityCode, $strEntityDataID, $arrJSONData, $strRelativeID, $strRelative, $strRelationship);
		// D:end

		// E:
        if ($blnResult && dbEndTrans($objConn_a, __FUNCTION__)) 
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
				$strSQL = str_replace('~ENTITYCODE~', ffel($strEntityCode), $strSQL);
				$strDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				$strDescription = 'Error updating ' . $strDescription . ' data.';
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
		// E:end
    }
    
    return $strResult;
}
