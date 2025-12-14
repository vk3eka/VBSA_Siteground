<?php require_once('../Connections/connvbsa.php'); ?><?php
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
$query_CurrentMemb = "SELECT members.MemberID, LastName, FirstName, MobilePhone, Email, paid_memb, LifeMember, referee, memb_by, promo_optin_out, promo_pref FROM members WHERE ((paid_memb=20 AND YEAR(paid_date)=YEAR(NOW( ) ))  OR LifeMember=1  OR totplayed_curr+totplaybill_curr>3  OR totplayed_prev+totplaybill_prev>3  OR referee=1) AND (MemberID != 1 AND MemberID != 100 AND MemberID != 1000 AND Deceased !=1) ORDER BY LastName,
FirstName"; $CurrentMemb = mysql_query($query_CurrentMemb, $connvbsa) or die(mysql_error());
$row_CurrentMemb = mysql_fetch_assoc($CurrentMemb);
$totalRows_CurrentMemb = mysql_num_rows($CurrentMemb);

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

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
</div>

<table width="1000" border="0" align="center" cellpadding="8" cellspacing="0">
	<tr>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  	</tr>
</table>

  <table align="center">
  
  <tr>
    <td align="center" style="border: 1px solid black;"><p class="red_text">&nbsp;</p>
      <p class="red_text">Download a .csv file for all Current members and referees</p>
      <p class="red_text">&nbsp;</p>
      <p>&nbsp;</p>
      <p style="padding-left:15px; padding-right:15px">PLEASE NOTE: Life Member, Referee, Promo columns are the results of a checkbox</p>
      <p>0=&quot;No&quot; (unchecked) , 1 = &quot;Yes&quot; (checked).</p>
      <p>&nbsp;</p>
    </td>
    </tr>
</table>

<table align="center">
              
              <tr>
                <td  class="greenbg">&nbsp;</td>
                <td align="right">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <?php $genCSVmemb = "download_csv_members_current.php?id="."members_current_" .date("M_Y");?><td  class="greenbg"><span class="red_text">Current Members</span></td>
                <td align="right">Total: </td>
                <td align="left"><?php echo $totalRows_CurrentMemb ?></td>
                <td class="greenbg"><a href="<?PHP echo $genCSVmemb?>">Download .csv</a></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="red_text">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <th>ID</th>
                <th align="left">Last Name</th>
                <th align="left">First Name</th>
                <th align="left">Mobile</th>
                <th align="left">Email</th>
                <th align="center">Paid Member?</th>
                <th align="center">Life Member?</th>
                <th align="center">Referee?</th>
                <th align="center">Promo Yes?</th>
                <th align="left">Promo Preference</th>
                <th align="left">&nbsp;</th>
              </tr>
              <?php do { ?>
                <tr>
                  <td align="center"><?php echo $row_CurrentMemb['MemberID']; ?></td>
                  <td align="left"><?php echo $row_CurrentMemb['LastName']; ?></td>
                  <td align="left"><?php echo $row_CurrentMemb['FirstName']; ?></td>
                  <td align="left"><?php echo $row_CurrentMemb['MobilePhone']; ?></td>
                  <td align="left"><?php echo $row_CurrentMemb['Email']; ?></td>
                  <td align="center"><?php echo $row_CurrentMemb['paid_memb']; ?></td>
                  <td align="center"><?php echo $row_CurrentMemb['LifeMember']; ?></td>
                  <td align="center"><?php echo $row_CurrentMemb['referee']; ?></td>
                  <td align="center"><?php echo $row_CurrentMemb['promo_optin_out']; ?></td>
                  <td align="left"><?php echo $row_CurrentMemb['promo_pref']; ?></td>
                  <td align="left">&nbsp;</td>
                </tr>
                <?php } while ($row_CurrentMemb = mysql_fetch_assoc($CurrentMemb)); ?>
            </table>
            

</body>
</html>
<?php
mysql_free_result($CurrentMemb);

?>