<?php

// transaction statuses:
//	COMPLETE = Product Delivered
//	PEN-APP = Pending Processing of Application
//	PEN-PAY = Pending Confirmation of Payment
// 	PEN-PROCESS = Pending Processing (in processing, not yet known if PEN-APP or PEN-PAY)
//	CANCELLED = cancelled
//
// products with a workqueueitemtype get put into the pending table, but workqueue items are created
// products without a workqueueitemtype get processed immediately
//
//	blnPendingTransaction = false
//	for each tbltransactionline where applicantproduct_id = null
//		if the line needs a workqueue
//			createApplication
//			blnPendingTransaction = true
//		else
//			if client applicant then clientProductAdd
//			if person applicant then personProductAdd
//			set the applicantproduct_id of the tbltransactionline
//		endif
//	next
//
//	if blnPendingTransaction
//		do nothing?
//	else
//		move tbltransaction to tbltransactionhistory
//		move tbltransactionline to tbltransactionlinehistory
//	endif
//
//	if applicant is client then profilesInitialise
//	if applicant is employee then haspaidbefore = 'Y'
//
function commitTransactionPending($objConn_a, $strClientID_a, $strUserID_a, $strTransactionPendingID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameEmployee = getTableNameEntity("employee", false);
	$strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameWorkQueueItemType = getTableNameEntity("workqueueitemtype", false);
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
	$strTableNameWorkQueue = getTableNameEntity("workqueue", false);

	$blnResult = true;
	$blnTransactionPending = false;
	
	$strStatusCode = '';
	$strStatusDescription = '';

	if (dependencies('security/clientProductAdd,security/profilesInitialise'))
	{
		$strLogin = $_SESSION['server_loggedin_user'];

		dbBeginTrans($objConn_a, __FUNCTION__);

		// for each transaction line that is not already processed
		$strSQL = "select t.paymentstatus_id, tl.id transactionlineid, tl.product_id, tl.applicant_id, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype applicanttype from ~TABLENAMETRANSACTIONPENDING~ t, ~TABLENAMETRANSACTIONLINEPENDING~ tl where t.id = tl.transactionpending_id and t.id = ~TRANSACTIONPENDINGID~ and t.g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid = 'Y' and tl.is_processed = 'N'";
		$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
		$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
		$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID_a), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strTransactionLinePendingID = $arrRow['transactionlineid'];
			$strPaymentStatusID = $arrRow['paymentstatus_id'];
			$strProductID = $arrRow['product_id'];
			$strApplicantID = $arrRow['applicant_id'];
			$strApplicantType = $arrRow['applicanttype'];
			
			$strSQL = "select workqueueitemtype_id returnvalue from ~TABLENAMEPRODUCT~ where id = ~PRODUCTID~";
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
			$strWorkQueueItemTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			// if we have a workqueue item type for the product, create the work queue entries
			if (strlen($strWorkQueueItemTypeID) > 0)
			{
				// $strSQL = "select code returnvalue from ~TABLENAMEWORKQUEUEITEMTYPE~ where id = ~WORKQUEUEITEMTYPEID~";
				// $strSQL = str_replace('~TABLENAMEWORKQUEUEITEMTYPE~', ff($strTableNameWorkQueueItemType), $strSQL);
				// $strSQL = str_replace('~WORKQUEUEITEMTYPEID~', ff($strWorkQueueItemTypeID), $strSQL);
				// $strWorkQueueItemTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				// // we have to put this product through the work queue
				// $strWorkQueueID = createApplication($objConn_a, $strTransactionPendingID_a, $strTransactionLinePendingID, $strApplicantID, $strApplicantType, $strWorkQueueItemTypeCode);
				
				// $strSQL = "update ~TABLENAMETRANSACTIONLINEPENDING~ set is_processed = 'Y' where id = ~TRANSACTIONLINEPENDINGID~";
				// $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
				// $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strTransactionLinePendingID), $strSQL);
				// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				
				// // update the work queue id in the transaction line
				// $strSQL = "select workqueueitemheader_code returnvalue from ~TABLENAMEWORKQUEUE~ where id = ~WORKQUEUEID~";
				// $strSQL = str_replace('~TABLENAMEWORKQUEUE~', ff($strTableNameWorkQueue), $strSQL);
				// $strSQL = str_replace('~WORKQUEUEID~', ff($strWorkQueueID), $strSQL);
				// $strWorkQueueCode = dbReadValue($objConn_a, $strSQL , __FUNCTION__);

				// $strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTIONLINEPENDING~ where id = ~TRANSACTIONLINEPENDINGID~";
				// $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
				// $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strTransactionLinePendingID), $strSQL);
				// $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
													
				// $arrJSONData = json_decode($strJSONData, true);
				
				// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "WORKQUEUECODE", $strWorkQueueCode);
				
				// $strJSONData = json_encode($arrJSONData);
				
				// // update transaction line to paid
				// $strSQL = "update ~TABLENAMETRANSACTIONLINEPENDING~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~TRANSACTIONLINEPENDINGID~";
				// $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
				// $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strTransactionLinePendingID), $strSQL);
				// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);	
				// $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
				// $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
				// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
					
				// exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONLINEPENDING', $strTransactionLinePendingID, $strJSONData);
					
				// // flag the entire transacton as pending even though only this product is
				// $blnTransactionPending = true;
			}
			else
			{
				// install the products to clientproduct
				$strApplicantProductID = "";

				$strSQL = "select ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
				$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				$strAccountName = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				$strApplicantProductID = clientProductAdd($objConn_a, $strApplicantID, $strProductID, $strPaymentStatusID, 'Product obtained via product purchase from ' . $strAccountName . '.', "");
				
				if (strlen($strApplicantProductID) > 0)
				{
					$strSQL = "update ~TABLENAMETRANSACTIONLINEPENDING~ set applicantproduct_id = ~APPLICANTPRODUCTID~, is_processed = 'Y' where id = ~TRANSACTIONLINEPENDINGID~";
					$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
					$strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strTransactionLinePendingID), $strSQL);
					$strSQL = str_replace('~APPLICANTPRODUCTID~', ff($strApplicantProductID), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
			}
		
			if ($strApplicantType == 'CLIENT')
			{
				profilesInitialise($objConn_a, $strApplicantID);
			}
		}
		
		dbCloseRecordset($objResult);

		// update the status
		if ($blnTransactionPending)
		{
			$strStatusCode = 'PEN-APP';
			$strStatusDescription = 'Pending Processing of Application';
		}
		else
		{
			$strStatusCode = 'COMPLETE';
			$strStatusDescription = 'Product Delivered';
		}
		
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
		if ($blnTransactionPending)
		{
			// keep the transaction pending (do nothing)
		}
		else
		{
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
g31f68ccc_e168_40b8_a7db_693353aa7aea_statuscode,
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
		}
*/		
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);

		//dbCloseRecordset($objResultTransactionPending);
	}

	return $blnResult;
}