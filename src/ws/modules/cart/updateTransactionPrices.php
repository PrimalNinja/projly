<?php

function updateTransactionPrices($objConn_a, $strClientID_a) 
{ 
    $strTableNameTransaction = getTableNameEntity("transaction", false);
    $strTableNameTransactionLine = getTableNameEntity("transactionline", false);
    
    $strSQL = "
select t.id transaction_id, t.jsondata, sum(tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst) priceexgst, sum(tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst) priceincgst
from ~TABLENAMETRANSACTION~ t, ~TABLENAMETRANSACTIONLINE~ tl
where t.client_id = ~CLIENTID~ and tl.transaction_id = t.id
";
    $strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
    $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    
	$strLoopSQL = $strSQL;
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    while ($arrRow = dbReadRecord($objResult)) 
    {
        $strTransactionID = $arrRow['transaction_id'];
        $strPriceExGST = $arrRow['priceexgst'];
        $strPriceIncGST = $arrRow['priceincgst'];
        $strJSONData = $arrRow['jsondata'];
		
		if (strlen($strTransactionID) == 0)
		{
			logError(false, "JC ERROR:" . $strLoopSQL);
		}

        $arrJSONData = json_decode($strJSONData, true);

		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PRICEEXGST", $strPriceExGST);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PRICEINCGST", $strPriceIncGST);
		
		$strJSONData = json_encode($arrJSONData);
		
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~' where id = ~TRANSACTIONID~";
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
		$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
		
		dbEndTrans($objConn_a, __FUNCTION__);
    }
    
    dbCloseRecordset($objResult);
    
    return true;
}
