<?php

function schemaChangeAdd($objConn_a, $strClientID_a, $strEntityCode_a, $strOperationCode_a, $strDescription_a, $strField_a, $strSQL_a, $strFunction_a) 
{ 
    $strTableNameSchemaChange = getTableNameEntity('schemachange', false);
    
    // initialisations
    $strLogin = $_SESSION['server_loggedin_user'];
        
    // get the entity ids
    $strEntityID = getEntityID($objConn_a, "systemform");
    $strDataEntityID = getEntityID($objConn_a, "schemachange");

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SCHEMACHANGE");
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ENTITYCODE", $strEntityCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "CODE", $strOperationCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "FIELD", $strField_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "OPERATION", $strSQL_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "FUNCTION", $strFunction_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ISDONE", 'N');
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ERROR", '');
	
	$strJSONData = json_encode($arrJSONData);
	
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "
insert into ~TABLENAMESCHEMACHANGE~ (client_id, entity_id, dataentity_id, code, description, jsondata, is_enabled, data_client_id, modifyuser, modifydatetime)
values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~JSONDATA~', 'Y', ~DATACLIENTID~,'~MODIFYUSER~', '~MODIFYDATETIME~')
    ";

    $strSQL = str_replace('~TABLENAMESCHEMACHANGE~', ff($strTableNameSchemaChange), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
    $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
    $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strOperationCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strSchemaChangeID = dbLastInsertID($objConn_a);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'SCHEMACHANGE', $strSchemaChangeID, $strJSONData);
    
    return dbEndTrans($objConn_a, __FUNCTION__);
}