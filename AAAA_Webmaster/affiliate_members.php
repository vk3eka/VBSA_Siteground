<?php require_once('../Connections/connvbsa.php'); ?>
<?php include('../security_header.php'); ?>
<?php

// set page url in session for insert / update files
$page = "../Admin_DB_VBSA/A_memb_index.php";
$_SESSION['page'] = $page;

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
//$query_aff_users = "SELECT id,vbsa_id, FirstName, LastName, Email, MobilePhone, username, hashed_password, usertype, block, sendEmail, gid, registerDate, lastvisitDate, activation FROM vbsaorga_users2 LEFT JOIN members ON members.MemberID = vbsa_id ORDER BY usertype ASC";
$query_aff_users = "SELECT id, vbsa_id, FirstName, LastName, Email, MobilePhone, hashed_password, usertype, block, sendEmail, gid, registerDate, lastvisitDate, activation FROM vbsaorga_users2 LEFT JOIN members ON members.MemberID = vbsa_id ORDER BY usertype ASC";
$aff_users = mysql_query($query_aff_users, $connvbsa) or die(mysql_error());
$row_aff_users = mysql_fetch_assoc($aff_users);
$totalRows_aff_users = mysql_num_rows($aff_users);

//echo($query_aff_users . "<br>");
mysql_select_db($database_connvbsa, $connvbsa);
$query_fix_list = "SELECT * FROM affiliate_extra_help WHERE file_type='fixture' ";
$fix_list = mysql_query($query_fix_list, $connvbsa) or die(mysql_error());
$row_fix_list = mysql_fetch_assoc($fix_list);
$totalRows_fix_list = mysql_num_rows($fix_list);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_list = "SELECT * FROM affiliate_extra_help WHERE file_type='tournament'";
$tourn_list = mysql_query($query_tourn_list, $connvbsa) or die(mysql_error());
$row_tourn_list = mysql_fetch_assoc($tourn_list);
$totalRows_tourn_list = mysql_num_rows($tourn_list);

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
        <td colspan="9" align="center">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><span class="red_bold">Affiliate - Access to database but not as a Board/Assitant Member  </span> To edit please go to the detail page &nbsp;&nbsp;&nbsp;<img src="../Admin_Images/detail.fw.png" alt="1" width="20" height="20" /></td>
       <td colspan="5" align="right" class="greenbg"><a href="user_files/affiliate_members_insert_id.php">Insert a new Affiliate Member</a></td>
      </tr>
      <tr>
        <td align="center">Member ID</td>
        <td>Name</td>
        <td align="left"> Email/Username</td>
        <td align="left">Phone</td>
        <!--<td>Username</td>-->
        <td>Password?</td>
        <td align="center">Access to</td>
      </tr>
       <?php do { 
      if(substr($row_aff_users['hashed_password'], 0, 6) == trim('$2y$10'))
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
          <td align="center"><?php echo $row_aff_users['vbsa_id']; ?></td>
          <td><?php echo $row_aff_users['FirstName']; ?> <?php echo $row_aff_users['LastName']; ?></td>
          <td align="left" class="page"><a href="mailto:<?php echo $row_aff_users['Email']; ?>" target="_blank"><?php echo $row_aff_users['Email']; ?></a></td>
          <td align="left" class="page"><a href="tel:<?php echo $row_aff_users['MobilePhone']; ?>"><?php echo $row_aff_users['MobilePhone']; ?></a></td>
          <!--<td><?php echo $row_aff_users['username']; ?></td>-->
          <td align="center"><input type=checkbox <?php echo($checked); ?> disabled></td>
          <td><?php echo $row_aff_users['usertype']; ?></td>
          <td width="50" align="center" nowrap="nowrap"><a href="user_files/affiliate_members_detail.php?bm=<?php echo $row_aff_users['vbsa_id']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" /></a></td>
          <td width="50" align="center"><a href="user_files/affiliate_member_delete_confirm.php?bm_del=<?php echo $row_aff_users['vbsa_id']; ?>"><img src="../Admin_Images/Trash.fw.png" width="20" height="20" /></a></td>
        </tr>
        <?php } while ($row_aff_users= mysql_fetch_assoc($aff_users)); ?>
    </table>
<p>&nbsp;</p>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="red_bold">Affiliate members - Help files (fixtures)</td>
  </tr>
  <tr>
    <td align="center">ID</td>
    <td align="left">File type</td>
    <td align="left">File Description</td>
    <td align="left">Uploaded file name</td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg"><a href="user_files/affiliate_help_insert.php">Insert new</a></td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_fix_list['id']; ?></td>
      <td align="left"><?php echo $row_fix_list['file_type']; ?></td>
      <td align="left"><?php echo $row_fix_list['file_desc']; ?></td>
      <td align="left"><?php echo $row_fix_list['file_name']; ?></td>
      <td align="center" class="greenbg"><a href="Affiliate_help_upload/<?php echo $row_fix_list['file_name']; ?>">Download</a></td>
      <td align="center"><a href="user_files/affiliate_help_delete_confirm.php?id=<?php echo $row_fix_list['id']; ?>"><img src="../Admin_Images/Trash.fw.png" height="20" title="Delete permanently" /></a></td>
    </tr>
    <?php } while ($row_fix_list = mysql_fetch_assoc($fix_list)); ?>
</table>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td align="center">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td align="left">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="red_bold">Affiliate members - Help files (tournaments)</td>
  </tr>
  <tr>
    <td align="center">ID</td>
    <td align="left">File type</td>
    <td align="left">File Description</td>
    <td align="left">Uploaded file name</td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg"><a href="user_files/affiliate_help_insert.php">Insert new</a></td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="center"><?php echo $row_tourn_list['id']; ?></td>
    <td align="left"><?php echo $row_tourn_list['file_type']; ?></td>
    <td align="left"><?php echo $row_tourn_list['file_desc']; ?></td>
    <td align="left"><?php echo $row_tourn_list['file_name']; ?></td>
    <td align="center" class="greenbg"><a href="Affiliate_help_upload/<?php echo $row_tourn_list['file_name']; ?>">Download</a></td>
    <td align="center"><a href="user_files/affiliate_help_delete_confirm.php?id=<?php echo $row_tourn_list['id']; ?>"><img src="../Admin_Images/Trash.fw.png" alt="" height="20" title="Delete permanently" /></a></td>
  </tr>
  <?php } while ($row_tourn_list = mysql_fetch_assoc($tourn_list)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($aff_users);

mysql_free_result($fix_list);

mysql_free_result($tourn_list);
?>
