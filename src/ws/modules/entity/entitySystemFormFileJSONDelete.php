<?php
function entitySystemFormFileJSONDelete($strSystemForm_a)
{
    $strFile = WS_PATH . 'modules/entity/forms/' . strtolower($strSystemForm_a) . '.json';
    
    return deleteFile($strFile);
}