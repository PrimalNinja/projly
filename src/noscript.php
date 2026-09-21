<?php

	// bootstrap file for the webapp
	require_once('inc-env.php');
	require_once('inc-constants.php');
	require_once('inc-settings.php');
	require_once('inc-app-' . APP_CODE . '.php');

?>
	
<body bgcolor="white">
<div id="ge-browsers">

	<center>
		<pre>

<b><?php echo(APP_NAME); ?></b><br><br>
<?php echo(APP_VERSION); ?><br>
<?php echo(APP_COPYRIGHT); ?><br><br>

<div id="ge-browser-message"><font color="red"><b>JavaScript is required to continue.</b></font><br></div>


We recommend you download and install the latest version of one of the following browsers.
		</pre>

<?php
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-browserrecommend.php');
?>		

	</center>
</div>

<br><br><br><br><br><br><br><br><br><br>
</body>
