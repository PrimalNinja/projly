<?php

// $strFormDataID_a = FORMSECTIONTEMPLATE_FIELD
// $strRelativeID_a = FORMSECTIONTEMPLATE

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
function beforeDisplayAddUpdate_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameFileFormatTemplateField = getTableNameEntity("fileformattemplate_field", false);
	$strTableNameFileFormat = getTableNameEntity("fileformat", false);
	$strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);

	$arrJSONData = $arrJSONData_a;
	
	$strCode = formValueGetBySectionCodeFieldCode($arrJSONData, "g17b76dbc-4845-4184-9757-5b7c5d52818b", "CODE");
	$strDescription = formValueGetBySectionCodeFieldCode($arrJSONData, "g17b76dbc-4845-4184-9757-5b7c5d52818b", "DESCRIPTION");
	$strIsMandatory = formValueGetBySectionCodeFieldCode($arrJSONData, "g17b76dbc-4845-4184-9757-5b7c5d52818b", "IS_MANDATORY");
	$strFileDataTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, "g17b76dbc-4845-4184-9757-5b7c5d52818b", "FILEDATATYPE");

	$strFileDataType = 'String';
	//--------- ADDED CODES ------------------------------
	$intFileFormatTemplateID = ff($strRelativeID_a);
				
	if ($blnUpdate_a)
	{
		//moved the fetching of fileformats objResult so that it can be used both in insert and update operation
		//update operation
		$strOldCode = $arrRow_a["code"];

		$arrJSONDataUpdatedField = formTemplateGetFromDBByEntityCode($objConn_a, "FILEFORMAT_FIELD");
				 
		$arrJSONDataUpdatedField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "CODE", $strCode);
		$arrJSONDataUpdatedField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "DESCRIPTION", $strDescription);
		$arrJSONDataUpdatedField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "ISENABLED", "Y");
		//$arrJSONDataUpdatedField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strIsMandatory);
		//$arrJSONDataUpdatedField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "FILEDATATYPE", $strFileDataTypeID, "String");

		$arrJSONDataNewField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strIsMandatory);
		//$arrJSONDataNewField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strFileDataTypeID, "String");
		$arrJSONDataNewField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataUpdatedField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "FILEDATATYPE", $strFileDataTypeID, "String");


		$strJSONDataUpdatedField = json_encode($arrJSONDataUpdatedField);

	
		$strSQL = "select id from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id 
		in ( select id as fileformat_id from ~TABLENAMEFILEFORMAT~ where fileformattemplate_id = ~FILEFORMATTEMPLATEID~ ) and 
		code = '~OLDCODE~' and 
		client_id = ~CLIENTID~ ";
		$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
		$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
		$strSQL = str_replace('~FILEFORMATTEMPLATEID~', $intFileFormatTemplateID, $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~OLDCODE~', $strOldCode, $strSQL);
		$objResultFileFormatField = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);		

		dbBeginTrans($objConn_a, __FUNCTION__);
		while ($arrRowFileFormatField = dbReadRecord($objResultFileFormatField)) 
		{
			$strFileFormatFieldID = $arrRowFileFormatField['id'];
			$arrJSONDataUpdateField = formTemplateGetFromDBByEntityCode($objConn_a, "FILEFORMAT_FIELD");
			
			$arrJSONDataUpdateField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdateField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "CODE", $strCode);
			$arrJSONDataUpdateField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdateField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "DESCRIPTION", $strDescription);
			$arrJSONDataUpdateField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdateField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "ISENABLED", "Y");
			$arrJSONDataUpdateField = formValueUpdateBySectionCodeFieldCode($arrJSONDataUpdateField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strIsMandatory);
			$arrJSONDataUpdateField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataUpdateField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "FILEDATATYPE", $strFileDataTypeID, "String");
			$strJSONDataUpdateField = json_encode($arrJSONDataUpdateField);
	
			$strSQL = "update ~TABLENAMEFILEFORMATFIELD~ set 
			description = '~DESCRIPTION~', is_enabled = '~ISENABLED~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~',
			filedatatype_id = ~FILEDATATYPEID~
			where id = ~FILEFORMATFIEDID~";
	
			$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
			$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONDataUpdateField), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			$strSQL = str_replace('~FILEDATATYPEID~', ffn($strFileDataTypeID), $strSQL);
			$strSQL = str_replace('~FILEFORMATFIEDID~', ffn($strFileFormatFieldID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'FILEFORMAT_FIELD', $strFileFormatFieldID, $strJSONDataUpdateField);
		}
		dbEndTrans($objConn_a, __FUNCTION__);
	}
	else
	{
		$strSQL = "update ~TABLENAMEFILEFORMATTEMPLATEFIELD~ set fileformattemplate_id = ~FILEFORMATTEMPLATEID~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEFILEFORMATTEMPLATEFIELD~', ff($strTableNameFileFormatTemplateField), $strSQL);
		$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATTEMPLATEID~', ff($strRelativeID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);	
		
		//insert operation
		//--------- ADDED CODES ------------------------------
		//check what table to use
		// -d_fileformattemplate_field
		// -d_fileformat
		// -d_fileformat_field

		//fetch first all file formats 
		// - select id from d_fileformat where fileformattemplate_id = 5
		$strSQL = "select id as fileformat_id from ~TABLENAMEFILEFORMAT~ where fileformattemplate_id = ~FILEFORMATTEMPLATEID~";
		$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
		$strSQL = str_replace('~FILEFORMATTEMPLATEID~', $intFileFormatTemplateID, $strSQL);
		$objResultFileFormat = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		


		//loop all file format
		// - inside the loop insert the fileformat field that was added
		$strDataEntityID = getEntityID($objConn_a, "fileformat_field");
		$strEntityID = getEntityID($objConn_a, "systemform");

		dbBeginTrans($objConn_a, __FUNCTION__);
		while ($arrRowFileFormat = dbReadRecord($objResultFileFormat)) 
		{
		 		$intFileFormatID = $arrRowFileFormat['fileformat_id'];

				 $arrJSONDataNewField = formTemplateGetFromDBByEntityCode($objConn_a, "FILEFORMAT_FIELD");
				 
				 $arrJSONDataNewField = formValueUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "CODE", $strCode);
				 $arrJSONDataNewField = formValueUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "DESCRIPTION", $strDescription);
				 $arrJSONDataNewField = formValueUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "ISENABLED", "Y");
				 $arrJSONDataNewField = formValueUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strIsMandatory);
				 //$arrJSONDataNewField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "IS_MANDATORY", $strFileDataTypeID, "String");
				 $arrJSONDataNewField = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataNewField, "gdca0c616-9e33-4f80-adb1-c964e9f44713", "FILEDATATYPE", $strFileDataTypeID, "String");
				 $strJSONDataNewField = json_encode($arrJSONDataNewField);
		 
				 $strSQL = "insert into ~TABLENAMEFILEFORMATFIELD~ 
				 (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
				 fileformat_id,
				 filedatatype_id
				 ) 

				 values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~',
				 ~FILEFORMATID~,
				 ~FILEDATATYPEID~)";
		 
				 $strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
				 $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				 $strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
				 $strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
				 $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
				 $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
				 $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
				 $strSQL = str_replace('~JSONDATA~', ff($strJSONDataNewField), $strSQL);
				 $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
				 $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
				 $strSQL = str_replace('~FILEFORMATID~', ffn($intFileFormatID), $strSQL);
				 $strSQL = str_replace('~FILEDATATYPEID~', ffn($strFileDataTypeID), $strSQL);
				 $strSQL = str_replace('~ISMANDATORY~', ffn($strIsMandatory), $strSQL);

				 dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				 $strFileFormatFieldID = dbLastInsertID($objConn_a);
				 
				 exposeEntityData($objConn_a, 'SYSTEMFORM', 'FILEFORMAT_FIELD', $strFileFormatFieldID, $strJSONDataNewField);
		}
		dbEndTrans($objConn_a, __FUNCTION__);
					
	}
				
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameFileFormat = getTableNameEntity("fileformat", false);
	$strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);
	
	$strCode = $arrRow_a["code"];

	$arrJSONData = json_decode($strJSONData);
	
	$strFileDataTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, "g17b76dbc-4845-4184-9757-5b7c5d52818b", "FILEDATATYPE");

	$intFileFormatTemplateID = ff($strRelativeID_a);

	$strSQL = "delete from ~TABLENAMEFILEFORMATFIELD~ where fileformat_id in ( select id as fileformat_id from ~TABLENAMEFILEFORMAT~ where fileformattemplate_id = ~FILEFORMATTEMPLATEID~ ) and 
	gdca0c616_9e33_4f80_adb1_c964e9f44713_code = '~CODE~' and 
	client_id = ~CLIENTID~ ";

	$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
	$strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
	$strSQL = str_replace('~FILEFORMATTEMPLATEID~', $intFileFormatTemplateID, $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~CODE~', $strCode, $strSQL);
	$strSQL = str_replace('~FILEDATATYPE~', ffn($strFileDataTypeID), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

function afterDelete_fileformattemplate_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_fileformattemplate_field($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
