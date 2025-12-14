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

$editFormAction = 'ajax/res_insert.php';
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tourn_history (ID, tourn_id, tourn_year, win, rup, brk, break, brk_shared1, brk_shared2, brk_comment, venue, why_not_run) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
  echo "Error!";
  exit;
}

$tid = "-1";
if (isset($_GET['tid'])) {
  $tid = $_GET['tid'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_arch_tourn = "SELECT tourn_history.tourn_id, tourn_history.tourn_year, win, rup, brk, break, brk_shared1, brk_shared2, brk_comment, why_not_run, tourn_name FROM tourn_history LEFT JOIN tourn_archives ON tourn_archives.tournament_ID = tourn_history.tourn_id WHERE tourn_ID = '$tid'";
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
<script type="text/javascript" src="../../../Scripts/AC_RunActiveContent.js"></script>
</head>

<body>
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"  onsubmit="return doit()">
      <table align="center" cellpadding="5" cellspacing="5">
        <tr valign="baseline">
          <td align="right" nowrap="nowrap" class="red_text">Insert item for the selected year into: </td>
          <td class="red_bold"><?php echo $row_arch_tourn['tourn_name']; ?></td>
          <td class="red_bold">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament year:</td>
          <td><input type="text" name="tourn_year" value="" size="32" /></td>
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
          <td nowrap="nowrap" align="right">Winner (Player ID):</td>
          <td><input type="text" name="win" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Runner up (Player ID):</td>
          <td><input type="text" name="rup" value="" size="32" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break By (Player ID):</td>
          <td><input type="text" name="brk" value="" size="32" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">High Break Total:</td>
          <td><input type="text" name="break" value="" size="32" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Venue:</td>
          <td><input type="text" name="venue" value="" size="50" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Shared 1 (Player ID):</td>
          <td colspan="2"><input type="text" name="brk_shared1" value="" size="32" />
            Insert first player ID if high break shared by 2 players</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Shared 2 (Player ID):</td>
          <td colspan="2"><input type="text" name="brk_shared2" value="" size="32" />
Insert second player ID if high break shared by 2 players</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">If shared by more than 2 (text):</td>
          <td colspan="2"><input type="text" name="brk_comment" value="" size="32" /> 
            Use only if high break shared by more than 2, eg Shared by 4 players</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Reason if tournament not run in this year </td>
          <td><input type="text" name="why_not_run" value="" size="60" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert record" /></td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <input type="hidden" name="ID" value="" />
      <input type="hidden" name="tourn_id" value="<?php echo $tid; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
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
                url: "ajax/quick_member_search.php",
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
</script>

<script type="text/javascript">

function doit(){     
	
	var tx = jQuery.noConflict();
        tx.ajax({
            url     : '<?PHP echo $editFormAction ?>',
            type    : tx('#form1').attr('method'),
            data    : tx('#form1').serialize(),
            success : function( data ) {
                        alert('Inserted Succesfully!');
						location.reload(); 
                      },
            error   : function( xhr, err ) {
                        alert('Error');     
                      }
        }); 
        return false;
}

</script>
