<?php

// fetch a file format
function fileFormatFetch($objConn_a, $strClientID_a, $strFileFormatID_a, $blnRequireIDs_a)
{   
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);    
    $strTableNameFileFormatType = getTableNameEntity('fileformattype', false);    

    $arrFileFormat = null;

    if (dependencies('import/fileFormatFetchFields')) 
    {        
        $strSQL = "select id, code, description, is_enabled, fileformattemplate_id, fileformattype_id, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate fileformattemplate, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype fileformattype, 
                    jsondata from ~TABLENAMEFILEFORMAT~ where id = '~FILEFORMATID~'";
        $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult)) 
        {
            $strSQL = "select code from ~TABLENAMEFILEFORMATTYPE~ WHERE id = ~FILEFORMATTYPEID~";
            $strSQL = str_replace('~TABLENAMEFILEFORMATTYPE~', ff($strTableNameFileFormatType), $strSQL);
            $strSQL = str_replace('~FILEFORMATTYPEID~', ff($arrRow['fileformattype_id']), $strSQL);
            $strFileFormatTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $arrFields = fileFormatFetchFields($objConn_a, $strClientID_a, $arrRow['id'], $blnRequireIDs_a);

            $arrJSONData = json_decode($arrRow['jsondata'], true);
            $strHeaderRows = formValueGetBySectionCodeFieldCode($arrJSONData, 'g5ab8ab01-9cdd-450d-b439-de0d09dfeea5', 'HEADERROWS');
            $strIsImport = formValueGetBySectionCodeFieldCode($arrJSONData, 'g5ab8ab01-9cdd-450d-b439-de0d09dfeea5', 'IS_IMPORT');
            $strIsExport = formValueGetBySectionCodeFieldCode($arrJSONData, 'g5ab8ab01-9cdd-450d-b439-de0d09dfeea5', 'IS_EXPORT');
            $strIsDefault = formValueGetBySectionCodeFieldCode($arrJSONData, 'g5ab8ab01-9cdd-450d-b439-de0d09dfeea5', 'IS_DEFAULT');

            $arrFileFormat = array();

            if ($blnRequireIDs_a)
            {
                $arrFileFormat['id'] = secureEntityValue('FILEFORMAT', $arrRow['id']);
                $arrFileFormat['fileformattemplate_id'] = secureEntityValue('FILEFORMATTEMPLATE', $arrRow['fileformattemplate_id']);
                $arrFileFormat['fileformattype_id'] = secureEntityValue('FILEFORMATTYPE', $arrRow['fileformattype_id']);
            }

            $arrFileFormat['formattypecode'] = $arrRow['fileformattemplate'];
            $arrFileFormat['formattype'] = $strFileFormatTypeCode;
            $arrFileFormat['headerrows'] = $strHeaderRows;
            $arrFileFormat['code'] = $arrRow['code'];
            $arrFileFormat['description'] = $arrRow['description'];
            $arrFileFormat['import'] = $strIsImport;
            $arrFileFormat['export'] = $strIsExport;
            $arrFileFormat['enabled'] = $arrRow['is_enabled'];
            $arrFileFormat['isdefault'] = $strIsDefault;
            $arrFileFormat['canuseredit'] = 'N';
            $arrFileFormat['fields'] = $arrFields;
        }
        dbCloseRecordset($objResult);
    }

    return $arrFileFormat;
}