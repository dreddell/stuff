<?php

$mastertitle="Stuff";
$tagline="write your crap down";


$dbconfig['host']='';
$dbconfig['dbuser']='';
$dbconfig['dbpass']='';
$dbconfig['database']='';


$ldapconfig['bindhost']='';
$ldapconfig['bindport']=636;
$ldapconfig['sasl']=true;
$ldapconfig['baseou']='';
$ldapconfig['attributes']['username']=array('name'=>'username','ldapname'=>'username');
$ldapconfig['attributes']['email']=array('name'=>'email','ldapname'=>'email');
$ldapconfig['attributes']['fname']=array('name'=>'fname','ldapname'=>'given_name');
$ldapconfig['attributes']['lname']=array('name'=>'lname','ldapname'=>'sn');
