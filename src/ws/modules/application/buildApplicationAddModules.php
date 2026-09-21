<?php

function buildApplicationAddModules(&$arrModulesToInclude_a, &$arrModulesToCheck_a, $arrModuleIDs_a)
{
    foreach ($arrModuleIDs_a as $strModuleID)
    {           
        if ((!in_array( $strModuleID, $arrModulesToInclude_a)) && (!in_array( $strModuleID, $arrModulesToCheck_a)))
        {
            array_push($arrModulesToCheck_a, $strModuleID);
        }

        if (!in_array($strModuleID, $arrModulesToInclude_a))
        {
            array_push($arrModulesToInclude_a, $strModuleID);
        }
    }
}