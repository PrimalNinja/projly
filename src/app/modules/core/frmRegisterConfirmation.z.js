// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function core_frmRegisterConfirmation(e,f,h){function g(b){var d=b[0].clientcode;b=b[0].emailaddress;0<d.length&&0<b.length&&(a.element(c,".ge-clientcode-field").html(htmlEncode(d)),a.element(c,".ge-emailaddress-field").html(htmlEncode(b)),a.element(c,".ge-logindetails-panel").show(),a.element(c,".ge-logindetails-panel").removeClass("hidden"))}var a=e,c=f;this.Form_allowMultipleInstances=function(){return!0};this.Form_canClose=function(){return!0};this.Form_isDirty=function(){return!1};this.Form_onFocus=
function(){a.closeExclusive(c)};this.Form_onLoad=function(){a.element(c,".ge-appname-field").html(APP_NAME);var b=a.ajaxRequestCreate("public_registrationfetch",[]);a.ajaxCall(URL_WEBSERVICE,b,g,a.ajaxError,doNothing,!0)};this.Form_onPermissionCheck=function(){return!0}};
