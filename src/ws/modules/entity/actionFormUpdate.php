<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataUpdate, actionFormUpdate and actionFormDataUpdate - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// notes: a client can update their own systemform data
//
// PURPOSE: add a form template
// 
// PARAMETERS: entitycode, entitydataid, formentitycode, jsondata
// 
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is updated for current client only
//		D: update the provided json data to the entity (which can be an entity only)
//		E: return the result
//
function actionFormUpdate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/entityDataUpdate')) 
	{ 
		// A:
		$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
		// A:end

		if (ffel($strEntityCode) != 'systemform')
		{
			safetyDie('invalid entity');
		}
		
        // permission check
		// B:
		if (!hasPermission($objConn_a, 'DEVELOPER', __FUNCTION__, true)) {return false;}
		// B:end

        // parameters
		$strEntityDataID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entitydataid'), 'entitydataid', true);
		$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
        $arrJSONData = getJSONParameter($arrParameters_a, 'jsondata');
		$arrJSONData = formRevertSecuredIDs($arrJSONData);
                
        // initialisations
		// C:
        $strClientID = $_SESSION['server_loggedin_clientid'];
		// C:end
                                
        dbBeginTrans($objConn_a, __FUNCTION__);
		// D:
		$blnResult = entityDataUpdate($objConn_a, $strClientID, $strEntityCode, $strFormEntityCode, $strEntityDataID, $arrJSONData, '', '', '');
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
