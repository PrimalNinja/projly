/*jsl:option explicit*/
/*jsl:import inc-osutils.js*/

// ====================================================================================
// AWAFOS Utils v20241106 =============================================================
// copyright(C) 2012-2024 Mitsukibo, Julian Cassin & Francis Weston. All rights reserved.

function JCalendar(objOS_a, objOptions_a, objEvents_a)
{
	var os = objOS_a;
	var m_objThis = this;

	// default options
	var m_objOptions =
	{
		cbOnDaySelect : null,
		cbOnEventSelect : null,
		cbOnFetchPreviousMonth : null,
		cbOnFetchNextMonth : null,
		dayStart : 0,
		dayEnd : 23,
		maxCellEvents : 3,
		navigateMonths : true,
		overhangDays : true,
		selectableDays : true,
		startOfWeek : 'SUNDAY',
		todayLabel : true
	};

	// allowable overridden options
	if (objOptions_a.cbOnDaySelect !== undefined)
	{
		m_objOptions.cbOnDaySelect = objOptions_a.cbOnDaySelect;
	}
	if (objOptions_a.cbOnEventSelect !== undefined)
	{
		m_objOptions.cbOnEventSelect = objOptions_a.cbOnEventSelect;
	}
	if (objOptions_a.cbOnFetchPreviousMonth !== undefined)
	{
		m_objOptions.cbOnFetchPreviousMonth = objOptions_a.cbOnFetchPreviousMonth;
	}
	if (objOptions_a.cbOnFetchNextMonth !== undefined)
	{
		m_objOptions.cbOnFetchNextMonth = objOptions_a.cbOnFetchNextMonth;
	}
	if (objOptions_a.dayStart !== undefined)
	{
		m_objOptions.dayStart = objOptions_a.dayStart;
	}
	if (objOptions_a.dayEnd !== undefined)
	{
		m_objOptions.dayEnd = objOptions_a.dayEnd;
	}
	if (objOptions_a.maxCellEvents !== undefined)
	{
		m_objOptions.maxCellEvents = objOptions_a.maxCellEvents;
	}
	if (objOptions_a.navigateMonths !== undefined)
	{
		m_objOptions.navigateMonths = objOptions_a.navigateMonths;
	}
	if (objOptions_a.overhangDays !== undefined)
	{
		m_objOptions.overhangDays = objOptions_a.overhangDays;
	}
	if (objOptions_a.selectableDays !== undefined)
	{
		m_objOptions.selectableDays = objOptions_a.selectableDays;
	}
	if (objOptions_a.startOfWeek !== undefined)
	{
		m_objOptions.startOfWeek = objOptions_a.startOfWeek.toUpperCase();
	}
	if (objOptions_a.todayLabel !== undefined)
	{
		m_objOptions.todayLabel = objOptions_a.todayLabel;
	}

	var m_strTarget;
	var m_dteDate;
	var m_objEvents = objEvents_a;
	var m_strResponse = '';
	var m_strResponseEvents = '';
	var m_strResponseEventCount = 0;
	var m_strSelectedCell = '';
	var m_strSelectedEventCode = '';
	var m_arrTips = [];
	var m_intNextTipID = 0;

	var m_WEEKDAYCOUNT = 7;
	var m_CALENDAR_ROWS = 6;

	// week days
	var m_arrDays = [];
	m_arrDays[0] = 'SUNDAY';
	m_arrDays[1] = 'MONDAY';
	m_arrDays[2] = 'TUESDAY';
	m_arrDays[3] = 'WEDNESDAY';
	m_arrDays[4] = 'THURSDAY';
	m_arrDays[5] = 'FRIDAY';
	m_arrDays[6] = 'SATURDAY';

	var m_intStartOfWeekOffset = getStartOfWeekOffset(objOptions_a.startOfWeek);

	// ====================================================================================
	// HELPERS ============================================================================

	function addTip(strCellGUID_a, strTip_a)
	{
		m_arrTips[m_intNextTipID] =
		{
			CellGUID : strCellGUID_a,
			Tip : strTip_a
		};
		m_intNextTipID++;
	}

	function bindTips()
	{
		processArray(m_arrTips, function (objTip_a)
		{
			os.showTip('#' + m_strTarget, '.' + objTip_a.CellGUID, 'bottom center', 'top center', 2, objTip_a.Tip);
		}
		);
	}

	function clearTips()
	{
		m_arrTips = [];
		m_intNextTipID = 0;
	}

	function cloneDate(dte_a)
	{
		var dteResult = new Date();
		dteResult.setMonth(dte_a.getMonth());
		dteResult.setDate(dte_a.getDate());
		dteResult.setFullYear(dte_a.getFullYear());
		dteResult.setHours(dte_a.getHours());
		dteResult.setMinutes(dte_a.getMinutes());
		dteResult.setSeconds(dte_a.getSeconds());

		return dteResult;
	}

	function dateToString(dteDateTime_a)
	{
		var strDateTime = dteDateTime_a.getFullYear() + '-' + (dteDateTime_a.getMonth() + 1) + '-' + dteDateTime_a.getDate() + ' ' + dteDateTime_a.getHours() + ':' + dteDateTime_a.getMinutes() + ':' + dteDateTime_a.getSeconds();
		return strDateTime;
	}

	function stringToDate(strDateTime_a)
	{
		var intDatePartLen = strDateTime_a.indexOf(' ');
		var strDate = '';
		var strTime = '';
		var dteDateTime = new Date();
		var arrDate = [];
		var arrTime = [];

		if (intDatePartLen >= 0)
		{
			strDate = strDateTime_a.substring(0, intDatePartLen);
			strTime = strDateTime_a.substring(intDatePartLen, strDateTime_a.length);

			arrDate = strDate.split('-');
			arrTime = strTime.split(':');

			dteDateTime = new Date(arrDate[0], arrDate[1] - 1, arrDate[2], arrTime[0], arrTime[1], arrTime[2]);
		}
		else
		{
			strDate = strDateTime_a;
			arrDate = strDate.split('-');

			dteDateTime = new Date(arrDate[0], arrDate[1] - 1, arrDate[2]);
		}

		return dteDateTime;
	}

	function responseAdd(str_a)
	{
		m_strResponse = m_strResponse + str_a;
	}

	function responseEventsAdd(str_a)
	{
		m_strResponseEvents = m_strResponseEvents + str_a;
	}

	function getOutputDate(dteDate_a)
	{
		return getOutputDay(dteDate_a) + ' ' + getOutputMonth(dteDate_a);
	}

	function getOutputDay(dteDate_a)
	{
		return dteDate_a.getDate();
	}

	function getOutputMonth(dteDate_a)
	{
		var strResult = '';

		var arrMonths = [];
		arrMonths[0] = 'January';
		arrMonths[1] = 'February';
		arrMonths[2] = 'March';
		arrMonths[3] = 'April';
		arrMonths[4] = 'May';
		arrMonths[5] = 'June';
		arrMonths[6] = 'July';
		arrMonths[7] = 'August';
		arrMonths[8] = 'September';
		arrMonths[9] = 'October';
		arrMonths[10] = 'November';
		arrMonths[11] = 'December';
		strResult = arrMonths[dteDate_a.getMonth()] + ' ' + dteDate_a.getFullYear();

		return strResult;
	}

	function getOutputWeekday(intDay_a)
	{
		return m_arrDays[intDay_a];
	}

	function getStartOfWeekOffset(strStartOfWeek_a)
	{
		var intResult = -1;
		var intDay = 0;

		processArray(m_arrDays, function (strDay_a)
		{
			if (strDay_a == strStartOfWeek_a)
			{
				intResult = intDay;
			}
			intDay++;
		}
		);

		return intResult;
	}

	function isToday(dteCell_a)
	{
		var blnResult = false;
		var dteNow = new Date();

		if (dteCell_a.setHours(0, 0, 0, 0) == dteNow.setHours(0, 0, 0, 0))
		{
			blnResult = true;
		}

		return blnResult;
	}

	function getTodayLabel()
	{
		var strResult = '';

		if (m_objOptions.todayLabel)
		{
			strResult = ' <i><font color="red"> Today</font></i>';
		}

		return strResult;
	}

	function dateHasEvent(dteDate_a)
	{
		var blnResult = false;
		var intI = 0;

		processArray(m_objEvents, function (objEvent_a)
		{
			if (dteDate_a.setHours(0, 0, 0, 0) == objEvent_a.eventDate.setHours(0, 0, 0, 0))
			{
				blnResult = true;
			}
		}
		);

		return blnResult;
	}

	// ====================================================================================
	// RENDERING - GENERAL ================================================================

	function renderEvents(dteDate_a, strType_a, blnLimit_a)
	{
		var blnEvents = false;

		processArray(m_objEvents, function (objEvent_a)
		{
			var objEventDate = objEvent_a.eventDate;
			objEventDate.setHours(0, 0, 0, 0);

			var dteDate = new Date(dteDate_a);
			dteDate.setHours(0, 0, 0, 0);

			if ((dteDate - objEventDate) === 0)
			{
				if ((dteDate_a.getHours() == objEvent_a.eventStartTime) && (objEvent_a.eventStartTime >= m_objOptions.dayStart) && (objEvent_a.eventStartTime <= m_objOptions.dayEnd))
				{
					var strSelected = '';
					if (objEvent_a.eventCode == m_strSelectedEventCode)
					{
						strSelected = ' gs-calendar-eventselected';
					}

					var blnContinue = true;
					if (blnLimit_a && (strType_a === 'MONTHVIEW') && (m_strResponseEventCount >= m_objOptions.maxCellEvents))
					{
						blnContinue = false;
					}

					if (blnContinue)
					{
						if (objEvent_a.eventSelectable)
						{
							responseEventsAdd('<tr><td class="gs-calendar-eventselectable ' + objEvent_a.eventStyle + strSelected + '">');
							responseEventsAdd(dteDate_a.getHours() + ':00 - ' + htmlEncode(objEvent_a.eventName) + '<br>');
							responseEventsAdd('</td></tr>');
							m_strResponseEventCount++;
						}
						else
						{
							responseEventsAdd('<tr><td class="gs-calendar-event ' + objEvent_a.eventStyle + strSelected + '">');
							responseEventsAdd(dteDate_a.getHours() + ':00 - ' + htmlEncode(objEvent_a.eventName) + '<br>');
							responseEventsAdd('</td></tr>');
							m_strResponseEventCount++;
						}
					}

					blnEvents = true;
				}
			}
		}
		);

		if ((blnEvents === false) && (strType_a === 'DAYVIEW'))
		{
			if ((dteDate_a.getHours() >= m_objOptions.dayStart) && (dteDate_a.getHours() <= m_objOptions.dayEnd))
			{
				responseEventsAdd('<tr><td class="gs-calendar-noevent">');
				responseEventsAdd(dteDate_a.getHours() + ':00<br>');
				responseEventsAdd('</td></tr>');
			}
		}
	}

	// ====================================================================================
	// RENDERING - DAY ====================================================================

	function renderCalendarDayCellEvents(dteDate_a)
	{
		m_strResponseEvents = '';
		m_strResponseEventCount = 0;
		processArray([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23], function (objTime_a)
		{
			var dteDate = dteDate_a;
			dteDate.setHours(objTime_a);

			responseEventsAdd('<tr><td class="gs-calendar-event">');
			renderEvents(dteDate, 'DAYVIEW', false);
			responseEventsAdd('</td></tr>');
		}
		);
	}

	function renderDayInternal()
	{
		m_strResponse = '';
		clearTips();

		responseAdd('<table class="gs-calendar" cols="1">');

		responseAdd('<tr class="gs-calendar-titlerow">');
		if (objOptions_a.navigateMonths)
		{
			responseAdd('<td class="gs-calendar-title" colspan="1"><div class="gi-calendar-arrowleft"></div><div class="gs-calendar-titleday">' + getOutputDate(m_dteDate) + '</div><div class="gi-calendar-arrowright"></div></td>');
		}
		else
		{
			responseAdd('<td class="gs-calendar-title" colspan="1">' + getOutputDate(m_dteDate) + '</td>');
		}
		responseAdd('</tr>');

		responseAdd('<th colspan="1">' + getOutputWeekday(m_dteDate.getDay()) + '</th>');

		responseAdd('<tr class="gs-calendar-oddrow">');

		responseAdd('<td><div class="gs-calendar-cellday gs-calendar-cellselectable">');
		responseAdd('<table class="gs-calendar-eventtable">');

		renderCalendarDayCellEvents(m_dteDate);
		responseAdd(m_strResponseEvents);

		responseAdd('</table>');
		responseAdd('</div></td>');

		responseAdd('</tr>');

		responseAdd('</table>');

		$('#' + m_strTarget).html(m_strResponse);
		bindTips();

		if (objOptions_a.navigateMonths)
		{
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowleft', 'cmdDayPrevious', 'onClick');
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowright', 'cmdDayNext', 'onClick');
		}
	}

	// ====================================================================================
	// RENDERING - WEEK ===================================================================

	function renderCalendarWeekCell(dteDate_a, intCalCol_a)
	{
		var intCell = intCalCol_a;

		var dteFirst = new Date();
		dteFirst.setDate(dteDate_a.getDate() - 3);
		dteFirst.setHours(0, 0, 0, 0);

		var dteCell = new Date(dteFirst.getFullYear(), dteFirst.getMonth(), dteFirst.getDate() + (intCell + m_intStartOfWeekOffset));

		var strToday = '';
		var strTodayLabel = '';
		if (isToday(dteCell))
		{
			strToday = ' gs-calendar-celltoday';
			strTodayLabel = getTodayLabel();
		}

		if ((dteDate_a.getMonth() == dteCell.getMonth()) && (dteDate_a.getFullYear() == dteCell.getFullYear()))
		{
			var strCellGUID = getGUID();
			var strID = dateToString(dteCell);
			var strSelected = '';
			if (strID == m_strSelectedCell)
			{
				strSelected = ' gs-calendar-cellselectedweek';
			}
			responseAdd('<td><div class="gs-calendar-cellselectable gs-calendar-cellweek ' + strCellGUID + strToday + strSelected + '" id="' + strID + '">');	// TODO remove the non-GUID ID
			responseAdd('<span class="gs-calendar-day">' + getOutputDay(dteCell) + strTodayLabel + '</span>');
			responseAdd('<table class="gs-calendar-eventtable">');

			renderCalendarDayCellEvents(dteCell);
			responseAdd(m_strResponseEvents);

			responseAdd('</table>');
			responseAdd('</div></td>');
		}
		else
		{
			if (m_objOptions.overhangDays)
			{
				responseAdd('<td><div class="gs-calendar-cellweek' + strToday + '">');
				responseAdd('<span class="gs-calendar-dayfaded">' + getOutputDay(dteCell) + '</span>');
				responseAdd('</div></td>');
			}
			else
			{
				responseAdd('<td><div class="gs-calendar-cellweek"></div></td>');
			}
		}
	}

	function renderWeekInternal()
	{
		var intCalCol;
		m_strResponse = '';
		clearTips();

		responseAdd('<table class="gs-calendar" cols="' + m_WEEKDAYCOUNT + '">');

		responseAdd('<tr class="gs-calendar-titlerow">');
		if (objOptions_a.navigateMonths)
		{
			responseAdd('<td class="gs-calendar-title" colspan="' + m_WEEKDAYCOUNT + '"><div class="gi-calendar-arrowleft"></div><div class="gs-calendar-titlemonth">' + getOutputMonth(m_dteDate) + '</div><div class="gi-calendar-arrowright"></div></td>');
		}
		else
		{
			responseAdd('<td class="gs-calendar-title" colspan="' + m_WEEKDAYCOUNT + '"><div class="gs-calendar-titlemonth">' + getOutputMonth(m_dteDate) + '</div></td>');
		}
		responseAdd('</tr>');

		responseAdd('<tr>');
		for (intCalCol = 0; intCalCol < m_WEEKDAYCOUNT; intCalCol++)
		{

			var dteFirst = new Date();
			dteFirst.setDate(m_dteDate.getDate() - 3);
			dteFirst.setHours(0, 0, 0, 0);
			var dteCell = new Date(dteFirst.getFullYear(), dteFirst.getMonth(), dteFirst.getDate() + (intCalCol + m_intStartOfWeekOffset));
			var intDay = dteCell.getDay();

			//var intDay = intCalCol % m_WEEKDAYCOUNT;
			responseAdd('<th>' + getOutputWeekday(intDay) + '</th>');
		}
		responseAdd('</tr>');

		responseAdd('<tr class="gs-calendar-oddrow">');

		for (intCalCol = 0; intCalCol < m_WEEKDAYCOUNT; intCalCol++)
		{
			renderCalendarWeekCell(m_dteDate, intCalCol);
		}
		responseAdd('</tr>');

		responseAdd('</table>');

		$('#' + m_strTarget).html(m_strResponse);
		bindTips();

		if (objOptions_a.navigateMonths)
		{
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowleft', 'cmdWeekPrevious', 'onClick');
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowright', 'cmdWeekNext', 'onClick');
		}
	}

	// ====================================================================================
	// RENDERING - MONTH ==================================================================

	function renderCalendarMonthCell(dteDate_a, intCalRow_a, intCalCol_a)
	{
		var intCell = (intCalRow_a - 1) * m_WEEKDAYCOUNT + intCalCol_a;

		var dteFirst = dteDate_a;
		dteFirst.setDate(1);
		dteFirst.setHours(0, 0, 0, 0);

		var intFirst = dteFirst.getDay();
		if ((m_intStartOfWeekOffset) > intFirst)
		{
			intFirst = intFirst + m_WEEKDAYCOUNT;
		}

		var dteCell = new Date(dteFirst.getFullYear(), dteFirst.getMonth(), dteFirst.getDate() + (intCell - intFirst + m_intStartOfWeekOffset));

		var strToday = '';
		var strTodayLabel = '';
		if (isToday(dteCell))
		{
			strToday = ' gs-calendar-celltoday';
			strTodayLabel = getTodayLabel();
		}

		if ((dteDate_a.getMonth() == dteCell.getMonth()) && (dteDate_a.getFullYear() == dteCell.getFullYear()))
		{
			var strCellGUID = getGUID();
			var strID = dateToString(dteCell);
			var strSelected = '';
			if (strID == m_strSelectedCell)
			{
				strSelected = ' gs-calendar-cellselected';
			}
			responseAdd('<td><div class="gs-calendar-cell gs-calendar-cellselectable ' + strCellGUID + strToday + strSelected + '" id="' + strID + '">');	// TODO remove the non-GUID ID
			responseAdd('<span class="gs-calendar-day">' + getOutputDay(dteCell) + strTodayLabel + '</span>');
			responseAdd('<table class="gs-calendar-eventtable">');

			renderCalendarMonthCellEvents(dteCell, true);
			responseAdd(m_strResponseEvents);

			if (m_strResponseEventCount > 0)
			{
				renderCalendarMonthCellEvents(dteCell, false);
				var strCalendarTip = '<div class="gs-calendar-day">' + getOutputDate(dteCell) + strTodayLabel + '</div><table class="gs-calendar-eventtable">' + m_strResponseEvents + '</table>';
				addTip(strCellGUID, strCalendarTip);
			}

			responseAdd('</table>');
			responseAdd('</div></td>');
		}
		else
		{
			if (m_objOptions.overhangDays)
			{
				responseAdd('<td><div class="gs-calendar-cell' + strToday + '">');
				responseAdd('<span class="gs-calendar-dayfaded">' + getOutputDay(dteCell) + '</span>');
				responseAdd('</div></td>');
			}
			else
			{
				responseAdd('<td><div class="gs-calendar-cell"></div></td>');
			}
		}
	}

	function renderCalendarMonthCellEvents(dteDate_a, blnLimit_a)
	{
		m_strResponseEvents = '';
		m_strResponseEventCount = 0;
		processArray([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23], function (objTime_a)
		{
			var dteDate = dteDate_a;
			dteDate.setHours(objTime_a);

			responseEventsAdd('<tr><td class="gs-calendar-event">');
			renderEvents(dteDate, 'MONTHVIEW', blnLimit_a);
			responseEventsAdd('</td></tr>');
		}
		);
	}

	function renderMonthInternal()
	{
		var intCalCol;
		m_strResponse = '';
		clearTips();

		responseAdd('<table class="gs-calendar" cols="' + m_WEEKDAYCOUNT + '">');

		responseAdd('<tr class="gs-calendar-titlerow">');
		if (objOptions_a.navigateMonths)
		{
			responseAdd('<td class="gs-calendar-title" colspan="' + m_WEEKDAYCOUNT + '"><div class="gi-calendar-arrowleft"></div><div class="gs-calendar-titlemonth">' + getOutputMonth(m_dteDate) + '</div><div class="gi-calendar-arrowright"></div></td>');
		}
		else
		{
			responseAdd('<td class="gs-calendar-title" colspan="' + m_WEEKDAYCOUNT + '"><div class="gs-calendar-titlemonth">' + getOutputMonth(m_dteDate) + '</div></td>');
		}
		responseAdd('</tr>');

		responseAdd('<tr>');
		for (intCalCol = 0; intCalCol < m_WEEKDAYCOUNT; intCalCol++)
		{
			var intDay = (intCalCol + m_intStartOfWeekOffset) % m_WEEKDAYCOUNT;
			responseAdd('<th>' + getOutputWeekday(intDay) + '</th>');
		}
		responseAdd('</tr>');

		for (var intCalRow = 1; intCalRow <= m_CALENDAR_ROWS; intCalRow++)
		{
			if (intCalRow % 2 === 0)
			{
				responseAdd('<tr class="gs-calendar-oddrow">');
			}
			else
			{
				responseAdd('<tr class="gs-calendar-evenrow">');
			}

			for (intCalCol = 0; intCalCol < m_WEEKDAYCOUNT; intCalCol++)
			{
				renderCalendarMonthCell(m_dteDate, intCalRow, intCalCol);
			}
			responseAdd('</tr>');
		}
		responseAdd('</table>');

		$('#' + m_strTarget).html(m_strResponse);
		bindTips();

		if (objOptions_a.navigateMonths)
		{
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowleft', 'cmdMonthPrevious', 'onClick');
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gi-calendar-arrowright', 'cmdMonthNext', 'onClick');
		}

		if (m_objOptions.selectableDays)
		{
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gs-calendar-cellselectable', 'cmdCell', 'onClick');
		}

		if ($.isFunction(m_objOptions.cbOnEventSelect))
		{
			os.bindEvent(m_objThis, '#' + m_strTarget, '.gs-calendar-eventselectable', 'cmdEvent', 'onClick');
		}
	}

	// ====================================================================================
	// PUBLIC =============================================================================

	this.renderDay = function (strTarget_a, dteDate_a)
	{
		m_strTarget = strTarget_a;
		m_dteDate = cloneDate(dteDate_a);
		m_dteDate.setHours(0, 0, 0, 0);

		renderDayInternal();
	};

	this.renderMonth = function (strTarget_a, dteDate_a)
	{
		m_strTarget = strTarget_a;
		m_dteDate = cloneDate(dteDate_a);
		m_dteDate.setHours(0, 0, 0, 0);

		renderMonthInternal();
	};

	this.renderWeek = function (strTarget_a, dteDate_a)
	{
		m_strTarget = strTarget_a;
		m_dteDate = cloneDate(dteDate_a);
		m_dteDate.setHours(0, 0, 0, 0);

		renderWeekInternal();
	};

	this.setEvents = function (objEvents_a)
	{
		m_objEvents = objEvents_a;
	};

	this.setSelection = function (strEventCode_a)
	{
		m_strSelectedEventCode = strEventCode_a;
	};

	// ====================================================================================
	// FUNCTION EVENTS ====================================================================

	this.cmdCell_onClick = function (objThis_a, objElement_a, objEvent_a)
	{
		if (m_objOptions.selectableDays)
		{
			var strDate = $(objThis_a).attr('id');
			var dteDate = stringToDate(strDate);

			if (m_strSelectedCell.length > 0)
			{
				// unselect previous class
				$('.gs-calendar-cellselectable', '#' + m_strTarget).removeClass('gs-calendar-cellselected');
				m_strSelectedCell = '';
			}

			if (strDate.length > 0)
			{
				m_strSelectedCell = strDate;
				$(objThis_a).addClass('gs-calendar-cellselected');
			}

			if ($.isFunction(m_objOptions.cbOnDaySelect))
			{
				m_objOptions.cbOnDaySelect(dteDate);
			}
		}
	};

	this.cmdDayNext_onClick = function ()
	{
		var intOldMonth = m_dteDate.getMonth();
		m_dteDate.setDate(m_dteDate.getDate() + 1);
		var intNewMonth = m_dteDate.getMonth();

		if (m_objOptions.selectableDays)
		{
			m_dteDate.setHours(0, 0, 0, 0);
			m_strSelectedCell = dateToString(m_dteDate);

			if ($.isFunction(m_objOptions.cbOnDaySelect))
			{
				m_objOptions.cbOnDaySelect(m_dteDate);
			}

			if (intOldMonth !== intNewMonth)
			{
				if ($.isFunction(m_objOptions.cbOnFetchNextMonth))
				{
					m_objOptions.cbOnFetchNextMonth(m_dteDate, 'DAYVIEW');
				}
				else
				{
					renderDayInternal();
				}
			}
			else
			{
				renderDayInternal();
			}
		}
	};

	this.cmdDayPrevious_onClick = function ()
	{
		var intOldMonth = m_dteDate.getMonth();
		m_dteDate.setDate(m_dteDate.getDate() - 1);
		var intNewMonth = m_dteDate.getMonth();

		if (m_objOptions.selectableDays)
		{
			m_dteDate.setHours(0, 0, 0, 0);
			m_strSelectedCell = dateToString(m_dteDate);

			if ($.isFunction(m_objOptions.cbOnDaySelect))
			{
				m_objOptions.cbOnDaySelect(m_dteDate);
			}

			if (intOldMonth !== intNewMonth)
			{
				if ($.isFunction(m_objOptions.cbOnFetchPreviousMonth))
				{
					m_objOptions.cbOnFetchPreviousMonth(m_dteDate, 'DAYVIEW');
				}
				else
				{
					renderDayInternal();
				}
			}
			else
			{
				renderDayInternal();
			}
		}
	};

	this.cmdEvent_onClick = function ()
	{
		if ($.isFunction(m_objOptions.cbOnEventSelect))
		{
			m_objOptions.cbOnEventSelect();
		}
	};

	this.cmdMonthNext_onClick = function ()
	{
		var dteFirst = m_dteDate;
		dteFirst.setDate(1);
		dteFirst.setHours(0, 0, 0, 0);
		m_dteDate = dteFirst;
		m_dteDate.setMonth(m_dteDate.getMonth() + 1);

		if ($.isFunction(m_objOptions.cbOnFetchNextMonth))
		{
			m_objOptions.cbOnFetchNextMonth(m_dteDate, 'MONTHVIEW');
		}
		else
		{
			renderMonthInternal();
		}
	};

	this.cmdMonthPrevious_onClick = function ()
	{
		var dteFirst = m_dteDate;
		dteFirst.setDate(1);
		dteFirst.setHours(0, 0, 0, 0);
		m_dteDate = dteFirst;
		m_dteDate.setMonth(m_dteDate.getMonth() - 1);

		if ($.isFunction(m_objOptions.cbOnFetchPreviousMonth))
		{
			m_objOptions.cbOnFetchPreviousMonth(m_dteDate, 'MONTHVIEW');
		}
		else
		{
			renderMonthInternal();
		}
	};

	this.cmdWeekNext_onClick = function ()
	{
		var intOldMonth = m_dteDate.getMonth();
		m_dteDate.setDate(m_dteDate.getDate() + 1);
		var intNewMonth = m_dteDate.getMonth();

		if (m_objOptions.selectableDays)
		{
			m_dteDate.setHours(0, 0, 0, 0);
			m_strSelectedCell = dateToString(m_dteDate);

			if ($.isFunction(m_objOptions.cbOnDaySelect))
			{
				m_objOptions.cbOnDaySelect(m_dteDate);
			}

			if (intOldMonth !== intNewMonth)
			{
				if ($.isFunction(m_objOptions.cbOnFetchNextMonth))
				{
					m_objOptions.cbOnFetchNextMonth(m_dteDate, 'WEEKVIEW');
				}
				else
				{
					renderWeekInternal();
				}
			}
			else
			{
				renderWeekInternal();
			}
		}
	};

	this.cmdWeekPrevious_onClick = function ()
	{
		var intOldMonth = m_dteDate.getMonth();
		m_dteDate.setDate(m_dteDate.getDate() - 1);
		var intNewMonth = m_dteDate.getMonth();

		if (m_objOptions.selectableDays)
		{
			m_dteDate.setHours(0, 0, 0, 0);
			m_strSelectedCell = dateToString(m_dteDate);

			if ($.isFunction(m_objOptions.cbOnDaySelect))
			{
				m_objOptions.cbOnDaySelect(m_dteDate);
			}

			if (intOldMonth !== intNewMonth)
			{
				if ($.isFunction(m_objOptions.cbOnFetchPreviousMonth))
				{
					m_objOptions.cbOnFetchPreviousMonth(m_dteDate, 'WEEKVIEW');
				}
				else
				{
					renderWeekInternal();
				}
			}
			else
			{
				renderWeekInternal();
			}
		}
	};
}
