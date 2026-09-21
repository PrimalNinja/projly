<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: actionEntityDataList, actionFormDataList - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only list their own entity
//
// PURPOSE: list non-form entities data (entitycode and dataentitycode should be the same for non-forms)
//
// PARAMETERS: entitycode, order (o), filter (o) offset (o)
// 
// PSEUDOCODE:
//		A: read entity code
//
//		B: check permission
//
//		C: searching & paging
//		D: read from client
//		E: include filter
//		F: include formatter
//		G: work out what table to read from
//		H: read from the table
//		I: return the result
//
function actionEntityDataList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();

	// A:
	$strEntityCode = getJSONParameter($arrParameters_a, 'entitycode');
	// A:end











    // permission check
	// B:
    if (!hasPermission($objConn_a, 'VW_' . ffeu($strEntityCode), __FUNCTION__, true)) {return false;}
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


	//$blnExclusive = toBoolean(getJSONParameter($arrParameters_a, 'exclusive'));




//logDebug('JPC:' . print_r($arrFixedFilter, true), '');

	// fix special meaning filter values
	if (is_array($arrFixedFilter))
    {
		$intI = 0;
        foreach ($arrFixedFilter as $arrField) {
        
			$strField = $arrField['field'];
			$strValue = $arrField['value'];
			$arrFixedFilter[$intI]['value'] = translateCustomEntityIDs($objConn_a, $strValue, true);

			$intI++;
        }    
    }

	if (is_array($arrPassedFilter))
    {
		$intI = 0;
        foreach ($arrPassedFilter as $arrField) {
        
			$strField = $arrField['field'];
			$strValue = $arrField['value'];
			$arrPassedFilter[$intI]['value'] = translateCustomEntityIDs($objConn_a, $strValue, true);

			$intI++;
        }    
    }

//logDebug('JPC2:' . print_r($arrFixedFilter, true), '');

	// client bypass
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strEntityCode) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}

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
    
    // for mapping and security purposes
	$arrFields = array();
	if (dependencies('entity/inc-idwhitelist')) 
	{
		$arrFields = getIDWhiteList($objConn_a, '');
	}
    
	// initialisations
	// D:
    $strClientID = $_SESSION['server_loggedin_clientid'];
	// D:end
	    
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strEntityCode) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID == getSystemOwnerClientID($objConn_a));
		}
	}
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strEntityCode) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID == getSystemClientID($objConn_a));
		}
	}
	
	// get filter
	// E:
	$strGetCountCallFunc = '';
	$strGetDataCallFunc = '';
	$strDecodeFilterCallFunc = '';
	$strFilterCallFunc = '';
    if (dependencies('entity/filters/' . ffel($strEntityCode), true)) 
	{ 
		$strGetCountCallFunc = 'getCount_' . ffel($strEntityCode);        
		$strGetDataCallFunc = 'getData_' . ffel($strEntityCode);        
        $strDecodeFilterCallFunc = 'decodeFilter_' . ffel($strEntityCode);        
        $strFilterCallFunc = 'getFilter_' . ffel($strEntityCode);        
    }
    else if (dependencies('entity/filters/default')) 
	{ 
		$strGetCountCallFunc = 'getCount_default';        
		$strGetDataCallFunc = 'getData_default';        
        $strDecodeFilterCallFunc = 'decodeFilter_default';
        $strFilterCallFunc = 'getFilter_default';
    }
	// E:end
        
//logDebug('JPCX:' . print_r($arrFixedFilter, true), '');
	$arrFixedFilter = call_user_func($strDecodeFilterCallFunc, $objConn_a, $strFilter, $arrFixedFilter);
	$arrPassedFilter = call_user_func($strDecodeFilterCallFunc, $objConn_a, $strFilter, $arrPassedFilter);
//logDebug('JPCY:' . print_r($arrFixedFilter, true), '');
	
	// get formatter
	// F:
	$strFormatterCallFunc = '';
    if (dependencies('entity/formatters/' . ffel($strEntityCode), true)) 
	{ 
        $strFormatterCallFunc = 'getFormatter_' . ffel($strEntityCode);        
    }
    else if (dependencies('entity/formatters/default')) 
	{ 
        $strFormatterCallFunc = 'getFormatter_default';
    }
	// F:
        
	//$strSearchableFields = getSearchableFields($objConn_a, 'SYSTEMFORM', $strEntityCode);
	
	//$arrSearchableFields = explode(',', $strSearchableFields);
	//if (strlen($strSearchableFields) > 0)
	//{
		//$strSearchableFields = ',' . $strSearchableFields;
	//}
	
	// G: note, can only be an entity
	$strTableNameEntity = getTableNameEntity($strEntityCode, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strEntityCode);
	
	$blnIsExtended = isExtended($objConn_a, $strEntityCode);
		

	// get table count
	$strSQL = "";
	//if (ffel($strEntityCode) == "dataform")
	//{
		//$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and is_deleted = 'N'";
	//}
	//else
	//{
	$strSQL = call_user_func($strGetCountCallFunc, $objConn_a, $strEntityCode, $blnIgnoreClient, $strClientID, $strFilter, $arrFixedFilter, $arrPassedFilter, false, [], '', '', '');
	if (strlen($strSQL) == 0)
	{
		if ($blnIsExtended)
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
			else
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id and e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
		}
		else
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e";
				$strSQL .= dbBuildWhere('where', $arrFields, $arrFixedFilter);
			}
			else
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e where e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
		}
	}
	//}
    $strSQL .= call_user_func($strFilterCallFunc, $objConn_a, 'and', $strFilter, array(), $arrFixedFilter, $arrPassedFilter);
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    //$strSQL = "select id, code, description, is_enabled, jsondata from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~";
	// JC added below SQL construct visit http://stackoverflow.com/questions/4481388/why-does-mysql-higher-limit-offset-slow-the-query-down
	// to speed up LIMIT with very large OFFSET
	//if (ffel($strEntityCode) == "dataform")
	//{
		//$strSQL = "select t.id, t.code, t.description, t.is_enabled, t.jsondata, t.modifydatetime, t.modifyuser from (select id from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and is_deleted = 'N'";
	//}
	//else
	//{
	$strSQL = call_user_func($strGetDataCallFunc, $objConn_a, $strEntityCode, $blnIgnoreClient, $strClientID, $strFilter, $arrFixedFilter, $arrPassedFilter, false, [], '', '', '', $arrOrder, $intOffset, $intLimit);
	if (strlen($strSQL) == 0)
	{
		if ($blnIsExtended)
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select t.*, te.* from (select e.id from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
			else
			{
				$strSQL = "select t.*, te.* from (select e.id from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id and e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
		}
		else
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select t.* from (select e.id from ~TABLENAMEENTITY~ e";
				$strSQL .= dbBuildWhere('where', $arrFields, $arrFixedFilter);
			}
			else
			{
				$strSQL = "select t.* from (select e.id from ~TABLENAMEENTITY~ e where e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter);
			}
		}
		//}
		
		//$strOrderBy = dbBuildOrderBy($arrFields, $arrOrder, "modifydatetime desc, id");
		$strOrderByE = '';
		$strOrderByT = '';
		
		if (is_array($arrOrder) && count($arrOrder) > 0)
		{
			$strOrderByE = dbBuildOrderBy($arrFields, $arrOrder, "e.modifydatetime desc, e.id");
			$strOrderByT = dbBuildOrderBy($arrFields, $arrOrder, "t.modifydatetime desc, t.id");
		}
		else
		{ 
			$strOrderByE = entityBuildOrderBy($objConn_a, $strEntityCode, "e.modifydatetime desc, e.id");
			$strOrderByT = entityBuildOrderBy($objConn_a, $strEntityCode, "t.modifydatetime desc, t.id");
		}

		$strSQL .= call_user_func($strFilterCallFunc, $objConn_a, 'and', $strFilter, array(), $arrFixedFilter, $arrPassedFilter);
		$strSQL .= $strOrderByE;    
		if ($blnIsExtended)
		{
			$strSQL .= " limit ~OFFSET~, ~LIMIT~) q join ~TABLENAMEENTITY~ t on t.id = q.id join ~TABLENAMEENTITYEXTENSION~ te on te.id = t.id";
		}
		else
		{
			$strSQL .= " limit ~OFFSET~,~LIMIT~) q join ~TABLENAMEENTITY~ t on t.id = q.id"; // . NONAJAXGRIDLIMIT;
		}
		$strSQL .= $strOrderByT;    
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~OFFSET~', ff($intOffset), $strSQL);
		$strSQL = str_replace('~LIMIT~', ff($intLimit), $strSQL);
		//$strSQL = str_replace('~SEARCHABLEFIELDS~', $strSearchableFields, $strSQL);
	// G:end
	}

	// H:
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    $intRowNum = $intOffset + 1;
                
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = call_user_func($strFormatterCallFunc, $objConn_a, $arrRow, 'ENTITY_', $strEntityCode, $intRowNum, $intRecordCount, $intLimit);
        $intRowNum++;
    }

    dbCloseRecordset($objResult);
	// H:end
                 
	// I:
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	// I:end
}
