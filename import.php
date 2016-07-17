<?php

include "connectors.php";
include "config.php";

$clustodb = new clustodb($clustodbconfig);
$sqlite = new sqlite($sqlite);


$sqlitetables=array();

$sqlitetables["server"]['name']="server";
$sqlitetables["server"]['cols'][]=array('name'=>'id','type'=>'INTEGER PRIMARY KEY');
$sqlitetables["server"]['cols'][]=array('name'=>'name','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'chef_role','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'serial','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'dracip','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'model','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'aws_ami','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'centosver','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'drivetype','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'drivecount','type'=>'INTEGER');
$sqlitetables["server"]['cols'][]=array('name'=>'aws_instance_id','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'systemip','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'memory','type'=>'INTEGER');
$sqlitetables["server"]['cols'][]=array('name'=>'proc','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'cores','type'=>'INTEGER');
$sqlitetables["server"]['cols'][]=array('name'=>'cpus','type'=>'INTEGER');
$sqlitetables["server"]['cols'][]=array('name'=>'env','type'=>'TEXT');
$sqlitetables["server"]['cols'][]=array('name'=>'tengb','type'=>'BOOL');


$sqlitetables["pool"]["name"]="pool";
$sqlitetables["pool"]['cols'][]=array('name'=>'id','type'=>'INTEGER PRIMARY KEY');
$sqlitetables["pool"]['cols'][]=array('name'=>'name','type'=>'TEXT');
$sqlitetables["pool"]['cols'][]=array('name'=>'type','type'=>'TEXT');

$sqlitetables["poolservermap"]["name"]="poolservermap";
$sqlitetables["poolservermap"]['cols'][]=array('name'=>'id','type'=>'INTEGER PRIMARY KEY');
$sqlitetables["poolservermap"]['cols'][]=array('name'=>'poolid','type'=>'INTEGER NOT NULL');
$sqlitetables["poolservermap"]['cols'][]=array('name'=>'serverid','type'=>'INTEGER NOT NULL');

$sqlitetables["rack"]['name']="rack";
$sqlitetables["rack"]['cols'][]=array('name'=>'id','type'=>'INTEGER PRIMARY KEY');
$sqlitetables["rack"]['cols'][]=array('name'=>'name','type'=>'TEXT');

$sqlitetables["rackservermap"]["name"]="rackservermap";
$sqlitetables["rackservermap"]['cols'][]=array('name'=>'id','type'=>'INTEGER PRIMARY KEY');
$sqlitetables["rackservermap"]['cols'][]=array('name'=>'rackid','type'=>'INTEGER NOT NULL');
$sqlitetables["rackservermap"]['cols'][]=array('name'=>'serverid','type'=>'INTEGER NOT NULL');
$sqlitetables["rackservermap"]['cols'][]=array('name'=>'ru','type'=>'INTEGER NOT NULL');

//Create Server Table
foreach($sqlitetables as $table){
    $q="DROP TABLE IF EXISTS '".$table['name']."';";
    $sqlite->search($q);
    $q="CREATE TABLE '".$table['name']."' (";
    foreach($table['cols'] as $col){
        $q.="'".$col['name']."'  ".$col['type'].",";
    }
    $q=rml($q);
    $q.=");";
    //echo "<hr>$q";
    $sqlite->search($q);
}

foreach($sqlitetables as $table) {
    $q = "delete from '" . $table['name'] . "';";
    $sqlite->search($q);
}


$rawservers=$clustodb->search("
select entities.name,entities.driver,entity_attrs.* from
entity_attrs
left join
entities on entity_attrs.entity_id = entities.entity_id
where entities.type = 'server'

order by entities.name asc");

$servers=array();
foreach($rawservers as $server){
    $servers[$server['name']]['name'] = $server['name'];
    $servers[$server['name']]['id'] = $server['entity_id'];
    $servers[$server['name']]['model'] = $server['driver'];
    $val="";
    switch ( $server['datatype']) {
        case "int":
            $val=$server['int_value'];
            break;
        case "string":
            $val=$server['string_value'];
            break;
        default:
            $val=$server;

    }
    $servers[$server['name']]['attribs'][$server['key']."_".$server['subkey']][$server['number']] = $val;
}


foreach($servers as $server){
    $id=$server['id'];
    $name=$server['name'];
    $model=$server['model'];
    $serial=@array_pop($server['attribs']['system_serial']);
    $dracip=@array_pop($server['attribs']['system_drac_ip']);
    $systemip=@array_pop($server['attribs']['ip_ipstring']);
    $chefrole=@array_pop($server['attribs']['chef_role']);
    $aws_instance_id=@array_pop($server['attribs']['aws_instance_id']);
    $aws_ami=@array_pop($server['attribs']['aws_ami']);
    $centosver=@array_pop($server['attribs']['system_centosversion']);
    $drivetype=@array_pop($server['attribs']['system_drive_types']);
    $drivecount=@count($server['attribs']['disk_id']);
    $systemip=@array_pop($server['attribs']['system_ipaddress']);
    $memchips=str_replace(" MB","",@array_pop($server['attribs']['memory_size']));
    $chipcount=count($server['attribs']['memory_size']);
    $chipcount++;
    $memory=($memchips*$chipcount)/1024;
    $proc=@array_pop($server['attribs']['processor_version']);
    $cores=@array_pop($server['attribs']['system_cpucorecount']);
    $cpus=@array_pop($server['attribs']['system_cpucount']);
    if(@$server['attribs']['port-nic-sfp_connection']){
        $tengb=1;
    }else{
        $tengb=0;
    }
    if($name=="s0439"){

        echo "<pre>";
        print_r($server);
        echo "</pre>";
    }


    $insq="insert into server
            ('id','name','serial','dracip','model','chef_role','aws_ami','aws_instance_id','centosver','drivetype','drivecount','systemip','memory','cores','proc','cpus','tengb')
            values
            ('$id','$name','$serial','$dracip','$model','$chefrole','$aws_ami','$aws_instance_id','$centosver','$drivetype','$drivecount','$systemip','$memory','$cores','$proc','$cpus','$tengb')";
    $sqlite->search($insq);
}




$enttypemap=array();
$imports=array();
$types=array();


$rawpools=$clustodb->search("select * from entities where type = 'pool'");
foreach($rawpools as $pool){
    $q="insert into pool ('id','name','type') values (".$pool['entity_id'].",'".$pool['name']."','".$pool['driver']."');";
    $sqlite->search($q);
}

$q="
        SELECT
        entities.name,
        e2.name as servername,
        e2.entity_id as serverid,
        entities.entity_id as poolid

        FROM
        entity_attrs
        LEFT JOIN entities
        on entity_attrs.entity_id = entities.entity_id
        left join entities as e2
        on entity_attrs.relation_id = e2.entity_id
        WHERE

        entities.type ='pool'
        and e2.type ='server'
        order by servername asc
        ";

$poolmaps=$clustodb->search($q);
foreach($poolmaps as $map){
    $poolid=$map['poolid'];
    $serverid=$map['serverid'];
    $q="insert into poolservermap (poolid,serverid) values ('$poolid','$serverid')";
    $sqlite->search($q);
}

$rawracks=$clustodb->search("select * from entities where driver = 'basicrack'");
foreach($rawracks as $rack){
    $rackid=$rack['entity_id'];
    $rackname=$rack['name'];
    $q="insert into rack (id,name) values ($rackid,'$rackname')";;
    $sqlite->search($q);
}

$rackmapq="
        SELECT
        entities.name,
        e2.name as servername,
        e2.entity_id as serverid,
        entities.entity_id as rackid,
	entity_attrs.number as ru

        FROM
        entity_attrs
        LEFT JOIN entities
        on entity_attrs.entity_id = entities.entity_id
        left join entities as e2
        on entity_attrs.relation_id = e2.entity_id
        WHERE

        entities.type ='rack'
        and e2.type ='server'
        order by servername asc
        ";

$rackmaps=$clustodb->search($rackmapq);
foreach($rackmaps as $map){
    $rackid=$map['rackid'];
    $serverid=$map['serverid'];
    $ru=$map['ru'];
    $q="insert into rackservermap (rackid,serverid,ru) values ($rackid,$serverid,$ru)";
    //echo $q;
    $sqlite->search($q);
}


$envs=array('test','prod','development','stag','carp-work','unallocated');

foreach($envs as $env) {
    $updateq = "update server set env='$env' where id in (
                select poolservermap.serverid from pool
                left join poolservermap on pool.id = poolservermap.poolid
                where pool.name = '$env')";
    $sqlite->search($updateq);
}





function debug($ray){
    echo "<pre>";
    print_r($ray);
    echo "</pre>";
}



function rml($data){
    $final = substr_replace(trim($data), "", -1);
    return $final;
}

