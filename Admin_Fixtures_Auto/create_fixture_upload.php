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

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
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

function ImportExcel($filename, $current_year, $season) 
{
    global $connvbsa;
    global $database_connvbsa;
    mysql_select_db($database_connvbsa, $connvbsa);
    // check if any data for this season exists
    $sql = "Select season From tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
    //echo($sql . "<br>");
    $result_fixture_season = mysql_query($sql, $connvbsa) or die(mysql_error());
    //$result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client)); 
    $num_rows = $result_fixture_season->num_rows;
    //echo($num_rows . "<br>");
    $data_exists = false;
    if($num_rows > 0)
    {
        $data_exists = true;
    } 
    if($data_exists)
    {
        $sql_delete = "Delete from tbl_fixtures Where season = '" . $season . "' and year = " . $current_year;
        //echo($sql_delete . "<br>");
        //$update_delete = $dbcnx_client->query($sql_delete);
        $update_delete = mysql_query($sql_delete, $connvbsa) or die(mysql_error());
        if(! $update_delete )
        {
          die("Could not delete season data: " . mysqli_error($dbcnx_client));
        }
    }
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
    //echo($filename . "<br>");
    $inputFileType = "Xlsx";
    $inputFileName = $filename; 
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $sheet = $spreadsheet->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestDataColumn();
    //echo("Row - " . $highestRow . ", Col - " . $highestColumn . "<br>");
    $rowData = $sheet->rangeToArray("A1:" . $highestColumn . $highestRow, NULL, TRUE, FALSE);
    for ($row = 1; $row < $highestRow; $row++) {
        //  Read a row of data into an array
        $unix_date = ($rowData[$row][0] - 25569) * 86400;
        if($rowData[$row][1] != '') // stop before the last entry
        {
            //echo($rowData[$row][1] . "<br>");
            $sql = "Insert INTO tbl_fixtures (date, type, grade, round, fix1home, fix1away, fix2home, fix2away, fix3home, fix3away, fix4home, fix4away, fix5home, fix5away, fix6home, fix6away, fix7home, fix7away, year, season, team_grade, dayplayed) Values ('" . date('Y-m-d', $unix_date) . "', '" . $rowData[$row][1] ."', '" . $rowData[$row][2] . "', " . $rowData[$row][3] .", '" . $rowData[$row][4] . "', '" . $rowData[$row][5] . "', '" . $rowData[$row][6] . "', '" . $rowData[$row][7] . "', '" . $rowData[$row][8] . "', '" . $rowData[$row][9] . "', '" . $rowData[$row][10] . "', '" . $rowData[$row][11] . "', '" . $rowData[$row][12] . "', '" . $rowData[$row][13] . "', '" . $rowData[$row][14] . "', '" . $rowData[$row][15] . "', '" . $rowData[$row][16] . "', '" . $rowData[$row][17] . "', '" . $rowData[$row][18] . "', '" . $rowData[$row][19] . "', '" . $rowData[$row][20] . "', '" . $rowData[$row][21] . "')";
            //echo($sql . "<br>");
            //$update = $dbcnx_client->query($sql);
            $update = mysql_query($sql, $connvbsa) or die(mysql_error());
            if(!$update )
            {
                die("Could not update data: " . mysqli_error($dbcnx_client));
            } 
        }
    }
    header("Location: AA_scores_index_grades.php?season=S2");
}

//if they DID upload a file...


if($_FILES['excel_file']['name'])
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
            $sql = "Select score_1, season, year From tbl_scoresheet Where season = '" . $season . "' and year = " . $current_year;
            //echo($sql . "<br>");
            //$result_fixture_season = $dbcnx_client->query($sql) or die("Couldn't execute season check. " . mysqli_error($dbcnx_client)); 
            $result_fixture_season = mysql_query($sql, $connvbsa) or die(mysql_error());
            $row_count = $result_fixture_season->num_rows;
            //echo($row_count . "<br>");
            $score_data_exists = false;
            while($score_data = $result_fixture_season->fetch_assoc())
            {
                if($score_data['score_1'] != '')
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
                echo("<td align=center>The fixture list already contains scoring data.</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>&nbsp;</td>");
                echo("</tr>");
                echo("<tr>");
                echo("<td align=center>The fixture list cannot be changed.</td>");
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
                //echo("Here<br>");
                ImportExcel($_FILES['excel_file']['tmp_name'], $current_year, $season);
                echo('<br><br><center>Your file '. $_FILES['excel_file']['name'] . " has been uploaded and imported.</center><br>");
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
    echo("<form action='create_fixture_upload.php?season=" . $season . "' method='post' enctype='multipart/form-data'>");
    echo("<center>");
    echo("<table border='0' align='center' cellpadding='0' cellspacing='0'>");
    echo("<tr>");
    echo("<td align=center><h2>Upload Fixtures List</h2></td>");
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
    echo("<td align=center><b><font color=red>Please note: any existing fixture data for season " . $season . ", in " . $current_year . " will be deleted.</b></font></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>&nbsp;</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td align=center>Please ensure the upload file contains the whole seasons fixtures.</td>");
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
    echo("<td align=center>You can download a template file <a href='Import_Fixtures_Template.xlsx'>here.</a></td>");
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