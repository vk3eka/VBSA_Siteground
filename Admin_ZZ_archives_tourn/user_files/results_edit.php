<?php require_once('../../Connections/connvbsa.php'); ?>
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


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tourn_archives_results SET tourn_year=%s, win=%s, rup=%s, brk=%s, break=%s, brk_shared1=%s, brk_shared2=%s, brk_comment=%s, venue=%s, why_not_run=%s WHERE ID=%s",
                       GetSQLValueString($_POST['tourn_year'], "date"),
                       GetSQLValueString($_POST['win'], "int"),
                       GetSQLValueString($_POST['rup'], "int"),
                       GetSQLValueString($_POST['brk'], "int"),
                       GetSQLValueString($_POST['break'], "int"),
                       GetSQLValueString($_POST['brk_shared1'], "int"),
                       GetSQLValueString($_POST['brk_shared2'], "int"),
                       GetSQLValueString($_POST['brk_comment'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['why_not_run'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../tourn_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$record_id = "-1";
if (isset($_GET['record_id'])) {
  $record_id = $_GET['record_id'];
}

;mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT ID, tourn_archives_results.tourn_id, tourn_archives_results.tourn_year, win, rup, brk, break, brk_shared1, brk_shared2, CONCAT(t1.FirstName, ' ', t1.LastName) AS winner,  CONCAT(t2.FirstName, ' ', t2.LastName) AS runnerup,  CONCAT(t3.FirstName, ' ', t3.LastName) AS brkby, CONCAT(t4.FirstName, ' ', t4.LastName) AS shared1, CONCAT(' / ',t5.FirstName, ' ', t5.LastName) AS shared2, brk_comment, venue, why_not_run, tourn_name FROM tourn_archives_results  LEFT JOIN tourn_archives ON tourn_archives.tournament_ID = tourn_archives_results.tourn_id LEFT JOIN members t1 ON t1.MemberID = win  LEFT JOIN members t2 ON t2.MemberID = rup  LEFT JOIN members t3 ON t3.MemberID = brk  LEFT JOIN members t4 ON t4.MemberID = brk_shared1 LEFT JOIN members t5 ON t5.MemberID = brk_shared2 WHERE ID = '$record_id'";
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

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="800" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><span class="red_bold">Edit Results for <?php echo $row_arch_tourn['tourn_name']; ?></span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" cellpadding="5" cellspacing="5">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td rowspan="2">
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
          <td nowrap="nowrap" align="right">Tournament year:</td>
          <td><input type="text" name="tourn_year" value="<?php echo htmlentities($row_arch_tourn['tourn_year'], ENT_COMPAT, 'utf-8'); ?>" size="15" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Winner (Player ID):</td>
          <td colspan="2">
          <input type="text" name="win" value="<?php echo htmlentities($row_arch_tourn['win'], ENT_COMPAT, 'utf-8'); ?>" size="15" />
          &nbsp;&nbsp; (<?php echo $row_arch_tourn['winner']; ?>)
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Runner up (Player ID):</td>
          <td colspan="2">
          <input type="text" name="rup" value="<?php echo htmlentities($row_arch_tourn['rup'], ENT_COMPAT, 'utf-8'); ?>" size="15" />
          &nbsp;&nbsp; (<?php echo $row_arch_tourn['runnerup']; ?>)</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break By (Player ID):</td>
          <td colspan="2">
          <input type="text" name="brk" value="<?php echo htmlentities($row_arch_tourn['brk'], ENT_COMPAT, 'utf-8'); ?>" size="15" />
          &nbsp;&nbsp; (<?php echo $row_arch_tourn['brkby']; ?>) Leave blank if high break is shared</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break Total:</td>
          <td colspan="2"><input type="text" name="break" value="<?php echo htmlentities($row_arch_tourn['break'], ENT_COMPAT, 'utf-8'); ?>" size="15" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Venue:</td>
          <td colspan="2"><input type="text" name="venue" value="<?php echo htmlentities($row_arch_tourn['venue'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Shared 1 (Player ID):</td>
          <td colspan="2">
          <input type="text" name="brk_shared1" value="<?php echo htmlentities($row_arch_tourn['brk_shared1'], ENT_COMPAT, 'utf-8'); ?>" size="15" />
          &nbsp;&nbsp; (<?php if(!empty($row_arch_tourn['shared1'])) echo $row_arch_tourn['shared1']; else echo "Insert first player ID if high break shared by 2 players"; ?>)
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Shared 2 (Player ID):</td>
          <td colspan="2">
          <input type="text" name="brk_shared2" value="<?php echo htmlentities($row_arch_tourn['brk_shared2'], ENT_COMPAT, 'utf-8'); ?>" size="15" />
          &nbsp;&nbsp; (<?php if(!empty($row_arch_tourn['shared2'])) echo $row_arch_tourn['shared2']; else echo "Insert first player ID if high break shared by 2 players"; ?>)
          </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">If shared by more than 2 (text):</td>
          <td colspan="2">
          <input type="text" name="brk_comment" value="<?php echo htmlentities($row_arch_tourn['brk_comment'], ENT_COMPAT, 'utf-8'); ?>" size="50" /> 
            Use only if high break shared by more than 2, eg Shared by 4 players</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Reason if tournament not run in this year </td>
          <td><input type="text" name="why_not_run" value="<?php echo htmlentities($row_arch_tourn['why_not_run'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update record" /></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="<?php echo $row_arch_tourn['ID']; ?>" />
      <input type="hidden" name="MM_update" value="form1" />
</form>
    <p>&nbsp;</p>
</body>
</html>
<?php
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

