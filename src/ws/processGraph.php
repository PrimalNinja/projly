<?php

// NOTE: JUST A test file.
// can be deleted.

// you can run this directly the browser /fms-dev/ws/progressGraph.php
// then it will generate an svg file and will save the file in the same location /fms/ws


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL ^ E_NOTICE);

require_once('inc-env.php');
require_once('inc-constants.php');
require_once('inc-settings.php');
require_once('inc-temporaryvalues.php');
require_once('inc-app-' . APP_CODE . '.php');
require_once('inc-dbsettings-client.php');
require_once('inc-dbsettings-system.php');
require_once('modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php');

if (dependencies('utils/graph')) { 
    
    $graph = new ezcGraphPieChart();
    $graph->title = 'Mitsukibo Projects';

    $graph->data['Access statistics'] = new ezcGraphArrayDataSet( array(
    'A' => 12113,
    'B' => 10917,
    'C' => 1464,
    'D' => 652,
    'E' => 474
    ) );
    
    $graph->render( 800, 450, 'simple_piechart.svg' );
}
