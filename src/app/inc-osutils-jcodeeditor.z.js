// Projly v20260922 =============================================================================
// Copyright (C) 2024 Projly Pty Ltd. Released under the MIT License.
//
// ==============================================================================================
// AWAF v20260922 ===============================================================================
// Copyright (C) 2012-2024 Mitsukibo Pty Ltd, Julian Cassin & Francis Weston. Released under the MIT License.

/*jsl:ignore*/
function jCodeEditor(b,d,e,f){b="codeeditor-"+getGUID();var a=null;$(e,d).attr("id",b);a=ace.edit(b);a.setTheme("ace/theme/twilight");a.session.setMode("ace/mode/javascript");a.renderer.setScrollMargin(10,10);a.setOptions({autoScrollEditorIntoView:!0});a.resize();this.getContent=function(){var c="";try{c=a.getValue()}catch(b){c=""}return c};this.isDirty=function(){return!1};this.setContent=function(c){try{a.setValue(c)}catch(b){doNothing()}}};
