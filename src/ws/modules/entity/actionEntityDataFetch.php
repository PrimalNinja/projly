<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataFetch, actionFormFetch and actionFormDataFetch - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can fetch their own entity
// 
// PURPOSE: fetch non-form entities data (entitycode and dataentitycode should be the same for non-forms)
//
// PARAMETERS: entitycode, entitydataid
//
// PSEUDOCODE:
//		A: read entity code
//
//		B: check permission based on entitycode
//
//		C: work out the client to fetch data from (data is fetched for current client only)
//		D: work out what table to fetch from
//		E: read the data to fetch
//
//		F: return the result
//
function actionEntityDataFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();
  
	

	
	// A:
	$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
	// A:end








	// permission check
	// B:
	if (!hasPermission($objConn_a, 'VW_' . ffeu($strEntityCode), __FUNCTION__, true)) {return false;}
	// B:end

	// parameters
	$strEntityDataID = revertSecuredValue($arrParameters_a['entitydataid'], 'entitydataid', true);

	








	



	


	
	

	// initialisations
	// C:
    $strClientID = $_SESSION['server_loggedin_clientid'];
	// C:end
     
	 
	 
	 
	 
	// D:
	$strTableNameEntity = getTableNameEntity($strEntityCode, false);
	// D:end
	
    // fetch
	// E:
    $strSQL = "select id, jsondata from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
    $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
    $strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($arrRow = dbReadRecord($objResult)) {
        $arrResult[] = array(
            "id" => secureEntityValue($strEntityCode, $arrRow['id']),
            "jsondata" => formSecureIDs(json_decode($arrRow['jsondata'], true))
        );
    }
	
    dbCloseRecordset($objResult);
	// E:end

	// F:
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	// F:end
}

