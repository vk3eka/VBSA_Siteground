<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster";
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


function GetMemberID($fullname)
{
    global $connvbsa;
    global $database_connvbsa;
    //$query_member_id = 'Select MemberID FROM vbsa3364_vbsa2.members where CONCAT(FirstName, " ", LastName) AS fullname';
    //$query_member_id = 'Select MemberID FROM vbsa3364_vbsa2.members where (CONCAT(FirstName, " ", LastName)) = "' . $fullname . '"';
    //$result_member_id = mysql_query($query_member_id, $connvbsa) or die(mysql_error());
    //$build_member_id = $result_member_id->fetch_assoc();
    //$member_id = $build_member_id['MemberID'];
    if($fullname == 'Bye')
    {
        $member_id = 1;
    }
    else
    {
        $query_member_id = 'Select MemberID FROM vbsa3364_vbsa2.members where (CONCAT(FirstName, " ", LastName)) = "' . $fullname . '"';
        $result_member_id = mysql_query($query_member_id, $connvbsa) or die(mysql_error());
        $build_member_id = $result_member_id->fetch_assoc();
        $member_id = $build_member_id['MemberID'];
    }
    return $member_id;
}


$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$tourn_id = "-1";
if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}
if (isset($_POST['tourn_id'])) {
  $tourn_id = $_POST['tourn_id'];
}

$current_year = date('Y');

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

<?php

//echo($current_year . "<br>");
//echo($season . "<br>");

mysql_select_db($database_connvbsa, $connvbsa);

/** Include PHPSpreadsheet */
require_once('../vbsa_online_scores/vendor/autoload.php');

function ImportExcel($filename, $current_year, $season, $tourn_id) 
{
    global $connvbsa;
    // check if any data for this season exists
    $sql = "Select * From tournament_players";
    $result_tourn_players = mysql_query($sql, $connvbsa) or die(mysql_error());
    $row_count = $result_tourn_players->num_rows;
    $data_exists = false;
    if($row_count > 0)
    {
        $data_exists = true;
    } 
    if($data_exists)
    {
        //$sql_delete = "Delete from tournament_players";
        $sql_delete = "truncate tournament_players";
        $update_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
        if(! $update_delete )
        {
          die("Could not delete season data: " . mysqli_error($dbcnx_client));
        }
    }
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    $inputFileType = "Xlsx";
    $inputFileName = $filename; 
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getSheetByName('Data');
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestDataColumn();
    //echo("Row - " . $highestRow . ", Col - " . $highestColumn . "<br>");
    $rowData = $sheet->rangeToArray("A2:C" . $highestRow, NULL, TRUE, FALSE);
    for ($row = 0; $row < $highestRow; $row++) {
        //  Read a row of data into an array
        //$unix_date = ($rowData[$row][0] - 25569) * 86400;
        if(isset($rowData[$row][0])) // stop before the last entry
        {
            if($rowData[$row][2] == 'Bye')
            {
                $memberID = (10000+$row);
            }
            else
            {
                $memberID = (int)GetMemberID($rowData[$row][2]);
            }
            $sql = "Insert INTO tournament_players (draw_pos, round_no, tourn_id, ranknum, memb_id, fullname) Values (" . ($row+1) . ", 1, " . $tourn_id . ", " . $rowData[$row][0] . ", " . $memberID . ", '" . $rowData[$row][2] . "')";
            //echo($sql . "<br>");
            $update = mysql_query($sql, $connvbsa) or die(mysql_error());
        }
    }

    $rowData_2 = $sheet->rangeToArray("D2:F" . $highestRow, NULL, TRUE, FALSE);
    for ($row_2 = 0; $row_2 < $highestRow; $row_2++) {
        //  Read a row of data into an array
        //$unix_date = ($rowData[$row][0] - 25569) * 86400;
        if(isset($rowData_2[$row_2][0])) // stop before the last entry
        {
            // no Byes carried forward.
            $sql_2 = "Insert INTO tournament_players (draw_pos, round_no, tourn_id, ranknum, memb_id, fullname) Values (" . ($row_2+1) . ", 2, " . $tourn_id . ", " . $rowData_2[$row_2][0] . ", " . (int)GetMemberID($rowData_2[$row_2][2]) . ", '" . $rowData_2[$row_2][2] . "')";
            $update = mysql_query($sql_2, $connvbsa) or die(mysql_error());
        }
    }


    //header("Location: tournament_draw.php");
}

//if they DID upload a file...


if(isset($_FILES['excel_file']['name']))
{
    if(!$_FILES['excel_file']['error'])
    {
        $new_file_name = strtolower($_FILES['excel_file']['tmp_name']); //rename file
        if($_FILES['excel_file']['size'] > (1024000)) //can't be larger than 1 MB
        {
            $valid_file = false;
            echo('Your file\'s size is too large.' . "<br>");
        }
        else
        {
            $valid_file = true;
        }
         //echo($_FILES['excel_file']['name'] . " 2<br>");
        if($valid_file)
        {
            $sql = "Select * From tournament_players";
            //echo($sql . "<br>");
            $result_tourn_players = mysql_query($sql, $connvbsa) or die(mysql_error());
            $row_count = $result_tourn_players->num_rows;
            //echo($row_count . "<br>");
            $score_data_exists = false;
            while($score_data = $result_tourn_players->fetch_assoc())
            {
                if($score_data['fullname'] != '')
                {
                    $score_data_exists = true;
                    break;
                }
            }
            $score_data_exists = false; // temp setting until in production
            if($score_data_exists)
            {
                echo("<center>");
                echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The tournament player list already contains data.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The tournament player list cannot be changed.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("</table>");
                echo("</center>");
            }
            else
            {
                $season = 'S1';
                //echo("Here<br>");
                ImportExcel($_FILES['excel_file']['tmp_name'], $current_year, $season, $tourn_id);
                echo('<br><br><center>Your file '. $_FILES['excel_file']['name'] . " has been uploaded and imported.</center><br>");
                echo("<br>");
                echo('<div align="center" class="greenbg"><a href="test_draw_order.php?tourn_id=' . $tourn_id . '" style="width: 300px;">Return to Tournament Draw</a></div>');
                echo("<br>");

            }
        }
    }
    else
    {
        echo('Your upload triggered the following error:  '. $_FILES['excel_file']['error'] . "<br>");
    }
}
else
{
    echo("<form action='upload_tourn_data.php?tourn_id=" . $tourn_id . "' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload Tournament Data (Tournament Id " . $tourn_id . ")</h2></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Select the Excel File to upload:</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='file' name='excel_file' size='25' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><input type='submit' name='submit' value='Upload' /></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center><b><font color=red>Please note: any existing tournamrnt data will be deleted.</b></font></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("</table>");
    echo("</center>");
    echo("</form>");
}

?>

</body>
</html>