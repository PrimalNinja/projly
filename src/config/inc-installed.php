	<div id="divInstalledPanel">
		<h3>Currently installed version:</h3>

<?php

		if (strlen($strInstalledVersion) > 0)
		{
			if (strlen($strInstalledURL) > 0)
			{
				$strInstalledMoreInfo = '<a href="' . $strInstalledURL . '" target="_blank">more info...</a>';
			}

?>

		<table>
			<tbody>
				<tr><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td></tr>

				<tr>
					<td><b>Date:</b></td>
					<td></td>
					<td colspan="9"><?php echo($strInstalledDate); ?></td>
				</tr>

				<tr>
					<td><b>App Version:</b></td>
					<td></td>
					<td colspan="9"><?php echo($strInstalledVersion); ?></td>
				</tr>

				<tr>
					<td><b>Build number:</b></td>
					<td></td>
					<td colspan="9"><?php echo($strInstalledBuild); ?></td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td colspan="9"><?php echo($strInstalledMoreInfo); ?></td>
				</tr>

				<tr><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td><td width="20px">&nbsp;</td><td></td></tr>

				<tr>
					<td><b>Database Date:</b></td>
					<td></td>
					<td><?php echo($strDBInstalledDate); ?></td>
					<td></td>
					<td><b>Version:</b></td>
					<td></td>
					<td><?php echo($strDBInstalledVersion); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr>
					<td colspan="11"><hr></td>
				</tr>

				<tr>
					<td colspan="11"><b>System DB:</b></td>
				</tr>

				<tr>
					<td><b>Main Hostname:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMMAIN_HOSTNAME); ?></td>
					<td></td>
					<td><b>User:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMMAIN_LOGIN); ?></td>
					<td></td>
					<td><b>Name:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMMAIN_DATABASENAME); ?></td>
				</tr>

                <tr>
					<td><b>History Hostname:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMHISTORY_HOSTNAME); ?></td>
					<td></td>
					<td><b>User:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMHISTORY_LOGIN); ?></td>
					<td></td>
					<td><b>Name:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMHISTORY_DATABASENAME); ?></td>
				</tr>

				<tr>
					<td><b>Temporary Hostname:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMTEMP_HOSTNAME); ?></td>
					<td></td>
					<td><b>User:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMTEMP_LOGIN); ?></td>
					<td></td>
					<td><b>Name:</b></td>
					<td></td>
					<td><?php echo(DBSYSTEMTEMP_DATABASENAME); ?></td>
				</tr>

				<tr>
					<td colspan="11"><hr></td>
				</tr>

				<tr>
					<td><b>Current Working Directory:</b></td>
					<td></td>
					<td colspan="8"><?php echo(getCurrentWD()); ?></td>
				</tr>
			</tbody>
		</table>
	
<?php		
		}
		else
		{
			echo('<div class="gs-alert">No current installation found.</div>');
?>
		<table>
			<tbody>
				<tr>
					<td><b>Current Working Directory:</b></td>
					<td></td>
					<td colspan="9"><?php echo(getCurrentWD()); ?></td>
				</tr>
			</tbody>
		</table>

<?php
		}
		
?>
	</div>
