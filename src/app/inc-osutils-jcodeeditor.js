/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function jCodeEditor(objOS_a, strFormID_a, strElementClass_a, objOptions_a)
{
	//alert('jCodeEditor');

	var os = objOS_a;
	var m_objThis = this;
	var m_strEditorElementID = 'codeeditor-' + getGUID();

	var m_objEditor = null;
	var m_blnDirty = false;	// note this dirty flag is just to indicate when internal processes should occur

	var m_strDefaultContent = objOptions_a.defaultContent;

	var m_editor = null;




	$(strElementClass_a, strFormID_a).attr('id', m_strEditorElementID);

	function initialise()
	{
		//alert('initialise');
		m_objThis.setContent(m_strDefaultContent);
	}

	m_editor = ace.edit(m_strEditorElementID);
	m_editor.setTheme("ace/theme/twilight");
	m_editor.session.setMode("ace/mode/javascript");
	m_editor.renderer.setScrollMargin(10, 10);
	m_editor.setOptions({
    // "scrollPastEnd": 0.8,
    autoScrollEditorIntoView: true
	});
	m_editor.resize();
	//m_editor.setValue('x99x99x99');

// publics
	this.getContent = function ()
	{
		//alert('getContent');

		var strResult = '';

		try
		{
			strResult = m_editor.getValue();
		}
		catch (err)
		{
			strResult = '';
		}

		return strResult;
	};

	this.isDirty = function ()
	{
		return m_blnDirty;
	};

	this.setContent = function (strContent_a)
	{
		//alert('SET setContent');
		try
		{
			m_editor.setValue(strContent_a);
		}
		catch (err)
		{
			doNothing();
		}
	};
}
