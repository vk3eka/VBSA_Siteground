<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['clubfind'])) {
  $clubfind = $_GET['clubfind'];
}

// set page url in session for insert / update files
$detail = "../clubs_srch_res.php?clubfind=$clubfind";
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



mysql_select_db($database_connvbsa, $connvbsa);
$query_Clubs = "SELECT * FROM clubs WHERE ClubTitle LIKE '$clubfind%' ORDER BY ClubTitle ASC";
$Clubs = mysql_query($query_Clubs, $connvbsa) or die(mysql_error());
$row_Clubs = mysql_fetch_assoc($Clubs);
$totalRows_Clubs = mysql_num_rows($Clubs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>

<table width="769" align="center">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><form id="form6" name="form6" method="get" action="clubs_srch_res.php">
      <input name="clubfind" type="text" id="clubfind" size="12" />
      <input type="submit" value="Search Clubs by name" />
      </form></td>
    <td align="right" class="greenbg"><a href="A_Club_index.php">Return to Club index</a></td>
  </tr>
</table>
<table width="816" align="center">
  <tr>
    <td>&nbsp;</td>
    <td width="154">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Club Search results for &quot;<?php echo $clubfind ?>&quot; ></td>
    <td align="right" class="greenbg"><a href="user_files/clubs_insert.php?">Insert a new club</a> </td>
  </tr>
</table>

  
<?php do { ?>
  <div id="Club_web"> 
    
    <table width="800" class="page">
      <tr>
        <td width="166" rowspan="6"><table width="166">
          <tr>
            <td><div id="Photo">
              <div align="center"><img src="http://www.vbsa.org.au/ClubImages/<?php echo $row_Clubs['ClubLogo']; ?> " height="120" /></td>
            </tr>
          </table></td>
        <td><p><strong><?php echo $row_Clubs['ClubTitle']; ?>, <?php echo $row_Clubs['ClubStreet']; ?>, <?php echo $row_Clubs['ClubSuburb']; ?>, <?php echo $row_Clubs['ClubPcode']; ?></strong></p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Phone: <?php echo $row_Clubs['ClubPhone1']; ?></td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td width="565">Melways Ref: <?php echo $row_Clubs['ClubMelwaysRef']; ?> </td>
        <td align="center"><a href="user_files/clubs_detail.php?club_id=<?php echo $row_Clubs['ClubNumber']; ?>&clubfind=<?php echo $clubfind; ?>&redirect=search"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="Detail" /></a></td>
      </tr>
      <tr>
        <td>Tables:<?php echo $row_Clubs['ClubTables']; ?></td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td>Email: <a href="mailto:<?php echo $row_Clubs['ClubEmail']; ?>"><?php echo $row_Clubs['ClubEmail']; ?></a></td>
        <td align="left" nowrap="nowrap">Active? <?php if($row_Clubs['inactive']==1) echo "Yes"; else echo "No"?></td>
      </tr>
      <tr>
        <td>Web Address: <a href="<?php echo $row_Clubs['../Admin/Clubs_Affiliates/ClubLink']; ?>" target="_blank"><?php echo $row_Clubs['ClubLink']; ?></a></td>
        <td>&nbsp;</td>
      </tr>
      </table>
  </div>
  <?php } while ($row_Clubs = mysql_fetch_assoc($Clubs)); ?>
</body>
</html>
<?php
mysql_free_result($Clubs);
?>
