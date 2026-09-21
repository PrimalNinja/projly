function getNavMapDeveloper(objOS_a)
{
	var os = objOS_a;
	var arrMapRow;

	var arrMap = [
		{
			location : 'root',
			title : 'Menu',
			layout : [['MenuGroup', 
			           'FormWelcomeButton', 
					   'TabExtendButton', 					   
					   'FormMyMessagesButton', 					   
					   'FormCheckoutButton',
					   'ListSpacerButton', 
					   'ListTodoButton', 
					   'ListWorkQueueButton', 
					   'ListMyRemindersButton',
					   'ListContactsButton',
					   'ListWikiPagesButton'
					]]
		}
	];

	//Projly
    if (hasPermission(['VW_PROJLYGROUP']))
	{
        arrMapRow = ['ProjectGroup',
					'ProjectDashButton',
					'--',
					'ListProjectAllIssuesButton',
					'ListProjectBugsButton',
					'ListProjectRequirementsButton',
					'ListProjectSupportButton',
					'ListProjectTasksButton',
					'ListProjectWishListButton',
					'--',
					'ListProjectBugsCompletedButton',
					'ListProjectRequirementsCompletedButton',
					'ListProjectSupportCompletedButton',
					'ListProjectTasksCompletedButton',
					'--',
					'ListProjectParticipantActivityButton',
					'ListProjectResourceActivityButton',
					'ListRosterButton',
					'ListRosterTemplateButton',
					'--',
					'ListProjectButton',
					'ListSprintsButton',
					'--',
					'ListRNDQuestionButton',
					'ListRNDGrantButton',
					'--',
					'ListProjectBillingCategoriesButton', 
					'ListProjectCostCategoriesButton', 
					'ListProjectCampaignTasksButton', 
					'ListProjectCampaignsButton', 
					'--',
					'ListProjectParticipantsButton', 
					'ListProjectResourcesButton', 
					'ListProjectStakeholdersButton'
					];
					  
        arrMap[0].layout.push(arrMapRow);
	}

	//licence manager
    if (hasPermission(['VW_LICENCEMANAGERGROUP']))
	{
		arrMapRow = ['LicenceManagerGroup', 
					'ListSoftwareLicencesButton', 
					'ListSoftwareLicenceTypesButton'
					];
	}
	
	//Printing
    if (hasPermission(['VW_PRINTINGGROUP']))
	{
		arrMapRow = ['PrintingGroup', 
					'ListPrintJobButton', 
					'ListPrintJobLogButton', 
					'--', 
					'ListMyPrintersButton', 
					'ListPublicPrintersButton', 
					'--', 
					'ListMyPrivatePrinterQueuesButton', 
					'ListMyPublicPrinterQueuesButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	//Import
    if (hasPermission(['VW_IMPORTGROUP']))
	{
		arrMapRow = ['ImportGroup', 
					'ListImportsButton',
					'FormImportDocumentsButton', 
					'FormImportImagesButton'
					];


		arrMap[0].layout.push(arrMapRow);
	}

	//Documents
	if (hasPermission(['VW_DOCUMENTGROUP']))
	{
		//'ListDocumentButton', 'ListDocumentTaglessButton', 'ListDocumentRepositoryButton'
		arrMapRow = ['DocumentsGroup', 
					'ListDocumentButton', 
					'ListDocumentRepositoryButton',
					'ListVideosButton', 
					'ListVideoCategoriesButton'
					];
		arrMap[0].layout.push(arrMapRow);
    }

	//Integration
	if (hasPermission(['VW_INTEGRATIONGROUP']))
	{
		arrMapRow = ['IntegrationGroup', 
					'ListIntegrationOutboundTaskButton', 
					'ListIntegrationOutboundsButton', 
					'ListServersButton',
					'ListAPICallsButton',
					'ListAPICallTypesButton',
					'ListAPIKeysButton',
					'--',
					'ListIntegrationInboundTaskButton', 
					'ListIntegrationInboundsButton',
					'--',
					'ListFileSystemsButton',
					'ListFileTypesButton'
					];
		arrMap[0].layout.push(arrMapRow);
    }
        	
	//Settings
	if (hasPermission(['VW_SETTINGGROUP']))
	{
		//'FormThemesButton' 
		arrMapRow = ['SettingsGroup', 
		            'ListMyDevicesButton', 
					'ListUserFormDeviceButton', 
					'FormChangePasswordButton', 
					'FormMyUserSettingsButton',
					'FormMyUserButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	//My Account
    if (hasPermission(['VW_MYACCOUNTGROUP']))
	{
		arrMapRow = ['MyAccountGroup', 
					'FormProductAboutButton', 
					'FormLicensingButton', 
					'FormAccountButton', 
					'FormClientDispatchSettingsButton', 
					'FormClientSettingsButton', 
					'--', 
					'ListMyProductsButton', 
					'ListReceiptsButton', 
					'ListPaymentsButton', 
					'ListTransactionPendingButton', 
					'ListTransactionButton', 
					'ListTransactionHistoryButton', 
					'--', 
					'ListSalesPersonCodeButton', 
					'ListSalesPersonCodeLogButton', 
					'--', 
					'LogoutButton', 
					'ReturnButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	//Configuration - System
	if (hasPermission(['VW_CONFIGURATIONGROUP']))
	{
        arrMapRow = ['ConfigurationSystemGroup', 
					'FormLicensingButton',
					'ListCMSButton', 
					'ListColoursButton',
					'ListContactStatusesButton',
					'ListContactTypesButton',
					'ListDesktopRegionsButton', 
					'ListDocumentRetentionTypesButton', 
					'ListDocumentTypesButton', 
					'ListEmployerSizesButton',
					'ListFAQButton', 
					'ListGenderButton', 
					'ListHonorificButton', 
					'ListIconButton', 
					'ListIndustryTypesButton', 
					'ListInternalMessageFoldersButton',
					'ListMessageTemplateTypesButton', 
					'ListMessageTemplatesButton', 
					'ListNegotiatedDiscountsButton', 
					'ListNegotiatedRatesButton', 
					'ListPaymentMethodsButton', 
					'ListPaymentStatusButton', 
					'ListPrinterPurposesButton', 
					'ListPrinterTypesButton', 
					'ListProductsButton', 
					'ListProductTypesButton', 
					'ListRegistrationTypesButton', 
					'ListSequencesButton',
					'ListServerProtocolsButton', 
					'ListStartupItemsButton', 
					'ListStatesButton', 
					'ListSuburbsButton', 
					'ListTodoStatusesButton', 
					'ListTransactionStatusButton', 
					'ListUserStatusButton', 
					'ListVideoSourcesButton', 
					'ListWorkQueueItemTypesButton',
					'ListWorkQueueStatusesButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}	

	//Security
	if (hasPermission(['VW_SECURITYGROUP']))
	{
		//'ListConfirmedRegistrationsButton', 'ListUnconfirmedRegistrationsButton'
		arrMapRow = ['SecurityGroup', 
					'SecurityDashButton', 
					'--',
					'ListAccountButton', 
					'ListBranchesButton',
					'ListClientsButton', 
					'ListMyClientsButton',
					'ListDataAuditLogsButton', 
					'ListDevicesButton', 
					'ListDeviceLogButton', 
					'ListMyProfilesButton', 
					'ListRegistrationsButton', 
					'ListResetPasswordButton', 
					'ListMyUsersButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	//System
	if (hasPermission(['VW_SYSTEMGROUP']))
	{
		arrMapRow = ['SystemGroup', 
					'FormHealthButton',
					'ListBatchJobsButton', 
					'ListConfigsButton', 
					'--',
					'ListDBProcessButton', 
					'--',
					'ListSchemaChangesPendingButton', 
					'ListSchemaChangesDoneButton', 
					'--',
					'ListSystemFlagValuesButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	//Tools
	// if (hasPermission(['VW_TOOLGROUP']))
	// {
		// arrMapRow = ['ToolsGroup', 
					// 'WidgetCalculatorButton', 
					// 'WidgetClockButton'
					// ];
		// arrMap[0].layout.push(arrMapRow);
	// }

	//Development
	if (hasPermission(['VW_DEVELOPMENTGROUP']))
	{
		arrMapRow = ['--'];
		arrMap[0].layout.push(arrMapRow);

		arrMapRow = ['DevelopmentGroup', 
					'ListApplicationsButton', 
					'ListModulesButton'
					];
		arrMap[0].layout.push(arrMapRow);
		
	}

	//Developer
	if (hasPermission(['DEVELOPER']) && DEVELOPER === 'TRUE')
	{
		arrMapRow = ['--'];
		arrMap[0].layout.push(arrMapRow);

		arrMapRow = ['DeveloperGroup',  
					'ListSystemButton', 
					'ListSystemModuleButton', 
					'ListSystemBuildButton',  
					'ListNavItemButton',  
					'--', 
					'ListEntityButton', 
					'ListEntityOperationDefaultButton', 
					'ListPermissionsButton', 
					'ListPermissionCategoriesButton', 
					'--',
					'ListApplicationFormsButton',
					'ListApplicationReportsButton',
					'ListMobileFormsButton',
					'--',
					'ListFormLayoutButton', 
					'ListSystemLayoutButton', 
					'ListBuilderLayoutButton', 
					'ListReportBuilderLayoutButton', 
					'ListFormLayoutsAllButton', 
					'ListFormSectionTemplatesButton', 
					'ListFragmentsFormButton', 
					'ListFormDataTypesButton', 
					'ListFormTypesButton', 
					'--',
					'ListReportLayoutButton', 
					'ListReportSectionTemplatesButton', 
					'ListReportDataTypesButton',
					'--',
					'ListAuthenticationPluginsButton', 
					'ListChartPluginsButton', 
					'ListIntegrationInboundPluginsButton', 
					'ListIntegrationOutboundPluginsButton', 
					'--',
					'ListAuthenticationTypesButton', 
					'ListIntegrationInboundTypesButton', 
					'ListIntegrationOutboundTypesButton', 
					'--',
					'ListChartsButton', 
					'ListChartTypesButton', 
					'ListSecurityChartsButton', 
					'--',
					'ListDisplayOrdersButton', 
					'ListMapProvidersButton',
					'--',
					'ListCheckDigitTypesButton',
					'ListSettingModulesButton',
					'ListSequenceTypesButton',
					'--',
					'ListSystemFlagTypesButton',
					'ListThemeButton'
					];
		arrMap[0].layout.push(arrMapRow);

		// PLACE NEW TILES INTO HERE (the DeveloperRnDGroup)
		//arrMapRow = ['DeveloperRnDGroup'];
		arrMapRow = ['DeveloperRnDGroup', 
					'ProcessIntegrationTaskButton'
					];
		arrMap[0].layout.push(arrMapRow);

		// developer TEST group
		arrMapRow = ['DeveloperTestGroup'];
		arrMap[0].layout.push(arrMapRow);
	}

	// Mobile
	if (os.hasCapability('mobile'))
	{
		arrMapRow = ['--'];
		arrMap[0].layout.push(arrMapRow);

		arrMapRow = ['LogoutMobileButton', 
					'ReturnMobileButton'
					];
		arrMap[0].layout.push(arrMapRow);
	}

	return arrMap;
}
