<?php require_once('../../Connections/connvbsa.php'); ?>
<?php include('../../security_header.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
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

$bm = "-1";
if (isset($_GET['bm'])) {
  $bm = $_GET['bm'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Users = "SELECT id, board_member_id, name, MobilePhone, hashed_password, members.Email, username, board_desc, display, register_year, order_display, assist, comment, usertype FROM vbsaorga_users  LEFT JOIN members ON MemberID = board_member_id WHERE id='$bm'";
$Users = mysql_query($query_Users, $connvbsa) or die(mysql_error());
$row_Users = mysql_fetch_assoc($Users);
$totalRows_Users = mysql_num_rows($Users);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>


<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>

    <table align="center" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="4" align="center">&nbsp;</td>
        <td colspan="3" align="right" class="greenbg">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><span class="red_bold">Board/Assist Member Detail for: <?php echo $row_Users['name']; ?></span></td>
        <td colspan="3" align="right" class="greenbg"><a href="../board_members.php">Return to Board/Assist members</a></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right" bgcolor="#CCCCCC">Member ID</td>
        <td bgcolor="#CCCCCC"><?php echo $row_Users['board_member_id']; ?></td>
        <td bgcolor="#CCCCCC">&nbsp;</td>
        <td align="right" bgcolor="#CCCCCC">&nbsp;</td>
        <td colspan="3" rowspan="3" bgcolor="#CCCCCC"><p>Member ID, Mobile and Email come from the Members table, </p>
        <p>to edit any of these items please go to the Members section</p></td>
      </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Mobile:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Users['MobilePhone']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
          <td align="right" nowrap="nowrap" bgcolor="#CCCCCC" class="greenbg">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" bgcolor="#CCCCCC">Personal Email:</td>
          <td bgcolor="#CCCCCC"><?php echo $row_Users['Email']; ?></td>
          <td bgcolor="#CCCCCC">&nbsp;</td>
          <td align="right" nowrap="nowrap" bgcolor="#CCCCCC" class="greenbg">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="right" class="greenbg"><a href="board_members_edit.php?bm=<?php echo $row_Users['id']; ?>">edit user details</a> </td>
        </tr>
        <tr>
          <td align="right">Current Position:</td>
          <td align="left"><?php echo $row_Users['board_desc']; ?></td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
      </tr>
        <tr>
          <td align="center">VBSA Email (email displayed on website)</td>
          <td align="left"><?php echo $row_Users['username']; ?></td>
          <td align="center">&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td align="left">&nbsp;</td>
          <td align="center">&nbsp;</td>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="right">username:</td>
          <td><?php echo $row_Users['username']; ?></td>
          <td>&nbsp;</td>
          <td align="right" nowrap="nowrap" class="greenbg">User type:</td>
          <td><?php echo $row_Users['usertype']; ?></td>
          <td>&nbsp;</td>
          <td class="greenbg">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><span class="greenbg">Display order:</span></td>
          <td><?php echo $row_Users['order_display']; ?></td>
          <td>&nbsp;</td>
          <td align="right" nowrap="nowrap" class="greenbg">Joined the board:</td>
          <td><?php echo $row_Users['register_year']; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <!--<tr>
          <td align="right"><span class="greenbg">Display order:</span></td>
          <td><?php echo $row_Users['order_display']; ?></td>
          <td>&nbsp;</td>
          <td align="right" nowrap="nowrap" class="greenbg">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>-->
        <tr>
          <td align="right"><span class="greenbg">Display on website:</span></td>
          <td colspan="6">
          <?php 
      		  if ($row_Users['display']==1)
      		  {
      			  echo "Yes";
      		  }
      		  else
      		  {
      			  echo "No";
      		  }
		      ?>
        </td>
        </tr>
        <tr>
          <td align="right">Assistant to Board</td>
          <td colspan="6"><?php if($row_Users['assist']==1)echo "Yes (Does not hold a position on the board, assists only)"; else echo "No"; ?></td>
        </tr>
        <tr>
          <td align="right">Comment:</td>
          <td colspan="6" style="overflow: hidden;max-width: 400px; word-wrap: break-word;"><?php echo $row_Users['comment']; ?></td>
        </tr>
    </table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

mysql_free_result($Users);
?>
