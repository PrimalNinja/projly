<?php

// function summary:

// formFieldNew($arrForm_a, $strSectionCode_a, $strField_a)
// formFieldNewText($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a)
// formFieldNewMultilineText($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a, $strSelection_a)
// formFieldNewList($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strClasses_a, $strSelection_a)
// formFieldNewYesNo($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a)
// formNew()
// formSectionNew($arrForm_a, $strSectionType_a, $strSectionCode_a, $strSectionTitle_a, $strSectionColour_a, $strSectionQuantity_a, $strSectionRef_a, $strSectionCodeTemp_a)

// utility for other datatypes
function formFieldNew($arrForm_a, $strSectionCode_a, $strField_a)
{
	$arrResult = $arrForm_a;

    foreach ($arrResult as $keySection => $arrSection) 
	{
        $strSectionCode = $arrSection['sectioncode'];
        
        if (strtoupper($strSectionCode) == strtoupper($strSectionCode_a))
        {
			$arrResult[$keySection]['fields'][] = json_decode($strField_a, true);
			//array_push($arrResult[$keySection]['fields'], json_decode($strField_a, true));

			break;
        }
    }

	return $arrResult;
}

// below are hardcoded datatype representations for form editor properties
function formFieldNewText($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a)
{	
	$arrField = array(
		'p_datatype' => 'd_text',
		'p_name' => $strFieldName_a,
		'p_label' => $strFieldLabel_a,
		'p_value' => $strFieldValue_a,
		'p_required' => 'N',
		'p_readonly' => $strReadOnly_a,
		'p_fieldvalidfrom' => $strValidFrom_a,
		'p_fieldvalidto' => $strValidTo_a,
		'p_info' => '',
		'p_length' => (string)$intFieldLength_a,
		'p_selection' => '',
		'p_lines' => '',
		'p_searchable' => 'N',
		'p_sortable' => 'N',
		'p_style' => '',
		'p_classes' => 'form-control',
		'p_colour' => $strColour_a
	);

	$strField = json_encode($arrField);
	
	return formFieldNew($arrForm_a, $strSectionCode_a, $strField);
}

function formFieldNewMultilineText($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a, $strSelection_a)
{	
	$arrField = array(
		'p_datatype' => 'd_multilinetext',
		'p_name' => $strFieldName_a,
		'p_label' => $strFieldLabel_a,
		'p_value' => $strFieldValue_a,
		'p_required' => 'N',
		'p_readonly' => $strReadOnly_a,
		'p_fieldvalidfrom' => $strValidFrom_a,
		'p_fieldvalidto' => $strValidTo_a,
		'p_info' => '',
		'p_length' => (string)$intFieldLength_a,
		'p_selection' => $strSelection_a,
		'p_lines' => '',
		'p_searchable' => 'N',
		'p_sortable' => 'N',
		'p_style' => '',
		'p_classes' => 'form-control',
		'p_colour' => $strColour_a
	);

	$strField = json_encode($arrField);
	
	return formFieldNew($arrForm_a, $strSectionCode_a, $strField);
}

function formFieldNewList($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strClasses_a, $strSelection_a)
{		
	$strClasses = 'form-control d_list';
	
	if (strlen($strClasses_a) > 0)
	{
		$strClasses .= ' ' . $strClasses_a;
	}
	
	$arrField = array(
		'p_datatype' => 'd_list',
		'p_name' => $strFieldName_a,
		'p_label' => $strFieldLabel_a,
		'p_value' => $strFieldValue_a,
		'p_required' => 'N',
		'p_readonly' => 'N',
		'p_fieldvalidfrom' => $strValidFrom_a,
		'p_fieldvalidto' => $strValidTo_a,
		'p_info' => '',
		'p_length' => (string)$intFieldLength_a,
		'p_selection' => $strSelection_a,
		'p_lines' => '',
		'p_searchable' => 'N',
		'p_sortable' => 'N',
		'p_style' => '',
		'p_classes' => $strClasses,
		'p_colour' => $strColour_a
	);

	$strField = json_encode($arrField);
	
	return formFieldNew($arrForm_a, $strSectionCode_a, $strField);
}

function formFieldNewYesNo($arrForm_a, $strSectionCode_a, $strFieldName_a, $strFieldLabel_a, $strFieldValue_a, $intFieldLength_a, $strColour_a, $strValidFrom_a, $strValidTo_a, $strReadOnly_a)
{
	$arrField = array(
		'p_datatype' => 'd_yesno',
		'p_name' => $strFieldName_a,
		'p_label' => $strFieldLabel_a,
		'p_value' => $strFieldValue_a,
		'p_required' => 'N',
		'p_readonly' => $strReadOnly_a,
		'p_fieldvalidfrom' => $strValidFrom_a,
		'p_fieldvalidto' => $strValidTo_a,
		'p_info' => '',
		'p_length' => (string)$intFieldLength_a,
		'p_selection' => '',
		'p_lines' => '',
		'p_searchable' => 'N',
		'p_sortable' => 'N',
		'p_style' => '',
		'p_classes' => 'form-control',
		'p_colour' => $strColour_a
	);

	$strField = json_encode($arrField);
	
	return formFieldNew($arrForm_a, $strSectionCode_a, $strField);
}
function formNew()
{
	$arrResult = array();
	return $arrResult;
}

function formSectionNew($arrForm_a, $strSectionType_a, $strSectionCode_a, $strSectionTitle_a, $strSectionColour_a, $strSectionQuantity_a, $strSectionRef_a, $strSectionCodeTemp_a)
{
	$arrResult = $arrForm_a;

	$arrSection = array(
		'sectionclasses' => 'btn btn-default fb-section-button',
		'sectioncode' => $strSectionCode_a,
		'sectioncodetemp' => $strSectionCodeTemp_a,
		'sectioncolour' => $strSectionColour_a,
		'sectionref' => $strSectionRef_a,
		'sectiontitle' => $strSectionTitle_a,
		'sectiontype' => $strSectionType_a,
		'sectionquantity' => $strSectionQuantity_a,
		'fields' => array()
	);

	$arrResult[] = $arrSection;
	
	return $arrResult;
}

