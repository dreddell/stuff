<?php
include "config.php";
include "connectors.php";
?>
<head>
<title><?php echo $mastertitle ?></title>
<script src="jquery-1.12.3.min.js" ></script>
<script src="./jquery-ui/jquery-ui.min.js" ></script>
<link rel='stylesheet' href='http://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css'/>
<script src="http://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" ></script>
<link rel='stylesheet' href='mainstyle.css'/> 
<link rel='stylesheet' href='./jquery-ui/jquery-ui.min.css'/>



<script>

$( document ).ready(function() {
	$('#searchbox').val('');

	$('.datatab').DataTable({searching: false, paging: true});
	$('.datatabnopage').DataTable({searching: false, paging: false});
	$( "#searchbox" ).autocomplete({
		source: "./searchjax.php",
		minLength: 3,//search after two characters

		select: function(event,match){
                document.location='./view.php?type='+match.item.mode+'&id='+match.item.id;
		}
	})

});
</script>

</head>
<body>
<div id="headwrap">
	<div id="mainhead">
		<h2 style='margin-left:30px'><a class='homelink' href='.'><img class='logo' src='./images/logo.png' />
										<?php
											echo $mastertitle;
											if($tagline){
												echo "&nbsp;&nbsp;&nbsp;<span style='font-size:.7em'>".$tagline."</span>";
											}
										?>
			</a></h2>
		<div id="mainnav">

			<ul id='navlist'>
				<li><a href='./ '>Home</a></li>
				<li><a href='./reports.php'>Reports</a></li>
				<li><a target='_blank' href='./import.php'>Refresh Data</a></li>
				<?php
				if($userlogin){
					if( isset( $_SESSION['stuff']['user'] ) ){
						echo "<li><input type='button' id='logout' value='Logout' /></li>";
					}else{
						echo "<li><input type='button' id='logbut' value='Login' /></li>";
					}
				}
				?>
				<li><input placeholder="Search for servers,serials,racks,IPs,pools" id="searchbox" /></li>
				<li></li>
			</ul>


		</div>
	</div>
</div>


<div id="logdiv" title="Log into Stuff">
Username:<br /><input type="text" name="user" id="loguser" /><br />
Password:<br /><input type="password" name="pass" id="logpass" /><br /><br />
<span id="logmsg"></span>
</div>






