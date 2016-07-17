<?php

$serverinfo=$sqlite->search("select * from server where id='$id'");
$serverinfo=$serverinfo[0];
$serverpools=$sqlite->search("select distinct pool.name,pool.id from poolservermap left join pool on poolservermap.poolid = pool.id where poolservermap.serverid = $id");
$rackinfo=$sqlite->search(" select rack.name,rack.id,ru from rackservermap left join rack on rackservermap.rackid = rack.id where rackservermap.serverid = $id");
if($serverinfo){
    echo "<img src='./images/icons/linux.png' style='opacity:.7;float:left;margin-right:15px'/>";
    echo "<div style='padding-left:70px'>";
    echo "<h2>".$serverinfo['name']."</h2><hr>";
    if($serverpools){
        foreach($serverpools as $pool){
            echo "<a href='./view.php?type=pool&id=".$pool['id']."' class='floatbox'>".$pool['name']."</a>";
        }
    }
    echo "<br />";

    echo "<table class='stats'>";
    if($rackinfo){
        $rackinfo=$rackinfo[0];
        echo "<tr><th>Location</th><td><a href='./view.php?type=rack&id=".$rackinfo['id']."'>".$rackinfo['name']."</a> Slot ".$rackinfo['ru']."</td></tr>";
    }

    foreach($serverinfo as $k=>$server){
        if($k <> 'id' && $k <> 'name') {
            echo "<tr><th>" . $k . "</th><td>" . $server . "</td></tr>";
        }
    }
    echo "</table>";
    echo "</div>";
}

