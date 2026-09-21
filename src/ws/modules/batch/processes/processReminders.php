<?php

// process reminders
function processReminders($objConn_a)
{
	$strTableNameReminder = getTableNameEntity("reminder", false);
	$strTableNameUser = getTableNameEntity("user", false);

	$strNow = getDateTime();
	$intRemindersSent = 0;
	$intReminderErrors = 0;
	echo('processing reminders...');
	$intRemindersInRun = 0;

	$strDateToday = getDateStringOut(getDateOnly());

	$strTemplateClientID = getSystemClientID($objConn_a);
	
	// process reminders for all clients
	// get the next reminder that isn't sent of the highest priority
	$strSQL = "
select id, client_id, user_id, jsondata, adee66ca_8443_4e3e_8ddf_b5a480bf2b49_description subject, adee66ca_8443_4e3e_8ddf_b5a480bf2b49_message message
from ~TABLENAMEREMINDER~
where adee66ca_8443_4e3e_8ddf_b5a480bf2b49_isreminded = 'N' and concat(concat(adee66ca_8443_4e3e_8ddf_b5a480bf2b49_date, ' '), adee66ca_8443_4e3e_8ddf_b5a480bf2b49_time) <= '~NOW~'
order by adee66ca_8443_4e3e_8ddf_b5a480bf2b49_date, adee66ca_8443_4e3e_8ddf_b5a480bf2b49_time
";
	$strSQL = str_replace('~TABLENAMEREMINDER~', ff($strTableNameReminder), $strSQL);
	$strSQL = str_replace('~NOW~', ff($strNow), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult)) 
	{
		$strReminderID = $arrRow['id'];
		$strClientID = $arrRow['client_id'];
		$strUserID = $arrRow['user_id'];
		$strSubject = $arrRow['subject'];
		$strMessage = $arrRow['message'];
		$strJSONData = $arrRow['jsondata'];
		$arrJSONData = json_decode($strJSONData, true);

		// send the reminder
		$strSQL = "select email_address as returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strClientEmailAddress = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$arrPlaceholders = Array(	// TODO make placeholders consistent with others
			'DATE' => $strDateToday,
			'APPLICATIONNAME' => APPNAME,
			'SUBJECT' => $strSubject,
			'MESSAGE' => $strMessage
		);

		sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strClientEmailAddress, $strClientID, $strUserID, $strTemplateClientID, "PROC_REMINDER", $arrPlaceholders, "", "", true);
		$blnResult = true; // currently sendMessageTemplatedMail does not have a return value

		if ($blnResult)
		{
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "adee66ca-8443-4e3e-8ddf-b5a480bf2b49", "ISREMINDED", "Y");
			
			dbBeginTrans($objConn_a, __FUNCTION__);
			// set isreminded flag to 'Y'
			$strJSONData = json_encode($arrJSONData);
			$strSQL = "update ~TABLENAMEREMINDER~ set jsondata = '~JSONDATA~', adee66ca_8443_4e3e_8ddf_b5a480bf2b49_isreminded = 'Y' where id = ~REMINDERID~";
			$strSQL = str_replace('~TABLENAMEREMINDER~', ff($strTableNameReminder), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~REMINDERID~', ff($strReminderID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			dbEndTrans($objConn_a, __FUNCTION__);
			
			$intRemindersInRun++;
			$intRemindersSent++;
		}
		else
		{
			$intReminderErrors++;
		}
	}

	dbCloseRecordset($objResult);
	
	if ($intRemindersInRun == 0)
	{
		echo ('no reminders...');
	}

	echo ('<br>sent ' . $intRemindersSent . '. errored ' . $intReminderErrors . '.');
}
