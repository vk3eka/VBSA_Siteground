<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/database_management/wa_appbuilder_php.php"); ?>
<?php
error_reporting(0);
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
if (isset($_GET['tourn_year'])) {
  $tourn_year = $_GET['tourn_year'];
}

// CSV Upload Handling - MOVED HERE AFTER tourn_id is set
if (isset($_POST['upload_csv']) && isset($_FILES['csv_file']['name']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['csv_file']['tmp_name'];
    $csvFile = fopen($fileTmpPath, 'r');
    if ($csvFile !== false) {
        mysql_select_db($database_connvbsa, $connvbsa);
        
        // Skip header row if exists
        $header = fgetcsv($csvFile);
        $updatedRows = [];
        $debugInfo = [];
        $successCount = 0;
        
        $debugInfo[] = "Tournament ID: $tourn_id";
        $debugInfo[] = "Database selected: $database_connvbsa";
        
        while (($row = fgetcsv($csvFile)) !== false) {
            // Skip empty rows
            if (empty($row) || count($row) <= 2) continue;
            $memberID = trim($row[0]);
            $rankingPoints = trim($row[2]);

            $debugInfo[] = "Processing Member ID: '$memberID' with points '$rankingPoints'";
            
            //if ($memberID !== '') {
            if ($memberID !== '' && is_numeric($memberID) && is_numeric($rankingPoints)) {
                $memberID = (int)$memberID;
                $rankingPoints = (int)$rankingPoints;
                
                // Find the tournament entry for this member in this tournament
                $findQuery = sprintf(
                    "SELECT ID FROM tourn_entry WHERE tournament_number = '%s' AND tourn_memb_id = %d",
                    mysql_real_escape_string($tourn_id),
                    $memberID
                );
                $findResult = mysql_query($findQuery, $connvbsa);
                $debugInfo[] = "Find query: $findQuery";
                $debugInfo[] = "Find result rows: " . ($findResult ? mysql_num_rows($findResult) : 'ERROR');
                
                if ($findResult && mysql_num_rows($findResult) > 0) {
                    $tournRow = mysql_fetch_assoc($findResult);
                    $tournEntryID = $tournRow['ID'];
                    
                    // Update the ranking points in tourn_entry table
                    $updateQuery = sprintf(
                        "UPDATE tourn_entry SET rank_pts = %d WHERE ID = %d",
                        $rankingPoints,
                        $tournEntryID
                    );
                    $debugInfo[] = "Update query: $updateQuery";
                    $updateResult = mysql_query($updateQuery, $connvbsa);
                    if ($updateResult) {
                        $updatedRows[] = $memberID;
                        $successCount++;
                        $debugInfo[] = "SUCCESS: Updated Member ID $memberID (Tournament Entry ID: $tournEntryID) with $rankingPoints points";
                    } else {
                        $debugInfo[] = "ERROR updating Member ID $memberID: " . mysql_error();
                    }
                } else {
                    $debugInfo[] = "Member ID $memberID not found in tournament $tourn_id entries";
                }
            } else {
                $debugInfo[] = "Invalid data - Member ID: '$memberID', Points: '$rankingPoints'";
            }
        }
        fclose($csvFile);
        
        // Store results in session
        $_SESSION['updated_rows'] = $updatedRows;
        $_SESSION['csv_success_count'] = $successCount;
        $_SESSION['csv_debug'] = $debugInfo;
        
        // Stay on same page - build the redirect URL properly
        $redirect_url = $_SERVER['PHP_SELF'] . "?tourn_id=" . urlencode($tourn_id);
        if (!empty($tourn_year)) {
            $redirect_url .= "&tourn_year=" . urlencode($tourn_year);
        }
        $debugInfo[] = "Redirecting to: $redirect_url";
        header("Location: $redirect_url");
        exit;
    } else {
        $_SESSION['csv_error'] = 'Failed to open CSV file';
        $redirect_url = $_SERVER['PHP_SELF'] . "?tourn_id=" . urlencode($tourn_id);
        if (!empty($tourn_year)) {
            $redirect_url .= "&tourn_year=" . urlencode($tourn_year);
        }
        header("Location: $redirect_url");
        exit;
    }
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_players1 = "SELECT members.MemberID, members.LastName, tourn_entry.rank_pts, ranknum, total_tourn_rp, members.FirstName, dob_year, tourn_entry.ID, tourn_entry.tourn_memb_id, tourn_entry.tournament_number, tourn_entry.amount_entry, tourn_entry.entered_by, tourn_entry.how_paid, tourn_entry.seed, tourn_entry.junior_cat, tourn_entry.tourn_date_ent, tourn_entry.ranked, tourn_entry.wcard, members.Email, members.MobilePhone FROM tourn_entry, members Left Join rank_S_open_tourn on members.MemberID = rank_S_open_tourn.memb_id WHERE tournament_number ='$tourn_id' AND members.MemberID=tourn_memb_id ORDER BY tourn_entry.junior_cat, members.FirstName";
//echo($query_players1 . "<br>");
$players1 = mysql_query($query_players1, $connvbsa) or die(mysql_error());
$row_players1 = mysql_fetch_assoc($players1);
$totalRows_players1 = mysql_num_rows($players1);

//mysql_select_db($database_connvbsa, $connvbsa);
$query_TournName = "SELECT tourn_id, tourn_name, date_format( tourn_year, '%Y') AS Tyear FROM tournaments WHERE tourn_id = '$tourn_id'";
//echo($query_TournName . "<br>");
$TournName = mysql_query($query_TournName, $connvbsa) or die(mysql_error());
$row_TournName = mysql_fetch_assoc($TournName);
$totalRows_TournName = mysql_num_rows($TournName);?>
<?php
// WA DataAssist Multiple Updates
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_SERVER["HTTP_REFERER"]) && strpos(urldecode($_SERVER["HTTP_REFERER"]), urldecode($_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"])) > 0) && isset($_POST)) // Trigger
{
  if (!session_id()) session_start();
  $WA_loopedIDField = array("WADA_RepeatID_ID");
  $WA_connection = $connvbsa;
  $WA_table = "tourn_entry";
  //$WA_redirectURL = "$_SESSION[tourn_page]?tourn_year=$tourn_year";
  $WA_redirectURL = "aa_tourn_index.php?tourn_year=$tourn_year";
  if (function_exists("rel2abs")) $WA_redirectURL = $WA_redirectURL?rel2abs($WA_redirectURL,dirname(__FILE__)):"";
  //echo($WA_redirectURL . "<br>");
  $WA_keepQueryString = false;
  $WA_indexField = "ID";
  $WA_fieldNamesStr = "tourn_memb_id|amount_entry|how_paid|seed|wcard|ranked|rank_pts|junior_cat";
  $WA_columnTypesStr = "none,none,NULL|none,none,NULL|',none,''|none,none,NULL|none,none,NULL|none,none,NULL|none,none,NULL|',none,''";
  $WA_fieldNames = explode("|", $WA_fieldNamesStr);
  $WA_columns = explode("|", $WA_columnTypesStr);
  $WA_connectionDB = $database_connvbsa;
  $WA_multipleUpdateCounter = 0;
  mysql_select_db($WA_connectionDB, $WA_connection);
  while (WA_AB_checkLoopedFieldsNotBlank($WA_loopedIDField, $WA_multipleUpdateCounter)) {
    $WA_fieldValuesStr = "".WA_AB_getLoopedFieldValue("tourn_memb_id", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("amount_entry", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("how_paid", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("seed", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("wcard", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("ranked", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("rank_pts", $WA_multipleUpdateCounter)  ."" . $WA_AB_Split . "".WA_AB_getLoopedFieldValue("junior_cat", $WA_multipleUpdateCounter)  ."";
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
    $WA_redirectURL = "../aa_tourn_index.php?tourn_year=$tourn_year";
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
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


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

<!-- Display CSV Upload Results -->
<?php if (isset($_SESSION['csv_success_count']) || isset($_SESSION['csv_debug']) || isset($_SESSION['csv_error'])): ?>
<div style="margin: 10px auto; width: 90%; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9;">
    <?php if (isset($_SESSION['csv_success_count'])): ?>
        <div style="color: green; font-weight: bold; margin-bottom: 10px;">
            ✓ CSV Upload Complete: <?php echo $_SESSION['csv_success_count']; ?> player(s) updated successfully.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['csv_error'])): ?>
        <div style="color: red; font-weight: bold; margin-bottom: 10px;">
            Error: <?php echo $_SESSION['csv_error']; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['csv_debug']) && !empty($_SESSION['csv_debug'])): ?>
        <details style="margin-top: 10px;">
            <summary style="cursor: pointer; font-weight: bold;">Show Debug Information</summary>
            <div style="background: #fff; padding: 10px; margin-top: 5px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto;">
                <?php foreach ($_SESSION['csv_debug'] as $debug): ?>
                    <div style="font-family: monospace; font-size: 11px; margin: 2px 0;"><?php echo htmlentities($debug); ?></div>
                <?php endforeach; ?>
            </div>
        </details>
    <?php endif; ?>
</div>
<?php 
    // Clear the session variables after displaying
    unset($_SESSION['csv_success_count']);
    unset($_SESSION['csv_debug']);
    unset($_SESSION['csv_error']);
endif; 
?>

<table width="893" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><span class="red_bold">Edit  all players</span></td>
    <td align="left">Tournament: <span class="red_bold"><?php echo $row_TournName['Tyear']; ?> <?php echo $row_TournName['tourn_name']; ?></span>
    <td align="left">No. of Players: <?= $totalRows_players1 ?></td>
    <td width="170" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<form method="post" name="form1" id="form1"  action='player_edit_all.php' >
<!--<form method="post" name="form1" id="form1"  onsubmit="return doit()" >-->
  <table align="center" cellpadding="2" cellspacing="2">
    <tr valign="baseline">
      <td align="right" valign="baseline" nowrap="nowrap">Member ID:</td>
      <td align="left">First Name</td>
      <td align="left">Last Name</td>
      <td align="center" valign="baseline">Rank Points</td>
      <td align="center" valign="baseline">$ Paid</td>
      <td align="center" valign="baseline">How paid</td>
      <td align="center" valign="baseline">Seed</td>
      <td align="center" valign="baseline">Wildcard</td>
      <td align="center" valign="baseline">Vic Rank</td>
      <td align="center" valign="baseline">Junior Cat:</td>
      <td valign="baseline">Entered on</td>
    </tr>
    <?php do { ?>
      <tr valign="baseline" id="player-row-<?php echo $row_players1['MemberID']; ?>">
        <?php
	// RepeatSelectionCounter_1 Begin Loop
	$RepeatSelectionCounter_1_IterationsRemaining = $RepeatSelectionCounter_1_Iterations;
	while($RepeatSelectionCounter_1_IterationsRemaining--){
		if($RepeatSelectionCounterBasedLooping_1 || $row_players1){
?>
          <td align="center" nowrap="nowrap"><input type="hidden" name="WADA_RepeatID_ID_<?php echo $RepeatSelectionCounter_1; ?>" id="WADA_RepeatID_ID_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_players1["ID"]; ?>" />
            <input type="text" name="tourn_memb_id_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo htmlentities($row_players1['tourn_memb_id'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
        <td align="left"><?php echo $row_players1['FirstName']; ?></td>
        <td align="left"><?php echo $row_players1['LastName']; ?></td>

<!-- New Rank Points Column position -->
        <td align="center"><input type="text" name="rank_pts_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo htmlentities($row_players1['rank_pts'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>

          <td align="center"><input type="text" name="amount_entry_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo htmlentities($row_players1['amount_entry'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
          <td align="center"><select name="how_paid_<?php echo $RepeatSelectionCounter_1; ?>">
            <option value="PP" selected="selected"<?php if (!(strcmp("PP", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>PP</option>
            <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
            <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
            <option value="Chq" <?php if (!(strcmp("Chq", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Chq</option>
            <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
        </select></td>
        <td align="center"><input type="text" name="seed_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_players1['seed']; ?>" size="10" /></td>
        <td align="center"><input type="text" name="wcard_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_players1['wcard']; ?>" size="10" /></td>
        <td align="center"><input type="text" name="ranked_<?php echo $RepeatSelectionCounter_1; ?>" value="<?php echo $row_players1['ranknum']; ?>" size="10" /></td>
  
          <td align="center"><select name="junior_cat_<?php echo $RepeatSelectionCounter_1; ?>">
<?php
            $dob_year = $row_players1['dob_year'];
            if(($dob_year >= (date("Y")-21)) AND ($dob_year <= (date("Y")-18)))
            {
            $junior = 'U21';
            }
            else if(($dob_year >= (date("Y")-18)) AND ($dob_year <= (date("Y")-16)))
            {
            $junior = 'U18';
            }
            else if(($dob_year >= (date("Y")-15)) AND ($dob_year <= (date("Y")-13)))
            {
            $junior = 'U15';
            }
            else if(($dob_year >= (date("Y")-12)) AND ($dob_year <= (date("Y"))))
            {
            $junior = 'U12';
            }
            else
            {
            $junior = 'Not Required';
            }

            echo('<option value="' . $junior . '" selected >' . $junior . '</option>');
?>
            <option value="U12">U12</option>
            <option value="U15">U15</option>
            <option value="U18">U18</option>
            <option value="U21">U21</option>

            <!--<option value="na" selected="selected"<?php if (!(strcmp("na", htmlentities($row_players1['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Not Required</option>
            <option value="U12" <?php if (!(strcmp("U12", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U12</option>
            <option value="U15" <?php if (!(strcmp("U15", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U15</option>
            <option value="U18" <?php if (!(strcmp("U18", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U18</option>
            <option value="U21" <?php if (!(strcmp("U21", htmlentities($row_players1['junior_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>U21</option>-->
        </select></td>
        <td><?php echo $row_players1['tourn_date_ent']; ?></td>
          <?php
	} // RepeatSelectionCounter_1 Begin Alternate Content
	else{
?>
            <td>No records match your request.</td>
            <?php } // RepeatSelectionCounter_1 End Alternate Content
		if(!$RepeatSelectionCounterBasedLooping_1 && $RepeatSelectionCounter_1_IterationsRemaining != 0){
			if(!$row_players1 && $RepeatSelectionCounter_1_Iterations == -1){$RepeatSelectionCounter_1_IterationsRemaining = 0;}
			$row_players1 = mysql_fetch_assoc($players1);
		}
		$RepeatSelectionCounter_1++;
	} // RepeatSelectionCounter_1 End Loop
?>
      </tr>
      <?php } while ($row_players1 = mysql_fetch_assoc($players1)); ?>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="submit" value="Update All" /></td>
    </tr>
  </table>
  <!--<input type="hidden" name="MM_update" value="form1" />-->
  <input type="hidden" name="ID" value="<?php echo $row_players1['ID']; ?>" />

<?php if (!empty($_SESSION['updated_rows'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var updated = <?php echo json_encode($_SESSION['updated_rows']); ?>;
    updated.forEach(function(id) {
        var row = document.getElementById('player-row-' + id);
        if (row) {
            row.style.backgroundColor = '#ffff99';
        }
    });
});
</script>
<?php unset($_SESSION['updated_rows']); ?>
<?php endif; ?>
</form>
<!-- CSV Upload Form -->
<form method="post" enctype="multipart/form-data" style="margin-top:20px;">
<table align="center" cellpadding="2" cellspacing="2">
    <tr>
        <td><label for="csv_file">Upload CSV (FullName, RankingPoints):</label></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <input type="file" name="csv_file" accept=".csv">
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><input type="submit" name="upload_csv" value="Upload CSV">
        </td>
    </tr>
</table>
</form>

</body>
</html>
