<?php

// $strFormDataID_a = TRANSACTIONLINE
// $strRelativeID_a = TRANSACTION

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
function beforeDisplayAddUpdate_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $strTableNameTransactionLine = getTableNameEntity("transactionline", false);
    
	$arrJSONData = $arrJSONData_a;
	
    $strTransactionID = "";
    
    if (strlen($strRelativeID_a) > 0)
    {
        $strTransactionID = $strRelativeID_a;
    }    
    else //fetched transaction id
    {
        $strSQL = "select transaction_id returnvalue from ~TABLENAMETRANSACTIONLINE~ where id = ~ID~";
        $strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
        $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
        $strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
    }
                    
    $strSecuredTransactionID = secureEntityValue('TRANSACTION', $strTransactionID);

	// we suspect to get around CSP that rejects onclick, we have renamed onclick to clickevent, and we rename it back within the formrenderer

	$strActionData = "{ entity:'systemform', formentity:'TRANSACTION', formcode:'TRANSACTION', mode:'edit', title:'Transaction', id:'" . $strSecuredTransactionID . "' }";
	$strCommand = "os().showForm('entity.frmForm', " . $strActionData . ");";
	$strHTML = '<div><button style="color:red; font-weight:bold;" clickevent="' . $strCommand . '">Click here to see the Transaction and Shipping Address</button></div><br /><br />';
	
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "TRANSACTIONURL", $strHTML);  
      
	return $arrJSONData;
}

function beforeAddUpdate_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{    
	$arrJSONData = $arrJSONData_a;
	      
	return $arrJSONData;
}

function afterAddUpdate_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    
	$arrJSONData = $arrJSONData_a;
           		
	return $arrJSONData;
}

function beforeDelete_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_transactionline($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_transactionline($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
