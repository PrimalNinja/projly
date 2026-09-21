<?php

// examples:
//	servercheck print
//	servercheck print
class rpnCLI_servercheck
{
	function execute($objRPNCLI)
	{
		$arrModules = array();
		$arrResult = array();
		$arrWebServices = array();
		$arrWebServiceTemp = array();
		$strModule = '';

		// fetch files
		//$varOperand = $objRPNCLI->popData();

		// handles strings & array paramters
		// if ($varOperand["type"] == 'json')
		// {
			// $arrModules = $varOperand["value"];
		// }
		// else if ($varOperand["type"] == 'string')
		// {
			// $strModule = $varOperand["value"];
			// array_push($arrModules, $strModule);
		// }
		
		$strPath = APP_PATH . 'ws/modules/*.json';
		$arrModules = glob($strPath, GLOB_BRACE);
		for ($intI = 0; $intI < count($arrModules); $intI++)
		{
			$arrModules[$intI] = str_replace(APP_PATH . 'ws/modules/', "", $arrModules[$intI]);
			$arrModules[$intI] = str_replace('.json', "", $arrModules[$intI]);
		}

		// processes each module to get a form list
		for ($intI = 0; $intI < count($arrModules); $intI++)
		{
			$strModule = $arrModules[$intI];
			$strModule = $objRPNCLI->stripString($strModule);
		
			// get the file list form the .json file
			$strPath = APP_PATH . 'ws/modules/' . $strModule . '.json';
			if (file_exists($strPath))
			{
				$strWebServiceTemp = file_get_contents($strPath);
				$arrWebServiceTemp = json_decode($strWebServiceTemp);
			}

			// process the file list and add it to the array if it doesn't exist
			for ($intJ = 0; $intJ < count($arrWebServiceTemp); $intJ++)
			{
				$objWebService = $arrWebServiceTemp[$intJ];
				array_push($arrWebServices, $objWebService);
			}
		}

		// processes each module to get a form list
		for ($intI = 0; $intI < count($arrWebServices); $intI++)
		{
			$objWebService = $arrWebServices[$intI];
			
			$strBasePath = APP_PATH . 'ws/modules/';

			// validations
			$blnNoDependencies = false;
			$blnNoFunctions = false;
			$blnNoPermissions = false;
			
			$arrDependencies = array();
			$arrFunctions = array();
			$arrPermissions = array();

			$arrDependencies = $objWebService->dependencies;
			$arrFunctions = $objWebService->functions;
			$arrPermissions = $objWebService->permissions;

			// start with flagging them as missing as later we will flag if explicitly not required
			$blnNoDependencies = (count($arrDependencies) == 0);
			$blnNoFunctions = (count($arrFunctions) == 0);
			$blnNoPermissions = (count($arrPermissions) == 0);

			$arrErrors = array();
			
			for ($intJ = 0; $intJ < count($arrDependencies); $intJ++)
			{
				$strDependency = $arrDependencies[$intJ];
				$strCodeFile = '';
				
				$blnCodeFileExists = file_exists($strBasePath . $strDependency . '.php');
				
				if (!$blnCodeFileExists) { $arrErrors[] = 'missing file .php'; }

				// read code file
				if ($blnCodeFileExists)
				{
					$strCodeFile = file_get_contents($strBasePath . $strDependency . '.php');
					
					// permission check
					for ($intK = 0; $intK < count($arrPermissions); $intK++)
					{
						$strPermission = $arrPermissions[$intK];

						if (strlen($strPermission) == 0)
						{
							$arrErrors[] = 'empty permission';
						}
						else if ($strPermission == 'NOT REQUIRED')
						{
							$blnNoPermissions = false;
						}
						else
						{
							if (InStr($strCodeFile, $strPermission) >= 0)
							{
								// found
							}
							else
							{
								$arrErrors[] = 'missing permission ' . $strPermission;
							}
						}
					}
				}
			}

			if ($blnNoDependencies) { $arrErrors[] = 'no dependencies checked'; }
			if ($blnNoFunctions) { $arrErrors[] = 'no functions checked'; }
			if ($blnNoPermissions) { $arrErrors[] = 'no permissions checked'; }
				
			if (count($arrErrors) > 0)
			{
				$arrResult[] = array(
					"webservice" => $strModule . ' ' . $objWebService->name,
					"errors" => $arrErrors
				);
			}
		}
		
		// return the result
		$objRPNCLI->pushJSON($arrResult);
	}
}

?>
