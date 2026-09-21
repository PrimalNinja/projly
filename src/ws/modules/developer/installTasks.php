<?php

function installTasks($objConn_a, $strSystemModuleID_a, $strSystemBuildID_a)
{
    $strTableNameSystemModuleTask = getTableNameEntity("systemmoduletask", false);
    $strTableNameSystemBuildTask = getTableNameEntity("systembuildtask", false);

    $blnResult = true;

    $strLogin = $_SESSION['server_loggedin_user'];
	$strClientID = $_SESSION['server_loggedin_clientid'];  
    $strEntityID = getEntityID($objConn_a, "systemform");
    
    $strSQL = "select id, code, description, jsondata, sortorder from ~TABLENAMESYSTEMMODULETASK~ where systemmodule_id = ~SYSTEMMODULEID~";
    $strSQL = str_replace('~TABLENAMESYSTEMMODULETASK~', ff($strTableNameSystemModuleTask), $strSQL);
    $strSQL = str_replace('~SYSTEMMODULEID~', ff($strSystemModuleID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    while ($arrRow = dbReadRecord($objResult))
    {
        $strCode = "SYSTEMBUILDTASK";
        $strDescription = "System Build Task";
        $arrSystemModuleTaskJSONData = json_decode($arrRow['jsondata'], true);
        $intSortOrder = $arrRow['sortorder'];

        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SYSTEMBUILDTASK");

        $arrJSONData = formTransferSectionValues($arrSystemModuleTaskJSONData, "g4768521c-4d28-41fe-a8f8-6e0ec6cfee71", $arrJSONData, "gd8fac874-b432-4019-b5b0-abefded76d05");
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "IS_PROCESSED", 'N');
        $strJSONData = json_encode($arrJSONData);
		
		$strSQL = "insert into ~TABLENAMESYSTEMBUILDTASK~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, sortorder, modifyuser, modifydatetime, systembuild_id, systemmodule_id) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', ~SORTORDER~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~SYSTEMBUILDID~, ~SYSTEMMODULEID~)";
		$strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~CODE~', $strCode, $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
		$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff(getEntityID($objConn_a, "systembuildtask")), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~SORTORDER~', ff($intSortOrder), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~SYSTEMBUILDID~', $strSystemBuildID_a, $strSQL);
		$strSQL = str_replace('~SYSTEMMODULEID~', $strSystemModuleID_a, $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strSystemBuildTaskID = dbLastInsertID($objConn_a);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILDTASK', $strSystemBuildTaskID, $strJSONData);
    }

    dbCloseRecordset($objResult);

    return $blnResult;
}