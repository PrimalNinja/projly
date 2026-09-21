<?php
function getFileFormatJSONFromFileFormat($objConn_a, $strClientID_a, $strFileFormatID_a)
{
    $strTableNameFileDateType = getTableNameEntity('filedatatype', false);
    $strTableNameFileFormat = getTableNameEntity('fileformat', false);
    $strTableNameFileFormatField = getTableNameEntity('fileformat_field', false);
    
    $objJSONResult = new genericObject();
    $strJSONResult = "";
    $arrFields = array();

    if (dependencies('import/fileFormatMassageFields') )
    {
        $strSQL = "select g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_description description, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype fileformattype, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_filename filename, g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows headerrows
                    from ~TABLENAMEFILEFORMAT~ where id = ~FILEFORMATID~";
        $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormat), $strSQL);
        $strSQL = str_replace('~FILEFORMATID~', ff($strFileFormatID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        $strFileFormatDescription = "";
        $strFormatType = "";
        $strFilename = "";
        $strHeaderRows = "";

        if ($arrRow = dbReadRecord($objResult))
        {           
            $strFileFormatDescription = $arrRow['description'];
            $strFormatType = $arrRow['fileformattype'];
            $strFilename = $arrRow['filename'];
            $strHeaderRows = $arrRow['headerrows'];
        }
        dbCloseRecordset($objResult);
        
        $arrFields = fileFormatMassageFields($objConn_a, $strFileFormatID_a, [], false);;

        if (strlen($strFilename) > 0 && count($arrFields) > 0)
        {
            $objJSONResult->description = $strFileFormatDescription;
            $objJSONResult->formattype = $strFormatType;
            $objJSONResult->headrows = $strHeaderRows;
            $objJSONResult->fields = $arrFields;

            $strJSONResult = json_encode($objJSONResult, JSON_PRETTY_PRINT);

            saveFile(FILEFORMAT_PATH . $strFilename, $strJSONResult);
        }
    }

    return $strJSONResult;
}