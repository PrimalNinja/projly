<?php

function appendFile($strFilePath_a, $strContent_a)
{
	$objHandle = fopen($strFilePath_a, 'a') or die("can't open file");
	fwrite($objHandle, $strContent_a);
	fclose($objHandle);
}

function checkArray($arr_a, $strEntry_a)
{
	$blnResult = false;

	foreach ($arr_a as $strEntry)
	{
		if ($strEntry == $strEntry_a)
		{
			$blnResult = true;
		}
	}
	
	return $blnResult;
}

function cleanFilename($strFilename_a)
{ 
    $strFilename = trim($strFilename_a); 
    $strRemove  = array("([\40])", "([^a-zA-Z0-9-])", "(-{2,})");
    $strReplace = array("-", "", "-");
    return preg_replace($strRemove, $strReplace, $strFilename);
} 

function configureApplication($strFolder_a, $objConfiguration_a)
{
	$blnResult = true;

	$arrFiles = $objConfiguration_a['files'];
	
	for ($intI = 0; $intI < count($arrFiles); $intI++)
	{
		$objFile = $arrFiles[$intI];
		
		$strSource = $objFile['source'];
		$strDestination = $objFile['destination'];
		$objSettings = $objFile['settings'];
		
		$strFile = loadFile($strFolder_a . '/' . $strSource);
		if (strlen($strFile) > 0)
		{
			foreach ($objSettings as $strKey => $strValue) 
			{
				$strKey = "%" . strtoupper($strKey) . "%";
				$strFile = str_replace($strKey, $strValue, $strFile);
			}
			
			deleteFile($strFolder_a . '/' . $strDestination);
			saveFile($strFolder_a . '/' . $strDestination, $strFile);
		}
		else
		{
			$blnResult = false;
		}
	}
	
	return $blnResult;
}

function copyFile($strSource_a, $strDestination_a)
{
	$bnlResult = false;
	
	if (is_file($strSource_a))
	{
		logDebug('copyFile: ' . $strSource_a . ',' . $strDestination_a, '');
		$blnResult = @copy($strSource_a, $strDestination_a);
	}
	
	return $blnResult;
}

function copyFolder($strSource_a, $strDestination_a, $blnRecurse_a)
{
	$blnResult = false;
	
	if (is_dir($strSource_a))
	{
		$blnResult = createFolder($strDestination_a, $blnRecurse_a);
		
		$objDir = dir($strSource_a);
		
		while (($blnResult) && (false !== ($strDirEntry = $objDir->read())))
		{
			if ($strDirEntry == '.' || $strDirEntry == '..') 
			{
				// do nothing
			}
			else
			{
				$blnX = true;
				if (is_dir($strSource_a . '/' . $strDirEntry)) 
				{
					if ($blnRecurse_a)
					{
						$blnX = copyFolder($strSource_a . '/' . $strDirEntry, $strDestination_a . '/' . $strDirEntry, $blnRecurse_a);
					}
				}
				else
				{
					$blnX = copyFile($strSource_a . '/' . $strDirEntry, $strDestination_a . '/' . $strDirEntry);
				}
				
				if ($blnX == false)
				{
					$blnResult = false;
				}
			}
		}

		$objDir->close();
	}
	
	return $blnResult;
}

function createFolder($strFolder_a, $blnRecurse_a)
{
	// create non existing directories
	if(!file_exists($strFolder_a))
	{
		if ($blnRecurse_a)
		{
			$strTemp = '';
			foreach(explode('/', $strFolder_a) AS $strFolderName)
			{
				$strTemp .= $strFolderName . '/';
				if(!file_exists($strTemp) )
				{
					//echo('mkdir: ' . $strTemp . "<br>");
					logDebug('createFolder: ' . $strTemp, '');
					@mkdir($strTemp, 0777);
				}
			}
		}
		else
		{
			logDebug('createFolder: ' . $strFolder_a, '');
			@mkdir($strFolder_a, 0777);
		}
	}

	return is_dir($strFolder_a);
}

// player image & thumbnail
define('THUMBNAIL_SIZE', 190);

function createThumbnail($strPicturePath_a)
{
	// decode the image
	$strPicture = loadFile($strPicturePath_a);
	$imgPicture = imagecreatefromstring($strPicture);

	// create a thumbnail
	$imgThumbnail = imagecreatetruecolor(THUMBNAIL_SIZE, THUMBNAIL_SIZE);

	//function fastimagecopyresampled(&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) 
	if (imagesx($imgPicture) > imagesy($imgPicture))
	{
		// lose horizontal by $intHalf left and right
		$intWhole = (imagesx($imgPicture) - imagesy($imgPicture));
		$intHalf =  $intWhole * 0.5;
		fastimagecopyresampled($imgThumbnail, $imgPicture, 0, 0, $intHalf, 0, THUMBNAIL_SIZE, THUMBNAIL_SIZE, (imagesx($imgPicture)-$intWhole), imagesy($imgPicture));
	}
	else if (imagesy($imgPicture) > imagesx($imgPicture))
	{
		// lose vertical by $intHalf top and bottom
		$intWhole = (imagesy($imgPicture) - imagesx($imgPicture));
		$intHalf = $intWhole * 0.5;
		//debug(imagesx($imgPicture) . ":" . imagesy($imgPicture) . ":" . $intHalf . ":" . (imagesy($imgPicture)-$intHalf));
		fastimagecopyresampled($imgThumbnail, $imgPicture, 0, 0, 0, $intHalf, THUMBNAIL_SIZE, THUMBNAIL_SIZE, imagesx($imgPicture), (imagesy($imgPicture)-$intWhole));
	}
	else
	{
		fastimagecopyresampled($imgThumbnail, $imgPicture, 0, 0, 0, 0, THUMBNAIL_SIZE, THUMBNAIL_SIZE, imagesx($imgPicture), imagesy($imgPicture));
	}	

	// save the thumbnail	
	$strDirName = pathinfo($strPicturePath_a, PATHINFO_DIRNAME);
	$strFilename = pathinfo($strPicturePath_a, PATHINFO_FILENAME);
	$strExtension = pathinfo($strPicturePath_a, PATHINFO_EXTENSION);
	imagejpeg($imgThumbnail, $strDirName . '/' . $strFilename . '-thumb.' . $strExtension);

	imagedestroy($imgPicture);
	imagedestroy($imgThumbnail);
}

function deleteFile($strFile_a)
{
	$blnResult = false;

	if (file_exists($strFile_a))
	{
		if (is_file($strFile_a))
		{
			logDebug('deleteFile: ' . $strFile_a, '');
			$blnResult = @unlink($strFile_a);
		}
	}
	else
	{
		$blnResult = true;
	}
	
	return $blnResult;
}

function deleteFolder($strFolder_a, $blnRecurse_a)
{
	$blnResult = false;
	
	if (is_dir($strFolder_a))
	{
		if ($blnRecurse_a)
		{
			$blnResult = deleteFolderContent($strFolder_a, array(), $blnRecurse_a);
		}
		
		if ($blnResult)
		{
			logDebug('deleteFolder: ' . $strFolder_a, '');
			$blnResult = @rmdir($strFolder_a);
		}
	}
	
	return $blnResult;
}

function deleteFolderContent($strFolder_a, $arrExclude_a, $blnRecurse_a)
{
	$blnResult = false;

	if (is_dir($strFolder_a))
	{
		$objDir = dir($strFolder_a);
		
		$blnResult = true;
		while (($blnResult) && (false !== ($strDirEntry = $objDir->read())))
		{
			if ($strDirEntry == '.' || $strDirEntry == '..') 
			{
				// do nothing
			}
			else
			{
				$blnX = true;
				if (is_dir($strFolder_a . '/' . $strDirEntry)) 
				{
					if ($blnRecurse_a)
					{
						$blnExclude = checkArray($arrExclude_a, $strDirEntry);
						if ($blnExclude)
						{
							// do nothing
						}
						else
						{
							$blnX = deleteFolderContent($strFolder_a . '/' . $strDirEntry, array(), $blnRecurse_a);
							if ($blnX)
							{
								logDebug('deleteFolder: ' . $strFolder_a . '/' . $strDirEntry, '');
								$blnX = @rmdir($strFolder_a . '/' . $strDirEntry);
							}
						}
					}
				}
				else
				{
					$blnExclude = checkArray($arrExclude_a, $strDirEntry);
					if ($blnExclude)
					{
						// do nothing
					}
					else
					{
						$blnX = deleteFile($strFolder_a . '/' . $strDirEntry);
					}
				}
				
				if ($blnX == false)
				{
					$blnResult = false;
				}
			}
		}

		$objDir->close();
	}
	
	return $blnResult;
}

function fastimagecopyresampled(&$dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3) 
{
	// Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
	// Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
  	// Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
  	// Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
  	//
  	// Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
  	// Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
  	// 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
  	// 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
  	// 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
  	// 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
  	// 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled	.

  	if (empty($src_image) || empty($dst_image) || $quality <= 0) 
  	{ 
  		return false; 
  	}
  
  	if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) 
  	{
    	$temp = imagecreatetruecolor ($dst_w * $quality + 1, $dst_h * $quality + 1);
    	imagecopyresized ($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
    	imagecopyresampled ($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
    	imagedestroy ($temp);
  	} 
  	else 
 	{
  		imagecopyresampled ($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
  	}
  
  	return true;
}

// return the path of a file within the cluster
function getClusterPath($strRoot_a, $strClusterKey_a, $blnAddClusterKey_a, $blnDynamic_a)
{
	$strResult = "";
	
	//return $strRoot_a . '/';	// useful for debugging to force all images into the root
	
	if (strlen($strClusterKey_a) > 2)
	{
		// code this to convert the first 5 letters into a dir structure, ie: $strRoot_a = 'storecluster1', $strClusterKey_a = 'laserdisc.csv', $blnAddClusterKey_a = TRUE
		// gives storecluster1/l/a/s/e/r/laserdisc.csv
		// if blnAddClusterKey_a = FALSE, then gives storecluster1/l/a/s
		$strResult = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1) . '/' . substr($strClusterKey_a, 2, 1) . '/' . substr($strClusterKey_a, 3, 1) . '/' . substr($strClusterKey_a, 4, 1) . '/';
		if ($blnDynamic_a)
		{
			$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1);
			@mkdir($strPath, 0777); 
			$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1);
			@mkdir($strPath, 0777); 
			$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1) . '/' . substr($strClusterKey_a, 2, 1);
			@mkdir($strPath, 0777); 
			$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1) . '/' . substr($strClusterKey_a, 2, 1) . '/' . substr($strClusterKey_a, 3, 1);
			@mkdir($strPath, 0777); 
			$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1) . '/' . substr($strClusterKey_a, 2, 1) . '/' . substr($strClusterKey_a, 3, 1) . '/' . substr($strClusterKey_a, 4, 1);
			@mkdir($strPath, 0777); 
			if ($blnAddClusterKey_a)
			{
				$strPath = $strRoot_a . substr($strClusterKey_a, 0, 1) . '/' . substr($strClusterKey_a, 1, 1) . '/' . substr($strClusterKey_a, 2, 1) . '/' . substr($strClusterKey_a, 3, 1) . '/' . substr($strClusterKey_a, 4, 1) . '/' . $strClusterKey_a;
				@mkdir($strPath, 0777); 
			}
		}
		if ($blnAddClusterKey_a)
		{
			$strResult .= $strClusterKey_a . '/';
		}
	}
	else
	{
		$strResult = $strRoot_a;
	}
	
	return $strResult;
}

function getCurrentWD()
{
	$strResult = getcwd();
	$strResult = str_replace("\\", "/", $strResult);
	return $strResult;
}

function getFileDateTime()
{
	return date(USER_DATETIME);
}

function getFiles($strPath_a, $blnIncludeSystemFiles_a)
{ 
    $arrResult = array();

    if (substr($strPath_a, -1) !== '/')
	{
		$strPath_a .= '/';
	}
	
    if ($objHandle = opendir($strPath_a))
    { 
        while (false !== ($strFile = readdir($objHandle)))
        { 
            if (filetype($strPath_a . $strFile) === 'file')
            { 
                clearstatcache(); 
				
                if ($blnIncludeSystemFiles_a == TRUE)
                {
					$arrResult[] = $strFile;
                }
                else
                {
                	if ((substr($strFile, 0, 1) !== '.') && (substr($strFile, 0, 1) !== '_')) 
                	{
						$arrResult[] = $strFile;
                	}
                }
            } 
        } 
        closedir($objHandle); 
    } 
	
	asort($arrResult);
    return $arrResult; 
}

function getFolders($strPath_a)
{ 
    $arrResult = array();

    if (substr($strPath_a, -1) !== '/')
	{
		$strPath_a .= '/';
	}
	
    if ($objHandle = opendir($strPath_a))
    { 
        while (false !== ($strFolder = readdir($objHandle)))
        { 
            if (filetype($strPath_a . $strFolder) === 'dir')
            { 
                clearstatcache(); 
				
				$arrResult[] = $strFolder;
            } 
        } 
        closedir($objHandle); 
    } 
    return $arrResult; 
}

function getFilenameGUID()
{
	return strrev(cleanFilename(uniqid('', true)));
}

function loadFile($strFilePath_a)
{
	$strResult = "";
	$intFileSize = filesize($strFilePath_a);

	if ($intFileSize > 0)
	{
		$objHandle = @fopen($strFilePath_a, 'r') or die();
		$strResult = fread($objHandle, $intFileSize);
		fclose($objHandle);
	}
	
	//$strResult = file_get_contents($strFilePath_a);	// note: also works but not sure the benefit compared to above

	return $strResult;
}

function saveFile($strFilePath_a, $strContent_a)
{
	$objHandle = @fopen($strFilePath_a, 'w') or die();
	fwrite($objHandle, $strContent_a);
	fclose($objHandle);
}

function unzipFile($strFile_a, $strFolder_a)
{ 
	$blnResult = true;
	$intFileCount = 0;
	$intProgress = 0;
	
	// if we want a progress indicator we need to count the files first
	updateProgress(0);

	$objZip = zip_open($strFile_a); 
	if (is_resource($objZip))
	{ 
		while ($objZipEntry = zip_read($objZip))
		{ 
			$intFileCount++;
		}
		zip_close($objZip);
	}

	// open the zip file for processing
	$objZip = zip_open($strFile_a); 
	if (is_resource($objZip))
	{ 
		$intI = 0;
		while ($objZipEntry = zip_read($objZip))
		{ 
			$strPathToFileInZip = dirname(zip_entry_name($objZipEntry));
			$strFilenameFromZip = zip_entry_name($objZipEntry);

			// create non existing directories
			$blnX = createFolder($strFolder_a . $strPathToFileInZip, true);
			if ($blnX == false) { $blnResult = false; }

			if (zip_entry_open($objZip, $objZipEntry, "r"))
			{
				//echo('extract: ' . $strFolder_a . $strFilenameFromZip . "...");
				logDebug('extractFile: ' . $strFolder_a . $strFilenameFromZip, '');
				if ($objFile = @fopen($strFolder_a . $strFilenameFromZip, 'wb'))
				{
					//echo('OK<br>');
					//fwrite($objFile, zip_entry_read($objZipEntry, zip_entry_filesize($objZipEntry)));
					$intFileSize = zip_entry_filesize($objZipEntry);
					while($intFileSize > 0)
					{
						$intChunkSize = ($intFileSize > 10240) ? 10240 : $intFileSize;
						$intFileSize -= $intChunkSize;
						$binChunk = zip_entry_read($objZipEntry, $intChunkSize);
						if($binChunk !== false) fwrite($objFile, $binChunk);
					}
					fclose($objFile);
				}
				else
				{
					//echo('ERROR<br>');
					// an empty directory
					$blnX = createFolder($strFolder_a . $strFilenameFromZip, false);
					if ($blnX == false) { $blnResult = false; }
				}
				zip_entry_close($objZipEntry);
			}

			$intLastProgressUpdate = $intProgress;
			$intProgress = intval(100 / $intFileCount * $intI);
			if (($intProgress % 5) == 0 && ($intLastProgressUpdate <> $intProgress))
			{
				updateProgress($intProgress);
			}
 			$intI++;
		} 
		updateProgress(100);

		zip_close($objZip);
	}
	else
	{ 
		$blnResult = false;
	} 
	
	return $blnResult;
}

function updateProgress($intPercentage_a)
{
	//echo($intPercentage_a . '<br>');
}

function zipFolder($strFolder_a, $strArchive_a, $arrExclude_a, $blnRecurse_a)
{ 
	$blnResult = false;

	$objArchive = new ZipArchive();
	if($objArchive->open($strArchive_a, ZIPARCHIVE::CREATE) === TRUE)
	{
		$blnResult = zipFolder2($strFolder_a, $objArchive, $arrExclude_a, $blnRecurse_a, $strFolder_a);
		$objArchive->close();
	}
	
	return $blnResult;
}

function zipFolder2($strFolder_a, $objArchive_a, $arrExclude_a, $blnRecurse_a, $strOriginalFolder_a)
{ 
	$blnResult = false;
	
	if (is_dir($strFolder_a))
	{
		$objDir = dir($strFolder_a);
		
		$blnResult = true;
		while (($blnResult) && (false !== ($strDirEntry = $objDir->read())))
		{
			if ($strDirEntry == '.' || $strDirEntry == '..') 
			{
				// do nothing
			}
			else
			{
				$blnX = true;
				if (is_dir($strFolder_a . '/' . $strDirEntry)) 
				{
					if ($blnRecurse_a)
					{
						$blnExclude = checkArray($arrExclude_a, $strDirEntry);
						if ($blnExclude)
						{
							// do nothing
						//echo('exclude:' . $strDirEntry . '<br>');
						}
						else
						{
						//echo('include:' . $strDirEntry . '<br>');
							$blnX = zipFolder2($strFolder_a . '/' . $strDirEntry, $objArchive_a, array(), $blnRecurse_a, $strOriginalFolder_a);
						}
					}
				}
				else
				{
					$strZipEntry = $strFolder_a;
					if (substr($strZipEntry, 0, strlen($strOriginalFolder_a)) == $strOriginalFolder_a)
					{
						$strZipEntry = substr($strZipEntry, strlen($strOriginalFolder_a) + 1, (strlen($strZipEntry) - strlen($strOriginalFolder_a) - 1));
					}
					
					if (strlen($strZipEntry) > 0)
					{
						$strZipEntry .= '/'; 
					}
					
					$strZipEntry .= $strDirEntry;
//echo($strZipEntry . "<br>");

					$blnExclude = checkArray($arrExclude_a, $strZipEntry);
					if ($blnExclude)
					{
						// do nothing
					//echo('exclude:' . $strZipEntry . '<br>');
					}
					else
					{
					//echo('include:' . $strZipEntry . '<br>');
						logDebug('addFile: ' . $strFolder_a . '/' . $strDirEntry, '');
						$blnX = $objArchive_a->addFile($strFolder_a . '/' . $strDirEntry, $strZipEntry);
					}
				}
				
				if ($blnX == false)
				{
					$blnResult = false;
				}
			}
		}

		$objDir->close();
	}
	
	return $blnResult;
}

// $strFilePath_a = full path including filename
// $strFileName_a = filename only to store
// $strArchive_a = resulting zip filename
function zipSingleFile($strFilePath_a, $strFileName_a, $strArchive_a)
{
	$objArchive = new ZipArchive();
	if($objArchive->open($strArchive_a, ZIPARCHIVE::CREATE) === TRUE)
	{
		$objArchive->addFile($strFilePath_a, $strFileName_a);
		$objArchive->close();
	}
}

?>
