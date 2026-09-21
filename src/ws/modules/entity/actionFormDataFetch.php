<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataFetch, actionFormFetch and actionFormDataFetch - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can fetch their own or other clients systemform data
// 
// PURPOSE: fetch a filled in form
// 
// PARAMETERS: entitycode, formentitycode, uselatestform, formentitydataid (o), formentitydatacode (o), submittoclientid (o), relativeid, relative, relationship
//
// PSEUDOCODE:
//		A: read entity code
//		B: check permission based on entitycode
//		C: work out the client to fetch data from
//		D: work out what table to fetch from
//		E: read the data to fetch
//		F: return the result
//
function actionFormDataFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();

	if (dependencies('entity/formDataFetchSystemForm'))
	{
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
		
		$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
		
		if (strlen($strFormEntityCode) == 0)
		{
			// future: validate the actual code exists for systemform
			safetyDie('invalid entity');
		}

		// permission check
		// B: 
		if (!hasPermission($objConn_a, 'VW_' . ffeu($strFormEntityCode), __FUNCTION__, true)) {return false;}
		// B:end

		// parameters
		$strFormEntityDataID = getJSONParameter($arrParameters_a, 'formentitydataid');
		$strFormEntityDataID = translateCustomEntityIDs($objConn_a, $strFormEntityDataID, true);
		$strFormEntityDataID = revertSecuredValue($strFormEntityDataID, 'formentitydataid', false);
		
		$strFormEntityDataCode = getJSONParameter($arrParameters_a, 'formentitydatacode');
		$blnUseLatestForm = toBoolean(getJSONParameter($arrParameters_a, 'uselatestform'));
		$strSubmitToClientID = revertSecuredValue(getJSONParameter($arrParameters_a, 'submittoclientid'), 'submittoclientid', false);
        $strHistoryID = revertSecuredValue(getJSONParameter($arrParameters_a, 'historyid'), 'historyid', false);
		$strMode = getJSONParameter($arrParameters_a, 'mode');
//debug($strEntityCode . ":" . $strFormEntityCode . ":" . $blnUseLatestForm . ":" . $strFormEntityDataID . ":" . $strSubmitToClientID . ":" . $strHistoryID);

		// initialisations
		// C:
		$strClientID = $_SESSION['server_loggedin_clientid'];    
		// C:end

		if (strlen($strSubmitToClientID) == 0)
		{
			$strSubmitToClientID = $strClientID;
		}

		// D: handled by underlying function
		// E: handled by underlying function
		$arrResult = formDataFetchSystemForm($objConn_a, $strClientID, $strSubmitToClientID, $strEntityCode, $strFormEntityCode, $strFormEntityDataID, $strFormEntityDataCode, $blnUseLatestForm, $strHistoryID, $strRelativeID, $strRelative, $strRelationship, $strMode);
	}
    
	// F:
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	// F:end
}

