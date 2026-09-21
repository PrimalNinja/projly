<?php

// fetch all active startup items
function actionCoreStartupItemsFetchActive($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameStartupItem = getTableNameEntity("startupitem", false);
	
    $arrResult = array();

	// permission check
	if (!hasPermission($objConn_a, 'VW_STARTUPITEM', __FUNCTION__, true)) {return false;}

	$blnIsPublic = $_SESSION['server_loggedin_public'];
	
    // fetch
	$strSQL = "";
	if ($blnIsPublic)
	{
		$strSQL = "select ffa23f2204_c11e_41f3_8c3e_85234a8b683c_command command, ffa23f2204_c11e_41f3_8c3e_85234a8b683c_parameters parameters, ffa23f2204_c11e_41f3_8c3e_85234a8b683c_flags flags from ~TABLENAMESTARTUPITEM~ where is_enabled = 'Y' and ffa23f2204_c11e_41f3_8c3e_85234a8b683c_public = 'Y' order by ffa23f2204_c11e_41f3_8c3e_85234a8b683c_priority";
	}
	else
	{
		$strSQL = "select ffa23f2204_c11e_41f3_8c3e_85234a8b683c_command command, ffa23f2204_c11e_41f3_8c3e_85234a8b683c_parameters parameters, ffa23f2204_c11e_41f3_8c3e_85234a8b683c_flags flags from ~TABLENAMESTARTUPITEM~ where is_enabled = 'Y' order by ffa23f2204_c11e_41f3_8c3e_85234a8b683c_priority";
	}
	$strSQL = str_replace('~TABLENAMESTARTUPITEM~', ff($strTableNameStartupItem), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "command" => $arrRow['command'],
            "parameters" => $arrRow['parameters'],
            "flags" => $arrRow['flags']
        );
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
