<?php

function getCount_printersetting($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
    $strTableNamePrinter = getTableNameEntity("printer", false);

	$strUserID = $_SESSION['server_loggedin_userid'];
	
	$strFilter = "";
	if (strlen($strFilter_a) > 0)
	{
		$strFilter .= "and settings_description like '%" . ff($strFilter_a) . "%'";
	}
	
    $strSQL = "select count(*) returnvalue from (
		select id from ~TABLENAMEPRINTER~ where client_id = ~CLIENTID~ and user_id = ~USERID~ ~FILTER~
		union
		select id from ~TABLENAMEPRINTER~ where client_id = ~CLIENTID~ and user_id <> ~USERID~ and is_public = 'Y' ~FILTER~
	) e where 1=1";
	$strSQL = str_replace('~TABLENAMEPRINTER~', ff($strTableNamePrinter), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
	$strSQL = str_replace('~FILTER~', $strFilter, $strSQL);
	
    return $strSQL;
}

function getData_printersetting($objConn_a, $strFormEntityCode_a, $blnIgnoreClient_a, $strClientID_a, $strFilter_a, $arrFixedFilter_a, $arrPassedFilter_a, $blnExclusive_a, $arrFields_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $arrOrder_a, $intOffset_a, $intLimit_a)
{
    $strTableNamePrinter = getTableNameEntity("printer", false);
    $strTableNameUser = getTableNameEntity("user", false);

	$strUserID = $_SESSION['server_loggedin_userid'];
	
	$strFilter1 = "";
	if (strlen($strFilter_a) > 0)
	{
		$strFilter1 .= "and settings_description like '%" . ff($strFilter_a) . "%'";
	}

	$strFilter2 = "";
	if (strlen($strFilter_a) > 0)
	{
		$strFilter2 .= "and concat(u.description, ' \\\ ', pr.settings_description) like '%" . ff($strFilter_a) . "%'";
	}
	
	$strOrderBy = "";
	if (count($arrOrder_a) > 0)
	{
		$strField = trim($arrOrder_a[0]["field"]);
		$blnAscending = toBoolean($arrOrder_a[0]["ascending"]);
		if (strlen($strField) > 0)
		{
			//logDebug("BINGO:" . print_r($arrOrder_a, true), "");
			$strOrderBy = "order by " . ff($strField);
			if (!$blnAscending)
			{
				$strOrderBy .= " desc";
			}
		}
	}
	
    $strSQL = "select t.* from (
		select id, settings_description description, '' code, '' is_enabled, '' modifydatetime from ~TABLENAMEPRINTER~ where client_id = ~CLIENTID~ and user_id = ~USERID~ ~FILTER1~
		union
		select pr.id, concat(u.description, ' \\\ ', pr.settings_description) description, '' code, '' is_enabled, '' modifydatetime from ~TABLENAMEPRINTER~ pr, ~TABLENAMEUSER~ u 
		where 
		pr.client_id = ~CLIENTID~ and 
		pr.user_id <> ~USERID~ and 
		pr.is_public = 'Y' and
		u.id = pr.user_id and
		u.client_id = pr.client_id
		~FILTER2~
		~ORDERBY~
	) t where 1=1";
	$strSQL = str_replace('~TABLENAMEPRINTER~', ff($strTableNamePrinter), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
	$strSQL = str_replace('~FILTER1~', $strFilter1, $strSQL);
	$strSQL = str_replace('~FILTER2~', $strFilter2, $strSQL);
	$strSQL = str_replace('~ORDERBY~', $strOrderBy, $strSQL);

    return $strSQL;
}

function decodeFilter_printersetting($objConn_a, $strFilter_a, $arrFixedFilter_a)
{
    $arrResult = $arrFixedFilter_a;

    foreach ($arrResult as &$objField)
    {
		if ($objField['field'] == 'client_id')
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
		}
		else if ($objField['field'] == 'user_id')	// to cater for ADDTO of profile to user
		{
			$objField['value'] = revertSecuredValue($objField['value'], 'id', true);
		}    
    }

    return $arrResult;
}

function getFilter_printersetting($objConn_a, $strAppend_a, $strFilter_a, $arrSearchableFields_a, $arrFixedFilter_a, $arrPassedFilter_a)
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
            //$strFilter .= "(email_address like '%" . ff($strFilter_a) . "%')";
            //$strFilter .= " or ";
            //$strFilter .= "(login like '%" . ff($strFilter_a) . "%')";
            //$strFilter .= " or ";
            $strFilter .= "(is_enabled like '%" . ff($strFilter_a) . "%')";
            $strFilter .= " or ";
            $strFilter .= "(modifydatetime like '%" . ff($strFilter_a) . "%')";
        $strFilter .= ")";
    }

    return $strFilter;
}
