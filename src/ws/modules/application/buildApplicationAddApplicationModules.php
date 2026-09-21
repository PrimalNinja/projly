<?php

function buildApplicationAddApplicationModules(&$arrApplicationModulesToInclude_a, &$arrApplicationModulesToCheck_a, $arrApplicationModuleIDs_a)
{
    foreach ($arrApplicationModuleIDs_a as $strApplicationModuleID)
    {   
        if ((!in_array($strApplicationModuleID, $arrApplicationModulesToInclude_a)) && (!in_array($strApplicationModuleID, $arrApplicationModulesToCheck_a)))
        {
            array_push($arrApplicationModulesToCheck_a, $strApplicationModuleID);
        }

        if (!in_array($strApplicationModuleID, $arrApplicationModulesToInclude_a))
        {
            array_push($arrApplicationModulesToInclude_a, $strApplicationModuleID);
        }
    }
}
