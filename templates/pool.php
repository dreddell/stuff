<?php

$poolinfo=$sqlite->search("select * from pool where id='$id'");
$poolinfo=$poolinfo[0];
if($poolinfo){
    $membersq="select distinct server.name,server.id,server.env from server
        left join poolservermap on poolservermap.serverid = server.id
        where poolservermap.poolid = $id order by env asc";
    $poolmembers=$sqlite->search($membersq);

    echo "<h2>Pool</h2>";
    echo "<img src='./images/icons/connections.png' style='opacity:.7;float:left;margin-right:15px'/>";
    echo "<div style='padding-left:70px'>";
    echo "<h2>".$poolinfo['name']." [".count($poolmembers)."]</h2><hr>";
    if($poolmembers){
        foreach($poolmembers as $box){
            echo "<div class='floatbox'><a href='./view.php?type=server&id=".$box['id']."'>".$box['name']."<br /><span class='env ".$box['env']."'>".$box['env']."</span></a></div>";
        }
    }
    echo "</div>";

}


?>


