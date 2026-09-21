<?php

// fetch a user
function actionSecurityUserFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserStatus = getTableNameEntity("userstatus", false);

    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'VW_USER', __FUNCTION__, true)) {return false;}

    // parameters
    $strUserID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
	
    // fetch
    $strSQL =
        "
select u.id id, u.code code, c.description client, u.userstatus_id, s.description statusdescription, u.login login, u.description description, u.email_address emailaddress, u.is_enabled enabled
from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u left join ~TABLENAMEUSERSTATUS~ s on u.userstatus_id = s.id
where c.id = u.client_id and u.id = ~USERID~
";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSERSTATUS~', ff($strTableNameUserStatus), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult)) {
        $arrResult[] = array(
            "id" => secureEntityValue('USER', $arrRow['id']),
            "login" => $arrRow['login'],
            "userstatus_id" => secureEntityValue('USERSTATUS', $arrRow['userstatus_id']),
            "statusdescription" => $arrRow['statusdescription'],
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
            "emailaddress" => $arrRow['emailaddress'],
            //"address1" => $arrRow['address1'],
            //"address2" => $arrRow['address2'],
            //"suburb" => $arrRow['suburb'],
            //"state" => $arrRow['state'],
            //"postcode" => $arrRow['postcode'],
            //"country" => $arrRow['country'],
            //"phone" => $arrRow['phone'],
            "enabled" => $arrRow['enabled'],
        );
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
