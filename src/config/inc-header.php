	<div id="divHeaderPanel">
<?php
	if (SHOWLOGO)
	{
?>
		<div class="gs-header">
			<img src="images/logo.png" border="0" alt="" width="320px" height="77px"/><br>
		</div>
<?php
	}
?>
		<h2><?php echo(APP_NAME); ?></h2>

<?php 

		echo(APP_COPYRIGHT); 
	
?>

		<br><br>

		<table cols=3><tr><td>Processing: </td><td><span class="ge-task" style="background-color:red">&nbsp;&nbsp;&nbsp;</span></td><td><span class="ge-subtask" style="background-color:red">&nbsp;&nbsp;&nbsp;</span></td></tr></table>
	</div>