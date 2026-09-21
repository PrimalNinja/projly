<?php

// examples:
//	'core' 'scan' forms print
//	["core"] 'scan' forms print
class rpnCLI_forms
{
	function execute($objRPNCLI)
	{
		$arrFiles = array();
		$arrModules = array();
		$arrResult = array();
		$strModule = '';

		$strSource = $objRPNCLI->popString();
		$varOperand = $objRPNCLI->popData();
		
		// is the source 'scan' or 'file'
		$strSource = $objRPNCLI->stripString($strSource);

		// handles strings & array paramters
		if ($varOperand["type"] == 'json')
		{
			$arrModules = $varOperand["value"];
		}
		else if ($varOperand["type"] == 'string')
		{
			$strModule = $varOperand["value"];
			array_push($arrModules, $strModule);
		}

		// processes each module to get a form list
		for ($intI = 0; $intI < count($arrModules); $intI++)
		{
			$strModule = $arrModules[$intI];
			$strModule = $objRPNCLI->stripString($strModule);
		
			if ($strSource == 'file')
			{
				// get the file list form the .json file
				$strPath = APP_PATH . DYNAMIC_APP_DIR . 'modules/' . $strModule . '.json';
				if (file_exists($strPath))
				{
					$strFiles = file_get_contents($strPath);
					$arrFiles = json_decode($strFiles);
				}
			}
			else if ($strSource == 'scan')
			{
				// get the file list form a dir scan
				$strPath = APP_PATH . DYNAMIC_APP_DIR . 'modules/' . $strModule . '/';
				$arrFiles = scandir($strPath);
			}

			// process the file list and add it to the array if it doesn't exist
			for ($intJ = 0; $intJ < count($arrFiles); $intJ++)
			{
				$strFile = $arrFiles[$intJ];
				if (($strFile != '.') && ($strFile != '..'))
				{
					$arrFile = explode(".", $strFile);
					$strFile = $arrFile[0];
					if (!in_array($strModule . '/' . $strFile, $arrResult))
					{
						array_push($arrResult, $strModule . '/' . $strFile);
					}
				}
			}
		}
		
		// return the result
		$objRPNCLI->pushJSON($arrResult);
	}
}

?>
