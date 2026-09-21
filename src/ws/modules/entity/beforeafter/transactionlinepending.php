<?php

// $strFormDataID_a = TRANSACTIONLINEPENDING
// $strRelativeID_a = TRANSACTIONPENDING

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
function beforeDisplayAddUpdate_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
    
	$arrJSONData = $arrJSONData_a;
	
    $strTransactionID = "";
    
    if (strlen($strRelativeID_a) > 0)
    {
        $strTransactionID = $strRelativeID_a;
    }    
    else //fetched transaction id
    {
        $strSQL = "select transactionpending_id returnvalue from ~TABLENAMETRANSACTIONLINEPENDING~ where id = ~ID~";
        $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
        $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
        $strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
    }
                    
    $strSecuredTransactionID = secureEntityValue('TRANSACTIONPENDING', $strTransactionID);

	// we suspect to get around CSP that rejects onclick, we have renamed onclick to clickevent, and we rename it back within the formrenderer

	$strActionData = "{ entity:'systemform', formentity:'TRANSACTIONPENDING', formcode:'TRANSACTIONPENDING', mode:'edit', title:'Transaction Pending', id:'" . $strSecuredTransactionID . "' }";
	$strCommand = "os().showForm('entity.frmForm', " . $strActionData . ");";
	$strHTML = '<div><button style="color:red; font-weight:bold;" clickevent="' . $strCommand . '">Click here to see the Transaction and Shipping Address</button></div><br /><br />';
	
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "TRANSACTIONURL", $strHTML);  
      
	return $arrJSONData;
}

function beforeAddUpdate_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{    
	
    $strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
    $strTableNameTransactionStatus = getTableNameEntity("transactionstatus", false);

    $arrJSONData = $arrJSONData_a;
	    
    if ($blnUpdate_a) 
    {     
        $strSQL = "select id returnvalue from ~TABLENAMETRANSACTIONSTATUS~ where code = 'CANCELLED'";    
            $strSQL = str_replace('~TABLENAMETRANSACTIONSTATUS~', ff($strTableNameTransactionStatus), $strSQL);
            $strTransactionStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 

        $strSQL = "select transactionstatus_id returnvalue from ~TABLENAMETRANSACTIONLINEPENDING~ where id = ~TRANSACTIONLINEPENDINGID~";
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
            $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strFormDataID_a), $strSQL);

            $strTransactionStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            if ($strTransactionStatusID === $strTransactionStatusCancelledID)
            {
                dbRaiseCustomError($objConn_a, "You cannot uncancel this order.");
            }
    }

	return $arrJSONData;
}

function afterAddUpdate_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    $strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
    $strTableNameTransactionStatus = getTableNameEntity("transactionstatus", false);
    $strTableNameSKUVariantItemBooking = getTableNameEntity("skuvariantitembooking", false);
    
	$arrJSONData = $arrJSONData_a;
       
    $strLogin = $_SESSION['server_loggedin_user'];
	$strUserID = $_SESSION['server_loggedin_userid'];
    
    if ($blnUpdate_a)
    {        
        if (dependencies('cart/updateTransactionStatus') && dependencies('sku/skuVariantItemBookingCancelByTransactionLine')) 
        {   

            $strSQL = "select transactionpending_id returnvalue from ~TABLENAMETRANSACTIONLINEPENDING~ where id = ~TRANSACTIONLINEPENDINGID~";
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
            $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strFormDataID_a), $strSQL);
            $strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
            $strSQL = "select transactionstatus_id returnvalue from ~TABLENAMETRANSACTIONLINEPENDING~ where id = ~TRANSACTIONLINEPENDINGID~";
            $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
            $strSQL = str_replace('~TRANSACTIONLINEPENDINGID~', ff($strFormDataID_a), $strSQL);

            $strTransactionStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
            $strSQL = "select id returnvalue from ~TABLENAMETRANSACTIONSTATUS~ where code = 'CANCELLED'";    
            $strSQL = str_replace('~TABLENAMETRANSACTIONSTATUS~', ff($strTableNameTransactionStatus), $strSQL);
            $strTransactionStatusCancelledID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
            if ($strTransactionStatusID === $strTransactionStatusCancelledID)
            {
                skuVariantItemBookingCancelByTransactionLine($objConn_a, $strFormDataID_a);
            }

            updateTransactionStatus($objConn_a, $strTransactionID, false);
        }
    }
		
	return $arrJSONData;
}

function beforeDelete_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_transactionlinepending($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_transactionlinepending($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
