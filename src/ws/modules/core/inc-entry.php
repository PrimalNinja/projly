<?php

$arrFunctions = array(
	"core_abnlookup" => array("dependencies" => 'core/actionCoreABNLookup', "function" => 'actionCoreABNLookup'),
	"core_businessnamelookup" => array("dependencies" => 'core/actionCoreBusinessNameLookup', "function" => 'actionCoreBusinessNameLookup'),
	"core_clientverify" => array("dependencies" => 'core/actionCoreClientVerify', "function" => 'actionCoreClientVerify'),
	"core_dataauditlogslist"  => array("dependencies" => 'core/actionCoreDataAuditLogsList', "function" => 'actionCoreDataAuditLogsList'),
	"core_getcurrentdevices" => array("dependencies" => 'core/actionCoreDevicesFetchCurrent', "function" => 'actionCoreDevicesFetchCurrent'),
	"core_registrationtypesfetchactive" => array("dependencies" => 'core/actionCoreRegistrationTypesFetchActive', "function" => 'actionCoreRegistrationTypesFetchActive'),
	"core_saveuserformdevice" => array("dependencies" => 'core/actionCoreUserFormDeviceSave', "function" => 'actionCoreUserFormDeviceSave'),
	"core_servereventscheck" => array("dependencies" => 'core/actionCoreServerEventsCheck', "function" => 'actionCoreServerEventsCheck'),
	"core_showform_device" => array("dependencies" => 'core/actionCoreShowFormDevice', "function" => 'actionCoreShowFormDevice'),
	"core_startupitemfetchactive" => array("dependencies" => 'core/actionCoreStartupItemsFetchActive', "function" => 'actionCoreStartupItemsFetchActive'),
	"core_suburbfetch" => array("dependencies" => 'core/actionCoreSuburbFetch', "function" => 'actionCoreSuburbFetch'),
	"core_suburbsearchbycountry" => array("dependencies" => 'core/actionCoreSuburbSearchByCountry', "function" => 'actionCoreSuburbSearchByCountry'),
	"core_suburbsearchbysuburb" => array("dependencies" => 'core/actionCoreSuburbSearchBySuburb', "function" => 'actionCoreSuburbSearchBySuburb'),
	"core_themesave" => array("dependencies" => 'core/actionCoreThemeSave', "function" => 'actionCoreThemeSave'),
	"devicesfetchall" => array("dependencies" => 'core/actionCoreDevicesFetchAll', "function" => 'actionCoreDevicesFetchAll')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
