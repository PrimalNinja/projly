<?php

// function summary:

// postMessage($objConn_a, $strDeviceID_a, $strFromClientID_a, $strFromUserID_a, $strToClientID_a, $strToUserID_a, $strSubject_a, $strMessage_a, $strIsSystem_a)
// prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a, $strMessageHTML_a, $intPriority_a)
// sendDataShareAccept($objConn_a, $strDataShareID_a)
// sendDataShareCancellation($objConn_a, $strDataShareID_a)
// sendDataShareDecline($objConn_a, $strDataShareID_a)
// sendDataShareRequest($objConn_a, $strDataShareID_a)
// sendFailureMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a)
// sendMail($strFromDomain_a, $strFrom_a, $strFromName_a, $arrRecipients_a, $strSubject_a, $strMessage_a)
// shortMessage($strMessage_a) 
// sendMessageMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strTemplateClientID_a, $strTemplateCode_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a)
// sendMessageTemplatedMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strTemplateClientID_a, $strTemplateCode_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a, $blnSendInternalMessage_a)
// sendMessageNonTemplatedMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strSubject_a, $strBody_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a, $blnSendInternalMessage_a)
// sendOwnerMessage($objConn_a, $strEmail_a, $strName_a, $strSubject_a, $strMessage_a)
// sendPushNotification($strServerKey_a, $strDeviceToken_a, $strMessage_a) 
// sendRegistrationMessageInit($objConn_a, $strClientCode_a, $strEmailAddress_a, $strPassword_a, $strToken_a)
// sendRegistrationConfirmationMessage($objConn_a, $strClientCode_a, $strEmailAddress_a, $strPassword_a)
// sendSMS($strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a)
// stripTags($str_a)

function postMessage($objConn_a, $strDeviceID_a, $strFromClientID_a, $strFromUserID_a, $strToClientID_a, $strToUserID_a, $strSubject_a, $strMessage_a, $strIsSystem_a)
{
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);

	$strResult = '';
	
	if (dependencies('esb/esbBroadcast'))
	{
        $strLogin = $_SESSION['server_loggedin_user'];

		dbBeginTrans($objConn_a, __FUNCTION__);

		// create the message in the sender's outbox
        
        if ($strFromClientID_a !== $strToClientID_a) 
		{ // do not do this if sending to self. so that it will not create duplicate.

            $strCode = "INTERNALMESSAGE";
            $strDescription = "Internal Message";

            $strEntityID = getEntityID($objConn_a, "systemform");
            $strDataEntityID = getEntityID($objConn_a, "internalmessage");
            $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "INTERNALMESSAGE");
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'SUBJECT', $strSubject_a);
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'MESSAGE', $strMessage_a);
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'SENTDATETIME', getDateTime());
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISSYSTEM', $strIsSystem_a);            
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISUNREAD', "Y");
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISARCHIVED', "N");
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISFLAGGED', "N");

            $strJSONData = json_encode($arrJSONData);

            $strSQL = "insert into ~INTERNALMESSAGE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fromclient_id, fromuser_id, toclient_id, touser_id) 
                        values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~FROMCLIENT_ID~', '~FROMUSER_ID~', '~TOCLIENT_ID~', '~TOUSER_ID~')";
			$strSQL = str_replace('~INTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strFromClientID_a), $strSQL);
			$strSQL = str_replace('~CODE~', $strCode, $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
			$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
            $strSQL = str_replace('~FROMCLIENT_ID~',ff($strFromClientID_a), $strSQL);
            $strSQL = str_replace('~FROMUSER_ID~',  ff($strFromUserID_a), $strSQL);
            $strSQL = str_replace('~TOCLIENT_ID~',  ff($strToClientID_a), $strSQL);
            $strSQL = str_replace('~TOUSER_ID~',    ff($strToUserID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			$strInternalMessageID = dbLastInsertID($objConn_a);
			
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTERNALMESSAGE', $strInternalMessageID, $strJSONData);
        }

        // create the message in the recipient's inbox
		$strCode = "INTERNALMESSAGE";
		$strDescription = "Internal Message";

		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "internalmessage");
		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "INTERNALMESSAGE");
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'SUBJECT', $strSubject_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'MESSAGE', $strMessage_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'SENTDATETIME', getDateTime());
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISSYSTEM', $strIsSystem_a);            
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISUNREAD', "Y");
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISARCHIVED', "N");
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISFLAGGED', "N");

		$strJSONData = json_encode($arrJSONData);

		$strSQL = "insert into ~INTERNALMESSAGE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, fromclient_id, fromuser_id, toclient_id, touser_id) 
					values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~FROMCLIENT_ID~', '~FROMUSER_ID~', '~TOCLIENT_ID~', '~TOUSER_ID~')";
		$strSQL = str_replace('~INTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strToClientID_a), $strSQL);
		$strSQL = str_replace('~CODE~', $strCode, $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
		$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~FROMCLIENT_ID~',ff($strFromClientID_a), $strSQL);
		$strSQL = str_replace('~FROMUSER_ID~',  ff($strFromUserID_a), $strSQL);
		$strSQL = str_replace('~TOCLIENT_ID~',  ff($strToClientID_a), $strSQL);
		$strSQL = str_replace('~TOUSER_ID~',    ff($strToUserID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strInternalMessageID = dbLastInsertID($objConn_a);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTERNALMESSAGE', $strInternalMessageID, $strJSONData);

		$strResult = $strInternalMessageID;
		            
		if (dbEndTrans($objConn_a, __FUNCTION__))
		{
			esbBroadcast($objConn_a, $strFromClientID_a, $strDeviceID_a, '', 'messages','messagecount', '', true, true);
		}
	}

	return $strResult;
}

// prepare message to 1 recipient, note: later we will handle to multiple recipients with merge data - even in this case it will create a single row for later batch file will process this data in
// priority order and expand the recipients (JSON) to individual recipient (string) records in a msg_tblsent table (or archive table). note will be able to support email and sms or other delivery types later
// 3 retries should be a constant
// sent can have N for not sent, E for error and Y for sent
function prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a, $strMessageHTML_a, $intPriority_a)
{
	$blnResult = false;
	
	if ((strlen($strFromDomain_a) > 0) && (strlen($strFrom_a) > 0) && (strlen($strFromName_a) > 0) && (strlen($strTo_a) > 0))
	{
		$arrRecipients = array();

		$arrRecipients[] = array(
			"recipient" => $strTo_a,
		);

		$strRecipients = json_encode($arrRecipients);

		// log the email so it can be later sent
		$strSQL = "insert into ~TABLENAMEMESSAGEPREPARED~ (fromdomain, sender, sendername, recipients, subject, message, messagehtml, priority, retries, sent, modifydatetime)
				  values ('~FROMDOMAIN~', '~SENDER~', '~SENDERNAME~', '~RECIPIENTS~', '~SUBJECT~', '~MESSAGE~', '~MESSAGEHTML~', ~PRIORITY~, 3, 'N', '~MODIFYDATETIME~')";

		$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
		$strSQL = str_replace('~FROMDOMAIN~', ff($strFromDomain_a), $strSQL);
		$strSQL = str_replace('~SENDER~', ff($strFrom_a), $strSQL);
		$strSQL = str_replace('~SENDERNAME~', ff($strFromName_a), $strSQL);
		$strSQL = str_replace('~RECIPIENTS~', ff($strRecipients), $strSQL);
		$strSQL = str_replace('~SUBJECT~', ff($strSubject_a), $strSQL);
		$strSQL = str_replace('~MESSAGE~', ff($strMessage_a), $strSQL);
		$strSQL = str_replace('~MESSAGEHTML~', ff($strMessageHTML_a), $strSQL);
		$strSQL = str_replace('~PRIORITY~', $intPriority_a, $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

		dbBeginTrans($objConn_a, __FUNCTION__);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $blnResult;
}

function sendDataShareAccept($objConn_a, $strDataShareID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameShare = getTableNameEntity("share", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strDeviceID = $_SESSION['server_deviceid'];
	
	$strSharedByClientID = '';
	$strSharedByUserID = '';
	$strSharedWithClientID = '';
	$strSharedWithUserID = '';

	// read the clientids and userids for messaging
	$strSQL = "select sharedbyclient_id, sharedwithclient_id from ~TABLENAMESHARE~ where id = ~DATASHAREID~";
	$strSQL = str_replace('~TABLENAMESHARE~', ff($strTableNameShare), $strSQL);
	$strSQL = str_replace('~DATASHAREID~', ff($strDataShareID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$strSharedByClientID = $arrRow['sharedbyclient_id'];
		$strSharedWithClientID = $arrRow['sharedwithclient_id'];
	}
	dbCloseRecordset($objResult);
	
	// shared by userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDBYCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDBYCLIENTID~", ff($strSharedByClientID), $strSQL);
	$strSharedByUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	// shared with userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDWITHCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDWITHCLIENTID~", ff($strSharedWithClientID), $strSQL);
	$strSharedWithUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedByClientID, $strSQL);
	$strSharedByClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedWithClientID, $strSQL);
	$strSharedWithClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// fetch system client and user
	$strSystemClientID = getSystemClientID($objConn_a);

	$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', $strSystemClientID, $strSQL);
	$strSQL = str_replace('~USERCODE~', SYSTEM_LOGIN, $strSQL);
	$strSystemUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	// ... has accepted your request to share with them
	postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedByClientID, $strSharedByUserID,
			$strSharedWithClientTitle . " has accepted your request to share with them.",
			$strSharedWithClientTitle . " has accepted your request to share with them.",
			"Y");
		
	// you have accepted the share request by ...
	//postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedWithClientID, $strSharedWithUserID,
			//"You have accepted the share request by " . $strSharedByClientTitle . ".",
			//"You have accepted the share request by " . $strSharedByClientTitle . ".",
			//"Y");
}

function sendDataShareCancellation($objConn_a, $strDataShareID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameShare = getTableNameEntity("share", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strDeviceID = $_SESSION['server_deviceid'];
	
	$strSharedByClientID = '';
	$strSharedByUserID = '';
	$strSharedWithClientID = '';
	$strSharedWithUserID = '';

	// read the clientids and userids for messaging
	$strSQL = "select sharedbyclient_id, sharedwithclient_id from ~TABLENAMESHARE~ where id = ~DATASHAREID~";
	$strSQL = str_replace('~TABLENAMESHARE~', ff($strTableNameShare), $strSQL);
	$strSQL = str_replace('~DATASHAREID~', ff($strDataShareID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$strSharedByClientID = $arrRow['sharedbyclient_id'];
		$strSharedWithClientID = $arrRow['sharedwithclient_id'];
	}
	dbCloseRecordset($objResult);
	
	// shared by userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDBYCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDBYCLIENTID~", ff($strSharedByClientID), $strSQL);
	$strSharedByUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	// shared with userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDWITHCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDWITHCLIENTID~", ff($strSharedWithClientID), $strSQL);
	$strSharedWithUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedByClientID, $strSQL);
	$strSharedByClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedWithClientID, $strSQL);
	$strSharedWithClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// fetch system client and user
	$strSystemClientID = getSystemClientID($objConn_a);

	$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', $strSystemClientID, $strSQL);
	$strSQL = str_replace('~USERCODE~', SYSTEM_LOGIN, $strSQL);
	$strSystemUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	// you have cancelled your request to share with ...
	//postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedByClientID, $strSharedByUserID,
			//"You have cancelled your request to share with " . $strSharedWithClientTitle . ".",
			//"You have cancelled your request to share with " . $strSharedWithClientTitle . ".",
			//"Y");
		
	// ... has cancelled their request to share with you
	postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedWithClientID, $strSharedWithUserID,
			$strSharedByClientTitle . " has cancelled their share request with you.",
			$strSharedByClientTitle . " has cancelled their share request with you.",
			"Y");
}

function sendDataShareDecline($objConn_a, $strDataShareID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameShare = getTableNameEntity("share", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strDeviceID = $_SESSION['server_deviceid'];
	
	$strSharedByClientID = '';
	$strSharedByUserID = '';
	$strSharedWithClientID = '';
	$strSharedWithUserID = '';

	// read the clientids and userids for messaging
	$strSQL = "select sharedbyclient_id, sharedwithclient_id from ~TABLENAMESHARE~ where id = ~DATASHAREID~";
	$strSQL = str_replace('~TABLENAMESHARE~', ff($strTableNameShare), $strSQL);
	$strSQL = str_replace('~DATASHAREID~', ff($strDataShareID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$strSharedByClientID = $arrRow['sharedbyclient_id'];
		$strSharedWithClientID = $arrRow['sharedwithclient_id'];
	}
	dbCloseRecordset($objResult);

	// shared by userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDBYCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDBYCLIENTID~", ff($strSharedByClientID), $strSQL);
	$strSharedByUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	// shared with userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDWITHCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDWITHCLIENTID~", ff($strSharedWithClientID), $strSQL);
	$strSharedWithUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedByClientID, $strSQL);
	$strSharedByClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedWithClientID, $strSQL);
	$strSharedWithClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// fetch system client and user
	$strSystemClientID = getSystemClientID($objConn_a);

	$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', $strSystemClientID, $strSQL);
	$strSQL = str_replace('~USERCODE~', SYSTEM_LOGIN, $strSQL);
	$strSystemUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	// ... has declined your request to share with them
	postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedByClientID, $strSharedByUserID,
			$strSharedWithClientTitle . " has declined your request to share with them.",
			$strSharedWithClientTitle . " has declined your request to share with them.",
			"Y");
		
	// you have declined the share request by ...
	//postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedWithClientID, $strSharedWithUserID,
			//"You have declined the share request by " . $strSharedByClientTitle . ".",
			//"You have declined the share request by " . $strSharedByClientTitle . ".",
			//"Y");
}

function sendDataShareRequest($objConn_a, $strDataShareID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameShare = getTableNameEntity("share", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strDeviceID = $_SESSION['server_deviceid'];
	
	$strSharedByClientID = '';
	$strSharedByUserID = '';
	$strSharedWithClientID = '';
	$strSharedWithUserID = '';

	// read the clientids and userids for messaging
	$strSQL = "select sharedbyclient_id, sharedwithclient_id from ~TABLENAMESHARE~ where id = ~DATASHAREID~";
	$strSQL = str_replace('~TABLENAMESHARE~', ff($strTableNameShare), $strSQL);
	$strSQL = str_replace('~DATASHAREID~', ff($strDataShareID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$strSharedByClientID = $arrRow['sharedbyclient_id'];
		$strSharedWithClientID = $arrRow['sharedwithclient_id'];
	}
	dbCloseRecordset($objResult);
	
	// shared by userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDBYCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDBYCLIENTID~", ff($strSharedByClientID), $strSQL);
	$strSharedByUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	// shared with userids
	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~SHAREDWITHCLIENTID~";
	$strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace("~SHAREDWITHCLIENTID~", ff($strSharedWithClientID), $strSQL);
	$strSharedWithUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedByClientID, $strSQL);
	$strSharedByClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select concat(c.description, ' ', rt.description) returnvalue 
				from ~TABLENAMECLIENT~ c left join ~TABLENAMEACCOUNT~ a on c.id = a.client_id
				left join ~TABLENAMEREGISTRATIONTYPE~ rt on a.registrationtype_id = rt.id
				where a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~CLIENTID~', $strSharedWithClientID, $strSQL);
	$strSharedWithClientTitle = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// fetch system client and user
	$strSystemClientID = getSystemClientID($objConn_a);

	$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', $strSystemClientID, $strSQL);
	$strSQL = str_replace('~USERCODE~', SYSTEM_LOGIN, $strSQL);
	$strSystemUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	// you have requested to share with ...
	//postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedByClientID, $strSharedByUserID,
			//"You have requested to share with " . $strSharedWithClientTitle . ".",
			//"You have requested to share with " . $strSharedWithClientTitle . ".",
			//"Y");
		
	// ... has requested to share with you
	postMessage($objConn_a, $strDeviceID, $strSystemClientID, $strSystemUserID, $strSharedWithClientID, $strSharedWithUserID,
			$strSharedByClientTitle . " has requested to share with you.",
			$strSharedByClientTitle . " has requested to share with you.",
			"Y");
}

function sendFailureMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a)
{
    $blnResult = true;

    if (toBoolean(FAILURE_EMAILS_ENABLED)) 
	{
        $blnResult = prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a, $strMessage_a, 1);
    }

    return $blnResult;
}

function sendMail($strFromDomain_a, $strFrom_a, $strFromName_a, $arrRecipients_a, $strSubject_a, $strMessage_a)
{
    $blnResult = true;

	foreach ($arrRecipients_a as $objRecipient)
	{
		$strTo = $objRecipient['recipient'];
		$strToName = $strTo;
		
		if (toBoolean(USE_PHP_MAIL)) 
		{
			$strHeader = 'MIME-Version: 1.0' . "\r\n";
			$strHeader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$strHeader .= 'From: ' . $strFromDomain_a . ' <' . $strFrom_a . '>' . "\r\n";

			$blnX = mail($strTo, $strSubject_a, $strMessage_a, $strHeader);
			if ($blnX == false)
			{
				$blnResult = false;
			}
		} 
		else if (toBoolean(USE_SENDGRID)) 
		{
			$strJSON = '{
							"personalizations":[
							{
								"to":[
								{
									"email":"' . $strTo . '",
									"name":"' . $strToName . '"
								}],
								"subject":"' . $strSubject_a . '"
							 }],
							"content": [
							{
								"type": "text/html", 
								"value": "' . $strMessage_a . '"
							}],
							"from":
							{
								"email":"' . $strFrom_a . '",
								"name":"' . $strFromName_a .'"
							},
							"reply_to":
							{
								"email":"' . $strFrom_a . '",
								"name":"' . $strFromName_a . '"
							}
						}';

			$arrHeader = array(
				'Content-type: application/json',
				'authorization: Bearer ' . SENDGRIDAPIKEY,
				'Content-Length: ' . strlen($strJSON)
			);

			$objCurl = curl_init();
			curl_setopt($objCurl, CURLOPT_URL, SENDGRIDAPIENTRY);
			//curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($objCurl, CURLOPT_HTTPHEADER, $arrHeader);
			curl_setopt($objCurl, CURLOPT_POST, 1);
			curl_setopt($objCurl, CURLOPT_POSTFIELDS, $strJSON);
			//curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
			$blnResponse = curl_exec ($objCurl);
			$arrCurlInfo = curl_getinfo($objCurl);
			$strHTTPCode = $arrCurlInfo['http_code'];
			curl_close ($objCurl);
			
			if ($blnResponse)
			{
			    if ($strHTTPCode != '202')
			    {
			        $blnResult = false;
			    }
			}
			else
			{
			    //echo('failed'); die();
				$blnResult = false;
			}
		} 
		else 
		{
			if (dependencies('3p/PhpMailer/PHPMailerAutoload')) 
			{
				$objMail = new PHPMailer;

				$objMail->isSMTP(); // Set mailer to use SMTP
				$objMail->Host = PHPMAILER_HOSTS;
				$objMail->SMTPAuth = toBoolean(PHPMAILER_AUTH);
				$objMail->Username = PHPMAILER_LOGIN;
				$objMail->Password = PHPMAILER_PASSWORD;
				$objMail->SMTPSecure = PHPMAILER_SECURITY;

				$objMail->From = $strFrom_a;
				$objMail->FromName = $strFromName_a;
				$objMail->addAddress($strTo); // Name is optional
				//$objMail->addReplyTo('info@example.com', 'Information');
				//$objMail->addCC('cc@example.com');
				//$objMail->addBCC('bcc@example.com');

				//$objMail->WordWrap = 50;                                     // Set word wrap to 50 characters
				//$objMail->addAttachment('/var/tmp/file.tar.gz');             // Add attachments
				//$objMail->addAttachment('/tmp/image.jpg', 'new.jpg');        // Optional name
				$objMail->isHTML(true); // Set email format to HTML

				$objMail->Subject = $strSubject_a;
				$objMail->Body = $strMessage_a;
				//$objMail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				if (!$objMail->send()) 
				{
					$blnResult = false;
					$strError = $objMail->ErrorInfo;                
					logError(false, $strError);
					//throw new Exception($strError);
				}
			}
		}
	}

    return $blnResult;
}

function shortMessage($strMessage_a) 
{
	$strMessage = $strMessage_a;
	return substr($strMessage, 0, intval(MSG_SHORTMESSAGE_LENGTH, 10)) . (strlen($strMessage) > intval(MSG_SHORTMESSAGE_LENGTH, 10) ? '...' : '');
}

// 	same as sendMessageTemplateMail. this one uses entity message
function sendMessageMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strTemplateClientID_a, $strTemplateCode_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a)
{	
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameMessage = getTableNameEntity("message", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strSQL = "select id, ff3cf93a12_6df2_4983_a62b_45dd3b699609_code code, ff3cf93a12_6df2_4983_a62b_45dd3b699609_subject subject, is_enabled, ff3cf93a12_6df2_4983_a62b_45dd3b699609_body body from ~TABLENAMEMESSAGE~ a where client_id = ~CLIENTID~ and ff3cf93a12_6df2_4983_a62b_45dd3b699609_code = '~CODE~'";
	$strSQL = str_replace('~TABLENAMEMESSAGE~', ff($strTableNameMessage), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strTemplateClientID_a), $strSQL);
    $strSQL = str_replace('~CODE~', ff($strTemplateCode_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($arrRow = dbReadRecord($objResult)) 
	{
        $strSubject = $arrRow['subject'];
        $strMessage = stripTags($arrRow['body']);
        $strMessageHTML = $arrRow['body'];
		$blnActive = toBoolean($arrRow['is_enabled']);
        
		if ($blnActive)
		{
			// try replacing placeholders        
			foreach ($arrPlaceholders_a as $strPlaceholder => $strValue) 
			{ 
				if ($strPlaceholder == "EXPIRYDATE")
				{
					if (strlen($strValue) == 0)
					{
						$strValue = "TBA";
					}
				}
				$strMessage = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessage);
				$strMessageHTML = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessageHTML);
				$strSubject = str_replace('[' . $strPlaceholder . ']', $strValue, $strSubject);
			}
			
			if (strlen($strToClientID_a) > 0)
			{
				$strDeviceID = $_SESSION['server_deviceid'];
				
				$strToUserID = $strToUserID_a;
				if (strlen($strToUserID) == 0)
				{
					$strSQL = "select user_id as returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
					$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strToClientID_a), $strSQL);
					$strToUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				}
				
				$strBroadcastClientID = $strBroadcastClientID_a;
				$strBroadcastUserID = $strBroadcastUserID_a;
				
				if (strlen($strBroadcastClientID) == 0)
				{
					// fetch batch client and user
					$strBroadcastClientID = getBatchClientID($objConn_a);

					$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
					$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
					$strSQL = str_replace('~CLIENTID~', $strBroadcastClientID, $strSQL);
					$strSQL = str_replace('~USERCODE~', REPLYTO_SYSTEMOWNERUSER, $strSQL);
					$strBroadcastUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				}
					
				postMessage($objConn_a, $strDeviceID, $strBroadcastClientID, $strBroadcastUserID, $strToClientID_a, $strToUserID, $strSubject, $strMessage, "Y");
			}
			
			if (strlen($strTo_a) > 0)
			{
				$blnX = prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject, $strMessage, $strMessageHTML, 3);
			}
		}
    }
    
    dbCloseRecordset($objResult);
}

// JC TODO - currently only sent by batch mode, need to enhance it to send interactively and sometimes with messages from systemclient (not always batchclient)
// 			 current workaround, if broadcastclientid is '' then we use the batchclient
function sendMessageTemplatedMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strTemplateClientID_a, $strTemplateCode_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a, $blnSendInternalMessage_a)
{	
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameMessageTemplate = getTableNameEntity("messagetemplate", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strSQL = "select id, ff3cf93a11_6df2_4982_a62a_45dd3b699608_code code, ff3cf93a11_6df2_4982_a62a_45dd3b699608_subject subject, is_enabled, ff3cf93a11_6df2_4982_a62a_45dd3b699608_body body from ~TABLENAMEMESSAGETEMPLATE~ a where client_id = ~CLIENTID~ and ff3cf93a11_6df2_4982_a62a_45dd3b699608_code = '~CODE~'";
	$strSQL = str_replace('~TABLENAMEMESSAGETEMPLATE~', ff($strTableNameMessageTemplate), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strTemplateClientID_a), $strSQL);
    $strSQL = str_replace('~CODE~', ff($strTemplateCode_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($arrRow = dbReadRecord($objResult)) 
	{
        $strSubject = $arrRow['subject'];
        $strMessage = stripTags($arrRow['body']);
        $strMessageHTML = $arrRow['body'];
		$blnActive = toBoolean($arrRow['is_enabled']);

		if ($blnActive)
		{
			// try replacing placeholders        
			foreach ($arrPlaceholders_a as $strPlaceholder => $strValue) 
			{ 
				if ($strPlaceholder == "EXPIRYDATE")
				{
					if (strlen($strValue) == 0)
					{
						$strValue = "TBA";
					}
				}
				$strMessage = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessage);
				$strMessageHTML = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessageHTML);
				$strSubject = str_replace('[' . $strPlaceholder . ']', $strValue, $strSubject);
			}
            
            // strip the tags again after placing placeholder cuz some placeholders content html tags too.
            $strMessage = stripTags($strMessage);
			
			if (strlen($strToClientID_a) > 0)
			{
				$strDeviceID = $_SESSION['server_deviceid'];
				
				$strToUserID = $strToUserID_a;
				if (strlen($strToUserID) == 0)
				{
					$strSQL = "select user_id as returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
					$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strToClientID_a), $strSQL);
					$strToUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				}
                
                if ($blnSendInternalMessage_a)
                {
					$strBroadcastClientID = $strBroadcastClientID_a;
					$strBroadcastUserID = $strBroadcastUserID_a;
					
					if (strlen($strBroadcastClientID) == 0)
					{
						// fetch batch client and user
						$strBroadcastClientID = getBatchClientID($objConn_a);

						$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
						$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
						$strSQL = str_replace('~CLIENTID~', $strBroadcastClientID, $strSQL);
						$strSQL = str_replace('~USERCODE~', REPLYTO_SYSTEMOWNERUSER, $strSQL);
						$strBroadcastUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					}
						
					postMessage($objConn_a, $strDeviceID, $strBroadcastClientID, $strBroadcastUserID, $strToClientID_a, $strToUserID, $strSubject, $strMessage, "Y");
                }
			}

			if (strlen($strTo_a) > 0)
			{
				$blnX = prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject, $strMessage, $strMessageHTML, 3);
			}
		}
    }
    
    dbCloseRecordset($objResult);
}

function sendMessageNonTemplatedMail($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strToClientID_a, $strToUserID_a, $strSubject_a, $strBody_a, $arrPlaceholders_a, $strBroadcastClientID_a, $strBroadcastUserID_a, $blnSendInternalMessage_a)
{	
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
	$strSubject = $strSubject_a;
	$strMessage = stripTags($strBody_a);
	$strMessageHTML = $strBody_a;

	// try replacing placeholders        
	foreach ($arrPlaceholders_a as $strPlaceholder => $strValue) 
	{ 
		if ($strPlaceholder == "EXPIRYDATE")
		{
			if (strlen($strValue) == 0)
			{
				$strValue = "TBA";
			}
		}
		$strMessage = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessage);
		$strMessageHTML = str_replace('[' . $strPlaceholder . ']', $strValue, $strMessageHTML);
		$strSubject = str_replace('[' . $strPlaceholder . ']', $strValue, $strSubject);
	}
	
	// strip the tags again after placing placeholder cuz some placeholders content html tags too.
	$strMessage = stripTags($strMessage);
	
	if (strlen($strToClientID_a) > 0)
	{
		$strDeviceID = $_SESSION['server_deviceid'];
		
		$strToUserID = $strToUserID_a;
		if (strlen($strToUserID) == 0)
		{
			$strSQL = "select user_id as returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strToClientID_a), $strSQL);
			$strToUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
		
		if ($blnSendInternalMessage_a)
		{
			$strBroadcastClientID = $strBroadcastClientID_a;
			$strBroadcastUserID = $strBroadcastUserID_a;
			
			if (strlen($strBroadcastClientID) == 0)
			{
				// fetch batch client and user
				$strBroadcastClientID = getBatchClientID($objConn_a);

				$strSQL = "select id as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~USERCODE~'";
				$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
				$strSQL = str_replace('~CLIENTID~', $strBroadcastClientID, $strSQL);
				$strSQL = str_replace('~USERCODE~', REPLYTO_SYSTEMOWNERUSER, $strSQL);
				$strBroadcastUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			}
				
			postMessage($objConn_a, $strDeviceID, $strBroadcastClientID, $strBroadcastUserID, $strToClientID_a, $strToUserID, $strSubject, $strMessage, "Y");
		}
	}

	if (strlen($strTo_a) > 0)
	{
		$blnX = prepareMessage($objConn_a, $strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject, $strMessage, $strMessageHTML, 3);
	}
}

function sendOwnerMessage($objConn_a, $strEmail_a, $strName_a, $strSubject_a, $strMessage_a)
{
    $arrRecipients[] = array(
        'recipient' => GENERAL_EMAIL_ADDRESS
    );
    
    return sendMail(GENERAL_EMAIL_DOMAIN, $strEmail_a, $strName_a, $arrRecipients, $strSubject_a, $strMessage_a);
}

function sendPushNotification($strServerKey_a, $strDeviceToken_a, $strMessage_a) 
{
    $strURL = 'https://fcm.googleapis.com/fcm/send';
	
	$blnResult = true;
	$strMessage = stripTags($strMessage_a);

    $arrFields = array(
						'registration_ids' => array($strDeviceToken_a),
						'data' => array ("strMessage_a" => $strMessage)
					);
    $strFields = json_encode ($arrFields);

    $strHeader = array (
						'Authorization: key=' . $strServerKey_a,
						'Content-Type: application/json'
					);

    $objCurl = curl_init ();
    curl_setopt($objCurl, CURLOPT_URL, $strURL);
    curl_setopt($objCurl, CURLOPT_POST, true);
    curl_setopt($objCurl, CURLOPT_HTTPHEADER, $strHeader);
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($objCurl, CURLOPT_POSTFIELDS, $strFields);
    $strResponse = curl_exec($objCurl);
    curl_close($objCurl);

	if (strlen($strResponse) > 0)
	{
		$blnResult = false;
	}
	
    return $blnResult;
}

function sendRegistrationMessageInit($objConn_a, $strClientCode_a, $strEmailAddress_a, $strPassword_a, $strToken_a)
{
    $strTableNameRegistration = getTableNameEntity("registration", false);
    
    $blnResult = false;
    
    $strTemplateClientID = getSystemClientID($objConn_a);
    $strDateToday = getDateStringOut(getDateOnly());
    $strToken = $strToken_a;
          
    $strURL = CONFIRMATION_URL;
    $strURL = str_replace('~TOKEN~', $strToken, $strURL);
        
    if (strlen($strEmailAddress_a) > 0)
    {                    
        $strSQL = "select client_id, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountname accountname, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode clientcode, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress emailaddress from ~TABLENAMEREGISTRATION~ where ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress = '~EMAILADDRESS~'";
        $strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);
        $strSQL = str_replace('~EMAILADDRESS~', ff($strEmailAddress_a), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
        $strAccountName = "";
        $strClientID = "";
        $strClientCode = "";
        $strBusinessName = "";
        $strBusinessPhone = "";
        $strBusinessEmail = "";
        $strBusinessWebsite = "";
        
        if ($arrRow = dbReadRecord($objResult)) 
        {
            $strAccountName = $arrRow['accountname'];
            $strClientID = $arrRow['client_id'];
            $strClientCode = $arrRow['clientcode'];
        }
        
        dbCloseRecordset($objResult);
        
        if (dependencies('setting/settingGet')) 
		{
            // fetch
            $strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessNumber = settingGet($objConn_a, 'CORE', 'BNUM', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessPhone = settingGet($objConn_a, 'CORE', 'BPHONE', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessAddress = settingGet($objConn_a, 'CORE', 'BADDR', $strClientID, '', '', '', __FUNCTION__);
        }
        
        $arrPlaceholders = Array(
            'DATE' => $strDateToday,
            'APPLICATIONNAME' => APPNAME,
            'FULLNAME' => $strAccountName,
            'GIVENNAMES' => $strAccountName,
            'CLIENTCODE' => $strClientCode,
            'EMAILADDRESS' => $strEmailAddress_a,
            'BUSINESSNAME' => $strBusinessName,
            'BUSINESSPHONE' => $strBusinessPhone,
            'BUSINESSEMAILADDRESS' => $strBusinessEmail,
            'BUSINESSWEBSITE' => $strBusinessWebsite,
            'REGISTRATIONLINK' => $strURL
        );

        sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strEmailAddress_a, $strClientID, "", $strTemplateClientID, "REG_INIT", $arrPlaceholders, "", "", false);                    
        
        $blnResult = true;
    }           
    
    return $blnResult;
}

function sendRegistrationConfirmationMessage($objConn_a, $strClientCode_a, $strEmailAddress_a, $strPassword_a)
{
    $strTableNameRegistration = getTableNameEntity("registration", false);
    
    $blnResult = false;
            
    $strTemplateClientID = getSystemClientID($objConn_a);
    $strDateToday = getDateStringOut(getDateOnly());
            
    if (strlen($strEmailAddress_a) > 0)
    {                    
        $strSQL = "select client_id, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountname accountname, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode clientcode, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress emailaddress from ~TABLENAMEREGISTRATION~ where ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress = '~EMAILADDRESS~'";
        $strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);
        $strSQL = str_replace('~EMAILADDRESS~', ff($strEmailAddress_a), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
        $strAccountName = "";
        $strClientID = "";
        $strClientCode = "";
        $strBusinessName = "";
        $strBusinessPhone = "";
        $strBusinessEmail = "";
        $strBusinessWebsite = "";
        
        if ($arrRow = dbReadRecord($objResult)) 
        {
            $strAccountName = $arrRow['accountname'];
            $strClientID = $arrRow['client_id'];
            $strClientCode = $arrRow['clientcode'];
        }
        
        dbCloseRecordset($objResult);
        
        if (dependencies('setting/settingGet')) 
		{
            // fetch
            $strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strClientID, '', '', '', __FUNCTION__);
            //$strBusinessNumber = settingGet($objConn_a, 'CORE', 'BNUM', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessPhone = settingGet($objConn_a, 'CORE', 'BPHONE', $strClientID, '', '', '', __FUNCTION__);
            //$strBusinessAddress = settingGet($objConn_a, 'CORE', 'BADDR', $strClientID, '', '', '', __FUNCTION__);
        }
    
        $arrPlaceholders = Array(
            'DATE' => $strDateToday,
            'APPLICATIONNAME' => APPNAME,
            'FULLNAME' => $strAccountName,
            'GIVENNAMES' => $strAccountName,
            'CLIENTCODE' => $strClientCode,
            'EMAILADDRESS' => $strEmailAddress_a,
            'BUSINESSNAME' => $strBusinessName,
            'BUSINESSPHONE' => $strBusinessPhone,
            'BUSINESSEMAILADDRESS' => $strBusinessEmail,
            'BUSINESSWEBSITE' => $strBusinessWebsite,
        );
        
        sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strEmailAddress_a, $strClientID, "", $strTemplateClientID, "REG_CONF", $arrPlaceholders, "", "", false);                    
        
        $blnResult = true;
    }           
    
    return $blnResult;
}

function sendSMS($strFromDomain_a, $strFrom_a, $strFromName_a, $strTo_a, $strSubject_a, $strMessage_a)
{
}

function stripTags($str_a)
{
	$strResult = $str_a;
	
	$strResult = str_replace("<br>", "\n", $strResult);
	$strResult = str_replace("<br />", "\n", $strResult);
	$strResult = str_replace("&nbsp;", " ", $strResult);
	$strResult = str_replace("&ndash;", "-", $strResult);
	$strResult = str_replace("&amp;", "&", $strResult);
	$strResult = strip_tags($strResult);
	
	return $strResult;
}