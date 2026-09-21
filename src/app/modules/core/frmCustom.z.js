// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function core_frmCustom(a,b,c){var d=this,f=getGUID(),k=c.title,e=c.url,l=c.barcodetype,g=c.barcodeaction,h=c.barcodecontent;"barcode"===c.type&&(e=str_replace(e,"URL_2DBARCODE_GENERATOR",URL_2DBARCODE_GENERATOR),"default"===l&&(0<g.length?e+="?action="+g:0<h.length&&(e+="?content="+h)));this.Form_getDesktopRegion=function(){return"C"};this.Form_isDirty=function(){return!1};this.Form_onClick=function(){a.setFormFocus(d,b)};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onLoad=function(){var c=
k;if(void 0===c||0===c.length)c=e;a.element(b,".ge-form-title").text(c);a.unbindEvents(b,"gb-form-close,gb-form-print,gb-form,gb-formtitle-inner-panel");a.bindEvent(d,b,".gb-form-close","FormClose","onClick");a.bindEvent(d,b,".gb-form-print","FormPrint","onClick");a.bindEvent(d,b,".cmdClose","cmdClose","onClick");a.bindEvent(d,b,".cmdClose","cmdClose","onEnterKey");a.bindEvent(d,b,".gb-form","Form","onClick");a.bindEvent(d,b,".gb-form","Form","onDblClick");a.bindEvent(d,b,".gb-formtitle-inner-panel",
"FormTitle","onClick");a.showTip(b,".cmdClose","top left","bottom right",1,"Click here to close the form.");c='<iframe src="'+e+'" width="100%" height="100%" id="'+f+'" name="'+f+'"></iframe>';a.element(b,".ge-custom-content").html(c)};this.Form_onPermissionCheck=function(){return!0};this.FormTitle_onClick=function(){a.formToFront(b)};this.FormClose_onClick=function(){a.closeForm(b)};this.FormPrint_onClick=function(){"Microsoft Edge"==a.getBrowser().name?parent.document.getElementsByName(f)[0].contentWindow.document.execCommand("print",
!1,null):(window.frames[f].focus(),window.frames[f].print())}};
