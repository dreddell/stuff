<?php
session_start();
include "config.php";
include "connectors.php";
$action=$_POST['action'];


if($action=='login'){
	$ad = new actived($ldapconfig);
	$user=$_POST['user'];
	$pass=$_POST['pass'];
	$info=$ad->login($user,$pass);
	if($info){
		@$_SESSION['stuff']['user']=$info;
		echo "success";
	}else{
		echo "Bad user or password";
	}
}

if($action=='logout'){
	unset($_SESSION['stuff']['user']);
}