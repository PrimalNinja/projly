<?php

// function summary:

// centreBarcode($str_a)

// barcode center approximately
// Characters * 11 modules (dots) per character (7 bars and 4 spaces)+ Start/Stop patterns  add approximately 22 modules
//  (whitespace before and after the barcode) add at least 10 dots on each side.
function centreBarcode($str_a)
{
	$intLen = strlen($str_a);
	$intWidth = (int)((3 * ($intLen * 11 + 22) + 20));
	$intCentre = (int)((812 - $intWidth) / 2) - 10;
	
	if ($intCentre < 0)
	{
		$intCentre = 0;
	}
	
	return $intCentre;
}
