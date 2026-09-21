<?php

function actionCartItemsFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	        
    $strTableNameCart = getTableNameEntity("cart", false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) { return false; }
    
    // parameters

    // initialisations
	$strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);
    $strClientID = $_SESSION['server_loggedin_clientid'];
	
    $strSQL = "select id, code, g9e435c04_affc_4cd3_b32a_36e48af07078_title title, g9e435c04_affc_4cd3_b32a_36e48af07083_costprice costprice from ~TABLENAMECART~ where client_id = ~CLIENTID~";
    $strSQL = str_replace('~TABLENAMECART~', ff($strTableNameCart), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult))
    {
        $strCartID = $arrRow['id'];
		$arrResult[] = array(
			"id" => secureEntityValue('CART', $strCartID),
			"code" => $arrRow['code'],
			"description" => $arrRow['title'],
			"priceincgst" => $arrRow['costprice'],
			"priceexgst" => $arrRow['costprice'],
            "ingraceperiod" => 'N'
		);     
    }

	dbCloseRecordset($objResult); 

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
