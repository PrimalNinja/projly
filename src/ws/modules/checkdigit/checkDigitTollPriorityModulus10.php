<?php

function checkDigitTollPriorityModulus10($str_a)
{
    $strResult = '';
    $strChar = '';
    $intDigit = 0;

    $intLength = strlen($str_a);

    $intProduct = 0;
    $intMultiplier = 3;
    $intI = ($intLength - 1);
    while ($intI >= 0) 
	{
        $strChar = substr($str_a, $intI, 1);
        if (is_numeric($strChar)) 
		{
            $intDigit = ord($strChar) - ord("0");
        } 
		else 
		{
            $intDigit = (ord($strChar) - ord("A")) % 10;
        }

        $intProduct = $intProduct + $intDigit * $intMultiplier;
        if ($intMultiplier == 3) 
		{
            $intMultiplier = 1;
        } 
		else 
		{
            $intMultiplier = 3;
        }
        $intI--;
    }

    $intRemainder = $intProduct % 10;
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
