// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function widgetpanel_wgtPanel(a,b,e){var c=this;this.Form_allowMultipleInstances=function(){return!1};this.Form_onBroadcast=function(b,c,d){"panel"===b&&("actionShowEntityList"===c?a.showForm("entity.frmLister",d):"actionShowForm"===c?a.showForm(d):"actionToggleFullScreen"===c?(a.setFocus(),!document.FullscreenElement&&!document.mozFullscreenElement&&!document.webkitFullscreenElement&&!document.msFullscreenElement?document.documentElement.requestFullscreen?document.documentElement.requestFullscreen():
document.documentElement.msRequestFullscreen?document.documentElement.msRequestFullscreen():document.documentElement.mozRequestFullscreen?document.documentElement.mozRequestFullscreen():document.documentElement.webkitRequestFullscreen&&document.documentElement.webkitRequestFullscreen(element.ALLOW_KEYBOARD_INPUT):document.exitFullscreen?document.exitFullscreen():document.msExitFullscreen?document.msExitFullscreen():document.mozCancelFullscreen?document.mozCancelFullscreen():document.webkitExitFullscreen&&
document.webkitExitFullscreen()):"actionZoom"===c&&(a.setFocus(),a.zoomInOut()))};this.Form_canClose=function(){return!1};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onMouseEnter=function(){a.showMDIClose(b)};this.Form_onMouseLeave=function(){a.hideMDIClose(b)};this.Form_onPermissionCheck=function(){return!0};this.Form_onLoad=function(){a.unbindEvents(b,"gb-form-close,gb-form");a.bindEvent(c,b,".gb-form","Form","onDblClick");a.bindEvent(c,b,".gb-form","Form","onMouseEnter");a.bindEvent(c,
b,".gb-form","Form","onMouseLeave");a.bindEvent(c,b,".gb-form-close","FormClose","onClick");a.openTab("#widgetpanel.wgtContent","&fullscreen=true");a.element(b,".gb-form").draggable({handle:".gb-form-handle"})};this.FormClose_onClick=function(){a.closeForm(b)}};
