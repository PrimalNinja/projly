<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataAdd, actionFormAdd and actionFormDataAdd - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only add their own entity data
// 
// PURPOSE: add non-form entities data (entitycode and dataentitycode should be the same for non-forms)
//
// PARAMETERS: entitycode, dataentitycode, jsondata
//
// PSEUDOCODE:
//		A: read entity code
//
//		B: check permission based on entitycode
//		C: data is added for current client only
//		D: add the provided json data to the entity (which can be an entity only)
//		E: return the result
//
function actionEntityDataAdd($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/entityDataAdd')) 
	{ 
		// A:
		$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');

		$strRelativeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'relativeid'), 'id', false);
		$strRelative = getJSONParameter($arrParameters_a, 'relative');
		$strRelationship = getJSONParameter($arrParameters_a, 'relationship');
		// A:end

		
		
		
		
		
		
		
        // permission check
		// B:
        if (!hasPermission($objConn_a, 'ADD_' . ffeu($strEntityCode), __FUNCTION__, true)) {return false;}
		// B:end

        // parameters
		$strDataEntityCode = getJSONParameter($arrParameters_a, 'dataentitycode');
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
        $strEntityDataID = entityDataAdd($objConn_a, $strClientID, $strEntityID, $strEntityCode, $strDataEntityCode, $arrJSONData, $strRelativeID, $strRelative, $strRelationship);        
		// D:end

		// E:
        if ((strlen($strEntityDataID) > 0) && dbEndTrans($objConn_a, __FUNCTION__)) 
		{
			$strDescription = dbSuccessMessage();
            $arrResult[] = array("id" => secureEntityValue($strDataEntityCode, $strEntityDataID));
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strDescription, $arrResult);
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
				$strDescription = 'Error adding ' . $strDescription . ' data.';
			}
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
		// E:end
    }
          
    return $strResult;
}
