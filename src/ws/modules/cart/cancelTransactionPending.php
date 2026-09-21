<?php

// transaction statuses:
//	COMPLETE = Product Delivered
//	PEN-APP = Pending Processing of Application
//	PEN-PAY = Pending Confirmation of Payment
// 	PEN-PROCESS = Pending Processing (in processing, not yet known if PEN-APP or PEN-PAY)
//	CANCELLED = cancelled
//
//	move tbltransaction to tbltransactionhistory
//	move tbltransactionline to tbltransactionlinehistory
function cancelTransactionPending($objConn_a, $strClientID_a, $strTransactionPendingID_a)
{
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);

	$blnResult = true;
	
	$strLogin = $_SESSION['server_loggedin_user'];

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strStatusCode = 'CANCELLED';
	$strStatusDescription = 'Cancelled';
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTIONPENDING~ where id = ~TRANSACTIONPENDINGID~";
	$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
										
	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSCODE", $strStatusCode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSDESCRIPTION", $strStatusDescription);
	
	$strJSONData = json_encode($arrJSONData);
	
	// update transaction to paid
	$strSQL = "update ~TABLENAMETRANSACTIONPENDING~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~TRANSACTIONPENDINGID~";
	$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);	
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONPENDING', $strTransactionPendingID_a, $strJSONData);

/*	
	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "transactionhistory");
	
	// move the transaction to transaction history
	$strSQL = "
insert into ~TABLENAMETRANSACTIONHISTORY~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
document_id, 
progress, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate,
g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber,
paymentmethod_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod,
paymentstatus_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus,
g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_statuscode,
g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription) 
select 
client_id, ~ENTITYID~, ~DATAENTITYID~, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
document_id, 
progress, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate,
g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber,
paymentmethod_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod,
paymentstatus_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus,
g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst,
sg31f68ccc_e168_40b8_a7db_693353aa7aea_statuscode,
g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription
from ~TABLENAMETRANSACTIONPENDING~ where id = ~TRANSACTIONPENDINGID~
";
	$strSQL = str_replace('~TABLENAMETRANSACTIONHISTORY~', ff($strTableNameTransactionHistory), $strSQL);
	$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);					
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strTransactionHistoryID = dbLastInsertID($objConn_a);
	
	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "transactionlinehistory");
	
	$strSQL = "
insert into ~TABLENAMETRANSACTIONLINEHISTORY~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
is_processed,
transactionhistory_id, 
applicantproduct_id, 
applicant_id, 
product_id, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_workqueuecode,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst) 
select 
client_id, ~ENTITYID~, ~DATAENTITYID~, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
is_processed,
~TRANSACTIONHISTORYID~, 
applicantproduct_id, 
applicant_id, 
product_id, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_workqueuecode,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst
from ~TABLENAMETRANSACTIONLINEPENDING~ where transactionpending_id = ~TRANSACTIONPENDINGID~
";
	$strSQL = str_replace('~TABLENAMETRANSACTIONLINEHISTORY~', ff($strTableNameTransactionLineHistory), $strSQL);
	$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
	$strSQL = str_replace('~TRANSACTIONHISTORYID~', ff($strTransactionHistoryID), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	$strSQL = "delete from ~TABLENAMETRANSACTIONLINEPENDING~ where transactionpending_id = ~TRANSACTIONPENDINGID~";
	$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	$strSQL = "delete from ~TABLENAMETRANSACTIONPENDING~ where id = ~TRANSACTIONPENDINGID~";
	$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
	$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
*/	
	$blnResult = dbEndTrans($objConn_a, __FUNCTION__);

	return $blnResult;
}