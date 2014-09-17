<?php 
session_start();
//connect.php
$server	    = 'mysql6.000webhost.com';
$username	= 'a5334575_omada';
$password	= 'cs2102';
$database	= 'a5334575_omada';

if(!mysql_connect($server, $username, $password))
{
 	exit('Error: could not establish database connection');
}
if(!mysql_select_db($database))
{
 	exit('Error: could not select the database');
}
?>