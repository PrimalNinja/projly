// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function core_frmPublic(a,b,d){var c=this;this.Form_allowMultipleInstances=function(){return!1};this.Form_canClose=function(){return!1};this.Form_onClick=function(){a.setFormFocus(c,b)};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onLoad=function(){0===a.getFormFromHash(location.hash).length?a.showForm("core.frmLogin","",!0):a.hashChange(location.hash)};this.Form_onPermissionCheck=function(){return!0}};
