// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function entityform_server(g,h,l,m){function e(){a.element(b,"."+c+"_fg").hide();a.element(b,"."+d+"_fg").hide();"FILESYSTEM"==a.element(b,"."+f+":checked").val()?(a.element(b,"."+d+"_fg").show(),a.element(b,"."+c+"_fg").hide()):(a.element(b,"."+c+"_fg").show(),a.element(b,"."+d+"_fg").hide())}var a=g,k=this,b=h,f=a.massageClassName("gafb65c33-7df8-4b01-a919-4df125c08f0e","FILESYSTEMPROTOCOL"),d=a.massageClassName("gafb65c33-7df8-4b01-a919-4df125c08f0e","FILESYSTEM"),c=a.massageClassName("gafb65c33-7df8-4b01-a919-4df125c08f0e",
"SERVERPROTOCOL");this.onRegister=function(c,d){a.bindEvent(k,b,"."+f,"FileSystemProtocol","onClick");e()};this.onLoad=function(){e()};this.FileSystemProtocol_onClick=function(a){e()}};
