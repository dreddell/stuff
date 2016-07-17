<?php
include 'header.php';
echo "<div id='viewbox'>";
echo "<h2>Report Generator</h2>";

?>
<script>
    function saverpt(){
        //Get parameters
        var myparms = [];
        $('#searchterms li').each(function(){
            var db =$(this).attr("parmname");
            var op=$(this).find( ".operator option:selected" ) .val();
            var srchval=$(this).find(".ac").val();
            if(srchval.length>0) {
                myparms.push([db, op, srchval]);
            }
        })
        var mycols = [];
        $('.opchk').each(function(){
            if($(this).is(':checked')) {
                mycols.push($(this).attr('id'));
            }
        })
        $("#rptsavediag").dialog({
            resizable: false,
            modal: true,
            title: "Save This Report",
            height: 250,
            width: 400,
            buttons: {
                "Save": function () {
                    var rptname= $('#rptname').val();
                    var rptdescript= $('#rptdescript').val();
                    if(rptname.length < 1){
                        $('#rptmessagename').show().html('Please provide a name').fadeOut(6000);
                    }
                    if(rptdescript.length < 1) {
                        //$('#rptmessagedescript').show().html('Please provide a description').fadeOut(6000);
                    }
                    if(rptname.length >1){
                        $(this).dialog('close');
                        rptcallback(rptname,rptdescript);
                    }


                },
                "Cancel": function () {
                    $(this).dialog('close');
                }
            }
        });
    }

    function rptexport(mode){
        //Get parameters
        var myparms = [];
        $('#searchterms li').each(function(){
            var db =$(this).attr("parmname");
            if(db=='Tag') {
                $(this).find(".ac").each(function(){
                    var op='eq';
                    var col=$(this).attr("accol");
                    var tab=$(this).attr("actab");
                    var grpid=$(this).attr("grpid");
                    var srchval = $(this).val();
                    if (srchval.length > 0) {
                        myparms.push([db, op, srchval,col,grpid]);
                    }

                });
            }else{
                var op = $(this).find(".operator option:selected").val();
                var srchval = $(this).find(".ac").val();
                if (srchval.length > 0) {
                    myparms.push([db, op, srchval]);
                }
            }
        })
        var mycols = [];
        $('.opchk').each(function(){
            if($(this).is(':checked')) {
                mycols.push($(this).attr('id'));
            }
        })
        //Create the form
        var f = document.createElement("form");
        f.setAttribute('action',"./export.php");
        f.setAttribute('method',"post");
        f.setAttribute('target',"_blank");
        //Create the type field
        var t = document.createElement("input"); //input element, text
        t.setAttribute('name',"type");
        t.value=rpttype;
        f.appendChild(t);

        //Create the parms field
        var p = document.createElement("input"); //input element, text
        p.setAttribute('name',"parms");
        p.value=JSON.stringify(myparms);
        f.appendChild(p);

        //Create the cols field
        var c = document.createElement("input"); //input element, text
        c.setAttribute('name',"cols");
        c.value=mycols;
        f.appendChild(c);



        //Create the exportmethod field
        var e = document.createElement("input"); //input element, text
        e.setAttribute('name',"exportmethod");
        e.value=mode;
        f.appendChild(e);

        //Submit the form
        document.body.appendChild(f);
        f.submit();
        document.body.removeChild(f);
    }


    function rptcallback(rptname,description) {
        //Get parameters
        var myparms = [];
        $('#searchterms li').each(function(){
            var db =$(this).attr("parmname");
            if(db=='Tag') {
                $(this).find(".ac").each(function(){
                    var op='eq';
                    var col=$(this).attr("accol");
                    var tab=$(this).attr("actab");
                    var grpid=$(this).attr("grpid");
                    var srchval = $(this).val();
                    if (srchval.length > 0) {
                        myparms.push([db, op, srchval,col,grpid]);
                    }

                });
            }else{
                var op = $(this).find(".operator option:selected").val();
                var srchval = $(this).find(".ac").val();
                if (srchval.length > 0) {
                    myparms.push([db, op, srchval]);
                }
            }
        })
        var mycols = [];
        $('.opchk').each(function(){
            if($(this).is(':checked')) {
                mycols.push($(this).attr('id'));
            }
        })

        $.post("./reportjax.php", {
            rpttype: 'saverpt',
            rptname: rptname,
            description: description,
            parms: myparms,
            cols: mycols,
            type: rpttype
        }, function (data) {
            postsavediag(data);
        });

    }


    function postsavediag(data){
        $("#postsaverptdiag").html(data);
        $("#postsaverptdiag").dialog(
            {
                resizable: false,
                modal: true,
                title: "Save This Report",
                height: 250,
                width: 700,
                buttons: {
                    "Dismiss": function () {
                        $(this).dialog('close');
                    }
                }
            }
        );
    }


    function rmrpt(rptkey){
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:200,
            width:600,
            modal: true,
            buttons: {
                "Delete Report": function() {
                    $.post("reportjax.php", {
                        rpttype: 'delrpt',
                        rptkey: rptkey
                    }, function (data) {
                        if(data='success'){
                            document.location.reload();
                        }
                    });
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }

    function rptstartsearch(){
        //Get parameters
        var myparms = [];
        $('#searchterms li').each(function(){
            var db =$(this).attr("parmname");
            if(db=='Tag') {
                $(this).find(".ac").each(function(){
                    var op='eq';
                    var col=$(this).attr("accol");
                    var tab=$(this).attr("actab");
                    var grpid=$(this).attr("grpid");
                    var srchval = $(this).val();
                    if (srchval.length > 0) {
                        myparms.push([db, op, srchval,col,grpid]);
                    }

                });
            }else{
                var op = $(this).find(".operator option:selected").val();
                var srchval = $(this).find(".ac").val();
                if (srchval.length > 0) {
                    myparms.push([db, op, srchval]);
                }
            }
        })
        var mycols = [];
        $('.opchk').each(function(){
            if($(this).is(':checked')) {
                mycols.push($(this).attr('id'));
            }
        })
        $('#results').html('');
        if(myparms.length>0 && mycols.length>0){
            $('#results').html("Searching <img style='vertical-align:middle;height:16px;' src='./images/progress-bar.gif' />");
            $.post("./reportjax.php", {
                rpttype: rpttype,
                parms: myparms,
                cols: mycols
            }, function (data) {
                $('#results').html(data);
                $('.resulttab').DataTable({searching: false});
            });

        }else{
            if(myparms.length < 1){
                $('#results').html('No search terms specified');
            }
            if(mycols.length < 1){
                $('#results').html('You must select at least one column to display');
            }
        }






    }
    function primeac() {
        $('input.ac').each(function(){
            $(this).autocomplete({ source: 'ac.php?cat='+rpttype+'&accol='+$(this).attr("accol")+'&actab='+$(this).attr("actab"),minLength: 1});
        });
    }

    var operators="<option value='eq'>=</option><option value='neq'>!=</option><option value='grtthan'>></option><option value='lessthan'><</option><option value='like'>like</option><option value='notlike'>Not like</option>";
    var rem="<input type='button' class='rem' onclick='$(this).parent().remove();' value='X' />";
    function addfilter(){
        var parmname = $('#parms option:selected').val();
        var parmcol = $('#parms option:selected').attr("col");
        var parmtab = $('#parms option:selected').attr("tab");
        if(parmtab == 'device_tag'){
            var grpid=Date.now();
            var parmli = "<li class='parm' parmname='" + parmname + "' tab='" + parmtab + "' col='" + parmcol + "'>Tag Name: <input grpid='"+grpid+"' type='text' class='ac' accol='tag_name' actab='device_tag' /> & Tag Val: <input grpid='"+grpid+"' accol='tag_value' actab='device_tag'  type='text' class='ac' /> " + rem + "</li>";
        }else {
            var parmli = "<li class='parm' parmname='" + parmname + "' tab='" + parmtab + "' col='" + parmcol + "'>" + parmname + " <select class='operator' >" + operators + "</select>&nbsp;<input accol='"+parmcol+"' actab='"+parmtab+"' type='text' class='ac' /> " + rem + "</li>";
        }
        $('#searchterms').prepend(parmli);
        primeac();
    }
</script>
<?php



echo "<div id='tabcont'>";
echo "<fieldset style='border:2px solid #eee;border-radius:5px' ><legend>Search parameters:</legend>";
echo "<div id='filters'>";
echo "Select a filter:&nbsp;";
echo "<select id='parms'>";
foreach($filteroptions['server'] as $filter){
    echo "<option col='".$filter['col']."' tab='".$filter['tab']."' val='".$filter['col']."'>".$filter['disp']."</option>";
}
echo "</select>&nbsp;<input onclick='addfilter()' type='button' id='btnaddfilter' value='Add Filter' />";
if(@$rptparms){
    $rem="<input type='button' class='rem' onclick='$(this).parent().remove();' value='X'>";
    $options='';
    $mytags=array();
    foreach($rptparms as $parm){
        if($parm[0]=='Tag'){
            $mytags[$parm[4]][]=$parm;
        }
    }
    if($mytags){
        foreach($mytags as $grp=>$tag){
            $tagname=$tag[0][2];
            $tagval=$tag[1][2];
            $rptmyparms .= "<li class='parm' parmname='Tag' tab='device_tag' col='tag_name'>
            Tag Name: <input grpid='$grp' value='$tagname' type='text' class='ac' accol='tag_name' actab='device_tag' />
            & Tag Val: <input grpid='$grp' value='$tagval' accol='tag_value' actab='device_tag'  type='text' class='ac' />
             <input type='button' class='rem' onclick='$(this).parent().remove();' value='X' /></li>";
        }
    }

    foreach($rptparms as $parm){
        $options="<select class='operator'>";
        if($parm[1]=='eq'){
            $options.="<option selected value='eq'>=</option>";
        }else{
            $options.="<option value='eq'>=</option>";
        }
        if($parm[1]=='neq'){
            $options.="<option selected value='neq'>=</option>";
        }else{
            $options.="<option value='neq'>=</option>";
        }
        if($parm[1]=='grtthan'){
            $options.="<option selected value='grtthan'>&gt;</option>";
        }else{
            $options.="<option value='grtthan'>&gt;</option>";
        }
        if($parm[1]=='lessthan'){
            $options.="<option selected value='lessthan'>&lt;</option>";
        }else{
            $options.="<option value='lessthan'>&lt;</option>";
        }
        if($parm[1]=='like'){
            $options.="<option selected value='like'>like</option>";
        }else{
            $options.="<option value='like'>like</option>";
        }
        if($parm[1]=='notlike'){
            $options.="<option selected value='notlike'>Not like</option>";
        }else{
            $options.="<option value='notlike'>Not like</option>";
        }

        $options.="</select>&nbsp;";
        if($parm[0]<>'Tag'){
            $parmdets = $filteroptions['server'][$parm[0]];
            $rptmyparms .= "<li class='parm' parmname='" . $parmdets['disp'] . "' tab='" . $parmdets['tab'] . "' col='" . $parmdets['col'] . "'>" . $parmdets['disp'] . " $options";
            $rptmyparms .= "<input type='text' class='ac' accol='" . $parmdets['col'] . "' actab='" . $parmdets['tab'] . "'  value='" . $parm[2] . "' autocomplete='off' /> $rem</li>";
        }
    }

}else{
    $rptmyparms='';
}

echo "<ul id='searchterms'>$rptmyparms</ul>";
echo "</fieldset>";
echo "<script>";
echo "var rpttype='server';";
echo "</script>";

echo "<br /><div id='colchks'>";
echo "Columns to display:<br />";
if(@$myrptcols) {
    foreach ($displayoptions['server'] as $dispop) {
        if($myrptcols[$dispop['tab']."-".$dispop['col']]){
            echo "<span class='dispop'><input class='opchk' id='{$dispop['tab']}-{$dispop['col']}' checked type='checkbox' />&nbsp;<label for='{$dispop['tab']}-{$dispop['col']}'>{$dispop['disp']}</label></span>&nbsp;&nbsp;";
        }else{
            echo "<span class='dispop'><input class='opchk' id='{$dispop['tab']}-{$dispop['col']}'  type='checkbox' />&nbsp;<label for='{$dispop['tab']}-{$dispop['col']}'>{$dispop['disp']}</label></span>&nbsp;&nbsp;";
        }

    }
}else{
    foreach ($displayoptions['server'] as $dispop) {
        echo "<span class='dispop'><input class='opchk' id='{$dispop['tab']}-{$dispop['col']}' checked type='checkbox' />&nbsp;<label for='{$dispop['tab']}-{$dispop['col']}'>{$dispop['disp']}</label></span>&nbsp;&nbsp;";
    }
}
echo "</div>";
echo "<br /><br /><input id='btnstartsearch' type='button' value='Search' class='bigbutton' onclick='rptstartsearch();' />";



?>
<div id="results"></div>


<style>
    table.edtable{border:1px solid #999}
    table.edtable{border:1px solid #999}
    table.edtable td{border-bottom:1px solid #aaa;padding:3px;}
    table.edtable td input{padding:3px;}
    table.edtable th{text-align:left;background:#eee;padding-left:10px;border:1px solid #999;text-transform: capitalize;}
    #tabs a{display:inline;background:#eee;color:#444;font-size:20px;padding:10px;padding-bottom:0px;padding-top:5px;text-decoration:none;border:1px solid #eee;border-radius:3px 3px 0 0;margin-left:5px;}
    #tabs a.selectedtab{margin-bottom:-15px;background:#fefefe;border-bottom:2px solid #fff}
    #tabs a.unselected{display:inline;background:#eee;color:#444;font-size:17px;padding:10px;padding-bottom:0px;padding-top:5px;text-decoration:none;border:1px solid #eee;border-radius:3px 3px 0 0;margin-left:5px;opacity:.8;}
    #tabs{border-bottom:1px solid #eee;margin-top:25px;padding-bottonm:15px}
    #tabcont{border-left:1px solid #eee;padding-top:15px;padding-bottom:15px;padding-left:10px;}
    #templatemanager{padding-top:20px}
    #tabcont{padding-top:15px;padding-left:15px;}
    input.txt{width:200px;}
    #results{padding-top:20px;}
    #searchterms{padding-left:0px}
    #searchterms li{
        display:inline;
        display:inline-block;
        border:1px solid #eeee;
        background:#eee;
        padding:8px;
        margin:3px;
        list-style:none;
        border-radius:5px;
        white-space: nowrap;
        margin-bottom:5px;

    }
    #searchterms li input {margin-right:5px;}
    #invrpt h3{margin:0px;}
    #results table.tablesorter{max-width:98%;margin-top:0px}
    #results table.tablesorter th{}
    #results a{margin-top:5px;display:inline-block}
    #results a.selected{font-weight:bold;color:green;text-decoration:none}
    .tablesorter tr:nth-child(odd) td{background-color: #f4f7f7;  }
    .tablesorter td a{color:#444}
    .tablesorter th{min-width:50px}
    table.tablesorter thead tr th, table.tablesorter tfoot tr th{padding-right:18px;}
    span.dispop{display:inline-block;margin:5px;padding:2px;background:#fefefe;padding-left:5px;padding-right:5px;border-radius:5px;}
    #colchks{padding:5px;background:#ededed;border-radius:5px;font-size:.8em}
    label{font-weight:bold}
    #rptsave{padding:5px;background:#eee;display:none;border-radius:5px;;margin-top:5px;}
    table.nics th,table.nics td{border-bottom:1px solid #aaa;font-size:.8em;}
    span.tg{display:inline-block;background:#eeee;padding:3px;margin:3px;border-radius:5px;border:1px solid #999}
    input.bigbutton{
        color:#222;
        border:2px solid #555;
        width:200px;
        font-weight:bold;
        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        background:#e4e4e4;
    }
    #rptsavediag,#postsaverptdiag{display:none}
    input.bigbutton:hover{box-shadow:none;}
    input.ac{padding-left:5px;}

</style>