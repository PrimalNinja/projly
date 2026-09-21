// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function core_frmLicenceChecker(a,b,d){this.Form_allowMultipleInstances=function(){return!1};this.Form_canClose=function(){return!0};this.Form_onLoad=function(){if(a.toBoolean(a.getProperty("licensed"))){var c=parseInt(a.getProperty("expirydays"),10);0<c&&a.dialogAlert("Your licence will expire in "+c+" days, please contact support to renew your licence.",function(){a.closeForm(b)})}else a.dialogAlert("Your licence has expired, please contact support to renew your licence.",function(){a.closeForm(b)})};
this.Form_onPermissionCheck=function(){return!0};this.TabEnd_onFocus=function(){};this.TabStart_onFocus=function(){}};
