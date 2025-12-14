<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once("../webassist/database_management/wa_appbuilder_php.php"); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form6")) {
  $updateSQL = sprintf("UPDATE Team_entries SET team_name=%s, team_grade=%s, Result_score=%s, HB=%s, Countback=%s, audited=%s WHERE team_id=%s",
                       GetSQLValueString($_POST['team_name'], "text"),
                       GetSQLValueString($_POST['team_grade'], "text"),
                       GetSQLValueString($_POST['Result_score'], "int"),
                       GetSQLValueString($_POST['HB'], "text"),
                       GetSQLValueString($_POST['Countback'], "int"),
                       GetSQLValueString($_POST['audited'], "int"),
                       GetSQLValueString($_POST['team_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "AA_scores_index_grades.php?season=$season";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if(isset($_POST["Result_pos"])) {
	for($i=0; $i < count($_POST["Result_pos"]); $i++) {
		$updateSQL = sprintf("UPDATE Team_entries SET Result_pos = %s, Result_score = %s, Countback = %s, HB = %s WHERE team_id = %d",
		GetSQLValueString($_POST["Result_pos"][$i], "int"),
		GetSQLValueString($_POST["Result_score"][$i], "int"),
		GetSQLValueString($_POST["Countback"][$i], "int"),
		GetSQLValueString($_POST["HB"][$i], "text"),
		GetSQLValueString($_POST["id"][$i], "int"));
		mysql_select_db($database_connvbsa, $connvbsa);
  		$Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());
		
$updateGoTo = "scores_index_season.php?season=$season";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));	
	}
}
?>
<?php

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$colname_rs = "-1";
if (isset($_GET['rds'])) {
  $colname_rs = $_GET['rds'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_rs = sprintf("SELECT team_id, team_name, team_grade, Result_pos, Result_score, HB, Countback, audited FROM Team_entries WHERE team_grade = %s AND  team_cal_year = YEAR( CURDATE( ) ) ORDER BY Result_pos", GetSQLValueString($colname_rs, "text"));
$rs = mysql_query($query_rs, $connvbsa) or die(mysql_error());
$row_rs = mysql_fetch_assoc($rs);
$totalRows_rs = mysql_num_rows($rs);?>
<?php
// WA DataAssist Multiple Updates
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SERVER["HTTP_REFERER"]) && strpos(urldecode($_SERVER["HTTP_REFERER"]), urldecode($_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"])) > 0) && isset($_POST)) // Trigger
{
  if (!session_id()) session_start();
  $WA_loopedIDField = array("WADA_RepeatID_team_id");
  $WA_connection = $connvbsa;
  $WA_table = "Team_entries";
  $WA_redirectURL = "";
  if (function_exists("rel2abs")) $WA_redirectURL = $WA_redirectURL?rel2abs($WA_redirectURL,dirname(__FILE__)):"";
  $WA_keepQueryString = false;
  $WA_indexField = "team_id";
  $WA_fieldNamesStr = "Result_pos|Result_score|HB|Countback|audited";
  $WA_columnTypesStr = "none,none,NULL|none,none,NULL|',none,''|none,none,NULL|',none,''";
  $WA_fieldNames = explode("|", $WA_fieldNamesStr);
  $WA_columns = explode("|", $WA_columnTypesStr);
  $WA_connectionDB = $database_connvbsa;
  $WA_multipleUpdateCounter = 0;
  mysql_select_db($WA_connectionDB, $WA_connection);
  while (WA_AB_checkLoopedFieldsNotBlank($WA_loopedIDField, $WA_multipleUpdateCounter)) {
    $WA_fieldValuesStr = "".WA_AB_getLoopedFieldValue("Result_pos", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("Result_score", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("HB", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("Countback", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".$row_rs['audited']  ."";
    $WA_fieldValues = explode($WA_AB_Split, $WA_fieldValuesStr);
    $WA_where_fieldValuesStr = WA_AB_getLoopedFieldValue($WA_loopedIDField[0], $WA_multipleUpdateCounter);
    $WA_where_columnTypesStr = "',none,''";
    $WA_where_comparisonStr = "=";
    $WA_where_fieldNames = explode("|", $WA_indexField);
    $WA_where_fieldValues = explode($WA_AB_Split, $WA_where_fieldValuesStr);
    $WA_where_columns = explode("|", $WA_where_columnTypesStr);
    $WA_where_comparisons = explode("|", $WA_where_comparisonStr);
    $updateParamsObj = WA_AB_generateInsertParams($WA_fieldNames, $WA_columns, $WA_fieldValues, -1);
    $WhereObj = WA_AB_generateWhereClause($WA_where_fieldNames, $WA_where_columns, $WA_where_fieldValues,  $WA_where_comparisons );
    $WA_Sql = "UPDATE `" . $WA_table . "` SET " . $updateParamsObj->WA_setValues . " WHERE " . $WhereObj->sqlWhereClause . "";
    $MM_editCmd = mysql_query($WA_Sql, $WA_connection) or die(mysql_error());
    $WA_multipleUpdateCounter++;
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
	$RepeatSelectionCounterBasedLooping_1 = false;
	$RepeatSelectionCounter_1_Iterations = "1";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
  
  <table border="0" align="center">
    <tr>
      <td align="center" class="page">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" class="red_bold">Update Rounds</td>
    </tr>
    <tr>
      <td align="center" class="page">Set Result position - Teams will appear in the order they are set in the match report section of the ladders page</td>
    </tr>
    <tr>
      <td align="center" class="page"><span class="pagetitle">Countback - Use countback at end of season only if required to order teams that finish equal (head to head matches to decide) .ALL BOXES MUST BE NUMBERED</span></td>
    </tr>
    <tr>
      <td align="center"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
  
  </form>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form6" id="form6">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Team_id:</td>
        <td>Team name</td>
        <td>Grade</td>
        <td align="center">Result Position</td>
        <td>Result_score:</td>
        <td>HB</td>
        <td>Countback</td>
        <td>Audited</td>
      </tr>
      <?php do { ?>
        <tr valign="baseline">
          <?php
	// RepeatSelectionCounter_1 Begin Loop
	$RepeatSelectionCounter_1_IterationsRemaining = $RepeatSelectionCounter_1_Iterations;
	while($RepeatSelectionCounter_1_IterationsRemaining--){
		if($RepeatSelectionCounterBasedLooping_1 || $row_rs){
?>
          <td nowrap="nowrap" align="right"><?php echo $row_rs['team_id']; ?></td>
          <td><?php echo $row_rs['team_name']; ?></td>
          <td><?php echo $row_rs['team_grade']; ?></td>
          <td align="center"><input type="text" name="Result_pos_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_rs['Result_pos']; ?>" size="4" /></td>
          <td><input type="hidden" name="WADA_RepeatID_team_id_<?php echo $RepeatSelectionCounter_1; ?>" id="WADA_RepeatID_team_id_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_rs["team_id"]; ?>" />
            <input type="text" name="Result_score_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_rs['Result_score']; ?>" size="6" /></td>
          <td><input type="text" name="HB_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_rs['HB']; ?>" size="50" /></td>
          <td><input type="text" name="Countback_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_rs['Countback']; ?>" size="6" /></td>
          <td><select name="audited_<?php echo $RepeatSelectionCounter_1; ?>">
              <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_rs['audited'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
              <option value="No" <?php if (!(strcmp("No", htmlentities($row_rs['audited'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
          </select></td>
          <?php
	} // RepeatSelectionCounter_1 Begin Alternate Content
	else{
?>
            <td>No records match your request.</td>
            <?php } // RepeatSelectionCounter_1 End Alternate Content
		if(!$RepeatSelectionCounterBasedLooping_1 && $RepeatSelectionCounter_1_IterationsRemaining != 0){
			if(!$row_rs && $RepeatSelectionCounter_1_Iterations == -1){$RepeatSelectionCounter_1_IterationsRemaining = 0;}
			$row_rs = mysql_fetch_assoc($rs);
		}
		$RepeatSelectionCounter_1++;
	} // RepeatSelectionCounter_1 End Loop
?>
        </tr>
        <?php } while ($row_rs = mysql_fetch_assoc($rs)); ?>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" value="Update rounds" /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form6" />
    <input type="hidden" name="team_id" value="<?php echo $row_rs['team_id']; ?>" />
</form>
  <p>&nbsp;</p>
</center>
</body>
</html>
<?php

?>
