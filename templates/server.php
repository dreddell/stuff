<?php

$serverinfo=$sqlite->search("select *,(case when server.name like 's%' then 'physical' when server.name like 'e%' then 'aws' else 'unknown' end) AS servertype from server where id='$id'");

$serverinfo=$serverinfo[0];
$serverpools=$sqlite->search("select distinct pool.name,pool.id,pool.type from poolservermap left join pool on poolservermap.poolid = pool.id where poolservermap.serverid = $id");
$rackinfo=$sqlite->search(" select rack.name,rack.id,ru from rackservermap left join rack on rackservermap.rackid = rack.id where rackservermap.serverid = $id");
if($serverinfo){
    echo "<img src='./images/icons/linux.png' style='opacity:.7;float:left;margin-right:15px'/>";
    echo "<div style='padding-left:70px'>";
    echo "<h2>".$serverinfo['name']."</h2><hr>";
    date_default_timezone_set("UTC");
    $to=(time()*1000);
    $from=($to - 60*60*3000);



    echo "<a style='text-decoration:none' target='_blank' href='https://grafana.prod.urbanairship.com/dashboard/db/server-overview?from=$from&to=$to&var-server=".$serverinfo['name']."'>
    <img style='vertical-align:middle;height:30px' src='./images/grafanalogo.png' /></a>&nbsp;&nbsp;&nbsp;&nbsp;";
    echo "<a style='text-decoration:none' target='_blank' href='https://monitoring.prod.urbanairship.com/check_mk/view.py?view_name=host&host=".$serverinfo['name']."'>
    <img style='vertical-align:middle;height:30px' src='./images/checkmklogo.png' /></a><br /><br />";
    echo "<table><tr><td style='width:30%;'>";
    echo "<h3 style='border-bottom:1px solid #aaa;margin:0px'>Server Attributes:</h3>";
    echo "<table class='stats zebra'><tbody>";
    if($serverinfo['servertype']=='aws'){
        $fields=array('aws_instance_id','aws_ami');
    }else {
        if($rackinfo){
            $rackinfo=$rackinfo[0];
            $server['location']="<a href='./view.php?type=rack&id=".$rackinfo['id']."'>".$rackinfo['name']."</a> Slot ".$rackinfo['ru'];
        }
        $fields = array('serial','model','dracip','drivetype','drivercount','memory','proc','cores','tengb');
    }
    $common=array('systemip','chef_role','centosver','env');
    $fields=array_merge($fields,$common);
    foreach($serverinfo as $k=>$server){
        if(in_array($k,$fields)) {
            $displayoptions['server']['name']=array('disp'=>'Name',    'tab'=>'server',    'col'=>'name');
            foreach($displayoptions['server'] as $option){
                if($option['col']==$k){
                    $disp=$option['disp'];
                }
            }
            if($k=='dracip'){
                $server="<a target='_blank' href='https://$server'>$server</a>";
            }
            if($k=='memory'){
                $server.=" GB";
            }
            if($k=='tengb'){
                if($server===1){
                    $server="Yes";
                }else{
                    $server="No";
                }
            }
            echo "<tr><th style='text-align:right'>".$disp."</th><td>" . $server . "</td></tr>";
        }
    }
    echo "</tbody></table>";

    echo "</td><td style='width:30%;padding-left:20px;'>";
    if($serverpools){
        echo "<h3 style='border-bottom:1px solid #aaa;margin:0px'>Pools:</h3>";
        foreach($serverpools as $pool){
            $mypools[$pool['type']][]=$pool;
        }
        foreach($mypools as $cat=>$pools){
            echo "<h4 style='border-bottom:1px solid #aaa'>".strtoupper(str_replace("_pool","",$cat))."</h4>";
            foreach($pools as $pool){
                echo "<a href='./view.php?type=pool&id=".$pool['id']."' class='floatbox'>".$pool['name']."</a>";
            }

        }
    }


    if($serverinfo['servertype']=='physical'){
        echo "</td><td style='width:30%;padding-left:20px;'>";
        echo "<h3 style='border-bottom:1px solid #aaa;margin:0px;margin-bottom:5px'>Potential Spares:</h3>";
        $spareq="select * from server where
                    drivecount = '".$serverinfo['drivecount']."'  and
                    model = '".$serverinfo['model']."' and
                    env != 'prod' and env != 'stag'
                    and memory >= '".$serverinfo['memory']."'
                    and cores = '".$serverinfo['cores']."'
                    and proc = '".$serverinfo['proc']."'
                    and drivecount = '".$serverinfo['drivecount']."'
         ";

        $spareresults=$sqlite->search($spareq);
        if($spareresults){
            foreach($spareresults as $server){
                echo "<a target='_blank' href='./view.php?type=server&id=".$server['id']."' class='floatbox'>".$server['name']."</a>";
            }
        }else{
            echo "No spares found";
        }

    }
    echo "</td></tr></table>";

    echo "</div>";
}


function debug($ray){
    echo "<pre>";
    print_r($ray);
    echo "</pre>";
}