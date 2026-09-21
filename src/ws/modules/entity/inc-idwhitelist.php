<?php

function getIDWhiteList($objConn_a, $strFormEntityCodePlugin_a)
{
    // for mapping and security purposes (used for sorting and searching)
	// syntax: fieldname[/hint], hint can be: boolean, integer
    $arrFields = array();
	$arrFields['clientcode'] = 'clientcode';
	$arrFields['code'] = 'code';
	$arrFields['country'] = 'country';
	$arrFields['created'] = 'createdatetime';
	$arrFields['description'] = 'description';
	$arrFields['displayorder'] = 'displayorder';
	$arrFields['documentdate'] = 'documentdate';
	$arrFields['email_address'] = 'email_address';
	$arrFields['errorcount'] = 'errorcount';
	$arrFields['hasnotes'] = 'hasnotes/boolean';
	$arrFields['has_warning'] = 'has_warning/boolean';
	$arrFields['ipaddress'] = 'ipaddress';
	$arrFields['is_archived'] = 'is_archived/boolean';
	$arrFields['is_authenticated'] = 'is_authenticated/boolean';
	$arrFields['is_cancelled'] = 'is_cancelled/boolean';
	$arrFields['is_completed'] = 'is_completed/boolean';
	$arrFields['is_confirmed'] = 'is_confirmed/boolean';
	$arrFields['is_current'] = 'is_current/boolean';
	$arrFields['is_individual'] = 'is_individual/boolean';
	$arrFields['is_tagless'] = 'is_tagless/boolean';
	$arrFields['is_verified'] = 'is_verified/boolean';
	$arrFields['is_inbound'] = 'is_inbound/boolean';   
	$arrFields['is_public'] = 'is_public/boolean';   
	$arrFields['isenabled'] = 'is_enabled/boolean';	// historical, will take some time to deprecate
	$arrFields['is_enabled'] = 'is_enabled/boolean';
	$arrFields['login'] = 'login';
	$arrFields['modified'] = 'modifydatetime';
	$arrFields['notes'] = 'notes';
	$arrFields['postcode'] = 'postcode';
	$arrFields['progress'] = 'progress';
	$arrFields['sent'] = 'sent';
	$arrFields['state'] = 'state';
	$arrFields['status'] = 'status';
	$arrFields['suburb'] = 'suburb';

	// IDs used for filtering related links
	$arrFields['application_id'] = 'application_id/numeric';
	$arrFields['applicationmodule_id'] = 'applicationmodule_id/numeric';
	$arrFields['branch_id'] = 'branch_id/numeric';
	$arrFields['client_id'] = 'client_id/numeric';
	$arrFields['config_id'] = 'config_id/numeric';
	$arrFields['device_id'] = 'device_id/numeric';
	$arrFields['documentrepository_id'] = 'documentrepository_id/numeric';
	$arrFields['fileformat_field_id'] = 'fileformat_field_id/numeric';
	$arrFields['fileformat_id'] = 'fileformat_id/numeric';
	$arrFields['fileformattemplate_id'] = 'fileformattemplate_id/numeric';
	$arrFields['id'] = 'id/numeric';
	$arrFields['integrationinbound_id'] = 'integrationinbound_id/numeric';
	$arrFields['module_id'] = 'module_id/numeric';	
	$arrFields['parent_id'] = 'parent_id/numeric';
	$arrFields['profile_id'] = 'profile_id/numeric';
	$arrFields['projectparticipant_id'] = 'projectparticipant_id/numeric';
	$arrFields['relatedentity_id'] = 'relatedentity_id/numeric';
	$arrFields['reminder_id'] = 'reminder_id/numeric';
	$arrFields['rostertemplate_id'] = 'rostertemplate_id/numeric';
	$arrFields['system_id'] = 'system_id/numeric';
	$arrFields['systembuild_id'] = 'systembuild_id/numeric';
	$arrFields['systemmodule_id'] = 'systemmodule_id/numeric';
	$arrFields['transaction_id'] = 'transaction_id/numeric';
	$arrFields['transactionhistory_id'] = 'transactionhistory_id/numeric';
	$arrFields['transactionpending_id'] = 'transactionpending_id/numeric';
	$arrFields['user_id'] = 'user_id/numeric';
	$arrFields['videocategory_id'] = 'videocategory_id/numeric';
	
	// application specific
	$arrFields['is_deleted'] = 'is_deleted/boolean';
	$arrFields['status_code'] = 'status_code';
	
	$arrFields['abbreviation'] = 'g60ab5906_7224_60ab_3737_60ab59068_abbreviation';
	$arrFields['is_sortable'] = 'ge9d76a87_1566_474c_88b3_cc7b43625eff_sortable/boolean';

	$arrFields['apicalltype_id'] = 'apicalltype_id/numeric';
	
	$arrFields['is_simpleimport'] = 'gc5913c98_7aff_4753_9b43_6f388e7b707f_is_simpleimport/boolean';

	// entity whitelist
	//$arrFields['ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_filter'] = 'ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_filter';	// JC commented out as not sure why i put it there

	// below whitelisted only for developers
	if (hasPermission($objConn_a, 'DEVELOPER', __FUNCTION__, false))
	{
		$arrFields['dataentity_id'] = 'dataentity_id';
	}

	return $arrFields;
}
