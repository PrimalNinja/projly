<?php

// fetch active registration types
function actionCoreRegistrationTypesFetchActive($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'VW_REGISTRATIONTYPE', __FUNCTION__, true)) {return false;}

    // fetch
    $strSQL = "select id, code, description, ff4909db1e_8bee_4c2b_be10_6d0853789635_longdescription longdescription, 
                ff4909db1e_8bee_4c2b_be10_6d0853789635_req_addresslines req_addresslines, ff4909db1e_8bee_4c2b_be10_6d0853789635_is_addressline1mandatory is_addressline1mandatory, is_enabled enabled, ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer is_employer, ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual is_individual 
				from ~TABLENAMEREGISTRATIONTYPE~ 
				where 
				is_enabled = 'Y' and 
				((ff4909db1e_8bee_4c2b_be10_6d0853789635_applications = '') or 
					(ff4909db1e_8bee_4c2b_be10_6d0853789635_applications is null) or 
					(ff4909db1e_8bee_4c2b_be10_6d0853789635_applications like '%~APPCODE~%')) 
				order by cast(ff4909db1e_8bee_4c2b_be10_6d0853789635_displayorder as unsigned)";
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);		
	$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
	
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureEntityValue('REGISTRATIONTYPE', $arrRow['id']),
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
			"longdescription" => $arrRow['longdescription'],
            "enabled" => $arrRow['enabled'],
            "is_employer" => $arrRow['is_employer'],
            "is_individual" => $arrRow['is_individual'],
            "request_addresslines" => $arrRow['req_addresslines'],
            "is_addressline1mandatory" => $arrRow['is_addressline1mandatory']
        );
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
 