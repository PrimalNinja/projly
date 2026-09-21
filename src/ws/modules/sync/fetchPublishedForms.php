<?php
function fetchedPublishedForms($objConn_a, $strClientID_a)
{
    $arrResult = [];

    $strClientID = $strClientID_a;

    // fetch
	$strSQL =
        "
    select p.id id, p.entity_id entity_id, p.client_id client_id, p.code code, p.description description, p.is_enabled is_enabled, p.jsondata jsondata, p.modifyuser modifyuser, p.modifydatetime modifydatetime
    from sync_tblpublishedform p
    where p.client_id = ~CLIENTID~
    ";

    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureValue(SYNC_PUBLISHED_FORMS, $arrRow['id']),
            "entity_id" => secureEntityValue('ENTITY', $arrRow['entity_id']),
            "client_id" => secureEntityValue('CLIENT', $arrRow['client_id']),
            "code" => $arrRow['code'],
            "description" => $arrRow['description'],
            "is_enabled" => $arrRow['is_enabled'],
            "jsondata" => formSecureIDs(json_decode($arrRow['jsondata'], true)),
            "modifyuser" => $arrRow['modifyuser'],
            "modifydatetime" => $arrRow['modifydatetime']
        );
    }
    dbCloseRecordset($objResult);

    return $arrResult;
}