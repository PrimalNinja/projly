<?php

function actionCartProductsRemove($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTableNameTransactionLine = getTableNameEntity("transactionline", false);

	$blnResult = true;
    $arrResult = array();

    // if (dependencies('docs/documentDelete'))
    // {
        // permissions
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        // parrameters
        //$strProductID = revertSecuredValue(getJSONParameter($arrParameters_a, 'product_id'), 'product_id', false);
        $strTransactionLineIDList = getParameterIDList($arrParameters_a, true);

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strLogin = $_SESSION['server_loggedin_user'];    


        if (strlen($strTransactionLineIDList) > 0) 
        {		
			dbBeginTrans($objConn_a, __FUNCTION__);

			$strSQL = "delete from ~TABLENAMETRANSACTIONLINE~ where client_id = ~CLIENTID~ and id in (~TRANSACTIONLINEIDLIST~)";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~TRANSACTIONLINEIDLIST~', ff($strTransactionLineIDList), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
        }    

		if ($blnResult)
		{
			// update transaction

			$strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "select count(*) returnvalue from ~TABLENAMETRANSACTIONLINE~ where transaction_id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$intRowCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			// check for ducument. remove document to create new one.
			// NOTE FROM JULIAN: we will not delete document file system. we will leave it for nightly jobs
			/*
			$strSQL = "select document_id returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);

			$strDocumentID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strDocumentID) > 0) {
				documentDelete($objConn_a, $strClientID, $strDocumentID);
			}
			*/

			dbBeginTrans($objConn_a, __FUNCTION__);

			$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			$arrJSONData = json_decode($strJSONData, true);

			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');

			$strJSONData = json_encode($arrJSONData);

			if (intval($intRowCount) === 0) { // if all products are deleted. reset to product selection process
			   $strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', document_id = null, progress='~PROGRESS~' where id= ~TRANSACTIONID~";
			   $strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			   $strSQL = str_replace('~PROGRESS~', "products", $strSQL);
			}
			else {
			   $strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', document_id = null where id= ~TRANSACTIONID~";
			   $strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			}

			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);

			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);

			if (dbEndTrans($objConn_a, __FUNCTION__))
			{
				$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
			}
			else
			{
				$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error removeing product to cart.', array());
			}
		}
    // }

    return $strResult;
}
