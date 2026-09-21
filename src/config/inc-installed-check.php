<?php

		$strInstalledBuild = '';
		$strInstalledDate = '';
		$strInstalledVersion = '';
		$strInstalledDescription = '';
		$strInstalledFilename = '';
		$strInstalledURL = '';
		$strInstalledMoreInfo = '';
		
		$strDBInstalledDate = '';
		$strDBInstalledVersion = '';
		$strDBInstalledDescription = '';
		
		if (file_exists('../application.json'))
		{
			$strMetaData = loadFile('../application.json');
			$arrJSON = json_decode($strMetaData, true);

			$strInstalledBuild = $arrJSON['build'];
			$strInstalledDate = $arrJSON['date'];
			$strInstalledVersion = $arrJSON['version'];
			$strInstalledDescription = $arrJSON['description'];
			$strInstalledFilename = $arrJSON['filename'];
			$strInstalledURL = $arrJSON['url'];

			$strInstalledMoreInfo = '';
			
			$blnInstalled = true;
		}

		// if (file_exists('../database.json'))
		// {
			// $strMetaData = loadFile('../database.json');
			// $arrJSON = json_decode($strMetaData, true);

			// $strDBInstalledDate = $arrJSON['date'];
			// $strDBInstalledVersion = $arrJSON['dbversion'];
			// $strDBInstalledDescription = $arrJSON['description'];
		// }

?>
