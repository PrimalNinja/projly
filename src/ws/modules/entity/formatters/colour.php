<?php

function getFormatter_colour($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
	// note: the default doesn't read from the jsondata as there may not be any
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
        "descriptionhtml" => populateSampleColour($objConn_a, $arrRowUnformatted_a),
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}

function populateSampleColour($objConn_a, $objRS_a)
{
	$strResult = "";
	
	if (dependencies('entity/dataaccess/colour'))
	{
		$strColourID = $objRS_a['id'];
		$strColour = $objRS_a['description'];
		
		if (strlen($strColourID) > 0)
		{
			// fetch the colour
			$strPrimary = fetchValue_colour($objConn_a, 'b1357cf9_db2f_4a3a_9094_1fd454026b43_primarycolour', $strColourID, [], "");
			$strSecondary = fetchValue_colour($objConn_a, 'b1357cf9_db2f_4a3a_9094_1fd454026b43_secondarycolour', $strColourID, [], "");
			$strResult .= '<span style="background-color: ' . $strSecondary . '; color: ' . $strPrimary . '; padding: 2px 10px 2px; border-radius: 50px; font-size: 9.5pt; text-align:center; text-decoration: none;">' . $strColour . '</span>';
		}
	}
	
	return $strResult;
}
