<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataList, actionFormDataList - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only list their own systemform
// 
// PURPOSE: list entered forms
// 
// PARAMETERS: entitycode, formentitycode, order (o), filter (o), offset (o)
// 
// PSEUDOCODE:
//		A: read entity code
//		B: entity & check permission
//		C: searching & paging
//		D: read from client
//		E: include filter
//		F: include formatter
//		G: work out what table to read from
//		H: read from the table
//		I: return the result
//
function actionFormDataList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();

	// A:
	$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
	// A:end
	
	if (ffel($strEntityCode) != 'systemform')
	{
		safetyDie('invalid entity');
	}
	
	$strFormEntityCode = getJSONParameter($arrParameters_a, 'formentitycode');
	
	if (strlen($strFormEntityCode) == 0)
	{
		safetyDie('invalid entity');
	}

	// permission check
	// B: 
	if (!hasPermission($objConn_a, 'VW_' . ffeu($strFormEntityCode), __FUNCTION__, true)) {return false;}
	// B:end

	// C:
    $strFilter = getJSONParameter($arrParameters_a, 'filter');

	// sometimes fixedfilter comes in as a string, sometimes an array so cater for both
	$strFixedFilter = '';
	$arrFixedFilter = array();
    $varFixedFilter = getJSONParameter($arrParameters_a, 'fixedfilter');
	if (is_string($varFixedFilter))
	{
		$strFixedFilter = $varFixedFilter;
		if (strlen($strFixedFilter) > 0)
		{
			$arrFixedFilter = json_decode($strFixedFilter, true);
		}
	}
	else if (is_array($varFixedFilter))
	{
		$arrFixedFilter = $varFixedFilter;
	}
	
	// sometimes passedfilter comes in as a string, sometimes an array so cater for both
	$strPassedFilter = '';
	$arrPassedFilter = array();
    $varPassedFilter = getJSONParameter($arrParameters_a, 'passedfilter');
	if (is_string($varPassedFilter))
	{
		$strPassedFilter = $varPassedFilter;
		if (strlen($strPassedFilter) > 0)
		{
			$arrPassedFilter = json_decode($strPassedFilter, true);
		}
	}
	else if (is_array($varPassedFilter))
	{
		$arrPassedFilter = $varPassedFilter;
	}
	
	// used to exclude selections from related entities, especially when adding to
	$blnExclusive = toBoolean(getJSONParameter($arrParameters_a, 'exclusive'));
	$strRelativeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'relativeid'), 'id', false);
	$strRelative = getJSONParameter($arrParameters_a, 'relative');
	$strRelationship = getJSONParameter($arrParameters_a, 'relationship');

//logDebug('JPC:' . print_r($arrFixedFilter, true), '');

	// fix special meaning filter values
	if (is_array($arrFixedFilter))
    {
		$intI = 0;
        foreach ($arrFixedFilter as $arrField) 
		{
			$strValue = $arrField['value'];
			$arrFixedFilter[$intI]['value'] = translateCustomEntityIDs($objConn_a, $strValue, true);

			$intI++;
        }    
    }
    
	if (is_array($arrPassedFilter))
    {
		$intI = 0;
        foreach ($arrPassedFilter as $arrField) 
		{
			$strValue = $arrField['value'];
			$arrPassedFilter[$intI]['value'] = translateCustomEntityIDs($objConn_a, $strValue, true);

			$intI++;
        }    
    }
    
//logDebug('JPC2:' . print_r($arrFixedFilter, true), '');










    // paging
    $intOffset = (int) getJSONParameter($arrParameters_a, 'offset');
    
    // limit    
    $intLimit = (int) getJSONParameter($arrParameters_a, 'limit');

    if ($intLimit == 0 || $intLimit > NONAJAXGRIDLIMIT) 
	{ 
       $intLimit = NONAJAXGRIDLIMIT;
    }
    else if ($intLimit < 0) 
	{ 
       $intLimit = 1; // make it one. 0 limit returns no record 
    }
    
    // ordering
    $arrOrder = getJSONParameter($arrParameters_a, 'order');
	
	// C:end
    
        
	// initialisations
	// D:
    $strClientID = $_SESSION['server_loggedin_clientid'];
	// D:end
    
	// G: note, can be an entity or formentity
	if ($blnExclusive)
	{
		if (dependencies('entity/formDataListSystemFormExclusive'))
		{
			$arrResult = formDataListSystemFormExclusive($objConn_a, $strClientID, $strFormEntityCode, $strFilter, $arrFixedFilter, $arrPassedFilter, $blnExclusive, $strRelativeID, $strRelative, $strRelationship, $intOffset, $intLimit, $arrOrder);
		}
	}
	else
	{
		if (dependencies('entity/formDataListSystemForm'))
		{
			$arrResult = formDataListSystemForm($objConn_a, $strClientID, $strFormEntityCode, $strFilter, $arrFixedFilter, $arrPassedFilter, $blnExclusive, $strRelativeID, $strRelative, $strRelationship, $intOffset, $intLimit, $arrOrder);
		}
	}
	// G:end
		
	// I:
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	// I:end
}
