<?php

// list computers
function actionCoreDataAuditLogsList($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();
	
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $arrFilter = getJSONParameter($arrParameters_a, 'filter');
    $arrOrder = getJSONParameter($arrParameters_a, 'order');

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
	$arrFields['client'] = 'c.description';
	$arrFields['user'] = 'u.description';
    $arrFields['category'] = 'l.category';
    $arrFields['description'] = 'l.description';
    $arrFields['modifyuser'] = 'l.modifyuser';
    $arrFields['modifydatetime'] = 'l.modifydatetime';

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

    // get table count
    $strSQL =
    "
select count(*) returnvalue
from ~TABLENAMEDATACHANGE~ l
left join ~TABLENAMECLIENT~ c on (l.client_id = c.id) 
left join ~TABLENAMEUSER~ u on (l.user_id = u.id)

" . dbBuildWhere('where', $arrFields, $arrFilter);

	$strSQL = str_replace('~TABLENAMEDATACHANGE~', CORE_DATACHANGE, $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // fetch
    $strSQL =
    "
select l.id, c.description client, u.description user, l.category, l.description, l.modifyuser, l.modifydatetime
from ~TABLENAMEDATACHANGE~ l
left join ~TABLENAMECLIENT~ c on (l.client_id = c.id) 
left join ~TABLENAMEUSER~ u on (l.user_id = u.id)

" . dbBuildWhere('where', $arrFields, $arrFilter) . dbBuildOrderBy($arrFields, $arrOrder) . " limit ~OFFSET~,~LIMIT~"; // . NONAJAXGRIDLIMIT;

	$strSQL = str_replace('~TABLENAMEDATACHANGE~', CORE_DATACHANGE, $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~OFFSET~', $intOffset, $strSQL);
    $strSQL = str_replace('~LIMIT~', $intLimit, $strSQL);


    $intRowNum = 1;

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    while ($arrRow = dbReadRecord($objResult)) 
	{
        $arrResult[] = array(
            "id" => secureValue("DATACHANGE", $arrRow['id']),
            "rownum" => $intRowNum,
			"client" => $arrRow['client'],
			"user" => $arrRow['user'],
            "category" => $arrRow['category'],
            "description" => $arrRow['description'],
            "modifyuser" => $arrRow['modifyuser'],
            "modifydatetime" => $arrRow['modifydatetime'],
            "recordcount" => $intRecordCount,
            "limited" => $intLimit //NONAJAXGRIDLIMIT
        );
        $intRowNum++;
    }
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
