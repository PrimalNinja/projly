<?php
// validate a setting & find it's location (entity & section)
function settingFind($objConn_a, $strModuleCode_a, $strSettingCode_a, $strClientID_a, $strUserID_a, $strPrinterID_a, $strDeviceID_a, $strNotes_a)
{
	$strResult = "";	
	
	// result can be prefixed by:
	// 	CLIENTSETTING, DEVICE, DISPATCHSETTING, USERSETTING or the special ones: SEQUENCE

	// legacy settings, the following functions honor the legacy settings: settingFind, settingGet, settingPut
	// MAKE SURE ALL 3 FUNCTIONS COMMENT IS IDENTICAL
	
	// current scopes:
	//	CL - 		Client
	//	CLU - 		Client User
	//	CLP -		Client Printer
	//	CLD - 		Client Device
	
	$strSettingCode = $strModuleCode_a . '_' . $strSettingCode_a;
	
	switch($strSettingCode)
	{
																															 //CL U A P D C S
// CLIENT SETTINGS FORM
		case "CORE_RNSIDPFX": $strResult = "CL_CLIENTSETTING_GENERALSETTINGS_RNSIDPFX_V"; break;		// rns id prefix		Y N N N N N N
		case "CORE_SYSTEMID": $strResult = "CL_CLIENTSETTING_GENERALSETTINGS_SYSTEMID_V"; break;		// default country		Y N N N N N N
		case "CORE_DEFCOUNTRY": $strResult = "CL_CLIENTSETTING_GENERALSETTINGS_DEFAULTCOUNTRY_V"; break;// default country		Y N N N N N N
		case "CORE_ENABLEBRANCHES": $strResult = "CL_CLIENTSETTING_GENERALSETTINGS_ENABLEBRANCHES_V"; break;// enable branches	Y N N N N N N
		case "CORE_THEME": $strResult = "CL_CLIENTSETTING_GENERALSETTINGS_THEME_VD"; break; 			// selected theme		Y N N N N N N

		case "CORE_BNAME": $strResult = "CL_CLIENTSETTING_BUSINESSSETTINGS_BNAME_V"; break;				// business name		Y N N N N N N
		case "CORE_BNUM": $strResult = "CL_CLIENTSETTING_BUSINESSSETTINGS_BNUM_V"; break;				// business number		Y N N N N N N
		case "CORE_BPHONE": $strResult = "CL_CLIENTSETTING_BUSINESSSETTINGS_BPHONE_V"; break;			// business phone		Y N N N N N N
		case "CORE_BADDR": $strResult = "CL_CLIENTSETTING_BUSINESSSETTINGS_BADDR_V"; break;				// business addr		Y N N N N N N

// DEVICE FORM
		case "CORE_PQOTH": $strResult = "CLD_DEVICE_QUEUEING_PQ4OR_VD"; break;							// report printer q		Y N N N Y N N

		case "CORE_P4OTH": $strResult = "CLD_DEVICE_PRINTING_P4OR_V"; break;							// report printer		Y N N N Y N N
		case "CORE_PRINTFROMQ": $strResult = "CLD_DEVICE_PRINTING_PRINTFROMQ_V"; break;					// print from queues	Y N N N Y N N

// SEQUENCE SETTING FORM
		// i.e. SEQUENCE_<SEQUENCECODE>
		case "CORE_DEMOID": $strResult = "CL_SEQUENCE_DEMOID"; break;									// actual sequence		Y N N N N N N
		case "CORE_INVOICEID": $strResult = "CL_SEQUENCE_INVOICEID"; break;								// actual sequence		Y N N N N N N
		case "CORE_RNSID": $strResult = "CL_SEQUENCE_RNSID"; break;										// actual sequence		Y N N N N N N
		case "CORE_USERID": $strResult = "CL_SEQUENCE_USERID"; break;									// actual sequence		Y N N N N N N
		case "CORE_WORKQUEUEID": $strResult = "CL_SEQUENCE_WORKQUEUEID"; break;							// actual sequence		Y N N N N N N

		case "PROJLY_CAMPAIGNTASKID": $strResult = "CL_SEQUENCE_CAMPAIGNTASKID"; break;					// actual sequence		Y N N N N N N
		case "PROJLY_PARTICIPANTID": $strResult = "CL_SEQUENCE_PARTICIPANTID"; break;						// actual sequence		Y N N N N N N
		case "PROJLY_PROJECTISSUEID": $strResult = "CL_SEQUENCE_PROJECTISSUEID"; break;					// actual sequence		Y N N N N N N
		case "PROJLY_PROJECTTASKID": $strResult = "CL_SEQUENCE_PROJECTTASKID"; break;						// actual sequence		Y N N N N N N

// USER SETTINGS FORM		
		case "CORE_BRANCHID": $strResult = "CLU_USERSETTING_GENERALSETTINGS_DEFAULTBRANCH_VD"; break;	// default branch		Y Y N N N N N

		case "UI_MDI": $strResult = "CLU_USERSETTING_GENERALSETTINGS_MDI_V"; break;						// default payer		Y Y N N N N N
		case "UI_TIPSON": $strResult = "CLU_USERSETTING_GENERALSETTINGS_TIPSON_V"; break;				// default payer		Y Y N N N N N

		default:
			$strResult = "";
			
		if (strlen($strResult) == 0)
		{
			logSetting("settingFind failed with: " . $strSettingCode . ", " . $strNotes_a);	
			dbRaiseCustomError($objConn_a, "Setting not found: " . $strSettingCode);
		}
	}
	
	return $strResult;
}
