<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

// set page url in session for insert / update files
$detail = "../A_Club_index.php";
$_SESSION['detail'] = $detail;


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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?><?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>


<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="769" align="center">

  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"> <span class="red_bold">Clubs, Affiliates and Associations</span></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><form id="form6" name="form6" method="get" action="clubs_srch_res.php">
      <input name="clubfind" type="text" id="clubfind" size="12" />
      <input type="submit" value="Search Clubs by name" />
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center"  class="red_text">To Insert a new Club, Affiliate or Association, please check the club is not listed via the above search</td>
  </tr>
  <tr>
    <td align="center"  class="greenbg"><a href="user_files/clubs_insert.php">Insert a new Club / Affiliate / Association</a></td>
    <td align="center"><span class="greenbg"><a href="help_Clubs.pdf" target="_blank">Clubs database help file</a></span></td>
  </tr>
</table>
<table width="987" align="center">
  <tr>
    <td width="240" align="left">&nbsp;</td>
    <td width="735">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="all_clubs_public.php">All Clubs - Public View details - Bulk Email</a></td>
    <td>&quot;Public view&quot; contact details and which (if any) Associations they participate in.</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="all_associations.php">Associations- Bulk Email</a></td>
    <td>View / Edit / Bulk Email all Associations</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="all_affiliates.php">Affiliates- Bulk Email</a></td>
    <td>View / Edit / Bulk Email all Affiliated Associations</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="greenbg_menu">In these &quot;Club Contact&quot; lists - there may be more than one contact per club</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_participating_clubs.php">VBSA participating Clubs </a></td>
    <td>View / Edit / Insert / Update / Delete contacts</td>
  </tr>
    <tr>
    <td align="left" class="greenbg_menu"><a href="vbsa_contact_only.php">Club & Association/Affiliate contacts</a></td>
    <td>View & bulk e-mail all contacts for these Clubs & Association/Affiliates</td>
  </tr>
  <?php
  /* Requested by Mark Dunn 25/9/24 - Alec Spyrou 
  <tr>
    <td align="left" class="greenbg_menu"><a href="clubs_email_CM.php">Clubs - Club Management - Bulk Email</a></td>
    <td>View / Edit / Bulk Email all Club Management</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="clubs_email_inv_to.php">Clubs - Invoice To - Bulk Email</a></td>
    <td>View / Edit / Bulk Email all Invoice To</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="clubs_email_SS.php">Clubs - Snooker section- Bulk Email</a></td>
    <td>View / Edit / Bulk Email all Snooker Section</td>
  </tr>
  */
  ?>
  <tr>
    <td align="left" class="greenbg_menu">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu"><a href="all_clubs_inactive.php">Inactive Clubs - Do not appear on the Website</a></td>
    <td>Clubs that no longer support our sport - for historical scoring these Clubs need to be kept</td>
  </tr>
  <tr>
    <td align="left" class="greenbg_menu">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="left" class="page">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
  </tr>
</table>
</body>
</html>
<?php
?>