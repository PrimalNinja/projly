<?php

$arrFunctions = array(
    "security_fetchcharts" => array("dependencies" => 'security/actionSecurityFetchCharts', "function" => 'actionSecurityFetchCharts'),
    "security_profileinstall" => array("dependencies" => 'security/actionSecurityProfileInstall', "function" => 'actionSecurityProfileInstall'),
    "security_profilesinstallablelist" => array("dependencies" => 'security/actionSecurityProfilesInstallableList', "function" => 'actionSecurityProfilesInstallableList'),
	"security_updatelicence" => array("dependencies" => 'security/actionSecurityUpdateLicence', "function" => 'actionSecurityUpdateLicence'),
	"security_userfetch" => array("dependencies" => 'security/actionSecurityUserFetch', "function" => 'actionSecurityUserFetch'),
	"security_userfetchbyaccount" => array("dependencies" => 'security/actionSecurityUserFetchByAccount', "function" => 'actionSecurityUserFetchByAccount'),
	"security_userinfo" => array("dependencies" => 'security/actionSecurityUserInfo', "function" => 'actionSecurityUserInfo'),
	"security_userpasswordupdate" => array("dependencies" => 'security/actionSecurityUserPasswordUpdate', "function" => 'actionSecurityUserPasswordUpdate'),
	"security_userpasswordupdatebyaccount" => array("dependencies" => 'security/actionSecurityUserPasswordUpdateByAccount', "function" => 'actionSecurityUserPasswordUpdateByAccount')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
