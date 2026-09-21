<?php

// valuate from JSON
function evaluateJSONFilter($arrJSONDatasetFields_a, $arrFilter_a, $arrJSONData_a)
{
	$blnResult = false;
	$intBlankOrs = 0;
	$intTotalOrs = count($arrFilter_a);

	if ($intTotalOrs == 0)
	{
		$blnResult = true;
	}
	else
	{
		$intOrs = 0;
		foreach ($arrFilter_a as $arrOr) 
		{
			if (count($arrOr) == 0)
			{
				//do nothing
				$intBlankOrs++;
			}
			else
			{
				$blnAnd = true;	// true until proven false
				$intAnds = 0;
				
				foreach ($arrOr as $arrAnd) 
				{
					if ($blnAnd)
					{
						$strSectionCode = $arrAnd['sectioncode'];
						$strFunctionCode = $arrAnd['functioncode'];
						$strFieldCode = $arrAnd['fieldcode'];
						$strDataType = getDataType($arrJSONDatasetFields_a, $strFieldCode);
						$strCriteria = $arrAnd['value'];
						
						$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData_a, $strSectionCode, $strFieldCode);
						$strFieldValue = $arrJSONField['p_value'];

						// functions to massage before our normal string comparison is done
						
						// todo: treat submitteddatetime differently because it is a date & time stored within a d_text
						try
						{
							// datatypes (currently no special formatting for datatypes)
							if ($strFunctionCode == "d_date")
							{
								// do nothing
							} 
							else if ($strFunctionCode == "d_number")
							{
								// do nothing
							}
							else if ($strFunctionCode == "d_text")
							{
								// do nothing
							}
							else
							{
								// do nothing
							}

							// functions
							$blnCompared = false;
							if ($strFunctionCode == "isEqualTo")
							{
								$blnCompared = ($strFieldValue == $strCriteria);
							}

							if ($strFunctionCode == "isNotEqualTo")
							{
								$blnCompared = ($strFieldValue != $strCriteria);
							}

							if ($strFunctionCode == "isLessThan")
							{
								$blnCompared = ($strFieldValue < $strCriteria);
							}

							if ($strFunctionCode == "isLessThanOrEqualTo")
							{
								$blnCompared = ($strFieldValue <= $strCriteria);
							}

							if ($strFunctionCode == "isGreaterThan")
							{
								$blnCompared = ($strFieldValue > $strCriteria);
							}

							if ($strFunctionCode == "isGreaterThanOrEqualTo")
							{
								$blnCompared = ($strFieldValue >= $strCriteria);
							}

							// special functions
							if ($strFunctionCode == "isLastMonth")
							{
								$strCriteria = date("m", strtotime("-1 month"));
								$date = new DateTime($strFieldValue);
								$strFieldValue = $date->format("m");
								$blnCompared = ($strFieldValue == $strCriteria);
							}

							if ($strFunctionCode == "isThisMonth")
							{
								$strCriteria = date("m");
								$date = new DateTime($strFieldValue);
								$strFieldValue = $date->format("m");
								$blnCompared = ($strFieldValue == $strCriteria);
							}
						}
						catch (Exception $e) 
						{
							$blnAnd = false;
						}

						if ($blnCompared)	
						{
							$intAnds++;
						}
						else // if any of the ANDs are false then the OR is false
						{
							$blnAnd = false;
						}
					}
				}
				
				if (($intAnds > 0) && ($blnAnd))	// if one of our ORs is true then break out as all is true
				{
					$intOrs++;
				}
			}
		}
		
		if (($intOrs > 0) || ($intTotalOrs == $intBlankOrs))
		{
			$blnResult = true;
		}
	}
	
	return $blnResult;
}

function getDataType($arrJSONDatasetFields_a, $strFieldCode_a)
{
	$strResult = "";

	foreach ($arrJSONDatasetFields_a as $objField) 
	{
		if ($objField['fieldcode'] == $strFieldCode_a)
		{
			$strResult = $objField['datatype'];
		}
	}

	return $strResult;
}

// delete if not required eventually 20250527
// format the date for output
function getDateIntOut($intDate_a)
{
	$strResult = "";

	if ($intDate_a > 0)
	{
		$strResult = substr(strval($intDate_a), 0, 4) . '/' . substr(strval($intDate_a), 4, 2) . '/' . substr(strval($intDate_a), 6, 2);
		
		if (DATE_OUTPUTFORMAT == "DD/MM/YYYY")
		{
			$strResult = substr(strval($intDate_a), 6, 2) . '/' . substr(strval($intDate_a), 4, 2) . '/' . substr(strval($intDate_a), 0, 4);
		}
		else if (DATE_OUTPUTFORMAT == "MM/DD/YYYY")
		{
			$strResult = substr(strval($intDate_a), 4, 2) . '/' . substr(strval($intDate_a), 6, 2) . '/' . substr(strval($intDate_a), 0, 4);
		}
		else if (DATE_OUTPUTFORMAT == "YYYY/MM/DD")
		{
			$strResult = substr(strval($intDate_a), 0, 4) . '/' . substr(strval($intDate_a), 4, 2) . '/' . substr(strval($intDate_a), 6, 2);
		}
	}
	
    return $strResult;
}

function getExportFields($arrFields_a)
{
	$intMaxPosition = 0;
	$arrExportFields = array();
	$strResult = "";

	// iterate through every field we want to export, work out the highest exported position (as we might have gaps prior to it)
	$intField = 1;
	foreach ($arrFields_a as $objField) 
	{
		//exclude field if position is empty
		if (!empty($objField['position']))
		{       
			$intPosition = $objField['position'];
			if ($intPosition > $intMaxPosition)
			{
				$intMaxPosition = $intPosition;
			}
			
			//$strField = "column" . $intField;
			$strField = "REPLACE( REPLACE( IFNULL(column" . $intField . ", ''), '\r' , '' ), '\n', '' )";
			$arrExportFields['ID' . $intPosition] = $strField;
		}

		$intField++;
	}

	// loop from 1 to max position pulling finding our required field otherwise an empty literal string
	for ($intField = 1; $intField <= $intMaxPosition; $intField++)
	{
		if (array_key_exists('ID' . $intField, $arrExportFields))
		{
			if (strlen($strResult) > 0)
			{
				$strResult .= ", ";
			}
			$strResult .= $arrExportFields['ID' . $intField];
		}
		else
		{
			if (strlen($strResult) > 0)
			{
				$strResult .= ", ";
			}
			$strResult .= "''";
		}
	}
	
	return $strResult;
}

// returns an empty name if not found
function getFileFormatFieldName($objFileFormat_a, $strFieldName_a)
{
    $strResult = "";

    $objField = getFileFormatField($objFileFormat_a, $strFieldName_a);
    if ($objField !== null) 
	{
        if (intval('0' . $objField->position) > 0) 
		{
            $strResult = 'column' . strval(intval('0' . $objField->position) - 1);
        }
    }

    return $strResult;
}

function getFileFormatTemporaryTableFields($objFileFormat_a)
{
    $strResult = '';

    foreach ($objFileFormat_a->fields as $objField) 
	{
        $strField = getFileFormatFieldValue($objFileFormat_a, $objField->fieldname);

        if (strlen($strResult) > 0) 
		{
            $strResult .= ",";
        }
        $strResult .= $strField . " " . $objField->fieldname;
    }

    return $strResult;
}

// get a filter to query the db
function getFilterSQL($arrJSONDatasetFields_a, $arrFilter_a)
{
	$strResult = "";
	// code the below so that it returns SQL version of the filter without the word WHERE in front, i.e.  (fielda = 'b' and fieldb = 'c') or (fieldc = 1 and fieldd = 2)
	$strOrs = "";
	$strAnds = "";
	foreach ($arrFilter_a as $arrOr) 
	{
		$strAnds = "";
		foreach ($arrOr as $arrAnd) 
		{
			$strFunctionCode = $arrAnd['functioncode'];
			$strFieldCode = $arrAnd['fieldcode'];
			$strDataType = getDataType($arrJSONDatasetFields_a, $strFieldCode);
			$strCriteria = $arrAnd['value'];
			
			if (strlen($strAnds) > 0)
			{
				$strAnds .= ' and ';
			}
		
			// functions to massage before our normal string comparison is done
			
			try
			{
				// datatypes
				if ($strDataType == "d_date")
				{
					$strFieldCode = "DATE(" . ff($strFieldCode) . ")";
					$strCriteria = "DATE('" . ff($strCriteria) . "')";
				}
				else if ($strDataType == "d_number")
				{
					$strFieldCode = $strFieldCode;
					$strCriteria = ff($strCriteria);
				}
				else if ($strDataType == "d_text")
				{
					$strFieldCode = $strFieldCode;
					$strCriteria = "'" . ff($strCriteria) . "'";
				}
				else
				{
					$strFieldCode = $strFieldCode;
					$strCriteria = ff($strCriteria);
				}

				// functions
				$strComparitor = "";
				if ($strFunctionCode == "isEqualTo")
				{
					$strComparitor = "=";
				}

				if ($strFunctionCode == "isNotEqualTo")
				{
					$strComparitor = "<>";
				}

				if ($strFunctionCode == "isLessThan")
				{
					$strComparitor = "<";
				}

				if ($strFunctionCode == "isLessThanOrEqualTo")
				{
					$strComparitor = "<=";
				}

				if ($strFunctionCode == "isGreaterThan")
				{
					$strComparitor = ">";
				}

				if ($strFunctionCode == "isGreaterThanOrEqualTo")
				{
					$strComparitor = ">=";
				}

				// special functions
				if ($strFunctionCode == "isLastMonth")
				{
					$strFieldCode = "MONTH(" . $strFieldCode . ")";
					$strCriteria = "MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)";
					$strComparitor = "=";
				}

				if ($strFunctionCode == "isThisMonth")
				{
					$strFieldCode = "MONTH(" . $strFieldCode . ")";
					$strCriteria = "MONTH(CURRENT_DATE())";
					$strComparitor = "=";
				}
			} 
			catch (Exception $e) 
			{
				$blnAnd = false;
			}

			if (strlen($strComparitor) > 0)
			{
				$strAnds .= $strFieldCode . $strComparitor . $strCriteria;
			}
		}
		
		if (strlen($strAnds) > 0)
		{
			$strAnds = "(" . $strAnds . ")"; 
		}
		
		if ((strlen($strOrs) > 0) && (strlen($strAnds) > 0))
		{
			$strOrs .= ' or ';
		}

		$strOrs .= $strAnds;
	}
	
	$strResult = $strOrs;

	return $strResult;
}

function getOrderBySQL($strDatasetType_a, $arrOrder_a)
{
	$strResult = "";
	
	if ($strDatasetType_a == "DF")
	{
		// returns the order by part without the words ORDER BY in front, i.e. column0, column1 desc
		foreach ($arrOrder_a as $objField) 
		{
			if (strlen($strResult) > 0)
			{
				$strResult .= ", ";
			}
			$strResult .= "column" . $objField['fieldid'];
			if (strlen($objField['sort']) > 0)
			{
				$strResult .=  " " . $objField['sort'];
			}
		}
	}
	else if ($strDatasetType_a == "INT")
	{
		$strResult = "counter"; // always export in counter order
	}
	else if ($strDatasetType_a == "SF")
	{
		// returns the order by part without the words ORDER BY in front, i.e. column0, column1 desc
		foreach ($arrOrder_a as $objField) 
		{
			if (strlen($strResult) > 0)
			{
				$strResult .= ", ";
			}
			$strResult .= "column" . $objField['fieldid'];
			if (strlen($objField['sort']) > 0)
			{
				$strResult .=  " " . $objField['sort'];
			}
		}
	}

	return $strResult;
}

function getUniqueCounter()
{
    return getGUID();
}

function strInClauseCreateFromStringList($strList_a, $strDelimimter_a)
{
	$strResult = "";
	$arrList = explode($strDelimimter_a, $strList_a);
	
	// for each array element, remove all ' and " then recombine them such as 'CODEA','CODEB'
	foreach ($arrList as $strItem)
	{
		$strItem = str_replace("'", "", $strItem);
		$strItem = str_replace('"', "", $strItem);
		
		if (strlen($strResult) > 0)
		{
			$strResult .= ",";
		}
		$strResult .= "'" . ff($strItem) . "'";
	}
	
	return $strResult;
}

function InList($strList_a, $strString_a)
{
	$blnResult = false;
	if (InStr(',' . $strList_a . ',', ',' . $strString_a . ',') >= 0)
	{
		$blnResult = true;
	}
	
	return $blnResult;
}

function massagePhoneNumber($str_a)
{
	$strResult = $str_a;
	
	$strResult = str_replace(' ', '', $strResult);
	$strResult = str_replace('(', '', $strResult);
	$strResult = str_replace(')', '', $strResult);
	
	return $strResult;
}

function strToArr($str_a, $intChars_a) 
{
    $arrResult = [];
    
    // Split the string into words based on spaces and tabs
    $arrWords = preg_split('/[\s]+/', $str_a);
    
    $strCurrentLine = '';
    
    foreach ($arrWords as $strWord) 
	{
        // Check if adding the next word exceeds the character limit
        if (strlen($strCurrentLine) + strlen($strWord) + 1 <= $intChars_a) 
		{
            // If current line is not empty, add a space before adding the next word
            if (strlen($strCurrentLine) > 0) 
			{
                $strCurrentLine .= ' ';
            }
            $strCurrentLine .= $strWord;
        } 
		else 
		{
            // If the current line is full, push it to the result array and start a new line
            if (strlen($strCurrentLine) > 0) 
			{
                $arrResult[] = $strCurrentLine;
            }
            $strCurrentLine = $strWord; // Start a new line with the current word
        }
    }
    
    // Add the last line if it's not empty
    if (strlen($strCurrentLine) > 0)
	{
        $arrResult[] = $strCurrentLine;
    }
    
    return $arrResult;
}

