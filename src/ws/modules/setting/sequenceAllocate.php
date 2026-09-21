<?php

// allocate a sequence
// example sequences settings:
//        RNS ID:         {"type":"rns","prefix":"%RNSIDPREFIX%","sequence":{"start":1,"end":0,"nextid":9,"length":0,"checkdigit":"modulus10"},"suffix":"","numeric":"false","totallength":0,"checkdigit":""}
//        RNS ID:         {"type":"rns","prefix":"%RNSIDPREFIX%","sequence":{"start":1,"nextid":9,"checkdigit":"modulus10"},"numeric":"false"}
function sequenceAllocate($objConn_a, $strSequenceID_a, $strModuleCode_a, $strSequenceCode_a, $strClientID_a, $strEntered_a, $strRNSIDPrefix_a, $strRNSCode_a, $strSenderOpAccount_a, $strNotes_a)
{
	$strTableNameCheckDigitType = getTableNameEntity("checkdigittype", false);
	$strTableNameSequence = getTableNameEntity("sequence", false);
	$strTableNameSequenceType = getTableNameEntity("sequencetype", false);

    $strResult = '';
	$strSequenceID = $strSequenceID_a;
	$strSequenceType = '';
    if (dependencies('setting/settingGet') &&
        dependencies('checkdigit/checkDigitModulus10EAN14,checkdigit/checkDigitTollPriorityModulus10')) 
	{
		$strEntered = $strEntered_a;
		if ($strEntered == "(AUTO)")
		{
			$strEntered = "";
		}

        // keep sequence transaction small
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		// get the appropriate sequence setting
		if (strlen($strSequenceID) == 0)
		{
			$strSequenceID = settingGet($objConn_a, $strModuleCode_a, $strSequenceCode_a, $strClientID_a, '', '', '', $strNotes_a);
		}
		
		$strSequenceTypeID = "";	// default
		$strSequenceType = 'guid';	// default
		
		if (strlen($strSequenceID) > 0)
		{
			$strSQL = "select jsondata returnvalue from ~TABLENAMESEQUENCE~ where id = ~ID~";
			$strSQL = str_replace('~TABLENAMESEQUENCE~', ff($strTableNameSequence), $strSQL);
			$strSQL = str_replace('~ID~', ff($strSequenceID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			$arrJSONData = json_decode($strJSONData, true);

			$strSequenceTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'SEQUENCETYPE');
			$strSequenceTypeDescription = formDescriptionGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'SEQUENCETYPE');
			$strSequenceType = strtolower(dbReadValueByID($objConn_a, $strTableNameSequenceType, "code", $strSequenceTypeID, __FUNCTION__));
		}

// return $strModuleCode_a . ":" . $strSequenceCode_a . ":" . $strSequenceTypeID . ":" . $strSequenceTypeDescription;

		$intSeq = 0;
//debug('type:'.$strSequenceType . ', setting:'.$strJSONData);

		if (($strSequenceType == 'standard') || ($strSequenceType == 'standardoverride') || ($strSequenceType == 'rns')) 
		{
			$strSeqStart = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'START');
			$strSeqStart = (strlen($strSeqStart) > 0) ? $strSeqStart : "1";
			$intSeqStart = intval($strSeqStart, 10);

			$strSeqEnd = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'END');
			$strSeqEnd = (strlen($strSeqEnd) > 0) ? $strSeqEnd : "0";
			$intSeqEnd = intval($strSeqEnd, 10);

			$blnHasSequence = false;
			$strSeqNext = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'NEXT');
			if (strlen($strSeqNext) > 0)
			{
				$intSeqNextID = intval($strSeqNext, 10);
				$blnHasSequence = true;
			}

			if ($blnHasSequence)
			{
				// get current sequence
				$intSeq = $intSeqNextID;

				// save next sequence value
				$intNewSeq = $intSeq + 1;
				if (($intSeqEnd > 0) && ($intNewSeq >= $intSeqEnd)) 
				{
					$intNewSeq = $intSeqStart;
				}
			
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', "NEXT", $intNewSeq);

				$strJSONData = json_encode($arrJSONData);

				$strSQL = "update ~TABLENAMESEQUENCE~ set jsondata = '~JSONDATA~' where id = ~ID~";
				$strSQL = str_replace('~TABLENAMESEQUENCE~', ff($strTableNameSequence), $strSQL);
				$strSQL = str_replace('~ID~', ff($strSequenceID), $strSQL);
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				logSetting("sequenceAllocate success: id: " . $strSequenceID . ", sql: " . $strSQL);	

				exposeEntityData($objConn_a, 'SYSTEMFORM', 'SEQUENCE', $strSequenceID, $strJSONData);
			}
		}            
		dbEndTrans($objConn_a, __FUNCTION__);

        if ($strSequenceType == 'guid') 
		{
            $strResult = getGUID();
        } 
		else if ($strSequenceType == 'manual') 
		{
            $strResult = $strEntered;
        } 
		else if (($strSequenceType == 'standard') || ($strSequenceType == 'standardoverride') || ($strSequenceType == 'rns')) 
		{
			$strPrefix = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'PREFIX');
			$strSuffix = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'SUFFIX');
			$blnNumeric = toBoolean(formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'ISNUMERIC'));

			$strTotalLength = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'TOTALLENGTH');
			$strTotalLength = (strlen($strTotalLength) > 0) ? $strTotalLength : "0";
			$intTotalLength = intval($strTotalLength, 10);

			$strOverAllCheckdigitTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'OVERALLCHECKDIGIT');
			$strOverAllCheckdigitType = strtolower(dbReadValueByID($objConn_a, $strTableNameCheckDigitType, "code", $strOverAllCheckdigitTypeID, __FUNCTION__));

            // placeholders
            $strPrefix = str_replace('%RNSID%', $strRNSCode_a, $strPrefix);
            $strPrefix = str_replace('%RNSIDPREFIX%', $strRNSIDPrefix_a, $strPrefix);
            //$strPrefix = str_replace('%SENDEROPACCOUNT%', $strSenderOpAccount_a, $strPrefix);

            $strResult = $strPrefix;

            $intSeqLength = 0;
            $strSeqCheckdigitType = '';
            if ($blnHasSequence) 
			{
				$strSeqLength = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'LENGTH');
				$strSeqLength = (strlen($strSeqLength) > 0) ? $strSeqLength : "0";
				$intSeqLength = intval($strSeqLength, 10);

				$strSeqCheckdigitTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, 'g9a1757b7-1a28-408e-8f6f-201a26e9b9ac', 'SEQUENCECHECKDIGIT');
				$strSeqCheckdigitType = strtolower(dbReadValueByID($objConn_a, $strTableNameCheckDigitType, "code", $strSeqCheckdigitTypeID, __FUNCTION__));

                // start to build the sequence
                $strSeq = strval($intSeq);

                if ($intSeqLength > 0) 
				{
                    // sequence checkdigit
                    if (strlen($strSeqCheckdigitType) > 0) 
					{
                        if ((strlen($strSeq) + 1) < $intSeqLength) 
						{
                            $strSeq = str_repeat('0', ($intSeqLength - (strlen($strSeq) + 1))) . $strSeq;
                        }
                    } 
					else 
					{
                        if (strlen($strSeq) < $intSeqLength) 
						{
                            $strSeq = str_repeat('0', ($intSeqLength - strlen($strSeq))) . $strSeq;
                        }
                    }
                }

                // create a sequence checkdigit if necessary
                $strSeqCheckDigit = '';
                if (strtolower($strSeqCheckdigitType) == 'modulus10ean14') 
				{
                    $strSeqCheckDigit = checkDigitModulus10EAN14($strSeq);
                } 
				else if (strtolower($strSeqCheckdigitType) == 'modulus10') 
				{
                    $strSeqCheckDigit = checkDigitTollPriorityModulus10($strSeq);
                }

                // add the checkdigit to the sequence
                $strSeq .= $strSeqCheckDigit;
                $strResult .= $strSeq;
            }

            // construct result
            $strResult .= $strSuffix;

            if ($intTotalLength > 0) 
			{
                // overall checkdigit
                if (strlen($strOverAllCheckdigitType) > 0) 
				{
                    if ((strlen($strResult) + 1) < $intTotalLength) 
					{
                        $strResult = str_repeat('0', ($intTotalLength - (strlen($strResult) + 1))) . $strResult;
                    }
                } 
				else 
				{
                    if (strlen($strResult) < $intTotalLength) 
					{
                        $strResult = str_repeat('0', ($intTotalLength - strlen($strResult))) . $strResult;
                    }
                }
            }

            // create a overall checkdigit if necessary
            $strOverallCheckDigit = '';
            if (strtolower($strOverAllCheckdigitType) == 'modulus10ean14') 
			{
                $strOverallCheckDigit = checkDigitModulus10EAN14($strResult);
            } 
			else if (strtolower($strOverAllCheckdigitType) == 'modulus10') 
			{
                $strOverallCheckDigit = checkDigitTollPriorityModulus10($strResult);
            }

            // add the checkdigit to the result
            $strResult .= $strOverallCheckDigit;

            // check the result is numeric if necessary
            if (($blnNumeric) && (!is_numeric($strResult))) 
			{
                safetyDie('non numeric sequence during sequence allocation');
            }
        } 
		else 
		{
            // default back to guid
            $strResult = getGUID();
        }
    }    
//debug('type:' . $strSequenceType . ', module:' . $strModuleCode_a . ', settingid:' . $strSettingValueID . ', setting:' . $strSequenceCode_a  . ', clientid:' . $strClientID_a . ', value:' . print_r($arrSetting, true) . ', result:' . $strResult);
    return $strResult;
}
