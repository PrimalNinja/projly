<?php

// uniquify a client code (add a counter to the end)
function clientCodeUniquify($objConn_a, $strCode_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strResult = $strCode_a;
	
    $blnContinue = true;
    $intSuffix = 0;

	while ($blnContinue)
	{
		$strSQL = "select count(*) returnvalue from ~TABLENAMECLIENT~ where code = '~CODE~'";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strResult), $strSQL);
		$intCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		if ($intCount == 0)
		{
			$blnContinue = false;
		}
		else
		{
			$intSuffix++;
			$strResult = $strCode_a . strval($intSuffix);
		}
	}

    return $strResult;
}