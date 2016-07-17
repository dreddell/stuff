<?php
require "config.php";
require "connectors.php";

$col=$_GET['accol'];
$tab=$_GET['actab'];
$q=@$_GET['term'];
$sqlite= new sqlite($sqlite);

if($col=='servertype'){
    $acquery="select distinct
                (
                case when server.name like 's%' then 'pysical'
                when server.name like 'e%' then 'aws'
                else 'unknown' end
                ) AS 'out'
                from server  ";

}else {
    $acquery = "select distinct $col as 'out' from $tab where $col like '%" . $q . "%' COLLATE NOCASE";
}

$acmatches = $sqlite->search($acquery);

$ret=array();
if($acmatches) {
    foreach ($acmatches as $acmatch) {
        $ret[]=array('value'=>$acmatch['out'],'id'=>$acmatch['out']);
    }
}
echo json_encode($ret);