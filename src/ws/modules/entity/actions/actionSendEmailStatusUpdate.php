<?php

function actionSendEmailStatusUpdate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    
    $strResult = "";
    
    // permission check
    if (!hasPermission($objConn_a, 'SEND_EMAILSTATUSUPDATE', __FUNCTION__, true)) {return false;}
    
    if (dependencies('cart/sendPurchaseLetter'))
    {
        $strTransactionID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
        //$strClientID = $_SESSION['server_loggedin_clientid'];
        
        $strClientID = "";
        $strUserID = "";

        $strSQL = "select client_id, user_id from ~TABLENAMETRANSACTIONPENDING~ where id = ~TRANSACTIONID~";
        $strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
        $strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult)) 
        {
            $strClientID = $arrRow['client_id'];
            $strUserID = $arrRow['user_id'];
        }	

        dbCloseRecordset($objResult);
        
        if (strlen($strClientID) > 0 && strlen($strUserID) > 0)
        {
            sendPurchaseLetter($objConn_a, $strClientID, $strUserID, $strTransactionID);
        
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK,  "Email Status Update Sent" , array());
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, "No transaction has been found", array());
        }
                
    }
    
    return $strResult;
}
