<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
<?php

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}
//echo("Username " . $_SESSION['MM_Username'] . "<br>");

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

mysql_select_db($database_connvbsa, $connvbsa);
$query_Board = "SELECT id, board_member_id, name, vbsaorga_users.email, vbsaorga_users.username, hashed_password, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=0 ORDER BY order_display ASC";
$Board = mysql_query($query_Board, $connvbsa) or die(mysql_error());
$row_Board = mysql_fetch_assoc($Board);
$totalRows_Board = mysql_num_rows($Board);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Access = "SELECT id, board_member_id, name, vbsaorga_users.email, vbsaorga_users.username, hashed_password, usertype, display, register_year, order_display, `comment`, MemberID, BoardMemb, board_desc, assist FROM vbsaorga_users, members WHERE board_member_id=MemberID AND assist=1 ORDER BY order_display ASC";
$Access = mysql_query($query_Access, $connvbsa) or die(mysql_error());
$row_Access = mysql_fetch_assoc($Access);
$totalRows_Access = mysql_num_rows($Access);

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

    <table align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="5" align="center">&nbsp;</td>
        <td colspan="7" align="right" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" align="center" valign="middle"><span class="red_bold">Current Board Members </span> To edit please go to the detail page &nbsp;&nbsp;&nbsp;<img src="../Admin_Images/detail.fw.png" width="20" height="20" /></td>
        <td colspan="7" align="right" class="greenbg"><a href="user_files/board_members_insert_id.php">Insert a new Board Member</a></td>
      </tr>
      <tr>
        <td align="center">Member ID</td>
        <td>Name</td>
        <td>Position</td>
        <td>Display Email?</td>
        <td>User Type</td>
        <td>Password?</td>
        <td align="center">Display?</td>
        <td align="center">Assist</td>
        <td align="center">Joined</td>
        <td align="center">Display Order</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php do { 
        if(substr($row_Board['hashed_password'], 0, 6) == trim('$2y$10'))
        {
          $checked = ' checked';
          //echo("Hashed OK " . substr($row_Board['hashed_password'], 0, 6) . "<br>");
        }
        else
        {
          $checked = '';
          //echo("Hashed NULL " . substr($row_Board['hashed_password'], 0, 6) . "<br>");
        }
      ?>
        <tr>
          <td align="center"><?php echo $row_Board['board_member_id']; ?></td>
          <td><?php echo $row_Board['name']; ?></td>
          <td><?php echo $row_Board['board_desc']; ?></td>
          <td><?php echo $row_Board['email_vbsa']; ?></td>
          <td><?php echo $row_Board['usertype']; ?></td>
          <td align="center"><input type=checkbox <?php echo($checked); ?> disabled></td>
          <td align="center"><?php echo $row_Board['display']; ?></td>
          <td align="center"><?php echo $row_Board['assist']; ?></td>
          <td align="center"><?php echo $row_Board['register_year']; ?></td>
          <td align="center"><?php echo $row_Board['order_display']; ?></td>
          <td width="50" align="center" nowrap="nowrap"><a href="user_files/board_members_detail.php?bm=<?php echo $row_Board['id']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" /></a></td>
          <td width="50" align="center"><a href="user_files/board_member_delete_confirm.php?bm_del=<?php echo $row_Board['board_member_id']; ?>"><img src="../Admin_Images/Trash.fw.png" width="20" height="20" /></a></td>
        </tr>
        <?php } while ($row_Board = mysql_fetch_assoc($Board)); ?>
    </table>
    <table align="center" cellpadding="3" cellspacing="3">
     <tr>
          <td colspan="5" align="center">&nbsp;</td>
          <td colspan="7" align="right" class="red_bold">&nbsp;</td>
        </tr>
    <tr>
      <td colspan="5" align="center"><span class="red_bold">Assist - Access to database but not a Board Member  </span> To edit please go to the detail page &nbsp;&nbsp;&nbsp;<img src="../Admin_Images/detail.fw.png" alt="1" width="20" height="20" /></td>
       <td colspan="7" align="right" class="greenbg"><a href="user_files/board_members_insert_id.php">Insert a new Assist Member</a></td>
    </tr>
    <tr>
      <td align="center">Member ID</td>
      <td>Name</td>
      <td>Position</td>
      <td>Display Email?</td>
      <td>User Type</td>
      <td>Password?</td>
      <td align="center">Display?</td>
      <td align="center">Assist</td>
      <td align="center">Joined</td>
      <td align="center">Display Order</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { 
      if(substr($row_Access['hashed_password'], 0, 6) == trim('$2y$10'))
      {
        $checked = ' checked';
        //echo("Hashed OK " . substr($row_Access['hashed_password'], 0, 6) . "<br>");
      }
      else
      {
        $checked = '';
        //echo("Hashed NULL " . substr($row_Acess['hashed_password'], 0, 6) . "<br>");
      }
    ?>
    <tr>
      <td align="center"><?php echo $row_Access['board_member_id']; ?></td>
      <td><?php echo $row_Access['name']; ?></td>
      <td><?php echo $row_Access['board_desc']; ?></td>
      <td><?php echo $row_Access['email_vbsa']; ?></td>
      <td><?php echo $row_Access['usertype']; ?></td>
      <td align="center"><input type=checkbox <?php echo($checked); ?> disabled></td>
      <td align="center"><?php echo $row_Access['display']; ?></td>
      <td align="center"><?php echo $row_Access['assist']; ?></td>
      <td align="center"><?php echo $row_Access['register_year']; ?></td>
      <td align="center"><?php echo $row_Access['order_display']; ?></td>
      <td width="50" align="center" nowrap="nowrap"><a href="user_files/board_members_detail.php?bm=<?php echo $row_Access['id']; ?>"><img src="../Admin_Images/detail.fw.png" alt="1" width="20" height="20" /></a></td>
      <td width="50" align="center"><a href="user_files/board_member_delete_confirm.php?bm_del=<?php echo $row_Access['id']; ?>"><img src="../Admin_Images/Trash.fw.png" alt="1" width="20" height="20" /></a></td>
    </tr>
    <?php } while ($row_Access = mysql_fetch_assoc($Access)); ?>
  </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Board);

mysql_free_result($Access);
?>
