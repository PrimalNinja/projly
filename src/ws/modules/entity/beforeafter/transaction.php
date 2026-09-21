<?php

// $strFormDataID_a = TRANSACTION

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
function beforeDisplayAddUpdate_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (hasPermission($objConn_a, 'REFUND', __FUNCTION__, false))
	{
		//$arrJSONData = formShowFieldsBySectionCodeFieldCodes($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", ["REFUNDDETAILS","REFUNDGRANTED","REFUNDAMOUNT","REFUNDNOTES"]);
	}
	else
	{
		$arrJSONData = formHideFieldsBySectionCodeFieldCodes($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", ["REFUNDDETAILS","REFUNDGRANTED","REFUNDAMOUNT","REFUNDNOTES"]);
	}

	return $arrJSONData;
}

function beforeAddUpdate_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNameTransaction = getTableNameEntity("transaction", false);
    	    
	$arrJSONData = $arrJSONData_a;
    
    if ($blnUpdate_a)
    {
        if (dependencies('cart/generateReceipt'))
        {
			// clear document_id and receipt number

			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');
			//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "RECEIPTNUMBER", '');
			
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', document_id = null where id= ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strFormDataID_a), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strFormDataID_a, $strJSONData);
        
            generateReceipt($objConn_a, $strClientID_a, "", $strFormDataID_a, true);
        }
    }
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_transaction($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
	// NOTE: if only the system owner client still has that signature, then delete it completely.  As we cannot work out the signature now as the record is already deleted
	//		 this cleanup of employees within the systemowner client can only really be done as a batch job if required
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_transaction($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
