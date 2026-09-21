<?php
function actionCartClear($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

	if (dependencies('cart/clearCart'))
	{
		// permissions
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
		
		// parameters

		// initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];
		
		dbBeginTrans($objConn_a, __FUNCTION__);

		clearCart($objConn_a, $strClientID);
		
		if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
		}
		else { 
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error in cancelling transaction.', array());
		}
	}
        
    return $strResult;
}