<?php
$rackinfo=$rackservers=$sqlite->search("select * from rack where id = $id");


$rackservers=$sqlite->search("
select distinct rack.name as rackname,server.name as servername, server.id as serverid, server.model, rackservermap.ru from rackservermap
left join rack on rackservermap.rackid = rack.id
left join server on rackservermap.serverid = server.id
where rackservermap.rackid = $id order by ru desc");

if($rackinfo) {
    echo "<img src='./images/icons/rack.png' style='opacity:.7;float:left;margin-right:15px'/>";
    echo "<div style='padding-left:70px'>";
    echo "<h2>" . $rackinfo[0]['name'] . "</h2>";
    echo "<table><tr><td width='350px'>";
    echo "<h3 style='margin:0px'>Servers</h3>";
    if($rackservers){
        echo "<table>";
        $rackpools=array();
        echo "<tr><th>Server Name</th><th>Model</th><th>RU</th></tr>";
        foreach($rackservers as $server){
            echo "<tr>";
            echo "<td><a href='./view.php?type=server&id=".$server['serverid']."'>".$server['servername']."</a></td>";
            echo "<td>".$server['model']."</td>";
            echo "<td>".$server['ru']."</td>";
            echo "</tr>";
            $serverpools=$sqlite->search("select distinct pool.name,pool.id from poolservermap left join pool on poolservermap.poolid = pool.id where poolservermap.serverid = ".$server['serverid']);
            foreach($serverpools as $pool){
                $rackpools[$pool['name']]['name']=$pool['name'];
                $rackpools[$pool['name']]['id']=$pool['id'];
                @$rackpools[$pool['name']]['cnt']++;
            }
        }
        echo "</table>";
        echo "</td><td width='400px'>";
        echo "<h3 style='margin:0px'>Pools</h3>";
        if($rackpools){
            foreach($rackpools as $pool){
                echo "<a href='./view.php?type=pool&id=".$pool['id']."' class='floatbox'>".$pool['name']."[".$pool['cnt']."]</a>";
            }
        }
        echo "</td></tr></table>";
    }
    echo "</div>";

}else{
    echo "Rack not found";
}

