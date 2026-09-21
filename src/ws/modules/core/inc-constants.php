<?php

// defaults
define('DEFAULT_COUNTRY', 'AUSTRALIA'); // this constant is used for national functionality
define('MAX_IMAGE_FETCH_COUNT', '48'); // this defines the maximum number of images that can be fetched in a single page

// public actions
define('ACTION_PUBLIC_AUTHENTICATION_CHECK', 'public_authenticationcheck');
define('ACTION_PUBLIC_CONFIRMATION', 'public_confirmation');
define('ACTION_PUBLIC_DOCUMENTDOWNLOAD', 'public_documentdownload');
define('ACTION_PUBLIC_FETCHIMAGE', 'public_fetchimage');
define('ACTION_PUBLIC_FETCHMETADATA', 'public_fetchmetadata');
define('ACTION_PUBLIC_GETDATABASENAME', 'public_getdatabasename');
define('ACTION_PUBLIC_LOGIN', 'public_login');
define('ACTION_PUBLIC_LOGOUT', 'public_logout');
define('ACTION_PUBLIC_PASSWORDRESET', 'public_passwordreset');
define('ACTION_PUBLIC_REGISTER', 'public_register');
define('ACTION_PUBLIC_REGISTRATIONFETCH', 'public_registrationfetch');
define('ACTION_PUBLIC_RETURN', 'public_return');
define('ACTION_PUBLIC_VERIFICATION', 'public_verification');
define('ACTION_PUBLIC_FETCHWIKI', 'public_fetchwiki');

// actions missing from here because they are driven from metadata within the batchJobAdd function


// document types
define('DOCUMENTTYPE_IMAGE', 'IMG');	// generic
define('DOCUMENTTYPE_GENERIC', 'GEN');	// generic
define('DOCUMENTTYPE_SUBURBS', 'SUB');	// REFERENCE

// errors
define('INVALID_SCHEMAVERSION', 'System error, please contact support.');
define('EMAIL_REGISTRATION_FAILURE', 'Email registrations are temporarily unavailable, please try again later.<br><br>If the problem persists please contact support.');
define('INTEGRITYVIOLATION', 23000);	// sql related

// general
define('CHUNKSIZE', 20);

define('CR', "\r"); // carriage return; Mac
define('LF', "\n"); // line feed; Unix
define('TAB', "\t"); // tab
define('CRLF', "\r\n"); // carriage return and line feed; Windows
define('BR', '<br />' . LF); // HTML Break

// response codes
define('RESPONSE_DEPRECATED', '1');	// deprecated for now
define('RESPONSE_ERRORMESSAGE', '2');
define('RESPONSE_FORCEDLOGOUT', '3');
define('RESPONSE_DOCUMENT', '4');
define('RESPONSE_URL', '5');
define('RESPONSE_RELOAD', '6');
define('RESPONSE_BROADCASTPRINTJOB', '7');
define('RESPONSE_OK', '0');

// server types
define('SERVERTYPE_FTPGET', 'FTPGET');
define('SERVERTYPE_FTPPUT', 'FTPPUT');
define('SERVERTYPE_HTTPGET', 'HTTPGET');
define('SERVERTYPE_HTTPPOST', 'HTTPPOST');
define('SERVERTYPE_HTTPSGET', 'HTTPSGET');
define('SERVERTYPE_HTTPSPOST', 'HTTPSPOST');
define('SERVERTYPE_HTTPPUT', 'HTTPPUT');
define('SERVERTYPE_HTTPSPUT', 'HTTPSPUT');

// future entities

// core tables
define('CORE_DATACHANGE', 'c_datachange');
define('CORE_DATAFORMENTITY', 'c_dataformentity');
define('CORE_ESB', 'c_esb');
define('CORE_ESBBROADCASTER', 'c_esbbroadcaster');
define('CORE_ESBLISTENER', 'c_esblistener');
define('CORE_ESBSTATUS', 'c_esbstatus');
define('CORE_MESSAGEPREPARED', 'c_messageprepared');
define('CORE_SYSTEM', 'c_system');
