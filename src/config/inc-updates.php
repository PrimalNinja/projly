	<div id="divUpdatePanel" class="gb-hidden">
		<h3>Available updates:</h3>

		<table width="100%">
			<thead>
				<tr>
					<td width="40px"></td>
					<td width="80px"><b>Build number</b></td>
					<td width="80px"><b>Date</b></td>
					<td width="60px"><b>App Version</b></td>
					<td width="60px"><b>DB Version</b></td>
					<td><b>Description</b></td>
					<td width="230px"><b>Filename</b></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
<?php

	$strLastUpdateNumber = '';
	for ($intI = count($arrUpdates) - 1; $intI >= 0; $intI--)
	{
		$strFilename = $arrUpdates[$intI];
		if (substr($strFilename, -5) === '.json')
		{
			$strMetaData = loadFile('updates/' . $strFilename);
			$arrJSON = json_decode($strMetaData, true);

			$strUpdateNumber = elementString($arrJSON, 'update', '');
			$strBuild = elementString($arrJSON, 'build', '');
			$strDate = elementString($arrJSON, 'date', '');
			$strVersion = elementString($arrJSON, 'version', '');
			$strDBVersion = elementString($arrJSON, 'dbversion', '');
			$strDescription = elementString($arrJSON, 'description', '');
			$strFilename = elementString($arrJSON, 'filename', '');
			$strURL = elementString($arrJSON, 'url', '');
			$strMoreInfo = '';
			
			if (strlen($strURL) > 0)
			{
				$strMoreInfo = '<a href="' . $strURL . '" target="_blank">more info...</a>';
			}
			
?>
				<tr>
					<td width="40px"></td>
					<td colspan="7"><hr></td>
				</tr>
				<tr>
					<?php
						if ($strLastUpdateNumber !== $strUpdateNumber)
						{
							echo('<td><input type="radio" name="fldUpdateNumber" class="fldUpdateNumber" value="' . $strUpdateNumber . '"></td>');
						}
						else
						{
							echo('<td></td>');
						}
						$strLastUpdateNumber = $strUpdateNumber;
					?>
					<td><?php echo($strBuild); ?></td>
					<td><?php echo($strDate); ?></td>
					<td><?php echo($strVersion); ?></td>
					<td><?php echo($strDBVersion); ?></td>
					<td colspan="3"><?php echo($strDescription); ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo($strFilename); ?></td>
					<td><?php echo($strMoreInfo); ?></td>
				</tr>
<?php			

		}
	}

?>
			</tbody>
		</table>
	</div>
