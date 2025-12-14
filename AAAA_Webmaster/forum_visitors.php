<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster";
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

$MM_restrictGoTo = "Webmaster_access_denied.php";
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

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_connvbsa, $connvbsa);
$query_visitors_all = "SELECT vbsaorga_users.id, vbsaorga_users.name, MAX( vbsa_login_record.visit_date ) AS lastvisit FROM vbsaorga_users LEFT JOIN vbsa_login_record ON vbsaorga_users.username = vbsa_login_record.username WHERE vbsaorga_users.username <> 'Taman' AND vbsaorga_users.username <> 'PCosgriff' GROUP BY vbsaorga_users.username ORDER BY lastvisit DESC";
$visitors_all = mysql_query($query_visitors_all, $connvbsa) or die(mysql_error());
$row_visitors_all = mysql_fetch_assoc($visitors_all);
$totalRows_visitors_all = mysql_num_rows($visitors_all);

$maxRows_visit_all = 25;
$pageNum_visit_all = 0;
if (isset($_GET['pageNum_visit_all'])) {
  $pageNum_visit_all = $_GET['pageNum_visit_all'];
}
$startRow_visit_all = $pageNum_visit_all * $maxRows_visit_all;

mysql_select_db($database_connvbsa, $connvbsa);
$query_visit_all = "SELECT vbsaorga_users.name, vbsa_login_record.visited, vbsa_login_record.visit_date FROM vbsaorga_users LEFT JOIN vbsa_login_record ON vbsaorga_users.username = vbsa_login_record.username WHERE name <> 'Taman' AND vbsaorga_users.username <> 'PCosgriff' ORDER BY visit_date DESC";
$query_limit_visit_all = sprintf("%s LIMIT %d, %d", $query_visit_all, $startRow_visit_all, $maxRows_visit_all);
$visit_all = mysql_query($query_limit_visit_all, $connvbsa) or die(mysql_error());
$row_visit_all = mysql_fetch_assoc($visit_all);

if (isset($_GET['totalRows_visit_all'])) {
  $totalRows_visit_all = $_GET['totalRows_visit_all'];
} else {
  $all_visit_all = mysql_query($query_visit_all, $connvbsa);
  $totalRows_visit_all = mysql_num_rows($all_visit_all);
}
$totalPages_visit_all = ceil($totalRows_visit_all/$maxRows_visit_all)-1;

$queryString_visit_all = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_visit_all") == false && 
        stristr($param, "totalRows_visit_all") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_visit_all = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_visit_all = sprintf("&totalRows_visit_all=%d%s", $totalRows_visit_all, $queryString_visit_all);
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

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table align="center">
  <tr>
    <td align="center" class="page"><a href="javascript:history.go(-1)">Return to previous page</a></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td class="greenbg"><a href="create_members_SMS.php">Create new SMS tables</a></td>
      <td class="greenbg"><a href="forum_visitors.php">Forum visitors</a></td>
      <td class="greenbg"><a href="Webmaster_tables.php">Database tables</a></td>
    </tr>
    <tr>
      <td class="greenbg">&nbsp;</td>
      <td class="greenbg">&nbsp;</td>
      <td class="greenbg">&nbsp;</td>
    </tr>
</table>
<table align="center">
  <tr>
    
    <!--nested table left -->
    <td valign="top">    
  <table cellpadding="5">
    <tr>
      <td colspan="4" class="red_bold">Most recent visit</td>
      </tr>
    <tr>
      <td>ID</td>
      <td>Name</td>
      <td>&nbsp;</td>
      <td>Last Visit</td>
      </tr>
    <?php do { 
      //echo((date("Y", strtotime($row_visitors_all['lastvisit'])) != '1970' ). "<br>");
      if(date("Y", strtotime($row_visitors_all['lastvisit'])) != '1970')
      {
      ?>
      <tr>
        <td><?php echo $row_visitors_all['id']; ?></td>
        <td><?php echo $row_visitors_all['name']; ?></td>
        <td class="greenbg"><a href="forum_visitors_last10.php?last10=<?php echo $row_visitors_all['id']; ?>">last 10 visits</a></td>
        <td>
          <?php $newDate = date("l jS F Y", strtotime($row_visitors_all['lastvisit'])); echo $newDate; ?>  - 
          <?php $newDate = date("g:ia ", strtotime($row_visitors_all['lastvisit'])); echo $newDate; ?>
          </td>
        </tr>
      <?php 
      }
    }
     while ($row_visitors_all = mysql_fetch_assoc($visitors_all)); ?>
  </table>       
    </td>
    <!--close nested table left -->
    <td width="20">&nbsp;</td>
    <!--ested table right -->
    <td valign="top">    
  <table cellpadding="5">
    <tr>
      <td colspan="3" class="red_bold">All visits (25 per page)</td>
      </tr>
    <tr>
      <td>Name</td>
      <td>Where Visited </td>
      <td>Date &amp; time of visit</td>
      </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_visit_all['name']; ?></td>
        <td><?php echo $row_visit_all['visited']; ?></td>
        <td><?php $newDate = date("l jS F Y", strtotime($row_visit_all['visit_date'])); echo $newDate; ?>  - 
          <?php $newDate = date("g:ia ", strtotime($row_visit_all['visit_date'])); echo $newDate; ?>
          </td>
        </tr>
      <?php } while ($row_visit_all = mysql_fetch_assoc($visit_all)); ?>
  </table>
  <table align="center" cellpadding="5" class="page">
    <tr>
      <td><a href="<?php printf("%s?pageNum_visit_all=%d%s", $currentPage, min($totalPages_visit_all, $pageNum_visit_all + 1), $queryString_visit_all); ?>">Next</a></td>
      <td><a href="<?php printf("%s?pageNum_visit_all=%d%s", $currentPage, max(0, $pageNum_visit_all - 1), $queryString_visit_all); ?>">Previous</a></td>
      <td><a href="<?php printf("%s?pageNum_visit_all=%d%s", $currentPage, 0, $queryString_visit_all); ?>">First</a></td>
      <td><a href="<?php printf("%s?pageNum_visit_all=%d%s", $currentPage, $totalPages_visit_all, $queryString_visit_all); ?>">Last</a></td>
      </tr>
  </table>  
      
    </td>
    <!--close nested table right -->
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($visitors_all);

mysql_free_result($visit_all);
?>
