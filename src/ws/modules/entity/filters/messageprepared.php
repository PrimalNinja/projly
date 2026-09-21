<?php

function getCount_messageprepared($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
	$strSQL = "select count(*) returnvalue from ~TABLENAMEMESSAGEPREPARED~";
	$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
	$strSQL .= dbBuildWhere('where', $arrFields_a, $arrFixedFilter_a);

	return $strSQL;
}

function getData_messageprepared($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $arrOrder_a, $intOffset_a, $intLimit_a)
{
	$strOrderBy = dbBuildOrderBy($arrFields_a, $arrOrder_a, "mp.modifydatetime desc, mp.id");

	$strSQL = "select q.* from (
	
select mp.id, mp.recipients, mp.subject, mp.priority, mp.retries, mp.sent, mp.modifydatetime
from ~TABLENAMEMESSAGEPREPARED~ mp
~FILTER~ ~ORDERBY~ limit ~OFFSET~, ~LIMIT~) q join ~TABLENAMEMESSAGEPREPARED~ t on t.id = q.id
";

	$strFilter = dbBuildWhere('where', $arrFields_a, $arrFixedFilter_a);
	if (strlen($strFilter_a) > 0)
	{
		$strFilter .= " and (mp.recipients like '%~FILTER~%' or mp.subject like '%~FILTER~%')";
		$strFilter = str_replace('~FILTER~', ff($strFilter_a), $strFilter);
	}
	$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
	$strSQL = str_replace('~FILTER~', $strFilter, $strSQL);
	$strSQL = str_replace('~ORDERBY~', $strOrderBy, $strSQL);
	$strSQL = str_replace('~OFFSET~', ff($intOffset_a), $strSQL);
	$strSQL = str_replace('~LIMIT~', ff($intLimit_a), $strSQL);

	return $strSQL;
}

function decodeFilter_messageprepared($objConn_a, $strFilter_a, $arrFixedFilter_a) 
{
	$arrResult = $arrFixedFilter_a;
	
	foreach ($arrResult as &$objField)
	{
		if ($objField['field'] == 'id')
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
		}
	}
	
	return $arrResult;
}

function getFilter_messageprepared($objConn_a, $strAppend_a, $strFilter_a, $arrSearchableFields_a, $arrFixedFilter_a, $arrPassedFilter_a) 
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
