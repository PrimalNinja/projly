<?php
/** 
 * initialise profiles based on the products the client has
 * Installing Profile
 *- Add the profile - Security > Profiles
 * - Add profile code to constant 'PROFILEDEFAULTS_<APP>' (e.g. PROFILEDEFAULTS_BLAH) to a file ws/inc-app-<app>.php
 * - In products, add the profile to profilelist based on the appropriate profile
 *
 * For debugging
 * - Test registration and login. if experiencing forced logout when logging in. Check if clientproduct and user_profile records are created in reference to the registration.
 */
function profilesInitialise($objConn_a, $strClientID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
    $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    $strTableNameProduct = getTableNameEntity("product", false);    
    $strTableNameProductType = getTableNameEntity("producttype", false);   
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
	$strRegistrationAccountType = "DEFAULT";
	$strRegistrationAccountSubType = "";
	$strRegisteredAccountType = "";
	$strRegisteredAccountSubType = "";
	$blnIsEmployer = false;
	$blnIsIndividual = false;
	
	$blnResult = false;
	
//logDebug("DEACT:1", "");
    if (dependencies('security/clientDeactivate,security/profileActivate,security/clientUserAssignProductProfile,security/clientProductAdd,security/profileAdd,security/profileDeactivate')) 
	{
//logDebug("DEACT:2", "");
		$strLogin = $_SESSION['server_loggedin_user'];
		
		// find out what type of profile the customer registered as
		$strSQL = "select rt.code returnvalue from ~TABLENAMEREGISTRATIONTYPE~ rt, ~TABLENAMEACCOUNT~ a where rt.id = a.registrationtype_id and a.client_id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strRegistrationAccountSubType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
//logDebug("DEACT:3", "");
		if (strlen($strRegistrationAccountSubType) > 0)
		{
//logDebug("DEACT:4", "");
			if (!$blnIsEmployer && !$blnIsIndividual)
			{
//logDebug("DEACT:5", "");
				$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
				$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
				$blnIsEmployer = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
			}
//logDebug("DEACT:6", "");
			
			if (!$blnIsEmployer)
			{
//logDebug("DEACT:7", "");
				$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
				$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
				$blnIsIndividual = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
			}
//logDebug("DEACT:8", "");

			if ($blnIsEmployer)
			{
				$strRegistrationAccountType = ACCOUNTTYPE_BUSINESS;
			}
			else if ($blnIsIndividual)
			{
				$strRegistrationAccountType = ACCOUNTTYPE_INDIVIDUAL;
			}
//logDebug("DEACT:9", "");
		}
//logDebug("DEACT:10", "");
		
        dbBeginTrans($objConn_a, __FUNCTION__);

		// fix the client's registration product
		$blnDeactivateClient = false;
		//if (strlen($strRegistrationAccountSubType) == 0)
		//{
			//$blnDeactivateClient = true;
		//}
		//else
		//{
			if (strlen($strRegistrationAccountType) > 0)
			{
//logDebug("DEACT:11", "");
				// check if the product of $strRegistrationAccountType exists as a type
				$strSQL = "select p.id returnvalue from ~TABLENAMEPRODUCT~ p, ~TABLENAMEPRODUCTTYPE~ pt where pt.id = p.producttype_id and pt.code = 'TYPE' and p.code = '~PRODUCTCODE~'";
				$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
				$strSQL = str_replace('~TABLENAMEPRODUCTTYPE~', ff($strTableNameProductType), $strSQL);
				$strSQL = str_replace('~PRODUCTCODE~', ff($strRegistrationAccountType), $strSQL);
				$strRegisteredAccountType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			}
//logDebug("DEACT:12:" . $strRegisteredAccountType, "");

			if (strlen($strRegistrationAccountSubType) > 0)
			{
//logDebug("DEACT:13", "");
				// check if the product of $strRegistrationAccountSubType exists as a subtype
				$strSQL = "select p.id returnvalue from ~TABLENAMEPRODUCT~ p, ~TABLENAMEPRODUCTTYPE~ pt where pt.id = p.producttype_id and pt.code = 'SUBTYPE' and p.code = '~PRODUCTCODE~'";
				$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
				$strSQL = str_replace('~TABLENAMEPRODUCTTYPE~', ff($strTableNameProductType), $strSQL);
				$strSQL = str_replace('~PRODUCTCODE~', ff($strRegistrationAccountSubType), $strSQL);
				$strRegisteredAccountSubType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			}
//logDebug("DEACT:14:" . $strRegisteredAccountSubType, "");

			if ((strlen($strRegisteredAccountType) == 0) && (strlen($strRegisteredAccountSubType) == 0))
			{
//logDebug("DEACT:15", "");
				$blnDeactivateClient = true;
			}
			else
			{
//logDebug("DEACT:16", "");
				if (strlen($strRegisteredAccountType) > 0)
				{
//logDebug("DEACT:17", "");
					// check if the product of $strRegisteredAccountType is installed in the client and if not, install it
					$strSQL = "select id returnvalue from ~TABLENAMECLIENTPRODUCT~ where is_enabled = 'Y' and client_id = ~CLIENTID~ and product_id = ~PRODUCTID~";
					$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
					$strSQL = str_replace('~PRODUCTID~', ff($strRegisteredAccountType), $strSQL);
					$strClientProductID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

					if (strlen($strClientProductID) == 0)
					{
//logDebug("DEACT:18", "");
						// the client doesn't have it, so install it
						$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code in ('COMPLIMENTARY','FREE')";
						$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
						$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

						clientProductAdd($objConn_a, $strClientID_a, $strRegisteredAccountType, $strPaymentStatusID, "Product obtained via account registration.", "");
					}
logDebug("DEACT:19:" . $strRegisteredAccountType . " installed", "");
				}
//logDebug("DEACT:20", "");

				if (strlen($strRegisteredAccountSubType) > 0)
				{
//logDebug("DEACT:21", "");
					// check if the product of $strRegisteredAccountSubType is installed in the client and if not, install it
					$strSQL = "select id returnvalue from ~TABLENAMECLIENTPRODUCT~ where is_enabled = 'Y' and client_id = ~CLIENTID~ and product_id = ~PRODUCTID~";
					$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
					$strSQL = str_replace('~PRODUCTID~', ff($strRegisteredAccountSubType), $strSQL);
					$strClientProductID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

					if (strlen($strClientProductID) == 0)
					{
//logDebug("DEACT:22", "");
						// the client doesn't have it, so install it
						$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code in ('COMPLIMENTARY','FREE')";
						$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
						$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

						clientProductAdd($objConn_a, $strClientID_a, $strRegisteredAccountSubType, $strPaymentStatusID, "Product obtained via account registration.", "");
					}
logDebug("DEACT:23:" . $strRegisteredAccountSubType . " installed", "");
				}
//logDebug("DEACT:24", "");
			}
//logDebug("DEACT:25", "");
		//}
		
		if ($blnDeactivateClient)
		{
logDebug("DEACT:26: deactivate client", "");
			// deactivate the client as they don't have a valid registration type
			clientDeactivate($objConn_a, $strClientID_a);
		}
		else
		{
//logDebug("DEACT:27", "");
			// dactivate all profiles which were active based on products including the registration one for now... 
			$strSQL = "select pr.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist profiles from ~TABLENAMECLIENTPRODUCT~ cp, ~TABLENAMEPRODUCT~ pr where cp.product_id = pr.id and cp.client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$objResultProduct = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRowProduct = dbReadRecord($objResultProduct)) 
			{
				$strProfiles = $arrRowProduct['profiles'];
//logDebug("DEACT:28", "");
				
				$arrProfiles = explode(",", $strProfiles);
				for ($intI = 0; $intI < count($arrProfiles); $intI++)
				{
					$strProfileCode = $arrProfiles[$intI];
//logDebug("DEACT:29:" . $strProfileCode, "");
					if (strlen($strProfileCode) > 0)
					{
//logDebug("DEACT:30", "");
						$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
						$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
						$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
						$strProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
						if (strlen($strProfileID) > 0)
						{
logDebug("DEACT:31:" . $strProfileCode . " deactivate", "");
							profileDeactivate($objConn_a, $strClientID_a, $strProfileID);
						}
//logDebug("DEACT:32", "");
					}
//logDebug("DEACT:33", "");
				}
//logDebug("DEACT:34", "");
			}
			dbCloseRecordset($objResultProduct);
//logDebug("DEACT:35", "");
	
			// select all currently installed profiles
			$arrInstalledProfiles = array();
			$strSQL = "select code from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$objResultProfile = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRowProfile = dbReadRecord($objResultProfile)) 
			{
				$strProfileCode = $arrRowProfile['code'];
//logDebug("DEACT:36:" . $strProfileCode, "");
				$arrInstalledProfiles[] = $strProfileCode;
			}
			dbCloseRecordset($objResultProfile);
//logDebug("DEACT:37:" . print_r($arrInstalledProfiles, true), "");
	
			$strSystemClientID = getSystemClientID($objConn_a);
	
			// install any new profiles for the client
			
			// select all profiles we could possibly install
			$strSQL = "select pr.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist profiles from ~TABLENAMECLIENTPRODUCT~ cp, ~TABLENAMEPRODUCT~ pr where cp.product_id = pr.id and cp.client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$objResultProduct = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRowProduct = dbReadRecord($objResultProduct)) 
			{
//logDebug("DEACT:38", "");
				$strProfiles = $arrRowProduct['profiles'];
				
				$arrProfiles = explode(",", $strProfiles);
				for ($intI = 0; $intI < count($arrProfiles); $intI++)
				{
					$strProfileCode = $arrProfiles[$intI];
//logDebug("DEACT:39:" . $strProfileCode, "");
					if ((strlen($strProfileCode) > 0) && (!in_array($strProfileCode, $arrInstalledProfiles)))
					{
//logDebug("DEACT:40", "");
						$strSQL = "select id, client_id, code, description, is_enabled, jsondata, f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin is_admin from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~' and is_sysadmin = 'N'";
						$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
						$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);		// we are getting the profiles from the system client
						$objResultProfile = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
						if ($arrRowProfile = dbReadRecord($objResultProfile)) 
						{
logDebug("DEACT:41:" . $strProfileCode . " install", "");
							$strSourceProfileID = $arrRowProfile['id'];
							$strCode = $arrRowProfile['code'];
							$strDescription = $arrRowProfile['description'];
							$strIsEnabled = $arrRowProfile['is_enabled'];
							$strIsAdmin = $arrRowProfile['is_admin'];
							$strJSONData = $arrRowProfile['jsondata'];
							$strIsDefined = "N";

							$strProfileID = profileAdd($objConn_a, $strClientID_a, $strCode, $strDescription, $strIsEnabled, $strIsAdmin, 'Y', 'N', $strIsDefined);

							// duplicate all of the permissions for the admin profile which is available to non-sysadmins
							//$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where code = 'DEFAULT_ADMINISTRATOR' and is_defined = 'N'";
							//$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
							//$strSourceProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

							// get the entity ids
							$strEntityID = getEntityID($objConn_a, "systemform");
							$strDataEntityID = getEntityID($objConn_a, "profile_permission");
						
							$strSQL =
								"
						insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, permission_id)
						select ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, pp.code, pp.description, 'Y', ~DATACLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', ~PROFILEID~, pp.permission_id
						from ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ p
						where pp.profile_id = ~SOURCEPROFILEID~ and p.id = pp.permission_id and p.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_isnonsysadmin = 'Y'
						";
							$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
							$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
							$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
							$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
							$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
							$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
							$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
							$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
							$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
							$strSQL = str_replace('~SOURCEPROFILEID~', ff($strSourceProfileID), $strSQL);
							dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
						}
						dbCloseRecordset($objResultProfile);
//logDebug("DEACT:42", "");
					}
//logDebug("DEACT:43", "");
				}
//logDebug("DEACT:44", "");
			}
			dbCloseRecordset($objResultProduct);
//logDebug("DEACT:45", "");

			// reactivate valid profiles (valid ones will be for enabled products only)
			$strSQL = "select pr.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist profiles from ~TABLENAMECLIENTPRODUCT~ cp, ~TABLENAMEPRODUCT~ pr where cp.product_id = pr.id and cp.client_id = ~CLIENTID~ and cp.is_enabled = 'Y'";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$objResultProduct = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRowProduct = dbReadRecord($objResultProduct)) 
			{
//logDebug("DEACT:46", "");
				$strProfiles = $arrRowProduct['profiles'];
				
				$arrProfiles = explode(",", $strProfiles);
				for ($intI = 0; $intI < count($arrProfiles); $intI++)
				{
					$strProfileCode = $arrProfiles[$intI];
//logDebug("DEACT:47:" . $strProfileCode, "");
					if (strlen($strProfileCode) > 0)
					{
//logDebug("DEACT:48", "");
						$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
						$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
						$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
						$strProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
						if (strlen($strProfileID) > 0)
						{
logDebug("DEACT:49:" . $strProfileCode . " activate", "");
							profileActivate($objConn_a, $strClientID_a, $strProfileID);
						}
//logDebug("DEACT:50", "");
					}
//logDebug("DEACT:51", "");
				}
//logDebug("DEACT:52", "");
			}
			dbCloseRecordset($objResultProduct);
//logDebug("DEACT:53", "");
			
			// assign the profiles that are product-related to the main user
			$strSQL = "select pr.gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist profiles from ~TABLENAMECLIENTPRODUCT~ cp, ~TABLENAMEPRODUCT~ pr where cp.product_id = pr.id and cp.client_id = ~CLIENTID~ and cp.is_enabled = 'Y'";
			$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$objResultProduct = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRowProduct = dbReadRecord($objResultProduct)) 
			{
//logDebug("DEACT:54", "");
				$strProfiles = $arrRowProduct['profiles'];
				
				$arrProfiles = explode(",", $strProfiles);
				for ($intI = 0; $intI < count($arrProfiles); $intI++)
				{
					$strProfileCode = $arrProfiles[$intI];
//logDebug("DEACT:55:" . $strProfileCode, "");
					if (strlen($strProfileCode) > 0)
					{
//logDebug("DEACT:56", "");
						$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
						$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
						$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
						$strProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
						if (strlen($strProfileID) > 0)
						{
//logDebug("DEACT:57 assign", "");
							clientUserAssignProductProfile($objConn_a, $strClientID_a, $strProfileID);
						}
//logDebug("DEACT:58", "");
					}
//logDebug("DEACT:59", "");
				}
//logDebug("DEACT:60", "");
			}
			dbCloseRecordset($objResultProduct);
        }
//logDebug("DEACT:61", "");

        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $blnResult;
}
