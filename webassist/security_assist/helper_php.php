<?php
/*-----------------------------------------------------------------------------
-    File Name:
-        Helper.php
-
-    This file contains proprietary and confidential information from WebAssist.com
-    corporation.  Any unauthorized reuse, reproduction, or modification without
-    the prior written consent of WebAssist.com is strictly prohibited.
-
-    Copyright 2011 WebAssist.com Corporation.  All rights reserved.
------------------------------------------------------------------------------*/
@session_start();
$securityassist_helper_Include_Start_Dir = getcwd();
chdir(dirname(__FILE__));
require_once("helpergroupsrulesphp.php");
if (file_exists("mail_php.php")) require_once("mail_php.php");
if (file_exists("wa_cryptencryption.php")) require_once("wa_cryptencryption.php");
if (file_exists("wa_hashencryption.php")) require_once("wa_hashencryption.php");
if (file_exists("wa_md5encryption.php")) require_once("wa_md5encryption.php");
if (file_exists("wa_rijndaelencryption.php")) require_once("wa_rijndaelencryption.php");
if (file_exists("wa_sha1encryption.php")) require_once("wa_sha1encryption.php");
chdir($securityassist_helper_Include_Start_Dir);
?>
<?php
$WA_Auth_Separator = "|¤|";
$_SESSION["WAENCRYPTEDRETURNUSED"] = false;
$_SESSION["WAENCRYPTEDRETURNSUCCESS"] = false;
function WA_AuthenticateUser($WA_Auth_Parameter){
	$UserAuthenticated = false;
	mysql_select_db($WA_Auth_Parameter["database"], $WA_Auth_Parameter["connection"]);
	$WA_Auth_loginSQL = "SELECT `".implode('`,`', $WA_Auth_Parameter["sessionColumns"])."` FROM `".$WA_Auth_Parameter["tableName"]."` WHERE ";
	for($idx=0;$idx<count($WA_Auth_Parameter["columns"]);$idx++){
		$WA_Auth_loginSQL .= sprintf((($idx!=0)?" AND ":" ")."`%s`=%s ", $WA_Auth_Parameter["columns"][$idx], WA_GetSQLValueString($WA_Auth_Parameter["columnValues"][$idx], $WA_Auth_Parameter["columnTypes"][$idx]));
	}
	$WA_Auth_RS = mysql_query($WA_Auth_loginSQL, $WA_Auth_Parameter["connection"]) or die(mysql_error());
	$WA_Auth_Rows = mysql_num_rows($WA_Auth_RS);
	if($WA_Auth_Rows){
		$UserAuthenticated = true;
		$idx = 0;
		foreach ($WA_Auth_Parameter["sessionNames"] as $sessionName){
			$_SESSION[$sessionName] = mysql_result($WA_Auth_RS,0,$WA_Auth_Parameter["sessionColumns"][$idx]);
			$idx++;
		}
		if (isset($_GET['accesscheck'])) {
			unset($_SESSION["WASA_accesscheck"]);
		}
		if (isset($_SESSION["WASA_accesscheck"]))  {
			$_GET['accesscheck'] = $_SESSION["WASA_accesscheck"];
			unset($_SESSION["WASA_accesscheck"]);
		}
		if (isset($_GET['accesscheck'])) {
			$WA_Auth_Parameter["successRedirect"] = urldecode($_GET['accesscheck']);
		}
		if($WA_Auth_Parameter["successRedirect"]!=""){
			$WA_Auth_Parameter["successRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["successRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
			header("Location: ".$WA_Auth_Parameter["successRedirect"]);
			exit();
		}
	}
	if(!$UserAuthenticated && $WA_Auth_Parameter["failRedirect"]!=""){
		$WA_Auth_Parameter["failRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["failRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
		header("Location: ".$WA_Auth_Parameter["failRedirect"]);
		exit();
	}
}


function WA_Auth_ClearSession($clearAll, $clearThese){

	if($clearAll){
		foreach ($_SESSION as $key => $value){
			unset($_SESSION[$key]);
		}
	}
	else{
		foreach($clearThese as $value){
			unset($_SESSION[$value]);
		}
	}
}

function WA_Auth_RestrictAccess($redirectURL){
	$redirectURL = WA_Auth_BuildRedirectURL($redirectURL, FALSE, TRUE);
	header("Location: ".$redirectURL);
	exit();
}

function WA_Auth_GetEmailFromPage($filePath) {
	ob_start();
	require($filePath);
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function WA_Auth_ForgotPassword($WA_Auth_Parameter){
	$selectColumns = array();
	for($idx=0;$idx<count($WA_Auth_Parameter["selectColumns"]);$idx++){
		if($WA_Auth_Parameter["selectColumns"][$idx]!=''){
			$selectColumns[] = $WA_Auth_Parameter["selectColumns"][$idx];
		}
	}
	if (!in_array($WA_Auth_Parameter["usernameColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["usernameColumn"];
	if (!in_array($WA_Auth_Parameter["passwordColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["passwordColumn"];
	if (!in_array($WA_Auth_Parameter["toAddressColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["toAddressColumn"];
	mysql_select_db($WA_Auth_Parameter["database"], $WA_Auth_Parameter["connection"]);
	$WA_Auth_Parameter["columnValueEnc"] = $WA_Auth_Parameter["columnValue"];
	switch ($WA_Auth_Parameter["filterEncryption"]) {
		case "crypt":
			$WA_Auth_Parameter["columnValueEnc"] = WA_CryptEncryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "hash":
			$WA_Auth_Parameter["columnValueEnc"] = WA_HashEncryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "md5":
			$WA_Auth_Parameter["columnValueEnc"] = WA_MD5Encryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "sha1":
			$WA_Auth_Parameter["columnValueEnc"] = WA_SHA1Encryption($WA_Auth_Parameter["columnValue"]);
			break;
	}
	mysql_select_db($WA_Auth_Parameter["database"], $WA_Auth_Parameter["connection"]);
	$WA_Auth_ForgotSQL = "SELECT `".implode('`,`', $selectColumns)."` FROM `".$WA_Auth_Parameter["tableName"]."` WHERE `".$WA_Auth_Parameter["filterColumn"]."` =".
							sprintf("%s ", WA_GetSQLValueString($WA_Auth_Parameter["columnValueEnc"], $WA_Auth_Parameter["columnType"]));
	$WA_Auth_RS = mysql_query($WA_Auth_ForgotSQL, $WA_Auth_Parameter["connection"]) or die(mysql_error());
	$WA_Auth_Rows = mysql_num_rows($WA_Auth_RS);
	$WA_EmailSuccess = false;
	$WA_UserFound = false;
	if($WA_Auth_Rows){
	    $WA_UserFound = true;
		$row_WA_Auth_RS = mysql_fetch_assoc($WA_Auth_RS);
		$WA_Auth_Parameter = WA_Auth_GetMailBody($WA_Auth_Parameter, $row_WA_Auth_RS, $selectColumns);
		if($WA_Auth_Parameter["fromAddressDisplay"]!=''){
			$WA_Auth_Parameter["fromAddress"] = $WA_Auth_Parameter["fromAddress"].'|WA|'.$WA_Auth_Parameter["fromAddressDisplay"];
		}
		call_user_func($WA_Auth_Parameter["emailFunction"], $WA_Auth_Parameter);
		if (isset($GLOBALS["WA_MailObject_Status"]) && $GLOBALS["WA_MailObject_Status"] == "Success") {
			$WA_EmailSuccess = true;
//die($WA_Auth_Parameter["mailBody"]);
			if($WA_Auth_Parameter["successRedirect"]!=""){
				$WA_Auth_Parameter["successRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["successRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
				header("Location: ".$WA_Auth_Parameter["successRedirect"]);
				exit();
			}
		}
	}
	if (!$WA_EmailSuccess && $WA_Auth_Parameter["failRedirect"]!=""){
		$WA_Auth_Parameter["failRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["failRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
		if ($WA_UserFound)  {
			$WA_Auth_Parameter["failRedirect"] = $WA_Auth_Parameter["failRedirect"].((strpos($WA_Auth_Parameter["failRedirect"], '?') === false)?"?":"&")."EmailFail=true";
		} else {
			$WA_Auth_Parameter["failRedirect"] = $WA_Auth_Parameter["failRedirect"].((strpos($WA_Auth_Parameter["failRedirect"], '?') === false)?"?":"&")."notFound=true";
		}
		header("Location: ".$WA_Auth_Parameter["failRedirect"]);
		exit();
	}
}

function WA_Auth_GetMailBody($WA_Auth_Parameter, $row_WA_Auth_RS, $selectColumns) {
	$WA_Auth_Parameter["username"] = $row_WA_Auth_RS[$WA_Auth_Parameter["usernameColumn"]];
	$WA_Auth_Parameter["password"] = $row_WA_Auth_RS[$WA_Auth_Parameter["passwordColumn"]];
	$WA_Auth_Parameter["toAddress"] = $row_WA_Auth_RS[$WA_Auth_Parameter["toAddressColumn"]];
	if ($WA_Auth_Parameter["usernameEncryption"]) {
		switch ($WA_Auth_Parameter["usernameEncryption"]) {
			case "hash":
				global $WA_HashEncryption;
				$WA_Auth_Parameter["username"] = WA_HashDecryption($row_WA_Auth_RS[$WA_Auth_Parameter["usernameColumn"]]);
				break;
		}
	}
	if ($WA_Auth_Parameter["passwordEncryption"]) {
		switch ($WA_Auth_Parameter["passwordEncryption"]) {
			case "hash":
				global $WA_HashEncryption;
				$WA_Auth_Parameter["password"] = WA_HashDecryption($row_WA_Auth_RS[$WA_Auth_Parameter["passwordColumn"]]);
				break;
		}
	}
	if ($WA_Auth_Parameter["toAddressEncryption"]) {
		switch ($WA_Auth_Parameter["toAddressEncryption"]) {
			case "hash":
				global $WA_HashEncryption;
				$WA_Auth_Parameter["toAddress"] = WA_HashDecryption($row_WA_Auth_RS[$WA_Auth_Parameter["toAddressColumn"]]);
				break;
		}
	}
	$WA_Auth_Parameter["mailBody"] = preg_replace("/\\n/", "\r\n", $WA_Auth_Parameter["mailBody"]);
	if (strstr($WA_Auth_Parameter["mailBody"], "\n") === false &&
			(stristr($WA_Auth_Parameter["mailBody"], ".php") !== false ||
				stristr($WA_Auth_Parameter["mailBody"], ".html") !== false ||
				stristr($WA_Auth_Parameter["mailBody"], ".htm") !== false ||
				strstr($WA_Auth_Parameter["mailBody"], ".HTM") !== false) &&
			file_exists($WA_Auth_Parameter["mailBody"])) {
		$WA_Auth_Parameter["mailBody"] = WA_Auth_GetEmailFromPage($WA_Auth_Parameter["mailBody"]);
	}
	$WA_Auth_Parameter["mailBody"] = preg_replace("/\[".$WA_Auth_Parameter["usernameColumn"]."\]/", $WA_Auth_Parameter["username"], $WA_Auth_Parameter["mailBody"]);
	$WA_Auth_Parameter["mailBody"] = preg_replace("/\[".$WA_Auth_Parameter["passwordColumn"]."\]/", $WA_Auth_Parameter["password"], $WA_Auth_Parameter["mailBody"]);
	$WA_Auth_Parameter["mailBody"] = preg_replace("/\[".$WA_Auth_Parameter["toAddressColumn"]."\]/", $WA_Auth_Parameter["toAddress"], $WA_Auth_Parameter["mailBody"]);
	for($idx=0;$idx<count($selectColumns);$idx++){
		$WA_Auth_Parameter["mailBody"] = preg_replace("/\[".$selectColumns[$idx]."\]/", $row_WA_Auth_RS[$selectColumns[$idx]], $WA_Auth_Parameter["mailBody"]);
	}
	for($idx=0;$idx<count($WA_Auth_Parameter["sessionVariables"]);$idx++){
		$WA_Auth_Parameter["mailBody"] = preg_replace("/\[Session\.".$WA_Auth_Parameter["sessionVariables"][$idx]."\]/", isset($_SESSION[$WA_Auth_Parameter["sessionVariables"][$idx]])?$_SESSION[$WA_Auth_Parameter["sessionVariables"][$idx]]:"", $WA_Auth_Parameter["mailBody"]);
	}
	if ($WA_Auth_Parameter["filterColumn"] == $WA_Auth_Parameter["toAddressColumn"] && ($WA_Auth_Parameter["filterEncryption"] || $WA_Auth_Parameter["toAddressEncryption"])) {
		$WA_Auth_Parameter["toAddress"] = $WA_Auth_Parameter["columnValue"];
	}
	return $WA_Auth_Parameter;
}

function WA_Auth_ForgotEncryptedPassword($WA_Auth_Parameter){
	$selectColumns = array();
	for($idx=0;$idx<count($WA_Auth_Parameter["selectColumns"]);$idx++){
		if($WA_Auth_Parameter["selectColumns"][$idx]!=''){
			$selectColumns[] = $WA_Auth_Parameter["selectColumns"][$idx];
		}
	}
	if (!in_array($WA_Auth_Parameter["filterColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["filterColumn"];
	if (!in_array($WA_Auth_Parameter["keyColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["keyColumn"];
	if (!in_array($WA_Auth_Parameter["usernameColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["usernameColumn"];
	if (!in_array($WA_Auth_Parameter["passwordColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["passwordColumn"];
	if (!in_array($WA_Auth_Parameter["toAddressColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["toAddressColumn"];
	$WA_Auth_Parameter["columnValueEnc"] = $WA_Auth_Parameter["columnValue"];
	switch ($WA_Auth_Parameter["filterEncryption"]) {
		case "crypt":
			$WA_Auth_Parameter["columnValueEnc"] = WA_CryptEncryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "hash":
			$WA_Auth_Parameter["columnValueEnc"] = WA_HashEncryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "md5":
			$WA_Auth_Parameter["columnValueEnc"] = WA_MD5Encryption($WA_Auth_Parameter["columnValue"]);
			break;
		case "sha1":
			$WA_Auth_Parameter["columnValueEnc"] = WA_SHA1Encryption($WA_Auth_Parameter["columnValue"]);
			break;
	}
	mysql_select_db($WA_Auth_Parameter["database"], $WA_Auth_Parameter["connection"]);
	$WA_Auth_ForgotSQL = "SELECT `".implode('`,`', $selectColumns)."` FROM `".$WA_Auth_Parameter["tableName"]."` WHERE `".$WA_Auth_Parameter["filterColumn"]."` =".
							sprintf("%s ", WA_GetSQLValueString($WA_Auth_Parameter["columnValueEnc"], $WA_Auth_Parameter["columnType"]));
	$WA_Auth_RS = mysql_query($WA_Auth_ForgotSQL, $WA_Auth_Parameter["connection"]) or die(mysql_error());
	$WA_Auth_Rows = mysql_num_rows($WA_Auth_RS);
	$WA_EmailSuccess = false;
	if($WA_Auth_Rows){
		$row_WA_Auth_RS = mysql_fetch_assoc($WA_Auth_RS);
		$WA_Auth_Parameter = WA_Auth_GetMailBody($WA_Auth_Parameter, $row_WA_Auth_RS, $selectColumns);
		$useEncryption = "";
		if ($WA_Auth_Parameter["usernameEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["usernameEncryption"];
		}
		else if ($WA_Auth_Parameter["passwordEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["passwordEncryption"];
		}
		else if ($WA_Auth_Parameter["toAddressEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["toAddressEncryption"];
		}
		if ($useEncryption) {
			$dataStr = $row_WA_Auth_RS[$WA_Auth_Parameter["toAddressColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["keyColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["passwordColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["usernameColumn"]];
			switch ($useEncryption) {
				case "crypt":
					$dataStr = WA_CryptEncryption($dataStr);
					break;
				case "hash":
					$dataStr = WA_HashEncryption($dataStr);
					break;
				case "md5":
					$dataStr = WA_MD5Encryption($dataStr);
					break;
				case "sha1":
					$dataStr = WA_SHA1Encryption($dataStr);
					break;
			}
			$fullReturnURL = WA_SecurityAssist_rel2Abs($WA_Auth_Parameter["returnURL"]) . "?fp_id=" . rawurlencode($row_WA_Auth_RS[$WA_Auth_Parameter["keyColumn"]]) . "&fp_email=" . rawurlencode($row_WA_Auth_RS[$WA_Auth_Parameter["toAddressColumn"]]) . "&fp_data=" . rawurlencode($dataStr);
			if (stripos($WA_Auth_Parameter["mailBody"], "<body") !== false) {
				//html body
				$fullReturnURL = htmlspecialchars($fullReturnURL);
			}
			$WA_Auth_Parameter["mailBody"] = preg_replace("/\[\*return_url\*\]/", $fullReturnURL, $WA_Auth_Parameter["mailBody"]);
//$WA_Auth_Parameter["mailBody"] = preg_replace("/\[\*return_url\*\]/", "", $WA_Auth_Parameter["mailBody"]);
			if($WA_Auth_Parameter["fromAddressDisplay"]!=''){
				$WA_Auth_Parameter["fromAddress"] = $WA_Auth_Parameter["fromAddress"].'|WA|'.$WA_Auth_Parameter["fromAddressDisplay"];
			}
			call_user_func($WA_Auth_Parameter["emailFunction"], $WA_Auth_Parameter);
//die("<pre>" . $WA_Auth_Parameter["mailBody"] . "</pre>");
			if (isset($GLOBALS["WA_MailObject_Status"]) && $GLOBALS["WA_MailObject_Status"] == "Success") {
				$WA_EmailSuccess = true;
				if($WA_Auth_Parameter["successRedirect"]!=""){
					$WA_Auth_Parameter["successRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["successRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
					header("Location: ".$WA_Auth_Parameter["successRedirect"]);
					exit();
				}
			}
		}
	}
	if (!$WA_EmailSuccess && $WA_Auth_Parameter["failRedirect"]!=""){
		$WA_Auth_Parameter["failRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["failRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
		if ($WA_Auth_Rows)  {
			$WA_Auth_Parameter["failRedirect"] = $WA_Auth_Parameter["failRedirect"].((strpos($WA_Auth_Parameter["failRedirect"], '?') === false)?"?":"&")."EmailFail=true";
		} else {
			$WA_Auth_Parameter["failRedirect"] = $WA_Auth_Parameter["failRedirect"].((strpos($WA_Auth_Parameter["failRedirect"], '?') === false)?"?":"&")."notFound=true";
		}
		header("Location: ".$WA_Auth_Parameter["failRedirect"]);
		exit();
	}
}

if (!function_exists("WA_SecurityAssist_rel2Abs(")) {
	function WA_SecurityAssist_rel2Abs($tPath) {
		if (preg_match("/^https?\:\/\//i", $tPath) != 0) return $tPath;
		$myPath = ( (preg_match("/^HTTPS/i", $_SERVER['SERVER_PROTOCOL']) === 0) ? "http" : "https" )."://".$_SERVER['HTTP_HOST'];
		if (strpos($tPath, "/") === 0) return $myPath.$tPath;
		$myPath .= $_SERVER['PHP_SELF'];
		$myPath = substr($myPath, 0, strrpos($myPath, "/") + 1);
		while (preg_match("/^\.\.\//", $tPath) !== 0) {
			$myPath = substr($myPath, 0, strrpos($myPath, "/"));
			$myPath = substr($myPath, 0, strrpos($myPath, "/") + 1);
			$tPath = substr($tPath, 3);
		}
		return $myPath.$tPath;
	}
}

function WA_Auth_ForgotEncryptedPasswordReturn($WA_Auth_Parameter){
	$_SESSION["WAENCRYPTEDRETURNUSED"] = true;
	$selectColumns = array();
	if (!in_array($WA_Auth_Parameter["keyColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["keyColumn"];
	if (!in_array($WA_Auth_Parameter["usernameColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["usernameColumn"];
	if (!in_array($WA_Auth_Parameter["passwordColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["passwordColumn"];
	if (!in_array($WA_Auth_Parameter["toAddressColumn"], $selectColumns)) $selectColumns[] = $WA_Auth_Parameter["toAddressColumn"];
	$WA_Auth_Parameter["columnValue"] = $_GET["fp_id"];
	mysql_select_db($WA_Auth_Parameter["database"], $WA_Auth_Parameter["connection"]);
	$WA_Auth_ForgotSQL = "SELECT `".implode('`,`', $selectColumns)."` FROM `".$WA_Auth_Parameter["tableName"]."` WHERE `".$WA_Auth_Parameter["keyColumn"]."` =".
							sprintf("%s ", WA_GetSQLValueString($WA_Auth_Parameter["columnValue"], $WA_Auth_Parameter["columnType"]));
	$WA_Auth_RS = mysql_query($WA_Auth_ForgotSQL, $WA_Auth_Parameter["connection"]) or die(mysql_error());
	$WA_Auth_Rows = mysql_num_rows($WA_Auth_RS);
	$WA_Auth_Parameter["success"] = false;
	$WA_EmailSuccess = false;
	if($WA_Auth_Rows){
		$row_WA_Auth_RS = mysql_fetch_assoc($WA_Auth_RS);
		$useEncryption = "";
		if ($WA_Auth_Parameter["usernameEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["usernameEncryption"];
		}
		else if ($WA_Auth_Parameter["passwordEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["passwordEncryption"];
		}
		else if ($WA_Auth_Parameter["toAddressEncryption"]) {
			$useEncryption = $WA_Auth_Parameter["toAddressEncryption"];
		}
		if ($useEncryption) {
			$test1 = $_GET["fp_email"] . "|" . $_GET["fp_id"] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["passwordColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["usernameColumn"]];
			$test2 = $row_WA_Auth_RS[$WA_Auth_Parameter["toAddressColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["keyColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["passwordColumn"]] . "|" . $row_WA_Auth_RS[$WA_Auth_Parameter["usernameColumn"]];
			switch ($useEncryption) {
				case "crypt":
					$test1 = WA_CryptEncryption($test1);
					$test2 = WA_CryptEncryption($test2);
					break;
				case "hash":
					$test1 = WA_HashEncryption($test1);
					$test2 = WA_HashEncryption($test2);
					break;
				case "md5":
					$test1 = WA_MD5Encryption($test1);
					$test2 = WA_MD5Encryption($test2);
					break;
				case "sha1":
					$test1 = WA_SHA1Encryption($test1);
					$test2 = WA_SHA1Encryption($test2);
					break;
			}
			if ($test1 == $test2 && $test1 == $_GET["fp_data"]) {
				$WA_Auth_Parameter["success"] = true;
				$WA_EmailSuccess = true;
				call_user_func($WA_Auth_Parameter["returnFunction"], $WA_Auth_Parameter);
				$_SESSION["WAENCRYPTEDRETURNSUCCESS"] = true;
			}
		}
	}
	if (!$WA_EmailSuccess) {
		if($WA_Auth_Parameter["failRedirect"]!=""){
			$WA_Auth_Parameter["failRedirect"] = WA_Auth_BuildRedirectURL($WA_Auth_Parameter["failRedirect"], $WA_Auth_Parameter["keepQueryString"], FALSE);
			header("Location: ".$WA_Auth_Parameter["failRedirect"]);
			exit();
		}
	}
}


function WA_Auth_BuildRedirectURL($redirectURL, $keepCurrentQueryString, $addDeniedURL){

	if ($keepCurrentQueryString && $redirectURL != "" && isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] !== "") {
		$redirectURL .= ((strpos($redirectURL, '?') === false)?"?":"&").$_SERVER["QUERY_STRING"];
	}
	
	if($addDeniedURL){
		$WA_Auth_Referrer = $_SERVER['PHP_SELF'];
		$redirectURL = $redirectURL.((strpos($redirectURL, "?"))?"&":"?")."accesscheck=".urlencode($WA_Auth_Referrer.((isset($_SERVER["QUERY_STRING"]))?"?".$_SERVER["QUERY_STRING"]:""));
		$_SESSION["WASA_accesscheck"] = urlencode($WA_Auth_Referrer.((isset($_SERVER["QUERY_STRING"]))?"?".$_SERVER["QUERY_STRING"]:""));
	}
    if(strpos($redirectURL, '/') === 0){
		$redirectURL = 'http'.((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]!="off")?"s":"").'://'.$_SERVER['HTTP_HOST'].$redirectURL;
	}
	
	return $redirectURL;
}

// Rules functions

function WA_Auth_RulePasses($ruleName){
	return WA_Auth_RuleObject_EvaluateRules($ruleName);
}

function WA_Auth_RuleObject_EvaluateRules($ruleName){
	$rulePasses = FALSE;
	$comparisons = WA_Auth_GetComparisonsForRule($ruleName);
	$compareLen = count($comparisons);
	
	for($idx=0;$idx<$compareLen;$idx++){
		$comparison = $comparisons[$idx];
		$compareSucceeds = !$comparison[0];
		switch($comparison[2]) {
/*
			1-9		Direct value comparisons
			10-19		String Comparisons
			20-29		List Comparisons
*/
			case 1:
				$compareSucceeds = ($comparison[1]==$comparison[3]);
				break;
				
			case 2:
				$compareSucceeds = ($comparison[1]!=$comparison[3]);
				break;
				
			case 3:
				$compareSucceeds = ($comparison[1]<$comparison[3]);
				break;
				
			case 4:
				$compareSucceeds = ($comparison[1]<=$comparison[3]);
				break;
				
			case 5:
				$compareSucceeds = ($comparison[1]>$comparison[3]);
				break;
				
			case 6:
				$compareSucceeds = ($comparison[1]>=$comparison[3]);
				break;
				
			case 20:
				$compareSucceeds = WA_Auth_GroupContainsValue($comparison[3], $comparison[1]);
				break;

		}

		if(!$comparison[0] && $compareSucceeds){
			$rulePasses = FALSE;
			break;
		}
		else if ($comparison[0] && $compareSucceeds){
			$rulePasses = TRUE;
			break;
		}
		else if(!$comparison[0] && !$compareSucceeds){
			$rulePasses = TRUE;
		}
		else if($comparison[0] && !$compareSucceeds){
			$rulePasses = FALSE;
		}
	}

	return $rulePasses;

}


// Groups functions

function WA_Auth_GroupContainsValue($groupName, $value){
	$group = WA_Auth_GetGroup($groupName);
	return in_array($value, $group);
}


// Debug functions
function WA_Auth_SessionDebug(){
	@session_start();
	$str = "Session variables: <br />";
	foreach ($_SESSION as $key => $value){
		$str.=$key." = ".$value."<br />";
	}
	echo($str);
}

function WA_Auth_RuleObject_DebugAllComparisons($comparisons){
	for($idx =0;$idx<count($comparisons);$idx++){
		WA_Auth_RuleObject_DebugComparison($comparisons[$idx]);
	}
}

function WA_Auth_RuleObject_DebugComparison($comparison){
	echo(($comparison[0]?"TRUE":"FALSE")."<br />".$comparison[1]."<br />".$comparison[2]."<br />".$comparison[3]."<br />" );
}

?>
<?php
if (!function_exists("WA_GetSQLValueString")) {
function WA_GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>