<?php
function entitySystemFormFileJSON($strSystemForm_a)
{
    $strResult = "";

    $strFile = WS_PATH . 'modules/entity/forms/' . strtolower($strSystemForm_a) . '.json';

    if (file_exists($strFile))
    {
        $strResult = loadFile($strFile);
    }

    return $strResult;
}