<?php

$mastertitle="Clusto Catalog";
$tagline="Inventory Reporting";
$userlogin=false;

$clustodbconfig['host']='localhost';
$clustodbconfig['port']='5432';
$clustodbconfig['dbuser']='clusto';
$clustodbconfig['dbpass']='password';
$clustodbconfig['database']='clusto';

$sqlite['filename']="/Applications/XAMPP/xamppfiles/htdocs/cluster/cluster.sqlite";
$sqlite['dbname']="cluster";

$filteroptions=array();
$filteroptions['server']['Name']=array('disp'=>'Name',    'tab'=>'server',    'col'=>'name');
$filteroptions['server']['Serial']=array('disp'=>'Serial',      'tab'=>'server',    'col'=>'serial');
$filteroptions['server']['Model']=array('disp'=>'Model',      'tab'=>'server',    'col'=>'model');
$filteroptions['server']['Chefrole']=array('disp'=>'Chefrole',      'tab'=>'server',    'col'=>'chef_role');
$filteroptions['server']['Dracip']=array('disp'=>'Dracip',      'tab'=>'server',    'col'=>'dracip');
$filteroptions['server']['Systemip']=array('disp'=>'Systemip',      'tab'=>'server',    'col'=>'systemip');
$filteroptions['server']['Centosver']=array('disp'=>'Centosver',      'tab'=>'server',    'col'=>'centosver');
$filteroptions['server']['Drivetype']=array('disp'=>'Drivetype',      'tab'=>'server',    'col'=>'drivetype');
$filteroptions['server']['Drivecount']=array('disp'=>'Drivecount',      'tab'=>'server',    'col'=>'drivecount');
$filteroptions['server']['Memory']=array('disp'=>'Memory',      'tab'=>'server',    'col'=>'memory');
$filteroptions['server']['Proc']=array('disp'=>'Proc',      'tab'=>'server',    'col'=>'proc');
$filteroptions['server']['Cores']=array('disp'=>'Cores',      'tab'=>'server',    'col'=>'cores');
$filteroptions['server']['Pool']=array('disp'=>'Pool',    'tab'=>'pool',    'col'=>'name', 'tab2'=>'poolservermap',    'col2'=>'poolid' );
$filteroptions['server']['Type']=array('disp'=>'Type',      'tab'=>'s2',    'col'=>'servertype');
$filteroptions['server']['Rack']=array('disp'=>'Rack',      'tab'=>'rack',    'col'=>'name');
$filteroptions['server']['Raid']=array('disp'=>'Raid',      'tab'=>'server',    'col'=>'raid');


$displayoptions['server']['name']=array('disp'=>'Name',    'tab'=>'server',    'col'=>'name');
$displayoptions['server']['Serial']=array('disp'=>'Serial',    'tab'=>'server',    'col'=>'serial');
$displayoptions['server']['Model']=array('disp'=>'Model',      'tab'=>'server',    'col'=>'model');
$displayoptions['server']['Chefrole']=array('disp'=>'Chefrole',      'tab'=>'server',    'col'=>'chef_role');
$displayoptions['server']['Dracip']=array('disp'=>'Dracip',      'tab'=>'server',    'col'=>'dracip');
$displayoptions['server']['Systemip']=array('disp'=>'Systemip',      'tab'=>'server',    'col'=>'systemip');
$displayoptions['server']['Centosver']=array('disp'=>'Centosver ',      'tab'=>'server',    'col'=>'centosver');
$displayoptions['server']['Drivetype']=array('disp'=>'Drivetype',      'tab'=>'server',    'col'=>'drivetype');
$displayoptions['server']['Drivecount']=array('disp'=>'Drivecount',      'tab'=>'server',    'col'=>'drivecount');
$displayoptions['server']['Memory']=array('disp'=>'Memory',      'tab'=>'server',    'col'=>'memory');
$displayoptions['server']['Proc']=array('disp'=>'Proc',      'tab'=>'server',    'col'=>'proc');
$displayoptions['server']['Cores']=array('disp'=>'Cores',      'tab'=>'server',    'col'=>'cores');
$displayoptions['server']['Type']=array('disp'=>'Type',      'tab'=>'s2',    'col'=>'servertype');
$displayoptions['server']['Rack']=array('disp'=>'Rack',      'tab'=>'rack',    'col'=>'name');
$displayoptions['server']['Raid']=array('disp'=>'Raid',      'tab'=>'server',    'col'=>'raid');
$displayoptions['server']['Env']=array('disp'=>'Env',      'tab'=>'server',    'col'=>'env');
$displayoptions['server']['10GB']=array('disp'=>'10GB',      'tab'=>'server',    'col'=>'tengb');


$sqlitetables["server"]['cols'][]=array('name'=>'cpus','type'=>'INTEGER');
$sqlitetables["server"]['cols'][]=array('name'=>'env','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'tengb','type'=>'BOOL');
