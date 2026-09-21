<?php

// clear the cart
function clearCart($objConn_a, $strClientID_a) 
{ 
	dbBeginTrans($objConn_a, __FUNCTION__);

	if (dependencies('entity/dataaccess/transaction') &&
		dependencies('entity/dataaccess/transactionline'))
	{
		$arrCustomWhere = [["n", "client_id", $strClientID_a]];
		$strTransactionID = fetchValue_transaction($objConn_a, "id", "", $arrCustomWhere);

		if (strlen($strTransactionID) > 0)
		{
			$arrCustomWhere = [["n", "transaction_id", $strTransactionID]];
			delete_transactionline($objConn_a, "", $arrCustomWhere);
			delete_transaction($objConn_a, $strTransactionID, []);
		}
	}
        
    return dbEndTrans($objConn_a, __FUNCTION__);
}
