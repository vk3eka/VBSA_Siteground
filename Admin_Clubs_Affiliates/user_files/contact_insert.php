<?php require_once('../../Connections/connvbsa.php'); ?>
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



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO clubs_contact (cont_id, club_id, cont_memb_id, cont_type, cont_title) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cont_id'], "int"),
                       GetSQLValueString($_POST['club_id'], "int"),
                       GetSQLValueString($_POST['cont_memb_id'], "int"),
                       GetSQLValueString($_POST['cont_type'], "text"),
                       GetSQLValueString($_POST['cont_title'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "clubs_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$club_id = "-1";
if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_clubs = "SELECT * FROM clubs WHERE ClubNumber = '$club_id'";
$clubs = mysql_query($query_clubs, $connvbsa) or die(mysql_error());
$row_clubs = mysql_fetch_assoc($clubs);
$totalRows_clubs = mysql_num_rows($clubs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table width="800" align="center">
    <tr>
      <td align="left">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" class="red_bold">Insert a contact to : <?php echo $row_clubs['ClubTitle']; ?> </th>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr>
      <td align="left">You will need a member ID</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center">
          <form id="form3" name="form3" method="get" action="http://www.vbsa.org.au/Admin_DB_VBSA/search_by_LastName.php">
          <input name="searchthis" type="text" id="searchthis" size="12" />
          <input type="submit" value="Search Memb Hist Surname" />
          </form>
      </td>
    </tr>
  </table>
  
  <td> 
</td>
  
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
  
  <table align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td colspan="2" align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">Club ID:</td>
      <td><?php echo $row_clubs['ClubNumber']; ?></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">Contact Member ID:</td>
      <td><input name="cont_memb_id" type="text" id="cont_memb_id" value="" size="32" /></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">Contact type:</td>
      <td><select name="cont_type">
        <option value="Club Management" <?php if (!(strcmp("Club Management", ""))) {echo "SELECTED";} ?>>Club Management</option>
        <option value="Team organiser" <?php if (!(strcmp("Team organiser", ""))) {echo "SELECTED";} ?>>Team organiser</option>
        <option value="Invoice to" <?php if (!(strcmp("Invoice to", ""))) {echo "SELECTED";} ?>>Invoice to</option>
      </select></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">Contact title:</td>
      <td><input name="cont_title" type="text" id="cont_title" value="" size="32" /></td>
    </tr>
    <tr>
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="cont_id" value="" />
  <input type="hidden" name="club_id" value="<?php echo $club_id; ?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>



</body>
</html>
<?php
mysql_free_result($clubs);
?>




