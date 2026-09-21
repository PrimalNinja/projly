<?php

function getFormatter_printer($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );

	$strInfoBranch = trim(formDescriptionGetBySectionCodeFieldCode($arrJSONData, "INFO", "BRANCH"));
	$strSettingsDescription = trim(formValueGetBySectionCodeFieldCode($arrJSONData, "SETTINGS", "DESCRIPTION"));
	$strSettingsPrinterPurpose = trim(formDescriptionGetBySectionCodeFieldCode($arrJSONData, "SETTINGS", "PRINTERPURPOSE"));
	$strInfoIdentifier = trim(formValueGetBySectionCodeFieldCode($arrJSONData, "INFO", "IDENTIFIER"));
	
	$strFullDescription = "";
	if (strlen($strInfoBranch) > 0)
	{
		$strFullDescription .= $strInfoBranch;
		$strFullDescription .= " - ";
	}
	$strFullDescription .= $strSettingsPrinterPurpose;
	if (strlen($strFullDescription) > 0)
	{
		$strFullDescription .= " - ";
	}
	$strFullDescription .= $strSettingsDescription;
	if (strlen($strInfoIdentifier) > 0)
	{
		$strFullDescription .= " - ";
		$strFullDescription .= $strInfoIdentifier;
	}
	    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
		"fulldescription" => $strFullDescription,
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"is_public" => $arrRowUnformatted_a['is_public'],
		"branch" => $arrRowUnformatted_a['info_branch'],
		"description" => $arrRowUnformatted_a['settings_description'],
		"purpose" => $arrRowUnformatted_a['settings_printerpurpose'],
		"systemprinter" => $arrRowUnformatted_a['settings_systemprinter'],
		"printertype" => $arrRowUnformatted_a['settings_printertype'],
		"leftmargin" => $arrRowUnformatted_a['settings_leftmargin'],
		"topmargin" => $arrRowUnformatted_a['settings_topmargin'],
		"identifier" => $arrRowUnformatted_a['info_identifier'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
