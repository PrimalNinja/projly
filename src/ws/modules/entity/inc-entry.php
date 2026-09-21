<?php

$arrFunctions = array(
	"entity_customoperationexecute" => array("dependencies" => 'entity/actionEntityCustomOperationExecute', "function" => 'actionEntityCustomOperationExecute'),
	"entity_dataformentitylist" => array("dependencies" => 'entity/actionEntityDataFormEntityList', "function" => 'actionEntityDataFormEntityList'),
	"entity_entitydatadelete" => array("dependencies" => 'entity/actionEntityDataDelete', "function" => 'actionEntityDataDelete'),
	"entity_entitydataheadersfetchbyentitycode" => array("dependencies" => 'entity/actionEntityDataHeadersFetchByEntityCode', "function" => 'actionEntityDataHeadersFetchByEntityCode'),   
	"entity_entitydatalist" => array("dependencies" => 'entity/actionEntityDataList', "function" => 'actionEntityDataList'),	
	"entity_entityoperationsfetchbyentitycode" => array("dependencies" => 'entity/actionEntityOperationsFetchByEntityCode', "function" => 'actionEntityOperationsFetchByEntityCode'),
	"entity_entityreorder" => array("dependencies" => 'entity/actionEntityReorder', "function" => 'actionEntityReorder'),
	"entity_entitysearchbycode" => array("dependencies" => 'entity/actionEntitySearchByCode', "function" => 'actionEntitySearchByCode'),
	"entity_formadd" => array("dependencies" => 'entity/actionFormAdd', "function" => 'actionFormAdd'),
	"entity_formdataadd" => array("dependencies" => 'entity/actionFormDataAdd', "function" => 'actionFormDataAdd'),
	"entity_formdatadelete" => array("dependencies" => 'entity/actionFormDataDelete', "function" => 'actionFormDataDelete'),
	"entity_formdatafetch" => array("dependencies" => 'entity/actionFormDataFetch', "function" => 'actionFormDataFetch'),
	"entity_formdataheadersfetchbyentitycode" => array("dependencies" => 'entity/actionFormDataHeadersFetchByEntityCode', "function" => 'actionFormDataHeadersFetchByEntityCode'),
	"entity_formdatalist" => array("dependencies" => 'entity/actionFormDataList', "function" => 'actionFormDataList'),
	"entity_formdataupdate" => array("dependencies" => 'entity/actionFormDataUpdate', "function" => 'actionFormDataUpdate'),
	"entity_formdelete" => array("dependencies" => 'entity/actionFormDelete', "function" => 'actionFormDelete'),
	"entity_formfetch" => array("dependencies" => 'entity/actionFormFetch', "function" => 'actionFormFetch'),
	"entity_formupdate" => array("dependencies" => 'entity/actionFormUpdate', "function" => 'actionFormUpdate')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);

if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
