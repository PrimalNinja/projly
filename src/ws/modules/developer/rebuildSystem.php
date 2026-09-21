<?php

function rebuildSystem($objConn_a, $strClientID_a, $strRebuildSystemBuildID_a, $strGUID_a, $strSystemDescription_a)
{    
    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);
    $strTableNameSystemBuildTask = getTableNameEntity("systembuildtask", false);
    
	$blnResult = false;

	$strLogin = $_SESSION['server_loggedin_user'];
	$strClientID = $strClientID_a;

	$strGUID = $strGUID_a;

	$strCode = $strGUID;
	$strDescription = $strSystemDescription_a;
	$strDate = getDateTime();
	$strTime = date("H:m:s");
	$strStatus = "INPROGRESS";
	$strFolderName = cleanFilename($strGUID);

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SYSTEMBUILD");
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "CODE", $strCode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "DESCRIPTION", $strDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "DATE", $strDate);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "TIME", $strTime);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "STATUS", $strStatus);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "FOLDER", $strFolderName);

	$strJSONData = json_encode($arrJSONData);
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "systembuild");

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "insert into ~TABLNAMESYSTEMBUILD~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
	$strSQL = str_replace('~TABLNAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~CODE~', $strCode, $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
	$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strSystemBuildID = dbLastInsertID($objConn_a);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILD', $strSystemBuildID, $strJSONData);
			
	// create the folder
	if (strlen($strSystemBuildID) > 0)
	{
		createFolder(BUILD_PATH . $strFolderName, false);

		$strSQL = "insert into ~TABLENAMESYSTEMBUILDTASK~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, sortorder, modifyuser, modifydatetime, systembuild_id, systemmodule_id,
					gd8fac874_b432_4019_b5b0_abefded76d05_command, 
					gd8fac874_b432_4019_b5b0_abefded76d05_parameters,
					gd8fac874_b432_4019_b5b0_abefded76d05_starttime,
					gd8fac874_b432_4019_b5b0_abefded76d05_endtime,
					gd8fac874_b432_4019_b5b0_abefded76d05_is_processed
					)
					select client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, sortorder, '~MODIFYUSER~', '~MODIFYDATETIME~', ~SYSTEMBUILDID~, systemmodule_id,
					gd8fac874_b432_4019_b5b0_abefded76d05_command,
					gd8fac874_b432_4019_b5b0_abefded76d05_parameters,
					'',
					'',
					'N'
					from ~TABLENAMESYSTEMBUILDTASK~ where systembuild_id=~REBUILDSYSTEMBUILDID~";
		$strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~SYSTEMBUILDID~', $strSystemBuildID, $strSQL);
		$strSQL = str_replace('~REBUILDSYSTEMBUILDID~', $strRebuildSystemBuildID_a, $strSQL);            
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
	}

	return dbEndTrans($objConn_a, __FUNCTION__);
}