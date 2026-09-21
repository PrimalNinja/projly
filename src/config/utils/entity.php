<?php

// function summary:
//
//		functions that use the database:
//
//			transfers:
//
//				insertEntity($objConn_a, $strClientID_a, $arrJSONDataFrom_a, $strSectionFrom_a, $strEntityCode_a, $strSectionTo_a, $arrJSONSetFields_a)
//				transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strID_a, $strJSONData_a)
//				transferEntityDataRow($objConn_a, $strFormEntityCode_a, $strID_a, $arrJSONData_a, $arrTransferFields_a, $blnIsExtended_a)
//				transferEntityDataRowCoreValues($objConn_a, $strFormEntityCode_a, $strID_a, $arrJSONData_a)
//
//			entity retrieval:
//
//				exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $intDataEntityID_a, $strClientID_a, $strID_a, $strJSONData_a)
//				exposeEntityFieldsExtension($objConn_a, $strFormEntityCode_a, $arrFieldsToProcess_a, $arrSearchableFields_a)
//				exposeEntityFieldsFast($objConn_a, $strFormEntityCode_a, $arrNewSearchableFields_a, $arrSearchableFields_a)
//				exposeEntityFieldsSlow($objConn_a, $strFormEntityCode_a, $blnRemoveAllNew_a, $arrFieldsToProcess_a, $blnRemoveAllSearchable_a, $arrSearchableFields_a)
//				getEntityFromDBFieldsByID($objConn_a, $strEntityCode_a, $strClientID_a, $strID_a, $arrFields_a)
// 				getEntityID($objConn_a, $strEntityCode_a)
//				getFormEntityID($objConn_a, $strClientID_a, $strFormEntityCode_a)
//
//			getter utils:
//
//				getAccountIDByClientIDViaAccount($objConn_a, $strClientID_a)
//				getAccountNameByClientIDViaAccount($objConn_a, $strClientID_a)
//				getClientIDByAccountIDViaAccount($objConn_a, $strAccountID_a)
//				getSuburbIDBySuburbFields($objConn_a, $strSuburb_a, $strPostcode_a, $strState_a, $strCountry_a)
//
//			general utils:
//
//				getFixedFilterValue($arrFixedFilter_a, $strField_a)
//				isExtended($objConn_a, $strEntityCode_a)
//				selectionListCreateFromDBByEntityCode($objConn_a, $strClientID_a, $strEntityCode_a, $strFilterField_a, $strFilterValue_a)
//				exposeEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strRowID_a, $strJSONData_a, $intLimit_a = 0)
//				exposeEntityDataDeferred($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData_a)
//				getIntegrityErrorDescription($objConn_a, $strSQLError_a, $strParentDescription_a)
//				getSearchableFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a)
//				getSystemFormByCode($objConn_a, $strFormEntityCode_a)
//              entityBuildOrderBy($objConn_a, $strEntityCode_a, $strAppendFields_a = "")
//
//			entity creation, deletion and maintenance:
//
//				createIDXName()
//				createFKName()
//				getTableNameDataForm($strClientID_a, $strFormEntityCode_a, $blnHistory_a)
//				getTableNameEntity($strFormEntityCode_a, $blnHistory_a)
//				getTableNameEntityExtension($strFormEntityCode_a)
//
//			data repair:
//				renameAllFormSectionGUIDsByEntityCode($objConn_a, $strEntityCode_a, $strOldGUID_a, $strNewGUID_a)
//				function renameSystemFormSectionGUID($objConn_a, $strEntityCode_a, $strOldGUID_a, $strNewGUID_a)
//				eg: renameSystemFormSectionGUID($objConn_a, "PRODUCT", "gffe35a8ad-d290-4ae3-8800-f2935dd30d07", "gffe35a8ad-d290-4ae3-8800-f2935dd30d07");
//				function updateSectionGUID($arrJSON_a, $strSectionCodeOld_a, $strSectionCodeNew_a)
//
//			reassigning records to other clients:
//				updateRecordClientIDByID($objConn_a, $strEntity_a, $strID_a, $strClientID_a)

// functions that use the database:

// transfers

// the sectioncode in arrJSONSetFields_a must be the same as strSectionTo
function insertEntity($objConn_a, $strClientID_a, $arrJSONDataFrom_a, $strSectionFrom_a, $strEntityCode_a, $strSectionTo_a, $arrJSONSetFields_a)
{
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);

    $strResult = "";
	$strCode = "";
	$strDescription = "";
	$strIsEnabled = "Y";

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, strtolower($strEntityCode_a));

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, strtoupper($strEntityCode_a));

	for ($intI = 0; $intI < count($arrJSONSetFields_a); $intI++)
	{
		$strFieldName = $arrJSONSetFields_a['fieldname'];
		$strFieldValue = $arrJSONSetFields_a['value'];

		if ($strFieldName = 'CODE')
		{
			$strCode = $strFieldValue;
		}
		else if ($strFieldName = 'DESCRIPTION')
		{
			$strDescription = $strFieldValue;
		}
		else if ($strFieldName = 'ISENABLED')
		{
			$strIsEnabled = $strFieldValue;
		}
	}

	if (strlen($strSectionFrom_a) > 0)
	{
		// transfer data from Registration form to the Account form
		$arrJSONData = formTransferSectionValues($arrJSONDataFrom_a, $strSectionFrom_a, $arrJSONData, $strSectionTo_a);

				// set additional provided values
		$arrJSONData = formValuesUpdate($arrJSONData, $arrJSONSetFields_a);
	}

	$strJSONData = json_encode($arrJSONData);

	$strSQL =
		"
insert into ~TABLENAMEENTITY~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime)
values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~')
";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
	$strSQL = str_replace('~ENABLED~', ff($strIsEnabled), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', strtoupper($strEntityCode_a), $strResult, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strResult;
}

// transfer data from a JSON row for a single table row
// note: for now we have no function to process all rows within a table which already had data at the time of exposing a field out of the JSON
// 		 the intent is later, to add a batch process here, and the batch process do the row processing
function transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strID_a, $strJSONData_a)
{
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	$arrJSON = json_decode($strJSONData_a, true);

	if(is_array($arrJSON))
	{
		$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
		$strSystemClientID = getSystemClientID($objConn_a);

		// read searchablefields
		$strSQL = "select searchablefields returnvalue from ~TABLENAMESYSTEMFORM~ where client_id = ~CLIENTID~ and code = '~FORMENTITYCODE~'";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);
		$strSQL = str_replace('~FORMENTITYCODE~', ff($strFormEntityCode_a), $strSQL);
		$strSearchableFields = strtolower(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

		$arrSearchableFields = explode(",", $strSearchableFields);
		$strTemp = implode(",", $arrSearchableFields);

		if (strlen($strSearchableFields) > 0)
		{
			$strID = $strID_a;
			$strJSONData = $strJSONData_a;
			$arrJSONData = json_decode($strJSONData, true);

			transferEntityDataRow($objConn_a, $strFormEntityCode_a, $strID, $arrJSONData, $arrSearchableFields, $blnIsExtended);
			transferEntityDataRowCoreValues($objConn_a, $strFormEntityCode_a, $strID, $arrJSONData);
		}
	}
}

// transfer a bunch of fields out of JSON to a table row in a single JSON pass (unlike reading a value at a time from the JSON)
function transferEntityDataRow($objConn_a, $strFormEntityCode_a, $strID_a, $arrJSONData_a, $arrTransferFields_a, $blnIsExtended_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);

	$strSQL = "";
	$strSetValues = "";
//logDebug(print_r($arrJSONData_a, true), '');
	if ($arrJSONData_a != null)
	{
		foreach ($arrJSONData_a as $keySection => $arrSection)
		{
			$strSectionCode = $arrSection['sectioncode'];
			$strSectionType = $arrSection['sectiontype'];

			if (($strSectionType == "INTERNALUSEFORMHEADER") || ($strSectionType == "INTERNALUSE") || ($strSectionType == "DATA"))
			{
				$arrFields = $arrSection['fields'];

				foreach ($arrFields as $keyField => $arrField)
				{
					$strDataType = $arrField['p_datatype'];
					$strFieldCode = $arrField['p_name'];
					$strSourceRaw = "";
					if (array_key_exists('p_source', $arrField))
					{
						$strSourceRaw = $arrField['p_source'];
					}
					$strIDField = ""; // default to none
					$strIDFieldValue = ""; // default to none

					$strValue = '';
					try
					{
						if ($strDataType == "d_document")
						{
							$arrValue = $arrField['p_value'];
							if (is_array($arrValue))
							{
								// iterate through and get the ids of each
								for ($intI = 0; $intI < count($arrValue); ++$intI)
								{
									$arrDocument = $arrValue[$intI];

									if (strlen($strValue) > 0)
									{
										$strValue .= ", ";
									}

									$strValue .= $arrDocument["documentid"];
								}
							}
							//debug($strValue);
						}
						else if ($strDataType == "d_list")
						{
							$strIDField = ffel($strFieldCode . "_id");
							if (strlen($strSourceRaw) == 0)
							{
								$strIDField = ffel($strFieldCode . "_code");
							}
							$strIDFieldValue = $arrField['p_value'];

							$strValue = "";
							if (array_key_exists('p_valuedescription', $arrField))
							{
								$strValue = $arrField['p_valuedescription'];
							}
						}
						else if ($strDataType == "d_multilist")
						{
							$strValue = '';
							if (strlen($strSourceRaw) > 0)
							{
								$arrSource = explode("|", $strSourceRaw);
								//$strSource = $arrSource[0];

								$arrValueDescription = array();
								if (array_key_exists('p_valuedescription', $arrField))
								{
									$arrValueDescription = $arrField['p_valuedescription'];	// iterate through and get the descriptions of each
								}

								sort($arrValueDescription);

								if (is_array($arrValueDescription))
								{
									for ($intI = 0; $intI < count($arrValueDescription); ++$intI)
									{
										$strValueDescription = $arrValueDescription[$intI];

										if (strlen($strValue) > 0)
										{
											$strValue .= ", ";
										}

										$strValue .= $strValueDescription;
									}
								}
							}
						}
						else if (($strDataType != "d_description") && ($strDataType != "d_spacer") && ($strDataType != "d_relatedlinks"))	// these have no values
						{
							$strValue = formDatatypeValue($arrField);
							if (is_array($strValue))
							{
								//debug($strDataType);
								$strValue = implode(", ", $strValue);
							}
						}
					}
					catch (Exception $e)
					{
						$strValue = '';	// some datatypes don't have a value such as spacer
					}

					$strField = $strSectionCode . "_" . $strFieldCode;
					$strField = ffel(str_replace("-", "_", $strField));

	//$strTemp = implode(",", $arrTransferFields_a);
	//debug($strField . ":" . $strTemp);
					if (in_array($strField, $arrTransferFields_a) == TRUE)
					{
						if (strlen($strSetValues) > 0)
						{
							$strSetValues .= ", ";
						}

						$strSetValues .= "~FIELDNAME~ = '~VALUE~'";
						$strSetValues = str_replace('~FIELDNAME~', ff($strField), $strSetValues);
						$strSetValues = str_replace('~VALUE~', ff($strValue), $strSetValues);
					}

					if (strlen($strIDField) > 0)
					{
						if (in_array($strIDField, $arrTransferFields_a) == TRUE)
						{
							if (strlen($strSetValues) > 0)
							{
								$strSetValues .= ", ";
							}

							if (strtolower($strIDFieldValue) == "null")
							{
								$strIDFieldValue = "";
							}

							if (strlen($strSourceRaw) > 0)
							{
								$strSetValues .= "~FIELDNAME~ = ~VALUE~";
								$strSetValues = str_replace('~FIELDNAME~', ff($strIDField), $strSetValues);
								$strSetValues = str_replace('~VALUE~', ffn($strIDFieldValue), $strSetValues);
							}
							else
							{
								$strSetValues .= "~FIELDNAME~ = '~VALUE~'";
								$strSetValues = str_replace('~FIELDNAME~', ff($strIDField), $strSetValues);
								$strSetValues = str_replace('~VALUE~', ff($strIDFieldValue), $strSetValues);
							}
						}
					}
				}
			}
		}

		if (strlen($strSetValues) > 0)
		{
			if ($blnIsExtended_a)
			{
				$strSQL = "select id returnvalue from ~TABLENAMEENTITYEXTENSION~ where id = ~ID~";
				$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
				$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
				$strTempID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				if (strlen($strTempID) == 0)
				{
					$strSQL = "insert into ~TABLENAMEENTITYEXTENSION~ (id, client_id, is_exposed) select id, client_id, 'Y' from ~TABLENAMEENTITY~ where id = ~ID~";
					$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
					$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
					$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				
				$strSQL = "update ~TABLENAMEENTITYEXTENSION~ set " . $strSetValues . " where id = ~ID~";
				$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
				$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
			else
			{
				$strSQL = "update ~TABLENAMEENTITY~ set " . $strSetValues . " where id = ~ID~";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
		}
	}
}

function transferEntityDataRowCoreValues($objConn_a, $strFormEntityCode_a, $strID_a, $arrJSONData_a)
{
	return; // currently this causes issues because we have been updating the codes and descriptions directly DO NOT ENABLE THIS UNTIL ALL ENTITIES ARE TESTED
	
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);

	$strCode = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'DATA', 'code');
	$strDescription = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'DATA', 'description');
	$strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'DATA', 'isenabled');
	
	// get the values from the data header if not yet found
	if (strlen($strCode) == 0) { $strCode = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'FORMHEADER', 'code'); }
	if (strlen($strDescription) == 0) { $strDescription = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'FORMHEADER', 'description'); }
	if (strlen($strIsEnabled) == 0) { $strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData_a, 'FORMHEADER', 'isenabled'); }

	$strSQL = "update ~TABLENAMEENTITY~ set code = '~CODE~', description = '~DESCRIPTION~', is_enabled = '~ISENABLED~' where id = ~ID~";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
	$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

// entity creation, deletion and maintenance

function getEntityFromDBFieldsByID($objConn_a, $strEntityCode_a, $strClientID_a, $strID_a, $arrFields_a)
{
	$arrResult = array();

	// device restriction
	$strDeviceIDCookie = $_SESSION['server_loggedin_cookie'];
	$blnRestrictToDevice = false;
	if (InStr(',' . ENTITIESFILTEREDBYDEVICE . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
	{
		$blnRestrictToDevice = true;
	}
	
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}

	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
		}
	}

	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
		}
	}

	$strFields = "";
    foreach ($arrFields_a as $strField)
	{
		if (strlen($strFields) > 0)
		{
			$strFields .= ", ";
		}
		$strFields .= $strField;
		$arrResult[$strField] = "";
	}

	$strTableName = getTableNameEntity($strEntityCode_a, false);

	$strSQL = "";
	if ($blnIgnoreClient)
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAME~ where id = ~ID~";
	}
	else
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAME~ where client_id = ~CLIENTID~ and id = ~ID~";
	}
	$strSQL = str_replace('~TABLENAME~', ff($strTableName), $strSQL);
	$strSQL = str_replace('~FIELDS~', ff($strFields), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

	if ($arrRow = dbReadRecord($objResult))
	{
		foreach ($arrFields_a as $strField)
		{
			$arrResult[$strField] = $arrRow[$strField];
		}
	}

	dbCloseRecordset($objResult);

	return $arrResult;
}

function getEntityID($objConn_a, $strEntityCode_a)
{
	$strTableNameEntity = getTableNameEntity('entity', false);

	$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~CODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~CODE~', ffeu($strEntityCode_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getFormEntityID($objConn_a, $strClientID_a, $strFormEntityCode_a)
{
	$strSQL = "select id returnvalue from entity_tbldataformentity where (code = '~ENTITYCODE1~' or code = '~ENTITYCODE2~') and client_id = ~CLIENTID~";
	$strSQL = str_replace('~ENTITYCODE1~', ffel($strFormEntityCode_a), $strSQL);
	$strSQL = str_replace('~ENTITYCODE2~', ffeu($strFormEntityCode_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}


// getter utils

function getAccountIDByClientIDViaAccount($objConn_a, $strClientID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);

	$strSQL = "select id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getAccountNameByClientIDViaAccount($objConn_a, $strClientID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);

	$strSQL = "select ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getClientIDByAccountIDViaAccount($objConn_a, $strAccountID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);

	$strSQL = "select client_id returnvalue from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getSuburbIDBySuburbFields($objConn_a, $strSuburb_a, $strPostcode_a, $strState_a, $strCountry_a)
{
	$strTableNameSuburb = getTableNameEntity("suburb", false);
	$strSQL = "select id returnvalue from ~TABLENAMESUBURB~ where suburb = '~SUBURB~' and state = '~STATE~' and postcode = '~POSTCODE~' and country = '~COUNTRY~'";
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~SUBURB~', ff($strSuburb_a), $strSQL);
	$strSQL = str_replace('~STATE~', ff($strState_a), $strSQL);
	$strSQL = str_replace('~POSTCODE~', ff($strPostcode_a), $strSQL);
	$strSQL = str_replace('~COUNTRY~', ff($strCountry_a), $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

// general utils

function getFixedFilterValue($arrFixedFilter_a, $strField_a)
{
	$strResult = "";

	foreach ($arrFixedFilter_a as &$objField)
	{
		if ($objField['field'] == $strField_a)
		{
			$strResult = $objField['value'];
		}
	}

	return $strResult;
}

function isExtended($objConn_a, $strEntityCode_a)
{
	//return false;
	$strTableNameEntity = getTableNameEntity("entity", false);
	
	$strSQL = "select ffe65a2521_c3d0_42fc_8d25_dedd43876fff_isextended returnvalue from ~TABLENAMEENTITY~ where code = '~CODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strEntityCode_a), $strSQL);
	$strIsExtended = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	$blnResult = toBoolean($strIsExtended);
	
	return $blnResult;
}

// create a lister selection from the database that is in the format of a manually in-application-form lister selection
// such listers do not have an entity id and relational integrity to another table
function selectionListCreateFromDBByEntityCode($objConn_a, $strClientID_a, $strEntityCode_a, $strFilterField_a, $strFilterValue_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);

	$arrResult = array();

	// client bypass
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}

	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
		}
	}

	$strSQL = "";
	if ($blnIgnoreClient)
	{
		$strSQL = "select id, code, description from ~TABLENAMEENTITYCODE~ where 1=1";
		if (strlen($strFilterField_a) > 0)
		{
			$strSQL .= " and ~FILTERFIELD~ = '~FILTERVALUE~'";
		}
	}
	else
	{
		$strSQL = "select id, code, description from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~";
		if (strlen($strFilterField_a) > 0)
		{
			$strSQL .= " and ~FILTERFIELD~ = '~FILTERVALUE~'";
		}
	}
	$strSQL .= " order by description";

	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~FILTERFIELD~', ff($strFilterField_a), $strSQL);
	$strSQL = str_replace('~FILTERVALUE~', ff($strFilterValue_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult))
	{
		$strID = $arrRow['id'];
		$strCode = $arrRow['code'];
		$strDescription = $arrRow['description'];

		$arrResult[] = array(
			"p_code" => $strCode,
			"p_value" => $strDescription,
			"p_valuedescription" => ""
		);
	}

	dbCloseRecordset($objResult);

	return $arrResult;
}

// transfer data from a JSON row for all rows in a table
function exposeEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strRowID_a, $strJSONData_a, $intLimit_a = 0)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	$arrJSON = json_decode($strJSONData_a, true);

	if(is_array($arrJSON) && (ffel($strEntityCode_a) == 'systemform'))
	{
		$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
		$strSystemClientID = getSystemClientID($objConn_a);

		// read searchablefields
		$strSQL = "select searchablefields returnvalue from ~TABLENAMESYSTEMFORM~ where client_id = ~CLIENTID~ and code = '~FORMENTITYCODE~'";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);
		$strSQL = str_replace('~FORMENTITYCODE~', ff($strFormEntityCode_a), $strSQL);
		$strSearchableFields = strtolower(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

		$arrSearchableFields = explode(",", $strSearchableFields);

		// read json
		$strSQL = "";
		if ($blnIsExtended && ($intLimit_a > 0))
		{
			$strSQL = "select e.id, e.jsondata from ~TABLENAMEENTITY~ e, ~TABLENAMEENTITYEXTENSION~ ee where e.id = ee.id and ee.is_exposed = 'N' order by e.id";
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
			if ($intLimit_a > 0)
			{
				$strSQL .= " limit 0, " . strval($intLimit_a);
			}
		}
		else
		{
			if (strlen($strRowID_a) > 0)
			{
				$strSQL = "select id, jsondata from ~TABLENAMEENTITY~ where id = ~ROWID~";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~ROWID~', ff($strRowID_a), $strSQL);
			}
			else
			{
				$strSQL = "select id, jsondata from ~TABLENAMEENTITY~ order by id";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			}
		}

		// for each record
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

		while ($arrRow = dbReadRecord($objResult))
		{
			$strID = $arrRow['id'];
			$strJSONData = $arrRow['jsondata'];
			$arrJSONData = json_decode($strJSONData, true);

			transferEntityDataRow($objConn_a, $strFormEntityCode_a, $strID, $arrJSONData, $arrSearchableFields, $blnIsExtended);
			transferEntityDataRowCoreValues($objConn_a, $strFormEntityCode_a, $strID, $arrJSONData);
			
			if ($blnIsExtended && ($intLimit_a > 0))
			{
				$strSQL = "update ~TABLENAMEENTITYEXTENSION~ set is_exposed = 'Y' where id = ~ID~";
				$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
				$strSQL = str_replace('~ID~', ff($strID), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
		}
		dbCloseRecordset($objResult);
	}
}

// transfer data deferred from a JSON row for all rows in a table
function exposeEntityDataDeferred($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	if (dependencies('system/schemaChangeAdd'))
	{
		$arrJSON = json_decode($strJSONData_a, true);

		if(is_array($arrJSON) && (ffel($strEntityCode_a) == 'systemform'))
		{
			$strOperationCode = getGUID();
			schemaChangeAdd($objConn_a, $strClientID_a, $strFormEntityCode_a, $strOperationCode, 'rebuild data initialise', '', 'rebuildextensiondatainit', 'exposeEntityDataDeferred');
			schemaChangeAdd($objConn_a, $strClientID_a, $strFormEntityCode_a, $strOperationCode, 'rebuild data', '', 'rebuildextensiondata', 'exposeEntityDataDeferred');
		}
	}
}

// create and drop fields that we make searchable from within a JSON to the real table fields
function exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $intDataEntityID_a, $strClientID_a, $strID_a, $strJSONData_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	global $BLACKLISTED_FORMBUILDER_IDS;
	
	$arrJSON = json_decode($strJSONData_a, true);

	if(is_array($arrJSON))
	{
		if (ffel($strEntityCode_a) == 'systemform')
		{
			$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
			
			// iterates through $arrJSON to build the list of newsearchable
			$arrNewSearchableFields = array();
			$arrFieldsToProcess = array();
			$arrAllDBFormFields = array();

			foreach ($arrJSON as $keySection => $arrSection)
			{
				$strSectionCode = $arrSection['sectioncode'];
				$strSectionType = $arrSection['sectiontype'];

				if (($strSectionType == "INTERNALUSEFORMHEADER") || ($strSectionType == "INTERNALUSE") || ($strSectionType == "DATA"))
				{
					$arrFields = $arrSection['fields'];

					foreach ($arrFields as $keyField => $arrField)
					{
						$strFieldCode = $arrField['p_name'];
						$strDataType = $arrField['p_datatype'];
						$strLength = $arrField['p_length'];
						$strSourceRaw = "";
						if (array_key_exists("p_source", $arrField))
						{
							$strSourceRaw = $arrField['p_source'];
						}
						$blnSearchable = toBoolean($arrField['p_searchable']);

						$strIDField = ffel($strFieldCode . "_id");	// exposed IDs have no GUID but a suffix of id
						if (strlen($strSourceRaw) == 0)
						{
							$strIDField = ffel($strFieldCode . "_code");	// exposed IDs have no GUID but a suffix of id
						}
						$strField = ffel($strSectionCode . "_" . $strFieldCode);
						$strField = str_replace("-", "_", $strField);

						if ($blnSearchable)
						{
							if (in_array($strIDField, $BLACKLISTED_FORMBUILDER_IDS))
							{
								// do nothing, as these are blacklisted
								logDebug('Use of black listed field name exposed in form builder: ' . $strIDField, '');
							}
							else
							{
								if ($strDataType == "d_list")
								{
									// to work out the related entity, eg "p_source": "SUBURB|['suburb','state','postcode']",
									$strSource = "";
									$arrSource = explode('|',$strSourceRaw);
									if (is_array($arrSource))
									{
										$strSource = $arrSource[0];
									}

									// bring out the id as well for type d_list only (for now)
									$arrFieldsToProcess[] = array("isidfield"=>true, "fieldname"=>$strIDField, "source"=>$strSource, "fieldcode"=>$strFieldCode, "datatype"=>$strDataType, "length"=>$strLength);
//debug(print_r(array("isidfield"=>true, "fieldname"=>$strIDField, "source"=>$strSource, "fieldcode"=>$strFieldCode, "datatype"=>$strDataType), true));
									array_push($arrNewSearchableFields, $strIDField);
								}
								$arrFieldsToProcess[] = array("isidfield"=>false, "fieldname"=>$strField, "source"=>"", "fieldcode"=>$strFieldCode, "datatype"=>$strDataType, "length"=>$strLength);
//debug(print_r(array("isidfield"=>false, "fieldname"=>$strField, "source"=>"", "fieldcode"=>$strFieldCode, "datatype"=>$strDataType), true));
								array_push($arrNewSearchableFields, $strField);
							}
						}
					}

					// get all fields from DB
					$strSQL = "DESCRIBE ~TABLENAMEENTITY~";
					$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
					$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
					while ($arrRow = dbReadRecord($objResult))
					{
						$strField = $arrRow['Field'];
						$strFieldPrefix = ffel($strSectionCode . "_");
						$strFieldPrefix = str_replace("-", "_", $strFieldPrefix);

						if ((strlen($strFieldPrefix) > 1) && ($strFieldPrefix == substr($strField, 0, strlen($strFieldPrefix))))
						{
							array_push($arrAllDBFormFields, $strField);
						}
					}
					dbCloseRecordset($objResult);
				}
			}

			$strAllDBFormFields = implode(',', $arrAllDBFormFields);
			//debug($strAllDBFormFields);die();
			//debug($arrNewSearchableFields);die();

			sort($arrNewSearchableFields);
			$strNewSearchableFields = implode(',', $arrNewSearchableFields);

			// read searchablefields
			$strSQL = "select searchablefields returnvalue from ~TABLENAMESYSTEMFORM~ where id = ~ID~ and client_id = ~CLIENTID~ and dataentity_id = ~DATAENTITYID~";
			$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($intDataEntityID_a), $strSQL);
			$strSearchableFields = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			//if ($strNewSearchableFields != $strSearchableFields)
			//{
				$arrSearchableFields = explode(",", $strSearchableFields);

				$strOperationCode = getGUID();
				
				if ($blnIsExtended)
				{
					$strNewSearchableFields = exposeEntityFieldsExtension($objConn_a, $strFormEntityCode_a, $arrFieldsToProcess, $arrAllDBFormFields);
				}
				else
				{
					//$strNewSearchableFields = exposeEntityFieldsFast($objConn_a, $strFormEntityCode_a, $arrNewSearchableFields, $arrSearchableFields);	// attempt 1 minimal SQL statements, but not very tolerant
					$strNewSearchableFields = exposeEntityFieldsSlow($objConn_a, $strFormEntityCode_a, true, $arrFieldsToProcess, true, $arrAllDBFormFields);
				}

				$strSQL = "update ~TABLENAMESYSTEMFORM~ set searchablefields = '~SEARCHABLEFIELDS~' where id = ~ID~ and client_id = ~CLIENTID~ and dataentity_id = ~DATAENTITYID~";
				$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
				$strSQL = str_replace('~DATAENTITYID~', ff($intDataEntityID_a), $strSQL);
				$strSQL = str_replace('~SEARCHABLEFIELDS~', ff($strNewSearchableFields), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			//}
		}
	}
}

// EXTENDED create and drop fields that we make searchable from within a JSON to the real table fields
function exposeEntityFieldsExtension($objConn_a, $strFormEntityCode_a, $arrFieldsToProcess_a, $arrSearchableFields_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);

	dbDropTable($objConn_a, $strTableNameEntityExtension, true);

	if (dependencies('entity/createEntityTables'))
	{
		createEntityExtensionTable($objConn_a, $strFormEntityCode_a);
		
		$arrPopulateFields = array();

		// add the new fields again
		foreach ($arrFieldsToProcess_a as $arrField)
		{
			$strField = $arrField['fieldname'];	// the name of the field, eg: suburb_id or guid_suburb
			$strFieldCode = $arrField['fieldcode'];	// the base name/code of the field, eg: suburb
			$blnIsIDField = toBoolean($arrField['isidfield']);	// is it an guid_ or an _id field
			$strSource = $arrField['source'];	// the related entity
			$strDataType = $arrField['datatype'];	// the exposed field's datatype
			$strLength = $arrField['length'];	// the exposed field's length if < 255 then it is 255

			if (strlen($strField) > 0)
			{
				// alter table add field
				if (($blnIsIDField == true) && (strlen($strSource) > 0))
				{
					$strSQL = "alter table ~TABLENAMEENTITYEXTENSION~ add column ~FIELDNAME~ bigint(20) NULL";
					$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
					try
					{
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					}
					catch (Exception $e) { }
				}
				else
				{
					if (($strDataType == "d_texthtml") || ($strDataType == "d_html-input"))
					{
						$strSQL = "alter table ~TABLENAMEENTITYEXTENSION~ add column ~FIELDNAME~ mediumblob NULL";
						$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
						$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
						try
						{
							dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
						}
						catch (Exception $e) { }
					}
					else
					{
						$intLength = intval($strLength, 10);
						if ($intLength < intval(MINEXPOSEDFIELDSIZE, 10))
						{
							$intLength = intval(MINEXPOSEDFIELDSIZE, 10);
						}
						$strLength = strval($intLength);

						$strSQL = "alter table ~TABLENAMEENTITYEXTENSION~ add column ~FIELDNAME~ varchar(~LENGTH~) NULL";
						$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
						$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
						$strSQL = str_replace('~LENGTH~', $strLength, $strSQL);
						try
						{
							dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
						}
						catch (Exception $e) { }
					}
				}

				if (($blnIsIDField == true) && (strlen($strSource) > 0))
				{
					$strTableNameEntitySource = getTableNameEntity($strSource, false);
					$strFK = createFKName();
					
					// create a foreign key
					$strSQL = "alter table ~TABLENAMEENTITYEXTENSION~ add constraint ~FK~ foreign key (~FIELDNAME~) references ~TABLENAMEENTITYSOURCE~ (id)";
					$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
					$strSQL = str_replace('~TABLENAMEENTITYSOURCE~', ff($strTableNameEntitySource), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
					$strSQL = str_replace('~FK~', ff($strFK), $strSQL);
					try
					{
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					}
					catch (Exception $e) { }
				}

				array_push($arrPopulateFields, $strField);
			}
		}

		sort($arrPopulateFields);
		$strNewSearchableFields = implode(',', $arrPopulateFields);
		
		// populate the extension table with empty rows
		$strSQL = "insert into ~TABLENAMEENTITYEXTENSION~ (id, client_id, is_exposed) select id, client_id, 'N' from ~TABLENAMEENTITY~";
		$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// update table description, NOTE uncomment when we have fixed the issues with re-exposing code, description and is_enabled
		//$strSQL = "update ~TABLENAMEENTITY~ set description = 'TEMPORARILY UNAVAILABLE'";
		//$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		//dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		dbClearErrors();
	}

	return $strNewSearchableFields;
}

// FAST create and drop fields that we make searchable from within a JSON to the real table fields
function exposeEntityFieldsFast($objConn_a, $strFormEntityCode_a, $arrNewSearchableFields_a, $arrSearchableFields_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);

	$arrPopulateFields = array();

	$strSQL = "";
	// work out the new fields
	foreach ($arrNewSearchableFields_a as $strField)
	{
		if (strlen($strField) > 0)
		{
			// if field not in searchablefields then
			if (in_array($strField, $arrSearchableFields_a) == FALSE)
			{
				// alter table add field
				if (strlen($strSQL) > 0)
				{
					$strSQL .= ", ";
				}
				$strSQL .= "add column ~FIELDNAME~ varchar(255) NULL";
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);

				// track the new fields so later we can process the data for them
				array_push($arrPopulateFields, $strField);
			}
		}
	}

	// work out the removed fields
	foreach ($arrSearchableFields_a as $strField)
	{
		if (strlen($strField) > 0)
		{
			// if field not in searchablefields then
			if (in_array($strField, $arrNewSearchableFields_a) == FALSE)
			{
				// alter table drop field
				if (strlen($strSQL) > 0)
				{
					$strSQL .= ", ";
				}
				$strSQL .= "drop column ~FIELDNAME~";
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
			}
		}
	}

	// alter the table
	if (strlen($strSQL) > 0)
	{
		$strSQL = "alter table ~TABLENAMEENTITY~ " . $strSQL;
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}

	sort($arrPopulateFields);
	$strNewSearchableFields = implode(',', $arrPopulateFields);

	return $strNewSearchableFields;
}

// SLOW create and drop fields that we make searchable from within a JSON to the real table fields
function exposeEntityFieldsSlow($objConn_a, $strFormEntityCode_a, $blnRemoveAllNew_a, $arrFieldsToProcess_a, $blnRemoveAllSearchable_a, $arrSearchableFields_a)
{
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);

	$arrPopulateFields = array();

	$strSQL = "";

	// remove all old fields
	if ($blnRemoveAllSearchable_a)
	{
		logError(false, 'exposeEntityFieldsSlow START OF blnRemoveAllSearchable_a');
		
		foreach ($arrSearchableFields_a as $strField)
		{
			if (strlen($strField) > 0)
			{
				// alter table drop field
				$strSQL = "alter table ~TABLENAMEENTITY~ drop column ~FIELDNAME~";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
				try
				{
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				catch (Exception $e) { }
			}
		}
	}

	// remove all new fields
	if ($blnRemoveAllNew_a)
	{
		logError(false, 'exposeEntityFieldsSlow START OF blnRemoveAllNew_a');
		
		foreach ($arrFieldsToProcess_a as $arrField)
		{
			$strField = $arrField['fieldname'];	// the name of the field, eg: suburb_id or guid_suburb
			$strFieldCode = $arrField['fieldcode'];	// the base name/code of the field, eg: suburb
			$blnIsIDField = toBoolean($arrField['isidfield']);	// is it an guid_ or an _id field
			$strSource = $arrField['source'];	// the related entity

			if (strlen($strField) > 0)
			{
				if (($blnIsIDField == true) && (strlen($strSource) > 0))
				{
					// drop the foreign key
					dbDropForeignKeys($objConn_a, $strTableNameEntity, ffel($strField), __FUNCTION__);
				}

				// alter table drop field
				$strSQL = "alter table ~TABLENAMEENTITY~ drop column ~FIELDNAME~";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
				try
				{
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				catch (Exception $e) { }
			}
		}
	}

	// add the new fields again
	logError(false, 'exposeEntityFieldsSlow START OF add new fields again');
	
	foreach ($arrFieldsToProcess_a as $arrField)
	{
		$strField = $arrField['fieldname'];	// the name of the field, eg: suburb_id or guid_suburb
		$strFieldCode = $arrField['fieldcode'];	// the base name/code of the field, eg: suburb
		$blnIsIDField = toBoolean($arrField['isidfield']);	// is it an guid_ or an _id field
		$strSource = $arrField['source'];	// the related entity
		$strDataType = $arrField['datatype'];	// the exposed field's datatype
		$strLength = $arrField['length'];	// the exposed field's length if < 255 then it is 255

		if (strlen($strField) > 0)
		{
			// alter table add field
			if (($blnIsIDField == true) && (strlen($strSource) > 0))
			{
				$strSQL = "alter table ~TABLENAMEENTITY~ add column ~FIELDNAME~ bigint(20) NULL";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
				try
				{
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				catch (Exception $e) { }
			}
			else
			{
				if (($strDataType == "d_texthtml") || ($strDataType == "d_html-input"))
				{
					$strSQL = "alter table ~TABLENAMEENTITY~ add column ~FIELDNAME~ mediumblob NULL";
					$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
					try
					{
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					}
					catch (Exception $e) { }
				}
				else
				{
					$intLength = intval($strLength, 10);
					if ($intLength < intval(MINEXPOSEDFIELDSIZE, 10))
					{
						$intLength = intval(MINEXPOSEDFIELDSIZE, 10);
					}
					$strLength = strval($intLength);

					$strSQL = "alter table ~TABLENAMEENTITY~ add column ~FIELDNAME~ varchar(~LENGTH~) NULL";
					$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
					$strSQL = str_replace('~LENGTH~', $strLength, $strSQL);
					try
					{
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					}
					catch (Exception $e) { }
				}
			}

			if (($blnIsIDField == true) && (strlen($strSource) > 0))
			{
				$strTableNameEntitySource = getTableNameEntity($strSource, false);
				$strFK = createFKName();
				
				// create a foreign key
				$strSQL = "alter table ~TABLENAMEENTITY~ add constraint ~FK~ foreign key (~FIELDNAME~) references ~TABLENAMEENTITYSOURCE~ (id)";
				$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
				$strSQL = str_replace('~TABLENAMEENTITYSOURCE~', ff($strTableNameEntitySource), $strSQL);
				$strSQL = str_replace('~FIELDNAME~', ffel($strField), $strSQL);
				$strSQL = str_replace('~FK~', ff($strFK), $strSQL);
				try
				{
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				catch (Exception $e) { }
			}

			array_push($arrPopulateFields, $strField);
		}
	}
	logError(false, 'exposeEntityFieldsSlow END OF add new fields again');
	
	sort($arrPopulateFields);
	$strNewSearchableFields = implode(',', $arrPopulateFields);

	dbClearErrors();

	return $strNewSearchableFields;
}

// try to get the entity name from the SQL error
// eg: SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails (`dbname`.`d_contract_invoice`, CONSTRAINT `d_contract_invoice_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `d_contract` (`id`))
// if cannot get an entity code, then use the provided one.
function getIntegrityErrorDescription($objConn_a, $strSQLError_a, $strParentDescription_a)
{
	$strTableNameEntity = getTableNameEntity('entity', false);

	$strSQLError = $strSQLError_a;
	$strResult = "";

	$intStart = InStr($strSQLError, "`d_") + 1;
	$intEnd = InStrNext($strSQLError, "`", $intStart);
	$strEntityCode = substr($strSQLError, $intStart, $intEnd - $intStart);
	//$strEntityCode = str_replace("d_", "", $strEntityCode);
	$strEntityCode = strtoupper($strEntityCode);

	$strSQL = "select description returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffel($strEntityCode), $strSQL);
	$strEntityDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	if (strlen($strEntityDescription) > 0)
	{
		$strResult = "Error deleting '" . $strParentDescription_a . "' due to related '" . $strEntityDescription . "'.";
	}
	else
	{
		$strResult = "Error deleting '" . $strParentDescription_a . "'.";
	}

	return $strResult;
}

function getSearchableFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);

	$strSQL = "select searchablefields returnvalue from ~TABLENAMEENTITYCODE~ where code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode_a), $strSQL);

	$strResult = strtolower(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

	return $strResult;
}

function getSystemFormByCode($objConn_a, $strFormEntityCode_a)
{
	$strTableNameEntityCode = getTableNameEntity("systemform", false);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEENTITYCODE~ where code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode_a), $strSQL);

	$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	return $strResult;
}

// functions that DO NOT use the database:

function createIDXName()
{
	$strResult = ffeu('IDX_' . getGUID());
	return $strResult;
}

function createFKName()
{
	$strResult = ffeu('FK_' . getGUID());
	return $strResult;
}

function getTableNameDataForm($strClientID_a, $strFormEntityCode_a, $blnHistory_a)
{
	$strResult = "";
	if ($blnHistory_a)
	{
		$strResult = "z_history_df_~CLIENTID~_tbl~ENTITYCODE~";
	}
	else
	{
		$strResult = "z_df_~CLIENTID~_tbl~ENTITYCODE~";
	}
	$strResult = str_replace('~CLIENTID~', ff($strClientID_a), $strResult);
	$strResult = str_replace('~ENTITYCODE~', ffel($strFormEntityCode_a), $strResult);

	return $strResult;
}

function getTableNameEntity($strFormEntityCode_a, $blnHistory_a)
{
	$strResult = "";
	if ($blnHistory_a)
	{
		$strResult = "h_~ENTITYCODE~";
	}
	else
	{
		$strResult = "d_~ENTITYCODE~";
	}
	$strResult = str_replace('~ENTITYCODE~', ffel($strFormEntityCode_a), $strResult);

	return $strResult;
}

function getTableNameEntityExtension($strFormEntityCode_a)
{
	$strResult = "d_~ENTITYCODE~_x";
	$strResult = str_replace('~ENTITYCODE~', ffel($strFormEntityCode_a), $strResult);

	return $strResult;
}

// data repair

function renameAllFormSectionGUIDsByEntityCode($objConn_a, $strEntityCode_a, $strOldGUID_a, $strNewGUID_a)
{
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);
	$strTableNameEntityHistory = getTableNameEntity($strEntityCode_a, true);

	// non-history
	$strSQL = "select id, jsondata from ~TABLENAMEENTITY~ order by id";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ff($strEntityCode_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult))
	{
		$strFormID = $arrRow['id'];
		$strJSONData = $arrRow['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);

		$arrJSONData = updateSectionGUID($arrJSONData, $strOldGUID_a, $strNewGUID_a);

		$strJSONData = json_encode($arrJSONData);

		$strSQL = "update ~TABLENAMEENTITY~ set jsondata = '~JSONDATA~' where id = ~FORMID~";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~FORMID~', ff($strFormID), $strSQL);
		$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		//exposeEntityData($objConn_a, 'SYSTEMFORM', $strEntityCode_a, $strFormID, $strJSONData);
	}
	dbCloseRecordset($objResult);

	// history
	$strSQL = "select id, jsondata from ~TABLENAMEENTITY~ order by id";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntityHistory), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ff($strEntityCode_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult))
	{
		$strFormID = $arrRow['id'];
		$strJSONData = $arrRow['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);

		$arrJSONData = updateSectionGUID($arrJSONData, $strOldGUID_a, $strNewGUID_a);

		$strJSONData = json_encode($arrJSONData);

		$strSQL = "update ~TABLENAMEENTITY~ set jsondata = '~JSONDATA~' where id = ~FORMID~";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntityHistory), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~FORMID~', ff($strFormID), $strSQL);
		$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		//exposeEntityData($objConn_a, 'SYSTEMFORM', $strEntityCode_a, $strFormID, $strJSONData);
	}
	dbCloseRecordset($objResult);
}

// eg: renameSystemFormSectionGUID($objConn_a, "PRODUCT", "gffe35a8ad-d290-4ae3-8800-f2935dd30d07", "gffe35a8ad-d290-4ae3-8800-f2935dd30d07");
function renameSystemFormSectionGUID($objConn_a, $strEntityCode_a, $strOldGUID_a, $strNewGUID_a)
{
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "select id, code, jsondata from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ff($strEntityCode_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult))
	{
		$strSystemFormID = $arrRow['id'];
		$strSystemFormCode = $arrRow['code'];
		$strJSONData = $arrRow['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);

		$arrJSONData = updateSectionGUID($arrJSONData, $strOldGUID_a, $strNewGUID_a);
		renameAllFormSectionGUIDsByEntityCode($objConn_a, $strEntityCode_a, $strOldGUID_a, $strNewGUID_a);

		$strJSONData = json_encode($arrJSONData);

		$strSQL = "update ~TABLENAMESYSTEMFORM~ set jsondata = '~JSONDATA~' where id = ~SYSTEMFORMID~";
		$strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~SYSTEMFORMID~', ff($strSystemFormID), $strSQL);
		$blnResult = dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	dbCloseRecordset($objResult);

	return dbEndTrans($objConn_a, __FUNCTION__);
}

function updateSectionGUID($arrJSON_a, $strSectionCodeOld_a, $strSectionCodeNew_a)
{
    foreach ($arrJSON_a as &$arrSection)
	{
        $strSectionCode = $arrSection['sectioncode'];
        $strSectionCodeTemp = $arrSection['sectioncodetemp'];

		if (strtoupper($strSectionCode) == strtoupper($strSectionCodeOld_a))
		{
			$arrSection['sectioncode'] = $strSectionCodeNew_a;
		}

		if (strtoupper($strSectionCodeTemp) == strtoupper($strSectionCodeOld_a))
		{
			$arrSection['sectioncodetemp'] = $strSectionCodeNew_a;
		}
    }

	return $arrJSON_a;
}

// Build an order by based on entity display order

function entityBuildOrderBy($objConn_a, $strEntityCode_a, $strAppendFields_a = "")
{
    $strTableNameEntity = getTableNameEntity('entity', false);
    $strTableNameDisplayOrder = getTableNameEntity('displayorder', false);

    $strOrderBy = "";
    $strType = "";
    $strSortOrder = "";

    $strSQL = "select displayorder_id, ffe65a2521_c3d0_42fc_8d25_dedd43876fff_displayorderfield displayorderfield from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
    $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffeu($strEntityCode_a), $strSQL);
    //debug($strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    if($arrRow = dbReadRecord($objResult))
    {
        $strField = $arrRow['displayorderfield'];
        $strDisplayOrderID = $arrRow['displayorder_id'];

        if(strlen($strField) > 0)
        {
            $strField = strtolower($strField);

           if(intval($strDisplayOrderID) > 0)
           {

               $strSQL = "select  code returnvalue from ~TABLENAMEDISPLAYORDER~ where id = ~DISPLAYORDERID~";
               $strSQL = str_replace('~TABLENAMEDISPLAYORDER~', ff($strTableNameDisplayOrder), $strSQL);
               $strSQL = str_replace('~DISPLAYORDERID~', ff($strDisplayOrderID), $strSQL);

               $strDisplayOrderCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

               list($strType, $strSortOrder) = explode('_', $strDisplayOrderCode);

               $strSortOrder = strtolower($strSortOrder);

           }

           if($strType === 'NUMBER')
           {
                $strOrderBy = "cast(~FIELD~ as unsigned) ~SORTORDER~";
                $strOrderBy = str_replace('~FIELD~', $strField, $strOrderBy);
                $strOrderBy = str_replace('~SORTORDER~', $strSortOrder, $strOrderBy);
           }
           else
           {
               $strOrderBy = "~FIELD~ ~SORTORDER~";
               $strOrderBy = str_replace('~FIELD~', $strField, $strOrderBy);
               $strOrderBy = str_replace('~SORTORDER~', $strSortOrder, $strOrderBy);

           }
        }

    }

    dbCloseRecordset($objResult);

    if ((strlen($strAppendFields_a) > 0) && (strlen($strOrderBy) > 0)) {
        $strOrderBy .= ', ';
    }

    if(strlen($strAppendFields_a) > 0)
    {
        $strOrderBy .= $strAppendFields_a;
    }

    if (strlen($strOrderBy) > 0) {
        $strOrderBy = ' order by ' . $strOrderBy;
    }

    return $strOrderBy;
}

//			reassigning records to other clients:

function updateRecordClientIDByID($objConn_a, $strEntity_a, $strID_a, $strClientID_a)
{
    $strTableNameDynamic = getTableNameEntity($strEntity_a, false);

	if (strlen($strClientID_a) > 0)
	{
		$strSQL = "update ~TABLENAMEDYNAMIC~ set client_id = ~CLIENTID~, data_client_id = ~CLIENTID~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEDYNAMIC~', ff($strTableNameDynamic), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
}
