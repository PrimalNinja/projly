<?php

// examples:
//	clientcheck print
//	clientcheck print
class rpnCLI_clientcheck
{
	function execute($objRPNCLI)
	{
		$arrFiles = array();
		$arrFilesList = array();
		$arrFilesScan = array();
		$arrFilesCombined = array();
		$arrModules = array();
		$arrResult = array();
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
		$strPath = APP_PATH . DYNAMIC_APP_DIR . 'modules/*.json';
		$arrModules = glob($strPath, GLOB_BRACE);
		for ($intI = 0; $intI < count($arrModules); $intI++)
		{
			$arrModules[$intI] = str_replace(APP_PATH . DYNAMIC_APP_DIR . 'modules/', "", $arrModules[$intI]);
			$arrModules[$intI] = str_replace('.json', "", $arrModules[$intI]);
		}

		// processes each module to get a form list
		for ($intI = 0; $intI < count($arrModules); $intI++)
		{
			$strModule = $arrModules[$intI];
			//$strModule = str_replace(".json", "", $strModule);
			$strModule = $objRPNCLI->stripString($strModule);
		
			// get the file list form the .json file
			$strPath = APP_PATH . DYNAMIC_APP_DIR . 'modules/' . $strModule . '.json';
			if (file_exists($strPath))
			{
				$strFiles = file_get_contents($strPath);
				$arrFilesList = json_decode($strFiles);
			}

			// get the file list form a dir scan
			$strPath = APP_PATH . DYNAMIC_APP_DIR . 'modules/' . $strModule . '/';
			$arrFilesScan = scandir($strPath);
			$arrFilesCombined = array_merge($arrFilesScan, $arrFilesList);
			asort($arrFilesCombined);

			// process the file list and add it to the array if it doesn't exist
			for ($intJ = 0; $intJ < count($arrFilesCombined); $intJ++)
			{
				$strFile = $arrFilesCombined[$intJ];
				if (($strFile != '.') && ($strFile != '..'))
				{
					$arrFile = explode(".", $strFile);
					$strFile = $arrFile[0];
					if (!in_array($strModule . '/' . $strFile, $arrFiles))
					{
						array_push($arrFiles, $strModule . '/' . $strFile);
					}
				}
			}
		}

		// processes each module to get a form list
		for ($intI = 0; $intI < count($arrFiles); $intI++)
		{
			$strFile = $arrFiles[$intI];
			$strFile = $objRPNCLI->stripString($strFile);

			// loop level variables
			$objMetaData = null;
			$strBasePath = APP_PATH . DYNAMIC_APP_DIR . 'modules/' . $strFile;
			$strCodeFile = '';

			// validations
			$blnJSONFileExists = false;
			$blnCodeFileExists = false;
			$blnHTMFileExists = false;
			$blnZFileExists = false;

			$blnNoCapabilities = false;
			$blnNoModules = false;
			$blnNoPermissions = false;
			$blnNoProperties = false;

			// simple checks
			$blnJSONFileExists = file_exists($strBasePath . '.json');
			$blnCodeFileExists = file_exists($strBasePath . '.js');
			$blnHTMFileExists = file_exists($strBasePath . '.htm');
			$blnZFileExists = file_exists($strBasePath . '.z.js');

			// results
			$arrErrors = array();
			if (!$blnJSONFileExists) { $arrErrors[] = 'missing file .json'; }
			if (!$blnCodeFileExists) { $arrErrors[] = 'missing file .js'; }
			if (!$blnHTMFileExists) { $arrErrors[] = 'missing file .htm'; }
			if (!$blnZFileExists) { $arrErrors[] = 'missing file .z'; }

			// read metadata file
			if ($blnJSONFileExists)
			{
				$strMetaData = file_get_contents($strBasePath . '.json');
				$objMetaData = json_decode($strMetaData);
			}

			// read code file
			if ($blnCodeFileExists)
			{
				$strCodeFile = file_get_contents($strBasePath . '.js');
			}

			// process the file against the metadata
			if (($blnJSONFileExists) && ($blnCodeFileExists))
			{
				$arrCapabilities = array();
				$arrModules = array();
				$arrPermissions = array();
				$arrProperties = array();

				$arrCapabilities = $objMetaData->capabilities;
				$arrModules = $objMetaData->modules;
				$arrPermissions = $objMetaData->permissions;
				$arrProperties = $objMetaData->properties;

				// start with flagging them as missing as later we will flag if explicitly not required
				$blnNoCapabilities = (count($arrCapabilities) == 0);
				$blnNoModules = (count($arrModules) == 0);
				$blnNoPermissions = (count($arrPermissions) == 0);
				$blnNoProperties = (count($arrProperties) == 0);
				
				// capability check
				for ($intJ = 0; $intJ < count($arrCapabilities); $intJ++)
				{
					$strCapability = $arrCapabilities[$intJ];
					if (strlen($strCapability) == 0)
					{
						$arrErrors[] = 'empty capability';
					}
					else if ($strCapability == 'NOT REQUIRED')
					{
						$blnNoCapabilities = false;
					}
					else
					{
						if (InStr($strCodeFile, $strCapability) >= 0)
						{
							// found
						}
						else
						{
							$arrErrors[] = 'missing capability ' . $strCapability;
						}
					}
				}

				// modules check
				for ($intJ = 0; $intJ < count($arrModules); $intJ++)
				{
					$strModule = $arrModules[$intJ];
					if (strlen($strModule) == 0)
					{
						$arrErrors[] = 'empty module';
					}
					else if ($strModule == 'NOT REQUIRED')
					{
						$blnNoModules = false;
					}
					else
					{
						if (InStr($strCodeFile, $strModule) >= 0)
						{
							// found
						}
						else
						{
							$arrErrors[] = 'missing module ' . $strModule;
						}
					}
				}

				// permission check
				for ($intJ = 0; $intJ < count($arrPermissions); $intJ++)
				{
					$strPermission = $arrPermissions[$intJ];

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

				// properties check
				for ($intJ = 0; $intJ < count($arrProperties); $intJ++)
				{
					$strProperty = $arrProperties[$intJ];
					if (strlen($strProperty) == 0)
					{
						$arrErrors[] = 'empty property';
					}
					else if ($strProperty == 'NOT REQUIRED')
					{
						$blnNoProperties = false;
					}
					else
					{
						if (InStr($strCodeFile, $strProperty) >= 0)
						{
							// found
						}
						else
						{
							$arrErrors[] = 'missing property ' . $strProperty;
						}
					}
				}

				if ($blnNoCapabilities) { $arrErrors[] = 'no capabilities checked'; }
				if ($blnNoModules) { $arrErrors[] = 'no modules checked'; }
				if ($blnNoPermissions) { $arrErrors[] = 'no permissions checked'; }
				if ($blnNoProperties) { $arrErrors[] = 'no properties checked'; }
			}
			
			if (count($arrErrors) > 0)
			{
				$arrResult[] = array(
					"file" => $strFile,
					"errors" => $arrErrors
				);
			}
		}
		
		// return the result
		$objRPNCLI->pushJSON($arrResult);
	}
}

?>
