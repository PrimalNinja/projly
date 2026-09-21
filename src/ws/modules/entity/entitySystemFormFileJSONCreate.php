<?php
function entitySystemFormFileJSONCreate($strSystemForm_a, $arrJSONData_a)
{
    $strFile = WS_PATH . 'modules/entity/forms/' . strtolower($strSystemForm_a) . '.json';

    $strJSONData = json_encode($arrJSONData_a, JSON_PRETTY_PRINT);

    return saveFile($strFile, $strJSONData);
}