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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO tourn_archives_results (ID, tourn_id, tourn_year, win, rup, brk, break, brk_shared1, brk_shared2, brk_comment, venue, why_not_run) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['tourn_id'], "int"),
                       GetSQLValueString($_POST['tourn_year'], "date"),
                       GetSQLValueString($_POST['win'], "int"),
                       GetSQLValueString($_POST['rup'], "int"),
                       GetSQLValueString($_POST['brk'], "int"),
                       GetSQLValueString($_POST['break'], "int"),
                       GetSQLValueString($_POST['brk_shared1'], "int"),
                       GetSQLValueString($_POST['brk_shared2'], "int"),
                       GetSQLValueString($_POST['brk_comment'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['why_not_run'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../tourn_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_ins = "SELECT tourn_archives_results.tourn_id, tourn_archives_results.tourn_year, win, rup, brk, break, brk_shared1, brk_shared2, brk_comment, why_not_run, tourn_name FROM tourn_archives_results LEFT JOIN tourn_archives ON tourn_archives.tournament_ID = tourn_archives_results.tourn_id WHERE tourn_ID = '$tid'";
$arch_ins = mysql_query($query_arch_ins, $connvbsa) or die(mysql_error());
$row_arch_ins = mysql_fetch_assoc($arch_ins);
$totalRows_arch_ins = mysql_num_rows($arch_ins);

mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT * FROM tourn_archives WHERE tournament_ID='$tid'";
$arch_tourn = mysql_query($query_arch_tourn, $connvbsa) or die(mysql_error());
$row_arch_tourn = mysql_fetch_assoc($arch_tourn);
$totalRows_arch_tourn = mysql_num_rows($arch_tourn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>

<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../ScriptLibrary/jquery-latest.pack.js"></script>

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
<table width="800" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><span class="red_bold">Insert Results into the archives for <?php echo $row_arch_tourn['tourn_name']; ?></span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center" cellpadding="3" cellspacing="3">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament year:</td>
          <td><input type="text" name="tourn_year" value="" size="32" /></td>
          <td colspan="2" rowspan="2">
          
          <!--Nested Table -->
        	<table>
            	<tr>
                	<td><input name="quicksearch" type="text" id="quick_search" size="20" /></td><td nowrap="nowrap">Quick Surname Search</td>
           		 </tr>
            	<tr>
                	<td><div id="qs_result" align="left" style="z-index: 1; position: absolute; "></div></td><td>&nbsp;</td>
            	</tr>
        	</table>
            <!--End Nested Table -->
          
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Winner (Player ID):</td>
          <td><input type="text" name="win" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Runner up (Player ID):</td>
          <td><input type="text" name="rup" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break (Player ID):</td>
          <td><input type="text" name="brk" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Break total:</td>
          <td><input type="text" name="break" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Brkshare 1:</td>
          <td><input type="text" name="brk_shared1" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Brk share 2:</td>
          <td><input type="text" name="brk_shared2" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Break comment:</td>
          <td><input type="text" name="brk_comment" value="" size="32" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Venue:</td>
          <td><input type="text" name="venue" value="" size="50" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Reason if tournament not run in this year:</td>
          <td><input type="text" name="why_not_run" value="" size="50" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert results" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="tourn_id" value="<?php echo $tid; ?>" />
      <input type="hidden" name="MM_insert" value="form2" />
</form>
    <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($arch_ins);

mysql_free_result($arch_tourn);
?>
<script type="text/javascript">
    $(function(){
        $('#quick_search').keyup(function(e){
            console.log("Got call");
            var input = $(this).val();
            input = input.trim();
            if(input.length == 0) {
                return;
            }
            $.ajax({
                type: "get",
                url: "../ajax/quick_member_search.php",
                data: {last_name: input},
                async: true,
                success: function(data){
                    var member = $.parseJSON(data);
                    $('#qs_result').html('');
                    for(x = 0; x < member.length; x++){
                        $('#qs_result').prepend('<div>' + member[x].MemberID + ' ' + member[x].FirstName + ' ' + member[x].LastName +  '</div>'); //Fills the #auto div with the options
                    }
                }
            })
        })
    });
</script>
