<?php

function dependencies($strDependencies_a, $blnOptional_a = false)
{
	$blnResult = true;
	$blnError = false;
	
    $arrDependencies = explode(',', $strDependencies_a);
    foreach ($arrDependencies as $strDependency) 
	{
        if (strlen($strDependency) > 0) 
		{
            $strDependencyPath = WS_PATH . 'modules/' . $strDependency . '.php';

			if ($blnOptional_a == false)
			{
				if (file_exists($strDependencyPath) == false)
				{
					logMissing(true, "could not find: " . $strDependencyPath);
				}
			}

			if (file_exists($strDependencyPath))
			{
				require_once $strDependencyPath;
				$blnResult = true;
			}
			else
			{
				$blnError = true;
			}
        }
    }

    return ($blnResult && !$blnError);
}

// used for dev/testing only (inc-returnpage excluded as it aborts execution)
function dependenciesFetch($strDependency_a, $blnEcho_a = true)
{
    $strResult = "";

    if (dependencies('utils/general,utils/dates')) 
	{
        $strDependencyPath = WS_PATH . 'modules/' . $strDependency_a . '/';
        $arrFilenames = scandir($strDependencyPath);

		if ($blnEcho_a)
		{
			echo ('fetching ' . $strDependency_a . '<br>');
		}
        
		foreach ($arrFilenames as $strFilename) 
		{
            if ((strlen($strFilename) > 0) && (InStr($strFilename, '.php') >= 0)) 
			{
                if (($strFilename == "inc-returnpage.php") || ($strFilename == "framework.php")) 
				{
                    // do nothing
                } 
				else 
				{
                    $strDependency = $strDependency_a . '/' . $strFilename;
                    $strDependency = str_replace('.php', '', $strDependency);

                    if (strlen($strResult) > 0) 
					{
                        $strResult .= ",";
                    }

                    $strResult .= $strDependency;
					if ($blnEcho_a)
					{
						echo ('&nbsp;&nbsp;&nbsp;&nbsp;' . $strDependency . '<br>');
					}
                }
            }
        }
    }

    return $strResult;
}

// dispatch a function
function dispatchFunction($objConn_a, $strSecurityToken_a, $strDataID_a, $arrFunction_a, $arrParameters_a)
{
    $strResult = '';

    if (dependencies($arrFunction_a["dependencies"])) {
        //logDebug('service: ' . $arrFunction_a["interface"], '');
		//logMissing(true, "start dispatch: " . $arrFunction_a["function"]);
        $strResult = call_user_func($arrFunction_a["function"], $objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a);
		//logMissing(true, "end dispatch: " . $arrFunction_a["function"]);
    }
	else
	{
		logMissing(true, "failed to dispatch: " . $arrFunction_a["function"]);
	}

    return $strResult;
}

function validateFunction($arrFunctions_a, $strInterface_a)
{
    $arrResult = null;

    if (array_key_exists($strInterface_a, $arrFunctions_a)) 
	{
        $arrResult = $arrFunctions_a[$strInterface_a];
    }

    return $arrResult;
}
