<?php

// function summary:

// ldapAuthenticateUser($objLDAP_a, $strLogin_a, $strPassword_a)
// ldapClose($objLDAP_a)
// ldapGetUserGroups($objLDAP_a, $strLogin_a)
// ldapOpen()

function ldapAuthenticateUser($objLDAP_a, $strLogin_a, $strPassword_a)
{
	$blnResult = false;
	
	if (dependencies("3p/adLDAP/adLDAP.php") &&
		dependencies("3p/adLDAP/classes/adLDAPComputers.php,3p/adLDAP/classes/adLDAPContacts.php,3p/adLDAP/classes/adLDAPExchange.php,3p/adLDAP/classes/adLDAPFolders.php,3p/adLDAP/classes/adLDAPGroups.php,3p/adLDAP/classes/adLDAPUsers.php,3p/adLDAP/classes/adLDAPUtils.php") &&
		dependencies("3p/adLDAP/collections/adLDAPCollection.php,3p/adLDAP/collections/adLDAPComputerCollection.php,3p/adLDAP/collections/adLDAPContactCollection.php,3p/adLDAP/collections/adLDAPGroupCollection.php,3p/adLDAP/collections/adLDAPUserCollection.php"))
	{
		$blnResult = $objLDAP_a->user()->authenticate($strLogin_a, $strPassword_a);
	}
	
	return $blnResult;
}

function ldapClose($objLDAP_a)
{
	if (dependencies("3p/adLDAP/adLDAP.php") &&
		dependencies("3p/adLDAP/classes/adLDAPComputers.php,3p/adLDAP/classes/adLDAPContacts.php,3p/adLDAP/classes/adLDAPExchange.php,3p/adLDAP/classes/adLDAPFolders.php,3p/adLDAP/classes/adLDAPGroups.php,3p/adLDAP/classes/adLDAPUsers.php,3p/adLDAP/classes/adLDAPUtils.php") &&
		dependencies("3p/adLDAP/collections/adLDAPCollection.php,3p/adLDAP/collections/adLDAPComputerCollection.php,3p/adLDAP/collections/adLDAPContactCollection.php,3p/adLDAP/collections/adLDAPGroupCollection.php,3p/adLDAP/collections/adLDAPUserCollection.php"))
	{
		$objLDAP_a->close();
	}
}

function ldapGetUserGroups($objLDAP_a, $strLogin_a)
{
	$arrResult = array();
	
	if (dependencies("3p/adLDAP/adLDAP.php") &&
		dependencies("3p/adLDAP/classes/adLDAPComputers.php,3p/adLDAP/classes/adLDAPContacts.php,3p/adLDAP/classes/adLDAPExchange.php,3p/adLDAP/classes/adLDAPFolders.php,3p/adLDAP/classes/adLDAPGroups.php,3p/adLDAP/classes/adLDAPUsers.php,3p/adLDAP/classes/adLDAPUtils.php") &&
		dependencies("3p/adLDAP/collections/adLDAPCollection.php,3p/adLDAP/collections/adLDAPComputerCollection.php,3p/adLDAP/collections/adLDAPContactCollection.php,3p/adLDAP/collections/adLDAPGroupCollection.php,3p/adLDAP/collections/adLDAPUserCollection.php"))
	{
		$arrResult = $objLDAP_a->user_groups($strLogin_a, toBoolean(LDAP_RECURSE_GROUPS));
	}
	
	return $arrResult;
}

function ldapOpen()
{
	global $g_arrLDAPConfig;
	
	$objResult = null;
	
	if (dependencies("3p/adLDAP/adLDAP.php") &&
		dependencies("3p/adLDAP/classes/adLDAPComputers.php,3p/adLDAP/classes/adLDAPContacts.php,3p/adLDAP/classes/adLDAPExchange.php,3p/adLDAP/classes/adLDAPFolders.php,3p/adLDAP/classes/adLDAPGroups.php,3p/adLDAP/classes/adLDAPUsers.php,3p/adLDAP/classes/adLDAPUtils.php") &&
		dependencies("3p/adLDAP/collections/adLDAPCollection.php,3p/adLDAP/collections/adLDAPComputerCollection.php,3p/adLDAP/collections/adLDAPContactCollection.php,3p/adLDAP/collections/adLDAPGroupCollection.php,3p/adLDAP/collections/adLDAPUserCollection.php"))
	{
		$objResult = new adLDAP($g_arrLDAPConfig);
		//$objResult->close();  close it just after opening it?
	}
	
	return $objResult;
}


?>