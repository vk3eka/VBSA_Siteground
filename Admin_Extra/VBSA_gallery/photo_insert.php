<?php require_once('../../Connections/connvbsa.php'); ?>
<?php //include('../php_function.php');

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,VBSA";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

//echo(isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup']));
$MM_restrictGoTo = "../Access_Denied.php";
//if (!((isset($_SESSION['MM_Username'])) )) { 
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php //require_once('../../ScriptLibrary/incPureUpload.php'); ?>
<?php
// Pure PHP Upload 2.1.12
/*
$ppu = new pureFileUpload();
$ppu->path = "../../galleries/team_photo";
$ppu->extensions = "GIF,JPG,JPEG,BMP,PNG";
$ppu->formName = "form1";
$ppu->storeType = "file";
$ppu->sizeLimit = "500";
$ppu->nameConflict = "over";
$ppu->nameToLower = false;
$ppu->requireUpload = true;
$ppu->minWidth = "";
$ppu->minHeight = "";
$ppu->maxWidth = "";
$ppu->maxHeight = "";
$ppu->saveWidth = "";
$ppu->saveHeight = "";
$ppu->timeout = "600";
$ppu->progressBar = "";
$ppu->progressWidth = "300";
$ppu->progressHeight = "100";
$ppu->redirectURL = "";
$ppu->checkVersion("2.1.12");
$ppu->doUpload();
*/
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

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
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($editFormAction)) {
  if (isset($_SERVER['QUERY_STRING'])) {
	  if (!eregi("GP_upload=true", $_SERVER['QUERY_STRING'])) {
  	  $editFormAction .= "&GP_upload=true";
		}
  } else {
    $editFormAction .= "?GP_upload=true";
  }
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $targetDir = "../../galleries/team_photo/";
  $imageFile = $_FILES['club_photo'];
  $fileName = basename($imageFile['name']);
  $targetFilePath = $targetDir . $fileName;

  // Validate file type
  $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
  $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

  if(($imageFile != $existing) && (in_array($fileType, $allowedTypes)))
  {
    move_uploaded_file($imageFile['tmp_name'], $targetFilePath);
  }

  $insertSQL = sprintf("Insert INTO gallery_team_photos (id, grade, season, year_photo, club_photo, 1or2, `current`) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['grade'], "text"),
                       GetSQLValueString($_POST['season'], "text"),
                       GetSQLValueString($_POST['year_photo'], "date"),
                       GetSQLValueString($targetFilePath, "text"),
					             GetSQLValueString($_POST['1or2'], "text"),
                       GetSQLValueString($_POST['current'], "text"));
  //echo($insertSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_grade = "SELECT * FROM Team_grade";
$grade = mysql_query($query_grade, $connvbsa) or die(mysql_error());
$row_grade = mysql_fetch_assoc($grade);
$totalRows_grade = mysql_num_rows($grade);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/gallery_team_photos.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<script language='javascript' src='../../ScriptLibrary/incPureUpload.js' type="text/javascript"></script>

<table width="1000" align="center" cellpadding="5">
  <tr>
    <td width="887" align="left" class="red_bold">Create a new entry and upload a photo</td>
    <td width="181" align="right" nowrap="nowrap"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="checkFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',true,500,'','','','','','');return document.MM_returnValue">

      <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ID Number:</td>
      <td>Auto Generated</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Grade:</td>
      <td><input type="text" name="grade" value="" size="32" />
      please type in the grade eg A Premier Snooker</td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Premiers or Runners Up?</td>
      <td><select name="1or2">
        <option value="Premiers" <?php if (!(strcmp("Premiers", ""))) {echo "SELECTED";} ?>>Premiers</option>
        <option value="Runners Up" <?php if (!(strcmp("Runners Up", ""))) {echo "SELECTED";} ?>>Runners Up</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><select name="season">
        <option value="S1" <?php if (!(strcmp("S1", ""))) {echo "SELECTED";} ?>>S1</option>
        <option value="S2" <?php if (!(strcmp("S2", ""))) {echo "SELECTED";} ?>>S2</option>
      </select>
      Please Select</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Year:</td>
      <td><input name="year_photo" type="text" id="year_photo" onblur="MM_validateForm('year_photo','','RisNum');return document.MM_returnValue" value="" size="10" />
Please type the year the photo was taken</td>
    </tr>
    <tr>
      <td align="right" nowrap="nowrap">Photo:</td>
      <td nowrap="nowrap"><input name="club_photo" type="file" id="club_photo" size="50" /></td>
    </tr>
    <!--<tr valign="baseline">
      <td nowrap="nowrap" align="right">Photo:</td>
      <td><input name="club_photo" type="file" onchange="checkOneFileUpload(this,'GIF,JPG,JPEG,BMP,PNG',true,500,'','','','','','')" /></td>
    </tr>-->
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Current?:</td>
      <td>Auto inserted as &quot;Yes&quot; please edit to archive</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  
<input type="hidden" name="id" value="" />  
<input type="hidden" name="current" value="Yes" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($grade);
?>