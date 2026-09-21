<?php

function actionMessageInboxList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) {
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder  = getJSONParameter($arrParameters_a, 'order');

    // paging
    $intOffset = (int) getJSONParameter($arrParameters_a, 'offset');

    // limit
    $intLimit = (int) getJSONParameter($arrParameters_a, 'limit');

    if ($intLimit == 0 || $intLimit > NONAJAXGRIDLIMIT ) 
	{
       $intLimit = NONAJAXGRIDLIMIT;
    }
    else if ($intLimit <= 0) 
	{
       $intLimit = 1; // make it one. 0 limit returns no record
    }


    $arrFields = array();
    $arrFields['fromclient'] = 'fc.description';
    $arrFields['fromuser'] = 'fu.description';
    $arrFields['toclient'] = 'tc.description';
    $arrFields['touser'] = 'tu.description';
    $arrFields['subject'] = 'm.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject';
    //$arrFields['message'] = 'm.message';
    $arrFields['sentdatetime'] = 'm.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_sentdatetime';
    $arrFields['is_unread']    = 'm.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread';
    $arrFields['is_flagged']   = 'm.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isflagged';

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];
	
    // get table count
    $strSQL =
    "
select count(*) returnvalue
from (
    select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject
    from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
    where m.touser_id = tu.id and m.toclient_id = tu.client_id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fu.client_id and m.fromclient_id = fc.id and m.touser_id = ~USERID~ and m.toclient_id = ~CLIENTID~ and m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isarchived = 'N' and m.client_id = ~CLIENTID~
" . dbBuildWhere('and', $arrFields, $arrFilter) . ") temp";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);

    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);


    // get unread count
    $strSQL =
    "
select count(*) returnvalue
from (
    select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject
    from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
    where m.touser_id = tu.id and m.toclient_id = tu.client_id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fu.client_id and m.fromclient_id = fc.id and m.touser_id = ~USERID~ and m.toclient_id = ~CLIENTID~ and m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isarchived = 'N' and m.client_id = ~CLIENTID~
    and m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread='Y') temp";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);

    $intUnreadCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL = "
    select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject, m.jsondata, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_sentdatetime sentdatetime, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread is_unread, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isarchived is_archived, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isflagged is_flagged
    from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
    where m.touser_id = tu.id and m.toclient_id = tu.client_id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fu.client_id and m.fromclient_id = fc.id and m.touser_id = ~USERID~ and m.toclient_id = ~CLIENTID~ and m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isarchived = 'N' and m.client_id = ~CLIENTID~
" . dbBuildWhere('and', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit ~OFFSET~,~LIMIT~"; // . NONAJAXGRIDLIMIT;
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    $strSQL = str_replace('~OFFSET~', $intOffset, $strSQL);
    $strSQL = str_replace('~LIMIT~', $intLimit, $strSQL);

    $intRowNum = 1;
    //print_r($strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrJSONData = json_decode($arrRow['jsondata'], true);
        $strMessage = formValueGetBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'MESSAGE');

        $arrResult[] = array(
            "id" => secureEntityValue('INTERNALMESSAGE', $arrRow['id']),
            "rownum" => $intRowNum,
            'sender_id' =>  secureEntityValue('USER', $arrRow['fromuser_id']),
            'fromclient' => $arrRow['from_clientname'],
            'fromuser' => $arrRow['from_username'],
            'recipient_id' =>  secureEntityValue('USER', $arrRow['touser_id']),
            'toclient' => $arrRow['to_clientname'],
            'touser' => $arrRow['to_username'],
            'subject' => $arrRow['subject'],
            'message' => shortMessage($strMessage),
            'isunread'=> $arrRow['is_unread'],
            'isarchived' => $arrRow['is_archived'],
            'is_flagged' => $arrRow['is_flagged'],
            'sentdatetime' => $arrRow['sentdatetime'],
            'unreadcount' => $intUnreadCount,
            "recordcount" => $intRecordCount,
            'limited' => $intLimit //NONAJAXGRIDLIMIT
        );

        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
