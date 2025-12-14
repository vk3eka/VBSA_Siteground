<?php

function WA_Auth_GetComparisonsForRule($ruleName){
	$comparisons = array();
	
	switch ($ruleName){
		case "checkout rule":
			$comparisons[0] = array(TRUE, "".((isset($_GET['checkout']))?$_GET['checkout']:"")  ."", 1, "1");
			break;
		case "Email address not found":
			$comparisons[0] = array(TRUE, "".((isset($_GET['notFound']))?$_GET['notFound']:"")  ."", 2, "");
			break;
		case "Email server failure":
			$comparisons[0] = array(TRUE, "".((isset($_GET['EmailFail']))?$_GET['EmailFail']:"")  ."", 2, "");
			break;
		case "Emailed password":
			$comparisons[0] = array(TRUE, "".((isset($_GET['emailedPassword']))?$_GET['emailedPassword']:"")  ."", 2, "");
			break;
		case "Failed log in":
			$comparisons[0] = array(TRUE, "".((isset($_GET['failedLogin']))?$_GET['failedLogin']:"")  ."", 2, "");
			break;
		case "Log in success":
			$comparisons[0] = array(TRUE, "".((isset($_GET['loggedIn']))?$_GET['loggedIn']:"")  ."", 2, "");
			break;
		case "Logged in to orders":
			$comparisons[0] = array(TRUE, "".((isset($_SESSION['SecurityAssist_OrderID']))?$_SESSION['SecurityAssist_OrderID']:"")  ."", 2, "");
			break;
		case "Logged in to users":
			$comparisons[0] = array(TRUE, "".((isset($_SESSION['SecurityAssist_UserID']))?$_SESSION['SecurityAssist_UserID']:"")  ."", 2, "");
			break;
		case "Successful update":
			$comparisons[0] = array(TRUE, "".((isset($_GET['success']))?$_GET['success']:"")  ."", 2, "");
			break;
		case "Validated form":
			$comparisons[0] = array(TRUE, "".((isset($_GET['invalid']))?$_GET['invalid']:"")  ."", 2, "");
			break;
	}
	return $comparisons;	
}


function WA_Auth_GetGroup($groupName){
	$group = Array();
	
	switch($groupName){
	
	}

	return $group;
}

?>
