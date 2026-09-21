<?php

function checkDigitModulus10EAN14($str_a)
{
    $intResult;
    $intSum1 = 0;
    $intSum2 = 0;

    if (!is_numeric($str_a)) 
	{
        safetyDie('non numeric during checkdigit creation');
    }

    $strID = $str_a;

    $intI = strlen($strID) - 1;
    while ($intI >= 0) 
	{
        $intSum1 = $intSum1 + intval(substr($strID, $intI < 0 ? 0 : $intI, 1));
        $intI = $intI - 2;
    }

    $intSum1 = $intSum1 * 3;

    $intI = strlen($strID) - 2;
    while ($intI >= 0) 
	{
        $intSum2 = $intSum2 + intval(substr($strID, $intI < 0 ? 0 : $intI, 1));
        $intI = $intI - 2;
    }

    $intTotal = $intSum1 + $intSum2;

    $intRemainder = $intTotal % 10;
    if ($intRemainder != 0) 
	{
        $intResult = 10 - $intRemainder;
    } 
	else 
	{
        $intResult = 0;
    }

    return strval($intResult);
}
