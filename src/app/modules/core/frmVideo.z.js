// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function core_frmVideo(k,l,f){function m(c){var e='<ul class="scrollbarstyle" style="width:400px; height:500px; overflow:auto; overflow-x: hidden; backgroun-color:red;">',d="";processArray(c,function(a){0<e.length&&(d="<hr>");var b=a.id;a=d+htmlEncode(a.user)+"<br>("+htmlEncode(a.device)+")";e+='<a class="dropdown-item" deviceid='+htmlEncode(b)+' href="#">'+a+"</a><br>"});e+="</ul>";a.element(b,".ge-nav-item-options").html(e);a.element(b,".ge-nav-item").dropdown("toggle")}var a=k,d=this,b=l,g=getGUID(),
n=f.title,h=f.id;this.Form_isDirty=function(){return!1};this.Form_onClick=function(){a.setFormFocus(d,b)};this.Form_onDblClick=function(){a.formToFront(b)};this.Form_onLoad=function(){a.resizeForm(b,500,500);a.element(b,".ge-formshare-panel").remove();var c=n;if(void 0===c||0===c.length)c=h;a.element(b,".ge-form-title").text(c);a.unbindEvents(b,"gb-form-close,gb-form-share,gb-form,gb-formtitle-inner-panel");a.bindEvent(d,b,".gb-form-close","FormClose","onClick");a.bindEvent(d,b,".gb-form-share","FormShare",
"onClick");a.bindEvent(d,b,".cmdClose","cmdClose","onClick");a.bindEvent(d,b,".cmdClose","cmdClose","onEnterKey");a.bindEvent(d,b,".gb-form","Form","onClick");a.bindEvent(d,b,".gb-form","Form","onDblClick");a.bindEvent(d,b,".gb-formtitle-inner-panel","FormTitle","onClick");a.showTip(b,".cmdClose","top left","bottom right",1,"Click here to close the form.");c='<iframe src="'+h+'" width="100%" height="100%" id="'+g+'" name="'+g+'" allowfullscreen allow="autoplay"></iframe>';a.element(b,".ge-video-panel").html(c)};
this.Form_onPermissionCheck=function(){return!0};this.Form_onResize=function(c,d){a.element(b,".ge-video-panel").width(c+"px");a.element(b,".ge-video-panel").height(d-60+"px");a.element(b,".ge-formshare-panel").css("left",c-30+"px")};this.FormTitle_onClick=function(){a.formToFront(b)};this.FormClose_onClick=function(){a.closeForm(b)};this.FormShare_onClick=function(c,d,f){a.element(b,".dropdown").find(".dropdown-menu").is(":hidden")&&(f.stopPropagation(),c=a.ajaxRequestCreate("devicesfetchall",[]),
a.ajaxCall(URL_WEBSERVICE,c,m,a.ajaxError,doNothing,!0))}};
