<?php

// add SendMessage
function actionMessageRecipientsFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
    
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $strRecipient = getJSONParameter($arrParameters_a, 'recipient');
	$strRecipient = str_replace("*", "%", $strRecipient);
	
    
    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);
    
    $strSQL = "
select u.id user_id, c.id client_id, u.description username, c.description businessname
from ~TABLENAMEUSER~ u, ~TABLENAMECLIENT~ c 
where u.client_id = c.id and (u.description like '%~RECIPIENT~%' or c.description like '%~RECIPIENT~%') and (c.id in(~CLIENTID~, ~SYSTEMOWNERCLIENTID~) or u.login = '~USERLOGIN~')
order by concat(concat(u.description, ' - '), c.description)
";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~RECIPIENT~', ff($strRecipient), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~SYSTEMOWNERCLIENTID~', ff($strSystemOwnerClientID), $strSQL);
    $strSQL = str_replace('~USERLOGIN~', REPLYTO_SYSTEMOWNERUSER, $strSQL); 

    $objResult   = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($objResult) 
	{
        while ($arrRow = dbReadRecord($objResult)) 
		{
			$strRecipient = $arrRow['username'] . ' - ' . $arrRow['businessname'];
			if ($arrRow['username'] == $arrRow['businessname'])
			{
				$strRecipient = $arrRow['username'];
			}

            $arrResult[] = array(
                        "id" => secureEntityValue('USER', $arrRow['user_id']),
                        "recipient" => $strRecipient
                    );
        }
    }
                  
    dbCloseRecordset($objResult);

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}

