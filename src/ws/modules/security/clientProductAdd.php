<?php

// add a client product
function clientProductAdd($objConn_a, $strClientID_a, $strProductID_a, $strPaymentStatusID_a, $strNotes_a, $strExpiryDate_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
	$strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
	$strTableNameProduct = getTableNameEntity("product", false);

    $strClientProductID = "";
	
	$strLogin = $_SESSION['server_loggedin_user'];

	$strProductID = '';
	$strDescription = '';
	$strProductType = '';
	$strPeriod = '';
	
	$strSQL = "select id, code, description, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_producttype producttype, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_period period from ~TABLENAMEPRODUCT~ where is_enabled = 'Y' and id = ~PRODUCTID~";
	$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
	$strSQL = str_replace('~PRODUCTID~', ff($strProductID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult)) 
	{
		$strProductID = $arrRow['id'];
		$strCode = $arrRow['code'];
		$strDescription = $arrRow['description'];
		$strProductType = $arrRow['producttype'];
		$strPeriod = $arrRow['period'];
	}
	dbCloseRecordset($objResult);
	
	if (strlen($strProductID) > 0)
	{
		dbBeginTrans($objConn_a, __FUNCTION__);

		// create a new instance of the product
		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "CLIENTPRODUCT");

		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "clientproduct");
		
		$strSQL = "select code returnvalue from ~TABLENAMEPAYMENTSTATUS~ where id = ~PAYMENTSTATUSID~";
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strSQL = str_replace('~PAYMENTSTATUSID~', ff($strPaymentStatusID_a), $strSQL);
		$strPaymentStatusCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strPaymentStatusDescription = dbGetDescriptionFromID($objConn_a, $strTableNamePaymentStatus, $strPaymentStatusID_a, __FUNCTION__);
		$strApplicantName = dbGetDescriptionFromID($objConn_a, $strTableNameClient, $strClientID_a, __FUNCTION__);

		$strPurchaseDate = getDateTime();
		
		$strPaymentDate = "";
		$strEnabled = "N";
		if (($strPaymentStatusCode == "COMPLIMENTARY") || ($strPaymentStatusCode == "FREE") || ($strPaymentStatusCode == "PAID"))
		{
			$strPaymentDate = getDateTime();
			$strEnabled = "Y";
		}
		
		$strExpiryDate = calculateExpiryDate($strPurchaseDate, $strPeriod);
		if (strlen($strExpiryDate_a) > 0)
		{
			$strExpiryDate = $strExpiryDate_a;
		}
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "APPLICANTNAME", $strApplicantName);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "PRODUCT", $strProductID, $strDescription);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "PAYMENTSTATUS", $strPaymentStatusID_a, $strPaymentStatusDescription);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "PURCHASEDATE", $strPurchaseDate);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "EXPIRYDATE", $strExpiryDate);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "PAYMENTDATE", $strPaymentDate);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "NOTES", $strNotes_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "ISENABLED", $strEnabled);
		
		$strJSONData = json_encode($arrJSONData);

		$strSQL = "select id returnvalue from ~TABLENAMECLIENTPRODUCT~ where client_id = ~CLIENTID~ and product_id = ~PRODUCTID~";
		$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
		$strClientProductID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		// fetch the existing product if it exists from the client, if so update it, otherwise add it
		if (strlen($strClientProductID) > 0)
		{
			$strSQL = "update ~TABLENAMECLIENTPRODUCT~ set is_enabled = 'Y', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~CLIENTPRODUCTID~";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			$strSQL = str_replace('~CLIENTPRODUCTID~', ff($strClientProductID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
		else
		{
			$strSQL =
				"
		insert into ~TABLENAMECLIENTPRODUCT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime)
		values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '', '~DESCRIPTION~', 'Y', ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')
		";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			//$strSQL = str_replace('~ID~', ff($strID_a), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			$strClientProductID = dbLastInsertID($objConn_a);
		}

		exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENTPRODUCT', $strClientProductID, $strJSONData);

		dbEndTrans($objConn_a, __FUNCTION__);
	}

    return $strClientProductID;
}
