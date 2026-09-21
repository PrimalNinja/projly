<?php

// $strFormDataID_a = DBPROCESS

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeList_dbprocess($objConn_a, $strClientID_a)
{
	$strTableNameDBProcess = getTableNameEntity("dbprocess", false);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "dbprocess");
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "delete from ~TABLENAMEDBPROCESS~";
	$strSQL = str_replace("~TABLENAMEDBPROCESS~", $strTableNameDBProcess, $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	$strSQL = "insert into ~TABLENAMEDBPROCESS~ (
		client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime,
		g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_id, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_user, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_host, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_db, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_command, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_time, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_state, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_info
		) select distinct
		~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '', '', 'Y', ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~',
		id, user, host, db, command, time, state, info 
		from information_schema.processlist
	";
	$strSQL = str_replace("~TABLENAMEDBPROCESS~", ff($strTableNameDBProcess), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~JSONDATA~", ff(""), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	dbEndTrans($objConn_a, __FUNCTION__);
}

function beforeDisplayAddUpdate_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$strTableNameDBProcess = getTableNameEntity("dbprocess", false);

	//$arrJSONData = $arrJSONData_a;
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "DBPROCESS");
	
	$strSQL = "select g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_id id, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_user user, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_host host, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_db db, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_command command, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_time time, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_state state, g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_info info from ~TABLENAMEDBPROCESS~ where id = ~ID~";
	$strSQL = str_replace("~TABLENAMEDBPROCESS~", ff($strTableNameDBProcess), $strSQL);
	$strSQL = str_replace("~ID~", ff($arrRow_a['id']), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "ID", $arrRow['id']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "USER", $arrRow['user']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "HOST", $arrRow['host']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "DB", $arrRow['db']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "COMMAND", $arrRow['command']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "TIME", $arrRow['time']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "STATE", $arrRow['state']);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g24e3d61f-aa39-4d5a-b654-cc55e2d69fc9", "INFO", $arrRow['info']);
	}
	
	return $arrJSONData;
}

function beforeAddUpdate_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameDBProcess = getTableNameEntity("dbprocess", false);

	$strSQL = "select g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_id returnvalue from ~TABLENAMEDBPROCESS~ where id = ~ID~";
	$strSQL = str_replace("~TABLENAMEDBPROCESS~", ff($strTableNameDBProcess), $strSQL);
	$strSQL = str_replace("~ID~", ff($arrRow_a['id']), $strSQL);
	$strProcessID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$strSQL = "kill ~PROCESSID~";
	$strSQL = str_replace("~PROCESSID~", ff($strProcessID), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

function afterDelete_dbprocess($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_dbprocess($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
