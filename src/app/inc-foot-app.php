<?php

	// include modules
	$arrModules = explode(',', CLIENTMODULES);
	foreach($arrModules as $strModule)
	{
		try
		{
			if (file_exists(DYNAMIC_APP_DIR_URL . 'modules/' . $strModule . '/inc-include-foot.php'))
			{
				require_once(DYNAMIC_APP_DIR_URL . 'modules/' . $strModule . '/inc-include-foot.php');
			}
		}
		catch (Exception $e)
		{
			// do nothing
		}
	}

?>
