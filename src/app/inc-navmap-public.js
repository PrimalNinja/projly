function getNavMapPublic(objOS_a)
{
	var os = objOS_a;
	var arrMapRow;

	//Francis:  No need for public admin nav at this stage
	var arrMap = [
		{
			location : 'root',
			title : 'Menu',
			layout : [
				[]
			]
		}
	];
	
	return arrMap;
}
