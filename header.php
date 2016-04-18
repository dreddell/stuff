<?php
$kb = new kbdb($dbconfig);
?>
<head>
<title><?php echo $mastertitle;?></title>
<script src="jquery-1.12.3.min.js" ></script>
<script src="./jquery-ui/jquery-ui.min.js" ></script>
<script src="login.js" ></script>
<link rel='stylesheet' href='mainstyle.css'/> 
<link rel='stylesheet' href='./jquery-ui/jquery-ui.min.css'/> 
</head>
<body>
<div id="headwrap">
	<div id="mainhead">
		<h3><a class='homelink' href='.'><?php echo $mastertitle;?></a></h3>
		<div id="mainnav">
			<ul id='navlist'>
				<li><a href='#'>Home</a></li>
				<li><a href='#'>Compose</a></li>
				<?php
				if( isset( $_SESSION['stuff']['user'] ) ){
					echo "<li><input type='button' id='logout' value='Logout' /></li>";
				}else{
					echo "<li><input type='button' id='logbut' value='Login' /></li>";
				};
				?>
			</ul>
		</div>
	</div>
</div>
<table id='mainbodlayout'>
	<tr>
		<td id='filtertd'>
			I am a filter
		</td>
		<td id='resultstd'>
			I am a result
		</td>
	</tr>
</table>

<div id="logdiv" title="Log into Stuff">
Username:<br /><input type="text" name="user" id="loguser" /><br />
Password:<br /><input type="password" name="pass" id="logpass" /><br /><br />
<span id="logmsg"></span>
</div>

<?php




