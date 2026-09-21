<?php

	require('inc-constants.php');
	require('app/ws/inc-dbsettings-client.php');
	require('app/ws/inc-dbsettings-system.php');
	require('utils/general.php');
	require('utils/dates.php');
	require('utils/logging.php');
	require('utils/file.php');
	require('utils/dbConnection.php');
	require('utils/database.php');
	require('utils/tasks.php');
 
	$blnInstalled = false;

	// script checking
	$strConfig = loadFile('config.json');
	$arrConfig = json_decode($strConfig, true);
	
	$strInstallScript = $arrConfig['awafinstallscript'];
	$strScriptVersion = $arrConfig['scriptversion'];

	if (intval($strScriptVersion) != intval(REQUIRED_SCRIPT_VERSION))
	{
		die("Required scriptversion '" . REQUIRED_SCRIPT_VERSION . "' but found scriptversion '" . $strScriptVersion . "'.");
	}
	
	$arrConfigurationsProd = getFiles('configurations-prod/', false);
	$arrConfigurationsUAT = getFiles('configurations-uat/', false);
	$arrConfigurationsDev = getFiles('configurations-dev/', false);
	
	$arrOptionSets = $arrConfig['optionSets'];
	$arrBackups = getFiles('backups/', false);
	$arrUpdates = getFiles('updates/', false);
	
	$blnIsLocalhost = false;
	
	$strURL =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$strEscapedURL = htmlspecialchars( $strURL, ENT_QUOTES, 'UTF-8' );
	
	if (strpos($strURL, 'localhost') !== false) {
		$blnIsLocalhost = true;
	}

?>
<html>
	<head>
		<title><?php echo(APP_NAME); ?> Installer & Updater</title>
		
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery.ui.accordion.min.css">
	</head>
	<body>
		<table width="100%">
			<tbody>
				<tr>
					<td></td>
					<td width="800px">
					
<?php

if (!$blnIsLocalhost)
{
	echo("<table width='100%' border='3px' bordercolor='red'><tr><td style='background-color:pink'><b><font style='font-size:30pt; color:red'>BE CAREFUL, THIS IS <u>NOT</u> YOUR LOCALHOST DEV ENVIRONMENT!<br/>Your current URL: " . $strEscapedURL . " </font></b></td></tr></table>");
}
else
{
	echo("<table width='100%' border='3px' bordercolor='#006400'><tr><td style='background-color:#cbffcb'><b><font style='font-size:16pt; color:#006400;'>HOORAY, THIS <u>IS</u> YOUR LOCALHOST DEV ENVIRONMENT!<br/>Your current URL: " . $strEscapedURL . " </font></b></td></tr></table>");
}
	
?>
						<?php require('inc-header.php'); ?>
						<div id="accordion">
							<h3>Information:</h3>
							<div>
								<?php require('inc-installed-check.php'); ?>
								<?php require('inc-installed.php'); ?>
							</div>
							<h3>Options:</h3>
							<div>
								<?php require('inc-options.php'); ?>
							</div>
							<h3>Parameters:</h3>
							<div>
								<?php require('inc-backups.php'); ?>
								<?php require('inc-configurations-prod.php'); ?>
								<?php require('inc-configurations-uat.php'); ?>
								<?php require('inc-configurations-dev.php'); ?>
								<?php require('inc-updates.php'); ?>
							</div>
							<h3>Output:</h3>
							<div>
								<?php require('inc-output.php'); ?>
							</div>
						</div>

						<br>
						<div id="cmdSubmit" class="gb-hidden">
							<div class="cmdSubmit gb-button gs-cell-content-xxx gs-green-background-colour gs-cellcontent-125x62-xxx gs-glow-focusborder">
								<div class="gb-button-text">Submit</div>
							</div>
							<div class="cmdProcessing gb-button gs-cell-content-xxx gs-blue-background-colour gs-cellcontent-125x62-xxx gs-glow-focusborder gb-hidden">
								<div class="gb-button-text">Processing...</div>
							</div>
							<div class="cmdDone gb-button gs-green-background-colour gs-cell-content-xxx gs-cellcontent-125x62-xxx gs-glow-focusborder gb-hidden">
								<div class="gb-button-text">Application</div>
							</div>
							<div class="cmdError gb-button gs-cell-content-xxx gs-red-background-colour gs-cellcontent-125x62-xxx gs-glow-focusborder gb-hidden">
								<div class="gb-button-text">Error</div>
							</div>
							<div class="cmdNext gb-button gs-green-background-colour gs-cell-content-xxx gs-cellcontent-125x62-xxx gs-glow-focusborder gb-hidden">
								<div class="gb-button-text">Config</div>
							</div>
						</div>

					</td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.accordion.min.js"></script>
		<script type="text/javascript" src="js/jquery.timer.js"></script>
		<script type="text/javascript" src="js/inc-config.js"></script>
		<script type="text/javascript">

		var g_objConfig = <?php echo($strConfig); ?>;
		var g_strCWD = '<?php echo(str_replace('\\', '/', getcwd())); ?>';
		
		$(document).ready(function()
		{
			$('.fldOptionSet', '#divOptionPanel').bind('change', fldOptionSet_onClick);
			$('.fldOption', '#divOptionPanel').bind('change', fldOption_onClick);
			$('.fldUpdateNumber', '#divConfigurationPanelProd').bind('change', fldUpdateNumber_onClick);
			$('.fldUpdateNumber', '#divConfigurationPanelUAT').bind('change', fldUpdateNumber_onClick);
			$('.fldUpdateNumber', '#divConfigurationPanelDev').bind('change', fldUpdateNumber_onClick);
			$('.fldUpdateNumber', '#divBackupPanel').bind('change', fldUpdateNumber_onClick);
			$('.fldUpdateNumber', '#divUpdatePanel').bind('change', fldUpdateNumber_onClick);
			$('.cmdSubmit', '#cmdSubmit').bind('click', cmdSubmit_onClick);
			$('.cmdNext', '#cmdSubmit').bind('click', cmdNext_onClick);
			$('.cmdDone', '#cmdSubmit').bind('click', cmdDone_onClick);
			
			$( "#accordion" ).accordion(
			{
					animate:false,
					heightStyle:"content"
			});
		});
		
		</script>
	</body>
</html>
