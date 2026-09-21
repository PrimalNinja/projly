<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: formDataListDataForm, formDataListSystemForm - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only list their own systemform or dataform
// 
// PURPOSE: list entered forms
//
function formDataListSystemForm($objConn_a, $strClientID_a, $strFormEntityCode_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $intOffset_a, $intLimit_a, $arrOrder_a) 
{ 
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);
	$strTableNameBranchUser = getTableNameEntity('branch_user', false);
	$strTableNameUserBranch = getTableNameEntity('user_branch', false);
	
	$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
	$strUserID = $_SESSION['server_loggedin_userid'];

    $arrResult = array();
	$strEntityType = 'ENTITY_';
	$strFormEntityCodePlugin = $strFormEntityCode_a;
	$strRelative = $strRelative_a;
	$strSQL = "";

	$arrFixedFilter = array();
	if (is_array($arrFixedFilter_a))
	{
		$arrFixedFilter = $arrFixedFilter_a;
	}

	$arrPassedFilter = array();
	if (is_array($arrPassedFilter_a))
	{
		$arrPassedFilter = $arrPassedFilter_a;
	}

	// filter application
	$blnFilterApplication = false;
	if (InStr(',' . ENTITIESFILTEREDBYAPPLICATION . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	{
		$blnFilterApplication = true;
	}

	// device restriction
	$blnRestrictToDevice = false;
	$blnIsPublic = $_SESSION['server_loggedin_public'];
	$strDeviceIDCookie = $_SESSION['server_loggedin_cookie'];
	if ($blnIsPublic && (InStr(',' . ENTITIESFILTEREDBYDEVICE . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0))
	{
		$blnRestrictToDevice = true;
	}

	// branch restriction
	$blnRestrictToBranch = false;
	$blnRestrictToBranchSelected = false;
	$blnIsPublic = $_SESSION['server_loggedin_public'];
	$strBranchID = $_SESSION['server_loggedin_branchid'];
	if (InStr(',' . ENTITIESFILTEREDBYBRANCH . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	{
		$blnRestrictToBranch = true;
	}
	// if (InStr(',' . ENTITIESFILTEREDBYBRANCHSELECTED . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	// {
		// $blnRestrictToBranchSelected = true;
	// }
	
	// client bypass
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}

	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
		}
	}
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
		}
	}
	
    // for mapping and security purposes
	$arrFields = array();
	if (dependencies('entity/inc-idwhitelist')) 
	{
		$arrFields = getIDWhiteList($objConn_a, $strFormEntityCodePlugin);
	}
            
	// initialisations
	// D:
	// D:end
	
	// BEFORE LIST
	$strBeforeAfterCallFunc = '';
	if (dependencies('entity/beforeafter/' . ffel($strFormEntityCodePlugin), true)) 
	{
		$strBeforeAfterCallFunc = 'beforeList_' . ffel($strFormEntityCodePlugin);        
	}
	
	if (function_exists($strBeforeAfterCallFunc))
	{
		call_user_func($strBeforeAfterCallFunc, $objConn_a, $strClientID_a);
	}

	// get filter
	// E:
	$strGetCountCallFunc = '';
	$strGetDataCallFunc = '';
	$strDecodeFilterCallFunc = '';
	$strFilterCallFunc = '';
    if (dependencies('entity/filters/' . ffel($strFormEntityCodePlugin), true)) 
	{ 
		$strGetCountCallFunc = 'getCount_' . ffel($strFormEntityCodePlugin);        
		$strGetDataCallFunc = 'getData_' . ffel($strFormEntityCodePlugin);        
        $strDecodeFilterCallFunc = 'decodeFilter_' . ffel($strFormEntityCodePlugin);        
        $strFilterCallFunc = 'getFilter_' . ffel($strFormEntityCodePlugin);        
    }
    else if (dependencies('entity/filters/default')) 
	{ 
		$strGetCountCallFunc = 'getCount_default';        
		$strGetDataCallFunc = 'getData_default';        
        $strDecodeFilterCallFunc = 'decodeFilter_default';
        $strFilterCallFunc = 'getFilter_default';
    }
	// E:end
        
	$arrFixedFilter = call_user_func($strDecodeFilterCallFunc, $objConn_a, $strFilter_a, $arrFixedFilter);
	$arrPassedFilter = call_user_func($strDecodeFilterCallFunc, $objConn_a, $strFilter_a, $arrPassedFilter);
	$arrFixedFilterExclusive = array();
	if ($blnExclusive_a)
	{
		// if exclusive then swap them around
		$arrFixedFilterExclusive = $arrFixedFilter;
		$arrFixedFilter = array();
	}

	// get formatter
	// F:
	$strFormatterCallFunc = '';
    if (dependencies('entity/formatters/' . ffel($strFormEntityCodePlugin), true)) 
	{ 
        $strFormatterCallFunc = 'getFormatter_' . ffel($strFormEntityCodePlugin);        
    }
    else if (dependencies('entity/formatters/default')) 
	{ 
        $strFormatterCallFunc = 'getFormatter_default';
    }
	// F:end
    
	$strSearchableFields = getSearchableFields($objConn_a, 'SYSTEMFORM', $strFormEntityCode_a);
	
	$arrSearchableFields = array();
	if (strlen($strSearchableFields) > 0)
	{
		$arrSearchableFields = explode(',', $strSearchableFields);
	}
    
    // By Jhun
    // need to add the searchable fields to arrFields
    // since they are also used in search and sorting
//logDebug('JPC1:' . print_r($arrSearchableFields, true), '');
	$arrSearchableFields = filterSearcheableFields($arrFields, $arrSearchableFields);
//logDebug('JPC2:' . print_r($arrSearchableFields, true), '');
    if (count($arrSearchableFields) > 0) 
	{ 
        foreach ($arrSearchableFields as $strSearchableField) 
		{ 
			if (!isset($arrFields[$strSearchableField]))
			{
				$arrFields[$strSearchableField] = $strSearchableField;
			}
        }
    }
    
	//if (strlen($strSearchableFields) > 0)
	//{
		//$strSearchableFields = ',' . $strSearchableFields;
	//}

	// G: note, can be an entity or formentity
	$strSQL = call_user_func($strGetCountCallFunc, $objConn_a, $strFormEntityCode_a, $blnIgnoreClient, $strClientID_a, $strFilter_a, $arrFixedFilter, $arrPassedFilter, false, $arrFields, $strRelativeID_a, $strRelative, $strRelationship_a);
	if (strlen($strSQL) == 0)
	{
		if ($blnIsExtended)
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');	// NOTE: FIXED FILTERS CAN ONLY BE IN THE MAIN TABLE
			}
			else
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id and e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');	// NOTE: FIXED FILTERS CAN ONLY BE IN THE MAIN TABLE
			}
			if ($blnFilterApplication)
			{
				$strSQL .= " and (e.applications = '' or e.applications is null or e.applications like '%~APPCODE~%')";
			}
			if ($blnRestrictToDevice)
			{
				$strSQL .= " and (e.deviceid = '~DEVICEID~')";
			}
			if ($blnRestrictToBranch)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
				else
				{
					$strSQL .= " and (ee.branch_id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
			}
			if ($blnRestrictToBranchSelected)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id = ~BRANCHID~)";
				}
				else
				{
					$strSQL .= " and (ee.branch_id = ~BRANCHID~)";
				}
			}
		}
		else
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e where 1=1";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');
			}
			else
			{
				$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~ e where e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');
			}
			if ($blnFilterApplication)
			{
				$strSQL .= " and (e.applications = '' or e.applications is null or e.applications like '%~APPCODE~%')";
			}
			if ($blnRestrictToDevice)
			{
				$strSQL .= " and (e.deviceid = '~DEVICEID~')";
			}
			if ($blnRestrictToBranch)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
				else
				{
					$strSQL .= " and (e.branch_id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
			}
			if ($blnRestrictToBranchSelected)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id = ~BRANCHID~)";
				}
				else
				{
					$strSQL .= " and (e.branch_id = ~BRANCHID~)";
				}
			}
		}

		$strSQL .= call_user_func($strFilterCallFunc, $objConn_a, 'and', $strFilter_a, $arrSearchableFields, $arrFixedFilter, $arrPassedFilter);
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
		$strSQL = str_replace('~TABLENAMEBRANCHUSER~', ff($strTableNameBranchUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranch), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
		$strSQL = str_replace('~DEVICEID~', ff($strDeviceIDCookie), $strSQL);
		$strSQL = str_replace('~BRANCHID~', ff($strBranchID), $strSQL);
	}

	$intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	//$strSQL = "select id, code, description, is_enabled, jsondata from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~";
	// JC added below SQL construct visit http://stackoverflow.com/questions/4481388/why-does-mysql-higher-limit-offset-slow-the-query-down
	// to speed up LIMIT with very large OFFSET
	
	$strSQL = call_user_func($strGetDataCallFunc, $objConn_a, $strFormEntityCode_a, $blnIgnoreClient, $strClientID_a, $strFilter_a, $arrFixedFilter, $arrPassedFilter, false, $arrFields, $strRelativeID_a, $strRelative, $strRelationship_a, $arrOrder_a, $intOffset_a, $intLimit_a);
	if (strlen($strSQL) == 0)
	{
		//$strOrderBy = dbBuildOrderBy($arrFields, $arrOrder_a, "modifydatetime desc, id");        
        // arrOrder is empty. check for dispaly order and display order fields and apply

		$strOrderByE = '';
		$strOrderByT = '';
        if (is_array($arrOrder_a) && (count($arrOrder_a) > 0))
        {
            $strOrderByE = dbBuildOrderBy($arrFields, $arrOrder_a, "e.modifydatetime desc, e.id");
			$strOrderByT = dbBuildOrderBy($arrFields, $arrOrder_a, "t.modifydatetime desc, t.id");
        }
        else
        { 
            $strOrderByE = entityBuildOrderBy($objConn_a, $strFormEntityCode_a, "e.modifydatetime desc, e.id");
			$strOrderByT = entityBuildOrderBy($objConn_a, $strFormEntityCode_a, "t.modifydatetime desc, t.id");
        }

		if ($blnIsExtended)
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select t.*, te.* from (select e.id from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');	// NOTE: FIXED FILTERS CAN ONLY BE IN THE MAIN TABLE
			}
			else
			{
				$strSQL = "select t.*, te.* from (select e.id from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where ee.id = e.id and e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');	// NOTE: FIXED FILTERS CAN ONLY BE IN THE MAIN TABLE
			}
			if ($blnFilterApplication)
			{
				$strSQL .= " and (e.applications = '' or e.applications is null or e.applications like '%~APPCODE~%')";
			}
			if ($blnRestrictToDevice)
			{
				$strSQL .= " and (e.deviceid = '~DEVICEID~')";
			}
			if ($blnRestrictToBranch)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
				else
				{
					$strSQL .= " and (ee.branch_id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
			}
			if ($blnRestrictToBranchSelected)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id = ~BRANCHID~)";
				}
				else
				{
					$strSQL .= " and (ee.branch_id = ~BRANCHID~)";
				}
			}
		}
		else
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select t.* from (select id from ~TABLENAMEENTITY~ e where 1=1";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');
			}
			else
			{
				$strSQL = "select t.* from (select id from ~TABLENAMEENTITY~ e where e.client_id = ~CLIENTID~";
				$strSQL .= dbBuildWhere('and', $arrFields, $arrFixedFilter, 'e.');
			}
			if ($blnFilterApplication)
			{
				$strSQL .= " and (e.applications = '' or e.applications is null or e.applications like '%~APPCODE~%')";
			}
			if ($blnRestrictToDevice)
			{
				$strSQL .= " and (e.deviceid = '~DEVICEID~')";
			}
			if ($blnRestrictToBranch)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
				else
				{
					$strSQL .= " and (e.branch_id in (select branch_id from ~TABLENAMEUSERBRANCH~ where user_id = ~USERID~))";
				}
			}
			if ($blnRestrictToBranchSelected)
			{
				// TODO: ~TABLENAMEBRANCHUSER~
				if ($strFormEntityCode_a == 'BRANCH')
				{
					$strSQL .= " and (e.id = ~BRANCHID~)";
				}
				else
				{
					$strSQL .= " and (e.branch_id = ~BRANCHID~)";
				}
			}
		}

		$strSQL .= call_user_func($strFilterCallFunc, $objConn_a, 'and', $strFilter_a, $arrSearchableFields, $arrFixedFilter, $arrPassedFilter);
		$strSQL .= $strOrderByE;    
		if ($blnIsExtended)
		{
			$strSQL .= " limit ~OFFSET~, ~LIMIT~) q join ~TABLENAMEENTITY~ t on t.id = q.id join ~TABLENAMEENTITYEXTENSION~ te on te.id = t.id";
		}
		else
		{
			$strSQL .= " limit ~OFFSET~, ~LIMIT~) q join ~TABLENAMEENTITY~ t on t.id = q.id";
		}
		$strSQL .= $strOrderByT;    
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
		$strSQL = str_replace('~TABLENAMEBRANCHUSER~', ff($strTableNameBranchUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranch), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~OFFSET~', ff($intOffset_a), $strSQL);
		$strSQL = str_replace('~LIMIT~', ff($intLimit_a), $strSQL);
		$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
		$strSQL = str_replace('~DEVICEID~', ff($strDeviceIDCookie), $strSQL);
		$strSQL = str_replace('~BRANCHID~', ff($strBranchID), $strSQL);
		//$strSQL = str_replace('~SEARCHABLEFIELDS~', $strSearchableFields, $strSQL);
		// G:end
	}

	// H:
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    $intRowNum = $intOffset_a + 1;
                
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = call_user_func($strFormatterCallFunc, $objConn_a, $arrRow, $strEntityType, $strFormEntityCodePlugin, $intRowNum, $intRecordCount, $intLimit_a);
        $intRowNum++;
    }
    dbCloseRecordset($objResult);
	// H:end
                 




	// logging
    if (dependencies('utils/log')) 
	{
		createAuditEntityListDataLog($objConn_a, "systemform", $strFormEntityCode_a, "");
	}

	// I:
    return $arrResult;
	// I:end
}
