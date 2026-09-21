function dash_wgtChart(objOS_a, strFormID_a, objParameters_a) 
{
    var os = objOS_a;
    var m_objThis = this;
    var m_strFormID = strFormID_a;
    var m_strTarget = getGUID();
    
    var m_objChart = null;
	var m_objCanvas = null;
    var m_objChartData = objParameters_a.chart;

    this.refresh = function(objNewData_a) 
	{
        if (m_objChart) 
		{
            m_objChart.destroy();
        }

        if (objNewData_a) 
		{
            m_objChartData = objNewData_a;
        }

        m_objChart = new Chart(m_objCanvas, m_objChartData);
    };

    this.render = function(strFormElementClass_a) 
	{
        if (m_objChart) 
		{
            m_objChart.destroy();
        }

        m_objCanvas = os.element(m_strFormID, strFormElementClass_a);
        m_objChart = new Chart(m_objCanvas, m_objChartData);
    };
}
