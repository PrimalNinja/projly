<?php

// process send emails from the preparedmessages table
function processSendEmails($objConn_a)
{
	if (ENABLE_EMAIL == 'TRUE')
	{
		$strNow = getDateTime();
		$intEmailsSent = 0;
		$intEmailErrors = 0;
		echo('sending emails...');
		for ($intEmailRun = 0; $intEmailRun < intval(EMAIL_RUNS, 10); $intEmailRun++) 
		{
			$intEmailsInRun = 0;
			for ($intEmail = 0; $intEmail < intval(EMAIL_PER_RUN, 10); $intEmail++) 
			{
				// get the next email that isn't sent of the highest priority
				$strSQL = "
	select id, fromdomain, sender, sendername, recipients, subject, messagehtml, retries, modifydatetime
	from ~TABLENAMEMESSAGEPREPARED~
	where sent = 'N' and modifydatetime < '~NOW~'
	order by priority, id limit 1
	";
				$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
				$strSQL = str_replace('~NOW~', ff($strNow), $strSQL);
				$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
				if ($arrRow = dbReadRecord($objResult)) {

					$strEmailID = $arrRow['id'];
					$strFromDomain = $arrRow['fromdomain'];
					$strSender = $arrRow['sender'];
					$strSenderName = $arrRow['sendername'];
					$strRecipients = $arrRow['recipients'];
					$arrRecipients = json_decode($strRecipients, true);
					$strSubject = $arrRow['subject'];
					$strMessageHTML = $arrRow['messagehtml'];
					$intRetries = intval($arrRow['retries'], 10);
					$dteEmailNextAttempt = getDateTimePlusSeconds($strNow, intval(EMAIL_NEXT_ATTEMPT_TIMEFRAME, 10));

					dbCloseRecordset($objResult);

					// send the email
					$blnResult = sendMail($strFromDomain, $strSender, $strSenderName, $arrRecipients, $strSubject, $strMessageHTML);

					if ($blnResult)
					{
						dbBeginTrans($objConn_a, __FUNCTION__);
						// complete the job
						$strSQL = "update ~TABLENAMEMESSAGEPREPARED~ set sent = 'Y' where id = ~EMAILID~";
						$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
						$strSQL = str_replace('~EMAILID~', ff($strEmailID), $strSQL);
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
						dbEndTrans($objConn_a, __FUNCTION__);
						
						$intEmailsInRun++;
						$intEmailsSent++;
					}
					else
					{
						// if sending emails fail, this should be monitored externally
						$intRetries--;
						if ($intRetries <= 0)
						{
							// fail the email completely
							dbBeginTrans($objConn_a, __FUNCTION__);
							$strSQL = "update ~TABLENAMEMESSAGEPREPARED~ set retries = 0, sent = 'E' where id = ~EMAILID~";
							$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
							$strSQL = str_replace('~EMAILID~', ff($strEmailID), $strSQL);
							dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
							dbEndTrans($objConn_a, __FUNCTION__);
						}
						else
						{
							// store the reduced retry count
							dbBeginTrans($objConn_a, __FUNCTION__);
							$strSQL = "update ~TABLENAMEMESSAGEPREPARED~ set retries = ~RETRIES~, modifydatetime = '~NEXTATTEMPT~' where id = ~EMAILID~";
							$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
							$strSQL = str_replace('~EMAILID~', ff($strEmailID), $strSQL);
							$strSQL = str_replace('~RETRIES~', ff($intRetries), $strSQL);
							$strSQL = str_replace('~NEXTATTEMPT~', ff($dteEmailNextAttempt), $strSQL);
							dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
							dbEndTrans($objConn_a, __FUNCTION__);
						}
						
						$intEmailErrors++;
					}
				} 
				else 
				{
					dbCloseRecordset($objResult);
				}
			}
			
			if ($intEmailsInRun == 0)
			{
				echo ('no emails...');
			}

			echo ('<br>sent ' . $intEmailsSent . '. errored ' . $intEmailErrors . '. sleeping for ' . EMAIL_PAUSE_BETWEEN_RUNS . ' seconds...');
			if ($intEmailRun < intval(EMAIL_RUNS, 10) - 1) 
			{
				sleep(intval(EMAIL_PAUSE_BETWEEN_RUNS, 10));
			}
		}
	}
	else
	{
		echo('sending emails disabled...');
	}
}
