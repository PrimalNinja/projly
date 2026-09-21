<?php

function getFormatter_devicelog($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a)
{
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
        "clientcode" => $arrRowUnformatted_a['clientcode'],
        "login" => $arrRowUnformatted_a['login'],
		"ipaddress" => $arrRowUnformatted_a['ipaddress'],
		"notes" => $arrRowUnformatted_a['notes'],
        "modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );

    return $arrResult;
}
