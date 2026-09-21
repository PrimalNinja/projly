<?php

function actionCartUpdateProgress($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	
	// permissions
	if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
	
    // parameters
	
    $strProgress = getJSONParameter($arrParameters_a, 'progress');
	$blnUpdatePayer = toBoolean(getJSONParameter($arrParameters_a, 'updatepayer'));
	if ($blnUpdatePayer)
	{
		$strPayerName = getJSONParameter($arrParameters_a, 'payername');
		$strPayerAddressLine1 = getJSONParameter($arrParameters_a, 'payeraddressline1');
		$strPayerAddressLine2 = getJSONParameter($arrParameters_a, 'payeraddressline2');
		$strPayerSuburb = getJSONParameter($arrParameters_a, 'payersuburb');
		$strPayerState = getJSONParameter($arrParameters_a, 'payerstate');
		$strPayerPostcode = getJSONParameter($arrParameters_a, 'payerpostcode');
		$strPayerNotes = getJSONParameter($arrParameters_a, 'payernotes');
	}

	// initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    
	dbBeginTrans($objConn_a, __FUNCTION__);
		
	$strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strTransactionID) > 0)
	{
		if ($blnUpdatePayer)
		{
			$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			$arrJSONData = json_decode($strJSONData, true);
			
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERNAME", $strPayerName);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERADDRESSLINE1", $strPayerAddressLine1);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERADDRESSLINE2", $strPayerAddressLine2);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERSUBURB", $strPayerSuburb);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERSTATE", $strPayerState);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERPOSTCODE", $strPayerPostcode);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERNOTES", $strPayerNotes);
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "update ~TABLENAMETRANSACTION~ set progress = '~PROGRESS~', jsondata = '~JSONDATA~', document_id = null where client_id = ~CLIENTID~ and id = ~TRANSACTIONID~";
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		}
		else
		{
			$strSQL = "update ~TABLENAMETRANSACTION~ set progress = '~PROGRESS~' where client_id = ~CLIENTID~ and id = ~TRANSACTIONID~";
		}
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ffn($strClientID), $strSQL);
		$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
		$strSQL = str_replace('~PROGRESS~', ff($strProgress), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		if ($blnUpdatePayer)
		{
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
		}
    }
    
    $arrResult = ['transactionid' => secureEntityValue('TRANSACTION', $strTransactionID)];
		
	if (dbEndTrans($objConn_a, __FUNCTION__)) 
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	} 
	else 
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error updating transaction.', array());
	}
            
    return $strResult;
}
