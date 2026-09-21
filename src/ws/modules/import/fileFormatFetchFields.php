<?php

// fetch file format fields
function fileFormatFetchFields($objConn_a, $strClientID_a, $strFileFormatID_a, $blnRequireIDs_a)
{
    $strTableNameFileDateType = getTableNameEntity('filedatatype', false);
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);
    $strTableNameFileFormatField = getTableNameEntity('fileformat_field', false);
    //$strTableNameFileFormatTemplateField = getTableNameEntity('fileformattemplate_field', false);
    
    $arrResult = array();

    if (dependencies('import/fileFormatMassageFields') && dependencies('import/getFileFormatJSONFromFileFormat'))
    {
        
        $strSQL = "select g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_filename filename from ~TABLENAMEFILEFORMAT~ where id = ~FILEFORMATID~";
        $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        $strFilename = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (strlen($strFilename) > 0 && file_exists(FILEFORMAT_PATH . $strFilename) )
        {
            $strJSON = loadFile(FILEFORMAT_PATH . $strFilename);            
        }
        else
        {
            $strJSON = getFileFormatJSONFromFileFormat($objConn_a, $strClientID_a, $strFileFormatID_a);
        }

        $objJSON = json_decode($strJSON, true);
        $arrFields = $objJSON["fields"];

        $arrResult = fileFormatMassageFields($objConn_a, $strFileFormatID_a, $arrFields, $blnRequireIDs_a);
    }
    
    return $arrResult;
}
