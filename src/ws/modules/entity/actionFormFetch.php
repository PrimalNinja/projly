<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataFetch, actionFormFetch and actionFormDataFetch - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can fetch their own systemform
// 
// PURPOSE: fetch a form template (either for filling in a new form or editing the template)
// 
// PARAMETERS: entitycode, entitydataid (o), formcode (o)
//
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//
//		C: work out the client to fetch data from
//		D: work out what table to fetch from
//		E: read the data to fetch
//		F: return the result
//
function actionFormFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();
	$arrJSONData = array();
	$strClientID = "";

	// A:
    $strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');

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

	// parameters
	$strEntityDataID = revertSecuredValue(getJSONParameter($arrParameters_a, 'entitydataid'), 'entitydataid', false);
	$strFormCode = getJSONParameter($arrParameters_a, 'formcode');

	$strTableNameEntity = getTableNameEntity($strEntityCode, false);

	// permission check
	// B:
	// note: system forms will usually fetch based on entityid's
	if (strlen($strEntityDataID) > 0)
	{
		// do nothing
	}
	else
	{
		if (!hasPermission($objConn_a, 'VW_' . ffeu($strFormCode), __FUNCTION__, true)) {return false;}
	}
	// B:end

	






	
	


	





	
	
	// initialisations
	// C:
	if (ffel($strEntityCode) == 'systemform')
	{
		$strClientID = getSystemClientID($objConn_a);
	}
	else
	{
		$strClientID = $_SESSION['server_loggedin_clientid'];
	}
	// C:end
       



	   
    // fetch
	// E:
	$strSQL = "";
	if (strlen($strEntityDataID) > 0)
	{
		$strSQL = "select id, code, jsondata from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID), $strSQL);
	}
	else if (ffel($strEntityCode) == 'systemform')
	{
		$strSQL = "select id, code, jsondata from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and code = '~CODE~'";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strFormCode), $strSQL);
	}
    
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if ($arrRow = dbReadRecord($objResult)) 
	{
        if (dependencies('entity/entitySystemFormFileJSON'))
        {            
            if (toBoolean(FETCHSYSTEMFORMSFROMFILE))
            {
                $strJSONData = entitySystemFormFileJSON($arrRow['code']);

                if (strlen($strJSONData) == 0)
                {
                    $strJSONData = $arrRow['jsondata'];
                }

                $arrJSONData = json_decode($strJSONData, true);
            }
            else
            {
                $arrJSONData = json_decode($arrRow['jsondata'], true);
            }
        }
		
		$strFormEntityCode = formValueGetBySectionTypeFieldCode($arrJSONData, "FORMHEADER", "ENTITY");
		
		// BEFORE DISPLAYNEW for renderer only, not builder
		if (strlen($strFormCode) > 0)
		{
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode), true)) { 
				$strClientID = $_SESSION['server_loggedin_clientid'];
                $strBeforeAfterCallFunc = 'beforeDisplayAddUpdate_' . ffel($strFormEntityCode);                   
				$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode, $strFormEntityCode, $strClientID, $arrJSONData, "", $strRelativeID, $strRelative, false, "add");
			}
		}

        $arrResult[] = array(
            "id" => secureEntityValue($strEntityCode, $arrRow['id']),
            "jsondata" => formSecureIDs($arrJSONData)
        );    
    }
    
    dbCloseRecordset($objResult); 
	// E:end
    
	// F:
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	// F:end
}

