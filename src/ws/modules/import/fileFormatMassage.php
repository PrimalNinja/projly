<?php

// massage a file format
function fileFormatMassage($objConn_a, $strFileTypeID_a, $strFormatTypeID_a, $objFileFormat_a, $blnRequireIDs_a)
{
    $arrResult = null;

    if (dependencies('import/fileFormatMassageFields'))
    {
        $arrResult = array();
        $arrFields = array();

        $strCode = elementString($objFileFormat_a, 'code', '');
        $strDescription = elementString($objFileFormat_a, 'description', '');
        $strHeaderRows = elementString($objFileFormat_a, 'headerrows', '0');
        $strFormatType = elementString($objFileFormat_a, 'formattype', '');
        $strIsImport = fixBoolean(elementString($objFileFormat_a, 'import', 'Y'));
        $strIsExport = fixBoolean(elementString($objFileFormat_a, 'export', 'Y'));
        $strIsEnabled = fixBoolean(elementString($objFileFormat_a, 'enabled', 'Y'));
        $arrFields = $objFileFormat_a['fields'];

        /*
        $strSQL = "select code returnvalue from ref_core_tblformattype where id = ~FORMATTYPEID~";
        $strSQL = str_replace('~FORMATTYPEID~', ff($strFormatTypeID_a), $strSQL);
        $strFormatTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        */

        $arrFields = fileFormatMassageFields($objConn_a, $strFileTypeID_a, $arrFields, $blnRequireIDs_a);

        $arrResult['code'] = $strCode;
        $arrResult['description'] = $strDescription;
        $arrResult['formattype'] = $strFormatType;
        $arrResult['headerrows'] = $strHeaderRows;
        $arrResult['fields'] = $arrFields;
    }

    return $arrResult;
}
