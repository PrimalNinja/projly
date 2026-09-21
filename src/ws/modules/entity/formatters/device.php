<?php

function getFormatter_device($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a)
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);

    $strFormEntityCode = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );

    //$strDescription = "<strong>". $_SESSION['server_deviceid'] . "</strong> ---------". $arrRowUnformatted_a['description'];

    $strID = $_SESSION['server_deviceid'];
    $strTableNameDevice = getTableNameEntity("device", false);
    $strDescription = dbGetDescriptionFromID($objConn_a, $strTableNameDevice, $strID, __FUNCTION__);

    if ($arrRowUnformatted_a['description'] == $strDescription)
    {
        //$strDescription = "<strong>(YOU)</strong>&nbsp;" . $strDescription;
		$strDescription = '<div style="color:red"><strong>(YOU)&nbsp;' . $strDescription . '</strong></div>';
    }
    else
    {
        $strDescription = $arrRowUnformatted_a['description'];
    }

    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "user" => $arrRowUnformatted_a['fff26af3dd_710e_4510_8153_058ed948e34e_user'],
        "description" => $arrRowUnformatted_a['description'],
		"description2" => $strDescription,
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		"p4or" => $arrRowUnformatted_a['printing_p4or'],
		"pq4or" => $arrRowUnformatted_a['queueing_pq4or'],
		"printfromq" => $arrRowUnformatted_a['printing_printfromq'],

		// put custom fields here
		"isauthenticated" => $arrRowUnformatted_a['is_authenticated'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );

    return $arrResult;
}
