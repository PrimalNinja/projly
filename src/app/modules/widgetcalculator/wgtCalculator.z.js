// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function widgetcalculator_wgtCalculator(a,b,d){var c=this;this.Form_onClick=function(){a.setFormFocus(c,b)};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onLoad=function(){a.unbindEvents(b,"gb-form-close,gb-form");a.bindEvent(c,b,".gb-form","Form","onClick");a.bindEvent(c,b,".gb-form","Form","onDblClick");a.bindEvent(c,b,".gb-form","Form","onMouseEnter");a.bindEvent(c,b,".gb-form","Form","onMouseLeave");a.bindEvent(c,b,".gb-form-close","FormClose","onClick");a.element(b,".gb-form").draggable({handle:".gb-form-handle"})};
this.Form_onMouseEnter=function(){a.showMDIClose(b)};this.Form_onMouseLeave=function(){a.hideMDIClose(b)};this.Form_onPermissionCheck=function(){return!0};this.FormClose_onClick=function(){a.closeForm(b)}};
