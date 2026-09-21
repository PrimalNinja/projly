<?php

// massage file format fields
function fileFormatMassageFields($objConn_a, $strFileFormatID_a, $arrFields_a, $blnRequireIDs_a)
{
    $strTableNameFileDateType = getTableNameEntity('filedatatype', false);
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);    
    $strTableNameFileFormatField = getTableNameEntity('fileformat_field', false);
    $strTableNameFileFieldExclusion = getTableNameEntity('filefieldexclusion', false);
    $strTableNameFileFieldMapping = getTableNameEntity('filefieldmapping', false);

    $arrResult = array();

	$strSQL = "select f.id, gdca0c616_9e33_4f80_adb1_c964e9f44713_code code, gdca0c616_9e33_4f80_adb1_c964e9f44713_description description, f.is_enabled, f.jsondata, d.code filedatatype from ~TABLENAMEFILEFORMATFIELD~ f, ~TABLENAMEFILEDATATYPE~ d where d.id=f.filedatatype_id and f.fileformat_id = ~FILEFORMATID~ order by cast(gdca0c616_9e33_4f80_adb1_c964e9f44713_position as unsigned)";
	$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
	$strSQL = str_replace('~TABLENAMEFILEDATATYPE~', ff($strTableNameFileDateType), $strSQL);
	$strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);

	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	$intRowNum = 1;

	while ($arrRow = dbReadRecord($objResult)) 
	{
		$arrFieldNew = array();
		$objField = null;
		$arrTranslations = array();
		$arrExclusions = array();
		
		$strFieldName = $arrRow['code'];            
		$strCode = $arrRow['code'];
		$strDescription = $arrRow['description'];
		$strDataType = $arrRow['filedatatype'];        
		
		// find field in decoded JSON
		if (count($arrFields_a) > 0)
		{
			foreach ($arrFields_a as $objFieldTemp) 
			{
				if ($objFieldTemp["fieldname"] == $strFieldName) {
					$objField = $objFieldTemp;
					break;
				}
			}
		}

		if ($blnRequireIDs_a)
		{
			$arrFieldNew['id'] = secureEntityValue('FILEFORMAT_FIELD', $arrRow['id']);
			$arrFieldNew['rownum'] = $intRowNum;
		}

		if ($objField == null) 
		{           
			$arrJSONData = json_decode($arrRow['jsondata'], true);

			$strFormat = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'FORMAT');
			$strPosition = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'POSITION');
			$strLength = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'LENGTH');
			$strMultiplier = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'MULTIPLIER');
			$strIsMandatory = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'IS_MANDATORY');
			$strDefault = formValueGetBySectionCodeFieldCode($arrJSONData, 'gdca0c616-9e33-4f80-adb1-c964e9f44713', 'DEFAULT');

			$arrFieldNew['code'] = $strCode;
			$arrFieldNew['datatype'] = $strDataType;
			$arrFieldNew['fieldname'] = $strFieldName;
			$arrFieldNew['format'] = $strFormat;
			$arrFieldNew['description'] = $strDescription;
			$arrFieldNew['position'] = (string) $strPosition;
			$arrFieldNew['length'] = $strLength;
			$arrFieldNew['multiplier'] = $strMultiplier;
			$arrFieldNew['mandatory'] = $strIsMandatory;
			$arrFieldNew['defaultvalue'] = $strDefault;
		} 
		else 
		{                
			// supress zeroed positions
			$intPosition = intval(elementString($objField, 'position', ''));
			$strPosition = "";

			$intLength = intval(elementString($objField, 'length', ''));
			$strLength = "";

			if ($intPosition > 0) 
			{
				$strPosition = strval($intPosition);

				if ($intLength > 0) 
				{
					$strLength = strval($intLength);
				}
			} 
			else 
			{
				$strLength = "";
			}
			
			$arrFieldNew['fieldname'] = elementString($objField, 'fieldname', '');
			$arrFieldNew['description'] = elementString($objField, 'description', '');
			$arrFieldNew['code'] = elementString($objField, 'code', '');
			$arrFieldNew['datatype'] = elementString($objField, 'datatype', '');
			$arrFieldNew['mandatory'] = elementString($objField, 'mandatory', '');                
			$arrFieldNew['defaultvalue'] = elementString($objField, 'defaultvalue', '');
			$arrFieldNew['multiplier'] = elementString($objField, 'multiplier', '');
			$arrFieldNew['format'] = elementString($objField, 'format', '');
			$arrFieldNew['position'] = $strPosition;
			$arrFieldNew['length'] = $strLength;                
		}

		$strSQL = "select id, g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_from mapfrom, g3b85cacf_9b3f_464b_b76b_2f2fa7b5fcc0_to mapto from ~TABLENAMEFILEFIELDMAPPING~ where fileformat_field_id = ~FILEFORMATFIELDID~";
		$strSQL = str_replace('~TABLENAMEFILEFIELDMAPPING~', ff($strTableNameFileFieldMapping), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDID~', ffn($arrRow['id']), $strSQL);
		$objResultFieldMapping = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		$intTranslation = 1;
		while ($arrRowFieldMapping = dbReadRecord($objResultFieldMapping)) 
		{
			$arrTranslationNew = array();
			if ($blnRequireIDs_a) 
			{
				$arrTranslationNew['id'] = secureEntityValue('FILEFIELDMAPPING', $arrRowFieldMapping['id']);
				$arrTranslationNew['rownum'] = $intTranslation;
			}                
			$arrTranslationNew['from'] = $arrRowFieldMapping['mapfrom'];
			$arrTranslationNew['to'] = $arrRowFieldMapping['mapto'];
			$arrTranslations[] = $arrTranslationNew;
			$intTranslation++;
		}
		dbCloseRecordset($objResultFieldMapping);

		$strSQL = "select id, g44107c60_2977_4385_9eb0_2bfc71bb76e4_exclusion exclusion from ~TABLENAMEFILEFIELDEXCLUSION~ where fileformat_field_id = ~FILEFORMATFIELDID~";
		$strSQL = str_replace('~TABLENAMEFILEFIELDEXCLUSION~', ff($strTableNameFileFieldExclusion), $strSQL);
		$strSQL = str_replace('~FILEFORMATFIELDID~', ffn($arrRow['id']), $strSQL);
		$objResultFieldExclusion = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		$intExclusion = 1;
		while ($arrRowFieldExclusion = dbReadRecord($objResultFieldExclusion)) 
		{
			$arrExclusionNew = array();
			if ($blnRequireIDs_a) 
			{
				$arrExclusionNew['id'] = secureEntityValue('FILEFIELDEXCLUSION', $arrRowFieldExclusion['id']);
				$arrExclusionNew['rownum'] = $intExclusion;
			}
			$arrExclusionNew['exclusion'] = $arrRowFieldExclusion['exclusion'];
			$arrExclusions[] = $arrExclusionNew;
			$intExclusion++;
		}
		dbCloseRecordset($objResultFieldExclusion);

		$arrFieldNew['translations'] = $arrTranslations;
		$arrFieldNew['exclusions'] = $arrExclusions;

		$arrResult[] = $arrFieldNew;
		$intRowNum++;
	}
	dbCloseRecordset($objResult);

    return $arrResult;
}