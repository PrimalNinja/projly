<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: formDataFetchDataForm, formDataFetchSystemForm - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can fetch their own or other clients systemform data
//
// PSEUDOCODE:
//		D: work out what table to fetch from
//		E: read the data to fetch
//		DIFFERENCE: read notes
//
function formDataFetchSystemForm($objConn_a, $strClientID_a, $strDataClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID_a, $strFormDataCode_a, $blnUseLatestForm_a, $strHistoryID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $strMode_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameFormEntityCode = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);

	$arrRow = array();
	$blnFormFetched = false;
	$blnMergeForm = false;
    $arrResult = array();
	$arrCurrentDataForm = array();
	$arrJSONData = array();
	$strDontGetFormVersions = "";
	$strModifyUser = "";
	$strModifyDateTime = "";
	$strEntityDataID = "";
    $strPreviousID = "";
    $strNextID = "";

	if (dependencies('entity/formDataVersionFetchSystemForm,entity/entitySystemFormFileJSON'))
	{
		$strFormDataCode = $strFormDataCode_a;
		$strFormDataID = $strFormDataID_a;
		
		// client bypass only can fetch by code
		$blnIgnoreClient = false;
		if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strFormEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = true;
		}
		
		if ($blnIgnoreClient == false)
		{
			if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strFormEntityCode_a) . ',') >= 0)
			{
				$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
			}
		}
		
		if ($blnIgnoreClient == false)
		{
			if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strFormEntityCode_a) . ',') >= 0)
			{
				$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
			}
		}
		
		$blnGetLatestFormVersion = true;
		if (InStr(',' . $strDontGetFormVersions . ',', ',' . ffeu($strFormEntityCode_a) . ',') >= 0)
		{
			$blnGetLatestFormVersion = false;
		}
			
		if ((strlen($strFormDataID) == 0) && (strlen($strFormDataCode) > 0))
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "select id returnvalue from ~TABLENAMEENTITYFORMENTITYCODE~ where code = '~CODE~'";
				$strSQL = str_replace('~TABLENAMEENTITYFORMENTITYCODE~', ff($strTableNameFormEntityCode), $strSQL);
				$strSQL = str_replace('~CODE~', ff($strFormDataCode), $strSQL);
				$strFormDataID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			}
		}
		
		$objResult = null;
		
		// if an entity id is provided then we have data to load
		if (strlen($strFormDataID) > 0)
		{
			$strSQL = '';
			
			// BEFORE SELECT
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
			{
				$strBeforeAfterCallFunc = 'beforeSelect_' . ffel($strFormEntityCode_a);        
				$strSQL = call_user_func($strBeforeAfterCallFunc, $objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a);
			}
				
			if (strlen($strSQL) == 0)
			{
				// fetch
				// E:
				if ($blnIgnoreClient)
				{
					$strSQL = "select id, entity_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYFORMENTITYCODE~ where id = ~FORMDATAID~";
					$strSQL = str_replace('~TABLENAMEENTITYFORMENTITYCODE~', ff($strTableNameFormEntityCode), $strSQL);
					$strSQL = str_replace('~FORMDATAID~', ff($strFormDataID), $strSQL);
				}
				else
				{
					$strSQL = "select id, entity_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYFORMENTITYCODE~ where client_id = ~CLIENTID~ and id = ~FORMDATAID~";
					$strSQL = str_replace('~TABLENAMEENTITYFORMENTITYCODE~', ff($strTableNameFormEntityCode), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
					$strSQL = str_replace('~FORMDATAID~', ff($strFormDataID), $strSQL);
				}
			}

			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

			if ($arrRow = dbReadRecord($objResult)) 
			{

				$arrCurrentDataForm = $arrRow;
				$strEntityDataID = $arrCurrentDataForm['id'];
				$arrJSONData = json_decode($arrCurrentDataForm['jsondata'], true);	// the formdata if there is data
				$strModifyUser = $arrCurrentDataForm['modifyuser'];
				$strModifyDateTime = $arrCurrentDataForm['modifydatetime'];
				$blnFormFetched = true;

				if ($blnUseLatestForm_a)
				{
					$blnMergeForm = true;
				}
			}
			// E:end
		}

		if (($blnFormFetched) && (!$blnMergeForm))
		{
			// we just want our form
		}
		else
		{
			// we want the latest and we might want to merge with it

			// DIFFERENCE: note, work out which client to fetch the form from, where no data is found then systemform which is always fetched from SYSTEM_CLIENT
			// return an empty form if no data is found
			// read empty form from SYSTEM_CLIENT

			// added this code to fixed the issue in getting the correct table name to fetch the latest form

			//fetch current latest formversion
			$strSQL = "select d.id returnvalue from ~TABLENAMEENTITYCODE~ d, ~TABLENAMEENTITY~ e where d.dataentity_id = e.id and e.code = '~FORMENTITYCODE~' ";
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
			$strSQL = str_replace('~FORMENTITYCODE~',  ffeu($strFormEntityCode_a), $strSQL);
			$strFormEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "select id, code, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYCODE~ where id = ~ENTITYID~";
			$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strFormEntityID), $strSQL);

			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

			if ($arrRow = dbReadRecord($objResult))
			{
				$arrLatestForm = $arrRow;
				$arrLatestFormJSON = array();
				
				if (toBoolean(FETCHSYSTEMFORMSFROMFILE))
				{
					$arrLatestFormJSON = json_decode(entitySystemFormFileJSON($arrLatestForm['code']), true);
				}
				else
				{
					$arrLatestFormJSON = json_decode($arrLatestForm['jsondata'], true);
				}
				//$strModifyUser = $arrLatestForm['modifyuser'];
				//$strModifyDateTime = $arrLatestForm['modifydatetime'];

				if ($blnFormFetched == false)
				{
					$arrJSONData = $arrLatestFormJSON; // empty form
				}
				else
				{
					if ($blnMergeForm && $blnGetLatestFormVersion)
					{
						//compare
						$intLatestFormVersion = intval(formValueGetBySectionTypeFieldCode($arrLatestFormJSON, 'FORMHEADER', 'FORMVERSION'));
						$intCurrentFormVersion = intval(formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'FORMVERSION'));

//logDebug('xx:' . $intCurrentFormVersion . ', ' . print_r($arrJSONData, true), '');
//logDebug('yy:' . $intLatestFormVersion . ', ' . print_r($arrLatestFormJSON, true), '');
						if ($intLatestFormVersion > $intCurrentFormVersion)
						{
							$arrJSONData = formTransferAllValuesToNewForm($arrJSONData, $arrLatestFormJSON); // merged form
						}
					}
				}
			}
			// DIFFERENCE:end
		}

		if ((strlen($strClientID_a) > 0) && (strlen($strEntityDataID) > 0))	// note that only clients can have history items
		{
			//get history id from previous / next id
			$arrFormDataVersionResult = formDataVersionFetchSystemForm($objConn_a, $strEntityCode_a, $strDataClientID_a, $strEntityDataID, $strFormEntityCode_a, $strHistoryID_a);

			if (strlen($arrFormDataVersionResult['previd']) > 0)
			{
				$strPreviousID = secureValue("historyid", $arrFormDataVersionResult['previd']);
			}

			if (strlen($arrFormDataVersionResult['nextid']) > 0)
			{
				$strNextID = secureValue("historyid", $arrFormDataVersionResult['nextid']);
			}

			//if $strHistoryID_a is provided then history data will be load
			if (strlen($strHistoryID_a) > 0)
			{
				$arrJSONData =  $arrFormDataVersionResult['jsondata'];
				$strModifyUser = $arrFormDataVersionResult['modifyuser'];
				$strModifyDateTime = $arrFormDataVersionResult['modifydatetime'];
			}
		}

		// BEFORE DISPLAYNEW
		if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
		{ 
			$strBeforeAfterCallFunc = 'beforeDisplayAddUpdate_' . ffel($strFormEntityCode_a);        
			$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strDataClientID_a, $arrJSONData, $strFormDataID_a, $strRelativeID_a, $strRelative_a, true, $strMode_a);
		}

		$arrResult[] = array(
			"id" => secureValue("ENTITY_" . ffel($strFormEntityCode_a), $strEntityDataID),
			"jsondata" => formSecureIDs($arrJSONData),
			"modifyuser" => $strModifyUser,
			"modifydatetime" => $strModifyDateTime,
			"previd" => $strPreviousID,
			"nextid" => $strNextID
		);

		dbCloseRecordset($objResult);
	}

	// logging
    if (dependencies('utils/log')) 
	{
		createAuditEntityViewDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID_a);
	}

    return $arrResult;
}

