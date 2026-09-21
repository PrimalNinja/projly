<?php

// $strFormDataID_a = TRANSACTIONPENDING

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (hasPermission($objConn_a, 'REFUND', __FUNCTION__, false))
	{
		$arrJSONData = formShowFieldsBySectionCodeFieldCodes($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", ["REFUNDDETAILS","REFUNDGRANTED","REFUNDAMOUNT","REFUNDNOTES"]);
	}
	else
	{
		$arrJSONData = formHideFieldsBySectionCodeFieldCodes($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", ["REFUNDDETAILS","REFUNDGRANTED","REFUNDAMOUNT","REFUNDNOTES"]);
	}

	return $arrJSONData;
}

function beforeAddUpdate_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    
	$arrJSONData = $arrJSONData_a;
	
	$strUserID = $_SESSION['server_loggedin_userid'];

    if ($blnUpdate_a)
    {
		$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";    
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strPaymentStatusPaidID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

		$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code in ('COMPLIMENTARY','FREE')";    
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strPaymentStatusFreeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

		$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PEN-PAY'";    
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strPaymentStatusPendingID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

		$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'CANCELLED'";    
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strPaymentStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYMENTSTATUS");
		$strPaymentStatusID = $arrJSONField['p_value'];

		// transaction statuses:
		//	COMPLETE = Product Delivered
		//	PEN-APP = Pending Processing of Application
		//	PEN-PAY = Pending Confirmation of Payment
		// 	PEN-PROCESS = Pending Processing (in processing, not yet known if PEN-APP or PEN-PAY)
		//	CANCELLED = cancelled

		if (($strPaymentStatusID == $strPaymentStatusPaidID) || ($strPaymentStatusID == $strPaymentStatusFreeID))
		{ 
			$strStatusCode = 'PEN-PROCESS';	// this will trigger the appropriate status change
			$strStatusDescription = 'Pending Processing';
		
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSCODE", $strStatusCode);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSDESCRIPTION", $strStatusDescription);

			if (dependencies('cart/commitTransactionPending'))
			{    
				commitTransactionPending($objConn_a, $strClientID_a, $strUserID, $strFormDataID_a);
			}
		}
    }
            
	return $arrJSONData;
}

function afterAddUpdate_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    
	$arrJSONData = $arrJSONData_a;
    
    if ($blnUpdate_a)
    {
        if (dependencies('cart/generateReceipt'))
        {
			// clear document_id and receipt number
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');
			//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "RECEIPTNUMBER", '');
			
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "update ~TABLENAMETRANSACTIONPENDING~ set jsondata = '~JSONDATA~', document_id = null where id= ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strFormDataID_a), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONPENDING', $strFormDataID_a, $strJSONData);
			
            generateReceipt($objConn_a, $strClientID_a, "", $strFormDataID_a, false);
        }
    }
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    
	$arrJSONData = $arrJSONData_a;        

	$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'CANCELLED'";    
	$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
	$strPaymentStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYMENTSTATUS");
	$strPaymentStatusID = $arrJSONField['p_value'];

	if ($strPaymentStatusID == $strPaymentStatusCancelledID)
	{
		$strStatusCode = 'CANCELLED';
		$strStatusDescription = 'Cancelled';
	
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSCODE", $strStatusCode);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSDESCRIPTION", $strStatusDescription);
		
		if (dependencies('cart/cancelTransactionPending'))
		{    
			cancelTransactionPending($objConn_a, $strClientID_a, $strFormDataID_a);
		}
	}
	
	return $arrJSONData;
}

function beforeDelete_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_transactionpending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_transactionpending($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
