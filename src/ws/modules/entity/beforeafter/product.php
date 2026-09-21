<?php

// $strFormDataID_a = PRODUCT

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
function beforeDisplayAddUpdate_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameProduct = getTableNameEntity("product", false);

	$arrJSONData = $arrJSONData_a;
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// bring out fields
	$strRequirements = formValueGetBySectionCodeFieldCode($arrJSONData, "gffe35a8ad-d290-4ae3-8800-f2935dd30d07", "REQUIREMENTS");
	$strFilter = formValueGetBySectionCodeFieldCode($arrJSONData, "gffe35a8ad-d290-4ae3-8800-f2935dd30d07", "FILTER");

	if (strlen($strRequirements) > 0)
	{
		$strRequirements = '~' . str_replace(',', '~,~', $strRequirements) . '~';
	}
	
	if (strlen($strFilter) > 0)
	{
		$strFilter = '~' . str_replace(',', '~,~', $strFilter) . '~';
	}
	
	$strSQL = "update ~TABLENAMEPRODUCT~ set requirements = '~REQUIREMENTS~', filter = '~FILTER~' where id = ~ID~";
	$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
	$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
	$strSQL = str_replace('~REQUIREMENTS~', ff($strRequirements), $strSQL);
	$strSQL = str_replace('~FILTER~', ff($strFilter), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_product($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_product($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameProduct = getTableNameEntity("product", false);

	// $strSQL = "select id, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_requirements from ~TABLENAMEPRODUCT~";
	// $strSQL = str_replace("~TABLENAMEPRODUCT~", ff($strTableNameProduct), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strProductID = $arrRowLoop['id'];
		// $strRequirements = $arrRowLoop['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_requirements'];

		// if (strlen($strRequirements) > 0)
		// {
			// $strRequirements = '~' . str_replace(',', '~,~', $strRequirements) . '~';
		// }
		
		// $strSQL = "update ~TABLENAMEPRODUCT~ set requirements = '~REQUIREMENTS~' where id = ~ID~";
		// $strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
		// $strSQL = str_replace('~ID~', ff($strProductID), $strSQL);
		// $strSQL = str_replace('~REQUIREMENTS~', ff($strRequirements), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
}
