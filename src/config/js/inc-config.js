var g_arrTaskSets = [];
var g_blnInSubTasks = false;
var g_strAlert = '';
var g_strOptionCode = '';
var g_strUpdateNumber = '';
var g_strSubTasks = '';
var g_intStartTime = 0;
var g_intSubTaskStartTime = 0;
var g_intSubTaskRowID = 0;
var g_blnFlagBackup = false;
var g_blnFlagConfigurationProd = false;
var g_blnFlagConfigurationUAT = false;
var g_blnFlagConfigurationDev = false;
var g_blnFlagUpdate = false;

//var g_objTimer = $.timer(function() 
//{
	//updateStatus();
//});

function fldConfiguration_onClick()
{
}

function fldOptionSet_onClick()
{
	var strOptionSetCode = $('input[name=fldOptionSet]:checked').val();

	g_strOptionCode = '';
	g_strUpdateNumber = '';
	g_arrTaskSets = [];
	
	$('.fldOption', '#divOptionPanel').removeAttr('checked');
	$('.fldUpdateNumber', '#divBackupPanel').removeAttr('checked');
	$('.fldUpdateNumber', '#divConfigurationPanelProd').removeAttr('checked');
	$('.fldUpdateNumber', '#divConfigurationPanelUAT').removeAttr('checked');
	$('.fldUpdateNumber', '#divConfigurationPanelDev').removeAttr('checked');
	$('.fldUpdateNumber', '#divUpdatePanel').removeAttr('checked');

	$('.tabOption', '#divOptionPanel').hide();
	$('.tabOption' + strOptionSetCode, '#divOptionPanel').show();
	
	$('#divBackupPanel').hide();
	$('#divConfigurationPanelProd').hide();
	$('#divConfigurationPanelUAT').hide();
	$('#divConfigurationPanelDev').hide();
	$('#divUpdatePanel').hide();
	$('.divProcessOutline', '#divOutputPanel').hide();
	$('.divProcessCommandOption', '#divOutputPanel').hide();
	$('#cmdSubmit').hide();
}

function fldOption_onClick()
{
	var strProcessOutline = '';
	var intI;

	g_strOptionCode = $('input[name=fldOption]:checked').val();

	// can be automated
	var blnFlagAutomate = false;
	// requires an update to be available
	g_blnFlagBackup = false;
	g_blnFlagConfigurationProd = false;
	g_blnFlagConfigurationUAT = false;
	g_blnFlagConfigurationDev = false;
	g_blnFlagUpdate = false;
	g_arrTaskSets = [];

	if (g_strOptionCode.length > 0)
	{
		var arrOptionSets = g_objConfig.optionSets;
		var arrTaskSets = [];
		var arrFlags = [];
		
		// find option within all optionsets
		for (intI = 0; intI < arrOptionSets.length; intI++)
		{
			var objOptions = arrOptionSets[intI].options;
			for (var intJ = 0; intJ < objOptions.length; intJ++)
			{
				if (objOptions[intJ].code == g_strOptionCode)
				{
					arrTaskSets = objOptions[intJ].taskSets;
					arrFlags = objOptions[intJ].flags;
				}
			}
		}
		
		// create a process outline from all tasksets within the found option's taskset
		var objTaskSets = g_objConfig.taskSets;
		if (arrTaskSets.length > 0)
		{
			strProcessOutline = '<table><thead><tr><td width="40px"></td><td><b>Description</b></td><td width="40px"></td><td><b>Comments</b></td></tr></thead><tbody>';
			for (intI = 0; intI < arrTaskSets.length; intI++)
			{
				var strTaskSetCode = arrTaskSets[intI];
				try
				{
					objTaskSets[strTaskSetCode].code = strTaskSetCode;
					g_arrTaskSets.push(objTaskSets[strTaskSetCode]);
					strProcessOutline = strProcessOutline + '<tr>';
					strProcessOutline = strProcessOutline + '<td>' + (intI + 1) + '</td>';
					strProcessOutline = strProcessOutline + '<td>' + objTaskSets[strTaskSetCode].description + '</td>';
					strProcessOutline = strProcessOutline + '<td></td>';
					strProcessOutline = strProcessOutline + '<td>' + objTaskSets[strTaskSetCode].comments + '</td>';
					strProcessOutline = strProcessOutline + '</tr>';
					
					strProcessOutline = strProcessOutline + '<tr>';
					strProcessOutline = strProcessOutline + '<td></td>';
					strProcessOutline = strProcessOutline + '<td colspan="3" class="idTaskSet idTaskSet_' + (intI + 1) + '"><div class="ge-done" style="white-space:nowrap; overflow:hidden; text-align:center; color:white; background-color:blue; width:0%;">&nbsp;</div><div class="ge-todo" style="background-color:gray; width:100%;"></div></td>';
					strProcessOutline = strProcessOutline + '</tr>';
				}
				catch (e)
				{
					alert("Invalid Taskset Code: " + strTaskSetCode);
				}
			}
			strProcessOutline = strProcessOutline + '</tbody></table>';
		}

		// check the flags
		if (arrFlags !== undefined)
		{
			for (intI = 0; intI < arrFlags.length; intI++)
			{
				var strFlag = arrFlags[intI];
				if (strFlag === 'automate')
				{
					blnFlagAutomate = true;
				}
				else if (strFlag === 'backup')
				{
					g_blnFlagBackup = true;
				}
				else if (strFlag === 'configuration-prod')
				{
					g_blnFlagConfigurationProd = true;
				}
				else if (strFlag === 'configuration-uat')
				{
					g_blnFlagConfigurationUAT = true;
				}
				else if (strFlag === 'configuration-dev')
				{
					g_blnFlagConfigurationDev = true;
				}
				else if (strFlag === 'update')
				{
					g_blnFlagUpdate = true;
				}
			}
		}
	}
		
	if (strProcessOutline.length > 0)
	{
		var blnShowSubmit = false;
		
		strProcessCommandOption = g_strCWD + '/process.php?optioncode=' + g_strOptionCode;
		
		// show description
		$('.divProcessOutline', '#divOutputPanel').show();
		$('.divProcessOutlineContent', '#divOutputPanel').html(strProcessOutline);

		if (blnFlagAutomate)
		{
			$('.divProcessCommandOption', '#divOutputPanel').show();
			$('.divProcessCommandOptionContent', '#divOutputPanel').html(strProcessCommandOption);
		}
		else
		{
			$('.divProcessCommandOption', '#divOutputPanel').hide();
		}
		
		$('#divBackupPanel').hide();
		$('#divConfigurationPanelProd').hide();
		$('#divConfigurationPanelUAT').hide();
		$('#divConfigurationPanelDev').hide();
		$('#divUpdatePanel').hide();
		if (g_blnFlagBackup)
		{
			$('#divBackupPanel').show();
		}
		
		if (g_blnFlagConfigurationProd)
		{
			$('#divConfigurationPanelProd').show();
		}
		
		if (g_blnFlagConfigurationUAT)
		{
			$('#divConfigurationPanelUAT').show();
		}
		
		if (g_blnFlagConfigurationDev)
		{
			$('#divConfigurationPanelDev').show();
		}
		
		if (g_blnFlagUpdate)
		{
			$('#divUpdatePanel').show();
		}

		showSubmit();
	}
	else
	{
		// hide output
		$('#cmdSubmit').hide();
		$('.divProcessOutline', '#divOutputPanel').hide();
		$('.divProcessCommandOption', '#divOutputPanel').hide();
	}
}

function showSubmit()
{
	var blnShowSubmit = false;

	if (g_blnFlagBackup || g_blnFlagConfigurationProd || g_blnFlagConfigurationUAT || g_blnFlagConfigurationDev || g_blnFlagUpdate)
	{
		blnShowSubmit = ((g_strOptionCode.length > 0) && (g_strUpdateNumber.length > 0));
	}
	else
	{
		blnShowSubmit = (g_strOptionCode.length > 0);
	}
		
	if (blnShowSubmit)
	{
		$('.cmdProcessing', '#cmdSubmit').hide();
		$('.cmdDone', '#cmdSubmit').hide();
		$('.cmdNext', '#cmdSubmit').hide();
		$('.cmdError', '#cmdSubmit').hide();
		$('.cmdSubmit', '#cmdSubmit').show();
		$('#cmdSubmit').show();
	}
	else
	{
		$('#cmdSubmit').hide();
	}
}

function fldUpdateNumber_onClick()
{
	g_strUpdateNumber = $('input[name=fldUpdateNumber]:checked').val();
	//$('.idTaskSet', '#divOutputPanel').html('');
	showSubmit();
}

function cmdNext_onClick()
{
	location.reload();
}

function cmdDone_onClick()
{
	var strLocation = location;
	strLocation = strLocation + '../';
	location = strLocation;
}

function getNextTaskSetNumber(intTask_a)
{
	var intResult = 0;
	var intTask = 0;
	
	for (var intI = 0; intI < g_arrTaskSets.length; intI++)
	{
		var arrTaskSet = g_arrTaskSets[intI];
		for (var intJ = 0; intJ < arrTaskSet.tasks.length; intJ++)
		{
			if (intTask == intTask_a)
			{
				intResult = intI;
			}
			intTask++;
		}
	}
	
	return intResult;
}

function getTaskSetTaskNumber(intTask_a)
{
	var intResult = 0;
	var intTask = 0;
	var intTaskSetTask = 0;
	
	for (var intI = 0; intI < g_arrTaskSets.length; intI++)
	{
		var arrTaskSet = g_arrTaskSets[intI];
		intTaskSetTask = 0;
		for (var intJ = 0; intJ < arrTaskSet.tasks.length; intJ++)
		{
			if (intTask == intTask_a)
			{
				intResult = intTaskSetTask;
			}
			intTask++;
			intTaskSetTask++;
		}
	}
	
	return intResult;
}

function getNextTask(intTask_a)
{
	var objResult = '';
	var intTask = 0;
	
	for (var intI = 0; intI < g_arrTaskSets.length; intI++)
	{
		var arrTaskSet = g_arrTaskSets[intI];
		for (var intJ = 0; intJ < arrTaskSet.tasks.length; intJ++)
		{
			if (intTask == intTask_a)
			{
				objResult = arrTaskSet.tasks[intJ];
			}
			intTask++;
		}
	}
	
	return objResult;
}

function addSubProcessRow(intTaskSet_a)
{
	var strSubProcess = '<tr>';
	strSubProcess = strSubProcess + '<td></td>';
	strSubProcess = strSubProcess + '<td colspan="3" class="idTaskSet idTaskSet_' + (intTaskSet_a + 1) + '_' + g_intSubTaskRowID + '"><div class="ge-done" style="white-space:nowrap; overflow:hidden; text-align:center; color:white; background-color:skyblue; width:0%;">&nbsp;</div><div class="ge-todo" style="background-color:gray; width:100%;"></div></td>';
	strSubProcess = strSubProcess + '</tr>';
	
	if (g_intSubTaskRowID === 0)
	{
		$('.idTaskSet_' + (intTaskSet_a + 1), '#divOutputPanel').closest('tr').after(strSubProcess);
	}
	else
	{
		$('.idTaskSet_' + (intTaskSet_a + 1) + '_' + (g_intSubTaskRowID - 1), '#divOutputPanel').closest('tr').after(strSubProcess);
	}
}

function cmdSubmit_onClick()
{
	var arrSubTasks = [];
	var intSubTaskCount = 0;
	var intSubTaskNext = 0;
	var intTaskCount = 0;
	g_strAlert = "";
	g_intSubTaskRowID = 0;
	g_blnInSubTasks = false;
	g_strSubTasks = "";
	
	for (var intI = 0; intI < g_arrTaskSets.length; intI++)
	{
		var arrTaskSet = g_arrTaskSets[intI];
		intTaskCount += arrTaskSet.tasks.length;
	}
	
	if (intTaskCount > 0)
	{
		$('.cmdSubmit', '#cmdSubmit').hide();
		$('.cmdDone', '#cmdSubmit').hide();
		$('.cmdNext', '#cmdSubmit').hide();
		$('.cmdError', '#cmdSubmit').hide();
		$('.cmdProcessing', '#cmdSubmit').show();
		//g_objTimer.set({ time : 1000, autostart : true });
		
		g_intStartTime = Date.now();
		g_intSubTaskStartTime = Date.now();
		var intNow;
		var intTaskSetPrevious = -1;
		
		function processNextTask()
		{
			var intTaskSet = getNextTaskSetNumber(intNext);
			var objTaskSet = g_arrTaskSets[intTaskSet];
			var intTaskSetTask = getTaskSetTaskNumber(intNext);
			var strTaskSetCode = objTaskSet.code;
			var objTask = getNextTask(intNext);
			var strTaskDescription = objTask.exec;
			var intChunk = 0;
			var intChunkOf = 0;
			
			if (intTaskSet != intTaskSetPrevious)
			{
				g_intSubTaskRowID = 0;
			}
			intTaskSetPrevious = intTaskSet;
			
			if (g_blnInSubTasks)
			{
				objTask = arrSubTasks[intSubTaskNext];
				intChunk = objTask.chunk;
				intChunkOf = objTask.chunkof;
				if (intChunkOf <= 1)
				{
					strTaskDescription = objTask.tablename;
				}
				else
				{
					strTaskDescription = objTask.tablename + " " + intChunk + " of " + intChunkOf;
				}
			}
			else
			{
				if (strTaskDescription === 'exportData')
				{
					objTask.exec = 'getSchemaElements';
					g_strSubTasks = "exportData";
				}
				else if (strTaskDescription === 'importData')
				{
					objTask.exec = 'getExportedFiles';
					g_strSubTasks = "importData";
				}
				strTaskDescription = objTask.exec;
			}

			var strTask = JSON.stringify(objTask);
			var strURL = 'process.php?optioncode=' + encodeURIComponent(g_strOptionCode);
			if (strTask.length > 0)
			{
				strURL = strURL + '&task=' + encodeURIComponent(strTask);
			}
			if (g_strUpdateNumber.length > 0)
			{
				strURL = strURL + '&update=' + encodeURIComponent(g_strUpdateNumber);
			}
			
			if (g_blnInSubTasks)
			{
				intNow = Date.now();
				intTime = (intNow - g_intSubTaskStartTime) / 1000;
				ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intSubTaskNext + 1),"tasks":intSubTaskCount,"statusmsg":"","alertmsg":"","processtime":intTime}]);
			}
			else
			{
				intNow = Date.now();
				intTime = (intNow - g_intStartTime) / 1000;
				ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":"","alertmsg":"","processtime":intTime}]);
			}
			
			if (g_blnInSubTasks)
			{
				$('.ge-task').css("background-color", "green");
				$('.ge-subtask').css("background-color", "green");
			}
			else
			{
				$('.ge-task').css("background-color", "green");
				$('.ge-subtask').css("background-color", "red");
			}
			$.ajax(
			{
				url: strURL,
				dataType : 'json',
				success: function(objResult_a)
				{
					$('.ge-task').css("background-color", "red");
					$('.ge-subtask').css("background-color", "red");
					
					var strSubTaskDescription = "";
					var blnNext = true;
					var objSubTask;
					var strFileName;
					var strTableName;
					var intRecordCount;
					var intChunkCountTemp;
					var intChunkCount;
					var intT;
					var intC;

					if (strTaskDescription === 'getExportedFiles')	// import logic is in here
					{
						intNow = Date.now();
						intTime = (intNow - g_intStartTime) / 1000;

						strSubTaskDescription = "importFile";
						ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strSubTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":objResult_a[0].statusmsg,"alertmsg":objResult_a[0].alertmsg,"processtime":intTime}]);

						//alert(JSON.stringify(objResult_a[0].result));
						addSubProcessRow(intTaskSet);
						arrSubTasks = [];
						for (intT = 0; intT < objResult_a[0].result.length; intT++)
						{
							objFile = objResult_a[0].result[intT];
							objSubTask = JSON.parse(JSON.stringify(objTask));
							
							objSubTask.exec = "importFile";
							objSubTask.tablename = objFile.tablename;
							objSubTask.source = objFile.filename;
							objSubTask.chunk = objFile.chunk;
							objSubTask.chunkof = objFile.chunkof;
							arrSubTasks.push(objSubTask);
						}
						
						intSubTaskCount = arrSubTasks.length;
						if (intSubTaskCount > 0)
						{
							blnNext = false;
							intSubTaskNext = 0;
							g_blnInSubTasks = true;
							
							setTimeout(processNextTask);
						}
						else
						{
							blnNext = true;
							g_blnInSubTasks = true;
						}
					}
					else if (strTaskDescription === 'getSchemaElements')	// export logic is in here
					{
						intNow = Date.now();
						intTime = (intNow - g_intStartTime) / 1000;

						strSubTaskDescription = "";
						if (g_strSubTasks === "exportData")
						{
							strSubTaskDescription = "exportTable";
						}
						ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strSubTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":objResult_a[0].statusmsg,"alertmsg":objResult_a[0].alertmsg,"processtime":intTime}]);

						//alert(JSON.stringify(objResult_a[0].result));
						addSubProcessRow(intTaskSet);
						arrSubTasks = [];
						for (intT = 0; intT < objResult_a[0].result.tables.length; intT++)
						{
							strTableName = objResult_a[0].result.tables[intT].tablename;
							intRecordCount = objResult_a[0].result.tables[intT].recordcount;
							intChunkCount = 1;
							if (objTask.chunksize > 0)
							{
								intChunkCountTemp = intRecordCount / objTask.chunksize;
								intChunkCount = parseInt(intChunkCountTemp, 10);
								if (intChunkCountTemp > intChunkCount)
								{
									intChunkCount++;
								}
							}
							
							if (getFlag(objTask.flags, 'data'))
							{
								for (intC = 1; intC <= intChunkCount; intC++)
								{
									objSubTask = JSON.parse(JSON.stringify(objTask));
									
									if (g_strSubTasks === "exportData")
									{
										objSubTask.exec = "exportTable";
									}
									objSubTask.tablename = strTableName;
									objSubTask.chunk = intC;
									objSubTask.chunkof = intChunkCount;
									objSubTask.chunksize = objTask.chunksize;
									arrSubTasks.push(objSubTask);
								}
							}

							if (getFlag(objTask.flags, 'schema'))
							{
								objSubTask = JSON.parse(JSON.stringify(objTask));
								
								if (g_strSubTasks === "exportData")
								{
									objSubTask.exec = "exportTable";
								}
								objSubTask.tablename = strTableName;
								objSubTask.chunk = 0;
								objSubTask.chunkof = 0;
								objSubTask.chunksize = 0;
								arrSubTasks.push(objSubTask);
							}
						}
						
						intSubTaskCount = arrSubTasks.length;
						if (intSubTaskCount > 0)
						{
							blnNext = false;
							intSubTaskNext = 0;
							g_blnInSubTasks = true;
							
							setTimeout(processNextTask);
						}
						else
						{
							blnNext = true;
							g_blnInSubTasks = true;
						}
					}
					
					if (blnNext)
					{
						blnNext = true;
						if (g_blnInSubTasks)
						{
							intSubTaskNext++;
							if ((objResult_a[0].statusmsg.length === 0) && (intSubTaskNext < intSubTaskCount))
							{
								setTimeout(processNextTask);
								blnNext = false;
							}
							else
							{
								intNow = Date.now();
								intTime = (intNow - g_intSubTaskStartTime) / 1000;
								strTaskDescription = objTask.description + ' completed';
								ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intSubTaskNext + 1),"tasks":intSubTaskCount,"statusmsg":"","alertmsg":"","processtime":intTime}]);
								g_blnInSubTasks = false;
								g_strSubTasks = "";
								g_intSubTaskRowID++;
							}
						}
						
						if (blnNext)
						{
							intNext++;
							if ((objResult_a[0].statusmsg.length === 0) && (intNext < intTaskCount))
							{
								setTimeout(processNextTask);
							}
							else
							{
								intNow = Date.now();
								intTime = (intNow - g_intStartTime) / 1000;
								ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":objResult_a[0].statusmsg,"alertmsg":objResult_a[0].alertmsg,"processtime":intTime}]);
								ajaxSuccess(objResult_a);
							}
						}
					}
				},
				error: function(objResult_a)
				{
					$('.ge-task').css("background-color", "red");
					$('.ge-subtask').css("background-color", "red");
					
					console.log(JSON.stringify(objResult_a));
					alert(JSON.stringify(objResult_a));	// uncomment to show PHP syntax errors
					intNow = Date.now();
					intTime = (intNow - g_intStartTime) / 1000;
					if (objResult_a == undefined)
					{
						ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":"Undefined Error","alertmsg":"","processtime":intTime}]);
					}
					else
					{
						ajaxStatus([{"optioncode":g_strOptionCode,"tasksetname":strTaskSetCode,"tasksetdescription":objTaskSet.description,"taskdescription":strTaskDescription,"taskset":(intTaskSet + 1),"tasksets":g_arrTaskSets.length,"task":(intTaskSetTask + 1),"tasks":objTaskSet.tasks.length,"statusmsg":objResult_a[0].statusmsg,"alertmsg":objResult_a[0].alertmsg,"processtime":intTime}]);
					}
				}
			});
		}
		
		intNext = 0;
		setTimeout(processNextTask);
	}
}

function ajaxError(objResult)
{
	//g_objTimer.stop();
	$('.cmdSubmit', '#cmdSubmit').hide();
	$('.cmdProcessing', '#cmdSubmit').hide();
	$('.cmdDone', '#cmdSubmit').hide();
	$('.cmdError', '#cmdSubmit').show();
	$('.cmdNext', '#cmdSubmit').show();
}

function ajaxSuccess(objResult)
{
	//g_objTimer.stop();
	updateStatus();
	$('.cmdSubmit', '#cmdSubmit').hide();
	$('.cmdProcessing', '#cmdSubmit').hide();
	$('.cmdError', '#cmdSubmit').hide();
	$('.cmdDone', '#cmdSubmit').show();
	$('.cmdNext', '#cmdSubmit').show();
}

function ajaxStatus(objResult)
{
	// eg: [{"optioncode":"BDO","tasksetname":"backupDatabase","tasksetdescription":"backup database","taskdescription":"","taskset":1,"tasksets":3,"task":1,"tasks":4,"statusmsg":"","alertmsg":"","processtime":""}]
	var strStatus = objResult[0].statusmsg;
	var intTaskSet = objResult[0].taskset;
	var intTaskSets = objResult[0].tasksets;
	var strTaskDescription = objResult[0].taskdescription;
	var intTask = objResult[0].task;
	var intTasks = objResult[0].tasks;
	var strProcessTime = objResult[0].processtime;
	var strAlert = objResult[0].alertmsg;

	for (var intI = 1; intI <= intTaskSet; intI++)
	{
		var strTaskSet = intI;
		if (intI < intTaskSet)
		{
			//$('.idTaskSet_' + strTaskSet, '#divOutputPanel').html('100');
			doNothing();
		}
		else
		{
			var intPercentage = Math.ceil(intTask / intTasks * 100);

			if (g_blnInSubTasks)
			{
				if (intPercentage >= 100)
				{
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').css('width', '100%');
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').css('background-color', 'green');
					//$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').html(intPercentage + '% (' + strProcessTime + 's)');
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').html(strTaskDescription);
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-todo', '#divOutputPanel').css('width', '0%');
					g_intSubTaskStartTime = Date.now();
				}
				else
				{
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').css('width', intPercentage + '%');
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-done', '#divOutputPanel').html(strTaskDescription);
					$('.idTaskSet_' + strTaskSet + '_' + g_intSubTaskRowID + ' .ge-todo', '#divOutputPanel').css('width', (100-intPercentage) + '%');
				}
			}
			else
			{
				if (intPercentage >= 100)
				{
					//$('.idTaskSet_' + strTaskSet, '#divOutputPanel').html(intPercentage + ' (' + strProcessTime + 's)');
					$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').css('width', '100%');
					$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').css('background-color', 'green');
					$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').html(intPercentage + '% (' + strProcessTime + 's)');
					$('.idTaskSet_' + strTaskSet + ' .ge-todo', '#divOutputPanel').css('width', '0%');
					g_intStartTime = Date.now();
				}
				else
				{
					//$('.idTaskSet_' + strTaskSet, '#divOutputPanel').html(intPercentage + ' (' + strProcessTime + 's), ' + strTaskDescription);
					$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').css('width', intPercentage + '%');
					$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').html(intPercentage + '% (' + strProcessTime + 's), ' + strTaskDescription);
					$('.idTaskSet_' + strTaskSet + ' .ge-todo', '#divOutputPanel').css('width', (100-intPercentage) + '%');
				}
			}
		}
	}
	
	if (strAlert.length > 0)
	{
		g_strAlert = strAlert;
	}
	
	if (g_strAlert.length > 0)
	{
		$('#divAlert').html('<br /><br /><font style="color:red;"><b>Alert: ' + g_strAlert + '</b></font>');
	}

	if (strStatus.length > 0)
	{
		$('.idTaskSet_' + strTaskSet + ' .ge-done', '#divOutputPanel').css('background-color', 'red');
		
		//var arrStatus = strStatus.split('.');
		//var strOutput = '<b class="gs-alert">ERROR at ' + strStatus + '</b>';
		//$('.idTaskSet_' + arrStatus[0], '#divOutputPanel').html(strOutput);
		$('#divAlert').html('<br /><br /><font style="color:red;"><b>' + strStatus + '</b></font>');
		ajaxError(objResult);
	}
}

function getFlag(arrFlags_a, strFlag_a)
{
	blnResult = false;

	for (intF = 0; intF < arrFlags_a.length; intF++)
	{
		if (arrFlags_a[intF] == strFlag_a)
		{
			blnResult = true;
		}
	}
	
	return blnResult;
}

function updateStatus()
{
	return; // we are not using AJAX for status updates currently
	//var strURL = 'process.php?status=true';

	//$.ajax(
	//{
	  //url: strURL,
	  //dataType : 'json',
	  //success: function(objResult_a)
	  //{
		  //ajaxStatus(objResult_a);
	  //},
	  //error: doNothing
	//});
}

function doNothing()
{
	// do nothing
}
