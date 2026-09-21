<?php

// add a sales person code
function salesPersonCodeLog($objConn_a, $strClientID_a, $arrRegistrationFormJSON_a)
{
	$strTableNameClient = getTableNameEntity("client", false);	
	$strTableNameSalesPersonCode = getTableNameEntity("salespersoncode", false);	
	$strTableNameSalesPersonCodeLog = getTableNameEntity("salespersoncodelog", false);	

	$blnResult = false;

	$strSalesPersonCode = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "SALESPERSONCODE");

	if (strlen($strSalesPersonCode) > 0)
	{
		dbBeginTrans($objConn_a, __FUNCTION__);

		$strClientID = getSystemOwnerClientID($objConn_a);
		$strLogin = $_SESSION['server_loggedin_user'];

		// get the phone number owner id (for validation) and description
		$strSQL = "select id returnvalue from ~TABLENAMESALESPERSONCODE~ where code = '~SALESPERSONCODE~'";
		$strSQL = str_replace('~TABLENAMESALESPERSONCODE~', ff($strTableNameSalesPersonCode), $strSQL);
		$strSQL = str_replace('~SALESPERSONCODE~', ff($strSalesPersonCode), $strSQL);
		$strSalesPersonCodeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strException = "";
		if (strlen($strSalesPersonCodeID) > 0)
		{
			$strSQL = "select code returnvalue from ~TABLENAMESALESPERSONCODE~ where code = '~SALESPERSONCODE~'";
			$strSQL = str_replace('~TABLENAMESALESPERSONCODE~', ff($strTableNameSalesPersonCode), $strSQL);
			$strSQL = str_replace('~SALESPERSONCODE~', ff($strSalesPersonCode), $strSQL);
			$strSalesPersonCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
		else
		{
			$strException = $strSalesPersonCode;
			$strSalesPersonCodeID = "";
			$strSalesPersonCode = "";
		}

		$strClientDescription = dbGetDescriptionFromID($objConn_a, $strTableNameClient, $strClientID_a, __FUNCTION__);

		// add to sales person code log entry
		$strCode = "SALESPERSONCODELOG";
		$strDescription = "Sales Person Code Log";

		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SALESPERSONCODELOG");
		
		// get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "salespersoncode");

		$strDate = getDateOnly();
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g8f690085-6d2f-4296-a16a-ff1d1d43b0bb", "DATE", $strDate);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "g8f690085-6d2f-4296-a16a-ff1d1d43b0bb", "SALESPERSONCODE", $strSalesPersonCodeID, $strSalesPersonCode);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g8f690085-6d2f-4296-a16a-ff1d1d43b0bb", "EXCEPTION", $strException);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "g8f690085-6d2f-4296-a16a-ff1d1d43b0bb", "SOLDTOCLIENT", $strClientID_a, $strClientDescription);
		$strJSONData = json_encode($arrJSONData);
		
		$strSQL =
			"
	insert into ~TABLENAMESALESPERSONCODELOG~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime)
	values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~')
	";
		$strSQL = str_replace('~TABLENAMESALESPERSONCODELOG~', ff($strTableNameSalesPersonCodeLog), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);	// this record is for the system owner
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
		$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);	// but it is still from the registered client
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strSalesPersonCodeLogID = dbLastInsertID($objConn_a);

		exposeEntityData($objConn_a, 'SYSTEMFORM', 'SALESPERSONCODELOG', $strSalesPersonCodeLogID, $strJSONData);
		
		return dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $blnResult;
}
