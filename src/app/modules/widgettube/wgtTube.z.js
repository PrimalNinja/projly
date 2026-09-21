// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function widgettube_wgtTube(c,f,d){function e(){b=!b;a()}function a(){var a="",a=b?"Play":"Mute";$("#ge-widgettube-mute").html(a)}var b=!0;this.Form_allowMultipleInstances=function(){return!1};this.Form_onPermissionCheck=function(){return!0};this.Form_onLoad=function(){c.element(d.target).tubular({videoId:LANDINGPAGE_VIDEOID,wrapperZIndex:3,mute:b});a();"TRUE"===LANDINGPAGE_AUDIO&&($("#ge-widgettube-container").show(),$(".tubular-mute").bind("click",e))}};
