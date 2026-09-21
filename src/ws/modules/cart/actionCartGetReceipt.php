<?php

function actionCartGetReceipt($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $strTableNameTransaction = getTableNameEntity("transaction", false);
    
    $arrResult = array();
	
    // permissions
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

	// parameters

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
	$strUserID = $_SESSION['server_loggedin_userid'];

    if (dependencies('cart/generateReceipt,cart/updateTransactionLinePrices,cart/updateTransactionPrices'))
    {
        
		updateTransactionLinePrices($objConn_a, $strClientID);
		updateTransactionPrices($objConn_a, $strClientID);
		
		$strDocumentID = '';
		
        $strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        $arrGenerateReceiptResult = generateReceipt($objConn_a, $strClientID, $strUserID, $strTransactionID, true);
        
        $strDocumentID = $arrGenerateReceiptResult['document_id'];
        
    }
    
    if (strlen($strDocumentID) > 0) 
	{ 
		$arrResult = array("document_id"=>secureEntityValue('DOCUMENT', $strDocumentID), "download"=>false);
        $strResult = createJSONResponse($strDataID_a, RESPONSE_DOCUMENT, 'Receipt successfully created.', $arrResult);
    }
    else 
	{ 
        $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error in generating receipt.', array());
    }
    
    return $strResult;
}