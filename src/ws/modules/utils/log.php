<?php

// function summary:

// createAuditDataLog($objConn_a, $strCategory_a, $strDescription_a, $strEntityID_a, $strEntityCode_a, $strEntity2ID_a, $strEntity2Code_a)
// createAuditEntityAddDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
// createAuditEntityDeleteDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
// createAuditEntityEditDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
// createAuditEntityListDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
// createAuditEntityViewDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)

function createAuditDataLog($objConn_a, $strCategory_a, $strDescription_a, $strEntityID_a, $strEntityCode_a, $strEntity2ID_a, $strEntity2Code_a)
{
    $strResult = '';

    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strLogin = $_SESSION['server_loggedin_user'];
	$strUserID = $_SESSION['server_loggedin_userid'];

    $strSQL =
        "
    insert into ~TABLENAMEDATACHANGE~ (client_id, user_id, category, description, entity_id, entity_code, entity2_id, entity2_code, modifyuser, modifydatetime)
    values (~CLIENTID~, ~USERID~, '~CATEGORY~', '~DESCRIPTION~', ~ENTITYID~, '~ENTITYCODE~', ~ENTITY2ID~, '~ENTITY2CODE~', '~MODIFYUSER~', '~MODIFYDATETIME~')
    ";

	$strSQL = str_replace('~TABLENAMEDATACHANGE~', CORE_DATACHANGE, $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    $strSQL = str_replace('~CATEGORY~', ff($strCategory_a), $strSQL);
    $strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ffn($strEntityID_a), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffn($strEntityCode_a), $strSQL);
	$strSQL = str_replace('~ENTITY2ID~', ffn($strEntity2ID_a), $strSQL);
	$strSQL = str_replace('~ENTITY2CODE~', ffn($strEntity2Code_a), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    $strResult = dbLastInsertID($objConn_a);

    return $strResult;
}

function createAuditEntityAddDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
{
    $strClient = $_SESSION['server_loggedin_client'];
    $strLogin = $_SESSION['server_loggedin_user'];

    $strCategory = 'Data Add';
    $strDescription = "Add '" . ffel($strFormEntityCode_a) . "' record.";
    return createAuditDataLog($objConn_a, $strCategory, $strDescription, "", ffeu($strEntityCode_a), $strFormEntityID_a, ffeu($strFormEntityCode_a));
}

function createAuditEntityDeleteDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
{
    $strClient = $_SESSION['server_loggedin_client'];
    $strLogin = $_SESSION['server_loggedin_user'];

    $strCategory = 'Data Delete';
    $strDescription = "Delete '" . ffel($strFormEntityCode_a) . "' record.";
    return createAuditDataLog($objConn_a, $strCategory, $strDescription, "", ffeu($strEntityCode_a), $strFormEntityID_a, ffeu($strFormEntityCode_a));
}

function createAuditEntityEditDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
{
    $strClient = $_SESSION['server_loggedin_client'];
    $strLogin = $_SESSION['server_loggedin_user'];

    $strCategory = 'Data Edit';
    $strDescription = "Edit '" . ffel($strFormEntityCode_a) . "' record.";
    return createAuditDataLog($objConn_a, $strCategory, $strDescription, "", ffeu($strEntityCode_a), $strFormEntityID_a, ffeu($strFormEntityCode_a));
}

function createAuditEntityListDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
{
    $strClient = $_SESSION['server_loggedin_client'];
    $strLogin = $_SESSION['server_loggedin_user'];

    $strCategory = 'Data Access';
    $strDescription = "List '" . ffel($strFormEntityCode_a) . "' records.";
    return createAuditDataLog($objConn_a, $strCategory, $strDescription, "", ffeu($strEntityCode_a), $strFormEntityID_a, ffeu($strFormEntityCode_a));
}

function createAuditEntityViewDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityID_a)
{
    $strClient = $_SESSION['server_loggedin_client'];
    $strLogin = $_SESSION['server_loggedin_user'];

    $strCategory = 'Data Access';
    $strDescription = "View '" . ffel($strFormEntityCode_a) . "' record.";
    return createAuditDataLog($objConn_a, $strCategory, $strDescription, "", ffeu($strEntityCode_a), $strFormEntityID_a, ffeu($strFormEntityCode_a));
}
