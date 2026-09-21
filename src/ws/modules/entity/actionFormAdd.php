<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataAdd, actionFormAdd and actionFormDataAdd - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only add their own systemform
// 
// PURPOSE: add a form template
//
// PARAMETERS: entitycode, formentitycode, jsondata
//
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is added for current client only
//		D: add the provided json data to the entity (which can be an entity only)
//		E: return the result
//
function actionFormAdd($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/entityDataAdd')) 
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
		$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
		$arrJSONData = getJSONParameter($arrParameters_a, 'jsondata');
		$arrJSONData = formRevertSecuredIDs($arrJSONData);




		
        // initialisations
		// C:
        $strClientID = $_SESSION['server_loggedin_clientid'];
		// C:end
        
		// temporarily fetch based on entitycode if entityid is not known
		$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~ENTITYCODE~', ffel($strEntityCode), $strSQL);
		$strEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        dbBeginTrans($objConn_a, __FUNCTION__);
		// D:
        $strEntityDataID = entityDataAdd($objConn_a, $strClientID, $strEntityID, $strEntityCode, $strFormEntityCode, $arrJSONData, '', '', '');        
		// D:end
		
		// E:
        if (dbEndTrans($objConn_a, __FUNCTION__) && (strlen($strEntityDataID) > 0)) 
		{
			$strDescription = dbSuccessMessage();
			$arrResult = array();
            $arrResult[] = array("id" => secureEntityValue($strFormEntityCode, $strEntityDataID));
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, $arrResult);
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
				$strDescription = 'Error adding ' . $strDescription . ' data.';
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
		// E:end
    }
          
    return $strResult;
}
