<?php require_once('../Connections/connvbsa.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../Admin_DB_VBSA/vbsa_logout.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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
}if (!function_exists("GetSQLValueString")) {
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_next_id = "SELECT MAX( webpage_items.ID)+1 AS newid FROM webpage_items";
$next_id = mysql_query($query_next_id, $connvbsa) or die(mysql_error());
$row_next_id = mysql_fetch_assoc($next_id);
$totalRows_next_id = mysql_num_rows($next_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>


<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="DBheader"></div>
<p>&nbsp;</p>
<table width="1000" align="center">
  <tr>
    <td align="center"><span class="red_bold">Web Pages, Front, Junior, Womens, Referee, Referee Profile, Referee Posers, Help, Player Profile or Featured pages. Insert or edit</span></td>
  </tr>
  <tr>
    <td align="center" class="grey_text"><em>This area is continually expanding as more pages become interactive. Have modified this page in the hope that it will be simpler to operate, please let me know if you would like to see anything else or you are having problems understanding the system. Always open to new ideas and appreciate your thoughts.</em></td>
  </tr>
  <tr>
    <td align="center" class="page">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="page">Rebuild complete Feb 20, 9.34am</td>
  </tr>
  <tr>
    <td align="center" class="page">If you see anything not working correctly please email the <a href="mailto:scores@vbsa.org.au">webmaster</a>.</td>
  </tr>
  <tr>
    <td align="center" class="page">&nbsp;</td>
  </tr>
</table>
<table width="1000" align="center">
  <tr>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
    <td align="center" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
	    <td align="center" class="greenbg"><a href="../Admin_DB_VBSA/vbsa_login_success.php">Return to Administration Menu</a></td>
	    <td width="352" align="center" class="greenbg"><a href="../Admin_Calendar/A_calendar_index.php">Visit the Calendar</a></td>
	    <td width="344" align="right" class="greenbg"><a href="<?php echo $logoutAction ?>">Logout</a></td>
      </tr>
	  <tr>
	    <td align="left" class=" greenbg_menu">&nbsp;</td>
	    <td colspan="2">&nbsp;</td>
      </tr>
	  <tr>
	    <td width="288" align="left" class=" greenbg_menu"><a href="item_insert_check.php?item_id=<?php echo $row_next_id['newid']; ?>">Insert a new Item </a></td>
	    <td colspan="2">Insert a new item to any of the web pages. </td>
      </tr>
	  <tr>
	    <td colspan="3" align="center">&nbsp;</td>
  </tr>
	  <tr>
	    <td colspan="3" align="center"><em class="red_text">Q. Why have 2 methods of inserting items to the web pages? <br />
	      A. To avoid duplicate attachments - PLEASE NOTE: If an item has been created from the Calendar then Information items - attachments, links, email contacts can only be inserted from the calendar, thus ensuring any updates appear in both places (avoids having 2 versions of the one document. eg entry forms, draws etc.). Items created from the calendar will show attachments both in the calendar and with the items on the web pages. </em></td>
      </tr>
	  <tr>
	    <td align="center">&nbsp;</td>
	    <td colspan="2" align="center">&nbsp;</td>
  </tr>
	  <tr>
	    <td colspan="3" align="center"><strong>Web Pages - Select the page you wish to review / edit</strong></td>
  </tr>
	  <tr>
	    <td align="left"><span class="greenbg_menu"><a href="page_front.php">Front </a></span></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to items that are currently set to display on the Front Page of the website</td>
  </tr>
	  <tr>
	    <td align="left"><span class="greenbg_menu"><a href="page_junior.php">Juniors</a></span></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Juniors Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_refs.php">Referees</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Womens Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_refs_profiles.php">Referee Profiles</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Referees Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_refs_posers.php">Referee Posers</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Referee Profiles Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_womens.php">Womens</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Referee Posers Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_help.php">Help</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Help Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_about.php">VBSA Member Information</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Members Info Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_scores.php">VBSA Scores</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Scores  Page of the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_polproc.php">VBSA Policies &amp; Procedures</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  items that are currently set to display on the Policies and Procedures Page</td>
  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
	    <td colspan="2" align="left">&nbsp;</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_all.php">All items</a></td>
	    <td colspan="2" align="left">View / Edit / Upload attachments to  all items that are currently set to display on  the website</td>
  </tr>
	  <tr>
	    <td align="left" class="greenbg_menu"><a href="page_blocked.php">Blocked Items</a></td>
	    <td colspan="2" align="left">View / Edit all items that are currently set to NOT display on  the website <span class="red_text">(Where &quot;Blocked&quot; = Yes)</span></td>
  </tr>
	  <tr>
	    <td colspan="3" align="center">When you have finished please check the website, you may have to refresh the site to see your changes </td>
  </tr>
</table>
</body>
</html>
<?php

?>
