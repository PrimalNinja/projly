<?php

function actionMessageFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a){ 
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);
        
    $arrResult = array();

	if (dependencies('esb/esbBroadcast'))
	{
		// permission check
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

		// parameters
		$strMessageID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
		
		// initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];
		$strDeviceID = $_SESSION['server_deviceid'];
		
		// fetch
		$strSQL = "
		select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject, m.jsondata, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_sentdatetime sentdatetime, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread is_unread, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isarchived is_archived, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isflagged is_flagged
		from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
		where m.touser_id = tu.id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fc.id and m.client_id = ~CLIENTID~ and m.id = ~MESSAGEID~";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);

		//print_r($strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		
		if ($arrRow = dbReadRecord($objResult)) 
        {
            $arrJSONData = json_decode($arrRow['jsondata'], true);
            $strMessage = formValueGetBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'MESSAGE');

    		$strMessage = str_replace("\r", "", $strMessage);
			$strMessage = str_replace("\n", "<br />", $strMessage);
			
			$arrResult[] = array(
				"id" => secureEntityValue('INTERNALMESSAGE', $arrRow['id']),
				'sender_id' =>  secureEntityValue('USER', $arrRow['fromuser_id']),
				'sender' => $arrRow['from_username'] . ' - ' . $arrRow['from_clientname'],
				'recipient_id' =>  secureEntityValue('USER', $arrRow['touser_id']),
				'recipient' => $arrRow['to_username'] . ' - ' . $arrRow['to_clientname'],
				'subject' => $arrRow['subject'],
				'message' => $strMessage,
				'isunread' => $arrRow['is_unread'],
                'isarchived' => $arrRow['is_archived'],
                'isflagged' => $arrRow['is_flagged'],
				'sentdatetime' => $arrRow['sentdatetime']
			);
		}
		dbCloseRecordset($objResult);

		// the below esb broadcast is because we have just read a message, we want to broadcast to all other of our logged in clients
		// we have done so, so that they can update their counters to reflect that we have read some messages
		esbBroadcast($objConn_a, $strClientID, $strDeviceID, '', 'messages','messagecount', '', true, true);
	}
	
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
