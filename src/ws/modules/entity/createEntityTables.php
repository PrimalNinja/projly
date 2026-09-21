<?php


function createEntityExtensionTable($objConn_a, $strEntityCode_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strEntityCode_a);
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);
	
	// create entity table
	$strEntityFields = "id bigint(20) NOT NULL, ";
	$strEntityFields .= "client_id bigint(20) NOT NULL, ";
	$strEntityFields .= "is_exposed varchar(1), ";
	$strEntityFields .= "PRIMARY KEY (id),";
	$strEntityFields .= "KEY ~FKNAME1~ (id),";
  	//$strEntityFields .= "CONSTRAINT ~FKNAME1~ FOREIGN KEY (id) REFERENCES ~TABLENAMEENTITY~ (id),";	// NOTE this does not work on MariaDB which is a derivative of MySQL
	$strEntityFields .= "KEY ~FKNAME2~ (client_id),";
  	$strEntityFields .= "CONSTRAINT ~FKNAME2~ FOREIGN KEY (client_id) REFERENCES ~TABLENAMECLIENT~ (id)";

	$strFKName1 = createFKName();
	$strFKName2 = createFKName();

	$strSQL = "create table ~TABLENAMEENTITYEXTENSION~ (~FIELDS~) engine=InnoDB auto_increment=1 default charset=utf8";
	$strSQL = str_replace("~FIELDS~", $strEntityFields, $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ffel($strTableNameClient), $strSQL);
	$strSQL = str_replace("~TABLENAMEENTITY~", ffel($strTableNameEntity), $strSQL);
	$strSQL = str_replace("~TABLENAMEENTITYEXTENSION~", ffel($strTableNameEntityExtension), $strSQL);
	$strSQL = str_replace("~FKNAME1~", ff($strFKName1), $strSQL);
	$strSQL = str_replace("~FKNAME2~", ff($strFKName2), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

// create entity tables
function createEntityTables($objConn_a, $strEntityCode_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameActualEntity = getTableNameEntity("entity", false);
	$strTableNameEntity = getTableNameEntity($strEntityCode_a, false);
	$strTableNameEntityHistory = getTableNameEntity($strEntityCode_a, true);
	
	// create entity table
	$strEntityFields = "id bigint(20) NOT NULL AUTO_INCREMENT, ";
	$strEntityFields .= "client_id bigint(20) NOT NULL, ";
	$strEntityFields .= "entity_id bigint(20) NOT NULL, ";		// this is the type of form, eg: systemform or dataform
	$strEntityFields .= "dataentity_id bigint(20) NOT NULL, ";	// this is the entity of the type of data a form creates, eg: koalaform NOTE: for entity tables, the dataentity_id can refer to another entity or a dataformentity
	$strEntityFields .= "code varchar(255), ";
	$strEntityFields .= "description varchar(255), ";
	$strEntityFields .= "is_enabled varchar(1), ";
	$strEntityFields .= "data_client_id bigint(20) NOT NULL, ";
	$strEntityFields .= "jsondata mediumblob, ";
	$strEntityFields .= "modifyuser varchar(255) NOT NULL, ";
	$strEntityFields .= "modifydatetime varchar(19) NOT NULL, ";
	$strEntityFields .= "PRIMARY KEY (id),";
	$strEntityFields .= "KEY ~FKNAME1~ (client_id),";
  	$strEntityFields .= "CONSTRAINT ~FKNAME1~ FOREIGN KEY (client_id) REFERENCES ~TABLENAMECLIENT~ (id),";
	$strEntityFields .= "KEY ~FKNAME2~ (data_client_id),";
  	$strEntityFields .= "CONSTRAINT ~FKNAME2~ FOREIGN KEY (data_client_id) REFERENCES ~TABLENAMECLIENT~ (id),";
	$strEntityFields .= "KEY ~FKNAME3~ (entity_id),";
  	$strEntityFields .= "CONSTRAINT ~FKNAME3~ FOREIGN KEY (entity_id) REFERENCES ~TABLENAMEACTUALENTITY~ (id),";
	$strEntityFields .= "KEY ~FKNAME4~ (dataentity_id),";
  	$strEntityFields .= "CONSTRAINT ~FKNAME4~ FOREIGN KEY (dataentity_id) REFERENCES ~TABLENAMEACTUALENTITY~ (id)";

	$strFKName1 = createFKName();
	$strFKName2 = createFKName();
	$strFKName3 = createFKName();
	$strFKName4 = createFKName();

	$strSQL = "create table ~TABLENAMEENTITY~ (~FIELDS~) engine=InnoDB auto_increment=1 default charset=utf8";
	$strSQL = str_replace("~FIELDS~", $strEntityFields, $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ffel($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEACTUALENTITY~', ffel($strTableNameActualEntity), $strSQL);
	$strSQL = str_replace("~TABLENAMEENTITY~", ffel($strTableNameEntity), $strSQL);
	$strSQL = str_replace("~FKNAME1~", ff($strFKName1), $strSQL);
	$strSQL = str_replace("~FKNAME2~", ff($strFKName2), $strSQL);
	$strSQL = str_replace("~FKNAME3~", ff($strFKName3), $strSQL);
	$strSQL = str_replace("~FKNAME4~", ff($strFKName4), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// create indexes
	$strIDXName1 = createIDXName();
	$strIDXName2 = createIDXName();

	$strSQL = "
alter table ~TABLENAMEENTITY~ 
add index ~IDXNAME1~ (code) using btree,
add index ~IDXNAME2~ (description) using btree
";
	$strSQL = str_replace("~TABLENAMEENTITY~", ffel($strTableNameEntity), $strSQL);
	$strSQL = str_replace("~IDXNAME1~", ff($strIDXName1), $strSQL);
	$strSQL = str_replace("~IDXNAME2~", ff($strIDXName2), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// create entity history table
	$strHistoryFields = "id bigint(20) NOT NULL AUTO_INCREMENT, ";
	$strHistoryFields .= "entitydata_id bigint(20) NOT NULL, ";	// this is the id from the non-history table
	$strHistoryFields .= "client_id bigint(20) NOT NULL, ";
	$strHistoryFields .= "entity_id bigint(20) NOT NULL, ";			// this is the type of form, eg: systemform or dataform
	$strHistoryFields .= "dataentity_id bigint(20) NOT NULL, ";		// this is the entity of the type of data a form creates, eg: koalaform NOTE: for entity tables, the dataentity_id can refer to another entity or a dataformentity
	$strHistoryFields .= "code varchar(255), ";
	$strHistoryFields .= "description varchar(255), ";
	$strHistoryFields .= "is_enabled varchar(1), ";
	$strHistoryFields .= "data_client_id bigint(20) NOT NULL, ";
	$strHistoryFields .= "jsondata mediumblob, ";
	$strHistoryFields .= "modifyuser varchar(255) NOT NULL, ";
	$strHistoryFields .= "modifydatetime varchar(19) NOT NULL, ";
    $strHistoryFields .= "PRIMARY KEY (id)";
    /*
	$strHistoryFields .= "KEY ~FKNAME1~ (client_id),";
  	$strHistoryFields .= "CONSTRAINT ~FKNAME1~ FOREIGN KEY (client_id) REFERENCES ~TABLENAMECLIENT~ (id),";
	$strHistoryFields .= "KEY ~FKNAME2~ (data_client_id),";
  	$strHistoryFields .= "CONSTRAINT ~FKNAME2~ FOREIGN KEY (data_client_id) REFERENCES ~TABLENAMECLIENT~ (id),";
	$strHistoryFields .= "KEY ~FKNAME3~ (entity_id),";
  	$strHistoryFields .= "CONSTRAINT ~FKNAME3~ FOREIGN KEY (entity_id) REFERENCES ~TABLENAMEACTUALENTITY~ (id),";
	$strHistoryFields .= "KEY ~FKNAME4~ (dataentity_id),";
  	$strHistoryFields .= "CONSTRAINT ~FKNAME4~ FOREIGN KEY (dataentity_id) REFERENCES ~TABLENAMEACTUALENTITY~ (id)";

	$strFKName1 = createFKName();
	$strFKName2 = createFKName();
	$strFKName3 = createFKName();
	$strFKName4 = createFKName();
    */

	$strSQL = "create table ~TABLENAMEENTITYHISTORY~ (~FIELDS~) engine=InnoDB auto_increment=1 default charset=utf8";
	$strSQL = str_replace("~FIELDS~", $strHistoryFields, $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ffel($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEACTUALENTITY~', ffel($strTableNameActualEntity), $strSQL);
	$strSQL = str_replace("~TABLENAMEENTITYHISTORY~", ffel($strTableNameEntityHistory), $strSQL);
    
    /*
    $strSQL = str_replace("~FKNAME1~", ff($strFKName1), $strSQL);
	$strSQL = str_replace("~FKNAME2~", ff($strFKName2), $strSQL);
	$strSQL = str_replace("~FKNAME3~", ff($strFKName3), $strSQL);
    $strSQL = str_replace("~FKNAME4~", ff($strFKName4), $strSQL);
    */

	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    
	// create indexes
	$strIDXName1 = createIDXName();
	$strIDXName2 = createIDXName();

	$strSQL = "
alter table ~TABLENAMEENTITYHISTORY~ 
add index ~IDXNAME1~ (code) using btree,
add index ~IDXNAME2~ (description) using btree
";
	$strSQL = str_replace("~TABLENAMEENTITYHISTORY~", ffel($strTableNameEntityHistory), $strSQL);
	$strSQL = str_replace("~IDXNAME1~", ff($strIDXName1), $strSQL);
	$strSQL = str_replace("~IDXNAME2~", ff($strIDXName2), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}
