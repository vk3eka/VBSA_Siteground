<?php require_once('../Connections/connvbsa.php'); 

if (!isset($_SESSION)) {
  session_start();
}

// set page url in session for insert / update files
$detail = "../vbsa_participating_clubs.php"; 
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

$redirect = "-1";
if (isset($_GET['redirect'])) {
  $redirect = $_GET['redirect'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsaclubs = "SELECT ClubNumber, ClubTitle, ClubTables, PennantTables, VBSAteam FROM clubs  WHERE VBSAteam=1 ORDER BY ClubTitle";
$vbsaclubs = mysql_query($query_vbsaclubs, $connvbsa) or die(mysql_error());
$row_vbsaclubs = mysql_fetch_assoc($vbsaclubs);
$totalRows_vbsaclubs = mysql_num_rows($vbsaclubs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<table align="center">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap" class="red_bold">VBSA participating Clubs in <?php echo date("Y")?> </td>
  </tr>
  <tr>
    <td align="center"><span class="red_bold">(where VBSAclub (Clubs table) is checked)</span></td>
  </tr>
  <tr>
    <td align="center">&nbsp; </td>
  </tr>
  <tr>
    <td align="center" class="greenbg"><a href="A_Club_index.php?">Return to Clubs Index</a></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<table border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td align="center">Club ID</td>
    <td align="left">Club Title</td>
    <td align="center">Club Tables</td>
    <td align="center">Pennant Tables</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_vbsaclubs['ClubNumber']; ?></td>
      <td align="left"><?php echo $row_vbsaclubs['ClubTitle']; ?></td>
      <td align="center"><?php echo $row_vbsaclubs['ClubTables']; ?></td>
      <td align="center"><?php echo $row_vbsaclubs['PennantTables']; ?></td>
      <td align="center"><a href="user_files/clubs_detail.php?club_id=<?php echo $row_vbsaclubs['ClubNumber']; ?>&redirect=<?php echo $redirect; ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="View club details"/></a></td>
    </tr>
    <?php } while ($row_vbsaclubs = mysql_fetch_assoc($vbsaclubs)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($vbsaclubs);
?>
