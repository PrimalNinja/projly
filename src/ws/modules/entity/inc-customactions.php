<?php

// for addto, we are naming such as relative__entity even though the glue table might be named something else.  this is because the JS can swap the relations around so we have parent__child or child__parent
function getCustomActions()
{
	return array(
		"addto_profile__permission" => array("dependencies" => 'entity/actions/actionProfilePermission_PermissionAddTo', "function" => 'actionProfilePermission_PermissionAddTo'),
		"addto_rostertemplate__projectparticipant" => array("dependencies" => 'entity/actions/actionRosterTemplate_ProjectParticipantAddTo', "function" => 'actionRosterTemplate_ProjectParticipantAddTo'),
		"addto_system__systemmodule" => array("dependencies" => 'entity/actions/actionSystem_SystemModuleAddTo', "function" => 'actionSystem_SystemModuleAddTo'),
		"addto_user__profile" => array("dependencies" => 'entity/actions/actionUserProfile_ProfileAddTo', "function" => 'actionUserProfile_ProfileAddTo'),

		"systemform_build_application" => array("dependencies" => 'entity/actions/actionApplicationBuild', "function" => 'actionApplicationBuild'),		
		"systemform_publish_application" => array("dependencies" => 'entity/actions/actionApplicationPublish', "function" => 'actionApplicationPublish'),
		"systemform_runcode_application" => array("dependencies" => 'entity/actions/actionApplicationRun', "function" => 'actionApplicationRun'),
		"systemform_runcodetest_application" => array("dependencies" => 'entity/actions/actionApplicationTest', "function" => 'actionApplicationTest'),

        "systemform_createdataaccessor_entity" => array("dependencies" => 'entity/actions/actionEntityCreateDataAccessor', "function" => 'actionEntityCreateDataAccessor'),
        "systemform_createalldataaccessors_entity" => array("dependencies" => 'entity/actions/actionEntityCreateDataAccessorsAll', "function" => 'actionEntityCreateDataAccessorsAll'),

        "systemform_buildfilejson_systemform" => array("dependencies" => 'entity/actions/actionSystemFormFileJSONCreate', "function" => 'actionSystemFormFileJSONCreate'),
        "systemform_buildfilejsonall_systemform" => array("dependencies" => 'entity/actions/actionSystemFormFileJSONCreateAll', "function" => 'actionSystemFormFileJSONCreateAll'),

		"addto_branch__user" => array("dependencies" => 'entity/actions/actionBranchUser_BranchAddTo', "function" => 'actionBranchUser_BranchAddTo'),
		"addto_user__branch" => array("dependencies" => 'entity/actions/actionUserBranch_UserAddTo', "function" => 'actionUserBranch_UserAddTo'),
		"systemform_mybranchget" => array("dependencies" => 'entity/actions/actionMyBranchGet', "function" => 'actionMyBranchGet'),
		"systemform_mybranchselect" => array("dependencies" => 'entity/actions/actionMyBranchSelect', "function" => 'actionMyBranchSelect'),
		
		"systemform_myclientlogin_as" => array("dependencies" => 'entity/actions/actionMyClientLoginAs', "function" => 'actionMyClientLoginAs'),
		"systemform_login_as" => array("dependencies" => 'entity/actions/actionLoginAs', "function" => 'actionLoginAs'),
		"systemform_rst_documentrepository" => array("dependencies" => 'entity/actions/actionResetDocumentRepository', "function" => 'actionResetDocumentRepository'),
		"systemform_rst_password" => array("dependencies" => 'entity/actions/actionResetPassword', "function" => 'actionResetPassword'),
		"systemform_rst_permission" => array("dependencies" => 'entity/actions/actionResetPermission', "function" => 'actionResetPermission'),
		"systemform_touch_systemform" => array("dependencies" => 'entity/actions/actionSystemFormTouch', "function" => 'actionSystemFormTouch'),
				
        "systemform_test_integrationoutbound" => array("dependencies" => 'entity/actions/actionIntegrationOutboundTest', "function" => 'actionIntegrationOutboundTest'),
        "systemform_createtask_integrationoutbound" => array("dependencies" => 'entity/actions/actionIntegrationOutboundCreateTask', "function" => 'actionIntegrationOutboundCreateTask'),
        
        "systemform_send_emailstatusupdate" => array("dependencies" => 'entity/actions/actionSendEmailStatusUpdate', "function" => 'actionSendEmailStatusUpdate'),
                
        "systemform_system_build" => array("dependencies" => 'entity/actions/actionSystemBuild', "function" => 'actionSystemBuild'),
        "systemform_systembuild_rebuild" => array("dependencies" => 'entity/actions/actionSystemBuildRebuild', "function" => 'actionSystemBuildRebuild'),

        "systemform_create_roster_rostertemplate" => array("dependencies" => 'entity/actions/actionRosterTemplateCreateRoster', "function" => 'actionRosterTemplateCreateRoster')
	);
}
