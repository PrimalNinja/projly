<?php

// report creation

function reportNew()
{
	$arrResult = array();
	return $arrResult;
}

function reportSectionNew($arrReport_a, $strSectionType_a, $strSectionCode_a, $strSectionTitle_a, $strSectionCodeTemp_a)
{
	$arrResult = $arrReport_a;

	$strSection = '
		{  
		  "sectioncode":"' . $strSectionCode_a . '",
		  "sectioncodetemp":"' . $strSectionCodeTemp_a . '",
		  "sectiontitle":"' . $strSectionTitle_a . '",
		  "sectiontype":"' . $strSectionType_a . '",
		  "fields":[  
		  ]
	   }
	';
	
	$arrResult[] = json_decode($strSection, true);
	
	return $arrResult;
}

// utility for other datatypes
function reportFieldNew($arrReport_a, $strSectionCode_a, $strField_a)
{
	$arrResult = $arrReport_a;

    $blnFound = false;

    foreach ($arrResult as $keySection => $arrSection) {
        $strSectionCode = $arrSection['sectioncode'];
        $arrFields = $arrSection['fields'];
        
        if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
        {
			$arrResult[$keySection]['fields'][] = json_decode($strField_a, true);
			//array_push($arrResult[$keySection]['fields'], json_decode($strField_a, true));

			$blnFound = true;
			break;
        }
                
        if ($blnFound)
        {
            break;
        }
    }

	return $arrResult;
}

