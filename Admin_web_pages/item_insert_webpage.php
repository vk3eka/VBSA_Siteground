<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
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
<?php require_once("../webassist/ckeditor/ckeditor.php"); ?>
<?php
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

$item_id = "-1";
if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO webpage_items (ID, Header, `Comment`, `By`, created_on, blocked, img_orientation, page_front, page_referee, page_junior, page_help, page_womens, page_refprofile, page_refposer, page_playerprofile, page_scores, page_polproc, page_about) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['Header'], "text"),
                       GetSQLValueString($_POST['Comment'], "text"),
                       GetSQLValueString($_POST['By'], "text"),
                       GetSQLValueString($_POST['created_on'], "date"),
                       GetSQLValueString($_POST['blocked'], "text"),
                       GetSQLValueString($_POST['img_orientation'], "text"),
                       GetSQLValueString(isset($_POST['page_front']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_referee']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_junior']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_help']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_womens']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_refprofile']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_refposer']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_playerprofile']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_scores']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_polproc']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['page_about']) ? "true" : "", "defined","'Y'","'N'"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

$updateGoTo = "item_detail.php?item_id=" .$newid;
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

$currentPage = $_SERVER["PHP_SELF"];

$queryString_MembHistory = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_MembHistory") == false && 
        stristr($param, "totalRows_MembHistory") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_MembHistory = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_MembHistory = sprintf("&totalRows_MembHistory=%d%s", $totalRows_MembHistory, $queryString_MembHistory);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Front Page Administation Area</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<script type="text/javascript" >
function validateForm() {
    var allchecked=0;
if(document.getElementById('page_front').checked)allchecked=1;
if(document.getElementById('page_referee').checked)allchecked=1;
if(document.getElementById('page_junior').checked)allchecked=1;
if(document.getElementById('page_help').checked)allchecked=1;
if(document.getElementById('page_womens').checked)allchecked=1;
if(document.getElementById('page_refprofile').checked)allchecked=1;
if(document.getElementById('page_refposer').checked)allchecked=1;
if(document.getElementById('page_playerprofile').checked)allchecked=1;
if(document.getElementById('page_scores').checked)allchecked=1;
if(document.getElementById('page_polproc').checked)allchecked=1;
if(document.getElementById('page_about').checked)allchecked=1;
    if(allchecked==0) {
        alert("NO PAGE SELECTED\n\nPlease select at least one");
        return false;
    }
	else {return true;}
}
</script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td align="center" class="red_bold">&nbsp; Item inserted by : <?php echo $row_getusername['name']; ?></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Insert a new Item into  any of the editable pages</td>
    <td align="center" class="page"><a href="javascript:history.go(-1)"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></a></td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="return validateForm()">
      <table width="1027" align="center" class="page">
        <tr>
          <td align="left" nowrap="nowrap">&nbsp;</td>
          <td colspan="10" align="left" nowrap="nowrap" class="Italic">Item inserted on:
            <?php date_default_timezone_set('Australia/Melbourne'); echo date("l jS F Y g:ia")."&nbsp;&nbsp;&nbsp;&nbsp;  By:";  echo $row_getusername['name']; ?>
(auto inserted) </td>
        </tr>
        <tr>
          <td width="157" align="right" nowrap="nowrap">ID:</td>
          <td width="831" colspan="10">Auto Generated <?php echo $item_id; ?></td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="right">Header:</td>
          <td colspan="10"><input type="text" name="Header" value="" size="60" /> 
          Appears in red bold text at the top of your item. Limited to 50 characters</td>
        </tr>
        <tr>
          <td align="right" valign="top" nowrap="nowrap">Comment:</td>
          <td colspan="10"><?php
// The initial value to be displayed in the editor.
$CKEditor_initialValue = '
<p><b>Before publishing delete this & the line below*</p>
<p>&nbsp;</p>
<p>Description below (keep content brief - description and key dates/links etc) - delete this line and modify the text below to suit your event</p></b>
<div>Get ready for one of Australia’s premier snooker events!</div>
<div>Whether you are a seasoned competitor or rising star, join the ranks of greats in this long-standing & respected Australian ranking tournament.</div>
<p>&nbsp;</p>
<div>Dress code: <b>**Copy dress code in calendar entry for this event here. IF STATE TITLE OR SENIORS USE TEXT BELOW**</b></div>
<div><b>Early rounds</b> Dark polo (preferably VBSA or Club Polo), Dark coloured Slacks (No cargo pants, Chinos or Jeans), Dark coloured shoes or Dark coloured runners</div>
<div><b>Finals</b> Formal tournament wear of Waistcoat or bowtie, plain coloured shirt (no patterns), Dress trousers, Dark shoes (not runners)</div>
<p>Entries close: <b>**ENTER DATE HERE**</b>(Event limited to <b>X entries</b>) <b>*ASK TD IF A LIMIT APPLIES*</b></p>
<p>Tournament Director: <b>**Enter Name, Email (VBSA email if available) and mobile here**</b></p>
<div>See entries:<a href="https://vbsa.org.au/Tournaments/tourn_index.php" target="_entries">here</a></div>
<div>Purchase entry:<a href="https://vbsa.org.au/vbsa_shop/shop_cart.php" target="_shop">here</a> If you have an unused pennant finals discount use the correct shopping cart item for this tournament</div>
<div>See draw and results:<a href="https://absc.com.au/results" target="_national">here</a><b> **IF A NATIONAL RANKING EVENT**</b></div>
<div>Search the club directory for a map location of the venue:<a href="https://vbsa.org.au/Club_dir/club_index.php" map="_map">here</a></div>
<p>Once the event is closed the draw & other event details such as start times, dress code etc will be provided here</p>
<p>Tournament Director contact (for all tournament enquiries):<b>Enter Name and Number here</b> </p>';
$CKEditor = new CKEditor();
$CKEditor->basePath = "../webassist/ckeditor/";
$CKEditor_config = array();
$CKEditor_config["wa_preset_name"] = "forum1 (custom)";
$CKEditor_config["wa_preset_file"] = "(custom)";
$CKEditor_config["width"] = "100%";
$CKEditor_config["height"] = "150px";
$CKEditor_config["uiColor"] = "#CCFFFF";
$CKEditor_config["docType"] = "<" ."!" ."DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
$CKEditor_config["contentsLanguage"] = "";
$CKEditor_config["dialog_startupFocusTab"] = false;
$CKEditor_config["fullPage"] = false;
$CKEditor_config["tabSpaces"] = 4;
$CKEditor_config["toolbar"] = array(
array( 'Source'),
array( 'Bold','Italic'),
array( 'TextColor'),
array( 'NumberedList','BulletedList','-','Outdent','Indent'),
array( 'Link','Unlink'),
array( 'PasteText','PasteFromWord','SpellChecker'),
array( 'Undo','Redo','-','Find','Replace'));
$CKEditor_config["contentsLangDirection"] = "ltr";
$CKEditor_config["entities"] = false;
$CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
$CKEditor_config["pasteFromWordRemoveStyles"] = false;
$CKEditor->editor("Comment", $CKEditor_initialValue, $CKEditor_config);
?></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td colspan="10" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap">Blocked:</td>
        <td colspan="10" valign="middle"><select name="blocked">            
              <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
              <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
            </select>
        If &quot;Yes&quot; the item will not appear on the website          </td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="11" align="left" valign="middle" nowrap="nowrap" class="red_text">Please select the page or pages that you want this item to appear on (item may be set to appear on multiple pages). Item will not insert unless at least one page is selected.</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Web Pages</td>
          <td align="right" valign="middle" nowrap="nowrap">Front Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_front" id="page_front" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Junior Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_junior" id="page_junior" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Help Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_help" id="page_help" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Womens Page</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_womens" id="page_womens" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Player Profiles</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_playerprofile" id="page_playerprofile" /></td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Administrative pages</td>
          <td align="right" valign="middle" nowrap="nowrap">About</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_about" id="page_about" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Scores</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_scores" id="page_scores" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Policies & Procedures</td>
          <td align="left" valign="middle" nowrap="nowrap"><input type="checkbox" name="page_polproc" id="page_polproc" /></td>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap" class="Italic">Referees pages</td>
          <td align="right" valign="middle" nowrap="nowrap">Referees Page</td>
          <td nowrap="nowrap"><input type="checkbox" name="page_referee" id="page_referee" /></td>
          <td align="right" valign="middle" nowrap="nowrap">Referee Profiles</td>
          <td nowrap="nowrap"><input type="checkbox" name="page_refprofile" id="page_refprofile" /></td>
          <td align="right" valign="middle" nowrap="nowrap"><label> Referee Posers&nbsp;&nbsp;&nbsp;</label></td>
          <td nowrap="nowrap"><input type="checkbox" name="page_refposer" id="page_refposer" /></td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
          <td nowrap="nowrap">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" valign="middle" nowrap="nowrap">&nbsp;</td>
          <td colspan="10">
          	<label>&nbsp;&nbsp;&nbsp;</label>
            <label>&nbsp;&nbsp;&nbsp;</label></td>
        </tr>
        <tr>
          <td align="center" valign="middle" nowrap="nowrap"> Ordered ?:</td>
          <td colspan="10" align="center" valign="middle"> Item inserted as &quot;not ordered&quot; please edit if you want this item to remain in a selected position on the selected pages. </td>
        </tr>
        <tr>
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td colspan="10"><input type="submit" name="submit" value="Insert item" /></td>
        </tr>
      </table>
		<input type="hidden" name="img_orientation" value="No Image" />
		<input type="hidden" name="By" value="<?php echo $row_getusername['name']; ?>" />
	  	<input type="hidden" name="ID" value="<?php echo $item_id; ?>" />
        <input type="hidden" name="created_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
      	<input type="hidden" name="MM_insert" value="form1" />
    </form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
  </table>
</center>
</body>
</html>
<?php

?>
