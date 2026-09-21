<?php

// $strFormDataID_a = FILEFORMAT

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
function beforeDisplayAddUpdate_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;        

	if ($blnUpdate_a)
	{
		$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "g5ab8ab01-9cdd-450d-b439-de0d09dfeea5", "FILEFORMATTEMPLATE", "Y");
		//$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "g5ab8ab01-9cdd-450d-b439-de0d09dfeea5", "FILENAME", "Y");
	}

	return $arrJSONData;
}

function beforeAddUpdate_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameFileFormatTemplateField = getTableNameEntity("fileformattemplate_field", false);
	$strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);
	
	$arrJSONData = $arrJSONData_a;
       
    $strLogin = $_SESSION['server_loggedin_user'];

    $arrFieldFileFormatTemplate = formFieldGetBySectionCodeFieldCode($arrJSONData, "g5ab8ab01-9cdd-450d-b439-de0d09dfeea5", "FILEFORMATTEMPLATE");    
    $strFileFormatTemplateID = $arrFieldFileFormatTemplate['p_value'];
    
    $strSQL = "select id, code from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id = ~FILEFORMATID~";
    $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
    $strSQL = str_replace('~FILEFORMATID~', ffn($strFormDataID_a), $strSQL);
    $objResultFileFormatFields = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select id, g17b76dbc_4845_4184_9757_5b7c5d52818b_code code, g17b76dbc_4845_4184_9757_5b7c5d52818b_description description, filedatatype_id, g17b76dbc_4845_4184_9757_5b7c5d52818b_filedatatype filedatatype, g17b76dbc_4845_4184_9757_5b7c5d52818b_is_mandatory is_mandatory
                from ~TABLENAMEFILEFORMATTEMPLATEFIELD~ where fileformattemplate_id = ~FILEFORMATTEMPLATEID~ order by id";
    $strSQL = str_replace('~TABLENAMEFILEFORMATTEMPLATEFIELD~', ff($strTableNameFileFormatTemplateField), $strSQL);
    $strSQL = str_replace('~FILEFORMATTEMPLATEID~', ff($strFileFormatTemplateID), $strSQL);      
    $objResultFileFormatTemplateFields = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);


    $arrFileFormatFieldRecords = [];
    $arrFileFormatFieldTemplateRecords = [];
    $arrFileFormatFieldCodes = [];
    $arrFileFormatTemplateFieldCodes = [];

    while ($arrRow = dbReadRecord($objResultFileFormatFields)) 
    {
        $arrFileFormatFieldCodes[] = $arrRow['code'];
        $arrFileFormatFieldRecords[] = $arrRow;
    }
    dbCloseRecordset($objResultFileFormatFields);
    
    while ($arrRow = dbReadRecord($objResultFileFormatTemplateFields)) 
    {
        $arrFileFormatTemplateFieldCodes[] = $arrRow['code'];
        $arrFileFormatFieldTemplateRecords[] = $arrRow;
    }
    dbCloseRecordset($objResultFileFormatTemplateFields);
        
   
    foreach ($arrFileFormatFieldRecords as $arrRow)
    {
        if (!in_array($arrRow['code'], $arrFileFormatTemplateFieldCodes))
        {
            $strSQL = "delete from  ~TABLENAMEFILEFORMATFIELD~ where id = ~FILEFORMATFIELDID~";
            $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
            $strSQL = str_replace('~FILEFORMATFIELDID~', ffn($arrRow['id']), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        }
    }
    
    foreach ($arrFileFormatFieldTemplateRecords as $arrRow)
    {
        if (!in_array($arrRow['code'], $arrFileFormatFieldCodes))
        {
            $strCode = $arrRow['code'];
            $strDescription = $arrRow['description'];

            $arrJSONDataFileFormatField = formTemplateGetFromDBByEntityCode($objConn_a, "FILEFORMAT_FIELD");
            $arrJSONDataFileFormatField = formValueUpdateBySectionCodeFieldCode($arrJSONDataFileFormatField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "CODE", $strCode);
            $arrJSONDataFileFormatField = formValueUpdateBySectionCodeFieldCode($arrJSONDataFileFormatField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "DESCRIPTION", $strDescription);
            $arrJSONDataFileFormatField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataFileFormatField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "FILEDATATYPE", $arrRow['filedatatype_id'], $arrRow['filedatatype']);
            $arrJSONDataFileFormatField = formValueUpdateBySectionCodeFieldCode($arrJSONDataFileFormatField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $arrRow['is_mandatory']);

            $strDataEntityID = getEntityID($objConn_a, "fileformat_field");
            $strEntityID = getEntityID($objConn_a, "systemform");
            $strJSONDataFileFormatField = json_encode($arrJSONDataFileFormatField);

            $strSQL = "insert into ~TABLENAMEFILEFORMATFIELD~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fileformat_id) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~FILEFORMATID~)";
            $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~CODE~', $strCode, $strSQL);
            $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
            $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
            $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
            $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
            $strSQL = str_replace('~JSONDATA~', ff($strJSONDataFileFormatField), $strSQL);
            $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
            $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
            $strSQL = str_replace('~FILEFORMATID~', ffn($strFormDataID_a), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            $strFileFormatFieldID = dbLastInsertID($objConn_a);

            exposeEntityData($objConn_a, 'SYSTEMFORM', 'FILEFORMAT_FIELD', $strFileFormatFieldID, $strJSONDataFileFormatField);
        }        
    }

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
    $strTableNameFileFieldExclusion = getTableNameEntity("filefieldexclusion", false);
    $strTableNameFileFieldMapping = getTableNameEntity("filefieldmapping", false);
    $strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);

	$strSQL = "delete from ~TABLENAMEFILEFIELDEXCLUSION~ where fileformat_field_id in (select id from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id = ~FILEFORMATID~)";
    $strSQL = str_replace('~TABLENAMEFILEFIELDEXCLUSION~', ff($strTableNameFileFieldExclusion), $strSQL);
    $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
    $strSQL = str_replace('~FILEFORMATID~', ffn($strFormDataID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "delete from ~TABLENAMEFILEFIELDMAPPING~ where fileformat_field_id in (select id from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id = ~FILEFORMATID~)";
    $strSQL = str_replace('~TABLENAMEFILEFIELDMAPPING~', ff($strTableNameFileFieldMapping), $strSQL);
    $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
    $strSQL = str_replace('~FILEFORMATID~', ffn($strFormDataID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "delete from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id = ~FILEFORMATID~";
    $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
    $strSQL = str_replace('~FILEFORMATID~', ffn($strFormDataID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

function afterDelete_fileformat($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_fileformat($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
