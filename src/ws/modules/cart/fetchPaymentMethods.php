<?php

// fetch active payment methods
function fetchPaymentMethods($objConn_a, $blnSecure_a)
{
    $arrResult = array();

	if (dependencies('entity/dataaccess/paymentmethod'))
	{
		$arrCustomWhere = [["s", "is_enabled", "Y"]];
		$strOrderBy = "cast(ffb775d2a9_59b7_453b_91ce_7621548ffe81_displayorder as unsigned), description";
		$objResult = fetch_paymentmethod($objConn_a, "id, code, description, is_enabled", "", $arrCustomWhere, $strOrderBy);
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strPaymentMethodID = $arrRow['id'];
			$strCode = $arrRow['code'];
			$strDescription = $arrRow['description'];
			$strIsEnabled = $arrRow['enabled'];
			$strJSONData = $arrRow['jsondata'];
			$arrJSONData = json_decode($strJSONData, true);
			
			$strNotes = formValueGetBySectionCodeFieldCode($arrJSONData, "ffb775d2a9-59b7-453b-91ce-7621548ffe81", "NOTES");
			$strIsCC = formValueGetBySectionCodeFieldCode($arrJSONData, "ffb775d2a9-59b7-453b-91ce-7621548ffe81", "ISCC");

			if ($blnSecure_a)
			{
				$strPaymentMethodID = secureEntityValue('PAYMENTMETHOD', $strPaymentMethodID);
			}
			
			$arrResult[] = array(
				"id" => $strPaymentMethodID,
				"code" => $strCode,
				"description" => $strDescription,
				"notes" => $strNotes,
				"enabled" => $strIsEnabled,
				"is_cc" => $strIsCC,
				"paymentproviderdescription" => PAYMENTPROVIDER_DESCRIPTION
			);
		}
		dbCloseRecordset($objResult);
	}

    return $arrResult;
}
