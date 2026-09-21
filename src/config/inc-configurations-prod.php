	<div id="divConfigurationPanelProd" class="gb-hidden">
		<h3>PRODUCTION configurations:</h3>

		<table width="100%">
			<thead>
				<tr>
					<td width="40px"></td>
					<td><b>Description</b></td>
				</tr>
			</thead>
			<tbody>
<?php

	for ($intI = count($arrConfigurationsProd) - 1; $intI >= 0; $intI--)
	{
		$strFilename = $arrConfigurationsProd[$intI];
		if (substr($strFilename, -5) === '.json')
		{
			$strMetaData = loadFile('configurations-prod/' . $strFilename);
			$arrJSON = json_decode($strMetaData, true);

			//$arrFiles = elementArray($arrJSON, 'files', []);	// just for debugging, ok here
			$strCode = elementString($arrJSON, 'code', '');
			$strDescription = elementString($arrJSON, 'description', '');

?>
				<tr>
					<td width="40px"></td>
					<td colspan="7"><hr></td>
				</tr>
				<tr>
					<td><input type="radio" name="fldUpdateNumber" class="fldUpdateNumber" value="<?php echo($strCode); ?>"></td>
					<td colspan="7"><?php echo($strDescription); ?></td>
				</tr>
<?php			

		}
	}

?>
			</tbody>
		</table>
	</div>
