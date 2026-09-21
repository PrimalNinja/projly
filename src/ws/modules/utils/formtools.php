<?php
// function summary:
//
//		functions that use the database:
//
//			form retrieval:
//
//				- get a single form field
//					formFieldGetFromDBBySectionCodeFieldCode($objConn_a, $strEntityCode_a, $strEntityDataID_a, $strSectionCode_a, $strFieldCode_a)
//					eg: $objField = formFieldGetFromDBBySectionCodeFieldCode($objConn_a, "EMPLOYER", $strEmployerID, "0eb18852-79d0-40e9-a337-2d0e14daf26e", "ADDRESSLINE1");
//				- get multiple form field values
// 					formFieldValuesGetFromDBByCode($objConn_a, $strEntityCode_a, $strClientID_a, $strCode_a, $arrFields_a)
//					eg: $arrResult = formFieldValuesGetFromDBByCode($objConn_a, "GENDER", $strSubmitToClientID, $arrJSONField['p_value'], ["id", "description"]);
//				- get multiple form field values
//					formFieldValuesGetFromDBByID($objConn_a, $strEntityCode_a, $strClientID_a, $strID_a, $arrFields_a)
//				- get a form
// 					formGetFromDBByEntityDataID($objConn_a, $strEntityCode_a, $strEntityDataID_a)
//				- get a fragment (template)
//					formFragmentGetFromDBByFragmentCode($objConn_a, $strFragmentCode_a, $strSectionCode_a)
//				- get a section of a from
//					formSectionGetFromDBByEntityCodeSectionCode($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strSectionCode_a)
//				- get a form template
//					formTemplateGetFromDBByEntityCode($objConn_a, $strEntityCode_a)
//				- update a form in the database with a section from another json form: NOT YET FULLY IMPLEMENTED, SEE COMMENTS WITHIN FUNCTION
//					formUpdateWithOtherFormSection($objConn_a, $arrSourceJSONData_a, $strSourceSectionCode_a, $strDestinationEntityCode_a, $strDestinationEntityID_a, $strCode_a, $strDescription_a)
//				- look up suburb info
//					suburbInfoGetFromDBBySuburbID($objConn_a, $strSuburbPopulateID)
// 
// 			form transfer functions:
//
//				- SPECIFIC transfer of defaults from one entity suffixed by _DEFAULT to another not suffixed
//					formTransferDBDefaults($objConn_a, $intSourceClientID_a, $strSourceFormEntityCode_a, $intDestClientID_a, $strDestFormEntityCode_a, $strParentIDField_a, $strParentIDValue_a)
//					eg: formTransferDBDefaults($objConn_a, $strClientID_a, "invoice_evidence_default", $strClientID_a, "invoice_evidence", "invoice_id", $strFormDataID_a);
//				- update multiple field values within a form within the database iterating through what matches the filter.  Perfect reference Data dependencies when reference data changes.
//					formValuesUpdateDBIterate($objConn_a, $strClientID_a, $strEntityCode_a, $strFilterField_a, $strFilterValue_a, $arrFields_a)
//					eg: formValuesUpdateDBIterate($objConn_a, $strClientID_a, "rto_learner", "person_id", $strFormDataID_a, array(
//						array(
//							"sectioncode" => "b535f187-b8cc-4671-bc9b-f8865945b3d7",
//							"fieldname" => "PERSON",
//							"value" => $strFormDataID_a,
//							"valuedescription" => $strFullName . ' | ' . $strDateOfBirth . ' | ' . $strSuburb . ' | '  . $strUSI,
//							"updatejson" => true,
//							"isnumber" => false
//						)
//					));
//
//		functions that DO NOT use the database:
//
//			form content retrieval:
// 
//				- get a form section from a form json
//					formGetSectionBySectionCode($arrJSON_a, $strSectionCode_a)
//				- get a form field from a form json
//					formFieldGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
//					eg: $arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "STORAGEUSED");
//				- get a form field value from a form json
//					formValueGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
//					eg: $arrJSONField = formValueGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "STORAGEUSED");
//				- get a field value from a json
//					formValueGetBySectionTypeFieldCode($arrJSON_a, $strSectionType_a, $strFieldCode_a)
//					eg: $intLatestFormVersion = intval(formValueGetBySectionTypeFieldCode($arrLatestFormJSON, 'FORMHEADER', 'FORMVERSION'));
//
//			form transfer functions:
//
//				- transfer all values from one form to another, forms must the same section guids to transfer
//					formTransferAllValuesToNewForm($arrJSONFormFrom_a, $arrJSONFormTo_a)
//				- transfer all values from one form's section to another form's section, the section guids can be specified
//					formTransferSectionValues($arrJSONFormFrom_a, $strSectionCodeFrom_a, $arrJSONFormTo_a, $strSectionCodeTo_a)
//
//			form modification:
//
//				- ...
//					formAppendField($arrJSON_a, $strSectionType_a, $arrNewField)	 -- NOT CURRENTLY USED
//				- ...
//					formAppendSection($arrJSON_a, $arrNewSection_a, $strOptionalSectionCode_a, $strOptionalSectionTitle_a)
//				- hide a field on a form
//					$arrJSONData = formHideFieldBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
//				- hide fields on a form
//					$arrJSONData = formHideFieldsBySectionCodeFieldCodes($arrJSON_a, $strSectionCode_a, $arrFields_a);
//				- show a field on a form
//					$arrJSONData = formShowFieldBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
//				- show fields on a form
//					$arrJSONData = formShowFieldsBySectionCodeFieldCodes($arrJSON_a, $strSectionCode_a, $arrFields_a);
//				- ...
//					formUpdateDataTypeBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strDataType_a, $strLength_a, $strClasses_a) -- NOT CURRENTLY USED
//				- update the field's value and description in a json
//					formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a, $strValueDescription_a)
//				- update the field's value and description in a json for multi-select
//					formMultiUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a, $strValueDescription_a)
//				- update the field's value description only in a json
//					formValueDescriptionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValueDescription_a)
//				- update the field's selection only in a json based on section CODE
//					formSelectionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $arrSelection_a)
//				- update the field's style only in a json based on section CODE
//					formStyleUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
//				- update the field's value only in a json based on section CODE
//					formValueUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
//				- update the field's value only in a json based on section TYPE
//					formValueUpdateBySectionTypeFieldCode($arrJSON_a, $strSectionType_a, $strFieldCode_a, $strValue_a)
//				- update a bunch of fields in a section
//					formValuesUpdate($arrJSON_a, $arrFields_a)
//					eg: formValuesUpdate($arrJSON_a, array(
//						array(
//							"sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
//							"fieldname" => "USI",
//							"value" => $strValue,
//							"valuedescription" => "ABC"			(optional valuedescription)
//						},
//						array(
//							"sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
//							"fieldname" => "person_id",
//							"value" => $strValue,
//							"valuedescription" => "5"			(optional valuedescription)
//						}
//					));
//				- make a field readonly
//					makeFieldReadOnlyBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
//
//			general form utils:
//
//				- ...
//					formCheckSectionCodeExists($arrJSON_a, $strSectionCode_a)
//				- ...
//					formCheckSectionTypeExists($arrJSON_a, $strSectionType_a)
//				- ...
//					formCheckSectionTypeFieldExist($arrJSON_a, $strSectionType_a, $strFieldCode_a)
//				- ...
//					formMassageData($arrJSON_a)
//				- ...
//					formRevertSecuredIDs($arrJSON_a)
//				- ...
//					formSecureIDs($arrJSON_a)
//				- ...
//					massageDatatypeDate($str_a)
//				- ...
//					massageDatatypeTime($str_a)
//				- ...
//					formUpdateImageIDs($arrJSON_a, $arrImages_a)
//

// functions that use the database

// form retrieval

// get a form from the database
function formGetFromDBByEntityDataID($objConn_a, $strEntityCode_a, $strEntityDataID_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMEENTITYCODE~ where id = ~ENTITYDATAID~";
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	//$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	// got the json of the form we want the code from
	$arrJSONData = json_decode($strJSONData, true);

	// return the field
	return $arrJSONData;
}

function formFragmentGetFromDBByFragmentCode($objConn_a, $strFragmentCode_a, $strSectionCode_a)
{
	return formSectionGetFromDBByEntityCodeSectionCode($objConn_a, "SYSTEMFORM", $strFragmentCode_a, $strSectionCode_a);
}

function formSectionGetFromDBByEntityCodeSectionCode($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strSectionCode_a)
{
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEENTITY~ where code = '~FORMENTITYCODE~'";
	$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace("~FORMENTITYCODE~", ff($strFormEntityCode_a), $strSQL);

	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	$arrJSON = json_decode($strJSONData, true);
	
	return formGetSectionBySectionCode($arrJSON, $strSectionCode_a);
}

function formTemplateGetFromDBByEntityCode($objConn_a, $strEntityCode_a)
{
	$strTableNameSystemForm = getTableNameEntity("systemform", false);
	    
    if (dependencies('entity/entitySystemFormFileJSON'))
    {        
        $strJSONData = "";

        if (toBoolean(FETCHSYSTEMFORMSFROMFILE))
        {
            $strJSONData = entitySystemFormFileJSON($strEntityCode_a);            
        }
        
        if (strlen($strJSONData) == 0)
        {
            $strSQL = "select jsondata returnvalue from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
            $strSQL = str_replace("~TABLENAMESYSTEMFORM~", ff($strTableNameSystemForm), $strSQL);
            $strSQL = str_replace("~ENTITYCODE~", ffeu($strEntityCode_a), $strSQL);

            $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        }
    }

	return json_decode($strJSONData, true);
}

function formUpdateWithOtherFormSection($objConn_a, $arrSourceJSONData_a, $strSourceSectionCode_a, $strDestinationEntityCode_a, $strDestinationEntityID_a, $strDestinationSectionCode_a, $strCode_a, $strDescription_a)
{
	$arrResult = array();
	
	$arrResult = formGetFromDBByEntityDataID($objConn_a, $strDestinationEntityCode_a, $strDestinationEntityID_a);
	$arrResult = formTransferSectionValues($arrSourceJSONData_a, $strSourceSectionCode_a, $arrResult, $strDestinationSectionCode_a);
	
	// TODO: update the destination entity in the database

	return $arrResult;
}

// get form suburb info
function suburbInfoGetFromDBBySuburbID($objConn_a, $strSuburbPopulateID)
{
	$strTableNameState = getTableNameEntity("state", false);
	$strTableNameSuburb = getTableNameEntity("suburb", false);

	$strSuburb = "";
	$strState = "";
	$strPostcode = "";
	$strCountry = "";
	
	// fetch
	$strSQL =
	"
	select suburb, state, postcode, country
	from ~TABLENAMESUBURB~
	where id = ~SUBURBID~
	";
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~SUBURBID~', $strSuburbPopulateID, $strSQL);

	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult))
	{
		$strSuburb = $arrRow['suburb'];
		$strState = $arrRow['state'];
		$strPostcode = $arrRow['postcode'];
		$strCountry = $arrRow['country'];
	}
	dbCloseRecordset($objResult);
	
	$strSQL = "select id returnvalue from ~TABLENAMESTATE~ where code = '~STATE~'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~STATE~', ff($strState), $strSQL);
	$strStateID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	return array("suburb" => $strSuburb, "state" => $strState, "postcode" => $strPostcode, "country" => $strCountry, "stateid" => $strStateID);
}

// form transfer functions

function formFieldValuesGetFromDBByCode($objConn_a, $strEntityCode_a, $strClientID_a, $strCode_a, $arrFields_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);

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
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
		}
	}

	$arrResult = array();
	
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
	
	$strSQL = "";
	if ($blnIgnoreClient)
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAMEENTITYCODE~ where code = '~CODE~'";
	}
	else
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~ and code = '~CODE~'";
	}
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	$strSQL = str_replace('~FIELDS~', ff($strFields), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
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

function formFieldValuesGetFromDBByID($objConn_a, $strEntityCode_a, $strClientID_a, $strID_a, $arrFields_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);

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
	
	$arrResult = array();
	
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
	
	$strSQL = "";
	if ($blnIgnoreClient)
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAMEENTITYCODE~ where id = ~ID~";
	}
	else
	{
		$strSQL = "select ~FIELDS~ from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~ and id = ~ID~";
	}
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
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

function formFieldGetFromDBBySectionCodeFieldCode($objConn_a, $strEntityCode_a, $strEntityDataID_a, $strSectionCode_a, $strFieldCode_a)
{
	// got the json of the form we want the code from
	$arrJSONData = formGetFromDBByEntityDataID($objConn_a, $strEntityCode_a, $strEntityDataID_a);

	// return the field
	return formFieldGetBySectionCodeFieldCode($arrJSONData, $strSectionCode_a, $strFieldCode_a);
}

function formTransferDBDefaults($objConn_a, $intSourceClientID_a, $strSourceFormEntityCode_a, $intDestClientID_a, $strDestFormEntityCode_a, $strParentIDField_a, $strParentIDValue_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameSource = getTableNameEntity($strSourceFormEntityCode_a, false);
	$strTableNameDest = getTableNameEntity($strDestFormEntityCode_a, false);
	
	$strLogin = $_SESSION['server_loggedin_user'];
		
	// work out the new dataentityid
	$strSystemClientID = getSystemClientID($objConn_a);
		
	$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~FORMENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);
	$strSQL = str_replace('~FORMENTITYCODE~', ff($strDestFormEntityCode_a), $strSQL);
	$strDataEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	dbBeginTrans($objConn_a, __FUNCTION__);
	
	$strSQL = "select entity_id, code, description, is_enabled, jsondata from ~TABLENAMESOURCE~ where client_id = ~CLIENTID~ order by id";
	$strSQL = str_replace('~TABLENAMESOURCE~', ff($strTableNameSource), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($intSourceClientID_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
		$strEntityID = $arrRow['entity_id'];
		$strCode = $arrRow['code'];
		$strDescription = $arrRow['description'];
		$strIsEnabled = $arrRow['is_enabled'];
		$strJSONData = $arrRow['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);
		
		//update the form entity so editing the form saves to the correct table (and not the 'default' table)
		//$strEntityCode = formValueGetBySectionTypeFieldCode($arrJSONData, "FORMHEADER", "ENTITY");
		//$strEntityCode = str_replace("_DEFAULT", "", $strEntityCode);	// use the same code minus the _DEFAULT suffix
		//$arrJSONData = formValueUpdateBySectionTypeFieldCode($arrJSONData, "FORMHEADER", "ENTITY", $strEntityCode);

		$arrJSONData = formValueUpdateBySectionTypeFieldCode($arrJSONData, "FORMHEADER", "ENTITY", $strDestFormEntityCode_a);
		$strJSONData = json_encode($arrJSONData);
		
		//create a new record based on the default
		$strSQL = "
insert into ~TABLENAMEDEST~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~'
)";
		$strSQL = str_replace('~TABLENAMEDEST~', ff($strTableNameDest), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($intDestClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
		$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strID = dbLastInsertID($objConn_a);
		
		if ((strlen($strParentIDField_a) > 0) && (strlen($strID) > 0))
		{
			$strSQL = "update ~TABLENAMEDEST~ set ~PARENTIDFIELD~ = ~PARENTIDVALUE~ where client_id = ~CLIENTID~ and id = ~IDVALUE~";
			$strSQL = str_replace('~TABLENAMEDEST~', ff($strTableNameDest), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($intDestClientID_a), $strSQL);
			$strSQL = str_replace('~PARENTIDFIELD~', ff($strParentIDField_a), $strSQL);
			$strSQL = str_replace('~PARENTIDVALUE~', ffn($strParentIDValue_a), $strSQL);
			$strSQL = str_replace('~IDVALUE~', ff($strID), $strSQL);
			
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
		
		if (strlen($strDestFormEntityCode_a) > 0)
		{
			transferEntityData($objConn_a, 'systemform', $strDestFormEntityCode_a, $intDestClientID_a, $strID, $strJSONData);
		}
	}
	dbCloseRecordset($objResult);

	dbEndTrans($objConn_a, __FUNCTION__);
}

// example:
//
// formValuesUpdateDBIterate($objConn_a, $strDataClientID_a, "learner", "rtolearner_id", $strFormDataID_a, array(
  // array(
   // "sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
   // "fieldname" => "USI",
   // "value" => $strValue,
   // "valuedescription" => "ABC",
   // "updatejson" => true,					// true if want to update the json field, false if you want to update the manually created field, have 2 array elements if you want to update both
											// searchable fields are automatically catered for
   // "isnumber" => false
  // },
  // array(
   // "sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
   // "fieldname" => "person_id",
   // "value" => $strValue,
   // "valuedescription" => "5",
   // "updatejson" => true,
   // "isnumber" => true
  // }
 // ));
function formValuesUpdateDBIterate($objConn_a, $strClientID_a, $strEntityCode_a, $strFilterField_a, $strFilterValue_a, $arrFields_a)
{
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);
	
	$strSQL = "select id, jsondata from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~ and ~FILTERFIELD~ = ~FILTERVALUE~";
	$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~FILTERFIELD~', ff($strFilterField_a), $strSQL);
	$strSQL = str_replace('~FILTERVALUE~', ff($strFilterValue_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
		$strID = $arrRow['id'];
		$strJSONData = $arrRow['jsondata'];

		// got the json of the form we want the code from
		$arrJSONData = json_decode($strJSONData, true);
		$strFieldList = "";

		// update the jsondata & create a field list
		foreach ($arrFields_a as $keyField => $arrField) 
		{
			if (strlen($strFieldList) > 0)
			{
				$strFieldList = $strFieldList . ", ";
			}
			
			if ($arrField['updatejson'])
			{
				$strSectionCode = $arrField['sectioncode'];
				$strFieldName = $arrField['fieldname'];
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldName, $arrField['value']);
				if ($arrField['valuedescription'] != null)
				{
					$arrJSONData = formValueDescriptionUpdateBySectionCodeFieldCode($arrJSONData, $strSectionCode, $strFieldName, $arrField['valuedescription']);
				}
				$strFieldList .= "jsondata = '~JSONDATA~'";
			}
			else
			{
				$strFieldName = str_replace('-', '_', $arrField['fieldname']);
				
				if ($arrField['isnumber'])
				{
					$strFieldList .= $strFieldName . "= " . ff($arrField['value']);
				}
				else
				{
					$strFieldList .= $strFieldName . "= '" . ff($arrField['value']) . "'";
				}
			}
		}
		
		$strJSONData = json_encode($arrJSONData);

		$strSQL = "update ~TABLENAMEENTITYCODE~ set ~FIELDLIST~ where client_id = ~CLIENTID~ and id = ~ID~";
		$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
		$strSQL = str_replace('~FIELDLIST~', $strFieldList, $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ID~', ff($strID), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		if (strlen($strEntityCode_a) > 0)
		{
			transferEntityData($objConn_a, 'systemform', $strEntityCode_a, $strClientID_a, $strID, $strJSONData);
		}
	}
		
	dbCloseRecordset($objResult);
}

function makeFieldReadOnlyBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_readonly'] = $strValue_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

// functions that DO NOT use the database

// form content retrieval

// read a json section
function formGetSectionBySectionCode($arrJSON_a, $strSectionCode_a)
{
	$blnFound = false;
    $arrResult = array();

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as &$arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			//$arrFields = $arrSection['fields'];

			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{
				$arrResult = $arrSection;
				break;
			}
		}
	}

    return $arrResult;
}

function formFieldDataTypeGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
    $strResult = "";
    $arrField = formFieldGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
    $strResult = $arrField['p_datatype'];
    return $strResult;
}


// read a json field within a specific section
function formFieldGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$blnFound = false;
    $arrResult = array();

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as &$arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];

			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{
				foreach ($arrFields as &$arrField) 
				{
					$strFieldCode = $arrField['p_name'];
					//if (isset($arrField['p_value']))
					//{
						//$strFieldValue = $arrField['p_value'];    
					//}
					//else 
					//{
						//$strFieldValue = '';    
					//}
					
					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrResult = $arrField;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrResult;
}

// read a json field value within a section code
function formValueGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$blnFound = false;
    $strResult = '';

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as &$arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{
				foreach ($arrFields as &$arrField) 
				{
					$strFieldCode = $arrField['p_name'];
					if (isset($arrField['p_value']))
					{
						$strFieldValue = $arrField['p_value'];    
					}
					else 
					{
						$strFieldValue = '';    
					}
					
					
					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$strResult = $strFieldValue;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $strResult;
}

// read a json field description within a section code
function formDescriptionGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$blnFound = false;
    $strResult = '';

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as &$arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{
				foreach ($arrFields as &$arrField) 
				{
					$strFieldCode = $arrField['p_name'];
					if (isset($arrField['p_valuedescription']))
					{
						$strFieldDescription = $arrField['p_valuedescription'];    
					}
					else 
					{
						$strFieldDescription = '';    
					}
					
					
					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$strResult = $strFieldDescription;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $strResult;
}

// read a json field within a section type (usually used for the generic code, description and is_enabled but also for formheader fields)
function formValueGetBySectionTypeFieldCode($arrJSON_a, $strSectionType_a, $strFieldCode_a)
{
	$blnFound = false;
    $strResult = '';

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as &$arrSection) 
		{
			$strSectionType = str_replace(' ', '', $arrSection['sectiontype']);
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionType) == strtoupper($strSectionType_a))
			{
				foreach ($arrFields as &$arrField) 
				{
					$strFieldCode = $arrField['p_name'];
					if (isset($arrField['p_value']))
					{
						$strFieldValue = $arrField['p_value'];    
					}
					else 
					{
						$strFieldValue = '';    
					}
					
					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$strResult = $strFieldValue;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $strResult;
}

// form transfer functions

// update a jsondata value & valuedescription
function formTransferAllValuesToNewForm($arrJSONFormFrom_a, $arrJSONFormTo_a)
{
    $arrJSONFormTo = $arrJSONFormTo_a;
    
    foreach ($arrJSONFormFrom_a as &$arrSectionFrom)
    {
        $strSectionFromType = $arrSectionFrom['sectiontype'];
		$strSectionFromCode = $arrSectionFrom['sectioncode'];
        $arrFieldsFrom = $arrSectionFrom['fields'];
        if ($strSectionFromType != 'FORMHEADER')
        {
            foreach ($arrFieldsFrom as &$arrFieldFrom) 
            {
                $strFieldFromCode = $arrFieldFrom['p_name'];

				$strFieldFromValue = '';
				$strFieldFromValueDescription = '';
                if (isset($arrFieldFrom['p_value']))
                {
                    $strFieldFromValue = $arrFieldFrom['p_value'];    
					if (array_key_exists('p_valuedescription', $arrFieldFrom))
					{
						$strFieldFromValueDescription = $arrFieldFrom['p_valuedescription'];    
					}
                }
                else 
                {
                    $strFieldFromValue = '';    
					$strFieldFromValueDescription = '';
                }

                $arrJSONFormTo = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONFormTo, $strSectionFromCode, $strFieldFromCode, $strFieldFromValue, $strFieldFromValueDescription);
            } 
        } 
    }

    return $arrJSONFormTo;
}

// update a jsondata value & valuedescription for a specific section
function formTransferSectionValues($arrJSONFormFrom_a, $strSectionCodeFrom_a, $arrJSONFormTo_a, $strSectionCodeTo_a)
{
    $arrJSONFormTo = $arrJSONFormTo_a;
    
    foreach ($arrJSONFormFrom_a as &$arrSectionFrom)
    {
		$strSectionFromCode = $arrSectionFrom['sectioncode'];
        $arrFieldsFrom = $arrSectionFrom['fields'];
        if ($strSectionFromCode == $strSectionCodeFrom_a)
        {
            foreach ($arrFieldsFrom as &$arrFieldFrom) 
            {
                $strFieldFromCode = $arrFieldFrom['p_name'];

				$strFieldFromValue = '';
				$strFieldFromValueDescription = '';
                if (isset($arrFieldFrom['p_value']))
                {
                    $strFieldFromValue = $arrFieldFrom['p_value'];    
					if (array_key_exists('p_valuedescription', $arrFieldFrom))
					{
						$strFieldFromValueDescription = $arrFieldFrom['p_valuedescription'];    
					}
                }
                else 
                {
                    $strFieldFromValue = '';    
					$strFieldFromValueDescription = '';
                }

                $arrJSONFormTo = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONFormTo, $strSectionCodeTo_a, $strFieldFromCode, $strFieldFromValue, $strFieldFromValueDescription);
            } 
        } 
    }
	
    return $arrJSONFormTo;
}

// form modification

// update a json add new field at the bottom of the array
function formAppendField($arrJSON_a, $strSectionType_a, $arrNewField)
{
    $blnFound = false;
    
	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionType = $arrSection['sectiontype'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionType) == strtoupper($strSectionType_a))
			{
				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($arrNewField['p_name']))
					{
						$blnFound = true;
						break;
					}
				}
				
				if (!$blnFound)
				{
					$arrJSON_a[$keySection]['fields'][] = $arrNewField;    
				}
			}
		}
	}

    return $arrJSON_a;
}

// delete a field 
function formDeleteField($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$blnFound = false;
	$arrResult = $arrJSON_a;

    foreach ($arrResult as $keySection => $arrSection) {
        $strSectionCode = $arrSection['sectioncode'];
        $arrFields = $arrSection['fields'];
        
        if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
        {
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			foreach ($arrFields as $keyField => $arrField) 
			{
				$strFieldCode = $arrField['p_name'];
				
				if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
				{
					unset($arrResult[$keySection]['fields'][$keyField]);
					$blnFound = true;
					break;
				}
            }
            
            // deleting an item does not reset the index which cause problem in js side
            // so, lets re-index the array using array values
            $arrResult[$keySection]['fields'] = array_values($arrResult[$keySection]['fields']);
        }
                
        if ($blnFound)
        {
            break;
        }
    }

	return $arrResult;		
}

// appends a section at the bottom of the array
function formAppendSection($arrJSON_a, $arrNewSection_a, $strOptionalSectionCode_a, $strOptionalSectionTitle_a)
{
	$arrNewSection = $arrNewSection_a;
	if (strlen($strOptionalSectionCode_a) > 0)
	{
		$arrNewSection['sectioncode'] = $strOptionalSectionCode_a;
	}
	if (strlen($strOptionalSectionTitle_a) > 0)
	{
		$arrNewSection['sectiontitle'] = $strOptionalSectionTitle_a;
	}
	
	$arrJSON_a[] = $arrNewSection;
    return $arrJSON_a;
}

function formHideFieldBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$arrJSON = $arrJSON_a;
	$objField = formFieldGetBySectionCodeFieldCode($arrJSON, $strSectionCode_a, $strFieldCode_a);
	
	$strStyle = "";
	if (array_key_exists("p_style", $objField))
	{
		$strStyle = $objField['p_style'];
	}
	
	if (InStr($strStyle, "gb-hidden") < 0)
	{
		$strStyle .= " gb-hidden";
		$strStyle = trim($strStyle);
	}
				
	$arrJSON = formStyleUpdateBySectionCodeFieldCode($arrJSON, $strSectionCode_a, $strFieldCode_a, $strStyle);
	
	return $arrJSON;
}

function formHideFieldsBySectionCodeFieldCodes($arrJSON_a, $strSectionCode_a, $arrFields_a)
{
	$arrResult = $arrJSON_a;

	for ($intI = 0; $intI < count($arrFields_a); $intI++)
	{
		$strField = $arrFields_a[$intI];
		$arrResult = formHideFieldBySectionCodeFieldCode($arrResult, $strSectionCode_a, $strField);
	}
	
	return $arrResult;
}

function formShowFieldBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	$arrJSON = $arrJSON_a;
	
	$objField = formFieldGetBySectionCodeFieldCode($arrJSON, $strSectionCode_a, $strFieldCode_a);
	$strStyle = " " . $objField['p_style'] . " ";
	if (InStr($strStyle, " gb-hidden ") >= 0)
	{
		$strStyle = str_replace(" gb-hidden ", "", $strStyle);
		$strStyle = trim($strStyle);
	}
	$objField['p_style'] = $strStyle;
	
	$arrJSON = formStyleUpdateBySectionCodeFieldCode($arrJSON, $strSectionCode_a, $strFieldCode_a, $strStyle);

	return $arrJSON;
}

function formShowFieldsBySectionCodeFieldCodes($arrJSON_a, $strSectionCode_a, $arrFields_a)
{
	$arrResult = $arrJSON_a;
	
	for ($intI = 0; $intI < count($arrFields_a); $intI++)
	{
		$strField = $arrFields_a[$intI];
		$arrResult = formShowFieldBySectionCodeFieldCode($arrResult, $strSectionCode_a, $strField);
	}
	
	return $arrResult;
}

function formFieldDataTypeSetDataType($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strDataType_a, $strSelection_a, $strClasses_a, $intLines_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_datatype'] = $strDataType_a;
                                                
                        if ($strDataType_a == 'd_representation')
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = "form-control d_representation " . $strClasses_a;
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = $strSelection_a;
							$arrJSON_a[$keySection]['fields'][$keyField]['p_options'] = 'd_singledropdown';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_lines'] = strval($intLines_a);
                        }
                        else if ($strDataType_a == 'd_list')
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = "form-control d_list " . $strClasses_a;
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = $strSelection_a;
							$arrJSON_a[$keySection]['fields'][$keyField]['p_options'] = 'd_singledropdown';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_lines'] = strval($intLines_a);
                        }
                        else if ($strDataType_a == 'd_multilist')
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = "form-control d_multilist " . $strClasses_a;
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = $strSelection_a;
							$arrJSON_a[$keySection]['fields'][$keyField]['p_options'] = 'd_singledropdown';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_lines'] = strval($intLines_a);
                        }
                        else if ($strDataType_a == 'd_multilinetext')
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = "form-control d_multilinetext " . $strClasses_a;
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = '';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_options'] = '';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_lines'] = strval($intLines_a);
                        }
                        else
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = "form-control";
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = '';
							$arrJSON_a[$keySection]['fields'][$keyField]['p_options'] = '';
							//$arrJSON_a[$keySection]['fields'][$keyField]['p_lines'] = strval($intLines_a);
                        }

						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formUpdateDataTypeBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strDataType_a, $strLength_a, $strClasses_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_datatype'] = $strDataType_a;
						$arrJSON_a[$keySection]['fields'][$keyField]['p_length'] = $strLength_a;
						$arrJSON_a[$keySection]['fields'][$keyField]['p_classes'] = $strClasses_a;
						
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formSelectionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $arrSelection_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_selection'] = $arrSelection_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formStyleUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strStyle_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_style'] = $strStyle_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formValueUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a, $strValueDescription_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue_a;
						$arrJSON_a[$keySection]['fields'][$keyField]['p_valuedescription'] = $strValueDescription_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formMultiUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a, $strValueDescription_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrValues = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];
						$arrDescriptions = $arrJSON_a[$keySection]['fields'][$keyField]['p_valuedescription'];
						
						if (!is_array($arrValues))
						{
							$arrValues = array();
							$arrDescriptions = array();
						}
						
						if (!in_array($strValue_a, $arrValues))
						{
							$arrValues[] = $strValue_a;
							$arrDescriptions[] = $strValueDescription_a;
							
							$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $arrValues;
							$arrJSON_a[$keySection]['fields'][$keyField]['p_valuedescription'] = $arrDescriptions;
						}
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

function formValueDescriptionUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValueDescription_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_valuedescription'] = $strValueDescription_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

// update a json field within a section type (usually used for the generic code, description and is_enabled but also for formheader fields)
function formValueUpdateBySectionTypeFieldCode($arrJSON_a, $strSectionType_a, $strFieldCode_a, $strValue_a)
{
    $blnFound = false;

	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionType = $arrSection['sectiontype'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionType) == strtoupper($strSectionType_a))
			{

				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue_a;
						$blnFound = true;
						break;
					}
				}
			}
					
			if ($blnFound)
			{
				break;
			}
		}
	}

    return $arrJSON_a;
}

// example:
//
// formValuesUpdate($objConn_a, $strDataClientID_a, "learner", "rtolearner_id", $strFormDataID_a, array(
  // array(
   // "sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
   // "fieldname" => "USI",
   // "value" => $strValue,
   // "valuedescription" => "ABC"
  // },
  // array(
   // "sectioncode" => "befb634c-50f2-405a-b0a4-eb7461ec4780",
   // "fieldname" => "person_id",
   // "value" => $strValue,
   // "valuedescription" => "5"
  // }
 // ));
function formValuesUpdate($arrJSON_a, $arrFields_a)
{
	$arrResult = $arrJSON_a;
	
	// update the jsondata & create a field list
	foreach ($arrFields_a as $keyField => $arrField) 
	{
		$strSectionCode = $arrField['sectioncode'];
		$strFieldName = $arrField['fieldname'];
		$arrResult = formValueUpdateBySectionCodeFieldCode($arrResult, $strSectionCode, $strFieldName, $arrField['value']);
		if ($arrField['valuedescription'] != null)
		{
			$arrResult = formValueDescriptionUpdateBySectionCodeFieldCode($arrResult, $strSectionCode, $strFieldName, $arrField['valuedescription']);
		}
	}
		
	return $arrResult;
}

// general form utils

//check if JSON Section Code Exist
function formCheckSectionCodeExists($arrJSON_a, $strSectionCode_a)
{
    $blnFound = false;
    
	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionCode = $arrSection['sectioncode'];
			
			if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
			{
				$blnFound = true;
				break;
			}
		}
	}

    return $blnFound;
}

//check if JSON Section Type Exist
function formCheckSectionTypeExists($arrJSON_a, $strSectionType_a)
{
    $blnFound = false;
    
	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionType = $arrSection['sectiontype'];
			
			if (strtoupper($strSectionType) == strtoupper($strSectionType_a))
			{
				$blnFound = true;
				break;
			}
		}
	}

    return $blnFound;
}

//check if JSON Field Exist
function formCheckSectionTypeFieldExist($arrJSON_a, $strSectionType_a, $strFieldCode_a)
{
    $blnFound = false;
    
	if (($arrJSON_a != null) && is_array($arrJSON_a))
	{
		foreach ($arrJSON_a as $keySection => $arrSection) 
		{
			$strSectionType = $arrSection['sectiontype'];
			$arrFields = $arrSection['fields'];
			
			if (strtoupper($strSectionType) == strtoupper($strSectionType_a))
			{
				foreach ($arrFields as $keyField => $arrField) {
					$strFieldCode = $arrField['p_name'];

					if (strtoupper($strFieldCode) == strtoupper($strFieldCode_a))
					{
						$blnFound = true;
						break;
					}
				}
			}
		}
	}

    return $blnFound;
}

// massage form data
function formMassageData($arrJSON_a)
{
    if (($arrJSON_a != null) && is_array($arrJSON_a))
    {
        foreach ($arrJSON_a as $keySection => $arrSection) 
		{
        
            $arrFields = $arrSection['fields'];
    
            foreach ($arrFields as $keyField => $arrField) {
                $strFieldType = $arrField['p_datatype'];
				$strStyle = " " . $arrField['p_style'] . " ";
                
                if (isset($arrJSON_a[$keySection]['fields'][$keyField]['p_value'])) // sometimes fields has no p_value?
                {    
                    $strValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];

                    if (!empty($strValue)) // strlen throws warning if a value is array.
                    {
                        if (strtoupper($strFieldType) == strtoupper('d_date'))
                        { 
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = massageDatatypeDate($strValue);
                        }
                        else if (strtoupper($strFieldType) == strtoupper('d_time'))
                        {
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = massageDatatypeTime($strValue);
                        }
                    }
                }

				if (InStr($strStyle, " gb-hidden ") >= 0)
				{
					$strStyle = str_replace(" gb-hidden ", "", $strStyle);
					$strStyle = trim($strStyle);
					$arrJSON_a[$keySection]['fields'][$keyField]['p_style'] = $strStyle;
				}
            }
        }    
    }

    return $arrJSON_a;
}

// revert all secured id's from JSONData
function formRevertSecuredIDs($arrJSON_a)
{
    if (($arrJSON_a != null) && is_array($arrJSON_a))
    {
        foreach ($arrJSON_a as $keySection => $arrSection) {
            
            $arrFields = $arrSection['fields'];
    
            foreach ($arrFields as $keyField => $arrField) {
                $strFieldType = $arrField['p_datatype'];
    
                if (strtoupper($strFieldType) == strtoupper('d_image'))
                {
					$strValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];
					if (strlen($strValue) > 0)
					{
						$strValue = revertSecuredValue($strValue, 'imageid', false);
					}
                    $arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue;
                }
                else if (strtoupper($strFieldType) == strtoupper('d_list'))
                {
					if (!array_key_exists('p_source', $arrField))
					{
						$arrField['p_source'] = '';
					}
					else
					{
						$strSource = $arrField['p_source'];	// at the moment our source is a | delimitered list with the entity being the first element
						if (strlen($strSource) > 0)
						{
							$arrSource = explode("|", $strSource);
							$strSource = $arrSource[0];
						
							$strValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];
							if (strlen($strValue) > 0)
							{
								$strValue = revertSecuredValue($strValue, 'id', false);
							}
							$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue;
						}
					}
                }
                else if (strtoupper($strFieldType) == strtoupper('d_document'))
                {
					$arrValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];	// iterate through and decode each
					if (is_array($arrValue))
					{
						for ($intI = 0; $intI < count($arrValue); ++$intI) 
						{
							$strValue = $arrValue[$intI]['documentid'];
							if (strlen($strValue) > 0)
							{
								$strValue = revertSecuredValue($strValue, 'id', false);
								
							}
							$arrJSON_a[$keySection]['fields'][$keyField]['p_value'][$intI]['documentid'] = $strValue;
						}
					}
                }
                else if (strtoupper($strFieldType) == strtoupper('d_multilist'))
                {
					if (!array_key_exists('p_source', $arrField))
					{
						$arrField['p_source'] = '';
					}
					else
					{
						$strSource = $arrField['p_source'];	// at the moment our source is a | delimitered list with the entity being the first element
						if (strlen($strSource) > 0)
						{
							$arrSource = explode("|", $strSource);
							$strSource = $arrSource[0];
						
							$arrValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];	// iterate through and decode each
							if (is_array($arrValue))
							{
								for ($intI = 0; $intI < count($arrValue); ++$intI) 
								{
									$strValue = $arrValue[$intI];
									if (strlen($strValue) > 0)
									{
										$strValue = revertSecuredValue($strValue, 'id', false);
									}
									$arrJSON_a[$keySection]['fields'][$keyField]['p_value'][$intI] = $strValue;
								}
							}
						}
					}
                }
            }
        }
    }
    
    return $arrJSON_a;
}

//  secure all id's from JSONData
function formSecureIDs($arrJSON_a)
{
	$strEntityType = 'ENTITY_';
	
    if (($arrJSON_a != null) && is_array($arrJSON_a))
    {
        foreach ($arrJSON_a as $keySection => $arrSection) {
        
            $arrFields = $arrSection['fields'];
    
            foreach ($arrFields as $keyField => $arrField) {
                $strFieldType = $arrField['p_datatype'];
    
                if (strtoupper($strFieldType) == strtoupper('d_image'))
                {
					$strValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];
					$strValue = secureEntityValue('DOCUMENT', $strValue);
                    $arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue;
                }
                else if (strtoupper($strFieldType) == strtoupper('d_list'))
                {
					if (!array_key_exists('p_source', $arrField))
					{
						$arrField['p_source'] = '';
					}
					else
					{
						$strSource = $arrField['p_source'];
						if (strlen($strSource) > 0)
						{
							$arrSource = explode("|", $strSource);
							$strSource = $arrSource[0];
						
							$strValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];
							if (strlen($strValue) > 0)
							{
								$strValue = secureValue($strEntityType . ffel($strSource), $strValue);
							}
							$arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $strValue;
						}
					}
                }
                else if (strtoupper($strFieldType) == strtoupper('d_document'))
                {
					$arrValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];	// iterate through and encode each
					if (is_array($arrValue))
					{
						for ($intI = 0; $intI < count($arrValue); ++$intI) 
						{
							$strValue = $arrValue[$intI]['documentid'];
							$strValue = secureEntityValue('DOCUMENT', $strValue);
							$arrJSON_a[$keySection]['fields'][$keyField]['p_value'][$intI]['documentid'] = $strValue;
						}
					}
                }
                else if (strtoupper($strFieldType) == strtoupper('d_multilist'))
                {
					if (!array_key_exists('p_source', $arrField))
					{
						$arrField['p_source'] = '';
					}
					else
					{
						$strSource = $arrField['p_source'];
						if (strlen($strSource) > 0)
						{
							$arrSource = explode("|", $strSource);
							$strSource = $arrSource[0];
						
							$arrValue = $arrJSON_a[$keySection]['fields'][$keyField]['p_value'];	// iterate through and encode each
							if (is_array($arrValue))
							{
								for ($intI = 0; $intI < count($arrValue); ++$intI) 
								{
									$strValue = $arrValue[$intI];
									if (strlen($strValue) > 0)
									{
										$strValue = secureValue($strEntityType . ffel($strSource), $strValue);
									}
									$arrJSON_a[$keySection]['fields'][$keyField]['p_value'][$intI] = $strValue;
								}
							}
						}
					}
                }
            }
        }    
    }

    return $arrJSON_a;
}

function massageDatatypeDate($str_a)
{
    //strtotime can't convert dates in / separator. 
    //https://stackoverflow.com/questions/2891937/strtotime-doesnt-work-with-dd-mm-yyyy-format
        
    $strDate = str_replace('/', '-', $str_a);
    
	$strResult = date("Y-m-d", strtotime($strDate));
    
	return $strResult;
}

function massageDatatypeTime($str_a)
{
	$strResult = date("H:i:s", strtotime($str_a));
	
	return $strResult;
}

//  update images id's from JSONData
function formUpdateImageIDs($arrJSON_a, $arrImages_a)
{
    if (($arrJSON_a != null) && is_array($arrJSON_a))
    {
        foreach ($arrJSON_a as $keySection => $arrSection) {
        
            $arrFields = $arrSection['fields'];
    
            foreach ($arrFields as $keyField => $arrField) {
                $strFieldType = $arrField['p_datatype'];
                $strValue = $arrField['p_value'];
    
                if (strtoupper($strFieldType) == strtoupper('d_image'))
                {
                    foreach ($arrImages_a as $arrImage) {
                        if ($arrImage['imagepath'] == $strValue)
                        {
                            //update value of images from path to document id 
                            $arrJSON_a[$keySection]['fields'][$keyField]['p_value'] = $arrImage['document_id'];    
                        }      
                    }
                }
            }
        }    
    }

    return $arrJSON_a;
}

function formDatatypeValue($arrField_a)
{
	$strResult = "";

	try
	{
		if (is_array($arrField_a))
		{
			$strResult = $arrField_a['p_value'];
		}
	}
	catch (Exception $e)
	{
		$strResult = "";
	}
	
	return $strResult;
}


// report functions

function reportFieldDataTypeGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	return formFieldDataTypeGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
}

function reportFieldGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	return formFieldGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
}

function reportValueGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a)
{
	return formValueGetBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a);
}

function reportValueUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a)
{
	return formValueUpdateBySectionCodeFieldCode($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strValue_a);
}

function reportFieldDataTypeSetDataType($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strDataType_a, $strSelection_a, $strClasses_a, $intLines_a)
{
	return formFieldDataTypeSetDataType($arrJSON_a, $strSectionCode_a, $strFieldCode_a, $strDataType_a, $strSelection_a, $strClasses_a, $intLines_a);
}

