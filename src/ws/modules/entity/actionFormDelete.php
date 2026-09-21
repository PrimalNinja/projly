<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataDelete, actionFormDelete and actionFormDataDelete - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can delete their own systemform
// 
// PURPOSE: delete a form template
// 
// PARAMETERS: entitycode, entitydataid
//
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: data is added for current client only
//		D: delete the entity (which can be an entity only)
//		E: return the result
//
function actionFormDelete($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	
    $strResult = "";
            
    if (dependencies('entity/entityDataDelete')) 
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
        //$strEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entityid'), 'entityid', true);
		//$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
		$strEntityDataID = revertSecuredValue( getJSONParameter($arrParameters_a, 'entitydataid'), 'entitydataid', true);
          






		  
        // initialisations
		// C:
        $strClientID = $_SESSION['server_loggedin_clientid'];
		// C:end
               


			   
        dbBeginTrans($objConn_a, __FUNCTION__);
		// D:
        $blnResult = entityDataDelete($objConn_a, $strClientID, $strEntityCode, $strEntityDataID, '', '', '');
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
