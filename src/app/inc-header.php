
<?php
	if (APP_IFRAMABLE == 'TRUE')
	{
		// do nothing
	}
	else
	{
?>

<script type="text/javascript">
	if (window.top !== window.self)
	{
	   location = '<?php echo(APP_SUPPORT); ?>';
	} 
</script>

<?php

	}

	// app constants
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-constants-app.php');

	// debug info
	if (DEBUG_CREDENTIALS == 'TRUE')
	{
		echo('security token: ' . $_SESSION['client_loggedin_token'] . '<br>');
		echo('<br><br>');
	}
	
?>