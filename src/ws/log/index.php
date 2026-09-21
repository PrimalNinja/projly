<html>
	<head>
		<title>AWAF Debug Logs</title>
		
		<style>
body, html {
  width: 100%;
  height: 100%;
  margin: 0;
}

.container {
  width: 100%;
  height: 100%;
  overflow: none;
}

.leftpane {
    width: 33%;
    height: 85%;
    float: left;
    background-color: cyan;
    border-collapse: collapse;
    overflow: auto;
}

.middlepane {
    width: 34%;
    height: 85%;
    float: left;
    background-color: red;
    border-collapse: collapse;
    overflow: auto;
}

.rightpane {
  width: 33%;
  height: 85%;
  position: relative;
  float: right;
  background-color: yellow;
  border-collapse: collapse;
    overflow: auto;
}

.toppane {
  text-align: center;
  width: 100%;
  height: 15%;
  border-collapse: collapse;
  background-color: #4da6ff;
}
		</style>
	</head>
	<body>
<?php

	define('MAXROWS', 500);

	$strFile1 = @file_get_contents("phpconsole.log");
	$strFile2 = @file_get_contents("phperrors.log");
	$strFile3 = @file_get_contents("security.log");
	
	if ($strFile1 && strlen($strFile1) > 0)
	{
		$strFile1 = str_replace("\r\n", "<br>", $strFile1);
		$arrFile1 = explode("<br>", $strFile1);
		
		if (count($arrFile1) > MAXROWS)
		{
			$arrFile1 = array_slice($arrFile1, 0, MAXROWS, true);
		}
		
		$strFile1 = implode("<hr>", array_reverse($arrFile1));
	}
	
	if ($strFile2 && strlen($strFile2) > 0)
	{
		$strFile2 = str_replace("\r\n", "<br>", $strFile2);
		$arrFile2 = explode("<br>", $strFile2);
		
		if (count($arrFile2) > MAXROWS)
		{
			$arrFile2 = array_slice($arrFile2, 0, MAXROWS, true);
		}
		
		$strFile2 = implode("<hr>", array_reverse($arrFile2));
	}
	
	if ($strFile3 && strlen($strFile3) > 0)
	{
		$strFile3 = str_replace("\r\n", "<br>", $strFile3);
		$arrFile3 = explode("<br>", $strFile3);
		
		if (count($arrFile3) > MAXROWS)
		{
			$arrFile3 = array_slice($arrFile3, 0, MAXROWS, true);
		}
		
		$strFile3 = implode("<hr>", array_reverse($arrFile3));
	}
	
?>
		<div class="container">
			<div class="toppane">
				<h1>AWAF Debug Logs</h1>
				<input id="btnRefresh" type="checkbox" checked="checked">Auto Refresh</>
			</div>
		
			<div id="leftpane" class="leftpane">
				<h2>PHP Console Log</h2><br><br>
				<?php echo($strFile1); ?>
			</div>
			
			<div id="middlepane" class="middlepane">
				<h2>Error Log</h2><br><br>
				<?php echo($strFile2); ?>
			</div>
			
			<div id="rightpane" class="rightpane">
				<h2>Security Log</h2><br><br>
				<?php echo($strFile3); ?>
			</div>
		</div>
	</body>

	<script>
	
		var blnInRefresh = false;
		var objRefreshButton = document.getElementById('btnRefresh');
		var objLeftPane = document.getElementById('leftpane');
		var objMiddlePane = document.getElementById('middlepane');
		var objRightPane = document.getElementById('rightpane');

		btnRefresh.onclick = function()
		{
			if (objRefreshButton.checked)
			{
				objLeftPane.scrollTop = 0;
				objMiddlePane.scrollTop = 0;
				objRightPane.scrollTop = 0;
			}
		};
	
		function refreshPage()
		{
			if (!blnInRefresh)
			{
				blnInRefresh = true;
				
				if ((objLeftPane.scrollTop > 0) || (objMiddlePane.scrollTop > 0) || (objRightPane.scrollTop > 0))
				{
					objRefreshButton.checked = false;
				}
				
				if (objRefreshButton.checked)
				{
					window.location.reload();
				}
				else
				{
					setTimeout(function() 
					{
						refreshPage();
					}, 1000);
				}
				
				blnInRefresh = false;
			}
		}
		
		setTimeout(function() 
		{
			refreshPage();
		}, 1000);
		
	</script>
</html>
