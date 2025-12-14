<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/database_management/wa_appbuilder_php.php"); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer";
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

$MM_restrictGoTo = "../../page_error.php";
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

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn1 = "SELECT tourn_id, tourn_name, date_format( tourn_year, '%Y') AS Tyear, site_visible, tourn_type, tourn_draw, tourn_class FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn1 = mysql_query($query_tourn1, $connvbsa) or die(mysql_error());
$row_tourn1 = mysql_fetch_assoc($tourn1);
$totalRows_tourn1 = mysql_num_rows($tourn1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_entries = "SELECT DATE_FORMAT(tourn_date_ent,'%m') AS mnth, tourn_entry.ID, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.wcard, tourn_entry.ranked, tourn_entry.junior_cat, tourn_entry.entry_cal_year, tourn_entry.entry_fin_year, tourn_entry.tourn_date_ent FROM tourn_entry WHERE tournament_number = '$tourn_id'";
$tourn_entries = mysql_query($query_tourn_entries, $connvbsa) or die(mysql_error());
$row_tourn_entries = mysql_fetch_assoc($tourn_entries);
$totalRows_tourn_entries = mysql_num_rows($tourn_entries);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);?>
<?php
// WA DataAssist Multiple Inserts
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SERVER["HTTP_REFERER"]) && strpos(urldecode($_SERVER["HTTP_REFERER"]), urldecode($_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"])) > 0) && isset($_POST)) // Trigger
{
  if (!session_id()) session_start();
  $WA_loopedFields = array("ID", "tourn_type", "tourn_memb_id", "tournament_number", "amount_entry", "entered_by", "how_paid", "junior_cat", "entry_cal_year", "entry_fin_year");
  $WA_connection = $connvbsa;
  $WA_table = "tourn_entry";
  $WA_redirectURL = "../tournament_detail.php";
  if (function_exists("rel2abs")) $WA_redirectURL = $WA_redirectURL?rel2abs($WA_redirectURL,dirname(__FILE__)):"";
  $WA_keepQueryString = true;
  $WA_fieldNamesStr = "ID|tourn_type|tourn_memb_id|tournament_number|amount_entry|entered_by|how_paid|junior_cat|entry_cal_year|entry_fin_year";
  $WA_columnTypesStr = "none,none,NULL|',none,''|none,none,NULL|none,none,NULL|none,none,NULL|',none,''|',none,''|',none,''|',none,NULL|',none,NULL";
  $WA_insertIfNotBlank = "tourn_memb_id";
  $WA_fieldNames = explode("|", $WA_fieldNamesStr);
  $WA_columns = explode("|", $WA_columnTypesStr);
  $WA_connectionDB = $database_connvbsa;
  $WA_multipleInsertCounter = 0;
  mysql_select_db($WA_connectionDB, $WA_connection);
  while (WA_AB_checkMultiInsertLoopedFieldsExist($WA_loopedFields, $WA_multipleInsertCounter)) {
    if ($WA_insertIfNotBlank == "" || WA_AB_checkLoopedFieldsNotBlank(array($WA_insertIfNotBlank), $WA_multipleInsertCounter)) {
      $WA_fieldValuesStr = "".WA_AB_getLoopedFieldValue("ID", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("tourn_type", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("tourn_memb_id", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("tournament_number", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("amount_entry", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("entered_by", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("how_paid", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("junior_cat", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("entry_cal_year", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("entry_fin_year", $WA_multipleInsertCounter)  ."";
      $WA_fieldValues = explode($WA_AB_Split, $WA_fieldValuesStr);
      $insertParamsObj = WA_AB_generateInsertParams($WA_fieldNames, $WA_columns, $WA_fieldValues, -1);
      $WA_Sql = "INSERT INTO `" . $WA_table . "` (" . $insertParamsObj->WA_tableValues . ") VALUES (" . $insertParamsObj->WA_dbValues . ")";
      $MM_editCmd = mysql_query($WA_Sql, $WA_connection) or die(mysql_error());
    }
    $WA_multipleInsertCounter++;
  }
  if ($WA_redirectURL != "")  {
    if ($WA_keepQueryString && $WA_redirectURL != "" && isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] !== "" && sizeof($_POST) > 0) {
      $WA_redirectURL .= ((strpos($WA_redirectURL, '?') === false)?"?":"&").$_SERVER["QUERY_STRING"];
    }
    header("Location: ".$WA_redirectURL);
  }
}
?>
<?php
	// RepeatSelectionCounter_1 Initialization
	$RepeatSelectionCounter_1 = 0;
	$RepeatSelectionCounterBasedLooping_1 = true;
	$RepeatSelectionCounter_1_Iterations = "5";
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
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />

</head>


<body>
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="507" align="left"><span class="red_bold">Insert multiple players into:</span> <?php echo $row_tourn1['Tyear']; ?> <?php echo $row_tourn1['tourn_name']; ?></td>
    <td width="146" align="left"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="left"> Please Note: If a Junior is entering several age groups - Insert the ID for as many age groups as player is competing in, select the age groups in &quot;Junior Cat&quot; then enter 1 payment only, the rest as 0</td>
  </tr>
</table>
	<form method="post" name="form6" id="form6">
	  
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td align="center" valign="middle" nowrap="nowrap">Member ID:</td>
      <td align="center" valign="middle">$ Amount:</td>
      <td align="center" valign="middle">How paid:</td>
      <td align="center" valign="middle">Junior Category</td>
      <td align="center" valign="middle">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">
        <!--------- Nested table starts-------------------------->
        <table>
          <tr>
            <td><input name="quicksearch" type="text" id="quick_search" size="20" /></td><td>Quick Surname Search</td>
            </tr>
          <tr>
            <td><div id="qs_result" align="left" style="z-index: 1; position: absolute; "></div></td><td>&nbsp;</td>
            </tr>
        </table>
        <!--------- Nested table ends-------------------------->
      </td>
    </tr>
    
    <?php
	// RepeatSelectionCounter_1 Begin Loop
	$RepeatSelectionCounter_1_IterationsRemaining = $RepeatSelectionCounter_1_Iterations;
	while($RepeatSelectionCounter_1_IterationsRemaining--){
		if($RepeatSelectionCounterBasedLooping_1 || $row_None){
	?>
      
    <tr valign="baseline">
      <td nowrap="nowrap" align="center"><input type="hidden" name="ID_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" id="ID_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" value="1" />
      <input type="text" name="tourn_memb_id_<?php echo $RepeatSelectionCounter_1; ?>" id="<?php echo $RepeatSelectionCounter_1; ?>" class="memb_id" value="" size="10" /></td>
      <td align="center"><input type="text" name="amount_entry_<?php echo $RepeatSelectionCounter_1; ?>" value="" size="10" /></td>
      <td align="center"><select name="how_paid_<?php echo $RepeatSelectionCounter_1; ?>">
          <option selected="selected" value="PP" <?php if (!(strcmp("PP", ""))) {echo "SELECTED";} ?>>PP</option>
          <option value="BT" <?php if (!(strcmp("BT", ""))) {echo "SELECTED";} ?>>BT</option>
          <option value="Chq" <?php if (!(strcmp("Chq", ""))) {echo "SELECTED";} ?>>Chq</option>
          <option value="Cash" <?php if (!(strcmp("Cash", ""))) {echo "SELECTED";} ?>>Cash</option>
          <option value="Other" <?php if (!(strcmp("Other", ""))) {echo "SELECTED";} ?>>Other</option>
      </select></td>
      <td><input type="text" name="junior_cat" id="junior_cat_<?php echo $RepeatSelectionCounter_1; ?>" value="" size="12" />&nbsp;&nbsp;(Use for Junior tournaments only) </td>
      <!--<td align="center"><select name="junior_cat_<?php echo $RepeatSelectionCounter_1; ?>">
          <option selected="selected" value="na" <?php if (!(strcmp("na", ""))) {echo "SELECTED";} ?>>Not Required</option>
          <option value="U12" <?php if (!(strcmp("U12", ""))) {echo "SELECTED";} ?>>U12</option>
          <option value="U15" <?php if (!(strcmp("U15", ""))) {echo "SELECTED";} ?>>U15</option>
          <option value="U18" <?php if (!(strcmp("U18", ""))) {echo "SELECTED";} ?>>U18</option>
          <option value="U21" <?php if (!(strcmp("U21", ""))) {echo "SELECTED";} ?>>U21</option>
      </select></td>-->
      <td>
        <input type="hidden" name="ID_<?php echo $RepeatSelectionCounter_1; ?>" value="" /> 
        <input type="hidden" name="tourn_type_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_tourn1['tourn_type']; ?>" />           
        <input type="hidden" name="tournament_number_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_tourn1['tourn_id']; ?>" />
        <input type="hidden" name="entry_cal_year_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo date("Y"); ?>" />
        <input type="hidden" name="entered_by_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_getusername['name']; ?>" />
        <input type="hidden" name="entry_fin_year_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php if($date['m']<=6) {echo date("Y")-1; } elseif($date['m']>=7) {echo date("Y"); }?> " />
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
      
    <?php
	} // RepeatSelectionCounter_1 Begin Alternate Content
	else{
	?>
    <?php } // RepeatSelectionCounter_1 End Alternate Content
		if(!$RepeatSelectionCounterBasedLooping_1 && $RepeatSelectionCounter_1_IterationsRemaining != 0){
			if(!$row_None && $RepeatSelectionCounter_1_Iterations == -1){$RepeatSelectionCounter_1_IterationsRemaining = 0;}
			$row_None = mysql_fetch_assoc($None);
		}
		$RepeatSelectionCounter_1++;
	} // RepeatSelectionCounter_1 End Loop
	?>
    
    <tr>
      <td nowrap="nowrap" align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center"><input type="submit" value="Insert Players" /></td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
	  
</form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>

<script type="text/javascript">
    $(function()
    {
      $('.memb_id').focusout(function(e)
      {
        e.preventDefault();
        var id = $(this).attr('id');
        var input = $(this).val();
        //console.log(id);
        $.ajax({
            type: "get",
            url: "../get_member_name.php",
            data: {memberid: input},
            async: true,
            success: function(data){
              //console.log(data);
              new_data = data.split(", " );
              if(new_data[0] === 'false')
              {
                alert('Entered player is not a member and can’t take part in the event. Ensure membership payment before the event');
              }
              else
              {
                $('#junior_cat_' + id).val(new_data[1]);
              }
              /*
              if(data === 'true')
              {
                alert('Entered player is not a member and can’t take part in the event. Ensure membership payment before the event');
              }
              */
            }
        })
      });

      $('#quick_search').keyup(function(e){
          //console.log("Got call");
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
