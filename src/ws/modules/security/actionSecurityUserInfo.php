<?php

// fetch user information
function actionSecurityUserInfo($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
//file_put_contents("d:\dev\session.log", " actionCoreUserInfo.php 1:" . $strSecurityToken_a . "\n", FILE_APPEND);	// DEBUGSESSION
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameDesktopRegion = getTableNameEntity("desktopregion", false);
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameTheme = getTableNameEntity("theme", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);
	$strTableNameUserSetting = getTableNameEntity("usersetting", false);
	
    $strResult = "";

    if (dependencies('setting/settingGet')) {
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // licence check
        $strExpiryDate = licenceCheck($objConn_a, PRODUCT_ID, intval(INSTALLATION_ID, 10));
        $blnLicensed = (strlen($strExpiryDate) > 0);
        $strLicensed = 'N';
        if ($blnLicensed) 
		{
            $strLicensed = 'Y';
        }
        $intExpiryDays = 0;

        // expiry in
        if ($blnLicensed) 
		{
            $dteToday = getISODate();
            $dteExpiryDate = date('Y-m-d', strtotime($strExpiryDate));
            $dteWarningDate = getDateMinusDays($dteExpiryDate, intval(LICENCEWARNINGDAYS, 10));

            if ($dteToday > $dteWarningDate) {
                $intExpiryDays = (strtotime($dteExpiryDate) - strtotime($dteToday)) / 86400;
            }
        }

        $arrResult = array();
		$arrDesktopRegions = array();
        $arrPermissions = array();

        // initialisations
		$strAgent = $_SESSION['server_loggedin_agent'];
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strClientCode = $_SESSION['server_loggedin_client'];
        $strLogin = $_SESSION['server_loggedin_user'];
        $blnIsPublic = $_SESSION['server_loggedin_public'];
        $blnIsSysAdmin = $_SESSION['server_loggedin_sysadmin'];
		$blnIsDeveloper = $_SESSION['server_loggedin_developer'];
        $strDeviceID = $_SESSION['server_deviceid'];
		$blnIsEmployer = $_SESSION['server_loggedin_isemployer'];
		$blnIsIndividual = $_SESSION['server_loggedin_isindividual'];
		$strEnableBranches = $_SESSION['server_loggedin_enablebranches'];
		$strBranchID = $_SESSION['server_loggedin_branchid'];
		$strBranchName = $_SESSION['server_loggedin_branchname'];

		// permissions
        if ($blnLicensed) 
		{
            // fetch
            $strSQL =
                "
		select distinct pr.code permissioncode
		from ~TABLENAMEUSER~ u, ~TABLENAMEUSERPROFILE~ up, ~TABLENAMEPROFILE~ p, ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ pr
		where
		u.client_id = ~CLIENTID~ and
		u.is_enabled = 'Y' and
		u.login = '~LOGIN~' and
		up.client_id = u.client_id and
		up.user_id = u.id and
		p.client_id = up.client_id and
		p.id = up.profile_id and
		p.is_enabled = 'Y' and
		pp.client_id = p.client_id and
		pp.profile_id = p.id and
		pr.id = pp.permission_id and
		(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications = '' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications is null or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications like '%~APPCODE~%')
		order by pr.code
		";
        } 
		else 
		{
            // fetch
            $strSQL =
                "
		select distinct pr.code permissioncode
		from ~TABLENAMEUSER~ u, ~TABLENAMEUSERPROFILE~ up, ~TABLENAMEPROFILE~ p, ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ pr
		where
		u.client_id = ~CLIENTID~ and
		u.is_enabled = 'Y' and
		u.login = '~LOGIN~' and
		up.client_id = u.client_id and
		up.user_id = u.id and
		p.client_id = up.client_id and
		p.id = up.profile_id and
		p.is_enabled = 'Y' and
		pp.client_id = p.client_id and
		pp.profile_id = p.id and
		pr.id = pp.permission_id and
		(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_islicensed = 'N' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_islicensed is null) and
		(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications = '' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications is null or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications like '%~APPCODE~%')
		order by pr.code
		";
        }
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        while ($arrRow = dbReadRecord($objResult)) 
		{
            $arrPermissions[] = $arrRow['permissioncode'];
        }
        dbCloseRecordset($objResult);
		
		$strReturnToClientDB = $_SESSION['server_returnto_clientdb'];
		if (strlen($strReturnToClientDB) > 0)
		{
			$arrPermissions[] = 'VW_RETURN';
		}
		else
		{
			$arrPermissions[] = 'VW_LOGOUT';
		}

		// fetch entity desktopregions
		$strSQL = "
select 
e.code entitycode, fdr.code formdesktopregion, ldr.code listerdesktopregion 
from 
~TABLENAMEENTITY~ e 
join ~TABLENAMEDESKTOPREGION~ fdr on e.formdesktopregion_id = fdr.id
join ~TABLENAMEDESKTOPREGION~ ldr on e.listerdesktopregion_id = ldr.id
where 
e.is_enabled = 'Y' and 
fdr.is_enabled = 'Y' and 
ldr.is_enabled = 'Y'
";
		$strSQL = str_replace('~TABLENAMEDESKTOPREGION~', ff($strTableNameDesktopRegion), $strSQL);
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        while ($arrRow = dbReadRecord($objResult)) {
            $arrDesktopRegions[] = array(
				"entitycode" => $arrRow['entitycode'],
                "listerdesktopregion" => $arrRow['listerdesktopregion'],
				"formdesktopregion" => $arrRow['formdesktopregion']
            );
        }
        dbCloseRecordset($objResult);

		// fetch products
		$strSQL =
			"
	select main.productcode, main.productdescription, main.status from  (select p.code productcode, p.description productdescription, ps.code status
    from ~TABLENAMECLIENTPRODUCT~ pp, ~TABLENAMEPRODUCT~ p, ~TABLENAMEPAYMENTSTATUS~ ps
    where
    pp.client_id = ~CLIENTID~ and
    pp.is_enabled = 'Y' and
    p.id = pp.product_id and
    p.is_enabled = 'Y' and
    pp.paymentstatus_id = ps.id and
    ps.is_enabled = 'Y' and
    ps.code in ('COMPLIMENTARY','FREE','PAID')
    order by p.id, ps.id ) as main group by main.productcode
	";
		$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    //    $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        while ($arrRow = dbReadRecord($objResult)) {
            $arrProducts[] = array(
                "productcode" => $arrRow['productcode'],
				"productdescription" => $arrRow['productdescription'],
                "productstatus" => $arrRow['status']
            );
        }
        dbCloseRecordset($objResult);

		// fetch the registration type as a product
		$strSQL = "select rt.code productcode, rt.description productdescription from ~TABLENAMEACCOUNT~ a, ~TABLENAMEREGISTRATIONTYPE~ rt where a.registrationtype_id = rt.id and a.client_id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult)) {
            $arrProducts[] = array(
                "productcode" => $arrRow['productcode'],
				"productdescription" => $arrRow['productdescription'],
                "productstatus" => "FREE"
            );
        }
        dbCloseRecordset($objResult);
		
        // fetch some user and client info
        $strSQL =
            "
	select distinct c.description clientname, u.description username, u.id userid
	from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
	where
	c.id = ~CLIENTID~ and
	u.client_id = c.id and
	u.login = '~LOGIN~'
	";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);

        $strClientName = '';
		
		$strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strClientID, '', '', '', __FUNCTION__);
        $strUserName = '';
        $strUserID = '';

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult)) {
            $strClientName = $arrRow['clientname'];
            $strUserName = $arrRow['username'];
            $strUserID = $arrRow['userid'];
        }
        dbCloseRecordset($objResult);

        // return some global/user/pc settings with the permissions
        $strDefaultCountry = settingGet($objConn_a, 'CORE', 'DEFCOUNTRY', $strClientID, '', '', '', __FUNCTION__);
        $strDisplayTooltips = settingGet($objConn_a, 'UI', 'TIPSON', $strClientID, $strUserID, '', '', __FUNCTION__);
		$strIsMDI = settingGet($objConn_a, 'UI', 'MDI', $strClientID, $strUserID, '', '', __FUNCTION__);
        $strThemeID = settingGet($objConn_a, 'CORE', 'THEME', $strClientID, $strUserID, '', '', __FUNCTION__);
		$strTheme = "";
		
		if (strlen($strThemeID) > 0)
		{
			$strSQL = "select code returnvalue from ~TABLENAMETHEME~ where client_id = ~CLIENTID~ and id = ~THEMEID~";
			$strSQL = str_replace('~TABLENAMETHEME~', ff($strTableNameTheme), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~THEMEID~', ff($strThemeID), $strSQL);
			$strTheme = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
		
		$strIsSysAdmin = "N";
		if ($blnIsSysAdmin)
		{
			$strIsSysAdmin = "Y";
		}

		$strIsDeveloper = "N";
		if ($blnIsDeveloper)
		{
			$strIsDeveloper = "Y";
		}

		$strIsBatchClient = "N";
		if ($strClientID == getBatchClientID($objConn_a))
		{
			$strIsBatchClient = "Y";
		}

		$strIsDefaultClient = "N";
		if ($strClientID == getDefaultClientID($objConn_a))
		{
			$strIsDefaultClient = "Y";
		}

		$strIsSystemOwnerClient = "N";
		if ($strClientID == getSystemOwnerClientID($objConn_a))
		{
			$strIsSystemOwnerClient = "Y";
		}
		
        $strIsEmployer = 'N';
        if ($blnIsEmployer) 
		{
            $strIsEmployer = 'Y';
        }

        $strIsIndividual = 'N';
        if ($blnIsIndividual) 
		{
            $strIsIndividual = 'Y';
        }
		
		if (strlen($strBranchID) > 0)
		{
			$strBranchID = secureEntityValue('BRANCH', $strBranchID);
		}

		$strIsPublic = "N";
		if ($blnIsPublic)
		{
			$strDeviceName = "PUBLIC";
			$strIsPublic = "Y";
		}
		else
		{
			// read the devicename
			$strSQL = "select description returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and id = ~DEVICEID~";
			$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~DEVICEID~', ff($strDeviceID), $strSQL);
			$strDeviceName = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}

        $arrResult = array(
			"clientdb" => $_SESSION['server_clientdb'],
			"loginas" => $_SESSION['server_loginas'],
            "clientid" => $strClientCode,
            "clientname" => $strClientName,
			"businessname" => $strBusinessName,
            "login" => $strLogin,
            "username" => $strUserName,
            "sysadmin" => $strIsSysAdmin,
			"developer" => $strIsDeveloper,
			"isbatch" => $strIsBatchClient,
			"isdefault" => $strIsDefaultClient,
			"isowner" => $strIsSystemOwnerClient,
            "ispublic" => $strIsPublic,
            "licensed" => $strLicensed,
            "expirydate" => $strExpiryDate,
            "expirydays" => $intExpiryDays,
			"desktopregions" => $arrDesktopRegions,
            "permissions" => $arrPermissions,
			"products" => $arrProducts,
            "defaultcountry" => $strDefaultCountry,
            "displaytooltips" => $strDisplayTooltips,
            "devicename" => $strDeviceName,
            //"uselocalapplet" => $strUseLocalApplet,
            "theme" => $strTheme,
			"isemployer" => $strIsEmployer,
			"isindividual" => $strIsIndividual,
			"ismdi" => $strIsMDI,
			"enablebranches" => $strEnableBranches,
			"branchid" => $strBranchID,
			"branchname" => $strBranchName
        );

        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
    }

//file_put_contents("d:\dev\session.log", " actionCoreUserInfo.php 2:" . $strSecurityToken_a . "\n", FILE_APPEND);	// DEBUGSESSION
    return $strResult;
}
