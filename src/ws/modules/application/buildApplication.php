<?php

function buildApplication($objConn_a, $strApplicationID_a)
{
    $strCodeFile = ''; // resulting code file
    $arrModulesToCheck = array(); // modules we need to check
    $arrModulesToInclude = array(); // we have checked these and need to include them
    $arrApplicationModulesToCheck = array(); // application modules we need to check
    $arrApplicationModulesToInclude = array(); // we have checked these and need to include them
    
    if (dependencies('application/getApplicationModules,application/getApplicationModuleModules,application/getModuleModules,application/buildApplicationAddModules,application/buildApplicationAddApplicationModules') &&
		dependencies('application/getModuleLogic,application/getApplicationModuleLogic'))
    {
		$arrApplicationModuleModules = getApplicationModuleModules($objConn_a, $strApplicationID_a);
		buildApplicationAddModules($arrModulesToInclude, $arrModulesToCheck, $arrApplicationModuleModules);
		
        $arrApplicationModules = getApplicationModules($objConn_a, $strApplicationID_a);	// REMOVE
        buildApplicationAddApplicationModules($arrApplicationModulesToInclude, $arrApplicationModulesToCheck, $arrApplicationModules);	// REMOVE
        
        // process the modules to Check
        /*
        for ($intI = 0; $intI < count($arrModulesToCheck); $intI++)
        {
            $strModuleID = $arrModulesToCheck[$intI];
            $arrModuleModules = getModuleModules($objConn_a, $strModuleID);
            buildApplicationAddModules($arrModulesToInclude, $arrModulesToCheck, $arrModuleModules);
        }
        */

        while (count($arrModulesToCheck) > 0)
        {
            $strModuleID = array_pop($arrModulesToCheck); //$arrModulesToCheck[$intI];
            $arrModuleModules = getModuleModules($objConn_a, $strModuleID);
            buildApplicationAddModules($arrModulesToInclude, $arrModulesToCheck, $arrModuleModules);
        }

        // process the applicaiton modules to Check (FUTURE)
        //while (count($arrApplicationModulesToCheck) > 0)
        //{
        //$intApplicationModuleID = array_pop($arrApplicationModulesToCheck);
        //$arrApplicationModule_ApplicaitonModules = getApplicationModule_ApplicationModules($intApplicationModuleID);
        //buildApplicationAddModules(&$arrModulesToInclude, &$arrApplicationModulesToCheck, $arrApplicationModule_ApplicaitonModules);
        //}

        $arrModulesToInclude = array_reverse($arrModulesToInclude);

        // build code file (modules)
        for ($intI = 0; $intI < count($arrModulesToInclude); $intI++)
        {
            $strModuleID = $arrModulesToInclude[$intI];

            // append the code logic of $strModuleID to $strCodeFile
            $strCodeFile .= getModuleLogic($objConn_a, $strModuleID) . PHP_EOL;
        }
        
        // build code file (application modules)
        for ($intI = 0; $intI < count($arrApplicationModulesToInclude); $intI++)
        {
            $strApplicationModuleID = $arrApplicationModulesToInclude[$intI];

            $strCodeFile .= getApplicationModuleLogic($objConn_a, $strApplicationModuleID) . PHP_EOL;
        }  
        // append the code logic of $strModuleID to $strCodeFile
		$strCodeFile = '(function() {' . $strCodeFile . '})()';
    }

    return $strCodeFile;
}