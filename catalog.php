<?php
include "header.php";
echo "<div style='padding-left:70px'>";
echo "<h2>Catalog</h2>";
echo "
    <ul id='catnav'>
        <li><a href='./catalog.php?items=servers'><img src='./images/icons/linux.png' />Servers</a></li>
        <li><a href='./view.php?type=aws'><img src='./images/icons/cloud.png' />AWS Servers</a></li>
        <li><a href='./catalog.php?items=pools'><img src='./images/icons/connections.png' />Pools</a></li>
        <li><a href='./catalog.php?items=racks'><img src='./images/icons/rack.png' />Racks</a></li>
    </ul>";


$items=@$_GET['items'];
if($items){
    $sqlite = new sqlite($sqlite);
    echo "<div id='viewbox'>";
    if($items=="servers"){
        $q="select * from server order by env,name asc";
        $servers=$sqlite->search($q);
        if($servers){
            foreach($servers as $server){
                $envs[$server['env']][]=$server;
            }
            foreach($envs as $k=>$env){
                echo strtoupper($k)."<hr>";
                foreach($env as $server) {
                    echo "<div class='floatbox'><a href='./view.php?type=server&id=" . $server['id'] . "'>" . $server['name'] . "<br /><span class='env " . $server['env'] . "'>" . $server['env'] . "</span></a></div>";
                }
                echo "<br /><br />";
            }
        }
    }
    if($items=="pools"){
        echo "<h2>Pools</h2><hr>";
        $q="select pool.name,pool.id,server.env
            from pool
            left join poolservermap on pool.id = poolservermap.poolid
            left join server on poolservermap.serverid = server.id
            order by pool.name asc ";
        $pools=$sqlite->search($q);

        if($pools){
            $poolmap=array();
            $poolenvs=array();
            foreach($pools as $pool){
                if(!$pool['env']){
                    $pool['env']="None";
                }
                @$poolmap[$pool['id']]['name']=$pool['name'];
                @$poolmap[$pool['id']][$pool['env']]++;
                $poolenvs[$pool['env']]=$pool['env'];
                //echo "<a href='./view.php?type=pool&id=".$pool['id']."' class='floatbox'>".$pool['name']." [".$pool['x']."]</a>";
            }
            echo "<table class='datatab stripe hover'>";
            echo "<thead><tr>";
            echo "<th>Name</th>";
            foreach($poolenvs as $env){
                echo "<th>$env</th>";
            }
            echo "</tr></thead>";
            echo "<tbody>";
            foreach($poolmap as $id=>$pool){
                echo "<tr>";
                echo "<td><a href='./view.php?type=pool&id=$id' >".$pool['name']."</a></td>";
                foreach($poolenvs as $env){
                    $envcount=(@$pool[$env]/2);
                    if($envcount<1){
                        $envcount=0;
                    }

                    echo "<td>".$envcount."</td>";
                }
                echo "</tr>";
            }
            echo "<tbody>";
            echo "</table>";

        }
    }

    if($items=="racks"){
        $q="select rack.name,rack.id, count(rackservermap.id) as x from rack
            left join rackservermap on rack.id = rackservermap.rackid
            group by rack.name
            order by rack.name asc";
        $racks=$sqlite->search($q);
        if($racks){
            foreach($racks as $rack){
                echo "<a href='./view.php?type=rack&id=".$rack['id']."' class='floatbox'>".$rack['name']." [".$rack['x']."]</a>";
            }
        }
    }

    echo "</div>";
}
echo "</div>";

function debug($ray){
    echo "<pre>";
    print_r($ray);
    echo "</pre>";
}
?>
<script>

$('.datatab').DataTable({searching: false, paging: false});

</script>
<style>
    table.datatab th{text-align:left}
</style>