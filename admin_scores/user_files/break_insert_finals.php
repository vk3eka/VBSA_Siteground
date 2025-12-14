<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/database_management/wa_appbuilder_php.php"); ?>
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

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

$colname_breaks_ins = "-1";
if (isset($_GET['team_id'])) {
  $colname_breaks_ins = $_GET['team_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_breaks_ins = sprintf("SELECT members.MemberID, members.LastName, members.FirstName, members.Club, members.Club2, scrs.team_grade, scrs.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.comptype  FROM members, scrs, Team_entries WHERE members.MemberID=scrs.MemberID AND scrs.team_id=Team_entries.team_id AND FirstName <> 'bye' AND scrs.team_id=%s AND current_year_scrs = YEAR( CURDATE( ) ) ORDER BY FirstName, LastName", GetSQLValueString($colname_breaks_ins, "text"));
$breaks_ins = mysql_query($query_breaks_ins, $connvbsa) or die(mysql_error());
$row_breaks_ins = mysql_fetch_assoc($breaks_ins);
$totalRows_breaks_ins = mysql_num_rows($breaks_ins);?>
<?php
// WA DataAssist Multiple Inserts
if ((((isset($_POST["Submit"]))?$_POST["Submit"]:"") != "")) // Trigger
{
  if (!session_id()) session_start();
  $WA_loopedFields = array("member_ID_brks", "brk", "finals_brk", "recvd", "brk_type", "season");
  $WA_connection = $connvbsa;
  $WA_table = "breaks";
  $WA_redirectURL = "../scores_index_finals.php?&grade=".$grade."&comptype=".$comptype."&season=".$season;
  if (function_exists("rel2abs")) $WA_redirectURL = $WA_redirectURL?rel2abs($WA_redirectURL,dirname(__FILE__)):"";
  $WA_keepQueryString = false;
  $WA_fieldNamesStr = "member_ID_brks|brk|grade|brk_team_id|finals_brk|recvd|brk_type|season";
  $WA_columnTypesStr = "none,none,NULL|none,none,NULL|',none,''|none,none,NULL|',none,''|',none,NULL|',none,''|',none,''";
  $WA_insertIfNotBlank = "member_ID_brks";
  $WA_fieldNames = explode("|", $WA_fieldNamesStr);
  $WA_columns = explode("|", $WA_columnTypesStr);
  $WA_connectionDB = $database_connvbsa;
  $WA_multipleInsertCounter = 0;
  mysql_select_db($WA_connectionDB, $WA_connection);
  while (WA_AB_checkMultiInsertLoopedFieldsExist($WA_loopedFields, $WA_multipleInsertCounter)) {
    if ($WA_insertIfNotBlank == "" || WA_AB_checkLoopedFieldsNotBlank(array($WA_insertIfNotBlank), $WA_multipleInsertCounter)) {
      $WA_fieldValuesStr = "".WA_AB_getLoopedFieldValue("member_ID_brks", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("brk", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".$row_breaks_ins['team_grade']  ."" . $WA_AB_Split . "".$row_breaks_ins['team_id']  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("finals_brk", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("recvd", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("brk_type", $WA_multipleInsertCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("season", $WA_multipleInsertCounter)  ."";
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
	$RepeatSelectionCounter_1_Iterations = "12";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
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
<table align="center">
  <tr>
    <td align="center" nowrap="nowrap" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap" class="greenbg"><span class="red_bold">Insert finals <?php echo $row_breaks_ins['comptype']; ?> Breaks for:<?php echo $row_breaks_ins['team_name']; ?> (Team ID: <?php echo $row_breaks_ins['team_id']; ?> ) in <?php echo $row_breaks_ins['team_grade']; ?> Season : <?php echo $season; ?></span></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td align="center">Up to 12 breaks may be inserted - Insert Member ID and corresponding break amount. Empty rows will not be inserted</td>
  </tr>
  <tr>
    <td align="center">After inserting breaks you will be returned to the ladder you were working on</td>
  </tr>
</table>

<table width="600" align="center" >
    <tr>
      <td colspan="2" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td>
  <form id="form" name="form6" method="post" action=""><table border="0">
    
    <tr>
      <td>Member ID</td>
      <td>break</td>
      </tr>
    
    <?php
	// RepeatSelectionCounter_1 Begin Loop
	$RepeatSelectionCounter_1_IterationsRemaining = $RepeatSelectionCounter_1_Iterations;
	while($RepeatSelectionCounter_1_IterationsRemaining--){
		if($RepeatSelectionCounterBasedLooping_1 || $row_None){
?>
      <tr>
        <td><input type="hidden" name="member_ID_brks_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" id="member_ID_brks_mihidden_<?php echo $RepeatSelectionCounter_1; ?>" value="1" />
          <input name="member_ID_brks_<?php echo $RepeatSelectionCounter_1; ?>" type="text" id="member_ID_brks_<?php echo $RepeatSelectionCounter_1; ?>" size="10" /></td>
        <td><input name="brk_<?php echo $RepeatSelectionCounter_1; ?>" type="text" id="brk_<?php echo $RepeatSelectionCounter_1; ?>" size="10" />
          <input name="grade_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="<?php echo $colname_breaks_ins ?>" />
          <input name="brk_team_id_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="<?php echo $colname_breaks_ins; ?>" />
          <input name="finals_brk_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="Yes" />
          <input name="recvd_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="<?php echo date('Y-m-d')?>" />
          <input name="brk_type_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="<?php echo $comptype ?>" />
          <input name="season_<?php echo $RepeatSelectionCounter_1; ?>" type="hidden" value="<?php echo $season ?>" />
          </td>
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
      <td colspan="2" align="center"><input type="submit" name="Submit" id="Submit" value="Insert Breaks" /></td>
      </tr>
    <tr>
      <td colspan="2" align="center">Date:<?php echo date("d-m-Y")?> (auto inserted)</td>
    </tr>
    </table>
  </form>
  </td>
      <td><table border="1" cellpadding="5" cellspacing="5">
        <tr>
          <td colspan="2" align="left">Players in this team</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_breaks_ins['MemberID']; ?></td>
            <td><?php echo $row_breaks_ins['FirstName']; ?> <?php echo $row_breaks_ins['LastName']; ?></td>
          </tr>
          <?php } while ($row_breaks_ins = mysql_fetch_assoc($breaks_ins)); ?>
        </table></td>
    </tr>
</table>

</body>
</html>
<?php

?>
