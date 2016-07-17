<?php
$awsservers=$sqlite->search("select * from server where name like 'e%' order by name asc");


if($awsservers) {
    echo "<img src='./images/icons/cloud.png' style='opacity:.7;float:left;margin-right:15px'/>";
    echo "<div style='padding-left:70px'>";
    echo "<h2>AWS Servers</h2>";
    echo "<table><tr><td width='350px'>";
    echo "<h3 style='margin:0px'>Servers</h3>";
    if($awsservers){
        echo "<table>";
        $rackpools=array();
        echo "<tr><th>Server Name</th><th>AMI</th><th>Instance ID</th></tr>";
        foreach($awsservers as $server){
            echo "<tr>";
            echo "<td><a href='./view.php?type=server&id=".$server['id']."'>".$server['name']."</a></td>";
            echo "<td>".$server['aws_ami']."</td>";
            echo "<td>".$server['aws_instance_id']."</td>";
            echo "</tr>";
            $serverpools=$sqlite->search("select distinct pool.name,pool.id from poolservermap left join pool
                                          on poolservermap.poolid = pool.id where poolservermap.serverid = ".$server['id']);
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

