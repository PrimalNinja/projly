<?php

function checkDigitTollPriorityModulus7($str_a)
{
    $strResult = '';

    if (!is_numeric($str_a)) 
	{
        safetyDie('non numeric during check digit creation');
    }

    $dblID = strval($str_a);
    $strResult = strval($dblID % 7);

    return $strResult;
}
