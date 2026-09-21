/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function jDock(os_a, objOptions_a)
{
	var MOREBUTTONSIZE = 82;	// to cater for the more button if required
		
	var os = os_a;
	var m_objThis = this;

	var m_blnHorizontal = false;
	var m_blnInRender = false;
    var m_intElementHeight = 0;
	var m_intElementWidth = 0;
	var m_intTotalRenderWidth = 0;
	var m_strElementID = '';
	var m_strElementClass = '';
	var m_strFormID = '';
	var m_strLocation = '';
	var m_arrDirtyTiles = [];

	var m_blnAddToRecent = objOptions_a.addtorecent;
	if (m_blnAddToRecent === undefined)
	{
		m_blnAddToRecent = true;
	}

	var m_blnAlignRight = objOptions_a.alignright;
	if (m_blnAlignRight === undefined)
	{
		m_blnAlignRight = false;
	}

	var m_cbOnRender = objOptions_a.cbOnRender;

	var m_strExpandedID = '';

	var m_arrMap = objOptions_a.map;

	var m_strMode = objOptions_a.mode;
	if (m_strMode == undefined)
	{
		m_strMode = "buttongrid";
	}

	var m_arrRecent = objOptions_a.recent;
	if (m_arrRecent === undefined)
	{
		m_arrRecent = [];
	}
	var m_blnRecent = false;

	var m_blnRenderBlanks = objOptions_a.renderblanks;
	if (m_blnRenderBlanks === undefined)
	{
		m_blnRenderBlanks = true;
	}

	var m_arrTiles = objOptions_a.tiles;
	if (m_arrTiles === undefined)
	{
		m_arrTiles = [];
	}
    
	var m_blnCloseOnSelect = objOptions_a.closeonselect;
    if( m_blnCloseOnSelect === undefined) { 
        m_blnCloseOnSelect = false; 
    }

	var m_blnAlwaysHideMore = objOptions_a.alwayshidemorebutton;
    if( m_blnAlwaysHideMore === undefined) { 
        m_blnAlwaysHideMore = false; 
    }

    //var m_intAlwaysVisibleButtonCount = objOptions_a.alwaysvisiblebuttoncount;
    //if(m_intAlwaysVisibleButtonCount === undefined) { 
       //m_intAlwaysVisibleButtonCount = m_arrTiles.count; 
    //}

	function addToRecent(objTile_a)
	{
		var intI;
		var intJ;
		var objTile;
		var objTileFirst;
		var objTileTo;
		var objTileFrom;
		var strClickedCaption;
		var strFirstTileCaption;
		var strTileCaption;
		var strTileFromCaption;

		if (m_arrRecent.length > 0)
		{
			objTileFirst = getTile(m_arrRecent[0]);

			if ($.isFunction(objTileFirst.caption))
			{
				strFirstTileCaption = objTileFirst.caption();
			}
			else
			{
				strFirstTileCaption = objTileFirst.caption;
			}

			if ($.isFunction(objTile_a.caption))
			{
				strTileCaption = objTile_a.caption();
			}
			else
			{
				strTileCaption = objTile_a.caption;
			}

			if (strFirstTileCaption != strTileCaption)
			{
				var objClicked = createTile();
				copyTile(objTile_a, objClicked);

				// remove any existing instances of the recent tile
				for (intI = 0; intI < m_arrRecent.length; intI++)
				{
					objTile = getTile(m_arrRecent[intI]);

					if ($.isFunction(objTile.caption))
					{
						strTileCaption = objTile.caption();
					}
					else
					{
						strTileCaption = objTile.caption;
					}

					if ($.isFunction(objClicked.caption))
					{
						strClickedCaption = objClicked.caption();
					}
					else
					{
						strClickedCaption = objClicked.caption;
					}

					if (strTileCaption == strClickedCaption)
					{
						// move remaining tiles down 1
						for (intJ = intI; intJ < (m_arrRecent.length - 1); intJ++)
						{
							objTileFrom = getTile(m_arrRecent[intJ + 1]);
							objTileTo = getTile(m_arrRecent[intJ]);
							copyTile(objTileFrom, objTileTo);
						}

						// clear the last tile
						objTile = getTile(m_arrRecent[m_arrRecent.length - 1]);
						clearTile(objTile);
					}
				}

				// move all existing tiles up 1
				for (intI = (m_arrRecent.length - 2); intI >= 0; intI--)
				{
					objTileFrom = getTile(m_arrRecent[intI]);

					if ($.isFunction(objTileFrom.caption))
					{
						strTileFromCaption = objTileFrom.caption();
					}
					else
					{
						strTileFromCaption = objTileFrom.caption;
					}

					if (strTileFromCaption.length > 0)
					{
						objTileTo = getTile(m_arrRecent[intI + 1]);
						copyTile(objTileFrom, objTileTo);
						objTileTo.significant = true;
					}
				}

				// create the new tile
				copyTile(objClicked, objTileFirst);
				objTileFirst.significant = true;

				if (m_blnRecent)
				{
					m_arrDirtyTiles = m_arrRecent;
				}
				m_blnRecent = true;
				refresh();
			}
		}
	}

	// if no tiles are dirty, then all are dirty
	function checkDirty(objTile_a)
	{
		var blnResult = false;

		if (m_arrDirtyTiles.length === 0)
		{
			blnResult = true;
		}
		else
		{
			processArray(m_arrDirtyTiles, function (strTile_a)
			{
				if (objTile_a.id == strTile_a)
				{
					blnResult = true;
					return true;
				}
			}
			);
		}

		return blnResult;
	}

	function checkTilePermission(objTile_a)
	{
		//return true; // JULIAN TO REMOVE
		var blnResult = false;

		// check permissions and if have render the tile
		if ($.isFunction(objTile_a.permissions))
		{
			blnResult = objTile_a.permissions();
		}
		else if (objTile_a.permissions.length > 0)
		{
			blnResult = hasPermission(objTile_a.permissions);
		}
		else
		{
			// doesn't require permissions
			blnResult = true;
		}

		return blnResult;
	}

	// if some tiles are dirty, just cleaer them
	function clearDock()
	{
		if (m_arrDirtyTiles.length > 0)
		{
			processArray(m_arrDirtyTiles, function (strTile_a)
			{
				$('.' + strTile_a + 'DIV', '#' + m_strElementID).remove();
			}
			);
		}
		else
		{
			$('#' + m_strElementID).html('');
		}
	}

	function clearTile(objTile_a)
	{
		objTile_a.caption = '';
		objTile_a.classes = '';
		objTile_a.permissions = [];
		objTile_a.action = '';
		objTile_a.actionData = null;
		objTile_a.actionData2 = null;
		objTile_a.actionData3 = null;
		objTile_a.actionShifted = '';
		objTile_a.actionDataShifted = null;
		objTile_a.actionDataShifted2 = null;
		objTile_a.actionDataShifted3 = null;
		objTile_a.significant = false;
		objTile_a.tip = '';
		objTile_a.renderwidth = 0;
		objTile_a.reversetip = false;
		objTile_a.tempclasses = '';
		objTile_a.toptip = false;
		objTile_a.type = 'blank';
	}

	function copyTile(objSource_a, objDestination_a)
	{
		var strClasses = '';
		if ($.isFunction(objSource_a.classes))
		{
			strClasses = objSource_a.classes();
		}
		else
		{
			strClasses = objSource_a.classes;
		}

		objDestination_a.caption = objSource_a.caption;
		objDestination_a.classes = strClasses;
		objDestination_a.permissions = objSource_a.permissions;
		objDestination_a.action = objSource_a.action;
		objDestination_a.actionData = objSource_a.actionData;
		objDestination_a.actionData2 = objSource_a.actionData2;
		objDestination_a.actionData3 = objSource_a.actionData3;
		objDestination_a.actionShifted = objSource_a.actionShifted;
		objDestination_a.actionDataShifted = objSource_a.actionDataShifted;
		objDestination_a.actionDataShifted2 = objSource_a.actionDataShifted2;
		objDestination_a.actionDataShifted3 = objSource_a.actionDataShifted3;
		objDestination_a.significant = objSource_a.significant;
		objDestination_a.tip = objSource_a.tip;
		objDestination_a.renderwidth = objSource_a.renderwidth;
		objDestination_a.reversetip = objSource_a.reversetip;
		objDestination_a.tempclasses = objSource_a.tempclasses;
		objDestination_a.toptip = objSource_a.toptip;
		objDestination_a.type = objSource_a.type;
	}

	function createTile()
	{
		var objResult =
		{
			id : '',
			caption : '',
			classes : '',
			permissions : [],
			action : '',
			actionData : null,
			actionData2 : null,
			actionData3 : null,
			actionShifted : '',
			actionDataShifted : null,
			actionDataShifted2 : null,
			actionDataShifted3 : null,
			significant : false,
			tip : '',
			renderwidth : 0,
			reversetip: false,
			tempclasses: '',
			toptip: false,
			type : 'blank'
		};

		return objResult;
	}

	function doCallback(intRows_a)
	{
		if ($.isFunction(m_cbOnRender))
		{
			var intHeight = $(m_strElementClass, m_strFormID).height();
			if (m_strMode === 'buttongrid')
			{
				intHeight = (intRows_a - 1) * 62.5;
			}
			m_cbOnRender(intHeight);
		}
	}

	function getLocationInternal(strLocation_a)
	{
		var objResult;

		processArray(m_arrMap, function (objLocation_a)
		{
			if (objLocation_a.location == strLocation_a)
			{
				objResult = objLocation_a;
				return true;
			}
		}
		);

		return objResult;
	}

	function getTile(strTileID_a)
	{
		var objResult = null;

		processArray(m_arrTiles, function (objTile_a)
		{
			if (objTile_a.id == strTileID_a)
			{
				objResult = objTile_a;

				var strClasses = '';
				if ($.isFunction(objTile_a.classes))
				{
					strClasses = objTile_a.classes();
				}
				else
				{
					strClasses = objTile_a.classes;
				}

				objResult.classes = strClasses;
			}
		}
		);

		return objResult;
	}

	// TODO in future, register the dock with the OS to be resized and use the OS's resize event
	function initialiseResizeEvent(strLocation_a)
	{
		m_strLocation = strLocation_a;
		
		//window.addEventListener("resize", onResize, false);
		if (m_strMode === "verticalmenu")
		{
			doNothing();
		}
		else
		{
			os.bindEvent(m_objThis, m_strFormID, '.gb-form', 'Dock', 'onResize');
		}
		m_objThis.Dock_onResize();
	}
	
	this.Dock_onResize = function ()
	{
		render2(m_strLocation, 0); // first time to calculate the space required
		render2(m_strLocation, m_intElementWidth);	// second time to render within the space required
	};
	
	function refresh()
	{
		m_objThis.setLocation(m_strLocation);
		m_arrDirtyTiles = [];
	}

	function renderTitle(strTitle_a)
	{
		//system_console("<br><br>" + strTitle_a + "<br>");
		doNothing();
	}

	function renderTile(intTile_a, intCol_a, intRow_a, objTile_a, strRowHeadID_a)
	{
		if (checkDirty(objTile_a))
		{
			var blnRenderTile = true;
			var blnReverseTip;
			var blnTopTip;
			var lngLeft;
			var lngTop;
			var strButtonKeyText;
			var strCaption;
			var strCaptionClasses = "";
			var strClasses;
			var strCollapsedClass = "";
			var strElementClass;
			var strElementClassDIV;
			var strFunction;
			var strIconClasses = "";
			var strStyle;
			var strTileID = objTile_a.id;
			var strTip;
			var strURL;
			
			var intWidth = $('#' + m_strElementID).width();

			if (strRowHeadID_a.length > 0)
			{
				strCollapsedClass += ' ' + strRowHeadID_a + 'ROWHEAD';
			}

			if ($.isFunction(objTile_a.caption))
			{
				strCaption = objTile_a.caption();
			}
			else
			{
				strCaption = objTile_a.caption;
			}

			strClasses = '';
			if ((objTile_a.tempclasses == undefined) || (objTile_a.tempclasses.length = 0))
			{
				if ($.isFunction(objTile_a.classes))
				{
					strClasses = objTile_a.classes();
				}
				else
				{
					strClasses = objTile_a.classes;
				}
				
				objTile_a.tempclasses = strClasses;
			}
			else
			{
				strClasses = objTile_a.tempclasses;
			}

			if (strClasses === undefined)
			{
				strClasses = 'gs-darkgrey-background-colour';
			}

			if (objTile_a.captionclasses !== undefined)
			{
				strCaptionClasses = objTile_a.captionclasses;
			}
			else
			{
				strCaptionClasses = 'gs-buttontext';
			}
			
			strStyle = objTile_a.style;
			if (strStyle === undefined) 
			{ 
			   strStyle = ''; 
			}

			if (objTile_a.iconClasses !== undefined)
			{
				strIconClasses = 'gs-buttonicon ' + objTile_a.iconClasses;
			}

			strURL = objTile_a.url;
			if (strURL === undefined)
			{
				strURL = '';
			}

			strTip = objTile_a.tip;
			if (strTip === undefined)
			{
				strTip = '';
			}

			blnReverseTip = objTile_a.reversetip;
			if (strTip === undefined)
			{
				blnReverseTip = false;
			}

			blnTopTip = objTile_a.toptip;
			if (strTip === undefined)
			{
				blnTopTip = false;
			}

			strButtonKeyText = objTile_a.buttonkeytext;
			if (strButtonKeyText === undefined)
			{
				strButtonKeyText = '';
			}

			if ((strRowHeadID_a !== '-') && (strTileID === '-'))
			{
				// collapsable vertical menu separator
				strTileID = 'blank';
				strCaption = '-';
				strCollapsedClass += " gb-collapsable hidden";
			}
			else if (strRowHeadID_a == strTileID)
			{
				// if we are the rowhead, we are not collapsable
				strCollapsedClass += " gb-noncollapsable";
			}
			else if (strRowHeadID_a.length > 0)
			{
				// collapsable vertical menu items
				if (objTile_a.noncollapsable)
				{
					strCollapsedClass += " gb-noncollapsable";
				}
				else
				{
					strCollapsedClass += " gb-collapsable hidden";
				}
			}
			else
			{
				// needed for non-vertical menus
				doNothing();
			}

			var strButtonType = objTile_a.type;
			if (m_strMode === "verticalmenu")
			{
				// force this style for vertical menus
				strButtonType = 'menuitem';
				m_blnHorizontal = false;
			}
			else if (m_strMode === "horizontalmenu")
			{
				// force this style for vertical menus
				strButtonType = 'menuitem';
				m_blnHorizontal = true;
			}
                        else if (m_strMode === "verticallinks")
			{
				// force this style for vertical menus
				strButtonType = 'linkitem';
				m_blnHorizontal = false;
			}
			else if (m_strMode === "horizontallinks")
			{
				// force this style for vertical menus
				strButtonType = 'linkitem';
				m_blnHorizontal = true;
			}


			if (strButtonType === 'button')
			{
				// type 'button' is only used in buttongrid mode

				if (m_blnAlignRight)
				{
					lngLeft = intWidth - (intCol_a * 125);
				}
				else
				{
					lngLeft = (intCol_a - 1) * 125;
				}
				lngTop = (intRow_a - 1) * 62;

				strFunction = '';
				strElementClass = strTileID;

				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}

				strElementClassDIV = strElementClass + 'DIV';
				strFunction = '<div class="' + strElementClassDIV + ' gs-cell-xxx gs-cell-125x62-xxx" style="top:' + lngTop + 'px; left:' + lngLeft + 'px">';
				if (strURL.length > 0)
				{
					strFunction += '<a href="' + strURL + '">';
				}

				strFunction += '<div class="' + strElementClass + ' gb-button gs-cell-content-xxx gs-cellcontent-125x62-xxx ' + strClasses + '">';

				if (strIconClasses.length > 0)
				{
					strFunction += '<i class="' + strIconClasses + '">' + strCaption + '</i>';
				}

				strFunction += '<div class="' + strCaptionClasses + '">' + strCaption + '</div>';

				if (strButtonKeyText.length > 0)
				{
					strFunction += '<div class="gs-buttonkeytext">' + strButtonKeyText + '</div>';
				}
				strFunction += '</div>';

				if (strURL.length > 0)
				{
					strFunction += '</a>';
				}
				strFunction += '</div>';

			}
			else if (strButtonType === 'menuitem')
			{
				strFunction = '';
				strElementClass = strTileID;

				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}

				strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
				if (m_strMode === "verticalmenu")
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-menuitem-container-vertical">';
				}
				else
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-menuitem-container">';
				}
				if (strURL.length > 0)
				{
					strFunction += '<a href="' + strURL + '">';
				}

				if (strCaption === '-')
				{
					strFunction += '<div class="' + strElementClass + ' gb-button ' + strClasses + '"><hr style="width: 99%; color: #cccccc; height: 1px; background-color:#cccccc; margin:5px; ' + strStyle + '" /></div>';
				}
				else
				{
					if (m_blnHorizontal)
					{
						strFunction += '<div class="' + strElementClass + ' gb-button gs-button gs-cell-content-xxx ' + strClasses + '" style="' + strStyle + '">';
					}
					else
					{
						strFunction += '<div class="' + strElementClass + ' gb-button gs-button gs-cell-content-xxx ' + strClasses + '" style="' + strStyle + '">';
					}

					if (strIconClasses.length > 0)
					{
						strFunction += '<i class="' + strIconClasses + '"></i>';
					}

					strFunction += '<div class="' + strCaptionClasses + '">' + strCaption + '</div>';
					if (strButtonKeyText.length > 0)
					{
						strFunction += '<div class="gs-buttonkeytext">' + strButtonKeyText + '</div>';
					}
					strFunction += '</div>';
				}

				if (strURL.length > 0)
				{
					strFunction += '</a>';
				}
				//strFunction += '</div>';
				strFunction += '</div>';

			}
                        else if (strButtonType === 'linkitem')
			{
				strFunction = '';
				strElementClass = strTileID;

				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}

				strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
				if (m_strMode === "verticallinks")
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-menuitem-container-vertical">';
				}
				else
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-menuitem-container">';
				}
				if (strURL.length > 0)
				{
					strFunction += '<a href="' + strURL + '">';
				}

				if (strCaption === '-')
				{
					strFunction += '<div class="' + strElementClass + ' gb-linksbutton ' + strClasses + '"><hr style="width: 99%; color: #cccccc; height: 1px; background-color:#cccccc; margin:5px; ' + strStyle + '" /></div>';
				}
				else
				{
					if (m_blnHorizontal)
					{
						strFunction += '<div class="' + strElementClass + ' gb-button gs-linksbutton gs-cell-content-xxx ' + strClasses + '" style="' + strStyle + '">';
					}
					else
					{
						strFunction += '<div class="' + strElementClass + ' gb-button gs-linksbutton gs-cell-content-xxx ' + strClasses + '" style="' + strStyle + '">';
					}

					if (strIconClasses.length > 0)
					{
						strFunction += '<i class="' + strIconClasses + '"></i>';
					}

					strFunction += '<div class="' + strCaptionClasses + '">' + strCaption + '</div>';
					if (strButtonKeyText.length > 0)
					{
						strFunction += '<div class="gs-buttonkeytext">' + strButtonKeyText + '</div>';
					}
					strFunction += '</div>';
				}

				if (strURL.length > 0)
				{
					strFunction += '</a>';
				}
				//strFunction += '</div>';
				strFunction += '</div>';

			}
			else if (strButtonType === 'hamburgermenu')
			{
				strFunction = '';
				strElementClass = strTileID;

				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}

				strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
				strFunction = '<div class="' + strElementClassDIV + ' gs-burger-container">';
				if (strURL.length > 0)
				{
					strFunction += '<a href="' + strURL + '">';
				}

				strFunction += '<div class="' + strElementClass + ' gb-button gs-button gs-cell-content-xxx ' + strClasses + '">';
				strFunction += '<div class="' + strCaptionClasses + '">' + strCaption + '</div>';
				if (strButtonKeyText.length > 0)
				{
					strFunction += '<div class="gs-buttonkeytext">' + strButtonKeyText + '</div>';
				}
				strFunction += '</div>';

				if (strURL.length > 0)
				{
					strFunction += '</a>';
				}
				//strFunction += '</div>';
				strFunction += '</div>';

			}
			else if (strButtonType === 'icon')
			{
				strFunction = '';
				strElementClass = strTileID;

				strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
				if (strStyle.length > 0)
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-cell-62x62-xxx" style="' + strStyle + '">';
				}
				else
				{
					strFunction = '<div class="' + strElementClassDIV + ' gs-cell-62x62-xxx" style="left:10px; top:10px;">';
				}
				strFunction += '<div class="' + strElementClass + ' gs-cell-content-xxx gs-cellcontent-62x62-xxx ' + strClasses + '"></div>';
				strFunction += '</div>';

			}
			else if (strButtonType === 'toolbarbutton')
			{
				strFunction = '';
				strElementClass = strTileID;
                
                if(strClasses === '' || strClasses === undefined) { 
                   strClasses = 'btn-primary'; 
                }
                    
                if(strClasses.indexOf('gs-green-background-colour') >= 0) { 
                   strClasses = strClasses.replace('gs-green-background-colour', 'btn-success'); 
                } 
                
                if(strClasses.indexOf('gs-red-background-colour') >= 0) { 
                   strClasses = strClasses.replace('gs-red-background-colour', 'btn-danger'); 
                }   
                
                
                if(strClasses.indexOf('gb-cell-disabled-xxx') >= 0) { 
                    strClasses = strClasses.replace('gb-cell-disabled-xxx', 'btn-disabled'); 
                }
                
                if(strClasses.indexOf('gb-cell-disabled') >= 0) { 
                    strClasses = strClasses.replace('gb-cell-disabled', 'btn-disabled'); 
                }
                
                if(strClasses.indexOf('btn-default') === -1 && strClasses.indexOf('btn-primary') === -1 && strClasses.indexOf('btn-success') === -1 && strClasses.indexOf('btn-danger') === -1 && strClasses.indexOf('btn-disabled') === -1 ) { 
                   strClasses += ' btn-primary';  
                }
                
				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}

				if (blnRenderTile)
				{
					strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
					strFunction = '<span class="' + strElementClass + ' ' + strElementClassDIV + ' btn ' + strClasses + ' gb-button" style="' + strStyle + '">';
					strFunction += strCaption;
					strFunction += '</span>';
				}
			}
			else if (strButtonType === 'toolbardevicebutton')
			{
				strFunction = '';
				strElementClass = strTileID;
                
                if(strClasses === '' || strClasses === undefined) { 
                   strClasses = 'btn-primary btn-toolbar-device-button'; 
                }
                    
                if(strClasses.indexOf('gs-green-background-colour') >= 0) { 
                   strClasses = strClasses.replace('gs-green-background-colour', 'btn-success'); 
                } 
                
                if(strClasses.indexOf('gs-red-background-colour') >= 0) { 
                   strClasses = strClasses.replace('gs-red-background-colour', 'btn-danger'); 
                }   
                
                
                if(strClasses.indexOf('gb-cell-disabled-xxx') >= 0) { 
                    strClasses = strClasses.replace('gb-cell-disabled-xxx', 'btn-disabled'); 
                }
                
                if(strClasses.indexOf('gb-cell-disabled') >= 0) { 
                    strClasses = strClasses.replace('gb-cell-disabled', 'btn-disabled'); 
                }
                
                if(strClasses.indexOf('btn-default') === -1 && strClasses.indexOf('btn-primary') === -1 && strClasses.indexOf('btn-success') === -1 && strClasses.indexOf('btn-danger') === -1 && strClasses.indexOf('btn-disabled') === -1 ) { 
                   strClasses += ' btn-primary';  
                }
                
				if (os.hasCapability('symbols'))
				{
					strCaption = str_replace(strCaption, '&gt;', '&#9658;');
				}
                
				if (blnRenderTile)
				{
					strElementClassDIV = strElementClass + 'DIV' + strCollapsedClass;
					strFunction += '<div class="btn-group">';
					strFunction += '<span class="' + strElementClass + ' ' + strElementClassDIV + ' btn ' + strClasses + ' gb-button dropdown-toggle" data-toggle="dropdown">';
					strFunction += strCaption + '&nbsp;<span class="caret"></span>';
					strFunction += '</span>';
					strFunction += '<ul class="gs-button-panel-dropdown-menu dropdown-menu">';
					
					processArray(objTile_a.actionDataList, function(objData_a)
					{
						strFunction += '<li><a class="gs-dropdown-tile btn btn-primary ' + objData_a.id + '">' + objData_a.caption + '</a></li>';
					});

					strFunction += '</ul>';					
					strFunction += '</div>';
				}
			}

			if (blnRenderTile)
			{
				$('#' + m_strElementID).append(strFunction);
				
				if (($.isFunction(objTile_a.action)) || (objTile_a.action.length > 0))
				{
					os.unbindEvents(m_strFormID, strElementClass);
					os.bindEvent(m_objThis, m_strFormID, '.' + strElementClass, 'Tile', 'onClick', objTile_a);
					os.bindEvent(m_objThis, m_strFormID, '.' + strElementClass, 'Tile', 'onEnterKey', objTile_a);

					if (blnTopTip)
					{
						if (blnReverseTip)
						{
							if (intCol_a <= 2)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom right', 'top center', 2, strTip);
							}
							else if (intCol_a <= 6)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom center', 'top center', 2, strTip);
							}
							else
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom left', 'top center', 2, strTip);
							}
						}
						else
						{
							if (intCol_a <= 2)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom left', 'top center', 2, strTip);
							}
							else if (intCol_a <= 6)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom center', 'top center', 2, strTip);
							}
							else
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'bottom right', 'top center', 2, strTip);
							}
						}
					}
					else
					{
						if (blnReverseTip)
						{
							if (intCol_a <= 2)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top right', 'bottom center', 2, strTip);
							}
							else if (intCol_a <= 6)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top center', 'bottom center', 2, strTip);
							}
							else
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top left', 'bottom center', 2, strTip);
							}
						}
						else
						{
							if (intCol_a <= 2)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top left', 'bottom center', 2, strTip);
							}
							else if (intCol_a <= 6)
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top center', 'bottom center', 2, strTip);
							}
							else
							{
								os.showTip(m_strFormID, '.' + strElementClass, 'top right', 'bottom center', 2, strTip);
							}
						}
					}

					if (os.element(m_strFormID, '.' + strElementClass).hasClass('gb-cell-disabled-xxx'))
					{
						//os.unbindEvents(m_strFormID, strElementClass);
						os.element(m_strFormID, '.' + strElementClass).qtip('hide', true);
						os.element(m_strFormID, '.' + strElementClass).qtip('disable', true);
					}
				}
				else
				{
					os.bindEvent(m_objThis, m_strFormID, '.' + strElementClass, 'Tile', 'onClick', objTile_a);
					os.bindEvent(m_objThis, m_strFormID, '.' + strElementClass, 'Tile', 'onEnterKey', objTile_a);
				}

				if (strButtonType === 'toolbardevicebutton')
				{
					processArray(objTile_a.actionDataList, function(objData_a)
					{
						os.unbindEvents(m_strFormID, objData_a.id);
						os.bindEvent(m_objThis, m_strFormID, '.' + objData_a.id, 'Tile', 'onClick', objData_a);
					});
				}
			}
		}
	}

	// render horizontally
	function renderLayoutH(arrLayout_a, intRenderLimit_a)
	{
		if (!m_blnInRender)
		{
			m_blnInRender = true;
			
			var blnSignificant;
			var intRow = 1;
			var intTile = 1;
			var objTile;
			var strLastTileID = "";
		
			var intLongestTileRenderWidth = MOREBUTTONSIZE;
			var intLeftover = intRenderLimit_a;
			m_intTotalRenderWidth = 0;

			if (intLeftover === undefined)
			{
				intLeftover = 0;
			}
			
			processArray(arrLayout_a, function (arrRow_a)
			{
				// check row for significance
				var intSignificant = 0;
				processArray(arrRow_a, function (strTileID_a)
				{
					objTile = getTile(strTileID_a);
					if (objTile !== null)
					{
						blnSignificant = objTile.significant;
						if (blnSignificant === undefined)
						{
							blnSignificant = true;
						}

						if (blnSignificant)
						{
							if (checkTilePermission(objTile))
							{
								intSignificant++;
							}
						}
					}
					else
					{
						doException('renderVerticalMenu', 'missing tile: ' + strTileID_a);
					}
				}
				);

				if (intSignificant > 0)
				{
					// if significant then add the row
					if (m_blnAlignRight)
					{
						arrRow_a = arrRow_a.reverse();
					}

					var intCol = 1;
					processArray(arrRow_a, function (strTileID_a)
					{
						objTile = getTile(strTileID_a);

						if ((objTile !== null) && (checkTilePermission(objTile)))
						{
							var blnContinue = true;
							if ((intRenderLimit_a > 0) && (!m_blnAlwaysHideMore))
							{
								// enforce a maximum number of tiles to render if provided, but must allow at least 1
								blnContinue = false;

								// notes: due to the way browsers work (http://taligarsiel.com/Projects/howbrowserswork1.htm) it isn't possible to manipulate DOM and work out how much space it will take
								// when rendering before the rendering happens if the DOM elements are not a static size.  So, we have to come up with a logic to allow our buttons to dynamically 
								// allocate the available leftover space without overflowing to a 2nd line.  Before we know if a button will fit, we have chosen the following logic currently:
								// if the leftover can fit twice the longers so-far added buttons plus the size of the more button, then we can fit the next button.  This works for most part unless the
								// next button is more than twice the longest of previously rendered buttons.  This is a quirk (design choice) until a better solution is found.
								if (intLeftover >= (intLongestTileRenderWidth * 1 + MOREBUTTONSIZE))
								{
									blnContinue = true;
								}
							}
							
							// use this for debugging
							//var strDebug = intRenderLimit_a + ":" + MOREBUTTONSIZE + ":" + intLeftover + ":" + intLongestTileRenderWidth;
							//os.element(m_strFormID, '.ge-form-title').html(strDebug);
							
							if (blnContinue)
							{
								renderTile(intTile, intCol, intRow, objTile, '');
								strLastTileID = objTile.id;

								var strWidth = os.element(m_strFormID, '.' + objTile.id).css("width");
								strWidth = str_replace(strWidth, "px", "");
								if (strWidth === null)
								{
									strWidth = "0";
								}
								
								objTile.renderwidth = parseInt(strWidth, 10);
								if (objTile.renderwidth > intLongestTileRenderWidth)
								{
									intLongestTileRenderWidth = objTile.renderwidth;
								}

								intLeftover -= objTile.renderwidth;
								if (intLeftover < 0) { intLeftover = 0; }

								intTile++;
								intCol++;
							}
						}
					}
					);

					if (m_blnAlignRight)
					{
						arrRow_a = arrRow_a.reverse();
					}

					intRow++;
				}
			}
			);

			doCallback(intRow);
			
			// NOTE FROM Jhun - Maybe this is place to add the dropdown menu?
			
			if(!m_blnAlwaysHideMore) 
			{ 
				intRow--;// stay on the current row
				renderMore(arrLayout_a, intRow, strLastTileID);
			}
			
			m_blnInRender = false;
		}
	}
    
    // Added by Jhun. to render more dropdown
    function renderMore(arrLayout_a, intStartOnRow_a, strStartAfterTileID_a) { 
        
        var objTile;
        var objFunction;
        var strFunction = '';
        var strElementClass;
        var strTile;
        var intRow = 1;
        var strCaption;
        
        strFunction += '<div class="ge-more-button btn-group">';
        strFunction += '<span class="btn btn-primary dropdown-toggle" data-toggle="dropdown">More...&nbsp;<span class="caret"></span></span>';
        strFunction += '<ul class="ge-more-menu gs-button-panel-dropdown-menu dropdown-menu">';
        strFunction += '</ul>';
        strFunction += '</div>';
        
        //objFunction = $(strFunction);
        
        //$('#' + m_strElementID).append(objFunction);
		$('#' + m_strElementID).append(strFunction);
        
		var blnRenderRow = false;
		var blnRenderTile = false;
		var blnRendered = false;
        var strClasses;
        
        processArray(arrLayout_a, function (arrRow_a)
		{
			if (intRow == intStartOnRow_a)
			{
				blnRenderRow = true;
			}
			
			if (blnRenderRow)	// no else here as we are starting on the row
			{
				processArray(arrRow_a, function (strTileID_a)
				{
					objTile = getTile(strTileID_a);
					strElementClass = strTileID_a; // + 'DropdownTile';
					
					if (!blnRenderTile)
					{
						if ((objTile.id == strStartAfterTileID_a) || (strStartAfterTileID_a.length === 0))
						{
							blnRenderTile = true;
						}
					}
					else	// this else is because we are starting after the tile
					{
						if ((objTile !== null) && (checkTilePermission(objTile)))
						{
							//if(strTileID_a.indexOf('Close') === -1 && strTileID_a.indexOf('Save') === -1 ) 
                            
                            strClasses = '';
                            
                            if ((objTile.tempclasses == undefined) || (objTile.tempclasses.length = 0))
                            {
                                if ($.isFunction(objTile.classes))
                                {
                                    strClasses = objTile.classes();
                                }
                                else
                                {
                                    strClasses = objTile.classes;
                                }

                                objTile.tempclasses = strClasses;
                            }
                            else
                            {
                                strClasses = objTile.tempclasses;
                            }
                                                        
                            if(strClasses === '' || strClasses === undefined) { 
                                strClasses = 'btn-primary'; 
                            }

                            if(strClasses.indexOf('gs-green-background-colour') >= 0) { 
                                strClasses = strClasses.replace('gs-green-background-colour', 'btn-success'); 
                            } 

                            if(strClasses.indexOf('gs-red-background-colour') >= 0) { 
                                strClasses = strClasses.replace('gs-red-background-colour', 'btn-danger'); 
                            }   

                            if(strClasses.indexOf('gb-cell-disabled-xxx') >= 0) { 
                                 strClasses = strClasses.replace('gb-cell-disabled-xxx', 'btn-disabled'); 
                            }

                            if(strClasses.indexOf('gb-cell-disabled') >= 0) { 
                                 strClasses = strClasses.replace('gb-cell-disabled', 'btn-disabled'); 
                            }

                            if(strClasses.indexOf('btn-default') === -1 && strClasses.indexOf('btn-primary') === -1 && strClasses.indexOf('btn-success') === -1 && strClasses.indexOf('btn-danger') === -1 && strClasses.indexOf('btn-disabled') === -1 ) { 
                                strClasses += ' btn-primary';  
                            }
                
							strCaption = objTile.caption;

							strTile = '';

							if(objTile.type === 'toolbardevicebutton')
							{
								
								strTile += '<li onclick="event.stopPropagation()">';

								strTile += '<div class="btn-group" style="display:block;">';
								strTile += '<span class="ge-toolbardevicebutton-more-menu gs-dropdown-tile btn ' + strClasses +  ' gb-dropdown-tile" data-toggle="dropdown" style="display:block;width:100%;padding-left:20px">';
								strTile += strCaption + '&nbsp;<span class="caret"></span>';
								strTile += '</span>';
								strTile += '<ul class="gs-button-panel-dropdown-menu dropdown-menu" style="left:100%">';

								processArray(objTile.actionDataList, function(objData_a)
								{
									strTile += '<li><a class="gs-dropdown-tile btn btn-primary gb-dropdown-tile ' + objData_a.id + '">' + objData_a.caption + '</a></li>';
								});
								
								strTile += '</ul>';
								strTile += '</div>';
								
								strTile += '</li>';
							}
							else
							{	
								strTile = '<li>';
								strTile += '<a class="' + strElementClass + ' gs-dropdown-tile btn ' + strClasses +  ' gb-dropdown-tile">'+strCaption+'</a>';
								strTile += '</li>';
							}
																											
							//objFunction.find('ul.dropdown-menu').append(strTile);
							os.element('#' + m_strElementID, '.ge-more-menu').append(strTile);
							
							
							if (($.isFunction(objTile.action)) || (objTile.action.length > 0))
							{
								os.unbindEvents('#' + m_strElementID, strElementClass);
								os.bindEvent(m_objThis, '#' + m_strElementID, '.' + strElementClass, 'Tile', 'onClick', objTile);
								os.bindEvent(m_objThis, '#' + m_strElementID, '.' + strElementClass, 'Tile', 'onEnterKey', objTile);
								
							}
							else
							{
								os.bindEvent(m_objThis, '#' + m_strElementID, '.' + strElementClass, 'Tile', 'onClick', objTile);
								os.bindEvent(m_objThis, '#' + m_strElementID, '.' + strElementClass, 'Tile', 'onEnterKey', objTile);
							}

							if (objTile.type === 'toolbardevicebutton')
							{
								processArray(objTile.actionDataList, function(objData_a)
								{
									os.unbindEvents('#' + m_strElementID, objData_a.id);
									os.bindEvent(m_objThis, '#' + m_strElementID, '.' + objData_a.id, 'Tile', 'onClick', objData_a);
								});
							}
							
                            objTile.tempclasses = os.element(m_strFormID, '.' + objTile.id).attr('class');
                            
							blnRendered = true;
						}
						else
						{
							doException('renderVerticalMenu', 'missing tile: ' + strTileID_a);
						}
					}
				});
			}

			intRow++;
		});
				
		$('.ge-toolbardevicebutton-more-menu').click(function() {
			var $objThis = $(this);

			if($objThis.parent('.btn-group').hasClass('open'))
			{
				$objThis.parent('.btn-group').removeClass('open');
			}
			else
			{
				$objThis.parent('.btn-group').addClass('open');
			}
		});

		if (!blnRendered)
		{
			// remove the empty dropdown as nothing was rendered
			os.element('#' + m_strElementID, '.ge-more-button').remove();
		}
    }
                
	// render vertically
	function renderVerticalMenu(arrLayout_a)
	{
		var intRow = 1;
		var intTile = 1;
		var objTile;
		var blnSignificant;
		var strRowHeadID = '';
        
		var objPreviousRenderedTile = null;
        
		processArray(arrLayout_a, function (arrRow_a)
		{
			// check row for significance
			var intSignificant = 0;                        
			processArray(arrRow_a, function (strTileID_a)
			{
				objTile = getTile(strTileID_a);                
				if (objTile !== null)
				{
					blnSignificant = objTile.significant;
					if (blnSignificant === undefined)
					{
						blnSignificant = true;
					}

					if (blnSignificant)
					{
						if (checkTilePermission(objTile))
						{
							intSignificant++;
						}
					}
				}
				else
				{
					doException('renderVerticalMenu', 'missing tile: ' + strTileID_a);
				}                
			}
			);

			if (intSignificant > 0)
			{
				var intCol = 1;                
				processArray(arrRow_a, function (strTileID_a)
				{
					objTile = getTile(strTileID_a);  

					if (objTile !== null)
					{
						if (intCol == 1)
						{
							if (objTile.id === '-')
							{
								// although this is a new row head, stay with the previous row head
								if (m_blnRenderBlanks)
								{
									renderTile(intTile, 1, intRow, objTile, strRowHeadID);
									intTile++;
								}
							}
							else if ((checkTilePermission(objTile)) && (objTile.caption.length > 0))
							{
								if(objPreviousRenderedTile !== null && objPreviousRenderedTile.id === '--' && objPreviousRenderedTile.id === objTile.id)
								{
									doNothing();
								}
								else
								{
									// we have a new head
									strRowHeadID = objTile.id;
									renderTile(intTile, 1, intRow, objTile, strRowHeadID);
									objPreviousRenderedTile = objTile;
									intTile++;
								}
							}
						}
						else if ((checkTilePermission(objTile)) && (objTile.caption.length > 0))
						{
							if(objPreviousRenderedTile !== null && objPreviousRenderedTile.id === '--' && objPreviousRenderedTile.id === objTile.id)
							{
								doNothing();
							}
							else
							{
								// render tile collapsed (invisible)
								// we must have a head so use it
								renderTile(intTile, 1, intRow, objTile, strRowHeadID);
								objPreviousRenderedTile = objTile;
								intTile++;
							}
						}

						intRow++;
						intCol++;
					}
					else
					{
						doException('renderVerticalMenu', 'missing tile: ' + strTileID_a);
					}                                        
				}
				);
			}
		}
		);

		doCallback(intRow);
	}

	function renderLocation(objLocation_a, intRenderLimit_a)
	{
		renderTitle(objLocation_a.title);

		if (m_strMode === "verticalmenu")
		{
			renderVerticalMenu(objLocation_a.layout);	// for now don't cater for mobile
		}
		else
		{
			// will be a buttongrid for now (which suits horizontal buttons and menus)
			renderLayoutH(objLocation_a.layout, intRenderLimit_a);
		}
	}

	this.disableButtons = function (arrButtons_a, strEnabledClass_a)
	{
		var arrButton = arrButtons_a.split(',');
		processArray(arrButton, function (strTileID_a)
		{
			var objTile = getTile(strTileID_a);

			if ((objTile !== null) && (checkTilePermission(objTile)))
			{
				var arrClasses = strEnabledClass_a.split(' ');
				processArray(arrClasses, function (strClass_a)
				{   
                    if(objTile.type === 'toolbarbutton') { 
                        
                       if( os.element(m_strFormID, '.' + objTile.id).hasClass('gs-darkblue-background-colour') ) { 
                           os.element(m_strFormID, '.' + objTile.id).removeClass('btn-primary');
                       } 
                       
                       if( os.element(m_strFormID, '.' + objTile.id).hasClass('gs-red-background-colour') ) { 
                           os.element(m_strFormID, '.' + objTile.id).removeClass('btn-danger');
                       } 
                    }
                    
					objTile.classes = objTile.classes.replace(strClass_a, '');
					os.element(m_strFormID, '.' + objTile.id).removeClass(strClass_a);
				}
				);

				if (objTile.type === 'toolbarbutton')
				{
                    
                    
					if (objTile.classes.indexOf('disabled') === -1)
					{
						//objTile.classes = objTile.classes + ' disabled';
						os.element(m_strFormID, '.' + objTile.id).addClass('btn-disabled disabled');
					}
                                        
                    /*
                    else { 
                        os.element(m_strFormID, '.' + objTile.id).addClass('btn-disabled');
                    }*/

				}
				else
				{
					if (objTile.classes.indexOf('gb-cell-disabled-xxx') === -1)
					{
						objTile.classes = objTile.classes + ' gb-cell-disabled-xxx';
						os.element(m_strFormID, '.' + objTile.id).addClass('gb-cell-disabled-xxx');
					}
				}

				objTile.classes = objTile.classes.replace('  ', ' ');
				objTile.classes = $.trim(objTile.classes);

				os.element(m_strFormID, '.' + objTile.id).qtip('hide', true);
				os.element(m_strFormID, '.' + objTile.id).qtip('disable', true);
				
				objTile.tempclasses = os.element(m_strFormID, '.' + objTile.id).attr('class');
			}
			else
			{
				doException('disableButtons', 'missing tile: ' + strTileID_a);
			}
		}
		);

        disableDropdownButtons(arrButtons_a, strEnabledClass_a);
	};

	this.enableButtons = function (arrButtons_a, strEnabledClass_a)
	{
		var arrButton = arrButtons_a.split(',');
		processArray(arrButton, function (strTileID_a)
		{
			var objTile = getTile(strTileID_a);

			if ((objTile !== null) && (checkTilePermission(objTile)))
			{
				var arrClasses = strEnabledClass_a.split(' ');
				processArray(arrClasses, function (strClass_a)
				{
                    if(objTile.type === 'toolbarbutton') { 
                    
                        if( os.element(m_strFormID, '.' + objTile.id).hasClass('gs-red-background-colour') ) { 
                            os.element(m_strFormID, '.' + objTile.id).addClass('btn-danger');
                        }
                        else if( os.element(m_strFormID, '.' + objTile.id).hasClass('gs-darkblue-background-colour') ) { 
                            os.element(m_strFormID, '.' + objTile.id).addClass('btn-primary');
                        }
                        else { 
                            os.element(m_strFormID, '.' + objTile.id).addClass(strClass_a);
                        }
                        
                    } else {
                    
                        if (objTile.classes.indexOf(strClass_a) === -1)
                        {
                            objTile.classes = objTile.classes + ' ' + strClass_a;
                            os.element(m_strFormID, '.' + objTile.id).addClass(strClass_a);

                        }
                    }
				}
				);

				if (objTile.type === 'toolbarbutton')
				{
					//objTile.classes = objTile.classes.replace('disabled', '');
					//objTile.classes = objTile.classes.replace('  ', ' ');
					//objTile.classes = $.trim(objTile.classes);
					os.element(m_strFormID, '.' + objTile.id).removeClass('disabled gb-cell-disabled btn-disabled');
				}
				else
				{
					objTile.classes = objTile.classes.replace('gb-cell-disabled-xxx', '');
					objTile.classes = objTile.classes.replace('  ', ' ');
					objTile.classes = $.trim(objTile.classes);
					os.element(m_strFormID, '.' + objTile.id).removeClass('gb-cell-disabled-xxx');
				}

				os.element(m_strFormID, '.' + objTile.id).qtip('disable', false);
				
				objTile.tempclasses = os.element(m_strFormID, '.' + objTile.id).attr('class');
			}
			else
			{
				doException('enableButtons', 'missing tile: ' + strTileID_a);
			}
		}
		);

        enableDropdownButtons(arrButtons_a, strEnabledClass_a);
	};
    
    function disableDropdownButtons (arrButtons_a, strEnabledClass_a)
	{
		var arrButton = arrButtons_a.split(',');
        
        var strDropdownTileID;
        
		processArray(arrButton, function (strTileID_a)
		{
			var objTile = getTile(strTileID_a);
                                    
			if ((objTile !== null) && (checkTilePermission(objTile)))
			{
				var arrClasses = strEnabledClass_a.split(' ');
                
                strDropdownTileID = objTile.id; // + 'DropdownTile';
                
				processArray(arrClasses, function (strClass_a)
				{   
                        
                       if( os.element(m_strFormID, '.' + strDropdownTileID).hasClass('gs-darkblue-background-colour') ) { 
                           os.element(m_strFormID, '.' + strDropdownTileID).removeClass('btn-primary');
                       } 
                       
                       if( os.element(m_strFormID, '.' + strDropdownTileID).hasClass('gs-red-background-colour') ) { 
                           os.element(m_strFormID, '.' + strDropdownTileID).removeClass('btn-danger');
                       } 
                       
					os.element(m_strFormID, '.' + strDropdownTileID).removeClass(strClass_a);
				}
				);
                                    
                os.element(m_strFormID, '.' + strDropdownTileID).addClass('btn-disabled disabled');
                                                                           								
			}
			else
			{
				doException('disableDropdownButtons', 'missing tile: ' + strTileID_a);
			}
		}
		);
	}
    
    function enableDropdownButtons (arrButtons_a, strEnabledClass_a)
	{
		var arrButton = arrButtons_a.split(',');
        var strDropdownTileID;
        
		processArray(arrButton, function (strTileID_a)
		{
			var objTile = getTile(strTileID_a);
                        
			if ((objTile !== null) && (checkTilePermission(objTile)))
			{
				var arrClasses = strEnabledClass_a.split(' ');
                
                strDropdownTileID = objTile.id; // + 'DropdownTile';
                
				processArray(arrClasses, function (strClass_a)
				{
                        if( os.element(m_strFormID, '.' + strDropdownTileID).hasClass('gs-red-background-colour') ) { 
                            os.element(m_strFormID, '.' + strDropdownTileID).addClass('btn-danger');
                        }
                        else if( os.element(m_strFormID, '.' + strDropdownTileID).hasClass('gs-darkblue-background-colour') ) { 
                            os.element(m_strFormID, '.' + strDropdownTileID).addClass('btn-primary');
                        }
                        else { 
                            os.element(m_strFormID, '.' + strDropdownTileID).addClass(strClass_a);
                        }
				}
				);
                                                
                os.element(m_strFormID, '.' + strDropdownTileID).removeClass('disabled gb-cell-disabled btn-disabled');
			}
			else
			{
				doException('enableDropdownButtons', 'missing tile: ' + strTileID_a);
			}
		}
		);
	}
        
    this.getHeight = function() {
        return m_intElementHeight;
    };

	function render2(strLocation_a, intRenderLimit_a)
	{
		m_objThis.setLocation(strLocation_a, intRenderLimit_a);
		
		var strTemp = $('#' + m_strElementID).css('height');
		strTemp = str_replace(strTemp, "px", "");
		if (strTemp.length === 0) { strTemp = "0"; }
		m_intElementHeight = parseInt(strTemp, 10);

		strTemp = $('#' + m_strElementID).css('width');
		strTemp = str_replace(strTemp, "px", "");
		if (strTemp.length === 0) { strTemp = "0"; }
		m_intElementWidth = parseInt(strTemp, 10);
	}
	
	this.render = function (strFormID_a, strElementClass_a, strLocation_a)
	{
		var strLocation = strLocation_a;
		if (strLocation === undefined) { strLocation = 'root'; }

		// dynamically set a unique id for the provided class div
		m_strFormID = strFormID_a;
		m_strElementClass = strElementClass_a;
		m_strElementID = 'jdock-' + getGUID();
		$(m_strElementClass, strFormID_a).attr('id', m_strElementID);

		initialiseResizeEvent(strLocation);
	};
	
	this.getLocation = function()
	{
		return m_strLocation;
	};

	this.setLocation = function (strLocation_a, intRenderLimit_a)
	{
		m_strLocation = strLocation_a;
		
		m_strExpandedID = '';
		
		clearDock();

		var objLocation = getLocationInternal(m_strLocation);
		renderLocation(objLocation, intRenderLimit_a);
	};

	// note: a menu item cannot be both collapsable and navigation
	this.Tile_onClick = function (objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a)
	{
		if (objEvent_a.shiftKey)
		{
			Tile_onClickShifted(objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a);
		}
		else
		{
			Tile_onClickNotShifted(objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a);
		}
	};
	
	// note: a menu item cannot be both collapsable and navigation
	function Tile_onClickNotShifted(objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a)
	{
		var objActionData = null;
		var objActionData2 = null;
		var objActionData3 = null;
		
		// if the action is a callback, then do it
		if ($.isFunction(objTile_a.action))
		{
			var objClicked = createTile();
			copyTile(objTile_a, objClicked);

			if (m_blnAddToRecent)
			{
				addToRecent(objTile_a);
			}
		
			if (m_blnCloseOnSelect)
			{
				os.toggleMenu();
			}

			objActionData = objClicked.actionData;
			if ($.isFunction(objClicked.actionData))
			{
				objActionData = objClicked.actionData();
			}
		
			objActionData2 = objClicked.actionData2;
			if ($.isFunction(objClicked.actionData2))
			{
				objActionData2 = objClicked.actionData2();
			}
		
			objActionData3 = objClicked.actionData3;
			if ($.isFunction(objClicked.actionData3))
			{
				objActionData3 = objClicked.actionData3();
			}
			
			objClicked.action(objActionData, objActionData2, objActionData3);
		}
		else if ((objTile_a.action === 'navigate') && (objTile_a.actionData != m_strLocation))
		{
			// else if naviation then do it
			m_objThis.setLocation(objTile_a.actionData);
			//alert(m_strLocation);
		}
		else
		{
			os.element(m_strFormID, '.gb-collapsable').hide();
			if (m_strExpandedID == objTile_a.id)
			{
				m_strExpandedID = '';
				doCallback(0);
			}
			else
			{
				m_strExpandedID = objTile_a.id;
				os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').removeClass('hidden');
				os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').show();
				// JC turned off the animation below for the time being... replace the line above with the commented out below
				//os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').show('fast', 'linear', function ()
				//{
					doCallback(0);
				//}
				//);
			}

			//doCallback(0);
			//m_objThis.setLocation(m_strLocation);
		}
	}

	// note: a menu item cannot be both collapsable and navigation
	function Tile_onClickShifted(objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a)
	{
		var objActionData = null;
		var objActionData2 = null;
		var objActionData3 = null;
		
		// if the actionShifted is a callback, then do it
		if ($.isFunction(objTile_a.actionShifted))
		{
			var objClicked = createTile();
			copyTile(objTile_a, objClicked);

			if (m_blnAddToRecent)
			{
				addToRecent(objTile_a);
			}
		
			if (m_blnCloseOnSelect)
			{
				os.toggleMenu();
			}

			objActionData = objClicked.actionDataShifted;
			if ($.isFunction(objClicked.actionDataShifted))
			{
				objActionData = objClicked.actionDataShifted();
			}
		
			objActionData2 = objClicked.actionDataShifted2;
			if ($.isFunction(objClicked.actionDataShifted2))
			{
				objActionData2 = objClicked.actionDataShifted2();
			}
		
			objActionData3 = objClicked.actionDataShifted3;
			if ($.isFunction(objClicked.actionDataShifted3))
			{
				objActionData3 = objClicked.actionDataShifted3();
			}
			
			objClicked.actionShifted(objActionData, objActionData2, objActionData3);
		}
		else if ((objTile_a.actionShifted === 'navigate') && (objTile_a.actionDataShifted != m_strLocation))
		{
			// else if naviation then do it
			m_objThis.setLocation(objTile_a.actionDataShifted);
			//alert(m_strLocation);
		}
		else
		{
			os.element(m_strFormID, '.gb-collapsable').hide();
			if (m_strExpandedID == objTile_a.id)
			{
				m_strExpandedID = '';
				doCallback(0);
			}
			else
			{
				m_strExpandedID = objTile_a.id;
				os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').removeClass('hidden');
				os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').show();
				// JC turned off the animation below for the time being... replace the line above with the commented out below
				//os.element(m_strFormID, '.' + m_strExpandedID + 'ROWHEAD').show('fast', 'linear', function ()
				//{
					doCallback(0);
				//}
				//);
			}

			//doCallback(0);
			//m_objThis.setLocation(m_strLocation);
		}
	}

	this.Tile_onEnterKey = function (objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a)
	{
		m_objThis.Tile_onClick(objThis_a, objElement_a, objEvent_a, objEventData_a, objTile_a);
	};
}
