	<div id="divOptionPanel">
		<table border="0" width="100%">
			<tbody>

<?php

		$strHidden = '';
		$strChecked = 'checked="checked"';
		for ($intI = 0; $intI < count($arrOptionSets); $intI++)
		{
			$blnAddItem = true;
			$strOptionSetCode = $arrOptionSets[$intI]['code'];
			$strOptionSetDescription = $arrOptionSets[$intI]['description'];
			
			if (getFlag($arrOptionSets[$intI], 'installed'))
			{
				$blnAddItem = $blnInstalled;
			}
			
			if (getFlag($arrOptionSets[$intI], 'advanced'))
			{
				if (ENABLE_ADVANCED === false)
				{
					$blnAddItem = false;
				}
			}
			
			if (getFlag($arrOptionSets[$intI], 'developer'))
			{
				if (ENABLE_DEVELOPER === false)
				{
					$blnAddItem = false;
				}
			}
			
			if ($blnAddItem)
			{
				echo('<tr>');
					echo('<td width="40px"><input type="radio" name="fldOptionSet" class="fldOptionSet" value="' . $strOptionSetCode . '" ' . $strChecked . '></td>');
					echo('<td colspan="2">' . $strOptionSetDescription . '</td>');
				echo('</tr>');

				echo('<tr>');
					echo('<td></td>');
					echo('<td colspan="2">');
					echo('<table border="0" class="tabOption tabOption' . $strOptionSetCode . ' ' . $strHidden . '"><tbody>');
					
					$blnOptions = false;
					$arrOptions = $arrOptionSets[$intI]['options'];
					for ($intJ = 0; $intJ < count($arrOptions); $intJ++)
					{
						$blnAddItem = true;
						$strOptionCode = $arrOptions[$intJ]['code'];
						$strOptionDescription = $arrOptions[$intJ]['description'];

						if (getFlag($arrOptions[$intJ], 'installed'))
						{
							$blnAddItem = $blnInstalled;
						}

						if ($blnAddItem)
						{
							echo('<tr>');
								echo('<td width="40px"><input type="radio" name="fldOption" class="fldOption fldOption' . $strOptionSetCode . '" value="' . $strOptionCode . '"></td>');
								echo('<td>' . $strOptionDescription . '</td>');
							echo('</tr>');
						
							$blnOptions = true;
						}
					}
					
					if ($blnOptions)
					{
						echo('<tr><td>&nbsp;</td><td></td></tr>');
					}
					
					echo('</tbody></table>');
					echo('</td>');
				echo('</tr>');			
				
				$strHidden = 'gb-hidden';
				$strChecked = '';
			}
		}		

?>
			</tbody>
		</table>
	</div>
	