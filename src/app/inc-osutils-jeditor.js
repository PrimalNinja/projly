/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function jEditor(objOS_a, strFormID_a, strElementClass_a, objOptions_a)
{
	var os = objOS_a;
	var m_objThis = this;
	var m_strEditorElementID = 'editor-' + getGUID();

	var m_objEditor = null;
	var m_blnDirty = false;	// note this dirty flag is just to indicate when internal processes should occur

	var m_strDefaultContent = objOptions_a.defaultContent;
	var m_cbOnClear;
	var m_cbOnDirty;
	var m_cbOnFileBrowse;
	var m_cbOnLoad;
	var m_cbOnReset;
	var m_cbOnRun;
	var m_cbOnSave;
	var m_cbOnImagePicker;
	var m_strToolbar = 'run | load | save | reset | clear | searchreplace | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen';

	if (objOptions_a.toolbar !== undefined)
	{
		m_strToolbar = objOptions_a.toolbar;
	}
	if (objOptions_a.cbOnClear !== undefined)
	{
		m_cbOnClear = objOptions_a.cbOnClear;
	}
	if (objOptions_a.cbOnDirty !== undefined)
	{
		m_cbOnDirty = objOptions_a.cbOnDirty;
	}
	if (objOptions_a.cbOnFileBrowse !== undefined)
	{
		m_cbOnFileBrowse = objOptions_a.cbOnFileBrowse;
	}
	if (objOptions_a.cbOnLoad !== undefined)
	{
		m_cbOnLoad = objOptions_a.cbOnLoad;
	}
	if (objOptions_a.cbOnReset !== undefined)
	{
		m_cbOnReset = objOptions_a.cbOnReset;
	}
	if (objOptions_a.cbOnRun !== undefined)
	{
		m_cbOnRun = objOptions_a.cbOnRun;
	}
	if (objOptions_a.cbOnSave !== undefined)
	{
		m_cbOnSave = objOptions_a.cbOnSave;
	}
	if (objOptions_a.cbOnImagePicker !== undefined)
	{
		m_cbOnImagePicker = objOptions_a.cbOnImagePicker;
	}

	$(strElementClass_a, strFormID_a).attr('id', m_strEditorElementID);

	function initialise()
	{
		os.after(500, function ()
		{
			if (m_strDefaultContent.length > 0)
			{
				m_objThis.setContent(m_strDefaultContent);
			}
		}
		);
	}

	// instantiation
	$('#' + m_strEditorElementID).tinymce(
	{
		setup : function (objEditor_a)
		{

			m_objEditor = objEditor_a;

			// events
			objEditor_a.on('change', function (objArgs_a)
			{
				m_blnDirty = true;
				if ($.isFunction(m_cbOnDirty))
				{
					m_cbOnDirty(m_blnDirty);
				}
			}
			);

			objEditor_a.on('ExecCommand', function (objArgs_a)
			{
				if (objArgs_a.command == 'mceNewDocument')
				{
					initialise();
				}
			}
			);

			objEditor_a.on('keyup', function (objArgs_a)
			{
				m_blnDirty = true;
				if ($.isFunction(m_cbOnDirty))
				{
					m_cbOnDirty(m_blnDirty);
				}
			}
			);

			if (m_cbOnLoad !== undefined)
			{
				objEditor_a.addButton('load',
				{
					title : 'Load',
					text : 'Load',
					onclick : function ()
					{
						if ($.isFunction(m_cbOnLoad))
						{
							if (m_cbOnLoad(m_objThis.getContent(true), m_objThis.getContent(false)))
							{
								m_blnDirty = false;
								if ($.isFunction(m_cbOnDirty))
								{
									m_cbOnDirty(m_blnDirty);
								}
							}
						}
					}
				}
				);
			}

			if (m_cbOnReset !== undefined)
			{
				objEditor_a.addButton('reset',
				{
					title : 'Reset',
					text : 'Reset',
					onclick : function ()
					{
						if ($.isFunction(m_cbOnReset))
						{
							if (m_cbOnReset(m_objThis.getContent(true), m_objThis.getContent(false)))
							{
								m_blnDirty = false;
								if ($.isFunction(m_cbOnDirty))
								{
									m_cbOnDirty(m_blnDirty);
								}
							}
						}
					}
				}
				);
			}

			if (m_cbOnClear !== undefined)
			{
				objEditor_a.addButton('clear',
				{
					title : 'Clear',
					text : 'Clear',
					onclick : function ()
					{
						if ($.isFunction(m_cbOnClear))
						{
							if (m_cbOnClear(m_objThis.getContent(true), m_objThis.getContent(false)))
							{
								m_blnDirty = false;
								if ($.isFunction(m_cbOnDirty))
								{
									m_cbOnDirty(m_blnDirty);
								}
							}
						}
					}
				}
				);
			}

			if (m_cbOnRun !== undefined)
			{
				objEditor_a.addButton('run',
				{
					title : 'Run',
					text : 'Run',
					onclick : function ()
					{
						if ($.isFunction(m_cbOnRun))
						{
							m_cbOnRun(m_objThis.getContent());
						}
					}
				}
				);
			}

			if (m_cbOnImagePicker !== undefined)
			{
				objEditor_a.addButton('imagepicker',
				{
					title : 'Image Picker',
					text : 'Image Picker',
					onclick : function ()
					{
						if ($.isFunction(m_cbOnImagePicker))
						{
							m_cbOnImagePicker(objEditor_a);
						}
					}
				}
				);
			}

			initialise();
		},

		force_br_newlines : true,

		force_p_newlines : false,

		forced_root_block : '',

		menu :
		{
			file :
			{
				title : 'File',
				items : 'newdocument | print preview'
			},
			edit :
			{
				title : 'Edit',
				items : 'undo redo | cut copy paste pastetext | selectall | searchreplace'
			},
			insert :
			{
				title : 'Insert',
				items : 'image media link | charmap hr anchor pagebreak insertdatetime nonbreaking'
			},
			view :
			{
				title : 'View',
				items : 'visualchars visualblocks visualaid | fullscreen'
			},
			format :
			{
				title : 'Format',
				items : 'bold italic underline strikethrough superscript subscript | formats | removeformat'
			},
			table :
			{
				title : 'Table',
				items : 'inserttable tableprops deletetable cell row column'
			},
			tools :
			{
				title : 'Tools',
				items : 'code'
			}
		},

		nonbreaking_force_tab : true,

		plugins : [
			'advlist autolink lists link image charmap hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking table contextmenu directionality',
			'paste print preview save textcolor'
		],

		resize : false,

		toolbar : m_strToolbar,

		file_browser_callback : function (strFieldName_a, strURL_a, strType_a, objWindow_a)
		{
			if ($.isFunction(m_cbOnFileBrowse))
			{
				m_cbOnFileBrowse(objWindow_a, strFieldName_a, strType_a);
			}
		},

		save_onsavecallback : function ()
		{
			if ($.isFunction(m_cbOnSave))
			{
				if (m_cbOnSave(m_objThis.getContent(true), m_objThis.getContent(false)))
				{
					m_blnDirty = false;
					if ($.isFunction(m_cbOnDirty))
					{
						m_cbOnDirty(m_blnDirty);
					}
				}
			}
		}
	}
	);

	// publics
	this.getContent = function (blnHTML_a)
	{
		var strResult = '';

		try
		{
			if (blnHTML_a)
			{
				strResult = tinymce.EditorManager.get(m_strEditorElementID).getContent(
					{
						format : 'html'
					}
					).toString();
			}
			else
			{
				strResult = tinymce.EditorManager.get(m_strEditorElementID).getContent(
					{
						format : 'text'
					}
					).toString();
			}
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
		try
		{
			//alert('in inc-class-jeditor:' + strContent_a);
			//tinymce.EditorManager.get(m_strEditorElementID).execCommand('mceSetContent', false, encodeHTML('hello'));
			tinymce.EditorManager.get(m_strEditorElementID).setContent(strContent_a);
			m_blnDirty = false;
			if ($.isFunction(m_cbOnDirty))
			{
				m_cbOnDirty(m_blnDirty);
			}
		}
		catch (err)
		{
			doNothing();
		}
	};
}
