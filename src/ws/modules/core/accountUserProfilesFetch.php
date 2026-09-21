<?php

// fetch a user profile
function accountUserProfilesFetch($objConn_a, $strClientID_a, $strUserID_a)
{
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);
	
     $arrResult = array();

    if (dependencies('system/generic'))
    {

    $strSQL =
"
select a.profile_id profile_id, b.description, b.is_admin, b.is_default, b.is_defined
from ~TABLENAMEUSERPROFILE~ a, ~TABLENAMEPROFILE~ b
where a.user_id = ~USERID~ and a.profile_id = b.id
";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult))
    {
        $arrResult[] = array(
            "id" => secureEntityValue('PROFILE', $arrRow['profile_id']),
            "description" => $arrRow['description']
        );
    }
    dbCloseRecordset($objResult);
    }

    return $arrResult;
}

?>