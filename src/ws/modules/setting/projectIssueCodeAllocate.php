<?php

// allocate a project issue code
function projectIssueCodeAllocate($objConn_a, $strClientID_a)
{
	$strTableNameProjectIssue = getTableNameEntity("projectissue", false);

    $strResult = '';
	
    $blnContinue = true;
    $intTry = 1;
    $intTimeout = 10;
    
    if (dependencies('setting/settingGet,setting/rnsCodeAllocate,setting/sequenceAllocate')) 
	{
		$strRNSIDPrefix = settingGet($objConn_a, 'CORE', 'RNSIDPFX', $strClientID_a, '', '', '', __FUNCTION__);
		$strRNSCode = rnsCodeAllocate($objConn_a, $strClientID_a, $strRNSIDPrefix);

        while ($blnContinue)
        {
            $strResult = sequenceAllocate($objConn_a, '', 'PROJLY', 'PROJECTISSUEID', $strClientID_a, '', $strRNSIDPrefix, $strRNSCode, '', __FUNCTION__);

            $strSQL = "select id returnvalue from ~TABLENAMEPROJECTISSUE~ where client_id = ~CLIENTID~ and code = '~CODE~'";
			$strSQL = str_replace('~TABLENAMEPROJECTISSUE~', ff($strTableNameProjectIssue), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~CODE~', ff($strResult), $strSQL);
            $intID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            if ((strlen($intID) == 0) || ($intTry == $intTimeout))
            {
                $blnContinue = false;
            }

            $intTry++;
        }
    }

    return $strResult;
}