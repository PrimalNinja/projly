<?php

// send a message
function actionMessageSend($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameUser = getTableNameEntity("user", false);
    
    $strResult = "";

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $arrMessage = getJSONParameter($arrParameters_a, 'message');
	
    $strRecipientUserID = revertSecuredValue($arrMessage['recipient_id'], 'recipient_id', false);
	
    if (strlen($strRecipientUserID) > 0)
	{
        $strSubject = $arrMessage['subject'];
        $strMessage = $arrMessage['message'];

        $strSQL = 'select client_id returnvalue from ~TABLENAMEUSER~ where id = ~USERID~';
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strRecipientUserID), $strSQL);
        $strRecipientClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID   = $_SESSION['server_loggedin_userid'];
        $strDeviceID = $_SESSION['server_deviceid'];

        dbBeginTrans($objConn_a, __FUNCTION__);

        $strMessageID = postMessage($objConn_a, $strDeviceID, $strClientID, $strUserID, $strRecipientClientID, $strRecipientUserID, $strSubject, $strMessage, 'N');

        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
            $arrResult[] = array("id" => secureValue(MSG_MESSAGE, $strMessageID));
            $strResult   = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult   = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error sending message.', array());
        }
    
    }
    else 
	{ 
        $strResult   = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Recipient does not exists.', array());
    }
    
    return $strResult;
}

