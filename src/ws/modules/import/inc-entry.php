<?php

$arrFunctions = array(
	"import_fileformatdelete" => array("dependencies" => 'import/actionImportFileFormatDelete', "function" => 'actionImportFileFormatDelete'),
	"import_fileformatinstall" => array("dependencies" => 'import/actionImportFileFormatInstall', "function" => 'actionImportFileFormatInstall'),
	"import_fileformatinstallablelist" => array("dependencies" => 'import/actionImportFileFormatInstallableList', "function" => 'actionImportFileFormatInstallableList'),
	"import_fileformatsfetchbyfiletypeid" => array("dependencies" => 'import/actionImportFileFormatsFetchByFileTypeId', "function" => 'actionImportFileFormatsFetchByFileTypeId'),
	"import_fileformatslist" => array("dependencies" => 'import/actionImportFileFormatsList', "function" => 'actionImportFileFormatsList'),
	"import_filetypesfetchimport" => array("dependencies" => 'import/actionImportFileTypesFetchImport', "function" => 'actionImportFileTypesFetchImport'),
	"import_processuploads" => array("dependencies" => 'import/actionImportProcessUploads', "function" => 'actionImportProcessUploads'),
);

$arrFunction = validateFunction($arrFunctions, $strFunction);
if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}
