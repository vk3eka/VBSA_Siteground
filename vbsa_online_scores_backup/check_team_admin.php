<?php 
//ob_start();
session_start();

$clubname = $_GET['clubname'];
// set session variable to new team
$_SESSION['clubname'] = $clubname;
// return response of new team
echo($_SESSION['clubname']);

?>