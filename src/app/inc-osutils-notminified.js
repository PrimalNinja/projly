/*jsl:option explicit*/

// ====================================================================================
// AWAFOS Utils v20240412 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function massageKeyword(str_a)
{
	var arrDateFormats = [
		/\b(\d{4})[-/](\d{1,2})[-/](\d{1,2})\b/, // yyyy-mm-dd or yyyy/mm/dd
		/\b(\d{1,2})[-/](\d{1,2})[-/](\d{4})\b/, // dd-mm-yyyy or dd/mm/yyyy or mm-dd-yyyy or mm/dd/yyyy
		/\b(\d{1,2})[-/](\d{1,2})\b/ // dd-mm or dd/mm
	];

	var arrMatch;
	var strResult = str_a;
	for (var intI = 0; intI < arrDateFormats.length; intI++)
	{
		arrMatch = str_a.match(arrDateFormats[intI]);
		if (arrMatch)
		{
			break;
		}
	}

	if (arrMatch)
	{
		var strYear, strMonth, strDay;
		if (arrMatch[1].length === 4)
		{ // yyyy-mm-dd or yyyy/mm/dd
			strYear = arrMatch[1];
			strMonth = arrMatch[2];
			strDay = arrMatch[3];
		}
		else if (arrMatch[3] && arrMatch[3].length === 4)
		{ // dd-mm-yyyy or dd/mm/yyyy or mm-dd-yyyy or mm/dd/yyyy
			if (parseInt(arrMatch[1]) > 12 || parseInt(arrMatch[2]) > 12)
			{ // assume dd-mm-yyyy or dd/mm/yyyy
				strDay = arrMatch[1];
				strMonth = arrMatch[2];
			}
			else
			{ // mm-dd-yyyy or mm/dd/yyyy
				strMonth = arrMatch[1];
				strDay = arrMatch[2];
			}
			strYear = arrMatch[3];
		}
		else
		{ // dd-mm or dd/mm
			strDay = arrMatch[1];
			strMonth = arrMatch[2];
			var objDate = new Date();
			strYear = objDate.getFullYear().toString();
		}

		strResult = strYear + '-' + ('0' + strMonth).slice(-2) + '-' + ('0' + strDay).slice(-2);
	}

	return strResult;
}
