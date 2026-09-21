<?php
function createDataAccessor($objConn_a, $strEntityID_a, $strEntityCode_a)
{
    $strTableNameSystemForm = getTableNameEntity("systemform", false);

    $blnResult = false;

    $strEntityID = $strEntityID_a;
    $strEntityCode = $strEntityCode_a;

    $strEntityGUID = "";
    $arrSectionData = [];

    $strSQL = "select jsondata from ~TABLENAMESYSTEMFORM~ where dataentity_id = '~ENTITYID~'";
    $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
    $strSQL = str_replace('~ENTITYID~', ffn($strEntityID), $strSQL);
    $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    if (strlen($strJSONData) > 0)
    {
        $arrJSONData = json_decode($strJSONData, true);
        
        foreach ($arrJSONData as $arrSection)
        {
            if ($arrSection['sectiontype'] === 'DATA')
            {
                $arrSectionData[] = $arrSection;
            }
        }

        if (count($arrSectionData) > 0)
        {
            $strEntityGUID = $arrSectionData[0]['sectioncode'];

            if (count($arrSectionData) > 1)
            {
                // log build - "[ENTITYCODE] has $count data forms
                logBuild("$strEntityCode has " . count($arrSectionData). " data forms");
            }
        }
        else
        {   
            // log build - "[ENTITYCODE] has no data form
            logBuild("$strEntityCode has no data form");
        }
    }
    else
    {
        // log build "[ENTITYCODE] has no systemform (Published Form)"
        logBuild("$strEntityCode has no systemform (Published Form)");
    }

    $strTemplateContent = loadFile(WS_PATH . 'modules/entity/dataaccess/template/template.php');

    // replace placeholders
    $strTemplateContent = str_replace('%%ENTITY_LCASE%%', strtolower($strEntityCode), $strTemplateContent);
    $strTemplateContent = str_replace('%%ENTITY_UCASE%%', strtoupper($strEntityCode), $strTemplateContent);
    $strTemplateContent = str_replace('%%ENTITY_GUID%%', $strEntityGUID, $strTemplateContent);

    $strEntityDataAccessorFile = WS_PATH . 'modules/entity/dataaccess/' . strtolower($strEntityCode) . '.php';

    saveFile($strEntityDataAccessorFile, $strTemplateContent);

    if (file_exists($strEntityDataAccessorFile))
    {
        $blnResult = true;
    }

    return $blnResult;
}