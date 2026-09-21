<?php

function getCount_systemreport($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
	$strSQL = '';
	return $strSQL;
}

function getData_systemreport($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $arrOrder_a, $intOffset_a, $intLimit_a)
{
	$strSQL = '';
	return $strSQL;
}

function decodeFilter_systemreport($objConn_a, $strFilter_a, $arrFixedFilter_a) 
{
	$arrResult = $arrFixedFilter_a;
	
	foreach ($arrResult as &$objField)
	{
		if ($objField['field'] == 'dataentity_id')
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
        }
        
        if ($objField['field'] == 'systemmodule_id') // to cater for ADDTO of permission to profile (used within the POPUP to choose the permission)
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
        }
        
        if ($objField['field'] == 'systemmodule_form_id') // to cater for ADDTO of permission to profile (used within the POPUP to choose the permission)
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
		}
	}
	
	return $arrResult;
}

function getFilter_systemreport($objConn_a, $strAppend_a, $strFilter_a, $arrSearchableFields_a, $arrFixedFilter_a, $arrPassedFilter_a) 
{
	$strFilter = "";
	
	if (strlen($strFilter_a) > 0)
	{
		$strFilter = " " . $strAppend_a . " (";
			foreach ($arrSearchableFields_a as $strField)
			{
				$strFilter .= "(" . $strField . " like '%" . ff($strFilter_a) . "%')";
				$strFilter .= " or ";
			}
		
			$strFilter .= "(code like '%" . ff($strFilter_a) . "%')";
            $strFilter .= " or ";
			$strFilter .= "(description like '%" . ff($strFilter_a) . "%')";
            $strFilter .= " or ";
			$strFilter .= "(is_enabled like '%" . ff($strFilter_a) . "%')";
            $strFilter .= " or ";
			$strFilter .= "(modifydatetime like '%" . ff($strFilter_a) . "%')";
		$strFilter .= ")";
	}
    
    return $strFilter;
}
