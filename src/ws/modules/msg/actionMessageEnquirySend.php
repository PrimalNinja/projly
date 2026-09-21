<?php

function actionMessageEnquirySend($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{	    
    $strResult = "";
    $blnResult = false;

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
    
    $strSubject = getJSONParameter($arrParameters_a, 'typeofenquiry');
    $strName = getJSONParameter($arrParameters_a, 'name');
    $strEmail = getJSONParameter($arrParameters_a, 'email');
    $strComment = getJSONParameter($arrParameters_a, 'comment');
        
    // initialisations
    
    $strFrom = $strEmail;
    $strFromName = $strName;    
    $strMessage = $strComment;
    
    //$blnResult = sendOwnerMessage($objConn_a, $strEmail, $strName, $strSubject, $strComment);

    $blnResult = prepareMessage($objConn_a, GENERAL_EMAIL_DOMAIN, $strFrom, $strFromName, GENERAL_EMAIL_ADDRESS, $strSubject, $strMessage, $strMessage, 1);
    
    if ($blnResult) 
	{
        $strResult   = createJSONResponse($strDataID_a, RESPONSE_OK, '', $strResult);
    } 
	else 
	{
        $strResult   = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error sending message.', array());
    }
    
    
    return $strResult;
}    