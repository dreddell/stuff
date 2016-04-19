<?php

$mastertitle="Stuff";
$tagline="write your crap down";


$dbconfig['host']='localhost';
$dbconfig['dbuser']='root';
$dbconfig['dbpass']='password';
$dbconfig['database']='know';


$ldapconfig['bindhost']='10.128.14.34';
$ldapconfig['bindport']=636;
$ldapconfig['sasl']=true;
$ldapconfig['baseou']='ou=people,dc=urbanairship,dc=com';
$ldapconfig['attributes']['username']=array('name'=>'username','ldapname'=>'username');
$ldapconfig['attributes']['email']=array('name'=>'email','ldapname'=>'email');
$ldapconfig['attributes']['fname']=array('name'=>'fname','ldapname'=>'given_name');
$ldapconfig['attributes']['lname']=array('name'=>'lname','ldapname'=>'sn');
