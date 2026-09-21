<?php

// function summary:

// formatMoney($strText_a)
// getExGSTAmount($fltAmount_a)
// getGSTRate()
// getGSTAmount($fltAmount_a)
// moneyRounded($flt_a, $intDP_a)

function formatMoney($strText_a)
{
    $strCurrency = "$"; // just a placeholder.
    $fltValue = floatval($strText_a);

    return $strCurrency . number_format($fltValue,2,'.', ',');
}

function getExGSTAmount($fltAmount_a)
{
    return $fltAmount_a - getGSTAmount($fltAmount_a);
}

function getGSTRate()
{
    $fltResult = 10;
    
    return $fltResult;
}

function getGSTAmount($fltAmount_a)
{
    $fltResult = 0;
	$fltGSTRate = getGSTRate();
	
	if ($fltAmount_a > 0)
	{
		// $fltResult = $fltAmount_a / 11;
        $fltExGSTAmount = $fltAmount_a / (1 + $fltGSTRate / 100);
        $fltResult = $fltAmount_a - $fltExGSTAmount;
	}
    
    return round($fltResult, 2);
}

function moneyRounded($flt_a, $intDP_a)
{
    return number_format($flt_a, $intDP_a, '.', '');
}

