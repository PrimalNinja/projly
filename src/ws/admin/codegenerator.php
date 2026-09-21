<?php

require_once('../inc-env.php');
require_once('../inc-constants.php');
require_once('../inc-settings.php');
require_once('../inc-temporaryvalues.php');
require_once('../inc-app-' . APP_CODE . '.php');
require_once('../inc-dbsettings-client.php');
require_once('../inc-dbsettings-system.php');
require_once('../modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

define('ENCRYPTION_TYPE_LICENCE2WAY', 'pluginEncrypt_OpenSSLRandomIV');			// the currently configured 2-way licence encryption type or '' for none

// no encryption use the passthrough encrypter
function encryptLicenceKey($strProductID_a, $strClient_a, $str_a)
{
	$strResult = '';
	
	if (strlen(ENCRYPTION_TYPE_LICENCE2WAY) == 0)
	{
		$strResult = $str_a;
	}
	else
	{
		if (dependencies('plugins/cryptplugins/' . ENCRYPTION_TYPE_LICENCE2WAY))
		{
			$strSalt = strtolower($strProductID_a . '-' . $strClient_a);
			
			$strResult = $str_a;
			$strResult = call_user_func(ENCRYPTION_TYPE_LICENCE2WAY, $strSalt, $strResult);
		}
	}

	return $strResult;
}

$strLicenceCode = "";
$strDecrypted = "";

$strProductID = "";
if (isset($_GET["fldProductID"]))
{
	$strProductID = $_GET["fldProductID"];
}

$strInstallationID = "";
if (isset($_GET["fldInstallationID"]))
{
	$strInstallationID = $_GET["fldInstallationID"];
}

$strExpiryDate = "";
if (isset($_GET["fldExpiryDate"]))
{
	$strExpiryDate = $_GET["fldExpiryDate"];
}

if ((strlen($strInstallationID) > 0) && (strlen($strExpiryDate) > 0))
{
	$strLicenceCode = encryptLicenceKey($strProductID, $strInstallationID, $strExpiryDate);

	if (dependencies('utils/security'))
	{
		$strDecrypted = decryptLicenceKey($strProductID, $strInstallationID, $strLicenceCode);
	}
}

?>
<html>
	<head>
		<title>Licence Code Generator</title>
	</head>
	<body>

		<?php if (strlen($strLicenceCode) == 0) { ?>
		
		<form>
			Enter Product ID: <input type="text" name="fldProductID" value="<?php echo($strProductID); ?>">
			Enter Installation ID: <input type="text" name="fldInstallationID" value="<?php echo($strInstallationID); ?>">
			Enter Expiry Date (YYYY-MM-DD): <input type="text" name="fldExpiryDate" value="<?php echo($strExpiryDate); ?>"><br>
			<input type="submit">
		</form>
		
		<?php } else { ?>

		Product ID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo($strProductID); ?><br>
		Installation ID: <?php echo($strInstallationID); ?><br>
		Expiry Date:&nbsp;&nbsp;&nbsp;&nbsp; <?php echo($strExpiryDate); ?><br><br>
		Licnese Code:&nbsp; <?php echo($strLicenceCode); ?><br><br>
		Decrypted:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo($strDecrypted); ?> <b>(should be the as per the Expiry Date)</b><br>
		
		<?php } ?>
	</body>
</html>

