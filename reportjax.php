<?php
require "config.php";
require "connectors.php";
$parameters= array();
$sqlite= new sqlite($sqlite);

$type=$_POST['rpttype'];


$myoptions = $filteroptions[$type];

if(@$_POST['cols']){
    $selectedcols=array_flip($_POST['cols']);
}


$pp=processparms($_POST,$myoptions);
$q=$pp['q'];

if($type=="server") {
    $query = "select server.id,";
    foreach ($displayoptions[$type] as $option) {
        if (isset($selectedcols[$option['tab'] . "-" . $option['col']])) {
            $query .= $option['tab'] . "." . $option['col'] . " as '" . $option['disp'] . "',";
        }
    }
    $query = rml($query);
    $query .= " from server ";
    $query.=" left join
                (
                select id,
                (case when server.name like 's%' then 'physical' when server.name like 'e%' then 'aws' else 'unknown' end) AS servertype
                from server
                ) as s2 on s2.id = server.id ";
    $query.="LEFT OUTER JOIN rackservermap on rackservermap.serverid = server.id
LEFT JOIN rack on rackservermap.rackid = rack.id ";

    if(@$pp['poolqueries']) {
        $q[]=addpoolqueries($pp['poolqueries']);
    }


    if ($q) {
        $query .= " where ";
        foreach ($q as $part) {
            $query .= $part . " and ";
        }
    }
    $query = substr($query, 0, -5);

    $query.=" COLLATE NOCASE";

    try{
        $matches = $sqlite->search($query);
    }catch(Exception $e){
        //echo "<hr>".$query."<hr>";
        $error['parms']=$parameters;
        $error['query']=$query;
    }

   // echo "<hr>".$query."<hr>";


    if ($matches) {
        echo "<div id='rptsavediag'>";
        echo "Report Name: <br /><input placeholder='title of report' style='width:90%' type='text' id='rptname' /><br />";
        echo "<span id='rptmessagename' style='clear:top'></span><br />";
        echo "Description: <br /><textarea placeholder='brief description' style='width:90%' id='rptdescript'></textarea>";
        echo "<span id='rptmessagedescript' style='clear:top'></span>";
        echo "</div>";
        echo "<div id='postsaverptdiag'></div>";

        echo "<p style='margin-bottom:2px;margin-top:0px;'>" . count($matches) . " matches found";
        /***
        echo "
                &nbsp;<input id='btnexportcsv' type='button' onclick=\"rptexport('csv');\" value='Export CSV' />
                &nbsp;<input id='btnexportjson' type='button' onclick=\"rptexport('json');\" value='Export JSON' />
                &nbsp;<input id='btnexportxlsflat' onclick=\"rptexport('xlsflat');\" type='button' value='Export XLS - Flat' />
                 &nbsp;<input id='btnexportxlstable' onclick=\"rptexport('xlstable');\" type='button' value='Export XLS - Table' />
                &nbsp;<input id='btnsaverpt' type='button' onclick='saverpt();' value='Save This Report' /></p>";
         * **/
        echo "<table class='resulttab stripe hover'><thead><tr>";
        foreach ($displayoptions['server'] as $option) {
            if (isset($selectedcols[$option['tab'] . "-" . $option['col']])) {
                echo "<th>" . $option['disp'] . "</th>";
            }
        }
        echo "</tr></thead><tbody>";
        foreach ($matches as $match) {


            echo "<tr>";
            foreach ($displayoptions['server'] as $option) {
                if (isset($selectedcols[$option['tab'] . "-" . $option['col']])) {
                    if($option['disp']=='Peakname'){
                        echo "<td><a target='_blank' href='./index.php?r=device/view&peakname=".$match[$option['disp']]."'>" . $match[$option['disp']] . "</a></td>";
                    }else {
                        echo "<td>" . $match[$option['disp']] . "</td>";
                    }
                }
            }
            echo "</tr>";
        }

        echo "</tbody></table>";

    }else{
        if(@$error){
            echo "Unable to process query";
            //debug($error);
        }else {
            echo "Nothing matched your parameters";
            //echo "<br /><hr>" . $query;
        }
    }

    //echo "<br /><hr>" . $query;
    //debug($matches);
    echo "<br /><br /><br /><br />";
}





function processparms($PST,$myoptions){
    $parameters=array();
    $q=array();
    if($PST['parms']){
        foreach($PST['parms'] as $parm) {
            if ($parm[0] == 'Pool') {
                $ret['poolqueries'][] = $parm;
            }else {
                $thisparm['name'] = $parm[0];
                $thisparm['op'] = $parm[1];
                $thisparm['val'] = $parm[2];
                $parameters[] = $thisparm;
            }
        }
        if($parameters) {
            foreach ($parameters as $p) {
                $options = $myoptions[$p['name']];
                if ($p['op'] == 'eq') {
                    $p['op'] = " = ";
                }
                if ($p['op'] == 'neq') {
                    $p['op'] = " != ";
                }

                if ($p['op'] == 'like') {
                    $p['val'] = '%' . str_replace('*', '%', sqlescape($p['val'])) . '%';
                }
                if ($p['op'] == 'notlike') {
                    $p['op'] = " not like ";
                    $p['val'] = '%' . str_replace('*', '%', sqlescape($p['val'])) . '%';
                }

                if ($p['op'] == 'lessthan') {
                    $p['op'] = " < ";
                }
                if ($p['op'] == 'grtthan') {
                    $p['op'] = " > ";
                }

                if( $p['op'] == " not like " ||  $p['op'] == " != ") {
                    $q[] = " (" . $options['tab'] . "." . $options['col'] . " " . $p['op'] . " '" . sqlescape($p['val']) . "' or ".$options['tab'] . "." . $options['col']."  is null) ";
                }else{
                    $q[] = " " . $options['tab'] . "." . $options['col'] . " " . $p['op'] . " '" . sqlescape($p['val']) . "' ";
                }
            }
        }

    }
    $ret['q']=$q;
    return $ret;
}


function addpoolqueries($poolqueries){
    $q="";
    foreach ($poolqueries as $pq) {
        $thispq = "";
        if ($pq[1] == 'eq') {
            $op = " = ";
        }
        if ($pq[1] == 'neq') {
            $op = " != ";
        }

        if ($pq[1] == 'like') {
            $op = 'like';
            $pq[2] = '%' . str_replace('*', '%', sqlescape($pq[2])) . '%';
        }
        if ($pq[1] == 'notlike') {
            $op = 'not like';
            $pq[2] = '%' . str_replace('*', '%', sqlescape($pq[2])) . '%';
        }

        if ($pq[1] == 'lessthan') {
            $op = " < ";
        }
        if ($pq[1] == 'grtthan') {
            $op = " > ";
        }
        $col="pool.name ";

        $thispq = $col . " $op '" . sqlescape(@$pq[2]) . "' and ";
        $thispq = rml(rml(rml($thispq)));


        $q .= " server.id in (select poolservermap.serverid from poolservermap
                    left join pool on poolservermap.poolid = pool.id
                    where $thispq) and ";
    }
    $q = substr($q, 0, -5);
    return $q;
}




function sqlescape($data){
    //$ret=mysql_real_escape_string($data);
    return $data;
}

function rml($data){
    $final = substr_replace(trim($data), "", -1);
    return $final;
}


function debug($ray){
    echo "<pre>";
    print_r($ray);
    echo "</pre>";
}