<?php require_once('../../Connections/connvbsa.php'); ?>
<?php require_once("../../webassist/ckeditor/ckeditor.php"); ?>
<?php include('../../security_header.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO calendar (event, venue, state, aust_rank, ranking_type, startdate, finishdate, entry_close, about, tourn, visible, footer1, footer2, footer3, footer4) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['event'], "text"),
                       GetSQLValueString($_POST['venue'], "text"),
                       GetSQLValueString($_POST['state'], "text"),
                       GetSQLValueString($_POST['aust_rank'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['startdate'], "date"),
                       GetSQLValueString($_POST['finishdate'], "date"),
                       GetSQLValueString($_POST['entry_close'], "date"),
                       GetSQLValueString($_POST['about'], "text"),
                       GetSQLValueString($_POST['tourn'], "text"),
                       GetSQLValueString($_POST['visible'], "text"),
                       GetSQLValueString(isset($_POST['footer1']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer2']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer3']) ? "true" : "", "defined","'Y'","'N'"),
                       GetSQLValueString(isset($_POST['footer4']) ? "true" : "", "defined","'Y'","'N'"));
//echo($insertSQL . "<br>");
  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  if($_POST['tourn_copy'] = 'Yes"')
  {
    $insert_tourn_SQL = sprintf("Insert INTO tournaments (tourn_name, site_visible, tourn_year, tourn_type, tourn_class, ranking_type, how_seed, status) 
                          VALUES (%s, %s, YEAR(CURDATE()), %s, %s, %s, %s, %s)",
                         GetSQLValueString($_POST['event'], "text"),
                         GetSQLValueString($_POST['visible'], "text"),
                         GetSQLValueString($_POST['tourn_type'], "text"),
                         GetSQLValueString($_POST['tourn_class'], "text"),
                         GetSQLValueString($_POST['ranking_type'], "text"),
                         GetSQLValueString($_POST['how_seed'], "text"),
                         GetSQLValueString($_POST['status'], "text"));
    //echo($insert_tourn_SQL . "<br>");
    mysql_select_db($database_connvbsa, $connvbsa);
    $Result_copy = mysql_query($insert_tourn_SQL, $connvbsa) or die(mysql_error());
  }

  $insertGoTo = "../A_calendar_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['eventID'])) {
  $eventID = $_GET['eventID'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_newevent = "SELECT * FROM calendar WHERE event_id = '$eventID'";
$newevent = mysql_query($query_newevent, $connvbsa) or die(mysql_error());
$row_newevent = mysql_fetch_assoc($newevent);
$totalRows_newevent = mysql_num_rows($newevent);
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

</head>

<body>

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center"><span class="red_bold">Insert a new event into the calendar from previous</span></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><span class="red_bold">If a &quot;Start Date&quot; is not set, or, &quot;Visible is set to &quot;No&quot; then event will not appear in the calendar, it will appear in the &quot;Archives&quot;</span></td>
  </tr>
  <tr>
    <td colspan="2" align="center">The following information will be inserted, after the item is inserted you may edit as required</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <!--<tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>From previous Event ID: <?php echo $eventID; ?></td>
    </tr>-->
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>Previous entry</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Event:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="event" value="<?php echo $row_newevent['event']; ?>" size="60" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Venue:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="venue" value="<?php echo $row_newevent['venue']; ?>" size="32" /></td>
    </tr>


    <tr valign="baseline">
        <td nowrap="nowrap" align="right">State:</td>
        <td>&nbsp;</td>
        <td><select name="state">
          <option value="<?php echo $row_newevent['state']; ?>"><?php echo $row_newevent['state']; ?></option>
          <option value="" <?php if (!(strcmp("No Entry", ""))) {echo "SELECTED";} ?>>No Entry</option>
          <option value="ACT" <?php if (!(strcmp("ACT", ""))) {echo "SELECTED";} ?>>ACT</option>
          <option value="NSW" <?php if (!(strcmp("NSW", ""))) {echo "SELECTED";} ?>>NSW</option>
          <option value="NT" <?php if (!(strcmp("NT", ""))) {echo "SELECTED";} ?>>NT</option>
          <option value="Qld" <?php if (!(strcmp("Qld", ""))) {echo "SELECTED";} ?>>Qld</option>
          <option value="SA" <?php if (!(strcmp("SA", ""))) {echo "SELECTED";} ?>>SA</option>
          <option value="Tas" <?php if (!(strcmp("Tas", ""))) {echo "SELECTED";} ?>>Tas</option>
          <option value="Vic" <?php if (!(strcmp("Vic", ""))) {echo "SELECTED";} ?>>Vic</option>
          <option value="WA" <?php if (!(strcmp("WA", ""))) {echo "SELECTED";} ?>>WA</option>
        </select></td>
      <tr>  
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Austalian Ranking event</td>
        <td>&nbsp;</td>
        <td><select name="aust_rank">
          <option value="<?php echo $row_newevent['aust_rank']; ?>"><?php echo $row_newevent['aust_rank']; ?></option>
          <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          <option selected="selected" value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
        </select></td>
      </tr>

      <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ranking Type:</td>
      <td>&nbsp;</td>
        <td><select name="ranking_type">
          <option value="<?php echo $row_newevent['ranking_type']; ?>"><?php echo $row_newevent['ranking_type']; ?></option>
          <option value="None" <?php if (!(strcmp("None", ""))) {echo "SELECTED";} ?>>None</option>
          <option value="National" <?php if (!(strcmp("National", ""))) {echo "SELECTED";} ?>>National</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Womens" <?php if (!(strcmp("Womens", ""))) {echo "SELECTED";} ?>>Womens</option>
          <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
      </tr>
    </tr>
    <tr valign="baseline">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
       <td align="left" class="red_text">"National" for tournaments that DO attract National Ranking points. <br />
            "Victorian" for tournaments that DO attract Victorian Ranking points. <br />
            "Womens" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
            "Junior" for tournaments that attract Victorian JUNIOR Ranking points. <br />
       </td>
    </tr>

<!--

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">State:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="venue" value="<?php echo $row_newevent['state']; ?>" size="10"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Austalian Ranking event:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="venue" value="<?php echo $row_newevent['aust_rank']; ?>" size="10"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ranking Type:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="venue" value="<?php echo $row_newevent['ranking_type']; ?>" size="10"></td>
    </tr>

  -->

  
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Start date:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="startdate" value="" size="24" />
        <input type="button" value="Select Date" onclick="displayDatePicker('startdate', false, 'ymd', '-');" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Finish date:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="finishdate" value="" size="24" />
      <input type="button" value="Select Date" onclick="displayDatePicker('finishdate', false, 'ymd', '-');" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Entries Close:</td>
      <td>&nbsp;</td>
      <td><input type="text" name="entry_close" value="" size="24" />
      <input type="button" value="Select Date" onclick="displayDatePicker('entry_close', false, 'ymd', '-');" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">About</td>
      <td>&nbsp;</td>
      <td><?php
      // The initial value to be displayed in the editor.
      $CKEditor_initialValue = "". $row_newevent['about']  ."";
      $CKEditor = new CKEditor();
      $CKEditor->basePath = "../../webassist/ckeditor/";
      $CKEditor_config = array();
      $CKEditor_config["wa_preset_name"] = "Normal";
      $CKEditor_config["wa_preset_file"] = "Normal.xml";
      $CKEditor_config["width"] = "100%";
      $CKEditor_config["height"] = "200px";
      $CKEditor_config["dialog_startupFocusTab"] = false;
      $CKEditor_config["fullPage"] = false;
      $CKEditor_config["tabSpaces"] = 4;
      $CKEditor_config["toolbar"] = array(
      array( 'Bold','Italic','Underline'),
      array( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
      array( 'NumberedList','BulletedList','-','Outdent','Indent'),
      array( 'FontName','FontSize'),
      array( 'TextColor'));
      $CKEditor_config["contentsLangDirection"] = "ltr";
      $CKEditor_config["entities"] = false;
      $CKEditor_config["pasteFromWordRemoveFontStyles"] = false;
      $CKEditor_config["pasteFromWordRemoveStyles"] = false;
      $CKEditor->editor("about", $CKEditor_initialValue, $CKEditor_config);
    ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Is this event a tournament?</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_newevent['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_newevent['tourn'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Copy this calendar entry to the list of tournaments for this year?</td>
      <td>&nbsp;</td>
      <td><select name="tourn_copy">
        <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
      </select></td>
    </tr>
    <tr valign="baseline">
        <td nowrap="nowrap" align="right">Tournament Type: </td>
        <td>&nbsp;</td>
        <td><select name="tourn_type">
          <option value="Snooker" selected="selected" <?php if (!(strcmp("Snooker", ""))) {echo "SELECTED";} ?>>Snooker</option>
          <option value="Billiards" <?php if (!(strcmp("Billiards", ""))) {echo "SELECTED";} ?>>Billiards</option>
        </select></td>
    </tr>
    <tr valign="baseline">
        <td nowrap="nowrap" align="right">Please Select a Class for this tournament:</td>
        <td>&nbsp;</td>
        <td><select name="tourn_class">
          <option value="Aust Rank" <?php if (!(strcmp("Aust Rank", ""))) {echo "SELECTED";} ?>>Aust Rank</option>
          <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
          <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">How is this tournament seeded?</td>
      <td>&nbsp;</td>
      <td><select name="how_seed">
      <option value="NA" <?php if (!(strcmp("NA", ""))) {echo "SELECTED";} ?>>Not Applicable</option>
        <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", ""))) {echo "SELECTED";} ?>>Aust Rankings</option>
        <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", ""))) {echo "SELECTED";} ?>>Victorian Rankings</option>
        <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", ""))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
        <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic WomensRankings", ""))) {echo "SELECTED";} ?>>Victorian Womens Rankings</option>
        <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", ""))) {echo "SELECTED";} ?>>Junior Rankings</option>
      </select></td>
    </tr>
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Does this tournament attract ranking points in Victoria?</td>
       <td>&nbsp;</td>
       <td><select name="ranking_type">
        <option value="No Entry" <?php if (!(strcmp("No Entry", ""))) {echo "SELECTED";} ?>>No Entry</option>
        <option value="Vic Rank" <?php if (!(strcmp("Vic Rank", ""))) {echo "SELECTED";} ?>>Vic Rank</option>
        <option value="Womens Rank" <?php if (!(strcmp("Womens Rank", ""))) {echo "SELECTED";} ?>>Womens Rank</option>
        <option value="Junior Rank" <?php if (!(strcmp("Junior Rank", ""))) {echo "SELECTED";} ?>>Junior Rank</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Visible:</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_newevent['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_newevent['visible'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>
<!--    
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Footer1:</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Y", htmlentities($row_newevent['footer1'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("N", htmlentities($row_newevent['footer1'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Footer2:</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Y", htmlentities($row_newevent['footer2'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("N", htmlentities($row_newevent['footer2'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Footer3:</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Y", htmlentities($row_newevent['footer3'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("N", htmlentities($row_newevent['footer3'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Footer4:</td>
      <td>&nbsp;</td>
      <td><select name="tourn">
          <option value="Yes" <?php if (!(strcmp("Y", htmlentities($row_newevent['footer4'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          <option value="No" <?php if (!(strcmp("N", htmlentities($row_newevent['footer4'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
        </select></td>
    </tr>
-->

    <table align="center" class="page">
      <tr>
        <td colspan="3" class="red_text">Check any of these to add to the Footer </td>
      </tr>
      <tr>
        <td>VBSA</td>
        <td><input type="checkbox" name="footer1" value="" /></td>
        <td>To enter this event, pay your membership or make a payment to   the VBSA please go to the payments page. Enquiries - <a href="mailto:treasurer@vbsa.org.au">VBSA treasurer </a></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="footer2" value="" /></td>
        <td>To check the VBSA have received your entry please go to <a href="http://www.vbsa.org.au/Tournaments/tournindex.php">&quot;VBSA Tournament entries&quot;</a> . Allow a couple of days for your entry to be processed and then check that your name is listed for the tournament.</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="57">Non VBSA</td>
        <td width="20"><input type="checkbox" name="footer3" value="" /></td>
        <td width="931"><p>Please Note: The VBSA do not accept entries for this event, please refer the entry form fo details on how to enter</p></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="checkbox" name="footer4" value="" /></td>
        <td>Please go to the <a href="http://absc.com.au/results.aspx">ABSC Site</a> for results</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" value="Insert Event" /></td>
      </tr>
    </table>
  <input type="hidden" name="event_id" value="" />
  <input type="hidden" name="MM_insert" value="form2" />
  <input type="hidden" name="status" value="Open" />
  <input type="hidden" name="state" value="<?php echo $row_newevent['state']; ?>" />
  <input type="hidden" name="aust_rank" value="<?php echo $row_newevent['aust_rank']; ?>" />
  <input type="hidden" name="tourn" value="<?php echo $row_newevent['tourn']; ?>" />
  <input type="hidden" name="visible" value="Yes" />
  <input type="hidden" name="footer1" value="<?php echo $row_newevent['footer1']; ?>" />
  <input type="hidden" name="footer2" value="<?php echo $row_newevent['footer2']; ?>" />
  <input type="hidden" name="footer3" value="<?php echo $row_newevent['footer3']; ?>" />
  <input type="hidden" name="footer4" value="<?php echo $row_newevent['footer4']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
