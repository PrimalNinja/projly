<?php

// maps URL
define('MAPSURL', 'http://maps.google.com/maps?z=12&t=m&q=loc:~GPS~');	// replace ~GPS~ with GPS coordinates, i.e. http://maps.google.com/maps?z=12&t=m&q=loc:-37.8503666,144.9959813

// security
define('MAXRESERVEDCLIENTS', 6);						// number of reserved client IDs
//define('SESSION_NAME', 'fms_server');					// session name
define('SESSION_NAME', APP_CODE . '_client');
define('SESSION_SECURITY', 'server_security_dictionary');	// security dictionary name
define('SECURE_IDS', 'TRUE');								// turn on ID security, should be enabled for production
define('SECURE_IDS_WITH_GUIDS', 'FALSE');					// if TRUE uses guids on the session, otherwise uses 2-way encryption
define('ALLOW_STAY_LOGGEDIN', 'TRUE');					// this should be TRUE only if we want to allow users to be authenticated based on a cookie

// user settings
define('USER_DATETIME', 'YmdHis');

// modules to load, case sensitive
//define('SERVERMODULES', 'application,cart,core,dash,developer,docs,entity,esb,integration,msg,process,projly,security,system,utils');		// modules to load, case sensitive
define('SERVERMODULES', 'cart,core,dash,developer,docs,entity,esb,import,integration,msg,process,projly,public,report,security,setting,system,utils');		// modules to load, case sensitive

// messages
define('MSG_FORCEDLOGOUT', 'The system has logged you out due to security reasons.<br>This can occur if you login multiple times with the same browser.');		// forced logout message
define('MSG_SHORTMESSAGE_LENGTH', '150');

// paths and urls
define('BUILD_PATH', APP_PATH . 'builds/');
define('FILEFORMAT_PATH',  APP_PATH . 'ws/modules/import/fileformats/');	// location of file formats
define('FILEOUTPUT_PATH',  APP_PATH . 'output/');	// location of file formats
define('TEMP_PATH', APP_PATH . 'ws/temp/');			// temp path
define('TEMP_PENDING_PATH', TEMP_PATH . 'pending/');// where pending are temporarily stored
define('TEMP_UPLOAD_PATH', TEMP_PATH . 'uploads/');	// where uploads are temporarily stored
define('TEMP_GENERAL_PATH', TEMP_PATH . 'general/');	// where general temporary files are
define('URL_TEMP_PATH', URL_APP_PATH . 'ws/temp/');			// temp path url
define('URL_GENERAL_PATH', URL_TEMP_PATH . 'general/');	// where general temporary files are
define('WS_PATH', APP_PATH . 'ws/');					// where the webservice is
define('URL_WELCOME_PAGE_BARCODE', URL_APP_PATH . 'welcome.php' . "?venueid=~TOKEN~");
define('URL_WELCOME_PAGE_EMAIL', URL_APP_PATH . 'welcome.php' . "?bookingid=~TOKEN~");


define('USECLUSTERPATH3', 'TRUE');	// use a 3 level document cluster path instead of 5 (5 is better for things like websites, 3 is better for documents)							

define('PATH_CLIENTREPOSITORY_ROOT', APP_PATH . 'repository-client/');            // root repository
define('PATH_CLIENTREPOSITORY_DOCUMENT', APP_PATH . 'repository-client/documents/');    // where the document cluster root is for the document module / printjobs go here too
define('PATH_CLIENTREPOSITORY_TEMPLATES', APP_PATH . 'repository-client/templates/');    // where the document template root is for the document module / printjobs go here too
define('REL_CLIENTREPOSITORY_PRINT_DOCUMENT', 'repository-client/documents/');            // where the document cluster root is for the document module / printjobs go here too
define('URL_CLIENTREPOSITORY_ROOT', URL_APP_PATH . 'repository-client/');            // root repository
define('URL_CLIENTREPOSITORY_DOCUMENT', URL_APP_PATH . 'repository-client/documents/');    // where the document cluster root is for the document module / printjobs go here too
define('URL_CLIENTREPOSITORY_TEMPLATES', URL_APP_PATH . 'repository-client/templates/');    // where the document template root is for the document module / printjobs go here too

define('PATH_SYSTEMREPOSITORY_ROOT', APP_PATH . 'repository-system/');            // root repository
define('PATH_SYSTEMREPOSITORY_DOCUMENT', APP_PATH . 'repository-system/documents/');    // where the document cluster root is for the document module / printjobs go here too
define('PATH_SYSTEMREPOSITORY_TEMPLATES', APP_PATH . 'repository-system/templates/');    // where the document template root is for the document module / printjobs go here too
define('REL_SYSTEMREPOSITORY_PRINT_DOCUMENT', 'repository-system/documents/');            // where the document cluster root is for the document module / printjobs go here too
define('URL_SYSTEMREPOSITORY_ROOT', URL_APP_PATH . 'repository-system/');            // root repository
define('URL_SYSTEMREPOSITORY_DOCUMENT', URL_APP_PATH . 'repository-system/documents/');    // where the document cluster root is for the document module / printjobs go here too
define('URL_SYSTEMREPOSITORY_TEMPLATES', URL_APP_PATH . 'repository-system/templates/');    // where the document template root is for the document module / printjobs go here too

define('LOG_APICALL_FILE', APP_PATH . 'ws/log/apicall.log');		// LOGGING
define('LOG_BUILD_FILE', APP_PATH . 'ws/log/build.log');		// BUILD LOGGING
define('LOG_DEBUG_FILE', APP_PATH . 'ws/log/debug.log');		// LOGGING
define('LOG_DEBUG_SQL_FILE', APP_PATH . 'ws/log/debug_sql.log');	// SQL LOGGING
define('LOG_ERROR_FILE', APP_PATH . 'ws/log/phperrors.log');	// LOGGING
define('LOG_MISSING_FILES', APP_PATH . 'ws/log/missingfiles.log');	// LOGGING MISSING FILES
define('LOG_PAYMENT_FILE', APP_PATH . 'ws/log/payment.log');		// PAYMENT LOGGING
define('LOG_PHPCONSOLE_FILE', APP_PATH . 'ws/log/phpconsole.log'); // PHP CONSOLE LOG
define('LOG_PRINTING_FILE', APP_PATH . 'ws/log/printing.log');		// LOGGING
define('LOG_REPOSITORY_FILE', APP_PATH . 'ws/log/repository.log');		// LOGGING
define('LOG_ROUTING_FILE', APP_PATH . 'ws/log/routing.log');		// LOGGING
define('LOG_SECURITY_FILE', APP_PATH . 'ws/log/security.log');		// LOGGING
define('LOG_SETTING_FILE', APP_PATH . 'ws/log/settings.log');		// LOGGING

// encryption
define('ALLOWUNENCRYPTEDINDB', 'FALSE');						// usually used in development to allow unencrypted passwords to be authenticated
define('ENCRYPTION_INTERATIONBASE', '1000');						// base number of encryption iterations
define('ENCRYPTION_INTERATIONOFFSET', '6');						// offset number of encryption iterations

define('ENCRYPTION_TYPE_PASSWORD1WAY', 'pluginEncrypt_MD5');			// the currently configured 1-way password encryption type or '' for none

define('ENCRYPTION_TYPE_PASSWORD2WAY', 'pluginEncrypt_OpenSSLRandomIV');	// the currently configured 2-way password encryption type or '' for none
define('DECRYPTION_TYPE_PASSWORD2WAY', 'pluginDecrypt_OpenSSLRandomIV');	// the currently configured 2-way password decryption type or '' for none

define('ENCRYPTION_TYPE_ID2WAY', 'pluginEncrypt_OpenSSL');			// the currently configured 2-way id encryption type or '' for none
define('DECRYPTION_TYPE_ID2WAY', 'pluginDecrypt_OpenSSL');			// the currently configured 2-way id decryption type or '' for none

define('DECRYPTION_TYPE_LICENCE2WAY', 'pluginDecrypt_OpenSSLRandomIV');	// the currently configured 2-way licence decryption type or '' for none

// system table entries
define('SETTING_KEY_SCHEMAVERSION', 'SCHEMA_VERSION');
define('SETTING_KEY_SERVERDISABLED', 'SERVER_DISABLED');
define('SETTING_KEY_SERVERDISABLEDREASON', 'SERVER_DISABLED_REASON');
define('SETTING_KEY_LICENCEKEY', 'LICENCEKEY');

// xml/json values
define('BOOLTRUE', 'TRUE');
define('BOOLFALSE', 'FALSE');

// limits
define('NONAJAXGRIDLIMIT', '500');								// grid page size

$BLACKLISTED_FORMBUILDER_IDS = ['id','client_id','entity_id','dataentity_id','data_client_id'];

// statuses (for batch jobs, documents, printqueues)
define('STAT_COMPLETED', 'COMPLETED');
define('STAT_ERROR', 'ERROR');
define('STAT_PAUSED', 'PAUSED');
define('STAT_PENDING', 'PENDING');
define('STAT_PREPARING', 'PREPARING');
define('STAT_PRINTING', 'PRINTING');
define('STAT_READY', 'READY');
define('STAT_YES', 'YES');
define('STAT_NO', 'NO');

// document retention types (for documentSimpleCopy, documentSimpleAdd and documentAdd)
define('DR_DOCUMENT', 'DOCUMENT');
define('DR_PRINTJOB', 'PRINTJOB');
define('DR_SYSTEM', 'SYSTEM');
define('DR_TEMPORARY', 'TEMPORARY');
define('DR_USER', 'USER');
//define('DR_EXPIRES', 'EXPIRES');		// only this one can use the optional expiry date

// listers
define('ENABLE_ROWNUM', 'FALSE');

// System specific behavioural
define('CUSTOM_FRAGMENTID', 'FORMFRAGMENT');		// is used to substitute a dataentity_id safely from the client (specifically for the entity 'FRAGMENT' for form fragments)
define('CUSTOM_SELFACCOUNTID', 'MYACCOUNT');		// this is used to substitute the current logged in userid for certain filters (of which the user can still see all if they require elsewhere) - not intended to be secure
define('CUSTOM_SELFCLIENTID', 'MYCLIENT');					// this is used to substitute the current logged in clientid for certain filters (of which the user can still see all if they require elsewhere) - not intended to be secure
define('CUSTOM_SELFID', 'MYSELF');						// this is used to substitute the current logged in userid for certain filters (of which the user can still see all if they require elsewhere) - not intended to be secure
define('CUSTOM_MYDEVICE', 'MYDEVICE');						// this is used to substitute the current logged in deviceid for certain filters (of which the user can still see all if they require elsewhere) - not intended to be secure
define('CUSTOM_SELFEMPLOYEEID', 'MYSELFEMPLOYEE');		// this is used to substitute the current logged in userid for certain filters (of which the user can still see all if they require elsewhere) - not intended to be secure
define('PERMIT_LISTERENTITIES_ACROSSCLIENTS', 'PROFILE,PROFILE_PERMISSION,USER,USER_PROFILE');			// data that is permitted to be visible for some clients
define('MINEXPOSEDFIELDSIZE', '255');						// minimum length of an exposed text field
define('FETCHSYSTEMFORMSFROMFILE', 'TRUE');						// if true, a file json data will be used instead of systemform jsondata

// ignore clientid for the following (ie: they are global entities to all clients)
define('ENTITIESFILTEREDBYAPPLICATION', 'CMS,MESSAGETEMPLATE');	// these entities we have an application column to filter them by configured application
define('ENTITIESFILTEREDBYBRANCH', 'BRANCH');	// these entities we have an application column to filter them by branch
define('ENTITIESFILTEREDBYBRANCHSELECTED', 'BLAH');	// these entities we have an application column to filter them by selected branch
define('ENTITIESFILTEREDBYDEVICE', 'BLAH');	// these entities we have an application column to filter them by device
define('IGNOREHISTORYONDELETE_ENTITES', 'CLIENT');	// upon deletion, don't put in the history
define('IGNORECLIENT_SYSTEMADMIN', 'ACCOUNT,CLIENT,DEVICELOG,DOCUMENT,ENTITY,ENTITYOPERATION,MODULE,PERMISSION,PERMISSIONCATEGORY,PRODUCT,REGISTRATION,SALESPERSONCODE,SALESPERSONCODELOG');	// entities that we want to ignore client for the system admin only
define('IGNORECLIENT_SYSTEMOWNERENTITES', 'ACCOUNT,CLIENT,DEVICELOG,DOCUMENT,ENTITY,ENTITYOPERATION,MODULE,PERMISSION,PRODUCT,REGISTRATION,SALESPERSONCODE,SALESPERSONCODELOG');	// entities that we want to ignore client for the system owner only
define('IGNORECLIENT_LISTERENTITIES_PROJLY', 'PROJECTCAMPAIGNSTATUS,PROJECTCAMPAIGNTYPE,PROJECTISSUEPRIORITY,PROJECTISSUESTATUS,PROJECTISSUETYPE,PROJECTPARTICIPANTTYPE,PROJECTPRIORITY,PROJECTSTATUS,PROJECTTASKPRIORITY,PROJECTTASKSTATUS,PROJECTTYPE,PROJECTRESOURCETYPE');										// the reference data that is accessible across all clients
define('IGNORECLIENT_LISTERENTITIES_NONREFERENCE', 'CMS,DESKTOPREGION,FAQ,STARTUPITEM,VIDEO,VIDEOCATEGORY,VIDEOSOURCE');										// the reference data that is accessible across all clients
define('IGNORECLIENT_LISTERENTITIES_REFERENCE', 'APICALLTYPE,CHECKDIGITTYPE,COLOUR,CONTACTSTATUS,CONTACTTYPE,DISPLAYORDER,DOCUMENTREPOSITORY,DOCUMENTTYPE,FILETYPE,GENDER,ICON,INTERNALMESSAGEFOLDER,MAPPROVIDER,MESSAGETEMPLATETYPE,PAYMENTMETHOD,PAYMENTSTATUS,PRINTERPURPOSE,SERVERPROTOCOL,SEQUENCETYPE,STATE,SUBURB,TODOSTATUS');										// the reference data that is accessible across all clients
define('IGNORECLIENT_LISTERENTITIES_SECURITY', 'DOCUMENTRETENTIONTYPE,PRODUCTTYPE,REGISTRATIONTYPE,USERSTATUS');		// the security reference data that is accessible across all clients
define('IGNORECLIENT_LISTERENTITIES_SETTINGS', 'PRINTERTYPE,SETTINGMODULE,THEME');										// the setting data that is accessible across all clients
define('IGNORECLIENT_LISTERENTITIES', IGNORECLIENT_LISTERENTITIES_SECURITY . ',' . IGNORECLIENT_LISTERENTITIES_NONREFERENCE . ',' . IGNORECLIENT_LISTERENTITIES_REFERENCE . ',' . IGNORECLIENT_LISTERENTITIES_PROJLY . ',' . IGNORECLIENT_LISTERENTITIES_SETTINGS);				// data that is accessible across all clients

// REGISTRATION TYPES

define('ACCOUNTTYPE_BUSINESS', 'BUSINESS');
define('ACCOUNTTYPE_INDIVIDUAL', 'INDIVIDUAL');

// BATCH JOB EMAILS

define('RENEWALPERIODDAYS', '30');			// number of days before expiry to start sending renewal messages
define('PRODUCTNOTIFICATIONDAYS', '15');	// number of days before notifying the user again

//TIMERS

define('HEARTBEAT_EXPIRY_INSECONDS', '30'); // 30 seconds

// HOUSEKEEPING
define('CLEAN_ESB', 'TRUE');
define('CLEAN_TEMP', 'TRUE');
?>
