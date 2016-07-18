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
    $servnameparts=explode("-",$poolinfo['name']);
    $lookupname=$servnameparts[0];
    echo "<a target='_blank' href='http://eng-docs.prod.urbanairship.com/docs/chef_configs/en/latest/".$lookupname."/OPSDOCS.html'>Docs</a> (worth a shot)";
    echo "<br /><br />";
    if($poolmembers){
        $envs=array();
        foreach($poolmembers as $box){
                $envs[$box['env']][]=$box;
         }
        foreach($envs as $env=>$boxes){
            echo "<h4 style='border-bottom:1px solid #aaa;margin-top:0px;margin-bottom:5px'>".strtoupper($env)."</h4>";
            foreach($boxes as $box){
                echo "<div class='floatbox'><a href='./view.php?type=server&id=".$box['id']."'>".$box['name']."<br /><span class='env ".$box['env']."'>".$box['env']."</span></a></div>";
            }
            echo "<br /><br />";
        }
    }
    echo "</div>";

}


?>




