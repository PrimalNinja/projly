<?php

// function summary:

// analyzeJSONFile($strContent_a, $strExpectedFileType_a)
// analyzeTextFile($strContent_a, $strExpectedFileType_a)
// convertFWtoCSV($strPath_a, $strFilename_a, $objFileFormat_a, $objFileFormatCSV_a)
// getFileFormatField($objFileFormat_a, $strFieldName_a)
// getFileFormatFieldValue($objFileFormat_a, $strFieldName_a)
// importCSV($objConn_a, $strFilename_a, $intHeaderRows_a, $strNumericFields_a)
// internalCSVFormatGet($objConn_a, $strClientID_a, $strFileFormatID_a)
// processImportFields($objConn_a, $strTableNameTemp_a, $objFileFormat_a)

function analyzeJSONFile($strContent_a, $strExpectedFileType_a)
{
    $objResult = array('truncated' => false, 'filetype' => 'unknown');
    
    // Try to decode JSON
    json_decode($strContent_a);
    $intJsonError = json_last_error();
    
    if ($intJsonError === JSON_ERROR_NONE)
    {
        // Valid JSON - use expected file type
        $objResult['filetype'] = $strExpectedFileType_a ?: 'unknown';
    }
    else
    {
        // Invalid JSON - keep as unknown but check for truncation
        $objResult['filetype'] = 'unknown';
        
        // Check if it's likely truncated
        if ($intJsonError === JSON_ERROR_SYNTAX || 
            $intJsonError === JSON_ERROR_STATE_MISMATCH ||
            $intJsonError === JSON_ERROR_CTRL_CHAR)
        {
            
            // Additional checks for truncation
            $strTrimmed = rtrim($strContent_a);
            $strLastChar = substr($strTrimmed, -1);
            
            // JSON should end with } or ]
            if ($strLastChar !== '}' && $strLastChar !== ']')
            {
                $objResult['truncated'] = true;
            }
            
            // Count brackets to see if they match
            $intOpenBraces = substr_count($strContent_a, '{');
            $intCloseBraces = substr_count($strContent_a, '}');
            $intOpenBrackets = substr_count($strContent_a, '[');
            $intCloseBrackets = substr_count($strContent_a, ']');
            
            if ($intOpenBraces !== $intCloseBraces || $intOpenBrackets !== $intCloseBrackets)
            {
                $objResult['truncated'] = true;
            }
        }
    }
    
    return $objResult;
}

function analyzeTextFile($strContent_a, $strExpectedFileType_a)
{
    $objResult = array('truncated' => false, 'filetype' => 'unknown');
    
    // Split content into lines
    $arrLines = preg_split('/\r?\n/', $strContent_a);
    $arrLines = array_filter($arrLines, 'strlen'); // Remove empty lines
    
    if (count($arrLines) < 1)
    {
        // No lines at all - unknown
        $objResult['format'] = 'unknown';
        $objResult['filetype'] = 'unknown';
    }
    else if (count($arrLines) < 5)
    {
        // Not enough lines to determine FW vs CSV, default to CSV
        $objResult['format'] = 'csv';
        $objResult['filetype'] = $strExpectedFileType_a ?: 'unknown';
    }
    else
    {
        // Check first 5 rows for consistent length (FW) vs variable (CSV)
        $arrLineLengths = array();
        for ($intI = 0; $intI < min(5, count($arrLines)); $intI++)
        {
            $arrLineLengths[] = strlen($arrLines[$intI]);
        }
        
        // If all lengths are the same, assume Fixed Width
        if (count(array_unique($arrLineLengths)) === 1)
        {
            $objResult['format'] = 'fw';
            $objResult['filetype'] = $strExpectedFileType_a ?: 'unknown';
        }
        else
        {
            $objResult['format'] = 'csv';
            $objResult['filetype'] = $strExpectedFileType_a ?: 'unknown';
        }
    }
    
    // Basic truncation check - see if last line seems incomplete
    if (count($arrLines) > 0)
    {
        $strLastLine = end($arrLines);
        // If last line is significantly shorter than average, might be truncated
        if (count($arrLines) >= 3)
        {
            $intTotalLength = 0;
            $intLineCount = min(5, count($arrLines) - 1); // Don't include last line in average
            
            for ($intI = 0; $intI < $intLineCount; $intI++)
            {
                $intTotalLength += strlen($arrLines[$intI]);
            }
            
            $intAverageLength = $intTotalLength / $intLineCount;
            
            // If last line is less than 50% of average length, might be truncated
            if (strlen($strLastLine) < ($intAverageLength * 0.5))
            {
                $objResult['truncated'] = true;
            }
        }
    }
    
    return $objResult;
}

// returns with the same named file in the same location
function convertFWtoCSV($strPath_a, $strFilename_a, $objFileFormat_a, $objFileFormatCSV_a)
{
    $strFilenameTemp = $strPath_a . $strFilename_a . '-temp.csv';

    // delete existing temp file if already exists
    if (file_exists($strFilenameTemp)) 
	{
        unlink($strFilenameTemp);
    }

    if (toBoolean(AUTO_DETECT_LINE_ENDINGS)) 
	{
        ini_set("auto_detect_line_endings", true);
    }

    // count how many fields in the output format
    $intFieldCount = 0;
    foreach ($objFileFormatCSV_a->fields as $objField) 
	{
        // default values
        if (intval('0' . $objField->position) > 0) 
		{
            $intFieldCount++;
        }
    }

    // copy data to new temp file
    $objInFile = fopen($strPath_a . $strFilename_a, "r");
    $objOutFile = fopen($strFilenameTemp, "w");

    while (($strInLine = fgets($objInFile)) !== false) 
	{
        $arrOutFields = array();
        for ($intFieldOut = 0; $intFieldOut < $intFieldCount; $intFieldOut++) 
		{
            $strValue = "";

            $intFieldNum = 0;
            $blnFound = false;
            foreach ($objFileFormatCSV_a->fields as $objFieldNew) 
			{
                if ($intFieldNum == $intFieldOut) 
				{
                    $blnFound = true;

                    //read value from old format
                    $strFieldNameNew = $objFieldNew->fieldname;
                    foreach ($objFileFormat_a->fields as $objFieldOld) 
					{
                        //read value from old format
                        $strFieldNameOld = $objFieldOld->fieldname;
                        if (strtolower($strFieldNameOld) == strtolower($strFieldNameNew)) 
						{
                            // read the value out of the input line
                            $intPosition = $objFieldOld->position - 1;
                            $intLength = $objFieldOld->length;
                            if (($intPosition >= 0) && ($intLength > 0)) 
							{
                                $strValue = substr($strInLine, $intPosition, $intLength);
                            }
                        }
                    }
                }
                $intFieldNum++;
            }

            if ($blnFound) 
			{
                // store value into new format
                $arrOutFields[$intFieldOut] = $strValue;
            }
        }

        fputcsv($objOutFile, $arrOutFields, ",", '"');
    }
    fclose($objOutFile);
    fclose($objInFile);

    // delete existing temp file if already exists
    if (file_exists($strFilenameTemp)) 
	{
        unlink($strPath_a . $strFilename_a);
        rename($strFilenameTemp, $strPath_a . $strFilename_a);
    }
}

// returns null if field isn't found
function getFileFormatField($objFileFormat_a, $strFieldName_a)
{
    $objResult = null;
    $objField = null;
    $arrFields = $objFileFormat_a->fields;

    $blnFound = false;
    $intI = 0;

    while (($intI < count($arrFields)) && (!$blnFound)) 
	{
        $objField = $arrFields[$intI];

        if ($objField->fieldname == $strFieldName_a) 
		{
            $objResult = $objField;
            $blnFound = true;
        }

        $intI++;
    }

    return $objResult;
}

// returns a literal value if not found based on the datatype which may be a default
function getFileFormatFieldValue($objFileFormat_a, $strFieldName_a)
{
    $strResult = "''";

    $objField = getFileFormatField($objFileFormat_a, $strFieldName_a);
    if ($objField !== null) 
	{
        $strDefault = "";
        if (property_exists($objField, 'defaultvalue')) 
		{
            $strDefault = $objField->defaultvalue;
        }

        if (intval('0' . $objField->position) == 0) 
		{
            if ($objField->datatype == "S") 
			{
                $strResult = "'" . $strDefault . "'";
            } 
			else if ($objField->datatype == "B") 
			{
                $strResult = "'" . $strDefault . "'";
            } 
			else if ($objField->datatype == "N") 
			{
                $strResult = $strDefault;
            } 
			else if ($objField->datatype == "D") 
			{
                if ($strDefault == "SYSDATE") 
				{
                    $strDefault = "str_to_date('" . getISODate() . "', '%Y-%m-%d')";
                    $strResult = $strDefault;
                } 
				else 
				{
                    $strResult = "'" . $strDefault . "'";
                }
            }
        } 
		else 
		{
            $strResult = 'column' . strval(intval(('0' . $objField->position) - 1));
        }
    }

    return $strResult;
}

function importCSV($objConn_a, $strFilename_a, $intHeaderRows_a, $strNumericFields_a)
{
    $strResult = "";
    $intFieldCount = 0;

    $strFilenameTemp = $strFilename_a . '-temp.csv';

    // count how many fields in the first row and validate that all records have the same field count
	if (toBoolean(AUTO_DETECT_LINE_ENDINGS)) 
	{
		ini_set("auto_detect_line_endings", true);
	}

    $objInFile = fopen($strFilename_a, "r");
    if (($arrInFields = fgetcsv($objInFile, 1000, ",")) !== false) 
	{
        $intFieldCount = count($arrInFields);
    }
    fclose($objInFile);

    // delete existing temp file if already exists
    if (file_exists($strFilenameTemp)) 
	{
        unlink($strFilenameTemp);
    }

    if ($intFieldCount > 0) 
	{
        // copy data to new temp file
        $objInFile = fopen($strFilename_a, "r");
        $objOutFile = fopen($strFilenameTemp, "w");

        while (($arrInFields = fgetcsv($objInFile, 1000, ",")) !== false) 
		{
            $intFieldCountTemp = count($arrInFields);

            $arrOutFields = array($intFieldCount);
            for ($intI = 0; ($intI < $intFieldCount) && ($intI < $intFieldCountTemp); $intI++) 
			{
                $arrOutFields[$intI] = $arrInFields[$intI];
            }

            fputcsv($objOutFile, $arrOutFields, ",", '"');
        }
        fclose($objOutFile);
        fclose($objInFile);

        // work out column names
        $strTableNameTemp = "temp_" . date("d_m_Y_H_i_s") . '_' . str_replace('-', '_', getGUID());

        $strColumns = "";
        $strFields = "";
        for ($intI = 0; $intI < $intFieldCount; $intI++) 
		{
            if ($intI == 0) 
			{
                $strColumns .= "column" . $intI;
                $strFields .= "column" . $intI . " text";
            } 
			else 
			{
                $strColumns .= ",column" . $intI;
                $strFields .= ",column" . $intI . " text";
            }
        }

        // create table if not exists ( collate utf8_general_ci )
        $strSQL =
            "
create table if not exists ~TABLENAMETEMP~ (~FIELDS~) engine=MyISAM default charset=utf8;
";
        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
        $strSQL = str_replace("~FIELDS~", ff($strFields), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // import the file into the database
        if (toBoolean(DBSYSTEM_LOADDATA)) 
		{
            // bulk import
            $strIgnoreLines = "";
            if ($intHeaderRows_a > 0) 
			{
                $strIgnoreLines = " ignore " . $intHeaderRows_a . " lines ";
            }

            if (toBoolean(DBSYSTEM_LOCALINFILE)) 
			{
                $strSQL =
                    "
load data LOCAL infile '~FILENAME~' into table ~TABLENAMETEMP~
fields terminated by '~SEPARATORCHAR~'
optionally enclosed by '~ENCLOSURECHAR~'
escaped by '~ESCAPECHAR~' ~IGNORELINES~ (~COLUMNS~)
";
            } 
			else 
			{
                $strSQL =
                    "
load data infile '~FILENAME~' into table ~TABLENAMETEMP~
fields terminated by '~SEPARATORCHAR~'
optionally enclosed by '~ENCLOSURECHAR~'
escaped by '~ESCAPECHAR~' ~IGNORELINES~ (~COLUMNS~)
";
            }
            $strSQL = str_replace("~FILENAME~", $strFilenameTemp, $strSQL);
            $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
            $strSQL = str_replace("~SEPARATORCHAR~", ",", $strSQL);
            $strSQL = str_replace("~ENCLOSURECHAR~", '"', $strSQL);
            $strSQL = str_replace("~ESCAPECHAR~", "\\\\", $strSQL);
            $strSQL = str_replace("~IGNORELINES~", $strIgnoreLines, $strSQL);
            $strSQL = str_replace("~COLUMNS~", ff($strColumns), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        } 
		else 
		{
            // slow iteration method of importing
            $intRow = 1;
            if (($objHandle = fopen($strFilenameTemp, "r")) !== false) 
			{
                while (($arrData = fgetcsv($objHandle, 1000, ",", '"', "\\")) !== false) 
				{
                    if ($intRow > $intHeaderRows_a) 
					{
                        $strValues = "";
                        foreach ($arrData as $varDataItem) 
						{
                            $strValue = "";
                            if (is_string($varDataItem)) 
							{
                                $strValue = "'" . ff($varDataItem) . "'";
                            } 
							else 
							{
                                $strValue = ff($varDataItem);
                            }

                            if (strlen($strValues) > 0) 
							{
                                $strValues .= "," . $strValue;
                            } 
							else 
							{
                                $strValues = $strValue;
                            }
                        }
                        $strSQL = "insert into ~TABLENAMETEMP~ values (" . $strValues . ")";
                        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
                        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                    }

                    $intRow++;
                }
                fclose($objHandle);
            }
        }

        $arrNumericFields = explode(',', $strNumericFields_a);
        foreach ($arrNumericFields as $strNumericField) 
		{
            if (strlen(trim($strNumericField)) > 0) 
			{
                $strSQL = "alter table ~TABLENAMETEMP~ add column ~FIELDNAME~ bigint(20) NULL";
                $strSQL = str_replace("~FIELDNAME~", ff($strNumericField), $strSQL);
                $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

                $strSQL = "alter table ~TABLENAMETEMP~ add index (~FIELDNAME~)";
                $strSQL = str_replace("~FIELDNAME~", ff($strNumericField), $strSQL);
                $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            }
        }

        // add error processing fields
        $strSQL = "alter table ~TABLENAMETEMP~ add column is_error varchar(1) NULL";
        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "alter table ~TABLENAMETEMP~ add column errordescription varchar(100) NULL";
        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "alter table ~TABLENAMETEMP~ add column counter bigint(20) NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (counter)";
        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        $strResult = $strTableNameTemp;
    }

    // delete existing temp file if already exists
    if (file_exists($strFilenameTemp)) 
	{
        unlink($strFilenameTemp);
    }

    return $strResult;
}

// return the internal format to use for importing
// this is used when importing FIXED WIDTH FILE FORMATS so that it can 'internally' convert to a CSV file.  The internal formats are NOT visible to the user.
function internalCSVFormatGet($objConn_a, $strClientID_a, $strFileFormatID_a)
{
    $objResult = null; // internal file format

    if (dependencies('import/fileFormatFetch,import/fileFormatMassage')) 
	{
        $strSQL = "select ft.id returnvalue from core_tblfileformat ff, ref_core_tblfiletype ft where ff.filetype_id = ft.id and ff.id = ~FILEFORMATID~";
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        $strFileTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "select ft.code returnvalue from core_tblfileformat ff, ref_core_tblfiletype ft where ff.filetype_id = ft.id and ff.id = ~FILEFORMATID~";
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        $strFileTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "select formattype_id returnvalue from core_tblfileformat where id = ~FILEFORMATID~";
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        $strFormatTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        //read the user file format
        $objFileFormat = fileFormatFetch($objConn_a, $strClientID_a, $strFileFormatID_a, false);

        //read the internal CSV format (from the externally stored json file)
        $strFilename = $strFileTypeCode . '-internal.json';
        $strJSON = loadFile(FILEFORMAT_PATH . $strFilename);
        $arrJSON = json_decode($strJSON, true);
        $objResult = fileFormatMassage($objConn_a, $strFileTypeID, $strFormatTypeID, $arrJSON, false);

        $objResult->headerrows = $objFileFormat->headerrows;
        $objResult->formattype = "csv";

        //create a merged format based on internal but with defaultvalue, format, exclusions and translations from user
        foreach ($objResult->fields as $objField) 
		{
            foreach ($objFileFormat->fields as $objFieldUser) 
			{
                if ($objFieldUser->fieldname == $objField->fieldname) 
				{
                    $objField->defaultvalue = $objFieldUser->defaultvalue;
                    $objField->format = $objFieldUser->format;
                    $objField->exclusions = $objFieldUser->exclusions;
                    $objField->translations = $objFieldUser->translations;
                }
            }
        }
    }

    return $objResult;
}

// currently deletes records where mandatory fields are not provided
// improve escaping if necessary (we might put additional JSON validation in future instead when users can create formats)
function processImportFields($objConn_a, $strTableNameTemp_a, $objFileFormat_a)
{
    $blnResult = true;
    $strSysDate = getISODate();

    foreach ($objFileFormat_a->fields as $objField) 
	{
        // default values
        if (intval('0' . $objField->position) > 0) 
		{
            $strField = "column" . strval(intval('0' . $objField->position) - 1);

            if (property_exists($objField, 'defaultvalue')) 
			{
                $strDefault = $objField->defaultvalue;
                if (strlen($strDefault) > 0) 
				{
                    $strFormat = '';

                    if ($strDefault == "SYSDATE") 
					{
                        $strDefault = $strSysDate;
                        if (property_exists($objField, 'format')) 
						{
                            $strFormat = $objField->format;
                            $strDefault = "str_to_date('" . $strSysDate . "', '%Y-%m-%d')";
                        }

                        $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set ~FIELDNAME~ = date_format(~DEFAULT~, '~FORMAT~') where ~FIELDNAME~ is null or ~FIELDNAME~ = ''";
                        $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                        $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                        $strSQL = str_replace("~DEFAULT~", ff($strDefault), $strSQL);
                        $strSQL = str_replace("~FORMAT~", ff($strFormat), $strSQL);
                        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                    } 
					else 
					{
                        $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set ~FIELDNAME~ = '~DEFAULT~' where ~FIELDNAME~ is null or ~FIELDNAME~ = ''";
                        $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                        $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                        $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                        $strSQL = str_replace("~DEFAULT~", ff($strDefault), $strSQL);
                        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                    }
                }
            }

            // translations
            if (property_exists($objField, 'translations')) 
			{
                foreach ($objField->translations as $objTranslation) 
				{
                    $strFrom = $objTranslation->from;
                    $strTo = $objTranslation->to;
                    $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set ~FIELDNAME~ = '~TO~' where ~FIELDNAME~ = '~FROM~'";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~FROM~", ff($strFrom), $strSQL);
                    $strSQL = str_replace("~TO~", ff($strTo), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }

            // multipliers
            if (property_exists($objField, 'multiplier')) 
			{
                $strMultiplier = $objField->multiplier;
                if (strlen(trim($strMultiplier)) > 0) 
				{
                    $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set ~FIELDNAME~ = ~FIELDNAME~*~MULTIPLIER~";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~MULTIPLIER~", ff($strMultiplier), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }

            // formats (note: at present only dates are catered for)
            if (property_exists($objField, 'format')) 
			{
                $strFormat = $objField->format;
                if (strlen(trim($strFormat)) > 0) 
				{
                    $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set ~FIELDNAME~ = str_to_date(~FIELDNAME~, '~FORMAT~')";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~FORMAT~", ff($strFormat), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }

            // inclusions (note: delete what's not included in the following)
            if (property_exists($objField, 'inclusions')) 
			{
                $strFilterList = '';
                foreach ($objField->inclusions as $objFilter) 
				{
                    $strFilter = $objFilter->inclusion;
                    if (strlen(trim($strFilterList)) > 0) 
					{
                        $strFilterList .= ",";
                    }
                    $strFilterList .= "'" . ff($strFilter) . "'";
                }

                if (strlen($strFilterList) > 0) 
				{
                    $strSQL = "delete from ~DATABASETEMP~.~TABLENAMETEMP~ where ~FIELDNAME~ not in (~FILTERLIST~)";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~FILTERLIST~", ff($strFilterList), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }

            // exclusions (note: delete what's included in the following)
            if (property_exists($objField, 'exclusions')) 
			{
                $strFilterList = '';
                foreach ($objField->exclusions as $objFilter) 
				{
                    $strFilter = $objFilter->exclusion;
                    if (strlen(trim($strFilterList)) > 0) 
					{
                        $strFilterList .= ",";
                    }
                    $strFilterList .= "'" . ff($strFilter) . "'";
                }

                if (strlen($strFilterList) > 0) 
				{
                    $strSQL = "delete from ~DATABASETEMP~.~TABLENAMETEMP~ where ~FIELDNAME~ in (~FILTERLIST~)";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~FILTERLIST~", ff($strFilterList), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }

            // mandatory
            if (property_exists($objField, 'mandatory')) 
			{
                $blnMandatory = toBoolean($objField->mandatory);
                if ($blnMandatory) 
				{
                    $strSQL = "update ~DATABASETEMP~.~TABLENAMETEMP~ set is_error = 'Y', errordescription = '~ERRORDESCRIPTION~' where is_error is null and (~FIELDNAME~ is null or ~FIELDNAME~ = '')";
                    $strSQL = str_replace('~DATABASETEMP~', DBSYSTEMTEMP_DATABASENAME, $strSQL);
                    $strSQL = str_replace("~TABLENAMETEMP~", ff($strTableNameTemp_a), $strSQL);
                    $strSQL = str_replace("~FIELDNAME~", ff($strField), $strSQL);
                    $strSQL = str_replace("~ERRORDESCRIPTION~", ff("Mandatory field '" . $objField->fieldname . "' is required."), $strSQL);
                    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                }
            }
        }
    }

    return $blnResult;
}
