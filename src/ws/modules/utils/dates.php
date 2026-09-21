<?php

// function summary:

// calculateExpiryDate($strPurchaseDate_a, $strPeriod_a)
// dateDayOfWeek($strDate_a)
// dateTimeToDate($strDate_a)
// dateTimeToDay($strDate_a)
// dateTimeToTime($strDate_a)
// dateTimeToYear($strDate_a)
// dateTimeToYM($strDate_a)
// getConvertPeriod($intPeriod_a, $strPeriodTo_a)
// getDateAddDays($dte_a, $intDays_a)
// getDateAddMonths($dte_a, $intMonths_a)
// getDateAddYears($dte_a, $intYears_a)
// getDateFromDateTime($dte_a)
// getDateMinusDays($dte_a, $intDays_a)
// getDateMinusYears($dte_a, $intYears_a)
// getDateOnly()
// getDateReportOut($strDate_a)
// getDateStringOut($strDate_a)
// getDateStringSearch($strDate_a)
// getDateTime()
// getDateTimeAddDays($dte_a, $intDays_a)
// getDateTimeAddHours($dte_a, $intHours_a)
// getDateTimeAddMinutes($dte_a, $intMinutes_a)
// getDateTimeLastWeek()
// getDateTimeMinusHours($dte_a, $intHours_a)
// getDateTimeMinusSeconds($dte_a, $intSeconds_a)
// getDateTimePlusSeconds($dte_a, $intSeconds_a)
// getDateTimeStringOut($strDateTime_a)
// getDifferenceBetweenDates($str_interval, $dt_smaller, $dt_larger, $relative=false)
// getFromISODateToFormattedDate($strDate_a, $strFormat_a)
// getFutureTimeOnly($dte_a, $intHours_a)
// getISODate()
// getPastTimeOnly($dte_a, $intHours_a)
// getPeriodStartDate($strBaseDate_a, $strDate_a, $intNumberOfDays_a)
// getTimeForHour($intHour_a)
// getTimeForHourMinute($intHour_a, $intMinute_a)
// getTimeOnly()
// getToday()
// validateDate($strDate_a)

function calculateExpiryDate($strPurchaseDate_a, $strPeriod_a)
{
	$strResult = '';
	$strPeriod = strtoupper($strPeriod_a);
	
	if (strlen($strPeriod) > 0)
	{
		$strResult = $strPurchaseDate_a;
		
		// now add the period
		if (InStr($strPeriod, 'D') >= 0)
		{
			// add days
			$intDays = str_replace('D', '', $strPeriod);
			$strResult = getDateAddDays($strResult, $intDays);
		}
		else if (InStr($strPeriod, 'M') >= 0)
		{
			// add months
			$intMonths = str_replace('M', '', $strPeriod);
			$strResult = getDateAddMonths($strResult, $intMonths);
		}
		else if (InStr($strPeriod, 'Y') >= 0)
		{
			// add years
			$intYears = str_replace('Y', '', $strPeriod);
			$strResult = getDateAddYears($strResult, $intYears);
		}
	}
	
	return $strResult;
}

function dateDayOfWeek($strDate_a)
{
    $dte = new DateTime($strDate_a);
	return $dte->format("l");
}

function dateTimeToDate($strDate_a)
{
    $dte = new DateTime($strDate_a);
	return $dte->format("Y-m-d");
}

function dateTimeToDay($strDate_a)
{
    $dte = new DateTime($strDate_a);
	return $dte->format("d");
}

function dateTimeToTime($strDate_a)
{
    $dte = new DateTime($strDate_a);
    return $dte->format("H:i:s");
}

function dateTimeToYear($strDate_a)
{
    $dte = new DateTime($strDate_a);
	return $dte->format("Y");
}

function dateTimeToYM($strDate_a)
{
    $dte = new DateTime($strDate_a);
	return $dte->format("Y-m");
}

// return the period in a type you want
function getConvertPeriod($intPeriod_a, $strPeriodTo_a)
{
	$strPeriodFrom = substr($intPeriod_a, -1);
	$intPeriod = substr($intPeriod_a, 0, -1);
	
	if ($strPeriodFrom == "y")
	{
		if ($strPeriodTo_a == "y")
		{
			$intPeriod = intval($intPeriod, 10);
		}
		else if ($strPeriodTo_a == "m")
		{
			$intPeriod = $intPeriod * 12;
		}
		else if ($strPeriodTo_a == "d")
		{
			$intPeriod = $intPeriod * 365;
		}
	}
	else if ($strPeriodFrom == "m")
	{
		if ($strPeriodTo_a == "y")
		{
			$intPeriod = $intPeriod * 1/12;
		}
		else if ($strPeriodTo_a == "m")
		{
			$intPeriod = intval($intPeriod, 10);
		}
		else if ($strPeriodTo_a == "d")
		{
			$intPeriod = $intPeriod * 30;
		}
	}
	else if ($strPeriodFrom == "d")
	{
		if ($strPeriodTo_a == "y")
		{
			$intPeriod = $intPeriod * 1/365;
		}
		else if ($strPeriodTo_a == "m")
		{
			$intPeriod = $intPeriod * 1/30;
		}
		else if ($strPeriodTo_a == "d")
		{
			$intPeriod = intval($intPeriod, 10);
		}
	}
	
	return $intPeriod;
}

function getDateAddDays($dte_a, $intDays_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intDays_a . "D");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d");
}

function getDateAddMonths($dte_a, $intMonths_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intMonths_a . "M");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d");
}

function getDateAddYears($dte_a, $intYears_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intYears_a . "Y");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d");
}

function getDateFromDateTime($dte_a)
{
    $dte = new DateTime($dte_a);
    return $dte->format("Y-m-d");
}

// return the last week's date ISO format
function getDateMinusDays($dte_a, $intDays_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intDays_a . "D");
    $dteInterval->invert = 1; //make the interval in the past.
    $dte->add($dteInterval);
    return $dte->format("Y-m-d");
}

// return the a past year in ISO format
function getDateMinusYears($dte_a, $intYears_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intYears_a . "Y");
    $dteInterval->invert = 1; //make the interval in the past.
    $dte->add($dteInterval);
    return $dte->format("Y-m-d");
}

// return the current date
function getDateOnly()
{
	return date("Y-m-d");
}

// format the date for report output
function getDateReportOut($strDate_a)
{
	$objDate = date_create($strDate_a);
	return date_format($objDate, 'j M Y');
}

// format the date for output
function getDateStringOut($strDate_a)
{
	$strResult = "";

	if (strlen($strDate_a) > 0)
	{
		$strResult = substr($strDate_a, 0, 4) . '/' . substr($strDate_a, 5, 2) . '/' . substr($strDate_a, 8, 2);
		
		if (DATE_OUTPUTFORMAT == "DD/MM/YYYY")
		{
			$strResult = substr($strDate_a, 8, 2) . '/' . substr($strDate_a, 5, 2) . '/' . substr($strDate_a, 0, 4);
		}
		else if (DATE_OUTPUTFORMAT == "MM/DD/YYYY")
		{
			$strResult = substr($strDate_a, 5, 2) . '/' . substr($strDate_a, 8, 2) . '/' . substr($strDate_a, 0, 4);
		}
		else if (DATE_OUTPUTFORMAT == "YYYY/MM/DD")
		{
			$strResult = substr($strDate_a, 0, 4) . '/' . substr($strDate_a, 5, 2) . '/' . substr($strDate_a, 8, 2);
		}
	}

    return $strResult;
}

// format the date for searching
function getDateStringSearch($strDate_a)
{
	$strResult = "";

	if (strlen($strDate_a) > 0)
	{
		$strResult = $strDate_a;
		                        //0123456789
		if (DATE_OUTPUTFORMAT == "DD/MM/YYYY")
		{
			$strResult = substr($strDate_a, 6, 4) . '-' . substr($strDate_a, 3, 2) . '-' . substr($strDate_a, 0, 2);
		}							//0123456789
		else if (DATE_OUTPUTFORMAT == "MM/DD/YYYY")
		{
			$strResult = substr($strDate_a, 6, 4) . '-' . substr($strDate_a, 0, 2) . '-' . substr($strDate_a, 3, 2);
		}							//0123456789
		else if (DATE_OUTPUTFORMAT == "YYYY/MM/DD")
		{
			$strResult = substr($strDate_a, 0, 4) . '-' . substr($strDate_a, 5, 2) . '-' . substr($strDate_a, 8, 2);
		}
	}

    return $strResult;
}

// return the current date and time ISO format
function getDateTime()
{
    return date("Y-m-d H:i:s");
}

function getDateTimeAddDays($dte_a, $intDays_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("P" . $intDays_a . "D");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d H:i:s");
}

function getDateTimeAddHours($dte_a, $intHours_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intHours_a . "H");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d H:i:s");
}

function getDateTimeAddMinutes($dte_a, $intMinutes_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intMinutes_a . "M");
    $dte->add($dteInterval);
    return $dte->format("Y-m-d H:i:s");
}

// return the last week's date and time ISO format
function getDateTimeLastWeek()
{
    return date('Y-m-d H:i:s', strtotime('-7 day'));
}

function getDateTimeMinusHours($dte_a, $intHours_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intHours_a . "H");
    $dteInterval->invert = 1; //make the interval in the past.
    $dte->add($dteInterval);
    return $dte->format("Y-m-d H:i:s");
}
// return the current date and time ISO format - a timeframe in seconds
function getDateTimeMinusSeconds($dte_a, $intSeconds_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intSeconds_a . "S");
    $dteInterval->invert = 1; //make the interval in the past.
    $dte->add($dteInterval);
    return $dte->format("Y-m-d H:i:s");
}

// return the current date and time ISO format + a timeframe in seconds
function getDateTimePlusSeconds($dte_a, $intSeconds_a)
{
    $dte = new DateTime($dte_a);
    $dte->add(new DateInterval("PT" . $intSeconds_a . "S"));
    return $dte->format("Y-m-d H:i:s");
}

// format the date & time for output
function getDateTimeStringOut($strDateTime_a)
{
	$strResult = "";

	if (strlen($strDateTime_a) > 0)
	{
		$strResult = substr($strDateTime_a, 0, 4) . '/' . substr($strDateTime_a, 5, 2) . '/' . substr($strDateTime_a, 8, 2) . ' ' . substr($strDateTime_a, 11, 8);
		
		if (DATE_OUTPUTFORMAT == "DD/MM/YYYY")
		{
			$strResult = substr($strDateTime_a, 8, 2) . '/' . substr($strDateTime_a, 5, 2) . '/' . substr($strDateTime_a, 0, 4) . ' ' . substr($strDateTime_a, 11, 8);
		}
		else if (DATE_OUTPUTFORMAT == "MM/DD/YYYY")
		{
			$strResult = substr($strDateTime_a, 5, 2) . '/' . substr($strDateTime_a, 8, 2) . '/' . substr($strDateTime_a, 0, 4) . ' ' . substr($strDateTime_a, 11, 8);
		}
		else if (DATE_OUTPUTFORMAT == "YYYY/MM/DD")
		{
			$strResult = substr($strDateTime_a, 0, 4) . '/' . substr($strDateTime_a, 5, 2) . '/' . substr($strDateTime_a, 8, 2) . ' ' . substr($strDateTime_a, 11, 8);
		}
	}

    return $strResult;
}

// calculate the difference between smaller and larger dates - example taken from comments section in http://php.net/manual/en/function.date-diff.php
function getDifferenceBetweenDates($str_interval, $dt_smaller, $dt_larger, $relative=false)
{
	if (is_string( $dt_smaller)) $dt_smaller = date_create( $dt_smaller);
	if (is_string( $dt_larger)) $dt_larger = date_create( $dt_larger);

	$diff = date_diff( $dt_smaller, $dt_larger, ! $relative);

	switch( $str_interval)
	{
		case "y": 
			$total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
		
		case "m":
			$total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
			break;
		
		case "d":
			$total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
			break;
		
		case "h": 
			$total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
			break;
		
		case "i": 
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
			break;
		
		case "s": 
			$total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
			break;
	}
 
	if ($diff->invert)
	{
		return -1 * $total;
	}
	else
	{
		return $total;
	}
}

// get a date in a specified format
function getFromISODateToFormattedDate($strDate_a, $strFormat_a)
{
	$strResult = $strDate_a;

	if (strlen($strDate_a) > 0)
	{
		//2025-05-27
		$strYear = substr($strDate_a, 0, 4);
		$strMonth = substr($strDate_a, 5, 2);
		$strDay = substr($strDate_a, 8, 2);

		if ($strFormat_a == "DD/MM/YYYY")
		{
			$strResult = $strDay . '/' . $strMonth . '/' . $strYear;
		}
		else if ($strFormat_a == "MM/DD/YYYY")
		{
			$strResult = $strMonth . '/' . $strDay . '/' . $strYear;
		}
		else if ($strFormat_a == "YYYY/MM/DD")
		{
			$strResult = $strYear . '/' . $strMonth . '/' . $strDay;
		}
	}
	
    return $strResult;
}

function getFutureTimeOnly($dte_a, $intHours_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intHours_a . "H");
    $dte->add($dteInterval);
    return $dte->format("H:i:s");
}

// return the current date ISO format
function getISODate()
{
    return date("Y-m-d");
}

function getPastTimeOnly($dte_a, $intHours_a)
{
    $dte = new DateTime($dte_a);
    $dteInterval = new DateInterval("PT" . $intHours_a . "H");
    $dteInterval->invert = 1; //make the interval in the past.
    $dte->add($dteInterval);
    return $dte->format("H:i:s");
}

// return the starting date of time sheet period
function getPeriodStartDate($strBaseDate_a, $strDate_a, $intNumberOfDays_a)
{
	$dte1 = date_create($strBaseDate_a);
	$dte2 = date_create($strDate_a);
	$objInterval = date_diff($dte1, $dte2, TRUE);

	$intDateDiff = intval($objInterval->format('%a '));
	$intDaysFromStartDate = ($intDateDiff % $intNumberOfDays_a);

	$strResult = getDateMinusDays($strDate_a, $intDaysFromStartDate);

	return $strResult;
}

// return the time for a given hour
function getTimeForHour($intHour_a)
{
	return date(str_pad($intHour_a, 2, "0", STR_PAD_LEFT) . ":00:00");
}

// return the time for a given hour/minute
function getTimeForHourMinute($intHour_a, $intMinute_a)
{
	return date(str_pad($intHour_a, 2, "0", STR_PAD_LEFT) . ":" . str_pad($intMinute_a, 2, "0", STR_PAD_LEFT) . ":00");
}

// return the current time
function getTimeOnly()
{
	return date("H:i:s");
}

// return the current date
function getToday()
{
	return date("d M Y");
}

// validate an input date against ISO format
function validateDate($strDate_a)
{
    $blnResult = false;

    if (date('Y-m-d', strtotime($strDate_a . '00:00:00')) == $strDate_a) 
	{
        $blnResult = true;
    }

    return $blnResult;
}
