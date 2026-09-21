<?php

// drop entity tables
function dropEntityTables($objConn_a, $strEntityCode_a)
{
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strEntityCode_a);
	$strTableNameEntityHistory = getTableNameEntity($strEntityCode_a, true);

	dbDropTable($objConn_a, $strTableNameEntityHistory, true);
	dbDropTable($objConn_a, $strTableNameEntityExtension, true);
	dbDropTable($objConn_a, $strTableNameEntity, true);
}
