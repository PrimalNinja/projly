<?php

$arrFunctions = array(
	"msg_messagearchivelist" => array("dependencies" => 'msg/actionMessageArchiveList', "function" => 'actionMessageArchiveList'),
	"msg_messagearchiveset" => array("dependencies" => 'msg/actionMessageArchiveSet', "function" => 'actionMessageArchiveSet'),
	"msg_messageenquirysend" => array("dependencies" => 'msg/actionMessageEnquirySend', "function" => 'actionMessageEnquirySend'),
	"msg_messagefetch" => array("dependencies" => 'msg/actionMessageFetch', "function" => 'actionMessageFetch'),
	"msg_messageflagset" => array("dependencies" => 'msg/actionMessageFlagSet', "function" => 'actionMessageFlagSet'),
	"msg_messagefoldersfetch" => array("dependencies" => 'msg/actionMessageFoldersFetch', "function" => 'actionMessageFoldersFetch'),
	"msg_messageinboxlist" => array("dependencies" => 'msg/actionMessageInboxList', "function" => 'actionMessageInboxList'),
	"msg_messagerecipientsfetch" => array("dependencies" => 'msg/actionMessageRecipientsFetch', "function" => 'actionMessageRecipientsFetch'),
	"msg_messagesend" => array("dependencies" => 'msg/actionMessageSend', "function" => 'actionMessageSend'),
	"msg_messagesentlist" => array("dependencies" => 'msg/actionMessageSentList', "function" => 'actionMessageSentList'),
	"msg_messagestatsfetch" => array("dependencies" => 'msg/actionMessageStatsFetch', "function" => 'actionMessageStatsFetch'),
	"msg_messagesystemlist" => array("dependencies" => 'msg/actionMessageSystemList', "function" => 'actionMessageSystemList'),
	"msg_messageupdatestatus" => array("dependencies" => 'msg/actionMessageUpdateStatus', "function" => 'actionMessageUpdateStatus')
);

$arrFunction = validateFunction($arrFunctions, $strFunction);

if ($arrFunction !== null) {
    $strResult = dispatchFunction($this->m_objConn, $strSecurityToken, $strDataID, $arrFunction, $arrParameters);
    $blnDispatched = true;
}