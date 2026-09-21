// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function widgetclock_wgtClock(d,e,k){function f(){var c=new Clock(b+" .ge-clock");a.element(b,".timeshift").click(function(a){c.moveBack(1);a.preventDefault();return!1})}var a=d,c=this,b=e;this.Form_allowMultipleInstances=function(){return!1};this.Form_onClick=function(){a.setFormFocus(c,b)};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onLoad=function(){var d=a.getViewPort().width,e=a.getViewPort().height,g=a.element(b,".gb-form").width(),h=a.element(b,".gb-form").width();a.repositionForm(b,
e-1.1*h,d-1.1*g);a.unbindEvents(b,"gb-form-close,gb-form");a.bindEvent(c,b,".gb-form","Form","onClick");a.bindEvent(c,b,".gb-form","Form","onDblClick");a.bindEvent(c,b,".gb-form","Form","onMouseEnter");a.bindEvent(c,b,".gb-form","Form","onMouseLeave");a.bindEvent(c,b,".gb-form-close","FormClose","onClick");a.element(b,".gb-form").draggable({handle:".gb-form-handle"});f()};this.Form_onMouseEnter=function(){a.showMDIClose(b)};this.Form_onMouseLeave=function(){a.hideMDIClose(b)};this.Form_onPermissionCheck=
function(){return!0};this.FormClose_onClick=function(){a.closeForm(b)}};
