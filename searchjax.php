<?php
include "config.php";
include "connectors.php";
$sqlite = new sqlite($sqlite);
$crumb=$_GET['term'];

$matchterms['server']['table']='server';
$matchterms['server']['cols']=array('name','serial','dracip','aws_instance_id');
$matchterms['pool']['table']='pool';
$matchterms['pool']['cols']=array('name');
$matchterms['rack']['table']='rack';
$matchterms['rack']['cols']=array('name');

$matches=array();
foreach($matchterms as $mt) {
    foreach($mt['cols'] as $col) {
        $colmatches = $sqlite->search("Select id,name,$col from " . $mt['table'] . " where $col like '%$crumb%'");
        if ($colmatches) {
            foreach ($colmatches as $match) {
                $matches[] = array('id' => $match['id'], 'value' => $match['name']." [". $match[$col]."]", 'mode' => $mt['table']);
            }
        }
    }
}

echo json_encode($matches);